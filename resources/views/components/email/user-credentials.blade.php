<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Your Account Details</title>
    <style>
        body {
            background-color: #f4f4f7;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #51545e;
        }

        .email-wrapper {
            width: 100%;
            background-color: #f4f4f7;
            padding: 40px 0;
        }

        .email-content {
            max-width: 550px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 10px;
            padding: 35px;
            box-shadow: 0px 4px 18px rgba(0, 0, 0, 0.05);
        }

        h2 {
            color: #333333;
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        p {
            font-size: 15px;
            line-height: 1.6;
            margin: 8px 0;
        }

        .info-box {
            background: #f0f5ff;
            padding: 15px;
            border-left: 4px solid #4f46e5;
            border-radius: 6px;
            margin: 20px 0;
        }

        .btn {
            display: inline-block;
            background: #4f46e5;
            color: #ffffff !important;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            margin-top: 35px;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>

<div class="email-wrapper">
    <div class="email-content">

        <h2>Hi {{ $email }},</h2>

        <p>Your account has been created successfully. Below are your login details:</p>

        <div class="info-box">
            <p><strong>Email:</strong> {{ $email }}</p>
            <p><strong>Password:</strong> {{ $password }}</p>
        </div>

        <p>Use the button below to log in to your account:</p>

        <a href="{{ url('admin/login') }}" class="btn">Login Now</a>

        <p>If you did not request this, please contact support immediately.</p>

        <p>Regards,<br><strong>{{ config('app.name') }}</strong></p>

    </div>

    <p class="footer">
        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </p>
</div>

</body>
</html>
