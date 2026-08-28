@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
    <h1>🔐 Forgot Password?</h1>

    <p>Enter your email address and we'll send you a link to reset your password.</p>

    @if($errors->any())
        <div class="errors">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('password.email') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="email">Email Address *</label>
            <input 
                type="email" 
                id="email" 
                name="email"
                placeholder="your@email.com"
                value="{{ old('email') }}"
                required
            >
        </div>

        <div class="button-group">
            <button type="submit" class="btn btn-primary">Send Reset Link</button>
            <a href="{{ route('login') }}" class="btn btn-secondary" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">
                Back to Login
            </a>
        </div>
    </form>
@endsection