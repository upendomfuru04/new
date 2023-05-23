<?php
    $category=""; $seller=""; $rowCounter=1; $preview_type=""; $media_type="";
    if($update!=""){ 
        $category=$table_rows['category'];
        $seller=$table_rows['seller_id'];
    }else{
        $category=set_value('category');
        $seller=set_value('seller');
        $preview_type=set_value('preview_type');
        $media_type=set_value('media_type');
    }
?>
<!DOCTYPE html>
<html>
<head>
    
    <style type="text/css">
        #url_panel{
            display: none;
        }
    </style>
</head>
<body>
	<h1 class="breadcumb"></h1>
    <div class="home-page">
        <div class="row p-t-20">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="ti-list fa-fw"></i> Add/Update Product</h3>
                    </div>
                    <div class="panel-body p40">
                        <div id="container-by-month" style="min-width: 310px; min-height: 400px; margin: 0 auto;">
                            <?php //echo form_open_multipart('admin/save_product',array('method'=>'post')); ?>
                            <form id="data-form" method="POST">
                                <div class="row">
                                    <div class="col-md-12"><?php echo $this->session->flashdata('feedback');?></div>
                                    <div class="form-group col-md-12">
                                        <center>
                                            <img src="<?php echo base_url();?>media/products/<?php if($update!=""){ echo $table_rows['image'];}else{ echo 'default.jpg'; } ?>" class="img-thumbnail avatarHolder m-b-10" id="img-current-holder1"/>
                                            <div class="col-md-12">
                                                <input type="file" name="image" id="avatarImg" style="display: none;"  accept="image/*" src="<?php echo set_value('image'); ?>" />
                                                <label for="avatarImg" class="btn btn-sm btn-info waves-effect waves-light"><span>Change Image</span></label>
                                            </div>
                                            <!-- <p><i><small>For better product display we recommend you to use product image size (ratio) should be 173px width and 225px height.</small></i></p> -->
                                            <p><i><small style="color: red" >Product image should be a straight front page cover only (not Box cover or a 3D cover). Also for better product display we recommend you to use product image size (ratio) with height greater than width, eg. 1050 X 1102 or 768 X 1020 etc.</small></i></p>
                                        </center>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Product Name:</label>
                                        <input type="text" name="name" class="form-control" value="<?php if($update!=""){ echo $table_rows['name'];}else{ echo set_value('name'); } ?>">
                                        <?php echo form_error('name'); ?>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Brand:</label>
                                        <input type="text" name="brand" class="form-control" value="<?php if($update!=""){ echo $table_rows['brand'];}else{ echo set_value('brand'); } ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Category:</label>
                                        <select class="form-control" name="category">
                                            <option value="">Select</option>
                                            <?php loadCategories($category);?>
                                        </select>
                                        <?php echo form_error('category'); ?>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Price (Tshs):</label>
                                        <input type="number" name="price" class="form-control" value="<?php if($update!=""){ echo $table_rows['price'];}else{ echo set_value('price'); } ?>" placeholder="eg. 12000">
                                        <?php echo form_error('price'); ?>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Quantity:</label>
                                        <input type="number" name="quantity" class="form-control" value="<?php echo '1';//if($update!=""){ echo $table_rows['quantity'];}else{ echo set_value('quantity'); } ?>" placeholder="eg. 25" readonly>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Summary:</label>
                                        <textarea class="form-control" name="summary"><?php if($update!=""){ echo $table_rows['summary'];}else{ echo set_value('summary'); } ?></textarea>
                                        <?php echo form_error('summary'); ?>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Description:</label>
                                        <textarea class="form-control summernote" name="description"><?php if($update!=""){ echo $table_rows['description'];}else{ echo set_value('description'); } ?></textarea>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">Sample Files Only</h4>
                                                <p class="" style="color: red;"><small>Caution: On this section only upload sample files and NOT your full product file (samples files can be downloaded for free by our customers).</small></p>
                                            </div>
                                            <div class="panel-body row">
                                                <div class="form-group col-md-3">
                                                    <select class="form-control" name="preview_type">
                                                        <option value="">Select Type</option>
                                                        <option value="image" <?php if(($update!="" && $table_rows['preview_type']=='image') || $preview_type=='image'){ echo 'selected';} ?>>Image</option>
                                                        <option value="pdf" <?php if(($update!="" && $table_rows['preview_type']=='pdf') || $preview_type=='pdf'){ echo 'selected';} ?>>PDF</option>
                                                        <option value="audio" <?php if(($update!="" && $table_rows['preview_type']=='audio') || $preview_type=='audio'){ echo 'selected';} ?>>Audio</option>
                                                        <option value="video" <?php if(($update!="" && $table_rows['preview_type']=='video') || $preview_type=='video'){ echo 'selected';} ?>>Video</option>
                                                        <option value="docs" <?php if(($update!="" && $table_rows['preview_type']=='docs') || $preview_type=='docs'){ echo 'selected';} ?>>Docs</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <input type="text" id="previewHolder" readonly="" disabled="" class="form-control" placeholder="No file selected" value="<?php if($update!="" && $table_rows['preview']!=""){ echo 'Uploaded'; } ?>">
                                                    <input type="file" name="preview" id="previewImg" style=""  accept="" src="<?php echo set_value('preview'); ?>" onchange="setFileName(this, 'previewHolder', 'previewLink');"/>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="previewImg" class="btn btn-sm btn-info waves-effect waves-light"><span>Choose</span></label>
                                                    <a href="" id="previewLink" target="_blank" class="btn btn-sm btn-default"><i class="fa fa-search"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Downloadable Media Type:</label>
                                        <select class="form-control" name="downloadable_media_type" onchange="checkDFile();" id="downloadable_media_type">
                                            <option value="downloadable" <?php if($update!="" && $table_rows['media']!=""){ echo 'selected'; } ?>>Media File</option>
                                            <option value="virtual" <?php if($update!="" && $table_rows['virtual_link']!=""){ echo 'selected'; } ?>>Virtual Products Link</option>
                                        </select>
                                    </div>
                                    <div id="downloadable">
                                        <div class="form-group col-md-6">
                                            <label>Media Type:</label>
                                            <select class="form-control" name="media_type">
                                                <option value="">Select</option>
                                                <option value="image" <?php if($update!="" && $table_rows['media_type']=='image'){ echo 'selected';} ?>>Image</option>
                                                <option value="pdf" <?php if($update!="" && $table_rows['media_type']=='pdf'){ echo 'selected';} ?>>PDF</option>
                                                <option value="audio" <?php if($update!="" && $table_rows['media_type']=='audio'){ echo 'selected';} ?>>Audio</option>
                                                <option value="video" <?php if($update!="" && $table_rows['media_type']=='video'){ echo 'selected';} ?>>Video</option>
                                                <option value="docs" <?php if($update!="" && $table_rows['media_type']=='docs'){ echo 'selected';} ?>>Docs</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <?php
                                                if($update!="" && $table_rows['media']!=""){
                                                    $mdia=explode(";", $table_rows['media']);
                                                    $rowCounter=sizeof($mdia)-1;
                                                }
                                            ?>
                                            <label>Episode Count:</label>
                                            <input type="number" name="episode_count" class="form-control" value="<?php if($update!="" && $table_rows['media']!=""){ echo sizeof($mdia)-1;}else{ echo '1';} ?>" id="episode_count" readonly="">
                                        </div>
                                        <div class="col-md-12">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">Downloadable Media Files</h4>
                                                    <p class="" style="color: red;"><small>Caution: On this section upload your full product file (Here your full product file is secured and customers have to pay to use this file).</small></p>
                                                </div>
                                                <div class="panel-body row">
                                                    <div id="mediaList">
                                                        <?php
                                                            if($update!="" && $table_rows['media']!=""){
                                                                $mdia=explode(";", $table_rows['media']);
                                                                for ($i=0; $i < sizeof($mdia); $i++) {
                                                                    if($mdia[$i]!=""){
                                                                        echo '<div>
                                                                            <div class="form-group col-md-4">
                                                                                <input type="text" class="form-control" name="media_file_name'.$i.'" id="media_file_name'.$i.'" placeholder="File name" value="'.$mdia[$i].'" disabled>
                                                                                <input type="hidden" class="form-control" name="holder_file_name'.$i.'" placeholder="File name" value="'.$mdia[$i].'">
                                                                            </div>
                                                                            <div class="form-group col-md-5">
                                                                                <input type="text" class="form-control" id="mediaFile'.$i.'" disabled="" placeholder="No file selected" value="Uploaded">
                                                                                <input type="file" name="media'.$i.'" id="mediaImg'.$i.'" style=""  accept="" src="" class="mediaFile" onchange="setFileName(this, \'mediaFile'.$i.'\', \'mediaLink'.$i.'\', \'media_file_name'.$i.'\');"/>
                                                                            </div>
                                                                            <div class="form-group col-md-3">
                                                                                <label for="mediaImg'.$i.'" class="btn btn-sm btn-info waves-effect waves-light"><span>Upload</span></label>
                                                                                <a href="" id="mediaLink'.$i.'" target="_blank" class="btn btn-sm btn-default"><i class="fa fa-search"></i></a>
                                                                            </div>
                                                                        </div>';
                                                                    }
                                                                }
                                                            }else{
                                                        ?>
                                                        <div>
                                                            <div class="form-group col-md-4">
                                                                <input type="text" class="form-control" name="media_file_name1" placeholder="File name" value="<?php if($update!="" && $table_rows['media']!="" && trim($table_rows['media'])!=";"){ echo 'Uploaded'; } ?>">
                                                            </div>
                                                            <div class="form-group col-md-5">
                                                                <input type="text" class="form-control" id="mediaFile" disabled="" placeholder="No file selected" value="<?php if($update!="" && $table_rows['media']!="" && trim($table_rows['media'])!=";"){ echo 'Uploaded'; } ?>">
                                                                <input type="file" name="media1" id="mediaImg" style=""  accept="" src="<?php echo set_value('media'); ?>" class="mediaFile" onchange="setFileName(this, 'mediaFile', 'mediaLink'); validateFiles();"/>
                                                                <small class="" style="color: red;" id="bigSizeFile"></small>
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="mediaImg" class="btn btn-sm btn-info waves-effect waves-light"><span>Choose</span></label>
                                                                <a href="" id="mediaLink" target="_blank" class="btn btn-sm btn-default"><i class="fa fa-search"></i></a>
                                                            </div>
                                                        </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <a href="javascript:void();" class="btn btn-sm btn-default" onclick="addFile();"><i class="fa fa-plus"></i> Add File</a>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12 col-lg-12" style="margin-left: 13px;">
                                                            <p class="" style="color: red;"><small>Caution: Files upload guidelines</small></p>
                                                            <ul class="" style="color: red;">
                                                                <li>For ebooks maximum file size 30mbs per episode</li>
                                                                <li>For audiobooks maximum file size 55mbs per episode</li>
                                                                <li>For Online video trainings maximum file size 200mbs per episode</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="virtual">
                                        <div class="form-group col-md-12">
                                            <label>Virtual Link</label>
                                            <input type="text" name="virtual_link" class="form-control" placeholder="http://" value="<?php if($update!="" && $table_rows['virtual_link']!=""){ echo $table_rows['virtual_link'];} ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Vendor to assign:</label>
                                        <select class="form-control" name="seller">
                                            <option value="">Select</option>
                                            <?php loadVendors($seller); ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Product Status:</label>
                                        <select class="form-control" name="product_status">
                                            <option value="live" <?php if($update!="" && $table_rows['product_status']=="live"){ echo 'selected';} ?>>Live Product</option>
                                            <option value="preorder" <?php if($update!="" && $table_rows['product_status']=="preorder"){ echo 'selected';} ?>>Pre-order Product</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <p style="color: red;">Note: Product upload may take longer due to the size of the uploaded files.</p>
                                    </div>
                                    <div class="col-md-12" id="resultMsg"></div>
                                    <div class="col-md-12 alert alert-info" id="url_panel">
                                        <div class="form-group">
                                            <p>Copy below product url to share. <a href="javascript:void(0);" onclick="copy_link();"><i class="fa fa-copy"></i></a></p>
                                            <textarea class="form-control" readonly="" id="url_text"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 p-t-10">
                                        <!-- <button type="submit" class="btn btn-success" name="register-form-submit" value="signup"><i class="ti-save"></i> Save</button> -->
                                        <?php if($update!=""){ ?>
                                        <a href="javascript:void();" class="btn btn-success mr-2 turnOnProgress" id="updateBtn">Update</a>
                                        <?php }else{ ?>
                                        <a href="javascript:void();" class="btn btn-success mr-2 turnOnProgress" id="saveBtn">Submit</a>
                                        <!-- <input type="submit" name="save" value="Save" class="btn btn-success"> -->
                                        <?php } ?>
                                        <a href="javascript:void();" class="btn btn-success mr-2 progressBarBtn"><i class="fa fa-spinner fa-spin"></i> Processing...</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<script type="text/javascript">
    var rowCounter=1;
    <?php if($update!=""){ ?>
        rowCounter=<?php echo $rowCounter; ?>;
    <?php } ?>
    $(document).ready(function(){
        /*$('.summernote').summernote({
            height: 150, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: false // set focus to editable area after initializing summernote
        });*/
        $('.summernote').summernote({
            height: 150,
            toolbar:[
                ['cleaner',['cleaner']], // The Button
                ['style',['style']],
                ['font',['bold','italic','underline','clear']],
                ['fontname',['fontname']],
                ['color',['color']],
                ['para',['ul','ol','paragraph']],
                ['height',['height']],
                ['table',['table']],
                ['insert',['media','link','hr', 'picture', 'video']],
                ['view',['fullscreen','codeview']],
                ['help',['help']]
            ],
            cleaner:{
                  action: 'both', // both|button|paste 'button' only cleans via toolbar button, 'paste' only clean when pasting content, both does both options.
                  newline: '<br>', // Summernote's default is to use '<p><br></p>'
                  notStyle: 'position:absolute;top:0;left:0;right:0', // Position of Notification
                  icon: '<i class="note-icon">Clean Data</i>',
                  keepHtml: false, // Remove all Html formats
                  keepOnlyTags: ['<p>', '<br>', '<ul>', '<li>', '<b>', '<strong>','<i>', '<a>'], // If keepHtml is true, remove all tags except these
                  keepClasses: false, // Remove Classes
                  badTags: ['style', 'script', 'applet', 'embed', 'noframes', 'noscript', 'html'], // Remove full tags with contents
                  badAttributes: ['style', 'start'], // Remove attributes from remaining tags
                  limitChars: false, // 0/false|# 0/false disables option
                  limitDisplay: 'both', // text|html|both
                  limitStop: false // true/false
            }
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#img-current-holder1').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#avatarImg").change(function(){
            readURL(this);
        });

        var m_type=document.getElementById('downloadable_media_type').value;
        if(m_type=='downloadable'){
            $('#downloadable').css('display', 'block');
            $('#virtual').css('display', 'none');
        }else{
            $('#virtual').css('display', 'block');
            $('#downloadable').css('display', 'none');
        }

        /*$('#form-data').submit(function(e){
            e.preventDefault();
             $.ajax({
                 url:'<?php //echo base_url();?>index.php/upload/do_upload',
                 type:"post",
                 data:new FormData(this),
                 processData:false,
                 contentType:false,
                 cache:false,
                 async:false,
                  success: function(data){
                      alert("Upload Image Successful.");
               }
             });
        });*/

        $('#saveBtn').click(function(){
           $('.turnOnProgress').css('display','none');
           $('.progressBarBtn').css('display','inline-block');
           var formData = new FormData($('#data-form')[0]);
        //   progressBarOnUpload();
           $.ajax({
               url: '<?php echo base_url(); ?>admin/product/save_product',
               type: 'POST',
               data: formData,
               async: true,
               cache: false,
               contentType: false,
               enctype: 'multipart/form-data',
               processData: false,
               success: function (data) {
                    if(isJSON(data)){
                        var resData=JSON.parse(data);
                        var msg = resData.msg;
                        var urlLink = resData.url;
                        var output = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> Uploaded Successfully </div>'; 
                        $('#resultMsg').html(output);
                        document.getElementById('url_text').value=urlLink;
                        $('#url_panel').css('display','block');
                        $('#saveBtn').css('display','none');
                        $('.progressBarBtn').css('display','none');
                        // document.getElementById("data-form").reset();
                        // window.location="";
                    }else{
                        $('#resultMsg').html(data);
                        $('.turnOnProgress').css('display','inline-block');
                        $('.progressBarBtn').css('display','none');
                    }
                },
                complete: function() {
                    /*$('.turnOnProgress').css('display','inline-block');
                    $('.progressBarBtn').css('display','none');*/
                },
                error: function( xhr, status, error ) {
                    $('#resultMsg').html(error);
                    console.log('ajax loading error...');
                    console.log(error);
                    $('.turnOnProgress').css('display','inline-block');
                    $('.progressBarBtn').css('display','none');
                    return false;
                }
           });
        });

        $('#updateBtn').click(function(){
           $('.turnOnProgress').css('display','none');
           $('.progressBarBtn').css('display','inline-block');
           var formData = new FormData($('#data-form')[0]);
           $.ajax({
               url: '<?php echo base_url(); ?>admin/product/update_product/<?php echo $update; ?>',
               type: 'POST',
               data: formData,
               async: true,
               cache: false,
               contentType: false,
               enctype: 'multipart/form-data',
               processData: false,
               success: function (data) {
                    if(isJSON(data)){
                        var resData=JSON.parse(data);
                        var msg = resData.msg;
                        var urlLink = resData.url;
                        var output = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> Updated Successfully </div>'; 
                        $('#resultMsg').html(output);
                        document.getElementById('url_text').value=urlLink;
                        $('#url_panel').css('display','block');
                        $('#updateBtn').css('display','none');
                        $('.progressBarBtn').css('display','none');
                        // document.getElementById("data-form").reset();
                        // window.location="<?php //echo base_url(); ?>admin/products";
                    }else{
                        $('#resultMsg').html(data);
                        $('.turnOnProgress').css('display','inline-block');
                        $('.progressBarBtn').css('display','none');
                    }
                },
                complete: function() {
                    /*$('.turnOnProgress').css('display','inline-block');
                    $('.progressBarBtn').css('display','none');*/
                },
                error: function( xhr, status, error ) {
                    $('#resultMsg').html(error);
                    console.log('ajax loading error...');
                    console.log(error);
                    $('.turnOnProgress').css('display','inline-block');
                    $('.progressBarBtn').css('display','none');
                    return false;
                }
           });
        });

    });

    function copy_link(){
        var link_tocopy=document.getElementById('url_text');
        link_tocopy.select();
        document.execCommand('copy');
        // window.prompt("Copy to clipboard: Ctrl+C, Enter", link_tocopy);
        // navigator.clipboard.writeText(link);
    }

    function checkDFile(){
        var mtype=document.getElementById('downloadable_media_type').value;
        if(mtype=='downloadable'){
            $('#downloadable').css('display', 'block');
            $('#virtual').css('display', 'none');
        }else{
            $('#virtual').css('display', 'block');
            $('#downloadable').css('display', 'none');
        }
    }

    function setFileName(input, holderID, prevLink, name_holder=""){
        var file = input.files[0].name;
        document.getElementById(holderID).value=file;

        var reader = new FileReader();
        reader.onload = function (e) {
            // $('#img-current-holder3').attr('src', e.target.result);
            $('#'+prevLink).attr('href', e.target.result);
            <?php if($update!=""){ ?>
                if(name_holder!="")
                $('#'+name_holder).removeAttr("disabled");
            <?php } ?>
        }
        reader.readAsDataURL(input.files[0]);
    }
    
    function addFile(){
        rowCounter++;
        var content='<div class="form-group col-md-4">\
                        <input type="text" class="form-control" name="media_file_name'+rowCounter+'" placeholder="File name">\
                    </div>\
                    <div class="form-group col-md-5">\
                        <input type="text" class="form-control media_file" id="mediaFile'+rowCounter+'" placeholder="No file selected" readonly="">\
                        <input type="file" name="media'+rowCounter+'" id="mediaImg'+rowCounter+'" style="" accept="" src="" class="mediaFile" onchange="setFileName(this, \'mediaFile'+rowCounter+'\', \'mediaPrevLink'+rowCounter+'\'); validateFiles();"/>\
                        <small class="" style="color: red;" id="bigSizeFile'+rowCounter+'"></small>\
                    </div>\
                    <div class="form-group col-md-3" id="innerRow">\
                        <label for="mediaImg'+rowCounter+'" class="btn btn-sm btn-info waves-effect waves-light"><span>Upload</span></label>\
                        <a href="javascript:void();" id="mediaPrevLink'+rowCounter+'" target="_blank" class="btn btn-sm btn-default"><i class="fa fa-search"></i></a>\
                        <a href="javascript:void();" class="btn btn-sm btn-default" onclick="removeRow(this);"><i class="fa fa-close"></i></a>\
                    </div>';
        const div = document.createElement('div');
        div.innerHTML = content;
        document.getElementById("mediaList").appendChild(div);
        document.getElementById("episode_count").value=rowCounter;
    }

    function removeRow(input){
        document.getElementById('mediaList').removeChild(input.parentNode.parentNode);
    }

    function isJSON(text) {
        if (typeof text !== "string") {
            return false;
        }
        try {
            JSON.parse(text);
            return true;
        } catch (error) {
            return false;
        }
    }
    
    function validateFiles(){
        
        var uploadedFiles = document.getElementById("mediaImg");
        
        //Check file extension
        var fileExtension = uploadedFiles.value;
        
        if(rowCounter > 1){

            var uploadedFiles2 = document.getElementById("mediaImg"+rowCounter+"");
            var fileExtension2 = uploadedFiles2.value;

            // Check if any file is selected.
            if (uploadedFiles2.files.length > 0) {
                for (var i = 0; i <= uploadedFiles2.files.length - 1; i++) {
     
                    var fsize = uploadedFiles2.files.item(i).size;
                    var file = Math.round((fsize / 1024));
                    
                    var imageExtension = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
                    var pdfDocExtension = /(\.pdf|\.txt|\.doc|\.docx|\.ppt|\.pptx)$/i;
                    var audioExtension = /(\.mp3)$/i;
                    var videoExtension = /(\.mp4)$/i;
                    
                    if (imageExtension.exec(fileExtension2)) {
                        if (file >= 10000) {
                            document.getElementById("bigSizeFile"+rowCounter+"").innerHTML = 'Please upload an image with required size';
                        }
                        else{
                            document.getElementById("bigSizeFile"+rowCounter+"").innerHTML = '';
                        }
                    }
                    
                   else if (pdfDocExtension.exec(fileExtension2)) {
                        if (file >= 30000) {
                            document.getElementById("bigSizeFile"+rowCounter+"").innerHTML = 'Please upload a PDF with required size';
                        }
                        else{
                            document.getElementById("bigSizeFile"+rowCounter+"").innerHTML = '';
                        }
                    }
                    
                   else if (audioExtension.exec(fileExtension2)) {
                        if (file >= 55000) {
                            document.getElementById("bigSizeFile"+rowCounter+"").innerHTML = 'Please upload an audio with required size';
                        }
                        else{
                            document.getElementById("bigSizeFile"+rowCounter+"").innerHTML = '';
                        }
                    }
                    
                    else if (videoExtension.exec(fileExtension2)) {
                        if (file >= 200000) {
                            document.getElementById("bigSizeFile"+rowCounter+"").innerHTML = 'Please upload a video with required size';
                        }
                        else{
                            document.getElementById("bigSizeFile"+rowCounter+"").innerHTML = '';
                        }
                    }
                }
            }
        }
        
        // Check if any file is selected.
        if (uploadedFiles.files.length > 0) {
            for (var i = 0; i <= uploadedFiles.files.length - 1; i++) {
 
                var fsize = uploadedFiles.files.item(i).size;
                var file = Math.round((fsize / 1024));
                
                var imageExtension = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
                var pdfDocExtension = /(\.pdf|\.txt|\.doc|\.docx|\.ppt|\.pptx)$/i;
                var audioExtension = /(\.mp3)$/i;
                var videoExtension = /(\.mp4)$/i;
                
                if (imageExtension.exec(fileExtension)) {
                    if (file >= 100) {
                        document.getElementById('bigSizeFile').innerHTML = 'Please upload an image with required size';
                    }
                    else{
                        document.getElementById('bigSizeFile').innerHTML = '';
                    }
                }
                
               else if (pdfDocExtension.exec(fileExtension)) {
                    if (file >= 30000) {
                        document.getElementById('bigSizeFile').innerHTML = 'Please upload a PDF with required size';
                    }
                    else{
                        document.getElementById('bigSizeFile').innerHTML = '';
                    }
                }
                
               else if (audioExtension.exec(fileExtension)) {
                    if (file >= 55000) {
                        document.getElementById('bigSizeFile').innerHTML = 'Please upload an audio with required size';
                    }
                    else{
                        document.getElementById('bigSizeFile').innerHTML = '';
                    }
                }
                
                else if (videoExtension.exec(fileExtension)) {
                    if (file >= 200000) {
                        document.getElementById('bigSizeFile').innerHTML = 'Please upload a video with required size';
                    }
                    else{
                        document.getElementById('bigSizeFile').innerHTML = '';
                    }
                }
            }
        }
    }
    
//     function progressBarOnUpload() {
//       var elem = document.getElementById("myBar");   
//       var width = 5;
//       var id = setInterval(frame, 50);
//       function frame() {
//         if (width >= 100) {
//           clearInterval(id);
//         } else {
//           width++; 
//           elem.style.width = width + '%'; 
//           elem.innerHTML = width * 1  + '%';
//         }
//       }
// }
</script>