<?php

namespace App\Http\Controllers;

use Auth;
use App\Ad;
use App\Event;
use Carbon\Carbon;
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

    	if ($user->is_admin()) {
			$status = $request->status ? array_search($request->status, getArticleStatuses()) : null;

    		$ads = Ad::where(function ($q) use ($request) {
						$q->where('title', 'LIKE', '%' . $request->q . '%')
	        				->orWhere('bid_no', 'LIKE', '%' . $request->q . '%');
    		});

    		if ($request->has('status')) {
    			$ads = $ads->where('status', (string)$status);
    		}
		}

    	if (!$user->is_admin()) {
			$status = $request->status ? $request->status : null;

    		$ads = Ad::whereHas('bids', function ($q) use ($status) {
	            if ($status == 'won') {
	            	$q->where('bids.user_id', Auth::id())
                		->where('bids.is_accepted', 1);
	            } elseif($status == 'lost') {
	            	$q->where('bids.user_id', Auth::id())
                        ->where('bids.is_accepted', 0)
                        ->whereNull('bids.won_bid_amount');
	            } else {
                    $q->where('bids.user_id', Auth::id());
                }
	        })
            ->where(function ($q) use ($request) {
				$q->where('title', 'LIKE', '%' . $request->q . '%')
    				->orWhere('bid_no', 'LIKE', '%' . $request->q . '%');
    			});

    		if ($status == 'active') {
    			$ads = $ads->where('expired_at', '>=', Carbon::now());
    		} else if ($status == 'lost') {
    			$ads = $ads->where('expired_at', '<', Carbon::now());
    		}
    	}

		$ads = isset($orderBy) 
                    ? $ads->orderBy(getBeforeLastChar($orderBy, '_'), getAfterLastChar($orderBy, '_')) 
                    : $ads->orderBy('order')->orderBy('bid_no');
		$ads = $ads->paginate(20);

    	$request->flash();

    	return view('admin.search', compact('ads', 'user', 'status'));
    }
}
