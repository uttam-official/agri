<?php
$url='http://' . $_SERVER['SERVER_NAME'];
require_once "./common/functions.php";
if (isset($_GET['remove']) && $_GET['remove'] > 0) {
    remove_cart($_GET['remove']);
    header('location:'.$url.$_GET['uri']);
}
?>
