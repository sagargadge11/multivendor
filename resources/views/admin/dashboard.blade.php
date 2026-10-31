@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Admin Dashboard</h3>
    <div class="row g-3">
        @foreach([
            'Total Vendors'=>$stats['vendors'],
            'Pending Vendors'=>$stats['pending_vendors'],
            'Total Products'=>$stats['products'],
            'Pending Products'=>$stats['pending_products'],
            'Enquiries'=>$stats['enquiries'],
            'Customers'=>$stats['customers'],
        ] as $label=>$value)
            <div class="col-6 col-lg-4">
                <div class="card border-0 shadow-sm"><div class="card-body">
                    <div class="text-muted">{{ $label }}</div><div class="display-6 fw-semibold">{{ $value }}</div>
                </div></div>
            </div>
        @endforeach
    </div>
</div>
@endsection
