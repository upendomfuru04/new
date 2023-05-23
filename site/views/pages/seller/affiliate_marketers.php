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
                        <h3 class="panel-title"><i class="ti-user fa-fw"></i> Affiliate Marketers</h3>
                    </div>
                    <div class="panel-body p40">
                        <div class="table-responsive pb20 pt20">
                            <table id="dataTable" class="display" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Profile</th>
                                        <th>Name</th>
                                        <th>Affiliate Type</th>
                                        <th>Joined Date</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>No. of Products</th>
                                        <th>Total Views</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $counter=0; foreach($marketers as $account){ 
                                        $counter++;
                                        if($account['avatar']!=""){
                                            $avatar=$account['avatar'];
                                        }else{
                                            $avatar="default.png";
                                        }
                                        $seller_type="";
                                        if($account['is_insider']=="1"){
                                            $seller_type.="Insider<br>";
                                        }
                                        if($account['is_outsider']=="1"){
                                            $seller_type.="Outsider<br>";
                                        }
                                        if($account['is_contributor']=="1"){
                                            $seller_type.="Contributor<br>";
                                        }
                                        $total_products=$account['total_products']; $total_views=$account['total_views'];
                                        $createdDate="";
                                        if($account['createdDate']!=""){
                                            $createdDate=date('d M, Y', $account['createdDate']);
                                        }
                                    ?>
                                    <tr>
                                        <td><?php echo $counter; ?></td>
                                        <td><img src="<?php echo base_url();?>media/seller_avatars/<?php echo $avatar; ?>" class="img-thumbnail productThumb" /></td>
                                        <td><?php echo ucwords($account['full_name']); ?></td>
                                        <td><?php echo $seller_type; ?></td>
                                        <td><?php echo $createdDate; ?></td>
                                        <td><?php echo $account['phone']; ?></td>
                                        <td><?php echo $account['email']; ?></td>
                                        <td><?php echo $account['address']; ?></td>
                                        <td><?php echo $total_products; ?></td>
                                        <td><?php echo $total_views; ?></td>
                                        <td>
                                            <a href="<?=base_url('seller/home/new_message?recepient='.$account['user_id'])?>" class="btn btn-sm btn-default"><i class="fa fa-envelope text-info"></i></a>
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

</script>