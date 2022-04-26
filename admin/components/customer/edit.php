<?php
require_once "../../includes/verify_login.php";
require_once "../../db/connect.php";
require_once "../../includes/session.php";
function get_address($id, $connect)
{
    $q = $connect->prepare("SELECT * FROM address WHERE customer_id=:id AND isactive=:active");
    $q->execute([':id' => $id, ':active' => 1]);
    return $q->fetchAll(PDO::FETCH_OBJ);
}
if (isset($_POST['customer_edit'])) {
    $q1 = $connect->prepare('UPDATE customer SET firstname=?,lastname=?,email=?,phone=?,fax=?,isactive=? WHERE id=?');
    $q1->bindValue(1, $_POST['fname']);
    $q1->bindValue(2, $_POST['lname']);
    $q1->bindValue(3, $_POST['email']);
    $q1->bindValue(4, $_POST['phone']);
    $q1->bindValue(5, $_POST['fax']);
    $q1->bindValue(6, $_POST['isactive']);
    $q1->bindValue(7, $_POST['id']);
    if ($q1->execute()) {
        echo json_encode(['status' => 1]);
    } else {
        echo json_encode(['status' => 0]);
    }

    return 1;
}
if (isset($_POST['address'])) {
    $q2 = $connect->prepare("UPDATE address SET company=?,address1=?,address2=?,city=?,postcode=?,country=?,state=? WHERE id=?");
    $q2->bindValue(1, $_POST['company']);
    $q2->bindValue(2, $_POST['address_1']);
    $q2->bindValue(3, $_POST['address_2']);
    $q2->bindValue(4, $_POST['city']);
    $q2->bindValue(5, $_POST['postcode']);
    $q2->bindValue(6, $_POST['country']);
    $q2->bindValue(7, $_POST['state']);
    $q2->bindValue(8, $_POST['id']);
    if ($q2->execute()) {
        echo json_encode(['status' => 1]);
    } else {
        echo json_encode(['status' => 0]);
    }
    return 1;
}



