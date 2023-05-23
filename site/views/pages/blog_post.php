<?php
	if($post['title']=="" || !isset($post['title'])){
		redirect_back("");
	}
?>
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

						<div class="entry clearfix">
							<div class="entry-title">
								<h2><?php echo ucfirst($post['title']);?></h2>
							</div>
							<ul class="entry-meta clearfix">
								<li><i class="fa fa-calendar"></i> <?php echo date('M d, Y', $post['postedDate']); ?></li>
								<li><i class="fa fa-folder-open"></i> <?php echo ucwords($post['category']);?></li>
							</ul>
							<ul class="entry-meta clearfix">
								<li>
									<?php 
										$poster=""; $avatar="";
										if($post['seller_type']=='admin'){
											$admin=getAdminInfo($post['postedBy']);
											$poster=ucwords($admin['name']);
											$avatar=base_url().'media/admin_avatars/'.$admin['avatar'];
										}else{
											$sellerInfo=getSellerInfo($post['postedBy']);
											$poster=ucwords($sellerInfo['name']);
											$avatar=base_url().'media/'.$sellerInfo['avatar'];
										}
									?>
									By: <img src="<?php echo $avatar; ?>" class="blog-thumb img-circle img-thumbnail" />
									 <?php echo $poster; ?>
								</li>
							</ul>
							<div class="entry-image">
							    <img src="<?php echo base_url();?>media/blog/<?php echo $post['image']; ?>" alt="<?php echo ucwords($post['title']); ?>">
							</div>

							<div class="entry-content notopmargin">
								<p>
									<div class="penci-entry-content entry-content">
										<?php //echo $post['content']; ?>
										<p class="text-justify"><?php echo preg_replace('#(<br */?>\s*)+#i', '<br />', htmlspecialchars_decode(stripslashes($post['content']), ENT_QUOTES)); ?></p>
									</div>
								</p>
							</div>

						</div>
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
							<!-- Comment area -->
							<p class="starRate">
								Ratings: <?php echo getPostRated($post['id']); ?>
		                        <!-- Ratings: <span class="stars-container stars-50" >★★★★★</span> (2.5)  -->
		                         <a class="float-right btn btn-outline-primary ml-2" href="javascript:void();" onclick="writeComment();"> <i class="fa fa-reply"></i> Leave a Comment</a>
		                    </p>
		                    <?php 
		                    	if(!isLoggedIn()){
		                    		echo '<p><small>You must login to post your comment.</small></p>';
		                    	}
		                   	?>
		                    <form method="POST" id="comment-form">
		                    	<div class="form-group">
		                    		<label>Comment</label>
		                    		<input type="hidden" name="post" value="<?php echo $post['id']; ?>">
				                    <input type="hidden" name="rate_counter" id="star_counter" value="">
		                    		<textarea class="form-control" name="comment"></textarea>
		                    	</div>
		                    	<div class="row"><div class="col-md-12" id="resultMsgReview"></div></div>
		                    	<a href="javascript:void();" class="btn btn-sm btn-success turnOnReviewProgress" onclick="sendComment('<?php echo $post['id']; ?>');">Post</a>
                				<a class="btn btn-sm btn-success progressBarReviewBtn"><i class="fa fa-spinner fa-spin"></i> Posting...</a>
		                    </form>
		                    <!-- </center> -->
		                    <hr >
		                    <div class="card">
		                        <div class="card-body">
		                            <div class="container-fluid">
			                            <div class="row">
				                            <div class="col-sm-9" style="border: 2px solid white; border-radius: 5px;" id="commentArea"></div>
				                            <div class="col-sm-3"></div>
				                        </div>
			                        </div> 
		                        </div>
		                    </div>
						</div>

					</div>
				</div>
				<div class="sidebar nobottommargin col_last clearfix">
					<div class="sidebar-widgets-wrap">
					    <div class="sideAds">
    			    	    <a href="https://www.getvalue.co/register" target="_blank">
    			    		    <img src="<?php echo base_url().'media/ads/affiliate-program.jpg'; ?>">
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