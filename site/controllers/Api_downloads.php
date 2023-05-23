<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Api_downloads extends CI_Controller {

		public function __construct(){
	        parent::__construct();
	        $this->load->model('Users_model');
	        $this->userToken=$this->session->userdata('getvalue_user_idetification');
	    }

		public function index($file_name=""){
			
		}

		public function medias1($file_name=""){
			$file=$_SERVER['DOCUMENT_ROOT'];
			$file.='/works/getvalue/media/products/makjdfka_book_26042019_11_21.jpg';
			header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
		    ob_flush();
            flush();
            readfile($file);
            die();
			
		}

		public function medias($file_name=""){
			$data = $this->input->get();
			$download_token="";
			if(isset($data['dlt'])){
				$download_token=$data['dlt'];
			}else{
				die();
			}
			/*$username=$data['username'];
			$password=sha1(md5(md5($data['password'])));
			if(!isset($username) || !isset($password)){
				die("Empty credential");
			}
			$check=$this->Users_model->login($username, $password, 'api');
	        if($check=='incorrect'){
	        	echo 'Incorrect Username Or Password';
	        	die();
	        }else if($check=='not active'){
	        	echo 'Sorry! your account is not activated yet, please wait or contact us to fastern the activation.';
	        	die();
	        }else if($check=='blocked'){
	        	echo 'Your account is blocked, please contact us.';
	        	die();
	        }else if($check=='deleted'){
	        	echo 'Your account is deleted, please contact us or create new account.';
	        	die();
	        }else{
	        	
	        }*/
			$userID="";
			if(isset($download_token) && $download_token!="")
				$userID=getApiUserID($download_token);
			$this->session->set_userdata('account_type', getApiAccountType($download_token));
			$folder_name=rtrim($this->uri->slash_segment(2), '/');
			$product_file_name=rtrim($this->uri->slash_segment(3), '/');
			// die($folder_name.'='.$product_file_name." == ".$userID);
			if($this->session->userdata('account_type')!='admin'){
				if($this->session->userdata('account_type')=='customer'){
					if(is_customer_owner_file($userID, $product_file_name)!='owner'){
						echo 'File Not Found...1';
						header("HTTP/1.1 403 Unauthorized" );
						die();
					}
				}else{
					echo 'File Not Found...';
					header("HTTP/1.1 403 Unauthorized" );
					die();
				}
			}

			if($folder_name=='medias'){
				// generate the full server path for the requested uri by including the document root
				$file=explode("?", str_replace("api_downloads", "products", $_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI']));
				///$file=explode("?", str_replace("api_downloads", "products", $_SERVER['DOCUMENT_ROOT'].'/products/medias/'.$product_file_name));
				// $file=base_url().'products/medias/'.$product_file_name;
				// $file=$_SERVER['DOCUMENT_ROOT'];
				// $file.='media/products/makjdfka_book_26042019_11_21.jpg';
				$file=$file[0];
				// die($file);
				if(is_file($file) && file_exists($file)){
					/*To force file download use this code*/
		            header('Content-Description: File Transfer');
		            header('Content-Type: application/octet-stream');
		            header('Content-Disposition: attachment; filename="'.basename($file).'"');
		            header('Expires: 0');
		            header('Cache-Control: must-revalidate');
		            header('Pragma: public');
		            header('Content-Length: ' . filesize($file));
		            ob_clean();
				    ob_flush();
		            flush();
		            readfile($file);
		            exit;

		            /*To send file to the browser use this code*/
					// $this->sendFile($file); 
					// exit();
				}else {
					echo 'File Not Exist Found...';
					header("HTTP/1.1 403 Unauthorized" );
					exit();
				}
			}else{
				header("HTTP/1.1 403 Unauthorized" );
				die();
			}
		}

		function sendFile($file) {
			// read the file
			ob_clean();
            // flush();
			$data = file_get_contents($file);
			// get the mime type to have appropriate header
			$mime = get_mime_by_extension($file);
			// get the last modified date/time in correct format -> used for doConditionalGet()
			$lastModifiedString	= gmdate('D, d M Y H:i:s', filemtime($file)) . ' GMT';
			// get the etag (md5 hash) of the file  -> used for doConditionalGet()
			$etag = md5($data);
			// check if file has already been served without modification
			$this->doConditionalGet($etag, $lastModifiedString);
			// if not send headers
			header("Accept-Ranges: bytes");
			header("Content-type: $mime");
			header('Content-Length: ' . strlen($data));
			header('Content-Disposition: inline;filename="'.basename($file).'"');
			// send the file to the browser
			
			echo $data;
		}

		function doConditionalGet($etag, $lastModified) {
			// send last-modified and etag header
			header("Last-Modified: $lastModified");
			header("ETag: \"{$etag}\"");
			// See if the client has provided the required headers
			$if_none_match = isset($_SERVER['HTTP_IF_NONE_MATCH']) ?
				stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) : 
				false;
			
			$if_modified_since = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ?
				stripslashes($_SERVER['HTTP_IF_MODIFIED_SINCE']) :
				false;
			
			// no headers are present - return
			if (!$if_modified_since && !$if_none_match)
				return;
			
			//at least one header is present so check them
			if ($if_none_match && $if_none_match != $etag && $if_none_match != '"' . $etag . '"')
				return; // etag is there but doesn't match
			
			if ($if_modified_since && $if_modified_since != $lastModified)
				return; // if-modified-since is there but doesn't match
			
			// Nothing has changed since last request - serve a 304 and exit
			header('HTTP/1.1 304 Not Modified');
			exit();
		}

		public function isRightUser(){
	        if($this->session->userdata('account_type')==''){
	            redirect(base_url(''));
	        }
	    }
	    
	    private function is_logged_in(){
	        $is_logged_in = $this->session->userdata('getvalue_user_idetification');
	        if (!isset($is_logged_in) || $is_logged_in == "") {
	            redirect(base_url('login'));
	        }
	    }
	}
?>