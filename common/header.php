<?php define('BASE_URL','http://'.$_SERVER['SERVER_NAME']."/agri/")?>

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
<title><?=$title?></title>
<meta name="description" content="My Store" />
<script src="<?=BASE_URL?>includes/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
<link href="<?=BASE_URL?>includes/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
<script src="<?=BASE_URL?>includes/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<link href="<?=BASE_URL?>includes/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="<?=BASE_URL?>stylesheet/stylesheet.css" rel="stylesheet">
<link href="<?=BASE_URL?>stylesheet/responsive.css" rel="stylesheet">
<link href="<?=BASE_URL?>stylesheet/menu.css" rel="stylesheet">
<link href="<?=BASE_URL?>includes/jquery/owl-carousel/owl.carousel.css" type="text/css" rel="stylesheet" media="screen" />
<script src="<?=BASE_URL?>javascript/jquery.extra.js" type="text/javascript"></script>
<script src="<?=BASE_URL?>includes/common.js" type="text/javascript"></script>
<link href="<?=BASE_URL?>images/favicon.png" rel="icon" />
<script src="<?=BASE_URL?>includes/jquery/owl-carousel/owl.carousel.min.js" type="text/javascript"></script>
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
        <div id="logo"><a href="<?=BASE_URL?>"><img src="images/logo.png" title="Agriculture" alt="Agriculture" class="img-responsive" /></a></div>
      </div>
      <div class="col-md-9 col-sm-8">
            <div class="header-right">
                <div class="input-group" id="search">
                  <input type="text" class="form-control input-lg" placeholder="Search" value="" name="search" vk_1e187="subscribed">
                  <span class="input-group-btn">
                    <button class="btn btn-default btn-lg" type="button"><i class="fa fa-search"></i></button>
                  </span>
                </div>
                <div class="btn-group btn-block" id="cart">
                  <button class="btn btn-viewcart dropdown-toggle" data-loading-text="Loading..." data-toggle="dropdown" type="button" aria-expanded="false"><span class="lg">My Cart</span><span id="cart-total"><i class="fa fa-shopping-basket"></i> (1) items</span></button>
                  <ul class="dropdown-menu pull-right"><li>
                      <table class="table table-striped">
                                <tbody><tr>
                          <td class="text-center">            <a href="#"><img class="img-thumbnail" title="iPhone" alt="iPhone" src="images/iphone_1-47x47.jpg"></a>
                            </td>
                          <td class="text-left"><a href="#">iPhone</a>
                                        </td>
                          <td class="text-right">x 1</td>
                          <td class="text-right">$123.20</td>
                          <td class="text-center"><button class="btn btn-danger btn-xs" title="Remove" onclick="cart.remove('1');" type="button"><i class="fa fa-times"></i></button></td>
                        </tr>
                                      </tbody></table>
                    </li><li>
                      <div>
                        <table class="table table-bordered">
                                    <tbody><tr>
                            <td class="text-right"><strong>Sub-Total</strong></td>
                            <td class="text-right">$101.00</td>
                          </tr>
                                    <tr>
                            <td class="text-right"><strong>Eco Tax (-2.00)</strong></td>
                            <td class="text-right">$2.00</td>
                          </tr>
                                    <tr>
                            <td class="text-right"><strong>VAT (20%)</strong></td>
                            <td class="text-right">$20.20</td>
                          </tr>
                                    <tr>
                            <td class="text-right"><strong>Total</strong></td>
                            <td class="text-right">$123.20</td>
                          </tr>
                                  </tbody></table>
                        <p class="text-right"><a href="#"><strong><i class="fa fa-shopping-cart"></i> View Cart</strong></a>&nbsp;&nbsp;&nbsp;<a href="#"><strong><i class="fa fa-share"></i> Checkout</strong></a></p>
                      </div>
                    </li></ul>
                </div>
            </div>
      </div>
    </div>
  </div>
</header>
