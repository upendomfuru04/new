<?php
    $total_price=0;
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
                        <h3 class="panel-title"> Sales Record</h3>
                    </div>
                    <div class="panel-body p-20 row">
                        <div class="table-responsive pb20 pt20">
                            <p><?php if(sizeOf($items)==0){ echo 'No any sale record...';}?></p>
                            <table id="dataTable" class="table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>...</th>
                                        <th>Customer</th>
                                        <th>OrderID</th>
                                        <th>Date</th>
                                        <th>Request</th>
                                        <th>Discount Code</th>
                                        <th>Discount Amount (Tsh.)</th>
                                        <th>Price (Tsh.)</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-right">Total Price (Tsh.)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $counter=0; $request="None";
                                        $price=0;
                                        foreach($items as $product){  
                                            $counter++;
                                            $total=($product->price*$product->quantity);
                                            $total_price=$total_price+$total;
                                            $quantity=$product->quantity;
                                            // $total_quantity=$total_quantity+$quantity;
                                            $price=$product->price;
                                            // $sub_total=$sub_total+$price;
                                            if(isRefunded($product->orderID)){
                                                $request='Refunded';
                                            }
                                            $discount_amount=0;
                                            if($product->coupon_value!=""){
                                                $discount_amount=$product->coupon_value;
                                            }
                                        ?>
                                    <tr>
                                        <td><?php echo $counter; ?></td>
                                        <td><a href="<?php echo base_url().'prod/'.$product->product_url; ?>"><img src="<?php echo base_url().'media/products/'.$product->image; ?>" class="cartImg"></a></td>
                                        <td class="no-break-sm"><a href="<?php echo base_url().'prod/'.$product->product_url; ?>"><?php echo ucwords($product->name); ?> (<?php echo getCategoryName($product->category);?>)</a></td>
                                        <td><?php echo ucwords($product->first_name.' '.$product->surname); ?></td>
                                        <td><?php echo $product->orderID; ?></td>
                                        <td><?php echo date('d-m-Y', $product->createdDate); ?></td>
                                        <td><?php echo $request; ?></td>
                                        <td><?php echo $product->coupon; ?></td>
                                        <td><?php echo number_format($discount_amount); ?></td>
                                        <td><?php echo number_format($price); ?></td>
                                        <td class="text-center"><?php echo $quantity; ?></td>
                                        <td class="text-right"><?php echo number_format($total); ?></td>
                                    </tr>
                                    <?php }?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="11"><b>Total</b></td>
                                        <!-- <td class="text-left"><?php //echo number_format($sub_total); ?></td> -->
                                        <!-- <td class="text-center"><?php //echo $total_quantity; ?></td> -->
                                        <td class="text-right"><?php echo number_format($total_price); ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <p class="sm-hint">Scroll horizontal to view more</p>
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
</script>