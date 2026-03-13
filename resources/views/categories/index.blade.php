@extends('layouts.app')
@section('title', 'Kategori')
@section('page-title', 'Kelola Kategori')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Add Category Form --}}
    <div class="card p-6 h-fit">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-lg">🏷️</div>
            <h3 class="font-display font-bold text-slate-800">Tambah Kategori</h3>
        </div>

        @if($errors->any())
            <div class="mb-4 p-3 bg-rose-50 border border-rose-200 rounded-xl text-rose-600 text-xs space-y-1">
                @foreach($errors->all() as $error)<p>• {{ $error }}</p>@endforeach
            </div>
        @endif

        <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="form-label">Nama Kategori</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-input" placeholder="Misal: Makan Siang" required>
            </div>
            <div>
                <label class="form-label">Tipe</label>
                <select name="type" class="form-input" required>
                    <option value="income" {{ old('type') === 'income' ? 'selected' : '' }}>📈 Pemasukan</option>
                    <option value="expense" {{ old('type') === 'expense' ? 'selected' : '' }}>📉 Pengeluaran</option>
                </select>
            </div>
            <div>
                <label class="form-label">Ikon (emoji)</label>
                <input type="text" name="icon" value="{{ old('icon', '💰') }}" class="form-input" placeholder="💰" maxlength="10">
            </div>
            <div>
                <label class="form-label">Warna</label>
                <div class="flex gap-2 items-center">
                    <input type="color" name="color" value="{{ old('color', '#6366f1') }}" class="h-10 w-16 rounded-lg border border-slate-200 cursor-pointer">
                    <span class="text-slate-400 text-sm">Pilih warna kategori</span>
                </div>
            </div>
            <button type="submit" class="btn-primary w-full text-center">+ Tambah Kategori</button>
        </form>
    </div>

    {{-- Categories List --}}
    <div class="lg:col-span-2">
        {{-- Income Categories --}}
        <div class="card mb-5">
            <div class="p-5 border-b border-slate-100 flex items-center gap-2">
                <span class="text-xl">📈</span>
                <h3 class="font-display font-bold text-slate-800">Kategori Pemasukan</h3>
                <span class="ml-auto text-xs text-slate-400">{{ $categories->where('type', 'income')->count() }} kategori</span>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($categories->where('type', 'income') as $cat)
                <div class="flex items-center gap-4 px-5 py-4">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg flex-shrink-0"
                         style="background-color: {{ $cat->color }}20;">
                        {{ $cat->icon }}
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-slate-700">{{ $cat->name }}</p>
                        <p class="text-xs text-slate-400">{{ $cat->transactions_count }} transaksi</p>
                    </div>
                    <div class="w-4 h-4 rounded-full flex-shrink-0" style="background-color: {{ $cat->color }}"></div>
                    <div class="flex gap-2">
                        <button onclick="openEditModal({{ $cat->id }}, '{{ $cat->name }}', '{{ $cat->type }}', '{{ $cat->icon }}', '{{ $cat->color }}')"
                            class="text-xs text-indigo-500 hover:text-indigo-700 font-semibold px-3 py-1.5 rounded-lg hover:bg-indigo-50 transition-all">
                            Edit
                        </button>
                        @if($cat->transactions_count === 0)
                        <form action="{{ route('categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-rose-500 hover:text-rose-700 font-semibold px-3 py-1.5 rounded-lg hover:bg-rose-50 transition-all">Hapus</button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-slate-400 text-sm">Belum ada kategori pemasukan</div>
                @endforelse
            </div>
        </div>

        {{-- Expense Categories --}}
        <div class="card">
            <div class="p-5 border-b border-slate-100 flex items-center gap-2">
                <span class="text-xl">📉</span>
                <h3 class="font-display font-bold text-slate-800">Kategori Pengeluaran</h3>
                <span class="ml-auto text-xs text-slate-400">{{ $categories->where('type', 'expense')->count() }} kategori</span>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($categories->where('type', 'expense') as $cat)
                <div class="flex items-center gap-4 px-5 py-4">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg flex-shrink-0"
                         style="background-color: {{ $cat->color }}20;">
                        {{ $cat->icon }}
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-slate-700">{{ $cat->name }}</p>
                        <p class="text-xs text-slate-400">{{ $cat->transactions_count }} transaksi</p>
                    </div>
                    <div class="w-4 h-4 rounded-full flex-shrink-0" style="background-color: {{ $cat->color }}"></div>
                    <div class="flex gap-2">
                        <button onclick="openEditModal({{ $cat->id }}, '{{ $cat->name }}', '{{ $cat->type }}', '{{ $cat->icon }}', '{{ $cat->color }}')"
                            class="text-xs text-indigo-500 hover:text-indigo-700 font-semibold px-3 py-1.5 rounded-lg hover:bg-indigo-50 transition-all">
                            Edit
                        </button>
                        @if($cat->transactions_count === 0)
                        <form action="{{ route('categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-rose-500 hover:text-rose-700 font-semibold px-3 py-1.5 rounded-lg hover:bg-rose-50 transition-all">Hapus</button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-slate-400 text-sm">Belum ada kategori pengeluaran</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="card p-7 w-full max-w-md mx-4">
        <h3 class="font-display font-bold text-slate-800 text-lg mb-5">Edit Kategori</h3>
        <form id="editForm" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="form-label">Nama Kategori</label>
                <input type="text" name="name" id="edit_name" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Tipe</label>
                <select name="type" id="edit_type" class="form-input" required>
                    <option value="income">📈 Pemasukan</option>
                    <option value="expense">📉 Pengeluaran</option>
                </select>
            </div>
            <div>
                <label class="form-label">Ikon (emoji)</label>
                <input type="text" name="icon" id="edit_icon" class="form-input" maxlength="10">
            </div>
            <div>
                <label class="form-label">Warna</label>
                <input type="color" name="color" id="edit_color" class="h-10 w-16 rounded-lg border border-slate-200 cursor-pointer">
            </div>
            <div class="flex gap-3 pt-1">
                <button type="submit" class="btn-primary flex-1">Simpan</button>
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Batal</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openEditModal(id, name, type, icon, color) {
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
    document.getElementById('editForm').action = `/categories/${id}`;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_type').value = type;
    document.getElementById('edit_icon').value = icon;
    document.getElementById('edit_color').value = color;
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
}
</script>
@endpush
@endsection
