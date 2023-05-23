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
                        <h3 class="panel-title"><i class="ti-list fa-fw"></i> Self Help</h3>
                    </div>
                    <div class="panel-body p40">
                        <div class="table-responsive pb20 pt20">
                            <table id="dataTable" class="display no-break-sm" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>URL</th>
                                        <th class="text-center">Views</th>
                                        <th class="text-center">Posted Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $counter=0; foreach($table_rows as $post){ 
                                        $counter++;
                                        $url='<a href="'.base_url().'home/self_help_details/'.$post->url.'" target="_blank">'.$post->url.'</a>';
                                    ?>
                                    <tr>
                                        <td><?php echo $counter; ?></td>
                                        <td><img src="<?php echo base_url();?>media/help/<?php echo $post->image; ?>" class="img-thumbnail productThumb" /></td>
                                        <td class="no-break-sm"><?php echo ucwords($post->title); ?></td>
                                        <td class="break-all"><?php echo $url; ?></td>
                                        <td class="text-center"><?php echo $post->views; ?></td>
                                        <td class="text-center"><?php echo date('d-m-Y', $post->postedDate); ?></td>
                                        <td>
                                            <!-- <a href="post_details/<?php //echo $post->id;?>" class="text-success" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a> -->
                                            <a href="<?=base_url()?>seller/blog/edit_self_help/<?php echo $post->id;?>" class="text-info"><i class="fa fa-edit"></i></a>
                                            <a href="javascript:void();" onclick="deletePost('<?php echo base_url()."seller/blog/delete_self_help/".$post->id;?>');" class="text-danger"><i class="ti-trash"></i></a>
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
        $('#dataTable').DataTable({});
    });

    function deletePost(data){
        var txt;
        if (confirm("Are you sure you want to delete this post?")) {
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