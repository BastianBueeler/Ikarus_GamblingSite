<?php
    
    session_regenerate_id(true);

    //Wird überprüft, ob User eingeloggt ist
    if(isset($_SESSION['logedin'])){

        //DB Verbindung wird hergestellt
        include("../dbconnector.inc.php");

        $username = $_SESSION['username'];

        //Eine Abfrae der Coins des Users wird auf der Datenbank gemacht.
        $stmt = $mysqli->prepare("SELECT IkarusCoins FROM person WHERE username = ?");
        $stmt->bind_param("s", $username);

        $stmt->execute();

        $result = $stmt->get_result();

        if($stmt->affected_rows !== 0){
            
            while($row = $result->fetch_assoc()){
                $IkarusCoins = $row['IkarusCoins'];
            }

        }else{
            echo "fail";
        }

        //Wenn die Ikaruscoins 0 sind, setz den Wert wieder auf 50, update die Datenbank und gib eine Meldung aus.
        if($IkarusCoins == 0){
            $newCoinValue = 50;
            $stmt = $mysqli->prepare("UPDATE person SET IkarusCoins = ? WHERE username = ?");
            $stmt->bind_param("is", $newCoinValue, $username);
            $stmt->execute();

            echo '<script language="javascript">';
            echo 'alert("Du bekommst 50 neue Coins, da du keine mehr hast.")';
            echo '</script>';
        }

    }
?>