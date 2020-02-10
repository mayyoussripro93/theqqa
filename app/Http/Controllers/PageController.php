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

use App\Helpers\Arr;
use App\Http\Requests\ContactRequest;
use App\Models\City;
use App\Models\Package;
use App\Models\Page;
use App\Models\Permission;
use App\Models\Post;
use App\Models\SubAdmin1;
use App\Models\User;
use App\Models\UserType;
use App\Notifications\FormSent;
use Aws\Waf\WafClient;

use Illuminate\Http\Request;
use App\Helpers\DBTool;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Models\ImageService;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Models\Message;
use App\Notifications\SellerContacted;

use Alert;


class PageController extends FrontController
{
    /**
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($slug)
    {
        // Get the Page
        $page = Page::where('slug', $slug)->trans()->first();
        if (empty($page)) {
            abort(404);
        }
        view()->share('page', $page);
        view()->share('uriPathPageSlug', $slug);

        // Check if an external link is available
        if (!empty($page->external_link)) {
            return headerLocation($page->external_link);
        }

        // SEO
        $title = $page->title;
        $description = str_limit(str_strip($page->content), 200);

        // Meta Tags
        MetaTag::set('title', $title);
        MetaTag::set('description', $description);

        // Open Graph
        $this->og->title($title)->description($description);
        if (!empty($page->picture)) {
            if ($this->og->has('image')) {
                $this->og->forget('image')->forget('image:width')->forget('image:height');
            }
            $this->og->image(Storage::url($page->picture), [
                'width'  => 600,
                'height' => 600,
            ]);
        }
        view()->share('og', $this->og);

        return view('pages.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contact()
    {
        // Get the Country's largest city for Google Maps
        $city = City::currentCountry()->orderBy('population', 'desc')->first();
        view()->share('city', $city);

        // Meta Tags
        MetaTag::set('title', getMetaTag('title', 'contact'));
        MetaTag::set('description', strip_tags(getMetaTag('description', 'contact')));
        MetaTag::set('keywords', getMetaTag('keywords', 'contact'));

        return view('pages.contact');
    }

    /**
     * @param ContactRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function contactPost_estimation(ContactRequest $request)
    {

//        session_start();
        // Store Contact Info
        $contactForm = $request->all();
        session()->put('contactForm', $contactForm);
        session()->pull('contactForm.car_Pictures');

        if(!empty($contactForm['car_Pictures'])) {
            //for car Pictures
            $filename_car_Pictures_arr=[];
            $files_car_Pictures= $request->file('car_Pictures');
            foreach ($files_car_Pictures as $key => $file__car_Picture) {

                $extension_car_Pictures = getUploadedFileExtension($file__car_Picture);
                if (empty($extension_car_Pictures)) {
                    $extension_car_Pictures = 'jpg';
                }
                // Make the image
                $image_car_Pictures = Image::make($file__car_Picture)->resize(400, 400, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Generate a filename.
                $filename_car_Pictures = md5($file__car_Picture . time()) . '.' . $extension_car_Pictures;
                array_push($filename_car_Pictures_arr, $filename_car_Pictures);
                $destination_path = 'app/service/'.$request->id_code;
                // Store the image on disk.
                Storage::disk('public')->put($destination_path . '/' . $filename_car_Pictures, $image_car_Pictures->stream());
            }
            $image_serv = new ImageService();
            $image_serv->image_code = implode(',',$filename_car_Pictures_arr);
            $image_serv->image_title = 'car_Pictures';
            $image_serv->token = $request->id_code;
            // Save
            $image_serv->save();
        }
        $request->session()->save();
        return redirect( config('app.locale').'/'.'post/0/paymentservice');

    }
    public function contact_estimation(\Illuminate\Http\Request $request)
    {
        if(!empty($request->id)){
            $p = Post::where ('id',$request->id)->get();
            $post_det = collect($p)->map(function ($post) {
                $post->title = mb_ucfirst($post->title);
                $post->uri = trans('routes.v-post', ['slug' => slugify($post->title), 'id' => $post->id]);

                return $post;
            })->toArray();


            // Get Pack Info
            $package = null;
            $cacheExpiration = (int)config('settings.other.cache_expiration');


            // Get PostType Info
            $cacheId = 'postType.' .$post_det[0]['post_type_id'] . '.' . config('app.locale');
            $postType = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post_det) {
                $postType = \App\Models\PostType::findTrans($post_det[0]['post_type_id']);
                return $postType;
            });


            // Get Post's Pictures
            $pictures = \App\Models\Picture::where('post_id', $post_det[0]['id'])->orderBy('position')->orderBy('id');
            if ($pictures->count() > 0) {
                $postImg = resize($pictures->first()->filename, 'medium');
            } else {
                $postImg = resize(config('larapen.core.picture.default'));
            }

            // Get the Post's City
            $cacheId = config('country.code') . '.city.' . $post_det[0]['city_id'];
            $city = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post_det) {
                $city = \App\Models\City::find( $post_det[0]['city_id']);
                return $city;
            });


            // Convert the created_at date to Carbon object
//            $post_det->created_at = \Date::parse($post_det[0]['created_at'])->timezone(config('timezone.id'));

//            $post_det->created_at =$post_det[0]['created_at']->ago();

            // Category
            $cacheId = 'category.' . $post_det[0]['category_id'] . '.' . config('app.locale');
            $liveCat = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post_det) {
                $liveCat = \App\Models\Category::findTrans($post_det[0]['category_id']);
                return $liveCat;

            });

            // Check parent
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

            // Check translation
            $liveCatName = $liveCat->name;
            $attr = ['slug' => slugify($post_det[0]['title']), 'id' => $post_det[0]['id']];
            $url =  lurl($post_det[0]['uri'], $attr) ;
            $colDescBox = 'col-sm-7';
            $colPriceBox = 'col-sm-3';
            $u1=qsurl(config('app.locale').'/'.trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge($request->except(['l', 'location']), ['l'=>$post_det[0]['city_id']]));
            $u2=qsurl(config('app.locale').'/'.trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge($request->except('c'), ['c'=>$liveCatParentId])) ;

            $output = ' <div class="item-list">';
            if (isset($package) and !empty($package)){
                if ($package->ribbon != ''){
                    $output .= '<div class="cornerRibbons {{ $package->ribbon }}"><a
                                            href="#">'. $package->short_name.'</a></div>';
                }
            }

            $output .='     <div class="row">
                            <div class="col-sm-2 no-padding photobox">
                                <div class="add-image">
                                                <span class="photo-count"><i
                                                            class="fa fa-camera"></i>'. $pictures->count()  .'</span>
                                   
                                    <a href="'.$url.'">
                                        <img class="img-thumbnail no-margin" src="'.$postImg.'" alt="img">
                                    </a>
                                </div>
                            </div>

                            <div class="'.$colDescBox .' add-desc-box">
                                <div class="ads-details">
                                    <h5 class="add-title">
                                        <a href="'.$url.'">'. str_limit($post_det[0]['title'], 70) .' </a>
                                    </h5>

                                    <span class="info-row">
										<span class="add-type business-ads tooltipHere" data-toggle="tooltip"
                                              data-placement="right" title="'.$postType->name .'">
											'. strtoupper(mb_substr($postType->name, 0, 1)) .'
										</span>&nbsp;
										<span class="date"><i class="icon-clock"></i>  '.$post_det[0]['created_at'] .' </span>';
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
            (isset($post_det[0]['distance'])) ? "- " . round(lengthPrecision($post_det[0]['distance']), 2) . unitOfLength() : "";
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
                if ($post_det[0]['price'] > 0){
                    $output .= \App\Helpers\Number::money($post_det[0]['price']);
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
                $output .=' <a class="btn btn- (\App\Models\SavedPost::where(\'user_id\', auth()->user()->id)->where(\'post_id\',$post_det[0][\'id\'] )->count() > 0) ? \'success\' : \'default\'  btn-sm make-favorite"
                                       id="{{ $post_det[0][\'id\']}}">
                                        <i class="fa fa-heart"></i><span> .'.' </span>
                                    </a>';
            }else{

                $output .='<a class="btn btn-default btn-sm make-favorite" id=" '.$post_det[0]['id'] .'"><i
                                                class="fa fa-heart"></i><span>'.  t("Save").'</span></a>';
            }
            $output .='   </div>

                                </div>
                            </div>';
            $attr = ['slug' => slugify($post_det[0]['title']), 'id' => $post_det[0]['id']];
            return json_encode(["data" => $output, "uri" => $post_det[0]['uri'], "attr"=> $attr]);


        }
        $package = Package::applyCurrency()->with('currency')->orderBy('lft')->where('id','21')->first();
        view()->share('package', $package);
        $cities = City::currentCountry()->get();

        view()->share('cities', $cities);
        view()->share('id_code', str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT));

        // Meta Tags
        MetaTag::set('title', t('estimation title'));
        MetaTag::set('description', strip_tags(getMetaTag('description', 'contact')));
        MetaTag::set('keywords', getMetaTag('keywords', 'contact'));

        return view('pages.estimation');
    }
    public function contactPost(ContactRequest $request)
    {
        // Store Contact Info
        $contactForm = $request->all();
        $contactForm['country_code'] = config('country.code');
        $contactForm['country_name'] = config('country.name');
        $contactForm = Arr::toObject($contactForm);

        // Send Contact Email
        try {
            if (config('settings.app.email')) {
                Notification::route('mail', config('settings.app.email'))->notify(new FormSent($contactForm));
            } else {
                $admins = User::permission(Permission::getStaffPermissions())->get();
                if ($admins->count() > 0) {
                    Notification::send($admins, new FormSent($contactForm));
                    /*
                    foreach ($admins as $admin) {
                        Notification::route('mail', $admin->email)->notify(new FormSent($contactForm));
                    }
                    */
                }
            }
            flash(t("Your message has been sent to our moderators. Thank you"))->success();
        } catch (\Exception $e) {
            flash($e->getMessage())->error();
        }

        return redirect(config('app.locale') . '/' . trans('routes.contact'));
    }
    public function contact_mogaz(\Illuminate\Http\Request $request)
    {
//        $joj=        htmlspecialchars(t('For_service_alert'),ENT_QUOTES);
//
////        {!t('For_service_alert')!};
//        alert()->info($joj,t( 'To Request This Service'))->persistent("Close this");

        if(!empty($request->id)){
            $p = Post::where ('id',$request->id)->get();
            $post_det = collect($p)->map(function ($post) {
                $post->title = mb_ucfirst($post->title);
                $post->uri = trans('routes.v-post', ['slug' => slugify($post->title), 'id' => $post->id]);

                return $post;
            })->toArray();


            // Get Pack Info
            $package = null;
            $cacheExpiration = (int)config('settings.other.cache_expiration');


            // Get PostType Info
            $cacheId = 'postType.' .$post_det[0]['post_type_id'] . '.' . config('app.locale');
            $postType = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post_det) {
                $postType = \App\Models\PostType::findTrans($post_det[0]['post_type_id']);
                return $postType;
            });


            // Get Post's Pictures
            $pictures = \App\Models\Picture::where('post_id', $post_det[0]['id'])->orderBy('position')->orderBy('id');
            if ($pictures->count() > 0) {
                $postImg = resize($pictures->first()->filename, 'medium');
            } else {
                $postImg = resize(config('larapen.core.picture.default'));
            }

            // Get the Post's City
            $cacheId = config('country.code') . '.city.' . $post_det[0]['city_id'];
            $city = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post_det) {
                $city = \App\Models\City::find( $post_det[0]['city_id']);
                return $city;
            });


            // Convert the created_at date to Carbon object
