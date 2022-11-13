<?php
session_start();
require_once("connection.php");

/*
 *  IMPLEMENT SHOWING PRODUCTS ON HOME PAGE
 *  FUNCTIONS IMPLEMENTED by Thanh Vu
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
  header("Location: error.php?error=Connection failed:" . $e->getMessage());
}

/**
 *  IMPLEMENT SHOWING BY CATEGORY
 */

try {
  if (!empty($_GET['category'])) {
    $category = $_GET['category'] ?? '';
    $stmt = $conn->query("SELECT * FROM Product where category='$category'");

    // Get id product name, brand, price, image_path from product Id 
    unset($productNames);
    unset($productIds);
    unset($productPrices);
    unset($productBrands);
    unset($productImagePaths);

    while ($row = $stmt->fetch()) {
      $productIds[] =  $row['id'];
      $productNames[] = $row['name'];
      $productPrices[] = $row['price'];
      $productBrands[] = $row['brand'];
      $productImagePaths[] = $row['image_path'];
    }
  }
} catch (PDOException $e) {
  header("Location: error.php?error=Connection failed:" . $e->getMessage());
}


/**
 *  IMPLEMENT SLIDER FILTER PRICE FUNCTION
 */

// Set default value for min and max
$min = 1;
$max = 200;

if (!empty($_POST['min_price'])) {
  $min = $_POST['min_price'];
}

if (!empty($_POST['max_price'])) {
  $max = $_POST['max_price'];
}


/**
 *  IMPLEMENT OPTIONS FILTER AVERAGE RATING FUNCTION
 *   THANH VU 11/10/22
 */

$stars = 1;
try {

  if (!empty($_POST['min_price'])) {
    $category = $_GET['category'] ?? '';
    $stars = $_POST['stars'] ?? 0;
    if ($stars == 0) {
      $checkCategory = (!empty($_GET['category'])) ? "AND category = '$category'" : '';
      $sql = "SELECT * FROM Product WHERE price between $min and $max " . $checkCategory;
      // . $checkCategory;
    } else {
      $checkCategory = (!empty($_GET['category'])) ? "AND (Product.category = '$category')" : '';
      $sql = "SELECT T.RatingAverage, T.id, T.name, T.price, T.brand, T.image_path
                from (SELECT id, name, price, brand, image_path, AVG(Rating) as RatingAverage, COUNT(Rating) as Votes FROM ProductRating INNER JOIN Product ON ProductRating.product_id = Product.id AND (Product.price between $min and $max) " . $checkCategory . " GROUP BY product_id) as T
            where T.RatingAverage between $stars and 5";
    }
    $stmt = $conn->query($sql);
    // Get id product name, brand, price, image_path from product Id 
    unset($productNames);
    unset($productIds);
    unset($productPrices);
    unset($productBrands);
    unset($productImagePaths);
    while ($row = $stmt->fetch()) {
      $productIds[] =  $row['id'];
      $productNames[] = $row['name'];
      $productPrices[] = $row['price'];
      $productBrands[] = $row['brand'];
      $productImagePaths[] = $row['image_path'];
    }
  }
} catch (PDOException $e) {
  header("Location: error.php?error=Connection failed:" . $e->getMessage());
}


/**
 *  IMPLEMENT SEARCH BAR
 */
 
$search = "";
try {
  if (!empty($_POST['search'])) {
    $search = $_POST['search'];
    $stmt = $conn->query("SELECT * FROM Product where name like '%$search%'");
    // Get id product name, brand, price, image_path from product Id 
    unset($productNames);
    unset($productIds);
    unset($productPrices);
    unset($productBrands);
    unset($productImagePaths);
    while ($row = $stmt->fetch()) {
      $productIds[] =  $row['id'];
      $productNames[] = $row['name'];
      $productPrices[] = $row['price'];
      $productBrands[] = $row['brand'];
      $productImagePaths[] = $row['image_path'];
    }
  }
} catch (PDOException $e) {
  header("Location: error.php?error=Connection failed:" . $e->getMessage());
}




// get product rating from product id:
$productAvgRatings;
$voteCounts;
$ratingDisplays;

try {
  if (!empty($productIds)) {
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
  }
} catch (PDOException $e) {
  header("Location: error.php?error=Connection failed:" . $e->getMessage());
}

