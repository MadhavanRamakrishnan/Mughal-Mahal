<?php
	/**
	 * Model Name: Choice_model
	 * Descripation: Use to manage the choice category related database interaction
	 * @author Rashmi Nayani
	 * Created date: 27-10-2017 12:45 PM
	 */
class Choice_model extends CI_Model
{
	/**
	 * function to build query to get all choice category
	 * @author Rashmi Nayani
	 * Created date: 27/10/2017 12:45 PM
	 */
	function getAllChoiceCategory($id=null){
		$this->db->select("tbl_choice_category.*,tbl_choice_category_type.choice_category_type_id,tbl_choice_category_type.type_name");
		$this->db->join('tbl_choice_category_type','tbl_choice_category_type.choice_category_type_id=tbl_choice_category.fk_type_id','left');
		if ($id != null) {
		$this->db->where('tbl_choice_category.choice_category_id',$id);
		}
		$this->db->where('tbl_choice_category.is_active','1');
		$query = $this->db->get('tbl_choice_category');
		return $query->result();
	}

	/**
	 * function to build query to get all category type
	 * @author Rashmi Nayani
	 * Created date: 27/10/2017 6:45 PM
	 */
	function getChoiceCategoryType(){

		$query = $this->db->get('tbl_choice_category_type');
		return $query->result();
	}
	
	/**
	 * function to build query to add choice category details
	 * @author Rashmi Nayani
	 * Created date: 27/10/2017 6:40 PM
	 */
	function addChoiceCategory($data){

		$this->db->insert('tbl_choice_category',$data);
		return $this->db->insert_id();
		
	}
	function addChoice($data){

		$this->db->insert('tbl_choice',$data);
		return $this->db->insert_id();
		
	}

	/**
	 * function to build query to edit choice category details
	 * @author Rashmi Nayani
	 * Created date: 27/10/2017 7:20 PM
	 */
	function editChoiceCategory($data,$id){
		$this->db->where('choice_category_id',$id);
		$query = $this->db->update("tbl_choice_category",$data);
		return $this->db->affected_rows();
	}
	function editChoice($data,$id){
		$this->db->where('choice_id',$id);
		$query = $this->db->update("tbl_choice",$data);
		return $this->db->affected_rows();
	}

	/**
	 * function to build query to edit cuisine details
	 * @author Rashmi Nayani
	 * Created date: 27/10/2017 7:00 PM
	 */
	function getDuplicateCategory($category,$id=""){
		
		if ($id !="") {
			$this->db->where('choice_category_id !=',$id);
			$this->db->where('choice_category_name',$category);
		}else{
			$this->db->where('choice_category_name',$category);
		}
		$this->db->where('is_active',1);
		
		$query=$this->db->get('tbl_choice_category');
		return $query->result();
	}
	/**
	 * function to build query to get all choices list
	 * @author Rashmi Nayani
	 * Created date: 27/10/2017 11:40 AM
	 */
	function getAllChoices($id='',$catId=''){
		$this->db->select("tbl_choice.*,tbl_choice_category.choice_category_name,tbl_choice_category.is_active");
		$this->db->join('tbl_choice_category','tbl_choice_category.choice_category_id=tbl_choice.fk_choice_category_id','left');
		if ($id != '') {
		$this->db->where('tbl_choice.choice_id',$id);
		}
		if ($catId != '') {
		$this->db->where('tbl_choice.fk_choice_category_id',$catId);
		}
		$this->db->order_by('tbl_choice.created_date','desc');
		$this->db->where('tbl_choice.is_active','1');
		$query = $this->db->get('tbl_choice');
		//echo $this->db->last_query();exit;
		return $query->result();
	}
}