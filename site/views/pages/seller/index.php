<!DOCTYPE html>
<html>
<head>
    
</head>
<body>
	<!-- <h1 class="breadcumb">Dashboard</h1> -->
    <div class="home-page">
        <!--Welcome panel-->
        <div class="row">
            <div class="col-lg-12">
                <div class="welcome-panel">
                    <div class="welcome-panel-content">
                    	<h2>Welcome to GetValue !</h2>
                    	<p class="about-description">We’ve assembled some links to get you started:</p>
                    </div>
                    <div class="col-md-4 welcome-panel-column">
                        <h3>Get Started</h3>
                        <a href="<?php echo base_url(); ?>seller/my_profile" class="btn btn-primary btn-customize">Update Profile</a>
                        <p style="margin-top:10px;">
                            or <a href="javascript:void();" data-toggle="modal" data-target="#changepassword">change your password</a>
                        </p>
                    </div>
                    <div class="col-md-4 welcome-panel-column">
                        <h3>Next Steps</h3>
                        <ul>
        					<li><a href="<?php echo base_url(); ?>seller/shop_info"><i class="fa fa-registered"></i>Add brand</a></li>
                            <?php if(in_array('vendor', $this->session->userdata('account_type_list'))){ ?>
        			            <li><a href="<?php echo base_url();?>seller/product/add_product"><i class="fa fa-plus"></i>Add product</a></li>
                            <?php }else{ ?>
        			            <li><a href="<?php echo base_url(); ?>seller/product/affiliate_urls"><i class="fa fa-list"></i>Affiliates Urls</a></li>
                            <?php } ?>
                            <?php if(in_array('insider', $this->session->userdata('account_type_list')) || in_array('contributor', $this->session->userdata('account_type_list'))){ ?>
                                <li><a href="<?php echo base_url(); ?>seller/blog/new_post"><i class="fa fa-newspaper-o"></i>Add a blog post</a></li>
                            <?php } ?>
                            <?php if(in_array('vendor', $this->session->userdata('account_type_list'))){ ?>
                                <li><a href="<?php echo base_url();?>seller/product/products"><i class="fa fa-list"></i>View products</a></li>
                            <?php }else{ ?>
                                <li><a href="<?php echo base_url(); ?>seller/product/referrals"><i class="ti-cloud"></i>Referrals</a></li>
                            <?php } ?>
        		        </ul>
                    </div>
                    <div class="col-md-4 welcome-panel-column">
                        <h3>More Actions</h3>
                        <ul>
                            <?php if(in_array('insider', $this->session->userdata('account_type_list')) || in_array('outsider', $this->session->userdata('account_type_list')) || in_array('contributor', $this->session->userdata('account_type_list'))){ ?>
        					   <li><a href="<?php echo base_url(); ?>seller/order/affiliate_orders"><i class="fa fa-cart-arrow-down"></i>Orders</a></li>
                            <?php }else{ ?>
                               <li><a href="<?php echo base_url(); ?>seller/order/orders"><i class="fa fa-cart-arrow-down"></i>Orders</a></li>
                           <?php } ?>
        			        <li><a href="<?php echo base_url(); ?>seller/commission/commissions"><i class="icon-tag"></i>Commissions</a></li>
                            <?php if(in_array('insider', $this->session->userdata('account_type_list')) || in_array('contributor', $this->session->userdata('account_type_list'))){ ?>
        			             <li><a href="<?php echo base_url(); ?>seller/blog/my_posts"><i class="fa fa-newspaper-o"></i>My Posts</a></li>
                            <?php } ?>
        					<li><a href="<?php echo base_url(); ?>seller/payment_info"><i class="icon-handbag"></i>Payment Info</a></li>
        		        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="row seller_stats">
            <div class="col-lg-3">
                <div class="well well-purple">
                    <div class="panel-body">
                        <div id="container-by-month" style="height: 70px;">
                            <?php if(in_array('vendor', $this->session->userdata('account_type_list'))){ ?>
                                <h3 class="panel-title text-center"><i class="fa fa-bar-chart-o fa-fw"></i> Total Products</h3>
                                <center><h3><?php echo $totalProducts; ?></h3></center>
                            <?php }else{ ?>
                                <h3 class="panel-title text-center"><i class="fa fa-bar-chart-o fa-fw"></i> Total Referrals</h3>
                                <center><h3><?php echo $totalReferrals; ?></h3></center>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php if(in_array('insider', $this->session->userdata('account_type_list')) || in_array('contributor', $this->session->userdata('account_type_list'))){ ?>
                <div class="col-lg-3">
                    <div class="well well-info">
                        <div class="panel-body">
                            <div id="container-by-month" style="height: 70px;">
                                <h3 class="panel-title text-center"><i class="fa fa-bar-chart-o fa-fw"></i> Pending Posts</h3>
                                <center><h3><?php echo $pendingPost; ?></h3></center>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if(in_array('insider', $this->session->userdata('account_type_list')) || in_array('contributor', $this->session->userdata('account_type_list'))){ ?>
            <div class="col-lg-6">
            <?php 
                }else{
                   echo '<div class="col-lg-9">'; 
                } 
            ?>
                <div class="well well-danger">
                    <div class="panel-body">
                        <div id="container-by-month" style="height: 70px;">
                            <h3 class="panel-title text-center"><i class="fa fa-bar-chart-o fa-fw"></i> Pending Commissions</h3>
                            <center><h3 class="m-t-20"><?php echo $pendingCommissions.' Tsh.'; ?></h3></center>
                        </div>
                    </div>
                </div>
            </div>
            <?php if(in_array('insider', $this->session->userdata('account_type_list')) || in_array('contributor', $this->session->userdata('account_type_list'))){ ?>
                <div class="col-lg-3">
                    <div class="well well-info">
                        <div class="panel-body">
                            <div id="container-by-month" style="height: 50px;">
                                <h3 class="panel-title text-center"><i class="fa fa-bar-chart-o fa-fw"></i> Approved Posts</h3>
                                <center><h3><?php echo $approvedPost; ?></h3></center>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if(in_array('insider', $this->session->userdata('account_type_list')) || in_array('contributor', $this->session->userdata('account_type_list'))){ ?>
            <div class="col-lg-3">
            <?php 
                }else{
                   echo '<div class="col-lg-6">'; 
                } 
            ?>
                <div class="well well-success">
                    <div class="panel-body">
                        <div id="container-by-month" style="height: 50px;">
                            <h3 class="panel-title text-center"><i class="fa fa-bar-chart-o fa-fw"></i> Paid Commissions</h3>
                            <center><h3 class="m-t-20"><?php echo $paidCommissions.' Tsh.';?></h3></center>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="well well-warning">
                    <div class="panel-body">
                        <div id="container-by-month" style="height: 50px;">
                            <h3 class="panel-title text-center"><i class="fa fa-bar-chart-o fa-fw"></i> Pending Orders</h3>
                            <center><h3><?php echo ($pendingOrders+$pendingAffiliateOrders); ?></h3></center>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="well well-info">
                    <div class="panel-body">
                        <div id="container-by-month" style="height: 50px;">
                            <h3 class="panel-title text-center"><i class="fa fa-bar-chart-o fa-fw"></i> Complete Orders</h3>
                            <center><h3><?php echo ($totalOrders+$totalAffiliateOrders); ?></h3></center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>