<?php

    //Session ID wird neu gesetzt
    session_regenerate_id(true);

    //Wenn User eingeloggt ist, führ das weitere aus. Sonst geh zu home.php
    if(isset($_SESSION['logedin'])){
        $username = $_SESSION['username'];

        //Wenn der Username = Admin ist, mach weiter. Ansonsten geh zu home.php
        if($username == 'Admin'){

            //Einbindung der Datenbank
            include("dbconnector.inc.php");

            //Abfrage aller Daten, welche zum auswerten benötigt werden.
            $stmt = $mysqli->prepare("SELECT personstatistic.*, person.Username FROM personstatistic 
                                        INNER JOIN person on person.fk_statistic=personstatistic.ID");

            $stmt->execute();

            $result = $stmt->get_result();

            //Wenn Daten gefunden worden sind.
            if($stmt->affected_rows !== 0){
                
                while($row = $result->fetch_assoc()){
                    //Abfüllen der Daten
                    $usernameTable= $row['Username'];
                    $countedBlackJackGames = $row['CountedBlackJackGames'];
                    $countedRouletteGames = $row['CountedRouletteGames'];
                    $blackJackWins = $row['BlackJackWins'];
                    $rouletteWins = $row['RouletteWins'];
                    $moneyWonBlackJack = $row['MoneyWonBlackJack'];
                    $moneyWonRoulette = $row['MoneyWonRoulette'];
                    $moneySpentBlackJack = $row['MoneySpentBlackJack'];
                    $moneySpentRoulette = $row['MoneySpentRoulette'];

                    //Abgefüllte Daten in Array speichern
                    $usernameArray[] = $usernameTable;
                    $countedBlackJackGamesArray[] = $countedBlackJackGames;
                    $countedRouletteGamesArray[] = $countedRouletteGames;
                    $blackJackWinsArray[] = $blackJackWins;
                    $rouletteWinsArray[] = $rouletteWins;
                    $moneyWonBlackJackArray[] = $moneyWonBlackJack;
                    $moneyWonRouletteArray[] = $moneyWonRoulette;
                    $moneySpentBlackJackArray[] = $moneySpentBlackJack;
                    $moneySpentRouletteArray[] = $moneySpentRoulette;

                    $arrlength = count($usernameArray);
                }

            } else {
                $countedBlackJackGames = "Keine Daten gefunden";
                $countedRouletteGames = "Keine Daten gefunden";
                $blackJackWins = "Keine Daten gefunden";
                $rouletteWins = "Keine Daten gefunden";
                $moneyWonBlackJack = "Keine Daten gefunden";
                $moneyWonRoulette = "Keine Daten gefunden";
                $moneySpentBlackJack = "Keine Daten gefunden";
                $moneySpentRoulette = "Keine Daten gefunden";
            }
            $stmt->close();
            $mysqli->close();

        } else {
            header("Location: home.php");
        } 
    } else {
        header("Location: home.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="main.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css">
    <title>Statistik</title>
  </head>
  <body>
        <div class="bg-dark d-flex align-items-center justify-content-between header">
            <p class="m-4 text-white font-weight-bold">IKARUS</p>
            <a href="home.php"><button type="submit" class="btn btn-secondary mr-4">Zurück</button></a>
        </div>

        <div class="statistic">
            <a href="#countedBlackJackGames" class="btn btn-primary" data-toggle="collapse">gezählte BlackJack Spiele</a>
            <div class="collapse" id="countedBlackJackGames">
                <div class="card card-body">
                    <?php 
                    for($x = 0; $x < $arrlength; $x++) {
                        echo "User: " . $usernameArray[$x] . " --> gezählte BlackJack Spiele: " .+ $countedBlackJackGamesArray[$x];
                        echo "<br>";
                    }
                    ?>
                </div>
            </div>

            <a href="#countedRouletteGames" class="btn btn-primary" data-toggle="collapse">gezählte Roulette Spiele</a>
            <div class="collapse" id="countedRouletteGames">
                <div class="card card-body">
                <?php 
                    for($x = 0; $x < $arrlength; $x++) {
                        echo "User: " . $usernameArray[$x] . " --> gezählte Roulette Spiele: " .+ $countedRouletteGamesArray[$x];
                        echo "<br>";
                    }
                    ?>
                </div>
            </div>

            <a href="#blackJackWins" class="btn btn-primary" data-toggle="collapse">BlackJack gewinne</a>
            <div class="collapse" id="blackJackWins">
                <div class="card card-body">
                <?php 
                    for($x = 0; $x < $arrlength; $x++) {
                        echo "User: " . $usernameArray[$x] . " --> BlackJack gewinne: " .+ $blackJackWinsArray[$x];
                        echo "<br>";
                    }
                    ?>
                </div>
            </div>

            <a href="#rouletteWins" class="btn btn-primary" data-toggle="collapse">Roulette gewinne</a>
            <div class="collapse" id="rouletteWins">
                <div class="card card-body">
                <?php 
                    for($x = 0; $x < $arrlength; $x++) {
                        echo "User: " . $usernameArray[$x] . " --> Roulette gewinne: " .+ $rouletteWinsArray[$x];
                        echo "<br>";
                    }
                    ?>
                </div>
            </div>

            <a href="#moneyWonBlackJack" class="btn btn-primary" data-toggle="collapse">gewonnenes Geld BlackJack</a>
            <div class="collapse" id="moneyWonBlackJack">
                <div class="card card-body">
                <?php 
                    for($x = 0; $x < $arrlength; $x++) {
                        echo "User: " . $usernameArray[$x] . " --> gewonnenes Geld BlackJack: " .+ $moneyWonBlackJackArray[$x];
                        echo "<br>";
                    }
                    ?>
                </div>
            </div>

            <a href="#moneyWonRoulette" class="btn btn-primary" data-toggle="collapse">gewonnenes Geld Roulette</a>
            <div class="collapse" id="moneyWonRoulette">
                <div class="card card-body">
                <?php 
                    for($x = 0; $x < $arrlength; $x++) {
                        echo "User: " . $usernameArray[$x] . " --> gewonnenes Geld Roulette: " .+ $moneyWonRouletteArray[$x];
                        echo "<br>";
                    }
                    ?>
                </div>
            </div>

            <a href="#moneySpentBlackJack" class="btn btn-primary" data-toggle="collapse">ausgegebenes Geld BlackJack</a>
            <div class="collapse" id="moneySpentBlackJack">
                <div class="card card-body">
                <?php 
                    for($x = 0; $x < $arrlength; $x++) {
                        echo "User: " . $usernameArray[$x] . " --> ausgegebenes Geld BlackJack: " .+ $moneySpentBlackJackArray[$x];
                        echo "<br>";
                    }
                    ?>
                </div>
            </div>

            <a href="#moneySpentRoulette" class="btn btn-primary" data-toggle="collapse">ausgegebenes Geld Roulette</a>
            <div class="collapse" id="moneySpentRoulette">
                <div class="card card-body">
                <?php 
                    for($x = 0; $x < $arrlength; $x++) {
                        echo "User: " . $usernameArray[$x] . " --> ausgegebenes Geld Roulette: " .+ $moneySpentRouletteArray[$x];
                        echo "<br>";

                    }
                    ?>
                </div>
            </div>
        </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>