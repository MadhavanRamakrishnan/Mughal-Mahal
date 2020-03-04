<?php
	/**
	 * Model Name: Customer Model
	 * Descripation: Use to manage the customer related details
	 * @author Vaibhav Mehta
	 * Created date: 23-11-2017 5:50 PM
	 */
class Customer_model extends CI_Model
{
	/**
	 * function to build query to get the order details
	 * @author Vaibhav Mehta
	 * Created date: 23/11/2017 5:50 PM
	*/
	function getAllCustomers($resId,$rid,$limit=null,$offset=null,$value="",$search=null)
	{
		if($limit)
		{
			$this->db->limit($limit,$offset);
		}
		if($search!=null){

			$where = "(first_name like '%$search%' OR last_name like '%$search%')";
			$this->db->where($where);
		}
		if($resId)
		{
			$this->db->where("ord.restaurant_id",$resId);
		}
		if($value)
		{
			$this->db->where("(users.first_name like '%$value%' OR users.last_name like '%$value%' OR concat(users.first_name,' ',users.last_name) like '%$value%' OR users.email like '%$value%' OR users.contact_no like '%$value%')");
		}
		$this->db->select("ord.order_id,users.user_id,COUNT(ord.order_id) as totalOrders,SUM(ord.total_price) as totalAmount,users.first_name,users.last_name,users.email,users.contact_no,users.profile_photo,users.dob");
		$this->db->from("tbl_users as users");
		$this->db->join("tbl_orders as ord","ord.user_id=users.user_id","left");
		$this->db->where("users.role_id",$rid);
		$this->db->where("users.is_active","1");
		$this->db->group_by("users.user_id");
		// $this->db->order_by("users.first_name","ASC");

		$query=$this->db->get();
		// echo $this->db->last_query(); exit;
		return $query->result();
	}

	/**
	 * function to build query to get total order count
	 * @author Vaibhav Mehta
	 * Created date: 23/11/2017 04:50 PM
	 */
	function getAllCustomersCount($resId,$value="")
	{

		$customerRole = $this->config->item("customer_role");
		if($resId)
		{
			//$this->db->where("ord.restaurant_id",$resId); //Old code : Rajesh
			$this->db->where("users.fk_restaurant_id",$resId);
		}
		if($value)
		{
			$this->db->where("users.first_name like '%$value%'");
			$this->db->or_where("users.email like '%$value%'");
			$this->db->or_where("users.contact_no like '%$value%'");
		}
		$this->db->select("COUNT(*) as customer_count");
		// $this->db->select("ord.*,users.user_id,COUNT(ord.order_id) as totalOrders,SUM(ord.total_price) as totalAmount,users.first_name,users.last_name,users.email,users.contact_no,users.profile_photo,users.dob");
		$this->db->from("tbl_users as users");
		// $this->db->join("tbl_orders as ord","ord.user_id=users.user_id","left");
		$this->db->where("users.role_id",$customerRole);
		$this->db->where("users.is_active","1");
		/*$this->db->group_by("users.user_id");
		$this->db->order_by("users.first_name","ASC");*/

		$query=$this->db->get();
		// echo $this->db->last_query(); exit;
		// print_r($query->result());exit();
		return $query->result();
	}



	
	/**
	 * function to build query to add Driver details
	 * @author Vaibhav Mehta
	 * Created date: 01/12/2017 03:15 PM
	 */
	function insertData($tableName,$data){
		$this->db->insert($tableName,$data);
		return $this->db->insert_id();
	}

	/**
	 * function to build query to check duplicate email exist
	 * @author Vaibhav Mehta
	 * Created date: 09/10/2017 5:45 PM
	 */
	function getDuplicateEmail($customerRole,$email)
	{
		$this->db->where('email',$email);
		$this->db->where('is_active','1');
		$this->db->where('role_id',$customerRole);
		$query=$this->db->get('tbl_users');
		return $query->result();
	}

	/**
	 * function to build query to get the customer details
	 * @author Vaibhav Mehta
	 * Created date: 01/12/2017 06:30 PM
	 */
	function getAllCustomerDetails($rid,$id=null)
	{
		if($id)
		{
			$this->db->where('tbl_users.user_id',$id);
		}
		$this->db->select('tbl_users.*,tbl_language.lang_name');
		$this->db->from('tbl_users');
		$this->db->join('tbl_language','tbl_language.language_id=tbl_users.language_id','left');
		$this->db->where('tbl_users.role_id',$rid);
		$this->db->where('tbl_users.is_active','1');
		$this->db->where('tbl_users.is_deleted','0');
		$this->db->group_by('tbl_users.user_id');
		$this->db->order_by('first_name','asc');
		$query=$this->db->get();
		return $query->result();
	}

