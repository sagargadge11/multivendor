@extends('layouts.vendor')
@section('content')
<div class="container-fluid">
    <h3 class="mb-1">Welcome, {{ $vendor->business_name }}</h3>
    <p class="text-muted">Vendor Dashboard</p>

    @if($vendor->verification_status === 'pending')
        <div class="alert alert-warning">Your business verification is pending admin review.</div>
    @elseif($vendor->verification_status === 'rejected')
        <div class="alert alert-danger">
            Your business verification was rejected.
            @if($vendor->rejection_reason)<div class="mt-1">Reason: {{ $vendor->rejection_reason }}</div>@endif
            Please update your profile and contact the administrator.
        </div>
    @else
        <div class="alert alert-success">Your business is verified.</div>
    @endif

    <div class="row g-3">
        @foreach([
            'Total Products'=>$stats['products'],
            'Approved Products'=>$stats['approved_products'],
            'Pending Products'=>$stats['pending_products'],
            'Enquiries'=>$stats['enquiries'],
        ] as $label=>$value)
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm"><div class="card-body">
                    <div class="text-muted">{{ $label }}</div><div class="display-6 fw-semibold">{{ $value }}</div>
                </div></div>
            </div>
        @endforeach
    </div>
    <div class="mt-4"><a href="{{ route('vendor.profile.edit') }}" class="btn btn-primary">Complete Business Profile</a></div>
</div>
@endsection
