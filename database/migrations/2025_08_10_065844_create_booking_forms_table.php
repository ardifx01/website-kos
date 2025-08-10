<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Booking
        Schema::create('booking_forms', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('email')->nullable();
            $table->string('nomor_hp')->nullable();
            $table->string('jenis_kelamin')->nullable(); // Laki-laki / Perempuan
            $table->string('pekerjaan')->nullable();
            $table->string('alamat_ktp')->nullable();
            $table->string('alamat_domisili')->nullable();

            // Info Booking
            $table->string('tipe_kamar')->nullable();
            $table->integer('jumlah_orang')->default(1);
            $table->date('tanggal_masuk'); // perubahan dari tanggal_mulai
            $table->string('status_booking')->default('pending'); // pending, approved, rejected
            $table->text('catatan')->nullable();

            // Audit trail
            $table->foreignId('created_by')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users', 'id')->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });

        // Tabel Komplain
        Schema::create('complaint_forms', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('email')->nullable();
            $table->string('nomor_hp')->nullable();
            $table->string('tipe_kamar')->nullable();
            $table->string('subjek');
            $table->enum('kategori', [
                'kebersihan',
                'fasilitas',
                'pelayanan',
                'keamanan',
                'lainnya'
            ])->default('lainnya');
            $table->text('deskripsi');
            $table->string('status_komplain')->default('baru'); // baru, diproses, selesai

            // Audit trail
            $table->foreignId('created_by')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users', 'id')->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaint_forms');
        Schema::dropIfExists('booking_forms');
    }
};
