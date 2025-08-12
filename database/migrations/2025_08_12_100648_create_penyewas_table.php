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
        Schema::create('penyewas', function (Blueprint $table) {
            $table->id();
            
            // Data personal
            $table->string('nama_lengkap');
            $table->string('email')->nullable();
            $table->string('nomor_hp')->nullable();
            $table->string('jenis_kelamin')->nullable(); // Laki-laki / Perempuan
            $table->string('pekerjaan')->nullable();
            $table->string('alamat_ktp')->nullable();
            $table->string('alamat_domisili')->nullable();

            // Info Sewa
            $table->string('tipe_kamar')->nullable();
            $table->integer('jumlah_orang')->default(1);
            $table->date('tanggal_masuk');
            $table->string('status_sewa')->default('aktif'); // aktif, selesai, batal
            $table->text('catatan')->nullable();

            // Relasi ke booking form (opsional)
            $table->foreignId('booking_form_id')->nullable()->constrained('booking_forms')->nullOnDelete();

            // Audit trail
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyewas');
    }
};
