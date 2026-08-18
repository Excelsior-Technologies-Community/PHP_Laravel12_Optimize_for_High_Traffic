@extends('layouts.admin')

@section('content')

<h2 class="mb-3">Discount List</h2>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<a href="{{ route('discounts.create') }}" class="btn btn-primary mb-3">Add Discount</a>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Type</th>
            <th>Value</th>
            <th>Apply To</th>
            <th>Code</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th width="150">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($discounts as $discount)
        <tr>
            <td>{{ $discount->id }}</td>
            <td>{{ $discount->title }}</td>
            <td>{{ ucfirst($discount->apply_on) }}</td>
            <td>{{ $discount->value }}</td>
            <td>{{ ucfirst(str_replace('_', ' ', $discount->apply_to)) }}</td>
            <td>{{ $discount->discount_code ?? '-' }}</td>
            <td>{{ $discount->start_date ?? '-' }}</td>
            <td>{{ $discount->end_date ?? '-' }}</td>
            <td>
                <a href="{{ route('discounts.edit', $discount->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('discounts.destroy', $discount->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="9" class="text-center text-muted">No discounts found</td>
        </tr>
        @endforelse
    </tbody>
</table>

@endsection
