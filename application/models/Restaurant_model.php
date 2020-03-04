<?php
	/**
	 * Model Name: Restaurant_model
	 * Descripation: Use to manage the restaurant related details
	 * @author Vaibhav mehta
	 * Created date: 28-09-2017 4:00 PM
	 */
class Restaurant_model extends CI_Model
{
	/**
	 * this function call for getting details of restaurants
	 * @author Vaibhav Mehta
	 * Created date: 30-09-2017 6:50 PM
	 */
	function getAllRestaurantDetails($id=null)
	{
		$this->db->select("res.*,tbl_locality.locality_id,media.media_name,m_users.first_name as m_fname,m_users.last_name as m_lname,o_users.first_name as o_fname,o_users.last_name as o_lname,o_users.user_id as o_uid,m_users.user_id as m_uid, o_users.email as o_email");
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
		// if ($id==null) {
			$this->db->group_by('res.restaurant_id');
		// }
		
		$query=$this->db->get();
		
		return $query->result();
	}

	/**
	 * function to build query to get restaurant owner
	 * @author Rashmi Nayani
	 * Created date: 09/10/2017 3:30 PM
	 */
	function updateAvilability($restaurantId, $data){
		$this->db->where('restaurant_id', $restaurantId);
        $this->db->update('tbl_restaurants', $data);
        return $this->db->affected_rows();
	}
	function getOwner1($rid){

		$this->db->where('is_active','1');
		$this->db->where('role_id',$rid);
		$query=$this->db->get('tbl_users');
		return $query->result();
	}
	/**
	 * function to build query to get all country list
	 * @author Rashmi Nayani
	 * Created date: 09/10/2017 3:40 PM
	 */
	
	function getOwner($res_id = NULL)
	{
		if($res_id != NULL){
			$this->db->where("user_id NOT IN (SELECT fk_user_id FROM `tbl_restaurant_owners` where `restaurant_id` !='$res_id' AND `restaurant_id` !='0')", NULL, FALSE);

		}else{

		$this->db->where("`user_id` NOT IN (SELECT `fk_user_id` FROM `tbl_restaurant_owners` where `restaurant_id` !='0')", NULL, FALSE);
		}
		$this->db->where('is_active','1');
		$this->db->where('role_id','2');
		$query=$this->db->get('tbl_users');
		// print_r($this->db->last_query()); die;
		return $query->result();
	}
	function getCountry(){
		$this->db->select('country_name,country_id');
		$this->db->where('is_active','1');
		$query=$this->db->get('countries');
		return $query->result();
	}
	
	/**
	 * function to build query to get state list for selected country
	 * @author Rashmi Nayani
	 * Created date: 09/10/2017 3:40 PM
	 */
	
	function getState($id){
		$this->db->where('is_active','1');
		$this->db->where('country_id',$id);
		$query=$this->db->get('state');
		return $query->result();
	}
	
	/**
	 * function to build query to get city list for selected state
	 * @author Rashmi Nayani
	 * Created date: 09/10/2017 3:40 PM
	 */
	function getCity($id){
		$this->db->where('is_active','1');
		$this->db->where('state_id',$id);
		$query=$this->db->get('city');
		return $query->result();
	}
	
	
	/**
	 * function to build query to add restaurant details
	 * @author Rashmi Nayani
	 * Created date: 09/10/2017 7:00 PM
	 */
	function getlocality($id =""){
		$this->db->select('tbl_locality.*,tbl_restaurants.restaurant_name');
		$this->db->join('tbl_restaurants','tbl_restaurants.restaurant_id =tbl_locality.restaurant_id','left');
		if($id != ""){
			$this->db->where('tbl_locality.locality_id',$id);
		}
		$query=$this->db->get('tbl_locality');
		return $query->result();
	}
	
	
	/**
	 * function to build query to add restaurant details
	 * @author Rashmi Nayani
	 * Created date: 09/10/2017 7:00 PM
	 */
	function getLatlongdata($latlongId){
		$this->db->where('locality_id',$latlongId);
		$query=$this->db->get('tbl_locality');

		return $query->result();
	}
	/**
	 * function to build query to get all country list
	 * @author Rashmi Nayani
	 * Created date: 09/10/2017 3:40 PM
	 */
	function addRestaurantDetail($data){

		$this->db->insert('tbl_restaurants',$data);
		return $this->db->insert_id();
		
	}

