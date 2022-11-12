<?php
session_start();
require_once("connection.php");
$randomProducts;

/**
 *  IMPLEMENT SHOWING PRODUCTS ON HOME PAGE
 *  Thanh Vu 11/03/2022
 */

try {

  $stmt = $conn->query("SELECT * FROM Product");

  // Get id product name, brand, price, image_path from product Id 
  while ($row = $stmt->fetch()) {
    $productIds[] =  $row['id'];
    $productNames[] = $row['name'];
    $productPrices[] = $row['price'];
    $productBrands[] = $row['brand'];
    $productImagePaths[] = $row['image_path'];
  }
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}

try {
  for ($i = 0; $i < count($productIds); $i++) {

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
      case ($productAvgRating >= 1.5 && $productAvgRating < 2):
        $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
        <img src='../images/star-orange-51-99' alt='star-rating' title='rating' />
        <img src='../images/star-white.png' alt='star-rating' title='rating' />
        <img src='../images/star-white.png' alt='star-rating' title='rating' />
        <img src='../images/star-white.png' alt='star-rating' title='rating' />";
        $ratingDisplays[] = $ratingDisplay;
        break;
      case ($productAvgRating > 2 && $productAvgRating <= 2.5):
        $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
        <img src='../images/star-orange.png' alt='star-rating' title='rating' />
        <img src='../images/star-orange-half.png' alt='star-rating' title='rating' />
        <img src='../images/star-white.png' alt='star-rating' title='rating' />
        <img src='../images/star-white.png' alt='star-rating' title='rating' />";
        $ratingDisplays[] = $ratingDisplay;
        break;
      case ($productAvgRating >= 2.5 && $productAvgRating < 3):
        $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
        <img src='../images/star-orange.png' alt='star-rating' title='rating' />
        <img src='../images/star-orange-51-99.png' alt='star-rating' title='rating' />
        <img src='../images/star-white.png' alt='star-rating' title='rating' />
        <img src='../images/star-white.png' alt='star-rating' title='rating' />";
        $ratingDisplays[] = $ratingDisplay;
        break;
      case ($productAvgRating > 3 && $productAvgRating <= 3.5):
        $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
        <img src='../images/star-orange.png' alt='star-rating' title='rating' />
        <img src='../images/star-orange.png' alt='star-rating' title='rating' />
        <img src='../images/star-orange-half.png' alt='star-rating' title='rating' />
        <img src='../images/star-white.png' alt='star-rating' title='rating' />";
        $ratingDisplays[] = $ratingDisplay;
        break;
      case ($productAvgRating >= 3.5 && $productAvgRating < 4):
        $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
        <img src='../images/star-orange.png' alt='star-rating' title='rating' />
        <img src='../images/star-orange.png' alt='star-rating' title='rating' />
        <img src='../images/star-orange-51-99.png' alt='star-rating' title='rating' />
        <img src='../images/star-white.png' alt='star-rating' title='rating' />";
        $ratingDisplays[] = $ratingDisplay;
        break;
      case ($productAvgRating > 4 && $productAvgRating <= 4.5):
        $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
        <img src='../images/star-orange.png' alt='star-rating' title='rating' />
        <img src='../images/star-orange.png' alt='star-rating' title='rating' />
        <img src='../images/star-orange.png' alt='star-rating' title='rating' />
        <img src='../images/star-orange-half.png' alt='star-rating' title='rating' />";
        $ratingDisplays[] = $ratingDisplay;
        break;
      case ($productAvgRating > 4.5 && $productAvgRating < 5):
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
} catch (PDOException $e) {
  header("Location: error.php?error=Connection failed:" . $e->getMessage());
}

// Set display to none if user is not logged in
$displayNone = (!isset($_SESSION["email"]))  ? "style='display:none'" : '';

// Close connection to save resources
$conn = null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../css/landing.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" />
  <title>Landing</title>
</head>

<body>
  <div id="root">
    <?php include("partials/header.php") ?>
    <?php include("partials/menu.php") ?>

    <div class="catalog">
      <div class="container px-4 px-lg-5 pt-5">
        <div class="row gx-4 gx-lg-5 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 row-cols-xxl-6">
          <?php
          for ($i = 0; $i < count($productNames); $i++) {
            echo "<div class='col mb-5'>
              <div class='catalog-item'>
                <div class='catalog-item-image'>
                  <img src='$productImagePaths[$i]' alt='Item' width='100%' height='130px' class='contain'/>
                </div>
                <div class='catalog-item-description'>
                  <div class='catalog-item-description-name'>
                    <a href='product.php?id=$productIds[$i]'><p>$productNames[$i]</p></a>
                    <img src='../images/HeartIcon.png' alt='heart-icon' height='12' width='12' />
                  </div>
              
                  <div class='catalog-item-description-brand'>
                    <p>$productBrands[$i]</p>
                    <img src='../images/PointerIcon.png' alt='heart-icon' height='12' width='13' />
                  </div>
              
                  <div class='catalog-item-description-star'>
                    <span>
                      $ratingDisplays[$i]
                      <p>$productAvgRatings[$i]/5</p>
                    </span>
                  </div>
                  <p>\$ $productPrices[$i].99</p>
                </div>
              </div>
              </div>";
          }
          ?>
        </div>
      </div>
    </div>
    <?php include("partials/footer.php") ?>
  </div>
</body>

</html>