<?php

namespace App\Http\Controllers;

use App\Ad;
use App\Category;
use Carbon\Carbon;
use App\Contact_query;
use App\Country;
use App\Post;
use App\Slider;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class HomeController extends Controller
{
    public function index(){
        $top_categories = Category::whereCategoryType('auction')->orderBy('category_name', 'asc')->get();

        $ads = Ad::active()->orderBy('id', 'desc')->get();

        $total_ads_count = Ad::active()->count();
        $user_count = User::count();

        return view('index', compact('top_categories', 'ads', 'total_ads_count', 'user_count'));
    }
    
    public function contactUs(){
        $title = trans('app.contact_us');
        return view('contact_us', compact('title'));
    }

    public function contactUsPost(Request $request){
        $rules = [
            'name'  => 'required',
            'email'  => 'required|email',
            'message'  => 'required',
        ];
        $this->validate($request, $rules);
        Contact_query::create(array_only($request->input(), ['name', 'email', 'message']));
        return redirect()->back()->with('success', trans('app.your_message_has_been_sent'));
    }

    public function contactMessages(){
        $title = trans('app.contact_messages');
        return view('admin.contact_messages', compact('title'));
    }

    public function contactMessagesData(){
        $contact_messages = Contact_query::select('name', 'email', 'message','created_at')->orderBy('id', 'desc')->get();

        return  Datatables::of($contact_messages)
            ->editColumn('created_at',function($contact_message){
                return Carbon::parse($contact_message->created_at)->formatLocalized(get_option('date_format'));
            })
            ->make();
    }

    /**
     * Switch Language
     */
    public function switchLang($lang){
        session(['lang'=>$lang]);
        return back();
    }

    /**
     * Reset Database
     */
    public function resetDatabase(){
        $database_location = base_path("database-backup/classified.sql");
        // Temporary variable, used to store current query
        $templine = '';
        // Read in entire file
        $lines = file($database_location);
        // Loop through each line
        foreach ($lines as $line) {
            // Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '')
                continue;
            // Add this line to the current segment
            $templine .= $line;
            // If it has a semicolon at the end, it's the end of the query
            if (substr(trim($line), -1, 1) == ';')
            {
                // Perform the query
                DB::statement($templine);
                // Reset temp variable to empty
                $templine = '';
            }
        }
        $now_time = date("Y-m-d H:m:s");
        DB::table('ads')->update(['created_at' => $now_time, 'updated_at' => $now_time]);
    }


}
