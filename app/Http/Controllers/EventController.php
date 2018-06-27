<?php

namespace App\Http\Controllers;

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
}
