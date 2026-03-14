@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Summary Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="balance-card rounded-2xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between mb-3">
            <p style="font-size:12px;font-weight:600;color:rgba(255,255,255,0.75);text-transform:uppercase;letter-spacing:0.05em;">Saldo Bulan Ini</p>
            <span style="font-size:22px;">💰</span>
        </div>
        <p class="font-display font-bold" style="font-size:clamp(20px,5vw,28px);">Rp {{ number_format($balance, 0, ',', '.') }}</p>
        <p style="font-size:11px;color:rgba(255,255,255,0.6);margin-top:4px;">{{ now()->translatedFormat('F Y') }}</p>
    </div>
    <div class="income-card rounded-2xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between mb-3">
            <p style="font-size:12px;font-weight:600;color:rgba(255,255,255,0.75);text-transform:uppercase;letter-spacing:0.05em;">Total Pemasukan</p>
            <span style="font-size:22px;">📈</span>
        </div>
        <p class="font-display font-bold" style="font-size:clamp(20px,5vw,28px);">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
        <p style="font-size:11px;color:rgba(255,255,255,0.6);margin-top:4px;">{{ now()->translatedFormat('F Y') }}</p>
    </div>
    <div class="expense-card rounded-2xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between mb-3">
            <p style="font-size:12px;font-weight:600;color:rgba(255,255,255,0.75);text-transform:uppercase;letter-spacing:0.05em;">Total Pengeluaran</p>
            <span style="font-size:22px;">📉</span>
        </div>
        <p class="font-display font-bold" style="font-size:clamp(20px,5vw,28px);">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
        <p style="font-size:11px;color:rgba(255,255,255,0.6);margin-top:4px;">{{ now()->translatedFormat('F Y') }}</p>
    </div>
</div>

{{-- Financial Plan --}}
<div class="card p-5 mb-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="font-display font-bold text-slate-800" style="font-size:14px;">📊 Alokasi Keuangan</h3>
            <p style="font-size:11px;color:#94a3b8;margin-top:2px;">Rencana bulan ini</p>
        </div>
        <a href="{{ route('investasi.index') }}" style="font-size:12px;font-weight:600;color:#6366f1;text-decoration:none;">Atur →</a>
    </div>
    <div class="grid grid-cols-3 gap-3">
        <div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:12px;padding:12px;text-align:center;">
            <div style="font-size:18px;margin-bottom:4px;">🏦</div>
            <div style="font-size:11px;font-weight:600;color:#0369a1;margin-bottom:2px;">Tabungan</div>
            <div style="font-size:13px;font-weight:700;color:#0c4a6e;">Rp {{ number_format($planAllocations['saving'], 0, ',', '.') }}</div>
            <div style="font-size:10px;color:#7dd3fc;margin-top:2px;">{{ $financialPlan->saving_percent }}%</div>
        </div>
        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:12px;text-align:center;">
            <div style="font-size:18px;margin-bottom:4px;">📈</div>
            <div style="font-size:11px;font-weight:600;color:#15803d;margin-bottom:2px;">Investasi</div>
            <div style="font-size:13px;font-weight:700;color:#14532d;">Rp {{ number_format($planAllocations['investment'], 0, ',', '.') }}</div>
            <div style="font-size:10px;color:#86efac;margin-top:2px;">{{ $financialPlan->investment_percent }}%</div>
        </div>
        <div style="background:#fff7ed;border:1px solid #fed7aa;border-radius:12px;padding:12px;text-align:center;">
            <div style="font-size:18px;margin-bottom:4px;">🛍️</div>
            <div style="font-size:11px;font-weight:600;color:#c2410c;margin-bottom:2px;">Belanja</div>
            <div style="font-size:13px;font-weight:700;color:#7c2d12;">Rp {{ number_format($planAllocations['spending'], 0, ',', '.') }}</div>
            <div style="font-size:10px;color:#fdba74;margin-top:2px;">{{ $financialPlan->spending_percent }}%</div>
        </div>
    </div>
</div>

{{-- Chart (hidden on mobile, shown on md+) --}}
<div class="card p-5 mb-6 hidden md:block">
    <div class="flex items-center justify-between mb-4">
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
<div class="card overflow-hidden">
    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
        <h3 class="font-display font-bold text-slate-800 text-base">Transaksi Terbaru</h3>
        <a href="{{ route('transactions.index') }}" class="text-indigo-500 text-xs font-semibold">Lihat semua →</a>
    </div>

    @if($recentTransactions->isEmpty())
        <div class="text-center py-10">
            <p class="text-3xl mb-2">📭</p>
            <p class="text-slate-400 text-sm">Belum ada transaksi</p>
            <a href="{{ route('transactions.create') }}" class="inline-block mt-3 text-indigo-500 text-xs font-semibold">Tambah sekarang</a>
        </div>
    @else
        <div style="display:flex;flex-direction:column;">
            @foreach($recentTransactions as $trx)
            <div style="display:flex;align-items:center;gap:12px;padding:14px 20px;border-bottom:1px solid #f8fafc;">
                <div style="width:42px;height:42px;border-radius:12px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:18px;background-color:{{ $trx->category->color }}20;">
                    {{ $trx->category->icon }}
                </div>
                <div style="flex:1;min-width:0;">
                    <p style="font-size:13px;font-weight:600;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $trx->description ?: $trx->category->name }}</p>
                    <p style="font-size:11px;color:#94a3b8;margin-top:2px;">{{ $trx->transaction_date->format('d M Y') }} · {{ $trx->category->name }}</p>
                </div>
                <p style="font-size:13px;font-weight:700;flex-shrink:0;{{ $trx->type === 'income' ? 'color:#059669;' : 'color:#e11d48;' }}">
                    {{ $trx->type === 'income' ? '+' : '-' }}Rp {{ number_format($trx->amount, 0, ',', '.') }}
                </p>
            </div>
            @endforeach
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
const ctx = document.getElementById('financeChart');
if (ctx) {
    new Chart(ctx.getContext('2d'), {
        type: 'bar',
        data: {
            labels: @json($monthlyData->pluck('month')),
            datasets: [
                { label: 'Pemasukan', data: @json($monthlyData->pluck('income')), backgroundColor: 'rgba(99,102,241,0.85)', borderRadius: 8, borderSkipped: false },
                { label: 'Pengeluaran', data: @json($monthlyData->pluck('expense')), backgroundColor: 'rgba(244,63,94,0.75)', borderRadius: 8, borderSkipped: false }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: c => ' Rp ' + new Intl.NumberFormat('id-ID').format(c.raw) } }
            },
            scales: {
                x: { grid: { display: false }, border: { display: false }, ticks: { color: '#94a3b8', font: { size: 11 } } },
                y: { grid: { color: '#f1f5f9' }, border: { display: false }, ticks: { color: '#94a3b8', font: { size: 11 }, callback: v => 'Rp ' + new Intl.NumberFormat('id-ID', {notation:'compact'}).format(v) } }
            }
        }
    });
}
</script>
@endpush