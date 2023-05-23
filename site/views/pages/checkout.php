<?php
    $email_address=""; $full_name=""; $phone=""; $p_method="";
    if(isset($user_info) && sizeof($user_info)>0){
        if(isset($user_info['first_name'])){
            $full_name=$user_info['first_name'].' '.$user_info['surname'];
        }else{
            $full_name=$user_info['full_name'];
        }
        $email_address=$user_info['email'];
    }
    if($full_name==""){ $full_name=$nspl_name;}
    if($email_address==""){ $email_address=$nspl_email;}
    if($p_method==""){ $p_method=$nspl_payment_method;}
    if($phone==""){ $phone=$nspl_payment_number;}
    $total_amount=0; $product_tag=""; $price=""; $orderID="";
    if(isset($order_info['total_amount']) && $order_info['total_amount']>0){
        $total_amount=$order_info['total_amount'];
        $orderID=$order_info['orderID'];
    }
    if(isset($product_info['price']) && $product_info['price']>0){
        $price=$product_info['price'];
        if(isset($product) && $product!=""){
            $product_tag=$product;
        }
    }
    if($total_amount==0 && $price!=""){
        $total_amount=$price;
    }

    if($total_amount==0){
        header("Location: ".base_url());
    }
    $productUrl="";
    if(isset($product_info) && isset($product_info['product_url']) && $product_info['product_url']!=""){
        $productUrl=$product_info['product_url'];
    }
    $coupon_code="";
    if(isset($_REQUEST['coupon']) && $_REQUEST['coupon']!=""){
        $coupon_code=$_REQUEST['coupon'];
    }
    
?>
<!DOCTYPE html>
<html>
<head>
	<title>Checkout - GetValue</title>
    <style type="text/css">
        sup{
            color: red;
        }
        
        /* Style for modal pop up*/
        
    @media (min-width: 320px) and (max-width: 374px) {
        
        #payment_success_modal {
          padding-top: 90px;
        }

        .logo {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto;
            position: absolute;
            top: -70px;
            left: 0px;
            right: 0px;
            border: 2px solid black;
            background-color: black;
        }

        .logo p {
        font-size: 1.3em;
        }

        #paymentSuccessText {
            font-size: 1.2em;
        }

        #paymentModalContent{
            padding: 10px;
            width: 350px;
        }
        
         .appLogo1{
            width: 145px;
            height: 47px;
        }

        .appLogo2{
            width: 165px;
            height: 50px;
        }
        
        #modalLogo{
            height: 30px;
        }
    }

    @media (min-width: 375px) and (max-width: 424px) {
        
        #payment_success_modal {
            padding-top: 90px;
        }

        .logo {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto;
            position: absolute;
            top: -70px;
            left: 0px;
            right: 0px;
            border: 2px solid black;
            background-color: black;
        }

        .logo p {
        font-size: 1.3em;
        }

        #paymentSuccessText {
            font-size: 1.2em;
        }

        #paymentModalContent{
            padding: 15px;
            width: 350px;
        }
        
         .appLogo1{
            width: 140px;
            height: 47px;
        }

        .appLogo2{
            width: 175px;
            height: 50px;
        }
        
        #modalLogo{
            height: 30px;
        }
    }

    @media (min-width: 425px) and (max-width: 767px) {
        #payment_success_modal {
          padding-top: 80px;
          display: grid;
          align-items: center;
        }

        .logo {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto;
            position: absolute;
            top: -70px;
            left: 0px;
            right: 0px;
            border: 2px solid black;
            background-color: black;
        }

        .logo p {
        font-size: 1.3em;
        }

        #paymentSuccessText {
            font-size: 1.2em;
        }

        #paymentModalContent{
            padding: 15px;
            width: 430px;
        }
        
        .appLogo1{
            width: 150px;
            height: 47px;
        }

        .appLogo2{
            width: 165px;
            height: 50px;
        }
        
        #modalLogo{
           height: 25px;
        }
    }

    @media (min-width: 767px){

        #payment_success_modal {
            padding-top: 120px;
            /*display: grid;*/
            /*align-items: center;*/
        }

        .logo {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto;
            position: absolute;
            top: -70px;
            left: 0px;
            right: 0px;
            border: 2px solid black;
            background-color: black;
        }

        .logo p {
        font-size: 1.3em;
        }

        #paymentSuccessText {
            font-size: 1.2em;
        }

        #paymentModalContent{
            padding: 20px;
            width: 450px;
        }

        .appLogo1{
            width: 165px;
            height: 47px;
        }

        .appLogo2{
            width: 185px;
            height: 50px;
        }
        
        #modalLogo{
            height: 32px;
        }
    }
    /* End style for modal pop up*/
    
    </style>
