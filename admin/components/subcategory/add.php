<?php
require_once "../../includes/verify_login.php";
require_once "../../db/connect.php";
require_once "../../includes/session.php";
function get_subcategory($id, $connect)
{
    $q = $connect->prepare('SELECT * FROM category WHERE id=:id AND isactive>:isactive AND parent>:parent');
    $q->execute([':id' => $id, ':isactive' => -1,':parent'=>0]);
    if ($q->rowCount() > 0) {
        return $q->fetch(PDO::FETCH_OBJ);
    } else {
        set_flash_session(
            'subcategory_error',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Subcategory Not Found !
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>'
        );
        header('location:index.php');
    }
}
$subcategory = 0;
$subcategory_id = 0;
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $subcategory_id = $_GET['id'];
    $subcategory = get_subcategory($subcategory_id, $connect);
    $heading = "Edit";
    $btn_text = "Update";
} else {
    $heading = "Add";
    $btn_text = "Add";
}


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if ($_POST['id'] > 0) {
        $subcategory_id = $_POST['id'];
        $subcategory = get_subcategory($subcategory_id, $connect);
        $sql = "UPDATE category SET name=?,parent=?,categoryorder=?,extension=?,isactive=? WHERE id=?";
    } else {
        $sql = "INSERT INTO category (name,parent,categoryorder,extension,isactive) values(?,?,?,?,?)";
    }

    $q = $connect->prepare($sql);
    $q->bindValue(1, $_POST['name']);
    $q->bindValue(2, $_POST['parent']);
    $q->bindValue(3, $_POST['categoryorder']);
    $q->bindValue(4, '');
    $q->bindValue(5, $_POST['isactive']);
    $subcategory_id > 0 ? $q->bindValue(6, $subcategory_id) : '';


    if ($q->execute()) {
        set_flash_session(
            'subcategory_success',
            '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Subcategory ' . $btn_text . 'ed Successfully !
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>'
        );
    } else {
        set_flash_session(
            'subcategory_error',
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


//GET CATEGORY LIST
$q = $connect->prepare("select id,name from category where parent=:parent and isactive=:isactive");
$q->execute([':parent' => 0, ':isactive' => 1]);
$category_list = $q->fetchAll(PDO::FETCH_OBJ);
?>


<?php
$title = "Agri Express > Subcategory > $heading Subcategory";
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
                    <h1 class="m-0"><?= $heading ?> Subcategory</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/components/subcategory">Subcategory</a></li>
                        <li class="breadcrumb-item active"><?= $heading ?> Subcategory</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?= show_flash('subcatagory_warning') ?>
            <!-- general form elements -->
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title"><?= $heading ?> Subcategory</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $subcategory_id ?>">
                    <div class="card-body">
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>Category <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-10">
                                <select name="parent" class="form-control text-uppercase" required>
                                    <option value="" disabled selected>---Select a category---</option>
                                    <?php foreach ($category_list as $l) : ?>
                                        <option value="<?= $l->id ?>" <?=$subcategory && $l->id==$subcategory->parent?"selected":""?>><?= $l->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>Name <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control" placeholder="Enter category name" name="name" value="<?= $subcategory ? $subcategory->name : '' ?>" required>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>Order <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-10">
                                <input type="number" class="form-control" placeholder="Enter category Order" name="categoryorder" value="<?= $subcategory ? $subcategory->categoryorder : 0 ?>" required>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>Status <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-10">
                                <select name="isactive" class="form-control text-uppercase" required>
                                    <option value="1" <?= $subcategory && $subcategory->isactive == "1" ? 'selected' : '' ?>>Active</option>
                                    <option value="0" <?= $subcategory && $subcategory->isactive == "0" ? 'selected' : '' ?>>Deactive</option>
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
    $(function() {
        bsCustomFileInput.init();
    });
</script>