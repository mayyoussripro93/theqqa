<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

// Registration Routes...


Route::post('register', 'API\Auth\RegisterController@register');
Route::get('register/city', 'API\Auth\RegisterController@showRegistrationForm');
Route::post('register/registershop', 'API\Auth\RegisterController@registershop');
Route::post('register/registerexhibition', 'API\Auth\RegisterController@register');
// Authentication Routes...
Route::post('login', 'API\Auth\LoginController@login');
Route::get('home/getSponsoredPosts', 'API\HomeController@getSponsoredPosts');
Route::get('home/getLatestPosts', 'API\HomeController@getLatestPosts');
Route::get('category', 'API\Search\CategoryController@index');
Route::get('category/{catSlug}/{subCatSlug}', 'API\Search\CategoryController@index');
Route::get('category/{catSlug}', 'API\Search\CategoryController@index');
Route::get('search', 'API\Search\SearchController@index');
Route::get('getcategory', 'API\HomeController@getCategories');
Route::get('getpackage', 'API\HomeController@getPackage');
Route::post('password/email/test', 'API\Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get("req/documents", 'API\PageController@ReqDocuments');
Route::get("post_details/{slug}/{id}", 'API\Post\DetailsController@index');
Route::get(LaravelLocalization::transRoute('routes.page'), 'API\PageController@indexApi');

    Route::post('/paytabs_payment', 'API\HomeController@create_paytabs');
      Route::post('/verify_payment_paytabs', 'API\Post\CreateController@verify_payment_paytabs');
        Route::post('/verify_payment_service_paytabs', 'API\PageController@verify_payment_service_paytabs');
Route::post('/verify_payment_booking_paytabs', 'API\PageController@verify_payment_booking_paytabs');
Route::middleware('auth:api')->group(function($router){


    Route::post('contactfor/{id}/booking_car', 'API\Post\PaymentController@booking_post_car');
    Route::get('contactfor/mogaz', 'API\PageController@contact_mogaz');
    Route::post('contactfor/mogaz', 'API\PageController@contactPost_mogaz');

    Route::get('contactfor/ownership', 'API\PageController@contact_ownership');
    Route::post('contactfor/ownership', 'API\PageController@contactPost_ownership');

    Route::get('contactfor/checking', 'API\PageController@contact_checking');
    Route::post('contactfor/checking', 'API\PageController@contactPost_checking');

    Route::get('contactfor/shipping', 'API\PageController@contact_shipping');
    Route::post('contactfor/shipping', 'API\PageController@contactPost_shipping');

    Route::get('contactfor/estimation', 'API\PageController@contact_estimation');
    Route::post('contactfor/estimation', 'API\PageController@contactPost_estimation');

    Route::get('contactfor/maintenance', 'API\PageController@contact_maintenance');
    Route::post('contactfor/maintenance', 'API\PageController@contactPost_maintenance');


    Route::get('countries/SA/posts/autocomplete', 'API\PageController@searchedposts');
    Route::group(['middleware' => 'auth'], function ($router) {
        $router->pattern('id', '[0-9]+');

    // Post
    Route::get('posts/create/{tmpToken?}', 'API\Post\CreateController@getForm');
    Route::post('posts/create','API\Post\CreateController@postForm');


    Route::get('posts/{id}/edit', 'API\Post\EditController@getForm');
    Route::post('posts/{id}/edit', 'API\Post\EditController@postForm');

    Route::get('all/posts', 'API\HomeController@getLatestPosts');
    });
    Route::get(LaravelLocalization::transRoute('routes.post'), 'API\Post\DetailsController@index');
    Route::get("love/{slug}/{id}", 'API\Post\DetailsController@lovepost');
    // Contact Post's Author
    Route::post('posts/{id}/contact', 'API\Post\DetailsController@sendMessage');

    // Send report abuse
    Route::get('posts/{id}/report', 'API\Post\ReportController@showReportForm');
    Route::post('posts/{id}/report', 'API\Post\ReportController@sendReport');

    // ACCOUNT
    Route::group(['middleware' => ['auth', 'banned.user', 'prevent.back.history']], function ($router) {
        $router->pattern('id', '[0-9]+');

        // Users
    Route::get('account', 'API\Account\EditController@index');
        Route::group(['middleware' => 'impersonate.protect'], function () {
    Route::put('account', 'API\Account\EditController@updateDetails');
    Route::put('account/settings', 'API\Account\EditController@updateSettings');
//        Route::put('account/preferences', 'EditController@updatePreferences');
    Route::post('account/{id}/photo', 'API\Account\EditController@updatePhoto');
    Route::post('account/{id}/photo/delete', 'API\Account\EditController@deletePhoto');

        });

    Route::get('account/saved-search', 'API\Account\PostsController@getSavedSearch');
    $router->pattern('pagePath', '(my-posts|archived|favourite|pending-approval|saved-search)+');
    Route::get('account/{pagePath}', 'API\Account\PostsController@getPage');
    Route::get('account/{pagePath}/{id}/repost', 'API\Account\PostsController@getArchivedPosts');
    Route::get('account/{pagePath}/{id}/delete', 'API\Account\PostsController@destroy');
    Route::post('account/{pagePath}/delete', 'API\Account\PostsController@destroy');

    // Conversations
    Route::get('account/conversations', 'API\Account\ConversationsController@index');
    Route::get('account/conversations/{id}/delete', 'API\Account\ConversationsController@destroy');
    Route::post('account/conversations/delete', 'API\Account\ConversationsController@destroy');
    Route::post('account/conversations/{id}/reply', 'API\Account\ConversationsController@reply');
    $router->pattern('msgId', '[0-9]+');
    Route::get('account/conversations/{id}/messages', 'API\Account\ConversationsController@messages');
    Route::get('account/conversations/{id}/messages/{msgId}/delete', 'API\Account\ConversationsController@destroyMessages');
    Route::post('account/conversations/{id}/messages/delete', 'API\AccountConversationsController@destroyMessages');

    // Transactions
    Route::get('account/transactions', 'API\Account\TransactionsController@index');



    });

    // AJAX
//    Route::group(['prefix' => 'ajax'], function ($router) {
        Route::get('countries/{countryCode}/admins/{adminType}', 'API\Ajax\LocationController@getAdmins');
        Route::get('countries/{countryCode}/admins/{adminType}/{adminCode}/cities', 'API\Ajax\LocationController@getCities');
        Route::get('countries/{countryCode}/cities/{id}', 'API\Ajax\LocationController@getSelectedCity');
        Route::post('countries/{countryCode}/cities/autocomplete', 'API\Ajax\LocationController@searchedCities');
        Route::post('countries/{countryCode}/admin1/cities', 'API\Ajax\LocationController@getAdmin1WithCities');
        Route::post('category/sub-categories', 'API\Ajax\CategoryController@getSubCategories');
        Route::post('category/custom-fields', 'API\Ajax\CategoryController@getCustomFields');
        Route::post('save/post', 'API\Ajax\PostController@savePost');
        Route::post('countries/{countryCode}/posts/autocomplete', 'API\Ajax\PostController@searchedposts');
        Route::post('save/search', 'API\Ajax\PostController@saveSearch');
        Route::post('post/phone', 'API\Ajax\PostController@getPhone');
        Route::post('post/pictures/reorder', 'API\Ajax\PostController@picturesReorder');
        Route::post('messages/check', 'API\Ajax\ConversationController@checkNewMessages');
//    });


});
