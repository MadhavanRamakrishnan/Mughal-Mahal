<?php
	/**
	 * Model Name: Rating_model
	 * Descripation: Use to manage the restaurant related details
	 * @author Vaibhav mehta
	 * Created date: 28-09-2017 4:00 PM
	 */
class Rating_model extends CI_Model
{
	/**
	 * this function call for getting details of restaurants
	 * @author Vaibhav Mehta
	 * Created date: 30-09-2017 6:50 PM
	 */
	function getAllrestaurantRatingCount($resId)
	{
		if($resId)
		{
			$this->db->where("tbl_restaurant_ratings.restaurant_id",$resId);
		}
		$this->db->from('tbl_restaurant_ratings');
		$this->db->where('is_active','1');
		$query=$this->db->get();
		return $query->num_rows();
	}

	/**
	 * function to build query to get restaurant owner
	 * @author Rashmi Nayani
	 * Created date: 09/10/2017 3:30 PM
	 */
	function getAllRestaurantAvelibility($resId)
	{
		if($resId)
		{
			$this->db->where("tbl_restaurants.restaurant_id",$resId);
		}
		$this->db->select("tbl_restaurants. `restaurant_id`, `restaurant_name`,`banner_image`, `custom_delivery_time`, tbl_restaurants.`is_active` , AVG(tbl_restaurant_ratings.rating) as avg_rate");
		$this->db->from("tbl_restaurants");
		$this->db->join("tbl_restaurant_ratings","tbl_restaurant_ratings.restaurant_id = tbl_restaurants.restaurant_id","left");
		$this->db->where('tbl_restaurants.is_active','1');
		$this->db->group_by("tbl_restaurants.restaurant_id");
		$query = $this->db->get();
		return $query->result();
	}


	/**
	 * function to build query to get restaurant for manager
	 * @author Manisha Kanazariya	
	 * Created date: 14/02/2018 12:30 PM
	 */
	function getRestaurantAvelibility($resId,$manager)
	{
		if($resId)
		{
			$this->db->where("tbl_restaurants.restaurant_id",$resId);
		}
		$this->db->select("tbl_restaurants. `restaurant_id`, `restaurant_name`,`banner_image`, `custom_delivery_time`, tbl_restaurants.`is_availability` , AVG(tbl_restaurant_ratings.rating) as avg_rate");
		$this->db->from("tbl_restaurants");
		$this->db->join("tbl_restaurant_ratings","tbl_restaurant_ratings.restaurant_id = tbl_restaurants.restaurant_id","left");
		$this->db->where('tbl_restaurants.is_active','1');
		$this->db->group_by("tbl_restaurants.restaurant_id");
		$query = $this->db->get();
		return $query->result();
	}
	/**
	 * function to build query to get restaurant owner
	 * @author Rashmi Nayani
	 * Created date: 09/10/2017 3:30 PM
	 */
	function getAllRestaurantRating($resId,$limit=null,$offset=null,$search=null)
	{
		if($limit)
		{
			$this->db->limit($limit,$offset);
		}
		if($search!=null){

			$where = "(reason like '%$search%' )";
			$this->db->where($where);
		}
		if($resId)
		{
			$this->db->where("tbl_restaurant_ratings.restaurant_id",$resId);
		}
		$this->db->select("*");
		$this->db->from("tbl_restaurant_ratings");
		$this->db->join("tbl_restaurants","tbl_restaurant_ratings.restaurant_id=tbl_restaurants.restaurant_id","left");
		$this->db->join("tbl_users","tbl_restaurant_ratings.user_id=tbl_users.user_id","left");
		$this->db->order_by("rating_id","desc");
		$this->db->where('tbl_restaurants.is_active','1');
		$query=$this->db->get();
		//echo $this->db->last_query(); exit;
		return $query->result();
	}

	/**
	 * function to build query to get total order count
	 * @author Vaibhav Mehta
	 * Created date: 23/11/2017 04:50 PM
	 */
	function getAllDriverRatingCount()
	{
		$this->db->from('tbl_driver_ratings');
		$this->db->where('is_active','1');
		$query=$this->db->get();
		return $query->num_rows();
	}

