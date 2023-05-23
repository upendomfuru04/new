<!DOCTYPE html>
<html>
<head>
    <title>Messages/Announcements || GetValue</title>
</head>
<body>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h2 class="panel-title">Messages/Announcements</h2>
        </div>
        <div class="panel-body cBody">
            <p><?php if(sizeOf($messages)==0){ echo 'No any message sent...';}?></p>
            <div class="table-responsive">
                <table class="table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-left">Subject</th>
                            <th class="text-right">Date</th>
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
                                <a href="<?php echo base_url();?>customer/customer/message_details/<?php echo $msg->id;?>" class="btn btn-success btn-xs">Read</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <br >
            </div>
            <p class="sm-hint">Scroll horizontal to view more</p>
            <div class="clearfix"></div>
        </div>
    </div>
</body>
</html>
<script type="text/javascript">
    $(document).ready(function(){

    });
</script>