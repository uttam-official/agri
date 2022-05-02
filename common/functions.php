<?php
//GET CATEGORY
function get_category($connect)
{
    $q = $connect->prepare("SELECT id,name,slug_url,extension FROM category  WHERE isactive=:isactive and parent=:parent ORDER BY categoryorder");
    $q->execute([':isactive' => 1, ':parent' => 0]);
    return $q->fetchAll(PDO::FETCH_OBJ);
}
// GET CATEGORY ID BY SLUG
function get_category_by_slug($slug,$connect){
    $q=$connect->prepare('SELECT id FROM category WHERE slug_url=? AND isactive=?');
    $q->bindValue(1,$slug);
    $q->bindValue(2,1);
    $q->execute();
    if($q->rowCount()>0){
        return $q->fetch(PDO::FETCH_OBJ)->id;
    }else{
        return 0;
    }
}
//GET SUBCATEGORY
function get_subcategory($parent, $connect)
{
    $q = $connect->prepare("SELECT id,name FROM category  WHERE isactive=:isactive and parent=:parent ORDER BY categoryorder");
    $q->execute([':isactive' => 1, ':parent' => $parent]);
    return $q->fetchAll(PDO::FETCH_OBJ);
}
//GET RANDOM SPECIAL PRODUCT
function get_random_special_product($connect)
{
    $q = $connect->prepare("SELECT id,name,price,image_extension FROM product WHERE isactive=:isactive AND special=:special ORDER BY RAND()");
    $q->execute([':isactive' => 1, ':special' => 1]);
    return $q->fetchAll(PDO::FETCH_OBJ);
}
//GET RANDOM FEATURED PRODUCT
function get_random_featured_product($connect)
{
    $q = $connect->prepare("SELECT id,name,price,image_extension FROM product WHERE isactive=:isactive AND featured=:featured ORDER BY RAND()");
    $q->execute([':isactive' => 1, ':featured' => 1]);
    return $q->fetchAll(PDO::FETCH_OBJ);
}
//GET SINGLE PRODUCT DETAILS
function get_single_product($id, $connect)
{
    $q = $connect->prepare('SELECT p.id,p.name,p.description,p.category,p.subcategory,c.name AS category_name,s.name AS subcategory_name,p.price,p.image_extension,p.availability,GROUP_CONCAT(g.extension) AS gallery FROM product p JOIN category c ON p.category=c.id AND c.isactive=:c_active JOIN category s ON p.subcategory=s.id AND s.isactive=:s_active LEFT JOIN productgallery g ON g.product_id=p.id AND g.isactive=:g_active WHERE p.id=:id AND p.isactive=:p_active');
    $q->execute([':c_active' => 1, ':s_active' => 1, ':g_active' => 1, ':id' => $id, ':p_active' => 1]);
    return $q->fetch(PDO::FETCH_OBJ);
}

//GET RELATED PRODUCTS
function get_related_product($id, $connect)
{
    $q = $connect->prepare('SELECT p.id,p.name,p.price,p.image_extension FROM product p JOIN product sp ON p.category=sp.category AND sp.id=:id WHERE p.isactive=:isactive AND p.id!=:p_id ORDER BY RAND()');
    $q->execute([':id' => $id, ':isactive' => 1, ':p_id' => $id]);
    return $q->fetchAll(PDO::FETCH_OBJ);
}

// GET PRODUCT BY CATEGORY
function get_product_by_category($category, $connect)
{
    $q = $connect->prepare('SELECT id,name,price,image_extension FROM product WHERE category=:category AND isactive=:isactive ORDER BY created');
    $q->execute([':category' => $category, ':isactive' => 1]);
    return $q->fetchAll(PDO::FETCH_OBJ);
}
// GET PRODUCT BY CATEGORY
function get_product_by_category_slug($slug, $connect)
{
    $category=get_category_by_slug($slug,$connect);
    $q = $connect->prepare('SELECT id,name,price,image_extension FROM product WHERE category=:category AND isactive=:isactive ORDER BY created');
    $q->execute([':category' => $category, ':isactive' => 1]);
    return $q->fetchAll(PDO::FETCH_OBJ);
}

// GET PRODUCT BY CATEGORY AND SUBCATEGORY
function get_product_by_subcategory($category, $subcategory, $connect)
{
    $q = $connect->prepare('SELECT id,name,price,image_extension FROM product WHERE category=:category AND subcategory=:subcategory AND isactive=:isactive ORDER BY created');
    $q->execute([':category' => $category, ':subcategory' => $subcategory, ':isactive' => 1]);
    return $q->fetchAll(PDO::FETCH_OBJ);
}

