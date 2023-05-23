<!DOCTYPE html>
<html>
<head>
    
</head>
<body>
	<h1 class="breadcumb"></h1>
    <div class="home-page">
        <div class="row p-t-20">
            <div class="col-md-7 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="ti-user fa-fw"></i> My Profile</h3>
                    </div>
                    <div class="panel-body p40">
                        <div id="container-by-month" style="min-width: 310px; min-height: 400px; margin: 0 auto;">
                            <?php echo form_open_multipart('admin/home/update_admin_profile',array('method'=>'post')); ?>
                                <div class="row">
                                    <div class="col-md-12"><?php echo $this->session->flashdata('feedback');?></div>
                                    <div class="form-group col-md-12">
                                        <center>
                                            <img src="<?php echo base_url();?>media/admin_avatars/<?php if($this->session->userdata('user_avatar')!=""){ echo $this->session->userdata('user_avatar');}else{ echo "default.png";} ?>" class="img-circle img-thumbnail avatarHolder m-b-10" id="img-current-holder1"/>
                                            <div class="col-md-12">
                                                <input type="file" name="avatar" id="avatarImg" style="display: none;"  accept="image/*"/>
                                                <label for="avatarImg" class="btn btn-sm btn-info waves-effect waves-light"><span>Change Image</span></label>
                                            </div>
                                        </center>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>First Name:</label>
                                        <input type="text" name="first_name" class="form-control" value="<?php echo $user_info['first_name']; ?>">
                                        <?php echo form_error('first_name'); ?>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Surname:</label>
                                        <input type="text" name="surname" class="form-control" value="<?php echo $user_info['surname']; ?>">
                                        <?php echo form_error('surname'); ?>
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
                                    <div class="col-md-12 p-t-10">
                                        <!-- <button type="submit" class="btn btn-success" name="register-form-submit" value="signup"><i class="ti-save"></i> Save</button> -->
                                        <input type="submit" name="save_profile" class="btn btn-success" value="Save">
                                    </div>
                                </div>
                            </form>
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