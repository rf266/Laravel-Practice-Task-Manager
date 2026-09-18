@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        
        <!-- Profile Header Banner -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-32"></div>
        
        <!-- Profile Content -->
       <div class="px-6 pb-6">
            <div class="mb-4 flex gap-2">
                <a href="{{ route('tasks.index') }}"
                    class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-2 rounded">
                     ← My Tasks
        </a>

        <a href="{{ route('profile.show', session('user_id')) }}"
           class="inline-block bg-blue-100 hover:bg-blue-200 text-blue-800 px-3 py-2 rounded">
            My Profile
        </a>
    </div>            
            <!-- Avatar Section -->
            <div class="flex items-end -mt-16 mb-4">
                <div class="relative">
                    <img src="{{ $user->avatar_url }}" 
                         alt="{{ $user->username }}" 
                         class="w-32 h-32 rounded-full border-4 border-white object-cover bg-gray-200">
                    
                    <!-- Edit button (only for own profile) -->
                    @if(session('user_id')  && session('user_id') === $user->id)
                        <a href="{{ route('avatars.create', $user->id) }}" 
                           class="absolute bottom-0 right-0 bg-blue-500 hover:bg-blue-600 text-white rounded-full p-2 shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M15 13a3 3 0 11-6 0 3 3 0 016 0z">
                                </path>
                            </svg>
                        </a>
                    @endif
                </div>
                
                <div class="ml-4">
                    <h1 class="text-3xl font-bold text-gray-800">{{ $user->username }}</h1>
                    <p class="text-gray-600">{{ $user->email }}</p>
                </div>
            </div>
            
            <!-- Edit Profile Button -->
            @if(session('user_id') && session('user_id') === $user->id)
                <a href="{{ route('profile.edit', $user->id) }}" 
                   class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mb-4">
                    ✏️ Edit Profile
                </a>
            @endif
            
            <!-- Bio Section -->
            @if($user->bio)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Bio</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $user->bio }}</p>
                </div>
            @endif
            
            <!-- Stats Grid -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-gray-600 text-sm">Total Tasks</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $tasksCount }}</p>
                </div>
                <div class="bg-purple-50 rounded-lg p-4">
                    <p class="text-gray-600 text-sm">Documents</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $documentsCount }}</p>
                </div>
            </div>
            
            <!-- Member Since -->
            <div class="border-t pt-4 text-gray-600 text-sm">
                <p>Member since <strong>{{ $user->created_at->format('F j, Y') }}</strong></p>
            </div>
        </div>
    </div>
</div>
@endsection