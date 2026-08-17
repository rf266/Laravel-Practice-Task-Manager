@extends('layouts.app')

@section('title', 'My Tasks')

@section('extra-css')
<style>
    .tasks-list {
        list-style: none;
    }

    .task-item {
        background-color: #f8f9fa;
        border-left: 4px solid #667eea;
        padding: 20px;
        margin-bottom: 15px;
        border-radius: 5px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .task-item:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .task-item.completed {
        opacity: 0.7;
        border-left-color: #28a745;
    }

    .task-item.completed .task-title {
        text-decoration: line-through;
        color: #999;
    }

    .task-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }

    .task-description {
        color: #666;
        font-size: 14px;
        margin-bottom: 15px;
        line-height: 1.5;
    }

    .task-actions {
        display: flex;
        gap: 10px;
    }

    .task-actions a,
    .task-actions button {
        padding: 8px 16px;
        font-size: 14px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
    }

    .task-actions a.btn-edit {
        background-color: #007bff;
        color: white;
    }

    .task-actions a.btn-edit:hover {
        background-color: #0056b3;
    }

    .task-actions button.btn-delete {
        background-color: #dc3545;
        color: white;
    }

    .task-actions button.btn-delete:hover {
        background-color: #c82333;
    }

    .empty-state {
        text-align: center;
        color: #999;
        padding: 40px 20px;
    }

    .empty-state p {
        margin-bottom: 20px;
        font-size: 16px;
    }
</style>
@endsection

@section('content')
    <h1>📋 My Tasks</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div style="margin-bottom: 30px;">
        <a href="{{ route('tasks.create') }}" class="btn btn-primary" style="width: 100%; display: block; text-align: center; text-decoration: none;">
            ➕ Create New Task
        </a>
    </div>

    @if($tasks->count() > 0)
        <ul class="tasks-list">
            @foreach($tasks as $task)
                <li class="task-item {{ $task->is_completed ? 'completed' : '' }}">
                    <div class="task-title">{{ $task->title }}</div>
                    
                    @if($task->description)
                        <div class="task-description">{{ $task->description }}</div>
                    @endif

                    <div class="task-actions">
                        <a href="{{ route('tasks.edit', $task) }}" class="btn-edit">
                            ✏️ Edit
                        </a>

                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" onclick="return confirm('Are you sure?');">
                                🗑️ Delete
                            </button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <div class="empty-state">
            <p>No tasks yet. Create one to get started!</p>
            <a href="{{ route('tasks.create') }}" class="btn btn-primary">Create Your First Task</a>
        </div>
    @endif
@endsection