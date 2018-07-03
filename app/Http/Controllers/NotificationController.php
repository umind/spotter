<?php

namespace App\Http\Controllers;

use Auth;
use App\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function removeNumber() {
    	$user = Auth::user();
    	$user->notification_bell = 0;
    	$user->save();

        return ['success' => 1];
    }

    public function show(Notification $notification)
    {
    	$notification->is_read = 1;;
    	$notification->save();
    	return redirect()->away($notification->url);
    }
}