$productId = "";
$heartColor = "WHITE";



/**
 * IMPLEMENT ADD TO WISHLIST 
 *  Thanh Vu 11/03/2022
 * 11/04/2012 Added missing userId check in SQL query
 */

try {
  // Case 1: if vote is selected but user is not signed in
  if (!empty($_POST['id']) && !isset($_SESSION["email"])) {
    header("Location: signin.php");
    // Case 2: if vote is selected
  } elseif (!empty($_POST['id']) && isset($_SESSION["userid"])) {
    $userId = $_SESSION["userid"];
    $productId = $_POST['id'];;
    $stmt = $conn->query("SELECT * FROM ProductFavorite where product_id = $productId AND user_id = $userId");
    if ($stmt->rowCount() > 0) {
      echo "<h2> Product already exists in WishList. Go to <a href='wishlist.php'>Wish Lists.</a> </h2>";
    } else {
      $conn->beginTransaction();
      $sql = ("INSERT INTO ProductFavorite (user_id, product_id) VALUES (?, ?)");
      $statement = $conn->prepare($sql);
      $statement->bindValue(1, $userId);
      $statement->bindValue(2, $productId);
      $statement->execute();
      $conn->commit();
      $heartColor = "red";
      echo "<h2> Added this product to WishList. Go to <a href='wishlist.php'>Wishlist.</a> </h2>";
    }
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<title>Webstore</title>

<script type="text/javascript">
  $(function() {
    $("#slider-range").slider({
      range: true,
      min: 1,
      max: 200,
      values: [<?php echo $min; ?>, <?php echo $max; ?>],
      slide: function(event, ui) {
        $("#amount").html("$" + ui.values[0] + " - $" + ui.values[1]);
        $("#min").val(ui.values[0]);
        $("#max").val(ui.values[1]);
      }
    });
    $("#amount").html("$" + $("#slider-range").slider("values", 0) +
      " - $" + $("#slider-range").slider("values", 1));
  });
</script>

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../css/index.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" />
  <title>Webstore</title>
</head>

<body>
  <div id="root">
    <?php include("partials/header.php") ?>
    <?php include("partials/menu.php") ?>

    <div class="catalog">
      <!-- slider begins -->
    <!--  <div>
        <form method="post" action="">
          <div style="display: flex; flex-direction: column;">
            <div style="display: flex;  width:200px; justify-content: space-between; align-items: center;">
              <div style="display: flex">
                <p>Min</p>
                <input style="width: 50px; height: 25px; border-radius: 8px; padding-left: 10px" type="" id="min" class="filter-amount" name="min_price" value="<?php echo $min; ?>">
              </div>
              <div style="display: flex">
                <p>Max</p>
                <input style="width: 50px; height: 25px; border-radius: 8px; padding-left: 10px" type="" id="max" name="max_price" value="<?php echo $max; ?>">
              </div>

            </div>
            <div id="slider-range" style="width: 180px"></div>
            <div style="margin-top: 10px;">
              <select name="stars" id="stars" value>
                <option value="0" selected disabled hidden>Select a Rating</option>
                <option value=4>4 Stars & Up</option>
                <option value=3>3 Stars & Up</option>
                <option value=2>2 Stars & Up</option>
                <option value=1>1 Star & Up</option>
                <option value=0>Include No Rating</option>
              </select>
              <button style='width: 80px' type='submit'> Submit </button>
            </div>
          </div>
        </form>
      </div> -->
      <!-- slider ends -->

      <div class="container px-4 px-lg-5 pt-5">
        <div class="row gx-4 gx-lg-5 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 row-cols-xxl-6">
          <?php
          if (!empty($productNames)) {
            for ($i = 0; $i < count($productNames); $i++) {
              $productRateMess = ($voteCounts[$i] > 1) ? $voteCounts[$i] . ' rates' :  $voteCounts[$i] . ' rate';
              echo "
              <div class='col mb-5'>
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
                    <p>&curren; $productPrices[$i]</p>
                  </div>
                </div>
              </div>";
            }
          } else {
            echo "<h3> No product to show </h3>";
          }
          ?>
        </div>
      </div>
    </div>
    <?php include("partials/footer.php") ?>
  </div>
</body>

</html>