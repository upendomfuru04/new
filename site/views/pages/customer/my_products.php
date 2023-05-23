<!DOCTYPE html>
<html>
<head>
	<title>My Products || GetValue</title>
</head>
<body>
	<div class="panel panel-info">
		<div class="panel-heading">
			<h2 class="panel-title">Customer Product Download/Preview</h2>
		</div>
		<div class="panel-body cBody">
            <?php if(sizeOf($items)==0){ ?>
            <div class="alert alert-info">You have no any product</div>
            <?php }else{ ?>
            <div class="table-responsive p-b-20">
    			<table class="table table-bordered">
                    <thead>
                        <th>#</th>
                        <th>Product</th>
                        <th>Type</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-right">Action</th>
                    </thead>
                    <tbody>
                        <?php 
                            $counter=0; $totalQtn=0; $totalPrice=0;
                            foreach($items as $product){  
                                $counter++;
                                $action='<a href="'.base_url().'media/products/preview/'.$product->preview.'" target="_blank" class="btn btn-xs btn-info m-r-10"><i class="fa fa-search"></i> Preview</a>';
                                if(strtolower($product->product_status)=='live'){
                                    $action.='<a href="'.base_url().'customer/download/?prd='.$product->id.'" class="btn btn-xs btn-success"><i class="fa fa-download"></i> Download</a>';
                                }else{
                                    $action.='<a href="javascript:void()" class="btn btn-xs btn-default" style="cursor: default;"><i class="fa fa-download"></i> Preorder...</a>';
                                }

                            ?>
                        <tr>
                            <td><?php echo $counter; ?></td>
                            <td><a href="<?php echo base_url().'prod/'.$product->product_url; ?>"><img src="<?php echo base_url().'media/products/thumb/'.$product->image; ?>" class="cartImg m-r-10"><?php echo ucwords($product->name); ?></a></td>
                            <td class="text-center"><?php echo ucwords($product->category); ?></td>
                            <td class="text-center"><?php echo $product->quantity; ?></td>
                            <td class="text-right"><?php echo $action; ?></td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
            <p class="sm-hint">Scroll horizontal to view more</p>
            <?php } ?>
            <div class="clearfix"></div>
		</div>
	</div>
</body>
</html>
<script type="text/javascript">
  $(document).ready(function(){
        
  });
</script>