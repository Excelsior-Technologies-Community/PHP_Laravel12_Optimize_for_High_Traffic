@extends('layouts.admin')

@section('content')

<h2 class="mb-3">Size Guide List</h2>

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

<a href="{{ route('size-guides.create') }}" class="btn btn-primary mb-3">Add Size Guide</a>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Size</th>
            <th>Measurements</th>
            <th>Description</th>
            <th width="150">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($sizeGuides as $guide)
        <tr>
            <td>{{ $guide->id }}</td>
            <td>{{ $guide->product->name ?? 'N/A' }}</td>
            <td>{{ $guide->size->size_name ?? 'N/A' }}</td>
            <td>
                @if(is_array($guide->measurements))
                    @foreach($guide->measurements as $key => $value)
                        <span class="badge bg-secondary me-1">{{ $key }}: {{ $value }}</span>
                    @endforeach
                @else
                    {{ $guide->measurements }}
                @endif
            </td>
            <td>{{ Str::limit($guide->description, 50) }}</td>
            <td>
                <a href="{{ route('size-guides.edit', $guide->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('size-guides.destroy', $guide->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center text-muted">No size guides found</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-center mt-3">
    {{ $sizeGuides->links('pagination::bootstrap-5') }}
</div>

@endsection
