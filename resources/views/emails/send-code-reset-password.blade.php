<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset Code</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .code {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #2563eb;
            margin: 20px 0;
            padding: 10px;
            background: #f8fafc;
            display: inline-block;
        }
        .footer { margin-top: 30px; font-size: 12px; color: #64748b; }
    </style>
</head>
<body>
<div class="container">
    <h2>Password Reset Request</h2>

    <p>We received a request to reset your password. Here is your verification code:</p>

    <div class="code">{{ $code }}</div>

    <p>This code will expire in {{ $expiration }}. If you didn't request this, please ignore this email.</p>

    <div class="footer">
        <p>© {{ date('Y') }} {{ $appName }}. All rights reserved.</p>
    </div>
</div>
</body>
</html>
