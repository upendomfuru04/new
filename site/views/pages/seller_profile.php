<?php
	if(sizeof($profile)==0){
		header("Location: ".base_url());
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title; ?> - GetValue</title>
</head>
<body>
	<div class="container" id="view-product">
	    <div class="row">
	        <div class="col-md-9">
	            <div class="row">
	                <div class="col-sm-5">
	                    <div style="margin-bottom:20px;">
	                    	<?php
	                    		if($profile['logo']!=""){
	                    			echo '<img src="'.base_url().'media/shop/logo/'.$profile['logo'].'" style="width:auto; height:auto;" data-num="0" class="other-img-preview img-responsive img-sl the-image" alt="">';
	                    		}elseif($profile['banner']!=""){
	                    			echo '<img src="'.base_url().'media/shop/banner/'.$profile['banner'].'" style="width:auto; height:auto;" data-num="0" class="other-img-preview img-responsive img-sl the-image" alt="">';
	                    		}else{
	                    			$avatar='default.png';
	                    			if($profile['logo']!=""){
	                    				$avatar=$profile['logo'];
	                    			}
	                    			echo '<img src="'.base_url().'media/seller_avatars/'.$avatar.'" style="width:auto; height:auto;" data-num="0" class="other-img-preview img-responsive img-sl the-image" alt="">';
	                    		}
	                    	?>
	                        
	                    </div>
	                    <div class="row"></div>
	                </div>
	        		<div class="col-sm-7">
	            		<h1><?php if($profile['brand']!=""){ echo ucwords($profile['brand']);}else{ echo ucwords($profile['full_name']);} ?></h1>
			            <div class="row row-info">
			                <div class="col-sm-12 starTxt">
			                    <p></p>
			                </div>
			            </div>
			            <div class="row row-info">
			            	<?php 
			            		if($profile['brand']!=""){

			            		}else{
			            			echo '<div class="col-sm-6"><b>Gender:</b></div>';
			            			echo '<div class="col-sm-6">'.ucwords($profile['gender']).'</div>';
			            		}
			            	?>
			                <div class="col-sm-12 border-bottom"></div>
			            </div>
                        <div class="row row-info">
			                <div class="col-sm-6"><b>Email:</b></div>
			                <div class="col-sm-6"><?php echo $profile['email']; ?></div>
			                <div class="col-sm-12 border-bottom"></div>
			            </div>
                        <div class="row row-info">
			                <div class="col-sm-6"><b>Account type(s):</b></div>
			                <div class="col-sm-6">
			                     <?php
			                     	if($profile['is_vendor']==1)
			                     		echo '<a href="javascript:void(0);" class="m-t-10 btn-blue-round" data-categorie-id="4">Vendor</a>';
			                     	if($profile['is_insider']==1)
			                     		echo '<a href="javascript:void(0);" class="m-t-10 btn-blue-round" data-categorie-id="4">Insider</a>';
			                     	if($profile['is_outsider']==1)
			                     		echo '<a href="javascript:void(0);" class="m-t-10 btn-blue-round" data-categorie-id="4">Outsider</a>';
			                     	if($profile['is_contributor']==1)
			                     		echo '<a href="javascript:void(0);" class="m-t-10 btn-blue-round" data-categorie-id="4">Contributor</a>';
			                     ?>
			                </div>
			                <div class="col-sm-12 border-bottom"></div>
			            </div>

	        		</div>
	    		</div>
	</div>

	</div>
	</div>

</body>
</html>
<script type="text/javascript">
	$(document).ready(function(){

	});
</script>