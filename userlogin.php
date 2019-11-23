<?php

  include("dbconnector.inc.php");

  $username = $pattern = $error = $message =""; 

  if ($_SERVER['REQUEST_METHOD'] == "POST"){
    if(isset($_POST['username']) && !empty(trim($_POST['username'])) && strlen(trim($_POST['username'])) <= 30){
      $username = htmlspecialchars(trim($_POST['username']));
    } else {
      $error = "Die Eingabe des Benutzernamens ist nicht korrekt!! ";
    }
      
    $pattern = '/(?=^.{8,}$)((?=.*\d+)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/';

    if(isset($_POST['password']) && preg_match($pattern, $_POST['password']) && strlen(trim($_POST['password'])) <= 30){
      $password = htmlspecialchars(trim($_POST['password']));
    } else {
      $error = "Die Eingabe des Passwortes ist nicht korrekt!! ";
    }

    if(empty($error)){
      $stmt = $mysqli->prepare("SELECT Password FROM person WHERE username = ?");
      $stmt->bind_param("s", $username);

      $stmt->execute();

      $result = $stmt->get_result();

      if($stmt->affected_rows !== 0){
        while($row = $result->fetch_assoc()){
          if(password_verify($password, $row['Password'])){
            session_start();
            session_regenerate_id(true);
            $_SESSION['username'] = $username;
            $_SESSION['logedin'] = TRUE;
            
            header('Location: home.php');
          } else {
            $error .= "Passwort oder Usernamen falsch, versuche es erneuert";
          }    
        }
      }else{
          $error .= "Passwort oder Usernamen falsch, versuche es erneuert";
        }

        $stmt->close();
        $mysqli->close();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="main.css">
    <title>Login</title>
  </head>
  <body>
    <?php

      if(strlen($error)){
        echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
      }  

    ?>
    
    <h3 class="display-4 text-center mt-5 font-weight-bold">IKARUS GLÜCKSSPIELSEITE</h3>

    <div class="container text-center m-auto bg-white createUserWindow">
    <form action ="" method="post">
      <div class="p-3 mt-4 mb-4 createUserWindowTitle">
        <p>Login</p>
      </div>
        <div class="form-group">
          <label for="username">Benutzername:</label>
          <input type="text" class="form-control" name="username" id="username" placeholder="Geben Sie ihren Benutzername ein" max="30" required> 
        </div>
        <div class="form-group">
          <label for="password">Passwort:</label>
          <input type="password" class="form-control" name="password" id="password" placeholder="Gross- und Kleinbuchstaben, Zahlen, Sonderzeichen, min. 8 Zeichen, keine Umlaute" max="30" required> 
        </div>
        <button type="submit" class="btn mb-4 w-100 btn-outline-dark">Login</button>
      </form>
      <a href="createUser.php"><button type="submit" class="btn w-100 mb-4 btn-outline-dark">Noch kein Mitglied?</button></a>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>