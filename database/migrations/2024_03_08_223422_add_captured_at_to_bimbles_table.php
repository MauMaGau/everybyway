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
        Schema::table('bimbles', function (Blueprint $table) {
            $table->timestamp('started_at')->after('user_id');
            $table->timestamp('ended_at')->after('started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bimbles', function (Blueprint $table) {
            $table->dropColumn(['started_at', 'ended_at']);
        });
    }
};
