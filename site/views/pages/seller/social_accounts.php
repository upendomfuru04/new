<!DOCTYPE html>
<html>
<head>
    
</head>
<body>
	<h1 class="breadcumb"></h1>
    <div class="home-page">
        <div class="row p-t-20">
            <div class="col-md-7">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"> Social Media Accounts</h3>
                    </div>
                    <div class="panel-body p-40 row">
                        <div class="table-responsive pt20 pb20">
                            <table id="dataTable" class="display nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Social Media</th>
                                        <th>Link</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Social Media</th>
                                        <th>Link</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php $counter=0; foreach($table_rows as $item){ $counter++;?>
                                    <tr>
                                        <td><?php echo $counter; ?></td>
                                        <td><?php echo ucwords($item->name); ?></td>
                                        <td><?php echo $item->link; ?></td>
                                        <td>
                                            <a href="<?php echo base_url().'seller/home/edit_social_account/'.$item->id;?>" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>
                                            <a href="javascript:void();" onclick="deleteSocial('<?php echo base_url()."seller/home/delete_social_account/".$item->id;?>');" class="btn btn-sm btn-danger"><i class="ti-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <p class="sm-hint">Scroll horizontal to view more</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"> Add/Update</h3>
                    </div>
                    <div class="panel-body p-40 row">
                        <form id="data-form" method="POST">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label>Media:</label>
                                    <select class="form-control" name="name">
                                        <option value="">Select</option>
                                        <option value="facebook" <?php if($update!="" && $edit_rows['name']=='facebook'){ echo 'selected';} ?>>Facebook</option>
                                        <option value="instagram" <?php if($update!="" && $edit_rows['name']=='instagram'){ echo 'selected';} ?>>Instagram</option>
                                        <option value="twitter" <?php if($update!="" && $edit_rows['name']=='twitter'){ echo 'selected';} ?>>Twitter</option>
                                        <option value="google-plus" <?php if($update!="" && $edit_rows['name']=='google-plus'){ echo 'selected';} ?>>Google Plus</option>
                                        <option value="linkedin" <?php if($update!="" && $edit_rows['name']=='linkedin'){ echo 'selected';} ?>>Linkedin</option>
                                        <option value="youtube" <?php if($update!="" && $edit_rows['name']=='youtube'){ echo 'selected';} ?>>Youtube</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Link:</label>
                                    <textarea class="form-control" name="link" placeholder="eg. https//facebook.com/user_link"><?php if($update!=""){ echo $edit_rows['link'];} ?></textarea>
                                </div>
                                <div class="col-md-12" id="resultMsg"></div>
                                <div class="col-md-12 p-t-10">
                                    <?php if($update!=""){ ?>
                                    <a href="javascript:void();" class="btn btn-success mr-2 turnOnProgress" id="updateBtn">Update</a>
                                    <?php }else{ ?>
                                    <a href="javascript:void();" class="btn btn-success mr-2 turnOnProgress" id="saveBtn">Save</a>
                                    <?php } ?>
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
        $('#dataTable').DataTable();

        $('#saveBtn').click(function(){
            $('.turnOnProgress').css('display','none');
            $('.progressBarBtn').css('display','inline-block');
            $.ajax({
                url: '<?php echo base_url(); ?>seller/home/save_social_accounts',
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
                url: '<?php echo base_url(); ?>seller/home/update_social_accounts/<?php echo $update; ?>',
                type: 'POST',
                data:$('#data-form').serialize(),
                async: true,
                processData: false,
                success: function (data) {
                    if(data.trim()=='Success'){
                        var output = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> Success </div>'; 
                        $('#resultMsg').html(output);
                        document.getElementById("data-form").reset();
                        window.location="<?php echo base_url() ?>seller/home/social_accounts";
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

    function deleteSocial(data){
        var txt;
        if (confirm("Are you sure you want to delete this social account?")) {
            $.ajax({
                url: data,
                type:"POST",
                success: function(data){
                    if(data.trim()=='Success'){
                        window.location="";
                    }else{
                        alert(data);
                    }
                }
            });
        }
    }
</script>