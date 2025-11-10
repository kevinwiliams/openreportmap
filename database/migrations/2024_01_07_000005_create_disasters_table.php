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
        Schema::create('disasters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('disaster_type', 50);
            $table->string('name', 100);
            $table->string('severity', 20);
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->json('affected_countries');
            $table->json('affected_adm1');
            $table->json('affected_adm2');
            $table->json('affected_adm3');
            $table->json('affected_adm4');
            $table->json('affected_adm5');
            $table->text('alert_message');
            $table->text('alert_message_es');
            $table->string('alert_color', 20);
            $table->dateTime('created_at');
            $table->json('statistics');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disasters');
    }
};
