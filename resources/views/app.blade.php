<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- SEO Meta Tags --}}
        <meta name="description" content="Intergeo - Jasa Survey dan Pemetaan profesional. Layanan survey tanah, pemetaan, dan konsultasi geospasial terpercaya di Indonesia.">
        <meta name="keywords" content="survey tanah, pemetaan, jasa survey, konsultasi geospasial, surveyor, mapping, GIS, GPS, drone mapping, topografi">
        <meta name="author" content="Intergeo">
        <meta name="robots" content="index, follow">
        <meta name="googlebot" content="index, follow">
        
        {{-- Open Graph Meta Tags --}}
        <meta property="og:type" content="website">
        <meta property="og:title" content="Intergeo - Jasa Survey dan Pemetaan Profesional">
        <meta property="og:description" content="Layanan survey tanah, pemetaan, dan konsultasi geospasial terpercaya di Indonesia.">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:site_name" content="Intergeo">
        <meta property="og:image" content="{{ url('/images/og-image.jpg') }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:locale" content="id_ID">
        
        {{-- Twitter Card Meta Tags --}}
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="Intergeo - Jasa Survey dan Pemetaan Profesional">
        <meta name="twitter:description" content="Layanan survey tanah, pemetaan, dan konsultasi geospasial terpercaya di Indonesia.">
        <meta name="twitter:image" content="{{ url('/images/og-image.jpg') }}">
        
        {{-- Additional SEO Meta Tags --}}
        <meta name="theme-color" content="#2563eb">
        <meta name="msapplication-TileColor" content="#2563eb">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="Intergeo">
        
        {{-- Canonical URL --}}
        <link rel="canonical" href="{{ url()->current() }}">
        
        {{-- DNS Prefetch --}}
        <link rel="dns-prefetch" href="//fonts.bunny.net">
        <link rel="dns-prefetch" href="//www.google-analytics.com">
        <link rel="dns-prefetch" href="//www.googletagmanager.com">
        
        {{-- Preconnect for performance --}}
        <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
        <link rel="preconnect" href="https://www.google-analytics.com" crossorigin>
        <link rel="preconnect" href="https://www.googletagmanager.com" crossorigin>

        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        {{-- Inline style to set the HTML background color based on our theme in app.css --}}
        <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }
        </style>

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" href="/favicon.png" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @routes
        @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        @inertiaHead

        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-804QVKBZCD"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'G-804QVKBZCD');
        </script>
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
