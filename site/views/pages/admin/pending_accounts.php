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
                        <h3 class="panel-title"><i class="ti-user fa-fw"></i> Pending Accounts</h3>
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
                                        <th>Account Type</th>
                                        <th>Date</th>
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
                                        <th>Account Type</th>
                                        <th>Date</th>
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
                                        $seller_type="";
                                        if($account->is_insider=="1"){
                                            $seller_type.="Insider<br>";
                                        }
                                        if($account->is_outsider=="1"){
                                            $seller_type.="Outsider<br>";
                                        }
                                        if($account->is_contributor=="1"){
                                            $seller_type.="Contributor<br>";
                                        }
                                        if($account->is_vendor=="1"){
                                            $seller_type.="Vendor<br>";
                                        }
                                    ?>
                                    <tr>
                                        <td><?php echo $counter; ?></td>
                                        <td><img src="<?php echo base_url();?>media/customer_avatars/<?php echo $avatar; ?>" class="img-thumbnail productThumb" /></td>
                                        <td><?php echo ucwords($account->full_name); ?></td>
                                        <td><?php echo ucwords($account->gender); ?></td>
                                        <td><?php echo $account->phone; ?></td>
                                        <td><?php echo $account->email; ?></td>
                                        <td><?php echo $seller_type; ?></td>
                                        <td><?php echo date('d M, Y', $account->createdDate); ?></td>
                                        <td>
                                            <a href="javascript:void();" onclick="activateNewAccount('<?php echo base_url()."admin/account/activate_new_seller_account/".$account->id;?>', '<?php echo $account->full_name;?>');" class="btn btn-xs btn-primary"><i class="fa fa-check-square-o"></i> Activate Account</a>
                                            <a href="javascript:void();" onclick="deleteAccount('<?php echo base_url()."admin/account/delete_seller_account/".$account->id;?>', '<?php echo $account->full_name;?>');" class="btn btn-xs btn-danger"><i class="ti-trash"></i> Delete Account</a>
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

    function activateNewAccount(data, name){
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
</script>