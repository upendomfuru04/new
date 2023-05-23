<!DOCTYPE html>
<html>
<head>
	
</head>
<body>
                        <br >
                        <br >
					</div>
                </div>
            </div>
        </div>
        <!--<footer>SafariBomba<a href="http://www.safaribomba.com"> Official Partner </a></footer>-->
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
    <!-- end of modals -->

	<!-- Modal Calculator -->
	<div class="modal fade" id="modalCalculator" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	    <div class="modal-dialog" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 class="modal-title" id="myModalLabel">Calculator</h4>
	            </div>
	            <div class="modal-body" id="calculator">
	                <div class="hero-unit" id="calculator-wrapper">
	                    <div class="row">
	                        <div class="col-sm-8">
	                            <div id="calculator-screen" class="form-control"></div>
	                        </div>
	                        <div class="col-sm-1">
	                            <div class="visible-xs">
	                                =
	                            </div>
	                            <div class="hidden-xs">
	                                =
	                            </div>
	                        </div>
	                        <div class="col-sm-3">
	                            <div id="calculator-result"  class="form-control">0</div>
	                        </div>
	                    </div>
	                </div>
	                <div class="well">
	                    <div id="calc-board">
	                        <div class="row">
	                            <a href="javascript:void(0);" class="btn btn-default" data-constant="SIN" data-key="115">sin</a>
	                            <a href="javascript:void(0);" class="btn btn-default" data-constant="COS" data-key="99">cos</a>
	                            <a href="javascript:void(0);" class="btn btn-default" data-constant="MOD" data-key="109">md</a>
	                            <a href="javascript:void(0);" class="btn btn-danger" data-method="reset" data-key="8">C</a>
	                        </div>
	                        <div class="row">
	                            <a href="javascript:void(0);" class="btn btn-default" data-key="55">7</a>
	                            <a href="javascript:void(0);" class="btn btn-default" data-key="56">8</a>
	                            <a href="javascript:void(0);" class="btn btn-default" data-key="57">9</a>
	                            <a href="javascript:void(0);" class="btn btn-default" data-constant="BRO" data-key="40">(</a>
	                            <a href="javascript:void(0);" class="btn btn-default" data-constant="BRC" data-key="41">)</a>
	                        </div>
	                        <div class="row">
	                            <a href="javascript:void(0);" class="btn btn-default" data-key="52">4</a>
	                            <a href="javascript:void(0);" class="btn btn-default" data-key="53">5</a>
	                            <a href="javascript:void(0);" class="btn btn-default" data-key="54">6</a>
	                            <a href="javascript:void(0);" class="btn btn-default" data-constant="MIN" data-key="45">-</a>
	                            <a href="javascript:void(0);" class="btn btn-default" data-constant="SUM" data-key="43">+</a>
	                        </div>
	                        <div class="row">
	                            <a href="javascript:void(0);" class="btn btn-default" data-key="49">1</a>
	                            <a href="javascript:void(0);" class="btn btn-default" data-key="50">2</a>
	                            <a href="javascript:void(0);" class="btn btn-default" data-key="51">3</a>
	                            <a href="javascript:void(0);" class="btn btn-default" data-constant="DIV" data-key="47">/</a>
	                            <a href="javascript:void(0);" class="btn btn-default" data-constant="MULT" data-key="42">*</a>
	                        </div>
	                        <div class="row">
	                            <a href="javascript:void(0);" class="btn btn-default" data-key="46">.</a>
	                            <a href="javascript:void(0);" class="btn btn-default" data-key="48">0</a>
	                            <a href="javascript:void(0);" class="btn btn-default" data-constant="PROC" data-key="37">%</a>
	                            <a href="javascript:void(0);" class="btn btn-primary" data-method="calculate" data-key="61">=</a>
	                        </div>
	                    </div>
	                </div>
	                <div class="well">
	                    <legend>History</legend>
	                    <div id="calc-panel">
	                        <div id="calc-history">
	                            <ol id="calc-history-list"></ol>
	                        </div>
	                    </div>
	                </div>
	                <hr>
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	            </div>
	        </div>
	    </div>
	</div>

    
	<!-- <script src="http://www.getvalueinc.com/assets/bootstrap-select-1.12.1/js/bootstrap-select.min.js"></script> -->
	<!-- <script src="http://www.getvalueinc.com/assets/js/bootstrap.min.js"></script> -->
	<!-- <script src="<?php //echo base_url();?>assets/js/bootbox.min.js"></script>
	<script src="<?php //echo base_url();?>assets/js/zxcvbn.js"></script>
	<script src="<?php //echo base_url();?>assets/js/zxcvbn_bootstrap3.js"></script> -->
	<!-- <script src="<?php //echo base_url();?>assets/js/pGenerator.jquery.js"></script> -->

	<!-- <script src="http://www.getvalueinc.com/assets/highcharts/highcharts.js"></script> -->
	<!-- <script src="http://www.getvalueinc.com/assets/highcharts/data.js"></script> -->
	<!-- <script src="http://www.getvalueinc.com/assets/highcharts/drilldown.js"></script> -->
	<!-- <script src="<?php //echo base_url();?>assets/js/mine_admin.js"></script> -->


    <script src="<?php echo base_url();?>assets/js/mine.js"></script>
    <script src="<?php echo base_url();?>assets/js/mine_admin.js"></script>
	
