<?php
//GET CATEGORY
function get_category($connect){
    $q = $connect->prepare("SELECT id,name,extension FROM category  WHERE isactive=:isactive and parent=:parent ORDER BY categoryorder");
    $q->execute([':isactive' => 1, ':parent' => 0]);
    return $q->fetchAll(PDO::FETCH_OBJ);
}
//GET SUBCATEGORY
function get_subcategory($parent,$connect)
{
    $q = $connect->prepare("SELECT id,name FROM category  WHERE isactive=:isactive and parent=:parent ORDER BY categoryorder");
    $q->execute([':isactive' => 1, ':parent' => $parent]);
    return $q->fetchAll(PDO::FETCH_OBJ);
}
//GET RANDOM SPECIAL PRODUCT
function get_random_special_product($connect){
    $q=$connect->prepare("SELECT id,name,price,extension FROM product WHERE isactive=:isactive AND special=:special ORDER BY RAND()");
    $q->execute([':isactive'=>1,':special'=>1]);
    return $q->fetchAll(PDO::FETCH_OBJ);
} 
?>