<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Blog extends CI_Controller {

		public function __construct(){
	        parent::__construct();
	        $this->load->model('app/Blog_model');
	    }

	    public function index(){
	    	$this->return_error('Request not found!!!');
	    }

	    public function load_all_post(){ 
			$this->authenticate_app();
			$res=array();
	    	$error=false;
	    	$feedback="";

	    	$data = $this->input->post();
	    	$category=$data['category'];
	    	$list=array();
			$posts=$this->Blog_model->load_posts($category);

	        if(sizeof($posts)>0){
	        	$error=false;
	    		$feedback="Data Loaded Successfully";
	        }else{
	        	$error=true;
	    		$feedback="No data found";
	        }
	        $list['posts']=$posts;

		    $res['error']=$error;
	        $res['feedback']=$feedback;
	        $res['result']=json_encode($list);
	        $result=json_encode($res);
	        echo $result;
		}

		public function load_single_post(){ 
			$this->authenticate_app();
			$res=array();
	    	$error=false;
	    	$feedback="";
	    	$data = $this->input->post();
	    	if(!isset($data['blogID']) || $data['blogID']==""){
	    		$this->return_error('Sorry! no blog post selected');
	    	}
	    	$blogID=$data['blogID'];
	    	$list=array();
			$post_info=$this->Blog_model->load_single_post($blogID);
			$comments=$this->Blog_model->load_post_comments($blogID);

	        if(sizeof($post_info)>0){
	        	$error=false;
	    		$feedback="Data Loaded Successfully";
	        }else{
	        	$error=true;
	    		$feedback="No data found";
	        }
	        $list['post_info']=$post_info;
	        $list['comments']=$comments;

		    $res['error']=$error;
	        $res['feedback']=$feedback;
	        $res['result']=json_encode($list);
	        $result=json_encode($res);
	        echo $result;
		}

		public function send_post_comment(){
			$this->authenticate_app();
			$res=array();
	    	$error=false;
	    	$feedback="";
	    	$data = $this->input->post();
	    	if(!isset($data['username']) || $data['username']==""){
	    		$this->return_error('Please! enter your username or logout then login.');
	    	}
	    	if(!isset($data['blogID']) || $data['blogID']==""){
	    		$this->return_error('Please! select post to comment');
	    	}
	    	if(!isset($data['comment']) || $data['comment']==""){
	    		$this->return_error('Please! enter your comment');
	    	}
			$userID=getUserIDFromUsername($data['username']);
			$comment=json_encode($data['comment']);
			$blogID=$data['blogID'];
			$rate_counter="";
			$parent="";
			if(isset($data['rate_counter']) && $data['rate_counter']!=""){
				$rate_counter=$data['rate_counter'];
			}
			if(isset($data['parent']) && $data['parent']!=""){
				$parent=$data['parent'];
			}

			$list=array();
			$comments=array();
	        $check=$this->Blog_model->post_comment($userID, $blogID, $comment, $rate_counter, $parent);
	        if($check=='Success'){
	        	$error=false;
	    		$feedback="Thanks for your comment";
	    		$comments=$this->Blog_model->load_post_comments($blogID);
	        }else{
	        	$error=true;
	    		$feedback=$check;
	        }
	        $list['comments']=$comments;

		    $res['error']=$error;
	        $res['feedback']=$feedback;
	        $res['result']=json_encode($list);
	        $result=json_encode($res);
	        echo $result;
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