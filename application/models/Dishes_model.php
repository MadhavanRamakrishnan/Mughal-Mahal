<?php
	/**
	 * Model Name: Dishes_model
	 * Descripation: Use to manage the dishes related database interaction
	 * @author Vaibhav mehta
	 * Created date: 30-09-2017 1:00 PM
	 */
	class Dishes_model extends CI_Model{
	/**
	 * function to build query to get all dishes count
	 * @author Rashmi Nayani
	 * Created date: 06/10/2017 8:00 PM
	 */
	function dishesCount($search=null){
		$this->db->select('tbl_dishes.*,tbl_dish_category.category_name,tbl_dish_category.category_id');
		$this->db->from('tbl_dishes');
		$this->db->join('tbl_dish_category','tbl_dish_category.category_id = tbl_dishes.category_id','left');
		$this->db->where('tbl_dishes.is_active','1');
		if ($search) {
			$where = "(tbl_dish_category.category_name like '%$search%'  or tbl_dishes.product_en_name like '%$search%'  or tbl_dishes.en_description like '%$search%' or tbl_dishes.price like '%$search%')";
			$this->db->where($where);
		}
		$query = $this->db->get();
		return $query->num_rows();
	}
	/**
	 * function to build query to get all cuisine dishes
	 * @author Rashmi Nayani
	 * Created date: 06/10/2017 7:15 PM
	 */
	function getAllDishes($limit=null,$offset=null){
		$this->db->select('tbl_dishes.*,tbl_dish_category.category_name,tbl_dish_category.category_id');
		$this->db->from('tbl_dishes');
		$this->db->join('tbl_dish_category','tbl_dish_category.category_id = tbl_dishes.category_id','left');
		if($limit)
		{
			$this->db->limit($limit,$offset);
		}
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}

	/**
	 * function to build query to get all category by cuisine id
	 * @author Rashmi Nayani
	 * Created date: 07/10/2017 12:15 PM
	 */
	function getCategory(){
		$this->db->where('is_active','1');
		$query=$this->db->get('tbl_dish_category');
		return $query->result();
	}
	
	/**
	 * function to build query to add dish details
	 * @author Rashmi Nayani
	 * Created date: 07/10/2017 12:45 PM
	 */
	function addDishDetail($data){

		$this->db->insert('tbl_dishes',$data);
		return $this->db->insert_id();
		
	}


	/**
	 * function to build query to edit cuisine details
	 * @author Rashmi Nayani
	 * Created date: 07/10/2017 1:30 PM
	 */
	function editDishDetail($data,$id){
		$this->db->where('product_id',$id);
		$this->db->update("tbl_dishes",$data);
		return $this->db->affected_rows();
	}

	/**
	 * function to build query to get duplicate dish name exist
	 * @author Rashmi Nayani
	 * Created date: 07/10/2017 1:00 PM
	 */
	function getDuplicateDish($dish,$catid,$id=null){
		$this->db->where('product_en_name',$dish);
		$this->db->where('category_id',$catid);
		if ($id) {
			$this->db->where('product_id !=',$id);
		}
		$this->db->where('is_active','1');
		$query=$this->db->get('tbl_dishes');
		return $query->result();
	}

	/**
	 * function to build query to get dish details
	 * @author Rashmi Nayani
	 * Created date: 07/10/2017 1:40 PM
	 */
	function getDishDetails($id){
		$this->db->select('tbl_dishes.*,tbl_dish_category.category_name,tbl_dish_category.category_id');
		$this->db->from('tbl_dishes');
		$this->db->join('tbl_dish_category','tbl_dish_category.category_id = tbl_dishes.category_id','left');
		$this->db->where('tbl_dishes.is_active','1');
		$this->db->where('tbl_dishes.product_id',$id);
		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * function to build query to add dish details
	 * @author Rashmi Nayani
	 * Created date: 07/10/2017 12:45 PM
	 */
	function addDishMedia($data){

		$this->db->insert('tbl_dish_media',$data);
		return $this->db->insert_id();
		
	}
	/**
	 * function to build query to get dishes images
	 * @author Rashmi Nayani
	 * Created date: 09/10/2017 11:45 PM
	 */
	function getImages(){
		
		$this->db->where('is_active','1');
		$query=$this->db->get('tbl_dish_media');
		return $query->result();
	}

	/**
	 * function to build query to add dish choices
	 * @author manisha Kanazariya
	 * Created date: 27/02/2018 7:45 PM
	 */
	function addDishChoices($data){

		$this->db->insert_batch('tbl_dish_choice',$data);
		return $this->db->insert_id();
		
	}
	/**
	 * function to build query to get dish choices
	 * @author manisha Kanazariya
	 * Created date: 27/02/2018 7:45 PM
	 */
	function getDishChoices($dishId){
        $this->db->select("tbl_dish_choice.*,tbl_choice.fk_choice_category_id");
        $this->db->join("tbl_choice",'tbl_choice.choice_id =tbl_dish_choice.fk_choice_id','left');
        $this->db->join("tbl_choice_category",'tbl_choice_category.choice_category_id =tbl_choice.fk_choice_category_id','left');
        $this->db->from("tbl_dish_choice");
		$this->db->where('tbl_dish_choice.is_active','1');
		$this->db->where('tbl_dish_choice.fk_dish_id',$dishId);
		$query=$this->db->get();
		return $query->result();
		
		
	}


	/**
	 * function to build query to delete dish choices
	 * @author manisha Kanazariya
	 * Created date: 28/02/2018 11:00 PM
	 */
	function deleteDishChoice($dishId){
		$this->db->where('fk_dish_id',$dishId);
		$this->db->delete("tbl_dish_choice");	
		return $this->db->affected_rows();
	}
}