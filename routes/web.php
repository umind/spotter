<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/**
 * @project: oClassifieds
 * @website: https://themeqx.com
 */

Auth::routes();

// Route::get('installation', ['as' => 'installation', 'uses'=>'HomeController@installation']);
// Route::post('installation', [ 'uses'=>'HomeController@installationPost']);

// Event Controller
Route::get('/', ['as' => 'home', 'uses'=>'EventController@index']);
Route::group(['middleware' => 'only_admin_access'], function () {
    // Admin Events
    // Route::get('dashboard/my_events', ['as' => 'dashboard_events', 'uses'=>'EventController@myEvents']);
    Route::get('dashboard/my_events/create', ['as' => 'create_event', 'uses'=>'EventController@create']);
    Route::post('dashboard/my_events/store', ['as' => 'store_event', 'uses'=>'EventController@store']);
    Route::get('dashboard/my_events/edit/{event}', ['as' => 'edit_event', 'uses'=>'EventController@edit']);
    Route::get('dashboard/my_events/pending', ['as' => 'pending_events', 'uses'=>'EventController@pending']);
    Route::get('dashboard/my_events/active', ['as' => 'active_events', 'uses'=>'EventController@active']);
    Route::get('dashboard/my_events/closed', ['as' => 'closed_events', 'uses'=>'EventController@closed']);
    Route::get('dashboard/my_events/archived', ['as' => 'archived_events', 'uses'=>'EventController@archived']);
    Route::post('ajax/my_events/change_status', ['as' => 'change_event_status', 'uses'=>'EventController@changeStatus']);
    Route::post('dashboard/my_events/update/{event}', ['as' => 'update_event', 'uses'=>'EventController@update']);
    Route::post('dashboard/my_events/publish/{event}', ['as' => 'publish_event', 'uses'=>'EventController@publish']);
    Route::post('dashboard/my_events/delete', ['as' => 'delete_event', 'uses'=>'EventController@delete']);
    Route::get('dashboard/my_events/archive/{event}', ['as' => 'archive_event', 'uses'=>'EventController@archive']);

    Route::get('dashboard/users/edit/{user_id}', ['uses' => 'UserController@editUser']);
    Route::post('dashboard/users/delete/{id}', ['uses' => 'UserController@profileEditPost']);
});

Route::get('events/{event}', ['as' => 'single_event', 'uses'=>'EventController@show'])->middleware('auction_closed');

// Products
Route::get('products', ['as' => 'products', 'uses' => 'HomeController@index']);

// Language Switcher
// Route::get('LanguageSwitch/{lang}', ['as' => 'switch_language', 'uses'=>'HomeController@switchLang']);

//Account activating
Route::get('account/activating/{activation_code}', ['as' => 'email_activation_link', 'uses'=>'UserController@activatingAccount']);

//Listing page
Route::get('contact-us', ['as' => 'contact_us_page', 'uses'=>'HomeController@contactUs']);
Route::post('contact-us', ['uses'=>'HomeController@contactUsPost']);

Route::get('page/{slug}', ['as' => 'single_page', 'uses'=>'PostController@showPage']);

Route::get('category/{cat_id?}', ['uses'=>'CategoriesController@show'])->name('category');
// Route::get('countries/{country_code?}', ['uses'=>'LocationController@countriesListsPublic'])->name('countries');
// Route::get('set-country/{country_code}', ['uses'=>'LocationController@setCurrentCountry'])->name('set_country');

Route::get('searchCityJson', ['uses'=>'LocationController@searchCityJson'])->name('searchCityJson');

Route::get('search/{category_slug?}/{brand_slug?}', ['as' => 'search', 'uses'=>'AdsController@search']);
Route::get('search-redirect', ['as' => 'search_redirect', 'uses'=>'AdsController@searchRedirect']);

// Route::get('auctions-by-user/{id?}', ['as' => 'ads_by_user', 'uses'=>'AdsController@adsByUser']);

Route::get('auction/{id}/{slug?}', ['as' => 'single_ad', 'uses'=>'AdsController@singleAuction'])->middleware('auction_closed');
// Route::get('embedded/{slug}', ['as' => 'embedded_ad', 'uses'=>'AdsController@embeddedAd']);

