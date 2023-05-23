<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Account extends CI_Controller {

    	public function __construct(){
            parent::__construct();
            $this->load->model('admin/Account_model');
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

        public function add_admin(){
            $data['update'] = '';
            $data['title'] = 'New System Admin - GetValue';
            $this->setView('add_admin', $data);
        }

        public function customers(){
            $data['title'] = "Customer Accounts - GetValue";
            $data['table_rows'] = $this->Account_model->load_customers();
            $this->setView('customers', $data);
        }

        public function system_admins(){
            $data['title'] = "Admin Accounts - GetValue";
            $data['table_rows'] = $this->Account_model->load_system_admins();
            $this->setView('system_admins', $data);
        }

        public function vendors(){
            $data['title'] = "Vendor Accounts - GetValue";
            $data['table_rows'] = $this->Account_model->load_vendors();
            $this->setView('vendors', $data);
        }

        public function affiliates(){
            $data['title'] = "Affiliate Accounts - GetValue";
            $data['table_rows'] = $this->Account_model->load_affiliates();
            $this->setView('affiliates', $data);
        }

        public function pending_accounts(){
            $data['title'] = "Pending Accounts - GetValue";
            $data['table_rows'] = $this->Account_model->load_pending_accounts();
            $this->setView('pending_accounts', $data);
        }

        /*Action*/

        public function save_new_system_admin(){
            $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
            $this->form_validation->set_rules('surname', 'Surname', 'trim|required');
            $this->form_validation->set_rules('gender', 'Gender', 'trim|required');
            $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            $data1 = array();
            $data1['title'] = 'Add System Admin - GetValue';

            if ($this->form_validation->run() === FALSE){
                $this->setView('add_admin', $data1);
            }else{
                $user_data = $this->input->post();

                $data = array(
                    'first_name' => $user_data['first_name'],
                    'surname' => $user_data['surname'],
                    'gender' => $user_data['gender'],
                    'phone' => $user_data['phone'],
                    'email' => $user_data['email']
                );
                $userID=getUserID($this->userToken);

                $check=$this->Account_model->save_new_system_admin($data, $userID);
                if($check=='Success'){
                    $this->session->set_flashdata('feedback', successMsg('New System Admin Saved Successfully.'));
                    $this->setView('add_admin', $data1);
                    redirect(base_url('admin/account/add_admin'));
                }else{
                    $this->session->set_flashdata('feedback', errorMsg('Failed! '.$check));
                    $this->setView('add_admin', $data1);
                }
            }
        }

        public function save_account_levels(){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('seller', 'seller', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('seller')!=""){
                    die(form_error('seller'));
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);

                $check=$this->Account_model->save_account_levels($user_data, $userID);
                echo $check;
            }
        }

        public function check_new_seller_accounts(){
            $result = $this->Account_model->check_new_seller_accounts();
            echo $result;
        }

        public function is_trusted_vendor($account){
            $userID=getUserID($this->userToken);
            $account=sqlSafe($account);
            $check = $this->Account_model->is_trusted_vendor($account, $userID);
            echo $check;
        }

        public function is_not_trusted_vendor($account){
            $userID=getUserID($this->userToken);
            $account=sqlSafe($account);
            $check = $this->Account_model->is_not_trusted_vendor($account, $userID);
            echo $check;
        }

        public function activate_new_seller_account($account){
            $userID=getUserID($this->userToken);
            $account=sqlSafe($account);
            $check = $this->Account_model->activate_new_seller_account($account, $userID);
            echo $check;
        }

        public function activate_seller_account($account){
            $userID=getUserID($this->userToken);
            $account=sqlSafe($account);
            $check = $this->Account_model->activate_seller_account($account, $userID);
            echo $check;
        }

        public function diactivate_seller_account($account){
            $userID=getUserID($this->userToken);
            $account=sqlSafe($account);
            $check = $this->Account_model->diactivate_seller_account($account, $userID);
            echo $check;
        }

        public function activate_admin_account($account){
            $userID=getUserID($this->userToken);
            $account=sqlSafe($account);
            $check = $this->Account_model->activate_admin_account($account, $userID);
            echo $check;
        }

        public function diactivate_admin_account($account){
            $userID=getUserID($this->userToken);
            $account=sqlSafe($account);
            $check = $this->Account_model->diactivate_admin_account($account, $userID);
            echo $check;
        }

        public function activate_customer_account($account){
            $userID=getUserID($this->userToken);
            $account=sqlSafe($account);
            $check = $this->Account_model->activate_customer_account($account, $userID);
            echo $check;
        }

        public function diactivate_customer_account($account){
            $userID=getUserID($this->userToken);
            $account=sqlSafe($account);
            $check = $this->Account_model->diactivate_customer_account($account, $userID);
            echo $check;
        }
        
        public function delete_seller_account($account){
            $userID=getUserID($this->userToken);
            $account=sqlSafe($account);
            $check = $this->Account_model->delete_seller_account($account, $userID);
            echo $check;
        }
        
        public function delete_admin_account($account){
            $userID=getUserID($this->userToken);
            $account=sqlSafe($account);
            $check = $this->Account_model->delete_customer_account($account, $userID);
            echo $check;
        }
        
        public function delete_customer_account($account){
            $userID=getUserID($this->userToken);
            $account=sqlSafe($account);
            $check = $this->Account_model->delete_customer_account($account, $userID);
            echo $check;
        }
        
        public function reset_user_password($account){
            $userID=getUserID($this->userToken);
            $account=sqlSafe($account);
            $check = $this->Account_model->reset_user_password($account, $userID);
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