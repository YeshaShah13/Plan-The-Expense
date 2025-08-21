<?php
require('config.php');

$success_msg = "";
$error_msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $firstname = mysqli_real_escape_string($con, $_POST['firstname']);
  $lastname = mysqli_real_escape_string($con, $_POST['lastname']);
  $email = mysqli_real_escape_string($con, $_POST['email']);
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

  if ($password !== $confirm_password) {
    $error_msg = "Passwords do not match!";
  } else {
    $email_check_query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($con, $email_check_query);

    if (mysqli_num_rows($result) > 0) {
      $error_msg = "Email already registered!";
    } else {
      $password_hash = md5($password); // For production, use password_hash
      $trn_date = date("Y-m-d H:i:s");

      $query = "INSERT INTO users (firstname, lastname, email, password, trn_date)
                VALUES ('$firstname', '$lastname', '$email', '$password_hash', '$trn_date')";

      if (mysqli_query($con, $query)) {
        header("Location: login.php?success=1");
        exit();
      } else {
        $error_msg = "Registration failed. Please try again.";
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - User Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .register-container {
      max-width: 450px;
      margin: 60px auto;
      background: #fff;
      border-radius: 8px;
      padding: 30px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .register-container h3 {
      text-align: center;
      margin-bottom: 25px;
      color: #333;
    }

    .form-control {
      border-radius: 0.375rem;
    }

    .btn-primary {
      width: 100%;
      border-radius: 0.375rem;
      padding: 10px;
    }

    .text-small {
      font-size: 0.875rem;
    }

    .form-error {
      color: red;
      font-size: 0.9rem;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>

  <div class="register-container">
    <h3>Create Your Account</h3>

    <?php if ($error_msg): ?>
      <div class="form-error"><?php echo $error_msg; ?></div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
      <div class="row mb-3">
        <div class="col">
          <input type="text" name="firstname" class="form-control" placeholder="First Name" required>
        </div>
        <div class="col">
          <input type="text" name="lastname" class="form-control" placeholder="Last Name" required>
        </div>
      </div>
      <div class="mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email address" required>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
      </div>
      <div class="mb-3">
        <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
      </div>
      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" required>
        <label class="form-check-label text-small">I agree to the <a href="#">terms and conditions</a>.</label>
      </div>
      <div class="mb-3">
        <button type="submit" class="btn btn-primary">Sign Up</button>
      </div>
    </form>

    <div class="text-center text-small">
      Already have an account? <a href="login.php">Login here</a>
    </div>
  </div>

  <script src="js/bootstrap.min.js"></script>
</body>
</html>
