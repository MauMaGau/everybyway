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
        Schema::table('pings', function (Blueprint $table) {
            // Let's store this when a ping is created (or perhaps later when it's first viewed)
            // Either way, caching it here means we only have to calculate it once,
            // rather than every time the data is retrieved or rendered.
            $table->boolean('is_home_area')->default(false)->after('distance_from_last_ping');
        });

        // Let's populate the legacy data
        $pings = \App\Models\Ping::get();

        $pings->each(function(\App\Models\Ping $ping) {
            // Setting the home area is done on the models ::saving event, so we only need to touch it here
            $ping->touch();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pings', function (Blueprint $table) {
            $table->dropColumn('is_home_area');
        });
    }
};
