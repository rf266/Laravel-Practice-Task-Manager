<h1>Import tasks from CSV</h1>

@if ($errors->any())
    <p style="color: red;">{{ $errors->first() }}</p>
@endif

@if (session('success'))
    <p>{{ session('success') }}</p>
@endif

@if (session('import_errors'))
    <p>Some rows were skipped:</p>
    <ul>
        @foreach (session('import_errors') as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif

<p>The first row must have a column called <strong>title</strong>. A <strong>description</strong> column is optional.</p>
<p><a href="{{ route('tasks.import.template') }}">Download a sample file</a></p>

<form method="POST" action="{{ route('tasks.import.store') }}" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" accept=".csv,text/csv" required>
    <button type="submit">Import</button>
</form>