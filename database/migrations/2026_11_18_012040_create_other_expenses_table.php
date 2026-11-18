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
        Schema::create('other_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('open_po_id')->nullable()->constrained('open_pos')->nullOnDelete();
            $table->date('tanggal_pengeluaran');
            $table->enum('kategori_biaya', [
                'Listrik/Air',
                'Gas',
                'Tenaga Kerja',
                'Kemasan/Packaging',
                'Transportasi',
                'Lainnya',
            ])->default('Lainnya');
            $table->string('nama_pengeluaran');
            $table->decimal('nominal', 12, 2);
            $table->enum('metode_pembayaran', ['Cash', 'Transfer', 'QRIS', 'E-Wallet'])->default('Cash');
            $table->text('keterangan')->nullable();
            $table->string('bukti_nota')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('other_expenses');
    }
};
