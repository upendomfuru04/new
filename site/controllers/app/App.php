<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class App extends CI_Controller {

		public function __construct(){
	        parent::__construct();
	        $this->load->model('app/App_model');
	    }

	    public function index(){
	    	$this->return_error('Request not found!!!');
	    }

	    public function home_list_data(){ 
			$this->authenticate_app();
			$res=array();
	    	$error=false;
	    	$feedback="";

	    	$list=array();
	    	$this->load->model('app/Product_model');
	    	$this->load->model('app/Blog_model');
			$sliderProducts=$this->Product_model->getSliderProducts();
			$categories=$this->Product_model->load_product_categories();
			$ebooks=$this->Product_model->loadProducts('ebooks', "", "", "", "", 15);
			$audiobooks=$this->Product_model->loadProducts('audiobooks', "", "", "", "", 15);
			$onlineTrainings=$this->Product_model->loadProducts('online training & programs', "", "", "", "", 15);
			$popularBooks=$this->Product_model->load_popular_products(6);
			$trendingProducts=$this->Product_model->load_popular_products(5);
			$insiderPopulars=$this->Blog_model->load_popular_posts('insider');
			$contributorPopulars=$this->Blog_model->load_popular_posts('contributor');
			$featuredInsConts=$this->App_model->load_featured_ins_cont();

	        if(sizeof($sliderProducts)>0 || sizeof($categories)>0 || sizeof($ebooks)>0 || sizeof($audiobooks)>0 || sizeof($onlineTrainings)>0 || sizeof($popularBooks)>0 || sizeof($trendingProducts)>0 || sizeof($insiderPopulars)>0 || sizeof($contributorPopulars)>0 || sizeof($featuredInsConts)>0){
	        	$error=false;
	    		$feedback="Data Loaded Successfully";
	        }else{
	        	$error=true;
	    		$feedback="No data found";
	        }

	        $list['sliderProducts']=$sliderProducts;
	        $list['categories']=$categories;
	        $list['ebooks']=$ebooks;
	        $list['audiobooks']=$audiobooks;
	        $list['onlineTrainings']=$onlineTrainings;
	        $list['popularBooks']=$popularBooks;
	        $list['trendingProducts']=$trendingProducts;
	        $list['insiderPopulars']=$insiderPopulars;
	        $list['contributorPopulars']=$contributorPopulars;
	        $list['featuredInsConts']=$featuredInsConts;

		    $res['error']=$error;
	        $res['feedback']=$feedback;
	        $res['result']=json_encode($list);
	        $result=json_encode($res);
	        echo $result;
		}

		public function load_messages(){
			$this->authenticate_app();
			$res=array();
	    	$error=false;
	    	$feedback="";
	    	$data = $this->input->post();
	    	if(!isset($data['username']) || $data['username']==""){
	    		$this->return_error('Please! enter your username or logout then login.');
	    	}
			$userID=getUserIDFromUsername($data['username']);
			$list=array();
	        $messages=$this->App_model->load_messages($userID);
	        if(sizeof($messages)>0){
	        	$error=false;
	    		$feedback="Data Loaded Successfully";
	        }else{
	        	$error=true;
	    		$feedback="No data found";
	        }
	        $list['messages']=$messages;

		    $res['error']=$error;
	        $res['feedback']=$feedback;
	        $res['result']=json_encode($list);
	        $result=json_encode($res);
	        echo $result;
		}

		public function load_chats(){
			$this->authenticate_app();
			$res=array();
	    	$error=false;
	    	$feedback="";
	    	$data = $this->input->post();
	    	if(!isset($data['username']) || $data['username']==""){
	    		$this->return_error('Please! enter your username or logout then login.');
	    	}
	    	if(!isset($data['msgID']) || $data['msgID']==""){
	    		$this->return_error('Error! No msg id.');
	    	}
	    	if(!isset($data['subject']) || $data['subject']==""){
	    		$this->return_error('Error! No msg subject.');
	    	}
			$userID=getUserIDFromUsername($data['username']);
			$msgID=$data['msgID'];
			$subject=$data['subject'];
			$list=array();
	        $messages=$this->App_model->load_chats($userID, $msgID, $subject);
	        if(sizeof($messages)>0){
	        	$error=false;
	    		$feedback="Data Loaded Successfully";
	        }else{
	        	$error=true;
	    		$feedback="No data found";
	        }
	        $list['messages']=$messages;

		    $res['error']=$error;
	        $res['feedback']=$feedback;
	        $res['result']=json_encode($list);
	        $result=json_encode($res);
	        echo $result;
		}

		public function send_message(){
			$this->authenticate_app();
			$res=array();
	    	$error=false;
	    	$feedback="";
	    	$data = $this->input->post();
	    	if(!isset($data['username']) || $data['username']==""){
	    		$this->return_error('Please! enter your username or logout then login.');
	    	}
	    	if(!isset($data['msg']) || $data['msg']==""){
	    		$this->return_error('Please! enter your message.');
	    	}
	    	if(!isset($data['msgID']) || $data['msgID']==""){
	    		$this->return_error('Error! No msg id.');
	    	}
	    	if(!isset($data['subject']) || $data['subject']==""){
	    		$this->return_error('Error! No msg subject.');
	    	}
			$userID=getUserIDFromUsername($data['username']);
			$msgID=$data['msgID'];
			$msg=$data['msg'];
			$subject=$data['subject'];
			$list=array();
			$messages=array();
	        $check=$this->App_model->send_message($userID, $msgID, $msg);
	        if($check=="Success"){
	        	$error=false;
	    		$feedback="Msg Sent Successfully";
	    		$messages=$this->App_model->load_chats($userID, $msgID, $subject);
	        }else{
	        	$error=true;
	    		$feedback=$check;
	        }
	        $list['messages']=$messages;

		    $res['error']=$error;
	        $res['feedback']=$feedback;
	        $res['result']=json_encode($list);
	        $result=json_encode($res);
	        echo $result;
		}

		public function load_sellers(){
			$this->authenticate_app();
			$res=array();
	    	$error=false;
	    	$feedback="";
			$list=array();
	        $sellers=$this->App_model->load_sellers();
	        if(sizeof($sellers)>0){
	        	$error=false;
	    		$feedback="Data Loaded Successfully";
	        }else{
	        	$error=true;
	    		$feedback="No data found";
	        }
	        $list['sellers']=$sellers;

		    $res['error']=$error;
	        $res['feedback']=$feedback;
	        $res['result']=json_encode($list);
	        $result=json_encode($res);
	        echo $result;
		}

		public function search_keyword(){
			$this->authenticate_app();
			$res=array();
	    	$error=false;
	    	$feedback="";
	    	$data = $this->input->post();
	    	if(!isset($data['keyword']) || $data['keyword']==""){
	    		$this->return_error('Please! enter your keyword to search.');
	    	}
	    	$keyword=$data['keyword'];
			$list=array();
	        $products=$this->App_model->search_products_results($keyword);
	        $blogs=$this->App_model->search_post_results($keyword);
	        if(sizeof($products)>0 || sizeof($blogs)>0){
	        	$error=false;
	    		$feedback="Data Loaded Successfully";
	        }else{
	        	$error=true;
	    		$feedback="No data found";
	        }
	        $list['products']=$products;
	        $list['blogs']=$blogs;

		    $res['error']=$error;
	        $res['feedback']=$feedback;
	        $res['result']=json_encode($list);
	        $result=json_encode($res);
	        echo $result;
		}

		public function subscribe_email(){
			$this->authenticate_app();
			$res=array();
	    	$error=false;
	    	$feedback="";
	    	$data = $this->input->post();
	    	if(!isset($data['email']) || $data['email']==""){
	    		$this->return_error('Please! enter your email address to subscribe.');
	    	}
	    	$email=$data['email'];
			$list=array();
	        $check=$this->App_model->save_subscription($email);
	        if($check=="Success"){
	        	$error=false;
	    		$feedback="Thanks for your subscription";
	        }else{
	        	$error=true;
	    		$feedback=$check;
	        }

		    $res['error']=$error;
	        $res['feedback']=$feedback;
	        $result=json_encode($res);
	        echo $result;
		}
		
		public function test(){
		    echo json_encode(['data' => "Hey there"]);
		}

		function authenticate_app(){
			$data = $this->input->post();
			if(!isset($data['auth']) || $data['auth']!="b81bd363b70fb1a6e921150a4df6413d"){
				$res=array();
				$res['error']=true;
		        $res['feedback']="You are not authenticated";        
		        $result=json_encode($res);
				die($result);
			}
		}

		function return_error($feedback){
			$res=array();
			$res['error']=true;
	        $res['feedback']=$feedback;        
	        $result=json_encode($res);
			die($result);
		}

	}
?>