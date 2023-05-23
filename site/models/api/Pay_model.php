<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Pay_model extends CI_Model{

	    public function __construct(){
	        parent::__construct();
	        $this->load->database();
	        $this->companyToken="02206B78-4C41-48E5-A296-8F4322AB86F5";
	        $this->companyRef="49FKEOA";
    	}

		public function directPay_createToken($paymentAmount, $currency, $redirectURL, $backUrl, $customerEmail, $customerFirstName, $customerLastName, $customerAddress, $customerCity, $customerCountry, $customerPhone, $customerZip, $service_type, $service_description, $service_date, $userID, $orderID="", $update=""){

			/*Services types:
			3854-Test Product
			5525-Test Service*/

			/*LIVE ENVIRONMEN INFO
			COMPANY ID: 02206B78-4C41-48E5-A296-8F4322AB86F5
			Services types:
			18682-e-books
			18683-e-learning material
			Live endpoint: https://secure.3gdirectpay.com/API/v6/
			Payment URL: https://secure.3gdirectpay.com/pay.asp?ID={token}
			*/

			$res="";
			$xml='<?xml version="1.0" encoding="utf-8"?>
				<API3G>
					<CompanyToken>02206B78-4C41-48E5-A296-8F4322AB86F5</CompanyToken>
					<Request>createToken</Request>
				    <Transaction>
				      <PaymentAmount>'.$paymentAmount.'</PaymentAmount>
				      <PaymentCurrency>'.$currency.'</PaymentCurrency>
				      <CompanyRef>49FKEOA</CompanyRef>
				      <RedirectURL>'.$redirectURL.'</RedirectURL>
				      <BackURL>'.$backUrl.'</BackURL>
				      <CompanyRefUnique>0</CompanyRefUnique>
				      <PTL>5</PTL>
				      <customerEmail>'.$customerEmail.'</customerEmail>
					  <customerFirstName>'.$customerFirstName.'</customerFirstName>
					  <customerLastName>'.$customerLastName.'</customerLastName>
					  <customerAddress>'.$customerAddress.'</customerAddress>
					  <customerCity>'.$customerCity.'</customerCity>
					  <customerCountry>Tz</customerCountry>
					  <customerPhone>'.$customerPhone.'</customerPhone>
				    </Transaction>
				    <Services>
				      <Service>
				        <ServiceType>'.$service_type.'</ServiceType>
				        <ServiceDescription>'.$service_description.'</ServiceDescription>
				        <ServiceDate>'.$service_date.'</ServiceDate>
				      </Service>
				    </Services>
				</API3G>';

			/*Testing url*/
			// $url='https://secure1.sandbox.directpay.online/API/v6/';
			/*LIVE URL*/
			$url='https://secure.3gdirectpay.com/API/v6/';
			$curl=curl_init($url);
			curl_setopt ($curl, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($curl);
			if(curl_errno($curl)){
			    throw new Exception(curl_error($curl));
			}
			curl_close($curl);
			$xml_res=simplexml_load_string($result) or die("Error: Cannot create object");
			if ($xml_res === false) {
			    $res.= "Failed loading XML: ";
			    foreach(libxml_get_errors() as $error) {
			        $res.="<br>".$error->message;
			    }
			}else{
			    $status=$xml_res->Result;
				$token=$xml_res->TransToken;
				$trans_reference=$xml_res->TransRef;
				// echo $result.'<br>';
				if($xml_res->Result=='000'){
					/*$this->db->select('id');
			        $this->db->where('customer',$userID);
			        $this->db->where('orderID', $orderID);
			        $this->db->where('provider', 'directpay');
			        $this->db->where('is_complete!=', '0');
			        $this->db->where('status','0');
			        $query = $this->db->get('tbl_transactions');

			        if($query->num_rows() > 0){
			            return 'Okay';
			        }else{*/

			        	$this->db->select('id');
				        $this->db->where('orderID', $orderID);
				        $this->db->where('customer', $userID);
				        // $this->db->where('provider', 'selcom');
				        $this->db->where('status', '0');
				        $query12 = $this->db->get('tbl_transactions');
				        if($query12->num_rows() > 0){
			        		$set2=array(
					            'trans_reference' => $trans_reference,
					            'trans_token' => $token,
					            'provider' => 'directpay',
					            'amount' => $paymentAmount
					        );
					        $this->db->where('status', '0');
					        // $this->db->where('is_complete', '2');
					        $this->db->where('customer',$userID);
					        $this->db->where('orderID', $orderID);
					        // $this->db->where('provider', 'directpay');
					     	$this->db->update('tbl_transactions', $set2);

					     	return 'Okay';
			        	}else{
			        		$set=$this->db->insert('tbl_transactions', array(
					            'customer' => $userID,
					            'orderID' => $orderID,
					            'provider' => 'directpay',
					            'amount' => $paymentAmount,
					            'trans_token' => $token,
					            'trans_reference' => $trans_reference,
					            'is_complete' => '2',
					            'status' => '0',
					            'createdBy' => $userID,
					            'transactionDate' => $service_date
					        ));

					        if($set){
					        	return 'Okay';
					        }else{
					        	return ErrorMsg($this->db->_error_message());
					        }
			        	}
			        	
			        // }
				}else{
					return $xml_res->ResultExplanation;
				}
			}
			
		}

		public function directPay_createToken_mobile($paymentAmount, $currency, $redirectURL, $backUrl, $customerEmail, $customerFirstName, $customerLastName, $customerAddress, $customerDialCode, $customerCity, $customerCountry, $customerZip, $customerPhone, $service_type, $service_description, $service_date, $userID, $orderID, $update=""){

			/*Services types:
			3854-Test Product
			5525-Test Service*/

			/*LIVE ENVIRONMEN INFO
			COMPANY ID: 02206B78-4C41-48E5-A296-8F4322AB86F5
			Services types:
			18682-e-books
			18683-e-learning material
			Live endpoint: https://secure.3gdirectpay.com/API/v6/
			Payment URL: https://secure.3gdirectpay.com/pay.asp?ID={token}
			*/

			$res="";
			$xml='<?xml version="1.0" encoding="utf-8"?>
				<API3G>
					<CompanyToken>'.$this->companyToken.'</CompanyToken>
					<Request>createToken</Request>
				    <Transaction>
				      <PaymentAmount>'.$paymentAmount.'</PaymentAmount>
				      <PaymentCurrency>'.$currency.'</PaymentCurrency>
				      <CompanyRef>'.$this->companyRef.'</CompanyRef>
				      <RedirectURL>'.$redirectURL.'</RedirectURL>
				      <BackURL>'.$backUrl.'</BackURL>
				      <CompanyRefUnique>0</CompanyRefUnique>
				      <PTL>5</PTL>
					  <AllowRecurrent>1</AllowRecurrent>
				      <customerEmail>'.$customerEmail.'</customerEmail>
					  <customerFirstName>'.$customerFirstName.'</customerFirstName>
					  <customerLastName>'.$customerLastName.'</customerLastName>
					  <customerAddress>'.$customerAddress.'</customerAddress>
					  <customerDialCode>'.$customerDialCode.'</customerDialCode>
					  <customerCity>'.$customerCity.'</customerCity>
					  <customerCountry>'.$customerCountry.'</customerCountry>
					  <customerZip>'.$customerZip.'</customerZip>
					  <customerPhone>'.$customerPhone.'</customerPhone>
				    </Transaction>
				    <Services>
				      <Service>
				        <ServiceType>'.$service_type.'</ServiceType>
				        <ServiceDescription>'.$service_description.'</ServiceDescription>
				        <ServiceDate>'.$service_date.'</ServiceDate>
				      </Service>
				    </Services>
				</API3G>';


			/*Testing url*/
			// $url='https://secure1.sandbox.directpay.online/API/v6/';
			/*LIVE URL*/
			$url='https://secure.3gdirectpay.com/API/v6/';
			$curl=curl_init($url);
			curl_setopt ($curl, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($curl);
			if(curl_errno($curl)){
			    throw new Exception(curl_error($curl));
			}
			curl_close($curl);
			$xml_res=simplexml_load_string($result) or die("Error: Cannot create object");
			if ($xml_res === false) {
			    $res.= "Failed loading XML: ";
			    foreach(libxml_get_errors() as $error) {
			        $res.="<br>".$error->message;
			    }
			}else{
			    $status=$xml_res->Result;
				$token=$xml_res->TransToken;
				$trans_reference=$xml_res->TransRef;
				// echo $result.'<br>';
				if($xml_res->Result=='000'){
					/*$this->db->select('id');
			        $this->db->where('customer',$userID);
			        $this->db->where('orderID', $orderID);
			        $this->db->where('provider', 'directpay');
			        $this->db->where('is_complete!=', '0');
			        $this->db->where('status','0');
			        $query = $this->db->get('tbl_transactions');

			        if($query->num_rows() > 0){
			            return 'Okay';
			        }else{*/

			        	$this->db->select('id');
				        $this->db->where('orderID', $orderID);
				        $this->db->where('customer', $userID);
				        // $this->db->where('provider', 'selcom');
				        $this->db->where('status', '0');
				        $query12 = $this->db->get('tbl_transactions');
				        if($query12->num_rows() > 0){
			        		$set2=array(
					            'trans_reference' => $trans_reference,
					            'trans_token' => $token,
					            'provider' => 'directpay',
					            'amount' => $paymentAmount
					        );
					        $this->db->where('status', '0');
					        // $this->db->where('is_complete', '2');
					        $this->db->where('customer',$userID);
					        $this->db->where('orderID', $orderID);
					        // $this->db->where('provider', 'directpay');
					     	$this->db->update('tbl_transactions', $set2);

					     	return 'Okay';
			        	}else{
			        		$set=$this->db->insert('tbl_transactions', array(
					            'customer' => $userID,
					            'orderID' => $orderID,
					            'provider' => 'directpay',
					            'amount' => $paymentAmount,
					            'trans_token' => $token,
					            'trans_reference' => $trans_reference,
					            'is_complete' => '2',
					            'status' => '0',
					            'createdBy' => $userID,
					            'transactionDate' => $service_date
					        ));

					        if($set){
					        	return 'Okay';
					        }else{
					        	return ErrorMsg($this->db->_error_message());
					        }
			        	}
			        	
			        // }
				}else{
					return $xml_res->ResultExplanation;
				}
			}
			
		}

		public function verifyToken($transactionToken){
			$res="";
			$xml='<?xml version="1.0" encoding="utf-8"?>
				<API3G>
					<CompanyToken>'.$this->companyToken.'</CompanyToken>
					<Request>verifyToken</Request>
					<TransactionToken>'.$transactionToken.'</TransactionToken>
				</API3G>';

			/*Testing url*/
			// $url='https://secure1.sandbox.directpay.online/API/v6/';
			/*LIVE URL*/
			$url='https://secure.3gdirectpay.com/API/v6/';
			$curl=curl_init($url);
			curl_setopt ($curl, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($curl);
			if(curl_errno($curl)){
			    throw new Exception(curl_error($curl));
			}
			curl_close($curl);
			$xml_res=simplexml_load_string($result) or die("Error: Cannot create object");
			if ($xml_res === false) {
			    $res.= "Failed loading XML: ";
			    foreach(libxml_get_errors() as $error) {
			        $res.="<br>".$error->message;
			    }
			}else{
			    return $xml_res;
			}
			
		}

		public function chargeTokenMobile($transactionToken, $phoneNumber, $MNO, $MNOcountry){
			$res="";
			$xml='<?xml version="1.0" encoding="utf-8"?>
				<API3G>
					<CompanyToken>'.$this->companyToken.'</CompanyToken>
					<Request>ChargeTokenMobile</Request>
					<TransactionToken>'.$transactionToken.'</TransactionToken>
					<PhoneNumber>'.$phoneNumber.'</PhoneNumber>
					<MNO>'.$MNO.'</MNO>
					<MNOcountry>'.$MNOcountry.'</MNOcountry>
				</API3G>';

			/*Testing url*/
			// $url='https://secure1.sandbox.directpay.online/API/v6/';
			/*LIVE URL*/
			$url='https://secure.3gdirectpay.com/API/v6/';
			$curl=curl_init($url);
			curl_setopt ($curl, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($curl);
			if(curl_errno($curl)){
			    throw new Exception(curl_error($curl));
			}
			curl_close($curl);
			// $result = str_replace("<br /></b>", "</b>", $result);
			if($MNO!='MTN' && $MNOcountry!='ghana'){
				$result = str_replace("<br>", "&lt;br /&gt;", $result);
			}

			// $result = preg_replace('/<br /></b>/i', '<\/b>', $result);
		 	// var_dump($result);
			// $result = str_replace("<br>", "<br></br>", $result);
			if($MNO=='MTN' && $MNOcountry=='ghana'){
				return $result;
			}else{
				$xml_res=simplexml_load_string($result) or die("Error: Cannot create object");
				if ($xml_res === false) {
				    $res.= "Failed loading XML: ";
				    foreach(libxml_get_errors() as $error) {
				        $res.="<br>".$error->message;
				    }
				}else{
				    return $xml_res;
				}
			}
			
		}

		/*System implementation*/
		function get_phone_menu($phoneNumber, $MNO, $MNOcountry, $transactionToken){
			$res=array(); $instruction="";
			$get_status=$this->chargeTokenMobile($transactionToken, $phoneNumber, $MNO, $MNOcountry);
			// die(var_dump($get_status));
			if($MNO=='MTN' && $MNOcountry=='ghana'){
				$status='Okay';
				$instruction=str_replace("130New Invoice1", "New Invoice: <br />1", $get_status);
			}else{
				if($get_status->StatusCode!='000' && $get_status->StatusCode!='4' && $get_status->StatusCode!='130'){
					$status=$get_status->ResultExplanation;
				}else{
					$status='Okay'; $instruction="";
					if($get_status->StatusCode=='4'){
						$instruction=$get_status->ResultExplanation;
					}elseif($get_status->StatusCode=='130'){
						$instruction=$get_status->ResultExplanation;
						if(isset($get_status->instructions))
							$instruction=$instruction.'<br >'.$get_status->instructions;
					}else{
						if(isset($get_status->Instructions))
							$instruction=$get_status->Instructions;
					}
					
					if(isset($get_status->RedirectOption) && $get_status->RedirectOption=='1'){
						/*need to be redirected*/
					}
				}
			}
			$res['status']=$status;
			$res['instruction']=$instruction;
			return $res;
		}

		function check_order_status($orderID, $userID, $transactionToken, $amount){
			$status="";
			$get_status=$this->verifyToken($transactionToken);
			// var_dump($get_status);
			if($get_status->Result=='000'){
				if($amount>$get_status->TransactionAmount){
					$status="Paid amount is lower than total order amount which is ".$amount;
				}else{
					$this->db->select('id, orderID');
					$this->db->from('tbl_transactions');
					$this->db->where('customer', $userID);
					$this->db->where('orderID', $orderID);
					$this->db->where('amount', $amount);
					// $this->db->where('trans_token', $transactionToken);
				   	// $this->db->where('provider', 'directpay');
				   	// $this->db->where('is_complete', '2');
				   	$this->db->where('status', '0');
				   	
			   		$query = $this->db->get();		   	
				   	if($query->num_rows() > 0){
		                $this->db->set('is_complete', '0');
						$this->db->where('customer', $userID);
						$this->db->where('orderID', $orderID);
						$this->db->where('trans_token', $transactionToken);
			   			// $this->db->where('provider', 'directpay');
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
				        $this->db->select('tbl_commissions.id as commission_id, tbl_transactions.id as transaction_id');
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
		                }
		                /*end t r*/
				     	
			   			$status="paid";
				   	}else{
				   		$status="not paid";
				   	}
			   	}
			}else if($get_status->Result=='900'){
				$status="not paid";
			}else{
				$status=$get_status->ResultExplanation;
			}
			return $status;
		}

		/*end*/

		function currencyConverter($currency_from, $currency_to, $amount) {
			//currency codes : http://en.wikipedia.org/wiki/ISO_4217
			$url="https://free.currencyconverterapi.com/api/v5/convert?q=".$currency_from."_".$currency_to."&compact=ultra&apiKey=6f23117d8e5313bee601";
	        $ch=curl_init();
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($ch, CURLOPT_URL, $url);
	        $res=curl_exec($ch);
	        curl_close($ch);
	        $data = json_decode($res, true);
	        $rate=$data[$currency_from."_".$currency_to];
	        $total = $rate * $amount;
    		$rounded = round($total);

	    	return $rounded;
		}

		/*$url="http://41.93.40.137/nacte_api/index.php/api/results/LXpH0l5N0oqUxT/b4c4315a2e6051e150267f2c6ab440b52858aa29/15000902010501/$avn";
		/*$url="https://free.currencyconverterapi.com/api/v5/convert?q=USD_TZS&compact=ultra&apiKey=6f23117d8e5313bee601";
        $ch=curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $res=curl_exec($ch);
        curl_close($ch);
        $data = json_decode($res, true);
    	return $data;*/

    }
?>