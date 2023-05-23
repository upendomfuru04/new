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
                        <h3 class="panel-title"> Transaction Balances</h3>
                    </div>
                    <div class="panel-body p-40 row">
                        <div class="table-responsive col-md-12 pb20">
                            <p><?php if(sizeOf($balances)==0){ echo 'No any transaction records...';} //var_dump($commissions);?></p>
                            <table id="dataTable" class="table-bordered no-break-sm" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-left">#</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-left">Gender</th>
                                        <th class="text-left">Phone</th>
                                        <th class="text-left">Email</th>
                                        <!-- <th class="text-center">Account Type</th> -->
                                        <!-- <th class="text-left">Transaction Type</th> -->
                                        <th class="text-right">Total Credit (TZS)</th>
                                        <th class="text-right">Total Debit (TZS)</th>
                                        <th class="text-right">Total Charges (TZS)</th>
                                        <th class="text-right">Balance (TZS)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $counter=0;
                                        $total_credits=0; $total_debits=0; $total_charges=0; $total_balance=0;
                                        foreach($balances as $balance_list){
                                            $counter++;
                                            $transaction_type=$balance_list['transaction_type'];
                                            $transaction_type="Commission";
                                            $debit=0; $credit=0; $charge=0;
                                            if($balance_list['credit']>0){
                                                $credit=$balance_list['credit'];
                                            }
                                            if($balance_list['debit']>0){
                                                $debit=$balance_list['debit'];
                                            }
                                            if($balance_list['charge']>0){
                                                $charge=$balance_list['charge'];
                                            }
                                            $balance=($balance_list['credit']-($balance_list['debit']+$balance_list['charge']));
                                            $total_credits=$total_credits+$credit;
                                            $total_debits=$total_debits+$debit;
                                            $total_charges=$total_charges+$charge;
                                            $total_balance=$total_balance+$balance;
                                        ?>
                                    <tr>
                                        <td class="text-left"><?php echo $counter; ?></td>
                                        <td class="text-center"><?php echo $balance_list['full_name']; ?></td>
                                        <td class="text-left"><?php echo ucwords($balance_list['gender']); ?></td>
                                        <td class="break-all"><?php echo $balance_list['phone']; ?></td>
                                        <td class="break-all"><?php echo $balance_list['email']; ?></td>
                                        <!-- <td class="text-left"><?php //echo ucwords($transaction_type); ?></td> -->
                                        <td class="text-right"><?php echo number_format($credit); ?></td>
                                        <td class="text-right"><?php echo number_format($debit); ?></td>
                                        <td class="text-right"><?php echo number_format($charge); ?></td>
                                        <td class="text-right"><b><?php echo number_format($balance); ?></b></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5"></th>
                                        <th class="text-right"><?=number_format($total_credits)?></th>
                                        <th class="text-right"><?=number_format($total_debits)?></th>
                                        <th class="text-right"><?=number_format($total_charges)?></th>
                                        <th class="text-right"><?=number_format($total_balance)?></th>
                                    </tr>
                                </tfoot>
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