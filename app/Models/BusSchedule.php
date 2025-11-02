<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusSchedule extends Model
{
    protected $fillable = ['bus_number', 'route_id', 'departure_time', 'bus_type_id', 'day'];

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    public function busType(): BelongsTo
    {
        return $this->belongsTo(BusType::class);
    }
}
