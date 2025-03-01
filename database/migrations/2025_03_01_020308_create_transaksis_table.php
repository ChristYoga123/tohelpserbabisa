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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->foreignId('voucher_id')->nullable()->constrained();
            $table->enum('jenis', ['ojek', 'taxi', 'bersih-rumah', 'angkutan', 'custom']);
            $table->string('jasa');
            $table->unsignedBigInteger('total_harga')->nullable();
            $table->enum('status_tugas', ['belum', 'proses', 'selesai'])->default('belum');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
