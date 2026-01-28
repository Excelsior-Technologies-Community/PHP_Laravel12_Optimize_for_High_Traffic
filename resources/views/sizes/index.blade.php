@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h2>Size List</h2>
    <a href="{{ route('sizes.create') }}" class="btn btn-primary">Add Size</a>
</div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Size Name</th>
            <th width="150">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sizes as $key => $size)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $size->size_name }}</td>
            <td>
                <a href="{{ route('sizes.edit',$size->id) }}" class="btn btn-warning btn-sm">Edit</a>

                <form action="{{ route('sizes.destroy',$size->id) }}"
                      method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
