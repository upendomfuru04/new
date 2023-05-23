<!DOCTYPE html>
<html>
<head>
    
</head>
<body>
	<h1 class="breadcumb"></h1>
    <div class="home-page">
        <div class="row p-t-20">
            <div class="col-md-4 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="ti-key fa-fw"></i> Reset Passoword</h3>
                    </div>
                    <div class="panel-body p40">
                        <div id="container-by-month" style="min-width: 310px; margin: 0 auto;">
                            <p>Please! reset your password to secure your account.</p>
                            <form method="post" id="changepassword-form">
                                <div class="row">                                    
                                    <div class="form-group col-md-12">
                                        <input type="password" name="cpassword" class="form-control" placeholder="Current Password"> 
                                    </div>
                                    <div class="form-group col-md-12">
                                        <input type="password" name="password" class="form-control" placeholder="New Password"> 
                                    </div>
                                    <div class="form-group col-md-12">
                                        <input type="password" name="repassword" class="form-control" id="passwordChangeField" placeholder="Re-Enter New Password">
                                    </div>
                                    <div class="col-md-12" id="resultMsgChangePass"></div>
                                    <div class="col-md-12">
                                        <a class="btn btn-info btn-block text-white waves-effect waves-light turnOnChangePassProgress" id="resetPassword">Reset Password</a>
                                        <a class="btn btn-info btn-block text-white waves-light progressBarChangePassBtn"><i class="fa fa-spinner fa-spin"></i> Processing...</a>
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

        $('#resetPassword').click(function(){
            $('.turnOnProgress').css('display','none');
            $('.progressBarBtn').css('display','inline-block');
            $.ajax({
                url:'<?= base_url() ?>Users/change_password',
                type:"POST",
                data:$('#changepassword-form').serialize(),
                success: function(data){
                    if(data.trim()=='Success'){
                        var output = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> Success </div>'; 
                        $('#resultMsgChangePass').html(output);
                        document.getElementById("changepassword-form").reset();
                        window.location='<?=base_url().'seller/'.$this->account_type?>';
                    }else{
                        $('#resultMsgChangePass').html(data);
                    }
                    $('.turnOnProgress').css('display','inline-block');
                    $('.progressBarBtn').css('display','none');
                }
            });
        });
        
        $("#passwordChangeField").keyup(function(event){
            if(event.keyCode == 13){
                var mValue=document.getElementById("passwordChangeField").value;
                if(mValue!=""){
                    $("#resetPassword").click();
                }
            }
        });

  });
</script>