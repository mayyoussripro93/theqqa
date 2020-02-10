<?php
/**
 * Theqqa - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace App\Http\Controllers\API\Post;

use App\Http\Controllers\API\Auth\Traits\VerificationTrait;
use App\Http\Controllers\API\Post\Traits\CustomFieldTrait;
use App\Http\Requests\PostApiRequest;
use App\Models\PostType;
use App\Models\Category;
use App\Models\Package;
use App\Models\PaymentMethod;
use App\Http\Controllers\FrontController;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Helpers\Localization\Country as CountryLocalization;
use App\Http\Controllers\API\Post\Traits\EditTrait;

class EditController extends FrontController
{
    use EditTrait, VerificationTrait, CustomFieldTrait;

    public $data;
    public $msg = [];
    public $uri = [];

    /**
     * EditController constructor.
     */
    public function __construct()
    {
//        parent::__construct();

        // From Laravel 5.3.4 or above
        $this->middleware(function ($request, $next) {
            $this->commonQueries();

            return $next($request);
        });
    }

    /**
     * Common Queries
     */
    public function commonQueries()
    {
        // References
        $data = [];

        // Get Countries
        $data['countries'] = $this->countries = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
        $this->countries = $data['countries'];
        view()->share('countries', $data['countries']);

        // Get Categories
        $data['categories'] = Category::trans()->where('parent_id', 0)->with([
            'children' => function ($query) {
                $query->trans();
            },
        ])->orderBy('lft')->get();

        foreach ($data['categories'] as $category){
            $category->user_type = explode(',',$category->user_type);
        }
        view()->share('categories', $data['categories']);

        // Get Post Types
        $data['postTypes'] = PostType::trans()->get();
        foreach ( $data['postTypes']  as $posttype){
            $posttype->user_type_id = explode(',',$posttype->user_type_id);
        }
        view()->share('postTypes', $data['postTypes']);
    
        // Count Packages
        $data['countPackages'] = Package::trans()->applyCurrency()->count();
        view()->share('countPackages', $data['countPackages']);
    
        // Count Payment Methods
        $data['countPaymentMethods'] = $this->countPaymentMethods;
    
        // Save common's data
        $this->data = $data;


    }

    /**
     * Show the form the create a new ad post.
     *
     * @param $postId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getForm($postId)
    {

        return  $this->getUpdateForm($postId);

    }

    /**
     * Update ad post.
     *
     * @param $postId
     * @param PostRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postForm($postId, PostApiRequest $request)
    {

        return $this->postUpdateForm($postId, $request);
    }
}
