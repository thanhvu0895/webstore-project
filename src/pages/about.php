<?php
session_start();
if (!isset($_SESSION["email"])) {
  header("Location: signin.php");
}

?>

<!DOCTYPE html>
<html>

<head>

  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="../css/about.css">
</head>

<body>
  <div>
    <?php include("partials/header.php") ?>
    <div style="height: 70vh; display: flex; width: 100vw; justify-content:center; align-content: center;">
      <div class="column">
        <div class="card">
          <div class="container">
            <div style="height:120px; display:flex; justify-content:center; align-items:center">
              <h2>Duure</h2>
            </div>
            <p class="title">Front-End Lead & Back-End Dev</p>
            <p>Year: Senior</p>
            <p>Major: CS & Econ</p>
          </div>
        </div>
      </div>

      <div class="column">
        <div class="card">
          <div class="container">
            <div style="height:120px; display:flex; justify-content:center; align-items:center">
              <h2>Thanh N. Vu</h2>
            </div>
            <p class="title">Back-End and Database Lead</p>
            <p>Year: Senior</p>
            <p>Major: CS</p>
          </div>
        </div>
      </div>

      <div class="column">
        <div class="card">
          <div class="container">
            <div style="height:120px; display:flex; justify-content:center; align-items:center">
              <h2>William</h2>
            </div>
            <p class="title">Back-End Dev</p>
            <p>Year: Senior</p>
            <p>Major: CS</p>
          </div>
        </div>
      </div>

      <div class="column">
        <div class="card">
          <div class="container">
            <div style="height:120px; display:flex; justify-content:center; align-items:center">
              <h2>Caroline</h2>
            </div>
            <p class="title">Front-End Dev</p>
            <p>Year: Senior</p>
            <p>Major: CS</p>
          </div>
        </div>
      </div>

      <div class="column">
        <div class="card">
          <div class="container">
            <div style="height:120px; display:flex; justify-content:center; align-items:center">
              <h2>Sophie</h2>
            </div>
            <p class="title">UI/UX Lead</p>
            <p>Year: Sophomore</p>
            <p>Major: CS</p>
          </div>
        </div>
      </div>
    </div>
    <?php include("partials/footer.php") ?>
</body>

</html>