@extends('layouts.app')

@section('content')


{{-- Hero Section --}}
@include('frontend.herosection')

<style>
    /* ===== Category Card Styling ===== */
    .category-card {
        border-radius: 15px;
        overflow: hidden;
        transition: 0.3s;
        cursor: pointer;
        position: relative;
    }

    .category-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .category-card img {
        width: 100%;
        height: 220px;
        object-fit: cover;
    }

    .category-title {
        position: absolute;
        bottom: 0;
        width: 100%;
        background: #ffffff;
        text-align: center;
        padding: 12px;
        font-weight: 600;
        font-size: 16px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .category-card img {
            height: 180px;
        }
    }
</style>

<div class="container mt-5">
    <h2 class="text-center mb-4">Welcome to Airline Ticket System ✈️</h2>

    {{-- Categories --}}
    @if(isset($categories) && $categories->count())
        <div class="row">
            @foreach($categories as $category)
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="category-card">

                        {{-- Image --}}
                        @if($category->image)
                            <img src="{{ Storage::url($category->image) }}">
                        @else
                            <img src="https://via.placeholder.com/400x250">
                        @endif

                        {{-- Title --}}
                        <div class="category-title">
                            {{ $category->name }}
                        </div>

                        {{-- Clickable --}}
                        <a href="{{ route('category.show', $category->id) }}" class="stretched-link"></a>

                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-center text-muted">No categories found.</p>
    @endif
</div>


@endsection
