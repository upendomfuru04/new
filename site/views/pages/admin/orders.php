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
                    <div class="panel-body p40">
                        <div class="table-responsive pb20 pt20">
                            <p><?php if(sizeOf($orders)==0){ echo 'No any order placed...';}?></p>
                            <table id="dataTable" class="table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-left">#</th>
                                        <th class="text-center">OrderID</th>
                                        <th>Customer</th>
                                        <th class="text-right">Total Cost (Tsh.)</th>
                                        <th class="text-right">Amount Paid (Tsh.)</th>
                                        <th class="text-center">Order Status</th>
                                        <th class="text-center">Items</th>
                                        <th>Action</th>
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
                                            }else if($order->is_complete=='4'){
                                                $order_status='Refunded';
                                            }else{
                                                $order_status='Pending';
                                            }
                                            $amount_paid=0;
                                            if($order->amount_paid!=''){
                                                $amount_paid=number_format($order->amount_paid);
                                            }
                                        ?>
                                    <tr>
                                        <td class="text-left"><?php echo $counter; ?></td>
                                        <td class="text-center"><?php echo $order->orderID; ?></td>
                                        <td><?php echo ucwords($order->first_name.' '.$order->surname); ?></td>
                                        <td class="text-right"><?php echo number_format(getOrderTotalCost($order->orderID)); ?></td>
                                        <td class="text-right"><?php echo $amount_paid; ?></td>
                                        <td class="text-center"><?php echo $order_status; ?></td>
                                        <td class="text-center"><?php echo getOrderCartCounter($order->orderID); ?></td>
                                        <td>
                                            <a href="<?php echo base_url(); ?>admin/order/order_details/<?php echo $order->orderID; ?>" class="btn btn-default btn-sm">View</a>
                                            <?php if($order->is_complete!="0" && $order->is_complete!="4"){ ?>
                                            <a href="javascript:void(0);" class="btn btn-success btn-sm turnOnProgress<?=$order->orderID?>" onclick="approve_order('<?php echo $order->orderID; ?>');">Approve</a>
                                            <a href="javascript:void();" class="btn btn-success btn-sm progressBarBtn progressBarBtn<?=$order->orderID?>"><i class="fa fa-spinner fa-spin"></i> Processing...</a>
                                            <a href="javascript:void();" onclick="deleteOrder('<?php echo base_url()."admin/order/delete_order/".$order->id;?>');" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Delete"><i class="ti-trash"></i></a>
                                            <?php } ?>
                                        </td>
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

    function deleteOrder(data){
        var txt;
        if (confirm("Are you sure you want to delete this order?")) {
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