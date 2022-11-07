<?php
    session_start();
    
    if (!isset($_SESSION["email"])) {
      header("Location: signin.php");
      }

    require_once("connection.php");

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
          $productUnits[] = $row['units_in_storage'];
      }
  } catch(PDOException $e) {
      header("Location: error.php?error=Connection failed:" . $e->getMessage());
  }


    /**
     * IMPLEMENT PURCHASING ITEM FROM CART:
     */

  try {
         // STEP 1: Show current balance
         $stmt = $conn->query("SELECT webstoreBalance FROM User where id = $userId");
         $result = $stmt->fetch();
         $userBalance = $result['webstoreBalance'] ?? -1;

      } catch(PDOException $e) {
      header("Location: error.php?error=Connection failed:" . $e->getMessage());
  }

     
  try {
      
      // STEP 2: Check if Purchase button is clicked
      if (!empty($_GET['productPurchasedPrice'])) {

        $productPurchasedPrice = $_GET['productPurchasedPrice'] ?? '0';
        $productUnit = $_GET['unit'] ?? 0;
        $productId = $_GET['id'] ?? 0;
        if ($userBalance > $productPurchasedPrice  && $productUnit >= 1) {
          

          //STEP 3: Deduce the unit by 1
          $conn->beginTransaction(); 
          $sql = ("UPDATE Product SET units_in_storage = units_in_storage - 1 where id = ?");
          $statement = $conn->prepare($sql);
          $statement->bindValue(1, $productId);
          $statement->execute();
          $conn->commit();

          //STEP 3: Deduce the remaining webcoin by the product 
          $conn->beginTransaction(); 
          $sql = ("UPDATE User SET webstoreBalance = webstoreBalance - ? where id = ?");
          $statement = $conn->prepare($sql);
          $statement->bindValue(1, $productPurchasedPrice);
          $statement->bindValue(2, $userId);
          $statement->execute();
          $conn->commit();
        }
      // //LAST STEP: REMOVE ITEM FROM CART
      //   $conn->beginTransaction(); 
      //   $sql = ("DELETE FROM Cart where product_id = ?");
      //   $statement = $conn->prepare($sql);
      //   $statement->bindValue(1, $productPurchasedPrice);
      //   $statement->execute();
      //   $conn->commit(); 
      }
      // STEP 4: Show the updated amount of webcoin after purchase
      $stmt = $conn->query("SELECT webstoreBalance FROM User where id = $userId");
      $result = $stmt->fetch();
      $userBalance = $result['webstoreBalance'] ?? -10;

      // STEP 5: SHOW THE UPDATED AMOUNT OF UNIT after purchase: 
      $stmt = $conn->query("SELECT * from Product
                            INNER JOIN Cart ON Product.id = Cart.product_id AND Cart.user_id = $userId
                          ");
      unset($productUnits);
      while ($row = $stmt->fetch()) {
          $productUnits[] = $row['units_in_storage'];
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
        <h3><strong><p>Remaining Balance: </strong>&curren;<?php echo $userBalance ?></p></h3> 
        <?php 
          if (!empty($productNames)) {
            print_r($productUnits);
            
            for ($i = 0; $i < count($productNames); $i++) { 
              if ($productUnits[$i] >= 1) {
                echo "There is enough in storage for you to buy";
              } else {
                echo "The product is out of stock";
              }
              $productPurchasedPrice = $productPurchasedPrice ?? 0;
              if ($productPurchasedPrice <= $userBalance) {
                echo "You have enough in your balance for this product";
              } else {
                echo "You don't have enough in your balance. Refill your webcoin!";
              }
              echo $userBalance;
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
                <p class='units-in-storage'>$productUnits[$i] left in storage</p>
              </div>
              <div class='form-group text-center'>
                <form action='cart.php' method='get'>
                  <button type='submit' value='$productIds[$i]' name='productRemoveId' class='btn btn-info'><span class='glyphicon glyphicon-ok'></span> Remove From cart</button>
                </form>
              </div>
                <div class='form-group text-center'>
                  <form action='cart.php' method='get'>
                   <!-- Hidden input contains value of query param id=? so we can append further query param -->
                    <input type='hidden' name='unit' value='$productUnits[$i]'>
                    <input type='hidden' name='id' value='$productIds[$i]'>
                    <button type='submit' value='$productPrices[$i]' name='productPurchasedPrice' class='btn btn-info'><span class='glyphicon glyphicon-ok'></span> Purchase Item</button>               
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