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
		$this->load->library('pdf');


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
		$data['totalNew']       = $this->Order_model->getOrderCount($resId)->total;
		$data['totalDisputed']  = $this->Order_model->getOrderCount($resId,8)->total;
		$data['order_category'] = $this->Order_model->getOrderTypes();
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
		$orderType  =($this->input->post('orderType'))?$this->input->post('orderType'):"";
		$status     =($this->input->post('status'))?$this->input->post('status'):null;

		$totalRow 					= $this->Order_model->getAllOrderCount($restaurant,$orderId,$phone,$date,$date1,$orderType,$status);
		
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

		$allOrders 			= $this->Order_model->getNewOrderDetails($restaurant,$config["per_page"],$offset,$orderId,$phone,$date,$date1,$orderType,$status);
		
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
			$allOrders[$key]->type  	 =$value->type;
			$allOrders[$key]->status     =$this->OrderStatus[$value->status];
			$allOrders[$key]->time       =date('m/d/Y h:i:s a',strtotime($value->order_time));
			$allOrders[$key]->sequence_no  	 =$value->sequence_no;
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
		$response['total']          = $this->Order_model->getOrderCount($resId)->total;
		$response['totalNew']       = $this->Order_model->getOrderCount($resId,1)->total;
		$response['totalDisputed']  = $this->Order_model->getOrderCount($resId,8)->total;
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

		$localityData = $this->Home_model->getLocalityData($getDelAdd[0]->locality_id);
		$orderData['area'] = $localityData->name;

		// echo "<pre>";print_r($getDelAdd);exit;
		$orderData['order_id'] 			   = $id;
		$orderData['sequence_no']		   = $orderDetail[0]->sequence_no;
		$orderData['order_status'] 		   = $orderDetail[0]->order_status;
		$orderData['order_placed_time']    = $orderDetail[0]->order_placed_time;
		$orderData['delivered_time'] 	   = $orderDetail[0]->delivered_time;
		$orderData['total_price'] 		   = $orderDetail[0]->total_price;
		$orderData['order_type'] 		   = $orderDetail[0]->order_type;
		$orderData['delivery_charges'] 	   = $orderDetail[0]->delivery_charges;
		$orderData['reason'] 			   = $orderDetail[0]->reason;
		$orderData['special_instruction']  = $orderDetail[0]->special_instruction;

		//restaurant detail 
		$orderData['restaurant_name']      = $getResData[0]->restaurant_name;

		//order delivery address
		$orderData['customer_name'] 	   = $getDelAdd[0]->customer_name;
		$orderData['customer_contact_no']  = $getDelAdd[0]->contact_no;
		$orderData['customer_email'] 	   = $getDelAdd[0]->email;
		$orderData['customer_latitude']    = $getDelAdd[0]->customer_latitude;
		$orderData['customer_longitude']   = $getDelAdd[0]->customer_longitude;
		$orderData['delivery_address'] 	   = $getDelAdd[0]->address1;
		$orderData['street'] 	           = $getDelAdd[0]->street;
		$orderData['building'] 	           = $getDelAdd[0]->building;
		$orderData['appartment_no'] 	   = $getDelAdd[0]->appartment_no;
		$orderData['block'] 	           = $getDelAdd[0]->block;
		$orderData['avenue'] 	           = $getDelAdd[0]->avenue;
		$orderData['floor'] 	           = $getDelAdd[0]->floor;
		
		$getDriverData= $this->Home_model->getDriver($orderDetail[0]->delivered_by);
		if($orderData['order_status']>3 && count($getDriverData)>0)
		{
        	 //order Driver details
			$orderData['d_first_name'] 	   = $getDriverData[0]->first_name;
			$orderData['d_last_name'] 	   = $getDriverData[0]->last_name;
			$orderData['d_contact_no'] 	   = "(+965)".$getDriverData[0]->contact_no;
			$orderData['d_contact_no'] 	   = "(+965)".$getDriverData[0]->contact_no;
			$orderData['driver_id'] 	   = $orderDetail[0]->delivered_by;
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
					$orderData['dishes'][$oDetailId]['description']    = $value->comment;
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
		// print_r($this->userdata[0]->user_id);exit;
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

							$orderDetails[$value->order_id]['sequence_no'] 				= $value->sequence_no;
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
							$orderDetails[$value->order_id]['appartment_no'] = $value->appartment_no;
							$orderDetails[$value->order_id]['building'] = $value->building;
							$orderDetails[$value->order_id]['block'] = $value->block;
							$orderDetails[$value->order_id]['floor'] = $value->floor;
							$orderDetails[$value->order_id]['street'] = $value->street;
							$orderDetails[$value->order_id]['avenue'] = $value->avenue;
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

							$orderchs = $this->Webservice_driver_model->getOrderDetailsForDeliveryChoiseEmail($oid, $value->product_id);

							if($orderchs) {
								$orderArr = [];
								$del_choise = '';
								foreach ($orderchs as $k => $val) {
									$orderArr[] = $val->choice_name;
									$del_choise = implode(', ', $orderArr);
								}
							}

							$orderDetails[$value->order_id]['dishes'][$value->product_id]['product_id']  = $value->product_id;
							$orderDetails[$value->order_id]['dishes'][$value->product_id]['quantity']  = $value->quantity;
							$orderDetails[$value->order_id]['dishes'][$value->product_id]['amount']  = $value->amount;
							$orderDetails[$value->order_id]['dishes'][$value->product_id]['discount_type']  = $value->discount_type;
							$orderDetails[$value->order_id]['dishes'][$value->product_id]['discount_amount']  = $value->discount_amount;
							$orderDetails[$value->order_id]['dishes'][$value->product_id]['name']  = $value->name;
							$orderDetails[$value->order_id]['dishes'][$value->product_id]['price']  = $value->price;
							$orderDetails[$value->order_id]['dishes'][$value->product_id]['description']  = $value->description;
							$orderDetails[$value->order_id]['dishes'][$value->product_id]['choice_name']  = $del_choise;
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
								$dishArray[0]['sequence_no'] = $dishArray[0]['sequence_no'];
								$dishArray[0]['order_id'] = $oid;
								$dishArray[0]['order_placed_time'] = $dishArray[0]['order_placed_time'];
								$dishArray[0]['total_price'] = $dishArray[0]['total_price'];
								$dishArray[0]['delivery_charges'] = $dishArray[0]['delivery_charges'];
								$address = $dishArray[0]['block']?', block-'.$dishArray[0]['block']:'';
								$address .= $dishArray[0]['street']?', '.$dishArray[0]['street'].', ':'';
								$address .= $dishArray[0]['avenue']?', '.$dishArray[0]['avenue'].', ':'';
								$address .= $dishArray[0]['building']?', building- '.$dishArray[0]['building']:'';
								$address .= $dishArray[0]['floor']?', floor- '.$dishArray[0]['floor']:'';
								$address .= $dishArray[0]['appartment_no']?$dishArray[0]['appartment_no']:'';
								$dishArray[0]['delivery_address'] = $address.$dishArray[0]['delivery_address'];

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
								$dishArray[0]['sequence_no'] = $dishArray[0]['sequence_no'];
								$dishArray[0]['order_id'] = $oid;
								$dishArray[0]['order_placed_time'] = $dishArray[0]['order_placed_time'];
								$dishArray[0]['total_price'] = $dishArray[0]['total_price'];
								$dishArray[0]['delivery_charges'] = $dishArray[0]['delivery_charges'];
								$address = $dishArray[0]['block']?', block-'.$dishArray[0]['block']:'';
								$address .= $dishArray[0]['street']?', '.$dishArray[0]['street'].', ':'';
								$address .= $dishArray[0]['avenue']?', '.$dishArray[0]['avenue'].', ':'';
								$address .= $dishArray[0]['building']?', building- '.$dishArray[0]['building']:'';
								$address .= $dishArray[0]['floor']?', floor- '.$dishArray[0]['floor']:'';
								$address .= $dishArray[0]['appartment_no']?$dishArray[0]['appartment_no']:'';
								$dishArray[0]['delivery_address'] = $address.$dishArray[0]['delivery_address'];

								$dishArray[0]['username'] = $dishArray[0]['user_first_name'].' '.$dishArray[0]['user_last_name'];
								$dishArray[0]['to_email'] = $dishArray[0]['customer_email'];
												//$dishArray[0]['to_email'] = 'vaibhav@itoneclick.com';
								$mails = $this->sendMail($dishArray[0]);
							}

							if($dishArray[0]['user_email'])
							{
								$dishArray1[0]['sequence_no'] = $dishArray[0]['sequence_no'];
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
		$oid 			= $this->input->post('oid');
		$userId         = $this->Order_model->getOrderData($oid);
		$uid 			= $userId[0]->user_id;
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

	/**
	 * [orderTypeList Order type list]
	 * @author Hardik Ghadshi
	 * @Created Date   2019-11-04T12:49:40+0530
	 * @return  [type] [description]
	 */
	function orderTypeList(){

		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$data['submenu']   	= $this->submenu;

		$data['result'] = $this->Order_model->getOrderTypes();

		$this->load->view('Elements/header',$data);
		$this->load->view('Ordertype/list_ordertype');
		$this->load->view('Elements/footer');
	}

	/**
	 * [addOrderType Add Order Type]
	 * @author Hardik Ghadshi
	 * @Created Date 2019-11-04T12:09:04+0530
	 */
	function addOrderType(){

		$data['userdata']	= $this->session->userdata('current_user');

		$orderType['type'] = $this->input->post('type_name');
		$orderType['is_active'] = 1;
		$orderType['created_by'] = $data['userdata'][0]->user_id;

		$getCategory = $this->Order_model->getOrderTypes($orderType['type']);
		if($getCategory)
		{
			$response = array("success"=>"0","message"=>"Order type already present!");
		}
		else
		{
			$result 	 = $this->Order_model->addOrderType($orderType);

			if($result){

				$response = array("success"=>"1","message"=>"Data added successfully","data" => $result);
			}else{
				$response = array("success"=>"0","message"=>"Something went wrong");
			}
		}

		echo json_encode($response);
	}

	/**
	 * [deleteOrderType Delete Order type]
	 * @author Hardik Ghadshi
	 * @Created Date   2019-11-04T17:23:26+0530
	 * @return  [type] [description]
	 */
	function deleteOrderType(){

		$data['userdata'] 	= $this->session->userdata('current_user');

		$req['is_active'] 	= 0;
		$req['updated_by'] 	= $data['userdata'][0]->user_id;
		$req['id'] 			= $this->input->post('id');

		$result = $this->Order_model->deleteOrderType($req);

		if($result){

			$response = array("success"=>"1","message"=>"Data removed successfully");
		}else{
			$response = array("success"=>"0","message"=>"Something went wrong");
		}

		echo json_encode($response);
	}

	/**
	 * [addOrder Add Order from backend]
	 * @author Hardik Ghadshi
	 * @Created Date 2019-11-04T18:19:59+0530
	 */
	function addOrder(){

		delete_cookie('dishDetail');

		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$data['submenu']   	= $this->submenu;

		$data['localitylist'] 	=$this->Restaurant_model->getlocality();
		$data['order_category'] = $this->Order_model->getOrderTypes();
		$restaurant 	= $this->Restaurant_model->getAllRestaurantDetails();
		
		for($i = 0; $i < sizeof($restaurant); $i++){

			if(empty($data['restaurant'][$i])) $data['restaurant'][$i] = new stdClass();

			$data['restaurant'][$i]->restaurant_id = $restaurant[$i]->restaurant_id;
			$data['restaurant'][$i]->restaurant_name = $restaurant[$i]->restaurant_name;
		}

		$this->load->view('Elements/header',$data);
		$this->load->view('Orders/add_order');
		$this->load->view('Elements/footer');
	}
	/**
	 * [saveOrder description]
	 * @author Rajnee Patel
	 * @Created Date   2019-11-11T18:08:17+0530
	 * @return  [type] [description]
	 */
	function saveOrder()
	{
		$data['userdata'] 	= $this->session->userdata('current_user');
		$userId 			= $data['userdata'][0]->user_id;

		$orderDate 		= $this->input->post('orderDate');
		$restaurant 	= $this->input->post('restaurant');
		$orderType 		= $this->input->post('orderType');
		$orderStatus 	= $this->input->post('orderStatus');
		$locality 		= $this->input->post('locality');
		$orderTypeId	= $this->input->post('orderTypeId');
		$customerId 	= $this->input->post('user');

		$orderDate = date("Y-m-d H:i:s", strtotime($orderDate));
		$dishdata  = json_decode($this->input->post('dishDetail'));

		$c          = 0;
		$dishprice  = 0;
		$totalprice = 0;
		$ch         = array();

		foreach ($dishdata as $key => $value1)
		{
			$dishDetail = $this->Webservice_customer_model->getCartDishDetail($value1->dishId,$_COOKIE['locality_id']);


			if(count($dishDetail)>0)
			{
				$finalDishData[$c]['dishId']           = $value1->dishId;
				$finalDishData[$c]['price']            = $dishDetail[0]->dish_price;
				$dishprice                             = $dishDetail[0]->dish_price;
				$finalDishData[$c]['dish_count']       = $value1->dishcount;
				$finalDishData[$c]['delivery_charges'] = $dishDetail[0]->delivery_charge;
				$finalDishData[$c]['description']	   = $value1->instruction;

				$i=0;
				$choices      =explode(',', $dishDetail[0]->choice_id);
				$choice_price =explode(',', $dishDetail[0]->choice_price);
				if(count($value1->choiceOfOne) >0)
				{
					foreach ($value1->choiceOfOne as $key => $value) {
						$ch[]=$value;
					}
				}
				
				if(count($value1->Multiplechoice) != "")
				{
					foreach ($value1->Multiplechoice as $key => $value) {
						$ch[]=$value;
					}
				}
				if(count($ch)>0)
				{

					foreach ($ch as $chId=> $chVal) {
						$dishchoice = $this->Home_model->getChoiceName($chVal);
						if(count($dishchoice)>0){
							$chk =array_search($chVal,$choices);
							$finalDishData[$c]['choice'][$i]['choice_id']  =  $dishchoice[0]->choice_id;
							$finalDishData[$c]['choice'][$i]['price']      = ($choice_price[$chk]!='0')?$choice_price[$chk]:0;
							$dishprice = $dishprice + $choice_price[$chk];
							$i++;
						}

					}
				}

				$finalDishData[$c]['totaldisheprice'] = $dishprice * $value1->dishcount;
				$totalprice = $totalprice + $finalDishData[$c]['totaldisheprice'];
				$c++;
				$ch  =array();
			}
		}
		
		$getLocalityData  =$this->Webservice_customer_model->getLocalityData($this->input->post('address_id'));
		$orderdata['user_id']           = $customerId;
		$orderdata['restaurant_id']     = $restaurant;

		if($orderTypeId == 2 || $orderTypeId == 3 || $orderTypeId == 5 || $orderTypeId == 6){
			$orderdata['delivery_charges'] = 0;
		}else{
			$orderdata['delivery_charges']  = $finalDishData[0]['delivery_charges'];
		}

		$orderdata['total_price']       = $totalprice+$orderdata['delivery_charges'];
		$orderdata['order_placed_time'] = $orderDate;
		if(isset($getLocalityData) && count($getLocalityData)>0){
			$timestamp = strtotime(date("Y-m-d H:i:s")) + 60*($getLocalityData[0]->delivered_time +$getLocalityData[0]->extra_delivery_time);
		}else{
			$timestamp = strtotime(date("Y-m-d H:i:s")) + 60*60;
		}
		$orderdata['expected_delivery_time']    = date('Y-m-d H:i:s', $timestamp);
		$orderdata['order_type']        		= $orderType;
		$orderdata['reason']            		= ($orderType == 1 || $orderType == 2)?'Payment not done':'';
		$orderdata['order_status']      		= $orderStatus;
		$orderdata['is_active']         		= 1;
		$orderdata['selected_delivery_address'] = $locality;
		$orderdata['created_by']        		= $userId;
		$orderdata['created_date']      		= date("Y-m-d H:i:s");
		$orderdata['order_category']      		= $orderTypeId;
		$tableName1                     		= 'tbl_orders';

		if($orderType != 1 && $orderType != 2){

			$seqNo = $this->Home_model->getLatestSequenceNumber();
			$orderdata['sequence_no'] = $seqNo->sequence_no + 1;
		}
		
		$orderres = $this->Webservice_customer_model->insertData($tableName1,$orderdata);
		foreach ($finalDishData as $key => $value)		{
			$orderdetailsdata['order_id']        = $orderres;
			$orderdetailsdata['product_id']      = $value['dishId'];
			$orderdetailsdata['quantity']        = $value['dish_count'];
			$orderdetailsdata['amount']          = $value['totaldisheprice'];
			$orderdetailsdata['discount_type']   = 0;
			$orderdetailsdata['discount_amount'] = 0;
			$orderdetailsdata['is_complimentry'] = 0;
			$orderdetailsdata['is_active']       = 1;
			$orderdetailsdata['created_by']      = $userId;
			$orderdetailsdata['created_date']    = date("Y-m-d H:i:s");
			$orderdetailsdata['description']	 = $value['description'];
			$table2                              = 'tbl_order_details';	
			$orderdetailres = $this->Webservice_customer_model->insertData($table2,$orderdetailsdata);
			$i=0;
			if(isset($value['choice']) && count($value['choice'])>0){
				foreach ($value['choice'] as $choicekey => $choicevalue) {
					$orderchoicedata['fk_order_detail_id'] = $orderdetailres;
					$orderchoicedata['fk_order_id'] = $orderres;
					$orderchoicedata['fk_dish_id'] = $value['dishId'];
					$orderchoicedata['fk_choice_id'] = $choicevalue['choice_id'];
					$orderchoicedata['created_by'] = $userId;
					$orderchoicedata['created_date'] = date("Y-m-d H:i:s");
					$table3 = 'tbl_order_dish_choice';
					$this->Webservice_customer_model->insertData($table3,$orderchoicedata);
					$i++;
				}
			}
		}

		$getUserData =$this->Webservice_customer_model->getUserdetails($userId);
		
		$response['success'] = 1;

		echo json_encode($response);exit;
	}

	public function exportOrderDetails($id,$print = '')
	{
		$data['userdata']	= $this->session->userdata('current_user');
		$data['panelColor'] = $this->config->item('panelColor');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();

		//$orderIdData 		= $this->Home_model->getOrderIdFromSequenceNo($id);

		$data['print'] = $print;

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
		$orderData['sequence_no'] 		   = $orderDetail[0]->sequence_no;
		$orderData['order_status'] 		   = $orderDetail[0]->order_status;
		$orderData['order_placed_time']    = $orderDetail[0]->order_placed_time;
		$orderData['delivered_time'] 	   = $orderDetail[0]->delivered_time;
		$orderData['total_price'] 		   = $orderDetail[0]->total_price;
		$orderData['order_type'] 		   = $orderDetail[0]->order_type;
		$orderData['delivery_charges'] 	   = $orderDetail[0]->delivery_charges;
		$orderData['reason'] 			   = $orderDetail[0]->reason;
		$orderData['special_instruction']  = $orderDetail[0]->special_instruction;

		//restaurant detail 
		$orderData['restaurant_name']      = $getResData[0]->restaurant_name;

		//order delivery address
		$orderData['customer_name'] 	   = $getDelAdd[0]->customer_name;
		$orderData['customer_contact_no']  = $getDelAdd[0]->contact_no;
		$orderData['customer_email'] 	   = $getDelAdd[0]->email;
		$orderData['customer_latitude']    = $getDelAdd[0]->customer_latitude;
		$orderData['customer_longitude']   = $getDelAdd[0]->customer_longitude;
		$orderData['delivery_address'] 	   = $getDelAdd[0]->address1;
		$orderData['street'] 	           = $getDelAdd[0]->street;
		$orderData['building'] 	           = $getDelAdd[0]->building;
		$orderData['appartment_no'] 	   = $getDelAdd[0]->appartment_no;
		$orderData['block'] 	           = $getDelAdd[0]->block;
		$orderData['avenue'] 	           = $getDelAdd[0]->avenue;
		$orderData['floor'] 	           = $getDelAdd[0]->floor;
		$orderData['locality_id'] 		   = $getDelAdd[0]->locality_id;

		$localityData = $this->Home_model->getLocalityData($orderData['locality_id']);
		$orderData['area'] = $localityData->name;
		
		$orderData['order_type_name'] = $this->config->item('payment_type'); 
		
		$getDriverData= $this->Home_model->getDriver($orderDetail[0]->delivered_by);
		if($orderData['order_status']>3 && count($getDriverData)>0)
		{
        	 //order Driver details
			$orderData['d_first_name'] 	   = $getDriverData[0]->first_name;
			$orderData['d_last_name'] 	   = $getDriverData[0]->last_name;
			$orderData['d_contact_no'] 	   = "(+965)".$getDriverData[0]->contact_no;
			$orderData['d_contact_no'] 	   = "(+965)".$getDriverData[0]->contact_no;
			$orderData['driver_id'] 	   = $orderDetail[0]->delivered_by;
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
					$orderData['dishes'][$oDetailId]['description']    = $value->comment;
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

			$html = $this->load->view('Orders/export_order_details',$data); 

			$this->load->library('pdf');
		    $this->load->view('Orders/export_order_details',$data);
		    $html = $this->output->get_output();
		    //$this->pdf->loadHtml($html);
		    $this->pdf->loadHtml($html);
		    // $customPaper = array(0,0,570,570);
		    //$this->pdf->set_paper($customPaper);
		    $this->pdf->setPaper('A2','portrait');//landscape
		    $this->pdf->render();
		    $this->pdf->stream("order_details.pdf", array('Attachment'=>1));

		}
	}
}