@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <h1>🔐 Login</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="errors">
            <strong>Login failed:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="username">Username *</label>
            <input 
                type="text" 
                id="username" 
                name="username"
                placeholder="Your username"
                value="{{ old('username') }}"
                required
                autofocus
            >
            @error('username')
                <small style="color: #dc3545;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password *</label>
            <input 
                type="password" 
                id="password" 
                name="password"
                placeholder="Your password"
                required
            >
            @error('password')
                <small style="color:rgb(223, 64, 80);">{{ $message }}</small>
            @enderror
        </div>

        <div class="button-group">
            <button type="submit" class="btn btn-primary">🔐 Login</button>
            <a href="{{ route('register') }}" class="btn btn-secondary" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">
                Create new account
            </a>
        </div>
    </form>
@endsection