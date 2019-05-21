<?php

	/**
	 * Controller Name 	: Home
	 * Descripation 	: Use to manage Front-end site
	 * @author 			: Umang Kothari
	 * Created date 	: 15 December 2017 5:00PM
	 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller
{
	/**
	 * function to invoke necessary component
	 * @author Umang Kothari
	*/
	
	function __construct()
	{
		parent::__construct();
		$this->load->library('upload');
		//loading login model
		$this->load->model(array('Home_model'));
		$this->load->model(array('Webservice_customer_model','Restaurant_model','Order_model','Customer_model'));
		$this->default_language   = $this->config->item('default_language');
		$this->supported_language = $this->config->item('supported_languages');
		
		
		if(isset($_COOKIE['lang']) && $_COOKIE['lang']!='EN' && in_array($_COOKIE['lang'],$this->supported_language))
		{
			$this->ORD_status   = $this->config->item('OrderStatus_ar');
			$this->langs 		= $_COOKIE['lang'];
			$this->langsFile 	= $this->langs.'_frontend_lang';
		}
		else
		{
			$_COOKIE['lang']    ='EN';
			$this->ORD_status   = $this->config->item('OrderStatus');
			$this->langs 		= 'EN';
			$this->langsFile 	= 'EN_frontend_lang';	
		}

		$this->lang->load($this->langsFile,$this->langs);
		$this->localityId  = json_decode($_COOKIE['locality_id']);
		$this->locality    =$this->Home_model->getLocality();
		$this->cartItem   = count(json_decode($_COOKIE['dishDetail']));

	}

	/**
	 * deafult function call when controller class is load
	 * @author Umang Kothari
	 * Created Date: 2 Jan 2018
	 */
	
	function index_Old()
	{
		$data['message'] = new stdClass();
		$data['message']->onlineorder  = $this->lang->line('message_onlineorder');
		$data['message']->bannertext   = $this->lang->line('message_bannertext');
		$data['message']->autocompph   = $this->lang->line('message_autocompph');
		$data['message']->StartMyOrder = $this->lang->line('message_StartMyOrder');

		if(isset($_COOKIE['access_token']) && $_COOKIE['access_token']!='')
		{
			if(isset($_COOKIE['user_id']))
			{
				$data['userdata'] = $this->Webservice_customer_model->getUserdetails($_COOKIE['user_id']);
				$data['addressDetail'] =  $this->Webservice_customer_model->getDeliveryAddress($_COOKIE['user_id']);
			}
			else
			{
				$data['userdata'] = array();
				$data['addressDetail'] = array();
			}
		}
		$data['startorder'] = 1;
		
		$this->load->view('Elements/Frontend-header',$data);
		$this->load->view('Home/home');
		$this->load->view('Elements/Frontend-footer');
	}
	/**
	 * [index Home page function]
	 * @author Umang Kothari
	 * @Created Date   2019-01-29T11:43:57+0530
	 * @return  [type] [description]
	 */
	function index_home()
	{
		$this->load->view('Elements/Frontend-header',$data);
		$this->load->view('Home/index');
		$this->load->view('Elements/Frontend-footer');	
	}

	/**
	 * [dishes description]
	 * Description:Show dish list
	 * @author: Manisha Kanazariya
	 * @CreatedDate:2019-01-01T17:16:57+0530
	 */
	function index()
	{
		if(isset($_COOKIE['access_token']) && $_COOKIE['access_token']!='')
		{
			$data['userdata']       = $this->Webservice_customer_model->getUserdetails($_COOKIE['user_id']);
			$data['addressDetail']  = $this->Webservice_customer_model->getDeliveryAddress($_COOKIE['user_id']);
		}

		if(!isset($_COOKIE['locality_id']))
		{
			setcookie('locality_id', 1, -1,'/');
			$this->localityId        = json_decode($_COOKIE['locality_id']);
		}

		$dishCategory     =$this->Home_model->dishCategory($this->localityId);
		$data['locality'] = $this->Home_model->getLocality($this->localityId);
		$this->load->view('Elements/Frontend-header',$data);
		$this->load->view('Home/orderNow');
		$this->load->view('Elements/Frontend-footer');	
	}

	/**
	 * [getDishCategory description]
	 * Description:list dish Category and dish list
	 * @author: Manisha Kanazariya
	 * @CreatedDate:2019-01-01T17:17:14+0530
	 */
	public function getDishData()
	{
		$locality     =$this->input->post('locality');
		$search       =($this->input->post('search') !="")?$this->input->post('search'):null;
		$dishCategory =$this->Home_model->dishCategory($locality,$search);
		
		if(count($dishCategory) >0)
		{
			$dishes =array();

			foreach ($dishCategory as $key => $value) 
			{
				$dishes[$value->category_id]=$this->Home_model->getDishData($value->category_id,$locality,$search);
			}
			$result['success']  =1;
			$result['message']  ="";
			$result['category'] =$dishCategory;
			$result['dishes']   =$dishes;
		}
		else
		{
			$result['success'] =0;
			$result['message'] ='';
		}
	
		echo json_encode($result);exit;
	}
	

	function logoutUser()
	{
		setcookie('access_token', '',time() - 3600,'/');
		setcookie('user_id', '',time() - 3600,'/');
		unset($_COOKIE['access_token']);
		
		unset($_COOKIE['user_id']);
		redirect('Home');
	}
	/**
	 * [getlocalites description]
	 * Description:set locality data in dish page 
	 * @author: Manisha Kanazariya
	 * @CreatedDate:2019-01-03T18:39:24+0530
	 */
	function getlocalites($locality="")
	{
		$localites = $this->Home_model->getLocality($locality);
		//print_r($localites);exit;
		if(count($localites) >0)
		{
			$response['success'] =1;
			$response['message'] =($locality !="")?$localites[0]:$localites;
		}
		else
		{
			$response['success'] =0;
			$response['message'] ='';
		}

		echo json_encode($response);exit;
	}

	/**
	 * [getDishChoice description]
	 * Description:Get Dish Choice
	 * @author: Manisha Kanazariya
	 * @CreatedDate:2019-01-04T10:22:40+0530
	 */
	function getDishChoice()
	{
		$dishId   =$this->input->post('dish_id');
		$locality =$this->input->post('locality_id');
		$dishData =array();
		$getDishChoice =$this->Home_model->getRestaurantDishChoices($dishId,$locality);
		
		// echo "<pre>";print_r($getDishChoice);exit;
		if(count($getDishChoice) >0)
		{
			$dishData['dish_id']   =$getDishChoice[0]->product_id;
			$dishData['dish_name'] =($_COOKIE['lang'] =='AR')?$getDishChoice[0]->product_ar_name:$getDishChoice[0]->product_en_name;
			$dishData['choices']   =array();
			$choices     =explode(',', $getDishChoice[0]->choice_id);
			$choicePrice =explode(',', $getDishChoice[0]->choice_price);
		
			//array of choice and its price
			foreach ($choices as $k => $v) {
				$ch[$v] =$choicePrice[$k];
			}

			
			if($getDishChoice[0]->choice_id !="")
			{
				$dichChoice =$this->Home_model->getDishChoice($getDishChoice[0]->choice_id);
				foreach ($dichChoice as $ck => $cv) 
				{
					$dishData['choices'][$cv->is_multiple][$cv->choice_id]['name']  = ($_COOKIE['lang'] =='AR')?$cv->choice_name_ar:$cv->choice_name;
					$dishData['choices'][$cv->is_multiple][$cv->choice_id]['price'] = $ch[$cv->choice_id];
				}
								
			}
			echo json_encode($dishData);exit;
			echo "<pre>";print_r($dishData);exit;
		}
		
	}

	/**
	 * function call for Display profile data
	 * @author Umang Kothari
	 * Created Date: 2 Jan 2018
	 * Modified By: Rashmi Nayani
	 * Modified Date: 2 Jan 2018
	 */

	function Profile()
	{
		if(!isset($_COOKIE['user_id'])){
			redirect('Home');
		}
		$data=array();
		
		if(isset($_COOKIE['access_token']) && $_COOKIE['access_token']!=''){
			$data['userdata'] = $this->Webservice_customer_model->getUserdetails($_COOKIE['user_id']);
			$data['addressDetail'] =  $this->Webservice_customer_model->getDeliveryAddress($_COOKIE['user_id']);
			if(count($data['addressDetail'])>0){
				foreach ($data['addressDetail'] as $key => $value) {
					if(isset($_COOKIE['lang']) && $_COOKIE['lang'] =='AR'){
							$data['addressDetail'][$key]->name =$value->name_ar;
						}else{
							$data['addressDetail'][$key]->name =$value->name;
						}
				}
			}
		}

		//echo "<pre>";print_r($data['addressDetail']);exit;
		$this->load->view('Elements/Frontend-header',$data);
		$this->load->view('Home/profile');
		$this->load->view('Elements/modals');
		$this->load->view('Elements/Frontend-footer');		
	}

	/**
	 * function call for Display profile data
	 * @author Umang Kothari
	 * Created Date: 2 Jan 2018
	 * Modified By: Rashmi Nayani
	 * Modified Date: 2 Jan 2018
	 */
	
	function myOrder()
	{
		if(!isset($_COOKIE['user_id']))
		{
			redirect('Home');
		}
		
		if(isset($_COOKIE['access_token']) && $_COOKIE['access_token']!='')
		{
			$data['userdata']      = $this->Webservice_customer_model->getUserdetails($_COOKIE['user_id']);
			$data['addressDetail'] =  $this->Webservice_customer_model->getDeliveryAddress($_COOKIE['user_id']);
		}
		$Orders =array();
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'] !="")
		{
			$myorders=$this->Webservice_customer_model->myOrderList($_COOKIE['user_id']);
			if(count($myorders)>0)
			{
				foreach ($myorders as $key => $value) 
				{
					$Orders[$value->order_id]['user_id']                =$value->user_id;
					$Orders[$value->order_id]['restaurant_id']          =$value->restaurant_id;
					$Orders[$value->order_id]['expected_delivery_time'] =$value->expected_delivery_time;
					$Orders[$value->order_id]['delivered_time']         =$value->delivered_time;
					$Orders[$value->order_id]['order_status']           =$value->order_status;
					
					if(($_COOKIE['lang'] =="AR")?$value->product_ar_name:$value->product_en_name !=""){

					$Orders[$value->order_id]['dish_name'][]            =($_COOKIE['lang'] =="AR")?$value->product_ar_name:$value->product_en_name;
					}

				}
			}
	 	
		}
		else
		{
		 	redirect("Home/dishes");
		}
		$data['myorders'] =$Orders;
		$this->load->view('Elements/Frontend-header',$data);
		$this->load->view('Home/myorder');
		$this->load->view('Elements/modals');
		$this->load->view('Elements/Frontend-footer');			
	}

	/**
	 * function call for Display profile data
	 * @author Umang Kothari
	 * Created Date: 2 Jan 2018
	 * Modified By: Rashmi Nayani
	 * Modified Date: 2 Jan 2018
	 */
	
	function updateusername()
	{
		$userdata = $this->input->post();
		if(isset($userdata['email']))
		{
			if(!$this->checkEmailExist($userdata['email'])){

				$response = array("response"=>"false","message"=>$this->lang->line('message_email_exists'));
				echo json_encode($response);
				exit;
			}

		}
		if(isset($userdata['contact_no']))
		{
			if(!$this->checkPhoneExist($userdata['contact_no'])){

				$response = array("response"=>"false","message"=>$this->lang->line('message_phone_exists'));
				echo json_encode($response);
				exit;
			}

		}
		$updateduser = $this->Webservice_customer_model->updateUserData($_COOKIE['user_id'],$userdata);
		if($updateduser>=0)
		{
			$response = array("response"=>"true","message"=>$this->lang->line('message_profileUpdated'));	
		}
		else
		{
			$response = array("response"=>"false","message"=>$this->lang->line('message_profileNotUpdated'));		
		}
		
		echo json_encode($response);
		exit;
	}

	/**
	 * function call For City List
	 * @author Umang Kothari
	 * Created Date: 2 Jan 2018
	 */
	
	function City()
	{

		$state[0] = $this->input->post('state_id');
		$city = $this->Webservice_customer_model->getCity($state);
		$response= array("response"=>"true","message"=>"getcity","data"=>$city);
		echo json_encode($response);
		exit;
	}

	/**
	 * function call get dishdetails of cookie and display in cart
	 * @author Umang Kothari
	 *updated By :Manisha Kanazariya
	 * Created Date: 17 Jan 2018
	 */
	
	function getdishdetails()
	{

		$dishdata      = json_decode($_COOKIE['dishDetail']);	
 		// $locality      =$_COOKIE['restaurant_id'];
 		$locality      =$_COOKIE['locality_id'];
		$finalDishData =array();
		$c             = 0;
		
		if(count($dishdata)>0)
		{
			foreach ($dishdata as $key => $value1) 
			{
				$ch =array();
				$dishDetail              = $this->Home_model->getCartDishDetail($value1->dishId);
				
				if(count($dishDetail)>0)
				{
					$finalDishData[$c]['id']        = $value1->id;
					$finalDishData[$c]['dishid']    = $value1->dishId;
					$finalDishData[$c]['price']     = $dishDetail[0]->price; 
					$finalDishData[$c]['dishcount'] = $value1->dishcount;
					$finalDishData[$c]['dishname']  = ($this->langs =="AR")?$dishDetail[0]->product_ar_name:$dishDetail[0]->product_en_name;
					$finalDishData[$c]['instruction'] 	= $value1->instruction;

					$resDishDetail = $this->Home_model->getResDishDetail($value1->dishId,$locality);

					if(count($resDishDetail))
					{
						$finalDishData[$c]['locality'] =$locality;
						$finalDishData[$c]['delivery_charges'] =$resDishDetail[0]->delivery_charge;
						$finalDishData[$c]['price']    = (isset($resDishDetail[0]->dish_price))?$resDishDetail[0]->dish_price:0;
						$choices      =explode(',', $resDishDetail[0]->choice_id);
						$choice_price =explode(',', $resDishDetail[0]->choice_price);
						$ch           =array();


						if(count($value1->choiceOfOne)>0){
							
							foreach ($value1->choiceOfOne as $key => $value) {
								$ch[]=$value;
							}
						}
						
						if(count($value1->Multiplechoice)>0)
						{
							foreach ($value1->Multiplechoice as $key => $value) {
								$ch[]=$value;
							}
						}
						if(count($ch)>0){

							foreach ($ch as $chId=> $chVal) 
							{
								$dishchoice = $this->Home_model->getChoiceName($chVal);
								
								if(count($dishchoice)>0){
									$finalDishData[$c]['choice'][$chId]['choice_id']  =  $dishchoice[0]->choice_id;
									$finalDishData[$c]['choice'][$chId]['choice_name'] = ($this->langs =="AR")? $dishchoice[0]->choice_name_ar: $dishchoice[0]->choice_name;
									$finalDishData[$c]['choice'][$chId]['choice_description'] =  $dishchoice[0]->choice_description;

									$chk =array_search($chVal,$choices);

									$finalDishData[$c]['choice'][$chId]['price'] = ($choice_price[$chk]!='0')?$choice_price[$chk]:0;
									$finalDishData[$c]['price'] = $finalDishData[$c]['price'] + $finalDishData[$c]['choice'][$chId]['price'];
								}
							}
						}
					}
					$c++;
				}

			}
		}
		
		echo json_encode($finalDishData);exit;

	}

	/**
	 * function set order summary details 
	 * @authorManisha Kanazariya
	 * Created Date: 17 Jan 2018
	 */
	function orderSummary()
	{
		
 		$dishdata        = json_decode($_COOKIE['dishDetail']);
		if(!($this->input->post('locality')))
		{
			if(count($dishdata)==0)
			{
				redirect('Home');
			}
 		}
 		
		if(isset($_COOKIE['access_token']) && $_COOKIE['access_token']!='')
		{
			$data['userdata']      = $this->Webservice_customer_model->getUserdetails($_COOKIE['user_id']);
			$data['addressDetail'] =  $this->Webservice_customer_model->getDeliveryAddress($_COOKIE['user_id']);
		}
		else{
			$data['userdata']      = $this->Webservice_customer_model->getUserdetails($_COOKIE['user_id']);
			$data['addressDetail'] =  $this->Webservice_customer_model->getDeliveryAddress($_COOKIE['user_id']);	
		}


		// print_r($_POST);exit;
		//$dishdata        =json_decode('[{"id":1,"dishId":"225","choiceOfOne":["1"],"Multiplechoice":[],"dishcount":"2"},{"id":2,"dishId":"264","choiceOfOne":["1"],"Multiplechoice":[],"dishcount":"1"},{"id":3,"dishId":"258","choiceOfOne":["77"],"Multiplechoice":[],"dishcount":"1"}]');
		
		$locality        = json_decode($_COOKIE['locality_id']);
		$finalDishData   = array();
		$totalPrice      = 0;
		$deliveryCharge  = 0;
		$removeDishTotal = 0;
		$c               = 0;
		
		if(count($dishdata)>0)
		{
			//print_r($dishdata);
			foreach ($dishdata as $key => $value1) {

				$ch =array();
				$dishDetail = $this->Home_model->getCartDishDetail($value1->dishId);
				//print_r($dishDetail);
				if(count($dishDetail)>0)
				{
					$finalDishData[$c]['id']         = $value1->id;
					$finalDishData[$c]['dishid']     = $value1->dishId;
					$finalDishData[$c]['price']      = number_format(0,3); 
					$finalDishData[$c]['dish_count'] = $value1->dishcount;
					$finalDishData[$c]['subtotal']   = number_format(0,3);
					$finalDishData[$c]['total']      = number_format(0,3);
					$finalDishData[$c]['dish_name']  = ($this->langs =="AR")?$dishDetail[0]->product_ar_name:$dishDetail[0]->product_en_name;
					$finalDishData[$c]['locality']   ='';
					$finalDishData[$c]['instruction']   =$value1->instruction;
					$resDishDetail = $this->Home_model->getResDishDetail($value1->dishId,$locality);
					$finalDishData[$c]['choice_name'] ='';
					//print_r($resDishDetail);
					if(count($resDishDetail))
					{
						$deliveryCharge  =$resDishDetail[0]->delivery_charge;
						$finalDishData[$c]['locality'] =$locality;
						$finalDishData[$c]['price']    = (isset($resDishDetail[0]->dish_price))?$resDishDetail[0]->dish_price:0;
						$subtotal      = $finalDishData[$c]['price'];
						$i=0;
						$choices      =explode(',', $resDishDetail[0]->choice_id);
						$choice_price =explode(',', $resDishDetail[0]->choice_price);
						

						if(count($value1->choiceOfOne)>0)
						{
							foreach ($value1->choiceOfOne as $chk => $chv) 
							{
								$ch[]=$chv;
							}
							//$ch[$key] = $value1->choiceOfOne;
						}
						
						if(count($value1->Multiplechoice)>0 )
						{
							foreach ($value1->Multiplechoice as $k1 => $v1) {
								$ch[]=$v1;
							}
						}
						if(count($ch)>0)
						{
							$finalDishData[$c]['choice_name'] ='';
								foreach ($ch as $chId=> $chVal) {
								$dishchoice = $this->Home_model->getChoiceName($chVal);

								if(count($dishchoice)>0)
								{
									$ck =array_search($chVal,$choices);
									$finalDishData[$c]['choice_name'] =trim(($this->langs =="AR")? $dishchoice[0]->choice_name_ar: $dishchoice[0]->choice_name).','.$finalDishData[$c]['choice_name'] ;
									$subtotal =$subtotal+$choice_price[$ck];
									$i++;
								}
							}
						}
						$finalDishData[$c]['subtotal']          =number_format($subtotal,3);
						$finalDishData[$c]['total']             =number_format($value1->dishcount*$subtotal,3);
						$totalPrice =number_format($totalPrice+$finalDishData[$c]['total'],3);
					}	
					else
					{
						$removeDishTotal++;
					}

					$c++;
				}
				
			}
			
		}
		//echo "<pre>";print_r($finalDishData);exit;
		if($this->input->post('locality') !="")
		{
			$response['success']         = 1;
			$response['message']         = $finalDishData;
			$response['total']           = number_format($totalPrice+$deliveryCharge,3);
			$response['subtotal']        = number_format($totalPrice,3);
			$response['del_charge']      = number_format($deliveryCharge,3);
			$response['removeDishTotal'] = $removeDishTotal;
			echo json_encode($response);exit;

 		}
 		else
 		{
 			$data['finalDishData']   = $finalDishData;
			$data['total']           = number_format($totalPrice+$deliveryCharge,3);
			$data['subtotal']        = number_format($totalPrice,3);
			$data['del_charge']      = number_format($deliveryCharge,3);
			$data['removeDishTotal'] = $removeDishTotal;
			$data['localitylist'] 	=$this->Restaurant_model->getlocality();
			$this->isShowCart =0;
			//print_r($data);exit;
			$this->load->view('Elements/Frontend-header',$data);
			$this->load->view('Home/orderSummary');
			$this->load->view('Elements/Frontend-footer');
		}
	}

	/**
	 * function add order details 
	 * @authorManisha Kanazariya
	 * Created Date: 17 Jan 2018
	 */
	function addOrder()
	{
		$dishdata = json_decode($this->input->post('dishDetail'));

		$c          = 0;
		$dishprice  = 0;
		$totalprice = 0;
		$ch         = array();

		// $dishdata   = json_decode('[{"id":1,"dishId":"225","choiceOfOne":["1"],"Multiplechoice":[],"dishcount":"2"},{"id":2,"dishId":"264","choiceOfOne":["1"],"Multiplechoice":[],"dishcount":"1"},{"id":3,"dishId":"258","choiceOfOne":["77","2"],"Multiplechoice":[],"dishcount":"1"}]');

		
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
		$orderdata['user_id']           = $this->input->post('user_id');
		$orderdata['restaurant_id']     = $getLocalityData[0]->restaurant_id;
		$orderdata['delivery_charges']  = $finalDishData[0]['delivery_charges'];
		$orderdata['total_price']       = $totalprice+$orderdata['delivery_charges'];
		$orderdata['order_placed_time'] = date("Y-m-d H:i:s");
		if(isset($getLocalityData) && count($getLocalityData)>0){
			$timestamp = strtotime(date("Y-m-d H:i:s")) + 60*$getLocalityData[0]->delivered_time;
		}else{
			$timestamp = strtotime(date("Y-m-d H:i:s")) + 60*60;
		}
		$orderdata['expected_delivery_time']    = date('Y-m-d H:i:s', $timestamp);
		$orderdata['order_type']        		= $this->input->post('payment');
		$orderdata['reason']            		= ($this->input->post('payment')== 1 || $this->input->post('payment')== 2)?'Payment not done':'';
		$orderdata['order_status']      		= 1;
		$orderdata['is_active']         		= 1;
		$orderdata['selected_delivery_address'] = $this->input->post('address_id');
		$orderdata['created_by']        		= $this->input->post('user_id');
		$orderdata['created_date']      		= date("Y-m-d H:i:s");
		$tableName1                     		= 'tbl_orders';
	
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
			$orderdetailsdata['created_by']      = $this->input->post('user_id');
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
					$orderchoicedata['created_by'] = $this->input->post('user_id');
					$orderchoicedata['created_date'] = date("Y-m-d H:i:s");
					$table3 = 'tbl_order_dish_choice';
					$this->Webservice_customer_model->insertData($table3,$orderchoicedata);
					$i++;
				}
			}
		}

		$getUserData =$this->Webservice_customer_model->getUserdetails($this->input->post('user_id'));
		
		if($this->input->post('payment') == 1 || $this->input->post('payment') == 2)
		{
			$fields = array(
				"amount" 			=> $orderdata['total_price'],
				"currency_code"		=> "KWD",
 				"gateway_code" 		=> "test-knet",
				"order_no" 			=> "CUSTOMER".date("Ymdhis"),
				"customer_email" 	=> $getUserData[0]->email,
				"disclosure_url" 	=> site_url('Home/disclosurePayment/'.$orderres),
				"redirect_url" 		=> site_url('Home/orderDetails/'.$orderres)
			);
			$fields_string ="";
			
			//url-ify the data for the POST
			foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			$chd = curl_init();
			curl_setopt($chd,CURLOPT_URL, "https://pay.mughalmahal.com/pos/crt/");
			curl_setopt($chd,CURLOPT_POST, count($fields));
			curl_setopt($chd,CURLOPT_POSTFIELDS, rtrim($fields_string, '&'));
			curl_setopt($chd,CURLOPT_RETURNTRANSFER, true);
			//execute post
			$result = curl_exec($chd);
			$array  = json_decode($result);
			curl_close($chd);
			$response['success'] = 2;
			$response['url']     = $array->url;	
			if(!$array->url)
			{
				$this->Home_model->deleteOrder($orderres);
				$this->Home_model->deleteOrderDetail($orderres);
				$this->Home_model->deleteOrderDishChoice($orderres);
			}
		}
		else
		{
			$response['success'] = 1;
			$response['order_id'] = $orderres;	
		}
		

		echo json_encode($response);exit;
	}	

	function disclosurePayment($orderres)
	{
		$data1 = file_get_contents("php://input");
		$jsonData = json_decode($data1);
		$data['fk_order_id']        = $orderres;
		$data['paymentid']          = $jsonData->gateway_response->paymentid;
		$data['transaction_status'] = $jsonData->gateway_response->result;
		$data['amount']             = $jsonData->amount;
		$data['data']               =json_encode($data1);
		$this->Home_model->saveTransactionData($data);
		if($data['transaction_status'] == "CAPTURED")
		{
			//make order status is "order placed"
			$updateOrd =$this->Order_model->updateOrder($data['fk_order_id'],array('order_status'=>1,'reason'=>''));
			setcookie('dishDetail', null, -1, '/');
		}
		

	}

	/**
	 * function set order  details 
	 * @authorManisha Kanazariya
	 * Created Date: 17 Jan 2018
	 */
	function orderDetails($orderId="")
	{
		
		if(isset($_COOKIE['access_token']) && $_COOKIE['access_token']!='')
		{
			$data['userdata'] = $this->Webservice_customer_model->getUserdetails($_COOKIE['user_id']);
		}

		//get order drtails
		$orderDetails = $this->Home_model->getOrderDetails($orderId);

		$orderDetails[0]->order_placed_time = date("N",strtotime($orderDetails[0]->order_placed_time));

		if(count($orderDetails)>0)
		{
			$resRating    = $this->Home_model->getRestaurantRating($orderId);
			$drivetRating = $this->Home_model->getDriverRating($orderId);
			$getResData   = $this->Home_model->getRestaurant($orderDetails[0]->restaurant_id);
			$getResTime   = $this->Home_model->getRestaurantTime($orderDetails[0]->restaurant_id,$orderDetails[0]->order_placed_time);
			$getDelAdd    = $this->Home_model->getDeliveryAdd($orderDetails[0]->selected_delivery_address);

			$getResTime->time = date("H:i:s A",strtotime($getResTime->from_time))." - ".date("g:i:s A",strtotime($getResTime->to_time));

			$resRating    =(count($resRating)>0)?ceil($resRating[0]->rating):'0';
			$drivetRating =(count($drivetRating)>0)?ceil($drivetRating[0]->rating):'0';

			 //order restaurantr details
	        $orderData['restaurant']['name'] = $getResData[0]->restaurant_name;
	        $orderData['restaurant']['res_email']		= $getResData[0]->email;
	        $orderData['restaurant']['res_contact_no']	= $getResData[0]->contact_no;
	        $orderData['restaurant']['res_address']		= $getResData[0]->address;
	        $orderData['restaurant']['res_time']		= $getResTime->time;
	        if($orderDetails[0]->order_status>3)
	        {
	        	 //order Driver details
		       	$getDriverData= $this->Home_model->getDriver($orderDetails[0]->delivered_by);
		        $orderData['driver']['d_first_name']   = $getDriverData[0]->first_name;
		        $orderData['driver']['d_last_name']    = $getDriverData[0]->last_name;
		        $orderData['driver']['res_contact_no'] = $getDriverData[0]->contact_no;
		        $orderData['driver']['d_email']        = $getDriverData[0]->email;
	        }

	        //order dilivery address details
	        $orderData['address']['cust_name']       = $getDelAdd[0]->customer_name;
	        $orderData['address']['cust_email']      = $getDelAdd[0]->email;
	        $orderData['address']['cust_contact_no'] = $getDelAdd[0]->contact_no;
	        $orderData['address']['cust_address']    = $getDelAdd[0]->address1;
	        $orderData['address']['cust_longitude']  = $getDelAdd[0]->customer_longitude;
	        $orderData['address']['cust_latitude']   = $getDelAdd[0]->customer_latitude;

	        $dishId   =0;
			foreach ($orderDetails as $key => $value)
			{
				$orderData['order_id']        = $value->order_id;
				$orderData['restaurant_id']   = $value->restaurant_id;
				$orderData['driver_id']       = $value->delivered_by;
				$orderData['user_id']         = $value->user_id;
				$orderData['total_price']     = $value->total_price;
	            $orderData['delivery_time']   = $value->expected_del_time;
	            $orderData['delivered_time']  = $value->delivered_time;
	            $orderData['order_status']    = $value->order_status;
	            $orderData['rating']          = $resRating;
	            $orderData['dr_rating']       = $drivetRating;
	            $orderData['delivery_charges']= $value->delivery_charges;

	            $choice   =($_COOKIE['lang'] =="AR")?$value->choice_name_ar:$value->choice_name;
				
	            if($dishId != $value->order_detail_id)
	            {
	            	//order dish details
	            	$dishId   =$value->order_detail_id;
		            $orderData['dishes'][$dishId]['product_id']     = $value->product_id;
		            $orderData['dishes'][$dishId]['product_en_name']=($_COOKIE['lang'] =="AR")?$value->product_ar_name:$value->product_en_name;
		            $orderData['dishes'][$dishId]['en_description'] = $value->en_description;
		            $orderData['dishes'][$dishId]['price']          = $value->amount;
		            $orderData['dishes'][$dishId]['quantity']       = $value->quantity;
		            $orderData['dishes'][$dishId]['choices']        =$choice;
	            }
	            else
	            {

	            	$orderData['dishes'][$dishId]['choices']=$orderData['dishes'][$dishId]['choices'].','.$choice;
	            }
			}
			
			$data['orderDetails'] = $orderData; 
			$this->load->view('Elements/Frontend-header',$data);
			$this->load->view('Home/orderDetails');
			$this->load->view('Elements/Frontend-footer');
		}
		else
		{
			redirect('Home');
		}
		
	}
	
	/**
	 * function call get dishdetails of cookie and display in cart
	 * @author Umang Kothari
	 * Created Date: 19 Mar 2018
	 */

	function privacyPolicy()
	{
		$cmspages = $this->Home_model->getCmspages(3);
		echo($cmspages[0]->description);exit;
	}

	
	function fblogin()
	{
		$fbuserdata = (json_decode($_POST['userData']));
		$is_exists = $this->Webservice_customer_model->checkFacebookIdExists($fbuserdata->id);
		//print_r($is_exists);
		//print_r(count($is_exists));exit;
		if(count($is_exists)==0)
		{
			$userdata['role_id']		= $this->config->item('customer_role');
			$userdata['first_name'] = $fbuserdata->first_name;
			$userdata['last_name'] = $fbuserdata->last_name;
			$userdata['email'] = ($fbuserdata->email!='')?$fbuserdata->email:'';
			$userdata['gender'] = ($fbuserdata->gender!='male')?'M':'F';
			$userdata['profile_photo'] = $fbuserdata->picture->data->url;
			$userdata['facebook_id'] = $fbuserdata->id;
			$userdata['created_date']	= date("Y-m-d H:i:s");
			$tableName = 'tbl_users';

			$userId = $this->Webservice_customer_model->insertData($tableName,$userdata);
			
			if($userId)
			{
				$accessToken 					= md5(date("Y-m-d H:i:s"));
				$userMetaData['user_id']		= $userId;
				$userMetaData['device_type']	= trim('web');
				$userMetaData['device_token']   = trim('web');
				$userMetaData['access_token']   = $accessToken;
				$tableName1 					= 'tbl_user_meta';

				$res = $this->Webservice_customer_model->insertData($tableName1,$userMetaData);

				$userData['access_token'] 		= $accessToken;
				$userData['user_id']      		= $userId;

				$response=array("response"=>"true","data"=>$userData);
			}
			else
			{
				$response = array("response"=>"false","webservice_name"=>$error_register,"message"=>$customer_not_created);	
			}
		}
		else
		{
			$accessToken 					= md5(date("Y-m-d H:i:s"));
			$userMetaData['user_id']		= $is_exists[0]->user_id;
			$userMetaData['device_type']	= trim('web');
			$userMetaData['device_token']   = trim('web');
			$userMetaData['access_token']   = $accessToken;
			$tableName1 					= 'tbl_user_meta';

			$res = $this->Webservice_customer_model->insertData($tableName1,$userMetaData);
			$userData['access_token'] 		= $accessToken;
			$userData['user_id']      		= $is_exists[0]->user_id;

			$response=array("response"=>"true","data"=>$userData);
		}
		echo json_encode($response);


	}

	

	/**
	 * function call set locality in session for dish page
	 * @author Manisha Kanazariya 
	 * Created Date: 18 April 2018
	 */

	function setLocalityInSession(){
		$locality       =$this->input->post('locality');
		$locality_value =$this->input->post('locality_value');
		if(isset($locality) && $locality !=''){
			$_SESSION['locality'] = $locality;
			$_SESSION['locality_value'] = $locality_value;
		}else{
			$_SESSION['locality'] = "";
			$_SESSION['locality_value'] = "";
		}
		exit;
	}
	
	/**
	 * function to check duplicate Restaurant Exist
	 * @author Rashmi Nayani
	 * Created date: 09-10-2017 5:30 PM
	 */
	public function getLatlongdata(){
		$latlongId 	= $this->input->post('latlong_id');
		$result 	= $this->Restaurant_model->getLatlongdata($latlongId);
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
	 * function to add Dilivery Address the customer
	 * @author Manisha kanazariya 
	 * Created date: 06-2-2018 06:30 PM
	 */
	function addDiliverAddress(){
		
			$address_id                             =$_POST['address_id'];
			$delivery_address['user_id']            =$_POST['user_id'];
			$delivery_address['address1']           =$_POST['address1'];
			$delivery_address['address_type']       =$_POST['address_type'];
			$delivery_address['customer_name']      =$_POST['customer_name'];
			$delivery_address['email']              =$_POST['email'];
			$delivery_address['contact_no']         =$_POST['contact_no'];
			$delivery_address['customer_latitude']  =$_POST['customer_latitude'];
			$delivery_address['customer_longitude'] =$_POST['customer_longitude'];
			$delivery_address['locality_id']        =$_POST['locality_id'];
			$delivery_address['is_active']          =1;
			$delivery_address['created_date']       =date('Y-m-d H:i:s');
		    
		    if($address_id !=""){
		    	$addDiliverAddress =$this->Customer_model->updateDiliverAddress($delivery_address,$address_id);
		    }else{
				$addDiliverAddress =$this->Customer_model->addDiliverAddress($delivery_address);
		    }
			if($addDiliverAddress > 0){
				$response['success'] =1;
				$response['data'] = $addDiliverAddress;
			}else{
				$response['success'] =0;
			}

			echo json_encode($response);exit;
			
	}

	/**
	 * function to delete Dilivery Address the customer
	 * @author Manisha kanazariya 
	 * Created date: 04-5-2018 06:30 PM
	 */
	function deleteCustomerAddress($address_id){

		$deleteCustomerAddress =$this->Customer_model->deleteCustomerAddress($address_id);
		if($deleteCustomerAddress>0){
			$response['success'] =1;
		}else{
			$response['success'] =0;
			$response['message'] =$this->lang->line('message_tryAgain');
		}
		echo json_encode($response);exit;
	}
	/**
	 * function to delete Dilivery Address the customer
	 * @author Manisha kanazariya 
	 * Created date: 04-5-2018 06:30 PM
	 */
	function getCustomerAddress($address_id){

		$getCustomerAddress =$this->Webservice_customer_model->getDeliveryAddress($_COOKIE['user_id'],$address_id);
		if(count($getCustomerAddress)>0){
			$response['success'] =1;
			$response['message'] =$getCustomerAddress;
		}else{
			$response['success'] =0;
			$response['message'] =$this->lang->line('message_tryAgain');
		}
		echo json_encode($response);exit;
	}

	/**
	 * function to update customer Profile photo
	 * @author Manisha kanazariya 
	 * Created date: 04-5-2018 06:30 PM
	 */
	function uploadCustomerPhoto(){
	
		
		$response['success'] =0;
		$response['message'] ="Please select photo";
		if($_FILES['uploadfile']['name'] != ""){

			$config['upload_path']   		= './assets/uploads/users/customers'; 
			$config['allowed_types']        = 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
			$config['max_size']             = 5120;
			$config["encrypt_name"] 		= true;
			
			$this->upload->initialize($config);
			if (! $this->upload->do_upload('uploadfile')){
				$error = array('error' => $this->upload->display_errors());
				$response['success'] =0;
			    $response['message'] =$error;
			}
			else{
				$dataupload 	             = array('upload_data' => $this->upload->data());
				
			    $userData['profile_photo']  =$dataupload['upload_data']['file_name'];
			    $userData['updated_date']   =date('Y-m-d H:i:s');
			    $updateProfile              = $this->Webservice_customer_model->updateUserData($_COOKIE['user_id'],$userData);
			    if($updateProfile>0){
			    	$response['success']         =1;
			    	$response['message']        =$dataupload['upload_data']['file_name'];
			    }else{
			    	$response['success']         =0;
			        $response['message'] =$this->lang->line('message_tryAgain');

			    }
			}
		}
		echo json_encode($response); exit;
	}

	/**
	 * Description : add favourite dish
	 * Created by : manisha Kanazariya
	 * Created Date: 30/04/18 1:30 PM 
	*/

	function addFavouriteDish(){
		$user_id =$this->input->post('user_id');
		$dish_id =$this->input->post('dish_id');
		

		if($user_id ==""){
			$response['success'] ="false";
			$response['message'] ="User id is required";
		}else if($dish_id ==""){
			$response['success'] ="false";
			$response['message'] ="Dish id is required";
		}else{
			$alreadyFavourite =$this->Webservice_customer_model->listFavouriteDish($user_id,$dish_id);
			if(count($alreadyFavourite)>0){
				$response['success'] ="false";
				$response['message'] ="This dish id already favourite.";
			}else{
				$data['fk_user_id'] =$user_id;
				$data['fk_dish_id'] =$dish_id;
				$data['created_date'] =date('Y-m-d H:i:s');
				$addFavouriteDish =$this->Webservice_customer_model->insertData('tbl_favourite_dishes',$data);

				if($addFavouriteDish >0){
					$response['success'] ="true";
					$response['message'] ="Your favourite dish added successfully";
				}else{
					$response['success'] ="false";
					$response['message'] ="Something went wrong please try again";
				}
			}
			
		}

		echo json_encode($response);exit;
	}

	/**
	 * Description : Remove favourite dish
	 * Created by : manisha Kanazariya
	 * Created Date: 2/05/18 1:30 PM 
	*/

	function removeFavouriteDish(){
		$user_id =$this->input->post('user_id');
		$dish_id =$this->input->post('dish_id');
		if($user_id ==""){
			$response['success'] ="false";
			$response['message'] ="User id is required";
		}else if($dish_id ==""){
			$response['success'] ="false";
			$response['message'] ="Dish id is required";
		}else{
		    $getFavouriteDish    =$this->Webservice_customer_model->listFavouriteDish($user_id,$dish_id);
		    if(count($getFavouriteDish) >0){
				$removeFavouriteDish =$this->Webservice_customer_model->removeFavouriteDish($user_id,$dish_id);

				if($removeFavouriteDish >0){
					$response['success'] ="true";
					$response['message'] ="Your favourite dish removed successfully";
				}else{
					$response['success'] ="false";
					$response['message'] ="Something went wrong please try again";
				}
			}else{
				$response['success'] ="false";
				$response['message'] ="This dish not favourite";
			}
			
		}
		echo json_encode($response);exit;
	}
	/**
	 * Description : list favourite dish
	 * Created by : manisha Kanazariya
	 * Created Date: 30/04/18 1:30 PM 
	*/
	function getAllFavouriteDish(){
		$user_id       =$this->input->post('user_id');
		
		if($user_id ==""){
			$response['success'] ="false";
			$response['message'] ="User id is required";
		}else{
			$allFavouriteDish =$this->Webservice_customer_model->listFavouriteDish($user_id,'');
		
			if(count($allFavouriteDish) >0){
				$response['success'] ="true";
				$response['message'] ="Your favourite dish listed successfully";
				$response['Dishes']  =$allFavouriteDish;
			}else{
				$response['success'] ="false";
				$response['message'] ="Your favourite dishes not found";
			}
		}
		echo json_encode($response);exit;
	}

	/**
	 * Description : show all favourite  dishes 
	 * Updated by  : Manisha Kanazariya
	 * Created Date: 13/06/18 03:30 PM 
	*/
	function favouriteDishes(){

		$data=array();
		$data['message'] = new stdClass();
		if(isset($_COOKIE['access_token']) && $_COOKIE['access_token']!=''){
			$data['userdata'] = $this->Webservice_customer_model->getUserdetails($_COOKIE['user_id']);
			$data['addressDetail'] =  $this->Webservice_customer_model->getDeliveryAddress($_COOKIE['user_id']);
		}
		if(isset($_COOKIE['lang']) && $_COOKIE['lang']!='' && in_array($_COOKIE['lang'],$this->supported_language))
		{
			$this->langs 		= $_COOKIE['lang'];
			$this->langsFile 	= $this->langs.'_frontend_lang';
		}
		else
		{
			$this->langs 		= 'EN';
			$this->langsFile 	= 'EN_frontend_lang';	
		}
		$locality =$this->Webservice_customer_model->getLocality();

		$data['locality_id'] =$locality[0]->locality_id;
		if(isset($_COOKIE['lang']) && $_COOKIE['lang'] =='AR'){
			$data['locality_name'] =$locality[0]->name_ar;
		}else{
			$data['locality_name'] =$locality[0]->name;
		}
		$this->lang->load($this->langsFile,$this->langs);
		$data['message']->onlineorder = $this->lang->line('message_onlineorder');
		$data['displaycart'] = 1;
		$data['startorder'] = 1;
		$this->load->view('Elements/Frontend-header',$data);
		$this->load->view('Home/favourite_dishes');
		$this->load->view('Elements/modals');
		$this->load->view('Elements/Frontend-footer');
	}

	/**
	 * Description : get all favourite  dishes 
	 * Updated by  : Manisha Kanazariya
	 * Created Date: 13/06/18 03:30 PM 
	*/
	function getFavouriteDishes($locality)
	{
		$lang =$_COOKIE['lang'];
		$dishes ="";
 		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id']!=''){
 			$user_id       =$_COOKIE['user_id'];	
 			$favouriteDish =$this->Webservice_customer_model->listFavouriteDish($user_id);

 			$favDishes     =array();
 			if(count($favouriteDish)>0){
 				foreach ($favouriteDish as $key => $value) {
 					$favDishes[]= $value->fk_dish_id;
 					$dishes     .="'".$value->fk_dish_id."',";
 				}

 			}
 		}
 		

 		//get dish rating
		$dishRating = $this->Home_model->dishesRating();
		if(count($dishRating)>0){
			foreach ($dishRating as $key => $value) {
				$dishRate[$value->dish_id] =ceil($value->rating/$value->total);
			}
		}
		//END get dish rating
 		
 		if(isset($favDishes) && !empty($favDishes)){

    	    $dishIds  = rtrim($dishes,',');
    		$dishesData['dishes']  = $this->Home_model->getAllFavouriteDishes($dishIds);
    		
			foreach ($dishesData['dishes'] as $key => $value) {
				
				$dishDetails      	= $this->Home_model->getFavouriteDishDetails($locality,$value->product_id);

			    foreach ($dishesData['dishes'] as $key1 => $value1) {

			    	$dishesData['dishes'][$key]->product_en_name =($lang =='AR')?$value->product_ar_name:$value->product_en_name;
			    	$dishesData['dishes'][$key]->en_description  =($lang =='AR')?$value->ar_description:$value->en_description;
			    	$dishesData['dishes'][$key]->in_locality     =0;
			    	if(isset($favDishes) && !empty($favDishes)){
			    		if(in_array($value1->product_id,$favDishes)){
			    			$dishesData['dishes'][$key]->is_favourite= 1;
			    		}else{
			    			$dishesData['dishes'][$key]->is_favourite= 0;
			    		}
			    	}else{
			    			$dishesData['dishes'][$key]->is_favourite= 0;
			    		}
			    	$choice       =array();

			    	if(count($dishDetails)>0){
			    		$dishesData['dishes'][$key]->in_locality     =1;
						$dishesData['dishes'][$key]->dish_price  =$dishDetails[0]->dish_price;
						if($dishDetails[0]->choice_id !=''){
							$choices      =explode(',',$dishDetails[0]->choice_id);
							$choicePrices =explode(',',$dishDetails[0]->choice_price);

							foreach ($choices as $key2 => $value2) {
								$choice[$key2]['choice_id']     =$value2;
								$choice[$key2]['choice_value']  =$choicePrices[$key2];
							}
						}
						
					}
					$dishesData['dishes'][$key]->choices    =$choice;
			    }
				
			}
			
			if(is_array($dishesData) && count($dishesData)>0)
			{				
				$response = array("response"=>"true","webservice_name"=>"getdishescategorywise","message"=>'Data is found',"dishesData"=>$dishesData,"locality_id"=>$locality);

					if(isset($favDishes) && !empty($favDishes)){
			    		$response['favouriteDish'] = $favDishes;
			    	}
			    	if(isset($dishRate) && !empty($dishRate)){
			    		$response['dishRating'] = $dishRate;
			    	}
				
			}
			else
			{
				$response = array("response"=>"false","webservice_name"=>"getdishescategorywise","message"=>'No data is found');
			}

    	}else{
    		$response = array("response"=>"false","webservice_name"=>"getdishescategorywise","message"=>'No data is found');
    	}	
		
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : to check  is customer email exist 
	 * Updated by  : Manisha Kanazariya
	 * Created Date: 13/06/18 03:30 PM 
	*/
	function checkEmailExist($email){
		$user_id =$_COOKIE['user_id'];
		$existEmail 	= $this->Webservice_customer_model->checkEmailExist($email,$user_id);
		if(count($existEmail)>0){
			return false;
			
		}else{
			return true;
		}
	}
	/**
	 * Description : to check  is customer mobile exist 
	 * Updated by  : Manisha Kanazariya
	 * Created Date: 14/06/18 03:30 PM 
	*/
	function checkPhoneExist($phone){
		$user_id =$_COOKIE['user_id'];
		$existPhone 	= $this->Webservice_customer_model->checkPhoneExist($phone,$user_id);
		if(count($existPhone)>0){
			return false;
		}else{
			return true;
		}
	}

	/**
	 * Description : set rating of dishes
	 * Updated by  : Manisha Kanazariya
	 * Created Date: 14/06/18 03:30 PM 
	*/
	function addDishRating(){
		$dishData     = $this->input->post();

		$user_id      =$dishData['user_id'];
		$dish_id      =$dishData['dish_id'];
		$checkRating  =$this->Home_model->checkRating($dish_id,$user_id);
		if(count($checkRating)>0){
			$dishData['updated_date'] =date("Y-m-d h:i:s");
			
			$addRating   =$this->Home_model->updateDishRating($dish_id,$user_id,$dishData);
		}else{
			
			$addRating   =$this->Home_model->addDishRating($dishData);
		}
		if($addRating>=0){
			
			$response['success']      =1;
		}else{
			$response['message']      =0;
		}

		echo json_encode($response);exit;
	}

	/**
	 * Description : get order status
	 * Updated by  : Manisha Kanazariya
	 * Created Date: 14/06/18 03:30 PM 
	*/
	function allMyorderStatus($oid=""){

		$myorders=$this->Home_model->allMyorderStatus($_COOKIE['user_id'],$oid);
		if(count($myorders)>0){
			foreach ($myorders as $key => $value) {
				   $myorders[$key]->status_val = $this->ORD_status[$value->status];
			}
			$response['success'] =1;		
			$response['message'] =$myorders;		
		}else{
			$response['success'] =0;
		}

		echo json_encode($response);exit;
	}

	/**
	 * Description : it is used for set restaurant detals for order summary
	 * Updated by  : Manisha Kanazariya
	 * Created Date: 4/07/18 03:30 PM 
	*/
	function getRestaurantDetail()
	{
		$locality  =($this->input->post('locality'))?$this->input->post('locality'):"";
		$day       =$this->config->item('day')[date('D')];  
		$getDetail =$this->Home_model->getRestaurantDetail($locality);
		$getTime   =$this->Home_model->getRestaurantDetail($locality,$day);
		if(count($getDetail)>0)
		{
			$response['success']    =1;
			$response['restaurant'] =$getDetail[0];
			$response['times']      =array();

			if(isset($getTime[0]->from_time))
			{
				foreach ($getTime as $key => $value) 
				{
					$response['times'][] =date('H:i:s A',strtotime($value->from_time))." - ".date('g:i:s A',strtotime($value->to_time));
				}
			}
		}
		else
		{
			$response['success'] =0;
			$response['message'] ="";
		}
		echo json_encode($response);exit;
	}

	/**
	 * Description : it is used for checking restaurant opening time
	 * Updated by  : Manisha Kanazariya
	 * Created Date: 4/07/18 03:30 PM 
	*/

	function checkOrderPlaceTime(){
		$locality =($this->input->post('locality'))?$this->input->post('locality'):"";
		$day      =$this->config->item('day')[date('D')];
		$getTime  =$this->Home_model->getRestaurantDetail($locality,$day);
		
		if(isset($getTime[0]->from_time)){
			$currunt = date('H:i:s');
			$count   =0;
			$j       =0;
			foreach ($getTime as $key => $value) {
				$from = date('H:i:s',strtotime($value->from_time));
				$to   = date('H:i:s',strtotime($value->to_time));
				if(strtotime($currunt) >= strtotime($value->from_time) && strtotime($currunt)<=strtotime($value->to_time) ) {
				 	$count =0;
				 	break;
				}else{
					$count++;
				}
			}
			
			if($count == 0){
				$response['success'] =1;
			}else{
				$response['success'] =0;
			}
		}else{
			$response['success'] =0;
		}
		echo json_encode($response);exit;
	}

	/**
	 * Description : it is used for add restaurant rating
	 * Updated by  : Manisha Kanazariya
	 * Created Date: 7/07/18 03:30 PM 
	*/

	function addRestaurantRating(){
		$ratingData =$_POST;
		$ratingData['user_id']      =$_COOKIE['user_id'];
		$ratingData['created_by	']  =$_COOKIE['user_id'];
		$ratingData['created_date'] =date('Y-m-d h:i:s');

		$this->setDusputeOrder();
		$addRating =$this->Home_model->addRestaurantRating($ratingData);
		if($addRating>0){
			$response['success'] =1;
		}else{
			$response['success'] =0;
		}
		echo json_encode($response);exit;
	}
	/**
	 * Description : it is used for add driver rating
	 * Updated by  : Manisha Kanazariya
	 * Created Date: 7/07/18 03:30 PM 
	*/

	function addDriverRating(){
		$driverData =$_POST;
		$driverData['user_id']      =$_COOKIE['user_id'];
		$driverData['created_by	']  =$_COOKIE['user_id'];
		$driverData['created_date'] =date('Y-m-d h:i:s');
		$addRating =$this->Home_model->addDriverRating($driverData);
		if($addRating>0){
			$this->setDusputeOrder();
			$response['success'] =1;
		}else{
			$response['success'] =0;
		}
		echo json_encode($response);exit;
	}

	/**
	 * Description : it is used for update order as disputed order
	 * Updated by  : Manisha Kanazariya
	 * Created Date: 9/07/18 03:30 PM 
	*/
    function setDusputeOrder(){
    	if($_POST['rating'] <=3){
    		$update = $this->Order_model->updateOrder($_POST['order_id'],array('order_status'=>'8','reason'=>$_POST['reason'],'updated_by'=>$_COOKIE['user_id'],'updated_date'=>date('Y-m-d H:i:s')));
    	}
     	return true;
    }
	
   /**
	 * Description : it is used get best dishes of the restaurant
	 * Updated by  : Manisha Kanazariya
	 * Created Date: 28/09/18 03:30 PM 
	*/
	function bestDishes($locality){

		$bestDishes        =$this->Home_model->getBestDishes($locality);
		$getResDishDetail  =$this->Home_model->getRestaurantDetail($locality);
		if(date('d') == 1)
		{
			$monthIni = new DateTime("first day of last month");
			$monthEnd = new DateTime("last day of last month");
			$startDate=$monthIni->format('Y-m-d H:i:s');
			$endDate  =$monthEnd->format('Y-m-d H:i:s');
		}
		else
		{
			$startDate =date('Y-m-01 H:i:s');
			$endDate =date('Y-m-t H:i:s');
		}
		$mostSellingDishes =$this->Home_model->mostSellingDishes($startDate,$endDate);
		if(count($bestDishes)>0){

			foreach ($bestDishes as $key => $value) {
				
				$bestDishes[$key]->dishName    = ($_COOKIE['lang']!='EN')?$value->product_ar_name:$value->product_en_name;
				$bestDishes[$key]->description = ($_COOKIE['lang']!='EN')?$value->ar_description:$value->en_description;
			}
			
			$response = array("success"=>"1","dish"=>$bestDishes,"message"=>"","mostSellingDishes"=>$mostSellingDishes,"Restaurant"=>$getResDishDetail);
		}
		else
		{
			$response=array("success"=>'0',"message"=>$this->lang->line('message_noDishFound'),"mostSellingDishes"=>$mostSellingDishes,"Restaurant"=>$getResDishDetail);
		}
		echo json_encode($response);exit;
	}
	
	/**
	 * Description : it is used for save userdata who signed in by google account
	 * Updated by  : Manisha Kanazariya
	 * Created Date: 9/07/18 03:30 PM 
	*/
	function saveGoogleData(){
		
		$data 	= $this->Home_model->ExistGoogleUser($_POST['google_id']);
		if(count($data)>0){

			$userDetails['user_id'] 		= $data[0]->user_id;
			$userDetails['first_name'] 		= $data[0]->first_name;
			$userDetails['last_name'] 		= $data[0]->last_name;
			$userDetails['email'] 			= $data[0]->email;
			$userDetails['profile_photo'] 	= $data[0]->profile_photo;
			$userDetails['role_id'] 		= $data[0]->role_id;
			$updateData 	= $this->Webservice_customer_model->updateUserData($data[0]->user_id,array("updated_date"=>date('Y-m-d H:i:s')));
		}else{
			$userId = $this->Webservice_customer_model->insertData('tbl_users',$_POST);
			if($userId>0)
			{
				$userDetails['user_id'] 		= $userId;
				$userDetails['first_name'] 		= $_POST['first_name'];
				$userDetails['last_name'] 		= $_POST['last_name'];
				$userDetails['email'] 			= $_POST['email'];
				$userDetails['profile_photo'] 	= $_POST['profile_photo'];
				$userDetails['role_id'] 		= $_POST['role_id'];
				$userDetails['role_id'] 		= $_POST['role_id'];
				
			}
		}
		if(count($userDetails)>0){
			$response = array("success"=>1,"data"=>$userDetails,"message"=> $this->lang->line('message_normalUserLogin'),"webservice_name"=>"normalUserLogin");
		}
		else
		{
			$response=array("success"=>0,"message"=>$this->lang->line('invalid_login'),"webservice_name"=>"normalUserLogin");
		}
		echo json_encode($response);exit;
	}	

	/**
	 * Description:This is check minimum order amount of the rastaurant based on locality
	 * @author: Manisha Kanazariya
	 * @CreatedDate:2018-07-23
	 */
	function minimumOrderAmount(){
		$locality =$this->input->post('locality');
		$getMiniOredr=$this->Home_model->minimumOrderAmount($locality);
		if(count($getMiniOredr)>0){
			$response['success'] =1;
			$response['message'] =$getMiniOredr;
			$response['error'] =$this->lang->line('message_miniOrderAmount');
		}else{
			$response['success'] =0;
		}
		echo json_encode($response);exit;
	}
	/**
	 * Description:this is used to show restaurant details
	 * @author: Manisha Kanazariya
	 * @CreatedDate:2018-08-13T18:38:52+0530
	 */
	function restaurantInfo(){

		if(isset($_COOKIE['access_token']) && $_COOKIE['access_token']!=''){
			if(isset($_COOKIE['user_id'])){
				$data['userdata'] = $this->Webservice_customer_model->getUserdetails($_COOKIE['user_id']);
				$data['addressDetail'] =  $this->Webservice_customer_model->getDeliveryAddress($_COOKIE['user_id']);
			}
			else{
				$data['userdata'] = array();
				$data['addressDetail'] = array();
			}
		}
		$data['days']       =$this->config->item('days');
		$resInfo =$this->Home_model->getRestaurantDetail($_COOKIE['restaurant_id']);
		
		$restaurantTime = $this->Webservice_customer_model->getRestaurantTime($resInfo[0]->restaurant_id);
		$resData =array();

		foreach ($restaurantTime as $k1 => $v1) {
			
			if($v1->is_approved == 1 ){
			 	$resData[$v1->day][$k1]['from_time']=date('h:i A',strtotime($v1->from_time));
			 	$resData[$v1->day][$k1]['to_time']=date('h:i A',strtotime($v1->to_time));
			 }
		 	
		 }
		$data['resData'] =$resData;
		$data['resInfo'] =$resInfo;
		$this->load->view('Elements/Frontend-header',$data);
		$this->load->view('Home/hotel_info');
		$this->load->view('Elements/modals');
		$this->load->view('Elements/Frontend-footer');
	}
	/**
	 * Description:used to set data for repeat order
	 * @author: Manisha Kanazariya
	 * @CreatedDate:2018-08-27 T15:04:20+0530
	 */
	public function getRepeatOrder($oId){
		
		$oData =$this->Home_model->getRepeatOrder($oId);
	
		$cData =array();
		$id    =0;
		foreach ($oData as $key => $value)
		 {

		 	if($id != $value->order_detail_id)
		 	{
		 		$id                          =$value->order_detail_id;
				$cData[$id]['id']            =$key+1;
				$cData[$id]['dishId']        =$value->product_id;
				$cData[$id]['dishcount']     =$value->dishcount;
				$cData[$id]['choiceOfOne']   =array();
				$cData[$id]['Multiplechoice']=array();
				$cData[$id]['res_id']        =$value->locality_id;
				$cData[$id]['instruction']   =$value->description;

				if($value->is_multiple ==0)
				{
					$cData[$id]['choiceOfOne'][]=$value->choice_id;
				}
				else
				{
					$cData[$id]['Multiplechoice'][]=$value->choice_id;
				}
		 	}
		 	else
		 	{
		 		if($value->is_multiple ==0)
				{
					$cData[$id]['choiceOfOne'][]=$value->choice_id;
				}
				else
				{
					$cData[$id]['Multiplechoice'][]=$value->choice_id;
				}
		 	}
			
			$response['locality_id']   =$value->locality_id;
			$response['locality_name'] = ($this->langs =="AR")?$value->name_ar:$value->name;
		}

		$cData =array_values($cData);
		$response['oData'] =json_encode($cData);

		echo json_encode($response);exit;

	}

	function driver_demo(){

		$this->load->view('Home/driver_demo');
	}
}
