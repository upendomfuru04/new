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
                        <h3 class="panel-title"> Withdrawal Payouts</h3>
                    </div>
                    <div class="panel-body p40">
                        <div class="table-responsive pb20 pt20">
                            <p><?php if(sizeOf($withdraw_payouts)==0){ echo 'No any withdraw payouts placed...';}?></p>
                            <table id="dataTable" class="table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-left">#</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">RequestID</th>
                                        <th class="text-center">Seller Type</th>
                                        <th class="text-right">Amount Requested (Tsh.)</th>
                                        <th class="text-right">Total Balance (Tsh.)</th>
                                        <th class="text-center">Request Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $counter=0; 
                                        foreach($withdraw_payouts as $request){
                                            $counter++;
                                            $request_status="";
                                            if($request->is_processed=='0'){
                                                $request_status='<span class="btn btn-xs btn-success">Paid</span>';
                                            }else{
                                                $request_status='<span class="btn btn-xs btn-danger">Pending</span>';
                                            }
                                            $seller_type="";
                                            if($request->seller_type!=''){
                                                $seller_type=$request->seller_type;
                                            }else{
                                                $seller_type='General';
                                            }
                                            $total=totalSellerCommission($request->seller_id);
                                        ?>
                                    <tr>
                                        <td class="text-left"><?php echo $counter; ?></td>
                                        <td class="text-center"><?php echo $request->full_name; ?></td>
                                        <td class="text-center"><?php echo $request->requestID; ?></td>
                                        <td class="text-center"><?php echo ucwords($seller_type); ?></td>
                                        <td class="text-right"><?php echo number_format($request->amount); ?></td>
                                        <td class="text-right"><?php echo number_format($total); ?></td>
                                        <td class="text-center"><?php echo $request_status; ?></td>
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

    function withdrawRequest(seller, sellerName, requestID, amountRequested, balance){
        document.getElementById('seller_name_wth').value=sellerName;
        document.getElementById('seller').value=seller;
        document.getElementById('requestID').value=requestID;
        document.getElementById('amountRequested').value=amountRequested;
        document.getElementById('total_balance').value=balance;
        $('#withdrawal_approval').modal('show');
    }
</script>