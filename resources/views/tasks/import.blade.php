@extends('layouts.app')

@section('title', 'Import Tasks')

@section('extra-css')
<style>
    .import-header {
        text-align: center;
        margin-bottom: 24px;
    }

    .import-header h1 {
        margin-bottom: 8px;
    }

    .import-header p {
        color: #666;
        line-height: 1.6;
    }

    .import-card {
        border: 2px dashed #cbd3f6;
        border-radius: 8px;
        background: #f8f9ff;
        padding: 24px;
        margin-bottom: 20px;
        text-align: center;
    }

    .import-card input[type="file"] {
        width: 100%;
        margin: 16px 0;
        padding: 12px;
        border: 1px solid #dfe3ff;
        border-radius: 6px;
        background: white;
        cursor: pointer;
    }

    .import-actions {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .import-actions .btn {
        flex: 1;
        text-decoration: none;
        text-align: center;
    }

    .template-link {
        display: inline-block;
        color: #667eea;
        font-weight: 600;
        text-decoration: none;
        margin-top: 16px;
    }

    .template-link:hover {
        text-decoration: underline;
    }

    .import-errors {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .import-errors ul {
        margin-top: 8px;
        padding-left: 20px;
    }
</style>
@endsection

@section('content')
    <div class="import-header">
        <h1>📥 Import Tasks</h1>
        <p>Upload a CSV file to quickly add multiple tasks.</p>
    </div>

    @if($errors->any())
        <div class="errors">
            <strong>Please fix the following error:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('import_errors'))
        <div class="import-errors">
            <strong>Some rows were skipped:</strong>
            <ul>
                @foreach(session('import_errors') as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="import-card">
        <strong>Choose a CSV file</strong>
        <p style="color: #777; margin-top: 6px;">
            The first column must be <strong>title</strong>. Description is optional.
        </p>

        <form method="POST" action="{{ route('tasks.import.store') }}" enctype="multipart/form-data">
            @csrf

            <input
                type="file"
                name="file"
                accept=".csv,text/csv"
                required
            >

            <div class="import-actions">
                <button type="submit" class="btn btn-success">📤 Import Tasks</button>
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                    ← Back to Tasks
                </a>
            </div>
        </form>

        <a href="{{ route('tasks.import.template') }}" class="template-link">
            ⬇️ Download CSV template
        </a>
    </div>
@endsection