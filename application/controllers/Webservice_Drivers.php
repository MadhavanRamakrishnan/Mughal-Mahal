<?php
/** 
 * Author  				: itoneclick.com 
 * Copyright 			: itoneclick.com 
 * Created by 			: Vaibhav Mehta 
 * Created Date 		: 23 October 2017 11:30 AM 
 * Description 			: Use for various webservice like login,logout,forgot password,etc.
 */ 

error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');

class Webservice_Drivers extends MY_Controller{
	/**
	 * Controller Name 	: Webservice
	 * Description 		: Load basic needs like model and functions
	 * Created by 		: Vaibhav Mehta
	 * Created Date 	: 23 August 2017 6:30 PM 
	*/ 
	function __construct(){
		parent::__construct();
		$this->load->model(array('Webservice_driver_model', 'Home_model'));
		$this->default_language = $this->config->item('default_language');
		$this->supported_language = $this->config->item('supported_languages');
		$this->OrderStatus    = $this->config->item('OrderStatus');
	}


	/**
	 * Description : User login functionality
	 * Input : Valid email or phone no and password
	 * Output : user succesfully logged in 
	 * Created by : Vaibhav Mehta
	 * Created Date: 24/10/17 01:00 PM 
	*/ 
	
	function normalUserLogin()
	{
		$startTime 		= microtime(true);
		$lange 			= trim($this->input->post("default_language"));
		if(in_array($lange,$this->supported_language))
		{
			$lang 		= $lange;
			$langFile 	= $lange.'_lang';
		}
		else
		{
			$lang 		= $this->default_language;
			$langFile 	= $lang.'_lang';
		}

		$this->lang->load($langFile,$lang);

		if(trim($this->input->post('email'))=="")
		{
			$response=array("response"=>"false","message"=>$this->lang->line('email_required'));
		}
		else if(trim($this->input->post('password'))=="")
		{
			$response=array("response"=>"false","message"=>$this->lang->line('password_required'));	
		}
		else
		{
			$email 		= $this->input->post('email');
			$password 	= trim($this->input->post('password'));

			if(is_numeric($email))
			{
				$email 	= trim($email);
				$type 	= 1;
			}
			else if (filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				$email 	= trim($email);
				$type 	= 2;
			}
			else
			{
				$error 	= $this->lang->line('invalid_login');
			}

			if($error)
			{
				$response = array("response"=>"false","webservice_name"=>"normalUserLogin","message"=>$error);
			}
			else
			{
				$role 	= $this->config->item('driver_role');
				$data 	= $this->Webservice_driver_model->normalUserLogin($email,$password,$type,$role);
				if(is_array($data) && sizeof($data)>0)
				{
					$userId 						= $data[0]->user_id;
					$accessToken 					= md5(date("Y-m-d H:i:s"));
					$userMetaData['access_token'] 	= $accessToken;
					$userMetaData['device_type'] 	= ($this->input->post('device_type')) ? trim($this->input->post('device_type')) : '';
					$userMetaData['device_token'] 	= ($this->input->post('device_token')) ? trim($this->input->post('device_token')) : '';
					$userMetaData['user_id'] 		= $userId;
					$tableName 						= 'tbl_user_meta';
					$metaId = $this->Webservice_driver_model->insertData($tableName,$userMetaData);					

					$userDetails['user_id'] 		= $userId;
					$userDetails['first_name'] 		= $data[0]->first_name;
					$userDetails['last_name'] 		= $data[0]->last_name;
					$userDetails['email'] 			= $data[0]->email;
					$userDetails['phone_no'] 		= $data[0]->phone_no;
					$userDetails['role_id'] 		= $data[0]->role_id;
					$userDetails['access_token'] 	= $accessToken;

					$userDetails = $this->Webservice_driver_model->getUserdetails($userId);
					if(isset($userId) && $userId !=''){

						$userDetails[0]->restaurantData =$this->Webservice_driver_model->getAllRestaurantDetails($data[0]->fk_restaurant_id);
					}
					if(is_array($userDetails) && count($userDetails)>0)
					{
						$userDetails[0]->profile_photo = base_url().'assets/uploads/users/drivers/'.$userDetails[0]->profile_photo;
					}
					
					$response = array("response"=>"true","data"=>$userDetails,"message"=>$this->lang->line('message_normalUserLogin'),"webservice_name"=>"normalUserLogin");
				}
				else
				{
					$response=array("response"=>"false","message"=>$this->lang->line('invalid_login'),"webservice_name"=>"normalUserLogin");
				}
			}			
		}
		$this->evaluateExecutionTime($startTime,'normalUserLogin');
		echo json_encode($response);
		exit;
	}

	/**
	 * Controller Name: Webservice
	 * Description : User logout functionality
	 * Input : Click on logout
	 * Output : user succesfully logged out
	 * Created by : Vaibhav Mehta
	 * Created Date: 24/10/17 04:20 PM 
	*/ 
	function driverLogout()
	{
		$startTime 		= microtime(true);
		$lange 			= trim($this->input->post("default_language"));
		if(in_array($lange,$this->supported_language))
		{
			$lang 		= $lange;
			$langFile 	= $lange.'_lang';
		}
		else
		{
			$lang 		= $this->default_language;
			$langFile 	= $lang.'_lang';
		}

		$this->lang->load($langFile,$lang);
		$message_driverLogout 		= $this->lang->line('message_driverLogout');
		$userid_required 			= $this->lang->line('userid_required');
		$default_language_required 	= $this->lang->line('default_language_required');

		if(trim($this->input->post("user_id"))=="")
		{
			$response=array("response"=>"false","message"=>$userid_required);
		}
		else if(trim($this->input->post('default_language'))==""){
			$response = array("response"=>"false","message"=>$default_language_required);
		}
		else
		{
			$userId 		= trim($this->input->post("user_id"));
			$deviceToken 	= ($this->input->post("device_token")) ? trim($this->input->post("device_token")) : '';
			$deviceType 	= ($this->input->post("device_type")) ? trim($this->input->post("device_type")) : '';

			$res = $this->Webservice_driver_model->deleteUserMetaData($userId,$deviceToken);
			$response = array("response"=>"true","message"=>$message_driverLogout,"webservice_name"=>"driverLogout");	
		}
		$this->evaluateExecutionTime($startTime,'driverLogout');
		echo json_encode($response);
		exit;
	}

