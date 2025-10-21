<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password - BookVerse</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .content {
            padding: 40px 30px;
        }
        .content p {
            margin: 0 0 20px;
            font-size: 16px;
            color: #555;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        .button:hover {
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .info-box p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 14px;
            color: #999;
        }
        .footer p {
            margin: 5px 0;
        }
        .link-text {
            word-break: break-all;
            color: #667eea;
            font-size: 14px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🔐 Reset Your Password</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Hello,</p>
            
            <p>We received a request to reset your password for your BookVerse account. Click the button below to create a new password:</p>

            <div class="button-container">
                <a href="{{ url('/password/reset/' . $token . '?email=' . urlencode($email)) }}" class="button">
                    Reset Password
                </a>
            </div>

            <div class="info-box">
                <p><strong>⏰ This link will expire in 60 minutes.</strong></p>
            </div>

            <p>If the button doesn't work, copy and paste this link into your browser:</p>
            <div class="link-text">
                {{ url('/password/reset/' . $token . '?email=' . urlencode($email)) }}
            </div>

            <p><strong>If you didn't request a password reset, please ignore this email.</strong> Your password will remain unchanged.</p>

            <p>Best regards,<br>The BookVerse Team</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} BookVerse. All rights reserved.</p>
            <p>This is an automated email. Please do not reply.</p>
        </div>
    </div>
</body>
</html>
