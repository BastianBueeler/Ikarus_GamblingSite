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
                <a href="#" class="dropdown-item">Benutzer abmelden</a>
                <a href="#" class="dropdown-item">Benutzer löschen</a>
            </div>
        </div>
    </div>

    <div class="container bg-white content">

        <div class="row mb-4">
            <div class="col-12">
                <p class="text-center pt-4 lead">Sie besitzen momentan ... Ikarus coins</p>
            </div>
        </div>

        <div class="row mb-4 d-flex justify-content-between">
            
            <div class="boxBlackJack border border-dark col-5 ml-5 shadow" onclick="window.location='http://google.com';">
                <p class="lead text-center font-weight-bold mt-5 text-primary">Black Jack</p>
            </div>
            
            <div class="boxRoulette border border-dark col-5 mr-5 shadow" onclick="window.location='http://google.com';">
                <p class="lead text-center font-weight-bold mt-5 text-white">Roulette</p>
            </div>
        </div>

        <div class="row d-flex justify-content-start">
            <div class="boxStatistics border border-dark col-5 ml-5 mb-5 shadow" onclick="window.location='http://google.com';">
                <p class="lead text-center font-weight-bold mt-5">Statistiken</p>
            </div>
        </div>

    </div>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>