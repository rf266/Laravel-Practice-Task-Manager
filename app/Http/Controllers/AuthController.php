<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\PasswordHelper;
use Closure;
use App\Models\User;
use App\Mail\VerificationEmail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendVerificationEmail;
use App\Jobs\SendPasswordResetEmail;
use App\Jobs\SendWelcomeEmail;

class AuthController extends Controller
{
    public function showRegister() {
        return view('auth.register'); //showing the reg page

    }

    public function register(Request $request ) {
        $validated = $request->validate(['username'=>'required|string|min:5|max:100|unique:users',
        'email' =>'required|string|unique:users',
        'password'=>'required|string|min:6',

    ]); //first, entered details from view are sent inside a Request object to the controller, then validated

    $validated['password']=PasswordHelper::hash_password($validated['password']); //password cant be stored as is, must be hashed using helper func
    $validated['email_verification_token'] = Str::random(60);
    $validated['email_verification_expires_at'] = now()->addDay();

    $user = User::create($validated); //add record to users table

    $verificationUrl = route('email.verify', [
        'token'=>$user->email_verification_token,
    ]);

    SendVerificationEmail::dispatch($user, $verificationUrl);

    return redirect()->route('login')->with('success',"Account created, please check your emails for the verification"); //redirect to login view with success message

    }


    public function showLogin(){
        return view('auth.login'); //display login blade view
    }

    public function login(Request $request) {
        $creds = $request->validate(['username'=>'required|string',
        'password'=>'required|string',]); //first check if the entered data is valid

        $user = User::where('username', $creds['username'])->first(); //find by username, first instance, we are selecting and loading in this record

        if (!$user || !PasswordHelper::verify_password($creds['password'], $user->password)) {
            return back()->withErrors([
                'username'=>'Invalid user or password'
            ])->withInput();
        }

        if (!$user->is_verified){
            return back()->withErrors(['username'=>'please verify your email before login'])->withInput();
        }

        $request->session()->regenerate();
        session(['user_id'=>$user->id,'username'=>$user->username]); //create a session

        return redirect()->route('tasks.index')->with('success', 'sign in successful');

    }

    public function logout() {

        session()->flush(); //end session

        return redirect()->route('login')->with('success','logged out successfully');
    }

    public function verifyEmail(Request $request) {
        $user = User::where('email_verification_token', $request->token)->where('email_verification_expires_at','>',now())->first();
        if (!$user) {
            return redirect()->route('login')->withErrors('invalid or expired verification link');
        }

        if ($user->is_verified) {
            return redirect()->route('login')->with('success', 'email already verified');
        }

        $user->update(
            [
                'is_verified'=>true,
                'email_verified_at'=>now(),
                'email_verification_token'=>null,
            ]
        );

        SendWelcomeEmail::dispatch($user);

        return redirect()->route('login')->with('success', "email verified. you can login");

    }

    public function showForgotPassword() {
        return view('auth.forgot-password');
    
    }


    public function sendPasswordReset(Request $request) {
        $request->validate(['email'=>'required|email|exists:users']);

        $user = User::where('email',$request->email)->first();
        $token = Str::random(60);

        $user->update(['password_reset_token'=>$token, 'password_reset_expires_at'=>now()->addHour(),]);
        $resetUrl = route('password.reset',['token'=>$token]);

        SendPasswordResetEmail::dispatch($user, $resetUrl);
    
        return redirect()->route('login')->with('success', 'check your email for password reset link');
        }


    public function showResetPassword($token)
{
   $user = User::where('password_reset_token', $token)
        ->where('password_reset_expires_at', '>', now())
        ->first();

    if (!$user) {
        return redirect()->route('login')
            ->withErrors('invalid or expired reset link');
    }

    return view('auth.reset-password', [
        'token' => $token
    ]);
}

    public function resetPassword(Request $request) {

        $validated = $request->validate([
            'token'=>'required|string',
            'password'=>'required|string|min:6',
            'password_confirmation'=>'required|same:password',
        ]);

        $user = User::where(
            'password_reset_token',$validated['token'])
        ->where('password_reset_expires_at','>',now())->first();

        if(!$user) {
            return redirect()->route('login')->withErrors('invalid or expired password reset link');

        }

        $user->update([
            'password'=>PasswordHelper::hash_password($validated['password']),'password_reset_token'=>null,'password_reset_expires_at'=>null]);


        

        return redirect()->route('login')->with('success', 'password changed successfully. please login');

        }




}



