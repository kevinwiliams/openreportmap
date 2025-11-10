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
        Schema::create('reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('report_type', 30);
            $table->char('country_code', 2);
            $table->char('parish_code', 2);
            $table->integer('community_geonames_id');
            $table->uuid('utility_type_id')->nullable();
            $table->uuid('provider_id')->nullable();
            $table->uuid('disaster_id');
            $table->decimal('precise_latitude', 10, 8)->nullable();
            $table->decimal('precise_longitude', 11, 8)->nullable();
            $table->text('location_description')->nullable();
            $table->string('severity', 20)->nullable();
            $table->text('description')->nullable();
            $table->string('source_url')->nullable();
            $table->string('source_type', 40)->nullable();
            $table->string('source_platform', 40)->nullable();
            $table->json('embed_data')->nullable();
            $table->string('status', 30)->nullable();
            $table->integer('confirmation_count')->default(0);
            $table->integer('comment_count')->default(0);
            $table->integer('upvote_count')->default(0);
            $table->integer('downvote_count')->default(0);
            $table->tinyInteger('is_flagged')->default(0);
            $table->string('relief_point_type', 40)->nullable();
            $table->string('relief_point_category', 40)->nullable();
            $table->string('contact_phone', 40)->nullable();
            $table->integer('capacity')->nullable();
            $table->integer('current_occupancy')->nullable();
            $table->string('operating_hours', 100)->nullable();
            $table->dateTime('created_at', 6)->nullable();
            $table->dateTime('updated_at', 6)->nullable();
            $table->string('reporter_display', 100)->nullable();
            $table->foreign('country_code')->references('country_code')->on('countries');
            $table->foreign('parish_code')->references('parish_code')->on('parishes');
            $table->foreign('community_geonames_id')->references('geonames_id')->on('communities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
