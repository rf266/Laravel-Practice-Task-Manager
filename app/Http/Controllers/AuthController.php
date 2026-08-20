<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function showRegister() {
        return view('register'); //showing the reg page

    }

    public function register(Request $request ) {
        $validated = $request->validate(['username'=>'required|string|min:5|max:100|unique:users',
        'email' =>'required|string|unique:users',
        'password'=>'required|string|min:6',

    ]); //first, entered details from view are sent inside a Request object to the controller, then validated

    $validated['password']=hash_password($validated['password']); //password cant be stored as is, must be hashed using built in func

    User::create($validated); //add record to users table

    return redirect()->route('login')->with('success',"sign in successful") //redirect to login view with success message

    }


    public function showLogin(){
        return view('login'); //display login blade view
    }

    public function login(Request $request) {
        $creds = $request->validate(['username'=>'required|string',
        'password'=>'required|string',]); //first check if the entered data is valid

        $user = User::where('username', $creds['username'])->first(); //find by username, first instance, we are selecting and loading in this record

        if (!$user || !verify_password($creds['password'], $user->password)) {
            return back()->withErrors([
                'username'->'Invalid user or password'
            ])->withInput();
        }

        session(['user_id'=>$user->id,'username'=>$user-username]); //create a session

        return redirect()->route('tasks.index')->with('success', 'sign in successful');

    }

    public function logout() {

        session()->flush(); //end session

        return redirect()->route('login')->with('sucess','logged out successfully');
    }

}
