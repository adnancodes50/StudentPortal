@extends('adminlte::auth.auth-page', ['authType' => 'login'])

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">

    {{-- ✅ Custom Styling --}}
    <style>
        .login-box {
            width: 420px !important; /* bigger form */
        }

        .card {
            border-radius: 12px;
        }

        .login-logo b {
            color: #0d6efd;
        }

        .btn-primary {
            border-radius: 25px;
            font-weight: 600;
        }

        .login-logo {
            display: none !important;
        }
    </style>
@stop

@php
    $loginUrl = View::getSection('login_url') ?? config('adminlte.login_url', 'login');

    if (config('adminlte.use_route_url', false)) {
        $loginUrl = $loginUrl ? route($loginUrl) : '';
    } else {
        $loginUrl = $loginUrl ? url($loginUrl) : '';
    }
@endphp

{{-- ✅ HEADER --}}
@section('auth_header')
    <div class="text-center">
<img src="{{ asset('images/logo/logo.png') }}" alt="Logo" height="60" class="mb-2">
        <h4><b>Airline Ticket System</b></h4>
    </div>
@stop

@section('auth_body')

    <form action="{{ $loginUrl }}" method="post">
        @csrf

        {{-- EMAIL --}}
        <div class="input-group mb-3">
            <input type="email" name="email"
                class="form-control form-control-lg @error('email') is-invalid @enderror"
                value="{{ old('email') }}"
                placeholder="Enter Email" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>

            @error('email')
                <span class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- PASSWORD --}}
        <div class="input-group mb-4">
            <input type="password" name="password"
                class="form-control form-control-lg @error('password') is-invalid @enderror"
                placeholder="Enter Password">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>

            @error('password')
                <span class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- REMEMBER --}}
        <div class="mb-3">
            <div class="icheck-primary">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Remember Me</label>
            </div>
        </div>

        {{-- BUTTON --}}
        <div class="row">
            <div class="col-12">
                <button type="submit"
                    class="btn btn-primary btn-block btn-lg">
                    <span class="fas fa-sign-in-alt"></span>
                    Login
                </button>
            </div>
        </div>

    </form>

@stop