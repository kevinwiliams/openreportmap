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
        Schema::create('providers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('provider_name', 100);
            $table->string('provider_code', 50)->unique();
            $table->uuid('utility_type_id');
            $table->string('utility_type_name', 50);
            $table->uuid('country_id');
            $table->string('country_name', 100);
            $table->string('website_url', 255)->nullable();
            $table->string('support_phone', 50)->nullable();
            $table->string('support_email', 255)->nullable();
            $table->foreign('utility_type_id')->references('id')->on('utility_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
