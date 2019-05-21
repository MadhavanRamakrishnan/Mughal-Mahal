<?php
/** 
 * Author 				: itoneclick.com 
 * Copyright 			: itoneclick.com 
 * Created by 			: Umang Kothari
 * Created Date 		: 17 August 2017 10:00 AM
 * Description 			: Use for various functionality for front-end
 * Updated by 			: 
 * Updated Date 		: 
 * Reason for update 	: 
 */ 
class Home_model extends CI_Model
{

	/**
	 * [getLocality description]
	 * Description:Return localities
	 * @author: Manisha Kanazariya
	 * @CreatedDate:2019-01-01T16:31:50+0530
	 */
	public function getLocality($id='')
	{
		$column =($_COOKIE['lang']=="AR")?'name_ar':'name';
		$this->db->select("locality_id,delivered_time,delivery_charge,min_order_amount,restaurant_id,".$column." as name,lat,lon");
		if($id != "")
		{
			$this->db->where('locality_id',$id);
		}
		$query = $this->db->get('tbl_locality');
//print_r($query);exit;
	//	echo $this->db->last_query();exit;
//print_r($query->result());exit;
		return $query->result();
	}

	public function dishCategory($locality="",$search=null)
	{
		$column =($_COOKIE['lang']=="AR")?'product_ar_name':'product_en_name';
		$this->db->select('tbl_dish_category.*,tbl_locality.locality_id');
		$this->db->join('tbl_restaurant_dishes','tbl_restaurant_dishes.fk_restaurant_id=tbl_locality.restaurant_id','right');
		$this->db->join('tbl_dishes','tbl_dishes.product_id =tbl_restaurant_dishes.fk_dish_id','left');
		$this->db->join('tbl_dish_category','tbl_dish_category.category_id =tbl_dishes.category_id','left');
		if($search)
		{
			$this->db->like('tbl_dishes.'.$column,$search);
		}
		$this->db->where('tbl_locality.locality_id',$locality);
		$this->db->where('tbl_dish_category.is_active', 1);
		$this->db->distinct();
		$query = $this->db->get('tbl_locality');
		return $query->result();

	}
	/**
	 * [getDishData description]
	 * Description:Will return dish details of the locality
	 * @author: Manisha Kanazariya
	 * @CreatedDate:2019-01-01T16:32:26+0530
	 */
	public function getDishData($categoryId,$locality,$search=null)
	{
		$column =($_COOKIE['lang']=="AR")?'product_ar_name':'product_en_name';
		$this->db->select('tbl_dishes.*');
		$this->db->join('tbl_restaurant_dishes','tbl_restaurant_dishes.fk_restaurant_id=tbl_locality.restaurant_id','right');
		$this->db->join('tbl_dishes','tbl_dishes.product_id =tbl_restaurant_dishes.fk_dish_id','left');
		$this->db->join('tbl_dish_category','tbl_dish_category.category_id =tbl_dishes.category_id','left');

		$this->db->where('tbl_dish_category.category_id',$categoryId);
		$this->db->where('tbl_locality.locality_id',$locality);
		$this->db->where('tbl_dishes.is_active','1');
		if($search)
		{
			$this->db->like('tbl_dishes.'.$column,$search);
		}
		$query = $this->db->get('tbl_locality');
		return $query->result();
	}

	/**  
	* Description : get all product  details of cart
	* Created by : Manisha Kanazariya
	* Created Date: 2/6/18 01:30 PM 
	*/ 
	function getCartDishDetail($productId)
	{
		$this->db->from('tbl_dishes');
		$this->db->where('product_id',$productId);
		$this->db->where("is_active","1");
		$query = $this->db->get();
		return $query->result();
	}

	/**  
	* Description : get  Dish details of restaurant
	* Created by : Manisha Kanazariya
	* Created Date: 21/9/18 01:30 PM 
	*/ 
	function getResDishDetail($dishId,$locality)
	{
		$this->db->from('tbl_restaurant_dishes as trd');
		$this->db->join('tbl_locality as tl','tl.restaurant_id=trd.fk_restaurant_id','lelt');
		$this->db->where('trd.fk_dish_id',$dishId);
		$this->db->where("tl.locality_id",$locality);
		$query = $this->db->get();
		return $query->result();
	}

	

