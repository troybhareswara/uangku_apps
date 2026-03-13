@extends('layouts.app')
@section('title', 'Investasi')
@section('page-title', 'Rencana Keuangan')

@section('content')

{{-- Header Stats Row --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">

    <div class="balance-card rounded-2xl p-6 text-white shadow-xl shadow-indigo-200">
        <div class="flex items-center justify-between mb-4">
            <p style="font-size:12px;font-weight:600;color:rgba(255,255,255,0.75);text-transform:uppercase;letter-spacing:0.05em;">Total Saldo</p>
            <span class="text-2xl">💰</span>
        </div>
        <p class="font-display text-3xl font-bold">Rp {{ number_format($totalBalance, 0, ',', '.') }}</p>
        <p style="font-size:12px;color:rgba(255,255,255,0.6);margin-top:6px;">Pemasukan - Pengeluaran</p>
    </div>

    <div class="income-card rounded-2xl p-6 text-white shadow-xl shadow-emerald-200">
        <div class="flex items-center justify-between mb-4">
            <p style="font-size:12px;font-weight:600;color:rgba(255,255,255,0.75);text-transform:uppercase;letter-spacing:0.05em;">Saldo Bulan Ini</p>
            <span class="text-2xl">📅</span>
        </div>
        <p class="font-display text-3xl font-bold">Rp {{ number_format($monthBalance, 0, ',', '.') }}</p>
        <p style="font-size:12px;color:rgba(255,255,255,0.6);margin-top:6px;">{{ now()->translatedFormat('F Y') }}</p>
    </div>

    <div class="card p-6">
        <div class="flex items-center justify-between mb-4">
            <p style="font-size:12px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;">Total Alokasi</p>
            <span class="text-2xl">⚖️</span>
        </div>
        <p class="font-display text-3xl font-bold text-slate-800">{{ $plan->total_percent }}%</p>
        <p style="font-size:12px;margin-top:6px;font-weight:600;{{ $plan->total_percent == 100 ? 'color:#10b981;' : 'color:#f43f5e;' }}">
            {{ $plan->total_percent == 100 ? '✓ Alokasi sempurna' : '⚠ Harus tepat 100%' }}
        </p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

    {{-- LEFT --}}
    <div class="lg:col-span-2" style="display:flex;flex-direction:column;gap:20px;">

        {{-- Form --}}
        <div class="card p-7">
            <div class="flex items-center gap-3 mb-6">
                <div style="width:40px;height:40px;border-radius:12px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;font-size:18px;">⚙️</div>
                <div>
                    <div class="font-display font-bold text-slate-800" style="font-size:15px;">Atur Alokasi</div>
                    <div style="font-size:12px;color:#94a3b8;">Total harus = 100%</div>
                </div>
            </div>

            @if($errors->any())
                <div style="background:#fff1f2;border:1px solid #fecdd3;border-radius:10px;padding:12px 14px;margin-bottom:20px;color:#e11d48;font-size:13px;">
                    @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
                </div>
            @endif

            <form action="{{ route('investasi.update') }}" method="POST" id="planForm">
                @csrf

                {{-- Tabungan --}}
                <div style="margin-bottom:22px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                        <label style="font-size:13px;font-weight:600;color:#475569;">🏦 Tabungan</label>
                        <span id="savingVal" style="font-size:12px;font-weight:700;color:#2563eb;background:#eff6ff;padding:3px 10px;border-radius:20px;border:1px solid #bfdbfe;">{{ $plan->saving_percent }}%</span>
                    </div>
                    <input type="range" name="saving_percent" id="saving" min="0" max="100" step="1"
                        value="{{ old('saving_percent', $plan->saving_percent) }}"
                        oninput="updateSlider()" style="width:100%;accent-color:#3b82f6;">
                    <input type="number" id="saving_num" min="0" max="100" step="1"
                        value="{{ old('saving_percent', $plan->saving_percent) }}"
                        oninput="syncFromNumber('saving')" class="form-input" style="margin-top:8px;">
                </div>

                {{-- Investasi --}}
                <div style="margin-bottom:22px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                        <label style="font-size:13px;font-weight:600;color:#475569;">📈 Investasi</label>
                        <span id="investVal" style="font-size:12px;font-weight:700;color:#059669;background:#ecfdf5;padding:3px 10px;border-radius:20px;border:1px solid #a7f3d0;">{{ $plan->investment_percent }}%</span>
                    </div>
                    <input type="range" name="investment_percent" id="investment" min="0" max="100" step="1"
                        value="{{ old('investment_percent', $plan->investment_percent) }}"
                        oninput="updateSlider()" style="width:100%;accent-color:#10b981;">
                    <input type="number" id="investment_num" min="0" max="100" step="1"
                        value="{{ old('investment_percent', $plan->investment_percent) }}"
                        oninput="syncFromNumber('investment')" class="form-input" style="margin-top:8px;">
                </div>

                {{-- Belanja --}}
                <div style="margin-bottom:24px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                        <label style="font-size:13px;font-weight:600;color:#475569;">🛍️ Belanja</label>
                        <span id="spendVal" style="font-size:12px;font-weight:700;color:#e11d48;background:#fff1f2;padding:3px 10px;border-radius:20px;border:1px solid #fecdd3;">{{ $plan->spending_percent }}%</span>
                    </div>
                    <input type="range" name="spending_percent" id="spending" min="0" max="100" step="1"
                        value="{{ old('spending_percent', $plan->spending_percent) }}"
                        oninput="updateSlider()" style="width:100%;accent-color:#f43f5e;">
                    <input type="number" id="spending_num" min="0" max="100" step="1"
                        value="{{ old('spending_percent', $plan->spending_percent) }}"
                        oninput="syncFromNumber('spending')" class="form-input" style="margin-top:8px;">
                </div>

                {{-- Total --}}
                <div id="totalBar" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:12px 16px;margin-bottom:20px;display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:13px;color:#64748b;font-weight:600;">Total Persentase</span>
                    <span id="totalText" style="font-size:15px;font-weight:700;color:#0f172a;">100%</span>
                </div>

                <button type="submit" id="submitBtn" class="btn-primary" style="width:100%;justify-content:center;">
                    Simpan Rencana Keuangan
                </button>
            </form>
        </div>

        {{-- Alokasi Bulan Ini --}}
        <div class="card p-6">
            <div class="font-display font-bold text-slate-800 mb-1" style="font-size:14px;">Alokasi Bulan Ini</div>
            <div style="font-size:12px;color:#94a3b8;margin-bottom:18px;">{{ now()->translatedFormat('F Y') }}</div>
            <div style="display:flex;flex-direction:column;gap:10px;">
                <div style="display:flex;align-items:center;justify-content:space-between;padding:13px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;">
                    <div style="display:flex;align-items:center;gap:9px;">
                        <span style="font-size:17px;">🏦</span>
                        <div>
                            <div style="font-size:12px;font-weight:700;color:#1d4ed8;">Tabungan</div>
                            <div style="font-size:11px;color:#93c5fd;">{{ $plan->saving_percent }}% dari saldo</div>
                        </div>
                    </div>
                    <div style="font-size:13px;font-weight:700;color:#1d4ed8;">Rp {{ number_format($monthAllocations['saving'], 0, ',', '.') }}</div>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;padding:13px;background:#ecfdf5;border:1px solid #a7f3d0;border-radius:12px;">
                    <div style="display:flex;align-items:center;gap:9px;">
                        <span style="font-size:17px;">📈</span>
                        <div>
                            <div style="font-size:12px;font-weight:700;color:#065f46;">Investasi</div>
                            <div style="font-size:11px;color:#6ee7b7;">{{ $plan->investment_percent }}% dari saldo</div>
                        </div>
                    </div>
                    <div style="font-size:13px;font-weight:700;color:#065f46;">Rp {{ number_format($monthAllocations['investment'], 0, ',', '.') }}</div>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;padding:13px;background:#fff1f2;border:1px solid #fecdd3;border-radius:12px;">
                    <div style="display:flex;align-items:center;gap:9px;">
                        <span style="font-size:17px;">🛍️</span>
                        <div>
                            <div style="font-size:12px;font-weight:700;color:#9f1239;">Belanja</div>
                            <div style="font-size:11px;color:#fda4af;">{{ $plan->spending_percent }}% dari saldo</div>
                        </div>
                    </div>
                    <div style="font-size:13px;font-weight:700;color:#9f1239;">Rp {{ number_format($monthAllocations['spending'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT --}}
    <div class="lg:col-span-3" style="display:flex;flex-direction:column;gap:20px;">

        {{-- Pie Chart --}}
        <div class="card p-7">
            <div class="font-display font-bold text-slate-800 mb-1" style="font-size:15px;">Distribusi Alokasi</div>
            <div style="font-size:12px;color:#94a3b8;margin-bottom:24px;">Visualisasi persentase rencana keuangan</div>
            <div style="display:flex;align-items:center;justify-content:center;gap:40px;flex-wrap:wrap;">
                <div style="width:200px;height:200px;">
                    <canvas id="pieChart"></canvas>
                </div>
                <div style="display:flex;flex-direction:column;gap:16px;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:12px;height:12px;border-radius:3px;background:#3b82f6;flex-shrink:0;"></div>
                        <div>
                            <div style="font-size:13px;font-weight:600;color:#1e293b;">Tabungan</div>
                            <div style="font-size:12px;color:#94a3b8;" id="legend-saving">{{ $plan->saving_percent }}%</div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:12px;height:12px;border-radius:3px;background:#10b981;flex-shrink:0;"></div>
                        <div>
                            <div style="font-size:13px;font-weight:600;color:#1e293b;">Investasi</div>
                            <div style="font-size:12px;color:#94a3b8;" id="legend-invest">{{ $plan->investment_percent }}%</div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:12px;height:12px;border-radius:3px;background:#f43f5e;flex-shrink:0;"></div>
                        <div>
                            <div style="font-size:13px;font-weight:600;color:#1e293b;">Belanja</div>
                            <div style="font-size:12px;color:#94a3b8;" id="legend-spend">{{ $plan->spending_percent }}%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Progress Bars --}}
        <div class="card p-7">
            <div class="font-display font-bold text-slate-800 mb-1" style="font-size:15px;">Alokasi Total Saldo</div>
            <div style="font-size:12px;color:#94a3b8;margin-bottom:22px;">Dari total saldo Rp {{ number_format($totalBalance, 0, ',', '.') }}</div>
            <div style="display:flex;flex-direction:column;gap:20px;">
                @php $items = [
                    ['label'=>'Tabungan',  'key'=>'saving',     'bar'=>'#3b82f6', 'text'=>'#1d4ed8', 'icon'=>'🏦'],
                    ['label'=>'Investasi', 'key'=>'investment', 'bar'=>'#10b981', 'text'=>'#065f46', 'icon'=>'📈'],
                    ['label'=>'Belanja',   'key'=>'spending',   'bar'=>'#f43f5e', 'text'=>'#9f1239', 'icon'=>'🛍️'],
                ]; @endphp
                @foreach($items as $item)
                @php $percent = $item['key']==='saving' ? $plan->saving_percent : ($item['key']==='investment' ? $plan->investment_percent : $plan->spending_percent); @endphp
                <div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="font-size:16px;">{{ $item['icon'] }}</span>
                            <span style="font-size:13px;font-weight:600;color:#475569;">{{ $item['label'] }}</span>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-size:14px;font-weight:700;color:{{ $item['text'] }};">Rp {{ number_format($allocations[$item['key']], 0, ',', '.') }}</div>
                            <div style="font-size:11px;color:#94a3b8;">{{ $percent }}%</div>
                        </div>
                    </div>
                    <div style="height:8px;background:#f1f5f9;border-radius:999px;overflow:hidden;">
                        <div style="height:100%;width:{{ $percent }}%;background:{{ $item['bar'] }};border-radius:999px;transition:width 0.4s;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Tips --}}
        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:20px;padding:24px;">
            <div style="font-size:14px;font-weight:700;color:#15803d;margin-bottom:12px;">💡 Tips Keuangan</div>
            <div style="display:flex;flex-direction:column;gap:8px;font-size:13px;color:#475569;line-height:1.7;">
                <div>• <strong style="color:#166534;">Aturan 50/30/20</strong>: 50% kebutuhan, 30% keinginan, 20% tabungan</div>
                <div>• <strong style="color:#166534;">Investasi konsisten</strong>: Mulai dari 10–20% penghasilan setiap bulan</div>
                <div>• <strong style="color:#166534;">Dana darurat</strong>: Siapkan 3–6x pengeluaran bulanan sebelum investasi</div>
                <div>• <strong style="color:#166534;">Diversifikasi</strong>: Jangan taruh semua investasi di satu instrumen</div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const plan = {
    saving:     {{ $plan->saving_percent }},
    investment: {{ $plan->investment_percent }},
    spending:   {{ $plan->spending_percent }},
};

const pieChart = new Chart(document.getElementById('pieChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: ['Tabungan', 'Investasi', 'Belanja'],
        datasets: [{
            data: [plan.saving, plan.investment, plan.spending],
            backgroundColor: ['#3b82f6', '#10b981', '#f43f5e'],
            borderColor: '#ffffff',
            borderWidth: 3,
            hoverOffset: 8,
        }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: c => ` ${c.label}: ${c.raw}%` } }
        }
    }
});

