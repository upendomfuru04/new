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
                        <h3 class="panel-title"> Messages/Announcements</h3>
                    </div>
                    <div class="panel-body p-40 row">
                        <div class="table-responsive pb20">
                            <p><?php if(sizeOf($messages)==0){ echo 'No any message sent...';}?></p>
                            <table id="dataTable" class="table-bordered no-break" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-left">Subject</th>
                                        <th class="text-right">Sent Date</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $counter=0; 
                                        foreach($messages as $msg){
                                            $counter++;
                                            $isReadClr="";
                                            if($msg->is_read=='1'){
                                                $isReadClr="isReadColor";
                                            }
                                        ?>
                                    <tr>
                                        <td class="text-center <?php echo $isReadClr; ?>"><?php echo $counter; ?></td>
                                        <td class="text-left <?php echo $isReadClr; ?>"><?php echo $msg->subject; ?></td>
                                        <td class="text-right <?php echo $isReadClr; ?>"><?php echo date('d M, Y H:i', $msg->createdDate); ?></td>
                                        <td class="text-right">
                                            <a href="<?php echo base_url();?>seller/home/message_details/<?php echo $msg->id;?>" class="btn btn-success btn-xs">Read</a>
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