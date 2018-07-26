<?php

namespace App\Http\Controllers;

use App\Ad;
use App\Event;
use App\Contact_query;
use App\Payment;
use App\Report_ad;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard(){
        $user = Auth::user();
        $user_id = $user->id;

        $active_events = 0;
        $pending_events = 0;
        $total_users = 0;
        $total_reports = 0;
        $total_payments = 0;
        $ten_contact_messages = 0;
        $reports = 0;
        $total_payments_amount = 0;

        if ($user->is_admin()){
            $active_ads = $user->ads()->whereStatus('1')->count();
            $active_events = $user->events()->whereStatus('1')->count();
            $pending_events = $user->events()->whereStatus('0')->count();
            $pending_ads = $user->ads()->whereStatus('0')->count();
            $blocked_ads = $user->ads()->whereStatus('2')->count();

            $total_users = User::count();
            $total_reports = Report_ad::count();
            $total_payments = Payment::whereStatus('success')->count();
            $total_payments_amount = Payment::whereStatus('success')->sum('amount');
            $ten_contact_messages = Contact_query::take(10)->orderBy('id', 'desc')->get();
            $reports = Report_ad::orderBy('id', 'desc')->with('ad')->take(10)->get();
        }else{
            $approved_ads = Ad::whereStatus('1')->whereUserId($user_id)->count();
            $pending_ads = Ad::whereStatus('0')->whereUserId($user_id)->count();
            $blocked_ads = Ad::whereStatus('2')->whereUserId($user_id)->count();
        }

        return view('admin.dashboard', compact('active_ads', 'pending_ads', 'blocked_ads', 'total_users', 'total_reports', 'total_payments', 'total_payments_amount', 'ten_contact_messages', 'reports', 'active_events', 'pending_events'));
    }


    public function logout(){
        if (Auth::check()){
            Auth::logout();
        }

        return redirect(route('login'));
    }
}
