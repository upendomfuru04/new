<!DOCTYPE html>
<html>
<head>
	<title>Account Activation || GetValue</title>
</head>
<body>
	<div class="container">			          
		<section id="content" style="margin-top: 50px;">
			<div class="content-wrap">
				<div class="container clearfix">
				    <div class="col-md-12 register-login">
					    <h2>Account Activation!</h2>
					    <p>
					    	<?php
					    		if(isset($tag) && $tag=='Success'){
					    			echo 'Congratulation! your account is activated successfully';
					    			echo '<br><br><a href="'.base_url().'login" class="button button-3d button-black nomargin">Login here</a>';
					    		}else{
					    			echo 'Activation code not found or expired';
					    		}
					    	?>
					    </p>
					    <p class="m-t-20"></p>
					</div>
				</div>
			</div>
		</section>
	</div>
</body>
</html>