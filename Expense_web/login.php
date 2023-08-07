<?php
require('config.php');
session_start();
$errormsg = "";
if (isset($_POST['email'])) {

  // The stripslashes() function is used to remove backslashes (\)
  $email = stripslashes($_REQUEST['email']);
  // to prevent sql injection
  $email = mysqli_real_escape_string($con, $email);

  $password = stripslashes($_REQUEST['password']);
  $password = mysqli_real_escape_string($con, $password);

  // data exist in database?
  $query = "SELECT * FROM `users` WHERE email='$email'and password='" . md5($password) . "'";
  $result = mysqli_query($con, $query) or die(mysqli_error($con));
  
  $rows = mysqli_num_rows($result);
  if ($rows == 1) {
    $_SESSION['email'] = $email;
    header("Location: index.php");
  } else {
    $errormsg  = "Wrong email or password.";
    echo "<script>alert('$errormsg');</script>";  }
} else {
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Login</title>

  <!-- Bootstrap core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <style>

  body{
  background-image: url("images/gif1.gif");
  background-repeat: no-repeat;
  background-size: 40% auto; 
  background-color: #f9afd1;
  background-position: 18% 40%;

}    
    .login-form {
      width: 400px;
      margin: 200px 51%;
      font-size: 15px;
    }

    .login-form form {
      margin-bottom: 15px;
      background: #fff;
      box-shadow: 6px 3px 4px rgba(0, 0, 0, 0.3);
      padding: 60px 50px;
      border: 1px solid #ddd;
    }

    .login-form h2 {
      color: #636363;
      margin: 0 0 15px;
      position: relative;
      text-align: center;
    }

    .login-form h2:before,
    .login-form h2:after {
      content: "";
      height: 2px;
      width: 25%;
      background: #d4d4d4;
      position: absolute;
      top: 50%;
      z-index: 2;
    }

    .login-form h2:before {
      left: 0;
    }

    .login-form h2:after {
      right: 0;
    }

    .login-form .hint-text {
      color: #999;
      margin-bottom: 30px;
      text-align: center;
    }

    .login-form a:hover {
      text-decoration:underline;
    }

    .form-control,
    .btn {
      min-height: 38px;
      border-radius: 2px;
    }

    .btn {
      font-size: 18px;
      font-weight: bold;
    }


  </style>
</head>

<body>
  <div class="login-form">
    <form action="" method="POST" autocomplete="off">
      <h2 class="text-center">Welcome</h2>
      <p class="hint-text">Login Panel</p>
      <div class="form-group">
        <input type="text" name="email" class="form-control" placeholder="Email" required="required">
      </div>
      <div class="form-group">
        <input type="password" name="password" class="form-control" placeholder="Password" required="required">
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-success btn-block" style="border-radius:0%;">Login</button>
      </div>
      <div class="clearfix">
        <label class="float-left form-check-label " style="zoom: 1.1;"><input type="checkbox"> Remember me</label>
        
      </div>
    </form>
    <p class="text-center">Don't have an account?<a href="register.php" class="text-danger font-weight-bold" > Register Here</a></p>
    
  </div>
</body>
<!-- Bootstrap core JavaScript -->
<script src="js/jquery.slim.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<!-- Menu Toggle Script -->
<script>
  $("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
  });
</script>
<script>
  feather.replace()
</script>

</html>