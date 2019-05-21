<?php
	/**
	 * Model Name: Vehicle_model
	 * Descripation: Use to manage the vehicle related database interaction
	 * @author Vaibhav mehta
	 * Created date: 30-09-2017 1:00 PM
	 */
class Vehicle_model extends CI_Model
{
	/**
	 * function to build query to get the driver details
	 * @author Vaibhav Mehta
	 * Created date: 29/09/2017 11:30 PM
	 */
	function getAllVehicleDetails($id=null,$resId="")
	{
		$this->db->select("vh.* ,tbl_restaurants.restaurant_name");
		$this->db->join('tbl_restaurants','tbl_restaurants.restaurant_id = vh.restaurant_id','left');
		$this->db->from('tbl_vehicles as vh');
		$this->db->where('vh.is_active','1');
		if ($id) {
			$this->db->where('vh.vehicle_id',$id);
		}
		if ($resId !="") {
			$this->db->where('tbl_restaurants.restaurant_id',$resId);
		}
		$this->db->order_by('vh.vehicle_id','desc');

		$query=$this->db->get();
		return $query->result();
	}

	/**
	 * function to build query to add vehicle details
	 * @author Rashmi Nayani
	 * Created date: 10/10/2017 5:10 PM
	 */
	function addVehicleDetail($data){

		$this->db->insert('tbl_vehicles',$data);
		return $this->db->insert_id();
	}

	/**
	 * function to build query to edit vehicle details
	 * @author Rashmi Nayani
	 * Created date: 10/10/2017 5:10 PM
	 */
	function editVehicleDetail($data,$id){
		$this->db->where('vehicle_id',$id);
		$query = $this->db->update("tbl_vehicles",$data);
		return $this->db->affected_rows();
	}

	
}