//            $post_det->created_at = \Date::parse($post_det[0]['created_at'])->timezone(config('timezone.id'));

//            $post_det->created_at =$post_det[0]['created_at']->ago();

            // Category
            $cacheId = 'category.' . $post_det[0]['category_id'] . '.' . config('app.locale');
            $liveCat = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post_det) {
                $liveCat = \App\Models\Category::findTrans($post_det[0]['category_id']);
                return $liveCat;

            });

            // Check parent
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

            // Check translation
            $liveCatName = $liveCat->name;
            $attr = ['slug' => slugify($post_det[0]['title']), 'id' => $post_det[0]['id']];
            $url =  lurl($post_det[0]['uri'], $attr) ;
            $colDescBox = 'col-sm-7';
            $colPriceBox = 'col-sm-3';
            $u1=qsurl(config('app.locale').'/'.trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge($request->except(['l', 'location']), ['l'=>$post_det[0]['city_id']]));
            $u2=qsurl(config('app.locale').'/'.trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge($request->except('c'), ['c'=>$liveCatParentId])) ;

            $output = ' <div class="item-list">';
            if (isset($package) and !empty($package)){
                if ($package->ribbon != ''){
                    $output .= '<div class="cornerRibbons {{ $package->ribbon }}"><a
                                            href="#">'. $package->short_name.'</a></div>';
                }
            }

            $output .='     <div class="row">
                            <div class="col-sm-2 no-padding photobox">
                                <div class="add-image">
                                                <span class="photo-count"><i
                                                            class="fa fa-camera"></i>'. $pictures->count()  .'</span>
                                   
                                    <a href="'.$url.'">
                                        <img class="img-thumbnail no-margin" src="'.$postImg.'" alt="img">
                                    </a>
                                </div>
                            </div>

                            <div class="'.$colDescBox .' add-desc-box">
                                <div class="ads-details">
                                    <h5 class="add-title">
                                        <a href="'.$url.'">'. str_limit($post_det[0]['title'], 70) .' </a>
                                    </h5>

                                    <span class="info-row">
										<span class="add-type business-ads tooltipHere" data-toggle="tooltip"
                                              data-placement="right" title="'.$postType->name .'">
											'. strtoupper(mb_substr($postType->name, 0, 1)) .'
										</span>&nbsp;
										<span class="date"><i class="icon-clock"></i>  '.$post_det[0]['created_at'] .' </span>';
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
            (isset($post_det[0]['distance'])) ? "- " . round(lengthPrecision($post_det[0]['distance']), 2) . unitOfLength() : "";
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
                if ($post_det[0]['price'] > 0){
                    $output .= \App\Helpers\Number::money($post_det[0]['price']);
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
                $output .=' <a class="btn btn- (\App\Models\SavedPost::where(\'user_id\', auth()->user()->id)->where(\'post_id\',$post_det[0][\'id\'] )->count() > 0) ? \'success\' : \'default\'  btn-sm make-favorite"
                                       id="{{ $post_det[0][\'id\']}}">
                                        <i class="fa fa-heart"></i><span> .'.' </span>
                                    </a>';
            }else{

                $output .='<a class="btn btn-default btn-sm make-favorite" id=" '.$post_det[0]['id'] .'"><i
                                                class="fa fa-heart"></i><span>'.  t("Save").'</span></a>';
            }
            $output .='   </div>

                                </div>
                            </div>';
            $attr = ['slug' => slugify($post_det[0]['title']), 'id' => $post_det[0]['id']];
            return json_encode(["data" => $output, "uri" => $post_det[0]['uri'], "attr"=> $attr]);


        }
        $cities = City::currentCountry()->get();
        $package = Package::applyCurrency()->with('currency')->orderBy('lft')->where('id','9')->first();
        view()->share('package', $package);
        view()->share('cities', $cities);
        view()->share('id_code', str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT));
        // Meta Tags
        MetaTag::set('title', t('mogaz_title'));
        MetaTag::set('description', strip_tags(getMetaTag('description', 'contact')));
        MetaTag::set('keywords', getMetaTag('keywords', 'contact'));

        return view('pages.mogaz');
    }
    public function contactPost_mogaz(ContactRequest $request)
    {
        $contactForm = $request->all();

        Session::put('contactForm', $contactForm);
        session()->pull('contactForm.car_Pictures');
        $request->session()->save();

        return redirect( config('app.locale').'/'.'post/0/paymentservice');

    }

    public function contact_ownership(Request $request)
    {
        if(!empty($request->id)){
            $p = Post::where ('id',$request->id)->get();
            $post_det = collect($p)->map(function ($post) {
                $post->title = mb_ucfirst($post->title);
                $post->uri = trans('routes.v-post', ['slug' => slugify($post->title), 'id' => $post->id]);

                return $post;
            })->toArray();


            // Get Pack Info
            $package = null;
            $cacheExpiration = (int)config('settings.other.cache_expiration');


            // Get PostType Info
            $cacheId = 'postType.' .$post_det[0]['post_type_id'] . '.' . config('app.locale');
            $postType = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post_det) {
                $postType = \App\Models\PostType::findTrans($post_det[0]['post_type_id']);
                return $postType;
            });


            // Get Post's Pictures
            $pictures = \App\Models\Picture::where('post_id', $post_det[0]['id'])->orderBy('position')->orderBy('id');
            if ($pictures->count() > 0) {
                $postImg = resize($pictures->first()->filename, 'medium');
            } else {
                $postImg = resize(config('larapen.core.picture.default'));
            }

            // Get the Post's City
            $cacheId = config('country.code') . '.city.' . $post_det[0]['city_id'];
            $city = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post_det) {
                $city = \App\Models\City::find( $post_det[0]['city_id']);
                return $city;
            });


            // Convert the created_at date to Carbon object