	/**
	 * function to build query to edit customer details
	 * @author Vaibhav Mehta
	 * Created date: 01/12/2017 06:30 PM
	 */
	function editCustomerDetail($data,$id)
	{
		$this->db->where('user_id',$id);
		$query = $this->db->update("tbl_users",$data);
		return $this->db->affected_rows();
	}

	/**
	 * function to build query to add dilivery address of  the customer
	 * @author Manisha Kanazariya
	 * Created date: 06/2/2018 07:30 PM
	 */
	 function addDiliverAddress($data){

	 		$this->db->insert("tbl_customer_delivery_address",$data);
	 		
	 		return $this->db->insert_id();
	 }
	 /**
	 * function to build query to add dilivery address of  the customer
	 * @author Manisha Kanazariya
	 * Created date: 06/2/2018 07:30 PM
	 */
	 function updateDiliverAddress($data,$address_id){
	 		$this->db->where('address_id',$address_id);
	 		$this->db->update("tbl_customer_delivery_address",$data);
	 		return $this->db->affected_rows();
	 }
	 /**
	 * function to build query to get all the information about the customer
	 * @author Vaibhav Mehta
	 * Created date: 01/12/2017 07:30 PM
	 */
	function getCustomerDetailsWithOrders($id)
	{
		$this->db->select('ord.order_id,od.order_detail_id,od.product_id,
			o_dish_choice.fk_choice_id,ord.restaurant_id, ord.total_price,
			ord.order_placed_time, ord.expected_delivery_time, ord.order_type,
			ord.order_status, ord.delivered_by, ord.delivery_charges,
			od.quantity,od.amount,od.discount_type,od.discount_amount,
			o_dish_choice.order_dish_choice_id,
			dish.product_id,dish.product_en_name,dish.dish_image,dish.en_description,dish.price,
			dish_choice.price as choice_price,dish_cat.category_name,
			choice.choice_id,choice.choice_name,choice.choice_description,
			choice_cat.choice_category_name,
			res.restaurant_name,res.address as res_address,
			res.email as res_email,res.contact_no as res_contact_no,
			del.address1 as usr_address,del.customer_name,
			del.email as usr_email,
			del.contact_no as usr_contact_no,del.zipcode,
			drivers.first_name as d_first_name,
			drivers.last_name as d_last_name,drivers.contact_no as d_contact_no');
		$this->db->from('tbl_orders as ord'); 
		$this->db->join('tbl_order_details as od','od.order_id=ord.order_id','left');
		$this->db->join('tbl_order_dish_choice as o_dish_choice','od.order_detail_id=o_dish_choice.fk_order_detail_id','left');
		$this->db->join('tbl_dishes as dish','od.product_id=dish.product_id','left');

		$this->db->join('tbl_dish_choice as dish_choice','o_dish_choice.fk_choice_id=dish_choice.dish_choice_id','left');
		$this->db->join('tbl_dish_category as dish_cat','dish.category_id=dish_cat.category_id','left');
		$this->db->join('tbl_choice as choice','o_dish_choice.fk_choice_id=choice.choice_id','left');
		$this->db->join('tbl_choice_category as choice_cat','choice.fk_choice_category_id=choice_cat.choice_category_id','left');

		$condi = "(SELECT tbl_orders.order_id FROM tbl_orders JOIN tbl_users `users` ON tbl_orders.user_id = users.user_id WHERE `users`.`is_active` = '1' AND `users`.`is_deleted` = '0' AND `users`.user_id=$id ORDER BY tbl_orders.order_placed_time DESC LIMIT 10) as last10orders";
		$this->db->join($condi,'last10orders.order_id = ord.order_id','inner');

		$this->db->join('tbl_restaurants as res','res.restaurant_id=ord.restaurant_id','left');
		$this->db->join('tbl_customer_delivery_address as del','del.user_id=ord.user_id','left');
		$this->db->join('tbl_users as drivers','drivers.user_id=ord.delivered_by','left');		

		$this->db->where("ord.order_status !=","0");
		//$this->db->where("ord.order_id",$oid);
		//$this->db->where("ord.user_id",$id);
		$this->db->order_by("ord.order_id","DESC");
		$query = $this->db->get();
		//echo $this->db->last_query(); exit;
		return $query->result();
	}

	/**
	 * function to build query to get basic information about the customer
	 * @author Manisha Kanazariya
	 * Created date: 26/02/2018 07:30 PM
	 */
	function customerBasicDetails($id){

		$this->db->select("tbl_users.*,ul.lang_name,tbl_restaurants.restaurant_name");
		$this->db->where('tbl_users.user_id',$id);
		$this->db->join('tbl_language as ul','ul.language_id=tbl_users.language_id','left');
		$this->db->join('tbl_restaurants','tbl_restaurants.restaurant_id = tbl_users.customer_restaurant_id','LEFT');
		$this->db->from('tbl_users');

		$query =$this->db->get();
		return $query->result();
	}

	/**
	 * function to build query to get delivery address information about the customer
	 * @author Manisha Kanazariya
	 * Created date: 26/02/2018 07:30 PM
	 */
	function customerAddress($userId,$limit=''){

		$this->db->select("tbl_customer_delivery_address .*,tbl_locality.name as locality_name");
		$this->db->where('tbl_customer_delivery_address.user_id',$userId);
		$this->db->join("tbl_locality","tbl_locality.locality_id=tbl_customer_delivery_address.locality_id");

		if($limit !=''){
			$this->db->limit($limit);
		}
		$query=$this->db->get('tbl_customer_delivery_address');
		return $query->result();

	}
	/**
	 * function to build query to get order details about the customer
	 * @author Manisha Kanazariya
	 * Created date: 27/02/2018 12:30 PM
	 */
	function orderDetails($userId,$rid=null){
		$this->db->select("tbl_order_details.*,tbl_orders.total_price,tbl_orders.delivery_charges,tbl_orders.total_price,ods.product_en_name as dish_name,tbl_orders.sequence_no");
		$this->db->join('tbl_orders ','tbl_orders.order_id = tbl_order_details.order_id ','left');
		$this->db->join('tbl_dishes as ods','ods.product_id=tbl_order_details.product_id','left');
		$this->db->order_by('tbl_orders.order_id','ASC');
		$this->db->where('tbl_orders.user_id',$userId);
		if($rid)
		{
			$this->db->where('tbl_orders.restaurant_id',$rid);
		}
		$this->db->from('tbl_order_details');
		$query=$this->db->get();
		
		return $query->result();

	}
	/**
	 * function to build query to get details about  dish order of the customer
	 * @author Manisha Kanazariya
	 * Created date: 27/02/2018 12:30 PM
	 */
	function orderDishDetails($userId,$order_id){
		$this->db->select("tbl_orders.order_id,od.quantity,od.amount,od.order_detail_id,ods.product_en_name as dish_name,odc.choice_id,odc.choice_name as choice_name,cat.choice_category_name as choice_category_name,od.product_id,rd.dish_price,,rd.choice_id as ches_id,rd.choice_price");

		$this->db->join('tbl_order_details as od','od.order_id =tbl_orders.order_id','left');
		$this->db->join('tbl_order_dish_choice as odsc','odsc.fk_order_detail_id = od.order_detail_id','left');
		$this->db->join('tbl_dishes as ods','ods.product_id=od.product_id','left');
		$this->db->join('tbl_choice as odc','odc.choice_id =odsc.fk_choice_id','left');
		$this->db->join('tbl_choice_category as cat','cat.choice_category_id =odc.fk_choice_category_id','left');
		$this->db->join('tbl_restaurant_dishes as rd','rd.fk_restaurant_id =tbl_orders.restaurant_id','left');
		$this->db->where('rd.fk_dish_id = od.product_id');
		$this->db->where('tbl_orders.user_id',$userId);
		if($order_id !=""){
			$this->db->where('od.order_id',$order_id);
		}
		$this->db->order_by('tbl_orders.order_id');
		$this->db->from('tbl_orders');
		$query=$this->db->get();

		return $query->result();
	}

	function deleteCustomerAddress($address_id){
		$this->db->where('address_id',$address_id);
		$this->db->delete('tbl_customer_delivery_address');
		return $this->db->affected_rows();
	}

	/**
	 * function to build query to check that contact number is exist for customer
	 * @author Manisha Kanazariya
	 * Created date: 15/05/2018 12:30 PM
	 */
	function isCustContactExist($contact_no,$user_id=""){
		$this->db->where('role_id',5);
		$this->db->where('is_active','1');
		$this->db->where('contact_no',$contact_no);
		if($user_id !=""){
			$this->db->where('user_id !=',$user_id);
		}
		return $this->db->get('tbl_users')->result();
	}
		/**
	 * function to build query to get choice category details 
	 * @author Manisha Kanazariya
	 * Created date: 18/05/2018 12:30 PM
	 */
	function getChoiceCategory($choices){
		$this->db->select("tbl_choice_category.choice_category_name,tbl_choice.choice_id");
		$this->db->where("tbl_choice.choice_id IN ($choices)");
		$this->db->join('tbl_choice_category','tbl_choice_category.choice_category_id=tbl_choice.fk_choice_category_id','left');
		$this->db->from("tbl_choice");
		$query =$this->db->get();
		return $query->result();
	}
}