	/**
	 * function to build query to add restaurant media
	 * @author Rashmi Nayani
	 * Created date: 09/10/2017 7:00 PM
	 */
	function restaurantOwnersData($restaurantOwners){

		$this->db->insert('tbl_restaurant_owners',$restaurantOwners);
		return $this->db->insert_id();
		
	}

	/**
	 * function to build query to add restaurant media
	 * @author Rashmi Nayani
	 * Created date: 09/10/2017 7:00 PM
	 */
	function restaurantManagerData($restaurantManager){

		$this->db->insert('tbl_restaurant_managers',$restaurantManager);
		return $this->db->insert_id();
		
	}

	/**
	 * function to build query to add restaurant media
	 * @author Rashmi Nayani
	 * Created date: 09/10/2017 7:00 PM
	 */
	function addRestaurantMedia($data){

		$this->db->insert('tbl_restaurant_media',$data);
		return $this->db->insert_id();
		
	}

	/**
	 * function to build query to edit restaurant details
	 * @author Rashmi Nayani
	 * Created date: 05/10/2017 8:00 PM
	 */
	function updaterestaurantOwnersData($restaurantOwners,$rid){
		$this->db->where('fk_user_id',$rid);
		$query = $this->db->update("tbl_restaurant_owners",$restaurantOwners);
		return $this->db->affected_rows();
	}
	function updaterestaurantManagerData($restaurantManager,$rid){
		$this->db->where('fk_user_id',$rid);
		$query = $this->db->update("tbl_restaurant_managers",$restaurantManager);
		return $this->db->affected_rows();
	}
	
	function editRestaurantDetail($data,$id){
		$this->db->where('restaurant_id',$id);
		$query = $this->db->update("tbl_restaurants",$data);
		return $this->db->affected_rows();
	}
	function editRestaurantDetailDelivery($data){
		
		$this->db->update_batch('tbl_restaurants', $data, 'restaurant_id');
		return $this->db->affected_rows();
	}
	/**
	 * function to build query to check duplicate Reataurant exist
	 * @author Rashmi Nayani
	 * Created date: 09/10/2017 5:45 PM
	 */
	function getDuplicateRestaurant($resName,$id=null){
		$this->db->where('restaurant_name',$resName);
		if ($id) {
			$this->db->where('restaurant_id !=',$id);
		}
		$this->db->where('is_active','1');
		$query=$this->db->get('tbl_restaurants');
		return $query->result();
	}
		/**
	 * function to build query to check duplicate email exist
	 * @author Rashmi Nayani
	 * Created date: 09/10/2017 5:45 PM
	 */
	function getDuplicateEmail($email){
		$this->db->where('email',$email);
		$this->db->where('is_active','1');
		$query=$this->db->get('tbl_restaurants');
		return $query->result();
	}
	/***
	 * function to build query to check duplicate email exist
	 * @author Rashmi Nayani
	 * Created date: 09/10/2017 5:45 PM
	 */
	function getDuplicateUserEmail($email){
		$admin_role   =$this->config->item('super_admin_role');
		$owner_role   =$this->config->item('restaurant_owner_role');
		$manager_role =$this->config->item('restaurant_manager_role');
		$salse_role   =$this->config->item('restaurant_sales_role');

		$this->db->where('email',$email);
		$this->db->where('is_active','1');
		$this->db->where("role_id IN ('$owner_role','$manager_role','$salse_role','$admin_role')");
		$query=$this->db->get('tbl_users');
		return $query->result();
	}
		/**
	 * function to build query to get images
	 * @author Rashmi Nayani
	 * Created date: 10/10/2017 11:15 PM
	 */
	function getImages($id){
		$this->db->where('restaurant_id',$id);
		$this->db->where('is_active','1');
		$query=$this->db->get('tbl_restaurant_media');
		return $query->result();
	}

	/**
	 * this function call for getting details of owner
	 * @author Rashmi Nayani
	 * Created date: 24-10-2017 1:50 PM
	 */

