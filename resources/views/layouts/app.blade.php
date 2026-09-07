<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}" data-theme="light">
<head>

<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
{{-- ── Dynamic PWA Manifest (tenant-aware) ─────────────────── --}}
<link rel="manifest" href="{{ route('pwa.manifest') }}">
<meta name="theme-color" content="{{ tenant_setting('pwa_theme_color', '#1e293b') }}">
<meta name="application-name" content="{{ $appName }}">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="{{ tenant_setting('pwa_short_name', $appName) }}">

{{-- ── Open Graph / SEO Meta Tags ──────────────────────────── --}}
<meta name="description" content="{{ tenant_setting('pwa_description', 'نظام إدارة القضايا القانونية المتكاملة') }}">
<meta property="og:title"       content="{{ $appName }}">
<meta property="og:description" content="{{ tenant_setting('pwa_description', '') }}">
<meta property="og:type"        content="website">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $appName)</title>

    @if(is_rtl())
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;700;800;900&family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    @else
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @endif
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/toaster.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.2.3/css/flag-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @livewireStyles
    <style>:root { --gold-accent: {{ tenant_setting('primary_color', '#d4af37') }}; --primary-color: {{ tenant_setting('primary_color', '#d4af37') }}; }</style>
    <style>
        @media print {
            .navbar, .sidebar, .sidebar-overlay, footer { display: none !important; }
            .main-wrapper { margin: 0 !important; width: 100% !important; }
            main { padding: 0 !important; }
        }

        /* ============================================================
           LIGHT MODE (default)
           ============================================================ */
        :root,
        [data-theme="light"] {
            --primary-bg:      #f8fafc;
            --navbar-bg:       rgba(248, 250, 252, 0.95);
            --sidebar-bg:      #1e293b;
            --accent-color:    #0f172a;
            --gold-accent:     #d4af37;

            --text-primary:    #0f172a;
            --text-secondary:  #475569;
            --border-color:    #e2e8f0;

            --card-bg:         #ffffff;
            --input-bg:        #ffffff;
            --input-focus-bg:  #ffffff;

            --shadow-sm:       0 1px 3px rgba(0,0,0,0.05);
            --shadow-md:       0 4px 6px -1px rgba(0,0,0,0.05);

            --transition:      all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);

            --sidebar-width:   260px;
            --sidebar-mini:    80px;
            --sidebar-collapsed-width: 72px;

            /* Notification colors */
            --notif-unread-bg: #fffbeb;
            --notif-read-bg:   #f8fafc;

            /* Toggle */
            --toggle-bg:       #e2e8f0;
            --toggle-knob:     #ffffff;
        }

        /* ============================================================
           DARK MODE
           ============================================================ */
        [data-theme="dark"] {
            --primary-bg:      #0f172a;
            --navbar-bg:       rgba(15, 23, 42, 0.92);
            --sidebar-bg:      #0d1b2e;
            --accent-color:    #e2e8f0;
            --gold-accent:     #d4af37;

            --text-primary:    #e2e8f0;
            --text-secondary:  #94a3b8;
            --border-color:    #1e3a5f;

            --card-bg:         #1e293b;
            --input-bg:        rgba(255,255,255,0.06);
            --input-focus-bg:  rgba(255,255,255,0.10);

            --shadow-sm:       0 1px 3px rgba(0,0,0,0.3);
            --shadow-md:       0 4px 6px -1px rgba(0,0,0,0.3);

            /* Notification colors — darkened tints that remain distinct */
            --notif-unread-bg: rgba(212, 175, 55, 0.08);
            --notif-read-bg:   rgba(255,255,255,0.03);

            /* Toggle */
            --toggle-bg:       #334155;
            --toggle-knob:     #d4af37;
        }

        /* ── dark navbar glass ── */
        [data-theme="dark"] .navbar {
            border-bottom-color: rgba(30,58,95,0.6);
        }
        [data-theme="dark"] .btn-icon {
            background-color: rgba(255,255,255,0.06);
            border-color: var(--border-color);
            color: var(--text-secondary);
        }
        [data-theme="dark"] .btn-icon:hover {
            background-color: rgba(255,255,255,0.12);
            color: var(--text-primary);
        }
        [data-theme="dark"] .navbar-user:hover {
            background-color: rgba(255,255,255,0.06);
            border-color: var(--border-color);
        }
        [data-theme="dark"] .toggle-btn:hover {
            background-color: rgba(255,255,255,0.08);
        }

        /* ── light mode base & cards ── */
        [data-theme="light"] body,
        [data-theme="light"] .main-wrapper,
        [data-theme="light"] .main-content,
        [data-theme="light"] main {
            background-color: #f8fafc !important;
            color: #0f172a !important;
        }

        [data-theme="light"] .stat-card,
        [data-theme="light"] .show-card,
        [data-theme="light"] .card,
        [data-theme="light"] .panel {
            background-color: #ffffff !important;
            border-color: #e2e8f0 !important;
            color: #0f172a;
        }

        /* ── dark mode base & cards ── */
        [data-theme="dark"] body,
        [data-theme="dark"] .main-wrapper,
        [data-theme="dark"] .main-content,
        [data-theme="dark"] main {
            background-color: #0f172a !important;
            color: #e2e8f0 !important;
        }

        [data-theme="dark"] .stat-card,
        [data-theme="dark"] .show-card,
        [data-theme="dark"] .card,
        [data-theme="dark"] .panel {
            background-color: var(--card-bg) !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary);
        }

        /* ── dark footer ── */
        [data-theme="dark"] footer {
            border-top-color: var(--border-color);
        }

        /* ── dark scrollbar ── */
        [data-theme="dark"] ::-webkit-scrollbar-track { background: var(--primary-bg); }
        [data-theme="dark"] ::-webkit-scrollbar-thumb { background: #334155; }
        [data-theme="dark"] ::-webkit-scrollbar-thumb:hover { background: #475569; }

        /* ── dark sidebar section title ── */
        [data-theme="dark"] .sidebar-section-title { color: #64748b; }

        /* ============================================================
           GLOBAL RESET & BASE
           ============================================================ */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--primary-bg); }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: var(--primary-bg);
            color: var(--text-primary);
            width: 100%;
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 0;
            inset-inline-start: 0;
            width: var(--sidebar-width);
            min-width: var(--sidebar-width);
            height: 100vh;
            z-index: 40;
            background-color: var(--sidebar-bg);
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 15px rgba(0,0,0,0.08);
        }

        .sidebar-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 1.5rem; height: 72px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            transition: var(--transition);
            overflow: hidden;
            white-space: nowrap;
        }

        .brand-link { display: flex; align-items: center; gap: 0.75rem; text-decoration: none; }
        .brand-link i { font-size: 1.5rem; color: var(--gold-accent); transition: var(--transition); }
        .brand-name { font-size: 1.3rem; font-weight: 800; color: #ffffff; letter-spacing: 1px; transition: var(--transition); }

        .sidebar-close { display: none; background: none; border: none; color: #94a3b8; font-size: 1.2rem; cursor: pointer; }

        .sidebar-nav { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 1.5rem 0; }
        
        .sidebar-link {
            display: flex; align-items: center; gap: 1rem; padding: 0.8rem 1.5rem;
            color: #94a3b8; text-decoration: none; font-size: 0.95rem; font-weight: 500;
            transition: 0.2s ease; border-inline-end: 3px solid transparent;
            white-space: nowrap;
        }
        .sidebar-link i { font-size: 1.1rem; width: 24px; text-align: center; transition: var(--transition); }
        .sidebar-link span { transition: var(--transition); max-width: 200px; display: inline-block; white-space: nowrap; overflow: hidden; opacity: 1; }
        
        .sidebar-link:hover { color: #ffffff; background-color: rgba(255,255,255,0.03); }
        .sidebar-link.active { background-color: rgba(212, 175, 55, 0.1); border-inline-end-color: var(--gold-accent); color: var(--gold-accent); }

        .sidebar-section-title {
            padding: 1.5rem 1.5rem 0.5rem; font-size: 0.75rem; font-weight: 700;
            color: #475569; text-transform: uppercase; letter-spacing: 1px;
            white-space: nowrap; transition: var(--transition);
        }

        /* ===== DESKTOP COLLAPSED STATE ===== */
        body.sidebar-collapsed .sidebar { width: var(--sidebar-collapsed-width); min-width: var(--sidebar-collapsed-width); }
        body.sidebar-collapsed .sidebar-header { padding: 0; justify-content: center; }
        body.sidebar-collapsed .brand-name { opacity: 0; max-width: 0; margin: 0; padding: 0; overflow: hidden; }
        body.sidebar-collapsed .brand-link i { font-size: 1.8rem; }
        body.sidebar-collapsed .sidebar-link { padding: 0.8rem 0; justify-content: center; }
        body.sidebar-collapsed .sidebar-link i { font-size: 1.3rem; margin: 0; }
        body.sidebar-collapsed .sidebar-link span { opacity: 0; max-width: 0; margin: 0; padding: 0; }
        body.sidebar-collapsed .sidebar-section-title { opacity: 0; height: 0; padding: 0; overflow: hidden; margin: 0; }

        /* ===== MAIN WRAPPER ===== */
        .main-wrapper {
            margin-inline-start: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            flex: 1;
            display: flex;
            flex-direction: column;
            transition: var(--transition);
            min-height: 100vh;
            background-color: var(--primary-bg);
            color: var(--text-primary);
        }
        body.sidebar-collapsed .main-wrapper { 
            margin-inline-start: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background-color: var(--navbar-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.6);
            position: sticky;
            top: 0; 
            z-index: 1000;
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .toggle-btn {
            background: none; border: none; color: var(--text-secondary); 
            font-size: 1.2rem; cursor: pointer; transition: 0.2s ease;
            width: 40px; height: 40px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
        }
        .toggle-btn:hover { background-color: rgba(0,0,0,0.04); color: var(--text-primary); }

        .navbar-user-section { display: flex; align-items: center; gap: 1rem; }

        .btn-icon {
            display: flex; align-items: center; justify-content: center;
            width: 38px; height: 38px; border-radius: 8px;
            background-color: var(--card-bg); border: 1px solid var(--border-color);
            color: var(--text-secondary); text-decoration: none; transition: 0.2s;
            box-shadow: var(--shadow-sm);
        }
        .btn-icon:hover { background-color: var(--primary-bg); color: var(--accent-color); transform: translateY(-1px); }

        .navbar-user { display: flex; align-items: center; gap: 0.75rem; cursor: pointer; padding: 0.4rem 0.75rem; border-radius: 8px; transition: 0.2s; border: 1px solid transparent; }
        .navbar-user:hover { background-color: var(--card-bg); border-color: var(--border-color); box-shadow: var(--shadow-sm); }
        .user-avatar { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; }
        .user-name { font-weight: 600; font-size: 0.9rem; color: var(--text-primary); }

        /* ===== DARK MODE TOGGLE ===== */
        .theme-toggle {
            position: relative;
            display: inline-flex; align-items: center; justify-content: center;
            width: 38px; height: 38px; border-radius: 8px;
            background-color: var(--card-bg); border: 1px solid var(--border-color);
            cursor: pointer; transition: 0.2s; color: var(--text-secondary);
            box-shadow: var(--shadow-sm);
        }
        .theme-toggle:hover { background-color: var(--primary-bg); color: var(--accent-color); transform: translateY(-1px); }
        .theme-toggle .icon-sun, .theme-toggle .icon-moon { transition: opacity 0.25s, transform 0.3s; position: absolute; }
        [data-theme="light"] .icon-moon { opacity: 0; transform: rotate(90deg) scale(0.5); }
        [data-theme="light"] .icon-sun  { opacity: 1; transform: rotate(0deg)   scale(1); }
        [data-theme="dark"]  .icon-sun  { opacity: 0; transform: rotate(-90deg) scale(0.5); }
        [data-theme="dark"]  .icon-moon { opacity: 1; transform: rotate(0deg)   scale(1); }

        /* ===== NOTIFICATION BELL ===== */
        .notif-bell {
            position: relative; display: inline-flex; align-items: center; 
            justify-content: center; width: 38px; height: 38px; 
            border-radius: 8px;
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            color: var(--text-secondary); text-decoration: none; transition: 0.2s;
            box-shadow: var(--shadow-sm);
        }
        .notif-bell:hover { background-color: var(--primary-bg); color: var(--accent-color); transform: translateY(-1px); }
        .notif-bell i { font-size: 1.05rem; }
        .notif-badge {
            position: absolute; inset-block-start: -5px; inset-inline-start: -5px;
            background: #ef4444; color: #ffffff;
            font-size: 0.65rem; font-weight: 800; min-width: 17px; height: 17px;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            border: 2px solid var(--card-bg); padding: 0 2px; line-height: 1;
        }

        /* ===== CONTENT & FOOTER ===== */
        main {
            padding: 2rem;
            flex: 1;
            background-color: var(--primary-bg);
            color: var(--text-primary);
        }

        footer {
            background-color: transparent;
            padding: 1.5rem 2rem;
            color: var(--text-secondary);
            font-size: 0.85rem;
            border-top: 1px solid var(--border-color);
            transition: border-color 0.3s ease;
        }
        .footer-content { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
        .footer-links a { color: var(--text-secondary); text-decoration: none; margin-inline-end: 1.5rem; transition: 0.2s; }
        .footer-links a:hover { color: var(--accent-color); }

        /* ===== OVERLAY ===== */
        .sidebar-overlay { position: fixed; inset: 0; background-color: rgba(15, 23, 42, 0.6); z-index: 1999; opacity: 0; pointer-events: none; transition: 0.3s ease; backdrop-filter: blur(2px); }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 992px) {
            .sidebar {
                inset-inline-start: calc(-1 * var(--sidebar-width)) !important;
                width: 280px !important;
                min-width: 280px !important;
            }
            .sidebar.mobile-open {
                inset-inline-start: 0 !important;
            }
            .sidebar-close { display: block; }
            .main-wrapper {
                margin-inline-start: 0 !important;
                width: 100% !important;
            }
            .navbar { padding: 0 1rem; }
            main { padding: 1.5rem 1rem; }
            .user-name, .footer-links { display: none; }
            .footer-content { justify-content: center; }
            .sidebar-overlay.active { opacity: 1; pointer-events: all; }
        }

        /* ── AI FAB Button Hover Effects ── */
        .ai-fab-btn:hover {
            transform: scale(1.12) rotate(-6deg);
            box-shadow: 0 14px 36px rgba(15, 23, 42, 0.75);
            border-color: var(--gold-accent, #d4af37) !important;
        }
    </style>
    @stack('styles')
</head>
<body>
<script>
    if (localStorage.getItem('elmizzan_sidebar_collapsed') === '1') {
        document.body.classList.add('sidebar-collapsed');
    }
</script>

@auth
    @if(auth()->user()->isAdmin() && !session('is_demo') && !tenant_setting('firm_country'))
        @livewire('onboarding.country-picker-modal')
    @endif
@endauth

@include('layouts.toaster')

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="/" wire:navigate class="brand-link">
            <i class="fas fa-scale-balanced"></i>
            <span class="brand-name">{{ $appName }}</span>
        </a>
        <button class="sidebar-close" id="mobileSidebarClose">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <nav class="sidebar-nav">
        <a href="/home" wire:navigate class="sidebar-link {{ request()->is('home') ? 'active' : '' }}" title="{{ __('Dashboard') }}">
            <i class="fas fa-home"></i> <span>{{ __('Dashboard') }}</span>
        </a>
        <a href="/cases" wire:navigate class="sidebar-link {{ request()->is('cases*') ? 'active' : '' }}" title="{{ __('Cases') }}">
            <i class="fas fa-folder-open"></i> <span>{{ __('Cases') }}</span>
        </a>

        @if(auth()->check() && auth()->user()->role === 'admin')
            <a href="/clients" wire:navigate class="sidebar-link {{ request()->is('clients*') ? 'active' : '' }}" title="{{ __('Clients') }}">
                <i class="fas fa-users"></i> <span>{{ __('Clients') }}</span>
            </a>
            <a href="/appointments" wire:navigate class="sidebar-link {{ request()->is('appointments*') ? 'active' : '' }}" title="{{ __('Appointments') }}">
                <i class="fas fa-calendar-check"></i> <span>{{ __('Appointments') }}</span>
            </a>
            <a href="/document/index" wire:navigate class="sidebar-link {{ request()->is('document*') ? 'active' : '' }}" title="{{ __('Documents') }}">
                <i class="fas fa-file-alt"></i> <span>{{ __('Documents') }}</span>
            </a>
            <a href="/lawyers" wire:navigate class="sidebar-link {{ request()->is('lawyers*') ? 'active' : '' }}" title="{{ __('Lawyers') }}">
                <i class="fas fa-user-tie"></i> <span>{{ __('Lawyers') }}</span>
            </a>
            <a href="{{ route('contracts.index') }}" wire:navigate class="sidebar-link {{ request()->is('contracts*') ? 'active' : '' }}" title="{{ __('Legal Library') }}">
                <i class="fas fa-book"></i> <span>{{ __('Legal Library') }}</span>
            </a>
            <a href="{{ route('workspace.chat') }}" wire:navigate class="sidebar-link {{ request()->is('workspace/chat*') ? 'active' : '' }}" title="{{ __('Internal Messages') }}">
                <i class="fas fa-comments"></i> <span>{{ __('Internal Messages') }}</span>
            </a>

            <div class="sidebar-section-title">{{ __('Settings') }}</div>
            <a href="{{ route('jurisdictions.index') }}" wire:navigate class="sidebar-link {{ request()->is('jurisdictions*') ? 'active' : '' }}" title="{{ __('Jurisdictions') }}">
                <i class="fas fa-sitemap"></i> <span>{{ __('Jurisdictions') }}</span>
            </a>
            <a href="{{ route('courts.index') }}" wire:navigate class="sidebar-link {{ request()->is('courts*') ? 'active' : '' }}" title="{{ __('Courts') }}">
                <i class="fas fa-landmark"></i> <span>{{ __('Courts') }}</span>
            </a>
            <a href="{{ route('settings.edit') }}" wire:navigate class="sidebar-link {{ request()->is('settings*') ? 'active' : '' }}" title="{{ __('Office Settings') }}">
                <i class="fas fa-cog"></i> <span>{{ __('Office Settings') }}</span>
            </a>
        @endif

        @if(auth()->check() && auth()->user()->role === 'lawyer')
            <a href="/clients" wire:navigate class="sidebar-link {{ request()->is('clients*') ? 'active' : '' }}" title="{{ __('Clients') }}">
                <i class="fas fa-users"></i> <span>{{ __('Clients') }}</span>
            </a>
            <a href="/appointments" wire:navigate class="sidebar-link {{ request()->is('appointments*') ? 'active' : '' }}" title="{{ __('Appointments') }}">
                <i class="fas fa-calendar-check"></i> <span>{{ __('Appointments') }}</span>
            </a>
            <a href="/document/index" wire:navigate class="sidebar-link {{ request()->is('document*') ? 'active' : '' }}" title="{{ __('Documents') }}">
                <i class="fas fa-file-alt"></i> <span>{{ __('Documents') }}</span>
            </a>
             <a href="{{ route('contracts.index') }}" wire:navigate class="sidebar-link {{ request()->is('contracts*') ? 'active' : '' }}" title="{{ __('Legal Library') }}">
                <i class="fas fa-book"></i> <span>{{ __('Legal Library') }}</span>
            </a>
            <a href="{{ route('workspace.chat') }}" wire:navigate class="sidebar-link {{ request()->is('workspace/chat*') ? 'active' : '' }}" title="{{ __('Internal Messages') }}">
                <i class="fas fa-comments"></i> <span>{{ __('Internal Messages') }}</span>
            </a>
            <a href="/lawyers/{{ auth()->user()->lawyer_id }}" wire:navigate class="sidebar-link" title="{{ __('My Profile') }}">
                <i class="fas fa-user-circle"></i> <span>{{ __('My Profile') }}</span>
            </a>
        @endif

        @if(auth()->check())
            <a href="{{ route('logout') }}" class="sidebar-link" title="{{ __('Logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               style="margin-top: 1rem; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 1.5rem;">
                <i class="fas fa-sign-out-alt"></i> <span>{{ __('Logout') }}</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                @csrf
            </form>
        @endif
    </nav>
</aside>

<div class="main-wrapper">

    {{-- ═══ Demo Mode Banner ═══════════════════════════════════════════ --}}
    @if(session('is_demo'))
        <div id="demo-banner" style="
            position: relative; z-index: 1001;
            background: linear-gradient(90deg, #b45309, #d97706, #f59e0b, #d97706, #b45309);
            background-size: 300% 100%;
            animation: demoBannerShimmer 4s linear infinite;
            color: #0f172a; text-align: center; padding: 0.6rem 1.25rem;
            font-size: 0.88rem; font-weight: 800; display: flex; align-items: center;
            justify-content: center; gap: 1rem; letter-spacing: 0.2px;
            box-shadow: 0 2px 8px rgba(180, 83, 9, 0.2);
        ">
            <span>{{ __('You are viewing the interactive demo — data shown is for demonstration only') }}</span>
            <form action="{{ route('demo.exit') }}" method="POST" style="display: inline; margin: 0;">
                @csrf
                <button type="submit" style="
                    background: #0f172a; color: #ffffff; padding: 0.35rem 0.9rem;
                    border-radius: 6px; font-size: 0.8rem; font-weight: 700;
                    border: 1px solid rgba(255,255,255,0.2); cursor: pointer;
                    display: inline-flex; align-items: center; gap: 0.4rem;
                    transition: all 0.2s; font-family: inherit;
                " onmouseover="this.style.background='#1e293b'" onmouseout="this.style.background='#0f172a'">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>{{ __('Exit Demo') }}</span>
                </button>
            </form>
        </div>
        <style>
            @keyframes demoBannerShimmer { 0%{background-position:0%} 100%{background-position:200%} }
        </style>
    @endif
    
    <header class="navbar">
        <button class="toggle-btn" id="mainToggleBtn" aria-label="تبديل القائمة">
            <i class="fas fa-bars"></i>
        </button>

        <div class="navbar-user-section">

            {{-- ── Locale Switcher ── --}}
            <livewire:settings.locale-switcher />

            {{-- ── Dark / Light Mode Toggle ── --}}
            <button class="theme-toggle" id="themeToggle" aria-label="تبديل المظهر" title="تبديل الوضع المظلم / الفاتح">
                <i class="fas fa-sun  icon-sun"  style="font-size:1rem;"></i>
                <i class="fas fa-moon icon-moon" style="font-size:1rem;"></i>
            </button>

            {{-- ── Notification Bell (Livewire) ── --}}
            <livewire:notifications.notification-bell />

            {{-- ── Workspace Message Bell ── --}}
            @if(auth()->check() && !auth()->user()->isClient())
                <livewire:messaging.message-bell />
            @endif

            {{-- ── User Avatar ── --}}
            @if(auth()->check())
            @php
                $profileImage = null;
                if (auth()->user()->role === 'lawyer' && auth()->user()->lawyer) {
                    $profileImage = auth()->user()->lawyer->profile_image
                        ? asset('storage/' . auth()->user()->lawyer->profile_image)
                        : null;
                }
                $avatarUrl = $profileImage
                    ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=1e293b&color=d4af37';
                $profileUrl = auth()->user()->role === 'lawyer'
                    ? '/lawyers/' . auth()->user()->lawyer_id
                    : '/home';
            @endphp
            <a href="{{ $profileUrl }}" wire:navigate class="navbar-user" style="text-decoration: none;">
                <img src="{{ $avatarUrl }}" alt="صورة المستخدم" class="user-avatar">
                <span class="user-name" style="display: inline-flex; align-items: center; gap: 0.45rem;">
                    <span>{{ auth()->user()->name }}</span>
                    @if(session('demo_country'))
                        <x-flag-icon :country="session('demo_country')" size="1.2rem" />
                    @elseif(tenant_setting('firm_country'))
                        <x-flag-icon :country="tenant_setting('firm_country')" size="1.2rem" />
                    @endif
                </span>
                <i class="fas fa-chevron-down" style="font-size: 0.75rem; color: var(--text-secondary);"></i>
            </a>
            @endif

        </div>
    </header>

    <main>
        @if(isset($slot))
            {{ $slot }}
        @else
            @yield('content')
        @endif
    </main>

    <footer>
        <div class="footer-content">
            <div>© {{ date('Y') }} {{ $appName }}. {{ is_rtl() ? 'جميع الحقوق محفوظة.' : 'All rights reserved.' }}</div>
            <div class="footer-links">
                <a href="#">{{ is_rtl() ? 'سياسة الخصوصية' : 'Privacy Policy' }}</a>
                <a href="#">{{ is_rtl() ? 'الدعم الفني' : 'Support' }}</a>
            </div>
        </div>
    </footer>
</div>

{{-- ── Floating Scale FAB (Pro Teaser) ── --}}
<div id="ai-teaser-widget"
     style="position: fixed; bottom: 1.5rem; inset-inline-start: 1.5rem; z-index: 500; width: 62px; height: 62px; touch-action: none; user-select: none;">

    <div class="ai-fab-btn"
         style="width: 62px; height: 62px; border-radius: 50%;
                background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
                display: flex; align-items: center; justify-content: center;
                box-shadow: 0 10px 28px rgba(15, 23, 42, 0.55);
                border: 2px solid rgba(212, 175, 55, 0.45);
                transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s;
                cursor: grab;">
        <i class="fas fa-scale-balanced" style="font-size: 1.75rem; color: #d4af37; pointer-events: none;"></i>
    </div>

    {{-- PRO badge at top-end corner --}}
    <div style="position: absolute; top: -4px; inset-inline-end: -4px;
                background: #ef4444; color: #ffffff; font-size: 0.62rem; font-weight: 900;
                padding: 2px 6px; border-radius: 10px; line-height: 1.5;
                border: 2px solid #ffffff; box-shadow: 0 2px 8px rgba(0,0,0,0.35);
                z-index: 60; pointer-events: none; letter-spacing: 0.5px;">PRO</div>
</div>

<div id="ai-teaser-modal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(6px);">
    <div style="background: var(--card-bg); width: 90%; max-width: 420px; border-radius: 20px; border: 1px solid var(--border-color); box-shadow: 0 25px 60px rgba(0,0,0,0.5); overflow: hidden; position: relative;">
        <button onclick="document.getElementById('ai-teaser-modal').style.display='none'" style="position: absolute; top: 1rem; inset-inline-end: 1rem; background: none; border: none; font-size: 1.2rem; color: var(--text-secondary); cursor: pointer;"><i class="fas fa-times"></i></button>
        <div style="padding: 2.2rem 1.8rem; text-align: center;">
            <div style="width: 80px; height: 80px; background: rgba(212, 175, 55, 0.12); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <i class="fas fa-scale-balanced" style="font-size: 2.4rem; color: #d4af37;"></i>
            </div>
            <h3 style="font-size: 1.35rem; font-weight: 900; color: var(--text-primary); margin-bottom: 0.5rem;">{{ __('Smart Legal Assistant (AI)') }}</h3>
            <p style="font-size: 0.95rem; color: var(--text-secondary); line-height: 1.6; margin-bottom: 1.75rem;">
                {{ __('This feature is exclusively available for Pro subscribers...') }}
            </p>
            <button onclick="document.getElementById('ai-teaser-modal').style.display='none'" style="width: 100%; padding: 0.85rem; background: linear-gradient(135deg, #d4af37 0%, #b8960c 100%); color: #0f172a; border: none; border-radius: 12px; font-size: 1rem; font-weight: 800; font-family: inherit; cursor: pointer; box-shadow: 0 4px 15px rgba(212,175,55,0.3);">{{ __('Upgrade Account Now') }}</button>
        </div>
    </div>
</div>



@stack('scripts')
@livewireScripts

<script>
    /* ===================================================
       1. THEME (Dark / Light) — localStorage persistence
       =================================================== */
    function applySavedTheme() {
        const stored = localStorage.getItem('elmizzan_theme') || 'light';
        document.documentElement.setAttribute('data-theme', stored);
    }
    applySavedTheme();

    function initThemeToggle() {
        const themeToggle = document.getElementById('themeToggle');
        if (themeToggle && !themeToggle._hasThemeListener) {
            themeToggle._hasThemeListener = true;
            themeToggle.addEventListener('click', function () {
                const html    = document.documentElement;
                const current = html.getAttribute('data-theme');
                const next    = current === 'dark' ? 'light' : 'dark';
                html.setAttribute('data-theme', next);
                localStorage.setItem('elmizzan_theme', next);
            });
        }
    }

    /* ===================================================
       2. SIDEBAR — toggle & mobile off-canvas
       =================================================== */
    function initSidebar() {
        const sidebar      = document.getElementById('sidebar');
        const overlay      = document.getElementById('sidebarOverlay');
        const mainToggle   = document.getElementById('mainToggleBtn');
        const mobileClose  = document.getElementById('mobileSidebarClose');

        if (!sidebar || !mainToggle) return;

        function toggleSidebar() {
            if (window.innerWidth <= 992) {
                sidebar.classList.toggle('mobile-open');
                overlay.classList.toggle('active');
                document.body.style.overflow = sidebar.classList.contains('mobile-open') ? 'hidden' : '';
            } else {
                document.body.classList.toggle('sidebar-collapsed');
                localStorage.setItem('elmizzan_sidebar_collapsed', document.body.classList.contains('sidebar-collapsed') ? '1' : '0');
            }
        }

        function closeMobileSidebar() {
            if (window.innerWidth <= 992) {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        }

        if (!mainToggle._hasSidebarListener) {
            mainToggle._hasSidebarListener = true;
            mainToggle.addEventListener('click', toggleSidebar);
        }
        if (mobileClose && !mobileClose._hasSidebarListener) {
            mobileClose._hasSidebarListener = true;
            mobileClose.addEventListener('click', closeMobileSidebar);
        }
        if (overlay && !overlay._hasSidebarListener) {
            overlay._hasSidebarListener = true;
            overlay.addEventListener('click', closeMobileSidebar);
        }

        window.addEventListener('resize', () => {
            if (window.innerWidth > 992) closeMobileSidebar();
        });
    }

    /* ===================================================
       3. DRAGGABLE FAB LOGIC (Pointer Events)
       =================================================== */
    function initDraggableFAB() {
        const widget = document.getElementById('ai-teaser-widget');
        if (!widget) return;

        // Prevent re-initialization
        if (widget._fabInitialized) return;
        widget._fabInitialized = true;

        let isDragging  = false;
        let hasDragged  = false;
        let startX, startY, originLeft, originTop;

        widget.addEventListener('pointerdown', function (e) {
            // Only primary pointer (left mouse / first touch)
            if (e.button !== undefined && e.button !== 0) return;

            isDragging = false;
            hasDragged = false;

            const rect = widget.getBoundingClientRect();
            originLeft = rect.left;
            originTop  = rect.top;
            startX = e.clientX;
            startY = e.clientY;

            widget.setPointerCapture(e.pointerId);
            widget.style.cursor = 'grabbing';
            e.preventDefault();
        });

        widget.addEventListener('pointermove', function (e) {
            if (!widget.hasPointerCapture(e.pointerId)) return;

            const diffX = e.clientX - startX;
            const diffY = e.clientY - startY;

            if (!isDragging && (Math.abs(diffX) > 5 || Math.abs(diffY) > 5)) {
                isDragging = true;
                hasDragged = true;
                // Override CSS positional shorthand so left/top take effect
                widget.style.insetInlineStart = 'auto';
                widget.style.bottom          = 'auto';
            }

            if (isDragging) {
                const newLeft = Math.max(0, Math.min(originLeft + diffX, window.innerWidth  - widget.offsetWidth));
                const newTop  = Math.max(0, Math.min(originTop  + diffY, window.innerHeight - widget.offsetHeight));
                widget.style.left = newLeft + 'px';
                widget.style.top  = newTop  + 'px';
            }
        });

        widget.addEventListener('pointerup', function (e) {
            widget.releasePointerCapture(e.pointerId);
            widget.style.cursor = 'grab';

            if (!hasDragged) {
                // Pure click — open modal
                document.getElementById('ai-teaser-modal').style.display = 'flex';
            }

            isDragging = false;
        });

        widget.addEventListener('pointercancel', function (e) {
            widget.releasePointerCapture(e.pointerId);
            widget.style.cursor = 'grab';
            isDragging = false;
        });

        // Prevent default click from firing after a drag
        widget.addEventListener('click', function (e) {
            if (hasDragged) {
                e.preventDefault();
                e.stopPropagation();
                hasDragged = false;
            }
        });
    }

    // Initialize on page load
    initThemeToggle();
    initSidebar();
    initDraggableFAB();

    // Re-initialize listeners and re-apply theme on wire:navigate
    document.addEventListener('livewire:navigated', () => {
        applySavedTheme();
        initThemeToggle();
        initSidebar();
        initDraggableFAB();
    });
</script>
</body>
</html>
