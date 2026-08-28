<h1>Welcome to Task Manager!</h1>

<p>Hi {{ $user->username }},</p>

<p>Thank you for registering. Please verify your email address by clicking the link below:</p>

<p>
    <a href="{{ $verificationUrl }}" style="display: inline-block; padding: 10px 20px; background-color: #667eea; color: white; text-decoration: none; border-radius: 5px;">
        Verify Email Address
    </a>
</p>

<p>Or copy this link into your browser:</p>
<p>{{ $verificationUrl }}</p>

<p>This link expires in 24 hours.</p>

<p>If you didn't create this account, please ignore this email.</p>

<p>Best regards,<br>Task Manager Team</p>