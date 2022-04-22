<?php
require_once "./db/connect.php";
require_once "./common/functions.php";
$title="User Login";
require_once "./common/header.php";
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
              <a class="btn btn-default btn-lg" href="#">Register</a>
            </div>
          </div>
          <div class="col-sm-8">
            <h4>RETURNING CUSTOMER</h4>
            <form enctype="multipart/form-data" method="post" action="#">
              <div class="form-group">
                <label for="input-email" class="control-label">Enter your Email Address</label>
                <input type="text" class="form-control" id="input-email" value="" name="email" vk_14c95="subscribed">
              </div>
              <div class="form-group">
                <label for="input-password" class="control-label">Enter your Password</label>
                <input type="password" class="form-control" id="input-password" value="" name="password" vk_14c95="subscribed">
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
<?php include_once "./common/footer.php";?>