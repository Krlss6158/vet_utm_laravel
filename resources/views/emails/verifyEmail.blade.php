<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinica veterinaria UTM</title>
</head>
<body>
    <h1>{{$detail['title']}}</h1>
    <p>{{$detail['body']}}</p>
    <a href="{{$detail['backurl']}}/api/verifyEmail/{{$detail['api_token']}}">LINK</a>
</body>
</html>