//            $post_det->created_at = \Date::parse($post_det[0]['created_at'])->timezone(config('timezone.id'));

//            $post_det->created_at =$post_det[0]['created_at']->ago();

            // Category
            $cacheId = 'category.' . $post_det[0]['category_id'] . '.' . config('app.locale');
            $liveCat = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post_det) {
                $liveCat = \App\Models\Category::findTrans($post_det[0]['category_id']);
                return $liveCat;

            });

            // Check parent
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

            // Check translation
            $liveCatName = $liveCat->name;
            $attr = ['slug' => slugify($post_det[0]['title']), 'id' => $post_det[0]['id']];
            $url =  lurl($post_det[0]['uri'], $attr) ;
            $colDescBox = 'col-sm-7';
            $colPriceBox = 'col-sm-3';
            $u1=qsurl(config('app.locale').'/'.trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge($request->except(['l', 'location']), ['l'=>$post_det[0]['city_id']]));
            $u2=qsurl(config('app.locale').'/'.trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge($request->except('c'), ['c'=>$liveCatParentId])) ;

            $output = ' <div class="item-list">';
            if (isset($package) and !empty($package)){
                if ($package->ribbon != ''){
                    $output .= '<div class="cornerRibbons {{ $package->ribbon }}"><a
                                            href="#"> '.$package->short_name.'</a></div>';
                }
            }

            $output .='     <div class="row">
                            <div class="col-sm-2 no-padding photobox">
                                <div class="add-image">
                                                <span class="photo-count"><i
                                                            class="fa fa-camera"></i>'. $pictures->count()  .'</span>
                                   
                                    <a href="'.$url.'">
                                        <img class="img-thumbnail no-margin" src="'.$postImg.'" alt="img">
                                    </a>
                                </div>
                            </div>

                            <div class="'.$colDescBox .' add-desc-box">
                                <div class="ads-details">
                                    <h5 class="add-title">
                                        <a href="'.$url.'">'. str_limit($post_det[0]['title'], 70) .' </a>
                                    </h5>

                                    <span class="info-row">
										<span class="add-type business-ads tooltipHere" data-toggle="tooltip"
                                              data-placement="right" title="'.$postType->name .'">
											'. strtoupper(mb_substr($postType->name, 0, 1)) .'
										</span>&nbsp;
										<span class="date"><i class="icon-clock"></i>  '.$post_det[0]['created_at'] .' </span>';
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
            (isset($post_det[0]['distance'])) ? "- " . round(lengthPrecision($post_det[0]['distance']), 2) . unitOfLength() : "";
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
                if ($post_det[0]['price'] > 0){
                    $output .= \App\Helpers\Number::money($post_det[0]['price']);
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
                $output .=' <a class="btn btn- (\App\Models\SavedPost::where(\'user_id\', auth()->user()->id)->where(\'post_id\',$post_det[0][\'id\'] )->count() > 0) ? \'success\' : \'default\'  btn-sm make-favorite"
                                       id="{{ $post_det[0][\'id\']}}">
                                        <i class="fa fa-heart"></i><span> .'.' </span>
                                    </a>';
            }else{

                $output .='<a class="btn btn-default btn-sm make-favorite" id=" '.$post_det[0]['id'] .'"><i
                                                class="fa fa-heart"></i><span>'.  t("Save").'</span></a>';
            }
            $output .='   </div>

                                </div>
                            </div>';
            $attr = ['slug' => slugify($post_det[0]['title']), 'id' => $post_det[0]['id']];
            return json_encode(["data" => $output, "uri" => $post_det[0]['uri'], "attr"=> $attr]);


        }
        // Get the Country's largest city for Google Maps



        $subladmin1s = City::currentCountry()->orderBy('name')->get();


        if($request->ajax() && !empty($request->lat)){
            $nearest  = DB::select("
                        select 
                           111.111 *
                            DEGREES(ACOS(LEAST(COS(RADIANS(theqqacities.latitude))
                                 * COS(RADIANS($request->lat))
                                 * COS(RADIANS(theqqacities.longitude - $request->lng))
                                 + SIN(RADIANS(theqqacities.latitude))
                                 * SIN(RADIANS($request->lat)), 1.0))) AS distance_in_km,id
                        
                        from theqqacities
                        order by distance_in_km
                        limit 1
                ");

            if(empty($nearest)){
                return response()->json(['status'=>'success', "city"=>[], "shipping_users" => []]);
            }
//            die(var_dump($nearest[0]->id));
            $city = City::currentCountry()->where('id', $nearest[0]->id)->first();
            if(!empty($city)){
                $exhibitionsusers= User::where('user_type_id',6)->whereRaw("find_in_set('$city->id',cities_ids)")->get();
            }else{
                $exhibitionsusers = [];
            }


            return response()->json(['status'=>'success', "city"=>$city, "exhibitionsusers" => $exhibitionsusers]);
        }

        if($request->ajax() && !empty($request->aId)){
            $city = City::currentCountry()->where('id', $request->aId)->first();
            if(!empty($city)){
                $exhibitionsusers= User::where('user_type_id',6)->whereRaw("find_in_set('$city->id',cities_ids)")->get();
            }else{
                $exhibitionsusers = [];
            }


            return response()->json(['status'=>'success', "city"=>$city, "exhibitionsusers" => $exhibitionsusers]);

        }

        $city = City::currentCountry()->orderBy('population', 'desc')->first();
        view()->share('subladmin1s', $subladmin1s);
        view()->share('city', $city);

        $exhibitionsusers= User::where('user_type_id',6)->get();
        $this->package = Package::applyCurrency()->with('currency')->orderBy('lft')->where('id','11')->first();
        view()->share('package', $this->package);
        view()->share('exhibitionsusers', $exhibitionsusers);
        view()->share('id_code', str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT));
        // Meta Tags
        MetaTag::set('title', t('ownership_title'));
        MetaTag::set('description', strip_tags(getMetaTag('description', 'contact')));
        MetaTag::set('keywords', getMetaTag('keywords', 'contact'));

        return view('pages.ownership');
    }
    public function contactPost_ownership(ContactRequest $request)
    {
        $contactForm = $request->all();
        if(!empty($contactForm['driving_license'])){
            //for driving license
            $extension_driving_license = getUploadedFileExtension($contactForm['driving_license']);
            if (empty($extension_driving_license)) {
                $extension_driving_license = 'jpg';
            }
            // Make the image
            $image_driving_license = Image::make($contactForm['driving_license'])->resize(400, 400, function ($constraint) {
                $constraint->aspectRatio();
            });
            // Generate a filename.
            $filename_driving_license = md5($contactForm['driving_license'] . time()) . '.' . $extension_driving_license;


            $destination_path = 'app/service/'.$request->id_code;
            // Store the image on disk.
            Storage::disk('public')->put($destination_path . '/' . $filename_driving_license, $image_driving_license->stream());
            $image_serv = new ImageService();
            $image_serv->image_code = $filename_driving_license;
            $image_serv->image_title = 'driving_license_image';
            $image_serv->token = $request->id_code;
            // Save
            $image_serv->save();
        }


        if(!empty($contactForm['purchaser_id_image'])) {

            //for purchaser id image
            $extension_purchaser_id_image = getUploadedFileExtension($contactForm['purchaser_id_image']);
            if (empty($extension_purchaser_id_image)) {
                $extension_purchaser_id_image = 'jpg';
            }
            // Make the image
            $image_purchaser_id_image = Image::make($contactForm['purchaser_id_image'])->resize(400, 400, function ($constraint) {
                $constraint->aspectRatio();
            });
            // Generate a filename.
            $filename_purchaser_id_image = md5($contactForm['purchaser_id_image'] . time()) . '.' . $extension_purchaser_id_image;
            $destination_path = 'app/service/'.$request->id_code;
            // Store the image on disk.
            Storage::disk('public')->put($destination_path . '/' . $filename_purchaser_id_image, $image_purchaser_id_image->stream());
            $image_serv = new ImageService();
            $image_serv->image_code = $filename_purchaser_id_image;
            $image_serv->image_title = 'purchaser_id_image';
            $image_serv->token = $request->id_code;
            // Save
            $image_serv->save();
        }
        if(!empty($contactForm['seller_id_image'])) {
            //for seller id image
            $extension_seller_id_image = getUploadedFileExtension($contactForm['seller_id_image']);
            if (empty($extension_seller_id_image)) {
                $extension_seller_id_image = 'jpg';
            }
            // Make the image
            $image_seller_id_image = Image::make($contactForm['seller_id_image'])->resize(400, 400, function ($constraint) {
                $constraint->aspectRatio();
            });
            // Generate a filename.
            $filename_seller_id_image = md5($contactForm['seller_id_image'] . time()) . '.' . $extension_seller_id_image;
            $destination_path = 'app/service/'.$request->id_code;
            // Store the image on disk.
            Storage::disk('public')->put($destination_path . '/' . $filename_seller_id_image, $image_seller_id_image->stream());
            $image_serv = new ImageService();
            $image_serv->image_code = $filename_seller_id_image;
            $image_serv->image_title = 'seller_id_image';
            $image_serv->token = $request->id_code;
            // Save
            $image_serv->save();

        }
        if(!empty($contactForm['car_Pictures'])) {
            //for car Pictures
            $filename_car_Pictures_arr=[];
            $files_car_Pictures= $request->file('car_Pictures');
            foreach ($files_car_Pictures as $key => $file__car_Picture) {

                $extension_car_Pictures = getUploadedFileExtension($file__car_Picture);
                if (empty($extension_car_Pictures)) {
                    $extension_car_Pictures = 'jpg';
                }
                // Make the image
                $image_car_Pictures = Image::make($file__car_Picture)->resize(400, 400, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Generate a filename.
                $filename_car_Pictures = md5($file__car_Picture . time()) . '.' . $extension_car_Pictures;
                array_push($filename_car_Pictures_arr, $filename_car_Pictures);
                $destination_path = 'app/service/'.$request->id_code;
                // Store the image on disk.
                Storage::disk('public')->put($destination_path . '/' . $filename_car_Pictures, $image_car_Pictures->stream());
            }
            $image_serv = new ImageService();
            $image_serv->image_code = implode(',',$filename_car_Pictures_arr);
            $image_serv->image_title = 'car_Pictures';
            $image_serv->token = $request->id_code;

            // Save
            $image_serv->save();
        }
//
//        session()->forget('filename_car_Pictures_arr');
//        session()->forget('filename_seller_id_image');
//        session()->forget('filename_purchaser_id_image');
//        session()->forget('filename_driving_license');
//        Session::put('variableName', $contactForm);
//        session()->pull('variableName.driving_license');
//        session()->pull('variableName.purchaser_id_image');
//        session()->pull('variableName.seller_id_image');
//        session()->pull('variableName.car_Pictures');
//        !empty($contactForm['driving_license'])? session()->push('filename_driving_license', $filename_driving_license):'';
//        !empty($contactForm['purchaser_id_image'])? session()->push('filename_purchaser_id_image', $filename_purchaser_id_image):'';
//        !empty($contactForm['seller_id_image'])? session()->push('filename_seller_id_image', $filename_seller_id_image):'';
//        !empty($contactForm['car_Pictures'])?  session()->push('filename_car_Pictures_arr', $filename_car_Pictures_arr):'';


        Session::put('contactForm', $contactForm);
        session()->pull('contactForm.driving_license');
        session()->pull('contactForm.purchaser_id_image');
        session()->pull('contactForm.seller_id_image');
        session()->pull('contactForm.car_Pictures');
        $request->session()->save();
        return redirect( config('app.locale').'/'.'post/0/paymentservice');

    }
    public function contact_maintenance_yes(\Illuminate\Http\Request $request)
    {
        $this->package = Package::applyCurrency()->with('currency')->orderBy('lft')->where('id','17')->first();
        view()->share('package', $this->package);
        if(!empty($request->id)){
            $post = Post::where ('id',$request->id)->first();
            $city = City::currentCountry()->where('id', $post->city_id)->first();

            if(!empty($city)){
                $maintenance_users= User::where('user_type_id',4)->whereRaw("find_in_set('$city->id',cities_ids)")->get();
            }else{
                $maintenance_users = [];
            }
            $output = '<select name="maintenance_id_yes" id="maintenance_id_yes"
                                                class="form-control">';
                                            foreach ($maintenance_users as $maintenance_user){
                                                $output .=   '<option value="'. $maintenance_user->id .'">'.  $maintenance_user->name .' </option>
                                        
                                        </select>';}
            return json_encode(["data" => $output]);
        }
    }
    public function contact_maintenance(\Illuminate\Http\Request $request)
    {

        if(!empty($request->id)){
            $p = Post::where ('id',$request->id)->get();

//            $maintenance_users_yes= User::where('user_type_id',4)->FIND_IN_SET($p[0]['city_id'],'user_type_id')('user_type_id',$p[0]['city_id'])->get();
//            dd($p[0]['city_id']);
            $post_det = collect($p)->map(function ($post) {
                $post->title = mb_ucfirst($post->title);
                $post->uri = trans('routes.v-post', ['slug' => slugify($post->title), 'id' => $post->id]);

                return $post;
            })->toArray();


            // Get Pack Info
            $package = null;
            $cacheExpiration = (int)config('settings.other.cache_expiration');


            // Get PostType Info
            $cacheId = 'postType.' .$post_det[0]['post_type_id'] . '.' . config('app.locale');
            $postType = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post_det) {
                $postType = \App\Models\PostType::findTrans($post_det[0]['post_type_id']);
                return $postType;
            });


            // Get Post's Pictures
            $pictures = \App\Models\Picture::where('post_id', $post_det[0]['id'])->orderBy('position')->orderBy('id');
            if ($pictures->count() > 0) {
                $postImg = resize($pictures->first()->filename, 'medium');
            } else {
                $postImg = resize(config('larapen.core.picture.default'));
            }

            // Get the Post's City
            $cacheId = config('country.code') . '.city.' . $post_det[0]['city_id'];
            $city = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post_det) {
                $city = \App\Models\City::find( $post_det[0]['city_id']);
                return $city;
            });


            // Convert the created_at date to Carbon object
