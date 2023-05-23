<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Blog extends CI_Controller {

    	public function __construct(){
            parent::__construct();
            $this->load->model('admin/Blog_model');
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

        public function edit_self_help_category($category){
            if($category!=""){
                $category=sqlSafe($category);
                $data['edit_rows'] = $this->Blog_model->load_self_help_category_details($category);
                $data['table_rows'] = $this->Blog_model->load_self_help_categories();
                $data['title'] = 'Update Self Help Category - GetValue';
                $data['update'] = $category;
                $this->setView('self_help_category', $data);
            }else{
                redirect('admin/self_help_category');
            }
        }

        public function edit_self_help_sub_category($category){
            if($category!=""){
                $category=sqlSafe($category);
                $data['edit_rows'] = $this->Blog_model->load_self_help_sub_category_details($category);
                $data['table_rows'] = $this->Blog_model->load_self_help_sub_categories();
                $data['category'] = $this->Blog_model->load_self_help_categories();
                $data['title'] = 'Update Self Help Sub Category - GetValue';
                $data['update'] = $category;
                $this->setView('self_help_sub_category', $data);
            }else{
                redirect('admin/self_help_sub_category');
            }
        }

        public function self_help_category(){
            $data['update'] = '';
            $data['title'] = 'Self Help Category - GetValue';
            $data['table_rows'] = $this->Blog_model->load_self_help_categories();
            $this->setView('self_help_category', $data);
        }

        public function self_help_sub_category(){
            $data['update'] = '';
            $data['title'] = 'Self Help Sub Category - GetValue';
            $data['category'] = $this->Blog_model->load_self_help_categories();
            $data['table_rows'] = $this->Blog_model->load_self_help_sub_categories();
            $this->setView('self_help_sub_category', $data);
        }

        public function edit_blog_category($category){
            if($category!=""){
                $category=sqlSafe($category);
                $data['edit_rows'] = $this->Blog_model->load_blog_category_details($category);
                $data['table_rows'] = $this->Blog_model->load_blog_categories();
                $data['title'] = 'Update Blog Category - GetValue';
                $data['update'] = $category;
                $this->setView('blog_categories', $data);
            }else{
                redirect('admin/blog_categories');
            }
        }

        public function blog_categories(){
            $data['update'] = '';
            $data['title'] = 'Blog Category - GetValue';
            $data['table_rows'] = $this->Blog_model->load_blog_categories();
            $this->setView('blog_categories', $data);
        }

        public function tv_post(){
            $data['title'] = 'TV Post - GetValue';
            $userID=getUserID($this->userToken);
            $data['table_rows'] = $this->Blog_model->load_tv_posts();
            $data['userID'] = $userID;
            $data['update'] = "";
            $this->setView('tv_post', $data);
        }

        public function edit_tv_post($info){
            $data['title'] = 'Edit TV Post - GetValue';
            if($info!=""){
                $userID=getUserID($this->userToken);
                $info=sqlSafe($info);
                $data['edit_rows'] = $this->Blog_model->load_tv_post_details($info);
                $data['update'] = $info;
                $data['userID'] = $userID;
                $data['table_rows'] = $this->Blog_model->load_tv_posts();
                $this->setView('tv_post', $data);
            }else{
                redirect('admin/tv_post');
            }
        }

        public function new_self_help(){
            $data['update'] = '';
            $data['title'] ='New Help Post - GetValue';
            $userID=getUserID($this->userToken);
            $data['userID'] =$userID;
            $data['categories'] = $this->Blog_model->load_self_help_categories();
            $this->setView('new_self_help', $data);
        }

        public function load_sub_categories($category){
            $cat=sqlSafe($category);
            $subs = $this->Blog_model->get_self_help_sub_categories($cat);
            echo $subs;
        }

        public function self_help(){
            $data['title'] = 'Self Help - GetValue';
            $data['table_rows'] = $this->Blog_model->self_help();
            $this->setView('self_help', $data);
        }

        public function edit_self_help($post){
            if($post!=""){
                $userID=getUserID($this->userToken);
                $post=sqlSafe($post);
                $data['table_rows'] = $this->Blog_model->load_self_help_details($post);
                $data['subcategories'] = $this->Blog_model->load_self_help_sub_categories($post);
                $data['categories'] = $this->Blog_model->load_self_help_categories();
                $data['title'] = 'Update Self Help - GetValue';
                $data['update'] = $post;
                $this->setView('new_self_help', $data);
            }else{
                redirect('seller/self_help');
            }
        }

        public function new_post(){
            $data['update'] = '';
            $data['title'] ='New Post - GetValue';
            $userID=getUserID($this->userToken);
            $data['userID'] =$userID;
            $this->setView('new_post', $data);
        }

        public function my_posts(){
            $data['title'] = 'My Posts - GetValue';
            $userID=getUserID($this->userToken);
            $data['table_rows'] = $this->Blog_model->my_posts($userID);
            
            $this->setView('my_posts', $data);
        }

        public function post_details($product){
            $data['title'] = 'Blog Post Details - GetValue';
            $userID=getUserID($this->userToken);
            $product=sqlSafe($product);
            $data['table_rows'] = $this->Blog_model->load_post_detail($product, $userID);
            $this->setView('post_details', $data);
        }

        public function pending_posts(){
            $data['title'] = 'Pending Blog Posts - GetValue';
            $data['table_rows'] = $this->Blog_model->pending_posts();
            $this->setView('pending_posts', $data);
        }

        public function posts(){
            $data['title'] = 'Blog Posts - GetValue';
            $userID=getUserID($this->userToken);
            $data['table_rows'] = $this->Blog_model->load_all_posts();
            $this->setView('posts', $data);
        }

        public function save_post(){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('category', 'blog category', 'trim|required');
            $this->form_validation->set_rules('title', 'blog title', 'trim|required');
            $this->form_validation->set_rules('summary', 'summary', 'trim|required');
            $this->form_validation->set_rules('content', 'content', 'trim|required');
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
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);
                $post=sqlSafe($post);

                $check=$this->Blog_model->edit_post($post, $user_data, $userID);
                echo $check;
            }
        }

        public function save_self_help_categories(){
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

                $check=$this->Blog_model->save_self_help_categories($user_data, $userID);
                echo $check;
            }
        }

        public function save_self_help_sub_categories(){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('category', 'main category', 'trim|required');
            $this->form_validation->set_rules('name', 'sub category name', 'trim|required');
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

        public function update_self_help_categories($category){
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

                $check=$this->Blog_model->edit_self_help_categories($category, $user_data, $userID);
                echo $check;
            }
        }

        public function update_self_help_sub_categories($category){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('category', 'main category', 'trim|required');
            $this->form_validation->set_rules('name', 'sub category name', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            if(empty($category)){ die(errorMsg('Select category'));}
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
                $category=sqlSafe($category);

                $check=$this->Blog_model->edit_self_help_sub_categories($category, $user_data, $userID);
                echo $check;
            }
        }

        public function save_blog_categories(){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('category', 'category name', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('category')!=""){
                    die(form_error('category'));
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);

                $check=$this->Blog_model->save_blog_categories($user_data, $userID);
                echo $check;
            }
        }

        public function update_blog_categories($category){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('category', 'category name', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            if(empty($category)){ die(errorMsg('Select category'));}
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('category')!=""){
                    die(form_error('category'));
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);
                $category=sqlSafe($category);

                $check=$this->Blog_model->edit_blog_categories($category, $user_data, $userID);
                echo $check;
            }
        }

        public function save_tv_post(){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('link', 'youtube link', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('link')!=""){
                    die(form_error('link'));
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);

                $check=$this->Blog_model->save_tv_post($user_data, $userID);
                echo $check;
            }
        }

        public function update_tv_post($link_id){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('link', 'youtube link', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('link')!=""){
                    die(form_error('link'));
                }
            }else{
                $user_data = $this->input->post();
                $userID=getUserID($this->userToken);
                $link_id=sqlSafe($link_id);

                $check=$this->Blog_model->update_tv_post($link_id, $user_data, $userID);
                echo $check;
            }
        }

        public function save_self_help(){
            $this->form_validation->set_error_delimiters(errMsg(), '</div>');
            $this->form_validation->set_rules('subcategory', 'sub category', 'trim|required');
            $this->form_validation->set_rules('title', 'help title', 'trim|required');
            $this->form_validation->set_rules('content', 'content', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('subcategory')!=""){
                    die(form_error('subcategory'));
                }elseif(form_error('title')!=""){
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
            $this->form_validation->set_rules('subcategory', 'sub category', 'trim|required');
            $this->form_validation->set_rules('title', 'help title', 'trim|required');
            $this->form_validation->set_rules('content', 'content', 'trim|required');
            $this->form_validation->set_message('required', 'The %s should not be empty.');
            $data = array();

            if ($this->form_validation->run() === FALSE){
                if(form_error('subcategory')!=""){
                    die(form_error('subcategory'));
                }elseif(form_error('title')!=""){
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

        public function delete_post($post){
            $userID=getUserID($this->userToken);
            $post=sqlSafe($post);
            $check = $this->Blog_model->delete_post($post, $userID);
            echo $check;
        }

        public function approve_post($post){
            $userID=getUserID($this->userToken);
            $post=sqlSafe($post);
            $check = $this->Blog_model->approve_post($post, $userID);
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

        public function delete_self_help_category($category){
            $userID=getUserID($this->userToken);
            $category=sqlSafe($category);
            $check = $this->Blog_model->delete_self_help_category($category, $userID);
            echo $check;
        }

        public function delete_self_help_sub_category($category){
            $userID=getUserID($this->userToken);
            $category=sqlSafe($category);
            $check = $this->Blog_model->delete_self_help_sub_category($category, $userID);
            echo $check;
        }

        public function delete_blog_category($category){
            $userID=getUserID($this->userToken);
            $category=sqlSafe($category);
            $check = $this->Blog_model->delete_blog_category($category, $userID);
            echo $check;
        }

        public function delete_tv_post($info){
            $userID=getUserID($this->userToken);
            $info=sqlSafe($info);
            $check = $this->Blog_model->delete_tv_post($info, $userID);
            echo $check;
        }

        public function delete_self_help($post){
            $userID=getUserID($this->userToken);
            $post=sqlSafe($post);
            $check = $this->Blog_model->delete_self_help($post, $userID);
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