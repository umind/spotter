<?php

namespace App\Http\Controllers;

use App\Ad;
use App\Bid;
use App\Invoice;
use App\Jobs\SendArticleSoldMail;
use App\Jobs\SendAuctionWonMail;
use App\Mail\OverbiddingUsersBidMail;
use App\Notification;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;

class BidController extends Controller
{
    public function construct()
    {
        $this->middleware('auth');
    }

    public function index($ad_id){
        $user = Auth::user();
        $user_id = $user->id;
        $ad = Ad::find($ad_id);

        $title = trans('app.bids_for').' '.$ad->title;

        if (! $user->is_admin()){
            if ($ad->user_id != $user_id){
                return view('admin.error.error_404');
            }
        }
        return view('admin.bids', compact('title', 'ad'));
    }

    public function postBid(Request $request , $ad_id){
        if ( ! Auth::check()){
            return redirect(route('login'))->with('error', trans('app.login_first_to_post_bid'));
        }

        $user = Auth::user();
        $bid_amount = toFloat($request->bid_amount);

        $ad = Ad::findOrFail($ad_id);

        // check if expired
        if (Carbon::parse($ad->expired_at)->isPast()) {
            return back()->with('error', trans('app.bidding_time_expired'));
        }

        $bids = $ad->bids()->whereNull('max_bid_amount')->get();

        if ($bids->count() > 0) {
            $minimumBid = $ad->current_bid_plus_increaser();
        } else {
            $minimumBid = $ad->price;
        }

        $maxBidObj = $ad->bids()->whereNotNull('max_bid_amount')->first();
        $currentMaxBid = $maxBidObj ? $maxBidObj->max_bid_amount : NULL;
        $minimumMaxBid = $currentMaxBid ? $currentMaxBid + $ad->price_increaser : $minimumBid;

        if ($bid_amount < $minimumBid){
            return back()->with('error', sprintf(trans('app.enter_min_bid_amount'), themeqx_price($minimumBid)));
        }

        if ($bid_amount == $currentMaxBid) {
            return back()->with('error', trans('app.bid_same_as_max_bid'));
        }

        $currentHighestBid = $ad->bids()->whereNotNull('bid_amount')->orderBy('bid_amount', 'desc')->first();
        if ($currentHighestBid) {
            $userWithCurrentHighestBid = User::find($currentHighestBid->user_id);

            if ($userWithCurrentHighestBid->id == $user->id) {
                return back()->with('error', trans('app.cannot_bid_yourself'));
            }
        }

        $bid = new Bid;
        $bid->ad_id = $ad->id;
        $bid->user_id = $user->id;
        $bid->bid_amount = $bid_amount;
        $bid->is_accepted = 0;
        $bid->save();

        if ($maxBidObj && $currentMaxBid > $bid_amount) {

            $bid_amount += $ad->price_increaser;

            if ($maxBidObj->user->id != $user->id) {
                $bid = new Bid;
                $bid->ad_id = $ad->id;
                $bid->user_id = $maxBidObj->user_id;
                $bid->bid_amount = $bid_amount;
                $bid->is_accepted = 0;
                $bid->save();
            }
        }

        // sell item if bid is greater than buy now price, but ony if buy price is defined
        if ($ad->buy_now_price && $bid_amount >= $ad->buy_now_price) {

            $event = $ad->events->first();

            $bid->is_accepted = 1;
            $bid->won_bid_amount = $ad->buy_now_price;
            $bid->save();

            $wonUser = $bid->user;

            $wonBidAmountWithTax = $bid->won_bid_amount + ($bid->won_bid_amount*7.7/100);

            $notification = new Notification;
            $notification->title = trans('app.you_won');
            $notification->text = trans('app.won_and_bought_for', ['won_bid_amount' => themeqx_price($wonBidAmountWithTax)]);
            $notification->url = url('auction/' . $ad->id);
            $notification->date = Carbon::now();

            $wonUser->notifications()->save($notification);

            // activate notification bell
            $wonUser->notification_bell = 1;
            $wonUser->save();

            // ad sold
            $ad->update([
                'status' => '3', 
                'order' => 3,
                'expired_at' => Carbon::now(),
            ]);

            $countArticles = $event->auctions()
                                ->where('status', '1')
                                ->count();

            // close an event inside if all articles underneath him are finished
            if ($countArticles < 1) {
                $event->status = '2';
                $event->order = 3;
                $event->auction_ends = Carbon::now();
                $event->save();
            }

            // create invoice and send email
            $invoice = $ad->invoice()->save(new Invoice);
            dispatch(new SendAuctionWonMail($event, $ad, $bid, $wonUser));

            // inform admin that something has been bought
            dispatch(new SendArticleSoldMail($event, $ad, $bid, $wonUser));

            return back()->with('success', trans('app.bought_now_message'));
        } else if ($maxBidObj) {

            // send notification to the user that is now bidding (auth user)
            // or to the user that has biggest max bid if the bid amount is bigger that the biggest max big
            if ($bid_amount > $currentMaxBid) {
                $user = $maxBidObj->user;
            }

            $notification = new Notification;
            $notification->title = trans('app.overbidding_notification_title');
            $notification->text = trans('app.overbidding_message_notification', ['article_title' => $ad->title]);
            $notification->url = url('auction/' . $ad->id);
            $notification->date = Carbon::now();

            $user->notifications()->save($notification);

            // activate notification bell
            $user->notification_bell = 1;
            $user->save();

            if ($user->email_notifications == 1 && !$user->is_online) {
                Mail::to($user->email)->send(new OverbiddingUsersBidMail($ad));
            }

            return back()->with('warning', trans('app.bid_overbidded_warning_message'));

        } else if (isset($userWithCurrentHighestBid)) {
            $notification = new Notification;
            $notification->title = trans('app.overbidding_notification_title');
            $notification->text = trans('app.overbidding_message_notification', ['article_title' => $ad->title]);
            $notification->url = url('auction/' . $ad->id);
            $notification->date = Carbon::now();

            $userWithCurrentHighestBid->notifications()->save($notification);

            // activate notification bell
            $userWithCurrentHighestBid->notification_bell = 1;
            $userWithCurrentHighestBid->save();

            if ($userWithCurrentHighestBid->email_notifications == 1 && !$userWithCurrentHighestBid->is_online) {
                Mail::to($userWithCurrentHighestBid->email)->send(new OverbiddingUsersBidMail($ad));
            }

            return back()->with('warning', trans('app.bid_overbidded_warning_message'));
        }

        return back()->with('success', trans('app.your_bid_posted'));
    }

