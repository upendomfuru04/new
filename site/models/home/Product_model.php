<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Product_model extends CI_Model{

	    public function __construct(){
	        parent::__construct();
	        $this->load->database();
    	}

    	function loadBrands($brand="", $category=""){
	        $list="";
	        /*$this->db->select('id');
	        $this->db->select('brand');
	        if($category!=""){
	            $this->db->where('category', $category);
	        }
	        $this->db->where('status', '0');
	        $query = $this->db->get('tbl_brands');
	        if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	                if($brand==$data['id']){
	                    $list.='<option value="'.$data['id'].'" selected>'.ucwords($data['brand']).'</option>';
	                }else{
	                    $list.='<option value="'.$data['id'].'">'.ucwords($data['brand']).'</option>';
	                }
	            }
	        }*/
	        $this->db->distinct();
	        $this->db->select('brand');
	        $this->db->from('tbl_products');
	        $this->db->join('tbl_shop_category', '(tbl_shop_category.id=tbl_products.category AND tbl_shop_category.status="0")');
	        $this->db->where('tbl_shop_category.name', $category);
	        $this->db->where('tbl_products.status', '0');
	        $this->db->group_by('brand');
	        $query = $this->db->get();
	        if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	                if($brand==$data['brand']){
	                    $list.='<option value="'.$data['brand'].'" selected>'.ucwords($data['brand']).'</option>';
	                }else{
	                    $list.='<option value="'.$data['brand'].'">'.ucwords($data['brand']).'</option>';
	                }
	            }
	        }
	        return $list;
	    }

	    function getTotalProductSales($product){
	        $this->db->select('id');
	        $this->db->from('tbl_cart');
	        $this->db->where('item_id', $product);
	        $this->db->where('(is_complete="0" OR is_complete="1")');
	        $this->db->where('status', '0');
	        $query = $this->db->get();
	        return $query->num_rows();
	    }

    	function getCategoryID($category){
	        $id="";
	        $this->db->select('id');
	        $this->db->where('name',$category);
	        $query = $this->db->get('tbl_shop_category');

	        if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	                $id=$data['id'];
	            }
	        }
	        return $id;
	    }

		function load_user_reviews($product){
			$list="";
			$avatar="";
        	$listArray=array();
	        $this->db->select('id, review, star, parent_id, user_id, admin_id, seller_id, seller_type, createdDate');
	        $this->db->where('product', $product);
	        $this->db->where('parent_id', '');
	        $this->db->where('status', '0');
	        $query = $this->db->get('tbl_product_reviews');

		   	if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	                $id=$data['id'];
	                // $review=json_decode($data['review']);
	                $review=decodeEmoticons($data['review']);
	                $star=$data['star'];
	                $user_id=$data['user_id'];
	                $admin=$data['admin_id'];
	                $seller=$data['seller_id'];
	                $full_name=""; $replys="";
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
	                	$avatar=base_url().'media/'.getSellerBrandAvatar($seller);
	                }
	                if($admin!=""){
	                	$full_name=getAdminFullName($admin);
	                	$avatar=base_url().'media/admin_avatars/'.getAdminAvatar($admin);
	                }

	                $this->db->select('id, review, user_id, admin_id, seller_id, seller_type, createdDate');
			        $this->db->where('product', $product);
			        $this->db->where('parent_id', $id);
			        $this->db->where('status', '0');
			        $sql = $this->db->get('tbl_product_reviews');
			        $replyCounter=$sql->num_rows();
			        foreach ($sql->result_array() as $rows) {
		                $reReply=json_decode($rows['review']);
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
		                	$r_avatar=base_url().'media/'.getSellerBrandAvatar($r_seller);
		                }
		                if($r_admin!=""){
		                	$r_full_name=getAdminFullName($r_admin);
		                	$r_avatar=base_url().'media/admin_avatars/'.getAdminAvatar($r_admin);
		                }
		                $replys.='<div class="card card-inner" >
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-1" style=" margin-top: 2px; margin-bottom: 2px;"></div>
                                                <div class="col-md-1 col-xs-2" style=" margin-top: 2px; margin-bottom: 2px;">
                                                    <img src="'.$r_avatar.'"  width="40px" height="40px" class="img-circle img-border"/>
                                                </div>
                                                <div class="col-md-10 col-xs-10" >
                                                    <div style="background-color: white; padding: 0px; border-radius: 10px;">
                                                    	<p style="margin-top: 10px;"><a href="javascript:void(0);"><strong>'.$r_full_name.'</strong></a></p>
                                    					<div class="clearfix"></div>
                                                    	<p><span style="font-weight: 500">'.$reReply.'</span></p>
                                                    </div><hr>
                                                    <span style="font-size: 12px">'.date("M d, Y : H:i", $data['createdDate']).'</span>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>';
		            }
		            if($avatar!=""){
			            $list.='<div class="row" >
	                            <div class="col-md-1 col-xs-2" style="margin-top: 2%; margin-bottom: 2%;">
	                                <img src="'.$avatar.'" width="50px" height="50px" class="img-circle"/>
	                            </div>
	                            <div class="col-md-11 col-xs-10">
	                                <div style="background-color: white; margin-top: 2%; padding: 10px; border-radius: 10px;">
	                                    <p>
	                                        <a class="float-left" href="#"><strong>'.$full_name.'</strong></a>
	                                        <span class="stars-containerXX stars-80" >'.$star_rate.'</span> ('.$star.')
	                                    </p>
	                                    <div class="clearfix"></div>
	                                    <p style="font-weight: 500;">'.$review.'</p> 
	                                </div>
	                                <p>
	                                    <a class="float-right btn btn-outline-primary ml-2" href="JavaScript:void(0);" onclick="displayReplyForm(\''.$id.'\');"> <i class="fa fa-reply"></i> Reply</a>
	                                    <a class="float-right btn btn-outline-primary ml-2" href=""> <i class="fa fa-comments-o"></i> Replies ('.$replyCounter.')</a>
	                                    <span style="font-size: 12px">'.date("M d, Y : H:i", $data['createdDate']).'</span>
	                                </p>
	                             </div>
	                        </div>';
	                    $list.='<form method="POST" id="replyForm'.$id.'" class="replyForm">
			                    	<div class="form-group">
			                    		<label>Comment</label>
			                    		<input type="hidden" id="reviewed_product'.$id.'" value="'.$product.'">
			                    		<input type="hidden" id="parent_review'.$id.'" value="'.$id.'">
			                    		<textarea class="form-control" id="reviewed_review'.$id.'"></textarea>
			                    	</div>
			                    	<div class="row"><div class="col-md-12" id="resultMsgReply'.$id.'"></div></div>
			                    	<a href="javascript:void();" class="btn btn-sm btn-success turnOnReplyProgress" onclick="sendReviewReply(\''.$id.'\');" id="turnOnProg'.$id.'">Post</a>
	                				<a class="btn btn-sm btn-success progressBarReplyBtn" id="progress'.$id.'"><i class="fa fa-spinner fa-spin"></i> Posting...</a>
			                    </form>'.$replys;
		            }
	            }
	        }
	        return $list;
	    }

	    public function post_review($userID, $product, $review, $rate_counter, $parent, $admin, $seller_id, $seller_type){
			$createdDate=time();

			$this->db->select('id');
	        $this->db->where('product',$product);
	        $this->db->where('review',$review);
	        $this->db->where('parent_id',$parent);
	        $this->db->where('user_id',$userID);
	        $this->db->where('admin_id',$admin);
	        $this->db->where('seller_id',$seller_id);
	        $this->db->where('seller_type',$seller_type);
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_product_reviews');

	        if($query->num_rows() > 0){
	            die(ErrorMsg("Thanks for your review, it already posted"));
	        }

	        $set=$this->db->insert('tbl_product_reviews', array(
	            'product' => $product,
	            'review' => $review,
	            'star' => $rate_counter,
	            'parent_id' => $parent,
	            'user_id' => $userID,
	            'admin_id' => $admin,
	            'seller_id' => $seller_id,
	            'seller_type' => $seller_type,
	            'status' => '0',
	            'createdDate' => $createdDate
	        ));

	        if($set){
	        	return 'Success';
	        }else{
	        	return ErrorMsg($this->db->_error_message());
	        }
		}

    }
?>