// GET PRODUCT BY CATEGORY
function get_product_by_search($key, $connect)
{
    $q = $connect->prepare('SELECT id,name,price,image_extension FROM product WHERE name LIKE :name AND isactive=:isactive ORDER BY name');
    $q->execute([':name' => '%' . $key . '%', ':isactive' => 1]);
    return $q->fetchAll(PDO::FETCH_OBJ);
}

//PRODUCT ADD TO CART
function add_to_cart($id, $qty, $connect)
{
    $q = $connect->prepare("SELECT id,name,image_extension,price FROM product WHERE id=:id");
    $q->execute([':id' => $id]);
    $product = $q->fetch(PDO::FETCH_ASSOC);
    $product['qty'] = (int) $qty;
    // var_dump($data[$id]['qty']);exit;
    session_status() == 1 ? session_start() : '';
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        if (isset($_SESSION['cart'][$id])) {
            $product['qty'] = $_SESSION['cart'][$id]['qty'] + $product['qty'];
            $_SESSION['cart'][$id] = $product;
        } else {
            $_SESSION['cart'][$id] = $product;
        }
    } else {
        $_SESSION['cart'] = array();
        $_SESSION['cart'][$id] = $product;
    }
    // unset($_SESSION['cart'][3]);
    // var_dump($_SESSION['cart'][2]['qty']);exit;
    return 1;
}
//UPDATE CART QUANTITY
function update_cart($id, $qty)
{
    session_status() == 1 ? session_start() : '';
    $_SESSION['cart'][$id]['qty'] = $qty;
    if (isset($_SESSION['checkout'])) {
        unset($_SESSION['checkout']);
        return 0;
    }
    return 1;
}

//PRODUCT REMOVE FROM CART
function remove_cart($id)
{
    session_status() == 1 ? session_start() : '';
    unset($_SESSION['cart'][$id]);
    if (isset($_SESSION['checkout'])) {
        unset($_SESSION['checkout']);
        return 0;
    }
    return 1;
}
//VALIDATE COUPON
function validate_coupon($coupon, $connect)
{
    $q = $connect->prepare('SELECT validfrom,validtill,amount,type FROM discount WHERE name=:name AND isactive=:isactive');
    $q->execute([':name' => $coupon, ':isactive' => 1]);
    if ($q->rowCount() > 0) {
        $data = $q->fetch(PDO::FETCH_OBJ);
        $validfrom = $data->validfrom;
        $validtill = $data->validtill;
        $stat1 = $validfrom != null ? (date('Y-m-d', strtotime($validfrom)) <= date('Y-m-d') ? 1 : 0) : 1;
        $stat2 = $validtill != null ? (date('Y-m-d', strtotime($validtill)) >= date('Y-m-d') ? 1 : 0) : 1;
        if ($stat1 && $stat2) {
            return ['status' => true, 'amount' => $data->amount, 'type' => $data->type];
        } else {
            return ['status' => false];
        }
    } else {
        return ['status' => false];
    }
}
//CHECK LOGIN
function verify_login()
{
    session_status() ? session_start() : '';
    !isset($_SESSION['user_id']) ? header('location:login.php') : '';
}
//CHECK ALREADY LOGGED IN
function is_logged()
{
    session_status() ? session_start() : '';
    isset($_SESSION['user_id']) ? header('location:address.php?addr=1') : '';
}
//GET CUSTOMER ADDRESSES
function get_customer_address($customer_id, $connect)
{
    $q = $connect->prepare("SELECT * FROM address WHERE customer_id=:id AND isactive=:active");
    $q->execute([':id' => $customer_id, ':active' => 1]);
    return $q->fetchAll(PDO::FETCH_OBJ);
}

//ADD CUSTOMER ADDRESS
function add_customer_address($address, $customer_id, $url, $connect)
{
    $q2 = $connect->prepare('INSERT INTO address (customer_id,company,address1,address2,city,postcode,country,state) VALUES (?,?,?,?,?,?,?,?) ');
    $q2->bindValue(1, $customer_id);
    $q2->bindValue(2, $address['company']);
    $q2->bindValue(3, $address['address_1']);
    $q2->bindValue(4, $address['address_2']);
    $q2->bindValue(5, $address['city']);
    $q2->bindValue(6, $address['postcode']);
    $q2->bindValue(7, $address['country']);
    $q2->bindValue(8, $address['state']);
    if ($q2->execute()) {
        header('location:' . $url);
    }
}

//PLACE ORDER

