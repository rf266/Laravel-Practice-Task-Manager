@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Upload Document</h1>
        <p class="text-gray-600 mb-6">{{ $documentCount }}/5 documents uploaded</p>
        
        <!-- Upload Area -->
        <div id="upload-area" 
             class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition">
            <input type="file" id="file-input" accept=".pdf,.docx,.csv" style="display: none;">
            
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            
            <p class="text-gray-700 font-medium mb-1">Click to upload or drag and drop</p>
            <p class="text-gray-500 text-sm">PDF, DOCX, or CSV • Max 10MB</p>
        </div>
        
        <!-- Progress Bar -->
        <div id="upload-progress" class="mt-4 hidden">
            <p class="text-sm text-gray-600 mb-2">Uploading...</p>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full" id="progress-bar" style="width: 0%"></div>
            </div>
        </div>
        
        <!-- Result Message -->
        <div id="upload-result" class="mt-4 hidden"></div>
        
        <!-- File Type Info -->
        <div class="mt-8 bg-blue-50 rounded-lg p-4">
            <h3 class="font-semibold text-blue-900 mb-2">Supported File Types</h3>
            <ul class="text-sm text-blue-800 space-y-1">
                <li>✓ PDF (.pdf) - Up to 10MB</li>
                <li>✓ Word Documents (.docx) - Up to 10MB</li>
                <li>✓ Spreadsheets (.csv) - Up to 10MB</li>
            </ul>
        </div>
    </div>
</div>

<script>
const uploadArea = document.getElementById('upload-area');
const fileInput = document.getElementById('file-input');
const uploadProgress = document.getElementById('upload-progress');
const uploadResult = document.getElementById('upload-result');
const progressBar = document.getElementById('progress-bar');

// Click to browse
uploadArea.addEventListener('click', () => fileInput.click());

// Drag and drop
uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('border-blue-500', 'bg-blue-50');
});

uploadArea.addEventListener('dragleave', () => {
    uploadArea.classList.remove('border-blue-500', 'bg-blue-50');
});

uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('border-blue-500', 'bg-blue-50');
    
    if (e.dataTransfer.files.length) {
        fileInput.files = e.dataTransfer.files;
        uploadFile();
    }
});

// File selection
fileInput.addEventListener('change', uploadFile);

function uploadFile() {
    const file = fileInput.files[0];
    if (!file) return;
    
    const formData = new FormData();
    formData.append('document', file);
    
    uploadProgress.classList.remove('hidden');
    uploadResult.classList.add('hidden');
    
    fetch('{{ route("documents.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        uploadProgress.classList.add('hidden');
        
        if (data.success) {
            uploadResult.innerHTML = `
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    ✓ ${data.message}
                    <p class="text-sm mt-2">
                        <strong>${data.document.filename}</strong> (${data.document.size})
                    </p>
                    <a href="{{ route('documents.index') }}" class="text-green-800 underline mt-2 inline-block">
                        View all documents →
                    </a>
                </div>
            `;
        } else {
            const errors = data.errors ? data.errors.join('<br>') : data.error;
            uploadResult.innerHTML = `
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    ✗ Upload failed<br>${errors}
                </div>
            `;
        }
        uploadResult.classList.remove('hidden');
        fileInput.value = '';
    });
}
</script>
@endsection