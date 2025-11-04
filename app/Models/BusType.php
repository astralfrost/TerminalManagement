<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusType extends Model
{
    use HasFactory;

    protected $primaryKey = 'type_id';
    protected $table = 'bus_types';

    protected $fillable = [
        'type_name',
        'description',
    ];

    /**
     * Get the buses associated with this type.
     */
    public function buses()
    {
        return $this->hasMany(Bus::class, 'type_id', 'type_id');
    }
}