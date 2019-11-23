<?php

    session_start();
    session_regenerate_id(true);

    if(isset($_SESSION['logedin'])){
        
        include("dbconnector.inc.php");

        $username = $_SESSION['username'];
        
        $stmt = $mysqli->prepare("SELECT fk_statistic FROM person WHERE username = ?");
        $stmt->bind_param("s", $username);

        $stmt->execute();

        $result = $stmt->get_result();

        if($stmt->affected_rows !== 0){
            
            while($row = $result->fetch_assoc()){
                $fk_statistic = $row['fk_statistic'];
            }

        }else{
            echo "fail1";
        } 

        $stmt = $mysqli->prepare("DELETE FROM person WHERE username = ?");
        $stmt->bind_param("s", $username);

        $stmt->execute();

        if($stmt->affected_rows !== 0){
            echo "perfekt2";
        }else{
            echo "fail2";
        }

        $stmt = $mysqli->prepare("DELETE FROM personstatistic WHERE id = ?");
        $stmt->bind_param("s", $fk_statistic);

        $stmt->execute();

        if($stmt->affected_rows !== 0){
            echo "perfekt3";
        }else{
            echo "fail3";
        }

        $stmt->close();
        $mysqli->close();

        $_SESSION = array();
        session_destroy();

        header("Location: http://localhost/uebung/Ikarus_GamblingSite/userlogin.php");

    }else{
        header("Location: http://localhost/uebung/Ikarus_GamblingSite/userlogin.php");
    }    


?>