function confirm_order($connect)
{
    session_status() == 1 ? session_start() : '';
    $q = $connect->prepare("INSERT INTO ordersummery (customer_id,billing_id,shipping_id,subtotal,discount,ecotax,vat,total,payment) VALUES(?,?,?,?,?,?,?,?,?)");
    $q->bindValue(1, $_SESSION['user_id']);
    $q->bindValue(2, $_SESSION['billing_address']);
    $q->bindValue(3, $_SESSION['shipping_address']);
    $q->bindValue(4, $_SESSION['checkout']['subtotal']);
    $q->bindValue(5, $_SESSION['checkout']['discount']);
    $q->bindValue(6, $_SESSION['checkout']['ecotax']);
    $q->bindValue(7, $_SESSION['checkout']['vat']);
    $q->bindValue(8, $_SESSION['checkout']['total']);
    $q->bindValue(9, $_SESSION['payment']);
    if ($q->execute()) {
        $order_id = $connect->lastInsertId();
        $sql = "INSERT INTO orderinfo (ordersummery_id,product_id,product_price,quantity) VALUES (?,?,?,?)";
        foreach ($_SESSION['cart'] as $l) {
            $q1 = $connect->prepare($sql);
            $q1->bindValue(1, $order_id);
            $q1->bindValue(2, $l['id']);
            $q1->bindValue(3, $l['price']);
            $q1->bindValue(4, $l['qty']);
            $q1->execute();
        }

        $htmlMsg = get_email_template($order_id,$connect);

        unset($_SESSION['checkout']);
        unset($_SESSION['cart']);
        $_SESSION['success_order_id'] = $order_id;
        $mail_id =$_SESSION['user_mail'];
        $fromName='Agri Express';
        $from='order@agriexpress.com';
        $subject = 'Order Confirmation #' . $order_id;
        //$htmlMsg="Your order successfully placed <br> order id #".$order_id;
        
        // Set content-type header for sending HTML email 
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // Additional headers 
        $headers .= 'From: ' . $fromName . '<' . $from . '>' . "\r\n";
        mail($mail_id, $subject, $htmlMsg, $headers);


        header('location:order_success.php');
    }
}


