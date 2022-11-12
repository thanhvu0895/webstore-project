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

          $conn->beginTransaction(); 
          $sql = ("INSERT INTO OrderDetails (user_id, total, product_id) VALUES (?, ?, ?)");
          $statement = $conn->prepare($sql);
          $statement->bindValue(1, $userId);
          $statement->bindValue(2, $productPurchasedPrice);
          $statement->bindValue(3, $productId);
          $statement->execute();
          $conn->commit(); 
            //  GENERATE HTML NOTIFYING USER THAT PRODUCT HAS BEEN Purchased successfully
            $purchaseMessage = "<div class='alert alert-warning alert-dismissable'>
            <a href='cart.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a> 
            You have successfully purchased this product. Go to <a href='order.php'>Order History.</a>
          </div>";

            //LAST STEP: REMOVE ITEM FROM CART
            $conn->beginTransaction(); 
            $sql = ("DELETE FROM Cart where product_id = ?");
            $statement = $conn->prepare($sql);
            $statement->bindValue(1, $productId);
            $statement->execute();
            $conn->commit(); 

            $stmt = $conn->query("SELECT * from Product
            INNER JOIN Cart ON Product.id = Cart.product_id AND Cart.user_id = $userId
            ");

            unset($productIds);
            unset($productNames);
            while ($row = $stmt->fetch()) {
                $productIds[] =  $row['id'];
                $productNames[] = $row['name'];
                $productUnits[] = $row['units_in_storage'];
            }

            


        } else if ($userBalance < $productPurchasedPrice ) { // if user does not have enough balance
          $purchaseMessage = "<div class='alert alert-warning alert-dismissable'>
          <a href='cart.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a> 
          You don't have enough balance to purchase this product
        </div>";

        } else {
          $purchaseMessage = "<div class='alert alert-warning alert-dismissable'>
          <a href='cart.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a> 
          The item is out of stock;
        </div>";
        }
      }
      // STEP 4: Show the updated amount of webcoin after purchase
      $stmt = $conn->query("SELECT webstoreBalance FROM User where id = $userId");
      $result = $stmt->fetch();
      $userBalance = $result['webstoreBalance'] ?? -10;

      // STEP 5: SHOW THE UPDATED AMOUNT OF UNIT after purchase: 
      $stmt = $conn->query("SELECT * from Product
                            INNER JOIN Cart ON Product.id = Cart.product_id AND Cart.user_id = $userId
                          ");

      //unset array so it holds value of updated unit number
      unset($productUnits);
      while ($row = $stmt->fetch()) {
          $productUnits[] = $row['units_in_storage'];
      }
  } catch(PDOException $e) {
      header("Location: error.php?error=Connection failed:" . $e->getMessage());
  }


  /**
  * IMPLEMENT SWITCH CASE FOR VOTING
  * THANH VU implemented on 11/05/22
  * This section is used for wishlist.php, cart.php (based on index.php)
  * This is the most up to date version 11/06/22
  * Testing steps:
  *   1. Sign in to webstore, usertest@123 | 1234 
  *   2. Check if the amount of star corresponds to rating (3.67 => 3 and about half stars). 
  *   3. Make sure that number of rating is displayed correctly. 
  *   4. Click on a product, add rating with another user to see: 
  *       - Number of rating changes
  *       - Choose a very small or big number to see if Rating/5 is reflected. 
  *       - Check if calculation is correct 
  */

  $productAvgRatings;
  $voteCounts;
  $ratingDisplays;


  try {
      $productNums = (!empty($productIds)) ? count($productIds) : 0;
      for ($i = 0; $i < $productNums; $i++) { 
        $stmt = $conn->query("SELECT AVG(Rating) as RatingAverage, COUNT(Rating) as Votes FROM ProductRating INNER JOIN Product ON ProductRating.product_id = Product.id AND Product.id = $productIds[$i]");
        $result = $stmt->fetch();
        
        $productAvgRating = empty($result['RatingAverage']) ? 0 : number_format($result['RatingAverage'], 2, '.', '');
        $voteCount = $result['Votes'];

        switch ($productAvgRating) {
          case 0: 
              $ratingDisplay = "<img src='../images/star-white.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />";
              $ratingDisplays[] = $ratingDisplay;
              break;
          case ($productAvgRating > 1 && $productAvgRating <= 1.5):
              $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange-half.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />";
              $ratingDisplays[] = $ratingDisplay;
              break;
          case ($productAvgRating >= 1.5 &&$productAvgRating < 2):
              $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange-51-99' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />";
              $ratingDisplays[] = $ratingDisplay;
              break;
          case ($productAvgRating > 2 &&$productAvgRating <= 2.5):
              $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange-half.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />";
              $ratingDisplays[] = $ratingDisplay;
              break;
          case ($productAvgRating >= 2.5 &&$productAvgRating < 3):
              $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange-51-99.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />";
              $ratingDisplays[] = $ratingDisplay;
              break;
          case ($productAvgRating > 3 &&$productAvgRating <= 3.5):
              $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange-half.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />";
              $ratingDisplays[] = $ratingDisplay;
              break;
          case ($productAvgRating >= 3.5 &&$productAvgRating < 4):
              $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange-51-99.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />";
              $ratingDisplays[] = $ratingDisplay;
              break;
          case ($productAvgRating > 4 &&$productAvgRating <= 4.5):
              $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange-half.png' alt='star-rating' title='rating' />";
              $ratingDisplays[] = $ratingDisplay;
              break;
          case ($productAvgRating > 4.5 &&$productAvgRating < 5):
              $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange-51-99.png' alt='star-rating' title='rating' />";
              $ratingDisplays[] = $ratingDisplay;
              break;            
            case 1:
              $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />";
              $ratingDisplays[] = $ratingDisplay;
              break;  
            case 2:
              $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />";
              $ratingDisplays[] = $ratingDisplay;
              break;  
            case 3:
              $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />";
              $ratingDisplays[] = $ratingDisplay;
              break;  
            case 4:
              $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />";
              $ratingDisplays[] = $ratingDisplay;
              break;  
            case 5:
              $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange.png' alt='star-rating' title='rating' />
              <img src='../images/star-orange.png' alt='star-rating' title='rating' />";
              $ratingDisplays[] = $ratingDisplay;
              break;  
            default: 
              $ratingDisplay = "<img src='../images/star-white.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />
              <img src='../images/star-white.png' alt='star-rating' title='rating' />";
              $ratingDisplays[] = $ratingDisplay;
      }

        $productAvgRatings[] = $productAvgRating;
        $voteCounts[] = $voteCount;
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
          $purchaseMessage = (!empty($purchaseMessage))  ? $purchaseMessage : '';
          echo $purchaseMessage;        
          
          if (!empty($productNames)) {
        
            for ($i = 0; $i < count($productNames); $i++) { 
              
              $productRateMess = ($voteCounts[$i] > 1) ? $voteCounts[$i] . ' rates' :  $voteCounts[$i] . ' rate';
              
              if ($productUnits[$i] >= 1) {
                $cartMessage = "<div class='alert alert-warning alert-dismissable'>
                <a href='cart.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a> 
                In stock 
              </div>";
              } else {
                $cartMessage = "<div class='alert alert-warning alert-dismissable'>
                <a href='cart.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a> 
                The product is out of stock
              </div>";
              }
              $productPurchasedPrice = $productPurchasedPrice ?? 0;
              if ($productPurchasedPrice <= $userBalance) {
                $cartMessage = "<div class='alert alert-warning alert-dismissable'>
                <a href='cart.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a> 
                Enough Balance
              </div>";
              } else {
                 $cartMessage = "<div class='alert alert-warning alert-dismissable'>
                <a href='cart.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a> 
                Not enough Balance
              </div>";
              }
              
              $productUnitsMess = ($productUnits[$i] == 0) ? "<p class='units-in-storage'>Out of stock</p>" : "<p class='units-in-storage'>$productUnits[$i] left in storage</p>";
              
              echo "
                    <div class='cart-item'>
                      <img class='item-image' src='$productImagePaths[$i]' width=500 height=500>
                      <div class='item-details'>
                        <a href='product.php?id=$productIds[$i]'><p class='product'>$productNames[$i]</p>
                        <p class='brand'>$productBrands[$i]</p>
                        <div class='catalog-item-description-star'>
                            <span>
                              $ratingDisplays[$i]
                              <p>$productAvgRatings[$i]/5</p>
                              <p>($productRateMess)</p>
                            </span>
                        </div>
                        <p class='price'>&curren; $productPrices[$i]</p>
                        $productUnitsMess
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