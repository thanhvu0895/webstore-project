<?php
    session_start();
    require_once("connection.php");

    /**
    *     IMPLEMENT PRODUCT DETAILS SECTION
    *      Thanh Vu 11/03/2022
    */

    // check if id exists in query param
    if (empty($_GET['id'])) {
        header("Location: error.php?error=Missing Query ID Param");
    } else {
        // get product ID from url ? params
        $productId = $_GET['id'];
    }

    
    // get product name, brand, price, units_in_storage from product id
    try {
        $stmt = $conn->query("SELECT * FROM Product where id = '$productId'");
        $result = $stmt->fetch();
        
        if ($result == null) {
            header("Location: error.php?error=Product with given id does not exist");
        }
        
        $productName = $result['name'];
        $productCategory = $result['category'];
        $productPrice = $result['price'] ?? 'unknown price';
        $productBrand = $result['brand'] ?? 'unknown brand';
        $productImagePath = $result['image_path'];
        $productQuantity = $result['units_in_storage'];
        $productWeight = $result['weight'] ?? 'weight not listed';
        $productDimension = $result['dimension'] ?? 'dimension not listed'; 
        $productDescription = $result['description'] ?? 'unknown description';
    } catch (PDOException $e) {
        header("Location: error.php?error=Connection failed:" . $e->getMessage());
    }


    /**
    *  IMPLEMENT SIMILAR PRODUCTS
    *   Thanh Vu 11/03/2022
    */

    try {
        $stmt = $conn->query("SELECT * FROM Product where (NOT id = $productId) AND category = '$productCategory'");

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
    * IMPLEMENT SWITCH CASE FOR VOTING
    * THANH VU implemented on 11/05/22
    * This section is used for index.php, wishlist.php, cart.php.
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

    
    /**
    * Implement product rating
    *  Thanh Vu 11/03/2022
    */
    $buyAgainNum = 0;

    try {

        if (isset($_SESSION["email"]) && !empty($_GET['id'])) {
            $userId = $_SESSION['userid'];
            // CHECK IF USER IS LOGGED IN AND HAVE VOTED BEFORE TO DISPLAY IN PRODUCT DETAIL 
            $stmt = $conn->query("SELECT user_id, product_id, would_buy_again from ProductRating where user_id = $userId AND product_id = $productId");
            $result = $stmt->fetch();
            $messageVoteDisplay = ($stmt->rowCount() > 0) ? "<p>You have already rated this product</p>" : "<p>You have not rated this product</p>";
            
            if (empty($result['would_buy_again'])) {
                $messageBuyAgainDisplay = "<p>You have not answered whether you would buy $productName again</p>";
            } else if ($result['would_buy_again'] == 'Y') {
                $messageBuyAgainDisplay = "<p>You have decided to buy $productName again</p>";    
            } else if ($result['would_buy_again'] == 'N') {
                $messageBuyAgainDisplay = "<p>You have decided not to buy $productName again</p>";  
            } 

            // DISPLAY PEOPLE WHO WOULD BUY A PRODUCT AGAIN
            $stmt = $conn->query("SELECT COUNT(would_buy_again) as BuyAgain FROM ProductRating where product_id = $productId AND would_buy_again = 'Y'");     
            $result = $stmt->fetch();
            $buyAgainNum = $result['BuyAgain'] ?? '0';
        }

        // Case 1: if vote is selected but user is not signed in
        if (!empty($_GET['points']) && !isset($_SESSION["email"])) { 
            header("Location: signin.php");
        // Case 2: if vote is selected but there is no product id
        } elseif (!empty($_GET['points']) && empty($_GET['id'])) { 
            die();  // kill all actions to avoid null record in database
        } elseif (!empty($_GET['points']) && isset($_SESSION["userid"])) {
            // get points from query param
            $point = $_GET['points'] ?? 0;
            $userId = $_SESSION['userid'];   
        
            $stmt = $conn->query("SELECT user_id, product_id from ProductRating where user_id = $userId AND product_id = $productId");

            // IF VOTE HAS BEEN CASTED
            if ($stmt->rowCount() > 0) {
                $messageVoteCasted = "<div class='alert alert-warning alert-dismissable'>
                <a href='product.php?id=$productId' class='close' data-dismiss='alert' aria-label='close'>&times;</a> 
                <strong>Error: </strong> You have already rated $productName!
                    </div>";
            } else { // VOTE HAS NOT BEEN CASTED
                $messageVoteCasted = "";
                $conn->beginTransaction(); 
                $sql = ("INSERT INTO ProductRating (user_id, product_id, rating) VALUES (?, ?, ?)");
                $statement = $conn->prepare($sql);
                $statement->bindValue(1, $userId);
                $statement->bindValue(2, $productId);
                $statement->bindValue(3, $point);
                $statement->execute();
                $conn->commit();
            //UPDATE WHETHER YOU HAVE RATED THIS PRODUCT
            $stmt = $conn->query("SELECT user_id, product_id from ProductRating where user_id = $userId AND product_id = $productId");
            $messageVoteDisplay = ($stmt->rowCount() > 0) ? "<p>You have already rated this product</p>" : "<p>You have not rated this product</p>";
            }
        } 
            
        /**
         *  IMPLEMENT BUY AGAIN FORM
         * THanh 11/10
         */
        
        // Case 1: If buy again is selected but user is not signed in:
        if (!empty($_GET['buyAgain']) && !isset($_SESSION["email"])) { 
            header("Location: signin.php");
        // Case 2: if buyAgain is selected but no product id
        } elseif (!empty($_GET['buyAgain']) && empty($_GET['id'])) { 
            die();  // kill all actions to avoid null record in database
            // Case 3: if vote is selected and user is signed in
        } elseif (!empty($_GET['buyAgain']) && isset($_SESSION["userid"])) {
            // BUY AGAIN insert function
            $buyAgain = $_GET['buyAgain'] ?? 'Y';
            $userId = $_SESSION['userid'];
            
            $stmt = $conn->query("SELECT product_id, would_buy_again from ProductRating where user_id = $userId AND product_id = $productId");
            $result = $stmt->fetch();

            // Check if you have already rated or not
            if ($stmt->rowCount() > 0) {
                $conn->beginTransaction(); 
                $sql = ("UPDATE ProductRating SET would_buy_again = ? where user_id = ? AND product_id = ?");
                $statement = $conn->prepare($sql);
                $statement->bindValue(1, $buyAgain);
                $statement->bindValue(2, $userId);
                $statement->bindValue(3, $productId);
                $statement->execute();
                $conn->commit();
            
                //UPDATE USER'S ANSWER:
                $stmt = $conn->query("SELECT user_id, product_id, would_buy_again from ProductRating where user_id = $userId AND product_id = $productId");
                $result = $stmt->fetch();
                if (empty($result['would_buy_again'])) {
                    $messageBuyAgainDisplay = "<p>You have not answered whether you would buy $productName again</p>";
                } else if ($result['would_buy_again'] == 'Y') {
                    $messageBuyAgainDisplay = "<p>You have decided to buy $productName again</p>";    
                } else if ($result['would_buy_again'] == 'N') {
                    $messageBuyAgainDisplay = "<p>You have decided not to buy $productName again</p>";  
                } 

                // UPDATE PEOPLE WHO WOULD BUY A PRODUCT AGAIN
                $stmt = $conn->query("SELECT COUNT(would_buy_again) as BuyAgain FROM ProductRating where product_id = $productId AND would_buy_again = 'Y'");     
                $result = $stmt->fetch();
                $buyAgainNum = $result['BuyAgain'] ?? '0'; 

            } else {
                $messageRateFirst = "<div class='alert alert-warning alert-dismissable'>
                <a href='product.php?id=$productId' class='close' data-dismiss='alert' aria-label='close'>&times;</a> 
                <strong>Error: </strong> You have to rate $productName first!
                    </div>";
            }
            }

    } catch (PDOException $e) {
        header("Location: error.php?error=Connection failed:" . $e->getMessage());
    }

    // Get Rating and Vote numbers from productid
    try {
        $stmt = $conn->query("SELECT AVG(Rating) as RatingAverage, COUNT(Rating) as Votes FROM ProductRating INNER JOIN Product ON ProductRating.product_id = Product.id AND Product.id = $productId");
        $result = $stmt->fetch();
    } catch(PDOException $e) {
        header("Location: error.php?error=Connection failed:" . $e->getMessage());
    }

    if (empty($result['RatingAverage'])) {
        $productAvgRating = 0;
    } else {
        $productAvgRating = number_format($result['RatingAverage'], 2, '.', '');
    }
    
    $voteCount = $result['Votes'];

    /**
     * IMPLEMENT ADD TO WISHLIST 
     *  Thanh Vu 11/03/2022
     * 11/04/2012 Added missing userId check in SQL query
     */
    try {
        // Case 1: if vote is selected but user is not signed in
        if (!empty($_GET['addWishListId']) && !isset($_SESSION["email"])) { 
            header("Location: signin.php");
        // Case 2: if vote is selected but there is no product id
        } elseif (!empty($_GET['addWishListId']) && empty($_GET['id'])) { 
            die();  // kill all actions to avoid null record in database
        // Case 3: if vote is selected and user is signed in
        } elseif (!empty($_GET['addWishListId']) && isset($_SESSION["userid"])){
            $userId = $_SESSION["userid"];
            $stmt = $conn->query("SELECT * FROM ProductFavorite where product_id = $productId AND user_id = $userId");
            if ($stmt->rowCount() > 0) {
                // GENERATE HTML NOTIFYING USER ABOUT EXISTING PRODUCT
                $messageProductExisted = "<div class='alert alert-warning alert-dismissable'>
                                            <a href='product.php?id=$productId' class='close' data-dismiss='alert' aria-label='close'>&times;</a> 
                                            <strong>Error: </strong> Product already exists in wishlist. Go to <a href='wishlist.php'>Wishlist.</a>
                                         </div>";
            } else {
                $conn->beginTransaction(); 
                $sql = ("INSERT INTO ProductFavorite (user_id, product_id) VALUES (?, ?)");
                $statement = $conn->prepare($sql);
                $statement->bindValue(1, $userId);
                $statement->bindValue(2, $productId);
                $statement->execute();
                $conn->commit(); 
                
                //  GENERATE HTML NOTIFYING USER THAT PRODUCT HAS BEEN ADDED TO WISHLIST
                $messageProductExisted = "<div class='alert alert-warning alert-dismissable'>
                                            <a href='product.php?id=$productId' class='close' data-dismiss='alert' aria-label='close'>&times;</a> 
                                            Added this product to wishlist!, Go to <a href='wishlist.php'>Wishlist.</a>
                                          </div>";
            }      
        }
    } catch (PDOException $e) {
        header("Location: error.php?error=Connection failed:" . $e->getMessage());
    }


     /**
     * IMPLEMENT ADD TO CART 
     *  Thanh Vu 11/05/2022 created this function
     * 
     */

    try {
        // Case 1: if vote is selected but user is not signed in
        if (!empty($_GET['addCartQuant']) && !isset($_SESSION["email"])) { 
            header("Location: signin.php");
        // Case 2: if vote is selected but there is no product id
        } elseif (!empty($_GET['addCartQuant']) && empty($_GET['id'])) { 
            die();  // kill all actions to avoid null record in database
        // Case 3: if vote is selected and user is signed in
        } elseif (!empty($_GET['addCartQuant']) && isset($_SESSION["userid"])){
            $userId = $_SESSION["userid"];
            $stmt = $conn->query("SELECT * FROM Cart where product_id = $productId AND user_id = $userId");
            if ($stmt->rowCount() > 0) {
                // GENERATE HTML NOTIFYING USER ABOUT EXISTING PRODUCT
                $messageProductExisted = "<div class='alert alert-warning alert-dismissable'>
                                            <a href='product.php?id=$productId' class='close' data-dismiss='alert' aria-label='close'>&times;</a> 
                                            <strong>Error: </strong> Product already exists in Cart. Go to <a href='cart.php'>Cart.</a>
                                         </div>";
            } else {
                $conn->beginTransaction(); 
                $sql = ("INSERT INTO Cart (user_id, product_id) VALUES (?, ?)");
                $statement = $conn->prepare($sql);
                $statement->bindValue(1, $userId);
                $statement->bindValue(2, $productId);
                $statement->execute();
                $conn->commit(); 
                //  GENERATE HTML NOTIFYING USER THAT PRODUCT HAS BEEN ADDED TO cart
                $messageProductExisted = "<div class='alert alert-warning alert-dismissable'>
                                            <a href='product.php?id=$productId' class='close' data-dismiss='alert' aria-label='close'>&times;</a> 
                                            Added this product to cart!, Go to <a href='cart.php'>cart.</a>
                                          </div>";
            }      
        }
    } catch (PDOException $e) {
        header("Location: error.php?error=Connection failed:" . $e->getMessage());
    }

    $displayNone = (!isset($_SESSION["email"]))  ? "style='display:none'" : '';
    switch ($productAvgRating) {
        case 0: 
            $ratingDisplay = "<img src='../images/star-white.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />";
            break;
        case ($productAvgRating > 1 && $productAvgRating <= 1.5):
            $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange-half.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />";
            break;
        case ($productAvgRating >= 1.5 &&$productAvgRating < 2):
            $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange-51-99' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />";
            break;
        case ($productAvgRating > 2 &&$productAvgRating <= 2.5):
            $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange-half.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />";
            break;
        case ($productAvgRating >= 2.5 &&$productAvgRating < 3):
            $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange-51-99.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />";
            break;
        case ($productAvgRating > 3 &&$productAvgRating <= 3.5):
            $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange-half.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />";
            break;
        case ($productAvgRating >= 3.5 &&$productAvgRating < 4):
            $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange-51-99.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />";
            break;
        case ($productAvgRating > 4 &&$productAvgRating <= 4.5):
            $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange-half.png' alt='star-rating' title='rating' />";
            break;
        case ($productAvgRating > 4.5 &&$productAvgRating < 5):
            $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange-51-99.png' alt='star-rating' title='rating' />";
            break;            
          case 1:
            $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />";
            break;  
          case 2:
            $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />";
            break;  
          case 3:
            $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />";
            break;  
          case 4:
            $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />";
            break;  
          case 5:
            $ratingDisplay = "<img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange.png' alt='star-rating' title='rating' />
            <img src='../images/star-orange.png' alt='star-rating' title='rating' />";
            break;  
          default: 
            $ratingDisplay = "<img src='../images/star-white.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />
            <img src='../images/star-white.png' alt='star-rating' title='rating' />";
    }

    // Close connection to save resources
    $conn = null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/product-page.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <title>Product</title>
</head>

<body>
      <?php include("partials/header.php") ?>
      <?php include("partials/menu.php")?>

        <!-- IF THERE IS AN ERROR for the user or password information, then display this --> 
        <?php 
        	echo (!empty($messageProductExisted)) ? $messageProductExisted : '';
            echo (!empty($newVoteCasted)) ? $newVoteCasted : '';
            echo (!empty($messageVoteCasted)) ?  $messageVoteCasted : '';
            echo (!empty($messageRateFirst)) ?  $messageRateFirst : '';  
        ?>
<main style="background-color:rgb(253, 240, 213);">
        <!-- END display error -->
    <div class="product-container">
        <div class="product-section">
            <div class="product-section-image">
                <img src="<?php echo $productImagePath; ?>" alt="product" style="object-fit: contain;" height="100%" width="100%" />
            </div>
            <div class="product-section-description">
                <div class="product-section-description-title">
                    <h2>Product: <?php echo $productName; ?></h2>
                </div>
                <div class="product-section-description-brand">
                    <h3>Brand: <?php echo $productBrand; ?></h3>
                </div>
                <div class="product-section-description-rating">
                    <h4>Rating: <?php echo $ratingDisplay; ?> <?php echo $productAvgRating; ?>/5 [<?php echo $voteCount; ?> Reviews] <a style="font-size:medium; text-decoration:underline; margin-left:5px;" href="#review" ONCLICK="ShowAndHide()">Leave a review</a></h4>
                    <div class="review-status" <?php echo $displayNone ?>>
                        <?php
                            switch ($buyAgainNum) {
                                case 0:
                                    echo '<p>No one answered whether they would buy this product again</p>';
                                    break;
                                case 1:
                                    echo '<p>1 person would buy this product again</p>';
                                    break;
                                default:
                                    echo "<p>$buyAgainNum people would buy this product again</p>";
                            }
                        ?>
                    </div>
                </div>
                <div class="product-section-description-price">
                    <h4>Price: &curren;<?php echo $productPrice; ?></h4>
                </div>
                <div class="product-section-description-weight">
                    <h4>Weight: <?php echo $productWeight; ?></h4>
                </div>
                <div class="product-section-description-dimension">
                    <h4>Dimension: <?php echo $productDimension; ?></h4>
                </div>
                <div class="product-section-description-stock">
                    <h4>Availability: <?php echo ($productQuantity > 0) ? $productQuantity : 'Out of Stock'; ?></h4>
                </div>
                <div class="product-section-description-info">
                    <p><strong>About this item:</strong> <?php echo $productDescription; ?></p>
                </div>
                <div class="actions">
                    <form action='product.php' method='get'>
                        <!-- Hidden input contains value of query param id=? so we can append further query param -->
                        <input type="hidden" name="id" value="<?php echo $productId; ?>">
                        <button type="submit" class="btn btn-secondary" value=<?php echo $productId; ?> name='addWishListId'>Add to Wishlist</button>
                    </form>
                    <form action='product.php' method='get'>
                        <!-- Hidden input contains value of query param id=? so we can append further query param -->
                        <input type="hidden" name="id" value="<?php echo $productId ?>">
                        <button type="submit" class="btn btn-secondary" value='1' name='addCartQuant'>Add to Cart</button>
                    </form>
                </div>
                <div class="review-status" <?php echo $displayNone; ?>>
                    <?php
                    echo (!empty($messageVoteDisplay)) ? $messageVoteDisplay : '';
                    echo (!empty($messageBuyAgainDisplay)) ? $messageBuyAgainDisplay : '';
                    ?>
                </div>
            </div>
        </div>
    </div>


    <hr id="line">
    <!-- BEGIN SIMILAR PRODUCT SECTION -->
    <div class="similar-product">
        <div>
            <h4 style="margin:0px">Similar Products:</h4>
        </div>
        <div class="container px-4 px-lg-5 pt-5">
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 row-cols-xxl-6">
                <?php
                if (!empty($productNames)) {
                    for ($i = 0; $i < count($productNames); $i++) {
                        $productRateMess = ($voteCounts[$i] > 1) ? $voteCounts[$i] . ' rates' :  $voteCounts[$i] . ' rate';
                        echo "
                        <div class='col mb-5'>
                            <div class='catalog-item'>
                                <img src='$productImagePaths[$i]' alt='Item' width='130' height='130' />
                                <div class='catalog-item-description'>	
                                    <div class='catalog-item-description-name'>	
                                        <a href='product.php?id=$productIds[$i]'><p>$productNames[$i]</p></a>
                                    </div>
                                    <div class='catalog-item-description-brand'>	
                                        <p>$productBrands[$i]</p>
                                    </div>
                                    <div class='catalog-item-description-star'>	
                                        <span>	
                                        $ratingDisplays[$i]	
                                        <p>$productAvgRatings[$i]/5</p>	
                                        <p>($productRateMess)</p>	
                                        </span>	
                                    </div>	
                                    <p> &curren; $productPrices[$i]</p>
                                </div>
                            </div>
                        </div>";
                    }
                } else {
                    echo "<h3> No similar product to show </h3>";
                }
                ?>
            </div>
        </div>
        <hr id="line2">
        <!-- VOTING SECTION -->
        <div style="width: 80px; padding-left: 50px">
            <button id="review-button" class="btn btn-secondary" ONCLICK="ShowAndHide()">Review this product</button>
        </div>

        <script>
            function ShowAndHide() {
                var x = document.getElementById('review');
                var y = document.getElementById('review-button');
                if (x.style.display == 'none') {
                    x.style.display = 'block';
                    y.style.display = 'none';
                }
            }
        </script>
        <div id="review" style="display:none;">
            <div class="review-brick">
                <div class="review-panel">
                    <div class="panel-head">
                        <h4 style="padding:0% 0% 0% 2%;">Rating</h4>
                    </div>
                    <div style="padding:2% 0% 0% 2%;">
                        <div>
                            <strong class="text-primary"><?php echo $productAvgRating?>/5</strong> [<?php 
                            $productRateMess = ($voteCount > 1) ? $voteCount . ' rates' :  $voteCount . ' rate';
                            echo $productRateMess;
                            ?>]
                        </div>
                    </div>
                    <form action="product.php" method="get" oninput="x.value=' ' + rng.value + ' '">
                        <div class="form-group text-center">
                            <output id="x" for="rng"> 3 </output> <span class="glyphicon glyphicon-thumbs-up"></span> <br>
                            <input type="range" id="rng" name="points" min="1" max="5" step="1">
                            <!-- The value of the hiddem input field is the productID -->
                            <input type="hidden" name="id" value=<?php echo $productId ?>>
                        </div>
                        <br>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-info"><span class="glyphicon glyphicon-ok"></span> RATE!</button>
                        </div>
                    </form>
                    <form action="product.php" method="get">
                        <div style="margin-left:10px; text-align:left;">
                            <br>
                            <p>Would you buy this again?</p>
                            <input type="radio" id="yes" name="buyAgain" value='Y' checked>
                            <label for="yes">Yes</label>
                            <input type="radio" id="no" name="buyAgain" value='N'>
                            <label for="no">No</label>
                        </div>

                        <!-- The value of the hiddem input field is the productID -->
                        <input type="hidden" name="id" value=<?php echo $productId ?>>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-info"><span class="glyphicon glyphicon-ok"></span> SUBMIT</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- END VOTING SECTION -->
            </div>
</main>
        <?php include("partials/footer.php") ?>

</body>
</html>