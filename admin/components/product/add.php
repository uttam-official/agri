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
function insert_productgallery($product_id, $extension, $connect)
{
    $q = $connect->prepare("insert into productgallery (product_id,extension) values (:product_id,:extension)");
    return $q->execute([':product_id' => $product_id, ':extension' => $extension]) ? 1 : 0;
}

//GET PRODUCT
function get_product($id, $connect)
{
    $q = $connect->prepare('SELECT p.name,p.description,p.category,p.subcategory,p.price,p.image_extension,p.availability,p.special,p.featured,GROUP_CONCAT(g.id) as gallery_id,GROUP_CONCAT(g.extension) as gallery FROM product p LEFT JOIN productgallery g ON g.product_id=p.id AND g.isactive=:g_active WHERE p.id=:id AND p.isactive=:p_active GROUP BY g.product_id');
    $q->execute([':g_active' => 1, ':id' => $id, ':p_active' => 1]);
    if ($q->rowCount() > 0) {
        return $q->fetch(PDO::FETCH_OBJ);
    } else {
        set_flash_session(
            'product_error',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Product Not Found !
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
    
    $image_layer = imagecreatetruecolor($new_width, $new_width);
    imagecopyresampled($image_layer, $resource_type, 0, 0,0, 0, $new_width, $new_width, $width, $height);
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
//DELETE IMAGE
function delete_image($file)
{
    if (file_exists($file)) {
        unlink($file);
    }
    return 1;
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
    $product = get_product($product_id, $connect);
    $heading = "Edit";
    $btn_text = "Update";
    // var_dump($product->gallery);exit;
} else {
    $heading = "Add";
    $btn_text = "Add";
}

//FORM SUBMIT
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $isimage=$_FILES['image']['error']==4?0:1;
    
    $valid = 1;
    $temp=$_POST;
    isset($product->image_extension)?$temp['image_extension']=$product->image_extension:'';
    isset($product->gallery)?$temp['gallery']=$product->gallery:'';
    isset($_POST['special'])?$temp['special']=1:'';
    isset($_POST['featured'])?$temp['featured']=1:'';
    $product = (object) $temp;
    if ($_POST['id'] == 0 && $_FILES['image']['error'] == 0) {
        $valid = image_validation($_FILES['image']['tmp_name'], $_FILES['image']['size'], 'Fetured Image');
    }
    if ($_POST['id'] > 0 && $_FILES['image']['error'] == 0) {
        $valid = image_validation($_FILES['image']['tmp_name'], $_FILES['image']['size'], 'Fetured Image');
    } elseif ($_POST['id'] == 0 && $_FILES['image']['error'] == 4) {
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
            $product = get_product($product_id, $connect);
            $sql = "UPDATE product SET name=?,description=?,category=?,subcategory=?,price=?,image_extension=?,availability=?,special=?,featured=? WHERE id=?";
        } else {
            $sql = "INSERT INTO product (name,description,category,subcategory,price,image_extension,availability,special,featured) VALUES(?,?,?,?,?,?,?,?,?)";
        }
        $query = $connect->prepare($sql);
        $query->bindValue(1, $_POST['name']);
        $query->bindValue(2, $_POST['description']);
        $query->bindValue(3, $_POST['category']);
        $query->bindValue(4, isset($_POST['subcategory']) ? $_POST['subcategory'] : null);
        $query->bindValue(5, $_POST['price']);
        $query->bindValue(6, $isimage ? get_imageextension($_FILES['image']['name']) : $product->image_extension);
        $query->bindValue(7, $_POST['availability']);
        $query->bindValue(8, isset($_POST['special']) ? 1 : 0);
        $query->bindValue(9, isset($_POST['featured']) ? 1 : 0);
        $_POST['id'] > 0 ? $query->bindValue(10, $_POST['id']) : '';
        if (!$query->execute()) {
            error_message();
            header('location:index.php');
        }
    }

    if ($_FILES['image']['error'] == 0 && $valid) {
        $product_id = $_POST['id'] == 0 ? $connect->lastInsertId() : $product_id;
        $upload_path = "../../dist/images/product/";
        $extension = get_imageextension($_FILES['image']['name']);
        $file = $_FILES['image']['tmp_name'];
        $name = $product_id . "." . $extension;
        if ($_POST['id'] > 0) {
            delete_image($upload_path . "small/" . $product_id . "." . $product->image_extension);
            delete_image($upload_path . "medium/" . $product_id . "." . $product->image_extension);
            delete_image($upload_path . "large/" . $product_id . "." . $product->image_extension);
        }
        $valid = upload_image($file, $upload_path . "small/" . $name, 100) && upload_image($file, $upload_path . "medium/" . $name, 500) && upload_image($file, $upload_path . "large/" . $name, 1000);
    }
    if ($valid && $_FILES['gallery']['error'][0] == 0) {
        if ($product && $product->gallery != null) {
            $q1 = $connect->prepare("DELETE FROM productgallery WHERE product_id=:product_id");
            $q1->execute([':product_id' => $product_id]);
            $gallery = explode(',', $product->gallery);
            $upload_path = "../../dist/images/productgallery/";
            foreach ($gallery as $key => $extension) {
                delete_image($upload_path . "small/"  . $extension);
                delete_image($upload_path . "medium/"  . $extension);
                delete_image($upload_path . "large/"  . $extension);
            }
        }

        foreach ($_FILES['gallery']['tmp_name'] as $key => $file) {
            $upload_path = "../../dist/images/productgallery/";
            $extension = get_imageextension($_FILES['gallery']['name'][$key]);

            $name = $product_id . "_" . $key . "." . $extension;
            $status = insert_productgallery($product_id, $name, $connect);

            $valid = upload_image($file, $upload_path . "small/" . $name, 100) && upload_image($file, $upload_path . "medium/" . $name, 500) && upload_image($file, $upload_path . "large/" . $name, 1000);
            if ($valid == 0 || $status == 0) {
                $valid = $valid && $status;
                break;
            }
        }
    }
    if ($valid) {
        set_flash_session(
            'product_success',
            '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Product ' . $btn_text . 'ed Successfully !
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>'
        );
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
$title = "Agri Express > Category > $heading Product";
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
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/components/product">Product</a></li>
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
                                <label>Subcategory <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-10">
                                <select name="subcategory" id="subcategory" class="form-control text-uppercase" required>
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
                                <textarea name="description" rows="4" class="form-control" placeholder="Enter product description"><?= $product ? $product->description : '' ?></textarea>
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
                        <div class="row">
                            <div class="offset-md-3 col-md-2">
                                <?php
                                if ($product && isset($product->image_extension)) {
                                    echo  '<img src="../../dist/images/product/small/' . $product_id . '.' . $product->image_extension . '" class="d-block mx-auto image-responsive mb-3" >';
                                }
                                ?>
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
                        <div class="row gallery mb-3">
                            <div class="col-md-2"></div>
                            <?php
                            if ($product && isset($product->gallery) && $product->gallery != null) :
                                $gallery = explode(',', $product->gallery);
                                foreach ($gallery as $key => $extension) :
                            ?>
                                    <div class="col-md-2 mb-1">
                                        <img src="../../dist/images/productgallery/small/<?= $extension ?>" alt="" class="image-responsive d-block mx-auto">
                                    </div>
                            <?php endforeach;
                            endif; ?>
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
                                    <input type="checkbox" name="special" class="form-check-input" <?= $product && isset($product->special) && $product->special==1 ? 'checked' : '' ?>>
                                    <label class="form-check-label">Add to Special Product</label>
                                </div>
                                <div class="col-md-3 form-check">
                                    <input type="checkbox" name="featured" class="form-check-input" <?= $product && isset($product->featured) && $product->featured==1 ? 'checked' : '' ?>>
                                    <label class="form-check-label">Add to Featured Product</label>
                                </div>
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
<script src="../../dist/js/pages/product/add.js"></script>
<script>
    $(function() {
        bsCustomFileInput.init();
    });
</script>