	function getAllOwnersDetails($role,$id=null)
	{
		$this->db->select('tbl_users.*,tbl_restaurant_owners.restaurant_id as r_id,tbl_restaurants.restaurant_name ');
		$this->db->from('tbl_users');
		$this->db->join('tbl_restaurant_owners','tbl_restaurant_owners.fk_user_id=tbl_users.user_id','left');
		$this->db->join('tbl_restaurants','tbl_restaurants.restaurant_id=tbl_restaurant_owners.restaurant_id','left');
		$this->db->where('tbl_users.role_id',$role);
		$this->db->where('tbl_users.is_active','1');
		$this->db->where('tbl_users.is_deleted','0');
		$this->db->group_by('tbl_users.user_id');
		$this->db->order_by('first_name','asc');
		if ($id !=null) {
			$this->db->where('tbl_users.user_id',$id);
		}
		$query=$this->db->get();
		return $query->result();
	}

	/**
	 * function to build query to add owner details
	 * @author Rashmi Nayani
	 * Created date:24-10-2017 3:50 PM
	 */
	function addOwnerDetail($data){
		$this->db->insert('tbl_users',$data);
		return $this->db->insert_id();
	}

	/**
	 * function to build query to check duplicate owner name exist
	 * @author Rashmi Nayani
	 * Created date: 24/10/2017 4:10 PM
	 */
	function getDuplicateName($role,$nm,$id=null)
	{
		$this->db->where('first_name',$nm);
		if ($id) {
			$this->db->where('user_id !=',$id);
		}
		$this->db->where('is_active','1');
		$this->db->where('role_id',$role);
		$query=$this->db->get('tbl_users');
		return $query->result();
	}
	/**
	 * function to build query to edit restaurant details
	 * @author Rashmi Nayani
	 * Created date: 24/10/2017 6:05 PM
	 */
	function editOwnerDetail($data,$id){
		$this->db->where('user_id',$id);
		$query = $this->db->update("tbl_users",$data);
		return $this->db->affected_rows();
	}

	/**
	 * this function call for getting details of managers
	 * @author Rashmi Nayani
	 * Created date: 24-10-2017 6:50 PM
	 */

	function getAllManagersDetails($role,$resId="",$id=null)
	{
		if($resId !="")
		{
			$this->db->select("tbl_restaurant_managers.restaurant_id");
			$this->db->join("tbl_restaurant_managers","tbl_restaurant_managers.restaurant_id=$resId");
			$this->db->where("tbl_restaurant_managers.restaurant_id",$resId);
			
			$this->db->group_by("tbl_users.user_id");
		}

		$this->db->select('tbl_users.*,restaurant_managers.restaurant_id as r_id,tbl_restaurants.restaurant_name');
		$this->db->from('tbl_users');
		$this->db->join('tbl_restaurant_managers as restaurant_managers','restaurant_managers.fk_user_id=tbl_users.user_id','left');
		$this->db->join('tbl_restaurants','tbl_restaurants.restaurant_id=restaurant_managers`	.restaurant_id','left');
		$this->db->where('tbl_users.role_id',$role);
		$this->db->where('tbl_users.is_active','1');
		$this->db->where('tbl_users.is_deleted','0');
		$this->db->group_by('tbl_users.user_id');
		$this->db->order_by('first_name','asc');
		if ($id != null) {
			$this->db->where('tbl_users.user_id',$id);
		}
		$query=$this->db->get();
		return $query->result();
	}

	/**
	 * this function call for getting details of restaurant
	 * @author Vaibhav Mehta
	 * Created date: 08-01-2018 6:50 PM
	 */

	function getRestaurantDetails($resId)
	{
		if($resId)
		{
			$this->db->where("res.restaurant_id",$resId);
		}

		$this->db->select('res.*,m_users.first_name as m_fname,m_users.last_name as m_lname,o_users.first_name as o_fname,o_users.last_name as o_lname,o_users.user_id as o_uid,m_users.user_id as m_uid');
		$this->db->from('tbl_restaurants as res');
		$this->db->join('tbl_restaurant_owners','tbl_restaurant_owners.restaurant_id=res.restaurant_id','left');
		$this->db->join('tbl_restaurant_managers','tbl_restaurant_managers.restaurant_id=res.restaurant_id','left');
		$this->db->join('tbl_users as m_users','m_users.user_id=tbl_restaurant_managers.fk_user_id','left');
		$this->db->join('tbl_users as o_users','o_users.user_id=tbl_restaurant_owners.fk_user_id','left');
		$this->db->group_by('res.restaurant_id');
		$query=$this->db->get();
		return $query->result();
	}

