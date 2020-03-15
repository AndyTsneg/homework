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


<?php
    //For FB
    $fb = new Facebook\Facebook([
        'app_id' => $_ENV['FB_APP_ID'],
        'app_secret' => $_ENV['FB_APP_SECRET'],
        'default_graph_version' => 'v6.0',
    ]);
    $helper = $fb->getRedirectLoginHelper();
    $permissions = ['email']; // Optional permissions
    $loginUrl = $helper->getLoginUrl($_ENV['FB_AUTH_URL'], $permissions);
    echo '<br>';
    echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
?>


<?php
    //For GOOOGLE
    $client = new Google_Client;
    $client->setClientId($_ENV['GOOGLE_APP_ID']);
    $client->setClientSecret($_ENV['GOOGLE_APP_Secret']);


    $client->revokeToken();

    // 添加授權範圍，參考 https://developers.google.com/identity/protocols/googlescopes
    $client->addScope(['https://www.googleapis.com/auth/userinfo.profile']);
    $client->setRedirectUri($_ENV['GOOGLE_AUTH_URL']);
    $url = $client->createAuthUrl();
    //    header("Location:{$url}");
    echo '<br>';
    echo '<a href="'.$url.'">Log in with Google!</a>';

 ?>