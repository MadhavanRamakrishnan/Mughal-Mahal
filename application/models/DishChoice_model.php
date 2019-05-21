<?php 

/**
	 * Model Name: DishChoice_model
	 * Descripation: Use to manage the DIsh Choice related database interaction
	 * @author Manisha Kanazariya
	 * Created date: 17-02-2018 6:00 PM
	 */
class DishChoice_model extends CI_Model
{
	
	/**
	 * function to build query to add Choice dish 
	 * @author Manisha Kanazariya
	 * Created date: 17-02-2018 6:00 PM
	 */
	function addDishChoices($data){

		$return = $this->db->insert_batch('tbl_dish_choice',$data);
		return $return;
		
	}

	function getAllDishChoices($limit=null,$offset =null,$search=null){
		if($limit != null){
			$this->db->limit($limit,$offset);
		}
		$this->db->where('is_active',1);
		$this->db->group_by('fk_dish_id');
		$query = $this->db->get('tbl_dish_choice');
		return $query->result();
	}
}

?>