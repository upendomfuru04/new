<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Blog_model extends CI_Model{

	    public function __construct(){
	        parent::__construct();
	        $this->load->database();
    	}

		public function load_popular_posts($seller_type){
			$result=array();
			$this->db->select('tbl_blog_post.id, title, image, tbl_blog_category.category, views, url');
			$this->db->from('tbl_blog_post');
			$this->db->join('tbl_blog_category', 'tbl_blog_category.id=tbl_blog_post.category');
		   	$this->db->where('tbl_blog_post.status', '0');
		   	$this->db->where('tbl_blog_category.status', '0');
			$this->db->where('tbl_blog_post.is_approved', '0');
		   	$this->db->where('tbl_blog_post.seller_type', $seller_type);
		   	$this->db->order_by('views', 'DESC');
		   	$this->db->limit(8);	   	
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	            	$res=array();
	            	$res['post_id']=$data['id'];
	            	$res['title']=$data['title'];
	            	$res['image']=base_url('media/blog/').$data['image'];
	            	$res['total_views']=$data['views'];
	            	$res['total_comments']=$this->getTotalReviews($data['id']);
	                array_push($result, $res);
	            }
	        }
		   	return json_encode($result);
		}

		public function load_posts($seller_type){
			$result=array();
			$this->db->select('tbl_blog_post.id, title, image, tbl_blog_category.category, views, url');
			$this->db->from('tbl_blog_post');
			$this->db->join('tbl_blog_category', 'tbl_blog_category.id=tbl_blog_post.category');
		   	$this->db->where('tbl_blog_post.status', '0');
		   	$this->db->where('tbl_blog_category.status', '0');
			$this->db->where('tbl_blog_post.is_approved', '0');
			if($seller_type!="")
		   		$this->db->where('tbl_blog_post.seller_type', $seller_type);
		   	$this->db->order_by('views', 'DESC');  	
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	            	$res=array();
	            	$res['post_id']=$data['id'];
	            	$res['title']=$data['title'];
	            	$res['image']=base_url('media/blog/').$data['image'];
	            	$res['total_views']=$data['views'];
	            	$res['total_comments']=$this->getTotalReviews($data['id']);
	                array_push($result, $res);
	            }
	        }
		   	return json_encode($result);
		}

		public function load_single_post($blogID){
			$result=array();
			$this->db->set('views', 'views+1', FALSE);
			$this->db->where('id', $blogID);
		   	$this->db->where('status', '0');
	     	$this->db->update('tbl_blog_post');

			$this->db->select('tbl_blog_post.id, title, image, tbl_blog_category.category, content, postedDate, url, postedBy, seller_type, admin');
			$this->db->from('tbl_blog_post');
			$this->db->join('tbl_blog_category', 'tbl_blog_category.id=tbl_blog_post.category');
		   	$this->db->where('tbl_blog_post.status', '0');
			$this->db->where('tbl_blog_post.is_approved', '0');
		   	$this->db->where('tbl_blog_category.status', '0');
		   	$this->db->where('tbl_blog_post.id', $blogID);
		   	$this->db->order_by('postedDate', 'DESC');
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	            	$res=array();
	            	$postedBy=$data['postedBy'];
	            	$admin=$data['admin'];
	            	$seller_type=$data['seller_type'];
	            	$author_name="";
	            	$avatar="";
	            	if($seller_type=='admin'){
                        $author_name=getAdminFullName($admin);
                        $avatar=base_url().'media/admin_avatars/'.getAdminAvatar($admin);
	            	}else{
                        $author_name=getSellerFullName($postedBy);
                        $avatar=base_url('media/').getSellerBrandAvatar($postedBy);
                    }
	            	$res['post_id']=$data['id'];
	            	$res['title']=$data['title'];
	            	$res['image']=base_url('media/blog/').$data['image'];
	            	$res['category']=$data['category'];
	            	$res['posted_date']=date("M d, Y", $data['postedDate']);
	            	$res['content']=preg_replace('#(<br */?>\s*)+#i', '<br />',stripcslashes(htmlspecialchars_decode(strip_tags($data['content']))));
	            	$res['author_name']=$author_name;
	            	$res['avatar']=$avatar;
	                array_push($result, $res);
	            }
	        }
		   	return $result;
		}

		function load_post_comments($blogID){
			$list="";
			$avatar="";
        	$result=array();
	        $this->db->select('id, comment, parent_id, user_id, admin_id, seller_id, seller_type, commentedDate, star_rating');
	        $this->db->where('blog_id', $blogID);
	        $this->db->where('parent_id', '');
	        $this->db->where('status', '0');
	        $query = $this->db->get('tbl_blog_comments');

		   	if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	            	$res=array();
	                $total_replies=0;
	                $childrens=array();
	                $sender_name="";
	                $avatar="";
	                $parent_id=$data['id'];
	                $rates=$data['star_rating'];
	                $user_id=$data['user_id'];
	                $admin=$data['admin_id'];
	                $seller=$data['seller_id'];
	                if($user_id!=""){
	                	$sender_name=getUserFullName($user_id);
	                	$avatar=base_url().'media/customer_avatars/'.getUserAvatar($user_id);
	                }
	                if($seller!=""){
	                	$sender_name=getSellerBrandName($seller);
	                	$avatar=base_url().'media/'.getSellerBrandAvatar($seller);
	                }
	                if($admin!=""){
	                	$sender_name=getAdminFullName($admin);
	                	$avatar=base_url().'media/admin_avatars/'.getAdminAvatar($admin);
	                }
	                $res['parent_id']=$data['id'];
	                $res['avatar']=$avatar;
	                $res['sender_name']=$sender_name;
	                $res['rates']=$rates;
	                $rvw=ltrim($data['comment'], '"');
	                $rvw=rtrim($rvw, '"');
	                $res['msg']=decodeEmoticons($rvw);
	                $res['send_date']=date('M d, Y H:i', $data['commentedDate']);
	                

	                $this->db->select('id, comment, user_id, admin_id, seller_id, seller_type, commentedDate');
			        $this->db->where('blog_id', $blogID);
			        $this->db->where('parent_id', $parent_id);
			        $this->db->where('status', '0');
			        $sql = $this->db->get('tbl_blog_comments');
			        if($sql->num_rows()>0){
				        foreach ($sql->result_array() as $rows) {
				        	$total_replies++;
				        	$res1=array();
				        	$r_user_id=$rows['user_id'];
			                $r_admin=$rows['admin_id'];
			                $r_seller=$rows['seller_id'];
				        	$r_full_name=""; $r_avatar="";
			                if($r_user_id!=""){
			                	$r_full_name=getUserFullName($r_user_id);
			                	$r_avatar=base_url().'media/customer_avatars/'.getUserAvatar($r_user_id);
			                }
			                if($r_seller!=""){
			                	$r_full_name=getSellerBrandName($r_seller);
			                	$r_avatar=base_url().'media/'.getSellerBrandAvatar($r_seller);
			                }
			                if($r_admin!=""){
			                	$r_full_name=getAdminFullName($r_admin);
			                	$r_avatar=base_url().'media/admin_avatars/'.getAdminAvatar($r_admin);
			                }
				        	$res1['avatar']=$r_avatar;
				        	$res1['sender_name']=$r_full_name;
				        	$rvw1=ltrim($rows['comment'], '"');
	                		$rvw1=rtrim($rvw1, '"');
				        	$res1['msg']=decodeEmoticons($rvw1);
				        	$res1['send_date']=date('M d, Y H:i', $rows['commentedDate']);;
				        	$res1['child_id']=$rows['id'];
				        	array_push($childrens, $res1);
			            }
			        }
	                $res['total_replies']=$total_replies;
		            $res['children']=$childrens;
		            array_push($result, $res);
	            }
	        }
	        return $result;
	    }

	    public function post_comment($userID, $blogID, $comment, $rate_counter, $parent){
			$createdDate=time();

			$this->db->select('id');
	        $this->db->where('blog_id',$blogID);
	        $this->db->where('comment',$comment);
	        $this->db->where('parent_id',$parent);
	        $this->db->where('star_rating', $rate_counter);
	        $this->db->where('user_id',$userID);
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_blog_comments');

	        if($query->num_rows() > 0){
	            die(ErrorMsg("Thanks for your comment, it already posted"));
	        }

	        $set=$this->db->insert('tbl_blog_comments', array(
	            'blog_id' => $blogID,
	            'comment' => $comment,
	            'parent_id' => $parent,
	            'star_rating' => $rate_counter,
	            'user_id' => $userID,
	            'status' => '0',
	            'commentedDate' => $createdDate
	        ));

	        if($set){
	        	return 'Success';
	        }else{
	        	return $this->db->_error_message();
	        }
		}

		public function getTotalReviews($post_id){
			$this->db->select('id');
			$this->db->where('blog_id', $post_id);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_blog_comments');	   	
		   	return $query->num_rows();
		}

	}
?>