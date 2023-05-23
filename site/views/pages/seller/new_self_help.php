<!DOCTYPE html>
<html>
<head>
  
</head>
<body>
	<h1 class="breadcumb"></h1>
    <div class="home-page">
        <div class="row p-t-20">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="ti-list fa-fw"></i> Add/Update Help Post</h3>
                    </div>
                    <div class="panel-body p40">
                        <div id="container-by-month" style="min-width: 310px; min-height: 400px; margin: 0 auto;">
                            <form id="data-form" method="POST">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <center>
                                            <img src="<?php echo base_url();?>media/help/<?php if($update!=""){ echo $table_rows['image'];}else{ echo 'default.jpg'; } ?>" class="img-thumbnail avatarHolder m-b-10" id="img-current-holder1"/>
                                            <div class="col-md-12">
                                                <input type="file" name="image" id="avatarImg" style="display: none;"  accept="image/*" src="<?php echo set_value('image'); ?>" />
                                                <label for="avatarImg" class="btn btn-sm btn-info waves-effect waves-light"><span>Upload Image</span></label>
                                            </div>
                                        </center>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Category:</label>
                                        <select class="form-control" name="category" id="category" onchange="load_sub_category();">
                                          <option value="">Select</option>
                                          <?php
                                            $cat_e="";
                                            if($update!="")
                                            $cat_e=$table_rows['category'];
                                            foreach($categories as $cat){
                                              if($cat_e!="" && $cat_e==$cat->id){
                                                echo '<option value="'.$cat->id.'" selected>'.$cat->name.'</option>';
                                              }else{
                                                echo '<option value="'.$cat->id.'">'.$cat->name.'</option>';
                                              }
                                            }
                                          ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Sub-Category:</label>
                                        <select class="form-control" name="subcategory" id="sub_list">
                                          <option value="">Select</option>
                                          <?php 
                                          if($update!=""){
                                            $scat=$table_rows['sub_category'];
                                            foreach($subcategories as $cats){ ?>
                                                <option value="<?php echo $cats->id; ?>" <?php if($update!="" && $scat==$cats->id){ echo 'selected';} ?>><?php echo $cats->name; ?></option>
                                          <?php } } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Title:</label>
                                        <textarea name="title" class="form-control"><?php if($update!=""){ echo $table_rows['title'];} ?></textarea>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Description:</label>
                                        <textarea class="form-control summernote" name="content"><?php if($update!=""){ echo $table_rows['content'];} ?></textarea>
                                    </div>
                                    <div class="col-md-12" id="resultMsg"></div>
                                    <div class="col-md-12 p-t-10">
                                        <?php if($update!=""){ ?>
                                        <a href="javascript:void();" class="btn btn-success mr-2 turnOnProgress" id="updateBtn">Update</a>
                                        <?php }else{ ?>
                                        <a href="javascript:void();" class="btn btn-success mr-2 turnOnProgress" id="saveBtn">Save</a>
                                        <?php } ?>
                                        <a href="javascript:void();" class="btn btn-success mr-2 progressBarBtn"><i class="fa fa-spinner fa-spin"></i> Processing...</a>
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
        $('.summernote').summernote({
            height: 150, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: false // set focus to editable area after initializing summernote
        });

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

        $('#saveBtn').click(function(){
           $('.turnOnProgress').css('display','none');
           $('.progressBarBtn').css('display','inline-block');
           var formData = new FormData($('#data-form')[0]);
           $.ajax({
               url: '<?php echo base_url(); ?>seller/blog/save_self_help',
               type: 'POST',
               data: formData,
               async: true,
               cache: false,
               contentType: false,
               enctype: 'multipart/form-data',
               processData: false,
               success: function (data) {
                    if(data.trim()=='Success'){
                        var output = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> Success </div>'; 
                        $('#resultMsg').html(output);
                        document.getElementById("data-form").reset();
                        window.location="";
                    }else{
                        $('#resultMsg').html(data);
                    }
                    $('.turnOnProgress').css('display','inline-block');
                    $('.progressBarBtn').css('display','none');
                },
                complete: function() {
                    $('.turnOnProgress').css('display','inline-block');
                    $('.progressBarBtn').css('display','none');
                },
                error: function( xhr, status, error ) {
                    $('#resultMsg').html(error);
                    console.log('ajax loading error...');
                    console.log(error);
                    $('.turnOnProgress').css('display','inline-block');
                    $('.progressBarBtn').css('display','none');
                    return false;
                }
           });
        });

        $('#updateBtn').click(function(){
           $('.turnOnProgress').css('display','none');
           $('.progressBarBtn').css('display','inline-block');
           var formData = new FormData($('#data-form')[0]);
           $.ajax({
               url: '<?php echo base_url(); ?>seller/blog/update_self_help/<?php echo $update; ?>',
               type: 'POST',
               data: formData,
               async: true,
               cache: false,
               contentType: false,
               enctype: 'multipart/form-data',
               processData: false,
               success: function (data) {
                    if(data.trim()=='Success'){
                        var output = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> Success </div>'; 
                        $('#resultMsg').html(output);
                        document.getElementById("data-form").reset();
                        window.location="<?php echo base_url(); ?>seller/blog/self_help";
                    }else{
                        $('#resultMsg').html(data);
                    }
                    $('.turnOnProgress').css('display','inline-block');
                    $('.progressBarBtn').css('display','none');
                },
                complete: function() {
                    $('.turnOnProgress').css('display','inline-block');
                    $('.progressBarBtn').css('display','none');
                },
                error: function( xhr, status, error ) {
                    $('#resultMsg').html(error);
                    console.log('ajax loading error...');
                    console.log(error);
                    $('.turnOnProgress').css('display','inline-block');
                    $('.progressBarBtn').css('display','none');
                    return false;
                }
           });
        });

    });

  function load_sub_category(){
    var category = document.getElementById('category').value;
    $.ajax({
        url: '<?php echo base_url(); ?>seller/blog/load_sub_categories/'+category,
        type:"POST",
        success: function(data){
            $('#sub_list').html('<option value="">Select...</option>'+data);
        },
        complete: function() {
        },
        error: function( xhr, status, error ) {
            alert(error);
            console.log('ajax loading error...');
            console.log(error);
            return false;
        }
    });
  }
</script>