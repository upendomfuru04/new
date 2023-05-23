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
                        <h3 class="panel-title"><i class="ti-list fa-fw"></i> Message Details</h3>
                    </div>
                    <div class="panel-body row">
                        <div class="col-md-6">
                        	<h4><?php echo ucwords($table_rows['subject']); ?></h4>
                        	<p>Receivers: <?php echo ucwords($table_rows['receiver']); ?></p>
                            <p>Sent By: <?php echo getAdminFullName($table_rows['createdBy']); ?></p>
                            <p>Sent Date: <?php echo date('d M, Y', $table_rows['createdDate']); ?></p>
                        	<hr >
                        	<p class="text-justify"><?php echo preg_replace('#(<br */?>\s*)+#i', '<br />', $table_rows['message']); ?></p>
                            <?php getMsgReplies($table_rows['id'], $userID); ?>
                            <div id="replyField">
                                <hr >
                                <form id="data-form" method="POST">
                                    <div class="form-group">
                                        <label>Reply Message</label>
                                        <textarea name="message" class="form-control"></textarea>
                                        <input type="hidden" name="parent_msg" id="parent_msg" value="">
                                        <input type="hidden" name="subject" id="subject" value="">
                                        <input type="hidden" name="receiver" id="receiver" value="">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" id="resultMsg"></div>
                                    </div>
                                    <a href="javascript:void();" class="btn btn-success mr-2 turnOnProgress" id="saveBtn">Send</a>
                                    <a href="javascript:void();" class="btn btn-success mr-2 progressBarBtn"><i class="fa fa-spinner fa-spin"></i> Processing...</a>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table id="dataTable" class="display" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th>Read Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $counter=0; 
                                            if(isset($receivers) && sizeof($receivers)>0){
                                            foreach($receivers as $rvr){
                                                $counter++;
                                                $status="<span class='text-danger'>Not Read</span>";
                                                $name="";
                                                if($rvr->is_read=='0'){
                                                    $status="<span class='text-success'>Read</span>";
                                                }
                                                if($rvr->account_type=='seller'){
                                                    $name=getSellerFullName($rvr->user_id);
                                                }else{
                                                    $name=getCustomerFullName($rvr->user_id);
                                                }
                                        ?>
                                            <tr>
                                                <td><?php echo $counter; ?></td>
                                                <td><?php echo ucwords($name); ?></td>
                                                <td><?php echo $rvr->receiver_email; ?></td>
                                                <td><?php echo $status; ?></td>
                                                <td><?php echo $rvr->read_time; ?></td>
                                            </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr>
                            <a href="javascript:void();" onclick="deleteData('<?php echo base_url()."admin/home/delete_msg/".$msg;?>');" class="btn btn-xs btn-danger"><i class="ti-trash"></i> Delete</a>
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
        $('#dataTable').DataTable();

        $('#saveBtn').click(function(){
           $('.turnOnProgress').css('display','none');
           $('.progressBarBtn').css('display','inline-block');
           $.ajax({
                url: '<?php echo base_url(); ?>admin/home/save_msg_reply',
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

    function deleteData(data){
        var txt;
        if (confirm("Are you sure you want to delete this message?")) {
            $.ajax({
                url: data,
                type:"POST",
                success: function(data){
                    if(data.trim()=='Success'){
                        window.location="<?php echo base_url();?>admin/home/messages";
                    }else{
                        alert(data);
                    }
                }
            });
        }
    }

    function displayReply(subject, parent_msg, receiver){
        document.getElementById('subject').value=subject;
        document.getElementById('parent_msg').value=parent_msg;
        document.getElementById('receiver').value=receiver;
        $('#replyField').css('display','block');
    }
</script>