</head>
<body>
    <div class="container">
        <div class="row m-t-40">
            <div class="col-md-8 col-md-offset-2">
                <form class="row billings" method="POST" id="data-form">
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h2 class="panel-title">Checkout - Bill Information</h2>
                            </div>
                            <div class="panel-body">
                                <?php if(isset($cart) && sizeof($cart)>0){ ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered checkout_list">
                                            <thead>
                                                <th>#</th>
                                                <th colspan="2" width="60%">Product</th>
                                                <th width="20%">Price (Tsh.)</th>
                                                <th></th>
                                            </thead>
                                            <tbody id="cart_list">
                                                <?php
                                                    $counter=0; $totalPrice=0;
                                                    foreach($cart as $product){
                                                        $counter++;
                                                        $total=($product->price*$product->quantity);
                                                        $totalPrice=$totalPrice+$total;
                                                ?>
                                                <tr>
                                                    <td><?php echo $counter; ?></td>
                                                    <td><a href="<?php echo base_url().'prod/'.$product->product_url; ?>"><img src="<?php echo base_url().'media/products/thumb/'.$product->image; ?>" class="m-r-10"></a></td>
                                                    <td class="tb_name"><?php echo ucwords($product->name); ?></td>
                                                    <td><?php echo number_format($total); ?></td>
                                                    <td><a href="javascript:void(0);" onclick="removeItem(<?php echo $product->cartID; ?>, '<?php echo $product->name; ?>')"><i class="ti-trash text-danger"></i></a></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <td colspan="3"><b>Cart Total:</b></td>
                                                <td><b><?=number_format($totalPrice)?></b></td>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php if(!isset($_SESSION['getvalue_user_idetification']) || $_SESSION['getvalue_user_idetification']==""){ ?>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label>Name:</label>
                                        <input type="text" name="name" class="form-control" placeholder="ie. Full name" value="<?php echo $full_name; ?>"/>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Email:</label>
                                        <input type="email" name="email" class="form-control" placeholder="youremail@domain.com" value="<?php echo $email_address; ?>"/>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <?php }else{ ?>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label>Name:</label>
                                            <input type="text" name="name" class="form-control" placeholder="ie. Full name" value="<?php echo $full_name; ?>" readonly/>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Email:</label>
                                            <input type="email" name="email" class="form-control" placeholder="youremail@domain.com" value="<?php echo $email_address; ?>" readonly/>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                <?php } ?>
                                <label>Choose Payment Method:</label>
                                <div class="form-group mobile_payment_list">
                                    <ul>
                                        <li><label><input type="radio" name="payment_method" value="mpesa" onclick="showPhoneNumber('tanzania')"><img src="<?=site_url('assets/themes/payment_method/mpesa.png')?>"> Vodacom Tanzania</label></li>
                                        <li><label><input type="radio" name="payment_method" value="airtelmoney" onclick="showPhoneNumber('tanzania')"><img src="<?=site_url('assets/themes/payment_method/airtelmoney.png')?>"> Airtel Tanzania</label></li>
                                        <li><label><input type="radio" name="payment_method" value="tigopesa" onclick="showPhoneNumber('tanzania')"><img src="<?=site_url('assets/themes/payment_method/tigopesa1.png')?>"> Tigo Pesa</label></li>
                                        <li><label><input type="radio" name="payment_method" value="halopesa" onclick="showPhoneNumber('tanzania')"><img src="<?=site_url('assets/themes/payment_method/halo-pesa.png')?>"> Halo-pesa</label></li>
                                        <li><label><input type="radio" name="payment_method" value="ezypesa" onclick="showPhoneNumber('tanzania')"><img src="<?=site_url('assets/themes/payment_method/zantel.png')?>"> EzyPesa</label></li>
                                        <li><label><input type="radio" name="payment_method" value="ttclpesa" onclick="showPhoneNumber('tanzania')"><img src="<?=site_url('assets/themes/payment_method/t-pesa.jpg')?>"> TTCLPesa</label></li>
                                        <li><label><input type="radio" name="payment_method" value="safaricom" onclick="showPhoneNumber('kenya')"><img src="<?=site_url('assets/themes/payment_method/safaricom.png')?>">Safaricom Kenya</label></li>
                                        <li><label><input type="radio" name="payment_method" value="airtelmoney_kenya" onclick="showPhoneNumber('kenya')"><img src="<?=site_url('assets/themes/payment_method/airtelmoney.png')?>">Airtel Kenya</label></li>
                                        <li><label><input type="radio" name="payment_method" value="mtn_uganda" onclick="showPhoneNumber('uganda')"><img src="<?=site_url('assets/themes/payment_method/mtn1.jpg')?>"> MTN Uganda</label></li>
                                        <li><label><input type="radio" name="payment_method" value="mtn_rwanda" onclick="showPhoneNumber('rwanda')"><img src="<?=site_url('assets/themes/payment_method/mtn1.jpg')?>"> MTN Rwanda</label></li>
                                        <li><label><input type="radio" name="payment_method" value="mtn_ghana" onclick="showPhoneNumber('ghana')"><img src="<?=site_url('assets/themes/payment_method/mtn1.jpg')?>"> MTN Ghana</label></li>
                                        <li><label><input type="radio" name="payment_method" value="mtn_ivory" onclick="showPhoneNumber('Ivory Coast')"><img src="<?=site_url('assets/themes/payment_method/mtn1.jpg')?>"> MTN Ivory Cost</label></li>
                                        <li><label><input type="radio" name="payment_method" value="orange_ivory" onclick="showPhoneNumber('Ivory Coast')"><img src="<?=site_url('assets/themes/payment_method/orange_money.png')?>"> Orange Ivory Cost</label></li>
                                        <li><label><input type="radio" name="payment_method" value="amex" onclick="showBankField('amex')"><img src="<?=site_url('assets/themes/payment_method/amex.jpg')?>"> AMEX</label></li>
                                        <li><label><input type="radio" name="payment_method" value="mastercard" onclick="showBankField('mastercard')"><img src="<?=site_url('assets/themes/payment_method/mastercard.png')?>"> Master Card</label></li>
                                        <li><label><input type="radio" name="payment_method" value="visa" onclick="showBankField('visa')"><img src="<?=site_url('assets/themes/payment_method/visa.png')?>"> Visa Card</label></li>
                                    </ul>
                                </div>
                                <div class="form-group hiddenFld" id="phone_number">
                                    <label>Phone Number <sup>*</sup></label>
                                    <div class="input-group">
                                        <span class="input-group-addon" id="country_code">+255</span>
                                        <input type="hidden" name="country_code" id="country_code_value" class="form-control" />
                                        <input type="text" name="phone_number" class="form-control" placeholder="75xxxxxxx" value="<?php echo $phone; ?>" />
                                    </div>
                                </div>
                                <div class="row hiddenFld bank_payment">
                                    <div class="form-group col-md-12">
                                        <label>Phone Number <sup>*</sup></label>
                                        <input type="text" class="form-control" name="phone_number1" placeholder="eg. 25575xxyyyzzz" value="<?php echo $phone; ?>" />
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="addressInput">Street <sup>*</sup></label>
                                        <input type="text" name="address" value="<?php if(isset($user_info['address'])) echo $user_info['address']; ?>" class="form-control" placeholder="eg. 67 Street, Mahina">
                                    </div>
                                    <!--<div class="form-group col-sm-6">-->
                                    <!--    <label>Country <sup>*</sup></label>-->
                                    <!--    <input class="form-control" name="country_name" value="<?php if(isset($user_info['country'])) echo $user_info['country']; ?>" type="text" placeholder="eg. Kenya">-->
                                    <!--</div>-->
                                    <div class="form-group col-sm-6">
                                        <label>Country <sup>*</sup></label>
                                        <select class="form-control" name="country_name">
                                            <option value="">Select Country</option>
                                            <?php foreach($countries as $country) { ?>
                                                 <option value="<?php echo $country['code']; ?>"><?php echo $country['name'] .' - '. $country['code']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>City <sup>*</sup></label>
                                        <input class="form-control" name="city" value="<?php if(isset($user_info['city'])) echo $user_info['city']; ?>" type="text" placeholder="eg. Mwanza">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Post/ZIP Code <sup>*</sup></label>
                                        <input class="form-control" name="post_code" value="<?php if(isset($user_info['post_code'])) echo $user_info['post_code']; ?>" type="text" placeholder="eg. P. O. Box or PostCode">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><input type="checkbox" name="remember_me" checked> Remember me</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-info1 payment-panel">
                            <div class="panel-heading">
                                <h2 class="panel-title">Payment Information</h2>
                            </div>
                            <div class="panel-body">                            
                                <div class="discount">
                                    <label>Discount code</label>
                                    <div class="row">
                                        <div class="col-md-12">
                                            Do you have a coupon? If yes type it in the box below, VERIFY it then CONTINUE. If you don`t have Click COMPLETE ORDER...
                                            <input class="form-control" name="coupon" value="<?=$coupon_code?>" id="couponCode" placeholder="Enter code here" type="text">
                                        </div>
                                        <div class="col-md-4">
                                            <a href="javascript:void(0);" class="btn btn-default m-t-20 turnOnProg" onclick="validateCoupon();">Verify</a>
                                            <a href="javascript:void(0);" class="btn btn-default m-t-20 progressBarC"><i class="fa fa-spinner fa-spin"></i> Processing...</a>
                                        </div>
                                        <div class="col-md-12" id="resultMsgCoupon"></div>
                                    </div>
                                </div>
                                <div class="form-group m-t-10">
                                    <input type="hidden" name="orderID" id="orderID" value="<?php echo $orderID; ?>">
                                    <input type="hidden" name="referral" value="<?php echo $referral; ?>">
                                    <input type="hidden" name="product_url" value="<?php echo $productUrl; ?>">
                                    <input type="hidden" name="country" id="country" value="">
                                    <input type="hidden" name="currency" id="currency" value="">
                                    <input type="hidden" name="mno" id="mno" value="">
                                    <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>">
                                    <input type="hidden" name="product" value="<?php echo $product_tag; ?>">
                                    <input type="hidden" name="price" value="<?php echo $price; ?>">
                                    <table class="table price_tb">
                                        <tr><td><strong>Price:</strong> </td><td class="text-right"><b><?php echo number_format($total_amount); ?> Tsh.</b></td></tr>
                                        <tr><td><strong>Discount (#Coupon):</strong> </td><td class="text-right"><b><span id="coupon_amount">0</span> Tsh.</b></td></tr>
                                        <tr><td><strong>Total Price:</strong> </td><td class="text-right"><b><span id="total_amount"><?php echo number_format($total_amount); ?></span> Tsh.</b></td></tr>
                                    </table>
                                </div>
                                <div class="col-md-12" id="resultMsg"></div>
                                <a href="<?php echo base_url(); ?>" class="btn btn-primary go-shop pull-left">
                                    <span class="glyphicon glyphicon-circle-arrow-left"></span>
                                    Back to shop                    </a>
                                <a href="javascript:void(0);" class="btn btn-info pull-right turnOnProgress" class="pull-left" id="saveBtn">
                                    Complete order 
                                    <span class="fa fa-check"></span>
                                </a>
                                <a href="javascript:void();" class="btn btn-info pull-right progressBarBtn"><i class="fa fa-spinner fa-spin"></i> Processing...</a>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9 col-md-offset-2 clearfix">
                <h4>We Accept</h4><hr>
                <div class="weaccept_list">
                    <ul class="pull-left text-left">
                        <li><img src="<?=site_url('assets/themes/payment_method/mastercard.png')?>"></li>
                        <li><img src="<?=site_url('assets/themes/payment_method/visa.png')?>"></li>
                        <li><img src="<?=site_url('assets/themes/payment_method/amex.jpg')?>"></li>
                    </ul>
                    <ul class="pull-right">
                        <li><img src="<?=site_url('assets/themes/payment_method/mpesa.png')?>"></li>
                        <li><img src="<?=site_url('assets/themes/payment_method/airtelmoney.png')?>"></li>
                        <li><img src="<?=site_url('assets/themes/payment_method/tigopesa1.png')?>"></li>
                        <li><img src="<?=site_url('assets/themes/payment_method/halo-pesa.png')?>"></li>
                        <li><img src="<?=site_url('assets/themes/payment_method/zantel.png')?>"></li>
                        <li><img src="<?=site_url('assets/themes/payment_method/t-pesa.jpg')?>"></li>
                        <li><img src="<?=site_url('assets/themes/payment_method/safaricom.png')?>"></li>
                        <li><img src="<?=site_url('assets/themes/payment_method/orange_money.png')?>"></li>
                        <li><img src="<?=site_url('assets/themes/payment_method/mtn1.jpg')?>"></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- menu -->
    <div class="modal fade sm_modal" id="payment_menu" tabindex="-1" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Payment Instruction (Menu)</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body">
                    <div id="payment_instruction"></div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-default text-white waves-effect waves-light"  class="close" data-dismiss="modal">Okay</a>
                </div>
            </div>
        </div>
    </div>
    <!-- end -->
    
    <!-- PAYMENT SUCCESS MODAL -->
     <div class="modal fade" id="payment_success_modal" style="background-color: rgb(0, 0, 0); background-color: rgba(0, 0, 0, 0.4);">
        <div class="modal-content" id="paymentModalContent" style="background-color: #fefefe; margin: auto; box-shadow: 0px 10px 29px 0px #e0e0e0; border-radius: 20px;">
            <button type="button" class="closeModal" id="closeModalId" data-dismiss="modal"  aria-label="Close" style="color: #aaaaaa; float: right; font-size: 28px; font-weight: bold; visibility: hidden;">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="logo" style="display: grid; align-items: center;">
                <img src='https://www.getvalue.co/assets/themes/logo.png' id="modalLogo"/>
               <!--<p id="mainLogo" style="text-transform: uppercase; margin-top: 15px; text-align: center;  font-weight: 600; color: #d6bd70; font-family: beaver;">GETVALUE</p>-->
            </div>
            <br/>
            <br/>
            <br/>
            <div class="">
                <h5 id="paymentSuccessText" style="color: green; font-style: italic; text-align: center;">Payment successful</h5>
            </div>
            <br/>
            <div id="confirmationText" style="text-align: center;">
                <p>Thanks For Buying From Us!</p>
                <div>
                  <p>
                    GETVALUE Has Created An Account For You, to Access your
                    Purchased Products Download our App basing on your smartphone
                    platform by Clicking On App's Icon Below! once downloaded you
                    will login by using
                  </p>
                </div>
                <div>
                  <p>Username: <?php echo $email_address; ?></p>
                </div>
                <div>
                  <p>Password:123456</p>
                </div>
            </div>
            <div class="appBtns" style="display: flex; justify-content: center; align-items: center;">
               <a href="https://play.google.com/store/apps/details?id=com.getvalue.getvalue"><img class="appLogo1" src="https://www.getvalue.co/assets/themes/icons/playstore_btn.png"></a> 
               <a href="https://apps.apple.com/tz/app/getvalue/id1644488065"><img class="appLogo2" src="https://www.getvalue.co/assets/themes/icons/appstore_btn.png"></a> 
            </div>
        </div>
    </div>
    <!-- PAYMENT SUCCESS MODAL -->
    
</body>
</html>
<script type="text/javascript">
    <?php
        echo "var total_amount=".$total_amount.";";
    ?>
    $(document).ready(function(){
        validateCoupon();
        var loadF="";
        check_order_status();
        <?php
            if($orderID!=""){
                // echo 'loadF = setInterval(function(){ check_order_status(); }, 3000);';
            }
        ?>
       
        // When the user clicks anywhere outside of the modal, close it

        $('#saveBtn').click(function(){
           $('.turnOnProgress').css('display','none');
           $('.progressBarBtn').css('display','inline-block');
           $.ajax({
                url: '<?php echo base_url(); ?>home/checkout_order',
                type: 'POST',
                data:$('#data-form').serialize(),
                async: true,
                processData: false,
                success: function (data) {
                    if (data.indexOf("@#$") > -1){
                        var res1=data.trim();
                        var res=res1.split("@#$");
                        console.log(res);
                        if(res[0]=='Success'){
                            // setInterval(function(){ check_order_status(); }, 10000);
                            check_order_status();
                            var output = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> Success, Moving to payment page... </div>'; 
                            $('#resultMsg').html(output);
                            window.location=res[2];
                        }else if(res[0]=='callback'){
                            var output = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> '+res[1]+', wait... </div>'; 
                            $('#resultMsg').html(output);
                            // $('#saveBtn').html('Resend order <span class="fa fa-check"></span>');
                            document.getElementById('orderID').value=res[2];
                            loadF = setTimeout(function(){ location.reload(); }, 20000);//10 Secs
                            // loadF = setInterval(function(){ check_order_status(); }, 10000);
                            // check_order_status();
                        }else if(res[0]=='instruction'){
                            var output = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> Follow the instruction to complete your payment, click resend btn to get payment instruction, waiting... </div>'; 
                            $('#resultMsg').html(output);
                            $('#saveBtn').html('Resend order <span class="fa fa-check"></span>');
                            document.getElementById('orderID').value=res[2];
                            loadF = setInterval(function(){ check_order_status(); }, 10000);//10 Secs
                            $('#payment_instruction').html(res[1]);
                            $('#payment_menu').modal('show');
                        }else if(res[1]=='Success'){
                            var output = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> '+res[1]+', wait we are confirming payments... </div>'; 
                            $('#resultMsg').html(output);
                            document.getElementById('orderID').value=res[2];
                           
                            //setInterval(function(){ check_order_status(); }, 5000);//3 Secs
                            check_order_status();

                        }else if(res[0]=='error'){
                            var output = '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> '+res[1]+'</div>'; 
                            $('#resultMsg').html(output);
                        }else{
                            $('#resultMsg').html(res[1]);
                        }
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
        });

    });

    function check_payment_method(){
        var method=document.getElementById('payment_type').value;
        if(method=='Bank'){
            $('.bankPay').css('display','inline-block');
        }else{
            $('.bankPay').css('display','none');
        }
    }

    function validateCoupon(){
        var coupon=document.getElementById('couponCode').value;
        if(coupon!=""){
            ShowNotificator('alert-info', "Verifying coupon...");
            $('.progressBarC').css('display','inline-block');
            $('.turnOnProg').css('display','none');
            $.ajax({
                url:'<?= base_url() ?>home/verify_coupon/'+coupon,
                type:"POST",
                success: function(data){
                    if(!isNaN(data.trim())){
                        var cAmount=data.trim();
                        ShowNotificator('alert-success', coupon+" Is valid");
                        $('#resultMsgCoupon').html('<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> Is valid, its value is '+cAmount+' tsh </div>');
                        $('#coupon_amount').html(cAmount);
                        $('#total_amount').html(total_amount-cAmount);
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

    function check_order_status(){
        var order_id=document.getElementById('orderID').value;
        if(order_id!=""){
            ShowNotificator('alert-info', "Checking payment status...");
            $.ajax({
                url:'<?= base_url() ?>home/check_order_status?orderID='+order_id,
                type:"POST",
                success: function(data){
                    if(data.trim()!=""){
                        if(data.trim()=="Success"){
                            // ShowNotificator('alert-success', "Payment Received");
                            // var output = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> Thank you for using GetValue, payment received successfully. </div>';
                            // $('#resultMsg').html(output);
                            // // clearInterval(loadF);
                           
                            $('#payment_success_modal').modal('show');
                            
                            setInterval(function () {
                               $('#payment_success_modal').modal('hide');
                            //   window.location.reload();
                             // window.location='<?=base_url() ?>customer/payment_success?orderID='+order_id;
                             window.location='<?=base_url() ?>customer/index/';
                            },60000);
                                                    
                        }else{
                            ShowNotificator('alert-danger',data);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    ShowNotificator('alert-danger', error);
                    return false;
                }
            });
        }
    }

    function showPhoneNumber(country){
        var currency="";
        currency="TZS";
        if(country=='tanzania'){
            $('#country_code').html('+255');
            document.getElementById('country_code_value').value="+255";
            // currency="TZS";
        }else if(country=='kenya'){
            $('#country_code').html('+254');
            document.getElementById('country_code_value').value="+254";
            // currency="KES";
        }else if(country=='uganda'){
            $('#country_code').html('+256');
            document.getElementById('country_code_value').value="+256";
            // currency="UGX";
        }else if(country=='rwanda'){
            $('#country_code').html('+250');
            document.getElementById('country_code_value').value="+250";
            // currency="RWF";
        }else if(country=='ghana'){
            $('#country_code').html('+233');
            document.getElementById('country_code_value').value="+233";
            // currency="GHS";
        }else if(country=='Ivory Coast'){
            $('#country_code').html('+225');
            document.getElementById('country_code_value').value="+225";
            // currency="XOF";
        }
        document.getElementById('country').value=country;
        document.getElementById('currency').value=currency;
        $('.bank_payment').css('display','none');
        $('#phone_number').css('display','block');
    }

    function showBankField(){
        $('#phone_number').css('display','none');
        $('.bank_payment').css('display','block');
        var currency="USD";
        document.getElementById('currency').value=currency;
    }
    
    var modal = document.getElementById("payment_success_modal");
    var btn = document.getElementById("closeModalId");
    var span = document.getElementsByClassName("closeModal")[0];

    btn.onclick = function () {
      modal.style.display = "block";
    };

    span.onclick = function () {
      modal.style.display = "none";
    };

    window.onclick = function (event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    };
</script>