<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #4CAF50;
        }
        p {
            font-size: 16px;
            line-height: 1.5;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            color: #007bff;
            background-color: #007bff;
            border-radius: 5px;
        }
        a:hover {
            background-color: #0056b3;

        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Congratulations! {{ $data['firstName'] }} {{ $data['lastName'] }} </h1>
        <p>Your Account is now Activated. You can now log in to your account using the link below:</p>
        <a href="{{ route('clientLogin') }}" target="_blank">Login to Your Account</a>
        <p>Use your Registration Credentials to Log-In.</p>
    </div>
</body>
</html>
