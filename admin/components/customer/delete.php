<?php
require_once "../../includes/verify_login.php";
require_once "../../db/connect.php";
require_once "../../includes/session.php";

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $q = $connect->prepare('UPDATE customer SET isactive=:active WHERE id=:id');
    if ($q->execute([':active' => -1, ':id' => $_GET['id']])) {
        set_session('customer_success', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        Customer Deleted Successfully ! 
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
        
    }else{
        set_session('customer_warning', '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        Customer not found !
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
    }
}
header('location:index.php');
