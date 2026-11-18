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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->date('tanggal_bayar');
            $table->enum('jenis_pembayaran', ['dp', 'cicilan', 'pelunasan']);
            $table->decimal('nominal_bayar', 12, 2);
            $table->string('metode_pembayaran');
            $table->string('bukti_bayar')->nullable();
            $table->enum('status_verifikasi', ['verified', 'pending'])->default('verified');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