    public function postMaxBid(Request $request , $ad_id){
        if ( ! Auth::check()){
            return redirect(route('login'))->with('error', trans('app.login_first_to_post_bid'));
        }
        $user = Auth::user();
        $bid_amount = toFloat($request->max_bid_amount);
        $ad = Ad::find($ad_id);

        // check if expired
        if (Carbon::parse($ad->expired_at)->isPast()) {
            return back()->with('error', trans('app.bidding_time_expired'));
        }

        $bids = $ad->bids()->whereNotNull('max_bid_amount')->get();

        if ($bids->count() > 0) {
            $minimumBid = $ad->current_bid_plus_increaser();
        } else {
            $minimumBid = $ad->price;
        }

        $maxBidObj = $ad->bids()->whereNotNull('max_bid_amount')->first();
        $currentMaxBid = $maxBidObj ? $maxBidObj->max_bid_amount : NULL;
        $minimumMaxBid = $currentMaxBid ? $currentMaxBid + $ad->price_increaser : $minimumBid;

        if ($bid_amount < $minimumMaxBid){
            return back()->with('error', sprintf(trans('app.enter_min_bid_amount'), themeqx_price($minimumMaxBid)));
        }

        if ($maxBidObj) {
            $currentMaxBidObj = $maxBidObj;
            $maxBidObj->max_bid_amount = $bid_amount;
            if ($maxBidObj->user_id != $user->id) {
                $maxBidObj->user_id = $user->id;
            } else {
                $maxBidObj->user_id = $maxBidObj->user_id;
            }
            $maxBidObj->save();

            $currentHighestBid = $ad->bids()->whereNotNull('bid_amount')->orderBy('bid_amount', 'desc')->first();
            $currentUserThatHasHighestBid = $currentHighestBid->user;

            if ($currentUserThatHasHighestBid->id != $user->id) {
                $bid = new Bid;
                $bid->ad_id = $ad->id;
                $bid->user_id = $user->id;
                $bid->bid_amount = $minimumBid;
                $bid->is_accepted = 0;
                $bid->save();

                $notification = new Notification;
                $notification->title = trans('app.overbidding_notification_title');
                $notification->text = trans('app.overbidding_message_notification', ['article_title' => $ad->title]);
                $notification->url = url('auction/' . $ad->id);
                $notification->date = Carbon::now();

                $currentUserThatHasHighestBid->notifications()->save($notification);

                // activate notification bell
                $currentUserThatHasHighestBid->notification_bell = 1;
                $currentUserThatHasHighestBid->save();

                if ($currentUserThatHasHighestBid->email_notifications == 1 && !$currentUserThatHasHighestBid->is_online) {
                    Mail::to($currentUserThatHasHighestBid->email)->send(new OverbiddingUsersBidMail($ad));
                }

                return back()->with('warning', trans('app.bid_overbidded_warning_message'));
            }

            $currentUserThatHasMaxBid = $currentMaxBidObj->user;

            if ($currentUserThatHasMaxBid->id != $user->id) {
                if ($currentUserThatHasMaxBid->email_notifications == 1 && !$currentUserThatHasMaxBid->is_online) {
                    Mail::to($currentUserThatHasMaxBid->email)->send(new OverbiddingUsersBidMail($ad));
                }

                return back()->with('warning', trans('app.bid_overbidded_warning_message'));
            }
        } else {
            $bid = new Bid;
            $bid->ad_id = $ad->id;
            $bid->user_id = $user->id;
            $bid->max_bid_amount = $bid_amount;
            $bid->is_accepted = 0;
            $bid->save();

            $currentHighestBid = $ad->bids()->whereNotNull('bid_amount')->orderBy('bid_amount', 'desc')->first();

            if (!$bids->count() || ($currentHighestBid && $currentHighestBid->user_id != $user->id)) {
                $bid = new Bid;
                $bid->ad_id = $ad->id;
                $bid->user_id = $user->id;
                $bid->bid_amount = $minimumBid;
                $bid->is_accepted = 0;
                $bid->save();
            }
        }
        
        return back()->with('success', trans('app.your_bid_posted'));
    }

