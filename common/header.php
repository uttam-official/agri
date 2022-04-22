<?php define('BASE_URL', 'http://' . $_SERVER['SERVER_NAME'] . "/agri/") ?>

<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="ltr" lang="en" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="ltr" lang="en" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="ltr" lang="en">
<!--<![endif]-->

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $title ?></title>
  <meta name="description" content="My Store" />
  <script src="<?= BASE_URL ?>includes/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
  <link href="<?= BASE_URL ?>includes/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
  <script src="<?= BASE_URL ?>includes/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
  <link href="<?= BASE_URL ?>includes/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <link href="<?= BASE_URL ?>stylesheet/stylesheet.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>stylesheet/responsive.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>stylesheet/menu.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>includes/jquery/owl-carousel/owl.carousel.css" type="text/css" rel="stylesheet" media="screen" />
  <script src="<?= BASE_URL ?>includes/common.js" type="text/javascript"></script>
  <link href="<?= BASE_URL ?>images/favicon.png" rel="icon" />
  <script src="<?= BASE_URL ?>includes/jquery/owl-carousel/owl.carousel.min.js" type="text/javascript"></script>
  <script src="<?= BASE_URL ?>includes/jquery/elevatezoom/jquery.elevatezoom.js" type="text/javascript"></script>
  <script src="<?= BASE_URL ?>includes/jquery/sweetalert/sweetalert2.all.min.js" type="text/javascript"></script>
</head>

<body class="common-home">
  <a class="house-heaven" href="#">&nbsp;</a>
  <nav id="top">
    <div class="container">
      <div id="top-links">
        <ul class="list-inline">
          <li><a href="tel:0906430244"><i class="fa fa-phone"></i></a>&nbsp; <span>(090)6430244</span></li>
        </ul>
      </div>

      <div id="top-links2">
        <ul class="list-inline">
          <li><a href="#"><i class="fa fa-user"></i> <span>My Account</span></a></li>
          <li><a href="#" id="wishlist-total" title="Wish List (0)"><i class="fa fa-heart"></i> <span>Wishlist (0)</span></a></li>
          <li><a href="" title="Checkout"><i class="fa fa-shopping-bag"></i> <span>Checkout</span></a></li>
        </ul>
      </div>
    </div>
  </nav>
  <header class="header">
    <div class="container">
      <div class="row">
        <div class="col-md-3 col-sm-4">
          <div id="logo"><a href="<?= BASE_URL ?>"><img src="images/logo.png" title="Agriculture" alt="Agriculture" class="img-responsive" /></a></div>
        </div>
        <div class="col-md-9 col-sm-8">
          <div class="header-right">
            <form class="input-group" action="<?=BASE_URL?>category.php" method="GET" id="search">
              <input type="text" class="form-control input-lg" placeholder="Search" value="" name="search">
              <span class="input-group-btn">
                <button class="btn btn-default btn-lg" type="submit"><i class="fa fa-search"></i></button>
              </span>
            </form>
            <div class="btn-group btn-block" id="cart">
              <?php
              session_status()==1?session_start():'';
              $cart=isset($_SESSION['cart'])?count($_SESSION['cart']):0;
              ?>
              <button class="btn btn-viewcart dropdown-toggle" data-loading-text="Loading..." data-toggle="dropdown" type="button" aria-expanded="false"><span class="lg">My Cart</span><span id="cart-total"><i class="fa fa-shopping-basket"></i> (<?=$cart?>) items</span></button>
              <ul class="dropdown-menu pull-right">
                <?php include_once 'mini_cart.php'; ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>