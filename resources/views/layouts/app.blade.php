<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — UangKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-display { font-family: 'Sora', sans-serif; }

        .sidebar {
            background: #21262d;
            border-right: 1px solid rgba(255,255,255,0.06);
            box-shadow: 4px 0 24px rgba(0,0,0,0.3);
        }
        .nav-link {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 14px; border-radius: 8px;
            color: #8b949e; font-size: 13.5px; font-weight: 500;
            text-decoration: none; transition: all 0.18s ease;
            border: 1px solid transparent; width: 100%;
            text-align: left; background: none; cursor: pointer;
            box-sizing: border-box; letter-spacing: 0.01em;
            position: relative;
        }
        .nav-link:hover { color: #e6edf3; background: rgba(255,255,255,0.07); }
        .nav-link.active {
            background: rgba(255,255,255,0.1); color: #ffffff;
            border-color: rgba(255,255,255,0.18); font-weight: 600;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.1);
        }
        .nav-link.active::before {
            content: ""; position: absolute;
            left: 0; top: 50%; transform: translateY(-50%);
            width: 3px; height: 55%;
            background: linear-gradient(180deg, #f1f5f9, #94a3b8);
            border-radius: 0 3px 3px 0;
        }
        .nav-link-logout { color: #f87171 !important; }
        .nav-link-logout:hover { background: rgba(248,113,113,0.08) !important; color: #fca5a5 !important; }

        .card { background: white; border-radius: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04); }
        .income-card  { background: linear-gradient(135deg, #10b981, #059669); }
        .expense-card { background: linear-gradient(135deg, #f43f5e, #e11d48); }
        .balance-card { background: linear-gradient(135deg, #6366f1, #4f46e5); }

        .badge-income  { background:#d1fae5;color:#065f46;font-size:11px;font-weight:700;padding:3px 10px;border-radius:999px;display:inline-block; }
        .badge-expense { background:#ffe4e6;color:#be123c;font-size:11px;font-weight:700;padding:3px 10px;border-radius:999px;display:inline-block; }

        .btn-primary {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white; padding: 10px 20px; border-radius: 12px;
            font-weight: 600; font-size: 14px; text-decoration: none;
            display: inline-flex; align-items: center; gap: 6px;
            transition: opacity 0.2s; box-shadow: 0 4px 12px rgba(99,102,241,0.3);
            border: none; cursor: pointer;
        }
        .btn-primary:hover { opacity: 0.88; }

        .form-input {
            width: 100%; border: 1px solid #e2e8f0; border-radius: 12px;
            padding: 10px 16px; font-size: 14px; outline: none;
            transition: all 0.2s; background: white; color: #1e293b; box-sizing: border-box;
        }
        .form-input:focus { border-color: #a5b4fc; box-shadow: 0 0 0 3px rgba(99,102,241,0.15); }
        .form-label { display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px; }

        /* Mobile sidebar overlay */
        #sidebar-overlay {
            display: none;
            position: fixed; inset: 0; z-index: 40;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(2px);
        }
        #sidebar-overlay.open { display: block; }

        /* Desktop sidebar */
        #sidebar {
            position: fixed; left: 0; top: 0; bottom: 0;
            width: 240px; z-index: 50;
            transform: translateX(-100%);
            transition: transform 0.25s ease;
            display: flex; flex-direction: column;
            overflow-y: auto; padding: 24px 16px;
        }
        #sidebar.open { transform: translateX(0); }

        @media (min-width: 768px) {
            #sidebar {
                position: relative;
                transform: translateX(0) !important;
                flex-shrink: 0;
            }
            #sidebar-overlay { display: none !important; }
            #hamburger { display: none !important; }
            .main-wrapper { margin-left: 0; }
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="h-full bg-slate-50">
<div class="flex h-screen overflow-hidden">

    <!-- Mobile Overlay -->
    <div id="sidebar-overlay" onclick="closeSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar">

        <!-- Logo -->
        <div style="margin-bottom:28px;padding:0 8px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#30363d,#484f58);display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:bold;color:white;box-shadow:0 4px 12px rgba(99,102,241,0.4);">₿</div>
                <div>
                    <div class="font-display" style="font-size:18px;font-weight:700;color:white;">UangKu</div>
                    <div style="font-size:11px;color:#6e7681;">Keuangan Pribadi</div>
                </div>
            </div>
        </div>

        <!-- User Info -->
        <div style="margin-bottom:24px;padding:12px;border-radius:10px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#30363d,#6e7681);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:white;flex-shrink:0;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div style="overflow:hidden;min-width:0;">
                    <div style="font-size:13px;font-weight:600;color:white;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ Auth::user()->name }}</div>
                    <div style="font-size:11px;color:#6e7681;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ Auth::user()->email }}</div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div style="flex:1;">
            <div style="font-size:10px;font-weight:700;color:#484f58;text-transform:uppercase;letter-spacing:0.12em;padding:0 8px;margin-bottom:8px;">Menu</div>
            <div style="display:flex;flex-direction:column;gap:3px;">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" onclick="closeSidebar()">
                    <span style="font-size:15px;flex-shrink:0;">🏠</span> Dashboard
                </a>
                <a href="{{ route('transactions.index') }}" class="nav-link {{ request()->routeIs('transactions.index') ? 'active' : '' }}" onclick="closeSidebar()">
                    <span style="font-size:15px;flex-shrink:0;">💳</span> Transaksi
                </a>
                <a href="{{ route('transactions.create') }}" class="nav-link {{ request()->routeIs('transactions.create') ? 'active' : '' }}" onclick="closeSidebar()">
                    <span style="font-size:15px;flex-shrink:0;">➕</span> Tambah Transaksi
                </a>
                <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" onclick="closeSidebar()">
                    <span style="font-size:15px;flex-shrink:0;">🏷️</span> Kategori
                </a>
                <a href="{{ route('investasi.index') }}" class="nav-link {{ request()->routeIs('investasi.*') ? 'active' : '' }}" onclick="closeSidebar()">
                    <span style="font-size:15px;flex-shrink:0;">📊</span> Keuangan
                </a>
            </div>
        </div>

        <!-- Logout -->
        <div style="margin-top:16px;padding-top:16px;border-top:1px solid rgba(255,255,255,0.07);">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link nav-link-logout">
                    <span style="font-size:15px;flex-shrink:0;">🚪</span> Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto" style="min-width:0;">

        <!-- Top Bar -->
        <div style="position:sticky;top:0;z-index:10;background:rgba(248,250,252,0.9);backdrop-filter:blur(12px);border-bottom:1px solid #e2e8f0;padding:16px 20px;display:flex;align-items:center;justify-content:space-between;gap:12px;">
            <div style="display:flex;align-items:center;gap:12px;min-width:0;">
                <!-- Hamburger (mobile only) -->
                <button id="hamburger" onclick="openSidebar()" style="flex-shrink:0;width:36px;height:36px;border-radius:8px;background:#f1f5f9;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:center;cursor:pointer;">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                        <rect x="2" y="4" width="14" height="1.5" rx="1" fill="#475569"/>
                        <rect x="2" y="8.25" width="14" height="1.5" rx="1" fill="#475569"/>
                        <rect x="2" y="12.5" width="14" height="1.5" rx="1" fill="#475569"/>
                    </svg>
                </button>
                <div style="min-width:0;">
                    <div class="font-display" style="font-size:17px;font-weight:700;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">@yield('page-title', 'Dashboard')</div>
                    <div style="font-size:11px;color:#94a3b8;margin-top:1px;">{{ now()->translatedFormat('l, d F Y') }}</div>
                </div>
            </div>
            <a href="{{ route('transactions.create') }}" class="btn-primary" style="flex-shrink:0;white-space:nowrap;padding:9px 14px;font-size:13px;">
                <span>+</span> <span class="hidden sm:inline">Transaksi Baru</span><span class="sm:hidden">Baru</span>
            </a>
        </div>

        <!-- Page Content -->
        <div style="padding:20px;">
            @if(session('success'))
                <div style="margin-bottom:20px;display:flex;align-items:center;gap:10px;background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;padding:12px 16px;border-radius:12px;font-size:14px;font-weight:500;">
                    <span>✅</span> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div style="margin-bottom:20px;display:flex;align-items:center;gap:10px;background:#fff1f2;border:1px solid #fecdd3;color:#9f1239;padding:12px 16px;border-radius:12px;font-size:14px;font-weight:500;">
                    <span>❌</span> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

<script>
function openSidebar() {
    document.getElementById('sidebar').classList.add('open');
    document.getElementById('sidebar-overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebar-overlay').classList.remove('open');
    document.body.style.overflow = '';
}
</script>

@stack('scripts')
</body>
</html>