	/**
	 * function to build query to add manager details
	 * @author Rashmi Nayani
	 * Created date:24-10-2017 7:10 PM
	 */
	function addManagerDetail($data){
		$this->db->insert('tbl_users',$data);
		return $this->db->insert_id();
	}

	/**
	 * function to build query to edit manager details
	 * @author Rashmi Nayani
	 * Created date: 24/10/2017 7:10 PM
	 */
	function editManagerDetail($data,$id){
		$this->db->where('user_id',$id);
		$query = $this->db->update("tbl_users",$data);
		return $this->db->affected_rows();
	}

	/**
	 * function to build query to get restaurant owner
	 * @author Rashmi Nayani
	 * Created date: 09/10/2017 3:30 PM
	 */
	function getManager1($rid){
		$this->db->where('is_active','1');
		$this->db->where('role_id',$rid);
		$query=$this->db->get('tbl_users');
		return $query->result();
	}

	/**
	 * function to build query to add restaurant owner details
	 * @author Rashmi Nayani
	 * Created date:24-10-2017 1:45 PM
	 */
	function getManager($res_id = NULL)
	{

		if($res_id != NULL){
			$this->db->where("user_id NOT IN (SELECT fk_user_id FROM `tbl_restaurant_managers` where `restaurant_id` !='$res_id' AND `restaurant_id` !='0')", NULL, FALSE);

		}else{

		$this->db->where("`user_id` NOT IN (SELECT `fk_user_id` FROM `tbl_restaurant_managers` where `restaurant_id` !='0')", NULL, FALSE);
		}
		$this->db->where('is_active','1');
		$this->db->where('role_id','3');
		$query=$this->db->get('tbl_users');
		return $query->result();
	}
	function updateRestaurantOwner($oData,$uid){
		
		$this->db->where('fk_user_id',$uid);
		$query = $this->db->update("tbl_restaurant_owners",$oData);
		return $this->db->affected_rows();

	}
	function updateRestaurantManager($mData,$uid,$rid){

		if($uid ==''){
			$this->db->where('restaurant_id',$rid);
		}else{

			$this->db->where('fk_user_id',$uid);
		}
		
		
		$query = $this->db->update("tbl_restaurant_managers",$mData);
		return $this->db->affected_rows();
	}
	function addRestaurantOwner($data){

		$this->db->insert('tbl_restaurant_owners',$data);
		return $this->db->insert_id();
	}

	/**
	 * function to build query to add restaurant  manager details
	 * @author Rashmi Nayani
	 * Created date:24-10-2017 1:45 PM
	 */
	function addRestaurantManager($data){

		$this->db->insert('tbl_restaurant_managers',$data);
		return $this->db->insert_id();
	}

	/**
	 * function to build query delete restaurant mangere details
	 * @author Rashmi Nayani
	 * Created date:25-10-2017 11:35 PM
	 */
	function deleteRestaurantManager($id,$user_id=""){	
		if($user_id !=""){
			$this->db->where('fk_user_id',$user_id);
		}
		if($id !=""){
			$this->db->where('restaurant_id',$id);
		}
		
		$this->db->delete("tbl_restaurant_managers");	
	}

	/**
	 * function to build query delete restaurant owner details
	 * @author Rashmi Nayani
	 * Created date:25-10-2017 11:45 PM
	 */
	function deleteRestaurantOwner($id,$user_id=""){	
		if($user_id !=""){
			$this->db->where('fk_user_id',$user_id);
		}
		if($id !=""){
			$this->db->where('restaurant_id',$id);
		}
		$this->db->delete("tbl_restaurant_owners");	
	}

	function getAllRestaurantAvelibility($resId)
	{
		if($resId)
		{
			$this->db->where("tbl_restaurants.restaurant_id",$resId);
		}
		$this->db->select("tbl_restaurants.`restaurant_id`, `restaurant_name`, `custom_delivery_time`, tbl_restaurants.`is_active`");
		$this->db->from("tbl_restaurants");
		
		$query = $this->db->get();
		return $query->result();
	}

	

