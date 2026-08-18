@extends('layouts.customer')

@section('content')

<h3 class="mb-4 fw-bold">❤️ My Wishlist</h3>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

@forelse($wishlistItems as $item)
@php $p = $item->product; @endphp
<div class="card shadow-sm mb-3">
    <div class="card-body">
        <div class="row align-items-start g-3">

            {{-- Image --}}
            <div class="col-md-2 text-center">
                @if($p->image)
                    @if(filter_var($p->image, FILTER_VALIDATE_URL))
                        <img src="{{ $p->image }}" class="img-fluid rounded" style="height:100px; object-fit:contain; background:#f8f9fa;">
                    @else
                        <img src="{{ asset('images/'.$p->image) }}" class="img-fluid rounded" style="height:100px; object-fit:contain; background:#f8f9fa;">
                    @endif
                @else
                    <img src="https://placehold.co/200x200?text=No+Image" class="img-fluid rounded" style="height:100px;">
                @endif
                {{-- Brand --}}
                @if($p->brand)
                <span class="badge bg-dark mt-1">{{ $p->brand->name }}</span>
                @endif
            </div>

            {{-- Details --}}
            <div class="col-md-3">
                <h5 class="mb-1">{{ $p->name }}</h5>
                <p class="text-muted small mb-2">{{ \Illuminate\Support\Str::limit($p->details, 80) }}</p>
                <p class="fw-bold text-primary mb-1">₹ {{ $p->price }}</p>
                <div class="d-flex flex-wrap gap-1 mb-1">
                    @foreach($p->sizes ?? [] as $sId)
                        <span class="badge bg-secondary">{{ $sizes[$sId] ?? '' }}</span>
                    @endforeach
                </div>
                <div class="d-flex flex-wrap gap-1">
                    @foreach($p->colors ?? [] as $cId)
                        <span class="badge bg-info text-dark">{{ $colors[$cId] ?? '' }}</span>
                    @endforeach
                </div>
            </div>

            {{-- Add to Cart Form --}}
            <div class="col-md-5">
                <form action="{{ route('cart.add') }}" method="POST" class="row g-2">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $p->id }}">

                    <div class="col-6">
                        <select name="size_id" class="form-select form-select-sm" required>
                            <option value="" disabled selected>Size</option>
                            @foreach($p->sizes ?? [] as $sId)
                                <option value="{{ $sId }}">{{ $sizes[$sId] ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6">
                        <select name="color_id" class="form-select form-select-sm" required>
                            <option value="" disabled selected>Color</option>
                            @foreach($p->colors ?? [] as $cId)
                                <option value="{{ $cId }}">{{ $colors[$cId] ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6">
                        <select name="category_id" class="form-select form-select-sm" required>
                            <option value="" disabled selected>Category</option>
                            @foreach($p->categories ?? [] as $catId)
                                <option value="{{ $catId }}">{{ $categories[$catId] ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-3">
                        <select name="quantity" class="form-select form-select-sm">
                            @for($i=1;$i<=5;$i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-3">
                        <button class="btn btn-primary btn-sm w-100" title="Add to Cart">
                            🛒 Add
                        </button>
                    </div>
                </form>
            </div>

            {{-- Remove --}}
            <div class="col-md-2 text-end">
                <form action="{{ route('wishlist.remove', $item->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm">❌ Remove</button>
                </form>
            </div>

        </div>
    </div>
</div>
@empty
<div class="alert alert-info text-center">
    Your wishlist is empty. <a href="{{ route('customer.products') }}">Browse products</a> to add items.
</div>
@endforelse

@endsection
