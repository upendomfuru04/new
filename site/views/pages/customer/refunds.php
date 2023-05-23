<!DOCTYPE html>
<html>
<head>
	<title>Refund Request || GetValue</title>
</head>
<body>
	<div class="panel panel-info">
		<div class="panel-heading">
			<h2 class="panel-title">Refund Request</h2>
		</div>
		<div class="panel-body cBody">
            <p><?php if(sizeOf($refunds)==0){ echo 'No any refund request...';}?></p>
            <div class="table-responsive">
    			<table class="table">
                    <thead>
                        <th>#</th>
                        <th>Order ID</th>
                        <th>Amount (Tsh.)</th>
                        <th>Note</th>
                        <th>Status</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <?php 
                            $counter=0;
                            foreach($refunds as $refund){  
                                $counter++;
                            ?>
                        <tr>
                            <td><?php echo $counter; ?></td>
                            <td><?php echo $refund->orderID; ?></td>
                            <td><?php echo number_format($refund->amount); ?></td>
                            <td><?php echo ucfirst($refund->customer_note); ?></td>
                            <td><?php if($refund->is_processed=='0'){ echo 'Processed';}else{ echo 'Pending';}; ?></td>
                            <td><?php if($refund->is_processed!='0'){ ?><a href="javascript:void(0);" onclick="removeRefundRequest(<?php echo $refund->id; ?>)"><i class="ti-trash text-danger"></i></a><?php }?></td>
                        </tr>
                        <?php }?>
                    </tbody>
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

    function removeRefundRequest(req){
        if (confirm("Are you sure you want to delete this request?")) {
            $.ajax({
                url:'<?= base_url() ?>customer/Customer/remove_refund_request/'+req,
                type:"POST",
                success: function(data){
                    if(data.trim()=='Success'){
                        ShowNotificator('alert-success', "Request deleted successfully...");
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