	function getAllOwnerRestaurant($user_id = NULL)
	{
		if($user_id){
			$this->db->where("restaurant_id NOT IN (SELECT restaurant_id FROM tbl_restaurant_owners WHERE fk_user_id!=$user_id  GROUP BY restaurant_id HAVING COUNT(`owner_id`) >=100 )", NULL, FALSE);
		}else{
			$this->db->where('`restaurant_id` NOT IN (SELECT restaurant_id FROM tbl_restaurant_owners GROUP BY restaurant_id HAVING COUNT(`owner_id`) >=100)',NULL,FALSE);
		}
		$this->db->where('is_active','1');
		$query=$this->db->get('tbl_restaurants');
		return $query->result();
	}

	function getAllManagerRestaurant($user_id = NULL)
	{
		if($user_id){
			$this->db->where("restaurant_id NOT IN (SELECT restaurant_id FROM tbl_restaurant_managers where fk_user_id!=$user_id)", NULL, FALSE);
		}else{

		$this->db->where('`restaurant_id` NOT IN (SELECT `restaurant_id` FROM `tbl_restaurant_managers`)', NULL, FALSE);
		}
		$this->db->where('is_active','1');
		$query=$this->db->get('tbl_restaurants');
		return $query->result();
	}

	function addRestaurantDish($data){
		$this->db->insert('tbl_restaurant_dishes',$data);
		return $this->db->insert_id();
	}

	function addLocality($data){

		$this->db->insert_batch('tbl_locality',$data);
		return $this->db->affected_rows();	
	}

	
	/**
	 * function to build query add add dish for  restaurant 
	 * @author Manisha Kanazariya 
	 * Created date:23-04-2018 11:45 PM
	 */

	function addRestaurantDishes($dishData){
		$this->db->insert_batch("tbl_restaurant_dishes",$dishData);
		return $this->db->affected_rows();
	}

	/**
	 * function to build query to get dishes for the restaurents (for list in add dishes of restaurents)
	 * @authorManisha Kanazariya 
	 * Created date: 24/04/2018 7:15 PM
	 */
	function getAllDishes($restaurantId="",$dish_id=""){
		
		if($restaurantId !=""){
			$where="product_id NOT IN (select fk_dish_id from tbl_restaurant_dishes where fk_restaurant_id='$restaurantId')";
			$this->db->where($where);
		}
		if($dish_id !=""){
			$this->db->where('product_id',$dish_id);
		}
		$this->db->where('is_active','1');
		
		return $this->db->get('tbl_dishes')->result();
		
	}
	/**
	 * function to build query to get dishes for the restaurents (for list in add dishes of restaurents)
	 * @authorManisha Kanazariya 
	 * Created date: 24/04/2018 7:15 PM
	 */
	function getRestaurantsDish($restaurantId="",$dish_id=""){
		$this->db->join('tbl_dishes','tbl_dishes.product_id =rs.fk_dish_id','left');
		$this->db->where('rs.fk_dish_id',$dish_id);
		$this->db->where('rs.fk_restaurant_id',$restaurantId);
		$this->db->where('tbl_dishes.is_active','1');
		$this->db->from('tbl_restaurant_dishes as rs');
		return $this->db->get()->result();
		
	}

