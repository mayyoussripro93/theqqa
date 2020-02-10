<?php
/**
 * Theqqa - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace App\Http\Controllers\API\Post;
use Illuminate\Http\Request;
use App\Helpers\Ip;
use App\Http\Controllers\API\Auth\Traits\VerificationTrait;
use App\Http\Controllers\API\Post\Traits\CustomFieldTrait;
use App\Http\Requests\PostApiRequest;
use App\Models\ImageService;
use App\Models\Payment;
use App\Models\Permission;
use App\Models\Picture;
use App\Models\Post;
use App\Models\PostType;
use App\Models\Category;
use App\Models\Package;
use App\Models\City;
use App\Models\Scopes\VerifiedScope;
use App\Models\User;
use App\Models\ServicePaytabs;
use App\Http\Controllers\API\FrontController;
use App\Models\Scopes\ReviewedScope;
use App\Notifications\PostActivated;
use App\Notifications\PostNotification;
use App\Notifications\PostReviewed;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Helpers\Localization\Country as CountryLocalization;
use App\Http\Controllers\API\Post\Traits\EditTrait;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
class CreateController extends FrontController
{
	use EditTrait, VerificationTrait, CustomFieldTrait;
	
	public $data;
	
	/**
	 * CreateController constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Check if guests can post Ads
		if (config('settings.single.guests_can_post_ads') != '1') {
			$this->middleware('auth')->only(['getForm', 'postForm']);
		}
		
		// From Laravel 5.3.4 or above
		$this->middleware(function ($request, $next) {
			$this->commonQueries();
			
			return $next($request);
		});
	}
	
	/**
	 * Common Queries
	 */
	public function commonQueries()
	{
		// References
		$data = [];

		// Get Countries
		$data['countries'] = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
		view()->share('countries', $data['countries']);

		// Get Categories
		$cacheId = 'categories.parentId.0.with.children' .'Ar';
		$data['categories'] = Cache::remember($cacheId, $this->cacheExpiration, function () {
            $categories = Category::trans()->where('parent_id', 0)->with([
                'children' => function ($query) {

                },

			])->orderBy('lft')->get();
            foreach ($categories as $category){
                $category->user_type = explode(',',$category->user_type);
            }
			return $categories;
		});
		view()->share('categories', $data['categories']);

		// Get Post Types
		$cacheId = 'postTypes.all.' . 'Ar';
		$data['postTypes'] = Cache::remember($cacheId, $this->cacheExpiration, function () {
			$postTypes = PostType::trans()->orderBy('lft')->get();
            foreach ($postTypes as $posttype){
                $posttype->user_type_id = explode(',',$posttype->user_type_id);
            }
			return $postTypes;
		});
		view()->share('postTypes', $data['postTypes']);

		// Count Packages
		$data['countPackages'] = Package::trans()->applyCurrency()->count();
		view()->share('countPackages', $data['countPackages']);

		// Count Payment Methods
		$data['countPaymentMethods'] = $this->countPaymentMethods;

		// Save common's data
		$this->data = $data;

	}
	
	/**
	 * New Post's Form.
	 *
	 * @param null $tmpToken
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getForm($tmpToken = null)
	{
		// Check possible Update
		if (!empty($tmpToken)) {
			session()->keep(['message']);
			
			return $this->getUpdateForm($tmpToken);
		}
		
		// Meta Tags
		MetaTag::set('title', getMetaTag('title', 'create'));
		MetaTag::set('description', strip_tags(getMetaTag('description', 'create')));
		MetaTag::set('keywords', getMetaTag('keywords', 'create'));



        // References
        $data = [];

        // Get Countries
//        $data['countries'] = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
//        view()->share('countries', $data['countries']);

        // Get Categories

        $categories = Category::where('translation_lang','ar')->where('parent_id', 0)->with([
                'children' => function ($query) {
                    $query->trans();
                },
            ])->orderBy('lft')->get();
        foreach ($categories as $category){
            $category->user_type = explode(',',$category->user_type);

            unset($category->rgt,$category->lft,$category->type);
            foreach ($category->children as $category1){

                unset($category1->rgt,$category1->lft,$category1->type,$category1->icon_class,$category1->depth,$category1->active,$category1->tid);
            }
        }

        foreach ($categories as $cat) {

            if(!empty(auth()->user())){

                if(is_array($cat->user_type)){
                    if(in_array(auth()->user()->user_type_id,$cat->user_type) ){
                        $data2[$cat->tid]= Category::where('translation_lang','ar')->where('parent_id', 0)->where('id', $cat->tid)->with([
                            'children' => function ($query) {
                                $query->trans();
                            },
                        ])->orderBy('lft')->first();
                    }
                }else{
                    if($cat->user_type == auth()->user()->user_type_id){
                        $data2[$cat->tid]= Category::where('translation_lang','ar')->where('parent_id', 0)->where('id', $cat->tid)->with([
                            'children' => function ($query) {
                                $query->trans();
                            },
                        ])->orderBy('lft')->first();

                    }
                }

            }else{
                if(is_array($cat->user_type)){
                    if(!empty(in_array( 2 ,$cat->user_type))?in_array( 2 ,$cat->user_type):'' ){

                        $data2[$cat->tid]= Category::where('translation_lang','ar')->where('parent_id', 0)->where('id', $cat->tid)->with([
                            'children' => function ($query) {
                                $query->trans();
                            },
                        ])->orderBy('lft')->first();
                    }
                }else{

                    if($cat->user_type == '2'){

                        $data2[$cat->tid]= Category::where('translation_lang','ar')->where('parent_id', 0)->where('id', $cat->tid)->with([
                            'children' => function ($query) {
                                $query->trans();
                            },
                        ])->orderBy('lft')->first();
                    }
                }
            }
        }


//        $object = new StdClass();
//
//foreach ( $data2 as $key => $value ){
//    $object -> $key = $value;
//}

        $data['categories'] = array_values($data2);
//        $cacheId = 'categories.parentId.0.with.children' . 'ar';
//        $data['categories'] = Cache::remember($cacheId, $this->cacheExpiration, function () {
//            $categories = Category::trans()->where('parent_id', 0)->with([
//                'children' => function ($query) {
//                    $query->trans();
//                },
//            ])->orderBy('lft')->get();
//
//            foreach ($categories as $category){
//                $category->user_type = explode(',',$category->user_type);
//            }
//            return $categories;
//        });
//        view()->share('categories', $data['categories']);

        // Get Post Types
//        $cacheId = 'postTypes.all.' .  'ar';
//        $data['postTypes'] = Cache::remember('postTypes.all.ar', $this->cacheExpiration, function () {
//            $postTypes = PostType::trans()->orderBy('lft')->get();
//            foreach ($postTypes as $posttype){
//                $posttype->user_type_id = explode(',',$posttype->user_type_id);
//            }
//            return $postTypes;
//        });
//        view()->share('postTypes', $data['postTypes']);
        $postTypes = PostType::where('translation_lang','ar')->orderBy('lft')->get();

        foreach ($postTypes as $posttype){
            $posttype->user_type_id = explode(',',$posttype->user_type_id);
        }
        $data['postTypes']=$postTypes;

        // Count Packages
        $data['countPackages'] = Package::trans()->applyCurrency()->count();
        view()->share('countPackages', $data['countPackages']);

        // Count Payment Methods
        $data['countPaymentMethods'] = $this->countPaymentMethods;
        $data['reqdocuments'] = '<p>
									<div style="font-size: 16px"><i class="icon-docs"></i>'.
								 t('Now you can publish your ad for free or special on Theqqa .There are no documents required in the case of free advertising').'
									</div>
								</p>
								<h3>
									<strong><i class="fa fa-plus-circle"></i>'.  t('Featured Ad (Paid)').'</strong>
								</h3>

								<p>'. html_entity_decode(t('For_service_alert_2'), ENT_COMPAT).'</p>';
        // Create
        $this->data = $data;
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
	}
	
	/**
	 * Store a new Post.
	 *
	 * @param null $tmpToken
	 * @param PostRequest $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function postForm($tmpToken = null, PostApiRequest $request)
	{

		// Check possible Update
		if (!empty($tmpToken)) {
			session()->keep(['message']);
			
			return $this->postUpdateForm($tmpToken, $request);
		}

		// Get the Post's City
		$city = City::find($request->input('city_id', 0));
		if (empty($city)) {
			flash(t("Posting Ads was disabled for this time. Please try later. Thank you."))->error();
			
			return back()->withInput();
		}

		// Conditions to Verify User's Email or Phone
		if (auth()->check()) {
			$emailVerificationRequired = config('settings.mail.email_verification') == 1 && $request->filled('email') && $request->input('email') != auth()->user()->email;
			$phoneVerificationRequired = config('settings.sms.phone_verification') == 1 && $request->filled('phone') && $request->input('phone') != auth()->user()->phone;
		} else {
			$emailVerificationRequired = config('settings.mail.email_verification') == 1 && $request->filled('email');
			$phoneVerificationRequired = config('settings.sms.phone_verification') == 1 && $request->filled('phone');
		}

		// New Post
		$post = new Post();
		$input = $request->only($post->getFillable());
		foreach ($input as $key => $value) {
			$post->{$key} = $value;
		}
        $post->country_code ='SA';
        $post->user_id = (auth()->check()) ? auth()->user()->id : 0;
        $post->negotiable = $request->input('negotiable');
        $post->post_type_id =$request->post_type_id ;
		$post->phone_hidden = $request->input('phone_hidden');
        $post->tags =$request->tags ;
		$post->lat = $city->latitude;
		$post->lon = $city->longitude;
		$post->ip_addr = Ip::get();
        $post->price = $request->price ;
		$post->tmp_token = md5(microtime() . mt_rand(100000, 999999));
		$post->verified_email = 1;
		$post->verified_phone = 1;
        if ($request->bank_transfer_in != 0) {
            $post->featured = 1 ;
		}
		// Email verification key generation
		if ($emailVerificationRequired) {
			$post->email_token = md5(microtime() . mt_rand());
			$post->verified_email = 0;
		}
		
		// Mobile activation key generation
		if ($phoneVerificationRequired) {
			$post->phone_token = mt_rand(100000, 999999);
			$post->verified_phone = 0;
		}

		// Save
		$post->save();

        // Save all pictures
        $pictures = [];
        $files = $request->pictures;


        if (count($files) > 0) {
            foreach ($files as $key => $file) {
                if (empty($file)) {
                    continue;
                }


                $name=$file->getClientOriginalName();
                $file->move(public_path()."/storage/files/sa"."/$post->id/", $name);
                // Give the Complete Path of the folder where you want to save the image
                $folder=public_path()."/storage/files/sa"."/$post->id/";

                $file=$folder.$name;
                $uploadimage=$file;
                $newname=$name;
                // Set the thumbnail name
                $thumbnail = $folder.$newname."_thumbnail.jpg";
                // Load the mian image
                $ext = pathinfo($uploadimage, PATHINFO_EXTENSION);


                if($ext == 'jpeg' or $ext == 'jpg')
                    $source = imagecreatefromjpeg($uploadimage);
                elseif ($ext == 'png')
                    $source = imagecreatefrompng($uploadimage);
                elseif ($ext == 'gif')
                    $source = imagecreatefromgif($uploadimage);



                // load the image you want to you want to be watermarked
                $watermark = imagecreatefrompng(storage_path().'/files/'.'watermark.png');

                // get the width and height of the watermark image
                $water_width = imagesx($watermark);
                $water_height = imagesy($watermark);

                // Set the dimension of the area you want to place your watermark we use 0
                // from x-axis and 0 from y-axis
                $dime_x = 0;
                $dime_y = 0;

                // copy both the images
                imagecopy($source, $watermark, $dime_x, $dime_y, 0, 0, $water_width, $water_height);

                // Final processing Creating The Image
                imagejpeg($source, $thumbnail, 100);
                $imgwm = 'simpletext'.(int)$key.time().".png";

                $f1=imagepng($source, $folder.$imgwm);
                imagedestroy($source);


                // Delete old file if new file has uploaded
                // Check if current Post have a pictures
                $picture = Picture::find($key);
                if (!empty($picture)) {
                    // Delete old file
                    $picture->delete($picture->id);
                }

                // Post Picture in database
                $picture = new Picture([
                    'post_id'  => $post->id,
                    'filename' => "/".$imgwm,
                    'position' => (int)$key + 1,
                ]);
                $picture->save();

                $pictures[] = $picture;

                // Check the pictures limit
//                if ($key >= ($picturesLimit - 1)) {
//                    break;
//                }
            }
        }
//        if (count($files) > 0) {
//            foreach ($files as $key => $file) {
//                if (empty($file)) {
//                    continue;
//                }
//
//                $output_file=str_random(6).'.jpg';
//                $ifp = fopen( $output_file, 'wb' );
//
//
//                // we could add validation here with ensuring count( $data ) > 1
//                fwrite( $ifp, base64_decode( $file ) );
//
//                // clean up the file resource
//                fclose( $ifp );
//
//
//
//
//                $extension_purchaser_id_image = getUploadedFileExtension($output_file);
//                if (empty($extension_purchaser_id_image)) {
//                    $extension_purchaser_id_image = 'jpg';
//                }
//                // Make the image
//                $image_purchaser_id_image = Image::make($output_file)->resize(400, 400, function ($constraint) {
//                    $constraint->aspectRatio();
//                });
//                // Generate a filename.
//                $filename_purchaser_id_image = md5($output_file . time()) . '.' . $extension_purchaser_id_image;
//                $destination_path = "storage/files/sa"."/$post->id";
//                // Store the image on disk.
//                Storage::disk('uploads')->put($destination_path . '/' . $filename_purchaser_id_image, $image_purchaser_id_image->stream());
//
//
//
//
//                // Post Picture in database
//                $picture = new Picture([
//                    'post_id'  => $post->id,
//                    'filename' => "/".$filename_purchaser_id_image,
//                    'position' => (int)$key + 1,
//                ]);
//                $picture->save();
//
//                $pictures[] = $picture;
//
//
//
//
//
//            }
//        }


if ($request->bank_transfer_in != 0){
//        $output_file2="test.jpg";
//        $ifp = fopen( $output_file2, 'wb' );
//
//
//        // we could add validation here with ensuring count( $data ) > 1
//        fwrite( $ifp, base64_decode( $request->bank_transfer_in ) );
//
//        // clean up the file resource
//        fclose( $ifp );
//        $extension_booking_bank_image = getUploadedFileExtension($output_file2);
//
//        if (empty($extension_booking_bank_image)) {
//            $extension_booking_bank_image = 'jpg';
//        }
//
//        // Make the image
//        $booking_bank_image = Image::make($output_file2)->resize(400, 400, function ($constraint) {
//            $constraint->aspectRatio();
//        });
//
//        // Generate a filename.
//        $filename_booking_bank_image = md5($output_file2. time()) . '.' . $extension_booking_bank_image;
//        $destination_path = 'app/booking';
//        // Store the image on disk.
//        Storage::disk('public')->put($destination_path . '/' . $filename_booking_bank_image, $booking_bank_image->stream());
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
        $Payment = new Payment([
            'user_id'           =>  auth()->user()->id ,
            'price'             =>  $package->price,
            'post_id'          => $post->id,
            'package_id' => $request->package_id,
            'payment_method_id' => $request->payment_method_id,
            'transaction_id' => $request->transaction_id,
            'active' => 1,
            'transaction_id'    => t('bank_transfer'),
            'active'            => 0,
            'image'     => $filename_booking_bank_image ,
        ]);
        $Payment->save();
}
        // Save ad Id in session (for next steps)
		session(['tmpPostId' => $post->id]);

		// Custom Fields
		$this->createPostFieldsValues($post, $request);
				
	
        if (config('settings.mail.admin_notification') == 1) {
            try {
           
                // Get all admin users
                $admins = User::permission(Permission::getStaffPermissions())->get();
                if ($admins->count() > 0) {
                   
                    Notification::send($admins, new PostNotification($post));
                 
                    /*
                    foreach ($admins as $admin) {
                        Notification::route('mail', $admin->email)->notify(new PostNotification($post));
                    }
                    */
                }
            } catch (\Exception $e) {
                flash($e->getMessage())->error();
            }
        }

        // Send Verification Link or Code
        if ($emailVerificationRequired || $phoneVerificationRequired) {

            // Save the Next URL before verification


            // Email
            if ($emailVerificationRequired) {
                // Send Verification Link by Email
                $this->sendVerificationEmail($post);
  
                // Show the Re-send link
                $this->showReSendVerificationEmailLink($post, 'post');
            }

            // Phone
            if ($phoneVerificationRequired) {
                // Send Verification Code by SMS
                $this->sendVerificationSms($post);

                // Show the Re-send link
                $this->showReSendVerificationSmsLink($post, 'post');

                // Go to Phone Number verification

            }

            // Send Confirmation Email or SMS,
            // When User clicks on the Verification Link or enters the Verification Code.
            // Done in the "app/Observers/PostObserver.php" file.

        } else {

            // Send Confirmation Email or SMS
            if (config('settings.mail.confirmation') == 1) {
                try {
                    if (config('settings.single.posts_review_activation') == 1) {
                        $post->notify(new PostActivated($post));
                    } else {
                        $post->notify(new PostReviewed($post));
                    }
                } catch (\Exception $e) {
                    flash($e->getMessage())->error();
                }
            }

        }
        if ($request->paytabs == 1){
     $package = \App\Models\Package::find(6);
        require_once 'paytabs.php';

         $pt = new \App\Http\Controllers\API\paytabs( env('PAYTABS_EMAIL'),  env('PAYTABS_SECRET_KEY'));


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
                'unit_price' => $package->price,                                  //Unit price of the product. If multiple products then add “||” separator.
                "other_charges" => "00.00",                                     //Additional charges. e.g.: shipping charges, taxes, VAT, etc.
                
                'amount' => $package->price,                                          //Amount of the products and other charges, it should be equal to: amount = (sum of all products’ (unit_price * quantity)) + other_charges
                'discount'=>"0",                                                //Discount of the transaction. The Total amount of the invoice will be= amount - discount
                'currency' => "SAR",                                            //Currency of the amount stated. 3 character ISO currency code 
               
            
                
                //Invoice Information
                'title' => $package->name,               // Customer's Name on the invoice
                "msg_lang" => "ar",                 //Language of the PayPage to be created. Invalid or blank entries will default to English.(Englsh/Arabic)
                "reference_no" =>$post->id,        //Invoice reference number in your system
    
                //Website Information
                "site_url" => "https://www.theqqa.com",      //The requesting website be exactly the same as the website/URL associated with your PayTabs Merchant Account
                "return_url" => "https://www.theqqa.com/api/verify_payment_paytabs",
                "cms_with_version" => "API USING PHP",

                "paypage_info" => "1"
            ));
       
            // echo "FOLLOWING IS THE RESPONSE: <br />";
            // print_r ($result);
}

   
        // Redirection
         if ($request->paytabs == 1){
                 return response()->json([
            'status' => 'success',
            'data' => 'saved data',
            'result'=>$result->payment_url
        ]);
         }
         else{
               return response()->json([
            'status' => 'success',
            'data' => 'saved data',
         
        ]);  
         }
   

	}
 public function verify_payment_paytabs(Request $request)
    {

        require_once 'paytabs.php';

        $pt = new \App\Http\Controllers\API\paytabs(env('PAYTABS_EMAIL'), env('PAYTABS_SECRET_KEY'));
$result = $pt->verify_payment($_POST['payment_reference']);

 if($result->response_code = 100){
   
      if($result->reference_no != null){
            $post =\App\Models\ Post::with(['latestPayment'])
                ->where('id', $result->reference_no)
                ->first();
                
            $post->featured=1;
          
            $post->update();
        }

            $package = \App\Models\Package::find(6);
            $paymentInfo = [

                'post_id'           => $result->reference_no,
                'user_id'           =>$post->user_id,
                'price'            =>  $package->price,
                'package_id'        =>  '6',
                'payment_method_id' => '3',
                'transaction_id'    => t('Paytabs'),
                'active'               => 1,
                'image'     => '' ,
                'pt_transaction_id' =>$result->transaction_id,
                'pt_invoice_id'=>$result->pt_invoice_id,
            ];

            // Check the uniqueness of the payment
            if($post->id != NULL or $post->id != 0 ){
                $paymentmodel = \App\Models\Payment::where('post_id', $result->reference_no)
                    ->where('user_id',$post->user_id)
                    ->where('package_id', '6')
                    ->where('payment_method_id', '3')
                    ->first();
            }
            // Save the payment
            $payment =!empty($paymentmodel)?$paymentmodel: new \App\Models\Payment($paymentInfo);
            $payment->save();
        
                    flash(t("Your ad has been updated."))->success();
    $nextStepUrl =config('app.locale') . '/';
    return redirect($nextStepUrl);
     
 }
    else{
               flash($result->result)->error();
    $nextStepUrl =config('app.locale') . '/';
    return redirect($nextStepUrl);
    }
    
        }
        


	/**
	 * Confirmation
	 *
	 * @param $tmpToken
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
	 */
	public function finish($tmpToken)
	{
		// Keep Success Message for the page refreshing
		session()->keep(['message']);
		if (!session()->has('message')) {
			return redirect(config('app.locale') . '/');
		}
		
		// Clear the steps wizard
		if (session()->has('tmpPostId')) {
			// Get the Post
			$post = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->where('id', session('tmpPostId'))->where('tmp_token', $tmpToken)->first();
			if (empty($post)) {
				abort(404);
			}
			
			// Apply finish actions
			$post->tmp_token = null;
			$post->save();
			session()->forget('tmpPostId');
		}
		
		// Redirect to the Post,
		// - If User is logged
		// - Or if Email and Phone verification option is not activated
		if (auth()->check() || (config('settings.mail.email_verification') != 1 && config('settings.sms.phone_verification') != 1)) {
			if (!empty($post)) {
				flash(session('message'))->success();

				return redirect(config('app.locale') . '/' . $post->uri . '?preview=1');
			}
		}
		
		// Meta Tags
		MetaTag::set('title', session('message'));
		MetaTag::set('description', session('message'));
		
		return view('post.finish');
	}
}
