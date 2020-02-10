<?php
/**
 * Theqqa - Geo Classified Ads Software
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace App\Http\Controllers\Auth\Traits;

use App\Models\City;
use App\Models\ImageService;
use App\Models\Message;
use App\Models\Package;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Post;
use App\Models\User;
use App\Notifications\BackPaymentNotification;
use App\Notifications\BackPaymentSent;
use App\Notifications\EmailVerification;
use App\Notifications\User_Mail;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Larapen\LaravelLocalization\Facades\LaravelLocalization;
use Prologue\Alerts\Facades\Alert;
use App\Models\Permission;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use App\Notifications\FormSent;
trait EmailVerificationTrait
{
	/**
	 * Send verification message
	 *
	 * @param $entity
	 * @param bool $displayFlashMessage
	 * @return bool
	 */
	public function sendVerificationEmail($entity, $displayFlashMessage = true)
	{
		// Get Entity
		$entityRef = $this->getEntityRef();
		if (empty($entity) || empty($entityRef)) {
			$message = t("Entity ID not found.");

			if (isFromAdminPanel()) {
				Alert::error($message)->flash();
			} else {
				flash($message)->error();
			}

			return false;
		}
		
		// Send Confirmation Email
		try {
			if (request()->filled('locale')) {
				$locale = (array_key_exists(request()->get('locale'), LaravelLocalization::getSupportedLocales()))
					? request()->get('locale')
					: null;
				
				if (!empty($locale)) {
					$entity->notify((new EmailVerification($entity, $entityRef))->locale($locale));
				} else {
					$entity->notify(new EmailVerification($entity, $entityRef));
				}
			} else {
				$entity->notify(new EmailVerification($entity, $entityRef));
			}
			
			if ($displayFlashMessage) {
				$message = t("An activation link has been sent to you to verify your email address.");
				flash($message)->success();
			}
			
			session(['verificationEmailSent' => true]);
			
			return true;
		} catch (\Exception $e) {
			$message = changeWhiteSpace($e->getMessage());
			if (isFromAdminPanel()) {
				Alert::error($message)->flash();
			} else {
				flash($message)->error();
			}
		}
		
		return false;
	}
	
	/**
	 * Show the ReSend Verification Message Link
	 *
	 * @param $entity
	 * @param $entityRefId
	 * @return bool
	 */
	public function showReSendVerificationEmailLink($entity, $entityRefId)
	{
		// Get Entity
		$entityRef = $this->getEntityRef($entityRefId);
		if (empty($entity) || empty($entityRef)) {
			return false;
		}
		
		// Show ReSend Verification Email Link
		if (session()->has('verificationEmailSent')) {
			$message = t("Resend the verification message to verify your email address.");
			$message .= ' <a href="' . lurl('verify/' . $entityRef['slug'] . '/' . $entity->id . '/resend/email') . '" class="btn btn-warning">' . t("Re-send") . '</a>';
			flash($message)->warning();
		}
		
		return true;
	}
	
	/**
	 * URL: Re-Send the verification message
	 *
	 * @param $entityId
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function reSendVerificationEmail($entityId)
	{
		// Non-admin data resources
		$entityRefId = getSegment(2);
		
		// Admin data resources
		if (isFromAdminPanel()) {
			$entityRefId = Request::segment(3);
		}
		
		// Keep Success Message If exists
		if (session()->has('message')) {
			session()->keep(['message']);
		}
		
		// Get Entity
		$entityRef = $this->getEntityRef($entityRefId);
		if (empty($entityRef)) {
			$message = t("Entity ID not found.");
			
			if (isFromAdminPanel()) {
				Alert::error($message)->flash();
			} else {
				flash($message)->error();
			}
			
			return back();
		}
		
		// Get Entity by Id
		$model = $entityRef['namespace'];
		$entity = $model::withoutGlobalScopes($entityRef['scopes'])->where('id', $entityId)->first();
		if (empty($entity)) {
			$message = t("Entity ID not found.");
			
			if (isFromAdminPanel()) {
				Alert::error($message)->flash();
			} else {
				flash($message)->error();
			}
			
			return back();
		}
		
		// Check if the Email is already verified
		if ($entity->verified_email == 1 || isDemo()) {
			if (isDemo()) {
				$message = t("This feature has been turned off in demo mode.");
				if (isFromAdminPanel()) {
					Alert::info($message)->flash();
				} else {
					flash($message)->info();
				}
			} else {
				$message = t("Your :field is already verified.", ['field' => t('Email Address')]);
				if (isFromAdminPanel()) {
					Alert::error($message)->flash();
				} else {
					flash($message)->error();
				}
			}

			// Remove Notification Trigger
			$this->clearEmailSession();

			return back();
		}
		
		// Re-Send the confirmation
		if ($this->sendVerificationEmail($entity, false)) {
			if (isFromAdminPanel()) {
				$message = 'The activation link has been sent to the user to verify his email address.';
				Alert::success($message)->flash();
			} else {
				$message = t("The activation link has been sent to you to verify your email address.");
				flash($message)->success();
			}
			
			// Remove Notification Trigger
			$this->clearEmailSession();
		}
		
		return back();
	}
    public function SendBookingEmail($entityId)
    {
        // Non-admin data resources
        $entityRefId = getSegment(2);
        $user_id  =getSegment(5);
        $post_id = getSegment(6);
        $package_id =getSegment(7);
        $payment_id =getSegment(8);

        // Admin data resources
        if (isFromAdminPanel()) {
            $entityRefId = Request::segment(3);
        }

        // Keep Success Message If exists
        if (session()->has('message')) {
            session()->keep(['message']);
        }

        // Get Entity
        $entityRef = $this->getEntityRef($entityRefId);
        if (empty($entityRef)) {
            $message = t("Entity ID not found.");

            if (isFromAdminPanel()) {
                Alert::error($message)->flash();
            } else {
                flash($message)->error();
            }

            return back();
        }

        // Get Entity by Id
        $model = $entityRef['namespace'];
        $entity = $model::withoutGlobalScopes($entityRef['scopes'])->where('id', $entityId)->first();
        if (empty($entity)) {
            $message = t("Entity ID not found.");

            if (isFromAdminPanel()) {
                Alert::error($message)->flash();
            } else {
                flash($message)->error();
            }

            return back();
        }



        // Check if the Email is already verified
//        if ($entity->verified_email == 1 || isDemo()) {
//            if (isDemo()) {
//                $message = t("This feature has been turned off in demo mode.");
//                if (isFromAdminPanel()) {
//                    Alert::info($message)->flash();
//                } else {
//                    flash($message)->info();
//                }
//            } else {
//                $message = t("Your :field is already verified.", ['field' => t('Email Address')]);
//                if (isFromAdminPanel()) {
//                    Alert::error($message)->flash();
//                } else {
//                    flash($message)->error();
//                }
//            }
//
//            // Remove Notification Trigger
//            $this->clearEmailSession();
//
//            return back();
//        }

        // Re-Send the confirmation
        if ($this->sendVerificationBookingEmail($entity,$post_id,$package_id,$payment_id,$user_id,false)) {
            if (isFromAdminPanel()) {
                $message = 'The activation link has been sent to the user to verify his email address.';
                Alert::success($message)->flash();
            } else {
                $message = t("The activation link has been sent to you to verify your email address.");
                flash($message)->success();
            }

            // Remove Notification Trigger
            $this->clearEmailSession();
        }

        return back();
    }
    public function sendVerificationBookingEmail($entity,$post_id,$package_id,$payment_id, $user_id,$displayFlashMessage = true)
    {
        // Get Entity
       
        $entityRef = $this->getEntityRef();
//		if (empty($entity) || empty($entityRef)) {
//			$message = t("Entity ID not found.");
//
//			if (isFromAdminPanel()) {
//				Alert::error($message)->flash();
//			} else {
//				flash($message)->error();
//			}
//
//			return false;
//		}
        $user =User::where('id',$user_id)->first();
        $admins = User::permission(Permission::getStaffPermissions())->get();
        $post =Post::where('id',$post_id)->first();
        $package =Package::where('id',$package_id)->first();
        $payment = DB::table('payments')->where('id', (int)$payment_id )->first();
        $isJson = json_decode($payment->date_service);




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
        $user_service= auth()->user();


        try {
//        if (request()->filled('locale')) {
//            $admins = User::permission(Permission::getStaffPermissions())->get();
//            $locale = (array_key_exists(request()->get('locale'), LaravelLocalization::getSupportedLocales()))
//                ? request()->get('locale')
//                : null;
//
//            if (!empty($locale)) {
//                Notification::send($admins, new FormSent($isJson));
//                $user_order_servce = User::where('id',$user_id)->get();
//                Notification::send($user_order_servce, new User_Mail($isJson));
//                if ($contactForm->service_type ==  t( 'maintenance_title')  or $contactForm->service_type ==  "maintenance service" ){
//                    if($contactForm->for_mainten == 'no'){
//                        $adminsmaintenance = User::where('id',$contactForm->maintenance_id)->get();
//                        Notification::send($adminsmaintenance, new FormSent($isJson));
//                    }else{
//                        $adminsmaintenance = User::where('id',$contactForm->maintenance_id_yes)->get();
//                        Notification::send($adminsmaintenance, new FormSent($isJson));
//                        $post_car= explode("/",$contactForm->car_url);
//                        $post_car_get = Post::where('id',$post_car[4])->first();
//                        $user_car=User::where('id',$post_car_get->user_id)->first();
//                        Notification::send($user_car, new FormSent($isJson));
//                    }
//
//                }
//                if ($contactForm->service_type == t( 'ownership_title')  or $contactForm->service_type == "ownership service" ){
//                    if($contactForm->for_ownership == 'no') {
//
//                        $adminsexhibitions= User::where('id',$contactForm->exhibitions_id)->get();
//                        Notification::send($adminsexhibitions, new FormSent($isJson));
//                    }
//
//                }else{
//
//                    $post_car= explode("/",$contactForm->car_url);
//                    $post_car_get = Post::where('id',$post_car[4])->first();
//                    $user_car=User::where('id',$post_car_get->user_id)->first();
//                    Notification::send($user_car, new FormSent($isJson));
//                }
//                if ($contactForm->service_type == t( 'shipping_title')  or $contactForm->service_type == "shipping service" ){
//                        $adminsshipping_id= User::where('id',$contactForm->shipping_id)->get();
//                        Notification::send($adminsshipping_id, new FormSent($isJson));
//                    if($contactForm->for_shipping == 'yes'){
//                        $post_car= explode("/",$contactForm->car_url);
//                        $post_car_get = Post::where('id',$post_car[4])->first();
//                        $user_car=User::where('id',$post_car_get->user_id)->first();
//                        Notification::send($user_car, new FormSent($isJson));
//                    }
//                }
//                if ($contactForm->service_type == t( 'checking_title') or $contactForm->service_type == "checking service"){
//                 $post_car= explode("/",$contactForm->car_url);
//                 $post_car_get = Post::where('id',$post_car[4])->first();
//                    $user_car=User::where('id',$post_car_get->user_id)->first();
//                    Notification::send($user_car, new FormSent($isJson));
//                }
//                if ($contactForm->service_type == t( 'mogaz service') or $contactForm->service_type == 'mogaz service') {
//                    if($contactForm->for_mogaz == 'yes'){
//                        $post_car= explode("/",$contactForm->car_url);
//                        $post_car_get = Post::where('id',$post_car[4])->first();
//                        $user_car=User::where('id',$post_car_get->user_id)->first();
//                        Notification::send($user_car, new FormSent($isJson));
//                    }
//                }
//
//            } else {
//                Notification::send($admins, new FormSent($isJson));
//                $user_order_servce = User::where('id',$user_id)->get();
//                Notification::send($user_order_servce, new User_Mail($isJson));
//                if ($contactForm->service_type ==  t( 'maintenance_title')or $contactForm->service_type ==  "maintenance service" ){
//                    if($contactForm->for_mainten == 'no'){
//                        $adminsmaintenance = User::where('id',$contactForm->maintenance_id)->get();
//                        Notification::send($adminsmaintenance, new FormSent($isJson));
//                    }else{
//                        $adminsmaintenance = User::where('id',$contactForm->maintenance_id_yes)->get();
//                        Notification::send($adminsmaintenance, new FormSent($isJson));
//                        $post_car= explode("/",$contactForm->car_url);
//                        $post_car_get = Post::where('id',$post_car[4])->first();
//                        $user_car=User::where('id',$post_car_get->user_id)->first();
//                        Notification::send($user_car, new FormSent($isJson));
//                    }
//
//                }
//                if ($contactForm->service_type == t( 'ownership_title')  or $contactForm->service_type == "ownership service" ){
//                    if($contactForm->for_ownership == 'no') {
//
//                        $adminsexhibitions= User::where('id',$contactForm->exhibitions_id)->get();
//                        Notification::send($adminsexhibitions, new FormSent($isJson));
//                    }
//
//                }else{
//                    $post_car= explode("/",$contactForm->car_url);
//                    $post_car_get = Post::where('id',$post_car[4])->first();
//                    $user_car=User::where('id',$post_car_get->user_id)->first();
//                    Notification::send($user_car, new FormSent($isJson));
//                }
//                if ($contactForm->service_type == t( 'shipping_title')  or $contactForm->service_type == "shipping service" ){
//                    $adminsshipping_id= User::where('id',$contactForm->shipping_id)->get();
//                    Notification::send($adminsshipping_id, new FormSent($isJson));
//                    if($contactForm->for_shipping == 'yes'){
//                        $post_car= explode("/",$contactForm->car_url);
//                        $post_car_get = Post::where('id',$post_car[4])->first();
//                        $user_car=User::where('id',$post_car_get->user_id)->first();
//                        Notification::send($user_car, new FormSent($isJson));
//                    }
//                }
//                if ($contactForm->service_type == t( 'checking_title') or $contactForm->service_type == "checking service"){
//                    $post_car= explode("/",$contactForm->car_url);
//                    $post_car_get = Post::where('id',$post_car[4])->first();
//                    $user_car=User::where('id',$post_car_get->user_id)->first();
//                    Notification::send($user_car, new FormSent($isJson));
//                }
//                if ($contactForm->service_type == t( 'mogaz service') or $contactForm->service_type == 'mogaz service') {
//                    if($contactForm->for_mogaz == 'yes'){
//                        $post_car= explode("/",$contactForm->car_url);
//                        $post_car_get = Post::where('id',$post_car[4])->first();
//                        $user_car=User::where('id',$post_car_get->user_id)->first();
//                        Notification::send($user_car, new FormSent($isJson));
//                    }
//                }
//            }
//        } else
            {

            $user_order_servce = User::where('id',$user_id)->get();
            Notification::send($user_order_servce, new User_Mail($isJson));
            Notification::send($admins, new FormSent($isJson));

            if ($contactForm->service_type ==  t( 'maintenance_title') or $contactForm->service_type ==  "maintenance service" ){
                if($contactForm->for_mainten == 'no'){
                    $adminsmaintenance = User::where('id',$contactForm->maintenance_id)->get();
                    Notification::send($adminsmaintenance, new FormSent($isJson));
                }else{
                    $adminsmaintenance = User::where('id',$contactForm->maintenance_id_yes)->get();
                    $post_car= explode("/",$contactForm->car_url);
                    $post_car_get = Post::where('id',$post_car[4])->first();
                    $user_car=User::where('id',$post_car_get->user_id)->first();
                    Notification::send($user_car, new FormSent($isJson));
                    Notification::send($adminsmaintenance, new FormSent($isJson));
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

        if ($displayFlashMessage) {
            $message = t("An activation link has been sent to you to verify your email address.");
            flash($message)->success();
        }

        session(['verificationEmailSent' => true]);

        return true;
    } catch (\Exception $e) {
        $message = changeWhiteSpace($e->getMessage());
        if (isFromAdminPanel()) {
            Alert::error($message)->flash();
        } else {
            flash($message)->error();
        }

    }}
elseif ($package_id == '19'){
    // Get all admin users
    $admins = User::permission(Permission::getStaffPermissions())->get();
    $attr = ['slug' => slugify($post->title), 'id' => $post->id];
    $postUrl = ': <a href="' .lurl($post->uri, $attr) . '">' ;

        $package = Package::findTrans($payment->package_id);
        $paymentMethod = PaymentMethod::find($payment->payment_method_id);
        $message_deposit_foruser = new Message();
        $mass_deposit_foruser =[];
        $mass_deposit_foruser [] = [
            t('postUrl')  => $postUrl,
            t('packageName' )  => (!empty($package->short_name)) ? $package->short_name : $package->name,
            t('amount' ) =>0.025 *$post->price,
            t('currency')    =>$package->currency_code,
            t('totalamount')   =>$post->price,
            t('paymentMethodName') => $paymentMethod->display_name,
        ];

$post_detail= Post::where('id',$post->id)->first();
$user_post= User::where('id',$post_detail->user_id)->first();
    $message_deposit_foruser->post_id = $post->id;
    $message_deposit_foruser->from_user_id = !empty($user) ? $user->id : '';
    $message_deposit_foruser->to_user_id = !empty($user_post->id ) ? $user_post->id : '';
    $message_deposit_foruser->to_name = !empty($user_post->name ) ? $user_post->name : '';
    $message_deposit_foruser->to_email = !empty($user_post->email )? $user_post->email : '';
    $message_deposit_foruser->to_phone = !empty($user_post->phone ) ? $user_post->phone : '';
    $message_deposit_foruser->from_name = !empty($user) ? $user->name : '';
    $message_deposit_foruser->from_email = !empty($user) ? $user->email : '';

    $message_deposit_foruser->subject = t('Car Booking Deposit');
    $message_deposit_foruser->message = json_encode($mass_deposit_foruser );


        // Save
    $message_deposit_foruser->save();

 //for admin
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

    // Send Confirmation Email
    try {
        if (request()->filled('locale')) {
            $admins = User::permission(Permission::getStaffPermissions())->get();
            $locale = (array_key_exists(request()->get('locale'), LaravelLocalization::getSupportedLocales()))
                ? request()->get('locale')
                : null;
            $post_detail= Post::where('id',$post->id)->first();
            $user_post= User::where('id',$post_detail->user_id)->first();

            if (!empty($locale)) {
                $entity->notify((new BackPaymentSent($post, $entityRef,$package))->locale($locale));
                Notification::send($admins, new BackPaymentNotification($post, $entityRef,$package,$user_post,$payment));


            } else {
                $entity->notify((new BackPaymentSent($post, $entityRef,$package))->locale($locale));
                Notification::send($admins, new BackPaymentNotification($post, $entityRef,$package,$user_post,$payment));

            }
        } else {
            $post_detail= Post::where('id',$post->id)->first();
            $user_post= User::where('id',$post_detail->user_id)->first();
            $entity->notify((new BackPaymentSent($post, $entityRef,$package)));
            Notification::send($admins, new BackPaymentNotification($post, $entityRef,$package,$user_post,$payment));

        }

        if ($displayFlashMessage) {
            $message = t("An activation link has been sent to you to verify your email address.");
            flash($message)->success();
        }

        session(['verificationEmailSent' => true]);

        return true;
    } catch (\Exception $e) {
        $message = changeWhiteSpace($e->getMessage());
        if (isFromAdminPanel()) {
            Alert::error($message)->flash();
        } else {
            flash($message)->error();
        }
    }

}
    else{
        // Send Confirmation Email
        try {

            // Update ad 'reviewed'
            if($package->id == '6')
            {

                $post->reviewed = 1;
                $post->featured = 1;
                $post->save();
            }
            if (request()->filled('locale')) {
                $admins = User::permission(Permission::getStaffPermissions())->get();
                $locale = (array_key_exists(request()->get('locale'), LaravelLocalization::getSupportedLocales()))
                    ? request()->get('locale')
                    : null;
                $post_detail= Post::where('id',$post->id)->first();
                $user_post= User::where('id',$post_detail->user_id)->first();

                if (!empty($locale)) {
                    $entity->notify((new BackPaymentSent($post, $entityRef,$package))->locale($locale));
                    Notification::send($admins, new BackPaymentNotification($post, $entityRef,$package,$user_post,$payment));


                } else {
                    $entity->notify((new BackPaymentSent($post, $entityRef,$package))->locale($locale));
                    Notification::send($admins, new BackPaymentNotification($post, $entityRef,$package,$user_post,$payment));

                }
            } else {

                $post_detail= Post::where('id',$post->id)->first();
                $user_post= User::where('id',$post_detail->user_id)->first();
                $entity->notify((new BackPaymentSent($post, $entityRef,$package)));
                Notification::send($admins, new BackPaymentNotification($post, $entityRef,$package,$user_post,$payment));

            }

            if ($displayFlashMessage) {
                $message = t("An activation link has been sent to you to verify your email address.");
                flash($message)->success();
            }

            session(['verificationEmailSent' => true]);

            return true;
        } catch (\Exception $e) {
            $message = changeWhiteSpace($e->getMessage());
            if (isFromAdminPanel()) {
                Alert::error($message)->flash();
            } else {
                flash($message)->error();
            }
        }

    }
        return false;
    }
	/**
	 * Remove Notification Trigger (by clearing the sessions)
	 */
	private function clearEmailSession()
	{
		if (session()->has('verificationEmailSent')) {
			session()->forget('verificationEmailSent');
		}
	}
}
