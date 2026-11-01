<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

protected $fillable = ['event_id', 'user_name', 'user_email'];

public function event()
{
    return $this->belongsTo(Event::class);
}
