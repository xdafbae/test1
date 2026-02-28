<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('app.login_title') }} — Movie Explorer</title>
    <meta name="description" content="Sign in to Movie Explorer to discover and save your favorite movies.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #0a0a0f;
            --card: #161622;
            --border: rgba(255,255,255,0.08);
            --accent: #7c6af7;
            --accent2: #a78bfa;
            --gold: #f59e0b;
            --text: #e2e8f0;
            --muted: #94a3b8;
            --danger: #ef4444;
            --input-bg: #1e1e2e;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Animated background */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(124,106,247,0.08) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(167,139,250,0.06) 0%, transparent 50%),
                radial-gradient(ellipse at 60% 80%, rgba(245,158,11,0.04) 0%, transparent 50%);
            pointer-events: none;
        }

        .login-wrapper {
            width: 100%;
            max-width: 440px;
            padding: 1.5rem;
            position: relative;
            z-index: 1;
        }

        .brand {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        .brand-icon {
            font-size: 3rem;
            display: block;
            margin-bottom: 0.5rem;
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        .brand-name {
            font-size: 1.75rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
        }
        .brand-name span { -webkit-text-fill-color: var(--gold); }
        .brand-sub {
            color: var(--muted);
            font-size: 0.875rem;
            margin-top: 0.4rem;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2.25rem;
            box-shadow: 0 25px 60px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.03);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        .card-sub {
            color: var(--muted);
            font-size: 0.8rem;
            margin-bottom: 1.75rem;
        }

        .form-group { margin-bottom: 1.25rem; }
        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: 0.5rem;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }
        .input-wrap { position: relative; }
        .input-wrap svg {
            position: absolute;
            left: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            pointer-events: none;
        }
        .form-input {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.75rem 0.875rem 0.75rem 2.75rem;
            color: var(--text);
            font-size: 0.9rem;
            font-family: inherit;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        .form-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(124,106,247,0.15);
        }
        .form-input::placeholder { color: #4a5068; }

        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: var(--muted);
            margin-bottom: 1.5rem;
        }
        .form-check input[type="checkbox"] { accent-color: var(--accent); width: 15px; height: 15px; }

        .btn-submit {
            width: 100%;
            padding: 0.85rem;
            background: linear-gradient(135deg, var(--accent), #6d5ef5);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.25s;
            letter-spacing: 0.02em;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(124,106,247,0.45);
        }
        .btn-submit:active { transform: translateY(0); }

        .alert-error {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.3);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            color: #fca5a5;
            font-size: 0.85rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .hint {
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid var(--border);
            font-size: 0.75rem;
            color: var(--muted);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .hint-creds {
            font-family: 'Courier New', monospace;
            background: rgba(255,255,255,0.04);
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
        }
        .lang-toggle { display: flex; gap: 0.4rem; }
        .lang-toggle a {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.2rem 0.5rem;
            border-radius: 5px;
            text-decoration: none;
            color: var(--muted);
            border: 1px solid var(--border);
            transition: all 0.2s;
        }
        .lang-toggle a:hover, .lang-toggle a.active { border-color: var(--accent); color: var(--accent2); }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="brand">
        <span class="brand-icon">🎬</span>
        <div class="brand-name">Movie<span>Explorer</span></div>
        <div class="brand-sub">{{ __('app.login_subtitle') }}</div>
    </div>

    <div class="card">
        <h1 class="card-title">{{ __('app.login_title') }}</h1>
        <p class="card-sub">{{ __('app.login_subtitle') }}</p>

        @if($errors->any())
            <div class="alert-error">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="username">{{ __('app.login_username') }}</label>
                <div class="input-wrap">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-input"
                        placeholder="aldmic"
                        value="{{ old('username') }}"
                        autocomplete="username"
                        required
                        autofocus
                    >
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">{{ __('app.login_password') }}</label>
                <div class="input-wrap">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        placeholder="••••••••••"
                        autocomplete="current-password"
                        required
                    >
                </div>
            </div>

            <div class="form-check">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">{{ __('app.login_remember') }}</label>
            </div>

            <button type="submit" class="btn-submit">{{ __('app.login_btn') }}</button>
        </form>

        <div class="hint">
            <div class="lang-toggle">
                <a href="{{ route('language.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                <a href="{{ route('language.switch', 'id') }}" class="{{ app()->getLocale() === 'id' ? 'active' : '' }}">ID</a>
            </div>
            <span class="hint-creds">aldmic / 123abc123</span>
        </div>
    </div>
</div>
</body>
</html>
