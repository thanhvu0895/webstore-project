<?php
session_start();
include("connection.php");


/**
 *  IMPLEMENT SIGN IN
 *  Duuren created this function
 *  Thanh revised SQL query to bind statement and display alert 02/11/22
 */


if (isset($_POST["signup"])) {
  if ($_POST["full_name"] == "" or $_POST["pass_word"] == "" or $_POST["email"] == "") {
    $messageEmpty = "<div class='alert alert-warning alert-dismissable'>
    <a href='signin.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a> 
    <strong>Error: </strong> Email, Password or Username cannot be blank. 
  </div>";
  } else {
    try {
      $email = trim($_POST["email"]);
      $full_name = trim($_POST["full_name"]);
      $pass_word = strip_tags(trim($_POST["pass_word"]));
      $hashed_password = password_hash($pass_word, PASSWORD_DEFAULT);

      $stmt = $conn->query("SELECT email FROM User WHERE email = '$email'");
      if ($stmt->rowCount() > 0) {
        $emailExisted = "<div class='alert alert-warning alert-dismissable'>
                          <a href='signin.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a> 
                          <strong>Error: </strong> Username $email has been taken!
                        </div>";
      } else {
    /**
     *  IMPLEMENT PASSWORD HASHING
     *  Thanh Vu revised to add form validation
     */
	 
	 $conn->beginTransaction();
	 $sql = ("INSERT INTO User (email, full_name, pass_word) VALUES (?, ?, ?)");
	 $statement = $conn->prepare($sql);
	 $statement->bindValue(1, $email);
	 $statement->bindValue(2, $full_name);
	 $statement->bindValue(3, $hashed_password);
	 $statement->execute();
	 $conn->commit();
	
	 $userCreated = "<div class='alert alert-warning alert-dismissable'>
		<a href='signin.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a> 
		New user $email has been created! 
	 </div>";
    }
    } catch (PDOException $e) {
      header("Location: error.php?error=Connection failed:" . $e->getMessage());
    }
  }
}

// Close connection to save resources
$conn = null;
?>

<!DOCTYPE html>
<html lang="en">

<!-- AVOID FORM RESUBMISSION UPON PAGE REFRESH-->
<script>
 if (window.history.replaceState) {
	window.history.replaceState(null, null, window.location.href);
 }
</script>
<!-- END SCRIPT - PAGE REFRESH-->


<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../css/signup.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" />
  <title>Sign up</title>
</head>

<body>
  <div id="root">
    <?php include("partials/header.php") ?>
    <?php include("partials/menu.php") ?>
    <div class="catalog">
      <div class="login-section">
        <div class="login-window">
          <div class="d-flex flex-column">
            <h3>Sign up</h3>
            <div style="display: flex; flex-direction: column; margin-top: 20px; justify-content: space-between">
            
            <!-- IF THERE IS AN ERROR for the user or password information, then display this --> 
            <?php
              echo (!empty($messageEmpty)) ?  $messageEmpty : '';
              echo (!empty($messageEmptyPass)) ?  $messageEmptyPass : '';
              echo (!empty($emailExisted)) ?  $emailExisted : '';
              echo (!empty($userCreated )) ?  $userCreated  : '';
            ?>
            <!-- END display error -->

              <form method="POST">
                <div style="margin-bottom: 10px">
                  <p>Email address</p>
                  <input type="email" name="email" class="form-control" placeholder="" required/>
                </div>
                <div style="margin-bottom: 10px">
                  <p>Full name</p>
                  <input type="text" name="full_name" class="form-control" placeholder="" required/>
                </div>
                <div style="margin-bottom: 10px">
                  <p>Password</p>
                  <input type="password" name="pass_word" class="form-control" pattern=".{8,12}" required title="Password must be 8 to 12 characters" />
                </div>
                <div style="margin-top: 30px">
                  <button type="submit" class="btn btn-secondary" name="signup" required>Sign up</button>
                  <a href="signin.php">Sign in</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include("partials/footer.php") ?>
  </div>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</body>

</html>