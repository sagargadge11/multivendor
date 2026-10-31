@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <div class="mb-4"><h3 class="mb-1">Vendor Management</h3><p class="text-muted mb-0">Review, verify and manage marketplace vendors.</p></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
    <div class="row g-3 mb-4">
        @foreach(['Total Vendors'=>'total','Pending'=>'pending','Verified'=>'verified','Rejected'=>'rejected','Suspended'=>'suspended'] as $label=>$key)
            <div class="col-6 col-md"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">{{ $label }}</div><h3 class="mb-0 mt-2">{{ $counts[$key] }}</h3></div></div></div>
        @endforeach
    </div>
    <div class="card border-0 shadow-sm"><div class="card-body">
        <form method="GET" action="{{ route('admin.vendors.index') }}" class="row g-2 mb-4">
            <div class="col-lg-4"><input name="search" class="form-control" placeholder="Search business, email or phone" value="{{ request('search') }}"></div>
            <div class="col-lg-2"><select name="verification_status" class="form-select"><option value="">All Verification</option>@foreach(['pending','verified','rejected'] as $s)<option value="{{ $s }}" @selected(request('verification_status')===$s)>{{ ucfirst($s) }}</option>@endforeach</select></div>
            <div class="col-lg-2"><select name="status" class="form-select"><option value="">All Accounts</option>@foreach(['active','suspended'] as $s)<option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst($s) }}</option>@endforeach</select></div>
            <div class="col-lg-2"><select name="listing_type" class="form-select"><option value="">All Listing Types</option>@foreach(['manual','website'] as $s)<option value="{{ $s }}" @selected(request('listing_type')===$s)>{{ ucfirst($s) }}</option>@endforeach</select></div>
            <div class="col-lg-2 d-flex gap-2"><button class="btn btn-primary flex-fill">Filter</button><a href="{{ route('admin.vendors.index') }}" class="btn btn-outline-secondary">Reset</a></div>
        </form>
        <div class="table-responsive"><table class="table table-hover align-middle">
            <thead class="table-light"><tr><th>Vendor</th><th>Contact</th><th>Type</th><th>Verification</th><th>Account</th><th>Registered</th><th></th></tr></thead>
            <tbody>
            @forelse($vendors as $vendor)
                <tr><td><strong>{{ $vendor->business_name }}</strong><div class="small text-muted">{{ $vendor->user?->name }}</div></td>
                <td>{{ $vendor->business_email }}<div class="small text-muted">{{ $vendor->phone }}</div></td>
                <td>{{ ucfirst($vendor->listing_type) }}</td>
                <td><span class="badge bg-{{ $vendor->verification_status==='verified'?'success':($vendor->verification_status==='rejected'?'danger':'warning text-dark') }}">{{ ucfirst($vendor->verification_status) }}</span></td>
                <td><span class="badge bg-{{ $vendor->status==='active'?'success':'danger' }}">{{ ucfirst($vendor->status) }}</span></td>
                <td>{{ $vendor->created_at?->format('d M Y') }}</td>
                <td><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.vendors.show',$vendor) }}">View</a></td></tr>
            @empty<tr><td colspan="7" class="text-center py-4 text-muted">No vendors found.</td></tr>@endforelse
            </tbody>
        </table></div>
        {{ $vendors->links() }}
    </div></div>
</div>
@endsection