if (isset($_GET['id']) && $_GET['id'] > 0) {
    $id = $_GET['id'];
    $q = $connect->prepare('SELECT firstname,lastname,email,phone,fax,isactive FROM customer WHERE id=:id AND isactive>:active');
    $q->execute([':id' => $id, ':active' => -1]);
    if ($q->rowCount() > 0) {
        $customer = $q->fetch((PDO::FETCH_OBJ));
        $address = get_address($id, $connect);
    } else {
        set_flash_session('customer_warning', '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        Customer not found !
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
        header('location:index.php');
    }
} else {
    set_flash_session('customer_warning', '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        Customer not found !
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
    header('location:index.php');
}

$title = "Edit Customer";
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
                    <h1 class="m-0">Edit Customer</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/components/customer">Customer</a></li>
                        <li class="breadcrumb-item">Edit Customer</li>
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
                <div class="row mt-2 ml-2">
                    <div class="col-md-12">
                        <p class="h5">Personal Details</p>
                        <form action="" method="post" class="form-group row" id="customer_details">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <div class="col-md-6">
                                <label>First Name</label>
                                <input type="text" class="form-control" name="fname" placeholder="Enter first name" value="<?= $customer->firstname ?>">
                            </div>
                            <div class="col-md-6">
                                <label>Last Name</label>
                                <input type="text" class="form-control" name="lname" placeholder="Enter last name" value="<?= $customer->lastname ?>">
                            </div>
                            <div class="col-md-6">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Enter email" value="<?= $customer->email ?>">
                            </div>
                            <div class="col-md-6">
                                <label>Telephone</label>
                                <input type="text" class="form-control" name="phone" placeholder="Enter telephone number" value="<?= $customer->phone ?>">
                            </div>
                            <div class="col-md-6">
                                <label>Fax</label>
                                <input type="text" class="form-control" name="fax" placeholder="Enter fax" value="<?= $customer->fax ?>">
                            </div>
                            <div class="col-md-6">
                                <label>Status</label>
                                <select name="isactive" class="form-control">
                                    <option value="1" <?= $customer->isactive ? "required" : '' ?>>Active</option>
                                    <option value="0" <?= $customer->isactive ? "" : 'required' ?>>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-12 mt-3 text-center">
                                <button class="btn btn-sm btn-success" type="submit">Save</button>&nbsp;&nbsp;
                                <button class="btn btn-sm btn-warning" type="reset">Reset</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-12">
                        <p class="h4">Addresses</p>
                        <?php foreach ($address as $key => $l) : ?>
                            <div class="pl-3 nav-item bg-warning  rounded">
                                <a class="h6 text-info nav-link" data-toggle="collapse" href="#address<?= $key + 1 ?>"><i class="fa fa-caret-down"></i> &nbsp; Address <?= $key + 1 ?></a>
                            </div>
                            <form action="" method="post" class="form-group row address collapse" id="address<?= $key + 1 ?>">
                                <input type="hidden" name="id" value="<?= $l->id ?>">
                                <input type="hidden" name="address" value="Address <?= $key + 1 ?>">
                                <div class="col-md-6">
                                    <label>Company</label>
                                    <input type="text" class="form-control" name="company" value="<?= $l->company ?>">
                                </div>
                                <div class="col-md-6">
                                    <label>Address 1</label>
                                    <input type="text" class="form-control" name="address_1" value="<?= $l->address1 ?>">
                                </div>
                                <div class="col-md-6">
                                    <label>Address 2</label>
                                    <input type="text" class="form-control" name="address_2" value="<?= $l->address2 ?>">
                                </div>
                                <div class="col-md-6">
                                    <label>City</label>
                                    <input type="text" class="form-control" name="city" value="<?= $l->city ?>">
                                </div>
                                <div class="col-md-6">
                                    <label>Postcode</label>
                                    <input type="text" class="form-control" name="postcode" value="<?= $l->postcode ?>">
                                </div>
                                <div class="col-md-6">
                                    <label>Country</label>
                                    <select class="form-control" id="input-country" name="country" required>
                                        <option value=""> --- Please Select --- </option>
                                        <option value="India" selected>India</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>State/Region</label>
                                    <select class="form-control" id="input-zone" name="state" required>
                                        <option value=""> --- Please Select --- </option>
                                        <option value="West Bengal" <?= $l->state == "West Bengal" ? 'selected' : '' ?>>West Bengal</option>
                                        <option value="Delhi" <?= $l->state == "Delhi" ? 'selected' : '' ?>>Delhi</option>
                                        <option value="Maharastra" <?= $l->state == "Maharastra" ? 'selected' : '' ?>>Maharastra</option>
                                        <option value="Tamilnadu" <?= $l->state == "Tamilnadu" ? 'selected' : '' ?>>Tamilnadu</option>
                                        <option value="Bihar" <?= $l->state == "Bihar" ? 'selected' : '' ?>>Bihar</option>
                                        <option value="Jharkhand" <?= $l->state == "Jharkhand" ? 'selected' : '' ?>>Jharkhand</option>
                                        <option value="UP" <?= $l->state == "UP" ? 'selected' : '' ?>>UP</option>
                                        <option value="HP" <?= $l->state == "HP" ? 'selected' : '' ?>>HP</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mt-3 text-center">
                                    <button class="btn btn-sm btn-success" type="submit">Save</button>&nbsp;&nbsp;
                                    <button class="btn btn-sm btn-warning" type="reset">Reset</button>
                                </div>
                            </form>
                        <?php endforeach; ?>

                    </div>
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
<script>
    $(document).ready(function() {
        $('#customer_details').on('submit', function(e) {
            e.preventDefault();
            const form = new FormData(e.target);
            const data = {
                'customer_edit': 'customer_edit',
                'id': form.get('id'),
                'fname': form.get('fname'),
                'lname': form.get('lname'),
                'email': form.get('email'),
                'phone': form.get('phone'),
                'fax': form.get('fax'),
                'isactive': form.get('isactive')
            }
            $.ajax({
                url: 'edit.php',
                type: 'post',
                dataType: 'json',
                data: data,
                success: function(data) {
                    if (data.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Voila..',
                            text: 'Customer details updated successfully !'
                        })
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            })
        })


        $('.address').on('submit', function(e) {
            e.preventDefault();
            var form = new FormData(e.target);
            var data = {
                'address': 'address',
                'id': form.get('id'),
                'company': form.get('company'),
                'address_1': form.get('address_1'),
                'address_2': form.get('address_2'),
                'city': form.get('city'),
                'postcode': form.get('postcode'),
                'country': form.get('country'),
                'state': form.get('state')
            };

            $.ajax({
                url: 'edit.php',
                type: 'post',
                dataType: 'json',
                data: data,
                success: function(data) {
                    if (data.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Voila..',
                            text: form.get('address') + ' updated successfully !'
                        })
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            })
        })
    })
</script>