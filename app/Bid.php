<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    protected $guarded = ['id'];

    public function posting_datetime(){
        $created_date_time = $this->created_at->timezone(get_option('default_timezone'))->format(get_option('date_format_custom').' '.get_option('time_format_custom'));
        return $created_date_time;
    }

    public function user()
    { //test
    	return $this->belongsTo(User::class);
    }

    public function auction()
    {
    	return $this->belongsTo(Ad::class);
    }
}
