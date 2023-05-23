<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Report extends CI_Controller {

    	public function __construct(){
            parent::__construct();
            $this->load->model('admin/Report_model');
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

        public function visits(){
            $data['title'] = "Visitors Report - GetValue";
            $data['visits'] = $this->Report_model->load_visits();
            $this->setView('visits', $data);
        }

        public function reports(){
            $data['title'] = "Quick Report - GetValue";
            /*Accounts & Visits*/
            $data['visits'] = $this->Report_model->visits();
            $data['customers'] = $this->Report_model->customers();
            $data['vendors'] = $this->Report_model->vendors();
            $data['affiliates'] = $this->Report_model->affiliates();
            $data['totalAffiliates'] = $this->Report_model->getTotalAffiliates();
            $data['insiders'] = $this->Report_model->insiders();
            $data['outsiders'] = $this->Report_model->outsiders();
            $data['contributors'] = $this->Report_model->contributors();
            /*Products & Blog*/
            $data['products'] = $this->Report_model->products();
            $data['ebooks'] = $this->Report_model->ebooks();
            $data['audiobooks'] = $this->Report_model->audiobooks();
            $data['trainings_programms'] = $this->Report_model->trainings_programms();
            $data['blog_posts'] = $this->Report_model->blog_posts();
            /*Commission & Withdrawal*/
            $data['commissions'] = $this->Report_model->commissions();
            $data['paid_commissions'] = $this->Report_model->paid_commissions();
            $data['unpaid_commissions'] = $this->Report_model->unpaid_commissions();
            $data['pending_commissions'] = $this->Report_model->pending_commissions();
            $data['refundend_commissions'] = $this->Report_model->refundend_commissions();
            $data['cancelled_commissions'] = $this->Report_model->cancelled_commissions();
            /*Commission & Withdrawal*/
            $data['requests'] = $this->Report_model->requests();
            $data['paid_requests'] = $this->Report_model->paid_requests();
            $data['pending_requests'] = $this->Report_model->pending_requests();
            $data['rejected_requests'] = $this->Report_model->rejected_requests();
            /*Sales*/
            $data['orders'] = $this->Report_model->orders();
            $data['pending_orders'] = $this->Report_model->pending_orders();
            $data['complete_orders'] = $this->Report_model->complete_orders();
            $this->setView('reports', $data);
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