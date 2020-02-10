<?php
/**
 * Theqqa - Geo Classified Ads Software
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace App\Http\Controllers\API\Search;

use App\Helpers\Search;
use Torann\LaravelMetaTags\Facades\MetaTag;

class TagController extends BaseController
{
	public $isTagSearch = true;
	public $tag;
	
	/**
	 * @param $countryCode
	 * @param null $tag
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index($countryCode, $tag = null)
	{
		// Check multi-countries site parameters
		if (!config('settings.seo.multi_countries_urls')) {
			$tag = $countryCode;
		}
		
		view()->share('isTagSearch', $this->isTagSearch);
		
		// Get Tag
		$this->tag = rawurldecode($tag);
		
		// Search
		$search = new Search();
		$data = $search->setTag($tag)->setRequestFilters()->fetch();
		
		// Get Titles
		$bcTab = $this->getBreadcrumb();
		$htmlTitle = $this->getHtmlTitle();
		view()->share('bcTab', $bcTab);
		view()->share('htmlTitle', $htmlTitle);
		
		// Meta Tags
		$title = $this->getTitle();
		MetaTag::set('title', $title);
		MetaTag::set('description', $title);
		
		// Translation vars
		view()->share('uriPathTag', $tag);
        return response()->json([
            'status' => 'success',
            'isTagSearch' =>$this->isTagSearch,
            'bcTab' =>$bcTab,
            'htmlTitle' =>$htmlTitle,
            'uriPathTag' =>$tag,

        ]);
//		return view('search.serp', $data);
	}
}