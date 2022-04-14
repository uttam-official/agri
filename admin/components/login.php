<?php 
    require_once "../db/connect.php";
    require_once "../includes/session.php";
    if(isset($_SESSION['admin']) and $_SESSION['admin']['id']>0){
        header('location:../');
    }
    if($_SERVER['REQUEST_METHOD']=="POST"){
        $q=$connect->prepare("SELECT id,name,password FROM admin WHERE email=:email AND isactive=:isactive");
        $q->execute([':email'=>$_POST['email'],':isactive'=>1]);
        if($q->rowCount()>0){
            $data=$q->fetch(PDO::FETCH_OBJ);
            if(password_verify($_POST['password'],$data->password)){
                $session=array(
                    'id'=>$data->id,
                    'name'=>$data->name
                );
                set_session('admin',$session);
                header('location:../');
            }else{
                $error="Please enter valid Email id and Password";
            }
        }else{
            $error="Please enter valid Email id and Password";
        }
    }
?>
<?php
    $title="Agri Express > Admin > Login";
    include_once "../includes/header.php";
    include_once "../includes/preloader.php";
?>

<div class="login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>Agri Express</b> LOGIN</a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <?=isset($error)?'<p class="bg-danger text-danger text-center p-2 rounded">'.$error.'</p>':''?>
                <form action="<?=BASE_URL?>/components/login.php" method="post">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email" name="email" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-5 mx-auto">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->
</div>



<?php include_once "../includes/footer.php";?>
