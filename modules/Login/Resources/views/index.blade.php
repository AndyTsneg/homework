<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Homework</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        .warning {
            background-color: red;
        }

    </style>
</head>
<body>
    <div>
        @if ($warningMessage !='')
            <b class="warning">{{$warningMessage}}</b>
        @endif
        <form action="/login/" method="post">
            ID:<input type="text" name="id"><br>
            PW:<input type="password" name="pw"><br>
            <input type="submit" name="go" value="Sign In">
            <input type="button" onclick="goForRegister()" value="Sign Up">
        </form>
    </div>
</body>
</html>
<script>
    function goForRegister(){
        window.location="/login/register/"
    }
</script>
