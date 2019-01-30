<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = ['id'];

    public function auctions()
    {
    	return $this->belongsToMany(Ad::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
                $html = '<span class="text-muted">'.trans('app.closed').'</span>';
                break;
        }
        return $html;
    }

    public function scopeActive($query)
    {
        // closed or active
        return $query->where('status', '1')
                    ->orWhere(function ($query) {
                        $query->closed();
                    })
                    ->orWhere(function ($query) {
                        $query->pending();
                    });
    }

    public function scopeClosed($query)
    {
        return $query->where('status', '2');
    }

    public function scopePending($query)
    {
        return $query->where('status', '0');
    }

    public function is_editable() {
        if ($this->status == 0 || $this->status == 1) {
            return true;
        }
        return false;
    }
}
