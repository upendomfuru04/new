<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Order extends CI_Controller {

    	public function __construct(){
            parent::__construct();
            $this->load->model('seller/Order_model');
            $this->load->helper('adminfx_helper');
            $this->isLoggedIn();
            $this->isRightUser();
            $this->userToken=$this->session->userdata('getvalue_user_idetification');
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

        public function check_new_order(){
            $userID=getUserID($this->userToken);
            $result = $this->Order_model->check_new_order($userID);
            echo $result;
        }

        public function check_new_affiliate_order(){
            $userID=getUserID($this->userToken);
            $result = $this->Order_model->check_new_affiliate_order($userID);
            echo $result;
        }

        public function orders(){
            $data['title'] ='Orders - GetValue';
            $userID=getUserID($this->userToken);
            $data['orders'] = $this->Order_model->orders($userID);
            $data['userID'] = $userID;
            $this->setView('orders', $data);
        }

        public function affiliate_orders(){
            $data['title'] ='Affiliate Orders - GetValue';
            $userID=getUserID($this->userToken);
            $data['orders'] = $this->Order_model->affiliate_orders($userID);
            $data['userID'] = $userID;
            $this->setView('affiliate_orders', $data);
        }

        public function order_details($orderID){
            $data['title'] ='Order Details - GetValue';
            $userID=getUserID($this->userToken);
            $data['items'] = $this->Order_model->order_items($orderID, $userID);
            $data['order'] = $this->Order_model->load_order_details($orderID, $userID);
            $data['orderID'] = $orderID;
            $this->setView('order_details', $data);
        }
        
        /*Action*/

        public function approve_customer_order($orderID){
            $userID=getUserID($this->userToken);
            $orderID=sqlSafe($orderID);
            $check = $this->Order_model->approve_customer_order($orderID, $userID, $this->account_type);
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