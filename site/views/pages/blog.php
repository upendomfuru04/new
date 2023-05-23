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
	<section id="content" style="margin-top:50px;">
		<div class="content-wrap">
			<div class="container clearfix">
				<div class="postcontent nobottommargin clearfix">
					<div id="posts" class="small-thumbs">
						<?php foreach($posts as $post){ ?>
						<div class="entry clearfix">
    						<div class="entry-image">
    						    <a href="<?php echo base_url().'home/blog_post/'.$post->url; ?>" data-lightbox="image">
    						        <img class="image_fade" src="<?php echo base_url();?>media/blog/<?php echo $post->image; ?>" alt="<?php echo ucwords($post->title); ?>">
    						    </a>
    						</div>
    						<div class="entry-c">
    							<div class="entry-title">
    								<h2><a href="<?php echo base_url().'home/blog_post/'.$post->url; ?>"><?php echo ucfirst($post->title); ?></a></h2>
    							</div>
    							<ul class="entry-meta clearfix">
    								<li>
										<?php 
											$poster="";
											if($post->seller_type=='admin'){
												$poster=ucwords(getAdminInfo($post->postedBy)['name']);
											}else{
												$poster=ucwords(getSellerFullName($post->postedBy));
											}
										?>
										By:
										<?php echo $poster; ?>
									</li>
    								<li><i class="fa fa-calendar"></i> <?php echo date('M d, Y', $post->postedDate); ?></li>
    								<li><i class="fa fa-folder"></i> <?php echo ucwords($post->category); ?></li>
    							</ul>
    							<div class="entry-content">
    								<p><?php echo ucfirst(get_words(htmlspecialchars_decode(stripslashes($post->content), ENT_QUOTES), 20)); ?></p>
    								<a href="<?php echo base_url().'home/blog_post/'.$post->url; ?>" class="more-link">Read More</a>
    							</div>
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
				    						if($total_posts>0){
				    							$pages=ceil($total_posts/$pg_limit);
				    							$current_page=floor($start_page/$pg_limit)+1;
				    							$p_count=0; $strt=0;
				    							for ($i=1; $i <= $pages; $i++) {
				    								$p_count++;
				    								if($current_page == $i){
				    									$pagination.='<li class="page-item active"><span class="page-link">'.$i.'<span class="sr-only">(current)</span></span></li>';
				    								}else{
				    									$pagination.='<li class="page-item"><a class="page-link" href="'.base_url('blog/'.$category.'?from='.(($strt*$pg_limit)+1).'&to='.($i*$pg_limit)).'">'.$i.'</a></li>';
				    								}
				    								$strt++;
				    							}
				    							if($current_page!=1){
				    								$dis_p="";
				    								$prev_url=base_url('blog/'.$category.'?from='.($start_page-$pg_limit).'&to='.($start_page-1));
				    							}
				    							if($current_page!=$p_count){
				    								$dis_n="";
				    								$next_url=base_url('blog/'.$category.'?from='.($end_page+1).'&to='.($end_page+$pg_limit));
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
				<div class="sidebar nobottommargin col_last clearfix">
					<div class="sidebar-widgets-wrap">
					    <div class="sideAds">
    			    	    <a href="https://www.getvalue.co/become_affiliate" target="_blank">
    			    		    <img src="<?php echo base_url().'media/ads/affiliate.jpg'; ?>">
    			    		</a>
    			    	</div>
						<div id="post-lists" class="widget clearfix">
							<h4 class="highlight-me">Recent Posts</h4>
							<div id="post-list-footer">
								<?php foreach($recent_posts as $rpost){ ?>
								<div class="spost clearfix">
	    							<div class="entry-image">
	    								<a href="<?php echo base_url().'home/blog_post/'.$rpost->url; ?>" class="nobg"><img src="<?php echo base_url();?>media/blog/<?php echo $rpost->image; ?>" alt="Image 1"></a>
	    							</div>
	    							<div class="entry-c">
	    								<div class="entry-title">
	    									<h4><a href="<?php echo base_url().'home/blog_post/'.$rpost->url; ?>"><?php echo ucfirst(get_words($rpost->title, 10)); ?></a></h4>
	    								</div>
	    								<ul class="entry-meta">
	    									<li><?php echo date('M d, Y', $rpost->postedDate); ?></li>
	    								</ul>
	    							</div>
	    						</div>
	    						<?php } ?>
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
													<p>
						                                <?php echo getProductRate($pProduct->product_url); ?>
						                            </p>
													<p>Sold by: <a href="<?php echo base_url().'home/seller_collection/'.$pProduct->seller_id; ?>"><?php echo ucwords(getSellerFullName($pProduct->seller_id)); ?></a></p>
												</ul>
											</div>
										</div>
									<?php } ?>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</body>
</html>