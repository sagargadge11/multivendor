<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','Admin') - Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body{background:#f5f6fa}.admin-top{background:#172033}.admin-sidebar{background:#fff;min-height:calc(100vh - 56px);border-right:1px solid #e5e7eb}
        .admin-sidebar .nav-link{color:#344054;padding:.7rem 1rem;border-radius:.4rem}.admin-sidebar .nav-link:hover{background:#f1f5f9}
        @media(max-width:767.98px){.admin-sidebar{min-height:auto;border-right:0;border-bottom:1px solid #e5e7eb}}
    </style>
    @stack('styles')
</head>
<body>
<nav class="navbar navbar-dark admin-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Marketplace Admin</a>
        <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-sm btn-outline-light">Logout</button></form>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">
        <aside class="col-md-3 col-lg-2 admin-sidebar p-3">
            <nav class="nav flex-column gap-1">
                <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                <a class="nav-link" href="{{ route('admin.vendors.index') }}"><i class="bi bi-shop me-2"></i>Vendors</a>
                <span class="nav-link text-muted"><i class="bi bi-box-seam me-2"></i>Products (Milestone 3)</span>
                <span class="nav-link text-muted"><i class="bi bi-tags me-2"></i>Categories</span>
                <span class="nav-link text-muted"><i class="bi bi-envelope me-2"></i>Enquiries</span>
                <span class="nav-link text-muted"><i class="bi bi-plug me-2"></i>Integrations</span>
            </nav>
        </aside>
        <main class="col-md-9 col-lg-10 p-3 p-lg-4">@yield('content')</main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
