<!DOCTYPE html>
<html>
<head>
	<title><?=$title?></title>
</head>
<body>
	<section id="content" style="margin-top: 50px;">
		<div class="content-wrap">
			<div class="container clearfix">
				<div class="postcontent nobottommargin clearfix">
					<div class="single-post nobottommargin">

						<div class="entry clearfix break-all">
							<div class="entry-title">
								<h2><?php echo ucfirst($post['title']);?></h2>
							</div>
							<ul class="entry-meta clearfix">
								<li><i class="fa fa-calendar"></i> <?php echo date('M d, Y', $post['postedDate']); ?></li>
							</ul>
							<div class="entry-image">
								<?php if($post['image']!=""){ ?>
							    <img src="<?php echo base_url();?>media/help/<?php echo $post['image']; ?>" alt="<?php echo ucwords($post['title']); ?>">
								<?php } ?>
							</div>

							<div class="entry-content notopmargin">
								<p>
									<div class="penci-entry-content entry-content">
										<?php echo $post['content']; ?>
									</div>
								</p>
							</div>

						</div>

					</div>
				</div>
				<div class="sidebar nobottommargin col_last clearfix">
					<div class="sidebar-widgets-wrap">
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
								<a href="#" class="social-icon si-colored si-facebook" data-toggle="tooltip" data-placement="top" title="Facebook">
									<i class="fa fa-facebook"></i>
								</a>
								<a href="#" class="social-icon si-colored si-twitter" data-toggle="tooltip" data-placement="top" title="Twitter">
									<i class="fa fa-twitter"></i>
								</a>
								<a href="#" class="social-icon si-colored si-linkedin" data-toggle="tooltip" data-placement="top" title="LinkedIn">
									<i class="fa fa-linkedin"></i>
								</a>
								<a href="#" class="social-icon si-colored si-instagram" data-toggle="tooltip" data-placement="top" title="Instagram">
									<i class="fa fa-instagram"></i>
								</a>
								<a href="#" class="social-icon si-colored si-youtube" data-toggle="tooltip" data-placement="top" title="YouTube">
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
						                                <span class="stars-container stars-50" >★★★★★</span> (2.5)
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
		echo 'var post="'.$post['id'].'";';
	?>
	var isPaused = false;
	$(document).ready(function(){
		load_comment(post);
        var loadSR = setInterval(function(){ if(!isPaused) { load_comment(post); } }, 20000);//20 Secs
	});
	function writeComment(){
		$('#comment-form').slideToggle();
	}

	function displayReplyForm(comment){
		isPaused = true;
		$('#replyForm'+comment).slideToggle();
	}
</script>