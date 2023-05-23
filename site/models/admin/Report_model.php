<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Report_model extends CI_Model{

	    public function __construct(){
	        parent::__construct();
	        $this->load->database();
    	}

		public function load_visits(){
			$result=array();
			$this->db->select('page, browser, ip_address, createdDate');
			$this->db->from('site_views_meta');
		   	$this->db->where('status', '0');
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0)
		   	$result = $query->result();
		   	return $result;
		}

    	public function visits(){
    		$total=0;
			$this->db->select('counter');
			// $this->db->distinct('page');
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('site_views');
		   	
		   	if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
                    $total=$total+$data['counter'];
	            }
	        }
	        return $total;
		}

    	public function customers(){
    		$total=0;
			$this->db->select('id');
		   	$this->db->where('account_type', 'customer');
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_users');
		   	
		   	$total=$query->num_rows();
	        return $total;
		}

    	public function vendors(){
    		$total=0;
			$this->db->select('tbl_users.id');
			$this->db->from('tbl_users');
			$this->db->join('tbl_sellers', 'user_id=tbl_users.id');
		   	$this->db->where('is_vendor', '1');
		   	$this->db->where('tbl_users.status', '0');
	   		$query = $this->db->get();
		   	
		   	$total=$query->num_rows();
	        return $total;
		}

    	public function insiders(){
    		$total=0;
			$this->db->select('tbl_users.id');
			$this->db->from('tbl_users');
			$this->db->join('tbl_sellers', 'user_id=tbl_users.id');
		   	$this->db->where('is_insider', '1');
		   	$this->db->where('tbl_users.status', '0');
	   		$query = $this->db->get();
		   	
		   	$total=$query->num_rows();
	        return $total;
		}

    	public function outsiders(){
    		$total=0;
			$this->db->select('tbl_users.id');
			$this->db->from('tbl_users');
			$this->db->join('tbl_sellers', 'user_id=tbl_users.id');
		   	$this->db->where('is_outsider', '1');
		   	$this->db->where('tbl_users.status', '0');
	   		$query = $this->db->get();
		   	
		   	$total=$query->num_rows();
	        return $total;
		}

    	public function contributors(){
    		$total=0;
			$this->db->select('tbl_users.id');
			$this->db->from('tbl_users');
			$this->db->join('tbl_sellers', 'user_id=tbl_users.id');
		   	$this->db->where('is_contributor', '1');
		   	$this->db->where('tbl_users.status', '0');
	   		$query = $this->db->get();
		   	
		   	$total=$query->num_rows();
	        return $total;
		}

    	public function affiliates(){
    		$total=0;
			$this->db->select('tbl_users.id');
			$this->db->from('tbl_users');
			$this->db->join('tbl_sellers', 'user_id=tbl_users.id');
		   	$this->db->where("(tbl_sellers.is_insider='1' OR tbl_sellers.is_outsider='1' OR tbl_sellers.is_contributor='1') AND tbl_sellers.status!='1'", null, false);
		   	$this->db->where('tbl_users.status', '0');
	   		$query = $this->db->get();
		   	
		   	$total=$query->num_rows();
	        return $total;
		}

    	public function products(){
    		$total=0;
			$this->db->select('id');
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_products');
		   	
		   	$total=$query->num_rows();
	        return $total;
		}

    	public function ebooks(){
    		$total=0;
			$this->db->select('id');
		   	$this->db->where('category', '1');
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_products');
		   	
		   	$total=$query->num_rows();
	        return $total;
		}

    	public function audiobooks(){
    		$total=0;
			$this->db->select('id');
		   	$this->db->where('category', '2');
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_products');
		   	
		   	$total=$query->num_rows();
	        return $total;
		}

    	public function trainings_programms(){
    		$total=0;
			$this->db->select('id');
		   	$this->db->where('category', '3');
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_products');
		   	
		   	$total=$query->num_rows();
	        return $total;
		}

    	public function blog_posts(){
    		$total=0;
			$this->db->select('id');
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_blog_post');
		   	
		   	$total=$query->num_rows();
	        return $total;
		}

    	public function commissions(){
    		$total=0;
			$this->db->select('id');
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_commissions');
		   	
		   	$total=$query->num_rows();
	        return $total;
		}

    	public function paid_commissions(){
    		$total=0;
			$this->db->select('id');
		   	$this->db->where('commissionStatus', '0');
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_commissions');
		   	
		   	$total=$query->num_rows();
	        return $total;
		}

    	public function unpaid_commissions(){
    		$total=0;
			$this->db->select('id');
		   	$this->db->where('commissionStatus', '1');
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_commissions');
		   	
		   	$total=$query->num_rows();
	        return $total;
		}

    	public function pending_commissions(){
    		$total=0;
			$this->db->select('id');
		   	$this->db->where('commissionStatus', '2');
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_commissions');
		   	
		   	$total=$query->num_rows();
	        return $total;
		}

    	public function refundend_commissions(){
    		$total=0;
			$this->db->select('id');
		   	$this->db->where('commissionStatus', '3');
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_commissions');
		   	
		   	$total=$query->num_rows();
	        return $total;
		}

    	public function cancelled_commissions(){
    		$total=0;
			$this->db->select('id');
		   	$this->db->where('commissionStatus', '4');
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_commissions');
		   	
		   	$total=$query->num_rows();
	        return $total;
		}

    	public function requests(){
    		$total=0;
			$this->db->select('id');
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_withdrawal');
		   	
		   	$total=$query->num_rows();
	        return $total;
		}

    	public function paid_requests(){
    		$total=0;
			$this->db->select('id');
		   	$this->db->where('is_processed', '0');
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_withdrawal');
		   	
		   	$total=$query->num_rows();
	        return $total;
		}

    	public function pending_requests(){
    		$total=0;
			$this->db->select('id');
		   	$this->db->where('is_processed', '1');
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_withdrawal');
		   	
		   	$total=$query->num_rows();
	        return $total;
		}

    	public function rejected_requests(){
    		$total=0;
			$this->db->select('id');
		   	$this->db->where('is_processed', '2');
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_withdrawal');
		   	
		   	$total=$query->num_rows();
	        return $total;
		}

    	public function orders(){
    		$total=0;
			$this->db->select('id');
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_orders');
		   	
		   	$total=$query->num_rows();
	        return $total;
		}

    	public function pending_orders(){
    		$total=0;
			$this->db->select('tbl_orders.id');
			$this->db->distinct();
			$this->db->from('tbl_orders');
			$this->db->join('tbl_cart', 'tbl_cart.orderID=tbl_orders.orderID');
			$this->db->join('tbl_products', 'tbl_products.id=tbl_cart.item_id');
		   	$this->db->where('tbl_cart.status', '0');
		   	$this->db->where('tbl_orders.status', '0');
		   	$this->db->where('tbl_orders.is_complete', '2');
		   	$this->db->order_by('tbl_orders.id', 'DESC');
	   		$query = $this->db->get();
		   	
		   	$total=$query->num_rows();
	        return $total;
		}

    	public function complete_orders(){
    		$total=0;
			$this->db->select('id');
		   	$this->db->where('is_complete', '0');
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_orders');
		   	
		   	$total=$query->num_rows();
	        return $total;
		}

	    function getTotalAffiliates(){
	        $this->db->select('tbl_users.id');
	        $this->db->from('tbl_sellers');
	        $this->db->join('tbl_users', 'tbl_users.id=user_id');
	        $this->db->where("(tbl_sellers.is_insider='1' OR tbl_sellers.is_outsider='1' OR tbl_sellers.is_contributor='1') AND tbl_sellers.status!='1'", null, false);
	        $this->db->where('tbl_users.status!=1 AND tbl_users.status!=3', NULL, false);
	        $query = $this->db->get();
	        return $query->num_rows();
	    }

    }
