<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UangKu - Dashboard</title>
    <!-- Memanggil Tailwind CSS lewat CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 p-6 md:p-10 font-sans text-slate-800">

    <div class="max-w-5xl mx-auto">
        <h1 class="text-3xl font-bold mb-8 text-slate-900">UangKu Dashboard</h1>

        <!-- Notifikasi Sukses -->
        @if(session('success'))
        <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative mb-6">
            {{ session('success') }}
        </div>
        @endif

        <!-- Kartu Ringkasan -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
                <h3 class="text-slate-500 text-sm font-semibold mb-2">Total Saldo</h3>
                <!-- Ubah $saldo menjadi $totalSaldo -->
                <p class="text-3xl font-bold text-blue-600">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
                <h3 class="text-slate-500 text-sm font-semibold mb-2">Pemasukan</h3>
                <!-- Ubah $pemasukan menjadi $totalPemasukan -->
                <p class="text-2xl font-bold text-emerald-500">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
                <h3 class="text-slate-500 text-sm font-semibold mb-2">Pengeluaran</h3>
                <!-- Ubah $pengeluaran menjadi $totalPengeluaran -->
                <p class="text-2xl font-bold text-rose-500">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Form Input -->
            <div class="col-span-1 bg-white p-6 rounded-xl shadow-sm border border-slate-100 h-fit">
                <h2 class="text-xl font-bold mb-4">Catat Transaksi</h2>
                <form action="{{ route('transaksi.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
                        <input type="text" name="description" class="w-full border border-slate-300 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Beli Kopi" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nominal (Rp)</label>
                        <input type="number" name="amount" class="w-full border border-slate-300 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: 25000" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Jenis</label>
                        <select name="type" class="w-full border border-slate-300 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="keluar">Pengeluaran</option>
                            <option value="masuk">Pemasukan</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal</label>
                        <input type="date" name="date" class="w-full border border-slate-300 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition duration-200">Simpan Transaksi</button>
                </form>
            </div>

            <!-- Tabel Riwayat -->
            <div class="col-span-2 bg-white p-6 rounded-xl shadow-sm border border-slate-100 overflow-x-auto">
                <h2 class="text-xl font-bold mb-4">Riwayat Transaksi</h2>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500">
                            <th class="py-3 px-2">Tanggal</th>
                            <th class="py-3 px-2">Deskripsi</th>
                            <th class="py-3 px-2 text-right">Nominal</th>
                            <th class="py-3 px-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trx)
                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                            <td class="py-3 px-2 text-slate-600">{{ \Carbon\Carbon::parse($trx->date)->format('d M Y') }}</td>
                            <td class="py-3 px-2 font-medium">{{ $trx->description }}</td>
                            <td class="py-3 px-2 text-right font-bold {{ $trx->type == 'masuk' ? 'text-emerald-500' : 'text-rose-500' }}">
                                {{ $trx->type == 'masuk' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-2 text-center">
                                <form action="{{ route('transaksi.destroy', $trx->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-500 hover:text-rose-700 font-bold text-sm bg-rose-50 px-3 py-1 rounded-md">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-6 text-center text-slate-400">Belum ada transaksi. Ayo mulai mencatat!</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Kotak Rekap Bulanan -->
        <div class="mt-8 bg-white p-6 rounded-lg shadow-sm border border-slate-100 max-w-6xl mx-auto mb-10">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Rekap Bulanan</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500 bg-slate-50">
                            <th class="py-3 px-4 rounded-tl-lg">Bulan</th>
                            <th class="py-3 px-4 text-right">Total Pemasukan</th>
                            <th class="py-3 px-4 text-right">Total Pengeluaran</th>
                            <th class="py-3 px-4 text-right rounded-tr-lg">Sisa (Net)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rekapBulanan as $rekap)
                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                            <!-- Mengubah format "2026-07" menjadi "Juli 2026" -->
                            <td class="py-3 px-4 font-medium text-slate-700">
                                {{ \Carbon\Carbon::createFromFormat('Y-m', $rekap->bulan)->translatedFormat('F Y') }}
                            </td>
                            <td class="py-3 px-4 text-right font-medium text-emerald-500">
                                Rp {{ number_format($rekap->pemasukan, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-right font-medium text-rose-500">
                                Rp {{ number_format($rekap->pengeluaran, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-right font-bold {{ ($rekap->pemasukan - $rekap->pengeluaran) >= 0 ? 'text-blue-500' : 'text-rose-500' }}">
                                Rp {{ number_format($rekap->pemasukan - $rekap->pengeluaran, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-slate-400">Belum ada rekap bulanan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>