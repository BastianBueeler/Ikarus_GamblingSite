<?php

    session_start();

    //Die Session ID wird neu gesetzt
    session_regenerate_id(true);

    //Wird überprüft, ob User eingeloggt ist
    if(isset($_SESSION['logedin'])){
        
        //Hinzufügen der Datenbank
        include("../dbconnector.inc.php");

        $username = $_SESSION['username'];
        $error = "";

        //Abfrage der Ikaruscoins und der Statistikdaten
        $stmt = $mysqli->prepare("SELECT person.IkarusCoins, personstatistic.CountedRouletteGames, 
                                 personstatistic.RouletteWins, personstatistic.MoneyWonRoulette,
                                 personstatistic.MoneySpentRoulette FROM person 
                                 INNER JOIN personstatistic ON person.fk_statistic=personstatistic.ID 
                                 WHERE username = ?");
        $stmt->bind_param("s", $username);

        $stmt->execute();

        $result = $stmt->get_result();

        if($stmt->affected_rows !== 0){
            
            while($row = $result->fetch_assoc()){
                $IkarusCoins = $row['IkarusCoins'];
                $countedRouletteGames = $row['CountedRouletteGames'];
                $rouletteWins = $row['RouletteWins'];
                $moneyWonRoulette = $row['MoneyWonRoulette'];
                $moneySpentRoulette = $row['MoneySpentRoulette'];
            }

        }else{
            echo "Keine Daten";
        }

        $_SESSION['IkarusCoins'] = $IkarusCoins;

        //Wenn POST nicht leer ist, führ das folgende aus
        if (!empty($_POST)) {

            //Setze die Farbe
            if(isset($_POST['color'])){
                 $color = $_POST['color'];
            } else {
                $color = NULL;
            }

            //Setze die definierte Zahl
            if(isset($_POST['definedNumber'])){
                $definedNumber = htmlspecialchars(trim($_POST['definedNumber']));
                
                if($definedNumber < 1 || $definedNumber > 20){
                    $definedNumber = NULL;
                }
            } else {
               $definedNumber = NULL;
            }

            //Setze den gesetzten Betrag
            if(isset($_POST['setAmountField']) && !empty(trim($_POST['setAmountField']))){
                $setAmountField = htmlspecialchars(trim($_POST['setAmountField']));

            } else {
                $setAmountField = NULL;
            }

            //Wenn Die Ikaruscoins kleines sind, als der Eingegebene Betrag gib echo aus, sonst führ den Rest aus.
            if ($IkarusCoins < $setAmountField){
                if($IkarusCoins == 0){
                    include("../setNewCoins.php");
                    $error = "Zu wenig Coins!";
                } else {
                    $error = "Es wurden mehr Coins gesetzt, als noch vorhanden sind!";
                }
            } elseif ($setAmountField < 0){
                $error = "Es können keine negativen Einsätze getätigt werden!";
            } else {

                $temporaryResult = $IkarusCoins - $setAmountField;

                //Wenn im Cookie winingNumber existiert, fahr weiter
                if(isset($_COOKIE["winningNumber"])){
                    if($_COOKIE["winningNumber"] != NULL){

                        $resultWheelNumber = $_COOKIE["winningNumber"];
                        $winningAmount = 0;

                        //Wenn man Farbe ausgewählt hat, Werte die Daten aus.
                        if ($color !== null && $definedNumber == null && $setAmountField !== null){

                            if($resultWheelNumber == 0){
                                $resultColor ="Grün";
                            } elseif ($resultWheelNumber % 2 == 0){
                                $resultColor ="Rot";
                            } elseif ($resultWheelNumber % 2 != 0){
                                $resultColor ="Schwarz";
                            }

                            if($resultColor == "Grün" && $color == "green"){
                                $winningAmount = $setAmountField * 14;
                                $winningText = "gewonnen!";
                                $rouletteWins += 1;
                                $moneyWonRoulette += $winningAmount;
                                
                            } elseif($resultColor == "Rot" && $color == "red"){
                                $winningAmount = $setAmountField * 2;
                                $winningText = "gewonnen!";
                                $rouletteWins += 1;
                                $moneyWonRoulette += $winningAmount;

                            } elseif($resultColor == "Schwarz" && $color == "black"){
                                $winningAmount = $setAmountField * 2;
                                $winningText = "gewonnen!";
                                $rouletteWins += 1;
                                $moneyWonRoulette += $winningAmount;

                            } else {
                                $winningAmount = 0;
                                $winningText = "Leider verloren";
                            }
                            
                        //Wenn man eine definierte Zahl ausgewählt hat, Werte die Daten aus
                        } elseif ($color == null && $definedNumber !== null && $setAmountField !== null){

                            if($resultWheelNumber == $definedNumber){
                                $winningAmount = $setAmountField * 14;
                                $winningText = "gewonnen!";
                                $rouletteWins += 1;
                                $moneyWonRoulette += $winningAmount;

                            } else {
                                $winningAmount = 0;
                                $winningText = "Leider verloren";
                            }
                            
                        } else {
                            if($IkarusCoins == 0){
                                include("../setNewCoins.php");
                            }
                            $error = "Es kann nur entweder eine Farbe oder eine Zahl zwischen 0 und 20 ausgewählt werden. Zudem muss ein Betrag gesetzt werden!";
                        }

                        if(empty($error)){
                            $countedRouletteGames += 1;
                            $moneySpentRoulette += $setAmountField;

                            $resultCoins = $temporaryResult + $winningAmount;

                            //Update die Datenbank mit den neuen Werten beider Tabellen
                            $stmt = $mysqli->prepare("UPDATE person INNER JOIN personstatistic p ON person.fk_statistic = p.ID
                                                    SET person.IkarusCoins = ?, p.CountedRouletteGames = ?, 
                                                    p.RouletteWins = ?, p.MoneyWonRoulette = ?,
                                                    p.MoneySpentRoulette = ? WHERE username = ?");
                            $stmt->bind_param("iiiiis", $resultCoins, $countedRouletteGames, $rouletteWins, $moneyWonRoulette, $moneySpentRoulette, $username);
                            $stmt->execute();
                            
                            $stmt->close();
                            $mysqli->close();
                            $IkarusCoins = $resultCoins;
                        }

                        setcookie("winningNumber", NULL);
                    }
                }
            }
        } 
    } else {
        header("Location: ../userlogin.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../main.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css">
    <script src='winwheelLibrary/Winwheel.js'></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js"></script>
    <title>Roulette</title>
  </head>
  <body>
        <div class="bg-dark d-flex align-items-center justify-content-between header">
            <p class="m-4 text-white font-weight-bold">IKARUS</p>
            <a href="../home.php"><button type="submit" class="btn btn-secondary mr-4">Zurück</button></a>
        </div>

        <div class="rouletteContent">
            
            <div class="rouletteplayField d-flex flex-column">

                <p class="text-black resultText"> 
                    <?php 

                        //Falls es Fehler gibt, ausgabe der Fehler
                        if(!(empty($error))){
                            echo $error;
                        } else {
                            //Ausgabe des Ergebnis
                            if(isset($resultWheelNumber) && $color !== null){
                                echo "Ergebnis: " . $resultColor . '<br>';
                                echo"Sie haben " . $winningText;

                            } elseif(isset($resultWheelNumber) && $definedNumber !== null){
                                echo "Ergebnis: " . $resultWheelNumber . '<br>';
                                echo "Sie haben " . $winningText;
                                
                            } else {
                                echo "";
                            } 
                        }
                    
                    ?>
                </p> 
                <div class="rouletteWheelBody d-flex flex-column">
                    <canvas id='canvas'  width='880' height='300'>
                        Canvas not supported, use another browser.
                    </canvas>
                    <script>

                        //Erstellen des Roulettes anhand von js Script
                        let theWheel = new Winwheel({
                            'canvasId'    : 'canvas',
                            'numSegments' : 21,
                            'textAlignment'  : 'outer',
                            'innerRadius'   : 90,
                            'segments'    :
                            [
                                {'fillStyle' : 'green', 'text' : '0'},
                                {'fillStyle' : 'black', 'text' : '1'},
                                {'fillStyle' : 'red', 'text' : '2'},
                                {'fillStyle' : 'black', 'text' : '3'},
                                {'fillStyle' : 'red', 'text' : '4'},
                                {'fillStyle' : 'black', 'text' : '5'},
                                {'fillStyle' : 'red', 'text' : '6'},
                                {'fillStyle' : 'black', 'text' : '7'},
                                {'fillStyle' : 'red', 'text' : '8'},
                                {'fillStyle' : 'black', 'text' : '9'},
                                {'fillStyle' : 'red', 'text' : '10'},
                                {'fillStyle' : 'black', 'text' : '11'},
                                {'fillStyle' : 'red', 'text' : '12'},
                                {'fillStyle' : 'black', 'text' : '13'},
                                {'fillStyle' : 'red', 'text' : '14'},
                                {'fillStyle' : 'black', 'text' : '15'},
                                {'fillStyle' : 'red', 'text' : '16'},
                                {'fillStyle' : 'black', 'text' : '17'},
                                {'fillStyle' : 'red', 'text' : '18'},
                                {'fillStyle' : 'black', 'text' : '19'},
                                {'fillStyle' : 'red', 'text' : '20'}
                            ],
                            'lineWidth'   : 1,
                            'textOrientation' : 'curved',
                            'animation' :                   
                            {
                                'type'     : 'spinToStop',  
                                'duration' : 5,             
                                'spins'    : 8,
                                'callbackFinished' : 'alertPrize()',
                                'callbackAfter' : 'drawTriangle()'      
                            },
                            'pointerAngle' : 0,
                        });

                        theWheel.segments[1].textFillStyle = 'white';
                        theWheel.segments[2].textFillStyle = 'white';
                        theWheel.segments[3].textFillStyle = 'white';
                        theWheel.segments[4].textFillStyle = 'white';
                        theWheel.segments[5].textFillStyle = 'white';
                        theWheel.segments[6].textFillStyle = 'white';
                        theWheel.segments[7].textFillStyle = 'white';
                        theWheel.segments[8].textFillStyle = 'white';
                        theWheel.segments[9].textFillStyle = 'white';
                        theWheel.segments[10].textFillStyle = 'white';
                        theWheel.segments[11].textFillStyle = 'white';
                        theWheel.segments[12].textFillStyle = 'white';
                        theWheel.segments[13].textFillStyle = 'white';
                        theWheel.segments[14].textFillStyle = 'white';
                        theWheel.segments[15].textFillStyle = 'white';
                        theWheel.segments[16].textFillStyle = 'white';
                        theWheel.segments[17].textFillStyle = 'white';
                        theWheel.segments[18].textFillStyle = 'white';
                        theWheel.segments[19].textFillStyle = 'white';
                        theWheel.segments[20].textFillStyle = 'white';
                        theWheel.segments[21].textFillStyle = 'white';
                        theWheel.draw();
                        drawTriangle();                        

                        //Preis wird gesetzt und die eingegebenen Daten weitergesendet
                        function alertPrize(){
                            let winningSegment = theWheel.getIndicatedSegment();
                            var textWinningSegment = winningSegment.text;

                            document.cookie = "winningNumber=" + textWinningSegment;
                            document.getElementById("form").submit();
                        }

                        function drawTriangle(){
                            let ctx = theWheel.ctx;
                    
                            ctx.strokeStyle = 'navy';     
                            ctx.fillStyle   = 'yellow';     
                            ctx.lineWidth   = 2;
                            ctx.beginPath();              
                            ctx.moveTo(410, -10);           
                            ctx.lineTo(470, -10);           
                            ctx.lineTo(440, 11);
                            ctx.lineTo(411, -10);
                            ctx.stroke();                 
                            ctx.fill();                   
                        }
                    </script>    
                </div>   
            </div>

            <div class="rouletteplayMenu pl-5 pr-5 pt-5 bg-secondary lead">
                <form action="" method="post" id="form">
                    <p class="text-white">Deine Coins: <?php echo $IkarusCoins ?></p> 
                    <br/>
                    <p class="text-white mb-0">Dein Einsatz</p>
                    <input class="w-100" id="setAmountField" name="setAmountField" type="number" min="0" max="9999999999999999999" required>
                    <br/>

                    <p class="text-white mb-0">Setze auf Zahl</p>
                    <input class="w-100" id="definedNumber" name="definedNumber" type="number" min="0" max="20">
                    <br/>
                    <br/>
                        <p class="text-white mb-0">Wähle eine Farbe</p>
                        <input type="radio" id="color" name="color" value="red"> Rot<br>
                        <input type="radio" id="color" name="color" value="black"> Schwarz<br>
                        <input type="radio" id="color" name="color" value="green"> Grün<br>
                    <button type="submit" class="btn btn-dark mt-4 w-100" id="spinWheel">Jetzt spielen</button>
                </form>
            </div>
        </div>
    <script>

        //Beim anklicken des Buttons startet die Animation
        var spinWheelBtn = document.getElementById("spinWheel");
        spinWheelBtn.addEventListener("click", function(e) {
            e.preventDefault();
            theWheel.startAnimation();
        });
        
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>