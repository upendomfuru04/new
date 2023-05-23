<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Order_model extends CI_Model{

	    public function __construct(){
	        parent::__construct();
	        $this->load->database();
    	}

		public function add_product_cart($userID, $product){
    		$createdDate=time();
    		$ordID=getLatestOrderID($userID);
    		$commission=""; $sellerCommission=""; $seller_id="";
    		$referral_url=""; $price="";
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

    		$this->db->select('price');
	        $this->db->where('id', $product);
	        $this->db->where('status','0');
	        $sqlP = $this->db->get('tbl_products');
	        if($sqlP->num_rows() > 0){
	            foreach ($sqlP->result_array() as $dataP) {
	                $price=$dataP['price'];
	            }
	        }

    		$this->db->select('id');
	        $this->db->where('user_id', $userID);
	        $this->db->where('item_id', $product);
	        $this->db->where('orderID', $orderID);
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_cart');

	        if($query->num_rows() > 0){
	        	return 'exists';
	        	exit();
	        }

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

	        /*Vendor commission*/
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
	        /**/

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
	        	return $this->db->_error_message();
	        }
		}

		function load_cart_items($userID){
        	$listArray=array();
	        $this->db->select('tbl_cart.id, quantity, item_id, tbl_cart.orderID');
	        $this->db->from('tbl_cart');
			$this->db->join('tbl_orders', 'tbl_orders.orderID = tbl_cart.orderID');
	        $this->db->where('tbl_cart.user_id', $userID);
	        $where="tbl_cart.is_complete='2'";
	        $this->db->where($where);
	        $this->db->where('tbl_cart.status', '0');
	        $this->db->where('tbl_orders.status', '0');
	        $query = $this->db->get();

		   	if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	                $id=$data['id'];
	                $item_id=$data['item_id'];
	                $orderID=$data['orderID'];
	                $quantity=$data['quantity'];
	                $this->db->select('name, image, price, product_url');
			        $this->db->where('id', $item_id);
			        $this->db->where('status', '0');
			        $sql = $this->db->get('tbl_products');
			        foreach ($sql->result_array() as $rows) {
			        	$row_array=array();
				        $row_array['cart_id']=$id;
				        $row_array['orderID']=$orderID;
		                $row_array['image']=base_url('media/products/').$rows['image'];
		                $row_array['product_url']=$rows['product_url'];
		                $row_array['name']=$rows['name'];
		                $row_array['price']=$rows['price'];
		                $row_array['quantity']=$quantity;
		                array_push($listArray, $row_array);
		            }
	            }
	        }
	        return $listArray;
	    }

		function load_payment_methods(){
        	$method_list=array();
	        $this->db->select('method_name, code, icon, country_code, country, currency');
	        $this->db->where('status', '0');
	        $sql = $this->db->get('tbl_payment_methods');
	        foreach ($sql->result_array() as $rows1) {
	        	$ls=array();
                $ls['method_name']=$rows1['method_name'];
                $ls['code']=$rows1['code'];
                $ls['country']=$rows1['country'];
                $ls['country_code']=$rows1['country_code'];
                $ls['currency']=$rows1['currency'];
                $ls['icon']=base_url().'assets/themes/payment_method/'.$rows1['icon'];
                array_push($method_list, $ls);
            }
	        return $method_list;
	    }

	    public function remove_cart_item($userID, $cart_id){
			$this->db->set('status', '1');
			$this->db->where('user_id', $userID);
			$this->db->where('id', $cart_id);
	   		$this->db->where('is_complete', '2');
	     	$upd=$this->db->update('tbl_cart');
	     	if($upd){
	     		$this->db->set('status', '1');
				$this->db->where('cart_id', $cart_id);
		   		$this->db->where('commissionStatus', '5');
		     	$this->db->update('tbl_commissions');
			   	return 'Success';
	     	}else{
	     		return "Failed to remove item from the cart....";
	     	}
		}

		public function verify_coupon($coupon){
			$coupon_value="Invalid Coupon";
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

		public function checkout_order($userID, $data){
    		$createdDate=time();    		
    		if($data['payment_method']=='mastercard' || $data['payment_method']=='visa'){
	        	if(!isset($data['phone_number']) || $data['phone_number']==''){
	        		return ('Enter your phone number');
	        		exit();
	        	}
	        	$phone=$data['phone_number'];
	        }else{
	        	if(!isset($data['phone_number']) || $data['phone_number']==''){
	        		return ('Enter your phone number for mobile transactions');
	        		exit();
	        	}
	        	if(!isset($data['country_code']) || $data['country_code']==''){
	        		return ('Please! select phone number operator');
	        		exit();
	        	}
	        	if(isset($data['phone_number']) && $data['phone_number']!=''){
	    			$country_code=ltrim($data['country_code'], '+');
	    			$phone=format_phone_number($data['phone_number']);
	    			$phone=$country_code.''.$phone;
	    		}
	        }
	        $email=$data['email'];
	        $country_code=$data['country_code'];
	        $coupon=$data['coupon'];
	        $payment_provider=$data['payment_method'];
	        $total_amount=$data['total_amount'];
	        $country=$data['country'];
        	//$currency=$data['currency'];
        	$currency="TZS";
	        $customer_name=$data['name'];
	        $name=explode(' ', $customer_name);
    		if(sizeof($name)>=2){
    			$first_name=$name[0];
    			$surname=$name[1];
    		}else{
    			$first_name=$name[0];
    			$surname='';
    		}    		
    		$user_id=$userID;

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
    			return "Total amount does not match with your cart order, please! refresh your page. Found ".$cart_total_amount;
    			exit();
    		}else{
	    		$new_price=0;
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
		            		return ("The coupon expired.");
		            		exit();
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
				     	$this->load->model('home/Homeorder_model');
				     	$update_com=$this->Home_model->update_commission($orderID, $cart_id, $item_id, $new_price, "");
				     	if($update_com!='Success'){
				     		return ($update_com);
				     		exit();
				     	}
		            }
		        }else{
		        	if($coupon!=""){
		        		return ("The coupon is not valid, correct or delete it to continue.");
		        		exit();
		        	}
		        }

		        if($total_amount==""){
		        	return ("An error occur...");
		        	exit();
		        }

		        if($payment_provider=='mastercard' || $payment_provider=='visa'){
		        	$payment_method='Bank';
		        	$payment_type=$payment_method;
		        	$account_number="";
		        }else{
		        	$payment_method='Mobile';
		        	$payment_type=$payment_method;
		        	$account_number=$phone;
		        }

		        $set=array(
		            'first_name' => $first_name,
		            'surname' => $surname,
		            'email' => $email,
		            'phone' => $phone,
		            'amount_paid' => $total_amount,
		            'payment_method' => $payment_provider,
		            'payment_type' => $payment_type,
		            'account_number' => $account_number,
		            'pay_date' => $createdDate
		        );

		        $this->db->where('user_id',$userID);
		        $this->db->where('orderID', $orderID);
		     	$this->db->update('tbl_orders', $set);

		     	$is_success="";
		     	if($payment_provider=='mpesa' || $payment_provider=='airtelmoney' || $payment_provider=='tigopesa' || $payment_provider=='halopesa' || $payment_provider=='ezypesa' || $payment_provider=='ttclpesa'){
		     		/*Selcom api for mobile payment*/
     				$this->load->model('api/Selcom_model');
     				$send=$this->Selcom_model->create_mobile_order($orderID, $user_id, $email, $customer_name, $phone, $total_amount, 'TZS', $no_of_items, $payment_provider);
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
		     	}elseif($payment_provider=='mastercard' || $payment_provider=='visa' || $payment_provider=='amex'){
		     		/*Selcom api for bank payment*/
     				$this->load->model('api/Selcom_model');
     				$send=$this->Selcom_model->create_bank_order($orderID, $user_id, $email, $customer_name, $first_name, $surname, $phone, $total_amount, 'TZS', $no_of_items, $payment_provider);
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
		        	if($country=='kenya'){
		        		$customerCountry="KE";
		        	}else if($country=='uganda'){
		        		$customerCountry="UG";
		        	}else if($country=='rwanda'){
		        		$customerCountry="RW";
		        	}else if($country=='ghana'){
		        		$customerCountry="GH";
		        	}else if($country=='ivory_cost'){
		        		$customerCountry="IC";
		        	}
		        	$customerPhone=$phone;
		        	$customerZip="";
		        	$service_description='Product Order '.$orderID;
		        	$service_type="18683"; /*default for all services*/

		        	$redirectURL=base_url('customer/payment_success/'.$orderID);
		        	$backUrl=base_url('customer/orders');
		        	$pay=$this->Pay_model->directPay_createToken_mobile($total_amount, $currency, $redirectURL, $backUrl, $customerEmail, $customerFirstName, $customerLastName, $customerAddress, $customerDialCode, $customerCity, $customerCountry, $customerZip, $customerPhone, $service_type, $service_description, $service_date, $userID, $orderID, $p_update);
		        	if($pay=='Okay'){
		        		$trans_token="";
		        		$MNOcountry=$country;
		        		$MNO="";
		        		if($payment_provider=='safaricom'){
			        		$MNO="mpesa";
			        	}else if($payment_provider=='airtelmoney_kenya'){
			        		$MNO="airtel";
			        	}else if($payment_provider=='mtn_uganda'){
			        		$MNO="MTNmobilemoney";
			        	}else if($payment_provider=='mtn_rwanda'){
			        		$MNO="MTN";
			        	}else if($payment_provider=='mtn_ghana'){
			        		$MNO="MTN";
			        	}else if($payment_provider=='mtn_ivory'){
			        		$MNO="MTN";
			        	}else if($payment_provider=='orange_ivory'){
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
				        	return $instr['status'];
				        }
		        	}else{
		        		return ($pay);
		        		exit();
		        	}
		     	}
    		}
		}

		public function check_order_status($userID, $orderID){
			$status="";
			$cart_total_amount=0;
    		$this->db->select('price, coupon_value');
	        $this->db->where('orderID', $orderID);
	        $this->db->where('user_id', $userID);
	        $this->db->where('status', '0');
	        $query11 = $this->db->get('tbl_cart');
	        if($query11->num_rows() > 0){
	            foreach ($query11->result_array() as $data11) {
	                $cart_total_amount=$cart_total_amount + ($data11['price'] - $data11['coupon_value']);
	            }
	        }

			$this->db->select('provider, trans_token');
	        $this->db->where('orderID', $orderID);
	        $this->db->where('customer', $userID);
	        $this->db->where('status', '0');
	        $query14 = $this->db->get('tbl_transactions');
	        if($query14->num_rows() > 0){
	            foreach ($query14->result_array() as $dt1) {
	            	$provider=$dt1['provider'];
	            	$trans_token=$dt1['trans_token'];
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

		function load_user_orders($userID){
        	$result=array();
	        $this->db->select('id, orderID, is_complete, pay_date');
		   	$this->db->where('user_id', $userID);
		   	// $this->db->where('is_complete !=', '2');
		   	$this->db->where('status', '0');
		   	$this->db->order_by('createdDate', 'DESC');		   	
	   		$query = $this->db->get('tbl_orders');
	        foreach ($query->result_array() as $rows) {
	        	$ls=array();
	        	$order_status="";
	        	if($rows['is_complete']=='0'){
                    $order_status='Complete';
                }elseif($rows['is_complete']=='1'){
                    $order_status='Pending';
                }elseif($rows['is_complete']=='2'){
                    $order_status='Not Paid';
                }elseif($rows['is_complete']=='4'){
                    $order_status='Refunded';
                }else{
                    $order_status='Unknown';
                }
                $pay_date=$rows['pay_date'];
                $orderID=$rows['orderID'];
                $show_action=false;
                if(strtolower(getOrderProductStatus($orderID))=='live'){
                    if(timeDiffInDays($pay_date, time()) < getRefundPeriod()){
                        if($rows['is_complete']!='4'){
                            $show_action=true;
                        }
                    }
                }else{
                    if($rows['is_complete']!='4'){
                        $show_action=true;
                    }
                }
                $ls['orderID']=$rows['orderID'];
                $ls['total_cost']=number_format(getOrderTotalCost($rows['orderID']))." TZS";
                $ls['total_items']=getOrderCartCounter($rows['orderID']);
                $ls['order_status']=$order_status;
                $ls['show_action']=$show_action;
                array_push($result, $ls);
            }
	        return $result;
	    }

		function load_order_items($userID, $orderID){
        	$result=array();
	        $this->db->select('tbl_products.id as product_id, item_id, name, image, tbl_cart.orderID, tbl_cart.price, tbl_cart.quantity, tbl_cart.createdDate, tbl_orders.is_complete, pay_date');
			$this->db->from('tbl_cart');
			$this->db->join('tbl_products', 'tbl_products.id = item_id');
			$this->db->join('tbl_orders', 'tbl_orders.orderID = tbl_cart.orderID');
			$this->db->where('tbl_cart.user_id', $userID);
			$this->db->where('tbl_cart.orderID', $orderID);
		   	$this->db->where('tbl_products.status', '0');
		   	$this->db->where('tbl_cart.status', '0');
		   	$this->db->order_by('createdDate', 'DESC');
		   	$query = $this->db->get();
	        foreach ($query->result_array() as $rows) {
	        	$ls=array();
                $ls['product_id']=$rows['product_id'];
                $ls['product_name']=$rows['name'];
                $ls['image']=base_url('media/products/').$rows['image'];
                $ls['price']=number_format($rows['price'])." TZS";
                $ls['quantity']=$rows['quantity'];
                $ls['total_price']=number_format($rows['quantity']*$rows['price'])." TZS";
                array_push($result, $ls);
            }
	        return $result;
	    }

	    public function save_request_refund($userID, $data){
    		$createdDate=time();
    		$product=$data['product'];
    		$orderID=$data['orderID'];
    		$description=$data['reason'];
    		$amount_paid="";
    		if($product!=""){
    			$amount_paid=getItemAmountPaid($orderID, $product);
    		}else{
    			$amount_paid=getOrderAmountPaid($orderID);
    		}
	        $this->db->select('id');
	        $this->db->where('customer', $userID);
	        $this->db->where('orderID', $orderID);
	        // $this->db->where('is_processed', '1');
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_refunds');

	        if($query->num_rows() > 0){
	            return ("This request (with orderID: ".$orderID.") already set");
	            exit();
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
	        	return ("You exceeded the refund deadline...");
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
		        	return $this->db->_error_message();
		        }
	        }
		}

		function load_user_refunds($userID){
        	$result=array();
	        $this->db->select('id, orderID, amount, is_processed, status, customer_note, createdDate');
		   	$this->db->where('customer', $userID);
		   	$this->db->where('status', '0');
		   	$this->db->order_by('createdDate', 'DESC');		   	
	   		$query = $this->db->get('tbl_refunds');
	        foreach ($query->result_array() as $rows) {
	        	$ls=array();
	        	$refund_status="";
	        	$show_action=false;
	        	if($rows['is_processed']=='0'){ 
	        		$refund_status='Processed';
	        	}else{ 
	        		$refund_status='Pending';
	        	}
	        	if($rows['is_processed']!='0'){
	        		$show_action=true;
	        	}
                $ls['refund_id']=$rows['id'];
                $ls['orderID']=$rows['orderID'];
                $ls['amount']=number_format($rows['amount'])." TZS";
                $ls['note']=ucfirst($rows['customer_note']);
                $ls['refund_status']=$refund_status;
                $ls['show_action']=$show_action;
                array_push($result, $ls);
            }
	        return $result;
	    }

	    public function remove_refund_request($userID, $orderID, $request_id){
			$this->db->set('status', '1');
			$this->db->where('customer', $userID);
			$this->db->where('orderID', $orderID);
			$this->db->where('id', $request_id);
	   		$this->db->where('is_processed', '1');
	     	$set=$this->db->update('tbl_refunds');
	     	if($set){
	     		return "Success";
	     	}else{
	     		return "Failed to delete refund request";
	     	}
		}

	}
?>