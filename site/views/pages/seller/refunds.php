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
                        <h3 class="panel-title"> Customer Refunds</h3>
                    </div>
                    <div class="panel-body p-40 row">
                        <div class="table-responsive pt20 pb20">
                            <p><?php if(sizeOf($refunds)==0){ echo 'No any refund requested...';}?></p>
                            <table id="dataTable" class="table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-left">#</th>
                                        <th class="text-center">OrderID</th>
                                        <th>Customer</th>
                                        <th class="text-right">Amount (Tsh.)</th>
                                        <th class="text-center">Customer Note</th>
                                        <th class="text-center">Refund Status</th>
                                        <!-- <th>Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $counter=0; 
                                        foreach($refunds as $refund){
                                            $counter++;
                                            $status=""; $viewed="";
                                            if($refund->is_processed=='0'){
                                                $status='Complete';
                                            }else{
                                                $status='Pending';
                                            }
                                        ?>
                                    <tr>
                                        <td class="text-left"><?php echo $counter; ?></td>
                                        <td class="text-center"><?php echo $refund->orderID; ?></td>
                                        <td><?php echo ucwords($refund->first_name.' '.$refund->surname); ?></td>
                                        <td class="text-right"><?php echo number_format($refund->amount); ?></td>
                                        <td class="text-center no-break-sm"><?php echo ucfirst($refund->customer_note); ?></td>
                                        <td class="text-center"><?php echo $status; ?></td>
                                        <!-- <td> -->
                                            <?php if($refund->is_processed!="0"){ ?>
                                            <!-- <a href="javascript:void();" class="btn btn-success btn-sm turnOnProgress<?=$refund->id?>" onclick="approve_refund('<?php echo $refund->id; ?>', '<?php echo $refund->orderID; ?>');">Approve</a>
                                            <a href="javascript:void();" class="btn btn-success btn-sm progressBarBtn progressBarBtn<?=$refund->id?>"><i class="fa fa-spinner fa-spin"></i> Processing...</a> -->
                                            <?php } ?>
                                        <!-- </td> -->
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