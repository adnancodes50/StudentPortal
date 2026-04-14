<nav class="navbar navbar-expand-lg front-navbar sticky-top">
    <div class="container py-2">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
            <span class="brand-badge" aria-hidden="true">✈</span>
            <span class="fw-semibold text-dark">{{ config('app.name', 'Student Portal') }}</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#frontNavbar"
            aria-controls="frontNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="frontNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active fw-semibold' : '' }}" href="{{ url('/') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}#categories">Group Tickets</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Umrah Calculator</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Umrah Package</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">Contact Us</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-lg-center gap-2">
                @guest
                    <li class="nav-item">
                        <a class="btn btn-signin btn-sm" href="{{ route('login') }}">Sign In</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-register btn-sm" href="{{ route('register') }}">Register</a>
                    </li>
                @endguest

                @auth
                    <li class="nav-item">
                        <a class="btn btn-outline-dark btn-sm rounded-pill px-3" href="{{ route('home') }}">Dashboard</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}"
                                class="rounded-circle" width="34" height="34" alt="">
                            <span class="fw-semibold text-dark">{{ Auth::user()->name }}</span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li class="px-3 py-2 text-muted small">{{ Auth::user()->email }}</li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="{{ route('home') }}">Dashboard</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form-front').submit();">
                                    Logout
                                </a>
                            </li>
                        </ul>

                        <form id="logout-form-front" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

