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
                        <h3 class="panel-title"> Withdrawal Request</h3>
                    </div>
                    <div class="panel-body p-40 row">
                        <div class="table-responsive pb20">
                            <p><?php if(sizeOf($withdraw_request)==0){ echo 'No any withdraw request placed...';}?></p>
                            <table id="dataTable" class="table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-left">#</th>
                                        <th class="text-center">RequestID</th>
                                        <th class="text-center">Seller Type</th>
                                        <th class="text-right">Amount (Tsh.)</th>
                                        <th class="text-right">Cheque</th>
                                        <th class="text-right">Transaction ID</th>
                                        <th class="text-center">Note</th>
                                        <th class="text-center">Request Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $counter=0; 
                                        foreach($withdraw_request as $request){
                                            $counter++;
                                            $request_status="";
                                            if($request->is_processed=='0'){
                                                $request_status='<span class="btn btn-xs btn-success">Paid</span>';
                                            }elseif($request->is_processed=='2'){
                                                $request_status='<span class="btn btn-xs btn-danger">Rejected</span>';
                                            }else{
                                                $request_status='<span class="btn btn-xs btn-warning">Pending</span>';
                                            }
                                            $seller_type="";
                                            if($request->seller_type!=''){
                                                $seller_type=$request->seller_type;
                                            }else{
                                                $seller_type='General';
                                            }
                                        ?>
                                    <tr>
                                        <td class="text-left"><?php echo $counter; ?></td>
                                        <td class="text-center"><?php echo $request->requestID; ?></td>
                                        <td class="text-center"><?php echo ucwords($seller_type); ?></td>
                                        <td class="text-right"><?php echo number_format($request->amount); ?></td>
                                        <td class="text-right"><?php echo $request->cheque; ?></td>
                                        <td class="text-center"><?php echo $request->transactionID; ?></td>
                                        <td class="text-center no-break-sm"><?php echo $request->rejection_reason; ?></td>
                                        <td class="text-center"><?php echo $request_status; ?></td>
                                        <td>
                                            <?php if($request->is_processed!="0"){ ?>
                                            <a href="<?php echo base_url().'seller/commission/withdrawal_form/'.$request->requestID;?>" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                            <?php } ?>
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
    });
</script>