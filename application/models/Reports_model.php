<?php
	/**
	 * Model Name: Reports_model
	 * Descripation: Use to manage the dishes Report related database interaction
	 * @author Manisha Kanazariya
	 * Created date: 05-05-2018 1:00 PM
	 */
class Reports_model extends CI_Model
{
	/**
	 * function to build query to get the report data Count
	 * @author Manisha Kanazariya
	 * Created date: 29/05/2018 5:50 PM
	 */
	
	function getOrderDataCount($type="",$limit="",$offset="",$startDate="1970:00:00 00:00:00",$endDate="",$restaurant="",$payment =""){
		if($limit !="" && ($type <=3 ||$type ==7 || $type == 9))
		{
			$this->db->limit($limit,$offset);
		}
		if($type == 1 || $type == 3 || $type ==7 || $type == 8){
			
			$this->db->join('tbl_customer_delivery_address as cst','cst.address_id =od.selected_delivery_address','left');
			$this->db->join('tbl_locality as lc','lc.locality_id =cst.locality_id','left');
			if($type == 3){
				$this->db->select("od.order_id as OrderID,od.delivered_time as OrderTime,cst.customer_name as CustomerName,lc.name as Area");
			}else if($type == 1){
				$this->db->select("lc.name as Name,count(od.order_id) as TotalOrders,SUM(od.total_price) as TotalSales");
				$this->db->group_by('lc.locality_id');
			}else if($type == 7){
				$this->db->join('tbl_restaurants as rs','rs.restaurant_id =od.restaurant_id','left');
				$this->db->select("od.order_id as Sr,od.order_id as OrderID,od.delivered_time as OrderTime,cst.customer_name as CustName,cst.contact_no as Mobile,od.total_price as Amount,od.order_type as Payment ,lc.name as AreaName,rs.restaurant_name as Restaurant,od.order_status as Status");
				if($payment !=""){
					$this->db->where('od.order_type',$payment);
				}
				$this->db->group_by('od.order_id');
			}else if($type == 8){
				$this->db->select('od.order_id,od.total_price,od.delivered_time as OrderTime,od.order_type as Payment');
				if($payment !=""){
					$this->db->where('od.order_type',$payment);
				}
			}
			$this->db->order_by("od.delivered_time","DESC");
			
		}
		else if($type == 2){
			$this->db->join('tbl_order_details as os','os.order_id =od.order_id');
			$this->db->join('tbl_dishes','tbl_dishes.product_id=os.product_id');
			$this->db->select('tbl_dishes.product_en_name as ItemName, count(os.product_id) as TotalSold');
			$this->db->group_by('os.product_id');
		}
		else if($type == 9){
			$this->db->join('tbl_order_details as os','os.order_id =od.order_id');
			$this->db->join('tbl_dishes','tbl_dishes.product_id=os.product_id');
			$this->db->join('tbl_dish_category','tbl_dish_category.category_id = tbl_dishes.category_id',"INNER");
			$this->db->select('tbl_dish_category.category_name as category_name, count(os.product_id) as TotalSold');
			$this->db->group_by('tbl_dish_category.category_id');
			$this->db->order_by('TotalSold',"DESC");
		}
		else{
			
			$this->db->select('od.order_id,od.total_price,od.delivered_time as OrderTime');
			$this->db->order_by("OrderTime","DESC");
		}

		if($restaurant !=""){
			$this->db->where('od.restaurant_id',$restaurant);
		}
		if($startDate !=""){
			$this->db->where("od.delivered_time  >='$startDate'");
		}
		if($endDate !=""){
			$this->db->where("od.delivered_time  <= '$endDate'");
		}
		
		if($type == 3){
			$this->db->where_in('od.order_status',array(13,14));
		}
		else{
			$this->db->where_in('od.order_status',array(7,8));
		}		

		$this->db->from('tbl_orders as od');

		return $this->db->get()->num_rows();
	}
	
	/**
	 * function to build query to get the report data
	 * @author Manisha Kanazariya
	 * Created date: 22/05/2018 5:50 PM
	 */
	