	/**
	 * Description 		: If user forgot password and want to change password, send email
	 * Input 			: Click on forgot password and enter new password
	 * Output 			: user login credentials has been changed
	 * Created by 		: Vaibhav Mehta
	 * Created Date 	: 24/10/2017 5:00 PM
	*/ 
	function forgotPassword()
	{
		$startTime = microtime(true);
		$lange 		= trim($this->input->post("default_language"));
		if(in_array($lange,$this->supported_language))
		{
			$lang 		= $lange;
			$langFile 	= $lange.'_lang';
		}
		else
		{
			$lang 		= $this->default_language;
			$langFile 	= $lang.'_lang';
		}

		$this->lang->load($langFile,$lang);
		$message_forgotPassword 	= $this->lang->line('message_forgotPassword');
		$email_required 			= $this->lang->line('email_required');
		$default_language_required 	= $this->lang->line('default_language_required');
		$error_email_send 			= $this->lang->line('error_email_send');
		$error_login_via_facebook 	= $this->lang->line('error_login_via_facebook');
		$error_login_via_gmail 		= $this->lang->line('error_login_via_gmail');
		$error_login_via_login 		= $this->lang->line('error_login_via_login');
		$error_email_format 		= $this->lang->line('error_email_format');

		if(trim($this->input->post("email"))==""){
			$response=array("response"=>"false","message"=>$email_required);
		}
		else if(trim($this->input->post('default_language'))==""){
			$response = array("response"=>"false","message"=>$default_language_required);
		}
		else{
			$email 	= trim($this->input->post('email'));
			
			$res 	= $this->Webservice_driver_model->checkEmailExist($email);
			if(is_array($res) && count($res)>0){
				if($res[0]->platform == "0"){

					$mail 							= $this->base64url_encode($email);
					$passwordResetLink 				= site_url("login/resetPassword/".$mail."/4");
					$data['passwordResetLink']		= $passwordResetLink;
					$data['email_template']			= 'password_forgot';
					$data['to_email']				= $email;
					$data['user_name']				= $res[0]->user_name;
					$data['subject']				= 'Reset Password';
					$message 						= "Click On below link to reset your password<br>".$passwordResetLink;

					$mails = $this->sendMail($data);
					if($mails['success'] == "1"){
						$userUpdate = array(
							"updated_date" 		=> date("Y-m-d H:i:s"),
							"security_token" 	=> $mail,
							"security_token_expiry_date" => date("Y-m-d H:i:s",strtotime('+1 hour'))
							);
						$dataSuccess = $this->Webservice_driver_model->updateUserProfile($res[0]->user_id,$userUpdate);
						$response = array("response"=>"true","message"=>$message_forgotPassword,"webservice_name"=>"forgotPassword");
					}
					else{
						$response = array("response"=>"false","message"=>$error_email_send,"webservice_name"=>"forgotPassword");
					}
				}
				else{
					if($res[0]->register_via == "1"){
						$response = array("response"=>"false","message"=>$error_login_via_facebook,"webservice_name"=>"forgotPassword");
					}
					else if($res[0]->register_via == "2"){
						$response = array("response"=>"false","message"=>$error_login_via_gmail,"webservice_name"=>"forgotPassword");
					}
					else{
						$response = array("response"=>"false","message"=>$error_login_via_login,"webservice_name"=>"forgotPassword");
					}
				}
			}
			else{
				$response = array("response"=>"false","message"=>$error_email_format,"webservice_name"=>"forgotPassword");
			}
		}
		$this->evaluateExecutionTime($startTime,'forgotPassword');
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : Get orders details allocated to driver
	 * Created by :Rashmi Nayani
	 * Created Date: 30/10/17 12:20 PM 
	*/
	function getOrdersAllocatedtoDriver()
	{
		$startTime 		= microtime(true);
		$lange 			= trim($this->input->post("default_language"));
		if(in_array($lange,$this->supported_language))
		{
			$lang 		= $lange;
			$langFile 	= $lange.'_lang';
		}
		else
		{
			$lang 		= $this->default_language;
			$langFile 	= $lang.'_lang';
		}

		$this->lang->load($langFile,$lang);
		$message_getOrdersAllocatedtoDriver = $this->lang->line('message_getOrdersAllocatedtoDriver');
		$account_deleted 			= $this->lang->line('account_deleted');
		$token_mismatched 			= $this->lang->line('token_mismatched');
		$filed_to_fetch_order 		= $this->lang->line('filed_to_fetch_order');
		$role_required 				= $this->lang->line('role_required');
		$token_required 			= $this->lang->line('token_required');
		$userid_required 			= $this->lang->line('userid_required');
		$default_language_required 	= $this->lang->line('default_language_required');
		$filed_to_fetch_driver_order= $this->lang->line('filed_to_fetch_driver_order');

		if(trim($this->input->post('user_id'))==""){

			$response = array("response"=>"false","message"=>$userid_required);
		}
		else if(trim($this->input->post('access_token'))==""){

			$response = array("response"=>"false","message"=>$token_required);
		}
		else if(trim($this->input->post('default_language'))==""){
			$response = array("response"=>"false","message"=>$default_language_required);
		}
		else{

			$userId 		= trim($this->input->post('user_id'));
			$accessToken 	= trim($this->input->post("access_token"));
			$token 			= $this->checkAccessToken($userId,$accessToken);

			if($accessToken===$token){
				$account = $this->Webservice_driver_model->chkAccountDelete($userId);
				if(is_array($account) && count($account)>0)
				{
					$response = array("response"=>"false","message"=>$account_deleted,"account_status"=>"inactive","webservice_name"=>"getOrdersAllocatedtoDriver");
				}
				else
				{
					$orderDetail = $this->Webservice_driver_model->getOrdersAllocatedtoDriver($userId);
					
					if(is_array($orderDetail) && count($orderDetail)>0){
						$k = 0;
						foreach ($orderDetail as $key => $value) {
							
							$orderDetails[$value->order_id]['order_id'] 				= $value->sequence_no;
							$orderDetails[$value->order_id]['is_replaced'] 				= ($value->order_refer_by !=0)?"1":"0";
							$orderDetails[$value->order_id]['replace_ord'] 				= $value->order_refer_by;
							$orderDetails[$value->order_id]['order_status'] 			= $value->order_status;
							$orderDetails[$value->order_id]['reason'] 			        = $value->reason;
							$orderDetails[$value->order_id]['order_placed_time'] 		= $value->order_placed_time;
							$orderDetails[$value->order_id]['expected_delivery_time'] 	= $value->expected_delivery_time;
							$orderDetails[$value->order_id]['order_confirmed_time'] 	= $value->order_confirmed_time;
							$orderDetails[$value->order_id]['total_price'] 				= $value->total_price;
							$orderDetails[$value->order_id]['payment_type'] 			= $value->order_type;
							$orderDetails[$value->order_id]['restaurant_name'] 			= $value->restaurant_name;
							$orderDetails[$value->order_id]['restaurant_address'] 		= $value->address;
							$orderDetails[$value->order_id]['restaurant_contact_no'] 	= $value->res_contact_no;
							$orderDetails[$value->order_id]['driver_first_name'] 		= $value->d_first_name;
							$orderDetails[$value->order_id]['driver_last_name'] 		= $value->d_last_name;
							$orderDetails[$value->order_id]['driver_contact_number'] 	= $value->d_contact_no;
							$orderDetails[$value->order_id]['customer_latitude'] 		= $value->customer_latitude;
							$orderDetails[$value->order_id]['customer_longitude'] 		= $value->customer_longitude;

						    $orderDetails[$value->order_id]['dishes'][$value->product_id][$k]['product_id']  = $value->product_id;
						    $orderDetails[$value->order_id]['dishes'][$value->product_id][$k]['quantity']  = $value->quantity;
						    $orderDetails[$value->order_id]['dishes'][$value->product_id][$k]['amount']  = $value->amount;
						    $orderDetails[$value->order_id]['dishes'][$value->product_id][$k]['discount_type']  = $value->discount_type;
						    $orderDetails[$value->order_id]['dishes'][$value->product_id][$k]['discount_amount']  = $value->discount_amount;
						    $orderDetails[$value->order_id]['dishes'][$value->product_id][$k]['name']  = $value->name;
						    $orderDetails[$value->order_id]['dishes'][$value->product_id][$k]['product_ar_name']  = $value->product_ar_name;
						    $orderDetails[$value->order_id]['dishes'][$value->product_id][$k]['description']  = $value->description;
						    $orderDetails[$value->order_id]['dishes'][$value->product_id][$k]['price']  = $value->price;
						    $orderDetails[$value->order_id]['dishes'][$value->product_id][$k]['dish_image']  = base_url().'assets/uploads/products/'.$value->dish_image;

						    $orderDetails[$value->order_id]['user_details'][$value->user_id]['first_name']  = $value->customer_name;
						    $orderDetails[$value->order_id]['user_details'][$value->user_id]['last_name']  ="";
						    $orderDetails[$value->order_id]['user_details'][$value->user_id]['contact_no']  = $value->contact_no;
						    $orderDetails[$value->order_id]['user_details'][$value->user_id]['email']  = $value->email;

						    $orderDetails[$value->order_id]['restaurant']['restaurant_name']  = $value->restaurant_name;
						    $orderDetails[$value->order_id]['restaurant']['address']= $value->address;
						    $orderDetails[$value->order_id]['restaurant']['contact_no']  = $value->res_contact_no;
						    $orderDetails[$value->order_id]['delivery_address'][$value->address_id]['address_id']  		= $value->address_id;
						    $orderDetails[$value->order_id]['delivery_address'][$value->address_id]['address2']  		= $value->address2;
						    $orderDetails[$value->order_id]['delivery_address'][$value->address_id]['customer_name'] 	= $value->customer_name;
						    $orderDetails[$value->order_id]['delivery_address'][$value->address_id]['email']  			= $value->del_email;
						    $orderDetails[$value->order_id]['delivery_address'][$value->address_id]['delivery_contact'] = $value->delivery_contact;
						    $orderDetails[$value->order_id]['delivery_address'][$value->address_id]['zipcode']  		= $value->zipcode;

						   $fullAddress =($value->appartment_no !="" || $value->appartment_no !=null)?$value->appartment_no.",":"";
							$fullAddress .=($value->floor!="" || $value->floor!=null)?"Floor -".$value->floor.",":"";
							$fullAddress .=($value->block!="" || $value->block!=null)?"Block -".$value->block.",":"";
							$fullAddress .=($value->building !="" ||$value->building !=null)?"Building -".$value->building.",":"";
							$fullAddress .=($value->street !="" ||$value->street !=null )?$value->street.',':'';
							$fullAddress .=($value->avenue !="" || $value->avenue !=null)?$value->avenue.',':'';
							$fullAddress .=($value->address1!="")?$value->address1:""; 

						    $orderDetails[$value->order_id]['delivery_address'][$value->address_id]['address1'] 		= $fullAddress;

						    $k++;
						}
						
						$i = 0;
						$k = 0;

						foreach ($orderDetails as $key => $value) {
							
							$orderData[$i]['order_id'] 					= $value['order_id'];
							$orderData[$i]['is_replaced'] 				= $value['is_replaced'];
							$orderData[$i]['replace_ord'] 				= $value['replace_ord'];
							$orderData[$i]['order_status'] 				= $value['order_status'];
							$orderData[$i]['reason'] 				    = $value['reason'];
							$orderData[$i]['order_placed_time'] 		= $value['order_placed_time'];
							$orderData[$i]['expected_delivery_time'] 	= $value['expected_delivery_time'];
							$orderData[$i]['current_time'] 	            = date('Y-m-d H:i:s');
							$orderData[$i]['order_confirmed_time']  	= $value['order_confirmed_time'];
							$orderData[$i]['total_price'] 				= $value['total_price'];
							$orderData[$i]['payment_type'] 				= $value['payment_type'];
							$orderData[$i]['restaurant_name'] 			= $value['restaurant_name'];
							$orderData[$i]['restaurant_address'] 		= $value['restaurant_address'];
							$orderData[$i]['driver_first_name'] 		= $value['driver_first_name'];
							$orderData[$i]['driver_last_name'] 			= $value['driver_last_name'];
							$orderData[$i]['driver_contact_number'] 	= $value['driver_contact_number'];
							$orderData[$i]['customer_latitude'] 		= $value['customer_latitude'];
							$orderData[$i]['customer_longitude'] 		= $value['customer_longitude'];
						
							$j =0;
							foreach ($value['dishes'] as $key => $val) {

								for($kkey = 0; $kkey < sizeof($val); $kkey++){
									$orderData[$i]['dishes'][$j]['product_id'] 		= $val[$k]['product_id'];
									$orderData[$i]['dishes'][$j]['quantity'] 			= $val[$k]['quantity'];
									$orderData[$i]['dishes'][$j]['amount'] 			= $val[$k]['amount'];
									$orderData[$i]['dishes'][$j]['discount_type'] 	= $val[$k]['discount_type'];
									$orderData[$i]['dishes'][$j]['discount_amount'] 	= $val[$k]['discount_amount'];
									$orderData[$i]['dishes'][$j]['name'] 				= $val[$k]['name'];
									$orderData[$i]['dishes'][$j]['product_ar_name'] 				= $val[$k]['product_ar_name'];
									$orderData[$i]['dishes'][$j]['description'] 		= $val[$k]['description'];
									$orderData[$i]['dishes'][$j]['price'] 			= $val[$k]['price'];
									$orderData[$i]['dishes'][$j]['dish_image'] 		= base_url().'assets/uploads/products/'.$val[$k]['name'];
									$k++;
									$j++;
								}
							}

							foreach ($value['user_details'] as $key => $val) {

								$orderData[$i]['user_details']['first_name'] 		= $val['first_name'];
								$orderData[$i]['user_details']['last_name'] 		= $val['last_name'];
								$orderData[$i]['user_details']['contact_no'] 		= $val['contact_no'];
								$orderData[$i]['user_details']['email'] 			= $val['email'];
							}
							foreach ($value['restaurant'] as $key => $val) {

								$orderData[$i]['restaurant'][$key] = $val;
	                        }

							foreach ($value['delivery_address'] as $key => $val) {

								$orderData[$i]['delivery_address']['address_id'] 		= $val['address_id'];
								$orderData[$i]['delivery_address']['address1'] 			= $val['address1'];
								$orderData[$i]['delivery_address']['address2'] 			= $val['address2'];
								$orderData[$i]['delivery_address']['customer_name'] 	= $val['customer_name'];
								$orderData[$i]['delivery_address']['email'] 			= $val['email'];
								$orderData[$i]['delivery_address']['delivery_contact'] 	= $val['delivery_contact'];
								$orderData[$i]['delivery_address']['zipcode'] 			= $val['zipcode'];
								$orderData[$i]['delivery_address']['country'] 			= $val['country'];
								$orderData[$i]['delivery_address']['state'] 			= $val['state'];
								$orderData[$i]['delivery_address']['city'] 				= $val['city'];
							}
							$i++;
						}
						
						$statusAll = $this->OrderStatus;
						for($status=0; $status<=9;$status++)
						{
							$m=0;
							foreach($orderData as $Dkey => $Dvalue) {

								if($Dvalue['order_status']==$status)
								{
									//$oData['status'] = $status;
									$oData[$statusAll[$status]][$m] = $Dvalue;
									$m++;
								}
							}

							if(sizeof($oData[$statusAll[$status]])==0){
								$oData[$statusAll[$status]] = array();
							}
						}

						$response = array("response"=>"true","webservice_name"=>"getOrdersAllocatedtoDriver","message"=>$message_getOrdersAllocatedtoDriver,"orders"=>$oData);
					
					}
					else
					{
						$response = array("response"=>"false","webservice_name"=>"getOrdersAllocatedtoDriver","message"=>$filed_to_fetch_driver_order);
					}
				}
			}
			else
			{
				$response = array("response"=>"false","access"=>"false","message"=>$token_mismatched,"webservice_name"=>"getOrdersAllocatedtoDriver");
			}
		}
		$this->evaluateExecutionTime($startTime,'getOrdersAllocatedtoDriver');
		echo json_encode($response);
		exit;
	}

		/**
	 * Controller Name: Webservice
	 * Description : Check accesstoken for authorize user
	 * Input : Add access token
	 * Output : User authentication
	 * Created by : Vaibhav Mehta
	 * Created Date: 18/05/17 06:30 PM 
	*/
	function checkAccessToken($userId,$accessToken)
	{
		$userData = $this->Webservice_driver_model->getUserMetaData($userId,$accessToken);
		if(is_array($userData) && sizeof($userData)==1)
		{
			return $userData[0]->access_token;
		}
		else
		{
			return 0;
		}
	}

	/**
	 * Description : update driver profile
	 * Created by : Rashmi Nayani
	 * Created Date: 03/11/17 12:00 PM 
	*/
	function updateDriverProfile()
	{
		$startTime = microtime(true);
		$lange 			= trim($this->input->post("default_language"));
		if(in_array($lange,$this->supported_language))
		{
			$lang 		= $lange;
			$langFile 	= $lange.'_lang';
		}
		else
		{
			$lang 		= $this->default_language;
			$langFile 	= $lang.'_lang';
		}

		$this->lang->load($langFile,$lang);
		$message_updateProfile 		= $this->lang->line('message_updateProfile');
		$userid_required 			= $this->lang->line('userid_required');
		$token_required 			= $this->lang->line('token_required');
		$role_required 				= $this->lang->line('role_required');
		$first_name_required 		= $this->lang->line('first_name_required');
		$default_language_required 	= $this->lang->line('default_language_required');
		$delivery_contact_no_required 	= $this->lang->line('delivery_contact_no_required');
		$gender_required 			= $this->lang->line('gender_required');
		$account_deleted 			= $this->lang->line('account_deleted');
		$error_dob 					= $this->lang->line('error_dob');
		$no_updates 				= $this->lang->line('no_updates');
		$token_mismatched 			= $this->lang->line('token_mismatched');
		$phone_required 			= $this->lang->line('phone_required');

		if(trim($this->input->post('user_id'))=="")
		{
			$response = array("response"=>"false","message"=>$userid_required);
		}
		else if(trim($this->input->post('access_token'))=="")
		{
			$response = array("response"=>"false","message"=>$token_required);
		}
		else if(trim($this->input->post('first_name'))=="")
		{
			$response = array("response"=>"false","message"=>$first_name_required);
		}
		else if(trim($this->input->post('contact_no'))=="")
		{
			$response = array("response"=>"false","message"=>$phone_required);
		}
		else if(trim($this->input->post('gender'))=="")
		{
			$response = array("response"=>"false","message"=>$gender_required);
		}
		else if(trim($this->input->post('default_language'))==""){
			$response = array("response"=>"false","message"=>$default_language_required);
		}
		else
		{
			$userId 		= trim($this->input->post('user_id'));
			$accessToken 	= trim($this->input->post("access_token"));
			$firstname 		= trim($this->input->post("first_name"));
			$lastname 		= trim($this->input->post("last_name"));
			$contactNo  	= trim($this->input->post("contact_no"));
			$gender 		= trim($this->input->post("gender"));
			$dob 			= trim($this->input->post("birth_date"));
		
			$token 			= $this->checkAccessToken($userId,$accessToken);
			if($accessToken===$token)
			{

				$account = $this->Webservice_driver_model->chkAccountDelete($userId);
				if(is_array($account) && count($account)>0)
				{
					$response = array("response"=>"false","message"=>$account_deleted,"account_status"=>"inactive","webservice_name"=>"updateDriverProfile");
				}
				else
				{
					$userData['first_name']		= trim($this->input->post('first_name'));
					$userData['last_name']		= trim($this->input->post('last_name'));
					$userData['gender']			= trim($this->input->post('gender'));
					$userData['contact_no'] 	= trim($this->input->post('contact_no'));
					$userData['updated_by'] 	= $userId;
					$userData['updated_date'] 	= date('Y-m-d H:i:s');

					if($this->input->post('birth_date')!='')
					{	
							$userData['dob']	= $this->input->post('birth_date');
					}
					if($this->input->post('city')!='')
					{
						$userData['city']	= (trim($this->input->post('city'))) ? trim($this->input->post('city')) : '';
					}
					if($this->input->post('state')!='')
					{
						$userData['state']	= (trim($this->input->post('state'))) ? trim($this->input->post('state')) : '';
					}
					if($this->input->post('country')!='')
					{
						$userData['country']	= (trim($this->input->post('country'))) ? trim($this->input->post('country')) : '';
					}
					if($this->input->post('country_code')!='')
					{
						$userData['country_code']	= (trim($this->input->post('country_code'))) ? trim($this->input->post('country_code')) : '';
					}
					if(isset($error) && sizeof($error)>0 )
					{
					
						$errors = isset($error) ? $error : '';
						$response = array("response"=>"false","message"=>$errors,"account_status"=>"active","webservice_name"=>"updateDriverProfile");
					}
					else
					{
						if( isset($_FILES['profile_photo']) && $_FILES['profile_photo']['name']!='' )
						{
							$config['upload_path']   = './assets/uploads/users/drivers/';
							$config['allowed_types'] = 'gif|jpg|png|jpeg|JPG|JPEG|PNG';
							$config['max_size']      = 2048; 
							$config['max_width']     = 6000; 
							$config['max_height']    = 6000;
							$config["encrypt_name"]  = true;  

							$this->load->library('upload', $config);

							if ( ! $this->upload->do_upload('profile_photo')) 
							{
								$error 		= strip_tags($this->upload->display_errors());
							}
							else
							{ 
								$fileData = array('profile_photo' => $this->upload->data());
								$userData['profile_photo'] = $fileData['profile_photo']['file_name'];
							}
						}

						if( isset($error) && sizeof($error)>0 )
						{
							$errors = isset($error) ? $error : '';
							$response = array("response"=>"false","message"=>$errors,"account_status"=>"active","webservice_name"=>"updateDriverProfile");
						}
						else
						{
							$resp = $this->Webservice_driver_model->updateDriverProfile($userId,$userData);
							if(isset($_FILES) && count($_FILES)>0)
							{
								if($role==$this->config->item('super_admin_role'))
								{
									$userData['profile_photo'] = base_url().'assets/uploads/users/'.$userData['profile_photo'];
								}
								else if($role==$this->config->item('restaurant_owner_role'))
								{
									$userData['profile_photo'] = base_url().'assets/uploads/restaurants/'.$userData['profile_photo'];
								}
								else if($role==$this->config->item('restaurant_manager_role'))
								{
									$userData['profile_photo'] = base_url().'assets/uploads/restaurants/'.$userData['profile_photo'];
								}
								else if($role==$this->config->item('driver_role'))
								{
									$userData['profile_photo'] = base_url().'assets/uploads/users/drivers/'.$userData['profile_photo'];
								}
								else if($role==$this->config->item('customer_role'))
								{
									$userData['profile_photo'] = base_url().'assets/uploads/users/customers/'.$userData['profile_photo'];
								}
							}

							if($resp)
							{
								$respo = $this->Webservice_driver_model->getUserdetails($userId);

								if(is_array($respo) && count($respo)>0)
								{
									foreach ($respo as $key => $value)
									{
										if($value->profile_photo)
										{
											$respo[$key]->profile_photo = base_url().'assets/uploads/users/drivers/'.$value->profile_photo;
										}
									}
								}
							
								$response = array("response"=>"true","message"=>$message_updateProfile,"data"=>$respo,"account_status"=>"active","webservice_name"=>"updateDriverProfile");
							}
							else
							{
								$response = array("response"=>"false","message"=>$no_updates,"account_status"=>"active","webservice_name"=>"updateDriverProfile");
							}
						}
					}
					
				}
			}
			else
			{
				$response = array("response"=>"false","access"=>"false","message"=>$token_mismatched);
			}
		}
		$this->evaluateExecutionTime($startTime,'updateDriverProfile');
		echo json_encode($response);
		exit;
	}


	/**
	 * Description : change order status
	 * Created by : Rashmi Nayani
	 * Created Date: 3/11/17 01:30 PM 
	*/ 
	function changeOrderStatus(){
		$startTime 		= microtime(true);
		$lange 			= trim($this->input->post("default_language"));
		if(in_array($lange,$this->supported_language))
		{
			$lang 		= $lange;
			$langFile 	= $lange.'_lang';
		}
		else
		{
			$lang 		= $this->default_language;
			$langFile 	= $lang.'_lang';
		}

		$this->lang->load($langFile,$lang);
		$message_changeOrderStatus 	= $this->lang->line('message_changeOrderStatus');
		$default_language_required 	= $this->lang->line('default_language_required');
		$token_mismatched 			= $this->lang->line('token_mismatched');
		$userid_required 			= $this->lang->line('userid_required');
		$token_required 			= $this->lang->line('token_required');
		$order_id_required 			= $this->lang->line('order_id_required');
		$account_deleted 			= $this->lang->line('account_deleted');
		$cant_change_order 			= $this->lang->line('cant_change_order');
		$fail_to_change_order_status= $this->lang->line('fail_to_change_order_status');
		$order_pending 				= $this->lang->line('order_pending'); 
		$order_placed 				= $this->lang->line('order_placed'); 
		$order_confirmed 			= $this->lang->line('order_confirmed'); 
		$order_cooking 				= $this->lang->line('order_cooking'); 
		$order_collected 			= $this->lang->line('order_collected'); 
		$order_ontheway 			= $this->lang->line('order_ontheway'); 
		$order_neartoyou 			= $this->lang->line('order_neartoyou'); 
		$order_delivered 			= $this->lang->line('order_delivered'); 
		$order_dispute 				= $this->lang->line('order_dispute'); 

		if(trim($this->input->post('user_id'))==""){

			$response = array("response"=>"false","message"=>$userid_required);
		}
		else if(trim($this->input->post('access_token'))==""){

			$response = array("response"=>"false","message"=>$token_required);
		}
		else if(trim($this->input->post('default_language'))==""){
			$response = array("response"=>"false","message"=>$default_language_required);
		}
		else{

			$userId 		= trim($this->input->post('user_id'));
			$accessToken 	= trim($this->input->post("access_token"));
			$orderId 		= trim($this->input->post("order_id"));
			$orderStatus 	= trim($this->input->post("order_status"));
			
			$order_id 		= $this->Home_model->getOrderIdFromSequenceNo($orderId);
			$orderId 		= $order_id->orderId;

			$token 			= $this->checkAccessToken($userId,$accessToken);
	
			if($accessToken===$token){
				$account = $this->Webservice_driver_model->chkAccountDelete($userId);
				if(is_array($account) && count($account)>0){

					$response = array("response"=>"false","message"=>$account_deleted,"account_status"=>"inactive","webservice_name"=>"changeOrderStatus");
				}
				else{

					$res = $this->Webservice_driver_model->getOrderDetails($orderId);
					//echo "<pre>"; print_r($res); exit;
					
					if ($res[0]->order_status >= 4) {

						if ($res[0]->order_status >= $orderStatus) {

							if ($res[0]->order_status == 0) {
								$error = $order_pending;
							}
							if ($res[0]->order_status == 1) {
								$error = $order_placed;
							}
							elseif ($res[0]->order_status == 2) {
								$error = $order_confirmed;
							}
							elseif ($res[0]->order_status == 3) {
								$error = $order_cooking;
							}
							elseif ($res[0]->order_status == 4) {
								$error = $order_collected;
							}
							elseif ($res[0]->order_status == 5) {
								$error = $order_ontheway;
							}
							elseif ($res[0]->order_status == 6) {
								$error = $order_neartoyou;
							}
							elseif ($res[0]->order_status == 7) {
								$error = $order_delivered;
							}
							elseif ($res[0]->order_status == 8) {
								$error = $order_dispute;
							}
							
							$response = array("response"=>"false","message"=>$error,"account_status"=>"inactive","webservice_name"=>"changeOrderStatus");
						}else{
							
							
							$statusData['order_status'] 	= $orderStatus;
							$statusData['updated_by'] 		= $userId;
							$statusData['updated_date'] 	= date("Y-m-d H:i:s");
							if($orderStatus == 7)
							{
								$statusData['delivered_time'] =date('Y-m-d H:i:s');
							}
							$res1 = $this->Webservice_driver_model->updateOrderStatus($statusData,$orderId);

							if(sizeof($res1) >0){
								$response=array("response"=>"true","data"=>$res,"message"=>$message_changeOrderStatus,"webservice_name"=>"changeOrderStatus");
								if($orderStatus==7)
								{
									$orderDetail = $this->Webservice_driver_model->getOrderDetailsForDeliveryEmail($orderId);

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
											$orderDetails[$value->order_id]['block'] 					= $value->block;
											$orderDetails[$value->order_id]['street'] 					= $value->street;
											$orderDetails[$value->order_id]['avenue'] 					= $value->avenue;
											$orderDetails[$value->order_id]['building'] 				= $value->building;
											$orderDetails[$value->order_id]['floor'] 					= $value->floor;
											$orderDetails[$value->order_id]['appartment_no'] 			= $value->appartment_no;
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

										$address = $dishArray[0]['block']?', block-'.$dishArray[0]['block']:'';
										$address .= $dishArray[0]['street']?', '.$dishArray[0]['street'].', ':'';
										$address .= $dishArray[0]['avenue']?', '.$dishArray[0]['avenue'].', ':'';
										$address .= $dishArray[0]['building']?', building- '.$dishArray[0]['building']:'';
										$address .= $dishArray[0]['floor']?', floor- '.$dishArray[0]['floor']:'';
										$address .= $dishArray[0]['appartment_no']?$dishArray[0]['appartment_no']:'';

										if(strcmp($dishArray[0]['customer_email'],$dishArray[0]['user_email'])==0)
										{
											if($dishArray[0]['customer_email'] || $dishArray[0]['user_email'])
											{
												$dishArray[0]['sequence_no'] = $dishArray[0]['sequence_no'];
												$dishArray[0]['order_id'] = $orderId;
												$dishArray[0]['order_placed_time'] = $dishArray[0]['order_placed_time'];
												$dishArray[0]['total_price'] = $dishArray[0]['total_price'];
												$dishArray[0]['delivery_charges'] = $dishArray[0]['delivery_charges'];
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
												$dishArray[0]['order_id'] = $orderId;
												$dishArray[0]['order_placed_time'] = $dishArray[0]['order_placed_time'];
												$dishArray[0]['total_price'] = $dishArray[0]['total_price'];
												$dishArray[0]['delivery_charges'] = $dishArray[0]['delivery_charges'];
												$dishArray[0]['delivery_address'] = $address.$dishArray[0]['delivery_address'];

												$dishArray[0]['username'] = $dishArray[0]['user_first_name'].' '.$dishArray[0]['user_last_name'];
												$dishArray[0]['to_email'] = $dishArray[0]['customer_email'];
												//$dishArray[0]['to_email'] = 'vaibhav@itoneclick.com';
												$mails = $this->sendMail($dishArray[0]);
											}
											
											if($dishArray[0]['user_email'])
											{
												$dishArray1[0]['sequence_no'] = $dishArray[0]['sequence_no'];
												$dishArray1[0]['order_id'] = $orderId;
												$dishArray1[0]['order_placed_time'] = $dishArray[0]['order_placed_time'];
												$dishArray[0]['total_price'] = $dishArray[0]['total_price'];
												$dishArray11[0]['delivery_charges'] = $dishArray[0]['delivery_charges'];
												$dishArray1[0]['delivery_address'] = $address.$dishArray[0]['delivery_address'];
												
												$dishArray1[0]['username'] = $dishArray[0]['customer_name'];
												$dishArray1[0]['to_email'] = $dishArray[0]['user_email'];
												//$dishArray1[0]['to_email'] = 'vaibhav@itoneclick.com';
												$mails = $this->sendMail($dishArray1[0]);
											}
										}
									}
								}
							}
							else{
								$response=array("response"=>"false","message"=>$fail_to_change_order_status,"webservice_name"=>"changeOrderStatus");
							}
						}
						$this->sendPushNotificationUsingFirebaseToDriver($orderStatus, $orderId);
					}
					else{

						$response = array("response"=>"false","message"=>$cant_change_order,"account_status"=>"inactive","webservice_name"=>"changeOrderStatus");
					}
				}
			}
			else{

				$response = array("response"=>"false","access"=>"false","message"=>$token_mismatched,"webservice_name"=>"changeOrderStatus");
			}
		}
		$this->evaluateExecutionTime($startTime,'changeOrderStatus');
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : Get order status
	 * Created by : Vaibhav Mehta
	 * Created Date: 28/11/17 03:30 PM 
	*/
	function getOrderStatus()
	{
		$startTime 		= microtime(true);

		$lange 				= trim($this->input->post("default_language"));
		if(in_array($lange,$this->supported_language))
		{
			$lang 		= $lange;
			$langFile 	= $lange.'_lang';
		}
		else
		{
			$lang 		= $this->default_language;
			$langFile 	= $lang.'_lang';
		}
		$this->lang->load($langFile,$lang);
		$message_getOrderStatus 		= $this->lang->line('message_getOrderStatus');
		$account_deleted 				= $this->lang->line('account_deleted');
		$default_language_required 		= $this->lang->line('default_language_required');
		$token_mismatched 				= $this->lang->line('token_mismatched');
		$userid_required 				= $this->lang->line('userid_required');
		$token_required 				= $this->lang->line('token_required');
		$order_id_required 				= $this->lang->line('order_id_required');
		$failed_order_status 			= $this->lang->line('failed_order_status');

		if(trim($this->input->post('user_id'))=="")
		{
			$response = array("success"=>"false","message"=>$userid_required);
		}
		else if(trim($this->input->post('access_token'))==""){
			$response = array("response"=>"false","message"=>$token_required);
		}
		else if(trim($this->input->post('order_id'))==""){
			$response = array("response"=>"false","message"=>$order_id_required);
		}
		else
		{
			$userId				= trim($this->input->post('user_id'));
			$accessToken 		= trim($this->input->post("access_token"));
			$orderId 			= trim($this->input->post("order_id"));
			
			$orderData 			= $this->Home_model->getOrderIdFromSequenceNo($orderId);
			$orderId 			= $orderData->order_id;
			$token = $this->checkAccessToken($userId,$accessToken);
		
			if($accessToken===$token)
			{
				$account = $this->Webservice_driver_model->chkAccountDelete($userId);

				if(is_array($account) && count($account)>0){

					$response = array("response"=>"false","message"=>$account_deleted,"account_status"=>"inactive","webservice_name"=>"getOrderStatus");
				}
				else
				{
					$getOrderData = $this->Webservice_driver_model->getOrderData($orderId);
					$deliveryTime = '3600';

					if(is_array($getOrderData) && count($getOrderData)>0)
					{
						$deliveryTime = $getOrderData[0]->custom_delivery_time;
						$order_status = $getOrderData[0]->order_status;
						$response=array("response"=>"true","message"=>$message_getOrderStatus,"delivery_time"=>$deliveryTime,"order_status"=>$order_status,"webservice_name"=>"getOrderStatus");
					}
					else
					{
						$response=array("response"=>"false","message"=>$failed_update_order,"webservice_name"=>"getOrderStatus");
					}
				}
			}
			else{

				$response = array("response"=>"false","access"=>"false","message"=>$token_mismatched,"webservice_name"=>"getOrderStatus");
			}
		}
		$this->evaluateExecutionTime($startTime,'getOrderStatus');
		echo json_encode($response);
		exit;
	}

		/**
	 * Description : Driver change password request
	 * Created by : Umang Kotahri
	 * Created Date: 25/12/17 04:45 PM 
	*/
	function changePassword()
	{
		$startTime = microtime(true);
		if(trim($this->input->post('user_id'))==""){
			$response=array("response"=>"0","message"=>"User id is required!");
		}
		else if(trim($this->input->post('access_token'))==""){
			$response=array("response"=>"0","message"=>"Access token is required!");
		}
		else if(trim($this->input->post('old_password'))==""){
			$response=array("response"=>"0","message"=>"Old password is required!");
		}
		else if(trim($this->input->post('new_password'))==""){
			$response=array("response"=>"0","message"=>"New password is required!");
		}
		else if(trim($this->input->post('default_language'))==""){
			$response = array("response"=>"false","message"=>"Language is required!");
		}
		else
		{
			$userId 		= trim($this->input->post('user_id'));
			$accessToken	= trim($this->input->post('access_token'));
			$lange 			= trim($this->input->post("default_language"));
			if(in_array($lange,$this->supported_language))
			{
				$lang 		= $lange;
				$langFile 	= $lange.'_lang';
			}
			else
			{
				$lang 		= $this->default_language;
				$langFile 	= $lang.'_lang';
			}

			$this->lang->load($langFile,$lang);
			$message = $this->lang->line('message_getProductDetails');

			$token = $this->checkAccessToken($userId,$accessToken);
		
			if($accessToken===$token){
				$account = $this->Webservice_driver_model->chkAccountDelete($userId);

				if(is_array($account) && count($account)>0){

					$response = array("response"=>"false","message"=>"Your account was deleted!!!","account_status"=>"inactive","webservice_name"=>"addMyOrderRate");
				}
				else{

					$oldPassword	= md5(trim($this->input->post('old_password')));


					$chkedPassword = $this->Webservice_driver_model->checkPassword($userId,$oldPassword);

					$toEmail 		= $chkedPassword['email'];
					$toName 		= $chkedPassword['first_name'];

					if(is_array($chkedPassword) && count($chkedPassword)>0)
					{
						$userDetails['password'] = md5(trim($this->input->post('new_password')));

						$result 		= $this->Webservice_driver_model->updateUserData($userId,$userDetails);
						if( $result == 1 )
						{
							$data['email_template']		= 'update_password';
							$data['to_email']			= $toEmail;
							$data['name']				= $toName;
							$data['subject']			= 'Update Password';					

							$mails = $this->sendMail($data);
							$response = array("response" => "1" , "message" => "Your password has been successfully updated. Please check your inbox.");					
						}
						else
						{
							$response = array("response" => "0" , "message" => "Your password is not updated.");
						}
					}
					else
					{
						$response = array("response" => "0" , "message" => "You current password is incorrect!");
					}
				}
			}
			else{

				$response = array("response"=>"false","access"=>"false","message"=>"Access token mismatched!!!","webservice_name"=>"addMyOrderRate");
			}
		}
		$this->evaluateExecutionTime($startTime,'getProductDetails');
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : change language
	 * Created by : Vaibhav Mehta
	 * Created Date: 28/12/17 07:20 PM 
	*/ 
	function changeLanguage()
	{
		$startTime 		= microtime(true);

		$lange 			= trim($this->input->post("default_language"));
		if(in_array($lange,$this->supported_language))
		{
			$lang 		= $lange;
			$langFile 	= $lange.'_lang';
		}
		else
		{
			$lang 		= $this->default_language;
			$langFile 	= $lang.'_lang';
		}
		$this->lang->load($langFile,$lang);
		$message_changeLanguage 		= $this->lang->line('message_changeLanguage');
		$account_deleted 				= $this->lang->line('account_deleted');
		$token_mismatched 				= $this->lang->line('token_mismatched');
		$language_required 				= $this->lang->line('language_required');
		$userid_required 				= $this->lang->line('userid_required');
		$token_required 				= $this->lang->line('token_required');
		$failed_changeLanguage 			= $this->lang->line('failed_changeLanguage');

		if(trim($this->input->post('user_id'))=="")
		{
			$response = array("success"=>"false","message"=>$userid_required);
		}
		else if(trim($this->input->post('access_token'))==""){
			$response = array("response"=>"false","message"=>$token_required);
		}
		else if(trim($this->input->post('language_id'))==""){
			$response = array("response"=>"false","message"=>$language_required);
		}
		else
		{
			$userId				= trim($this->input->post('user_id'));
			$accessToken 		= trim($this->input->post("access_token"));
			$lang 				= trim($this->input->post("language_id"));
			
			$token = $this->checkAccessToken($userId,$accessToken);
		
			if($accessToken===$token)
			{
				$account = $this->Webservice_driver_model->chkAccountDelete($userId);

				if(is_array($account) && count($account)>0)
				{
					$response = array("response"=>"false","message"=>$account_deleted,"account_status"=>"inactive","webservice_name"=>"changeLanguage");
				}
				else
				{
					$userData = array();
					$userData['language_id'] 	= $lang;
					$userData['updated_date'] 	= date("Y-m-d H:i:s");

					$result = $this->Webservice_driver_model->updateUserProfile($userId,$userData);

					if($result)
					{
						$response=array("response"=>"true","message"=>$message_changeLanguage,"webservice_name"=>"changeLanguage");
					}
					else
					{
						$response=array("response"=>"false","message"=>$failed_changeLanguage,"webservice_name"=>"changeLanguage");
					}
				}
			}
			else
			{
				$response = array("response"=>"false","access"=>"false","message"=>$token_mismatched,"webservice_name"=>"changeLanguage");
			}
		}
		$this->evaluateExecutionTime($startTime,'changeLanguage');
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : for getting current server time
	 * Created by :Manisha Kanazariya
	 * Created Date: 20/06/18 05:20 PM 
	*/
	function getCurrentTime(){
		$response = array("response"=>"true","current_time"=>date('Y-m-d h:i:s'),"webservice_name"=>"getCurrentTime");
		$this->evaluateExecutionTime($startTime,'getCurrentTime');
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : for getting drivers today order Details
	 * Created by :Manisha Kanazariya
	 * Created Date: 20/06/18 05:20 PM 
	*/

	function getTodayOrderDetails(){
		$startTime 		= microtime(true);

		$lange 			= trim($this->input->post("default_language"));
		if(in_array($lange,$this->supported_language))
		{
			$lang 		= $lange;
			$langFile 	= $lange.'_lang';
		}
		else
		{
			$lang 		= $this->default_language;
			$langFile 	= $lang.'_lang';
		}

		$this->lang->load($langFile,$lang);
		$language_required 				= $this->lang->line('language_required');
		$userid_required 				= $this->lang->line('userid_required');
		$token_required 				= $this->lang->line('token_required');
		$user_id =$this->input->post('user_id');
		if(trim($user_id)=="")
		{
			$response = array("success"=>"false","message"=>$userid_required);
		}
		else if(trim($this->input->post('access_token'))==""){
			$response = array("response"=>"false","message"=>$token_required);
		}
		
		else
		{
			$getOrders =$this->Webservice_driver_model->getTodayOrderData($user_id);
			//$getOrders =$this->Webservice_driver_model->getTodayOrderDetails($user_id);
			
			if(count($getOrders)>0){
				$oData['total_amount']   =number_format($this->Webservice_driver_model->getTodayOrderData($user_id,7)->total_amount,3);
				$oData['total_order']    =$getOrders->total_order;
				$oData['total_pending']  =$this->Webservice_driver_model->getTodayOrderData($user_id,4)->total_pending;
				$oData['total_completed']=$this->Webservice_driver_model->getTodayOrderData($user_id,7)->total_completed;
				$oData['total_disputed'] =$this->Webservice_driver_model->getTodayOrderData($user_id,8)->total_disputed;
				$response = array("response"=>"true","todaysOrder"=>$oData,"webservice_name"=>"getTodayOrderDetails");
			}else{
				$response = array("response"=>"false","message"=>"order not found","webservice_name"=>"getTodayOrderDetails");
			}
		}
		$this->evaluateExecutionTime($startTime,'getTodayOrderDetails');
		echo json_encode($response);
		exit;
	}

}
