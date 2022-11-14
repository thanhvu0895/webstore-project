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
} catch (PDOException $e) {
  header("Location: error.php?error=Connection failed:" . $e->getMessage());
}

/**
 * IMPLEMENT CHANGE USERNAME
 */
$fullName = "";

if (!empty($_POST['email'])){
  $fullName = $_POST['email'];
  $stmt = $conn->query("SELECT full_name FROM User WHERE full_name = '$fullName'");
  
        $conn->beginTransaction();
        $sql = ("UPDATE User SET full_name = ? where id = ?");
        $statement = $conn->prepare($sql);
        $statement->bindValue(1, $fullName);
        $statement->bindValue(2, $userId);
        $statement->execute();
        $conn->commit();

        echo "<div class='alert alert-warning alert-dismissable'>
                          <a href='home.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a> 
                          Changed Username successfully to $fullName
                        </div>";
        unset($_SESSION['username']);
        $_SESSION["username"] = $fullName;
}

/**
 * IMPLEMENT CHANGE PASSWORD
 */
 $currentPass = "";
 $newPass = "";

try {
  if (!empty($_POST['currentPass'])) {
    $currentPass = $_POST['currentPass'] ?? '';
    $newPass = $_POST['newPass'];
    $newPassConfirm = $_POST['newPassConfirm'];
    
    $stmt = $conn->query("SELECT pass_word FROM User WHERE id = '$userId'");
    $result = $stmt->fetch();
    $currentHashPass = $result['pass_word'] ?? '';

    if (!password_verify($currentPass, $currentHashPass)) {
      echo "<div class='alert alert-warning alert-dismissable'>
        <a href='home.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a> 
        <strong>Error: </strong> Password did not match our records
      </div>";
    } else if ($newPassConfirm != $newPass) {
      echo "<div class='alert alert-warning alert-dismissable'>
      <a href='home.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a> 
      <strong>Error: </strong> confirm password and new pass are not identical 
    </div>";
    } else if ($currentPass == $newPass) {
      echo "<div class='alert alert-warning alert-dismissable'>
      <a href='home.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a> 
      <strong>Error: </strong> new password and old password cannot be the same
    </div>";
    } else {
      $hashed_password = password_hash($newPass, PASSWORD_DEFAULT);
      $conn->beginTransaction(); 
      $sql = ("UPDATE User SET pass_word = ? where id = ?");
      $statement = $conn->prepare($sql);
      $statement->bindValue(1, $hashed_password);
      $statement->bindValue(2, $userId);
      $statement->execute();
      $conn->commit(); 
      echo "<div class='alert alert-warning alert-dismissable'>
      <a href='home.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a> 
      Password Changed Successfully!
    </div>";
    }  
 }
} catch (PDOException $e) {
  header("Location: error.php?error=Connection failed:" . $e->getMessage());
}

try {
  if (isset($_POST['deleteAcc'])) {
    $conn->beginTransaction(); 
    
    $sql = ("DELETE FROM ProductFavorite WHERE user_id = ?");
    $statement = $conn->prepare($sql);
    $statement->bindValue(1, $userId);
    $statement->execute();
    $sql = ("DELETE FROM ProductRating WHERE user_id = ?");
    $statement = $conn->prepare($sql);
    $statement->bindValue(1, $userId);
    $statement->execute();

    $sql = ("DELETE FROM OrderDetails WHERE user_id = ?");
    $statement = $conn->prepare($sql);
    $statement->bindValue(1, $userId);
    $statement->execute();

    $sql = ("DELETE FROM Cart WHERE user_id = ?");
    $statement = $conn->prepare($sql);
    $statement->bindValue(1, $userId);
    $statement->execute();


    $sqlUser = ("DELETE FROM User WHERE id = ?");
    $statement = $conn->prepare($sql);
    $statement->bindValue(1, $userId);
    $statement->execute();

    $conn->commit(); 

    session_destroy();
    header("Location: signin.php");
  }
} catch (PDOException $e) {
  header("Location: error.php?error=Connection failed:" . $e->getMessage());
}

// Close DB to save resources.
$conn = null;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/home.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <title>Account</title>
</head>

<body>
  <?php include("partials/header.php") ?>
    <div class="account-container">
        <div class="sidebar-container">
            <?php include("partials/sidebar.php") ?>
            <a href="logout.php"><h3>Log out<h3></a>
        </div>
        <div class="form-container">
            <div>
                <strong>Greeting</strong> <?php echo $_SESSION['username'] ?>.
                <br>
                <strong>Your username is: </strong> <?php echo $_SESSION['email'] ?>
                <br>
                <strong>Your WebCoin Balance is: </strong>&curren; <?php echo $userBalance ?>.
            </div>
            <div>
                <form method="post" action="">
                    <p style="margin-top: 30px">Change Full Name:</p>
                    <input type="text" name="email" class="form-control" placeholder="new username" required />
                    <button type="submit" class="btn btn-secondary" style="margin-top: 10px">Submit</button>
                </form>
                <form method="post" action="">
                    <p style="margin-top: 30px">Change Password:</p>
                    <input type="password" name="currentPass" class="form-control" placeholder="current password" required />
                    <input type="password" name="newPass" class="form-control" placeholder="new password" style="margin-top: 6px" pattern=".{8,12}" required title="Password must be 8 to 12 characters" />
                    <input type="password" name="newPassConfirm" class="form-control" placeholder="confirm password" pattern=".{8,12}" required title="Password must be 8 to 12 characters" />
                    <button type="submit" class="btn btn-secondary" style="margin-top: 10px">Submit</button>
                </form>
                <form method="post" action="">
                    <p style="margin-top: 30px">Delete account:</p>
                    <button type="submit" class="btn btn-secondary" name="deleteAcc" style="margin-top: 10px">Delete</button>
                </form>
            </div>

        </div>

    </div>
    <?php include("partials/footer.php") ?>
</body>

</html>