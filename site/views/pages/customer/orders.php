<!DOCTYPE html>
<html>
<head>
	<title>My Orders || GetValue</title>
</head>
<body>
	<div class="panel panel-info">
		<div class="panel-heading">
			<h2 class="panel-title">Orders</h2>
		</div>
		<div class="panel-body cBody">
            <p><?php if(sizeOf($orders)==0){ 
                    echo 'No any order placed...<br><br>';
                    echo '<a href="'.base_url().'" class="btn btn-primary go-checkout"><span class="glyphicon glyphicon-circle-arrow-left"></span> Back to Shop</a>';
                }else{ 
                    echo SuccessMsg("Thanks for your order..");
                ?></p>
                <div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> <small>Note: If you get message "Payment Page Timeout" in payment page, please! go back to checkout page click "Make order" Button.</small> </div>
            <div class="table-responsive">
    			<table class="table">
                    <thead>
                        <th>#</th>
                        <th>OrderID</th>
                        <th>Total Cost (Tsh.)</th>
                        <!-- <th>Viewed</th> -->
                        <th>Order Status</th>
                        <th>Items</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <?php 
                            $counter=0; 
                            foreach($orders as $order){  
                                $counter++;
                                $order_status=""; $viewed=""; $action="";
                                if(strtolower(getOrderProductStatus($order->orderID))=='live'){
                                    if(is_numeric($order->pay_date) && timeDiffInDays($order->pay_date, time()) < getRefundPeriod()){
                                        if($order->is_complete!='4'){
                                            // $action='<a href="'.base_url().'customer/request_refund/'.$order->orderID.'">Request For Refund</a>';
                                            $action='<a href="'.base_url().'customer/customer/order_items/'.$order->orderID.'">Request For Refund</a>';
                                        }
                                    }
                                }else{
                                    if($order->is_complete!='4'){
                                        // $action='<a href="'.base_url().'customer/request_refund/'.$order->orderID.'">Request For Refund</a>';
                                        $action='<a href="'.base_url().'customer/customer/order_items/'.$order->orderID.'">Request For Refund</a>';
                                    }
                                }
                                if($order->is_complete=='0'){
                                    $order_status='Complete';
                                }elseif($order->is_complete=='1'){
                                    $order_status='Pending';
                                }elseif($order->is_complete=='2'){
                                    $order_status='Not Paid';
                                    $action='<a href="'.base_url().'customer/complete_payment" class="btn btn-primary btn-sm">Complete Payment</a>';
                                }elseif($order->is_complete=='4'){
                                    $order_status='Refunded';
                                }else{
                                    $order_status='Unknown';
                                }
                                if($order->viewed=='0'){
                                    $viewed='Seen';
                                }else{
                                    $viewed='Pending';
                                }
                            ?>
                        <tr>
                            <td><?php echo $counter; ?></td>
                            <td><?php echo $order->orderID; ?></td>
                            <td><?php echo number_format(getOrderTotalCost($order->orderID)); ?></td>
                            <!-- <td><?php //echo $viewed; ?></td> -->
                            <td><?php echo $order_status; ?></td>
                            <td><a href="<?php echo base_url(); ?>customer/customer/order_items/<?php echo $order->orderID; ?>" class="btn btn-default"><?php echo getOrderCartCounter($order->orderID); ?></a></td>
                            <td><?php echo $action; ?></td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
            <p class="sm-hint">Scroll horizontal to view more</p>
            <?php } ?>
            <div class="clearfix"></div>
		</div>
	</div>
</body>
</html>
<script type="text/javascript">
  $(document).ready(function(){
        
  });
</script>