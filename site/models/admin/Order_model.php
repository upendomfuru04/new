<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Order_model extends CI_Model{

	    public function __construct(){
	        parent::__construct();
	        $this->load->database();
    	}

		public function orders(){
			$result=array();
			$this->db->select('tbl_orders.id, tbl_orders.orderID, tbl_orders.user_id, first_name, surname, amount_paid, tbl_orders.is_complete, tbl_orders.createdDate');
			$this->db->distinct();
			$this->db->from('tbl_orders');
			$this->db->join('tbl_cart', 'tbl_cart.orderID=tbl_orders.orderID');
			$this->db->join('tbl_products', 'tbl_products.id=tbl_cart.item_id');
		   	$this->db->where('tbl_cart.status', '0');
		   	$this->db->where('tbl_orders.status', '0');
		   	$this->db->order_by('tbl_orders.createdDate', 'DESC');
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function order_items($orderID, $userID){
			$result=array();
			$this->db->select('tbl_products.id, tbl_cart.id as cartID, product_url, item_id, name, image, 
				orderID, user_id, tbl_cart.price, tbl_products.quantity as avQtns, tbl_cart.quantity, is_complete, tbl_cart.createdDate, coupon_value, coupon');
			$this->db->from('tbl_cart');
			$this->db->join('tbl_products', 'tbl_products.id = item_id');
			$this->db->where('orderID', $orderID);
			// $this->db->where('seller_id', $userID);
		   	$this->db->where('tbl_products.status', '0');
		   	$this->db->where('tbl_cart.status', '0');
		   	$this->db->order_by('createdDate', 'DESC');
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function load_order_details($orderID, $userID){
			$this->db->select('id, first_name, surname, phone, email, address, country, region, postCode, notes, payment_type, account_name, account_number, coupon, coupon_value, total_amount, amount_paid, is_complete, pay_date, createdDate');
			$this->db->where('orderID', $orderID);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_orders');
		   	if($query->num_rows() > 0)
		   	return $query->row_array();
		}

		public function check_new_order(){
			$res=0;
			$this->db->select('tbl_orders.id');
			$this->db->distinct();
			$this->db->from('tbl_orders');
			$this->db->join('tbl_cart', 'tbl_cart.orderID=tbl_orders.orderID');
			$this->db->join('tbl_products', 'tbl_products.id=tbl_cart.item_id');
		   	$this->db->where('tbl_cart.status', '0');
		   	$this->db->where('tbl_orders.status', '0');
		   	$this->db->where('tbl_orders.is_complete', '2');
		   	$this->db->order_by('tbl_orders.id', 'DESC');
		   	
	   		$sql = $this->db->get();		   	
		   	$res=$sql->num_rows();
		   	return $res;
		}
		
		public function approve_customer_order($orderID, $userID){
			$this->db->set('is_complete', '0');
	   		$this->db->where('orderID', $orderID);
	   		$this->db->where('status', '0');
	     	$this->db->update('tbl_orders');

	     	$set2=array(
	            'is_complete' => '0'
	        );
	        $this->db->where('status', '0');
	        $this->db->where('orderID', $orderID);
	     	$this->db->update('tbl_cart', $set2);

	        $this->db->set('commissionStatus', '0');
	     	$this->db->where('status', '0');
	        $this->db->where('orderID', $orderID);
	     	$this->db->update('tbl_commissions');

	     	$this->db->select('id');
			$this->db->from('tbl_commissions');
			$this->db->where('orderID', $orderID);
		   	$this->db->where('status', '0');		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0){
		   		foreach ($query->result_array() as $data){
	                $commission_id=$data['id'];
	                $this->db->set('is_complete', '0');
			     	$this->db->where('status', '0');
			        $this->db->where('commission_id', $commission_id);
			        $this->db->where('transaction_type', 'commission');
			     	$this->db->update('tbl_transaction_records');
	            }
		   	}
		   	return 'Success';
		}

		public function delete_order($order, $userID){
			$this->db->set('status', '1');
	   		$this->db->where('id', $order);
	     	$this->db->update('tbl_orders');
		   	return 'Success';
		}

    }
