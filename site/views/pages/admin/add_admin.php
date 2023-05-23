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
                        <h3 class="panel-title"><i class="ti-user fa-fw"></i> Add System Admin</h3>
                    </div>
                    <div class="panel-body p40">
                        <div id="container-by-month" style="min-width: 310px; min-height: 400px; margin: 0 auto;">
                            <?php echo form_open_multipart('admin/account/save_new_system_admin',array('method'=>'post')); ?>
                                <div class="row">
                                    <div class="col-md-12"><?php echo $this->session->flashdata('feedback');?></div>
                                    <div class="form-group col-md-4">
                                        <label>First Name:</label>
                                        <input type="text" name="first_name" class="form-control" value="<?php echo set_value('first_name'); ?>">
                                        <?php echo form_error('first_name'); ?>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Surname:</label>
                                        <input type="text" name="surname" class="form-control" value="<?php echo set_value('surname'); ?>">
                                        <?php echo form_error('surname'); ?>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Gender:</label>
                                        <select class="form-control" name="gender">
                                            <option value="">Select</option>
                                            <option value="male" <?php if(set_value('gender')=='male'){ echo 'selected';}?>>Male</option>
                                            <option value="female" <?php if(set_value('gender')=='female'){ echo 'selected';}?>>Female</option>
                                        </select>
                                        <?php echo form_error('gender'); ?>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Phone:</label>
                                        <input type="text" name="phone" class="form-control" value="<?php echo set_value('phone'); ?>">
                                        <?php echo form_error('phone'); ?>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Email Address:</label>
                                        <input type="text" name="email" class="form-control" value="<?php echo set_value('email'); ?>">
                                        <?php echo form_error('email'); ?>
                                    </div>
                                    <div class="col-md-12">
                                        <p>Note: default password is surname in lowercase</p>
                                    </div>
                                    <div class="col-md-12 p-t-10">
                                        <!-- <button type="submit" class="btn btn-success" name="register-form-submit" value="signup"><i class="ti-save"></i> Save</button> -->
                                        <input type="submit" name="save_admin" class="btn btn-success" value="Save">
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