//GET EMAIL TEMPLATE
function get_email_template($order_id,$connect){
 session_status()?session_start():'';
 $template='
 <!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Order Confirmation</title>
    <style type="text/css">
      a { text-decoration: none; outline: none; }
      @media (max-width: 649px) {
        .o_col-full { max-width: 100% !important; }
        .o_col-half { max-width: 50% !important; }
        .o_hide-lg { display: inline-block !important; font-size: inherit !important; max-height: none !important; line-height: inherit !important; overflow: visible !important; width: auto !important; visibility: visible !important; }
        .o_hide-xs, .o_hide-xs.o_col_i { display: none !important; font-size: 0 !important; max-height: 0 !important; width: 0 !important; line-height: 0 !important; overflow: hidden !important; visibility: hidden !important; height: 0 !important; }
        .o_xs-center { text-align: center !important; }
        .o_xs-left { text-align: left !important; }
        .o_xs-right { text-align: left !important; }
        table.o_xs-left { margin-left: 0 !important; margin-right: auto !important; float: none !important; }
        table.o_xs-right { margin-left: auto !important; margin-right: 0 !important; float: none !important; }
        table.o_xs-center { margin-left: auto !important; margin-right: auto !important; float: none !important; }
        h1.o_heading { font-size: 32px !important; line-height: 41px !important; }
        h2.o_heading { font-size: 26px !important; line-height: 37px !important; }
        h3.o_heading { font-size: 20px !important; line-height: 30px !important; }
        .o_xs-py-md { padding-top: 24px !important; padding-bottom: 24px !important; }
        .o_xs-pt-xs { padding-top: 8px !important; }
        .o_xs-pb-xs { padding-bottom: 8px !important; }
      }
      @media screen {
        @font-face {
          font-family: "Roboto";
          font-style: normal;
          font-weight: 400;
          src: local("Roboto"), local("Roboto-Regular"), url(https://fonts.gstatic.com/s/roboto/v18/KFOmCnqEu92Fr1Mu7GxKOzY.woff2) format("woff2");
          unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF; }
        @font-face {
          font-family: "Roboto";
          font-style: normal;
          font-weight: 400;
          src: local("Roboto"), local("Roboto-Regular"), url(https://fonts.gstatic.com/s/roboto/v18/KFOmCnqEu92Fr1Mu4mxK.woff2) format("woff2");
          unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD; }
        @font-face {
          font-family: "Roboto";
          font-style: normal;
          font-weight: 700;
          src: local("Roboto Bold"), local("Roboto-Bold"), url(https://fonts.gstatic.com/s/roboto/v18/KFOlCnqEu92Fr1MmWUlfChc4EsA.woff2) format("woff2");
          unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF; }
        @font-face {
          font-family: "Roboto";
          font-style: normal;
          font-weight: 700;
          src: local("Roboto Bold"), local("Roboto-Bold"), url(https://fonts.gstatic.com/s/roboto/v18/KFOlCnqEu92Fr1MmWUlfBBc4.woff2) format("woff2");
          unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD; }
        .o_sans, .o_heading { font-family: "Roboto", sans-serif !important; }
        .o_heading, strong, b { font-weight: 700 !important; }
        a[x-apple-data-detectors] { color: inherit !important; text-decoration: none !important; }
      }
    </style>
  </head>
  <body class="o_body o_bg-light" style="width: 100%;margin: 0px;padding: 0px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;background-color: #dbe5ea;">
    <!-- preview-text -->
    <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
      <tbody>
        <tr>
          <td class="o_hide" align="center" style="display: none;font-size: 0;max-height: 0;width: 0;line-height: 0;overflow: hidden;mso-hide: all;visibility: hidden;">Email Summary (Hidden)</td>
        </tr>
      </tbody>
    </table>
    <!-- header-button -->
    <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
      <tbody>
        <tr>
          <td class="o_bg-light o_px-xs o_pt-lg o_xs-pt-xs" align="center" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;padding-top: 32px;">
            <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
            <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
              <tbody>
                <tr>
                  <td class="o_re o_bg-dark o_px o_pb-md o_br-t" align="center" style="font-size: 0;vertical-align: top;background-color: #242b3d;border-radius: 4px 4px 0px 0px;padding-left: 16px;padding-right: 16px;padding-bottom: 24px;">
                    <!--[if mso]><table cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td width="200" align="left" valign="top" style="padding:0px 8px;"><![endif]-->
                    <div class="o_col o_col-2" style="display: inline-block;vertical-align: top;width: 100%;max-width: 200px;">
                      <div style="font-size: 24px; line-height: 24px; height: 24px;">&nbsp; </div>
                      <div class="o_px-xs o_sans o_text o_left o_xs-center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;text-align: left;padding-left: 8px;padding-right: 8px;">
                        <p style="margin-top: 0px;margin-bottom: 0px;"><a class="o_text-white" href="#" style="text-decoration: none;outline: none;color: #ffffff;">Agri Express</a></p>
                      </div>
                    </div>
                    <!--[if mso]></td><td width="400" align="right" valign="top" style="padding:0px 8px;"><![endif]-->
                    <div class="o_col o_col-4" style="display: inline-block;vertical-align: top;width: 100%;max-width: 400px;">
                      <div style="font-size: 24px; line-height: 24px; height: 24px;">&nbsp; </div>
                      <div class="o_px-xs" style="padding-left: 8px;padding-right: 8px;">
                        <table class="o_right o_xs-center" cellspacing="0" cellpadding="0" border="0" role="presentation" style="text-align: right;margin-left: auto;margin-right: 0;">
                          <tbody>
                            <tr>
                              <td class="o_btn-xs o_bg-primary o_br o_heading o_text-xs" align="center" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;mso-padding-alt: 7px 16px;background-color: #126de5;border-radius: 4px;">
                                <a class="o_text-white" href="#" style="text-decoration: none;outline: none;color: #ffffff;display: block;padding: 7px 16px;mso-text-raise: 3px;">Visit</a>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <!--[if mso]></td></tr></table><![endif]-->
                  </td>
                </tr>
              </tbody>
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
          </td>
        </tr>
      </tbody>
    </table>
    <!-- hero-icon-outline -->
    <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
      <tbody>
        <tr>
          <td class="o_bg-light o_px-xs" align="center" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
            <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
            <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
              <tbody>
                <tr>
                  <td class="o_bg-ultra_light o_px-md o_py-xl o_xs-py-md o_sans o_text-md o_text-light" align="center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 19px;line-height: 28px;background-color: #ebf5fa;color: #82899a;padding-left: 24px;padding-right: 24px;padding-top: 64px;padding-bottom: 64px;">
                    <table cellspacing="0" cellpadding="0" border="0" role="presentation">
                      <tbody>
                        <tr>
                          <td class="o_sans o_text o_text-secondary o_b-primary o_px o_py o_br-max" align="center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;border: 2px solid #126de5;border-radius: 96px;padding-left: 16px;padding-right: 16px;padding-top: 16px;padding-bottom: 16px;">
                            <img src="images/shopping_cart-48-primary.png" width="48" height="48" alt="" style="max-width: 48px;-ms-interpolation-mode: bicubic;vertical-align: middle;border: 0;line-height: 100%;height: auto;outline: none;text-decoration: none;">
                          </td>
                        </tr>
                        <tr>
                          <td style="font-size: 24px; line-height: 24px; height: 24px;">&nbsp; </td>
                        </tr>
                      </tbody>
                    </table>
                    <h2 class="o_heading o_text-dark o_mb-xxs" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 4px;color: #242b3d;font-size: 30px;line-height: 39px;">Order Confirmation</h2>
                    <p style="margin-top: 0px;margin-bottom: 0px;">That thus much less heron other hello</p>
                  </td>
                </tr>
              </tbody>
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
          </td>
        </tr>
      </tbody>
    </table>
    <!-- spacer -->
    <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
      <tbody>
        <tr>
          <td class="o_bg-light o_px-xs" align="center" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
            <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
            <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
              <tbody>
                <tr>
                  <td class="o_bg-white" style="font-size: 24px;line-height: 24px;height: 24px;background-color: #ffffff;">&nbsp; </td>
                </tr>
              </tbody>
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
          </td>
        </tr>
      </tbody>
    </table>
    <!-- order-intro -->
    <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
      <tbody>
        <tr>
          <td class="o_bg-light o_px-xs" align="center" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
            <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
            <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
              <tbody>
                <tr>
                  <td class="o_bg-white o_px-md o_py o_sans o_text o_text-secondary" align="center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;background-color: #ffffff;color: #424651;padding-left: 24px;padding-right: 24px;padding-top: 16px;padding-bottom: 16px;">
                    <h4 class="o_heading o_text-dark o_mb-xs" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 8px;color: #242b3d;font-size: 18px;line-height: 23px;">Hello, '.$_SESSION['user_name'].'</h4>
                    <p class="o_mb-md" style="margin-top: 0px;margin-bottom: 24px;">Thank you for ordering from Agri Express. You can track your shipment status using our mobile tracking. See your order confirmation below.</p>
                    <table align="center" cellspacing="0" cellpadding="0" border="0" role="presentation">
                      <tbody>
                        <tr>
                          <td width="300" class="o_btn o_bg-success o_br o_heading o_text" align="center" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;mso-padding-alt: 12px 24px;background-color: #0ec06e;border-radius: 4px;">
                            <a class="o_text-white" href="#" style="text-decoration: none;outline: none;color: #ffffff;display: block;padding: 12px 24px;mso-text-raise: 3px;">Track My Order</a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <div style="font-size: 28px; line-height: 28px; height: 28px;">&nbsp; </div>
                    <h4 class="o_heading o_text-dark o_mb-xxs" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 4px;color: #242b3d;font-size: 18px;line-height: 23px;">Order #'.$order_id.'</h4>
                    <p class="o_text-xs o_text-light" style="font-size: 14px;line-height: 21px;color: #82899a;margin-top: 0px;margin-bottom: 0px;">Placed on March 14, 2018 1:20:32 PM FET</p>
                  </td>
                </tr>
              </tbody>
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
          </td>
        </tr>
      </tbody>
    </table>
    <!-- order-details -->
    <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
      <tbody>
        <tr>
          <td class="o_bg-light o_px-xs" align="center" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
            <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
            <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
              <tbody>
                <tr>
                  <td class="o_re o_bg-white o_px o_pb-md" align="center" style="font-size: 0;vertical-align: top;background-color: #ffffff;padding-left: 16px;padding-right: 16px;padding-bottom: 24px;">
                    <!--[if mso]><table cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td width="300" align="center" valign="top" style="padding: 0px 8px;"><![endif]-->
                    <div class="o_col o_col-3 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 300px;">
                      <div style="font-size: 24px; line-height: 24px; height: 24px;">&nbsp; </div>
                      <div class="o_px-xs" style="padding-left: 8px;padding-right: 8px;">
                        <table width="100%" role="presentation" cellspacing="0" cellpadding="0" border="0">
                          <tbody>
                            <tr>
                              <td class="o_bg-ultra_light o_br o_px o_py o_sans o_text-xs o_text-secondary" align="left" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;background-color: #ebf5fa;color: #424651;border-radius: 4px;padding-left: 16px;padding-right: 16px;padding-top: 16px;padding-bottom: 16px;">
                                <p class="o_mb-xs" style="margin-top: 0px;margin-bottom: 8px;"><strong>Billing Information</strong></p>
                                <p class="o_mb-md" style="margin-top: 0px;margin-bottom: 24px;">'.$_SESSION['user_name'].'<br>
                                    '.get_single_address($_SESSION['billing_address'],$connect).'  
                                </p>
                                <p class="o_mb-xs" style="margin-top: 0px;margin-bottom: 8px;"><strong>Payment Method</strong></p>
                                <p style="margin-top: 0px;margin-bottom: 0px;">Cash on Delivery</p>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <!--[if mso]></td><td width="300" align="center" valign="top" style="padding: 0px 8px;"><![endif]-->
                    <div class="o_col o_col-3 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 300px;">
                      <div style="font-size: 24px; line-height: 24px; height: 24px;">&nbsp; </div>
                      <div class="o_px-xs" style="padding-left: 8px;padding-right: 8px;">
                        <table width="100%" role="presentation" cellspacing="0" cellpadding="0" border="0">
                          <tbody>
                            <tr>
                              <td class="o_bg-ultra_light o_br o_px o_py o_sans o_text-xs o_text-secondary" align="left" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;background-color: #ebf5fa;color: #424651;border-radius: 4px;padding-left: 16px;padding-right: 16px;padding-top: 16px;padding-bottom: 16px;">
                                <p class="o_mb-xs" style="margin-top: 0px;margin-bottom: 8px;"><strong>Shipping Information</strong></p>
                                <p class="o_mb-md" style="margin-top: 0px;margin-bottom: 24px;">'.$_SESSION['user_name'].'<br>
                                '.get_single_address($_SESSION['billing_address'],$connect).'
                                </p>
                                <p class="o_mb-xs" style="margin-top: 0px;margin-bottom: 8px;"><strong>Shipping Method</strong></p>
                                <p style="margin-top: 0px;margin-bottom: 0px;">FedEx</p>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <!--[if mso]></td></tr></table><![endif]-->
                  </td>
                </tr>
              </tbody>
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
          </td>
        </tr>
      </tbody>
    </table>
    <!-- order-summary -->
    <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
      <tbody>
        <tr>
          <td class="o_bg-light o_px-xs" align="center" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
            <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
            <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
              <tbody>
                <tr>
                  <td class="o_bg-white o_sans o_text-xs o_text-light o_px-md o_pt-xs" align="center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;background-color: #ffffff;color: #82899a;padding-left: 24px;padding-right: 24px;padding-top: 8px;">
                    <p style="margin-top: 0px;margin-bottom: 0px;">Order Summary</p>
                    <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                      <tbody>
                        <tr>
                          <td class="o_re o_bb-light" style="font-size: 8px;line-height: 8px;height: 8px;vertical-align: top;border-bottom: 1px solid #d3dce0;">&nbsp; </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
          </td>
        </tr>
      </tbody>
    </table>
    <!-- product -->
    
    '.get_cart_product().'
    
    
    <!-- end product -->
    <!-- invoice-total -->
    <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
      <tbody>
        <tr>
          <td class="o_bg-light o_px-xs" align="center" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
            <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
            <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
              <tbody>
                <tr>
                  <td class="o_re o_bg-white o_px-md o_py" align="right" style="font-size: 0;vertical-align: top;background-color: #ffffff;padding-left: 24px;padding-right: 24px;padding-top: 16px;padding-bottom: 16px;">
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                      <tbody>
                        <tr>
                          <td width="284" align="left">
                            <table width="100%" role="presentation" cellspacing="0" cellpadding="0" border="0">
                              <tbody>
                                <tr>
                                  <td width="50%" class="o_pt-xs" align="left" style="padding-top: 8px;">
                                    <p class="o_sans o_text o_text-secondary" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;">Subtotal</p>
                                  </td>
                                  <td width="50%" class="o_pt-xs" align="right" style="padding-top: 8px;">
                                    <p class="o_sans o_text o_text-secondary" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;">$'.$_SESSION['checkout']['subtotal'].'</p>
                                  </td>
                                </tr>
                                <tr>
                                  <td width="50%" class="o_pt-xs" align="left" style="padding-top: 8px;">
                                    <p class="o_sans o_text o_text-secondary" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;">Discount</p>
                                  </td>
                                  <td width="50%" class="o_pt-xs" align="right" style="padding-top: 8px;">
                                    <p class="o_sans o_text o_text-secondary" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;">$'.$_SESSION['checkout']['discount'].'</p>
                                  </td>
                                </tr>
                                <tr>
                                  <td width="50%" class="o_pt-xs" align="left" style="padding-top: 8px;">
                                    <p class="o_sans o_text o_text-secondary" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;">Ecotax</p>
                                  </td>
                                  <td width="50%" class="o_pt-xs" align="right" style="padding-top: 8px;">
                                    <p class="o_sans o_text o_text-secondary" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;">$'.$_SESSION['checkout']['ecotax'].'</p>
                                  </td>
                                </tr>
                                <tr>
                                  <td width="50%" class="o_pt-xs" align="left" style="padding-top: 8px;">
                                    <p class="o_sans o_text o_text-secondary" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;">Vat</p>
                                  </td>
                                  <td width="50%" class="o_pt-xs" align="right" style="padding-top: 8px;">
                                    <p class="o_sans o_text o_text-secondary" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;">$'.$_SESSION['checkout']['vat'].'</p>
                                  </td>
                                </tr>
                                <tr>
                                  <td class="o_pt o_bb-light" style="border-bottom: 1px solid #d3dce0;padding-top: 16px;">&nbsp; </td>
                                  <td class="o_pt o_bb-light" style="border-bottom: 1px solid #d3dce0;padding-top: 16px;">&nbsp; </td>
                                </tr>
                                <tr>
                                  <td width="50%" class="o_pt" align="left" style="padding-top: 16px;">
                                    <p class="o_sans o_text o_text-secondary" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;"><strong>Total Due</strong></p>
                                  </td>
                                  <td width="50%" class="o_pt" align="right" style="padding-top: 16px;">
                                    <p class="o_sans o_text o_text-primary" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #126de5;"><strong>$'.$_SESSION['checkout']['total'].'</strong></p>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
          </td>
        </tr>
      </tbody>
    </table>
    <!-- spacer -->
    <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
      <tbody>
        <tr>
          <td class="o_bg-light o_px-xs" align="center" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
            <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
            <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
              <tbody>
                <tr>
                  <td class="o_bg-white" style="font-size: 24px;line-height: 24px;height: 24px;background-color: #ffffff;">&nbsp; </td>
                </tr>
              </tbody>
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
          </td>
        </tr>
      </tbody>
    </table>
    <!-- footer-3cols -->
    <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
      <tbody>
        <tr>
          <td class="o_bg-light o_px-xs o_pb-lg o_xs-pb-xs" align="center" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;padding-bottom: 32px;">
            <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
            <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
              <tbody>
                <tr>
                  <td class="o_re o_bg-dark o_px o_pb-lg" align="center" style="font-size: 0;vertical-align: top;background-color: #242b3d;padding-left: 16px;padding-right: 16px;padding-bottom: 32px;">
                    <!--[if mso]><table cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td width="200" align="center" valign="top" style="padding:0px 8px;"><![endif]-->
                    <div class="o_col o_col-2 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 200px;">
                      <div style="font-size: 32px; line-height: 32px; height: 32px;">&nbsp; </div>
                      <div class="o_px-xs o_sans o_text-xs o_center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;text-align: center;padding-left: 8px;padding-right: 8px;">
                        <p style="margin-top: 0px;margin-bottom: 0px;"><a class="o_text-dark_light" href="#" style="text-decoration: none;outline: none;color: #a0a3ab;"><strong style="color: #a0a3ab;">Help Center</strong></a></p>
                      </div>
                    </div>
                    <!--[if mso]></td><td width="200" align="center" valign="top" style="padding:0px 8px;"><![endif]-->
                    <div class="o_col o_col-2 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 200px;">
                      <div style="font-size: 24px; line-height: 24px; height: 24px;">&nbsp; </div>
                      <div class="o_px-xs o_sans o_text-xs o_center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;text-align: center;padding-left: 8px;padding-right: 8px;">
                        <p style="margin-top: 0px;margin-bottom: 0px;">
                          <a class="o_text-dark_light" href="#" style="text-decoration: none;outline: none;color: #a0a3ab;"><img src="images/facebook-light.png" width="36" height="36" alt="fb" style="max-width: 36px;-ms-interpolation-mode: bicubic;vertical-align: middle;border: 0;line-height: 100%;height: auto;outline: none;text-decoration: none;"></a><span> &nbsp;</span>
                          <a class="o_text-dark_light" href="#" style="text-decoration: none;outline: none;color: #a0a3ab;"><img src="images/twitter-light.png" width="36" height="36" alt="tw" style="max-width: 36px;-ms-interpolation-mode: bicubic;vertical-align: middle;border: 0;line-height: 100%;height: auto;outline: none;text-decoration: none;"></a><span> &nbsp;</span>
                          <a class="o_text-dark_light" href="#" style="text-decoration: none;outline: none;color: #a0a3ab;"><img src="images/instagram-light.png" width="36" height="36" alt="ig" style="max-width: 36px;-ms-interpolation-mode: bicubic;vertical-align: middle;border: 0;line-height: 100%;height: auto;outline: none;text-decoration: none;"></a><span> &nbsp;</span>
                          <a class="o_text-dark_light" href="#" style="text-decoration: none;outline: none;color: #a0a3ab;"><img src="images/snapchat-light.png" width="36" height="36" alt="sc" style="max-width: 36px;-ms-interpolation-mode: bicubic;vertical-align: middle;border: 0;line-height: 100%;height: auto;outline: none;text-decoration: none;"></a>
                        </p>
                      </div>
                    </div>
                    <!--[if mso]></td><td width="200" align="center" valign="top" style="padding:0px 8px;"><![endif]-->
                    <div class="o_col o_col-2 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 200px;">
                      <div style="font-size: 32px; line-height: 32px; height: 32px;">&nbsp; </div>
                      <div class="o_px-xs o_sans o_text-xs o_center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;text-align: center;padding-left: 8px;padding-right: 8px;">
                        <p style="margin-top: 0px;margin-bottom: 0px;"><a class="o_text-dark_light" href="#" style="text-decoration: none;outline: none;color: #a0a3ab;"><strong style="color: #a0a3ab;">Preferences</strong></a></p>
                      </div>
                    </div>
                    <!--[if mso]></td></tr></table><![endif]-->
                  </td>
                </tr>
                <tr>
                  <td class="o_bg-dark o_px-md o_pb-lg o_br-b o_sans o_text-xs o_text-dark_light" align="center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;background-color: #242b3d;color: #a0a3ab;border-radius: 0px 0px 4px 4px;padding-left: 24px;padding-right: 24px;padding-bottom: 32px;">
                    
                    <p class="o_mb-xs" style="margin-top: 0px;margin-bottom: 8px;">©2018 Agri Express Inc<br>
                      2603 Woodridge Lane, Memphis, TN 38104, USA
                    </p>
                    <p style="margin-top: 0px;margin-bottom: 0px;">
                      <a class="o_text-xxs o_text-dark_light o_underline" href="#" style="text-decoration: underline;outline: none;font-size: 12px;line-height: 19px;color: #a0a3ab;">Unsubscribe</a>
                    </p>
                  </td>
                </tr>
              </tbody>
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
            <div class="o_hide-xs" style="font-size: 64px; line-height: 64px; height: 64px;">&nbsp; </div>
          </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>
 ';

 return $template;
}

//GET SINGLE ADDRESS
function get_single_address($id,$connect){
    $q=$connect->prepare("SELECT company,address1,address2,city,state,country,postcode FROM address WHERE id=:id");
    $q->execute([':id'=>$id]);
    $data=$q->fetch(PDO::FETCH_ASSOC);
    return implode(',',array_filter($data));
}


//GET CART PRODUCTS FOR MAIL
function get_cart_product(){
    $data='';
    foreach($_SESSION['cart'] as $l){
        $data.=get_single_cart_product($l);
    }
    return $data;
}
function get_single_cart_product($l){
    return '
    <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
    <tbody>
      <tr>
        <td class="o_bg-light o_px-xs" align="center" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
          <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
          <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
            <tbody>
              <tr>
                <td class="o_re o_bg-white o_px o_pt" align="center" style="font-size: 0;vertical-align: top;background-color: #ffffff;padding-left: 16px;padding-right: 16px;padding-top: 16px;">
                  <!--[if mso]><table cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td width="200" align="center" valign="top" style="padding: 0px 8px;"><![endif]-->
                  <div class="o_col o_col-2 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 200px;">
                    <div class="o_px-xs o_sans o_text o_center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;text-align: center;padding-left: 8px;padding-right: 8px;">
                      <p style="margin-top: 0px;margin-bottom: 0px;"><a class="o_text-primary" href="#" style="text-decoration: none;outline: none;color: #126de5;"><img src="images/thumb_184.jpg" width="184" height="184" alt="" style="max-width: 184px;-ms-interpolation-mode: bicubic;vertical-align: middle;border: 0;line-height: 100%;height: auto;outline: none;text-decoration: none;"></a></p>
                    </div>
                  </div>
                  <!--[if mso]></td><td width="300" align="left" valign="top" style="padding: 0px 8px;"><![endif]-->
                  <div class="o_col o_col-3 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 300px;">
                    <div style="font-size: 24px; line-height: 24px; height: 24px;">&nbsp; </div>
                    <div class="o_px-xs o_sans o_text o_text-light o_left o_xs-center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #82899a;text-align: left;padding-left: 8px;padding-right: 8px;">
                      <h4 class="o_heading o_text-dark o_mb-xxs" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 4px;color: #242b3d;font-size: 18px;line-height: 23px;">'.$l['name'].'</h4>
                      
                      <p class="o_text-xs o_mb-xs" style="font-size: 14px;line-height: 21px;margin-top: 0px;margin-bottom: 8px;">
                        Price: '.$l['price'].'<br>
                        Quantity: '.$l['qty'].'
                      </p>
                    </div>
                  </div>
                  <!--[if mso]></td><td width="100" align="right" valign="top" style="padding: 0px 8px;"><![endif]-->
                  <div class="o_col o_col-1 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 100px;">
                    <div class="o_hide-xs" style="font-size: 24px; line-height: 24px; height: 24px;">&nbsp; </div>
                    <div class="o_px-xs o_sans o_text o_text-secondary o_right o_xs-center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;text-align: right;padding-left: 8px;padding-right: 8px;">
                      <p style="margin-top: 0px;margin-bottom: 0px;">$'.$l['price']*$l['qty'].'</p>
                    </div>
                  </div>
                  <!--[if mso]></td></tr><tr><td colspan="3" style="padding: 0px 8px;"><![endif]-->
                  <div class="o_px-xs" style="padding-left: 8px;padding-right: 8px;">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                      <tbody>
                        <tr>
                          <td class="o_re o_bb-light" style="font-size: 16px;line-height: 16px;height: 16px;vertical-align: top;border-bottom: 1px solid #d3dce0;">&nbsp; </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <!--[if mso]></td></tr></table><![endif]-->
                </td>
              </tr>
            </tbody>
          </table>
          <!--[if mso]></td></tr></table><![endif]-->
        </td>
      </tr>
    </tbody>
  </table>
    ';
}