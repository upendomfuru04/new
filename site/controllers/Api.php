<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Api extends CI_Controller {

		public function __construct(){
	        parent::__construct();
	        $this->load->model('api/Api_model');
	    }

	    public function index($page){
	    	
	    }

	    private function hash_password($password){
		   return password_hash($password, PASSWORD_BCRYPT);
		}

	    public function authenticate(){
	    	// $data = json_decode(file_get_contents('php://input'), true);
	    	$data = $this->input->post();
	    	$res=array();
	    	$error=false;
	    	$details=NULL;
	    	$msg="";
	    	$username="";
	    	$password="";
	    	if(!isset($data['username']) || !isset($data['password'])){
	    		$error=true;
	    		$msg="Empty username or password";
	    		$res['error']=$error;
		        $res['message']=$msg;
	        	$res['details']=$details;
		        $result=json_encode($res);
		        die($result);
	    	}
	    	$username=$data['username'];
	    	$password=$data['password'];
	    	
	    	$old_password=sha1(md5(md5($data['password'])));
	        $check=$this->Api_model->login($username, $password, $old_password);
	        if($check=='Success'){
	        	$error=false;
	    		$msg="Logged Successfully";
	    		$details=$this->Api_model->load_user_info($username, $password, $old_password);
	        }else{
	        	$error=true;
	    		$msg=$check;
	        }
	        $res['error']=$error;
	        $res['message']=$msg;
	        $res['details']=$details;
	        $result=json_encode($res);
	        echo $result;
	    }

	    public function orders(){
	    	$data = $this->input->get();
	    	$res=array();
	    	$error=false;
	    	$msg="";
	    	$email="";

	    	if(!isset($data['email']) || $data['email']=="" || empty($data['email'])){
	    		$error=true;
	    		$msg="Failed: Empty email";
	    		$res['error']=$error;
		        $res['message']=$msg;
		        $result=json_encode($res);
		        die($result);
	    	}
	    	$email=$data['email'];
	    	
	        $orders=$this->Api_model->orders($email);
	        if(is_array($orders)){
	        	$error=false;
	    		$msg="Success";
	        }else{
	        	$error=true;
	    		$msg=$orders;
	    		$orders="";
	        }
	        $res['error']=$error;
	        $res['order']=$orders;
	        $res['message']=$msg;
	        $result=json_encode($res);
	        echo $result;
	    }

	}
?>