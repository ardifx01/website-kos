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
            $table->string('nomorKamar');
            $table->string('tipeKamar');
            $table->double('hargaSewa');
            $table->string('status');

            // Audit trail
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });

        // Tabel Fasilitas Kamar
        Schema::create('fasilitas_kamar', function (Blueprint $table) {
            $table->bigIncrements('idFasilitas');
            $table->unsignedBigInteger('idKamar');
            $table->string('namaFasilitas');
            $table->string('kondisi');

            // Relasi manual karena primary key di kamar bukan 'id'
            $table->foreign('idKamar')->references('idKamar')->on('kamar')->cascadeOnDelete();

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
        Schema::dropIfExists('fasilitas_kamar');
        Schema::dropIfExists('kamar');
    }
};
