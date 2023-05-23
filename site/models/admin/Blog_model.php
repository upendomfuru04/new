<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Blog_model extends CI_Model{

	    public function __construct(){
	        parent::__construct();
	        $this->load->database();
    	}

    	public function load_blog_categories(){
			$result=array();
			$this->db->select('id, category');
		   	$this->db->where('status!=', '1');
		   	$this->db->order_by('createdDate', 'DESC');
	   		$query = $this->db->get('tbl_blog_category');
		   	// return $query->row_array();
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

    	public function load_self_help_categories(){
			$result=array();
			$this->db->select('id, name');
		   	$this->db->where('status!=', '1');
		   	$this->db->order_by('createdDate', 'DESC');
	   		$query = $this->db->get('tbl_self_category');
		   	// return $query->row_array();
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

    	public function get_self_help_sub_categories($category){
			$result=array();
			$this->db->select('id, name');
		   	$this->db->where('category', $category);
		   	$this->db->where('status', '0');
		   	$this->db->order_by('createdDate', 'DESC');
	   		$query = $this->db->get('tbl_self_sub_category');
		   	if($query->num_rows() > 0){
		   		foreach ($query->result() as $data) {
		   			echo '<option value="'.$data->id.'">'.$data->name.'</option>';
		   		}
		   	}
		}

    	public function load_self_help_sub_categories(){
			$result=array();
			$this->db->select('tbl_self_sub_category.id, tbl_self_category.name as category, tbl_self_sub_category.name as name');
		   	$this->db->from('tbl_self_sub_category');
		   	$this->db->join('tbl_self_category', 'tbl_self_category.id=tbl_self_sub_category.category');
		   	$this->db->where('tbl_self_category.status!=', '1');
		   	$this->db->where('tbl_self_sub_category.status!=', '1');
		   	$this->db->order_by('tbl_self_sub_category.createdDate', 'DESC');
	   		$query = $this->db->get();
		   	// return $query->row_array();
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function load_self_help_category_details($category){
			$this->db->select('id, name');
			$this->db->where('id', $category);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_self_category');
		   	if($query->num_rows() > 0)
		   	return $query->row_array();
		}

		public function load_self_help_sub_category_details($category){
			$this->db->select('id, name, category');
			$this->db->where('id', $category);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_self_sub_category');
		   	if($query->num_rows() > 0)
		   	return $query->row_array();
		}

		public function load_blog_category_details($category){
			$this->db->select('id, category');
			$this->db->where('id', $category);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_blog_category');
		   	if($query->num_rows() > 0)
		   	return $query->row_array();
		}

		public function load_post_detail($post, $userID){
			$this->db->select('tbl_blog_post.id, title, image, tbl_blog_category.category, content, is_approved, denied_reason');
			$this->db->from('tbl_blog_post');
			$this->db->join('tbl_blog_category', 'tbl_blog_category.id=tbl_blog_post.category');
			$this->db->where('tbl_blog_post.id', $post);
			// $this->db->where('postedBy', $userID);
		   	$this->db->where('tbl_blog_post.status', '0');
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0)
		   	return $query->row_array();
		}

		public function load_post_details($post, $userID){
			$this->db->select('id, title, image, category, content, summary, seller_type');
			$this->db->where('id', $post);
			// $this->db->where('postedBy', $userID);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_blog_post');
		   	if($query->num_rows() > 0)
		   	return $query->row_array();
		}

		public function pending_posts(){
			$result=array();
			$this->db->select('tbl_blog_post.id, image, title, tbl_blog_category.category, is_approved, postedBy, postedDate, url');
			$this->db->from('tbl_blog_post');
			$this->db->join('tbl_blog_category', 'tbl_blog_category.id=tbl_blog_post.category');
		   	$this->db->where('tbl_blog_post.status', '0');
		   	$this->db->where('tbl_blog_post.is_approved', '1');
		   	$this->db->order_by('postedDate', 'DESC');
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function approve_post($post, $userID){
			$approvedDate=time();
			$this->db->set('approvedBy', $userID);
			$this->db->set('approvedDate', $approvedDate);
			$this->db->set('is_approved', '0');
			$this->db->where('status', '0');
	   		$this->db->where('id', $post);
	     	$this->db->update('tbl_blog_post');
		   	return 'Success';
		}

		public function deny_post($post, $reason, $userID){
			$denyDate=time();
			$this->db->set('deniedBy', $userID);
			$this->db->set('deniedDate', $denyDate);
			$this->db->set('denied_reason', $reason);
			$this->db->set('is_approved', '2');
			$this->db->where('status', '0');
	   		$this->db->where('id', $post);
	     	$this->db->update('tbl_blog_post');
		   	return 'Success';
		}

		public function load_all_posts(){
			$result=array();
			$this->db->select('tbl_blog_post.id, image, title, tbl_blog_category.category, is_approved, postedBy, postedDate, url');
			$this->db->from('tbl_blog_post');
			$this->db->join('tbl_blog_category', 'tbl_blog_category.id=tbl_blog_post.category');
		   	$this->db->where('tbl_blog_post.status', '0');
		   	$this->db->order_by('postedDate', 'DESC');
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function delete_post($post, $userID){
			$this->db->set('status', '1');
			// $this->db->where('postedBy', $userID);
	   		$this->db->where('id', $post);
	     	$this->db->update('tbl_blog_post');
		   	return 'Success';
		}

		public function load_tv_posts(){
			$result=array();
			$this->db->select('id, link');
			$this->db->from('tbl_tv');
		   	$this->db->where('status', '0');
		   	$this->db->order_by('createdDate', 'DESC');
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function load_tv_post_details($info){
			$this->db->select('id, link');
			$this->db->where('id', $info);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_tv');
		   	if($query->num_rows() > 0)
		   	return $query->row_array();
		}

		public function self_help(){
			$result=array();
			$this->db->select('tbl_self_help.id, image, title, tbl_self_sub_category.name as subcategory, content, postedDate, views, url');
			$this->db->from('tbl_self_help');
			$this->db->join('tbl_self_sub_category', 'tbl_self_sub_category.id=sub_category', 'LEFT');
		   	$this->db->where('tbl_self_help.status', '0');
		   	$this->db->order_by('postedDate', 'DESC');
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function load_self_help_details($post){
			$this->db->select('tbl_self_help.id, title, image, content, tbl_self_sub_category.category, sub_category');
			$this->db->from('tbl_self_help');
			$this->db->join('tbl_self_sub_category', 'tbl_self_sub_category.id=tbl_self_help.sub_category AND tbl_self_sub_category.status="0"', 'LEFT');
			$this->db->where('tbl_self_help.id', $post);
		   	$this->db->where('tbl_self_help.status', '0');
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0)
		   	return $query->row_array();
		}

		public function my_posts($userID){
			$result=array();
			$this->db->select('tbl_blog_post.id, image, title, tbl_blog_category.category, is_approved, postedDate, views, url');
			$this->db->from('tbl_blog_post');
			$this->db->join('tbl_blog_category', 'tbl_blog_category.id=tbl_blog_post.category');
			$this->db->where('admin', $userID);
		   	$this->db->where('tbl_blog_post.status', '0');
		   	$this->db->order_by('postedDate', 'DESC');
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

    	public function save_self_help_categories($data, $userID){
    		$createdDate=time();
    		$name=sqlSafe($data['name']);

    		$this->db->select('id');
	        $this->db->where('name', $name);
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_self_category');
	        if($query->num_rows() > 0){
	            die(ErrorMsg("Category already exist"));
	        }

            $set=$this->db->insert('tbl_self_category', array(
	            'name' => $name,
	            'status' => '0',
		        'createdBy' => $userID,
	            'createdDate' => $createdDate
	        ));

	        if($set){
	        	return 'Success';
	        }else{
	        	return ErrorMsg($this->db->_error_message());
	        }
		}

    	public function save_self_help_sub_categories($data, $userID){
    		$createdDate=time();
    		$category=sqlSafe($data['category']);
    		$name=sqlSafe($data['name']);

    		$this->db->select('id');
	        $this->db->where('category', $category);
	        $this->db->where('name', $name);
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_self_sub_category');

	        if($query->num_rows() > 0){
	            die(ErrorMsg("Sub category already exist"));
	        }

            $set=$this->db->insert('tbl_self_sub_category', array(
	            'name' => $name,
	            'category' => $category,
	            'status' => '0',
		        'createdBy' => $userID,
	            'createdDate' => $createdDate
	        ));

	        if($set){
	        	return 'Success';
	        }else{
	        	return ErrorMsg($this->db->_error_message());
	        }
		}

		public function edit_self_help_categories($category, $data, $userID){
    		$name=sqlSafe($data['name']);

    		$this->db->select('id');
	        $this->db->where('name', $name);
	        $this->db->where('status','0');
	        $this->db->where('id !=', $category);
	        $query = $this->db->get('tbl_self_category');

	        if($query->num_rows() > 0){
	            die(ErrorMsg("Category already exist"));
	        }

            $set=array(
	            'name' => $name
	        );

	        $this->db->where('id', $category);
	     	$this->db->update('tbl_self_category', $set);
        	return 'Success';
		}

		public function edit_self_help_sub_categories($sub_id, $data, $userID){
    		$category=sqlSafe($data['category']);
    		$name=sqlSafe($data['name']);

    		$this->db->select('id');
	        $this->db->where('category',$category);
	        $this->db->where('name',$name);
	        $this->db->where('status','0');
	        $this->db->where('id !=', $sub_id);
	        $query = $this->db->get('tbl_self_sub_category');

	        if($query->num_rows() > 0){
	            die(ErrorMsg("Sub category already exist"));
	        }

            $set=array(
	            'category' => $category,
	            'name' => $name
	        );

	        $this->db->where('id', $sub_id);
	     	$this->db->update('tbl_self_sub_category', $set);
        	return 'Success';
		}

    	public function save_blog_categories($data, $userID){
    		$createdDate=time();
    		$category=sqlSafe($data['category']);

    		$this->db->select('id');
	        $this->db->where('category', $category);
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_blog_category');

	        if($query->num_rows() > 0){
	            die(ErrorMsg("Category already exist"));
	        }

            $set=$this->db->insert('tbl_blog_category', array(
	            'category' => $category,
	            'status' => '0',
		        'createdBy' => $userID,
	            'createdDate' => $createdDate
	        ));

	        if($set){
	        	return 'Success';
	        }else{
	        	return ErrorMsg($this->db->_error_message());
	        }
		}

		public function edit_blog_categories($categoryID, $data, $userID){
    		$category=sqlSafe($data['category']);

    		$this->db->select('id');
	        $this->db->where('category', $category);
	        $this->db->where('status','0');
	        $this->db->where('id !=', $categoryID);
	        $query = $this->db->get('tbl_blog_category');

	        if($query->num_rows() > 0){
	            die(ErrorMsg("Category already exist"));
	        }

            $set=array(
	            'category' => $category
	        );

	        $this->db->where('id', $categoryID);
	     	$this->db->update('tbl_blog_category', $set);
        	return 'Success';
		}

    	public function save_tv_post($data, $userID){
    		$createdDate=time();
    		$link=sqlSafe($data['link']);

    		$this->db->select('id');
	        $this->db->where('link', $link);
	        $this->db->where('status', '0');
	        $query = $this->db->get('tbl_tv');

	        if($query->num_rows() > 0){
	            die(ErrorMsg("This post already exist"));
	        }

            $set=$this->db->insert('tbl_tv', array(
	            'link' => $link,
	            'status' => '0',
	            'createdBy' => $userID,
	            'createdDate' => $createdDate
	        ));

	        if($set){
	        	return 'Success';
	        }else{
	        	return ErrorMsg($this->db->_error_message());
	        }
		}

    	public function update_tv_post($link_id, $data, $userID){
    		$link=sqlSafe($data['link']);

    		$set=array(
	            'link' => $link
	        );
	        $this->db->where('id', $link_id);
	     	$this->db->update('tbl_tv', $set);
	        return 'Success';
		}

		public function save_self_help($data, $userID){
    		$createdDate=time();
    		$title=sqlSafe($data['title']);
    		$subcategory=sqlSafe($data['subcategory']);
    		$content=sqlSafe($data['content']);

    		unset($_SESSION['uploaded_file']);
    		$imagePost="";
    		if(isset($_FILES['image']) && $_FILES['image']['name']!=""){
    			$image=do_upload('media/help', 'image', str_replace(" ", "_", $title), '', '', '', 'image');
    			if($image!='ok'){ 
    				die(ErrorMsg($image));
    			}
    			$imagePost=$this->session->userdata('uploaded_file');
    		}
    		
    		$url=generateLink('tbl_self_help', 'url', $title);

    		$this->db->select('id');
	        $this->db->where('sub_category', $subcategory);
	        $this->db->where('title', $title);
	        $this->db->where('content', htmlspecialchars($content, ENT_QUOTES));
	        $this->db->where('admin', $userID);
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_self_help');

	        if($query->num_rows() > 0){
	            die(ErrorMsg("Self help post already exist"));
	        }

            $set=$this->db->insert('tbl_self_help', array(
	            'image' => $imagePost,
	            'sub_category' => $subcategory,
	            'title' => $title,
	            'url' => $url,
	            'content' => htmlspecialchars($content, ENT_QUOTES),
	            'admin' => $userID,
	            'status' => '0',
	            'postedDate' => $createdDate
	        ));

	        if($set){
	        	return 'Success';
	        }else{
	        	return ErrorMsg($this->db->_error_message());
	        }
		}

    	public function edit_self_help($post, $data){
    		$createdDate=time();
    		$title=sqlSafe($data['title']);
    		$subcategory=sqlSafe($data['subcategory']);
    		$content=sqlSafe($data['content']);

    		$postImage="";
    		if(isset($_FILES['image']) && $_FILES['image']['name']!=""){
    			$image=do_upload('media/help', 'image', str_replace(" ", "_", $title), '', '', '', 'image');
    			if($image!='ok'){ 
    				die(ErrorMsg($image));
    			}
    			$postImage=$this->session->userdata('uploaded_file');
    		}
    		
    		$url=generateLink('tbl_self_help', 'url', $title, $post);
    		$set=array(
	            'sub_category' => $subcategory,
	            'title' => $title,
	            'content' => htmlspecialchars($content, ENT_QUOTES),
	            'url' => $url
	        );
    		if($postImage!=""){ $set['image']=$postImage;}

	        $this->db->where('id',$post);
	     	$this->db->update('tbl_self_help', $set);
	        return 'Success';
		}

		public function save_post($data, $userID){
    		$createdDate=time();
    		$title=sqlSafe($data['title']);
    		$category=sqlSafe($data['category']);
    		$content=sqlSafe($data['content']);
    		$summary=sqlSafe($data['summary']);
    		$res=array();
    		/*$doc = new DOMDocument();
			$doc->loadHTML($data['content']);
			$content = $doc->saveHTML();*/

    		unset($_SESSION['uploaded_file']);
    		$imagePost="";
    		if(isset($_FILES['image']) && $_FILES['image']['name']!=""){
    			$image=do_upload('media/blog', 'image', str_replace(" ", "_", $title), '', '', '', 'image');
    			if($image!='ok'){ 
    				die(ErrorMsg($image));
    			}
    			$imagePost=$this->session->userdata('uploaded_file');
    		}else{
    			die(ErrorMsg("You must upload post image"));
    		}
    		
    		$url=generateLink('tbl_blog_post', 'url', $title);

    		$this->db->select('id');
	        $this->db->where('category', $category);
	        $this->db->where('title', $title);
	        $this->db->where('content', htmlspecialchars($content, ENT_QUOTES));
	        $this->db->where('admin', $userID);
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_blog_post');

	        if($query->num_rows() > 0){
	            die(ErrorMsg("Blog post already exist"));
	        }

            $set=$this->db->insert('tbl_blog_post', array(
	            'category' => $category,
	            'image' => $imagePost,
	            'title' => $title,
	            'summary' => htmlspecialchars($summary, ENT_QUOTES),
	            'url' => $url,
	            'content' => htmlspecialchars($content, ENT_QUOTES),
	            'seller_type' => 'admin',
	            'is_approved' => '1',
	            'admin' => $userID,
	            'status' => '0',
	            'postedDate' => $createdDate
	        ));

	        if($set){
	        	$res['msg']="Success";
		     	$res['url']=base_url().'home/blog_post/'.$url;
	        	return json_encode($res);
	        }else{
	        	return ErrorMsg($this->db->_error_message());
	        }
		}

    	public function edit_post($post, $data, $userID){
    		$createdDate=time();
    		$res=array();
    		$title=sqlSafe($data['title']);
    		$category=sqlSafe($data['category']);
    		$content=sqlSafe($data['content']);
    		$summary=sqlSafe(stripslashes(htmlspecialchars_decode($data['summary'])));
    		/*$doc = new DOMDocument();
			$doc->loadHTML($content);
			$content = $doc->saveHTML();*/

    		$postImage="";
    		if(isset($_FILES['image']) && $_FILES['image']['name']!=""){
    			$image=do_upload('media/blog', 'image', str_replace(" ", "_", $title), '', '', '', 'image');
    			if($image!='ok'){ 
    				die(ErrorMsg($image));
    			}
    			$postImage=$this->session->userdata('uploaded_file');
    		}
    		
    		$url=generateLink('tbl_blog_post', 'url', $title, $post);
    		$set=array(
	            'title' => sqlSafe($title),
	            'summary' => sqlSafe(htmlspecialchars($summary, ENT_QUOTES)),
	            'category' => sqlSafe($category),
	            'content' => htmlspecialchars($content, ENT_QUOTES),
	            'url' => $url
	        );
    		if($postImage!=""){ $set['image']=$postImage;}

	        // $this->db->where('admin',$userID);
	        $this->db->where('id',$post);
	     	$this->db->update('tbl_blog_post', $set);
	        $res['msg']="Success";
	     	$res['url']=base_url().'home/blog_post/'.$url;
        	return json_encode($res);
		}

		public function delete_self_help_category($category, $userID){
			$this->db->set('status', '1');
	   		$this->db->where('id', $category);
	     	$this->db->update('tbl_self_category');
		   	return 'Success';
		}

		public function delete_self_help_sub_category($category, $userID){
			$this->db->set('status', '1');
	   		$this->db->where('id', $category);
	     	$this->db->update('tbl_self_sub_category');
		   	return 'Success';
		}

		public function delete_blog_category($category, $userID){
			$this->db->set('status', '1');
	   		$this->db->where('id', $category);
	     	$this->db->update('tbl_blog_category');
		   	return 'Success';
		}

		public function delete_tv_post($infoID, $userID){
			$this->db->set('status', '1');
	   		$this->db->where('id', $infoID);
	     	$this->db->update('tbl_tv');
		   	return 'Success';
		}

		public function delete_self_help($post, $userID){
			$this->db->set('status', '1');
	   		$this->db->where('id', $post);
	     	$this->db->update('tbl_self_help');
		   	return 'Success';
		}

    }
