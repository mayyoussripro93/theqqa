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
use App\Http\Requests\ApiContactRequest;
use App\Models\City;
use App\Models\ImageService;
use App\Models\Page;
use App\Models\PaymentMethod;
use App\Models\Permission;
use App\Models\Post;
use App\Models\User;
use App\Models\ServicePaytabs;

use App\Models\UserType;
use App\Notifications\BackPaymentNotification;
use App\Notifications\BackPaymentSent;
use App\Notifications\FormSent;
use App\Notifications\PaymentNotification;
use App\Notifications\PaymentSent;
use App\Notifications\User_Mail;
use Aws\Waf\WafClient;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Larapen\LaravelLocalization\Facades\LaravelLocalization;
use Prologue\Alerts\Facades\Alert;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Models\Message;
use App\Notifications\SellerContacted;
use App\Http\Requests\PackageRequest;
use Illuminate\Support\Facades\Cookie;
use Intervention\Image\Facades\Image;
use App\Models\Payment as PaymentModel;
use App\Models\Package;
use Illuminate\Http\Request;
use App\Http\Controllers\Post\Traits\PaymentTrait;
use App\Helpers\Localization\Country as CountryLocalization;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Helpers\Localization\Language as LanguageLocalization;
class PageController extends FrontController
{
    use PaymentTrait;
    /**
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($slug)
    {
        $country =  \App\Models\Country::where('active', 1)->where('id', 194)->first();

       $countryLang = CountryLocalization::getLangFromCountry(config('app.locale'));

        // Translate the Country name (If translation exists)
//        if (!empty($country) && !empty($countryLang)) {
//
//            $country = CountryLocalizationHelper::trans($country, $countryLang->get('abbr'));
//            die(dd($country));
//        }

        // Session: Set Country Code
        // Config: Country
        if (!empty($country) && $country->code) {

            session(['country_code' => $country->cod]);
            $countryLangExists = $country->lang && $countryLang->has('abbr');
            Config::set('country.locale', ($countryLangExists) ? $countryLang->get('abbr') : config('app.locale'));
            Config::set('country.lang', ($country->lang) ? $country->lang->toArray() : []);
            Config::set('country.code', $country->code);
            Config::set('country.icode', $country->icode);
            Config::set('country.name', $country->name);
            Config::set('country.currency', $country->currency_code);
            Config::set('country.admin_type', $country->admin_type);
            Config::set('country.admin_field_active', $country->admin_field_active);
            Config::set('country.background_image', $country->background_image);
        }

        // Config: IP Country
//        if (!$ipCountry->isEmpty() && $ipCountry->has('code')) {
//            Config::set('ipCountry.code', $ipCountry->get('code'));
//        }
        // Config: Currency
        if (!empty($country)  && $country->currency && !empty($country->currency)) {
            Config::set('currency', $country->currency->toArray());
        }

        // Config: Set TimeZome
        if (!empty($country)  && $country->timezone && !empty($country->timezone)) {
            Config::set('timezone.id', $country->timezone->time_zone_id);
        }
        // Config: Language
        if (!$countryLang->isEmpty()) {
            session(['language_code' => $countryLang->get('abbr')]);
            Config::set('lang.abbr', $countryLang->get('abbr'));
            Config::set('lang.locale', $countryLang->get('locale'));
            Config::set('lang.direction', $countryLang->get('direction'));
            Config::set('lang.russian_pluralization', $countryLang->get('russian_pluralization'));
        }
        // Config: Currency Exchange Plugin
        if (config('plugins.currencyexchange.installed')) {
            Config::set('country.currencies', ($country->currencies) ? $country->currencies : '');
        } else {
            Config::set('selectedCurrency', config('currency'));
        }
        // Config: Domain Mapping Plugin
        if (config('plugins.domainmapping.installed')) {
            applyDomainMappingConfig(config('country.code'));
        }
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
    public function indexApi($slug)
    {
        $country =  \App\Models\Country::where('active', 1)->where('id', 194)->first();

        $countryLang = CountryLocalization::getLangFromCountry(config('app.locale'));

        if (!empty($country) && $country->code) {

            session(['country_code' => $country->cod]);
            $countryLangExists = $country->lang && $countryLang->has('abbr');
            Config::set('country.locale', ($countryLangExists) ? $countryLang->get('abbr') : config('app.locale'));
            Config::set('country.lang', ($country->lang) ? $country->lang->toArray() : []);
            Config::set('country.code', $country->code);
            Config::set('country.icode', $country->icode);
            Config::set('country.name', $country->name);
            Config::set('country.currency', $country->currency_code);
            Config::set('country.admin_type', $country->admin_type);
            Config::set('country.admin_field_active', $country->admin_field_active);
            Config::set('country.background_image', $country->background_image);
        }

        if (!empty($country)  && $country->currency && !empty($country->currency)) {
            Config::set('currency', $country->currency->toArray());
        }

        // Config: Set TimeZome
        if (!empty($country)  && $country->timezone && !empty($country->timezone)) {
            Config::set('timezone.id', $country->timezone->time_zone_id);
        }
        // Config: Language
        if (!$countryLang->isEmpty()) {
            session(['language_code' => $countryLang->get('abbr')]);
            Config::set('lang.abbr', $countryLang->get('abbr'));
            Config::set('lang.locale', $countryLang->get('locale'));
            Config::set('lang.direction', $countryLang->get('direction'));
            Config::set('lang.russian_pluralization', $countryLang->get('russian_pluralization'));
        }
        // Config: Currency Exchange Plugin
        if (config('plugins.currencyexchange.installed')) {
            Config::set('country.currencies', ($country->currencies) ? $country->currencies : '');
        } else {
            Config::set('selectedCurrency', config('currency'));
        }
        // Config: Domain Mapping Plugin
        if (config('plugins.domainmapping.installed')) {
            applyDomainMappingConfig(config('country.code'));
        }
        // Get the Page
        $page = Page::where('slug', $slug)->trans()->first();

        if (empty($page)) {
            abort(404);
        }

//        view()->share('page', $page);
//        view()->share('uriPathPageSlug', $slug);

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
       return response()->json([
        'status' => 'success',
        'page' => $page,
        'uriPathPageSlug' => $title,
        'description' => $description,
    ]);
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
    public function ReqDocuments()
    {
        $reqdocuments_0 = t('Required Documents');
        $reqdocuments_1 = t('Now you can publish your ad for free or special on Theqqa .There are no documents required in the case of free advertising');
		$reqdocuments_2 = t('Featured Ad (Paid)');
        $reqdocuments_3 = html_entity_decode(t('For_service_alert_2'), ENT_COMPAT);
        $reqdocuments_4 = html_entity_decode(t('For_service_alert'), ENT_COMPAT);
        $reqdocuments_5 = t('Add an ad on a Theqqa site');
        $reqdocuments_6 = t('To Request This Service');
        $reqdocuments_7 = t("Once you've made a bank transfer, please attach a bank transfer image in the appropriate field when creating a new unique ad.");
        $reqdocuments_8 = t('You can now show your ad on the list of featured ads on Theqqa by wire transfer on the following account:');
        $reqdocuments_9 = t('Required Documents In Case Of Special Advertisements');
        $reqdocuments_10 = t("let's start");
        return response()->json([
            'status' => 'success',
            'reqdocuments_0'=>$reqdocuments_0,
            'reqdocuments_1'=>$reqdocuments_1,
            'reqdocuments_2'=>$reqdocuments_2,
            'reqdocuments_3'=>$reqdocuments_3,
            'reqdocuments_4'=>$reqdocuments_4,
            'reqdocuments_5'=>$reqdocuments_5,
            'reqdocuments_6'=>$reqdocuments_6,
            'reqdocuments_7'=>$reqdocuments_7,
            'reqdocuments_8'=>$reqdocuments_8,
            'reqdocuments_9'=>$reqdocuments_9,
            'reqdocuments_10'=>$reqdocuments_10,

        ]);

    }
    /**
     * @param ContactRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
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
    public function contactPost_estimation(ApiContactRequest $request)
    {

//        session_start();
        // Store Contact Info
        $contactForm = $request->all();
        session()->put('contactForm', $contactForm);
        session()->pull('contactForm.car_Pictures');

        if(!empty($contactForm['car_Pictures'])) {
            //for car Pictures
            $filename_car_Pictures_arr=[];
            $files_car_Pictures= explode(',', $request->car_Pictures);
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
        return redirect('post/0/paymentservice');
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
                                            href="#"> $package->short_name</a></div>';
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

        view()->share('cities', $cities);
        view()->share('id_code', str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT));

        // Meta Tags
        MetaTag::set('title', t('estimation title'));
        MetaTag::set('description', strip_tags(getMetaTag('description', 'contact')));
        MetaTag::set('keywords', getMetaTag('keywords', 'contact'));

        return view('pages.estimation');
    }
    public function contact_mogaz()
    {
        // Get the Country's largest city for Google Maps
        $cities = City::where('country_code','SA')->orderBy('name')->get();
        $reqdocuments = '<p>
									<div style="font-size: 16px"><i class="icon-docs"></i>'.
            t('Now you can publish your ad for free or special on Theqqa .There are no documents required in the case of free advertising').'
									</div>
								</p>
								<h3>
									<strong><i class="fa fa-plus-circle"></i>'.  t('Featured Ad (Paid)').'</strong>
								</h3>

								<p>'. html_entity_decode(t('For_service_alert_2'), ENT_COMPAT).'</p>';
        foreach ($cities as $data1){

            unset($data1->lon);
            unset($data1->latitude,$data1->longitude,$data1->feature_class,$data1->feature_code,$data1->subadmin1_code,$data1->subadmin2_code,$data1->population,$data1->time_zone
                );

        }
        // Meta Tags
        MetaTag::set('title', getMetaTag('title', 'contact'));
        MetaTag::set('description', strip_tags(getMetaTag('description', 'contact')));
        MetaTag::set('keywords', getMetaTag('keywords', 'contact'));
        return response()->json([
            'status' => 'success',
            'city' => $cities,
            'reqdocuments'=>$reqdocuments,
        ]);
//        return view('pages.mogaz');
    }
    public function contactPost_mogaz(ApiContactRequest $request)
    {

        $contactForm = $request ->except('bank_transfer_in');


        if ($request->payment_method_id == 2 && $request->package_id != 5 ){

            if(!empty($request->bank_transfer_in)) {
                //for booking bank image
                $extension_booking_bank_image = getUploadedFileExtension($request->bank_transfer_in);

                if (empty($extension_booking_bank_image)) {
                    $extension_booking_bank_image = 'jpg';
                }
                // Make the image
                $booking_bank_image = Image::make($request->bank_transfer_in)->resize(400, 400, function ($constraint) {
                    $constraint->aspectRatio();
                });

                // Generate a filename.
                $filename_booking_bank_image = md5($request->bank_transfer_in . time()) . '.' . $extension_booking_bank_image;
                $destination_path = 'app/booking';
                // Store the image on disk.
                Storage::disk('public')->put($destination_path . '/' . $filename_booking_bank_image, $booking_bank_image->stream());
            }


            $dataservice = json_encode($contactForm);
//            $output_file="test.jpg";
//            $ifp = fopen( $output_file, 'wb' );
//
//
//            // we could add validation here with ensuring count( $data ) > 1
//            fwrite( $ifp, base64_decode( $request->bank_transfer_in ) );
//
//            // clean up the file resource
//            fclose( $ifp );
//            //for booking bank image
//            $extension_booking_bank_image = getUploadedFileExtension($output_file);
//
//            if (empty($extension_booking_bank_image)) {
//                $extension_booking_bank_image = 'jpg';
//            }
//
//            // Make the image
//            $booking_bank_image = Image::make($output_file)->resize(400, 400, function ($constraint) {
//                $constraint->aspectRatio();
//            });
//
//            // Generate a filename.
//            $filename_booking_bank_image = md5($output_file. time()) . '.' . $extension_booking_bank_image;
//            $destination_path = 'app/booking';
//            // Store the image on disk.
//            Storage::disk('public')->put($destination_path . '/' . $filename_booking_bank_image, $booking_bank_image->stream());
            $package = Package::find($request->input('package_id'));

            $paymentInfo = [

                'post_id'           => 0,
                'user_id'           =>  auth()->user()->id ,
                'price'             =>  $package->price,
                'package_id'        =>  $request->package_id,
                'payment_method_id' => $request->payment_method_id,
                'transaction_id'    => t('bank_transfer'),
                'active'            => 0,
                'date_service'      => $dataservice,
                'image'     => $filename_booking_bank_image ,
            ];
            // Save the payment
            $payment =!empty($paymentmodel)?$paymentmodel: new PaymentModel($paymentInfo);
            $payment->save();


            return response()->json([
                'status' => 'success',
                'massage' => t("You will be assured of bank transfer and confirmation of your booking by your email and by massages on Theqqa"),
            ]);

        }
        else {
                  $dataservice = json_encode($contactForm);
                if ($request->paytabs == 1){
     $package = \App\Models\Package::find( $request->package_id);
        require_once 'paytabs.php';


                    $pt = new \App\Http\Controllers\API\paytabs(env('PAYTABS_EMAIL'), env('PAYTABS_SECRET_KEY'));
         $result =    $pt->create_pay_page(array(
                //Customer's Personal Information
             'merchant_email' =>   env('PAYTABS_EMAIL'),
             'secret_key' => env('PAYTABS_SECRET_KEY'),
                'cc_first_name' => $request->card_first_name,          //This will be prefilled as Credit Card First Name
                'cc_last_name' =>  $request->card_last_name,            //This will be prefilled as Credit Card Last Name
                'cc_phone_number' => "966",
                'phone_number' => $request->card_phone,
                'email' => $request->card_email,

              //Customer's Billing Address (All fields are mandatory)
                //When the country is selected as USA or CANADA, the state field should contain a String of 2 characters containing the ISO state code otherwise the payments may be rejected. 
                //For other countries, the state can be a string of up to 32 characters.
                'billing_address' => "4410 طريق الدمام - حي المؤنسية",
                'city' => "Riyadh",
                'state' => "Riyadh",
                'postal_code' => "13253",
                'country' => "SAU",
                
                //Customer's Shipping Address (All fields are mandatory)
                'address_shipping' => "4410 طريق الدمام - حي المؤنسية", 
                'city_shipping' => "Riyadh",
                'state_shipping' => "Riyadh",
                'postal_code_shipping' => "13253",
                'country_shipping' => "SAU",
               
              //Product Information
                "products_per_title" => $package->name,   //Product title of the product. If multiple products then add “||” separator
                'quantity' => "1",                                    //Quantity of products. If multiple products then add “||” separator
                'unit_price' => $package->price,                                  //Unit price of the product. If multiple products then add “||” separator.
                "other_charges" => "00.00",                                     //Additional charges. e.g.: shipping charges, taxes, VAT, etc.
                
                'amount' => $package->price,                                          //Amount of the products and other charges, it should be equal to: amount = (sum of all products’ (unit_price * quantity)) + other_charges
                'discount'=>"0",                                                //Discount of the transaction. The Total amount of the invoice will be= amount - discount
                'currency' => "SAR",                                            //Currency of the amount stated. 3 character ISO currency code 
               
            
                
                //Invoice Information
                'title' => $package->name,               // Customer's Name on the invoice
                "msg_lang" => "Arabic",                 //Language of the PayPage to be created. Invalid or blank entries will default to English.(Englsh/Arabic)
                "reference_no" =>$package->id,        //Invoice reference number in your system
   
                //Website Information
                "site_url" => "https://www.theqqa.com",      //The requesting website be exactly the same as the website/URL associated with your PayTabs Merchant Account
                "return_url" => 'https://www.theqqa.com/api/verify_payment_service_paytabs',
                "cms_with_version" => "API USING PHP",

                "paypage_info" => "1"
            ));
       
            // echo "FOLLOWING IS THE RESPONSE: <br />";
            // print_r ($result);
}
	$p_service = new ServicePaytabs();
	$p_service->p_id=$result->p_id;
	$p_service->service_data=$dataservice;
   $p_service->save();
        // Redirection
        return response()->json([
            'status' => 'success',
            'data' => 'saved data',
            'result'=>$result->payment_url
        ]);

            // // Check if Payment is required
            // $package = Package::find($request->input('package_id'));

            // if (!empty($package)) {
            //     if ($package->price > 0 && $request->filled('payment_method_id')) {
            //         // Send the Payment

            //         return $this->sendPayment($request, $post);
            //     }
            // }
            // return response()->json([
            //     'status' => 'error',
            //     'massage' => t("We regret that we can not process your request at this time. Our engineers have been notified of this problem and will try to resolve it as soon as possible."),
            // ]);

        }
    }
    public function contact_ownership()
    {
        // Get the Country's largest city for Google Maps
        $cities = City::where('country_code','SA')->orderBy('name')->get();
        foreach ($cities as $data1){

            unset($data1->lon);
            unset($data1->latitude,$data1->longitude,$data1->feature_class,$data1->feature_code,$data1->subadmin1_code,$data1->subadmin2_code,$data1->population,$data1->time_zone
            );

        }
        $exhibitionsusers= User::where('user_type_id',6)->get();
        foreach ($exhibitionsusers as $data1){

            unset($data1->lon,$data1->lat,$data1->py_package_id,$data1->calculatedPrice,$data1->partner,$data1->fb_profile,$data1->deletion_mail_sent_at,$data1->country_code,$data1->description
                ,$data1->tags,$data1->phone_hidden,$data1->email_token,$data1->address, $data1->city_id, $data1->ip_addr,$data1->phone_token,$data1->tmp_token,$data1->negotiable);

        }
        // Meta Tags
        MetaTag::set('title', getMetaTag('title', 'contact'));
        MetaTag::set('description', strip_tags(getMetaTag('description', 'contact')));
        MetaTag::set('keywords', getMetaTag('keywords', 'contact'));
        $reqdocuments = '<p>
									<div style="font-size: 16px"><i class="icon-docs"></i>'.
            t('Now you can publish your ad for free or special on Theqqa .There are no documents required in the case of free advertising').'
									</div>
								</p>
								<h3>
									<strong><i class="fa fa-plus-circle"></i>'.  t('Featured Ad (Paid)').'</strong>
								</h3>

								<p>'. html_entity_decode(t('For_service_alert_2'), ENT_COMPAT).'</p>';
        return response()->json([
            'status' => 'success',
            'city' => $cities,
            'reqdocuments'=>$reqdocuments,
            'exhibitionsusers' =>$exhibitionsusers,
        ]);


    }
    public function contactPost_ownership(ApiContactRequest $request)
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
        $contactForm = $request->except('bank_transfer_in','car_Pictures','driving_license','purchaser_id_image','seller_id_image');

        // if(!empty( $request->driving_license)){

        //     $output_file="test.jpg";
        //     $ifp = fopen( $output_file, 'wb' );


        //     // we could add validation here with ensuring count( $data ) > 1
        //     fwrite( $ifp, base64_decode( $request->driving_license) );

        //     // clean up the file resource
        //     fclose( $ifp );
        //     //for driving license
        //     $extension_driving_license = getUploadedFileExtension($output_file);
        //     if (empty($extension_driving_license)) {
        //         $extension_driving_license = 'jpg';
        //     }
        //     // Make the image
        //     $image_driving_license = Image::make($output_file)->resize(400, 400, function ($constraint) {
        //         $constraint->aspectRatio();
        //     });
        //     // Generate a filename.
        //     $filename_driving_license = md5($output_file . time()) . '.' . $extension_driving_license;


        //     $destination_path = 'app/service/'.$request->id_code;
        //     // Store the image on disk.
        //     Storage::disk('public')->put($destination_path . '/' . $filename_driving_license, $image_driving_license->stream());

        //     $image_serv = new ImageService();
        //     $image_serv->image_code = $filename_driving_license;
        //     $image_serv->image_title = 'driving_license_image';
        //     $image_serv->token = $request->id_code;
        //     // Save
        //     $image_serv->save();
        // }


        // if(!empty($request->purchaser_id_image)) {
        //     $output_file="test.jpg";
        //     $ifp = fopen( $output_file, 'wb' );


        //     // we could add validation here with ensuring count( $data ) > 1
        //     fwrite( $ifp, base64_decode($request->purchaser_id_image ) );

        //     // clean up the file resource
        //     fclose( $ifp );
        //     //for purchaser id image
        //     $extension_purchaser_id_image = getUploadedFileExtension($output_file);
        //     if (empty($extension_purchaser_id_image)) {
        //         $extension_purchaser_id_image = 'jpg';
        //     }
        //     // Make the image
        //     $image_purchaser_id_image = Image::make($output_file)->resize(400, 400, function ($constraint) {
        //         $constraint->aspectRatio();
        //     });
        //     // Generate a filename.
        //     $filename_purchaser_id_image = md5($output_file . time()) . '.' . $extension_purchaser_id_image;
        //     $destination_path = 'app/service/'.$request->id_code;
        //     // Store the image on disk.
        //     Storage::disk('public')->put($destination_path . '/' . $filename_purchaser_id_image, $image_purchaser_id_image->stream());
        //     $image_serv = new ImageService();
        //     $image_serv->image_code = $filename_purchaser_id_image;
        //     $image_serv->image_title = 'purchaser_id_image';
        //     $image_serv->token = $request->id_code;
        //     // Save
        //     $image_serv->save();
        // }
        // if(!empty($request->seller_id_image)) {
        //     //for seller id image
        //     $output_file="test.jpg";
        //     $ifp = fopen( $output_file, 'wb' );


        //     // we could add validation here with ensuring count( $data ) > 1
        //     fwrite( $ifp, base64_decode( $request->seller_id_image ) );

        //     // clean up the file resource
        //     fclose( $ifp );
        //     $extension_seller_id_image = getUploadedFileExtension($output_file);
        //     if (empty($extension_seller_id_image)) {
        //         $extension_seller_id_image = 'jpg';
        //     }
        //     // Make the image
        //     $image_seller_id_image = Image::make($output_file)->resize(400, 400, function ($constraint) {
        //         $constraint->aspectRatio();
        //     });
        //     // Generate a filename.
        //     $filename_seller_id_image = md5($output_file . time()) . '.' . $extension_seller_id_image;
        //     $destination_path = 'app/service/'.$request->id_code;
        //     // Store the image on disk.
        //     Storage::disk('public')->put($destination_path . '/' . $filename_seller_id_image, $image_seller_id_image->stream());
        //     $image_serv = new ImageService();
        //     $image_serv->image_code = $filename_seller_id_image;
        //     $image_serv->image_title = 'seller_id_image';
        //     $image_serv->token = $request->id_code;
        //     // Save
        //     $image_serv->save();

        // }
        // if(!empty($request->car_Pictures)) {
        //     //for car Pictures

        //     $filename_car_Pictures_arr=[];
        //     $files_car_Pictures= explode(',', $request->car_Pictures);
        //     foreach ($files_car_Pictures as $key => $file__car_Picture) {
        //         $output_file=str_random(6).'jpg';
        //         $ifp = fopen( $output_file, 'wb' );


        //         // we could add validation here with ensuring count( $data ) > 1
        //         fwrite( $ifp, base64_decode( $file__car_Picture ) );

        //         // clean up the file resource
        //         fclose( $ifp );
        //         $extension_car_Pictures = getUploadedFileExtension($output_file);
        //         if (empty($extension_car_Pictures)) {
        //             $extension_car_Pictures = 'jpg';
        //         }
        //         // Make the image
        //         $image_car_Pictures = Image::make($output_file)->resize(400, 400, function ($constraint) {
        //             $constraint->aspectRatio();
        //         });
        //         // Generate a filename.
        //         $filename_car_Pictures = md5($output_file . time()) . '.' . $extension_car_Pictures;
        //         array_push($filename_car_Pictures_arr, $filename_car_Pictures);
        //         $destination_path = 'app/service/'.$request->id_code;
        //         // Store the image on disk.
        //         Storage::disk('public')->put($destination_path . '/' . $filename_car_Pictures, $image_car_Pictures->stream());
        //     }
        //     $image_serv = new ImageService();
        //     $image_serv->image_code = implode(',',$filename_car_Pictures_arr);
        //     $image_serv->image_title = 'car_Pictures';
        //     $image_serv->token = $request->id_code;

        //     // Save
        //     $image_serv->save();
        // }
        if ($request->payment_method_id == 2 && $request->package_id != 5 ){

            if(!empty($request->bank_transfer_in)) {
                //for booking bank image
                $extension_booking_bank_image = getUploadedFileExtension($request->bank_transfer_in);

                if (empty($extension_booking_bank_image)) {
                    $extension_booking_bank_image = 'jpg';
                }
                // Make the image
                $booking_bank_image = Image::make($request->bank_transfer_in)->resize(400, 400, function ($constraint) {
                    $constraint->aspectRatio();
                });

                // Generate a filename.
                $filename_booking_bank_image = md5($request->bank_transfer_in . time()) . '.' . $extension_booking_bank_image;
                $destination_path = 'app/booking';
                // Store the image on disk.
                Storage::disk('public')->put($destination_path . '/' . $filename_booking_bank_image, $booking_bank_image->stream());
            }


            $dataservice = json_encode($contactForm);
//            $output_file="test.jpg";
//            $ifp = fopen( $output_file, 'wb' );
//
//
//            // we could add validation here with ensuring count( $data ) > 1
//            fwrite( $ifp, base64_decode( $request->bank_transfer_in ) );
//
//            // clean up the file resource
//            fclose( $ifp );
//            //for booking bank image
//            $extension_booking_bank_image = getUploadedFileExtension($output_file);
//
//            if (empty($extension_booking_bank_image)) {
//                $extension_booking_bank_image = 'jpg';
//            }
//            // Make the image
//            $booking_bank_image = Image::make($output_file)->resize(400, 400, function ($constraint) {
//                $constraint->aspectRatio();
//            });
//
//            // Generate a filename.
//            $filename_booking_bank_image = md5($output_file. time()) . '.' . $extension_booking_bank_image;
//            $destination_path = 'app/booking';
//            // Store the image on disk.
//            Storage::disk('public')->put($destination_path . '/' . $filename_booking_bank_image, $booking_bank_image->stream());
            $package = Package::find($request->input('package_id'));

            $paymentInfo = [

                'post_id'           =>  0,
                'user_id'           =>  auth()->user()->id ,
                'price'             =>  $package->price,
                'package_id'        =>  $request->package_id,
                'payment_method_id' => $request->payment_method_id,
                'transaction_id'    => t('bank_transfer'),
                'active'            => 0,
                'date_service'      => $dataservice,
                'image'     => $filename_booking_bank_image ,
            ];




            // Save the payment
            $payment =!empty($paymentmodel)?$paymentmodel: new PaymentModel($paymentInfo);
            $payment->save();


            return response()->json([
                'status' => 'success',
                'massage' => t("You will be assured of bank transfer and confirmation of your booking by your email and by massages on Theqqa"),
            ]);

        }        else {
            
                 $dataservice = json_encode($contactForm);
         
      
                if ($request->paytabs == 1){
     $package = \App\Models\Package::find( $request->package_id);
        require_once 'paytabs.php';

                    $pt = new \App\Http\Controllers\API\paytabs(env('PAYTABS_EMAIL'), env('PAYTABS_SECRET_KEY'));

         $result =    $pt->create_pay_page(array(
                //Customer's Personal Information
             'merchant_email' =>   env('PAYTABS_EMAIL'),
             'secret_key' => env('PAYTABS_SECRET_KEY'),
                'cc_first_name' => $request->card_first_name,          //This will be prefilled as Credit Card First Name
                'cc_last_name' =>  $request->card_last_name,            //This will be prefilled as Credit Card Last Name
                'cc_phone_number' => "966",
                'phone_number' => $request->card_phone,
                'email' => $request->card_email,

              //Customer's Billing Address (All fields are mandatory)
                //When the country is selected as USA or CANADA, the state field should contain a String of 2 characters containing the ISO state code otherwise the payments may be rejected. 
                //For other countries, the state can be a string of up to 32 characters.
                'billing_address' => "4410 طريق الدمام - حي المؤنسية",
                'city' => "Riyadh",
                'state' => "Riyadh",
                'postal_code' => "13253",
                'country' => "SAU",
                
                //Customer's Shipping Address (All fields are mandatory)
                'address_shipping' => "4410 طريق الدمام - حي المؤنسية", 
                'city_shipping' => "Riyadh",
                'state_shipping' => "Riyadh",
                'postal_code_shipping' => "13253",
                'country_shipping' => "SAU",
               
              //Product Information
                "products_per_title" => $package->name,   //Product title of the product. If multiple products then add “||” separator
                'quantity' => "1",                                    //Quantity of products. If multiple products then add “||” separator
                'unit_price' => $package->price,                                  //Unit price of the product. If multiple products then add “||” separator.
                "other_charges" => "00.00",                                     //Additional charges. e.g.: shipping charges, taxes, VAT, etc.
                
                'amount' => $package->price,                                          //Amount of the products and other charges, it should be equal to: amount = (sum of all products’ (unit_price * quantity)) + other_charges
                'discount'=>"0",                                                //Discount of the transaction. The Total amount of the invoice will be= amount - discount
                'currency' => "SAR",                                            //Currency of the amount stated. 3 character ISO currency code 
               
            
                
                //Invoice Information
                'title' => $package->name,               // Customer's Name on the invoice
                "msg_lang" => "Arabic",                 //Language of the PayPage to be created. Invalid or blank entries will default to English.(Englsh/Arabic)
                "reference_no" =>$package->id,        //Invoice reference number in your system
   
                //Website Information
                "site_url" => "https://www.theqqa.com",      //The requesting website be exactly the same as the website/URL associated with your PayTabs Merchant Account
                "return_url" => 'https://www.theqqa.com/api/verify_payment_service_paytabs',
                "cms_with_version" => "API USING PHP",

                "paypage_info" => "1"
            ));
       
            // echo "FOLLOWING IS THE RESPONSE: <br />";
            // print_r ($result);
}
	$p_service = new ServicePaytabs();
	$p_service->p_id=$result->p_id;
	$p_service->service_data=$dataservice;
   $p_service->save();
        // Redirection
        return response()->json([
            'status' => 'success',
            'data' => 'saved data',
            'result'=>$result->payment_url
        ]);
            
            
            // // Check if Payment is required
            // $package = Package::find($request->input('package_id'));

            // if (!empty($package)) {
            //     if ($package->price > 0 && $request->filled('payment_method_id')) {
            //         // Send the Payment

            //         return $this->sendPayment($request, $post);
            //     }
            // }
            // return response()->json([
            //     'status' => 'error',
            //     'massage' => t("We regret that we can not process your request at this time. Our engineers have been notified of this problem and will try to resolve it as soon as possible."),
            // ]);

        }
//        Session::put('contactForm', $contactForm);
//        return redirect('post/0/paymentservice');
    }
    public function contact_maintenance()
    {
        // Get the Country's largest city for Google Maps
        $cities = City::where('country_code','SA')->orderBy('name')->get();
        $maintenance_users= User::where('user_type_id',4)->get();

        foreach ($cities as $data1){

            unset($data1->lon);
            unset($data1->latitude,$data1->longitude,$data1->feature_class,$data1->feature_code,$data1->subadmin1_code,$data1->subadmin2_code,$data1->population,$data1->time_zone
            );

        }
        $exhibitionsusers= User::where('user_type_id',6)->get();
        foreach ($maintenance_users as $data1){

            unset($data1->lon,$data1->lat,$data1->py_package_id,$data1->calculatedPrice,$data1->partner,$data1->fb_profile,$data1->deletion_mail_sent_at,$data1->country_code,$data1->description
                ,$data1->tags,$data1->phone_hidden,$data1->email_token,$data1->address, $data1->city_id, $data1->ip_addr,$data1->phone_token,$data1->tmp_token,$data1->negotiable);

        }
        // Meta Tags
        MetaTag::set('title', getMetaTag('title', 'contact'));
        MetaTag::set('description', strip_tags(getMetaTag('description', 'contact')));
        MetaTag::set('keywords', getMetaTag('keywords', 'contact'));
        $reqdocuments = '<p>
									<div style="font-size: 16px"><i class="icon-docs"></i>'.
            t('Now you can publish your ad for free or special on Theqqa .There are no documents required in the case of free advertising').'
									</div>
								</p>
								<h3>
									<strong><i class="fa fa-plus-circle"></i>'.  t('Featured Ad (Paid)').'</strong>
								</h3>

								<p>'. html_entity_decode(t('For_service_alert_2'), ENT_COMPAT).'</p>';
        return response()->json([
            'status' => 'success',
            'maintenance_users' => $maintenance_users,
            'reqdocuments'=>$reqdocuments,
            'city' => $cities,
        ]);
//        return view('pages.maintenance');
    }
    public function contactPost_maintenance(ApiContactRequest $request)
    {
        $contactForm = $request->except('bank_transfer_in');


        if ($request->payment_method_id == 2 && $request->package_id != 5 ){

            $dataservice = json_encode($contactForm);
            if(!empty($request->bank_transfer_in)) {
                //for booking bank image
                $extension_booking_bank_image = getUploadedFileExtension($request->bank_transfer_in);

                if (empty($extension_booking_bank_image)) {
                    $extension_booking_bank_image = 'jpg';
                }
                // Make the image
                $booking_bank_image = Image::make($request->bank_transfer_in)->resize(400, 400, function ($constraint) {
                    $constraint->aspectRatio();
                });

                // Generate a filename.
                $filename_booking_bank_image = md5($request->bank_transfer_in . time()) . '.' . $extension_booking_bank_image;
                $destination_path = 'app/booking';
                // Store the image on disk.
                Storage::disk('public')->put($destination_path . '/' . $filename_booking_bank_image, $booking_bank_image->stream());
            }

//            $output_file="test.jpg";
//            $ifp = fopen( $output_file, 'wb' );
//
//
//            // we could add validation here with ensuring count( $data ) > 1
//            fwrite( $ifp, base64_decode( $request->bank_transfer_in ) );
//
//            // clean up the file resource
//            fclose( $ifp );
//            //for booking bank image
//            $extension_booking_bank_image = getUploadedFileExtension($output_file);
//
//            if (empty($extension_booking_bank_image)) {
//                $extension_booking_bank_image = 'jpg';
//            }
//            // Make the image
//            $booking_bank_image = Image::make($output_file)->resize(400, 400, function ($constraint) {
//                $constraint->aspectRatio();
//            });
//
//            // Generate a filename.
//            $filename_booking_bank_image = md5($output_file. time()) . '.' . $extension_booking_bank_image;
//            $destination_path = 'app/booking';
//            // Store the image on disk.
//            Storage::disk('public')->put($destination_path . '/' . $filename_booking_bank_image, $booking_bank_image->stream());
            $package = Package::find($request->input('package_id'));

            $paymentInfo = [

                'post_id'           =>  0,
                'user_id'           =>  auth()->user()->id ,
                'price'             =>  $package->price,
                'package_id'        =>  $request->package_id,
                'payment_method_id' => $request->payment_method_id,
                'transaction_id'    => t('bank_transfer'),
                'active'            => 0,
                'date_service'      => $dataservice,
                'image'     => $filename_booking_bank_image ,
            ];


            // Save the payment
            $payment =!empty($paymentmodel)?$paymentmodel: new PaymentModel($paymentInfo);
            $payment->save();


            return response()->json([
                'status' => 'success',
                'massage' => t("You will be assured of bank transfer and confirmation of your booking by your email and by massages on Theqqa"),
            ]);

        }
        else {
                 $dataservice = json_encode($contactForm);
         
      
                if ($request->paytabs == 1){
     $package = \App\Models\Package::find( $request->package_id);
        require_once 'paytabs.php';


                    $pt = new \App\Http\Controllers\API\paytabs(env('PAYTABS_EMAIL'), env('PAYTABS_SECRET_KEY'));
         $result =    $pt->create_pay_page(array(
                //Customer's Personal Information
             'merchant_email' =>   env('PAYTABS_EMAIL'),
             'secret_key' => env('PAYTABS_SECRET_KEY'),
                'cc_first_name' => $request->card_first_name,          //This will be prefilled as Credit Card First Name
                'cc_last_name' =>  $request->card_last_name,            //This will be prefilled as Credit Card Last Name
                'cc_phone_number' => "966",
                'phone_number' => $request->card_phone,
                'email' => $request->card_email,

              //Customer's Billing Address (All fields are mandatory)
                //When the country is selected as USA or CANADA, the state field should contain a String of 2 characters containing the ISO state code otherwise the payments may be rejected. 
                //For other countries, the state can be a string of up to 32 characters.
                'billing_address' => "4410 طريق الدمام - حي المؤنسية",
                'city' => "Riyadh",
                'state' => "Riyadh",
                'postal_code' => "13253",
                'country' => "SAU",
                
                //Customer's Shipping Address (All fields are mandatory)
                'address_shipping' => "4410 طريق الدمام - حي المؤنسية", 
                'city_shipping' => "Riyadh",
                'state_shipping' => "Riyadh",
                'postal_code_shipping' => "13253",
                'country_shipping' => "SAU",
               
              //Product Information
                "products_per_title" => $package->name,   //Product title of the product. If multiple products then add “||” separator
                'quantity' => "1",                                    //Quantity of products. If multiple products then add “||” separator
                'unit_price' => $package->price,                                  //Unit price of the product. If multiple products then add “||” separator.
                "other_charges" => "00.00",                                     //Additional charges. e.g.: shipping charges, taxes, VAT, etc.
                
                'amount' => $package->price,                                          //Amount of the products and other charges, it should be equal to: amount = (sum of all products’ (unit_price * quantity)) + other_charges
                'discount'=>"0",                                                //Discount of the transaction. The Total amount of the invoice will be= amount - discount
                'currency' => "SAR",                                            //Currency of the amount stated. 3 character ISO currency code 
               
            
                
                //Invoice Information
                'title' => $package->name,               // Customer's Name on the invoice
                "msg_lang" => "Arabic",                 //Language of the PayPage to be created. Invalid or blank entries will default to English.(Englsh/Arabic)
                "reference_no" =>$package->id,        //Invoice reference number in your system
   
                //Website Information
                "site_url" => "https://www.theqqa.com",      //The requesting website be exactly the same as the website/URL associated with your PayTabs Merchant Account
                "return_url" => 'https://www.theqqa.com/api/verify_payment_service_paytabs',
                "cms_with_version" => "API USING PHP",

                "paypage_info" => "1"
            ));
       
            // echo "FOLLOWING IS THE RESPONSE: <br />";
            // print_r ($result);
}
	$p_service = new ServicePaytabs();
	$p_service->p_id=$result->p_id;
	$p_service->service_data=$dataservice;
   $p_service->save();
        // Redirection
        return response()->json([
            'status' => 'success',
            'data' => 'saved data',
            'result'=>$result->payment_url
        ]);
            // // Check if Payment is required
            // $package = Package::find($request->input('package_id'));

            // if (!empty($package)) {
            //     if ($package->price > 0 && $request->filled('payment_method_id')) {
            //         // Send the Payment

            //         return $this->sendPayment($request, $post);
            //     }
            // }
            // return response()->json([
            //     'status' => 'error',
            //     'massage' => t("We regret that we can not process your request at this time. Our engineers have been notified of this problem and will try to resolve it as soon as possible."),
            // ]);

        }

    }
    public function contact_checking()
    {
        // Get the Country's largest city for Google Maps
        $cities = City::where('country_code','SA')->orderBy('name')->get();
        foreach ($cities as $data1){

            unset($data1->lon);
            unset($data1->latitude,$data1->longitude,$data1->feature_class,$data1->feature_code,$data1->subadmin1_code,$data1->subadmin2_code,$data1->population,$data1->time_zone
            );

        }
        // Meta Tags
        MetaTag::set('title', getMetaTag('title', 'contact'));
        MetaTag::set('description', strip_tags(getMetaTag('description', 'contact')));
        MetaTag::set('keywords', getMetaTag('keywords', 'contact'));
        $reqdocuments = '<p>
									<div style="font-size: 16px"><i class="icon-docs"></i>'.
            t('Now you can publish your ad for free or special on Theqqa .There are no documents required in the case of free advertising').'
									</div>
								</p>
								<h3>
									<strong><i class="fa fa-plus-circle"></i>'.  t('Featured Ad (Paid)').'</strong>
								</h3>

								<p>'. html_entity_decode(t('For_service_alert_2'), ENT_COMPAT).'</p>';
        return response()->json([
            'status' => 'success',
            'city' => $cities,
            'reqdocuments'=>$reqdocuments,
        ]);
//        return view('pages.checking');
    }
    public function contactPost_checking(ApiContactRequest $request)
    {
        $contactForm = $request->except('bank_transfer_in');

        if ($request->payment_method_id == 2 && $request->package_id != 5 ){
            $dataservice = json_encode($contactForm);
            if(!empty($request->bank_transfer_in)) {
                //for booking bank image
                $extension_booking_bank_image = getUploadedFileExtension($request->bank_transfer_in);

                if (empty($extension_booking_bank_image)) {
                    $extension_booking_bank_image = 'jpg';
                }
                // Make the image
                $booking_bank_image = Image::make($request->bank_transfer_in)->resize(400, 400, function ($constraint) {
                    $constraint->aspectRatio();
                });

                // Generate a filename.
                $filename_booking_bank_image = md5($request->bank_transfer_in . time()) . '.' . $extension_booking_bank_image;
                $destination_path = 'app/booking';
                // Store the image on disk.
                Storage::disk('public')->put($destination_path . '/' . $filename_booking_bank_image, $booking_bank_image->stream());
            }


//            $output_file="test.jpg";
//            $ifp = fopen( $output_file, 'wb' );
//
//
//            // we could add validation here with ensuring count( $data ) > 1
//            fwrite( $ifp, base64_decode( $request->bank_transfer_in ) );
//
//            // clean up the file resource
//            fclose( $ifp );
//            //for booking bank image
//            $extension_booking_bank_image = getUploadedFileExtension($output_file);
//
//            if (empty($extension_booking_bank_image)) {
//                $extension_booking_bank_image = 'jpg';
//            }
//            // Make the image
//            $booking_bank_image = Image::make($output_file)->resize(400, 400, function ($constraint) {
//                $constraint->aspectRatio();
//            });
//
//            // Generate a filename.
//            $filename_booking_bank_image = md5($output_file. time()) . '.' . $extension_booking_bank_image;
//            $destination_path = 'app/booking';
//            // Store the image on disk.
//            Storage::disk('public')->put($destination_path . '/' . $filename_booking_bank_image, $booking_bank_image->stream());
            $package = Package::find($request->input('package_id'));

            $paymentInfo = [

                'post_id'           => 0,
                'user_id'           =>  auth()->user()->id ,
                'price'             =>  $package->price,
                'package_id'        =>  $request->package_id,
                'payment_method_id' => $request->payment_method_id,
                'transaction_id'    => t('bank_transfer'),
                'active'            => 0,
                'date_service'      => $dataservice,
                'image'     => $filename_booking_bank_image ,
            ];
                      // Save the payment
            $payment =!empty($paymentmodel)?$paymentmodel: new PaymentModel($paymentInfo);
            $payment->save();


            return response()->json([
                'status' => 'success',
                'massage' => t("You will be assured of bank transfer and confirmation of your booking by your email and by massages on Theqqa"),
            ]);

        }
        else {
                 $dataservice = json_encode($contactForm);
         
      
                if ($request->paytabs == 1){
     $package = \App\Models\Package::find( $request->package_id);
        require_once 'paytabs.php';


                    $pt = new \App\Http\Controllers\API\paytabs(env('PAYTABS_EMAIL'), env('PAYTABS_SECRET_KEY'));
         $result =    $pt->create_pay_page(array(
                //Customer's Personal Information
             'merchant_email' =>   env('PAYTABS_EMAIL'),
             'secret_key' => env('PAYTABS_SECRET_KEY'),
                'cc_first_name' => $request->card_first_name,          //This will be prefilled as Credit Card First Name
                'cc_last_name' =>  $request->card_last_name,            //This will be prefilled as Credit Card Last Name
                'cc_phone_number' => "966",
                'phone_number' => $request->card_phone,
                'email' => $request->card_email,

              //Customer's Billing Address (All fields are mandatory)
                //When the country is selected as USA or CANADA, the state field should contain a String of 2 characters containing the ISO state code otherwise the payments may be rejected. 
                //For other countries, the state can be a string of up to 32 characters.
                'billing_address' => "4410 طريق الدمام - حي المؤنسية",
                'city' => "Riyadh",
                'state' => "Riyadh",
                'postal_code' => "13253",
                'country' => "SAU",
                
                //Customer's Shipping Address (All fields are mandatory)
                'address_shipping' => "4410 طريق الدمام - حي المؤنسية", 
                'city_shipping' => "Riyadh",
                'state_shipping' => "Riyadh",
                'postal_code_shipping' => "13253",
                'country_shipping' => "SAU",
               
              //Product Information
                "products_per_title" => $package->name,   //Product title of the product. If multiple products then add “||” separator
                'quantity' => "1",                                    //Quantity of products. If multiple products then add “||” separator
                'unit_price' => $package->price,                                  //Unit price of the product. If multiple products then add “||” separator.
                "other_charges" => "00.00",                                     //Additional charges. e.g.: shipping charges, taxes, VAT, etc.
                
                'amount' => $package->price,                                          //Amount of the products and other charges, it should be equal to: amount = (sum of all products’ (unit_price * quantity)) + other_charges
                'discount'=>"0",                                                //Discount of the transaction. The Total amount of the invoice will be= amount - discount
                'currency' => "SAR",                                            //Currency of the amount stated. 3 character ISO currency code 
               
            
                
                //Invoice Information
                'title' => $package->name,               // Customer's Name on the invoice
                "msg_lang" => "Arabic",                 //Language of the PayPage to be created. Invalid or blank entries will default to English.(Englsh/Arabic)
                "reference_no" =>$package->id,        //Invoice reference number in your system
   
                //Website Information
                "site_url" => "https://www.theqqa.com",      //The requesting website be exactly the same as the website/URL associated with your PayTabs Merchant Account
                "return_url" => 'https://www.theqqa.com/api/verify_payment_service_paytabs',
                "cms_with_version" => "API USING PHP",

                "paypage_info" => "1"
            ));
       
            // echo "FOLLOWING IS THE RESPONSE: <br />";
            // print_r ($result);
}
	$p_service = new ServicePaytabs();
	$p_service->p_id=$result->p_id;
	$p_service->service_data=$dataservice;
   $p_service->save();
        // Redirection
        return response()->json([
            'status' => 'success',
            'data' => 'saved data',
            'result'=>$result->payment_url
        ]);
            // // Check if Payment is required
            // $package = Package::find($request->input('package_id'));

            // if (!empty($package)) {
            //     if ($package->price > 0 && $request->filled('payment_method_id')) {
            //         // Send the Payment

            //         return $this->sendPayment($request, $post);
            //     }
            // }
            // return response()->json([
            //     'status' => 'error',
            //     'massage' => t("We regret that we can not process your request at this time. Our engineers have been notified of this problem and will try to resolve it as soon as possible."),
            // ]);

        }
    }
    public function contact_shipping()
    {
        // Get the Country's largest city for Google Maps
        $cities = City::where('country_code','SA')->orderBy('name')->get();
        foreach ($cities as $data1){

            unset($data1->lon);
            unset($data1->latitude,$data1->longitude,$data1->feature_class,$data1->feature_code,$data1->subadmin1_code,$data1->subadmin2_code,$data1->population,$data1->time_zone
            );

        }
        $shipping_users= User::where('user_type_id',5)->get();
        foreach ($shipping_users as $data1){

            unset($data1->lon,$data1->lat,$data1->py_package_id,$data1->calculatedPrice,$data1->partner,$data1->fb_profile,$data1->deletion_mail_sent_at,$data1->country_code,$data1->description
                ,$data1->tags,$data1->phone_hidden,$data1->email_token,$data1->address, $data1->city_id, $data1->ip_addr,$data1->phone_token,$data1->tmp_token,$data1->negotiable);

        }
        $reqdocuments = '<p>
									<div style="font-size: 16px"><i class="icon-docs"></i>'.
            t('Now you can publish your ad for free or special on Theqqa .There are no documents required in the case of free advertising').'
									</div>
								</p>
								<h3>
									<strong><i class="fa fa-plus-circle"></i>'.  t('Featured Ad (Paid)').'</strong>
								</h3>

								<p>'. html_entity_decode(t('For_service_alert_2'), ENT_COMPAT).'</p>';
        // Meta Tags
        MetaTag::set('title', getMetaTag('title', 'contact'));
        MetaTag::set('description', strip_tags(getMetaTag('description', 'contact')));
        MetaTag::set('keywords', getMetaTag('keywords', 'contact'));
        return response()->json([
            'status' => 'success',
            'shipping_users' => $shipping_users,
            'city' => $cities,
            'reqdocuments'=>$reqdocuments,
        ]);

    }
    public function contactPost_shipping(ApiContactRequest $request)
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
        $contactForm = $request->except('bank_transfer_in','car_Pictures');


    //        if(!empty($request->car_Pictures)) {
    //            //for car Pictures
    //            $filename_car_Pictures_arr=[];
    //            $files_car_Pictures= explode(',', $request->car_Pictures);
    //            foreach ($files_car_Pictures as $key => $file__car_Picture) {
    //                $output_file=str_random(6).'jpg';
    //                $ifp = fopen( $output_file, 'wb' );
    //
    //                // split the string on commas
    //                // $data[ 0 ] == "data:image/png;base64"
    //                // $data[ 1 ] == <actual base64 string>
    //                //  $data = explode( ',', $base64_string );
    //
    //                // we could add validation here with ensuring count( $data ) > 1
    //                fwrite( $ifp, base64_decode($file__car_Picture ) );
    //
    //                // clean up the f
    //                $extension_car_Pictures = getUploadedFileExtension($output_file);
    //                if (empty($extension_car_Pictures)) {
    //                    $extension_car_Pictures = 'jpg';
    //                }
    //                // Make the image
    //                $image_car_Pictures = Image::make($output_file)->resize(400, 400, function ($constraint) {
    //                    $constraint->aspectRatio();
    //                });
    //                // Generate a filename.
    //                $filename_car_Pictures = md5($output_file . time()) . '.' . $extension_car_Pictures;
    //                array_push($filename_car_Pictures_arr, $filename_car_Pictures);
    //                $destination_path = 'app/service/'.$request->id_code;
    //                // Store the image on disk.
    //                Storage::disk('public')->put($destination_path . '/' . $filename_car_Pictures, $image_car_Pictures->stream());
    //            }
    //            $image_serv = new ImageService();
    //            $image_serv->image_code = implode(',',$filename_car_Pictures_arr);
    //            $image_serv->image_title = 'car_Pictures';
    //            $image_serv->token = $request->id_code;
    //
    //            // Save
    //            $image_serv->save();
    //        }

        if ($request->payment_method_id == 2 && $request->package_id != 5 ){
//            $dataservice = implode(",",$contactForm);
            $dataservice = json_encode($contactForm);
            if(!empty($request->bank_transfer_in)) {
                //for booking bank image
                $extension_booking_bank_image = getUploadedFileExtension($request->bank_transfer_in);

                if (empty($extension_booking_bank_image)) {
                    $extension_booking_bank_image = 'jpg';
                }
                // Make the image
                $booking_bank_image = Image::make($request->bank_transfer_in)->resize(400, 400, function ($constraint) {
                    $constraint->aspectRatio();
                });

                // Generate a filename.
                $filename_booking_bank_image = md5($request->bank_transfer_in . time()) . '.' . $extension_booking_bank_image;
                $destination_path = 'app/booking';
                // Store the image on disk.
                Storage::disk('public')->put($destination_path . '/' . $filename_booking_bank_image, $booking_bank_image->stream());
            }



//            $output_file="test.jpg";
//            $ifp = fopen( $output_file, 'wb' );
//
//            // split the string on commas
//            // $data[ 0 ] == "data:image/png;base64"
//            // $data[ 1 ] == <actual base64 string>
//          //  $data = explode( ',', $base64_string );
//
//            // we could add validation here with ensuring count( $data ) > 1
//            fwrite( $ifp, base64_decode( $request->bank_transfer_in ) );
//
//            // clean up the file resource
//            fclose( $ifp );
//            //for booking bank image
//            $extension_booking_bank_image = getUploadedFileExtension($output_file);
//
//            if (empty($extension_booking_bank_image)) {
//                $extension_booking_bank_image = 'jpg';
//            }
//            // Make the image
//            $booking_bank_image = Image::make($output_file)->resize(400, 400, function ($constraint) {
//                $constraint->aspectRatio();
//            });
//
//            // Generate a filename.
//            $filename_booking_bank_image = md5($output_file. time()) . '.' . $extension_booking_bank_image;
//            $destination_path = 'app/booking';
//            // Store the image on disk.
//            Storage::disk('public')->put($destination_path . '/' . $filename_booking_bank_image, $booking_bank_image->stream());
            $package = Package::find($request->input('package_id'));


//
            $paymentInfo = [

                'post_id'           =>  0,
                'user_id'           =>  auth()->user()->id ,
                'price'             =>  $package->price,
                'package_id'        =>  $request->package_id,
                'payment_method_id' => $request->payment_method_id,
                'transaction_id'    => t('bank_transfer'),
                'active'            => 0,
                'date_service'      => $dataservice,
                'image'     => $filename_booking_bank_image ,
            ];

            // Save the payment
            $payment =!empty($paymentmodel)?$paymentmodel: new PaymentModel($paymentInfo);
            $payment->save();


            return response()->json([
                'status' => 'success',
                'massage' => t("You will be assured of bank transfer and confirmation of your booking by your email and by massages on Theqqa"),
            ]);

        }
        else {
            // // Check if Payment is required
            // $package = Package::find($request->input('package_id'));

            // if (!empty($package)) {
            //     if ($package->price > 0 && $request->filled('payment_method_id')) {
            //         // Send the Payment

            //         return $this->sendPayment($request, $post);
            //     }
            // }
            // return response()->json([
            //     'status' => 'error',
            //     'massage' => t("We regret that we can not process your request at this time. Our engineers have been notified of this problem and will try to resolve it as soon as possible."),
            // ]);
                 $dataservice = json_encode($contactForm);
         
      
                if ($request->paytabs == 1){
     $package = \App\Models\Package::find( $request->package_id);
        require_once 'paytabs.php';

         $pt = new \App\Http\Controllers\API\paytabs(env('PAYTABS_EMAIL'), env('PAYTABS_SECRET_KEY'));


         $result =    $pt->create_pay_page(array(
                //Customer's Personal Information
                'merchant_email' =>   env('PAYTABS_EMAIL'),
                'secret_key' => env('PAYTABS_SECRET_KEY'),
                'cc_first_name' => $request->card_first_name,          //This will be prefilled as Credit Card First Name
                'cc_last_name' =>  $request->card_last_name,            //This will be prefilled as Credit Card Last Name
                'cc_phone_number' => "966",
                'phone_number' => $request->card_phone,
                'email' => $request->card_email,

              //Customer's Billing Address (All fields are mandatory)
                //When the country is selected as USA or CANADA, the state field should contain a String of 2 characters containing the ISO state code otherwise the payments may be rejected. 
                //For other countries, the state can be a string of up to 32 characters.
                'billing_address' => "4410 طريق الدمام - حي المؤنسية",
                'city' => "Riyadh",
                'state' => "Riyadh",
                'postal_code' => "13253",
                'country' => "SAU",
                
                //Customer's Shipping Address (All fields are mandatory)
                'address_shipping' => "4410 طريق الدمام - حي المؤنسية", 
                'city_shipping' => "Riyadh",
                'state_shipping' => "Riyadh",
                'postal_code_shipping' => "13253",
                'country_shipping' => "SAU",
               
              //Product Information
                "products_per_title" => $package->name,   //Product title of the product. If multiple products then add “||” separator
                'quantity' => "1",                                    //Quantity of products. If multiple products then add “||” separator
                'unit_price' => $package->price,                                  //Unit price of the product. If multiple products then add “||” separator.
                "other_charges" => "00.00",                                     //Additional charges. e.g.: shipping charges, taxes, VAT, etc.
                
                'amount' => $package->price,                                          //Amount of the products and other charges, it should be equal to: amount = (sum of all products’ (unit_price * quantity)) + other_charges
                'discount'=>"0",                                                //Discount of the transaction. The Total amount of the invoice will be= amount - discount
                'currency' => "SAR",                                            //Currency of the amount stated. 3 character ISO currency code 
               
            
                
                //Invoice Information
                'title' => $package->name,               // Customer's Name on the invoice
                "msg_lang" => "Arabic",                 //Language of the PayPage to be created. Invalid or blank entries will default to English.(Englsh/Arabic)
                "reference_no" =>$package->id,        //Invoice reference number in your system
   
                //Website Information
                "site_url" => "https://www.theqqa.com",      //The requesting website be exactly the same as the website/URL associated with your PayTabs Merchant Account
                "return_url" => 'https://www.theqqa.com/api/verify_payment_service_paytabs',
                "cms_with_version" => "API USING PHP",

                "paypage_info" => "1"
            ));
       
            // echo "FOLLOWING IS THE RESPONSE: <br />";
            // print_r ($result);
}
	$p_service = new ServicePaytabs();
	$p_service->p_id=$result->p_id;
	$p_service->service_data=$dataservice;
   $p_service->save();
        // Redirection
        return response()->json([
            'status' => 'success',
            'data' => 'saved data',
            'result'=>$result->payment_url
        ]);

        }
    }
    public function searchedposts($countryCode='SA')
    {

        $query = request()->get('query');

        $postsArr = [];


            $posts = Post::countryOf($countryCode)->get();
        $posts = collect($posts)->map(function ($post) {
            $post->city_id=\App\Models\City::find($post->city_id);
            $post->title = mb_ucfirst($post->title);
            $post->uri = trans('routes.v-post', ['slug' => slugify($post->title), 'id' => $post->id]);
            $pictures = \App\Models\Picture::where('post_id', $post->id)->orderBy('position')->orderBy('id');
            if ($pictures->count() > 0) {
                $postImg = resize($pictures->first()->filename, 'medium');
            } else {
                $postImg = resize(config('larapen.core.picture.default'));}
            $post->url_picture=$postImg;
            return $post;
        });
//            $jesionposts=json_decode($posts);

//                ->where(function ($builder) use ($query) {
////                    $builder->where('title', 'LIKE', $query . '%');
////                    $builder->orWhere('title', 'LIKE', '%' . $query);
//                });

            $limit = 25;
            $cacheId = $countryCode . '.posts.where.title.' . $query . '.take.' . $limit;
//            $posts = Cache::remember($cacheId, $this->cacheExpiration, function () use ($posts, $limit) {
//                $posts = $posts->orderBy('title')->take($limit)->get();
//
//                return $posts;
//            });

            foreach ($posts as $post) {
                if( url()->previous() ==  url('/'."contactfor/checking") or url()->previous() == url('/'."contactfor/maintenance") ){
                    if($post->category_id != 440 and $post->category_id != 439){
                        if($post->post_type_id == 6 )
                        {
                            $value = $post->title;
                            $postsArr[] = [
                                'data'  => $post,
                                'value' => $value,
//                                'image' => $post->first_image['filename'],
                            ];
                        }
                    }
                }else{
                    if($post->post_type_id == 6 )
                    {
                        $value = $post->title;
                        $postsArr[] = [
                            'data'  => $post,
                            'value' => $value,
//                            'image' => $post->first_image['filename'],
                        ];
                    }


                }


            }



        $result = [
            'query'       => $query,
            'suggestions' => $postsArr,
        ];

        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }
    public function register_serve(Post $post, $params)
    {
        if (empty($post)) {
            return null;
        }

        // Update ad 'reviewed'
        $post->reviewed = 1;
        $post->featured = 1;
        $post->save();

        // Get the payment info
        $post->id = (($post->id == 0)? null:$post->id);
        $paymentInfo = [
            'post_id'           =>  $post->id,
            'package_id'        => $params['package_id'],
            'payment_method_id' => $params['payment_method_id'],
            'transaction_id'    => (isset($params['transaction_id'])) ? $params['transaction_id'] : null,
        ];

        // Check the uniqueness of the payment
        if($post->id != NULL){
            $payment = PaymentModel::where('post_id', $paymentInfo['post_id'])
                ->where('package_id', $paymentInfo['package_id'])
                ->where('payment_method_id', $params['payment_method_id'])
                ->first();
        }

        if (!empty($payment)) {
            return $payment;
        }

        // Save the payment
        $payment = new PaymentModel($paymentInfo);
        $payment->save();
        $contactForm= Session::get('contactForm');
        $contactForm['country_code'] = config('country.code');
        $contactForm['country_name'] = config('country.name');
        $contactForm = Arr::toObject($contactForm);
        $admins = User::permission(Permission::getStaffPermissions())->get();
        $mass=[];
        $mass[] = [
            t('Country') => !empty($contactForm->country_name)?$contactForm->country_name:'' ,
            t('First Name') =>!empty($contactForm->first_name)?$contactForm->first_name:'' ,
            t('Last Name') =>  !empty($contactForm->last_name)?$contactForm->last_name:'',
            t('Owner') => !empty($contactForm->owner)?$contactForm->owner:'',
            t('User') =>  !empty($contactForm->user)?$contactForm->user:'',
            t('Owner ID') => !empty( $contactForm->owner_id)? $contactForm->owner_id:'',
            t('User ID') =>  !empty($contactForm->user_id)?$contactForm->user_id:'',
            t('Structure number') =>  !empty($contactForm->structure_number)?$contactForm->structure_number:'',
            t('plate number') => !empty($contactForm->plate_number)?$contactForm->plate_number:'' ,
            t('brand vehicle') => !empty($contactForm->brand_vehicle)?$contactForm->brand_vehicle:'' ,
            t('Vehicle Weight') => !empty($contactForm->Vehicle_Weight)?$contactForm->Vehicle_Weight:'' ,
            t('serial number') =>  !empty($contactForm->serial_number)?$contactForm->serial_number:'',
            t('Message') =>  !empty($contactForm->message)?$contactForm->message:'',
            t('car url') => ' <a href="' . lurl( (!empty($contactForm->car_url) ? $contactForm->car_url:'SA')) . '">' . (!empty($contactForm->car_url) ? $contactForm->car_url:t('car_in')) . '</a>',
        ];
        // New Message
        $message = new Message();

//                $input = $contactForm->only($message->getFillable());
//                foreach ($input as $key => $value) {
//                    $message->{$key} = $value;
//                }

        $message->post_id = '0';
        $message->from_user_id = auth()->check() ? auth()->user()->id : 0;
        $message->to_user_id =  !empty($admins['0']['id'])?$admins['0']['id']:'' ;
        $message->to_name = !empty($admins['0']['name'])?$admins['0']['name']:'';
        $message->to_email = !empty($admins['0']['email'])?$admins['0']['email']:'';
        $message->to_phone = !empty($admins['0']['phone'])?$admins['0']['phone']:'';
        $message->from_name =auth()->check() ? auth()->user()->name : 0;
        $message->from_email = auth()->check() ? auth()->user()->email : 0;
        $message->subject = !empty($contactForm->service_type)?$contactForm->service_type:'';
        $message->message = json_encode($mass);

        // Save
        $message->save();

        // Save and Send user's resume
        $message->save();

        if ($message->subject == t( 'shipping_title') )
        {

            $messageshipping = new Message();
            $adminsshipping = User::where('id',$contactForm->shipping_id)->get();
            $messageshipping->post_id = '0';
            $messageshipping->from_user_id = auth()->check() ? auth()->user()->id : 0;
            $messageshipping->to_user_id =  !empty($adminsshipping['0']['id'])?$adminsshipping['0']['id']:'' ;
            $messageshipping->to_name = !empty($adminsshipping['0']['name'])?$adminsshipping['0']['name']:'';
            $messageshipping->to_email = !empty($adminsshipping['0']['email'])?$adminsshipping['0']['email']:'';
            $messageshipping->to_phone = !empty($adminsshipping['0']['phone'])?$adminsshipping['0']['phone']:'';
            $messageshipping->from_name =auth()->check() ? auth()->user()->name : 0;
            $messageshipping->from_email = auth()->check() ? auth()->user()->email : 0;
            $messageshipping->subject = t( 'shipping_title') ;
            $messageshipping->message = json_encode($mass);
            // Save
            $messageshipping->save();


        }
        if ($message->subject ==  t( 'maintenance_title') )
        {
            $messagemaintenance = new Message();
            $adminsmaintenance = User::where('id',$contactForm->maintenance_id)->get();
            $messagemaintenance->post_id = '0';
            $messagemaintenance->from_user_id = auth()->check() ? auth()->user()->id : 0;
            $messagemaintenance->to_user_id =  !empty($adminsmaintenance['0']['id'])?$adminsmaintenance['0']['id']:'' ;
            $messagemaintenance->to_name = !empty($adminsmaintenance['0']['name'])?$adminsmaintenance['0']['name']:'';
            $messagemaintenance->to_email = !empty($adminsmaintenance['0']['email'])?$adminsmaintenance['0']['email']:'';
            $messagemaintenance->to_phone = !empty($adminsmaintenance['0']['phone'])?$adminsmaintenance['0']['phone']:'';
            $messagemaintenance->from_name =auth()->check() ? auth()->user()->name : 0;
            $messagemaintenance->from_email = auth()->check() ? auth()->user()->email : 0;
            $messagemaintenance->subject = t( 'maintenance_title') ;


            $messagemaintenance->message = json_encode($mass);

            // Save
            $messagemaintenance->save();
        }
        return response()->json([
            'status' => 'success',
            'data' => 'saved payment service',
        ]);
    }
      public function verify_payment_service_paytabs(Request $request)
    {


        require_once 'paytabs.php';

        $pt = new \App\Http\Controllers\API\paytabs(env('PAYTABS_EMAIL'), env('PAYTABS_SECRET_KEY'));
$result = $pt->verify_payment($_POST['payment_reference']);

 if($result->response_code = 100){

 $service_data= ServicePaytabs::where('p_id', $result->pt_invoice_id) ->first();
 $data=json_decode($service_data->service_data);

   $user = User::where('id',$data->card_user_id)->first();
 $admins = User::permission(Permission::getStaffPermissions())->get();
$package = \App\Models\Package::find($result->reference_no);
     $paymentInfo = [

         'post_id'           => 0,
         'user_id'           =>$data->card_user_id,
         'price'            =>  $package->price,
         'package_id'        => $result->reference_no,
         'payment_method_id' => '3',
         'transaction_id'    => t('Paytabs'),
         'active'               => 1,
         'date_service'      => $service_data->service_data,
         'image'     => '' ,
         'pt_transaction_id' =>$result->transaction_id,
         'pt_invoice_id'=>$result->pt_invoice_id,
     ];

     // Check the uniqueness of the payment
     // if($post->id != NULL or $post->id != 0 ){
     //     $paymentmodel = \App\Models\Payment::where('post_id', $result->reference_no)
     //         ->where('user_id',$post->user_id)
     //         ->where('package_id', '6')
     //         ->where('payment_method_id', '3')
     //         ->first();
     // }
     // Save the payment
     $payment =!empty($paymentmodel)?$paymentmodel: new \App\Models\Payment($paymentInfo);
     $payment->save();
     if($package->id == '9' or $package->id == '11' or $package->id == '13' or $package->id == '15' or $package->id == '17' or $package->id == '21'  )
     {

         $mass=[];
         $contactForm=$data;


         if ($contactForm->service_type ==  t( 'maintenance_title') or $contactForm->service_type ==  "maintenance service"){

             if($contactForm->for_mainten == 'no'){
                 $maintenance_id=User::Where('id',$contactForm->maintenance_id)->first();
                 $car_place=City::Where('id',$contactForm->car_place)->first();
                 $mass[] = [
                     t('car_exist') => t('car_out') ,
                     t('Country') => !empty($contactForm->country_name)?$contactForm->country_name:'' ,
                     t('First Name') =>!empty($contactForm->first_name)?$contactForm->first_name:'' ,
                     t('Email') => !empty($contactForm->email)?$contactForm->email:'',
                     t('Owner ID') => !empty( $contactForm->owner_id)? $contactForm->owner_id:'',
                     t('maintenance_center') => !empty( $maintenance_id->name)? $maintenance_id->name:'',
                     t('car_place')  =>  !empty($car_place->name)?$car_place->name:'',
                     t('address')  =>  !empty($contactForm->address)?$contactForm->address:'',
                     t('plate number') => !empty($contactForm->plate_number)?$contactForm->plate_number:'' ,
                     t('serial number') =>  !empty($contactForm->serial_number)?$contactForm->serial_number:'',
                     t('Message') =>  !empty($contactForm->message)?$contactForm->message:'',
                 ];
             }else{
                 $maintenance_id=User::Where('id',$contactForm->maintenance_id_yes)->first();
                 $mass[] = [
                     t('car_exist') => t('car-in') ,
                     t('Country') => !empty($contactForm->country_name)?$contactForm->country_name:'' ,
                     t('First Name') =>!empty($contactForm->first_name)?$contactForm->first_name:'' ,
                     t('Email') => !empty($contactForm->email)?$contactForm->email:'',
                     t('maintenance_center') => !empty( $maintenance_id->name)? $maintenance_id->name:'',
                     t('Message') =>  !empty($contactForm->message)?$contactForm->message:'',
                     t('car url') => ' <a href="' . lurl( (!empty($contactForm->car_url) ? $contactForm->car_url:'SA')) . '">' . (!empty($contactForm->car_url) ? $contactForm->car_url:t('car_in')) . '</a>',
                 ];

             }

         }
         elseif ($contactForm->service_type == t( 'shipping_title')or $contactForm->service_type == "shipping service"){
             $shipping_id=User::Where('id',$contactForm->shipping_id)->first();
             $shipping_place=City::Where('id',$contactForm->shipping_place)->first();
             $shipping_place_to=City::Where('id',$contactForm->shipping_place_to)->first();

             if($contactForm->for_shipping == 'no'){
                 $mass[] = [
                     t('car_exist') => t('car_out') ,
                     t('Country') => !empty($contactForm->country_name)?$contactForm->country_name:'' ,
                     t('First Name') =>!empty($contactForm->first_name)?$contactForm->first_name:'' ,
                     t('Email') => !empty($contactForm->email)?$contactForm->email:'',
                     t('Owner ID') => !empty( $contactForm->owner_id)? $contactForm->owner_id:'',
                     t('Shipping_center') => !empty( $shipping_id->name)? $shipping_id->name:'',
                     t('shipping_place')  =>  !empty($shipping_place->name)?$shipping_place->name:'',
                     t('address')  =>  !empty($contactForm->address)?$contactForm->address:'',
                     t('shipping_place_to')   =>  !empty($shipping_place_to->name)?$shipping_place_to->name:'',
                     t('address_to_ditail')  =>  !empty($contactForm->address_to)?$contactForm->address_to:'',
                     t('plate number') => !empty($contactForm->plate_number)?$contactForm->plate_number:'' ,
                     t('serial number') =>  !empty($contactForm->serial_number)?$contactForm->serial_number:'',
                     t('Message') =>  !empty($contactForm->message)?$contactForm->message:'',


                 ];}else{
                 $mass[] = [
                     t('car_exist') => t('car-in') ,
                     t('Country') => !empty($contactForm->country_name)?$contactForm->country_name:'' ,
                     t('First Name') =>!empty($contactForm->first_name)?$contactForm->first_name:'' ,
                     t('Email') => !empty($contactForm->email)?$contactForm->email:'',
                     t('Shipping_center') => !empty( $shipping_id->name)? $shipping_id->name:'',
                     t('shipping_place')  =>  !empty($shipping_place->name)?$shipping_place->name:'',
                     t('shipping_place_to')   =>  !empty($shipping_place_to->name)?$shipping_place_to->name:'',
                     t('address')  =>  !empty($contactForm->address)?$contactForm->address:'',
                     t('address_to_ditail')  =>  !empty($contactForm->address_to)?$contactForm->address_to:'',
                     t('Message') =>  !empty($contactForm->message)?$contactForm->message:'',
                     t('car url') => ' <a href="' . lurl( (!empty($contactForm->car_url) ? $contactForm->car_url:'SA')) . '">' . (!empty($contactForm->car_url) ? $contactForm->car_url:t('car_in')) . '</a>',
                 ];
             }
         }
         elseif ($contactForm->service_type == t( 'checking_title') or $contactForm->service_type == "checking service"){

             $shipping_place=City::Where('id',$contactForm->car_place)->first();
             $mass[] = [
                 t('car_exist') => t('car-in') ,
                 t('Country') => !empty($contactForm->country_name)?$contactForm->country_name:'' ,
                 t('First Name') =>!empty($contactForm->first_name)?$contactForm->first_name:'' ,
                 t('Email') => !empty($contactForm->email)?$contactForm->email:'',
                 t('Phone') =>  !empty($contactForm->phone)?$contactForm->phone:'',
                 t('car_place')  =>  !empty($shipping_place->name)?$shipping_place->name:'',
                 t('address')  =>  !empty($contactForm->address)?$contactForm->address:'',
                 t('checking_date')  =>  !empty($contactForm->checking_date)?$contactForm->checking_date:'',
                 t('checking_time')  =>  !empty($contactForm->checking_time)?$contactForm->checking_time:'',
                 t('Message') =>  !empty($contactForm->message)?$contactForm->message:'',
                 t('car url') => ' <a href="' . lurl( (!empty($contactForm->car_url) ? $contactForm->car_url:'SA')) . '">' . (!empty($contactForm->car_url) ? $contactForm->car_url:t('car_in')) . '</a>',

             ];}
         elseif ($contactForm->service_type == t( 'mogaz service') or $contactForm->service_type == 'mogaz service') {
             if($contactForm->for_mogaz == 'no'){

                 $mass[] = [
                     t('car_exist') => t('car-in') ,
                     t('Country') => !empty($contactForm->country_name)?$contactForm->country_name:'' ,
                     t('First Name') =>!empty($contactForm->first_name)?$contactForm->first_name:'' ,
                     t('Email') => !empty($contactForm->email)?$contactForm->email:'',
                     t('Owner ID') => !empty( $contactForm->owner_id)? $contactForm->owner_id:'',
                     t('plate number') => !empty($contactForm->plate_number)?$contactForm->plate_number:'' ,
                     t('serial number') =>  !empty($contactForm->serial_number)?$contactForm->serial_number:'',
                     t('Message') =>  !empty($contactForm->message)?$contactForm->message:'',
                     t('car url') => ' <a href="' . lurl( (!empty($contactForm->car_url) ? $contactForm->car_url:'SA')) . '">' . (!empty($contactForm->car_url) ? $contactForm->car_url:t('car_in')) . '</a>',

                 ];
             }else{
                 $mass[] = [
                     t('car_exist') => t('car-in') ,
                     t('Country') => !empty($contactForm->country_name)?$contactForm->country_name:'' ,
                     t('First Name') =>!empty($contactForm->first_name)?$contactForm->first_name:'' ,
                     t('Email') => !empty($contactForm->email)?$contactForm->email:'',
                     t('Message') =>  !empty($contactForm->message)?$contactForm->message:'',
                     t('car url') => ' <a href="' . lurl( (!empty($contactForm->car_url) ? $contactForm->car_url:'SA')) . '">' . (!empty($contactForm->car_url) ? $contactForm->car_url:t('car_in')) . '</a>',

                 ];
             }
         }
         elseif ($contactForm->service_type == t( 'estimation title') or $contactForm->service_type ==  'estimation service' ){
             $mass[] = [
                 t('car_exist') => t('car_out') ,
                 t('Country') => !empty($contactForm->country_name)?$contactForm->country_name:'' ,
                 t('First Name') =>!empty($contactForm->first_name)?$contactForm->first_name:'' ,
                 t('Email') => !empty($contactForm->email)?$contactForm->email:'',
                 t('First Owner\'s Name') =>(!empty($contactForm->first_owner_name) ? $contactForm->first_owner_name : ""),
                 t('Middle Owner\'s Name') =>!empty($contactForm->middle_owner_name) ? $contactForm->middle_owner_name: "",
                 t('Last owner\'s name') =>  !empty($contactForm->last_owner_name) ? $contactForm->last_owner_name : "",
                 t('Mobile_number') =>  !empty($contactForm->Mobile_number) ? $contactForm->Mobile_number : "",
                 t('Car Type') => !empty( $contactForm->car_type)? $contactForm->car_type:'',
                 t('Car category') => !empty($contactForm->car_category)?$contactForm->car_category:'' ,
                 t('Car brand') =>  !empty($contactForm->car_brand)?$contactForm->car_brand:'',
                 t('Year of manufacture') =>  !empty($contactForm->Year_manufacture)?$contactForm->Year_manufacture:'',
                 t('Kilometers_car') =>  !empty($contactForm->Kilometers)?$contactForm->Kilometers:'',
                 t('Notes') =>  !empty($contactForm->message)?$contactForm->message:'',

             ];
         }
         elseif ($contactForm->service_type == t( 'ownership_title') or $contactForm->service_type == "ownership service"  ){

             $exhibitions_id=user::Where('id',$contactForm->exhibitions_id)->first();
             $exhibitions_place=City::Where('id',$contactForm->exhibitions_place)->first();
             if($contactForm->for_ownership == 'no'){
                 $mass[] = [
                     t('car_exist') => t('car_out') ,
                     t('Country') => !empty($contactForm->country_name)?$contactForm->country_name:'' ,
                     t('First Name') =>!empty($contactForm->first_name)?$contactForm->first_name:'' ,
                     t('Email') => !empty($contactForm->email)?$contactForm->email:'',
                     t('seller name') => !empty($contactForm->seller_name)?$contactForm->seller_name:'' ,
                     t('seller ID') => !empty( $contactForm->owner_id)? $contactForm->owner_id:'',
                     t('seller phone') => !empty($contactForm->seller_phone)?$contactForm->seller_phone:'' ,
                     t('purchaser name') =>  !empty($contactForm->purchaser_name)?$contactForm->purchaser_name:'',
                     t('purchaser ID') =>  !empty($contactForm->user_id)?$contactForm->user_id:'',
                     t('purchaser phone') =>  !empty($contactForm->purchaser_phone)?$contactForm->purchaser_phone:'',
                     t('exhibition_name') => !empty( $exhibitions_id->name)? $exhibitions_id->name:'',
                     t('exhibitions_location')  =>  !empty($exhibitions_place->name)?$exhibitions_place->name:'',
                     t('Kilometers_car') => !empty($contactForm->Kilometers)?$contactForm->Kilometers:'' ,
                     t('car price') =>  !empty($contactForm->price)?$contactForm->price:'',
                     t('Message') =>  !empty($contactForm->message)?$contactForm->message:'',
                 ];
             }else{
                 $mass[] = [
                     t('car_exist') => t('car-in') ,
                     t('Country') => !empty($contactForm->country_name)?$contactForm->country_name:'' ,
                     t('First Name') =>!empty($contactForm->first_name)?$contactForm->first_name:'' ,
                     t('Email') => !empty($contactForm->email)?$contactForm->email:'',
                     t('seller ID') => !empty( $contactForm->owner_id)? $contactForm->owner_id:'',
                     t('seller phone') => !empty($contactForm->seller_phone)?$contactForm->seller_phone:'' ,
                     t('purchaser name') =>  !empty($contactForm->purchaser_name)?$contactForm->purchaser_name:'',
                     t('purchaser ID') =>  !empty($contactForm->user_id)?$contactForm->user_id:'',
                     t('purchaser phone') =>  !empty($contactForm->purchaser_phone)?$contactForm->purchaser_phone:'',
                     t('Kilometers_car') => !empty($contactForm->Kilometers)?$contactForm->Kilometers:'' ,
                     t('car price') =>  !empty($contactForm->price)?$contactForm->price:'',
                     t('Message') =>  !empty($contactForm->message)?$contactForm->message:'',
                     t('car url') => ' <a href="' . lurl( (!empty($contactForm->car_url) ? $contactForm->car_url:'SA')) . '">' . (!empty($contactForm->car_url) ? $contactForm->car_url:t('car_in')) . '</a>',

                 ];
             }
         }

         for ($i = 0; $i < count($admins); $i++) {
             $message = new Message();

             $message->post_id = '0';
             $message->from_user_id = !empty($user) ? $user->id : 0;
             $message->to_user_id =  !empty($admins['0']['id'])?$admins['0']['id']:'' ;
             $message->to_name = !empty($admins['0']['name'])?$admins['0']['name']:'';
             $message->to_email = !empty($admins['0']['email'])?$admins['0']['email']:'';
             $message->to_phone = !empty($admins['0']['phone'])?$admins['0']['phone']:'';
             $message->from_name =!empty($user)? $user->name : 0;
             $message->from_email = !empty($user) ? $user->email : 0;
             $message->from_phone = !empty($user) ? $user->phone : 0;
             $message->id_code = $contactForm->id_code ;
             $message->subject = !empty($contactForm->service_type)?$contactForm->service_type:'';
             $message->message = json_encode($mass);
             if ($contactForm->service_type == t( 'ownership_title')  or $contactForm->service_type == "ownership service" ){
                 if($contactForm->for_ownership == 'no'){

                     $driving_license_image=  ImageService::where('token',$contactForm->id_code)->where('image_title', 'driving_license_image')->first();
                     $purchaser_id_image= ImageService::where('token',$contactForm->id_code)->where('image_title', 'purchaser_id_image')->first();
                     $seller_id_image=   ImageService::where('token',$contactForm->id_code)->where('image_title', 'seller_id_image')->first();
                     $image_car_arr = ImageService::where('token',$contactForm->id_code)->where('image_title', 'car_Pictures')->first();
                     $message->image_car_arr=!empty($image_car_arr->image_code)? $image_car_arr->image_code:'';
                     $message->image_driving_license=!empty($driving_license_image->image_code)?$driving_license_image->image_code:'';
                     $message->image_purchaser_id=!empty($purchaser_id_image->image_code)?$purchaser_id_image->image_code:'';
                     $message->image_seller_id=!empty($seller_id_image->image_code)?$seller_id_image->image_code:'';

                 }else{
                     $driving_license_image=  ImageService::where('token',$contactForm->id_code)->where('image_title', 'driving_license_image')->first();
                     $purchaser_id_image= ImageService::where('token',$contactForm->id_code)->where('image_title', 'purchaser_id_image')->first();
                     $message->image_driving_license=!empty($driving_license_image->image_code)?$driving_license_image->image_code:'';
                     $message->image_purchaser_id=!empty($purchaser_id_image->image_code)?$purchaser_id_image->image_code:'';

                 }

             }

             if  ($contactForm->service_type == t( 'shipping_title') or $contactForm->service_type == "shipping service"){

//                $image_car_arr=Session::get('filename_car_Pictures_arr')[0];


                 $image_car_arr = ImageService::where('token',$contactForm->id_code)->where('image_title', 'car_Pictures')->first();
                 $message->image_car_arr=!empty($image_car_arr->image_code)? $image_car_arr->image_code:'';


             }

             if  ($contactForm->service_type == t( 'estimation title')  or $contactForm->service_type ==  'estimation service' ){

                 $image_car_arr = ImageService::where('token',$contactForm->id_code)->first();

                 $message->image_car_arr=!empty($image_car_arr->image_code)? $image_car_arr->image_code:'';


             }
         }



         // Save
         $message->save();

         // Save and Send user's resume


         if ($message->subject == t( 'shipping_title') or $contactForm->service_type == "shipping service")
         {

             $messageshipping = new Message();
             $adminsshipping = User::where('id',$contactForm->shipping_id)->get();
             $messageshipping->post_id = '0';
             $messageshipping->from_user_id =!empty($user) ? $user->id : 0;
             $messageshipping->to_user_id =  !empty($adminsshipping['0']['id'])?$adminsshipping['0']['id']:'' ;
             $messageshipping->to_name = !empty($adminsshipping['0']['name'])?$adminsshipping['0']['name']:'';
             $messageshipping->to_email = !empty($adminsshipping['0']['email'])?$adminsshipping['0']['email']:'';
             $messageshipping->to_phone = !empty($adminsshipping['0']['phone'])?$adminsshipping['0']['phone']:'';
             $messageshipping->from_name =!empty($user)? $user->name: 0;
             $messageshipping->from_email = !empty($user)? $user->email : 0;
             $messageshipping->from_phone = !empty($user) ? $user->phone : 0;
             $messageshipping->id_code = $contactForm->id_code ;
             $messageshipping->subject = t( 'shipping_title') ;
             $messageshipping->message = json_encode($mass);
             if  ($contactForm->service_type == t( 'shipping_title') or $contactForm->service_type == "shipping service"){

                 $image_car_arr = ImageService::where('token',$contactForm->id_code)->where('image_title', 'car_Pictures')->first();
                 $messageshipping->image_car_arr=!empty($image_car_arr->image_code)? $image_car_arr->image_code:'';


             }
             // Save
             $messageshipping->save();
         }
         if ($message->subject ==  t( 'maintenance_title') or $contactForm->service_type ==  "maintenance service" )
         {
             $messagemaintenance = new Message();
             if($contactForm->for_mainten == 'no'){
                 $adminsmaintenance = User::where('id',$contactForm->maintenance_id)->get();
             }else{
                 $adminsmaintenance = User::where('id',$contactForm->maintenance_id_yes)->get();
             }


             $messagemaintenance->post_id = '0';
             $messagemaintenance->from_user_id =!empty($user) ? $user->id : 0;
             $messagemaintenance->to_user_id =  !empty($adminsmaintenance['0']['id'])?$adminsmaintenance['0']['id']:'' ;
             $messagemaintenance->to_name = !empty($adminsmaintenance['0']['name'])?$adminsmaintenance['0']['name']:'';
             $messagemaintenance->to_email = !empty($adminsmaintenance['0']['email'])?$adminsmaintenance['0']['email']:'';
             $messagemaintenance->to_phone = !empty($adminsmaintenance['0']['phone'])?$adminsmaintenance['0']['phone']:'';
             $messagemaintenance->from_name =!empty($user) ? $user->name : 0;
             $messagemaintenance->from_email = !empty($user) ? $user->email : 0;
             $messagemaintenance->from_phone = !empty($user) ? $user->phone : 0;
             $messagemaintenance->id_code = $contactForm->id_code ;
             $messagemaintenance->subject = t( 'maintenance_title') ;
             $messagemaintenance->message = json_encode($mass);

             // Save
             $messagemaintenance->save();
         }

         if ($message->subject == t( 'ownership_title')   or $contactForm->service_type == "ownership service")
         {
             $messagemaintenance = new Message();
             if($contactForm->for_ownership == 'no') {

                 $adminsexhibitions= User::where('id',$contactForm->exhibitions_id)->get();



                 $messagemaintenance->post_id = '0';
                 $messagemaintenance->from_user_id =!empty($user) ? $user->id : 0;
                 $messagemaintenance->to_user_id =  !empty($adminsexhibitions['0']['id'])?$adminsexhibitions['0']['id']:'' ;
                 $messagemaintenance->to_name = !empty($adminsexhibitions['0']['name'])?$adminsexhibitions['0']['name']:'';
                 $messagemaintenance->to_email = !empty($adminsexhibitions['0']['email'])?$adminsexhibitions['0']['email']:'';
                 $messagemaintenance->to_phone = !empty($adminsexhibitions['0']['phone'])?$adminsexhibitions['0']['phone']:'';
                 $messagemaintenance->from_name =!empty($user)  ? $user->name : 0;
                 $messagemaintenance->from_email = !empty($user) ? $user->email : 0;
                 $messagemaintenance->from_phone = !empty($user)  ? $user->phone : 0;
                 $messagemaintenance->id_code = $contactForm->id_code ;
                 $messagemaintenance->subject = t( 'ownership_title');
                 $messagemaintenance->message = json_encode($mass);
                 if ($contactForm->service_type == t( 'ownership_title')   or $contactForm->service_type == "ownership service" ){
                     if($contactForm->for_ownership == 'no'){

                         $driving_license_image=  ImageService::where('token',$contactForm->id_code)->where('image_title', 'driving_license_image')->first();
                         $purchaser_id_image= ImageService::where('token',$contactForm->id_code)->where('image_title', 'purchaser_id_image')->first();
                         $seller_id_image=   ImageService::where('token',$contactForm->id_code)->where('image_title', 'seller_id_image')->first();
                         $image_car_arr = ImageService::where('token',$contactForm->id_code)->where('image_title', 'car_Pictures')->first();
                         $messagemaintenance->image_car_arr=!empty($image_car_arr->image_code)? $image_car_arr->image_code:'';
                         $messagemaintenance->image_driving_license=!empty($driving_license_image->image_code)?$driving_license_image->image_code:'';
                         $messagemaintenance->image_purchaser_id=!empty($purchaser_id_image->image_code)?$purchaser_id_image->image_code:'';
                         $messagemaintenance->image_seller_id=!empty($seller_id_image->image_code)?$seller_id_image->image_code:'';

                     }else{
                         $driving_license_image=  ImageService::where('token',$contactForm->id_code)->where('image_title', 'driving_license_image')->first();
                         $purchaser_id_image= ImageService::where('token',$contactForm->id_code)->where('image_title', 'purchaser_id_image')->first();
                         $messagemaintenance->image_driving_license=!empty($driving_license_image->image_code)?$driving_license_image->image_code:'';
                         $messagemaintenance->image_purchaser_id=!empty($purchaser_id_image->image_code)?$purchaser_id_image->image_code:'';

                     }

                 }
                 // Save
                 $messagemaintenance->save();
             }
         }





         if ($contactForm->service_type == t( 'checking_title') or $contactForm->service_type == "checking service"){
             $post_car= explode("/",$contactForm->car_url);
             $post_car_get = Post::where('id',$post_car[4])->first();
             $user_car=User::where('id',$post_car_get->user_id)->first();
             $message = new Message();

             $message->post_id = '0';
             $message->from_user_id = !empty($user) ? $user->id : 0;
             $message->to_user_id =  !empty($user_car)?$user_car->id:'' ;
             $message->to_name = !empty($user_car)?$user_car->name:'';
             $message->to_email = !empty($user_car)?$user_car->email:'';
             $message->to_phone = !empty($user_car)?$user_car->phone:'';
             $message->from_name =!empty($user)? $user->name : 0;
             $message->from_email = !empty($user) ? $user->email : 0;
             $message->from_phone = !empty($user) ? $user->phone : 0;
             $message->id_code = $contactForm->id_code ;
             $message->subject = !empty($contactForm->service_type)?$contactForm->service_type:'';
             $message->message = json_encode($mass);
             $message->save();
         }
         if ($message->subject ==  t( 'maintenance_title') or $contactForm->service_type ==  "maintenance service" ){
             if($contactForm->for_mainten == 'yes'){
                 $post_car= explode("/",$contactForm->car_url);
                 $post_car_get = Post::where('id',$post_car[4])->first();
                 $user_car=User::where('id',$post_car_get->user_id)->first();
                 $message = new Message();

                 $message->post_id = '0';
                 $message->from_user_id = !empty($user) ? $user->id : 0;
                 $message->to_user_id =  !empty($user_car)?$user_car->id:'' ;
                 $message->to_name = !empty($user_car)?$user_car->name:'';
                 $message->to_email = !empty($user_car)?$user_car->email:'';
                 $message->to_phone = !empty($user_car)?$user_car->phone:'';
                 $message->from_name =!empty($user)? $user->name : 0;
                 $message->from_email = !empty($user) ? $user->email : 0;
                 $message->from_phone = !empty($user) ? $user->phone : 0;
                 $message->id_code = $contactForm->id_code ;
                 $message->subject = !empty($contactForm->service_type)?$contactForm->service_type:'';
                 $message->message = json_encode($mass);
                 $message->save();
             }
         }
         if ($contactForm->service_type == t( 'ownership_title')  or $contactForm->service_type == "ownership service" ){
             if($contactForm->for_ownership == 'yes'){
                 $message = new Message();
                 $post_car= explode("/",$contactForm->car_url);
                 $post_car_get = Post::where('id',$post_car[4])->first();
                 $user_car=User::where('id',$post_car_get->user_id)->first();
                 $message->post_id = '0';
                 $message->from_user_id = !empty($user) ? $user->id : 0;
                 $message->to_user_id =  !empty($user_car)?$user_car->id:'' ;
                 $message->to_name = !empty($user_car)?$user_car->name:'';
                 $message->to_email = !empty($user_car)?$user_car->email:'';
                 $message->to_phone = !empty($user_car)?$user_car->phone:'';
                 $message->from_name =!empty($user)? $user->name : 0;
                 $message->from_email = !empty($user) ? $user->email : 0;
                 $message->from_phone = !empty($user) ? $user->phone : 0;
                 $message->id_code = $contactForm->id_code ;
                 $message->subject = !empty($contactForm->service_type)?$contactForm->service_type:'';
                 $message->message = json_encode($mass);
                 $driving_license_image=  ImageService::where('token',$contactForm->id_code)->where('image_title', 'driving_license_image')->first();
                 $purchaser_id_image= ImageService::where('token',$contactForm->id_code)->where('image_title', 'purchaser_id_image')->first();
                 $message->image_driving_license=!empty($driving_license_image->image_code)?$driving_license_image->image_code:'';
                 $message->image_purchaser_id=!empty($purchaser_id_image->image_code)?$purchaser_id_image->image_code:'';
                 $message->save();
             }}
         if ($contactForm->service_type == t( 'shipping_title')  or $contactForm->service_type == "shipping service" ){
             if($contactForm->for_shipping == 'yes') {
                 $message = new Message();
                 $post_car = explode("/", $contactForm->car_url);
                 $post_car_get = Post::where('id', $post_car[4])->first();
                 $user_car = User::where('id', $post_car_get->user_id)->first();
                 $message->post_id = '0';
                 $message->from_user_id = !empty($user) ? $user->id : 0;
                 $message->to_user_id = !empty($user_car) ? $user_car->id : '';
                 $message->to_name = !empty($user_car) ? $user_car->name : '';
                 $message->to_email = !empty($user_car) ? $user_car->email : '';
                 $message->to_phone = !empty($user_car) ? $user_car->phone : '';
                 $message->from_name = !empty($user) ? $user->name : 0;
                 $message->from_email = !empty($user) ? $user->email : 0;
                 $message->from_phone = !empty($user) ? $user->phone : 0;
                 $message->id_code = $contactForm->id_code;
                 $message->subject = !empty($contactForm->service_type) ? $contactForm->service_type : '';
                 $message->message = json_encode($mass);
                 $image_car_arr = ImageService::where('token', $contactForm->id_code)->where('image_title', 'car_Pictures')->first();
                 $message->image_car_arr = !empty($image_car_arr->image_code) ? $image_car_arr->image_code : '';
                 $message->save();
             }

         }
         if ($contactForm->service_type == t( 'mogaz service') or $contactForm->service_type == 'mogaz service') {
             if($contactForm->for_mogaz == 'yes'){
                 $post_car= explode("/",$contactForm->car_url);
                 $post_car_get = Post::where('id',$post_car[4])->first();
                 $user_car=User::where('id',$post_car_get->user_id)->first();
                 $message = new Message();
                 $message->post_id = '0';
                 $message->from_user_id = !empty($user) ? $user->id : 0;
                 $message->to_user_id = !empty($user_car) ? $user_car->id : '';
                 $message->to_name = !empty($user_car) ? $user_car->name : '';
                 $message->to_email = !empty($user_car) ? $user_car->email : '';
                 $message->to_phone = !empty($user_car) ? $user_car->phone : '';
                 $message->from_name = !empty($user) ? $user->name : 0;
                 $message->from_email = !empty($user) ? $user->email : 0;
                 $message->from_phone = !empty($user) ? $user->phone : 0;
                 $message->id_code = $contactForm->id_code;
                 $message->subject = !empty($contactForm->service_type) ? $contactForm->service_type : '';
                 $message->message = json_encode($mass);
                 $message->save();
             }
         }


         $user_service= auth()->user();



         try {
           
 
                 $user_order_servce = User::where('id',$data->card_user_id)->get();
                 Notification::send($user_order_servce, new User_Mail($data));
                 Notification::send($admins, new FormSent($data));

                 if ($contactForm->service_type ==  t( 'maintenance_title') or $contactForm->service_type ==  "maintenance service" ){
                     if($contactForm->for_mainten == 'no'){
                         $adminsmaintenance = User::where('id',$contactForm->maintenance_id)->get();
                         Notification::send($adminsmaintenance, new FormSent($data));
                     }else{
                         $adminsmaintenance = User::where('id',$contactForm->maintenance_id_yes)->get();
                         Notification::send($adminsmaintenance, new FormSent($data));
                         $post_car= explode("/",$contactForm->car_url);
                         $post_car_get = Post::where('id',$post_car[4])->first();
                         $user_car=User::where('id',$post_car_get->user_id)->first();
                         Notification::send($user_car, new FormSent($data));
                     }

                 }
                 if ($contactForm->service_type == t( 'ownership_title')  or $contactForm->service_type == "ownership service"){
                     if($contactForm->for_ownership == 'no') {

                         $adminsexhibitions= User::where('id',$contactForm->exhibitions_id)->get();
                         Notification::send($adminsexhibitions, new FormSent($data));
                     }else{
                         $post_car= explode("/",$contactForm->car_url);
                         $post_car_get = Post::where('id',$post_car[4])->first();
                         $user_car=User::where('id',$post_car_get->user_id)->first();
                         Notification::send($user_car, new FormSent($data));
                     }

                 }
                 if ($contactForm->service_type == t( 'shipping_title')  or $contactForm->service_type == "shipping service"  ){
                     $adminsshipping_id= User::where('id',$contactForm->shipping_id)->get();
                     Notification::send($adminsshipping_id, new FormSent($data));
                     if($contactForm->for_shipping == 'yes'){
                         $post_car= explode("/",$contactForm->car_url);
                         $post_car_get = Post::where('id',$post_car[4])->first();
                         $user_car=User::where('id',$post_car_get->user_id)->first();
                         Notification::send($user_car, new FormSent($data));
                     }
                 }

             if ($contactForm->service_type == t( 'checking_title') or $contactForm->service_type == "checking service"){
                 $post_car= explode("/",$contactForm->car_url);
                 $post_car_get = Post::where('id',$post_car[4])->first();
                 $user_car=User::where('id',$post_car_get->user_id)->first();
                 Notification::send($user_car, new FormSent($data));
             }
             if ($contactForm->service_type == t( 'mogaz service') or $contactForm->service_type == 'mogaz service') {
                 if($contactForm->for_mogaz == 'yes'){
                     $post_car= explode("/",$contactForm->car_url);
                     $post_car_get = Post::where('id',$post_car[4])->first();
                     $user_car=User::where('id',$post_car_get->user_id)->first();
                     Notification::send($user_car, new FormSent($data));
                 }
             }

//             if ($displayFlashMessage) {
//                 $message = t("An activation link has been sent to you to verify your email address.");
//                 flash($message)->success();
//             }
             flash(t("we will send confirmation of this servce by your email and by massages on Theqqa"))->success();
             session(['verificationEmailSent' => true]);
       
            //  return true;
         } catch (\Exception $e) {
             $message = changeWhiteSpace($e->getMessage());
             if (isFromAdminPanel()) {
                 Alert::error($message)->flash();
             } else {
                 flash($message)->error();
             }

         }}



    $nextStepUrl =config('app.locale') . '/';
   
    return redirect($nextStepUrl);
     
 }
    else{
               flash($result->result)->error();
    $nextStepUrl =config('app.locale') . '/';
    return redirect($nextStepUrl);
    }
    
        }
    public function verify_payment_booking_paytabs(Request $request)
    {


        require_once 'paytabs.php';

        $pt = new \App\Http\Controllers\API\paytabs(env('PAYTABS_EMAIL'), env('PAYTABS_SECRET_KEY'));
        $result = $pt->verify_payment($_POST['payment_reference']);

        if($result->response_code = 100){

            $service_data= ServicePaytabs::where('p_id', $result->pt_invoice_id) ->first();
            $data=json_decode($service_data->service_data);

            $user = User::where('id',$data->card_user_id)->first();
            $admins = User::permission(Permission::getStaffPermissions())->get();
            $package = \App\Models\Package::find(19);
            $paymentInfo = [

                'post_id'           => $result->reference_no,
                'user_id'           =>$data->card_user_id,
                'price'            =>  $data->post_booking_price,
                'package_id'        => 19,
                'payment_method_id' => '3',
                'transaction_id'    => t('Paytabs'),
                'active'               => 1,
                'date_service'      => $service_data->service_data,
                'image'     => '' ,
                'pt_transaction_id' =>$result->transaction_id,
                'pt_invoice_id'=>$result->pt_invoice_id,
            ];

            // Check the uniqueness of the payment
            // if($post->id != NULL or $post->id != 0 ){
            //     $paymentmodel = \App\Models\Payment::where('post_id', $result->reference_no)
            //         ->where('user_id',$post->user_id)
            //         ->where('package_id', '6')
            //         ->where('payment_method_id', '3')
            //         ->first();
            // }
            // Save the payment
            $payment =!empty($paymentmodel)?$paymentmodel: new \App\Models\Payment($paymentInfo);
            $payment->save();

            $post =Post::where('id',$result->reference_no)->first();


                // Get all admin users
                $admins = User::permission(Permission::getStaffPermissions())->get();
                $attr = ['slug' => slugify($post->title), 'id' => $post->id];

            $postUrl = ': <a href="' .lurl($post->uri, $attr) . '">' ;
                $package = Package::findTrans($payment->package_id);
                $paymentMethod = PaymentMethod::find($payment->payment_method_id);
                $message_deposit_foruser = new Message();
                $mass_deposit_foruser =[];
                $mass_deposit_foruser [] = [
                    t('postUrl')  => $postUrl,
                    t('packageName' )  => (!empty($package->short_name)) ? $package->short_name : $package->name,
                    t('amount' ) =>0.025 *$post->price,
                    t('currency')    =>$package->currency_code,
                    t('totalamount')   =>$post->price,
                    t('paymentMethodName') => $paymentMethod->display_name,
                ];

                $post_detail= Post::where('id',$post->id)->first();
                $user_post= User::where('id',$post_detail->user_id)->first();
                $message_deposit_foruser->post_id = $post->id;
                $message_deposit_foruser->from_user_id = !empty($user) ? $user->id : '';
                $message_deposit_foruser->to_user_id = !empty($user_post->id ) ? $user_post->id : '';
                $message_deposit_foruser->to_name = !empty($user_post->name ) ? $user_post->name : '';
                $message_deposit_foruser->to_email = !empty($user_post->email )? $user_post->email : '';
                $message_deposit_foruser->to_phone = !empty($user_post->phone ) ? $user_post->phone : '';
                $message_deposit_foruser->from_name = !empty($user) ? $user->name : '';
                $message_deposit_foruser->from_email = !empty($user) ? $user->email : '';

                $message_deposit_foruser->subject = t('Car Booking Deposit');
                $message_deposit_foruser->message = json_encode($mass_deposit_foruser );


                // Save
                $message_deposit_foruser->save();

                //for admin
                $package = Package::findTrans($payment->package_id);
                $paymentMethod = PaymentMethod::find($payment->payment_method_id);
                $message_deposit = new Message();
                $mass_deposit=[];
                $mass_deposit[] = [

                    t('postUrl')  => $postUrl,
                    t('packageName' )  => (!empty($package->short_name)) ? $package->short_name : $package->name,
                    t('amount' ) =>0.025 *$post->price,
                    t('currency')    =>$package->currency_code,
                    t('totalamount')   =>$post->price,
                    t('paymentMethodName') => $paymentMethod->display_name,
                ];


                for ($i = 0; $i < count($admins); $i++) {
                    $message_deposit->post_id = $post->id;
                    $message_deposit->from_user_id =  !empty($user) ? $user->id : 0;
                    $message_deposit->to_user_id = !empty($admins[$i]['id']) ? $admins[$i]['id'] : '';
                    $message_deposit->to_name = !empty($admins[$i]['name']) ? $admins[$i]['name'] : '';
                    $message_deposit->to_email = !empty($admins[$i]['email']) ? $admins[$i]['email'] : '';
                    $message_deposit->to_phone = !empty($admins[$i]['phone']) ? $admins[$i]['phone'] : '';
                    $message_deposit->from_name =  !empty($user) ? $user->name : 0;
                    $message_deposit->from_email =  !empty($user) ? $user->email : 0;
                    $message_deposit->subject = t('Car Booking Deposit');
                    $message_deposit->message = json_encode($mass_deposit);
                }

                // Save
                $message_deposit->save();

                // Send Confirmation Email
                try {

                        $post_detail= Post::where('id',$post->id)->first();
                        $user_post= User::where('id',$post_detail->user_id)->first();
                    $post->notify(new PaymentSent($payment, $post));
                    Notification::send($admins, new PaymentNotification($payment, $post,$user_post));

                    flash(t("we will send confirmation of this servce by your email and by massages on Theqqa"))->success();



                } catch (\Exception $e) {
                    $message = changeWhiteSpace($e->getMessage());
                    if (isFromAdminPanel()) {
                        Alert::error($message)->flash();
                    } else {
                        flash($message)->error();
                    }
                }


            $nextStepUrl =config('app.locale') . '/';

            return redirect($nextStepUrl);

        }
        else{
            flash($result->result)->error();
            $nextStepUrl =config('app.locale') . '/';
            return redirect($nextStepUrl);
        }

    }
}
