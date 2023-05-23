<!DOCTYPE html>
<html>
<head>
	<title>My Cart || GetValue</title>
</head>
<body>
	<div class="panel panel-info">
		<div class="panel-heading">
			<h2 class="panel-title">Cart Items</h2>
		</div>
		<div class="panel-body cBody">
            <p><?php if(sizeOf($cart)==0){ echo 'No any product in your cart...';}?></p>
            <div class="table-responsive">
    			<table class="table">
                    <thead>
                        <th>#</th>
                        <th>Product</th>
                        <th>Price (Tsh.)</th>
                        <th>Quantity</th>
                        <th>Total Price (Tsh.)</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <?php 
                            $counter=0; $totalQtn=0; $totalPrice=0;
                            foreach($cart as $product){  
                                $counter++;
                                $total=($product->price*$product->quantity);
                                $totalQtn=$totalQtn+$product->quantity;
                                $totalPrice=$totalPrice+$total;
                            ?>
                        <tr>
                            <td><?php echo $counter; ?></td>
                            <td><a href="<?php echo base_url().'prod/'.$product->product_url; ?>"><img src="<?php echo base_url().'media/products/thumb/'.$product->image; ?>" class="cartImg m-r-10"><?php echo ucwords($product->name); ?></a></td>
                            <td><?php echo number_format($product->price); ?></td>
                            <td>
                                <?php echo $product->quantity; ?>
                                <!-- <div class="quantity clearfix">
                                    <?php if($product->avQtns <= $product->quantity){?>
                                    <a class="plus refresh-me" data-id="16" href="javascript:void(0);" onclick="alert('Cart item should not be higher than the available quantity');">
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </a>
                                    <?php }else{?>
                                    <a class="plus refresh-me" data-id="16" href="javascript:void(0);" onclick="addCartQuantity(<?php echo $product->cartID; ?>, '<?php echo $product->name; ?>');">
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </a>
                                    <?php }?>
                                    <span class="quantity-num qty"><?php echo $product->quantity; ?></span>
                                    <?php if($product->quantity<2){ ?>
                                    <a class="minus" href="javascript:void(0);" onclick="alert('Cart should not be below 1 item.');">
                                        <span class="glyphicon glyphicon-minus"></span>
                                    </a>
                                    <?php }else{ ?>
                                    <a class="minus" onclick="removeCartQuantity(<?php echo $product->cartID; ?>, '<?php echo $product->name; ?>');" href="javascript:void(0);">
                                        <span class="glyphicon glyphicon-minus"></span>
                                    </a>
                                    <?php }?>
                                </div> -->
                            </td>
                            <td><?php echo number_format($total); ?></td>
                            <td><a href="javascript:void(0);" onclick="removeItem(<?php echo $product->cartID; ?>, '<?php echo $product->name; ?>')"><i class="ti-trash text-danger"></i></a></td>
                        </tr>
                        <?php }?>
                    </tbody>
                    <tfoot>
                        <td colspan="3" class="p-l-20"><p class="p-10"><strong>Total</strong></p></td>
                        <td><p class="p-10"><?php echo $totalQtn;?></p></td>
                        <td><p class="p-t-10"><?php echo number_format($totalPrice);?></p></td>
                        <td></td>
                    </tfoot>
                </table>
            </div>
            <p class="sm-hint">Scroll horizontal to view more</p>
            <hr />
            <div>
                <a href="<?php echo base_url();?>" class="btn btn-primary go-shop pull-left">
                    <span class="glyphicon glyphicon-circle-arrow-left"></span>
                    Back to shop        </a>
                <?php if(sizeOf($cart) > 0){ ?>
                <a class="btn btn-primary go-checkout pull-right" href="<?php echo base_url();?>home/checkout">
                    Checkout 
                    <i class="fa fa-credit-card-alt" aria-hidden="true"></i>
                </a>
                <?php } ?>
            </div>
            <div class="clearfix"></div>
		</div>
	</div>
</body>
</html>
<script type="text/javascript">
  $(document).ready(function(){
        
  });

    function addCartQuantity(cart, name){
        if (confirm("Are you sure you want to increment "+name+" in your cart?")) {
            ShowNotificator('alert-success', "Processing...");
            $.ajax({
                url:'<?= base_url() ?>customer/Customer/add_cart_quantity/'+cart,
                type:"POST",
                success: function(data){
                    if(data.trim()=='Success'){
                        ShowNotificator('alert-success', name+" - incremented to your cart");
                        window.location="";
                    }else if(data.trim()=='login'){
                        ShowNotificator('alert-info', "Please login to continue");
                        window.location="<?php echo base_url().'login'; ?>";
                    }else{
                        ShowNotificator('alert-danger', data);
                    }
                },
                error: function(xhr, status, error) {
                    ShowNotificator('alert-danger', error);
                    return false;
                }
            });
        }
    }

    function removeCartQuantity(cart, name){
        if (confirm("Are you sure you want to decrement "+name+" from your cart?")) {
            ShowNotificator('alert-success', "Processing...");
            $.ajax({
                url:'<?= base_url() ?>customer/Customer/remove_cart_quantity/'+cart,
                type:"POST",
                success: function(data){
                    if(data.trim()=='Success'){
                        ShowNotificator('alert-success', name+" - decrement from your cart");
                        window.location="";
                    }else if(data.trim()=='login'){
                        ShowNotificator('alert-info', "Please login to continue");
                        window.location="<?php echo base_url().'login'; ?>";
                    }else{
                        ShowNotificator('alert-danger', data);
                    }
                },
                error: function(xhr, status, error) {
                    ShowNotificator('alert-danger', error);
                    return false;
                }
            });
        }
    }
</script>