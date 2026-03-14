@extends('layouts.app')
@section('title', 'Transaksi')
@section('page-title', 'Riwayat Transaksi')

@section('content')

{{-- Filter Bar --}}
<div class="card p-4 mb-5">
    <form method="GET" action="{{ route('transactions.index') }}">
        {{-- Mobile: 2 col grid --}}
        <div class="grid grid-cols-2 gap-3 mb-3">
            <div>
                <label class="form-label">Tipe</label>
                <select name="type" class="form-input">
                    <option value="">Semua</option>
                    <option value="income" {{ request('type')==='income'?'selected':'' }}>Pemasukan</option>
                    <option value="expense" {{ request('type')==='expense'?'selected':'' }}>Pengeluaran</option>
                </select>
            </div>
            <div>
                <label class="form-label">Bulan</label>
                <select name="month" class="form-input">
                    <option value="">Semua</option>
                    @for($m=1;$m<=12;$m++)
                        <option value="{{ $m }}" {{ request('month')==$m?'selected':'' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('M') }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="form-label">Tahun</label>
                <select name="year" class="form-input">
                    @for($y=now()->year;$y>=now()->year-3;$y--)
                        <option value="{{ $y }}" {{ request('year',now()->year)==$y?'selected':'' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="form-label">Kategori</label>
                <select name="category_id" class="form-input">
                    <option value="">Semua</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id')==$cat->id?'selected':'' }}>{{ $cat->icon }} {{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn-primary" style="flex:1;justify-content:center;">Filter</button>
            <a href="{{ route('transactions.index') }}" style="flex:1;display:flex;align-items:center;justify-content:center;border:1px solid #e2e8f0;border-radius:12px;color:#64748b;font-size:14px;font-weight:600;text-decoration:none;">Reset</a>
        </div>
    </form>
</div>

{{-- Summary --}}
<div class="grid grid-cols-2 gap-3 mb-5">
    <div class="card p-4 flex items-center gap-3">
        <div style="width:40px;height:40px;border-radius:12px;background:#d1fae5;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">📈</div>
        <div style="min-width:0;">
            <p style="font-size:11px;color:#94a3b8;font-weight:500;">Pemasukan</p>
            <p style="font-size:13px;font-weight:700;color:#059669;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
        </div>
    </div>
    <div class="card p-4 flex items-center gap-3">
        <div style="width:40px;height:40px;border-radius:12px;background:#ffe4e6;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">📉</div>
        <div style="min-width:0;">
            <p style="font-size:11px;color:#94a3b8;font-weight:500;">Pengeluaran</p>
            <p style="font-size:13px;font-weight:700;color:#e11d48;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
        </div>
    </div>
</div>

{{-- Transaction List --}}
<div class="card overflow-hidden">
    <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
        <h3 class="font-display font-bold text-slate-800" style="font-size:14px;">Daftar Transaksi</h3>
        <span style="font-size:12px;color:#94a3b8;">{{ $transactions->total() }} transaksi</span>
    </div>

    @if($transactions->isEmpty())
        <div class="text-center py-12">
            <p class="text-4xl mb-3">📭</p>
            <p class="text-slate-500 text-sm font-medium">Tidak ada transaksi</p>
            <a href="{{ route('transactions.create') }}" class="inline-block mt-4 btn-primary">+ Tambah</a>
        </div>
    @else
        {{-- Mobile: card list (always shown) --}}
        <div class="md:hidden">
            @foreach($transactions as $trx)
            <div style="display:flex;align-items:center;gap:12px;padding:14px 16px;border-bottom:1px solid #f8fafc;">
                <div style="width:44px;height:44px;border-radius:12px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:20px;background-color:{{ $trx->category->color }}20;">
                    {{ $trx->category->icon }}
                </div>
                <div style="flex:1;min-width:0;">
                    <p style="font-size:13px;font-weight:600;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $trx->description ?: $trx->category->name }}</p>
                    <div style="display:flex;align-items:center;gap:6px;margin-top:3px;">
                        <span style="font-size:10px;color:#94a3b8;">{{ $trx->transaction_date->format('d M Y') }}</span>
                        <span style="width:3px;height:3px;border-radius:50%;background:#cbd5e1;display:inline-block;"></span>
                        @if($trx->type === 'income')
                            <span class="badge-income" style="font-size:10px;padding:1px 7px;">Masuk</span>
                        @else
                            <span class="badge-expense" style="font-size:10px;padding:1px 7px;">Keluar</span>
                        @endif
                    </div>
                </div>
                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;flex-shrink:0;">
                    <p style="font-size:13px;font-weight:700;{{ $trx->type === 'income' ? 'color:#059669;' : 'color:#e11d48;' }}">
                        {{ $trx->type === 'income' ? '+' : '-' }}Rp {{ number_format($trx->amount, 0, ',', '.') }}
                    </p>
                    <div style="display:flex;gap:8px;">
                        <a href="{{ route('transactions.edit', $trx) }}" style="font-size:11px;color:#6366f1;font-weight:600;text-decoration:none;">Edit</a>
                        <form action="{{ route('transactions.destroy', $trx) }}" method="POST" onsubmit="return confirm('Hapus?')" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" style="font-size:11px;color:#e11d48;font-weight:600;background:none;border:none;cursor:pointer;padding:0;">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Desktop: table (hidden on mobile) --}}
        <div class="hidden md:block overflow-x-auto">
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
                        <td class="px-5 py-4"><p class="text-sm font-semibold text-slate-700">{{ $trx->description ?: '-' }}</p></td>
                        <td class="px-5 py-4">
                            <span class="flex items-center gap-2 text-sm text-slate-600">
                                <span class="text-base">{{ $trx->category->icon }}</span>{{ $trx->category->name }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            @if($trx->type === 'income')<span class="badge-income">Pemasukan</span>
                            @else<span class="badge-expense">Pengeluaran</span>@endif
                        </td>
                        <td class="px-5 py-4 text-right font-bold text-sm {{ $trx->type === 'income' ? 'text-emerald-600' : 'text-rose-500' }}">
                            {{ $trx->type === 'income' ? '+' : '-' }}Rp {{ number_format($trx->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('transactions.edit', $trx) }}" class="text-xs text-indigo-500 hover:text-indigo-700 font-semibold px-3 py-1.5 rounded-lg hover:bg-indigo-50 transition-all">Edit</a>
                                <form action="{{ route('transactions.destroy', $trx) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-rose-500 font-semibold px-3 py-1.5 rounded-lg hover:bg-rose-50 transition-all">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $transactions->links() }}
            </div>
        @endif
    @endif
</div>
@endsection