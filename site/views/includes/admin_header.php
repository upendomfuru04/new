<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title><?=$title?></title>
    <link rel="shortcut icon" href="<?= base_url('assets/themes/favicon.png') ?>" />
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

    <!-- <link rel="stylesheet" href="<?php echo base_url();?>assets/css/select2.min.css" /> -->
    <!-- <link rel="stylesheet" href="<?php echo base_url();?>assets/css/select2-bootstrap.min.css" /> -->
    <!-- <script src="<?php echo base_url();?>assets/js/select2.min.js"></script> -->
    <!-- <script src="<?php echo base_url();?>assets/js/select2.js"></script> -->
    <!-- end - This is for export functionality only -->
</head>
<body style="background: #f1f1f1;">
    <div id="wrapper">
        <div id="content">
            <nav class="navbar navbar-default">
                <div class="navbar-header">
                    <a href="<?php echo base_url(); ?>admin" style="padding-left:0;"><img class="img-responsive" src="<?php echo base_url(); ?>assets/themes/logo.png" style="max-width:128px;"></a>
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
                        <li><a href="<?=base_url()?><?php echo $this->session->userdata('account_type'); ?>"> <?php echo ucwords($this->session->userdata('user_full_name')); ?></a></li>
                        <li><a href="<?php echo base_url(); ?>admin/logout"><i class="fa fa-sign-out"></i> Logout</a></li>
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
                            <li><a href="<?php echo base_url();?>admin"><i class="icon-home"></i> Home</a></li>
                            <li>
                                <a href="javascript:void();" ><i class="icon-list" aria-hidden="true"></i> Products <span style="position: absolute; right:10px; top:12px;"><i class="fa fa-caret-down"></i></span></a>
                                <ul class="dropdown">
                                    <li><a href="<?php echo base_url();?>admin/product/add_product">Add Product</a></li>
                                    <li><a href="<?php echo base_url();?>admin/product/products">View Products</a></li>
                                    <li><a href="<?php echo base_url();?>admin/product/product_categories">Categories</a></li>
                                </ul>
                            </li>
                            <li><a href="<?php echo base_url(); ?>admin/sale/sales" ><i class="fa fa-shopping-basket" aria-hidden="true"></i> Sales </a></li>
                            <li><a href="<?php echo base_url(); ?>admin/sale/refunds" ><i class="fa fa-money" aria-hidden="true"></i> Refunds <span class="badge" style="position: absolute; right:10px; top:12px; background:#ca4a1f;" id="refundCounter"></span></a></li>
                            <li><a href="<?php echo base_url(); ?>admin/sale/refund_setting" ><i class="fa fa-money" aria-hidden="true"></i> Refund Settings</a></li>
                            <li><a href="<?php echo base_url(); ?>admin/sale/coupons" ><i class="ti-archive" aria-hidden="true"></i> Coupons</a></li>
                            <!-- <li><a href="<?php //echo base_url(); ?>admin/orders" ><i class="fa fa-shopping-cart" aria-hidden="true"></i> Cart </a></li> -->
                            <li><a href="<?php echo base_url(); ?>admin/order/orders" ><i class="fa fa-cart-arrow-down" aria-hidden="true"></i> Orders <span class="badge" style="position: absolute; right:10px; top:12px; background:#ca4a1f;" id="orderCounter"></span></a></li>
                            <li><a href="<?php echo base_url(); ?>admin/product/referrals"><i class="ti-cloud"></i> Referrals</a></li>
                            <li><a href="<?php echo base_url(); ?>admin/sale/affiliate_sales" ><i class="fa fa-shopping-basket" aria-hidden="true"></i> Affiliate Sales </a></li>
                            <li><a href="<?php echo base_url(); ?>admin/commission/commissions" ><i class="icon-tag" aria-hidden="true"></i> Commissions</a></li>
                            <li><a href="<?php echo base_url(); ?>admin/commission/transaction_balance" ><i class="fa fa-bar-chart" aria-hidden="true"></i> Transaction Balance</a></li>
                            <li><a href="javascript:void();" ><i class="icon-tag" aria-hidden="true"></i> Commission Rates <span style="position: absolute; right:10px; top:12px;"><i class="fa fa-caret-down"></i></span></a>
                                <ul class="dropdown">
                                    <li><a href="<?php echo base_url(); ?>admin/commission/commission_rates">Products</a></li>
                                    <li><a href="<?php echo base_url(); ?>admin/commission/commission_rates_vendor">Vendor Wise</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void();" ><i class="ti-credit-card" aria-hidden="true"></i> Withdrawal <span class="badge" style="position: absolute; right:10px; top:12px; background:#ca4a1f;" id="newWithdrawRequest"></span></a>
                                <ul class="dropdown">
                                    <li><a href="<?php echo base_url(); ?>admin/commission/withdraw_request">Request</a></li>
                                    <li><a href="<?php echo base_url(); ?>admin/commission/withdraw_reject">Rejected Request</a></li>
                                    <li><a href="<?php echo base_url(); ?>admin/commission/withdrawal_payouts">Payouts</a></li>
                                </ul>
                            </li>
                            <li><a href="<?php echo base_url(); ?>admin/report/reports" ><i class="fa fa-image" aria-hidden="true"></i>Reports</a></li>
                            <li><a href="javascript:void();" ><i class="fa fa-users" aria-hidden="true"></i> User Accounts <span class="badge" style="position: absolute; right:10px; top:12px; background:#ca4a1f;" id="newAccountCounter"></span></a>
                                <ul class="dropdown">
                                    <li><a href="<?php echo base_url(); ?>admin/account/customers">Customers</a></li>
                                    <li><a href="<?php echo base_url(); ?>admin/account/vendors">Vendors</a></li>
                                    <li><a href="<?php echo base_url(); ?>admin/account/affiliates">Affiliates</a></li>
                                    <li><a href="<?php echo base_url(); ?>admin/account/pending_accounts">Pending Accounts</a></li>
                                </ul>
                            </li>
                            <li><a href="javascript:void();" ><i class="fa fa-user" aria-hidden="true"></i> System Admins <span style="position: absolute; right:10px; top:12px;"><i class="fa fa-caret-down"></i></span></a>
                                <ul class="dropdown">
                                    <li><a href="<?php echo base_url(); ?>admin/account/system_admins">Manage</a></li>
                                    <li><a href="<?php echo base_url(); ?>admin/account/add_admin">Add New</a></li>
                                </ul>
                            </li>
                            <li><a href="javascript:void();" ><i class="fa fa-envelope-open" aria-hidden="true"></i> Alerts/Messages <span class="badge" style="position: absolute; right:10px; top:12px; background:#ca4a1f;"></span></a>
                                <ul class="dropdown">
                                    <li><a href="<?php echo base_url(); ?>admin/home/new_message">New Message</a></li>
                                    <li><a href="<?php echo base_url(); ?>admin/home/messages">Messages</a></li>
                                </ul>
                            </li>
                            <li><a href="<?php echo base_url(); ?>admin/blog/tv_post" ><i class="fa fa-desktop" aria-hidden="true"></i>TV Posts</a></li>
                            <!-- <li><a href="<?php //echo base_url(); ?>admin/visits" ><i class="fa fa-image" aria-hidden="true"></i>Visits</a></li> -->
                            <!-- <li><a href="<?php //echo base_url(); ?>admin/media_center" ><i class="fa fa-image" aria-hidden="true"></i>Media Center</a></li> -->
                            <li><a href="javascript:void();" ><i class="fa fa-newspaper-o" aria-hidden="true"></i> Blog <span style="position: absolute; right:10px; top:12px;"><i class="fa fa-caret-down"></i></span></a>
                                <ul class="dropdown">
                                    <li><a href="<?php echo base_url(); ?>admin/blog/new_post">New Post</a></li>
                                    <li><a href="<?php echo base_url(); ?>admin/blog/my_posts">My Posts</a></li>
                                    <li><a href="<?php echo base_url(); ?>admin/blog/pending_posts">Pending Posts</a></li>
                                    <li><a href="<?php echo base_url(); ?>admin/blog/posts">All Posts</a></li>
                                    <li><a href="<?php echo base_url(); ?>admin/blog/blog_categories">Categories</a></li>
                                </ul>
                            </li>
                            <li><a href="javascript:void();" ><i class="fa fa-question-circle" aria-hidden="true"></i> Self Help <span style="position: absolute; right:10px; top:12px;"><i class="fa fa-caret-down"></i></span></a>
                                <ul class="dropdown">
                                    <li><a href="<?php echo base_url(); ?>admin/blog/new_self_help">Post New Help</a></li>
                                    <li><a href="<?php echo base_url(); ?>admin/blog/self_help_category">Categories</a></li>
                                    <li><a href="<?php echo base_url(); ?>admin/blog/self_help_sub_category">Sub-Categories</a></li>
                                    <li><a href="<?php echo base_url(); ?>admin/blog/self_help">All Help</a></li>
                                </ul>
                            </li>
                            <li><a href="<?php echo base_url(); ?>admin/home/subscribers" ><i class="ti-write" aria-hidden="true"></i>Subscribers</a></li>
                            <li><a href="<?php echo base_url(); ?>admin/home/admin_profile"><i class="icon-user"></i> My Profile</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-9 col-xs-12 col-md-9 col-lg-10 col-sm-offset-3 col-md-offset-3 col-lg-offset-2">

</body>
</html>