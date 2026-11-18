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
        Schema::create('open_pos', function (Blueprint $table) {
            $table->id();
            $table->string('nama_po');
            $table->string('kode_po')->unique();
            $table->date('tanggal_buka');
            $table->date('tanggal_tutup');
            $table->date('tanggal_pengiriman');
            $table->enum('status', ['draft', 'open', 'closed', 'completed'])->default('open');
            $table->boolean('is_published')->default(true);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('open_pos');
    }
};
