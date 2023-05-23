<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Admin_model extends CI_Model{

	    public function __construct(){
	        parent::__construct();
	        $this->load->database();
        	$this->load->model('Upload_model');
    	}

    	function getTotalCustomers(){
	        $this->db->select('id');
	        $this->db->where('account_type', 'customer');
	        $this->db->where('status', '0');
	        $query = $this->db->get('tbl_users');
	        return $query->num_rows();
	    }

	    function getTotalPendingPost(){
	        $this->db->select('id');
	        $this->db->from('tbl_blog_post');
	        $this->db->where('is_approved', '1');
	        $this->db->where('status', '0');
	        $query = $this->db->get();
	        return $query->num_rows();
	    }

	    function getTotalPendingCommissions(){
	        $res=0;
	        $this->db->select('amount');
	        $this->db->where('commissionStatus', '2');
	        $this->db->where('status', '0');
	        $query = $this->db->get('tbl_commissions');
	        if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	                $res=$res+$data['amount'];
	            }
	        }
	        if(is_numeric($res))
	            $res=number_format($res);
	        return $res;
	    }

	    function getTotalPendingAccounts(){
	        $this->db->select('tbl_users.id');
	        $this->db->from('tbl_sellers');
	        $this->db->join('tbl_users', 'tbl_users.id=user_id');
	        $this->db->where("(tbl_sellers.is_insider='1' OR tbl_sellers.is_outsider='1' OR tbl_sellers.is_contributor='1' OR tbl_sellers.is_vendor='1') AND tbl_sellers.status!='1' AND tbl_users.status='3'", null, false);
	        $query = $this->db->get();
	        return $query->num_rows();
	    }

	    function getTotalVendors(){
	        $this->db->select('tbl_users.id');
	        $this->db->from('tbl_sellers');
	        $this->db->join('tbl_users', 'tbl_users.id=user_id');
	        $this->db->where('tbl_sellers.is_vendor=', '1');
	        $this->db->where('tbl_users.status!=1 AND tbl_users.status!=3');
	        $query = $this->db->get();
	        return $query->num_rows();
	    }

	    function getTotalAffiliates(){
	        $this->db->select('tbl_users.id');
	        $this->db->from('tbl_sellers');
	        $this->db->join('tbl_users', 'tbl_users.id=user_id');
	        $this->db->where("(tbl_sellers.is_insider='1' OR tbl_sellers.is_outsider='1' OR tbl_sellers.is_contributor='1') AND tbl_sellers.status!='1'", null, false);
	        $this->db->where('tbl_users.status!=1 AND tbl_users.status!=3', NULL, false);
	        $query = $this->db->get();
	        return $query->num_rows();
	    }

	    function getTotalVisits(){
	        $res=0;
	        $this->db->select('counter');
	        $this->db->from('site_views');
	        $this->db->where('status', '0');
	        $query = $this->db->get();
	        if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	                $res=$res+$data['counter'];
	            }
	        }
	        return $res;
	    }

		public function user_info($userID){
			$this->db->where('user_id', $userID);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_admin');		   	
		   	return $query->row_array();
		}

    	public function update_admin_profile($data){
    		$email=sqlSafe($data['email']);
    		$first_name=sqlSafe($data['first_name']);
    		$surname=sqlSafe($data['surname']);
    		$gender=sqlSafe($data['gender']);
    		$phone=sqlSafe($data['phone']);
    		$user_id=sqlSafe($data['user_id']);

    		$avFile="";
    		unset($_SESSION['uploaded_file']);
    		// $avatar='ok';
    		if(isset($_FILES['avatar']) && $_FILES['avatar']['name']!=""){
    			$avatar=do_upload('media/admin_avatars', 'avatar', $first_name.' '.$surname, '', '', '', 'image');
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
	            'first_name' => $first_name,
	            'surname' => $surname,
	            'gender' => $gender,
	            'email' => $email,
	            'phone' => $phone,
	            'avatar' => $avFile
	        );
			$this->db->where('user_id', $user_id);
			$this->db->where('status', '0');
	     	$update=$this->db->update('tbl_admin', $values);
	     	if($update){
				$this->session->set_userdata('user_full_name', $first_name.' '.$surname);
	     		return 'Success';
	     	}else{
	        	return $this->db->_error_message();
	        }    		
		}

		public function load_message_detail($msg, $userID){
			$this->db->select('id, subject, message, createdBy, createdDate, receiver');
			$this->db->from('tbl_msg');
		   	$this->db->where('id', $msg);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0)
		   	return $query->row_array();
		}

		public function load_message_receivers($msg){
			$this->db->select('id, msg_id, user_id, receiver_email, is_read, account_type, read_time');
			$this->db->from('tbl_msg_readers');
		   	$this->db->where('msg_id', $msg);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0)
		   	return $query->result();
		}

		public function delete_subscriber($subscriber, $userID){
			$this->db->set('status', '1');
	   		$this->db->where('id', $subscriber);
	     	$this->db->update('tbl_subscribers');
		   	return 'Success';
		}

		public function load_messages(){
			$result=array();
			$this->db->select('id, subject, createdDate');
			$this->db->from('tbl_msg');
		   	$this->db->where('parent_msg', '');
		   	$this->db->where('status', '0');
		   	$this->db->order_by('createdDate', 'DESC');
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function subscribers(){
			$result=array();
			$this->db->select('id, email, time');
			// $this->db->distinct();
			$this->db->from('tbl_subscribers');
		   	$this->db->where('status', '0');
		   	$this->db->order_by('time', 'DESC');
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function media_center($userID){
			$result=array();
			$this->db->select('id, image, name, preview, product_url, preview_type');
			// $this->db->where('seller_id', $userID);
			// $this->db->where('seller_type', $account_type);
		   	$this->db->where('status', '0');
		   	$this->db->order_by('createdDate', 'DESC');
	   		$query = $this->db->get('tbl_products');
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		/*Save & Update*/
		public function save_msg_reply($data, $userID){
    		$createdDate=time();
    		$message=sqlSafe($data['message']);
    		$subject=sqlSafe($data['subject']);
    		$receiver=sqlSafe($data['receiver']);
    		$parent_msg=sqlSafe($data['parent_msg']);

            $set=$this->db->insert('tbl_msg', array(
	            'message' => $message,
	            'subject' => $subject,
	            'receiver' => $receiver,
	            'sender' => 'admin',
	            'parent_msg' => $parent_msg,
	            'status' => '0',
	            'createdBy' => $userID,
	            'createdDate' => $createdDate
	        ));

	        if($set){
	        	$msg_id="";
	        	$this->db->select('id');
		        $this->db->where('message', $message);
		        $this->db->where('receiver', $receiver);
		        $this->db->where('parent_msg', $parent_msg);
		        $this->db->where('createdBy', $userID);
		        $this->db->where('status', '0');
		        $this->db->order_by('createdDate', 'DESC');
		        $this->db->limit(1);
		        $query = $this->db->get('tbl_msg');

		        if($query->num_rows() > 0){
		            foreach($query->result() as $row){
		            	$msg_id=$row->id;
		            }
		        }
	        	$sent=$this->sendMsg($receiver, $msg_id, 'Reply: '.$subject, $message);
	        	if($sent){
	        		return 'Success';
	        	}else{
	        		return ErrorMsg("Failed to send message...");
	        	}
	        	
	        }else{
	        	return ErrorMsg($this->db->_error_message());
	        }
		}

		public function sendMsg($receiver, $msg_id, $subject, $msg){
			$res=0;
			$account_type="";
			if($receiver=='all customers'){
				$account_type="customer";
				$this->db->select('tbl_users.id, tbl_user_info.email');
		        $this->db->from('tbl_users');
		        $this->db->join('tbl_user_info', 'user_id=tbl_users.id');
		        $this->db->where('tbl_users.account_type', 'customer');
		        $this->db->where('tbl_users.status', '0');
		        $query = $this->db->get();
			}else if($receiver=='all'){
				$account_type="customer";
				$this->db->select('tbl_users.id, tbl_user_info.email');
		        $this->db->from('tbl_users');
		        $this->db->join('tbl_user_info', 'user_id=tbl_users.id');
		        $this->db->where('tbl_users.account_type', 'customer');
		        $this->db->where('tbl_users.status', '0');
		        $query = $this->db->get();

				$this->db->select('tbl_users.id, tbl_sellers.email');
		        $this->db->from('tbl_users');
		        $this->db->join('tbl_sellers', 'tbl_sellers.user_id=tbl_users.id');
		        $this->db->where('tbl_users.account_type!=', 'customer');
		        $this->db->where('tbl_users.account_type!=', 'admin');
		        $this->db->where('tbl_users.status!=1 AND tbl_users.status!=3');		        
		        $query1 = $this->db->get();
		        if($query1->num_rows() > 0){
		            foreach($query1->result() as $data){
		            	$user_id=$data->id;
		            	$email=$data->email;
		            	$set=$this->db->insert('tbl_msg_readers', array(
				            'msg_id' => $msg_id,
				            'user_id' => $user_id,
				            'receiver_email' => $email,
				            'account_type' => 'seller',
				            'is_read' => '1',
				            'status' => '0'
				        ));
				        if($set){
				        	$this->load->library('email');
							$this->email->from('info@getvalue.co', 'Get Value Inc - Updates');
							$this->email->to($email);
							$this->email->subject($subject);
							$this->email->message($msg);
							$this->email->send();
				        	$res++;
				        }
		            }
		        }
			}else if($receiver=='all sellers' || $receiver=='all affiliates' || $receiver=='all vendors' || $receiver=='all insiders' || $receiver=='all outsiders' || $receiver=='all contributors'){
				$this->db->select('tbl_users.id, tbl_sellers.email');
		        $this->db->from('tbl_users');
		        $this->db->join('tbl_sellers', 'tbl_sellers.user_id=tbl_users.id');
		        $this->db->where('tbl_users.account_type!=', 'customer');
		        $this->db->where('tbl_users.account_type!=', 'admin');
		        $this->db->where('tbl_users.status!=1 AND tbl_users.status!=3');
		        if($receiver=='all sellers'){

				}else if($receiver=='all affiliates'){
					$this->db->where('(is_insider="1" OR is_outsider="1" OR is_contributor="1")');
				}else if($receiver=='all vendors'){
					$this->db->where('is_vendor', '1');
				}else if($receiver=='all insiders'){
					$this->db->where('is_insider', '1');
				}else if($receiver=='all outsiders'){
					$this->db->where('is_outsider', '1');
				}else if($receiver=='all contributors'){
					$this->db->where('is_contributor', '1');
				}
		        
		        $query1 = $this->db->get();
		        if($query1->num_rows() > 0){
		            foreach($query1->result() as $data){
		            	$user_id=$data->id;
		            	$email=$data->email;
		            	$set=$this->db->insert('tbl_msg_readers', array(
				            'msg_id' => $msg_id,
				            'user_id' => $user_id,
				            'receiver_email' => $email,
				            'account_type' => 'seller',
				            'is_read' => '1',
				            'status' => '0'
				        ));
				        if($set){
				        	$this->load->library('email');
							$this->email->from('info@getvalue.co', 'Get Value Inc - Updates');
							$this->email->to($email);
							$this->email->subject($subject);
							$this->email->message($msg);
							$this->email->send();
				        	$res++;
				        }
		            }
		        }
			}else{
				/*$account_type="customer";
				$this->db->select('tbl_users.id, tbl_user_info.email');
		        $this->db->from('tbl_users');
		        $this->db->join('tbl_user_info', 'tbl_user_info.user_id=tbl_users.id');
		        $this->db->where('tbl_users.id', $receiver);
		        $this->db->where('tbl_users.account_type!=', 'admin');
		        $this->db->where('tbl_users.status', '0');		        
		        $query = $this->db->get();*/

				$this->db->select('tbl_users.id, tbl_sellers.email');
		        $this->db->from('tbl_users');
		        $this->db->join('tbl_sellers', 'tbl_sellers.user_id=tbl_users.id');
		        $this->db->where('tbl_users.id', $receiver);
		        $this->db->where('tbl_users.account_type!=', 'admin');
		        $this->db->where('tbl_users.status', '0');
		        
		        $query1 = $this->db->get();
		        if($query1->num_rows() > 0){
		            foreach($query1->result() as $data){
		            	$user_id=$data->id;
		            	$email=$data->email;
		            	$set=$this->db->insert('tbl_msg_readers', array(
				            'msg_id' => $msg_id,
				            'user_id' => $user_id,
				            'receiver_email' => $email,
				            'account_type' => 'seller',
				            'is_read' => '1',
				            'status' => '0'
				        ));
				        if($set){
				        	$this->load->library('email');
							$this->email->from('info@getvalue.co', 'Get Value Inc - Updates');
							$this->email->to($email);
							$this->email->subject($subject);
							$this->email->message($msg);
							$this->email->send();
				        	$res++;
				        }
		            }
		        }
			}

			if(isset($query) && $query->num_rows() > 0){
	            foreach($query->result() as $data){
	            	$user_id=$data->id;
	            	$email=$data->email;
	            	$set=$this->db->insert('tbl_msg_readers', array(
			            'msg_id' => $msg_id,
			            'user_id' => $user_id,
			            'receiver_email' => $email,
			            'account_type' => $account_type,
			            'is_read' => '1',
			            'status' => '0'
			        ));
			        if($set){
			        	$this->load->library('email');
						$this->email->from('info@getvalue.co', 'Get Value Inc - Updates');
						$this->email->to($email);
						$this->email->subject($subject);
						$this->email->message($msg);
						$this->email->send();
			        	$res++;
			        }
	            }
	        }

			if($res>0){
				return true;
			}else{
				return false;
			}
		}

    	public function save_new_message($data, $userID){
    		$createdDate=time();
    		$subject=sqlSafe($data['subject']);
    		$message=sqlSafe($data['message']);
    		$receiver=sqlSafe($data['receiver']);

            $set=$this->db->insert('tbl_msg', array(
	            'subject' => $subject,
	            'message' => $message,
	            'receiver' => $receiver,
	            'status' => '0',
	            'createdBy' => $userID,
	            'createdDate' => $createdDate
	        ));

	        if($set){
	        	$msg_id="";
	        	$this->db->select('id');
		        $this->db->where('subject', $subject);
		        $this->db->where('message', $message);
		        $this->db->where('receiver', $receiver);
		        $this->db->where('createdBy', $userID);
		        $this->db->where('status', '0');
		        $this->db->order_by('createdDate', 'DESC');
		        $this->db->limit(1);
		        $query = $this->db->get('tbl_msg');
		        if($query->num_rows() > 0){
		            foreach($query->row_array() as $row){
		            	$msg_id=$row;
		            }
		        }

	        	$sent=$this->sendMsg($receiver, $msg_id, $subject, $message);
	        	if($sent){
	        		return 'Success';
	        	}else{
	        		return ErrorMsg("Failed to send message...");
	        	}
	        	
	        }else{
	        	return ErrorMsg($this->db->_error_message());
	        }
		}

		/*Action & Deletes*/

		public function delete_msg($msgID, $userID){
			$this->db->set('status', '1');
	   		$this->db->where('id', $msgID);
	     	$this->db->update('tbl_msg');

			$this->db->set('status', '1');
	   		$this->db->where('msg_id', $msgID);
	     	$this->db->update('tbl_msg_readers');
		   	return 'Success';
		}

		public function optimize_images(){
			$updt=0; $no_image=array();
			ini_set('memory_limit', '-1');
			$this->load->model('Upload_model');
			$counter=0;
			$thumb_path='media/products/thumb/';
			
			$this->db->select('image');
	        $this->db->where('status', '0');
	        // $this->db->limit(15);
	        $query = $this->db->get('tbl_products');
	        if($query->num_rows() > 0){
	            foreach($query->result_array() as $row){
	            	$image=$row['image'];
	            	if(!file_exists($thumb_path.$image)){
	            		array_push($no_image, $image);
		            }
	            }
	        }
	        my_line:
	        for ($i=0; $i < sizeof($no_image); $i++) { 
	        	$img=$no_image[$i];
	        	if(!file_exists($thumb_path.$img)){
		        	$chk=$this->optimize($img);
	            	if($chk==1){
	            		// die("....");
	            		$counter++;
	            		$updt++;
		            	// echo '<img src="'.base_url('media/products/thumb/'.$img).'" style="width: 50px;">';
		            	echo $counter.' Image: '.$img;
		            	echo '<br>';
		            }
	            }
	        }
	        $hk=0;
	        for ($i=0; $i < sizeof($no_image); $i++) { 
	        	$img=$no_image[$i];
	        	if(!file_exists($thumb_path.$img)){
		        	// goto my_line;
		        	/*$hk++;
		        	echo $hk;
		        	echo 'Not Available<br>';*/
	            }
	        }
	        /*if($updt<5){
	        	goto my_line;
	        }*/
		}

		function optimize($image){
			$original_path='media/products/';
			$thumb_path='media/products/thumb/';		
        	$check=$this->resizeImage($original_path, $thumb_path, $image, 350, 402);
			return $check;
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
	      	}else{
		      	$this->image_lib->clear();
		      	return 1;
	      	}
	   	}

		public function generate_seller_profile(){
			/*$this->db->select('profile_url1, id');
	   		$query = $this->db->get('tbl_sellers');
		   	if($query->num_rows() > 0){
		   		foreach ($query->result() as $data) {
		   			$seller=$data->id;
		   			$profile_url=$data->profile_url1;
		   			$new_profile_url=generateLink("tbl_sellers", "profile_url", "", "");
		   			$this->db->set('profile_url', $new_profile_url);
				   	$this->db->where('profile_url', '');
				   	$this->db->where('id', $seller);
			   		$this->db->update('tbl_sellers');
		   			echo $profile_url.' - '.$new_profile_url.' <br>';
		   		}
		   	}*/

			/*$this->db->select('source_url, id');
		   	$this->db->where('source_url!=', '');
	   		$query = $this->db->get('tbl_sellers');
		   	if($query->num_rows() > 0){
		   		foreach ($query->result() as $data) {
		   			$seller=$data->id;
		   			$source_url=$data->source_url;
		   			$pro=explode("=", $source_url);
		   			$pro_url=$pro[1];

		   			$this->db->select('profile_url');
				   	$this->db->where('profile_url1', $pro_url);
			   		$sql = $this->db->get('tbl_sellers');
			   		if($sql->num_rows() > 0){
				   		foreach ($sql->result() as $data1) {
				   			$profile_url=$data1->profile_url;

				   			$this->db->set('source_url', '?vref='.$profile_url);
						   	$this->db->where('source_url!=', '');
						   	$this->db->where('id', $seller);
					   		$this->db->update('tbl_sellers');
				   		}
				   	}
		   			echo $source_url.' - '.$profile_url.' <br>';
		   		}
		   	}*/

			/*$this->db->select('referral_url, id');
		   	$this->db->where('referral_url!=', '');
	   		$query = $this->db->get('tbl_affiliate_urls');
		   	if($query->num_rows() > 0){
		   		foreach ($query->result() as $data) {
		   			$seller=$data->id;
		   			$referral_url=$data->referral_url;
		   			$pro=explode("=", $referral_url);
		   			$pro_url=$pro[1];

		   			$this->db->select('profile_url');
				   	$this->db->where('profile_url1', $pro_url);
			   		$sql = $this->db->get('tbl_sellers');
			   		if($sql->num_rows() > 0){
				   		foreach ($sql->result() as $data1) {
				   			$profile_url=$data1->profile_url;

				   			$this->db->set('referral_url', $pro[0].'='.$profile_url);
						   	$this->db->where('referral_url!=', '');
						   	$this->db->where('id', $seller);
					   		$this->db->update('tbl_affiliate_urls');
				   		}
				   	}
		   			echo $referral_url.' - '.$profile_url.' <br>';
		   		}
		   	}*/
		}

	}
?>