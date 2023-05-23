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
                    <div class="panel-body p-20 row">
                        <form method="GET" action="" class="col-md-12 row">
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
                        <div class="table-responsive col-md-12  pb20 pt20">
                            <p><?php if(sizeOf($commissions)==0){ echo 'No any commission placed...';} //var_dump($commissions);?></p>
                            <table id="dataTable" class="table-bordered no-break-sm" cellspacing="0" width="100%">
                                <thead>
                                    <th class="text-left">#</th>
                                    <th class="text-center">Seller</th>
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
                                        $counter=0; $balance=0;
                                        /*foreach($balances as $balance_list){
                                            $balance=$balance+($balance_list['debit']+$balance_list['charge']);
                                        }*/
                                        foreach($commissions as $commission){
                                            /*Note: in admin commissions, seller credit = admin debit likewise to debit*/
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
                                            if($commission['debit']>0){
                                                $dbit=$commission['debit'];
                                                $debit=number_format($dbit);
                                                $balance=$balance-$dbit;
                                            }
                                            if($commission['credit']>0){
                                                $crdit=$commission['credit']*$commission['quantity'];
                                                $credit=number_format($crdit);
                                                $balance=$balance+$crdit;
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
                                        <td class="text-center"><?php echo $commission['seller']; ?></td>
                                        <td class="text-center"><?php echo $commission['orderID']; ?></td>
                                        <td class="text-left"><?php echo ucwords($commission['name']); ?></td>
                                        <td class="break-all"><?php echo $referral_url; ?></td>
                                        <td class="text-center"><?php echo ucwords($seller_type); ?></td>
                                        <td class="text-center"><?php echo ucwords($from); ?></td>
                                        <td class="text-left"><?php echo ucwords($transaction_type); ?></td>
                                        <td class="text-right"><?php echo $credit; ?></td>
                                        <td class="text-right"><?php echo $debit; ?></td>
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

    function confirmCommission(data){
        var txt;
        if (confirm("Are you sure you want to confirm this commission?")) {
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