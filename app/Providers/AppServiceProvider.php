<?php

namespace App\Providers;

use App\Country;
use App\Post;
use Carbon\Carbon;
use App\Bid;
use App\Notification;
use App\Ad;
use App\Event;
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

        // loop through events there are finished, declare user a winner, close events and auctions in that event
        $events = Event::whereDate('auction_ends', '<=', Carbon::now())->where('status', '1')->get();

        if ($events->count() > 0) {
            foreach ($events as $event) {
                if (Carbon::parse($event->auction_ends)->isPast()) {
                    $auctions = $event->auctions()->where('status', '1')->get();

                    foreach ($auctions as $auction) {


                        if (Carbon::parse($auction->expired_at)->isPast()) {
                            // auction not sold (has not been bidded)
                            $auction->update(['status' => '4']);
                            
                            // won bid
                            $bid = $auction->bids()
                                            ->where('is_accepted', 0)
                                            ->orderBy('max_bid_amount', 'desc')
                                            ->orderBy('bid_amount', 'desc')
                                            ->first();

                            if ($bid) {
                                $bid->is_accepted = 1;
                                $bid->won_bid_amount = $bid->bid_amount;
                                $bid->save();

                                $wonUser = $bid->user;

                                $wonBidAmountWithTax = $bid->won_bid_amount + ($bid->won_bid_amount*7.7/100);

                                $notification = new Notification;
                                $notification->title = trans('app.you_won');
                                $notification->text = trans('app.won_and_bought_for', ['won_bid_amount' => themeqx_price($wonBidAmountWithTax)]);
                                $notification->url = url('auction/' . $auction->id);
                                $notification->date = Carbon::now();

                                $wonUser->notifications()->save($notification);

                                // activate notification bell
                                $wonUser->notification_bell = 1;
                                $wonUser->save();

                                // auction sold
                                $auction->update(['status' => '3']);
                            }
                        }
                    }

                    // check if all the articles inside the auction are expired 
                    $countArticles = $event->auctions()->where('status', '1')->whereDate('expired_at', '<=', Carbon::now())->count();

                    // close an event inside if all articles underneath him are finished
                    if ($countArticles < 1) {
                        $event->status = '3';
                        $event->save();
                    }

                    // $event->auctions()->update(['status' => '3']);
                }
            }
        }

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
