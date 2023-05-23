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
                        <h3 class="panel-title"><i class="ti-user fa-fw"></i> Referral Vendors</h3>
                    </div>
                    <div class="panel-body p40">
                        <div class="table-responsive pb20 pt20">
                            <table id="dataTable" class="display" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Profile</th>
                                        <th>Name</th>
                                        <th class="text-center">Gender</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Joined Date</th>
                                        <th>Expire Date</th>
                                        <th>Products</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Profile</th>
                                        <th>Name</th>
                                        <th class="text-center">Gender</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Joined Date</th>
                                        <th>Expire Date</th>
                                        <th>Products</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php $counter=0; foreach($table_rows as $account){ 
                                        $counter++;
                                        if($account->avatar!=""){
                                            $avatar='media/seller_avatars/'.$account->avatar;
                                        }else{
                                            $avatar='media/seller_avatars/default.png';
                                        }
                                        $product_link="";
                                        $product_link='<a href="'.base_url('seller/product/vendor_products?seller='.$account->id).'"><i class="fa fa-list"></i> View</a>';
                                        $expire_date="";
                                        if($account->source_expire_date!=""){
                                            $expire_date=date("d M, Y", strtotime($account->source_expire_date));
                                        }
                                    ?>
                                    <tr>
                                        <td><?php echo $counter; ?></td>
                                        <td><img src="<?php echo base_url();?><?php echo $avatar; ?>" class="img-thumbnail productThumb" /></td>
                                        <td><?php echo ucwords($account->full_name); ?></td>
                                        <td class="text-center"><?php echo ucwords($account->gender); ?></td>
                                        <td><?php echo $account->phone; ?></td>
                                        <td><?php echo $account->email; ?></td>
                                        <td><?php echo $account->address; ?></td>
                                        <td><?php echo date('d M, Y', $account->createdDate); ?></td>
                                        <td><?=$expire_date?></td>
                                        <td><?=$product_link?></td>
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
</script>