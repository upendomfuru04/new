<!DOCTYPE html>
<html>
<head>
	<title>GetValue, Inc | A Global Online Digital Information Products Retailer</title>
</head>
<body>
	<?php if (count($sliderProducts) > 0) { ?>
	    <div id="home-slider" class="carousel slide" data-ride="carousel">
	        <ol class="carousel-indicators">
	            <?php
	            $i = 0;
	            while ($i < count($sliderProducts)) {
	                ?>
	                <li data-target="#home-slider" data-slide-to="0" class="<?= $i == 0 ? 'active' : '' ?>"></li>
	                <?php
	                $i++;
	            }
	            ?>
	        </ol>
	        <div class="container-fluid">
	            <div class="carousel-inner" role="listbox">
	                <?php
	                $i = 0;
	                foreach ($sliderProducts as $article) {
	                    ?>
	                    <div class="item <?= $i == 0 ? 'active' : '' ?>">
	                        <div class="row">
	                            <div class="col-sm-6 col-md-5 left-side slider-pl">
	                                <a href="" class="">
	                                    <img src="<?= base_url('media/products/thumb/' . $article->image) ?>" class="img-responsive" alt="">
	                                </a>
	                            </div>
	                            <div class="col-sm-6 col-md-5 right-side">
	                                <h3 class="text-right">
	                                    <a href="">
	                                        <?= $article->name ?>
	                                    </a>
	                                </h3>
	                                <div class="description text-right">
	                                    <?= strip_tags($article->summary) ?>
	                                    
	                                    <br><span class="stars-container stars-23" >★★★★★</span> (4.5)
	                                </div>
	                                
	                                <div class="price text-right"><?= $article->price ?> <small>Tsh.</small></div>
	                                <div class="xs-center">

	                                    <a class="option add-to-cart" data-goto="" href="<?php echo base_url().'prod/'.$article->product_url; ?>" data-id="">
	                                        <img src="<?= base_url('assets/themes/icons/shopping-cart-icon-515.png') ?>" alt="">
	                                        Buy Now
	                                    </a>
	                                    <a class="option right-5" href="">
	                                        <img src="<?= base_url('assets/themes/icons/info.png') ?>" alt="">
	                                        Preview
	                                    </a>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                    <?php
	                    $i++;
	                }
	                ?>
	            </div>
	        </div>
	        <a class="left carousel-control" href="#home-slider" role="button" data-slide="prev"></a>
	        <a class="right carousel-control" href="#home-slider" role="button" data-slide="next"></a>
	    </div>
	<?php } ?>

	<div class="ebooks">
		<div class="container-fluid clearfix">
		    <div class="col-md-8">
		        <div class="row">
    				<h2>EBOOKS</h2>
    				<div id="shop" class="shop grid-container clearfix" data-layout="fitRows">

    					<?php foreach($ebooks as $ebook){ ?>
						<div class="product clearfix">
							<div class="product-image">
								<a href="<?php echo base_url().'prod/'.$ebook->product_url; ?>"><img src="<?= base_url().'media/products/thumb/'.$ebook->image; ?>" alt=""></a>
							</div>
							<div class="product-desc">
								<div class="product-title"><h3><a href="<?php echo base_url().'prod/'.$ebook->product_url; ?>"><?php echo ucwords($ebook->name); ?></a></h3></div>
								<div class="product-price"><?php echo numberFormat($ebook->price); ?> <small>Tsh.</small></div>
								<div class="product-rating">
									<?php echo getProductRate($ebook->product_url); ?>
								</div>
								<p class="soldBy">Sold by: <a href="<?php echo base_url().'home/seller_collection/'.$ebook->seller_id; ?>"><?php echo ucwords(getSellerFullName($ebook->seller_id)); ?></a></p>
							</div>
						</div>
						<?php } ?>
    					<?php if(sizeof($popularBooks)>0){ ?>
							<br />
							<div class="row"><div class="col-md-12"></div></div>
							<a href="<?=base_url('ebooks')?>" class="btn btn-primary m-t-10">View All >></a>
						<?php } ?>
                    </div>
                </div>
            </div>
           
            <div class="col-md-4 pl30">
                <div class="row">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#bestseller"><h4>BEST SELLERS</h4></a></li>
                        <li><a data-toggle="tab" href="#home"><h4>TRENDING</h4></a></li>
                    </ul>
                    <div class="tab-content trending">
                        <div id="bestseller" class="tab-pane fade in active">
                            <div id="post-list-footer">

                            	<?php foreach($popularBooks as $pProduct){ ?>
									<div class="spost clearfix">
										<div class="entry-image popular">
											<a href="<?php echo base_url().'prod/'.$pProduct->product_url; ?>"><img src="<?= base_url().'media/products/thumb/'.$pProduct->image; ?>" alt=""></a>
										</div>
										<div class="entry-c">
											<div class="bestseller_label" style="width: 90px !important; font-size: 11px; margin-top: 0px; padding-top: 2px; padding-bottom: 2px; padding-left: 4px;">Best Seller</div>
											<div class="entry-title">
												<h4><a href="<?php echo base_url().'prod/'.$pProduct->product_url; ?>"><?php echo ucwords($pProduct->name); ?></a></h4>
											</div>
											<ul class="entry-meta">
												<li class="color"><?php echo numberFormat($pProduct->price); ?> Tsh.</li>
												<p>
	                                                <div class="product-rating">
	                    								<?php echo getProductRate($pProduct->product_url); ?>
	                    							</div>
	                                            </p>
												<p>Sold by: <a href="<?php echo base_url().'home/seller_collection/'.$pProduct->seller_id; ?>"><?php echo ucwords(getSellerFullName($pProduct->seller_id)); ?></a></p>
											</ul>
										</div>
									</div>
								<?php } ?>
								<?php if(sizeof($popularBooks)>0){ ?>
									<br />
									<a href="<?=base_url('home/products?filter=best_seller')?>" class="btn btn-primary">View All >></a>
								<?php } ?>
        							
        					</div>
                        </div>
                        <div id="home" class="tab-pane fade">
                            <ul>
                            	<?php $t_counter=0; foreach($trendingProducts as $tProducts){ $t_counter++; ?>
								<li><span style="height: 18px; overflow: hidden; display: inline-block;"><b class="text-orange">#<?=$t_counter?></b> <a href="<?php echo base_url().'prod/'.$tProducts->product_url; ?>"><?php echo ucwords($tProducts->name); ?></a></span></li>
								<?php } ?>
				            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
        
    <div class="insiders">
		<div class="container-fluid">
			<h2>INSIDER'S POPULAR POST</h2>
			<?php foreach($insiderPopulars as $insiderPopular){ ?>
			<div class="col-md-3">
				<div class="">
					<div class="article">
						<a href="<?php echo base_url().'home/blog_post/'.$insiderPopular->url; ?>"><img class="img-responsive" src="<?php echo base_url();?>media/blog/<?php echo $insiderPopular->image; ?>" alt="<?php echo ucfirst($insiderPopular->title); ?>">
							<div class="overlay">
								<div class="slider-post-meta">
									<div class="text-overlay-title">
										<p>
										    <?php echo ucfirst($insiderPopular->title); ?> <br> 
										    <small class="text-gold"><i class="fa fa-eye"></i> <?php echo $insiderPopular->views; ?> </small>
										    <small class="text-gold"><i class="fa fa-comments-o"></i> <?php echo getPostCommentCounter($insiderPopular->id); ?></small>
									    </p>
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>

	<div class="audiobooks">
		<div class="container-fluid clearfix">
			<h2>AUDIOBOOKS</h2>
			<div id="shop" class="shop grid-container clearfix" data-layout="fitRows">
				<?php foreach($audiobooks as $audiobook){ ?>
				<div class="product clearfix">
					<div class="product-image">
						<a href="<?php echo base_url().'prod/'.$audiobook->product_url; ?>"><img src="<?= base_url().'media/products/thumb/'.$audiobook->image; ?>" alt=""></a>
					</div>
					<div class="product-desc">
						<div class="product-title"><h3><a href="<?php echo base_url().'prod/'.$audiobook->product_url; ?>"><?php echo ucwords($audiobook->name); ?></a></h3></div>
						<div class="product-price"><?php echo numberFormat($audiobook->price); ?> <small>Tsh.</small></div>
						<div class="product-rating">
							<?php echo getProductRate($audiobook->product_url); ?>
						</div>
						<p>Sold by: <a href="<?php echo base_url().'home/seller_collection/'.$audiobook->seller_id; ?>"><?php echo ucwords(getSellerFullName($audiobook->seller_id)); ?></a></p>
					</div>
				</div>
				<?php } ?>
				<?php if(sizeof($popularBooks)>0){ ?>
					<br />
					<div class="row"><div class="col-md-12"></div></div>
					<a href="<?=base_url('audiobooks')?>" class="btn btn-primary m-t-10">View All >></a>
				<?php } ?>
            </div>
        </div>
    </div>
        
    <div class="insiders">
		<div class="container-fluid">
			<h2>CONTRIBUTOR'S POPULAR POST</h2>
			<?php foreach($contributorPopulars as $contributorPopular){ ?>
			<div class="col-md-3">
				<div class="">
					<div class="article contrbp">
						<a href="<?php echo base_url().'home/blog_post/'.$contributorPopular->url; ?>"><img class="img-responsive" src="<?php echo base_url();?>media/blog/<?php echo $contributorPopular->image; ?>" alt="<?php echo ucfirst($contributorPopular->title); ?>">
							<div class="overlay">
								<div class="slider-post-meta">
									<div class="text-overlay-title">
										<p>
										    <?php echo ucfirst($contributorPopular->title); ?> <br> 
										    <small class="text-gold"><i class="fa fa-eye"></i> <?php echo $contributorPopular->views; ?> </small>
										    <small class="text-gold"><i class="fa fa-comments-o"></i> <?php echo getPostCommentCounter($contributorPopular->id); ?></small>
									    </p>
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>

    <div class="trainings">
		<div class="container-fluid clearfix">
			<h2>ONLINE TRAININGS & PROGRAMS</h2>
			<div id="shop" class="shop grid-container clearfix" data-layout="fitRows">
				<?php foreach($onlineTrainings as $onlineTraining){ ?>
				<div class="product clearfix">
					<div class="product-image">
						<a href="<?php echo base_url().'prod/'.$onlineTraining->product_url; ?>"><img src="<?= base_url().'media/products/thumb/'.$onlineTraining->image; ?>" alt=""></a>
					</div>
					<div class="product-desc">
						<div class="product-title"><h3><a href="<?php echo base_url().'prod/'.$onlineTraining->product_url; ?>"><?php echo ucwords($onlineTraining->name); ?></a></h3></div>
						<div class="product-price"><?php echo numberFormat($onlineTraining->price); ?> <small>Tsh.</small></div>
						<div class="product-rating">
							<span class="white-span"><?php echo getProductRate($onlineTraining->product_url); ?></span>
						</div>
						<p><span style="color: #fff;">Sold by:</span> <a href="<?php echo base_url().'home/seller_collection/'.$onlineTraining->seller_id; ?>"><?php echo ucwords(getSellerFullName($onlineTraining->seller_id)); ?></a></p>
					</div>
				</div>
				<?php } ?>
				<?php if(sizeof($popularBooks)>0){ ?>
					<br/>
					<div class="row"><div class="col-md-12"></div></div>
					<a href="<?=base_url('online_trainings')?>" class="btn btn-primary m-t-10">View All >></a>
				<?php } ?>
            </div>
        </div>
    </div>

    <div class="author-team">
        <div class="container-fluid clearfix" style="margin-bottom: 50px;">
            <h2>FEATURED INSIDERS & CONTRIBUTORS</h2>
            <?php 
            	foreach($featuredInsConts as $featuredInsCont){
            		$seller_type=""; $avatar="default.png";
            		if($featuredInsCont->is_insider==1 && $featuredInsCont->is_contributor==1){
            			$seller_type='Insider & Contributor';
            		}
            		if($featuredInsCont->is_insider==1){
            			$seller_type='Insider';
            		}
            		if($featuredInsCont->is_contributor==1){
            			$seller_type='contributor';
            		}
            ?>
            <div class="col-md-3">
                <div class=" row team">       
                    <div class="team-image">
                        <center><img class="img-responsive" src="<?php echo base_url().'media/'.getSellerBrandAvatar($featuredInsCont->user_id); ?>" alt="<?php echo ucwords($featuredInsCont->full_name); ?>"></center>
                    </div>
                    <div class="team-desc">
                        <div class="team-title"><h4><?php echo ucwords($featuredInsCont->full_name); ?></h4><span><?php echo ucwords($seller_type); ?></span></div>
                        <p class="fsocial"><?php echo getInsContSocial($featuredInsCont->user_id); ?></p>
                    </div>
                </div>
            </div>
        	<?php } ?>
        </div>
    </div>
        
        <div class="gaston">
                        
            <div class="container-fluid clearfix">
            	
	                <div class="col-md-4">
	                                
	                    <h4 class="clearfix">February Issue</h4>
	                                
	                    <p class="inside">see inside >></p>

	                    <img src="<?php echo base_url(); ?>media/book-cover.jpg" class="img-responsive" alt="">

	                    <a href="#" class="btn btn-success"><i class="icon-shopping-cart"></i> BUY</a>

	                    <a href="#" class="btn btn-success" style="float: right;">MORE</a>

	                </div>

	                <div class="col-md-8">
	                	<?php foreach($homeBlogs as $homeBlog){ ?>
	                    <div class="gaston-article">
	                        <h5><?php echo ucfirst($homeBlog->title); ?></h5>
	                        <!-- <p><?php //echo ucfirst(get_words($homeBlog->content, 30)); ?></p> -->
	                        <p><?php echo ucfirst(get_words($homeBlog->summary, 30)); ?></p>
	                        <a href="<?php echo base_url().'home/blog_post/'.$homeBlog->url; ?>" class="btn btn-success">Read more</a>
	                    </div>
	                	<?php } ?>
	                </div>

            </div>

        </div>
</body>
</html>
<div class="clear"></div>
<?php ?>