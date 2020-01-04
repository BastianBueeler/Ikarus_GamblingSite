<?php

    session_start();
    session_regenerate_id(true);

    $username = $password = $passwordAgain = $error = $success = ""; 

    if(isset($_SESSION['logedin'])){

        if($_SERVER['REQUEST_METHOD'] == "POST"){
            
            $pattern = '/(?=^.{8,}$)((?=.*\d+)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/';

            if(isset($_POST['password']) && preg_match($pattern, $_POST['password']) && strlen(trim($_POST['password'])) <= 30){
              $password = htmlspecialchars(trim($_POST['password']));
            } else {
              $error .= "Die Eingabe des Passwortes ist nicht korrekt!! ";
            }
        
            if(isset($_POST['passwordAgain']) && preg_match($pattern, $_POST['passwordAgain']) && strlen(trim($_POST['passwordAgain'])) <= 30){
              $passwordAgain = htmlspecialchars(trim($_POST['passwordAgain']));
            } else {
              $error .= "Die zweite Eingabe des Passwortes ist nicht korrekt!! ";
            }
        
            if(strcmp($password, $passwordAgain) !== 0){
              $error .= "Die zwei Passwörter sind nicht gleich!! ";
            }else{
                include("dbconnector.inc.php");

                $password = password_hash($password, PASSWORD_DEFAULT);
                $username = $_SESSION['username'];

                $stmt = $mysqli->prepare("UPDATE person SET Password = ? WHERE username = ?");

                $stmt->bind_param("ss", $password, $username);

                if($stmt->execute()){
                    $success = "Das Passwort wurde erfolgreich geändert";
                }else{
                    $error .= "Etwas ist schief gelaufen";
                }
            }
        }
        
    }else{
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
        <title>Black Jack</title>
    </head>
    <body>
        <div class="bg-dark d-flex align-items-center justify-content-between header">
                <p class="m-4 text-white font-weight-bold">IKARUS</p>
                <a href="http://localhost/Ikarus_GamblingSite/home.php"><button type="submit" class="btn btn-secondary mr-4">Zurück</button></a>
        </div>

        <?php
            if(strlen($error)){
                echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
            }elseif(strlen($success)){

                $stmt = $mysqli->prepare("SELECT * FROM person WHERE username = ?");
                echo $username;
                $abc = strval($username);
                $stmt->bind_param("s", $abc);

                echo $stmt->fullQuery();

                $result = $stmt->get_result();

                if($stmt->affected_rows !== 0){
                    
                    while($row = $result->fetch_assoc()){
                        if(password_verify($passwordAgain, $row['Password'])){
                            echo "hash funktioniert";
                        }else{
                            echo "hash fail";
                        }
                    }
        
                }else{
                    echo "fail";
                }

                echo "<div class=\"alert alert-success\" role=\"alert\">" . $success . "</div>";
            }  
        ?>

        <div class="container text-center ml-auto mr-auto mt-5 bg-white changePasswordWindow">
        
            <div class="p-3 mt-4 mb-4 changePasswordWindowTitle">
                <p>Passwort ändern</p>
            </div>

            <form class="text-left" action="" method="post">

                <div class="form-group">
                    <label for="password">Passwort:</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Gross- und Kleinbuchstaben, Zahlen, Sonderzeichen, min. 8 Zeichen, keine Umlaute" max="30" required> 
                </div>

                <div class="form-group">
                    <label for="passwordAgain">Passwort bestätigen:</label>
                    <input type="password" class="form-control" name="passwordAgain" id="passwordAgain" placeholder="Gross- und Kleinbuchstaben, Zahlen, Sonderzeichen, min. 8 Zeichen, keine Umlaute" max="30" required> 
                </div>

                <button id="submit" type="submit" class="btn mb-4 w-100 btn-outline-dark">Submit</button>
            </form>

        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>
</html>