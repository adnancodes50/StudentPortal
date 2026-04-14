@extends('frontend.layouts.app')

@section('title', $category->name . ' - ' . config('app.name', 'Student Portal'))

@section('content')
    <section class="py-4 py-lg-5">
        <div class="container">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
                <div>
                    <nav class="small text-muted-2 mb-2">
                        <a class="text-decoration-none text-muted-2" href="{{ url('/') }}">Home</a>
                        <span class="mx-1">/</span>
                        <span class="text-dark fw-semibold">{{ $category->name }}</span>
                    </nav>
                    <h1 class="h3 fw-semibold mb-1">{{ $category->name }} Flights</h1>
                    <div class="text-muted-2">Choose a schedule that fits your plan.</div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ url('/') }}" class="btn btn-outline-dark rounded-pill px-3">Back</a>
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-brand">Login</a>
                    @endguest
                </div>
            </div>

            @if (isset($category->flights) && $category->flights->count())
                <div class="row g-3">
                    @foreach ($category->flights as $flight)
                        <div class="col-12">
                            <div class="flight-card p-4">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-3">
                                        <div class="text-muted-2 small">Date</div>
                                        <div class="fw-semibold">
                                            {{ $flight->departure_date ?? 'N/A' }}
                                            @if (!empty($flight->return_date))
                                                <span class="text-muted-2 fw-normal">→</span> {{ $flight->return_date }}
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="text-muted-2 small">Flight</div>
                                        <div class="fw-semibold">{{ $flight->flight_number ?? 'SV000' }}</div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="text-muted-2 small">Time</div>
                                        <div class="fw-semibold">
                                            {{ $flight->departure_time ?? '00:00' }}
                                            <span class="text-muted-2 fw-normal">–</span>
                                            {{ $flight->arrival_time ?? '00:00' }}
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="text-muted-2 small">Included</div>
                                        <div class="d-flex flex-wrap gap-2">
                                            <span class="badge text-bg-light border">Bag: {{ $flight->baggage ?? '23KG' }}</span>
                                            <span class="badge text-bg-light border">Meal: {{ $flight->meal ?? 'YES' }}</span>
                                        </div>
                                    </div>

                                    <div class="col-md-2 text-md-end">
                                        <div class="text-muted-2 small">Fare</div>
                                        <div class="h5 fw-semibold mb-2">PKR {{ number_format($flight->price ?? 0) }}</div>

                                        @auth
                                            <a href="#" class="btn btn-accent btn-sm">Book Flight</a>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-brand btn-sm">Login to Book</a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-light border mb-0">
                    <div class="fw-semibold mb-1">No flights available</div>
                    <div class="text-muted-2 small">Add flights to this category from the admin panel.</div>
                </div>
            @endif
        </div>
    </section>
@endsection

