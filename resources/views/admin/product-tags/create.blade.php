@extends('layouts.admin')

@section('content')

<h2 class="mb-4">Add Product Tag</h2>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('product-tags.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" value="{{ old('name') }}" class="form-control">
        @error('name')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Slug</label>
        <input type="text" name="slug" value="{{ old('slug') }}" class="form-control">
        @error('slug')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <button class="btn btn-success">Save Tag</button>
    <a href="{{ route('product-tags.index') }}" class="btn btn-secondary">Back</a>
</form>

@endsection
