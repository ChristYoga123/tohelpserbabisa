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
        Schema::create('tarif_jaraks', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['Motor', 'Mobil']);
            $table->double('jarak_min');
            $table->double('jarak_max')->nullable();
            $table->unsignedBigInteger('harga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarif_jaraks');
    }
};
