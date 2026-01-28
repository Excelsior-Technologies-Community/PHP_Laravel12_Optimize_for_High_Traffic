@extends('layouts.admin')

@section('content')
<style>
    body {
        background: linear-gradient(120deg,rgba(229, 229, 229, 0.37) 0%,rgba(225, 225, 225, 0.24) 100%);
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
    }

 

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 30px;
    }

    .header h2 {
        font-size: 30px;
        font-weight: bold;
        color: #2c3e50;
        margin: 0;
    }


    

    
    .table {
        width: 100%;
        border-collapse: collapse;
        overflow: hidden;
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }

    .table thead {
        background: linear-gradient(135deg, #0984e3, #6c5ce7);
        color: white;
    }

    .table th,
    .table td {
        padding: 14px 18px;
        text-align: center;
        font-size: 14px;
        vertical-align: middle;
        white-space: nowrap;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f9f9fb;
    }

  

    .badge {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        display: inline-block;
    }

    .badge-warning {
        background:rgb(255, 206, 108);
        color: black;
    }

    .badge-success {
        background:rgb(0, 129, 103);
        color: white;
    }

    .badge-secondary {
        background: #b2bec3;
        color: white;
    }

    /*  Flex wrap for action buttons */
    .action-column {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
    }

    @media (max-width: 768px) {
        .action-column {
            flex-direction: column;
            align-items: center;
        }

        .btn {
            width: 100%;
            text-align: center;
        }
    }
</style> 

<div class="container-box">
    <div class="header">
        <h2> Discounts</h2>
        <div class="action-buttons">
            <a href="{{ route('products.index') }}" class="btn btn-secondary"> Back</a>
            <a href="{{ route('discounts.create') }}" class="btn btn-primary"> Add Discount</a>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Discount Code</th>
                <th>Apply On</th>
                <th>Value</th>
                <th>Apply To</th>
                <th>Product(s)</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($discounts as $discount)
            <tr>
                <td>{{ $discount->id }}</td>
                <td>{{ $discount->title }}</td>
                <td>
                    @if($discount->discount_code)
                        <span class="badge badge-warning">{{ $discount->discount_code }}</span>
                    @else
                        <span class="badge badge-secondary">—</span>
                    @endif
                </td>
                <td>{{ ucfirst($discount->apply_on) }}</td>
                <td>
                    @if($discount->apply_on === 'percentage')
                        {{ $discount->value }}%
                    @else
                        ₹{{ number_format($discount->value, 2) }}
                    @endif
                </td>
                <td>
                    @if($discount->apply_to === 'all_products')
                        <span class="badge badge-success">All Products</span>
                    @else
                        <span class="badge badge-warning">Specific Product</span>
                    @endif
                </td>
                <td>
                    @if($discount->apply_to === 'specific_product' && $discount->product_ids)
                        @php
                            $productIds = is_string($discount->product_ids)
                                ? json_decode($discount->product_ids, true)
                                : $discount->product_ids;

                            $products = \App\Models\Product::whereIn('id', $productIds)->pluck('name')->toArray();
                        @endphp
                        {{ implode(', ', $products) }}
                    @else
                        —
                    @endif
                </td>
                <td>{{ $discount->start_date ? \Carbon\Carbon::parse($discount->start_date)->format('d-m-Y') : '—' }}</td>
                <td>{{ $discount->end_date ? \Carbon\Carbon::parse($discount->end_date)->format('d-m-Y') : '—' }}</td>
                <td>
                    <div class="action-column">
                        <!-- <a href="{{ route('discounts.show', $discount->id) }}" class="btn btn-secondary"> View</a> -->
                        <a href="{{ route('discounts.edit', $discount->id) }}" class="btn btn-warning"> Edit</a>
                        <form action="{{ route('discounts.destroy', $discount->id) }}" method="POST" style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')"> Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10">No discounts found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
