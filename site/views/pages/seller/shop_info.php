<?php
    $seller="";
    if($update!=""){ $seller=$edit_rows['seller_type'];}
?>
<!DOCTYPE html>
<html>
<head>
    
</head>
<body>
    <h1 class="breadcumb"></h1>
    <div class="home-page">
        <div class="row p-t-20">
            <div class="col-md-7">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"> Shop Information</h3>
                    </div>
                    <div class="panel-body p-40 row">
                        <div class="table-responsive pb20">
                            <table id="dataTable" class="display nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Logo</th>
                                        <th>Banner</th>
                                        <th>Brand</th>
                                        <th>Seller Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Logo</th>
                                        <th>Banner</th>
                                        <th>Brand</th>
                                        <th>Seller Type</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php $counter=0; foreach($table_rows as $item){ $counter++;?>
                                    <tr>
                                        <td><?php echo $counter; ?></td>
                                        <td><img src="<?php echo base_url();?>media/shop/logo/<?php echo $item->logo; ?>" class="img-thumbnail productThumb" /></td>
                                        <td><img src="<?php echo base_url();?>media/shop/banner/<?php echo $item->banner; ?>" class="img-thumbnail productThumb" /></td>
                                        <td><?php echo ucwords($item->brand); ?></td>
                                        <td><?php echo ucwords($item->seller_type); ?></td>
                                        <td>
                                            <a href="<?php echo base_url().'seller/home/edit_shop_info/'.$item->id;?>" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>
                                            <a href="javascript:void();" onclick="deleteData('<?php echo base_url()."seller/home/delete_shop_info/".$item->id;?>');" class="btn btn-sm btn-danger"><i class="ti-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <p class="sm-hint">Scroll horizontal to view more</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"> Add/Update</h3>
                    </div>
                    <div class="panel-body p-40 row">
                        <form id="data-form" method="POST">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label>Seller Type:</label>
                                    <select class="form-control" name="seller_type">
                                        <?php loadSellerAccounts($userID, $seller);?>
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Brand:</label>
                                    <input type="text" name="brand" class="form-control" placeholder="eg. Shop Name" value="<?php if($update!=""){ echo $edit_rows['brand'];} ?>">
                                </div>
                                <div class="form-group col-md-12">
                                    <center>
                                        <img src="<?php echo base_url();?>media/shop/logo/<?php if($update!=""){ echo $edit_rows['logo'];}else{ echo 'default.jpg'; } ?>" class="img-thumbnail avatarHolder m-b-10" id="img-current-holder"/>
                                        <div class="col-md-12">
                                            <input type="file" name="logo" id="logoImg" style="display: none;"  accept="image/*" src="<?php echo set_value('image'); ?>" />
                                            <label for="logoImg" class="btn btn-sm btn-info waves-effect waves-light"><span>Change Logo</span></label>
                                        </div>
                                    </center>
                                </div>
                                <div class="form-group col-md-12">
                                    <center>
                                        <img src="<?php if($update!=""){ echo base_url().'media/shop/banner/'.$edit_rows['banner'];} ?>" class="img-thumbnail avatarHolder m-b-10" id="img-current-holder1"/>
                                        <div class="col-md-12">
                                            <input type="file" name="banner" id="bannerImg" style="display: none;"  accept="image/*" src="<?php echo set_value('image'); ?>" />
                                            <label for="bannerImg" class="btn btn-sm btn-info waves-effect waves-light"><span>Change Banner</span></label>
                                        </div>
                                    </center>
                                </div>
                                <div class="col-md-12" id="resultMsg"></div>
                                <div class="col-md-12 p-t-10">
                                    <!-- <button type="submit" class="btn btn-success" name="register-form-submit" value="signup"><i class="ti-save"></i> Save</button> -->
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
</body>
</html>
<script type="text/javascript">
    $(document).ready(function(){
        $('#dataTable').DataTable();

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#img-current-holder').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#logoImg").change(function(){
            readURL(this);
        });

        function readURL1(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#img-current-holder1').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#bannerImg").change(function(){
            readURL1(this);
        });

        $('#saveBtn').click(function(){
           $('.turnOnProgress').css('display','none');
           $('.progressBarBtn').css('display','inline-block');
           var formData = new FormData($('#data-form')[0]);
           $.ajax({
               url: '<?php echo base_url(); ?>seller/home/save_shop_info',
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
               url: '<?php echo base_url(); ?>seller/home/update_shop_info/<?php echo $update; ?>',
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
                        window.location="<?php echo base_url(); ?>seller/home/shop_info";
                    }else{
                        $('#resultMsg').html(data);
                    }
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

    function deleteData(data){
        var txt;
        if (confirm("Are you sure you want to delete this shop info?")) {
            $.ajax({
                url: data,
                type:"POST",
                success: function(data){
                    if(data.trim()=='Success'){
                        window.location="";
                    }else{
                        alert(data);
                    }
                }
            });
        }
    }
</script>