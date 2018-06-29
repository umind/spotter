<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
	// public $timestamps = false;

    public function notifiable()
	{
		return $this->morphTo();
	}

	public function getDates()
    {
        return ['created_at'];
    }
}
