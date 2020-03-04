<?php
	/**
	 * Model Name: Driver_model
	 * Descripation: Use to manage the driver related details
	 * @author Vaibhav mehta
	 * Created date: 28-09-2017 7:00 PM
	 */
	class Driver_model extends CI_Model
	{
	/**
	 * function to build query to get the driver details
	 * @author Vaibhav Mehta
	 * Created date: 29/09/2017 11:30 PM
	 */
	function getAllDriverDetails($driverRole,$resId,$id=null)
	{
		if($resId)
		{
			$this->db->where("tbl_restaurants.restaurant_id",$resId);
		}

		$this->db->select('us.user_id,us.role_id,us.first_name,us.last_name,us.email,us.profile_photo,us.address,us.contact_no,us.vendor,us.is_active,us.is_deleted,us.driver_password,tbl_language.lang_name,tbl_restaurants.restaurant_name,tbl_vehicles.brand,tbl_vehicles.model,tbl_restaurants.restaurant_id as rid');
		$this->db->join('tbl_restaurants','tbl_restaurants.restaurant_id=us.fk_restaurant_id','left');
		$this->db->join('tbl_language','tbl_language.language_id=us.language_id','left');
		$this->db->join('tbl_vehicles','tbl_vehicles.vehicle_id=us.fk_vehicle_id','left');
		$this->db->where('us.role_id',$driverRole);
		$this->db->where('us.is_active','1');
		$this->db->where('us.is_deleted','0');
		$this->db->group_by('us.user_id');
		$this->db->order_by('first_name','asc');
		if ($id) {
			$this->db->where('us.user_id',$id);
		}
		$this->db->from('tbl_users as us');
		$query=$this->db->get();
		return $query->result();
	}

	function getAllCountries()
	{
		$query=$this->db->get('countries');
		return $query->result();
	}
	/**
	 * function to build query to get all state
	 * @author Rashmi Nayani
	 * Created date: 10/10/2017 3:40 PM
	 */
	function getState($id){
		$this->db->where('is_active','1');
		$this->db->where('country_id',$id);
		$query=$this->db->get('state');
		return $query->result();
	}
	
	/**
	 * function to build query to get all city
	 * @author Rashmi Nayani
	 * Created date: 10/10/2017 3:40 PM
	 */
	function getCity($id){
		$this->db->where('is_active','1');
		$this->db->where('state_id',$id);
		$query=$this->db->get('city');
		return $query->result();
	}
	/**
	 * function to build query to add Driver details
	 * @author Rashmi Nayani
	 * Created date: 10/10/2017 1:15 PM
	 */
	function getallVehicleData($user_id = NULL,$rid= NULL)
	{

		if($rid != NULL){
			$this->db->where("restaurant_id",$rid);
		}

		$this->db->where('`vehicle_id` NOT IN (SELECT `fk_vehicle_id` FROM `tbl_users`)', NULL, FALSE);
		$this->db->where('is_active','1');
		$query = $this->db->get('tbl_vehicles');
		return $query->result();
	}
	function getallVehicleById($did= NULL)
	{
		
		
		$this->db->where('vehicle_id',$did);
		$this->db->where('is_active','1');
		$query = $this->db->get('tbl_vehicles');
		return $query->result();
	}
	function updateasignVehicle($oid,$updateOrder)
	{
		$this->db->where('user_id',$oid);
		$query = $this->db->update("tbl_users",$updateOrder);
		return $this->db->affected_rows();
	}

	/**
	 * function to build query to update order dish choice
	 * @author Vaibhav Mehta
	 * Created date: 16/11/2017 03:20 PM
	*/
	function addDriverDetail($data){

		$this->db->insert('tbl_users',$data);
		return $this->db->insert_id();
	}

	/**
	 * function to build query to edit cuisine details
	 * @author Rashmi Nayani
	 * Created date: 10/10/2017 8:00 PM
	 */
	function editDriverDetail($data,$id){
		$this->db->where('user_id',$id);
		$query = $this->db->update("tbl_users",$data);
		return $this->db->affected_rows();
	}

	/**
	 * function to build query to check duplicate Reataurant exist
	 * @author Rashmi Nayani
	 * Created date: 09/10/2017 5:45 PM
	 */
	function getDuplicateName($driverRole,$nm,$id=null){
		$this->db->where('first_name',$nm);
		if ($id) {
			$this->db->where('user_id !=',$id);
		}
		$this->db->where('is_active','1');
		$this->db->where('role_id',$driverRole);
		$query=$this->db->get('tbl_users');
		return $query->result();
	}
	
	/**
	 * function to build query to check duplicate email exist
	 * @author Rashmi Nayani
	 * Created date: 09/10/2017 5:45 PM
	 */
	function getDuplicateEmail($driverRole,$email){
		$this->db->where('email',$email);
		$this->db->where('is_active','1');
		$this->db->where('role_id',$driverRole);
		$query=$this->db->get('tbl_users');
		return $query->result();
	}
	
	/**
	 * function to build query to get all restaurant
	 * @author Rashmi Nayani
	 * Created date: 25/10/2017 12:10 PM
	 */
	function getAllRestaurant($rid=''){
		if($rid !=""){
			$this->db->where('restaurant_id',$rid);
		}
		$this->db->where('is_active','1');
		$query=$this->db->get('tbl_restaurants');
		return $query->result();
	}
	
}
