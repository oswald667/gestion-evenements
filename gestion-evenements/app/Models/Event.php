<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Event extends Model
{
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
    ];

    // ── Relations ──────────────────────────────────────────
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    // ── Accesseurs utiles ───────────────────────────────────
    public function getOccupancyPercentAttribute(): int
    {
        if ($this->total_places === 0) return 100;
        return (int) round(
            (($this->total_places - $this->available_places) / $this->total_places) * 100
        );
    }

    public function getIsFullAttribute(): bool
    {
        return $this->available_places <= 0;
    }

    public function getIsAlmostFullAttribute(): bool
    {
        return !$this->is_full && $this->available_places <= 5;
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->event_date->translatedFormat('l d F Y à H\hi');
    }

    // ── Couleur badge par catégorie ─────────────────────────
    public function getCategoryColorAttribute(): string
    {
        return match ($this->category) {
            'Conférence' => 'bg-indigo-100 text-indigo-700',
            'Concert'    => 'bg-pink-100 text-pink-700',
            'Sport'      => 'bg-green-100 text-green-700',
            'Atelier'    => 'bg-amber-100 text-amber-700',
            'Exposition' => 'bg-purple-100 text-purple-700',
            default      => 'bg-gray-100 text-gray-700',
        };
    }
}