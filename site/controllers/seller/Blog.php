<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Blog extends CI_Controller {

    	public function __construct(){
            parent::__construct();
            $this->load->model('seller/Blog_model');
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

        public function new_post(){
            $data['update'] = '';
            $data['title'] ='New Post - GetValue';
            $userID=getUserID($this->userToken);
            $data['userID'] =$userID;
            $this->setView('new_post', $data);
        }

        public function new_self_help(){
            $data['update'] = '';
            $data['title'] ='New Help Post - GetValue';
            $userID=getUserID($this->userToken);
            $data['userID'] =$userID;
            $data['categories'] = $this->Blog_model->load_self_help_categories();
            $data['subcategories'] = $this->Blog_model->load_self_help_sub_categories();
            $this->setView('new_self_help', $data);
        }

        public function my_posts(){
            $data['title'] ='My Posts - GetValue';
            $userID=getUserID($this->userToken);
            $data['table_rows'] = $this->Blog_model->my_posts($userID);
            $this->setView('my_posts', $data);
        }

        public function self_help(){
            $data['title'] ='Self Help - GetValue';
            $userID=getUserID($this->userToken);
            $data['table_rows'] = $this->Blog_model->self_help();
            $this->setView('self_help', $data);
        }

        public function self_help_sub_category(){
            $data['title'] ='Self Help Sub - GetValue';
            $userID=getUserID($this->userToken);
            $data['categories'] = $this->Blog_model->load_self_help_categories();
            $data['subcategories'] = $this->Blog_model->load_self_help_sub_categories();
            $data['update'] = "";
            $this->setView('self_help_sub_category', $data);
        }

        public function edit_self_help_sub_category($sub){
            $data['title'] ='Edit Self Help Sub - GetValue';
            $userID=getUserID($this->userToken);
            $data['categories'] = $this->Blog_model->load_self_help_categories();
            $data['subcategories'] = $this->Blog_model->load_self_help_sub_categories();
            if($sub!=""){
                $data['edit_rows'] = $this->Blog_model->load_self_help_sub_category_details($sub);
            }
            $data['update'] = $sub;
            $this->setView('self_help_sub_category', $data);
        }

        public function posts(){
            $data['title'] ='Posts - GetValue';
            $userID=getUserID($this->userToken);
            $data['table_rows'] = $this->Blog_model->load_all_posts();
            $this->setView('posts', $data);
        }

        public function pending_posts(){
            $data['title'] ='Pending Posts - GetValue';
            $userID=getUserID($this->userToken);
            $data['table_rows'] = $this->Blog_model->pending_posts();
            $this->setView('pending_posts', $data);
        }

        public function post_details($product){
            $data['title'] ='Post Details - GetValue';
            $userID=getUserID($this->userToken);
            $account_type=sqlSafe($this->session->userdata('account_type'));
            $product=sqlSafe($product);
            $data['table_rows'] = $this->Blog_model->load_post_detail($product, $userID, $account_type);
            $this->setView('post_details', $data);
        }

        public function edit_post($post){
            if($post!=""){
                $userID=getUserID($this->userToken);
                $post=sqlSafe($post);
                $data['userID'] = $userID;
                $data['table_rows'] = $this->Blog_model->load_post_details($post, $userID);
                $data['title'] = 'Update Post - GetValue';
                $data['update'] = $post;
                $this->setView('new_post', $data);
            }else{
                redirect('seller/my_posts');
            }
        }

        public function edit_blog_post($post){
            if($post!=""){
                $userID=getUserID($this->userToken);
                $account_type=sqlSafe($this->session->userdata('account_type'));
                if($account_type!='insider'){
                    redirect('seller/posts');
                }
                $post=sqlSafe($post);
                $data['userID'] = $userID;
                $data['table_rows'] = $this->Blog_model->load_blog_post_details($post);
                $data['title'] = 'Update Blog Post - GetValue';
                $data['update'] = $post;
                $this->setView('edit_blog_post', $data);
            }else{
                redirect('seller/posts');
            }
        }

        public function edit_self_help($post){
            if($post!=""){
                $userID=getUserID($this->userToken);
                $post=sqlSafe($post);
                $data['userID'] = $userID;
                $data['categories'] = $this->Blog_model->load_self_help_categories();
                $data['subcategories'] = $this->Blog_model->load_self_help_sub_categories();
                $data['table_rows'] = $this->Blog_model->load_self_help_details($post, $userID);
                $data['title'] = 'Update Self Help - GetValue';
                $data['update'] = $post;
                $this->setView('new_self_help', $data);
            }else{
                redirect('seller/self_help');
            }
        }

        public function edit_product($product){
            if($product!=""){
                $userID=getUserID($this->userToken);
                $account_type=sqlSafe($this->session->userdata('account_type'));
                $product=sqlSafe($product);
                $data['table_rows'] = $this->Blog_model->load_product_details($product, $userID, $account_type);
                $data['title'] = 'Update Product - GetValue';
                $data['update'] = $product;
                $this->setView('add_product', $data);
            }else{
                redirect('seller/products');
            }
        }
        
        /*Action*/

        public function save_self_help(){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('title', 'help title', 'trim|required');
            $this->form_validation->set_rules('content', 'content', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('title')!=""){
                    die(form_error('title'));
                }elseif(form_error('content')!=""){
                    die(form_error('content'));
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);

                $check=$this->Blog_model->save_self_help($user_data, $userID);
                echo $check;
            }
        }

        public function update_self_help($post){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('title', 'help title', 'trim|required');
            $this->form_validation->set_rules('content', 'content', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('title')!=""){
                    die(form_error('title'));
                }elseif(form_error('content')!=""){
                    die(form_error('content'));
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);
                $post=sqlSafe($post);

                $check=$this->Blog_model->edit_self_help($post, $user_data);
                echo $check;
            }
        }

        public function save_self_help_sub_categories(){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('category', 'help category', 'trim|required');
            $this->form_validation->set_rules('name', 'name', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('category')!=""){
                    die(form_error('category'));
                }elseif(form_error('name')!=""){
                    die(form_error('name'));
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);

                $check=$this->Blog_model->save_self_help_sub_categories($user_data, $userID);
                echo $check;
            }
        }

        public function update_self_help_sub_categories($post){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('category', 'help category', 'trim|required');
            $this->form_validation->set_rules('name', 'name', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('category')!=""){
                    die(form_error('category'));
                }elseif(form_error('name')!=""){
                    die(form_error('name'));
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);
                $post=sqlSafe($post);

                $check=$this->Blog_model->edit_self_help_sub_categories($post, $user_data, $userID);
                echo $check;
            }
        }

        public function save_post(){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('category', 'blog category', 'trim|required');
            $this->form_validation->set_rules('title', 'blog title', 'trim|required');
            $this->form_validation->set_rules('summary', 'summary', 'trim|required');
            $this->form_validation->set_rules('content', 'content', 'trim|required');
            $this->form_validation->set_rules('seller_type', 'account type', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('category')!=""){
                    die(form_error('category'));
                }elseif(form_error('title')!=""){
                    die(form_error('title'));
                }elseif(form_error('summary')!=""){
                    die(form_error('summary'));
                }elseif(form_error('content')!=""){
                    die(form_error('content'));
                }elseif(form_error('seller_type')!=""){
                    die(form_error('seller_type'));
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);

                $check=$this->Blog_model->save_post($user_data, $userID);
                echo $check;
            }
        }

        public function update_post($post){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('category', 'blog category', 'trim|required');
            $this->form_validation->set_rules('title', 'blog title', 'trim|required');
            $this->form_validation->set_rules('summary', 'summary', 'trim|required');
            $this->form_validation->set_rules('content', 'content', 'trim|required');
            $this->form_validation->set_rules('seller_type', 'account type', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            if(empty($post)){ die(errorMsg('Select Post'));}
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('category')!=""){
                    die(form_error('category'));
                }elseif(form_error('title')!=""){
                    die(form_error('title'));
                }elseif(form_error('summary')!=""){
                    die(form_error('summary'));
                }elseif(form_error('content')!=""){
                    die(form_error('content'));
                }elseif(form_error('seller_type')!=""){
                    die(form_error('seller_type'));
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);
                $post=sqlSafe($post);

                $check=$this->Blog_model->edit_post($post, $user_data, $userID);
                echo $check;
            }
        }

        public function update_blog_post($post){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('category', 'blog category', 'trim|required');
            $this->form_validation->set_rules('title', 'blog title', 'trim|required');
            $this->form_validation->set_rules('content', 'content', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            if(empty($post)){ die(errorMsg('Select Post'));}
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('category')!=""){
                    die(form_error('category'));
                }elseif(form_error('title')!=""){
                    die(form_error('title'));
                }elseif(form_error('content')!=""){
                    die(form_error('content'));
                }
            }else{
                $user_data = $this->input->post();
                // $userID=getUserID($this->userToken);
                $post=sqlSafe($post);

                $check=$this->Blog_model->update_blog_post($post, $user_data);
                echo $check;
            }
        }

        public function delete_post($post){
            $userID=getUserID($this->userToken);
            $post=sqlSafe($post);
            $check = $this->Blog_model->delete_post($post, $userID);
            echo $check;
        }

        public function delete_self_help($post){
            $userID=getUserID($this->userToken);
            $post=sqlSafe($post);
            $check = $this->Blog_model->delete_self_help($post, $userID);
            echo $check;
        }

        public function delete_self_help_sub_category($post){
            $userID=getUserID($this->userToken);
            $post=sqlSafe($post);
            $check = $this->Blog_model->delete_self_help_sub_category($post, $userID);
            echo $check;
        }

        public function approve_post($post){
            $userID=getUserID($this->userToken);
            $post=sqlSafe($post);
            $check = $this->Blog_model->approve_post($post, $userID);
            echo $check;
        }

        public function load_sub_categories($category){
            $category=sqlSafe($category);
            $check = $this->Blog_model->load_sub_categories($category);
            echo $check;
        }

        public function deny_post($post){
            $userID=getUserID($this->userToken);
            $post=sqlSafe($post);
            $user_data=$this->input->get();
            $reason=$user_data['reason'];
            if(!isset($reason) || $reason==""){
                die(errorMsg("You must provide the deny reason"));
            }
            $check = $this->Blog_model->deny_post($post, $reason, $userID);
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