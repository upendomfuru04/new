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
                        <h3 class="panel-title"> Customer Subscribers</h3>
                    </div>
                    <div class="panel-body p40">
                        <div class="table-responsive pb20 pt20">
                            <p><?php if(sizeOf($subscribers)==0){ echo 'No any subscriber...';}?></p>
                            <table id="dataTable" class="table-bordered no-break" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-left">#</th>
                                        <th>Email</th>
                                        <th class="text-center">Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $counter=0; 
                                        foreach($subscribers as $sub){
                                            $counter++;
                                        ?>
                                    <tr>
                                        <td class="text-left"><?php echo $counter; ?></td>
                                        <td><?php echo $sub->email; ?></td>
                                        <td class="text-center"><?php echo date('M d, Y H:i', $sub->time); ?></td>
                                        <td>
                                            <a href="javascript:void();" class="btn btn-danger btn-xs" onclick="deleteData('<?php echo base_url()."admin/home/delete_subscriber/".$sub->id;?>');">Delete</a>
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

    function deleteData(data){
        var txt;
        if (confirm("Are you sure you want to delete this subscriber?")) {
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