//            $post_det->created_at = \Date::parse($post_det[0]['created_at'])->timezone(config('timezone.id'));

//            $post_det->created_at =$post_det[0]['created_at']->ago();

            // Category
            $cacheId = 'category.' . $post_det[0]['category_id'] . '.' . config('app.locale');
            $liveCat = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post_det) {
                $liveCat = \App\Models\Category::findTrans($post_det[0]['category_id']);
                return $liveCat;

            });

            // Check parent
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

            // Check translation
            $liveCatName = $liveCat->name;
            $attr = ['slug' => slugify($post_det[0]['title']), 'id' => $post_det[0]['id']];
            $url =  lurl($post_det[0]['uri'], $attr) ;
            $colDescBox = 'col-sm-7';
            $colPriceBox = 'col-sm-3';
            $u1=qsurl(config('app.locale').'/'.trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge($request->except(['l', 'location']), ['l'=>$post_det[0]['city_id']]));
            $u2=qsurl(config('app.locale').'/'.trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge($request->except('c'), ['c'=>$liveCatParentId])) ;

            $output = ' <div class="item-list">';
            if (isset($package) and !empty($package)){
                if ($package->ribbon != ''){
                    $output .= '<div class="cornerRibbons {{ $package->ribbon }}"><a
                                            href="#">'. $package->short_name.'</a></div>';
                }
            }

            $output .='     <div class="row">
                            <div class="col-sm-2 no-padding photobox">
                                <div class="add-image">
                                                <span class="photo-count"><i
                                                            class="fa fa-camera"></i>'. $pictures->count()  .'</span>
                                   
                                    <a href="'.$url.'">
                                        <img class="img-thumbnail no-margin" src="'.$postImg.'" alt="img">
                                    </a>
                                </div>
                            </div>

                            <div class="'.$colDescBox .' add-desc-box">
                                <div class="ads-details">
                                    <h5 class="add-title">
                                        <a href="'.$url.'">'. str_limit($post_det[0]['title'], 70) .' </a>
                                    </h5>

                                    <span class="info-row">';
            if (isset($postType->name) && !empty($postType->name)) {
                $output .= ' 	<span class="add-type business-ads tooltipHere" data-toggle="tooltip"
                                              data-placement="right" title="' . $postType->name . '">
											' . strtoupper(mb_substr($postType->name, 0, 1)) . '
										</span>&nbsp;';
            }
                $output .='		<span class="date"><i class="icon-clock"></i>  '.$post_det[0]['created_at'] .' </span>';
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
            (isset($post_det[0]['distance'])) ? "- " . round(lengthPrecision($post_det[0]['distance']), 2) . unitOfLength() : "";
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
                if ($post_det[0]['price'] > 0){
                    $output .= \App\Helpers\Number::money($post_det[0]['price']);
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
                $output .=' <a class="btn btn- (\App\Models\SavedPost::where(\'user_id\', auth()->user()->id)->where(\'post_id\',$post_det[0][\'id\'] )->count() > 0) ? \'success\' : \'default\'  btn-sm make-favorite"
                                       id="{{ $post_det[0][\'id\']}}">
                                        <i class="fa fa-heart"></i><span> .'.' </span>
                                    </a>';
            }else{

                $output .='<a class="btn btn-default btn-sm make-favorite" id=" '.$post_det[0]['id'] .'"><i
                                                class="fa fa-heart"></i><span>'.  t("Save").'</span></a>';
            }
            $output .='   </div>

                                </div>
                            </div>';
            $attr = ['slug' => slugify($post_det[0]['title']), 'id' => $post_det[0]['id']];

            return json_encode(["data" => $output, "uri" => $post_det[0]['uri'], "attr"=> $attr]);


        }

        $subladmin1s = City::currentCountry()->orderBy('name')->get();


        if($request->ajax() && !empty($request->lat)){
            $nearest  = DB::select("
                        select 
                           111.111 *
                            DEGREES(ACOS(LEAST(COS(RADIANS(theqqacities.latitude))
                                 * COS(RADIANS($request->lat))
                                 * COS(RADIANS(theqqacities.longitude - $request->lng))
                                 + SIN(RADIANS(theqqacities.latitude))
                                 * SIN(RADIANS($request->lat)), 1.0))) AS distance_in_km,id
                        
                        from theqqacities
                        order by distance_in_km
                        limit 1
                ");

            if(empty($nearest)){
                return response()->json(['status'=>'success', "city"=>[], "maintenance_users" => []]);
            }
//            die(var_dump($nearest[0]->id));
            $city = City::currentCountry()->where('id', $nearest[0]->id)->first();
            if(!empty($city)){
                $maintenance_users= User::where('user_type_id',4)->whereRaw("find_in_set('$city->id',cities_ids)")->get();
            }else{
                $maintenance_users = [];
            }


            return response()->json(['status'=>'success', "city"=>$city, "maintenance_users" => $maintenance_users]);
        }

        if($request->ajax() && !empty($request->aId)){
            $city = City::currentCountry()->where('id', $request->aId)->first();
            if(!empty($city)){
                $maintenance_users= User::where('user_type_id',4)->whereRaw("find_in_set('$city->id',cities_ids)")->get();
            }else{
                $maintenance_users = [];
            }


            return response()->json(['status'=>'success', "city"=>$city, "maintenance_users" => $maintenance_users]);

        }

        $city = City::currentCountry()->orderBy('population', 'desc')->first();
        view()->share('subladmin1s', $subladmin1s);
        view()->share('city', $city);
        $this->package = Package::applyCurrency()->with('currency')->orderBy('lft')->where('id','17')->first();
        view()->share('package', $this->package);
        $maintenance_users= User::where('user_type_id',4)->get();
        view()->share('id_code', str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT));
        view()->share('maintenance_users_yes', $maintenance_users);
        view()->share('maintenance_users', $maintenance_users);
        // Meta Tags
        MetaTag::set('title', t('maintenance_title'));
        MetaTag::set('description', strip_tags(getMetaTag('description', 'contact')));
        MetaTag::set('keywords', getMetaTag('keywords', 'contact'));

        return view('pages.maintenance');
    }
    public function get_cities($id) {

        $cities = City::currentCountry()->where("subadmin1_code",$id)->pluck("name","id");
        return json_encode($cities);

//        $states = DB::table("state")->where("country_id",$id)->pluck("name","id");
//
//        return json_encode($states);

    }
    public function contactPost_maintenance(ContactRequest $request)
    {

        $contactForm = $request->all();
        Session::put('contactForm', $contactForm);
        return redirect( config('app.locale').'/'.'post/0/paymentservice');
    }

    public function contact_checking(\Illuminate\Http\Request $request)
    {


        if(!empty($request->id)){
            $p = Post::where ('id',$request->id)->get();
            $post_det = collect($p)->map(function ($post) {
                $post->title = mb_ucfirst($post->title);
                $post->uri = trans('routes.v-post', ['slug' => slugify($post->title), 'id' => $post->id]);

                return $post;
            })->toArray();


            // Get Pack Info
            $package = null;
            $cacheExpiration = (int)config('settings.other.cache_expiration');


            // Get PostType Info
            $cacheId = 'postType.' .$post_det[0]['post_type_id'] . '.' . config('app.locale');
            $postType = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post_det) {
                $postType = \App\Models\PostType::findTrans($post_det[0]['post_type_id']);
                return $postType;
            });


            // Get Post's Pictures
            $pictures = \App\Models\Picture::where('post_id', $post_det[0]['id'])->orderBy('position')->orderBy('id');
            if ($pictures->count() > 0) {
                $postImg = resize($pictures->first()->filename, 'medium');
            } else {
                $postImg = resize(config('larapen.core.picture.default'));
            }

            // Get the Post's City
            $cacheId = config('country.code') . '.city.' . $post_det[0]['city_id'];
            $city = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post_det) {
                $city = \App\Models\City::find( $post_det[0]['city_id']);
                return $city;
            });


            // Convert the created_at date to Carbon object
