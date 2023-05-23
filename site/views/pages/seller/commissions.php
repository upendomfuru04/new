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
                        <h3 class="panel-title"> Commissions</h3>
                    </div>
                    <div class="panel-body row">
                        <div class="col-md-7 col-md-offset-3 col-xs-12">
                            <table class="table table-bordered m-b-20">
                                <thead>
                                    <th>Total Credit</th>
                                    <th>Total Debit</th>
                                    <th>Total Charges</th>
                                    <th class="text-right">Balance</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>TZS <?php echo number_format($summary['credit']); ?></td>
                                        <td>TZS <?php echo number_format($summary['debit']); ?></td>
                                        <td>TZS <?php echo number_format($summary['charge']); ?></td>
                                        <td class="text-right"><b>TZS <?php $balance=$summary['credit']; echo number_format($summary['credit']-($summary['debit']+$summary['charge'])); ?></b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr class="col-md-12">
                        <form method="GET" action="" class="col-md-12">
                            <div class="form-group col-md-3">
                                <select class="form-control" name="sort_by">
                                    <option value="">Sort By</option>
                                    <option value="" <?php if($sort_by==''){ echo 'selected';} ?>>All</option>
                                    <option value="commission" <?php if($sort_by=='commission'){ echo 'selected';} ?>>Commissions</option>
                                    <option value="charge" <?php if($sort_by=='charge'){ echo 'selected';} ?>>Charges</option>
                                    <option value="withdraw" <?php if($sort_by=='withdraw'){ echo 'selected';} ?>>Withdraw</option>
                                    <option value="refund" <?php if($sort_by=='refund'){ echo 'selected';} ?>>Refunds</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <input type="submit" class="btn btn-xm btn-info" value="Filter">
                            </div>
                        </form>
                        <div class="table-responsive col-md-12 pb20">
                            <p><?php if(sizeOf($commissions)==0){ echo 'No any commission placed...';} //var_dump($commissions);?></p>
                            <table id="dataTable" class="table-bordered no-break-sm" cellspacing="0" width="100%">
                                <thead>
                                    <th class="text-left">#</th>
                                    <th class="text-center">OrderID</th>
                                    <th class="text-left">Product</th>
                                    <th>Referral Url</th>
                                    <th class="text-center">Account Type</th>
                                    <th class="text-center">Source</th>
                                    <th class="text-left">Transaction Type</th>
                                    <th class="text-right">Debit (Tsh.)</th>
                                    <th class="text-right">Credit (Tsh.)</th>
                                    <th class="text-right">Balance (Tsh.)</th>
                                </thead>
                                <tbody>
                                    <?php 
                                        $counter=0;
                                        $balance=0;
                                        foreach($commissions as $commission){
                                            $counter++;
                                            $from="";
                                            $seller_type=$commission['seller_type'];
                                            $transaction_type=$commission['transaction_type'];

                                            $referral_url="";
                                            if($commission['referral_url']!=""){
                                                $referral_url=base_url().'prod/'.$commission['referral_url'];
                                            }
                                            if($commission['vendor_url']!=""){
                                                $from="Vendor";
                                                $referral_url=base_url()."register".$commission['vendor_url'];
                                            }elseif($commission['orderID']!=""){
                                                $from="Product";
                                            }
                                            $debit="-"; $credit="-";
                                            if($commission['credit']>0){
                                                $crdt=$commission['credit']*$commission['quantity'];
                                                $credit=number_format($crdt);
                                                $balance=$balance+$crdt;
                                            }
                                            if($commission['debit']>0){
                                                $dbit=$commission['debit'];
                                                $debit=number_format($dbit);
                                                $balance=$balance-$dbit;
                                            }
                                            if($transaction_type=='charge' &&$commission['withdraw_id']!=""){
                                                $transaction_type="W-Charge";
                                            }
                                            if($transaction_type=='charge' &&$commission['refund_id']!=""){
                                                $transaction_type="R-Charge";
                                            }
                                        ?>
                                    <tr>
                                        <td class="text-left"><?php echo $counter; ?></td>
                                        <td class="text-center"><?php echo $commission['orderID']; ?></td>
                                        <td class="text-left"><?php echo ucwords($commission['name']); ?></td>
                                        <td class="break-all"><?php echo $referral_url; ?></td>
                                        <td class="text-center"><?php echo ucwords($seller_type); ?></td>
                                        <td class="text-center"><?php echo ucwords($from); ?></td>
                                        <td class="text-left"><?php echo ucwords($transaction_type); ?></td>
                                        <td class="text-right"><?php echo $debit; ?></td>
                                        <td class="text-right"><?php echo $credit; ?></td>
                                        <td class="text-right"><?php echo number_format($balance); ?></td>
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