</body>
</html>
<script>
    $(document).ready(function(){
    	check_new_order();
    	check_new_refund();
        check_new_message();
        check_new_affiliate();
        <?php if(in_array('insider', $this->session->userdata('account_type_list')) || in_array('outsider', $this->session->userdata('account_type_list')) || in_array('contributor', $this->session->userdata('account_type_list'))){ ?>
            check_new_affiliate_refund();
            check_new_affiliate_order();
    	    var loadR = setInterval(function(){ check_new_affiliate_refund(); check_new_affiliate_order(); }, 60000);//60 Secs
        <?php } ?>
        var loadF = setInterval(function(){ check_new_order(); check_new_refund(); check_new_affiliate_refund(); check_new_message(); check_new_affiliate(); }, 60000);//60 Secs

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

    });

    function approve_order(order){
    	if (confirm("Are you sure you want to approve the order with ID "+order+"?")) {
    		$('.turnOnProgress'+order).css('display','none');
           	$('.progressBarBtn'+order).css('display','inline-block');
            $.ajax({
                url:'<?= base_url() ?>seller/order/approve_customer_order/'+order,
                type:"POST",
                success: function(data){
                    if(data.trim()=='Success'){
                        alert("Order approved successfully...");
                        window.location="";
                    }else if(data.trim()=='login'){
                        window.location="<?php echo base_url().'login'; ?>";
                    }else{
                        alert(data);
                    }
                    $('.turnOnProgress'+order).css('display','inline-block');
                    $('.progressBarBtn'+order).css('display','none');
                },
                error: function(xhr, status, error) {
                    alert(error);
                    $('.turnOnProgress'+order).css('display','inline-block');
                    $('.progressBarBtn'+order).css('display','none');
                    return false;
                }
            });
        }
    }

    function approve_refund(refund, order){
    	if (confirm("Are you sure you want to approve the refund with order ID "+order+"?")) {
    		$('.turnOnProgress'+refund).css('display','none');
           	$('.progressBarBtn'+refund).css('display','inline-block');
            $.ajax({
                url:'<?= base_url() ?>seller/sale/approve_customer_refund/'+refund,
                type:"POST",
                success: function(data){
                    if(data.trim()=='Success'){
                        alert("Refund approved successfully...");
                        window.location="";
                    }else if(data.trim()=='forceRefund'){
                        force_approve_refund(refund, order);
                    }else if(data.trim()=='login'){
                        window.location="<?php echo base_url().'login'; ?>";
                    }else{
                        alert(data);
                    }
                    $('.turnOnProgress'+refund).css('display','inline-block');
                    $('.progressBarBtn'+refund).css('display','none');
                },
                error: function(xhr, status, error) {
                    alert(error);
                    $('.turnOnProgress'+refund).css('display','inline-block');
                    $('.progressBarBtn'+refund).css('display','none');
                    return false;
                }
            });
        }
    }

    function force_approve_refund(refund, order){
        if (confirm("The order with ID "+order+" seem to be paid to another provider rather than Selcom, use different method to pay the refund then force the refund!")) {
            $('.turnOnProgress'+refund).css('display','none');
            $('.progressBarBtn'+refund).css('display','inline-block');
            $.ajax({
                url:'<?= base_url() ?>seller/sale/approve_customer_refund/'+refund,
                type:"POST",
                success: function(data){
                    if(data.trim()=='Success'){
                        alert("Refund approved successfully...");
                        window.location="";
                    }else if(data.trim()=='login'){
                        window.location="<?php echo base_url().'login'; ?>";
                    }else{
                        alert(data);
                    }
                    $('.turnOnProgress'+refund).css('display','inline-block');
                    $('.progressBarBtn'+refund).css('display','none');
                },
                error: function(xhr, status, error) {
                    alert(error);
                    $('.turnOnProgress'+refund).css('display','inline-block');
                    $('.progressBarBtn'+refund).css('display','none');
                    return false;
                }
            });
        }
    }

    function check_new_order(){
        $.ajax({
            url:'<?= base_url() ?>seller/order/check_new_order',
            type:"POST",
            success: function(data){
                if(!isNaN(data.trim())){
                    $('#orderCounter').html(data.trim());
                }else{
                    // alert("Refresh page");
                    window.location="";
                }
            },
            error: function(xhr, status, error) {
                return false;
            }
        });
    }

    function check_new_affiliate_order(){
        $.ajax({
            url:'<?= base_url() ?>seller/order/check_new_affiliate_order',
            type:"POST",
            success: function(data){
                if(!isNaN(data.trim())){
                    // $('#affiliateOrderCounter').html(data.trim());
                }else{
                    // alert("Refresh page");
                    window.location="";
                }
            },
            error: function(xhr, status, error) {
                return false;
            }
        });
    }

    function check_new_refund(){
        $.ajax({
            url:'<?= base_url() ?>seller/sale/check_new_refund',
            type:"POST",
            success: function(data){
                if(!isNaN(data.trim())){
                    $('#refundCounter').html(data.trim());
                }else{
                    // alert("Refresh page");
                    window.location="";
                }
            },
            error: function(xhr, status, error) {
                return false;
            }
        });
    }

    function check_new_message(){
        $.ajax({
            url:'<?= base_url() ?>seller/home/check_new_message',
            type:"POST",
            success: function(data){
                if(!isNaN(data.trim())){
                    $('#messageCounter').html(data.trim());
                }else{
                    // alert("Refresh page");
                    window.location="";
                }
            },
            error: function(xhr, status, error) {
                return false;
            }
        });
    }

    function check_new_affiliate(){
        $.ajax({
            url:'<?= base_url() ?>seller/home/check_new_affiliate',
            type:"POST",
            success: function(data){
                if(!isNaN(data.trim())){
                    $('#affiliateMarketCounter').html(data.trim());
                }else{
                    // alert("Refresh page");
                    window.location="";
                }
            },
            error: function(xhr, status, error) {
                return false;
            }
        });
    }

    function check_new_affiliate_refund(){
        $.ajax({
            url:'<?= base_url() ?>seller/sale/check_new_affiliate_refund',
            type:"POST",
            success: function(data){
                if(!isNaN(data.trim())){
                    $('#affiliateRefundCounter').html(data.trim());
                }else{
                    // alert("Refresh page");
                    window.location="";
                }
            },
            error: function(xhr, status, error) {
                return false;
            }
        });
    }
</script>