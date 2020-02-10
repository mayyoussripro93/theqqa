<?php
/**
 * Theqqa - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use Torann\LaravelMetaTags\Facades\MetaTag;

class CountriesController extends FrontController
{
	/**
	 * CountriesController constructor.
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * @return View
	 */
	public function index()
	{
		$data = [];
		
		// Meta Tags
		MetaTag::set('title', getMetaTag('title', 'countries'));
		MetaTag::set('description', strip_tags(getMetaTag('description', 'countries')));
		MetaTag::set('keywords', getMetaTag('keywords', 'countries'));
		
		return view('countries', $data);
	}
}
