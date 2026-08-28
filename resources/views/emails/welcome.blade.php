<h1>Welcome to Task Manager, {{ $user->username }}!</h1>

<p>Your email has been verified successfully.</p>

<p>You can now login and start managing your tasks:</p>

<p>
    <a href="{{ route('login') }}" style="display: inline-block; padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px;">
        Go to Login
    </a>
</p>

<p>Happy task managing!<br>Task Manager Team</p>