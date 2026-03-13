@extends('layouts.auth')
@section('title', 'Daftar')

@section('content')
<div style="margin-bottom:24px;">
    <div class="font-display" style="font-size:20px;font-weight:700;color:#e6edf3;">Buat Akun Baru ✨</div>
    <div style="font-size:13px;color:#6e7681;margin-top:4px;">Mulai catat keuanganmu hari ini</div>
</div>

@if($errors->any())
    <div class="error-box">
        @foreach($errors->all() as $error)
            <div>• {{ $error }}</div>
        @endforeach
    </div>
@endif

<form action="{{ route('register') }}" method="POST" style="display:flex;flex-direction:column;gap:16px;">
    @csrf
    <div>
        <label class="form-label">Nama Lengkap</label>
        <input type="text" name="name" value="{{ old('name') }}" class="form-input" placeholder="Nama kamu" required>
    </div>
    <div>
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="nama@email.com" required>
    </div>
    <div>
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-input" placeholder="Min. 8 karakter" required>
    </div>
    <div>
        <label class="form-label">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password" required>
    </div>
    <button type="submit" class="btn-auth" style="margin-top:4px;">Daftar Sekarang →</button>
</form>

<div class="divider"></div>

<p style="text-align:center;font-size:13px;color:#6e7681;">
    Sudah punya akun?
    <a href="{{ route('login') }}" class="link-auth">Masuk di sini</a>
</p>
@endsection