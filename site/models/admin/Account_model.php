<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Account_model extends CI_Model{

	    public function __construct(){
	        parent::__construct();
	        $this->load->database();
    	}

    	public function load_customers(){
			$result=array();
			$this->db->select('tbl_users.id, first_name, surname, gender, phone, email, avatar, tbl_users.createdDate, tbl_users.status');
		   	$this->db->from('tbl_user_info');
		   	$this->db->join('tbl_users', 'tbl_users.id=user_id');
		   	$this->db->where('tbl_users.status!=', '1');
		   	$this->db->order_by('tbl_users.createdDate', 'DESC');
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

    	public function load_system_admins(){
			$result=array();
			$this->db->select('tbl_users.id, first_name, surname, gender, phone, email, avatar, tbl_users.createdDate, tbl_users.status, role');
		   	$this->db->from('tbl_admin');
		   	$this->db->join('tbl_users', 'tbl_users.id=user_id');
		   	$this->db->where('tbl_users.status!=', '1');
		   	$this->db->order_by('tbl_users.createdDate', 'DESC');
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

    	public function load_vendors(){
			$result=array();
			$this->db->select('tbl_users.id, full_name, gender, phone, email, avatar, address, is_insider, is_outsider, is_vendor, is_contributor, is_trusted_vendor, tbl_users.createdDate, tbl_users.status, source_url, source_expire_date');
		   	$this->db->from('tbl_sellers');
		   	$this->db->join('tbl_users', 'tbl_users.id=user_id');
		   	// $this->db->join('tbl_seller_info', 'tbl_users.id=seller_id');
		   	$this->db->where('tbl_sellers.is_vendor', '1');
		   	// $this->db->where('tbl_seller_info.seller_type=', 'vendor');
		   	$this->db->where('tbl_users.status!=1 AND tbl_users.status!=3');
		   	$this->db->order_by('tbl_users.createdDate', 'DESC');
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

    	public function load_affiliates(){
			$result=array();
			$this->db->select('tbl_users.id, full_name, gender, phone, email, avatar, address, is_insider, is_outsider, is_vendor, is_contributor, is_trusted_vendor, tbl_users.createdDate, tbl_users.status');
		   	$this->db->from('tbl_sellers');
		   	$this->db->join('tbl_users', 'tbl_users.id=user_id');
		   	$this->db->where("(tbl_sellers.is_insider='1' OR tbl_sellers.is_outsider='1' OR tbl_sellers.is_contributor='1') AND tbl_sellers.status!='1'", null, false);
		   	$this->db->where('tbl_users.status!=1 AND tbl_users.status!=3', NULL, false);
		   	$this->db->order_by('tbl_users.createdDate', 'DESC');
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

    	public function load_pending_accounts(){
			$result=array();
			$this->db->select('tbl_users.id, full_name, gender, phone, email, avatar, address, is_insider, is_outsider, is_vendor, is_contributor, is_trusted_vendor, tbl_users.createdDate, tbl_users.status');
		   	$this->db->from('tbl_sellers');
		   	$this->db->join('tbl_users', 'tbl_users.id=user_id');
		   	$this->db->where("(tbl_sellers.is_insider='1' OR tbl_sellers.is_outsider='1' OR tbl_sellers.is_contributor='1' OR tbl_sellers.is_vendor='1') AND tbl_sellers.status!='1' AND tbl_users.status='3'", null, false);
		   	// $this->db->where('tbl_users.status!=', '3');
		   	$this->db->order_by('tbl_users.createdDate', 'DESC');
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

    	public function save_new_system_admin($data, $userID){    		
    		$createdDate=time();
    		$email=sqlSafe($data['email']);
    		$first_name=sqlSafe($data['first_name']);
    		$surname=sqlSafe($data['surname']);
    		$gender=sqlSafe($data['gender']);
    		$phone=sqlSafe($data['phone']);
    		$user_id="";
    		$this->db->select('id');
	        $this->db->where('username', $email);
	        $this->db->where('account_type', 'admin');
	        $this->db->where('status!=','1');
	        $query1 = $this->db->get('tbl_users');

	        if($query1->num_rows() == 0){
	        	// $password=sha1(md5(md5(strtolower($surname))));
	        	$password=password_hash($surname, PASSWORD_BCRYPT);;
	        	$set1=$this->db->insert('tbl_users', array(
		            'username' => $email,
		            'password' => $password,
		            'account_type' => 'admin',
		            'role' => '2',
		            'createdBy' => $userID,
		            'status' => '0',
		            'createdDate' => $createdDate
		        ));

	        	if($set1){
		        }else{
		        	return ErrorMsg($this->db->_error_message());
		        }
	        }

    		$this->db->select('id');
	        $this->db->where('username', $email);
	        $this->db->where('account_type', 'admin');
	        $this->db->where('status!=','1');
	        $query2 = $this->db->get('tbl_users');
	        if($query2->num_rows() > 0){
	        	foreach($query2->result() as $rows){
	        		$user_id=$rows->id;
	        	}
	        }

	        if($user_id==""){
	        	return "This user account already exist";
	            exit();
	        }

    		$this->db->select('id');
	        $this->db->where('first_name', $first_name);
	        $this->db->where('surname', $surname);
	        $this->db->where('email', $email);
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_admin');
	        if($query->num_rows() > 0){
	            return "This admin already exist";
	            exit();
	        }

            $set=$this->db->insert('tbl_admin', array(
	            'user_id' => $user_id,
	            'first_name' => $first_name,
	            'surname' => $surname,
	            'gender' => $gender,
	            'email' => $email,
	            'phone' => $phone,
	            'createdBy' => $userID,
	            'status' => '0',
	            'createdDate' => $createdDate
	        ));

	        if($set){
	        	return 'Success';
	        }else{
	        	return ErrorMsg($this->db->_error_message());
	        }   		
		}

		public function check_new_seller_accounts(){
			$res=0;
			$this->db->select('id');
			// $this->db->distinct();
			$this->db->from('tbl_users');
		   	$this->db->where('status', '3');
	   		$sql = $this->db->get();   	
		   	$res=$sql->num_rows();
		   	return $res;
		}

		public function save_account_levels($data, $userID){
			$seller_id=sqlSafe($data['seller']);
			$is_vendor="0"; $is_insider="0"; $is_outsider="0"; $is_contributor="0";
			if(isset($data['is_vendor']))
				$is_vendor='1';
			if(isset($data['is_insider']))
				$is_insider='1';
			if(isset($data['is_outsider']))
				$is_outsider='1';
			if(isset($data['is_contributor']))
				$is_contributor='1';

            $set=array(
	            'is_vendor' => $is_vendor,
	            'is_insider' => $is_insider,
	            'is_outsider' => $is_outsider,
	            'is_contributor' => $is_contributor
	        );

	        $this->db->where('user_id', $seller_id);
	     	$this->db->update('tbl_sellers', $set);
        	return 'Success';
		}

		public function is_trusted_vendor($account, $userID){
			$this->db->set('is_trusted_vendor', '1');
	   		$this->db->where('user_id', $account);
	     	$this->db->update('tbl_sellers');
		   	return 'Success';
		}

		public function is_not_trusted_vendor($account, $userID){
			$this->db->set('is_trusted_vendor', '0');
	   		$this->db->where('user_id', $account);
	     	$this->db->update('tbl_sellers');
		   	return 'Success';
		}

		public function activate_new_seller_account($account, $userID){
			$this->db->set('status', '0');
			// $this->db->set('activated_by', $userID);
			// $this->db->set('activated_date', date("Y-m-d H:i:s"));
	   		$this->db->where('id', $account);
	     	$this->db->update('tbl_users');

	     	$username=""; $account_type="";
	     	$this->db->select("username, account_type");
	     	$this->db->from("tbl_users");
	     	$this->db->where("id", $account);
	     	$query=$this->db->get();
	     	if($query->num_rows()>0){
	     		foreach ($query->result_array() as $data) {
	     			$username=$data['username'];
	     			$account_type=$data['account_type'];
	     			$msg="Account activation is complete, username is ".$username." and account type is ".ucwords($account_type.". THANKS FOR USING getvalueinc.com");
	     			$this->load->library('email');
	     			$this->email->set_header('Organization', 'Get Value Inc');
					$this->email->set_header('MIME-Version', '1.0');
					$this->email->set_header('Content-type', 'text/plain; charset=iso-8859-1');
					$this->email->set_header('X-Priority', '3');
					$this->email->set_header('X-Mailer', "PHP".phpversion());
					$this->email->set_header('Return-Path', "GetValueInc <info@getvalue.co>");
				  	// $headers .= "Return-Path: The Sender <sender@sender.com>\r\n";
				  	// $headers .= "From: The Sender <senter@sender.com>\r\n";
				  	// $headers .= "Organization: Sender Organization\r\n";
				  	// $headers .= "MIME-Version: 1.0\r\n";
				  	// $headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
				  	// $headers .= "X-Priority: 3\r\n";
				  	// $headers .= "X-Mailer: PHP". phpversion() ."\r\n" 

					$this->email->from('info@getvalue.co', 'Get Value Inc - Updates');
					$this->email->to($username);
					$this->email->reply_to('info@getvalue.co', 'Get Value Inc - Support');
					$this->email->subject("Thanks For Choosing Us");
					$this->email->message($msg);
					$this->email->send();
					/*if($this->email->send()){
				        return true;
				    }else{
				        return $this->email->print_debugger();
				        die();
				    }*/
	     		}
	     	}
	     	$this->db->select('tbl_users.id');
	        $this->db->from('tbl_sellers');
	        $this->db->join('tbl_users', 'tbl_users.id=user_id');
	        $this->db->where('tbl_sellers.is_vendor=', '1');
	        $this->db->where('tbl_users.status!=1 AND tbl_users.status!=3');
	        $query = $this->db->get();
	        if($query->num_rows()>0){
	        	foreach($query->result_array() as $row){
	            	$seller_uid=$row['id'];
	            	$this->db->insert('tbl_affiliate_notification', array(
			            'user_id' => $seller_uid,
			            'seller_id' => $account,
			            'is_read' => '1',
			            'status' => '0',
			            'createdDate' => time()
			        ));
	            }
	        }
		   	return 'Success';
		}

		public function activate_seller_account($account, $userID){
			$this->db->set('status', '0');
			// $this->db->set('activated_by', $userID);
			// $this->db->set('activated_date', date("Y-m-d H:i:s"));
	   		$this->db->where('id', $account);
	     	$this->db->update('tbl_users');

			$this->db->set('status', '0');
	   		$this->db->where('user_id', $account);
	     	$this->db->update('tbl_sellers');
		   	return 'Success';
		}

		public function diactivate_seller_account($account, $userID){
			$this->db->set('status', '2');
			// $this->db->set('diactivated_by', $userID);
			// $this->db->set('diactivated_date', date("Y-m-d H:i:s"));
	   		$this->db->where('id', $account);
	     	$this->db->update('tbl_users');

			$this->db->set('status', '2');
	   		$this->db->where('user_id', $account);
	     	$this->db->update('tbl_sellers');
		   	return 'Success';
		}

		public function activate_admin_account($account, $userID){
			$this->db->set('status', '0');
			// $this->db->set('activated_by', $userID);
			// $this->db->set('activated_date', date("Y-m-d H:i:s"));
	   		$this->db->where('id', $account);
	     	$this->db->update('tbl_users');
		   	return 'Success';
		}

		public function diactivate_admin_account($account, $userID){
			$this->db->set('status', '2');
	   		$this->db->where('id', $account);
	     	$this->db->update('tbl_users');
		   	return 'Success';
		}

		public function activate_customer_account($account, $userID){
			$this->db->set('status', '0');
			// $this->db->set('activated_by', $userID);
			// $this->db->set('activated_date', date("Y-m-d H:i:s"));
	   		$this->db->where('id', $account);
	     	$this->db->update('tbl_users');
		   	return 'Success';
		}

		public function diactivate_customer_account($account, $userID){
			$this->db->set('status', '2');
	   		$this->db->where('id', $account);
	     	$this->db->update('tbl_users');
		   	return 'Success';
		}

		public function delete_customer_account($account, $userID){
			$this->db->set('status', '1');
	   		$this->db->where('id', $account);
	     	$this->db->update('tbl_users');
		   	return 'Success';
		}

		public function reset_user_password($account, $userID){ //die($account);
			$this->db->select('username');
	        $this->db->from('tbl_users');
	        $this->db->where('id', $account);
	        // $this->db->where('status', '1');
	        $query = $this->db->get();
	        if($query->num_rows()>0){
	        	foreach($query->result_array() as $row){
	            	$username=$row['username'];
	            	$password=password_hash(strtolower($username), PASSWORD_BCRYPT);
	            	$this->db->set('password', $password);
			   		$this->db->where('id', $account);
			   		// $this->db->where('status', '0');
			     	$set=$this->db->update('tbl_users');
			     	if($set){
				   		return 'Success';
				   	}else{
				   		return 'Failed to reset password';
				   	}
	            }
	        }else{
		   		return 'Username not found...';
		   	}
		}

		public function delete_seller_account($account, $userID){
			$this->db->set('status', '1');
	   		$this->db->where('id', $account);
	     	$this->db->update('tbl_users');

			$this->db->set('status', '1');
	   		$this->db->where('user_id', $account);
	     	$this->db->update('tbl_sellers');
		   	return 'Success';
		}

    }
