<?php

use App\Models\Ping;
use Carbon\Carbon;
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
            $table->timestamp('captured_at')->after('is_home_area');
        });

        Ping::all()->each(function(Ping $ping) {
            $ping->captured_at = Carbon::createFromFormat('Y/m/d H:i:s', $ping->created_at)->toDateTimeString();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pings', function (Blueprint $table) {
            $table->dropColumn('captured_at');
        });
    }
};
