<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Api_model extends CI_Model{

	    public function __construct(){
	        parent::__construct();
	        $this->load->database();
        	$this->userToken=$this->session->userdata('getvalue_user_idetification');
    	}

    	public function login($username, $password, $old_password){
		    $this->db->select('status, password'); 
		    $this->db->where('username', $username);
		   	$query = $this->db->get('tbl_users');
		   	if($query->num_rows() > 0){
		   		$user_row=$query->row_array();
		   		if(password_verify($password, $user_row['password']) || $user_row['password']==$old_password){
		            foreach ($query->result_array() as $data) {
		                if($data['status']=='0'){
			            	return "Success";
			            }elseif($data['status']=='2'){
			            	return 'Account blocked';
			            }elseif($data['status']=='3'){
			            	return 'Account is not active';
			            }
		            }
		        }else{
			   		return 'Incorrect username or password';
			 	}
		   	}else{
		   		return 'Incorrect username or password';
		 	}
		}

    	public function load_user_info($username, $password, $old_password){
    		$result=array();
			$res=array();
		    $this->db->select('first_name, surname, gender, phone, email, avatar, password'); 
		    $this->db->from('tbl_users'); 
		    $this->db->join('tbl_user_info', 'tbl_users.id=user_id'); 
		    $this->db->where('username', $username); 
		   	// $this->db->where('password', $password);
		   	$query = $this->db->get();
		   	if($query->num_rows() > 0){
		   		$user_row=$query->row_array();
		   		if(password_verify($password, $user_row['password']) || $user_row['password']==$old_password){
		            foreach ($query->result_array() as $data) {
		            	$res['first_name']=$data['first_name'];
		                $res['surname']=$data['surname'];
		                $res['gender']=$data['gender'];
		                $res['phone']=$data['phone'];
		                $res['email']=$data['email'];
		                $res['avatar']=$data['avatar'];
		                array_push($result, $res);
		            }
	            }
	        }
	        return (object)$res;
		}

		public function orders($email){
			$result=array();
			$res=array();
			$this->db->select('tbl_cart.id as cart_id, tbl_orders.orderID, tbl_products.name, product_url, category, image, profile_url, full_name, tbl_shop_category.name as category, media, tbl_products.description, avatar, logo');
			$this->db->from('tbl_orders');
			$this->db->join('tbl_cart', '(tbl_cart.orderID=tbl_orders.orderID AND tbl_cart.status="0" AND tbl_cart.is_complete="0")');
			$this->db->join('tbl_products', '(tbl_products.id=tbl_cart.item_id AND tbl_products.status="0")');
			$this->db->join('tbl_shop_category', 'tbl_shop_category.id=tbl_products.category');
			$this->db->join('tbl_users', 'tbl_users.id=tbl_orders.user_id');
			$this->db->join('tbl_sellers', '(tbl_sellers.user_id=tbl_products.seller_id AND tbl_sellers.status="0")', 'LEFT');
			$this->db->join('tbl_seller_info', '(tbl_seller_info.seller_id=tbl_sellers.user_id AND tbl_seller_info.status="0" AND tbl_seller_info.seller_type="vendor")', 'LEFT');
		   	$this->db->where('username', $email);
		   	$this->db->where('tbl_orders.status', '0');
		   	$this->db->where('tbl_orders.is_complete', '0');
		   	$this->db->order_by('tbl_orders.createdDate', 'DESC');
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0){
		   		$download_token=generateToken();
		   		$this->db->set('user_download_token', $download_token);
				$this->db->where('username', $email);
				$this->db->where('status', '0');
		     	$this->db->update('tbl_users');
	            foreach ($query->result_array() as $data) {
	            	$product_link=""; $purchase_link=""; $product_cover=""; $owner_profile=""; $product_owner="";
	            	if($data['media']){
	            		$mdia=explode(";", $data['media']);
						for ($i=0; $i < sizeof($mdia); $i++) {
							if($mdia[$i]!=""){
								// $product_link.=base_url().'products/medias/'.trim($mdia[$i]).'; ';
								$product_link.=base_url().'api_downloads/medias/'.trim($mdia[$i]).'?dlt='.$download_token.'; ';
							}
						}
	            	}
	            	if($data['product_url'])
	            		$purchase_link=base_url().'home/product_details/'.$data['product_url'];
	            	if($data['image']!="")
	            		$product_cover=base_url().'media/products/'.$data['image'];
	            	/*if($data['profile_url']!="")
	            		$owner_profile=base_url().'home/seller_profile/'.$data['profile_url'];*/
	            	if($data['avatar']!=""){
	            		$owner_profile=base_url().'media/seller_avatars/'.$data['avatar'];
	            	}else{
	            		$owner_profile=base_url().'media/shop/logo/'.$data['logo'];
	            	}
	            	if($data['full_name']!="")
	            		$product_owner=$data['full_name'];
	                $res['orderID']=$data['cart_id'];
	                $res['order_ID']=$data['orderID'];
	                $res['user_email']=$email;
	                $res['product_title']=$data['name'];
	               // $res['product_synopsis']=$data['description'];
	                $res['product_synopsis']=preg_replace('#(<br */?>\s*)+#i', '<br />',stripcslashes(htmlspecialchars_decode(strip_tags($data['description']))));
	                $res['product_cover']=$product_cover;
	                $res['product_category']=$data['category'];
	                $res['product_link']=$product_link;
	                $res['product_owner']=$product_owner;
	                $res['owner_profile']=$owner_profile;
	                $res['purchase_link']=$purchase_link;
	                array_push($result, $res);
	            }
	            return $result;
		   	}else{
		   		return 'No any order';
		 	}
		}

    }
?>