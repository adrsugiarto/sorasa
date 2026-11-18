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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_order')->unique();
            $table->foreignId('open_po_id')->nullable()->constrained('open_pos')->nullOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->date('tanggal_pemesanan');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('diskon', 12, 2)->default(0);
            $table->decimal('ongkos_kirim', 12, 2)->default(0);
            $table->decimal('total_tagihan', 12, 2)->default(0);
            $table->enum('status_pembayaran', ['belum_bayar', 'dp', 'lunas'])->default('belum_bayar');
            $table->enum('status_order', [
                'menunggu_konfirmasi',
                'diproses',
                'siap_kirim',
                'selesai',
                'dibatalkan',
            ])->default('menunggu_konfirmasi');
            $table->enum('order_source', ['admin', 'storefront'])->default('admin');
            $table->text('keterangan_pesanan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
