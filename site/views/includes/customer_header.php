<?php 
	include "header.php";
	if(basename($_SERVER['PHP_SELF'])!=""){
        new_pageView('Customer - '.basename($_SERVER['PHP_SELF']));
    }
?>
<!DOCTYPE html>
<html>
<head>
	
</head>
<body>
	<div class="container">
		<div class="row p-t-40">
			<div class="col-md-3">
				<div class="panel panel-default customerMenu">
					<div class="panel-heading">
						<h1 class="panel-title"> Customer</h1>
					</div>
					<div class="panel-body">
						<ul>
							<li><a href="<?php echo base_url(); ?>customer"><i class="ti-home"></i> Home</a></li>
							<li><a href="<?php echo base_url(); ?>customer/cart"><i class="fa fa-shopping-cart"></i> My Cart</a></li>
							<li><a href="<?php echo base_url(); ?>customer/orders"><i class="icon-notebook"></i> Orders</a></li>
							<li><a href="<?php echo base_url(); ?>customer/refunds"><i class="fa fa-money"></i> Refunds</a></li>
							<li><a href="<?php echo base_url(); ?>customer/my_products"><i class="fa fa-list"></i> My Products</a></li>
							<li><a href="https://play.google.com/store/apps/details?id=com.getvalue.getvalue" target="_blank"><i class="fa fa-download"></i> Download App</a></li>
							<li><a href="<?php echo base_url(); ?>customer/messages"><i class="fa fa-envelope-o"></i> Message(s) <span class="span bg-primary" id="messageCounter"></span></a></li>
							<!-- <li><a href="<?php //echo base_url(); ?>customer/help_and_support"><i class="fa fa-info-circle"></i> Help & Support</a></li> -->
							<li><a href="<?php echo base_url(); ?>users/my_profile"><i class="icon-user"></i> My Profile</a></li>
							<li><a href="javascript:void();" data-toggle="modal" data-target="#changepassword"><i class="ti-key"></i> Change Password</a></li>
							<li><a href="<?php echo base_url(); ?>logout"><i class="fa fa-sign-out"></i> Log Out</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-9">
</body>
</html>