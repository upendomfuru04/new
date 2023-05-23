<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Upload_model extends CI_Model{

	    public function __construct(){
	        parent::__construct();
    	}

    	public function upload_product_image($userfile, $fileName){
    		$res="";
			$img_path='media/products/';
			$thumb_path='media/products/thumb/';

			$upload=$this->uploadImage($img_path, $userfile, $fileName, "", "", "", true, true, 350, 402, $thumb_path);
			if($upload=="ok"){
				$res='ok';
			}else{
				$res=$upload;
			}
		   	return $res;
		}

// 		public function uploadImage($path, $userfile, $fileName, $max_size=1024, $width=1024, $height=768, $overwrite=true, $thumbnail=false, $thumb_width="", $thumb_height="", $thumb_path=""){
		public function uploadImage($path, $userfile, $fileName, $max_size=1024, $width=1024, $height=768, $overwrite=true, $thumbnail=false, $thumb_width="", $thumb_height="", $thumb_path=""){
			$userfile=$this->security->sanitize_filename($userfile, TRUE);
			$_SESSION['uploaded_file']="";
	      	$config['upload_path']   = $path;
	      	$config['allowed_types'] = 'gif|jpg|png|jpeg|PNG';
	      	$config['max_size']      = $max_size;
	      	$config['width'] = $width;
	      	$config['height'] = $height;
	      	$config['overwrite'] = $overwrite;
	      	$config['file_name'] = $fileName;
	      	$this->load->library('upload', $config);
	      	
	      	if(!$this->upload->do_upload($userfile)){
	         	return $this->upload->display_errors();
	      	}else{
		        $uploadedImage = $this->upload->data();
		        if($thumbnail){
			        $this->resizeImage($path, $thumb_path, $uploadedImage['file_name'], $thumb_width, $thumb_height);
			    }
		        $_SESSION['uploaded_file']=$uploadedImage['file_name'];
		        return "ok";
	      	} 
	   	}

	   	public function resizeImage($original_path, $thumb_path, $filename, $width=300, $height=""){
	      	$source_path = $original_path.''.$filename;
	      	$target_path = $thumb_path;
	      	$config_manip = array(
	          	'image_library' => 'gd2',
	          	'source_image' => $source_path,
	         	'new_image' => $target_path,
	          	'overwrite' => true,
	          	'maintain_ratio' => true,
	         	'width' => $width,
	         	'height' => $height
	      	);
	      	if($height!=""){
	      		// $config_manip['height']=$height;
	      	}
	      	$this->load->library('image_lib', $config_manip);
	      	if(!$this->image_lib->resize()){
	          	die($this->image_lib->display_errors());
	      	}
	      
	      	$this->image_lib->clear();
	   	}

    }
?>