<?php

    session_start();
    //Session ID wird neu gesetzt
    session_regenerate_id(true);

    //Wenn User eingeloggt ist.
    if(isset($_SESSION['logedin'])){
        //falls user keine ikaruscoins mehr hat, werden ihm 50 pro tag gegeben
        include("../setNewCoins.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="../main.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css">
        <title>Black Jack</title>
    </head>
    <body>
        <div class="bg-dark d-flex align-items-center justify-content-between header">
                <p class="m-4 text-white font-weight-bold">IKARUS</p>
                <a href="../home.php"><button type="submit" class="btn btn-secondary mr-4">Zurück</button></a>
        </div>

        <div class="d-flex align-items-center justify-content-center" id="blackJackContent">
                     
            <div class="playField d-flex flex-column">
                <div class="d-flex justify-content-center bg-danger" id="dealerCards">
                    
                </div>

                <div id="cardDeck" class="bg-success">

                </div>

                <div class="d-flex justify-content-center bg-warning" id="myCards">

                </div>
            </div>

            <div class="playMenu pl-5 pr-5 pt-5 bg-secondary lead">
                <p class="text-white" id="fortune"></p>

                <p class="text-white" id="bet"></p>

                <p class="text-white mb-0">Sie setzen:</p>
                <input class="w-100" id="amountIkarusCoins" type="number" min="0"></input>
                <br/>
                <button class="btn btn-dark mt-2 w-100" id="setIkarusCoins">setzen</button>
                <br/>

                <button class="btn btn-dark mt-4 w-100" id="takeCard">Karte ziehen</button>
                <button class="btn btn-dark mt-2 w-100" id="takeNoCards">Keine Karten mehr ziehen</button>
                <br/>
            </div>

        </div>

        <script src="ajaxCalls.js"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>
</html>