function updateSlider() {
    const s = parseFloat(document.getElementById('saving').value)     || 0;
    const i = parseFloat(document.getElementById('investment').value)  || 0;
    const p = parseFloat(document.getElementById('spending').value)    || 0;
    const total = s + i + p;
    const ok = Math.round(total * 100) === 10000;

    document.getElementById('saving_num').value      = s;
    document.getElementById('investment_num').value   = i;
    document.getElementById('spending_num').value     = p;
    document.getElementById('savingVal').textContent  = s + '%';
    document.getElementById('investVal').textContent  = i + '%';
    document.getElementById('spendVal').textContent   = p + '%';
    document.getElementById('legend-saving').textContent = s + '%';
    document.getElementById('legend-invest').textContent = i + '%';
    document.getElementById('legend-spend').textContent  = p + '%';

    const totalText = document.getElementById('totalText');
    const totalBar  = document.getElementById('totalBar');
    totalText.textContent      = total + '%';
    totalText.style.color      = ok ? '#10b981' : '#f43f5e';
    totalBar.style.borderColor = ok ? '#a7f3d0' : '#fecdd3';
    totalBar.style.background  = ok ? '#f0fdf4' : '#fff1f2';

    const btn = document.getElementById('submitBtn');
    btn.disabled      = !ok;
    btn.style.opacity = ok ? '1' : '0.4';
    btn.style.cursor  = ok ? 'pointer' : 'not-allowed';

    pieChart.data.datasets[0].data = [s, i, p];
    pieChart.update();
}

function syncFromNumber(field) {
    document.getElementById(field).value = parseFloat(document.getElementById(field + '_num').value) || 0;
    updateSlider();
}

updateSlider();
</script>
@endpush