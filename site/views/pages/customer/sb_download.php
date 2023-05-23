<!DOCTYPE html>
<html>
<head>
	<title>Download Product || GetValue</title>
</head>
<body>
	<?php
		if(isset($_GET['para']) && $_GET['para']!=""){
			$fl_para=$_GET['para'];
			// $path='media/products/media/';
			$path='products/medias/';
			if(strpos($media['media'], ";")!==FALSE){
				$mdia=explode(";", $media['media']);
				if($mdia[$fl_para]!=""){
					$file_name=pathinfo(trim($mdia[$fl_para]), PATHINFO_BASENAME);
					if(!file_exists($path.''.$file_name)){
						echo '<div class="alert alert-danger">SORRY! FILE ('.$media['name'].' - '.$fl_para.') NOT FOUND.</div>';
					}else{
						redirect(base_url().''.$path.''.$file_name);
						/*$this->load->helper('download');
						$data=file_get_contents(base_url().''.$path.''.$file_name);
						@ob_end_clean();
						force_download($file_name, $data);*/
					}
				}
			}else{
				exit();
			}
		}else{
			exit();
		}

	?>
</body>
</html>