	/**
	 * function to build query to get restaurant owner
	 * @author Rashmi Nayani
	 * Created date: 09/10/2017 3:30 PM
	 */
	function getAllDriverRating($limit=null,$offset=null,$search=null)
	{
		if($limit)
		{
			$this->db->limit($limit,$offset);
		}
		if($search!=null){

			$where = "(reason like '%$search%' )";
			$this->db->where($where);
		}
		$this->db->select("*");
		$this->db->from("tbl_driver_ratings");
		$this->db->join("tbl_users","tbl_users.user_id=tbl_driver_ratings.driver_id","left");
		$this->db->order_by("rating_id","desc");

		$query=$this->db->get();
		//echo $this->db->last_query(); exit;
		return $query->result();
	}
	function getAllDriverData($resId,$limit=null,$offset=null,$search=null)
	{ 		
		if($limit)
		{
			$this->db->limit($limit,$offset);
		}
		if($search!=null){

			$where = "(reason like '%$search%' )";
			$this->db->where($where);
		}
	    $this->db->select("tbl_users.user_id,tbl_users.role_id, tbl_users.first_name,tbl_users.last_name ,tbl_users.is_active, AVG(tbl_driver_ratings.rating) as avg_rate, tbl_driver_ratings.reason");
		$this->db->from("tbl_users");
		$this->db->join("tbl_driver_ratings","tbl_driver_ratings.driver_id = tbl_users.user_id","left");

		if($resId)
		{
			$this->db->join("tbl_orders","tbl_driver_ratings.order_id=tbl_orders.order_id","left");
			$this->db->where("tbl_orders.restaurant_id",$resId);
		}

		$this->db->where('tbl_users.role_id','4');
		$this->db->order_by('user_id','desc');
		$this->db->group_by("tbl_users.user_id");
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->result();
	}
	function getAllDriverDataCount($resId)
	{ 
		$driverRole = $this->config->item('driver_role');
	    $this->db->select("tbl_users.user_id,tbl_users.role_id, tbl_users.first_name,tbl_users.last_name ,tbl_users.is_active, AVG(tbl_driver_ratings.rating) as avg_rate");
		$this->db->from("tbl_users");
		$this->db->join("tbl_driver_ratings","tbl_driver_ratings.driver_id = tbl_users.user_id","left");
		$this->db->where('tbl_users.role_id',$driverRole);
		$this->db->group_by("tbl_users.user_id");
		
		if($resId !="")
		{
			$this->db->join("tbl_orders","tbl_driver_ratings.order_id=tbl_orders.order_id","left");
			$this->db->where("tbl_orders.restaurant_id",$resId);
		}

		$query = $this->db->get();
		return $query->num_rows();
	}
	/**
	 * function to build query to get restaurant owner
	 * @author Rashmi Nayani
	 * Created date: 09/10/2017 3:30 PM
	 */
	function getDriverRatingById($id,$limit=null,$offset=null)
	{
		if($limit)
		{
			$this->db->limit($limit,$offset);
		}
		$this->db->select('tbl_driver_ratings.*,tbl_users.first_name ,tbl_users.last_name');
		$this->db->from('tbl_driver_ratings');
		$this->db->join('tbl_orders ','tbl_orders.order_id=tbl_driver_ratings.order_id','left');
		$this->db->join('tbl_users ','tbl_users.user_id=tbl_orders.user_id','left');

		$this->db->where('driver_id',$id);
		$query = $this->db->get();
		return $query->result();
		
	}
	function getDriverRatingByIdCount($id)
	{
		$this->db->select('tbl_driver_ratings.*,tbl_users.first_name ,tbl_users.last_name');
		$this->db->from('tbl_driver_ratings');
		$this->db->join('tbl_orders ','tbl_orders.order_id=tbl_driver_ratings.order_id','left');
		$this->db->join('tbl_users ','tbl_users.user_id=tbl_orders.user_id','left');

		$this->db->where('driver_id',$id);
		$query = $this->db->get();
		return $query->num_rows();
		
		
	}
	function RestaurantsRatingUserWise($id,$limit=null,$offset=null)
	{
		if($limit)
		{
			$this->db->limit($limit,$offset);
		}
		
		$this->db->select('tbl_restaurant_ratings.*,tbl_users.first_name ,tbl_users.last_name');
		$this->db->from('tbl_restaurant_ratings');
		
		$this->db->join('tbl_users ','tbl_users.user_id=tbl_restaurant_ratings.user_id','left');

		$this->db->where('restaurant_id',$id);
		$query = $this->db->get();
		return $query->result();
		
	}
	function RestaurantsRatingUserWiseCount($id)
	{

		$this->db->select('tbl_restaurant_ratings.*,tbl_users.first_name ,tbl_users.last_name');
		$this->db->from('tbl_restaurant_ratings');
		
		$this->db->join('tbl_users ','tbl_users.user_id=tbl_restaurant_ratings.user_id','left');

		$this->db->where('restaurant_id',$id);
		$query = $this->db->get();
		return $query->num_rows();
		
	}
	function getDriverName($id)
	{

		$this->db->select('first_name ,last_name');
		$this->db->from('tbl_users');
		$this->db->where('user_id',$id);
		$query = $this->db->get();
		return $query->result();
		
	}
	function getRestaurantName($id)
	{

		$this->db->select('restaurant_name');
		$this->db->from('tbl_restaurants');
		$this->db->where('restaurant_id',$id);
		$query = $this->db->get();
		return $query->result();
		
	}

	
 }