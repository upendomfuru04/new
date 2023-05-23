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
                        <h3 class="panel-title"> Customer Orders</h3>
                    </div>
                    <div class="panel-body p-30 row">
                        <div class="table-responsive pt20 pb20">
                            <p><?php if(sizeOf($orders)==0){ echo 'No any order placed...';}?></p>
                            <table id="dataTable" class="table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-left">#</th>
                                        <th class="text-center">OrderID</th>
                                        <th>Customer</th>
                                        <th class="text-right">Total Cost (Tsh.)</th>
                                        <th class="text-right">Amount Paid (Tsh.)</th>
                                        <th class="text-right">Total Commission (Tsh.)</th>
                                        <th class="text-center">Order Status</th>
                                        <th class="text-center">Items</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $counter=0; 
                                        foreach($orders as $order){
                                            $counter++;
                                            $order_status=""; $viewed="";
                                            if($order->is_complete=='0'){
                                                $order_status='Complete';
                                            }else{
                                                $order_status='Pending';
                                            }
                                            $amount_paid=0;
                                            if($order->amount_paid!=''){
                                                $amount_paid=number_format($order->amount_paid);
                                            }
                                            $quantity=getOrderCartCounter($order->orderID);
                                        ?>
                                    <tr>
                                        <td class="text-left"><?php echo $counter; ?></td>
                                        <td class="text-center"><?php echo $order->orderID; ?></td>
                                        <td><?php echo ucwords($order->first_name.' '.$order->surname); ?></td>
                                        <td class="text-right"><?php echo number_format(getOrderTotalCost($order->orderID)); ?></td>
                                        <td class="text-right"><?php echo $amount_paid; ?></td>
                                        <td class="text-right"><?php echo ($order->commission*$quantity); ?></td>
                                        <td class="text-center"><?php echo $order_status; ?></td>
                                        <td class="text-center"><?php echo $quantity; ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
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