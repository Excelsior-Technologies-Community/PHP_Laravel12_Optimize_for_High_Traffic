@extends('layouts.admin')

@section('content')

<h2 class="mb-4">Add Size</h2>

<form action="{{ route('sizes.store') }}" method="POST">
@csrf

<div class="mb-3">
    <label class="form-label">Size Name</label>
    <input type="text" name="size_name" class="form-control">
</div>

<button class="btn btn-success">Save</button>
<a href="{{ route('sizes.index') }}" class="btn btn-secondary">Back</a>

</form>

@endsection
