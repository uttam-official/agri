<?php
//GET CATEGORY
function get_category($connect)
{
    $q = $connect->prepare("SELECT id,name,extension FROM category  WHERE isactive=:isactive and parent=:parent ORDER BY categoryorder");
    $q->execute([':isactive' => 1, ':parent' => 0]);
    return $q->fetchAll(PDO::FETCH_OBJ);
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
    $q2->bindValue(2, $_POST['company']);
    $q2->bindValue(3, $_POST['address_1']);
    $q2->bindValue(4, $_POST['address_2']);
    $q2->bindValue(5, $_POST['city']);
    $q2->bindValue(6, $_POST['postcode']);
    $q2->bindValue(7, $_POST['country']);
    $q2->bindValue(8, $_POST['state']);
    if ($q2->execute()) {
        header('location:'.$url);
    }
}

//PLACE ORDER

function confirm_order($connect){
    session_status()==1?session_start():'';
    $q=$connect->prepare("INSERT INTO ordersummery (customer_id,billing_id,shipping_id,subtotal,discount,ecotax,vat,total,payment) VALUES(?,?,?,?,?,?,?,?,?)");
    $q->bindValue(1,$_SESSION['user_id']);
    $q->bindValue(2,$_SESSION['billing_address']);
    $q->bindValue(3,$_SESSION['shipping_address']);
    $q->bindValue(4,$_SESSION['checkout']['subtotal']);
    $q->bindValue(5,$_SESSION['checkout']['discount']);
    $q->bindValue(6,$_SESSION['checkout']['ecotax']);
    $q->bindValue(7,$_SESSION['checkout']['vat']);
    $q->bindValue(8,$_SESSION['checkout']['total']);
    $q->bindValue(9,$_SESSION['payment']);
    if($q->execute()){
        $order_id=$connect->lastInsertId();
        $sql="INSERT INTO orderinfo (ordersummery_id,product_id,product_price,quantity) VALUES (?,?,?,?)";
        foreach($_SESSION['cart'] as $l){
            $q1=$connect->prepare($sql);
            $q1->bindValue(1,$order_id);
            $q1->bindValue(2,$l['id']);
            $q1->bindValue(3,$l['price']);
            $q1->bindValue(4,$l['qty']);
            $q1->execute();
        }
        unset($_SESSION['checkout']);
        unset($_SESSION['cart']);
        $_SESSION['success_order_id']=$order_id;
        header('location:order_success.php');
    }
}