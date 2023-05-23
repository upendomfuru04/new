<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Corder_model extends CI_Model{

	    public function __construct(){
	        parent::__construct();
	        $this->load->database();
    	}

    	function getCustomerPendingOrders($userID){
	        $res=0;
	        $this->db->select('id');
	        $this->db->from('tbl_orders');
	        $this->db->where('user_id', $userID);
	        $this->db->where('is_complete', '1');
	        $this->db->where('status', '0');
	        $query = $this->db->get();
	        $res=$query->num_rows();
	        return $res;
	    }

	    function getCustomerCompleteOrders($userID){
	        $res=0;
	        $this->db->select('id');
	        $this->db->from('tbl_orders');
	        $this->db->where('user_id', $userID);
	        $this->db->where('is_complete', '0');
	        $this->db->where('status', '0');
	        $query = $this->db->get();
	        if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	                $res++;
	            }
	        }
	        return $res;
	    }

	    function getPurchasedProducts($userID){
	        $res=0;
	        $this->db->select('tbl_cart.quantity');
	        $this->db->from('tbl_cart');
	        $this->db->join('tbl_orders', 'tbl_orders.orderID = tbl_cart.orderID');
	        $this->db->where('tbl_orders.user_id', $userID);
	        $this->db->where('tbl_orders.is_complete', '0');
	        $this->db->where('tbl_cart.status', '0');
	        $this->db->where('tbl_orders.status', '0');
	        $query = $this->db->get();
	        if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	                $res=$res+$data['quantity'];
	            }
	        }
	        return $res;
	    }

	    function getCustomerProcessRefunds($userID){
	        $res=0;
	        $this->db->select('tbl_refunds.id');
	        $this->db->from('tbl_refunds');
	        $this->db->join('tbl_orders', 'tbl_orders.orderID=tbl_refunds.orderID');
	        $this->db->where('user_id', $userID);
	        $this->db->where('is_processed', '0');
	        $this->db->where('tbl_refunds.status', '0');
	        $this->db->where('tbl_orders.status', '0');
	        $query = $this->db->get();
	        $res=$query->num_rows();
	        return $res;
	    }

	    function getCustomerPendingRefunds($userID){
	        $res=0;
	        $this->db->select('tbl_refunds.id');
	        $this->db->from('tbl_refunds');
	        $this->db->join('tbl_orders', 'tbl_orders.orderID=tbl_refunds.orderID');
	        $this->db->where('user_id', $userID);
	        $this->db->where('is_processed', '1');
	        $this->db->where('tbl_refunds.status', '0');
	        $this->db->where('tbl_orders.status', '0');
	        $query = $this->db->get();
	        $res=$query->num_rows();
	        return $res;
	    }

		public function load_customer_orders($customer){
			$result=array();
			$this->db->select('id, orderID, user_id, first_name, surname, phone, email, country, region, 
				notes, viewed, confirmed, paid, is_complete, createdDate, pay_date');
		   	$this->db->where('user_id', $customer);
		   	// $this->db->where('is_complete !=', '2');
		   	$this->db->where('status', '0');
		   	$this->db->order_by('createdDate', 'DESC');
		   	
	   		$query = $this->db->get('tbl_orders');		   	
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function load_single_order_info($customer, $orderID){
			$result=array();
			$pay_date=""; $is_complete="";
			$this->db->select('id, orderID, user_id, first_name, surname, phone, email, country, region, 
				notes, viewed, confirmed, paid, is_complete, createdDate, pay_date');
		   	$this->db->where('user_id', $customer);
		   	$this->db->where('orderID', $orderID);
		   	$this->db->where('status', '0');
		   	$this->db->order_by('createdDate', 'DESC');
		   	
	   		$query = $this->db->get('tbl_orders');		   	
		   	if($query->num_rows() > 0){
		   		foreach ($query->result_array() as $data) {
		   			$pay_date=$data['pay_date'];
		   			$is_complete=$data['is_complete'];
		   			$orderID=$data['orderID'];
		   		}
		   	}
		   	$result['pay_date']=$pay_date;
		   	$result['is_complete']=$is_complete;
		   	$result['orderID']=$orderID;
		   	return $result;
		}

		public function load_cart_items($customer){
			$result=array();
			$this->db->select('tbl_products.id, tbl_cart.id as cartID, product_url, item_id, name, image, tbl_cart.orderID, tbl_cart.user_id, tbl_cart.price, tbl_products.quantity as avQtns, tbl_cart.quantity, tbl_cart.is_complete, tbl_cart.createdDate');
			$this->db->from('tbl_cart');
			$this->db->join('tbl_products', 'tbl_products.id = item_id');
			$this->db->join('tbl_orders', 'tbl_orders.orderID = tbl_cart.orderID');
			$this->db->where('tbl_cart.user_id', $customer);
		   	$this->db->where('tbl_cart.is_complete', '2');
		   	$this->db->where('tbl_products.status', '0');
		   	$this->db->where('tbl_cart.status', '0');
		   	$this->db->where('tbl_orders.status', '0');
		   	$this->db->order_by('createdDate', 'DESC');
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function load_order_items($userID, $orderID){
			$result=array();
			$this->db->select('tbl_products.id, tbl_cart.id as cartID, product_url, item_id, name, image, tbl_cart.orderID, tbl_cart.user_id, tbl_cart.price, tbl_products.quantity as avQtns, tbl_cart.quantity, tbl_cart.createdDate, tbl_orders.is_complete, pay_date');
			$this->db->from('tbl_cart');
			$this->db->join('tbl_products', 'tbl_products.id = item_id');
			$this->db->join('tbl_orders', 'tbl_orders.orderID = tbl_cart.orderID');
			$this->db->where('tbl_cart.user_id', $userID);
			$this->db->where('tbl_cart.orderID', $orderID);
		   	$this->db->where('tbl_products.status', '0');
		   	$this->db->where('tbl_cart.status', '0');
		   	$this->db->order_by('createdDate', 'DESC');
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function load_order_info($customer){
			$result=array();
			$orderID=""; $totalAmount=0;
			$this->db->select('id, orderID');
			$this->db->from('tbl_orders');
			$this->db->where('user_id', $customer);
		   	$this->db->where('is_complete', '2');
		   	$this->db->where('status', '0');
		   	$this->db->limit(1);
		   	$this->db->order_by('createdDate', 'DESC');
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0){
		   		foreach ($query->result_array() as $data){
	                $orderID=$data['orderID'];
	            }
		   	}

			$this->db->select('id, user_id, price, quantity, is_complete');
			$this->db->from('tbl_cart');
			$this->db->where('user_id', $customer);
			$this->db->where('orderID', $orderID);
		   	$this->db->where('status', '0');
	   		$sql = $this->db->get();		   	
		   	if($sql->num_rows() > 0){
		   		foreach ($sql->result_array() as $rows){
	                $totalAmount=$totalAmount+($rows['price']*$rows['quantity']);
	            }
		   	}
		   	$result['orderID']=$orderID;
		   	$result['total_amount']=$totalAmount;
		   	return $result;
		}

		public function save_order($data, $userID){
    		$createdDate=time();
    		$orderID=sqlSafe($data['orderID']);
    		$coupon=sqlSafe($data['coupon']);
    		$new_price=0;
    		$total_amount=sqlSafe($data['total_amount']);
    		$coupon_value="";
    		$this->db->select('expire_date, tbl_seller_coupons.coupon_value, price, quantity, product');
    		$this->db->from('tbl_seller_coupons');
    		$this->db->join('tbl_cart', 'tbl_cart.item_id=product');
	        $this->db->where('orderID', $orderID);
	        $this->db->where('user_id', $userID);
	        $this->db->where('coupon_code', $coupon);
	        $this->db->where('is_complete!=', '0');
	        $this->db->where('tbl_seller_coupons.status','0');
	        $this->db->where('tbl_cart.status','0');
	        $sql = $this->db->get();

	        if($sql->num_rows() > 0){
	            foreach ($sql->result_array() as $rows) {
	            	$quantity=$rows['quantity'];
	            	$coupon_value=$rows['coupon_value'];
	            	$expire_date=$rows['expire_date'];
	            	$item_id=$rows['product'];
	            	$price=$rows['price'];
	            	if($expire_date!="" && timeDiffInDays(strtotime($expire_date), time())>0){
	            		die(ErrorMsg("The coupon expired."));
	            	}
	            	$new_price=$price-$coupon_value;
	            	$total_amount=$total_amount-($coupon_value*$quantity);
	            	$set1=array(
			            'coupon' => $coupon,
			            'coupon_value' => $coupon_value
			        );
			        $this->db->where('status', '0');
			        $this->db->where('item_id',$item_id);
			        $this->db->where('user_id',$userID);
			        $this->db->where('orderID', $orderID);
			     	$this->db->update('tbl_cart', $set1);
	            }
	        }else{
	        	if($coupon!=""){
	        		die(ErrorMsg("The coupon is not valid, correct or delete it to continue."));
	        	}
	        }

	        $account_name="";
	        if(isset($data['account_name'])){
	        	$account_name=sqlSafe($data['account_name']);
	        }
	        $account_number="";
	        if(isset($data['account_number'])){
	        	$account_number=sqlSafe($data['account_number']);
	        }

	        if(empty($data['total_amount'])){
	        	die(ErrorMsg("An error occur..."));
	        }
	        $amount_paid=$total_amount;
	        $payment_type=sqlSafe($data['payment_type']);
	        $first_name=sqlSafe($data['first_name']);
	        $surname=sqlSafe($data['surname']);
	        $phone=sqlSafe($data['phone']);
	        $email=sqlSafe($data['email']);
	        $address=sqlSafe($data['address']);
	        $country=sqlSafe($data['country']);
	        $city=sqlSafe($data['city']);
	        $post_code=sqlSafe($data['post_code']);
	        $notes=sqlSafe($data['notes']);

	        $set=array(
	            'first_name' => $first_name,
	            'surname' => $surname,
	            'phone' => $phone,
	            'email' => $email,
	            'address' => $address,
	            'country' => $country,
	            'region' => $city,
	            'postCode' => $post_code,
	            'notes' => $notes,
	            'amount_paid' => $total_amount,
	            'payment_type' => $payment_type,
	            'account_name' => $account_name,
	            'account_number' => $account_number,
	            // 'is_complete' => '1',
	            'pay_date' => $createdDate
	        );

	        $this->db->where('user_id', $userID);
	        $this->db->where('orderID', $orderID);
	     	$this->db->update('tbl_orders', $set);

	     	$values=array(
	            'country' => $country,
	            'city' => $city,
	            'address' => $address,
	            'post_code' => $post_code
	        );
			$this->db->where('user_id',$userID);
			$this->db->where('status', '0');
	     	$update=$this->db->update('tbl_user_info', $values);

	     	$is_success="";
	     	$this->load->model('api/Pay_model');
	     	if($payment_type=='directpay'){
	     		$service_date=date("Y/m/d h:i");
	     		$this->db->select('id');
		        $this->db->where('customer',$userID);
		        $this->db->where('orderID', $orderID);
		        $this->db->where('provider', 'directpay');
		        $this->db->where('is_complete!=', '0');
		        $this->db->where('status','0');
		        $query = $this->db->get('tbl_transactions');

		        if($query->num_rows() > 0){
		            $p_update="update";
		        }else{
		        	$p_update="";
		        }
		        $customerEmail=$email;
	        	$customerFirstName=$first_name;
	        	$customerLastName=$surname;
	        	$customerAddress=$address;
	        	$customerCity=$city;
	        	$customerCountry=$country;
	        	$customerPhone=$phone;
	        	$customerZip=$post_code;
	        	$service_type="18683"; /*default for all services*/

	        	$redirectURL=base_url('customer/payment_success/'.$orderID);
	        	$backUrl=base_url('customer/orders');
	        	$pay=$this->Pay_model->directPay_createToken($amount_paid, 'TZS', $redirectURL, $backUrl, $customerEmail, $customerFirstName, $customerLastName, $customerAddress, $customerCity, $customerCountry, $customerPhone, $customerZip, $service_type, 'Product Order '.$orderID, $service_date, $userID, $orderID, $p_update);
	        	if($pay=='Okay'){
	        		$is_success='Success';
	        	}else{
	        		die(ErrorMsg($pay));
	        	}
	     	}

	     	if($is_success=='Success'){
	     		/*$set2=array(
		            'is_complete' => '1'
		        );
		        $this->db->where('status', '0');
		        $this->db->where('user_id',$userID);
		        $this->db->where('orderID', $data['orderID']);
		     	$this->db->update('tbl_cart', $set2);*/

		     	return 'Success';
	     	}else{
	     		die(ErrorMsg("Sorry! checkout failed, please! try again or contact us to resolve the issue."));
	     	}
	        /*$this->db->where('status', '0');
	        $this->db->where('orderID', $data['orderID']);
	        $this->db->set('commissionStatus', '2');
	     	$this->db->update('tbl_commissions');*/
	        // return 'Success';
		}

		public function short_save_order($data, $userID){
    		$createdDate=time();
    		$orderID=sqlSafe($data['orderID']);
    		$coupon=sqlSafe($data['coupon']);
    		$new_price=0;
    		$total_amount=sqlSafe($data['total_amount']);
    		$coupon_value="";
    		$this->db->select('expire_date, tbl_seller_coupons.coupon_value, price, quantity, product');
    		$this->db->from('tbl_seller_coupons');
    		$this->db->join('tbl_cart', 'tbl_cart.item_id=product');
	        $this->db->where('orderID', $orderID);
	        $this->db->where('user_id', $userID);
	        $this->db->where('coupon_code', $coupon);
	        $this->db->where('is_complete!=', '0');
	        $this->db->where('tbl_seller_coupons.status','0');
	        $this->db->where('tbl_cart.status','0');
	        $sql = $this->db->get();

	        if($sql->num_rows() > 0){
	            foreach ($sql->result_array() as $rows) {
	            	$quantity=$rows['quantity'];
	            	$coupon_value=$rows['coupon_value'];
	            	$expire_date=$rows['expire_date'];
	            	$item_id=$rows['product'];
	            	$price=$rows['price'];
	            	if($expire_date!="" && timeDiffInDays(strtotime($expire_date), time())>0){
	            		die(ErrorMsg("The coupon expired."));
	            	}
	            	$new_price=$price-$coupon_value;
	            	$total_amount=$total_amount-($coupon_value*$quantity);
	            	$set1=array(
			            'coupon' => $coupon,
			            'coupon_value' => $coupon_value
			        );
			        $this->db->where('status', '0');
			        $this->db->where('item_id',$item_id);
			        $this->db->where('user_id',$userID);
			        $this->db->where('orderID', $orderID);
			     	$this->db->update('tbl_cart', $set1);
	            }
	        }else{
	        	if($coupon!=""){
	        		die(ErrorMsg("The coupon is not valid, correct or delete it to continue."));
	        	}
	        }

	        $account_name="";
	        if(isset($data['account_name'])){
	        	$account_name=sqlSafe($data['account_name']);
	        }
	        $account_number="";
	        if(isset($data['account_number'])){
	        	$account_number=sqlSafe($data['account_number']);
	        }

	        if(empty($data['total_amount'])){
	        	die(ErrorMsg("An error occur..."));
	        }
	        $amount_paid=$total_amount;
	        $payment_type='directpay';
	        $first_name=sqlSafe($data['first_name']);
	        $surname=sqlSafe($data['surname']);
	        $phone=sqlSafe($data['phone']);
	        $email=sqlSafe($data['email']);
	        $address=sqlSafe($data['address']);
	        $country=sqlSafe($data['country']);
	        $city=sqlSafe($data['city']);
	        $post_code=sqlSafe($data['post_code']);

	        $set=array(
	            'first_name' => $first_name,
	            'surname' => $surname,
	            'phone' => $phone,
	            'email' => $email,
	            'address' => $address,
	            'country' => $country,
	            'region' => $city,
	            'postCode' => $post_code,
	            'notes' => '',
	            'amount_paid' => $total_amount,
	            'payment_type' => $payment_type,
	            'account_name' => $account_name,
	            'account_number' => $account_number,
	            // 'is_complete' => '1',
	            'pay_date' => $createdDate
	        );

	        $this->db->where('user_id',$userID);
	        $this->db->where('orderID', $orderID);
	     	$this->db->update('tbl_orders', $set);

	     	$values=array(
	            'country' => $country,
	            'city' => $city,
	            'address' => $address,
	            'post_code' => $post_code
	        );
			$this->db->where('user_id',$userID);
			$this->db->where('status', '0');
	     	$update=$this->db->update('tbl_user_info', $values);

	     	$is_success="";
	     	$this->load->model('api/Pay_model');
	     	if($payment_type=='directpay'){
	     		$service_date=date("Y/m/d h:i");
	     		$this->db->select('id');
		        $this->db->where('customer',$userID);
		        $this->db->where('orderID', $orderID);
		        $this->db->where('provider', 'directpay');
		        $this->db->where('is_complete!=', '0');
		        $this->db->where('status','0');
		        $query = $this->db->get('tbl_transactions');

		        if($query->num_rows() > 0){
		            $p_update="update";
		        }else{
		        	$p_update="";
		        }
		        $customerEmail=$email;
	        	$customerFirstName=$first_name;
	        	$customerLastName=$surname;
	        	$customerAddress=$address;
	        	$customerCity=$city;
	        	$customerCountry=$country;
	        	$customerPhone=$phone;
	        	$customerZip=$post_code;
	        	$service_type="18683"; /*default for all services*/

	        	$redirectURL=base_url('customer/payment_success/'.$orderID);
	        	$backUrl=base_url('customer/orders');
	        	$pay=$this->Pay_model->directPay_createToken($amount_paid, 'TZS', $redirectURL, $backUrl, $customerEmail, $customerFirstName, $customerLastName, $customerAddress, $customerCity, $customerCountry, $customerPhone, $customerZip, $service_type, 'Product Order '.$orderID, $service_date, $userID, $orderID, $p_update);
	        	if($pay=='Okay'){
	        		$is_success='Success';
	        	}else{
	        		die(ErrorMsg($pay));
	        	}
	     	}

	     	if($is_success=='Success'){
	     		/*$set2=array(
		            'is_complete' => '1'
		        );
		        $this->db->where('status', '0');
		        $this->db->where('user_id',$userID);
		        $this->db->where('orderID', $data['orderID']);
		     	$this->db->update('tbl_cart', $set2);*/

		     	return 'Success';
	     	}else{
	     		die(ErrorMsg("Sorry! checkout failed, please! try again or contact us to resolve the issue."));
	     	}
	     	

	        /*$this->db->where('status', '0');
	        $this->db->where('orderID', $data['orderID']);
	        $this->db->set('commissionStatus', '2');
	     	$this->db->update('tbl_commissions');*/
	        // return 'Success';
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

		public function add_cart_quantity($userID, $cartID){
			$this->db->set('quantity', 'quantity+1', FALSE);
			$this->db->where('user_id', $userID);
			$this->db->where('id', $cartID);
	   		$this->db->where('is_complete', '2');
	     	$this->db->update('tbl_cart');
		   	return 'Success';
		}

		public function remove_cart_quantity($userID, $cartID){
			$this->db->set('quantity', 'quantity-1', FALSE);
			$this->db->where('user_id', $userID);
			$this->db->where('id', $cartID);
	   		$this->db->where('is_complete', '2');
	     	$this->db->update('tbl_cart');
		   	return 'Success';
		}
    }
?>