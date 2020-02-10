<?php
/**
 * Theqqa - Geo Classified Ads Software
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
 * Theqqa
 */

namespace App\Http\Requests;
use App\Models\Package;
use App\Models\PaymentMethod;

class ApiContactRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        // $rules = [

        // ];
//        if ($this->package_id != 5){
//            if ($this->payment_method_id == 2){
//                $rules['bank_transfer_in'] = 'required';}
//        }
//        // Get all the Packages & Payment Methods in the database
//        $countPackages = Package::transIn(config('app.locale'))->count();
//        $countPaymentMethods = PaymentMethod::count();
//
//        // Check if 'package_id' & 'payment_method_id' are required
//        if ($countPackages > 0 && $countPaymentMethods > 0) {
//            // Require 'package_id' if Packages are available
//            $rules['package_id'] = 'required';
//
//            // Require 'payment_method_id' if the Package 'price' > 0
//            if ($this->filled('package_id')) {
//                $package = Package::find($this->input('package_id'));
//                if (!empty($package) && $package->price > 0) {
//                    $rules['payment_method_id'] = 'required|not_in:0';
//                }
//            }
//        }

        if ($this->input('service_type') == 'mogaz service')
        {


            $rules = [
                'first_name'           => 'required|mb_between:2,100',
                'email'                => 'required|email|whitelist_email|whitelist_domain',
                'message'              => '',
                'g-recaptcha-response' => (config('settings.security.recaptcha_activation')) ? 'required' : '',
                'owner_id'             => '',
                'plate_number'         => '',
                'serial_number'        => '',
                'car_url'              => '',
            ];



        }

        if ($this->input('service_type') == 'estimation service')
        {


            $rules = [
                'first_name'           => 'required|mb_between:2,100',
                'email'                => 'required|email|whitelist_email|whitelist_domain',
                'message'              => '',
                'g-recaptcha-response' => (config('settings.security.recaptcha_activation')) ? 'required' : '',
                'first_owner_name'     => 'required|mb_between:2,100',
                'middle_owner_name'    => 'required|mb_between:2,100',
                'last_owner_name'      => 'required|mb_between:2,100',
                'Mobile_number'  => 'required|regex:/^[0-9]+$/',
                'car_type'             => 'required',
                'car_category'         => 'required',
                'car_brand'        => 'required',
                'Year_manufacture'              => 'required|regex:/^[0-9]+$/',
                'Kilometers'        => 'required',
                'car_Pictures' =>'required',

            ];



        }

        if ($this->input('service_type') == 'ownership service')
        {
            $rules = [
                'first_name'           => 'required|mb_between:2,100',
                'email'                => 'required|email|whitelist_email|whitelist_domain',
                'seller_name'          => '',
                'purchaser_name'       => 'required|mb_between:2,100',
                'message'              => '',
                'g-recaptcha-response' => (config('settings.security.recaptcha_activation')) ? 'required' : '',
                'owner_id'             => 'required|regex:/^[0-9]+$/|digits:10',
                'user_id'              => 'required|regex:/^[0-9]+$/|digits:10',
                'seller_phone'         => 'required|regex:/^[0-9]+$/',
                'purchaser_phone'      => 'required|regex:/^[0-9]+$/',
                'Kilometers'           => 'required',
                'price'                => 'required',
                'driving_license'      => 'required' ,
                'seller_id_image'      => '',
                'purchaser_id_image'   => 'required',
                'car_Pictures'         => '',
                'exhibitions_place'    => '',
                'exhibitions_id'       => '',
                'car_url'              => '',

            ];


        }

        if ($this->input('service_type') == 'checking service')
        {

            $timedate =date('Y-m-d H:i:s');
            $rules = [
                'first_name'           => 'required|mb_between:2,100',
                'email'                => 'required|email|whitelist_email|whitelist_domain',
                'message'              => '',
                'g-recaptcha-response' => (config('settings.security.recaptcha_activation')) ? 'required' : '',
                'car_url'              => 'required',
                'checking_time'        => 'required',
                'checking_date'   => 'required',
                'address'              =>'required',
                'phone'                =>'required',

            ];



        }
        if ($this->input('service_type') == 'shipping service')
        {
            $rules = [
                'first_name'           => 'required|mb_between:2,100',
                'email'                => 'required|email|whitelist_email|whitelist_domain',
                'message'              => '',
                'g-recaptcha-response' => (config('settings.security.recaptcha_activation')) ? 'required' : '',
                'car_url'               => '',
                'shipping_id'          =>'required',
                'address'              =>'required',
                'owner_id'             => '',
                'plate_number'         => '',
                'serial_number'        => '',
                'address_to'            =>'required',
                'car_Pictures'         => 'required',
            ];



        }
        if ($this->input('service_type') == 'maintenance service')
        {
            $rules = [
                'first_name'           => 'required|mb_between:2,100',
                'email'                => 'required|email|whitelist_email|whitelist_domain',
                'message'              => '',
                'g-recaptcha-response' => (config('settings.security.recaptcha_activation')) ? 'required' : '',
                'car_place'            => '',
                'car_url'              => '',
                'maintenance_id'       =>'',
                'maintenance_id_yes'   =>'',
                'address'              =>'',
                'owner_id'             => '',
                'plate_number'         => '',
                'serial_number'        => '',
            ];



        }

        if ($this->input('service_type') == 'estimation service')
        {
            $rules['car_Pictures.*'] ='image'  ;
        }
        if (!empty($this->input('for_shipping')))
        {

            if ($this->input('for_shipping')== "no" ){

//                $rules['car_Pictures.*'] ='image|mimes:' . getUploadFileTypes('image') ;
                $rules['owner_id'] = 'required|regex:/^[0-9]+$/|digits:10' . $rules['owner_id'];
                $rules['plate_number'] = 'required|' . $rules['plate_number'];
                $rules['serial_number'] = 'required|' . $rules['serial_number'];
                $rules['address'] = 'required|' . $rules['address'];

            }elseif($this->input('for_shipping') == "yes" ){

                $rules['car_url'] = 'required|' . $rules['car_url'];
//                $rules['car_Pictures.*'] ='image|mimes:' . getUploadFileTypes('image') ;
            }
        }else{
            if ($this->input('service_type') == 'shipping service')
            {
//                $rules['car_Pictures.*'] ='image|mimes:' . getUploadFileTypes('image') ;
                $rules['owner_id'] = 'required|regex:/^[0-9]+$/|digits:10' . $rules['owner_id'];
                $rules['plate_number'] = 'required|' . $rules['plate_number'];
                $rules['serial_number'] = 'required|' . $rules['serial_number'];
                $rules['address'] = 'required|' . $rules['address'];
            }}


        if (!empty($this->input('for_mainten')))
        {
            if ($this->input('for_mainten') == "no" ){

                $rules['owner_id'] = 'required|regex:/^[0-9]+$/|digits:10' . $rules['owner_id'];
                $rules['plate_number'] = 'required|' . $rules['plate_number'];
                $rules['serial_number'] = 'required|' . $rules['serial_number'];
                $rules['address'] = 'required|' . $rules['address'];
                $rules['maintenance_id'] = 'required|' . $rules['maintenance_id'];

            }elseif($this->input('for_mainten') == "yes" ){

                $rules['car_url'] = 'required|' . $rules['car_url'];
                $rules['maintenance_id_yes'] = 'required|' . $rules['maintenance_id_yes'];
            }
        }else{
            if ($this->input('service_type') == 'maintenance service' )
            {
                $rules['owner_id'] = 'required|regex:/^[0-9]+$/|digits:10' . $rules['owner_id'];
                $rules['plate_number'] = 'required|' . $rules['plate_number'];
                $rules['serial_number'] = 'required|' . $rules['serial_number'];
                $rules['address'] = 'required|' . $rules['address'];
                $rules['maintenance_id'] = 'required|' . $rules['maintenance_id'];
            }}

        if (!empty($this->input('for_mogaz')))
        {
            if ($this->input('for_mogaz') == "no" ){
                $rules['owner_id'] = 'required|regex:/^[0-9]+$/|digits:10' . $rules['owner_id'];
                $rules['plate_number'] = 'required|' . $rules['plate_number'];
                $rules['serial_number'] = 'required|' . $rules['serial_number'];

            }elseif($this->input('for_mogaz') == "yes" ){
                $rules['car_url'] = 'required|' . $rules['car_url'];
            }
        }else{

            if ($this->input('service_type') == 'mogaz service' )
            {
                $rules['owner_id'] = 'required|regex:/^[0-9]+$/|digits:10' . $rules['owner_id'];
                $rules['plate_number'] = 'required|' . $rules['plate_number'];
                $rules['serial_number'] = 'required|' . $rules['serial_number'];

            }}



        if (!empty($this->input('for_ownership')))
        {

            if ($this->input('for_ownership')== "no" ){
                $rules['seller_name'] = 'required|' . $rules['seller_name'];
//                $rules['seller_id_image'] = 'required|image|mimes:' . getUploadFileTypes('image'). $rules['seller_id_image'];
                $rules['seller_id_image'] = 'required';
                $rules['car_Pictures'] = 'required';
//                $rules['car_Pictures.*'] ='image|mimes:' . getUploadFileTypes('image') ;
                $rules['exhibitions_id'] = 'required|' . $rules['exhibitions_id'];
                $rules['exhibitions_place'] = 'required|' . $rules['exhibitions_place'];

            }elseif($this->input('for_ownership')== "yes" ){
                $rules['car_url'] = 'required|' . $rules['car_url'];
            }
        }else{

            if ($this->input('service_type') == 'ownership service'){
                $rules['seller_name'] = 'required|' . $rules['seller_name'];
                $rules['seller_id_image'] = 'required';
                $rules['car_Pictures'] = 'required';
//                $rules['car_Pictures.*'] ='image|mimes:' . getUploadFileTypes('image');
                $rules['exhibitions_id'] = 'required|' . $rules['exhibitions_id'];
                $rules['exhibitions_place'] = 'required|' . $rules['exhibitions_place'];
            }}

        if ($this->input('service_type') =='contact_page'){
            $rules = [
                'first_name'           => 'required|mb_between:2,100',
                'last_name'            => 'required|mb_between:2,100',
                'email'                => 'required|email|whitelist_email|whitelist_domain',
                'message'              => 'required|mb_between:5,500',
                'g-recaptcha-response' => (config('settings.security.recaptcha_activation')) ? 'required' : '',
            ];
        }


        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        $messages = [];

        return $messages;
    }
}
