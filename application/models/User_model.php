<?php
/**
 * Model Name 		: User_model
 * Descripation 	: Use to manage user data
 * @author 			: Vaibhav Mehta
 * Created date 	: 11 September 2017 3:50PM
 */
class User_model extends CI_Model
{
	
	/**
	 * Descripation 	: Use to get total count for Admin, Angel or Survivor
	 * @author 			: Vaibhav Mehta
	 * Created date 	: 11 September 2017 3:50PM
	 */
	function getHelperAndAdminCOUNT($user_id=null){
		$this->db->select('COUNT(user_id) as countUser');
		$this->db->where('is_active','1');
		if($user_id !=null){
			$this->db->where('tbl_users.fk_role_id ','4');
		}else{
			$this->db->where('tbl_users.fk_role_id !=','4');
		}
		
		$query=$this->db->get('tbl_users');
		return $query->result();

	}


	/**
	 * Descripation 	: Use to get total count for Admin, Angel or Survivor
	 * @author 			: Vaibhav Mehta
	 * Created date 	: 11 September 2017 3:50PM
	 */
	function getHelperAndAdmin($limit=null,$offset=null,$user_id=null){
		$this->db->select('tbl_users.*,tbl_roles.role_name');
		$this->db->from('tbl_users');
		$this->db->join("tbl_roles","tbl_roles.role_id=tbl_users.fk_role_id","left");
		$this->db->where('tbl_users.is_active','1');
		if($user_id !=null){
			$this->db->where('tbl_users.fk_role_id ','4');
		}else{
			$this->db->where('tbl_users.fk_role_id !=','4');
		}
		if($limit !=null || $offset !=null){
			$this->db->limit($limit,$offset);
		}
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get();
		//echo $this->db->last_query();
		return $query->result();

	}

	/**
	 * Descripation 	: Use to get all roll 
	 * @author 			: Vaibhav Mehta
	 * Created date 	: 11 September 2017 6:50PM
	 */
	function getUserRole(){
		$this->db->where('is_active','1');
		$this->db->where('role_id !=','4');
		$query=$this->db->get('tbl_roles');
		return $query->result();

	}


	/**
	 * function to build query to save  user
	 * @author Suresh Suthar
	 */
	function addUser($userData){

		$this->db->insert("tbl_users",$userData);
		return $this->db->insert_id();
	}

	/**
	 * function to build query to get user details
	 * @author Suresh Suthar
	 */
	function getUserDetails($userId=null){
		$this->db->join('tbl_roles','tbl_roles.role_id=tbl_users.fk_role_id');
		$this->db->where('tbl_users.user_id',$userId);
		$query=$this->db->get('tbl_users');
		return $query->result();
	}

	 /**
	 * function to build query to get user having passed email
	 * @author Manisha Kanazariya
	 */
	function checkEmail($email=null){

		$this->db->where('email',$email);

		$query=$this->db->get('tbl_users');

		return $query->result();
	}
	/**
	 * function to build query to update user details
	 * @author Suresh Suthar
	 */
	function updateUser($userData,$userId){

		$this->db->where('user_id',$userId);

		$this->db->update('tbl_users',$userData);
		// echo $this->db->last_query();exit;
		return $this->db->affected_rows();
	}

	/**
	 * function to build query to Delete user data
	 * @author Manisha Kanazariya
	 */
	function deleteUserdata($userId,$userData){
		$this->db->where("user_id",$userId);
		$this->db->update("tbl_users",$userData);
		return $this->db->affected_rows();

	}

	/**
	 * function to build query to get user counts
	 * @author Suresh Suthar
	 */
	function totalUser(){

		$this->db->select("count(*) as total_user");

		$this->db->where("is_active","1");

		$this->db->where("role !=","1");

		$query=$this->db->get("tbl_users");

		//echo $this->db->last_query();exit();
		return $query->result();
	}

	/**
	 * function to build query to get user security token 
	 * @author Suresh Suthar
	 */
	function getUserDetailsBySecurityToken($securityToken,$user_role =""){

		if($user_role !=""){
			$this->db->where('role_id',$user_role);
		}
		$this->db->where("security_token",$securityToken);

		$query=$this->db->get("tbl_users");	
		return $query->result();
	}

	/**
	 * function to build query to update security token 
	 * @author Suresh Suthar
	 */
	function updateUserDetailsBYSecurityToken($securityToken,$userData){

		$this->db->where("security_token",$securityToken);

		$this->db->update("tbl_users",$userData);

		return $this->db->affected_rows();
	}

