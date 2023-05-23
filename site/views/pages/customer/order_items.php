<!DOCTYPE html>
<html>
<head>
	<title>My Cart || GetValue</title>
</head>
<body>
	<div class="panel panel-info">
		<div class="panel-heading">
			<h2 class="panel-title">Cart/Order Items - #<?php echo $orderID['orderID'];?></h2>
		</div>
		<div class="panel-body cBody">
            <p><?php if(sizeOf($items)==0){ header("Location: ".base_url()."customer/orders");}?></p>
            <?php
                $act="";
                if(strtolower(getOrderProductStatus($orderID['orderID']))=='live'){
                    if(timeDiffInDays($orderID['pay_date'], time()) < getRefundPeriod()){
                        if($orderID['is_complete']!='4'){
                            $act='<center class="text-center"><a href="'.base_url().'customer/customer/request_refund/'.$orderID['orderID'].'" class="btn btn-default">Request For ORDER Refund</a></center><hr>';
                        }
                    }
                }else{
                    if($orderID['is_complete']!='4'){
                        $act='<center class="text-center"><a href="'.base_url().'customer/customer/request_refund/'.$orderID['orderID'].'" class="btn btn-default">Request For ORDER Refund</a></center><hr>';
                    }
                }
                echo $act;
            ?>
            <div class="table-responsive">
    			<table class="table">
                    <thead>
                        <th>#</th>
                        <th colspan="2">Product</th>
                        <th class="text-right">Price (Tsh.)</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-right">Total Price (Tsh.)</th>
                        <th class="text-right">Status</th>
                        <th class="text-right"></th>
                    </thead>
                    <tbody>
                        <?php 
                            $counter=0; $totalQtn=0; $totalPrice=0;
                            foreach($items as $product){  
                                $counter++;
                                $total=($product->price*$product->quantity);
                                $totalQtn=$totalQtn+$product->quantity;
                                $totalPrice=$totalPrice+$total;

                                $action=""; $order_status="";
                                if(strtolower(getOrderProductStatus($product->orderID))=='live'){
                                    if(timeDiffInDays($product->pay_date, time()) < getRefundPeriod()){
                                        if($product->is_complete!='4'){
                                            $action='<a href="'.base_url().'customer/customer/request_refund/'.$product->orderID.'?product='.$product->id.'">Request For Refund</a>';
                                        }
                                    }
                                }else{
                                    if($product->is_complete!='4'){
                                        $action='<a href="'.base_url().'customer/customer/request_refund/'.$product->orderID.'?product='.$product->id.'">Request For Refund</a>';
                                    }
                                }
                                if($product->is_complete=='4'){
                                    $order_status='Refunded';
                                }
                            ?>
                        <tr>
                            <td><?php echo $counter; ?></td>
                            <td><a href="<?php echo base_url().'prod/'.$product->product_url; ?>"><img src="<?php echo base_url().'media/products/thumb/'.$product->image; ?>" class="cartImg m-r-10"><?php echo ucwords($product->name); ?></a></td>
                            <td><a href="<?php echo base_url().'home/product_review/'.$product->product_url; ?>" class="btn btn-xs btn-info m-t-10" target="_blank">Rate & Review</td>
                            <td class="text-right"><?php echo number_format($product->price); ?></td>
                            <td class="text-center"><?php echo $product->quantity; ?></td>
                            <td class="text-right"><?php echo number_format($total); ?></td>
                            <td class="text-right"><?php echo $order_status; ?></td>
                            <td class="text-right"><?php echo $action; ?></td>
                        </tr>
                        <?php }?>
                    </tbody>
                    <tfoot>
                        <td colspan="4" class="p-l-20"></td>
                        <td class="text-center"><p class="p-10"><strong><?php echo $totalQtn;?></strong></p></td>
                        <td class="text-right"><p class="p-t-10"><strong><?php echo number_format($totalPrice);?></strong></p></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                    </tfoot>
                </table>
            </div>
            <p class="sm-hint">Scroll horizontal to view more</p>
            <div class="clearfix"></div>
		</div>
	</div>
</body>
</html>
<script type="text/javascript">
  $(document).ready(function(){
        
  });
</script>