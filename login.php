<?php
require_once "./common/functions.php";
is_logged();
require_once "./db/connect.php";
session_status() == 1 ? session_start() : '';
$status = 0;
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $q = $connect->prepare('SELECT id,firstname,lastname,email,password FROM customer WHERE email=:email AND isactive=:active');
  $q->execute([':email' => $_POST['email'], ':active' => 1]);
  if ($q->rowCount() == 1) {
    $customer = $q->fetch(PDO::FETCH_OBJ);
    if (password_verify($_POST['password'], $customer->password)) {
      $_SESSION['user_id'] = $customer->id;
      $_SESSION['user_mail'] = $customer->email;
      $_SESSION['user_name'] = $customer->firstname.' '.$customer->lastname;
      $status = 1;
    } else {
      $status=2;
    }
  } else {
    $status = 2;
  }
}




$title = "User Login";
require_once "./common/header.php";
if ($status == 1) {
  echo "<script>
          Swal.fire({
            icon: 'success',
            title: 'Voila...',
            text: 'Login Successful ...'
          }).then(function() {
            window.location = 'address.php?addr=1';
          })
        </script>";
}
if ($status == 2) {
  echo "<script>Swal.fire({icon:'error',title:'Oops...',text:'Please enter correct email and password ...'});</script>";
}

include_once "./common/navbar.php";
?>


<div class="banner-in">
  <div class="container">
    <h1>Login</h1>
    <ul class="newbreadcrumb">
      <li><a href="#">Home</a></li>
      <li>Login</li>
    </ul>
  </div>
</div>
<div id="main-container">
  <div class="container">

    <div class="row">
      <div class="col-sm-12 login-page" id="content">
        <div class="row">
          <div class="col-sm-4">
            <div class="well">
              <h4>NEW CUSTOMER</h4>
              <p>By creating an account you will be able to shop faster, be up to date on an order's status, and keep track of the orders you have previously made.</p>
              <a class="btn btn-default btn-lg" href="<?= BASE_URL . 'register.php' ?>">Register</a>
            </div>
          </div>
          <div class="col-sm-8">
            <h4>RETURNING CUSTOMER</h4>
            <form enctype="multipart/form-data" method="post" action="">
              <div class="form-group">
                <label for="email" class="control-label">Enter your Email Address</label>
                <input type="text" class="form-control" id="email" value="" name="email">
              </div>
              <div class="form-group">
                <label for="password" class="control-label">Enter your Password</label>
                <input type="password" class="form-control" id="password" value="" name="password">
              </div>
              <div class="clearfix">
                <input type="submit" class="btn btn-default btn-lg pull-left" value="Login">
                <a class="pull-right" href="#">Forgotten Password</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include_once "./common/footer.php"; ?>