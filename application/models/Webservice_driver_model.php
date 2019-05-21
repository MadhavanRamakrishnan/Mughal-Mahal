<?php
/** 
 * Author 				: itoneclick.com 
 * Copyright 			: itoneclick.com 
 * Created by 			: Vaibhav Mehta
 * Created Date 		: 24 October 2017 11:00 AM
 * Description 			: Use for various webservice like login,logout,forgot password,etc. 
 */ 
class Webservice_driver_model extends CI_Model
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
		$this->db->where('role_id',4);
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
			$this->db->where("tbl_users.user_id",$userId);
		}
		$this->db->select('tbl_users.*,meta.access_token,meta.device_type,meta.device_token');
		$this->db->join('tbl_user_meta as meta','meta.user_id=tbl_users.user_id','left');
		$this->db->group_by('tbl_users.user_id');
		$query=$this->db->get("tbl_users");
		//echo $this->db->last_query(); exit;
		return $query->result();
	}
	
	/**  
	* Description : get all orders details allocated to driver
	* Created by : Rashmi Nayani
	* Created Date: 30/10/17 12:30 PM 
	*/ 
	function getOrdersAllocatedtoDriver($id,$oid=null)
	{

		$today    =date('Y-m-d H:i:s',strtotime('23:59:59'));
		$last_day =date('Y-m-d H:i:s',strtotime(date('Y-m-d',strtotime('-7 days'))));
		
		$this->db->select('ord.*,od.product_id,od.quantity,od.amount,od.discount_type,od.discount_amount,od.is_complimentry,dish.category_id,dish.product_en_name as name,dish.product_ar_name,dish.en_description as description,dish.ar_description,dish.price,dish.dish_image,del.customer_name,del.user_id,del.contact_no,del.email,res.restaurant_name,res.address,res.contact_no as res_contact_no,res.email as res_email,res.custom_delivery_time,drivers.first_name as d_first_name,drivers.last_name as d_last_name,drivers.contact_no as d_contact_no,del.address_id,del.address1,del.address2,del.customer_name,del.email as del_email,del.contact_no as delivery_contact,del.zipcode,del.customer_latitude,del.customer_longitude');
		$this->db->from('tbl_orders as ord'); 
		$this->db->join('tbl_order_details as od','od.order_id=ord.order_id','left');
		$this->db->join('tbl_dishes as dish','dish.product_id=od.product_id','left');
		$this->db->join('tbl_users as usr','usr.user_id=ord.user_id','left');
		$this->db->join('tbl_restaurants as res','res.restaurant_id=ord.restaurant_id','left');
		$this->db->join('tbl_users as drivers','drivers.user_id=ord.delivered_by','left');
		$this->db->join('tbl_customer_delivery_address as del','del.user_id=ord.user_id AND del.address_id=ord.selected_delivery_address','left');
		$this->db->join('tbl_order_dish_choice as ord_ch','ord_ch.fk_order_detail_id=od.order_detail_id  AND ord_ch.fk_dish_id=od.product_id','left');
		$this->db->join('tbl_dish_choice as dish_ch','dish_ch.fk_dish_id=ord_ch.fk_dish_id','left');
		$this->db->where("ord.order_placed_time >= '$last_day' AND ord.order_placed_time <= '$today'");
		$this->db->where("ord.order_status >=","4");
		$this->db->where("ord.order_status <=","9");
		$this->db->where("ord.delivered_by",$id);
		$this->db->order_by("ord.order_id","DESC");

		//$this->db->group_by("ord_ch.fk_dish_id");
		$query = $this->db->get();
		return $query->result();

	}


	/**  
	* Description : get all driver details
	* Created by : Rashmi Nayani
	* Created Date: 3/11/17 12:30 PM 
	*/ 
	function getDriverDetails($id)
	{
		$this->db->where("user_id",$id);
		$this->db->where("is_active",'1');
		$query=$this->db->get("tbl_users");
		return $query->result();
	}

	/**  
	* Description : get all driver details
	* Created by : Rashmi Nayani
	* Created Date: 3/11/17 12:30 PM 
	*/ 
	function getOrderDetails($id)
	{
		$this->db->where("order_id",$id);
		$this->db->where("is_active",'1');
		$query=$this->db->get("tbl_orders");
		return $query->result();
	}

	/**  
	* Description : update driver details
	* Created by : Rashmi Nayani
	* Created Date: 3/11/17 12:30 PM 
	*/ 
	function updateDriverProfile($id,$data)
	{
		$this->db->where("user_id",$id);
		$this->db->update("tbl_users",$data);
		return $this->db->affected_rows();
	}
	/**  
	* Description : update driver details
	* Created by : Rashmi Nayani
	* Created Date: 3/11/17 3:45 PM 
	*/ 
	function updateOrderStatus($data,$id)
	{
		$this->db->where("order_id",$id);
		$this->db->update("tbl_orders",$data);
		return $this->db->affected_rows();
	}

	/**  
	* Description : get custom delivery time based on order and restaurant
	* Created by : Vaibhav Mehta
	* Created Date: 27/11/17 01:45 PM 
	*/ 
	function getOrderData($oid)
	{
		$this->db->select("tbl_orders.order_status,tbl_restaurants.custom_delivery_time");
		$this->db->from("tbl_orders");
		$this->db->join("tbl_restaurants","tbl_orders.restaurant_id=tbl_restaurants.restaurant_id","left");
		$this->db->where("tbl_orders.is_active","1");
		$this->db->where("tbl_restaurants.is_active","1");
		$this->db->where("tbl_orders.order_id",$oid);

		$query = $this->db->get();
		return $query->result();
	}

	/** 
	 * Description : Check password is correct or not for change password request
	 * Created by : Umang Kothari
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
	 * Created by : Umang Kothari
	 * Created Date: 04/11/17 11:30 PM
	*/
	function updateUserData($userId,$userDetails)
	{
		$this->db->where("user_id",$userId);
		$this->db->update("tbl_users",$userDetails);
		//echo $this->db->last_query(); exit;
		return $this->db->affected_rows();
	}

	/**  
	* Description : get order details of customer for sending email
	* Created by : Vaibhav Mehta
	* Created Date: 31/10/17 4:12 PM 
	*/ 
	function getOrderDetailsForDeliveryEmail($oid)
	{
		$this->db->select('ord.*,od.product_id,od.quantity,od.amount,od.discount_type,od.discount_amount,od.is_complimentry,dish.category_id,dish.product_en_name as name,dish.price,res.restaurant_name,res.address as res_address,res.email as res_email,res.contact_no` as res_contact_no,del.address1 as usr_address,del.customer_name,del.email as usr_email,del.contact_no as usr_contact_no,del.zipcode,usr.first_name as user_first_name,usr.last_name as user_last_name,usr.email as user_email,del.customer_latitude,del.customer_longitude');
		$this->db->from('tbl_orders as ord'); 
		$this->db->join('tbl_order_details as od','od.order_id=ord.order_id','left');
		$this->db->join('tbl_dishes as dish','dish.product_id=od.product_id','left');
		$this->db->join('tbl_order_dish_choice as ord_ch','ord_ch.fk_order_detail_id=od.order_detail_id  AND ord_ch.fk_dish_id=od.product_id','left');
		$this->db->join('tbl_restaurants as res','res.restaurant_id=ord.restaurant_id','left');
		$this->db->join('tbl_customer_delivery_address as del','del.user_id=ord.user_id and ord.selected_delivery_address = del.address_id','left');
		$this->db->join('tbl_users as usr','ord.user_id=usr.user_id','left');

		$this->db->where("ord.order_status >","1");
		if ($oid) {
			$this->db->where("ord.order_id",$oid);
		}
		$this->db->order_by("ord.order_id","ASC");
		$query = $this->db->get();
		//echo $this->db->last_query(); exit;
		return $query->result();
	}

	/** 
	 * Description : Build query to get user details without group
	 * Input :  facebook id or google id
	 * Output :  return user details
	 * Created by : Umang Kothari
	 * Created Date: 19/03/18 01:00 PM 
	 */ 
	
	function getUserdetailsonly($userId,$type=null)
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
			$this->db->where("tbl_users.user_id",$userId);
		}
		$query=$this->db->get("tbl_users");
		//echo $this->db->last_query(); exit;
		return $query->result();
	}

	/**
	 * this function call for getting details of restaurants
	 * @author manisha Kanazariya
	 * Created date: 13-04-2018 6:50 PM
	 */
	function getAllRestaurantDetails($id)
	{
		$this->db->select("res.*,tbl_locality.locality_id,media.media_name,m_users.first_name as m_fname,m_users.last_name as m_lname,o_users.first_name as o_fname,o_users.last_name as o_lname,o_users.user_id as o_uid,m_users.user_id as m_uid");
		$this->db->from('tbl_restaurants as res');
		$this->db->join('tbl_restaurant_media as media','media.restaurant_id=res.restaurant_id','left');
		$this->db->join('tbl_restaurant_owners','tbl_restaurant_owners.restaurant_id=res.restaurant_id','left');
		$this->db->join('tbl_restaurant_managers','tbl_restaurant_managers.restaurant_id=res.restaurant_id','left');
		$this->db->join('tbl_users as m_users','m_users.user_id=tbl_restaurant_managers.fk_user_id','left');
		$this->db->join('tbl_users as o_users','o_users.user_id=tbl_restaurant_owners.fk_user_id','left');
		
		$this->db->join('tbl_locality','tbl_locality.lat=res.res_latitude','left');
		$this->db->where('res.is_active','1');
		if ($id) {
			$this->db->where('res.restaurant_id',$id);
		}
		
		$query=$this->db->get();
		
		return $query->result();
	}

	/**
	 * Description : for getting drivers today order Details
	 * Created by :Manisha Kanazariya
	 * Created Date: 20/06/18 05:20 PM 
	*/

	function getTodayOrderData($user_id,$status=null){
		$today =date('Y-m-d');
		
		if($status == 4)
		{
			//total pending  orders
			$this->db->select("COUNT(order_id) as total_pending");
			$this->db->where('order_status >3  AND order_status <7 ');
		}
		else if($status == 7)
		{
			//total completed and total amount of orders
			$this->db->select("SUM(total_price) as total_amount,COUNT(order_id) as total_completed");
			$this->db->where('order_status >=',$status);
		}
		else if($status == 8)
		{
			//total disputeds 
			$this->db->select("COUNT(order_id) as total_disputed");
			$this->db->where('order_status',$status);
		}
		else
		{
			//total orders
			$this->db->select("count(order_id) as total_order");
			$this->db->where('order_status',$status);
		}

		/*$this->db->where("order_placed_time LIKE '%$today%'");*/
		$this->db->where("order_confirmed_time LIKE '%$today%'");
		$this->db->where('delivered_by',$user_id);
		$this->db->from('tbl_orders');
		$query = $this->db->get();
		return $query->row();
	}
	/**
	 * [checkForUpdateVersion Get Latest Version]
	 * @author Hardik Ghadshi
	 * @Created Date   2019-03-12T17:14:39+0530
	 * @param   [type] $device_type             [1=Android, 2= IOS]
	 * @param   [type] $app                     [description]
	 * @return  [type]                          [description]
	 */
	public function checkForUpdateVersion($device_type, $app)
	{
		if($device_type==1)
		{
			$this->db->select("android_ver as latest_ver,android_update_status as status");
		}
		elseif ($device_type==2) {
			$this->db->select("ios_ver as latest_ver,ios_update_status as status");
		}

		$this->db->where("app", $app);
		$this->db->order_by('id',"DESC");
		$this->db->limit("1");

		$query = $this->db->get("app_version");

		return $query->row();
	}

    /**
     * Descripation: Check force update is available or not, and get Last force update version of app
     * @author Hardik Ghadshi
     * Created date: 18-10-2018
     * Parameter: Device Type, app name
     */
    public function getLstForceupdatever($device_type, $app)
    {
    	if($device_type==1)
    	{
    		$this->db->select("last_force_update_android as latest_ver,android_update_status as status");

    	}
    	elseif ($device_type==2) {
    		$this->db->select("last_force_update_ios as latest_ver,ios_update_status as status");
    	}

    	$this->db->where("app", $app);
    	$this->db->order_by('id',"DESC");
    	$this->db->limit("1");

    	$query = $this->db->get("app_version");

    	return $query->row();
    }
}
