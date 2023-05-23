<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('home/Home_model');
        $this->load->model('home/Blog_model');
        $this->load->model('home/Product_model');
        $this->load->model('home/Homeorder_model');
        $this->userToken=$this->session->userdata('getvalue_user_idetification');
        /*$csrf = array(
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash()
        );*/
    }

	public function index($page='index'){
		if(!file_exists(APPPATH.'views/pages/'.$page.'.php')){
            // show_404();
            redirect(base_url().'notfound/ind');
        }
        $data['title'] = '';
        if($page=='index'){
            $data['sliderProducts'] = $this->Home_model->getSliderProducts();
            $data['ebooks'] = $this->Home_model->loadProducts('ebooks', "", "", "", "", "", 10);
            $data['audiobooks'] = $this->Home_model->loadProducts('audiobooks', "", "", "", "", "", 12);
            $data['onlineTrainings'] = $this->Home_model->loadProducts('online training & programs', "", "", "", "", "", 10);
            $data['popularBooks'] = $this->Home_model->load_popular_products(6);
            $data['trendingProducts'] = $this->Home_model->load_popular_products(19);
            $data['insiderPopulars'] = $this->Blog_model->load_popular_posts('insider');
            $data['contributorPopulars'] = $this->Blog_model->load_popular_posts('contributor');
            $data['featuredInsConts'] = $this->Home_model->load_featured_ins_cont();
            $data['homeBlogs'] = $this->Blog_model->load_home_post();
        }
        if($page=='ebooks'){
            $brand=""; $initialPrice=""; $finalPrice=""; $time="";
            $from=0; $to=20;
            $user_data=$this->input->get();
            if(isset($user_data) && !empty($user_data)){
                $brand=sqlSafe($user_data['brand']);
                $initialPrice=sqlSafe($user_data['initialPrice']);
                $finalPrice=sqlSafe($user_data['finalPrice']);
                $time=sqlSafe($user_data['time']);
                if(isset($user_data['from']))
                $from=sqlSafe($user_data['from'])-1;
            }            
            $data['products'] = $this->Home_model->loadProducts('ebooks', $brand, $time, $initialPrice, $finalPrice, $from,  $to, false);
            $data['total_products'] = $this->Home_model->getTotalProducts('ebooks', $brand, $time, $initialPrice, $finalPrice);
            $data['brands'] = $this->Product_model->loadBrands($brand, 'ebooks');
        }
        if($page=='audiobooks'){
            $brand=""; $initialPrice=""; $finalPrice=""; $time="";
            $from=0; $to=20;
            $user_data=$this->input->get();
            if(isset($user_data) && !empty($user_data)){
                $brand=sqlSafe($user_data['brand']);
                $initialPrice=sqlSafe($user_data['initialPrice']);
                $finalPrice=sqlSafe($user_data['finalPrice']);
                $time=sqlSafe($user_data['time']);
                if(isset($user_data['from']))
                $from=sqlSafe($user_data['from'])-1;
            }            
            $data['products'] = $this->Home_model->loadProducts('audiobooks', $brand, $time, $initialPrice, $finalPrice, $from,  $to, false);
            $data['total_products'] = $this->Home_model->getTotalProducts('audiobooks', $brand, $time, $initialPrice, $finalPrice);
            $data['brands'] = $this->Product_model->loadBrands($brand, 'audiobooks');
        }
        if($page=='online_trainings'){
            $brand=""; $initialPrice=""; $finalPrice=""; $time="";
            $from=0; $to=20;
            $user_data=$this->input->get();
            if(isset($user_data) && !empty($user_data)){
                $brand=sqlSafe($user_data['brand']);
                $initialPrice=sqlSafe($user_data['initialPrice']);
                $finalPrice=sqlSafe($user_data['finalPrice']);
                $time=sqlSafe($user_data['time']);
                if(isset($user_data['from']))
                $from=sqlSafe($user_data['from'])-1;
            }            
            $data['products'] = $this->Home_model->loadProducts('online training & programs', $brand, $time, $initialPrice, $finalPrice, $from,  $to, false);
            $data['total_products'] = $this->Home_model->getTotalProducts('online training & programs', $brand, $time, $initialPrice, $finalPrice);
            $data['brands'] = $this->Product_model->loadBrands($brand, 'online training & programs');
        }
        if($page=='getvaluetv'){
            $data['title']='TV Post || GetValue Inc';
            $data['tv_post'] = $this->Blog_model->load_tv_posts();
        }
        $this->setView($page, $data);
	}

    public function setView($page='index', $data=""){
        $this->load->view('includes/header');
        $this->load->view('pages/'.$page, $data);
        $this->load->view('includes/footer');
    }

    public function setView_app($page, $data=""){
        $this->load->view('includes/app_header');
        $this->load->view('pages/app_pages/'.$page, $data);
        $this->load->view('includes/app_footer');
    }

    public function seller_profile($seller){
        if($seller!=""){
            $data['title'] = 'Seller Profile';
            $seller=sqlSafe($seller);
            $data['profile'] = $this->Home_model->load_seller_profile($seller);
            $this->setView('seller_profile', $data);
        }else{
            redirect(base_url('./'));
        }
    }

    public function terms_app(){
        $this->setView('terms_app', $data);
    }

    public function policy_app(){
        $this->setView('policy_app', $data);
    }

    public function test_mpesa(){
        $this->load->model('Vpesa_model');
        $check=$this->Vpesa_model->create_order("255752844733", "EBook", "T15634C", "600");
        // $check=$this->Vpesa_model->transaction_status("cwfMSPStride");
        // $check=$this->Vpesa_model->transaction_status("T15634C");
        var_dump($check);
    }

    public function product_details($product){
        if($product!=""){
            $referral="";
            $user_data=$this->input->get();
            if(isset($user_data['ref']) && $user_data['ref']!=""){
                $referral=$user_data['ref'];
            }
            $data['title'] = ucwords(str_replace("_", ' ', $product));
            $product=dataSafe($product);
            $referral=dataSafe($referral);
            $data['userID']=getUserID($this->userToken);
            $data['referral']=$referral;
            $product_info = $this->Home_model->load_product_details($product, $referral);
            $data['product'] = $product_info;
            $data['productInDiscounts'] = $this->Home_model->load_discount_products(6, "", $product_info['id'], true);
            $data['sellerProducts'] = $this->Home_model->load_seller_products($product_info['seller_id'], "", 6, $product_info['id']);
            $data['popularProducts'] = $this->Home_model->load_popular_products(6, "", $product_info['id']);
            $data['bestSellerProducts'] = $this->Home_model->load_best_seller_products(6, "", $product_info['id']);
            $data['oldProducts'] = $this->Home_model->load_old_gold_products(6, "", $product_info['id']);
            // $data['relatedProducts'] = $this->Home_model->load_related_products($product);
            $data['sellerInfo'] = $this->Home_model->load_seller_info($product);
            $data['popularBooks'] = $this->Home_model->load_popular_products();
            $data['totalSales'] = $this->Product_model->getTotalProductSales($product_info['id']);
            $this->setView('product_details', $data);
        }else{
            redirect(base_url('./'));
        }
    }

    public function products(){
        $data['title'] = "Product By Category - GetValue";
        $user_data=$this->input->get();
        if(isset($user_data['filter']) && $user_data['filter']!=""){
            $filter=sqlSafe($user_data['filter']);
            $seller=""; $from=0; $limit=20;
            if(isset($user_data['seller']) && $user_data['seller']!=""){
                $seller=sqlSafe($user_data['seller']);
            }
            if(isset($user_data['from']) && $user_data['from']!=""){
                $from=sqlSafe($user_data['from'])-1;
            }
            if($filter=='discounts'){
                $data['productInDiscounts'] = $this->Home_model->load_discount_products($limit, $from, "", false);
                $data['total_products'] = $this->Home_model->total_discount_products();
            }
            if($filter=='seller_products'){
                $data['sellerProducts'] = $this->Home_model->load_seller_products($seller, "", $from, $limit, "");
                $data['total_products'] = $this->Home_model->total_seller_products($seller, "");
            }
            if($filter=='best_seller'){
                $data['bestSellerProducts'] = $this->Home_model->load_best_seller_products($limit, $from, "");
                $data['total_products'] = $this->Home_model->total_best_seller_products();
            }
            if($filter=='old_gold'){
                $data['oldProducts'] = $this->Home_model->load_old_gold_products($limit, $from, "");
                $data['total_products'] = $this->Home_model->total_old_gold_products();
            }
            if($filter=='getvalue_recommendation'){
                $data['popularProducts'] = $this->Home_model->load_popular_products($limit, $from, "");
                $data['total_products'] = $this->Home_model->total_popular_products();
            }
            // $data['userID']=getUserID($this->userToken);
            $data['filter']=$filter;
            $data['seller']=$seller;
            $this->setView('products', $data);
        }else{
            redirect(base_url('./'));
        }
    }

    public function product_review($product){
        if($product!=""){
            $referral="";
            $user_data=$this->input->get();
            if(isset($user_data['ref']) && $user_data['ref']!=""){
                $referral=sqlSafe($user_data['ref']);
            }
            $data['title'] = ucwords(str_replace("_", ' ', 'Review - '.$product));
            $product=sqlSafe($product);
            $data['userID']=getUserID($this->userToken);
            $data['product'] = $this->Home_model->load_product_details($product, $referral);
            $data['relatedProducts'] = $this->Home_model->load_related_products($product);
            $data['sellerInfo'] = $this->Home_model->load_seller_info($product);
            $data['popularBooks'] = $this->Home_model->load_popular_products();
            $this->setView('product_review', $data);
        }else{
            redirect(base_url('./'));
        }
    }

    public function search_results(){
        $data = $this->input->get();           
        $keyword=sqlSafe($data['keyword']);
        $blog_category=sqlSafe($data['blog_category']);
        $product_category=sqlSafe($data['product_category']);
        $data['product_results']=$this->Home_model->search_products_results($keyword, $product_category);
        $data['post_results']=$this->Home_model->search_post_results($keyword, $blog_category);
        $this->setView('search_results', $data);
    }

    public function seller_collection($seller){
        if($seller!=""){
            $seller=sqlSafe($seller);
            $user_data=$this->input->get();
            $category="";
            if(isset($user_data) && !empty($user_data)){
                $category=sqlSafe($user_data['category']);
            }
            $data['products'] = $this->Home_model->load_seller_products($seller, $category);
            $info = $this->Home_model->load_sellerid_profile($seller);
            $data['info'] = $info;
            $data['socials'] = $this->Home_model->load_seller_socials($seller);
            $data['listShopCategories'] = $this->Home_model->listShopCategories();
            $data['seller']=$seller;
            if(sizeof($info)>0){
                if($info['brand']!=""){
                    $data['title']=$info['brand'];
                }else{
                    $data['title']=$info['full_name'];
                }
                $this->setView('seller_collection', $data);
            }else{
                redirect(base_url('./'));
            }
        }else{
            redirect(base_url('./'));
        }
    }

    public function blog($category){
        if($category!=""){
            $cat=$category;
            $category=sqlSafe(str_replace("@", "/", $category));
            $data['title']=str_replace("_", " ", $category).' || GetValue Inc';
            $user_data=$this->input->get();
            $from=0; $limit=20;
            if(isset($user_data['from']) && $user_data['from']!=""){
                $from=sqlSafe($user_data['from']);
            }
            $data['posts'] = $this->Blog_model->load_blog_posts($category, $from, $limit);
            $data['total_posts'] = $this->Blog_model->total_blog_posts($category);
            $data['recent_posts'] = $this->Blog_model->load_recent_posts();
            $data['popularBooks'] = $this->Home_model->load_popular_products();
            $data['category'] = $cat;
            $this->setView('blog', $data);
        }else{
            redirect(base_url('./'));
        }
    }

    public function self_help(){
        $user_data=$this->input->get();
        $keyword="";
        if(isset($user_data['keyword']))
        $keyword=$user_data['keyword'];
        if($keyword!=""){
            $keyword=sqlSafe($keyword);
        }
        $data['title']='Self Help || GetValue Inc';
        $data['categories'] = $this->Blog_model->load_self_help_categories();
        $data['helps'] = $this->Blog_model->load_self_help($keyword);
        $this->setView('help', $data);
    }

    public function checkout(){
        $data['title']='Checkout || GetValue Inc';
        $userID=getUserID($this->userToken);
        $account_type=$this->session->userdata('account_type');
        $user_data=$this->input->get();
        $product="";
        $referral="";
        $user_info=array(); $cart=array();
        $product_info=array(); $order_info=array();
        if(isset($user_data['product']) && $user_data['product']!=''){
            $product=sqlSafe($user_data['product']);
            $product_info=$this->Home_model->load_product_info($product);
        }
        if(isset($user_data['referral']) && $user_data['referral']!=''){
            $referral=sqlSafe($user_data['referral']);
        }
        $this->load->helper('cookie');
        $nspl_name=get_cookie('nspl_name');
        $nspl_email=get_cookie('nspl_email');
        $nspl_payment_method=get_cookie('nspl_payment_method');
        $nspl_payment_number=get_cookie('nspl_payment_number');
        $data['nspl_name']=$nspl_name;
        $data['nspl_email']=$nspl_email;
        $data['nspl_payment_method']=$nspl_payment_method;
        $data['nspl_payment_number']=$nspl_payment_number;
        if($userID!=""){
            if($product!=""){
                // $check = $this->Product_model->add_cart($product, $product_info['price'], $userID, "", "", "quick");
            }
            $this->load->model('customer/Corder_model');
            $order_info = $this->Corder_model->load_order_info($userID);
            $this->load->model('Users_model');
            $user_info = $this->Users_model->user_info($userID, $account_type);
            $cart = $this->Corder_model->load_cart_items($userID);
        }

        $data['cart'] = $cart;
        $data['product']=$product;
        $data['referral']=$referral;
        $data['user_info']=$user_info;
        $data['product_info']=$product_info;
        $data['order_info']=$order_info;
        
        //Get all countries
        $countries_info=$this->Home_model->get_countries();
        
        $data['countries'] = $countries_info;
        
        $this->setView('checkout', $data);
    }

    public function check_order_status(){
        $userID=getUserID($this->userToken);
        $user_data=$this->input->get();
        
        $order_email = $user_data;
        
        $orderID="";
        if(isset($user_data['orderID']) && $user_data['orderID']!=''){
            $orderID=sqlSafe($user_data['orderID']);
        }
        
        $check = $this->Homeorder_model->check_order_status($orderID, $userID);
      
		$this->db->select('item_id');
		$this->db->from('tbl_cart');
		$this->db->where('orderID', $orderID);
   		$query = $this->db->get();
   		
   		if($query->num_rows() > 0){
            foreach ($query->result_array() as $data_user) {
                $product = $data_user['item_id'];
            }
        }
   		
        $order_details = [
            'order_id' => $orderID,
            'user_id' => $userID,
            'product_id' => $product
        ];
                
        if($check == "Success"){
            echo "Success";
            
            //Send complete order email to user
            $this->sendCompleteOrderEmail($order_details);
            exit;
        }
        else{
            echo "We didn't get Order status, click Complete Order to try again.";
            
        //Send pending order email to user
         $this->sendPendingOrderEmail($order_details);
         exit;
        }
        
    }

    public function verify_coupon($coupon=""){
        if(!empty($coupon) && $coupon!=""){
            $coupon=sqlSafe($coupon);
            $userID=getUserID($this->userToken);
            $this->load->model('customer/Customer_model');
            $check = $this->Customer_model->verify_coupon($coupon);
            echo $check;
        }else{
            echo 'No coupon provided';
        }
    }

    public function checkout_order(){
        $this->form_validation->set_error_delimiters(errMsg(), '</div>');
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('email', 'email', 'trim|required');
        $this->form_validation->set_message('required', 'The %s should not be empty.');
        $data = array();
        
        if ($this->form_validation->run() === FALSE){
            if(form_error('name')!=""){
                die(form_error('name'));
            }elseif(form_error('email')!=""){
                die(form_error('email'));
            }
        }else{
            $user_data = $this->input->post();
            
            $order_email = $user_data;
            
            $userID=getUserID($this->userToken);
            if(!isset($user_data['payment_method'])){
                die(ErrorMsg('Select your payment method'));
            }else{
                if($user_data['payment_method']=='mastercard' && $user_data['payment_method']=='visa'){
                    if($user_data['phone_number']==''){
                        die(ErrorMsg('Enter your mobile payment number'));
                    }
                }
            }

            $check=$this->Homeorder_model->checkout_order($user_data, $userID);
            
            if($check != "Success"){
                
                echo $check;
                
                $userID=getUserID($this->userToken);
                $user_data=$this->input->get();
                
                $orderID="";
                if(isset($user_data['orderID']) && $user_data['orderID']!=''){
                    $orderID=sqlSafe($user_data['orderID']);
                }
                
                $result=array();
    			
    	   		$this->db->select('user_id');
    	        $this->db->where('email', $order_email['email']);
    	        $query = $this->db->get('tbl_user_info');
    	   		
                $newUserId = "";
                
                if($query->num_rows() > 0){
                    foreach ($query->result_array() as $data_user) {
                        $newUserId = $data_user['user_id'];
                    }
                }
                
                $order_details = [
                    'order_id' => $order_email['orderID'],
                    'user_id' => $newUserId,
                    'product_id' => $order_email['product']
                ];
                
                $this->sendPendingOrderEmail($order_details);
                exit;
            }
            else{
                echo $check;
                exit;
            }
            
        }
        /*$this->load->model('Selcom_model');
        $send=$this->Selcom_model->push_ussd_payment("255752844733", "me2002");
        echo var_dump($send);*/
    }
    
    public function sendCompleteOrderEmail($data){
        
        $order_details = [];
        
        $this->db->select(['first_name','surname','email','payment_type','amount_paid','status']);
        $this->db->where('orderID', $data['order_id']);
        $this->db->where('user_id', $data['user_id']);
        $this->db->where('status', '0');
        $query11 = $this->db->get('tbl_orders');
        
        if($query11->num_rows() > 0){
            foreach ($query11->result_array() as $data11) {
                $order_details[] = $data11;
            }
        }
       
        $email = $order_details[0]['email'];
        $name = $order_details[0]['first_name']." ".$order_details[0]['surname'];
        $orderID = $data['order_id'];
        $payment_type = $order_details[0]['payment_type'];
        $order_date = date('Y-m-d',strtotime(date('Y-m-d')));
        $amount = $order_details[0]['amount_paid'];
        $discount_amount = 0;
        $total_amount = $order_details[0]['amount_paid'];
        $payment_status = "Complete";
        
        $google_url = "<?=site_url('assets/themes/icons/playstore_btn.png'); ?>";
        $ios_url = "<?=site_url('assets/themes/icons/appstore_btn.png'); ?>";
        
        
        $email_html = "
            <div class='container'>
                <div class='main-header'>
                    <img src='https://www.getvalue.co/assets/themes/logo.png' height='50'/>
                </div>
                <h5 style='color: black; font-size: 1.3em;'>Order Confirmation</h5>
                <hr size='7' style='background-color: black;'/>
                <div class='greetings'>
                    <p style='font-style: italic; opacity: 0.6; font-size: 1.0em;'>Hello ".$name." ,</p>
                    <p style='text-transform: capitalize; font-size: 1.0em; font-weight: 600;'>Thank you for buying from Us!</p>
                </div>
                <div class='content'>
                    <p>GETVALUE has created an Online Library for you, all digital product you have purchased including eBooks, Audiobooks 'and' Online Training programs are stored in 'My Products' section in your online library located in our app.</p>
                    <p style='padding-top: 5px;'>Below is your order details:</p>
                </div>
                <div class='order-details'>
                    <h5 style='color: black; opacity: 0.9; font-size: 1.1em;'>Order details:</h5>
                    <hr style='margin-top: 0px;'/>
                    <div class='details text-right'>
                        <p style='text-align: end; color: black; font-weight: 600; font-size: 0.9em;'>Order ID: <span style='opacity: 0.6;'>".$orderID."</span></p>
                        <p style='text-align: end; color: black; font-weight: 600; font-size: 0.9em;'>Payment Type: <span style='opacity: 0.6;'>".$payment_type."</span></p>
                        <p style='text-align: end; color: black; font-weight: 600; font-size: 0.9em;'>Order Date: <span style='opacity: 0.6;'>".$order_date."</span></p>
                        <p style='text-align: end; color: black; font-weight: 600; font-size: 0.9em;'>Amount: <span style='opacity: 0.6;'>".$amount." Tsh</span></p>
                        <p style='text-align: end; color: black; font-weight: 600; font-size: 0.9em;'>Discount Amount: <span style='opacity: 0.6;'>".$discount_amount." Tsh</span></p>
                        <p style='text-align: end; color: black; font-weight: 600; font-size: 0.9em;'>Total Amount: <span style='opacity: 0.6;'>".$total_amount." Tsh</span></p>
                        <p style='text-align: end; color: black; font-weight: 600; font-size: 0.9em;'>Payment Status: <span style='color: green;'>".$payment_status."</span></p>
                        <hr style='margin-top: 0px;'/>
                    </div>
                </div>
                <div class='footer-words'>
                    <p>To access your purchased digital products, basing on your smartphone platform download our GETVALUE App by clicking on App icons below! Once downloaded you will login using:</p>
                </div>
                
               <div class='buttons' style='text-align: center;'>
                  <a class='btn' href='https://play.google.com/store/apps/details?id=com.getvalue.getvalue'><img src='https://www.getvalue.co/assets/themes/icons/playstore_btn.png' height='43' style='border-radius: 2px;'/></a>
                  <a class='btn' href='https://apps.apple.com/tz/app/getvalue/id1644488065'><img src='https://www.getvalue.co/assets/themes/icons/appstore_btn.png' height='43' style='border-radius: 2px;'/></a>
                </div>
                
                <h5 class='' style='text-align: center; font-size: 1.0em; font-weight: 600;'>Username: ".$email." <br/><span style='margin-top: 5px;'>Password: 123456</span></h5>
                <p style='font-size: 0.8em; font-weight: 500; opacity: 0.85; text-align: center; margin-top: 15px;'>Customer Support Contact <br/>WhatsApp number: +255 717 568 861<br/>Support email: support@getvalue.co</p>
            </div>
        ";
        
        $this->load->library('email');
        
        $email_config['mailtype'] = 'html';
        $this->email->initialize($email_config);
        
 		$this->email->set_header('Organization', 'Get Value Inc');
		$this->email->set_header('MIME-Version', '1.0');
		//$this->email->set_header('Content-type', 'text/plain; charset=iso-8859-1');
		$this->email->set_header('Content-type', 'text/html;');
		$this->email->set_header('X-Priority', '3');
		$this->email->set_header('X-Mailer', "PHP".phpversion());
		$this->email->set_header('Return-Path', "GetValueInc <orders@getvalue.co>");
		$this->email->from('orders@getvalue.co', 'GETVALUE - Updates');
		$this->email->to($email);
// 		$this->email->to('patrickvensilaus98@gmail.com');
		$this->email->reply_to('support@getvalue.co', 'Get Value Inc - Support');
		$this->email->subject("Complete Order Notification!");
		$this->email->message($email_html);
		$this->email->send();
        
        
        
    }
    
    public function sendPendingOrderEmail($data){
        
        $order_details = [];
        
        $this->db->select(['first_name','surname','email','payment_type','amount_paid','status']);
        $this->db->where('orderID', $data['order_id']);
        $this->db->where('user_id', $data['user_id']);
        $this->db->where('status', '0');
        $query11 = $this->db->get('tbl_orders');
        
        if($query11->num_rows() > 0){
            foreach ($query11->result_array() as $data11) {
                $order_details[] = $data11;
            }
        }
        
        $email = $order_details[0]['email'];
        $name = $order_details[0]['first_name']." ".$order_details[0]['surname'];
        $orderID = $data['order_id'];
        $payment_type = $order_details[0]['payment_type'];
        $order_date = date('Y-m-d',strtotime(date('Y-m-d')));
        $amount = $order_details[0]['amount_paid'];
        $discount_amount = 0;
        $total_amount = $order_details[0]['amount_paid'];
        $payment_status = "Pending";

        $productId = $data['product_id'];
        
        $html_email = "
            <div class='container'>
                <div class='main-header'>
                     <img src='https://www.getvalue.co/assets/themes/logo.png' height='50'/>
                </div>
                <h5 style='color: black; font-size: 1.3em;'>Pending Order Notification</h5>
                <hr size='7' style='background-color: black;'/>
                <div class='greetings'>
                    <p style='font-style: italic; opacity: 0.6; font-size: 1.0em;'>Hello ".$name.",</p>
                    <p style='text-transform: capitalize; font-size: 1.0em; font-weight: 600;'>Thank you for wanting to buy from Us!</p>
                </div>
                <div class='content'>
                    <p>GETVALUE has created an Online Library for you, all digital product you have purchased including eBooks, Audiobooks 'and' Online Training programs are stored in 'My Products' section in your online library located in our app.</p>
                    <p style='padding-top: 5px;'>Below is your order details:</p>
                </div>
                <div class='order-details'>
                    <h5 style='color: black; opacity: 0.9; font-size: 1.1em;'>Order details:</h5>
                    <hr style='margin-top: 0px;'/>
                    <div class='details text-right'>
                        <p style='text-align: end; color: black; font-size: 0.9em; font-weight: 600;'>Order ID: <span style='opacity: 0.6;'>".$orderID."</span></p>
                        <p style='text-align: end; color: black; font-weight: 600; font-size: 0.9em;'>Payment Type: <span style='opacity: 0.6;'>".$payment_type."</span></p>
                        <p style='text-align: end; color: black; font-weight: 600; font-size: 0.9em;'>Order Date: <span style='opacity: 0.6;'>".$order_date."</span></p>
                        <p style='text-align: end; color: black; font-weight: 600; font-size: 0.9em;'>Amount: <span style='opacity: 0.6;'>".$amount." Tsh</span></p>
                        <p style='text-align: end; color: black; font-weight: 600; font-size: 0.9em;'>Discount Amount: <span style='opacity: 0.6;'>".$discount_amount." Tsh</span></p>
                        <p style='text-align: end; color: black; font-weight: 600; font-size: 0.9em;'>Total Amount: <span style='opacity: 0.6;'>".$total_amount." Tsh</span></p>
                        <p style='text-align: end; color: black; font-weight: 600; font-size: 0.9em;'>Payment Status: <span style='color: orange;'>".$payment_status."</span></p>
                        <hr style='margin-top: 0px;'/>
                    </div>
                </div>
                <div class='footer-words' style='text-align: center;'>
                    <p>To pay for your pending order click the <b>Buy Now</b> button below:</p>
                </div>
                <div class='buy-now' style='text-align: center;'>
                    <a href='https://www.getvalue.co/home/checkout?product=".$productId."' style='text-align: center;'><img class='opacity-60 rounded-3' src='https://www.getvalue.co/assets/themes/GETVALUE/Buy-now.png' height='45'/></a>
                </div>
                <p class='text-center' style='font-size: 0.8em; font-weight: 500; opacity: 0.85; text-align: center; margin-top: 15px;'>Customer Support Contact <br/>WhatsApp number: +255 717 568 861<br/>Support email: support@getvalue.co</p>
            </div>
        ";
        
        if(count($order_details) <= 0){
            exit;
        }
        
 		$this->load->library('email');
 		
 		$email_config['mailtype'] = 'html';
        $this->email->initialize($email_config);
        
 		$this->email->set_header('Organization', 'Get Value Inc');
		$this->email->set_header('MIME-Version', '1.0');
		//$this->email->set_header('Content-type', 'text/plain; charset=iso-8859-1');
		$this->email->set_header('Content-type', 'text/html;');
		$this->email->set_header('X-Priority', '3');
		$this->email->set_header('X-Mailer', "PHP".phpversion());
		$this->email->set_header('Return-Path', "GetValueInc <orders@getvalue.co>");
		$this->email->from('orders@getvalue.co', 'GETVALUE - Updates');
 		$this->email->to($email);
//  		$this->email->to('patrickvensilaus98@gmail.com');
		$this->email->reply_to('support@getvalue.co', 'Get Value Inc - Support');
		$this->email->subject("Pending Order Notification!");
		$this->email->message($html_email);
		$this->email->send();
    }
    
    public function self_help_details($post){
        if($post!=""){
            $post=sqlSafe($post);
            $data['title']=str_replace("_", " ", $post).' || GetValue Inc';
            $data['post'] = $this->Blog_model->load_self_help_post($post);
            $data['recent_posts'] = $this->Blog_model->load_recent_posts();
            $data['popularBooks'] = $this->Home_model->load_popular_products();
            $this->setView('self_help_details', $data);
        }else{
            redirect(base_url('./'));
        }
    }

    public function blog_post($post){
        if($post!=""){
            $post=sqlSafe($post);
            $data['title']=str_replace("_", " ", $post).' || GetValue Inc';
            $data['post'] = $this->Blog_model->load_blog_post($post);
            $data['recent_posts'] = $this->Blog_model->load_recent_posts();
            $data['popularBooks'] = $this->Home_model->load_popular_products();
            $this->setView('blog_post', $data);
        }else{
            redirect(base_url('./'));
        }
    }

    public function post_user_review(){
        if($this->userToken=="" || $this->userToken==null || empty($this->userToken)){
            die('login');
        }
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters(errMsg(), '</div>');
        $this->form_validation->set_rules('review', 'review', 'trim|required');
        $this->form_validation->set_rules('rate_counter', 'rate counter', 'trim|required');
        $this->form_validation->set_message('required', 'Please write your %s to post');
        $data = array();

        if ($this->form_validation->run() === FALSE){
            if(form_error('rate_counter')!=""){
                die('<div class="alert alert-danger">Please give this product a star</div>');
            }elseif(form_error('review')!=""){
                die(form_error('review'));
            }
        }else{
            $data = $this->input->post();           
            $product=sqlSafe($data['product']);
            $review=json_encode(sqlSafe($data['review']));
            $rate_counter=sqlSafe($data['rate_counter']);
            
            $userID=getUserID($this->userToken);
            $account_type=$this->session->userdata('account_type');
            if($account_type=='customer'){
                $check=$this->Product_model->post_review($userID, $product, $review, $rate_counter, "", "", "", "");
            }elseif($account_type=='admin'){
                $check=$this->Product_model->post_review("", $product, $review, $rate_counter, "", $userID, "", "");
            }else{
                $check=$this->Product_model->post_review("", $product, $review, $rate_counter, "", "", $userID, $account_type);
            }
            
            echo $check;
        }
    }

    public function post_user_comment(){
        if($this->userToken=="" || $this->userToken==null || empty($this->userToken)){
            die('login');
        }
        // $this->load->helper('form');
        $this->form_validation->set_error_delimiters(errMsg(), '</div>');
        $this->form_validation->set_rules('comment', 'comment', 'trim|required');
        $this->form_validation->set_rules('rate_counter', 'star', 'trim|required');
        $this->form_validation->set_message('required', 'Please write your %s to post');
        $data = array();

        if ($this->form_validation->run() === FALSE){
            if(form_error('comment')!=""){
                die(form_error('comment'));
            }elseif(form_error('rate_counter')!=""){
                die('<div class="alert alert-danger">Please give this post a star</div>');
            }
        }else{
            $data = $this->input->post();           
            $post=sqlSafe($data['post']);
            $comment=json_encode(sqlSafe($data['comment']));
            $rate_counter=sqlSafe($data['rate_counter']);            
            $userID=getUserID($this->userToken);
            $account_type=$this->session->userdata('account_type');
            if($account_type=='customer'){
                $check=$this->Blog_model->post_comment($userID, $post, $comment, $rate_counter, "", "", "", "");
            }elseif($account_type=='admin'){
                $check=$this->Blog_model->post_comment("", $post, $comment, $rate_counter, "", $userID, "", "");
            }else{
                $check=$this->Blog_model->post_comment("", $post, $comment, $rate_counter, "", "", $userID, $account_type);
            }            
            echo $check;
        }
    }

    public function add_subscription(){
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters(errMsg(), '</div>');
        $this->form_validation->set_rules('subscribeEmail', 'email', 'trim|required');
        $this->form_validation->set_message('required', 'Please enter your %s');
        $data = array();

        if ($this->form_validation->run() === FALSE){
            if(form_error('subscribeEmail')!=""){
                die(form_error('subscribeEmail'));
            }
        }else{
            $data = $this->input->post();           
            $subscribeEmail=sqlSafe($data['subscribeEmail']);
            $check=$this->Home_model->save_subscription($subscribeEmail);
            echo $check;
        }
    }

    public function post_user_reply_review(){
        if($this->userToken=="" || $this->userToken==null || empty($this->userToken)){
            die('login');
        }
        $data = $this->input->get();           
        $product=sqlSafe($data['product']);
        $parent=sqlSafe($data['parent']);
        $review=json_encode(sqlSafe($data['review']));
        $rate_counter="";
        if(empty($review)){
            die(errMsg("Write your comment to post..."));
        }        
        $userID=getUserID($this->userToken);
        $account_type=$this->session->userdata('account_type');
        if($account_type=='customer'){
            $check=$this->Product_model->post_review($userID, $product, $review, $rate_counter, $parent, "", "", "");
        }elseif($account_type=='admin'){
            $check=$this->Product_model->post_review("", $product, $review, $rate_counter, $parent, $userID, "", "");
        }else{
            $check=$this->Product_model->post_review("", $product, $review, $rate_counter, $parent, "", $userID, $account_type);
        }        
        echo $check;
    }

    public function post_user_reply_comment(){
        if($this->userToken=="" || $this->userToken==null || empty($this->userToken)){
            die('login');
        }
        $data = $this->input->get();           
        $post=sqlSafe($data['post']);
        $parent=sqlSafe($data['parent']);
        $comment=json_encode(sqlSafe($data['comment']));
        if(empty($comment)){
            die(errMsg("Write your comment to post..."));
        }        
        $userID=getUserID($this->userToken);
        $account_type=$this->session->userdata('account_type');
        if($account_type=='customer'){
            $check=$this->Blog_model->post_comment($userID, $post, $comment, "", $parent, "", "", "");
        }elseif($account_type=='admin'){
            $check=$this->Blog_model->post_comment("", $post, $comment, "", $parent, $userID, "", "");
            // $check=$this->Home_model->post_comment($userID, $post, $comment, $rate_counter, $parent, $admin, $seller_id, $seller_type)
        }else{
            $check=$this->Blog_model->post_comment("", $post, $comment, "", $parent, "", $userID, $account_type);
        }        
        echo $check;
    }

    public function load_user_reviews(){
        $data = $this->input->get();           
        $product=sqlSafe($data['product']);
        $check = $this->Product_model->load_user_reviews($product);
        echo $check;
    }

    public function load_user_comments(){
        $data = $this->input->get();           
        $post=sqlSafe($data['post']);
        $check = $this->Blog_model->load_user_comments($post);
        echo $check;
    }

    public function add_cart(){
        $this->is_logged_in();
        $userID=getUserID($this->userToken);
        $user_data=$this->input->get();
        $product=sqlSafe($user_data['product']);
        $price=sqlSafe($user_data['price']);
        $product_url=sqlSafe($user_data['product_url']);
        $referral=sqlSafe($user_data['referral']);
        if(empty($product)){ die('select the product to add');}
        $check = $this->Homeorder_model->add_cart($product, $price, $userID, $product_url, $referral);
        echo $check;
    }

    public function remove_product(){
        $this->is_logged_in();
        $userID=getUserID($this->userToken);
        $user_data=$this->input->get();
        $product=sqlSafe($user_data['product']);
        if(empty($product)){ die('select the product to remove');}
        $check = $this->Homeorder_model->remove_product($product, $userID);
        echo $check;
    }

    public function remove_all_product(){
        $this->is_logged_in();
        $userID=getUserID($this->userToken);
        $check = $this->Homeorder_model->remove_all_product($userID);
        echo $check;
    }

    public function load_cart_items(){
        $this->is_logged_in();
        $userID=getUserID($this->userToken);
        $check = $this->Home_model->load_cart_items($userID);
        echo $check;
    }

    public function check_new_message(){
        $userID=getUserID($this->userToken);
        $result = $this->Home_model->check_new_message($userID);
        echo $result;
    }

	private function is_logged_in(){
		$is_logged_in = $this->session->userdata('getvalue_user_idetification');
		if (!isset($is_logged_in) || $is_logged_in == "") {
	 		die('login');
		}
	}
}
