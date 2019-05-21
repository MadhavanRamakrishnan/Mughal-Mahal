<?php

	/**
	 * Controller Name 	: Driver
	 * Descripation 	: Use to manage all the activity related to driver
	 * @author 			: Rashmi Nayani
	 * Created date 	: 11-10-2017 05:40PM
	 */

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Orders extends MY_Controller
	{
	/**
	 * deafult function call when controller class is load
	 * @author Rashmi Nayani
	 * Created date: 11-10-2017 5:40 PM
	 */
	function __construct(){
		parent::__construct();
		$this->isLoginUser();
		//loading login model
		$this->checkLogin();
		$this->load->model(array('Login_model','Order_model','Webservice_customer_model','Home_model'));
		$this->menu 	= $this->getMenu();
		$this->submenu 	= $this->getSubMenu();
		$this->load->library('form_validation');
		$this->load->library('pagination');
		$this->load->library('upload');
		$this->userdata		= $this->session->userdata('current_user');
		$this->payment_type = $this->config->item('payment_type');
		$this->OrderStatus  = $this->config->item('OrderStatus');
		$this->labelColor   = $this->config->item('labelColor'); 


	}

	/**
	 * deafult function call for listing orders
	 * @author MAnisha Kanazariya
	 * Created date: 2-07-2018 5:50 PM
	 */
	function index(){

		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();

		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']   	 = $submenuArray;

		$resId = $this->getRestaurantForRoleBaseAccess();

		$totalRow 					= $this->Order_model->getAllOrderCount($resId);
		$config["base_url"] 		= site_url('Orders/newOrders');
		$config["total_rows"] 		= $totalRow;
		$config["per_page"] 		= 50;
		$config['use_page_numbers'] = TRUE;
		$config['num_links'] 		= 50;
		$config['cur_tag_open']		= '&nbsp;<a class="active ">';
		$config['cur_tag_close']	= '</a>';
		$config['next_link'] 		= 'Next';
		$config['prev_link'] 		= 'Previous';
		
		$this->pagination->initialize($config);

		if($this->uri->segment(3)){
			$page = ($this->uri->segment(3)) ;
		}
		else{
			$page = 1;
		}

		$offset=($page - 1) * $config["per_page"];

		$allOrders 			= $this->Order_model->getNewOrderDetails($resId,$config["per_page"],$offset);
		foreach ($allOrders as $key => $value) {
			if($value->status !='1')
			{
				$allOrders[$key]->name    	 =($value->name != null)?$value->name:'';
				$allOrders[$key]->area       =($value->area != null)?$value->area:'';
				$allOrders[$key]->contact_no =($value->contact_no != null)?$value->contact_no:'';
				$allOrders[$key]->oId        =$value->order_id;
				$allOrders[$key]->order_id   =$this->getOrerId($value->order_id);
				$allOrders[$key]->paymnet 	 =$this->payment_type[$value->paymnet];
				$allOrders[$key]->amount  	 =number_format($value->amount,3);
				$allOrders[$key]->time    	 =date('m/d/Y h:i:s a',strtotime($value->order_time));
			}else{
				unset($allOrders[$key]);
			}
			
		}
		$data['resId']	        = $resId;
		$data['restaurants']    = $this->Restaurant_model->getAllRestaurantDetails($resId);
		$data['Orders']	        = $allOrders;
		$data['totalRow']	    = $totalRow;
		$data['offset'] 	    = $offset + 1;	
		$str_links 			    = $this->pagination->create_links();
		$data["links"] 		    = explode('&nbsp;',$str_links );
		$data['totalNew']       = $this->Order_model->getOrderCount($resId);
		$data['totalDisputed']  = $this->Order_model->getOrderCount($resId,8);
		$this->load->view('Elements/header',$data);
		$this->load->view('Orders/new_orders');
		$this->load->view('Elements/footer');
	}
	/**
	 * deafult function call for listing orders
	 * @author Manisha Kanazariya
	 * Created date:2-07-2018 5:50 PM 
	 */
	function newOrders(){
		if(is_numeric($this->input->post('order_id'))){
			if($this->input->post('order_id') == 0){
				$orderId    =$this->input->post('order_id');
			}else{
				$orderId    =(int)$this->input->post('order_id');
			}
			
		}else{
			$orderId    =$this->input->post('order_id');
		}
		$resId      = $this->getRestaurantForRoleBaseAccess();

		$phone      =($this->input->post('phone'))?$this->input->post('phone'):"";
		$restaurant =($this->input->post('restaurant'))?$this->input->post('restaurant'):$resId;
		$date       =($this->input->post('date'))?date('Y-m-d H:i:s',strtotime($this->input->post('date'))):"";
		$date1      =($this->input->post('date1'))?date('Y-m-d H:i:s',strtotime(" +1day ".$this->input->post('date1'))):"";
	
		
		$status     =($this->input->post('status'))?$this->input->post('status'):null;

		$totalRow 					= $this->Order_model->getAllOrderCount($restaurant,$orderId,$phone,$date,$date1,$status);
		
		$config["base_url"] 		= site_url('Orders/newOrders');
		$config["total_rows"] 		= $totalRow;
		$config["per_page"] 		= 50;
		$config['use_page_numbers'] = TRUE;
		$config['num_links'] 		= 50;
		$config['cur_tag_open']		='&nbsp;<a class="active ">';
		$config['cur_tag_close']	= '</a>';
		$config['next_link'] 		= 'Next';
		$config['prev_link'] 		= 'Previous';
		
		$this->pagination->initialize($config);

		if($this->uri->segment(3)){
			$page = ($this->uri->segment(3)) ;
		}
		else{
			$page = 1;
		}

		$offset=($page - 1) * $config["per_page"];

		$allOrders 			= $this->Order_model->getNewOrderDetails($restaurant,$config["per_page"],$offset,$orderId,$phone,$date,$date1,$status);
		
		foreach ($allOrders as $key => $value) {
			$allOrders[$key]->name       =($value->name != null)?$value->name:'';
			$allOrders[$key]->area       =($value->area != null)?$value->area:'';
			$allOrders[$key]->contact_no =($value->contact_no != null)?"(+965) ".$value->contact_no:'';
			$allOrders[$key]->oId        =$value->order_id;
			$allOrders[$key]->order_id   =$this->getOrerId($value->order_id);
			$allOrders[$key]->amount     =number_format($value->amount,3);
			$allOrders[$key]->paymnet    =$this->payment_type[$value->paymnet];
			$allOrders[$key]->status_vla =$this->OrderStatus[$value->status];
			$nextStatus                  =($value->status == 5)?$value->status+2:$value->status+1;
			$allOrders[$key]->nxtstatus  =($nextStatus <=14)?$this->OrderStatus[$nextStatus]:"";
			$allOrders[$key]->nextlbl    =($nextStatus <=14)?$this->labelColor[$nextStatus]:"";
			$allOrders[$key]->lbl        =$this->labelColor[$value->status];
			$allOrders[$key]->status_id  =$value->status;
			$allOrders[$key]->status     =$this->OrderStatus[$value->status];
			$allOrders[$key]->time       =date('m/d/Y h:i:s a',strtotime($value->order_time));
		}
		$data['Orders']	    = $allOrders;
		$data['totalRow']	= $totalRow;
		$data['status']	    = ($status !="")?$this->OrderStatus[$status]:"All Orders";
		$str_links 			= $this->pagination->create_links();
		$data["links"] 		= explode('&nbsp;',$str_links);
		$data['offset'] 	= $offset + 1;	
		echo json_encode($data);exit;
	}

	function getNewOrderCounts(){
		$resId                      = $this->getRestaurantForRoleBaseAccess();
		$response['total']          = $this->Order_model->getOrderCount($resId);
		$response['totalNew']       = $this->Order_model->getOrderCount($resId,1);
		$response['totalDisputed']  = $this->Order_model->getOrderCount($resId,8);
		echo json_encode($response); exit;
	}



	/**
	 * function to get city for selectimg state
	 * @author Rashmi Nayani
	 * Created date: 06-11-2017 4:50 PM
	 */
	public function getCity(){
		$data['user_data']  =$this->session->userdata('current_user');
		$stateId 	= $this->input->post('state_id');
		$result 	= $this->Order_model->getCity($stateId);

		if(sizeof($result)>0){
			$response = array("success"=>"1","data"=>$result);
		}
		else{
			$response = array("success"=>"0","message"=>"No City deatils found!");
		}
		echo json_encode($response);
		exit;
	}
	

	/**
	 * function to get dish for selectimg dish categpry
	 * @author Rashmi Nayani
	 * Created date: 7-11-2017 1:20 PM
	 */
	public function getDish(){

		$data['user_data']  = $this->session->userdata('current_user');
		$categoryId 		= $this->input->post('category_id');
		$dishId 			= $this->input->post('dish_id');
		$result 			= $this->Order_model->getDish($categoryId,$dishId);

		if(sizeof($result)>0){
			$response = array("success"=>"1","data"=>$result);
		}
		else{
			$response = array("success"=>"0","message"=>"No dish deatils found!");
		}
		echo json_encode($response);
		exit;
	}

	/**
	 * function to get dish choices
	 * @author Rashmi Nayani
	 * Created date: 7-11-2017 7:15 PM
	 */
	public function getDishChoice(){

		$data['user_data']  = $this->session->userdata('current_user');
		$dishId 			= $this->input->post('dish_id');

		$result 			= $this->Order_model->getDishChoice($dishId);
		if(sizeof($result)>0){
			$response = array("success"=>"1","data"=>$result);
		}
		else{
			$response = array("success"=>"0","message"=>"No dish deatils found!");
		}
		echo json_encode($response);
		exit;
	}

	/**
	 * function call to get order details
	 * @author Rashmi Nayani
	 * Created date: 13-11-2017 5:40 PM
	 */
	function getOrderDetails($id){

		$data['userdata']	= $this->session->userdata('current_user');
		$data['panelColor'] = $this->config->item('panelColor');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();

		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}

		$data['submenu']    = $submenuArray;
		$orderDetail 	    = $this->Order_model->getOrderDetails($id);
		$getDelAdd          = $this->Home_model->getDeliveryAdd($orderDetail[0]->selected_delivery_address);
		$getResData         = $this->Home_model->getRestaurant($orderDetail[0]->restaurant_id);
		//echo "<pre>";print_r($getDelAdd);exit;
		$orderData['order_id'] 			   = $id;
		$orderData['order_status'] 		   = $orderDetail[0]->order_status;
		$orderData['order_placed_time']    = $orderDetail[0]->order_placed_time;
		$orderData['delivered_time'] 	   = $orderDetail[0]->delivered_time;
		$orderData['total_price'] 		   = $orderDetail[0]->total_price;
		$orderData['order_type'] 		   = $orderDetail[0]->order_type;
		$orderData['delivery_charges'] 	   = $orderDetail[0]->delivery_charges;
		$orderData['reason'] 			   = $orderDetail[0]->reason;

		//restaurant detail 
		$orderData['restaurant_name']      = $getResData[0]->restaurant_name;

		//order delivery address
		$orderData['delivery_address'] 	   = $getDelAdd[0]->address1;
		$orderData['customer_name'] 	   = $getDelAdd[0]->customer_name;
		$orderData['customer_contact_no']  = $getDelAdd[0]->contact_no;
		$orderData['customer_email'] 	   = $getDelAdd[0]->email;
		$orderData['customer_latitude']    = $getDelAdd[0]->customer_latitude;
		$orderData['customer_longitude']   = $getDelAdd[0]->customer_longitude;
		
		$getDriverData= $this->Home_model->getDriver($orderDetail[0]->delivered_by);
		if($orderData['order_status']>3 && count($getDriverData)>0)
        {
        	 //order Driver details
	       	$orderData['d_first_name'] 	   = $getDriverData[0]->first_name;
			$orderData['d_last_name'] 	   = $getDriverData[0]->last_name;
			$orderData['d_contact_no'] 	   = "(+965)".$getDriverData[0]->contact_no;
	        
		}

		$oDetailId =0;
		if(count($orderDetail)>0)
		{
			foreach ($orderDetail as $key => $value) {

				$ordId     =$value->order_id;
				$choiceID  =$value->choice_id;

				$productDetails = $this->Webservice_customer_model->getDishDetail($value->product_id,$value->restaurant_id);
				if(count($productDetails)>0)
				{
					$dishChoices   =explode(',',$productDetails[0]->choice_id);
					$choicesPrices =explode(',',$productDetails[0]->choice_price);
					
					$key          = array_search($choiceID,$dishChoices);
					$choicePrice  =$choicesPrices[$key];	
				}
				else
				{
					$choicePrice="0";
				}

				if($oDetailId != $value->order_detail_id)
	            {
	            	$oDetailId   =$value->order_detail_id;
					$orderData['dishes'][$oDetailId]['product_id']     = $value->product_id;
					$orderData['dishes'][$oDetailId]['description']    = $value->description;
					$orderData['dishes'][$oDetailId]['price']          = $productDetails[0]->dish_price;
					$orderData['dishes'][$oDetailId]['quantity']       = $value->quantity;
					$orderData['dishes'][$oDetailId]['amount']         = $value->amount;
					$orderData['dishes'][$oDetailId]['discount_type']  = $value->discount_type;
					$orderData['dishes'][$oDetailId]['product_en_name']= $value->product_en_name;
					$orderData['dishes'][$oDetailId]['en_description'] = $value->en_description;

					if ($value->choice_id != "") 
					{
						$orderData['dishes'][$oDetailId]['choice'][$choiceID]['choice_id'] = $choiceID;
						$orderData['dishes'][$oDetailId]['choice'][$choiceID]['choice_price'] =$choicePrice;;
						$orderData['dishes'][$oDetailId]['choice'][$choiceID]['choice_name'] = $value->choice_name;
						$orderData['dishes'][$oDetailId]['choice'][$choiceID]['choice_description'] = $value->choice_description;
						$orderData['dishes'][$oDetailId]['choice'][$choiceID]['choice_category_name'] = $value->choice_category_name;

					}
				}
				else
				{
					if ($value->choice_id != "") 
					{
						$orderData['dishes'][$oDetailId]['choice'][$choiceID]['choice_id'] = $choiceID;
						$orderData['dishes'][$oDetailId]['choice'][$choiceID]['choice_price'] = $choicePrice;
						$orderData['dishes'][$oDetailId]['choice'][$choiceID]['choice_name'] = $value->choice_name;
						$orderData['dishes'][$oDetailId]['choice'][$choiceID]['choice_description'] = $value->choice_description;
						$orderData['dishes'][$oDetailId]['choice'][$choiceID]['choice_category_name'] = $value->choice_category_name;
					}
				}
			}
			
			$data['orderDetail'] = $orderData;
		}
		$this->load->view('Elements/header',$data);
		$this->load->view('Orders/order_detail',$data);
		$this->load->view('Elements/footer');
	}

	/**
	 * deafult function call for listing orders
	 * @author Vaibhav Mehta
	 * Created date: 11-10-2017 5:50 PM
	 */
	function changeOrderStatus()
	{
		//print_r($this->userdata[0]->user_id);exit;
		$uid 			= $this->userdata[0]->user_id;
		$oid 			= $this->input->post('oid');
		$os 			= $this->input->post('os');
		$OrderStatus    = $this->config->item('OrderStatus');
		$panelColor     = $this->config->item('panelColor');
		$labelColor     = $this->config->item('labelColor');
		if($oid && $os)
		{
			$updateOrder = array();
			$updateOrder['order_status'] 	= $os;
			$updateOrder['updated_by'] 		= $uid;
			if($os == "7"){
				$updateOrder['delivered_time'] 	 = date("Y-m-d H:i:s");
			}

			$updateOrder['order_confirmed_time'] = date("Y-m-d H:i:s");
			$update = $this->Order_model->updateOrder($oid,$updateOrder);
			$html = '';
			$listOrderStatus = '';
			if($update)
			{
				
				$html .= '<span class="active label '.$labelColor[$os].'">'.$OrderStatus[$os].'</span>';
				if($os<7)
				{
					if($os==3)
					{
						$html .= '&nbsp;&nbsp;<i class="fa fa-arrow-right"></i>';
						$html .= '<span class="label '.$labelColor[$os+1].' changeOrderStatusAndDriver" oid="'.$oid.'" os="'.($os+1).'" data-toggle="modal" data-target="#modal-form" data-backdrop="static" data-keyboard="false" style="cursor: pointer;" title="Change Order Status">'.$OrderStatus[$os+1].'</span>';
					}
					else
					{
						$html .= '&nbsp;&nbsp;<i class="fa fa-arrow-right"></i>';
						$html .= '<span class="label '.$labelColor[$os+1].' changeOrder" oid="'.$oid.'" os="'.($os+1).'" data-toggle="modal" data-target="#cngStatusmodal" data-backdrop="static" data-keyboard="false" style="cursor: pointer;" title="Change Order Status">'.$OrderStatus[$os+1].'</span>';
					}
				}
			
				if($os==7)
				{
					$this->load->model('Webservice_driver_model');
					$orderDetail = $this->Webservice_driver_model->getOrderDetailsForDeliveryEmail($oid);

					if(is_array($orderDetail) && count($orderDetail)>0){

						foreach ($orderDetail as $key => $value) {

							$orderDetails[$value->order_id]['order_id'] 				= $value->order_id;
							$orderDetails[$value->order_id]['order_status'] 			= $value->order_status;
							$orderDetails[$value->order_id]['order_placed_time'] 		= $value->order_placed_time;
							$orderDetails[$value->order_id]['expected_delivery_time'] 	= $value->expected_delivery_time;
							$orderDetails[$value->order_id]['total_price'] 				= $value->total_price;
							$orderDetails[$value->order_id]['payment_type'] 			= $value->payment_type;
							$orderDetails[$value->order_id]['restaurant_name'] 			= $value->restaurant_name;
							$orderDetails[$value->order_id]['restaurant_address'] 		= $value->res_address;
							$orderDetails[$value->order_id]['restaurant_email'] 		= $value->res_email;
							$orderDetails[$value->order_id]['restaurant_contact_no'] 	= $value->res_contact_no;
							$orderDetails[$value->order_id]['delivery_address'] 		= $value->usr_address;
							$orderDetails[$value->order_id]['customer_name'] 			= $value->customer_name;
							$orderDetails[$value->order_id]['customer_contact_no'] 		= $value->usr_contact_no;
							$orderDetails[$value->order_id]['customer_email'] 			= $value->usr_email;
							$orderDetails[$value->order_id]['customer_latitude'] 		= $value->customer_latitude;
							$orderDetails[$value->order_id]['customer_longitude'] 		= $value->customer_longitude;
							$orderDetails[$value->order_id]['zipcode'] 					= $value->zipcode;
							$orderDetails[$value->order_id]['user_first_name'] 					= $value->user_first_name;
							$orderDetails[$value->order_id]['usre_last_name'] 					= $value->usre_last_name;
							$orderDetails[$value->order_id]['user_email'] 					= $value->user_email;
							$orderDetails[$value->order_id]['delivery_charges']			= $value->delivery_charges;

							$orderDetails[$value->order_id]['dishes'][$value->product_id]['product_id']  = $value->product_id;
							$orderDetails[$value->order_id]['dishes'][$value->product_id]['quantity']  = $value->quantity;
							$orderDetails[$value->order_id]['dishes'][$value->product_id]['amount']  = $value->amount;
							$orderDetails[$value->order_id]['dishes'][$value->product_id]['discount_type']  = $value->discount_type;
							$orderDetails[$value->order_id]['dishes'][$value->product_id]['discount_amount']  = $value->discount_amount;
							$orderDetails[$value->order_id]['dishes'][$value->product_id]['name']  = $value->name;
							$orderDetails[$value->order_id]['dishes'][$value->product_id]['price']  = $value->price;
						}
					}

					$dishArray = array_values($orderDetails);
					foreach ($dishArray as $dishkey => $dishvalue)
					{
						if($dishvalue['dishes'] && is_array($dishvalue['dishes']))
						{
							$dishArray[$dishkey]['dishes'] = array_values($dishvalue['dishes']);
						}
					}
					if(is_array($dishArray) && count($dishArray)>0)
					{
						$dishArray1 = $dishArray;

						$dishArray[0]['email_template'] = 'delivery_email';
						$dishArray[0]['subject'] = 'Your order from '.$dishArray[0]['restaurant_name'];

						$dishArray1[0]['email_template'] = 'delivery_email';
						$dishArray1[0]['subject'] = 'Your order from '.$dishArray[0]['restaurant_name'];

						if(strcmp($dishArray[0]['customer_email'],$dishArray[0]['user_email'])==0)
						{
							if($dishArray[0]['customer_email'] || $dishArray[0]['user_email'])
							{
								$dishArray[0]['order_id'] = $oid;
								$dishArray[0]['order_placed_time'] = $dishArray[0]['order_placed_time'];
								$dishArray[0]['total_price'] = $dishArray[0]['total_price'];
								$dishArray[0]['delivery_charges'] = $dishArray[0]['delivery_charges'];
								$dishArray[0]['delivery_address'] = $dishArray[0]['delivery_address'];

								$dishArray[0]['username'] = $dishArray[0]['customer_name'];
								$dishArray[0]['to_email'] = $dishArray[0]['customer_email'];
												//$dishArray[0]['to_email'] = 'vaibhav@itoneclick.com';
								$mails = $this->sendMail($dishArray[0]);
							}
						}
						else
						{
							if($dishArray[0]['customer_email'])
							{
								$dishArray[0]['order_id'] = $oid;
								$dishArray[0]['order_placed_time'] = $dishArray[0]['order_placed_time'];
								$dishArray[0]['total_price'] = $dishArray[0]['total_price'];
								$dishArray[0]['delivery_charges'] = $dishArray[0]['delivery_charges'];
								$dishArray[0]['delivery_address'] = $dishArray[0]['delivery_address'];

								$dishArray[0]['username'] = $dishArray[0]['user_first_name'].' '.$dishArray[0]['user_last_name'];
								$dishArray[0]['to_email'] = $dishArray[0]['customer_email'];
												//$dishArray[0]['to_email'] = 'vaibhav@itoneclick.com';
								$mails = $this->sendMail($dishArray[0]);
							}

							if($dishArray[0]['user_email'])
							{
								$dishArray1[0]['order_id'] = $oid;
								$dishArray1[0]['order_placed_time'] = $dishArray[0]['order_placed_time'];
								$dishArray[0]['total_price'] = $dishArray[0]['total_price'];
								$dishArray11[0]['delivery_charges'] = $dishArray[0]['delivery_charges'];
								$dishArray1[0]['delivery_address'] = $dishArray[0]['delivery_address'];

								$dishArray1[0]['username'] = $dishArray[0]['customer_name'];
								$dishArray1[0]['to_email'] = $dishArray[0]['user_email'];
												//$dishArray1[0]['to_email'] = 'vaibhav@itoneclick.com';
								$mails = $this->sendMail($dishArray1[0]);
							}
						}
					}
				}

				$response = array("success"=>"1","message"=>"Order status changed successfully.","data"=>$html);

				// $this->sendPushNotificationUsingFirebase($os, $oid);
			}
			else
			{
				$response = array("success"=>"0","message"=>"Order status can't change, please try after sometime.","data"=>$html);
			}
		}
		else
		{
			$response = array("success"=>"0","message"=>"Please select the order for change order status.");
		}
		echo json_encode($response);
	}

	/**
	 * function call to delete all order details
	 * @author Vaibhav Mehta
	 * Created date: 16-11-2017 12:40 PM
	 */
	function deleteOrderDetails()
	{
		$uid 	= $this->userdata[0]->user_id;
		$oid 	= $this->input->post('order_id');
		$reason	= $this->input->post('reason');
		
		if($oid)
		{
			$updateOrder = array();
			$updateOrder['order_status'] 	= '14';
			$updateOrder['reason'] 	        = $reason;
			$updateOrder['updated_by'] 		= $uid;
			$updateOrder['updated_date'] 	= date("Y-m-d H:i:s");
			
			$update = $this->Order_model->updateOrder($oid,$updateOrder);

			if($update)
			{
				$response = array("success"=>"1","message"=>"Order has been discarded successfully.");
			}
			else
			{
				$response = array("success"=>"0","message"=>"Order can't be discarded, please try after sometime.");
			}
		}
		else
		{
			$response = array("success"=>"0","message"=>"Please select the order to Discard.");
		}
		echo json_encode($response);
	}

	/**
	 * function call to get all drivers
	 * @author Vaibhav Mehta
	 * Created date: 16-11-2017 06:00 PM
	 */
	function getDrivers()
	{
		$oid = $this->input->post('oid');
		if($oid)
		{
			$drivers 	= $this->Order_model->getDrivers($oid);
			$html 		= '';
			$html 		.= '<option value="0"> --- Select Driver --- </option>';
			if(is_array($drivers) && count($drivers)>0)
			{
				foreach ($drivers as $key => $value)
				{
					if($value->user_id==$value->delivered_by)
					{
						$html .= '<option value="'.$value->user_id.'" selected>';
					}
					else
					{
						$html .= '<option value="'.$value->user_id.'">';
					}
					$html .= $value->first_name.' '.$value->last_name.'</option>';
				}
				$response = array("success"=>"1","message"=>"Drivers available.","drivers"=>$html);
			}
			else
			{
				$response = array("success"=>"0","message"=>"No drivers available.","drivers"=>$html);
			}
		}
		else
		{
			$response = array("success"=>"0","message"=>"Please select restaurant for drivers.");
		}
		echo json_encode($response);
	}

	function changeDriver()
	{
		$uid = $this->userdata[0]->user_id;
		$oid = $this->input->post('oid');
		$did = $this->input->post('did');

		if($oid && $did)
		{
			$updateOrder = array();
			$updateOrder['delivered_by']	= $did;
			$updateOrder['updated_by'] 		= $uid;
			$updateOrder['updated_date'] 	= date("Y-m-d H:i:s");
			
			$update 		= $this->Order_model->updateOrderDriver($oid,$updateOrder);
			$driver 		= $this->Order_model->getDrivers($oid,$did);

			$name 			= $driver[0]->first_name.' '.$driver[0]->last_name;
			$contact 		= $driver[0]->contact_no;

			if($update)
			{
				$response = array("success"=>"1","message"=>"Order has been successfully assign to the driver.","driver_name"=>$name,"driver_contact"=>$contact);
			}
			else
			{
				$response = array("success"=>"0","message"=>"Order can't be assigned to selected driver, please try after sometime.");
			}
		}
		else
		{
			$response = array("success"=>"0","message"=>"Please select driver.");
		}
		echo json_encode($response);
	}

	

	/**
	 * function call to confirm order details
	 * @author Vaibhav Mehta
	 * Created date: 05-01-2018 02:40 PM
	 */
	function confirmOrderDetails()
	{
		$uid 	= $this->userdata[0]->user_id;
		$oid 	= $this->input->post('order_id');
		$os 	= $this->input->post('order_status');

		if($oid != "")
		{
			$updateOrder = array();				
			$updateOrder['order_status'] 			= $os;
			$updateOrder['order_confirmed_time'] 	= date("Y-m-d H:i:s");
			$updateOrder['updated_by'] 				= $uid;
			$updateOrder['updated_date'] 			= date("Y-m-d H:i:s");
			
			$update = $this->Order_model->updateOrder($oid,$updateOrder);

			if($update)
			{
				$response = array("success"=>"1","message"=>"Order has been confirmed successfully.","next_status_lbl"=>$this->labelColor[$os],"next_status"=>$this->OrderStatus[$os]);
			}
			else
			{
				$response = array("success"=>"0","message"=>"Order can't be confirmed, please try after sometime.");
			}
		}
		else
		{
			$response = array("success"=>"0","message"=>"Order can't be confirmed, please try after sometime.");
		}
		echo json_encode($response);
	}

	/**
	 * function call to confirm order details
	 * @author Vaibhav Mehta
	 * Created date: 05-01-2018 02:40 PM
	 */
	function changeDriverAndOrderStatus()
	{
		//print_r($this->userdata[0]->user_id);exit;
		$uid 			= $this->userdata[0]->user_id;
		$oid 			= $this->input->post('oid');
		$os 			= $this->input->post('os');
		$did 			= $this->input->post('did');
		$OrderStatus    = $this->config->item('OrderStatus');
		$panelColor     = $this->config->item('panelColor');
		$labelColor     = $this->config->item('labelColor');
		if($oid && $os)
		{
			$updateOrder = array();
			$updateOrder['order_status'] 	= $os;
			$updateOrder['delivered_by'] 	= $did;
			$updateOrder['updated_by'] 		= $uid;
			$updateOrder['updated_date'] 	= date("Y-m-d H:i:s");
			
			$update 	= $this->Order_model->updateOrder($oid,$updateOrder);
			$driver 	= $this->Order_model->getDrivers($oid,$did);
			$name 		= $driver[0]->first_name.' '.$driver[0]->last_name;
			$contact 	= $driver[0]->contact_no;

			$html = '';
			$listOrderStatus = '';

			if($update)
			{
				
				$html .= '<span class="active label '.$labelColor[$os].'">'.$OrderStatus[$os].'</span>';
				if($os<7)
				{
					$html .= '&nbsp;&nbsp;<i class="fa fa-arrow-right"></i>';
					$html .= '<span class="label '.$labelColor[$os+1].' changeOrder" oid="'.$oid.'" os="'.($os+1).'" data-toggle="modal" data-target="#cngStatusmodal" data-backdrop="static" data-keyboard="false" style="cursor: pointer;" title="Change Order Status">'.$OrderStatus[$os+1].'</span>';
				}
				
				$response = array("success"=>"1","message"=>"Order status changed successfully.","data"=>$html);

				if($os == 4){
					$this->sendPushNotificationUsingFirebaseToDriver($did,$oid);// Push Notification to driver
				}
			}
			else
			{
				$listOrderStatus .= '<span class="label '.$labelColor[($value->order_status)].'">'.$OrderStatus[($value->order_status)].'</span>';
				$response = array("success"=>"0","message"=>"Order status can't change, please try after sometime.","data"=>$html);
			}
		}
		else
		{
			$response = array("success"=>"0","message"=>"Please select the order for change order status.");
		}
		echo json_encode($response);
	}

	/**
	 * function call to set order status as replace
	 * @author manisha Kanazariya
	 * Created date: 05-03-2018 07:40 PM
	 */

	function ChangeRefundOrderStatus($ordreId){

			$uid 	 =$this->userdata[0]->user_id;

			if($ordreId)
			{
				$updateOrder = array();
				$updateOrder['order_status'] 	= '9';
				$updateOrder['updated_by'] 		= $uid;
				$updateOrder['updated_date'] 	= date("Y-m-d H:i:s");
				
				$update = $this->Order_model->updateOrder($ordreId,$updateOrder);

				if($update)
				{
					$response = array("success"=>"1","message"=>"Order has been refunded successfully.");
				}
				else
				{
					$response = array("success"=>"0","message"=>"Order can't be refunded, please try after sometime.");
				}
			}
			else
			{
				$response = array("success"=>"0","message"=>"Please select the order to refunded.");
			}

			echo json_encode($response);exit;	
	}
	/**
	 * function call to Replace order
	 * @author manisha Kanazariya
	 * Created date: 11-04-2018 07:40 PM
	 */
	function replaceOrder(){

			$uid 	 =$this->userdata[0]->user_id;
			$ordreId =$this->input->post('orderId');

			if($ordreId !='' )
			{
				$getOrderData        	=$this->Order_model->getOrderData($ordreId);
				$newOrderData['user_id']       		 	  =$getOrderData[0]->user_id;        
				$newOrderData['restaurant_id'] 		 	  =$getOrderData[0]->restaurant_id;        
				$newOrderData['total_price']   		 	  =$getOrderData[0]->total_price;        
				$newOrderData['order_placed_time']   	  =date('Y-m-d h:i:s');  
				$newOrderData['delivered_time']   	      ="";  
				$newOrderData['order_confirmed_time']  	  ="";  
				$newOrderData['reason']  	              =$getOrderData[0]->reason;  
				$newOrderData['order_type'] 			  =$getOrderData[0]->order_type;        
				$newOrderData['order_status'] 			  ='1';        
				$newOrderData['selected_delivery_address']=$getOrderData[0]->selected_delivery_address;        
				$newOrderData['delivery_charges']         =$getOrderData[0]->delivery_charges;        
				$newOrderData['is_active'] 				  =$getOrderData[0]->is_active;        
				$newOrderData['order_refer_by']  		  =$ordreId;       
				$newOrderData['created_by']  			  =$uid;       
				$newOrderData['created_date']  			  =date('Y-m-d h:i:s'); 
				//echo "<pre>";print_r($newOrderData);exit;
				$newOrder = $this->Order_model->addOrderData($newOrderData); 
				if($newOrder>0){
					$updateOldOrder =$this->Order_model->updateOrderDriver($ordreId,array('order_status'=>12));
					$response = array("success"=>"1","message"=>"Order has been replaced successfully.");
					$getOrderDetailsData 	=$this->Order_model->getOrderDetailsData($ordreId);
					if(count($getOrderDetailsData) >0){
						foreach ($getOrderDetailsData as $key => $value) {
							$newOrderDetailsData['order_id']  	    =$newOrder;
							$newOrderDetailsData['product_id']      =$value->product_id;
							$newOrderDetailsData['quantity'] 		=$value->quantity;
							$newOrderDetailsData['amount']          =$value->amount;
							$newOrderDetailsData['discount_type']   =$value->discount_type;
							$newOrderDetailsData['discount_amount'] =$value->discount_amount;
							$newOrderDetailsData['is_active']       =$value->is_active;
							$newOrderDetailsData['created_by']      =$uid; 
							$newOrderDetailsData['created_date']    =date('Y-m-d h:i:s');  
							
							$addDishDetails=$this->Order_model->addOrderDetailsData($newOrderDetailsData);
							if($addDishDetails >0){
								$ord =$this->Order_model->getOrderDishDetail($value->order_detail_id);
								if(!empty($ord)){
									$OrderDishDetail =$ord;
									$newOrderDishDetail['fk_order_detail_id'] =$addDishDetails;
									$newOrderDishDetail['fk_order_id']  		=$newOrder;
									$newOrderDishDetail['fk_dish_id'] 		=$ord[0]->fk_dish_id;
									$newOrderDishDetail['fk_choice_id']       =$ord[0]->fk_choice_id;
									$newOrderDishDetail['created_by']         =$uid; 
									$newOrderDishDetail['created_date']       =date('Y-m-d h:i:s');   
									$addOrderDishDetail =$this->Order_model->addOrderDishDetail($newOrderDishDetail); 
								}
							}
							
						}
						

					}
				}else{
					$response = array("success"=>"0","message"=>"Please select the order to replaced,please try again.");
				}    
				
			}
		
			echo json_encode($response);exit;	
			
	}

}