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
use App\Models\City;
use Torann\LaravelMetaTags\Facades\MetaTag;

class CityController extends BaseController
{
	public $isCitySearch = true;

    protected $city = null;

    /**
     * @param $countryCode
     * @param $cityName
     * @param null $cityId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($countryCode, $cityName, $cityId = null)
    {
        // Check multi-countries site parameters
        if (!config('settings.seo.multi_countries_urls')) {
            $cityId = $cityName;
            $cityName = $countryCode;
        }

        view()->share('isCitySearch', $this->isCitySearch);

        // Get the City
        $this->city = City::findOrFail((int)$cityId);
        view()->share('city', $this->city);

        // Search
        $search = new Search();
        $data = $search->setLocationByCityCoordinates($this->city->latitude, $this->city->longitude, $this->city->id)->setRequestFilters()->fetch();

        // Get Titles
        $bcTab = $this->getBreadcrumb();
        $htmlTitle = $this->getHtmlTitle();
        view()->share('bcTab', $bcTab);
        view()->share('htmlTitle', $htmlTitle);
        
        // Meta Tags
        $title = $this->getTitle();
        $description = t('Free ads in :location', ['location' => $this->city->name]) . ', 
            ' . config('country.name') . '. ' . t('Looking for a product or service') . ' - ' . $this->city->name . ', ' . config('country.name');

        MetaTag::set('title', $title);
        MetaTag::set('description', $description);

        // Translation vars
        view()->share('uriPathCityName', $cityName);
        view()->share('uriPathCityId', $cityId);

        return response()->json([
            'status' => 'success',
            'isCitySearch' =>$this->isCitySearch,
            'bcTab' =>$bcTab,
            'htmlTitle' =>$htmlTitle,
            'uriPathCityName' =>$cityName,
            'uriPathCityId' =>$cityId,
            'data'=>$data
        ]);
//        return view('search.serp', $data);
    }
}