	function getOrderData($type="",$limit="",$offset="",$startDate="1970:00:00 00:00:00",$endDate="",$restaurant="",$payment =""){
		if($limit !="" && ($type <=3 ||$type ==7 || $type == 9))
		{
			$this->db->limit($limit,$offset);
		}
		if($type == 1 || $type == 3 || $type ==7 || $type == 8){
			$this->db->join('tbl_customer_delivery_address as cst','cst.address_id =od.selected_delivery_address','left');
			$this->db->join('tbl_locality as lc','lc.locality_id =cst.locality_id','left');
			if($type == 3){
				$this->db->select("od.order_id as OrderID,od.order_placed_time as OrderTime,cst.customer_name as CustomerName,lc.name as Area,od.reason as Remark/Reason ");
				$this->db->order_by("OrderTime","DESC");
			}else if($type == 1){
				$this->db->select("lc.name as Name,count(od.order_id) as TotalOrders,SUM(od.total_price) as TotalSales");
				$this->db->group_by('lc.locality_id');
				$this->db->order_by("TotalSales","DESC");
			}else if($type == 7){
				$this->db->join('tbl_restaurants as rs','rs.restaurant_id =od.restaurant_id','left');
				$this->db->select("od.order_id as Sr,od.order_id as OrderID,od.delivered_time as OrderTime,cst.customer_name as CustName,cst.contact_no as Mobile,od.total_price as Amount,od.order_type as Payment ,lc.name as AreaName,rs.restaurant_name as Restaurant,od.order_status as Status");
				if($payment !=""){
					$this->db->where('od.order_type',$payment);
				}
				

				$this->db->order_by("od.order_id","DESC");
				$this->db->group_by('od.order_id');

				if($startDate !="")
				$this->db->where("od.delivered_time  >='$startDate'");

				if($endDate !="")
				$this->db->where("od.delivered_time  <='$endDate'");

			}else if($type == 8){
				$this->db->select('od.order_id,od.total_price,od.delivered_time as OrderTime,od.order_type as Payment');
				if($payment !=""){
					$this->db->where('od.order_type',$payment);
				}
				$this->db->order_by("OrderTime","DESC");
			}
			
			
		}
		else if($type == 9){
			$this->db->join('tbl_order_details as os','os.order_id =od.order_id');
			$this->db->join('tbl_dishes','tbl_dishes.product_id=os.product_id');
			$this->db->join('tbl_dish_category','tbl_dish_category.category_id = tbl_dishes.category_id',"INNER");
			$this->db->select('tbl_dish_category.category_name as Category Name, count(os.product_id) as TotalSold');
			$this->db->group_by('tbl_dish_category.category_id');
			$this->db->order_by('TotalSold',"DESC");
		}
		else if($type == 2){
			$this->db->join('tbl_order_details as os','os.order_id =od.order_id');
			$this->db->join('tbl_dishes','tbl_dishes.product_id=os.product_id');
			$this->db->select('tbl_dishes.product_en_name as ItemName, count(os.product_id) as TotalSold');
			$this->db->group_by('os.product_id');
			$this->db->order_by('TotalSold',"DESC");
		}
	
		else{
			
			$this->db->select('od.order_id,od.total_price,od.delivered_time as OrderTime');
			$this->db->order_by("OrderTime","DESC");
		}

		if($restaurant !=""){
			$this->db->where('od.restaurant_id',$restaurant);
		}
		if($startDate !="" && $type == 3){
			($type == 3)?$this->db->where("od.order_placed_time  >='$startDate'"):$this->db->where("od.delivered_time  >='$startDate'");
		}
		if($endDate !=""){
			($type == 3)?$this->db->where("od.order_placed_time  <='$endDate'"):$this->db->where("od.delivered_time  <='$endDate'");
		}

		if($startDate !="" && ($type == 8 || $type == 5 || $type == 1 || $type == 2 || $type == 4 || $type == 6 || $type == 9)){
			$this->db->where("od.delivered_time  >='$startDate'");
		}
		if($endDate !="" && $type == 8 || $type == 5 || $type == 1 || $type == 2 || $type == 4 || $type == 6 || $type == 9){
			$this->db->where("od.delivered_time  <='$endDate'");
		}
		
		if($type == 3){
			$this->db->where_in('od.order_status',array(13,14));
		}else{
			$this->db->where_in('od.order_status',array(7,8));
		}	
		$this->db->from('tbl_orders as od');
		
		return $this->db->get()->result();
	}	
	/**
	 * [getRestaurantDrivers description]
	 * Description:
	 * @author: Manisha Kanazariya
	 * @CreatedDate:2019-07-22T18:34:47+0530
	 */
	function getRestaurantDrivers($resId = ''){

		$this->db->select('first_name,last_name,user_id');
		$this->db->where("role_id",4);
		$this->db->where('is_deleted',0);

		if($resId != ''){
			$this->db->where('fk_restaurant_id', $resId);
		}

		$query =$this->db->get("tbl_users");
		return $query->result();
	}

	function countDriverOrders($driverId=null,$resId=null,$startDate=null,$endDate=null,$paymentType=null)
	{
		if($driverId){
			$this->db->where("ord.delivered_by",$driverId);
		}
		if($resId){
			$this->db->where("ord.restaurant_id",$resId);
		}
		if($startDate){
			$this->db->where("date(ord.delivered_time)>=",$startDate);
		}
		if($endDate){
			$this->db->where("date(ord.delivered_time) <=",$endDate);
		}
		if($paymentType){
			$this->db->where("ord.order_type",$paymentType);
		}
		$this->db->select("count(ord.order_id) as total ,sum(ord.total_price) as amount","left");
		$this->db->join("tbl_restaurants as rs","rs.restaurant_id =ord.restaurant_id","left");
		$this->db->where_in("ord.order_status",array(7,8,9));
		$this->db->from("tbl_orders as ord");
		$query =$this->db->get();
		return $query->row();
	}

	function getDriverOrders($limit=null,$offset=null,$driverId=null,$resId=null,$startDate=null,$endDate=null,$paymentType=null)
	{
		if($driverId){
			$this->db->where("ord.delivered_by",$driverId);
		}
		if($resId){
			$this->db->where("ord.restaurant_id",$resId);
		}
		if($startDate){
			$this->db->where("date(ord.delivered_time)>=",$startDate);
		}
		if($endDate){
			$this->db->where("date(ord.delivered_time) <=",$endDate);
		}
		if($paymentType){
			$this->db->where("ord.order_type",$paymentType);
		}
		$this->db->select("first_name,last_name,rs.restaurant_name,ord.order_id,ord.total_price,ord.delivered_by,ord.order_status,ord.order_type,ord.delivered_time");
		$this->db->join("tbl_users as us","us.user_id =ord.delivered_by","left");
		$this->db->join("tbl_restaurants as rs","rs.restaurant_id =ord.restaurant_id","left");
		$this->db->where_in("ord.order_status",array(7,8,9));
		if($limit){
			$this->db->limit($limit,$offset);
		}
		$this->db->from("tbl_orders as ord");
		$query =$this->db->get();
		return $query->result();
	}
}	