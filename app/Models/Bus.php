<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    protected $primaryKey = 'bus_id';
    protected $table = 'buses';

    protected $fillable = [
        'driver_id',
        'bus_number',
        'capacity',
        'route_id',
        'type_id',
        'official_receipt_number',
        'certificate_of_registration',
        'updated_by_admin_id',
    ];

    /**
     * Get the driver for the bus.
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'driver_id');
    }

    /**
     * Get the route for the bus.
     */
    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id', 'route_id');
    }

    /**
     * Get the bus type.
     */
    public function busType()
    {
        return $this->belongsTo(BusType::class, 'type_id', 'type_id');
    }

    /**
     * Get the schedules for the bus.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'bus_id', 'bus_id');
    }
}