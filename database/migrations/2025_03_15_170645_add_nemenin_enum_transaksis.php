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
        $enumJenis = ['ojek', 'taxi', 'bersih-rumah', 'angkutan', 'custom', 'bantuan-online', 'jastip', 'daily-activity', 'jasa-nemenin'];

        Schema::table('transaksis', function (Blueprint $table) use ($enumJenis) {
            $table->enum('jenis', $enumJenis)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $enumJenis = ['ojek', 'taxi', 'bersih-rumah', 'angkutan', 'custom', 'bantuan-online', 'jastip', 'daily-activity'];

        Schema::table('transaksis', function (Blueprint $table) use ($enumJenis) {
            $table->enum('jenis', $enumJenis)->change();
        });
    }
};
