@extends('layouts.admin')

@section('content')

<h2 class="mb-3">Banner List</h2>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<a href="{{ route('banners.create') }}" class="btn btn-primary mb-3">+ Add Banner</a>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Image</th>
            <th>Link</th>
            <th>Order</th>
            <th>Status</th>
            <th width="150">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($banners as $banner)
        <tr>
            <td>{{ $banner->id }}</td>
            <td>{{ $banner->title }}</td>
            <td>
                @php $src = filter_var($banner->image, FILTER_VALIDATE_URL) ? $banner->image : asset('images/banners/'.$banner->image); @endphp
                <img src="{{ $src }}" width="120" height="50" style="object-fit:cover; border-radius:6px;"
                     onerror="this.src='https://placehold.co/120x50?text=No+Image'">
            </td>
            <td>{{ $banner->link ? Str::limit($banner->link, 30) : '-' }}</td>
            <td>{{ $banner->sort_order }}</td>
            <td>
                <span class="badge bg-{{ $banner->status == 'active' ? 'success' : 'danger' }}">
                    {{ ucfirst($banner->status) }}
                </span>
            </td>
            <td>
                <a href="{{ route('banners.edit', $banner->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('banners.destroy', $banner->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center text-muted">No banners found</td></tr>
        @endforelse
    </tbody>
</table>

{{ $banners->links('pagination::bootstrap-5') }}

@endsection
