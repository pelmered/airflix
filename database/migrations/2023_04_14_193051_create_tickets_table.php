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
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('flight_number', 10);
            $table->uuid('passenger_uuid');
            $table->integer('seat_number');
            $table->string('status');
            $table->integer('price');
            $table->timestamps();

            $table->foreign('flight_number')
                  ->references('flight_number')
                  ->on('flights')
                  ->cascadeOnDelete();
            $table->foreign('passenger_uuid')
                  ->references('uuid')
                  ->on('passengers')
                  ->cascadeOnDelete();
            //$table->unique(['flight_number', 'seat_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
