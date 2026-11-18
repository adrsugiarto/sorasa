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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('open_po_id')->nullable()->constrained('open_pos')->nullOnDelete();
            $table->date('tanggal_pembelian');
            $table->string('nama_toko_supplier');
            $table->string('nama_barang');
            $table->decimal('jumlah', 10, 2);
            $table->string('satuan');
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('total_pembelian', 12, 2);
            $table->enum('metode_pembayaran', ['Cash', 'Transfer', 'QRIS', 'E-Wallet'])->default('Cash');
            $table->text('keterangan')->nullable();
            $table->string('foto_nota')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
