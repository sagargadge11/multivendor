@extends('layouts.admin')
@section('title', 'Review Product')
@section('content')
<div class="mb-3"><a href="{{ route('admin.products.index') }}" class="text-decoration-none">&larr; Back to products</a></div>
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<div class="row g-4">
<div class="col-lg-7"><div class="card border-0 shadow-sm"><div class="card-body p-4">
<h3>{{ $product->name }}</h3><p class="text-muted mb-1">Vendor: {{ $product->vendor?->business_name }}</p>
<p class="text-muted">SKU: {{ $product->sku ?: '—' }} · Source: {{ ucfirst($product->source_type) }}</p>
@if($product->images->isNotEmpty())<div class="d-flex flex-wrap gap-2 mb-3">@foreach($product->images as $image)<img src="{{ $image->image_url ?: ($image->image_path ? asset('storage/'.$image->image_path) : '') }}" alt="" style="width:110px;height:110px;object-fit:contain" class="border rounded">@endforeach</div>@endif
<div class="mb-3">{!! nl2br(e($product->description)) !!}</div>
@if($product->product_url)<a href="{{ $product->product_url }}" target="_blank" rel="noopener noreferrer">Open vendor product page</a>@endif
</div></div></div>
<div class="col-lg-5"><div class="card border-0 shadow-sm mb-3"><div class="card-body p-4">
<h5>Approve & Assign Global Category</h5>
<form method="POST" action="{{ route('admin.products.approve',$product) }}">@csrf
<label class="form-label mt-2">Global Category *</label><select name="category_id" class="form-select" required><option value="">Select category</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected((string)old('category_id',$product->category_id)===(string)$category->id)>{{ $category->parent ? $category->parent->name.' / ' : '' }}{{ $category->name }}</option>@endforeach</select>
<label class="form-label mt-3">Admin remarks (optional)</label><textarea name="remarks" class="form-control" rows="3">{{ old('remarks') }}</textarea>
<button class="btn btn-success w-100 mt-3" @disabled($categories->isEmpty())>Approve & Publish Product</button>
@if($categories->isEmpty())<small class="text-danger d-block mt-2">Create an active global category before approving products.</small>@endif
</form></div></div>
<div class="card border-0 shadow-sm"><div class="card-body p-4"><h5>Reject Product</h5><form method="POST" action="{{ route('admin.products.reject',$product) }}">@csrf
<label class="form-label">Reason *</label><textarea name="remarks" class="form-control" rows="3" required>{{ old('remarks') }}</textarea>
<button class="btn btn-outline-danger w-100 mt-3">Reject Product</button></form></div></div></div></div>
@endsection
