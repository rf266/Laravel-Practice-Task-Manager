@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">My Documents</h1>
            <p class="text-gray-600">{{ $documentCount }}/5 documents uploaded</p>
        </div>
        
        @if($canUpload)
            <a href="{{ route('documents.create') }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded font-medium">
                + Upload Document
            </a>
        @else
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded">
                Document limit reached (5/5)
            </div>
        @endif
    </div>
    
    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    
    @if($documents->count() > 0)
        <!-- Documents Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Filename</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Uploaded</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($documents as $doc)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="flex items-center">
                                    @if($doc->file_type === 'pdf')
                                        <span class="text-red-500 mr-2">📄</span>
                                    @elseif($doc->file_type === 'docx')
                                        <span class="text-blue-500 mr-2">📝</span>
                                    @else
                                        <span class="text-green-500 mr-2">📊</span>
                                    @endif
                                    {{ $doc->original_filename }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded uppercase text-xs font-medium">
                                    {{ strtoupper($doc->file_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $doc->file_size_formatted }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $doc->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ $doc->download_url }}" 
                                   class="text-blue-500 hover:text-blue-700 mr-4">
                                    ⬇️ Download
                                </a>
                                <button class="text-red-500 hover:text-red-700 delete-doc" 
                                        data-id="{{ $doc->id }}">
                                    🗑️ Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-4">
            {{ $documents->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-gray-50 rounded-lg p-8 text-center">
            <p class="text-gray-600 mb-4 text-lg">No documents uploaded yet</p>
            @if($canUpload)
                <a href="{{ route('documents.create') }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded inline-block font-medium">
                    Upload First Document
                </a>
            @endif
        </div>
    @endif
</div>

<script>
document.querySelectorAll('.delete-doc').forEach(btn => {
    btn.addEventListener('click', function() {
        if (confirm('Delete this document? This cannot be undone.')) {
            const docId = this.dataset.id;
            fetch(`/documents/${docId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    });
});
</script>
@endsection