	/**
	 * function to build query to get all customers count
	 * @author Rashmi Nayani
	 * Created date: 10/10/2017 6:00 PM
	 */
	function getCustomer($resId,$limit=null,$orderBy=null,$type=null)
	{
		if($resId)
		{
			$this->db->select("tbl_orders.restaurant_id");
			$this->db->join("tbl_orders","tbl_users.user_id=tbl_orders.user_id AND tbl_orders.restaurant_id=$resId","left");
			$this->db->where("tbl_orders.restaurant_id",$resId);
			$this->db->group_by("tbl_users.user_id");
		}
		$custRole = $this->config->item('customer_role');

		if($type == "count"){
			$this->db->select("COUNT(*) as countCustomer");
		}else{
			$this->db->select("tbl_users.*");
		}
		$this->db->from("tbl_users");
		$this->db->where('role_id',$custRole);
		$this->db->where('tbl_users.is_active','1');
		if ($orderBy) {
			$this->db->order_by('tbl_users.user_id',$orderBy);
		}
		if ($limit) {
			$this->db->limit($limit);
		}
		$query = $this->db->get();
		//echo $this->db->last_query(); exit;
		return $query->result();
	}
	/**
	 * function to build query to get all orders count
	 * @author Rashmi Nayani
	 * Created date: 10/10/2017 6:00 PM
	 */
	function getOrder($resId,$limit=null,$orderBy=null, $orderStatus=null)
	{
		if($resId)
		{
			$this->db->where("tbl_orders.restaurant_id",$resId);
		}
		$this->db->select("tbl_orders.*,tbl_users.first_name,tbl_users.last_name,tbl_users.user_id");

		$this->db->join('tbl_users','tbl_users.user_id = tbl_orders.user_id','left');
		$this->db->where('tbl_orders.is_active','1');
		$this->db->where('tbl_orders.order_status > ','0');
		/*if($orderStatus){
			$this->db->where('tbl_orders.order_status > ','0');
		}*/
		if ($orderBy) {
			$this->db->group_by('tbl_orders.order_id',$orderBy);
		}
		if ($limit) {
			$this->db->limit($limit);
		}
		$this->db->order_by("order_id","desc");
		$query = $this->db->get('tbl_orders');
		//echo $this->db->last_query(); exit;
		return $query->result();
		
	}/**
	 * function to build query to get all drivers count
	 * @author Rashmi Nayani
	 * Created date: 10/10/2017 6:00 PM
	 */
	function getDriverCount($resId)
	{
		if($resId)
		{
			$this->db->where("tbl_users.fk_restaurant_id",$resId);
		}
		$driverRole = $this->config->item('driver_role');
		$this->db->where('is_active','1');
		$this->db->where('role_id',$driverRole);
		$query = $this->db->get('tbl_users');
		//echo $this->db->last_query(); exit;
		return $query->num_rows();
	}
	/**
	 * function to build query to get all vehicles count
	 * @author Rashmi Nayani
	 * Created date: 10/10/2017 6:00 PM
	 */
	function getVehicleCount($resId){
		$this->db->where('is_active','1');
		if($resId){
			$this->db->where('restaurant_id',$resId);
		}
		$query = $this->db->get('tbl_vehicles');
		return $query->num_rows();
	}
    
    /**
	 * getting allocated restaurant of the user to restaurant owner
	 * @author Vaibhav Mehta
	 * Created date: 30/12/2017 07:00 PM
	 */
   	function getRestaurantIdOfOwner($uid,$rid)
   	{
   		$this->db->select("tbl_restaurant_owners.restaurant_id");
   		$this->db->from("tbl_restaurant_owners");
   		$this->db->join("tbl_users","tbl_users.user_id=tbl_restaurant_owners.fk_user_id","left");
   		$this->db->join("tbl_restaurants","tbl_restaurant_owners.restaurant_id=tbl_restaurants.restaurant_id","left");
   		$this->db->where("tbl_users.user_id",$uid);
   		$this->db->where("tbl_users.role_id",$rid);
   		$this->db->where("tbl_users.is_active","1");

   		$query = $this->db->get();
   		//echo $this->db->last_query(); exit;
		return $query->result();
   	}

   	 /**
	 * getting allocated restaurant of the user to restaurant manager
	 * @author Vaibhav Mehta
	 * Created date: 30/12/2017 07:00 PM
	 */
	function getUserData($userId)
	{
		$this->db->where('user_id',$userId);
		$query = $this->db->get('tbl_users');
		return $query->result();
	}	
	
   	 /**
	 * getting allocated restaurant of the user to restaurant manager
	 * @author Vaibhav Mehta
	 * Created date: 30/12/2017 07:00 PM
	 */
   	function getRestaurantIdOfManager($uid,$rid)
   	{
   		$this->db->select("tbl_restaurant_managers.restaurant_id");
   		$this->db->from("tbl_restaurant_managers");
   		$this->db->join("tbl_users","tbl_users.user_id=tbl_restaurant_managers.fk_user_id","left");
   		$this->db->join("tbl_restaurants","tbl_restaurant_managers.restaurant_id=tbl_restaurants.restaurant_id","left");
   		$this->db->where("tbl_users.user_id",$uid);
   		$this->db->where("tbl_users.role_id",$rid);
   		$this->db->where("tbl_users.is_active","1");

   		$query = $this->db->get();
   		//echo $this->db->last_query(); exit;
		return $query->result();
   	}
     
}