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
                        <h3 class="panel-title"><i class="ti-list fa-fw"></i> Customer Accounts</h3>
                    </div>
                    <div class="panel-body p40">
                        <div class="table-responsive pb20 pt20">
                            <table id="dataTable" class="display" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Profile</th>
                                        <th>Name</th>
                                        <th>Gender</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Joined Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Profile</th>
                                        <th>Name</th>
                                        <th>Gender</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Joined Date</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php $counter=0; foreach($table_rows as $account){ 
                                        $counter++;
                                        if($account->avatar!=""){
                                            $avatar=$account->avatar;
                                        }else{
                                            $avatar="default.png";
                                        }
                                    ?>
                                    <tr>
                                        <td><?php echo $counter; ?></td>
                                        <td><img src="<?php echo base_url();?>media/customer_avatars/<?php echo $avatar; ?>" class="img-thumbnail productThumb" /></td>
                                        <td><?php echo ucwords($account->first_name.' '.$account->surname); ?></td>
                                        <td><?php echo ucwords($account->gender); ?></td>
                                        <td><?php echo $account->phone; ?></td>
                                        <td><?php echo $account->email; ?></td>
                                        <td><?php echo date('d M, Y', $account->createdDate); ?></td>
                                        <td>
                                            <?php if($account->status=='2'){ ?>
                                            <a href="javascript:void();" onclick="activateAccount('<?php echo base_url()."admin/account/activate_customer_account/".$account->id;?>', '<?php echo $account->first_name.' '.$account->surname;?>');" class="btn btn-xs btn-success" data-toggle="tooltip" title="Activate"><i class="fa fa-check"></i></a>
                                            <?php }else{ ?>
                                            <a href="javascript:void();" onclick="diactivateAccount('<?php echo base_url()."admin/account/diactivate_customer_account/".$account->id;?>', '<?php echo $account->first_name.' '.$account->surname;?>');" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Diactivate"><i class="fa fa-ban"></i></a>
                                            <?php }?>
                                            <a href="javascript:void();" onclick="deleteAccount('<?php echo base_url()."admin/account/delete_customer_account/".$account->id;?>', '<?php echo $account->first_name.' '.$account->surname;?>');" class="btn btn-xs btn-danger"><i class="ti-trash"></i></a>
                                            <a href="javascript:void();" onclick="resetPassword('<?php echo base_url()."admin/account/reset_user_password/".$account->id;?>', '<?php echo $account->first_name.' '.$account->surname;?>');" class="btn btn-xs btn-info" data-toggle="tooltip" title="Reset Password"><i class="ti-key"></i></a>
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
        </div>
    </div>
</body>
</html>
<script type="text/javascript">
    $(document).ready(function(){
        $('#dataTable').DataTable();
    });

    function activateAccount(data, name){
        var txt;
        if (confirm("Are you sure you want to activate "+name+" account?")) {
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

    function diactivateAccount(data, name){
        var txt;
        if (confirm("Are you sure you want to disable "+name+" account?")) {
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

    function deleteAccount(data, name){
        var txt;
        if (confirm("Are you sure you want to delete "+name+" account?")) {
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

    function resetPassword(data, name){
        var txt;
        if (confirm("Are you sure you want to reset "+name+" password?")) {
            $.ajax({
                url: data,
                type:"POST",
                success: function(data){
                    if(data.trim()=='Success'){
                        alert("Password reset successfully, new password is username in small letters.");
                    }else{
                        alert(data);
                    }
                }
            });
        }
    }
</script>