<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Users_model extends CI_Model{

	    public function __construct(){
	        parent::__construct();
	        $this->load->database();
    	}

    	public function register($data){
    		$createdDate=time();
    		$token=generateToken();
    		$accTypeList=array();
    		$email=$this->security->xss_clean($data['email']);
    		$password=$this->security->xss_clean($data['password']);
    		$fname=$this->security->xss_clean($data['fname']);
    		$sname=$this->security->xss_clean($data['sname']);
    		$phone=$this->security->xss_clean($data['phone']);
    		$this->db->select('id');
	        $this->db->where('username', $email);
	        $this->db->where('status!=', '1');
	        $query3 = $this->db->get('tbl_users');
    		// $num = $this->db->where('username', $data['email'])->count_all_results('tbl_users');
    		$num = $query3->num_rows();
	        if ($num == 0) {
	        	$status='3';
	        	if($data['account_type']=='customer'){
	        		$status='0';
	        	}
	            $set=$this->db->insert('tbl_users', array(
		            'username' => $email,
		            'password' => $password,
		            'token' => $token,
		            'account_type' => $data['account_type'],
		            'status' => $status,
		            'createdDate' => $createdDate
		        ));		        

		        if($set){
		        	$this->db->select('id');
			        $this->db->where('username', $email);
			        $this->db->where('status', $status);
			        $query = $this->db->get('tbl_users');
			        if($query->num_rows() > 0){
			            foreach ($query->result_array() as $data1) {
			                $user_id=$data1['id'];
			            }
			        }

		        	if($data['account_type']=='customer'){
		        		$profile_url=generateLink('tbl_user_info', 'profile_url', $sname.' '.$fname, '');
		        		$this->db->insert('tbl_user_info', array(
				            'user_id' => $user_id,
				            'first_name' => str_replace("'", "`", $fname),
				            'surname' => str_replace("'", "`", $sname),
				            'gender' => $data['gender'],
				            'phone' => $phone,
				            'email' => $email,
				            'profile_url' => $profile_url,
				            'status' => '0',
				            'createdDate' => $createdDate
				        ));

				        if($this->db->affected_rows() > 0){
			        		$this->session->set_userdata('getvalue_user_idetification', $token);
			        		$this->session->set_userdata('account_type', $data['account_type']);
			        		$this->session->set_userdata('user_full_name', $fname.' '.$sname);
			        		return 'true';
			        	}else{
			        		return $this->db->_error_message();
			        	}
		        	}else{
		        		$profile_url=generateLink('tbl_sellers', 'profile_url', $sname.' '.$fname, '');
		        		$is_insider="0"; $is_outsider="0"; $is_contributor="0"; $is_vendor="0";
		        		$vendor_url=$data['vendor_url'];
        				$source_url="";
        				$source_expire_date="";
		        		if($data['account_type']=='insider'){ $is_insider='1';}
		        		if($data['account_type']=='outsider'){ $is_outsider='1';}
		        		if($data['account_type']=='contributor'){ $is_contributor='1';}
		        		if($data['account_type']=='vendor'){ 
		        			$is_vendor='1';
		        			if($vendor_url!=""){
		        				$this->db->select('vendor_url');
						        $this->db->where('vendor_url', $source_url);
						        $this->db->where('is_insider', '1');
						        $this->db->where('status', '0');
						        $query = $this->db->get('tbl_sellers');
						        if($query->num_rows() > 0){
						            $source_url="?vref=".$vendor_url;
						            $source_expire_date=date('Y-m-d', strtotime('+5 years'));
						        }else{
						        	$source_url="";
						        }
		        			}
		        		}
		        		$this->db->insert('tbl_sellers', array(
				            'user_id' => $user_id,
				            'full_name' => str_replace("'", "`", $fname).' '.str_replace("'", "`", $sname),
				            'gender' => $data['gender'],
				            'phone' => $phone,
				            'email' => $email,
				            'profile_url' => $profile_url,
				            'source_url' => $source_url,
				            'source_expire_date' => $source_expire_date,
				            'is_insider' => $is_insider,
				            'is_outsider' => $is_outsider,
				            'is_contributor' => $is_contributor,
				            'is_vendor' => $is_vendor,
				            'status' => '0',
				            'createdDate' => $createdDate
				        ));
				        
				        $this->db->where('user_id', $user_id);
				        $query = $this->db->get('tbl_sellers');
		            	foreach ($query->result_array() as $data){
			                $full_name=$data['full_name'];
			                if($data['avatar']!=""){ $user_avatar=$data['avatar'];}else{ $user_avatar="default.png";}
			                if($data['is_insider']==1){ 
			                	array_push($accTypeList, 'insider');
			                }
			                if($data['is_outsider']==1){ 
			                	array_push($accTypeList, 'outsider');
			                }
			                if($data['is_contributor']==1){ 
			                	array_push($accTypeList, 'contributor');
			                }
			                if($data['is_vendor']==1){ 
			                	array_push($accTypeList, 'vendor');
			                }
			            }
			            if($this->db->affected_rows() > 0){
			        		// $this->session->set_userdata('getvalue_user_idetification', $token);
			        		// $this->session->set_userdata('account_type', $data['account_type']);
		            		// $this->session->set_userdata('account_type_list', $accTypeList);
			        		// $this->session->set_userdata('user_full_name', $data['fname'].' '.$data['sname']);
			        		$activation_code=generate_verification_code();
			        		$val=array(
					            'activation_code' => $activation_code
					        );
							$this->db->where('id', $user_id);
					     	$this->db->update('tbl_users', $val);
					     	$subject="Account activation";
					     	$msg="Click the lik to activate your account ".base_url()."account_activation?vtcode=".$activation_code;
					     	$email=$email;
					     	$this->load->library('email');
							$this->email->from('info@getvalue.co', 'Get Value - Updates');
							$this->email->to($email);
							$this->email->subject($subject);
							$this->email->message($msg);
							$this->email->send();
			        		return true;
			        	}else{
			        		return $this->db->_error_message();
			        	}
		        	}
		        }else{
		        	return $this->db->_error_message();
		        }
	        }else{
	        	return "User already exist";
	        }
		}

    	public function update_seller_profile($data){
    		$full_name=sqlSafe($data['full_name']);
    		$gender=sqlSafe($data['gender']);
    		$email=sqlSafe($data['email']);
    		$phone=sqlSafe($data['phone']);
    		$address=sqlSafe($data['address']);
    		$postCode=sqlSafe($data['postCode']);
    		$user_id=sqlSafe($data['user_id']);
    		$avFile="";
    		unset($_SESSION['uploaded_file']);
    		// $avatar='ok';
    		if(isset($_FILES['avatar']) && $_FILES['avatar']['name']!=""){
    			$avatar=do_upload('media/seller_avatars', 'avatar', $full_name, '', '', '', 'image');
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
	            'full_name' => str_replace("'", "`", $full_name),
	            'gender' => $gender,
	            'email' => $email,
	            'phone' => $phone,
	            'address' => $address,
	            'avatar' => $avFile,
	            'postCode' => $postCode
	        );
			$this->db->where('user_id', $user_id);
			$this->db->where('status', '0');
	     	$update=$this->db->update('tbl_sellers', $values);
	     	if($update){
	     		return 'Success';
	     	}else{
	        	return $this->db->_error_message();
	        }    		
		}

    	public function update_profile($data){
    		$fname=sqlSafe($data['fname']);
    		$sname=sqlSafe($data['sname']);
    		$gender=sqlSafe($data['gender']);
    		$email=sqlSafe($data['email']);
    		$phone=sqlSafe($data['phone']);
    		$country=sqlSafe($data['country']);
    		$city=sqlSafe($data['city']);
    		$address=sqlSafe($data['address']);
    		$post_code=sqlSafe($data['post_code']);
    		$user_id=sqlSafe($data['user_id']);
    		$avFile="";
    		unset($_SESSION['uploaded_file']);
    		if(isset($_FILES['avatar']) && $_FILES['avatar']['name']!=""){
    			$avatar=do_upload('media/customer_avatars', 'avatar', $fname.' '.$sname, '', '', '', 'image');
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
	            'first_name' => str_replace("'", "`", $fname),
	            'surname' => str_replace("'", "`", $sname),
	            'gender' => $gender,
	            'email' => $email,
	            'phone' => $phone,
	            'country' => $country,
	            'city' => $city,
	            'address' => $address,
	            'post_code' => $post_code,
	            'avatar' => $avFile
	        );
			$this->db->where('user_id', $user_id);
			$this->db->where('status', '0');
	     	$update=$this->db->update('tbl_user_info', $values);
	     	if($update){
	     		return 'Success';
	     	}else{
	        	return $this->db->_error_message();
	        }    		
		}

    	public function login($username, $password, $old_password=""){
		    $this->db->where('username', $username); 
		    $this->db->where('status!=', '1'); 
		   	$query = $this->db->get('tbl_users');
		   	if(!empty($query->row_array())){
		   		$user_row=$query->row_array();
		   		if(password_verify($password, $user_row['password']) || $user_row['password']==$old_password){
			   		$accTypeList=array();
			   		$token=generateToken();
					$this->db->set('token', $token);
					$this->db->where('username', $username);
					// $this->db->where('password', $password);
					$this->db->where('status', '0');
			     	$this->db->update('tbl_users');
		        	$account_type=""; $full_name=""; $user_id=""; $password_reset="";
		            foreach ($query->result_array() as $data) {
		                $user_id=$data['id'];
		                $account_type=$data['account_type'];
		                $status=$data['status'];
		                $password_reset=$data['password_set'];
		            }

		            if($data['status']=='0'){
	                	/*Account is okay*/
	                	$user_avatar="";
	                	$this->db->where('user_id', $user_id);
			            if($account_type=='customer'){
			            	$query = $this->db->get('tbl_user_info');
			            	foreach ($query->result_array() as $data){
				                $full_name=$data['first_name'].' '.$data['surname'];
				                if($data['avatar']!=""){ $user_avatar=$data['avatar'];}else{ $user_avatar="default.png";}
				            }
			            }else if($account_type=='admin'){
			            	$query = $this->db->get('tbl_admin');
			            	foreach ($query->result_array() as $data){
				                $full_name=$data['first_name'].' '.$data['surname'];
				                if($data['avatar']!=""){ $user_avatar=$data['avatar'];}else{ $user_avatar="default.png";}
				            }
			            }else{
			            	$query = $this->db->get('tbl_sellers');
			            	foreach ($query->result_array() as $data){
				                $full_name=$data['full_name'];
				                if($data['avatar']!=""){ $user_avatar=$data['avatar'];}else{ $user_avatar="default.png";}
				                if($data['is_insider']==1){ 
				                	array_push($accTypeList, 'insider');
				                }
				                if($data['is_outsider']==1){ 
				                	array_push($accTypeList, 'outsider');
				                }
				                if($data['is_contributor']==1){ 
				                	array_push($accTypeList, 'contributor');
				                }
				                if($data['is_vendor']==1){ 
				                	array_push($accTypeList, 'vendor');
				                }
				            }
			            }
					   		     	
			        	$this->session->set_userdata('getvalue_user_idetification', $token);
			            $this->session->set_userdata('account_type', $account_type);
			            $this->session->set_userdata('account_type_list', $accTypeList);
						$this->session->set_userdata('user_full_name', $full_name);
						$this->session->set_userdata('user_avatar', $user_avatar);
			        	// $userID=getUserID($this->userToken);
			        	recordTrails('tbl_users', $username, 'User logged in', $user_id);
			        	if($account_type=='customer'){
			        		return '';
			        	}elseif($account_type=='admin'){
			        		if($password_reset==1){
			        			return 'admin/home/password_reset';
			        		}else{
			        			return 'admin';
			        		}
			        	}else{
			        		if($password_reset==1){
			        			return 'seller/password_reset';
			        		}else{
			        			return 'seller/'.$account_type;
			        		}
			        	}
	                }elseif($data['status']=='2'){
	                	/*Account is blocked*/
	                	return 'blocked';
	                }elseif($data['status']=='3'){
	                	/*Account is not activated*/
	                	return 'not active';
	                }elseif($data['status']=='1'){
	                	return 'deleted';
	                }
	            }else{
			   		return 'incorrect';
			 	}
		   	}else{
		   		return 'incorrect';
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
	                /*$this->load->library('email');

	                $config['protocol']    = 'smtp';
					$config['smtp_host']    = 'ssl://smtp.gmail.com';
					$config['smtp_port']    = '465';
					$config['smtp_timeout'] = '7';
					$config['smtp_user']    = 'sender_mailid@gmail.com';
					$config['smtp_pass']    = 'password';
					$config['charset']    = 'utf-8';
					$config['newline']    = "\r\n";
					$config['mailtype'] = 'text';
					$config['validation'] = TRUE;   

					$this->email->initialize($config);

	                $config['protocol'] = 'ssmtp';
					$config['smtp_host'] = 'ssl://ssmtp.gmail.com';
					$this->email->initialize($config);
					$this->email->from('info@getvalue.co', 'Get Value Inc - Help Center');
					$this->email->to($email);
					$this->email->subject('Password Recovery');
					$this->email->message($msg);
					$this->email->send();*/
					// die(":".$this->email->print_debugger());
					$g_mail="info@getvalue.co";
					$g_name="Get Value Inc - Help Center";
					$subject="Password Recovery";
					$to=$email;
					$headers = "Reply-To: $g_name <$g_mail>\r\n"; 
			      	$headers .= "Return-Path: $g_name <$g_mail>\r\n";
			      	$headers .= "From: $g_name <$g_mail>\r\n"; 

			      	if(mail($to, $subject, $msg, $headers)){
			      	}

	                $this->db->set('password', $encrypted_password);
					$this->db->where('id', $user_id);
			     	$this->db->update('tbl_users');
			     	return 'Success';
	            }
		   	}else{
		   		return 'incorrect';
		   	}
		}

		public function change_password($userID, $cpassword, $password, $old_password=""){
			$this->db->where('id', $userID); 
		   	// $this->db->where('password', $cpassword);
		   	$this->db->where('status', '0');
		   	$query = $this->db->get('tbl_users');
		   	if(!empty($query->row_array())){
		   		$user_row=$query->row_array();
		   		if(password_verify($cpassword, $user_row['password']) || $user_row['password']==$old_password){
					$this->db->set('password', $password);
					$this->db->set('password_set', '0');
					$this->db->where('id', $userID);
					// $this->db->where('password', $cpassword);
			     	$this->db->update('tbl_users');
		        	echo 'Success';
		            $desc=$this->session->userdata('user_full_name').' changed password';
		            recordTrails('tbl_users', $userID, $desc, $userID);
		        }else{
			   		echo ErrorMsg("Incorrect Current Password");
	            	$desc=$this->session->userdata('user_full_name').' attempt to change password failed, Incorrect Current Password';
	            	recordTrails('tbl_users', $userID, $desc, $userID);
			 	}
		    }else{
		   		echo ErrorMsg("Incorrect Current Password");
            	$desc=$this->session->userdata('user_full_name').' attempt to change password failed, Incorrect Current Password';
            	recordTrails('tbl_users', $userID, $desc, $userID);
		 	}
		}

		public function resend_activation_code($email){
			$this->db->select('id, activation_code');
	        $this->db->where('username', $email);
	        $this->db->where('status!=', '1');
	        $query = $this->db->get('tbl_users');
	        if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data1) {
	                $user_id=$data1['id'];
	                $activation_code=$data1['activation_code'];
	                if($activation_code==""){
	                	$activation_code=generate_verification_code();
	                }
	                $val=array(
			            'activation_code' => $activation_code
			        );
					$this->db->where('id', $user_id);
			     	$this->db->update('tbl_users', $val);
			     	$subject="Account activation";
			     	$msg="Click the lik to activate your account ".base_url()."account_activation?vtcode=".$activation_code;
			     	$this->load->library('email');
					$this->email->from('info@getvalue.co', 'Get Value - Updates');
					$this->email->to($email);
					$this->email->subject($subject);
					$this->email->message($msg);
					$this->email->send();
					return '<div class="alert alert-success">Activate your account by clicking the activation link sent to your email address. If you don`t get the email try to look in spam folder or <a href="'.base_url().'resend_activation_code?rvtcode='.$email.'" class="btn btn-xs btn-success" style="color: #fff;">RESEND THE LINK</a></div>';
	            }
	        }
		}

		public function account_activation($activation_code){
			$this->db->select('id, status');
		   	$this->db->where('activation_code', $activation_code);
		   	$this->db->where('status!=', '1');
		   	$query = $this->db->get('tbl_users');
		   	if($query->num_rows() > 0){
		   		foreach ($query->result_array() as $data) {
	                $status=$data['status'];
	                $user_id=$data['id'];
	                if($status=='3'){
	                	$this->db->set('status', '0');
						$this->db->where('id', $user_id);
				     	$this->db->update('tbl_users');
	                }
	            }
	            return 'Success';
		    }else{
		   		return 'Not Found';
		 	}
		}

		public function user_info($userID, $account_type){
			$this->db->where('user_id', $userID);
		   	$this->db->where('status', '0');
		   	if($account_type=='customer'){
		   		$query = $this->db->get('tbl_user_info');
		   	}else if($account_type=='admin'){
		   		$query = $this->db->get('tbl_admin');
		   	}else{
		   		$query = $this->db->get('tbl_sellers');
		   	}
		   	
		   	return $query->row_array();
		}
    }
?>