<!DOCTYPE html>
<?php
    if(sizeof($table_rows)==0){
        redirect_back();
    }
?>
<html>
<head>
    
    <style type="text/css">
        
    </style>
</head>
<body>
    <h1 class="breadcumb"></h1>
    <div class="home-page">
        <div class="row p-t-20">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="ti-list fa-fw"></i> Message Details</h3>
                    </div>
                    <div class="panel-body row p-40">
                        <div class="col-md-12">
                            <h4><?php echo ucwords($table_rows['subject']); ?></h4>
                            <p>Sent Date: <?php echo date('d M, Y', $table_rows['createdDate']); ?></p>
                            <hr >
                            <p class="text-justify"><?php echo preg_replace('#(<br */?>\s*)+#i', '<br />', $table_rows['message']); ?></p>
                            <?php echo '<a href="javascript:void();" onclick="displayReply(\''.$table_rows['subject'].'\', \''.$table_rows['id'].'\', \''.$table_rows['createdBy'].'\');"><i class="fa fa-reply"></i></a>'; ?>
                            <?php getMsgReplies($table_rows['id'], $userID); ?>
                            <hr >
                        </div>
                        <?php //if($table_rows['createdBy']==$userID){ ?>
                        <div class="col-md-12 row" id="replyField">
                            <form id="data-form" method="POST">
                                <div class="form-group">
                                    <label>Reply Message</label>
                                    <textarea name="message" class="form-control"></textarea>
                                    <input type="hidden" name="parent_msg" id="parent_msg" value="<?php echo $table_rows['id']; ?>">
                                    <input type="hidden" name="subject" id="subject" value="<?php echo $table_rows['subject']; ?>">
                                    <input type="hidden" name="receiver" id="receiver" value="<?php echo $table_rows['createdBy']; ?>">
                                </div>
                                <div class="row">
                                    <div class="col-md-12" id="resultMsg"></div>
                                </div>
                                <a href="javascript:void();" class="btn btn-success mr-2 turnOnProgress" id="saveBtn">Send</a>
                                <a href="javascript:void();" class="btn btn-success mr-2 progressBarBtn"><i class="fa fa-spinner fa-spin"></i> Processing...</a>
                            </form>
                        </div>
                        <?php //} ?>
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
                url: '<?php echo base_url(); ?>seller/home/save_msg_reply',
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

    function displayReply(subject, parent_msg, receiver){
        document.getElementById('subject').value=subject;
        document.getElementById('parent_msg').value=parent_msg;
        document.getElementById('receiver').value=receiver;
        $('#replyField').css('display','block');
    }
</script>