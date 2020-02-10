<?php
/**
 * Theqqa - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace App\Http\Controllers\API;

use App\Helpers\Arr;
use App\Helpers\DBTool;

use App\Models\SavedPost;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\HomeSection;
use App\Models\SubAdmin1;
use App\Models\City;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Helpers\Localization\Country as CountryLocalization;

class HomeController extends FrontController
{
	/**
	 * HomeController constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		// Check Country URL for SEO
		$countries = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
		view()->share('countries', $countries);
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		$data = [];
		$countryCode ='SA';

		// Get all homepage sections
		$cacheId = $countryCode . '.homeSections';
		$data['sections'] = Cache::remember($cacheId, $this->cacheExpiration, function () use ($countryCode) {
			$sections = collect([]);

			// Check if the Domain Mapping plugin is available
			if (config('plugins.domainmapping.installed')) {
				try {
					$sections = \App\Plugins\domainmapping\app\Models\DomainHomeSection::where('country_code', $countryCode)->orderBy('lft')->get();
				} catch (\Exception $e) {}
			}

			// Get the entry from the core
			if ($sections->count() <= 0) {
				$sections = HomeSection::orderBy('lft')->get();
			}

			return $sections;
		});

		if ($data['sections']->count() > 0) {
			foreach ($data['sections'] as $section) {
				// Clear method name
				$method = str_replace(strtolower($countryCode) . '_', '', $section->method);

				// Check if method exists
				if (!method_exists($this, $method)) {
					continue;
				}

				// Call the method
				try {
					if (isset($section->value)) {
						$this->{$method}($section->value);
					} else {
						$this->{$method}();
					}
				} catch (\Exception $e) {
					flash($e->getMessage())->error();
					continue;
				}
			}
		}

		// Get SEO
		$this->setSeo();
		return view('home.index', $data);
	}

	/**
	 * Get search form (Always in Top)
	 *
	 * @param array $value
	 */
	protected function getSearchForm($value = [])
	{
		view()->share('searchFormOptions', $value);
	}

	/**
	 * Get locations & SVG map
	 *
	 * @param array $value
	 */
	protected function getLocations($value = [])
	{
		// Get the default Max. Items
		$maxItems = 14;
		if (isset($value['max_items'])) {
			$maxItems = (int)$value['max_items'];
		}

		// Get the Default Cache delay expiration
		$cacheExpiration = $this->getCacheExpirationTime($value);

		// Modal - States Collection
		$cacheId ='SA' . '.home.getLocations.modalAdmins';
		$modalAdmins = Cache::remember($cacheId, $cacheExpiration, function () {
			$modalAdmins = SubAdmin1::currentCountry()->orderBy('name')->get(['code', 'name'])->keyBy('code');

			return $modalAdmins;
		});
		view()->share('modalAdmins', $modalAdmins);

		// Get cities
		$cacheId ='SA' . 'home.getLocations.cities';
		$cities = Cache::remember($cacheId, $cacheExpiration, function () use ($maxItems) {
			$cities = City::currentCountry()->take($maxItems)->orderBy('population', 'DESC')->orderBy('name')->get();

			return $cities;
		});
		$cities = collect($cities)->push(Arr::toObject([
			'id'             => 999999999,
			'name'           => t('More cities') . ' &raquo;',
			'subadmin1_code' => 0,
		]));

		// Get cities number of columns
		$numberOfCols = 4;
		if (file_exists(config('larapen.core.maps.path') . strtolower(config('country.code')) . '.svg')) {
			if (isset($value['show_map']) && $value['show_map'] == '1') {
				$numberOfCols = (isset($value['items_cols']) && !empty($value['items_cols'])) ? (int)$value['items_cols'] : 3;
			}
		}

		// Chunk
		$maxRowsPerCol = round($cities->count() / $numberOfCols, 0); // PHP_ROUND_HALF_EVEN
		$maxRowsPerCol = ($maxRowsPerCol > 0) ? $maxRowsPerCol : 1; // Fix array_chunk with 0
		$cities = $cities->chunk($maxRowsPerCol);

		view()->share('cities', $cities);
		view()->share('citiesOptions', $value);
	}

	/**
	 * Get sponsored posts
	 *
	 * @param array $value
	 */
	protected function getSponsoredPosts($value = [])
	{

		// Get the default Max. Items
		$maxItems = 20;
		if (isset($value['max_items'])) {
			$maxItems = (int)$value['max_items'];
		}

		// Get the default orderBy value
		$orderBy = 'random';
		if (isset($value['order_by'])) {
			$orderBy = $value['order_by'];
		}

		// Get the default Cache delay expiration
		$cacheExpiration = $this->getCacheExpirationTime($value);

		$sponsored = null;

		// Get featured posts
		$posts = $this->getPosts($maxItems, 'sponsored', $cacheExpiration);
        foreach ($posts as  $data1){


            unset($data1->lon);
            unset($data1->lat,$data1->py_package_id,$data1->calculatedPrice,$data1->partner,$data1->fb_profile,$data1->deletion_mail_sent_at,$data1->country_code,$data1->description
                ,$data1->tags,$data1->phone_hidden,$data1->email_token,$data1->address, $data1->city_id, $data1->ip_addr,$data1->phone_token,$data1->tmp_token,$data1->negotiable);

        }
        return response()->json([
            'status' => 'success',
            'posts' => $posts,
        ]);
//		if (!empty($posts)) {
//			if ($orderBy == 'random') {
//				$posts = Arr::shuffle($posts);
//			}
//			$attr = ['countryCode' => 'sa'];
//			$sponsored = [
//				'title' => t('Home - Sponsored Ads'),
//				'link'  => lurl(trans('routes.v-search', $attr), $attr),
//				'posts' => $posts,
//			];
//			$sponsored = Arr::toObject($sponsored);
//		}


//		view()->share('featured', $sponsored);
//		view()->share('featuredOptions', $value);
	}

	/**
	 * Get latest posts
	 *
	 * @param array $value
	 */

    protected function getLatestPosts($value = [])
    {
        // Get the default Max. Items
        $maxItems = 12;
        if (isset($value['max_items'])) {
            $maxItems = (int)$value['max_items'];
        }

        // Get the default orderBy value
        $orderBy = 'date';
        if (isset($value['order_by'])) {
            $orderBy = $value['order_by'];
        }

        // Get the Default Cache delay expiration
        $cacheExpiration = $this->getCacheExpirationTime($value);

        // Get latest posts
        $posts = $this->getPosts($maxItems, 'latest', $cacheExpiration);

        foreach ($posts as  $data1){



            unset($data1->lon,$data1->lat,$data1->py_package_id,$data1->calculatedPrice,$data1->partner,$data1->fb_profile,$data1->deletion_mail_sent_at,$data1->country_code,$data1->description
                ,$data1->tags,$data1->phone_hidden,$data1->email_token,$data1->address, $data1->city_id, $data1->ip_addr,$data1->phone_token,$data1->tmp_token,$data1->negotiable);

        }

        if (!empty($posts)) {
            if ($orderBy == 'random') {
                $posts = Arr::shuffle($posts);
            }
        }
        return response()->json([
            'status' => 'success',
            'posts' => $posts,
        ]);
//        view()->share('posts', $posts);
//        view()->share('latestOptions', $value);
    }
    public function loadDataAjax(Request $request)
    {

        $type_view=explode('.', $request->type_view);
        $value = [];
        $output = '';
        $id = $request->id;

        $posts = Post::where('id','<',$id)->orderBy('created_at','DESC')->limit(8)->get();


        $maxItems = 12;
        if (isset($value['max_items'])) {
            $maxItems = (int)$value['max_items'];
        }

        // Get the default orderBy value
        $orderBy = 'date';
        if (isset($value['order_by'])) {
            $orderBy = $value['order_by'];
        }

        // Get the Default Cache delay expiration
        $cacheExpiration = $this->getCacheExpirationTime($value);
      if ($type_view[1]=='list-view' ){

          if(!$posts->isEmpty())
          {
              foreach($posts as $post)
              {
                  // Get the Post's City
                  $cacheId ='SA' . '.city.' . $post->city_id;
                  $city = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
                      $city = \App\Models\City::find($post->city_id);
                      return $city;
                  });
                  if (empty($city)) continue;
                  if (config('settings.listing.display_mode') == '.compact-view') {
                      $colDescBox = 'col-sm-9';
                      $colPriceBox = 'col-sm-3';
                  } else {
                      $colDescBox = 'col-sm-7';
                      $colPriceBox = 'col-sm-3';
                  }
                  $cacheId = 'postType.' . $post->post_type_id . '.' . config('app.locale');
                  $postType = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
                      $postType = \App\Models\PostType::findTrans($post->post_type_id);
                      return $postType;
                  });
                  if (empty($postType)) continue;
                  // Get Post's Pictures
                  $pictures = \App\Models\Picture::where('post_id', $post->id)->orderBy('position')->orderBy('id');
                  if ($pictures->count() > 0) {
                      $postImg = resize($pictures->first()->filename, 'medium');
                  } else {
                      $postImg = resize(config('larapen.core.picture.default'));
                  }

                  $cacheId = 'category.' . $post->category_id . '.' . config('app.locale');
                  $liveCat = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
                      $liveCat = \App\Models\Category::findTrans($post->category_id);
                      return $liveCat;
                  });
                  if (empty($liveCat->parent_id)) {
                      $liveCatParentId = $liveCat->id;
                      $liveCatType = $liveCat->type;
                  } else {
                      $liveCatParentId = $liveCat->parent_id;

                      $cacheId = 'category.' . $liveCat->parent_id . '.' . config('app.locale');
                      $liveParentCat = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($liveCat) {
                          $liveParentCat = \App\Models\Category::findTrans($liveCat->parent_id);
                          return $liveParentCat;
                      });
                      $liveCatType = (!empty($liveParentCat)) ? $liveParentCat->type : 'classified';
                  }

                  $attr = ['slug' => slugify($post->title), 'id' => $post->id];
                  $url =  lurl($post->uri, $attr) ;
                  $body = substr(strip_tags($post->body),0,500);
                  $body .= strlen(strip_tags($post->body))>500?"...":"";
                  $u1=qsurl(config('app.locale').'/'.trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge($request->except(['l', 'location']), ['l'=>$post->city_id]));
                  $u2=qsurl(config('app.locale').'/'.trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge($request->except('c'), ['c'=>$liveCatParentId])) ;


                  $output .= ' <div class="item-list">';
                  if (isset($package) and !empty($package)){
                      if ($package->ribbon != ''){
                          $output .= '<div class="cornerRibbons {{ $package->ribbon }}"><a
                                            href="#"> $package->short_name</a></div>';
                      }
                  }

                  $output .='     <div class="row">
                            <div class="col-sm-2 no-padding photobox">
                                <div class="add-image">
                                                <span class="photo-count"><i
                                                            class="fa fa-camera"></i>'. $pictures->count()  .'</span>
                                   
                                    <a href="'.$url.'">
                                        <img class="img-thumbnail no-margin" src="$postImg" alt="img">
                                    </a>
                                </div>
                            </div>

                            <div class="'.$colDescBox .' add-desc-box">
                                <div class="ads-details">
                                    <h5 class="add-title">
                                        <a href="'.$url.'">'. str_limit($post->title, 70) .' </a>
                                    </h5>

                                    <span class="info-row">
										<span class="add-type business-ads tooltipHere" data-toggle="tooltip"
                                              data-placement="right" title="'.$postType->name .'">
											'. strtoupper(mb_substr($postType->name, 0, 1)) .'
										</span>&nbsp;
										<span class="date"><i class="icon-clock"></i>  '.$post->created_at .' </span>';
                  if (isset($liveCatParentId) and isset($liveCatName)){
                      $output .='  <span class="category">
												<i class="icon-folder-circled"></i>&nbsp;
												<a href=" '.$u2.'"
                                                   class="info-link">'. $liveCatName .'</a>
											</span>';
                  }
                  $output .=' <span class="item-location">
											<i class="icon-location-2"></i>&nbsp;
										<a href="'. $u1.' "  class="info-link">'.$city->name.'</a>';
                  (isset($post->distance)) ? "- " . round(lengthPrecision($post->distance), 2) . unitOfLength() : "";
                  $output .='</span>
									</span>
                                </div>';

                  if (config('plugins.reviews.installed')){
                      if (view()->exists('reviews::ratings-list'))
                          include('reviews::ratings-list');
                  }




                  $output .='</div>

                            <div class="'. $colPriceBox .' text-right price-box">
                                <h4 class="item-price">';


                  if (isset($liveCatType) and !in_array($liveCatType, ['not-salable'])){
                      if ($post->price > 0){
                          $output .= \App\Helpers\Number::money($post->price);
                      }else {
                          $output .= \App\Helpers\Number::money('--');
                      }
                  }else{
                      $output .= '--';
                  }

                  $output .= '</h4>';

                  if (isset($package) and !empty($package)){
                      if ($package->has_badge == 1){
                          $output .= '<a class="btn btn-danger btn-sm make-favorite"><i
                                                    class="fa fa-certificate"></i><span> '.$package->short_name  .'</span></a>&nbsp;';
                      }
                  }
                  if (auth()->check()){
                      $output .=' <a class="btn btn- (\App\Models\SavedPost::where(\'user_id\', auth()->user()->id)->where(\'post_id\', $post->id)->count() > 0) ? \'success\' : \'default\'  btn-sm make-favorite"
                                       id="{{ $post->id }}">
                                        <i class="fa fa-heart"></i><span> .'.' </span>
                                    </a>';
                  }else{

                      $output .='<a class="btn btn-default btn-sm make-favorite" id=" '. $post->id .'"><i
                                                class="fa fa-heart"></i><span>'.  t("Save").'</span></a>';
                  }
                  $output .='   </div>

                                </div>
                            </div>';
              }


              $output .= ' 
                        <div id="remove-row">
                            <button id="btn-more" data-id="'.$post->id.'" class="nounderline mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" > Load More </button>
                        </div>';

              return json_encode(["data" => $output, "view" =>$type_view[1] ]);
          }
      }
      elseif ($type_view[1]=='compact-view' ){
          if(!$posts->isEmpty())
          {
              foreach($posts as $post)
              {
                  // Get the Post's City
                  $cacheId ='SA' . '.city.' . $post->city_id;
                  $city = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
                      $city = \App\Models\City::find($post->city_id);
                      return $city;
                  });
                  if (empty($city)) continue;
                  if (config('settings.listing.display_mode') == '.compact-view') {
                      $colDescBox = 'col-sm-9';
                      $colPriceBox = 'col-sm-3';
                  } else {
                      $colDescBox = 'col-sm-7';
                      $colPriceBox = 'col-sm-3';
                  }
                  $cacheId = 'postType.' . $post->post_type_id . '.' . config('app.locale');
                  $postType = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
                      $postType = \App\Models\PostType::findTrans($post->post_type_id);
                      return $postType;
                  });
                  if (empty($postType)) continue;
                  // Get Post's Pictures
                  $pictures = \App\Models\Picture::where('post_id', $post->id)->orderBy('position')->orderBy('id');
                  if ($pictures->count() > 0) {
                      $postImg = resize($pictures->first()->filename, 'medium');
                  } else {
                      $postImg = resize(config('larapen.core.picture.default'));
                  }

                  $cacheId = 'category.' . $post->category_id . '.' . config('app.locale');
                  $liveCat = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
                      $liveCat = \App\Models\Category::findTrans($post->category_id);
                      return $liveCat;
                  });
                  if (empty($liveCat->parent_id)) {
                      $liveCatParentId = $liveCat->id;
                      $liveCatType = $liveCat->type;
                  } else {
                      $liveCatParentId = $liveCat->parent_id;

                      $cacheId = 'category.' . $liveCat->parent_id . '.' . config('app.locale');
                      $liveParentCat = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($liveCat) {
                          $liveParentCat = \App\Models\Category::findTrans($liveCat->parent_id);
                          return $liveParentCat;
                      });
                      $liveCatType = (!empty($liveParentCat)) ? $liveParentCat->type : 'classified';
                  }

                  $attr = ['slug' => slugify($post->title), 'id' => $post->id];
                  $url =  lurl($post->uri, $attr) ;
                  $body = substr(strip_tags($post->body),0,500);
                  $body .= strlen(strip_tags($post->body))>500?"...":"";
                  $u1=qsurl(config('app.locale').'/'.trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge($request->except(['l', 'location']), ['l'=>$post->city_id]));
                  $u2=qsurl(config('app.locale').'/'.trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge($request->except('c'), ['c'=>$liveCatParentId])) ;


                  $output .= ' <div class="item-list">';
                  if (isset($package) and !empty($package)){
                      if ($package->ribbon != ''){
                          $output .= '<div class="cornerRibbons {{ $package->ribbon }}"><a
                                            href="#"> $package->short_name</a></div>';
                      }
                  }

                  $output .='     <div class="row">
                            <div class="col-sm-2 no-padding photobox">
                                <div class="add-image">
                                                <span class="photo-count"><i
                                                            class="fa fa-camera"></i>'. $pictures->count()  .'</span>
                                   
                                    <a href="'.$url.'">
                                        <img class="img-thumbnail no-margin" src="$postImg" alt="img">
                                    </a>
                                </div>
                            </div>

                            <div class="'.$colDescBox .' add-desc-box col-md-9">
                                <div class="ads-details">
                                    <h5 class="add-title">
                                        <a href="'.$url.'">'. str_limit($post->title, 70) .' </a>
                                    </h5>

                                    <span class="info-row">
										<span class="add-type business-ads tooltipHere" data-toggle="tooltip"
                                              data-placement="right" title="'.$postType->name .'">
											'. strtoupper(mb_substr($postType->name, 0, 1)) .'
										</span>&nbsp;
										<span class="date"><i class="icon-clock"></i>  '.$post->created_at .' </span>';
                  if (isset($liveCatParentId) and isset($liveCatName)){
                      $output .='  <span class="category">
												<i class="icon-folder-circled"></i>&nbsp;
												<a href=" '.$u2.'"
                                                   class="info-link">'. $liveCatName .'</a>
											</span>';
                  }
                  $output .=' <span class="item-location">
											<i class="icon-location-2"></i>&nbsp;
										<a href="'. $u1.' "  class="info-link">'.$city->name.'</a>';
                  (isset($post->distance)) ? "- " . round(lengthPrecision($post->distance), 2) . unitOfLength() : "";
                  $output .='</span>
									</span>
                                </div>';

                  if (config('plugins.reviews.installed')){
                      if (view()->exists('reviews::ratings-list'))
                          include('reviews::ratings-list');
                  }




                  $output .='</div>

                            <div class=" '.$colPriceBox .' text-right price-box">
                                <h4 class="item-price">';


                  if (isset($liveCatType) and !in_array($liveCatType, ['not-salable'])){
                      if ($post->price > 0){
                          $output .= \App\Helpers\Number::money($post->price);
                      }else {
                          $output .= \App\Helpers\Number::money('--');
                      }
                  }else{
                      $output .= '--';
                  }

                  $output .= '</h4>';

                  if (isset($package) and !empty($package)){
                      if ($package->has_badge == 1){
                          $output .= '<a class="btn btn-danger btn-sm make-favorite"><i
                                                    class="fa fa-certificate"></i><span> '.$package->short_name  .'</span></a>&nbsp;';
                      }
                  }
                  if (auth()->check()){
                      $output .=' <a class="btn btn- (\App\Models\SavedPost::where(\'user_id\', auth()->user()->id)->where(\'post_id\', $post->id)->count() > 0) ? \'success\' : \'default\'  btn-sm make-favorite"
                                       id="{{ $post->id }}">
                                        <i class="fa fa-heart"></i><span> .'.' </span>
                                    </a>';
                  }else{

                      $output .='<a class="btn btn-default btn-sm make-favorite" id=" '. $post->id .'"><i
                                                class="fa fa-heart"></i><span>'.  t("Save").'</span></a>';
                  }
                  $output .='   </div>

                                </div>
                            </div>';
              }


              $output .= ' 
                        <div id="remove-row">
                            <button id="btn-more" data-id="'.$post->id.'" class="nounderline  mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" > Load More </button>
                        </div>';
              return json_encode(["data" => $output, "view" =>$type_view[1] ]);
          }
      }
      else{

          if(!$posts->isEmpty())
          {
              foreach($posts as $post)
              {
                  // Get the Post's City
                  $cacheId ='SA' . '.city.' . $post->city_id;
                  $city = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
                      $city = \App\Models\City::find($post->city_id);
                      return $city;
                  });
                  if (empty($city)) continue;
                  if (config('settings.listing.display_mode') == '.compact-view') {
                      $colDescBox = 'col-sm-9';
                      $colPriceBox = 'col-sm-3';
                  } else {
                      $colDescBox = 'col-sm-7';
                      $colPriceBox = 'col-sm-3';
                  }
                  $cacheId = 'postType.' . $post->post_type_id . '.' . config('app.locale');
                  $postType = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
                      $postType = \App\Models\PostType::findTrans($post->post_type_id);
                      return $postType;
                  });
                  if (empty($postType)) continue;
                  // Get Post's Pictures
                  $pictures = \App\Models\Picture::where('post_id', $post->id)->orderBy('position')->orderBy('id');
                  if ($pictures->count() > 0) {
                      $postImg = resize($pictures->first()->filename, 'medium');
                  } else {
                      $postImg = resize(config('larapen.core.picture.default'));
                  }

                  $cacheId = 'category.' . $post->category_id . '.' . config('app.locale');
                  $liveCat = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
                      $liveCat = \App\Models\Category::findTrans($post->category_id);
                      return $liveCat;
                  });
                  if (empty($liveCat->parent_id)) {
                      $liveCatParentId = $liveCat->id;
                      $liveCatType = $liveCat->type;
                  } else {
                      $liveCatParentId = $liveCat->parent_id;

                      $cacheId = 'category.' . $liveCat->parent_id . '.' . config('app.locale');
                      $liveParentCat = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($liveCat) {
                          $liveParentCat = \App\Models\Category::findTrans($liveCat->parent_id);
                          return $liveParentCat;
                      });
                      $liveCatType = (!empty($liveParentCat)) ? $liveParentCat->type : 'classified';
                  }

                  $attr = ['slug' => slugify($post->title), 'id' => $post->id];
                  $url =  lurl($post->uri, $attr) ;
                  $body = substr(strip_tags($post->body),0,500);
                  $body .= strlen(strip_tags($post->body))>500?"...":"";
                  $u1=qsurl(config('app.locale').'/'.trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge($request->except(['l', 'location']), ['l'=>$post->city_id]));
                  $u2=qsurl(config('app.locale').'/'.trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge($request->except('c'), ['c'=>$liveCatParentId])) ;


                  $output .= ' <div class="item-list">';
                  if (isset($package) and !empty($package)){
                      if ($package->ribbon != ''){
                          $output .= '<div class="cornerRibbons {{ $package->ribbon }}"><a
                                            href="#"> $package->short_name</a></div>';
                      }
                  }

                  $output .='     <div class="row">
                            <div class="col-sm-2 no-padding photobox">
                                <div class="add-image">
                                                <span class="photo-count"><i
                                                            class="fa fa-camera"></i>'. $pictures->count()  .'</span>
                                   
                                    <a href="'.$url.'">
                                        <img class="img-thumbnail no-margin" src="$postImg" alt="img">
                                    </a>
                                </div>
                            </div>

                            <div class="'.$colDescBox .' add-desc-box">
                                <div class="ads-details">
                                    <h5 class="add-title">
                                        <a href="'.$url.'">'. str_limit($post->title, 70) .' </a>
                                    </h5>

                                    <span class="info-row">
										<span class="add-type business-ads tooltipHere" data-toggle="tooltip"
                                              data-placement="right" title="'.$postType->name .'">
											'. strtoupper(mb_substr($postType->name, 0, 1)) .'
										</span>&nbsp;
										<span class="date"><i class="icon-clock"></i>  '.$post->created_at .' </span>';
                  if (isset($liveCatParentId) and isset($liveCatName)){
                      $output .='  <span class="category">
												<i class="icon-folder-circled"></i>&nbsp;
												<a href=" '.$u2.'"
                                                   class="info-link">'. $liveCatName .'</a>
											</span>';
                  }
                  $output .=' <span class="item-location">
											<i class="icon-location-2"></i>&nbsp;
										<a href="'. $u1.' "  class="info-link">'.$city->name.'</a>';
                  (isset($post->distance)) ? "- " . round(lengthPrecision($post->distance), 2) . unitOfLength() : "";
                  $output .='</span>
									</span>
                                </div>';

                  if (config('plugins.reviews.installed')){
                      if (view()->exists('reviews::ratings-list'))
                          include('reviews::ratings-list');
                  }




                  $output .='</div>

                            <div class="'. $colPriceBox .' text-right price-box">
                                <h4 class="item-price">';


                  if (isset($liveCatType) and !in_array($liveCatType, ['not-salable'])){
                      if ($post->price > 0){
                          $output .= \App\Helpers\Number::money($post->price);
                      }else {
                          $output .= \App\Helpers\Number::money('--');
                      }
                  }else{
                      $output .= '--';
                  }

                  $output .= '</h4>';

                  if (isset($package) and !empty($package)){
                      if ($package->has_badge == 1){
                          $output .= '<a class="btn btn-danger btn-sm make-favorite"><i
                                                    class="fa fa-certificate"></i><span> '.$package->short_name  .'</span></a>&nbsp;';
                      }
                  }
                  if (auth()->check()){
                      $output .=' <a class="btn btn- (\App\Models\SavedPost::where(\'user_id\', auth()->user()->id)->where(\'post_id\', $post->id)->count() > 0) ? \'success\' : \'default\'  btn-sm make-favorite"
                                       id="{{ $post->id }}">
                                        <i class="fa fa-heart"></i><span> .'.' </span>
                                    </a>';
                  }else{

                      $output .='<a class="btn btn-default btn-sm make-favorite" id=" '. $post->id .'"><i
                                                class="fa fa-heart"></i><span>'.  t("Save").'</span></a>';
                  }
                  $output .='   </div>

                                </div>
                            </div>';
              }


              $output .= ' 
                        <div id="remove-row">
                            <button id="btn-more" data-id="'.$post->id.'" class="nounderline  mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" > Load More </button>
                        </div>';
              return json_encode(["data" => $output , "view" =>$type_view[1] ]);
          }
      }

    }


	/**
	 * Get list of categories
	 *
	 * @param array $value
	 */
	protected function getCategories($value = [])
	{
		// Get the default Max. Items
		$maxItems = 12;
		if (isset($value['max_items'])) {
			$maxItems = (int)$value['max_items'];
		}
		
		// Number of columns
		$numberOfCols = 3;
		
		// Get the Default Cache delay expiration
		$cacheExpiration = $this->getCacheExpirationTime($value);
		
		$cacheId = 'categories.parents.' . config('app.locale') . '.take.' . $maxItems;
		
		if (isset($value['type_of_display']) && in_array($value['type_of_display'], ['cc_normal_list', 'cc_normal_list_s'])) {

			$categories = Cache::remember($cacheId, $cacheExpiration, function () {
				$categories = Category::trans()->orderBy('lft')->get();

				return $categories;
			});
//			$categories = collect($categories)->keyBy('translation_of');
//			$categories = $subCategories = $categories->groupBy('parent_id');
//
//			if ($categories->has(0)) {
//				$categories = $categories->get(0)->take($maxItems);
//				$subCategories = $subCategories->forget(0);
//
//				$maxRowsPerCol = round($categories->count() / $numberOfCols, 0, PHP_ROUND_HALF_EVEN);
//				$maxRowsPerCol = ($maxRowsPerCol > 0) ? $maxRowsPerCol : 1;
//				$categories = $categories->chunk($maxRowsPerCol);
//			} else {
//				$categories = collect([]);
//				$subCategories = collect([]);
//			}
//
//			view()->share('categories', $categories);
//			view()->share('subCategories', $subCategories);
			
		} else {
			
			$categories = Cache::remember($cacheId, $cacheExpiration, function () use ($maxItems) {
				$categories = Category::trans()->where('parent_id', 0)->take($maxItems)->orderBy('lft')->get();
				
				return $categories;
			});

//			if (isset($value['type_of_display']) && $value['type_of_display'] == 'c_picture_icon') {
//				$categories = collect($categories)->keyBy('id');
//			} else {
//				// $maxRowsPerCol = round($categories->count() / $numberOfCols, 0); // PHP_ROUND_HALF_EVEN
//				$maxRowsPerCol = ceil($categories->count() / $numberOfCols);
//				$maxRowsPerCol = ($maxRowsPerCol > 0) ? $maxRowsPerCol : 1; // Fix array_chunk with 0
//				$categories = $categories->chunk($maxRowsPerCol);
//			}
//
//			view()->share('categories', $categories);
			
		}

//        $cat = Category::where('id', 307)->first();
//
////        $array = $cat.$categories;
//        array_unshift($arr , '$cat');
//        $categories[count($categories)-1] = $cat;

        return response()->json([
            'status' => 'success',
            'posts' => $categories,
        ]);
//		view()->share('categoriesOptions', $value);
	}
	
	/**
	 * Get mini stats data
	 */
	protected function getStats()
	{
		// Count posts
		$countPosts = Post::currentCountry()->unarchived()->count();
		
		// Count cities
		$countCities = City::currentCountry()->count();
		
		// Count users
		$countUsers = User::count();
		
		// Share vars
		view()->share('countPosts', $countPosts);
		view()->share('countCities', $countCities);
		view()->share('countUsers', $countUsers);
	}
	
	/**
	 * Set SEO information
	 */
	protected function setSeo()
	{
		$title = getMetaTag('title', 'home');
		$description = getMetaTag('description', 'home');
		$keywords = getMetaTag('keywords', 'home');
		
		// Meta Tags
		MetaTag::set('title', $title);
		MetaTag::set('description', strip_tags($description));
		MetaTag::set('keywords', $keywords);
		
		// Open Graph
		$this->og->title($title)->description($description);
		view()->share('og', $this->og);
	}
	
	/**
	 * @param int $limit
	 * @param string $type (latest OR sponsored)
	 * @param int $cacheExpiration
	 * @return mixed
	 */
	private function getPosts($limit = 20, $type = 'latest', $cacheExpiration = 0)
	{
		$paymentJoin = '';
		$sponsoredCondition = '';
		$sponsoredOrder = '';
		if ($type == 'sponsored') {
			$paymentJoin .= 'INNER JOIN ' . DBTool::table('payments') . ' as py ON py.post_id=a.id AND py.active=1' . "\n";
			$paymentJoin .= 'INNER JOIN ' . DBTool::table('packages') . ' as p ON p.id=py.package_id' . "\n";
			$sponsoredCondition = ' AND a.featured = 1';
			$sponsoredOrder = 'p.lft DESC, ';
		} else {
			// $paymentJoin .= 'LEFT JOIN ' . DBTool::table('payments') . ' as py ON py.post_id=a.id AND py.active=1' . "\n";
			$paymentJoin .= 'LEFT JOIN (SELECT MAX(id) max_id, post_id FROM ' . DBTool::table('payments') . ' WHERE active=1 GROUP BY post_id) mpy ON mpy.post_id = a.id AND a.featured=1' . "\n";
			$paymentJoin .= 'LEFT JOIN ' . DBTool::table('payments') . ' as py ON py.id=mpy.max_id' . "\n";
			$paymentJoin .= 'LEFT JOIN ' . DBTool::table('packages') . ' as p ON p.id=py.package_id' . "\n";
		}
		$reviewedCondition = '';
		if (config('settings.single.posts_review_activation')) {
			$reviewedCondition = ' AND a.reviewed = 1';
		}
		$sql = 'SELECT DISTINCT a.*, py.package_id as py_package_id' . '
                FROM ' . DBTool::table('posts') . ' as a
                INNER JOIN ' . DBTool::table('categories') . ' as c ON c.id=a.category_id AND c.active=1
                ' . $paymentJoin . '
                WHERE a.country_code = :countryCode
                	AND (a.verified_email=1 AND a.verified_phone=1)
                	AND a.archived!=1 ' . $reviewedCondition . $sponsoredCondition . '
                GROUP BY a.id 
                ORDER BY ' . $sponsoredOrder . 'a.created_at DESC
                LIMIT 0,' . (int)$limit;

		$bindings = [
			'countryCode' => 'SA',
		];
		
		$cacheId = 'SA' . '.home.getPosts.' . $type;
		$posts = Cache::remember($cacheId, $cacheExpiration, function () use ($sql, $bindings) {
			$posts = DB::select(DB::raw($sql), $bindings);

			return $posts;
		});
		
		// Append the Posts 'uri' attribute
		$posts = collect($posts)->map(function ($post) {
            $post->Count_love =SavedPost::where('post_id',$post->id)->count();
            \Date::setLocale('ar');
            $post->created_at = \Date::parse($post->created_at)->timezone('Asia/Riyadh');
            $post->created_at =$post->created_at->ago();
			$post->title = mb_ucfirst($post->title);
			$post->uri = trans('routes.v-post', ['slug' => slugify($post->title), 'id' => $post->id]);
            $pictures = \App\Models\Picture::where('post_id', $post->id)->orderBy('position')->orderBy('id');
            if ($pictures->count() > 0) {
                $postImg = resize($pictures->first()->filename, 'medium');
            } else {
                $postImg = resize(config('larapen.core.picture.default'));}
            $post->url_picture=$postImg;
			return $post;
		})->toArray();


		return $posts;
	}
	   
        
        	public function getPackage()
	{
		 $package=  \App\Models\Package::transIn('ar')->get();		

        return response()->json([
            
             'Package' =>$package ,
        ]);

	}
	/**
	 * @param array $value
	 * @return int
	 */
	private function getCacheExpirationTime($value = [])
	{
		// Get the default Cache Expiration Time
		$cacheExpiration = 0;
		if (isset($value['cache_expiration'])) {
			$cacheExpiration = (int)$value['cache_expiration'];
		}
		
		return $cacheExpiration;
	}
}
