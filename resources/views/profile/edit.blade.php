@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Profile</h1>
        
        <!-- Error Messages -->
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('profile.update', $user->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            
            <!-- Username -->
            <div>
                <label for="username" class="block text-gray-700 font-medium mb-2">Username</label>
                <input type="text" id="username" name="username" value="{{ $user->username }}" 
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       required>
            </div>
            
            <!-- Email -->
            <div>
                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ $user->email }}" 
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       required>
            </div>
            
            <!-- Bio -->
            <div>
                <label for="bio" class="block text-gray-700 font-medium mb-2">Bio (optional)</label>
                <textarea id="bio" name="bio" rows="4" maxlength="500"
                          class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Tell us about yourself...">{{ $user->bio }}</textarea>
                <p class="text-sm text-gray-500 mt-1">Max 500 characters</p>
            </div>
            
            <!-- Buttons -->
            <div class="flex gap-4 pt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded font-medium">
                    Save Changes
                </button>
                <a href="{{ route('profile.show', $user->id) }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded font-medium">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection