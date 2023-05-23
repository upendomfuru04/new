<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Homeorder_model extends CI_Model{

	    public function __construct(){
	        parent::__construct();
	        $this->load->database();
    	}

    	function getCategoryID($category){
	        $id="";
	        $this->db->select('id');
	        $this->db->where('name',$category);
	        $query = $this->db->get('tbl_shop_category');

	        if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	                $id=$data['id'];
	            }
	        }
	        return $id;
	    }

		public function add_cart($product, $price, $userID, $product_url="", $referral="", $source_triger=""){
			$account_type_session=$this->session->userdata('account_type');
			if($account_type_session!='customer'){
				die('Login with your customer account to continue shopping');
			}
    		$createdDate=time();

    		$ordID=getLatestOrderID($userID);
    		$commission=""; $sellerCommission=""; $seller_id="";
    		$referral_url="";
    		if($referral!="") {
    			$referral_url=$product_url.'/?ref='.$referral;
    		}
    		// die($ordID);
    		if($ordID!=""){
    			$orderID=$ordID;
    		}else{
    			$order_ID=generateOrderID();
    			$this->db->insert('tbl_orders', array(
		            'orderID' => $order_ID,
		            'user_id' => $userID,
		            'is_complete' => '2',
		            'status' => '0',
		            'createdDate' => $createdDate
		        ));
		        $orderID=$order_ID;
    		}

    		$this->db->select('id');
	        $this->db->where('user_id', $userID);
	        $this->db->where('item_id', $product);
	        $this->db->where('orderID', $orderID);
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_cart');

	        if($query->num_rows() > 0){
	        	if($source_triger==""){
		            die("Product added in your Cart, continue shopping.");
		        }else{
		        	return 'exists';
		        	exit();
		        }
	        }

	        /*$this->db->select('payment_gateway_url');
	        $this->db->where('orderID', $orderID);
	        $this->db->where('user_id', $userID);
	        $this->db->where('payment_gateway_url!=', '');
	        $this->db->where('status', '0');
	        $query13 = $this->db->get('tbl_orders');
	        if($query13->num_rows() > 0){
	        	if($source_triger==""){
		            die("Sorry! you have an unfinished order with pending payment, please! complete the payment or clear your cart");
		        }else{
		        	return 'Sorry! you have an unfinished order with pending payment, please! complete the payment or clear your cart';
		        	exit();
		        }
	        }*/

            $set=$this->db->insert('tbl_cart', array(
	            'user_id' => $userID,
	            'item_id' => $product,
	            'price' => $price,
	            'quantity' => '1',
	            'is_complete' => '2',
	            'orderID' => $orderID,
	            'referral_url' => $referral_url,
	            'status' => '0',
	            'createdDate' => $createdDate
	        ));

            $this->db->select('id, price');
	        $this->db->where('user_id', $userID);
	        $this->db->where('item_id', $product);
	        $this->db->where('orderID', $orderID);
	        $this->db->where('status','0');
	        $sqlC = $this->db->get('tbl_cart');

	        if($sqlC->num_rows() > 0){
	            foreach ($sqlC->result_array() as $data) {
	                $cart_id=$data['id'];
	            }
	        }

	        $this->db->select('seller_id, seller_type');
	        $this->db->where('id', $product);
	        $sqlCom = $this->db->get('tbl_products');

	        $comsn1=0; $comsn2=0; $comsn3=0; $comsn4=0;
	        if($sqlCom->num_rows() > 0){
	            foreach ($sqlCom->result_array() as $data) {
	                $seller_type=$data['seller_type'];
	                $seller_id=$data['seller_id'];

	                $comsn1_array=getProductCommission($product);
	                $comsn2_array=getSellerTypeCommission($seller_id, $seller_type);
	                $comsn3_array=getAccountTypeCommission($seller_type);
	                $comsn4_array=getFlatCommission();

	                if(isset($comsn1_array['amount_percent']) && $comsn1_array['amount_percent']>0){
	                	$comsn1=($price*$comsn1_array['amount_percent'])/100;
	                }elseif(isset($comsn1_array['amount_fixed']) && $comsn1_array['amount_fixed']>0){
	                	$comsn1=$comsn1_array['amount_fixed'];
	                }elseif(isset($comsn2_array['amount_percent']) && $comsn2_array['amount_percent']>0){
	                	$comsn2=($price*$comsn2_array['amount_percent'])/100;
	                }elseif(isset($comsn2_array['amount_fixed']) && $comsn2_array['amount_fixed']>0){
	                	$comsn2=$comsn2_array['amount_fixed'];
	                }elseif(isset($comsn3_array['amount_percent']) && $comsn3_array['amount_percent']>0){
	                	$comsn3=($price*$comsn3_array['amount_percent'])/100;
	                }elseif(isset($comsn3_array['amount_fixed']) && $comsn3_array['amount_fixed']>0){
	                	$comsn3=$comsn3_array['amount_fixed'];
	                }elseif(isset($comsn4_array['amount_percent']) && $comsn4_array['amount_percent']>0){
	                	$comsn4=($price*$comsn4_array['amount_percent'])/100;
	                }elseif(isset($comsn4_array['amount_fixed']) && $comsn4_array['amount_fixed']>0){
	                	$comsn4=$comsn4_array['amount_fixed'];
	                }

	                if($comsn1>0){
	                	$sellerCommission=$comsn1;
	                }elseif($comsn2>0){
	                	$sellerCommission=$comsn2;
	                }elseif($comsn3>0){
	                	$sellerCommission=$comsn3;
	                }elseif($comsn4>0){
	                	$sellerCommission=$comsn4;
	                }
	            }
	        }

	        $comsn5=0; $comsn6=0;
	        /*for vendor_url*/
            $this->db->select('source_url, source_expire_date');
	        $this->db->where('user_id', $seller_id);
	        $this->db->where('source_url!=','');
	        $this->db->where('status','0');
	        $sqlC12 = $this->db->get('tbl_sellers');
	        if($sqlC12->num_rows() > 0){
	            foreach ($sqlC12->result_array() as $data12) {
	                $source_url=$data12['source_url'];
	                $expire_date=$data12['source_expire_date'];
	                if($source_url!=""){
	                	if(timeDiffInDays(strtotime($expire_date), time()) < 1){
		                	$this->db->select('user_id');
					        $this->db->where('vendor_url', $source_url);
					        $this->db->where('status','0');
					        $sqlC13 = $this->db->get('tbl_sellers');
					        if($sqlC13->num_rows() > 0){
					            foreach ($sqlC13->result_array() as $data13) {
					                $source_url_seller_id=$data13['user_id'];
					                if($source_url_seller_id!=""){
					                	$vendor_commision="";
					                	$comsn5_array=getSellerTypeVendorCommission($source_url_seller_id, 'insider');
						                $comsn6_array=getAccountTypeVendorCommission('insider');
						                $comsn7_array=getFlatVendorCommission();
						                $comsn8_array=getProductVendorCommission($product);

						                if(isset($comsn5_array['amount_percent']) && $comsn5_array['amount_percent']>0){
						                	$comsn5=($price*$comsn5_array['amount_percent'])/100;
						                }elseif(isset($comsn5_array['amount_fixed']) && $comsn5_array['amount_fixed']>0){
						                	$comsn5=$comsn5_array['amount_fixed'];
						                }elseif(isset($comsn6_array['amount_percent']) && $comsn6_array['amount_percent']>0){
						                	$comsn6=($price*$comsn6_array['amount_percent'])/100;
						                }elseif(isset($comsn6_array['amount_fixed']) && $comsn6_array['amount_fixed']>0){
						                	$comsn6=$comsn6_array['amount_fixed'];
						                }elseif(isset($comsn7_array['amount_percent']) && $comsn7_array['amount_percent']>0){
						                	$comsn7=($price*$comsn7_array['amount_percent'])/100;
						                }elseif(isset($comsn7_array['amount_fixed']) && $comsn7_array['amount_fixed']>0){
						                	$comsn7=$comsn7_array['amount_fixed'];
						                }elseif(isset($comsn8_array['amount_percent']) && $comsn8_array['amount_percent']>0){
						                	$comsn8=($price*$comsn8_array['amount_percent'])/100;
						                }elseif(isset($comsn8_array['amount_fixed']) && $comsn8_array['amount_fixed']>0){
						                	$comsn8=$comsn8_array['amount_fixed'];
						                }
						                if($comsn5>0){
						                	$vendor_commision=$comsn5;
						                }elseif($comsn6>0){
						                	$vendor_commision=$comsn6;
						                }elseif($comsn7>0){
						                	$vendor_commision=$comsn7;
						                }elseif($comsn8>0){
						                	$vendor_commision=$comsn8;
						                }
					                	$set=$this->db->insert('tbl_commissions', array(
								            'seller_id' => $source_url_seller_id,
								            'product' => $product,
								            'vendor_url' => $source_url,
								            'cart_id' => $cart_id,
								            'orderID' => $orderID,
								            'amount' => $vendor_commision,
								            'commissionStatus' => '5',
								            'status' => '0',
								            'createdDate' => $createdDate
								        ));

					                	/*set transaction record*/
								        $this->db->select('id');
					                	$this->db->from('tbl_commissions');
								        $this->db->where('seller_id', $source_url_seller_id);
								        $this->db->where('product', $product);
								        $this->db->where('vendor_url', $source_url);
								        $this->db->where('cart_id', $cart_id);
								        $this->db->where('orderID', $orderID);
								        $this->db->where('amount', $vendor_commision);
								        $this->db->where('commissionStatus', '5');
								        $this->db->where('status', '0');

								        $sql = $this->db->get();
								        foreach ($sql->result_array() as $rows) {
								        	$commission_id=$rows['id'];
						                	$this->db->insert('tbl_transaction_records', array(
									            'transaction_type' => 'commission',
									            'seller_id' => $source_url_seller_id,
									            'commission_id' => $commission_id,
									            'credit' => $vendor_commision,
									            'is_complete' => '1',
									            'status' => '0',
									            'createdDate' => $createdDate
									        ));
						                }
						                /*end t r*/
					                }
					            }
					        }
				        }
	                }
	            }
	        }
            /**/


	        $comsn1=0; $comsn2=0; $comsn3=0; $comsn4=0;
            if(isReferralValid($referral_url, $product)){
            	$affiliator="";
            	$comsn1=0; $comsn2=0; $comsn3=0; $comsn4=0;
			    $this->db->select('seller_id, seller_type');
		        $this->db->where('product', $product);
		        $this->db->where('referral_url', $referral_url);
		        $sqlCom = $this->db->get('tbl_affiliate_urls');

		        if($sqlCom->num_rows() > 0){
		            foreach ($sqlCom->result_array() as $data) {
		                $affiliator=$data['seller_id'];
		                $affiliatorType=$data['seller_type'];
		                // $comsn1_array=getProductCommission($product);
		                $comsn2_array=getSellerTypeCommission($affiliator, $affiliatorType);
		                $comsn3_array=getAccountTypeCommission($affiliatorType);
		                // $comsn4_array=getFlatCommission();

		                /*if($comsn1_array['amount_percent']>0){
		                	$comsn1=($price*(100-$comsn1_array['amount_percent']))/100;
		                }elseif($comsn1_array['amount_fixed']>0){
		                	$comsn1=$price-$comsn1_array['amount_fixed'];
		                }else*/
		                if(isset($comsn2_array['amount_percent']) && $comsn2_array['amount_percent']>0){
		                	$comsn2=($price*$comsn2_array['amount_percent'])/100;
		                }elseif(isset($comsn2_array['amount_fixed']) && $comsn2_array['amount_fixed']>0){
		                	$comsn2=$comsn2_array['amount_fixed'];
		                }elseif(isset($comsn3_array['amount_percent']) && $comsn3_array['amount_percent']>0){
		                	$comsn3=($price*$comsn3_array['amount_percent'])/100;
		                }elseif(isset($comsn3_array['amount_fixed']) && $comsn3_array['amount_fixed']>0){
		                	$comsn3=$comsn3_array['amount_fixed'];
		                }
		                /*elseif($comsn4_array['amount_percent']>0){
		                	$comsn4=($price*(100-$comsn4_array['amount_percent']))/100;
		                }elseif($comsn4_array['amount_fixed']>0){
		                	$comsn4=$price-$comsn4_array['amount_fixed'];
		                }*/

		                /*if($comsn1>0){
		                	$commission=$comsn1;
		                }else*/
		                if($comsn2>0){
		                	$commission=$comsn2;
		                }elseif($comsn3>0){
		                	$commission=$comsn3;
		                }
		                /*elseif($comsn4>0){
		                	$commission=$comsn4;
		                }*/
		            }
		        }

		        $set=$this->db->insert('tbl_commissions', array(
		            'seller_id' => $affiliator,
		            'product' => $product,
		            'referral_url' => $referral_url,
		            'cart_id' => $cart_id,
		            'orderID' => $orderID,
		            'amount' => $commission,
		            'commissionStatus' => '5',
		            'status' => '0',
		            'createdDate' => $createdDate
		        ));

		        /*set transaction record*/
		        $this->db->select('id');
            	$this->db->from('tbl_commissions');
		        $this->db->where('seller_id', $affiliator);
		        $this->db->where('product', $product);
		        $this->db->where('referral_url', $referral_url);
		        $this->db->where('cart_id', $cart_id);
		        $this->db->where('orderID', $orderID);
		        $this->db->where('amount', $commission);
		        $this->db->where('commissionStatus', '5');
		        $this->db->where('status', '0');

		        $sql = $this->db->get();
		        foreach ($sql->result_array() as $rows) {
		        	$commission_id=$rows['id'];
                	$this->db->insert('tbl_transaction_records', array(
			            'transaction_type' => 'commission',
			            'seller_id' => $affiliator,
			            'commission_id' => $commission_id,
			            'credit' => $commission,
			            'is_complete' => '1',
			            'status' => '0',
			            'createdDate' => $createdDate
			        ));
                }
                /*end t r*/
		    }

		    $set=$this->db->insert('tbl_commissions', array(
	            'seller_id' => $seller_id,
	            'product' => $product,
	            'referral_url' => '',
	            'cart_id' => $cart_id,
	            'orderID' => $orderID,
	            'amount' => $sellerCommission,
	            'commissionStatus' => '5',
	            'status' => '0',
	            'createdDate' => $createdDate
	        ));

	        if($set){
	        	/*set transaction record*/
		        $this->db->select('id');
            	$this->db->from('tbl_commissions');
		        $this->db->where('seller_id', $seller_id);
		        $this->db->where('product', $product);
		        $this->db->where('referral_url', '');
		        $this->db->where('cart_id', $cart_id);
		        $this->db->where('orderID', $orderID);
		        $this->db->where('amount', $sellerCommission);
		        $this->db->where('commissionStatus', '5');
		        $this->db->where('status', '0');

		        $sql = $this->db->get();
		        foreach ($sql->result_array() as $rows) {
		        	$commission_id=$rows['id'];
                	$this->db->insert('tbl_transaction_records', array(
			            'transaction_type' => 'commission',
			            'seller_id' => $seller_id,
			            'commission_id' => $commission_id,
			            'credit' => $sellerCommission,
			            'is_complete' => '1',
			            'status' => '0',
			            'createdDate' => $createdDate
			        ));
                }
                /*end t r*/
	        	return 'Success';
	        }else{
	        	return ErrorMsg($this->db->_error_message());
	        }
		}

		public function remove_product($product, $userID){
			$this->db->set('status', '1');
			$this->db->where('user_id', $userID);
			$this->db->where('id', $product);
	   		$this->db->where('is_complete', '2');
	     	$this->db->update('tbl_cart');

			$this->db->set('status', '1');
			$this->db->where('cart_id', $product);
	   		$this->db->where('commissionStatus', '5');
	     	$this->db->update('tbl_commissions');
		   	return 'Success';
		}

		public function remove_all_product($userID){
			$this->db->select('orderID, id');
	        $this->db->where('user_id', $userID);
	   		$this->db->where('is_complete', '2');
	        $query = $this->db->get('tbl_cart');

		   	if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	                $orderID=$data['orderID'];
	                $cart_id=$data['id'];
	                $this->db->set('status', '1');
					$this->db->where('cart_id', $cart_id);
					$this->db->where('orderID', $orderID);
			   		$this->db->where('commissionStatus', '5');
			     	$this->db->update('tbl_commissions');
	            }
	        }

			$this->db->set('status', '1');
			$this->db->where('user_id', $userID);
	   		$this->db->where('is_complete', '2');
	     	$this->db->update('tbl_cart');
		   	return 'Success';
		}

		public function checkout_order($data, $userID){
		   
    		$createdDate=time();
    		$token=generateToken();
    		// $password=getRandomNumber(4);
    		$password='123456';
    		$email=sqlSafe($data['email']);
    		$phone=""; $address=""; $country=""; $city=""; $post_code="";
    		$name=explode(' ', sqlSafe($data['name']));
    		if(sizeof($name)>=2){
    			$first_name=$name[0];
    			$surname=$name[1];
    		}else{
    			$first_name=$name[0];
    			$surname='';
    		}

    		if(!isset($first_name) || $first_name==''){
        		die(ErrorMsg('Enter your full name'));
        	}

    		if(!isset($surname) || $surname==''){
        		die(ErrorMsg('Enter your full name'));
        	}

    		if($data['payment_method']=='mastercard' || $data['payment_method']=='visa' || $data['payment_method']=='amex'){
	        	if(!isset($data['phone_number1']) || $data['phone_number1']==''){
	        		die(ErrorMsg('Enter your phone number'));
	        	}
	        	if(!isset($data['address']) || $data['address']==''){
	        		die(ErrorMsg('Enter your address'));
	        	}
	        	if(!isset($data['country_name']) || $data['country_name']==''){
	        		die(ErrorMsg('Enter your country name'));
	        	}
	        	if(!isset($data['city']) || $data['city']==''){
	        		die(ErrorMsg('Enter your city/region name'));
	        	}
	        	if(!isset($data['post_code']) || $data['post_code']==''){
	        		die(ErrorMsg('Enter your PostCode or P. O. Box'));
	        	}
	        	$phone=sqlSafe($data['phone_number1']);
	        	$address=sqlSafe($data['address']);
	        	$country=sqlSafe($data['country_name']);
	        	$city=sqlSafe($data['city']);
	        	$post_code=sqlSafe($data['post_code']);
	        }else{
	        	if(!isset($data['phone_number']) || $data['phone_number']==''){
	        		die(ErrorMsg('Enter your phone number for mobile transactions'));
	        	}
	        	if(!isset($data['country_code']) || $data['country_code']==''){
	        		die(ErrorMsg('Please! select phone number operator'));
	        	}
	        	if(isset($data['phone_number']) && $data['phone_number']!=''){
	    			$country_code=ltrim(sqlSafe($data['country_code']), '+');
	    			$phone=format_phone_number(sqlSafe($data['phone_number']));
	    			$phone=$country_code.''.$phone;
	    		}
	        }

    		$this->db->select('id, account_type');
	        $this->db->where('username', $email);
	        $this->db->where('status!=', '1');
	        $query3 = $this->db->get('tbl_users');
    		// $num = $this->db->where('username', $data['email'])->count_all_results('tbl_users');
    		$num = $query3->num_rows();
	        if ($num == 0) {
	        	$set=$this->db->insert('tbl_users', array(
		            'username' => $email,
		            'password' => password_hash($password, PASSWORD_BCRYPT),
		            'token' => $token,
		            'account_type' => 'customer',
		            'password_set' => '1',
		            'status' => '0',
		            'createdDate' => $createdDate
		        ));

		        if(!$set){
		        	die(ErrorMsg($this->db->_error_message()));
		        }
	        }else{
	        	foreach ($query3->result_array() as $data3) {
	        		if($data3['account_type']!='customer'){
	        			die(ErrorMsg('Sorry! this email already registered to another account type, please! use another email address'));
	        		}
	        	}
	        	$this->db->set('token', $token);
				$this->db->where('username', $email);
				$this->db->where('status', '0');
		     	$this->db->update('tbl_users');
	        }
	        $user_id="";
	        $this->db->select('id');
	        $this->db->where('username', $email);
	        $this->db->where('account_type', 'customer');
	        $this->db->where('status', '0');
	        $query = $this->db->get('tbl_users');
	        if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data1) {
	                $user_id=$data1['id'];
	            }
	        }

	        if($user_id!=''){
	        	$userID=$user_id;
	        	$this->db->select('user_id');
		        $this->db->where('user_id', $user_id);
		        $this->db->where('status', '0');
		        $query = $this->db->get('tbl_user_info');
		        if($query->num_rows() == 0){
		            $profile_url=generateLink('tbl_user_info', 'profile_url', $surname.' '.$first_name, '');
	        		$this->db->insert('tbl_user_info', array(
			            'user_id' => $user_id,
			            'first_name' => $first_name,
			            'surname' => $surname,
			            'phone' => $phone,
			            'email' => $email,
			            'profile_url' => $profile_url,
			            'status' => '0',
			            'createdDate' => $createdDate
			        ));
			        if($this->db->affected_rows() == 0){
		        		die(ErrorMsg($this->db->_error_message()));
		        	}
		        }else{
		        	$this->db->set('phone', $phone);
					$this->db->where('user_id', $user_id);
					$this->db->where('status', '0');
			     	$this->db->update('tbl_user_info');
		        }
		        $this->session->set_userdata('getvalue_user_idetification', $token);
        		$this->session->set_userdata('account_type', 'customer');
        		$this->session->set_userdata('user_full_name', $first_name.' '.$surname);

        		/*set cookie*/
        		if($data['phone_number']!=""){
        			$phn=sqlSafe($data['phone_number']);
        		}else{
        			$phn=sqlSafe($data['phone_number1']);
        		}
        		$this->load->helper('cookie');
        		if(isset($data['remember_me'])){
		     		$payment_number="";
		     		if($phone!=""){
		     			$payment_number=$phone;	
		     		}else{
		     			$payment_number="";
		     		}
		     		/*$cookiedata = array(
				        'nspl_name' => $first_name.' '.$surname,
				        'nspl_email' => $email,
				        'nspl_payment_method' => $data['payment_method'],
				        'nspl_payment_number' => $payment_number
				    );*/
				    $cookie = array(
					    'name'   => 'nspl_name',
					    'value'  =>  $first_name.' '.$surname,
					    'expire' => '1209600',  // Two weeks
					    'domain' => '.getvalue.co',
					    'path'   => '/'
					);
				    set_cookie($cookie);
				    $cookie = array(
					    'name'   => 'nspl_email',
					    'value'  =>  $email,
					    'expire' => '1209600',  // Two weeks
					    'domain' => '.getvalue.co',
					    'path'   => '/'
					);
				    set_cookie($cookie);
				    $cookie = array(
					    'name'   => 'nspl_payment_method',
					    'value'  =>  $data['payment_method'],
					    'expire' => '1209600',  // Two weeks
					    'domain' => '.getvalue.co',
					    'path'   => '/'
					);
				    set_cookie($cookie);
				    $cookie = array(
					    'name'   => 'nspl_payment_number',
					    'value'  =>  $phn,
					    'expire' => '1209600',  // Two weeks
					    'domain' => '.getvalue.co',
					    'path'   => '/'
					);
				    set_cookie($cookie);
		     	}else{
				    $cookie = array(
					    'name'   => 'nspl_name',
					    'value'  => '',
					    'expire' => '0',  // Two weeks
					    'domain' => '',
					    'path'   => '/'
					);
				    set_cookie($cookie);
				    $cookie = array(
					    'name'   => 'nspl_email',
					    'value'  => '',
					    'expire' => '0',  // Two weeks
					    'domain' => '',
					    'path'   => '/'
					);
				    set_cookie($cookie);
				    $cookie = array(
					    'name'   => 'nspl_payment_method',
					    'value'  => '',
					    'expire' => '0',  // Two weeks
					    'domain' => '',
					    'path'   => '/'
					);
				    set_cookie($cookie);
				    $cookie = array(
					    'name'   => 'nspl_payment_number',
					    'value'  => '',
					    'expire' => '0',  // Two weeks
					    'domain' => '',
					    'path'   => '/'
					);
				    set_cookie($cookie);
		     	}
		     	/*end of cookie*/


	    		$product=sqlSafe($data['product']);
	    		$price=sqlSafe($data['price']);
	    		$total_amount=sqlSafe($data['total_amount']);
	    		$referral=sqlSafe($data['referral']);
	    		$product_url=sqlSafe($data['product_url']);
	    		if($product!=""){
	    			// add_cart($product, $price, $userID, $product_url="", $referral="", $source_triger="")
	    			$cart=$this->add_cart($product, $price, $user_id, $product_url, $referral, "quick");
	        		if($cart!='Success' && $cart!='exists'){
	        			die(ErrorMsg($cart));
	        		}
	    		}

        		$this->load->model('customer/Corder_model');
        		$order_info = $this->Corder_model->load_order_info($user_id);
    			$orderID=$order_info['orderID'];

    			$cart_total_amount=0;
        		$this->db->select('price');
		        $this->db->where('orderID', $orderID);
		        $this->db->where('user_id', $user_id);
		        $this->db->where('status', '0');
		        $query11 = $this->db->get('tbl_cart');
		        if($query11->num_rows() > 0){
		            foreach ($query11->result_array() as $data11) {
		                $cart_total_amount=$cart_total_amount + $data11['price'];
		            }
		        }
		        $no_of_items=$query11->num_rows();

        		if($total_amount!=$cart_total_amount){
        			die(ErrorMsg('Total amount does not match with your cart order, please! refresh your page. found '.$cart_total_amount));
        		}else{
		    		$coupon=$data['coupon'];
		    		$new_price=0;
		    		$total_amount=$data['total_amount'];
		    		$coupon_value="";
		    		$this->db->select('expire_date, tbl_seller_coupons.coupon_value, price, quantity, product, tbl_cart.id as cart_id');
		    		$this->db->from('tbl_seller_coupons');
		    		$this->db->join('tbl_cart', 'tbl_cart.item_id=product');
			        $this->db->where('orderID', $orderID);
			        $this->db->where('user_id', $user_id);
			        $this->db->where('coupon_code', $coupon);
			        $this->db->where('is_complete!=', '0');
			        $this->db->where('tbl_seller_coupons.status','0');
			        $this->db->where('tbl_cart.status','0');
			        $sql = $this->db->get();

			        if($sql->num_rows() > 0){
			            foreach ($sql->result_array() as $rows) {
			            	$cart_id=$rows['cart_id'];
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
					        $this->db->where('user_id',$user_id);
					        $this->db->where('orderID', $orderID);
					     	$this->db->update('tbl_cart', $set1);
					     	$update_com=$this->update_commission($orderID, $cart_id, $item_id, $new_price, $referral);
					     	if($update_com!='Success'){
					     		die($update_com);
					     	}
			            }
			        }else{
			        	if($coupon!=""){
			        		die(ErrorMsg("The coupon is not valid, correct or delete it to continue."));
			        	}
			        }

			        if(empty($data['total_amount'])){
			        	die(ErrorMsg("An error occur..."));
			        }

			        if($data['payment_method']=='mastercard' || $data['payment_method']=='visa'){
			        	$payment_method='Bank';
			        	$payment_type=sqlSafe($data['payment_method']);
			        	$account_number="";
			        }else{
			        	$payment_method='Mobile';
			        	$payment_type=sqlSafe($data['payment_method']);
			        	$account_number=$phone;
			        }

			        $set=array(
			            'first_name' => $first_name,
			            'surname' => $surname,
			            'email' => $email,
			            'phone' => $phone,
			            'amount_paid' => $total_amount,
			            'payment_method' => $payment_method,
			            'payment_type' => $payment_type,
			            'account_number' => $account_number,
			            'pay_date' => $createdDate
			        );

			        $this->db->where('user_id',$user_id);
			        $this->db->where('orderID', $orderID);
			     	$this->db->update('tbl_orders', $set);

			     	$is_success="";
		     	    //if($data['payment_method']=='mpesa' || $data['payment_method']=='airtelmoney' || $data['payment_method']=='tigopesa' || $data['payment_method']=='halopesa' || $data['payment_method']=='ezypesa' || $data['payment_method']=='ttclpesa'){
		     	    if($data['payment_method']=='airtelmoney' || $data['payment_method']=='tigopesa' || $data['payment_method']=='halopesa' || $data['payment_method']=='ezypesa' || $data['payment_method']=='ttclpesa'){
			     		/*Selcom api for mobile payment*/
	     				$this->load->model('api/Selcom_model');
	     				
	     				$send=$this->Selcom_model->create_mobile_order($orderID, $user_id, $email, $data['name'], $phone, $total_amount, 'TZS', $no_of_items, $data['payment_method']);
	     			    
	     				if(!$send['error']){
	     					if($send['msg']!='Success'){
	     						return 'callback@#$'.$send['msg'].'@#$'.$orderID;
	     					}else{
	     						if($send['instruction']!=""){
	     							return 'instruction@#$'.$send['instruction'].'@#$'.$orderID;
	     						}else{
	     							return 'Success@#$'.$send['msg'].'@#$'.$send['url'];
	     						}
	     						
	     					}
	     				}else{
	     					return 'error@#$'.$send['msg'].'@#$ ';
	     				}
			     	}
			     	elseif($data['payment_method']=='mpesa'){
			     		/*Vodacom Mpesa api for mobile payment*/
			     		
	     				$this->load->model('api/Vpesa_model');
	     				
	     				$send=$this->Vpesa_model->create_mobile_order($orderID, $user_id, $phone, $total_amount);
	     				
	     				if(!$send['error']){
	     					if($send['msg'] !='Success'){
	     						 $msj='Request in progress. You will receive a callback shortly to the phone number you provided, make sure it is available and phone is active. Click resend btn if callback takes more than 2 mins';
	     						 return 'callback@#$'.$msj.'@#$'.$orderID;
	     					}else{
	     						return 'Success@#$'.$send['msg'].'@#$'.$orderID.$send['url'];
	     					}
	     				}else{
	     					return 'error@#$'.$send['msg'].'@#$ ';
	     				}
			     	}
			     	elseif($data['payment_method']=='mastercard' || $data['payment_method']=='visa' || $data['payment_method']=='amex'){
			     		/*Selcom api for bank payment*/
	     				$this->load->model('api/Selcom_model');
	     				$send=$this->Selcom_model->create_bank_order($orderID, $user_id, $email, $data['name'], $first_name, $surname, $phone, $address, $city, $post_code, $country, $total_amount, 'TZS', $no_of_items, $data['payment_method']);
	     				if(!$send['error']){
	     					if($send['msg']!='Success'){
	     						return 'callback@#$'.$send['msg'].'@#$'.$orderID;
	     					}else{
	     						if($send['instruction']!=""){
	     							return 'instruction@#$'.$send['instruction'].'@#$'.$orderID;
	     						}else{
	     							return 'Success@#$'.$send['msg'].'@#$'.$send['url'];
	     						}
	     						
	     					}
	     				}else{
	     					return 'error@#$'.$send['msg'].'@#$ ';
	     				}
			     	}else{
			     		/*Direct api for internation payment - mobile*/
	     				$this->load->model('api/Pay_model');
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
			        	$customerAddress="";
			        	$customerDialCode="";
			        	$customerCity="";
			        	$customerCountry="";
			        	if($data['country']=='kenya'){
			        		$customerCountry="KE";
			        	}else if($data['country']=='uganda'){
			        		$customerCountry="UG";
			        	}else if($data['country']=='rwanda'){
			        		$customerCountry="RW";
			        	}else if($data['country']=='ghana'){
			        		$customerCountry="GH";
			        	}else if($data['country']=='ivory_cost'){
			        		$customerCountry="IC";
			        	}
			        	$customerPhone=$phone;
			        	$customerZip="";
			        	$service_description='Product Order '.$orderID;
			        	$service_type="18683"; /*default for all services*/
			        	$currency=sqlSafe($data['currency']);

			        	$redirectURL=base_url('customer/payment_success/'.$orderID);
			        	$backUrl=base_url('customer/orders');
			        	// $conv=$this->Pay_model->currencyConverter('TZS', $currency, $total_amount); die($conv.' '.$currency);
			        	$pay=$this->Pay_model->directPay_createToken_mobile($total_amount, $currency, $redirectURL, $backUrl, $customerEmail, $customerFirstName, $customerLastName, $customerAddress, $customerDialCode, $customerCity, $customerCountry, $customerZip, $customerPhone, $service_type, $service_description, $service_date, $userID, $orderID, $p_update);
			        	if($pay=='Okay'){
			        		$trans_token="";
			        		$MNOcountry=$data['country'];
			        		$MNO="";
			        		if($data['payment_method']=='safaricom'){
				        		$MNO="mpesa";
				        	}else if($data['payment_method']=='airtelmoney_kenya'){
				        		$MNO="airtel";
				        	}else if($data['payment_method']=='mtn_uganda'){
				        		$MNO="MTNmobilemoney";
				        	}else if($data['payment_method']=='mtn_rwanda'){
				        		$MNO="MTN";
				        	}else if($data['payment_method']=='mtn_ghana'){
				        		$MNO="MTN";
				        	}else if($data['payment_method']=='mtn_ivory'){
				        		$MNO="MTN";
				        	}else if($data['payment_method']=='orange_ivory'){
				        		$MNO="Orange";
				        	}
			        		$this->db->select('trans_token');
					        $this->db->where('orderID', $orderID);
					        $this->db->where('customer', $userID);
					        $this->db->where('provider', 'directpay');
					        $this->db->where('is_complete!=', '0');
					        $this->db->where('status', '0');
					        $query13 = $this->db->get('tbl_transactions');
					        if($query13->num_rows() > 0){
					        	foreach ($query13->result_array() as $dt) {
					        		$trans_token=$dt['trans_token'];
					        	}
					        }
					        $instr=$this->Pay_model->get_phone_menu($phone, $MNO, $MNOcountry, $trans_token);
					        if($instr['status']=="Okay"){
					        	$converted_amount=$total_amount;
					        	if($converted_amount!=""){

					        	}
					        	return 'instruction@#$'.$instr['instruction'].'@#$'.$orderID;
					        }else{
					        	return ErrorMsg($instr['status']);
					        }
			        	}else{
			        		die(ErrorMsg($pay));
			        	}
			     	}
        		}
	        }else{
	        	die(ErrorMsg('Account creation failed, please! contact us for help'));
	        }
	        /*$this->db->where('status', '0');
	        $this->db->where('orderID', $data['orderID']);
	        $this->db->set('commissionStatus', '2');
	     	$this->db->update('tbl_commissions');*/
	        // return 'Success';
		}

		public function update_commission($orderID, $cart_id, $product, $price, $referral_url){
			$this->db->select('seller_id, seller_type');
	        $this->db->where('id', $product);
	        $sqlCom = $this->db->get('tbl_products');

	        $comsn1=0; $comsn2=0; $comsn3=0; $comsn4=0;
	        if($sqlCom->num_rows() > 0){
	            foreach ($sqlCom->result_array() as $data) {
	                $seller_type=$data['seller_type'];
	                $seller_id=$data['seller_id'];

	                $comsn1_array=getProductCommission($product);
	                $comsn2_array=getSellerTypeCommission($seller_id, $seller_type);
	                $comsn3_array=getAccountTypeCommission($seller_type);
	                $comsn4_array=getFlatCommission();

	                if(isset($comsn1_array['amount_percent']) && $comsn1_array['amount_percent']>0){
	                	$comsn1=($price*$comsn1_array['amount_percent'])/100;
	                }elseif(isset($comsn1_array['amount_fixed']) && $comsn1_array['amount_fixed']>0){
	                	$comsn1=$comsn1_array['amount_fixed'];
	                }elseif(isset($comsn2_array['amount_percent']) && $comsn2_array['amount_percent']>0){
	                	$comsn2=($price*$comsn2_array['amount_percent'])/100;
	                }elseif(isset($comsn2_array['amount_fixed']) && $comsn2_array['amount_fixed']>0){
	                	$comsn2=$comsn2_array['amount_fixed'];
	                }elseif(isset($comsn3_array['amount_percent']) && $comsn3_array['amount_percent']>0){
	                	$comsn3=($price*$comsn3_array['amount_percent'])/100;
	                }elseif(isset($comsn3_array['amount_fixed']) && $comsn3_array['amount_fixed']>0){
	                	$comsn3=$comsn3_array['amount_fixed'];
	                }elseif(isset($comsn4_array['amount_percent']) && $comsn4_array['amount_percent']>0){
	                	$comsn4=($price*$comsn4_array['amount_percent'])/100;
	                }elseif(isset($comsn4_array['amount_fixed']) && $comsn4_array['amount_fixed']>0){
	                	$comsn4=$comsn4_array['amount_fixed'];
	                }

	                if($comsn1>0){
	                	$sellerCommission=$comsn1;
	                }elseif($comsn2>0){
	                	$sellerCommission=$comsn2;
	                }elseif($comsn3>0){
	                	$sellerCommission=$comsn3;
	                }elseif($comsn4>0){
	                	$sellerCommission=$comsn4;
	                }
	            }
	        }

	        $comsn5=0; $comsn6=0;
	        /*for vendor_url*/
            $this->db->select('source_url, source_expire_date');
	        $this->db->where('user_id', $seller_id);
	        $this->db->where('source_url!=','');
	        $this->db->where('status','0');
	        $sqlC12 = $this->db->get('tbl_sellers');
	        if($sqlC12->num_rows() > 0){
	            foreach ($sqlC12->result_array() as $data12) {
	                $source_url=$data12['source_url'];
	                $expire_date=$data12['source_expire_date'];
	                if($source_url!=""){
	                	if(timeDiffInDays(strtotime($expire_date), time()) < 1){
		                	$this->db->select('user_id');
					        $this->db->where('vendor_url', $source_url);
					        $this->db->where('status','0');
					        $sqlC13 = $this->db->get('tbl_sellers');
					        if($sqlC13->num_rows() > 0){
					            foreach ($sqlC13->result_array() as $data13) {
					                $source_url_seller_id=$data13['user_id'];
					                if($source_url_seller_id!=""){
					                	$vendor_commision="";
					                	$comsn5_array=getSellerTypeVendorCommission($source_url_seller_id, 'insider');
						                $comsn6_array=getAccountTypeVendorCommission('insider');
						                $comsn7_array=getFlatVendorCommission();
						                $comsn8_array=getProductVendorCommission($product);

						                if(isset($comsn5_array['amount_percent']) && $comsn5_array['amount_percent']>0){
						                	$comsn5=($price*$comsn5_array['amount_percent'])/100;
						                }elseif(isset($comsn5_array['amount_fixed']) && $comsn5_array['amount_fixed']>0){
						                	$comsn5=$comsn5_array['amount_fixed'];
						                }elseif(isset($comsn6_array['amount_percent']) && $comsn6_array['amount_percent']>0){
						                	$comsn6=($price*$comsn6_array['amount_percent'])/100;
						                }elseif(isset($comsn6_array['amount_fixed']) && $comsn6_array['amount_fixed']>0){
						                	$comsn6=$comsn6_array['amount_fixed'];
						                }elseif(isset($comsn7_array['amount_percent']) && $comsn7_array['amount_percent']>0){
						                	$comsn7=($price*$comsn7_array['amount_percent'])/100;
						                }elseif(isset($comsn7_array['amount_fixed']) && $comsn7_array['amount_fixed']>0){
						                	$comsn7=$comsn7_array['amount_fixed'];
						                }elseif(isset($comsn8_array['amount_percent']) && $comsn8_array['amount_percent']>0){
						                	$comsn8=($price*$comsn8_array['amount_percent'])/100;
						                }elseif(isset($comsn8_array['amount_fixed']) && $comsn8_array['amount_fixed']>0){
						                	$comsn8=$comsn8_array['amount_fixed'];
						                }
						                if($comsn5>0){
						                	$vendor_commision=$comsn5;
						                }elseif($comsn6>0){
						                	$vendor_commision=$comsn6;
						                }elseif($comsn7>0){
						                	$vendor_commision=$comsn7;
						                }elseif($comsn8>0){
						                	$vendor_commision=$comsn8;
						                }
								        $this->db->set('amount', $vendor_commision);
								        $this->db->where('seller_id', $source_url_seller_id);
								        $this->db->where('product', $product);
								        $this->db->where('vendor_url', $source_url);
								        $this->db->where('cart_id', $cart_id);
								        $this->db->where('orderID', $orderID);
								        $this->db->where('status', '0');
								        $this->db->update('tbl_commissions');

					                	/*set transaction record*/
								        $this->db->select('id');
					                	$this->db->from('tbl_commissions');
								        $this->db->where('seller_id', $source_url_seller_id);
								        $this->db->where('product', $product);
								        $this->db->where('vendor_url', $source_url);
								        $this->db->where('cart_id', $cart_id);
								        $this->db->where('orderID', $orderID);
								        $this->db->where('amount', $vendor_commision);
								        $this->db->where('status', '0');

								        $sql = $this->db->get();
								        foreach ($sql->result_array() as $rows) {
								        	$commission_id=$rows['id'];
									        $this->db->set('credit', $vendor_commision);
									        $this->db->where('transaction_type', 'commission');
									        $this->db->where('seller_id', $source_url_seller_id);
									        $this->db->where('commission_id', $commission_id);
									        $this->db->where('status', '0');
									        $this->db->update('tbl_transaction_records');
						                }
						                /*end t r*/
					                }
					            }
					        }
				        }
	                }
	            }
	        }
            /**/


	        $comsn1=0; $comsn2=0; $comsn3=0; $comsn4=0;
            if(isReferralValid($referral_url, $product)){
            	$affiliator="";
            	$comsn1=0; $comsn2=0; $comsn3=0; $comsn4=0;
			    $this->db->select('seller_id, seller_type');
		        $this->db->where('product', $product);
		        $this->db->where('referral_url', $referral_url);
		        $sqlCom = $this->db->get('tbl_affiliate_urls');

		        if($sqlCom->num_rows() > 0){
		            foreach ($sqlCom->result_array() as $data) {
		                $affiliator=$data['seller_id'];
		                $affiliatorType=$data['seller_type'];
		                // $comsn1_array=getProductCommission($product);
		                $comsn2_array=getSellerTypeCommission($affiliator, $affiliatorType);
		                $comsn3_array=getAccountTypeCommission($affiliatorType);
		                // $comsn4_array=getFlatCommission();

		                /*if($comsn1_array['amount_percent']>0){
		                	$comsn1=($price*(100-$comsn1_array['amount_percent']))/100;
		                }elseif($comsn1_array['amount_fixed']>0){
		                	$comsn1=$price-$comsn1_array['amount_fixed'];
		                }else*/
		                if(isset($comsn2_array['amount_percent']) && $comsn2_array['amount_percent']>0){
		                	$comsn2=($price*$comsn2_array['amount_percent'])/100;
		                }elseif(isset($comsn2_array['amount_fixed']) && $comsn2_array['amount_fixed']>0){
		                	$comsn2=$comsn2_array['amount_fixed'];
		                }elseif(isset($comsn3_array['amount_percent']) && $comsn3_array['amount_percent']>0){
		                	$comsn3=($price*$comsn3_array['amount_percent'])/100;
		                }elseif(isset($comsn3_array['amount_fixed']) && $comsn3_array['amount_fixed']>0){
		                	$comsn3=$comsn3_array['amount_fixed'];
		                }
		                /*elseif($comsn4_array['amount_percent']>0){
		                	$comsn4=($price*(100-$comsn4_array['amount_percent']))/100;
		                }elseif($comsn4_array['amount_fixed']>0){
		                	$comsn4=$price-$comsn4_array['amount_fixed'];
		                }*/

		                /*if($comsn1>0){
		                	$commission=$comsn1;
		                }else*/
		                if($comsn2>0){
		                	$commission=$comsn2;
		                }elseif($comsn3>0){
		                	$commission=$comsn3;
		                }
		                /*elseif($comsn4>0){
		                	$commission=$comsn4;
		                }*/
		            }
		        }

		        $this->db->set('amount', $commission);
		        $this->db->where('seller_id', $affiliator);
		        $this->db->where('product', $product);
		        $this->db->where('referral_url', $referral_url);
		        $this->db->where('cart_id', $cart_id);
		        $this->db->where('orderID', $orderID);
		        $this->db->where('status', '0');
		        $this->db->update('tbl_commissions');

		        /*set transaction record*/
		        $this->db->select('id');
            	$this->db->from('tbl_commissions');
		        $this->db->where('seller_id', $affiliator);
		        $this->db->where('product', $product);
		        $this->db->where('referral_url', $referral_url);
		        $this->db->where('cart_id', $cart_id);
		        $this->db->where('orderID', $orderID);
		        $this->db->where('status', '0');

		        $sql = $this->db->get();
		        foreach ($sql->result_array() as $rows) {
		        	$commission_id=$rows['id'];
			        $this->db->set('credit', $commission);
			        $this->db->where('transaction_type', 'commission');
			        $this->db->where('seller_id', $affiliator);
			        $this->db->where('commission_id', $commission_id);
			        $this->db->where('referral_url', $referral_url);
			        $this->db->where('cart_id', $cart_id);
			        $this->db->where('orderID', $orderID);
			        $this->db->where('status', '0');
			        $this->db->update('tbl_transaction_records');
                }
                /*end t r*/
		    }

	        $this->db->set('amount', $sellerCommission);
	        $this->db->where('seller_id', $seller_id);
	        $this->db->where('product', $product);
	        $this->db->where('referral_url', '');
	        $this->db->where('cart_id', $cart_id);
	        $this->db->where('orderID', $orderID);
	        $this->db->where('status', '0');
	        $this->db->update('tbl_commissions');

	        // if($this->db->affected_rows() > 0){
	        	/*set transaction record*/
		        $this->db->select('id');
            	$this->db->from('tbl_commissions');
		        $this->db->where('seller_id', $seller_id);
		        $this->db->where('product', $product);
		        $this->db->where('referral_url', '');
		        $this->db->where('cart_id', $cart_id);
		        $this->db->where('orderID', $orderID);
		        $this->db->where('amount', $sellerCommission);
		        $this->db->where('commissionStatus', '5');
		        $this->db->where('status', '0');

		        $sql = $this->db->get();
		        foreach ($sql->result_array() as $rows) {
		        	$commission_id=$rows['id'];
			        $this->db->set('credit', $sellerCommission);
			        $this->db->where('transaction_type', 'commission');
			        $this->db->where('seller_id', $seller_id);
			        $this->db->where('commission_id', $commission_id);
			        $this->db->where('status', '0');
			        $this->db->update('tbl_transaction_records');
                }
                /*end t r*/
	        	return 'Success';
	        /*}else{
	        	return ErrorMsg(json_encode($this->db->error()));
	        }*/
		}

		public function check_order_status($orderID, $userID){
			$status="";
			$cart_total_amount=0;
    		$this->db->select('price, coupon_value');
	        $this->db->where('orderID', $orderID);
	        $this->db->where('user_id', $userID);
	        $this->db->where('status', '0');
	        $query11 = $this->db->get('tbl_cart');
	        if($query11->num_rows() > 0){
	            foreach ($query11->result_array() as $data11) {
	                $cart_total_amount=$cart_total_amount + ((float)$data11['price'] - (float)$data11['coupon_value']);
	            }
	        }
	        
			$this->db->select('provider, trans_token, transid');
	        $this->db->where('orderID', $orderID);
	        $this->db->where('customer', $userID);
	        $this->db->where('status', '0');
	        $query14 = $this->db->get('tbl_transactions');
	        
	       
	        if($query14->num_rows() > 0){
	            foreach ($query14->result_array() as $dt1) {
	             
	            	$provider=$dt1['provider'];
	            	$trans_token=$dt1['trans_token'];
	            	$transid=$dt1['transid'];
	        		if($provider=='selcom'){
	        			$this->load->model('api/Selcom_model');
						$state=$this->Selcom_model->check_order_status($orderID, $userID, $cart_total_amount);
						if($state=='paid'){
							$status='Success';
						}elseif($state=='not paid'){
							$status='Transaction not paid yet';
						}else{
							$status=$state;
						}
	        		}elseif($provider=='vodacom'){
	        			$this->load->model('api/Vpesa_model');
						$state=$this->Vpesa_model->check_order_status($orderID, $userID);
					
						if($state=='paid'){
							$status='Success';
						}elseif($state=='not paid'){
							$status='Transaction not paid yet';
						}else{
							$status=$state." - Transaction not initiated";
						}
	        		}else{
	        			$this->load->model('api/Pay_model');
						$state=$this->Pay_model->check_order_status($orderID, $userID, $trans_token, $cart_total_amount);
						if($state=='paid'){
							$status='Success';
						}elseif($state=='not paid'){
							$status='Transaction not paid yet';
						}else{
							$status=$state;
						}
	        		}
	        	}
	        }
	        
	       	return $status;
		}

    }
?>