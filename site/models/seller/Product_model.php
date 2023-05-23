<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Product_model extends CI_Model{

	    public function __construct(){
	        parent::__construct();
	        $this->load->database();
        	$this->load->model('Upload_model');
    	}

		public function load_products($userID, $account_type){
			$result=array();
			$this->db->select('id, image, name, category, product_status, brand, price, quantity, product_url');
			$this->db->where('seller_id', $userID);
			$this->db->where('seller_type', $account_type);
		   	$this->db->where('status', '0');
		   	$this->db->order_by('createdDate', 'DESC');
	   		$query = $this->db->get('tbl_products');
		   	// return $query->row_array();
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function load_seller_products($seller, $userID){
            $profile_url = $this->get_seller_profile_url($userID);
			$result=array();
			$this->db->select('id, image, name, product_url');
			$this->db->where('seller_id', $seller);
		   	$this->db->where('status', '0');
		   	$this->db->order_by('createdDate', 'DESC');
		   	// $this->db->limit(2);
	   		$query = $this->db->get('tbl_products');
		   	if($query->num_rows() > 0){
		   		foreach ($query->result_array() as $data) {
		   			$product=$data['id'];
		   			$product_url=$data['product_url'];
		   			$is_referred=false;
		   			$this->db->select('id');
					$this->db->from('tbl_affiliate_urls');
					$this->db->where('product', $product);
					$this->db->where('seller_id', $userID);
				   	$this->db->where('status!=', '1');
			   		$sql = $this->db->get();
			   		if($sql->num_rows() > 0){
			   			$is_referred=true;
			   		}
			   		if($is_referred){
                        $referral_url=$product_url.'/?ref='.$profile_url;
                    }else{
                        $referral_url=generateReferralUrl($userID, "", $product_url);
                    }
			   		$saved=$this->save_affiliateurl($referral_url, $product, $userID);
			   		if($saved=='Success'){
				   		$res=array();
				   		$res['id']=$product;
				   		$res['image']=$data['image'];
				   		$res['name']=$data['name'];
				   		$res['product_url']=$product_url;
				   		$res['referral_url']=$referral_url;
				   		$res['is_referred']=$is_referred;
				   		array_push($result, $res);
				   	}
		   		}
		   	}
		   	return $result;
		}

		public function get_seller_info($seller){
			$result=array();
			$this->db->select('full_name');
			$this->db->where('user_id', $seller);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_sellers');
		   	if($query->num_rows() > 0)
		   	$result = $query->row_array();
		   	return $result;
		}

		public function get_seller_profile_url($userID){
			$profile_url="";
			$this->db->select('profile_url');
	        $this->db->where('user_id', $userID);
	        $this->db->where('is_insider', '1');
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_sellers');
	        if($query->num_rows() > 0){
	        	foreach ($query->result_array() as $rows) {
	        		$profile_url=$rows['profile_url'];
	        	}
	        }
	        return $profile_url;
		}

		public function load_referrals($userID){
		   	$result=array();
			$this->db->select('tbl_affiliate_urls.id, image, name, referral_url, tbl_affiliate_urls.seller_type, tbl_affiliate_urls.views, commission');
			$this->db->from('tbl_affiliate_urls');
			$this->db->join('tbl_products', 'tbl_products.id = product');
			$this->db->where('tbl_affiliate_urls.seller_id', $userID);
		   	$this->db->where('tbl_affiliate_urls.status', '0');
		   	$this->db->where('tbl_products.status', '0');
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function load_referrals_vendors($userID){
			$vendor_url="";
			$this->db->select('vendor_url');
			$this->db->from('tbl_sellers');
			$this->db->where('user_id', $userID);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0){
		   		foreach ($query->result() as $data) {
		   			$vendor_url=$data->vendor_url;
		   		}
		   	}

			$result=array();
			$this->db->select('tbl_users.id, full_name, gender, phone, email, avatar, address, is_insider, is_outsider, is_vendor, is_contributor, is_trusted_vendor, tbl_users.createdDate, source_expire_date');
		   	$this->db->from('tbl_sellers');
		   	$this->db->join('tbl_users', 'tbl_users.id=user_id AND tbl_users.status="0"');
		   	$this->db->where('tbl_sellers.source_url=', $vendor_url);
		   	$this->db->where('tbl_sellers.status', '0');
		   	$this->db->order_by('tbl_users.createdDate', 'DESC');
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function vendor_url($userID){
		   	$vendor_url="";
			$this->db->select('vendor_url');
			$this->db->from('tbl_sellers');
			$this->db->where('user_id', $userID);
		   	$this->db->where('is_insider', '1');
		   	$this->db->where('status', '0');
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0){
		   		foreach ($query->result() as $data) {
		   			$vendor_url=$data->vendor_url;
		   		}
		   	}
		   	return $vendor_url;
		}

    	public function load_affiliate_marketers($userID){
			$result=array(); $sellers=array();
			// $this->db->distinct();
			$this->db->select('tbl_affiliate_urls.seller_id, tbl_affiliate_urls.views');
			$this->db->from('tbl_affiliate_urls');
			$this->db->join('tbl_products', 'tbl_products.id = product AND tbl_products.status="0"');
		   	$this->db->where('tbl_affiliate_urls.status', '0');
		   	$this->db->order_by('tbl_affiliate_urls.views', 'DESC');
		   	// $this->db->group_by('tbl_affiliate_urls.seller_id');
	   		$query1 = $this->db->get();
		   	if($query1->num_rows() > 0){
		   		foreach ($query1->result_array() as $data1) {
		   			$seller_id=$data1['seller_id'];
		   			if(!in_array($seller_id, $sellers)){
			   			array_push($sellers, $seller_id);
			   		}
		   		}
		   	}

		   	for ($i=0; $i < sizeof($sellers); $i++) { 
		   		$seller=$sellers[$i];
		   		$total_views=0; $total_products=0;
		   		$full_name=""; $avatar=""; $is_insider=""; $is_outsider=""; $is_contributor=""; $createdDate=""; $phone=""; $email=""; $address="";
		   		$this->db->select('tbl_affiliate_urls.id, tbl_affiliate_urls.views');
				$this->db->from('tbl_affiliate_urls');
				$this->db->join('tbl_products', 'tbl_products.id = product AND tbl_products.status="0"');
				$this->db->where('tbl_affiliate_urls.seller_id', $seller);
			   	$this->db->where('tbl_affiliate_urls.status', '0');
		   		$sql = $this->db->get();
			   	if($sql->num_rows() > 0){
			   		foreach ($sql->result_array() as $data2) {
			   			$total_views=$total_views+$data2['views'];
			   			$total_products++;
			   		}
			   	}

			   	$this->db->select('full_name, phone, email, avatar, address, is_insider, is_outsider, is_contributor, tbl_users.createdDate');
			   	$this->db->from('tbl_sellers');
			   	$this->db->join('tbl_users', '(tbl_users.id=user_id AND tbl_users.status="0")');
			   	$this->db->where("(tbl_sellers.is_insider='1' OR tbl_sellers.is_outsider='1' OR tbl_sellers.is_contributor='1') AND tbl_sellers.status='0'", null, false);
			   	$this->db->where('tbl_sellers.user_id', $seller);
			   	$this->db->where('tbl_sellers.status', '0');
		   		$query = $this->db->get();
			   	if($query->num_rows() > 0){
			   		foreach ($query->result_array() as $data) {
			   			$full_name=$data['full_name'];
			   			$phone=$data['phone'];
			   			$email=$data['email'];
			   			$avatar=$data['avatar'];
			   			$address=$data['address'];
			   			$is_insider=$data['is_insider'];
			   			$is_outsider=$data['is_outsider'];
			   			$is_contributor=$data['is_contributor'];
			   			$createdDate=$data['createdDate'];
			   		}
			   	}

			   	$res=array();
			   	$res['user_id']=$seller;
			   	$res['avatar']=$avatar;
			   	$res['full_name']=$full_name;
			   	$res['phone']=$phone;
			   	$res['email']=$email;
			   	$res['address']=$address;
			   	$res['is_insider']=$is_insider;
			   	$res['is_outsider']=$is_outsider;
			   	$res['is_contributor']=$is_contributor;
			   	$res['total_views']=$total_views;
			   	$res['total_products']=$total_products;
			   	$res['createdDate']=$createdDate;
			   	array_push($result, $res);
		   	}

		   	/*$this->db->select('full_name, phone, email, avatar, address, is_insider, is_outsider, is_contributor, tbl_users.createdDate');
		   	$this->db->from('tbl_sellers');
		   	$this->db->join('tbl_users', '');
		   	$this->db->where("(tbl_sellers.is_insider='1' OR tbl_sellers.is_outsider='1' OR tbl_sellers.is_contributor='1') AND tbl_sellers.status='0'", null, false);
		   	$this->db->where('tbl_sellers.user_id', $seller);
	   		$query = $this->db->get();*/
	   		$seller_list='('.implode(", ", $sellers).')';
	   		$query = $this->db->query("SELECT user_id, full_name, phone, email, avatar, address, is_insider, is_outsider, is_contributor, tbl_users.createdDate FROM tbl_sellers JOIN tbl_users ON (tbl_users.id=user_id AND tbl_users.status='0') WHERE tbl_sellers.status='0' AND (tbl_sellers.is_insider='1' OR tbl_sellers.is_outsider='1' OR tbl_sellers.is_contributor='1') AND tbl_sellers.user_id NOT IN $seller_list");
		   	if($query->num_rows() > 0){
		   		foreach ($query->result_array() as $data) {
		   			$full_name=$data['full_name'];
		   			$phone=$data['phone'];
		   			$email=$data['email'];
		   			$avatar=$data['avatar'];
		   			$address=$data['address'];
		   			$is_insider=$data['is_insider'];
		   			$is_outsider=$data['is_outsider'];
		   			$is_contributor=$data['is_contributor'];
		   			$createdDate=$data['createdDate'];
		   			$res=array();
				   	$res['user_id']=$data['user_id'];
				   	$res['avatar']=$avatar;
				   	$res['full_name']=$full_name;
				   	$res['phone']=$phone;
				   	$res['email']=$email;
				   	$res['address']=$address;
				   	$res['is_insider']=$is_insider;
				   	$res['is_outsider']=$is_outsider;
				   	$res['is_contributor']=$is_contributor;
				   	$res['total_views']=0;
				   	$res['total_products']=0;
				   	$res['createdDate']=$createdDate;
				   	array_push($result, $res);
		   		}
		   	}

		   	$view_lst = array();
			foreach ($result as $key => $row){
			    $view_lst[$key] = $row['total_views'];
			}
			array_multisort($view_lst, SORT_DESC, $result);
		   	
		   	$this->db->set('is_read', '0');
			$this->db->where('user_id', $userID);
	     	$this->db->update('tbl_affiliate_notification');
		   	return $result;
		}

		public function load_product_details($product, $userID, $account_type){
			$this->db->select('id, name, image, category, brand, price, quantity, product_status, summary, description, preview_type, media_type, preview, media, virtual_link, views, update_time, createdDate, seller_type');
			$this->db->where('id', $product);
			$this->db->where('seller_id', $userID);
			$this->db->where('seller_type', $account_type);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_products');
		   	if($query->num_rows() > 0)
		   	return $query->row_array();
		}

		public function delete_product($product, $userID, $account_type){
			$this->db->set('status', '1');
			$this->db->where('seller_id', $userID);
			$this->db->where('seller_type', $account_type);
	   		$this->db->where('id', $product);
	     	$this->db->update('tbl_products');
		   	return 'Success';
		}

		public function delete_referral_url($product, $userID){
			$this->db->set('status', '1');
			$this->db->where('seller_id', $userID);
	   		$this->db->where('id', $product);
	     	$this->db->update('tbl_affiliate_urls');
		   	return 'Success';
		}

    	public function save_affiliate_url($data, $userID){
    		$createdDate=time();
			$seller_type=sqlSafe($data['seller_type']);

    		$error=false;
    		if(!isUrlValid($data['product'])){
    			$error=true;
    			$res=ErrorMsg("Sorry! Url is not valid");
    		}else{
    			$link_array = explode('/', $data['product']);
	        	$product_url = end($link_array);
	        	$product=getItemID($product_url);

	    		$this->db->select('id');
		        $this->db->where('product', $product);
		        $this->db->where('seller_id', $userID);
		        $this->db->where('seller_type', $seller_type);
		        $this->db->where('status','0');
		        $query = $this->db->get('tbl_affiliate_urls');

		        if($query->num_rows() > 0){
		        	$error=true;
		            $res=ErrorMsg("This referral url already exist");
		        }else{
		        	$referral_url=generateReferralUrl($userID, $seller_type, $product_url);
		            $set=$this->db->insert('tbl_affiliate_urls', array(
			            'product' => $product,
			            'referral_url' => $referral_url,
			            'seller_id' => $userID,
			            'seller_type' => $seller_type,
			            'status' => '0',
			            'createdBy' => $userID,
			            'createdDate' => $createdDate
			        ));
		           
			        if($set){
			        	$error=false;
			        	$res=base_url()."prod/".$referral_url;
			        }else{
			        	$error=true;
			        	$res=ErrorMsg($this->db->_error_message());
			        }
		        }
		        $result = json_encode(array('error' => $error, 'res' => $res));
		        return $result;
    		}
		}

    	public function save_affiliateurl($referral_url, $product, $userID){
    		$createdDate=time();
    		$seller_type="";
    		$this->db->select('is_insider, is_outsider, is_contributor');
	        $this->db->where('user_id', $userID);
	        $sql1 = $this->db->get('tbl_sellers');
	        if($sql1->num_rows() > 0){
	            foreach ($sql1->result_array() as $data) {
	                if($data['is_insider']=='1'){
	                    $seller_type="insider";
	                }
	                if($data['is_outsider']=='1'){
	                    $seller_type="outsider";
	                }
	                if($data['is_contributor']=='1'){
	                    $seller_type="contributor";
	                }
	            }
	        }

    		$this->db->select('id');
	        $this->db->where('product', $product);
	        $this->db->where('seller_id', $userID);
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_affiliate_urls');
	        if($query->num_rows() > 0){
	            // $res="This referral url already exist";
	            return 'Success';
	        }else{
	            $set=$this->db->insert('tbl_affiliate_urls', array(
		            'product' => $product,
		            'referral_url' => $referral_url,
		            'seller_id' => $userID,
		            'seller_type' => $seller_type,
		            'status' => '0',
		            'createdBy' => $userID,
		            'createdDate' => $createdDate
		        ));
	           
		        if($set){
		        	return 'Success';
		        }else{
		        	die($this->db->_error_message());
		        }
	        }
		}

    	public function save_vendor_url($userID){
    		$this->db->select('profile_url');
	        $this->db->where('user_id', $userID);
	        $this->db->where('is_insider', '1');
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_sellers');

	        if($query->num_rows() > 0){
	        	foreach ($query->result() as $rows) {
	        		$vendor_url="?vref=".$rows->profile_url;
	        		$set=array(
			            'vendor_url' => $vendor_url
			        );

			        $this->db->where('user_id', $userID);
			        $this->db->where('is_insider', '1');
			        $this->db->where('status','0');
			        $this->db->update('tbl_sellers', $set);

			        $error=false;
		        	$res=base_url()."/register".$vendor_url;
	        	}
	        }else{
	        	$error=true;
	            $res=ErrorMsg("Sorry! you are not insider");
	        }
	        $result = json_encode(array('error' => $error, 'res' => $res));
	        return $result;
		}

    	public function save_product($data, $account_type, $userID){
    		$createdDate=time();
			$name=sqlSafe($data['name']);
			$category=sqlSafe($data['category']);
			$brand=sqlSafe($data['brand']);
			$price=sqlSafe($data['price']);
			$summary=sqlSafe($data['summary']);
			$description=sqlSafe($data['description']);
			$virtual_link=sqlSafe($data['virtual_link']);
			$media_type=sqlSafe($data['media_type']);
			$preview_type=sqlSafe($data['preview_type']);
			$product_status=sqlSafe($data['product_status']);
			$quantity=sqlSafe($data['quantity']);

    		$res=array();
    		$this->db->select('id');
	        $this->db->where('name', $name);
	        $this->db->where('category', $category);
	        $this->db->where('brand', $brand);
	        $this->db->where('price', $price);
	        $this->db->where('summary', htmlspecialchars($summary, ENT_QUOTES));
	        $this->db->where('description', htmlspecialchars($description, ENT_QUOTES));
	        $this->db->where('virtual_link', $virtual_link);
	        $this->db->where('media_type', $media_type);
	        $this->db->where('preview_type', $preview_type);
	        $this->db->where('seller_id', $userID);
	        $this->db->where('seller_type', $account_type);
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_products');

	        if($query->num_rows() > 0){
	            die(ErrorMsg("Product already exist"));
	        }

	        $product_url=generateLink('tbl_products', 'product_url', $name);

    		unset($_SESSION['uploaded_file']);
    		$productPreview=""; $productMedia="";
    		if(isset($_FILES['image']) && $_FILES['image']['name']!=""){
    			$image=$this->Upload_model->upload_product_image('image', $product_url);
    			if($image!='ok'){ 
    				die(ErrorMsg($image));
    			}
    		}else{
    			die(ErrorMsg("You must upload product image"));
    		}
    		$productImage=$this->session->userdata('uploaded_file');

    		if($virtual_link=="" && $data['product_status']=='live'){
	    		$media_count=$data['episode_count'];
	    		$has_media=0;
	    		for ($i=1; $i <= $media_count; $i++) {
	    			if(isset($_FILES['media'.$i]) && $_FILES['media'.$i]['name']!=""){
	    				$user_mname=str_replace(' ', '_', $data['media_file_name'.$i]);
		        		unset($_SESSION['uploaded_file']);
		    			$media=do_upload('products/medias', 'media'.$i, str_replace(" ", "_", $name), '', '', '', 'pdf');
		    			if($media!='ok'){ 
		    				die(ErrorMsg($media));
		    				exit;
		    			}else{
		    				$productMedia.=$this->session->userdata('uploaded_file').';';
		    			}
		    			$has_media++;
		    		}
	    		}
	    		if($has_media==0){
	    			die(ErrorMsg("You must upload the product media/file"));
	    		}
	    	}
    		
    		if(isset($_FILES['preview']) && $_FILES['preview']['name']!=""){
        		unset($_SESSION['uploaded_file']);
    			$preview=do_upload('media/products/preview', 'preview', str_replace(" ", "_", $name), '', '', '', 'pdf');
    			if($preview!='ok'){ 
    				die(ErrorMsg($preview));
    				exit;
    			}else{
    				$productPreview=$this->session->userdata('uploaded_file');
    			}
    		}else{
    			die(ErrorMsg("You must upload the product preview"));
    		}
    		
    		$product_url=generateLink('tbl_products', 'product_url', $name);

            $set=$this->db->insert('tbl_products', array(
	            'name' => $name,
	            'category' => $category,
	            'image' => $productImage,
	            'brand' => $brand,
	            'price' => $price,
	            'product_status' => $product_status,
	            'quantity' => $quantity,
	            'product_url' => $product_url,
	            'summary' => htmlspecialchars($summary, ENT_QUOTES),
	            'description' => htmlspecialchars($description, ENT_QUOTES),
	            'virtual_link' => $virtual_link,
	            'media' => $productMedia,
	            'media_type' => $media_type,
	            'preview' => $productPreview,
	            'preview_type' => $preview_type,
	            'seller_id' => $userID,
	            'seller_type' => $account_type,
	            'status' => '0',
	            'createdDate' => $createdDate
	        ));

	        if($set){
	        	$res['msg']="Success";
		     	$res['url']=base_url().'prod/'.$product_url;
	        	return json_encode($res);
	        }else{
	        	return ErrorMsg($this->db->_error_message());
	        }
		}

		public function edit_product($product, $data, $account_type, $userID){
    		$createdDate=time();
			$name=sqlSafe($data['name']);
			$category=sqlSafe($data['category']);
			$brand=sqlSafe($data['brand']);
			$price=sqlSafe($data['price']);
			$summary=sqlSafe($data['summary']);
			$description=sqlSafe($data['description']);
			$virtual_link=sqlSafe($data['virtual_link']);
			$media_type=sqlSafe($data['media_type']);
			$preview_type=sqlSafe($data['preview_type']);
			$product_status=sqlSafe($data['product_status']);
			$quantity=sqlSafe($data['quantity']);

    		$res=array();
    		$this->db->select('id');
	        $this->db->where('name', $name);
	        $this->db->where('category', $category);
	        $this->db->where('brand', $brand);
	        $this->db->where('price', $price);
	        $this->db->where('summary', htmlspecialchars($summary, ENT_QUOTES));
	        $this->db->where('description', htmlspecialchars($description, ENT_QUOTES));
	        $this->db->where('virtual_link', $virtual_link);
	        $this->db->where('media_type', $media_type);
	        $this->db->where('preview_type', $preview_type);
	        $this->db->where('seller_id', $userID);
	        $this->db->where('seller_type', $account_type);
	        $this->db->where('status','0');
	        $this->db->where('id !=', $product);
	        $query = $this->db->get('tbl_products');

	        if($query->num_rows() > 0){
	            die(ErrorMsg("Product already exist"));
	        }

	        $product_url=generateLink('tbl_products', 'product_url', $name, $product);

    		$virtual_link=$data['virtual_link'];
    		unset($_SESSION['uploaded_file']);
    		$productPreview=""; $productMedia="";
    		if(isset($_FILES['image']) && $_FILES['image']['name']!=""){
    			$image=$this->Upload_model->upload_product_image('image', $product_url);
    			if($image!='ok'){ 
    				die(ErrorMsg($image));
    			}
    		}
    		$productImage=$this->session->userdata('uploaded_file');

    		if($virtual_link=="" && $data['product_status']=='live'){
	    		$media_count=$data['episode_count'];
	    		$has_media=0;
	    		for ($i=0; $i <= $media_count; $i++) {
	    			if(isset($_FILES['media'.$i]) && $_FILES['media'.$i]['name']!=""){
	    				$user_mname=str_replace(' ', '_', $data['media_file_name'.$i]);
		        		unset($_SESSION['uploaded_file']);
		    			$media=do_upload('products/medias', 'media'.$i, str_replace(" ", "_", $name), '', '', '', 'pdf');
		    			if($media!='ok'){ 
		    				die(ErrorMsg($media));
		    				exit;
		    			}else{
		    				$productMedia.=$this->session->userdata('uploaded_file').';';
		    			}
		    			$has_media++;
		    		}else{
		    			if(isset($data['holder_file_name'.$i]) && $data['holder_file_name'.$i]!=""){
			    			$productMedia.=$data['holder_file_name'.$i].';';
			    			$has_media++;
			    		}
		    		}
	    		}
	    		if($has_media==0){
	    			die(ErrorMsg("You must upload the product media/file"));
	    		}
	    	}
	    	
    		if(isset($_FILES['preview']) && $_FILES['preview']['name']!=""){
        		unset($_SESSION['uploaded_file']);
        		/*delete other files*/
        		$filename=strtolower(str_replace(" ", "_", $name));
				$fl_path = glob('media/products/preview/'.$filename.'*');
				foreach($fl_path as $duplicate){ 
					chmod($duplicate, 0777);
					unlink($duplicate);
				}
        		/*end*/
    			$preview=do_upload('media/products/preview', 'preview', str_replace(" ", "_", $name), '', '', '', 'pdf');
    			if($preview!='ok'){ 
    				return ErrorMsg($preview);
    				exit();
    			}else{
    				$productPreview=$this->session->userdata('uploaded_file');
    			}
    		}

            $set=array(
	            'name' => $name,
	            'category' => $category,
	            'brand' => $brand,
	            'price' => $price,
	            'product_status' => $product_status,
	            'quantity' => $quantity,
	            'product_url' => $product_url,
	            'summary' => htmlspecialchars($summary, ENT_NOQUOTES),
	            'description' => htmlspecialchars($description, ENT_NOQUOTES),
	            'virtual_link' => $virtual_link,
	            'media_type' => $media_type,
	            'preview_type' => $preview_type,
		        'seller_id' => $userID,
		        'seller_type' => $account_type,
	            'update_time' => $createdDate
	        );

    		if($productImage!=""){ $set['image']=$productImage;}
    		if($productMedia!=""){ $set['media']=$productMedia;}
    		if($productPreview!=""){ $set['preview']=$productPreview;}

	        $this->db->where('id',$product);
	     	$this->db->update('tbl_products', $set);
	     	$res['msg']="Success";
	     	$res['url']=base_url().'prod/'.$product_url;
        	return json_encode($res);
		}

    }