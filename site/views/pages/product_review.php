<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title; ?> - GetValue</title>
	<style type="text/css">
		#review-form{
			display: block;
		}
	</style>
</head>
<body>
	<div class="container" id="view-product">
	    <div class="row">
	        <div class="col-md-9">
	            <div class="row">
	                <div class="col-sm-5">
	                    <div style="margin-bottom:20px;">
	                        <img src="<?php echo base_url().'media/products/thumb/'.$product['image']; ?>" style="width:auto; height:auto;" data-num="0" class="other-img-preview img-responsive img-sl the-image" alt="Biashara & Ujasilimia Mali">
	                    </div>
	                    <div class="row"></div>
	                </div>
	        		<div class="col-sm-7">
	            		<h1>Review - <?php echo ucwords($product['name']); ?></h1>
			            <div class="row row-info">
			                <div class="col-sm-12">
			                    <p>
			                        Ratings <span class="stars-container stars-50" >★★★★★</span> (2.5)
			                    </p>
			                </div>
			            </div>
			            <div class="row row-info">
			                <div class="col-sm-6"><b>Price:</b></div>
			                <div class="col-sm-6"><?php echo numberFormat($product['price']); ?></div>
			                <div class="col-sm-12 border-bottom"></div>
			            </div>
			            <div class="row row-info">
			                <div class="col-sm-6"><b><?php echo ucwords($product['seller_type']); ?>:</b></div>
			                <div class="col-sm-6"><a href="<?php echo base_url().'home/seller_collection/'.$product['seller_id']; ?>"><?php echo ucwords(getSellerFullName($product['seller_id'])); ?></a></div>
			                <div class="col-sm-12 border-bottom"></div>
			            </div>
                        <div class="row row-info">
		                    <div class="col-sm-6"><b>Added to shop:</b></div>
		                    <div class="col-sm-6"><?php echo date("M d, Y", $product['createdDate']); ?></div>
		                    <div class="col-sm-12 border-bottom"></div>
		                </div>
                        <div class="row row-info">
			                <div class="col-sm-6"><b>In category:</b></div>
			                <div class="col-sm-6">
			                    <a href="javascript:void(0);" class="btn-blue-round" data-categorie-id="4">
			                     <?php echo ucwords(getCategoryName($product['category'])); ?></a>
			                </div>
			                <div class="col-sm-12 border-bottom">

			                </div>
			            </div>
			            <div class="row row-info">
			                <div class="col-sm-6"><b>Sample</b></div>
			                <div class="col-sm-6">
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
			                <div class="col-sm-12 border-bottom"></div>
			            </div>

			            <div class="row" style="margin-top:50px;">
					        <ul class="nav nav-tabs product-tabs">
					            <li class="active"><a data-toggle="tab" href="#menu2">Reviews</a></li>
					        </ul>
					        <div class="tab-content product-tab-content">
					            <div id="menu2" class="tab-pane fade in active" style="margin-top:40px;">
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
					                	Give this product star...
					                    <p class="starRate">
					                    	<a href="javascript:void()" class="star star_unrate" id="star1" onclick="getStarCounter(1);"><i class="fa fa-star"></i></a>
					                    	<a href="javascript:void()" class="star star_unrate" id="star2" onclick="getStarCounter(2);"><i class="fa fa-star"></i></a>
					                    	<a href="javascript:void()" class="star star_unrate" id="star3" onclick="getStarCounter(3);"><i class="fa fa-star"></i></a>
					                    	<a href="javascript:void()" class="star star_unrate" id="star4" onclick="getStarCounter(4);"><i class="fa fa-star"></i></a>
					                    	<a href="javascript:void()" class="star star_unrate" id="star5" onclick="getStarCounter(5);"><i class="fa fa-star"></i></a>
			                               	(<span id="starCounter">...</span>)
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
					                    	<a href="javascript:void();" class="btn btn-sm btn-success turnOnReviewProgress" onclick="sendReview('<?php echo $product['id']; ?>');">Submit</a>
		                    				<a class="btn btn-sm btn-success progressBarReviewBtn"><i class="fa fa-spinner fa-spin"></i> Posting...</a>
					                    </form>
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

			            		</div>
			        		</div>
			    		</div>

	        		</div>
	    		</div>
			    
	</div>
	 <!-- Sidebar
	============================================= -->
		<div class="sidebar nobottommargin col_last clearfix">
				
			<div class="sidebar-widgets-wrap">
			    
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
										<li class="color"><?php echo numberFormat($pProduct->price); ?></li>
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

		$("#popupPassword").keyup(function(event){
            if(event.keyCode == 13){
                var mValue=document.getElementById("popupPassword").value;
                if(mValue!=""){
                    $("#popupLogin").click();
                }
            }
        });
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