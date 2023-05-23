<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Home extends CI_Controller {

    	public function __construct(){
            parent::__construct();
            $this->load->model('seller/Seller_model');
            $this->load->helper('adminfx_helper');
            $this->isLoggedIn();
            $this->isRightUser();
            $this->userToken=sqlSafe($this->session->userdata('getvalue_user_idetification'));
            $this->account_type=sqlSafe($this->session->userdata('account_type'));
            if(getUserID($this->userToken)==""){
                unset($_SESSION['getvalue_user_idetification']);
                $this->session->sess_destroy();
                redirect(base_url().'login');
            }
        }

        public function index($page='index'){
            if(!file_exists(APPPATH.'views/pages/seller/'.$page.'.php')){
                show_404();
            }
            
            $data['title'] = 'Seller - GetValue';
            $data['update'] = '';
            $this->setView($page, $data);
        }

        /*public function view($page){
            $data['update'] = '';
            if($page=='vendor' || $page=='insider' || $page=='outsider' || $page=='contributor'){
                $data['title'] = ucwords(str_replace("_", " ", $page)).' - GetvalueInc';
                $this->setView('index', $data);
            }else{
                if($page=='products'){
                    $this->load_products($page);
                }elseif($page=='media_center'){
                    $this->media_center($page);
                }elseif($page=='social_accounts'){
                    $this->social_accounts($page);
                }elseif($page=='payment_info'){
                    $this->payment_info($page);
                }elseif($page=='shop_info'){
                    $this->shop_info($page);
                }elseif($page=='coupons'){
                    $this->coupons($page);
                }elseif($page=='order_details'){
                    $this->order_details($page);
                }else{
                    $data['title'] = ucwords(str_replace("_", " ", $page)).' - GetvalueInc';
                    $this->setView($page, $data);
                }
            }
        }*/

        public function setView($page='index', $data=""){
            $this->load->view('includes/seller_header.php', $data);
            $this->load->view('pages/seller/'.$page);
            $this->load->view('includes/seller_footer.php');
        }

        public function vendor(){
            $data['title'] = 'Vendor - GetValue';
            $userID=getUserID($this->userToken);
            $data['userID']=$userID;
            $data['totalProducts']=$this->Seller_model->getTotalProducts($userID);
            $data['totalReferrals']=$this->Seller_model->getTotalReferrals($userID);
            $data['pendingPost']=$this->Seller_model->getPendingPost($userID);
            $data['pendingCommissions']=$this->Seller_model->getPendingCommissions($userID);
            $data['approvedPost']=$this->Seller_model->getApprovedPost($userID);
            $data['paidCommissions']=$this->Seller_model->getPaidCommissions($userID);
            $data['pendingOrders']=$this->Seller_model->getPendingOrders($userID);
            $data['pendingAffiliateOrders']=$this->Seller_model->getPendingAffiliateOrders($userID);
            $data['totalOrders']=$this->Seller_model->getTotalOrders($userID);
            $data['totalAffiliateOrders']=$this->Seller_model->getTotalAffiliateOrders($userID);
            $this->setView('index', $data);
        }

        public function insider(){
            $data['title'] = 'Insider - GetValue';
            $userID=getUserID($this->userToken);
            $data['userID']=$userID;
            $data['pendingPost']=$this->Seller_model->getPendingPost($userID);
            $data['totalProducts']=$this->Seller_model->getTotalProducts($userID);
            $data['totalReferrals']=$this->Seller_model->getTotalReferrals($userID);
            $data['approvedPost']=$this->Seller_model->getApprovedPost($userID);
            $data['paidCommissions']=$this->Seller_model->getPaidCommissions($userID);
            $data['pendingOrders']=$this->Seller_model->getPendingOrders($userID);
            $data['pendingAffiliateOrders']=$this->Seller_model->getPendingAffiliateOrders($userID);
            $data['pendingCommissions']=$this->Seller_model->getPendingCommissions($userID);
            $data['totalOrders']=$this->Seller_model->getTotalOrders($userID);
            $data['totalAffiliateOrders']=$this->Seller_model->getTotalAffiliateOrders($userID);
            $this->setView('index', $data);
        }

        public function outsider(){
            $data['title'] = 'Outsider - GetValue';
            $userID=getUserID($this->userToken);
            $data['userID']=$userID;
            $data['totalProducts']=$this->Seller_model->getTotalProducts($userID);
            $data['totalReferrals']=$this->Seller_model->getTotalReferrals($userID);
            $this->setView('index', $data);
        }

        public function contributor(){
            $data['title'] = 'Contributor - GetValue';
            $userID=getUserID($this->userToken);
            $data['userID']=$userID;
            $data['pendingPost']=$this->Seller_model->getPendingPost($userID);
            $data['totalProducts']=$this->Seller_model->getTotalProducts($userID);
            $data['totalReferrals']=$this->Seller_model->getTotalReferrals($userID);
            $this->setView('index', $data);
        }

        public function save_shop_info(){
            $user_data = $this->input->post();
            $userID=getUserID($this->userToken);

            $check=$this->Seller_model->save_shop_info($user_data, $this->account_type, $userID);
            echo $check;
        }

        public function update_shop_info($product){
            $user_data = $this->input->post();
            $userID=getUserID($this->userToken);
            $product=sqlSafe($product);

            $check=$this->Seller_model->update_shop_info($product, $user_data, $this->account_type, $userID);
            echo $check;
        }

        public function media_center(){
            $data['title'] ='Media Center - GetValue';
            $userID=getUserID($this->userToken);
            $data['table_rows'] = $this->Seller_model->media_center($userID, $this->account_type);
            $this->setView('media_center', $data);
        }

        public function password_reset(){
            $userID=getUserID($this->userToken);
            $data['title'] = 'Password Reset - GetValue';
            $this->setView('password_reset', $data);
        }

        public function edit_social_account($social){
            $data['title'] ='Edit Social Account - GetValue';
            if($social!=""){
                $userID=getUserID($this->userToken);
                $social=sqlSafe($social);
                $data['edit_rows'] = $this->Seller_model->load_social_media_details($social, $userID, $this->account_type);
                $data['update'] = $social;
                $data['table_rows'] = $this->Seller_model->social_accounts($userID, $this->account_type);
                $this->setView('social_accounts', $data);
            }else{
                redirect('seller/social_accounts');
            }
        }

        public function edit_payment_info($info){
            $data['title'] ='Edit Payment Info - GetValue';
            if($info!=""){
                $userID=getUserID($this->userToken);
                $info=sqlSafe($info);
                $data['edit_rows'] = $this->Seller_model->load_payment_info_details($info, $userID, $this->account_type);
                $data['update'] = $info;
                $data['table_rows'] = $this->Seller_model->payment_info($userID, $this->account_type);
                $this->setView('payment_info', $data);
            }else{
                redirect('seller/payment_info');
            }
        }

        public function edit_shop_info($info){
            $data['title'] ='Edit Shop Info - GetValue';
            if($info!=""){
                $userID=getUserID($this->userToken);
                $info=sqlSafe($info);
                $data['edit_rows'] = $this->Seller_model->load_shop_info_details($info, $userID, $this->account_type);
                $data['update'] = $info;
                $data['userID'] = $userID;
                $data['table_rows'] = $this->Seller_model->shop_info($userID, $this->account_type);
                $this->setView('shop_info', $data);
            }else{
                redirect('seller/shop_info');
            }
        }

        public function social_accounts(){
            $data['title'] ='Social Accounts - GetValue';
            $userID=getUserID($this->userToken);
            $data['table_rows'] = $this->Seller_model->social_accounts($userID, $this->account_type);
            $data['update'] = "";
            $this->setView('social_accounts', $data);
        }

        public function shop_info(){
            $data['title'] ='Shop Info - GetValue';
            $userID=getUserID($this->userToken);
            $data['table_rows'] = $this->Seller_model->shop_info($userID, $this->account_type);
            $data['userID'] = $userID;
            $data['update'] = "";
            $this->setView('shop_info', $data);
        }

        public function check_new_message(){
            $userID=getUserID($this->userToken);
            $result = $this->Seller_model->check_new_message($userID);
            echo $result;
        }

        public function check_new_affiliate(){
            $userID=getUserID($this->userToken);
            $result = $this->Seller_model->check_new_affiliate($userID);
            echo $result;
        }

        public function new_message(){
            $data['update'] = '';
            $data['title'] ='New Messages - GetValue';
            $recepient="";
            $user_data=$this->input->get();
            if(isset($user_data['recepient']) && $user_data['recepient']!=""){
                $recepient=$user_data['recepient'];
                $data['info'] = $this->Seller_model->seller_info($recepient);
            }
            $userID=getUserID($this->userToken);
            $data['recepient'] =$recepient;
            $data['userID'] =$userID;
            $this->setView('new_message', $data);
        }

        public function messages(){
            $data['title'] ='Messages - GetValue';
            $userID=getUserID($this->userToken);
            $data['messages'] = $this->Seller_model->load_messages($userID);
            $this->setView('messages', $data);
        }

        public function message_details($msg){
            $data['title'] ='Message Details - GetValue';
            $userID=getUserID($this->userToken);
            $data['userID']=$userID;
            $msg=sqlSafe($msg);
            $data['table_rows'] = $this->Seller_model->load_message_detail($msg, $userID);
            $this->setView('message_details', $data);
        }

        public function payment_info(){
            $data['title'] ='Payment Info - GetValue';
            $userID=getUserID($this->userToken);
            $data['table_rows'] = $this->Seller_model->payment_info($userID, $this->account_type);
            $data['update'] = "";
            $this->setView('payment_info', $data);
        }

        /*Action*/

        public function save_msg_reply(){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('message', 'message', 'trim|required');
            $this->form_validation->set_rules('receiver', 'receiver', 'trim|required');
            $this->form_validation->set_rules('parent_msg', 'parent msg', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('message')!=""){
                    die(form_error('message'));
                }elseif(form_error('receiver')!=""){
                    die(form_error('receiver'));
                }elseif(form_error('parent_msg')!=""){
                    die(form_error('parent_msg'));
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);

                $check=$this->Seller_model->save_msg_reply($user_data, $userID);
                echo $check;
            }
        }

        public function save_social_accounts(){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('name', 'social media', 'trim|required');
            $this->form_validation->set_rules('link', 'link', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('name')!=""){
                    die(form_error('name'));
                }elseif(form_error('link')!=""){
                    die(form_error('link'));
                }
            }else{
                $user_data = $this->input->post();
                $account_type=$this->session->userdata('account_type');
                $userID=getUserID($this->userToken);

                $check=$this->Seller_model->save_social_accounts($user_data, $account_type, $userID);
                echo $check;
            }
        }

        public function save_new_message(){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('subject', 'subject', 'trim|required');
            $this->form_validation->set_rules('message', 'message', 'trim|required');
            $this->form_validation->set_rules('receiver', 'receiver', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('subject')!=""){
                    die(form_error('subject'));
                }elseif(form_error('message')!=""){
                    die(form_error('message'));
                }elseif(form_error('receiver')!=""){
                    die(form_error('receiver'));
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);

                $check=$this->Seller_model->save_new_message($user_data, $userID);
                echo $check;
            }
        }

        public function update_social_accounts($social){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('name', 'social media', 'trim|required');
            $this->form_validation->set_rules('link', 'link', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            if(empty($social)){ die(errorMsg('Select Account'));}
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('name')!=""){
                    die(form_error('name'));
                }elseif(form_error('link')!=""){
                    die(form_error('link'));
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);
                $social=sqlSafe($social);

                $check=$this->Seller_model->update_social_accounts($social, $user_data, $this->account_type, $userID);
                echo $check;
            }
        }

        public function save_payment_info(){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('method', 'payment method', 'trim|required');
            $this->form_validation->set_rules('payment_type', 'payment type', 'trim|required');
            $this->form_validation->set_rules('provider_name', 'provider name', 'trim|required');
            $this->form_validation->set_rules('account_name', 'account number', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('method')!=""){
                    die(form_error('method'));
                }elseif(form_error('payment_type')!=""){
                    die(form_error('payment_type'));
                }elseif(form_error('provider_name')!=""){
                    die(form_error('provider_name'));
                }elseif(form_error('account_name')!=""){
                    die(form_error('account_name'));
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);

                $check=$this->Seller_model->save_payment_info($user_data, $this->account_type, $userID);
                echo $check;
            }
        }

        public function update_payment_info($info){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('method', 'payment method', 'trim|required');
            $this->form_validation->set_rules('payment_type', 'payment type', 'trim|required');
            $this->form_validation->set_rules('provider_name', 'provider name', 'trim|required');
            $this->form_validation->set_rules('account_name', 'account name', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            if(empty($info)){ die(errorMsg('Select row'));}
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('method')!=""){
                    die(form_error('method'));
                }elseif(form_error('payment_type')!=""){
                    die(form_error('payment_type'));
                }elseif(form_error('provider_name')!=""){
                    die(form_error('provider_name'));
                }elseif(form_error('account_name')!=""){
                    die(form_error('account_name'));
                }
            }else{
                $user_data = $this->input->post();
                $info=sqlSafe($info);
                $userID=getUserID($this->userToken);

                $check=$this->Seller_model->update_payment_info($info, $user_data, $this->account_type, $userID);
                echo $check;
            }
        }

        public function delete_shop_info($info){
            $userID=getUserID($this->userToken);
            $info=sqlSafe($info);
            $check = $this->Seller_model->delete_shop_info($info, $userID, $this->account_type);
            echo $check;
        }

        public function delete_social_account($social){
            $userID=getUserID($this->userToken);
            $social=sqlSafe($social);
            $check = $this->Seller_model->delete_social_account($social, $userID, $this->account_type);
            echo $check;
        }

        public function delete_payment_info($info){
            $userID=getUserID($this->userToken);
            $info=sqlSafe($info);
            $check = $this->Seller_model->delete_payment_info($info, $userID, $this->account_type);
            echo $check;
        }

        /*end*/

        public function isRightUser(){
            if($this->session->userdata('account_type')!='insider' && $this->session->userdata('account_type')!='outsider' && $this->session->userdata('account_type')!='vendor' && $this->session->userdata('account_type')!='contributor'){
                redirect(base_url(''));
            }
        }

        private function is_logged_in(){
            $is_logged_in = $this->session->userdata('getvalue_user_idetification');
            if (!isset($is_logged_in) || $is_logged_in == "") {
                echo 'login';
                exit;
            }
        }

        private function isLoggedIn(){
            $is_logged_in = $this->session->userdata('getvalue_user_idetification');
            if (!isset($is_logged_in) || $is_logged_in == "") {
                // redirect(base_url('login'));
                //Sleep for five seconds.
                sleep(5);
                header('Location: '.base_url().'login'); 
                exit;
            }
        }
    }
