<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class App_model extends CI_Model{

	    public function __construct(){
	        parent::__construct();
	        $this->load->database();
    	}

		public function load_featured_ins_cont(){
			$result=array();
			$this->db->select('tbl_sellers.user_id, avatar, full_name, is_insider, is_contributor');
			$this->db->from('tbl_sellers');
			$this->db->join('tbl_users', 'tbl_users.id=user_id AND tbl_users.status="0"');
		   	$this->db->where('tbl_sellers.status', '0');
		   	$this->db->where('is_insider!="" OR is_contributor!=""');
		   	$this->db->order_by('tbl_sellers.id', 'DESC');	   	
		   	$this->db->limit(20);		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	            	$res=array();
	            	$res['seller']=$data['full_name'];
	            	$res['avatar']=base_url('media/').getSellerBrandAvatar($data['user_id']);
	            	$seller_type="";
            		if($data['is_insider']==1 && $data['is_contributor']==1){
            			$seller_type='Insider & Contributor';
            		}
            		if($data['is_insider']==1){
            			$seller_type='Insider';
            		}
            		if($data['is_contributor']==1){
            			$seller_type='contributor';
            		}
	            	$res['seller_type']=ucwords($seller_type);
	            	$res['socials']=$this->getSellerSocials($data['user_id']);
	                array_push($result, $res);
	            }
	        }
		   	return json_encode($result);
		}

		public function load_sellers(){
			$result=array();
			$this->db->select('tbl_sellers.user_id, avatar, full_name, is_insider, is_contributor, is_vendor, is_outsider, email');
			$this->db->from('tbl_sellers');
			$this->db->join('tbl_users', 'tbl_users.id=user_id AND tbl_users.status="0"');
		   	$this->db->where('tbl_sellers.status', '0');
		   	$this->db->order_by('tbl_sellers.id', 'DESC');
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	            	$res=array();
	            	$seller_type="";
            		if($data['is_insider']==1 && $data['is_contributor']==1){
            			$seller_type='Insider & Contributor';
            		}
            		if($data['is_insider']==1 && $data['is_contributor']==1 && $data['is_vendor']==1){
            			$seller_type='Insider, Contributor & Vendor';
            		}
            		if($data['is_insider']==1 && $data['is_contributor']==1 && $data['is_outsider']==1){
            			$seller_type='Insider, Contributor, Outsider & Vendor';
            		}
            		if($data['is_insider']==1){
            			$seller_type='Insider';
            		}
            		if($data['is_contributor']==1){
            			$seller_type='contributor';
            		}
            		if($data['is_vendor']==1){
            			$seller_type='vendor';
            		}
            		if($data['is_outsider']==1){
            			$seller_type='outsider';
            		}
	            	$res['seller_id']=$data['user_id'];
	            	$res['seller_name']=$data['full_name'];
	            	$res['seller_email']=$data['email'];
	            	$res['avatar']= base_url('media/').getSellerBrandAvatar($data['user_id']);
	            	$res['seller_type']=ucwords($seller_type);
	            	$res['total_products']=$this->getSellerTotalProducts($data['user_id']);
	            	$res['socials']=$this->getSellerSocials($data['user_id']);
	                array_push($result, $res);
	            }
	        }
		   	return $result;
		}

		public function getSellerTotalProducts($user_id){
			$list="";
			$this->db->select('id');
			$this->db->from('tbl_products');
		   	$this->db->where('status', '0');
		   	$this->db->where('seller_id', $user_id);
	   		$query = $this->db->get();		   	
		   	return $query->num_rows();
		}

		public function getSellerSocials($user_id){
			$list="";
			$this->db->select('name, link');
			$this->db->from('tbl_seller_social');
		   	$this->db->where('status', '0');
		   	$this->db->where('seller_id', $user_id);
		   	$this->db->limit(5);	   	
		   	
	   		$query = $this->db->get();		   	
		   	if($query->num_rows() > 0){
	            foreach ($query->result_array() as $data) {
	            	$list.=$data['name']."-".$data['link'].";";
	            }
	        }
		   	return $list;
		}

		function load_messages($userID){
        	$result=array();
	        $this->db->select('tbl_msg.id, subject, message, createdDate, is_read');
			$this->db->from('tbl_msg_readers');
			$this->db->join('tbl_msg', 'tbl_msg_readers.msg_id=tbl_msg.id');
		   	$this->db->where('user_id', $userID);
		   	$this->db->where('tbl_msg.status', '0');
		   	$this->db->where('tbl_msg.parent_msg', '');
		   	$this->db->order_by('is_read', 'DESC');
		   	$this->db->order_by('createdDate', 'DESC');		   	
	   		$query = $this->db->get();	
	        foreach ($query->result_array() as $rows) {
	        	$ls=array();
                $ls['msg_id']=$rows['id'];
                $ls['subject']=ucfirst($rows['subject']);
                $ls['message']=ucfirst($rows['message']);
                $ls['sent_date']=date("M d, Y H:i", $rows['createdDate']);
                $ls['is_read']=$rows['is_read'];
                array_push($result, $ls);
            }
	        return $result;
	    }

		function load_chats($userID, $msgID, $subject){
        	$result=array();
	        $this->db->set('is_read', '0');
	   		$this->db->where('msg_id', $msgID);
	   		$this->db->where('user_id', $userID);
	     	$this->db->update('tbl_msg_readers');

	   		$this->db->select('tbl_msg.id, subject, message, createdBy, createdDate, receiver, sender');
	        $this->db->from('tbl_msg');
	        $this->db->join('tbl_msg_readers', 'msg_id=tbl_msg.id', 'LEFT');
	        $this->db->where('(tbl_msg_readers.user_id='.$userID.' OR tbl_msg.createdBy='.$userID.')');
	        $this->db->where('(parent_msg='.$msgID.' OR createdBy='.$userID.') AND parent_msg!=""');
	        $this->db->where('tbl_msg.subject', $subject);
	        $this->db->where('tbl_msg.status', '0');
	        $this->db->where('tbl_msg_readers.status', '0');
	        $this->db->order_by('createdDate', 'ASC');
	        $query = $this->db->get();
	        foreach ($query->result_array() as $rows) {
	        	$ls=array();
	        	$msg_id=$rows['id'];
	        	$createdBy=$rows['createdBy'];
	        	$msg_type="";
	        	$sender_name="";
	        	if($userID==$createdBy){
	        		$msg_type="sent";
	        		$sender_name=getCustomerFullName($rows['createdBy']);
	        	}else{
	        		$msg_type="received";
	        		if($rows['sender']=='admin')
                        $sender_name=getAdminFullName($rows['createdBy']);
                    if($rows['sender']=='seller')
                        $sender_name=getSellerFullName($rows['createdBy']);
                    if($rows['sender']=='customer')
                        $sender_name=getCustomerFullName($rows['createdBy']);
	        	}
                $ls['msg_id']=$rows['id'];
                $ls['msg_type']=$msg_type;
                $ls['message']=$rows['message'];
                $ls['sent_date']=date("M d, Y H:i", $rows['createdDate']);
                $ls['sender_name']=$sender_name;
                $this->db->set('is_read', '0');
                $this->db->where('status', '0');
                $this->db->where('msg_id', $msg_id);
                $this->db->where('user_id', $userID);
                $this->db->update('tbl_msg_readers');
                array_push($result, $ls);

                $this->db->select('id, subject, message, createdBy, createdDate, receiver, sender');
		        $this->db->from('tbl_msg');
		        $this->db->where('parent_msg', $msg_id);
		        $this->db->where('status', '0');
		        $this->db->order_by('createdDate', 'ASC');		        
		        $sql = $this->db->get();          
		        if($sql->num_rows() > 0){
		            foreach ($sql->result_array() as $data) {
		            	$list=array();
		                $subject=$data['subject'];
		                $msgid=$data['id'];
		                $message=$data['message'];
		                $createdBy=$data['createdBy'];
		                $createdDate=$data['createdDate'];
		                $receiver=$data['receiver'];
		                $msgtype="";
	        			$sendername="";
		                if($userID==$createdBy){
			        		$msgtype="sent";
			        		$sendername=getCustomerFullName($data['createdBy']);
			        	}else{
			        		$msgtype="received";
			        		if($data['sender']=='admin')
		                        $sendername=getAdminFullName($data['createdBy']);
		                    if($data['sender']=='seller')
		                        $sendername=getSellerFullName($data['createdBy']);
		                    if($data['sender']=='customer')
		                        $sendername=getCustomerFullName($data['createdBy']);
			        	}
		                $list['msg_id']=$msgid;
		                $list['msg_type']=$msgtype;
		                $list['message']=$message;
		                $list['sent_date']=date("M d, Y H:i", $createdDate);
		                $list['sender_name']=$sendername;
		                $this->db->set('is_read', '0');
		                $this->db->where('status', '0');
		                $this->db->where('msg_id', $msgid);
		                $this->db->where('user_id', $userID);
		                $this->db->update('tbl_msg_readers');
		                array_push($result, $list);
		            }
		        }
            }
	        return $result;
	    }

		function send_message($userID, $parent_msg, $message){
        	$createdDate=time();
        	$subject=""; $receiver="";        	
        	$this->db->select('subject, createdBy');
			$this->db->from('tbl_msg');
		   	$this->db->where('id', $parent_msg);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get();
	        foreach ($query->result_array() as $rows) {
	        	$subject=$rows['subject'];
	        	$receiver=$rows['createdBy'];
            }

            $set=$this->db->insert('tbl_msg', array(
	            'subject' => $subject,
	            'message' => $message,
	            'receiver' => $receiver,
	            'sender' => 'customer',
	            'parent_msg' => $parent_msg,
	            'status' => '0',
	            'createdBy' => $userID,
	            'createdDate' => $createdDate
	        ));

	        if($set){
	        	$msg_id="";
	        	$this->db->select('id');
		        $this->db->where('message', $message);
		        $this->db->where('subject', $subject);
		        $this->db->where('receiver', $receiver);
		        $this->db->where('parent_msg', $parent_msg);
		        $this->db->where('createdBy', $userID);
		        $this->db->where('status', '0');
		        $this->db->order_by('createdDate', 'DESC');
		        $this->db->limit(1);
		        $query = $this->db->get('tbl_msg');
		        if($query->num_rows() > 0){
		            foreach($query->result() as $row){
		            	$msg_id=$row->id;
		            }
		        }
	        	$sent=$this->sendMsg($receiver, $msg_id, 'Customer Reply', $message);
	        	if($sent){
	        		return 'Success';
	        	}else{
	        		return "Failed to send message...";
	        	}
	        	
	        }else{
	        	return $this->db->_error_message();
	        }
	    }

	    public function sendMsg($receiver, $msg_id, $subject, $msg){
			$res=0;
			$this->db->select('email');
	        $this->db->from('tbl_admin');
	        $this->db->where('user_id', $receiver);
	        $this->db->where('status', '0');
	        $query = $this->db->get();
	        if($query->num_rows() > 0){
	            foreach($query->result() as $data){
	            	$user_id=$receiver;
	            	$email=$data->email;
	            	$set=$this->db->insert('tbl_msg_readers', array(
			            'msg_id' => $msg_id,
			            'user_id' => $receiver,
			            'receiver_email' => $email,
			            'account_type' => 'admin',
			            'is_read' => '1',
			            'status' => '0'
			        ));
			        if($set){
			        	$this->load->library('email');
						$this->email->from('info@getvalue.co', 'Get Value Inc - Updates');
						$this->email->to($email);
						$this->email->subject($subject);
						$this->email->message($msg);
						$this->email->send();
			        	$res++;
			        }
	            }
	        }

			if($res>0){
				return true;
			}else{
				return false;
			}
		}

		public function search_products_results($keyword){
			$result=array();
			if($keyword!=""){
				$this->db->select('id, name, image, views, product_status, seller_type, seller_id, summary, description, price, createdDate, product_url');
			   	$this->db->where('status', '0');
			   	$this->db->where("name LIKE '%".$keyword."%'");
			   	$this->db->order_by('createdDate','DESC');
		   		$query = $this->db->get('tbl_products');
			   	if($query->num_rows() > 0){
		            foreach ($query->result_array() as $data) {
		            	$res=array();
		            	$res['product_id']=$data['id'];
		            	$res['product_name']=$data['name'];
		            	$res['image']=base_url('media/products/').$data['image'];
		            	$res['price']=numberFormat($data['price'])." TZS";
		            	$res['summary']=$data['summary'];
		            	$res['total_views']=$data['views'];
		            	$stars=getProductRateValue($data['id']);
		            	$res['rate']=$stars;
		                $res['seller']=getSellerBrandName($data['seller_id']);
		                array_push($result, $res);
		            }
		        }
			}
		   	return json_encode($result);
		}

		public function search_post_results($keyword){
			$result=array();
			if($keyword!=""){
				$this->db->select('tbl_blog_post.id, title, image, tbl_blog_category.category, postedDate, url, views');
				$this->db->from('tbl_blog_post');
				$this->db->join('tbl_blog_category', 'tbl_blog_category.id=tbl_blog_post.category');
			   	$this->db->where('tbl_blog_post.status', '0');
			   	$this->db->where('tbl_blog_post.is_approved', '0');
			   	$this->db->where('tbl_blog_category.status', '0');
			   	$this->db->where("title LIKE '%".$keyword."%'");
			   	$this->db->order_by('postedDate', 'DESC');			   	
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
			}
		   	return json_encode($result);
		}

		public function getTotalReviews($post_id){
			$this->db->select('id');
			$this->db->where('blog_id', $post_id);
		   	$this->db->where('status', '0');
	   		$query = $this->db->get('tbl_blog_comments');	   	
		   	return $query->num_rows();
		}

		public function save_subscription($email){
			$createdDate=time();
			$this->db->select('id');
	        $this->db->where('email',$email);
	        $this->db->where('status','0');
	        $query = $this->db->get('tbl_subscribers');

	        if($query->num_rows() > 0){
	            return ("Thanks for your subscription");
	            exit();
	        }

	        $set=$this->db->insert('tbl_subscribers', array(
	            'email' => $email,
	            'status' => '0',
	            'time' => $createdDate
	        ));

	        if($set){
	        	return 'Success';
	        }else{
	        	return $this->db->_error_message();
	        }
		}

	}
?>