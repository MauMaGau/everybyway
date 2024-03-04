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
            $table->integer('distance_from_last_ping')->after('lon');

            // Let's populate the legacy data
            $pings = \App\Models\Ping::get();

            $lastPing = null; // No variable type hinting yet :(

            $pings->each(function(\App\Models\Ping $ping) use (&$lastPing) {
                if ($lastPing) {
                    $distance = \App\Helpers\GeoHelper::distance($lastPing->geo, $ping->geo);
                    $ping->distance_from_last_ping = $distance;
                }

                $lastPing = $ping;
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pings', function (Blueprint $table) {
            $table->dropColumn('distance_from_last_ping');
        });
    }
};
