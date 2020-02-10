<?php
/**
 * Theqqa - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace App\Observer;

use App\Models\Field;
use App\Models\PostValue;
use Illuminate\Support\Facades\Storage;

class PostValueObserver
{
    /**
     * Listen to the Entry deleting event.
     *
     * @param  PostValue $postValue
     * @return void
     */
    public function deleting(PostValue $postValue)
    {
        // Remove files (if exists)
        $field = Field::findTrans($postValue->field_id);
        if (!empty($field)) {
            if ($field->type == 'file') {
                if (!empty($postValue->value)) {
                    Storage::delete($postValue->value);
                }
            }
        }
    }
}
