<?php

namespace App\Providers;

use App\Country;
use App\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(){
        //Check if app is demo?
        if (env('APP_DEMO') == true){
            //Set default country to US if demo
            if ( ! session('country')){
                $country = Country::whereCountryCode('US')->first();
                if ($country){
                    //Setting default country
                    //session(['country' => $country->toArray()]);
                }
            }
        }
        /**
         * Set dynamic configuration for third party services
         */
        $amazonS3Config = [
            'filesystems.disks.s3' =>
                [
                    'driver' => 's3',
                    'key' => get_option('amazon_key'),
                    'secret' => get_option('amazon_secret'),
                    'region' => get_option('amazon_region'),
                    'bucket' => get_option('bucket'),
                ]
        ];
        $facebookConfig = [
            'services.facebook' =>
                [
                    'client_id' => get_option('fb_app_id'),
                    'client_secret' => get_option('fb_app_secret'),
                    'redirect' => url('login/facebook-callback'),
                ]
        ];
        $googleConfig = [
            'services.google' =>
                [
                    'client_id' => get_option('google_client_id'),
                    'client_secret' => get_option('google_client_secret'),
                    'redirect' => url('login/google-callback'),
                ]
        ];
        $twitterConfig = [
            'services.twitter' =>
                [
                    'client_id' => get_option('twitter_consumer_key'),
                    'client_secret' => get_option('twitter_consumer_secret'),
                    'redirect' => url('login/twitter-callback'),
                ]
        ];
        config($amazonS3Config);
        config($facebookConfig);
        config($googleConfig);
        config($twitterConfig);

        view()->composer('*', function ($view) {
            $header_menu_pages = Post::whereStatus('1')->where('show_in_header_menu', 1)->get();
            $show_in_footer_menu = Post::whereStatus('1')->where('show_in_footer_menu', 1)->get();

            $enable_monetize = get_option('enable_monetize');
            $loggedUser = null;
            if (Auth::check()) {
                $loggedUser = Auth::user();
            }

            $current_lang = current_language();

            $view->with(['lUser' => $loggedUser, 'enable_monetize' => $enable_monetize, 'header_menu_pages' => $header_menu_pages, 'show_in_footer_menu' => $show_in_footer_menu, 'current_lang' => $current_lang] );
        });

        // set locale
        setLocale(LC_TIME, 'de_DE');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
