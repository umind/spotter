<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
	public $timestamps = false;

    public function ad() 
    {
        return $this->belongsTo(Ad::class);
    }
}
