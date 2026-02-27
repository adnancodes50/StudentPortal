@extends('adminlte::page')

@section('title', 'manage your profile')

@section('content_header')
    <h1 class="m-0 text-dark">Profile</h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center mb-3">
                        <img class="profile-user-img img-fluid img-circle"
                             src="{{ asset('images/placeholder.png') }}"
                             alt="Student profile image">
                    </div>

                    <h3 class="profile-username text-center mb-1">
                        {{ $studentFullName ?? (auth()->user()->name ?? 'N/A') }}
                    </h3>
                    <p class="text-muted text-center mb-3">Student</p>

                    <ul class="list-group list-group-unbordered mb-0">
                        <li class="list-group-item">
                            <b>Registration No</b> <span class="float-right">{{ $student->arid_reg_no ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Email</b> <span class="float-right">{{ $student->primary_email ?? (auth()->user()->email ?? 'N/A') }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Semester</b> <span class="float-right">{{ $currentSemester ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Session</b> <span class="float-right">{{ $currentSession ?? 'N/A' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Account Status Form</h3>
                </div>
                <div class="card-body">
                    <form action="javascript:void(0);">
                        <div class="form-group">
                            <label for="old_username">Old Username</label>
                            <input type="text" class="form-control" id="old_username" name="old_username"
                                value="{{ auth()->user()->student_login_name ?? '' }}">
                        </div>

                        <div class="form-group">
                            <label for="new_username">New Username</label>
                            <input type="text" class="form-control" id="new_username" name="new_username"
                                placeholder="Enter new username">
                        </div>

                        <div class="form-group">
                            <label for="previous_password">Previous Password</label>
                            <input type="password" class="form-control" id="previous_password" name="previous_password"
                                placeholder="Enter previous password">
                        </div>

                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password"
                                placeholder="Enter new password">
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                placeholder="Confirm new password">
                        </div>

                        <button type="button" class="btn btn-info">Update Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
