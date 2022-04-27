<?php
require_once "../../includes/verify_login.php";
require_once "../../db/connect.php";
require_once "../../includes/session.php";
//GET ADDRESS
function get_address($id, $connect)
{
    $q = $connect->prepare("SELECT company,address1,address2,city,postcode,state,country FROM address WHERE id=:id");
    $q->execute([':id' => $id]);
    $data = $q->fetch(PDO::FETCH_ASSOC);
    return implode(', ', array_filter($data));
}
//CANCEL
if (isset($_GET['cl']) && $_GET['cl'] > 0) {
    $q1 = $connect->prepare("UPDATE ordersummery SET order_status=:status WHERE id=:id");
    if ($q1->execute([':status' => -1, ':id' => $_GET['cl']])) {
        set_session('order_success', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        Order id: ' . $_GET['cl'] . ' canceled successfully ! 
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
    }
    header('location:index.php');
    return 0;
}
//DELETE
if (isset($_GET['dl']) && $_GET['dl'] > 0) {
    $q2 = $connect->prepare("UPDATE ordersummery SET isactive=:active WHERE id=:id");
    if ($q2->execute([':active' => -1, ':id' => $_GET['dl']])) {
        set_session('order_success', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        Order id: ' . $_GET['dl'] . ' deleted successfully ! 
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
    }
    header('location:index.php');
    return 0;
}



//GET ORDER
$q = $connect->prepare("SELECT o.id,o.shipping_id,o.created,o.total,os.name as status,o.order_status,c.firstname,c.lastname FROM ordersummery o JOIN customer c ON o.customer_id=c.id JOIN order_status os ON o.order_status=os.id WHERE o.isactive=:o_active ORDER BY created DESC");
$q->execute([':o_active' => 1]);
$order = $q->fetchAll(PDO::FETCH_OBJ);

$title = "Order Management";
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
                    <h1 class="m-0">Order</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Dashboard</a></li>
                        <li class="breadcrumb-item">Order</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?=show_flash('order_success') ?>
            <?=show_flash('order_edit_warning') ?>
            <?=show_flash('order_edit_error') ?>
            <!-- general form elements -->
            <div class="card card-outline card-info">
                <div class="card-body">
                    <form action="" method="GET" class="">
                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-2">
                                <input type="text" class="form-control" name="srh" placeholder="Search Order id ...">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-success form-control">Search</button>
                            </div>
                        </div>
                    </form>



                    <div class="rounded-top" style="border-top: solid 3px #17a2b8;">
                        <div class="row mt-4">
                            <table class="table-sm table-bordered table-striped table-hover col-md-11 mx-auto rounded">
                                <thead>
                                    <tr class="text-center table-secondary">
                                        <th>#</th>
                                        <th>Order ID</th>
                                        <th class="text-left col-md-3">Deliver to</th>
                                        <th>Date</th>
                                        <th>Price (&dollar;)</th>
                                        <th>Status</th>
                                        <th class="text-left">Who Ordered</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($order as $key => $l) : ?>
                                        <tr class="text-center">
                                            <td><?= $key + 1 ?></td>
                                            <td><?= $l->id ?></td>
                                            <td class="text-left "><?= get_address($l->shipping_id, $connect) ?></td>
                                            <td><?= date("d-m-Y", strtotime($l->created)) ?></td>
                                            <td><?= $l->total ?></td>
                                            <td><span class="badge badge-info"><?= $l->status ?></span></td>
                                            <td class="text-left"><?= $l->firstname . ' ' . $l->lastname ?></td>
                                            <td>
                                                <a href="edit.php?id=<?= $l->id ?>" class="btn btn-sm btn-primary <?= $l->order_status < 0 ? 'disabled' : '' ?>" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
                                                <a href="index.php?cl=<?= $l->id ?>" class="btn btn-sm btn-warning <?= $l->order_status < 0 ? 'disabled' : '' ?>" data-toggle="tooltip" title="Cancel"><i class="fa fa-times"></i></a>
                                                <a href="index.php?dl=<?= $l->id ?>" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Delete"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php
include_once "../../includes/footer_content.php";
require_once "../../includes/footer.php";
?>