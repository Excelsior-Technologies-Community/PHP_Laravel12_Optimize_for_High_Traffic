@extends('layouts.admin')

@section('content')

<h2 class="mb-4">Add Brand</h2>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
        @error('name')<div class="text-danger">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Slug <span class="text-danger">*</span></label>
        <input type="text" name="slug" value="{{ old('slug') }}" class="form-control" required>
        @error('slug')<div class="text-danger">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Image — Upload File</label>
        <input type="file" name="image_file" class="form-control" accept="image/*">
        <small class="text-muted">OR paste an online image URL below</small>
        <input type="text" name="image_url" value="{{ old('image_url') }}" class="form-control mt-2"
               placeholder="https://example.com/brand-logo.png">
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control">{{ old('description') }}</textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
            <option value="active"   {{ old('status') == 'active'   ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>

    <button class="btn btn-success">Save Brand</button>
    <a href="{{ route('brands.index') }}" class="btn btn-secondary">Back</a>
</form>

@endsection
