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
//CANCEL
if(isset($_POST['cl'])){
    $q1=$connect->prepare("UPDATE ordersummery SET order_status=:status WHERE id=:id");
   if($q1->execute([':status'=>-1,':id'=>$_POST['cl']])){
       echo json_encode(['status'=>1]);
   }else{
    echo json_encode(['status'=>0]);
   }
   return 0;
}

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $q = $connect->prepare("SELECT * FROM ordersummery WHERE customer_id=:id ORDER BY created DESC");
    $q->execute([':id' => $_GET['id']]);
    $order = $q->fetchAll(PDO::FETCH_OBJ);
    // var_dump($order);exit;
    if ($q->rowCount() > 0) {
    } else {
        set_flash_session('customer_warning', '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        Order not found !
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
        header('location:index.php');
    }
} else {
    set_flash_session('customer_error', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        Bad Request !
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
    header('location:index.php');
}



$title = "Customer Order Details";
require_once "../../includes/header.php";
// include_once "../../includes/preloader.php";
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
                    <h1 class="m-0">Order Details</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/components/customer">Customer</a></li>
                        <li class="breadcrumb-item">Order Details</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="rounded-top" style="border-top: solid 3px #17a2b8;">
                <div class="row mt-3">
                    <?php foreach ($order as $k => $l) : ?>
                        <div class="col-md-12 ">
                            <div class="nav-item bg-primary rounded col-md-12">
                                <a href="#order<?= $k?>" data-toggle="collapse" class="nav-link h5"><i class="fa fa-caret-down"> &nbsp; Order <?= $k + 1 ?></i></a>
                            </div>
                            <div id="order<?= $k ?>" class="collapse form-group row <?= $k == 0 ? 'show' : '' ?>">
                                <?php foreach (get_products($l->id, $connect) as $key => $sl) : ?>
                                    <div class="col-md-6">
                                        <label>Product <?= $key + 1 ?></label>
                                        <input type="text" class="form-control form-control-sm" value="<?= $sl->name ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Quantity</label>
                                        <input type="text" class="form-control form-control-sm" value="<?= $sl->quantity ?>" readonly>
                                    </div>
                                <?php endforeach; ?>
                                <div class="col-md-6">
                                    <label>Delivery Address</label>
                                    <input type="text" class="form-control form-control-sm" value="<?= get_address($l->shipping_id, $connect) ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label>Billing Address</label>
                                    <input type="text" class="form-control form-control-sm" value="<?= get_address($l->billing_id, $connect) ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label>Subtotal (&dollar;)</label>
                                    <input type="text" class="form-control form-control-sm" value="<?= $l->subtotal ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label>Discount (&dollar;)</label>
                                    <input type="text" class="form-control form-control-sm" value="<?= $l->discount ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label>Ecotax (&dollar;)</label>
                                    <input type="text" class="form-control form-control-sm" value="<?= $l->ecotax ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label>Vat (&dollar;)</label>
                                    <input type="text" class="form-control form-control-sm" value="<?= $l->vat ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label>Total (&dollar;)</label>
                                    <input type="text" class="form-control form-control-sm" value="<?= $l->total ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label>Payment Mode</label>
                                    <input type="text" class="form-control form-control-sm" value="Cash on delivery" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label>Payment Status</label>
                                    <input type="text" class="form-control form-control-sm" value="<?= $l->payment_status ? "Paid" : "Unpaid" ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label>Transaction Id</label>
                                    <input type="text" class="form-control form-control-sm" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label>Order Status</label>
                                    <input type="text" class="form-control form-control-sm" id="i<?=$l->id?>" value="<?= get_order_status($l->order_status, $connect) ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label>Order Date</label>
                                    <input type="text" class="form-control form-control-sm" value="<?= date("d-m-Y", strtotime($l->created)) ?>" readonly>
                                </div>
                                <div class="col-md-12 text-center mt-3">
                                    <a href="<?=BASE_URL?>/components/order/edit.php?id=<?=$l->id?>"id="e<?=$l->id?>"class="btn btn-sm btn-info <?=$l->order_status<0?'disabled':''?>"><i class="fa fa-edit"> Edit</i></a>&nbsp;&nbsp;
                                    <button data-name="<?=$l->id?>" class="btn btn-sm btn-danger cancel " type="button" <?=$l->order_status<0?'disabled':''?>><i class="fa fa-times"> Cancel</i></button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
</div>
<?php
include_once "../../includes/footer_content.php";
require_once "../../includes/footer.php";
?>


<script>
    $(document).ready(function(){
        $('.cancel').on('click',function(e){
            const id=$(this).attr('data-name');
            $(this).attr('disabled',true);
            var data={cl:id};
            $.ajax({
                url:'order.php',
                type:'post',
                dataType:'json',
                data:data,
                success:function(data){
                    if(data.status){
                        Swal.fire({icon:'success',title:'Yup...',text:`Order id: ${id} cancelled successfully`});
                        $('#i'+id).val('Canceled  By Seller');
                        $('#e'+id).addClass('disabled');
                    }
                },
                error:function(response){
                    console.log(response);
                }
            })
        })
    })
</script>