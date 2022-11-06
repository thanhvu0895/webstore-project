<?php
session_start();

include("connection.php");
if (isset($_POST["signup"])) {
  if ($_POST["full_name"] == "" or $_POST["pass_word"] == "" or $_POST["email"] == "") {
    echo "<center><h1>Fullname, Email, and Password cannot be empty</h1></center>";
  } else {
    try {
      $email = trim($_POST["email"]);
      $full_name = trim($_POST["full_name"]);
      $pass_word = strip_tags(trim($_POST["pass_word"]));
      $hashed_password = password_hash($pass_word, PASSWORD_DEFAULT);

      $stmt = $conn->query("SELECT email FROM User WHERE email = '$email'");
      if ($stmt->rowCount() > 0) {
        echo "Email already exists!";
      } else {
        $query = $conn->exec("insert into User (email, full_name, pass_word) values ('$email', '$full_name', '$hashed_password')");
        echo "New record created successfully";
      }
    } catch (PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
    }
  }
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
              <form method="POST">
                <div style="margin-bottom: 10px">
                  <p>Email address</p>
                  <input type="text" name="email" class="form-control" placeholder="" />
                </div>
                <div style="margin-bottom: 10px">
                  <p>Full name</p>
                  <input type="text" name="full_name" class="form-control" placeholder="" />
                </div>
                <div style="margin-bottom: 10px">
                  <p>Password</p>
                  <input type="password" name="pass_word" class="form-control" placeholder="" />
                </div>
                <div style="margin-top: 30px">
                  <button type="submit" class="btn btn-secondary" name="signup">Sign up</button>
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
</body>

</html>