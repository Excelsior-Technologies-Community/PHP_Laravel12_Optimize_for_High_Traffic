@extends('layouts.admin')

@section('content')

<h2 class="mb-4">Edit Product Tag</h2>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('product-tags.update', $productTag->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" value="{{ old('name', $productTag->name) }}" class="form-control">
        @error('name')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Slug</label>
        <input type="text" name="slug" value="{{ old('slug', $productTag->slug) }}" class="form-control">
        @error('slug')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <button class="btn btn-primary">Update Tag</button>
    <a href="{{ route('product-tags.index') }}" class="btn btn-secondary">Back</a>
</form>

@endsection
