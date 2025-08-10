<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_keuangan', function (Blueprint $table) {
            $table->id('idLaporan');
            $table->string('periode');
            $table->double('totalPemasukan')->default(0);
            $table->double('totalPengeluaran')->default(0);

            // Audit trail
            $table->foreignId('created_by')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users', 'id')->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('export_laporan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idLaporan')->constrained('laporan_keuangan', 'idLaporan')->cascadeOnDelete();
            $table->string('format');
            $table->string('lokasiFile');

            // Audit trail
            $table->foreignId('created_by')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users', 'id')->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('riwayat_aktivitas', function (Blueprint $table) {
            $table->id('idAktivitas');
            $table->date('tanggalAktivitas');
            $table->text('deskripsi');

            // Audit trail
            $table->foreignId('created_by')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users', 'id')->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('pengingat_pembayaran', function (Blueprint $table) {
            $table->id('idPengingat');
            $table->date('tanggalJatuhTempo');
            $table->string('statusPengingat');

            // Audit trail
            $table->foreignId('created_by')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users', 'id')->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('dashboard', function (Blueprint $table) {
            $table->id();
            $table->integer('totalPenyewa')->default(0);
            $table->double('totalPendapatanBulanan')->default(0);
            $table->integer('totalKamarTersedia')->default(0);

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
        Schema::dropIfExists('export_laporan');
        Schema::dropIfExists('laporan_keuangan');
        Schema::dropIfExists('riwayat_aktivitas');
        Schema::dropIfExists('pengingat_pembayaran');
        Schema::dropIfExists('dashboard');
    }
};
