<?php 
	// die(uri_string());
	// die(basename($_SERVER['PHP_SELF']));
	// die($_SESSION['redirect_back']);
	if(isset($_SESSION['getvalue_user_idetification']) && $_SESSION['getvalue_user_idetification']!=""){ 
		// header("Location: ./");
		redirect_back();
	}
	$vendor_url="";
	if(isset($_GET['vref']) && $_GET['vref']!=""){
		$vendor_url=$_GET['vref'];
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>GetValue || Register</title>
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
				    <div class="col-md-5 register-login">
					    <h2>Welcome!</h2>
					    <h4>Do you have an account</h4>
					    <a href="<?php echo base_url(); ?>login" class="button button-3d button-black nomargin">Login here</a>
					    <p class="m-t-20"></p>
					</div>

					<div class="col-md-7">
						<div class="login">
							<?php
								if($vendor_url!=""){
									echo form_open('users/register?vref='.$vendor_url,array('method'=>'post'));
								}else{
									echo form_open('users/register',array('method'=>'post'));
								}
							?>
								<h3>Create Seller Account</h3>
								<div class="row">
									<div class="col-md-12"><?php echo $this->session->flashdata('feedback');?></div>
									<div class="form-group col-md-4">
										<input type="hidden" name="vendor_url" value="<?php if($vendor_url!=""){ echo $vendor_url; }elseif(isset($vendorUrl)){ echo $vendorUrl;} ?>" />
										<label>Account Type:</label>
										<select class="form-control" name="account_type">
											<!-- <option value="customer" <?php //if(set_value('account_type')=='customer'){ echo 'selected'; } ?>>Customer</option> -->
											<option value="vendor" <?php if(set_value('account_type')=='vendor'){ echo 'selected'; } ?>>Vendor</option>
											<option value="insider" <?php if(set_value('account_type')=='insider'){ echo 'selected'; } ?>>Affiliate - Insider</option>
											<option value="outsider" <?php if(set_value('account_type')=='outsider'){ echo 'selected'; } ?>>Affiliate - Outsider</option>
											<option value="contributor" <?php if(set_value('account_type')=='contributor'){ echo 'selected'; } ?>>Affiliate - Contributor</option>
										</select>
										<?php echo form_error('account_type'); ?>
									</div>
									<div class="form-group col-md-4">
										<label>First Name:</label>
										<input type="text" name="fname" value="<?php echo set_value('fname'); ?>" class="form-control" />
										<?php echo form_error('fname'); ?>
									</div>
									<div class="form-group col-md-4">
										<label>Surname:</label>
										<input type="text" name="sname" value="<?php echo set_value('sname'); ?>" class="form-control" />
										<?php echo form_error('sname'); ?>
									</div>
									<div class="form-group col-md-4">
										<label>Gender:</label>
										<select class="form-control" name="gender">
											<option value="">Select</option>
											<option value="male" <?php if(set_value('gender')=='male'){ echo 'selected'; } ?>>Male</option>
											<option value="female" <?php if(set_value('gender')=='female'){ echo 'selected'; } ?>>Female</option>
										</select>
										<?php echo form_error('gender'); ?>
									</div>
									<div class="form-group col-md-4">
										<label>Phone:</label>
										<input type="text" name="phone" value="<?php echo set_value('phone'); ?>" class="form-control" placeholder="+25575xxxxxxx" />
										<?php echo form_error('phone'); ?>
									</div>
									<div class="form-group col-md-4">
										<label>Email:</label>
										<input type="email" name="email" value="<?php echo set_value('email'); ?>" class="form-control" placeholder="youremail@domain.com" />
										<?php echo form_error('email'); ?>
									</div>
									<div class="form-group col-md-6">
										<label>Password:</label>
										<input type="password" name="password" value="<?php echo set_value('password'); ?>" class="form-control" placeholder="************" />
										<?php echo form_error('password'); ?>
									</div>
									<div class="form-group col-md-6">
										<label>Re-Enter Password:</label>
										<input type="password" name="pass_repeat" value="<?php echo set_value('pass_repeat'); ?>" class="form-control" placeholder="************" />
										<?php echo form_error('pass_repeat'); ?>
									</div>
									<div class="col-md-12"><p>Note: Username is your email address.</p></div>
									<div class="clear"></div>
									<div class="col-md-12 p-t-10">
										<button type="submit" class="button button-3d nomargin" id="register-form-submit" name="register-form-submit" value="signup">Register Now</button>
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