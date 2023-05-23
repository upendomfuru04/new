<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Home extends CI_Controller {

    	public function __construct(){
            parent::__construct();
            $this->load->model('admin/Admin_model');
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
            if(!file_exists(APPPATH.'views/pages/admin/'.$page.'.php')){
                show_404();
            }

            $data['title'] = 'Admin - GetValue';
            $data['update'] = '';
            $userID=getUserID($this->userToken);
            $data['userID']=$userID;
            $data['totalCustomers']=$this->Admin_model->getTotalCustomers();
            $data['totalPendingPost']=$this->Admin_model->getTotalPendingPost();
            $data['totalPendingCommissions']=$this->Admin_model->getTotalPendingCommissions();
            $data['totalPendingAccounts']=$this->Admin_model->getTotalPendingAccounts();
            $data['totalVendors']=$this->Admin_model->getTotalVendors();
            $data['totalAffiliates']=$this->Admin_model->getTotalAffiliates();
            $data['totalVisits']=$this->Admin_model->getTotalVisits();
            $this->setView($page, $data);
    	}

        public function setView($page='index', $data=""){
            $this->load->view('includes/admin_header.php', $data);
            $this->load->view('pages/admin/'.$page);
            $this->load->view('includes/admin_footer.php');
        }

        public function messages(){
            $data['title'] = 'Messages - GetValue';
            $userID=getUserID($this->userToken);
            $data['messages'] = $this->Admin_model->load_messages();
            $this->setView('messages', $data);
        }

        public function subscribers(){
            $data['title'] = 'Subscribers - GetValue';
            $data['subscribers'] = $this->Admin_model->subscribers();
            $this->setView('subscribers', $data);
        }

        public function optimize_images(){
            $data['title'] = 'Optimize Images - GetValue';
            $this->Admin_model->optimize_images();
        }

        public function generate_seller_profile(){
            $data['title'] = 'Optimize Images - GetValue';
            $this->Admin_model->generate_seller_profile();
        }

        public function media_center(){
            $data['title'] = 'Media Center - GetValue';
            $userID=getUserID($this->userToken);
            $data['table_rows'] = $this->Admin_model->media_center($userID);
            $this->setView('media_center', $data);
        }

        public function admin_profile(){
            $userID=getUserID($this->userToken);
            $data['title'] = 'My Profile - GetValue';
            $data['user_info'] = $this->Admin_model->user_info($userID);
            $this->setView('admin_profile', $data);
        }

        public function password_reset(){
            $userID=getUserID($this->userToken);
            $data['title'] = 'Password Reset - GetValue';
            $this->setView('password_reset', $data);
        }

        public function new_message(){
            $data['update'] = '';
            $data['title'] ='New Help Post - GetValue';
            $userID=getUserID($this->userToken);
            $data['userID'] =$userID;
            $this->setView('new_message', $data);
        }

        public function message_details($msg){
            $data['title'] = 'Message Details - GetValue';
            $userID=getUserID($this->userToken);
            $msg=sqlSafe($msg);
            $data['msg'] = $msg;
            $data['userID']=$userID;
            $data['receivers'] = $this->Admin_model->load_message_receivers($msg);
            $data['table_rows'] = $this->Admin_model->load_message_detail($msg, $userID);
            $this->setView('message_details', $data);
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

                $check=$this->Admin_model->save_msg_reply($user_data, $userID);
                echo $check;
            }
        }

        /*Check & Load*/

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

                $check=$this->Admin_model->save_new_message($user_data, $userID);
                echo $check;
            }
        }

        public function update_admin_profile(){
            $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
            $this->form_validation->set_rules('surname', 'Surname', 'trim|required');
            $this->form_validation->set_rules('gender', 'Gender', 'trim|required');
            $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            $data = array();
            $userID=getUserID($this->userToken);
            $data1['title'] = 'My Profile - GetvalueInc';

            if ($this->form_validation->run() === FALSE){
                $data1['user_info'] = $this->Admin_model->user_info($userID);
                $this->setView('admin_profile', $data1);
            }else{
                $user_data = $this->input->post();
                $avatar="";
                if(isset($user_data['avatar'])){ $avatar=$user_data['avatar'];}
                $data = array(
                        'user_id' => $userID,
                        'first_name' => $user_data['first_name'],
                        'surname' => $user_data['surname'],
                        'gender' => $user_data['gender'],
                        'phone' => $user_data['phone'],
                        'email' => $user_data['email'],
                        'avatar' => $avatar
                    );  

                $check=$this->Admin_model->update_admin_profile($data);
                if($check=='Success'){
                    $this->session->set_flashdata('feedback', successMsg('You have successfully updated your account.'));
                    $data1['user_info'] = $this->Admin_model->user_info($userID);
                    $this->setView('admin_profile', $data1);
                }else{
                    $this->session->set_flashdata('feedback', errorMsg('Failed '.$check));
                    $data1['user_info'] = $this->Admin_model->user_info($userID);
                    $this->setView('admin_profile', $data1);
                }
            }
        }

        /*Actions*/

        public function delete_msg($msg){
            $userID=getUserID($this->userToken);
            $msg=sqlSafe($msg);
            $check = $this->Admin_model->delete_msg($msg, $userID);
            echo $check;
        }

        public function delete_subscriber($subscriber){
            $userID=getUserID($this->userToken);
            $subscriber=sqlSafe($subscriber);
            $check = $this->Admin_model->delete_subscriber($subscriber, $userID);
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
