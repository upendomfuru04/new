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
                        <h3 class="panel-title"><i class="ti-list fa-fw"></i> Referrals</h3>
                    </div>
                    <div class="panel-body p40">
                        <div class="table-responsive pb20 pt20">
                            <table id="dataTable" class="display no-break-sm" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Affiliate Url</th>
                                        <th>Type</th>
                                        <th>Affiliate</th>
                                        <th>Views</th>
                                        <th>Commission</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $counter=0; foreach($table_rows as $item){ $counter++;?>
                                    <tr>
                                        <td><?php echo $counter; ?></td>
                                        <td><img src="<?php echo base_url();?>media/products/<?php echo $item->image; ?>" class="img-thumbnail productThumb" /></td>
                                        <td><?php echo ucwords($item->name); ?></td>
                                        <td class="break-all"><?php echo base_url().'prod/'.$item->referral_url; ?></td>
                                        <td class="text-center"><?php echo ucwords($item->seller_type); ?></td>
                                        <td class="text-center"><?php echo getSellerFullName($item->seller_id); ?></td>
                                        <td class="text-center"><?php echo $item->views; ?></td>
                                        <td class="text-center"><?php if($item->commission!="") echo number_format($item->commission); ?> Tsh.</td>
                                        <td class="text-center">
                                            <a href="javascript:void();" onclick="deleteReferralUrl('<?php echo base_url()."admin/product/delete_referral_url/".$item->id;?>');" class="text-danger"><i class="ti-trash"></i></a>
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
        $('#dataTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });

    function deleteReferralUrl(data){
        var txt;
        if (confirm("Are you sure you want to delete this referral url?")) {
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