<?php
    $total_commission=0;
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
                        <h3 class="panel-title">Affiliate Sales</h3>
                    </div>
                    <div class="panel-body p40">
                        <div class="table-responsive pb20 pt20">
                            <p><?php if(sizeOf($items)==0){ echo 'No any sale record...';}?></p>
                            <table id="dataTable" class="table-bordered no-break-sm" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>...</th>
                                        <th class="text-center">OrderID</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Commission (Tsh.)</th>
                                        <th class="text-right">Total Commission (Tsh.)</th>
                                        <th class="text-center">Refund Request</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $counter=0; $request="None";
                                        foreach($items as $product){  
                                            $counter++;
                                            $total=($product->commission*$product->quantity);
                                            $total_commission=$total_commission+$total;
                                            if(isRefunded($product->orderID)){
                                                $request='Refunded';
                                            }
                                        ?>
                                    <tr>
                                        <td><?php echo $counter; ?></td>
                                        <td><a href="<?php echo base_url().'prod/'.$product->product_url; ?>" target="_blank"><img src="<?php echo base_url().'media/products/'.$product->image; ?>" class="cartImg"></a></td>
                                        <td><a href="<?php echo base_url().'prod/'.$product->product_url; ?>" target="_blank"><?php echo ucwords($product->name); ?> (<?php echo getCategoryName($product->category);?>)</a></td>
                                        <td class="text-center"><?php echo $product->orderID; ?></td>
                                        <td class="text-center"><?php echo date('d-m-Y', $product->createdDate); ?></td>
                                        <td class="text-center"><?php if($product->commission!="") echo number_format($product->commission).' X '.$product->quantity; ?></td>
                                        <td class="text-right"><?php echo number_format($total); ?></td>
                                        <td class="text-center"><?php echo $request; ?></td>
                                    </tr>
                                    <?php }?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6"><b>Total</b></td>
                                        <td class="text-right"><?php echo number_format($total_commission); ?></td>
                                        <td class="text-right"></td>
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