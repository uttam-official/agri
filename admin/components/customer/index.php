<?php
require_once "../../includes/verify_login.php";
require_once "../../db/connect.php";
require_once "../../includes/session.php";

//GET CUSTOMER
$q = $connect->prepare("SELECT c.id,c.firstname,c.lastname,c.email,c.phone,c.isactive,COUNT(o.id) AS no_of_order FROM customer c LEFT JOIN ordersummery o ON o.customer_id=c.id AND o.isactive=:o_active WHERE c.isactive>:c_active GROUP BY(c.id)");
$q->execute([':o_active' => 1, ':c_active' => -1]);
$customer = $q->fetchAll(PDO::FETCH_OBJ);
// var_dump($customer);exit;

$title = "Customer Management";
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
                    <h1 class="m-0">Customer</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Dashboard</a></li>
                        <li class="breadcrumb-item">Customer</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?= show_flash('customer_warning') ?>
            <?= show_flash('customer_success') ?>
            <!-- general form elements -->
            <div class="card card-outline card-info">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-center mb-2">
                        <a href="<?= BASE_URL ?>/components/customer" class="btn btn-success btn-sm mr-1 mb-1">ALL</a>
                        <?php
                        for ($char = ord('A'); $char <= ord('Z'); $char++) {
                            echo '<a href="' . BASE_URL . '/components/customer/index.php?search=' . chr($char) . '" class="btn btn-success btn-sm mr-1 mb-1">' . chr($char) . '</a>';
                        }
                        ?>
                    </div>
                    <form action="" method="GET" class="">
                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-2">
                                <input type="text" class="form-control" name="srh" placeholder="Search Customer ...">
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
                                        <th class="text-left ">Name</th>
                                        <th class="text-left">Mail</th>
                                        <th class="text-left">Telephone</th>
                                        <th>Status</th>
                                        <th>No. of order</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($customer as $key => $l) : ?>
                                        <tr class="text-center">
                                            <td><?= $key + 1 ?></td>
                                            <td class="text-left "><?= $l->firstname . ' ' . $l->lastname ?></td>
                                            <td class="text-left"><?= $l->email ?></td>
                                            <td class="text-left"><?= $l->phone ?></td>
                                            <td><?= $l->isactive ? '<span class="badge badge-primary">Active</span>' : '<span class="badge badge-secondary">Inactive</span>' ?></td>
                                            <td><?= $l->no_of_order ?></td>
                                            <td>
                                                <a href="edit.php?id=<?= $l->id ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
                                                <a href="order.php?id=<?= $l->id ?>" class="btn btn-sm btn-info" data-toggle="tooltip" title="Orders"><i class="fa fa-shopping-cart"></i></a>
                                                <a href="delete.php?id=<?= $l->id ?>" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Delete"><i class="fa fa-trash"></i></a>
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