<?php 
    require_once '../includes/session.php';
    unset_session('admin');
    header('location:login.php');
?>