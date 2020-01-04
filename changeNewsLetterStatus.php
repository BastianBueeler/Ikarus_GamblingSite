<?php

    session_start();
    session_regenerate_id(true);

    if(isset($_SESSION['logedin'])){

        if($_SESSION['AboNewsLetter']){

            $newStatus = false;

        }else{

            $newStatus = true;

        }

        include("dbconnector.inc.php");

        $stmt = $mysqli->prepare("UPDATE person SET AboNewsLetter = ? WHERE username = ?");

        $username = $_SESSION['username'];

        $stmt->bind_param("is", $newStatus, $username);

        if($stmt->execute()){
            $feedback = "erfolg";
        }else{
            $feedback = "fehlgeschlagen";
        }

        header("Location: home.php?feedback=$feedback&status=$newStatus");
    }else{
        header("Location: userlogin.php");
    }

?>