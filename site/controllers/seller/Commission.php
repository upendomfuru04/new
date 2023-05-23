<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Commission extends CI_Controller {

    	public function __construct(){
            parent::__construct();
            $this->load->model('seller/Commission_model');
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

        public function withdrawal_form($requestID=""){
            $data['title'] ='Withdraw Form - GetValue';
            $userID=getUserID($this->userToken);
            $data['update']='';
            $data['request_amount']='';
            if($requestID!=""){
                $requestID=sqlSafe($requestID);
                $data['update']=$requestID;
                $data['w_request']=$this->Commission_model->load_withdrawal_request($requestID);
            }
            // $data['total_balance'] = $this->Commission_model->withdrawal_form($userID);
            $data['total_balance'] = $this->Commission_model->load_transaction_summary($userID);
            $data['userID'] = $userID;
            $this->setView('withdrawal_form', $data);
        }

        public function withdraw_request(){
            $data['title'] ='Withdraw Request - GetValue';
            $userID=getUserID($this->userToken);
            $data['withdraw_request'] = $this->Commission_model->withdraw_request($userID);
            $data['userID'] = $userID;
            $this->setView('withdraw_request', $data);
        }

        public function commissions(){
            $data['title'] ='Commissions - GetValue';
            $userID=getUserID($this->userToken);
            $sort_by="";
            $user_data=$this->input->get();
            if(isset($user_data['sort_by']) && $user_data['sort_by']!=""){
                $sort_by=sqlSafe($user_data['sort_by']);
            }
            $data['summary'] = $this->Commission_model->load_transaction_summary($userID);
            $data['commissions'] = $this->Commission_model->commissions($userID, $sort_by);
            $data['userID'] = $userID;
            $data['sort_by'] = $sort_by;
            $this->setView('commissions', $data);
        }
        
        /*Action*/

        public function save_withdrawal_form(){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('amount', 'request amount', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('amount')!=""){
                    die(form_error('amount'));
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);

                $check=$this->Commission_model->save_withdrawal_form($user_data, $userID);
                echo $check;
            }
        }

        public function update_withdrawal_form(){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('amount', 'request amount', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('amount')!=""){
                    die(form_error('amount'));
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);

                $check=$this->Commission_model->update_withdrawal_form($user_data, $userID);
                echo $check;
            }
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