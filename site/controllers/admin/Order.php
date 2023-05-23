<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Order extends CI_Controller {

    	public function __construct(){
            parent::__construct();
            $this->load->model('admin/Order_model');
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

        public function index($page="index"){
            redirect(base_url('admin'));
        }

        public function setView($page='index', $data=""){
            $this->load->view('includes/admin_header.php', $data);
            $this->load->view('pages/admin/'.$page);
            $this->load->view('includes/admin_footer.php');
        }

        public function orders(){
            $data['title'] = 'Orders - GetValue';
            $data['orders'] = $this->Order_model->orders();
            $this->setView('orders', $data);
        }

        public function order_details($orderID){
            $data['title'] = 'Order Details - GetValue';
            $userID=getUserID($this->userToken);
            $orderID=sqlSafe($orderID);
            $data['items'] = $this->Order_model->order_items($orderID, $userID);
            $data['order'] = $this->Order_model->load_order_details($orderID, $userID);
            $data['orderID'] = $orderID;
            $this->setView('order_details', $data);
        }
        public function check_new_order(){
            $result = $this->Order_model->check_new_order();
            echo $result;
        }

        public function delete_order($order){
            $userID=getUserID($this->userToken);
            $order=sqlSafe($order);
            $check = $this->Order_model->delete_order($order, $userID);
            echo $check;
        }

        public function approve_customer_order($orderID){
            $userID=getUserID($this->userToken);
            $orderID=sqlSafe($orderID);
            $check = $this->Order_model->approve_customer_order($orderID, $userID);
            echo $check;
        }

        /**/
        public function isRightUser(){
            if($this->session->userdata('account_type')!='admin'){
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