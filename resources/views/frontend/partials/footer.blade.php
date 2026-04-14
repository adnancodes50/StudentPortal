<footer class="front-footer">
    <div class="container py-4" id="contact">
        <div class="row g-4 align-items-start">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="brand-badge" aria-hidden="true">✈</span>
                    <span class="fw-semibold">{{ config('app.name', 'Student Portal') }}</span>
                </div>
                <p class="text-muted-2 mb-0">
                    Simple, fast flight browsing with a fresh new theme. Manage categories and flights from the admin panel.
                </p>
            </div>
            <div class="col-md-3">
                <div class="fw-semibold mb-2">Quick Links</div>
                <div class="d-flex flex-column gap-1">
                    <a class="text-decoration-none text-muted-2" href="{{ url('/') }}">Home</a>
                    <a class="text-decoration-none text-muted-2" href="{{ url('/') }}#categories">Categories</a>
                    @auth
                        <a class="text-decoration-none text-muted-2" href="{{ route('home') }}">Dashboard</a>
                    @endauth
                </div>
            </div>
            <div class="col-md-3">
                <div class="fw-semibold mb-2">Support</div>
                <div class="text-muted-2 small">
                    Email: support@example.com<br>
                    Hours: 9:00–18:00
                </div>
            </div>
        </div>

        <hr class="my-4">

        <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 small text-muted-2">
            <div>© {{ date('Y') }} {{ config('app.name', 'Student Portal') }}. All rights reserved.</div>
            <div>Built with Laravel + Bootstrap.</div>
        </div>
    </div>
</footer>

