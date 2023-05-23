<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class User_model extends CI_Model{

	    public function __construct(){
	        parent::__construct();
	        $this->load->database();
    	}

    	public function load_user_info($userID){
			$res=array();
		    $this->db->select('first_name, surname, gender, phone, email, avatar'); 
		    $this->db->from('tbl_users'); 
		    $this->db->join('tbl_user_info', 'tbl_users.id=user_id');
		   	$this->db->where('tbl_users.id', $userID);
		   	$query = $this->db->get();
		   	if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	            	$res['first_name']=$data['first_name'];
	                $res['surname']=$data['surname'];
	                $res['gender']=$data['gender'];
	                $res['phone']=$data['phone'];
	                $res['email']=$data['email'];
	                $res['avatar']=base_url('media/customer_avatars/').$data['avatar'];
	            }
	        }
	        return (object)$res;
		}

		public function change_password($username, $cpassword, $password, $old_password){
			$this->db->where('username', $username); 
		   	// $this->db->where('password', $cpassword);
		   	$this->db->where('status', '0');
		   	$query = $this->db->get('tbl_users');
		   	if(!empty($query->row_array())){
		   		$user_row=$query->row_array();
		   		if(password_verify($cpassword, $user_row['password']) || $user_row['password']==$old_password){
					$this->db->set('password', $password);
					$this->db->set('password_set', '0');
					$this->db->where('username', $username);
					// $this->db->where('password', $cpassword);
			     	$this->db->update('tbl_users');
		        	return 'Success';
		        }else{
			   		return "Incorrect Current Password";
			 	}
		    }else{
		   		return "Incorrect Current Password";
		 	}
		}

		public function recover_password($username, $new_password, $encrypted_password){
    		$this->db->select('id, account_type');
    		$this->db->from('tbl_users');
		    $this->db->where('username', $username);
		    $this->db->where('status!=', '1');
		   	$query = $this->db->get();
		   	if($query->num_rows() > 0){
		   		$email="";
	            foreach ($query->result_array() as $data) {
	                $user_id=$data['id'];
	                $account_type=$data['account_type'];
	                if($account_type=='customer'){
	                	$this->db->select('email');
			    		$this->db->from('tbl_user_info');
					    $this->db->where('user_id', $user_id);
					    $this->db->where('status', '0');
					   	$query = $this->db->get();
					   	if($query->num_rows() > 0){
				            foreach ($query->result_array() as $data) {
				                $email=$data['email'];
				            }
					   	}
	                }elseif($account_type=='admin'){
	                	$this->db->select('email');
			    		$this->db->from('tbl_admin');
					    $this->db->where('user_id', $user_id);
					    $this->db->where('status', '0');
					   	$query = $this->db->get();
					   	if($query->num_rows() > 0){
				            foreach ($query->result_array() as $data) {
				                $email=$data['email'];
				            }
					   	}
	                }else{
	                	$this->db->select('email');
			    		$this->db->from('tbl_sellers');
					    $this->db->where('user_id', $user_id);
					    $this->db->where('status', '0');
					   	$query = $this->db->get();
					   	if($query->num_rows() > 0){
				            foreach ($query->result_array() as $data) {
				                $email=$data['email'];
				            }
					   	}
	                }

	                $msg='Your new password is '.$new_password.', use it to login then you can change it';
	                $this->load->library('email');
					$this->email->from('info@getvalue.co', 'Get Value Inc - Help Center');
					$this->email->to($email);
					$this->email->subject('Password Recovery');
					$this->email->message($msg);
					$this->email->send();

	                $this->db->set('password', $encrypted_password);
					$this->db->where('id', $user_id);
			     	$this->db->update('tbl_users');
			     	return 'Success';
	            }
		   	}else{
		   		return 'Incorrect Username';
		   	}
		}

		public function register_customer($data){
    		$createdDate=time();
    		$token=generateToken();
    		$email=$this->security->xss_clean($data['email']);
    		$pass=$this->security->xss_clean($data['password']);
    		$password=sha1(md5(md5($pass)));
    		$fname=$this->security->xss_clean($data['first_name']);
    		$sname=$this->security->xss_clean($data['surname']);
    		$phone=$this->security->xss_clean($data['phone']);
    		$this->db->select('id');
	        $this->db->where('username', $email);
	        $this->db->where('status!=', '1');
	        $query3 = $this->db->get('tbl_users');
    		$num = $query3->num_rows();
	        if ($num == 0) {
	            $set=$this->db->insert('tbl_users', array(
		            'username' => $email,
		            'password' => $password,
		            'account_type' => 'customer',
		            'status' => '0',
		            'createdDate' => $createdDate
		        ));		        

		        if($set){
		        	$this->db->select('id');
			        $this->db->where('username', $email);
			        $this->db->where('status', '0');
			        $query = $this->db->get('tbl_users');
			        if($query->num_rows() > 0){
			            foreach ($query->result_array() as $data1) {
			                $user_id=$data1['id'];
			            }
			        }
	        		$profile_url=generateLink('tbl_user_info', 'profile_url', $sname.' '.$fname, '');
	        		$this->db->insert('tbl_user_info', array(
			            'user_id' => $user_id,
			            'first_name' => str_replace("'", "`", $fname),
			            'surname' => str_replace("'", "`", $sname),
			            'gender' => '',
			            'phone' => $phone,
			            'email' => $email,
			            'profile_url' => $profile_url,
			            'status' => '0',
			            'createdDate' => $createdDate
			        ));

			        if($this->db->affected_rows() > 0){
		        		return 'Success';
		        	}else{
		        		return $this->db->_error_message();
		        	}
		        }else{
		        	return $this->db->_error_message();
		        }
	        }else{
	        	return "Account already exist";
	        }
		}

		public function update_profile($data, $userID){
    		$avFile="";
    		unset($_SESSION['uploaded_file']);
    		if(isset($_FILES['avatar']) && $_FILES['avatar']['name']!=""){
    			$avatar=do_upload('media/customer_avatars', 'avatar', $data['first_name'].' '.$data['surname'], '', '', '', 'image');
    			if($avatar!='ok'){ 
    				return $avatar;
    				exit;
    			}
    		}
			if(!empty($this->session->userdata('uploaded_file')) && $this->session->userdata('uploaded_file')!=""){
				$avFile=$this->session->userdata('uploaded_file');
				$this->session->set_userdata('user_avatar', $avFile);
			}
			$values=array(
	            'first_name' => str_replace("'", "`", $data['first_name']),
	            'surname' => str_replace("'", "`", $data['surname']),
	            'email' => $data['email'],
	            'phone' => $data['phone'],
	            'avatar' => $avFile
	        );
			$this->db->where('user_id', $userID);
			$this->db->where('status', '0');
	     	$update=$this->db->update('tbl_user_info', $values);
	     	if($update){
	     		return 'Success';
	     	}else{
	        	return $this->db->_error_message();
	        }    		
		}
    }
?>