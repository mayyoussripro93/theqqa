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

class RegisterApiRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        if ($this->input('client')=='user')
        {

            $rules = [
                'name'         => 'required|mb_between:2,200',
                'country_code' => 'sometimes|required|not_in:0',
                'phone'        => 'max:20',
                'email'        => 'max:100|whitelist_email|whitelist_domain',
                'password'     => 'required|between:6,60|dumbpwd|confirmed',
                'term'         => 'accepted',
//                'id_number'    => 'required|regex:/^[0-9]+$/|digits:10',
            ];
        }
        else
        {

            $rules = [
                'name'         => 'required|mb_between:2,200',
                'country_code' => 'sometimes|required|not_in:0',
                'phone'        => 'max:20',
                'email'        => 'max:100|whitelist_email|whitelist_domain',
                'password'     => 'required|between:6,60|dumbpwd|confirmed',
                'term'         => 'accepted',
//                'id_number_owner'    =>  'required|regex:/^[0-9]+$/|digits:10',
                'subladmin1'  =>  'required',
                'file'         =>  'required',
            ];
        }
        // Email
        if ($this->filled('email')) {
            $rules['email'] = 'email|unique:users,email|' . $rules['email'];
        }
        if (isEnabledField('email')) {
            if (isEnabledField('phone') and isEnabledField('email')) {
//                $rules['email'] = 'required_without:phone|' . $rules['email'];
                $rules['email'] = 'required|' . $rules['email'];
                $rules['phone'] = 'required|' . $rules['phone'];
            } else {
//                $rules['email'] = 'required|' . $rules['email'];
                $rules['email'] = 'required|' . $rules['email'];
                $rules['phone'] = 'required|' . $rules['phone'];
            }
        }

        // Phone
        if (config('settings.sms.phone_verification') == 1) {
            if ($this->filled('phone')) {
                $countryCode = $this->input('country_code', config('country.code'));
                if ($countryCode == 'UK') {
                    $countryCode = 'GB';
                }
                $rules['phone'] = 'phone:' . $countryCode . '|' . $rules['phone'];
            }
        }
        if (isEnabledField('phone')) {
            if (isEnabledField('phone') and isEnabledField('email')) {
//                $rules['phone'] = 'required_without:email|' . $rules['phone'];
                $rules['email'] = 'required|' . $rules['email'];
                $rules['phone'] = 'required|' . $rules['phone'];
            } else {
//                $rules['phone'] = 'required|' . $rules['phone'];
                $rules['email'] = 'required|' . $rules['email'];
                $rules['phone'] = 'required|' . $rules['phone'];
            }
        }
        if ($this->filled('phone')) {
            $rules['phone'] = 'unique:users,phone|' . $rules['phone'];
        }

        // Username
        if (isEnabledField('username')) {
            $rules['username'] = ($this->filled('username')) ? 'valid_username|allowed_username|between:3,100|unique:users,username' : '';
        }

        // Recaptcha
//        if (config('settings.security.recaptcha_activation')) {
//            $rules['g-recaptcha-response'] = 'required';
//        }

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
