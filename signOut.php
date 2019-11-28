<?php

    session_start();
    session_regenerate_id(true);

    if(isset($_SESSION['logedin'])){

        $_SESSION = array();
        session_destroy();

        header("Location: userlogin.php");

    }else{
        header("Location: userlogin.php");
    }

?>
