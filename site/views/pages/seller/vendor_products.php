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
                        <h3 class="panel-title"><i class="ti-list fa-fw"></i> <?=ucwords($info['full_name'])?> - Products</h3>
                    </div>
                    <div class="panel-body p40">
                        <div class="table-responsive pt20 pb20">
                            <table id="dataTable" class="display no-break-sm" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Affiliate Url</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $counter=0; 
                                        foreach($products as $item){ 
                                            $counter++;
                                            $product_url=$item['product_url'];
                                            /*if($item['is_referred']){
                                                $referral_url=$product_url.'/?ref='.$profile_url;
                                            }else{
                                                $referral_url=generateReferralUrl($userID, "", $product_url);
                                            }*/
                                    ?>
                                    <tr>
                                        <td><?php echo $counter; ?></td>
                                        <td><a href="<?php echo base_url().'prod/'.$product_url; ?>" target="_blank"><img src="<?php echo base_url();?>media/products/thumb/<?php echo $item['image']; ?>" class="img-thumbnail productThumb" /></a></td>
                                        <td class="no-break-sm"><a href="<?php echo base_url().'prod/'.$product_url; ?>" target="_blank"><?php echo ucwords($item['name']); ?></a></td>
                                        <td class="break-all"><?php echo base_url().'prod/'.$item['referral_url']; ?></td>
                                        <td class="text-center">
                                            <?php //if($item['is_referred']){ ?>
                                                <a href="javascript:void(0);" onclick="copy_link('<?php echo base_url().'prod/'.$item['referral_url']; ?>');" class="btn btn-sm btn-default" title="Copy Url"><i class="fa fa-copy text-danger"></i></a>
                                            <?php //}else{ ?>
                                                <!-- <a href="javascript:void();" onclick="save_affiliate_url('<?=$referral_url?>', '<?=$item['id']?>');" class="btn btn-sm btn-default turnOnProgress" title="Save Url"><i class="ti-save text-success"></i></a> -->
                                            <?php //} ?>
                                            <!-- <a href="javascript:void();" class="btn btn-sm btn-primary progressBarBtn"><i class="fa fa-spinner fa-spin"></i></a> -->
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

    function save_affiliate_url(url_link, product){
        if(url_link!=""){
            if (confirm("Are you sure you want to add the referral url?")) {
                $('.turnOnProgress').css('display','none');
                $('.progressBarBtn').css('display','inline-block');
                $.ajax({
                    url: '<?php echo base_url(); ?>seller/product/save_affiliateurl?affiliate_url='+url_link+'&product='+product,
                    type: 'POST',
                    data:$('#data-form').serialize(),
                    async: true,
                    processData: false,
                    success: function (data) {
                        if(data.trim()=='Success'){
                            window.location="";
                        }else{
                            alert(data);
                        }
                        $('.turnOnProgress').css('display','inline-block');
                        $('.progressBarBtn').css('display','none');
                    },
                    error: function( xhr, status, error ) {
                        alert(error);
                        $('.turnOnProgress').css('display','inline-block');
                        $('.progressBarBtn').css('display','none');
                        return false;
                    }
                });
            }
        }
    }

    function copy_link(link){
        window.prompt("Copy to clipboard: Ctrl+C, Enter", link);
        // navigator.clipboard.writeText(link);
    }
</script>