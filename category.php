<?php
session_status()==1?session_start():'';
require_once "./db/connect.php";
require_once "./common/functions.php";

if (isset($_GET['cid']) && isset($_GET['sid']) && $_GET['cid'] > 0 && $_GET['sid'] > 0) {
  $products = get_product_by_subcategory($_GET['cid'], $_GET['sid'], $connect);
} elseif (isset($_GET['cid']) && $_GET['cid'] > 0) {
  $products = get_product_by_category($_GET['cid'], $connect);
} elseif (isset($_GET['search']) && $_GET['search'] != "") {
  $products = get_product_by_search($_GET['search'], $connect);
} else {
  header('location:index.php');
}

$title = "Category";
require_once "./common/header.php";
include_once "./common/navbar.php";
?>

<div class="banner-in">
  <div class="container">
    <h1>Products</h1>
    <ul class="newbreadcrumb">
      <li><a href="<?= BASE_URL ?>">Home</a></li>
      <li>Products</li>
    </ul>
  </div>
</div>
<div id="main-container">
  <div class="container">

    <div class="row">
      <aside class="col-sm-3 hidden-xs" id="column-left">
        <h4 class="widget-title">CATEGORIES</h4>
        <ul class="category-list">
          <?php foreach (get_category($connect) as $l) : ?>
            <li>
              <a class="sidenav-link" href="<?= BASE_URL . 'category.php?cid=' . $l->id ?>"><?= $l->name ?></a>
              <ul class="collapse sublist">
                <?php
                foreach (get_subcategory($l->id, $connect) as $sl) {
                  echo '<li><a href="' . BASE_URL . 'category.php?cid=' . $l->id . '&sid=' . $sl->id . '">' . $sl->name . '</a></li>';
                }
                ?>
              </ul>
            </li>
          <?php endforeach; ?>
        </ul>
      </aside>
      <div class="col-sm-9" id="content">
        <div class="search-bar">
          <div class="row">
            <div class="col-md-4 col-sm-12"><a id="compare-total" href="#">Product Compare (0)</a></div>
            <div class="col-md-2 col-sm-2 text-right">
              <label for="input-sort" class="control-label">Sort By:</label>
            </div>
            <div class="col-md-3  col-sm-5 text-right">
              <select onchange="location = this.value;" class="form-control" id="input-sort">
                <option selected="selected">Default</option>
                <option>Name (A - Z)</option>
                <option>Name (Z - A)</option>
                <option>Price (Low &gt; High)</option>
                <option>Price (High &gt; Low)</option>
                <option>Rating (Highest)</option>
                <option>Rating (Lowest)</option>
                <option>Model (A - Z)</option>
                <option>Model (Z - A)</option>
              </select>
            </div>
            <div class="col-md-1 col-sm-3 text-right">
              <label for="input-limit" class="control-label">Show:</label>
            </div>
            <div class="col-md-2 col-sm-2 text-right">
              <select onchange="location = this.value;" class="form-control" id="input-limit">
                <option selected="selected">12</option>
                <option>24</option>
                <option>48</option>
                <option>96</option>
                <option>108</option>
              </select>
            </div>
          </div>
        </div>
        <br>
        <div class="row">
          <?php
          if ($products && count($products) > 0) :
            foreach ($products as $l) :
          ?>
              <div class="product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="product-thumb transition">
                  <a href="<?= BASE_URL . 'product.php?id=' . $l->id ?>">
                    <div class="image" style="display:flex;align-items:center;height: 150px;">
                      <img src="<?= BASE_URL . '/admin/dist/images/product/small/' . $l->id . '.' . $l->image_extension ?>" alt="" title="" class="img-responsive" />
                    </div>
                  </a>
                  <div class="caption">
                    <h4><a href="<?= BASE_URL . 'product.php?id=' . $l->id ?>"><?= $l->name ?></a></h4>
                    <div class="rating">
                      <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                      <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                      <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                      <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                      <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                    </div>
                    <p class="price">&#8364; <?= $l->price ?></p>
                  </div>
                </div>
              </div>
          <?php 
            endforeach;
            else:
              echo '<p class="text-danger text-center">No Product Found!</p>';
            endif;
          ?>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left"></div>
        </div>
      </div>
    </div>

  </div>
</div>
<script>
  /*** add active class and stay opened when selected ***/
  var url = window.location;
  var server = window.location.origin;
  var sidelink = document.querySelectorAll('.sidenav-link');
  var sublist = document.querySelectorAll('.sublist');
  sidelink.forEach((element, i) => {
    if (element.href == url || url.href.indexOf(element.href + '&sid=') == 0) {
      sublist[i].classList.remove('collapse');
    }
  })
</script>

<?php include_once "./common/footer.php"; ?>