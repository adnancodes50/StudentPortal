<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Student Portal'))</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Poppins:300,400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --brand-950: #0a0f1a;
            --brand-900: #0b1220;
            --brand-800: #0e1b34;
            --brand-600: #0a6aa6;
            --brand-500: #0b84c6;
            --accent-500: #a855f7;
            --kicker-500: #f59e0b;
            --signin-500: #dc2626;
            --register-900: #111827;
            --surface: #ffffff;
            --muted: #64748b;
            --border: rgba(15, 23, 42, .10);
        }

        body {
            font-family: Poppins, system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
            background: #f8fafc;
            color: #0f172a;
        }

        .front-navbar {
            background: rgba(255, 255, 255, .85);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(10px);
        }

        .brand-badge {
            width: 40px;
            height: 40px;
            display: grid;
            place-items: center;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(11, 132, 198, .22), rgba(168, 85, 247, .18));
            border: 1px solid rgba(11, 132, 198, .22);
        }

        .btn-brand {
            --bs-btn-bg: var(--brand-500);
            --bs-btn-border-color: var(--brand-500);
            --bs-btn-hover-bg: var(--brand-600);
            --bs-btn-hover-border-color: var(--brand-600);
            --bs-btn-color: #fff;
            --bs-btn-hover-color: #fff;
            --bs-btn-focus-shadow-rgb: 14, 165, 233;
            border-radius: 999px;
            padding-left: 1.15rem;
            padding-right: 1.15rem;
        }

        .btn-accent {
            --bs-btn-bg: var(--accent-500);
            --bs-btn-border-color: var(--accent-500);
            --bs-btn-hover-bg: #9333ea;
            --bs-btn-hover-border-color: #9333ea;
            --bs-btn-color: #fff;
            --bs-btn-hover-color: #fff;
            border-radius: 999px;
            padding-left: 1.15rem;
            padding-right: 1.15rem;
        }

        .btn-signin {
            --bs-btn-bg: var(--signin-500);
            --bs-btn-border-color: var(--signin-500);
            --bs-btn-hover-bg: #b91c1c;
            --bs-btn-hover-border-color: #b91c1c;
            --bs-btn-color: #fff;
            --bs-btn-hover-color: #fff;
            border-radius: 10px;
            padding-left: 1.4rem;
            padding-right: 1.4rem;
        }

        .btn-register {
            --bs-btn-bg: var(--register-900);
            --bs-btn-border-color: var(--register-900);
            --bs-btn-hover-bg: #0b1220;
            --bs-btn-hover-border-color: #0b1220;
            --bs-btn-color: #fff;
            --bs-btn-hover-color: #fff;
            border-radius: 10px;
            padding-left: 1.4rem;
            padding-right: 1.4rem;
        }

        .text-muted-2 {
            color: var(--muted) !important;
        }

        .front-hero {
            background:
                radial-gradient(1200px circle at 15% 20%, rgba(249, 115, 22, .24), transparent 55%),
                radial-gradient(900px circle at 85% 15%, rgba(14, 165, 233, .24), transparent 55%),
                linear-gradient(135deg, var(--brand-900), var(--brand-800));
            color: #fff;
            border-bottom: 1px solid rgba(255, 255, 255, .10);
        }

        .glass-card {
            background: rgba(255, 255, 255, .06);
            border: 1px solid rgba(255, 255, 255, .14);
            border-radius: 18px;
        }

        .section-title {
            letter-spacing: -0.02em;
        }

        .feature-card,
        .category-card,
        .flight-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 18px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, .06);
        }

        .category-card {
            overflow: hidden;
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .category-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(15, 23, 42, .10);
        }

        .category-cover {
            height: 220px;
            width: 100%;
            object-fit: cover;
        }

        .category-title {
            font-weight: 600;
        }

        .front-footer {
            border-top: 1px solid var(--border);
            background: #fff;
        }

        .portal-hero {
            --hero-bg: none;
            position: relative;
            color: #fff;
            background-image:
                linear-gradient(180deg, rgba(255, 255, 255, .42), rgba(14, 27, 52, .45)),
                radial-gradient(900px circle at 15% 20%, rgba(11, 132, 198, .28), transparent 55%),
                radial-gradient(900px circle at 85% 20%, rgba(168, 85, 247, .24), transparent 55%),
                var(--hero-bg);
            background-size: cover;
            background-position: center;
            border-bottom: 1px solid rgba(255, 255, 255, .10);
        }

        .portal-kicker {
            color: var(--kicker-500);
            font-weight: 700;
            letter-spacing: .02em;
        }

        .portal-title {
            color: #0b4a6f;
            text-shadow: 0 1px 0 rgba(255, 255, 255, .18);
            font-weight: 800;
            letter-spacing: .06em;
        }

        .portal-panel {
            background: rgba(255, 255, 255, .14);
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 18px;
            padding: 22px;
            backdrop-filter: blur(10px);
        }

        .portal-category {
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 18px 45px rgba(2, 6, 23, .25);
            background: #fff;
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .portal-category:hover {
            transform: translateY(-4px);
            box-shadow: 0 24px 60px rgba(2, 6, 23, .30);
        }

        .portal-category img {
            width: 100%;
            height: 175px;
            object-fit: cover;
        }

        .portal-category-label {
            padding: 14px 12px;
            font-weight: 700;
            text-align: center;
            color: #0f172a;
            background: #fff;
        }

        @media (max-width: 576px) {
            .portal-category img {
                height: 150px;
            }

            .portal-title {
                letter-spacing: .03em;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    @include('frontend.partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('frontend.partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
