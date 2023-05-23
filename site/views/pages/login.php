<?php 
	if(isset($_SESSION['getvalue_user_idetification']) && $_SESSION['getvalue_user_idetification']!=""){ 
		header("Location: ./");
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>GetValue || Login</title>
</head>
<body>
	<div class="container">
		<div class="row">
			<?php
			   //if ($this->session->flashdata('message') != '') echo '<div class="alert alert-success" role="alert">' . $this->session->flashdata('message') . '</div>';
			 ?>
		</div>
			          
		<section id="content" style="margin-top: 50px;">
			<div class="content-wrap">
				<div class="container clearfix">
				    <div class="col-md-8 register-login">
					    <h2>Welcome!</h2>
					    <h4>Don't have and account?</h4>
					    <p></p>
					    <a href="<?php echo base_url(); ?>register" class="button button-3d button-black nomargin btn-primary">Seller Registration</a>
					    <p class="m-t-20"></p>
					    <a href="javascript:void();" data-toggle="modal" data-target="#register_popup" class="button button-3d button-black nomargin">Buyer Registration</a>
					    <p class="m-t-20"></p>
					</div>

					<div class="col-md-4">
						<div class="login">
							<?php echo form_open('users/login', array('method'=>'post')); ?>
								<h3>Or login to your Account</h3>
								<div class="row">
									<div class="col-md-12"><?php echo $this->session->flashdata('feedback');?></div>
									<div class="form-group col-md-12">
										<label for="login-form-username">Username:</label>
										<input type="text" id="login-form-username" name="username" value="" class="form-control" required="" autofocus="" placeholder="Email address" />
										<?php echo form_error('username'); ?>
										<!-- <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" /> -->
									</div>
									<div class="form-group col-md-12">
										<label for="login-form-password">Password:</label>
										<input type="password" id="password" name="password" value="" class="form-control" required placeholder="" />
										<?php echo form_error('password'); ?>
									</div>
									<div class="form-group col-md-12 nobottommargin">
										<button class="button button-3d nomargin" type="submit" id="login-form-submit" name="login-form-submit" value="login">Login</button>
										<a href="<?php echo base_url(); ?>password_recover" class="fright">Forgot Password?</a>
									</div>
								</div>
							</form>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</body>
</html>