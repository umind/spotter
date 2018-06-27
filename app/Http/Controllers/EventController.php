<?php

namespace App\Http\Controllers;

use Auth;
use App\Ad;
use App\User;
use App\Event;
use App\Category;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(){
        $limit_regular_ads = get_option('number_of_free_ads_in_home');
        $events = Event::paginate(10);
        return view('events.index', compact('events'));
    }

    public function show(Event $event){
        $top_categories = Category::whereCategoryType('auction')->orderBy('category_name', 'asc')->get();

        $limit_regular_ads = get_option('number_of_free_ads_in_home');
        $limit_premium_ads = get_option('number_of_premium_ads_in_home');

        $total_ads_count = Ad::active()->count();
        $user_count = User::count();

        $ads = $event->auctions()->paginate(10);

        return view('events.show', compact('top_categories', 'ads', 'total_ads_count', 'user_count'));
    }

    // admin panel methods
    public function myEvents() {
        $limit_regular_ads = get_option('number_of_free_ads_in_home');
        $title = trans('app.my_events');
        $events = Auth::user()->events()->paginate(10);
        return view('admin.events.my_events', compact('events', 'title'));
    }

    public function create()
    {
        $title = trans('app.post_an_event');
        $ads = Auth::user()->ads;

        return view('admin.events.create', compact('title', 'ads'));
    }

    public function store()
    {

    }

    public function edit(Event $event) {
        $limit_regular_ads = get_option('number_of_free_ads_in_home');
        $title = trans('app.my_events');
        $events = Auth::user()->events()->paginate(10);
        return view('admin.events.my_events', compact('events', 'title'));
    }

    public function update(Event $event)
    {
        
    }

    public function pending()
    {
        $title = trans('app.pending_events');
        $events = Auth::user()->events()->whereStatus('0')->orderBy('id', 'desc')->paginate(20);

        return view('admin.events.pending_events', compact('title', 'events'));
    }

    public function changeStatus(Request $request){
        $event = Auth::user()->events()->findOrFail($request->event);

        if ($event) {
            $value = $request->value;

            $event->status = $value;
            $event->save();

            if ($value == 1){
                return ['success' => 1, 'msg' => trans('app.event_approved_msg')];
            }
        }
        return ['success'=> 0, 'msg' => trans('app.error_msg')];
    }
}
