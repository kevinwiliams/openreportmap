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
        Schema::create('communities', function (Blueprint $table) {
            $table->id();
            $table->integer('geonames_id')->unique();
            $table->string('name', 100);
            $table->string('ascii_name', 100);
            $table->char('parish_code', 2);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('population')->nullable();
            $table->char('country_code', 2);
            $table->foreign('parish_code')->references('parish_code')->on('parishes');
            $table->foreign('country_code')->references('country_code')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communities');
    }
};
