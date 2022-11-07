<?php
session_start();
require_once("connection.php");

if (!isset($_SESSION["email"])) {
  header("Location: signin.php");
}

/**
 *  IMPLEMENT GET WEBCOIN AMOUNT
 *     Thanh Vu 11/06/2022
 */


$userId = $_SESSION['userid'];

try {
  $stmt = $conn->query("SELECT webstoreBalance FROM User where id = $userId");
  $result = $stmt->fetch();
  $userBalance = $result['webstoreBalance'] ?? -1;

} catch(PDOException $e) {
    header("Location: error.php?error=Connection failed:" . $e->getMessage());
  }

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
        <a href="logout.php">Log out</a>
      </div>
      <div>
        <div>
        <strong>Greeting</strong> <?php echo $_SESSION['username'] ?>.
          <br>
          <strong>Your WebCoin Balance is: </strong>&curren; <?php echo $userBalance ?>.
        </div>
        <form>
          <p style="margin-top: 30px">Change Username:</p>
          <input type="text" name="email" class="form-control" placeholder="new username" />
          <button type="submit" class="btn btn-secondary" name="change-username" style="margin-top: 10px">Submit</button>

          <p style="margin-top: 30px">Change Password:</p>
          <input type="text" name="password" class="form-control" placeholder="current password" />
          <input type="text" name="password" class="form-control" placeholder="new password" style="margin-top: 6px" />
          <input type="text" name="password" class="form-control" placeholder="confirm password" />
          <button type="submit" class="btn btn-secondary" name="change-password" style="margin-top: 10px">Submit</button>

        </form>
      </div>
    </div>
    <?php include("partials/footer.php") ?>
  </div>
</body>

</html>