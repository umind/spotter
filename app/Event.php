<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function auctions()
    {
    	return $this->hasMany(Ad::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function feature_img(){
        $feature_img = $this->hasOne(Media::class)->whereIsFeature('1');
        if (! $feature_img){
            $feature_img = $this->hasOne(Media::class)->first();
        }
        return $this->hasOne(Media::class);
    }

    public function media_img(){
        return $this->hasMany(Media::class)->whereType('image');
    }

    public function status_context(){
        $status = $this->status;
        $html = '';
        switch ($status){
            case 0:
                $html = '<span class="text-muted">'.trans('app.pending').'</span>';
                break;
            case 1:
                $html = '<span class="text-success">'.trans('app.published').'</span>';
                break;
            case 2:
                $html = '<span class="text-warning">'.trans('app.blocked').'</span>';
                break;
        }
        return $html;
    }
}
