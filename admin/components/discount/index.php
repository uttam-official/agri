<?php 
    require_once "../../includes/verify_login.php";
    require_once "../../db/connect.php";
    require_once "../../includes/session.php";
    
    $sql="SELECT * FROM discount where isactive>:isactive";
    $q=$connect->prepare($sql);
    $q->execute([':isactive'=>-1]);
    $page_data=$q->fetchAll(PDO::FETCH_OBJ);
?>
<?php 
    $title="Discount Management";
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
                    <h1 class="m-0">Discount</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=BASE_URL?>">Dashboard</a></li>
                        <li class="breadcrumb-item">Discount</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?=show_flash('discount_success') ?>
            <?=show_flash('discount_error') ?>
            <!-- general form elements -->
            <div class="card card-outline card-info">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-center mb-2">
                        <a href="<?=BASE_URL?>/components/discount" class="btn btn-success btn-sm mr-1 mb-1">ALL</a>
                        <?php 
                            for($char=ord('A');$char<=ord('Z');$char++){
                                echo '<a href="'.BASE_URL.'/components/discount/index.php?search='.chr($char).'" class="btn btn-success btn-sm mr-1 mb-1">'.chr($char).'</a>';
                            }
                        ?>
                    </div>
                    <form action="" method="GET" class="">
                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-2">
                                <input type="text" class="form-control" name="srh">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-success form-control">Search</button>
                            </div>
                        </div>
                    </form>



                    <div class="rounded-top" style="border-top: solid 3px #17a2b8;">
                        <div class="text-right my-2">
                            <a href="<?=BASE_URL?>/components/discount/add.php" class="btn btn-sm btn-success">Add
                                New</a>
                        </div>
                        <div class="row mt-4">
                            <table class="table-sm table-bordered table-hover col-md-11 mx-auto rounded">
                                <thead>
                                    <tr class="text-center table-secondary">
                                        <th>#</th>
                                        <th class="text-left">Discount Name</th>
                                        <th>Valid From</th>
                                        <th>Valid Till</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        if($page_data && !is_null($page_data)):
                                            foreach($page_data as $key=>$value):
                                    ?>
                                        <tr class="text-center">
                                            <td><?=$key+1?></td>
                                            <td class="text-left"><?=$value->name?></td>
                                            <td><?=$value->validfrom?></td>
                                            <td><?=$value->validtill?></td>
                                            <td class="text-left"><?=$value->amount?></td>
                                            <td><?=$value->isactive?'<span class="badge badge-primary">Active</span>':'<span class="badge badge-secondary">Inactive</span>';?></td>
                                            <td>
                                                <a href="<?=BASE_URL?>/components/discount/add.php?id=<?=$value->id?>" class="btn btn-sm btn-outline-info"><i class="fa fa-edit"></i></a>
                                                <a href="<?=BASE_URL?>/components/discount/delete.php?id=<?=$value->id?>" class="btn btn-sm btn-outline-danger"> <i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach;else:?>
                                        <tr>
                                            <td class="text-center text-danger" colspan="6">No data found !</td>
                                        </tr>
                                    <?php  endif;?>
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
    include_once '../../includes/footer_content.php';
    require_once '../../includes/footer.php';
?>