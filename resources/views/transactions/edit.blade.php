@extends('layouts.app')
@section('title', 'Edit Transaksi')
@section('page-title', 'Edit Transaksi')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card p-8">
        <div class="flex items-center gap-3 mb-7">
            <div class="w-11 h-11 rounded-xl bg-indigo-100 flex items-center justify-center text-xl">✏️</div>
            <div>
                <h3 class="font-display font-bold text-slate-800 text-lg">Edit Transaksi</h3>
                <p class="text-slate-400 text-sm">Perbarui detail transaksi</p>
            </div>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('transactions.update', $transaction) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="form-label">Kategori</label>
                <select name="category_id" id="category_id" class="form-input" required onchange="updateType(this)">
                    <option value="">— Pilih Kategori —</option>
                    <optgroup label="📈 Pemasukan">
                        @foreach($categories->where('type', 'income') as $cat)
                            <option value="{{ $cat->id }}" data-type="income" {{ $transaction->category_id == $cat->id ? 'selected' : '' }}>
                                {{ $cat->icon }} {{ $cat->name }}
                            </option>
                        @endforeach
                    </optgroup>
                    <optgroup label="📉 Pengeluaran">
                        @foreach($categories->where('type', 'expense') as $cat)
                            <option value="{{ $cat->id }}" data-type="expense" {{ $transaction->category_id == $cat->id ? 'selected' : '' }}>
                                {{ $cat->icon }} {{ $cat->name }}
                            </option>
                        @endforeach
                    </optgroup>
                </select>
            </div>

            <div id="type-display" class="{{ $transaction->type ? '' : 'hidden' }}">
                <label class="form-label">Tipe Transaksi</label>
                <div id="type-badge" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold
                    {{ $transaction->type === 'income' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-600' }}">
                    {{ $transaction->type === 'income' ? '📈 Pemasukan' : '📉 Pengeluaran' }}
                </div>
                <input type="hidden" name="type" id="type-input" value="{{ $transaction->type }}">
            </div>

            <div>
                <label class="form-label">Jumlah (Rp)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-semibold text-sm">Rp</span>
                    <input type="number" name="amount" value="{{ old('amount', $transaction->amount) }}" class="form-input pl-10" min="1" required>
                </div>
            </div>

            <div>
                <label class="form-label">Deskripsi (opsional)</label>
                <input type="text" name="description" value="{{ old('description', $transaction->description) }}" class="form-input" placeholder="Deskripsi transaksi...">
            </div>

            <div>
                <label class="form-label">Tanggal Transaksi</label>
                <input type="date" name="transaction_date" value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}" class="form-input" required>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary flex-1 text-center">Simpan Perubahan</button>
                <a href="{{ route('transactions.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-all">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function updateType(select) {
    const option = select.options[select.selectedIndex];
    const type = option.dataset.type;
    const typeDisplay = document.getElementById('type-display');
    const typeBadge = document.getElementById('type-badge');
    const typeInput = document.getElementById('type-input');
    if (type) {
        typeDisplay.classList.remove('hidden');
        typeInput.value = type;
        if (type === 'income') {
            typeBadge.className = 'inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold bg-emerald-100 text-emerald-700';
            typeBadge.innerHTML = '📈 Pemasukan';
        } else {
            typeBadge.className = 'inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold bg-rose-100 text-rose-600';
            typeBadge.innerHTML = '📉 Pengeluaran';
        }
    }
}
</script>
@endpush
@endsection
