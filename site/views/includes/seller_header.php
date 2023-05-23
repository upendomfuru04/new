<?php
    if(basename($_SERVER['PHP_SELF'])!=""){
        new_pageView('seller - '.basename($_SERVER['PHP_SELF']));
    }
    if(empty($this->session->userdata('account_type_list'))){
        redirect(base_url());
    }
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <link rel="shortcut icon" href="<?= base_url('assets/themes/favicon.png') ?>" />
    <title><?=$title?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/font-awesome.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap-select.min.css') ?>" />
    <link href="<?= base_url('assets/css/bootstrap-datepicker.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/dimension.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/getvalue.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/themify-icons.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/simple-line-icons.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/custom-admin.css') ?>" rel="stylesheet">
    <!-- summernotes CSS -->
    <link href="<?= base_url('assets/css/summernote.css') ?>" rel="stylesheet" />
    <link href='https://fonts.googleapis.com/css?family=Inconsolata' rel='stylesheet' type='text/css'>
    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
	<!-- Datatable -->
    <link href="<?php echo base_url();?>assets/datatable/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url();?>assets/datatable/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<!-- js -->
	<script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap-select.min.js') ?>"></script>    
    <script src="<?= base_url('assets/js/summernote.min.js') ?>"></script>
	<!-- Datatable -->
    <script src="<?php echo base_url();?>assets/datatable/jquery.dataTables.min.js"></script>
	<!-- start - This is for export functionality only -->
    <script src="<?php echo base_url();?>assets/datatable/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url();?>assets/datatable/buttons.flash.min.js"></script>
    <script src="<?php echo base_url();?>assets/datatable/jszip.min.js"></script>
    <script src="<?php echo base_url();?>assets/datatable/pdfmake.min.js"></script>
    <script src="<?php echo base_url();?>assets/datatable/vfs_fonts.js"></script>
    <script src="<?php echo base_url();?>assets/datatable/buttons.html5.min.js"></script>
    <script src="<?php echo base_url();?>assets/datatable/buttons.print.min.js"></script>
    <!-- end - This is for export functionality only -->
