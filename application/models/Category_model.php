<?php
	/**
	 * Model Name: Category_model
	 * Descripation: Use to manage the dishes category related database interaction
	 * @author Vaibhav mehta
	 * Created date: 30-09-2017 1:00 PM
	 */
class Category_model extends CI_Model
{
	/**
	 * function to build query to get all cuisine dishes
	 * @author Rashmi Nayani
	 * Created date: 06/10/2017 3:40 PM
	 */
	function getAllCategory($id=null){
	
		$this->db->where('is_active','1');
		if ($id != null) {
		$this->db->where('tbl_dish_category.category_id',$id);
		}
		$query = $this->db->get('tbl_dish_category');
		return $query->result();
	}
	
	/**
	 * function to build query to add cuisine details
	 * @author Rashmi Nayani
	 * Created date: 05/10/2017 7:00 PM
	 */
	function addCategory($data){

		$this->db->insert('tbl_dish_category',$data);
		return $this->db->insert_id();
		
	}

	/**
	 * function to build query to edit/update cuisine details
	 * @author Rashmi Nayani
	 * Created date: 05/10/2017 8:00 PM
	 * uodated by :manisha kanazariya 
	 */
	function editCategory($data,$id){
		$this->db->where('category_id',$id);
		$this->db->update("tbl_dish_category",$data);
		return $this->db->affected_rows();
	}

	
	/**
	 * function to build query to edit cuisine details
	 * @author Rashmi Nayani
	 * Created date: 06/10/2017 4:45 PM
	 */
	function getDuplicateCategory($category,$catid=null){
		$this->db->where('category_name',$category);
		if ($catid !=null) {
			$this->db->where('category_id !=',$catid);
		}
		$this->db->where('is_active','1');
		$query=$this->db->get('tbl_dish_category');
		return $query->result();
	}
}