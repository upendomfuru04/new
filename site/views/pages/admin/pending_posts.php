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
                        <h3 class="panel-title"><i class="ti-list fa-fw"></i> Pending Posts</h3>
                    </div>
                    <div class="panel-body p40">
                        <div class="table-responsive pb20 pt20">
                            <table id="dataTable" class="display no-break-sm" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>URL</th>
                                        <th>Comments</th>
                                        <th>Posted By</th>
                                        <th>Posted Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $counter=0; foreach($table_rows as $post){ 
                                        $counter++;
                                        $url="";
                                        $status="Pending";
                                        if($post->is_approved=='0'){
                                            $url='<a href="'.base_url().'home/blog/blog_post/'.$post->url.'" target="_blank">'.$post->url.'</a>';
                                            $status="Approved";
                                        }
                                    ?>
                                    <tr>
                                        <td><?php echo $counter; ?></td>
                                        <td><img src="<?php echo base_url();?>media/blog/<?php echo $post->image; ?>" class="img-thumbnail productThumb" /></td>
                                        <td class="no-break-sm"><?php echo ucwords($post->title); ?></td>
                                        <td><?php echo ucwords($post->category); ?></td>
                                        <td class="break-all"><?php echo $url; ?></td>
                                        <td class="text-center"><?php echo getPostCommentCounter($post->id); ?></td>
                                        <td><?php echo ucwords(getSellerFullName($post->postedBy)); ?></td>
                                        <td><?php echo date('d-m-Y', $post->postedDate); ?></td>
                                        <td class="text-center"><?php echo $status; ?></td>
                                        <td>
                                            <?php if($post->is_approved=='1'){ ?>
                                            <a href="javascript:void();" class="btn btn-xs btn-success" onclick="approvePost('<?php echo base_url()."admin/blog/approve_post/".$post->id;?>');"><i class="fa fa-check"></i> Approve</a>
                                            <?php }?>
                                            <a href="<?php echo base_url(); ?>admin/blog/post_details/<?php echo $post->id;?>" class="text-success" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>
                                            <a href="<?php echo base_url(); ?>admin/blog/edit_post/<?php echo $post->id;?>" class="text-info"><i class="fa fa-edit"></i></a>
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

    function approvePost(data){
        var txt;
        if (confirm("Are you sure you want to approve this post?")) {
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