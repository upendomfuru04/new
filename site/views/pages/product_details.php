<?php
	if(sizeof($product)==0){
		header("Location: ".base_url());
	}
	$refer="";
	if($referral!=""){
		$refer="&referral=".$referral;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title; ?> - GetValue</title>
</head>
<body>
	<div class="container break-all" id="view-product">
	    <div class="row">
	        <div class="col-md-9">
	            <div class="row">
	                <div class="col-sm-5">
	                    <div style="margin-bottom:20px;">
	                        <img src="<?php echo base_url().'media/products/'.$product['image']; ?>" style="width:auto; height:auto;" data-num="0" class="other-img-preview img-responsive img-sl the-image" alt="Biashara & Ujasilimia Mali">
	                    </div>
	                    <div class="row"></div>
	                </div>
	        		<div class="col-sm-7">
	        			<?php
	        				if($totalSales>2){
	        					echo '<div class="bestseller_label">Best Seller</div><br>';
	        				}
	        			?>
	            		<h1 style="word-break: normal; hyphens: auto !important; -webkit-hyphens: auto !important; -ms-hyphens: auto !important;"><?php echo ucwords($product['name']); ?></h1>
			            <div class="row row-info">
			                <!-- <div class="col-sm-12 starTxt">
			                    <p>Ratings <?php //echo getProductRate($product['product_url']); ?> </p>
			                </div> -->
			                <?php
			                	$star_list=getProductRateValueList($product['id']);
			                	$stars=explode("-", $star_list);
			                	$totalrate=$stars[0];
			                	$star1=$stars[1]; $star2=$stars[2]; $star3=$stars[3]; $star4=$stars[4]; $star5=$stars[5];
			                	$star_l1=0; $star_l2=0; $star_l3=0; $star_l4=0; $star_l5=0;
			                	$total_rate=$star1+$star2+$star3+$star4+$star5;
			                	$rate=$total_rate/5;
			                	if($total_rate>0){
			                		$star_l1=($star1/$total_rate)*100;
			                		$star_l2=($star2/$total_rate)*100;
			                		$star_l3=($star3/$total_rate)*100;
			                		$star_l4=($star4/$total_rate)*100;
			                		$star_l5=($star5/$total_rate)*100;
			                	}
			                ?>
			                <div class="col-sm-4 col-xs-4">
			                    <center>
			                    	<strong style="font-size: 50px"><?=$rate?></strong>
			                    	<div class="stars">
			                    		<?php echo convertNumberToStar($rate); ?>
			                    	</div>
			                    	<span><?=number_format($totalrate)?></span>
			                    </center>
			                </div>
			                <div class="col-sm-8 col-xs-8">
			                	<ul class="rating_list">
			                		<li><span>5</span> <div class="bar-container pull-right"><div class="bar-x" style="width: <?=$star_l5?>%;"></div></div></li>
			                		<li><span>4</span> <div class="bar-container pull-right"><div class="bar-x" style="width: <?=$star_l4?>%;"></div></div></li>
			                		<li><span>3</span> <div class="bar-container pull-right"><div class="bar-x" style="width: <?=$star_l3?>%;"></div></div></li>
			                		<li><span>2</span> <div class="bar-container pull-right"><div class="bar-x" style="width: <?=$star_l2?>%;"></div></div></li>
			                		<li><span>1</span> <div class="bar-container pull-right"><div class="bar-x" style="width: <?=$star_l1?>%;"></div></div></li>
			                	</ul>
			                </div>
			                <div class="col-xs-12 col-sm-12 border-bottom"></div>
			            </div>
			            <div class="row row-info">
			                <div class="col-sm-6 col-xs-6"><b>Price:</b></div>
			                <div class="col-sm-6 col-xs-6">
			                	<?php
			                		$coupon_value=0; $coupon_code="";
			                		$couponInf=getProductCoupon($product['id']);
			                		if(sizeof($couponInf)>0 && (float)$couponInf['coupon_value']>0){
			                			$coupon_code=$couponInf['coupon_code'];
			                			echo '<del style="">'.numberFormat($product['price']).' Tsh.</del> <small>'.numberFormat($product['price']-(float)$couponInf['coupon_value']).' Tsh.</small>';
			                		}else{
			                			echo numberFormat($product['price']).' <small> Tsh.</small>';
			                		}
			                	?>			                		
			                </div>
			                <div class="col-sm-12 col-xs-12 border-bottom"></div>
			            </div>
			            <?php
			            	if($coupon_code!=""){
			            		echo '<div class="row row-info">';
			            		echo '<div class="col-sm-6 col-xs-6"><b>Discount Code</b> : <span>'.$coupon_code.'</span></div>';
			            		echo '<div class="col-sm-6 col-xs-6"><a href="javascript:void(0);" onclick="buyProduct('.$product['id'].', '.$product['price'].', \''.str_replace("'", "", $product['name']).'\', \''.$product['product_url'].'\', \''.$coupon_code.'\');" class="btn btn-sm btn-info">Use this Code</a></div>';
			            		echo '<div class="col-sm-12 col-xs-12 border-bottom"></div>';
			            		echo '</div>';
			            	}
			            ?>
			            <div class="row row-info">
			                <div class="col-sm-6 col-xs-6"><b><?php echo ucwords($product['seller_type']); ?>:</b></div>
			                <div class="col-sm-6 col-xs-6"><a href="<?php echo base_url().'home/seller_collection/'.$product['seller_id']; ?>"><?php echo ucwords(getSellerFullName($product['seller_id'])); ?></a></div>
			                <div class="col-sm-12 col-xs-12 border-bottom"></div>
			            </div>
                        <div class="row row-info">
			                <div class="col-sm-6 col-xs-6"><b>Added To Cart:</b></div>
			                <div class="col-sm-6 col-xs-6"><?php echo getItemCartCounter($userID, $product['id']); ?></div>
			                <div class="col-sm-12 col-xs-12 border-bottom"></div>
			            </div>
                        <div class="row row-info">
		                    <div class="col-sm-6 col-xs-6"><b>Added to shop:</b></div>
		                    <div class="col-sm-6 col-xs-6"><?php echo date("M d, Y", $product['createdDate']); ?></div>
		                    <div class="col-sm-12 col-xs-12 border-bottom"></div>
		                </div>
                        <div class="row row-info">
		                    <div class="col-sm-6 col-xs-6"><b>Product Views:</b></div>
		                    <div class="col-sm-6 col-xs-6"><?=number_format($product['views'])?></div>
		                    <div class="col-sm-12 col-xs-12 border-bottom"></div>
		                </div>
                        <div class="row row-info">
			                <div class="col-sm-6 col-xs-6"><b>In category:</b></div>
			                <div class="col-sm-6 col-xs-6">
			                    <a href="javascript:void(0);" class="btn-blue-round" data-categorie-id="4">
			                     <?php echo ucwords(getCategoryName($product['category'])); ?></a>
			                </div>
			                <div class="col-sm-12 col-xs-12 border-bottom">

			                </div>
			            </div>
			            <div class="row row-info">
			                <div class="col-sm-6 col-xs-6"><b>Sample</b></div>
			                <div class="col-sm-6 col-xs-6">
			                	<?php if($product['preview_type']=='audio'){ ?>
			                    <audio controls style="width:100% !important;">
									<source src="<?php echo base_url().'media/products/preview/'.$product['preview']; ?>">
									Your browser does not support the audio element. Please update it to new version.
								</audio>
								<?php }elseif($product['preview_type']=='video'){ ?>
									<button class="btn btn-default" data-toggle="modal" data-target=".sample-video">
									    <i class="fa fa-play"></i>
									</button> 
									<span style="padding: 10px;">Watch Sample</span>
								<?php }else{ ?>
									<a href="<?php echo base_url().'media/products/preview/'.$product['preview']; ?>" target="_blank">
										<button class="btn btn-default"><i class="fa fa-file"></i></button> 
										<span style="padding: 10px;">View</span>
									</a>
								<?php } ?>
			                </div>
			                <div class="col-sm-12 col-xs-12 border-bottom"></div>
			            </div>
			            <div class="row row-info">
			                <div class="col-sm-6 col-xs-6 buy">
			                	<?php if(strtolower($product['product_status'])=='live'){ ?>
			                    <!-- <a href="javascript:void(0);" onclick="buyProduct('<?php echo $product['id']; ?>', '<?php echo $product['price']; ?>', '<?php echo $product['name']; ?>', '<?php echo $product['product_url']; ?>');" class="add-to-cart btn-add"> -->
			                    <a href="<?=base_url('home/checkout?product='.$product['id'].$refer)?>" class="add-to-cart btn-add">
			                        <span class="text-to-bg">Buy now </span>
			                    </a>
			                    <?php }else{ ?>
			                    <a href="javascript:void(0);" onclick="buyProduct('<?php echo $product['id']; ?>', '<?php echo $product['price']; ?>', '<?php echo str_replace("'", "", $product['name']); ?>', '<?php echo $product['product_url']; ?>');" class="add-to-cart btn-add btn-danger">
			                        <span class="text-to-bg">Pre-order now <i id="progress<?php echo $product['id']; ?>" class="progIc fa fa-spinner fa-spin"></i></span>
			                    </a>
			                    <?php } ?>
			                </div>
			                <div class="col-sm-6 col-xs-6 add">
			                	<?php if(strtolower($product['product_status'])=='live'){ ?>
			                    <a href="javascript:void(0);" onclick="addToCart('<?php echo $product['id']; ?>', '<?php echo $product['price']; ?>', '<?php echo $product['name']; ?>', '<?php echo $product['product_url']; ?>');" class="add-to-cart btn-add more-blue">
			                        <span class="text-to-bg">Add To Cart <i id="progress<?php echo $product['id']; ?>" class="progIc fa fa-spinner fa-spin"></i></span>
			                    </a>
			                    <?php }else{ ?>
    							<a class="add-to-cart btn-add cart-unactive m-b-5" href="javascript:void();"><span class="text-to-bg">Add To Cart</span></a>
			                    <?php } ?>
			                </div>
			                <div class="col-sm-12 col-xs-12"></div>
			            </div>
	        		</div>
	    		</div>
			    <div class="row" style="margin-top:50px;">
			        <ul class="nav nav-tabs product-tabs">
			            <li class="active"><a data-toggle="tab" href="#home">Description</a></li>
			            <li><a data-toggle="tab" href="#menu1">Seller Info</a></li>
			            <li><a data-toggle="tab" href="#menu2">Reviews</a></li>
			        </ul>
			        <div class="tab-content product-tab-content">
			            <div id="home" class="tab-pane fade in active" style="margin-top:40px;">
			            	<p><i><?php echo ucfirst(htmlspecialchars_decode(stripslashes($product['summary']), ENT_QUOTES)); ?></i></p>
			            	<?php echo htmlspecialchars_decode(stripslashes($product['description']), ENT_QUOTES); ?>
			            </div>
			            <div id="menu1" class="tab-pane fade" style="margin-top:40px;">
			            	<ul class="seller_info">
			            	<?php
			            		if(sizeof($sellerInfo) > 0){
			            			if($sellerInfo['brand']!=""){
			            				echo '<li>Name: <strong>'.$sellerInfo['brand'].'</strong></li>';
			            			}else{
			            				echo '<li>Name: <strong>'.$sellerInfo['full_name'].'</strong></li>';
			            			}
			            			if($sellerInfo['address']!=''){
			            				echo '<li>Address: <strong>'.$sellerInfo['address'].'</strong></li>';
			            			}
			            			echo '<li>Phone: <strong>'.$sellerInfo['phone'].'</strong></li>';
			            			echo '<li>Email: <strong>'.$sellerInfo['email'].'</strong></li>';
			            		}
			            	?>
			            	</ul>       
			            </div>
			            <div id="menu2" class="tab-pane fade" style="margin-top:40px;">
			                <!--COMMENTS START-->
			                <style>
			                    hr { 
			                        display: block;
			                        margin-top: 0.5em;
			                        margin-bottom: 0.5em;
			                        margin-left: auto;
			                        margin-right: auto;
			                        border-style: inset;
			                        border-width: 1px;
			                        color: red;
			                    }
			                    .card-inner{
			                    
			                        margin-left: 4rem;
			                    }
			                </style>        
			                <div class="container-fluid">
			                    <p class="starRate">Ratings: <?php echo getProductRated($product['product_url']); ?>
			                         <a class="float-right btn btn-outline-primary ml-2" href="javascript:void();" onclick="writeReview();"> <i class="fa fa-reply"></i> Leave a Review</a>
			                    </p>
			                    <?php 
			                    	if(!isLoggedIn()){
			                    		echo '<p><small>You must login to post your review.</small></p>';
			                    	}
			                   	?>
			                    <form method="POST" id="review-form">
			                    	<div class="form-group">
			                    		<label>Review</label>
			                    		<input type="hidden" name="product" value="<?php echo $product['id']; ?>">
					                    <input type="hidden" name="rate_counter" id="star_counter" value="">
			                    		<textarea class="form-control" name="review"></textarea>
			                    	</div>
			                    	<div class="row"><div class="col-md-12" id="resultMsgReview"></div></div>
			                    	<a href="javascript:void();" class="btn btn-sm btn-success turnOnReviewProgress" onclick="sendReview('<?php echo $product['id']; ?>');">Post</a>
                    				<a class="btn btn-sm btn-success progressBarReviewBtn"><i class="fa fa-spinner fa-spin"></i> Posting...</a>
			                    </form>
			                    <!-- </center> -->
			                    <hr >
			                    <div class="card">
			                        <div class="card-body">
			                            <div class="container-fluid">
				                            <div class="row">
					                            <div class="col-sm-9" style="border: 2px solid white; border-radius: 5px;" id="reviewArea"></div>
					                            <div class="col-sm-3"></div>
					                        </div>
				                        </div> 
			                        </div>
			                    </div>			                    
			                </div>
	                		<!--COMMENTS END HERE-->        
	            		</div>
	        		</div>
	    		</div>
			</div>
	 <!-- Sidebar
	============================================= -->
			<div class="sidebar nobottommargin col_last clearfix">
					
				<div class="sidebar-widgets-wrap">
				    
				    <div class="sideAds">
					    <a href="https://www.getvalue.co/register" target="_blank">
			    		    <img src="<?php echo base_url().'media/ads/affiliate-program.jpg'; ?>">
					    </a>
					</div>
				    
					<div class="widget clearfix">

				        <h4>Popular Books</h4>
							
						<div id="post-list-footer">
							<?php foreach($popularBooks as $pProduct){ ?>
								<div class="spost clearfix">
									<div class="entry-image popular">
										<a href="<?php echo base_url().'prod/'.$pProduct->product_url; ?>"><img src="<?= base_url().'media/products/thumb/'.$pProduct->image; ?>" alt=""></a>
									</div>
									<div class="entry-c">
										<div class="entry-title">
											<h4><a href="<?php echo base_url().'prod/'.$pProduct->product_url; ?>"><?php echo ucwords($pProduct->name); ?></a></h4>
										</div>
										<ul class="entry-meta">
											<li class="color"><?php echo numberFormat($pProduct->price); ?> <small>Tsh.</small></li>
											<p><?php echo getProductRate($pProduct->product_url); ?>
				                                <!-- <span class="stars-container stars-50" >★★★★★</span> (2.5) -->
				                            </p>
											<p>Sold by: <a href="<?php echo base_url().'home/seller_collection/'.$pProduct->seller_id; ?>"><?php echo ucwords(getSellerFullName($pProduct->seller_id)); ?></a></p>
										</ul>
									</div>
								</div>
							<?php } ?>
						</div>

					</div>

					<div id="s-icons" class="widget quick-contact-widget clearfix">

						<h4 class="highlight-me">Connect Socially</h4>

						<a href="https://m.facebook.com/getvalueinc/" class="social-icon si-colored si-facebook" data-toggle="tooltip" data-placement="top" title="Facebook">
											<i class="fa fa-facebook"></i>
						</a>
						<a href="https://mobile.twitter.com/getvalueinc/" class="social-icon si-colored si-twitter" data-toggle="tooltip" data-placement="top" title="Twitter">
							<i class="fa fa-twitter"></i>
						</a>
						<a href="https://www.linkedin.com/company/getvalueinc" class="social-icon si-colored si-linkedin" data-toggle="tooltip" data-placement="top" title="LinkedIn">
							<i class="fa fa-linkedin"></i>
						</a>
						<a href="https://www.instagram.com/getvalueinc/" class="social-icon si-colored si-instagram" data-toggle="tooltip" data-placement="top" title="Instagram">
							<i class="fa fa-instagram"></i>
						</a>
						<a href="https://m.youtube.com/channel/UCuSS0Qj54SWQKF1cYT-IOBQ" class="social-icon si-colored si-youtube" data-toggle="tooltip" data-placement="top" title="YouTube">
							<i class="fa fa-youtube"></i>
						</a>
					</div>

				</div>
			</div><!-- .sidebar end -->
		</div>
		<div class="row orders-from-category ebooks">
			<div class="filter-sidebar">
			    <div class="detail_title row">
			    	<div class="col-sm-8 col-xs-8">
			        	<span>More Products On Discount</span>
			        </div>
			        <div class="col-sm-4 col-xs-4">
			        	<a class="btn btn-xs btn-info pull-right" href="<?=base_url('home/products?filter=discounts')?>">View All >></a>
			        </div>
			    </div>
			</div>
			<div id="shop" class="shop grid-container clearfix" data-layout="fitRows">
			<?php
				if(sizeof($productInDiscounts) > 0){
					foreach($productInDiscounts as $rProduct){
			?>
					<div class="product_y clearfix">
						<div class="product-image">
							<a href="<?php echo base_url().'prod/'.$rProduct->product_url; ?>"><img src="<?= base_url().'media/products/thumb/'.$rProduct->image; ?>" alt=""></a>
						</div>
						<div class="product-desc">
							<div class="product-title"><h3><a href="<?php echo base_url().'prod/'.$rProduct->product_url; ?>"><?php echo ucwords($rProduct->name); ?></a></h3></div>
							<div class="product-price">
								<!-- <?php //echo numberFormat($rProduct->price); ?> <small>Tsh.</small> -->
								<?php
			                		$couponInfDp=getProductCoupon($rProduct->id);
			                		if(sizeof($couponInfDp)>0 && (float)$couponInfDp['coupon_value']>0){
			                			echo '<del class="text-danger">'.numberFormat($rProduct->price).' Tsh.</del> <small>'.numberFormat($rProduct->price-(float)$couponInfDp['coupon_value']).' Tsh.</small>';
			                		}else{
			                			echo numberFormat($rProduct->price).' <small> Tsh.</small>';
			                		}
								?>
							</div>
							<div class="product-rating">
								<?php echo getProductRate($rProduct->product_url); ?>
							</div>
							<p class="soldBy">Sold by: <a href="<?php echo base_url().'home/seller_collection/'.$rProduct->seller_id; ?>"><?php echo ucwords(getSellerFullName($rProduct->seller_id)); ?></a></p>
						</div>
					</div>
            <?php }}else{ ?>
	        	<div class="alert alert-info">No other products from this category</div>
	        <?php } ?>
	    	</div>

	    	<!-- More Products seller -->
	    	<?php if(sizeof($sellerProducts) > 0){ ?>
	    	<div class="filter-sidebar m-t-l-20">
			    <div class="detail_title row">
			    	<div class="col-sm-8 col-xs-8">
			        	<span>More Products From This Seller</span>
		        	</div>
			        <div class="col-sm-4 col-xs-4">
				        <a class="btn btn-xs btn-info pull-right" href="<?=base_url('home/products?filter=seller_products&seller=').$product['seller_id']?>">View All >></a>
				    </div>
			    </div>
			</div>
			<div id="shop" class="shop grid-container clearfix" data-layout="fitRows">
			<?php
				foreach($sellerProducts as $rProduct){
				?>
					<div class="product_y clearfix">
						<div class="product-image">
							<a href="<?php echo base_url().'prod/'.$rProduct->product_url; ?>"><img src="<?= base_url().'media/products/thumb/'.$rProduct->image; ?>" alt=""></a>
						</div>
						<div class="product-desc">
							<div class="product-title"><h3><a href="<?php echo base_url().'prod/'.$rProduct->product_url; ?>"><?php echo ucwords($rProduct->name); ?></a></h3></div>
							<div class="product-price text-danger">
								<?php echo numberFormat($rProduct->price); ?> <small>Tsh.</small>
							</div>
							<div class="product-rating">
								<?php echo getProductRate($rProduct->product_url); ?>
							</div>
							<p class="soldBy">Sold by: <a href="<?php echo base_url().'home/seller_collection/'.$rProduct->seller_id; ?>"><?php echo ucwords(getSellerFullName($rProduct->seller_id)); ?></a></p>
						</div>
					</div>
	            <?php } ?>
	    	</div>
	    	<?php } ?>

	    	<!-- Best Sellers List -->
	    	<div class="filter-sidebar m-t-l-20">
			    <div class="detail_title row">
			    	<div class="col-sm-8 col-xs-8">
			        	<span>Best Sellers List</span>
			        </div>
			        <div class="col-sm-4 col-xs-4">
			        	<a class="btn btn-xs btn-info pull-right" href="<?=base_url('home/products?filter=best_seller')?>">View All >></a>
			        </div>
			    </div>
			</div>
			<div id="shop" class="shop grid-container clearfix" data-layout="fitRows">
			<?php
				if(sizeof($bestSellerProducts) > 0){
					foreach($bestSellerProducts as $rProduct){
				?>
					<div class="product_y clearfix">
						<div class="product-image">
							<a href="<?php echo base_url().'prod/'.$rProduct['product_url']; ?>"><img src="<?= base_url().'media/products/thumb/'.$rProduct['image']; ?>" alt=""></a>
						</div>
						<div class="product-desc">
							<div class="bestseller_label" style="width: 80% !important;">Best Seller</div>
							<div class="product-title"><h3><a href="<?php echo base_url().'prod/'.$rProduct['product_url']; ?>"><?php echo ucwords($rProduct['name']); ?></a></h3></div>
							<div class="product-price text-danger">
								<?php echo numberFormat($rProduct['price']); ?> <small>Tsh.</small>
							</div>
							<div class="product-rating">
								<?php echo getProductRate($rProduct['product_url']); ?>
							</div>
							<p class="soldBy">Sold by: <a href="<?php echo base_url().'home/seller_collection/'.$rProduct['seller_id']; ?>"><?php echo ucwords(getSellerFullName($rProduct['seller_id'])); ?></a></p>
						</div>
					</div>
		            <?php }
		        } ?>
	    	</div>

	    	<!-- GetValue Recommendations -->
	    	<div class="filter-sidebar m-t-l-20">
			    <div class="detail_title row">
			    	<div class="col-sm-8 col-xs-8">
			        	<span>GetValue Recommendations</span>
			        </div>
			        <div class="col-sm-4 col-xs-4">
			        	<a class="btn btn-xs btn-info pull-right" href="<?=base_url('home/products?filter=getvalue_recommendation')?>">View All >></a>
			        </div>
			    </div>
			</div>
			<div id="shop" class="shop grid-container clearfix" data-layout="fitRows">
			<?php
				if(sizeof($popularProducts) > 0){
					foreach($popularProducts as $rProduct){
				?>
					<div class="product_y clearfix">
						<div class="product-image">
							<a href="<?php echo base_url().'prod/'.$rProduct->product_url; ?>"><img src="<?= base_url().'media/products/thumb/'.$rProduct->image; ?>" alt=""></a>
						</div>
						<div class="product-desc">
							<div class="product-title"><h3><a href="<?php echo base_url().'prod/'.$rProduct->product_url; ?>"><?php echo ucwords($rProduct->name); ?></a></h3></div>
							<div class="product-price text-danger">
								<?php echo numberFormat($rProduct->price); ?> <small>Tsh.</small>
							</div>
							<div class="product-rating">
								<?php echo getProductRate($rProduct->product_url); ?>
							</div>
							<p class="soldBy">Sold by: <a href="<?php echo base_url().'home/seller_collection/'.$rProduct->seller_id; ?>"><?php echo ucwords(getSellerFullName($rProduct->seller_id)); ?></a></p>
						</div>
					</div>
		            <?php }
		        } ?>
	    	</div>

	    	<!-- Old is Gold -->
	    	<div class="filter-sidebar m-t-l-20">
			    <div class="detail_title row">
			    	<div class="col-sm-8 col-xs-8">
			        	<span>Old is Gold</span>
			        </div>
			        <div class="col-sm-4 col-xs-4">
			        	<a class="btn btn-xs btn-info pull-right" href="<?=base_url('home/products?filter=old_gold')?>">View All >></a>
			        </div>
			    </div>
			</div>
			<div id="shop" class="shop grid-container clearfix" data-layout="fitRows">
			<?php
				if(sizeof($oldProducts) > 0){
					foreach($oldProducts as $rProduct){
				?>
					<div class="product_y clearfix">
						<div class="product-image">
							<a href="<?php echo base_url().'prod/'.$rProduct->product_url; ?>"><img src="<?= base_url().'media/products/thumb/'.$rProduct->image; ?>" alt=""></a>
						</div>
						<div class="product-desc">
							<div class="product-title"><h3><a href="<?php echo base_url().'prod/'.$rProduct->product_url; ?>"><?php echo ucwords($rProduct->name); ?></a></h3></div>
							<div class="product-price text-danger">
								<?php echo numberFormat($rProduct->price); ?> <small>Tsh.</small>
							</div>
							<div class="product-rating">
								<?php echo getProductRate($rProduct->product_url); ?>
							</div>
							<p class="soldBy">Sold by: <a href="<?php echo base_url().'home/seller_collection/'.$rProduct->seller_id; ?>"><?php echo ucwords(getSellerFullName($rProduct->seller_id)); ?></a></p>
						</div>
					</div>
		            <?php }
		        } ?>
	    	</div>

	   	</div>
	</div>

	<!--Sample Video Model-->
	<div class="modal fade sample-video" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-md">
			<div class="modal-body">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="myModalLabel">Watch Sample</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
						 <video class="videoPreview" controls>
					  		<source src="<?php echo base_url().'media/products/preview/'.$product['preview']; ?>">
							Your browser does not support the video tag. Update your browser.
						</video> 
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="modalImagePreview" class="modal">
		<div class="image-preview-container">
			<div class="modal-content">
			    <div class="inner-prev-container">
			        <img id="img01" alt="">
			        <span class="close">&times;</span>
			        <span class="img-series"></span>
			    </div>
			</div>
			<a href="javascript:void(0);" class="inner-next"></a>
			<a href="javascript:void(0);" class="inner-prev"></a>
		</div>
		<div id="caption"></div>
	</div>
</body>
</html>
<script type="text/javascript">
	<?php
		echo 'var product="'.$product['id'].'";';
	?>
	var isPaused = false;
	$(document).ready(function(){
		load_reviews(product);
        var loadSR = setInterval(function(){ if(!isPaused) { load_reviews(product); } }, 20000);//20 Secs
	});
	function writeReview(){
		// $('#review-form').css('display','inline-block');
		$('#review-form').slideToggle();

		$("#popupPassword").keyup(function(event){
            if(event.keyCode == 13){
                var mValue=document.getElementById("popupPassword").value;
                if(mValue!=""){
                    $("#popupLogin").click();
                }
            }
        });
	}

	function displayReplyForm(review){
		isPaused = true;
		$('#replyForm'+review).slideToggle();
	}

	function getStarCounter(count){
		$('.star').removeClass('star_rate');
		$('.star').addClass('star_unrate');
		for (var i = 1; i <= count; i++) {
			$('#star'+i).removeClass('star_unrate');
			$('#star'+i).addClass('star_rate');
		}
		document.getElementById('star_counter').value=count;
		$('#starCounter').html(count);
	}
</script>