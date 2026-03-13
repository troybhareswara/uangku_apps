<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — UangKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-display { font-family: 'Sora', sans-serif; }

        body {
            background: #161b22;
            min-height: 100vh;
        }

        /* Subtle noise texture overlay */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                radial-gradient(ellipse 80% 60% at 20% 10%, rgba(148,163,184,0.06) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 80% 90%, rgba(100,116,139,0.05) 0%, transparent 55%);
            pointer-events: none;
            z-index: 0;
        }

        .page-wrap {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 24px 16px;
        }

        .glass-card {
            background: rgba(33,38,45,0.95);
            border: 1px solid rgba(255,255,255,0.09);
            border-radius: 20px;
            box-shadow:
                0 0 0 1px rgba(255,255,255,0.03),
                0 24px 64px rgba(0,0,0,0.5),
                inset 0 1px 0 rgba(255,255,255,0.06);
        }

        .form-input {
            width: 100%;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 11px 14px;
            color: #e6edf3;
            font-size: 14px;
            transition: all 0.2s;
            box-sizing: border-box;
        }
        .form-input:focus {
            outline: none;
            border-color: rgba(241,245,249,0.35);
            background: rgba(255,255,255,0.07);
            box-shadow: 0 0 0 3px rgba(148,163,184,0.12);
        }
        .form-input::placeholder { color: rgba(255,255,255,0.2); }

        .form-label {
            color: rgba(255,255,255,0.55);
            font-size: 12px;
            font-weight: 600;
            display: block;
            margin-bottom: 6px;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .btn-auth {
            width: 100%;
            background: linear-gradient(180deg, #3d444d 0%, #2d333b 100%);
            color: #e6edf3;
            padding: 12px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            border: 1px solid rgba(255,255,255,0.15);
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 1px 0 rgba(255,255,255,0.08) inset, 0 4px 12px rgba(0,0,0,0.3);
            letter-spacing: 0.02em;
        }
        .btn-auth:hover {
            background: linear-gradient(180deg, #484f58 0%, #373e47 100%);
            border-color: rgba(255,255,255,0.22);
            transform: translateY(-1px);
            box-shadow: 0 1px 0 rgba(255,255,255,0.1) inset, 0 6px 16px rgba(0,0,0,0.35);
        }
        .btn-auth:active { transform: translateY(0); }

        .logo-icon {
            width: 56px; height: 56px;
            border-radius: 16px;
            background: linear-gradient(145deg, #448863, #4b5563);
            border: 1px solid rgba(255,255,255,0.12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.1);
            margin-bottom: 14px;
        }

        .divider {
            height: 1px;
            background: rgba(255,255,255,0.07);
            margin: 20px 0;
        }

        .link-auth {
            color: #94a3b8;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.15s;
        }
        .link-auth:hover { color: #e2e8f0; }

        .error-box {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.25);
            border-radius: 10px;
            color: #fca5a5;
            font-size: 13px;
            padding: 10px 14px;
            margin-bottom: 18px;
        }

        /* Subtle grid lines in background */
        .bg-grid {
            position: fixed;
            inset: 0;
            z-index: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.015) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.015) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="bg-grid"></div>

    <div class="page-wrap">
        <div style="width:100%;max-width:420px;">

            <!-- Logo -->
            <div style="text-align:center;margin-bottom:28px;">
                <div class="logo-icon">₿</div>
                <div class="font-display" style="font-size:26px;font-weight:700;color:#e6edf3;letter-spacing:-0.02em;">UangKu</div>
                <div style="font-size:13px;color:#6e7681;margin-top:4px;">Catat keuanganmu dengan mudah</div>
            </div>

            <div class="glass-card" style="padding:32px;">
                @yield('content')
            </div>

            <p style="text-align:center;color:#30363d;font-size:11px;margin-top:20px;">
                © {{ date('Y') }} UangKu. Made by @troybhareswara
            </p>
        </div>
    </div>
</body>
</html>