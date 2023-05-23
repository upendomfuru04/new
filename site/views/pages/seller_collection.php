<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title; ?> - GetValue</title>
</head>
<body>
	<section id="" style="margin-top:0px;">
		<div class="ebooks">
			<div class="container clearfix">
			    <div class="col-md-3">
			    	<div class="productSideBar">
			    		<h4>Categories</h4><hr>
			    		<ul class="m-0 p-l-10">
			    			<?php echo $listShopCategories; ?>
			    		</ul>
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
    				    <div class="profile-cover">
    				        <div class="profile-image">
    				            <!-- <img src="<?php //echo base_url().'media/avatar.png'; ?>"> -->
                                <?php
                                    if(isset($info['logo']) && $info['logo']!=""){
                                        echo '<img src="'.base_url().'media/shop/logo/'.$info['logo'].'" style="width:150px; height:150px;" data-num="0" class="other-img-preview img-responsive img-thumbnail img-circle img-sl the-image" alt="">';
                                    }elseif(isset($info['banner']) && $info['banner']!=""){
                                        echo '<img src="'.base_url().'media/shop/banner/'.$info['banner'].'" style="width:150px; height:150px;" data-num="0" class="other-img-preview img-responsive img-thumbnail img-circle img-sl the-image" alt="">';
                                    }else{
                                        $avatar='default.png';
                                        if(isset($info['logo']) && $info['logo']!=""){
                                            $avatar=$info['logo'];
                                        }
                                        echo '<img src="'.base_url().'media/seller_avatars/'.$avatar.'" style="width:150px; height:150px;" data-num="0" class="other-img-preview img-responsive img-thumbnail img-circle img-sl the-image" alt="">';
                                    }
                                ?>
    				        </div>
    				        <div class="profile-desc">
    				            <h2><?php echo $title; ?> Collection</h2>
    				            <p><?php if(isset($info['email']))echo $info['email']; ?> <span>Vendor</span></p>
    				            <div id="s-icons" class="widget quick-contact-widget clearfix vendor">
                    				
                    				<ul>
                                        <?php foreach($socials as $social){ ?>
                    				    <li>
                    				        <a href="<?php echo $social->link; ?>" class="social-icon si-colored si-<?php echo strtolower($social->name); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $social->name; ?>">
                    									<i class="fa fa-<?php echo strtolower($social->name); ?>"></i>
                    				        </a>
                    				    </li>
                    				    <!-- <li>
                    				        <a href="https://mobile.twitter.com/getvalueinc/" class="social-icon si-colored si-twitter" data-toggle="tooltip" data-placement="top" title="Twitter">
                            					<i class="fa fa-twitter"></i>
                            				</a>
                    				    </li>
                    				    <li>
                    				        <a href="https://www.linkedin.com/company/getvalueinc" class="social-icon si-colored si-linkedin" data-toggle="tooltip" data-placement="top" title="LinkedIn">
                            					<i class="fa fa-linkedin"></i>
                            				</a>
                    				    </li>
                    				    <li>
                    				        <a href="https://www.instagram.com/getvalueinc/" class="social-icon si-colored si-instagram" data-toggle="tooltip" data-placement="top" title="Instagram">
                            					<i class="fa fa-instagram"></i>
                            				</a>
                    				    </li>
                    				    <li>
                    				        <a href="https://m.youtube.com/channel/UCuSS0Qj54SWQKF1cYT-IOBQ" class="social-icon si-colored si-youtube" data-toggle="tooltip" data-placement="top" title="YouTube">
                            					<i class="fa fa-youtube"></i>
                            				</a>
                    				    </li> -->
                                        <?php } ?>
                    				</ul>
                    
                    			</div>
    				        </div>
    				        <div class="clearfix"></div>
    				    </div>
    					<div class="alert alert-info">Click <a href="<?php echo base_url(); ?>home/seller_profile/<?php echo $info['profile_url']; ?>">HERE</a> to View Seller Profile</div>
    					<?php foreach($products as $product){ ?>
    					<div class="product clearfix">
    						<div class="product-image">
    							<a href="<?php echo base_url().'prod/'.$product->product_url; ?>"><img src="<?= base_url().'media/products/thumb/'.$product->image; ?>" alt=""></a>
    						</div>
    						<div class="product-desc">
    							<div class="product-title"><h3 style="height: 50px !important; overflow: hidden !important;"><a href="<?php echo base_url().'prod/'.$product->product_url; ?>"><?php echo ucwords($product->name); ?></a></h3></div>
    							<div class="product-price"><?php echo numberFormat($product->price); ?></div>
    							<div class="product-rating">
    								<?php echo getProductRate($product->product_url); ?>
    							</div>
    							<p>Sold by: <a href=""><?php echo ucwords($info['full_name']); ?></a></p>
                                <?php if(strtolower($product->product_status)=='live'){ ?>
    							<a class="add-to-cart btn-add m-b-5" href="javascript:void();" onclick="addToCart('<?php echo $product->id; ?>', '<?php echo $product->price; ?>', '<?php echo $product->name; ?>');">Add To Cart <i id="progress<?php echo $product->id; ?>" class="progIc fa fa-spinner fa-spin"></i></a>
    							<!-- <a class="add-to-cart btn-add more-blue" href="javascript:void();" onclick="buyProduct('<?php echo $product->id; ?>', '<?php echo $product->price; ?>', '<?php echo $product->name; ?>');">Buy Now <i id="progress<?php echo $product->id; ?>" class="progIc fa fa-spinner fa-spin"></i></a> -->
                                <a class="add-to-cart btn-add more-blue" href="<?=base_url('home/checkout').'?product='.$product->id?>">Buy Now </a>
                                <?php }else{ ?>
                                <a class="add-to-cart btn-add cart-unactive m-b-5" href="javascript:void();">Add To Cart</a>
                                <a class="add-to-cart btn-add btn-danger" href="javascript:void();" onclick="buyProduct('<?php echo $product->id; ?>', '<?php echo $product->price; ?>', '<?php echo $product->name; ?>');">Pre-order Now <i id="progress<?php echo $product->id; ?>" class="progIc fa fa-spinner fa-spin"></i></a>
                                <?php } ?>
    						</div>
    					</div>
    					<?php } ?>
                    </div>

                    
                </div>
            </div>
        </div>
	</section>
</body>
</html>
<script type="text/javascript">
</script>