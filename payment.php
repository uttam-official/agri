<?php
require_once "./common/functions.php";
verify_login();
session_status() == 1 ? session_start() : '';
if (isset($_SESSION['checkout'])) {
    $total = $_SESSION['checkout']['total'];
    $status = true;
} else {
    $total = 0;
    $status = false;
}
require_once "./db/connect.php";


if($_SERVER['REQUEST_METHOD']=="POST"){
    $_SESSION['payment']=$_POST['payment'];
    // var_dump($_SESSION);exit;
    confirm_order($connect);
}



$title = "Payment";
require_once "./common/header.php";
if (!$status) {
    echo "<script>Swal.fire({icon:'warning',title:'Oops',text:'plaese Checkout first...'}).then(function(){window.location='cart.php'})</script>";
}
include_once "./common/navbar.php";
?>
<div class="banner-in">
    <div class="container">
        <h1><?= $title ?></h1>
        <ul class="newbreadcrumb">
            <li><a href="<?= BASE_URL ?>">Home</a></li>
            <li><?= $title ?></li>
        </ul>
    </div>
</div>
<div id="main-container">
    <div class="container">
        <h3>Amount Payable: &dollar;<?= $total; ?></h3>
        <form action="" method="post">
            <div class="form-group">
                <h4><u>Please select a payment method</u></h4>
                <br>
                <label class="radio-inline "><input type="radio" value="1" name="payment" class="form-radio" required/> Cash on delivery</label>
            </div>
            <br><br>
            <div>
                <a class="btn btn-danger" href="cancel_order.php">Cancel Order</a><button type="submit" class="btn btn-primary">Confirm Order</button>
            </div>

        </form>
    </div>
</div>
<?php include_once "./common/footer.php" ?>