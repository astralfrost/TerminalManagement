<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bus_schedules', function (Blueprint $table) {
           $table->id();
$table->string('bus_number')->unique();
$table->foreignId('route_id')->constrained('routes')->onDelete('cascade');
$table->time('departure_time');
$table->foreignId('bus_type_id')->constrained('bus_types')->onDelete('cascade');
$table->date('day'); // Changed to date to match calendar picker
$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bus_schedules');
    }
};
