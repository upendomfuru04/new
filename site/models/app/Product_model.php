<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Product_model extends CI_Model{

	    public function __construct(){
	        parent::__construct();
	        $this->load->database();
    	}

		public function load_product_categories(){
		   	$result=array();
		    $this->db->select('id, name, category_icon'); 
		    $this->db->from('tbl_shop_category'); 
		   	$this->db->where('status', '0');
		   	$query = $this->db->get();
		   	if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	            	$res=array();
	            	$res['category_id']=$data['id'];
	            	$res['category_name']=$data['name'];
	            	$res['icon']=$data['category_icon'];
	                $res['total_products']=$this->getTotalCategoryProducts($data['id']);
	                array_push($result, $res);
	            }
	        }
	        return json_encode($result);
		}

		public function getTotalCategoryProducts($category){
			$this->db->select('id');
			$this->db->where('category', $category);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_products');	   	
		   	return $query->num_rows();
		}

		public function getSliderProducts(){
			$result=array();
			$this->db->select('id, name, image, summary, product_status, price, product_url, seller_id');
			$this->db->where('in_slider', '1');
		   	$this->db->where('status', '0');
		   	$this->db->limit(9);
	   		$query = $this->db->get('tbl_products');
	   		if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	            	$res=array();
	            	$res['product_id']=$data['id'];
	            	$res['product_name']=$data['name'];
	            	$res['image']=base_url('media/products/').$data['image'];
	            	$res['price']=numberFormat($data['price'])." TZS";
	            	$res['summary']=htmlspecialchars($data['summary'], ENT_QUOTES);
	            	$stars=getProductRateValue($data['id']);
	            	$res['rate']=$stars;
	                $res['seller']=getSellerBrandName($data['seller_id']);
	                array_push($result, $res);
	            }
	        }
		   	return json_encode($result);
		}

		public function loadProducts($category, $brand, $time, $initialPrice, $finalPrice, $limit){
			$cat="";
			if($category!=""){
				$cat=getCategoryID($category);
			}
			$result=array();
			$this->db->select('id, name, image, category, summary, product_status, seller_id, price, product_url, views');
			if($cat!=""){
				$this->db->where('category', $cat);
			}
		   	$this->db->where('status', '0');
		   	if($brand!=""){ $this->db->where('brand', $brand);}
		   	if($time==""){ $this->db->order_by('createdDate', 'DESC');}
		   	if($time!="" && $time=='old'){ $this->db->order_by('createdDate', 'ASC');}
		   	if($time!="" && $time=='new'){ $this->db->order_by('createdDate', 'DESC');}
		   	if($initialPrice!=""){ $this->db->where('price >=', $initialPrice);}		   	
		   	if($finalPrice!=""){ $this->db->where('price <=', $finalPrice);}
		   	if($limit>0)	   	
		   		$this->db->limit($limit);
	   		$query = $this->db->get('tbl_products');		   	
		   	if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	            	$res=array();
	            	$res['product_id']=$data['id'];
	            	$res['product_name']=$data['name'];
	            	$res['image']=base_url('media/products/').$data['image'];
	            	$res['price']=numberFormat($data['price'])." TZS";
	            	$res['summary']=htmlspecialchars($data['summary'], ENT_QUOTES);
	            	$res['total_views']=$data['views'];
	            	$stars=getProductRateValue($data['id']);
	            	$res['rate']=$stars;
	                $res['seller']=getSellerBrandName($data['seller_id']);
	                array_push($result, $res);
	            }
	        }
		   	return json_encode($result);
		}

		public function load_popular_products($limit=3){
			$result=array();
			$this->db->select('id, name, image, category, product_status, seller_type, seller_id, summary, price, createdDate, product_url');
		   	$this->db->where('status', '0');
		   	$this->db->order_by('views','DESC');
        	$this->db->limit($limit);
	   		$query = $this->db->get('tbl_products');
		   	if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	            	$res=array();
	            	$res['product_id']=$data['id'];
	            	$res['product_name']=$data['name'];
	            	$res['image']=base_url('media/products/').$data['image'];
	            	$res['price']=numberFormat($data['price'])." TZS";
	            	$res['summary']=htmlspecialchars($data['summary'], ENT_QUOTES);
	            	$stars=getProductRateValue($data['id']);
	            	$res['rate']=$stars;
	                $res['seller']=getSellerBrandName($data['seller_id']);
	                array_push($result, $res);
	            }
	        }
		   	return json_encode($result);
		}

		public function load_seller_products($seller_id){
			$result=array();
			$this->db->select('id, name, image, category, product_status, seller_id, price, product_url, summary, views');
		   	$this->db->where('status', '0');
		   	$this->db->where('seller_id', $seller_id);
		   	$this->db->order_by('createdDate', 'DESC');
	   		$query = $this->db->get('tbl_products');
		   	if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	            	$res=array();
	            	$res['product_id']=$data['id'];
	            	$res['product_name']=$data['name'];
	            	$res['image']=base_url('media/products/').$data['image'];
	            	$res['price']=numberFormat($data['price'])." TZS";
	            	$res['summary']=htmlspecialchars($data['summary'], ENT_QUOTES);
	            	$res['total_views']=$data['views'];
	            	$stars=getProductRateValue($data['id']);
	            	$res['rate']=$stars;
	                $res['seller']=getSellerBrandName($data['seller_id']);
	                array_push($result, $res);
	            }
	        }
		   	return json_encode($result);
		}

		public function load_product_details($product_id, $user_id){
			$result=array();
			$this->db->set('views', 'views+1', FALSE);
			$this->db->where('id', $product_id);
		   	$this->db->where('status', '0');
	     	$this->db->update('tbl_products');

			$this->db->select('id, name, product_status, image, category, seller_type, seller_id, summary, description, price, createdDate, preview, preview_type, product_url');
			$this->db->where('id', $product_id);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_products');
		   	if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	            	$res=array();
	            	$res['product_id']=$data['id'];
	            	$res['product_name']=$data['name'];
	            	$res['image']=base_url('media/products/').$data['image'];
	            	$res['preview']=$data['preview'];
	            	$res['preview_type']=$data['preview_type'];
	            	$res['price']=numberFormat($data['price'])." TZS";
	            	$res['summary']=htmlspecialchars($data['summary'], ENT_QUOTES);
	           // 	$res['product_description']=htmlspecialchars($data['description']);
	            	$res['product_description']=htmlspecialchars_decode(stripslashes(strip_tags($data['description'])));
	            	$stars=getProductRateValueList($data['id']);
	            	$res['rate']=$stars;
	            	$res['category_name']=ucwords(getCategoryName($data['category']));
	            	$res['number_in_cart']=getItemCartCounter($user_id, $data['id']);
	            	$res['created_date']=date("M d, Y", $data['createdDate']);
	                array_push($result, $res);
	            }
	        }
		   	return $result;
		}

		public function load_seller_info($product_id){
			$result=array();
			$seller_id="";
			$seller_type="";
			$this->db->select('seller_id, seller_type');
			$this->db->where('id', $product_id);
		   	$this->db->where('status', '0');
	   		$sql = $this->db->get('tbl_products');
	   		if($sql->num_rows() > 0){
	            foreach ($sql->result_array() as $rows) {
	                $seller_id=$rows['seller_id'];
	                $seller_type=$rows['seller_type'];
	            }
	        }

			$this->db->select('user_id, full_name, phone, email, address, brand');
			$this->db->from('tbl_sellers');
			$this->db->join('tbl_seller_info', 'user_id = seller_id AND seller_type="$seller_type" AND tbl_seller_info.status="0"', 'LEFT');
			$this->db->where('user_id', $seller_id);
		   	$this->db->where('tbl_sellers.status', '0');
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	            	$res=array();
	            	$res['full_name']=$data['full_name'];
	            	$res['phone']=$data['phone'];
	            	$res['email']=$data['email'];
	            	$res['address']=$data['address'];
	            	$res['brand']=$data['brand'];
	                array_push($result, $res);
	            }
	        }
		   	return $result;
		}

		function load_product_reviews($product){
			$list="";
			$avatar="";
        	$result=array();
	        $this->db->select('id, review, star, parent_id, user_id, admin_id, seller_id, seller_type, createdDate');
	        $this->db->where('product', $product);
	        $this->db->where('parent_id', '');
	        $this->db->where('status', '0');
	        $query = $this->db->get('tbl_product_reviews');

		   	if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	            	$res=array();
	                $total_replies=0;
	                $childrens=array();
	                $sender_name="";
	                $avatar="";
	                $parent_id=$data['id'];
	                $rates=$data['star'];
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
	                // $res['msg']=json_encode($data['review']);
	                $rvw=ltrim($data['review'], '"');
	                $rvw=rtrim($rvw, '"');
	                $res['msg']=decodeEmoticons($rvw);
	                $res['send_date']=date('M d, Y H:i', $data['createdDate']);
	                

	                $this->db->select('id, review, user_id, admin_id, seller_id, seller_type, createdDate');
			        $this->db->where('product', $product);
			        $this->db->where('parent_id', $parent_id);
			        $this->db->where('status', '0');
			        $sql = $this->db->get('tbl_product_reviews');
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
				        	$rvw1=ltrim($rows['review'], '"');
	                		$rvw1=rtrim($rvw1, '"');
				        	$res1['msg']=decodeEmoticons($rvw1);
				        	$res1['send_date']=date('M d, Y H:i', $rows['createdDate']);;
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

	    public function post_review($userID, $product, $review, $rate_counter, $parent){
			$createdDate=time();
			$this->db->select('id');
	        $this->db->where('product', $product);
	        $this->db->where('review', $review);
	        $this->db->where('parent_id', $parent);
	        $this->db->where('user_id', $userID);
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_product_reviews');

	        if($query->num_rows() > 0){
	            return "Thanks for your review, it already posted";
	        }else{
		        $set=$this->db->insert('tbl_product_reviews', array(
		            'product' => $product,
		            'review' => $review,
		            'star' => $rate_counter,
		            'parent_id' => $parent,
		            'user_id' => $userID,
		            'status' => '0',
		            'createdDate' => $createdDate
		        ));

		        if($set){
		        	return 'Success';
		        }else{
		        	return "Operation Failed... Try again!";
		        }
	        }
		}

		public function add_product_cart($userID, $product){
    		$createdDate=time();
    		$ordID=getLatestOrderID($userID);
    		$commission=""; $sellerCommission=""; $seller_id="";
    		$referral_url=""; $price="";
    		if($ordID!=""){
    			$orderID=$ordID;
    		}else{
    			$order_ID=generateOrderID();
    			$this->db->insert('tbl_orders', array(
		            'orderID' => $order_ID,
		            'user_id' => $userID,
		            'is_complete' => '2',
		            'status' => '0',
		            'createdDate' => $createdDate
		        ));
		        $orderID=$order_ID;
    		}

    		$this->db->select('price');
	        $this->db->where('id', $product);
	        $this->db->where('status','0');
	        $sqlP = $this->db->get('tbl_products');
	        if($sqlP->num_rows() > 0){
	            foreach ($sqlP->result_array() as $dataP) {
	                $price=$dataP['price'];
	            }
	        }

    		$this->db->select('id');
	        $this->db->where('user_id', $userID);
	        $this->db->where('item_id', $product);
	        $this->db->where('orderID', $orderID);
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_cart');

	        if($query->num_rows() > 0){
	        	return 'exists';
	        	exit();
	        }

            $set=$this->db->insert('tbl_cart', array(
	            'user_id' => $userID,
	            'item_id' => $product,
	            'price' => $price,
	            'quantity' => '1',
	            'is_complete' => '2',
	            'orderID' => $orderID,
	            'referral_url' => $referral_url,
	            'status' => '0',
	            'createdDate' => $createdDate
	        ));

            $this->db->select('id, price');
	        $this->db->where('user_id', $userID);
	        $this->db->where('item_id', $product);
	        $this->db->where('orderID', $orderID);
	        $this->db->where('status','0');
	        $sqlC = $this->db->get('tbl_cart');

	        if($sqlC->num_rows() > 0){
	            foreach ($sqlC->result_array() as $data) {
	                $cart_id=$data['id'];
	            }
	        }

	        /*Vendor commission*/
	        $this->db->select('seller_id, seller_type');
	        $this->db->where('id', $product);
	        $sqlCom = $this->db->get('tbl_products');
	        $comsn1=0; $comsn2=0; $comsn3=0; $comsn4=0;
	        if($sqlCom->num_rows() > 0){
	            foreach ($sqlCom->result_array() as $data) {
	                $seller_type=$data['seller_type'];
	                $seller_id=$data['seller_id'];

	                $comsn1_array=getProductCommission($product);
	                $comsn2_array=getSellerTypeCommission($seller_id, $seller_type);
	                $comsn3_array=getAccountTypeCommission($seller_type);
	                $comsn4_array=getFlatCommission();

	                if(isset($comsn1_array['amount_percent']) && $comsn1_array['amount_percent']>0){
	                	$comsn1=($price*$comsn1_array['amount_percent'])/100;
	                }elseif(isset($comsn1_array['amount_fixed']) && $comsn1_array['amount_fixed']>0){
	                	$comsn1=$comsn1_array['amount_fixed'];
	                }elseif(isset($comsn2_array['amount_percent']) && $comsn2_array['amount_percent']>0){
	                	$comsn2=($price*$comsn2_array['amount_percent'])/100;
	                }elseif(isset($comsn2_array['amount_fixed']) && $comsn2_array['amount_fixed']>0){
	                	$comsn2=$comsn2_array['amount_fixed'];
	                }elseif(isset($comsn3_array['amount_percent']) && $comsn3_array['amount_percent']>0){
	                	$comsn3=($price*$comsn3_array['amount_percent'])/100;
	                }elseif(isset($comsn3_array['amount_fixed']) && $comsn3_array['amount_fixed']>0){
	                	$comsn3=$comsn3_array['amount_fixed'];
	                }elseif(isset($comsn4_array['amount_percent']) && $comsn4_array['amount_percent']>0){
	                	$comsn4=($price*$comsn4_array['amount_percent'])/100;
	                }elseif(isset($comsn4_array['amount_fixed']) && $comsn4_array['amount_fixed']>0){
	                	$comsn4=$comsn4_array['amount_fixed'];
	                }

	                if($comsn1>0){
	                	$sellerCommission=$comsn1;
	                }elseif($comsn2>0){
	                	$sellerCommission=$comsn2;
	                }elseif($comsn3>0){
	                	$sellerCommission=$comsn3;
	                }elseif($comsn4>0){
	                	$sellerCommission=$comsn4;
	                }
	            }
	        }
	        /**/

	        $comsn5=0; $comsn6=0;
	        /*for vendor_url*/
            $this->db->select('source_url, source_expire_date');
	        $this->db->where('user_id', $seller_id);
	        $this->db->where('source_url!=','');
	        $this->db->where('status','0');
	        $sqlC12 = $this->db->get('tbl_sellers');
	        if($sqlC12->num_rows() > 0){
	            foreach ($sqlC12->result_array() as $data12) {
	                $source_url=$data12['source_url'];
	                $expire_date=$data12['source_expire_date'];
	                if($source_url!=""){
	                	if(timeDiffInDays(strtotime($expire_date), time()) < 1){
		                	$this->db->select('user_id');
					        $this->db->where('vendor_url', $source_url);
					        $this->db->where('status','0');
					        $sqlC13 = $this->db->get('tbl_sellers');
					        if($sqlC13->num_rows() > 0){
					            foreach ($sqlC13->result_array() as $data13) {
					                $source_url_seller_id=$data13['user_id'];
					                if($source_url_seller_id!=""){
					                	$vendor_commision="";
					                	$comsn5_array=getSellerTypeVendorCommission($source_url_seller_id, 'insider');
						                $comsn6_array=getAccountTypeVendorCommission('insider');
						                $comsn7_array=getFlatVendorCommission();
						                $comsn8_array=getProductVendorCommission($product);

						                if(isset($comsn5_array['amount_percent']) && $comsn5_array['amount_percent']>0){
						                	$comsn5=($price*$comsn5_array['amount_percent'])/100;
						                }elseif(isset($comsn5_array['amount_fixed']) && $comsn5_array['amount_fixed']>0){
						                	$comsn5=$comsn5_array['amount_fixed'];
						                }elseif(isset($comsn6_array['amount_percent']) && $comsn6_array['amount_percent']>0){
						                	$comsn6=($price*$comsn6_array['amount_percent'])/100;
						                }elseif(isset($comsn6_array['amount_fixed']) && $comsn6_array['amount_fixed']>0){
						                	$comsn6=$comsn6_array['amount_fixed'];
						                }elseif(isset($comsn7_array['amount_percent']) && $comsn7_array['amount_percent']>0){
						                	$comsn7=($price*$comsn7_array['amount_percent'])/100;
						                }elseif(isset($comsn7_array['amount_fixed']) && $comsn7_array['amount_fixed']>0){
						                	$comsn7=$comsn7_array['amount_fixed'];
						                }elseif(isset($comsn8_array['amount_percent']) && $comsn8_array['amount_percent']>0){
						                	$comsn8=($price*$comsn8_array['amount_percent'])/100;
						                }elseif(isset($comsn8_array['amount_fixed']) && $comsn8_array['amount_fixed']>0){
						                	$comsn8=$comsn8_array['amount_fixed'];
						                }
						                if($comsn5>0){
						                	$vendor_commision=$comsn5;
						                }elseif($comsn6>0){
						                	$vendor_commision=$comsn6;
						                }elseif($comsn7>0){
						                	$vendor_commision=$comsn7;
						                }elseif($comsn8>0){
						                	$vendor_commision=$comsn8;
						                }
					                	$set=$this->db->insert('tbl_commissions', array(
								            'seller_id' => $source_url_seller_id,
								            'product' => $product,
								            'vendor_url' => $source_url,
								            'cart_id' => $cart_id,
								            'orderID' => $orderID,
								            'amount' => $vendor_commision,
								            'commissionStatus' => '5',
								            'status' => '0',
								            'createdDate' => $createdDate
								        ));

					                	/*set transaction record*/
								        $this->db->select('id');
					                	$this->db->from('tbl_commissions');
								        $this->db->where('seller_id', $source_url_seller_id);
								        $this->db->where('product', $product);
								        $this->db->where('vendor_url', $source_url);
								        $this->db->where('cart_id', $cart_id);
								        $this->db->where('orderID', $orderID);
								        $this->db->where('amount', $vendor_commision);
								        $this->db->where('commissionStatus', '5');
								        $this->db->where('status', '0');

								        $sql = $this->db->get();
								        foreach ($sql->result_array() as $rows) {
								        	$commission_id=$rows['id'];
						                	$this->db->insert('tbl_transaction_records', array(
									            'transaction_type' => 'commission',
									            'seller_id' => $source_url_seller_id,
									            'commission_id' => $commission_id,
									            'credit' => $vendor_commision,
									            'is_complete' => '1',
									            'status' => '0',
									            'createdDate' => $createdDate
									        ));
						                }
						                /*end t r*/
					                }
					            }
					        }
				        }
	                }
	            }
	        }
            /**/

		    $set=$this->db->insert('tbl_commissions', array(
	            'seller_id' => $seller_id,
	            'product' => $product,
	            'referral_url' => '',
	            'cart_id' => $cart_id,
	            'orderID' => $orderID,
	            'amount' => $sellerCommission,
	            'commissionStatus' => '5',
	            'status' => '0',
	            'createdDate' => $createdDate
	        ));

	        if($set){
	        	/*set transaction record*/
		        $this->db->select('id');
            	$this->db->from('tbl_commissions');
		        $this->db->where('seller_id', $seller_id);
		        $this->db->where('product', $product);
		        $this->db->where('referral_url', '');
		        $this->db->where('cart_id', $cart_id);
		        $this->db->where('orderID', $orderID);
		        $this->db->where('amount', $sellerCommission);
		        $this->db->where('commissionStatus', '5');
		        $this->db->where('status', '0');

		        $sql = $this->db->get();
		        foreach ($sql->result_array() as $rows) {
		        	$commission_id=$rows['id'];
                	$this->db->insert('tbl_transaction_records', array(
			            'transaction_type' => 'commission',
			            'seller_id' => $seller_id,
			            'commission_id' => $commission_id,
			            'credit' => $sellerCommission,
			            'is_complete' => '1',
			            'status' => '0',
			            'createdDate' => $createdDate
			        ));
                }
                /*end t r*/
	        	return 'Success';
	        }else{
	        	return ErrorMsg($this->db->_error_message());
	        }
		}

		function load_cart_items($userID){
        	$listArray=array();
	        $this->db->select('tbl_cart.id, quantity, item_id');
	        $this->db->from('tbl_cart');
			$this->db->join('tbl_orders', 'tbl_orders.orderID = tbl_cart.orderID');
	        $this->db->where('tbl_cart.user_id', $userID);
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
			        	$row_array=array();
				        $row_array['cart_id']=$id;
		                $row_array['image']=base_url('media/products/').$rows['image'];
		                $row_array['product_url']=$rows['product_url'];
		                $row_array['name']=$rows['name'];
		                $row_array['price']=$rows['price'];
		                $row_array['quantity']=$quantity;
		                array_push($listArray, $row_array);
		            }
	            }
	        }
	        return $listArray;
	    }

		function load_payment_methods(){
        	$method_list=array();
	        $this->db->select('method_name, code, icon, country_code');
	        $this->db->where('status', '0');
	        $sql = $this->db->get('tbl_payment_methods');
	        foreach ($sql->result_array() as $rows1) {
	        	$ls=array();
                $ls['method_name']=$rows1['method_name'];
                $ls['code']=$rows1['code'];
                $ls['country_code']=$rows1['country_code'];
                $ls['icon']=base_url().'assets/themes/payment_method/'.$rows1['icon'];
                array_push($method_list, $ls);
            }
	        return $method_list;
	    }

	}
?>