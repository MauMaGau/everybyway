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
        Schema::create('pings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('bimble_id')->nullable()->constrained();
            $table->json('data'); // temporarily store all data for testing
            $table->float('lat', 11, 11-2); // (+/- 90)
            $table->float('lon', 11, 11-3); // (+/- 180)
            $table->integer('distance_from_last_ping')->nullable();
            $table->boolean('is_home_area')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pings');
    }
};
