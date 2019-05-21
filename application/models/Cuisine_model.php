<?php
	/**
	 * Model Name: Cuisine_model
	 * Descripation: Use to manage the cuisine related database interaction
	 * @author Vaibhav mehta
	 * Created date: 30-09-2017 1:00 PM
	 */
class Cuisine_model extends CI_Model
{
	/**
	 * function to build query to get the driver details
	 * @author Vaibhav Mehta
	 * Created date: 29/09/2017 11:30 PM
	 */
	function getAllCuisines($id=null)
	{
		$this->db->from('tbl_cuisines');
		if ($id) {
			$this->db->where('cuisine_id',$id);
		}
		
		$this->db->where('is_active','1');
		$this->db->order_by('cuisine_name','asc');

		$query=$this->db->get();
		return $query->result();
	}
	/**
	 * function to build query to add cuisine details
	 * @author Rashmi Nayani
	 * Created date: 05/10/2017 7:00 PM
	 */
	function addCuisine($data){

		$this->db->insert('tbl_cuisines',$data);
		return $this->db->insert_id();
		
	}

	/**
	 * function to build query to edit cuisine details
	 * @author Rashmi Nayani
	 * Created date: 05/10/2017 8:00 PM
	 */
	function editCuisine($data,$id){
		$this->db->where('cuisine_id',$id);
		$query = $this->db->update("tbl_cuisines",$data);
		return $this->db->affected_rows();
	}

	/**
	 * function to build query to edit cuisine details
	 * @author Rashmi Nayani
	 * Created date: 05/10/2017 8:00 PM
	 */
	function getDuplicateCuisine($cuisine=null,$id=null){
		$this->db->from('tbl_cuisines');
		if ($id) {
			$this->db->where('cuisine_id !=',$id);
		}
		if ($cuisine) {
			$this->db->where('cuisine_name',$cuisine);
		}
		$this->db->where('is_active','1');
		$query=$this->db->get();
		return $query->result();
	}
}