	/**
	 * function to build query to get dishes details for the restaurents 
	 * @authorManisha Kanazariya 
	 * Created date: 24/04/2018 7:15 PM
	 */
	function getRestaurantsDishes($restaurantId="",$category_id="",$dish_id="",$is_best_dish=null){

		$this->db->select("tbl_restaurant_dishes.* ,tbl_dishes.*,tbl_dish_category.*");
		$this->db->where('tbl_restaurant_dishes.fk_restaurant_id',$restaurantId);
		$this->db->where('tbl_dishes.is_active',1);
		$this->db->join('tbl_dishes','tbl_dishes.product_id =tbl_restaurant_dishes.fk_dish_id','left');
		$this->db->join('tbl_dish_category','tbl_dish_category.category_id =tbl_dishes.category_id','left');
		if($category_id ==1){
			$this->db->where('tbl_restaurant_dishes.is_best_dishes',1);
		}
		else if($category_id !=""){
			$this->db->where('tbl_dish_category.category_id',$category_id);
		}
		if($dish_id !=""){
			$this->db->where('tbl_restaurant_dishes.fk_dish_id',$dish_id);
		}
		if($is_best_dish ==1){
			$this->db->where('tbl_restaurant_dishes.is_best_dishes',1);
		}
		$this->db->order_by('tbl_dish_category.category_id');
		$this->db->from('tbl_restaurant_dishes');
		//echo $this->db->last_query();exit;
		return $this->db->get()->result();
	}
	/**
	 * function to build query to get choice details of t for the Dish 
	 * @authorManisha Kanazariya 
	 * Created date: 24/04/2018 7:15 PM
	 */
	function restaurantDisheChoice($choiceId=""){
		
		$this->db->where('tbl_choice.choice_id',$choiceId);
		$this->db->where('tbl_choice.is_active',1);
		$this->db->join('tbl_choice_category','tbl_choice_category.choice_category_id =tbl_choice.fk_choice_category_id','left');
		$this->db->from('tbl_choice');
		return $this->db->get()->result();
	}

	/**
	 * function to build query to check is exist owner
	 * @authorManisha Kanazariya 
	 * Created date: 14/05/2018 7:15 PM
	 */
	function checkOwner($user_id=""){
		$this->db->join('tbl_restaurants','tbl_restaurants.restaurant_id =tbl_restaurant_owners.restaurant_id','left');
		$this->db->where('tbl_restaurant_owners.fk_user_id',$user_id);
		return $this->db->get("tbl_restaurant_owners")->result();
	}
	
	/**
	 * function to build query to check is exist owner
	 * @authorManisha Kanazariya 
	 * Created date: 14/05/2018 7:15 PM
	 */
	function checkManager($user_id=""){
		$this->db->join('tbl_restaurants','tbl_restaurants.restaurant_id =tbl_restaurant_managers.restaurant_id','left');
		$this->db->where('tbl_restaurant_managers.fk_user_id',$user_id);
		return $this->db->get("tbl_restaurant_managers")->result();
	}
    
    /**
	 * function to build query to delete dish of restaurant
	 * @authorManisha Kanazariya 
	 * Created date: 19/05/2018 7:15 PM
	 */
	function deleteRestaurantDish($res_id,$dish_id){
		$this->db->where('fk_restaurant_id',$res_id);
		$this->db->where('fk_dish_id',$dish_id);
		$this->db->delete('tbl_restaurant_dishes');
		return $this->db->affected_rows();
	}
    
	/**
	 * function to build query to add sales person 
	 * @authorManisha Kanazariya 
	 * Created date: 21/05/2018 7:15 PM
	 */
    function addSalesPersonDetails($data){
    	$this->db->insert('tbl_users',$data);
    	return $this->db->insert_id();
    }

   
   /**
	 * function to build query to get sales person 
	 * @authorManisha Kanazariya 
	 * Created date: 21/05/2018 7:15 PM
	 */
    function getSalesPersonDetails($user_id=""){
    	$salse_role   =$this->config->item('sales_role');
    	$this->db->where('is_active','1');
    	$this->db->where('role_id',$salse_role);
    	if($user_id !=""){
    		$this->db->where('user_id',$user_id);
    	}
    	$query =$this->db->get('tbl_users');
    	return $query->result();
    }

    /**
	 * function to build query to check is contact number already registered 
	 * @authorManisha Kanazariya 
	 * Created date: 22/05/2018 7:15 PM
	 */
    function getDuplicateUserPhone($phone,$user_id=""){
    	$this->db->where('is_active','1');
    	$this->db->where('contact_no',$phone);
    	if($user_id !=""){
    		$this->db->where('user_id !=',$user_id);
    	}
    	$query =$this->db->get('tbl_users');
    	return $query->result();
    }

    /**
	 * function to build query to update sales person details
	 * @authorManisha Kanazariya 
	 * Created date: 22/05/2018 7:15 PM
	 */
    function updateSalesPersonDetails($data,$user_id){
    	$this->db->where('user_id',$user_id);
    	$this->db->update('tbl_users',$data);
    	return $this->db->affected_rows();
    }
	/**
	 * function to build query to get restaurant day time
	 * @authorManisha Kanazariya 
	 * Created date: 24/05/2018 7:15 PM
	 */
    function getRestaurantTime($id)
    {
    	$this->db->where('restaurant_days_time_id',$id);
    	return $this->db->get('tbl_restaurant_days_time')->result();
    }

