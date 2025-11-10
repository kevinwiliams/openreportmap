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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->char('country_code', 2)->unique();
            $table->string('name', 100);
            $table->string('flag_emoji', 8)->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->date('launch_date')->nullable();
            $table->smallInteger('total_adm1')->nullable();
            $table->integer('total_adm2')->nullable();
            $table->decimal('map_center_lat', 10, 8)->nullable();
            $table->decimal('map_center_lng', 11, 8)->nullable();
            $table->tinyInteger('map_zoom_level')->nullable();
            $table->char('primary_language', 2)->nullable();
            $table->char('secondary_language', 2)->nullable();
            $table->string('timezone', 50)->nullable();
            $table->tinyInteger('disaster_mode_active')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
