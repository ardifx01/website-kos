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
            
            // Personal Information
            $table->string('nama_lengkap');
            $table->string('email');
            $table->string('nomor_hp');
            $table->string('tipe_kamar');
            
            // Complaint Information
            $table->string('subjek');
            $table->string('kategori');
            $table->text('deskripsi');
            
            // Status and Management
            $table->enum('status_komplain', ['Open', 'In Progress', 'Resolved', 'Closed'])->default('Open');
            $table->string('token_used')->nullable()->index();
            
            // Admin Response
            $table->text('admin_response')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->unsignedBigInteger('responded_by')->nullable();
            
            // Metadata
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->index(['status_komplain', 'created_at']);
            $table->index(['kategori', 'created_at']);
            $table->index('email');
            $table->index('created_at');
            
            // Foreign key constraint
            $table->foreign('responded_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaint_forms');
        Schema::dropIfExists('booking_forms');
    }
};
