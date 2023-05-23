<?php
    $product="";
    if($update!=""){ $product=$edit_rows['product'];}
?>
<!DOCTYPE html>
<html>
<head>
    
</head>
<body>
    <h1 class="breadcumb"></h1>
    <div class="home-page">
        <div class="row p-t-20">
            <div class="col-md-9 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"> Send New Message</h3>
                    </div>
                    <div class="panel-body p-40 row">
                        <form id="data-form" method="POST">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label>Recepient:</label>
                                    <input type="hidden" name="receiver" class="form-control" value="<?php echo $recepient; ?>">
                                    <input type="text" class="form-control" value="<?php if(isset($info)){ echo ucwords($info['full_name']); } ?>" readonly="">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Subject:</label>
                                    <input type="text" name="subject" class="form-control" placeholder="" value="">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Message:</label>
                                    <textarea class="form-control summernote" name="message"></textarea>
                                </div>
                                <div class="col-md-12" id="resultMsg"></div>
                                <div class="col-md-12 p-t-10">
                                    <a href="javascript:void();" class="btn btn-success mr-2 turnOnProgress" id="saveBtn">Send</a>
                                    <a href="javascript:void();" class="btn btn-success mr-2 progressBarBtn"><i class="fa fa-spinner fa-spin"></i> Processing...</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<script type="text/javascript">
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

        $('#saveBtn').click(function(){
           $('.turnOnProgress').css('display','none');
           $('.progressBarBtn').css('display','inline-block');
           $.ajax({
                url: '<?php echo base_url(); ?>seller/home/save_new_message',
                type: 'POST',
                data:$('#data-form').serialize(),
                async: true,
                processData: false,
                success: function (data) {
                    if(data.trim()=='Success'){
                        var output = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times; </button> Success </div>'; 
                        $('#resultMsg').html(output);
                        document.getElementById("data-form").reset();
                        window.location="";
                    }else{
                        $('#resultMsg').html(data);
                    }
                    $('.turnOnProgress').css('display','inline-block');
                    $('.progressBarBtn').css('display','none');
                },
                error: function( xhr, status, error ) {
                    $('#resultMsg').html(error);
                    $('.turnOnProgress').css('display','inline-block');
                    $('.progressBarBtn').css('display','none');
                    return false;
                }
            });
        });

    });
</script>