    public function bidAction(Request $request){
        $action = $request->action;
        $ad_id = $request->ad_id;
        $bid_id = $request->bid_id;
        $won_bid_amount = $request->won_bid_amount;

        $user = Auth::user();
        $user_id = $user->id;
        $ad = Ad::find($ad_id);

        if (! $user->is_admin()){
            if ($ad->user_id != $user_id){
                return ['success' => 0];
            }
        }

        $bid = Bid::find($bid_id);
        switch ($action){
            case 'accept':
                $bid->is_accepted = 1;
                $bid->won_bid_amount = $won_bid_amount;
                $bid->save();

                $wonUser = $bid->user;

                $notification = new Notification;
                $notification->title = trans('app.you_won');
                $notification->text = trans('app.won_and_bought_for', ['won_bid_amount' => themeqx_price($bid->won_bid_amount)]);
                $notification->url = route('single_ad', [$bid->ad_id]);
                $notification->date = Carbon::now();

                $wonUser->notifications()->save($notification);

                // activate notification bell
                $wonUser->notification_bell = 1;
                $wonUser->save();

                break;
            case 'delete':
                $bid->delete();
                break;
        }
        return ['success' => 1];
    }

    public function bidderInfo($bid_id){
        $bid = Bid::find($bid_id);
        $title = trans('app.bidder_info');

        $auth_user = Auth::user();
        $user_id = $auth_user->id;
        $ad = Ad::find($bid->ad_id);

        if (! $auth_user->is_admin()){
            if ($ad->user_id != $user_id){
                return view('admin.error.error_404');
            }
        }

        $user = User::find($bid->user_id);

        return view('admin.profile', compact('title', 'user', 'auth_user'));
    }


}
