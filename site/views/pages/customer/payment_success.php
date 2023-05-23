<!DOCTYPE html>
<html>
<head>
	<title>Payment Status - GetValue</title>
    <style type="text/css">
        sup{
            color: red;
        }
    </style>
</head>
<body>
	<div class="panel panel-info">
		<div class="panel-heading">
			<h2 class="panel-title">Payment Confirmation</h2>
		</div>
		<div class="panel-body">
			<center>
				<?php if($payment=="Success"){ ?>
                	<p>Thanks for shopping with us... payment for order #<?php echo $orderID; ?> is success, You can access your product in "<a href="<?php echo base_url(); ?>customer/my_products"> My Products</a>" Link. Or else download Our App to access your products on your android smartphone, <a href="https://play.google.com/store/apps/details?id=com.getvalue.getvalue" target="_blank"><i class="fa fa-download"></i> Download App Now</a></p>  
                <?php }elseif($payment=="not paid"){ ?>
                    <div class="alert alert-warning">Transaction not paid yet click <a class="btn btn-xs btn-info" href="<?=base_url('home/checkout')?>"> <i class="fa fa-check"></i> Checkout Now</a></div>
                <?php }else{ ?>
                	<div class="alert alert-danger"><?php echo $payment; ?></div>
                <?php } ?>
                <?php
                    if($password_set['password_set']!='0'){
                        echo '<div class="alert alert-success">Your default password is 123456 and username is '.$password_set['username'].', please! set your new password.</div>';
                    }
                ?>
            </center>
		</div>
	</div>
</body>
</html>
<script type="text/javascript">
    $(document).ready(function(){

    });
</script>