@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3 class="mb-1">Vendor Management</h3>
        <p class="text-muted mb-0">Review, verify and manage marketplace vendors.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row g-3 mb-4">
        @foreach([
            'Total Vendors' => $counts['total'],
            'Pending' => $counts['pending'],
            'Verified' => $counts['verified'],
            'Rejected' => $counts['rejected'],
            'Suspended' => $counts['suspended'],
        ] as $label => $count)
            <div class="col-6 col-md">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">{{ $label }}</div>
                        <h3 class="mb-0 mt-2">{{ $count }}</h3>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.vendors.index') }}">
                <div class="row g-2 mb-4">
                    <div class="col-lg-4">
                        <input type="text" name="search" class="form-control"
                               placeholder="Search business, email or phone" value="{{ request('search') }}">
                    </div>
                    <div class="col-lg-2">
                        <select name="verification_status" class="form-select">
                            <option value="">All Verification</option>
                            <option value="pending" @selected(request('verification_status') === 'pending')>Pending</option>
                            <option value="verified" @selected(request('verification_status') === 'verified')>Verified</option>
                            <option value="rejected" @selected(request('verification_status') === 'rejected')>Rejected</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <select name="status" class="form-select">
                            <option value="">All Accounts</option>
                            <option value="active" @selected(request('status') === 'active')>Active</option>
                            <option value="suspended" @selected(request('status') === 'suspended')>Suspended</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <select name="listing_type" class="form-select">
                            <option value="">All Listing Types</option>
                            <option value="manual" @selected(request('listing_type') === 'manual')>Manual</option>
                            <option value="website" @selected(request('listing_type') === 'website')>Website</option>
                        </select>
                    </div>
                    <div class="col-lg-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                        <a href="{{ route('admin.vendors.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Vendor</th><th>Contact</th><th>Listing Type</th>
                            <th>Verification</th><th>Account</th><th>Registered</th><th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($vendors as $vendor)
                        <tr>
                            <td>
                                <strong>{{ $vendor->business_name }}</strong>
                                <div class="small text-muted">{{ $vendor->user?->name }}</div>
                            </td>
                            <td>
                                <div>{{ $vendor->business_email }}</div>
                                <div class="small text-muted">{{ $vendor->phone }}</div>
                            </td>
                            <td>{{ ucfirst($vendor->listing_type) }}</td>
                            <td>
                                @if($vendor->verification_status === 'verified')
                                    <span class="badge bg-success">Verified</span>
                                @elseif($vendor->verification_status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if($vendor->status === 'suspended')
                                    <span class="badge bg-danger">Suspended</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </td>
                            <td>{{ $vendor->created_at?->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.vendors.show', $vendor) }}"
                                   class="btn btn-sm btn-outline-primary">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">No vendors found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $vendors->links() }}</div>
        </div>
    </div>
</div>
@endsection
