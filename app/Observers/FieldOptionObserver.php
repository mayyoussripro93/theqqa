<?php
/**
 * Theqqa - Geo Classified Ads Software
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace App\Observer;

use App\Models\FieldOption;
use App\Models\PostValue;

class FieldOptionObserver
{
    /**
     * Listen to the Entry deleting event.
     *
     * @param  FieldOption $fieldOption
     * @return void
     */
    public function deleting(FieldOption $fieldOption)
    {
        // Delete all translated entries
        $fieldOption->translated()->delete();
    
        // Delete all Posts Custom Field's Values
        $postValues = PostValue::where('value', $fieldOption->id)->get();
        if ($postValues->count() > 0) {
            foreach ($postValues as $value) {
                $value->delete();
            }
        }
    }
}