</head>
<body style="background: #f1f1f1;">
	<div id="wrapper">
        <div id="content">
            <nav class="navbar navbar-default">
                <div class="navbar-header">
                    <a href="" style="padding-left:0;"><img class="img-responsive" src="<?php echo base_url(); ?>assets/themes/logo.png" style="max-width:128px;"></a>
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <i class="fa fa-lg fa-bars"></i>
                    </button>
                </div>
                <div id="navbar" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li></li>
                        <!-- <li><a href="vendor"><i class="icon-home"></i> Home</a></li> -->
                        <li>
                            <a href="javascript:void();" data-toggle="modal" data-target="#changepassword" class="h-settings"><i class="fa fa-key" aria-hidden="true"></i> Change Password</a>
                        </li>
                        <li><a href="javascript:void(0);" data-toggle="modal" data-target="#modalCalculator"><i class="fa fa-calculator" aria-hidden="true"></i> Calculator</a></li>
                        <li><a href="<?=base_url()?>" target="_blank"><i class="fa fa-globe" aria-hidden="true"></i> Visit Web</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="<?php echo $this->session->userdata('account_type'); ?>"> <?php echo ucwords($this->session->userdata('user_full_name')); ?></a></li>
                        <li><a href="<?php echo base_url(); ?>seller/logout"><i class="fa fa-sign-out"></i> Logout</a></li>
                    </ul>
                </div>
            </nav>
                                
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-3 col-xs-12 col-md-3 col-lg-2 left-side navbar-default menu">
                        <div class="show-menu">
                            <a id="show-xs-nav" class="visible-xs" href="javascript:void(0)">
                                <span class="show-sp">
                                    Show menu
                                    <i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i>
                                </span>
                                <span class="hidde-sp">
                                    Hide menu
                                    <i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i>
                                </span>
                            </a>
                        </div>
                        <ul class="sidebar-menu adminMenu">                                    
                            <li><a href="<?php echo base_url();?>seller/vendor"><i class="icon-home"></i> Home</a></li>                            
                            <?php if(in_array('vendor', $this->session->userdata('account_type_list'))){ ?>
                            <li>
                                <a href="javascript:void();" ><i class="icon-list" aria-hidden="true"></i> Products <span style="position: absolute; right:10px; top:12px;"><i class="fa fa-caret-down"></i></span></a>
                                <ul class="dropdown">
                                    <li><a href="<?php echo base_url();?>seller/product/add_product">Add Product</a></li>
                                    <li><a href="<?php echo base_url();?>seller/product/products">View Products</a></li>
                                </ul>
                            </li>
                            <li><a href="<?php echo base_url(); ?>seller/product/affiliate_marketers" ><i class="fa fa-bar-chart" aria-hidden="true"></i> Affiliate Marketers <span class="badge" style="position: absolute; right:10px; top:12px; background:#ca4a1f;" id="affiliateMarketCounter"></span></a></li>
                            <li><a href="<?php echo base_url(); ?>seller/sale/sales" ><i class="fa fa-shopping-basket" aria-hidden="true"></i> Sales </a></li>
                            <li><a href="<?php echo base_url(); ?>seller/sale/refunds" ><i class="fa fa-money" aria-hidden="true"></i> Refunds <span class="badge" style="position: absolute; right:10px; top:12px; background:#ca4a1f;" id="refundCounter"></span></a></li>
                            <li><a href="<?php echo base_url(); ?>seller/sale/coupons" ><i class="ti-archive" aria-hidden="true"></i> Coupons</a></li>
                            <li><a href="<?php echo base_url(); ?>seller/order/orders" ><i class="fa fa-cart-arrow-down" aria-hidden="true"></i> Orders <span class="badge" style="position: absolute; right:10px; top:12px; background:#ca4a1f;" id="orderCounter"></span></a></li>
                            <?php } ?>
                            <?php if(in_array('insider', $this->session->userdata('account_type_list')) || in_array('outsider', $this->session->userdata('account_type_list')) || in_array('contributor', $this->session->userdata('account_type_list'))){ ?>
                            <?php if(in_array('insider', $this->session->userdata('account_type_list'))){ ?>
                            <li>
                                <a href="javascript:void();" ><i class="ti-credit-card" aria-hidden="true"></i> Affiliate Urls <span style="position: absolute; right:10px; top:12px;"><i class="fa fa-caret-down"></i></span></a>
                                <ul class="dropdown">
                                    <li><a href="<?php echo base_url(); ?>seller/product/affiliate_urls">Product Urls</a></li>
                                    <li><a href="<?php echo base_url(); ?>seller/product/vendor_url">Vendor Url</a></li>
                                </ul>
                            </li>
                             <?php }else{ ?>
                            <li><a href="<?php echo base_url();?>seller/product/affiliate_urls"><i class="icon-list"></i> Affiliate Urls</a></li>
                                <?php } ?>
                            <?php if(in_array('insider', $this->session->userdata('account_type_list'))){ ?>
                            <li>
                                <a href="javascript:void();" ><i class="ti-cloud" aria-hidden="true"></i> Referrals <span style="position: absolute; right:10px; top:12px;"><i class="fa fa-caret-down"></i></span></a>
                                <ul class="dropdown">
                                    <li><a href="<?php echo base_url(); ?>seller/product/referrals">Products</a></li>
                                    <li><a href="<?php echo base_url(); ?>seller/product/referrals_vendors">Vendors</a></li>
                                </ul>
                            </li>
                             <?php }else{ ?>
                            <li><a href="<?php echo base_url();?>seller/product/referrals"><i class="ti-cloud"></i> Referrals</a></li>
                                <?php } ?>
                            <li><a href="<?php echo base_url(); ?>seller/sale/affiliate_sales" ><i class="fa fa-shopping-basket" aria-hidden="true"></i> Affiliate Sales </a></li>
                            <!-- <li><a href="<?php //echo base_url(); ?>seller/affiliate_refunds" ><i class="fa fa-money" aria-hidden="true"></i> Affiliate Refunds <span class="badge" style="position: absolute; right:10px; top:12px; background:#ca4a1f;" id="affiliateRefundCounter"></span></a></li> -->
                            <li><a href="<?php echo base_url(); ?>seller/order/affiliate_orders" ><i class="fa fa-shopping-cart" aria-hidden="true"></i> Affiliate Orders <span class="badge" style="position: absolute; right:10px; top:12px; background:#ca4a1f;" id="affiliateOrderCounter"></span></a></li>
                            <?php } ?>
                            <li><a href="<?php echo base_url(); ?>seller/commission/commissions" ><i class="icon-tag" aria-hidden="true"></i> Commissions</a></li>
                            <li>
                                <a href="javascript:void();" ><i class="ti-credit-card" aria-hidden="true"></i> Withdrawal <span style="position: absolute; right:10px; top:12px;"><i class="fa fa-caret-down"></i></span></a>
                                <ul class="dropdown">
                                    <li><a href="<?php echo base_url(); ?>seller/commission/withdraw_request">Track Request</a></li>
                                    <li><a href="<?php echo base_url(); ?>seller/commission/withdrawal_form">Withdrawal Form</a></li>
                                </ul>
                            </li>
                            <li><a href="<?php echo base_url(); ?>seller/messages" ><i class="fa fa-envelope" aria-hidden="true"></i> Alerts/Messages <span class="badge" style="position: absolute; right:10px; top:12px; background:#ca4a1f;" id="messageCounter"></span></a></li>
                            <li>
                                <a href="javascript:void();" ><i class="icon-handbag" aria-hidden="true"></i> Shop <span style="position: absolute; right:10px; top:12px;"><i class="fa fa-caret-down"></i></span></a>
                                <ul class="dropdown">
                                    <li><a href="<?php echo base_url(); ?>seller/home/shop_info">Shop Info</a></li>
                                    <li><a href="<?php echo base_url(); ?>seller/home/payment_info">Payment Info</a></li>
                                    <li><a href="<?php echo base_url(); ?>seller/home/social_accounts">Social Accounts</a></li>
                                </ul>
                            </li>
                            <?php if(in_array('vendor', $this->session->userdata('account_type_list'))){ ?>
                            <!-- <li><a href="<?php //echo base_url(); ?>seller/media_center" ><i class="fa fa-image" aria-hidden="true"></i>Media Center</a></li> -->
                            <?php } ?>
                            <?php if(in_array('insider', $this->session->userdata('account_type_list')) || in_array('contributor', $this->session->userdata('account_type_list'))){ ?>
                            <li><a href="javascript:void();" ><i class="fa fa-newspaper-o" aria-hidden="true"></i> Blog <span style="position: absolute; right:10px; top:12px;"><i class="fa fa-caret-down"></i></span></a>
                                <ul class="dropdown">
                                    <li><a href="<?php echo base_url(); ?>seller/blog/new_post">New Post</a></li>
                                    <li><a href="<?php echo base_url(); ?>seller/blog/my_posts">My Posts</a></li>
                                    <?php if(in_array('insider', $this->session->userdata('account_type_list'))){ ?>
                                    <li><a href="<?php echo base_url(); ?>seller/blog/pending_posts">Pending Posts</a></li>
                                    <li><a href="<?php echo base_url(); ?>seller/blog/posts">All Posts</a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <?php if(in_array('insider', $this->session->userdata('account_type_list'))){ ?>
                            <li><a href="javascript:void();" ><i class="fa fa-question-circle" aria-hidden="true"></i> Self Help <span style="position: absolute; right:10px; top:12px;"><i class="fa fa-caret-down"></i></span></a>
                                <ul class="dropdown">
                                    <li><a href="<?php echo base_url(); ?>seller/blog/new_self_help">Post New Help</a></li>
                                    <li><a href="<?php echo base_url(); ?>seller/blog/self_help_sub_category">Sub-Categories</a></li>
                                    <li><a href="<?php echo base_url(); ?>seller/blog/self_help">All Help</a></li>
                                </ul>
                            </li>
                            <?php } ?>
                            <?php } ?>
                            <li><a href="<?php echo base_url(); ?>seller/my_profile"><i class="icon-user"></i> Update Profile</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-9 col-xs-12 col-md-9 col-lg-10 col-sm-offset-3 col-md-offset-3 col-lg-offset-2">

</body>
</html>