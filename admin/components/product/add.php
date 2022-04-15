<?php
require_once "../../includes/verify_login.php";
require_once "../../db/connect.php";
require_once "../../includes/session.php";
function get_imageextension($name)
{
    return pathinfo($name, PATHINFO_EXTENSION);
}
function error_message()
{
    set_flash_session(
        'product_error',
        '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        Something wents wrong .... Please try again later ....
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>'
    );
}
//PRODUCT GALLERY
function insert_productgallery($product_id,$extension,$connect){
    $q=$connect->prepare("insert into productgallery (product_id,extension) values (:product_id,:extension)");
    return $q->execute([':product_id'=>$product_id,':extension'=>$extension])?1:0;
}
function get_category($id, $connect)
{
    $q = $connect->prepare('SELECT * FROM category WHERE id=:id AND isactive>:isactive');
    $q->execute([':id' => $id, ':isactive' => -1]);
    if ($q->rowCount() > 0) {
        return $q->fetch(PDO::FETCH_OBJ);
    } else {
        set_flash_session(
            'product_error',
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
//RESIZE IMAGE
function resize_image($resource_type, $width, $height, $new_width)
{
    $new_height = $new_width * $height / $width;
    $image_layer = imagecreatetruecolor($new_width, $new_height);
    imagecopyresampled($image_layer, $resource_type, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    return $image_layer;
}
//UPLOAD IMAGE
function upload_image($file, $upload_path, $new_width)
{
    $progress = 0;
    $file_proprty = getimagesize($file);
    $image_type = $file_proprty[2];
    $height = $file_proprty[1];
    $width = $file_proprty[0];
    if ($image_type == IMAGETYPE_JPEG) {
        $resource_type = imagecreatefromjpeg($file);
        $image_layer = resize_image($resource_type, $width, $height, $new_width);
        imagejpeg($image_layer, $upload_path) ? $progress = 1 : '';
        imagedestroy($image_layer);
    } elseif ($image_type == IMAGETYPE_PNG) {
        $resource_type = imagecreatefrompng($file);
        $image_layer = resize_image($resource_type, $width, $height, $new_width);
        imagepng($image_layer, $upload_path) ? $progress = 1 : '';
        imagedestroy($image_layer);
    }
    // exit;
    return $progress;
}
//IMAGE VALIDATION
function image_validation($file, $size, $key)
{
    $deatils = getimagesize($file);
    if ($deatils[2] != IMAGETYPE_JPEG && $deatils[2] != IMAGETYPE_PNG) {
        set_flash_session(
            'product_warning',
            '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            Only Jpg,Jpeg,Png type ' . $key . ' allowed
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>'
        );
        return 0;
    } elseif ($size > 10485760 || $deatils[0] < 1000 || $deatils[1] < 1000) {
        set_flash_session(
            'product_warning',
            '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            ' . $key . ' should be <10MB and minimum 1000&times;1000 pixels square sized
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>'
        );
        return 0;
    }
    return 1;
}
$product = 0;
$product_id = 0;
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $product_id = $_GET['id'];
    $product = get_category($product_id, $connect);
    $heading = "Edit";
    $btn_text = "Update";
} else {
    $heading = "Add";
    $btn_text = "Add";
}

//FORM SUBMIT
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if ($_POST['id'] == 0 && $_FILES['image']['error'] == 0) {
        $valid = image_validation($_FILES['image']['tmp_name'], $_FILES['image']['size'], 'Fetured Image');
    } else {
        set_flash_session(
            'product_warning',
            '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            Fetured Image Required !
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>'
        );
        $valid = 0;
    }
    if ($valid && $_FILES['gallery']['error'][0] == 0) {
        $length = count($_FILES['gallery']['tmp_name']);
        for ($i = 0; $i < $length; $i++) {
            $valid = image_validation($_FILES['gallery']['tmp_name'][$i], $_FILES['gallery']['size'][$i], 'Gallery Image');
            if ($valid == 0) {
                break;
            }
        }
    }
    if ($valid) {
        if ($_POST['id'] > 0) {
            $product_id = $_POST['id'];
            $sql = "UPADTE product SET name=?,description=?,category=?,subcategory=?,price=?,image_extension=?,availability=?,special=?,featured=? WHERE id=?";
        } else {
            $sql = "INSERT INTO product (name,description,category,subcategory,price,image_extension,availability,special,featured) VALUES(?,?,?,?,?,?,?,?,?)";
        }
        $query = $connect->prepare($sql);
        $query->bindValue(1, $_POST['name']);
        $query->bindValue(2, $_POST['description']);
        $query->bindValue(3, $_POST['category']);
        $query->bindValue(4, isset($_POST['subcategory']) ? $_POST['subcategory'] : null);
        $query->bindValue(5, $_POST['price']);
        $query->bindValue(6, get_imageextension($_FILES['image']['name']));
        $query->bindValue(7, $_POST['availability']);
        $query->bindValue(8, isset($_POST['special']) ? 1 : 0);
        $query->bindValue(9, isset($_POST['featured']) ? 1 : 0);
        $_POST['id'] > 0 ? $query->bindValue(10, $_POST['id']) : '';
        $valid = $query->execute();
    }
    if (!$valid) {
        error_message();
        header('location:index.php');
    } else {
        $product_id = $_POST['id'] == 0 ? $connect->lastInsertId() : $product_id;
        $upload_path = "../../dist/images/product/";
        $extension = get_imageextension($_FILES['image']['name']);
        $file = $_FILES['image']['tmp_name'];
        $name = $product_id . "." . $extension;

        $valid = upload_image($file, $upload_path . "small/" . $name, 100) && upload_image($file, $upload_path . "medium/" . $name, 500) && upload_image($file, $upload_path . "large/" . $name, 1000);
    }
    if ($valid && $_FILES['gallery']['error'][0] == 0) {
        foreach ($_FILES['gallery']['tmp_name'] as $key => $file) {
            $upload_path = "../../dist/images/productgallery/";
            $extension = get_imageextension($_FILES['gallery']['name'][$key]);

            $name = $product_id ."_".$key. "." . $extension;
            $status=insert_productgallery($product_id,$extension,$connect);

            $valid = upload_image($file, $upload_path . "small/" . $name, 100) && upload_image($file, $upload_path . "medium/" . $name, 500) && upload_image($file, $upload_path . "large/" . $name, 1000);
            if ($valid == 0 || $status==0 ) {
                $valid=$valid&&$status;
                break;
            }
        }
    } else {
        error_message();
        header('location:index.php');
    }
    if($valid){
        set_flash_session(
            'product_success',
            '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Product '.$btn_text.'ed Successfully !
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>'
        );
        header('location:index.php');
    }else{
        error_message();
        header('location:index.php');
    }
}



// AJAX CALL FOR SUBCATEGORY
if (isset($_GET['category'])) {
    echo json_encode(get_subcategory($_GET['category'], $connect));
    return 1;
}


//GET CATEGORY LIST
$q = $connect->prepare("SELECT id,name FROM category WHERE parent=:parent AND isactive=:isactive");
$q->execute([':parent' => 0, ':isactive' => 1]);
$category_list = $q->fetchAll(PDO::FETCH_OBJ);


//GET SUB CATEGORY
function get_subcategory($id, $connect)
{
    $sql = "SELECT s.id,s.name FROM category s JOIN category c ON s.parent=c.id AND c.isactive=:c_active WHERE s.isactive=:s_active AND s.parent=:parent";
    $q = $connect->prepare($sql);
    $q->execute([':c_active' => 1, ':s_active' => 1, ':parent' => $id]);
    return $q->fetchAll(PDO::FETCH_OBJ);
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
                    <h1 class="m-0"><?= $heading ?> Product</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/components/category">Product</a></li>
                        <li class="breadcrumb-item active"><?= $heading ?> Product</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?= show_flash('product_warning') ?>
            <!-- general form elements -->
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title"><?= $heading ?> Product</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $product_id ?>">
                    <div class="card-body">
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>Category <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-10">
                                <select name="category" id="category" class="form-control text-uppercase" required>
                                    <option value="" disabled selected>---Select a category---</option>
                                    <?php foreach ($category_list as $l) : ?>
                                        <option value="<?= $l->id ?>" <?= $product && $l->id == $product->category ? "selected" : "" ?>><?= $l->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>Subcategory</label>
                            </div>
                            <div class="col-md-10">
                                <select name="subcategory" id="subcategory" class="form-control text-uppercase">
                                    <option value="" disabled selected>---Select a Subcategory---</option>
                                    <?php
                                    if ($product && $product->category > 0) :
                                        foreach (get_subcategory($product->category, $connect) as $l) :
                                    ?>
                                            <option value="<?= $l->id ?>" <?= $product && $l->id == $product->subcategory ? "selected" : "" ?>><?= $l->name ?></option>
                                    <?php endforeach;
                                    endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>Name <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control" placeholder="Enter product name" name="name" value="<?= $product ? $product->name : '' ?>" required>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>Description</label>
                            </div>
                            <div class="col-md-10">
                                <textarea name="description" rows="4" class="form-control" placeholder="Enter product description"></textarea>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>Price <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-10">
                                <input type="number" class="form-control" placeholder="Enter product price" name="price" value="<?= $product ? $product->price : '' ?>" required>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>Fetured Image <span class="text-danger"><?= $product_id ? '' : "*" ?></span></label>
                            </div>
                            <div class="col-md-10">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="image" accept="image/jpeg,image/png" <?= $product_id ? '' : "required" ?>>
                                    <label class="custom-file-label">Choose file</label>
                                </div>
                                <label class="text-info text-sm">(Image should be &lt;10MB and minimum 1000&times;1000 pixels square sized)</label>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>Availability <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-10">
                                <select name="availability" class="form-control" required>
                                    <option value="1" <?= $product && $product->availability == "1" ? 'selected' : '' ?>>In Stock</option>
                                    <option value="0" <?= $product && $product->availability == "0" ? 'selected' : '' ?>>Out of Stock</option>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>Gallery Image</label>
                            </div>
                            <div class="col-md-10">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" multiple="true" name="gallery[]" accept="image/jpeg,image/png">
                                    <label class="custom-file-label">Choose file</label>
                                </div>
                                <label class="text-info text-sm">*** You can choose multiple image here for product gallery *** <br />(Image should be &lt;10MB and minimum 1000&times;1000 pixels square sized)</label>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="offset-md-2 col-md-10 row">
                                <div class="col-md-3 form-check">
                                    <input type="checkbox" name="special" class="form-check-input">
                                    <label class="form-check-label">Add to Special Product</label>
                                </div>
                                <div class="col-md-3 form-check">
                                    <input type="checkbox" name="featured" class="form-check-input">
                                    <label class="form-check-label">Add to Featured Product</label>
                                </div>
                            </div>
                        </div>
                        <div class=" text-center">
                            <button type="submit" class="btn btn-sm btn-success"><?= $btn_text ?></button>
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
<script src="../../dist/js/pages/product/add.js"></script>
<script>
    $(function() {
        bsCustomFileInput.init();
    });
</script>