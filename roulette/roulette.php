<?php

    session_start();
    session_regenerate_id(true);

    if(isset($_SESSION['logedin'])){
        
        include("../dbconnector.inc.php");

        $username = $_SESSION['username'];

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

        $stmt->close();
        $mysqli->close();

        if(strcmp($username, "Admin")){
            $isAdmin = TRUE;
        }else{
            $isAdmin = FALSE;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../main.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css">
    <title>Roulette</title>
  </head>
  <body>
  <div class="bg-dark d-flex align-items-center justify-content-between header">
                <p class="m-4 text-white font-weight-bold">IKARUS</p>
                <a href="../home.php"><button type="submit" class="btn btn-secondary mr-4">Zurück</button></a>
        </div>

        <div class="rouletteContent">
            
            <div class="playField d-flex flex-column">
                
            </div>

            <div class="playMenu pl-5 pr-5 pt-5 bg-secondary lead">
                <p class="text-white">Sie besitzen</p>

                <p class="text-white mb-0">Definierte Zahl</p>
                <input class="w-100" id="definednumber" type="number" min="0" max="60">
                <br/>
                <button class="btn btn-dark mt-2 w-100" id="setIkarusCoins">setzen</button>
                <br/>

                <button class="btn btn-dark mt-4 w-100" id="takeCard">Karte ziehen</button>
                <br/>

                <button class="btn btn-dark mt-4 w-100" id="split">Split</button>
                <br/>

                <button class="btn btn-dark mt-4 w-100" id="doubleDown">Double Down</button>
                <br/>

            </div>

        </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>