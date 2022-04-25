<?php
require_once "./common/functions.php";
verify_login();
require_once "./db/connect.php";
if(isset($_POST['add_address'])){
    // var_dump($_SERVER);exit;
    session_status()==1?session_start():'';
    $customer_id = $_SESSION['user_id'];
    add_customer_address($_POST,$customer_id,$_SERVER['HTTP_REFERER'],$connect);
}
if(isset($_POST['billing']) || isset($_POST['shipping'])){
    session_status()==1?session_start():'';
    if(isset($_POST['billing'])){
        $_SESSION["billing_address"]=$_POST['address'];
        header('location:address.php?addr=2');
    }
    if(isset($_POST['shipping'])){
        $_SESSION["shipping_address"]=$_POST['address'];
        header('location:payment.php');
    }
    // var_dump($_POST);exit;
}
if (isset($_GET['addr']) && ($_GET['addr'] == 1 || $_GET['addr'] == 2)) {
    $addr = $_GET['addr'];
    if ($addr == 1) {
        $title = "Billing Address";
        $btn_name="billing";
    }
    if ($addr == 2) {
        $title = "Shipping Address";
        $btn_name="shipping";
    }
} else {
    header('location:login.php');
}




require_once "./common/header.php";
include_once "./common/navbar.php";
$customer_id = $_SESSION['user_id'];
$address = get_customer_address($customer_id, $connect);
?>

<style>
    .row-eq-height {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        flex-wrap: wrap;
    }
</style>
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
        <form action="" method="post">
            <div class="row row-eq-height">
                <?php foreach ($address as $l) : ?>
                    <div class="col-sm-3 " style="padding-bottom:19px;">
                        <div class="panel panel-default " style="height: 100%;" >
                            <div class="panel-body" style="height: 84%;">
                                <p>
                                    <strong> Address: </strong><?= $l->company ? $l->company . ',' : '' ?> <?= $l->address1 ?>, <?= $l->address2 ? $l->address2 . ',' : '' ?> <?= $l->city ?> - <?= $l->postcode ?>
                                </p>
                                <p>
                                    <strong>Post code :</strong> <?= $l->postcode ?>
                                </p>
                                <p>
                                    <strong>State :</strong> <?= $l->state ?>
                                </p>
                                <p>
                                    <strong>Country :</strong> <?= $l->country ?>
                                </p>
                            </div>
                            <div class="panel-footer text-center">
                                <input type="radio" name="address" value="<?= $l->id ?>" required>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="col-md-3 " style="padding-bottom:19px;">
                    <div class="panel panel-default" style="height: 100%;">
                        <div class="panel-body" style="HEIGHT:100%;display:flex;align-items:center;justify-content:center;">
                            <div>
                                <button data-toggle="modal" data-target="#flipFlop" class="btn btn-success" type="button"><i class="fa fa-plus-circle"></i> &nbsp;ADD NEW ADDRESS</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary" type="submit" name="<?=$btn_name?>">NEXT</button>
        </form>
    </div>
</div>

<div class="modal fade" id="flipFlop" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close" style="font-size:36px;"></i></button>
            </div>
            <form action="" method="post">
                <div class="modal-body">
                    <fieldset id="address">
                        <legend>Your Address</legend>
                        <div class="form-group">
                            <label for="input-company" class=" col-sm-3 control-label">Company</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="input-company" placeholder="Company" value="" name="company">
                            </div>
                        </div>
                        <br><br>
                        <div class="form-group required">
                            <label for="input-address-1" class="col-sm-3 control-label">Address 1</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="input-address-1" placeholder="Address 1" value="" name="address_1" required>
                            </div>
                        </div><br><br>
                        <div class="form-group">
                            <label for="input-address-2" class="col-sm-3 control-label">Address 2</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="input-address-2" placeholder="Address 2" value="" name="address_2">
                            </div>
                        </div><br><br>
                        <div class="form-group required">
                            <label for="input-city" class="col-sm-3 control-label">City</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="input-city" placeholder="City" value="" name="city" required>
                            </div>
                        </div><br><br>
                        <div class="form-group required">
                            <label for="input-postcode" class="col-sm-3 control-label">Post Code</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="input-postcode" placeholder="Post Code" value="" name="postcode" required>
                            </div>
                        </div><br><br>
                        <div class="form-group required">
                            <label for="input-country" class="col-sm-3 control-label">Country</label>
                            <div class="col-sm-9">
                                <select class="form-control" id="input-country" name="country" required>
                                    <option value=""> --- Please Select --- </option>
                                    <option value="India">India</option>
                                </select>
                            </div>
                        </div><br><br>
                        <div class="form-group required">
                            <label for="input-zone" class="col-sm-3 control-label">Region / State</label>
                            <div class="col-sm-9">
                                <select class="form-control" id="input-zone" name="state" required>
                                    <option value=""> --- Please Select --- </option>
                                    <option value="West Bengal">West Bengal</option>
                                    <option value="Delhi">Delhi</option>
                                    <option value="Maharastra">Maharastra</option>
                                    <option value="Tamilnadu">Tamilnadu</option>
                                    <option value="Bihar">Bihar</option>
                                    <option value="Jharkhand">Jharkhand</option>
                                    <option value="UP">UP</option>
                                    <option value="HP">HP</option>
                                </select>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-info" name="add_address">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once "./common/footer.php" ?>