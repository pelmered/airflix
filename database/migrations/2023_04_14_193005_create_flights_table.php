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
        Schema::create('flights', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('flight_number', 10)->unique();
            $table->string('departure_airport');
            $table->string('destination_airport');
            $table->string('departure_time');
            $table->string('arrival_time');
            $table->integer('no_of_seats')->default(32);
            $table->integer('price');
            $table->string('airline');
            $table->string('aircraft');
            $table->string('flight_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
