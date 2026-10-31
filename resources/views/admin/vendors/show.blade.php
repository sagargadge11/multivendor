@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4"><div><h3>{{ $vendor->business_name }}</h3><p class="text-muted">Vendor details and account management</p></div><a href="{{ route('admin.vendors.index') }}" class="btn btn-outline-secondary">Back to Vendors</a></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>@endif
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4"><div class="card-body p-4">
                <h5>Business Information</h5>
                @if($vendor->banner)<img src="{{ asset('storage/'.$vendor->banner) }}" class="w-100 rounded my-3" style="height:180px;object-fit:cover" alt="Banner">@endif
                <div class="d-flex gap-3 align-items-center my-3">@if($vendor->logo)<img src="{{ asset('storage/'.$vendor->logo) }}" style="width:80px;height:80px;object-fit:contain" class="border rounded p-1" alt="Logo">@endif<div><h5>{{ $vendor->business_name }}</h5>{{ $vendor->business_type }}</div></div>
                <div class="row g-3">
                    <div class="col-md-6"><small class="text-muted">Business Email</small><div>{{ $vendor->business_email }}</div></div>
                    <div class="col-md-6"><small class="text-muted">Phone</small><div>{{ $vendor->phone }}</div></div>
                    <div class="col-md-6"><small class="text-muted">GSTIN</small><div>{{ $vendor->gst_number ?: 'Not provided' }}</div></div>
                    <div class="col-md-6"><small class="text-muted">Website</small><div>@if($vendor->website_url)<a href="{{ $vendor->website_url }}" target="_blank" rel="noopener noreferrer">{{ $vendor->website_url }}</a>@else Not provided @endif</div></div>
                    <div class="col-md-6"><small class="text-muted">Listing Type</small><div>{{ ucfirst($vendor->listing_type) }}</div></div>
                    <div class="col-md-6"><small class="text-muted">Location</small><div>{{ $vendor->city }}{{ $vendor->state?', '.$vendor->state:'' }}, {{ $vendor->country }}</div></div>
                    <div class="col-12"><small class="text-muted">Address</small><div>{{ $vendor->address ?: 'Not provided' }}</div></div>
                    <div class="col-12"><small class="text-muted">Description</small><div>{{ $vendor->description ?: 'Not provided' }}</div></div>
                </div>
            </div></div>
            <div class="card border-0 shadow-sm mb-4"><div class="card-body p-4"><h5>Registered User</h5><div>Name: {{ $vendor->user?->name }}</div><div>Email: {{ $vendor->user?->email }}</div><div>Registered: {{ $vendor->created_at?->format('d M Y, h:i A') }}</div></div></div>
            <div class="card border-0 shadow-sm"><div class="card-body p-4"><h5>Recent Products</h5>
                @forelse($vendor->products as $product)<div class="border-bottom py-2">{{ $product->name }} <span class="text-muted small">— {{ $product->approval_status }} / {{ $product->publication_status }}</span></div>@empty<p class="text-muted mb-0">No products submitted yet.</p>@endforelse
            </div></div>
        </div>
        <div class="col-lg-4"><div class="card border-0 shadow-sm"><div class="card-body p-4">
            <h5>Vendor Status</h5>
            <p>Verification: <strong>{{ ucfirst($vendor->verification_status) }}</strong></p>
            <p>Account: <strong>{{ ucfirst($vendor->status) }}</strong></p>
            @if($vendor->rejection_reason)<div class="alert alert-danger small">Rejection: {{ $vendor->rejection_reason }}</div>@endif
            @if($vendor->suspension_reason)<div class="alert alert-warning small">Suspension: {{ $vendor->suspension_reason }}</div>@endif
            <hr>
            @if($vendor->verification_status!=='verified')
                <form method="POST" action="{{ route('admin.vendors.approve',$vendor) }}" class="mb-3" onsubmit="return confirm('Verify this vendor?')">@csrf<button class="btn btn-success w-100" @disabled($vendor->status==='suspended')>Approve / Verify Vendor</button></form>
            @endif
            @if($vendor->verification_status!=='rejected')
                <form method="POST" action="{{ route('admin.vendors.reject',$vendor) }}" class="mb-3">@csrf<label class="form-label">Rejection Reason</label><textarea name="reason" class="form-control mb-2" rows="3" required></textarea><button class="btn btn-outline-danger w-100" onclick="return confirm('Reject this vendor?')">Reject Vendor</button></form>
            @endif
            <hr>
            @if($vendor->status==='active')
                <form method="POST" action="{{ route('admin.vendors.suspend',$vendor) }}">@csrf<label class="form-label">Suspension Reason</label><textarea name="reason" class="form-control mb-2" rows="3" required></textarea><button class="btn btn-danger w-100" onclick="return confirm('Suspend this vendor?')">Suspend Vendor</button></form>
            @else
                <form method="POST" action="{{ route('admin.vendors.reactivate',$vendor) }}" onsubmit="return confirm('Reactivate this vendor?')">@csrf<button class="btn btn-primary w-100">Reactivate Vendor</button></form>
            @endif
        </div></div></div>
    </div>
</div>
@endsection
