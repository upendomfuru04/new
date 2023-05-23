<?php
    if($orderID==""){ header("Location: ".base_url()."seller/orders");}
?>
<!DOCTYPE html>
<html>
<head>
    
</head>
<body>
    <h1 class="breadcumb"></h1>
    <div class="home-page">
        <div class="row p-t-20">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"> Order Details</h3>
                    </div>
                    <div class="panel-body p-40 row">
                        <div class="col-md-6 text-right no-float-sm">
                            <p><strong>Order ID: </strong><?=$orderID?></p>
                            <p><strong>First Name: </strong><?=$order['first_name']?></p>
                            <p><strong>Surname: </strong><?=$order['surname']?></p>
                            <p><strong>Phone: </strong><?=$order['phone']?></p>
                            <p><strong>Email: </strong><?=$order['email']?></p>
                            <p><strong>Address: </strong><?=$order['address']?></p>
                            <p><strong>Country: </strong><?=$order['country']?></p>
                            <p><strong>City: </strong><?=$order['region']?></p>
                            <p><strong>Post Code: </strong><?=$order['postCode']?></p>
                        </div>
                        <div class="col-md-6">
                            <?php
                                if($order['is_complete']=='0'){
                                    $order_status='<i class="badge badge-success">Complete</i>';
                                }elseif($order['is_complete']=='4'){
                                    $order_status='<i class="badge badge-success">Refunded</i>';
                                }else{
                                    $order_status='<i class="badge badge-info">Pending</i>';
                                }
                            ?>
                            <p><strong>Payment Type: </strong><?=$order['payment_type']?></p>
                            <p><strong>Coupon Code: </strong><?=$order['coupon']?></p>
                            <p><strong>Coupon Value: </strong><?=$order['coupon_value']?></p>
                            <p><strong>Total Amount: </strong><?php echo number_format(getOrderTotalCost($orderID));?> Tsh.</p>
                            <p><strong>Amount: </strong><?=$order['amount_paid']?> Tsh.</p>
                            <p><strong>Order Date: </strong><?=date('d M, Y', $order['createdDate'])?></p>
                            <?php if($order['is_complete']=='0'){ ?>
                                <p><strong>Paid Date: </strong><?php echo getOrderPaymentDate($orderID); ?></p>
                            <?php } ?>
                            <p><strong>Payment Status: </strong><?=$order_status?></p>
                            <p><strong>Note: </strong><?=ucfirst($order['notes'])?></p>
                        </div>
                        <hr class="clear m-t-20">
                        <div class="table-responsive pt20 pb20 p-t-20">
                            <table id="dataTable" class="table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>Price (Tsh.)</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-right">Total Price (Tsh.)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $counter=0; $totalQtn=0; $totalPrice=0;
                                        foreach($items as $product){  
                                            $counter++;
                                            $total=($product->price*$product->quantity);
                                            $totalQtn=$totalQtn+$product->quantity;
                                            $totalPrice=$totalPrice+$total;
                                        ?>
                                    <tr>
                                        <td><?php echo $counter; ?></td>
                                        <td><a href="<?php echo base_url().'prod/'.$product->product_url; ?>"><img src="<?php echo base_url().'media/products/'.$product->image; ?>" class="cartImg m-r-10"><?php echo ucwords($product->name); ?></a></td>
                                        <td><?php echo number_format($product->price); ?></td>
                                        <td class="text-center"><?php echo $product->quantity; ?></td>
                                        <td class="text-right"><?php echo number_format($total); ?></td>
                                    </tr>
                                    <?php }?>
                                </tbody>
                                <tfoot>
                                    <td colspan="3" class="p-l-20"></td>
                                    <td class="text-center"><p class="p-10"><strong><?php echo $totalQtn;?></strong></p></td>
                                    <td class="text-right"><strong><?php echo number_format($totalPrice);?></strong></td>
                                </tfoot>
                            </table>
                            <hr class="clear m-t-20">
                            <div class="col-md-12">
                                <?php if($order['is_complete']!="0" && $order['is_complete']!="4"){ ?>
                                <!-- <a href="javascript:void();" class="btn btn-success btn-sm turnOnProgress<?=$orderID?>" onclick="approve_order('<?php echo $orderID; ?>');">Approve</a> -->
                                <a href="javascript:void();" class="btn btn-success btn-sm progressBarBtn progressBarBtn<?=$orderID?>"><i class="fa fa-spinner fa-spin"></i> Processing...</a>
                                <?php } ?>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<script type="text/javascript">
    $(document).ready(function(){
        $('#dataTable').DataTable();

    });

    function deleteData(data){
        var txt;
        if (confirm("Are you sure you want to delete this product coupon?")) {
            $.ajax({
                url: data,
                type:"POST",
                success: function(data){
                    if(data.trim()=='Success'){
                        window.location="";
                    }else{
                        alert(data);
                    }
                }
            });
        }
    }
</script>