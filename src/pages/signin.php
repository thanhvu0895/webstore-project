<?php
session_start();
include("connection.php");


/**
 *  IMPLEMENT SIGN IN
 *  Duuren created this function
 *  Thanh Vu revised SQL query string and alert display
 */

if (isset($_POST["signin"])) {
  if ($_POST["pass_word"] == "" or $_POST["email"] == "") {
    $messageEmptyPass = "<div class='alert alert-warning alert-dismissable'>
    <a href='signin.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a> 
    <strong>Error: </strong> Email or password cannot be empty. 
  </div>";
  } else {
    $email = trim($_POST["email"]);
    $pass_word = strip_tags(trim($_POST["pass_word"]));
    $stmt = $conn->query("SELECT id, pass_word, full_name FROM User WHERE email = '$email'");
    $result = $stmt->fetch();
    $hashedPassword = $result['pass_word'] ?? '';
    $userName = $result['full_name'] ?? '';
    $userId = $result['id'] ?? '';

    /**
     * IMPLEMENTING PASSWORD HASHING
     * Thanh Vu modified hashing variables and saving sessions on 11/05/2022
     */
    if (password_verify($pass_word, $hashedPassword)) {
      $query = $conn->prepare("SELECT * FROM User WHERE email=? and pass_word=?");
      $query->execute(array($email, $hashedPassword));
      $control = $query->fetch(PDO::FETCH_OBJ);
      if ($control > 0) {
        $_SESSION["email"] = $email;
        $_SESSION['username'] = $userName;
        $_SESSION['userid'] = $userId;
        header("Location: index.php");
      }
    }
    
    $messageIncorrectPass =  "<div class='alert alert-warning alert-dismissable'>
    <a href='signin.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a> 
    <strong>Error: </strong> E-mail or password did not match our records. 
  </div>";
  }
} 

// Close connection to save resources
$conn = null;
?>

<!DOCTYPE html>
<html lang="en">

<!-- AVOID FORM RESUBMISSION UPON PAGE REFRESH-->
<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>
<!-- END SCRIPT - PAGE REFRESH-->

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../css/signin.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" />
  <title>Log in</title>
</head>

<body>
  <div id="root">
    <?php include("partials/header.php") ?>
    <?php include("partials/menu.php") ?>
    <div class="catalog">
      <div class="login-section">
        <div class="login-window">
          <div class="d-flex flex-column">
            <h3>Sign in</h3>
            <div style="display: flex; flex-direction: column; margin-top: 20px; justify-content: space-between">
           
            <!-- IF THERE IS AN ERROR for the user or password information, then display this --> 
            <?php 
              echo (!empty($messageIncorrectPass)) ? $messageIncorrectPass : '';
              echo (!empty($messageEmptyPass)) ?  $messageEmptyPass : '';
            ?>
            <!-- END display error -->
           
            <form method="POST">
                <div style="margin-bottom: 10px">
                  <p>Email address</p>
                  <input type="email" name="email" class="form-control" placeholder="" required/>
                </div>
                <div style="margin-bottom: 10px">
                  <p>Password</p>
                  <input type="password" name="pass_word" class="form-control" placeholder="" required/>
                </div>
                <div style="margin-top: 30px">
                  <button type="submit" class="btn btn-secondary" name="signin">Sign in</button>
                  <a href="signup.php">Sign up</a>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>

    <?php include("partials/footer.php") ?>
  </div>  <!-- end container -->



<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</body>
</html>