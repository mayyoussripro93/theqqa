<?php
/**
 * Theqqa - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace App\Http\Requests\Admin;

class CityRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'           => 'required|min:2|max:255',
            'asciiname'      => 'required|min:2|max:255',
            'latitude'       => 'required',
            'longitude'      => 'required',
            'time_zone'      => 'required',
        ];
    
        if (in_array($this->method(), ['POST', 'CREATE'])) {
            $rules['country_code'] = 'required|min:2|max:2';
			$rules['subadmin1_code'] = 'required';
        }
    
        return $rules;
    }
}
