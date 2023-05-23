<script type="text/javascript">
    var referral="";
</script>
<?php
    $first_param=rtrim($this->uri->slash_segment(1), '/');
    $second_param=rtrim($this->uri->slash_segment(2), '/');
    // die($folder_name);
    // die(uri_string());
    if($first_param!='assets' && $first_param!='users' && uri_string()!='users/register' && uri_string()!='users' && uri_string()!='login' && uri_string()!='register' && uri_string()!='account_activation' && uri_string()!='logout' &&  uri_string()!='customer/sb_download' && uri_string()!=''){
    // if(basename($_SERVER['PHP_SELF'])!='login' && basename($_SERVER['PHP_SELF'])!='register' && basename($_SERVER['PHP_SELF'])!='logout'){
        // $this->session->set_userdata('redirect_back', $_SERVER['HTTP_REFERER']);
        $this->session->set_userdata('redirect_back', $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].''.$_SERVER['REQUEST_URI']);
    }
    if(isset($_REQUEST['ref']) && !empty($_REQUEST['ref'])){
        echo '<script>';
        echo 'referral="'.$_REQUEST['ref'].'"';
        echo '</script>';
    }
    if(basename($_SERVER['PHP_SELF'])!=""){
        new_pageView(basename($_SERVER['PHP_SELF']));
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="Jeremiah Samwel">
    <meta name="description" content="GetValue is an exceptionally and uniquely designed platform, our mission is to be an International favourite destination for finding unlimited value for our customers, sellers and affiliate marketers worldwide.">
    <!-- <title>GetValue, Inc | A Global Online Digital Information Products Retailer</title> -->
    <meta name="keywords" content="Audiobooks, Ebooks, Online Trainings & Programs, Online Book Store, Buy and Sell Ebooks Online.">
    <link rel="shortcut icon" href="<?= base_url('assets/themes/favicon.png') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/font-awesome.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap-select.min.css') ?>" />
    <link href="<?= base_url('assets/css/bootstrap-datepicker.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/dimension.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/getvalue.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/themify-icons.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/simple-line-icons.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/custom.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/theme.css') ?>" rel="stylesheet" />
    <!-- <link href="<?= base_url('assets/lightslider/lightslider.css') ?>" rel="stylesheet" /> -->
    <!-- <link href="<?= base_url('assets/css/product_slider.css') ?>" rel="stylesheet" /> -->
    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/all.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <div id="wrapper">
        <div id="content">
            <div id="languages-bar">
                <div class="container">
                    <ul class="pull-left">
                        <li class="phone label-xs">
                            <img src="<?php echo base_url(); ?>/assets/themes/icons/Phone-icon.png" alt="Call us">
                            +255 717 568 861
                        </li>
                        <!-- <li class="phone label-xs"><a href="https://play.google.com/store/apps/details?id=com.getvalue.getvalue"><img src="<?=base_url('assets/themes/icons/playstore_btn.png')?>" class="m1"> Android App</a></li>
                        <li class="last-item phone label-xs"><a href="https://apps.apple.com/tz/app/getvalue/id1644488065"><img src="<?=base_url('assets/themes/icons/appstore_btn.png')?>" class="m2"> IOS App</a></li> -->
                    </ul>
                    <?php if(isset($_SESSION['getvalue_user_idetification']) && $_SESSION['getvalue_user_idetification']!=""){ ?>
                    <div class="phone pull-left label-xs m-l-20">
                        <a href="<?php echo base_url(); ?><?php if($this->session->userdata('account_type')=='customer'){ echo 'customer/';}elseif($this->session->userdata('account_type')=='admin'){ echo $this->session->userdata('account_type');}else{ echo 'seller/'.$this->session->userdata('account_type'); } ?>" class="wColor"><i class="fa fa-user"></i> My Account </a>
                    </div>
                    <div class="phone pull-left label-xs m-l-20">
                        <a href="<?php echo base_url(); ?>logout" class="wColor"><i class="fa fa-sign-out"></i> Logout</a>
                    </div>
                    <?php }else{ ?>
                    <!-- <div class="phone pull-right">
                        <img src="<?php //echo base_url(); ?>/assets/themes/icons/Phone-icon.png" alt="Call us">
                        +255 717 568 861
                    </div> -->
                    <div class="phone pull-right">
                        <a href="<?php echo base_url(); ?>login" class="wColor"><i class="fa fa-sign-in"></i> Sign In</a>
                    </div>
                    <?php } ?>                    
                    <ul class="pull-right app_links">
                        <li class="phone label-xs"><a href="https://play.google.com/store/apps/details?id=com.getvalue.getvalue"><img src="<?=base_url('assets/themes/icons/playstore.png')?>" height="50px;"> Android App</a></li>
                        <li class="last-item phone label-xs m-r-10"><a href="https://apps.apple.com/tz/app/getvalue/id1644488065"><img src="<?=base_url('assets/themes/icons/appstore.png')?>" height="50px;"> IOS App</a></li>
                    </ul>
                </div>
            </div>
            <div id="top-part">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 col-md-3 col-lg-4 left">
                            <a href="<?= base_url() ?>">
                                <img src="<?= base_url('assets/themes/logo.png') ?>" class="site-logo" alt="<?= $_SERVER['HTTP_HOST'] ?>">
                            </a>
                        </div>
                        <div class="col-sm-6 col-md-5 col-lg-5">
                            <div class="input-group" id="adv-search">
                                <input type="text" value="" id="search_in_title" class="form-control" placeholder="Search for word in the title" />
                                <div class="input-group-btn">
                                    <div class="btn-group" role="group">
                                        <div class="dropdown dropdown-lg">
                                            <button type="button" class="button-more dropdown-toggle mine-color" data-toggle="dropdown" aria-expanded="false">More <span class="caret"></span></button>
                                            <div class="dropdown-menu dropdown-menu-right" role="menu">
                                                <form class="form-horizontal row" method="GET" id="bigger-search">
                                                    <div class="col-md-6">
                                                        <select class="form-control" id="product_category">
                                                            <option value="">Select Product Category</option>
                                                            <option value="">All</option>
                                                            <?php loadCategories(""); ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <select class="form-control" id="blog_category">
                                                            <option value="">Select Blog Category</option>
                                                            <option value="">All</option>
                                                            <?php loadBlogCategories(""); ?>
                                                        </select>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <a onclick="submitSearchForm()" class="btn btn-go-search mine-color">
                                            <img src="<?= base_url('assets/themes/icons/search-ico.png') ?>" alt="Search">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="basket-box">
                                <table>
                                    <tr>
                                        <td>
                                            <img src="<?= base_url('assets/themes/icons/green-basket.png') ?>" class="green-basket" alt="">
                                        </td>
                                        <td>
                                            <div class="center">
                                                <h4>Your Basket</h4>
                                                <a href="<?php echo base_url(); ?>home/checkout">Checkout</a> |
                                                <a href="<?php echo base_url(); ?>customer/cart">Shopping Cart</a>
                                            </div>
                                        </td>
                                        <td>
                                            <ul class="shop-dropdown">
                                                <li class="dropdown text-center">
                                                        <a href="cart" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> 
                                                            <div><span class="sumOfItems" id="cartCounts">0</span> Items</div>
                                                            <img src="<?= base_url('assets/themes/icons/shopping-cart-icon-515.png') ?>" alt="">
                                                            <span class="caret"></span>
                                                        </a>
                                                    <ul class="dropdown-menu dropdown-menu-right dropdown-cart" role="menu" id="cartList">
                                                        <li class="text-center">No Product</li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>        
                    </div>
                </div>
            </div>
            <nav class="navbar bg-black">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>

                    <div id="navbar" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav" style="margin-left:-15px">
                            <li> <a href="<?php echo base_url(); ?>">Home</a> </li>
                            <li> <a href="<?php echo base_url(); ?>ebooks">Ebooks</a> </li>
                            <li> <a href="<?php echo base_url(); ?>audiobooks">Audiobooks</a> </li>
                            <li> <a href="<?php echo base_url(); ?>online_trainings">Online Trainings & Programs </a> </li>
                                <ul class="nav navbar-nav">
                                    <li class="dropdown dropdown-more">
                                        <a class="dropdown-toggle parent innerMenu" data-toggle="dropdown" href="#">
                                                Inspirations  
                                            <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu inspMenu">
                                            <?php list_blog_category(); ?>
                                        </ul>
                                    </li>
                                </ul>
                            <li><a href="<?php echo base_url(); ?>getvaluetv">GetValue TV</a></li>
                            <?php if(!isset($_SESSION['getvalue_user_idetification']) || $_SESSION['getvalue_user_idetification']==""){ ?>
                            <li><a href="<?php echo base_url(); ?>register">Register</a></li>
                            <!-- <li><a href="javascript:void();" data-toggle="modal" data-target="#register_popup">Buyer Register</a></li> -->
                            <li><a href="<?php echo base_url(); ?>login">Login</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </nav>
<body>
</html>