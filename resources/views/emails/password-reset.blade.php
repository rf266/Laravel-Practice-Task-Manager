<h1>Reset Your Password</h1>

<p>Hi {{ $user->username }},</p>

<p>We received a request to reset your password. Click the link below:</p>

<p>
    <a href="{{ $resetUrl }}" style="display: inline-block; padding: 10px 20px; background-color: #667eea; color: white; text-decoration: none; border-radius: 5px;">
        Reset Password
    </a>
</p>

<p>This link expires in 1 hour.</p>

<p>If you didn't request this, ignore this email.</p>

<p>Task Manager Team</p>