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
    <link rel="stylesheet" type="text/css" href="roulette.css">
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

                    function alertPrize()
                    {
                        let winningSegment = theWheel.getIndicatedSegment();
                        alert("Deine Zahl: " + winningSegment.text + "!");
                    }
                
                    
                
                    function drawTriangle()
                    {
                        let ctx = theWheel.ctx;
                
                        ctx.strokeStyle = 'navy';     // Set line colour.
                        ctx.fillStyle   = 'yellow';     // Set fill colour.
                        ctx.lineWidth   = 2;
                        ctx.beginPath();              // Begin path.
                        ctx.moveTo(410, -10);           // Move to initial position.
                        ctx.lineTo(470, -10);           // Draw lines to make the shape.
                        ctx.lineTo(440, 11);
                        ctx.lineTo(411, -10);
                        ctx.stroke();                 // Complete the path by stroking (draw lines).
                        ctx.fill();                   // Then fill.
                    }
                    </script>    
                </div>
                <p class="text-black mb-0" id="setAmountText">Ihr Einsatz</p>
                <input id="setAmountField" type="number" min="0" max="999999999999999999999">
                <br/>

                
            </div>

            <div class="playMenu pl-5 pr-5 pt-5 bg-secondary lead">
                <p class="text-white">Sie besitzen</p>
                <br/>

                <p class="text-white mb-0">Setze auf Zahl</p>
                <input class="w-100" id="definednumber" type="number" min="0" max="60">
                <br/>
                <br/>
                <br/>

                <form class="text-white mb-0"action="">
                    <p>Wähle eine Farbe</p>
                    <input type="radio" name="color" value="red"> Rot<br>
                    <input type="radio" name="color" value="black"> Schwarz<br>
                    <input type="radio" name="color" value="green"> Grün<br>
                </form>
                <br/>

                <button onClick="theWheel.startAnimation();" class="btn btn-dark mt-4 w-100" id="spinWheel">Jetzt spielen</button>
            </div>

            

            

        </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>