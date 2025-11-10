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
        Schema::create('utility_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type_name', 50)->unique();
            $table->string('display_name', 50);
            $table->string('icon_name', 50);
            $table->tinyInteger('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utility_types');
    }
};
