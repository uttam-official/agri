<?php
require_once "../../includes/verify_login.php";
require_once "../../db/connect.php";
require_once "../../includes/session.php";
function get_discount($id, $connect)
{
    $q = $connect->prepare('SELECT * FROM discount WHERE id=:id AND isactive>:isactive');
    $q->execute([':id' => $id, ':isactive' => -1]);
    if ($q->rowCount() > 0) {
        return $q->fetch(PDO::FETCH_OBJ);
    } else {
        set_flash_session(
            'discount_error',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Discount Not Found !
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>'
        );
        header('location:index.php');
    }
}
$discount = 0;
$discount_id = 0;
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $discount_id = $_GET['id'];
    $discount = get_discount($discount_id, $connect);
    $heading = "Edit";
    $btn_text = "Update";
} else {
    $heading = "Add";
    $btn_text = "Add";
}


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // var_dump($_POST);exit;
    if ($_POST['id'] > 0) {
        $discount_id = $_POST['id'];
        $discount = get_discount($discount_id, $connect);
        $sql = "UPDATE discount SET name=?,validfrom=?,validtill=?,type=?,amount=?,isactive=? WHERE id=?";
    } else {
        $sql = "INSERT INTO discount (name,validfrom,validtill,type,amount,isactive) values(?,?,?,?,?,?)";
    }
    $q = $connect->prepare($sql);
    $q->bindValue(1, $_POST['name']);
    $q->bindValue(2, $_POST['validfrom'] != "" ? $_POST['validfrom'] : null);
    $q->bindValue(3, $_POST['validtill'] != "" ? $_POST['validtill'] : null);
    $q->bindValue(4, $_POST['type']);
    $q->bindValue(5, $_POST['amount']);
    $q->bindValue(6, $_POST['isactive']);
    $discount_id > 0 ? $q->bindValue(7, $discount_id) : '';
    // $q->execute();
    // $q->queryString;exit;
    if ($q->execute()) {
        set_flash_session(
            'discount_success',
            '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Discount ' . $btn_text . 'ed Successfully !
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>'
        );
    } else {
        set_flash_session(
            'discount_error',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Something wents wrong....  please try after some time ....
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>'
        );
    }
    header('location:index.php');
}
?>


<?php
$title = "Agri Express > Discount > $heading Discount";
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
                    <h1 class="m-0"><?= $heading ?> Discount</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/components/discount">Discount</a></li>
                        <li class="breadcrumb-item active"><?= $heading ?> Discount</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?= show_flash('discount_warning') ?>
            <!-- general form elements -->
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title"><?= $heading ?> Discount</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="" method="POST" onsubmit="return validation()">
                    <div id="error" class="mt-3 mx-1"></div>

                    <input type="hidden" name="id" value="<?= $discount_id ?>">
                    <div class="card-body">
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>Name <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control" placeholder="Enter discount coupon name" name="name" value="<?= $discount ? $discount->name : '' ?>" required>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>Valid From</label>
                            </div>
                            <div class="col-md-10">
                                <input type="date" class="form-control" name="validfrom" id="valid_from" value="<?= $discount ? $discount->validfrom : '' ?>">
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>Valid Till</label>
                            </div>
                            <div class="col-md-10">
                                <input type="date" class="form-control" name="validtill" id="valid_till" value="<?= $discount ? $discount->validtill : '' ?>">
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>Type <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-10">
                                <select name="type" class="form-control text-uppercase" required>
                                    <option value="1" <?= $discount && $discount->type == "1" ? 'selected' : '' ?>>Fixed</option>
                                    <option value="2" <?= $discount && $discount->type == "2" ? 'selected' : '' ?>>Percentage</option>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>Amount <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-10">
                                <input type="number" class="form-control" placeholder="Enter discount coupon amount" name="amount" value="<?= $discount ? $discount->amount : '' ?>" required>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>Status <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-10">
                                <select name="isactive" class="form-control text-uppercase" required>
                                    <option value="1" <?= $discount && $discount->isactive == "1" ? 'selected' : '' ?>>Active</option>
                                    <option value="0" <?= $discount && $discount->isactive == "0" ? 'selected' : '' ?>>Deactive</option>
                                </select>
                            </div>
                        </div>
                        <div class=" text-center">
                            <button type="submit" class="btn btn-sm btn-success"><?= $btn_text ?></button>&nbsp;&nbsp;<button type="reset" class="btn btn-sm btn-warning text-white">Reset</button>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </form>
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
<script>
    function validation() {
        console.log('submitted');
        $('#error').empty();
        var from = $('#valid_from').val();
        var to = $('#valid_till').val();
        if (Date.parse(from) > Date.parse(to)) {
            $('#error').html('<p class="alert alert-danger">Valid till date should greter than valid from date !</p>');
            return false;
        }
        return true;
    }
</script>
<script>
    $(function() {
        bsCustomFileInput.init();
    });
</script>