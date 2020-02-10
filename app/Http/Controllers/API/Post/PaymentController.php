<?php
/**
 * Theqqa - Geo Classified Ads Software
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace App\Http\Controllers\API\Post;

use App\Http\Requests\PackageRequest;
use App\Models\Payment as PaymentModel;
use App\Models\Post;
use App\Models\Package;
use App\Models\Scopes\StrictActiveScope;
use App\Models\Scopes\VerifiedScope;
use App\Models\Scopes\ReviewedScope;
use App\Http\Controllers\API\FrontController;
use App\Http\Requests\Request;
use App\Models\ServicePaytabs;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Helpers\Payment as PaymentHelper;
use App\Http\Controllers\API\Post\Traits\PaymentTrait;

class PaymentController extends FrontController
{
	use PaymentTrait;
	
	public $request;
	public $data;
	public $msg = [];
	public $uri = [];
	public $packages;
	public $paymentMethods;
	
	/**
	 * PackageController constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		
		// From Laravel 5.3.4 or above
		$this->middleware(function ($request, $next) {
			$this->request = $request;
			$this->commonQueries();
			
			return $next($request);
		});
	}
	
	/**
	 * Common Queries
	 */
	public function commonQueries()
	{
		// Messages
		if (getSegment(2) == 'create') {
			$this->msg['post']['success'] = t("Your ad has been created.");
		} else {
			$this->msg['post']['success'] = t("Your ad has been updated.");
		}
		$this->msg['checkout']['success'] = t("We have received your payment.");
		$this->msg['checkout']['cancel'] = t("We have not received your payment. Payment cancelled.");
		$this->msg['checkout']['error'] = t("We have not received your payment. An error occurred.");
		
		// Set URLs
		if (getSegment(2) == 'create') {
			$this->uri['previousUrl'] = config('app.locale') . '/posts/create/#entryToken/payment';
			$this->uri['nextUrl'] = config('app.locale') . '/posts/create/#entryToken/finish';
			$this->uri['paymentCancelUrl'] = url(config('app.locale') . '/posts/create/#entryToken/payment/cancel');
			$this->uri['paymentReturnUrl'] = url(config('app.locale') . '/posts/create/#entryToken/payment/success');
		} else {
			$this->uri['previousUrl'] = config('app.locale') . '/posts/#entryId/payment';
			$this->uri['nextUrl'] = config('app.locale') . '/' . trans('routes.v-post', ['slug' => '#title', 'id' => '#entryId']);
			$this->uri['paymentCancelUrl'] = url(config('app.locale') . '/posts/#entryId/payment/cancel');
			$this->uri['paymentReturnUrl'] = url(config('app.locale') . '/posts/#entryId/payment/success');
		}
		
		// Payment Helper init.
		PaymentHelper::$country = collect(config('country'));
		PaymentHelper::$lang = collect(config('lang'));
		PaymentHelper::$msg = $this->msg;
		PaymentHelper::$uri = $this->uri;
		
		// Get Packages
		$this->packages = Package::trans()->applyCurrency()->with('currency')->orderBy('lft')->get();
		view()->share('packages', $this->packages);
		view()->share('countPackages', $this->packages->count());
		
		// Keep the Post's creation message
		// session()->keep(['message']);
		if (getSegment(2) == 'create') {
			if (session()->has('tmpPostId')) {
				session()->flash('message', t('Your ad has been created.'));
			}
		}
	}
	
	/**
	 * Show the form the create a new ad post.
	 *
	 * @param $postIdOrToken
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getForm($postIdOrToken)
	{
		$data = [];
		
		// Get Post
		if (getSegment(2) == 'create') {
			if (!session()->has('tmpPostId')) {
				return redirect('posts/create');
			}
			$post = Post::with(['latestPayment' =>  function ($builder) {
				$builder->with(['package'])->withoutGlobalScope(StrictActiveScope::class);
			}])
				->withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
				->where('id', session('tmpPostId'))
				->where('tmp_token', $postIdOrToken)
				->first();
		} else {
			$post = Post::with(['latestPayment' =>  function ($builder) {
				$builder->with(['package'])->withoutGlobalScope(StrictActiveScope::class);
			}])
				->withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
				->where('user_id', auth()->user()->id)
				->where('id', $postIdOrToken)
				->first();
		}
		
		if (empty($post)) {
			abort(404);
		}
		
		view()->share('post', $post);
		
		// Meta Tags
		if (getSegment(2) == 'create') {
			MetaTag::set('title', getMetaTag('title', 'create'));
			MetaTag::set('description', strip_tags(getMetaTag('description', 'create')));
			MetaTag::set('keywords', getMetaTag('keywords', 'create'));
		} else {
			MetaTag::set('title', t('Update My Ad'));
			MetaTag::set('description', t('Update My Ad'));
		}
		
		return view('post.packages', $data);
	}
	
	/**
	 * Store a new ad post.
	 *
	 * @param $postIdOrToken
	 * @param PackageRequest $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function postForm($postIdOrToken, PackageRequest $request)
	{

		// Get Post
		if (getSegment(2) == 'create') {
			if (!session()->has('tmpPostId')) {
				return redirect('posts/create');
			}
			$post = Post::with(['latestPayment'])
				->withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
				->where('id', session('tmpPostId'))
				->where('tmp_token', $postIdOrToken)
				->first();

		} else {
			$post = Post::with(['latestPayment'])
				->withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
				->where('user_id', auth()->user()->id)
				->where('id', $postIdOrToken)
				->first();

		}
		
		if (empty($post)) {
			abort(404);
		}
		
		// MAKE A PAYMENT (IF NEEDED)
		
		// Check if the selected Package has been already paid for this Post
		$alreadyPaidPackage = false;
		if (!empty($post->latestPayment)) {
			if ($post->latestPayment->package_id == $request->input('package_id')) {
				$alreadyPaidPackage = true;
			}
		}
		
		// Check if Payment is required
		$package = Package::find($request->input('package_id'));
		if (!empty($package)) {
			if ($package->price > 0 && $request->filled('payment_method_id') && !$alreadyPaidPackage) {
				// Send the Payment
				return $this->sendPayment($request, $post);
			}
		}
		
		// IF NO PAYMENT IS MADE (CONTINUE)
		
		// Get the next URL
		if (getSegment(2) == 'create') {
			$request->session()->flash('message', t('Your ad has been created.'));
			$nextStepUrl = config('app.locale') . '/posts/create/' . $postIdOrToken . '/finish';
		} else {
			flash(t("Your ad has been updated."))->success();

			$nextStepUrl = config('app.locale') . '/' . $post->uri . '?preview=1';
		}
		
		// Redirect
		return redirect($nextStepUrl);
	}
   /** mogaz payment**/
    public function getFormmogaz($postIdOrToken)
    {


        $contactForm = Session::get('contactForm');
        view()->share('contactForm', $contactForm);
        $data = [];

        // Get Post
       {
            $post = Post::with(['latestPayment' =>  function ($builder) {
                $builder->with(['package'])->withoutGlobalScope(StrictActiveScope::class);
            }])
                ->withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
                ->where('id', $postIdOrToken)
                ->first();
        }
        if (empty($post)) {
            abort(404);
        }

        view()->share('post', $post);
        return view('post.mogazpackages', $data);
    }
    public function postFormmogaz($postIdOrToken, PackageRequest $request)
    {

        // Get Post
            $post = Post::with(['latestPayment'])
                ->withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
                ->where('id', $postIdOrToken)
                ->first();


        if (empty($post)) {
            abort(404);
        }

        // MAKE A PAYMENT (IF NEEDED)

        // Check if the selected Package has been already paid for this Post
        $alreadyPaidPackage = false;
        if (!empty($post->latestPayment)) {
            if ($post->latestPayment->package_id == $request->input('package_id')) {
                $alreadyPaidPackage = true;
            }
        }

        // Check if Payment is required
        $package = Package::find($request->input('package_id'));
        if (!empty($package)) {
            if ($package->price > 0 && $request->filled('payment_method_id') && !$alreadyPaidPackage) {
                // Send the Payment
                return $this->sendPayment($request, $post);
            }
        }

        // IF NO PAYMENT IS MADE (CONTINUE)

        flash(t("Your message has been sent to our moderators. Thank you"))->success();
            $nextStepUrl =config('app.locale') . '/';
        return redirect($nextStepUrl);
    }
    /**
     * @param ContactRequest $request
     * @return \App\Http\Controllers\Post\PaymentController|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function booking_post_car($postIdOrToken, PackageRequest $request)
    {

        // Get Post
        $post = Post::with(['latestPayment'])
            ->withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
            ->where('id', $postIdOrToken)
            ->first();


        if (empty($post)) {
            abort(404);
        }

        if ($request->payment_method_id == 2 && $request->package_id != 5 ){
//            $output_file="test.jpg";
//            $ifp = fopen( $output_file, 'wb' );
//
//
//            // we could add validation here with ensuring count( $data ) > 1
//            fwrite( $ifp, base64_decode( $request->bank_transfer_in ) );
//
//            // clean up the file resource
//            fclose( $ifp );
//            //for booking bank image
//            $extension_booking_bank_image = getUploadedFileExtension($output_file);
//
//            if (empty($extension_booking_bank_image)) {
//                $extension_booking_bank_image = 'jpg';
//            }
//
//            // Make the image
//            $booking_bank_image = Image::make($output_file)->resize(400, 400, function ($constraint) {
//                $constraint->aspectRatio();
//            });
//
//            // Generate a filename.
//            $filename_booking_bank_image = md5($output_file. time()) . '.' . $extension_booking_bank_image;
//            $destination_path = 'app/booking';
//            // Store the image on disk.
//            Storage::disk('public')->put($destination_path . '/' . $filename_booking_bank_image, $booking_bank_image->stream());


            $extension_booking_bank_image = getUploadedFileExtension($request->bank_transfer);
            if (empty($extension_booking_bank_image)) {
                $extension_booking_bank_image = 'jpg';
            }
            // Make the image
            $booking_bank_image = Image::make($request->bank_transfer_in)->resize(400, 400, function ($constraint) {
                $constraint->aspectRatio();
            });
            // Generate a filename.
            $filename_booking_bank_image = md5($request->bank_transfer_in. time()) . '.' . $extension_booking_bank_image;
            $destination_path = 'app/booking';
            // Store the image on disk.
            Storage::disk('public')->put($destination_path . '/' . $filename_booking_bank_image, $booking_bank_image->stream());
            $paymentInfo = [

                'post_id'           =>  $post->id,
                'user_id'           =>   auth()->user()->id,
                'price'           =>   $request->post_booking_price,
                'package_id'        =>  $request->package_id,
                'payment_method_id' => $request->payment_method_id,
                'transaction_id'    => t('bank_transfer'),
                'active'               => 0,
                'image'     => $filename_booking_bank_image ,
            ];


            // Save the payment
            $payment = new PaymentModel($paymentInfo);
            $payment->save();
            // Successful transaction
            flash(t("You will be assured of bank transfer and confirmation of your booking by your email and by massages on Theqqa"))->success();
            return response()->json([
                'status' => 'success',
                'massage' => t("You will be assured of bank transfer and confirmation of your booking by your email and by massages on Theqqa"),
            ]);
//            $nextStepUrl =config('app.locale') . '/';
//            return redirect($nextStepUrl);
        }
        else{



            if ($request->paytabs == 1){
                $package = \App\Models\Package::find( $request->package_id);
                require_once 'paytabs.php';

                $pt = new \App\Http\Controllers\API\paytabs(env('PAYTABS_EMAIL'), env('PAYTABS_SECRET_KEY'));

                $result =    $pt->create_pay_page(array(
                    //Customer's Personal Information
                    'merchant_email' =>   env('PAYTABS_EMAIL'),
                    'secret_key' => env('PAYTABS_SECRET_KEY'),
                    'cc_first_name' => $request->card_first_name,          //This will be prefilled as Credit Card First Name
                    'cc_last_name' =>  $request->card_last_name,            //This will be prefilled as Credit Card Last Name
                    'cc_phone_number' => "966",
                    'phone_number' => $request->card_phone,
                    'email' => $request->card_email,

                    //Customer's Billing Address (All fields are mandatory)
                    //When the country is selected as USA or CANADA, the state field should contain a String of 2 characters containing the ISO state code otherwise the payments may be rejected.
                    //For other countries, the state can be a string of up to 32 characters.
                    'billing_address' => "4410 طريق الدمام - حي المؤنسية",
                    'city' => "Riyadh",
                    'state' => "Riyadh",
                    'postal_code' => "13253",
                    'country' => "SAU",

                    //Customer's Shipping Address (All fields are mandatory)
                    'address_shipping' => "4410 طريق الدمام - حي المؤنسية",
                    'city_shipping' => "Riyadh",
                    'state_shipping' => "Riyadh",
                    'postal_code_shipping' => "13253",
                    'country_shipping' => "SAU",

                    //Product Information
                    "products_per_title" => $package->name,   //Product title of the product. If multiple products then add “||” separator
                    'quantity' => "1",                                    //Quantity of products. If multiple products then add “||” separator
                    'unit_price' =>  $request->post_booking_price,                                  //Unit price of the product. If multiple products then add “||” separator.
                    "other_charges" => "00.00",                                     //Additional charges. e.g.: shipping charges, taxes, VAT, etc.

                    'amount' =>   $request->post_booking_price,                                          //Amount of the products and other charges, it should be equal to: amount = (sum of all products’ (unit_price * quantity)) + other_charges
                    'discount'=>"0",                                                //Discount of the transaction. The Total amount of the invoice will be= amount - discount
                    'currency' => "SAR",                                            //Currency of the amount stated. 3 character ISO currency code



                    //Invoice Information
                    'title' => $package->name,               // Customer's Name on the invoice
                    "msg_lang" => "Arabic",                 //Language of the PayPage to be created. Invalid or blank entries will default to English.(Englsh/Arabic)
                    "reference_no" =>$request->id,        //Invoice reference number in your system

                    //Website Information
                    "site_url" => "https://www.theqqa.com",      //The requesting website be exactly the same as the website/URL associated with your PayTabs Merchant Account
                    "return_url" => 'https://www.theqqa.com/api/verify_payment_booking_paytabs',
                    "cms_with_version" => "API USING PHP",

                    "paypage_info" => "1"
                ));

                // echo "FOLLOWING IS THE RESPONSE: <br />";
                // print_r ($result);
            }
            
     $contactForm = $request->all();
        
            $dataservice = json_encode($contactForm);
            $p_service = new ServicePaytabs();
            $p_service->p_id=$result->p_id;
            $p_service->service_data=$dataservice;
            $p_service->save();
            // Redirection
            return response()->json([
                'status' => 'success',
                'data' => 'saved data',
                'result'=>$result->payment_url
            ]);
//            // Check if Payment is required
//            $package = Package::find($request->input('package_id'));
//            if (!empty($package)) {
////            if ($package->price > 0 && $request->filled('payment_method_id') && !$alreadyPaidPackage) {
//                // Send the Payment
//
//                return $this->sendPayment($request, $post);
////            }
//            }
//            // IF NO PAYMENT IS MADE (CONTINUE)
//
//            flash(t("We regret that we can not process your request at this time. Our engineers have been notified of this problem and will try to resolve it as soon as possible."))->error();
//            $nextStepUrl =config('app.locale') . '/';
//            return redirect($nextStepUrl);

        }


    }
}
