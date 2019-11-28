<?php

  include("dbconnector.inc.php");

  session_start();
  session_regenerate_id(true);

  $name = $prename = $username = $email = $pattern = $passwordAgain = $error = ""; 

  if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(isset($_POST['name']) && !empty(trim($_POST['name'])) && strlen(trim($_POST['name'])) <= 30) {
      $name = htmlspecialchars(trim($_POST['name']));
    } else {
      $error .= "Die Eingabe des Namens ist nicht korrekt!! ";
    }

    if(isset($_POST['prename']) && !empty(trim($_POST['prename'])) && strlen(trim($_POST['prename'])) <= 30){
      $prename = htmlspecialchars(trim($_POST['prename']));
    } else {
      $error .= "Die Eingabe des Vornamens ist nicht korrekt!! ";
    }

    if(isset($_POST['username']) && !empty(trim($_POST['username'])) && strlen(trim($_POST['username'])) <= 30){
      $username = htmlspecialchars(trim($_POST['username']));
    } else {
      $error .= "Die Eingabe des Benutzernamens ist nicht korrekt!! ";
    }

    if(isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && strlen(trim($_POST['email'])) <= 40) {
      $email = htmlspecialchars(trim($_POST['email']));
    } else {
      $error .= "Die Eingabe der E-Mail Adresse ist nicht korrekt!! "; 
    }
      
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
    }

    if(empty($error)){

      $sql = "SELECT username FROM person";
      $result = $mysqli->query($sql);

      if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            if(strcmp($row["username"], $username) === 0){
              $error .= "Diesen Benutzernamen gibt es bereits!! ";
            }
        }
      }

      if(empty($error)){

        $_SESSION['username'] = $username;
        $_SESSION['logedin'] = TRUE;

        $IkarusCoins = 50;

        $password = password_hash($password, PASSWORD_DEFAULT);
      

	if ($stmt = $mysqli->prepare("INSERT INTO person (Username, Password, EMail, Name, Prename, IkarusCoins) VALUES ( ?, ?, ?, ?, ?, ? )")) {
        $stmt->bind_param("sssssi", $username, $password, $email, $name, $prename, $IkarusCoins);

        $stmt->execute();

        $stmt->close();
        $mysqli->close();
	}	else {
    die("Errormessage: ". $mysqli->error);
}

        header("Location: home.php");
      }
    }
  }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="main.css">
    <title>Benuter erstellen</title>
  </head>
  <body>
    <?php

      if(strlen($error)){
        echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
      }  

    ?>
    
    <h3 class="display-4 text-center mt-5 font-weight-bold">IKARUS GLÜCKSSPIELSEITE</h3>

    <div class="container text-center m-auto bg-white createUserWindow">
    
      <div class="p-3 mt-4 mb-4 createUserWindowTitle">
        <p>Benuter erstellen</p>
      </div>

        <form class="text-left" action="" method="post">
        <div class="form-group">
          <label for="name">Name:</label>
          <input type="text" class="form-control" name="name" id="name" placeholder="Geben Sie ihren Namen ein" max="30" value="<?php echo $name ?>" required> 
        </div>
        <div class="form-group">
          <label for="prename">Vorame:</label>
          <input type="text" class="form-control" name="prename" id="prename" placeholder="Geben Sie ihren Voramen ein" max="30" value="<?php echo $prename ?>" required> 
        </div>
        <div class="form-group">
          <label for="username">Benutzername:</label>
          <input type="text" class="form-control" name="username" id="username" placeholder="Geben Sie ihren Benutzernamen ein" max="30" value="<?php echo $username ?>" required> 
        </div>
        <div class="form-group">
          <label for="email">E-Mail Adresse:</label>
          <input type="email" class="form-control" name="email" id="email" placeholder="Geben Sie ihre E-Mail Adresse ein" max="40" value="<?php echo $email ?>" required> 
        </div>
        <div class="form-group">
          <label for="password">Passwort:</label>
          <input type="password" class="form-control" name="password" id="password" placeholder="Gross- und Kleinbuchstaben, Zahlen, Sonderzeichen, min. 8 Zeichen, keine Umlaute" max="30" required> 
        </div>
        <div class="form-group">
          <label for="passwordAgain">Passwort:</label>
          <input type="password" class="form-control" name="passwordAgain" id="passwordAgain" placeholder="Gross- und Kleinbuchstaben, Zahlen, Sonderzeichen, min. 8 Zeichen, keine Umlaute" max="30" required> 
        </div>
        <button id="submit" type="submit" class="btn mb-4 w-100 btn-outline-dark">Submit</button>
      </form>
      <a href="http://localhost/uebung/Ikarus_GamblingSite/userlogin.php"><button type="submit" class="btn w-100 mb-4 btn-outline-dark">Zurück</button></a>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
