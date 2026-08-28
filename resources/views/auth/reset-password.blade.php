@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
    <h1>🔄 Reset Your Password</h1>

    @if($errors->any())
        <div class="errors">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label for="password">New Password *</label>
            <input 
                type="password" 
                id="password" 
                name="password"
                placeholder="Minimum 6 characters"
                required
            >
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password *</label>
            <input 
                type="password" 
                id="password_confirmation" 
                name="password_confirmation"
                placeholder="Re-enter password"
                required
            >
        </div>

        <div class="button-group">
            <button type="submit" class="btn btn-success">Reset Password</button>
            <a href="{{ route('login') }}" class="btn btn-secondary" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">
                Back to Login
            </a>
        </div>
    </form>
@endsection