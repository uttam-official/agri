<?php
    isset($_SESSION)?:session_start();
    $url="http://".$_SERVER['SERVER_NAME'];
    isset($_SESSION['admin']) && $_SESSION['admin']['id']>0?'':header('location:'.$url.'/agri/admin/components/login.php');
?>