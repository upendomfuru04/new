<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Sale_model extends CI_Model{

	    public function __construct(){
	        parent::__construct();
	        $this->load->database();
    	}

		public function sales(){
			$result=array();
			$this->db->select('tbl_products.id, tbl_cart.id as cartID, product_url, item_id, name, image, tbl_cart.orderID, tbl_cart.price, tbl_products.quantity as avQtns, tbl_cart.quantity, tbl_cart.createdDate, first_name, surname, category, tbl_cart.coupon, tbl_cart.coupon_value');
			$this->db->from('tbl_cart');
			$this->db->join('tbl_products', 'tbl_products.id = item_id');
			$this->db->join('tbl_orders', 'tbl_orders.orderID = tbl_cart.orderID');
		   	$this->db->where('tbl_products.status', '0');
		   	$this->db->where('tbl_cart.status', '0');
		   	$this->db->where('tbl_orders.status', '0');
		   	$this->db->where('tbl_orders.is_complete', '0');
		   	$this->db->order_by('createdDate', 'DESC');
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function coupons($userID){
			$result=array();
			$this->db->select('tbl_seller_coupons.id, name, coupon_code, coupon_value, expire_date');
			$this->db->from('tbl_seller_coupons');
			$this->db->join('tbl_products', 'tbl_products.id = product');
			// $this->db->where('tbl_products.seller_id', $userID);
			// $this->db->where('tbl_seller_coupons.seller_id', $userID);
		   	$this->db->where('tbl_seller_coupons.status', '0');
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function customer_refunds(){
			$result=array();
			$this->db->select('tbl_refunds.id, tbl_refunds.orderID, amount, tbl_refunds.createdDate, first_name, surname, customer_note, is_processed');
			$this->db->distinct();
			$this->db->from('tbl_refunds');
			$this->db->join('tbl_orders', 'tbl_orders.orderID = tbl_refunds.orderID');
			$this->db->join('tbl_cart', 'tbl_cart.orderID=tbl_refunds.orderID');
			$this->db->join('tbl_products', 'tbl_products.id=tbl_cart.item_id');
		   	$this->db->where('tbl_refunds.status', '0');
		   	$this->db->where('tbl_cart.status', '0');
		   	$this->db->where('tbl_refunds.status', '0');
		   	$this->db->where('tbl_orders.status', '0');
		   	$this->db->order_by('tbl_refunds.createdDate', 'DESC');
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function load_coupon_details($info){
			$this->db->select('id, product, coupon_code, coupon_value, expire_date');
			$this->db->where('id', $info);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_seller_coupons');
		   	if($query->num_rows() > 0)
		   	return $query->row_array();
		}

		public function affiliate_sales(){
			$result=array();
			$this->db->select('tbl_products.id, tbl_cart.id as cartID, product_url, item_id, name, image, tbl_cart.orderID, tbl_commissions.amount commission, tbl_products.quantity as avQtns, tbl_cart.quantity, tbl_cart.createdDate, category');
			$this->db->from('tbl_cart');
			$this->db->join('tbl_affiliate_urls', 'tbl_affiliate_urls.referral_url = tbl_cart.referral_url');
			$this->db->join('tbl_products', 'tbl_products.id = item_id');
			$this->db->join('tbl_orders', 'tbl_orders.orderID = tbl_cart.orderID');
			$this->db->join('tbl_commissions', 'tbl_commissions.referral_url=tbl_cart.referral_url AND tbl_commissions.orderID=tbl_orders.orderID AND tbl_commissions.status="0"');
		   	$this->db->where('tbl_affiliate_urls.status', '0');
		   	$this->db->where('tbl_cart.status', '0');
		   	$this->db->where('tbl_orders.status', '0');
		   	$this->db->where('tbl_orders.is_complete', '0');
		   	$this->db->order_by('createdDate', 'DESC');
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}
		
		public function refund_setting(){
			$result=array();
			$this->db->select('id, period, status');
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_refund_setting');
		   	if($query->num_rows() > 0)
		   	$result = $query->row_array();
		   	return $result;
		}

    	public function save_refund_period($data, $userID){
    		$createdDate=time();
    		$period=sqlSafe($data['period']);

    		$this->db->select('id');
	        $this->db->where('status', '0');
	        $query = $this->db->get('tbl_refund_setting');

	        if($query->num_rows() > 0){
	            $this->db->set('period', $period);
		   		$this->db->where('status', '0');
		     	$this->db->update('tbl_refund_setting');
		     	echo 'Success';
	        }else{
	        	$set=$this->db->insert('tbl_refund_setting', array(
		            'period' => $period,
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

		public function check_new_refund(){
			$res=0;
			$this->db->select('tbl_refunds.id');
			$this->db->distinct();
			$this->db->from('tbl_refunds');
			$this->db->join('tbl_orders', 'tbl_orders.orderID = tbl_refunds.orderID');
			$this->db->join('tbl_cart', 'tbl_cart.orderID=tbl_refunds.orderID');
			$this->db->join('tbl_products', 'tbl_products.id=tbl_cart.item_id');
		   	$this->db->where('tbl_refunds.status', '0');
		   	$this->db->where('tbl_cart.status', '0');
		   	$this->db->where('tbl_refunds.is_processed', '1');
		   	$this->db->where('tbl_refunds.status', '0');
		   	$this->db->where('tbl_orders.status', '0');
		   	$this->db->order_by('tbl_refunds.id', 'DESC');
		   	
	   		$sql = $this->db->get();		   	
		   	$res=$sql->num_rows();
		   	return $res;
		}

    	public function save_shop_coupons($data, $userID){
    		$createdDate=time();
    		$product=sqlSafe($data['product']);
    		$coupon_code=sqlSafe($data['coupon_code']);
    		$coupon_value=sqlSafe($data['coupon_value']);
    		$expire_date=sqlSafe($data['expire_date']);

    		$this->db->select('id');
	        $this->db->where('product', $product);
	        $this->db->where('coupon_code', $coupon_code);
	        $this->db->where('coupon_value', $coupon_value);
	        $this->db->where('expire_date', $expire_date);
	        $this->db->where('status', '0');
	        $query = $this->db->get('tbl_seller_coupons');

	        if($query->num_rows() > 0){
	            die(ErrorMsg("This coupon already exist"));
	        }

            $set=$this->db->insert('tbl_seller_coupons', array(
	            'product' => $product,
	            'coupon_code' => $coupon_code,
	            'coupon_value' => $coupon_value,
	            'expire_date' => $expire_date,
	            'status' => '0',
	            'admin' => $userID,
	            'createdDate' => $createdDate
	        ));

	        if($set){
	        	return 'Success';
	        }else{
	        	return ErrorMsg($this->db->_error_message());
	        }
		}

    	public function update_shop_coupons($coupon, $data, $userID){
    		$coupon_value=sqlSafe($data['coupon_value']);
    		$expire_date=sqlSafe($data['expire_date']);

    		$set=array(
	            'coupon_value' => $coupon_value,
	            'expire_date' => $expire_date
	        );
	        $this->db->where('id',$coupon);
	     	$this->db->update('tbl_seller_coupons', $set);
	        return 'Success';
		}

		public function approve_customer_refund($refundID, $userID){
			$orderID=""; $amount=0; $phone=""; $payment_type=""; $refund_product="";
	     	$this->db->select('orderID, amount, product');
			$this->db->from('tbl_refunds');
			$this->db->where('id', $refundID);
		   	$this->db->where('status', '0');
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0){
		   		foreach ($query->result_array() as $data){
	                $orderID=$data['orderID'];
	                $amount=$data['amount'];
	                $refund_product=$data['product'];
	            }
		   	}

			$is_paid_order="0";
			$this->db->select('orderID, amount, provider');
			$this->db->from('tbl_transactions');
		   	$this->db->where('orderID', $orderID);
		   	$this->db->where('is_complete', '0');
		   	$this->db->where('status', '0');
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0){
		   		foreach ($query->result_array() as $data){
	                if($data['provider']=='selcom'){
		                if($amount>$data['amount']){
		                	$is_paid_order="Requested amount is higher than paid amount";
		                }else{
		                	$is_paid_order="1";
		                }
	                }else{
	                	$is_paid_order='forceRefund';
	                }
	            }
		   	}else{
		   		$is_paid_order="Un-paid order";
		   	}

		   	$this->db->select('orderID, payment_type, account_number');
			$this->db->from('tbl_orders');
			$this->db->where('orderID', $orderID);
		   	$this->db->where('status', '0');
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0){
		   		foreach ($query->result_array() as $data){
	                $payment_type=$data['payment_type'];
	                $phone=$data['account_number'];
	            }
		   	}

		   	if($is_paid_order=='1'){
		   		$this->load->model('Selcom_model');
 				$send=$this->Selcom_model->push_disbursement($orderID, $refundID, "", $payment_type, $phone, $amount, "refund", $userID, "admin");
 				if($send['msg']=='success'){
 					$this->db->set('is_processed', '0');
					$this->db->set('refunder', $userID);
					$this->db->set('approvedBy', $userID);
					$this->db->set('disbursedBy', $userID);
					$this->db->set('transactionID', $send['transactionID']);
			   		$this->db->where('id', $refundID);
			   		$this->db->where('status', '0');
			     	$this->db->update('tbl_refunds');

			     	$this->db->set('commissionStatus', '3');
			     	$this->db->where('status', '0');
			        $this->db->where('orderID', $orderID);
			     	$this->db->update('tbl_commissions');

			     	$this->db->set('is_complete', '4');
			     	$this->db->where('status', '0');
			        $this->db->where('orderID', $orderID);
			     	$this->db->update('tbl_orders');

			     	$sellerList=array();
			     	$this->db->select('seller_id');
					$this->db->from('tbl_cart');
					$this->db->join('tbl_products', 'tbl_products.id=item_id AND tbl_products.status="0"');
					$this->db->where('orderID', $orderID);
					if($refund_product!=""){
						$this->db->where('tbl_products.id', $refund_product);
					}
				   	$this->db->where('tbl_cart.status', '0');				   	
			   		$query14 = $this->db->get(); 	
				   	if($query14->num_rows() > 0){
				   		foreach ($query14->result_array() as $data14){
			                $com_seller_id=$data14['seller_id'];
			                if(!in_array($com_seller_id, $sellerList)){
			                	array_push($sellerList, $com_seller_id);
			                }
			            }
				   	}

				   	for ($i=0; $i < sizeof($sellerList); $i++) {
				   		$totalSeller=sizeof($sellerList);
				   		// $debt_amount=($amount+1534)/$totalSeller;
				   		/*$set=$this->db->insert('tbl_commissions', array(
				            'seller_id' => $sellerList[$i],
				            'product' => "",
				            'vendor_url' => "",
				            'cart_id' => "",
				            'orderID' => $orderID,
				            'amount' => '-'.$debt_amount,
				            'commissionStatus' => '1',
				            'status' => '0',
				            'createdDate' => time()
				        ));*/
				        $this->db->insert('tbl_transaction_records', array(
				            'transaction_type' => 'refund',
				            'seller_id' => $sellerList[$i],
				            'refund_id' => $refundID,
				            'debit' => $amount,
				            'is_complete' => '0',
				            'status' => '0',
				            'createdDate' => time()
				        ));

				        $this->db->insert('tbl_transaction_records', array(
				            'transaction_type' => 'charge',
				            'seller_id' => $sellerList[$i],
				            'refund_id' => $refundID,
				            'debit' => 1534,
				            'is_complete' => '0',
				            'status' => '0',
				            'createdDate' => time()
				        ));
				   	}

				   	return 'Success';
 				}else{
 					return $send['msg'];
 				}
		   	}else{
		   		return $is_paid_order;
		   	}
		}

		public function force_approve_customer_refund($refundID, $userID){
			$this->db->set('is_processed', '0');
			$this->db->set('refunder', $userID);
	   		$this->db->where('id', $refundID);
	   		$this->db->where('status', '0');
	     	$this->db->update('tbl_refunds');

	     	$orderID=""; $refund_product="";
	     	$this->db->select('orderID, amount, product');
			$this->db->from('tbl_refunds');
			$this->db->where('id', $refundID);
		   	$this->db->where('refunder', $userID);
		   	$this->db->where('status', '0');
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0){
		   		foreach ($query->result_array() as $data){
	                $orderID=$data['orderID'];
	                $amount=$data['amount'];
	                $refund_product=$data['product'];
	            }
		   	}

	     	$this->db->set('commissionStatus', '3');
	     	$this->db->where('status', '0');
	        $this->db->where('orderID', $orderID);
	     	$this->db->update('tbl_commissions');

	     	$this->db->set('is_complete', '4');
	     	$this->db->where('status', '0');
	        $this->db->where('orderID', $orderID);
	     	$this->db->update('tbl_orders');

	     	$sellerList=array();
	     	$this->db->select('seller_id');
			$this->db->from('tbl_cart');
			$this->db->join('tbl_products', 'tbl_products.id=item_id AND tbl_products.status="0"');
			$this->db->where('orderID', $orderID);
			if($refund_product!=""){
				$this->db->where('tbl_products.id', $refund_product);
			}
		   	$this->db->where('status', '0');				   	
	   		$query14 = $this->db->get(); 	
		   	if($query14->num_rows() > 0){
		   		foreach ($query14->result_array() as $data14){
	                $com_seller_id=$data14['seller_id'];
	                if(!in_array($com_seller_id, $sellerList)){
	                	array_push($sellerList, $com_seller_id);
	                }
	            }
		   	}

		   	for ($i=0; $i < sizeof($sellerList); $i++) {
		   		$totalSeller=sizeof($sellerList);
		   		// $debt_amount=($amount+1534)/$totalSeller;
		   		/*$set=$this->db->insert('tbl_commissions', array(
		            'seller_id' => $sellerList[$i],
		            'product' => "",
		            'vendor_url' => "",
		            'cart_id' => "",
		            'orderID' => $orderID,
		            'amount' => '-'.$debt_amount,
		            'commissionStatus' => '1',
		            'status' => '0',
		            'createdDate' => time()
		        ));*/
		        $this->db->insert('tbl_transaction_records', array(
		            'transaction_type' => 'refund',
		            'seller_id' => $sellerList[$i],
		            'refund_id' => $refundID,
		            'debit' => $amount,
		            'is_complete' => '0',
		            'status' => '0',
		            'createdDate' => time()
		        ));

		        $this->db->insert('tbl_transaction_records', array(
		            'transaction_type' => 'charge',
		            'seller_id' => $sellerList[$i],
		            'refund_id' => $refundID,
		            'debit' => 1534,
		            'is_complete' => '0',
		            'status' => '0',
		            'createdDate' => time()
		        ));
		   	}
		   	return 'Success';
		}

		public function delete_seller_coupon($infoID, $userID){
			$this->db->set('status', '1');
	   		$this->db->where('id', $infoID);
	     	$this->db->update('tbl_seller_coupons');
		   	return 'Success';
		}

    }
