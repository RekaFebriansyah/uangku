<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;

// Halaman utama
Route::get('/', [TransactionController::class, 'index']);

// URL rahasia untuk memproses penyimpanan data
Route::post('/simpan', [TransactionController::class, 'store'])->name('transaksi.store');

// URL untuk menghapus data berdasarkan ID
Route::delete('/hapus/{id}', [TransactionController::class, 'destroy'])->name('transaksi.destroy');