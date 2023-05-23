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
                        <h3 class="panel-title"> Page Visits</h3>
                    </div>
                    <div class="panel-body p-40 row">
                        <div class="table-responsive123">
                            <p><?php if(sizeOf($visits)==0){ echo 'No any visits...';}else{ echo 'Total Visits: '.sizeOf($visits);}?></p>
                            <table id="dataTable" class="table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-left">#</th>
                                        <th class="text-left">Page</th>
                                        <th class="text-center">Browser</th>
                                        <th class="text-center">IP Address</th>
                                        <th class="text-right">Visit Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $counter=0; 
                                        foreach($visits as $visit){
                                            $counter++;
                                        ?>
                                    <tr>
                                        <td class="text-left"><?php echo $counter; ?></td>
                                        <td class="text-left"><?php echo str_replace('index.php', 'Home', $visit->page); ?></td>
                                        <td class="text-center"><?php echo $visit->browser; ?></td>
                                        <td class="text-center"><?php echo $visit->ip_address; ?></td>
                                        <td class="text-right"><?php echo $visit->createdDate; ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
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