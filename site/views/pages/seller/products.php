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
                        <h3 class="panel-title"><i class="ti-list fa-fw"></i> Product</h3>
                    </div>
                    <div class="panel-body p40">
                        <div class="table-responsive pt20 pb20">
                            <table id="dataTable" class="display" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Brand</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Reviews</th>
                                        <th>Product Link</th>
                                        <th class="text-center">Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Brand</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Reviews</th>
                                        <th>Product Link</th>
                                        <th class="text-center">Status</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php $counter=0; foreach($table_rows as $item){ $counter++;?>
                                    <tr>
                                        <td><?php echo $counter; ?></td>
                                        <td><img src="<?php echo base_url();?>media/products/thumb/<?php echo $item->image; ?>" class="img-thumbnail productThumb" /></td>
                                        <td class="no-break-sm"><?php echo ucwords($item->name); ?></td>
                                        <td><?php echo ucwords(getCategoryName($item->category)); ?></td>
                                        <td><?php echo $item->brand; ?></td>
                                        <td><?php echo $item->price; ?></td>
                                        <td><?php echo $item->quantity; ?></td>
                                        <td class="text-center"><a href="<?php echo base_url().'prod/'.$item->product_url; ?>" target="_blank"><?php echo getProductReviewCounter($item->id); ?></a></td>
                                        <td class="text-center"><a href="javascript:void(0);" onclick="copy_link('<?php echo base_url().'prod/'.$item->product_url; ?>');"><i class="fa fa-copy"></i></a></td>
                                        <td class="text-center"><?php echo ucwords($item->product_status); ?></td>
                                        <td>
                                            <a href="<?=base_url()?>seller/product/product_details/<?php echo $item->id;?>" class="btn btn-sm btn-success" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>
                                            <a href="<?=base_url()?>seller/product/edit_product/<?php echo $item->id;?>" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>
                                            <a href="javascript:void();" onclick="deleteProduct('<?php echo base_url()."seller/product/delete_product/".$item->id;?>');" class="btn btn-sm btn-danger"><i class="ti-trash"></i></a>
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

    function deleteProduct(data){
        var txt;
        if (confirm("Are you sure you want to delete this product?")) {
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

    function copy_link(link){
        window.prompt("Copy to clipboard: Ctrl+C, Enter", link);
        // navigator.clipboard.writeText(link);
    }
</script>