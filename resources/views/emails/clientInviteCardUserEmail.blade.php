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
            color: #1c545d;
        }
        p {
            font-size: 16px;
            line-height: 1.5;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            color: #fff;
            /* background-color: #007bff; */
            border-radius: 5px;
            text-decoration: none;
        }
        a:hover {
            background-color: #238594;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hi! {{ $data['name'] }} </h1>
        <p>You have been Invited to create a card in {{$data['appname'] }} by {{ Auth::user()->name }}.</p>
        <a href="{{ route('showClientRegister') }}" target="_blank" style="color: #1c545d">Click Here!</a>
    </div>
</body>
</html>
