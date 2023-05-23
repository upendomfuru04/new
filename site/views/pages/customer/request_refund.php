<?php
    $allowed_amount=getOrderAmountPaid($orderID);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Request Refund || GetValue</title>
</head>
<body>
	<div class="panel panel-info">
		<div class="panel-heading">
			<h2 class="panel-title">Request Refund - #<?php echo $orderID;?></h2>
		</div>
		<div class="panel-body cBody">
            <?php if($orderID==""){ header("Location: ".base_url()."customer/orders");} ?>
            <p class="col-md-12">Give us the reason why you want to be refunded.</p>
			<form method="POST" id="data-form">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" name="orderID" value="<?php echo $orderID;?>">
                        <input type="hidden" name="product" value="<?php echo $product;?>">
                        <textarea class="form-control" name="description"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Amount To Be Refunded:</label>
                        <?php if($product!=""){ ?>
                        <input type="text" name="amount_paid" id="request_amount" value="<?php echo getItemAmountPaid($orderID, $product);?>" class="form-control" readonly>
                        <?php }else{ ?>
                        <input type="text" name="amount_paid" id="request_amount" value="<?php echo $allowed_amount;?>" class="form-control" readonly>
                        <?php } ?>
                    </div>
                </div>
                 <div class="col-md-12 p-t-10" id="resultMsg"></div>
                <div class="col-md-12 p-t-10">
                    <a href="javascript:void();" class="btn btn-success mr-2 turnOnProgress" id="saveBtn">Send Request</a>
                    <a href="javascript:void();" class="btn btn-success mr-2 progressBarBtn"><i class="fa fa-spinner fa-spin"></i> Processing...</a>
                </div>    
            </form>
            <div class="clearfix"></div>
		</div>
	</div>
</body>
</html>
<script type="text/javascript">
    var allowed_amount='<?php echo $allowed_amount; ?>';
  $(document).ready(function(){
        $('#saveBtn').click(function(){
            var request_amount=document.getElementById('request_amount').value;
            if(request_amount>allowed_amount){
                alert("Amount you are requesting to be refunded is higher than paid amount for this order.");
            }else{
               $('.turnOnProgress').css('display','none');
               $('.progressBarBtn').css('display','inline-block');
               $.ajax({
                    url: '<?php echo base_url(); ?>customer/Customer/save_request_refund',
                    type: 'POST',
                    data:$('#data-form').serialize(),
                    async: true,
                    processData: false,
                    success: function (data) {
                        if(data.trim()=='Success'){
                            var output = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> Success </div>'; 
                            $('#resultMsg').html(output);
                            document.getElementById("data-form").reset();
                            window.location="<?php echo base_url();?>customer/refunds";
                        }else{
                            $('#resultMsg').html(data);
                        }
                        $('.turnOnProgress').css('display','inline-block');
                        $('.progressBarBtn').css('display','none');
                    },
                    error: function( xhr, status, error ) {
                        $('#resultMsg').html(error);
                        $('.turnOnProgress').css('display','inline-block');
                        $('.progressBarBtn').css('display','none');
                        return false;
                    }
                });
           }
        });
  });
</script>