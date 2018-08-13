<?php

namespace App\Http\Controllers;

use Auth;
use App\Ad;
use App\Event;
use Illuminate\Http\Request;

class SearchController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

    public function getArticlesResults(Request $request)
    {
    	$title = __('app.ads_search');
    	$user = Auth::user();

		$orderBy = $request->order_by ? $request->order_by : null;
		$status = $request->status ? array_search($request->status, getArticleStatuses()) : null;

    	if ($user->is_admin()) {
    		$ads = Ad::where(function ($q) use ($request) {
    					$q->where('title', 'LIKE', '%' . $request->q . '%')
	        				->orWhere('bid_no', 'LIKE', '%' . $request->q . '%');
    		});

    		if ($request->has('status')) {
    			$ads = $ads->where('status', (string)$status);
    		}
		}

    	if (!$user->is_admin()) {
    		$ads = Ad::whereHas('bids', function ($q) use ($user) {
	            $q->where('bids.user_id', $user->id);
	        })->where('title', 'LIKE', '%' . $request->q . '%')
	        	->orWhere('bid_no', 'LIKE', '%' . $request->q . '%');
    	}

		$ads = isset($orderBy) ? $ads->orderBy(getBeforeLastChar($orderBy, '_'), getAfterLastChar($orderBy, '_')) : $ads->latest();
		$ads = $ads->paginate(20);

    	$request->flash();

    	return view('admin.search', compact('ads', 'user'));
    }
}
