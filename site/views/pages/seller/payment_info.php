<!DOCTYPE html>
<html>
<head>
    
</head>
<body>
	<h1 class="breadcumb"></h1>
    <div class="home-page">
        <div class="row p-t-20">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"> Add/Update</h3>
                    </div>
                    <div class="panel-body p-40 row">
                        <form id="data-form" method="POST">
                            <div class="row">
                                <div class="form-group col-md-2">
                                    <label>Method:</label>
                                    <select class="form-control" name="method">
                                        <option value="">Select</option>
                                        <option value="mobile" <?php if($update!="" && $edit_rows['method']=='mobile'){ echo 'selected';} ?>>Mobile</option>
                                        <option value="bank" <?php if($update!="" && $edit_rows['method']=='bank'){ echo 'selected';} ?>>Bank</option>
                                        <option value="online" <?php if($update!="" && $edit_rows['method']=='online'){ echo 'selected';} ?>>Online</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Payment Type:</label>
                                    <select class="form-control" name="payment_type">
                                        <option value="">Select</option>
                                        <option value="payment" <?php if($update!="" && $edit_rows['payment_type']=='payment'){ echo 'selected';} ?>>Payment</option>
                                        <option value="withdraw" <?php if($update!="" && $edit_rows['payment_type']=='withdraw'){ echo 'selected';} ?>>Withdraw</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Bank/Provider Name:</label>
                                    <input type="text" class="form-control" name="provider_name" value="<?php if($update!=""){ echo $edit_rows['provider_name'];} ?>" placeholder="eg. NMB, Vodacom, Paypal"/>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Account Name:</label>
                                    <input type="text" class="form-control" name="account_name" value="<?php if($update!=""){ echo $edit_rows['account_name'];} ?>" placeholder="eg. Jerry Sam"/>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Account/Phone Number:</label>
                                    <input type="text" class="form-control" name="account_number" value="<?php if($update!=""){ echo $edit_rows['account_number'];} ?>" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Email Address/Link:</label>
                                    <input type="text" class="form-control" name="email_address" value="<?php if($update!=""){ echo $edit_rows['email_address'];} ?>" />
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
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"> Payment Information</h3>
                    </div>
                    <div class="panel-body p-40 row">
                        <div class="table-responsive pb20 pt20">
                            <table id="dataTable" class="display nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Method</th>
                                        <th>Payment Type</th>
                                        <th>Provider Name</th>
                                        <th>Account Name</th>
                                        <th>Account Number</th>
                                        <th>Email Address</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Method</th>
                                        <th>Payment Type</th>
                                        <th>Provider Name</th>
                                        <th>Account Name</th>
                                        <th>Account Number</th>
                                        <th>Email Address</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php $counter=0; foreach($table_rows as $item){ $counter++;?>
                                    <tr>
                                        <td><?php echo $counter; ?></td>
                                        <td><?php echo ucwords($item->method); ?></td>
                                        <td><?php echo ucwords($item->payment_type); ?></td>
                                        <td><?php echo ucwords($item->provider_name); ?></td>
                                        <td><?php echo ucwords($item->account_name); ?></td>
                                        <td><?php echo $item->account_number; ?></td>
                                        <td><?php echo $item->email_address; ?></td>
                                        <td>
                                            <a href="<?php echo base_url().'seller/home/edit_payment_info/'.$item->id;?>" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>
                                            <a href="javascript:void();" onclick="deleteData('<?php echo base_url()."seller/home/delete_payment_info/".$item->id;?>');" class="btn btn-xs btn-danger"><i class="ti-trash"></i></a>
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
                url: '<?php echo base_url(); ?>seller/home/save_payment_info',
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
                url: '<?php echo base_url(); ?>seller/home/update_payment_info/<?php echo $update; ?>',
                type: 'POST',
                data:$('#data-form').serialize(),
                async: true,
                processData: false,
                success: function (data) {
                    if(data.trim()=='Success'){
                        var output = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> Success </div>'; 
                        $('#resultMsg').html(output);
                        document.getElementById("data-form").reset();
                        window.location="<?php echo base_url();?>seller/home/payment_info";
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
        if (confirm("Are you sure you want to delete this payment info?")) {
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