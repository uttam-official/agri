<?php 
    require_once "../../includes/verify_login.php";
    require_once "../../db/connect.php";
    require_once "../../includes/session.php";
    
    $sql="SELECT p.id,p.name,c.name as category,s.name as subcategory,p.price,p.availability from product p join category c on p.category=c.id and c.isactive=:c_active left join category s on p.subcategory=s.id and s.isactive=:s_active where p.isactive=:p_active ";
    $q=$connect->prepare($sql);
    $q->execute([':c_active'=>1,':s_active'=>1,':p_active'=>1]);
    $page_data=$q->fetchAll(PDO::FETCH_OBJ);
?>
<?php 
    $title="Category Management";
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
                    <h1 class="m-0">Product</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=BASE_URL?>">Dashboard</a></li>
                        <li class="breadcrumb-item">Product</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?=show_flash('product_success') ?>
            <?=show_flash('product_error') ?>
            <!-- general form elements -->
            <div class="card card-outline card-info">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-center mb-2">
                        <a href="<?=BASE_URL?>/components/product" class="btn btn-success btn-sm mr-1 mb-1">ALL</a>
                        <?php 
                            for($char=ord('A');$char<=ord('Z');$char++){
                                echo '<a href="'.BASE_URL.'/components/product/index.php?search='.chr($char).'" class="btn btn-success btn-sm mr-1 mb-1">'.chr($char).'</a>';
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
                            <a href="<?=BASE_URL?>/components/product/add.php" class="btn btn-sm btn-success">Add
                                New</a>
                        </div>
                        <div class="row mt-4">
                            <table class="table-sm table-bordered col-md-11 mx-auto rounded">
                                <thead>
                                    <tr class="text-center table-secondary">
                                        <th>#</th>
                                        <th class="text-left">Product Name</th>
                                        <th class="text-left">Category Name</th>
                                        <th class="text-left">Subcategory Name</th>
                                        <th>Price (&dollar;)</th>
                                        <th>Availabilty</th>
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
                                            <td class="text-left"><?=$value->category?></td>
                                            <td class="text-left"><?=$value->subcategory?></td>
                                            <td><?=$value->price?></td>
                                            <td><?=$value->availability==1?'<span class="badge badge-info">In Stock</span>':'<span class="badge badge-warning">Out of Stock</span>'?></td>
                                            <td>
                                                <a href="<?=BASE_URL?>/components/product/add.php?id=<?=$value->id?>" class="btn btn-sm btn-outline-info"><i class="fa fa-edit"></i></a>
                                                <a href="<?=BASE_URL?>/components/product/delete.php?id=<?=$value->id?>" class="btn btn-sm btn-outline-danger"> <i class="fa fa-trash"></i></a>
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