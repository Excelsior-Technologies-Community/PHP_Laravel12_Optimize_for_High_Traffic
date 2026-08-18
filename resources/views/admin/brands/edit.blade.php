@extends('layouts.admin')

@section('content')

<h2 class="mb-4">Edit Brand</h2>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" value="{{ old('name', $brand->name) }}" class="form-control" required>
        @error('name')<div class="text-danger">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Slug <span class="text-danger">*</span></label>
        <input type="text" name="slug" value="{{ old('slug', $brand->slug) }}" class="form-control" required>
        @error('slug')<div class="text-danger">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Current Image</label><br>
        @if($brand->image)
            @if(filter_var($brand->image, FILTER_VALIDATE_URL))
                <img src="{{ $brand->image }}" width="120" class="rounded mb-2"
                     onerror="this.src='https://placehold.co/120x80?text=No+Image'">
            @else
                <img src="{{ asset('images/brands/'.$brand->image) }}" width="120" class="rounded mb-2"
                     onerror="this.src='https://placehold.co/120x80?text=No+Image'">
            @endif
        @else
            <span class="text-muted d-block mb-2">No image</span>
        @endif

        <label class="form-label mt-2">Upload New Image File</label>
        <input type="file" name="image_file" class="form-control" accept="image/*">
        <small class="text-muted">OR paste an online image URL below (leave blank to keep current)</small>
        <input type="text" name="image_url"
               value="{{ old('image_url', filter_var($brand->image, FILTER_VALIDATE_URL) ? $brand->image : '') }}"
               class="form-control mt-2"
               placeholder="https://example.com/brand-logo.png">
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control">{{ old('description', $brand->description) }}</textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
            <option value="active"   {{ old('status', $brand->status) == 'active'   ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $brand->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>

    <button class="btn btn-primary">Update Brand</button>
    <a href="{{ route('brands.index') }}" class="btn btn-secondary">Back</a>
</form>

@endsection
