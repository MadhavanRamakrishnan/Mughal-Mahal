<?php
/** 
 * Author 				: itoneclick.com 
 * Copyright 			: itoneclick.com 
 * Created by 			: Vaibhav Mehta
 * Created Date 		: 24 August 2017 10:00 AM
 * Description 			: Use for various webservice like login,logout,forgot password,etc. 
 * Updated by 			: 
 * Updated Date 		: 
 * Reason for update 	: 
 */ 
class Webservice_customer_model extends CI_Model
{
	/**
	 * Description : Use to manage user side web serive query 
	 * Created by : Vaibhav Mehta
	 * Created Date: 18/05/17 06:00 PM 
	*/ 
	function checkPhoneNumber($phone,$role,$ccd = null)
	{
		if($ccd)
		{
			$this->db->where("country_code",$ccd);
		}
		$this->db->where("contact_no",$phone);
		$this->db->where("is_active","1");
		$this->db->where("role_id",$role);
		$query = $this->db->get("tbl_users");
		return $query->result();
	}

	/** 
	 * Model Name 		: Webservice_model
	 * Description 		: For checking duplicate of emails arrive
	 * Input 			: Mention Input parameters email
	 * Output 			: Fetch if email already exists
	 * Created by 		: Vaibhav Mehta
	 * Created Date 	: 24 August 2017 10:00 AM
	*/
	function checkEmailExist($email){
		$this->db->where('email',$email);
		$query = $this->db->get('tbl_users');
		return $query->result();
	}

	/**
	 * Description : Check same access token is available or not
	 * Created by : Vaibhav Mehta
	 * Created Date: 10/07/17 01:30 PM 
	*/ 
	function checkDeviceTokenAvailable($deviceToken)
	{
		$this->db->where("device_token",$deviceToken);
		$query=$this->db->get("tbl_user_meta");
		return $query->result();
	}

	/** 
	 * Description : Delete duplicate accesstoken
	 * Created by : Vaibhav Mehta
	 * Created Date: 10/07/17 01:40 PM
	*/
	function deleteDeviceToken($deviceToken)
	{
		$this->db->where("device_token",$deviceToken);
		$this->db->delete("tbl_user_meta");
		return $this->db->affected_rows();
	}

	/**  
		* Description : Insert data in table name provided 
		* Created by : Vaibhav Mehta
		* Created Date: 18/05/17 07:20 PM 
	*/ 
	function insertData($tableName,$userData)
	{
		$this->db->insert($tableName,$userData);
		return $this->db->insert_id();
	}

	/**  
		* Description : delete user's device information
		* Input : provide user id
		* Output : User is logged out so this service delete user's meta details 
		* Created by : Vaibhav Mehta
		* Created Date: 27/09/17 06:50 PM 
	*/ 
	function deleteUserMetaData($userId,$deviceToken=null)
	{
		if($deviceToken)
		{
			$this->db->where("device_token",$deviceToken);
		}
		$this->db->where("user_id",$userId);
		$this->db->delete("tbl_user_meta");

		return $this->db->affected_rows();
	}

	/** 
	 * Description : Use to update user details
	 * Input : Mention Input parameters user id and userdata array
	 * Output : User profile details has been updated
	 * Created by : Vaibhav Mehta
	 * Created Date: 05/10/17 03:15 PM
	*/ 
	function updateUserProfile($userId,$userData)
	{
		$this->db->where("user_id",$userId);
		$this->db->update("tbl_users",$userData);
		return $this->db->affected_rows();
	}

	/**  
		* Description : Get users other detail like access token and device token
		* Created by : Vaibhav Mehta
		* Created Date: 05/10/17 06:45 PM 
	*/ 
	function getUserMetaData($userId,$accessToken=null)
	{
		if($accessToken)
		{
			$this->db->where("access_token",$accessToken);
		}
		$this->db->where("user_id",$userId);
		$query=$this->db->get("tbl_user_meta");

		return $query->result();		
	}

	/**  
		* Description : check user account is deleted or not
		* Created by : Vaibhav Mehta
		* Created Date: 24/05/17 04:30 PM 
	*/ 
	function chkAccountDelete($userId)
	{
		$this->db->where("user_id",$userId);
		$this->db->where("is_deleted","1");
		$query = $this->db->get("tbl_users");
		return $query->result();
	}

	/**  
		* Description : get all cuisines with details
		* Created by : Vaibhav Mehta
		* Created Date: 06/10/17 04:00 PM 
	*/ 
	function getAllCuisines($offset,$total)
	{
		$this->db->where('is_active','1');
		$this->db->order_by('cuisine_name','asc');
		$this->db->limit($total,$offset);
		$query = $this->db->get('tbl_cuisines');
		return $query->result();
	}

	/**  
		* Description : get fast moving dishes from all the restaurants
		* Created by : Vaibhav Mehta
		* Created Date: 06/10/17 04:00 PM 
	*/ 
	function getFastMovingDishes($offset,$total)
	{		
		$date = date("Y-m-d H:i:s");
		$week = date('Y-m-d H:i:s', strtotime('-30 days'));
		$this->db->select('det.product_id,SUM(det.quantity) as qty,pro.name,pro.description,pro.dish_image');
		$this->db->from('tbl_order_details as det');
		$this->db->join('tbl_orders as ord','ord.order_id=det.order_id','left');
		$this->db->join('tbl_dishes as pro','pro.product_id=det.product_id','left');
		$this->db->where('det.is_active','1');
		$where = "det.created_date BETWEEN ".$week." AND ".$date;
		$this->db->group_by('det.product_id');
		$this->db->order_by('qty','desc');
		$this->db->limit($total,$offset);
		$query = $this->db->get();
		//echo $this->db->last_query(); exit;
		return $query->result();
	}

