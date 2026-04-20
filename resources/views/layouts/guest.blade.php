<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap');

            :root {
                color-scheme: light;
                --bg: #f4f2ee;
                --panel: rgba(255, 255, 255, 0.88);
                --panel-strong: #fffdfa;
                --panel-soft: #f5efe8;
                --ink: #1f2328;
                --muted: #6b7280;
                --accent: #0f766e;
                --accent-2: #f59e0b;
                --border: #e7e2db;
                --shadow: 0 24px 60px rgba(31, 35, 40, 0.12);
                --radius: 24px;
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                min-height: 100vh;
                font-family: "Space Grotesk", "Segoe UI", system-ui, sans-serif;
                color: var(--ink);
                background:
                    radial-gradient(circle at 10% 10%, rgba(245, 158, 11, 0.12), transparent 40%),
                    radial-gradient(circle at 90% 16%, rgba(15, 118, 110, 0.14), transparent 42%),
                    linear-gradient(180deg, #f7f3ee 0%, #f1ece5 100%);
            }

            a {
                color: inherit;
                text-decoration: none;
            }

            .auth-shell {
                min-height: 100vh;
                display: grid;
                grid-template-columns: minmax(0, 1.05fr) minmax(0, 0.95fr);
                padding: 28px;
                gap: 24px;
            }

            .auth-showcase,
            .auth-panel {
                border: 1px solid rgba(231, 226, 219, 0.9);
                box-shadow: var(--shadow);
                backdrop-filter: blur(16px);
            }

            .auth-showcase {
                position: relative;
                overflow: hidden;
                border-radius: 32px;
                padding: 42px;
                background:
                    radial-gradient(circle at top left, rgba(245, 158, 11, 0.16), transparent 34%),
                    radial-gradient(circle at bottom right, rgba(15, 118, 110, 0.16), transparent 42%),
                    linear-gradient(180deg, rgba(255, 253, 250, 0.94) 0%, rgba(245, 239, 232, 0.96) 100%);
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                gap: 40px;
            }

            .auth-brand {
                display: inline-flex;
                align-items: center;
                gap: 12px;
                width: fit-content;
                padding: 14px 18px;
                border-radius: 18px;
                background: rgba(255, 255, 255, 0.74);
                border: 1px solid rgba(231, 226, 219, 0.9);
                box-shadow: 0 12px 32px rgba(31, 35, 40, 0.06);
                font-weight: 700;
                letter-spacing: 0.02em;
            }

            .auth-brand-mark {
                width: 12px;
                height: 12px;
                border-radius: 999px;
                background: linear-gradient(135deg, var(--accent), #14b8a6);
                box-shadow: 0 0 0 6px rgba(15, 118, 110, 0.12);
            }

            .auth-copy h1 {
                margin: 0 0 14px;
                font-size: clamp(2.4rem, 4vw, 4rem);
                line-height: 0.98;
                letter-spacing: -0.04em;
            }

            .auth-copy p {
                max-width: 32rem;
                margin: 0;
                font-size: 1.02rem;
                line-height: 1.7;
                color: rgba(31, 35, 40, 0.72);
            }

            .auth-highlights {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 14px;
            }

            .auth-highlight {
                padding: 18px;
                border-radius: 20px;
                background: rgba(255, 255, 255, 0.64);
                border: 1px solid rgba(231, 226, 219, 0.92);
            }

            .auth-highlight-label {
                display: block;
                font-size: 0.78rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                color: rgba(31, 35, 40, 0.45);
                margin-bottom: 10px;
            }

            .auth-highlight strong {
                display: block;
                font-size: 1.05rem;
                line-height: 1.35;
            }

            .auth-panel {
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 32px;
                background: linear-gradient(180deg, rgba(255, 255, 255, 0.94) 0%, rgba(249, 246, 242, 0.95) 100%);
                padding: 42px 32px;
            }

            .auth-panel-inner {
                width: min(100%, 480px);
            }

            .auth-status {
                margin-bottom: 20px;
                padding: 14px 16px;
                border-radius: 18px;
                border: 1px solid rgba(15, 118, 110, 0.18);
                background: linear-gradient(180deg, rgba(236, 253, 245, 0.95) 0%, rgba(220, 252, 231, 0.92) 100%);
                color: #065f46;
                font-size: 0.95rem;
                font-weight: 600;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8);
            }

            .auth-inline-link {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                color: var(--accent);
                font-size: 0.9rem;
                font-weight: 700;
                letter-spacing: 0.01em;
                transition: color 160ms ease, transform 160ms ease;
            }

            .auth-inline-link::after {
                content: "\2192";
                font-size: 0.95rem;
                transition: transform 160ms ease;
            }

            .auth-inline-link:hover {
                color: #0b5f59;
            }

            .auth-inline-link:hover::after {
                transform: translateX(2px);
            }

            .auth-inline-link:focus {
                outline: none;
                text-decoration: underline;
                text-underline-offset: 3px;
            }

            .button {
                padding: 5px 16px;
                border-radius: 99px;
                border: 1px solid transparent;
                background: var(--accent);
                color: white;
                font-weight: 600;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                text-align: center;
            }

            .form-card input,
            .form-card select,
            .form-card textarea {
                width: 100%;
                padding: 10px 12px;
                border-radius: 14px;
                border: 1px solid rgba(31, 35, 40, 0.08);
                background: linear-gradient(180deg, #fffdfa 0%, #f8f3ec 100%);
                font-size: 0.95rem;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7);
                transition: border-color 160ms ease, box-shadow 160ms ease, background 160ms ease;
            }

            .form-card input:focus,
            .form-card select:focus,
            .form-card textarea:focus {
                outline: none;
                border-color: rgba(15, 118, 110, 0.35);
                box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.08);
                background: #fffefb;
            }

            .form-row {
                margin-bottom: 18px;
            }

            .form-row label {
                display: inline-block;
                margin-bottom: 6px;
                font-weight: 700;
                font-size: 0.78rem;
                letter-spacing: 0.03em;
                text-transform: uppercase;
                color: #374151;
            }

            .form-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                margin-top: 28px;
            }

            .auth-check {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 12px 14px;
                border-radius: 14px;
                border: 1px solid rgba(31, 35, 40, 0.08);
                background: linear-gradient(180deg, #fffdfa 0%, #f8f3ec 100%);
                color: #374151;
                font-size: 0.95rem;
            }

            .auth-check input {
                appearance: none;
                -webkit-appearance: none;
                width: 18px;
                height: 18px;
                margin: 0;
                border: 1px solid rgba(15, 118, 110, 0.28);
                border-radius: 6px;
                background: #fffdfa;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.78);
                display: inline-grid;
                place-items: center;
                flex: 0 0 18px;
                cursor: pointer;
                transition: border-color 160ms ease, background 160ms ease, box-shadow 160ms ease, transform 160ms ease;
            }

            .auth-check input::after {
                content: "";
                width: 9px;
                height: 5px;
                border-left: 2px solid white;
                border-bottom: 2px solid white;
                transform: rotate(-45deg) scale(0);
                transform-origin: center;
                transition: transform 160ms ease;
                margin-top: -1px;
            }

            .auth-check input:hover {
                border-color: rgba(15, 118, 110, 0.45);
            }

            .auth-check input:focus {
                outline: none;
                box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.12);
                border-color: rgba(15, 118, 110, 0.45);
            }

            .auth-check input:checked {
                background: var(--accent);
                border-color: var(--accent);
            }

            .auth-check input:checked::after {
                transform: rotate(-45deg) scale(1);
            }

            .auth-check span {
                line-height: 1.45;
            }

            @media (max-width: 1024px) {
                .auth-shell {
                    grid-template-columns: 1fr;
                }

                .auth-showcase {
                    min-height: 320px;
                }

                .auth-highlights {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 640px) {
                .auth-shell {
                    padding: 16px;
                    gap: 16px;
                }

                .auth-showcase,
                .auth-panel {
                    border-radius: 24px;
                    padding: 24px 20px;
                }

                .auth-copy h1 {
                    font-size: 2.3rem;
                }
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="auth-shell">
            <section class="auth-showcase">
                <a class="auth-brand" href="/" wire:navigate>
                    <span class="auth-brand-mark" aria-hidden="true"></span>
                    <span>Zenocar</span>
                </a>

                <div class="auth-copy">
                    <h1>Zenocar Fleet Dashboard.</h1>
                    <p>Sign in to access your Zenocar workspace for bookings, vehicles, suppliers, drivers, and compliance operations.</p>
                </div>

                <div class="auth-highlights" aria-hidden="true">
                    <div class="auth-highlight">
                        <span class="auth-highlight-label">Bookings</span>
                        <strong>Track dispatch flow and customer activity in one place.</strong>
                    </div>
                    <div class="auth-highlight">
                        <span class="auth-highlight-label">Compliance</span>
                        <strong>Keep documents, checks, and alerts visible without extra tools.</strong>
                    </div>
                    <div class="auth-highlight">
                        <span class="auth-highlight-label">Fleet</span>
                        <strong>Move between vehicles, suppliers, and drivers with the same admin language.</strong>
                    </div>
                </div>
            </section>

            <section class="auth-panel">
                <div class="auth-panel-inner">
                    {{ $slot }}
                </div>
            </section>
        </div>
    </body>
</html>
