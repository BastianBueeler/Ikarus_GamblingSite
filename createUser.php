<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="main.css">
    <title>Benuter erstellen</title>
  </head>
  <body>
    
    <h3 class="display-4 text-center mt-5 font-weight-bold">IKARUS GLÜCKSSPIELSEITE</h3>

    <div class="container text-center m-auto bg-white createUserWindow">
    
      <div class="p-3 mt-4 mb-4 createUserWindowTitle">
        <p>Benuter erstellen</p>
      </div>

        <form class="text-left" action="" method="">
        <div class="form-group">
          <label for="name">Name:</label>
          <input type="text" class="form-control" id="name" placeholder="Geben Sie ihren Namen ein" max="30" required> 
        </div>
        <div class="form-group">
          <label for="prename">Vorame:</label>
          <input type="text" class="form-control" id="prename" placeholder="Geben Sie ihren Voramen ein" max="30" required> 
        </div>
        <div class="form-group">
          <label for="username">Benutzername:</label>
          <input type="text" class="form-control" id="username" placeholder="Geben Sie ihren Benutzername ein" max="30" required> 
        </div>
        <div class="form-group">
          <label for="email">E-Mail Adresse:</label>
          <input type="email" class="form-control" id="email" placeholder="Geben Sie ihren E-Mail Adresse ein" max="30" required> 
        </div>
        <div class="form-group">
          <label for="password">Passwort:</label>
          <input type="password" class="form-control" id="password" placeholder="Geben Sie ihren Passwort ein" max="30" required> 
        </div>
        <div class="form-group">
          <label for="passwordAgain">Passwort:</label>
          <input type="password" class="form-control" id="passwordAgain" placeholder="Geben Sie erneut ihren Passwort ein" max="30" required> 
        </div>
        <button type="submit" class="btn mb-4 w-100 btn-outline-dark">Submit</button>
      </form>
      <a href=""><button type="submit" class="btn w-100 mb-4 btn-outline-dark">Zurück</button></a>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>