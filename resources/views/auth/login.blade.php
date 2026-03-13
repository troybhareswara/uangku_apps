@extends('layouts.auth')
@section('title', 'Masuk')

@section('content')
<div style="margin-bottom:24px;">
    <div class="font-display" style="font-size:20px;font-weight:700;color:#e6edf3;">Selamat Datang 👋</div>
    <div style="font-size:13px;color:#6e7681;margin-top:4px;">Masuk untuk melihat laporan keuanganmu</div>
</div>

@if($errors->any())
    <div class="error-box">{{ $errors->first() }}</div>
@endif

<form action="{{ route('login') }}" method="POST" style="display:flex;flex-direction:column;gap:16px;">
    @csrf
    <div>
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="nama@email.com" required>
    </div>
    <div>
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-input" placeholder="••••••••" required>
    </div>
    <div style="display:flex;align-items:center;gap:8px;">
        <input type="checkbox" name="remember" id="remember" style="accent-color:#94a3b8;width:14px;height:14px;">
        <label for="remember" style="font-size:13px;color:#6e7681;cursor:pointer;">Ingat saya</label>
    </div>
    <button type="submit" class="btn-auth" style="margin-top:4px;">Masuk →</button>
</form>

<div class="divider"></div>

<p style="text-align:center;font-size:13px;color:#6e7681;">
    Belum punya akun?
    <a href="{{ route('register') }}" class="link-auth">Daftar sekarang</a>
</p>
@endsection