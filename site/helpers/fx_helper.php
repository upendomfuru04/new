<?php
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    function getUserID($token){
        $user_id="";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('id');
        $ci->db->where('token',$token);
        $ci->db->where('status', '0');
        $query = $ci->db->get('tbl_users');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $user_id=$data['id'];
            }
        }
        return $user_id;
    }

    function getApiUserID($download_token){
        $user_id="";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('id');
        $ci->db->where('user_download_token', $download_token);
        $ci->db->where('status', '0');
        $query = $ci->db->get('tbl_users');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $user_id=$data['id'];
            }
        }
        return $user_id;
    }

    function getApiAccountType($download_token){
        $account_type="";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('account_type');
        $ci->db->where('user_download_token', $download_token);
        $ci->db->where('status', '0');
        $query = $ci->db->get('tbl_users');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $account_type=$data['account_type'];
            }
        }
        return $account_type;
    }

    function isLoggedIn(){
        if(isset($_SESSION['getvalue_user_idetification']) && $_SESSION['getvalue_user_idetification']!=""){
            return true;
        }else{
            return false;
        }
    }

    function recordTrails($table, $tableKey, $desc, $username=''){
        $ci =& get_instance();
        $ci->load->database();
        $actionDate=date('d-m-Y H:i:s');
        $data = array(
            'action_table' => $table,
            'primary_key_table' => $tableKey,
            'description' => $desc,
            'action_by' => $username,
            'action_date' => $actionDate
        );
        $ci->db->insert('z_audit_trails', $data);
    }

    function getAdminInfo($user_id){
        $ci =& get_instance();
        $ci->load->database();
        $res=array(); $name=""; $avatar='default.png';
        $ci->db->select('first_name, surname, avatar');
        $ci->db->where('user_id',$user_id);
        $query = $ci->db->get('tbl_admin');
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $name=$data['first_name'].' '.$data['surname'];
                if($data['avatar']!=""){
                    $avatar=$data['avatar'];
                }
            }
        }
        $res['name']=$name;
        $res['avatar']=$avatar;
        return $res;
    }

    function getSellerInfo($user_id){
        $ci =& get_instance();
        $ci->load->database();
        $res=array(); $name=""; $avatar="";
        $ci->db->select('full_name, avatar');
        $ci->db->where('user_id',$user_id);
        $query = $ci->db->get('tbl_sellers');
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $name=$data['full_name'];
                $avatar='seller_avatars/default.png';
                if($data['avatar']!=""){
                    $avatar='seller_avatars/'.$data['avatar'];
                }
            }
        }
        $res['name']=$name;
        $res['avatar']=$avatar;
        return $res;
    }

    function getOrderProductStatus($orderID){
        $status="live";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('item_id');
        $ci->db->where('orderID', $orderID);
        $ci->db->where('status', '0');
        $query = $ci->db->get('tbl_cart');
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $item_id=$data['item_id'];
                $ci->db->select('product_status');
                $ci->db->where('id', $item_id);
                $ci->db->where('status', '0');
                $query = $ci->db->get('tbl_products');
                if($query->num_rows() > 0){
                    foreach ($query->result_array() as $data) {
                        if(strtolower($data['product_status'])=='preorder'){
                            $status='preorder';
                        }
                    }
                }
            }
        }
        return $status;
    }

    function getRefundPeriod(){
        $period="";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('period');
        $ci->db->where('status', '0');
        $query = $ci->db->get('tbl_refund_setting');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $period=$data['period'];
            }
        }
        return $period;
    }

    function getOrderTotalCost($orderID){
        $cost=0;
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('price, quantity');
        $ci->db->where('orderID', $orderID);
        $ci->db->where('status', '0');
        /*$where="is_complete='1' OR is_complete='0'";
        $ci->db->where($where);*/
        $query = $ci->db->get('tbl_cart');
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $cost=$cost+($data['price']*$data['quantity']);
            }
        }
        return $cost;
    }

    function getOrderCartCounter($orderID){
        $quantity=0;
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('quantity');
        $ci->db->where('orderID', $orderID);
        $ci->db->where('status', '0');
        /*$where="is_complete='1' OR is_complete='0'";
        $ci->db->where($where);*/
        $query = $ci->db->get('tbl_cart');
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $quantity=$quantity+$data['quantity'];
            }
        }
        return $quantity;
    }

    function getUserBrowser(){
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $name = 'NA';
        if (preg_match('/MSIE/i', $agent) && !preg_match('/Opera/i', $agent)) {
            $name = 'Internet Explorer';
        } elseif (preg_match('/Firefox/i', $agent)) {
            $name = 'Mozilla Firefox';
        } elseif (preg_match('/Chrome/i', $agent)) {
            $name = 'Google Chrome';
        } elseif (preg_match('/Safari/i', $agent)) {
            $name = 'Apple Safari';
        } elseif (preg_match('/Opera/i', $agent)) {
            $name = 'Opera';
        } elseif (preg_match('/Netscape/i', $agent)) {
            $name = 'Netscape';
        } else{
            $name = 'Unknown';
        }
        return $name;
    }

    function new_browser($browser){
        $ci =& get_instance();
        $ci->load->database();
        $counter=1;
        $browser=trim($browser);
        $ci->db->select('id');
        $ci->db->from('site_browsers');
        $ci->db->where('browser', $browser);
        $ci->db->where('status', '0');        
        $query = $ci->db->get();
        if($query->num_rows() > 0){
            
        }else{
            $ci->db->insert('site_browsers', array(
                'browser' => $browser,
                'status' => '0'
            ));
        }
    }

    function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

	function new_pageView($page){
        $ci =& get_instance();
        $ci->load->database();
        $counter=1;
        $page=trim($page);
        $ci->db->select('id, counter');
        $ci->db->from('site_views');
        $ci->db->where('page', $page);
        $ci->db->where('status', '0');
        
        $query = $ci->db->get();          
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $counter=$data['counter']+1;
                $ci->db->set('counter', $counter);
                $ci->db->where('status', '0');
                $ci->db->where('page', $page);
                $ci->db->update('site_views');
            }
        }else{
            $ci->db->insert('site_views', array(
                'page' => $page,
                'counter' => $counter,
                'status' => '0'
            ));
        }
        $browser=getUserBrowser();
        $ip_address=get_client_ip();
        $createdDate=date("d-m-Y h:i A");
        new_browser($browser);
        $ci->db->insert('site_views_meta', array(
            'page' => $page,
            'ip_address' => $ip_address,
            'browser' => $browser,
            'createdDate' => $createdDate,
            'status' => '0'
        ));
    }

    function loadCategories($category){
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('id');
        $ci->db->select('name');
        $ci->db->where('status', '0');
        $query = $ci->db->get('tbl_shop_category');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                if($category==$data['id']){
                    echo '<option value="'.$data['id'].'" selected>'.ucwords($data['name']).'</option>';
                }else{
                    echo '<option value="'.$data['id'].'">'.ucwords($data['name']).'</option>';
                }
            }
        }
    }

    function loadBlogCategories($category){
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('id');
        $ci->db->select('category');
        $ci->db->where('status', '0');
        $query = $ci->db->get('tbl_blog_category');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                if($category==$data['id']){
                    echo '<option value="'.$data['id'].'" selected>'.ucwords($data['category']).'</option>';
                }else{
                    echo '<option value="'.$data['id'].'">'.ucwords($data['category']).'</option>';
                }
            }
        }
    }

    function list_blog_category(){
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('category');
        $ci->db->where('status', '0');
        $query = $ci->db->get('tbl_blog_category');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                echo '<li><a href="'.base_url().'blog/'.strtolower(str_replace("/", "@", $data['category'])).'">'.ucwords($data['category']).'</a></li>';
            }
        }
    }

    function numberFormat($value){
        $val="";
        if($value!="" && is_numeric($value)){
            $val=number_format($value);
        }
        return $val;
    }

    function getProductRate($product_url){
        $star=0;
        $star_rate=' 
                    <span class="star_unrated"><i class="fa fa-star xstxt"></i></span>
                    <span class="star_unrated"><i class="fa fa-star xstxt"></i></span>
                    <span class="star_unrated"><i class="fa fa-star xstxt"></i></span>
                    <span class="star_unrated"><i class="fa fa-star xstxt"></i></span>
                    <span class="star_unrated"><i class="fa fa-star xstxt"></i></span>';
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('star');
        $ci->db->from('tbl_product_reviews');
        $ci->db->join('tbl_products', 'tbl_products.id=product');
        $ci->db->where('product_url', $product_url);
        $ci->db->where('tbl_product_reviews.status', '0');
        $ci->db->where('tbl_products.status', '0');
        
        $query = $ci->db->get();          
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $star=$star + $data['star'];
            }
        }
        if($star>0){
            $star=$star/5;
            for ($i=1; $i <=$star ; $i++) {
                $star_rate='<span class="star_rated"><i class="fa fa-star xstxt"></i></span>';
                if(round($star)>1){
                    $star_rate.='<span class="star_rated"><i class="fa fa-star xstxt"></i></span>';
                }else{
                    $star_rate.='<span class="star_unrated"><i class="fa fa-star xstxt"></i></span>';
                }
                if(round($star)>2){
                    $star_rate.='<span class="star_rated"><i class="fa fa-star xstxt"></i></span>';
                }else{
                    $star_rate.='<span class="star_unrated"><i class="fa fa-star xstxt"></i></span>';
                }
                if(round($star)>3){
                    $star_rate.='<span class="star_rated"><i class="fa fa-star xstxt"></i></span>';
                }else{
                    $star_rate.='<span class="star_unrated"><i class="fa fa-star xstxt"></i></span>';
                }
                if(round($star)>4){
                    $star_rate.='<span class="star_rated"><i class="fa fa-star xstxt"></i></span>';
                }else{
                    $star_rate.='<span class="star_unrated"><i class="fa fa-star xstxt"></i></span>';
                }
            }
        }
        if($star>0){
            return $star_rate.' <span>('.$star.')</span>';
        }else{
            return $star_rate;
        }
    }

    function getProductRateValue($product_id){
        $rates=0;
        $ci =& get_instance();
        $ci->load->database();

        $ci->db->select('star');
        $ci->db->from('tbl_product_reviews');
        $ci->db->where('product', $product_id);
        $ci->db->where('status', '0');
        $query = $ci->db->get();
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $rates+=$data['star'];
            }
        }
        return $rates;
    }

    function getProductRateValueList($product_id){
        $rates="";
        $total_rates=0;
        $rate1=0;
        $rate2=0;
        $rate3=0;
        $rate4=0;
        $rate5=0;
        $ci =& get_instance();
        $ci->load->database();

        $ci->db->select('star');
        $ci->db->from('tbl_product_reviews');
        $ci->db->where('product', $product_id);
        $ci->db->where('status', '0');
        $query = $ci->db->get();
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                // $total_rates+=$data['star'];
                $total_rates++;
                if($data['star']=="1"){
                    $rate1++;
                }else if($data['star']=="2"){
                    $rate2++;
                }else if($data['star']=="3"){
                    $rate3++;
                }else if($data['star']=="4"){
                    $rate4++;
                }else if($data['star']=="5"){
                    $rate5++;
                }
            }
        }
        $rates=$total_rates."-".$rate1."-".$rate2."-".$rate3."-".$rate4."-".$rate5;
        return $rates;
    }

    function convertNumberToStar($star){
        $star_rate="";
        if($star<=5){
            for ($i=1; $i <=$star ; $i++) {
                $star_rate='<span class="star_rated"><i class="fa fa-star xstxt"></i></span>';
                if($star>=1){
                    if($star>1 && $star<2){
                        $star_rate.='<span class="star_rated"><i class="fa fa-star-half-o xstxt"></i></span>';
                    }else{
                        $star_rate.='<span class="star_rated"><i class="fa fa-star xstxt"></i></span>';
                    }
                }else{
                    $star_rate.='<span class="star_unrated"><i class="fa fa-star xstxt"></i></span>';
                }
                if($star>=2){
                    if($star>2 && $star<3){
                        $star_rate.='<span class="star_rated"><i class="fa fa-star-half-o xstxt"></i></span>';
                    }else{
                        $star_rate.='<span class="star_rated"><i class="fa fa-star xstxt"></i></span>';
                    }
                }else{
                    $star_rate.='<span class="star_unrated"><i class="fa fa-star xstxt"></i></span>';
                }
                if($star>=3){
                    if($star>3 && $star<4){
                        $star_rate.='<span class="star_rated"><i class="fa fa-star-half-o xstxt"></i></span>';
                    }else{
                        $star_rate.='<span class="star_rated"><i class="fa fa-star xstxt"></i></span>';
                    }
                }else{
                    $star_rate.='<span class="star_unrated"><i class="fa fa-star xstxt"></i></span>';
                }
                if($star>=4){
                    if($star>4 && $star<5){
                        $star_rate.='<span class="star_rated"><i class="fa fa-star-half-o xstxt"></i></span>';
                    }else{
                        $star_rate.='<span class="star_rated"><i class="fa fa-star xstxt"></i></span>';
                    }
                }else{
                    $star_rate.='<span class="star_unrated"><i class="fa fa-star xstxt"></i></span>';
                }
            }
        }else{
            $star_rate=' 
                    <span class="star_unrated"><i class="fa fa-star xstxt"></i></span>
                    <span class="star_unrated"><i class="fa fa-star xstxt"></i></span>
                    <span class="star_unrated"><i class="fa fa-star xstxt"></i></span>
                    <span class="star_unrated"><i class="fa fa-star xstxt"></i></span>
                    <span class="star_unrated"><i class="fa fa-star xstxt"></i></span>';
        }
        return $star_rate;
    }

    function getProductRated($product_url){
        $star=0;
        $star_rate=' 
                    <a href="javascript:void();" class="star star_unrated" id="star1" onclick="getStarCounter(1);"><i class="fa fa-star xstxt"></i></a>
                    <a href="javascript:void();" class="star star_unrated" id="star2" onclick="getStarCounter(2);"><i class="fa fa-star xstxt"></i></a>
                    <a href="javascript:void();" class="star star_unrated" id="star3" onclick="getStarCounter(3);"><i class="fa fa-star xstxt"></i></a>
                    <a href="javascript:void();" class="star star_unrated" id="star4" onclick="getStarCounter(4);"><i class="fa fa-star xstxt"></i></a>
                    <a href="javascript:void();" class="star star_unrated" id="star5" onclick="getStarCounter(5);"><i class="fa fa-star xstxt"></i></a>';
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('star');
        $ci->db->from('tbl_product_reviews');
        $ci->db->join('tbl_products', 'tbl_products.id=product');
        $ci->db->where('product_url', $product_url);
        $ci->db->where('tbl_product_reviews.status', '0');
        $ci->db->where('tbl_products.status', '0');
        
        $query = $ci->db->get();          
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $star=$star + $data['star'];
            }
        }
        if($star>0){
            $star=$star/5;
            for ($i=1; $i <=$star ; $i++) {
                $star_rate='<span class="star star_rated" id="star1" onclick="getStarCounter(1);"><i class="fa fa-star xstxt"></i></span>';
                if(round($star)>1){
                    $star_rate.='<span class="star star_rated" id="star2" onclick="getStarCounter(2);"><i class="fa fa-star xstxt"></i></span>';
                }else{
                    $star_rate.='<span class="star star_unrated" id="star2" onclick="getStarCounter(2);"><i class="fa fa-star xstxt"></i></span>';
                }
                if(round($star)>2){
                    $star_rate.='<span class="star star_rated" id="star3" onclick="getStarCounter(3);"><i class="fa fa-star xstxt"></i></span>';
                }else{
                    $star_rate.='<span class="star star_unrated" id="star3" onclick="getStarCounter(3);"><i class="fa fa-star xstxt"></i></span>';
                }
                if(round($star)>3){
                    $star_rate.='<span class="star star_rated" id="star4" onclick="getStarCounter(4);"><i class="fa fa-star xstxt"></i></span>';
                }else{
                    $star_rate.='<span class="star star_unrated" id="star4" onclick="getStarCounter(4);"><i class="fa fa-star xstxt"></i></span>';
                }
                if(round($star)>4){
                    $star_rate.='<span class="star star_rated" id="star5" onclick="getStarCounter(5);"><i class="fa fa-star xstxt"></i></span>';
                }else{
                    $star_rate.='<span class="star star_unrated" id="star5" onclick="getStarCounter(5);"><i class="fa fa-star xstxt"></i></span>';
                }
            }
        }
        return $star_rate.' (<span id="starCounter">'.$star.'</span>)';
    }

    function getPostRated($post_id){
        $star=0;
        $star_rate=' 
                    <a href="javascript:void();" class="star star_unrated" id="star1" onclick="getStarCounter(1);"><i class="fa fa-star xstxt"></i></a>
                    <a href="javascript:void();" class="star star_unrated" id="star2" onclick="getStarCounter(2);"><i class="fa fa-star xstxt"></i></a>
                    <a href="javascript:void();" class="star star_unrated" id="star3" onclick="getStarCounter(3);"><i class="fa fa-star xstxt"></i></a>
                    <a href="javascript:void();" class="star star_unrated" id="star4" onclick="getStarCounter(4);"><i class="fa fa-star xstxt"></i></a>
                    <a href="javascript:void();" class="star star_unrated" id="star5" onclick="getStarCounter(5);"><i class="fa fa-star xstxt"></i></a>';
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('star_rating');
        $ci->db->from('tbl_blog_comments');
        $ci->db->where('blog_id', $post_id);
        $ci->db->where('status', '0');
        
        $query = $ci->db->get();          
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $star=$star + $data['star_rating'];
            }
        }
        if($star>0){
            $star=$star/5;
            for ($i=1; $i <=$star ; $i++) {
                $star_rate='<span class="star star_rated" id="star1" onclick="getStarCounter(1);"><i class="fa fa-star xstxt"></i></span>';
                if(round($star)>1){
                    $star_rate.='<span class="star star_rated" id="star2" onclick="getStarCounter(2);"><i class="fa fa-star xstxt"></i></span>';
                }else{
                    $star_rate.='<span class="star star_unrated" id="star2" onclick="getStarCounter(2);"><i class="fa fa-star xstxt"></i></span>';
                }
                if(round($star)>2){
                    $star_rate.='<span class="star star_rated" id="star3" onclick="getStarCounter(3);"><i class="fa fa-star xstxt"></i></span>';
                }else{
                    $star_rate.='<span class="star star_unrated" id="star3" onclick="getStarCounter(3);"><i class="fa fa-star xstxt"></i></span>';
                }
                if(round($star)>3){
                    $star_rate.='<span class="star star_rated" id="star4" onclick="getStarCounter(4);"><i class="fa fa-star xstxt"></i></span>';
                }else{
                    $star_rate.='<span class="star star_unrated" id="star4" onclick="getStarCounter(4);"><i class="fa fa-star xstxt"></i></span>';
                }
                if(round($star)>4){
                    $star_rate.='<span class="star star_rated" id="star5" onclick="getStarCounter(5);"><i class="fa fa-star xstxt"></i></span>';
                }else{
                    $star_rate.='<span class="star star_unrated" id="star5" onclick="getStarCounter(5);"><i class="fa fa-star xstxt"></i></span>';
                }
            }
        }
        return $star_rate.' (<span id="starCounter">'.$star.'</span>)';
    }

    function get_words($sentence, $count = 10) {
        return implode( 
            '', 
            array_slice( 
              preg_split(
                '/([\s,\.;\?\!]+)/', 
                $sentence, 
                $count*2+1, 
                PREG_SPLIT_DELIM_CAPTURE
              ),
              0,
              $count*2-1
            )
          );
    }

    function getProductCoupon($product){
        $res=array();
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('coupon_code, coupon_value, expire_date');
        $ci->db->where('product',$product);
        $ci->db->where('expire_date >= CURDATE()');
        $ci->db->where('status', '0');
        $ci->db->order_by('id', 'DESC');
        $ci->db->limit(1);
        $query = $ci->db->get('tbl_seller_coupons');
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $expire_date=$data['expire_date'];
                if(timeDiffInDays(time(), strtotime($expire_date))>=0){
                    $res['coupon_code']=$data['coupon_code'];
                    $res['coupon_value']=$data['coupon_value'];
                }
            }
        }
        return $res;
    }

    function getSellerFullName($user_id){
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('full_name');
        $ci->db->where('user_id',$user_id);
        $query = $ci->db->get('tbl_sellers');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                return $data['full_name'];
            }
        }
    }

    function getPostCommentCounter($blog){
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('id');
        $ci->db->where('blog_id',$blog);
        $ci->db->where('status', '0');
        $query = $ci->db->get('tbl_blog_comments');
        return $query->num_rows();
    }

    function getItemCartCounter($userID, $product){
        $quantity="0";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('quantity');
        $ci->db->where('user_id',$userID);
        $ci->db->where('item_id',$product);
        $ci->db->where('status', '0');
        $where="is_complete='2'";
        $ci->db->where($where);
        $query = $ci->db->get('tbl_cart');
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $quantity=$data['quantity'];
            }
        }
        return $quantity;
    }

    function getCategoryName($category){
        $name="";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('name');
        $ci->db->where('id',$category);
        $query = $ci->db->get('tbl_shop_category');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $name=$data['name'];
            }
        }
        return $name;
    }

    function getCategoryID($category){
        $id="";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('id');
        $ci->db->where('name',$category);
        $query = $ci->db->get('tbl_shop_category');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $id=$data['id'];
            }
        }
        return $id;
    }

    function getSellerBrandName($userID){
        $user="";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('full_name, brand');
        $ci->db->from('tbl_sellers');
        $ci->db->join('tbl_seller_info', 'tbl_sellers.user_id = tbl_seller_info.seller_id', 'LEFT');
        $ci->db->where('user_id',$userID);
        $query = $ci->db->get();

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                if($data['brand']!=""){
                    $user=$data['brand'];
                }else{
                    $user=$data['full_name'];
                }
            }
        }
        return $user;
    }

    function getAdminAvatar($userID){
        $user="default.png";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('avatar');
        $ci->db->where('user_id',$userID);
        $query = $ci->db->get('tbl_admin');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                if($data['avatar']!=""){
                    $user=$data['avatar'];
                }
            }
        }
        return $user;
    }

    function getSellerBrandAvatar($userID){
        $user="seller_avatars/default.png";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('logo');
        $ci->db->from('tbl_sellers');
        $ci->db->join('tbl_seller_info', 'tbl_sellers.user_id = tbl_seller_info.seller_id', 'LEFT');
        $ci->db->where('user_id',$userID);
        $query = $ci->db->get();
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                if($data['logo']!=""){
                    $user='shop/logo/'.$data['logo'];
                }else{
                    $ci->db->select('avatar');
                    $ci->db->from('tbl_sellers');
                    $ci->db->where('user_id',$userID);
                    $query = $ci->db->get();
                    if($query->num_rows() > 0){
                        foreach ($query->result_array() as $data) {
                            if($data['avatar']!=""){
                                $user='seller_avatars/'.$data['avatar'];
                            }else{
                                $user='seller_avatars/default.png';
                            }
                        }
                    }
                }
            }
        }
        return $user;
    }

    function getInsContSocial($userID){
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('name, link');
        $ci->db->from('tbl_seller_social');
        $ci->db->where('seller_id',$userID);
        $ci->db->limit(4);
        $query = $ci->db->get();

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {;
                echo '<a href="'.$data['link'].'" class="social-icon si-colored si-'.str_replace('_', '-', $data['name']).'" data-toggle="tooltip" data-placement="top" title="'.$data['name'].'"> <i class="fa fa-'.str_replace('_', '-', $data['name']).'"></i></a>';
            }
        }
    }

    function getMsgReplies($msg, $userID){
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('tbl_msg.id, subject, message, createdBy, createdDate, receiver, sender');
        $ci->db->from('tbl_msg');
        $ci->db->join('tbl_msg_readers', 'msg_id=tbl_msg.id', 'LEFT');
        $ci->db->where('(tbl_msg_readers.user_id='.$userID.' OR tbl_msg.createdBy='.$userID.')');
        $ci->db->where('(parent_msg='.$msg.' AND parent_msg!="")');
        $ci->db->where('tbl_msg.status', '0');
        $ci->db->where('tbl_msg_readers.status', '0');
        $ci->db->order_by('createdDate', 'ASC');
        
        $query = $ci->db->get();          
        if($query->num_rows() > 0){
            echo '<div class="col-md-12"><hr /></div>';
            foreach ($query->result_array() as $data) {
                $subject=$data['subject'];
                $msg_id=$data['id'];
                $message=$data['message'];
                $createdBy=$data['createdBy'];
                $createdDate=$data['createdDate'];
                $receiver=$data['receiver'];
                if($userID==$createdBy){
                    echo '<div class="col-md-6 col-sm-12 col-xs-12 pull-right">
                            <small class="sm-text"><i>Sender: Me</i></small><div class="alert alert-success">'.$message.'</div>
                        </div> <div class="clear"></div>';
                }else{
                    $sender="";
                    if($data['sender']=='admin')
                        $sender=getAdminFullName($data['createdBy']);
                    if($data['sender']=='seller')
                        $sender=getSellerFullName($data['createdBy']);
                    if($data['sender']=='customer')
                        $sender=getCustomerFullName($data['createdBy']);
                    echo '<div class="col-md-6 col-sm-12 col-xs-12">
                            <small class="sm-text"><i>Sender: '.$sender.'</i></small><div class="alert alert-info">'.$message.'<br /><a href="javascript:void();" onclick="displayReply(\''.$subject.'\', \''.$msg_id.'\', \''.$createdBy.'\');"><i class="fa fa-reply"></i></a></div>
                        </div> <div class="clear"></div>';
                }
                $ci->db->set('is_read', '0');
                $ci->db->where('status', '0');
                $ci->db->where('msg_id', $msg_id);
                $ci->db->where('user_id', $userID);
                $ci->db->update('tbl_msg_readers');
                getMsgReplied($msg_id, $userID);
            }
        }
    }

    function getMsgReplied($msg, $userID){
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('id, subject, message, createdBy, createdDate, receiver, sender');
        $ci->db->from('tbl_msg');
        $ci->db->where('parent_msg', $msg);
        // $ci->db->where('parent_msg='.$msg.' OR createdBy='.$userID.'');
        $ci->db->where('status', '0');
        $ci->db->order_by('createdDate', 'ASC');
        
        $query = $ci->db->get();          
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $subject=$data['subject'];
                $msg_id=$data['id'];
                $message=$data['message'];
                $createdBy=$data['createdBy'];
                $createdDate=$data['createdDate'];
                $receiver=$data['receiver'];
                if($userID==$createdBy){
                    echo '<div class="col-md-6 col-sm-12 col-xs-12 pull-right">
                            <small class="sm-text"><i>Sender: Me</i></small><div class="alert alert-success">'.$message.'</div>
                        </div> <div class="clear"></div>';
                }else{
                    $sender="";
                    if($data['sender']=='admin')
                        $sender=getAdminFullName($data['createdBy']);
                    if($data['sender']=='seller')
                        $sender=getSellerFullName($data['createdBy']);
                    if($data['sender']=='customer')
                        $sender=getCustomerFullName($data['createdBy']);
                    echo '<div class="col-md-6 col-sm-12 col-xs-12">
                            <small class="sm-text"><i>Sender: '.$sender.'</i></small><div class="alert alert-info">'.$message.'<br /><a href="javascript:void();" onclick="displayReply(\''.$subject.'\', \''.$msg_id.'\', \''.$createdBy.'\');"><i class="fa fa-reply"></i></a></div>
                        </div> <div class="clear"></div>';
                }
                $ci->db->set('is_read', '0');
                $ci->db->where('status', '0');
                $ci->db->where('msg_id', $msg_id);
                $ci->db->where('user_id', $userID);
                $ci->db->update('tbl_msg_readers');
            }
        }
    }

    function getCustomerFullName($user_id){
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
    }

    function getAdminFullName($user_id){
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('first_name, surname');
        $ci->db->where('user_id',$user_id);
        $query = $ci->db->get('tbl_admin');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                return $data['first_name'].' '.$data['surname'];
            }
        }
    }

    function generateLink($table, $link_name, $link, $id=""){
        if($table=="tbl_sellers"){
            $url=getRandomNumber(10);
        }else{
            $url=str_replace(" ", "_", strtolower($link));
            $url=str_replace("?", "", strtolower($url));
            $url=str_replace("'", "", strtolower($url));
            $url=str_replace('"', '', strtolower($url));
            $url=str_replace(",", "", strtolower($url));
            $url=str_replace("/", "", strtolower($url));
            $url=str_replace("!", "", strtolower($url));
            $url=str_replace("$", "", strtolower($url));
            $url=str_replace("#", "", strtolower($url));
            $url=str_replace("`", "", strtolower($url));
            $url=str_replace("%", "", strtolower($url));
            $url=str_replace("^", "", strtolower($url));
            $url=str_replace(">", "", strtolower($url));
            $url=str_replace("<", "", strtolower($url));
            $url=str_replace("|", "", strtolower($url));
            $url=str_replace("~", "", strtolower($url));
            $url=str_replace(":", "", strtolower($url));
        }
        $new_url=$url;
        $counter=0;
        $valid=false;
        $ci =& get_instance();
        $ci->load->database();        
        while(!$valid){
            $ci->db->select('id');
            if($id!=""){
                $ci->db->where('id !=', $id);
            }
            $ci->db->where($link_name, $new_url);
            $query = $ci->db->get($table);
            if($query->num_rows()==0){
                $valid=true;
            }else{
                $counter++;
                $new_url=$url.''.$counter;
            }
        }
        return $new_url;
    }

    function getUserAvatar($userID){
        $user="default.png";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('avatar');
        $ci->db->where('user_id',$userID);
        $query = $ci->db->get('tbl_user_info');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                if($data['avatar']!=""){
                    $user=$data['avatar'];
                }
            }
        }
        return $user;
    }

    function getUserFullName($user_id){
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('first_name');
        $ci->db->select('surname');
        $ci->db->where('user_id',$user_id);
        $query = $ci->db->get('tbl_user_info');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                return $data['first_name'].' '.$data['surname'];
            }
        }
    }

    function getLatestOrderID($userID){
        $id="";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('orderID');
        $ci->db->where('user_id',$userID);
        $ci->db->where('is_complete','2');
        $ci->db->where('status','0');
        $ci->db->order_by('createdDate','DESC');
        $ci->db->limit(1);
        $query = $ci->db->get('tbl_orders');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $id=$data['orderID'];
            }
        }
        return $id;
    }

    function generateOrderID(){
        $orderID=getRandomNumber(6);
        $valid=false;
        $ci =& get_instance();
        $ci->load->database();        
        while(!$valid){
            $ci->db->select('id');
            $ci->db->where('orderID', $orderID);
            $query = $ci->db->get('tbl_orders');
            if($query->num_rows()==0){
                $valid=true;
            }else{
                $orderID=getRandomNumber(6);
            }
        }
        return $orderID;
    }

    function getProductCommission($product){
        $amount=array();
        $amount_fixed="";
        $amount_percent="";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('amount_fixed, amount_percent');
        $ci->db->where('product', $product);
        $ci->db->where('status', '0');
        $query = $ci->db->get('tbl_commission_rates');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $amount_fixed=$data['amount_fixed'];
                $amount_percent=$data['amount_percent'];
            }
        }
        $amount['amount_fixed']=$amount_fixed;
        $amount['amount_percent']=$amount_percent;
        return $amount;
    }

    function getSellerTypeCommission($seller, $account_type){
        $amount=array();
        $amount_fixed="";
        $amount_percent="";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('amount_fixed, amount_percent');
        $ci->db->where('type', $account_type);
        $ci->db->where('seller_id', $seller);
        $ci->db->where('status', '0');
        $query = $ci->db->get('tbl_commission_rates');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $amount_fixed=$data['amount_fixed'];
                $amount_percent=$data['amount_percent'];
            }
        }
        $amount['amount_fixed']=$amount_fixed;
        $amount['amount_percent']=$amount_percent;
        return $amount;
    }

    function getAccountTypeCommission($account_type){
        $amount=array();
        $amount_fixed="";
        $amount_percent="";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('amount_fixed, amount_percent');
        $ci->db->where('type', $account_type);
        $ci->db->where('seller_id', 'all');
        $ci->db->where('status', '0');
        $query = $ci->db->get('tbl_commission_rates');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $amount_fixed=$data['amount_fixed'];
                $amount_percent=$data['amount_percent'];
            }
        }
        $amount['amount_fixed']=$amount_fixed;
        $amount['amount_percent']=$amount_percent;
        return $amount;
    }

    function getFlatCommission(){
        $amount=array();
        $amount_fixed="";
        $amount_percent="";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('amount_fixed, amount_percent');
        $ci->db->where('type', 'flat');
        $ci->db->where('status', '0');
        $query = $ci->db->get('tbl_commission_rates');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $amount_fixed=$data['amount_fixed'];
                $amount_percent=$data['amount_percent'];
            }
        }
        $amount['amount_fixed']=$amount_fixed;
        $amount['amount_percent']=$amount_percent;
        return $amount;
    }

    function getFlatVendorCommission(){
        $amount=array();
        $amount_fixed="";
        $amount_percent="";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('amount_fixed, amount_percent');
        $ci->db->where('type', 'flat');
        $ci->db->where('status', '0');
        $query = $ci->db->get('tbl_commission_rates_v');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $amount_fixed=$data['amount_fixed'];
                $amount_percent=$data['amount_percent'];
            }
        }
        $amount['amount_fixed']=$amount_fixed;
        $amount['amount_percent']=$amount_percent;
        return $amount;
    }

    function isReferralValid($referral_url, $product_url){
        $res=false;
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('id');
        $ci->db->where('status', '0');
        $ci->db->where('product', $product_url);
        $ci->db->where('referral_url', $referral_url);
        $query = $ci->db->get('tbl_affiliate_urls');

        if($query->num_rows() > 0){
            $res=true;
        }
        return $res;
    }

    function getOrderAmountPaid($orderID){
        $res="";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('amount_paid');
        $ci->db->where('status', '0');
        $ci->db->where('orderID', $orderID);
        $query = $ci->db->get('tbl_orders');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $res=$data['amount_paid'];
            }
        }
        return $res;
    }

    function getItemAmountPaid($orderID, $product){
        $amount="";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('price, coupon_value');
        $ci->db->where('orderID',$orderID);
        $ci->db->where('item_id',$product);
        $ci->db->where('status', '0');
        /*$where="is_complete='2'";
        $ci->db->where($where);*/
        $query = $ci->db->get('tbl_cart');
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $amount=(float)$data['price']-(float)$data['coupon_value'];
            }
        }
        return $amount;
    }

    function is_customer_owner_file($userID, $product_file_name){
        $owner="not";
        $ci =& get_instance();
        $ci->load->database();

        $ci->db->select('id');
        $ci->db->like('media', $product_file_name);
        $ci->db->where('status','0');
        $query = $ci->db->get('tbl_products');

        if($query->num_rows() > 0){
            $counter=0;
            foreach ($query->result() as $data) {
                $product=$data->id;
                $ci->db->select('tbl_cart.id');
                $ci->db->from('tbl_cart');
                $ci->db->join('tbl_orders', 'tbl_orders.orderID = tbl_cart.orderID');
                $ci->db->where('tbl_orders.user_id', $userID);
                $ci->db->where('tbl_cart.user_id', $userID);
                $ci->db->where('item_id', $product);
                $ci->db->where('tbl_orders.is_complete', '0');
                $ci->db->where('tbl_cart.status', '0');
                $ci->db->where('tbl_orders.status', '0');
                $sql = $ci->db->get();
                if($sql->num_rows() > 0){
                    $counter++;
                }
            }
            if($counter>0){
                $owner='owner';
            }
        }
        $owner='owner';
        return $owner;
    }

    function is_vendor_owner_file($userID, $product_file_name){
        $owner="not";
        $ci =& get_instance();
        $ci->load->database();

        $ci->db->select('media');
        $ci->db->where('seller_id', $userID);
        $ci->db->where('status','0');
        $query = $ci->db->get('tbl_products');

        if($query->num_rows() > 0){
            $counter=0;
            foreach ($query->result() as $data) {
                if(strpos($data->media, ";")!==FALSE){
                    $mdia=explode(";", $data->media);
                    for ($i=0; $i < sizeof($mdia); $i++) {
                        if($mdia[$i]==$product_file_name){
                            $counter++;
                        }
                    }
                }elseif($data->media==$product_file_name){
                    $counter++;
                }
            }
            if($counter>0){
                $owner='owner';
            }
        }
        return $owner;
    }

    function getUserIDFromUsername($username){
        $user_id="";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('id');
        $ci->db->where('username',$username);
        $ci->db->where('status', '0');
        $query = $ci->db->get('tbl_users');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $user_id=$data['id'];
            }
        }
        return $user_id;
    }

    function getSellerTypeVendorCommission($seller, $account_type){
        $amount=array();
        $amount_fixed="";
        $amount_percent="";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('amount_fixed, amount_percent');
        $ci->db->where('type', $account_type);
        $ci->db->where('seller_id', $seller);
        $ci->db->where('status', '0');
        $query = $ci->db->get('tbl_commission_rates_v');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $amount_fixed=$data['amount_fixed'];
                $amount_percent=$data['amount_percent'];
            }
        }
        $amount['amount_fixed']=$amount_fixed;
        $amount['amount_percent']=$amount_percent;
        return $amount;
    }

    function getAccountTypeVendorCommission($account_type){
        $amount=array();
        $amount_fixed="";
        $amount_percent="";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('amount_fixed, amount_percent');
        $ci->db->where('type', $account_type);
        $ci->db->where('seller_id', 'all');
        $ci->db->where('status', '0');
        $query = $ci->db->get('tbl_commission_rates_v');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $amount_fixed=$data['amount_fixed'];
                $amount_percent=$data['amount_percent'];
            }
        }
        $amount['amount_fixed']=$amount_fixed;
        $amount['amount_percent']=$amount_percent;
        return $amount;
    }

    function getProductVendorCommission($product){
        $amount=array();
        $amount_fixed="";
        $amount_percent="";
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('amount_fixed, amount_percent');
        $ci->db->where('product', $product);
        $ci->db->where('status', '0');
        $query = $ci->db->get('tbl_commission_rates_v');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $data) {
                $amount_fixed=$data['amount_fixed'];
                $amount_percent=$data['amount_percent'];
            }
        }
        $amount['amount_fixed']=$amount_fixed;
        $amount['amount_percent']=$amount_percent;
        return $amount;
    }

    function generate_verification_code(){
        $randCode=getRandomMixedCode(15);
        $code=md5(md5(sha1(md5($randCode))));
        $valid=false;
        $ci =& get_instance();
        $ci->load->database();
        while(!$valid){
            $ci->db->select('id');
            $ci->db->where('activation_code',$code);
            $query = $ci->db->get('tbl_users');
            if($query->num_rows()==0){
                $valid=true;
            }else{
                $randCode=getRandomMixedCode(15);
                $code=md5(md5(sha1(md5($randCode))));
            }
        }
        return $code;
    }

    function getSelfHelpList($category, $counter){
        $res=0;
        $ci =& get_instance();
        $ci->load->database();

        $ci->db->select('id, name');
        $ci->db->where('category', $category);
        $ci->db->where('status','0');
        $query = $ci->db->get('tbl_self_sub_category');
        if($counter==1){
            echo '<div id="tab'.$counter.'" class="tab-pane fade in active row">';
        }else{
            echo '<div id="tab'.$counter.'" class="tab-pane fade row">';
        }

        if($query->num_rows() > 0){
            foreach ($query->result() as $data) {
                $subcategory=$data->id;
                $name=$data->name;
                echo '<div class="col-md-4 topics-wrapper subList">';
                echo '<h3>'.$name.'</h3>';
                echo '<ul class="topics-list">';
                $ci->db->select('id, title, url');
                $ci->db->where('sub_category', $subcategory);
                $ci->db->where('status', '0');
                $sql = $ci->db->get('tbl_self_help');
                if($sql->num_rows() > 0){
                    foreach ($sql->result() as $row) {
                        echo '<li><a href="'.base_url().'self_help_details/'.$row->url.'"><i class="fa fa-file-text-o"></i> '.ucfirst($row->title).'</a></li>';
                    }
                }
                echo '</ul>';
                echo '</div>';
            }
        }
        echo '</div>';
    }
?>