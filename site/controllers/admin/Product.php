<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Product extends CI_Controller {

    	public function __construct(){
            parent::__construct();
            $this->load->model('admin/Product_model');
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

        public function add_product(){
            $data['update'] = '';
            $data['title'] = 'Add Product - GetValue';
            $this->setView('add_product', $data);
        }

        public function products(){
            $data['title'] = 'Products - GetValue';
            $data['table_rows'] = $this->Product_model->load_products();
            $this->setView('products', $data);
        }

        public function product_details($product){
            $data['title'] = 'Product Details - GetValue';
            $product=sqlSafe($product);
            $data['table_rows'] = $this->Product_model->load_product_details($product);
            $this->setView('product_details', $data);
        }

        public function edit_product($product){
            $data['title'] = 'Edit Product - GetValue';
            if($product!=""){
                $product=sqlSafe($product);
                $data['table_rows'] = $this->Product_model->load_product_details($product);
                $data['title'] = 'Update Product - GetValue';
                $data['update'] = $product;
                $this->setView('add_product', $data);
            }else{
                redirect('admin/products');
            }
        }

        public function edit_product_category($category){
            $data['title'] = 'Edit Product Category - GetValue';
            if($category!=""){
                $category=sqlSafe($category);
                $data['edit_rows'] = $this->Product_model->load_product_category_details($category);
                $data['table_rows'] = $this->Product_model->load_product_categories();
                $data['title'] = 'Update Product/Shop Category - GetValue';
                $data['update'] = $category;
                $this->setView('product_categories', $data);
            }else{
                redirect('admin/product_categories');
            }
        }

        public function product_categories(){
            $data['title'] = 'Product Categories - GetValue';
            $data['update'] = '';
            $data['title'] = 'Product Category - GetValue';
            $data['table_rows'] = $this->Product_model->load_product_categories();
            $this->setView('product_categories', $data);
        }

        public function referrals(){
            $data['title'] = 'Referrals - GetValue';
            $data['table_rows'] = $this->Product_model->load_referrals();
            $this->setView('referrals', $data);
        }

        public function delete_referral_url($product){
            $userID=getUserID($this->userToken);
            $product=sqlSafe($product);
            $check = $this->Product_model->delete_referral_url($product, $userID);
            echo $check;
        }

        /*Action starts here*/

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
            $this->form_validation->set_rules('seller', 'seller', 'trim|required');
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
                }elseif(form_error('seller')!=""){
                    die(form_error('seller'));
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);

                if((!isset($user_data['virtual_link']) || $user_data['virtual_link']=="") && (!isset($user_data['media_type']) || $user_data['media_type']=="") && $user_data['product_status']=='live'){
                    die(errorMsg("Media file or virtual product link should not be empty"));
                }

                $check=$this->Product_model->save_product($user_data, $userID);
                echo $check;
            }
        }

        public function update_product($product){
            // echo json_encode($product);
            // exit;
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('name', 'product name', 'trim|required');
            $this->form_validation->set_rules('category', 'product category', 'trim|required');
            $this->form_validation->set_rules('price', 'price', 'trim|required');
            $this->form_validation->set_rules('summary', 'summary', 'trim|required');
            $this->form_validation->set_rules('description', 'description', 'trim|required');
            // $this->form_validation->set_rules('media_type', 'media type', 'trim|required');
            $this->form_validation->set_rules('preview_type', 'preview type', 'trim|required');
            $this->form_validation->set_rules('episode_count', 'episode count', 'trim|required');
            $this->form_validation->set_rules('seller', 'seller', 'trim|required');
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
                }elseif(form_error('seller')!=""){
                    die(form_error('seller'));
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);

                if((!isset($user_data['virtual_link']) || $user_data['virtual_link']=="") && (!isset($user_data['media_type']) || $user_data['media_type']=="") && ((!isset($user_data['holder_file_name0']) || $user_data['holder_file_name0']=="") || (!isset($user_data['holder_file_name1']) || $user_data['holder_file_name1']=="")) && $user_data['product_status']=='live'){
                    die(errorMsg("Media file or virtual product link should not be empty"));
                }
                $product=sqlSafe($product);

                $check=$this->Product_model->edit_product($product, $user_data, $userID);
                echo $check;
            }
        }

        public function save_referral_expire_date(){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('seller', 'seller', 'trim|required');
            $this->form_validation->set_rules('expire_date', 'expire date', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('seller')!=""){
                    die(form_error('seller'));
                }elseif(form_error('expire_date')!=""){
                    die(form_error('expire_date'));
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);

                $check=$this->Product_model->save_referral_expire_date($user_data, $userID);
                echo $check;
            }
        }

        public function save_product_categories(){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('name', 'category name', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('name')!=""){
                    die(form_error('name'));
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);

                $check=$this->Product_model->save_product_categories($user_data, $userID);
                echo $check;
            }
        }

        public function update_product_categories($category){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('name', 'category name', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            if(empty($category)){ die(errorMsg('Select category'));}
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('name')!=""){
                    die(form_error('name'));
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);
                $category=sqlSafe($category);

                $check=$this->Product_model->edit_product_categories($category, $user_data, $userID);
                echo $check;
            }
        }

        public function delete_product($product){
            $userID=getUserID($this->userToken);
            $product=sqlSafe($product);
            $check = $this->Product_model->delete_product($product, $userID);
            echo $check;
        }

        public function delete_product_category($category){
            $userID=getUserID($this->userToken);
            $category=sqlSafe($category);
            $check = $this->Product_model->delete_product_category($category, $userID);
            echo $check;
        }

        public function add_product_to_slideshow($product){
            if($product=="" || !isset($product)){
                die(errorMsg("Select product"));
            }
            $userID=getUserID($this->userToken);
            $product=sqlSafe($product);
            $check=$this->Product_model->add_product_to_slideshow($product, $userID);
            echo $check;
        }

        public function remove_from_slideshow($product){
            if($product=="" || !isset($product)){
                die(errorMsg("Select product"));
            }
            $userID=getUserID($this->userToken);
            $product=sqlSafe($product);
            $check=$this->Product_model->remove_from_slideshow($product, $userID);
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