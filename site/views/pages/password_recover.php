<!DOCTYPE html>
<html>
<head>
	<title>Password Recover - GetValue</title>
</head>
<body>
	<div class="container">			          
		<section id="content" style="margin-top: 50px;">
			<div class="content-wrap">
				<div class="container clearfix">
				    <div class="col-md-8 register-login">
					    <h2>Welcome!</h2>
					    <p>
					    	Your new password will be sent to your email address.
					    </p>
					    <h4>Don't have and account?</h4>
					    <a href="<?php echo base_url(); ?>register" class="button button-3d button-black nomargin">Register here</a>
					    <p class="m-t-20"></p>
					</div>

					<div class="col-md-4">
						<div class="login">
							<?php echo form_open('/Users/recover_password',array('method'=>'post')); ?>
								<h3>Password Recovery</h3>
								<div class="col_full"><?php echo $this->session->flashdata('feedback');?></div>
								<div class="col_full">
									<label for="login-form-username">Username:</label>
									<input type="text" id="login-form-username" name="username" value="<?php echo set_value('username'); ?>" class="form-control" required="" autofocus="" placeholder="" />
								</div>
								<div class="col_full nobottommargin">
									<button class="button button-3d nomargin" type="submit" id="login-form-submit" name="login-form-submit" value="Recover">Recover</button>
									<a href="<?php echo base_url(); ?>/login" class="fright">Or Login</a>
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