Route::post('save-ad-as-favorite', ['as' => 'save_ad_as_favorite', 'uses'=>'UserController@saveAdAsFavorite']);
// Route::post('report-post', ['as' => 'report_ads_pos', 'uses'=>'AdsController@reportAds']);
Route::post('reply-by-email', ['as' => 'reply_by_email_post', 'uses'=>'UserController@replyByEmailPost']);
Route::post('post-comments/{id}', ['as' => 'post_comments', 'uses'=>'CommentController@postComments']);


// Route::get('apply_job', function (){
//     return redirect(route('home'));
// });

// Password reset routes...
Route::post('send-password-reset-link', ['as' => 'send_reset_link', 'uses'=>'Auth\PasswordController@postEmail']);
//Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
//Route::post('password/reset', ['as'=>'password_reset_post', 'uses'=>'Auth\PasswordController@postReset']);

Route::post('get-sub-category-by-category', ['as'=>'get_sub_category_by_category', 'uses' => 'AdsController@getSubCategoryByCategory']);
Route::post('get-brand-by-category', ['as'=>'get_brand_by_category', 'uses' => 'AdsController@getBrandByCategory']);
Route::post('get-category-info', ['as'=>'get_category_info', 'uses' => 'AdsController@getParentCategoryInfo']);
Route::post('get-state-by-country', ['as'=>'get_state_by_country', 'uses' => 'AdsController@getStateByCountry']);
Route::post('get-city-by-state', ['as'=>'get_city_by_state', 'uses' => 'AdsController@getCityByState']);
Route::get('get-event-time', ['as'=>'get_event_time', 'uses' => 'EventController@getEventTime']);
Route::post('switch/product-view', ['as'=>'switch_grid_list_view', 'uses' => 'AdsController@switchGridListView']);


// only admin can create auction
Route::group(['middleware' => 'only_admin_access'], function () {
    Route::get('post-new', ['as'=>'create_ad', 'uses' => 'AdsController@create']);
    Route::post('post-new', ['uses' => 'AdsController@store']);
});

//Post bid
Route::post('{id}/post-new', ['as' => 'post_bid','uses' => 'BidController@postBid']);
Route::post('{id}/post-max-bid', ['as' => 'post_max_bid','uses' => 'BidController@postMaxBid']);

//Checkout payment
// Route::get('checkout/{transaction_id}', ['as'=>'payment_checkout', 'uses' => 'PaymentController@checkout']);
// Route::post('checkout/{transaction_id}', ['uses' => 'PaymentController@chargePayment']);
//Payment success url
// Route::any('checkout/{transaction_id}/payment-success', ['as'=>'payment_success_url','uses' => 'PaymentController@paymentSuccess']);
// Route::any('checkout/{transaction_id}/paypal-notify', ['as'=>'paypal_notify_url','uses' => 'PaymentController@paypalNotify']);


// Route::group(['prefix'=>'login'], function(){
//     //Social login route

//     Route::get('facebook', ['as' => 'facebook_redirect', 'uses'=>'SocialLogin@redirectFacebook']);
//     Route::get('facebook-callback', ['as' => 'facebook_callback', 'uses'=>'SocialLogin@callbackFacebook']);

//     Route::get('google', ['as' => 'google_redirect', 'uses'=>'SocialLogin@redirectGoogle']);
//     Route::get('google-callback', ['as' => 'google_callback', 'uses'=>'SocialLogin@callbackGoogle']);

//     Route::get('twitter', ['as' => 'twitter_redirect', 'uses'=>'SocialLogin@redirectTwitter']);
//     Route::get('twitter-callback', ['as' => 'twitter_callback', 'uses'=>'SocialLogin@callbackTwitter']);
// });

Route::resource('user', 'UserController');