	/**  
	* Description :Get  Restaurant dish choice
	* Created by  :Manisha Kanazariya
	* Created Date:21/9/18 01:30 PM 
	*/ 
	function getRestaurantDishChoices($dishId,$locality)
	{
		$this->db->select('rd.*,dish.*');
		$this->db->join('tbl_restaurant_dishes as rd','rd.fk_restaurant_id=lc.restaurant_id','left');
		$this->db->join('tbl_dishes as dish','dish.product_id=rd.fk_dish_id','left');
		$this->db->where('rd.fk_dish_id',$dishId);
		$this->db->where('lc.locality_id',$locality);
		$this->db->from('tbl_locality as lc');
		$query =$this->db->get();
		return $query->result();
	}

	/**
	 * [getDishChoice description]
	 * Description: return dish choice
	 * @author: Manisha Kanazariya
	 * @CreatedDate:2019-01-04T10:40:09+0530
	 */
	function getDishChoice($chV)
	{
		$this->db->join('tbl_choice_category','tbl_choice_category.choice_category_id =tbl_choice.fk_choice_category_id','left');
		$this->db->from('tbl_choice');
		$where ="tbl_choice.choice_id IN ($chV)";
		$this->db->where($where);
		$query =$this->db->get();
		return $query->result();
	}

	/**
	 * Description : Use to Get Choice details with price
	 * Created by : Umang Kothari
	 * Created Date: 17/02/18 06:00 PM 
	*/

	function getChoiceName($choice_id,$dishId=null)
	{
		if($dishId!=null)
		{
			$this->db->where('fk_dish_id',$dishId);
		}
		$this->db->where('choice_id',$choice_id);
		$this->db->join('tbl_dish_choice','tbl_dish_choice.fk_choice_id = tbl_choice.choice_id','left');
		$query = $this->db->get('tbl_choice');
		return $query->result();
	}
	/**
	 * Description : Use to Get Cms Page details
	 * Created by : Umang Kothari
	 * Created Date: 19/03/18 06:00 PM 
	*/	

	function getCmspages($cms_id=null)
	{
		if($cms_id!=null){
			$this->db->where('cms_id',$cms_id);
		}
		$query = $this->db->get('tbl_cms');
		return $query->result();
	}


	function getAllFavouriteDishes($dishes)
	{
		$this->db->select("product_id,product_en_name,product_ar_name,en_description,ar_description,ar_description,dish_image");
		$this->db->where("product_id IN ($dishes)");
		$this->db->from("tbl_dishes");
		$query = $this->db->get();
		return $query->result();
	}


	function getFavouriteDishDetails($locality,$dishId){

		$this->db->where("tbl_locality.locality_id",$locality);
		$this->db->where("tbl_restaurant_dishes.fk_dish_id",$dishId);
		$this->db->join("tbl_locality","tbl_locality.restaurant_id =tbl_restaurant_dishes.fk_restaurant_id","left");
		$this->db->from("tbl_restaurant_dishes");
		$query = $this->db->get();
		return $query->result();

	}

	/**
	 * Description : get rating of dishes
	 * Updated by  : Manisha Kanazariya
	 * Created Date: 14/06/18 03:30 PM 
	*/
	function dishesRating(){
		
		$this->db->select("dish_id,sum(rating) as rating,count(dish_id) as total");
		$this->db->group_by("dish_id");
		$query = $this->db->get("tbl_dish_rating");
		return $query->result();
	}
	/**
	 * Description : check rating of dishes
	 * Updated by  : Manisha Kanazariya
	 * Created Date: 14/06/18 03:30 PM 
	*/
	function checkRating($dish_id,$user_id){
		$this->db->where('dish_id',$dish_id);
		$this->db->where('user_id',$user_id);
		$query = $this->db->get("tbl_dish_rating");
		return $query->result();

	}
	/**
	 * Description : check rating of dishes
	 * Updated by  : Manisha Kanazariya
	 * Created Date: 14/06/18 03:30 PM 
	*/
	function updateDishRating($dish_id,$user_id,$data){
		$this->db->where('dish_id',$dish_id);
		$this->db->where('user_id',$user_id);
		$this->db->update("tbl_dish_rating",$data);
		return $this->db->affected_rows();

	}
	/**
	 * Description : check rating of dishes
	 * Updated by  : Manisha Kanazariya
	 * Created Date: 14/06/18 03:30 PM 
	*/
	function addDishRating($data){
		$this->db->insert("tbl_dish_rating",$data);
		return $this->db->insert_id();

	}

