@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h3 class="mb-1">{{ $vendor->business_name }}</h3>
            <p class="text-muted mb-0">Vendor details and account management</p>
        </div>
        <a href="{{ route('admin.vendors.index') }}" class="btn btn-outline-secondary">Back to Vendors</a>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="mb-3">Business Information</h5>
                    @if($vendor->banner)
                        <img src="{{ asset('storage/' . $vendor->banner) }}" alt="Business Banner"
                             class="w-100 rounded mb-3" style="height:180px;object-fit:cover">
                    @endif
                    <div class="d-flex align-items-center gap-3 mb-4">
                        @if($vendor->logo)
                            <img src="{{ asset('storage/' . $vendor->logo) }}" alt="Business Logo"
                                 class="border rounded p-1" style="width:85px;height:85px;object-fit:contain">
                        @endif
                        <div>
                            <h5 class="mb-1">{{ $vendor->business_name }}</h5>
                            <div class="text-muted">{{ $vendor->business_type }}</div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6"><div class="text-muted small">Business Email</div>{{ $vendor->business_email }}</div>
                        <div class="col-md-6"><div class="text-muted small">Phone</div>{{ $vendor->phone }}</div>
                        <div class="col-md-6"><div class="text-muted small">GST Number</div>{{ $vendor->gst_number ?: 'Not provided' }}</div>
                        <div class="col-md-6">
                            <div class="text-muted small">Website</div>
                            @if($vendor->website_url)
                                <a href="{{ $vendor->website_url }}" target="_blank" rel="noopener noreferrer">{{ $vendor->website_url }}</a>
                            @else
                                Not provided
                            @endif
                        </div>
                        <div class="col-md-6"><div class="text-muted small">Listing Type</div>{{ ucfirst($vendor->listing_type) }}</div>
                        <div class="col-md-6"><div class="text-muted small">Country</div>{{ $vendor->country }}</div>
                        <div class="col-md-6"><div class="text-muted small">City / State</div>{{ $vendor->city }}{{ $vendor->state ? ', '.$vendor->state : '' }}</div>
                        <div class="col-md-6"><div class="text-muted small">Postal Code</div>{{ $vendor->postal_code ?: 'Not provided' }}</div>
                        <div class="col-12"><div class="text-muted small">Address</div>{{ $vendor->address ?: 'Not provided' }}</div>
                        <div class="col-12"><div class="text-muted small">Description</div>{{ $vendor->description ?: 'Not provided' }}</div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="mb-3">Registered User</h5>
                    <div><strong>Name:</strong> {{ $vendor->user?->name }}</div>
                    <div><strong>Email:</strong> {{ $vendor->user?->email }}</div>
                    <div><strong>Registration Date:</strong> {{ $vendor->created_at?->format('d M Y, h:i A') }}</div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="mb-3">Recent Products</h5>
                    @if($vendor->products->isEmpty())
                        <p class="text-muted mb-0">No products submitted yet.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead><tr><th>Product</th><th>Approval</th><th>Publication</th></tr></thead>
                                <tbody>
                                @foreach($vendor->products as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ ucfirst($product->approval_status) }}</td>
                                        <td>{{ ucfirst($product->publication_status) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="mb-3">Vendor Status</h5>
                    <p class="mb-2">
                        <strong>Verification:</strong>
                        @if($vendor->verification_status === 'verified')
                            <span class="badge bg-success">Verified</span>
                        @elseif($vendor->verification_status === 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </p>
                    <p class="mb-2">
                        <strong>Account:</strong>
                        @if($vendor->status === 'suspended')
                            <span class="badge bg-danger">Suspended</span>
                        @else
                            <span class="badge bg-success">Active</span>
                        @endif
                    </p>

                    @if($vendor->verified_at)
                        <p class="small text-muted">Verified on: {{ $vendor->verified_at->format('d M Y, h:i A') }}</p>
                    @endif
                    @if($vendor->rejection_reason)
                        <div class="alert alert-danger small"><strong>Rejection reason</strong><div>{{ $vendor->rejection_reason }}</div></div>
                    @endif
                    @if($vendor->suspension_reason)
                        <div class="alert alert-warning small"><strong>Suspension reason</strong><div>{{ $vendor->suspension_reason }}</div></div>
                    @endif

                    <hr>

                    @if($vendor->verification_status !== 'verified')
                        <form action="{{ route('admin.vendors.approve', $vendor) }}" method="POST" class="mb-3"
                              onsubmit="return confirm('Are you sure you want to verify this vendor?')">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" @disabled($vendor->status === 'suspended')}>
                                Approve / Verify Vendor
                            </button>
                        </form>
                    @endif

                    @if($vendor->verification_status !== 'rejected')
                        <form action="{{ route('admin.vendors.reject', $vendor) }}" method="POST" class="mb-3">
                            @csrf
                            <label class="form-label">Rejection Reason</label>
                            <textarea name="reason" class="form-control mb-2" rows="3"
                                      placeholder="Enter reason for rejection" required></textarea>
                            <button type="submit" class="btn btn-outline-danger w-100"
                                    onclick="return confirm('Reject this vendor application?')">Reject Vendor</button>
                        </form>
                    @endif

                    <hr>

                    @if($vendor->status === 'active')
                        <form action="{{ route('admin.vendors.suspend', $vendor) }}" method="POST">
                            @csrf
                            <label class="form-label">Suspension Reason</label>
                            <textarea name="reason" class="form-control mb-2" rows="3"
                                      placeholder="Enter reason for suspension" required></textarea>
                            <button type="submit" class="btn btn-danger w-100"
                                    onclick="return confirm('Suspend this vendor account?')">Suspend Vendor</button>
                        </form>
                    @else
                        <form action="{{ route('admin.vendors.reactivate', $vendor) }}" method="POST"
                              onsubmit="return confirm('Reactivate this vendor account?')">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">Reactivate Vendor</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
