<!DOCTYPE html>
<html>
<head>
    <title>New Message</title>
</head>
    <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
        <h2>New Message from Cool Movies</h2>

        <p><strong>From:</strong> {{ $data['email'] }}</p>

        <hr>

        <p><strong>Message:</strong></p>
        <p style="background: #f4f4f4; padding: 15px; border-radius: 5px;">
            {{ nl2br(e($data['message'])) }}
        </p>
    </body>
</html>
