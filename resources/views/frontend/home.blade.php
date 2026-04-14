@extends('frontend.layouts.app')

@section('title', 'Home - ' . config('app.name', 'Student Portal'))

@section('content')
    <section class="portal-hero py-5" style="--hero-bg: url('{{ asset('images/portal-bg.jpg') }}');">
        <div class="container py-4">
            <div class="text-center mb-4 mb-lg-5">
                <div class="portal-kicker mb-2">Group Ticketing Portal</div>
                <h1 class="portal-title display-5 mb-0 text-uppercase">
                    {{ config('app.name', 'Airline Ticket System') }}
                </h1>
            </div>

            <div class="portal-panel" id="categories">
                @if (isset($categories) && $categories->count())
                    <div class="row g-4">
                        @foreach ($categories as $category)
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="portal-category position-relative h-100">
                                    @php
                                        $imageUrl = $category->image ? Storage::url($category->image) : 'https://via.placeholder.com/1200x800?text=Category';
                                    @endphp
                                    <img src="{{ $imageUrl }}" alt="{{ $category->name }}">
                                    <div class="portal-category-label">{{ $category->name }}</div>
                                    <a href="{{ route('category.show', $category->id) }}" class="stretched-link"
                                        aria-label="Open {{ $category->name }}"></a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-white">
                        <div class="fw-semibold">No categories found</div>
                        <div class="text-white-50 small mt-1">Add categories from the admin panel and mark them active.</div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

