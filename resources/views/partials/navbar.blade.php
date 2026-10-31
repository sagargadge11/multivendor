<nav class="navbar navbar-expand-lg bg-white border-bottom">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">Marketplace</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <div class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <a class="nav-link" href="{{ route('home') }}">Home</a>
                @guest
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                    <a class="btn btn-primary btn-sm" href="{{ route('register') }}">Register</a>
                @else
                    @if(auth()->user()->role === 'admin')
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                    @elseif(auth()->user()->role === 'vendor')
                        <a class="nav-link" href="{{ route('vendor.dashboard') }}">Vendor Dashboard</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-secondary btn-sm" type="submit">Logout</button>
                    </form>
                @endguest
            </div>
        </div>
    </div>
</nav>
