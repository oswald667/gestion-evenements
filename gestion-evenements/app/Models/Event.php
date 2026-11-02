<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'location',
        'category',
        'total_places',
        'available_places',
        'image',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'total_places' => 'integer',
        'available_places' => 'integer',
    ];

    /**
     * Relation avec toutes les inscriptions
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Relation avec les inscriptions actives
     */
    public function activeRegistrations()
    {
        return $this->hasMany(Registration::class)->where('status', 'active');
    }

    /**
     * Attribut dynamique pour les places restantes
     */
    public function getRemainingPlacesAttribute(): int
    {
        $activeCount = $this->activeRegistrations()->count();
        $remaining = $this->total_places - $activeCount;
        return max(0, $remaining);
    }

    /**
     * Vérifier si l'événement est complet
     */
    public function isFull(): bool
    {
        return $this->remaining_places <= 0;
    }

    /**
     * Vérifier si l'événement est passé
     */
    public function isPast(): bool
    {
        return $this->event_date->isPast();
    }

    /**
     * Scope pour filtrer par recherche, catégorie et date
     */
    public function scopeFilter($query, array $filters)
    {
        // Recherche par titre, description ou lieu
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Filtre par catégorie
        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        // Filtre par date : 'upcoming' (à venir), 'past' (passé), ou date précise (YYYY-MM-DD)
        if (!empty($filters['date_filter'])) {
            if ($filters['date_filter'] === 'upcoming') {
                $query->where('event_date', '>=', Carbon::now());
            } elseif ($filters['date_filter'] === 'past') {
                $query->where('event_date', '<', Carbon::now());
            } elseif ($filters['date_filter'] === 'today') {
                $query->whereDate('event_date', Carbon::today());
            } else {
                // Date précise
                try {
                    $d = Carbon::parse($filters['date_filter'])->toDateString();
                    $query->whereDate('event_date', $d);
                } catch (\Exception $e) {
                    // ignore invalid date
                }
            }
        }

        return $query;
    }
}
