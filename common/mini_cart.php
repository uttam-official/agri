<?php
$page_data = array();
if (isset($_SESSION['cart']) && count($_SESSION['cart'])>0) {
    $page_data = $_SESSION['cart'];
}
?>

<li>
    <table class="table table-striped">
        <tbody>
            <?php $subtotal=0;$ecotax=0; foreach($page_data as $id=>$value): $ecotax+=2; $subtotal+=$value['qty'] * $value['price'];?>
            <tr>
                <td class="text-center"> <a href="<?= BASE_URL . 'product.php?id=' . $id ?>"><img class="img-thumbnail" title="" alt="" style="width:50px;" src="<?= BASE_URL . 'admin/dist/images/product/small/' . $id . '.' . $value['image_extension'] ?>"></a>
                </td>
                <td class="text-left" style="width:200px"><a href="<?= BASE_URL . 'product.php?id=' . $id ?>" ><?=$value['name']?></a>
                </td>
                <td class="text-right">x <?=$value['qty']?></td>
                <td class="text-right"><?=$value['qty']*$value['price']?></td>
                <td class="text-center"><a href="<?=BASE_URL.'remove_cart.php?remove='.$id.'&uri='.$_SERVER['REQUEST_URI']?>" class="btn btn-danger btn-xs" title="Remove" onclick=""><i class="fa fa-times"></i></a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</li>
<li>
    <div>
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td class="text-right"><strong>Sub-Total</strong></td>
                    <td class="text-right">$<?=$subtotal?></td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Eco Tax (-2.00)</strong></td>
                    <td class="text-right">$<?=$ecotax?></td>
                </tr>
                <tr>
                    <td class="text-right"><strong>VAT (20%)</strong></td>
                    <td class="text-right">$<?=$vat=$subtotal*20/100;?></td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Total</strong></td>
                    <td class="text-right">$<span id="total_mini"><?=$subtotal+$ecotax+$vat?></span></td>
                </tr>
            </tbody>
        </table>
        <p class="text-right"><a href="<?= BASE_URL . 'cart.php' ?>"><strong><i class="fa fa-shopping-cart"></i> View Cart</strong></a>&nbsp;&nbsp;&nbsp;<a href="#" class="checkout_mini"><strong><i class="fa fa-share"></i> Checkout</strong></a></p>
    </div>
</li>

<script>
    $('.checkout_mini').on('click',function(){
        if(Number($('#total_mini').html())<=0){
            Swal.fire({icon:'error',title:'Oops...',text:'Your cart is empty !'});
        }else{
            window.location="checkout.php";
        }
    })
</script>