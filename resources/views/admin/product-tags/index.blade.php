@extends('layouts.admin')

@section('content')

<h2 class="mb-3">Product Tag List</h2>

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

<form method="GET" action="{{ route('product-tags.index') }}" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name">
    </div>
    <div class="col-md-3">
        <button class="btn btn-primary">Search</button>
        <a href="{{ route('product-tags.index') }}" class="btn btn-secondary">Reset</a>
    </div>
</form>

<a href="{{ route('product-tags.create') }}" class="btn btn-primary mb-3">Add Product Tag</a>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Slug</th>
            <th width="150">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($tags as $tag)
        <tr>
            <td>{{ $tag->id }}</td>
            <td>{{ $tag->name }}</td>
            <td>{{ $tag->slug }}</td>
            <td>
                <a href="{{ route('product-tags.edit', $tag->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('product-tags.destroy', $tag->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="text-center text-muted">No tags found</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-center mt-3">
    {{ $tags->links('pagination::bootstrap-5') }}
</div>

@endsection
