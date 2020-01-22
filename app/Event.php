<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    //
    protected $fillable = [
        "name", "start", "end", "category", "location", "phone", "email", "website", "description", "poster", "video",
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
