<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class UserProfileController extends Controller
{
    public function show(User $user) {
        $tasksCount = $user->tasks()->count();
        $documentsCount = $user->documents()->count();

        return view('profile.show',
        [
            'user'=>$user,
            "tasksCount"=>$tasksCount,
            "documentsCount"=>$documentsCount
        ]);
    }

    public function edit(User $user)
    {
        if (auth()->user()->id !== $user->id){
            abort(403, 'Unauthorized');
        }

        return view('profile.edit', ["user"=>$user]);
    }

    public function update(Request $request, User $user) {
        if(auth()->user()->id !== $user->id) {
            abort(403, "Unauthorized");
        }

        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:500'
        ]);

        $user->update($validated);

        return redirect()->route('profile.show', $user->id)->with('success', "Profile updated successfully");
        
    }

}
