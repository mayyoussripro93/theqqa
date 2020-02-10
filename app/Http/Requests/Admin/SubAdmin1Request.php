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

class SubAdmin1Request extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code'      => 'required|min:2|max:20',
            'name'      => 'required|min:2|max:255',
            'asciiname' => 'required|min:2|max:255',
        ];
    }
}
