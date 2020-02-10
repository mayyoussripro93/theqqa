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
use App\Models\Category;
use App\Models\CategoryField;
use Torann\LaravelMetaTags\Facades\MetaTag;

class CategoryController extends BaseController
{
	public $isCatSearch = true;

    protected $cat = null;
    protected $subCat = null;

    /**
     * @param $countryCode
     * @param $catSlug
     * @param null $subCatSlug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index($countryCode, $catSlug, $subCatSlug = null)
    {

        // Check multi-countries site parameters
        if (!config('settings.seo.multi_countries_urls')) {
            $subCatSlug = $catSlug;
            $catSlug = $countryCode;
        }

        view()->share('isCatSearch', $this->isCatSearch);

        // Get Category
        $this->cat = Category::trans()->where('parent_id', 0)->where('slug', '=', $catSlug)->firstOrFail();
        view()->share('cat', $this->cat);

        // Get common Data
        $catName = $this->cat->name;
        $catDescription = $this->cat->description;

		// Get Category nested IDs
		$catNestedIds = (object)[
			'parentId' => $this->cat->parent_id,
			'id'       => $this->cat->tid,
		];

        // Check if this is SubCategory Request
        if (!empty($subCatSlug))
        {
            $this->isSubCatSearch = true;
            view()->share('isSubCatSearch', $this->isSubCatSearch);

            // Get SubCategory
            $this->subCat = Category::trans()->where('parent_id', $this->cat->tid)->where('slug', '=', $subCatSlug)->firstOrFail();
            view()->share('subCat', $this->subCat);

            // Get common Data
            $catName = $this->subCat->name;
            $catDescription = $this->subCat->description;
            
            // Get Category nested IDs
			$catNestedIds = (object)[
				'parentId' => $this->subCat->parent_id,
				'id'       => $this->subCat->tid,
			];
        }

		// Get Custom Fields
		$customFields = CategoryField::getFields($catNestedIds);
		view()->share('customFields', $customFields);

        // Search
        $search = new Search();
        \Date::setLocale('ar');
        if (isset($this->subCat) && !empty($this->subCat)) {
            $data = $search->setCategory($this->cat->tid, $this->subCat->tid)->setRequestFilters()->fetch();
        } else {
            $data = $search->setCategory($this->cat->tid)->setRequestFilters()->fetch();
        }

        // Get Titles
        $bcTab = $this->getBreadcrumb();
        $htmlTitle = $this->getHtmlTitle();
        view()->share('bcTab', $bcTab);
        view()->share('htmlTitle', $htmlTitle);

        // SEO
        $title = $this->getTitle();
        if (isset($catDescription) && !empty($catDescription)) {
            $description = str_limit($catDescription, 200);
        } else {
            $description = str_limit(t('Free ads :category in :location', [
                    'category' => $catName,
                    'location' => config('country.name')
                ]) . '. ' . t('Looking for a product or service') . ' - ' . config('country.name'), 200);
        }

        // Meta Tags
        MetaTag::set('title', $title);
        MetaTag::set('description', $description);

        // Open Graph
        $this->og->title($title)->description($description)->type('website');
        if ($data['count']->get('all') > 0) {
            if ($this->og->has('image')) {
                $this->og->forget('image')->forget('image:width')->forget('image:height');
            }
        }
        view()->share('og', $this->og);

        // Translation vars
        view()->share('uriPathCatSlug', $catSlug);
        if (!empty($subCatSlug)) {
            view()->share('uriPathSubCatSlug', $subCatSlug);
        }

        foreach ($data['paginator'] as $key => $data1){


            unset($data1->lon);
            unset($data1->lat,$data1->py_package_id,$data1->calculatedPrice,$data1->partner,$data1->fb_profile,$data1->deletion_mail_sent_at,$data1->country_code,$data1->description
                ,$data1->tags,$data1->phone_hidden,$data1->email_token,$data1->address, $data1->city_id, $data1->ip_addr,$data1->phone_token,$data1->tmp_token,$data1->negotiable);

        }
        return response()->json([
            'status' => 'success',
//            'isCatSearch' =>$this->isCatSearch,
//            'cat' =>$this->cat,
//            'bcTab' =>$bcTab,
//            'htmlTitle' =>$htmlTitle,
//            'isSubCatSearch' =>$this->isSubCatSearch,
//            'subCat' =>$this->subCat,
//            'customFields'=>$customFields,
//            'uriPathCatSlug'=>$customFields,
//            'og'=>$this->og,
//            'uriPathSubCatSlug'=>$subCatSlug,
            'data' =>$data,

        ]);
        
//        return view('search.serp', $data);
    }
}
