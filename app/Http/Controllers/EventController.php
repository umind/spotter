<?php

namespace App\Http\Controllers;

use File;
use App\Ad;
use App\Category;
use App\Event;
use App\Http\Requests\EventRequest;
use App\Http\Requests\EventUpdateRequest;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Image;

class EventController extends Controller
{
    public function index(){
        $limit_regular_ads = get_option('number_of_free_ads_in_home');
        $events = Event::active()
                        ->orderBy('auction_ends')
                        ->orderBy('status')
                        ->paginate(20);

        return view('events.index', compact('events'));
    }

    public function show(Event $event){
        $total_ads_count = Ad::active()->count();
        $user_count = User::count();

        $ads = $event->auctions()->active()->paginate(20);

        return view('events.show', compact('ads', 'total_ads_count', 'user_count', 'event'));
    }

    // admin panel methods
    public function myEvents() {
        $limit_regular_ads = get_option('number_of_free_ads_in_home');
        $title = trans('app.my_events');
        $events = Auth::user()->events()->paginate(20);
        return view('admin.events.my_events', compact('events', 'title'));
    }

    public function create()
    {
        $title = trans('app.post_an_event');
        // get all products of logged in user that are not assinged to any event
        $products = Auth::user()->ads()->doesntHave('events')->get();

        return view('admin.events.create', compact('title', 'products'));
    }

    public function store(EventRequest $request)
    {
        $user = Auth::user();
        $event = new Event;
        $event->title = $request->title;
        $event->auctioner = $request->auctioner;
        $event->address = $request->address;
        $event->city = $request->city;
        $event->zip_code = $request->zip_code;
        // $event->auction_begins = Carbon::parse($request->auction_begins);
        $event->auction_ends = Carbon::parse($request->auction_deadline);
        $event->view_dates = $request->view_dates;
        $event->description = $request->description;
        $event->save();

        $this->uploadEventImage($request, $event);
        $user->events()->save($event);

        // $event->auctions()->sync($request->products);

        return redirect()->route('pending_events')->with('success', trans('app.auction_created_msg'));
    }

    public function edit(Event $event) {
        $title = trans('app.edit_an_event');
        $productsWithNoEvent = Auth::user()->ads()->doesntHave('events')->get();
        $thisEventProducts = $event->auctions;
        $products = $productsWithNoEvent->concat($event->auctions);
        $selectedProducts = isset($thisEventProducts) ? $thisEventProducts->pluck('id')->toArray() : [];

        return view('admin.events.edit', compact('products', 'title', 'selectedProducts', 'event'));
    }

    public function update(EventUpdateRequest $request, Event $event)
    {
        $user = Auth::user();
        $event->title = $request->title;
        $event->auctioner = $request->auctioner;
        $event->address = $request->address;
        $event->city = $request->city;
        $event->zip_code = $request->zip_code;
        // $event->auction_begins = Carbon::parse($request->auction_begins);
        $event->auction_ends = Carbon::parse($request->auction_deadline);
        $event->view_dates = $request->view_dates;
        $event->description = $request->description;
        $event->save();

        $this->uploadEventImage($request, $event);
        $user->events()->save($event);


        // $event->auctions()->sync($request->products);

        // assign bid deadline to every product the same as the event deadline
        // foreach ($request->products as $product) {
        //     $ad = Ad::find($product);
        //     if ($event->status == '1') {
        //         $ad->status = '1';
        //     }
        //     $ad->save();
        // }

        if ($event->status == '2') {
            return redirect()->route('closed_events')->with('success', trans('app.auction_created_msg'));
        } elseif($event->status == '1') {
            return redirect()->route('active_events')->with('success', trans('app.auction_created_msg'));
        } elseif ($event->status == '0') {
            return redirect()->route('pending_events')->with('success', trans('app.auction_created_msg'));
        } elseif ($event->status == '3') {
            return redirect()->route('archived_events')->with('success', trans('app.auction_created_msg'));
        }
    }

    public function delete(Request $request)
    {
        $event = Auth::user()->events()->findOrFail($request->event);

        $eventImagePath = public_path('uploads/images/' . $event->image);
        if (file_exists($eventImagePath)) {
            File::delete($eventImagePath);
        }

        $event->delete();

        return ['success'=>1, 'msg'=>trans('app.auction_deleted_msg')];
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

    public function archived()
    {
        $title = trans('app.archived_events');
        $events = Auth::user()->events()->whereStatus('3')->orderBy('id', 'desc')->paginate(20);

        return view('admin.events.archived_events', compact('title', 'events'));
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

    public function archive(Event $event)
    {
        $event->status = '3';
        $event->save();

        $event->auctions()->update(['status' => '3']);

        return redirect()->route('closed_events')->with('success', trans('app.event_archived_msg'));
    }

    public function getEventTime(Request $request)
    {
        $event = Event::findOrFail($request->event_id);
        $eventTime = Carbon::parse($event->auction_ends)->format('H:i');
        if ($eventTime) {
            return response()->json(['success' => true, 'eventTime' => $eventTime]);
        }

        return response()->json(['success' => false]);
    }

    public function uploadEventImage(Request $request, $event){

        $user_id = Auth::user()->id;

        if ($request->file('image')) {
            $image = $request->file('image');
            $valid_extensions = ['jpg','jpeg','png'];

            if ( ! in_array(strtolower($image->getClientOriginalExtension()), $valid_extensions) ){
                return redirect()->back()->withInput($request->input())->with('error', 'Only .jpg, .jpeg and .png is allowed extension') ;
            }

            $file_base_name = str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName());
            $resized = Image::make($image)->resize(null, 1277, function ($constraint) { // 1278
                $constraint->aspectRatio();
                $constraint->upsize();
            })->stream();

            $image_name = strtolower(time().str_random(5).'-'.str_slug($file_base_name)).'.' . $image->getClientOriginalExtension();

            $imageFileName = 'uploads/images/' . $image_name;

            try{
                $currentPhotoPath = public_path('uploads/images/' . $event->image);
                if (file_exists($currentPhotoPath)) {
                    File::delete($currentPhotoPath);
                }
                
                current_disk()->put($imageFileName, $resized->__toString(), 'public');

                //Save image name into db
                $event->update(['image' => $image_name]);

            } catch (\Exception $e){
                return redirect()->back()->withInput($request->input())->with('error', $e->getMessage()) ;
            }
        }
    }
}
