<?php
/**
 * Theqqa - Geo Classified Ads Software
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace App\Http\Controllers\Post;

use App\Http\Requests\PackageRequest;
use App\Models\Post;
use App\Models\Permission;
use App\Models\User;
use App\Models\City;
use App\Models\Package;
use App\Models\ImageService;
use App\Models\Message;
use App\Notifications\PaymentNotification;
use App\Models\Scopes\StrictActiveScope;
use App\Models\Scopes\VerifiedScope;
use App\Models\Scopes\ReviewedScope;
use App\Http\Controllers\FrontController;
// use App\Http\Requests\Request;
use Illuminate\Support\Facades\Session;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Helpers\Payment as PaymentHelper;
use App\Http\Controllers\Post\Traits\PaymentTrait;
use App\Models\Payment as PaymentModel;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use App\Notifications\FormSent;
use App\Notifications\User_Mail;
use Illuminate\Support\Facades\Notification;
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

        if ($request->payment_method_id == 2 && $request->package_id != 5){

            //for booking bank image
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
            $package = Package::find($request->input('package_id'));
            $paymentInfo = [

                'post_id'           =>  $post->id,
                'user_id'           =>  auth()->user()->id ,
                'price'            =>  $package->price,
                'package_id'        =>  $request->package_id,
                'payment_method_id' => $request->payment_method_id,
                'transaction_id'    => t('bank_transfer'),
                'active'               => 0,
                'image'     => $filename_booking_bank_image ,
            ];

            // Check the uniqueness of the payment
            if($post->id != NULL){
                $paymentmodel = PaymentModel::where('post_id', $post->id)
                    ->where('user_id',auth()->user()->id)
                    ->where('package_id', $request->package_id)
                    ->where('payment_method_id', $request->payment_method_id)
                    ->first();
            }
            // Save the payment
            $payment =!empty($paymentmodel)?$paymentmodel: new PaymentModel($paymentInfo);
            $payment->save();
//            if(!empty($paymentmodel)){
//                // Successful transaction
//                flash(t("You will be assured of bank transfer and confirmation of your booking by your email and by massages on Theqqa"))->success();
//
//                $nextStepUrl =config('app.locale') . '/';
//            }
//            else{
                // Successful transaction
                flash(t("You will be assured of bank transfer and confirmation of your booking by your email and by massages on Theqqa"))->success();

                $nextStepUrl =config('app.locale') . '/';
//            }


        }
        else {
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
        }
		// Redirect
		return redirect($nextStepUrl);
	}
   /** mogaz payment**/
    public function getFormmogaz($postIdOrToken)
    {
//        $contactForm = Session::get('contactForm');
        $contactForm=  session()->get('contactForm');


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


        if ($request->payment_method_id == 2 && $request->package_id != 5 ){

            $contactForm = Session::get('contactForm');

//            $dataservice = implode(",",$contactForm);
            $dataservice = json_encode($contactForm);
if(!empty($request->bank_transfer_in)) {
    //for booking bank image
    $extension_booking_bank_image = getUploadedFileExtension($request->bank_transfer_in);

    if (empty($extension_booking_bank_image)) {
        $extension_booking_bank_image = 'jpg';
    }
    // Make the image
    $booking_bank_image = Image::make($request->bank_transfer_in)->resize(400, 400, function ($constraint) {
        $constraint->aspectRatio();
    });

    // Generate a filename.
    $filename_booking_bank_image = md5($request->bank_transfer_in . time()) . '.' . $extension_booking_bank_image;
    $destination_path = 'app/booking';
    // Store the image on disk.
    Storage::disk('public')->put($destination_path . '/' . $filename_booking_bank_image, $booking_bank_image->stream());
}
            $package = Package::find($request->input('package_id'));
            $paymentInfo = [

                'post_id'           =>  $post->id,
                'user_id'           =>  auth()->user()->id ,
                'price'             =>  $package->price,
                'package_id'        =>  $request->package_id,
                'payment_method_id' => $request->payment_method_id,
                'transaction_id'    => t('bank_transfer'),
                'active'            => 0,
                'date_service'      => $dataservice,
                'image'     => isset($filename_booking_bank_image)?$filename_booking_bank_image:'' ,
            ];

            // Check the uniqueness of the payment
            if($post->id != NULL){
                $paymentmodel = PaymentModel::where('post_id', $post->id)
                    ->where('user_id',auth()->user()->id)
                    ->where('package_id', $request->package_id)
                    ->where('payment_method_id', $request->payment_method_id)
                    ->first();
            }




            // Save the payment
            $payment =!empty($paymentmodel)?$paymentmodel: new PaymentModel($paymentInfo);
            $payment->save();
              $isJson = json_decode($dataservice);
  
              $admins = User::permission(Permission::getStaffPermissions())->get();
        Notification::send($admins, new FormSent($isJson));
            
                 
//            if(!empty($paymentmodel)){
//                // Successful transaction
//                flash(t("You will be assured of bank transfer and confirmation of your booking by your email and by massages on Theqqa"))->success();
//
//                $nextStepUrl =config('app.locale') . '/';
//            }
//            else{
            // Successful transaction
            if (  $request->package_id =='21' or  $request->package_id == '22'){
                flash(t("You will be contacted by email or messages through Theqqa website"))->success();
            }else{
                flash(t("You will be assured of bank transfer and confirmation of your booking by your email and by massages on Theqqa"))->success();
            }


            $nextStepUrl =config('app.locale') . '/';
//            }

            return redirect($nextStepUrl);
        }
        else {
         $contactForm = Session::get('contactForm');

            $dataservice = json_encode($contactForm);
            $package = \App\Models\Package::find($request->order_id);
            $paymentInfo = [

                'post_id'           => 0,
                'user_id'           =>auth()->user()->id,
                'price'            =>  $package->price,
                'package_id'        =>  $request->order_id,
                'payment_method_id' => '3',
                'transaction_id'    => t('Paytabs'),
                'active'               => 0,
                 'date_service'      =>$dataservice,
                'image'     => '' ,
                'pt_transaction_id' =>$request->transaction_id,
                'image'     => '' ,
            ];

 
            // Save the payment
            $payment =!empty($paymentmodel)?$paymentmodel: new \App\Models\Payment($paymentInfo);
            $payment->save();
            flash(t("we will send confirmation of this servce by your email and by massages on Theqqa"))->success();

            // // MAKE A PAYMENT (IF NEEDED)

            // // Check if the selected Package has been already paid for this Post
            // $alreadyPaidPackage = false;
            // if (!empty($post->latestPayment)) {
            //     if ($post->latestPayment->package_id == $request->input('package_id')) {
            //         $alreadyPaidPackage = true;
            //     }
            // }

            // // Check if Payment is required
            // $package = Package::find($request->input('package_id'));

            // if (!empty($package)) {
            //     if ($package->price > 0 && $request->filled('payment_method_id')) {
            //         // Send the Payment
            //         return $this->sendPayment($request, $post);
            //     }
            // }

            // // IF NO PAYMENT IS MADE (CONTINUE)

            // flash(t("We regret that we can not process your request at this time. Our engineers have been notified of this problem and will try to resolve it as soon as possible."))->error();
        }
        $nextStepUrl =config('app.locale') . '/';
        return redirect($nextStepUrl);
    }
    public function booking_car($postIdOrToken)
    {
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
        MetaTag::set('title',  t('Booking the car'));
        MetaTag::set('description', t('Booking the car'));
        MetaTag::set('keywords', t('Booking the car'));
        if (empty($post)) {
            abort(404);
        }

        view()->share('post', $post);
        return view('post.booking_car', $data);

    }

    /**
     * @param ContactRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
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
    //for booking bank image
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
        'user_id'           =>  $request->user_id,
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
     $post_detail= Post::where('id',$post->id)->first();
      $admins = User::permission(Permission::getStaffPermissions())->get();
                        $user_post= User::where('id',$post_detail->user_id)->first();
              
                    Notification::send($admins, new PaymentNotification($payment, $post,$user_post));
    // Successful transaction
    flash(t("You will be assured of bank transfer and confirmation of your booking by your email and by massages on Theqqa"))->success();

    $nextStepUrl =config('app.locale') . '/';
    return redirect($nextStepUrl);
}
else{

    // Check if Payment is required
    $package = Package::find($request->input('package_id'));
    if (!empty($package)) {
//            if ($package->price > 0 && $request->filled('payment_method_id') && !$alreadyPaidPackage) {
        // Send the Payment

        return $this->sendPayment($request, $post);
//            }
    }
    // IF NO PAYMENT IS MADE (CONTINUE)

    flash(t("We regret that we can not process your request at this time. Our engineers have been notified of this problem and will try to resolve it as soon as possible."))->error();
    $nextStepUrl =config('app.locale') . '/';
    return redirect($nextStepUrl);

}


    }
        public function paytabservceDataAjax(Request $request)
    {
              $contactForm = Session::get('contactForm');

            $dataservice = json_encode($contactForm);
            $package = \App\Models\Package::find($request->order_id);
            $paymentInfo = [

                'post_id'           => 0,
                'user_id'           =>auth()->user()->id,
                'price'            =>  $package->price,
                'package_id'        =>  $request->order_id,
                'payment_method_id' => '3',
                'transaction_id'    => t('Paytabs'),
                'active'               => 1,
                'date_service'      =>$dataservice,
                'image'     => '' ,
                'pt_transaction_id' =>$request->transaction_id,
              
            ];

 
            // Save the payment
        $payment =!empty($paymentmodel)?$paymentmodel: new \App\Models\Payment($paymentInfo);
        $payment->save();
       $user =User::where('id',auth()->user()->id)->first();
        $admins = User::permission(Permission::getStaffPermissions())->get();
        // $post =Post::where('id',$post_id)->first();
        
            
        $isJson = json_decode($dataservice);




    if($package->id == '9' or $package->id == '11' or $package->id == '13' or $package->id == '15' or $package->id == '17' or $package->id == '21'  )
    {
    
        $mass=[];
        $contactForm=$isJson;


        if ($contactForm->service_type ==  t( 'maintenance_title') or $contactForm->service_type ==  "maintenance service"){

            if($contactForm->for_mainten == 'no'){
                $maintenance_id=User::Where('id',$contactForm->maintenance_id)->first();
                $car_place=City::Where('id',$contactForm->car_place)->first();
                $mass[] = [
                    t('car_exist') => t('car_out') ,
                    t('Country') => !empty($contactForm->country_name)?$contactForm->country_name:'' ,
                    t('First Name') =>!empty($contactForm->first_name)?$contactForm->first_name:'' ,
                    t('Email') => !empty($contactForm->email)?$contactForm->email:'',
                    t('Owner ID') => !empty( $contactForm->owner_id)? $contactForm->owner_id:'',
                    t('maintenance_center') => !empty( $maintenance_id->name)? $maintenance_id->name:'',
                    t('car_place')  =>  !empty($car_place->name)?$car_place->name:'',
                    t('address')  =>  !empty($contactForm->address)?$contactForm->address:'',
                    t('plate number') => !empty($contactForm->plate_number)?$contactForm->plate_number:'' ,
                    t('serial number') =>  !empty($contactForm->serial_number)?$contactForm->serial_number:'',
                    t('Message') =>  !empty($contactForm->message)?$contactForm->message:'',
                ];
            }else{
                $maintenance_id=User::Where('id',$contactForm->maintenance_id_yes)->first();
                $mass[] = [
                    t('car_exist') => t('car-in') ,
                    t('Country') => !empty($contactForm->country_name)?$contactForm->country_name:'' ,
                    t('First Name') =>!empty($contactForm->first_name)?$contactForm->first_name:'' ,
                    t('Email') => !empty($contactForm->email)?$contactForm->email:'',
                    t('maintenance_center') => !empty( $maintenance_id->name)? $maintenance_id->name:'',
                    t('Message') =>  !empty($contactForm->message)?$contactForm->message:'',
                    t('car url') => ' <a href="' . lurl( (!empty($contactForm->car_url) ? $contactForm->car_url:'SA')) . '">' . (!empty($contactForm->car_url) ? $contactForm->car_url:t('car_in')) . '</a>',

                ];

            }

        }
        elseif ($contactForm->service_type == t( 'shipping_title')or $contactForm->service_type == "shipping service"){
            $shipping_id=User::Where('id',$contactForm->shipping_id)->first();
            $shipping_place=City::Where('id',$contactForm->shipping_place)->first();
            $shipping_place_to=City::Where('id',$contactForm->shipping_place_to)->first();

            if($contactForm->for_shipping == 'no'){
                $mass[] = [
                    t('car_exist') => t('car_out') ,
                    t('Country') => !empty($contactForm->country_name)?$contactForm->country_name:'' ,
                    t('First Name') =>!empty($contactForm->first_name)?$contactForm->first_name:'' ,
                    t('Email') => !empty($contactForm->email)?$contactForm->email:'',
                    t('Owner ID') => !empty( $contactForm->owner_id)? $contactForm->owner_id:'',
                    t('Shipping_center') => !empty( $shipping_id->name)? $shipping_id->name:'',
                    t('shipping_place')  =>  !empty($shipping_place->name)?$shipping_place->name:'',
                    t('address')  =>  !empty($contactForm->address)?$contactForm->address:'',
                    t('shipping_place_to')   =>  !empty($shipping_place_to->name)?$shipping_place_to->name:'',
                    t('address_to_ditail')  =>  !empty($contactForm->address_to)?$contactForm->address_to:'',
                    t('plate number') => !empty($contactForm->plate_number)?$contactForm->plate_number:'' ,
                    t('serial number') =>  !empty($contactForm->serial_number)?$contactForm->serial_number:'',
                    t('Message') =>  !empty($contactForm->message)?$contactForm->message:'',


                ];}else{
                $mass[] = [
                    t('car_exist') => t('car-in') ,
                    t('Country') => !empty($contactForm->country_name)?$contactForm->country_name:'' ,
                    t('First Name') =>!empty($contactForm->first_name)?$contactForm->first_name:'' ,
                    t('Email') => !empty($contactForm->email)?$contactForm->email:'',
                    t('Shipping_center') => !empty( $shipping_id->name)? $shipping_id->name:'',
                    t('shipping_place')  =>  !empty($shipping_place->name)?$shipping_place->name:'',
                    t('shipping_place_to')   =>  !empty($shipping_place_to->name)?$shipping_place_to->name:'',
                    t('address')  =>  !empty($contactForm->address)?$contactForm->address:'',
                    t('address_to_ditail')  =>  !empty($contactForm->address_to)?$contactForm->address_to:'',
                    t('Message') =>  !empty($contactForm->message)?$contactForm->message:'',
                    t('car url') => ' <a href="' . lurl( (!empty($contactForm->car_url) ? $contactForm->car_url:'SA')) . '">' . (!empty($contactForm->car_url) ? $contactForm->car_url:t('car_in')) . '</a>',

                ];
            }
        }
        elseif ($contactForm->service_type == t( 'checking_title') or $contactForm->service_type == "checking service"){

            $shipping_place=City::Where('id',$contactForm->car_place)->first();
            $mass[] = [
                t('car_exist') => t('car-in') ,
                t('Country') => !empty($contactForm->country_name)?$contactForm->country_name:'' ,
                t('First Name') =>!empty($contactForm->first_name)?$contactForm->first_name:'' ,
                t('Email') => !empty($contactForm->email)?$contactForm->email:'',
                t('Phone') =>  !empty($contactForm->phone)?$contactForm->phone:'',
                t('car_place')  =>  !empty($shipping_place->name)?$shipping_place->name:'',
                t('address')  =>  !empty($contactForm->address)?$contactForm->address:'',
                t('checking_date')  =>  !empty($contactForm->checking_date)?$contactForm->checking_date:'',
                t('checking_time')  =>  !empty($contactForm->checking_time)?$contactForm->checking_time:'',
                t('Message') =>  !empty($contactForm->message)?$contactForm->message:'',
                t('car url') => ' <a href="' . lurl( (!empty($contactForm->car_url) ? $contactForm->car_url:'SA')) . '">' . (!empty($contactForm->car_url) ? $contactForm->car_url:t('car_in')) . '</a>',


            ];}
        elseif ($contactForm->service_type == t( 'mogaz service') or $contactForm->service_type == 'mogaz service') {

            if($contactForm->for_mogaz == 'no'){

                $mass[] = [
                    t('car_exist') => t('car-in') ,
                    t('Country') => !empty($contactForm->country_name)?$contactForm->country_name:'' ,
                    t('First Name') =>!empty($contactForm->first_name)?$contactForm->first_name:'' ,
                    t('Email') => !empty($contactForm->email)?$contactForm->email:'',
                    t('Owner ID') => !empty( $contactForm->owner_id)? $contactForm->owner_id:'',
                    t('plate number') => !empty($contactForm->plate_number)?$contactForm->plate_number:'' ,
                    t('serial number') =>  !empty($contactForm->serial_number)?$contactForm->serial_number:'',
                    t('Message') =>  !empty($contactForm->message)?$contactForm->message:'',
                    t('car url') => ' <a href="' . lurl( (!empty($contactForm->car_url) ? $contactForm->car_url:'SA')) . '">' . (!empty($contactForm->car_url) ? $contactForm->car_url:t('car_in')) . '</a>',

                ];
            }else{
               
                $mass[] = [
                    t('car_exist') => t('car-in') ,
                    t('Country') => !empty($contactForm->country_name)?$contactForm->country_name:'' ,
                    t('First Name') =>!empty($contactForm->first_name)?$contactForm->first_name:'' ,
                    t('Email') => !empty($contactForm->email)?$contactForm->email:'',
                    t('Message') =>  !empty($contactForm->message)?$contactForm->message:'',
                    t('car url') => ' <a href="' . lurl( (!empty($contactForm->car_url) ? $contactForm->car_url:'SA')) . '">' . (!empty($contactForm->car_url) ? $contactForm->car_url:t('car_in')) . '</a>',


                ];
            }
        }
        elseif ($contactForm->service_type == t( 'estimation title') or $contactForm->service_type ==  'estimation service' ){
            $mass[] = [
                t('car_exist') => t('car_out') ,
                t('Country') => !empty($contactForm->country_name)?$contactForm->country_name:'' ,
                t('First Name') =>!empty($contactForm->first_name)?$contactForm->first_name:'' ,
                t('Email') => !empty($contactForm->email)?$contactForm->email:'',
                t('First Owner\'s Name') =>(!empty($contactForm->first_owner_name) ? $contactForm->first_owner_name : ""),
                t('Middle Owner\'s Name') =>!empty($contactForm->middle_owner_name) ? $contactForm->middle_owner_name: "",
                t('Last owner\'s name') =>  !empty($contactForm->last_owner_name) ? $contactForm->last_owner_name : "",
                t('Mobile_number') =>  !empty($contactForm->Mobile_number) ? $contactForm->Mobile_number : "",
                t('Car Type') => !empty( $contactForm->car_type)? $contactForm->car_type:'',
                t('Car category') => !empty($contactForm->car_category)?$contactForm->car_category:'' ,
                t('Car brand') =>  !empty($contactForm->car_brand)?$contactForm->car_brand:'',
                t('Year of manufacture') =>  !empty($contactForm->Year_manufacture)?$contactForm->Year_manufacture:'',
                t('Kilometers_car') =>  !empty($contactForm->Kilometers)?$contactForm->Kilometers:'',
                t('Notes') =>  !empty($contactForm->message)?$contactForm->message:'',

            ];
        }
        elseif ($contactForm->service_type == t( 'ownership_title') or $contactForm->service_type == "ownership service"  ){

            $exhibitions_id=user::Where('id',$contactForm->exhibitions_id)->first();
            $exhibitions_place=City::Where('id',$contactForm->exhibitions_place)->first();
            if($contactForm->for_ownership == 'no'){
                $mass[] = [
                    t('car_exist') => t('car_out') ,
                    t('Country') => !empty($contactForm->country_name)?$contactForm->country_name:'' ,
                    t('First Name') =>!empty($contactForm->first_name)?$contactForm->first_name:'' ,
                    t('Email') => !empty($contactForm->email)?$contactForm->email:'',
                    t('seller name') => !empty($contactForm->seller_name)?$contactForm->seller_name:'' ,
                    t('seller ID') => !empty( $contactForm->owner_id)? $contactForm->owner_id:'',
                    t('seller phone') => !empty($contactForm->seller_phone)?$contactForm->seller_phone:'' ,
                    t('purchaser name') =>  !empty($contactForm->purchaser_name)?$contactForm->purchaser_name:'',
                    t('purchaser ID') =>  !empty($contactForm->user_id)?$contactForm->user_id:'',
                    t('purchaser phone') =>  !empty($contactForm->purchaser_phone)?$contactForm->purchaser_phone:'',
                    t('exhibition_name') => !empty( $exhibitions_id->name)? $exhibitions_id->name:'',
                    t('exhibitions_location')  =>  !empty($exhibitions_place->name)?$exhibitions_place->name:'',
                    t('Kilometers_car') => !empty($contactForm->Kilometers)?$contactForm->Kilometers:'' ,
                    t('car price') =>  !empty($contactForm->price)?$contactForm->price:'',
                    t('Message') =>  !empty($contactForm->message)?$contactForm->message:'',
                ];
            }else{
                $mass[] = [
                    t('car_exist') => t('car-in') ,
                    t('Country') => !empty($contactForm->country_name)?$contactForm->country_name:'' ,
                    t('First Name') =>!empty($contactForm->first_name)?$contactForm->first_name:'' ,
                    t('Email') => !empty($contactForm->email)?$contactForm->email:'',
                    t('seller ID') => !empty( $contactForm->owner_id)? $contactForm->owner_id:'',
                    t('seller phone') => !empty($contactForm->seller_phone)?$contactForm->seller_phone:'' ,
                    t('purchaser name') =>  !empty($contactForm->purchaser_name)?$contactForm->purchaser_name:'',
                    t('purchaser ID') =>  !empty($contactForm->user_id)?$contactForm->user_id:'',
                    t('purchaser phone') =>  !empty($contactForm->purchaser_phone)?$contactForm->purchaser_phone:'',
                    t('Kilometers_car') => !empty($contactForm->Kilometers)?$contactForm->Kilometers:'' ,
                    t('car price') =>  !empty($contactForm->price)?$contactForm->price:'',
                    t('Message') =>  !empty($contactForm->message)?$contactForm->message:'',
                    t('car url') => ' <a href="' . lurl( (!empty($contactForm->car_url) ? $contactForm->car_url:'SA')) . '">' . (!empty($contactForm->car_url) ? $contactForm->car_url:t('car_in')) . '</a>',


                ];
            }
        }

        for ($i = 0; $i < count($admins); $i++) {
            $message = new Message();

            $message->post_id = '0';
            $message->from_user_id = !empty($user) ? $user->id : 0;
            $message->to_user_id =  !empty($admins['0']['id'])?$admins['0']['id']:'' ;
            $message->to_name = !empty($admins['0']['name'])?$admins['0']['name']:'';
            $message->to_email = !empty($admins['0']['email'])?$admins['0']['email']:'';
            $message->to_phone = !empty($admins['0']['phone'])?$admins['0']['phone']:'';
            $message->from_name =!empty($user)? $user->name : 0;
            $message->from_email = !empty($user) ? $user->email : 0;
            $message->from_phone = !empty($user) ? $user->phone : 0;
            $message->id_code = $contactForm->id_code ;
            $message->subject = !empty($contactForm->service_type)?$contactForm->service_type:'';
            $message->message = json_encode($mass);
            if ($contactForm->service_type == t( 'ownership_title')  or $contactForm->service_type == "ownership service" ){
                if($contactForm->for_ownership == 'no'){

                    $driving_license_image=  ImageService::where('token',$contactForm->id_code)->where('image_title', 'driving_license_image')->first();
                    $purchaser_id_image= ImageService::where('token',$contactForm->id_code)->where('image_title', 'purchaser_id_image')->first();
                    $seller_id_image=   ImageService::where('token',$contactForm->id_code)->where('image_title', 'seller_id_image')->first();
                    $image_car_arr = ImageService::where('token',$contactForm->id_code)->where('image_title', 'car_Pictures')->first();
                    $message->image_car_arr=!empty($image_car_arr->image_code)? $image_car_arr->image_code:'';
                    $message->image_driving_license=!empty($driving_license_image->image_code)?$driving_license_image->image_code:'';
                    $message->image_purchaser_id=!empty($purchaser_id_image->image_code)?$purchaser_id_image->image_code:'';
                    $message->image_seller_id=!empty($seller_id_image->image_code)?$seller_id_image->image_code:'';

                }else{
                    $driving_license_image=  ImageService::where('token',$contactForm->id_code)->where('image_title', 'driving_license_image')->first();
                    $purchaser_id_image= ImageService::where('token',$contactForm->id_code)->where('image_title', 'purchaser_id_image')->first();
                    $message->image_driving_license=!empty($driving_license_image->image_code)?$driving_license_image->image_code:'';
                    $message->image_purchaser_id=!empty($purchaser_id_image->image_code)?$purchaser_id_image->image_code:'';

                }

            }

            if  ($contactForm->service_type == t( 'shipping_title') or $contactForm->service_type == "shipping service"){

//                $image_car_arr=Session::get('filename_car_Pictures_arr')[0];


                $image_car_arr = ImageService::where('token',$contactForm->id_code)->where('image_title', 'car_Pictures')->first();
                $message->image_car_arr=!empty($image_car_arr->image_code)? $image_car_arr->image_code:'';


            }

            if  ($contactForm->service_type == t( 'estimation title')  or $contactForm->service_type ==  'estimation service' ){

                $image_car_arr = ImageService::where('token',$contactForm->id_code)->first();

                $message->image_car_arr=!empty($image_car_arr->image_code)? $image_car_arr->image_code:'';


            }
        }



        // Save
        $message->save();

        // Save and Send user's resume


        if ($message->subject == t( 'shipping_title') or $contactForm->service_type == "shipping service")
        {

            $messageshipping = new Message();
            $adminsshipping = User::where('id',$contactForm->shipping_id)->get();
            $messageshipping->post_id = '0';
            $messageshipping->from_user_id =!empty($user) ? $user->id : 0;
            $messageshipping->to_user_id =  !empty($adminsshipping['0']['id'])?$adminsshipping['0']['id']:'' ;
            $messageshipping->to_name = !empty($adminsshipping['0']['name'])?$adminsshipping['0']['name']:'';
            $messageshipping->to_email = !empty($adminsshipping['0']['email'])?$adminsshipping['0']['email']:'';
            $messageshipping->to_phone = !empty($adminsshipping['0']['phone'])?$adminsshipping['0']['phone']:'';
            $messageshipping->from_name =!empty($user)? $user->name: 0;
            $messageshipping->from_email = !empty($user)? $user->email : 0;
            $messageshipping->from_phone = !empty($user) ? $user->phone : 0;
            $messageshipping->id_code = $contactForm->id_code ;
            $messageshipping->subject = t( 'shipping_title') ;
            $messageshipping->message = json_encode($mass);
            if  ($contactForm->service_type == t( 'shipping_title') or $contactForm->service_type == "shipping service"){

                $image_car_arr = ImageService::where('token',$contactForm->id_code)->where('image_title', 'car_Pictures')->first();
                $messageshipping->image_car_arr=!empty($image_car_arr->image_code)? $image_car_arr->image_code:'';


            }
            // Save
            $messageshipping->save();
        }
        if ($message->subject ==  t( 'maintenance_title') or $contactForm->service_type ==  "maintenance service" )
        {
            $messagemaintenance = new Message();
            if($contactForm->for_mainten == 'no'){
                $adminsmaintenance = User::where('id',$contactForm->maintenance_id)->get();
            }else{
                $adminsmaintenance = User::where('id',$contactForm->maintenance_id_yes)->get();
            }


            $messagemaintenance->post_id = '0';
            $messagemaintenance->from_user_id =!empty($user) ? $user->id : 0;
            $messagemaintenance->to_user_id =  !empty($adminsmaintenance['0']['id'])?$adminsmaintenance['0']['id']:'' ;
            $messagemaintenance->to_name = !empty($adminsmaintenance['0']['name'])?$adminsmaintenance['0']['name']:'';
            $messagemaintenance->to_email = !empty($adminsmaintenance['0']['email'])?$adminsmaintenance['0']['email']:'';
            $messagemaintenance->to_phone = !empty($adminsmaintenance['0']['phone'])?$adminsmaintenance['0']['phone']:'';
            $messagemaintenance->from_name =!empty($user) ? $user->name : 0;
            $messagemaintenance->from_email = !empty($user) ? $user->email : 0;
            $messagemaintenance->from_phone = !empty($user) ? $user->phone : 0;
            $messagemaintenance->id_code = $contactForm->id_code ;
            $messagemaintenance->subject = t( 'maintenance_title') ;
            $messagemaintenance->message = json_encode($mass);

            // Save
            $messagemaintenance->save();
        }

        if ($message->subject == t( 'ownership_title')   or $contactForm->service_type == "ownership service")
        {
            $messagemaintenance = new Message();
            if($contactForm->for_ownership == 'no') {

                $adminsexhibitions= User::where('id',$contactForm->exhibitions_id)->get();



            $messagemaintenance->post_id = '0';
            $messagemaintenance->from_user_id =!empty($user) ? $user->id : 0;
            $messagemaintenance->to_user_id =  !empty($adminsexhibitions['0']['id'])?$adminsexhibitions['0']['id']:'' ;
            $messagemaintenance->to_name = !empty($adminsexhibitions['0']['name'])?$adminsexhibitions['0']['name']:'';
            $messagemaintenance->to_email = !empty($adminsexhibitions['0']['email'])?$adminsexhibitions['0']['email']:'';
            $messagemaintenance->to_phone = !empty($adminsexhibitions['0']['phone'])?$adminsexhibitions['0']['phone']:'';
            $messagemaintenance->from_name =!empty($user)  ? $user->name : 0;
            $messagemaintenance->from_email = !empty($user) ? $user->email : 0;
            $messagemaintenance->from_phone = !empty($user)  ? $user->phone : 0;
            $messagemaintenance->id_code = $contactForm->id_code ;
            $messagemaintenance->subject = t( 'ownership_title');
            $messagemaintenance->message = json_encode($mass);
                if ($contactForm->service_type == t( 'ownership_title')   or $contactForm->service_type == "ownership service" ){
                    if($contactForm->for_ownership == 'no'){

                        $driving_license_image=  ImageService::where('token',$contactForm->id_code)->where('image_title', 'driving_license_image')->first();
                        $purchaser_id_image= ImageService::where('token',$contactForm->id_code)->where('image_title', 'purchaser_id_image')->first();
                        $seller_id_image=   ImageService::where('token',$contactForm->id_code)->where('image_title', 'seller_id_image')->first();
                        $image_car_arr = ImageService::where('token',$contactForm->id_code)->where('image_title', 'car_Pictures')->first();
                        $messagemaintenance->image_car_arr=!empty($image_car_arr->image_code)? $image_car_arr->image_code:'';
                        $messagemaintenance->image_driving_license=!empty($driving_license_image->image_code)?$driving_license_image->image_code:'';
                        $messagemaintenance->image_purchaser_id=!empty($purchaser_id_image->image_code)?$purchaser_id_image->image_code:'';
                        $messagemaintenance->image_seller_id=!empty($seller_id_image->image_code)?$seller_id_image->image_code:'';

                    }else{
                        $driving_license_image=  ImageService::where('token',$contactForm->id_code)->where('image_title', 'driving_license_image')->first();
                        $purchaser_id_image= ImageService::where('token',$contactForm->id_code)->where('image_title', 'purchaser_id_image')->first();
                        $messagemaintenance->image_driving_license=!empty($driving_license_image->image_code)?$driving_license_image->image_code:'';
                        $messagemaintenance->image_purchaser_id=!empty($purchaser_id_image->image_code)?$purchaser_id_image->image_code:'';

                    }

                }
            // Save
            $messagemaintenance->save();
            }
        }



        if ($contactForm->service_type == t( 'checking_title') or $contactForm->service_type == "checking service"){
            $post_car= explode("/",$contactForm->car_url);
            $post_car_get = Post::where('id',$post_car[4])->first();
            $user_car=User::where('id',$post_car_get->user_id)->first();
            $message = new Message();

            $message->post_id = '0';
            $message->from_user_id = !empty($user) ? $user->id : 0;
            $message->to_user_id =  !empty($user_car)?$user_car->id:'' ;
            $message->to_name = !empty($user_car)?$user_car->name:'';
            $message->to_email = !empty($user_car)?$user_car->email:'';
            $message->to_phone = !empty($user_car)?$user_car->phone:'';
            $message->from_name =!empty($user)? $user->name : 0;
            $message->from_email = !empty($user) ? $user->email : 0;
            $message->from_phone = !empty($user) ? $user->phone : 0;
            $message->id_code = $contactForm->id_code ;
            $message->subject = !empty($contactForm->service_type)?$contactForm->service_type:'';
            $message->message = json_encode($mass);
            $message->save();
        }
        if ($message->subject ==  t( 'maintenance_title') or $contactForm->service_type ==  "maintenance service" ){
            if($contactForm->for_mainten == 'yes'){
                $post_car= explode("/",$contactForm->car_url);
                $post_car_get = Post::where('id',$post_car[4])->first();
                $user_car=User::where('id',$post_car_get->user_id)->first();
                $message = new Message();

                $message->post_id = '0';
                $message->from_user_id = !empty($user) ? $user->id : 0;
                $message->to_user_id =  !empty($user_car)?$user_car->id:'' ;
                $message->to_name = !empty($user_car)?$user_car->name:'';
                $message->to_email = !empty($user_car)?$user_car->email:'';
                $message->to_phone = !empty($user_car)?$user_car->phone:'';
                $message->from_name =!empty($user)? $user->name : 0;
                $message->from_email = !empty($user) ? $user->email : 0;
                $message->from_phone = !empty($user) ? $user->phone : 0;
                $message->id_code = $contactForm->id_code ;
                $message->subject = !empty($contactForm->service_type)?$contactForm->service_type:'';
                $message->message = json_encode($mass);
                $message->save();
            }
        }
        if ($contactForm->service_type == t( 'ownership_title')  or $contactForm->service_type == "ownership service" ){
            if($contactForm->for_ownership == 'yes'){
                $message = new Message();
                $post_car= explode("/",$contactForm->car_url);
                $post_car_get = Post::where('id',$post_car[4])->first();
                $user_car=User::where('id',$post_car_get->user_id)->first();
                $message->post_id = '0';
                $message->from_user_id = !empty($user) ? $user->id : 0;
                $message->to_user_id =  !empty($user_car)?$user_car->id:'' ;
                $message->to_name = !empty($user_car)?$user_car->name:'';
                $message->to_email = !empty($user_car)?$user_car->email:'';
                $message->to_phone = !empty($user_car)?$user_car->phone:'';
                $message->from_name =!empty($user)? $user->name : 0;
                $message->from_email = !empty($user) ? $user->email : 0;
                $message->from_phone = !empty($user) ? $user->phone : 0;
                $message->id_code = $contactForm->id_code ;
                $message->subject = !empty($contactForm->service_type)?$contactForm->service_type:'';
                $message->message = json_encode($mass);
                $driving_license_image=  ImageService::where('token',$contactForm->id_code)->where('image_title', 'driving_license_image')->first();
                $purchaser_id_image= ImageService::where('token',$contactForm->id_code)->where('image_title', 'purchaser_id_image')->first();
                $message->image_driving_license=!empty($driving_license_image->image_code)?$driving_license_image->image_code:'';
                $message->image_purchaser_id=!empty($purchaser_id_image->image_code)?$purchaser_id_image->image_code:'';
                $message->save();
            }}
        if ($contactForm->service_type == t( 'shipping_title')  or $contactForm->service_type == "shipping service" ){
            if($contactForm->for_shipping == 'yes') {
                $message = new Message();
                $post_car = explode("/", $contactForm->car_url);
                $post_car_get = Post::where('id', $post_car[4])->first();
                $user_car = User::where('id', $post_car_get->user_id)->first();
                $message->post_id = '0';
                $message->from_user_id = !empty($user) ? $user->id : 0;
                $message->to_user_id = !empty($user_car) ? $user_car->id : '';
                $message->to_name = !empty($user_car) ? $user_car->name : '';
                $message->to_email = !empty($user_car) ? $user_car->email : '';
                $message->to_phone = !empty($user_car) ? $user_car->phone : '';
                $message->from_name = !empty($user) ? $user->name : 0;
                $message->from_email = !empty($user) ? $user->email : 0;
                $message->from_phone = !empty($user) ? $user->phone : 0;
                $message->id_code = $contactForm->id_code;
                $message->subject = !empty($contactForm->service_type) ? $contactForm->service_type : '';
                $message->message = json_encode($mass);
                $image_car_arr = ImageService::where('token', $contactForm->id_code)->where('image_title', 'car_Pictures')->first();
                $message->image_car_arr = !empty($image_car_arr->image_code) ? $image_car_arr->image_code : '';
                $message->save();
            }

        }
        if ($contactForm->service_type == t( 'mogaz service') or $contactForm->service_type == 'mogaz service') {
            if($contactForm->for_mogaz == 'yes'){
                $post_car= explode("/",$contactForm->car_url);
                $post_car_get = Post::where('id',$post_car[4])->first();
                $user_car=User::where('id',$post_car_get->user_id)->first();
                $message = new Message();
                $message->post_id = '0';
                $message->from_user_id = !empty($user) ? $user->id : 0;
                $message->to_user_id = !empty($user_car) ? $user_car->id : '';
                $message->to_name = !empty($user_car) ? $user_car->name : '';
                $message->to_email = !empty($user_car) ? $user_car->email : '';
                $message->to_phone = !empty($user_car) ? $user_car->phone : '';
                $message->from_name = !empty($user) ? $user->name : 0;
                $message->from_email = !empty($user) ? $user->email : 0;
                $message->from_phone = !empty($user) ? $user->phone : 0;
                $message->id_code = $contactForm->id_code;
                $message->subject = !empty($contactForm->service_type) ? $contactForm->service_type : '';
                $message->message = json_encode($mass);
                $message->save();
            }
        }

        $user_service= auth()->user();


     try {
        if (request()->filled('locale')) {
            $admins = User::permission(Permission::getStaffPermissions())->get();
            $locale = (array_key_exists(request()->get('locale'), LaravelLocalization::getSupportedLocales()))
                ? request()->get('locale')
                : null;

            if (!empty($locale)) {
                Notification::send($admins, new FormSent($isJson));
                $user_order_servce = User::where('id',auth()->user()->id)->get();
                Notification::send($user_order_servce, new User_Mail($isJson));
                if ($contactForm->service_type ==  t( 'maintenance_title')  or $contactForm->service_type ==  "maintenance service" ){
                    if($contactForm->for_mainten == 'no'){
                        $adminsmaintenance = User::where('id',$contactForm->maintenance_id)->get();
                        Notification::send($adminsmaintenance, new FormSent($isJson));
                    }else{
                        $adminsmaintenance = User::where('id',$contactForm->maintenance_id_yes)->get();
                        Notification::send($adminsmaintenance, new FormSent($isJson));

                        $post_car= explode("/",$contactForm->car_url);
                        $post_car_get = Post::where('id',$post_car[4])->first();
                        $user_car=User::where('id',$post_car_get->user_id)->first();
                        Notification::send($user_car, new FormSent($isJson));
                    }

                }
                if ($contactForm->service_type == t( 'ownership_title')  or $contactForm->service_type == "ownership service" ){
                    if($contactForm->for_ownership == 'no') {
                        $adminsexhibitions= User::where('id',$contactForm->exhibitions_id)->get();
                        Notification::send($adminsexhibitions, new FormSent($isJson));
                    }else{
                        $post_car= explode("/",$contactForm->car_url);
                        $post_car_get = Post::where('id',$post_car[4])->first();
                        $user_car=User::where('id',$post_car_get->user_id)->first();
                        Notification::send($user_car, new FormSent($isJson));
                    }

                }
                if ($contactForm->service_type == t( 'shipping_title')  or $contactForm->service_type == "shipping service" ){
                        $adminsshipping_id= User::where('id',$contactForm->shipping_id)->get();
                        Notification::send($adminsshipping_id, new FormSent($isJson));
                    if($contactForm->for_shipping == 'yes'){
                        $post_car= explode("/",$contactForm->car_url);
                        $post_car_get = Post::where('id',$post_car[4])->first();
                        $user_car=User::where('id',$post_car_get->user_id)->first();
                        Notification::send($user_car, new FormSent($isJson));
                    }
                }
                if ($contactForm->service_type == t( 'checking_title') or $contactForm->service_type == "checking service"){
                    $post_car= explode("/",$contactForm->car_url);
                    $post_car_get = Post::where('id',$post_car[4])->first();
                    $user_car=User::where('id',$post_car_get->user_id)->first();
                    Notification::send($user_car, new FormSent($isJson));
                }
                if ($contactForm->service_type == t( 'mogaz service') or $contactForm->service_type == 'mogaz service') {
                    if($contactForm->for_mogaz == 'yes'){
                        $post_car= explode("/",$contactForm->car_url);
                        $post_car_get = Post::where('id',$post_car[4])->first();
                        $user_car=User::where('id',$post_car_get->user_id)->first();
                        Notification::send($user_car, new FormSent($isJson));
                    }
                }
            } else {
                Notification::send($admins, new FormSent($isJson));
                $user_order_servce = User::where('id',auth()->user()->id)->get();
                Notification::send($user_order_servce, new User_Mail($isJson));
                if ($contactForm->service_type ==  t( 'maintenance_title')or $contactForm->service_type ==  "maintenance service" ){
                    if($contactForm->for_mainten == 'no'){
                        $adminsmaintenance = User::where('id',$contactForm->maintenance_id)->get();
                        Notification::send($adminsmaintenance, new FormSent($isJson));
                    }else{
                        $adminsmaintenance = User::where('id',$contactForm->maintenance_id_yes)->get();
                        Notification::send($adminsmaintenance, new FormSent($isJson));
                        $post_car= explode("/",$contactForm->car_url);
                        $post_car_get = Post::where('id',$post_car[4])->first();
                        $user_car=User::where('id',$post_car_get->user_id)->first();
                        Notification::send($user_car, new FormSent($isJson));
                    }

                }
                if ($contactForm->service_type == t( 'ownership_title')  or $contactForm->service_type == "ownership service" ){
                    if($contactForm->for_ownership == 'no') {

                        $adminsexhibitions= User::where('id',$contactForm->exhibitions_id)->get();
                        Notification::send($adminsexhibitions, new FormSent($isJson));
                    }else{
                        $post_car= explode("/",$contactForm->car_url);
                        $post_car_get = Post::where('id',$post_car[4])->first();
                        $user_car=User::where('id',$post_car_get->user_id)->first();
                        Notification::send($user_car, new FormSent($isJson));
                    }

                }
                if ($contactForm->service_type == t( 'shipping_title')  or $contactForm->service_type == "shipping service" ){
                    $adminsshipping_id= User::where('id',$contactForm->shipping_id)->get();
                    Notification::send($adminsshipping_id, new FormSent($isJson));
                    if($contactForm->for_shipping == 'yes'){
                        $post_car= explode("/",$contactForm->car_url);
                        $post_car_get = Post::where('id',$post_car[4])->first();
                        $user_car=User::where('id',$post_car_get->user_id)->first();
                        Notification::send($user_car, new FormSent($isJson));
                    }
                }
                if ($contactForm->service_type == t( 'checking_title') or $contactForm->service_type == "checking service"){
                    $post_car= explode("/",$contactForm->car_url);
                    $post_car_get = Post::where('id',$post_car[4])->first();
                    $user_car=User::where('id',$post_car_get->user_id)->first();
                    Notification::send($user_car, new FormSent($isJson));
                }
                if ($contactForm->service_type == t( 'mogaz service') or $contactForm->service_type == 'mogaz service') {
                    if($contactForm->for_mogaz == 'yes'){
                        $post_car= explode("/",$contactForm->car_url);
                        $post_car_get = Post::where('id',$post_car[4])->first();
                        $user_car=User::where('id',$post_car_get->user_id)->first();
                        Notification::send($user_car, new FormSent($isJson));
                    }
                }
            }
        } else {

            $user_order_servce = User::where('id',auth()->user()->id)->get();
            Notification::send($user_order_servce, new User_Mail($isJson));
            Notification::send($admins, new FormSent($isJson));

            if ($contactForm->service_type ==  t( 'maintenance_title') or $contactForm->service_type ==  "maintenance service" ){
                if($contactForm->for_mainten == 'no'){
                    $adminsmaintenance = User::where('id',$contactForm->maintenance_id)->get();
                    Notification::send($adminsmaintenance, new FormSent($isJson));
                }else{
                    $adminsmaintenance = User::where('id',$contactForm->maintenance_id_yes)->get();
                    Notification::send($adminsmaintenance, new FormSent($isJson));
                    $post_car= explode("/",$contactForm->car_url);
                    $post_car_get = Post::where('id',$post_car[4])->first();
                    $user_car=User::where('id',$post_car_get->user_id)->first();
                    Notification::send($user_car, new FormSent($isJson));
                }

            }
            if ($contactForm->service_type == t( 'ownership_title')  or $contactForm->service_type == "ownership service"){
                if($contactForm->for_ownership == 'no') {

                    $adminsexhibitions= User::where('id',$contactForm->exhibitions_id)->get();
                    Notification::send($adminsexhibitions, new FormSent($isJson));
                }else{
                    $post_car= explode("/",$contactForm->car_url);
                    $post_car_get = Post::where('id',$post_car[4])->first();
                    $user_car=User::where('id',$post_car_get->user_id)->first();
                    Notification::send($user_car, new FormSent($isJson));
                }

            }
            if ($contactForm->service_type == t( 'shipping_title')  or $contactForm->service_type == "shipping service"  ){
                $adminsshipping_id= User::where('id',$contactForm->shipping_id)->get();
                Notification::send($adminsshipping_id, new FormSent($isJson));
                if($contactForm->for_shipping == 'yes'){
                    $post_car= explode("/",$contactForm->car_url);
                    $post_car_get = Post::where('id',$post_car[4])->first();
                    $user_car=User::where('id',$post_car_get->user_id)->first();
                    Notification::send($user_car, new FormSent($isJson));
                }
            }
            if ($contactForm->service_type == t( 'checking_title') or $contactForm->service_type == "checking service"){
                $post_car= explode("/",$contactForm->car_url);
                $post_car_get = Post::where('id',$post_car[4])->first();
                $user_car=User::where('id',$post_car_get->user_id)->first();
                Notification::send($user_car, new FormSent($isJson));
            }
            if ($contactForm->service_type == t( 'mogaz service') or $contactForm->service_type == 'mogaz service') {
                if($contactForm->for_mogaz == 'yes'){
                    $post_car= explode("/",$contactForm->car_url);
                    $post_car_get = Post::where('id',$post_car[4])->first();
                    $user_car=User::where('id',$post_car_get->user_id)->first();
                    Notification::send($user_car, new FormSent($isJson));
                }
            }

        }

        // if ($displayFlashMessage) {
        //     $message = t("An activation link has been sent to you to verify your email address.");
        //     flash($message)->success();
        // }
 flash(t("we will send confirmation of this servce by your email and by massages on Theqqa"))->success();
        session(['verificationEmailSent' => true]);

        // return true;
    }  catch (\Exception $e) {
        $message = changeWhiteSpace($e->getMessage());
        if (isFromAdminPanel()) {
            Alert::error($message)->flash();
        } else {
            flash($message)->error();
        }

    }}
         
            $nextStepUrl =config('app.locale') . '/';
    return redirect($nextStepUrl);
    }
}
