<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Movie Explorer') — Movie Explorer</title>
    <meta name="description" content="@yield('meta_description', 'Discover, search, and save your favorite movies with Movie Explorer.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #0a0a0f;
            --bg2: #101018;
            --card: #161622;
            --card2: #1e1e2e;
            --border: rgba(255,255,255,0.08);
            --accent: #7c6af7;
            --accent2: #a78bfa;
            --gold: #f59e0b;
            --text: #e2e8f0;
            --muted: #94a3b8;
            --danger: #ef4444;
            --success: #10b981;
            --radius: 12px;
            --radius-lg: 18px;
            --shadow: 0 4px 24px rgba(0,0,0,0.4);
        }

        html, body { height: 100%; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Navbar ── */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(10, 10, 15, 0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
        }
        .navbar-brand {
            font-size: 1.25rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
            letter-spacing: -0.5px;
        }
        .navbar-brand span { -webkit-text-fill-color: var(--gold); }
        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            list-style: none;
        }
        .nav-link {
            color: var(--muted);
            text-decoration: none;
            padding: 0.5rem 0.85rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .nav-link:hover, .nav-link.active { color: var(--text); background: var(--card2); }
        .nav-link.active { color: var(--accent2); }
        .nav-divider { width: 1px; height: 24px; background: var(--border); margin: 0 0.25rem; }
        .lang-btn {
            background: none;
            border: 1px solid var(--border);
            color: var(--muted);
            padding: 0.3rem 0.65rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }
        .lang-btn.active, .lang-btn:hover { border-color: var(--accent); color: var(--accent2); background: rgba(124,106,247,0.1); }
        .btn-logout {
            background: none;
            border: none;
            color: var(--muted);
            padding: 0.5rem 0.85rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .btn-logout:hover { color: var(--danger); background: rgba(239,68,68,0.08); }

        /* ── Main Content ── */
        .main { flex: 1; padding: 2rem; max-width: 1400px; margin: 0 auto; width: 100%; }

        /* ── Alerts / Toast ── */
        .toast-container {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .toast {
            background: var(--card2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 0.875rem 1.25rem;
            font-size: 0.875rem;
            min-width: 240px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: var(--shadow);
            animation: slideIn 0.3s ease;
        }
        .toast.success { border-left: 3px solid var(--success); }
        .toast.error   { border-left: 3px solid var(--danger); }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to   { transform: translateX(0); opacity: 1; }
        }

        /* ── Buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.6rem 1.25rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            text-decoration: none;
            white-space: nowrap;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--accent), #6d5ef5);
            color: #fff;
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(124,106,247,0.4); }
        .btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text);
        }
        .btn-outline:hover { border-color: var(--accent); color: var(--accent2); background: rgba(124,106,247,0.08); }
        .btn-danger {
            background: rgba(239,68,68,0.12);
            color: var(--danger);
            border: 1px solid rgba(239,68,68,0.3);
        }
        .btn-danger:hover { background: rgba(239,68,68,0.2); }
        .btn-fav {
            background: rgba(245,158,11,0.12);
            color: var(--gold);
            border: 1px solid rgba(245,158,11,0.3);
        }
        .btn-fav:hover { background: rgba(245,158,11,0.2); }

        /* ── Badge ── */
        .badge {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .badge-accent { background: rgba(124,106,247,0.2); color: var(--accent2); }
        .badge-gold   { background: rgba(245,158,11,0.15); color: var(--gold); }

        /* ── Footer ── */
        .footer {
            text-align: center;
            padding: 1.25rem;
            color: var(--muted);
            font-size: 0.75rem;
            border-top: 1px solid var(--border);
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg2); }
        ::-webkit-scrollbar-thumb { background: #2d2d45; border-radius: 3px; }

        @media (max-width: 768px) {
            .navbar { padding: 0 1rem; }
            .main { padding: 1rem; }
            .navbar-brand { font-size: 1rem; }
        }
    </style>
    @stack('styles')
</head>
<body>

@auth
<nav class="navbar">
    <a href="{{ route('movies.index') }}" class="navbar-brand">🎬 Movie<span>Explorer</span></a>

    <ul class="navbar-nav">
        <li>
            <a href="{{ route('movies.index') }}" class="nav-link {{ request()->routeIs('movies.*') ? 'active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="15" rx="2"/><path d="M7 7V4a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v3"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/></svg>
                {{ __('app.nav_movies') }}
            </a>
        </li>
        <li>
            <a href="{{ route('favorites.index') }}" class="nav-link {{ request()->routeIs('favorites.*') ? 'active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                {{ __('app.nav_favorites') }}
            </a>
        </li>
        <li><div class="nav-divider"></div></li>
        <li>
            <a href="{{ route('language.switch', 'en') }}" class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
        </li>
        <li>
            <a href="{{ route('language.switch', 'id') }}" class="lang-btn {{ app()->getLocale() === 'id' ? 'active' : '' }}">ID</a>
        </li>
        <li><div class="nav-divider"></div></li>
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    {{ __('app.nav_logout') }}
                </button>
            </form>
        </li>
    </ul>
</nav>
@endauth

<main class="main">
    @yield('content')
</main>

<footer class="footer">
    &copy; {{ date('Y') }} Movie Explorer — Powered by OMDb API
</footer>

{{-- Toast notifications --}}
<div class="toast-container" id="toastContainer">
    @if(session('success'))
        <div class="toast success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="toast error">❌ {{ session('error') }}</div>
    @endif
</div>

<script>
    // Auto-dismiss toasts
    document.querySelectorAll('.toast').forEach(t => {
        setTimeout(() => t.remove(), 4000);
    });

    // Global toast helper
    window.showToast = function(msg, type = 'success') {
        const c = document.getElementById('toastContainer');
        const t = document.createElement('div');
        t.className = `toast ${type}`;
        t.innerHTML = (type === 'success' ? '✅ ' : '❌ ') + msg;
        c.appendChild(t);
        setTimeout(() => t.remove(), 4000);
    };
</script>
@stack('scripts')
</body>
</html>
