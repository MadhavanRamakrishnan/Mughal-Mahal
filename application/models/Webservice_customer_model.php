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
	
	function checkEmailExist($email,$user_id =""){
		if($user_id !=""){
			$this->db->where('user_id !=',$user_id);
		}
		$this->db->where('email',trim($email));
		$this->db->where('role_id',5);
		$this->db->where("is_active","1");
		$query = $this->db->get('tbl_users');
		return $query->result();
	}

	/**
	 * Description : to check  is customer mobile exist 
	 * Updated by  : Manisha Kanazariya
	 * Created Date: 14/06/18 03:30 PM 
	*/
	function checkPhoneExist($phone,$user_id =""){
		if($user_id !=""){
			$this->db->where('user_id !=',$user_id);
		}
		$this->db->where('contact_no',trim($phone));
		$this->db->where('role_id',5);
		$this->db->where("is_active","1");
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
		$this->db->select('det.product_id,SUM(det.quantity) as qty,pro.product_en_name as name,pro.product_ar_name,pro.description,pro.dish_image');
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
		* updated By  :Manisha Kanazariya
		* Created Date: 06/10/17 07:30 PM 
		* Updated Date: 19/4/18 07:30 PM 
	*/ 
	function getAllDishes($catId='',$locality='',$dishId='',$search ="")
	{

		
		$select ="";
		if($locality !=''){
			$this->db->join("tbl_restaurant_dishes","tbl_restaurant_dishes.fk_dish_id=tbl_dishes.product_id","left");
			$this->db->join("tbl_locality","tbl_locality.restaurant_id=tbl_restaurant_dishes.fk_restaurant_id","left");
			
			$select .="tbl_restaurant_dishes.dish_price as resDishPrice,tbl_restaurant_dishes.choice_id,tbl_restaurant_dishes.choice_price,tbl_restaurant_dishes.is_best_dishes,";
			$this->db->where("tbl_locality.locality_id",$locality);
			$this->db->where("tbl_restaurant_dishes.is_show",1);
		}
		$select .="tbl_dishes.*,tbl_dishes.product_en_name as name,cat.category_id,cat.category_name,cat.image,cat.category_ar_name";
		$this->db->select($select);
		$this->db->join("tbl_dish_category as cat","cat.category_id=tbl_dishes.category_id","left");
		
		$this->db->where("tbl_dishes.is_active","1");
		if($catId !=''){
			$this->db->where("cat.category_id",$catId);
		}
		if($dishId !=''){
			$this->db->where("tbl_dishes.product_id",$dishId);
		}
		if($search != ""){
			if(isset($_COOKIE['lang']) && $_COOKIE['lang'] !='EN' ){
				$this->db->like('tbl_dishes.product_ar_name',$search, 'both'); 
			}else{
				$this->db->like('tbl_dishes.product_en_name',$search, 'both'); 
			}
			
		}
		$this->db->from("tbl_dishes");
		
		$this->db->order_by("tbl_dishes.product_en_name","asc");
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
		$this->db->where("is_profile_updated","1");
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
		$this->db->where('is_profile_updated',1);
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
		$this->db->where('is_profile_updated',1);
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
		$this->db->where('is_profile_updated',1);
		$query=$this->db->get("tbl_users");
		
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
	*updated By   :Manisha Kanazariya
	* Created Date: 11/10/17 01:30 PM 
	* Updated Date: 2/5/18 01:30 PM 
	*/ 
	function getDishDetail($productId,$restaurantId)
	{
		$this->db->from("tbl_restaurant_dishes");
		$this->db->where("tbl_restaurant_dishes.fk_dish_id",$productId);
		$this->db->where("tbl_restaurant_dishes.fk_restaurant_id ",$restaurantId);
		$this->db->join("tbl_dishes as cat","cat.product_id=tbl_restaurant_dishes.fk_dish_id","left");
		$this->db->where("cat.is_active","1");
		$this->db->where("tbl_restaurant_dishes.is_show",1);
		$query = $this->db->get();
		return $query->result();
	}
	/**  
	* Description : get all product  details
	* Created by : Manisha Kanazariya
	* Created Date: 2/6/18 01:30 PM 
	*/ 
	function getCartDishDetail($productId,$locality =null)
	{

		$this->db->from('tbl_locality');
		if($locality)
		{
			$this->db->where("tbl_locality.locality_id IN ($locality)");
		}
		$this->db->join("tbl_restaurant_dishes as res","res.fk_restaurant_id=tbl_locality.restaurant_id","left");
		$this->db->join("tbl_dishes as cat","cat.product_id=res.fk_dish_id","left");
		$this->db->where("res.fk_dish_id",$productId);
		$this->db->where("cat.is_active","1");
		$query = $this->db->get();
		return $query->result();
	}

	/**  
	* Description : update customer delivey address details
	* Created by : Rashmi Nayani
	* Created Date: 13/10/17 04:20 PM 
	*/ 
	function updateDeliveryAddressData($userData,$id)
	{
		$this->db->where("address_id",$id);
		$this->db->update("tbl_customer_delivery_address",$userData);
		return $this->db->affected_rows();
	}

	/**  
		* Description : get customer delivery address
		* Created by : Vaibhav Mehta
		* Created Date: 06/10/17 07:30 PM 
	*/ 
	function getDeliveryAddress($id,$address_id="")
	{
		$this->db->select("tbl_customer_delivery_address.*,tbl_locality.name,tbl_locality.name_ar");
		$this->db->from("tbl_customer_delivery_address");
		$this->db->join("tbl_users","tbl_users.user_id=tbl_customer_delivery_address.user_id","left");
		$this->db->join("tbl_locality","tbl_locality.locality_id=tbl_customer_delivery_address.locality_id","left");
		$this->db->where("tbl_customer_delivery_address.is_active","1");
		$this->db->where("tbl_customer_delivery_address.user_id",$id);
		if($address_id != ""){
			$this->db->where("tbl_customer_delivery_address.address_id",$address_id);
		}
		$query = $this->db->get();
		return $query->result();
	}

	/**  
		* Description : get all products with category details
		* Created by : Vaibhav Mehta
		* Created Date: 06/10/17 07:30 PM 
	*/ 
	function searchDishes($key)
	{
		$this->db->select("tbl_dishes.*,tbl_dishes.product_en_name as name,cat.category_id,cat.category_name,cat.category_name_ar,cat.description,cat.image");
		$this->db->from("tbl_dishes");
		$this->db->join("tbl_dish_category as cat","cat.category_id=tbl_dishes.category_id","left");
		$this->db->where("tbl_dishes.is_active","1");
		$where = "tbl_dishes.name LIKE '%".$key."%'";
		$this->db->where($where);
		$this->db->order_by("tbl_dishes.product_en_name","asc");
		$query = $this->db->get();
		//echo $this->db->last_query(); exit;
		return $query->result();
	}

	/**  
	* Description : get dish choice  details
	* Created by : Rashmi Nayani
	* Created Date: 30/10/17 06:30 PM 
	*/ 
	function getDishChoiceDetail($id,$pid=null)
	{
		$this->db->where("is_active","1");
		$this->db->where("fk_choice_id",$id);
		if ($pid) {
		$this->db->where("fk_dish_id",$pid);
		}
		$query = $this->db->get("tbl_dish_choice");
		return $query->result();
	}
	
	/** 
	 * Description :update order details
	 * Created by : Rashmi Nayani
	 * Created Date: 10/11/17 07:45 PM
	*/ 
	function updateData($data,$id)
	{
		$this->db->where("order_id",$id);
		$this->db->update("tbl_orders",$data);
		return $this->db->affected_rows();
	}	

	/**  
	* Description : get all orders details of customer
	* Created by : Rashmi Nayani
	* Created Date: 31/10/17 4:12 PM 
	*/ 
	function getMyOrders($id,$oid=null)
	{
		$this->db->select('ord.*,od.product_id,od.quantity,od.amount,od.description as od_desc,od.discount_type,od.discount_amount,od.is_complimentry,dish.category_id,dish.product_en_name as name,dish.product_ar_name,dish.en_description as description,dish.ar_description,dish.price,dish.dish_image,dish_ch.price as choice_price,choice.choice_id,choice.choice_name,choice.choice_description,choice_cat.choice_category_name,res.restaurant_name,res.delivery_charge,res.custom_delivery_time,res.address as res_address,res.email as res_email,res.contact_no` as res_contact_no,del.address_type,del.address1 as usr_address,del.other_address,res.res_latitude,res.res_longitude,del.customer_name,del.email as usr_email,del.contact_no as usr_contact_no,del.appartment_no,del.floor,del.block,del.building,del.street,del.avenue,del.zipcode,del.customer_latitude,del.customer_longitude,choice.choice_name_ar,choice_cat.choice_category_name_ar,driver.first_name as driver_first_name,driver.last_name as driver_last_name,driver.contact_no as driver_contact_no');
		$this->db->from('tbl_orders as ord'); 
		$this->db->join('tbl_order_details as od','od.order_id=ord.order_id','left');
		$this->db->join('tbl_dishes as dish','dish.product_id=od.product_id','left');
		$this->db->join('tbl_order_dish_choice as ord_ch','ord_ch.fk_order_detail_id=od.order_detail_id  AND ord_ch.fk_dish_id=od.product_id','left');
		$this->db->join('tbl_dish_choice as dish_ch','dish_ch.fk_dish_id=ord_ch.fk_dish_id AND dish_ch.fk_choice_id=ord_ch.fk_choice_id','left');
		$this->db->join('tbl_choice as choice','choice.choice_id=dish_ch.fk_choice_id','left');
		$this->db->join('tbl_choice_category as choice_cat','choice_cat.choice_category_id=choice.fk_choice_category_id','left');
		$this->db->join('tbl_restaurants as res','res.restaurant_id=ord.restaurant_id','left');
		$this->db->join('tbl_customer_delivery_address as del','del.address_id=ord.selected_delivery_address','left');
		//$this->db->join('countries','countries.country_id=state.country_id','left');
		$this->db->join('tbl_users as driver','ord.delivered_by=driver.user_id','left');

		$this->db->where("ord.order_status >","0");
		$this->db->where("ord.user_id",$id);
		if ($oid) {
			$this->db->where("ord.order_id",$oid);
		}
		$this->db->order_by("ord.order_id","ASC");
		$query = $this->db->get();

		return $query->result();
	}

	/** 
	 * Description : Check password is correct or not for change password request
	 * Created by : Rashmi Nayani
	 * Created Date: 04/11/17 11:30 PM
	*/
	function checkPassword($uid,$password)
	{
		$results = array();
		$this->db->from('tbl_users');
     	$this->db->where('user_id',$uid);
     	$this->db->where('password',$password);
		$result = $this->db->get()->row_array();
		return $result;
	}

	/** 
	 * Description : update users data
	 * Created by : Rashmi Nayani
	 * Created Date: 04/11/17 11:30 PM
	*/
	function updateUserData($userId,$userDetails)
	{
		$eachUserId = explode(',', $userId);

		$this->db->where_in("user_id",$eachUserId);
		$this->db->update("tbl_users",$userDetails);
		return $this->db->affected_rows();
	}

	/**  
	* Description : get all driver details of allocated order
	* Created by : Rashmi Nayani
	* Created Date: 4/11/17 12:15 PM 
	*/ 
	function getDriverDetails($oid)
	{
		$this->db->select('ord.*,drivers.first_name as d_first_name,drivers.last_name as d_last_name,drivers.contact_no as d_contact_no,drivers.driver_latitude,drivers.driver_longitude');
		$this->db->from('tbl_orders as ord'); 
		$this->db->join('tbl_users as drivers','drivers.user_id=ord.delivered_by','left');
		$this->db->where("ord.order_id",$oid);
		$query = $this->db->get();
		return $query->result();
	}

	/**  
	* Description : get custom delivery time based on order and restaurant
	* Created by : Vaibhav Mehta
	* Created Date: 27/11/17 01:45 PM 
	*/ 
	function getOrderData($oid)
	{
		$this->db->select("tbl_orders.order_status,tbl_restaurants.custom_delivery_time,	tbl_orders.restaurant_id,tbl_orders.delivery_charges as delivery_charge,tbl_orders.sequence_no");
		$this->db->from("tbl_orders");
		$this->db->join("tbl_restaurants","tbl_orders.restaurant_id=tbl_restaurants.restaurant_id","left");
		$this->db->where("tbl_orders.is_active","1");
		$this->db->where("tbl_restaurants.is_active","1");
		$this->db->where("tbl_orders.order_id",$oid);

		$query = $this->db->get();
		return $query->result();
	}
	function getAllChoiceData($offset=null,$total=null)
	{
		if($offset && $total){
			$this->db->limit($total,$offset);
		}
		$this->db->where('is_active',1);
		$this->db->order_by('choice_id','asc');
		$query = $this->db->get('tbl_choice');
		return $query->result();
	}
	function getAllChoiceCategoryData($offset=null,$total=null)
	{
		if($offset && $total){
			$this->db->limit($total,$offset);
		}
		$this->db->order_by('choice_category_id','asc');
		$this->db->where('is_active',1);
		$query = $this->db->get('tbl_choice_category');
		return $query->result();
	}
	function getAllDishChoiceData($offset=null,$total=null)
	{
		if($offset && $total){
			$this->db->limit($total,$offset);
		}
		$this->db->order_by('dish_choice_id','asc');
		$this->db->where('is_active',1);
		$query = $this->db->get('tbl_dish_choice');
		return $query->result();
	}
	
	function getAllDishCategoryData($offset=null,$total=null,$locality=null,$search ="")
	{
		if($locality == null){
			if($offset && $total){
			$this->db->limit($total,$offset);
			}
			$this->db->order_by('priority','asc');
			$this->db->where("is_active",1);
			$query = $this->db->get('tbl_dish_category');
			return $query->result();
		}
		else
		{
			$this->db->select('tbl_dish_category.*,tbl_locality.locality_id,tbl_locality.restaurant_id');
			$this->db->join("tbl_restaurant_dishes","tbl_restaurant_dishes.fk_restaurant_id=tbl_locality.restaurant_id","left");
			$this->db->join("tbl_dishes","tbl_dishes.product_id=tbl_restaurant_dishes.fk_dish_id","left");
			$this->db->join('tbl_dish_category','tbl_dish_category.category_id=tbl_dishes.category_id','left');
			if($locality != ""){
				$this->db->where("tbl_locality.locality_id",$locality);
			}
			$this->db->where("tbl_restaurant_dishes.is_show",1);
			$this->db->where("tbl_dishes.is_active",1);
			$this->db->group_by('tbl_dish_category.category_id');
			$this->db->order_by('tbl_dish_category.priority','asc');
			if($search != ""){
				if(isset($_COOKIE['lang']) && $_COOKIE['lang'] !='EN' ){
					$this->db->like('tbl_dishes.product_ar_name',$search, 'both'); 
				}else{
					$this->db->like('tbl_dishes.product_en_name',$search, 'both'); 
				} 
			}
			$query = $this->db->get('tbl_locality');
			return $query->result();
		}
		
		
	}
	
	function getAllDishData($offset=null,$total=null,$type=null)
	{
		$this->db->select('tbl_dishes.*');
		if($offset && $total){
			$this->db->limit($total,$offset);
		}
		if($type=='popular')
		{
			$this->db->where('tbl_dishes.is_popular',1);
		}
		elseif ($type=='groupby') {
			$this->db->select('tbl_dish_category.category_name');	
			$this->db->order_by('category_id','asc');	
			$this->db->join('tbl_dish_category','tbl_dishes.category_id=tbl_dish_category.category_id','left');
		}
		$this->db->where('tbl_dishes.is_active',1);
		$this->db->from('tbl_dishes');
		$query = $this->db->get();
		//echo $this->db->last_query(); exit;
		return $query->result();
	}
	function dishchoiceDetails($choiceId=null)
	{
		$this->db->where("tbl_choice.choice_id",$choiceId);

		$this->db->join('tbl_choice_category','tbl_choice_category.choice_category_id=tbl_choice.fk_choice_category_id','left');
		$this->db->where('tbl_choice.is_active',1);
		$query = $this->db->get("tbl_choice");
		return $query->result();
	}

	/**  
	* Description : get State List base on country id
	* Created by : Umang Kothari
	* Created Date: 28-12-17 04:04 PM 
	*/ 

	function getState($country_id)
	{
		$this->db->where('country_id',$country_id);
		$query =$this->db->get('state');
		return $query->result();
	}
	/**  
	* Description : get City List base on State id
	* Created by : Umang Kothari
	* Created Date: 28-12-17 04:04 PM 
	*/ 
	function getCity($statelist)
	{
		$this->db->where_in('state_id',$statelist);
		$query =$this->db->get('city');
		return $query->result();
	}

	/**  
	* Description : get restaurant near by to given location
	* Created by : Vaibhav Mehta
	* Created Date: 29-12-17 01:00 PM 
	*/ 
	function getSelectedRestaurant($lat,$lon)
	{		
		$this->db->select("(((acos(sin((".$lat."*pi()/180)) * sin((res_latitude*pi()/180))+cos((".$lat."*pi()/180)) *cos((res_latitude*pi()/180)) * cos(((".$lon."- res_longitude)*pi()/180))))*180/pi())*60*1.1515) AS distance,restaurant_id,delivery_charge");
		$this->db->from("tbl_restaurants");
		$this->db->where("is_active","1");
		$this->db->order_by("distance","ASC");

		$query =$this->db->get();
		return $query->result();
	}

	/**  
	* Description : get restaurant near by to given location
	* Created by : Vaibhav Mehta
	* Created Date: 29-12-17 01:00 PM 
	*/ 
	function getSelectedRestaurantRandom()
	{		
		$this->db->select("restaurant_id");
		$this->db->from("tbl_restaurants");
		$this->db->where("is_active","1");
		$this->db->order_by("distance","DESC");
		$this->db->limit("1");

		$query =$this->db->get();
		return $query->result();
	}

	function getRestaurantDefaultTime($oid)
	{
		$this->db->select("tbl_orders.*,tbl_restaurants.custom_delivery_time");
		$this->db->from("tbl_orders");
		$this->db->join("tbl_restaurants","tbl_orders.restaurant_id=tbl_restaurants.restaurant_id","left");
		$this->db->where("tbl_orders.order_id",$oid);
		$query = $this->db->get();
		return $query->result();
	}

	/**  
		* Description : get order ids for already pending data
		* Created by : Vaibhav Mehta
		* Created Date: 04/12/17 03:50 PM 
	*/ 
	function deleteOrderData($oid)
	{
		$this->db->where("order_status <","2");
		$this->db->where_in("order_id",$oid);
		$this->db->delete("tbl_orders");
		return $this->db->affected_rows();
	}

	/**  
		* Description : delete user's old order data
		* Created by : Vaibhav Mehta
		* Created Date: 04/12/17 03:50 PM 
	*/ 
	function getOrderId($userId,$restaurantId)
	{
		$this->db->where("user_id",$userId);
		$this->db->where("restaurant_id",$restaurantId);
		$this->db->where("order_status <","2");
		$query = $this->db->get("tbl_orders");
		return $query->result();
	}

	/**  
		* Description : delete user's old order details data
		* Created by : Vaibhav Mehta
		* Created Date: 04/12/17 03:50 PM 
	*/ 
	function deleteOrderDetailData($oid)
	{
		$this->db->where_in("order_id",$oid);
		$this->db->delete("tbl_order_details");
		return $this->db->affected_rows();
	}

	/**  
		* Description : delete user's old order choice data
		* Created by : Vaibhav Mehta
		* Created Date: 04/12/17 03:50 PM 
	*/ 
	function deleteOrderChoiceData($oid)
	{
		
		$this->db->where("order_dish_choice_id !=","");
		$this->db->where_in("fk_order_id",$oid);
		$this->db->delete("tbl_order_dish_choice");
		return $this->db->affected_rows();
		
	}

	/**  
		* Description : Get locality list 
		* Created by : Umang Kothari
		* Created Date: 04/12/17 03:50 PM 
	*/

	function getLocality($res_id="")
	{
		$this->db->select("tbl_locality.*");
		$this->db->join('tbl_restaurants','tbl_restaurants.restaurant_id=tbl_locality.restaurant_id','left');
		$this->db->where('tbl_restaurants.is_availability','1');

		if($res_id !=""){
			$this->db->where('tbl_locality.restaurant_id',$res_id);
		}
		$query = $this->db->get('tbl_locality');
		return $query->result();

	}

	/**  
		* Description : Get locality list 
		* Created by : Umang Kothari
		* Created Date: 16/03/17 03:50 PM 
	*/
	function getRating($orderId,$table)
	{
		$this->db->where('order_id',$orderId);
		$query = $this->db->get($table);
		return $query->result();
	}

	/**  
		* Description : Get locality list 
		* Created by : Manisha Kanazariya
		* Created Date: 30/04/18 03:50 PM 
	*/
	function listFavouriteDish($user_id,$dishId=""){

		$this->db->where('fk_user_id',$user_id);
		if($dishId !=""){
			$this->db->where('fk_dish_id',$dishId);
		}
		return $this->db->get('tbl_favourite_dishes')->result();
	}

	/**  
		* Description : Get my Order list 
		* Created by : Manisha Kanazariya
		* Created Date: 01/05/18 03:50 PM 
	*/
	function myOrderList($user_id=""){
		
		$this->db->from('tbl_orders as ord'); 
		$this->db->join('tbl_order_details as od','od.order_id=ord.order_id','inner');
		$this->db->join('tbl_dishes as dish','dish.product_id=od.product_id','inner');
		$this->db->where("ord.order_status >","0");
		$this->db->where("ord.user_id",$user_id);
		
		$this->db->order_by("ord.order_id","DESC");
		$query = $this->db->get();
		return $query->result();
	} 

	/** 
	 * Description : Delete duplicate accesstoken
	 * Created by : Vaibhav Mehta
	 * Created Date: 10/07/17 01:40 PM
	*/
	function removeFavouriteDish($user_id,$dish_id)
	{
		$this->db->where("fk_user_id",$user_id);
		$this->db->where("fk_dish_id",$dish_id);
		$this->db->delete("tbl_favourite_dishes");
		return $this->db->affected_rows();
	}

	/**
	 * Description :Get Restaurant full information(restaurant details ,locality,opening time)
	 * Created by : manisha Kanazariya
	 * Created Date: 22/05/18 1:30 PM 
	*/
	function getRestaurantDetail($res_id =""){

		$this->db->select("res.*,tbl_locality.locality_id,media.media_name,m_users.first_name as m_fname,m_users.last_name as m_lname,o_users.first_name as o_fname,o_users.last_name as o_lname,o_users.user_id as o_uid,m_users.user_id as m_uid");
		$this->db->from('tbl_restaurants as res');
		$this->db->join('tbl_restaurant_media as media','media.restaurant_id=res.restaurant_id','left');
		$this->db->join('tbl_restaurant_owners','tbl_restaurant_owners.restaurant_id=res.restaurant_id','left');
		$this->db->join('tbl_restaurant_managers','tbl_restaurant_managers.restaurant_id=res.restaurant_id','left');
		$this->db->join('tbl_users as m_users','m_users.user_id=tbl_restaurant_managers.fk_user_id','left');
		$this->db->join('tbl_users as o_users','o_users.user_id=tbl_restaurant_owners.fk_user_id','left');
		
		$this->db->join('tbl_locality','tbl_locality.lat=res.res_latitude','left');
		$this->db->where('res.is_active','1');
		
		$query=$this->db->get();
		
		return $query->result();

	}


	function getRestaurantTime($res_id ="",$is_approved=""){
		$this->db->where('tbl_restaurant_days.fk_restaurant_id',$res_id);
		$this->db->join('tbl_restaurant_days_time','tbl_restaurant_days_time.fk_restaurant_days_id=tbl_restaurant_days.restaurant_days_id','left');
		$this->db->order_by('tbl_restaurant_days_time.restaurant_days_time_id','ASC');
		$this->db->from('tbl_restaurant_days');
		if($is_approved != ""){
    		$this->db->where('tbl_restaurant_days_time.is_approved',0);
    	}
		$query =$this->db->get();
		return $query->result();
	}

	function getLocalityData($delivery_address_id){
		$this->db->join('tbl_locality','tbl_locality.locality_id =cst.locality_id','left');
		$this->db->where('cst.address_id',$delivery_address_id);
		$this->db->from('tbl_customer_delivery_address as cst');

		return $this->db->get()->result();
	}
	/**
	 * [getBestDishes description:get all restaurant best dishes]
	 * @author manisha Kanazariya
	 * @Created Date   2018-10-05T17:59:35+0530
	 */
	function countBestDishes($restaurantId)
	{
		$this->db->where('fk_restaurant_id',$restaurantId);
		$this->db->where('is_best_dishes',1);
		$this->db->where("is_show",1);
		$this->db->select('count(id) as total');
		$query =$this->db->get('tbl_restaurant_dishes');
		return $query->result();

	}

	/**
	 * function to build query add get dish for  restaurant 
	 * @author Manisha Kanazariya 
	 * Created date:23-04-2018 11:45 PM
	 */
	function getRestaurantDishes($restaurantId ="")
	{
		if($restaurantId !=""){
			$this->db->where("fk_restaurant_id",$restaurantId);
		}
		$this->db->where("is_show",1);
		return $this->db->get("tbl_restaurant_dishes")->result();
	}

	/**
	 * function to build query update order type
	 * @author Devesh Khandelwal
	 * Created date:21-02-2020 04:08 PM
	 */
	function updateOrder($orderId, $data)
	{
		$this->db->where('order_id', $orderId);

		$this->db->update('tbl_orders', $data);
		return $this->db->affected_rows();
	}
}
