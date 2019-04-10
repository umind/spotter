<?php

namespace App\Http\Controllers;

use Auth;
use App\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function removeNumber() {
    	$user = Auth::user()->notifications()->update([
            'seen' => 1
        ]);

        return ['success' => 1];
    }

    public function show(Notification $notification)
    {
    	$notification->is_read = 1;
    	$notification->save();
    	return redirect()->away($notification->url);
    }
}
