<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Kamar
        Schema::create('kamar', function (Blueprint $table) {
            $table->bigIncrements('idKamar');
            $table->string('nomorKamar')->unique();
            
            // Ganti tipeKamar dan hargaSewa menjadi foreign key ke tipe_kamar
            $table->foreignId('tipe_kamar_id')->constrained('tipe_kamar')->cascadeOnDelete();
            
            // Status kamar (misal: tersedia, penuh, maintenance)
            $table->string('status')->default('tersedia');

            // Audit trail
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });

        // Tabel Histori Harga Renovasi
        Schema::create('histori_harga_renovasi', function (Blueprint $table) {
            $table->bigIncrements('idHistori');
            $table->unsignedBigInteger('idKamar');
            $table->date('tanggalPerubahan');
            $table->double('hargaSewaBaru');
            $table->text('deskripsiRenovasi')->nullable();

            // Relasi manual
            $table->foreign('idKamar')->references('idKamar')->on('kamar')->cascadeOnDelete();

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
        // Drop child table dulu biar foreign key gak error
        Schema::dropIfExists('histori_harga_renovasi');
        Schema::dropIfExists('kamar');
    }
};
