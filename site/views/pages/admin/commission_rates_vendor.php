<?php
    $vendor=""; $insider=""; $outsider=""; $contributor=""; $product=""; $type="";
    if($update!=""){
        $type=$edit_rows['type'];
        if($type=='vendor'){
            $vendor=$edit_rows['seller_id'];
        }
        if($type=='insider'){
            $insider=$edit_rows['seller_id'];
        }
        if($type=='outsider'){
            $outsider=$edit_rows['seller_id'];
        }
        if($type=='contributor'){
            $contributor=$edit_rows['seller_id'];
        }
        $product=$edit_rows['product'];
    }
?>
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
                        <h3 class="panel-title"> Add/Update Vendor Commission Rates</h3>
                    </div>
                    <div class="panel-body p-40 row">
                        <form id="data-form" method="POST">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label>Rate Type:</label>
                                    <select class="form-control" name="type" id="rate_type" onchange="switchField();">
                                        <option value="">Select</option>
                                        <option value="flat" <?php if($update!="" && $type=='flat'){ echo 'selected';} ?>>Flat Commission</option>
                                        <!-- <option value="vendor" <?php //if($update!="" && $type=='vendor'){ echo 'selected';} ?>>Vendor Commission</option> -->
                                        <option value="insider" <?php if($update!="" && $type=='insider'){ echo 'selected';} ?>>Insider Commission</option>
                                        <!-- <option value="outsider" <?php //if($update!="" && $type=='outsider'){ echo 'selected';} ?>>Outsider Commission</option> -->
                                        <!-- <option value="contributor" <?php //if($update!="" && $type=='contributor'){ echo 'selected';} ?>>Contributor Commission</option> -->
                                        <option value="product" <?php if($update!="" && $type=='product'){ echo 'selected';} ?>>Product Commission</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-5" id="vendorList">
                                    <label>Vendors:</label>
                                    <select class="form-control" name="vendor">
                                        <option value="all">All</option>
                                        <?php loadVendors($vendor); ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-5" id="insiderList">
                                    <label>Insider:</label>
                                    <select class="form-control" name="insider">
                                        <option value="all">All</option>
                                        <?php loadInsiders($insider); ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-5" id="outsiderList">
                                    <label>Outsider:</label>
                                    <select class="form-control" name="outsider">
                                        <option value="all">All</option>
                                        <?php loadOutsiders($outsider); ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-5" id="contributorList">
                                    <label>Contributor:</label>
                                    <select class="form-control" name="contributor">
                                        <option value="all">All</option>
                                        <?php loadContributors($contributor); ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-5" id="productList">
                                    <label>Product:</label>
                                    <select class="form-control" name="product">
                                        <option value="">Select</option>
                                        <?php loadAllProducts($product); ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Amount (TZS):</label>
                                    <input type="number" class="form-control" name="amount_fixed" id="amount_fixed" value="<?php if($update!=""){ echo $edit_rows['amount_fixed'];} ?>" placeholder="eg. 23000" onkeyup="resetValue('fixed');"/>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Amount (%):</label>
                                    <input type="number" class="form-control" name="amount_percent" id="amount_percent" value="<?php if($update!=""){ echo $edit_rows['amount_percent'];} ?>" placeholder="eg. 30" onkeyup="resetValue('percent');"/>
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
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"> Commission Rates</h3>
                    </div>
                    <div class="panel-body p40">
                        <div class="table-responsive pb20 pt20">
                            <table id="dataTable" class="display nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">S/N</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-center">Seller</th>
                                        <th class="text-center">Product</th>
                                        <th class="text-right">Amount (%)</th>
                                        <th class="text-right">Amount (TZS)</th>
                                        <th class="text-center">Created Date</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $counter=0; 
                                        foreach($table_rows as $item){ 
                                            $counter++;
                                            $status="";
                                            $action='<a href="'.base_url().'admin/commission/edit_vendor_commission_rates/'.$item->id.'" class="btn btn-xs btn-info m-r-10"><i class="fa fa-edit"></i></a>';
                                            if($item->status=='2'){
                                                $action.='<a href="javascript:void();" onclick="activateData(\''.base_url()."admin/commission/activate_vendor_commission_rate/".$item->id.'\');" class="btn btn-xs btn-success m-r-10" title="activate"><i class="fa fa-check"></i></a>';
                                                $status="Disabled";
                                            }elseif($item->status=='0'){
                                                $action.='<a href="javascript:void();" onclick="diactivateData(\''.base_url()."admin/commission/diactivate_vendor_commission_rate/".$item->id.'\');" class="btn btn-xs btn-warning m-r-10" title="diactivate"><i class="fa fa-ban"></i></a>';
                                                $status="Active";
                                            }
                                            $action.='<a href="javascript:void();" onclick="deleteData(\''.base_url()."admin/commission/delete_vendor_commission_rate/".$item->id.'\');" class="btn btn-xs btn-danger"><i class="ti-trash"></i></a>';
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $counter; ?></td>
                                        <td class="text-center"><?php echo ucwords($item->type); ?></td>
                                        <td class="text-center"><?php echo ucwords($item->full_name); ?></td>
                                        <td class="text-center"><?php echo ucwords($item->name); ?></td>
                                        <td class="text-right"><?php echo $item->amount_percent; ?></td>
                                        <td class="text-right"><?php echo $item->amount_fixed; ?></td>
                                        <td class="text-center"><?php echo date("d M, Y", $item->createdDate); ?></td>
                                        <td class="text-center"><?php echo $status; ?></td>
                                        <td class="text-center"><?php echo $action; ?></td>
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
        // $(".select2").select2();
        $('#dataTable').DataTable();
        switchField();

        $('#saveBtn').click(function(){
            $('.turnOnProgress').css('display','none');
            $('.progressBarBtn').css('display','inline-block');
            $.ajax({
                url: '<?php echo base_url(); ?>admin/commission/save_vendor_commission_rate',
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
                url: '<?php echo base_url(); ?>admin/commission/update_vendor_commission_rate/<?php echo $update; ?>',
                type: 'POST',
                data:$('#data-form').serialize(),
                async: true,
                processData: false,
                success: function (data) {
                    if(data.trim()=='Success'){
                        var output = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> Success </div>'; 
                        $('#resultMsg').html(output);
                        document.getElementById("data-form").reset();
                        window.location="<?php echo base_url();?>admin/commission/commission_rates_vendor";
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
        if (confirm("Are you sure you want to delete this commission rate?")) {
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

    function activateData(data){
        var txt;
        if (confirm("Are you sure you want to activate this commission rate?")) {
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

    function diactivateData(data){
        var txt;
        if (confirm("Are you sure you want to diactivate this commission rate?")) {
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

    function switchField(){
        var type=document.getElementById('rate_type').value;
        if(type=='vendor'){
            $('#vendorList').css('display','inline-block');
            $('#insiderList').css('display','none');
            $('#outsiderList').css('display','none');
            $('#contributorList').css('display','none');
            $('#productList').css('display','none');
        }else if(type=='insider'){
            $('#vendorList').css('display','none');
            $('#insiderList').css('display','inline-block');
            $('#outsiderList').css('display','none');
            $('#contributorList').css('display','none');
            $('#productList').css('display','none');
        }else if(type=='outsider'){
            $('#vendorList').css('display','none');
            $('#insiderList').css('display','none');
            $('#outsiderList').css('display','inline-block');
            $('#contributorList').css('display','none');
            $('#productList').css('display','none');
        }else if(type=='contributor'){
            $('#vendorList').css('display','none');
            $('#insiderList').css('display','none');
            $('#outsiderList').css('display','none');
            $('#contributorList').css('display','inline-block');
            $('#productList').css('display','none');
        }else if(type=='product'){
            $('#vendorList').css('display','none');
            $('#insiderList').css('display','none');
            $('#outsiderList').css('display','none');
            $('#contributorList').css('display','none');
            $('#productList').css('display','inline-block');
        }else{
            $('#vendorList').css('display','none');
            $('#insiderList').css('display','none');
            $('#outsiderList').css('display','none');
            $('#contributorList').css('display','none');
            $('#productList').css('display','none');
        }
    }

    function resetValue(field){
        if(field=='percent'){
            document.getElementById('amount_fixed').value="";
        }else{
            document.getElementById('amount_percent').value="";
        }
    }
</script>