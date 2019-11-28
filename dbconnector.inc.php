<?php

$host = array_key_exists('DB_HOST', $_ENV) ? $_ENV['DB_HOST'] : 'localhost';
$username = array_key_exists('DB_USERNAME', $_ENV) ? $_ENV['DB_USERNAME'] : 'IkarusDBUser';
$password = array_key_exists('DB_PASSWORD', $_ENV) ? $_ENV['DB_PASSWORD'] : 'IkarusPasswort'; 
$database = array_key_exists('DB_DATABASE', $_ENV) ? $_ENV['DB_DATABASE'] : 'ikarusgamblingsite';

// mit Datenbank verbinden
$mysqli = new mysqli($host, $username, $password, $database);
// fehlermeldung, falls verbindung fehl schlägt.

if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

?>
