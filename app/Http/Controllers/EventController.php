<?php

namespace App\Http\Controllers;

use Auth;
use App\Ad;
use App\User;
use App\Event;
use App\Category;
use Carbon\Carbon;
use App\Http\Requests\EventRequest;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(){
        $limit_regular_ads = get_option('number_of_free_ads_in_home');
        $events = Event::published()->paginate(10);
        return view('events.index', compact('events'));
    }

    public function show(Event $event){
        $total_ads_count = Ad::active()->count();
        $user_count = User::count();

        $ads = $event->auctions()->paginate(10);

        return view('events.show', compact('ads', 'total_ads_count', 'user_count', 'event'));
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
        $products = Auth::user()->ads;

        return view('admin.events.create', compact('title', 'products'));
    }

    public function store(EventRequest $request)
    {
        $user = Auth::user();
        $event = new Event;
        $event->title = $request->title;
        $event->address = $request->address;
        $event->city = $request->city;
        $event->zip_code = $request->zip_code;
        $event->auction_ends = Carbon::parse($request->auction_deadline);
        $event->view_dates = $request->view_dates;
        $event->description = $request->description;
        $event->save();

        $user->events()->save($event);

        $event->auctions()->sync($request->products);

        // assign bid deadline to every product the same as the event deadline
        foreach ($request->products as $product) {
            $ad = Ad::find($product);
            $ad->expired_at = Carbon::parse($request->auction_deadline);
            $ad->save();
        }

        return redirect()->route('pending_events')->with('success', trans('app.ad_created_msg'));
    }

    public function edit(Event $event) {
        $title = trans('app.edit_an_event');
        $products = Auth::user()->ads;
        $selectedProducts = isset($event->auctions) ? $event->auctions->pluck('id')->toArray() : [];
        return view('admin.events.edit', compact('products', 'title', 'selectedProducts', 'event'));
    }

    public function update(EventRequest $request, Event $event)
    {
        $user = Auth::user();
        $event->title = $request->title;
        $event->address = $request->address;
        $event->city = $request->city;
        $event->zip_code = $request->zip_code;
        $event->auction_ends = Carbon::parse($request->auction_deadline);
        $event->view_dates = $request->view_dates;
        $event->description = $request->description;
        $event->save();

        $user->events()->save($event);
        $event->auctions()->sync($request->products);

        // assign bid deadline to every product the same as the event deadline
        foreach ($request->products as $product) {
            $ad = Ad::find($product);
            $ad->expired_at = Carbon::parse($request->auction_deadline);
            $ad->save();
        }

        if ($event->status == '2') {
            return redirect()->route('closed_events')->with('success', trans('app.ad_created_msg'));
        } elseif($event->status == '1') {
            return redirect()->route('closed_events')->with('success', trans('app.ad_created_msg'));
        } elseif ($event->status == '0') {
            return redirect()->route('pending_events')->with('success', trans('app.ad_created_msg'));
        }
    }

    public function delete(Request $request)
    {
        $event = Auth::user()->events()->findOrFail($request->event);
        $event->delete();
        return ['success'=>1, 'msg'=>trans('app.ad_deleted_msg')];
    }

    public function pending()
    {
        $title = trans('app.pending_events');
        $events = Auth::user()->events()->whereStatus('0')->orderBy('id', 'desc')->paginate(20);

        return view('admin.events.pending_events', compact('title', 'events'));
    }

    public function active()
    {
        $title = trans('app.active_events');
        $events = Auth::user()->events()->whereStatus('1')->orderBy('id', 'desc')->paginate(20);

        return view('admin.events.active_events', compact('title', 'events'));
    }

        public function closed()
    {
        $title = trans('app.closed_events');
        $events = Auth::user()->events()->whereStatus('2')->orderBy('id', 'desc')->paginate(20);

        return view('admin.events.closed_events', compact('title', 'events'));
    }

    public function changeStatus(Request $request){
        $event = Auth::user()->events()->findOrFail($request->event);

        if ($event) {
            $value = $request->value;

            $event->status = $value;
            $event->save();

            $event->auctions()->update(['status' => $value]);

            if ($value == 1){
                return ['success' => 1, 'msg' => trans('app.event_approved_msg')];
            }
        }
        return ['success'=> 0, 'msg' => trans('app.error_msg')];
    }

    public function close(Event $event)
    {
        $event->status = '2';
        $event->save();

        $event->auctions()->update(['status' => '2']);

        return redirect()->route('dashboard_events')->with('success', trans('app.auction_closed_msg'));
    }
}