	function allMyorderStatus($user_id,$order_id=""){
		$this->db->select("order_id,order_status as status,user_id,delivered_time,expected_delivery_time");
		$this->db->where('user_id',$user_id);
		if($order_id !=""){
			$this->db->where('order_id',$order_id);
		}
		$query=$this->db->get('tbl_orders');
		return $query->result();
	}

	/**
	 * Description : it is used for set restaurant detals for order summary
	 * Updated by  : Manisha Kanazariya
	 * Created Date: 4/07/18 03:30 PM 
	*/
	function getRestaurantDetail($locality_id,$day =""){
		$this->db->select('rs.restaurant_id,rs.restaurant_name,rs.restaurant_name_ar,rs.banner_image,rs.address,rs.email,rs.contact_no,rs.delivery_contact_no,tbl_locality.min_order_amount,tbl_locality.delivered_time,tbl_locality.delivery_charge');
		$this->db->join('tbl_locality','tbl_locality.restaurant_id =rs.restaurant_id','left');
		
		$this->db->where('tbl_locality.locality_id',$locality_id);
		if($day !=""){
			$this->db->select('rt.*');
			$this->db->join('tbl_restaurant_days as rd','rd.fk_restaurant_id =tbl_locality.restaurant_id','left');
			$this->db->join('tbl_restaurant_days_time as rt','rt.fk_restaurant_days_id =rd.restaurant_days_id','left');
			$this->db->where('rd.day',(int)$day);
			$this->db->where('rt.is_approved',1);

		}
		$this->db->from('tbl_restaurants as rs');
		
		return $this->db->get()->result();

	}
	/**
	 * function to build query to get order details
	 * @author Manisha Kanazariya
	 * Created date: 7/7/2018 7:20 PM
	 */

	function getOrderDetails($oid)
	{
		$this->db->select("ord.order_id,ord.user_id,ord.restaurant_id,ord.total_price,ord.expected_delivery_time as expected_del_time,ord.delivered_time,ord.order_status,ord.reason,ord.selected_delivery_address,ord.delivered_by,ord.delivery_charges,od.order_detail_id,od.amount,od.quantity,dish.*,choice.*");
		$this->db->from('tbl_orders as ord'); 
		$this->db->join('tbl_order_details as od','od.order_id=ord.order_id','left');
		$this->db->join('tbl_dishes as dish','dish.product_id=od.product_id','left');
		$this->db->join('tbl_order_dish_choice as ord_ch','ord_ch.fk_order_detail_id=od.order_detail_id  AND ord_ch.fk_dish_id=od.product_id','left');
		$this->db->join('tbl_choice as choice','choice.choice_id=ord_ch.fk_choice_id','left');
		$this->db->join('tbl_choice_category as choice_cat','choice_cat.choice_category_id=choice.fk_choice_category_id','left');

		$this->db->where("ord.order_id",$oid);
		$query = $this->db->get();
		
		return $query->result();
	}

	/**
	 * Description : it is used for get restaurant rating
	 * Updated by  : Manisha Kanazariya
	 * Created Date: 4/07/18 03:30 PM 
	*/

	function getRestaurantRating($order_id){
		$this->db->where('order_id',$order_id);

		$query=$this->db->get("tbl_restaurant_ratings");
		return $query->result();
	}
	/**
	 * Description : it is used for get driver rating
	 * Updated by  : Manisha Kanazariya
	 * Created Date: 4/07/18 03:30 PM 
	*/
	function getDriverRating($order_id){
		$this->db->where('order_id',$order_id);
		$query=$this->db->get("tbl_driver_ratings");
		return $query->result();
	}

