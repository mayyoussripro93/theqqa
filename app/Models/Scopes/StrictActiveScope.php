<?php
/**
 * Theqqa - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

class StrictActiveScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
	 * @param Builder $builder
	 * @param Model $model
	 * @return $this|Builder
	 */
    public function apply(Builder $builder, Model $model)
    {
		// Load all entries from some Admin panel Controllers:
		// - Admin\PaymentController
		// - Admin\AjaxController
		if (
			str_contains(Route::currentRouteAction(), 'Admin\PaymentController')
			|| str_contains(Route::currentRouteAction(), 'Admin\AjaxController')
			|| str_contains(Route::currentRouteAction(), 'Admin\InlineRequestController')
		) {
			return $builder;
		}
	
		// Load only activated entries for the rest of the website (Admin panel & Front)
        return $builder->where('active', 1);
    }
}
