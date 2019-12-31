<?php

    session_start();
    session_regenerate_id(true);

    if(isset($_SESSION['logedin'])){
        
        include("../dbconnector.inc.php");

        $username = $_SESSION['username'];

        include('../setNewCoins.php');

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

        $_SESSION['IkarusCoins'] = $IkarusCoins;

        $message = '';

        if (!empty($_POST)) {

            if(isset($_POST['color'])){
                 $color = $_POST['color'];
            } else{
                $color = NULL;
            }

            if(isset($_POST['definedNumber'])){
                $definedNumber = htmlspecialchars(trim($_POST['definedNumber']));
                
                if($definedNumber < 1 || $definedNumber > 20){
                    $definedNumber = NULL;
                }
            } else {
               $definedNumber = NULL;
            }

            if(isset($_POST['setAmountField']) && !empty(trim($_POST['setAmountField']))){
                $setAmountField = htmlspecialchars(trim($_POST['setAmountField']));

            } else {
                $error = "Keinen Einsatz vorhanden";
                $setAmountField = NULL;
            }

            if($IkarusCoins < $setAmountField){
               echo "Zu wenig Coins";
            } else {

                $stmt = $mysqli->prepare("UPDATE person SET IkarusCoins = ? WHERE username = ?");

                $temporaryResult = $IkarusCoins - $setAmountField;

                $stmt->bind_param("is", $temporaryResult, $username);
                $stmt->execute();

                print_r($_POST);
                //if(isset($_COOKIE["finishedAnimation"])){
                   // if($_COOKIE["finishedAnimation"] == 1){
                if(isset($_POST["winningNumber"])){
                    if($_POST["winningNumber"] != NULL){
                        setcookie("finishedAnimation", 0);
                        //$resultWheelNumber = $_COOKIE["winningNumber"];
                        $resultWheelNumber = $_POST["winningNumber"];
                        $winningAmount = 0;

                        if ($color !== null && $definedNumber == null && $setAmountField !== null){

                            if($resultWheelNumber == 0 && $color == "Grün"){
                                $winningAmount = $setAmountField * 14;
                                echo "Du hast grün getroffen!";
                                
                            } elseif($resultWheelNumber % 2 == 0 && $color == "Rot"){
                                $winningAmount = $setAmountField * 2;
                                echo "Du hast rot getroffen!";

                            } elseif($resultWheelNumber % 2 != 0 && $color == "Schwarz"){
                                $winningAmount = $setAmountField * 2;
                                echo "Du hast schwarz getroffen!";
                            } else {
                                $winningAmount = 0;
                                echo "Leider verloren";
                            }
                            
                            echo "farbe--->";
                            echo $resultWheelNumber;
                            
                        } elseif ($color == null && $definedNumber !== null && $setAmountField !== null){

                            if($resultWheelNumber == $definedNumber){
                                $winningAmount = $setAmountField * 14;
                                echo "Du hast die richtige Zahl getroffen!";
                            } else {
                                $winningAmount = 0;
                                echo "Leider verloren";
                            }
                            
                        } else {
                            echo "Sie haben entweder zu viel oder zu wenig Optionen ausgewählt oder etwas falsch eingegeben";
                        }
                        $resultCoins = $IkarusCoins + $winningAmount;
                        $stmt = $mysqli->prepare("UPDATE person SET IkarusCoins = ? WHERE username = ?");
                        $stmt->bind_param("is", $resultCoins, $username);
                        $stmt->execute();
                        
                        $stmt->close();
                        $mysqli->close();
                    }
                }
            }
        } 
        print_r($_POST);
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="roulette.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css">
    <script src='winwheelLibrary/Winwheel.js'></script>
    <script src="minAjaxLibrary/minify/index.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js"></script>
    <title>Roulette</title>
  </head>
  <body>
        <div class="bg-dark d-flex align-items-center justify-content-between header">
            <p class="m-4 text-white font-weight-bold">IKARUS</p>
            <a href="../home.php"><button type="submit" class="btn btn-secondary mr-4">Zurück</button></a>
        </div>

        <div class="rouletteContent">
            
            <div class="playField d-flex flex-column">
                <div class="rouletteWheelBody d-flex flex-column">
                    <canvas id='canvas'  width='880' height='300'>
                        Canvas not supported, use another browser.
                    </canvas>
                    <script>
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

                        function alertPrize(){
                            let winningSegment = theWheel.getIndicatedSegment();
                            var textWinningSegment = winningSegment.text;

                            document.cookie = "winningNumber=" + textWinningSegment;
                            //document.cookie = "finishedAnimation=" + 1;
                            //header("Location:.");
                            //location.href = 'roulette.php';
                            
                            //var request = new XMLHttpRequest();
                            //var url = 'roulette.php';
                            //request.open('POST', url, true);
                            //request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                            //var params = 'winningNumber=' + textWinningSegment;
                            //request.send(params);
                            
                            //$.ajax({
                            //type: "POST",
                            //url: 'roulette.php',
                            //data: textWinningSegment
                            //});

                            
                            //minAjax({
                            //    url:"roulette.php",
                             //   type:"POST",//Request type GET/POST
                                //Send Data in form of GET/POST
                             //   data:{
                             //   "winningNumber": textWinningSegment
                             //   }
                            //});

                            //xhttp.open("POST", "roulette.php", textWinningSegment);
                            //xhttp.send();
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
                            //das isch wie dis; funktioniert das nit? nei; s'problem isch: du wilsch uf das element zuegriffe befor es erstellt worde ist, bi mir nit sicher ob das funktioniert
                    </script>    
                </div>        
            </div>

            <div class="playMenu pl-5 pr-5 pt-5 bg-secondary lead">
                <form action="" method="post">
                    <p class="text-white">Ihre Coins: <?php echo $IkarusCoins ?></p> 
                    <br/>
                    <p class="text-white mb-0">Ihr Einsatz</p>
                    <input class="w-100" id="setAmountField" name="setAmountField" type="number" min="0" max="9999999999999999999">
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
        var spinWheelBtn = document.getElementById("spinWheel");
        spinWheelBtn.addEventListener("click", function() {theWheel.startAnimation();});
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>