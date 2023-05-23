<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Product extends CI_Controller {

    	public function __construct(){
            parent::__construct();
            $this->load->model('seller/Product_model');
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
            redirect(base_url('seller/home'));
        }

        public function setView($page='index', $data=""){
            $this->load->view('includes/seller_header.php', $data);
            $this->load->view('pages/seller/'.$page);
            $this->load->view('includes/seller_footer.php');
        }

        public function add_product(){
            $data['update'] = '';
            $data['title'] = 'Add Product - GetValue';
            $this->setView('add_product', $data);
        }

        public function products(){
            $data['title'] ='Products - GetValue';
            $userID=getUserID($this->userToken);
            // $data['title'] = 'Seller - GetvalueInc';
            $data['table_rows'] = $this->Product_model->load_products($userID, $this->account_type);
            $this->setView('products', $data);
        }

        public function referrals(){
            $data['title'] ='Referrals - GetValue';
            $userID=getUserID($this->userToken);
            $data['table_rows'] = $this->Product_model->load_referrals($userID);
            $this->setView('referrals', $data);
        }

        public function referrals_vendors(){
            $data['title'] ='Vendor Referrals - GetValue';
            $userID=getUserID($this->userToken);
            $data['table_rows'] = $this->Product_model->load_referrals_vendors($userID);
            $this->setView('referrals_vendors', $data);
        }

        public function affiliate_marketers(){
            $data['title'] ='Affiliate Marketers - GetValue';
            $userID=getUserID($this->userToken);
            $data['marketers'] = $this->Product_model->load_affiliate_marketers($userID);
            $this->setView('affiliate_marketers', $data);
        }

        public function vendor_products(){
            $data['title'] ='Vendor Products - GetValue';
            $user_data=$this->input->get();
            $seller="";
            if(isset($user_data['seller']) && $user_data['seller']!=""){
                $seller=sqlSafe($user_data['seller']);
            }else{
                redirect(base_url('seller'));
            }
            $userID=getUserID($this->userToken);
            $data['userID']=$userID;
            $data['products'] = $this->Product_model->load_seller_products($seller, $userID);
            $data['info'] = $this->Product_model->get_seller_info($seller);
            $data['profile_url'] = $this->Product_model->get_seller_profile_url($userID);
            $this->setView('vendor_products', $data);
        }

        public function product_details($product){
            $data['title'] ='Product Details - GetValue';
            $userID=getUserID($this->userToken);
            $product=sqlSafe($product);
            $data['table_rows'] = $this->Product_model->load_product_details($product, $userID, $this->account_type);
            $this->setView('product_details', $data);
        }

        public function affiliate_urls(){
            $userID=getUserID($this->userToken);
            $data['update'] = "";
            $data['title'] = "Affiliate Urls - GetValue";
            //$data['orders'] = $this->Product_model->orders($userID);
            $data['userID'] = $userID;
            $this->setView('affiliate_urls', $data);
        }

        public function vendor_url(){
            $userID=getUserID($this->userToken);
            $data['update'] = "";
            $data['title'] = "Vendor Url - GetValue";
            $data['vendor_url'] = $this->Product_model->vendor_url($userID);
            $data['userID'] = $userID;
            $this->setView('vendor_url', $data);
        }

        public function edit_product($product){
            if($product!=""){
                $userID=getUserID($this->userToken);
                $account_type=sqlSafe($this->session->userdata('account_type'));
                $product=sqlSafe($product);
                $data['table_rows'] = $this->Product_model->load_product_details($product, $userID, $account_type);
                $data['title'] = 'Update Product - GetValue';
                $data['update'] = $product;
                $this->setView('add_product', $data);
            }else{
                redirect('seller/products');
            }
        }

        /*action*/


        public function save_product(){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('name', 'product name', 'trim|required');
            $this->form_validation->set_rules('category', 'product category', 'trim|required');
            $this->form_validation->set_rules('price', 'price', 'trim|required');
            $this->form_validation->set_rules('summary', 'summary', 'trim|required');
            $this->form_validation->set_rules('description', 'description', 'trim|required');
            // $this->form_validation->set_rules('media_type', 'media type', 'trim|required');
            $this->form_validation->set_rules('preview_type', 'preview type', 'trim|required');
            $this->form_validation->set_rules('episode_count', 'episode count', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('name')!=""){
                    die(form_error('name'));
                }elseif(form_error('category')!=""){
                    die(form_error('category'));
                }elseif(form_error('price')!=""){
                    die(form_error('price'));
                }elseif(form_error('summary')!=""){
                    die(form_error('summary'));
                }elseif(form_error('description')!=""){
                    die(form_error('description'));
                }elseif(form_error('preview_type')!=""){
                    die(form_error('preview_type'));
                }
            }else{
                $user_data = $this->input->post();
                $account_type=$this->session->userdata('account_type');
                $userID=getUserID($this->userToken);

                if((!isset($user_data['virtual_link']) || $user_data['virtual_link']=="") && (!isset($user_data['media_type']) || $user_data['media_type']=="")){
                    die(errorMsg("Media file or virtual product link should not be empty"));
                }

                $check=$this->Product_model->save_product($user_data, $account_type, $userID);
                echo $check;
            }
        }

        public function update_product($product){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('name', 'product name', 'trim|required');
            $this->form_validation->set_rules('category', 'product category', 'trim|required');
            $this->form_validation->set_rules('price', 'price', 'trim|required');
            $this->form_validation->set_rules('summary', 'summary', 'trim|required');
            $this->form_validation->set_rules('description', 'description', 'trim|required');
            // $this->form_validation->set_rules('media_type', 'media type', 'trim|required');
            $this->form_validation->set_rules('preview_type', 'preview type', 'trim|required');
            $this->form_validation->set_rules('episode_count', 'episode count', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            if(empty($product)){ die(errorMsg('Select Product'));}
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('name')!=""){
                    die(form_error('name'));
                }elseif(form_error('category')!=""){
                    die(form_error('category'));
                }elseif(form_error('price')!=""){
                    die(form_error('price'));
                }elseif(form_error('summary')!=""){
                    die(form_error('summary'));
                }elseif(form_error('description')!=""){
                    die(form_error('description'));
                }elseif(form_error('preview_type')!=""){
                    die(form_error('preview_type'));
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);
                $product=sqlSafe($product);

                if((!isset($user_data['virtual_link']) || $user_data['virtual_link']=="") && (!isset($user_data['media_type']) || $user_data['media_type']=="") && ((!isset($user_data['holder_file_name0']) || $user_data['holder_file_name0']=="") || (!isset($user_data['holder_file_name1']) || $user_data['holder_file_name1']==""))){
                    die(errorMsg("Media file or virtual product link should not be empty"));
                }

                $check=$this->Product_model->edit_product($product, $user_data, $this->account_type, $userID);
                echo $check;
            }
        }

        public function save_affiliate_url(){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('product', 'page url', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('product')!=""){
                    $error=true;
                    $res=form_error('product');
                    $result = json_encode(array('error' => $error, 'res' => $res));
                    die($result);
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);

                $check=$this->Product_model->save_affiliate_url($user_data, $userID);
                echo $check;
            }
        }

        public function save_affiliateurl(){
            $affiliate_url=$this->input->get('affiliate_url');
            $product=$this->input->get('product');
            if(isset($affiliate_url) && $affiliate_url!="" && isset($product) && $product!=""){
                $userID=getUserID($this->userToken);
                $check=$this->Product_model->save_affiliateurl($affiliate_url, $product, $userID);
                echo $check;
            }else{
                echo 'missing url';
            }
        }

        public function save_vendor_url(){
            $userID=getUserID($this->userToken);
            $check=$this->Product_model->save_vendor_url($userID);
            echo $check;
        }

        public function delete_product($product){
            $userID=getUserID($this->userToken);
            $product=sqlSafe($product);
            $check = $this->Product_model->delete_product($product, $userID, $this->account_type);
            echo $check;
        }

        public function delete_referral_url($product){
            $userID=getUserID($this->userToken);
            $product=sqlSafe($product);
            $check = $this->Product_model->delete_referral_url($product, $userID);
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