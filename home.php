<?php

    session_start();
    //Session ID wird neu gesetzt
    session_regenerate_id(true);

    //Wenn User eingeloggt ist.
    if(isset($_SESSION['logedin'])){

        //überprüfen ob der status des Newsletter-abo geändert wurde
        if (isset($_GET['NewsLetterfeedback'])){
            if($_GET['NewsLetterfeedback'] == 'erfolg'){
                if($_GET['NewsLetterstatus']){
                    $message = 'Sie haben den Newsletter erfolgreich abonniert';
                }else{
                    $message = 'Sie haben den Newsletter erfolgreich deabonniert';
                }
                
                //rückmeldung an den user bezüglich dem ändern des NewsLetter-abo
                echo '<script>';
                echo 'alert("' . $message . '")';
                echo '</script>';
            }elseif($_GET['NewsLetterfeedback'] == 'fehlgeschlagen'){
                if($_GET['NewsLetterstatus']){
                    $message = 'Etwas ist fehlgeschalgen beim abonnieren des Newsletter';
                }else{
                    $message = 'Etwas ist fehlgeschlagen beim deabonieren des Newsletter';
                }
                
                //rückmeldung an den user bezüglich dem ändern des NewsLetter-abo
                echo '<script>';
                echo 'alert("' . $message . '")';
                echo '</script>';
            }
        }
        
        include("dbconnector.inc.php");
        include('setNewCoins.php');

        $username = $_SESSION['username'];

        //Datenbankabfrage der und anschliessendes setzen der Ikaruscoins und dem status des Newsletter-abo
        $stmt = $mysqli->prepare("SELECT IkarusCoins, AboNewsLetter FROM person WHERE username = ?");
        $stmt->bind_param("s", $username);

        $stmt->execute();

        $result = $stmt->get_result();

        if($stmt->affected_rows !== 0){
            
            while($row = $result->fetch_assoc()){
                $IkarusCoins = $row['IkarusCoins'];
                $_SESSION['AboNewsLetter'] = $row['AboNewsLetter'];
            }

        }else{
            echo "fail";
        }

        $stmt->close();
        $mysqli->close();

        //Setzt isAdmin auf True, wenn Username = Admin
        if(strcmp($username, "Admin")){
            $isAdmin = TRUE;
        }else{
            $isAdmin = FALSE;
        }

    } else {
        header("Location: userlogin.php");
    }


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="main.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css">
    <title>Benuter erstellen</title>
  </head>
  <body>

    <div class="bg-dark d-flex align-items-center justify-content-between header">
        <p class="m-4 text-white font-weight-bold">IKARUS</p>
        <div class="dropdown dropleft m-4">
            <a href="#" class="btn btn-secondary dropdown-toggle" id="dropdownMenu" data-toggle="dropdown" role="button"><i class="far fa-user"></i></a>

            <div class="dropdown-menu">
                <a href="signOut.php" target="_self" class="dropdown-item">Benutzer abmelden</a>
                <a href="changePassword.php" target="_self" class="dropdown-item">Passwort ändern</a>
                <?php
                    //Wenn der Username Admin ist, dann darf er sich löschen können und wird nicht nach einem newsletter-abo gefragt.
                    if($isAdmin == 1){
                        echo "<a href='deleteUser.php' target='_self' class='dropdown-item'>Benutzer löschen</a>";
                        //überprüfen ob newsletter abonniert ist
                        if($_SESSION['AboNewsLetter']){
                            echo "<a href='changeNewsLetterStatus.php' target='_self' class='dropdown-item'>Newsletter deabonnieren</a>";
                        }else{
                            echo "<a href='changeNewsLetterStatus.php' target='_self' class='dropdown-item'>Newsletter abonnieren</a>";
                        }
                    }
                ?>
            </div>
        </div>
    </div>

    <div class="container bg-white content pb-4">

        <div class="row mb-4">
            <div class="col-12">
                <p class="text-center pt-4 lead">Sie besitzen momentan <?php echo $IkarusCoins ?> Ikarus coins</p>
            </div>
        </div>

        <div class="row mb-4 d-flex justify-content-between">
            
            <div class="boxBlackJack border border-dark col-5 ml-5 shadow rounded" onclick="window.location='BlackJack/BalckJackFrontend.php';">
                <p class="lead text-center font-weight-bold mt-5 text-primary">Black Jack</p>
            </div>
            
            <div class="boxRoulette border border-dark col-5 mr-5 shadow rounded" onclick="window.location='roulette/roulette.php';">
                <p class="lead text-center font-weight-bold mt-5 text-white">Roulette</p>
            </div>
        </div>
        
        <?php

            //Wenn der Username Admin ist, zeig auf Home ein weiters Feld an für die Statistik Seite.
            if($isAdmin == 0){
                echo "<div class='row d-flex justify-content-start'>";
                echo "<div class='boxStatistics border border-dark col-5 ml-5 mb-5 shadow rounded' onclick=\"window.location='statistic.php';\">";
                echo "<p class='lead text-center font-weight-bold mt-5'>Statistiken</p>";
                echo "</div>";
                echo "</div>";
            } else {

            }
        ?>

    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>