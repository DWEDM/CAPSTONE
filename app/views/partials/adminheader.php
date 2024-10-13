<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>
      <?= APP_NAME ?>
  </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <!-- Make sure Bootstrap JS is loaded properly -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
      integrity="sha384-ho+yBZv55R+bcPUEZB1xDoerPBryC/oODspc0RhKnmc/uyR6W9I/jPz1JwCv29K6"
      crossorigin="anonymous"></script>

  <style>
    .navbar {
        background-color: #F6F2F1;
    }
    .logo-nav img {
        margin-left: 3%;
        width: 4%;
        height: auto;
    }
  </style>
</head>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="">
      <img src="../assets/icon.png" alt="" style="height: auto; width: 60px;">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" 
        aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">                
        <li class="nav-item">
          <a class="nav-link" href="<?= SERVER ?>/dashboard">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= SERVER ?>/users">Users</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" 
              data-bs-toggle="dropdown" aria-expanded="false">
            Posts
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <li><a class="dropdown-item" href="<?= SERVER ?>/articles">Articles</a></li>
            <li><a class="dropdown-item" href="<?= SERVER ?>/cats">Cats</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= SERVER ?>/profile">Profile</a>
        </li>
        <form class="d-flex">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item float-right">
              <a href="<?= SERVER ?>/logout" class="nav-link">Log Out</a>
            </li>
          </ul>
        </form>
      </ul>
    </div>
  </div>
</nav>
