<?php
    require_once "../../includes/verify_login.php";
    require_once "../../db/connect.php";
    require_once "../../includes/session.php";

    if($_GET['id']>0){
        $q=$connect->prepare("UPDATE category SET isactive=:isactive WHERE id=:id");
        if($q->execute([':isactive'=>-1,':id'=>$_GET['id']])){
            set_flash_session(
                'category_success',
                '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Category deleted successfully !
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>'
            );
        }else{
            set_flash_session(
                'category_error',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Something wents wrong ... please try after some time ... 
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>'
            );
        }
        header('location:index.php');
    }else{
        set_flash_session(
            'category_error',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Bad Request!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>'
        );
        header('location:index.php');
    }


?>