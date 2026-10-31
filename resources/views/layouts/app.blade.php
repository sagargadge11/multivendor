<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Marketplace')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background:#f7f8fa; color:#212529; }
        .navbar-brand { font-weight:700; }
        .hero { background:#172554; color:#fff; border-radius:1rem; }
        .product-card img { height:190px; object-fit:contain; background:#fff; }
        .footer { background:#172033; color:#fff; }
    </style>
    @stack('styles')
</head>
<body>
@include('partials.navbar')
<main class="py-4">@yield('content')</main>
@include('partials.footer')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
