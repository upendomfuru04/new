<!DOCTYPE html>
<html>
<head>
	<title>Search Results - GetValue</title>
</head>
<body>
	<div class="audiobooks">
		<div class="container clearfix">
			<?php if(sizeof($product_results)){ ?>
			<h2>PRODUCT RESULTS</h2>
			<div id="shop" class="shop grid-container clearfix" data-layout="fitRows">
				<?php foreach($product_results as $product_result){ ?>
				<div class="product clearfix">
					<div class="product-image">
						<a href="<?php echo base_url().'prod/'.$product_result->product_url; ?>"><img src="<?= base_url().'media/products/thumb/'.$product_result->image; ?>" alt=""></a>
					</div>
					<div class="product-desc">
						<div class="product-title"><h3><a href="<?php echo base_url().'prod/'.$product_result->product_url; ?>"><?php echo ucwords($product_result->name); ?></a></h3></div>
						<div class="product-price"><?php echo numberFormat($product_result->price); ?> Tsh.</div>
						<div class="product-rating">
							<?php echo getProductRate($product_result->product_url); ?>
						</div>
						<p>Sold by: <a href="<?php echo base_url().'home/seller_collection/'.$product_result->seller_id; ?>"><?php echo ucwords(getSellerFullName($product_result->seller_id)); ?></a></p>
					</div>
				</div>
				<?php } ?>
            </div>
			<?php }else{ echo '<p>No product results found...</p>';} ?>
        </div>
    </div>
    <div class="insiders">
		<div class="container">
			<?php if(sizeof($post_results)){ ?>
			<h2>BLOG RESULTS</h2>
			<?php foreach($post_results as $insiderPopular){ ?>
			<div class="col-md-3">
				<div class="">
					<div class="article">
						<a href="<?php echo base_url().'home/blog_post/'.$insiderPopular->url; ?>"><img class="img-responsive" src="<?php echo base_url();?>media/blog/<?php echo $insiderPopular->image; ?>" alt="<?php echo ucfirst($insiderPopular->title); ?>">
							<div class="overlay">
								<div class="slider-post-meta">
									<div class="text-overlay-title">
										<p>
										    <?php echo ucfirst($insiderPopular->title); ?> <br> 
										    <small><i class="fa fa-eye"></i> <?php echo $insiderPopular->views; ?> </small>
										    <small><i class="fa fa-comments-o"></i> <?php echo getPostCommentCounter($insiderPopular->id); ?></small>
									    </p>
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>
			<?php } ?>
			<?php }else{ echo '<p>No blog post results found...</p>';} ?>
		</div>
	</div>
</body>
</html>