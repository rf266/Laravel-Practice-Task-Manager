@extends('layouts.app')

@section('title', 'Edit Task')

@section('content')
    <h1>✏️ Edit Task</h1>

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

    <form action="{{ route('tasks.update', $task) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Task Title *</label>
            <input 
                type="text" 
                id="title" 
                name="title" 
                placeholder="What do you need to do?"
                value="{{ old('title', $task->title) }}"
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
            >{{ old('description', $task->description) }}</textarea>
            @error('description')
                <small style="color: #dc3545;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group checkbox">
            <input 
                type="checkbox" 
                id="is_completed" 
                name="is_completed" 
                value="1"
                {{ old('is_completed', $task->is_completed) ? 'checked' : '' }}
            >
            <label for="is_completed">Mark as completed</label>
        </div>

        <div class="button-group">
            <button type="submit" class="btn btn-success">💾 Update Task</button>
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">
                ← Cancel
            </a>
        </div>
    </form>
@endsection