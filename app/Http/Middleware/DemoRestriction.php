<?php
/**
 * Theqqa - Geo Classified Ads Software
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace App\Http\Middleware;

use Closure;
use Prologue\Alerts\Facades\Alert;

class DemoRestriction
{
	/**
	 * @param \Illuminate\Http\Request $request
	 * @param Closure $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (isDemo()) {
			$message = t("This feature has been turned off in demo mode.");
			
			if (isFromAdminPanel()) {
				Alert::info($message)->flash();
			} else {
				flash($message)->info();
			}
			
			return redirect()->back();
		}
		
		return $next($request);
	}
}
