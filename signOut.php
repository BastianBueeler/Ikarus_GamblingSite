<?php

    session_start();
    session_regenerate_id(true);

    if(isset($_SESSION['logedin'])){
        
        //session leeren und löschen

        $_SESSION = array();
        session_destroy();
        
        //user zurück auf login seite führen
        header("Location: http://localhost/Ikarus_GamblingSite/userlogin.php");

    }else{
        header("Location: http://localhost/Ikarus_GamblingSite/userlogin.php");
    }

?>