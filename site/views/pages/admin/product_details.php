<!DOCTYPE html>
<html>
<head>
    
</head>
<body>
	<h1 class="breadcumb"></h1>
    <div class="home-page">
        <div class="row p-t-20">
            <div class="col-md-11 col-md-offset-">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="ti-list fa-fw"></i> Product Details</h3>
                    </div>
                    <div class="panel-body row p40">
                        <div class="col-md-8">
                            <div class="table-responsive1 prodDesc">
                                <table class="table table-bordered tb-Verticle-sm">
                                    <tr><td><strong>Product Name: </strong></td><td><?php echo ucwords($table_rows['name']); ?></td></tr>
                                    <tr><td><strong>Category: </strong></td><td><?php echo ucwords(getCategoryName($table_rows['category'])); ?></td></tr>
                                    <tr><td><strong>Brand: </strong></td><td><?php echo ucwords($table_rows['brand']); ?></td></tr>
                                    <tr><td><strong>Price: </strong></td><td><?php echo number_format($table_rows['price']); ?> Tsh.</td></tr>
                                    <tr><td><strong>Quantity: </strong></td><td><?php echo $table_rows['quantity']; ?></td></tr>
                                    <tr><td><strong>Summary: </strong></td><td><?php echo ucfirst($table_rows['summary']); ?></td></tr>
                                    <tr><td><strong>Description: </strong></td><td class="break-word"><?php echo preg_replace('#(<br */?>\s*)+#i', '<br />', htmlspecialchars_decode(stripslashes($table_rows['description']), ENT_QUOTES)); ?></td></tr>
                                    <tr><td><strong>Views: </strong></td><td><span class="p-5 badge-outline-info"><?php echo $table_rows['views']; ?></span></td></tr>
                                    <tr><td><strong>Created Date: </strong></td><td><span class="text-info"><?php echo date('M d, Y', $table_rows['createdDate']); ?></span></td></tr>
                                    <tr><td><strong>Admin: </strong></td><td><span class="text-info"><?php echo getAdminFullName($table_rows['admin']); ?></span></td></tr>
                                    <?php if($table_rows['update_time']!=""){ ?>
                                    <tr><td><strong>Updated Date: </strong></td><td><span class="text-info"><?php echo date('M d, Y', $table_rows['update_time']); ?></span></td></tr>
                                    <?php } ?>
                                    <tr><td><strong>Seller Account: </strong></td><td><?php echo ucwords($table_rows['seller_type']); ?></td></tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <img src="<?php echo base_url(); ?>media/products/<?php echo $table_rows['image']; ?>" class="img-thumbnail productImgD">
                            <?php
                                if($table_rows['preview_type']=='image'){
                                    echo '<img src="'.base_url().'media/products/preview/'.$table_rows['preview'].'" class="img-thumbnail productImgD m-t-20">';
                                }else{
                                    echo '<a href="'.base_url().'media/products/preview/'.$table_rows['preview'].'" target="_blank" class="m-t-20 btn badge-outline-primary">Preview - '.$table_rows['name'].'</a>';
                                }
                                if($table_rows['virtual_link']!=""){
                                    echo '<a href="'.$table_rows['virtual_link'].'" target="_blank" class="m-t-20 btn badge-outline-info">Media - Virtual Link</a>';
                                }else{
                                    if($table_rows['media_type']=='image'){
                                        if(strpos($table_rows['media'], ';')!==FALSE){
                                            $mdia=explode(";", trim($table_rows['media']));
                                            for ($i=0; $i < sizeof($mdia); $i++) { 
                                                if($mdia[$i]!=""){
                                                    echo '<img src="'.base_url().'products/medias/'.trim($mdia[$i]).'" class="img-thumbnail productImgD m-t-20">';
                                                }
                                            }
                                        }else{
                                            echo '<img src="'.base_url().'products/medias/'.$table_rows['media'].'" class="img-thumbnail productImgD m-t-20">';
                                        }
                                    }else{
                                        if(strpos($table_rows['media'], ';')!==FALSE){
                                            $mdia=explode(";", trim($table_rows['media']));
                                            for ($i=0; $i < sizeof($mdia); $i++) { 
                                                if($mdia[$i]!=""){
                                                    echo '<a href="'.base_url().'products/medias/'.trim($mdia[$i]).'" target="_blank" class="m-t-20 btn badge-outline-success">Media - '.$table_rows['name'].'</a>';
                                                }
                                            }
                                        }else{
                                            echo '<a href="'.base_url().'products/medias/'.$table_rows['media'].'" target="_blank" class="m-t-20 btn badge-outline-success">Media - '.$table_rows['name'].'</a>';
                                        }
                                    }
                                }
                            ?>
                        </div>
                        <div class="col-md-12">
                            <hr>
                            <a href="<?php echo base_url(); ?>admin/product/edit_product/<?php echo $table_rows['id'];?>" class="btn btn-sm btn-info"><i class="fa fa-edit"></i> Edit</a>
                            <a href="javascript:void();" onclick="deleteProduct('<?php echo base_url()."admin/product/delete_product/".$table_rows['id'];?>');" class="btn btn-sm btn-danger"><i class="ti-trash"></i> Delete</a>
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

    function deleteProduct(data){
        var txt;
        if (confirm("Are you sure you want to delete this product?")) {
            $.ajax({
                url: data,
                type:"POST",
                success: function(data){
                    if(data.trim()=='Success'){
                        window.location="<?php echo base_url(); ?>admin/product/products";
                    }else{
                        alert(data);
                    }
                }
            });
        }
    }
</script>