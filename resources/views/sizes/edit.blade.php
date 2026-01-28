@extends('layouts.admin')

@section('content')

<h2 class="mb-4">Edit Size</h2>

<form action="{{ route('sizes.update',$size->id) }}" method="POST">
@csrf
@method('PUT')

<div class="mb-3">
    <label class="form-label">Size Name</label>
    <input type="text" name="size_name"
           value="{{ $size->size_name }}"
           class="form-control">
</div>

<button class="btn btn-primary">Update</button>
<a href="{{ route('sizes.index') }}" class="btn btn-secondary">Back</a>

</form>

@endsection
