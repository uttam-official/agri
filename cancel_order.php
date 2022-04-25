<?php 
require_once "./common/functions.php";
verify_login();
session_status()==1?session_start():'';
unset($_SESSION['checkout']);
header('location:index.php');
?>