//            $post_det->created_at = \Date::parse($post_det[0]['created_at'])->timezone(config('timezone.id'));

//            $post_det->created_at =$post_det[0]['created_at']->ago();

            // Category
            $cacheId = 'category.' . $post_det[0]['category_id'] . '.' . config('app.locale');
            $liveCat = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post_det) {
                $liveCat = \App\Models\Category::findTrans($post_det[0]['category_id']);
                return $liveCat;

            });

            // Check parent
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

            // Check translation
            $liveCatName = $liveCat->name;
            $attr = ['slug' => slugify($post_det[0]['title']), 'id' => $post_det[0]['id']];
            $url =  lurl($post_det[0]['uri'], $attr) ;
            $colDescBox = 'col-sm-7';
            $colPriceBox = 'col-sm-3';
            $u1=qsurl(config('app.locale').'/'.trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge($request->except(['l', 'location']), ['l'=>$post_det[0]['city_id']]));
            $u2=qsurl(config('app.locale').'/'.trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge($request->except('c'), ['c'=>$liveCatParentId])) ;

            $output = ' <div class="item-list">';
            if (isset($package) and !empty($package)){
                if ($package->ribbon != ''){
                    $output .= '<div class="cornerRibbons {{ $package->ribbon }}"><a
                                            href="#"> '.$package->short_name.'</a></div>';
                }
            }

            $output .='     <div class="row">
                            <div class="col-sm-2 no-padding photobox">
                                <div class="add-image">
                                                <span class="photo-count"><i
                                                            class="fa fa-camera"></i>'. $pictures->count()  .'</span>
                                   
                                    <a href="'.$url.'">
                                        <img class="img-thumbnail no-margin" src="'.$postImg.'" alt="img">
                                    </a>
                                </div>
                            </div>

                            <div class="'.$colDescBox .' add-desc-box">
                                <div class="ads-details">
                                    <h5 class="add-title">
                                        <a href="'.$url.'">'. str_limit($post_det[0]['title'], 70) .' </a>
                                    </h5>

                                    <span class="info-row">
										<span class="add-type business-ads tooltipHere" data-toggle="tooltip"
                                              data-placement="right" title="'.$postType->name .'">
											'. strtoupper(mb_substr($postType->name, 0, 1)) .'
										</span>&nbsp;
										<span class="date"><i class="icon-clock"></i>  '.$post_det[0]['created_at'] .' </span>';
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
            (isset($post_det[0]['distance'])) ? "- " . round(lengthPrecision($post_det[0]['distance']), 2) . unitOfLength() : "";
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
                if ($post_det[0]['price'] > 0){
                    $output .= \App\Helpers\Number::money($post_det[0]['price']);
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
                $output .=' <a class="btn btn- (\App\Models\SavedPost::where(\'user_id\', auth()->user()->id)->where(\'post_id\',$post_det[0][\'id\'] )->count() > 0) ? \'success\' : \'default\'  btn-sm make-favorite"
                                       id="{{ $post_det[0][\'id\']}}">
                                        <i class="fa fa-heart"></i><span> .'.' </span>
                                    </a>';
            }else{

                $output .='<a class="btn btn-default btn-sm make-favorite" id=" '.$post_det[0]['id'] .'"><i
                                                class="fa fa-heart"></i><span>'.  t("Save").'</span></a>';
            }
            $output .='   </div>

                                </div>
                            </div>';
            $attr = ['slug' => slugify($post_det[0]['title']), 'id' => $post_det[0]['id']];
            return json_encode(["data" => $output, "uri" => $post_det[0]['uri'], "attr"=> $attr]);


        }

        $subladmin1s = City::currentCountry()->orderBy('name')->get();


        if($request->ajax() && !empty($request->lat)){
            $nearest  = DB::select("
                        select 
                           111.111 *
                            DEGREES(ACOS(LEAST(COS(RADIANS(theqqacities.latitude))
                                 * COS(RADIANS($request->lat))
                                 * COS(RADIANS(theqqacities.longitude - $request->lng))
                                 + SIN(RADIANS(theqqacities.latitude))
                                 * SIN(RADIANS($request->lat)), 1.0))) AS distance_in_km,id
                        
                        from theqqacities
                        order by distance_in_km
                        limit 1
                ");

            if(empty($nearest)){
                return response()->json(['status'=>'success', "city"=>[], "maintenance_users" => []]);
            }
//            die(var_dump($nearest[0]->id));
            $city = City::currentCountry()->where('id', $nearest[0]->id)->first();
            if(!empty($city)){
                $maintenance_users= User::where('user_type_id',4)->whereRaw("find_in_set('$city->id',cities_ids)")->get();
            }else{
                $maintenance_users = [];
            }


            return response()->json(['status'=>'success', "city"=>$city, "maintenance_users" => $maintenance_users]);
        }

        if($request->ajax() && !empty($request->aId)){
            $city = City::currentCountry()->where('id', $request->aId)->first();
            if(!empty($city)){
                $maintenance_users= User::where('user_type_id',4)->whereRaw("find_in_set('$city->id',cities_ids)")->get();
            }else{
                $maintenance_users = [];
            }


            return response()->json(['status'=>'success', "city"=>$city, "maintenance_users" => $maintenance_users]);

        }

        $city = City::currentCountry()->orderBy('population', 'desc')->first();
        view()->share('subladmin1s', $subladmin1s);
        view()->share('city', $city);

        $maintenance_users= User::where('user_type_id',4)->get();

        view()->share('maintenance_users', $maintenance_users);
        $package = Package::applyCurrency()->with('currency')->orderBy('lft')->where('id','13')->first();
        view()->share('package', $package);
        // Get the Country's largest city for Google Maps
        $city = City::currentCountry()->orderBy('population', 'desc')->first();
        view()->share('city', $city);
        view()->share('id_code', str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT));
        // Meta Tags
        MetaTag::set('title', t('checking_title'));
        MetaTag::set('description', strip_tags(getMetaTag('description', 'contact')));
        MetaTag::set('keywords', getMetaTag('keywords', 'contact'));

        return view('pages.checking');
    }
    public function contactPost_checking(ContactRequest $request)
    {
        $contactForm = $request->all();
        Session::put('contactForm', $contactForm);
        $request->session()->save();
        return redirect( config('app.locale').'/'.'post/0/paymentservice');
    }
    public function contact_shipping(\Illuminate\Http\Request $request)
    {
        if(!empty($request->id)
        )
        {
            $p = Post::where ('id',$request->id)->get();
            $post_det = collect($p)->map(function ($post) {
                $post->title = mb_ucfirst($post->title);
                $post->uri = trans('routes.v-post', ['slug' => slugify($post->title), 'id' => $post->id]);

                return $post;
            })->toArray();


            // Get Pack Info
            $package = null;
            $cacheExpiration = (int)config('settings.other.cache_expiration');


            // Get PostType Info
            $cacheId = 'postType.' .$post_det[0]['post_type_id'] . '.' . config('app.locale');
            $postType = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post_det) {
                $postType = \App\Models\PostType::findTrans($post_det[0]['post_type_id']);
                return $postType;
            });


            // Get Post's Pictures
            $pictures = \App\Models\Picture::where('post_id', $post_det[0]['id'])->orderBy('position')->orderBy('id');
            if ($pictures->count() > 0) {
                $postImg = resize($pictures->first()->filename, 'medium');
            } else {
                $postImg = resize(config('larapen.core.picture.default'));
            }

            // Get the Post's City
            $cacheId = config('country.code') . '.city.' . $post_det[0]['city_id'];
            $city = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post_det) {
                $city = \App\Models\City::find( $post_det[0]['city_id']);
                return $city;
            });


            // Convert the created_at date to Carbon object
