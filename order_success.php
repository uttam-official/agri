<?php
require_once './common/functions.php';
verify_login();
require_once "./db/connect.php";
session_status() == 1 ? session_start() : '';
if (isset($_SESSION['success_order_id'])) {
    $order_id = $_SESSION['success_order_id'];
    unset($_SESSION['success_order_id']);
} else {
    header('location:index.php');
}
$title = "Order Successful";
require_once "./common/header.php";
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
        <h2 class="text-primary h2">Thank You !</h2>
        <br>
        <p class="h3">Your order number <strong class="text-info"><u><?=$order_id?></u></strong> has been successfully placed, Please check email for further details. </p>
        <br>
        <a href="index.php" class="btn btn-primary">GO TO HOME</a>
        <br><br>
    </div>
</div>



<?php include_once "./common/footer.php";?>
