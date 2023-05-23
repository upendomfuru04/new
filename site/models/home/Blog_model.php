<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Blog_model extends CI_Model{

	    public function __construct(){
	        parent::__construct();
	        $this->load->database();
    	}

		public function load_self_help_categories(){
			$result=array();
			$this->db->select('id, name');
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_self_category');
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function load_self_help($keyword){
			$result=array();
			$this->db->select('id, title, url');
		   	$this->db->where('status', '0');
		   	if($keyword!=""){
		   		$this->db->like('title', $keyword);
		   	}
		   	$this->db->order_by('postedDate','DESC');
	   		$query = $this->db->get('tbl_self_help');
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function load_blog_posts($category, $from="", $limit=""){
			$result=array();
			$this->db->select('tbl_blog_post.id, title, image, tbl_blog_category.category, content, postedDate, url, postedBy, seller_type');
			$this->db->from('tbl_blog_post');
			$this->db->join('tbl_blog_category', 'tbl_blog_category.id=tbl_blog_post.category');
		   	$this->db->where('tbl_blog_post.status', '0');
			$this->db->where('tbl_blog_post.is_approved', '0');
		   	$this->db->where('tbl_blog_category.status', '0');
		   	$this->db->where('tbl_blog_category.category', $category);
		   	if($from!="" && $limit!=""){ 
		   		$this->db->limit($limit, $from);
		   	}elseif($limit!=""){
		   		$this->db->limit($limit);
		   	}
		   	$this->db->order_by('postedDate', 'DESC');
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function total_blog_posts($category){
			$this->db->select('tbl_blog_post.id, title, image, tbl_blog_category.category, content, postedDate, url, postedBy, seller_type');
			$this->db->from('tbl_blog_post');
			$this->db->join('tbl_blog_category', 'tbl_blog_category.id=tbl_blog_post.category');
		   	$this->db->where('tbl_blog_post.status', '0');
			$this->db->where('tbl_blog_post.is_approved', '0');
		   	$this->db->where('tbl_blog_category.status', '0');
		   	$this->db->where('tbl_blog_category.category', $category);
		   	$this->db->order_by('postedDate', 'DESC');
	   		$query = $this->db->get();		   	
		   	return $query->num_rows();
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
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function load_blog_post($post){
			$result=array();
			$this->db->set('views', 'views+1', FALSE);
			$this->db->where('url', $post);
		   	$this->db->where('status', '0');
	     	$this->db->update('tbl_blog_post');

			$this->db->select('tbl_blog_post.id, title, image, tbl_blog_category.category, content, postedDate, url, postedBy, seller_type');
			$this->db->from('tbl_blog_post');
			$this->db->join('tbl_blog_category', 'tbl_blog_category.id=tbl_blog_post.category');
		   	$this->db->where('tbl_blog_post.status', '0');
			$this->db->where('tbl_blog_post.is_approved', '0');
		   	$this->db->where('tbl_blog_category.status', '0');
		   	$this->db->where('tbl_blog_post.url', $post);
		   	$this->db->order_by('postedDate', 'DESC');	   	
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0)
		   	$result = $query->row_array();
		   	return $result;
		}

		public function load_self_help_post($post){
			$result=array();
			$this->db->set('views', 'views+1', FALSE);
			$this->db->where('url', $post);
		   	$this->db->where('status', '0');
	     	$this->db->update('tbl_self_help');

			$this->db->select('id, title, image, content, postedDate, url');
			$this->db->from('tbl_self_help');
		   	$this->db->where('status', '0');
		   	$this->db->where('url', $post);
		   	$this->db->order_by('postedDate', 'DESC');	   	
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0)
		   	$result = $query->row_array();
		   	return $result;
		}

		public function load_recent_posts(){
			$result=array();
			$this->db->select('tbl_blog_post.id, title, image, postedDate, url');
			$this->db->from('tbl_blog_post');
			$this->db->join('tbl_blog_category', 'tbl_blog_category.id=tbl_blog_post.category');
		   	$this->db->where('tbl_blog_post.status', '0');
			$this->db->where('tbl_blog_post.is_approved', '0');
		   	$this->db->where('tbl_blog_category.status', '0');
		   	$this->db->order_by('postedDate', 'DESC');	   	
		   	$this->db->limit(6);	   	
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function load_tv_posts(){
			$result=array();
			$this->db->select('id, link');
			$this->db->from('tbl_tv');
		   	$this->db->where('status', '0');
		   	$this->db->order_by('createdDate', 'DESC');	   	
		   	$this->db->limit(6);	   	
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function load_home_post(){
			$result=array();
			$this->db->select('tbl_blog_post.id, title, content, summary, url');
			$this->db->from('tbl_blog_post');
			$this->db->join('tbl_blog_category', 'tbl_blog_category.id=tbl_blog_post.category');
		   	$this->db->where('tbl_blog_post.status', '0');
			$this->db->where('tbl_blog_post.is_approved', '0');
		   	$this->db->where('tbl_blog_category.status', '0');
		   	$this->db->order_by('views', 'DESC');	   	
		   	$this->db->limit(3);	   	
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		function load_user_comments($post){
			$list="";
        	$listArray=array();
	        $this->db->select('id, comment, parent_id, user_id, admin_id, seller_id, seller_type, commentedDate, star_rating');
	        $this->db->where('blog_id', $post);
	        $this->db->where('parent_id', '');
	        $this->db->where('status', '0');
	        $query = $this->db->get('tbl_blog_comments');
		   	if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	                $id=$data['id'];
	                // $comment=decodeEmoticons($data['comment']);
	                $comment=json_decode($data['comment']);
	                $user_id=$data['user_id'];
	                $admin=$data['admin_id'];
	                $seller=$data['seller_id'];
	                $full_name=""; $replys="";
	                $avatar="";
	                $star=$data['star_rating'];
	                $star_rate=' 
	                <span class="star_unrated"><i class="fa fa-star xstxt"></i></span>
	                <span class="star_unrated"><i class="fa fa-star xstxt"></i></span>
	                <span class="star_unrated"><i class="fa fa-star xstxt"></i></span>
	                <span class="star_unrated"><i class="fa fa-star xstxt"></i></span>
	                <span class="star_unrated"><i class="fa fa-star xstxt"></i></span>';
	                if($star>0){
		                for ($i=1; $i <=$star ; $i++) {
		                	$star_rate='<span class="star_rated"><i class="fa fa-star xstxt"></i></span>';
		                	if($star>1){
			                	$star_rate.='<span class="star_rated"><i class="fa fa-star xstxt"></i></span>';
			                }else{
			                	$star_rate.='<span class="star_unrated"><i class="fa fa-star xstxt"></i></span>';
			                }
		                	if($star>2){
			                	$star_rate.='<span class="star_rated"><i class="fa fa-star xstxt"></i></span>';
			                }else{
			                	$star_rate.='<span class="star_unrated"><i class="fa fa-star xstxt"></i></span>';
			                }
		                	if($star>3){
			                	$star_rate.='<span class="star_rated"><i class="fa fa-star xstxt"></i></span>';
			                }else{
			                	$star_rate.='<span class="star_unrated"><i class="fa fa-star xstxt"></i></span>';
			                }
		                	if($star>4){
			                	$star_rate.='<span class="star_rated"><i class="fa fa-star xstxt"></i></span>';
			                }else{
			                	$star_rate.='<span class="star_unrated"><i class="fa fa-star xstxt"></i></span>';
			                }
		                }
	                }
	                $replyCounter=0;
	                if($user_id!=""){
	                	$full_name=getUserFullName($user_id);
	                	$avatar=base_url().'media/customer_avatars/'.getUserAvatar($user_id);
	                }
	                if($seller!=""){
	                	$full_name=getSellerBrandName($seller);
	                	$getAvatar=getSellerBrandAvatar($seller);
	                	$avatar=base_url().'media/'.$getAvatar;
	                	if($full_name==""){
	                		$full_name=getSellerFullName($seller);
	                	}
	                	if($getAvatar==""){
	                		$avatar=getSellerAvatar($seller);
	                	}
	                }
	                if($admin!=""){
	                	$adminInfo=getAdminInfo($admin);
	                	$full_name=$adminInfo['name'];
	                	$avt='default.png';
	                	if($adminInfo['avatar']!=""){
	                		$avt=$adminInfo['avatar'];
	                	}
	                	$avatar=base_url().'media/admin_avatars/'.$avt;
	                }

	                $this->db->select('id, comment, user_id, admin_id, seller_id, seller_type, commentedDate');
			        $this->db->where('blog_id', $post);
			        $this->db->where('parent_id', $id);
			        $this->db->where('status', '0');
			        $sql = $this->db->get('tbl_blog_comments');
			        $replyCounter=$sql->num_rows();
			        foreach ($sql->result_array() as $rows) {
		                $reReply=json_decode($rows['comment']);
		                $r_user_id=$rows['user_id'];
		                $r_admin=$rows['admin_id'];
		                $r_seller=$rows['seller_id'];
		                $r_full_name="";
		                if($r_user_id!=""){
		                	$r_full_name=getUserFullName($r_user_id);
		                	$r_avatar=base_url().'media/customer_avatars/'.getUserAvatar($r_user_id);
		                }
		                if($r_seller!=""){
		                	$r_full_name=getSellerBrandName($r_seller);
		                	$r_getAvatar=getSellerBrandAvatar($r_seller);
		                	$r_avatar=base_url().'media/'.$r_getAvatar;
		                	if($r_full_name==""){
		                		$r_full_name=getSellerFullName($r_seller);
		                	}
		                	if($r_getAvatar==""){
		                		$r_avatar=getSellerAvatar($r_seller);
		                	}
		                }
		                if($r_admin!=""){
		                	$adminInfo_r=getAdminInfo($r_admin);
		                	$r_full_name=$adminInfo_r['name'];
		                	$r_avatar=base_url().'media/admin_avatars/'.$adminInfo_r['avatar'];
		                }
		                $replys.='<div class="card card-inner" >
                                        <div class="card-body">
                                            <div class="row commenter_reply">
                                                <div class="col-md-1" style=" margin-top: 2px; margin-bottom: 2px;"></div>
                                                <div class="col-md-1" style=" margin-top: 2px; margin-bottom: 2px;">
                                                    <img src="'.$r_avatar.'"  width="40px" height="40px" class="img-circle img-border"/>
                                                </div>
                                                <div class="col-md-10" >
                                                    <div style="background-color: white; padding: 0px; border-radius: 10px;">
                                                    	<p style="margin-top: 10px;"><a href="javascript:void(0);"><strong>'.ucwords($r_full_name).'</strong></a></p>
                                    					<div class="clearfix"></div>
                                                    	<p><span style="font-weight: 500">'.$reReply.'</span></p>
                                                    </div><hr>
                                                    <span style="font-size: 12px">'.date("M d, Y : H:i", $data['commentedDate']).'</span>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>';
		            }
		            $list.='<div class="row commenter" >
                            <div class="col-md-1 col-xs-2" style="margin-top: 2%; margin-bottom: 2%;">
                                <img src="'.$avatar.'" width="50px" height="50px" class="img-circle"/>
                            </div>
                            <div class="col-md-11 col-xs-10">
                                <div style="background-color: white; margin-top: 2%; padding: 10px; border-radius: 10px;">
                                    <p>
                                        <a class="float-left c_name" href="javascript:void();"><strong>'.ucwords($full_name).'</strong></a>
                                        <span class="stars-container122 stars-80" >'.$star_rate.'</span> ('.$star.')
                                    </p>
                                    <div class="clearfix"></div>
                                    <p style="font-weight: 500;">'.$comment.'</p> 
                                </div>
                                <p>
                                    <a class="float-right btn btn-outline-primary ml-2" href="JavaScript:void(0);" onclick="displayReplyForm(\''.$id.'\');"> <i class="fa fa-reply"></i> Reply</a>
                                    <a class="float-right btn btn-outline-primary ml-2" href=""> <i class="fa fa-comments-o"></i> Replies ('.$replyCounter.')</a>
                                    <span style="font-size: 12px">'.date("M d, Y : H:i", $data['commentedDate']).'</span>
                                </p>
                             </div>
                        </div>';
                    $list.='<form method="POST" id="replyForm'.$id.'" class="replyForm">
		                    	<div class="form-group">
		                    		<label>Comment</label>
		                    		<input type="hidden" id="commented_product'.$id.'" value="'.$post.'">
		                    		<input type="hidden" id="parent_comment'.$id.'" value="'.$id.'">
		                    		<textarea class="form-control" id="commented_comment'.$id.'"></textarea>
		                    	</div>
		                    	<div class="row"><div class="col-md-12" id="resultMsgReply'.$id.'"></div></div>
		                    	<a href="javascript:void();" class="btn btn-sm btn-success turnOnReplyProgress" onclick="sendCommentReply(\''.$id.'\');" id="turnOnProg'.$id.'">Post</a>
                				<a class="btn btn-sm btn-success progressBarReplyBtn" id="progress'.$id.'"><i class="fa fa-spinner fa-spin"></i> Posting...</a>
		                    </form>'.$replys;
	            }
	        }
	        return $list;
	    }

	    public function post_comment($userID, $post, $comment, $rate_counter, $parent, $admin, $seller_id, $seller_type){
			$createdDate=time();
			$this->db->select('id');
	        $this->db->where('blog_id',$post);
	        $this->db->where('comment',$comment);
	        $this->db->where('parent_id',$parent);
	        $this->db->where('star_rating', $rate_counter);
	        $this->db->where('user_id',$userID);
	        $this->db->where('admin_id',$admin);
	        $this->db->where('seller_id',$seller_id);
	        $this->db->where('seller_type',$seller_type);
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_blog_comments');
	        if($query->num_rows() > 0){
	            die(ErrorMsg("Thanks for your comment, it already posted"));
	        }
	        $set=$this->db->insert('tbl_blog_comments', array(
	            'blog_id' => $post,
	            'comment' => $comment,
	            'parent_id' => $parent,
	            'star_rating' => $rate_counter,
	            'user_id' => $userID,
	            'admin_id' => $admin,
	            'seller_id' => $seller_id,
	            'seller_type' => $seller_type,
	            'status' => '0',
	            'commentedDate' => $createdDate
	        ));
	        if($set){
	        	return 'Success';
	        }else{
	        	return ErrorMsg($this->db->_error_message());
	        }
		}

    }
?>