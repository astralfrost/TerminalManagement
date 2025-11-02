<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Route;
use App\Models\BusType;
use App\Models\BusSchedule; // Make sure to import BusSchedule

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. MUST clear dependent tables first due to foreign keys.
        BusSchedule::truncate();
        
        // 2. Clear parent (lookup) tables.
        BusType::truncate();
        Route::truncate();

        // Seed Bus Types
        BusType::create(['name' => 'Ordinary']);
        BusType::create(['name' => 'Express']);
        BusType::create(['name' => 'Premium']);

        // Seed Routes
        Route::create(['name' => 'Tagbilaran - Tubigon']);
        Route::create(['name' => 'Tagbilaran - Carmen']);
        Route::create(['name' => 'Tubigon - Ubay']);
        Route::create(['name' => 'Carmen - Jagna']);
    }
}
