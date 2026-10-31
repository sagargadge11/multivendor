@extends('layouts.vendor')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3 class="mb-1">Business Profile</h3>
        <p class="text-muted mb-0">Manage your business information and website details.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('vendor.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <h5 class="mb-3">Business Information</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Business Name *</label>
                        <input type="text" name="business_name" class="form-control"
                               value="{{ old('business_name', $vendor->business_name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Business Type</label>
                        <input type="text" name="business_type" class="form-control"
                               placeholder="Manufacturer, Wholesaler, Retailer"
                               value="{{ old('business_type', $vendor->business_type) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Business Email *</label>
                        <input type="email" name="business_email" class="form-control"
                               value="{{ old('business_email', $vendor->business_email) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Business Phone *</label>
                        <input type="text" name="phone" class="form-control"
                               value="{{ old('phone', $vendor->phone) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">GST Number</label>
                        <input type="text" name="gst_number" class="form-control text-uppercase"
                               maxlength="15" value="{{ old('gst_number', $vendor->gst_number) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Country *</label>
                        <input type="text" name="country" class="form-control"
                               value="{{ old('country', $vendor->country ?? 'India') }}" required>
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="mb-3">Website & Product Management</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Listing Type *</label>
                        <select name="listing_type" class="form-select" id="listingType" required>
                            <option value="manual" @selected(old('listing_type', $vendor->listing_type) === 'manual')>
                                Manual Product Management
                            </option>
                            <option value="website" @selected(old('listing_type', $vendor->listing_type) === 'website')>
                                Connect Existing Website
                            </option>
                        </select>
                        <small class="text-muted">Choose how you will manage products.</small>
                    </div>
                    <div class="col-md-6" id="websiteField">
                        <label class="form-label">Website URL</label>
                        <input type="url" name="website_url" class="form-control"
                               placeholder="https://example.com"
                               value="{{ old('website_url', $vendor->website_url) }}">
                    </div>
                </div>

                <div class="alert alert-info mt-3">
                    Website URL identifies your store. Actual platform connection and product synchronization
                    will be configured in the Website Integration module.
                </div>

                <hr class="my-4">
                <h5 class="mb-3">Business Address</h5>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="3">{{ old('address', $vendor->address) }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control"
                               value="{{ old('city', $vendor->city) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">State</label>
                        <input type="text" name="state" class="form-control"
                               value="{{ old('state', $vendor->state) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Postal Code</label>
                        <input type="text" name="postal_code" class="form-control"
                               value="{{ old('postal_code', $vendor->postal_code) }}">
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="mb-3">Business Description</h5>
                <textarea name="description" class="form-control" rows="5"
                          placeholder="Tell customers about your business...">{{ old('description', $vendor->description) }}</textarea>

                <hr class="my-4">
                <h5 class="mb-3">Business Images</h5>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">Business Logo</label>
                        @if($vendor->logo)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $vendor->logo) }}" alt="Business Logo"
                                     style="width:110px;height:110px;object-fit:contain"
                                     class="border rounded p-2">
                            </div>
                        @endif
                        <input type="file" name="logo" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                        <small class="text-muted">JPG, PNG or WebP. Maximum 2 MB.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Business Banner</label>
                        @if($vendor->banner)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $vendor->banner) }}" alt="Business Banner"
                                     style="width:100%;max-width:300px;height:100px;object-fit:cover"
                                     class="border rounded">
                            </div>
                        @endif
                        <input type="file" name="banner" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                        <small class="text-muted">JPG, PNG or WebP. Maximum 4 MB.</small>
                    </div>
                </div>

                <hr class="my-4">
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-4">Save Business Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const listingType = document.getElementById('listingType');
    const websiteField = document.getElementById('websiteField');
    const websiteInput = websiteField.querySelector('input');

    function toggleWebsiteField() {
        const isWebsite = listingType.value === 'website';
        websiteField.style.display = isWebsite ? '' : 'none';
        websiteInput.required = isWebsite;
    }

    listingType.addEventListener('change', toggleWebsiteField);
    toggleWebsiteField();
});
</script>
@endsection
