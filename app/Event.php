<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function auctions()
    {
    	return $this->hasMany(Ad::class);
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
}
