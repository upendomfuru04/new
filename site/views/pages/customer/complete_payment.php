<!DOCTYPE html>
<html>
<head>
	<title>Checkout - GetValue</title>
    <style type="text/css">
        sup{
            color: red;
        }
    </style>
</head>
<body>
	<div class="panel panel-info">
		<div class="panel-heading">
			<h2 class="panel-title">Checkout - Bill Payments</h2>
		</div>
		<div class="panel-body">
			We are directing you to the payment page, please wait...
            <?php redirect(base_url('home/checkout')); ?>
		</div>
	</div>
</body>
</html>
<script type="text/javascript">
    $(document).ready(function(){

    });
</script>
<?php
    if(isset($order_info['orderID'])){
        $orderID=$order_info['orderID'];
        checkOrderPaymentStatus($orderID, $userID);
    }
?>