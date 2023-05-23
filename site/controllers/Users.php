<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('Users_model');
        $this->userToken=$this->session->userdata('getvalue_user_idetification');
    }

    public function index($index=""){
    	/*$this->load->view('includes/header');
        $this->load->view('pages/login');
        $this->load->view('includes/footer');*/
        redirect(base_url());
    }

	public function view(){
		$this->setView('login');
	}

	public function setView($page, $data=""){
		$this->load->view('includes/header');
        $this->load->view('pages/'.$page, $data);
        $this->load->view('includes/footer');
	}

	private function hash_password($password){
	   return password_hash($password, PASSWORD_BCRYPT);
	}

	public function register(){
		/*$vendorUrl="";
        $userdata=$this->input->get();
        if(isset($userdata['vref']) && $userdata['vref']!=""){
            $vendorUrl=$userdata['vref'];
        }*/
    	$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
		$this->form_validation->set_rules('fname', 'First Name', 'trim|required');
		$this->form_validation->set_rules('sname', 'Surname', 'trim|required');
		$this->form_validation->set_rules('gender', 'Gender', 'trim|required');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_rules('pass_repeat', 'Repeat password', 'trim|required|matches[password]');
		$this->form_validation->set_message('is_unique', 'The %s is already registered.');
		$this->form_validation->set_message('matches', 'The %s does not match.');
   		$data = array();

   		if ($this->form_validation->run() === FALSE){
	        $this->setView('register', $data);
	    }else{
	    	$user_data = $this->input->post();
			// $password=sha1(md5(md5($user_data['password'])));
			$password=$this->hash_password(sqlSafe($user_data['password']));
			$account_type=$user_data['account_type'];
			$data = array(
	 				'vendor_url' => $user_data['vendor_url'],
	 				'fname' => $user_data['fname'],
	 				'sname' => $user_data['sname'],
	 				'gender' => $user_data['gender'],
	 				'phone' => $user_data['phone'],
	 				'email' => $user_data['email'],
	 				'password' => $password,
	 				'account_type' => $user_data['account_type'],
	 			);	

	        $check=$this->Users_model->register($data);
	        if($check=='true'){
	        	$this->session->set_flashdata('feedback', successMsg('You have successfully created an account.'));
	        	if($account_type=='customer'){
	        		// redirect(base_url(''));
	        		redirect_back();
	        	}else{
	        		$this->session->set_flashdata('feedback', successMsg('You have successfully created an account. Activate your account by clicking the activation link sent to your email address. If you don`t get the email try to look in spam folder or <a href="'.base_url().'resend_activation_code?rvtcode='.$user_data['email'].'" class="btn btn-xs btn-success" style="color: #fff;">RESEND THE LINK</a>'));
	        		$this->setView('register');
	        	}
	        }else{
	        	$this->session->set_flashdata('feedback', errorMsg('Failed, '.$check));
	        	$this->setView('register');
	        }
	    }
	}

	public function customer_register_popup(){
    	$this->form_validation->set_error_delimiters(errMsg(), '</div>');
    	$this->form_validation->set_rules('fname', 'First name', 'trim|required');
    	$this->form_validation->set_rules('sname', 'Surname', 'trim|required');
    	$this->form_validation->set_rules('email', 'Email', 'trim|required');
   		$this->form_validation->set_rules('phone', 'Phone number', 'trim|required');
   		$this->form_validation->set_rules('password', 'Password', 'trim|required');
   		$this->form_validation->set_message('required', 'Please fill %s.');
   		$data = array();

   		if ($this->form_validation->run() === FALSE){
	        if(form_error('fname')!=""){
	        	die(form_error('fname'));
	        }elseif(form_error('sname')!=""){
	        	die(form_error('sname'));
	        }elseif(form_error('email')!=""){
	        	die(form_error('email'));
	        }elseif(form_error('phone')!=""){
	        	die(form_error('phone'));
	        }elseif(form_error('password')!=""){
	        	die(form_error('password'));
	        }
	    }else{
	    	$user_data = $this->input->post();			
			$email=sqlSafe($user_data['email']);
			$phone=sqlSafe($user_data['phone']);
			// $password=sha1(md5(md5($user_data['password'])));
			$password=$this->hash_password(sqlSafe($user_data['password']));
			$data = array(
					'fname' => sqlSafe($user_data['fname']),
	 				'sname' => sqlSafe($user_data['sname']),
	 				'gender' => "",
	 				'email' => $email,
	 				'phone' => $phone,
	 				'password' => $password,
	 				'account_type' => "customer",
	 			);

	        $check=$this->Users_model->register($data);
	        if($check=='true'){
	        	echo 'Success';
	        }else{
	        	echo ErrorMsg($check);
	        }
	    }
	}

    public function account_activation(){
        $vtcode="";
        $user_data=$this->input->get();
        $data['title'] = "Account Activation";
        $data['tag'] = "";
        if(isset($user_data['vtcode']) && $user_data['vtcode']!=""){
            $vtcode=sqlSafe($user_data['vtcode']);
            $activ = $this->Users_model->account_activation($vtcode);
            if($activ=='Success'){
            	$data['tag']='Success';
            }else{
            	$data['tag']=$activ;
            }
            $this->setView('account_activation', $data);
        }else{
            $this->setView('account_activation', $data);
        }
    }

    public function resend_activation_code(){
        $rvtcode="";
        $user_data=$this->input->get();
        $data['title'] = "Account Activation";
        $data['tag'] = "";
        if(isset($user_data['rvtcode']) && $user_data['rvtcode']!=""){
            $rvtcode=sqlSafe($user_data['rvtcode']);
            $activ = $this->Users_model->resend_activation_code($rvtcode);
            $data['tag']=$activ;
            $this->setView('resend_activation_code', $data);
        }else{
            $this->setView('resend_activation_code', $data);
        }
    }

	public function recover_password(){
    	$this->form_validation->set_error_delimiters(errMsg(), '</div>');
    	$this->form_validation->set_rules('username', 'Username', 'trim|required');
   		$this->form_validation->set_message('required', 'Please Fill %s.');
   		$data = array();

   		if ($this->form_validation->run() === FALSE){
	        $this->setView('password_recover', $data);
	    }else{
	    	$data = $this->input->post();
	    	$username=$data['username'];
	    	$new_password=getRandomMixedCode(4).''.getRandomMixedCode(4).''.getRandomMixedCode(4);
	    	// $encryted_password=sha1(md5(md5($new_password)));
	    	$encryted_password=$this->hash_password($new_password);

	        $check=$this->Users_model->recover_password($username, $new_password, $encryted_password);
	        if($check=='incorrect'){
	        	$this->session->set_flashdata('feedback', errorMsg('Incorrect Username'));
	        	$this->setView('password_recover');
	        }else{
	        	$this->session->set_flashdata('feedback', successMsg('New password sent to your email address, check your spam folder if it is not in your inbox'));
	        	$this->setView('password_recover');
	        }
	    }
	}

	public function login(){
    	$this->form_validation->set_error_delimiters(errMsg(), '</div>');
    	$this->form_validation->set_rules('username', 'Username', 'trim|required');
   		$this->form_validation->set_rules('password', 'Password', 'trim|required');
   		$this->form_validation->set_message('required', 'Please Fill %s.');
   		$data = array();

   		if ($this->form_validation->run() === FALSE){
	        $this->setView('login', $data);
	    }else{
	    	$data = $this->input->post();
			
			$username=sqlSafe($data['username']);
			$password=sqlSafe($data['password']);
			$old_password=sha1(md5(md5(sqlSafe($data['password']))));
			// $password=$this->hash_password(sqlSafe($data['password']));

	        $check=$this->Users_model->login($username, $password, $old_password);
	        if($check=='incorrect'){
	        	$this->session->set_flashdata('feedback', errorMsg('Incorrect Username Or Password'));
	        	$this->setView('login');
	        }else if($check=='not active'){
	        	$this->session->set_flashdata('feedback', errorMsg('Sorry! your account is not activated yet, please wait or contact us to fastern the activation.'));
	        	$this->setView('login');
	        }else if($check=='blocked'){
	        	$this->session->set_flashdata('feedback', errorMsg('Your account is blocked, please contact us.'));
	        	$this->setView('login');
	        }else if($check=='deleted'){
	        	$this->session->set_flashdata('feedback', errorMsg('Your account is deleted, please contact us or create new account.'));
	        	$this->setView('login');
	        }else{
	        	$this->session->set_flashdata('feedback', successMsg('Success'));
	        	if($check!=""){
	        		redirect(base_url($check));
	        	}else{
	        		// die($_SESSION['redirect_back']);
	        		redirect_back();
	        		// redirect(base_url());
	        	}
	        	
	        }
	    }
	}

	public function user_login(){
    	$this->form_validation->set_error_delimiters(errMsg(), '</div>');
    	$this->form_validation->set_rules('username', 'Username', 'trim|required');
   		$this->form_validation->set_rules('password', 'Password', 'trim|required');
   		$this->form_validation->set_message('required', 'Please Fill %s.');
   		$data = array();

   		if ($this->form_validation->run() === FALSE){
	        if(form_error('username')!=""){
	        	die(form_error('username'));
	        }elseif(form_error('password')!=""){
	        	die(form_error('password'));
	        }
	    }else{
	    	$data = $this->input->post();
			
			$username=sqlSafe($data['username']);
			$password=sqlSafe($data['password']);
			$old_password=sha1(md5(md5(sqlSafe($data['password']))));
			// $password=$this->hash_password(sqlSafe($data['password'])); die($password);

	        $check=$this->Users_model->login($username, $password, $old_password);
	        if($check=='incorrect'){
	        	echo ErrorMsg('Incorrect Username Or Password');
	        }else{
	        	echo 'Success';
	        }
	    }
	}

	public function change_password(){
		if($this->userToken=="" || $this->userToken==null || empty($this->userToken)){
			die(ErrorMsg('Session expired refresh the page...'));
		}
        $this->load->helper('form');
    	$this->load->library('form_validation');

    	$this->form_validation->set_error_delimiters(errMsg(), '</div>');
    	$this->form_validation->set_rules('cpassword', 'Current Password', 'trim|required');
   		$this->form_validation->set_rules('repassword', 'Re-Enter Password', 'trim|required');
   		$this->form_validation->set_rules('password', 'New Password', 'trim|required');
   		$data = array();

   		if ($this->form_validation->run() === FALSE){
	        // die(validation_errors());
	        if(form_error('cpassword')!=""){
	        	die(form_error('cpassword'));
	        }elseif(form_error('password')!=""){
	        	die(form_error('password'));
	        }elseif(form_error('repassword')!=""){
	        	die(form_error('repassword'));
	        }
	    }else{
	    	$data = $this->input->post();			
			$cpassword=sqlSafe($data['cpassword']);
			$repassword=sqlSafe($data['repassword']);
			$password=sqlSafe($data['password']);
			$pass=$this->hash_password($password);
			$old_password=sha1(md5(md5($cpassword)));

			if($password!=$repassword){
				die(errorMsg("Sorry! Your New Password Does Not Match"));
			}
			$userID=getUserID($this->userToken);
	        $check=$this->Users_model->change_password($userID, $cpassword, $pass, $old_password);
        	echo $check;
	    }
	}

	public function my_profile(){
		$userID=getUserID($this->userToken);
		if($userID==""){
            unset($_SESSION['getvalue_user_idetification']);
            $this->session->sess_destroy();
            redirect(base_url().'login');
        }
		$data['title'] = 'My Profile - GetvalueInc';
		$account_type=$this->session->userdata('account_type');
		$data['user_info'] = $this->Users_model->user_info($userID, $account_type);
		if($account_type=='customer'){
			$this->load->view('includes/customer_header.php', $data);
	        $this->load->view('pages/customer/my_profile');
	        $this->load->view('includes/customer_footer.php');
		}else{
			$this->load->view('includes/seller_header.php', $data);
	        $this->load->view('pages/seller/my_profile');
	        $this->load->view('includes/seller_footer.php');
		}
	}

	public function update_seller_profile(){
    	$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
		$this->form_validation->set_rules('full_name', 'Full Name', 'trim|required');
		$this->form_validation->set_rules('gender', 'Gender', 'trim|required');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
   		$data = array();
		$account_type=$this->session->userdata('account_type');
		$userID=getUserID($this->userToken);
   		$data1['title'] = 'My Profile - GetvalueInc';

   		if ($this->form_validation->run() === FALSE){
			$data1['user_info'] = $this->Users_model->user_info($userID, $account_type);
	        $this->load->view('includes/seller_header.php');
	        $this->load->view('pages/seller/my_profile', $data1);
	        $this->load->view('includes/seller_footer.php');
	    }else{
	    	$user_data = $this->input->post();
	    	$avatar="";
	    	if(isset($user_data['avatar'])){ $avatar=$user_data['avatar'];}
			$data = array(
	 				'user_id' => $userID,
	 				'full_name' => $user_data['full_name'],
	 				'gender' => $user_data['gender'],
	 				'phone' => $user_data['phone'],
	 				'email' => $user_data['email'],
	 				'avatar' => $avatar,
	 				'address' => $user_data['address'],
	 				'postCode' => $user_data['postCode'],
	 			);	

	        $check=$this->Users_model->update_seller_profile($data);
	        if($check=='Success'){
	        	$this->session->set_flashdata('feedback', successMsg('You have successfully updated your account.'));
				$data1['user_info'] = $this->Users_model->user_info($userID, $account_type);
				$this->session->set_userdata('user_full_name', $user_data['full_name']);
		        $this->load->view('includes/seller_header.php', $data1);
		        $this->load->view('pages/seller/my_profile');
		        $this->load->view('includes/seller_footer.php');
	        }else{
	        	$this->session->set_flashdata('feedback', errorMsg('Failed '.$check));
				$data1['user_info'] = $this->Users_model->user_info($userID, $account_type);
		        $this->load->view('includes/seller_header.php', $data1);
		        $this->load->view('pages/seller/my_profile');
		        $this->load->view('includes/seller_footer.php');
	        }
	    }
	}

	public function update_profile(){
    	$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
		$this->form_validation->set_rules('fname', 'First Name', 'trim|required');
		$this->form_validation->set_rules('fname', 'Surname', 'trim|required');
		$this->form_validation->set_rules('gender', 'Gender', 'trim|required');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('country', 'Country', 'trim|required');
		$this->form_validation->set_rules('city', 'City', 'trim|required');
		$this->form_validation->set_rules('address', 'Address', 'trim|required');
		$this->form_validation->set_rules('post_code', 'Post Code', 'trim|required');
   		$data = array();
		$account_type=$this->session->userdata('account_type');
		$userID=getUserID($this->userToken);
   		$data1['title'] = 'My Profile - GetvalueInc';

   		if ($this->form_validation->run() === FALSE){
			$data1['user_info'] = $this->Users_model->user_info($userID, $account_type);
	        $this->load->view('includes/customer_header.php');
	        $this->load->view('pages/customer/my_profile', $data1);
	        $this->load->view('includes/customer_footer.php');
	    }else{
	    	$user_data = $this->input->post();
	    	$avatar="";
	    	if(isset($user_data['avatar'])){ $avatar=$user_data['avatar'];}
			$data = array(
	 				'user_id' => $userID,
	 				'fname' => $user_data['fname'],
	 				'sname' => $user_data['sname'],
	 				'gender' => $user_data['gender'],
	 				'phone' => $user_data['phone'],
	 				'email' => $user_data['email'],
	 				'country' => $user_data['country'],
	 				'city' => $user_data['city'],
	 				'address' => $user_data['address'],
	 				'post_code' => $user_data['post_code'],
	 				'avatar' => $avatar
	 			);	

	        $check=$this->Users_model->update_profile($data);
	        if($check=='Success'){
	        	$this->session->set_flashdata('feedback', successMsg('You have successfully updated your account.'));
				$data1['user_info'] = $this->Users_model->user_info($userID, $account_type);
		        $this->load->view('includes/customer_header.php');
		        $this->load->view('pages/customer/my_profile', $data1);
		        $this->load->view('includes/customer_footer.php');
	        }else{
	        	$this->session->set_flashdata('feedback', errorMsg('Failed '.$check));
				$data1['user_info'] = $this->Users_model->user_info($userID, $account_type);
		        $this->load->view('includes/customer_header.php');
		        $this->load->view('pages/customer/my_profile', $data1);
		        $this->load->view('includes/customer_footer.php');
	        }
	    }
	}

	public function logout(){
		foreach ($_SESSION as $key => $val) {
			if($key!=='redirect_back'){
				unset($_SESSION[$key]);
			}
		}
        // unset($_SESSION['getvalue_user_idetification']);
        // $this->session->sess_destroy();
        redirect($this->session->userdata('redirect_back'));
    }
}
