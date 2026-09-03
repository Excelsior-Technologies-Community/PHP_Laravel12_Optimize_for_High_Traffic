@extends('layouts.admin')

@section('content')

<div class="container-fluid">


    {{-- ============================================================
     HEADER
============================================================= --}}

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">

        <div>
            <h2 class="mb-1">
                Size Guide List
            </h2>

            <p class="text-muted mb-0">
                Manage product size guides
            </p>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('size-guides.create') }}"
                class="btn btn-primary">
                + Add Size Guide
            </a>

            <a
                href="{{ route('size-guides.export', request()->query()) }}"
                class="btn btn-success">
                📥 Export CSV
            </a>

        </div>

    </div>


    {{-- ============================================================
     SUCCESS MESSAGE
============================================================= --}}

    @if(session('success'))

    <div class="alert alert-success alert-dismissible fade show">

        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

    @endif


    {{-- ============================================================
     ERROR MESSAGE
============================================================= --}}

    @if(session('error'))

    <div class="alert alert-danger alert-dismissible fade show">

        {{ session('error') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

    @endif


    {{-- ============================================================
     SEARCH + FILTERS
============================================================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('size-guides.index') }}">

                <div class="row g-3">

                    {{-- SEARCH --}}

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            class="form-control"
                            placeholder="Search product, size or description...">

                    </div>


                    {{-- PRODUCT FILTER --}}

                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Product
                        </label>

                        <select
                            name="product_id"
                            class="form-select">

                            <option value="">
                                All Products
                            </option>

                            @foreach($products as $product)

                            <option
                                value="{{ $product->id }}"
                                {{ (string) $productId === (string) $product->id ? 'selected' : '' }}>

                                {{ $product->name }}

                            </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- SIZE FILTER --}}

                    <div class="col-md-2">

                        <label class="form-label fw-semibold">
                            Size
                        </label>

                        <select
                            name="size_id"
                            class="form-select">

                            <option value="">
                                All Sizes
                            </option>

                            @foreach($sizes as $size)

                            <option
                                value="{{ $size->id }}"
                                {{ (string) $sizeId === (string) $size->id ? 'selected' : '' }}>

                                {{ $size->size_name }}

                            </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- SORT --}}

                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Sort By
                        </label>

                        <select
                            name="sort"
                            class="form-select">

                            <option
                                value="newest"
                                {{ $sort === 'newest' ? 'selected' : '' }}>
                                Newest
                            </option>

                            <option
                                value="oldest"
                                {{ $sort === 'oldest' ? 'selected' : '' }}>
                                Oldest
                            </option>

                            <option
                                value="id_asc"
                                {{ $sort === 'id_asc' ? 'selected' : '' }}>
                                ID Low-High
                            </option>

                            <option
                                value="id_desc"
                                {{ $sort === 'id_desc' ? 'selected' : '' }}>
                                ID High-Low
                            </option>

                            <option
                                value="product_asc"
                                {{ $sort === 'product_asc' ? 'selected' : '' }}>
                                Product A-Z
                            </option>

                            <option
                                value="product_desc"
                                {{ $sort === 'product_desc' ? 'selected' : '' }}>
                                Product Z-A
                            </option>

                            <option
                                value="size_asc"
                                {{ $sort === 'size_asc' ? 'selected' : '' }}>
                                Size A-Z
                            </option>

                            <option
                                value="size_desc"
                                {{ $sort === 'size_desc' ? 'selected' : '' }}>
                                Size Z-A
                            </option>

                        </select>

                    </div>


                    {{-- BUTTONS --}}

                    <div class="col-12">

                        <button
                            type="submit"
                            class="btn btn-primary">
                            🔎 Search / Filter
                        </button>

                        <a
                            href="{{ route('size-guides.index') }}"
                            class="btn btn-outline-secondary">
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ============================================================
     BULK DELETE FORM
