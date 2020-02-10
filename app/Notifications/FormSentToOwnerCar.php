<?php
/**
 * Theqqa - #1 Cars Services Platform in KSA
 * Copyright (c) ProCrew. All Rights Reserved
 *
 * Website: http://www.procrew.pro
 *
 * Theqqa
 */

namespace App\Notifications;

use App\Models\City;
use App\Models\Post;
use App\Models\SubAdmin1;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\ImageService;

class FormSentToOwnerCar extends Notification implements ShouldQueue
{
    use Queueable;

    protected $msg;

    public function __construct($request)
    {

        $this->msg = $request;

    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
       
        if ($this->msg->service_type == t( 'mogaz service') or $this->msg->service_type == 'mogaz service')
        {

            if($this->msg->for_mogaz == 'no') {

                $mailMessage = (new MailMessage)
                    ->replyTo($this->msg->email, $this->msg->first_name)
                    ->subject(trans('mail.New_Service_Request', ['country' => !empty($this->msg->country_name) ? $this->msg->country_name : config('country.name') or 'المملكة العربية السعودية', 'appName' => config('app.name'), 'servName' => $this->msg->service_type]))
                    ->greeting(trans('mail.post_notification_content_1'))
                    ->line(trans('mail.post_Service_content_2', ['advertiserName' => $this->msg->first_name]))
                    ->line(t('service_name') . ': ' . (!empty($this->msg->service_type) ? $this->msg->service_type : ""))
                    ->line(t('Country') . ': <a href="' . lurl('/?d=' . (!empty($this->msg->country_code) ? $this->msg->country_code:'SA')) . '">' . (!empty($this->msg->country_name) ? $this->msg->country_name:'المملكة العربية السعودية') . '</a>')
                    ->line('<br><strong style="color: #907624;">' . t('user data') . '</strong>')
                    ->line(t('First Name') . ': ' . (!empty($this->msg->first_name) ? $this->msg->first_name : ""))
                    ->line(t('Email Address') . ': ' . (!empty($this->msg->email) ? $this->msg->email : ""))
                    ->line(t('User ID') . ': ' . (!empty($this->msg->owner_id) ? $this->msg->owner_id : ""))
                    ->line('<br><strong style="color: #907624;">' . t('car data') . '</strong>')
                    ->line(t('plate number') . ': ' . (!empty($this->msg->plate_number) ? $this->msg->plate_number : ""))
                    ->line(t('serial number') . ': ' . (!empty($this->msg->serial_number) ? $this->msg->serial_number : ""))
//                    ->line(t('car url') . ': ' . (!empty($this->msg->car_url) ? $this->msg->car_url : t('car_out')))
                    ->line(t('Message') . ': ' . (!empty($this->msg->message) ? $this->msg->message : ""));

            }else  {
                $mailMessage = (new MailMessage)
                    ->replyTo($this->msg->email, $this->msg->first_name )
                    ->subject(trans('mail.New_Service_Request', ['country' => !empty($this->msg->country_name)? $this->msg->country_name:config('country.name') or 'المملكة العربية السعودية', 'appName' => config('app.name'),'servName' => $this->msg->service_type]))
                    ->greeting(trans('mail.post_notification_content_1'))
                    ->line(trans('mail.post_Service_content_2', ['advertiserName' => $this->msg->first_name]))
                    ->line(t('service_name') . ': ' . (!empty($this->msg->service_type) ? $this->msg->service_type : ""))
                    ->line(t('Country') . ': <a href="' . lurl('/?d=' . (!empty($this->msg->country_code) ? $this->msg->country_code:'SA')) . '">' . (!empty($this->msg->country_name) ? $this->msg->country_name:'المملكة العربية السعودية') . '</a>')
                    ->line( '<br><strong style="color: #907624;">'.t('user data').'</strong>' )
                    ->line(t('First Name') . ': ' .(!empty($this->msg->first_name) ? $this->msg->first_name : ""))
                    ->line(t('Email Address') . ': ' . (!empty($this->msg->email) ?$this->msg->email: ""))
                    ->line( '<br><strong style="color: #907624;">'.t('car data').'</strong>' )
                    ->line(t('car url'). ': ' . (!empty($this->msg->car_url) ?url($this->msg->car_url) : t('car_in') ) )
                    ->line(t('Message'). ': ' . (!empty($this->msg->message) ? $this->msg->message :"" ) );
            }
        }
        elseif ($this->msg->service_type == t( 'estimation title') or $this->msg->service_type ==  'estimation service')
        {
            $image_car_arr = ImageService::where('token',$this->msg->id_code)->where('image_title', 'car_Pictures')->first();
            $mailMessage = (new MailMessage)
                ->replyTo($this->msg->email, $this->msg->first_name )
                ->subject(trans('mail.New_Service_Request', ['country' => !empty($this->msg->country_name)? $this->msg->country_name:config('country.name') or 'المملكة العربية السعودية', 'appName' => config('app.name'),'servName' => $this->msg->service_type]))
                ->greeting(trans('mail.post_notification_content_1'))
                ->line(trans('mail.post_Service_content_2', ['advertiserName' => $this->msg->first_name]))
                ->line(t('service_name') . ': ' . (!empty($this->msg->service_type) ? $this->msg->service_type : ""))
                ->line(t('Country') . ': <a href="' . lurl('/?d=' . (!empty($this->msg->country_code) ? $this->msg->country_code:'SA')) . '">' . (!empty($this->msg->country_name) ? $this->msg->country_name:'المملكة العربية السعودية') . '</a>')
                ->line( '<br><strong style="color: #907624;">'.t('user data').'</strong>' )
                ->line(t('First Name') . ': ' .(!empty($this->msg->first_name) ? $this->msg->first_name : ""))
                ->line(t('Email Address') . ': ' . (!empty($this->msg->email) ?$this->msg->email: ""))
                ->line( '<br><strong style="color: #907624;">'.t('owner data').'</strong>' )
                ->line(t('First Owner\'s Name') . ': ' .(!empty($this->msg->first_owner_name) ? $this->msg->first_owner_name : ""))
                ->line(t('Middle Owner\'s Name') . ': ' . (!empty($this->msg->middle_owner_name) ?$this->msg->middle_owner_name: ""))
                ->line(t('Last owner\'s name') . ': ' .  (!empty($this->msg->last_owner_name) ? $this->msg->last_owner_name : ""))
                ->line(t('Mobile_number') . ': ' .  (!empty($this->msg->Mobile_number) ? $this->msg->Mobile_number : ""))
                ->line( '<br><strong style="color: #907624;">'.t('car data').'</strong>' )
                ->line(t('Car Type') . ': ' . (!empty($this->msg->car_type) ? $this->msg->car_type : "") )
                ->line(t('Car category') . ': ' . (!empty($this->msg->car_category) ? $this->msg->car_category : "") )
                ->line(t('Car brand') . ': ' . (!empty($this->msg->car_brand) ? $this->msg->car_brand : "") )
                ->line(t('Year of manufacture') . ': ' . (!empty($this->msg->Year_manufacture) ? $this->msg->Year_manufacture : "") )
                ->line(t('Kilometers_car'). ': ' . (!empty($this->msg->Kilometers) ? $this->msg->Kilometers :"" ) )
                ->line(t('Notes'). ': ' . (!empty($this->msg->message) ? $this->msg->message :"" ) );
//                $image_car_arr = ImageService::where('token',$this->msg->id_code)->first();

            if (!empty($image_car_arr)) {
                $img = "";
                foreach (explode(',', $image_car_arr->image_code)  as $key => $image_car) {
                    $img .= "<img src=" . url('/storage/app/service/' . $image_car_arr->token.'/'. $image_car) . ">";
                }

                $mailMessage->line(t('car Pictures')  . ': <br>' . (!empty($image_car) ?  $img : ""));
            }
            $mailMessage->line(t('Message') . ': ' . (!empty($this->msg->message) ? $this->msg->message : ""));


        }
        elseif ($this->msg->service_type == t( 'ownership_title') or $this->msg->service_type == 'ownership service')
        {
            $exhibitions_id=User::Where('id',$this->msg->exhibitions_id)->first();
            $exhibitions_place=City::Where('id',$this->msg->exhibitions_place)->first();
            if($this->msg->for_ownership == 'no') {

                $image_driving_license=  ImageService::where('token',$this->msg->id_code)->where('image_title', 'driving_license_image')->first();
                $image_purchaser_id= ImageService::where('token',$this->msg->id_code)->where('image_title', 'purchaser_id_image')->first();
                $image_seller_id=   ImageService::where('token',$this->msg->id_code)->where('image_title', 'seller_id_image')->first();
                $image_car_arr = ImageService::where('token',$this->msg->id_code)->where('image_title', 'car_Pictures')->first();

                $mailMessage = (new MailMessage)
                    ->replyTo($this->msg->email, $this->msg->first_name)
                    ->subject(trans('mail.New_Service_Request', ['country' => !empty($this->msg->country_name)? $this->msg->country_name:config('country.name') or 'المملكة العربية السعودية', 'appName' => config('app.name'), 'servName' => $this->msg->service_type]))
                    ->greeting(trans('mail.post_notification_content_1'))
                    ->line(trans('mail.post_Service_content_2', ['advertiserName' => $this->msg->first_name]))
                    ->line(t('service_name') . ': ' . (!empty($this->msg->service_type) ? $this->msg->service_type : ""))
                    ->line(t('Country') . ': <a href="' . lurl('/?d=' . (!empty($this->msg->country_code) ? $this->msg->country_code:'SA')) . '">' . (!empty($this->msg->country_name) ? $this->msg->country_name:'المملكة العربية السعودية') . '</a>')
                    ->line( '<br><strong style="color: #907624;">'.t('user data').'</strong>' )
                    ->line(t('First Name') . ': ' . (!empty($this->msg->first_name) ? $this->msg->first_name : ""))
                    ->line(t('Email Address') . ': ' . (!empty($this->msg->email) ? $this->msg->email : ""))
                    ->line( '<br><strong style="color: #907624;">'. t('seller_data') .'</strong>' )
                    ->line(t('seller name') . ': ' . (!empty($this->msg->seller_name) ? $this->msg->seller_name : ""))
                    ->line(t('seller ID') . ': ' . (!empty($this->msg->owner_id) ? $this->msg->owner_id : ""))
                    ->line(t('seller phone') . ': ' . (!empty($this->msg->seller_phone) ? $this->msg->seller_phone : ""))
                    ->line(t('seller id image') . ': <br>' . (!empty($image_seller_id) ? "<img src=" . url('/storage/app/service/'  . $image_seller_id->token.'/'.$image_seller_id->image_code) . ">" : ""))
                    ->line( '<br><strong style="color: #907624;">'. t('purchaser_data') .'</strong>' )
                    ->line(t('purchaser name') . ': ' . (!empty($this->msg->purchaser_name) ? $this->msg->purchaser_name : ""))
                    ->line(t('purchaser ID') . ': ' . (!empty($this->msg->user_id) ? $this->msg->user_id : ""))
                    ->line(t('purchaser phone') . ': ' . (!empty($this->msg->purchaser_phone) ? $this->msg->purchaser_phone : ""))
                    ->line(t('purchaser id image'). ': <br>' . (!empty($image_purchaser_id) ? "<img src=" . url('/storage/app/service/' . $image_purchaser_id->token.'/'. $image_purchaser_id->image_code) . ">" : ""))
                    ->line( '<br><strong style="color: #907624;">'.t('ownership_exhib').'</strong>' )
                    ->line(t('available_exhibitions') . ': ' . (!empty($exhibitions_id->name) ? $exhibitions_id->name : ""))
                    ->line(t('exhibitions_location') . ': ' . (!empty($exhibitions_place->name) ? $exhibitions_place->name : ""))
                    ->line( '<br><strong style="color: #907624;">'.t('car data').'</strong>' )
                    ->line(t('Kilometers_car') . ': ' . (!empty($this->msg->Kilometers) ? $this->msg->Kilometers : ""))
                    ->line(t('car price') . ': ' . (!empty($this->msg->price) ? $this->msg->price : ""))

                    ->line(t('driving_license') . ': <br>' . (!empty($image_driving_license) ? "<img src=" . url('/storage/app/service/' . $image_driving_license->token.'/'. $image_driving_license->image_code) . ">" : ""));

                if (!empty($image_car_arr)) {
                    $img = "";
                    foreach (    explode(',', $image_car_arr->image_code) as $key => $image_car) {
                        $img .= "<img src=" . url('/storage/app/service/' . $image_car_arr->token.'/'. $image_car) . ">";
                    }
                    $mailMessage->line(t('car Pictures')  . ': <br>' . (!empty($image_car) ?  $img : ""));
                }
                $mailMessage->line(t('Message') . ': ' . (!empty($this->msg->message) ? $this->msg->message : ""));

            }else{
                $image_driving_license=  ImageService::where('token',$this->msg->id_code)->where('image_title', 'driving_license_image')->first();
                $image_purchaser_id= ImageService::where('token',$this->msg->id_code)->where('image_title', 'purchaser_id_image')->first();

                $mailMessage = (new MailMessage)
                    ->replyTo($this->msg->email, $this->msg->first_name)
                    ->subject(trans('mail.New_Service_Request', ['country' => !empty($this->msg->country_name)? $this->msg->country_name:config('country.name') or 'المملكة العربية السعودية', 'appName' => config('app.name'), 'servName' => $this->msg->service_type]))
                    ->greeting(trans('mail.post_notification_content_1'))
                    ->line(trans('mail.post_Service_content_2', ['advertiserName' => $this->msg->first_name]))
                    ->line(t('service_name') . ': ' . (!empty($this->msg->service_type) ? $this->msg->service_type : ""))
                    ->line(t('Country') . ': <a href="' . lurl('/?d=' . (!empty($this->msg->country_code) ? $this->msg->country_code:'SA')) . '">' . (!empty($this->msg->country_name) ? $this->msg->country_name:'المملكة العربية السعودية') . '</a>')
                    ->line( '<br><strong style="color: #907624;">'.t('user data').'</strong>' )
                    ->line(t('First Name') . ': ' . (!empty($this->msg->first_name) ? $this->msg->first_name : ""))
                    ->line(t('Email Address') . ': ' . (!empty($this->msg->email) ? $this->msg->email : ""))
                    ->line( '<br><strong style="color: #907624;">'. t('seller_data') .'</strong>' )
                    ->line(t('seller ID') . ': ' . (!empty($this->msg->user_id) ? $this->msg->user_id : ""))
                    ->line(t('seller phone') . ': ' . (!empty($this->msg->seller_phone) ? $this->msg->seller_phone : ""))
                    ->line( '<br><strong style="color: #907624;">'. t('purchaser_data') .'</strong>' )
                    ->line(t('purchaser name') . ': ' . (!empty($this->msg->purchaser_name) ? $this->msg->purchaser_name : ""))
                    ->line(t('purchaser ID') . ': ' . (!empty($this->msg->owner_id) ? $this->msg->owner_id : ""))
                    ->line(t('purchaser phone') . ': ' . (!empty($this->msg->purchaser_phone) ? $this->msg->purchaser_phone : ""))
                    ->line(t('purchaser id image'). ': <br>' . (!empty($image_purchaser_id) ? "<img src=" . url('/storage/app/service/' . $image_purchaser_id->token.'/'.$image_purchaser_id->image_code) . ">" : ""))
                    ->line( '<br><strong style="color: #907624;">'.t('car data').'</strong>' )
                    ->line(t('Kilometers_car') . ': ' . (!empty($this->msg->Kilometers) ? $this->msg->Kilometers : ""))
                    ->line(t('car price') . ': ' . (!empty($this->msg->price) ? $this->msg->price : ""))
                    ->line(t('car url'). ': ' . (!empty($this->msg->car_url) ? url($this->msg->car_url) : t('car_in') ) )
                    ->line(t('driving_license') . ': <br>' . (!empty($image_driving_license) ? "<img src=" . url('/storage/app/service/' . $image_driving_license->token.'/'. $image_driving_license->image_code) . ">" : ""))
                    ->line(t('Message') . ': ' . (!empty($this->msg->message) ? $this->msg->message : ""));

            }

        }
        elseif ($this->msg->service_type == t( 'checking_title') or $this->msg->service_type == 'checking service')
        {
            $shipping_place=City::Where('id',$this->msg->car_place)->first();
            $mailMessage = (new MailMessage)
                ->replyTo($this->msg->email, $this->msg->first_name )
                ->subject(trans('mail.New_Service_Request', ['country' => !empty($this->msg->country_name)? $this->msg->country_name:config('country.name') or 'المملكة العربية السعودية', 'appName' => config('app.name'),'servName' => $this->msg->service_type]))
                ->greeting(trans('mail.post_notification_content_1'))
                ->line(trans('mail.post_Service_content_2', ['advertiserName' => $this->msg->first_name]))
                ->line(t('service_name') . ': ' . (!empty($this->msg->service_type) ? $this->msg->service_type : ""))
                ->line(t('Country') . ': <a href="' . lurl('/?d=' . (!empty($this->msg->country_code) ? $this->msg->country_code:'SA')) . '">' . (!empty($this->msg->country_name) ? $this->msg->country_name:'المملكة العربية السعودية') . '</a>')
                ->line( '<br><strong style="color: #907624;">'.t('user data').'</strong>' )
                ->line(t('First Name') . ': ' .(!empty($this->msg->first_name) ? $this->msg->first_name : ""))
                ->line(t('Email Address') . ': ' . (!empty($this->msg->email) ?$this->msg->email: ""))
                ->line(t('Phone') . ': ' .  (!empty($this->msg->phone) ? $this->msg->phone : ""))
                ->line( '<br><strong style="color: #907624;">'.t('checking_place').'</strong>' )
                ->line(t('Address') . ': ' .  (!empty($this->msg->address) ? $this->msg->address : ""))
                ->line(t('checking_date') . ': ' .  (!empty($this->msg->checking_date) ? $this->msg->checking_date : ""))
                ->line(t('checking_time') . ': ' .  (!empty($this->msg->checking_time) ? $this->msg->checking_time : ""))
                ->line( '<br><strong style="color: #907624;">'.t('car data').'</strong>' )
                ->line(t('car_place') . ': ' .  (!empty($shipping_place->name) ? $shipping_place->name : ""))
                ->line(t('car url'). ': ' . (!empty($this->msg->car_url) ? url($this->msg->car_url) : t('car_in') ) )
                ->line(t('Message'). ': ' . (!empty($this->msg->message) ? $this->msg->message :"" ) );
        }
        elseif ($this->msg->service_type == t( 'shipping_title') or $this->msg->service_type ==  'shipping service')
        {
            $shipping_id=User::Where('id',$this->msg->shipping_id)->first();
            $shipping_place=City::Where('id',$this->msg->shipping_place)->first();
            $shipping_place_to=City::Where('id',$this->msg->shipping_place_to)->first();
            $image_car_arr = ImageService::where('token',$this->msg->id_code)->where('image_title', 'car_Pictures')->first();


            if($this->msg->for_shipping == 'no'){
                $mailMessage = (new MailMessage)
                    ->replyTo($this->msg->email, $this->msg->first_name )
                    ->subject(trans('mail.New_Service_Request', ['country' => !empty($this->msg->country_name)? $this->msg->country_name:config('country.name') or 'المملكة العربية السعودية', 'appName' => config('app.name'),'servName' => $this->msg->service_type]))
                    ->greeting(trans('mail.post_notification_content_1'))
                    ->line(trans('mail.post_Service_content_2', ['advertiserName' => $this->msg->first_name]))
                    ->line(t('service_name') . ': ' . (!empty($this->msg->service_type) ? $this->msg->service_type : ""))
                    ->line(t('Country') . ': <a href="' . lurl('/?d=' . (!empty($this->msg->country_code) ? $this->msg->country_code:'SA')) . '">' . (!empty($this->msg->country_name) ? $this->msg->country_name:'المملكة العربية السعودية') . '</a>')
                    ->line( '<br><strong style="color: #907624;">'.t('user data').'</strong>' )
                    ->line(t('First Name') . ': ' .(!empty($this->msg->first_name) ? $this->msg->first_name : ""))
                    ->line(t('Email Address') . ': ' . (!empty($this->msg->email) ?$this->msg->email: ""))
                    ->line( '<br><strong style="color: #907624;">'.t('from_location').'</strong>' )
                    ->line(t('shipping_place') . ': ' .  (!empty($shipping_place->name) ? $shipping_place->name : ""))
                    ->line(t('origin_address_detail') . ': ' .  (!empty($this->msg->address) ? $this->msg->address : ""))
                    ->line(t('Shipping_center') . ': ' .  (!empty($shipping_id->name) ? $shipping_id->name : ""))
                    ->line( '<br><strong style="color: #907624;">'.t('to_location').'</strong>' )
                    ->line(t('shipping_place_to') . ': ' .  (!empty($shipping_place_to->name) ?$shipping_place_to->name : ""))
                    ->line(t('address_to_ditail') . ': ' .  (!empty($this->msg->address_to) ? $this->msg->address_to: ""))
                    ->line( '<br><strong style="color: #907624;">'.t('car data').'</strong>' )
                    ->line(t('Owner ID') . ': ' .  (!empty($this->msg->owner_id) ? $this->msg->owner_id :t('car_in') ))
                    ->line(t('plate number') . ': ' . (!empty($this->msg->plate_number) ? $this->msg->plate_number : t('car_in') ) )
                    ->line(t('serial number') . ': ' . (!empty($this->msg->serial_number) ? $this->msg->serial_number : t('car_in') ) );
                if (!empty($image_car_arr)) {
                    $img = "";
                    foreach ( explode(',', $image_car_arr->image_code) as $key => $image_car) {
                        $img .= "<img src=" . url('/storage/app/service/' . $image_car_arr->token.'/'. $image_car) . ">";
                    }
                    $mailMessage->line(t('car Pictures')  . ': <br>' . (!empty($image_car) ?  $img : ""));
                }
                $mailMessage->line(t('Message') . ': ' . (!empty($this->msg->message) ? $this->msg->message : ""));
            }
            else{
                $mailMessage = (new MailMessage)
                    ->replyTo($this->msg->email, $this->msg->first_name )
                    ->subject(trans('mail.New_Service_Request', ['country' =>!empty($this->msg->country_name)? $this->msg->country_name:config('country.name') or 'المملكة العربية السعودية', 'appName' => config('app.name'),'servName' => $this->msg->service_type]))
                    ->greeting(trans('mail.post_notification_content_1'))
                    ->line(trans('mail.post_Service_content_2', ['advertiserName' => $this->msg->first_name]))
                    ->line(t('service_name') . ': ' . (!empty($this->msg->service_type) ? $this->msg->service_type : ""))
                    ->line(t('Country') . ': <a href="' . lurl('/?d=' . (!empty($this->msg->country_code) ? $this->msg->country_code:'SA')) . '">' . (!empty($this->msg->country_name) ? $this->msg->country_name:'المملكة العربية السعودية') . '</a>')
                    ->line( '<br><strong style="color: #907624;">'.t('user data').'</strong>' )
                    ->line(t('First Name') . ': ' .(!empty($this->msg->first_name) ? $this->msg->first_name : ""))
                    ->line(t('Email Address') . ': ' . (!empty($this->msg->email) ?$this->msg->email: ""))
                    ->line( '<br><strong style="color: #907624;">'.t('from_location').'</strong>' )
                    ->line(t('shipping_place') . ': ' .  (!empty($shipping_place->name) ? $shipping_place->name: ""))
                    ->line(t('origin_address_detail') . ': ' .  (!empty($this->msg->address) ? $this->msg->address : ""))
                    ->line(t('Shipping_center') . ': ' .  (!empty($shipping_id->name) ? $shipping_id->name : ""))
                    ->line( '<br><strong style="color: #907624;">'.t('to_location').'</strong>')
                    ->line(t('shipping_place_to') . ': ' .  (!empty($shipping_place_to->name) ?$shipping_place_to->name : ""))
                    ->line(t('address_to_ditail') . ': ' .  (!empty($this->msg->address_to) ? $this->msg->address_to: ""))
                    ->line( '<br><strong style="color: #907624;">'.t('car data').'</strong>' )
                    ->line(t('car url'). ': ' . (!empty($this->msg->car_url) ? url($this->msg->car_url) : t('car_in') ) );
                if (!empty($image_car_arr)) {
                    $img = "";
                    foreach (explode(',', $image_car_arr->image_code)  as $key => $image_car) {
                        $img .= "<img src=" . url('/storage/app/service/' .  $image_car_arr->token.'/'.$image_car) . ">";
                    }
                    $mailMessage->line(t('car Pictures')  . ': <br>' . (!empty($image_car) ?  $img : ""));
                }
                $mailMessage->line(t('Message') . ': ' . (!empty($this->msg->message) ? $this->msg->message : ""));
            }

        }

        elseif ($this->msg->service_type ==  t( 'maintenance_title') or $this->msg->service_type == 'maintenance service')
        {
            if($this->msg->for_mainten == 'no'){
                $maintenance_id=User::Where('id',$this->msg->maintenance_id)->first();
                $car_place=City::Where('id',$this->msg->car_place)->first();
                $mailMessage = (new MailMessage)
                    ->replyTo($this->msg->email, $this->msg->first_name )
                    ->subject(trans('mail.New_Service_Request', ['country' => !empty($this->msg->country_name)? $this->msg->country_name:config('country.name') or 'المملكة العربية السعودية', 'appName' => config('app.name'),'servName' => $this->msg->service_type]))
                    ->greeting(trans('mail.post_notification_content_1'))
                    ->line(trans('mail.post_Service_content_2', ['advertiserName' => $this->msg->first_name]))
                    ->line(t('service_name') . ': ' . (!empty($this->msg->service_type) ? $this->msg->service_type : ""))
                    ->line(t('Country') . ': <a href="' . lurl('/?d=' . (!empty($this->msg->country_code) ? $this->msg->country_code:'SA')) . '">' . (!empty($this->msg->country_name) ? $this->msg->country_name:'المملكة العربية السعودية') . '</a>')
                    ->line( '<br><strong style="color: #907624;">'.t('user data').'</strong>' )
                    ->line(t('First Name') . ': ' .(!empty($this->msg->first_name) ? $this->msg->first_name : ""))
                    ->line(t('Email Address') . ': ' . (!empty($this->msg->email) ?$this->msg->email: ""))
                    ->line( '<br><strong style="color: #907624;">'.t('maintenance_title').'</strong>' )
                    ->line(t('maintenance_center') . ': ' .  (!empty($maintenance_id->name) ? $maintenance_id->name : ""))
                    ->line(t('car_place') . ': ' .  (!empty($car_place->name) ? $car_place->name : ""))
                    ->line(t('address') . ': ' .  (!empty($this->msg->address) ? $this->msg->address : ""))
                    ->line( '<br><strong style="color: #907624;">'.t('car data').'</strong>' )
                    ->line(t('Owner ID') . ': ' .  (!empty($this->msg->owner_id) ? $this->msg->owner_id :t('car_in') ))
                    ->line(t('plate number') . ': ' . (!empty($this->msg->plate_number) ? $this->msg->plate_number : t('car_in') ) )
                    ->line(t('serial number') . ': ' . (!empty($this->msg->serial_number) ? $this->msg->serial_number : t('car_in') ) )
                    ->line(t('Message'). ': ' . (!empty($this->msg->message) ? $this->msg->message :"" ) );
            }else{
                $maintenance_id=User::Where('id',$this->msg->maintenance_id_yes)->first();
                $mailMessage = (new MailMessage)
                    ->replyTo($this->msg->email, $this->msg->first_name )
                    ->subject(trans('mail.New_Service_Request', ['country' => !empty($this->msg->country_name)? $this->msg->country_name:config('country.name') or 'المملكة العربية السعودية', 'appName' => config('app.name'),'servName' => $this->msg->service_type]))
                    ->greeting(trans('mail.post_notification_content_1'))
                    ->line(trans('mail.post_Service_content_2', ['advertiserName' => $this->msg->first_name]))
                    ->line(t('service_name') . ': ' . (!empty($this->msg->service_type) ? $this->msg->service_type : ""))
                    ->line(t('Country') . ': <a href="' . lurl('/?d=' . (!empty($this->msg->country_code) ? $this->msg->country_code:'SA')) . '">' . (!empty($this->msg->country_name) ? $this->msg->country_name:'المملكة العربية السعودية') . '</a>')
                    ->line( '<br><strong style="color: #907624;">'.t('user data').'</strong>' )
                    ->line(t('First Name') . ': ' .(!empty($this->msg->first_name) ? $this->msg->first_name : ""))
                    ->line(t('Email Address') . ': ' . (!empty($this->msg->email) ?$this->msg->email: ""))
                    ->line( '<br><strong style="color: #907624;">'.t('maintenance_title').'</strong>' )
                    ->line(t('maintenance_center') . ': ' .  (!empty($maintenance_id->name) ? $maintenance_id->name : ""))
                    ->line( '<br><strong style="color: #907624;">'.t('car data').'</strong>' )
                    ->line(t('car url'). ': ' . (!empty($this->msg->car_url) ? url($this->msg->car_url) : t('car_in') ) )
                    ->line(t('Message'). ': ' . (!empty($this->msg->message) ? $this->msg->message :"" ) );
            }

        }

        else
        {

            $mailMessage = (new MailMessage)
                ->replyTo($this->msg->email, $this->msg->first_name . ' ' . $this->msg->last_name)
                ->subject(trans('mail.contact_form_title', ['country' => $this->msg->country_name, 'appName' => config('app.name')]))
                ->line(t('Country') . ': <a href="' . lurl('/?d=' . (!empty($this->msg->country_code) ? $this->msg->country_code:'SA')) . '">' . (!empty($this->msg->country_name) ? $this->msg->country_name:'المملكة العربية السعودية') . '</a>')
                ->line( '<br><strong style="color: #907624;">'.t('user data').'</strong>' )
                ->line(t('First Name') . ': ' . $this->msg->first_name)
                ->line(t('Last Name') . ': ' . $this->msg->last_name)
                ->line(t('Email Address') . ': ' . $this->msg->email)
                ->line(nl2br($this->msg->message));
        }


        if (isset($this->msg->company_name) && $this->msg->company_name!='') {
            $mailMessage->line(t('Company Name') . ': ' . $this->msg->company_name);
        }

//		$mailMessage->line(nl2br($this->msg->message));

        return $mailMessage;
    }
}