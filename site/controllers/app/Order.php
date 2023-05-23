<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Order extends CI_Controller {

		public function __construct(){
	        parent::__construct();
	        $this->load->model('app/Order_model');
	    }

	    public function index(){
	    	$this->return_error('Request not found!!!');
	    }

		public function add_product_cart(){
			$this->authenticate_app();
			$res=array();
	    	$error=false;
	    	$feedback="";
	    	$data = $this->input->post();
	    	if(!isset($data['username']) || $data['username']==""){
	    		$this->return_error('Please! enter your username or logout then login.');
	    	}
	    	if(!isset($data['product']) || $data['product']==""){
	    		$this->return_error('Please! select product to add');
	    	}
			$userID=getUserIDFromUsername($data['username']);
			$product=$data['product'];

	        $check=$this->Order_model->add_product_cart($userID, $product);
	        if($check=='Success' || $check=='exists'){
	        	$error=false;
	    		$feedback="Product added in your Cart, continue shopping.";
	        }else{
	        	$error=true;
	    		$feedback=$check;
	        }

		    $res['error']=$error;
	        $res['feedback']=$feedback;
	        $result=json_encode($res);
	        echo $result;
		}

		public function remove_product_cart(){
			$this->authenticate_app();
			$res=array();
	    	$error=false;
	    	$feedback="";
	    	$data = $this->input->post();
	    	if(!isset($data['username']) || $data['username']==""){
	    		$this->return_error('Please! enter your username or logout then login.');
	    	}
	    	if(!isset($data['cart_id']) || $data['cart_id']==""){
	    		$this->return_error('Please! select product to remove');
	    	}
			$userID=getUserIDFromUsername($data['username']);
			$cart_id=$data['cart_id'];
			$cart_items=array();
			$payment_methods=array();
	        $check=$this->Order_model->remove_cart_item($userID, $cart_id);
	        if($check=='Success'){
	        	$error=false;
	    		$feedback="Product removed from your cart successfully!";
	    		$cart_items=$this->Order_model->load_cart_items($userID);
	        	$payment_methods=$this->Order_model->load_payment_methods();
	        }else{
	        	$error=true;
	    		$feedback=$check;
	        }
	        $list['cart_items']=$cart_items;
	        $list['payment_methods']=$payment_methods;

		    $res['error']=$error;
	        $res['feedback']=$feedback;
	        $res['result']=json_encode($list);
	        $result=json_encode($res);
	        echo $result;
		}

		public function verify_coupon(){
			$this->authenticate_app();
			$res=array();
	    	$error=false;
	    	$feedback="";
	    	$data = $this->input->post();
	    	if(!isset($data['coupon']) || $data['coupon']==""){
	    		$this->return_error('Please! enter the coupon to validate');
	    	}
			$coupon=$data['coupon'];
	        $check=$this->Order_model->verify_coupon($coupon);
	        if(is_numeric($check)){
	        	$error=false;
	    		$feedback=$check;
	        }else{
	        	$error=true;
	    		$feedback=$check;
	        }
		    $res['error']=$error;
	        $res['feedback']=$feedback;
	        $result=json_encode($res);
	        echo $result;
		}

		public function load_user_cart(){
			$this->authenticate_app();
			$res=array();
	    	$error=false;
	    	$feedback="";
	    	$data = $this->input->post();
	    	if(!isset($data['username']) || $data['username']==""){
	    		$this->return_error('Please! enter your username or logout then login.');
	    	}
			$userID=getUserIDFromUsername($data['username']);
			$list=array();
	        $cart_items=$this->Order_model->load_cart_items($userID);
	        $payment_methods=$this->Order_model->load_payment_methods();
	        if(sizeof($cart_items)>0 && sizeof($payment_methods)>0){
	        	$error=false;
	    		$feedback="Data Loaded Successfully";
	        }else{
	        	$error=true;
	    		$feedback="No data found";
	        }
	        $list['cart_items']=$cart_items;
	        $list['payment_methods']=$payment_methods;

		    $res['error']=$error;
	        $res['feedback']=$feedback;
	        $res['result']=json_encode($list);
	        $result=json_encode($res);
	        echo $result;
		}

		public function checkout_order(){
			$this->authenticate_app();
			$res=array();
	    	$error=false;
	    	$feedback="";
	    	$data = $this->input->post();
	    	if(!isset($data['username']) || $data['username']==""){
	    		$this->return_error('Please! enter your username or logout then login.');
	    	}
	    	if(!isset($data['name']) || $data['name']==""){
	    		$this->return_error('Please! enter your order`s full name');
	    	}
	    	if(!isset($data['email']) || $data['email']==""){
	    		$this->return_error('Please! enter your order`s email address');
	    	}
	    	if(!isset($data['provider_code']) || $data['provider_code']==""){
	    		$this->return_error('Please! select your payment method');
	    	}
	    	if(!isset($data['total_amount']) || $data['total_amount']==""){
	    		$this->return_error('Missing total amount, please! try again');
	    	}
			$userID=getUserIDFromUsername($data['username']);
			$customer_name=""; $customer_email=""; $customer_phone=""; $coupon=""; $payment_method=""; $country_code="";
			$total_amount=""; $country=""; $currency="";
			if(isset($data['name']))
				$customer_name=$data['name'];
			if(isset($data['email']))
				$customer_email=$data['email'];
			if(isset($data['phone']))
				$customer_phone=$data['phone'];
			if(isset($data['coupon']))
				$coupon=$data['coupon'];
			if(isset($data['provider_code']))
				$payment_method=$data['provider_code'];
			if(isset($data['country_code']))
				$country_code=$data['country_code'];
			if(isset($data['total_amount']))
				$total_amount=$data['total_amount'];
			if(isset($data['country']))
				$country=$data['country'];
			if(isset($data['currency']))
				$currency=$data['currency'];

			$list=array();
			$data['name']=$customer_name;
			$data['email']=$customer_email;
			$data['phone_number']=$customer_phone;
			$data['country_code']=$country_code;
			$data['country']=$country;
			$data['currency']=$currency;
			$data['coupon']=$coupon;
			$data['payment_method']=$payment_method;
			$data['total_amount']=$total_amount;
	        $check=$this->Order_model->checkout_order($userID, $data);
	        if(strpos($check, '@#$') !== false){
	        	$error=false;
	    		$feedback="";
	    		$url=""; $instruction=""; $msg="";
	    		$res1=trim($check);
                $rest=explode("@#$", $res1);
                if($rest[0]=='Success'){
                	$feedback="success";
                	$url=$rest[2];
                }else if($rest[0]=='callback'){
                	$feedback="callback";
                	$msg=$rest[1];
                }else if($rest[0]=='instruction'){
                	$feedback="instruction";
                	$instruction=$rest[1];
                }else if($rest[0]=='error'){
                	$feedback="error";
                	$msg=$rest[1];
                }
                $list['url']=$url;
                $list['instruction']=$instruction;
                $list['msg']=$msg;
	        }else{
	        	$error=true;
	    		$feedback=$check;
	        }

		    $res['error']=$error;
	        $res['feedback']=$feedback;
	        $res['result']=json_encode($list);
	        $result=json_encode($res);
	        echo $result;
		}

		public function check_order_status(){
			$this->authenticate_app();
			$res=array();
	    	$error=false;
	    	$feedback="";
	    	$data = $this->input->post();
	    	if(!isset($data['username']) || $data['username']==""){
	    		$this->return_error('Please! enter your username or logout then login.');
	    	}
	    	if(!isset($data['orderID']) || $data['orderID']==""){
	    		$this->return_error('Sorry! Order id is empty.');
	    	}
			$userID=getUserIDFromUsername($data['username']);
			$orderID=$data['orderID'];
	        $check=$this->Order_model->check_order_status($userID, $orderID);
	        if($check=="Success"){
	        	$error=false;
	    		$feedback="Payment Confirmed Successfully";
	        }else{
	        	$error=true;
	    		$feedback=$check;
	        }

		    $res['error']=$error;
	        $res['feedback']=$feedback;
	        $result=json_encode($res);
	        echo $result;
		}

		public function load_user_orders(){
			$this->authenticate_app();
			$res=array();
	    	$error=false;
	    	$feedback="";
	    	$data = $this->input->post();
	    	if(!isset($data['username']) || $data['username']==""){
	    		$this->return_error('Please! enter your username or logout then login.');
	    	}
			$userID=getUserIDFromUsername($data['username']);
			$list=array();
	        $orders=$this->Order_model->load_user_orders($userID);
	        if(sizeof($orders)>0){
	        	$error=false;
	    		$feedback="Data Loaded Successfully";
	        }else{
	        	$error=true;
	    		$feedback="No data found";
	        }
	        $list['orders']=$orders;

		    $res['error']=$error;
	        $res['feedback']=$feedback;
	        $res['result']=json_encode($list);
	        $result=json_encode($res);
	        echo $result;
		}

		public function load_order_items(){
			$this->authenticate_app();
			$res=array();
	    	$error=false;
	    	$feedback="";
	    	$data = $this->input->post();
	    	if(!isset($data['username']) || $data['username']==""){
	    		$this->return_error('Please! enter your username or logout then login.');
	    	}
	    	if(!isset($data['orderID']) || $data['orderID']==""){
	    		$this->return_error('Sorry! order id is empty.');
	    	}
			$userID=getUserIDFromUsername($data['username']);
			$orderID=$data['orderID'];
			$list=array();
	        $items=$this->Order_model->load_order_items($userID, $orderID);
	        if(sizeof($items)>0){
	        	$error=false;
	    		$feedback="Data Loaded Successfully";
	        }else{
	        	$error=true;
	    		$feedback="No data found";
	        }
	        $list['items']=$items;

		    $res['error']=$error;
	        $res['feedback']=$feedback;
	        $res['result']=json_encode($list);
	        $result=json_encode($res);
	        echo $result;
		}

		public function send_refund_request(){
			$this->authenticate_app();
			$res=array();
	    	$error=false;
	    	$feedback="";
	    	$data = $this->input->post();
	    	if(!isset($data['username']) || $data['username']==""){
	    		$this->return_error('Please! enter your username or logout then login.');
	    	}
	    	if(!isset($data['orderID']) || $data['orderID']==""){
	    		$this->return_error('Sorry! order id is empty.');
	    	}
	    	if(!isset($data['reason']) || $data['reason']==""){
	    		$this->return_error('Please! enter your refund reason.');
	    	}
			$userID=getUserIDFromUsername($data['username']);
	        $check=$this->Order_model->save_request_refund($userID, $data);
	        if($check=="Success"){
	        	$error=false;
	    		$feedback="Request submitted successfully! you will be notified when approved";
	        }else{
	        	$error=true;
	    		$feedback=$check;
	        }

		    $res['error']=$error;
	        $res['feedback']=$feedback;
	        $result=json_encode($res);
	        echo $result;
		}

		public function load_user_refunds(){
			$this->authenticate_app();
			$res=array();
	    	$error=false;
	    	$feedback="";
	    	$data = $this->input->post();
	    	if(!isset($data['username']) || $data['username']==""){
	    		$this->return_error('Please! enter your username or logout then login.');
	    	}
			$userID=getUserIDFromUsername($data['username']);
			$list=array();
	        $refunds=$this->Order_model->load_user_refunds($userID);
	        if(sizeof($refunds)>0){
	        	$error=false;
	    		$feedback="Data Loaded Successfully";
	        }else{
	        	$error=true;
	    		$feedback="No data found";
	        }
	        $list['refunds']=$refunds;

		    $res['error']=$error;
	        $res['feedback']=$feedback;
	        $res['result']=json_encode($list);
	        $result=json_encode($res);
	        echo $result;
		}

		public function delete_refund(){
			$this->authenticate_app();
			$res=array();
	    	$error=false;
	    	$feedback="";
	    	$data = $this->input->post();
	    	if(!isset($data['username']) || $data['username']==""){
	    		$this->return_error('Please! enter your username or logout then login.');
	    	}
	    	if(!isset($data['orderID']) || $data['orderID']==""){
	    		$this->return_error('Please! select refund to remove');
	    	}
	    	if(!isset($data['refund_id']) || $data['refund_id']==""){
	    		$this->return_error('Please! select refund request to remove');
	    	}
			$userID=getUserIDFromUsername($data['username']);
			$orderID=$data['orderID'];
			$request_id=$data['refund_id'];
			$list=array();
			$refunds=array();
	        $check=$this->Order_model->remove_refund_request($userID, $orderID, $request_id);
	        if($check=='Success'){
	        	$error=false;
	    		$feedback="Refund request removed successfully!";
	    		$refunds=$this->Order_model->load_user_refunds($userID);
	        }else{
	        	$error=true;
	    		$feedback=$check;
	        }
	        $list['refunds']=$refunds;

		    $res['error']=$error;
	        $res['feedback']=$feedback;
	        $res['result']=json_encode($list);
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
?>