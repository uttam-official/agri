<?php
require_once "../../includes/verify_login.php";
require_once "../../db/connect.php";
require_once "../../includes/session.php";
function get_products($id, $connect)
{
    $q = $connect->prepare("SELECT p.name,o.quantity FROM orderinfo o JOIN product p ON o.product_id=p.id WHERE o.ordersummery_id=:id");
    $q->execute([':id' => $id]);
    return $q->fetchAll(PDO::FETCH_OBJ);
}
function get_address($id, $connect)
{
    $q = $connect->prepare("SELECT company,address1,address2,city,postcode,state,country FROM address WHERE id=:id");
    $q->execute([':id' => $id]);
    $data = $q->fetch(PDO::FETCH_ASSOC);
    return implode(',', array_filter($data));
}
function get_order_status($id, $connect)
{
    $q = $connect->prepare("SELECT name from order_status where id=:id");
    $q->execute([':id' => $id]);
    $data = $q->fetch(PDO::FETCH_OBJ);
    return $data->name;
}

if($_SERVER['REQUEST_METHOD']=='POST'){
    $q3=$connect->prepare("UPDATE ordersummery SET payment_status=:payment_status, order_status=:order_status WHERE id=:id");
    if($q3->execute([':payment_status'=>$_POST['payment_status'],':order_status'=>$_POST['order_status'],':id'=>$_POST['id']])){
        set_flash_session('order_edit_success', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        Order id:'.$_POST['id'].' edited successfully !
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
    }
}

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $q = $connect->prepare("SELECT * FROM ordersummery WHERE id=:id AND isactive=:active AND order_status>:order_status");
    $q->execute([':id' => $_GET['id'],':active'=>1,':order_status'=>0]);
    $order = $q->fetch(PDO::FETCH_OBJ);
    // var_dump($order);exit;
    if ($q->rowCount() > 0) {
    } else {
        set_flash_session('order_edit_warning', '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        You can\'t edit canceled or deleted order  !
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
        header('location:index.php');
    }
} else {
    set_flash_session('order_edit_error', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        Bad Request !
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
    header('location:index.php');
}


//GET ALL ORDER STATUS LIST
$q2=$connect->prepare("SELECT id,name FROM order_status WHERE isactive=:active AND id>:id");
$q2->execute([':active'=>1,':id'=>0]);
$order_status=$q2->fetchAll(PDO::FETCH_OBJ);


$title = "Edit Order Details";
require_once "../../includes/header.php";
include_once "../../includes/preloader.php";
include_once "../../includes/navbar.php";
include_once "../../includes/sidebar.php";
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Order</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/components/order">Order</a></li>
                        <li class="breadcrumb-item">Edit Order</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?=show_flash('order_edit_success')?>
            <div class="rounded-top" style="border-top: solid 3px #17a2b8;">
                <div class="row mt-1">
                    <p class="h5 col-md-12 text-primary">Product Details</p>
                    <form action="" method="POST" class="col-md-12 form-group row">
                        <input type="hidden" name="id" value="<?=$order->id?>">
                        <?php foreach (get_products($order->id, $connect) as $key => $sl) : ?>
                            <div class="col-md-6">
                                <label>Product <?= $key + 1 ?></label>
                                <input type="text" class="form-control form-control-sm" value="<?= $sl->name ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label>Quantity</label>
                                <input type="text" class="form-control form-control-sm" value="<?= $sl->quantity ?>" readonly>
                            </div>
                        <?php endforeach; ?>
                        <p class="h5 col-md-12 text-primary mt-3">Address Details</p>
                        <div class="col-md-6">
                            <label>Delivery Address</label>
                            <input type="text" class="form-control form-control-sm" value="<?= get_address($order->shipping_id, $connect) ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label>Billing Address</label>
                            <input type="text" class="form-control form-control-sm" value="<?= get_address($order->billing_id, $connect) ?>" readonly>
                        </div>
                        <p class="h5 col-md-12 text-primary mt-3">Billing Details</p>
                        <div class="col-md-6">
                            <label>Subtotal (&dollar;)</label>
                            <input type="text" class="form-control form-control-sm" value="<?= $order->subtotal ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label>Discount (&dollar;)</label>
                            <input type="text" class="form-control form-control-sm" value="<?= $order->discount ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label>Ecotax (&dollar;)</label>
                            <input type="text" class="form-control form-control-sm" value="<?= $order->ecotax ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label>Vat (&dollar;)</label>
                            <input type="text" class="form-control form-control-sm" value="<?= $order->vat ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label>Total (&dollar;)</label>
                            <input type="text" class="form-control form-control-sm" value="<?= $order->total ?>" readonly>
                        </div>
                        <p class="h5 col-md-12 text-primary mt-3">Payment Details</p>
                        <div class="col-md-6">
                            <label>Payment Mode</label>
                            <input type="text" class="form-control form-control-sm" value="Cash on delivery" readonly>
                        </div>
                        <div class="col-md-6">
                            <label>Payment Status</label>
                            <select name="payment_status" class="form-control form-control-sm">
                                <option value="1" <?=$order->payment_status==1?'selected':''?>>Successful</option>
                                <option value="0" <?=$order->payment_status==0?'selected':''?>>Unsuccessful</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Transaction Id</label>
                            <input type="text" class="form-control form-control-sm" readonly>
                        </div>
                        <div class="col-md-6">
                            <label>Order Status</label>
                            <select name="order_status" id="" class="form-control form-control-sm">
                                <option value="" disbled selected>---Select One---</option>
                                <?php 
                                foreach($order_status as $l):
                                    $_selected=$l->id==$order->order_status?"selected":'';
                                ?>
                                <option value="<?=$l->id?>" <?=$_selected?>><?=$l->name?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Order Date</label>
                            <input type="text" class="form-control form-control-sm" value="<?= date("d-m-Y", strtotime($order->created)) ?>" readonly>
                        </div>
                        <div class="col-md-12 text-center mt-3">
                            <button type="submit" class="btn btn-sm btn-success"> Save</button>&nbsp;&nbsp;
                            <button class="btn btn-sm btn-warning " type="reset">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</div>
</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php
include_once "../../includes/footer_content.php";
require_once "../../includes/footer.php";
?>