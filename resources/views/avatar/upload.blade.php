@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Upload Avatar</h1>
        <p class="text-gray-600 mb-6">Choose a profile picture (JPG, PNG, GIF, or WebP)</p>
        
        <!-- Current Avatar -->
        <div class="mb-6">
            <p class="text-gray-700 font-medium mb-2">Current Avatar:</p>
            <img src="{{ $user->avatar_url }}" 
                 alt="{{ $user->username }}" 
                 class="w-32 h-32 rounded-full object-cover bg-gray-200">
        </div>
        
        <!-- Upload Area -->
        <div id="upload-area" 
             class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition">
            <input type="file" id="file-input" accept="image/*" style="display: none;">
            
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M12 4v16m8-8H4">
                </path>
            </svg>
            
            <p class="text-gray-700 font-medium mb-1">Click to upload or drag and drop</p>
            <p class="text-gray-500 text-sm">JPG, PNG, GIF, or WebP • Max 2MB • 100x100 to 5000x5000 pixels</p>
        </div>
        
        <!-- Progress -->
        <div id="upload-progress" class="mt-4 hidden">
            <p class="text-sm text-gray-600 mb-2">Uploading...</p>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full" id="progress-bar" style="width: 0%"></div>
            </div>
        </div>
        
        <!-- Result -->
        <div id="upload-result" class="mt-4 hidden" id="result-content"></div>
        
        <!-- Delete Button -->
        @if($user->hasAvatar())
            <div class="mt-6">
                <form action="{{ route('avatars.destroy', $user->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700" 
                            onclick="return confirm('Delete your avatar?')">
                        🗑️ Delete Avatar
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>

<script>
const uploadArea = document.getElementById('upload-area');
const fileInput = document.getElementById('file-input');
const uploadProgress = document.getElementById('upload-progress');
const uploadResult = document.getElementById('upload-result');
const progressBar = document.getElementById('progress-bar');

// Click to upload
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

// File selected
fileInput.addEventListener('change', uploadFile);

function uploadFile() {
    const file = fileInput.files[0];
    if (!file) return;
    
    const formData = new FormData();
    formData.append('avatar', file);
    
    uploadProgress.classList.remove('hidden');
    uploadResult.classList.add('hidden');
    
    fetch('{{ route("avatars.store", $user->id) }}', {
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
                    ✓ Avatar uploaded successfully!
                    <p class="mt-2">
                        <a href="{{ route('profile.show', $user->id) }}" class="text-green-800 underline">
                            View profile →
                        </a>
                    </p>
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