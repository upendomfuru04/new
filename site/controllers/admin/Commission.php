<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Commission extends CI_Controller {

    	public function __construct(){
            parent::__construct();
            $this->load->model('admin/Commission_model');
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

        public function withdraw_request(){
            $data['title'] = 'Withdraw Request - GetValue';
            $userID=getUserID($this->userToken);
            $data['withdraw_request'] = $this->Commission_model->withdraw_request($userID);
            $data['userID'] = $userID;
            $this->setView('withdraw_request', $data);
        }

        public function withdraw_reject(){
            $data['title'] = 'Withdraw Reject - GetValue';
            $userID=getUserID($this->userToken);
            $data['withdraw_reject'] = $this->Commission_model->withdraw_reject($userID);
            $data['userID'] = $userID;
            $this->setView('withdraw_reject', $data);
        }

        public function withdrawal_payouts(){
            $data['title'] = 'Withdraw Payouts - GetValue';
            $userID=getUserID($this->userToken);
            $data['withdraw_payouts'] = $this->Commission_model->withdrawal_payouts($userID);
            $data['userID'] = $userID;
            $this->setView('withdrawal_payouts', $data);
        }

        public function commissions(){
            $data['title'] = 'Commissions - GetValue';
            $userID=getUserID($this->userToken);
            $sort_by="";
            $user_data=$this->input->get();
            if(isset($user_data['sort_by']) && $user_data['sort_by']!=""){
                $sort_by=sqlSafe($user_data['sort_by']);
            }
            // $data['balances'] = $this->Commission_model->load_seller_transaction_balance();
            $data['commissions'] = $this->Commission_model->commissions($userID, $sort_by);
            $data['userID'] = $userID;
            $data['sort_by'] = $sort_by;
            $this->setView('commissions', $data);
        }

        public function commission_rates(){
            $data['title'] = 'Commission Rates - GetValue';
            $data['table_rows'] = $this->Commission_model->commission_rates();
            $data['update'] = "";
            $this->setView('commission_rates', $data);
        }

        public function transaction_balance(){
            $data['title'] = 'Transaction Balance - GetValue';
            $data['balances'] = $this->Commission_model->load_seller_transaction_balance();
            $this->setView('transaction_balance', $data);
        }

        public function edit_commission_rates($info){
            $data['title'] = 'Edit Commission Rates - GetValue';
            if($info!=""){
                $info=sqlSafe($info);
                $data['edit_rows'] = $this->Commission_model->load_commission_rate($info);
                $data['update'] = $info;
                $data['table_rows'] = $this->Commission_model->commission_rates();
                $this->setView('commission_rates', $data);
            }else{
                redirect('admin/commission_rates');
            }
        }

        public function commission_rates_vendor(){
            $data['title'] = 'Vendor Commission Rates - GetValue';
            $data['table_rows'] = $this->Commission_model->commission_rates_vendor();
            $data['update'] = "";
            $this->setView('commission_rates_vendor', $data);
        }

        public function edit_vendor_commission_rates($info){
            $data['title'] = 'Edit Vendor Commission Rates - GetValue';
            if($info!=""){
                $info=sqlSafe($info);
                $data['edit_rows'] = $this->Commission_model->load_commission_rate_vendor($info);
                $data['update'] = $info;
                $data['table_rows'] = $this->Commission_model->commission_rates_vendor();
                $this->setView('commission_rates_vendor', $data);
            }else{
                redirect('admin/commission_rates_vendor');
            }
        }

        /*Action*/

        public function save_commission_rate(){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('type', 'rate type', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('type')!=""){
                    die(form_error('type'));
                }elseif(form_error('amount_fixed')!="" && form_error('amount_percent')!=""){
                    die(form_error('amount_fixed'));
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);
                if(($user_data['amount_fixed']=="" || $user_data['amount_fixed']=="0") && ($user_data['amount_percent']=="" || $user_data['amount_percent']=="0")){
                    die(errorMsg("Amount should not be empty."));
                }
                $check=$this->Commission_model->save_commission_rate($user_data, $userID);
                echo $check;
            }
        }

        public function save_vendor_commission_rate(){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('type', 'rate type', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('type')!=""){
                    die(form_error('type'));
                }elseif(form_error('amount_fixed')!="" && form_error('amount_percent')!=""){
                    die(form_error('amount_fixed'));
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);
                if(($user_data['amount_fixed']=="" || $user_data['amount_fixed']=="0") && ($user_data['amount_percent']=="" || $user_data['amount_percent']=="0")){
                    die(errorMsg("Amount should not be empty."));
                }
                $check=$this->Commission_model->save_vendor_commission_rate($user_data, $userID);
                echo $check;
            }
        }

        public function update_commission_rate($info){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('type', 'rate type', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            if(empty($info)){ die(errorMsg('Select row'));}
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('type')!=""){
                    die(form_error('type'));
                }
            }else{
                $user_data = $this->input->post();
                if(($user_data['amount_fixed']=="" || $user_data['amount_fixed']=="0") && ($user_data['amount_percent']=="" || $user_data['amount_percent']=="0")){
                    die(errorMsg("Amount should not be empty."));
                }
                $info=sqlSafe($info);
                $check=$this->Commission_model->update_commission_rate($info, $user_data);
                echo $check;
            }
        }

        public function update_vendor_commission_rate($info){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('type', 'rate type', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            if(empty($info)){ die(errorMsg('Select row'));}
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('type')!=""){
                    die(form_error('type'));
                }
            }else{
                $user_data = $this->input->post();
                if(($user_data['amount_fixed']=="" || $user_data['amount_fixed']=="0") && ($user_data['amount_percent']=="" || $user_data['amount_percent']=="0")){
                    die(errorMsg("Amount should not be empty."));
                }
                $info=sqlSafe($info);
                $check=$this->Commission_model->update_vendor_commission_rate($info, $user_data);
                echo $check;
            }
        }

        public function save_withdrawal_request(){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('status', 'status', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('status')!=""){
                    die(form_error('status'));
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);
                if($user_data['status']=='2' && empty($user_data['reason'])){
                    die(errMsg().'Rejection reason should not be empty</div>');
                }

                $check=$this->Commission_model->save_withdrawal_request($user_data, $userID);
                echo $check;
            }
        }

        public function delete_commission_rate($info){
            $info=sqlSafe($info);
            $check = $this->Commission_model->delete_commission_rate($info);
            echo $check;
        }

        public function activate_commission_rate($info){
            $info=sqlSafe($info);
            $check = $this->Commission_model->activate_commission_rate($info);
            echo $check;
        }

        public function diactivate_commission_rate($info){
            $info=sqlSafe($info);
            $check = $this->Commission_model->diactivate_commission_rate($info);
            echo $check;
        }

        public function delete_vendor_commission_rate($info){
            $info=sqlSafe($info);
            $check = $this->Commission_model->delete_vendor_commission_rate($info);
            echo $check;
        }

        public function activate_vendor_commission_rate($info){
            $info=sqlSafe($info);
            $check = $this->Commission_model->activate_vendor_commission_rate($info);
            echo $check;
        }

        public function diactivate_vendor_commission_rate($info){
            $info=sqlSafe($info);
            $check = $this->Commission_model->diactivate_vendor_commission_rate($info);
            echo $check;
        }

        public function pay_seller_commission($info){
            $userID=getUserID($this->userToken);
            $info=sqlSafe($info);
            $check = $this->Commission_model->pay_seller_commission($info, $userID);
            echo $check;
        }

        public function check_new_withdraw_request(){
            $result = $this->Commission_model->check_new_withdraw_request();
            echo $result;
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