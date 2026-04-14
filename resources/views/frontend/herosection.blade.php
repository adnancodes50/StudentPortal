<section class="front-hero py-5">
    <div class="container py-4 py-lg-5">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div class="d-inline-flex align-items-center gap-2 glass-card px-3 py-2 mb-3">
                    <span class="badge rounded-pill text-bg-light">New Theme</span>
                    <span class="small text-white-50">Sky + Orange palette</span>
                </div>

                <h1 class="display-5 fw-semibold lh-sm mb-3">
                    Find flights faster and book with confidence
                </h1>
                <p class="lead text-white-50 mb-4">
                    Browse categories, compare schedules, and manage everything from one place.
                </p>

                <div class="d-flex flex-column flex-sm-row gap-2">
                    <a href="{{ url('/') }}#categories" class="btn btn-accent btn-lg">Explore Categories</a>
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg rounded-pill px-4">Login</a>
                    @endguest
                    @auth
                        <a href="{{ route('home') }}" class="btn btn-outline-light btn-lg rounded-pill px-4">Go to Dashboard</a>
                    @endauth
                </div>
            </div>

            <div class="col-lg-5">
                <div class="glass-card p-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="fw-semibold">Secure</div>
                            <div class="text-white-50 small">Auth-protected actions</div>
                        </div>
                        <div class="col-6">
                            <div class="fw-semibold">Responsive</div>
                            <div class="text-white-50 small">Mobile-first layout</div>
                        </div>
                        <div class="col-6">
                            <div class="fw-semibold">Fast</div>
                            <div class="text-white-50 small">Clean, lightweight UI</div>
                        </div>
                        <div class="col-6">
                            <div class="fw-semibold">Admin</div>
                            <div class="text-white-50 small">Manage flights easily</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

