<!DOCTYPE html>
<html>
<head>
    
</head>
<body>
	<h1 class="breadcumb"></h1>
    <div class="home-page">
        <div class="row p-t-20">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="ti-list fa-fw"></i> Create Affiliate Url (Vendor)</h3>
                    </div>
                    <div class="panel-body p40">
                        <div id="container-by-month" style="min-width: 310px; height: 400px; margin: 0 auto;">
                            <form id="data-form" method="POST">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label>Referral URL:</label>
                                        <textarea class="form-control" name="referral_url" readonly="" id="referral_url"><?php if($vendor_url!=""){ echo base_url()."register/".$vendor_url; } ?></textarea>
                                    </div>
                                    <div class="col-md-12"><?php if($vendor_url!=""){ echo '<p><small>Highlight the link and copy to use it.</small></p>'; } ?></div>
                                    <div class="col-md-12" id="resultMsg"></div>
                                    <div class="col-md-12 p-t-10">
                                        <?php if($vendor_url!=""){ ?>
                                        <a href="javascript:void();" class="btn btn-default w100 mr-2">Generate Link</a>
                                        <?php }else{ ?>
                                        <a href="javascript:void();" class="btn btn-primary w100 mr-2 turnOnProgress" id="saveBtn">Generate Link</a>
                                        <?php } ?>
                                        <a href="javascript:void();" class="btn btn-primary w100 mr-2 progressBarBtn"><i class="fa fa-spinner fa-spin"></i> Processing...</a>
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

        $('#saveBtn').click(function(){
           $('.turnOnProgress').css('display','none');
           $('.progressBarBtn').css('display','inline-block');
           $.ajax({
                url: '<?php echo base_url(); ?>seller/product/save_vendor_url',
                type: 'POST',
                data:$('#data-form').serialize(),
                async: true,
                processData: false,
                success: function (data) {
                    var varObj = JSON.parse(data);
                    if(varObj.error){
                        $('#resultMsg').html(varObj.res);
                    }else{
                        $('#referral_url').html(varObj.res);
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