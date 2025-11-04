<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fare extends Model
{
    use HasFactory;

    protected $primaryKey = 'fare_id';
    protected $table = 'fare';

    protected $fillable = [
        'route_id',
        'fare_amount',
        'updated_by_admin_id',
    ];

    /**
     * Get the route associated with the fare.
     */
    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id', 'route_id');
    }
}