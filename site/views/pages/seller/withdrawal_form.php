<?php
    $seller_type="";
    if($update!='' && sizeof($w_request)>0){ 
        $seller_type=$w_request['seller_type'];
    }else{
        $update="";
    }
?>
<!DOCTYPE html>
<html>
<head>
    
</head>
<body>
	<h1 class="breadcumb"></h1>
    <div class="home-page">
        <div class="row p-t-20">
            <div class="col-md-5 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="ti-list fa-fw"></i> Withdrawal Form</h3>
                    </div>
                    <div class="panel-body p40">
                        <div id="container-by-month" style="min-width: 310px; height: 400px; margin: 0 auto;">
                            <form id="data-form" method="POST">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label>Affiliate Type (Optional):</label>
                                        <select class="form-control" name="seller_type">
                                            <option value="">General/Select Account Type</option>
                                            <?php loadAffiliateTypes($userID, $seller_type);?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Total Balance (Tsh.):</label>
                                        <input type="text" name="total_balance" value="<?php echo number_format($total_balance['credit']-($total_balance['debit']+$total_balance['charge']));?>" class="form-control" readonly="">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Request Amount (Tsh.):</label>
                                        <input type="text" id="totalAmount" value="<?php if($update!=''){ echo $w_request['amount']+1534;}?>" class="form-control" onkeyup="setAmount();">
                                        <input type="hidden" name="amount" id="amount" value="<?php if($update!=''){ echo $w_request['amount'];}?>" class="form-control">
                                        <input type="hidden" name="requestID" value="<?php if($update!=''){ echo $w_request['requestID'];}?>" class="form-control">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <p>Total Requested Amount: <b id="total_amount">0</b> Tsh.</p>
                                        <p>Transaction Fee: <b>1534</b> Tsh.</p>
                                        <p>Net Amount: <b id="net_amount">0</b> Tsh.</p>
                                    </div>
                                    <div class="col-md-12" id="resultMsg"></div>
                                    <div class="col-md-12 p-t-10">
                                        <?php if($update!=""){?>
                                        <a href="javascript:void();" class="btn btn-info w100 mr-2 turnOnProgress" id="updateBtn">Update Request</a>
                                        <?php }else{?>
                                        <a href="javascript:void();" class="btn btn-info w100 mr-2 turnOnProgress" id="saveBtn">Send Request</a>
                                        <?php } ?>
                                        <a href="javascript:void();" class="btn btn-info w100 mr-2 progressBarBtn"><i class="fa fa-spinner fa-spin"></i> Processing...</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<script type="text/javascript">
    $(document).ready(function(){

        setAmount();

        $('#saveBtn').click(function(){
           $('.turnOnProgress').css('display','none');
           $('.progressBarBtn').css('display','inline-block');
           $.ajax({
                url: '<?php echo base_url(); ?>seller/commission/save_withdrawal_form',
                type: 'POST',
                data:$('#data-form').serialize(),
                async: true,
                processData: false,
                success: function (data) {
                    if(data.trim()=='Success'){
                        var output = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> Success </div>'; 
                        $('#resultMsg').html(output);
                        document.getElementById("data-form").reset();
                        window.location="";
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

        $('#updateBtn').click(function(){
           $('.turnOnProgress').css('display','none');
           $('.progressBarBtn').css('display','inline-block');
           $.ajax({
                url: '<?php echo base_url(); ?>seller/commission/update_withdrawal_form',
                type: 'POST',
                data:$('#data-form').serialize(),
                async: true,
                processData: false,
                success: function (data) {
                    if(data.trim()=='Success'){
                        var output = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> Success </div>'; 
                        $('#resultMsg').html(output);
                        document.getElementById("data-form").reset();
                        window.location="";
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

    function setAmount(){
        var totalAmount=document.getElementById('totalAmount').value;
        if(totalAmount!=""){
            $('#total_amount').html(totalAmount);
            $('#net_amount').html(totalAmount-1534);
            document.getElementById('amount').value=totalAmount-1534;
        }
    }
</script>