//            $post_det->created_at = \Date::parse($post_det[0]['created_at'])->timezone(config('timezone.id'));

//            $post_det->created_at =$post_det[0]['created_at']->ago();

            // Category
            $cacheId = 'category.' . $post_det[0]['category_id'] . '.' . config('app.locale');
            $liveCat = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post_det) {
                $liveCat = \App\Models\Category::findTrans($post_det[0]['category_id']);
                return $liveCat;

            });

            // Check parent
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

            // Check translation
            $liveCatName = $liveCat->name;
            $attr = ['slug' => slugify($post_det[0]['title']), 'id' => $post_det[0]['id']];
            $url =  lurl($post_det[0]['uri'], $attr) ;
            $colDescBox = 'col-sm-7';
            $colPriceBox = 'col-sm-3';
            $u1=qsurl(config('app.locale').'/'.trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge($request->except(['l', 'location']), ['l'=>$post_det[0]['city_id']]));
            $u2=qsurl(config('app.locale').'/'.trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge($request->except('c'), ['c'=>$liveCatParentId])) ;

            $output = ' <div class="item-list">';
            if (isset($package) and !empty($package)){
                if ($package->ribbon != ''){
                    $output .= '<div class="cornerRibbons {{ $package->ribbon }}"><a
                                            href="#">'. $package->short_name.'</a></div>';
                }
            }

            $output .='     <div class="row">
                            <div class="col-sm-2 no-padding photobox">
                                <div class="add-image">
                                                <span class="photo-count"><i
                                                            class="fa fa-camera"></i>'. $pictures->count()  .'</span>
                                   
                                    <a href="'.$url.'">
                                        <img class="img-thumbnail no-margin" src="'.$postImg.'" alt="img">
                                    </a>
                                </div>
                            </div>

                            <div class="'.$colDescBox .' add-desc-box">
                                <div class="ads-details">
                                    <h5 class="add-title">
                                        <a href="'.$url.'">'. str_limit($post_det[0]['title'], 70) .' </a>
                                    </h5>

                                    <span class="info-row">
										<span class="add-type business-ads tooltipHere" data-toggle="tooltip"
                                              data-placement="right" title="'.$postType->name .'">
											'. strtoupper(mb_substr($postType->name, 0, 1)) .'
										</span>&nbsp;
										<span class="date"><i class="icon-clock"></i>  '.$post_det[0]['created_at'] .' </span>';
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
            (isset($post_det[0]['distance'])) ? "- " . round(lengthPrecision($post_det[0]['distance']), 2) . unitOfLength() : "";
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
                if ($post_det[0]['price'] > 0){
                    $output .= \App\Helpers\Number::money($post_det[0]['price']);
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
                $output .=' <a class="btn btn- (\App\Models\SavedPost::where(\'user_id\', auth()->user()->id)->where(\'post_id\',$post_det[0][\'id\'] )->count() > 0) ? \'success\' : \'default\'  btn-sm make-favorite"
                                       id="{{ $post_det[0][\'id\']}}">
                                        <i class="fa fa-heart"></i><span> .'.' </span>
                                    </a>';
            }else{

                $output .='<a class="btn btn-default btn-sm make-favorite" id=" '.$post_det[0]['id'] .'"><i
                                                class="fa fa-heart"></i><span>'.  t("Save").'</span></a>';
            }
            $output .='   </div>

                                </div>
                            </div>';
            $attr = ['slug' => slugify($post_det[0]['title']), 'id' => $post_det[0]['id']];
            return json_encode(["data" => $output, "uri" => $post_det[0]['uri'], "attr"=> $attr]);


        }
        // Get the Country's largest city for Google Maps



        $subladmin1s = City::currentCountry()->orderBy('name')->get();


        if($request->ajax() && !empty($request->lat)){
            $nearest  = DB::select("
                        select 
                           111.111 *
                            DEGREES(ACOS(LEAST(COS(RADIANS(theqqacities.latitude))
                                 * COS(RADIANS($request->lat))
                                 * COS(RADIANS(theqqacities.longitude - $request->lng))
                                 + SIN(RADIANS(theqqacities.latitude))
                                 * SIN(RADIANS($request->lat)), 1.0))) AS distance_in_km,id
                        
                        from theqqacities
                        order by distance_in_km
                        limit 1
                ");

            if(empty($nearest)){
                return response()->json(['status'=>'success', "city"=>[], "shipping_users" => []]);
            }
//            die(var_dump($nearest[0]->id));
            $city = City::currentCountry()->where('id', $nearest[0]->id)->first();
            if(!empty($city)){
                $shipping_users= User::where('user_type_id',5)->whereRaw("find_in_set('$city->id',cities_ids)")->get();

            }else{
                $shipping_users = [];
            }


            return response()->json(['status'=>'success', "city"=>$city, "shipping_users" => $shipping_users]);
        }

        if($request->ajax() && !empty($request->aId)){
            $city = City::currentCountry()->where('id', $request->aId)->first();
            if(!empty($city)){
                $shipping_users= User::where('user_type_id',5)->whereRaw("find_in_set('$city->id',cities_ids)")->get();

            }else{
                $shipping_users = [];
            }


            return response()->json(['status'=>'success', "city"=>$city, "shipping_users" => $shipping_users]);

        }

        $city = City::currentCountry()->orderBy('population', 'desc')->first();
        view()->share('subladmin1s', $subladmin1s);
        view()->share('city', $city);

        $shipping_users= User::where('user_type_id',5)->get();

        view()->share('shipping_users', $shipping_users);
        view()->share('id_code', str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT));
