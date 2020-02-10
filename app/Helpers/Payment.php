<?php
/**
 * Theqqa - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace App\Helpers;

use App\Models\City;
use App\Models\ImageService;
use App\Models\Message;
use App\Models\Permission;
use App\Models\Post;
use App\Models\Package;
use App\Models\Payment as PaymentModel;
use App\Models\SubAdmin1;
use App\Notifications\FormSent;
use App\Notifications\UserWillApproved;
use App\Notifications\PaymentNotification;
use App\Notifications\PaymentSent;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use App\Models\PaymentMethod;

class Payment
{
	public static $country;
	public static $lang;
	public static $msg = [];
	public static $uri = [];
	
	// API FEATURES...
	public static $messages = [];
	public static $errors = [];
	
	/**
	 * Apply actions after successful Payment
	 *
	 * @param $params
	 * @param $post
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public static function paymentConfirmationActions($params, $post)
	{
		// Save the Payment in database
		$payment = self::register($post, $params);

		if (isFromApi()) {
			$request = request();
			
			// Transform Entity using its Eloquent Resource
			$payment = (new \App\Plugins\apilc\app\Http\Resources\PaymentResource($payment))->toArray($request);
			
			$msg = self::$msg['checkout']['success'];
			return self::response($payment, $msg);
		} else {

            if($post->id == 0 ){

                $contactForm= Session::get('contactForm');
                $contactForm['country_code'] = config('country.code');
                $contactForm['country_name'] = config('country.name');
                $contactForm = Arr::toObject($contactForm);
                $admins = User::permission(Permission::getStaffPermissions())->get();
                $mass=[];

                if ($contactForm->service_type ==  t( 'maintenance_title') ){

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
                            t('car url') => !empty($contactForm->car_url)?$contactForm->car_url:t('car-in') ,
                        ];

                    }

                }
                elseif ($contactForm->service_type == t( 'shipping_title')){
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
                        t('car url') => !empty($contactForm->car_url)?$contactForm->car_url:t('car-in') ,
                    ];
                }
                }
                elseif ($contactForm->service_type == t( 'checking_title')){

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
                        t('car url') => !empty($contactForm->car_url)?$contactForm->car_url:t('car-in') ,

                    ];}
                elseif ($contactForm->service_type == t( 'mogaz service') ){
                    if($contactForm->for_mogaz == 'no'){
                    $mass[] = [
                        t('car_exist') => t('car_out') ,
                        t('Country') => !empty($contactForm->country_name)?$contactForm->country_name:'' ,
                        t('First Name') =>!empty($contactForm->first_name)?$contactForm->first_name:'' ,
                        t('Email') => !empty($contactForm->email)?$contactForm->email:'',
                        t('Owner ID') => !empty( $contactForm->owner_id)? $contactForm->owner_id:'',
                        t('plate number') => !empty($contactForm->plate_number)?$contactForm->plate_number:'' ,
                        t('serial number') =>  !empty($contactForm->serial_number)?$contactForm->serial_number:'',
                        t('Message') =>  !empty($contactForm->message)?$contactForm->message:'',

                    ];
                    }else{
                        $mass[] = [
                            t('car_exist') => t('car-in') ,
                            t('Country') => !empty($contactForm->country_name)?$contactForm->country_name:'' ,
                            t('First Name') =>!empty($contactForm->first_name)?$contactForm->first_name:'' ,
                            t('Email') => !empty($contactForm->email)?$contactForm->email:'',
                            t('Message') =>  !empty($contactForm->message)?$contactForm->message:'',
                            t('car url') => !empty($contactForm->car_url)?$contactForm->car_url:t('car-in') ,

                        ];
                    }

                    }
                elseif ($contactForm->service_type == t( 'estimation title') ){

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
                elseif ($contactForm->service_type == t( 'ownership_title') ){

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
                            t('car url') => !empty($contactForm->car_url)?$contactForm->car_url:t('car-in') ,

                        ];
                    }
                  }

                for ($i = 0; $i < count($admins); $i++) {
                    $message = new Message();

                $message->post_id = '0';
                $message->from_user_id = auth()->check() ? auth()->user()->id : 0;
                $message->to_user_id =  !empty($admins['0']['id'])?$admins['0']['id']:'' ;
                $message->to_name = !empty($admins['0']['name'])?$admins['0']['name']:'';
                $message->to_email = !empty($admins['0']['email'])?$admins['0']['email']:'';
                $message->to_phone = !empty($admins['0']['phone'])?$admins['0']['phone']:'';
                $message->from_name =auth()->check() ? auth()->user()->name : 0;
                $message->from_email = auth()->check() ? auth()->user()->email : 0;
                $message->from_phone = auth()->check() ? auth()->user()->phone : 0;
                $message->id_code = $contactForm->id_code ;
                $message->subject = !empty($contactForm->service_type)?$contactForm->service_type:'';
                $message->message = json_encode($mass);
                if ($contactForm->service_type == t( 'ownership_title') ){
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

//                    if($contactForm->for_ownership == 'no'){
//                        $image_car_arr=Session::get('filename_car_Pictures_arr')[0];
//                        $image_car= implode(",",$image_car_arr);
//                        $message->image_car_arr=!empty($image_car)? $image_car:'';
//                        $message->image_driving_license=!empty(Session::get('filename_driving_license')[0])?Session::get('filename_driving_license')[0]:'';
//                        $message->image_purchaser_id=!empty(Session::get('filename_purchaser_id_image')[0])?Session::get('filename_purchaser_id_image')[0]:'';
//                        $message->image_seller_id=!empty(Session::get('filename_seller_id_image')[0])?Session::get('filename_seller_id_image')[0]:'';
//
//                    }else{
//                        $message->image_driving_license=!empty(Session::get('filename_driving_license')[0])?Session::get('filename_driving_license')[0]:'';
//                        $message->image_purchaser_id=!empty(Session::get('filename_purchaser_id_image')[0])?Session::get('filename_purchaser_id_image')[0]:'';
//
//                    }


                }
                if  ($contactForm->service_type == t( 'shipping_title')){



                    $image_car_arr = ImageService::where('token',$contactForm->id_code)->where('image_title', 'car_Pictures')->first();
                    $message->image_car_arr=!empty($image_car_arr->image_code)? $image_car_arr->image_code:'';
//
////                    $image_car_arr = ImageService::where('token',$contactForm->csrf_token)->where('image_title', 'car_Pictures')->first();
//////                    $image_car= implode(",",$image_car_arr);
////                    $message->image_car_arr=!empty($image_car_arr)? $image_car_arr:'';
//                    $image_car_arr=Session::get('filename_car_Pictures_arr')[0];
//                    $image_car= implode(",",$image_car_arr);
//                    $message->image_car_arr=!empty($image_car)? $image_car:'';


                }

                    if  ($contactForm->service_type == t( 'estimation title')){

                    $image_car_arr = ImageService::where('token',$contactForm->id_code)->first();

                    $message->image_car_arr=!empty($image_car_arr->image_code)? $image_car_arr->image_code:'';

                    }

                }



                // Save
                $message->save();

                // Save and Send user's resume


                    if ($message->subject == t( 'shipping_title') )
                    {

                        $messageshipping = new Message();
                        $adminsshipping = User::where('id',$contactForm->shipping_id)->get();
                        $messageshipping->post_id = '0';
                        $messageshipping->from_user_id = auth()->check() ? auth()->user()->id : 0;
                        $messageshipping->to_user_id =  !empty($adminsshipping['0']['id'])?$adminsshipping['0']['id']:'' ;
                        $messageshipping->to_name = !empty($adminsshipping['0']['name'])?$adminsshipping['0']['name']:'';
                        $messageshipping->to_email = !empty($adminsshipping['0']['email'])?$adminsshipping['0']['email']:'';
                        $messageshipping->to_phone = !empty($adminsshipping['0']['phone'])?$adminsshipping['0']['phone']:'';
                        $messageshipping->from_name =auth()->check() ? auth()->user()->name : 0;
                        $messageshipping->from_email = auth()->check() ? auth()->user()->email : 0;
                        $messageshipping->from_phone = auth()->check() ? auth()->user()->phone : 0;
                        $messageshipping->id_code = $contactForm->id_code ;
                        $messageshipping->subject = t( 'shipping_title') ;
                        $messageshipping->message = json_encode($mass);
                        if  ($contactForm->service_type == t( 'shipping_title')){
////                            $image_car_arr = ImageService::where('token',$contactForm->csrf_token)->where('image_title', 'car_Pictures')->first();
//////                            $image_car= implode(",",$image_car_arr);
////                            $message->image_car_arr=!empty($image_car_arr)? $image_car_arr:'';
//
//                            $image_car_arr=Session::get('filename_car_Pictures_arr')[0];
//                            $image_car= implode(",",$image_car_arr);
//                            $messageshipping->image_car_arr=!empty($image_car)? $image_car:'';

                            $image_car_arr = ImageService::where('token',$contactForm->id_code)->where('image_title', 'car_Pictures')->first();
                            $messageshipping->image_car_arr=!empty($image_car_arr->image_code)? $image_car_arr->image_code:'';



                        }
                        // Save
                        $messageshipping->save();
                    }
                if ($message->subject ==  t( 'maintenance_title') )
                {
                    $messagemaintenance = new Message();
                    if($contactForm->for_mainten == 'no'){
                        $adminsmaintenance = User::where('id',$contactForm->maintenance_id)->get();
                    }else{
                        $adminsmaintenance = User::where('id',$contactForm->maintenance_id_yes)->get();
                    }


                    $messagemaintenance->post_id = '0';
                    $messagemaintenance->from_user_id = auth()->check() ? auth()->user()->id : 0;
                    $messagemaintenance->to_user_id =  !empty($adminsmaintenance['0']['id'])?$adminsmaintenance['0']['id']:'' ;
                    $messagemaintenance->to_name = !empty($adminsmaintenance['0']['name'])?$adminsmaintenance['0']['name']:'';
                    $messagemaintenance->to_email = !empty($adminsmaintenance['0']['email'])?$adminsmaintenance['0']['email']:'';
                    $messagemaintenance->to_phone = !empty($adminsmaintenance['0']['phone'])?$adminsmaintenance['0']['phone']:'';
                    $messagemaintenance->from_name =auth()->check() ? auth()->user()->name : 0;
                    $messagemaintenance->from_email = auth()->check() ? auth()->user()->email : 0;
                    $messagemaintenance->from_phone = auth()->check() ? auth()->user()->phone : 0;
                    $messagemaintenance->id_code = $contactForm->id_code ;
                    $messagemaintenance->subject = t( 'maintenance_title') ;
                    $messagemaintenance->message = json_encode($mass);

                    // Save
                    $messagemaintenance->save();
                }
                if ($message->subject == t( 'ownership_title') )
                {
                    $messagemaintenance = new Message();
                    if($contactForm->for_ownership == 'no') {

                        $adminsexhibitions= User::where('id',$contactForm->exhibitions_id)->get();



                    $messagemaintenance->post_id = '0';
                    $messagemaintenance->from_user_id = auth()->check() ? auth()->user()->id : 0;
                    $messagemaintenance->to_user_id =  !empty($adminsexhibitions['0']['id'])?$adminsexhibitions['0']['id']:'' ;
                    $messagemaintenance->to_name = !empty($adminsexhibitions['0']['name'])?$adminsexhibitions['0']['name']:'';
                    $messagemaintenance->to_email = !empty($adminsexhibitions['0']['email'])?$adminsexhibitions['0']['email']:'';
                    $messagemaintenance->to_phone = !empty($adminsexhibitions['0']['phone'])?$adminsexhibitions['0']['phone']:'';
                    $messagemaintenance->from_name =auth()->check() ? auth()->user()->name : 0;
                    $messagemaintenance->from_email = auth()->check() ? auth()->user()->email : 0;
                    $messagemaintenance->from_phone = auth()->check() ? auth()->user()->phone : 0;
                     $messagemaintenance->id_code = $contactForm->id_code ;
                    $messagemaintenance->subject = t( 'ownership_title');
                    $messagemaintenance->message = json_encode($mass);
                        if ($contactForm->service_type == t( 'ownership_title') ){
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




                if (config('settings.mail.admin_notification') == 1) {
                    try {
                        // Get all admin users
                        $admins = User::permission(Permission::getStaffPermissions())->get();
                        if ($admins->count() > 0) {
                            Notification::send($admins, new FormSent($contactForm));
                            /*
                            foreach ($admins as $admin) {
                                Notification::route('mail', $admin->email)->notify(new UserNotification($user));
                            }
                            */
                        }

                        if ($contactForm->service_type ==  t( 'maintenance_title') ){
                            if($contactForm->for_mainten == 'no'){
                                $adminsmaintenance = User::where('id',$contactForm->maintenance_id)->get();
                                Notification::send($adminsmaintenance, new FormSent($contactForm));
                            }else{
                                $adminsmaintenance = User::where('id',$contactForm->maintenance_id_yes)->get();
                                Notification::send($adminsmaintenance, new FormSent($contactForm));
                            }

                        }
                        if ($contactForm->service_type == t( 'ownership_title') ){
                            if($contactForm->for_ownership == 'no') {

                                $adminsexhibitions= User::where('id',$contactForm->exhibitions_id)->get();
                                Notification::send($adminsexhibitions, new FormSent($contactForm));
                            }

                        }
                        if ($contactForm->service_type == t( 'shipping_title') ){
                            $adminsshipping_id= User::where('id',$contactForm->shipping_id)->get();
                            Notification::send($adminsshipping_id, new FormSent($contactForm));
                        }
                    }
                    catch (\Exception $e) {
//                flash($e->getMessage())->error();
                    }
                }

                // Successful transaction
                flash(self::$msg['checkout']['success'])->success();

                // Redirect
                session()->flash('message', self::$msg['post']['success']);
                $nextStepUrl =config('app.locale') . '/';
                return redirect($nextStepUrl);
            }

              else{
                  // Successful transaction
                  flash(self::$msg['checkout']['success'])->success();

                  // Redirect
                  session()->flash('message', self::$msg['post']['success']);
                  return redirect(self::$uri['nextUrl']);
              }

		}
	}
	
	/**
	 * Apply actions when Payment failed
	 *
	 * @param $post
	 * @param null $errorMessage
	 * @return $this|\Illuminate\Http\JsonResponse
	 * @throws \Exception
	 */
	public static function paymentFailureActions($post, $errorMessage = null)
	{

		// Remove the entry
		self::removeEntry($post);
		
		// Return to Form
		$message = '';
		$message .= self::$msg['checkout']['error'];
		if (!empty($errorMessage)) {
			$message .= '<br>' . $errorMessage;
		}
		
		if (isFromApi()) {
			self::$errors[] = $message;
			return self::error(400);
		} else {
			flash($message)->error();
			
			// Redirect
			return redirect(self::$uri['previousUrl'] . '?error=payment')->withInput();
		}
	}
	
	/**
	 * Apply actions when API failed
	 *
	 * @param $post
	 * @param $exception
	 * @return $this|\Illuminate\Http\JsonResponse
	 * @throws \Exception
	 */
	public static function paymentApiErrorActions($post, $exception)
	{
		// Remove the entry
		self::removeEntry($post);
		
		if (isFromApi()) {
			self::$errors[] = $exception->getMessage();
			return self::error(400);
		} else {
			// Remove local parameters into the session (if exists)
			if (Session::has('params')) {
				Session::forget('params');
			}
			
			// Return to Form
			flash($exception->getMessage())->error();
			
			// Redirect
			return redirect(self::$uri['previousUrl'] . '?error=paymentApi')->withInput();
		}
	}
	
	/**
	 * Save the payment and Send payment confirmation email
	 *
	 * @param Post $post
	 * @param $params
	 * @return PaymentModel|\Illuminate\Http\JsonResponse|null
	 */
	public static function register(Post $post, $params)
	{

		if (empty($post)) {
			return null;
		}

		// Update ad 'reviewed'
      if($params['package_id'] =='6' or $params['package_id'] =='2')
      {

          $post->reviewed = 1;
          $post->featured = 1;
          $post->save();
      }

		// Get the payment info
        $post->id = (($post->id == 0)? null:$post->id);
		$paymentInfo = [
			'post_id'           =>  $post->id,
			'package_id'        => $params['package_id'],
			'payment_method_id' => $params['payment_method_id'],
            'price'             =>   $params['amount'],
            'user_id'           => auth()->user()->id  ,
			'transaction_id'    => (isset($params['transaction_id'])) ? $params['transaction_id'] : null,
		];
		
		// Check the uniqueness of the payment
        if($post->id != NULL){
            $payment = PaymentModel::where('post_id', $paymentInfo['post_id'])
                ->where('package_id', $paymentInfo['package_id'])
                ->where('payment_method_id', $params['payment_method_id'])
                ->first();
        }
        if($params['package_id'] !='19') {
            if (!empty($payment)) {
                return $payment;
            }
        }
		// Save the payment
		$payment = new PaymentModel($paymentInfo);
		$payment->save();
		
		// SEND EMAILS
		
		// Get all admin users


        if ($params['package_id'] =='19' )
        {
            $admins = User::permission(Permission::getStaffPermissions())->get();
            $attr = ['slug' => slugify($post->title), 'id' => $post->id];
            $postUrl = url($post->uri, $attr);
            $user=User::where('id', $params['user_id'])->first();
            $package = Package::findTrans($payment->package_id);
            $paymentMethod = PaymentMethod::find($payment->payment_method_id);
            $message_deposit = new Message();

            $mass_deposit=[];
            $mass_deposit[] = [


                    t('postUrl')  => $postUrl,
                    t('packageName' )  => (!empty($package->short_name)) ? $package->short_name : $package->name,
                    t('amount' ) =>0.025 *$post->price,
                    t('currency')    =>$package->currency_code,
                    t('totalamount')   =>$post->price,
                    t('paymentMethodName') => $paymentMethod->display_name,


            ];

            $post_detail= Post::where('id',$post->id)->first();
            $user_post= User::where('id',$post_detail->user_id)->first();
            $message_deposit->post_id = $post->id;
            $message_deposit->from_user_id = !empty($user) ? $user->id : '';
            $message_deposit->to_user_id = !empty($user_post->id ) ? $user_post->id : '';
            $message_deposit->to_name = !empty($user_post->name ) ? $user_post->name : '';
            $message_deposit->to_email = !empty($user_post->email )? $user_post->email : '';
            $message_deposit->to_phone = !empty($user_post->phone ) ? $user_post->phone : '';
            $message_deposit->from_name = !empty($user) ? $user->name : '';
            $message_deposit->from_email = !empty($user) ? $user->email : '';
            $message_deposit->subject = t('Car Booking Deposit');
            $message_deposit->message = json_encode($mass_deposit);
            // Save
            $message_deposit->save();
        }

        if ($params['package_id'] =='19' )
        {
            $admins = User::permission(Permission::getStaffPermissions())->get();
            $attr = ['slug' => slugify($post->title), 'id' => $post->id];
            $postUrl = url($post->uri, $attr);
            $user=User::where('id', $params['user_id'])->first();
            $package = Package::findTrans($payment->package_id);
            $paymentMethod = PaymentMethod::find($payment->payment_method_id);
            $message_deposit = new Message();
            $mass_deposit=[];
            $mass_deposit[] = [

              t('postUrl')  => $postUrl,
              t('packageName' )  => (!empty($package->short_name)) ? $package->short_name : $package->name,
              t('amount' ) =>0.025 *$post->price,
              t('currency')    =>$package->currency_code,
              t('totalamount')   =>$post->price,
              t('paymentMethodName') => $paymentMethod->display_name,

            ];

            for ($i = 0; $i < count($admins); $i++) {
                $message_deposit->post_id = $post->id;
                $message_deposit->from_user_id =  !empty($user) ? $user->id : 0;
                $message_deposit->to_user_id = !empty($admins[$i]['id']) ? $admins[$i]['id'] : '';
                $message_deposit->to_name = !empty($admins[$i]['name']) ? $admins[$i]['name'] : '';
                $message_deposit->to_email = !empty($admins[$i]['email']) ? $admins[$i]['email'] : '';
                $message_deposit->to_phone = !empty($admins[$i]['phone']) ? $admins[$i]['phone'] : '';
                $message_deposit->from_name =  !empty($user) ? $user->name : 0;
                $message_deposit->from_email =  !empty($user) ? $user->email : 0;
                $message_deposit->subject = t('Car Booking Deposit');
                $message_deposit->message = json_encode($mass_deposit);
            }

            // Save
            $message_deposit->save();
        }

		// Send Payment Email Notifications
		if (config('settings.mail.payment_notification') == 1) {
			// Send Confirmation Email
			try {

				$post->notify(new PaymentSent($payment, $post));


            } catch (\Exception $e) {

                if($post->id != 0)
                {
                    if (isFromApi()) {
                        self::$errors[] = $e->getMessage();
                        return self::error(400);
                    } else {
                        flash($e->getMessage())->error();
                    }
                }
			}
			
			// Send to Admin the Payment Notification Email
			try {
                $admins = User::permission(Permission::getStaffPermissions())->get();
				if ($admins->count() > 0) {
                    $post_detail= Post::where('id',$post->id)->first();
                    $user_post= User::where('id',$post_detail->user_id)->first();
					Notification::send($admins, new PaymentNotification($payment, $post,$user_post));


					/*
					foreach ($admins as $admin) {
						Notification::route('mail', $admin->email)->notify(new PaymentNotification($payment, $post));
					}
					*/
				}
			} catch (\Exception $e) {

			    if($post->id != 0)
			    {
                    if (isFromApi()) {
                        self::$errors[] = $e->getMessage();
                        return self::error(400);

                    } else {
                        flash($e->getMessage())->error();
                    }
                }

			}
//		}

		}
        return $payment;
	}
	
	/**
	 * Remove the ad for public - If there are no free packages
	 *
	 * @param Post $post
	 * @return bool
	 * @throws \Exception
	 */
	public static function removeEntry(Post $post)
	{
		if (empty($post)) {
			return false;
		}
		
		// Don't delete the ad when user try to UPGRADE her ads
		if (empty($post->tmp_token)) {
			return false;
		}
		
		if (auth()->check()) {
			// Delete the ad if user is logged in and there are no free package
			if (Package::where('price', 0)->count() == 0) {
				// But! User can access to the ad from her area to UPGRADE it!
				// You can UNCOMMENT the line below if you don't want the feature above.
				// $post->delete();
			}
		} else {
			// Delete the ad if user is a guest
			$post->delete();
		}
		
		return true;
	}
	
	// API FEATURES...
	
	/**
	 * @param $result
	 * @param null $message
	 * @param array $messages
	 * @param array $errors
	 * @param array $headers
	 * @return \Illuminate\Http\JsonResponse
	 */
	public static function response($result, $message = null, $messages = [], $errors = [], $headers = [])
	{
		$messages = !empty($messages) ? $messages : self::$messages;
		$errors = !empty($errors) ? $errors : self::$errors;
		
		$response = [
			'success'  => true,
			'message'  => $message,
			'data'     => $result,
			'messages' => $messages,
			'errors'   => $errors,
			'code'     => 200,
		];
		
		return response()->json($response, 200, $headers, JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * @param int $code
	 * @param null $error
	 * @param array $errors
	 * @param array $headers
	 * @return \Illuminate\Http\JsonResponse
	 */
	public static function error($code = 404, $error = null, $errors = [], $headers = [])
	{
		$error = !empty($error) ? $error : t('Whoops! Something went wrong!');
		$errors = !empty($errors) ? $errors : self::$errors;
		
		$response = [
			'success' => false,
			'message' => $error,
			'data'    => $errors,
			'code'    => $code,
		];
		
		return response()->json($response, $code, $headers, JSON_UNESCAPED_UNICODE);
	}
}
