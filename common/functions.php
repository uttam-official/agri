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
function get_product_by_category($category,$connect)
{
    $q=$connect->prepare('SELECT id,name,price,image_extension FROM product WHERE category=:category AND isactive=:isactive ORDER BY created');
    $q->execute([':category'=>$category,':isactive'=>1]);
    return $q->fetchAll(PDO::FETCH_OBJ);
}

// GET PRODUCT BY CATEGORY AND SUBCATEGORY
function get_product_by_subcategory($category,$subcategory,$connect){
    $q=$connect->prepare('SELECT id,name,price,image_extension FROM product WHERE category=:category AND subcategory=:subcategory AND isactive=:isactive ORDER BY created');
    $q->execute([':category'=>$category,':subcategory'=>$subcategory,':isactive'=>1]);
    return $q->fetchAll(PDO::FETCH_OBJ);
}

// GET PRODUCT BY CATEGORY
function get_product_by_search($key,$connect){
    $q=$connect->prepare('SELECT id,name,price,image_extension FROM product WHERE name LIKE :name AND isactive=:isactive ORDER BY name');
    $q->execute([':name'=>'%'.$key.'%',':isactive'=>1]);
    return $q->fetchAll(PDO::FETCH_OBJ);
}

//PRODUCT ADD TO CART
function add_to_cart($id,$qty,$connect){
    $q=$connect->prepare("SELECT id,name,image_extension,price FROM product WHERE id=:id");
    $q->execute([':id'=>$id]);
	$product=$q->fetch(PDO::FETCH_ASSOC);
	$product['qty']=(int) $qty;
	// var_dump($data[$id]['qty']);exit;
	session_status()==1?session_start():'';
	if(isset($_SESSION['cart']) && count($_SESSION['cart'])>0){
		if(isset($_SESSION['cart'][$id])){
			$product['qty']=$_SESSION['cart'][$id]['qty']+$product['qty'];
			$_SESSION['cart'][$id]=$product;
		}else{
			$_SESSION['cart'][$id]=$product;
		}
	}else{
		$_SESSION['cart']=array();
		$_SESSION['cart'][$id]=$product;
	}
	// unset($_SESSION['cart'][3]);
	// var_dump($_SESSION['cart'][2]['qty']);exit;
	return 1;
}
//UPDATE CART QUANTITY
function update_cart($id,$qty){
	session_status()==1?session_start():'';
	$_SESSION['cart'][$id]['qty']=$qty;
    if(isset($_SESSION['checkout'])){
        unset($_SESSION['checkout']);
        return 0;
    }
    return 1;
}

//PRODUCT REMOVE FROM CART
function remove_cart($id){
	session_status()==1?session_start():'';
	unset($_SESSION['cart'][$id]);
    if(isset($_SESSION['checkout'])){
        unset($_SESSION['checkout']);
        return 0;
    }
    return 1;
}
//VALIDATE COUPON
function validate_coupon($coupon,$connect){
    $q=$connect->prepare('SELECT validfrom,validtill,amount,type FROM discount WHERE name=:name AND isactive=:isactive');
    $q->execute([':name'=>$coupon,':isactive'=>1]);
    if($q->rowCount()>0){
        $data=$q->fetch(PDO::FETCH_OBJ);
        $validfrom=$data->validfrom;
        $validtill=$data->validtill;
        $stat1=$validfrom!=null?(date('Y-m-d',strtotime($validfrom))<=date('Y-m-d')?1:0):1;
        $stat2=$validtill!=null?(date('Y-m-d',strtotime($validtill))>=date('Y-m-d')?1:0):1;
        if($stat1 && $stat2){
            return ['status'=>true,'amount'=>$data->amount,'type'=>$data->type];
        }else{ 
            return ['status'=>false];
        }
    }else{
        return ['status'=>false];
    }
}
?>