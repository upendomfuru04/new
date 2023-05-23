<!DOCTYPE html>
<html>
<head>
    
</head>
<body>
    <h1 class="breadcumb">Quick Reports</h1>
    <div class="home-page">
        <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#accounts_visits">Accounts & Visits</a></li>
                <li><a data-toggle="tab" href="#products_blog">Products & Blog</a></li>
                <li><a data-toggle="tab" href="#withdraw_commission">Withdraws & Commissions</a></li>
                <li><a data-toggle="tab" href="#campaigns">Sales</a></li>
            </ul>
            
            <div class="tab-content">
                <div id="accounts_visits" class="tab-pane fade in active">
                    <h2></h2>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="panel panel-default center">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Total Visits</h3>
                                </div>
                                <div class="panel-body">
                                    <p class="amount"><?php echo $visits; ?></p>
                                    <p>Statistics</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="panel panel-default center">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Total Customer</h3>
                                </div>
                                <div class="panel-body">
                                    <p class="amount"><?php echo $customers; ?></p>
                                    <p>Statistics</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="panel panel-default center">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Total Affiliates</h3>
                                </div>
                                <div class="panel-body">
                                    <p class="amount"><?php echo $totalAffiliates; ?></p>
                                    <p>Statistics</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="panel panel-default center">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Total Insiders</h3>
                                </div>
                                <div class="panel-body">
                                    <p class="amount"><?php echo $insiders; ?></p>
                                    <p>Statistics</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="panel panel-default center">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Total Outsiders</h3>
                                </div>
                                <div class="panel-body">
                                    <p class="amount"><?php echo $outsiders; ?></p>
                                    <p>Statistics</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="panel panel-default center">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Total Contributor</h3>
                                </div>
                                <div class="panel-body">
                                    <p class="amount"><?php echo $contributors; ?></p>
                                    <p>Statistics</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="panel panel-default center">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Total Vendors</h3>
                                </div>
                                <div class="panel-body">
                                    <p class="amount"><?php echo $vendors; ?></p>
                                    <p>Statistics</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="products_blog" class="tab-pane fade">
                    <h2></h2>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="panel panel-default center">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Total Products</h3>
                                </div>
                                <div class="panel-body">
                                    <p class="amount"><?php echo $products; ?></p>
                                    <p>Items</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="panel panel-default center">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Ebooks</h3>
                                </div>
                                <div class="panel-body">
                                    <p class="amount"><?php echo $ebooks; ?></p>
                                    <p>Items</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="panel panel-default center">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Audiobooks</h3>
                                </div>
                                <div class="panel-body">
                                    <p class="amount"><?php echo $audiobooks; ?></p>
                                    <p>Items</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="panel panel-default center">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Online Trainings & Programs</h3>
                                </div>
                                <div class="panel-body">
                                    <p class="amount"><?php echo $trainings_programms; ?></p>
                                    <p>Items</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="panel panel-default center">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Total Blog Posts</h3>
                                </div>
                                <div class="panel-body">
                                    <p class="amount"><?php echo $blog_posts; ?></p>
                                    <p>Posts</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="withdraw_commission" class="tab-pane fade">
                    <h2></h2>
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="panel panel-default center">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Total Commissions</h3>
                                </div>
                                <div class="panel-body">
                                    <p class="amount"><?php echo $commissions; ?></p>
                                    <p>Commissions</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="panel panel-default center">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Paid Commissions</h3>
                                </div>
                                <div class="panel-body">
                                    <p class="amount"><?php echo $paid_commissions; ?></p>
                                    <p>Commissions</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="panel panel-default center">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Un-Paid Commissions</h3>
                                </div>
                                <div class="panel-body">
                                    <p class="amount"><?php echo $unpaid_commissions; ?></p>
                                    <p>Commissions</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="panel panel-default center">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Pending Commissions</h3>
                                </div>
                                <div class="panel-body">
                                    <p class="amount"><?php echo $pending_commissions; ?></p>
                                    <p>Commissions</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="panel panel-default center">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Refunded Commissions</h3>
                                </div>
                                <div class="panel-body">
                                    <p class="amount"><?php echo $refundend_commissions; ?></p>
                                    <p>Commissions</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="panel panel-default center">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Cancelled Commissions</h3>
                                </div>
                                <div class="panel-body">
                                    <p class="amount"><?php echo $cancelled_commissions; ?></p>
                                    <p>Commissions</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="panel panel-default center">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Total Request</h3>
                                </div>
                                <div class="panel-body">
                                    <p class="amount"><?php echo $requests; ?></p>
                                    <p>Withdraw</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="panel panel-default center">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Paid Request</h3>
                                </div>
                                <div class="panel-body">
                                    <p class="amount"><?php echo $paid_requests; ?></p>
                                    <p>Withdraw</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="panel panel-default center">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Pending Request</h3>
                                </div>
                                <div class="panel-body">
                                    <p class="amount"><?php echo $pending_requests; ?></p>
                                    <p>Withdraw</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="panel panel-default center">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Rejected Request</h3>
                                </div>
                                <div class="panel-body">
                                    <p class="amount"><?php echo $rejected_requests; ?></p>
                                    <p>Withdraw</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="campaigns" class="tab-pane fade">
                    <h2></h2>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="panel panel-default center">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Total Orders</h3>
                                </div>
                                <div class="panel-body">
                                    <p class="amount"><?php echo $orders; ?></p>
                                    <p>Statistics</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="panel panel-default center">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Pending Orders</h3>
                                </div>
                                <div class="panel-body">
                                    <p class="amount"><?php echo $pending_orders; ?></p>
                                    <p>Statistics</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="panel panel-default center">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Complete Orders</h3>
                                </div>
                                <div class="panel-body">
                                    <p class="amount"><?php echo $complete_orders; ?></p>
                                    <p>Statistics</p>
                                </div>
                            </div>
                        </div>
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
        $('#dataTable').DataTable();
    });
</script>