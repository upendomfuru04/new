<?php
    /*if(isset($order_info['orderID'])){
        $orderID=$order_info['orderID'];
        checkOrderPaymentStatus($orderID, $userID);
    }*/
    if(empty($order_info['total_amount']) || $order_info['total_amount']==""){
        header("Location: ".base_url());
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
    </style>
</head>
<body>
	<div class="panel panel-info">
		<div class="panel-heading">
			<h2 class="panel-title">Checkout - Bill Information</h2>
		</div>
		<div class="panel-body">
			<form method="POST" id="data-form">
	            <div class="row p-t-20">
                    <div class="form-group col-sm-4">
                        <label>Order ID <sup>*</sup></label>
                        <input class="form-control" name="orderID" value="<?php echo $order_info['orderID']; ?>" type="text" readonly>
                    </div>
                    <div class="form-group col-sm-4">
                        <label>First Name <sup>*</sup></label>
                        <input class="form-control" name="first_name" value="<?php echo $user_info['first_name']; ?>" type="text" placeholder="">
                    </div>
                    <div class="form-group col-sm-4">
                        <label>Surname <sup>*</sup></label>
                        <input class="form-control" name="surname" value="<?php echo $user_info['surname']; ?>" type="text" placeholder="">
                    </div>
                    <div class="form-group col-sm-4">
                        <label>Email address <sup>*</sup></label>
                        <input class="form-control" name="email" value="<?php echo $user_info['email']; ?>" type="text" placeholder="" readonly>
                    </div>
                    <div class="form-group col-sm-4">
                        <label>Phone <sup>*</sup></label>
                        <input class="form-control" name="phone" value="<?php echo $user_info['phone']; ?>" type="text" placeholder="" >
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="addressInput">Address</label>
                        <input type="text" name="address" value="<?php echo $user_info['address']; ?>" class="form-control">
                    </div>
                    <div class="form-group col-sm-4">
                        <label>Country <sup>*</sup></label>
                        <input class="form-control" name="country" value="<?php echo $user_info['country']; ?>" type="text" placeholder="">
                    </div>
                    <div class="form-group col-sm-4">
                        <label>City <sup>*</sup></label>
                        <input class="form-control" name="city" value="<?php echo $user_info['city']; ?>" type="text">
                    </div>
                    <div class="form-group col-sm-4">
                        <label>Post code</label>
                        <input class="form-control" name="post_code" value="<?php echo $user_info['post_code']; ?>" type="text" placeholder="">
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="notesInput">Remarks</label>
                        <textarea id="notesInput" class="form-control" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="payment-type-box">
                    <div class="row">
                        <div class="col-md-4">
                            <select class="selectpicker payment-type" data-style="btn-primary" name="payment_type" onchange="check_payment_method();" id="payment_type">
                                <option value="directpay">Direct Pay Online (DPO)</option>
                                <!-- <option value="dogodogo">Dogo Dogo Pay </option> -->
                                <!-- <option value="cashOnDelivery">Cash on Delivery </option> -->
                                <!-- <option value="Bank">Bank payment </option> -->
                            </select>
                        </div>
                        <div class="col-md-4 bankPay">
                            <input type="text" class="form-control" name="account_name" placeholder="Account Name">
                        </div>
                        <div class="col-md-4 bankPay">
                            <input type="text" class="form-control" name="account_number" placeholder="Account Number">
                        </div>
                    </div>
                    <span class="top-header text-center">Choose payment type</span>
                </div>
                <div class="discount">
                    <label>Discount code</label>
                    <div class="row">
	                	<div class="col-md-4">
                            <small>If you have a coupon, type it in the box.</small>
	                		<input class="form-control" name="coupon" value="" id="couponCode" placeholder="Enter code here" type="text">
	                	</div>
                        <div class="col-md-4">
                            <a href="javascript:void(0);" class="btn btn-default m-t-20 turnOnProg" onclick="validateCoupon();">Verify</a>
                            <a href="javascript:void(0);" class="btn btn-default m-t-20 progressBarC"><i class="fa fa-spinner fa-spin"></i> Processing...</a>
                        </div>
                        <div class="col-md-12" id="resultMsgCoupon"></div>
	                </div>
                </div>
                <hr >
                <div class="row">
                	<div class="col-md-12 text-right">
                        <input type="hidden" name="total_amount" value="<?php echo $order_info['total_amount']; ?>">
                		<p><strong>Price: </strong><?php echo number_format($order_info['total_amount']); ?> Tsh.</p>
                		<p><strong>Discount (#Coupon): </strong><span id="coupon_amount">0</span> Tsh.</p>
                		<p><strong>Total Price: </strong><span id="total_amount"><?php echo number_format($order_info['total_amount']); ?></span> Tsh.</p>
                	</div>
                </div>
                <hr >
                <div>
                    <div class="col-md-12" id="resultMsg"></div>
                    <a href="<?php echo base_url(); ?>" class="btn btn-primary go-shop pull-left">
                        <span class="glyphicon glyphicon-circle-arrow-left"></span>
                        Back to shop                    </a>
                    <a href="javascript:void(0);" class="btn btn-primary pull-right turnOnProgress" class="pull-left" id="saveBtn">
                        Complete order 
                        <span class="glyphicon glyphicon-circle-arrow-right"></span>
                    </a>
                    <a href="javascript:void();" class="btn btn-primary pull-right progressBarBtn"><i class="fa fa-spinner fa-spin"></i> Processing...</a>
                    <div class="clearfix"></div>
                </div>
	        </form>
		</div>
	</div>
</body>
</html>
<script type="text/javascript">
    <?php
        echo "var total_amount=".$order_info['total_amount'].";";
    ?>
    $(document).ready(function(){

        $('#saveBtn').click(function(){
           $('.turnOnProgress').css('display','none');
           $('.progressBarBtn').css('display','inline-block');
           $.ajax({
                url: '<?php echo base_url(); ?>Customer/save_order',
                type: 'POST',
                data:$('#data-form').serialize(),
                async: true,
                processData: false,
                success: function (data) {
                    if(data.trim()=='Success'){
                        var output = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> Success, Moving to payment page... </div>'; 
                        $('#resultMsg').html(output);
                        document.getElementById("data-form").reset();
                        window.location="<?=base_url()?>customer/complete_payment";
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
                url:'<?= base_url() ?>Customer/verify_coupon/'+coupon,
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
</script>