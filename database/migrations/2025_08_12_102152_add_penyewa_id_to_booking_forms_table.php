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
        Schema::table('booking_forms', function (Blueprint $table) {
            $table->unsignedBigInteger('penyewa_id')->nullable()->after('id');
            // jika ingin relasi foreign key:
            $table->foreign('penyewa_id')->references('id')->on('penyewas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_forms', function (Blueprint $table) {
            $table->dropForeign(['penyewa_id']);
            $table->dropColumn('penyewa_id');
        });
    }
};
