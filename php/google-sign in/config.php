<?php

//config.php

//Include Google Client Library for PHP autoload file
require_once 'vendor/autoload.php';

//Make object of Google API Client for call Google API
$google_client = new Google_Client();

//Set the OAuth 2.0 Client ID
$google_client->setClientId('client id from google developer site');

//Set the OAuth 2.0 Client Secret key
$google_client->setClientSecret('its password');

//Set the OAuth 2.0 Redirect URI
$google_client->setRedirectUri('http://localhost/consultancy/google%20-signup/index.php');

//
$google_client->addScope('email');

$google_client->addScope('profile');

//start session on web page
session_start();

?>