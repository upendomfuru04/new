<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Commission_model extends CI_Model{

	    public function __construct(){
	        parent::__construct();
	        $this->load->database();
    	}

		/*public function withdrawal_form($userID){
			$total_balance=0;
			$this->db->select('amount, paid_amount, quantity');
			$this->db->from('tbl_commissions');
			$this->db->join('tbl_cart', 'tbl_cart.id=cart_id');
		   	$this->db->where('tbl_commissions.seller_id', $userID);
		   	$this->db->where('tbl_cart.status', '0');
		   	$this->db->where('tbl_commissions.commissionStatus', '1');
		   	$this->db->where('tbl_commissions.status', '0');
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0){
		   		foreach ($query->result_array() as $data) {
		   			$total_balance=$total_balance+(($data['quantity']*$data['amount'])-$data['paid_amount']);
		   		}
		   	}
		   	return $total_balance;
		}*/

		public function load_withdrawal_request($requestID){
			$result=array();
			$this->db->select('amount, seller_type, requestID');
			$this->db->from('tbl_withdrawal');
		   	$this->db->where('requestID', $requestID);
		   	$this->db->where('is_processed', '1');
		   	$this->db->where('status', '0');
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0)
		   	$result=$query->row_array();
		   	return $result;
		}

		public function withdraw_request($userID){
			$result=array();
			$this->db->select('requestID, amount, seller_type, cheque, is_processed, transactionID, rejection_reason');
			$this->db->from('tbl_withdrawal');
		   	$this->db->where('seller_id', $userID);
		   	$this->db->where('status', '0');
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function load_transaction_summary($userID){
			$result=array();
			$credit=0; $debit=0; $charge=0;
			$this->db->select('id, transaction_type, credit, debit');
	        $this->db->from('tbl_transaction_records');
	        $this->db->where('seller_id', $userID);
	        $this->db->where('is_complete', '0');
	        $this->db->where('status', '0');
		   	$query = $this->db->get();
	   		if($query->num_rows() > 0){
		   		foreach ($query->result() as $data) {
	                $transaction_type=$data->transaction_type;
	                $credit=$credit+(float)$data->credit;	                
	                if($transaction_type=='charge'){
	                	$charge=$charge+(float)$data->debit;
	                }else{
	                	$debit=$debit+(float)$data->debit;
	                }
	            }
	        }
	        $result['credit']=$credit;
	        $result['debit']=$debit;
	        $result['charge']=$charge;
		   	return $result;
		}

		public function commissions($userID, $sort_by){
			$listArray=array();
	        $this->db->select('id, commission_id, withdraw_id, refund_id, transaction_type, transaction_id, credit, debit');
	        $this->db->from('tbl_transaction_records');
	        $this->db->where('seller_id', $userID);
	        $this->db->where('is_complete', '0');
	        $this->db->where('status', '0');
	        if($sort_by!=""){
				$this->db->where('transaction_type', $sort_by);
			}
	        $this->db->order_by('createdDate', 'ASC');
	        $query = $this->db->get();

		   	if($query->num_rows() > 0){
		   		foreach ($query->result() as $data) {
	                $commission_id=$data->commission_id;
	                $withdraw_id=$data->withdraw_id;
	                $refund_id=$data->refund_id;
	                $transaction_id=$data->transaction_id;
	                $transaction_type=$data->transaction_type;
	                $credit=$data->credit;
	                $debit=$data->debit;

	                $orderID=""; $referral_url=""; $commissionStatus=""; $name=""; $quantity=1; $orderID=""; $seller_type=""; $vendor_url="";
	                if($refund_id!=''){
	                	$this->db->select('orderID, name');
				        $this->db->from('tbl_refunds');
	                	$this->db->join('tbl_products', 'tbl_products.id=tbl_refunds.product', 'LEFT'); 
				        $this->db->where('tbl_refunds.id', $refund_id);
				        $this->db->where('tbl_refunds.status', '0');
				        $query1 = $this->db->get();

					   	if($query1->num_rows() > 0){
				            foreach ($query1->result_array() as $data1) {
				            	$orderID=$data1['orderID'];
				            	$name=$data1['name'];
				            }
				        }
	                }
	                if($commission_id!=''){
	                	$this->db->select('referral_url, amount, commissionStatus, product, cart_id, vendor_url, orderID');
				        $this->db->from('tbl_commissions');	        
				        $this->db->where('id', $commission_id);
				        $this->db->where('seller_id', $userID);
				        $this->db->where('status', '0');
					   	// $this->db->where('commissionStatus!=', '5');
				        $query2 = $this->db->get();

					   	if($query2->num_rows() > 0){
				            foreach ($query2->result() as $data2) {
				                $referral_url=$data2->referral_url;
				                $vendor_url=$data2->vendor_url;
				                $amount=$data2->amount;
				                $orderID=$data2->orderID;
				                $commissionStatus=$data2->commissionStatus;
				                $product=$data2->product;
				                $cart_id=$data2->cart_id;
				                if($cart_id!=""){
					                if($referral_url!=""){
					                	$this->db->select('name, tbl_cart.quantity, orderID, tbl_affiliate_urls.seller_type');
					                	$this->db->from('tbl_affiliate_urls');
					                	$this->db->join('tbl_cart', 'tbl_cart.id='.$cart_id);
					                	$this->db->join('tbl_products', 'tbl_products.id='.$product);
								        $this->db->where('tbl_affiliate_urls.seller_id', $userID);
								        $this->db->where('tbl_affiliate_urls.referral_url', $referral_url);
								        $this->db->where('tbl_cart.status', '0');
								        $this->db->where('tbl_affiliate_urls.status', '0');

								        $sql = $this->db->get();
								        foreach ($sql->result_array() as $rows) {
							                $name=$rows['name'];
							                $quantity=$rows['quantity'];
							                $orderID=$rows['orderID'];
							                $seller_type=$rows['seller_type'];
							            }
					                }else{
					                	$this->db->select('name, tbl_cart.quantity, orderID, seller_type, commission');
					                	$this->db->from('tbl_cart');
					                	$this->db->join('tbl_products', 'tbl_products.id='.$product);
								        $this->db->where('tbl_cart.id', $cart_id);
								        $this->db->where('seller_id', $userID);
								        $this->db->where('tbl_cart.status', '0');

								        $sql = $this->db->get();
								        foreach ($sql->result_array() as $rows) {
							                $name=$rows['name'];
							                $quantity=$rows['quantity'];
							                $orderID=$rows['orderID'];
							                $seller_type=$rows['seller_type'];
							            }
					                }
				                }else{
					                $name="REFUNDED PRODUCT";
				                }
				            }
				        }
	                }

	                $row_array['transaction_type']=$transaction_type;
		        	$row_array['withdraw_id']=$withdraw_id;
		        	$row_array['refund_id']=$refund_id;
                	$row_array['referral_url']=$referral_url;
			        $row_array['seller_id']=$userID;
			        $row_array['credit']=$credit;
			        $row_array['debit']=$debit;
			        $row_array['commissionStatus']=$commissionStatus;
	                $row_array['name']=$name;
	                $row_array['quantity']=$quantity;
	                $row_array['orderID']=$orderID;
	                $row_array['seller_type']=$seller_type;
	                $row_array['vendor_url']=$vendor_url;
	                array_push($listArray, $row_array);
	                
	            }
		   	}
	        return $listArray;
		}

    	public function save_withdrawal_form($data, $userID){
    		$createdDate=time();
			$seller_type=sqlSafe($data['seller_type']);
			$amount=sqlSafe($data['amount']);

    		$this->db->select('id');
	        $this->db->where('is_processed', '1');
	        $this->db->where('seller_id',$userID);
	        $this->db->where('seller_type', $seller_type);
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_withdrawal');

	        if($query->num_rows() > 0){
	            die(ErrorMsg("You have a pending request, which is in process."));
	        }

	        if($data['amount'] > getSellerPendingCommissions($userID)){
	            die(ErrorMsg("You can not request withdraw amount more than your commission balance"));
	        }

	        if($data['amount'] <= 1534){
	            die(ErrorMsg("You don`t have enough balance to withdraw!! Your balance should be above TZS 1534."));
	        }

	        $payment_type=""; $phone=""; $withdrawID="";
	        $this->db->select('account_number');
	        $this->db->where('seller_id', $userID);
	        // $this->db->where('seller_type',$data['seller_type']);
	        $this->db->where('method','mobile');
	        $this->db->where('payment_type','withdraw');
	        $this->db->where('status','0');
	        $query1 = $this->db->get('tbl_seller_payment_info');

	        if($query1->num_rows() > 0){
	            foreach ($query1->result_array() as $data1) {
	            	$phone=$data1['account_number'];
	            	$paymenttype=getMobileMoneyName($phone);
	            	if($paymenttype=='unknown'){
	            		die(ErrorMsg("Unknown phone provider"));
	            	}else{
	            		$payment_type=$paymenttype;
	            	}
	            }
	        }
	        if($payment_type==""){
	        	die(ErrorMsg("You does not have any mobile account for withdraw transaction in ".$data['seller_type']." type, please! set it first or choose different Account Type"));
	        }

	        $requestID=generateRequestID();
            $set=$this->db->insert('tbl_withdrawal', array(
	            'requestID' => $requestID,
	            'seller_id' => $userID,
	            'seller_type' => $seller_type,
	            'amount' => $amount,
	            'status' => '0',
	            'createdBy' => $userID,
	            'createdDate' => $createdDate
	        ));

	        if($set){
	        	$this->db->select('id, amount');
	        	$this->db->where('is_processed', '1');
		        $this->db->where('seller_id', $userID);
	        	$this->db->where('seller_type', $seller_type);
		        $this->db->where('requestID', $requestID);
		        $this->db->where('status','0');
		        $query = $this->db->get('tbl_withdrawal');

		        if($query->num_rows() > 0){
		            foreach ($query->result_array() as $data2) {
		            	$amount=$data2['amount'];
		            	$withdrawID=$data2['id'];

		            	$this->db->insert('tbl_transaction_records', array(
				            'transaction_type' => 'withdraw',
				            'seller_id' => $userID,
				            'withdraw_id' => $withdrawID,
				            'debit' => $amount,
				            'is_complete' => '1',
				            'status' => '0',
				            'createdDate' => $createdDate
				        ));

		            	$this->db->insert('tbl_transaction_records', array(
				            'transaction_type' => 'charge',
				            'seller_id' => $userID,
				            'withdraw_id' => $withdrawID,
				            'debit' => 1534,
				            'is_complete' => '1',
				            'status' => '0',
				            'createdDate' => $createdDate
				        ));
		            }
		        }

				$this->load->model('api/Selcom_model');
 				$send=$this->Selcom_model->push_disbursement("", "", $withdrawID, $payment_type, $phone, $amount, "withdraw", $userID, $seller_type);
 				if($send['msg']=='success'){
 					$transactionID=$send['transactionID'];
 					$set=array(
			            'is_processed' => '0',
			            'transactionID' => $transactionID
			        );

			        $this->db->where('requestID', $requestID);
	        		$this->db->where('is_processed', '1');
	        		$this->db->where('seller_type',$seller_type);
			        $this->db->where('seller_id', $userID);
			        $this->db->where('status', '0');
			     	$this->db->update('tbl_withdrawal', $set);

			     	$this->db->set('is_complete', '0');
	                $this->db->where('withdraw_id', $withdrawID);
			        $this->db->where('debit>0');
			     	$this->db->update('tbl_transaction_records');

			     	// $amount=$amount+1534;
					// deduct_commision($userID, $amount);

					$email="";
					$this->db->select('email');
			        $this->db->where('user_id', $userID);
			        $this->db->where('status','0');
			        $query3 = $this->db->get('tbl_sellers');
			        if($query3->num_rows() > 0){
			            foreach ($query3->result_array() as $data2) {
			            	$email=$data2['email'];
			            }
			        }
					$msg='Your withdraw request (#'.$requestID.') of TZS '.$amount.' has been sent to your mobile withdraw account.';
	                $this->load->library('email');
					$this->email->from('info@getvalue.co', 'Get Value Inc - Transaction');
					$this->email->to($email);
					$this->email->subject('Payment Confirmation');
					$this->email->message($msg);
					$this->email->send();
		        	return 'Success';
 				}else{
 					return ErrorMsg($send['msg']);
 				}
	        }else{
	        	return ErrorMsg($this->db->_error_message());
	        }
		}

    	public function update_withdrawal_form($data, $userID){
			$requestID=sqlSafe($data['requestID']);
			$amount=sqlSafe($data['amount']);
			$seller_type=sqlSafe($data['seller_type']);

    		$this->db->select('id');
	        $this->db->where('is_processed', '1');
	        $this->db->where('seller_id', $userID);
	        $this->db->where('requestID', $requestID);
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_withdrawal');

	        if($query->num_rows() > 0){
	        	foreach ($query->result_array() as $rows) {
	        		$withdrawID=$rows['id'];
	        	}
	            $set=array(
		            'amount' => $amount,
		            'seller_type' => $seller_type
		        );
		        $this->db->where('seller_id', $userID);
		        $this->db->where('requestID', $requestID);
		        $this->db->where('status', '0');
		     	$this->db->update('tbl_withdrawal', $set);

		        $this->db->set('debit', $amount);
		        $this->db->where('seller_id', $userID);
		        $this->db->where('withdraw_id', $withdrawID);
		        $this->db->where('transaction_type', 'withdraw');
		        $this->db->where('status', '0');
		     	$this->db->update('tbl_transaction_records');

		     	return 'Success';
	        }else{
	        	die(ErrorMsg("Invalid request."));
	        }
		}

    }