<!DOCTYPE html>
<html>
<head>
	<title>Download Product || GetValue</title>
</head>
<body>
	<p>Please use the Mobile App to access your products. <a href="https://play.google.com/store/apps/details?id=com.getvalue.getvalue" target="_blank"><i class="fa fa-download"></i> Download App Now</a></p>
	<?php 
		$path='products/medias/';
		if(strpos($media['media'], ";")!==FALSE){
			$mdia=explode(";", $media['media']);
			for ($i=0; $i < sizeof($mdia); $i++) {
				if($mdia[$i]!=""){
					// echo '<div class="alert alert-info">'.$media['name'].' - '.($i+1).' <a href="'.base_url().'customer/sb_download/?prd='.$media['id'].'&para='.$i.'" class="btn btn-xs btn-default" target="_blank">Download</a></div>';
					echo '<div class="alert alert-info">'.$media['name'].' - '.($i+1).' <a href="https://play.google.com/store/apps/details?id=com.getvalue.getvalue" target="_blank" class="btn btn-xs btn-default" target="_blank">Download</a></div>';
				}
			}
		}else{
			echo '<div class="alert alert-info">Download starts in a few secs...</div>';
			$file_name=pathinfo($media['media'], PATHINFO_BASENAME);
			if(!file_exists($path.''.$file_name)){
				echo '<div class="alert alert-danger">SORRY! FILE ('.$media['name'].') NOT FOUND.</div>';
			}else{
				redirect(base_url().''.$path.''.$file_name);
				/*$this->load->helper('download');
				$data=file_get_contents(base_url().''.$path.''.$file_name);
				@ob_end_clean();
				force_download($file_name, $data);*/
			}
		}
	?>
</body>
</html>