	/**  
		* Description : get all cuisines with details
		* Created by : Vaibhav Mehta
		* Created Date: 06/10/17 04:00 PM 
	*/ 
	function getAllDishCategories($offset=null,$total=null)
	{
		if($offset && $total)
		{
			$this->db->limit($total,$offset);
		}
		$this->db->where('is_active','1');
		$this->db->order_by('category_name','asc');
		$query = $this->db->get('tbl_dish_category');
		return $query->result();
	}

	/**  
		* Description : get all products with category details
		* Created by : Vaibhav Mehta
		* Created Date: 06/10/17 07:30 PM 
	*/ 
	function getAllDishes($catId)
	{
		$this->db->select("tbl_dishes.*,cat.category_id,cat.category_name,cat.description,cat.image");
		$this->db->from("tbl_dishes");
		$this->db->join("tbl_dish_category as cat","cat.category_id=tbl_dishes.category_id","left");
		$this->db->where("tbl_dishes.is_active","1");
		$this->db->where("cat.category_id",$catId);
		$this->db->order_by("tbl_dishes.name","asc");
		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * Description : Use to manage user side web serive query 
	 * Input : Mention Input parameters for login email/phone,password 
	 * Output : User has logged in if he puts correct credentials
	 * Created by : Vaibhav Mehta
	 * Created Date: 09/10/17 05:00 PM 
	*/ 
	function normalUserLogin($email,$password,$type=null,$role=null)
	{
		if($type==1)
		{
			$this->db->where("contact_no",$email);
		}
		else if($type==2)
		{
			$this->db->where("email",$email);
		}
		if($role)
		{
			$this->db->where("role_id",$role);
		}
		$this->db->where("password",md5($password));
		$this->db->where("is_active","1");
		$this->db->order_by("user_id","desc");
		$query=$this->db->get("tbl_users");
		return $query->result();
	}

	/** 
	 * Model Name 		: Webservice_model
	 * Description 		: For checking duplicate of facebook id arrives
	 * Created by 		: Vaibhav Mehta
	 * Created Date 	: 09/10/17 05:00 PM 
	*/
	function checkFacebookIdExists($fbId)
	{
		$this->db->where('facebook_id',$fbId);
		$query = $this->db->get('tbl_users');
		return $query->result();
	}

	/** 
	 * Model Name 		: Webservice_model
	 * Description 		: For checking duplicate of emails arrive
	 * Input 			: Mention Input parameters email
	 * Output 			: Fetch if email already exists
	 * Created by 		: Vaibhav Mehta
	 * Created Date 	: 09/10/17 05:00 PM 
	*/
	function checkGoogleIdExists($gId)
	{
		$this->db->where('google_id',$gId);
		$query = $this->db->get('tbl_users');
		return $query->result();
	}

	/** 
	 * Description : Build query to get user details
	 * Input :  facebook id or google id
	 * Output :  return user details
	 * Created by : Vaibhav Mehta
	 * Created Date: 10/10/17 01:00 PM 
	 */ 
	function getUserdetails($userId,$type=null)
	{
		if($type==1)
		{
			$this->db->where("facebook_id",$userId);
		}
		else if($type==2)
		{
			$this->db->where("google_id",$userId);
		}
		else
		{
			$this->db->where("user_id",$userId);
		}
		$this->db->select('tbl_users.*,meta.access_token,meta.device_type,meta.device_token');
		$this->db->join('tbl_user_meta as meta','meta.user_id=tbl_users.user_id','left');
		$this->db->group_by('tbl_users.user_id');
		$query=$this->db->get("tbl_users");
		//echo $this->db->last_query(); exit;
		return $query->result();
	}
	/**  
		* Description : check user access token 
		* Created by : Rashmi Nayani
		* Created Date: 11/10/17 01:05 PM 
	*/ 
	function checkAccessToken($userId,$accessToken)
	{
		$this->db->select('access_token');
		$this->db->where("user_id",$userId);
		$this->db->where("access_token",$accessToken);
		$this->db->where("is_active","1");
		$query = $this->db->get("tbl_user_meta");
		return $query->result();
	}

	/**  
	* Description : get all product  details
	* Created by : Rashmi Nayani
	* Created Date: 11/10/17 01:30 PM 
	*/ 
	function getDishDetail($id)
	{
		$this->db->select("tbl_dishes.*,cat.category_id,cat.category_name");
		$this->db->from("tbl_dishes");
		$this->db->join("tbl_dish_category as cat","cat.category_id=tbl_dishes.category_id","left");
		$this->db->where("tbl_dishes.is_active","1");
		$this->db->where("tbl_dishes.product_id",$id);
		$this->db->order_by("tbl_dishes.name","asc");
		$query = $this->db->get();
		return $query->result();
	}
}