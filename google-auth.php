<?php
session_start();

require_once realpath(dirname(__FILE__) . '/google/google-api-php-client/autoload.php');

$client_id = '<YOUR_CLIENT_ID>';
$client_secret = '<YOUR_SECRET_KEY>';

$client = new Google_Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setScopes('email profile');

$token = $_POST['token_google'];
if($token != null){
    try{
        $user_info = $client->verifyIdToken($token)->getAttributes();
        if($user_info['payload']['hd'] == '<YOUR_DOMAIN>'){ // <YOUR_DOMAIN>
            
            if($user_info['payload']['name'] == null){
                echo 'User not found';
                return;
            }
            //create session
            $_SESSION['USER_INFO'] = $user_info['payload'];
            echo 'GOOGLE_AUTH_OK';
        }
    }catch (Exception $e){
        echo 'INCORRECT_TOKEN';
    }
}else{
    echo 'TOKEN_NOT_FOUND';
}

/*
//$user_info structure

[envelope] => Array
    (
        [alg] => RS256
        [kid] => aaa6a5f48d96fFFF9726351XXXdf277e79c
    )
[payload] => Array
    (
        [iss] => accounts.google.com
        [at_hash] => 2chfiFdjBAbAIlAipAIig
        [aud] => 57772388960-.....apps.googleusercontent.com
        [sub] => 11750...0335
        [email_verified] => 1
        [azp] => 57772388960.....apps.googleusercontent.com
        [hd] => <EMAIL_DOMAIN>
        [email] => <USER_EMAIL>
        [iat] => 1460635168
        [exp] => 1460638768
        [name] => <USER FULL NAME>
        [given_name] => <FIRSTNAME>
        [family_name] => <LASTNAME>
        [locale] => pt
*/
?>