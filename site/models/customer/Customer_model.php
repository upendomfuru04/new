<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Customer_model extends CI_Model{

	    public function __construct(){
	        parent::__construct();
	        $this->load->database();
        	$this->userToken=$this->session->userdata('getvalue_user_idetification');
    	}

		public function load_refunds($customer){
			$result=array();
			$this->db->select('id, orderID, amount, is_processed, status, customer_note, createdDate');
		   	$this->db->where('customer', $customer);
		   	$this->db->where('status', '0');
		   	$this->db->order_by('createdDate', 'DESC');
		   	
	   		$query = $this->db->get('tbl_refunds');		   	
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function load_messages($userID){
			$result=array();
			$this->db->select('tbl_msg.id, subject, createdDate, is_read');
			$this->db->from('tbl_msg_readers');
			$this->db->join('tbl_msg', 'tbl_msg_readers.msg_id=tbl_msg.id');
		   	$this->db->where('user_id', $userID);
		   	$this->db->where('tbl_msg.status', '0');
		   	$this->db->where('tbl_msg.parent_msg', '');
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


    	public function save_new_message($data, $userID){
    		$createdDate=time();
    		$subject=sqlSafe($data['subject']);
    		$message=sqlSafe($data['message']);
    		$receiver=sqlSafe($data['receiver']);
    		$parent_msg=sqlSafe($data['parent_msg']);

            $set=$this->db->insert('tbl_msg', array(
	            'subject' => $subject,
	            'message' => $message,
	            'receiver' => $receiver,
	            'sender' => 'customer',
	            'parent_msg' => $parent_msg,
	            'status' => '0',
	            'createdBy' => $userID,
	            'createdDate' => $createdDate
	        ));

	        if($set){
	        	$msg_id="";
	        	$this->db->select('id');
		        $this->db->where('message', $message);
		        $this->db->where('subject', $subject);
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
	        	$sent=$this->sendMsg($receiver, $msg_id, 'Customer Reply', $message);
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
			$this->db->select('email');
	        $this->db->from('tbl_admin');
	        $this->db->where('user_id', $receiver);
	        $this->db->where('status', '0');
	        $query = $this->db->get();
	        if($query->num_rows() > 0){
	            foreach($query->result() as $data){
	            	$user_id=$receiver;
	            	$email=$data->email;
	            	$set=$this->db->insert('tbl_msg_readers', array(
			            'msg_id' => $msg_id,
			            'user_id' => $receiver,
			            'receiver_email' => $email,
			            'account_type' => 'admin',
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

		public function load_my_items($userID){
			$result=array();
			$this->db->select('tbl_products.id, tbl_cart.id as cartID, product_url, item_id, tbl_products.name, preview, media, image, product_status, tbl_cart.orderID, tbl_cart.user_id, tbl_shop_category.name as category, tbl_products.quantity as avQtns, tbl_cart.quantity, tbl_cart.is_complete, tbl_cart.createdDate');
			$this->db->from('tbl_cart');
			$this->db->join('tbl_orders', 'tbl_orders.orderID = tbl_cart.orderID');
			$this->db->join('tbl_products', 'tbl_products.id = item_id');
			$this->db->join('tbl_shop_category', 'tbl_products.category = tbl_shop_category.id');
			$this->db->where('tbl_orders.user_id', $userID);
			$this->db->where('tbl_cart.user_id', $userID);
			$this->db->where('tbl_orders.is_complete', '0');
		   	$this->db->where('tbl_products.status', '0');
		   	$this->db->where('tbl_cart.status', '0');
		   	$this->db->order_by('createdDate', 'DESC');
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function download_product($userID, $product){
			$result=array();
			$this->db->select('tbl_products.id, media, tbl_products.name');
			$this->db->from('tbl_cart');
			$this->db->join('tbl_orders', 'tbl_orders.orderID = tbl_cart.orderID');
			$this->db->join('tbl_products', 'tbl_products.id = item_id');
			$this->db->join('tbl_shop_category', 'tbl_products.category = tbl_shop_category.id');
			$this->db->where('tbl_orders.user_id', $userID);
			$this->db->where('tbl_cart.user_id', $userID);
			$this->db->where('tbl_products.id', $product);
			$this->db->where('tbl_orders.is_complete', '0');
		   	$this->db->where('tbl_products.status', '0');
		   	$this->db->where('tbl_cart.status', '0');
		   	$this->db->where('tbl_orders.status', '0');
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0)
		   	$result = $query->row_array();
		   	return $result;
		}

		public function save_request_refund($data, $userID){
    		$createdDate=time();
    		$product=sqlSafe($data['product']);
    		$orderID=sqlSafe($data['orderID']);
    		$amount_paid=sqlSafe($data['amount_paid']);
    		$description=sqlSafe($data['description']);
	        $this->db->select('id');
	        $this->db->where('customer',$userID);
	        $this->db->where('orderID',$orderID);
	        // $this->db->where('is_processed', '1');
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_refunds');

	        if($query->num_rows() > 0){
	            die(ErrorMsg("This request already set"));
	        }

	        $restrict=FALSE;
	        $currentTime=time();
	        $this->db->select('pay_date');
	        $this->db->where('orderID', $orderID);
	        $this->db->where('is_complete', '1');
	        $this->db->where('status','0');
	        $sql = $this->db->get('tbl_orders');

	        if($sql->num_rows() > 0){
	            foreach ($sql->result() as $rows) {
	            	if(timeDiffInDays($rows->pay_date, $currentTime) > getRefundPeriod()){
	            		$restrict=true;
	            	}
	            }
	        }

	        if($restrict==TRUE){
	        	die(ErrorMsg("You exceeded the refund deadline..."));
	        }else{
	        	$set=$this->db->insert('tbl_refunds', array(
		            'customer' => $userID,
		            'product' => $product,
		            'orderID' => $orderID,
		            'amount' => $amount_paid,
		            'customer_note' => $description,
		            'is_processed' => '1',
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
		}

		public function verify_coupon($coupon){
			$coupon_value="Invalid";
			$orderID=""; $totalAmount=0;
			$this->db->select('coupon_value');
			$this->db->from('tbl_seller_coupons');
			$this->db->where('coupon_code', $coupon);
		   	$this->db->where('status', '0');
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0){
		   		foreach ($query->result_array() as $data){
	                $coupon_value=$data['coupon_value'];
	            }
		   	}
		   	return $coupon_value;
		}

		public function remove_refund_request($userID, $request){
			$this->db->set('status', '1');
			$this->db->where('customer', $userID);
			$this->db->where('id', $request);
	   		$this->db->where('is_processed', '1');
	     	$this->db->update('tbl_refunds');
		   	return 'Success';
		}

		public function password_set($userID){
			$set=array(); $username=""; $password_set='0';
			$this->db->select('username, password_set');
			$this->db->from('tbl_users');
			$this->db->where('id', $userID);
		   	$this->db->where('status', '0');
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0){
		   		foreach ($query->result_array() as $data){
	                $username=$data['username'];
	                $password_set=$data['password_set'];
	            }
		   	}
		   	$set['username']=$username;
		   	$set['password_set']=$password_set;
		   	return $set;
		}
    }
?>