//Dashboard Route
Route::group(['prefix'=>'dashboard', 'middleware' => 'dashboard'], function(){
    Route::group(['middleware'=>'only_admin_access'], function(){

        Route::get('/', ['as'=>'dashboard', 'uses' => 'DashboardController@dashboard']);

        // Route::group(['prefix'=>'settings'], function(){
            // Route::get('theme-settings', ['as'=>'theme_settings', 'uses' => 'SettingsController@ThemeSettings']);
            // Route::get('modern-theme-settings', ['as'=>'modern_theme_settings', 'uses' => 'SettingsController@modernThemeSettings']);
            // Route::get('social-url-settings', ['as'=>'social_url_settings', 'uses' => 'SettingsController@SocialUrlSettings']);
            // Route::get('general', ['as'=>'general_settings', 'uses' => 'SettingsController@GeneralSettings']);
            // Route::get('payments', ['as'=>'payment_settings', 'uses' => 'SettingsController@PaymentSettings']);
            // Route::get('ad', ['as'=>'ad_settings', 'uses' => 'SettingsController@AdSettings']);
            // Route::get('languages', ['as'=>'language_settings', 'uses' => 'LanguageController@index']);
            // Route::post('languages', ['uses' => 'LanguageController@store']);
            // Route::post('languages-delete', ['as'=>'delete_language', 'uses' => 'LanguageController@destroy']);

            // Route::get('storage', ['as'=>'file_storage_settings', 'uses' => 'SettingsController@StorageSettings']);
            // Route::get('social', ['as'=>'social_settings', 'uses' => 'SettingsController@SocialSettings']);
            // Route::get('blog', ['as'=>'blog_settings', 'uses' => 'SettingsController@BlogSettings']);
            // Route::get('other', ['as'=>'other_settings', 'uses' => 'SettingsController@OtherSettings']);
            // Route::post('other', ['as'=>'other_settings', 'uses' => 'SettingsController@OtherSettingsPost']);

            // Route::get('recaptcha', ['as'=>'re_captcha_settings', 'uses' => 'SettingsController@reCaptchaSettings']);

            //Save settings / options
            // Route::post('save-settings', ['as'=>'save_settings', 'uses' => 'SettingsController@update']);
            // Route::get('monetization', ['as'=>'monetization', 'uses' => 'SettingsController@monetization']);
        // });

        // Route::group(['prefix'=>'location'], function(){
        //     Route::get('country', ['as'=>'country_list', 'uses' => 'LocationController@countries']);
        //     Route::get('country-data', ['as'=>'get_countries_data', 'uses' => 'LocationController@getCountriesData']);
        //     Route::get('states', ['as'=>'state_list', 'uses' => 'LocationController@stateList']);
        //     Route::post('states', [ 'uses' => 'LocationController@saveState']);
        //     Route::get('states/{id}/edit', ['as'=>'edit_state', 'uses' => 'LocationController@stateEdit']);
        //     Route::post('states/{id}/edit', ['uses' => 'LocationController@stateEditPost']);
        //     Route::post('states/delete', ['as'=>'delete_state', 'uses' => 'LocationController@stateDestroy']);
        //     Route::get('state-data', ['as'=>'get_state_data', 'uses' => 'LocationController@getStatesData']);
        //     Route::get('cities', ['as'=>'city_list', 'uses' => 'LocationController@cityList']);
        //     Route::post('cities', ['uses' => 'LocationController@saveCity']);
        //     Route::get('city-data', ['as'=>'get_city_data', 'uses' => 'LocationController@getCityData']);

        //     Route::get('cities/{id}/edit', ['as'=>'edit_city', 'uses' => 'LocationController@cityEdit']);
        //     Route::post('cities/{id}/edit', ['uses' => 'LocationController@cityEditPost']);
        //     Route::post('city/delete', ['as'=>'delete_city', 'uses' => 'LocationController@cityDestroy']);
        // });

        Route::group(['prefix'=>'categories'], function(){
            Route::get('/', ['as'=>'parent_categories', 'uses' => 'CategoriesController@index']);
            Route::post('/', ['uses' => 'CategoriesController@store']);

            Route::get('edit/{id}', ['as'=>'edit_categories', 'uses' => 'CategoriesController@edit']);
            Route::post('edit/{id}', ['uses' => 'CategoriesController@update']);

            Route::post('delete-categories', ['as'=>'delete_categories', 'uses' => 'CategoriesController@destroy']);
        });

        // Route::group(['prefix'=>'posts'], function(){
        //     Route::get('/', ['as'=>'posts', 'uses' => 'PostController@posts']);
        //     Route::get('data', ['as'=>'posts_data', 'uses' => 'PostController@postsData']);

        //     Route::get('create', ['as'=>'create_new_post', 'uses' => 'PostController@createPost']);
        //     Route::post('create', ['uses' => 'PostController@storePost']);
        //     Route::post('delete', ['as'=>'delete_post','uses' => 'PostController@destroyPost']);

        //     Route::get('edit/{slug}', ['as'=>'edit_post', 'uses' => 'PostController@editPost']);
        //     Route::post('edit/{slug}', ['uses' => 'PostController@updatePost']);
        // });

        Route::group(['prefix'=>'pages'], function(){
            Route::get('/', ['as'=>'pages', 'uses' => 'PostController@index']);
            Route::get('data', ['as'=>'pages_data', 'uses' => 'PostController@pagesData']);

            Route::get('create', ['as'=>'create_new_page', 'uses' => 'PostController@create']);
            Route::post('create', ['uses' => 'PostController@store']);
            Route::post('delete', ['as'=>'delete_page','uses' => 'PostController@destroy']);

            Route::get('edit/{slug}', ['as'=>'edit_page', 'uses' => 'PostController@edit']);
            Route::post('edit/{slug}', ['uses' => 'PostController@updatePage']);
        });
        Route::group(['prefix'=>'admin_comments'], function(){
            Route::get('/', ['as'=>'admin_comments', 'uses' => 'CommentController@index']);
            Route::get('data', ['as'=>'admin_comments_data', 'uses' => 'CommentController@commentData']);

            Route::post('action', ['as'=>'comment_action', 'uses' => 'CommentController@commentAction']);
        });

        // Route::get('approved', ['as'=>'approved_ads', 'uses' => 'AdsController@index']);
        Route::get('pending', ['as'=>'admin_pending_ads', 'uses' => 'AdsController@adminPendingAds']);
        Route::get('sold', ['as'=>'sold_ads', 'uses' => 'AdsController@soldAds']);
        Route::get('not-sold', ['as'=>'not_sold_ads', 'uses' => 'AdsController@notSoldAds']);
        Route::post('change-payment-status', ['as'=>'change_payment_status', 'uses' => 'AdsController@changePaymentStatus']);
        // Route::get('blocked', ['as'=>'admin_blocked_ads', 'uses' => 'AdsController@adminBlockedAds']);
        // Route::post('status-change', ['as'=>'ads_status_change', 'uses' => 'AdsController@adStatusChange']);



        // Route::get('ad-reports', ['as'=>'ad_reports', 'uses' => 'AdsController@reports']);
        Route::get('users', ['as'=>'users', 'uses' => 'UserController@index']);
        Route::get('users-data', ['as'=>'get_users_data', 'uses' => 'UserController@usersData']);
        Route::get('users-info/{id}', ['as'=>'user_info', 'uses' => 'UserController@userInfo']);
        Route::post('change-user-status', ['as'=>'change_user_status', 'uses' => 'UserController@changeStatus']);
        Route::post('change-user-feature', ['as'=>'change_user_feature', 'uses' => 'UserController@changeFeature']);
        // Route::post('delete-reports', ['as'=>'delete_report', 'uses' => 'AdsController@deleteReports']);

        Route::get('contact-messages', ['as'=>'contact_messages', 'uses' => 'HomeController@contactMessages']);
        Route::get('contact-messages-data', ['as'=>'contact_messages_data', 'uses' => 'HomeController@contactMessagesData']);


        Route::group(['prefix'=>'administrators'], function(){
            Route::get('/', ['as'=>'administrators', 'uses' => 'UserController@administrators']);
            Route::get('create', ['as'=>'add_administrator', 'uses' => 'UserController@addAdministrator']);
            Route::post('create', ['uses' => 'UserController@storeAdministrator']);

            Route::post('block-unblock', ['as'=>'administratorBlockUnblock','uses' => 'UserController@administratorBlockUnblock']);

        });


    });

    // only standard user access
    Route::get('finished_auctions', ['as'=>'finished_auctions', 'uses' => 'AdsController@finishedAuctions']);
    Route::get('active_bidding_auctions', ['as'=>'active_bidding_auctions', 'uses' => 'AdsController@activeBiddingAuctions']);
    Route::get('won_auctions', ['as'=>'won_auctions', 'uses' => 'AdsController@wonAuctions']);
    Route::get('favorite-lists', ['as'=>'favorite_ads', 'uses' => 'AdsController@favoriteAds']);

    // all user access

    Route::group(['prefix'=>'u'], function(){
        Route::group(['prefix'=>'posts'], function(){

            Route::group(['middleware' => 'only_admin_access'], function () {
                Route::get('/', ['as'=>'my_ads', 'uses' => 'AdsController@myAds']);
                Route::post('delete', ['as'=>'delete_ads', 'uses' => 'AdsController@destroy']);
                Route::get('edit/{id}', ['as'=>'edit_ad', 'uses' => 'AdsController@edit']);
                Route::post('edit/{id}', ['uses' => 'AdsController@update']);
                Route::get('my-lists', ['as'=>'my_ads', 'uses' => 'AdsController@myAds']);
                //Upload ads image
                Route::post('upload-a-image', ['as'=>'upload_ads_image', 'uses' => 'AdsController@uploadAdsImage']);
                Route::post('upload-post-image', ['as'=>'upload_post_image', 'uses' => 'PostController@uploadPostImage']);
                //Delete media
                Route::post('delete-media', ['as'=>'delete_media', 'uses' => 'AdsController@deleteMedia']);
                Route::post('feature-media-creating', ['as'=>'feature_media_creating_ads', 'uses' => 'AdsController@featureMediaCreatingAds']);
                Route::get('append-media-image', ['as'=>'append_media_image', 'uses' => 'AdsController@appendMediaImage']);
                Route::get('append-post-media-image', ['as'=>'append_post_media_image', 'uses' => 'PostController@appendPostMediaImage']);
                Route::get('pending-lists', ['as'=>'pending_ads', 'uses' => 'AdsController@pendingAds']);
                // Route::get('archive-lists', ['as'=>'favourite_ad', 'uses' => 'AdsController@create']);
                // Route::get('reports-by/{slug}', ['as'=>'reports_by_ads', 'uses' => 'AdsController@reportsByAds']);

                // bids
                Route::get('bids/{ad_id}', ['as'=>'auction_bids', 'uses' => 'BidController@index']);
                Route::post('bids/action', ['as'=>'bid_action', 'uses' => 'BidController@bidAction']);
                Route::get('bidder_info/{bid_id}', ['as'=>'bidder_info', 'uses' => 'BidController@bidderInfo']);
            });

            // all users access
            Route::get('profile', ['as'=>'profile', 'uses' => 'UserController@profile']);
            Route::get('profile/edit/{user_id}', ['as'=>'profile_edit', 'uses' => 'UserController@profileEdit']);
            Route::post('profile/edit/{user_id}', ['as'=>'update_user', 'uses' => 'UserController@profileEditPost']);
            Route::get('profile/delete/{user_id}', ['as'=>'delete_user', 'uses' => 'UserController@deleteUser']);
            Route::get('profile/block/{user_id}', ['as'=>'block_user', 'uses' => 'UserController@blockUser']);
            Route::get('profile/unblock/{user_id}', ['as'=>'unblock_user', 'uses' => 'UserController@unblockUser']);
            Route::get('profile/change-avatar', ['as'=>'change_avatar', 'uses' => 'UserController@changeAvatar']);
            Route::post('upload-avatar', ['as'=>'upload_avatar',  'uses' => 'UserController@uploadAvatar']);

            /**
             * Change Password route
             */
            Route::group(['prefix' => 'account'], function() {
                Route::get('change-password', ['as' => 'change_password', 'uses' => 'UserController@changePassword']);
                Route::post('change-password', 'UserController@changePasswordPost');
            });

        });
    });

    Route::group(['middleware' => 'web'], function () {
        Route::get('notifications/{notification}', ['as' => 'show_notification', 'uses' => 'NotificationController@show']);
        Route::post('notifications/ajax/remove-number', ['as' => 'remove_notification_number', 'uses' => 'NotificationController@removeNumber']);
    });

    Route::get('articles/search', ['as' => 'get_search_results', 'uses' => 'SearchController@getArticlesResults']);

    //Route::get('logout', ['as'=>'logout', 'uses' => 'DashboardController@logout']);
});

Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});