<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	date_default_timezone_set('Africa/Dar_es_Salaam');
	ini_set('max_execution_time', 300);
    set_time_limit(300);
	

	class Vpesa_model extends CI_Model{

	    public function __construct(){
	        parent::__construct();
	        $this->load->database();
	        $this->load->helper('vpesa/api');
	        $this->api_key = 'qcPpOPpsSSVFkZsiRyVSfVkfhGTHJdYS';
    	}

    	public function getPublicKey($scope){
    		$public_key="";
    		if($scope=='test'){
    			$public_key = 'MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEArv9yxA69XQKBo24BaF/D+fvlqmGdYjqLQ5WtNBb5tquqGvAvG3WMFETVUSow/LizQalxj2ElMVrUmzu5mGGkxK08bWEXF7a1DEvtVJs6nppIlFJc2SnrU14AOrIrB28ogm58JjAl5BOQawOXD5dfSk7MaAA82pVHoIqEu0FxA8BOKU+RGTihRU+ptw1j4bsAJYiPbSX6i71gfPvwHPYamM0bfI4CmlsUUR3KvCG24rB6FNPcRBhM3jDuv8ae2kC33w9hEq8qNB55uw51vK7hyXoAa+U7IqP1y6nBdlN25gkxEA8yrsl1678cspeXr+3ciRyqoRgj9RD/ONbJhhxFvt1cLBh+qwK2eqISfBb06eRnNeC71oBokDm3zyCnkOtMDGl7IvnMfZfEPFCfg5QgJVk1msPpRvQxmEsrX9MQRyFVzgy2CWNIb7c+jPapyrNwoUbANlN8adU1m6yOuoX7F49x+OjiG2se0EJ6nafeKUXw/+hiJZvELUYgzKUtMAZVTNZfT8jjb58j8GVtuS+6TM2AutbejaCV84ZK58E2CRJqhmjQibEUO6KPdD7oTlEkFy52Y1uOOBXgYpqMzufNPmfdqqqSM4dU70PO8ogyKGiLAIxCetMjjm6FCMEA3Kc8K0Ig7/XtFm9By6VxTJK1Mg36TlHaZKP6VzVLXMtesJECAwEAAQ==';
    		}elseif($scope=='live'){
    			$public_key = 'MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAietPTdEyyoV/wvxRjS5pSn3ZBQH9hnVtQC9SFLgM9IkomEX9Vu9fBg2MzWSSqkQlaYIGFGH3d69Q5NOWkRo+Y8p5a61sc9hZ+ItAiEL9KIbZzhnMwi12jUYCTff0bVTsTGSNUePQ2V42sToOIKCeBpUtwWKhhW3CSpK7S1iJhS9H22/BT/pk21Jd8btwMLUHfVD95iXbHNM8u6vFaYuHczx966T7gpa9RGGXRtiOr3ScJq1515tzOSOsHTPHLTun59nxxJiEjKoI4Lb9h6IlauvcGAQHp5q6/2XmxuqZdGzh39uLac8tMSmY3vC3fiHYC3iMyTb7eXqATIhDUOf9mOSbgZMS19iiVZvz8igDl950IMcelJwcj0qCLoufLE5y8ud5WIw47OCVkD7tcAEPmVWlCQ744SIM5afw+Jg50T1SEtu3q3GiL0UQ6KTLDyDEt5BL9HWXAIXsjFdPDpX1jtxZavVQV+Jd7FXhuPQuDbh12liTROREdzatYWRnrhzeOJ5Se9xeXLvYSj8DmAI4iFf2cVtWCzj/02uK4+iIGXlX7lHP1W+tycLS7Pe2RdtC2+oz5RSSqb5jI4+3iEY/vZjSMBVk69pCDzZy4ZE8LBgyEvSabJ/cddwWmShcRS+21XvGQ1uXYLv0FCTEHHobCfmn2y8bJBb/Hct53BaojWUCAwEAAQ==';
    		}
    		return $public_key;
    	}

    	public function encryptKey(){
			$public_key = $this->getPublicKey('live');
            // Public key on the API listener used to encrypt keys
            // Create Context with API to request a Session ID
            $context = new APIContext();
            // Api key
            // $context->set_api_key('6bc4157dbowkdd409118e0978dc6991a');
            $context->set_api_key($this->api_key);
            // Public key
            $context->set_public_key($public_key);
            // Create a request object
            $request = new APIRequest($context);
            // Generate BearerToken
            $token = $request->create_bearer_token();
            return $token ;
    	}

    	public function getToken(){
			// Public key on the API listener used to encrypt keys
			$public_key = $this->getPublicKey('live');
			// Create Context with API to request a Session ID
			$context = new APIContext();
			// Api key
			$context->set_api_key($this->api_key);
			// Public key
			$context->set_public_key($public_key);
			// Use ssl/https
			$context->set_ssl(true);
			// Method type (can be GET/POST)
			$context->set_method_type(APIMethodType::GET);
			// API address
			$context->set_address('openapi.m-pesa.com');
			// API Port
			$context->set_port(443);
			// API Path
			$context->set_path('/openapi/ipg/v2/vodacomTZN/getSession/');
			// Add/update headers
			$context->add_header('Origin', '*');
			// Parameters can be added to the call as well that on POST will be in JSON format and on GET will be URL parameters
			// context->add_parameter('key', 'value');
			// Create a request object
			$request = new APIRequest($context);
			// Do the API call and put result in a response packet
			$response = null;
			try {
				$response = $request->execute();
			} catch(exception $e) {
				echo 'Call failed: ' . $e->getMessage() . '<br>';
			}

			if ($response->get_body() == null) {
				throw new Exception('SessionKey call failed to get result. Please check.');
			}
			// die(var_dump($response));
			// Display results
			/*echo $response->get_status_code() . 'YOOXOO<br>';
			echo $response->get_headers() . 'YOTOOO<br>';
			echo $response->get_body() . 'YOOOO<br>';*/
			// Decode JSON packet
			$decoded = json_decode($response->get_body());
			$resp=$decoded->output_ResponseCode;
			if($resp=='INS-0'){
				return $decoded->output_SessionID;
			}else{
				die($decoded->output_ResponseDesc);
			}
    	}
    	
    	public function create_third_party_conversation_id($orderId){
    	    
    	    $time = str_replace(":","",date('H:i:s'));
    	    $conversationID = 'GTV'.$orderId.$time;
    	    
    	    $this->db->set('uniqueConversationID', $conversationID);
			$this->db->where('orderID', $orderId);
	     	$this->db->update('tbl_orders');
	     	
    	    return $conversationID;
    	}
    	
    	public function get_third_party_conversation_id($orderId){
    	    
    	    $conversationID = "";
    	    
    	    $this->db->select('uniqueConversationID');
	        $this->db->where('orderID', $orderId);
	        $query13 = $this->db->get('tbl_orders');
	        
	        if($query13->num_rows() > 0){
	            foreach ($query13->result_array() as $data) {
	                $conversationID = $data['uniqueConversationID'];
	            }
	        }
	        
    	    return $conversationID;
    	}

    	public function create_order($phone, $product, $orderID, $amount){
    	    
    	    $conversationID = $this->create_third_party_conversation_id($orderID);
    	    
    		$sessionID=$this->getToken();
    		$public_key = $this->getPublicKey('live');
			$context = new APIContext();
			$context->set_api_key($sessionID);
			$context->set_public_key($public_key);
			$context->set_ssl(true);
			$context->set_method_type(APIMethodType::POST);
			$context->set_address('openapi.m-pesa.com');
			$context->set_port(443);
			$context->set_path('/openapi/ipg/v2/vodacomTZN/c2bPayment/singleStage/');
			$context->add_header('Origin', '*');
			$context->add_parameter('input_Amount', $amount);
			$context->add_parameter('input_Country', 'TZN');
			$context->add_parameter('input_Currency', 'TZS');
			$context->add_parameter('input_CustomerMSISDN', $phone);
			$context->add_parameter('input_ServiceProviderCode', '971838');
// 			$context->add_parameter('input_ThirdPartyConversationID', 'asv02e5958774f7ba228d83d0d689762');
			$context->add_parameter('input_ThirdPartyConversationID', $conversationID);
			$context->add_parameter('input_TransactionReference', $orderID);
			$context->add_parameter('input_PurchasedItemsDesc', $product);
			$request = new APIRequest($context);
			// SessionID can take up to 30 seconds to become 'live' in the system and will be invalid until it is
			sleep(5);
			$response = null;
			try {
				$response = $request->execute();
				
			} catch(exception $e) {
				echo 'Call failed: ' . $e->getMessage() . '<br>';
			}
			if ($response->get_body() == null) {
				throw new Exception('API call failed to get result. Please check.');
			}
			
			
			echo $response->get_status_code() . '<br>';
			echo $response->get_headers() . '<br>';
			echo $response->get_body() . '<br>';
			
			$decoded = json_decode($response->get_body());

			$resp=$decoded->output_ResponseCode;
			
// 			if($resp=='INS-0'){
// 				return $decoded->output_TransactionID;
// 			}else{
// 				die($decoded->output_ResponseDesc);
// 			}
			
			return $decoded;
    	}

    	public function transaction_status($transaction_id,$orderID){
    	    
    	    $conversationID = $this->get_third_party_conversation_id($orderID);
    	    
    		$sessionID=$this->getToken();
    		$public_key = $this->getPublicKey('live');
			$context = new APIContext();
			$context->set_api_key($sessionID);
			$context->set_public_key($public_key);
			$context->set_ssl(true);
			$context->set_method_type(APIMethodType::GET);
			$context->set_address('openapi.m-pesa.com');
			$context->set_port(443);
			$context->set_path('/openapi/ipg/v2/vodacomTZN/queryTransactionStatus/');
			$context->add_header('Origin', '*');
			$context->add_parameter('input_QueryReference', $transaction_id);
			$context->add_parameter('input_ServiceProviderCode', '971838');
// 			$context->add_parameter('input_ThirdPartyConversationID', 'asv02e5958774f7ba228d83d0d689762');
			$context->add_parameter('input_ThirdPartyConversationID', $conversationID);
			$context->add_parameter('input_Country', 'TZN');
			
			
			$request = new APIRequest($context);
			
			// SessionID can take up to 30 seconds to become 'live' in the system and will be invalid until it is
			sleep(5);
			$response = null;
			try {
				$response = $request->execute();
				
			} catch(exception $e) {
				die('Call failed: ' . $e->getMessage() . '<br>');
			}
			
			if ($response->get_body() == null) {
				throw new Exception('API call failed to get result. Please check.');
			}
			
			echo $response->get_status_code() . '<br>';
			echo $response->get_headers() . '<br>';
			echo $response->get_body() . '<br>';

			$decoded = json_decode($response->get_body());
		
			$resp=$decoded->output_ResponseCode;
			
			if($resp=='INS-0'){
				return 'Success';
			}else{
				if(isset($decoded->output_ResponseDesc))
					die($decoded->output_ResponseDesc);
				else
					return json_encode($decoded);
			}

            //return $decoded;
    	}

		/*System implementation*/
		
		function check_order_status($orderID, $userID){
		    
			$status="";
			$this->db->select('transid');
	        $this->db->where('orderID', $orderID);
	        $this->db->where('customer', $userID);
	        $this->db->where('status', '0');
	        $query13 = $this->db->get('tbl_transactions');
	  
	        if($query13->num_rows() > 0){
	             
	        	foreach ($query13->result_array() as $dt) {
	        	    
	        		$transid=$dt['transid'];
	        		
	        		if($transid != ''){
	        		    
	        		 //   $get_status=$this->transaction_status($transid,$orderID);
	        		 
	        		   /* Temp function to complete all the paid orders from VPESA */
	        		   
	        		    $get_status = $this->complete_order_payments($orderID,$transid);
	        		    
    	        		if($get_status=='Success'){
    	        		    $this->db->set('is_complete', '0');
    						$this->db->where('customer', $userID);
    						$this->db->where('orderID', $orderID);
    			   			$this->db->where('provider', 'vodacom');
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
    					   	
    				     	$status="paid";
    				     	
    	        		}else{
    				   		$status="not paid";
    				   		//$status='Payment Status: '.$get_status;
    				   	}
	        		}
	        		else{
	        		    $status="Failed to get transaction id";
	        		}
	        		
	        	}
	        }else{
	            $status="not set";
	        }
	       
			return $status;
		}
		
		private function complete_order_payments($orderID,$transactionID){
		    
		    $this->db->select('id');
	        $this->db->where('orderID', $orderID);
	        $this->db->where('transid', $transactionID);
	        $this->db->where('provider', 'vodacom');
	        $this->db->where('status', '0');
	        $query12 = $this->db->get('tbl_transactions');
	        
	        if($query12->num_rows() > 0){
	            return 'Success';
	        }
	        else{
	            return 'Not Paid';
	        }
	        
		}

		
		//Refactored function for creating order
		function create_mobile_order($orderID, $userID, $phone, $amount){
		    
		    $res=array();
		    
			$error=false; $msg="";
			
			$check_pay=$this->check_order_status($orderID, $userID);
			
			if($check_pay != 'paid'){
			    
    			$transid="";
    			
    	        $create_order=$this->create_order($phone, 'EBook', $orderID, $amount);
    	        
    			if($create_order->output_ResponseCode!='INS-0'){
    				$msg=" --- ";
    				if(isset($create_order->output_ResponseDesc))
    					$msg=ErrorMsg($create_order->output_ResponseDesc);
    				$error=true;
    			}else{
    				$transid=$create_order->output_TransactionID;
    				//$msg='Request in progress. You will receive a callback shortly to the phone number you provided, make sure it is available and phone is active. Click resend btn if callback takes more than 2 mins';
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
    	        		$set2['provider']='vodacom';
    		        	$set2['transid']=$transid;
    
    			        $this->db->where('status', '0');
    			        $this->db->where('is_complete', '2');
    			        $this->db->where('customer',$userID);
    			        $this->db->where('orderID', $orderID);
    			        //$this->db->where('provider', 'vodacom');
    			     	$this->db->update('tbl_transactions', $set2);
    		        }else{
    		        	$this->db->insert('tbl_transactions', array(
    			            'customer' => $userID,
    			            'orderID' => $orderID,
    			            'provider' => 'vodacom',
    			            'amount' => $amount,
    			            'transid' => $transid,
    			            'is_complete' => '2',
    			            'status' => '0',
    			            'createdBy' => $userID,
    			            'transactionDate' => date('Y/m/d H:i:s')
    			        ));
    		        }
    			}
    			
    			$res['msg']=$msg;
				$res['instruction']='';
				$res['error']=$error;
				
    			return $res;
    			
    			}
			else{
			    return 'paid';
			}
			
		}
		/*end*/

    }
?>