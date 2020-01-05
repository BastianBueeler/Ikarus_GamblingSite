<?php

    session_start();
    session_regenerate_id(true);

    if(isset($_SESSION['logedin'])){
        
        //überprüfen ob newsletter abonniert ist oder nicht und status ändern
        if($_SESSION['AboNewsLetter']){

            $newStatus = false;

        }else{

            $newStatus = true;

        }

        include("dbconnector.inc.php");

        //der neue status zum abo des newsletter in db schreiben
        $stmt = $mysqli->prepare("UPDATE person SET AboNewsLetter = ? WHERE username = ?");

        $username = $_SESSION['username'];

        $stmt->bind_param("is", $newStatus, $username);

        if($stmt->execute()){
            $feedback = "erfolg";
        }else{
            $feedback = "fehlgeschlagen";
        }

        //user zurück auf home seite führen mit rückmeldung
        header("Location: home.php?NewsLetterfeedback=$feedback&NewsLetterstatus=$newStatus");
    }else{
        header("Location: userlogin.php");
    }

?>