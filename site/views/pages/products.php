<?php
	$start_page=1; $end_page=20;
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
	<title><?=$title?></title>
</head>
<body>
	<section id="" style="margin-top:0px;">
		<div class="ebooks">
			<div class="container clearfix">
				<h2 class="col-md-12"><?=ucwords(str_replace("_", " ", $filter))?> - List</h2>
			    <div class="col-md-3">
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
    					<?php if(isset($productInDiscounts) && sizeof($productInDiscounts)>0){ ?>
	    					<?php foreach($productInDiscounts as $product){ ?>
	    					<div class="product_x clearfix">
	    						<div class="product-image">
	    							<a href="<?php echo base_url().'prod/'.$product->product_url; ?>"><img src="<?= base_url().'media/products/thumb/'.$product->image; ?>" alt=""></a>
	    						</div>
	    						<div class="product-desc">
	    							<div class="product-title"><h3><a href="<?php echo base_url().'prod/'.$product->product_url; ?>"><?php echo ucwords($product->name); ?></a></h3></div>
	    							<!-- <div class="product-price"><?php //echo numberFormat($product->price); ?> Tsh.</div> -->
	    							<div class="product-price">
	    								<?php
					                		$couponInfDp=getProductCoupon($product->id);
					                		if(sizeof($couponInfDp)>0 && (float)$couponInfDp['coupon_value']>0){
					                			echo '<del class="text-danger">'.numberFormat($product->price).' Tsh.</del> <small>'.numberFormat($product->price-(float)$couponInfDp['coupon_value']).' Tsh.</small>';
					                		}else{
					                			echo numberFormat($product->price).' <small> Tsh.</small>';
					                		}
										?>
	    							</div>
	    							<div class="product-rating">
	    								<?php echo getProductRate($product->product_url); ?>
	    							</div>
	    							<p>Sold by: <a href="<?php echo base_url().'home/seller_collection/'.$product->seller_id; ?>"><?php echo ucwords(getSellerFullName($product->seller_id)); ?></a></p>
	    							<?php if(strtolower($product->product_status)=='live'){ ?>
	    							<a class="add-to-cart btn-add m-b-5" href="javascript:void();" onclick="addToCart('<?php echo $product->id; ?>', '<?php echo $product->price; ?>', '<?php echo $product->name; ?>', '<?php echo $product->product_url; ?>');">Add To Cart <i id="progress<?php echo $product->id; ?>" class="progIc fa fa-spinner fa-spin"></i></a>
	    							<a class="add-to-cart btn-add more-blue" href="<?=base_url('home/checkout').'?product='.$product->id?>">Buy Now </a>
	    							<?php }else{ ?>
	    							<a class="add-to-cart btn-add cart-unactive m-b-5" href="javascript:void();">Add To Cart</a>
	    							<a class="add-to-cart btn-add btn-danger" href="javascript:void();" onclick="buyProduct('<?php echo $product->id; ?>', '<?php echo $product->price; ?>', '<?php echo $product->name; ?>');">Pre-order Now <i id="progress<?php echo $product->id; ?>" class="progIc fa fa-spinner fa-spin"></i></a>
	    							<?php } ?>
	    						</div>
	    					</div>
	    					<?php } ?>
    					<?php } ?>

    					<?php if(isset($sellerProducts) && sizeof($sellerProducts)>0){ ?>
	    					<?php foreach($sellerProducts as $product){ ?>
	    					<div class="product clearfix">
	    						<div class="product-image">
	    							<a href="<?php echo base_url().'prod/'.$product->product_url; ?>"><img src="<?= base_url().'media/products/thumb/'.$product->image; ?>" alt=""></a>
	    						</div>
	    						<div class="product-desc">
	    							<div class="product-title"><h3><a href="<?php echo base_url().'prod/'.$product->product_url; ?>"><?php echo ucwords($product->name); ?></a></h3></div>
	    							<div class="product-price"><?php echo numberFormat($product->price); ?> Tsh.</div>
	    							<div class="product-rating">
	    								<?php echo getProductRate($product->product_url); ?>
	    							</div>
	    							<p>Sold by: <a href="<?php echo base_url().'home/seller_collection/'.$product->seller_id; ?>"><?php echo ucwords(getSellerFullName($product->seller_id)); ?></a></p>
	    							<?php if(strtolower($product->product_status)=='live'){ ?>
	    							<a class="add-to-cart btn-add m-b-5" href="javascript:void();" onclick="addToCart('<?php echo $product->id; ?>', '<?php echo $product->price; ?>', '<?php echo $product->name; ?>', '<?php echo $product->product_url; ?>');">Add To Cart <i id="progress<?php echo $product->id; ?>" class="progIc fa fa-spinner fa-spin"></i></a>
	    							<a class="add-to-cart btn-add more-blue" href="<?=base_url('home/checkout').'?product='.$product->id?>">Buy Now </a>
	    							<?php }else{ ?>
	    							<a class="add-to-cart btn-add cart-unactive m-b-5" href="javascript:void();">Add To Cart</a>
	    							<a class="add-to-cart btn-add btn-danger" href="javascript:void();" onclick="buyProduct('<?php echo $product->id; ?>', '<?php echo $product->price; ?>', '<?php echo $product->name; ?>');">Pre-order Now <i id="progress<?php echo $product->id; ?>" class="progIc fa fa-spinner fa-spin"></i></a>
	    							<?php } ?>
	    						</div>
	    					</div>
	    					<?php } ?>
    					<?php } ?>

    					<?php if(isset($bestSellerProducts) && sizeof($bestSellerProducts)>0){ ?>
	    					<?php foreach($bestSellerProducts as $product){ ?>
	    					<div class="product clearfix">
	    						<div class="product-image">
	    							<a href="<?php echo base_url().'prod/'.$product['product_url']; ?>"><img src="<?= base_url().'media/products/thumb/'.$product['image']; ?>" alt=""></a>
	    						</div>
	    						<div class="product-desc">
	    							<div class="bestseller_label" style="width: 90% !important">Best Seller</div>
	    							<div class="product-title"><h3><a href="<?php echo base_url().'prod/'.$product['product_url']; ?>"><?php echo ucwords($product['name']); ?></a></h3></div>
	    							<div class="product-price"><?php echo numberFormat($product['price']); ?> Tsh.</div>
	    							<div class="product-rating">
	    								<?php echo getProductRate($product['product_url']); ?>
	    							</div>
	    							<p>Sold by: <a href="<?php echo base_url().'home/seller_collection/'.$product['seller_id']; ?>"><?php echo ucwords(getSellerFullName($product['seller_id'])); ?></a></p>
	    							<?php if(strtolower($product['product_status'])=='live'){ ?>
	    							<a class="add-to-cart btn-add m-b-5" href="javascript:void();" onclick="addToCart('<?php echo $product['id']; ?>', '<?php echo $product['price']; ?>', '<?php echo $product['name']; ?>', '<?php echo $product['product_url']; ?>');">Add To Cart <i id="progress<?php echo $product['id']; ?>" class="progIc fa fa-spinner fa-spin"></i></a>
	    							<a class="add-to-cart btn-add more-blue" href="<?=base_url('home/checkout').'?product='.$product['id']?>">Buy Now </a>
	    							<?php }else{ ?>
	    							<a class="add-to-cart btn-add cart-unactive m-b-5" href="javascript:void();">Add To Cart</a>
	    							<a class="add-to-cart btn-add btn-danger" href="javascript:void();" onclick="buyProduct('<?php echo $product['id']; ?>', '<?php echo $product['price']; ?>', '<?php echo $product['name']; ?>');">Pre-order Now <i id="progress<?php echo $product['id']; ?>" class="progIc fa fa-spinner fa-spin"></i></a>
	    							<?php } ?>
	    						</div>
	    					</div>
	    					<?php } ?>
    					<?php } ?>

    					<?php if(isset($popularProducts) && sizeof($popularProducts)>0){ ?>
	    					<?php foreach($popularProducts as $product){ ?>
	    					<div class="product clearfix">
	    						<div class="product-image">
	    							<a href="<?php echo base_url().'prod/'.$product->product_url; ?>"><img src="<?= base_url().'media/products/thumb/'.$product->image; ?>" alt=""></a>
	    						</div>
	    						<div class="product-desc">
	    							<div class="product-title"><h3><a href="<?php echo base_url().'prod/'.$product->product_url; ?>"><?php echo ucwords($product->name); ?></a></h3></div>
	    							<div class="product-price"><?php echo numberFormat($product->price); ?> Tsh.</div>
	    							<div class="product-rating">
	    								<?php echo getProductRate($product->product_url); ?>
	    							</div>
	    							<p>Sold by: <a href="<?php echo base_url().'home/seller_collection/'.$product->seller_id; ?>"><?php echo ucwords(getSellerFullName($product->seller_id)); ?></a></p>
	    							<?php if(strtolower($product->product_status)=='live'){ ?>
	    							<a class="add-to-cart btn-add m-b-5" href="javascript:void();" onclick="addToCart('<?php echo $product->id; ?>', '<?php echo $product->price; ?>', '<?php echo $product->name; ?>', '<?php echo $product->product_url; ?>');">Add To Cart <i id="progress<?php echo $product->id; ?>" class="progIc fa fa-spinner fa-spin"></i></a>
	    							<a class="add-to-cart btn-add more-blue" href="<?=base_url('home/checkout').'?product='.$product->id?>">Buy Now </a>
	    							<?php }else{ ?>
	    							<a class="add-to-cart btn-add cart-unactive m-b-5" href="javascript:void();">Add To Cart</a>
	    							<a class="add-to-cart btn-add btn-danger" href="javascript:void();" onclick="buyProduct('<?php echo $product->id; ?>', '<?php echo $product->price; ?>', '<?php echo $product->name; ?>');">Pre-order Now <i id="progress<?php echo $product->id; ?>" class="progIc fa fa-spinner fa-spin"></i></a>
	    							<?php } ?>
	    						</div>
	    					</div>
	    					<?php } ?>
    					<?php } ?>

    					<?php if(isset($oldProducts) && sizeof($oldProducts)>0){ ?>
	    					<?php foreach($oldProducts as $product){ ?>
	    					<div class="product clearfix">
	    						<div class="product-image">
	    							<a href="<?php echo base_url().'prod/'.$product->product_url; ?>"><img src="<?= base_url().'media/products/thumb/'.$product->image; ?>" alt=""></a>
	    						</div>
	    						<div class="product-desc">
	    							<div class="product-title"><h3><a href="<?php echo base_url().'prod/'.$product->product_url; ?>"><?php echo ucwords($product->name); ?></a></h3></div>
	    							<div class="product-price"><?php echo numberFormat($product->price); ?> Tsh.</div>
	    							<div class="product-rating">
	    								<?php echo getProductRate($product->product_url); ?>
	    							</div>
	    							<p>Sold by: <a href="<?php echo base_url().'home/seller_collection/'.$product->seller_id; ?>"><?php echo ucwords(getSellerFullName($product->seller_id)); ?></a></p>
	    							<?php if(strtolower($product->product_status)=='live'){ ?>
	    							<a class="add-to-cart btn-add m-b-5" href="javascript:void();" onclick="addToCart('<?php echo $product->id; ?>', '<?php echo $product->price; ?>', '<?php echo $product->name; ?>', '<?php echo $product->product_url; ?>');">Add To Cart <i id="progress<?php echo $product->id; ?>" class="progIc fa fa-spinner fa-spin"></i></a>
	    							<a class="add-to-cart btn-add more-blue" href="<?=base_url('home/checkout').'?product='.$product->id?>">Buy Now </a>
	    							<?php }else{ ?>
	    							<a class="add-to-cart btn-add cart-unactive m-b-5" href="javascript:void();">Add To Cart</a>
	    							<a class="add-to-cart btn-add btn-danger" href="javascript:void();" onclick="buyProduct('<?php echo $product->id; ?>', '<?php echo $product->price; ?>', '<?php echo $product->name; ?>');">Pre-order Now <i id="progress<?php echo $product->id; ?>" class="progIc fa fa-spinner fa-spin"></i></a>
	    							<?php } ?>
	    						</div>
	    					</div>
	    					<?php } ?>
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
				    									$pagination.='<li class="page-item"><a class="page-link" href="'.base_url('home/products?filter='.$filter.'&seller='.$seller.'&from='.(($strt*$pg_limit)+1).'&to='.($i*$pg_limit)).'">'.$i.'</a></li>';
				    								}
				    								$strt++;
				    							}
				    							if($current_page!=1){
				    								$dis_p="";
				    								$prev_url=base_url('home/products?filter='.$filter.'&seller='.$seller.'&from='.($start_page-$pg_limit).'&to='.($start_page-1));
				    							}
				    							if($current_page!=$p_count){
				    								$dis_n="";
				    								$next_url=base_url('home/products?filter='.$filter.'&seller='.$seller.'&from='.($end_page+1).'&to='.($end_page+$pg_limit));
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