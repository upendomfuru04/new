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
                        <h3 class="panel-title"><i class="ti-list fa-fw"></i> Product</h3>
                    </div>
                    <div class="panel-body p-40">
                        <div class="table-responsive123">
                            <table id="dataTable" class="display nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th class="text-center">Image</th>
                                        <th class="text-center">Preview</th>
                                        <th>Belong to</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>S/N</th>
                                        <th class="text-center">Image</th>
                                        <th class="text-center">Preview</th>
                                        <th>Belong to</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php $counter=0; foreach($table_rows as $item){ $counter++;?>
                                    <tr>
                                        <td><?php echo $counter; ?></td>
                                        <td class="text-center">
                                            <img src="<?php echo base_url();?>media/products/<?php echo $item->image; ?>" class="img-thumbnail mediaCPreview" />
                                            <br >
                                            <a href="javascript:void();" class="btn btn-xs btn-info m-t-10" onclick="copyMedia('<?php echo $item->image; ?>');"><i class="fa fa-refresh"></i> Re-use</a>
                                        </td>
                                        <td class="text-center">
                                            <?php 
                                                if($item->preview_type=='image'){
                                                    echo '<img src="'.base_url().'media/products/preview/'.$item->preview.'" class="img-thumbnail mediaCPreview">';
                                                }else{
                                                    echo '<a href="'.base_url().'media/products/preview/'.$item->preview.'" target="_blank" class="btn badge-outline-primary">Preview - '.$item->name.'</a>';
                                                }
                                            ?>
                                            <br >
                                            <a href="javascript:void();" class="btn btn-xs btn-info m-t-10" onclick="copyMedia('<?php echo $item->preview; ?>');"><i class="fa fa-refresh"></i> Re-use</a>
                                        </td>
                                        <td><?php echo ucwords($item->name); ?></td>
                                        <td>
                                            <a href="product_details/<?php echo $item->id;?>" class="btn btn-sm btn-success" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>
                                        </td>
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

    function copyMedia(media){
        window.prompt("Copy to clipboard: Ctrl+C, Enter", media);
    }
</script>