	/**
	 * Description : it is used for add restaurant rating
	 * Updated by  : Manisha Kanazariya
	 * Created Date: 7/07/18 03:30 PM 
	*/

	function addRestaurantRating($data){
		$this->db->insert("tbl_restaurant_ratings",$data);
		return $this->db->insert_id();
	}
	/**
	 * Description : it is used for add restaurant rating
	 * Updated by  : Manisha Kanazariya
	 * Created Date: 7/07/18 03:30 PM 
	*/

	function addDriverRating($data){
		$this->db->insert("tbl_driver_ratings",$data);
		return $this->db->insert_id();
	}

	/**
	 * Description : it is used get most selling dish of ordres ofthe last month/current month 
	 * Updated by  : Manisha Kanazariya
	 * Created Date: 11/07/18 03:30 PM 
	*/

	function mostSellingDishes($sdate,$edate){

		$this->db->join('tbl_order_details as os','os.order_id =od.order_id');
		$this->db->join('tbl_dishes','tbl_dishes.product_id=os.product_id');

		if(isset($_COOKIE['lang']) && $_COOKIE['lang']!='EN' && in_array($_COOKIE['lang'],$this->supported_language))
		{
			$this->db->select('tbl_dishes.product_ar_name as dishName,tbl_dishes.ar_description as description,tbl_dishes.dish_image');
		}
		else
		{
			$this->db->select('tbl_dishes.product_en_name as dishName,tbl_dishes.en_description as description,tbl_dishes.dish_image');
		}
		$this->db->select('count(os.product_id) as TotalSold');
		$this->db->group_by('os.product_id');
		$this->db->order_by('TotalSold','desc');
		if($sdate !=""){
			$this->db->where("od.delivered_time  >='$sdate'");
		}
		if($edate !=""){
			$this->db->where("od.delivered_time  <= '$edate'");
		}
		$this->db->where("od.order_status IN (7,13)");
	    $this->db->limit(1,0);
		$this->db->from('tbl_orders as od');
		
		return $this->db->get()->result();
	}

	/**
	 * Description : it is used check if user og the google id is exist or not
	 * Updated by  : Manisha Kanazariya
	 * Created Date: 12/07/18 03:30 PM 
	*/
	function ExistGoogleUser($google_id,$type=5){

		$this->db->where("google_id",$google_id);
		$this->db->where("is_active","1");
		$query=$this->db->get("tbl_users");
		return $query->result();
	}

	function minimumOrderAmount($locality){
		$this->db->where('locality_id',$locality);
		$this->db->select('min_order_amount');
		return $this->db->get('tbl_locality')->result();
	}
	/**
	 * Description:used to set data for repeat order
	 * @author: Manisha Kanazariya
	 * @CreatedDate:2018-08-27 T15:04:20+0530
	 */
	function getRepeatOrder($oId)
	{
		$this->db->select("od.order_id,od.order_detail_id,od.product_id,od.quantity as dishcount, od.description,ch.choice_id,chc.is_multiple,chc.choice_category_id,tbl_locality.locality_id,tbl_locality.name,tbl_locality.name_ar");
		$this->db->join('tbl_order_dish_choice as odc','odc.fk_order_detail_id=od.order_detail_id',"left");
		$this->db->join('tbl_dishes','tbl_dishes.product_id=od.product_id',"left");
		$this->db->join('tbl_choice as ch','ch.choice_id=odc.fk_choice_id',"left");
		$this->db->join('tbl_choice_category as chc','chc.choice_category_id=ch.fk_choice_category_id',"left");
		$this->db->join('tbl_orders','tbl_orders.order_id=od.order_id',"left");
		$this->db->join('tbl_customer_delivery_address','tbl_customer_delivery_address.address_id=tbl_orders.selected_delivery_address',"left");
		$this->db->join('tbl_locality','tbl_locality.locality_id=tbl_customer_delivery_address.locality_id',"left");
		$this->db->from('tbl_order_details as od');
		$this->db->where('od.order_id',$oId);
		$query =$this->db->get();
		return $query->result();
	}



