<?php
require_once '../includes/verify_login.php';
require_once '../db/connect.php';
include_once '../includes/session.php';
$admin_id = $_SESSION['admin']['id'];
function get_admin($id, $connect)
{
    $q = $connect->prepare("SELECT email FROM admin WHERE id=:id");
    $q->execute([':id' => $id]);
    return $q->fetch(PDO::FETCH_OBJ);
}
$data = get_admin($admin_id, $connect);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $q1 = $connect->prepare('UPDATE admin SET email=?,password=? WHERE id=?');
    $q1->bindValue(1, $_POST['email']);
    $q1->bindValue(2, $password);
    $q1->bindValue(3, $admin_id);
    
    if ($q1->execute()) {
        $data=get_admin($admin_id,$connect);
        set_flash_session('settings_success', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        Username and Password updated Successfully !
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
    } else {
        set_flash_session('settings_error', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        Something wents wrong....  please try after some time ....
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
    }
}
$title = "Agri Express > Settings";
require_once '../includes/header.php';
include_once '../includes/navbar.php';
include_once '../includes/sidebar.php';
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Settings</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?= show_flash('settings_success') ?>
            <?= show_flash('settings_error') ?>
            <!-- general form elements -->
            <div class="card card-outline card-info">
                <!-- /.card-header -->
                <!-- form start -->
                <div class="card-body">
                    <form action="" method="POST" class="form-group">
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label>Username/Email Id <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" placeholder="Enter your username/email id" value="<?= $data->email ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label>Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" placeholder="Enter new password" required>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="d-inline-block ml-auto mr-3">
                                <button class="btn btn-sm btn-success">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<?php
include_once '../includes/footer_content.php';
require_once '../includes/footer.php';
?>