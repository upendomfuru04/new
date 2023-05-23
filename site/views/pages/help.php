<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Self Help Center - GetValue</title>
    <style type="text/css">
        .self_help ul li a{
            font-size: 1.5em;
            margin: .4em 0;
            line-height: 1.5em;
            word-wrap: break-word;
            padding: 4% 1% 1% 1%;
            color: #333;
        }
        .self_help ul li:hover, .self_help ul li a:hover{
            background: transparent;
            color: #debd60 !important;
        }
        .self_help ul li{
            width: 33.33333%;
            min-height: 66px;
            display: block;
            float: left;
        }
        .self_help ul .active a{
            color: #debd60 !important;
        }
        .nav-tabs{
            border-bottom: 1px solid #333;
        }
        .subList{
            border: none !important;
        }
        .subList h3{
            color: #debd60;
            text-align: left;
            font-size: 1.4em;
            line-height: 1.3;
            border-bottom: 1px solid #c5c5c5;

            border-bottom-width: 1px;
            background-color: #ffffff;
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
            border-bottom-color: #c5c5c5;
            padding-top: 4px;
            padding-bottom: 20px;
            padding-left: 4px;
            padding-right: 4px;
        }
        .topics-list{
            padding: 10px !important;
        }
        .topics-list li{
            display: block;
        }
        .topics-list li a{
            color: #333 !important;
        }
        .topics-list li a i{
            color: #ccc !important;
        }
        .tab-pane{
            padding-right: 20px;
            padding-left: 20px;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <section class="search-section home-search">
        <div class="masthead text-center">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 mx-md-auto">
                        <h1>Self Help Center</h1>
                        <p class="lead text-muted">Let's make making money easy for you! here you can get the support from articles and videos created by our #1 experts who created this system.</p>
                        <form method="GET" action="">
                            <input type="text" class="search-field" name="keyword" placeholder="Search Something ... ">
                            <button type="submit"><i class="fa fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="topics">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-md-12 self_help">
                    <ul class="nav nav-tabs">
                        <?php 
                            $counter=0; 
                            foreach($categories as $item){
                                $counter++;
                                if($counter==1){
                                    echo '<li class="active"><a data-toggle="tab" href="#tab'.$counter.'">'.$item->name.'</a></li>';
                                }else{
                                    echo '<li><a data-toggle="tab" href="#tab'.$counter.'">'.$item->name.'</a></li>';
                                }
                            }
                        ?>
                    </ul>

                    <div class="tab-content">
                        <?php 
                            $counter=0; 
                            foreach($categories as $itm){
                                $counter++;
                                $category=$itm->id;
                                getSelfHelpList($category, $counter);
                            }
                        ?>
                      <!-- <div id="home" class="tab-pane fade in active">
                        <h3>HOME</h3>
                        <p>Some content.</p>
                      </div>
                      <div id="menu1" class="tab-pane fade">
                        <h3>Menu 1</h3>
                        <p>Some content in menu 1.</p>
                      </div>
                      <div id="menu2" class="tab-pane fade">
                        <h3>Menu 2</h3>
                        <p>Some content in menu 2.</p>
                      </div> -->
                    </div>


                    <!-- <div class="topics-wrapper border-style">
                        <h3><a href="javascript:void();"><span class="fa fa-circle-o text-blue"></span>Topics</a></h3>
                        <ul class="topics-list">
                            <?php //foreach($helps as $post){ ?>
                            <li><a href="<?php //echo base_url().'self_help_details/'.$post->url; ?>"> <?php //echo ucfirst($post->title); ?> </a>
                            </li>
                            <?php //} ?>
                        </ul>
                        <ul class="topics-meta">
                            <li><?php //echo sizeof($helps); ?> Topics</li>
                        </ul>
                    </div> -->
                </div>
            </div>
        </div>
    </section>
</body>
</html>