    function getRestaurantTimeData($res_id ="",$is_approved=""){
    	$this->db->select('rdt.is_approved,rdt.update_from_time,rdt.update_to_time,rd.day,rdt.restaurant_days_time_id');
		$this->db->join('tbl_restaurant_days_time as rdt','rdt.fk_restaurant_days_id=rd.restaurant_days_id','left');
		$this->db->order_by('rdt.restaurant_days_time_id','ASC');
		$this->db->from('tbl_restaurant_days as rd');
		$this->db->where('rd.fk_restaurant_id',$res_id);
		if($is_approved != ""){
    		$this->db->where('rdt.is_approved',0);
    	}
		$query =$this->db->get();
		return $query->result();
	}

    /**
	 * function to build query to update restaurant day time
	 * @authorManisha Kanazariya 
	 * Created date: 24/05/2018 7:15 PM
	 */
    function updateRestaurantTime($id,$data){
    	$this->db->where('restaurant_days_time_id',$id);
    	$this->db->update('tbl_restaurant_days_time',$data);
    	return $this->db->affected_rows();
    }
    /**
	 * function to build query to add restaurant day time
	 * @authorManisha Kanazariya 
	 * Created date: 25/05/2018 7:15 PM
	 */
    function addRestaurantTime($data){
    	$this->db->insert('tbl_restaurant_days_time',$data);
    	return $this->db->insert_id();
    }

    /**
	 * function to build query to add restaurant day time
	 * @authorManisha Kanazariya 
	 * Created date: 25/05/2018 7:15 PM
	 */
    function addRestaurantDay($data){
    	$this->db->insert('tbl_restaurant_days',$data);
    	return $this->db->insert_id();
    }

    /**
	 * function to build query to get restaurant day time
	 * @authorManisha Kanazariya 
	 * Created date: 24/05/2018 7:15 PM
	 */
    function getRestaurantTimeDetail($res_id,$day){
    	$this->db->where('fk_restaurant_id',$res_id);
    	$this->db->where('day',$day);
    	return $this->db->get('tbl_restaurant_days')->result();
    }
    /**
	 * function to build query to get restaurant day time
	 * @authorManisha Kanazariya 
	 * Created date: 24/05/2018 7:15 PM
	 */
    function deleteTimeTime($id){
    	$this->db->where('restaurant_days_time_id',$id);
    	$this->db->delete('tbl_restaurant_days_time');
    	return $this->db->affected_rows();
    }


    function checkFromTime($id="",$res_id,$day){
    	$this->db->join('tbl_restaurant_days as rday','rday.restaurant_days_id=rtime.fk_restaurant_days_id','left');
    	if($id !=""){
    		$this->db->where('rtime.restaurant_days_time_id !=',$id);
    	}
    	$this->db->where('rday.day',$day);
    	$this->db->where('rday.fk_restaurant_id',$res_id);
    	$this->db->from('tbl_restaurant_days_time as rtime');
    	$query =$this->db->get();
    	return $query->result();

    }

    /**
	 * Edit localities
	 * @author Manisha Kanazariya 
	 * Created date: 1-06-2018 3:50 PM
	 */
    function updateLocality($data,$id){
    	$this->db->where('locality_id',$id);
    	$this->db->update('tbl_locality',$data);
    	return $this->db->affected_rows();
    }

    /**
	 * Delete localities
	 * @author Manisha Kanazariya 
	 * Created date: 1-06-2018 3:50 PM
	 */
    function deleteLocality($id){
    	$this->db->where('locality_id',$id);
    	$this->db->delete('tbl_locality');
    	return $this->db->affected_rows();
    }

    /**
	 * function to build query to get locality for restaurant
	 * @author Manisha Kanazariya 
	 * Created date: 1-06-2018 3:50 PM
	 */
	function getRestaurantLocality($id =""){
		if($id != ""){
			$this->db->where("restaurant_id = $id  OR restaurant_id=0");
		}else{
           $this->db->where('restaurant_id',0);
		}
		
		$query=$this->db->get('tbl_locality');
		return $query->result();
	}

