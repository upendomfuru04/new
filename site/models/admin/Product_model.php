<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Product_model extends CI_Model{

	    public function __construct(){
	        parent::__construct();
	        $this->load->database();
	        $this->load->model('Upload_model');
    	}

    	public function load_product_categories(){
			$result=array();
			$this->db->select('id, name');
		   	$this->db->where('status!=', '1');
		   	$this->db->order_by('createdDate', 'DESC');
	   		$query = $this->db->get('tbl_shop_category');
		   	// return $query->row_array();
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

    	public function load_products(){
			$result=array();
			$this->db->select('id, image, name, category, brand, price, product_status, quantity, product_url, admin, in_slider');
		   	$this->db->where('status!=', '1');
		   	$this->db->order_by('createdDate', 'DESC');
	   		$query = $this->db->get('tbl_products');
		   	// return $query->row_array();
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

		public function load_product_category_details($category){
			$this->db->select('id, name');
			$this->db->where('id', $category);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_shop_category');
		   	if($query->num_rows() > 0)
		   	return $query->row_array();
		}

		public function load_product_details($product){
			$this->db->select('id, name, image, category, brand, price, product_status, quantity, summary, description, preview_type, media_type, preview, media, virtual_link, views, update_time, createdDate, seller_type, admin, seller_id');
			$this->db->where('id', $product);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_products');
		   	if($query->num_rows() > 0)
		   	return $query->row_array();
		}

		public function load_referrals(){
		   	$result=array();
			$this->db->select('tbl_affiliate_urls.id, image, name, referral_url, tbl_affiliate_urls.seller_type, tbl_affiliate_urls.views, tbl_affiliate_urls.seller_id, commission');
			$this->db->from('tbl_affiliate_urls');
			$this->db->join('tbl_products', 'tbl_products.id = product');
		   	$this->db->where('tbl_affiliate_urls.status', '0');
		   	$this->db->where('tbl_products.status', '0');
	   		$query = $this->db->get();
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

    	public function save_product($data, $userID){
    	   
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
    		$seller=sqlSafe($data['seller']);
    		$product_status=sqlSafe($data['product_status']);
    		$quantity=sqlSafe($data['quantity']);

    		$res=array();
    		$this->db->select('id');
	        $this->db->where('name',$name);
	        $this->db->where('category',$category);
	        $this->db->where('brand',$brand);
	        $this->db->where('price',$price);
	        $this->db->where('summary', htmlspecialchars($summary, ENT_QUOTES));
	        $this->db->where('description', htmlspecialchars($description, ENT_QUOTES));
	        $this->db->where('virtual_link',$virtual_link);
	        $this->db->where('media_type',$media_type);
	        $this->db->where('preview_type',$preview_type);
	        $this->db->where('seller_id',$seller);
	        $this->db->where('seller_type', 'vendor');
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_products');

	        if($query->num_rows() > 0){
	            return ErrorMsg("Product already exist");
	            exit();
	        }
    		
    		$product_url=generateLink('tbl_products', 'product_url', $name);
    		
    		$virtual_link=$data['virtual_link'];
    		unset($_SESSION['uploaded_file']);
    		$productPreview=""; $productMedia="";
    		if(isset($_FILES['image']) && $_FILES['image']['name']!=""){
    			$image=$this->Upload_model->upload_product_image('image', $product_url);
    		
    			if($image!='ok'){ 
    				return $image;
    				exit();
    			}
    		}else{
    			return ErrorMsg("You must upload product image");
    			exit();
    		}
    		$productImage=$this->session->userdata('uploaded_file');
    	
    	
    		if($virtual_link=="" && $data['product_status']=='live'){
	    		$media_count=sqlSafe($data['episode_count']);
	    		$has_media=0;
	    		for ($i=1; $i <= $media_count; $i++) {
	    			if(isset($_FILES['media'.$i]) && $_FILES['media'.$i]['name']!=""){
	    				$user_mname=str_replace(' ', '_', $data['media_file_name'.$i]);
		        		unset($_SESSION['uploaded_file']);
		    			$media=do_upload('products/medias', 'media'.$i, str_replace(" ", "_", $name), '', '', '', 'pdf');
		    			
		    			if($media!='ok'){ 
		    				return ErrorMsg($media);
		    				exit();
		    			}else{
		    				$productMedia.=$this->session->userdata('uploaded_file').';';
		    			}
		    			$has_media++;
		    		}
	    		}
	    		if($has_media==0){
	    			return ErrorMsg("You must upload the product media/file");
	    			exit();
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
    		}else{
    			return ErrorMsg("You must upload the product preview");
    			exit();
    		}

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
		        'seller_id' => $seller,
		        'admin' => $userID,
		        'seller_type' => 'vendor',
	            'status' => '0',
	            'createdDate' => $createdDate
	        ));

	        if($set){
	        	$res['msg']="Success";
		     	$res['url']=base_url().'prod/'.$product_url;
	        	return json_encode($res);
	        }else{
	        	return ErrorMsg($this->db->_error_message());
	        	exit();
	        }
		}

		public function edit_product($product, $data, $userID){
    		$createdDate=time();
    		$res=array();
    		$name=sqlSafe($data['name']);
    		$category=sqlSafe($data['category']);
    		$brand=sqlSafe($data['brand']);
    		$price=sqlSafe($data['price']);
    		$summary=sqlSafe($data['summary']);
    		$description=sqlSafe($data['description']);
    		$virtual_link=sqlSafe($data['virtual_link']);
    		$media_type=sqlSafe($data['media_type']);
    		$preview_type=sqlSafe($data['preview_type']);
    		$seller=sqlSafe($data['seller']);
    		$product_status=sqlSafe($data['product_status']);
    		$quantity=sqlSafe($data['quantity']);
    		
    // 		echo json_encode($data);
    // 		exit;
    		
    		$product_url=generateLink('tbl_products', 'product_url', $name, $product);
    		
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
	    		$media_count=sqlSafe($data['episode_count']);
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

    		
    		/*if(isset($_FILES['preview']) && $_FILES['preview']['name']!=""){
        		unset($_SESSION['uploaded_file']);
    			$preview=do_upload('media/products/preview', 'preview', str_replace(" ", "_", $data['name']), '', '', '', 'pdf');
    			if($preview!='ok'){ 
    				die(ErrorMsg($preview));
    				exit;
    			}else{
    				$productPreview=$this->session->userdata('uploaded_file');
    			}
    		}*/
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

    		$this->db->select('id');
	        $this->db->where('name',$name);
	        $this->db->where('category',$category);
	        $this->db->where('brand',$brand);
	        $this->db->where('price',$price);
	        $this->db->where('summary', htmlspecialchars($summary, ENT_QUOTES));
	        $this->db->where('description', htmlspecialchars($description, ENT_QUOTES));
	        $this->db->where('virtual_link',$virtual_link);
	        $this->db->where('media_type',$media_type);
	        $this->db->where('preview_type',$preview_type);
	        $this->db->where('seller_id', $seller);
	        $this->db->where('seller_type', 'vendor');
	        $this->db->where('status','0');
	        $this->db->where('id !=', $product);
	        $query = $this->db->get('tbl_products');

	        if($query->num_rows() > 0){
	            die(ErrorMsg("Product already exist"));
	        }

            $set=array(
	            'name' => $name,
	            'category' => $category,
	            'brand' => $brand,
	            'price' => $price,
	            'product_status' => $product_status,
	            'quantity' => $quantity,
	            'product_url' => $product_url,
	            'summary' => htmlspecialchars($summary, ENT_QUOTES),
	            'description' => htmlspecialchars($description, ENT_QUOTES),
	            'virtual_link' => $virtual_link,
	            'media_type' => $media_type,
	            'preview_type' => $preview_type,
		        'seller_id' => $seller,
		        'seller_type' => 'vendor',
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

    	public function save_product_categories($data, $userID){
    		$createdDate=time();
    		$name=sqlSafe($data['name']);

    		$this->db->select('id');
	        $this->db->where('name', $name);
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_shop_category');

	        if($query->num_rows() > 0){
	            die(ErrorMsg("Category already exist"));
	        }

            $set=$this->db->insert('tbl_shop_category', array(
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

		public function save_referral_expire_date($data, $userID){
			$seller_id=sqlSafe($data['seller']);
			$expire_date=sqlSafe($data['expire_date']);

			$expire_date=date("Y-m-d", strtotime($expire_date));

            $set=array(
	            'source_expire_date' => $expire_date
	        );

	        $this->db->where('user_id', $seller_id);
	     	$this->db->update('tbl_sellers', $set);
        	return 'Success';
		}

		public function edit_product_categories($category, $data, $userID){
			$name=sqlSafe($data['name']);

    		$this->db->select('id');
	        $this->db->where('name',$name);
	        $this->db->where('status','0');
	        $this->db->where('id !=', $category);
	        $query = $this->db->get('tbl_shop_category');

	        if($query->num_rows() > 0){
	            die(ErrorMsg("Category already exist"));
	        }

            $set=array(
	            'name' => $name
	        );

	        $this->db->where('id', $category);
	     	$this->db->update('tbl_shop_category', $set);
        	return 'Success';
		}

		public function delete_product($product, $userID){
			$this->db->set('status', '1');
	   		$this->db->where('id', $product);
	     	$this->db->update('tbl_products');
		   	return 'Success';
		}

		public function delete_product_category($category, $userID){
			$this->db->set('status', '1');
	   		$this->db->where('id', $category);
	     	$this->db->update('tbl_shop_category');
		   	return 'Success';
		}

		public function delete_referral_url($product, $userID){
			$this->db->set('status', '1');
	   		$this->db->where('id', $product);
	     	$this->db->update('tbl_affiliate_urls');
		   	return 'Success';
		}

    	public function add_product_to_slideshow($product, $userID){
            $this->db->set('in_slider', '1');
	   		$this->db->where('id', $product);
	   		$this->db->where('status', '0');
	     	$this->db->update('tbl_products');
	     	echo 'Success';
		}

    	public function remove_from_slideshow($product, $userID){
            $this->db->set('in_slider', '0');
	   		$this->db->where('id', $product);
	   		$this->db->where('status', '0');
	     	$this->db->update('tbl_products');
	     	echo 'Success';
		}

    }
