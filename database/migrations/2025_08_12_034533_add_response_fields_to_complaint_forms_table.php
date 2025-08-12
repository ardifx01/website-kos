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
        Schema::table('complaint_forms', function (Blueprint $table) {
            $table->timestamp('responded_at')->nullable()->after('updated_by');
            $table->unsignedBigInteger('responded_by')->nullable()->after('responded_at');

            // kalau mau relasi ke users
            $table->foreign('responded_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaint_forms', function (Blueprint $table) {
            $table->dropForeign(['responded_by']);
            $table->dropColumn(['responded_at', 'responded_by']);
        });
    }
};
