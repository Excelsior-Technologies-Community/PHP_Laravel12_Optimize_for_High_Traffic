@extends('layouts.customer')

@section('title', 'Customer Dashboard')

@section('content')

<div class="card">
    <div class="card-body">
        <h4>Welcome, {{ auth('customer')->user()->name }}</h4>
        <p>Email: {{ auth('customer')->user()->email }}</p>
    </div>
</div>

@endsection
