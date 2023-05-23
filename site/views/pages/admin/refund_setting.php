<?php

?>
<!DOCTYPE html>
<html>
<head>
    
</head>
<body>
	<h1 class="breadcumb"></h1>
    <div class="home-page">
        <div class="row p-t-20">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"> Update Refund Period</h3>
                    </div>
                    <div class="panel-body p-40 row">
                        <form id="data-form" method="POST">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label><input type="radio" name="period" value="7" <?php if(!empty($table_rows['period']) && $table_rows['period']=='7'){ echo 'checked'; } ?>> 7 Days </label>
                                </div>
                                <div class="form-group col-md-3">
                                    <label><input type="radio" name="period" value="30" <?php if(!empty($table_rows['period']) && $table_rows['period']=='30'){ echo 'checked'; } ?>> 30 Days </label>
                                </div>
                                <div class="form-group col-md-3">
                                    <label><input type="radio" name="period" value="60" <?php if(!empty($table_rows['period']) && $table_rows['period']=='60'){ echo 'checked'; } ?>> 60 Days </label>
                                </div>
                                <div class="form-group col-md-3">
                                    <label><input type="radio" name="period" value="90" <?php if(!empty($table_rows['period']) && $table_rows['period']=='90'){ echo 'checked'; } ?>> 90 Days </label>
                                </div>
                                <div class="col-md-12" id="resultMsg"></div>
                                <div class="col-md-12 p-t-10">
                                    <a href="javascript:void();" class="btn btn-success mr-2 turnOnProgress" id="saveBtn">Save</a>
                                    <a href="javascript:void();" class="btn btn-success mr-2 progressBarBtn"><i class="fa fa-spinner fa-spin"></i> Processing...</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<script type="text/javascript">
    $(document).ready(function(){

        $('#saveBtn').click(function(){
            $('.turnOnProgress').css('display','none');
            $('.progressBarBtn').css('display','inline-block');
            $.ajax({
                url: '<?php echo base_url(); ?>admin/sale/save_refund_period',
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
</script>