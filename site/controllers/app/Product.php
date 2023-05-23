<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Product extends CI_Controller {

		public function __construct(){
	        parent::__construct();
	        $this->load->model('app/Product_model');
	    }

	    public function index(){
	    	$this->return_error('Request not found!!!');
	    }

	    public function product_categories(){ 
			$this->authenticate_app();
			$res=array();
	    	$error=false;
	    	$feedback="";

	    	$list=array();
			$categories=$this->Product_model->load_product_categories();

	        if(sizeof($categories)>0){
	        	$error=false;
	    		$feedback="Data Loaded Successfully";
	        }else{
	        	$error=true;
	    		$feedback="No data found";
	        }

	        $list['categories']=$categories;

		    $res['error']=$error;
	        $res['feedback']=$feedback;
	        $res['result']=json_encode($list);
	        $result=json_encode($res);
	        echo $result;
		}

	    public function load_all_products(){ 
			$this->authenticate_app();
			$res=array();
	    	$error=false;
	    	$feedback="";

	    	$data = $this->input->post();
	    	$category=$data['category'];
	    	$list=array();
			$products=$this->Product_model->loadProducts($category, "", "", "", "", "");

	        if(sizeof($products)>0){
	        	$error=false;
	    		$feedback="Data Loaded Successfully";
	        }else{
	        	$error=true;
	    		$feedback="No data found";
	        }
	        $list['products']=$products;

		    $res['error']=$error;
	        $res['feedback']=$feedback;
	        $res['result']=json_encode($list);
	        $result=json_encode($res);
	        echo $result;
		}

	    public function load_latest_products(){ 
			$this->authenticate_app();
			$res=array();
	    	$error=false;
	    	$feedback="";
	    	$list=array();
			$products=$this->Product_model->loadProducts("", "", "new", "", "", "250");
	        if(sizeof($products)>0){
	        	$error=false;
	    		$feedback="Data Loaded Successfully";
	        }else{
	        	$error=true;
	    		$feedback="No data found";
	        }
	        $list['products']=$products;

		    $res['error']=$error;
	        $res['feedback']=$feedback;
	        $res['result']=json_encode($list);
	        $result=json_encode($res);
	        echo $result;
		}

	    public function load_seller_products(){ 
			$this->authenticate_app();
			$res=array();
	    	$error=false;
	    	$feedback="";
	    	$data = $this->input->post();
	    	if(!isset($data['seller']) || $data['seller']==""){
	    		$this->return_error('Unknown seller.');
	    	}
	    	$seller_id=$data['seller'];
	    	$list=array();
			$products=$this->Product_model->load_seller_products($seller_id);
	        if(sizeof($products)>0){
	        	$error=false;
	    		$feedback="Data Loaded Successfully";
	        }else{
	        	$error=true;
	    		$feedback="No data found";
	        }
	        $list['products']=$products;

		    $res['error']=$error;
	        $res['feedback']=$feedback;
	        $res['result']=json_encode($list);
	        $result=json_encode($res);
	        echo $result;
		}

	    public function product_details(){ 
			$this->authenticate_app();
			$res=array();
	    	$error=false;
	    	$feedback="";

	    	$data = $this->input->post();
	    	if(!isset($data['product']) || $data['product']==""){
	    		$this->return_error('Sorry! no product selected');
	    	}
	    	if(!isset($data['username']) || $data['username']==""){
	    		$this->return_error('Undefined user');
	    	}
	    	$product=$data['product'];
	    	$username=$data['username'];
	    	$userID=getUserIDFromUsername($username);
	    	$list=array();
			$product_info=$this->Product_model->load_product_details($product, $userID);
			$seller_info=$this->Product_model->load_seller_info($product);
			$reviews=$this->Product_model->load_product_reviews($product);

	        if(sizeof($product_info)>0){
	        	$error=false;
	    		$feedback="Data Loaded Successfully";
	        }else{
	        	$error=true;
	    		$feedback="No data found";
	        }
	        $list['product_info']=$product_info;
	        $list['seller_info']=$seller_info;
	        $list['reviews']=$reviews;

		    $res['error']=$error;
	        $res['feedback']=$feedback;
	        $res['result']=json_encode($list);
	        $result=json_encode($res);
	        echo $result;
		}

		public function send_product_review(){
			$this->authenticate_app();
			$res=array();
	    	$error=false;
	    	$feedback="";
	    	$data = $this->input->post();
	    	if(!isset($data['username']) || $data['username']==""){
	    		$this->return_error('Please! enter your username or logout then login.');
	    	}
	    	if(!isset($data['product']) || $data['product']==""){
	    		$this->return_error('Please! select product for review');
	    	}
	    	if(!isset($data['review']) || $data['review']==""){
	    		$this->return_error('Please! enter your review message');
	    	}
			$userID=getUserIDFromUsername($data['username']);
			$review=json_encode($data['review']);
			$product=$data['product'];
			$rate_counter="";
			$parent="";
			if(isset($data['rate_counter']) && $data['rate_counter']!=""){
				$rate_counter=$data['rate_counter'];
			}
			if(isset($data['parent']) && $data['parent']!=""){
				$parent=$data['parent'];
			}

			$list=array();
			$reviews=array();
	        $check=$this->Product_model->post_review($userID, $product, $review, $rate_counter, $parent);
	        if($check=='Success'){
	        	$error=false;
	    		$feedback="Thanks for your review";
	    		$reviews=$this->Product_model->load_product_reviews($product);
	        }else{
	        	$error=true;
	    		$feedback=$check;
	        }
	        $list['reviews']=$reviews;

		    $res['error']=$error;
	        $res['feedback']=$feedback;
	        $res['result']=json_encode($list);
	        $result=json_encode($res);
	        echo $result;
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
	    		$this->return_error('Please! select product for review');
	    	}
			$userID=getUserIDFromUsername($data['username']);
			$product=$data['product'];

	        $check=$this->Product_model->add_product_cart($userID, $product);
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
	        $cart_items=$this->Product_model->load_cart_items($userID);
	        $payment_methods=$this->Product_model->load_payment_methods();
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