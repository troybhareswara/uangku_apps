@extends('layouts.app')
@section('title', 'Transaksi')
@section('page-title', 'Riwayat Transaksi')

@section('content')

{{-- Filter Bar --}}
<div class="card p-5 mb-6">
    <form method="GET" action="{{ route('transactions.index') }}" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="form-label">Tipe</label>
            <select name="type" class="form-input w-auto">
                <option value="">Semua</option>
                <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>Pemasukan</option>
                <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Pengeluaran</option>
            </select>
        </div>
        <div>
            <label class="form-label">Bulan</label>
            <select name="month" class="form-input w-auto">
                <option value="">Semua Bulan</option>
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>
        </div>
        <div>
            <label class="form-label">Tahun</label>
            <select name="year" class="form-input w-auto">
                @for($y = now()->year; $y >= now()->year - 3; $y--)
                    <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div>
            <label class="form-label">Kategori</label>
            <select name="category_id" class="form-input w-auto">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->icon }} {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn-primary">Filter</button>
            <a href="{{ route('transactions.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-all">Reset</a>
        </div>
    </form>
</div>

{{-- Summary Row --}}
<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="card p-4 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-emerald-100 flex items-center justify-center text-xl">📈</div>
        <div>
            <p class="text-xs text-slate-400 font-medium">Total Pemasukan</p>
            <p class="text-emerald-600 font-bold text-lg">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
        </div>
    </div>
    <div class="card p-4 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-rose-100 flex items-center justify-center text-xl">📉</div>
        <div>
            <p class="text-xs text-slate-400 font-medium">Total Pengeluaran</p>
            <p class="text-rose-500 font-bold text-lg">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
        </div>
    </div>
</div>

{{-- Transactions Table --}}
<div class="card overflow-hidden">
    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
        <h3 class="font-display font-bold text-slate-800">Daftar Transaksi</h3>
        <span class="text-sm text-slate-400">{{ $transactions->total() }} transaksi</span>
    </div>

    @if($transactions->isEmpty())
        <div class="text-center py-16">
            <p class="text-5xl mb-3">📭</p>
            <p class="text-slate-500 font-medium">Tidak ada transaksi ditemukan</p>
            <a href="{{ route('transactions.create') }}" class="inline-block mt-4 btn-primary">+ Tambah Transaksi</a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-xs text-slate-400 font-semibold uppercase tracking-wider bg-slate-50/50">
                        <th class="px-5 py-3 text-left">Tanggal</th>
                        <th class="px-5 py-3 text-left">Deskripsi</th>
                        <th class="px-5 py-3 text-left">Kategori</th>
                        <th class="px-5 py-3 text-left">Tipe</th>
                        <th class="px-5 py-3 text-right">Jumlah</th>
                        <th class="px-5 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($transactions as $trx)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-5 py-4 text-sm text-slate-500">{{ $trx->transaction_date->format('d M Y') }}</td>
                        <td class="px-5 py-4">
                            <p class="text-sm font-semibold text-slate-700">{{ $trx->description ?: '-' }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <span class="flex items-center gap-2 text-sm text-slate-600">
                                <span class="text-base">{{ $trx->category->icon }}</span>
                                {{ $trx->category->name }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            @if($trx->type === 'income')
                                <span class="badge-income">Pemasukan</span>
                            @else
                                <span class="badge-expense">Pengeluaran</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right font-bold text-sm {{ $trx->type === 'income' ? 'text-emerald-600' : 'text-rose-500' }}">
                            {{ $trx->type === 'income' ? '+' : '-' }}Rp {{ number_format($trx->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('transactions.edit', $trx) }}" class="text-xs text-indigo-500 hover:text-indigo-700 font-semibold px-3 py-1.5 rounded-lg hover:bg-indigo-50 transition-all">Edit</a>
                                <form action="{{ route('transactions.destroy', $trx) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-rose-500 hover:text-rose-700 font-semibold px-3 py-1.5 rounded-lg hover:bg-rose-50 transition-all">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="p-5 border-t border-slate-100">
                {{ $transactions->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
