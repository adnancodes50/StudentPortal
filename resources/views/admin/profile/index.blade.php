@extends('adminlte::page')

@section('title', 'manage your profile')

@section('css')
    <style>
        .profile-swal-popup {
            border: 0 !important;
            border-radius: 3px !important;
            background: #ffffff !important;
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.2) !important;
            padding: 1.2em 1em 0.8em !important;
            width: 420px !important;
        }

        .profile-swal-title {
            color: #3f3f46 !important;
            font-weight: 400 !important;
            font-size: 34px !important;
            line-height: 1.2 !important;
            margin-top: 8px !important;
        }

        .profile-swal-text {
            color: #8a8f98 !important;
            line-height: 1.4 !important;
            font-size: 15px !important;
            margin-top: 2px !important;
        }

        .profile-swal-btn {
            background: #2f86d6 !important;
            color: #fff !important;
            border: 1px solid #1f65a8;
            border-radius: 2px;
            min-width: 60px;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 400;
            box-shadow: none !important;
        }

        .swal2-icon.swal2-success .swal2-success-ring {
            border-color: rgba(120, 210, 110, 0.25) !important;
        }

        .swal2-icon.swal2-success [class^='swal2-success-line'] {
            background-color: #8fd17f !important;
        }
    </style>
@endsection

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
                    <h3 class="card-title">Update Profile</h3>
                </div>
                <div class="card-body">
                    <form id="profile-reset-form" action="{{ route('student.profile.reset') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="old_username">Old Username</label>
                            <input type="text" class="form-control" id="old_username" name="old_username"
                                value="{{ old('old_username', auth()->user()->student_login_name ?? '') }}">
                        </div>

                        <div class="form-group">
                            <label for="new_username">New Username</label>
                            <input type="text" class="form-control" id="new_username" name="new_username"
                                value="{{ old('new_username') }}"
                                placeholder="Enter new username">
                        </div>

                        <div class="form-group">
                            <label for="new_email">New Email (Optional)</label>
                            <input type="email" class="form-control" id="new_email" name="new_email"
                                value="{{ old('new_email', $student->primary_email ?? '') }}"
                                placeholder="Enter new email">
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
                            <input type="password" class="form-control" id="confirm_password" name="new_password_confirmation"
                                placeholder="Confirm new password">
                        </div>

                        <button type="submit" class="btn btn-info">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        const alertDefaults = {
            background: '#ffffff',
            color: '#2f3743',
            confirmButtonText: 'OK',
            draggable: true,
            customClass: {
                popup: 'profile-swal-popup',
                title: 'profile-swal-title',
                htmlContainer: 'profile-swal-text',
                confirmButton: 'profile-swal-btn'
            },
            buttonsStyling: false
        };

        const profileResetForm = document.getElementById('profile-reset-form');

        if (profileResetForm) {
            profileResetForm.addEventListener('submit', async function (event) {
                event.preventDefault();

                const submitBtn = profileResetForm.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                }

                try {
                    const response = await fetch(profileResetForm.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: new FormData(profileResetForm)
                    });

                    let result = {};
                    try {
                        result = await response.json();
                    } catch (jsonError) {
                        result = {};
                    }

                    if (!response.ok) {
                        const errors = result.errors || {};
                        const messages = Object.values(errors).flat();

                        await Swal.fire({
                            ...alertDefaults,
                            icon: 'error',
                            title: result.message || 'Validation Error',
                            html: (messages.length ? messages : ['Something went wrong.']).join('<br>'),
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    await Swal.fire({
                        ...alertDefaults,
                        icon: 'success',
                        title: 'Good job!',
                        text: result.message || 'Updated successfully.',
                        confirmButtonText: 'OK'
                    });

                    window.location.reload();
                } catch (error) {
                    Swal.fire({
                        ...alertDefaults,
                        icon: 'error',
                        title: 'Request Failed',
                        text: 'Unable to submit form. Please try again.',
                        confirmButtonText: 'OK'
                    });
                } finally {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                    }
                }
            });
        }
    </script>
@endsection
