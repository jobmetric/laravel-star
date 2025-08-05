<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for creating the stars table.
 *
 * This table stores polymorphic star ratings from any actor model (such as User, Customer, Device)
 * to any target model (such as Product, Article, Post). It supports flexible configuration
 * and includes optional fields for tracking platform and device-specific metadata.
 *
 * Configuration:
 * - Table name: config('star.tables.star')
 * - Default minimum star value: config('star.min_rate')
 *
 * Table Structure:
 * - `starred_by_type` / `starred_by_id`: polymorphic relation to the model giving the rating
 * - `starable_type` / `starable_id`: polymorphic relation to the model receiving the rating
 * - `rate`: unsigned tiny integer, indexed, representing the rating value
 * - `ip`: optional IP address of the actor giving the rating
 * - `device_id`: optional device identifier for more precise tracking
 * - `source`: optional platform identifier (e.g., 'web', 'mobile', 'api')
 * - `timestamps`: standard created_at and updated_at fields
 *
 * Indexes and Constraints:
 * - Unique constraint on [starable_type, starable_id, starred_by_type, starred_by_id]
 *   ensures a single actor can only rate the same target once
 * - Indexed: rate, ip, device_id for fast lookup and analysis
 *
 * Example Use-Case:
 * A `User` gives a rating of 4 to a `Product` from their mobile device,
 * and the system stores their IP address and device ID for analytics.
 */
return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(config('star.tables.star'), function (Blueprint $table) {
            $table->id();

            $table->nullableMorphs('starred_by');
            // The actor who gives the rating
            // This can be any model that can give ratings, such as User, Customer, Device, etc.

            $table->morphs('starable');
            // The target that receives the rating

            $table->unsignedTinyInteger('rate')->index();
            // Rating value (e.g. 1 to 5, or 0 to 10), configurable

            $table->ipAddress('ip')->nullable()->index();
            // IP address of the rater (optional)

            $table->string('device_id')->nullable()->index();
            // Device identifier for further tracking (optional)

            $table->string('source')->nullable();
            // Source platform of the rater (e.g., 'web', 'mobile')

            $table->timestamps();

            $table->unique([
                'starable_type',
                'starable_id',
                'starred_by_type',
                'starred_by_id'
            ], 'UNIQUE_STAR_PER_ACTOR_TARGET');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(config('star.tables.star'));
    }
};
