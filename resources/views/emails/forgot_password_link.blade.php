<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password reset</title>
</head>
<body>
    <p>Hello {{ $name }},</p>
    <p>We received a request to reset the password for your account ({{ $email }}).</p>
    <p>Click the link below to choose a new password. This link will expire according to your application's password reset token settings.</p>
    <p><a href="{{ $url }}">Reset your password</a></p>
    <p>If you did not request a password reset, no action is needed.</p>
    <p>Thanks,<br>Your team</p>
</body>
</html>