//        $city = City::currentCountry()->orderBy('population', 'desc')->first();
//        $shipping_users= User::where('user_type_id',5)->get();
//        view()->share('city', $city);
//        view()->share('shipping_users', $shipping_users);
        // Meta Tags
        $this->package = Package::applyCurrency()->with('currency')->orderBy('lft')->where('id','15')->first();
        view()->share('package', $this->package);
        MetaTag::set('title', t('shipping_title'));
        MetaTag::set('description', strip_tags(getMetaTag('description', 'contact')));
        MetaTag::set('keywords', getMetaTag('keywords', 'contact'));

        return view('pages.shipping');
    }
    public function contactPost_shipping(ContactRequest $request)
    {

        $contactForm = $request->all();


        if(!empty($contactForm['car_Pictures'])) {
            //for car Pictures
            $filename_car_Pictures_arr=[];
            $files_car_Pictures= $request->file('car_Pictures');
            foreach ($files_car_Pictures as $key => $file__car_Picture) {

                $extension_car_Pictures = getUploadedFileExtension($file__car_Picture);
                if (empty($extension_car_Pictures)) {
                    $extension_car_Pictures = 'jpg';
                }
                // Make the image
                $image_car_Pictures = Image::make($file__car_Picture)->resize(400, 400, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Generate a filename.
                $filename_car_Pictures = md5($file__car_Picture . time()) . '.' . $extension_car_Pictures;
                array_push($filename_car_Pictures_arr, $filename_car_Pictures);
                $destination_path = 'app/service/'.$request->id_code;
                // Store the image on disk.
                Storage::disk('public')->put($destination_path . '/' . $filename_car_Pictures, $image_car_Pictures->stream());
            }
            $image_serv = new ImageService();
            $image_serv->image_code = implode(',',$filename_car_Pictures_arr);
            $image_serv->image_title = 'car_Pictures';
            $image_serv->token = $request->id_code;

            // Save
            $image_serv->save();
        }

        Session::put('contactForm', $contactForm);
        session()->pull('contactForm.car_Pictures');
        $request->session()->save();
        return redirect( config('app.locale').'/'.'post/0/paymentservice');

    }


}
