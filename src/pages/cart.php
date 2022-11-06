<?php
    session_start();
    require_once("connection.php");

    if (!isset($_SESSION["email"])) {
    header("Location: signin.php");
    }

    /**
    * IMPLEMENT REMOVING FROM Cart
    * revised by: Thanh Vu 11/03/2022 - add this function 
    */
    $userId = $_SESSION["userid"];

    try {
        if (!empty($_GET['productRemoveId'])){
            $productRemoveId = $_GET['productRemoveId'] ?? '0';
            $conn->beginTransaction(); 
            $sql = ("DELETE FROM Cart where product_id = ?");
            $statement = $conn->prepare($sql);
            $statement->bindValue(1, $productRemoveId);
            $statement->execute();
            $conn->commit(); 
    }
    } catch(PDOException $e) {
        header("Location: error.php?error=Connection failed:" . $e->getMessage());
    }

    /**
     * IMPLEMENT PURCHASING ITEM FROM CART:
     */

    try {
      if (!empty($_GET['productPurchaseId'])){
          $productPurchaseId = $_GET['productPurchaseId'] ?? '0';
          
          // STEP 1: Add to order detail:
          $conn->beginTransaction(); 
          $sql = ("DELETE FROM Cart where product_id = ?");
          $statement = $conn->prepare($sql);
          $statement->bindValue(1, $productPurchaseId);
          $statement->execute();
          $conn->commit(); 
          
          // STEP 2: Deduce the amount of webcoin 


  }
  } catch(PDOException $e) {
      header("Location: error.php?error=Connection failed:" . $e->getMessage());
  }


    /**
    * IMPLEMENT SHOWING PRODUCTS ON CART
    * Sophie Decker and Thanh Vu
    * revised by: Thanh Vu 11/03/2022 - restructuring DB query 
    */


    try {
        $stmt = $conn->query("SELECT * from Product
        INNER JOIN Cart ON Product.id = Cart.product_id AND Cart.user_id = $userId
        ");

        while ($row = $stmt->fetch()) {
            $productIds[] =  $row['id'];
            $productNames[] = $row['name'];
            $productPrices[] = $row['price'];
            $productBrands[] = $row['brand'];
            $productImagePaths[] = $row['image_path'];
        }
    } catch(PDOException $e) {
        header("Location: error.php?error=Connection failed:" . $e->getMessage());
    }

    // Close connection to save resources
    $conn = null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/cart.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous" />
  <title>Cart</title>
</head>

<body>
  <div id="root">
    <?php include("partials/header.php") ?>
    <?php include("partials/menu.php") ?>

    <main>
      <?php include("../pages/partials/sidebar.php") ?>
      <div class="cart">
        <h2>My Cart</h2>
        <?php 
          if (!empty($productNames)) {
            
            for ($i = 0; $i < count($productNames); $i++) { 
              echo "
            <div class='cart-item'>
              <img class='item-image' src='$productImagePaths[$i]' width=500 height=500>
              <div class='item-details'>
                <a href='product.php?id=$productIds[$i]'><p class='product'>$productNames[$i]</p>
                <p class='brand'>$productBrands[$i]</p>
                <div class='catalog-item-description-star'>
                    <span>
                      <img src='../images/star-orange.png' alt='star-rating' title='rating' />
                      <img src='../images/star-orange.png' alt='star-rating' title='rating' />
                      <img src='../images/star-orange.png' alt='star-rating' title='rating' />
                      <img src='../images/star-orange.png' alt='star-rating' title='rating' />
                      <img src='../images/star-white.png' alt='star-rating' title='rating' />
                      <p>(37)</p>
                    </span>
                </div>
                <p class='price'>&curren; $productPrices[$i]</p>
              </div>
              <div class='form-group text-center'>
                <form action='cart.php' method='get'>
                  <button type='submit' value='$productIds[$i]' name='productRemoveId' class='btn btn-info'><span class='glyphicon glyphicon-ok'></span> Remove From cart</button>
                </form>
              </div>
                <div class='form-group text-center'>
                  <form action='cart.php' method='get'>
                    <button type='submit' value='$productIds[$i]' name='productPurchaseId' class='btn btn-info'><span class='glyphicon glyphicon-ok'></span> Purchase Item</button>               
                  </form>
                  </div>   
             </div>";
            }
        } else {
            echo "<h3>No cart items to display</h3>";
          }     
        ?>
      </div> 
    </main>
    <?php include("partials/footer.php") ?>
  </div>
</body>

</html>