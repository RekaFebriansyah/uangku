<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // Fungsi untuk menampilkan dashboard utama
    public function index()
    {
        // 1. Ambil semua transaksi untuk tabel riwayat utama
        $transactions = Transaction::orderBy('date', 'desc')->get();
        
        // 2. Hitung total saldo (Global)
        $totalPemasukan = Transaction::where('type', 'masuk')->sum('amount');
        $totalPengeluaran = Transaction::where('type', 'keluar')->sum('amount');
        $totalSaldo = $totalPemasukan - $totalPengeluaran;

        // 3. FITUR BARU: Rekap Bulanan (Grouping menggunakan SQL)
        $rekapBulanan = Transaction::selectRaw('
                strftime("%Y-%m", date) as bulan,
                SUM(CASE WHEN type = "masuk" THEN amount ELSE 0 END) as pemasukan,
                SUM(CASE WHEN type = "keluar" THEN amount ELSE 0 END) as pengeluaran
            ')
            ->groupBy('bulan')
            ->orderBy('bulan', 'desc')
            ->get();

        // 4. Kirim semua data ke halaman dashboard
        return view('dashboard', compact('transactions', 'totalSaldo', 'totalPemasukan', 'totalPengeluaran', 'rekapBulanan'));
    }

    // Fungsi untuk memproses data dari form
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'description' => 'required',
            'amount' => 'required|numeric',
            'type' => 'required|in:masuk,keluar',
            'date' => 'required|date',
        ]);

        // Simpan ke database
        Transaction::create($request->all());

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Transaksi berhasil dicatat!');
    }

    // Fungsi untuk menghapus transaksi
    public function destroy($id)
    {
        // Cari data berdasarkan ID, lalu hapus
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Data transaksi berhasil dihapus!');
    }
}