<?php
	/**
	 * Model Name: Driver_model
	 * Descripation: Use to manage the order related details
	 * @author Rashmi Nayani
	 * Created date: 11-10-2017 5:50 PM
	 */
	class Order_model extends CI_Model
	{
	/**
	 * function to build query to get total order count
	 * @author Vaibhav Mehta
	 * Created date: 23/11/2017 04:50 PM
	 */

	


	function getAllOrderCount($resId="",$orderId="",$phone="",$date="",$date1="",$status=null)
	{

		if($resId !=''){
			$this->db->where("tbl_orders.restaurant_id",$resId);
		}
		if($orderId !=""){
			$this->db->where("tbl_orders.order_id like '%$orderId%'");
		}
		if($phone !=""){
			$this->db->where("address.contact_no like '%$phone%'");
		}
		if($date !=""){
			$this->db->where("tbl_orders.order_placed_time >",$date);
		}
		if($date1 !=""){
			$this->db->where("tbl_orders.order_placed_time <",$date1);
		}
		if($status){

			$this->db->where("tbl_orders.order_status",$status);
		}else{
			$this->db->where("tbl_orders.order_status NOT IN('1','0','8')");
		}
		
		$this->db->join('tbl_customer_delivery_address as address','address.address_id=tbl_orders.selected_delivery_address','left');
		$this->db->where('tbl_orders.is_active','1');
		$this->db->order_by('tbl_orders.order_status','ASC');
		$query=$this->db->get('tbl_orders');
		return $query->num_rows();

	}

	function getDisputedOrderCount($resId=""){
		if($resId !=""){
			$this->db->where('restaurant_id',$resId);
		}
		$this->db->where('order_status','8');
		return $this->db->get('tbl_orders')->num_rows();

	}

	/**
	 * function to build query to get the order details
	 * @author Rashmi Nayani
	 * Created date: 11/10/2017 5:50 PM
	 */
	function getAllOrderDetails($resId,$limit=null,$offset=null,$status='',$orderTtype="")
	{
		
		if($limit)
		{
			$this->db->limit($limit,$offset);
		}
		if($resId !=''){
			$this->db->where("tbl_orders.restaurant_id",$resId);
		}
		
		$this->db->select('tbl_orders.*,customer.first_name as c_fname,customer.last_name as c_lname,customer.contact_no,driver.first_name as d_fname,driver.last_name as d_lname,driver.contact_no as d_contact_no,addrfess.customer_name as delivery_name,address.address1 as delivery_ad_line1,address.address2 as delivery_ad_line2,address.zipcode,address.contact_no as delivery_contact_no,res.restaurant_name,tbl_locality.name as city_name');
		$this->db->from('tbl_orders');
		$this->db->join('tbl_users as customer','customer.user_id=tbl_orders.user_id','left');
		$this->db->join('tbl_users as driver','driver.user_id=tbl_orders.delivered_by','left');
		$this->db->join('tbl_customer_delivery_address as address','address.address_id=tbl_orders.selected_delivery_address','left');
		$this->db->join('tbl_locality','tbl_locality.locality_id=address.locality_id','left');
		$this->db->join('tbl_restaurants as res','tbl_orders.restaurant_id=res.restaurant_id','left');
		if($status !=''){
			$this->db->where("tbl_orders.order_status >",0);
		}
		if($orderTtype == "pending") {
			$this->db->where("tbl_orders.order_status >1 AND tbl_orders.order_status < 7");
		}
		$this->db->where('tbl_orders.is_active','1');
		$this->db->group_by('tbl_orders.order_id');
		$this->db->order_by('order_id','desc');
		$query=$this->db->get();
		return $query->result();
	}

	/**
	 * function to build query to get dish category list
	 * @author Rashmi Nayani
	 * Created date: 12/10/2017 3:30 PM
	 */
	function getCategoryList(){
		$this->db->where('is_active','1');
		$query=$this->db->get('tbl_dish_category');
		return $query->result();
		}

	/**
	 * function to build query to get dish category list
	 * @author Rashmi Nayani
	 * Created date: 12/10/2017 3:30 PM
	 */
	function getStateList(){
		$this->db->where('is_active','1');
		$this->db->where('country_id','117');
		$query=$this->db->get('state');
		return $query->result();
	}
	/**
	 * function to build query to get restaurant list
	 * @author Rashmi Nayani
	 * Created date: 12/10/2017 4:40 PM
	 */
	function getRestaurantList(){
		$this->db->where('is_active','1');
		$query=$this->db->get('tbl_restaurants');
		return $query->result();
	}

	/**
	 * function to build query to get city list
	 * @author Rashmi Nayani
	 * Created date: 12/10/2017 4:40 PM
	 */
	function getCity($id){

		$this->db->where('is_active','1');
		$this->db->where('state_id',$id);
		$query=$this->db->get('city');
		return $query->result();
	}

	/**
	 * function to build query to get dish list
	 * @author Rashmi Nayani
	 * Created date: 7/11/2017 1:20 PM
	 */
	function getDish($id=null,$did=null){

		$this->db->where('is_active','1');
		if ($id) {
			$this->db->where('category_id',$id);
			
		}
		if ($did) {
			$this->db->where('product_id',$did);
			
		}
		$query=$this->db->get('tbl_dishes');
		return $query->result();
	}

	/**
	 * function to build query to get dish choices
	 * @author Rashmi Nayani
	 * Created date: 7/11/2017 7:20 PM
	 */
	function getDishChoice($id){

		$this->db->select("ch_cat.choice_category_name,ch_cat.choice_category_id,ch_cat.is_multipl,CONCAT(GROUP_CONCAT(ch.choice_name,'**',ch.choice_id  SEPARATOR '##')) as message");
		$this->db->from('tbl_dish_choice as di_ch');
		$this->db->join('tbl_choice as ch','ch.choice_id=di_ch.fk_choice_id','left');
		$this->db->join('tbl_choice_category as ch_cat','ch_cat.choice_category_id=ch.fk_choice_category_id','left');
		$this->db->where('di_ch.is_active','1');
		$this->db->where('fk_dish_id',$id);
		$this->db->group_by('choice_category_id');
		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * function to build query to get order details
	 * @author Rashmi Nayani
	 * Created date: 7/11/2017 7:20 PM
	 */

	function getOrderDetails($oid)
	{
		$this->db->select("ord.*,od.order_detail_id,od.amount,od.quantity,dish.*,choice.*,choice_cat.choice_category_name");
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
	 * function to build query to update orders
	 * @author Vaibhav Mehta
	 * Created date: 16/11/2017 12:20 PM
	*/
	function editOrderDetail($data,$id)
	{
		$this->db->where('user_id',$id);
		$query = $this->db->update("tbl_users",$data);
		return $this->db->affected_rows();
	}

	function getAllCountries()
	{
		$query=$this->db->get('countries');
		return $query->result();
	}
	
	/**
	 * function to build query to update order details
	 * @author Vaibhav Mehta
	 * Created date: 16/11/2017 01:20 PM
	*/
	function updateOrderDetails($oid,$updateOrder)
	{
		$this->db->where('order_id',$oid);
		$query = $this->db->update("tbl_order_details",$updateOrder);
		return $this->db->affected_rows();
	}

	function updateOrder($oid,$updateOrder)
	{	
	
		$this->db->where('order_id',$oid);
		$query = $this->db->update("tbl_orders",$updateOrder);

		return $this->db->affected_rows();
	}

	function deleteOrder($oid)
	{
		$this->db->where('order_id',$oid);
		$query = $this->db->delete("tbl_orders");
		return $this->db->affected_rows();
	}

	function updateOrderDriver($oid,$updateOrder)
	{
		$this->db->where('order_id',$oid);
		$query = $this->db->update("tbl_orders",$updateOrder);
		return $this->db->affected_rows();
	}

	/**
	 * function to build query to update order dish choice
	 * @author Vaibhav Mehta
	 * Created date: 16/11/2017 03:20 PM
	*/
	function updateOrderDishChoices($oid,$updateOrder)
	{
		$this->db->where('fk_order_id',$oid);
		$query = $this->db->update("tbl_order_dish_choice",$updateOrder);
		return $this->db->affected_rows();
	}
	
	/**
	 * function to build query to get all driver details of restaurant
	 * @author Vaibhav Mehta
	 * Created date: 16/11/2017 06:20 PM
	*/
	function getDrivers($oid,$did=null)
	{
		if($did)
		{
			$this->db->where("tbl_orders.delivered_by",$did);
			$this->db->where("tbl_users.user_id",$did);
		}
		$this->db->select('tbl_users.user_id,tbl_users.first_name,tbl_users.last_name,tbl_users.contact_no,tbl_orders.delivered_by');
		$this->db->from('tbl_orders');
		$this->db->join('tbl_users','tbl_orders.restaurant_id=tbl_users.fk_restaurant_id','left');
		$this->db->where("tbl_orders.order_id",$oid);
		$this->db->where("tbl_users.is_active","1");
		$this->db->where("tbl_users.role_id",'4');
		$this->db->order_by("tbl_users.user_id","desc");
		$query = $this->db->get();
		return $query->result();
	}	

	/**
	 * function to build query to get today's total order count
	 * @author Vaibhav Mehta
	 * Created date: 05/01/2018 12:50 PM
	 */
	function getTodayOrderCount($resId)
	{
		$startdate 	= date("Y-m-d 00:00:00");
		$enddate 	= date("Y-m-d 23:59:59");
		if($resId !="")
		{
			$this->db->where("tbl_orders.restaurant_id",$resId);
		}
		$this->db->select('tbl_orders.*,customer.first_name as c_fname,customer.last_name as c_lname,customer.contact_no,driver.first_name as d_fname,driver.last_name as d_lname,driver.contact_no as d_contact_no,address.customer_name as delivery_name,address.address1 as delivery_ad_line1,address.address2 as delivery_ad_line2,address.zipcode,address.contact_no as delivery_contact_no,res.restaurant_name');
		$this->db->from('tbl_orders');
		$this->db->join('tbl_users as customer','customer.user_id=tbl_orders.user_id','left');
		$this->db->join('tbl_users as driver','driver.user_id=tbl_orders.delivered_by','left');
		$this->db->join('tbl_customer_delivery_address as address','address.address_id=tbl_orders.selected_delivery_address','left');
		$this->db->join('tbl_restaurants as res','tbl_orders.restaurant_id=res.restaurant_id','left');
		$this->db->where('tbl_orders.is_active','1');
		$this->db->where('tbl_orders.order_status >0 AND tbl_orders.order_status < 	7');
		$this->db->where('tbl_orders.order_placed_time >',$startdate);
		$this->db->where('tbl_orders.order_placed_time <',$enddate);
		$this->db->where('tbl_orders.order_placed_time !=',"0000-00-00 00:00:00");
		$this->db->group_by('tbl_orders.order_id');
		$this->db->order_by('order_id','desc');
		$query=$this->db->get();

		return $query->num_rows();
	}

	/**
	 * function to build query to get today's order details
	 * @author Vaibhav Mehta
	 * Created date: 05/01/2018 1:30 PM
	 */
	function getTodayOrderDetails($resId,$limit=null,$offset=null,$search=null)
	{
		$startdate 	= date("Y-m-d 00:00:00");
		$enddate 	= date("Y-m-d 23:59:59");
		if($resId)
		{
			$this->db->where("tbl_orders.restaurant_id",$resId);
		}
		if($limit)
		{
			$this->db->limit($limit,$offset);
		}
		if($search!=null){

			$where = "(c_fname like '%$search%' OR c_lname like '%$search%')";
			$this->db->where($where);
		}
		
		$this->db->select('tbl_orders.*,customer.first_name as c_fname,customer.last_name as c_lname,customer.contact_no,driver.first_name as d_fname,driver.last_name as d_lname,driver.contact_no as d_contact_no,address.customer_name as delivery_name,address.address1 as delivery_ad_line1,address.address2 as delivery_ad_line2,address.zipcode,address.contact_no as delivery_contact_no,res.restaurant_name,tbl_locality.name as city_name');
		$this->db->from('tbl_orders');
		$this->db->join('tbl_users as customer','customer.user_id=tbl_orders.user_id','left');
		$this->db->join('tbl_users as driver','driver.user_id=tbl_orders.delivered_by','left');
		$this->db->join('tbl_customer_delivery_address as address','address.address_id=tbl_orders.selected_delivery_address','left');
		$this->db->join('tbl_locality','tbl_locality.locality_id=address.locality_id','left');
		$this->db->join('tbl_restaurants as res','tbl_orders.restaurant_id=res.restaurant_id','left');
		$this->db->where('tbl_orders.is_active','1');
		$this->db->where('tbl_orders.order_status >0 AND tbl_orders.order_status < 	7');
		$this->db->where('tbl_orders.order_placed_time >',$startdate);
		$this->db->where('tbl_orders.order_placed_time <',$enddate);
		$this->db->where('tbl_orders.order_placed_time !=',"0000-00-00 00:00:00");
		$this->db->group_by('tbl_orders.order_id');
		$this->db->order_by('order_id','desc');
		$query=$this->db->get();
		return $query->result();
	}

	/**
	 * function to build query to get total new order count
	 * @author Vaibhav Mehta
	 * Created date: 05/01/2018 02:50 PM
	 */
	function getOrderCount($resId="",$status=null)
	{
		if($resId !="")
		{
			$this->db->where("tbl_orders.restaurant_id",$resId);
		}
		
		$this->db->from('tbl_orders');
		$this->db->join('tbl_users as customer','customer.user_id=tbl_orders.user_id','left');
		$this->db->join('tbl_users as driver','driver.user_id=tbl_orders.delivered_by','left');
		$this->db->join('tbl_customer_delivery_address as address','address.address_id=tbl_orders.selected_delivery_address','left');
		$this->db->join('tbl_restaurants as res','tbl_orders.restaurant_id=res.restaurant_id','left');
		$this->db->where('tbl_orders.is_active','1');
		if($status)
		{
			$this->db->where('tbl_orders.order_status',$status);
		}
		else
		{
			$this->db->where("tbl_orders.order_status NOT IN('0')");
		}
		$this->db->group_by('tbl_orders.order_id');
		$this->db->order_by('order_id','desc');
		$query=$this->db->get();
		return $query->num_rows();
	}

	/**
	 * function to build query to get the Disputed/Discarded  order Counts
	 * @author Manisha Kanazariya 
	 * Created date: 05/03/2018 11:50 PM
	 */
	function getOrderCountByStatus($resId,$status=null)
	{
		if($resId != "")
		{
			$this->db->where("tbl_orders.restaurant_id",$resId);
		}
		
		$this->db->select('tbl_orders.*,customer.first_name as c_fname,customer.last_name as c_lname,customer.contact_no,driver.first_name as d_fname,driver.last_name as d_lname,driver.contact_no as d_contact_no,address.customer_name as delivery_name,address.address1 as delivery_ad_line1,address.address2 as delivery_ad_line2,address.zipcode,address.contact_no as delivery_contact_no,res.restaurant_name,discard_by.role_id as discard_by');
		$this->db->from('tbl_orders');
		$this->db->join('tbl_users as customer','customer.user_id=tbl_orders.user_id','left');
		$this->db->join('tbl_users as discard_by','discard_by.user_id=tbl_orders.updated_by','left');
		$this->db->join('tbl_users as driver','driver.user_id=tbl_orders.delivered_by','left');
		$this->db->join('tbl_customer_delivery_address as address','address.address_id=tbl_orders.selected_delivery_address','left');

		$this->db->join('tbl_locality','tbl_locality.locality_id=address.locality_id','left');
		$this->db->join('tbl_restaurants as res','res.restaurant_id=tbl_orders.restaurant_id','left');
		$this->db->where('tbl_orders.is_active','1');
		if($status == '13'){
			$this->db->where("tbl_orders.order_status IN('13','14')");
		}else if($status == '9'){
			$this->db->where("tbl_orders.order_status IN('9','10')");
		}else if($status == '11'){
			$this->db->where("tbl_orders.order_status IN('11','12')");
		}else{
			$this->db->where('tbl_orders.order_status',$status);
		}
		$this->db->group_by('tbl_orders.order_id');
		$this->db->order_by('order_id','desc');
		$query=$this->db->get();
		return $query->num_rows();
	}

	/**
	 * function to build query to get the Disputed/Discarded  order Details
	 * @author Manisha Kanazariya 
	 * Created date: 05/03/2018 11:50 PM
	 */
	function getOrderByStatusDetails($resId,$limit=null,$offset=null,$status=null)
	{
		if($limit)
		{
			$this->db->limit($limit,$offset);
		}
		/*if($search!=null){

			$where = "(c_fname like '%$search%' OR c_lname like '%$search%')";
			$this->db->where($where);
		}*/
		if($resId !="")
		{
			$this->db->where("tbl_orders.restaurant_id",$resId);
		}
		
		$this->db->select('tbl_orders.*,customer.first_name as c_fname,customer.last_name as c_lname,customer.contact_no,driver.first_name as d_fname,driver.last_name as d_lname,driver.contact_no as d_contact_no,address.customer_name as delivery_name,address.address1 as delivery_ad_line1,address.address2 as delivery_ad_line2,address.zipcode,address.contact_no as delivery_contact_no,res.restaurant_name,discard_by.role_id as discard_by,tbl_locality.name as city_name');
		$this->db->from('tbl_orders');
		$this->db->join('tbl_users as customer','customer.user_id=tbl_orders.user_id','left');
		$this->db->join('tbl_users as discard_by','discard_by.user_id=tbl_orders.updated_by','left');
		$this->db->join('tbl_users as driver','driver.user_id=tbl_orders.delivered_by','left');
		$this->db->join('tbl_customer_delivery_address as address','address.address_id=tbl_orders.selected_delivery_address','left');
		$this->db->join('tbl_locality','tbl_locality.locality_id=address.locality_id','left');
		$this->db->join('tbl_restaurants as res','res.restaurant_id=tbl_orders.restaurant_id','left');
		$this->db->where('tbl_orders.is_active','1');
		if($status == '13'){
			$this->db->where("tbl_orders.order_status IN('13','14')");
		}else if($status == '9'){
			$this->db->where("tbl_orders.order_status IN('9','10')");
		}else if($status == '11'){
			$this->db->where("tbl_orders.order_status IN('11','12')");
		}else{
			$this->db->where('tbl_orders.order_status',$status);
		}
		$this->db->group_by('tbl_orders.order_id');
		$this->db->order_by('order_id','desc');
		$query=$this->db->get();
		//echo $this->db->last_query(); exit;
		return $query->result();
	}

	/**
	 * function to build query to get total order count
	 * @author Manisha  Kanazariya
	 * Created date: 28/06/2018 10:50 AM
	 */

	function getNewOrderDetails($resId="",$limit=null,$offset=null,$orderId="",$phone="",$date="",$date1="",$status=null)
	{

		if($limit)
		{
			$this->db->limit($limit,$offset);
		}
		if($resId !=''){
			$this->db->where("od.restaurant_id",$resId);
		}
		if($orderId !=""){
			$this->db->where("od.order_id like '%$orderId%'");
		}
		if($phone !=""){
			$this->db->where("address.contact_no like '%$phone%'");
		}
		if($date !=""){
			$this->db->where("od.order_placed_time >=",$date);
		}
		if($date1 !=""){
			$this->db->where("od.order_placed_time <",$date1);
		}
		if($status){
			$this->db->where("od.order_status",$status);
		}else{
			$this->db->where("od.order_status NOT IN('1','0','8')");
		}
		$this->db->select('od.order_id,od.order_placed_time as order_time,od.order_type as paymnet,od.total_price as amount,od.delivery_charges,od.order_status as status,address.customer_name as name,address.contact_no,address.address1 as address,res.restaurant_name,lc.name as area');
		$this->db->from('tbl_orders as od');
		$this->db->join('tbl_customer_delivery_address as address','address.address_id=od.selected_delivery_address','left');
		$this->db->join('tbl_restaurants as res','od.restaurant_id=res.restaurant_id','left');
		$this->db->join('tbl_locality as lc','lc.locality_id=address.locality_id','left');
		$this->db->where('od.is_active','1');
		$this->db->order_by('od.order_id','DESC');
		$query=$this->db->get();
		return $query->result();
	}

	/**
	 * function to build query to get custom delivery time of restaurant
	 * @author Vaibhav Mehta
	 * Created date: 05/01/2018 5:50 PM
	 */
	function getCustomDeliveryTime($oid,$locality_id=null)
	{
		$this->db->select("ord.*,loc.*");
		$this->db->from("tbl_orders as ord");
		$this->db->join("tbl_restaurants as res","ord.restaurant_id=res.restaurant_id","left");
		$this->db->join("tbl_locality as loc","loc.restaurant_id=res.restaurant_id","left");
		$this->db->where("ord.order_id",$oid);
		if($locality_id !=null)
		{
			$this->db->where("loc.locality_id",$locality_id);
		}

		$this->db->where("ord.is_active","1");
		$this->db->where("res.is_active","1");

		$query=$this->db->get();
	
		return $query->result();
	}


	/**
	 * function to build query to get order data
	 * @author Umang Kothari
	 * Created date: 05/01/2018 5:50 PM
	 */
	
	function getOrderData($orderId)
	{
		$this->db->where('order_id',$orderId);
		$query=$this->db->get('tbl_orders');
		return $query->result();	
	}

	/**
	 * function to build query to get order  details
	 * @authorManisha Kanazariya
	 * Created date: 11/4/2018 5:50 PM
	 */

	function getOrderDetailsData($orderId)
	{
		$this->db->where('order_id',$orderId);
		$query=$this->db->get('tbl_order_details');
		return $query->result();	

	}
	/**
	 * function to build query to get order dish choice details
	 * @authorManisha Kanazariya
	 * Created date: 11/4/2018 5:50 PM
	 */

	function getOrderDishDetail($order_detail_id)
	{
		$this->db->where('fk_order_detail_id',$order_detail_id);
		$query=$this->db->get('tbl_order_dish_choice');
		return $query->result();	

	}

	/**
	 * function to build query to add order data
	 * @author Umang Kothari
	 * Created date: 05/01/2018 5:50 PM
	 */
	
	function addOrderData($data)
	{

		$query=$this->db->insert('tbl_orders',$data);
		return $this->db->insert_id();	
	}

	/**
	 * function to build query to add order  details
	 * @authorManisha Kanazariya
	 * Created date: 11/4/2018 5:50 PM
	 */

	function addOrderDetailsData($data)
	{
		
		$query=$this->db->insert('tbl_order_details',$data);
		return $this->db->insert_id();	

	}
	/**
	 * function to build query to add order dish choice details
	 * @authorManisha Kanazariya
	 * Created date: 11/4/2018 5:50 PM
	 */

	function addOrderDishDetail($data)
	{
		
		$query=$this->db->insert('tbl_order_dish_choice',$data);
		return $this->db->insert_id();	

	}

}
