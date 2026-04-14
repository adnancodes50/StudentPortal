@extends('adminlte::page')

@section('title', 'Passengers')

@section('content_header')
    <h1>Passengers List ✈️</h1>
@stop

@section('content')

<div class="card">
    <div class="card-body">

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    {{-- <th>Phone</th> --}}
                    {{-- <th>City</th> --}}
                    {{-- <th>Country</th> --}}
                    <th>Status</th>
                    <th>Type</th>
                </tr>
            </thead>

            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        {{-- <td>{{ $user->phone }}</td> --}}
                        {{-- <td>{{ $user->city }}</td> --}}
                        {{-- <td>{{ $user->country }}</td> --}}
                        <td>{{ $user->status }}</td>
                        <td>
                            @if($user->type === 'admin')
                                <span class="badge bg-danger">Admin</span>
                            @else
                                <span class="badge bg-success">User</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

    </div>
</div>

@stop