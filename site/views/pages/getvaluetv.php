<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <title>GetValue TV</title>
</head>
<body>
    <section id="content" style="padding: 50px 0;" class="gold-bg">
        <div class="content-wrap">
            <div class="container clearfix">
                
                <div class="row">
                    <div class="col-md-9">
                        <h2>GetValue TV</h2>
                        <p>
                            <span style="padding-right:15px;">25 Subscriber </span>
                            <span style="padding-right:15px;">21 Videos </span>
                            <span style="padding-right:15px;">347 Views </span>
                        </p>
                        <p>
                            Official Youtube Channel of GetValue (GetValue.co) A Global Online Digital Information Products Retailer
                        </p>
                    </div>
                    <div class="col-md-3">
                        <a href="" class="button button-3d button-red nomargin">
                            <i class="fa fa-youtube-play" style="padding-right:15px;"></i> SUBSCRIBE
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>    
    
    <section id="content" style="margin-top: 50px;">
        <div class="content-wrap">
            <div class="container clearfix">
                
    			<!-- Post Content
    			============================================= -->
    			<div class="row">
                    <?php foreach($tv_post as $post){ ?>
        				<div class="col-md-4" style="padding:15px;">
        					<iframe width="100%" height="220" src="<?php echo $post->link; ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        				</div>
                    <?php } ?>
                
                </div>
            </div>
        </div>
    </section>
</body>
</html>
