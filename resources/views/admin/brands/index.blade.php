@extends('layouts.admin')

@section('content')

<h2 class="mb-3">Brand List</h2>

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

<form method="GET" action="{{ route('brands.index') }}" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name">
    </div>
    <div class="col-md-3">
        <button class="btn btn-primary">Search</button>
        <a href="{{ route('brands.index') }}" class="btn btn-secondary">Reset</a>
    </div>
</form>

<a href="{{ route('brands.create') }}" class="btn btn-primary mb-3">Add Brand</a>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Slug</th>
            <th>Image</th>
            <th>Description</th>
            <th>Status</th>
            <th width="150">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($brands as $brand)
        <tr>
            <td>{{ $brand->id }}</td>
            <td>{{ $brand->name }}</td>
            <td>{{ $brand->slug }}</td>
            <td>
                @if($brand->image)
                    @if(filter_var($brand->image, FILTER_VALIDATE_URL))
                        <img src="{{ $brand->image }}" width="60" class="rounded">
                    @else
                        <img src="{{ asset('images/'.$brand->image) }}" width="60" class="rounded">
                    @endif
                @else
                <span class="text-muted">No image</span>
                @endif
            </td>
            <td>{{ Str::limit($brand->description, 50) }}</td>
            <td>
                @if($brand->status == 'active')
                <span class="badge bg-success">Active</span>
                @else
                <span class="badge bg-danger">Inactive</span>
                @endif
            </td>
            <td>
                <a href="{{ route('brands.edit', $brand->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center text-muted">No brands found</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-center mt-3">
    {{ $brands->links('pagination::bootstrap-5') }}
</div>

@endsection
