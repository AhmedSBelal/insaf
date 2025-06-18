<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Received</title>
    <style>
        body { font-family: 'Arial', sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { color: #2d3748; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; }
        .content { padding: 20px 0; }
        .footer { font-size: 0.9em; color: #718096; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        .details { background: #f7fafc; padding: 15px; border-radius: 5px; margin-top: 20px; }
    </style>
</head>
<body>
{{--@dd($name)--}}
<div class="container">
    <div class="header">
        <h1>Thank You, {{ $name }}!</h1>
        <p>{{ $currentDate }}</p>
    </div>

    <div class="content">
        <p>We've received your message and our team will get back to you within 24-48 hours.</p>

        <div class="details">
            <h3>Your Message Details:</h3>
            <p><strong>Name:</strong> {{ $name }}</p>
            <p><strong>Email:</strong> {{ $email }}</p>
            @if($phone)
                <p><strong>Phone:</strong> {{ $phone }}</p>
            @endif
            <p><strong>Message:</strong></p>
            <p style="white-space: pre-wrap;">{{ $userMessage }}</p>
        </div>
    </div>

    <div class="footer">
        <p>If you need immediate assistance, please call our support line at 0111..... .</p>
        <p>© {{ date('Y') }} {{ $appName }}. All rights reserved.</p>
    </div>
</div>
</body>
</html>
