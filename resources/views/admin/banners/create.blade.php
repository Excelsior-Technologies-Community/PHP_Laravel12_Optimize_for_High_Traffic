@extends('layouts.admin')

@section('content')

<h2 class="mb-4">Add Banner</h2>

@if($errors->any())
<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" name="title" value="{{ old('title') }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Image — Upload File</label>
        <input type="file" name="image_file" class="form-control" accept="image/*">
        <small class="text-muted">OR paste online image URL below</small>
    </div>

    <div class="mb-3">
        <label class="form-label">Image URL (online)</label>
        <input type="text" name="image" value="{{ old('image') }}" class="form-control"
               placeholder="https://example.com/banner.jpg">
        <small class="text-muted">If file uploaded above, URL will be ignored</small>
    </div>

    <div class="mb-3">
        <label class="form-label">Link (optional)</label>
        <input type="url" name="link" value="{{ old('link') }}" class="form-control"
               placeholder="https://yoursite.com/products">
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="active"   {{ old('status')=='active'   ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status')=='inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Sort Order</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="form-control" min="0">
        </div>
    </div>

    <button class="btn btn-success">Save Banner</button>
    <a href="{{ route('banners.index') }}" class="btn btn-secondary">Back</a>
</form>

@endsection
