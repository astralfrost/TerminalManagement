<?php

namespace Database\Seeders;

// Add the necessary model imports here:
use App\Models\Bus;
use App\Models\BusType;
use App\Models\Driver;
use App\Models\Fare;
use App\Models\Schedule; // THIS IS THE CRITICAL LINE
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Disable foreign key checks for truncation (important for order)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear tables in reverse dependency order
        Schedule::truncate();
        Bus::truncate();
        Driver::truncate();
        Fare::truncate();
        BusType::truncate();
        // ... (other models to clear)

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Call your specific seeders here, if any
        $this->call([
            // DriverSeeder::class,
            // RouteSeeder::class,
            // ...
        ]);
    }
}