	/**
	 * function to build query to update locality for restaurant
	 * @author Manisha Kanazariya 
	 * Created date: 1-06-2018 3:50 PM
	 */
	function updateRestaurantLocality($data,$id ="",$localities=""){
		if($id !=""){
			$this->db->where('restaurant_id',$id);
		}
		if($localities !=""){
			$this->db->where("locality_id IN ($localities)");
		}
		$this->db->update('tbl_locality',$data);
    	return $this->db->affected_rows();
		
	}
	

	/**
	 * function to build query to update dish data for restaurant
	 * @author Manisha Kanazariya 
	 * Created date: 1-06-2018 3:50 PM
	 */

	function UpdateRestaurantDish($res_id,$dish_id,$data){
		$this->db->where("fk_restaurant_id",$res_id);
		$this->db->where("fk_dish_id",$dish_id);
		$this->db->update('tbl_restaurant_dishes',$data);
		// print_r($this->db->last_query());
    	return $this->db->affected_rows();
	}


	/**
	 * function to build query to dish choice of restaurant
	 * @author Manisha Kanazariya 
	 * Created date: 1-06-2018 3:50 PM
	 */
	function getRestaurantsDishChoice($dish_id,$choiceIds){
		$this->db->where("choice_id IN ($choiceIds)");
		$this->db->from('tbl_choice');
		return $this->db->get()->result();
	}


	/**
	 * function for remove best dish form restaurant
	 * @author Manisha Kanazariya
	 * Created date: 28-09-2018 11:50 PM
	 */
	function updateBestDish($resId,$dishId,$resData)
	{
		$this->db->where('fk_restaurant_id',$resId);
		$this->db->where('fk_dish_id',$dishId);
		$this->db->update('tbl_restaurant_dishes',$resData);
		return $this->db->affected_rows();
	}
	/**
	 * function for get restaurant time to approve by admin 
	 * @author Manisha Kanazariya
	 * Created date: 28-09-2018 11:50 PM
	 */
	function checkRestaurantTime($resId)
	{
		$this->db->where('tbl_restaurant_days.fk_restaurant_id',$resId);
		$this->db->join('tbl_restaurant_days_time','tbl_restaurant_days_time.fk_restaurant_days_id=tbl_restaurant_days.restaurant_days_id','left');
		$this->db->order_by('tbl_restaurant_days_time.restaurant_days_time_id','ASC');
		$this->db->from('tbl_restaurant_days');
		
		$query =$this->db->get();
		//echo $this->db->last_query();exit;
		return $query->result();
	}
	/**
	 * [hideShowDishUrl description]
	 * Description:
	 * @author: Manisha Kanazariya
	 * @CreatedDate:2019-07-09T19:43:12+0530
	 */
	function hideShowDishUrl($data){
		$this->db->where('fk_restaurant_id',$data['fk_restaurant_id']);
		$this->db->where('fk_dish_id',$data['fk_dish_id']);
		$this->db->update('tbl_restaurant_dishes',$data);
		return $this->db->affected_rows();
	}
	/**
	 * [countManager description]
	 * Description:
	 * @author: Manisha Kanazariya
	 * @CreatedDate:2019-07-20T17:40:41+0530
	 */
	function countManager($resId)
	{
		$this->db->select("count(owner_id) as totalManager");
		$this->db->where("restaurant_id",$resId);
		$query =$this->db->get("tbl_restaurant_owners");
		return $query->row;
	}

	/**
	 * function to build query to add restaurant details
	 * @author Rashmi Nayani
	 * Created date: 09/10/2017 7:00 PM
	 */
	function getlocalitylistForAdminPanel($res =null){
		$this->db->select('tbl_locality.*,tbl_restaurants.restaurant_name');
		$this->db->join('tbl_restaurants','tbl_restaurants.restaurant_id =tbl_locality.restaurant_id','left');
		if($res){
			$this->db->where('tbl_locality.restaurant_id',$res);
		}
		$query=$this->db->get('tbl_locality');
		return $query->result();
	}

	function updateLocalityExtraTime($rId,$data)
	{
		$this->db->where('restaurant_id',$rId);
		$this->db->update('tbl_locality',$data);
		return $this->db->affected_rows();
		
	}
 }
