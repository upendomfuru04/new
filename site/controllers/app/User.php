<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('app/User_model');
    }

    public function index($index=""){
    }

    private function hash_password($password){
       return password_hash($password, PASSWORD_BCRYPT);
    }

	public function change_password(){
		$this->authenticate_app();
		$res=array();
    	$error=false;
    	$feedback="";
    	$data = $this->input->post();
    	if(!isset($data['username']) || $data['username']==""){
    		$this->return_error('Please! enter your username or logout then login.');
    	}
    	if(!isset($data['current_password']) || $data['current_password']==""){
    		$this->return_error('Please! enter the current password');
    	}
    	if(!isset($data['newpassword']) || $data['newpassword']==""){
    		$this->return_error('Please! enter new password');
    	}
    	if(!isset($data['renewpassword']) || $data['renewpassword']==""){
    		$this->return_error('Please! re-enter new password');
    	}
    	if($data['renewpassword']!=$data['newpassword']){
    		$this->return_error('Sorry! your new password does not match...');
    	}
		$username=$data['username'];
		$cpassword=$data['current_password'];
		$repassword=$data['renewpassword'];
		$password=$data['newpassword'];
		// $pass=sha1(md5(md5($password)));
		// $cpass=sha1(md5(md5($cpassword)));
        $pass=$this->hash_password($password);
        $old_password=sha1(md5(md5($cpassword)));

        $check=$this->User_model->change_password($username, $cpass, $pass, $old_password);
        if($check=='Success'){
        	$error=false;
    		$feedback="Password Changed Successfully";
        }else{
        	$error=true;
    		$feedback=$check;
        }

	    $res['error']=$error;
        $res['feedback']=$feedback;
        $result=json_encode($res);
        echo $result;
	}

	public function recover_password(){
		$this->authenticate_app();
		$res=array();
    	$error=false;
    	$feedback="";
    	$data = $this->input->post();
    	if(!isset($data['username']) || $data['username']==""){
    		$this->return_error('Please! enter your username or logout then login.');
    	}
    	$username=$data['username'];
    	$new_password=getRandomMixedCode(4).''.getRandomMixedCode(4).''.getRandomMixedCode(4);
    	// $encryted_password=sha1(md5(md5($new_password)));
        $encryted_password=$this->hash_password($new_password);

        $check=$this->User_model->recover_password($username, $new_password, $encryted_password);
        if($check=='Success'){
        	$error=false;
    		$feedback="New password sent to your email address, check your spam folder if it is not in your inbox";
        }else{
        	$error=true;
    		$feedback=$check;
        }
        $res['error']=$error;
        $res['feedback']=$feedback;
        $result=json_encode($res);
        echo $result;
	}

	public function register_customer(){ 
		$this->authenticate_app();
		$res=array();
    	$error=false;
    	$feedback="";
    	$data = $this->input->post();
    	if(!isset($data['first_name']) || $data['first_name']==""){
    		$this->return_error('Please! enter your first name.');
    	}
    	if(!isset($data['surname']) || $data['surname']==""){
    		$this->return_error('Please! enter your surname');
    	}
    	if(!isset($data['email']) || $data['email']==""){
    		$this->return_error('Please! enter your email address');
    	}
    	if(!isset($data['phone']) || $data['phone']==""){
    		$this->return_error('Please! enter your phone number');
    	}

		$details=array();

        $check=$this->User_model->register_customer($data);
        if($check=='Success'){
        	$error=false;
    		$feedback="Registered Successfully";
    		$username=$data['email'];
    		$userID=getUserIDFromUsername($username);
    		$details=$this->User_model->load_user_info($userID);
        }else{
        	$error=true;
    		$feedback=$check;
        }

	    $res['error']=$error;
        $res['feedback']=$feedback;
        $res['details']=$details;
        $result=json_encode($res);
        echo $result;
	}

	public function update_profile(){ 
		$this->authenticate_app();
		$res=array();
    	$error=false;
    	$feedback="";
    	$data = $this->input->post();
    	if(!isset($data['username']) || $data['username']==""){
    		$this->return_error('Sorry! empty user identity.');
    	}
    	if(!isset($data['first_name']) || $data['first_name']==""){
    		$this->return_error('Please! enter your first name.');
    	}
    	if(!isset($data['surname']) || $data['surname']==""){
    		$this->return_error('Please! enter your surname');
    	}
    	if(!isset($data['email']) || $data['email']==""){
    		$this->return_error('Please! enter your email address');
    	}
    	if(!isset($data['phone']) || $data['phone']==""){
    		$this->return_error('Please! enter your phone number');
    	}

		$username=$data['username'];
		$userID=getUserIDFromUsername($username);
		$details=array();

        $check=$this->User_model->update_profile($data, $userID);
        if($check=='Success'){
        	$error=false;
    		$feedback="Profile Updated Successfully";
    		$details=$this->User_model->load_user_info($userID);
        }else{
        	$error=true;
    		$feedback=$check;
        }

	    $res['error']=$error;
        $res['feedback']=$feedback;
        $res['details']=$details;
        $result=json_encode($res);
        echo $result;
	}

	function authenticate_app(){
		$data = $this->input->post();
		if(!isset($data['auth']) || $data['auth']!="b81bd363b70fb1a6e921150a4df6413d"){
			$res=array();
			$res['error']=true;
	        $res['feedback']="You are not authenticated";        
	        $result=json_encode($res);
			die($result);
		}
	}

	function return_error($feedback){
		$res=array();
		$res['error']=true;
        $res['feedback']=$feedback;        
        $result=json_encode($res);
		die($result);
	}
}