============================================================= --}}

    <form
        method="POST"
        action="{{ route('size-guides.bulk-delete') }}"
        id="bulkDeleteForm">

        @csrf


        {{-- ========================================================
         BULK ACTION BAR
    ========================================================= --}}

        <div
            class="card shadow-sm mb-3"
            id="bulkActionBar"
            style="display: none;">

            <div class="card-body d-flex justify-content-between align-items-center">

                <div>

                    <strong id="selectedCount">
                        0
                    </strong>

                    size guide(s) selected.

                </div>

                <button
                    type="button"
                    class="btn btn-danger"
                    onclick="confirmBulkDelete()">

                    🗑 Bulk Delete

                </button>

            </div>

        </div>


        {{-- ========================================================
         TABLE
    ========================================================= --}}

        <div class="card shadow-sm">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table
                        class="table table-bordered table-striped align-middle mb-0">

                        <thead class="table-dark">

                            <tr>

                                {{-- SELECT ALL --}}

                                <th width="50">

                                    <input
                                        type="checkbox"
                                        id="selectAll"
                                        class="form-check-input">

                                </th>


                                <th>
                                    ID
                                </th>


                                <th>
                                    Product
                                </th>


                                <th>
                                    Size
                                </th>


                                <th>
                                    Measurements
                                </th>


                                <th>
                                    Description
                                </th>


                                <th>
                                    Created
                                </th>


                                <th width="250">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($sizeGuides as $guide)

                            <tr>

                                {{-- CHECKBOX --}}

                                <td>

                                    <input
                                        type="checkbox"
                                        name="ids[]"
                                        value="{{ $guide->id }}"
                                        class="form-check-input guide-checkbox">

                                </td>


                                {{-- ID --}}
                                <td>
                                    {{ $sizeGuides->firstItem() + $loop->index }}
                                </td>


                                {{-- PRODUCT --}}

                                <td>

                                    <strong>
                                        {{ $guide->product->name ?? 'N/A' }}
                                    </strong>

                                </td>


                                {{-- SIZE --}}

                                <td>

                                    <span class="badge bg-primary">

                                        {{ $guide->size->size_name ?? 'N/A' }}

                                    </span>

                                </td>


                                {{-- MEASUREMENTS --}}

                                <td>

                                    @if(is_array($guide->measurements))

                                    @foreach($guide->measurements as $key => $value)

                                    <span class="badge bg-secondary me-1 mb-1">

                                        {{ $key }}: {{ $value }}

                                    </span>

                                    @endforeach

                                    @elseif($guide->measurements)

                                    {{ $guide->measurements }}

                                    @else

                                    <span class="text-muted">
                                        N/A
                                    </span>

                                    @endif

                                </td>


                                {{-- DESCRIPTION --}}

                                <td>

                                    {{ Str::limit(
                                        $guide->description ?? '',
                                        60
                                    ) }}

                                </td>


                                {{-- CREATED --}}

                                <td>

                                    {{ optional($guide->created_at)->format('d M Y') }}

                                </td>


                                {{-- ACTIONS --}}

                                <td>

                                    <div class="d-flex gap-1 flex-wrap">

                                        {{-- EDIT --}}

                                        <a
                                            href="{{ route(
                                                'size-guides.edit',
                                                $guide->id
                                            ) }}"
                                            class="btn btn-warning btn-sm">

                                            Edit

                                        </a>


                                        {{-- DUPLICATE --}}

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'size-guides.duplicate',
                                                $guide->id
                                            ) }}"
                                            class="d-inline">

                                            @csrf

                                            <button
                                                type="submit"
                                                class="btn btn-info btn-sm">

                                                Duplicate

                                            </button>

                                        </form>


                                        {{-- DELETE --}}

                                        <form
                                            action="{{ route(
                                                'size-guides.destroy',
                                                $guide->id
                                            ) }}"
                                            method="POST"
                                            class="d-inline">

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this size guide?')">

                                                Delete

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center text-muted py-5">

                                    No size guides found.

                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </form>


    {{-- ============================================================
     PAGINATION
============================================================= --}}

    @if($sizeGuides->hasPages())

    <div class="d-flex justify-content-center mt-4">

        {{ $sizeGuides->links('pagination::bootstrap-5') }}

    </div>

    @endif


</div>

{{-- ================================================================
JAVASCRIPT
================================================================ --}}

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const selectAll = document.getElementById('selectAll');

        const checkboxes = document.querySelectorAll('.guide-checkbox');

        const bulkActionBar = document.getElementById('bulkActionBar');

        const selectedCount = document.getElementById('selectedCount');


        function updateBulkActionBar() {

            const checked = document.querySelectorAll(
                '.guide-checkbox:checked'
            );

            const count = checked.length;


            selectedCount.textContent = count;


            if (count > 0) {

                bulkActionBar.style.display = 'block';

            } else {

                bulkActionBar.style.display = 'none';

            }


            if (checkboxes.length > 0) {

                selectAll.checked =
                    count === checkboxes.length;

                selectAll.indeterminate =
                    count > 0 &&
                    count < checkboxes.length;

            }

        }


        selectAll.addEventListener('change', function() {

            checkboxes.forEach(function(checkbox) {

                checkbox.checked = selectAll.checked;

            });

            updateBulkActionBar();

        });


        checkboxes.forEach(function(checkbox) {

            checkbox.addEventListener(
                'change',
                updateBulkActionBar
            );

        });

    });


    function confirmBulkDelete() {

        const selected = document.querySelectorAll(
            '.guide-checkbox:checked'
        );


        if (selected.length === 0) {

            alert(
                'Please select at least one size guide.'
            );

            return;

        }


        const confirmed = confirm(
            'Are you sure you want to delete ' +
            selected.length +
            ' size guide(s)?'
        );


        if (confirmed) {

            document.getElementById(
                'bulkDeleteForm'
            ).submit();

        }

    }
</script>

@endsection