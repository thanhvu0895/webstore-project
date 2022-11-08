<?php
  session_start();
  require_once("connection.php");

  if (!isset($_SESSION["email"])) {
    header("Location: signin.php");
  }

  // get email of logged in user
  $userId = $_SESSION["userid"];


  /**
  * IMPLEMENT SHOWING PRODUCTS from Order
  * Thanh Vu
  * revised by: Thanh Vu 11/03/2022 - restructuring DB query 
  */

  try {
    $stmt = $conn->query("SELECT * from Product
    INNER JOIN OrderDetails ON Product.id = OrderDetails.product_id AND OrderDetails.user_id = $userId;
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
  <link rel="stylesheet" href="../css/wishlist.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous" />
  <title>Wishlist</title>
</head>

<body>
  <div id="root">
    <?php include("partials/header.php") ?>
    <?php include("partials/menu.php") ?>

    <main>
      <?php include("../pages/partials/sidebar.php") ?>
      <div class="wishlist">
        <h2>My Order</h2>
        <?php 
          if (!empty($productNames)) {
            
            for ($i = 0; $i < count($productNames); $i++) { 
              $productRateMess = ($voteCounts[$i] > 1) ? $voteCounts[$i] . ' rates' :  $voteCounts[$i] . ' rate';
              echo "
                <div class='wishlist-item'>
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
                  </div>
              </div>";
            }
        } else {
            echo "<h3>You have not purchased anything yet</h3>";
          }     
        ?>
      </div> 
    </main>
    <?php include("partials/footer.php") ?>
  </div>
</body>

</html>