<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Booking Kamar
        Schema::create('booking_kamar', function (Blueprint $table) {
            $table->bigIncrements('idBooking');
            $table->unsignedBigInteger('idKamar');
            $table->foreign('idKamar')->references('idKamar')->on('kamar')->cascadeOnDelete();

            $table->date('tanggalBooking');
            $table->string('status');

            // Audit trail
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });

        // Pembayaran
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->bigIncrements('idPembayaran');
            $table->unsignedBigInteger('idBooking')->nullable();
            $table->foreign('idBooking')->references('idBooking')->on('booking_kamar')->nullOnDelete();

            $table->double('jumlah');
            $table->date('tanggalBayar');
            $table->string('metodePembayaran');
            $table->string('status');
            $table->string('buktiTransfer')->nullable();

            // Audit trail
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });

        // Komunikasi Penting
        Schema::create('komunikasi_penting', function (Blueprint $table) {
            $table->bigIncrements('idKomunikasi');
            $table->date('tanggalKomunikasi');
            $table->text('catatan');

            // Audit trail
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });

        // Komplain
        Schema::create('komplain', function (Blueprint $table) {
            $table->bigIncrements('idKomplain');
            $table->date('tanggalKomplain');
            $table->text('deskripsi');
            $table->string('status');

            // Audit trail
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('booking_kamar');
        Schema::dropIfExists('komunikasi_penting');
        Schema::dropIfExists('komplain');
    }
};
