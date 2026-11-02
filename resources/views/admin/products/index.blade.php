@extends('layouts.admin')
@section('title', 'Product Review')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4"><div><h3 class="mb-1">Product Review</h3><p class="text-muted mb-0">Review vendor products and assign a global category before publishing.</p></div></div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<form class="row g-2 mb-3" method="GET">
<div class="col-md-5"><input name="search" class="form-control" placeholder="Search product, SKU or vendor" value="{{ request('search') }}"></div>
<div class="col-md-3"><select name="approval_status" class="form-select"><option value="pending" @selected(request('approval_status','pending')==='pending')>Pending review</option><option value="approved" @selected(request('approval_status')==='approved')>Approved</option><option value="rejected" @selected(request('approval_status')==='rejected')>Rejected</option></select></div>
<div class="col-auto"><button class="btn btn-primary">Filter</button> <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Reset</a></div>
</form>
<div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table align-middle mb-0">
<thead class="table-light"><tr><th>Product</th><th>Vendor</th><th>Vendor category / Global category</th><th>Status</th><th></th></tr></thead>
<tbody>
@forelse($products as $product)
<tr><td><strong>{{ $product->name }}</strong><br><small class="text-muted">SKU: {{ $product->sku ?: '—' }}</small></td>
<td>{{ $product->vendor?->business_name ?? 'Vendor unavailable' }}</td>
<td>{{ $product->category?->name ?? 'Not assigned' }}</td>
<td><span class="badge text-bg-{{ $product->approval_status==='approved'?'success':($product->approval_status==='rejected'?'danger':'warning') }}">{{ ucfirst($product->approval_status) }}</span></td>
<td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.products.show',$product) }}">Review</a></td></tr>
@empty<tr><td colspan="5" class="text-center text-muted py-4">No products found.</td></tr>@endforelse
</tbody></table></div><div class="card-body">{{ $products->links() }}</div></div>
@endsection
