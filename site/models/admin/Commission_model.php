<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Commission_model extends CI_Model{

	    public function __construct(){
	        parent::__construct();
	        $this->load->database();
    	}

		public function withdraw_request(){
			$result=array();
			$this->db->select('requestID, full_name, amount, seller_type, is_processed, seller_id');
			$this->db->from('tbl_withdrawal');
			$this->db->join('tbl_sellers', 'user_id=seller_id');
		   	$this->db->where('is_processed', '1');
		   	$this->db->where('tbl_withdrawal.status', '0');
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function withdraw_reject(){
			$result=array();
			$this->db->select('requestID, full_name, amount, seller_type, is_processed, seller_id');
			$this->db->from('tbl_withdrawal');
			$this->db->join('tbl_sellers', 'user_id=seller_id');
		   	$this->db->where('is_processed', '2');
		   	$this->db->where('tbl_withdrawal.status', '0');
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function withdrawal_payouts(){
			$result=array();
			$this->db->select('requestID, full_name, amount, seller_type, is_processed, seller_id');
			$this->db->from('tbl_withdrawal');
			$this->db->join('tbl_sellers', 'user_id=seller_id');
		   	$this->db->where('is_processed', '0');
		   	$this->db->where('tbl_withdrawal.status', '0');
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function commission_rates(){
			$result=array();
			$this->db->select('tbl_commission_rates.id, product, amount_fixed, amount_percent, type, tbl_commission_rates.createdDate, tbl_commission_rates.status, name, full_name');
		   	$this->db->from('tbl_commission_rates');
		   	$this->db->join('tbl_products', 'tbl_products.id=product', 'LEFT');
		   	$this->db->join('tbl_sellers', 'tbl_sellers.user_id=tbl_commission_rates.seller_id', 'LEFT');
		   	$this->db->where('tbl_commission_rates.status!=', '1');
		   	$this->db->order_by('tbl_commission_rates.createdDate', 'DESC');
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function load_commission_rate($info){
			$result=array();
			$this->db->select('id, seller_id, product, amount_fixed, amount_percent, type, createdDate, status');
		   	$this->db->where('status!=', '1');
		   	$this->db->where('id', $info);
		   	$this->db->order_by('createdDate', 'DESC');
	   		$query = $this->db->get('tbl_commission_rates');
		   	if($query->num_rows() > 0)
		   	$result = $query->row_array();
		   	return $result;
		}

		public function commission_rates_vendor(){
			$result=array();
			$this->db->select('tbl_commission_rates_v.id, product, amount_fixed, amount_percent, type, tbl_commission_rates_v.createdDate, tbl_commission_rates_v.status, name, full_name');
		   	$this->db->from('tbl_commission_rates_v');
		   	$this->db->join('tbl_products', 'tbl_products.id=product', 'LEFT');
		   	$this->db->join('tbl_sellers', 'tbl_sellers.user_id=tbl_commission_rates_v.seller_id', 'LEFT');
		   	$this->db->where('tbl_commission_rates_v.status!=', '1');
		   	$this->db->order_by('tbl_commission_rates_v.createdDate', 'DESC');
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function load_commission_rate_vendor($info){
			$result=array();
			$this->db->select('id, seller_id, product, amount_fixed, amount_percent, type, createdDate, status');
		   	$this->db->where('status!=', '1');
		   	$this->db->where('id', $info);
		   	$this->db->order_by('createdDate', 'DESC');
	   		$query = $this->db->get('tbl_commission_rates_v');
		   	if($query->num_rows() > 0)
		   	$result = $query->row_array();
		   	return $result;
		}

    	public function save_commission_rate($data, $userID){
    		$createdDate=time();
    		$type=sqlSafe($data['type']);
    		$product=sqlSafe($data['product']);
    		$amount_fixed=sqlSafe($data['amount_fixed']);
    		$amount_percent=sqlSafe($data['amount_percent']);

    		$seller_id="";
    		if($data['type']=='vendor' && (isset($data['vendor']) && $data['vendor']!="")){
    			$seller_id=sqlSafe($data['vendor']);
    		}elseif($data['type']=='insider' && (isset($data['insider']) && $data['insider']!="")){
    			$seller_id=sqlSafe($data['insider']);
    		}elseif($data['type']=='outsider' && (isset($data['outsider']) && $data['outsider']!="")){
    			$seller_id=sqlSafe($data['outsider']);
    		}elseif($data['type']=='contributor' && (isset($data['contributor']) && $data['contributor']!="")){
    			$seller_id=sqlSafe($data['contributor']);
    		}
    		$this->db->select('id');
	        $this->db->where('type',$type);
	        $this->db->where('seller_id',$seller_id);
	        $this->db->where('product',$product);
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_commission_rates');

	        if($query->num_rows() > 0){
	            die(ErrorMsg("This commission rate already exist"));
	        }

            $set=$this->db->insert('tbl_commission_rates', array(
	            'type' => $type,
	            'seller_id' => $seller_id,
	            'product' => $product,
	            'amount_fixed' => $amount_fixed,
	            'amount_percent' => $amount_percent,
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

    	public function save_vendor_commission_rate($data, $userID){
    		$createdDate=time();
    		$type=sqlSafe($data['type']);
    		$product=sqlSafe($data['product']);
    		$amount_fixed=sqlSafe($data['amount_fixed']);
    		$amount_percent=sqlSafe($data['amount_percent']);

    		$seller_id="";
    		if($data['type']=='vendor' && (isset($data['vendor']) && $data['vendor']!="")){
    			$seller_id=sqlSafe($data['vendor']);
    		}elseif($data['type']=='insider' && (isset($data['insider']) && $data['insider']!="")){
    			$seller_id=sqlSafe($data['insider']);
    		}elseif($data['type']=='outsider' && (isset($data['outsider']) && $data['outsider']!="")){
    			$seller_id=sqlSafe($data['outsider']);
    		}elseif($data['type']=='contributor' && (isset($data['contributor']) && $data['contributor']!="")){
    			$seller_id=sqlSafe($data['contributor']);
    		}
    		$this->db->select('id');
	        $this->db->where('type',$type);
	        $this->db->where('seller_id',$seller_id);
	        $this->db->where('product',$product);
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_commission_rates_v');

	        if($query->num_rows() > 0){
	            die(ErrorMsg("This commission rate already exist"));
	        }

            $set=$this->db->insert('tbl_commission_rates_v', array(
	            'type' => $type,
	            'seller_id' => $seller_id,
	            'product' => $product,
	            'amount_fixed' => $amount_fixed,
	            'amount_percent' => $amount_percent,
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

		public function update_commission_rate($info, $data){
    		$type=sqlSafe($data['type']);
    		$product=sqlSafe($data['product']);
    		$amount_fixed=sqlSafe($data['amount_fixed']);
    		$amount_percent=sqlSafe($data['amount_percent']);

			$seller_id="";
    		if($data['type']=='vendor' && (isset($data['vendor']) && $data['vendor']!="")){
    			$seller_id=sqlSafe($data['vendor']);
    		}elseif($data['type']=='insider' && (isset($data['insider']) && $data['insider']!="")){
    			$seller_id=sqlSafe($data['insider']);
    		}elseif($data['type']=='outsider' && (isset($data['outsider']) && $data['outsider']!="")){
    			$seller_id=sqlSafe($data['outsider']);
    		}elseif($data['type']=='contributor' && (isset($data['contributor']) && $data['contributor']!="")){
    			$seller_id=sqlSafe($data['contributor']);
    		}

    		$set=array(
	            'type' => $type,
	            'seller_id' => $seller_id,
	            'product' => $product,
	            'amount_fixed' => $amount_fixed,
	            'amount_percent' => $amount_percent
	        );
	        $this->db->where('id', $info);
	     	$this->db->update('tbl_commission_rates', $set);
	        return 'Success';
		}

		public function update_vendor_commission_rate($info, $data){
    		$type=sqlSafe($data['type']);
    		$product=sqlSafe($data['product']);
    		$amount_fixed=sqlSafe($data['amount_fixed']);
    		$amount_percent=sqlSafe($data['amount_percent']);

			$seller_id="";
    		if($data['type']=='vendor' && (isset($data['vendor']) && $data['vendor']!="")){
    			$seller_id=sqlSafe($data['vendor']);
    		}elseif($data['type']=='insider' && (isset($data['insider']) && $data['insider']!="")){
    			$seller_id=sqlSafe($data['insider']);
    		}elseif($data['type']=='outsider' && (isset($data['outsider']) && $data['outsider']!="")){
    			$seller_id=sqlSafe($data['outsider']);
    		}elseif($data['type']=='contributor' && (isset($data['contributor']) && $data['contributor']!="")){
    			$seller_id=sqlSafe($data['contributor']);
    		}

    		$set=array(
	            'type' => $type,
	            'seller_id' => $seller_id,
	            'product' => $product,
	            'amount_fixed' => $amount_fixed,
	            'amount_percent' => $amount_percent
	        );
	        $this->db->where('id', $info);
	     	$this->db->update('tbl_commission_rates_v', $set);
	        return 'Success';
		}

		public function delete_commission_rate($info){
			$this->db->set('status', '1');
	   		$this->db->where('id', $info);
	     	$this->db->update('tbl_commission_rates');
		   	return 'Success';
		}

		public function activate_commission_rate($info){
			$this->db->set('status', '0');
	   		$this->db->where('id', $info);
	     	$this->db->update('tbl_commission_rates');
		   	return 'Success';
		}

		public function diactivate_commission_rate($info){
			$this->db->set('status', '2');
	   		$this->db->where('id', $info);
	     	$this->db->update('tbl_commission_rates');
		   	return 'Success';
		}

		public function delete_vendor_commission_rate($info){
			$this->db->set('status', '1');
	   		$this->db->where('id', $info);
	     	$this->db->update('tbl_commission_rates_v');
		   	return 'Success';
		}

		public function activate_vendor_commission_rate($info){
			$this->db->set('status', '0');
	   		$this->db->where('id', $info);
	     	$this->db->update('tbl_commission_rates_v');
		   	return 'Success';
		}

		public function diactivate_vendor_commission_rate($info){
			$this->db->set('status', '2');
	   		$this->db->where('id', $info);
	     	$this->db->update('tbl_commission_rates_v');
		   	return 'Success';
		}

		public function load_seller_transaction_balance(){
			$result=array();
			$this->db->select('full_name, gender, phone, email, user_id');
	        $this->db->from('tbl_sellers');
	        $this->db->where('status', '0');
	        $this->db->order_by('createdDate', 'DESC');
		   	$query1 = $this->db->get();
	   		if($query1->num_rows() > 0){
		   		foreach ($query1->result() as $data1) {
		   			$list=array();
	                $seller_id=$data1->user_id;
	                $full_name=$data1->full_name;
	                $gender=$data1->gender;
	                $phone=$data1->phone;
	                $email=$data1->email;
	                $credit=0; $debit=0; $charge=0; $transaction_type="";

	                $this->db->select('id, transaction_type, credit, debit');
			        $this->db->from('tbl_transaction_records');
			        $this->db->where('seller_id', $seller_id);
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
			        if($transaction_type!=""){
		                $list['full_name']=$full_name;
		                $list['gender']=$gender;
		                $list['phone']=$phone;
		                $list['email']=$email;
		                $list['credit']=$credit;
		                $list['debit']=$debit;
		                $list['charge']=$charge;
		                $list['transaction_type']=$transaction_type;
		                array_push($result, $list);
		            }
	            }
	        }
		   	return $result;
		}

		/*TEMPORARY FAST EXCUTION*/

		function move_commissions(){
			$this->db->select('tbl_commissions.id, tbl_commissions.amount, tbl_commissions.seller_id, tbl_transactions.id as transaction_id');
	        $this->db->from('tbl_commissions');	        
	        $this->db->join('tbl_transactions', 'tbl_transactions.orderID=tbl_commissions.orderID AND tbl_transactions.status="0" AND tbl_transactions.is_complete="0" AND tbl_transactions.orderID<>"" AND (transactionType="" OR transactionType="payment") AND customer<>"" AND refund_id=""');
	        $this->db->where('tbl_commissions.status', '0');
		   	$this->db->where('commissionStatus', '0');
		   	$this->db->where('tbl_commissions.orderID!=', '');
			/*$this->db->select('tbl_commissions.id, tbl_commissions.amount, tbl_commissions.seller_id');
	        $this->db->from('tbl_commissions');
	        $this->db->where('tbl_commissions.status', '0');
		   	$this->db->where('commissionStatus', '1');
		   	$this->db->where('tbl_commissions.orderID!=', '');*/
	        $query = $this->db->get();

	        $counter=$query->num_rows();
	        $total=0;
		   	if($query->num_rows() > 0){
	            foreach ($query->result() as $data) {
	                $commission_id=$data->id;
	                $transaction_id=$data->transaction_id;
	                $amount=$data->amount;
	                $seller_id=$data->seller_id;
	                $total=$total+$amount;

	                $this->db->select('id');
			        $this->db->from('tbl_transaction_records');
			        $this->db->where('is_complete', '0');
			        $this->db->where('status', '0');
			        $this->db->where('transaction_type', 'commission');
			        $this->db->where('seller_id', $seller_id);
			        $this->db->where('commission_id', $commission_id);
			        $this->db->where('transaction_id', $transaction_id);
			        $query1 = $this->db->get();

				   	if($query1->num_rows() == 0){
		                $this->db->insert('tbl_transaction_records', array(
				            'transaction_type' => 'commission',
				            'seller_id' => $seller_id,
				            'commission_id' => $commission_id,
				            'transaction_id' => $transaction_id,
				            'credit' => $amount,
				            'is_complete' => '0',
				            'status' => '0',
				            'createdDate' => time()
				        ));
		            }
	            }
	        }
	        die("Total Commission: ".$counter."Total Amount: ".$total);

	        /*
	        INSERT INTO `tbl_transaction_records`(`id`, `transaction_type`, `seller_id`, `commission_id`, `refund_id`, `withdraw_id`, `transaction_id`, `credit`, `debit`, `is_complete`, `status`, `createdBy`, `createdDate`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7],[value-8],[value-9],[value-10],[value-11],[value-12],[value-13])*/
		}

		function move_withdraw(){
			$this->db->select('tbl_withdrawal.id, tbl_withdrawal.amount, tbl_withdrawal.seller_id, tbl_transactions.id as transaction_id');
	        $this->db->from('tbl_withdrawal');	        
	        $this->db->join('tbl_transactions', 'tbl_transactions.withdraw_id=tbl_withdrawal.id AND tbl_transactions.status="0" AND tbl_transactions.is_complete="0" AND transactionType="withdraw" AND withdraw_id<>""');
	        $this->db->where('tbl_withdrawal.status', '0');
		   	$this->db->where('is_processed', '0');
		   	// $this->db->where('tbl_withdrawal.transactionID!=', '');
		   	
	        $query = $this->db->get();
	        /*$query = $this->db->query("SELECT * FROM `tbl_withdrawal` WHERE status='0' AND is_processed='0' AND id IN (SELECT withdraw_id FROM tbl_transactions WHERE status='0' AND provider='selcom' AND is_complete='0' AND (withdraw_id<>'' OR transactionType='withdraw'))");*/

	        $counter=$query->num_rows();
	        $total=0;
		   	if($query->num_rows() > 0){
	            foreach ($query->result() as $data) {
	                $withdraw_id=$data->id;
	                $transaction_id=$data->transaction_id;
	                $amount=$data->amount;
	                $seller_id=$data->seller_id;
	                $total=$total+$amount;

	                $this->db->select('id');
			        $this->db->from('tbl_transaction_records');
			        $this->db->where('is_complete', '0');
			        $this->db->where('status', '0');
			        $this->db->where('transaction_type', 'withdraw');
			        $this->db->where('seller_id', $seller_id);
			        $this->db->where('withdraw_id', $withdraw_id);
			        $this->db->where('transaction_id', $transaction_id);
			        $query1 = $this->db->get();

				   	if($query1->num_rows() == 0){
		                $this->db->insert('tbl_transaction_records', array(
				            'transaction_type' => 'withdraw',
				            'seller_id' => $seller_id,
				            'withdraw_id' => $withdraw_id,
				            'transaction_id' => $transaction_id,
				            'debit' => $amount,
				            'is_complete' => '0',
				            'status' => '0',
				            'createdDate' => time()
				        ));
		            }
	            }
	        }
	        die("Total Commission: ".$counter."Total Amount: ".$total);

	        /*
	        INSERT INTO `tbl_transaction_records`(`id`, `transaction_type`, `seller_id`, `commission_id`, `refund_id`, `withdraw_id`, `transaction_id`, `credit`, `debit`, `is_complete`, `status`, `createdBy`, `createdDate`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7],[value-8],[value-9],[value-10],[value-11],[value-12],[value-13])*/
		}

		function move_refund(){
			$this->db->select('tbl_refunds.id, tbl_commissions.amount, tbl_commissions.seller_id, tbl_transactions.id as transaction_id');
	        $this->db->from('tbl_refunds');	        
	        $this->db->join('tbl_transactions', 'tbl_transactions.refund_id=tbl_refunds.id AND tbl_transactions.status="0" AND tbl_transactions.is_complete="0" AND transactionType="refund" AND refund_id<>""');
	        $this->db->join('tbl_commissions', 'tbl_commissions.orderID=tbl_refunds.orderID AND commissionStatus="3" AND tbl_commissions.status="0"');
	        $this->db->where('tbl_refunds.status', '0');
		   	$this->db->where('is_processed', '0');
		   	$this->db->where('tbl_refunds.transactionID!=', '');
		   	
	        $query = $this->db->get();
	        /*$query = $this->db->query("SELECT * FROM `tbl_withdrawal` WHERE status='0' AND is_processed='0' AND id IN (SELECT withdraw_id FROM tbl_transactions WHERE status='0' AND provider='selcom' AND is_complete='0' AND (withdraw_id<>'' OR transactionType='withdraw'))");*/

	        $counter=$query->num_rows();
	        $total=0;
		   	if($query->num_rows() > 0){
	            foreach ($query->result() as $data) {
	                $refund_id=$data->id;
	                $transaction_id=$data->transaction_id;
	                $amount=$data->amount+1534;
	                $seller_id=$data->seller_id;
	                $total=$total+$amount;

	                $this->db->select('id');
			        $this->db->from('tbl_transaction_records');
			        $this->db->where('is_complete', '0');
			        $this->db->where('status', '0');
			        $this->db->where('transaction_type', 'refund');
			        $this->db->where('seller_id', $seller_id);
			        $this->db->where('refund_id', $refund_id);
			        $this->db->where('transaction_id', $transaction_id);
			        $query1 = $this->db->get();

				   	if($query1->num_rows() == 0){
		                $this->db->insert('tbl_transaction_records', array(
				            'transaction_type' => 'refund',
				            'seller_id' => $seller_id,
				            'refund_id' => $refund_id,
				            'transaction_id' => $transaction_id,
				            'debit' => $amount,
				            'is_complete' => '0',
				            'status' => '0',
				            'createdDate' => time()
				        ));
		            }
	            }
	        }
	        die("Total Commission: ".$counter."Total Amount: ".$total);

	        /*
	        INSERT INTO `tbl_transaction_records`(`id`, `transaction_type`, `seller_id`, `commission_id`, `refund_id`, `withdraw_id`, `transaction_id`, `credit`, `debit`, `is_complete`, `status`, `createdBy`, `createdDate`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7],[value-8],[value-9],[value-10],[value-11],[value-12],[value-13])*/
		}
		/*END OF TEMPORARY FAST EXCUTION*/

		public function commissions($userID, $sort_by){
			// $this->move_commissions();
			// $this->move_withdraw();
			// $this->move_refund();
	        $listArray=array();
	        $this->db->select('id, commission_id, withdraw_id, refund_id, transaction_type, transaction_id, credit, debit, seller_id');
	        $this->db->from('tbl_transaction_records');
	        $this->db->where('is_complete', '0');
	        $this->db->where('status', '0');
	        if($sort_by!=""){
				$this->db->where('transaction_type', $sort_by);
			}
	        $this->db->order_by('createdDate', 'ASC');
	        $query = $this->db->get();

		   	if($query->num_rows() > 0){
		   		foreach ($query->result() as $data) {
	                $seller_id=$data->seller_id;
	                $commission_id=$data->commission_id;
	                $withdraw_id=$data->withdraw_id;
	                $refund_id=$data->refund_id;
	                $transaction_id=$data->transaction_id;
	                $transaction_type=$data->transaction_type;
	                $credit=$data->credit;
	                $debit=$data->debit;

	                $seller_name="";
	                $this->db->select('full_name');
			        $this->db->from('tbl_sellers');
			        $this->db->where('user_id', $seller_id);
			        $this->db->where('status', '0');
				   	$query3 = $this->db->get();
			   		if($query3->num_rows() > 0){
				   		foreach ($query3->result() as $data3) {
			                $seller_name=$data3->full_name;
			            }
			        }

	                $orderID=""; $referral_url=""; $commissionStatus=""; $name=""; $quantity=1; $orderID=""; $seller_type=""; $vendor_url="";
	                if($refund_id!=''){
	                	$this->db->select('orderID, name');
				        $this->db->from('tbl_refunds');
	                	$this->db->join('tbl_products', 'tbl_products.id=tbl_refunds.product', 'LEFT');       
				        $this->db->where('tbl_refunds.id', $refund_id);
				        $this->db->where('tbl_refunds.status', '0');
				        $query = $this->db->get();

					   	if($query->num_rows() > 0){
				            foreach ($query->result_array() as $data1) {
				            	$orderID=$data1['orderID'];
				            	$name=$data1['name'];
				            }
				        }
	                }
	                if($commission_id!=''){
	                	$this->db->select('referral_url, amount, commissionStatus, product, cart_id, vendor_url, orderID');
				        $this->db->from('tbl_commissions');	        
				        $this->db->where('id', $commission_id);
				        $this->db->where('seller_id', $seller_id);
				        $this->db->where('status', '0');
					   	$this->db->where('commissionStatus!=', '5');
					   	// $this->db->order_by('createdDate', 'DESC');
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
								        $this->db->where('tbl_affiliate_urls.seller_id', $seller_id);
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
								        $this->db->where('seller_id', $seller_id);
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

                	$row_array['seller']=$seller_name;
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

		public function save_withdrawal_request($data, $userID){
			$seller_id=sqlSafe($data['seller']);
			$requestID=sqlSafe($data['requestID']);

			$amount=""; $payment_type=""; $phone=""; $withdrawID="";
			$is_paid="0"; $transactionID="";
			if($data['status']=='0'){
				$this->db->select('id, amount');
	        	$this->db->where('is_processed', '1');
		        $this->db->where('seller_id', $seller_id);
		        $this->db->where('requestID', $requestID);
		        $this->db->where('status','0');
		        $query = $this->db->get('tbl_withdrawal');

		        if($query->num_rows() > 0){
		            foreach ($query->result_array() as $data2) {
		            	$amount=$data2['amount'];
		            	$withdrawID=$data2['id'];
		            }
		        }
		        if($amount > getSellerPendingCommissions($seller_id)){
		            die(ErrorMsg("You can not approve withdraw request amount more than the commission balance"));
		        }

		        if($amount <= 1534){
		            die(ErrorMsg("You don`t have enough balance to withdraw!! Your balance should be above TZS 1534."));
		        }
		        // die("request ID: ".$requestID."ID: ".$withdrawID." Amount: ".$amount);
				$this->db->select('account_number');
		        $this->db->where('seller_id', $seller_id);
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
		        	die(ErrorMsg("Seller does not have any mobile account for withdraw transaction"));
		        }

				$this->load->model('api/Selcom_model');
 				$send=$this->Selcom_model->push_disbursement("", "", $withdrawID, $payment_type, $phone, $amount, "withdraw", $userID, "admin");
 				if($send['msg']=='success'){
 					$is_paid="1";
 					$transactionID=$send['transactionID'];
 				}else{
 					$is_paid=ErrorMsg($send['msg']);
 				}
			}else{
				$is_paid="1";
			}

			if($is_paid=="1"){
	            $set=array(
		            'is_processed' => sqlSafe($data['status']),
		            'rejection_reason' => sqlSafe($data['reason']),
		            'transactionID' => $transactionID
		        );

		        $this->db->where('requestID', $requestID);
		        $this->db->where('seller_id', $seller_id);
		        $this->db->where('status', '0');
		     	$this->db->update('tbl_withdrawal', $set);

		     	$this->db->set('is_complete', '0');
                $this->db->where('withdraw_id', $withdrawID);
		        $this->db->where('debit>0');
		     	$this->db->update('tbl_transaction_records');
		     	/*$amount=$amount+1534;
				deduct_commision($seller_id, $amount);*/

				$email="";
				$this->db->select('email');
		        $this->db->where('user_id', $seller_id);
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
	        	return $is_paid;
	        }
		}

		public function pay_seller_commission($infoID, $userID){
			/*$this->db->set('commissionStatus', '0');
	   		$this->db->where('id', $infoID);
	   		$this->db->where('status', '0');
	     	$this->db->update('tbl_commissions');*/
		   	return 'Success';
		}

		public function check_new_withdraw_request(){
			$res=0;
			$this->db->select('id');
			$this->db->from('tbl_withdrawal');
		   	$this->db->where('is_processed', '1');
		   	$this->db->where('status', '0');
	   		$sql = $this->db->get();   	
		   	$res=$sql->num_rows();
		   	return $res;
		}

    }
