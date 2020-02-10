<?php
/**
 * Theqqa - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace App\Http\Controllers\API\Account;

use App\Http\Controllers\API\FrontController;
use App\Models\Post;
use App\Models\Message;
use App\Models\Payment;
use App\Models\SavedPost;
use App\Models\SavedSearch;
use App\Models\Scopes\VerifiedScope;
use App\Models\Scopes\ReviewedScope;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Helpers\Localization\Country as CountryLocalization;

abstract class AccountBaseController extends FrontController
{
    public $countries;
    public $myPosts;
    public $archivedPosts;
    public $favouritePosts;
    public $pendingPosts;
    public $conversations;
    public $transactions;

    /**
     * AccountBaseController constructor.
     */
    public function __construct()
    {
        parent::__construct();
		
        $this->middleware(function ($request, $next) {
            $this->leftMenuInfo();
            return $next($request);
        });
	
		view()->share('pagePath', '');
    }

    public function leftMenuInfo()
    {
    	// Get & Share Countries
        $this->countries = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
        view()->share('countries', $this->countries);
        
        // Share User Info
        view()->share('user', auth()->user());

        // My Posts
        $this->myPosts = Post::where('country_code','SA')
            ->where('user_id', auth()->user()->id)
            ->verified()
			->unarchived()
			->reviewed()
            ->with(['pictures', 'city', 'latestPayment' => function ($builder) { $builder->with(['package']); }])
            ->orderByDesc('id');
        view()->share('countMyPosts', $this->myPosts->count());

        // Archived Posts
        $this->archivedPosts = Post::where('country_code','SA')
            ->where('user_id', auth()->user()->id)
            ->archived()
            ->with(['pictures', 'city', 'latestPayment' => function ($builder) { $builder->with(['package']); }])
            ->orderByDesc('id');
        view()->share('countArchivedPosts', $this->archivedPosts->count());

        // Favourite Posts
        $this->favouritePosts = SavedPost::whereHas('post', function($query) {
                $query->where('country_code','SA');
            })
            ->where('user_id', auth()->user()->id)
            ->with(['post.pictures', 'post.city'])
            ->orderByDesc('id');
        view()->share('countFavouritePosts', $this->favouritePosts->count());

        // Pending Approval Posts
        $this->pendingPosts = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
            ->where('country_code','SA')
            ->where('user_id', auth()->user()->id)
            ->unverified()
            ->with(['pictures', 'city', 'latestPayment' => function ($builder) { $builder->with(['package']); }])
            ->orderByDesc('id');
        view()->share('countPendingPosts', $this->pendingPosts->count());

        // Save Search
        $savedSearch = SavedSearch::where('country_code','SA')
            ->where('user_id', auth()->user()->id)
            ->orderByDesc('id');
        view()->share('countSavedSearch', $savedSearch->count());
        
        // Conversations
		$this->conversations = Message::with('latestReply')
			->whereHas('post', function($query) {
				$query->where('country_code','SA');
			})
			->byUserId(auth()->user()->id)
			->where('parent_id', 0)
			->orderByDesc('id');
		view()->share('countConversations', $this->conversations->count());
		
		// Payments
		$this->transactions = Payment::where('user_id', auth()->user()->id)
			->with(['post', 'paymentMethod', 'package' => function ($builder) { $builder->with(['currency']); }])
			->orderByDesc('id');
		view()->share('countTransactions', $this->transactions->count());
    }
}
