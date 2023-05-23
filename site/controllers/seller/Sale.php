<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Sale extends CI_Controller {

    	public function __construct(){
            parent::__construct();
            $this->load->model('seller/Sale_model');
            $this->load->helper('adminfx_helper');
            $this->isLoggedIn();
            $this->isRightUser();
            $this->userToken=$this->session->userdata('getvalue_user_idetification');
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

        public function coupons(){
            $data['title'] ='Coupons - GetValue';
            $userID=getUserID($this->userToken);
            $account_type=sqlSafe($this->session->userdata('account_type'));
            $data['table_rows'] = $this->Sale_model->coupons($userID, $account_type);
            $data['userID'] = $userID;
            $data['update'] = "";
            $this->setView('coupons', $data);
        }

        public function sales(){
            $data['title'] ='Sales - GetValue';
            $userID=getUserID($this->userToken);
            $account_type=sqlSafe($this->session->userdata('account_type'));
            $data['items'] = $this->Sale_model->sales($userID);
            $this->setView('sales', $data);
        }

        public function affiliate_sales(){
            $data['title'] ='Affiliate Sales - GetValue';
            $userID=getUserID($this->userToken);
            $data['items'] = $this->Sale_model->affiliate_sales($userID);
            $this->setView('affiliate_sales', $data);
        }

        public function refunds(){
            $data['title'] ='Refunds - GetValue';
            $userID=getUserID($this->userToken);
            $account_type=sqlSafe($this->session->userdata('account_type'));
            $data['refunds'] = $this->Sale_model->customer_refunds($userID);
            $this->setView('refunds', $data);
        }

        public function affiliate_refunds(){
            $data['title'] ='Affiliate Refunds - GetValue';
            $userID=getUserID($this->userToken);
            $account_type=sqlSafe($this->session->userdata('account_type'));
            $data['refunds'] = $this->Sale_model->affiliate_customer_refunds($userID);
            $this->setView('affiliate_refunds', $data);
        }

        public function edit_product_coupon($info){
            $data['title'] ='Edit Product Coupon - GetValue';
            if($info!=""){
                $userID=getUserID($this->userToken);
                $account_type=sqlSafe($this->session->userdata('account_type'));
                $info=sqlSafe($info);
                $data['edit_rows'] = $this->Sale_model->load_coupon_details($info, $userID);
                $data['update'] = $info;
                $data['userID'] = $userID;
                $data['table_rows'] = $this->Sale_model->coupons($userID, $account_type);
                $this->setView('coupons', $data);
            }else{
                redirect('seller/coupons');
            }
        }

        public function check_new_refund(){
            $userID=getUserID($this->userToken);
            $result = $this->Sale_model->check_new_refund($userID);
            echo $result;
        }

        public function check_new_affiliate_refund(){
            $userID=getUserID($this->userToken);
            $result = $this->Sale_model->check_new_affiliate_refund($userID);
            echo $result;
        }

        /*Action*/

        public function save_shop_coupons(){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('product', 'product', 'trim|required');
            $this->form_validation->set_rules('coupon_code', 'coupon code', 'trim|required|is_unique[tbl_seller_coupons.coupon_code]');
            $this->form_validation->set_rules('coupon_value', 'coupon value', 'trim|required');
            $this->form_validation->set_rules('expire_date', 'expire date', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            $this->form_validation->set_message('is_unique', 'The %s is already registered.');
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('product')!=""){
                    die(form_error('product'));
                }elseif(form_error('coupon_code')!=""){
                    die(form_error('coupon_code'));
                }elseif(form_error('coupon_value')!=""){
                    die(form_error('coupon_value'));
                }elseif(form_error('expire_date')!=""){
                    die(form_error('expire_date'));
                }
            }else{
                $user_data = $this->input->post();
                $account_type=sqlSafe($this->session->userdata('account_type'));
                $userID=getUserID($this->userToken);

                $check=$this->Sale_model->save_shop_coupons($user_data, $account_type, $userID);
                echo $check;
            }
        }

        public function update_shop_coupons($coupon){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('coupon_value', 'coupon value', 'trim|required');
            $this->form_validation->set_rules('expire_date', 'expire date', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            $this->form_validation->set_message('is_unique', 'The %s is already registered.');
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('coupon_value')!=""){
                    die(form_error('coupon_value'));
                }elseif(form_error('expire_date')!=""){
                    die(form_error('expire_date'));
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);
                $coupon=sqlSafe($coupon);

                $check=$this->Sale_model->update_shop_coupons($coupon, $user_data, $userID);
                echo $check;
            }
        }

        public function approve_customer_refund($refundID){
            /*$userID=getUserID($this->userToken);
            $account_type=sqlSafe($this->session->userdata('account_type'));
            $refundID=sqlSafe($refundID);
            $check = $this->Sale_model->approve_customer_refund($refundID, $userID, $account_type);
            echo $check;*/
        }

        public function force_approve_customer_refund($refundID){
            /*$userID=getUserID($this->userToken);
            $account_type=sqlSafe($this->session->userdata('account_type'));
            $refundID=sqlSafe($refundID);
            $check = $this->Sale_model->force_approve_customer_refund($refundID, $userID, $account_type);
            echo $check;*/
        }

        public function delete_seller_coupon($info){
            $userID=getUserID($this->userToken);
            $account_type=sqlSafe($this->session->userdata('account_type'));
            $info=sqlSafe($info);
            $check = $this->Sale_model->delete_seller_coupon($info, $userID, $account_type);
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