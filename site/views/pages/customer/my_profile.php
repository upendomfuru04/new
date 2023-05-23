<!DOCTYPE html>
<html>
<head>
	<title>My Profile || GetValue</title>
</head>
<body>
	<div class="panel panel-info">
		<div class="panel-heading">
			<h2 class="panel-title">My Profile</h2>
		</div>
		<div class="panel-body row">
			<?php echo form_open_multipart('users/update_profile',array('method'=>'post')); ?>
                <div class="row col-md-8 col-md-offset-2">
                    <div class="col-md-12"><?php echo $this->session->flashdata('feedback');?></div>
                    <div class="form-group col-md-12">
                        <center>
                            <img src="<?php echo base_url();?>media/customer_avatars/<?php echo $this->session->userdata('user_avatar'); ?>" class="img-circle img-thumbnail avatarHolder m-b-10" id="img-current-holder1"/>
                            <div class="col-md-12">
                                <input type="file" name="avatar" id="avatarImg" style="display: none;"  accept="image/*"/>
                                <label for="avatarImg" class="btn btn-sm btn-info waves-effect waves-light"><span>Change Image</span></label>
                            </div>
                        </center>
                    </div>
                    <div class="form-group col-md-4">
                        <label>First Name:</label>
                        <input type="text" name="fname" class="form-control" value="<?php echo $user_info['first_name']; ?>">
                        <?php echo form_error('fname'); ?>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Surname:</label>
                        <input type="text" name="sname" class="form-control" value="<?php echo $user_info['surname']; ?>">
                        <?php echo form_error('sname'); ?>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Gender:</label>
                        <select class="form-control" name="gender">
                            <option value="">Select</option>
                            <option value="male" <?php if($user_info['gender']=='male'){ echo 'selected';}?>>Male</option>
                            <option value="female" <?php if($user_info['gender']=='female'){ echo 'selected';}?>>Female</option>
                        </select>
                        <?php echo form_error('gender'); ?>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Phone:</label>
                        <input type="text" name="phone" class="form-control" value="<?php echo $user_info['phone']; ?>">
                        <?php echo form_error('phone'); ?>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Email Address:</label>
                        <input type="text" name="email" class="form-control" value="<?php echo $user_info['email']; ?>">
                        <?php echo form_error('email'); ?>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Country:</label>
                        <input type="text" name="country" class="form-control" value="<?php echo $user_info['country']; ?>">
                        <?php echo form_error('country'); ?>
                    </div>
                    <div class="form-group col-md-6">
                        <label>City:</label>
                        <input type="text" name="city" class="form-control" value="<?php echo $user_info['city']; ?>">
                        <?php echo form_error('city'); ?>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Address:</label>
                        <input type="text" name="address" class="form-control" value="<?php echo $user_info['address']; ?>">
                        <?php echo form_error('address'); ?>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Post Code:</label>
                        <input type="text" name="post_code" class="form-control" value="<?php echo $user_info['post_code']; ?>">
                        <?php echo form_error('post_code'); ?>
                    </div>
                    <div class="col-md-12 p-t-10">
                        <button type="submit" class="btn btn-success" name="register-form-submit" value="signup"> Save</button>
                    </div>
                </div>
            </form>
		</div>
	</div>
</body>
</html>
<script type="text/javascript">
  $(document).ready(function(){
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#img-current-holder1').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#avatarImg").change(function(){
            readURL(this);
        });

  });
</script>