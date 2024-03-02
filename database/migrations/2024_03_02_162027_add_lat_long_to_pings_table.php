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
            $table->float('lat', 11, 2); // (+/- 90)
            $table->float('lon', 11, 3); // (+/- 180)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pings', function (Blueprint $table) {
            $table->dropColumn(['lat', 'lon']);
        });
    }
};
