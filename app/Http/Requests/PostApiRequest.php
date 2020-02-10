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

class PostApiRequest extends Request
{
    protected $cfMessages = [];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'category_id'  => 'required|not_in:0',
            'title'        => 'required|mb_between:2,150|whitelist_word_title',
            'description'  => 'required|mb_between:5,6000|whitelist_word',
            'contact_name' => 'required|mb_between:2,200',
            'email'        => 'max:100|whitelist_email|whitelist_domain',
            'phone'        => 'max:20',
            'city_id'      => 'required|not_in:0',
//            'pictures'     => 'required|image|mimes:' . getUploadFileTypes('image') . '|max:' . (int)config('settings.upload.max_file_size', 1000),
            'package_id'   => 'required',
            'payment_method_id'=>'required|not_in:0',
        ];

        // CREATE
        if (in_array($this->method(), ['POST', 'CREATE'])) {
            $rules['parent_id'] = 'required|not_in:0';

            // Recaptcha
//            if (config('settings.security.recaptcha_activation')) {
//                $rules['g-recaptcha-response'] = 'required';
//            }
        }

        // UPDATE
        // if (in_array($this->method(), ['PUT', 'PATCH', 'UPDATE'])) {}

        // COMMON

        // Location
        if (in_array(config('country.admin_type'), ['1', '2']) && config('country.admin_field_active') == 1) {
            $rules['admin_code'] = 'required|not_in:0';
        }

        // Email
        if ($this->filled('email')) {
            $rules['email'] = 'email|' . $rules['email'];
        }
        if (isEnabledField('email')) {
            if (isEnabledField('phone') && isEnabledField('email')) {
                if (auth()->check()) {
                    $rules['email'] = 'required_without:phone|' . $rules['email'];
                } else {
                    // Email address is required for Guests
                    $rules['email'] = 'required|' . $rules['email'];
                }
            } else {
                $rules['email'] = 'required|' . $rules['email'];
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
            if (isEnabledField('phone') && isEnabledField('email')) {
                $rules['phone'] = 'required_without:email|' . $rules['phone'];
            } else {
                $rules['phone'] = 'required|' . $rules['phone'];
            }
        }


        // Require 'package_id' if Packages are available
        $rules['package_id'] = 'required';

        // Require 'payment_method_id' if the Package 'price' > 0
        if ($this->filled('package_id')) {
            $package = Package::find($this->input('package_id'));
            if (!empty($package) && $package->price > 0) {
                $rules['payment_method_id'] = 'required|not_in:0';
            }}

//
//        if ($this->hasFile('pictures')) {
//            $files = $this->file('pictures');
//            foreach ($files as $key => $file) {
//                if (!empty($file)) {
//                    $rules['pictures.' . $key] = 'required|image|mimes:' . getUploadFileTypes('image') . '|max:' . (int)config('settings.upload.max_file_size', 1000);
//                }
//            }
//        }

        // Custom Fields
//		if (!isFromApi()) {
        $cfRequest = new CustomFieldRequest();
        $rules = $rules + $cfRequest->rules();
        $this->cfMessages = $cfRequest->messages();
//		}

        /*
         * Tags (Only allow letters, numbers, spaces and ',;_-' symbols)
         *
         * Explanation:
         * [] 	=> character class definition
         * p{L} => matches any kind of letter character from any language
         * p{N} => matches any kind of numeric character
         * _- 	=> matches underscore and hyphen
         * + 	=> Quantifier — Matches between one to unlimited times (greedy)
         * /u 	=> Unicode modifier. Pattern strings are treated as UTF-16. Also causes escape sequences to match unicode characters
         */
        if ($this->filled('tags')) {
            $rules['tags'] = 'regex:/^[\p{L}\p{N} ,;_-]+$/u';
        }

        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        $messages = [];

        // Custom Fields
        if (!isFromApi()) {
            $messages = $messages + $this->cfMessages;
//            if ($this->hasFile('pictures')) {
//                $files = $this->file('pictures');
//                foreach ($files as $key => $file) {
//                    $messages['pictures.' . $key . '.required'] = t('The picture #:key is required.', ['key' => $key]);
//                    $messages['pictures.' . $key . '.image'] = t('The picture #:key must be image.', ['key' => $key]);
//                    $messages['pictures.' . $key . '.mimes'] = t('The picture #:key must be a file of type: :type.', [
//                        'key'  => $key,
//                        'type' => getUploadFileTypes('image'),
//                    ]);
//                    $messages['pictures.' . $key . '.max'] = t('The picture #:key may not be greater than :max.', [
//                        'key' => $key,
//                        'max' => fileSizeFormat((int)config('settings.upload.max_file_size', 1000)),
//                    ]);
//                }
//            }
        }


        return $messages;
    }
}
