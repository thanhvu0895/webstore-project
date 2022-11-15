<?php
session_start();
require_once("connection.php");


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/about-us.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous" />
  <title>About us</title>
</head>

<body>

  <?php include("partials/header.php") ?>
  <div class="cart-container">
    <div class="product-container">
      <div class='cart-item'>
        <img src="../images/Duure.png" alt="dom" height=200 width=200>
        <div class='item-details'>
          <h2>Duure</h2>
          <p class="title">Front-End and UI/UX Lead&nbsp;&nbsp;&nbsp;</p>
          <p>Year: Senior</p>
          <p>Major: CS & Econ</p>
        </div>
      </div>
      <div class='cart-item'>
        <img src="../images/Thanh.png" alt="dom" height=200 width=200>
        <div class='item-details'>
          <h2>Thanh</h2>
          <p class="title">Back-End and Database Lead&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
          <p>Year: Senior</p>
          <p>Major: CS</p>
        </div>
      </div>
      <div class='cart-item'>
        <img src="../images/William.png" alt="dom" height=200 width=200>
        <div class='item-details'>
          <h2>William</h2>
          <p class="title">Back-End Dev</p>
          <p>Year: Senior</p>
          <p>Major: CS</p>
        </div>
      </div>
      <div class='cart-item'>
        <img src="../images/Caroline.jpg" alt="dom" height=200 width=200>
        <div class='item-details'>
          <h2>Caroline</h2>
          <p class="title">Front-End Dev</p>
          <p>Year: Senior</p>
          <p>Major: CS</p>
        </div>
      </div>
      <div class='cart-item'>
        <img src="../images/Sophie.png" alt="dom" height=200 width=200>
        <div class='item-details'>
          <h2>Sophie</h2>
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