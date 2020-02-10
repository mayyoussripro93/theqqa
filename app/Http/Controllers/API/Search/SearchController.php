<?php
/**
 * Theqqa - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace App\Http\Controllers\API\Search;


use App\Helpers\Search;
use App\Http\Controllers\Search\Traits\PreSearchTrait;
use App\Models\CategoryField;
use Torann\LaravelMetaTags\Facades\MetaTag;

class SearchController extends BaseController
{
	use PreSearchTrait;
	
	public $isIndexSearch = true;
	
	protected $cat = null;
	protected $subCat = null;
	protected $city = null;
	protected $admin = null;
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{

		view()->share('isIndexSearch', $this->isIndexSearch);
		
		// Pre-Search
		if (request()->filled('c')) {
			if (request()->filled('sc')) {
				$this->getCategory(request()->get('c'), request()->get('sc'));
				
				// Get Category nested IDs
				$catNestedIds = (object)[
					'parentId' => request()->get('c'),
					'id'       => request()->get('sc'),
				];
			} else {
				$this->getCategory(request()->get('c'));
				
				// Get Category nested IDs
				$catNestedIds = (object)[
					'parentId' => 0,
					'id'       => request()->get('c'),
				];
			}
			
			// Get Custom Fields
			$customFields = CategoryField::getFields($catNestedIds);
			view()->share('customFields', $customFields);
		}
		if (request()->filled('l') || request()->filled('location')) {
			$city = $this->getCity(request()->get('l'), request()->get('location'));
		}
		if (request()->filled('r') && !request()->filled('l')) {
			$admin = $this->getAdmin(request()->get('r'));
		}
		
		// Pre-Search values
		$preSearch = [
			'city'  => (isset($city) && !empty($city)) ? $city : null,
			'admin' => (isset($admin) && !empty($admin)) ? $admin : null,
		];
		
		// Search
		$search = new Search($preSearch);
		$data = $search->fechAll();
		foreach ($data['paginator'] as $key => $data1){


            unset($data1->lon);
            unset($data1->lat,$data1->py_package_id,$data1->calculatedPrice,$data1->partner,$data1->fb_profile,$data1->deletion_mail_sent_at,$data1->country_code,$data1->description
                ,$data1->tags,$data1->phone_hidden,     $data1->   email_token,$data1->address, $data1->city_id, $data1->ip_addr,$data1->phone_token,$data1->tmp_token,$data1->negotiable);

        }
		// Export Search Result
		view()->share('count', $data['count']);
		view()->share('paginator', $data['paginator']);
		
		// Get Titles
		$title = $this->getTitle();
		$this->getBreadcrumb();
		$this->getHtmlTitle();
		
		// Meta Tags
		MetaTag::set('title', $title);
		MetaTag::set('description', $title);
        return response()->json([
            'status' => 'success',
            'data' =>$data['paginator'],
                ]);

	}
}
