@extends('layouts.app')

@section('title', 'Create Task')

@section('content')
    <h1>✏️ Create New Task</h1>

    @if($errors->any())
        <div class="errors">
            <strong>Please fix the following errors:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="title">Task Title *</label>
            <input 
                type="text" 
                id="title" 
                name="title" 
                placeholder="What do you need to do?"
                value="{{ old('title') }}"
                required
            >
            @error('title')
                <small style="color: #dc3545;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea 
                id="description" 
                name="description" 
                placeholder="Add more details (optional)"
            >{{ old('description') }}</textarea>
            @error('description')
                <small style="color: #dc3545;">{{ $message }}</small>
            @enderror
        </div>

        <div class="button-group">
            <button type="submit" class="btn btn-success">✅ Create Task</button>
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">
                ← Cancel
            </a>
        </div>
    </form>
@endsection