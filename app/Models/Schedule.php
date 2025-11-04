<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    // --- FIX: Explicitly set the table name ---
    protected $table = 'schedules'; 
    // ------------------------------------------

    // Define the custom primary key
    protected $primaryKey = 'schedule_id';

    // Define fillable fields for mass assignment
    protected $fillable = [
        'bus_id',
        'driver_id',
        'departure_time',
        'arrival_time',
        'updated_by_admin_id',
    ];

    /**
     * Get the Bus associated with the Schedule.
     */
    public function bus()
    {
        // Links to the Bus model using the custom foreign key 'bus_id'
        return $this->belongsTo(Bus::class, 'bus_id', 'bus_id');
    }

    /**
     * Get the Driver associated with the Schedule.
     */
    public function driver()
    {
        // Links to the Driver model using the custom foreign key 'driver_id'
        return $this->belongsTo(Driver::class, 'driver_id', 'driver_id');
    }
}