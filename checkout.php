<?php
session_status() == 1 ? session_start() : '';
if (isset($_SESSION['cart'])  && count($_SESSION['cart']) > 0) {
    $subtotal = 0;
    $ecotax = 0;
    foreach ($_SESSION['cart'] as $id => $value) {
        $ecotax += 2;
        $subtotal += $value['qty'] * $value['price'];
    }
    $vat = $subtotal * 20 / 100;
    $total = $subtotal + $ecotax + $vat;
    $_SESSION['checkout'] = array(
        "subtotal" => $subtotal,
        "discount"=>0,
        "vat" => $vat,
        "ecotax" => $ecotax,
        "total" => $total
    );
    header('location:login.php');
} else {
    header('location:cart.php');
}
