<?php
	$brand=""; $initialPrice=""; $finalPrice=""; $time="";
	$start_page=1; $end_page=20;
	if(isset($_REQUEST['brand'])){
		$brand=sqlSafe($_REQUEST['brand']);
	}
	if(isset($_REQUEST['initialPrice'])){
		$initialPrice=sqlSafe($_REQUEST['initialPrice']);
	}
	if(isset($_REQUEST['finalPrice'])){
		$finalPrice=sqlSafe($_REQUEST['finalPrice']);
	}
	if(isset($_REQUEST['time'])){
		$time=sqlSafe($_REQUEST['time']);
	}
	if(isset($_REQUEST['from'])){
		$start_page=(int)sqlSafe($_REQUEST['from']);
	}
	if(isset($_REQUEST['to'])){
		$end_page=(int)sqlSafe($_REQUEST['to']);
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Online Trainings - GetValue</title>
</head>
<body>
	<section id="" style="margin-top:0px;">
		<div class="ebooks">
			<div class="container clearfix">
				<h2 class="col-md-12">ONLINE TRAININGS & PROGRAMS</h2>
			    <div class="col-md-3">
			    	<div class="productSideBar">
			    		<h4>Filter</h4><hr>
			    		<form method="get" class="row" action="">
			    			<div class="form-group col-md-12">
			    				<select class="form-control" name="time">
			    					<option value="new" <?php if($time=='new'){ echo 'selected'; } ?>>New</option>
			    					<option value="old" <?php if($time=='old'){ echo 'selected'; } ?>>Old</option>
			    				</select>
			    			</div>
			    			<div class="form-group col-md-12">
			    				<label>By Price</label>
			    				<div class="input-group">
									<input type="number" class="form-control" placeholder="1" value="<?php echo $initialPrice; ?>" name="initialPrice">
									<span class="input-group-addon">-</span>
									<input type="number" class="form-control" placeholder="0" value="<?php echo $finalPrice; ?>" name="finalPrice">
								</div>
			    			</div>
			    			<div class="form-group col-md-12">
			    				<select class="form-control" name="brand">
			    					<option value="">Sort by brand</option>
			    					<option value="">All brand</option>
			    					<?php echo $brands; ?>
			    				</select>
			    			</div>
			    			<div class="form-group col-md-12">
			    				<button type="submit" class="btn btn-info btn-block">Search</button>
			    			</div>
			    		</form>
			    	</div>
			    	<div class="sideAds">
			    	    <a href="https://www.getvalue.co/register" target="_blank">
			    		    <img src="<?php echo base_url().'media/ads/affiliate-program.jpg'; ?>">
			    		</a>
			    	</div>
			    	<div class="sideAds">
			    	    <a href="https://www.getvalue.co/become_affiliate" target="_blank">
			    		    <img src="<?php echo base_url().'media/ads/affiliate.jpg'; ?>">
			    		</a>
			    	</div>
			    </div>
			    <div class="col-md-9">

    				<div id="shop" class="shop grid-container clearfix" data-layout="fitRows">
    					<?php foreach($products as $product){ ?>
    					<div class="product_x clearfix">
    						<div class="product-image">
    							<a href="<?php echo base_url().'prod/'.$product->product_url; ?>"><img src="<?= base_url().'media/products/thumb/'.$product->image; ?>" alt=""></a>
    						</div>
    						<div class="product-desc">
    							<div class="product-title"><h3><a href="<?php echo base_url().'prod/'.$product->product_url; ?>"><?php echo ucwords($product->name); ?></a></h3></div>
    							<div class="product-price"><?php echo numberFormat($product->price); ?> Tsh.</div>
    							<div class="product-rating">
    								<?php echo getProductRate($product->product_url); ?>
    								<!-- <i class="fa fa-star"></i>
    								<i class="fa fa-star"></i>
    								<i class="fa fa-star"></i>
    								<i class="fa fa-star"></i>
    								<i class="fa fa-star-half-full"></i> -->
    							</div>
    							<p>Sold by: <a href=""><?php echo ucwords(getSellerFullName($product->seller_id)); ?></a></p>
    							<?php if(strtolower($product->product_status)=='live'){ ?>
    							<a class="add-to-cart btn-add m-b-5" href="javascript:void();" onclick="addToCart('<?php echo $product->id; ?>', '<?php echo $product->price; ?>', '<?php echo $product->name; ?>', '<?php echo $product->product_url; ?>');">Add To Cart <i id="progress<?php echo $product->id; ?>" class="progIc fa fa-spinner fa-spin"></i></a>
    							<!-- <a class="add-to-cart btn-add more-blue" href="javascript:void();" onclick="buyProduct('<?php echo $product->id; ?>', '<?php echo $product->price; ?>', '<?php echo $product->name; ?>');">Buy Now <i id="progress<?php echo $product->id; ?>" class="progIc fa fa-spinner fa-spin"></i></a> -->
    							<a class="add-to-cart btn-add more-blue" href="<?=base_url('home/checkout').'?product='.$product->id?>">Buy Now </a>
    							<?php }else{ ?>
    							<a class="add-to-cart btn-add cart-unactive m-b-5" href="javascript:void();">Add To Cart</a>
    							<a class="add-to-cart btn-add btn-danger" href="javascript:void();" onclick="buyProduct('<?php echo $product->id; ?>', '<?php echo $product->price; ?>', '<?php echo $product->name; ?>');">Pre-order Now <i id="progress<?php echo $product->id; ?>" class="progIc fa fa-spinner fa-spin"></i></a>
    							<?php } ?>
    						</div>
    					</div>
    					<?php } ?>

    					<div class="row">
    						<div class="col-md-12 m-t-20">
		    					<nav aria-label="...">
									<ul class="pagination">
										<?php
											// $total_products=65;
											$pagination=""; $dis_p="disabled"; $dis_n="disabled";
											$prev_url="javascript:void(0);"; $next_url="javascript:void(0);";
											$pg_limit=20;
				    						if($total_products>0){
				    							$pages=ceil($total_products/$pg_limit);
				    							$current_page=floor($start_page/$pg_limit)+1;
				    							$p_count=0; $strt=0;
				    							for ($i=1; $i <= $pages; $i++) {
				    								$p_count++;
				    								if($current_page == $i){
				    									$pagination.='<li class="page-item active"><span class="page-link">'.$i.'<span class="sr-only">(current)</span></span></li>';
				    								}else{
				    									$pagination.='<li class="page-item"><a class="page-link" href="'.base_url('online_trainings?brand='.$brand.'&initialPrice='.$initialPrice.'&finalPrice='.$finalPrice.'&time='.$time.'&from='.(($strt*$pg_limit)+1).'&to='.($i*$pg_limit)).'">'.$i.'</a></li>';
				    								}
				    								$strt++;
				    							}
				    							if($current_page!=1){
				    								$dis_p="";
				    								$prev_url=base_url('online_trainings?brand='.$brand.'&initialPrice='.$initialPrice.'&finalPrice='.$finalPrice.'&time='.$time.'&from='.($start_page-$pg_limit).'&to='.($start_page-1));
				    							}
				    							if($current_page!=$p_count){
				    								$dis_n="";
				    								$next_url=base_url('online_trainings?brand='.$brand.'&initialPrice='.$initialPrice.'&finalPrice='.$finalPrice.'&time='.$time.'&from='.($end_page+1).'&to='.($end_page+$pg_limit));
				    							}
				    						}
				    					?>
										<li class="page-item <?=$dis_p?>"><a class="page-link" href="<?=$prev_url?>">Previous</a></li>
										<?=$pagination?>
										<li class="page-item <?=$dis_n?>"><a class="page-link" href="<?=$next_url?>">Next</a></li>
									</ul>
								</nav>
							</div>
						</div>

                    </div>

                    
                </div>
            </div>
        </div>
	</section>
</body>
</html>
<script type="text/javascript">
</script>