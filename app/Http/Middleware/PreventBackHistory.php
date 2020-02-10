<?php
/**
 * Theqqa - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace App\Http\Middleware;

use Closure;

class PreventBackHistory
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$response = $next($request);
		
		$headers = [
			'Cache-Control' => 'nocache, no-store, max-age=0, must-revalidate',
			'Pragma'        => 'no-cache',
			'Expires'       => 'Sun, 02 Jan 1990 00:00:00 GMT',
		];
		
		foreach ($headers as $key => $value) {
			$response->headers->set($key, $value);
		}
		
		return $response;
	}
}