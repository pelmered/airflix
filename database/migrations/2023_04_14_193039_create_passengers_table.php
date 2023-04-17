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
        Schema::create('passengers', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('name');
            $table->string('personal_id_number');
            $table->string('email');
            $table->string('phone_number');
            $table->string('address');
            $table->string('city');
            $table->string('country');
            $table->string('nationality');
            $table->string('passport_number');
            $table->string('passport_expiration_date');
            $table->string('passport_issuing_country'); // ISO 3166-1 alpha-2
            $table->string('passport_issuing_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passengers');
    }
};
