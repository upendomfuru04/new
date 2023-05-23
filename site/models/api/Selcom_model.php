<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	date_default_timezone_set('Africa/Dar_es_Salaam');

	class Selcom_model extends CI_Model{

	    public function __construct(){
	        parent::__construct();
	        $this->load->database();
	        /*API variables*/
	        $this->vendorID= 'TILL60485398';
	        $this->api_key = 'GVALUE-8Ylj0rPnRhVUkUSd';
			$this->api_secret = '43236655-9J21-4J13-aT85-a78c1b154432';
			$this->pincode = '8254';
			$this->base_url = "https://apigwtest.selcommobile.com/v1";
			$this->authorization = base64_encode($this->api_key);
    	}

    	public function api($endpoint, $request){
			// $api_endpoint = "/utilitypayment/process";
			$api_endpoint = $endpoint;
			$url = $this->base_url.$api_endpoint;
			$isPost =1;
			$req = $request;
			$timestamp = date('c'); //2019-02-26T09:30:46+03:00
    		$signed_fields  = implode(',', array_keys($req));
			$digest = $this->computeSignature($req, $signed_fields, $timestamp, $this->api_secret);
			$response = $this->sendJSONPost($url, $isPost, json_encode($req), $this->authorization, $digest, $signed_fields, $timestamp);
			// return $digest.' = '.$response;
			return $response;
    	}

    	public function disbursement($transid, $utilitycode, $phone, $amount){
			// $api_endpoint = "/utilitypayment/process";
			$api_endpoint = "/walletcashin/process";
			// $api_endpoint = "/wallet/pushussd";
			$url = $this->base_url.$api_endpoint;
			$isPost =1;
			$utilityref=$phone;
			// $req = array("utilityref"=>"12345", "transid"=>"transid", "amount"=>"1000");
			//$req = array("transid"=>"dtid".getRandomNumber(4), "utilitycode"=>"VMCASHIN", "utilityref"=>"0752844733", "amount"=>"1000", "vendor"=>$this->vendorID, "pin"=>$this->pincode, "msisdn"=>"255752844733");
			$req = array("transid"=>"dtid".$transid, "utilitycode"=>$utilitycode, "utilityref"=>$utilityref, "amount"=>$amount, "vendor"=>$this->vendorID, "pin"=>$this->pincode, "msisdn"=>$phone);
			// $req = array("utilityref"=>"12345", "transid"=>"transid", "amount"=>"1000", "vendor"=>"TILL60485398", "msisdn"=>"255752844733");
			$timestamp = date('c'); //2019-02-26T09:30:46+03:00
    		$signed_fields  = implode(',', array_keys($req));
			$digest = $this->computeSignature($req, $signed_fields, $timestamp, $this->api_secret);

			$response = $this->sendJSONPost($url, $isPost, json_encode($req), $this->authorization, $digest, $signed_fields, $timestamp);
			// return $digest.' = '.$response;
			return $response;
    	}

    	/*CHECKOUT ENDPOINTS*/
    	public function create_order($orderID, $email, $name, $phone, $amount, $currency, $payment_methods, $redirect_url, $cancel_url, $no_of_items, $firstname, $surname, $address_1, $city, $state, $postcode, $country){
			$api_endpoint = "/checkout/create-order";
			$req = array("vendor"=>$this->vendorID, "order_id"=>$orderID, "buyer_email"=>$email, "buyer_name"=>$name, "buyer_userid"=>"", "buyer_phone"=>$phone, "gateway_buyer_uuid"=>"", "amount"=>$amount, "currency"=>$currency, "payment_methods"=>$payment_methods, "redirect_url"=>$redirect_url, "cancel_url"=>$cancel_url, "webhook"=>"", "billing.firstname"=>$firstname, "billing.lastname"=>$surname, "billing.address_1"=>$address_1, "billing.address_2"=>'', "billing.city"=>$city, "billing.state_or_region"=>$state, "billing.postcode_or_pobox"=>$postcode, "billing.country"=>$country, "billing.phone"=>$phone, "shipping.firstname"=>'', "shipping.lastname"=>'', "shipping.address_2"=>'', "shipping.city"=>'', "shipping.state_or_region"=>'', "shipping.postcode_or_pobox"=>'', "shipping.phone"=>'', "buyer_remarks"=>'', "merchant_remarks"=>'', "no_of_items"=>$no_of_items, "header_colour"=>"#D9A623", "link_colour"=>'#D9A623', "button_colour"=>'#D9A623');
			$response = $this->api($api_endpoint, $req);
			return $response;
    	}

    	/*for mobile trans.*/
    	public function create_order_minimum($orderID, $email, $name, $phone, $amount, $currency, $redirect_url, $cancel_url, $no_of_items){
			$api_endpoint = "/checkout/create-order-minimal";
			$req = array("vendor"=>$this->vendorID, "order_id"=>$orderID, "buyer_email"=>$email, "buyer_name"=>$name, "buyer_phone"=>$phone, "amount"=>$amount, "currency"=>$currency, "redirect_url"=>$redirect_url, "cancel_url"=>$cancel_url, "webhook"=>"", "buyer_remarks"=>'', "merchant_remarks"=>'', "no_of_items"=>$no_of_items, "header_colour"=>"#D9A623", "link_colour"=>'#D9A623', "button_colour"=>'#D9A623', "expiry"=>'');
			$response = $this->api($api_endpoint, $req);
			return $response;
    	}

    	public function cancel_order($orderID){
    		$timestamp = date('c');			
			$req = array("order_id"=>$orderID);
    		$signed_fields  = implode(',', array_keys($req));
			$digest = $this->computeSignature($req, $signed_fields, $timestamp, $this->api_secret);
			$url="https://apigwtest.selcommobile.com/v1/checkout/cancel-order?order_id=".$orderID;
			$headers = array(
		      "Content-type: application/json;",
		      "Authorization: SELCOM $this->authorization",
		      "Digest-Method: HS256",
		      "Digest: $digest",
		      "Timestamp: $timestamp",
		      "Signed-Fields: $signed_fields",
		    );
            $ch=curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
		    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $res=curl_exec($ch);
            curl_close($ch);
            $data = json_decode($res, true);
        	return $data;
    	}

    	public function get_order_status($orderID){
    		$api_endpoint = "https://apigwtest.selcommobile.com/v1/checkout/order-status?order_id=".$orderID;
			$url = $api_endpoint;
			$isPost =0;
			$req = array("order_id"=>$orderID);
			$timestamp = date('c');
    		$signed_fields  = implode(',', array_keys($req));
			$digest = $this->computeSignature($req, $signed_fields, $timestamp, $this->api_secret);
			$response = $this->sendJSONPost($url, $isPost, json_encode($req), $this->authorization, $digest, $signed_fields, $timestamp);
			return $response;
    	}

    	public function list_all_orders($fromdate, $todate){
    		$api_endpoint = "/checkout/list-orders?fromdate=".$fromdate."&todate=".$todate;
			$req = array();
			$response = $this->api($api_endpoint, $req);
			return $response;
    	}

    	public function process_order_card_payment($phone, $orderID){
			$api_endpoint = "/checkout/card-payment";
			$url = $this->base_url.$api_endpoint;
			$isPost =1;
			$req = array("transid"=>"tid".$orderID, "vendor"=>$this->vendorID, "order_id"=>$orderID, "card_token"=>$card_token, "buyer_userid"=>$buyer_userid, "gateway_buyer_uuid"=>$gateway_buyer_uuid);
			$timestamp = date('c'); //2019-02-26T09:30:46+03:00
    		$signed_fields  = implode(',', array_keys($req));
			$digest = $this->computeSignature($req, $signed_fields, $timestamp, $this->api_secret);
			$response = $this->sendJSONPost($url, $isPost, json_encode($req), $this->authorization, $digest, $signed_fields, $timestamp);
			return $response;
    	}

    	public function process_order_wallet_pull_payment($phone, $orderID, $transid){
			$api_endpoint = "/checkout/wallet-payment";
			$url = $this->base_url.$api_endpoint;
			$isPost =1;
			$req = array("transid"=>"tid".$transid, "order_id"=>$orderID, "msisdn"=>$phone);
			$timestamp = date('c'); //2019-02-26T09:30:46+03:00
    		$signed_fields  = implode(',', array_keys($req));
			$digest = $this->computeSignature($req, $signed_fields, $timestamp, $this->api_secret);
			$response = $this->sendJSONPost($url, $isPost, json_encode($req), $this->authorization, $digest, $signed_fields, $timestamp);
			return $response;
    	}

    	public function payment_refund($transid, $original_transid, $amount){
    		$api_endpoint = "/checkout/refund-payment";
			$url = $this->base_url.$api_endpoint;
			$isPost =1;
			$req = array("transid"=>$transid, "original_transid"=>$original_transid, "amount"=>$amount);
			$timestamp = date('c'); //2019-02-26T09:30:46+03:00
    		$signed_fields  = implode(',', array_keys($req));
			$digest = $this->computeSignature($req, $signed_fields, $timestamp, $this->api_secret);
			$response = $this->sendJSONPost($url, $isPost, json_encode($req), $this->authorization, $digest, $signed_fields, $timestamp);
			return $response;
    	}
    	/*END OF CHECKOUT*/

    	function sendJSONPost($url, $isPost, $json, $authorization, $digest, $signed_fields, $timestamp) {
    	   //   echo json_encode($url);
		      //exit;
		    $headers = array(
		      "Content-type: application/json;charset=\"utf-8\"", "Accept: application/json", "Cache-Control: no-cache",
		      "Authorization: SELCOM $authorization",
		      "Digest-Method: HS256",
		      "Digest: $digest",
		      "Timestamp: $timestamp",
		      "Signed-Fields: $signed_fields",
		    );
		    $ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, $url);
		    if($isPost){
		      curl_setopt($ch, CURLOPT_POST, 1);
		      curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		    }
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		    curl_setopt($ch,CURLOPT_TIMEOUT,90);
		    $result = curl_exec($ch);
		    curl_close($ch);
		    $resp = json_decode($result, true);
		    return $resp;
	 	}

	 	function computeSignature($parameters, $signed_fields, $request_timestamp, $api_secret){
		    $fields_order = explode(',', $signed_fields);
		    $sign_data = "timestamp=$request_timestamp";
		    foreach ($fields_order as $key) {
		      $sign_data .= "&$key=".$parameters[$key];
		    }

		    //RS256 Signature Method
		    #$private_key_pem = openssl_get_privatekey(file_get_contents("path_to_private_key_file"));
		    #openssl_sign($sign_data, $signature, $private_key_pem, OPENSSL_ALGO_SHA256);
		    #return base64_encode($signature);

		    //HS256 Signature Method
		    return base64_encode(hash_hmac('sha256', $sign_data, $api_secret, true));
		}

		function getRandomNumber($limit){
			$rands=rand(1, 9);
			if($limit == 0){
				return 0;
			}else if($limit == 1){
				return $limit;
			}else if($limit == 2){
				$rands.=rand(1, 9);
				return $rands;
			}else if($limit == 3){
				$rands.=rand(0, 9).rand(1, 9);
				return $rands;
			}else if($limit == 4){
				$rands.=rand(0, 9).rand(0, 9).rand(1, 9);
				return $rands;
			}else if($limit > 4){
				for ($i=0; $i < ($limit - 2); $i++) { 
					$rands.=rand(0, 9);
				}
				$rands.=rand(1, 9);
				return $rands;
			}
		}

    	public function response($code){
			switch ($code) {
				case '000':
					return 'SUCCESS';
					// return 'Transaction successful';
					break;
				case '111,927':
					return 'IN PROGRESS';
					// return 'Transaction in progress please query to know the exact status of the transaction';
					break;
				case '999':
					return 'AMBIGOUS';
					// return 'Transactions status unknown wait for recon';
					break;
				case '400':
					return 'Bad Request -- Your request is invalid.';
					break;
				case '401':
					return 'Unauthorized -- Your Authorization key is wrong or Missing Authorization, Digest, Timestamp HTTP Headers.';
					break;
				case '403':
					return 'Forbidden -- Endpoint or resource is restricted from access.';
					break;
				case '404':
					return 'Not Found -- Endpoint not found or resource requested not found';
					break;
				case '405':
					return 'Method Not Allowed -- You tried to access Selcom API with an invalid HTTP method.';
					break;
				case '406':
					return 'Not Acceptable -- You requested a format that isn`t json.';
					break;
				case '412':
					return 'Prevalidation Failure. Missing mandatory parameters or Unexpected parameter in the payload';
					break;
				case '415':
					return 'Invalid Media type';
					break;
				case '500':
					return 'Internal Server Error -- We had a problem with our server or transaction failure.';
					break;
				case '503':
					return 'Service Unavailable -- We`re temporarily offline for maintenance. Please try again later.';
					break;
				case '200':
					return 'Transaction successful or in progress status';
					break;
				
				default:
					return 'FAIL';
					// return 'Transaction failed, Code failed!';
					break;
			}
		}

		function getUtilitycode($payment_type){
			$utilitycode="";
			if($payment_type=='mpesa'){
				$utilitycode="VMCASHIN";
			}elseif($payment_type=='airtelmoney'){
				$utilitycode="AMCASHIN";
			}elseif($payment_type=='tigopesa'){
				$utilitycode="TPCASHIN";
			}elseif($payment_type=='halopesa'){
				$utilitycode="HPCASHIN";
			}elseif($payment_type=='ezypesa'){
				$utilitycode="EZCASHIN";
			}elseif($payment_type=='ttclpesa'){
				$utilitycode="TTCASHIN";
			}elseif($payment_type=='selcom'){
				$utilitycode="SPCASHIN";
			}
			return $utilitycode;
		}

		/*System implementation*/

		function check_order_status($orderID, $userID, $amount){
			$status="";
			$get_status=$this->get_order_status($orderID);
			
			if($get_status['resultcode']!='000'){
				$status=$get_status['message'];
			}else{
				if($get_status['data'][0]['payment_status']=='COMPLETED'){
					if($amount>$get_status['data'][0]['amount']){
						$status="Paid amount is lower than total order amount which is ".$amount;
					}else{
						$this->db->select('id, orderID');
						$this->db->from('tbl_transactions');
						$this->db->where('customer', $userID);
						$this->db->where('orderID', $orderID);
						$this->db->where('amount', $amount);
					   	// $this->db->where('provider', 'selcom');
					   	// $this->db->where('is_complete', '2');
					   	$this->db->where('status', '0');
					   	
				   		$query = $this->db->get();	
				   	
					   	if($query->num_rows() > 0){				   			
			                $this->db->set('is_complete', '0');
							$this->db->where('customer', $userID);
							$this->db->where('orderID', $orderID);
				   			$this->db->where('provider', 'selcom');
					        $this->db->where('status', '0');
					     	$this->db->update('tbl_transactions');

			                $this->db->set('is_complete', '0');
			                $this->db->where('user_id',$userID);
					        $this->db->where('orderID', $orderID);
					        $this->db->where('status', '0');
					     	$this->db->update('tbl_orders');

			                $this->db->set('is_complete', '0');
			                $this->db->where('user_id',$userID);
					        $this->db->where('orderID', $orderID);
					        $this->db->where('status', '0');
					     	$this->db->update('tbl_cart');

			                $this->db->set('commissionStatus', '0');
					        $this->db->where('orderID', $orderID);
					        $this->db->where('status', '0');
					     	$this->db->update('tbl_commissions');

					     	/*set transaction record*/
					     	$this->db->select('id');
							$this->db->from('tbl_commissions');
							$this->db->where('orderID', $orderID);
						   	$this->db->where('status', '0');		   	
					   		$sql = $this->db->get();		   	
						   	if($sql->num_rows() > 0){
						   		foreach ($sql->result_array() as $rows){
					                $commission_id=$rows['id'];
					                $this->db->set('is_complete', '0');
							     	$this->db->where('status', '0');
							        $this->db->where('commission_id', $commission_id);
							        $this->db->where('transaction_type', 'commission');
							     	$this->db->update('tbl_transaction_records');
					            }
						   	}
					        /*$this->db->select('tbl_commissions.id as commission_id, tbl_transactions.id as transaction_id');
			            	$this->db->from('tbl_commissions');
					        $this->db->join('tbl_transactions', 'tbl_transactions.orderID=tbl_commissions.orderID AND customer="'.$userID.'" AND tbl_transactions.status="0"');
					        $this->db->where('tbl_commissions.orderID', $orderID);
					        $this->db->where('tbl_commissions.status', '0');
					        $sql = $this->db->get();
					        foreach ($sql->result_array() as $rows) {
					        	$commission_id=$rows['commission_id'];
					        	$transaction_id=$rows['transaction_id'];
				                $this->db->set('is_complete', '0');
				                $this->db->set('transaction_id', $transaction_id);
				                $this->db->where('commission_id', $commission_id);
					        	$this->db->where('status', '0');
						        $this->db->where('credit>0');
						     	$this->db->update('tbl_transaction_records');
			                }*/
			                /*end t r*/

					     	$status="paid";
					   	}else{
					   		$status="not paid";
					   	}
					}
			   	}else{
			   		$status='Payment Status: '.$get_status['data'][0]['payment_status'];
			   	}
			}
			return $status;
		}

		function create_mobile_order($orderID, $userID, $email, $name, $phone, $amount, $currency, $no_of_items, $payment_method){
			$res=array();
		
			$check_pay=$this->check_order_status($orderID, $userID, $amount);
			
			if($check_pay!='paid'){
				$error=false; $msg=""; $url=""; $instruction=""; $transid="";  $payment_token="";
				$this->db->select('selcom_transid');
		        $this->db->where('orderID', $orderID);
		        $this->db->where('user_id', $userID);
		        $this->db->where('status', '0');
		        $query13 = $this->db->get('tbl_orders');
		        if($query13->num_rows() > 0){
		        	foreach ($query13->result_array() as $dt) {
		        		$transid=$dt['selcom_transid'];
		        	}
		        }

		        if($transid==""){
		        	$transid=getRandomNumber(4).''.$orderID;
					$this->db->set('selcom_transid', $transid);
					$this->db->where('orderID', $orderID);
					$this->db->where('status', '0');
			     	$this->db->update('tbl_orders');
		        }

		        $continue=true;
		        $this->db->select('amount');
		        $this->db->where('orderID', $orderID);
		        $this->db->where('customer', $userID);
		        // $this->db->where('provider', 'selcom');
		        $this->db->where('status', '0');
		        $query14 = $this->db->get('tbl_transactions');
		        if($query14->num_rows() > 0){
		            foreach ($query14->result_array() as $dt1) {
		        		// if($amount!=$dt1['amount']){
		        			$result=$this->cancel_order($orderID);
	        				// die(var_dump($result));
							if($result['resultcode']!='000' && $result['resultcode']!='400'){
								$status=$result['message'];
								$continue=false;
							}else{
								// echo 'order canceled';
								$orderID=$this->update_orderID($orderID, $userID);
								$transid=getRandomNumber(4).''.$orderID;
							}
		        		// }
		        	}
		        }

		        if($continue){
			     	$dec_transid=base64_encode($transid);
					$redirect_url=base64_encode(base_url().'customer/payment_success/'.$orderID);
					$cancel_url=base64_encode(base_url());
					$create_order=$this->create_order_minimum($orderID, $email, $name, $phone, $amount, $currency, $redirect_url, $cancel_url, $no_of_items);
					if($create_order['resultcode']!='000' && $create_order['resultcode']!='400'){
						$msg=ErrorMsg($create_order['message']);
						$error=true;
					}else{
						// var_dump($create_order); die();
						$payment_gateway_url="";
						if(isset($create_order['data'][0]['payment_gateway_url']) && $create_order['data'][0]['payment_gateway_url']!=""){
							$payment_gateway_url=base64_decode($create_order['data'][0]['payment_gateway_url']);
							$payment_token=$create_order['data'][0]['payment_token'];
							$set=array(
					            'payment_gateway_url' => $payment_gateway_url,
					            'payment_token' => $payment_token
					        );
					        $this->db->where('orderID', $orderID);
					     	$this->db->update('tbl_orders', $set);
						}else{
							$this->db->select('payment_gateway_url, payment_token');
					        $this->db->where('orderID', $orderID);
					        $this->db->where('status', '0');
					        $query11 = $this->db->get('tbl_orders');
					        if($query11->num_rows() > 0){
					            foreach ($query11->result_array() as $data11) {
					                $payment_gateway_url=$data11['payment_gateway_url'];
					                $payment_token=$data11['payment_token'];
					            }
					        }
						}
						$url=$payment_gateway_url;
						//if($payment_method=='airtelmoney' || $payment_method=='tigopesa' || $payment_method=='mpesa'){
						if($payment_method=='airtelmoney' || $payment_method=='tigopesa'){
							$ussd=$this->process_order_wallet_pull_payment($phone, $orderID, $transid);
							
				// 			echo '************USSD**********';
				// 			echo json_encode($ussd);
				// 			echo '************USSD**********';
							
							 //var_dump($ussd); die();
							if($ussd['resultcode']=='111' || $ussd['resultcode']=='000'){
								$msg='Request in progress. You will receive a callback shortly to the phone number you provided, make sure it is available and phone is active. Click resend btn if callback takes more than 2 mins';
								$error=false;
							}else{
								$msg=$ussd['message'];
								$error=true;
							}
						}else{					
							$msg='Success';
							$error=false;
						}

						$this->db->select('id');
				        $this->db->where('orderID', $orderID);
				        $this->db->where('customer', $userID);
				        // $this->db->where('provider', 'selcom');
				        $this->db->where('status', '0');
				        $query12 = $this->db->get('tbl_transactions');
				        if($query12->num_rows() > 0){
				        	$set2=array();
			        		$set2['amount']=$amount;
			        		$set2['provider']='selcom';
				        	if($payment_token!=""){
				        		$set2['payment_token']=$payment_token;
				        	}
				        	if($payment_gateway_url!=""){
				        		$set2['payment_gateway_url']=$payment_gateway_url;
				        	}

					        $this->db->where('status', '0');
					        // $this->db->where('is_complete', '2');
					        $this->db->where('customer',$userID);
					        $this->db->where('orderID', $orderID);
					        // $this->db->where('provider', 'selcom');
					     	$this->db->update('tbl_transactions', $set2);
				        }else{
				        	$this->db->insert('tbl_transactions', array(
					            'customer' => $userID,
					            'orderID' => $orderID,
					            'provider' => 'selcom',
					            'amount' => $amount,
					            'payment_token' => $payment_token,
					            'payment_gateway_url' => $payment_gateway_url,
					            'is_complete' => '2',
					            'status' => '0',
					            'createdBy' => $userID,
					            'transactionDate' => date('Y/m/d H:i:s')
					        ));
				        }
					}
				}
				$instruction=$this->get_mobilemoney_menu($payment_method, $payment_token, "123123", $amount);
				$res['url']=$url;
				$res['instruction']=$instruction;
				$res['error']=$error;
				$res['msg']=$msg;
				return $res;
			}else{
				return 'paid';
			}
		}

		function create_bank_order($orderID, $userID, $email, $name, $firstname, $surname, $phone, $address="23, street Ubungo", $city_state_region="Dar es salaam", $postcode="43434", $country="TZ", $amount, $currency, $no_of_items, $payment_method){
			$res=array();
			$check_pay=$this->check_order_status($orderID, $userID, $amount);
			if($check_pay!='paid'){
				$error=false; $msg=""; $url=""; $instruction=""; $transid="";  $payment_token="";
				$this->db->select('selcom_transid');
		        $this->db->where('orderID', $orderID);
		        $this->db->where('user_id', $userID);
		        $this->db->where('status', '0');
		        $query13 = $this->db->get('tbl_orders');
		        if($query13->num_rows() > 0){
		        	foreach ($query13->result_array() as $dt) {
		        		$transid=$dt['selcom_transid'];
		        	}
		        }

		        if($transid==""){
		        	$transid=getRandomNumber(4).''.$orderID;
					$this->db->set('selcom_transid', $transid);
					$this->db->where('orderID', $orderID);
					$this->db->where('status', '0');
			     	$this->db->update('tbl_orders');
		        }

		        $continue=true;
		        $this->db->select('amount');
		        $this->db->where('orderID', $orderID);
		        $this->db->where('customer', $userID);
		        // $this->db->where('provider', 'selcom');
		        $this->db->where('status', '0');
		        $query14 = $this->db->get('tbl_transactions');
		        if($query14->num_rows() > 0){
		            foreach ($query14->result_array() as $dt1) {
		            	$result=$this->cancel_order($orderID);
		        		if($amount!=$dt1['amount']){
							if($result['resultcode']!='000' && $result['resultcode']!='400'){
								$status=$result['message'];
								$continue=false;
							}else{
								// echo 'order canceled';
								$orderID=$this->update_orderID($orderID, $userID);
								$transid=getRandomNumber(4).''.$orderID;
							}
		        		}
		        	}
		        }

		        if($continue){
			     	$dec_transid=base64_encode($transid);
					$redirect_url=base64_encode(base_url().'customer/payment_success/'.$orderID);
					$cancel_url=base64_encode(base_url());
					$create_order=$this->create_order($orderID, $email, $name, $phone, $amount, $currency, "CARD", $redirect_url, $cancel_url, $no_of_items, $firstname, $surname, $address, $city_state_region, $city_state_region, $postcode, $country);
					if($create_order['resultcode']!='000' && $create_order['resultcode']!='400'){
						$msg=ErrorMsg($create_order['message']);
						$error=true;
					}else{
						$payment_gateway_url="";
						if(isset($create_order['data'][0]['payment_gateway_url']) && $create_order['data'][0]['payment_gateway_url']!=""){
							$payment_gateway_url=base64_decode($create_order['data'][0]['payment_gateway_url']);
							$payment_token=$create_order['data'][0]['payment_token'];
							$set=array(
					            'payment_gateway_url' => $payment_gateway_url,
					            'payment_token' => $payment_token
					        );
					        $this->db->where('orderID', $orderID);
					     	$this->db->update('tbl_orders', $set);
						}else{
							$this->db->select('payment_gateway_url, payment_token');
					        $this->db->where('orderID', $orderID);
					        $this->db->where('status', '0');
					        $query11 = $this->db->get('tbl_orders');
					        if($query11->num_rows() > 0){
					            foreach ($query11->result_array() as $data11) {
					                $payment_gateway_url=$data11['payment_gateway_url'];
					                $payment_token=$data11['payment_token'];
					            }
					        }
						}
						$url=$payment_gateway_url;				
						$msg='Success';
						$error=false;

						$this->db->select('id');
				        $this->db->where('orderID', $orderID);
				        $this->db->where('customer', $userID);
				        // $this->db->where('provider', 'selcom');
				        $this->db->where('status', '0');
				        $query12 = $this->db->get('tbl_transactions');
				        if($query12->num_rows() > 0){
				        	$set2=array();
			        		$set2['amount']=$amount;
			        		$set2['provider']='selcom';
				        	if($payment_token!=""){
				        		$set2['payment_token']=$payment_token;
				        	}
				        	if($payment_gateway_url!=""){
				        		$set2['payment_gateway_url']=$payment_gateway_url;
				        	}

					        $this->db->where('status', '0');
					        // $this->db->where('is_complete', '2');
					        $this->db->where('customer',$userID);
					        $this->db->where('orderID', $orderID);
					        // $this->db->where('provider', 'selcom');
					     	$this->db->update('tbl_transactions', $set2);
				        }else{
				        	$this->db->insert('tbl_transactions', array(
					            'customer' => $userID,
					            'orderID' => $orderID,
					            'provider' => 'selcom',
					            'amount' => $amount,
					            'payment_token' => $payment_token,
					            'payment_gateway_url' => $payment_gateway_url,
					            'is_complete' => '2',
					            'status' => '0',
					            'createdBy' => $userID,
					            'transactionDate' => date('Y/m/d H:i:s')
					        ));
				        }
					}
				}
				// $instruction=$this->get_mobilemoney_menu($payment_method, $payment_token, "123123");
				$res['url']=$url;
				$res['instruction']=$instruction;
				$res['error']=$error;
				$res['msg']=$msg;
				return $res;
			}else{
				return 'paid';
			}
		}

		function push_disbursement($orderID, $refundID, $withdrawID, $payment_type, $phone, $amount, $transactionType, $userID, $user_type){
			$transid=""; $new_transid="";
			if($user_type=='admin'){
				$us_type='adm';
			}else{
				$us_type='slr';
			}
			if($refundID!=""){
				$transid=$refundID.'ref'.$refundID.'o'.$orderID.'us'.$userID.''.$us_type;
				// $new_transid=getRandomNumber(4).'ref'.$refundID.'o'.$orderID.'us'.$userID.''.$us_type;
			}else if($withdrawID!=""){
				$transid=$withdrawID.'wthd'.$withdrawID.'us'.$userID.''.$us_type;
				// $new_transid=getRandomNumber(4).'wthd'.$withdrawID.'us'.$userID.''.$us_type;
			}

	        if($transid==""){
	        	// $transid=getRandomNumber(8).'_us_'.$userID;
				die("Unknown transaction type");
	        }
			$status=array(); $transactionID=""; $msg="";
			$utilitycode=$this->getUtilitycode($payment_type);
			$get_status=$this->disbursement($transid, $utilitycode, $phone, $amount);
			if($get_status['resultcode']!='000'){
				$msg=$get_status['message'];
			}else{
				$response_msg=$get_status['message'];
				$seller_id=""; $seller_type=""; $admin_id="";
				if($user_type=="admin"){
					$admin_id=$userID;
				}else{
					$seller_id=$userID;
					$seller_type=$user_type;
				}	
	        	$this->db->insert('tbl_transactions', array(
		            'admin_id' => $admin_id,
		            'seller_id' => $seller_id,
		            'seller_type' => $seller_type,
		            'orderID' => $orderID,
		            'refund_id' => $refundID,
		            'withdraw_id' => $withdrawID,
		            'transactionType' => $transactionType,
		            'provider' => 'selcom',
		            'amount' => $amount,
		            'method' => 'mobile',
		            'account_name' => 'mobile',
		            'account_number' => $phone,
		            'transid' => $transid,
		            'response_msg' => $response_msg,
		            'is_complete' => '0',
		            'status' => '0',
		            'processedBy' => $userID,
		            'createdBy' => $userID,
		            'transactionDate' => date('Y/m/d H:i:s')
		        ));

		        $this->db->select('id');
				$this->db->from('tbl_transactions');
				$this->db->where('transid', $transid);
				$this->db->where('admin_id', $admin_id);
				$this->db->where('seller_id', $seller_id);
			   	$this->db->where('status', '0');
			   	
		   		$query = $this->db->get();		   	
			   	if($query->num_rows() > 0){
			   		foreach ($query->result_array() as $data){
		                $transactionID=$data['id'];
		            }
			   	}        
		        $msg="success";
			}
			$status['msg']=$msg;
			$status['transactionID']=$transactionID;
			return $status;
		}
		/*end*/


		function get_mobilemoney_menu($provider, $pay_number, $business_number, $amount){
			$menu="";
			if($pay_number!=""){
				$menu.='<p>'.ucwords($provider).' - INSTRUCTION</p>';
				if($provider=='mpesa'){
					$menu.='<ol>';
				    $menu.='<li>Dial *150*00#</li>';
				    $menu.='<li>Choose 4 - Lipa by M-Pesa</li>';
				    $menu.='<li>Choose 4 - Enter business number</li>';
				    $menu.='<li>Enter '.$business_number.' (As Business number)</li>';
				    $menu.='<li>Enter reference number ('.$pay_number.' As Pay Number)</li>';
				    $menu.='<li>Enter amount ('.$amount.')</li>';
				    $menu.='<li>Enter PIN</li>';
				    $menu.='<li>You will receive confirmation SMS</li>';
					$menu.='</ol>';
				}elseif($provider=='airtelmoney'){
					$menu.='<ol>';
				    $menu.='<li>Dial *150*60#</li>';
				    $menu.='<li>Choose 5 - Make Payments</li>';
				    $menu.='<li>Choose 1 - Merchant Payments</li>';
				    $menu.='<li>Choose 1 - Pay with SelcomPay/Masterpass</li>';
				    $menu.='<li>Enter Amount ('.$amount.')</li>';
				    $menu.='<li>Enter the reference number ('.$pay_number.' As Pay Number)</li>';
				    $menu.='<li>Enter PIN to confirm</li>';
					$menu.='</ol>';
				}elseif($provider=='tigopesa'){
					$menu.='<ol>';
				    $menu.='<li>Dial *150*01#</li>';
				    $menu.='<li>Choose 5 - Lipia bidhaa</li>';
				    $menu.='<li>Choose 2 - Pay by Matercard QR</li>';
				    $menu.='<li>Enter reference number ('.$pay_number.' As Pay Number)</li>';
				    $menu.='<li>Enter amount ('.$amount.')</li>';
				    $menu.='<li>Enter PIN</li>';
				    $menu.='<li>You will receive confirmation SMS</li>';
					$menu.='</ol>';
				}elseif($provider=='halopesa'){
					$menu.='<ol>';
				    $menu.='<li>Dial *150*88#</li>';
				    $menu.='<li>Choose 5-Make Payments</li>';
				    $menu.='<li>Choose 3 – Selcom Pay/Masterpass</li>';
				    $menu.='<li>Enter Pay Number ('.$pay_number.' As Pay Number)</li>';
				    $menu.='<li>Enter amount ('.$amount.')</li>';
				    $menu.='<li>Enter PIN</li>';
				    $menu.='<li>You will receive confirmation SMS</li>';
					$menu.='</ol>';
				}elseif($provider=='ttclpesa'){
					$menu.='<ol>';
				    $menu.='<li>Dial *150*71#</li>';
				    $menu.='<li>Choose 6 - Pay Merchant</li>';
				    $menu.='<li>Choose 2 – SelcomPay/Masterpass</li>';
				    $menu.='<li>Enter Pay Number ('.$pay_number.' As Pay Number)</li>';
				    $menu.='<li>Enter amount ('.$amount.')</li>';
				    $menu.='<li>Enter PIN</li>';
				    $menu.='<li>You will receive confirmation SMS</li>';
					$menu.='</ol>';
				}elseif($provider=='ezypesa'){
					$menu.='<ol>';
				    $menu.='<li>Dial *150*02#</li>';
				    $menu.='<li>Choose 5 - Payments</li>';
				    $menu.='<li>Choose 1 - Lipa Hapa</li>';
				    $menu.='<li>Choose 2 - Pay by Masterpass QR</li>';
				    $menu.='<li>Enter Merchant Number ('.$pay_number.' As Pay Number)</li>';
				    $menu.='<li>Enter Amount ('.$amount.')</li>';
				    $menu.='<li>Enter PIN</li>';
				    $menu.='<li>You will receive confirmation SMS</li>';
					$menu.='</ol>';
				}
			}
			return $menu;
		}

		function update_orderID($orderID, $userID){
			$new_orderID=generateOrderID();
			if($new_orderID!="" && $orderID!=""){
				$set1['orderID']=$new_orderID;
				$this->db->where('customer', $userID);
				$this->db->where('orderID', $orderID);
		     	$this->db->update('tbl_transactions', $set1);

                $set2['orderID']=$new_orderID;
                $this->db->where('user_id',$userID);
		        $this->db->where('orderID', $orderID);
		     	$this->db->update('tbl_orders', $set2);

                $set3['orderID']=$new_orderID;
                $this->db->where('user_id',$userID);
		        $this->db->where('orderID', $orderID);
		     	$this->db->update('tbl_cart', $set3);

                $set4['orderID']=$new_orderID;
		        $this->db->where('orderID', $orderID);
		     	$this->db->update('tbl_commissions', $set4);
			}
			return $new_orderID;
		}

    }
?>