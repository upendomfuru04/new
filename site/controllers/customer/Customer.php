<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

	public function __construct(){
        parent::__construct();
        $this->is_logged_in();
        $this->isRightUser();
        $this->load->model('customer/Customer_model');
        $this->load->model('home/Homeorder_model');
        $this->load->model('customer/Corder_model');
        $this->userToken=$this->session->userdata('getvalue_user_idetification');
        if(getUserID($this->userToken)==""){
            unset($_SESSION['getvalue_user_idetification']);
            $this->session->sess_destroy();
            redirect(base_url().'login');
        }
    }

	public function index(){
		/*if(!file_exists(APPPATH.'views/pages/customer/'.$page.'.php')){
            show_404();
        }*/
        // $data['title'] = ucfirst($page);
        $userID=getUserID($this->userToken);
        $data['userID']=$userID;
        $data['totalCustomerPendingOrders'] = $this->Corder_model->getCustomerPendingOrders($userID);
        $data['totalCustomerCompleteOrders'] = $this->Corder_model->getCustomerCompleteOrders($userID);
        $data['totalPurchasedProducts'] = $this->Corder_model->getPurchasedProducts($userID);
        $data['totalCustomerProcessRefunds'] = $this->Corder_model->getCustomerProcessRefunds($userID);
        $data['totalCustomerPendingRefunds'] = $this->Corder_model->getCustomerPendingRefunds($userID);
        $this->setView('index', $data);
	}

    public function setView($page='index', $data=""){
        $this->load->view('includes/customer_header.php');
        $this->load->view('pages/customer/'.$page, $data);
        $this->load->view('includes/customer_footer.php');
    }

    public function checkout(){
        $this->load->model('Users_model');
        $userID=getUserID($this->userToken);
        $account_type=$this->session->userdata('account_type');
        $data['userID']=$userID;
        $data['order_info'] = $this->Corder_model->load_order_info($userID);
        $data['user_info'] = $this->Users_model->user_info($userID, $account_type);
        $this->setView('checkout', $data);
    }

    public function checkout1(){
        $this->load->model('Users_model');
        $userID=getUserID($this->userToken);
        $account_type=$this->session->userdata('account_type');
        $data['userID']=$userID;
        $data['order_info'] = $this->Corder_model->load_order_info($userID);
        $data['user_info'] = $this->Users_model->user_info($userID, $account_type);
        $this->setView('checkout1', $data);
    }

    public function load_order_info(){
        $this->load->model('Users_model');
        $userID=getUserID($this->userToken);
        $info = $this->Corder_model->load_order_info($userID);
        echo $info['total_amount'];
    }

    public function complete_payment(){
        $this->load->model('Users_model');
        $userID=getUserID($this->userToken);
        $data['userID']=$userID;
        $data['order_info'] = $this->Corder_model->load_order_info($userID);
        $this->setView('complete_payment', $data);
    }

    // public function payment_success($orderID=""){
    //     $payment="";
    //     $userID=getUserID($this->userToken);
    //     if($orderID!=""){
    //         $user_data = $this->input->get();
    //         // $data['userID']=$userID;
    //         $data['orderID']=$_GET['orderID'];
    //         // $trans_token=$user_data['TransactionToken'];
    //         // $data['payment'] = $this->Customer_model->confirm_order_payment($userID, $orderID, $trans_token);
    //         $this->load->model('Home_model');
    //         $payment = $this->Home_model->check_order_status($orderID, $userID);
    //     }else{
    //         $payment="Empty order ID";
    //     }
    //     $data['payment'] = $payment;
    //     $data['password_set'] = $this->Customer_model->password_set($userID);
    //     $this->setView('payment_success', $data);
    // }
    
    public function payment_success(){
        
        $orderID= $_GET['orderID'];
        
        $payment="";
        $userID=getUserID($this->userToken);
        
        if($orderID){
            
            $data['orderID']= $orderID;

            $payment = $this->Homeorder_model->check_order_status($orderID, $userID);
            
        }else{
            $payment="Empty order ID";
        }
        $data['payment'] = $payment;
        $data['password_set'] = $this->Customer_model->password_set($userID);
        $this->setView('payment_success', $data);
    }

    public function cart(){
        $userID=getUserID($this->userToken);
        $data['cart'] = $this->Corder_model->load_cart_items($userID);
        $this->setView('cart', $data);
    }

    public function messages(){
        $userID=getUserID($this->userToken);
        $data['messages'] = $this->Customer_model->load_messages($userID);
        $this->setView('messages', $data);
    }

    public function message_details($msg){
        $userID=getUserID($this->userToken);
        $data['userID']=$userID;
        $data['table_rows'] = $this->Customer_model->load_message_detail($msg, $userID);
        $this->setView('message_details', $data);
    }

    public function add_cart_quantity($cartID){
        if($cartID!=""){
            $userID=getUserID($this->userToken);
            $check = $this->Customer_model->add_cart_quantity($userID, $cartID);
            echo $check;
        }
    }

    public function verify_coupon($coupon=""){
        if(!empty($coupon) && $coupon!=""){
            $coupon=sqlSafe($coupon);
            $userID=getUserID($this->userToken);
            $check = $this->Customer_model->verify_coupon($coupon);
            echo $check;
        }else{
            echo 'No coupon provided';
        }
    }

    public function remove_cart_quantity($cartID){
        if($cartID!=""){
            $userID=getUserID($this->userToken);
            $check = $this->Customer_model->remove_cart_quantity($userID, $cartID);
            echo $check;
        }
    }

    public function remove_refund_request($request){
        if($request!=""){
            $userID=getUserID($this->userToken);
            $check = $this->Customer_model->remove_refund_request($userID, $request);
            echo $check;
        }
    }

    public function save_request_refund(){
        $this->form_validation->set_error_delimiters(errMsg(), '</div>');
        $this->form_validation->set_rules('orderID', 'orderID', 'trim|required');
        $this->form_validation->set_rules('description', 'description', 'trim|required');
        $this->form_validation->set_rules('amount_paid', 'amount', 'trim|required');
        $this->form_validation->set_message('required', 'The %s should not be empty.');
        $data = array();

        if ($this->form_validation->run() === FALSE){
            if(form_error('orderID')!=""){
                die(form_error('orderID'));
            }elseif(form_error('description')!=""){
                die(form_error('description'));
            }elseif(form_error('amount_paid')!=""){
                die(form_error('amount_paid'));
            }
        }else{
            $user_data = $this->input->post();
            $userID=getUserID($this->userToken);

            $check=$this->Customer_model->save_request_refund($user_data, $userID);
            echo $check;
        }
    }

    public function save_order(){
        $this->form_validation->set_error_delimiters(errMsg(), '</div>');
        $this->form_validation->set_rules('orderID', 'orderID', 'trim|required');
        $this->form_validation->set_rules('first_name', 'first name', 'trim|required');
        $this->form_validation->set_rules('surname', 'surname', 'trim|required');
        $this->form_validation->set_rules('email', 'email', 'trim|required');
        $this->form_validation->set_rules('phone', 'phone', 'trim|required');
        $this->form_validation->set_rules('address', 'address', 'trim|required');
        $this->form_validation->set_rules('country', 'country', 'trim|required');
        $this->form_validation->set_rules('city', 'city', 'trim|required');
        $this->form_validation->set_message('required', 'The %s should not be empty.');
        $data = array();

        if ($this->form_validation->run() === FALSE){
            if(form_error('orderID')!=""){
                die(form_error('orderID'));
            }elseif(form_error('first_name')!=""){
                die(form_error('first_name'));
            }elseif(form_error('surname')!=""){
                die(form_error('surname'));
            }elseif(form_error('phone')!=""){
                die(form_error('phone'));
            }elseif(form_error('address')!=""){
                die(form_error('address'));
            }elseif(form_error('email')!=""){
                die(form_error('email'));
            }elseif(form_error('country')!=""){
                die(form_error('country'));
            }elseif(form_error('city')!=""){
                die(form_error('city'));
            }
        }else{
            $user_data = $this->input->post();
            $userID=getUserID($this->userToken);

            $check=$this->Corder_model->save_order($user_data, $userID);
            echo $check;
        }
    }

    public function short_save_order($coupon=""){
        $user_data=array();
        $this->load->model('Users_model');
        $userID=getUserID($this->userToken);
        $account_type=$this->session->userdata('account_type');
        $order_info = $this->Customer_model->load_order_info($userID);
        $user_data = $this->Users_model->user_info($userID, $account_type);
        $user_data['orderID']=$order_info['orderID'];
        $user_data['total_amount']=$order_info['total_amount'];
        $user_data['coupon']=$coupon;

        $check=$this->Customer_model->short_save_order($user_data, $userID);
        echo $check;
    }

    public function save_new_message(){
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

            $check=$this->Customer_model->save_new_message($user_data, $userID);
            echo $check;
        }
    }

    public function orders(){
        $userID=getUserID($this->userToken);
        $data['userID'] = $userID;
        $data['orders'] = $this->Corder_model->load_customer_orders($userID);
        $this->setView('orders', $data);
    }

    public function refunds(){
        $userID=getUserID($this->userToken);
        $data['refunds'] = $this->Customer_model->load_refunds($userID);
        $this->setView('refunds', $data);
    }

    public function order_items($orderID){
        $userID=getUserID($this->userToken);
        $data['orderID'] = $this->Corder_model->load_single_order_info($userID, $orderID);
        $data['items'] = $this->Corder_model->load_order_items($userID, $orderID);
        $this->setView('order_items', $data);
    }

    public function my_products(){
        $userID=getUserID($this->userToken);
        $data['items'] = $this->Customer_model->load_my_items($userID);
        $this->setView('my_products', $data);
    }

    public function download(){
        $userID=getUserID($this->userToken);
        $user_data=$this->input->get();
        $product=$user_data['prd'];
        $data['media'] = $this->Customer_model->download_product($userID, $product);
        $this->setView('download', $data);
    }

    public function sb_download(){
        $userID=getUserID($this->userToken);
        $user_data=$this->input->get();
        $product=$user_data['prd'];
        $data['media'] = $this->Customer_model->download_product($userID, $product);
        $this->setView('sb_download', $data);
    }

    public function request_refund($orderID){
        $userID=getUserID($this->userToken);
        $product="";
        $user_data=$this->input->get();
        if(isset($user_data['product']) && $user_data['product']!=""){
            $product=sqlSafe($user_data['product']);
        }
        $data['orderID'] = $orderID;
        $data['product'] = $product;
        // $data['items'] = $this->Customer_model->load_order_items($userID, $orderID);
        $this->setView('request_refund', $data);
    }

    public function isRightUser(){
        if($this->session->userdata('account_type')!='customer'){
            redirect(base_url(''));
        }
    }
    
    private function is_logged_in(){
        $is_logged_in = $this->session->userdata('getvalue_user_idetification');
        if (!isset($is_logged_in) || $is_logged_in == "") {
            redirect(base_url('login'));
        }
    }
}