	/**  
	* Description : get  best dishes of restaurant
	* Created by : Manisha Kanazariya
	* Created Date: 21/9/18 01:30 PM 
	*/ 
	function getBestDishes($locality)
	{
		$this->db->select('tbl_dishes.*,tbl_locality.*,tbl_restaurants.*');
		$this->db->join('tbl_restaurant_dishes','tbl_restaurant_dishes.fk_restaurant_id=tbl_locality.restaurant_id','left');
		$this->db->join('tbl_restaurants','tbl_restaurants.restaurant_id=tbl_locality.restaurant_id','left');
		$this->db->join('tbl_dishes','tbl_dishes.product_id=tbl_restaurant_dishes.fk_dish_id','left');
		$this->db->where('tbl_locality.locality_id',$locality);
		$this->db->where('tbl_restaurant_dishes.is_best_dishes',1);
		$this->db->from('tbl_locality');
		$query =$this->db->get();
		return $query->result();
	}
	/**  
	* Description : get  restaurant data
	* Created by : Manisha Kanazariya
	* Created Date: 21/9/18 01:30 PM 
	*/ 
	function getRestaurant($restaurant_id)
	{
		$this->db->where('restaurant_id',$restaurant_id);
		$query =$this->db->get('tbl_restaurants');
		return  $query->result();
	}
	/**  
	* Description : get  driver data
	* Created by : Manisha Kanazariya
	* Created Date: 21/9/18 01:30 PM 
	*/ 
	function getDriver($user_id)
	{
		$this->db->where('user_id',$user_id);
		$query =$this->db->get('tbl_users');
		return $query->result();
	}
	/**  
	* Description : get  Delivery address
	* Created by : Manisha Kanazariya
	* Created Date: 21/9/18 01:30 PM 
	*/ 
	function getDeliveryAdd($addId)
	{
		$this->db->where('address_id',$addId);
		$query =$this->db->get('tbl_customer_delivery_address');
		return $query->result();
	}

	/**  
	* Description : store order payment data
	* Created by : Manisha Kanazariya
	* Created Date: 21/9/18 01:30 PM 
	*/ 
	function saveTransactionData($data){
		$this->db->insert('tbl_order_transaction',$data);
		return $this->db->insert_id();
	}
	/**  
	* Description : remove order data
	* Created by  : Manisha Kanazariya
	* Created Date: 21/9/18 01:30 PM 
	*/ 
	function deleteOrder($orderId){
		$this->db->where('order_id',$orderId);
		$this->db->delete('tbl_orders');
	}
	function deleteOrderDetail($orderId){
		$this->db->where('order_id',$orderId);
		$this->db->delete('tbl_order_details');
	}
	function deleteOrderDishChoice($orderId){
		$this->db->where('fk_order_id',$orderId);
		$this->db->delete('tbl_order_dish_choice');
	}

	/**
	 * [getOrderDetails To get Restaurant time]
	 * @author Hardik Ghadshi
	 * @Created Date   2019-03-15T11:13:41+0530
	 * @param   [type] $restaurantId            [description]
	 * @param   [type] $placeTime               [description]
	 * @return  [type]                          [description]
	 */
	function getRestaurantTime($restaurantId, $placeTime)
	{
		// $this->db->select("GROUP_CONCAT(from_time,'-',to_time) as time");
		$this->db->join('tbl_restaurant_days','tbl_restaurants.restaurant_id = tbl_restaurant_days.fk_restaurant_id','INNER');
		$this->db->join('tbl_restaurant_days_time','tbl_restaurant_days_time.fk_restaurant_days_id = tbl_restaurant_days.restaurant_days_id','INNER');
		$this->db->where('tbl_restaurants.restaurant_id',$restaurantId);
		$this->db->where('tbl_restaurant_days.day',$placeTime);

		$query = $this->db->get('tbl_restaurants');
		
		return $query->row();
	}
	
}
