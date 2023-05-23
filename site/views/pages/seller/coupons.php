<?php
    $product="";
    if($update!=""){ $product=$edit_rows['product'];}
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
                        <h3 class="panel-title"> Shop Coupons</h3>
                    </div>
                    <div class="panel-body p-40 row">
                        <div class="table-responsive pt20 pb20">
                            <table id="dataTable" class="display" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Product</th>
                                        <th>Coupon Code</th>
                                        <th>Coupon Value</th>
                                        <th>Expire Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Product</th>
                                        <th>Coupon Code</th>
                                        <th>Coupon Value</th>
                                        <th>Expire Date</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php $counter=0; foreach($table_rows as $item){ $counter++;?>
                                    <tr>
                                        <td><?php echo $counter; ?></td>
                                        <td class="no-break-sm"><?php echo ucwords($item->name); ?></td>
                                        <td><?php echo $item->coupon_code; ?></td>
                                        <td><?php echo $item->coupon_value; ?></td>
                                        <td><?php echo date('d M, Y', strtotime($item->expire_date)); ?></td>
                                        <td>
                                            <a href="<?php echo base_url().'seller/sale/edit_product_coupon/'.$item->id;?>" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>
                                            <a href="javascript:void();" onclick="deleteData('<?php echo base_url()."seller/sale/delete_seller_coupon/".$item->id;?>');" class="btn btn-xs btn-danger"><i class="ti-trash"></i></a>
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
                                    <label>Product:</label>
                                    <select class="form-control select2" name="product" <?php if($update!=""){ echo 'disabled';} ?>>
                                        <?php loadProducts($userID, $product);?>
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Coupon Code:</label>
                                    <input type="text" name="coupon_code" class="form-control" placeholder="" value="<?php if($update!=""){ echo $edit_rows['coupon_code'];}else{ echo generateCouponCode();} ?>" readonly>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Coupon Value:</label>
                                    <input type="text" name="coupon_value" class="form-control" placeholder="" value="<?php if($update!=""){ echo $edit_rows['coupon_value'];} ?>">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Expire Date:</label>
                                    <input type="date" name="expire_date" class="form-control" placeholder="" value="<?php if($update!=""){ echo $edit_rows['expire_date'];} ?>">
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
</body>
</html>
<script type="text/javascript">
    $(document).ready(function(){
        $('#dataTable').DataTable();

        $('#saveBtn').click(function(){
           $('.turnOnProgress').css('display','none');
           $('.progressBarBtn').css('display','inline-block');
           $.ajax({
                url: '<?php echo base_url(); ?>seller/sale/save_shop_coupons',
                type: 'POST',
                data:$('#data-form').serialize(),
                async: true,
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
                    $('.turnOnProgress').css('display','inline-block');
                    $('.progressBarBtn').css('display','none');
                    return false;
                }
            });
        });

        $('#updateBtn').click(function(){
            $('.turnOnProgress').css('display','none');
            $('.progressBarBtn').css('display','inline-block');
            $.ajax({
                url: '<?php echo base_url(); ?>seller/sale/update_shop_coupons/<?php echo $update; ?>',
                type: 'POST',
                data:$('#data-form').serialize(),
                async: true,
                processData: false,
                success: function (data) {
                    if(data.trim()=='Success'){
                        var output = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> Success </div>'; 
                        $('#resultMsg').html(output);
                        document.getElementById("data-form").reset();
                        window.location="<?php echo base_url(); ?>seller/sale/coupons";
                    }else{
                        $('#resultMsg').html(data);
                    }
                    $('.turnOnProgress').css('display','inline-block');
                    $('.progressBarBtn').css('display','none');
                },
                error: function( xhr, status, error ) {
                    $('#resultMsg').html(error);
                    $('.turnOnProgress').css('display','inline-block');
                    $('.progressBarBtn').css('display','none');
                    return false;
                }
            });
        });

    });

    function deleteData(data){
        var txt;
        if (confirm("Are you sure you want to delete this product coupon?")) {
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