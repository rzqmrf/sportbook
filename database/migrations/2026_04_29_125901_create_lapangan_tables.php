<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lapangan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('jenis', ['Futsal', 'Badminton', 'Basket', 'Voli', 'Tenis', 'Lainnya']);
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_per_jam', 10, 2);
            $table->string('foto')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->time('jam_buka')->default('07:00:00');
            $table->time('jam_tutup')->default('22:00:00');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lapangan');
    }
};
