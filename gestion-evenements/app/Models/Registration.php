<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'full_name',
        'email',
        'phone',
        'status',
    ];

    /**
     * Relation avec l'événement
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Scope pour les inscriptions actives
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
