@extends('layouts.app')
@section('title','Register')
@section('content')
<div class="container" style="max-width:650px">
    <div class="card border-0 shadow-sm"><div class="card-body p-4 p-md-5">
        <h3 class="mb-4">Create Account</h3>
        @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <form method="POST" action="{{ route('register.submit') }}">
            @csrf
            <div class="mb-3"><label class="form-label">Your Name *</label><input name="name" class="form-control" value="{{ old('name') }}" required></div>
            <div class="mb-3"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" value="{{ old('email') }}" required></div>
            <div class="mb-3"><label class="form-label">Phone</label><input name="phone" class="form-control" value="{{ old('phone') }}"></div>
            <div class="mb-3"><label class="form-label">Account Type *</label>
                <select name="account_type" id="accountType" class="form-select" required>
                    <option value="customer" @selected(old('account_type','customer')==='customer')>Customer</option>
                    <option value="vendor" @selected(old('account_type')==='vendor')>Vendor</option>
                </select>
            </div>
            <div class="mb-3" id="businessNameField" style="display:none">
                <label class="form-label">Business Name *</label>
                <input name="business_name" id="businessName" class="form-control" value="{{ old('business_name') }}">
            </div>
            <div class="mb-3"><label class="form-label">Password *</label><input type="password" name="password" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Confirm Password *</label><input type="password" name="password_confirmation" class="form-control" required></div>
            <button class="btn btn-primary w-100">Create Account</button>
        </form>
        <p class="mt-3 mb-0 text-center">Already registered? <a href="{{ route('login') }}">Login</a></p>
    </div></div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
 const type=document.getElementById('accountType');
 const wrap=document.getElementById('businessNameField');
 const field=document.getElementById('businessName');
 function toggle(){const vendor=type.value==='vendor';wrap.style.display=vendor?'':'none';field.required=vendor;}
 type.addEventListener('change',toggle);toggle();
});
</script>
@endsection
