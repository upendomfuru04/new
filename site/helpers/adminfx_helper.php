<?php
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    function loadVendors($vendor){
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('user_id, full_name');
        $ci->db->where('is_vendor', '1');
        $ci->db->where('status', '0');
        $query = $ci->db->get('tbl_sellers');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                if($vendor==$data['user_id']){
                    echo '<option value="'.$data['user_id'].'" selected>'.ucwords($data['full_name']).'</option>';
                }else{
                    echo '<option value="'.$data['user_id'].'">'.ucwords($data['full_name']).'</option>';
                }
            }
        }
    }

    function getProductReviewCounter($item){
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('id');
        $ci->db->where('product',$item);
        $ci->db->where('status', '0');
        $query = $ci->db->get('tbl_product_reviews');
        return $query->num_rows();
    }

    function isRefunded($orderID){
        $res=false;
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('id');
        $ci->db->where('status', '0');
        $ci->db->where('is_processed', '0');
        $ci->db->where('orderID', $orderID);
        $query = $ci->db->get('tbl_refunds');

        if($query->num_rows() > 0){
            $res=true;
        }
        return $res;
    }

    function loadAllProducts($product=""){
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('id, name');
        $ci->db->where('status', '0');
        $query = $ci->db->get('tbl_products');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                if($data['id']==$product){
                    echo '<option value="'.$data['id'].'" selected>'.ucwords($data['name']).'</option>';
                }else{
                    echo '<option value="'.$data['id'].'">'.ucwords($data['name']).'</option>';
                }
            }
        }
    }

    function generateCouponCode(){
        $coupon_code=getRandomMixedCode(7);
        $valid=false;
        $ci =& get_instance();
        $ci->load->database();        
        while(!$valid){
            $ci->db->select('id');
            $ci->db->where('coupon_code', $coupon_code);
            $query = $ci->db->get('tbl_seller_coupons');
            if($query->num_rows()==0){
                $valid=true;
            }else{
                $coupon_code=getRandomMixedCode(7);
            }
        }
        return $coupon_code;
    }

    function loadInsiders($insider){
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('user_id, full_name');
        $ci->db->where('is_insider', '1');
        $ci->db->where('status', '0');
        $query = $ci->db->get('tbl_sellers');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                if($insider==$data['user_id']){
                    echo '<option value="'.$data['user_id'].'" selected>'.ucwords($data['full_name']).'</option>';
                }else{
                    echo '<option value="'.$data['user_id'].'">'.ucwords($data['full_name']).'</option>';
                }
            }
        }
    }

    function loadOutsiders($outsider){
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('user_id, full_name');
        $ci->db->where('is_outsider', '1');
        $ci->db->where('status', '0');
        $query = $ci->db->get('tbl_sellers');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                if($outsider==$data['user_id']){
                    echo '<option value="'.$data['user_id'].'" selected>'.ucwords($data['full_name']).'</option>';
                }else{
                    echo '<option value="'.$data['user_id'].'">'.ucwords($data['full_name']).'</option>';
                }
            }
        }
    }

    function loadContributors($contributor){
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('user_id, full_name');
        $ci->db->where('is_contributor', '1');
        $ci->db->where('status', '0');
        $query = $ci->db->get('tbl_sellers');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                if($contributor==$data['user_id']){
                    echo '<option value="'.$data['user_id'].'" selected>'.ucwords($data['full_name']).'</option>';
                }else{
                    echo '<option value="'.$data['user_id'].'">'.ucwords($data['full_name']).'</option>';
                }
            }
        }
    }

    function totalSellerCommission($userID){
        $total_balance=0;
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('amount, quantity');
        $ci->db->from('tbl_commissions');
        $ci->db->join('tbl_cart', 'tbl_cart.id=cart_id');
        $ci->db->where('tbl_commissions.seller_id', $userID);
        $ci->db->where('tbl_cart.status', '0');
        $ci->db->where('tbl_commissions.commissionStatus', '1');
        $ci->db->where('tbl_commissions.status', '0');
        
        $query = $ci->db->get();          
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $total_balance=$total_balance+($data['quantity']*(float)$data['amount']);
            }
        }
        return $total_balance;
    }

    /*function getCustomerFullName($user_id){
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('first_name, surname');
        $ci->db->where('user_id',$user_id);
        $query = $ci->db->get('tbl_user_info');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                return $data['first_name'].' '.$data['surname'];
            }
        }
    }*/

    function loadSellerAccounts($userID, $seller){
        $list="";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('is_insider, is_outsider, is_vendor, is_contributor');
        $ci->db->where('user_id', $userID);
        $query = $ci->db->get('tbl_sellers');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $insSelect=""; $outSelect=""; $venSelect=""; $conSelect="";
                if($seller=='insider'){ $insSelect='selected'; }
                if($seller=='outsider'){ $outSelect='selected'; }
                if($seller=='contributor'){ $conSelect='selected'; }
                if($seller=='vendor'){ $venSelect='selected'; }

                if($data['is_insider']=='1'){
                    $list.='<option value="insider" '.$insSelect.'>Insider</option>';
                }
                if($data['is_outsider']=='1'){
                    $list.='<option value="outsider" '.$outSelect.'>Outsider</option>';
                }
                if($data['is_vendor']=='1'){
                    $list.='<option value="vendor" '.$venSelect.'>Vendor</option>';
                }
                if($data['is_contributor']=='1'){
                    $list.='<option value="contributor" '.$conSelect.'>Contributor</option>';
                }
            }
        }
        echo $list;
    }

    function loadAffiliateTypes($userID, $seller){
        $list="";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('is_insider, is_outsider, is_contributor');
        $ci->db->where('user_id', $userID);
        $query = $ci->db->get('tbl_sellers');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $insSelect=""; $outSelect=""; $venSelect=""; $conSelect="";
                if($seller=='insider'){ $insSelect='selected'; }
                if($seller=='outsider'){ $outSelect='selected'; }
                if($seller=='contributor'){ $conSelect='selected'; }

                if($data['is_insider']=='1'){
                    $list.='<option value="insider" '.$insSelect.'>Insider</option>';
                }
                if($data['is_outsider']=='1'){
                    $list.='<option value="outsider" '.$outSelect.'>Outsider</option>';
                }
                if($data['is_contributor']=='1'){
                    $list.='<option value="contributor" '.$conSelect.'>Contributor</option>';
                }
            }
        }
        echo $list;
    }

    function getRefundStatus($orderID){
        $res="";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('id, is_processed');
        $ci->db->where('status', '0');
        // $ci->db->where('is_processed', '0');
        $ci->db->where('orderID', $orderID);
        $query = $ci->db->get('tbl_refunds');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                if($data['is_processed']=='1'){
                    $res="Requested";
                }elseif($data['is_processed']=='0'){
                    $res="Refunded";
                }
            }
        }else{
            $res="None";
        }
        return $res;
    }

    function loadProducts($seller_id, $product=""){
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('id, name');
        $ci->db->where('seller_id', $seller_id);
        if($product!=""){
            $ci->db->where('id', $product);
        }
        $ci->db->where('status', '0');
        $query = $ci->db->get('tbl_products');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                if($data['id']==$product){
                    echo '<option value="'.$data['id'].'" selected>'.ucwords($data['name']).'</option>';
                }else{
                    echo '<option value="'.$data['id'].'">'.ucwords($data['name']).'</option>';
                }
            }
        }
    }

    function getItemID($product_url){
        $res="";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('id');
        $ci->db->from('tbl_products');
        $ci->db->where('product_url', $product_url);
        $query = $ci->db->get();

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $res=$data['id'];
            }
        }
        return $res;
    }

    function getOrderPaymentDate($orderID){
        $res="";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('transactionDate');
        $ci->db->from('tbl_transactions');
        $ci->db->where('orderID', $orderID);
        $query = $ci->db->get();
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $res=date("d M, Y H:i", strtotime($data['transactionDate']));
            }
        }
        return $res;
    }

    function generateReferralUrl($seller_id, $seller_type, $product_link){
        $profile_url=""; $new_url="";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('profile_url');
        $ci->db->where('user_id',$seller_id);
        $ci->db->where('status', '0');
        $query = $ci->db->get('tbl_sellers');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $profile_url=$data['profile_url'];
            }
        }

        if($profile_url!=""){
            $url=$product_link.'/?ref='.$profile_url;
            $new_url=$url;
            $counter=0;
            $valid=false;
            $ci =& get_instance();
            $ci->load->database();        
            while(!$valid){
                $ci->db->select('id');
                $ci->db->where('referral_url', $new_url);
                $query = $ci->db->get('tbl_affiliate_urls');
                if($query->num_rows()==0){
                    $valid=true;
                }else{
                    $counter++;
                    $new_url=$url.''.$counter;
                }
            }
        }        
        return $new_url;
    }

    function getSellerPendingCommissions($seller_id){
        $ci =& get_instance();
        $ci->load->database();
        $total_balance=0;
        $credit=0; $debit=0;
        $ci->db->select('credit, debit');
        $ci->db->from('tbl_transaction_records');
        $ci->db->where('seller_id', $seller_id);
        $ci->db->where('is_complete', '0');
        $ci->db->where('status', '0');
        $query = $ci->db->get();
        if($query->num_rows() > 0){
            foreach ($query->result() as $data) {
                $credit=$credit+(float)$data->credit;
                $debit=$debit+(float)$data->debit;
            }
        }
        $total_balance=$credit-$debit;
        return ($total_balance);
    }

    function generateRequestID(){
        $requestID=getRandomNumber(6);
        $valid=false;
        $ci =& get_instance();
        $ci->load->database();        
        while(!$valid){
            $ci->db->select('id');
            $ci->db->where('requestID', $requestID);
            $query = $ci->db->get('tbl_withdrawal');
            if($query->num_rows()==0){
                $valid=true;
            }else{
                $requestID=getRandomNumber(6);
            }
        }
        return $requestID;
    }