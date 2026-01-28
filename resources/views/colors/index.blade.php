@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h2>Color List</h2>
    <a href="{{ route('colors.create') }}" class="btn btn-primary">Add Color</a>
</div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>NO</th>
            <th>Color Name</th>
            <th width="150">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($colors as $key => $color)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $color->color_name }}</td>
            <td>
                <a href="{{ route('colors.edit',$color->id) }}" class="btn btn-warning btn-sm">Edit</a>

                <form action="{{ route('colors.destroy',$color->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm"
                        onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
