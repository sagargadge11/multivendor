@extends('layouts.app')
@section('title','Marketplace')
@section('content')
<div class="container">
    <section class="hero p-4 p-md-5 mb-5">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold">Discover products from trusted businesses</h1>
                <p class="lead mb-0">Explore products and send enquiries directly to vendors.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <a href="{{ route('register') }}" class="btn btn-warning btn-lg">Join the Marketplace</a>
            </div>
        </div>
    </section>

    @if($categories->isNotEmpty())
        <section class="mb-5">
            <h2 class="h3 mb-3">Categories</h2>
            <div class="row g-3">
                @foreach($categories as $category)
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm h-100"><div class="card-body">
                            <h3 class="h6 mb-0">{{ $category->name }}</h3>
                        </div></div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <section>
        <h2 class="h3 mb-3">Latest Products</h2>
        <div class="row g-4">
            @forelse($products as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card product-card border-0 shadow-sm h-100">
                        @if($product->images->first())
                            <img src="{{ $product->images->first()->image_path ? asset('storage/'.$product->images->first()->image_path) : $product->images->first()->image_url }}"
                                 class="card-img-top" alt="{{ $product->name }}">
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light" style="height:190px">No image</div>
                        @endif
                        <div class="card-body">
                            <h3 class="h6">{{ $product->name }}</h3>
                            <p class="small text-muted mb-2">{{ $product->vendor->business_name }}</p>
                            <strong>{{ $product->price !== null ? $product->currency.' '.$product->price : 'Price on request' }}</strong>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12"><div class="alert alert-light border">Products will appear here after admin approval.</div></div>
            @endforelse
        </div>
    </section>
</div>
@endsection
