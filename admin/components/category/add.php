<?php
require_once "../../includes/verify_login.php";
require_once "../../db/connect.php";
require_once "../../includes/session.php";
function get_category($id, $connect)
{
    $q = $connect->prepare('SELECT * FROM category WHERE id=:id AND isactive>:isactive');
    $q->execute([':id' => $id, ':isactive' => -1]);
    if ($q->rowCount() > 0) {
        return $q->fetch(PDO::FETCH_OBJ);
    } else {
        set_flash_session(
            'category_error',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Category Not Found !
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>'
        );
        header('location:index.php');
    }
}
$category = 0;
$category_id = 0;
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $category_id = $_GET['id'];
    $category = get_category($category_id, $connect);
    $heading = "Edit";
    $btn_text = "Update";
} else {
    $heading = "Add";
    $btn_text = "Add";
}


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $files=(object) $_FILES['image'];
    // var_dump($files);exit;
    $category=(object) $_POST;
    if ($_POST['id'] == 0 && $files->error == 4) {
        set_flash_session(
            'catagory_warning',
            '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            Image is mandatory !
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>'
        );
    } elseif ($_POST['id'] == 0 && $files->type != 'image/jpeg' && $files->type != 'image/png') {
        set_flash_session(
            'catagory_warning',
            '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            Please upload Jpeg,Jpg,Png type Image !
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>'
        );
    } elseif ($files->error==0 && $files->size > 102400) {
        set_flash_session(
            'catagory_warning',
            '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            Maximum 100KB image allowed !
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>'
        );
    } else {
        $tmp_file =$files->tmp_name;
        $extension = $files->error==0?pathinfo($files->name, PATHINFO_EXTENSION):$category->extension;
        $upload_path = "../../dist/images/category/";
        
        if ($_POST['id'] > 0) {
            $category_id = $_POST['id'];
            $category = get_category($category_id, $connect);
            $sql = "UPDATE category SET name=?,parent=?,categoryorder=?,extension=?,isactive=? WHERE id=?";
        } else {
            $sql = "INSERT INTO category (name,parent,categoryorder,extension,isactive) values(?,?,?,?,?)";
        }

        $q = $connect->prepare($sql);
        $q->bindValue(1, $_POST['name']);
        $q->bindValue(2, 0);
        $q->bindValue(3, $_POST['categoryorder']);
        $q->bindValue(4, $extension);
        $q->bindValue(5, $_POST['isactive']);
        $category_id > 0 ? $q->bindValue(6, $category_id) : '';
        $stat1 = $q->execute();
        $category_id = $stat1 && $category_id == 0 ? $connect->lastInsertId() : $category_id;
        if($stat1 && $files->error==0){
            if ($_POST['id'] > 0 && file_exists($upload_path . $category_id . "." . $category->extension)) {
                unlink($upload_path . $category_id . "." . $category->extension);
            }
            $new_file = $upload_path . $category_id . "." . $extension;
            $stat2 = move_uploaded_file($tmp_file, $new_file);
        }else{
            $stat2=$stat1;
        }
        if ($stat2) {
            set_flash_session(
                'category_success',
                '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Category '.$btn_text.'ed Successfully !
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>'
            );
        } else {
            set_flash_session(
                'category_error',
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
}



?>


<?php
$title = "Agri Express > Category > $heading Category";
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
                    <h1 class="m-0"><?= $heading ?> Category</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/components/category">Category</a></li>
                        <li class="breadcrumb-item active"><?= $heading ?> Category</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?= show_flash('catagory_warning') ?>
            <!-- general form elements -->
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title"><?= $heading ?> Category</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $category_id ?>">
                    <div class="card-body">
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>Name <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control" placeholder="Enter category name" name="name" value="<?= $category ? $category->name : '' ?>" required>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>Order <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-10">
                                <input type="number" class="form-control" placeholder="Enter category Order" name="categoryorder" value="<?= $category ? $category->categoryorder : 0 ?>" required>
                            </div>
                        </div>
                        <?php
                        if ($category && isset($category->extension)) {
                            echo '<div class="row mb-3">';
                            echo '<div class="col-md-3"></div>';
                            echo '<div class="col-md-2">';
                            echo '<img style="background-color:rgb(180 217 129)" class="image image-responsive d-block mx-auto" src="../../dist/images/category/' . $category_id . '.' . $category->extension . '" />';
                            echo '</div>';
                            echo '</div>';
                        }
                        ?>
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>Image <span class="text-danger"><?=$category_id?'':"*"?></span></label>
                            </div>
                            <div class="col-md-10">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="image" accept="image/jpeg,image/png" <?=$category_id?'':"required"?>>
                                    <label class="custom-file-label">Choose file</label>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>Status <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-10">
                                <select name="isactive" class="form-control" required>
                                    <option value="1" <?= $category && $category->isactive == "1" ? 'selected' : '' ?>>Active</option>
                                    <option value="0" <?= $category && $category->isactive == "0" ? 'selected' : '' ?>>Deactive</option>
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