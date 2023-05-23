<!DOCTYPE html>
<html>
<head>
	<title>Customer || GetValue</title>
</head>
<body>
	<div class="row home">
		<div class="col-md-6">
			<div class="panel panel-warning">
				<div class="panel-heading">
					<h3 class="panel-title text-center">Pending Orders</h3>
				</div>
				<center>
					<h2><?php echo $totalCustomerPendingOrders; ?></h2>
				</center>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title text-center">Complete Orders</h3>
				</div>
				<center>
					<h2><?php echo $totalCustomerCompleteOrders; ?></h2>
				</center>
			</div>
		</div>
		<div class="col-md-12">
			<div class="panel panel-success my_products_count">
				<div class="panel-heading">
					<h3 class="panel-title text-center">Total Product Purchased</h3>
				</div>
				<center>
					<h1><?php echo $totalPurchasedProducts; ?></h1>
					<a href="<?php echo base_url(); ?>" class="btn btn-primary go-checkout"><span class="glyphicon glyphicon-circle-arrow-left"></span> Continue to Shop</a>
				</center>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title text-center">Processed Refund Request</h3>
				</div>
				<center>
					<h2><?php echo $totalCustomerProcessRefunds; ?></h2>
				</center>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-warning">
				<div class="panel-heading">
					<h3 class="panel-title text-center">Pending Refund Request</h3>
				</div>
				<center>
					<h2><?php echo $totalCustomerPendingRefunds; ?></h2>
				</center>
			</div>
		</div>
	</div>
</body>
</html>