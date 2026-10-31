@extends('layouts.app')
@section('title','Login')
@section('content')
<div class="container" style="max-width:520px">
    <div class="card border-0 shadow-sm"><div class="card-body p-4 p-md-5">
        <h3 class="mb-4">Login</h3>
        @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus></div>
            <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
            <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="remember" value="1" id="remember"><label class="form-check-label" for="remember">Remember me</label></div>
            <button class="btn btn-primary w-100">Login</button>
        </form>
        <p class="mt-3 mb-0 text-center">Don't have an account? <a href="{{ route('register') }}">Register</a></p>
    </div></div>
</div>
@endsection
