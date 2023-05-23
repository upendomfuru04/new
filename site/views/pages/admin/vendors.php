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
                        <h3 class="panel-title"><i class="ti-user fa-fw"></i> Vendor Accounts</h3>
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
                                        <th>Address</th>
                                        <th>Source</th>
                                        <th>Expire Date</th>
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
                                        <th>Address</th>
                                        <th>Source</th>
                                        <th>Expire Date</th>
                                        <th>Joined Date</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php $counter=0; foreach($table_rows as $account){ 
                                        $counter++;
                                        $expire_date=""; $source_url="Direct";
                                        if($account->avatar!=""){
                                            $avatar='media/seller_avatars/'.$account->avatar;
                                        }else{
                                            $avatar='media/seller_avatars/default.png';
                                        }
                                        if($account->source_expire_date!=""){
                                            $expire_date=date("d M, Y", strtotime($account->source_expire_date));
                                        }
                                        if($account->source_url!=""){
                                            $source_url=$account->source_url;
                                        }
                                    ?>
                                    <tr>
                                        <td><?php echo $counter; ?></td>
                                        <td><img src="<?php echo base_url();?><?php echo $avatar; ?>" class="img-thumbnail productThumb" /></td>
                                        <td><?php echo ucwords($account->full_name); ?></td>
                                        <td><?php echo ucwords($account->gender); ?></td>
                                        <td><?php echo $account->phone; ?></td>
                                        <td><?php echo $account->email; ?></td>
                                        <td><?php echo $account->address; ?></td>
                                        <td><?php echo $source_url; ?></td>
                                        <td><?php echo $expire_date; ?></td>
                                        <td><?php echo date('d M, Y', $account->createdDate); ?></td>
                                        <td>
                                            <?php if($account->is_trusted_vendor=='0'){ ?>
                                            <a href="javascript:void();" onclick="isTrusted('<?php echo base_url()."admin/account/is_trusted_vendor/".$account->id;?>', '<?php echo $account->full_name;?>');" class="btn btn-xs btn-success"><i class="fa fa-check"></i> Is Trusted</a>
                                            <?php }else{ ?>
                                            <a href="javascript:void();" onclick="isNotTrusted('<?php echo base_url()."admin/account/is_not_trusted_vendor/".$account->id;?>', '<?php echo $account->full_name;?>');" class="btn btn-xs btn-warning"><i class="fa fa-check-o"></i> Not Trusted</a>
                                            <?php }?>
                                            <?php if($account->source_url!=""){ ?>
                                                <a href="javascript:void();" onclick="set_referral_expire('<?php echo $account->id;?>', '<?php echo $account->full_name;?>', '<?php echo $account->source_expire_date;?>')" class="btn btn-xs btn-info"><i class="fa fa-clock-o"></i> Ref. Expire Date</a>
                                            <?php } ?>
                                            <a href="javascript:void();" onclick="load_account_levels('<?php echo $account->id;?>', '<?php echo $account->full_name;?>', '<?php echo $account->is_vendor;?>', '<?php echo $account->is_insider;?>', '<?php echo $account->is_outsider;?>', '<?php echo $account->is_contributor;?>');" class="btn btn-xs btn-primary"><i class="fa fa-cog"></i> Manage Account Levels</a>
                                            <?php if($account->status=='2'){ ?>
                                            <a href="javascript:void();" onclick="activateAccount('<?php echo base_url()."admin/account/activate_seller_account/".$account->id;?>', '<?php echo $account->full_name;?>');" class="btn btn-xs btn-success" data-toggle="tooltip" title="Activate"><i class="fa fa-check"></i> Enable Acc.</a>
                                            <?php }else{ ?>
                                            <a href="javascript:void();" onclick="diactivateAccount('<?php echo base_url()."admin/account/diactivate_seller_account/".$account->id;?>', '<?php echo $account->full_name;?>');" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Diactivate"><i class="fa fa-ban"></i> Disable Acc.</a>
                                            <?php }?>
                                            <a href="javascript:void();" onclick="deleteAccount('<?php echo base_url()."admin/account/delete_seller_account/".$account->id;?>', '<?php echo $account->full_name;?>');" class="btn btn-xs btn-danger"><i class="ti-trash"></i> Delete Acc.</a>
                                            <a href="javascript:void();" onclick="resetPassword('<?php echo base_url()."admin/account/reset_user_password/".$account->id;?>', '<?php echo $account->full_name;?>');" class="btn btn-xs btn-info" data-toggle="tooltip" title="Reset Password"><i class="ti-key"></i></a>
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

    function isTrusted(data, name){
        var txt;
        if (confirm("Are you sure you want to set vendor "+name+" is trusted?")) {
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

    function isNotTrusted(data, name){
        var txt;
        if (confirm("Are you sure you want to set vendor "+name+" is not trusted?")) {
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

    function load_account_levels(seller, sellerName, is_vendor, is_insider, is_outsider, is_contributor){
        $('#sellerName').html(sellerName);
        document.getElementById('seller_identity').value=seller;
        if(is_vendor=='1'){
            document.getElementById('is_vendor').checked=true;
        }else{
            document.getElementById('is_vendor').checked=false;
        }
        if(is_insider=='1'){
            document.getElementById('is_insider').checked=true;
        }else{
            document.getElementById('is_insider').checked=false;
        }
        if(is_outsider=='1'){
            document.getElementById('is_outsider').checked=true;
        }else{
            document.getElementById('is_outsider').checked=false;
        }
        if(is_contributor=='1'){
            document.getElementById('is_contributor').checked=true;
        }else{
            document.getElementById('is_contributor').checked=false;
        }
        $('#account_level').modal('show');
    }

    function set_referral_expire(seller, sellerName, expire_date){
        $('#vSellerName').html(sellerName);
        document.getElementById('seller_idetity').value=seller;
        document.getElementById('expire_date').value=expire_date;
        $('#referral_expire_date').modal('show');
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