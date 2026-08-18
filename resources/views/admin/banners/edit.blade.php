@extends('layouts.admin')

@section('content')

<h2 class="mb-4">Edit Banner</h2>

@if($errors->any())
<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<form action="{{ route('banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" name="title" value="{{ old('title', $banner->title) }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Current Image</label><br>
        @if($banner->image)
            @if(filter_var($banner->image, FILTER_VALIDATE_URL))
                <img src="{{ $banner->image }}" height="80" class="rounded mb-2">
            @else
                <img src="{{ asset('images/banners/'.$banner->image) }}" height="80" class="rounded mb-2">
            @endif
        @endif
    </div>

    <div class="mb-3">
        <label class="form-label">Replace Image — Upload File</label>
        <input type="file" name="image_file" class="form-control" accept="image/*">
        <small class="text-muted">OR paste online image URL below</small>
    </div>

    <div class="mb-3">
        <label class="form-label">Image URL (online)</label>
        <input type="text" name="image" value="{{ old('image', $banner->image) }}" class="form-control"
               placeholder="https://example.com/banner.jpg">
        <small class="text-muted">If file uploaded above, URL will be ignored</small>
    </div>

    <div class="mb-3">
        <label class="form-label">Link (optional)</label>
        <input type="url" name="link" value="{{ old('link', $banner->link) }}" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="2">{{ old('description', $banner->description) }}</textarea>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="active"   {{ old('status', $banner->status) == 'active'   ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $banner->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Sort Order</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order) }}" class="form-control" min="0">
        </div>
    </div>

    <button class="btn btn-success">Update Banner</button>
    <a href="{{ route('banners.index') }}" class="btn btn-secondary">Back</a>
</form>

@endsection
