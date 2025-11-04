<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id('route_id'); // Custom primary key name
            $table->string('route_name', 50)->unique(); // e.g., Tagbilaran - Tubigon
            $table->string('start_location', 100);
            $table->string('end_location', 100);
            $table->decimal('base_fare', 10, 2); // Base fare as per ERD
            $table->unsignedBigInteger('updated_by_admin_id')->nullable(); // Soft foreign key to admin_users
            $table->timestamps(); // updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};