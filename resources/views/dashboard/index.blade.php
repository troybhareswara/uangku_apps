@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Summary Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
    {{-- Balance Card --}}
    <div class="balance-card rounded-2xl p-6 text-white shadow-xl shadow-indigo-200">
        <div class="flex items-center justify-between mb-4">
            <p class="text-indigo-200 text-sm font-medium">Saldo Bulan Ini</p>
            <span class="text-2xl">💰</span>
        </div>
        <p class="font-display text-3xl font-bold">Rp {{ number_format($balance, 0, ',', '.') }}</p>
        <p class="text-indigo-200 text-xs mt-2">{{ now()->translatedFormat('F Y') }}</p>
    </div>

    {{-- Income Card --}}
    <div class="income-card rounded-2xl p-6 text-white shadow-xl shadow-emerald-200">
        <div class="flex items-center justify-between mb-4">
            <p class="text-emerald-100 text-sm font-medium">Total Pemasukan</p>
            <span class="text-2xl">📈</span>
        </div>
        <p class="font-display text-3xl font-bold">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
        <p class="text-emerald-100 text-xs mt-2">Bulan {{ now()->translatedFormat('F Y') }}</p>
    </div>

    {{-- Expense Card --}}
    <div class="expense-card rounded-2xl p-6 text-white shadow-xl shadow-rose-200">
        <div class="flex items-center justify-between mb-4">
            <p class="text-rose-100 text-sm font-medium">Total Pengeluaran</p>
            <span class="text-2xl">📉</span>
        </div>
        <p class="font-display text-3xl font-bold">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
        <p class="text-rose-100 text-xs mt-2">Bulan {{ now()->translatedFormat('F Y') }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Chart --}}
    <div class="lg:col-span-2 card p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="font-display font-bold text-slate-800 text-base">Grafik Keuangan</h3>
                <p class="text-slate-400 text-xs">6 bulan terakhir</p>
            </div>
            <div class="flex gap-4 text-xs text-slate-500">
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-indigo-500 inline-block"></span>Pemasukan</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-rose-400 inline-block"></span>Pengeluaran</span>
            </div>
        </div>
        <canvas id="financeChart" height="100"></canvas>
    </div>

    {{-- Recent Transactions --}}
    <div class="card p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-display font-bold text-slate-800 text-base">Transaksi Terbaru</h3>
            <a href="{{ route('transactions.index') }}" class="text-indigo-500 text-xs font-semibold hover:text-indigo-700">Lihat semua →</a>
        </div>

        @if($recentTransactions->isEmpty())
            <div class="text-center py-10">
                <p class="text-3xl mb-2">📭</p>
                <p class="text-slate-400 text-sm">Belum ada transaksi</p>
                <a href="{{ route('transactions.create') }}" class="inline-block mt-3 text-indigo-500 text-xs font-semibold">Tambah sekarang</a>
            </div>
        @else
            <div class="space-y-3">
                @foreach($recentTransactions as $trx)
                <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg flex-shrink-0"
                         style="background-color: {{ $trx->category->color }}20; color: {{ $trx->category->color }}">
                        {{ $trx->category->icon }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-slate-800 text-sm font-semibold truncate">{{ $trx->description ?: $trx->category->name }}</p>
                        <p class="text-slate-400 text-xs">{{ $trx->transaction_date->format('d M Y') }}</p>
                    </div>
                    <p class="text-sm font-bold flex-shrink-0 {{ $trx->type === 'income' ? 'text-emerald-600' : 'text-rose-500' }}">
                        {{ $trx->type === 'income' ? '+' : '-' }}Rp {{ number_format($trx->amount, 0, ',', '.') }}
                    </p>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>


{{-- Financial Planning Widget --}}
<div class="card p-6 mt-6">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
        <div>
            <h3 class="font-display font-bold text-slate-800" style="font-size:15px;">📊 Rekomendasi Alokasi Keuangan</h3>
            <p style="font-size:12px;color:#94a3b8;margin-top:2px;">Berdasarkan saldo & rencana keuangan bulan ini</p>
        </div>
        <a href="{{ route('investasi.index') }}" style="font-size:12px;font-weight:600;color:#6366f1;text-decoration:none;">Atur →</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Tabungan --}}
        <div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:14px;padding:16px;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
                <span style="font-size:18px;">🏦</span>
                <span style="font-size:12px;font-weight:600;color:#0369a1;">Tabungan</span>
                <span style="margin-left:auto;font-size:11px;font-weight:700;color:#0369a1;background:#e0f2fe;padding:2px 8px;border-radius:20px;">{{ $financialPlan->saving_percent }}%</span>
            </div>
            <div style="font-size:20px;font-weight:700;color:#0c4a6e;">Rp {{ number_format($planAllocations['saving'], 0, ',', '.') }}</div>
            <div style="height:4px;background:#bae6fd;border-radius:999px;margin-top:10px;">
                <div style="height:100%;width:{{ $financialPlan->saving_percent }}%;background:#0ea5e9;border-radius:999px;"></div>
            </div>
        </div>

        {{-- Investasi --}}
        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:14px;padding:16px;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
                <span style="font-size:18px;">📈</span>
                <span style="font-size:12px;font-weight:600;color:#15803d;">Investasi</span>
                <span style="margin-left:auto;font-size:11px;font-weight:700;color:#15803d;background:#dcfce7;padding:2px 8px;border-radius:20px;">{{ $financialPlan->investment_percent }}%</span>
            </div>
            <div style="font-size:20px;font-weight:700;color:#14532d;">Rp {{ number_format($planAllocations['investment'], 0, ',', '.') }}</div>
            <div style="height:4px;background:#bbf7d0;border-radius:999px;margin-top:10px;">
                <div style="height:100%;width:{{ $financialPlan->investment_percent }}%;background:#22c55e;border-radius:999px;"></div>
            </div>
        </div>

        {{-- Belanja --}}
        <div style="background:#fff7ed;border:1px solid #fed7aa;border-radius:14px;padding:16px;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
                <span style="font-size:18px;">🛍️</span>
                <span style="font-size:12px;font-weight:600;color:#c2410c;">Belanja</span>
                <span style="margin-left:auto;font-size:11px;font-weight:700;color:#c2410c;background:#ffedd5;padding:2px 8px;border-radius:20px;">{{ $financialPlan->spending_percent }}%</span>
            </div>
            <div style="font-size:20px;font-weight:700;color:#7c2d12;">Rp {{ number_format($planAllocations['spending'], 0, ',', '.') }}</div>
            <div style="height:4px;background:#fed7aa;border-radius:999px;margin-top:10px;">
                <div style="height:100%;width:{{ $financialPlan->spending_percent }}%;background:#f97316;border-radius:999px;"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const ctx = document.getElementById('financeChart').getContext('2d');
const labels = @json($monthlyData->pluck('month'));
const incomeData = @json($monthlyData->pluck('income'));
const expenseData = @json($monthlyData->pluck('expense'));

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Pemasukan',
                data: incomeData,
                backgroundColor: 'rgba(99, 102, 241, 0.85)',
                borderRadius: 8,
                borderSkipped: false,
            },
            {
                label: 'Pengeluaran',
                data: expenseData,
                backgroundColor: 'rgba(244, 63, 94, 0.75)',
                borderRadius: 8,
                borderSkipped: false,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return ' Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                    }
                }
            }
        },
        scales: {
            x: {
                grid: { display: false },
                border: { display: false },
                ticks: { color: '#94a3b8', font: { size: 11 } }
            },
            y: {
                grid: { color: '#f1f5f9' },
                border: { display: false },
                ticks: {
                    color: '#94a3b8',
                    font: { size: 11 },
                    callback: function(value) {
                        return 'Rp ' + new Intl.NumberFormat('id-ID', {notation: 'compact'}).format(value);
                    }
                }
            }
        }
    }
});
</script>
@endpush
