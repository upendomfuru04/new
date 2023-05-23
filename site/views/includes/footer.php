<?php ?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <footer>
        <div class="footer-bg center">
            <div class="container">
                <div class="row">
                    <div class="footer-logo"><img class="img-responsive" src="<?= base_url('assets/themes/logo.png') ?>" alt=""></div>
                    <h2>JOIN OUR NETWORK</h2>
                    <p>Get our best advice of the week direct to your inbox</p>
                    <div class="subscribe">
                        <form method="POST" id="subscribeForm">
                            <div class="input-group">
                                <input class="form-control" type="text" class="full text-center" name="subscribeEmail" placeholder="Email address" id="subscribeEmail">
                                <a class="btn bg-gold" type="button" onclick="addSubscription();">Subscribe <i class="fa fa-long-arrow-right"></i></a>
                            </div>
                        </form>
                    </div>
                    <hr>
                    <div class="get_app row">
                        <div class="col-sm-6 col-xs-6">
                            <a href="https://play.google.com/store/apps/details?id=com.getvalue.getvalue" class="pull-right"><img src="<?=base_url('assets/themes/icons/playstore_btn.png')?>" class="m1"></a>
                        </div>
                        <div class="col-sm-6 col-xs-6">
                            <a href="https://apps.apple.com/tz/app/getvalue/id1644488065" class="pull-left"><img src="<?=base_url('assets/themes/icons/appstore_btn.png')?>" class="m2"></a>
                        </div>
                    </div>
                    
                    <div class="footer-links">
                        <ul>
                            <li><a href="<?php echo base_url(); ?>customer_support">Customer Support</a></li>|
                            <li><a href="<?php echo base_url(); ?>help">Self Help Center</a></li>|
                            <li><a href="<?php echo base_url(); ?>getvaluetv">GetValue TV</a></li>|
                            <li><a href="<?php echo base_url(); ?>sell_on_getvalueinc">Sell On GetValue</a></li>|
                            <li><a href="<?php echo base_url(); ?>become_affiliate">Become an Affiliate</a></li>|
                            <li><a href="<?php echo base_url(); ?>contact">Contact Us</a></li>|
                            <li><a href="<?php echo base_url(); ?>about">About GetValue</a></li>
                        </ul>
                    </div>
                    
                    <ul class="social-links">
                        <li> <a href="https://www.facebook.com/getvalue.co"><i class=" fa fa-facebook"></i></a></li>
                        <li> <a href="https://mobile.twitter.com/getvalue_"><i class="fa fa-twitter"></i></a></li>
                        <li> <a href="https://www.instagram.com/getvalue.co"><i class="fa fa-instagram"></i></a></li>
                        <li> <a href="https://www.linkedin.com/company/getvalueinc"><i class="fa fa-linkedin"></i></a></li>
                        <li> <a href="https://www.youtube.com/@getvalue"><i class="fa fa-youtube"></i></a>
                    </ul>
                    
                    <p style="color: #debe67;">Copyrights © 2020 - <?php echo date("Y"); ?> GetValue  |  All Rights Reserved</p>
                    
                    <!-- <p>Website by: <a href="http://www.oceaniatz.com" style="color: #00adef;" target="_blank">Oceania Co LTD</a></p> -->
                    
                    <div class="footer-links">
                        <ul>
                            <li><a href="<?php echo base_url(); ?>terms">Terms of Service</a></li>|
                            <li><a href="<?php echo base_url(); ?>policy">Privacy Policy</a></li>|
                            <li><a href="<?php echo base_url(); ?>cookies">Cookies Policy</a></li>
                            <!-- <li><a href="<?php //echo base_url(); ?>sitemap">Sitemap</a></li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    </div>
    </div>

    <!-- Modals -->
    <div class="modal fade" id="changepassword" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Change Password</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="changepassword-form">
                        <div class="form-group">
                            <input type="password" name="cpassword" class="form-control" placeholder="Current Password"> 
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control" placeholder="New Password"> 
                        </div>
                        <div class="form-group">
                            <input type="password" name="repassword" class="form-control" id="passwordChangeField" placeholder="Re-Enter New Password">
                        </div>
                    </form>
                    <div class="row"><div class="col-md-12" id="resultMsgChangePass"></div></div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-info text-white waves-effect waves-light turnOnChangePassProgress" id="userChangePassword">Change Password</a>
                    <a class="btn btn-info text-white waves-light progressBarChangePassBtn"><i class="fa fa-spinner fa-spin"></i> Processing...</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="login_popup" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Login</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="login-form">
                        <div class="form-group">
                            <label>Username: </label>
                            <input type="text" name="username" class="form-control" placeholder=""> 
                        </div>
                        <div class="form-group">
                            <label>Password: </label>
                            <input type="password" name="password" id="popupPassword" class="form-control" placeholder=""> 
                        </div>
                        <div class="form-group">
                            <center><p>Dont have account? Create <a href="javascript:void(0);" data-toggle="modal" data-target="#register_popup" onclick="$('#login_popup').modal('hide');" class="m-l-0 m-r-0">buyer account</a></p></center>
                        </div>
                    </form>
                    <div class="row"><div class="col-md-12" id="resultMsgLogin"></div></div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-success text-white waves-effect waves-light turnOnLoginProgress" onclick="loginPopup();" id="popupLogin">Sign In</a>
                    <a class="btn btn-success text-white waves-light progressBarLoginBtn"><i class="fa fa-spinner fa-spin"></i> Processing...</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade sm_modal" id="register_popup" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create Buyer Account</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="reg-form">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>First name:</label>
                                <input type="text" name="fname" value="" class="form-control" placeholder="ie. First name" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Surname:</label>
                                <input type="text" name="sname" value="" class="form-control" placeholder="ie. Surname" />
                            </div>
                            <div class="form-group col-md-12">
                                <label>Email:</label>
                                <input type="email" name="email" value="" class="form-control" placeholder="youremail@domain.com" />
                            </div>
                            <div class="form-group col-md-12">
                                <label>Phone:</label>
                                <input type="text" name="phone" value="" class="form-control" placeholder="+25575xxxxxxx" />
                            </div>
                            <div class="form-group col-md-12">
                                <label>Password:</label>
                                <input type="password" name="password" value="" class="form-control" placeholder="************" />
                            </div>
                            <div class="col-md-12"><p><small>Note: Username is your email address.</small></p></div>
                            <div class="clear"></div>
                        </div>
                    </form>
                    <div class="row"><div class="col-md-12" id="resultMsgRegBuyer"></div></div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-success text-white waves-effect waves-light turnOnRegProgress" onclick="registerPopup();">Register</a>
                    <a class="btn btn-success text-white waves-light progressBaRegBtn"><i class="fa fa-spinner fa-spin"></i> Processing...</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="coupon_popup" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Discount code</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="coupon-form">
                        <p>Do you have a coupon? if yes type it in the box below, VERIFY it then CONTINUE. If you don`t have then Click CONTINUE...</p>
                        <div class="form-group">
                            <input class="form-control" name="coupon" value="" id="coupon_Code" placeholder="Enter code here" type="text">
                        </div>
                    </form>
                    <div class="row"><div class="col-md-12" id="resultMsgCoupon"></div></div>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <p><strong>Price: </strong><span id="given_total_amount"></span> Tsh.</p>
                            <p><strong>Discount (#Coupon): </strong><span id="coupon_amount1">0</span> Tsh.</p>
                            <p><strong>Total Price: </strong><span id="total_amount1"></span> Tsh.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-md-12" id="resultMsgCoupon"></div>
                    <a class="btn btn-default text-white waves-light turnOnProg" onclick="validate_coupon();">Verify</a>
                    <a class="btn btn-default text-white waves-light progressBarC"><i class="fa fa-spinner fa-spin"></i> Verifying...</a>
                    <a class="btn btn-success text-white waves-effect waves-light turnOnProg" href="javascript:void();" onclick="buyProductDirect();">Continue</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade sm_modal" id="cart_item_list" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Item(s) in your Cart (<span id="list_item_counter"></span>)</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-stripped" id="cart_item_list_views"></table>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-sm btn-success text-white waves-effect waves-light" href="<?=base_url('home/checkout');?>">Checkout Now</a>
                    <a class="btn btn-sm text-white waves-effect waves-light" class="close" data-dismiss="modal" style="background: #000000; color: #fff;">Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>
    <!-- end of modals -->

    <div id="notificator" class="alert"></div>
    <script src="<?= base_url('assets/js/bootstrap-confirmation.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap-select.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/placeholders.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap-datepicker.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/system.js') ?>"></script>
    <script src="<?= base_url('assets/js/mine.js') ?>"></script>
    <!-- <script src="<?= base_url('assets/lightslider/lightslider.js') ?>"></script> -->
    <!-- <script src="<?= base_url('assets/js/product_slider.js') ?>"></script> -->
</body>
</html>
<script>
    $(document).ready(function(){
        var total_amount1=0;
        loadCartCount();
        check_new_message();

        $('#userChangePassword').click(function(){
            $('.turnOnChangePassProgress').css('display','none');
            $('.progressBarChangePassBtn').css('display','inline-block');
            $.ajax({
                url:'<?= base_url() ?>Users/change_password',
                type:"POST",
                data:$('#changepassword-form').serialize(),
                success: function(data){
                    if(data.trim()=='Success'){
                        var output = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> Success </div>'; 
                        $('#resultMsgChangePass').html(output);
                        document.getElementById("changepassword-form").reset();
                        window.location='';
                    }else{
                        $('#resultMsgChangePass').html(data);
                    }
                    $('.turnOnChangePassProgress').css('display','inline-block');
                    $('.progressBarChangePassBtn').css('display','none');
                }
            });
        });
        
        $("#passwordChangeField").keyup(function(event){
            if(event.keyCode == 13){
                var mValue=document.getElementById("passwordChangeField").value;
                if(mValue!=""){
                    $("#userChangePassword").click();
                }
            }
        });
        
        $("#popupPassword").keyup(function(event){
            if(event.keyCode == 13){
                var mValue=document.getElementById("popupPassword").value;
                if(mValue!=""){
                    $("#popupLogin").click();
                }
            }
        });
        
        $("#search_in_title").keyup(function(event){
            if(event.keyCode == 13){
                submitSearchForm();
            }
        });

    });

    function validate_coupon(){
        var coupon=document.getElementById('coupon_Code').value;
        if(coupon!=""){
            ShowNotificator('alert-info', "Verifying coupon...");
            $('.progressBarC').css('display','inline-block');
            $('.turnOnProg').css('display','none');
            $.ajax({
                url:'<?= base_url() ?>Customer/verify_coupon/'+coupon,
                type:"POST",
                success: function(data){
                    if(!isNaN(data.trim())){
                        var cAmount=data.trim();
                        ShowNotificator('alert-success', coupon+" Is valid");
                        $('#resultMsgCoupon').html('<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> Is valid, its value is '+cAmount+' tsh </div>');
                        $('#coupon_amount1').html(cAmount);
                        $('#total_amount1').html(total_amount1-cAmount);
                    }else if(data.trim()=='Invalid'){
                        ShowNotificator('alert-warning', coupon+" Is not valid coupon");
                        $('#resultMsgCoupon').html('<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button>Coupon not valid</div>');
                    }else if(data.trim()=='login'){
                        ShowNotificator('alert-info', "Please login to continue");
                        $('#login_popup').modal('show');
                    }else{
                        $('#resultMsgCoupon').html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button>' +data+ '</div>');
                    }
                    $('.progressBarC').css('display','none');
                    $('.turnOnProg').css('display','inline-block');
                },
                error: function(xhr, status, error) {
                    $('#resultMsgCoupon').html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button>' +error+ '</div>');
                    $('.progressBarC').css('display','none');
                    $('.turnOnProg').css('display','inline-block');
                    return false;
                }
            });
        }else{
            ShowNotificator('alert-info', "Provide your coupon");
        }
    }

    function addToCart(product, price, name, product_url){
        $('#progress'+product).css('display','inline-block');
        $.ajax({
            url:'<?= base_url() ?>Home/add_cart/?product='+product+'&price='+price+'&product_url='+product_url+'&referral='+referral,
            type:"POST",
            success: function(data){
                if(data.trim()=='Success'){
                    ShowNotificator('alert-success', name+" - added to your cart");
                    loadCartCount("show");
                }else if(data.trim()=='login'){
                    ShowNotificator('alert-info', "Please login then continue");
                    // window.location="<?php //echo base_url().'login'; ?>";
                    $('#login_popup').modal('show');
                }else{
                    ShowNotificator('alert-danger', data);
                }
                $('#progress'+product).css('display','none');
            },
            error: function(xhr, status, error) {
                ShowNotificator('alert-danger', error);
                $('#progress'+product).css('display','none');
                return false;
            }
        });
    }

    function addSubscription(){
        var subscribeEmail=document.getElementById('subscribeEmail').value;
        if(subscribeEmail==""){
            ShowNotificator('alert-danger', "Enter your email address to subscribe");
            return;
        }
        ShowNotificator('alert-info', "Processing...");
        $.ajax({
            url:'<?= base_url() ?>Home/add_subscription',
            type:"POST",
            data:$('#subscribeForm').serialize(),
            success: function(data){
                if(data.trim()=='Success'){
                    ShowNotificator('alert-success', "Thank you for subscribing... You will be receiving notifications about our services.");
                    document.getElementById("subscribeForm").reset();
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

    function buyProduct(product, price, name, product_url, coupon_code=""){
        $('#progress'+product).css('display','inline-block');
        $.ajax({
            url:'<?= base_url() ?>Home/add_cart/?product='+product+'&price='+price+'&product_url='+product_url+'&referral='+referral,
            type:"POST",
            success: function(data){
                if(data.trim()=='Success'){
                    if(coupon_code!=""){
                        window.location="<?php echo base_url().'home/checkout?product='; ?>"+product+'&coupon='+coupon_code;
                    }else{
                        window.location="<?php echo base_url().'home/checkout?product='; ?>"+product;
                    }
                    // load_order_info();
                }else if(data.trim()=='login'){
                    if(coupon_code!=""){
                        window.location="<?php echo base_url().'home/checkout?product='; ?>"+product+'&coupon='+coupon_code;
                    }else{
                        window.location="<?php echo base_url().'home/checkout?product='; ?>"+product;
                    }
                    // ShowNotificator('alert-info', "Please login then continue");
                    // window.location="<?php //echo base_url().'login'; ?>";
                    // $('#login_popup').modal('show');
                }else{
                    ShowNotificator('alert-danger', data);
                }
                $('#progress'+product).css('display','none');
            },
            error: function(xhr, status, error) {
                ShowNotificator('alert-danger', error);
                $('#progress'+product).css('display','none');
                return false;
            }
        });
    }

    function load_order_info(){
        $.ajax({
            url:'<?php echo base_url(); ?>customer/Customer/load_order_info',
            type:"POST",
            success: function(data){
                if(!isNaN(data.trim())){
                    total_amount1=data.trim();
                    $('#given_total_amount').html(total_amount1);
                    $('#total_amount1').html(total_amount1);
                    $('#coupon_popup').modal('show');
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

    function buyProductDirect(){
        var coupon=document.getElementById('coupon_Code').value;
        $('.progressBarC').css('display','inline-block');
        $('.turnOnProg').css('display','none');
        $.ajax({
            url:'<?php echo base_url(); ?>Customer/short_save_order/'+coupon,
            type:"POST",
            success: function(data){
                if(data.trim()=='Success'){
                    window.location="<?=base_url()?>customer/complete_payment";
                }else{
                    ShowNotificator('alert-danger', data);
                }
                $('.progressBarC').css('display','none');
                $('.turnOnProg').css('display','inline-block');
            },
            error: function(xhr, status, error) {
                ShowNotificator('alert-danger', error);
                $('.progressBarC').css('display','none');
                $('.turnOnProg').css('display','inline-block');
                return false;
            }
        });
    }

    function removeItem(product, name, showList=""){
        if (confirm("Are you sure you want to remove "+name+" from your cart?")) {
            $.ajax({
                url:'<?= base_url() ?>Home/remove_product/?product='+product,
                type:"POST",
                success: function(data){
                    if(data.trim()=='Success'){
                        ShowNotificator('alert-success', name+" - removed from your cart");
                        var sPath=window.location.pathname;
                        var sPage = sPath.substring(sPath.lastIndexOf('/') + 1);
                        if(sPage=='cart'){
                            window.location="";
                        }else{
                            loadCartCount(showList);
                        }
                    }else if(data.trim()=='login'){
                        ShowNotificator('alert-info', "Please login then continue");
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

    function removeAllItem(){
        if (confirm("Are you sure you want to clear your cart?")) {
            $.ajax({
                url:'<?= base_url() ?>Home/remove_all_product/',
                type:"POST",
                success: function(data){
                    if(data.trim()=='Success'){
                        ShowNotificator('alert-success', " Your cart cleared successfully");
                        var sPath=window.location.pathname;
                        var sPage = sPath.substring(sPath.lastIndexOf('/') + 1);
                        if(sPage=='cart'){
                            window.location="";
                        }else{
                            $('#cartCounts').html(0);
                            result='<li class="text-center">No Product</li>';
                            $('#cartList').html(result);
                        }
                    }else if(data.trim()=='login'){
                        ShowNotificator('alert-info', "Please login then continue");
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

    function loadCartCount(showList=""){
        $.ajax({
            url:'<?= base_url() ?>Home/load_cart_items',
            type:"POST",
            success: function(data){
                var listObj = JSON.parse(data);
                var count = Object.keys(listObj).length;
                var result=""; var total=0; var result_list="";
                $('#cartCounts').html(count);
                $('#list_item_counter').html(count);
                result+='<li class="cleaner text-right"><a href="javascript:void(0);" class="btn-blue-round" onclick="removeAllItem()">Clear</a></li><li class="divider"></li>';
                if(count>0){
                    for(i in listObj){
                        total=total+(listObj[i].quantity*listObj[i].price);
                        result+='<li class="shop-item" data-artticle-id="14"><span class="num_added hidden">'+count+'</span><div class="item"><div class="item-in"><div class="left-side"><img src="<?php echo base_url().'media/products/'; ?>'+listObj[i].image+'" alt="" class="pull-left" /></div><div class="right-side"><a href="<?php echo base_url().'prod/'; ?>'+listObj[i].product_url+'" class="item-info pull-left"><span>'+listObj[i].name+'</span><span class="prices"><span class="num-added-single">'+listObj[i].quantity+'</span> x <span class="price-single">'+listObj[i].price+'</span> - <span class="sum-price-single">'+(listObj[i].quantity*listObj[i].price)+'</span></span><span class="currency"><small> tsh</small></span></a></div></div><div class="item-x-absolute"><button class="btn btn-xs btn-danger pull-right" onclick="removeItem('+listObj[i].id+', \''+listObj[i].name+'\')">x</button></div></div></li><li class="divider"></li>';
                        result_list+='<tr><td><img src="<?php echo base_url().'media/products/'; ?>'+listObj[i].image+'" alt="" class="pull-left img-thumbnail" /></td><td><a href="<?php echo base_url().'prod/'; ?>'+listObj[i].product_url+'" class="item-info pull-left"><span>'+listObj[i].name+'</span><br><span class="prices">'+listObj[i].quantity+' x '+listObj[i].price+'</span> = <span class="sum-price-single">'+(listObj[i].quantity*listObj[i].price)+'</span></span><span class="currency"><small> tsh</small></span></a></td><td><button class="btn btn-xs btn-danger pull-right" onclick="removeItem('+listObj[i].id+', \''+listObj[i].name+'\', \'show\')">x</button></td></tr>';
                    }
                    result+='<li class="text-center"><a class="go-checkout btn btn-default btn-sm" href="<?php echo base_url(); ?>home/checkout"><i class="fa fa-check"></i> Checkout - <span class="finalSum">'+total+'</span><small> tsh</small></a></li>';
                    $('#cartList').html(result);
                    if(showList=='show'){
                        $('#cart_item_list_views').html(result_list);
                        $('#cart_item_list').modal('show');
                    }
                }else{
                    result='<li class="text-center">No Product</li>';
                    $('#cartList').html(result);
                }
            },
            error: function(xhr, status, error) {
                ShowNotificator('alert-danger', error);
                return false;
            }
        });
    }

    function registerPopup(){
        $('.turnOnRegProgress').css('display','none');
        $('.progressBaRegBtn').css('display','inline-block');
        $.ajax({
            url:'<?= base_url() ?>Users/customer_register_popup',
            type:"POST",
            data:$('#reg-form').serialize(),
            success: function(data){
                if(data.trim()=='Success'){
                    var output = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> You have successfully created an account. </div>'; 
                    $('#resultMsgRegBuyer').html(output);
                    document.getElementById("reg-form").reset();
                    window.location='';
                }else{
                    $('#resultMsgRegBuyer').html(data);
                }
                $('.turnOnRegProgress').css('display','inline-block');
                $('.progressBaRegBtn').css('display','none');
            },
            error: function(xhr, status, error) {
                $('#resultMsgRegBuyer').html(error);
                $('.turnOnRegProgress').css('display','inline-block');
                $('.progressBaRegBtn').css('display','none');
                return false;
            }
        });
    }

    function loginPopup(){
        $('.turnOnLoginProgress').css('display','none');
        $('.progressBarLoginBtn').css('display','inline-block');
        $.ajax({
            url:'<?= base_url() ?>Users/user_login',
            type:"POST",
            data:$('#login-form').serialize(),
            success: function(data){
                if(data.trim()=='Success'){
                    var output = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> Success </div>'; 
                    $('#resultMsgLogin').html(output);
                    document.getElementById("login-form").reset();
                    window.location='';
                }else{
                    $('#resultMsgLogin').html(data);
                }
                $('.turnOnLoginProgress').css('display','inline-block');
                $('.progressBarLoginBtn').css('display','none');
            },
            error: function(xhr, status, error) {
                $('#resultMsgLogin').html(error);
                $('.turnOnLoginProgress').css('display','inline-block');
                $('.progressBarLoginBtn').css('display','none');
                return false;
            }
        });
    }

    function sendReview(item){
        $('.turnOnReviewProgress').css('display','none');
        $('.progressBarReviewBtn').css('display','inline-block');
        $.ajax({
            url:'<?= base_url() ?>Home/post_user_review',
            type:"POST",
            data:$('#review-form').serialize(),
            success: function(data){
                if(data.trim()=='Success'){
                    var output = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> Success </div>'; 
                    $('#resultMsgReview').html(output);
                    document.getElementById("review-form").reset();
                    $('#review-form').css('display','none');
                    if (typeof load_reviews === 'function')
                    load_reviews(product);
                }else if(data.trim()=='login'){
                    $('#login_popup').modal('show');
                }else{
                    $('#resultMsgReview').html(data);
                }
                $('.turnOnReviewProgress').css('display','inline-block');
                $('.progressBarReviewBtn').css('display','none');
            },
            error: function(xhr, status, error) {
                $('.turnOnReviewProgress').css('display','inline-block');
                $('.progressBarReviewBtn').css('display','none');
                $('#resultMsgReview').html(error);
                return false;
            }
        });
    }

    function sendReviewReply(rev){
        $('#turnOnProg'+rev).css('display','none');
        $('#progress'+rev).css('display','inline-block');
        var product=document.getElementById('reviewed_product'+rev).value;
        var parent=document.getElementById('parent_review'+rev).value;
        var review=document.getElementById('reviewed_review'+rev).value;
        $.ajax({
            url:'<?= base_url() ?>Home/post_user_reply_review?product='+product+'&parent='+parent+'&review='+review,
            type:"POST",
            data:$('#review-form').serialize(),
            success: function(data){
                if(data.trim()=='Success'){
                    var output = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> Success </div>'; 
                    $('#resultMsgReply'+rev).html(output);
                    document.getElementById("review-form").reset();
                    $('#review-form').css('display','none');
                    if (typeof load_reviews === 'function')
                    load_reviews(product);
                }else if(data.trim()=='login'){
                    $('#login_popup').modal('show');
                }else{
                    $('#resultMsgReply'+rev).html(data);
                }
                $('#turnOnProg'+rev).css('display','inline-block');
                $('#progress'+rev).css('display','none');
            },
            error: function(xhr, status, error) {
                $('#turnOnProg'+rev).css('display','inline-block');
                $('#progress'+rev).css('display','none');
                $('#resultMsgReply'+rev).html(error);
                return false;
            }
        });
    }

    function sendComment(item){
        $('.turnOnReviewProgress').css('display','none');
        $('.progressBarReviewBtn').css('display','inline-block');
        $.ajax({
            url:'<?= base_url() ?>Home/post_user_comment',
            type:"POST",
            data:$('#comment-form').serialize(),
            success: function(data){
                if(data.trim()=='Success'){
                    var output = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> Success </div>'; 
                    $('#resultMsgReview').html(output);
                    document.getElementById("comment-form").reset();
                    $('#comment-form').css('display','none');
                    if (typeof load_comment === 'function')
                    load_comment(post);
                }else if(data.trim()=='login'){
                    $('#login_popup').modal('show');
                }else{
                    $('#resultMsgReview').html(data);
                }
                $('.turnOnReviewProgress').css('display','inline-block');
                $('.progressBarReviewBtn').css('display','none');
            },
            error: function(xhr, status, error) {
                $('.turnOnReviewProgress').css('display','inline-block');
                $('.progressBarReviewBtn').css('display','none');
                $('#resultMsgReview').html(error);
                return false;
            }
        });
    }

    function sendCommentReply(rev){
        $('#turnOnProg'+rev).css('display','none');
        $('#progress'+rev).css('display','inline-block');
        var post=document.getElementById('commented_product'+rev).value;
        var parent=document.getElementById('parent_comment'+rev).value;
        var comment=document.getElementById('commented_comment'+rev).value;
        $.ajax({
            url:'<?= base_url() ?>Home/post_user_reply_comment?post='+post+'&parent='+parent+'&comment='+comment,
            type:"POST",
            data:$('#comment-form').serialize(),
            success: function(data){
                if(data.trim()=='Success'){
                    var output = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> Success </div>'; 
                    $('#resultMsgReply'+rev).html(output);
                    document.getElementById("comment-form").reset();
                    $('#comment-form').css('display','none');
                    if (typeof load_comment === 'function')
                    load_comment(post);
                }else if(data.trim()=='login'){
                    $('#login_popup').modal('show');
                }else{
                    $('#resultMsgReply'+rev).html(data);
                }
                $('#turnOnProg'+rev).css('display','inline-block');
                $('#progress'+rev).css('display','none');
            },
            error: function(xhr, status, error) {
                $('#turnOnProg'+rev).css('display','inline-block');
                $('#progress'+rev).css('display','none');
                $('#resultMsgReply'+rev).html(error);
                return false;
            }
        });
    }

    function load_reviews(product){
        isPaused = false;
        $.ajax({
            url:'<?= base_url() ?>Home/load_user_reviews?product='+product,
            type:"GET",
            success: function(data){
                $('#reviewArea').html(data);
            },
            error: function(xhr, status, error) {
                //ShowNotificator('alert-danger', error);
                //window.location='';
                return false;
            }
        });
    }

    function load_comment(post){
        isPaused = false;
        $.ajax({
            url:'<?= base_url() ?>Home/load_user_comments?post='+post,
            type:"GET",
            success: function(data){
                $('#commentArea').html(data);
            },
            error: function(xhr, status, error) {
                //ShowNotificator('alert-danger', error);
                //window.location='';
                return false;
            }
        });
    }

    function submitSearchForm(){
        var keyword=document.getElementById("search_in_title").value;
        var product_category=document.getElementById("product_category").value;
        var blog_category=document.getElementById("blog_category").value;
        if(keyword!=""){
            window.location='<?php echo base_url(); ?>search_results?keyword='+keyword+'&blog_category='+blog_category+'&product_category='+product_category;
        }else{
            // alert("keyword is empty");
        }
    }

    function check_new_message(){
        $.ajax({
            url:'<?= base_url() ?>Home/check_new_message',
            type:"POST",
            success: function(data){
                $('#messageCounter').html(data.trim());
            },
            error: function(xhr, status, error) {
                return false;
            }
        });
    }
</script>
<?php ?>