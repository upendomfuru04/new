<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Seller_model extends CI_Model{

	    public function __construct(){
	        parent::__construct();
	        $this->load->database();
        	$this->load->model('Upload_model');
    	}

    	function getTotalProducts($userID){
	        $res=0;
	        $this->db->select('id');
	        $this->db->from('tbl_products');
	        $this->db->where('seller_id', $userID);
	        $this->db->where('status', '0');
	        $query = $this->db->get();
	        $res=$query->num_rows();
	        return $res;
	    }

	    function getTotalReferrals($userID){
	        $this->db->select('id');
	        $this->db->from('tbl_affiliate_urls');
	        $this->db->where('seller_id', $userID);
	        $this->db->where('status', '0');
	        $query = $this->db->get();
	        $res=$query->num_rows();
	        return $res;
	    }

	    function getPendingPost($userID){
	        $this->db->select('id');
	        $this->db->from('tbl_blog_post');
	        $this->db->where('postedBy', $userID);
	        $this->db->where('is_approved!=', '0');
	        $this->db->where('status', '0');
	        $query = $this->db->get();
	        $res=$query->num_rows();
	        return $res;
	    }

	    function getPendingCommissions($userID){
	        $res=0;
	        $this->db->select('amount');
	        $this->db->from('tbl_commissions');
	        $this->db->where('seller_id', $userID);
	        $this->db->where('commissionStatus', '2');
	        $this->db->where('status', '0');
	        $query = $this->db->get();

	        if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	                $res=$res+$data['amount'];
	            }
	        }
	        if(is_numeric($res))
	            $res=number_format($res);
	        return $res;
	    }

	    function getApprovedPost($userID){
	        $this->db->select('id');
	        $this->db->from('tbl_blog_post');
	        $this->db->where('postedBy', $userID);
	        $this->db->where('is_approved', '0');
	        $this->db->where('status', '0');
	        $query = $this->db->get();
	        $res=$query->num_rows();
	        return $res;
	    }

	    function getPaidCommissions($userID){
	        $res=0;
	        $this->db->select('amount');
	        $this->db->from('tbl_commissions');
	        $this->db->where('seller_id', $userID);
	        $this->db->where('commissionStatus', '0');
	        $this->db->where('status', '0');
	        $query = $this->db->get();
	        if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	                $res=$res+(int)$data['amount'];
	            }
	        }
	        if(is_numeric($res))
	            $res=number_format($res);
	        return $res;
	    }

	    function getPendingOrders($userID){
	        $this->db->select('tbl_orders.id');
	        $this->db->from('tbl_orders');
	        $this->db->join('tbl_cart', 'tbl_cart.orderID=tbl_orders.orderID');
	        $this->db->join('tbl_products', 'tbl_products.id=tbl_cart.item_id');
	        $this->db->where('tbl_products.seller_id', $userID);
	        $this->db->where('tbl_cart.status', '0');
	        $this->db->where('tbl_orders.status', '0');
	        $this->db->where('tbl_orders.is_complete', '1');
	        $query = $this->db->get();
			$res=$query->num_rows();
	        return $res;
	    }

	    function getPendingAffiliateOrders($userID){
	        $this->db->select('tbl_orders.id');
	        $this->db->from('tbl_orders');
	        $this->db->join('tbl_cart', 'tbl_cart.orderID=tbl_orders.orderID');
	        $this->db->join('tbl_affiliate_urls', 'tbl_affiliate_urls.referral_url=tbl_cart.referral_url');
	        $this->db->join('tbl_products', 'tbl_products.id=tbl_affiliate_urls.product');
	        $this->db->where('tbl_affiliate_urls.seller_id', $userID);
	        $this->db->where('tbl_affiliate_urls.status', '0');
	        $this->db->where('tbl_cart.status', '0');
	        $this->db->where('tbl_orders.status', '0');
	        $this->db->where('tbl_orders.is_complete', '1');
	        $query = $this->db->get();
	        $res=$query->num_rows();
	        return $res;
	    }

	    function getTotalOrders($userID){
	        $this->db->select('tbl_orders.id');
	        $this->db->from('tbl_orders');
	        $this->db->join('tbl_cart', 'tbl_cart.orderID=tbl_orders.orderID');
	        $this->db->join('tbl_products', 'tbl_products.id=tbl_cart.item_id');
	        $this->db->where('tbl_products.seller_id', $userID);
	        $this->db->where('tbl_cart.status', '0');
	        $this->db->where('tbl_orders.status', '0');
	        $this->db->where('tbl_orders.is_complete', '0');
	        $query = $this->db->get();
	        $res=$query->num_rows();
	        return $res;
	    }

	    function getTotalAffiliateOrders($userID){
	        $this->db->select('tbl_orders.id');
	        $this->db->from('tbl_orders');
	        $this->db->join('tbl_cart', 'tbl_cart.orderID=tbl_orders.orderID');
	        $this->db->join('tbl_affiliate_urls', 'tbl_affiliate_urls.referral_url=tbl_cart.referral_url');
	        $this->db->join('tbl_products', 'tbl_products.id=tbl_affiliate_urls.product');
	        $this->db->where('tbl_affiliate_urls.seller_id', $userID);
	        $this->db->where('tbl_affiliate_urls.status', '0');
	        $this->db->where('tbl_cart.status', '0');
	        $this->db->where('tbl_orders.status', '0');
	        $this->db->where('tbl_orders.is_complete', '0');
	        $query = $this->db->get();
	        return $query->num_rows();
	    }

    	public function save_shop_info($data, $account_type, $userID){
    		$createdDate=time();
			$seller_type=sqlSafe($data['seller_type']);
			$brand=sqlSafe($data['brand']);

    		$this->db->select('id');
	        $this->db->where('seller_type', $seller_type);
	        $this->db->where('seller_id', $userID);
	        $this->db->where('status != ', '1');
	        $query = $this->db->get('tbl_seller_info');

	        if($query->num_rows() > 0){
	            die(ErrorMsg("Shop info already exist"));
	        }

    		$logoImg=""; $bannerImg="";
    		if(isset($_FILES['logo']) && $_FILES['logo']['name']!=""){
    			$logo=do_upload('media/shop/logo', 'logo', $seller_type.'_'.$userID, '', '', '', 'image');
    			if($logo!='ok'){ 
    				die(ErrorMsg($logo));
    			}
    			$logoImg=$this->session->userdata('uploaded_file');
    		}
    		
    		if(isset($_FILES['banner']) && $_FILES['banner']['name']!=""){
    			$banner=do_upload('media/shop/banner', 'banner', $seller_type.'_'.$userID, '', '', '', 'image');
    			if($banner!='ok'){ 
    				die(ErrorMsg($banner));
    			}else{
    				$bannerImg=$this->session->userdata('uploaded_file');
    			}
    		}

    		if($logoImg=="" && $bannerImg=="" && $data['brand']==""){ die(ErrorMsg("No data to save"));}

            $set=$this->db->insert('tbl_seller_info', array(
	            'seller_type' => $seller_type,
	            'brand' => $brand,
	            'logo' => $logoImg,
	            'banner' => $bannerImg,
	            'seller_id' => $userID,
	            'status' => '0',
	            'createdBy' => $userID,
	            'createdDate' => $createdDate
	        ));

	        if($set){
	        	return 'Success';
	        }else{
	        	return ErrorMsg($this->db->_error_message());
	        }
		}

    	public function update_shop_info($infoID, $data, $account_type, $userID){
    		$createdDate=time();
    		$logoImg=""; $bannerImg="";
    		if(isset($_FILES['logo']) && $_FILES['logo']['name']!=""){
    			$logo=do_upload('media/shop/logo', 'logo', dataSafe($data['seller_type']).'_'.$userID, '', '', '', 'image');
    			if($logo!='ok'){ 
    				die(ErrorMsg($logo));
    			}
    			$logoImg=$this->session->userdata('uploaded_file');
    		}
    		
    		if(isset($_FILES['banner']) && $_FILES['banner']['name']!=""){
    			$banner=do_upload('media/shop/banner', 'banner', dataSafe($data['seller_type']).'_'.$userID, '', '', '', 'image');
    			if($banner!='ok'){ 
    				die(ErrorMsg($banner));
    			}else{
    				$bannerImg=$this->session->userdata('uploaded_file');
    			}
    		}

    		if($logoImg=="" && $bannerImg=="" && $data['brand']==""){ die(ErrorMsg("No data to save"));}

            $set=array(
	            'brand' => sqlSafe($data['brand'])
	        );
	     	
    		if($logoImg!=""){ $set['logo']=$logoImg;}
    		if($bannerImg!=""){ $set['banner']=$bannerImg;}

	        $this->db->where('seller_type',$account_type);
	        $this->db->where('seller_id',$userID);
	        $this->db->where('id',$infoID);
	     	$this->db->update('tbl_seller_info', $set);
	        return 'Success';
		}

		public function media_center($userID, $account_type){
			$result=array();
			$this->db->select('id, image, name, preview, product_url, preview_type');
			$this->db->where('seller_id', $userID);
			$this->db->where('seller_type', $account_type);
		   	$this->db->where('status', '0');
		   	$this->db->order_by('createdDate', 'DESC');
	   		$query = $this->db->get('tbl_products');
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function load_social_media_details($social, $userID, $account_type){
			$this->db->select('id, name, link');
			$this->db->where('id', $social);
			$this->db->where('seller_id', $userID);
			$this->db->where('seller_type', $account_type);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_seller_social');
		   	if($query->num_rows() > 0)
		   	return $query->row_array();
		}

		public function shop_info($userID, $account_type){
			$result=array();
			$this->db->select('id, seller_type, logo, banner, brand');
			$this->db->where('seller_id', $userID);
			$this->db->where('seller_type', $account_type);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_seller_info');
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function seller_info($seller){
			$result=array();
			$this->db->select('id, full_name, phone, email, address, gender');
			$this->db->where('user_id', $seller);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_sellers');
		   	if($query->num_rows() > 0)
		   	$result = $query->row_array();
		   	return $result;
		}

		public function check_new_message($userID){
			$res=0;
			$this->db->select('tbl_msg_readers.id');
			$this->db->from('tbl_msg_readers');
		   	$this->db->where('user_id', $userID);
		   	$this->db->where('is_read', '1');
		   	$this->db->where('status', '0');
		   	
	   		$sql = $this->db->get();		   	
		   	$res=$sql->num_rows();
		   	return $res;
		}

		public function check_new_affiliate($userID){
			$res=0;
			$this->db->select('id');
			$this->db->from('tbl_affiliate_notification');
		   	$this->db->where('user_id', $userID);
		   	$this->db->where('is_read', '1');
		   	$this->db->where('status', '0');		   	
	   		$sql = $this->db->get();
		   	$res=$sql->num_rows();
		   	return $res;
		}

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
	            'sender' => 'seller',
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
	        		return ErrorMsg("Failed to send message... Error ".$sent);
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
		        $this->db->where('tbl_users.status', '0');
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

				$this->db->select('tbl_users.id, tbl_admin.email');
		        $this->db->from('tbl_users');
		        $this->db->join('tbl_admin', 'tbl_admin.user_id=tbl_users.id');
		        $this->db->where('tbl_users.id', $receiver);
		        $this->db->where('tbl_users.account_type=', 'admin');
		        $this->db->where('tbl_users.status', '0');		        
		        $query2 = $this->db->get();
		        if($query2->num_rows() > 0){
		            foreach($query2->result() as $data2){
		            	$user_id=$data2->id;
		            	$email=$data2->email;
		            	$set2=$this->db->insert('tbl_msg_readers', array(
				            'msg_id' => $msg_id,
				            'user_id' => $user_id,
				            'receiver_email' => $email,
				            'account_type' => 'admin',
				            'is_read' => '1',
				            'status' => '0'
				        ));
				        if($set2){
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

		public function load_messages($userID){
			$result=array();
			$this->db->select('tbl_msg.id, subject, createdDate, is_read');
			$this->db->from('tbl_msg_readers');
			$this->db->join('tbl_msg', 'tbl_msg_readers.msg_id=tbl_msg.id', 'LEFT');
		   	$this->db->where('(user_id="'.$userID.'" OR createdBy="'.$userID.'")');
		   	$this->db->where('tbl_msg.parent_msg', '');
		   	$this->db->where('tbl_msg.status', '0');
		   	$this->db->order_by('is_read', 'DESC');
		   	$this->db->order_by('createdDate', 'DESC');
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function load_message_detail($msg, $userID){
			$this->db->set('is_read', '0');
	   		$this->db->where('msg_id', $msg);
	   		$this->db->where('user_id', $userID);
	     	$this->db->update('tbl_msg_readers');

			$this->db->select('id, subject, message, createdBy, createdDate, receiver');
			$this->db->from('tbl_msg');
		   	$this->db->where('id', $msg);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0)
		   	return $query->row_array();
		}

		public function load_shop_info_details($info, $userID, $account_type){
			$this->db->select('id, seller_type, logo, banner, brand');
			$this->db->where('id', $info);
			$this->db->where('seller_id', $userID);
			$this->db->where('seller_type', $account_type);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_seller_info');
		   	if($query->num_rows() > 0)
		   	return $query->row_array();
		}

		public function load_payment_info_details($info, $userID, $account_type){
			$this->db->select('id, method, payment_type, account_name, account_number, provider_name, email_address');
			$this->db->where('id', $info);
			$this->db->where('seller_id', $userID);
			$this->db->where('seller_type', $account_type);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_seller_payment_info');
		   	if($query->num_rows() > 0)
		   	return $query->row_array();
		}

		public function delete_shop_info($infoID, $userID, $account_type){
			$this->db->set('status', '1');
			$this->db->where('seller_id', $userID);
			$this->db->where('seller_type', $account_type);
	   		$this->db->where('id', $infoID);
	     	$this->db->update('tbl_seller_info');
		   	return 'Success';
		}

		public function delete_social_account($social, $userID, $account_type){
			$this->db->set('status', '1');
			$this->db->where('seller_id', $userID);
			$this->db->where('seller_type', $account_type);
	   		$this->db->where('id', $social);
	     	$this->db->update('tbl_seller_social');
		   	return 'Success';
		}

		public function delete_payment_info($info, $userID, $account_type){
			$this->db->set('status', '1');
			$this->db->where('seller_id', $userID);
			$this->db->where('seller_type', $account_type);
	   		$this->db->where('id', $info);
	     	$this->db->update('tbl_seller_payment_info');
		   	return 'Success';
		}

    	public function save_social_accounts($data, $account_type, $userID){
    		$createdDate=time();
    		$name=sqlSafe($data['name']);
    		$link=sqlSafe($data['link']);

    		$this->db->select('id');
	        $this->db->where('name', $name);
	        $this->db->where('link', $link);
	        $this->db->where('seller_id',$userID);
	        $this->db->where('seller_type',$account_type);
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_seller_social');

	        if($query->num_rows() > 0){
	            die(ErrorMsg("This account already exist"));
	        }

            $set=$this->db->insert('tbl_seller_social', array(
	            'name' => $name,
	            'link' => $link,
	            'seller_id' => $userID,
	            'seller_type' => $account_type,
	            'status' => '0',
	            'createdBy' => $userID,
	            'createdDate' => $createdDate
	        ));

	        if($set){
	        	return 'Success';
	        }else{
	        	return ErrorMsg($this->db->_error_message());
	        }
		}

    	public function update_social_accounts($social, $data, $account_type, $userID){
    		$createdDate=time();
    		$name=sqlSafe($data['name']);
    		$link=sqlSafe($data['link']);

    		$set=array(
	            'name' => sqlSafe($name),
	            'link' => sqlSafe($link)
	        );
	        $this->db->where('seller_type', $account_type);
	        $this->db->where('seller_id', $userID);
	        $this->db->where('id', $social);
	     	$this->db->update('tbl_seller_social', $set);
			return 'Success';
		}

    	public function save_payment_info($data, $account_type, $userID){
    		$createdDate=time();
    		$method=sqlSafe($data['method']);
    		$payment_type=sqlSafe($data['payment_type']);
    		$provider_name=sqlSafe($data['provider_name']);
    		$account_name=sqlSafe($data['account_name']);
    		$account_number=sqlSafe($data['account_number']);
    		$email_address=sqlSafe($data['email_address']);

    		$this->db->select('id');
	        $this->db->where('method', $method);
	        $this->db->where('payment_type', $payment_type);
	        $this->db->where('provider_name', $provider_name);
	        $this->db->where('account_name', $account_name);
	        $this->db->where('account_number', $account_number);
	        $this->db->where('email_address', $email_address);
	        $this->db->where('seller_id', $userID);
	        $this->db->where('seller_type', $account_type);
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_seller_payment_info');

	        if($query->num_rows() > 0){
	            die(ErrorMsg("This payment info already exist"));
	        }

            $set=$this->db->insert('tbl_seller_payment_info', array(
	            'method' => $method,
	            'payment_type' => $payment_type,
	            'provider_name' => $provider_name,
	            'account_name' => $account_name,
	            'account_number' => $account_number,
	            'email_address' => $email_address,
	            'seller_id' => $userID,
	            'seller_type' => $account_type,
	            'status' => '0',
	            'createdBy' => $userID,
	            'createdDate' => $createdDate
	        ));

	        if($set){
	        	return 'Success';
	        }else{
	        	return ErrorMsg($this->db->_error_message());
	        }
		}

    	public function update_payment_info($info, $data, $account_type, $userID){
    		$method=sqlSafe($data['method']);
    		$payment_type=sqlSafe($data['payment_type']);
    		$provider_name=sqlSafe($data['provider_name']);
    		$account_name=sqlSafe($data['account_name']);
    		$account_number=sqlSafe($data['account_number']);
    		$email_address=sqlSafe($data['email_address']);

    		$set=array(
	            'method' => $method,
	            'payment_type' => $payment_type,
	            'provider_name' => $provider_name,
	            'account_name' => $account_name,
	            'account_number' => $account_number,
	            'email_address' => $email_address
	        );
	        $this->db->where('seller_type', $account_type);
	        $this->db->where('seller_id', $userID);
	        $this->db->where('id', $info);
	     	$this->db->update('tbl_seller_payment_info', $set);
	        return 'Success';
		}

		public function social_accounts($userID, $account_type){
			$result=array();
			$this->db->select('id, name, link');
			$this->db->where('seller_id', $userID);
			$this->db->where('seller_type', $account_type);
		   	$this->db->where('status', '0');
		   	$this->db->order_by('createdDate', 'DESC');
	   		$query = $this->db->get('tbl_seller_social');
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function payment_info($userID, $account_type){
			$result=array();
			$this->db->select('id, method, payment_type, account_name, account_number, provider_name, email_address');
			$this->db->where('seller_id', $userID);
			$this->db->where('seller_type', $account_type);
		   	$this->db->where('status', '0');
		   	$this->db->order_by('createdDate', 'DESC');
	   		$query = $this->db->get('tbl_seller_payment_info');
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}
    }
?>