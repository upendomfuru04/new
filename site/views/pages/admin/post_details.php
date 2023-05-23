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
                        <h3 class="panel-title"><i class="ti-list fa-fw"></i> Post Details</h3>
                    </div>
                    <div class="panel-body row p40">
                        <div class="col-md-8">
                        	<h4><?php echo ucwords($table_rows['title']); ?></h4>
                        	<p>Category: <?php echo ucwords($table_rows['category']); ?></p>
                        	<hr >
                        	<p class="text-justify"><?php echo preg_replace('#(<br */?>\s*)+#i', '<br />', htmlspecialchars_decode(stripslashes($table_rows['content']), ENT_QUOTES)); ?></p>
                        </div>
                        <div class="col-md-4">
                            <img src="<?php echo base_url(); ?>media/blog/<?php echo $table_rows['image']; ?>" class="img-thumbnail productImgD">
                        </div>
                        <div class="col-md-12">
                            <hr>
                            <a href="<?php echo base_url(); ?>admin/blog/edit_post/<?php echo $table_rows['id'];?>" class="btn btn-sm btn-info"><i class="fa fa-edit"></i> Edit</a>
                            <?php if($table_rows['is_approved']=='1'){ ?>
                            <a href="javascript:void();" class="btn btn-sm btn-success" onclick="approvePost('<?php echo base_url()."admin/blog/approve_post/".$table_rows['id'];?>');"><i class="fa fa-check"></i> Approve</a>
                            <a href="javascript:void();" class="btn btn-sm btn-danger" onclick="showDeny();"><i class="fa fa-close"></i> Deny</a>
                            <?php }?>
                        </div>
                        <div class="col-md-12 denyComment">
                            <div class="form-group m-t-20">
                                <label>Deny Reason</label>
                                <textarea class="form-control" name="deny_reason" id="deny_reason"><?php echo $table_rows['denied_reason']; ?></textarea>
                            </div>
                            <div class="col-md-12" id="resultMsg"></div>
                            <a href="javascript:void();" class="btn btn-sm btn-danger turnOnProgress" onclick="denyPost('<?php echo $table_rows['id'];?>');"> Submit</a>
                            <a href="javascript:void();" class="btn btn-success mr-2 progressBarBtn"><i class="fa fa-spinner fa-spin"></i> Sending...</a>
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

    });

    function showDeny(){
        $('.denyComment').slideToggle();
    }

    function denyPost(data){
        var reason=document.getElementById('deny_reason').value;
        if(reason!=""){
            var txt;
            if (confirm("Are you sure you want to deny this post?")) {
                $.ajax({
                    url: '<?php echo base_url()?>admin/blog/deny_post/'+data+'?reason='+reason,
                    type:"POST",
                    async: true,
                    processData: false,
                    success: function (data) {
                        if(data.trim()=='Success'){
                            var output = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> Success </div>'; 
                            $('#resultMsg').html(output);
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
            }
        }else{
            $('#resultMsg').html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> You must provide the deny reason </div>');
        }
    }

    function approvePost(data){
        var txt;
        if (confirm("Are you sure you want to approve this post?")) {
            $.ajax({
                url: data,
                type:"POST",
                success: function(data){
                    if(data.trim()=='Success'){
                        window.location="";
                    }else{
                        $('#resultMsg').html(data);
                    }
                }
            });
        }
    }
</script>