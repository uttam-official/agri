<?php 
session_status()?session_start():'';
if(isset($_SESSION['user_id']))
{
    unset($_SESSION['user_id']);
    session_unset();
    session_destroy();
}
header('location:login.php');

