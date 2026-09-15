<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\FileHelper;


class AvatarUploadController extends Controller
{
    public function create(User $user) {
        if (auth()->user()->id !== $user->id) {
            abort(403, "unauthorised");
        }
        return view('avatars.upload', ['user'=>$user]);
    }

    public function store(Request $request, User $user) {
        if (auth()->user()->id !== $user->id) {
            return response()->json([
                'success'=>false, 'error'=>'unauthorised'],403);
            
        }

        if (!$request->hasFile('avatar')) {
            return response()->json([
                'succcess' =>false, 'error'=>"no file provided"], 400);
     
        }

        $file = $request->file('avatar');

        $result = FileHelper::uploadAvatar($file, $user->id);

        if (!$result['success']) {
            return response()->json([
                'success'=>false,
                'errors'=> $result['errors']
            ],422);
        }

        if ($user->avatar_path) {
            FileHelper::deleteAvatar($user->avatar_path);
        }

        $user->update(['avatar_path'=>$result['filename']]);

        return response()->json([
            'success'=>true, 'message'=> 'avatar uploaded successfully', 'avatar_url'=>$user->avatar_url
        ]);
    }


    public function destroy(User $user) {

        if (auth()->user()->id  !== $user->id){
            abort(403, "unauthorised");
        }

        if ($user->avatar_path) {
            FileHelper::deleteAvatar($user->avatar_path);
            $user->update(['avatar_path'=>null]);
        }

        return redirect()->route('profile.edit', $user->id)->with('success', "avatar deleted");
    }


}
