@extends('adminlte::page')

@section('title', 'Agents')

@section('content_header')
    <h1>Agents</h1>
    <p class="text-muted mb-0">Agent management is merged into Users & Agents module.</p>
@stop

@section('content')
    <div class="card"><div class="card-body">
        <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Go to Users & Agents CRUD</a>
    </div></div>
@stop

