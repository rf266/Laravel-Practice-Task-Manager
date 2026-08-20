@extends('layouts.app')

@section('title', 'Register') 


@section('content')

<h1>📝 Create Account</h1>

    @if($errors->any())
        <div class="errors">
            <strong>Please fix the errors:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif




<form action="{{ route('register') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="username">Username *</label>
            <input 
                type="text" 
                id="username" 
                name="username"
                placeholder="Choose a username (3+ chars)"
                value="{{ old('username') }}"
                required
            >
            @error('username')
                <small style="color: #dc3545;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email *</label>
            <input 
                type="email" 
                id="email" 
                name="email"
                placeholder="your@email.com"
                value="{{ old('email') }}"
                required
            >
            @error('email')
                <small style="color: #dc3545;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password *</label>
            <input 
                type="password" 
                id="password" 
                name="password"
                placeholder="Minimum 6 characters"
                required
            >
            @error('password')
                <small style="color: #dc3545;">{{ $message }}</small>
            @enderror
        </div>

        <div class="button-group">
            <button type="submit" class="btn btn-success">✅ Create Account</button>
            <a href="{{ route('login') }}" class="btn btn-secondary" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">
                Already have an account?
            </a>
        </div>
    </form>
@endsection

@endsection