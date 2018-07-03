<?php

namespace App\Http\Controllers;

use App\Ad;
use App\Bid;
use App\User;
use Carbon\Carbon;
use App\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $ad = Ad::find($ad_id);

        // check if expired
        if (Carbon::parse($ad->expired_at)->isPast()) {
            return back()->with('error', trans('app.bidding_time_expired'));
        }

        // get max bid that you enter inside place bid field
        $current_max_bid = $ad->current_bid_plus_increaser();

        // get max bid that you enter inside place MAX bid field
        $maxBid = $ad->bids()->where('user_id', '!=', $user->id)->max('max_bid_amount');

        // for notification also
        $userWithCurrentMaxBid = User::whereHas('bids', function ($q) {
            $q->orderBy('max_bid_amount', 'desc');
        })->first();

        if ($bid_amount < $current_max_bid ){
            return back()->with('error', sprintf(trans('app.enter_min_bid_amount'), themeqx_price($current_max_bid)) );
        }

        if($userWithCurrentMaxBid && $maxBid > $bid_amount) {
            $bid_amount += $ad->price_increaser;
        }

        $data = [
            'ad_id'         => $ad_id,
            'user_id'       => $userWithCurrentMaxBid && $maxBid > $bid_amount ? $userWithCurrentMaxBid->id : $user->id,
            'bid_amount'    => $bid_amount,
            'is_accepted'   => 0,
        ];

        Bid::create($data);

        // notification here
        // if ($bid_amount > $maxBid) {
        //     $notification = new Notification
        //     $user->
        // }

        return back()->with('success', trans('app.your_bid_posted'));
    }

    public function postMaxBid(Request $request , $ad_id){
        if ( ! Auth::check()){
            return redirect(route('login'))->with('error', trans('app.login_first_to_post_bid'));
        }
        $user = Auth::user();
        $bid_amount = toFloat($request->max_bid_amount);

        $ad = Ad::find($ad_id);
        $current_max_bid = $ad->current_bid_plus_increaser();
        $maxBid = $ad->bids()->where('user_id', '!=', $user->id)->max('max_bid_amount');

        if ($bid_amount < $current_max_bid ){
            return back()->with('error', sprintf(trans('app.enter_min_bid_amount'), themeqx_price($current_max_bid)) );
        }

        if ($bid_amount == $maxBid){
            return back()->with('error', trans('app.max_bid_same_error_msg'));
        }

        $data = [
            'ad_id'         => $ad_id,
            'user_id'       => $user->id,
            'bid_amount'    => $current_max_bid == $bid_amount ? $bid_amount : $current_max_bid,
            'max_bid_amount'    => $bid_amount,
            'is_accepted'   => 0,
        ];

        // find if this user already has placed max bid
        $bid = $ad->bids()->where('user_id', $user->id)->whereNotNull('max_bid_amount')->first();

        // if max bids by this user already exists replace by new max bid
        // if thats not the case then just create new max bid
        if ($bid) {
            $bid->bid_amount = $current_max_bid == $bid_amount ? $bid_amount : null;
            $bid->max_bid_amount = $bid_amount;
            $bid->save();
        } else {
            Bid::create($data);
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
                $notification->title = 'You won';
                $notification->text = 'You won on an auction. Product bought for ' . themeqx_price($bid->won_bid_amount);
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

        return view('admin.profile', compact('title', 'user'));
    }


}
