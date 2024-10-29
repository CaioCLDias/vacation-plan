<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - Welcome</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600" rel="stylesheet">
    <style>
        body { font-family: 'Nunito', sans-serif; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; }
        .section { margin-bottom: 20px; }
        .section h2 { margin-bottom: 10px; }
        .credentials { background-color: #f5f5f5; padding: 15px; border-radius: 5px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Welcome to {{ config('app.name') }}</h1>
    <p>This application provides a platform to manage vacation plans.</p>

    <div class="section">
        <h2>Documentation</h2>
        <p>
            Access the API documentation here:
            <a href="{{ config('app.url') }}/docs/" target="_blank">{{ config('app.url') }}/docs</a>
        </p>
    </div>

    <div class="section">
        <h2>Test Credentials</h2>
        <div class="credentials">
            <p><strong>Email:</strong> {{ $email }}</p>
            <p><strong>Password:</strong> {{ $password }}</p>
            <p><strong>Client ID:</strong> {{ $clientId}}</p>
            <p><strong>Client Secret:</strong> {{ $clientSecret }}</p>
        </div>
    </div>
</div>
</body>
</html>
