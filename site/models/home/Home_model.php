<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Home_model extends CI_Model{

	    public function __construct(){
	        parent::__construct();
	        $this->load->database();
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

	    function getItemID($product_url){
	        $res="";
	        $this->db->select('id');
	        $this->db->from('tbl_products');
			$this->db->where('product_url', $product_url);
			$query = $this->db->get();

	        if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	                $res=$data['id'];
	            }
	        }
	        return $res;
	    }

	    function isReferralValid($referral_url, $product_url){
	        $res=false;
	        $this->db->select('id');
	        $this->db->where('status', '0');
	        $this->db->where('product', $product_url);
	        $this->db->where('referral_url', $referral_url);
	        $query = $this->db->get('tbl_affiliate_urls');
	        if($query->num_rows() > 0){
	            $res=true;
	        }
	        return $res;
	    }

	    function listShopCategories(){
	    	$list="";
	        $this->db->select('id, name');
	        $this->db->where('status', '0');
	        $query = $this->db->get('tbl_shop_category');
	        if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	                $list.='<li class="p-5"><a href="?category='.$data['id'].'">'.ucwords($data['name']).'</a></li>';
	            }
	        }
	        return $list;
	    }

		public function getSliderProducts(){
			$result=array();
			$this->db->select('id, name, image, summary, product_status, description, price, product_url');
			$this->db->where('in_slider', '1');
		   	$this->db->where('status', '0');
		   	$this->db->order_by('id', 'DESC');
	   		$query = $this->db->get('tbl_products');		   	
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function load_seller_profile($profile_url){
			$result=array();
			$this->db->select('full_name, brand, tbl_seller_info.banner, logo, avatar, gender, email, is_vendor, is_insider, is_outsider, is_contributor');
			$this->db->from('tbl_sellers');
			$this->db->join('tbl_seller_info', 'tbl_seller_info.seller_id=user_id', 'LEFT');
			$this->db->where('profile_url', $profile_url);
		   	$this->db->where('tbl_seller_info.status', '0');
		   	$this->db->where('tbl_sellers.status', '0');
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0)
		   	$result=$query->row_array();
		   	return $result;
		}

		public function load_sellerid_profile($seller_id){
			$result=array();
			$this->db->select('full_name, brand, tbl_seller_info.banner, logo, avatar, gender, email, is_vendor, is_insider, is_outsider, is_contributor, profile_url');
			$this->db->from('tbl_sellers');
			$this->db->join('tbl_seller_info', 'tbl_seller_info.seller_id=user_id', 'LEFT');
			$this->db->where('user_id', $seller_id);
		   	$this->db->where('tbl_seller_info.status', '0');
		   	$this->db->where('tbl_sellers.status', '0');
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0)
		   	$result=$query->row_array();
		   	return $result;
		}

		public function load_seller_socials($seller_id){
			$result=array();
			$this->db->select('name, link');
			$this->db->from('tbl_seller_social');
			$this->db->where('seller_type', 'vendor');
			$this->db->where('seller_id', $seller_id);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0)
		   	$result=$query->result();
		   	return $result;
		}

		public function load_product_details($product_url, $referral){
			$result=array();
			$product_url=$this->db->escape_str($product_url);
			$referral=$this->db->escape_str($referral);
			$this->db->set('views', 'views+1', FALSE);
			$this->db->where('product_url', $product_url);
		   	$this->db->where('status', '0');
	     	$this->db->update('tbl_products');
	     	$referral_url=""; $product="";
    		if($referral!="") {
    			$referral_url=$product_url.'/?ref='.$referral;
    			$product=$this->getItemID($product_url);
    		}

	     	if($referral!="" && $this->isReferralValid($referral_url, $product)){
		     	$referral_url=$product_url.'/?ref='.$referral;
				$this->db->set('views', 'views+1', FALSE);
				$this->db->where('referral_url', $referral_url);
			   	$this->db->where('status', '0');
		     	$this->db->update('tbl_affiliate_urls');
		     }

			$this->db->select('id, name, product_status, image, category, seller_type, seller_id, summary, description, price, createdDate, preview, preview_type, product_url, views');
			$this->db->where('product_url', $product_url);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_products');
		   	if($query->num_rows() > 0)
		   	$result=$query->row_array();
		   	return $result;
		}

		public function load_product_info($product){
			$this->db->select('id, price, product_url');
			$this->db->where('id', $product);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_products');
		   	if($query->num_rows() > 0)
		   	$result=$query->row_array();
		   	return $result;
		}

		public function load_discount_products($limit, $from="", $product="", $random=true){
			$current_date=date("Y-m-d");
			$result=array();
			$this->db->distinct();
			$this->db->select('tbl_products.id, name, image, category, product_status, tbl_products.seller_type, tbl_products.seller_id, summary, description, price, tbl_products.createdDate, product_url, product');
			$this->db->from('tbl_seller_coupons');
			$this->db->join('tbl_products', '(tbl_seller_coupons.product=tbl_products.id AND tbl_products.status="0")');
		   	$this->db->where('tbl_seller_coupons.expire_date >= CURDATE()');
		   	$this->db->where('tbl_seller_coupons.status', '0');
		   	if($product!=""){
			   	$this->db->where('tbl_seller_coupons.product !=', $product);
		   	}
		   	if($random){
		   		$this->db->order_by('RAND()');
		   	}
		   	$this->db->group_by('product');
		   	if($from!="" && $limit!=""){ 
		   		$this->db->limit($limit, $from);
		   	}elseif($limit!=""){
		   		$this->db->limit($limit);
		   	}
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function total_discount_products(){
			$current_date=date("Y-m-d");
			$this->db->distinct();
			$this->db->select('tbl_products.id, name, image, category, product_status, tbl_products.seller_type, tbl_products.seller_id, summary, description, price, tbl_products.createdDate, product_url, product');
			$this->db->from('tbl_seller_coupons');
			$this->db->join('tbl_products', '(tbl_seller_coupons.product=tbl_products.id AND tbl_products.status="0")');
		   	$this->db->where('tbl_seller_coupons.expire_date >= CURDATE()');
		   	$this->db->where('tbl_seller_coupons.status', '0');
		   	$this->db->group_by('product');
	   		$query = $this->db->get();
		   	return $query->num_rows();
		}

		public function load_best_seller_products($limit, $from="", $product=""){
			$result=array();
			$lmt_cond="";
			if($from!="" && $limit!=""){
				$from=$from-1;
		   		$lmt_cond=' LIMIT '.$from.', '.$limit;
		   	}elseif($limit!=""){
		   		$lmt_cond=' LIMIT '.$limit;
		   	}
			$pro_cond="";
			if($product!=""){
				$pro_cond=" AND item_id!='".$product."'";
			}
			$this->db->distinct();
	   		$sql = $this->db->query("SELECT item_id, COUNT(id) as cart_count FROM tbl_cart WHERE status='0' AND (is_complete='0' OR is_complete='1') $pro_cond GROUP BY item_id HAVING COUNT(id)>1 ORDER BY cart_count DESC $lmt_cond");
	   		if($sql->num_rows() > 0){
	            foreach ($sql->result_array() as $data) {
	                $item_id=$data['item_id'];
	                $this->db->select('id, name, image, category, product_status, seller_type, seller_id, summary, description, price, createdDate, product_url');
					$this->db->where('id', $item_id);
				   	$this->db->where('status', '0');
			   		$query = $this->db->get('tbl_products');
			   		if($query->num_rows() > 0){
			   			array_push($result, $query->row_array());
			   		}
	            }
	        }
		   	return $result;
		}

		public function total_best_seller_products(){
			$this->db->distinct();
	   		$sql = $this->db->query("SELECT item_id, COUNT(id) as cart_count FROM tbl_cart WHERE status='0' AND (is_complete='0' OR is_complete='1') GROUP BY item_id HAVING COUNT(id)>1 ORDER BY cart_count DESC");
	   		return $sql->num_rows();
		}

		public function load_old_gold_products($limit, $from="", $product=""){
			$result=array();
			$lmt_cond="";
			if($from!="" && $limit!=""){
				$from=$from-1;
		   		$lmt_cond=' LIMIT '.$from.', '.$limit;
		   	}elseif($limit!=""){
		   		$lmt_cond=' LIMIT '.$limit;
		   	}
			$pro_cond="";
			if($product!=""){
				$pro_cond=" AND id!='".$product."'";
			}
	   		$sql = $this->db->query("SELECT id, name, image, category, product_status, seller_type, seller_id, summary, description, price, createdDate, product_url FROM tbl_products WHERE status='0' $pro_cond AND id NOT IN (SELECT item_id FROM tbl_cart WHERE status='0' AND (is_complete='0' OR is_complete='1')) ORDER BY RAND() $lmt_cond");
	   		if($sql->num_rows() > 0){
	            $result=$sql->result();
	        }
		   	return $result;
		}

		public function total_old_gold_products(){
	   		$sql = $this->db->query("SELECT id, name, image, category, product_status, seller_type, seller_id, summary, description, price, createdDate, product_url FROM tbl_products WHERE status='0' AND id NOT IN (SELECT item_id FROM tbl_cart WHERE status='0' AND (is_complete='0' OR is_complete='1'))");
	   		return $sql->num_rows();
		}

		public function load_related_products($product_url){
			$result=array();
			$category="";
			$this->db->select('category');
			$this->db->where('product_url', $product_url);
		   	$this->db->where('status', '0');
	   		$sql = $this->db->get('tbl_products');
	   		if($sql->num_rows() > 0){
	            foreach ($sql->result_array() as $data) {
	                $category=$data['category'];
	            }
	        }

			$this->db->select('id, name, image, category, product_status, seller_type, seller_id, summary, description, price, createdDate, product_url');
			$this->db->where('category', $category);
			$this->db->where('product_url !=', $product_url);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_products');
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function load_popular_products($limit=3, $from="", $product=""){
			$result=array();
			$this->db->select('id, name, image, category, product_status, seller_type, seller_id, summary, description, price, createdDate, product_url');
		   	$this->db->where('status', '0');
		   	$this->db->where('views > 2');
		   	if($product!=""){
		   		$this->db->where('id !=', $product);
		   	}
		   	$this->db->order_by('views','DESC');
        	if($from!="" && $limit!=""){ 
		   		$this->db->limit($limit, $from);
		   	}elseif($limit!=""){
		   		$this->db->limit($limit);
		   	}
	   		$query = $this->db->get('tbl_products');
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function total_popular_products(){
			$this->db->select('id, name, image, category, product_status, seller_type, seller_id, summary, description, price, createdDate, product_url');
		   	$this->db->where('status', '0');
		   	$this->db->where('views > 2');
		   	$this->db->order_by('views','DESC');
	   		$query = $this->db->get('tbl_products');
		   	return $query->num_rows();
		}

		public function search_products_results($keyword, $category){
			$result=array();
			if($keyword!=""){
				$this->db->select('id, name, image, category, product_status, seller_type, seller_id, summary, description, price, createdDate, product_url');
			   	$this->db->where('status', '0');
			   	if($category!=""){
			   		$this->db->where('category', $this->db->escape($category));
			   	}
			   	// $this->db->like('name', $keyword);
			   	$this->db->where("name LIKE '%".$this->db->escape_like_str($keyword)."%'");
			   	$this->db->order_by('createdDate','DESC');
		   		$query = $this->db->get('tbl_products');
			   	if($query->num_rows() > 0)
			   	$result = $query->result();
			}
		   	return $result;
		}

		public function search_post_results($keyword, $category){
			$result=array();
			if($keyword!=""){
				$this->db->select('tbl_blog_post.id, title, image, tbl_blog_category.category, postedDate, url, views');
				$this->db->from('tbl_blog_post');
				$this->db->join('tbl_blog_category', 'tbl_blog_category.id=tbl_blog_post.category');
			   	$this->db->where('tbl_blog_post.status', '0');
			   	$this->db->where('tbl_blog_post.is_approved', '0');
			   	$this->db->where('tbl_blog_category.status', '0');
			   	if($category!=""){
			   		$this->db->where('tbl_blog_post.category', $category);
			   	}
			   	// $this->db->like('title', $keyword);
			   	$this->db->where("title LIKE '%".$keyword."%'");
			   	// $this->db->like('title', $keyword, 'after');
			   	$this->db->order_by('postedDate', 'DESC');	   	
			   	
		   		$query = $this->db->get();		   	
			   	if($query->num_rows() > 0)
			   	$result = $query->result();
			}
		   	return $result;
		}

		public function load_seller_info($product_url){
			$result=array();
			$seller_id="";
			$seller_type="";
			$this->db->select('seller_id, seller_type');
			$this->db->where('product_url', $product_url);
		   	$this->db->where('status', '0');
	   		$sql = $this->db->get('tbl_products');
	   		if($sql->num_rows() > 0){
	            foreach ($sql->result_array() as $data) {
	                $seller_id=$data['seller_id'];
	                $seller_type=$data['seller_type'];
	            }
	        }

			$this->db->select('user_id, full_name, phone, email, address, brand');
			$this->db->from('tbl_sellers');
			$this->db->join('tbl_seller_info', 'user_id = seller_id');
			$this->db->where('user_id', $seller_id);
			$this->db->where('seller_type', $seller_type);
		   	$this->db->where('tbl_sellers.status', '0');
		   	$this->db->where('tbl_seller_info.status', '0');
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0)
		   	$result=$query->row_array();
		   	return $result;
		}

		public function load_seller_products($seller, $category="", $from="", $limit="", $product=""){
			$result=array();
			$this->db->select('id, name, image, category, product_status, seller_id, price, product_url');
		   	$this->db->where('status', '0');
		   	if($category!=""){
			   	$this->db->where('category', $category);
			}
		   	$this->db->where('seller_id', $seller);
		   	if($product!=""){
		   		$this->db->where('id !=', $product);
		   	}
		   	$this->db->order_by('createdDate', 'DESC');
		   	if($from!="" && $limit!=""){ 
		   		$this->db->limit($limit, $from);
		   	}elseif($limit!=""){
		   		$this->db->limit($limit);
		   	}
	   		$query = $this->db->get('tbl_products');
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function total_seller_products($seller, $category=""){
			$this->db->select('id, name, image, category, product_status, seller_id, price, product_url');
		   	$this->db->where('status', '0');
		   	if($category!=""){
			   	$this->db->where('category', $category);
			}
		   	$this->db->where('seller_id', $seller);
		   	$this->db->order_by('createdDate', 'DESC');
	   		$query = $this->db->get('tbl_products');
		   	return $query->num_rows();
		}

		public function load_featured_ins_cont(){
			$result=array();
			$this->db->select('tbl_sellers.user_id, avatar, full_name, is_insider, is_contributor');
			$this->db->from('tbl_sellers');
			$this->db->join('tbl_users', 'tbl_users.id=user_id AND tbl_users.status="0"');
		   	$this->db->where('tbl_sellers.status', '0');
		   	$this->db->where('is_insider!="" OR is_contributor!=""');
		   	$this->db->order_by('tbl_sellers.id', 'DESC');	   	
		   	$this->db->limit(4);
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function loadProducts($category, $brand, $time, $initialPrice, $finalPrice, $from="", $limit="", $random=true){
			$cat=$this->getCategoryID($category);
			$result=array();
			$this->db->select('id, name, image, category, summary, product_status, description, seller_id, price, product_url');
			$this->db->where('category', $cat);
		   	$this->db->where('status', '0');
		   	if($brand!=""){ $this->db->where('brand', $brand);}
		   	if($time==""){ $this->db->order_by('createdDate', 'DESC');}
		   	if($time!="" && $time=='old'){ $this->db->order_by('createdDate', 'ASC');}
		   	if($time!="" && $time=='new'){ $this->db->order_by('createdDate', 'DESC');}
		   	if($initialPrice>0 && $finalPrice>0){ 
		   		$this->db->where('price BETWEEN  '.$initialPrice.' AND '.$finalPrice);
		   	}else{
		   		if($initialPrice!=""){ $this->db->where('price >=', $initialPrice);}		   	
		   		if($finalPrice!=""){ $this->db->where('price <=', $finalPrice);}
		   	}		   	
		   	if($from!="" && $limit!=""){ 
		   		$this->db->limit($limit, $from);
		   	}elseif($limit!=""){
		   		$this->db->limit($limit);
		   	}	   	
		   	if($random){ $this->db->order_by('RAND()');}
	   		$query = $this->db->get('tbl_products');		   	
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function getTotalProducts($category, $brand, $time, $initialPrice, $finalPrice, $limit=""){
			$cat=$this->getCategoryID($category);
			$this->db->select('id, name, image, category, summary, product_status, description, seller_id, price, product_url');
			$this->db->where('category', $cat);
		   	$this->db->where('status', '0');
		   	if($brand!=""){ $this->db->where('brand', $brand);}
		   	if($time==""){ $this->db->order_by('createdDate', 'DESC');}
		   	if($time!="" && $time=='old'){ $this->db->order_by('createdDate', 'ASC');}
		   	if($time!="" && $time=='new'){ $this->db->order_by('createdDate', 'DESC');}
		   	if($initialPrice!=""){ $this->db->where('price >=', $initialPrice);}   	
		   	if($finalPrice!=""){ $this->db->where('price <=', $finalPrice);}		   	
	   		$query = $this->db->get('tbl_products');		   	
		   	return $query->num_rows();
		}

		function load_cart_items($userID){
        	$listArray=array();
			$list=json_encode($listArray);
	        $this->db->select('tbl_cart.id, quantity, item_id');
	        $this->db->from('tbl_cart');
			$this->db->join('tbl_orders', 'tbl_orders.orderID = tbl_cart.orderID');
	        $this->db->where('tbl_cart.user_id',$userID);
	        $where="tbl_cart.is_complete='2'";
	        $this->db->where($where);
	        $this->db->where('tbl_cart.status', '0');
	        $this->db->where('tbl_orders.status', '0');
	        $query = $this->db->get();

		   	if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	                $id=$data['id'];
	                $item_id=$data['item_id'];
	                $quantity=$data['quantity'];
	                $this->db->select('name, image, price, product_url');
			        $this->db->where('id', $item_id);
			        $this->db->where('status', '0');
			        $sql = $this->db->get('tbl_products');
			        foreach ($sql->result_array() as $rows) {
				        $row_array['id']=$id;
		                $row_array['image']=$rows['image'];
		                $row_array['product_url']=$rows['product_url'];
		                $row_array['name']=$rows['name'];
		                $row_array['price']=$rows['price'];
		                $row_array['quantity']=$quantity;
		                array_push($listArray, $row_array);
		            }
	            }
	            $list=json_encode($listArray);
	        }
	        return $list;
	    }

	    public function check_new_message($userID){
			$res=0;
			$this->db->select('tbl_msg_readers.id');
			$this->db->from('tbl_msg_readers');
		   	$this->db->where('user_id', $userID);
		   	$this->db->where('is_read', '1');
		   	$this->db->where('status', '0');		   	
	   		$sql = $this->db->get();		   	
		   	$res=$sql->num_rows();
		   	return $res;
		}

	    public function save_subscription($email){
			$createdDate=time();
			$this->db->select('id');
	        $this->db->where('email',$email);
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_subscribers');
	        if($query->num_rows() > 0){
	            die("Thanks for your subscription");
	        }
	        $set=$this->db->insert('tbl_subscribers', array(
	            'email' => $email,
	            'status' => '0',
	            'time' => $createdDate
	        ));
	        if($set){
	        	return 'Success';
	        }else{
	        	return ErrorMsg($this->db->_error_message());
	        }
		}
		
		public function get_countries(){
		    
		    $this->db->select(['id','name','code']);
            $country_query = $this->db->get('tbl_country');
            
            $countries = [];
            
            if($country_query->num_rows() > 0){
                foreach ($country_query->result_array() as $data) {
                    $countries[]= $data;
                }
            }
            
            return $countries;
		}
    }
?>