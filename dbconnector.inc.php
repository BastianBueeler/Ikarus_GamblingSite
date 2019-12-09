<?php

$host = 'localhost';
$uname = 'IkarusDBUser';
$password = 'IkarusPasswort'; 
$database = 'ikarusgamblingsite';

// mit Datenbank verbinden
$mysqli = new mysqli($host, $uname, $password, $database);
// fehlermeldung, falls verbindung fehl schlägt.

if ($mysqli->connect_error) {
 die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
}

?>