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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('description'); // Untuk nama barang/keperluan
            $table->integer('amount'); // Untuk nominal uang
            $table->enum('type', ['masuk', 'keluar']); // Pemasukan atau pengeluaran
            $table->date('date'); // Tanggal transaksi
            $table->timestamps(); // Bawaan Laravel (created_at & updated_at)
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
