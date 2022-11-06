<?php
session_start();
require_once("connection.php");

if (!isset($_SESSION["email"])) {
    header("Location: signin.php");
}

/**
 *  IMPLEMENT REFILL BY AMOUNT
 *     @author: Caroline and Thanh Vu 11/03/2022
 */

if (!empty($_GET['amounts'])) {
    $refillAmount = $_GET['amounts'] ?? '0';
    $userId = $_SESSION['userid'];

    $conn->beginTransaction(); 
    $sql = ("UPDATE User SET webstoreBalance = webstoreBalance + ? where id = ?");
    $statement = $conn->prepare($sql);
    $statement->bindValue(1, $refillAmount);
    $statement->bindValue(2, $userId);
    $statement->execute();
    $conn->commit(); 

    echo $refillAmount . " webstore added successfully";
}   


// close db connection to save resources
$conn = null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/account.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" />
    <title>Webstore</title>
</head>

<body>
    <div id="root">
        <?php include("partials/header.php") ?>
        <div class="catalog">
            <div>
                <?php include("../pages/partials/sidebar.php") ?>
            </div>

            <div>
                <p>Remaining Balance: 10</p>
                <form action="refill-webcoins.php" method="post">
                    <label for="amount">Add coins to the account:</label>
                    <select name="amounts" id="amount">
                        <option value=100>100</option>
                        <option value=50>50</option>
                        <option value=10>10</option>
                    </select>
                    <br><br>
                    <button type="submit" value="Submit" class="btn btn-secondary" style="margin-top: 10px">Add</button>
                </form>
            </div>
        </div>
        <?php include("partials/footer.php") ?>
    </div>
</body>

</html>