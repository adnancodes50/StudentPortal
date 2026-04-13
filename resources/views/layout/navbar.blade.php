<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom">
    <div class="container">

        {{-- Logo --}}
        <a class="navbar-brand fw-bold d-flex align-items-center text-dark" href="{{ url('/') }}">
            <span style="font-size: 1.5rem;">✈</span>
            <span class="ms-2">Airline Ticket</span>
        </a>

        {{-- Mobile Toggle --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">

            {{-- Left Menu --}}
            <ul class="navbar-nav me-auto ms-3">

                @auth
                <li class="nav-item">
                    <a class="nav-link text-dark fw-semibold {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-dark fw-semibold" href="#">
                        Flights
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-dark fw-semibold" href="#">
                        Bookings
                    </a>
                </li>
                @endauth

            </ul>

            {{-- Right Side --}}
            <ul class="navbar-nav ms-auto align-items-center">

                @guest
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm px-3 rounded-pill" href="{{ route('login') }}">
                            Login
                        </a>
                    </li>
                @endguest

                @auth
                <li class="nav-item dropdown">

                    {{-- User --}}
                    <a class="nav-link dropdown-toggle d-flex align-items-center text-dark" href="#" role="button" data-bs-toggle="dropdown">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}"
                             class="rounded-circle me-2" width="35" height="35">
                        <span class="fw-semibold">{{ Auth::user()->name }}</span>
                    </a>

                    {{-- Dropdown --}}
                    <ul class="dropdown-menu dropdown-menu-end shadow">

                        <li class="px-3 py-2 text-muted small">
                            {{ Auth::user()->email }}
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <a class="dropdown-item" href="{{ route('home') }}">
                                Dashboard
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <a class="dropdown-item text-danger"
                               href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                        </li>
                    </ul>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>

                </li>
                @endauth

            </ul>

        </div>
    </div>
</nav>
