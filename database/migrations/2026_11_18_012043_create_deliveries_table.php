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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->date('tanggal_pengiriman');
            $table->string('jam_pengiriman')->nullable();
            $table->string('nama_penerima');
            $table->string('no_telepon_penerima');
            $table->text('alamat_lengkap');
            $table->text('patokan_lokasi')->nullable();
            $table->text('link_google_maps')->nullable();
            $table->enum('status_pengantaran', ['menunggu', 'dijadwalkan', 'dalam_pengiriman', 'terkirim'])->default('menunggu');
            $table->text('catatan_kurir')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
