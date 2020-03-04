<?php
/** 
 * Author  				: itoneclick.com 
 * Copyright 			: itoneclick.com 
 * Created by 			: Vaibhav Mehta 
 * Created Date 		: 23 August 2017 6:30 PM 
 * Description 			: Use for various webservice like login,logout,forgot password,etc.
 */ 

error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');


class Webservice_Customers extends MY_Controller{
	/**
	 * Controller Name 	: Webservice
	 * Description 		: Load basic needs like model and functions
	 * Created by 		: Vaibhav Mehta
	 * Created Date 	: 23 August 2017 6:30 PM 
	*/ 
	function __construct(){

		parent::__construct();
		$this->load->model(array('Webservice_customer_model','Webservice_driver_model','Login_model','User_model','Restaurant_model','Home_model'));

		$this->load->library('form_validation');
		$this->default_language    = $this->config->item('default_language');
		$this->supported_language  = $this->config->item('supported_languages');
		$this->OrderStatus         = $this->config->item('OrderStatus');
		$this->default_language    = $this->config->item('default_language');
		$this->supported_language  = $this->config->item('supported_languages');
		if(isset($_COOKIE['lang']) && $_COOKIE['lang']!='' && in_array($_COOKIE['lang'],$this->supported_language))
		{
			$lang 		= $_COOKIE['lang'];
			$langFile 	= $lang.'_frontend_lang';
			$langFiles 	= $lang.'_lang';
		}
		else
		{
			$lang 		= 'EN';
			$langFile 	= 'EN_frontend_lang';	
			$langFiles 	= 'EN_lang';	
		}
		$this->lang->load($langFile,$lang);
		$this->lang->load($langFiles,$lang);

		/*$this->load->library('encrypt');*/
	}
	/**
	 * Description : Check Phone Number is already registered or not
	 * Created by : Vaibhav Mehta
	 * Created Date: 18/05/17 06:00 PM 
	*/ 
	function loginWithPhoneNumber()
	{
		$startTime 		= $this->benchmark->mark('code_start');
		$lange 		 	= trim($this->input->post("default_language"));
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
		$phone_required 			= $this->lang->line('phone_required');
		$country_code_required 		= $this->lang->line('country_code_required');
		$language_required 			= $this->lang->line('language_required');
		$default_language_required 	= $this->lang->line('default_language_required');
		$error_dob 					= $this->lang->line('error_dob');
		$user_details_not_saved 	= $this->lang->line('user_details_not_saved');
		$user_details_found 		= $this->lang->line('user_details_found');
		$user_details_saved 		= $this->lang->line('user_details_saved');

		if(trim($this->input->post('phone_number'))=="")
		{
			$response = array("response"=>"false","message"=>$phone_required);
		}
		else if(trim($this->input->post('country_code'))=="")
		{
			$response = array("response"=>"false","message"=>$country_code_required);
		}
		else if(trim($this->input->post('language'))=="")
		{
			$response = array("response"=>"false","message"=>$language_required);	
		}
		else if(trim($this->input->post('default_language'))==""){
			$response = array("response"=>"false","message"=>$default_language_required);
		}
		else
		{
			if(trim($this->input->post('dob'))!="")
			{
				if($this->validateDate(trim($this->input->post('dob')))=='')
				{
					$error = $error_dob;
				}
				else
				{
					$dob = ($this->input->post('dob')) ? trim($this->input->post('dob')) : '';
				}
			}
			if($error)
			{
				$response = array("response"=>"false","message"=>$error);
			}
			else
			{
				$phone 		 = trim($this->input->post('phone_number'));
				$ccd 		 = trim($this->input->post('country_code'));
				$language 	 = trim($this->input->post('language'));
				

				$deviceType  = ($this->input->post('device_type')) ? trim($this->input->post('device_type')) : '';
				$deviceToken = ($this->input->post('device_token')) ? trim($this->input->post('device_token')) : '';
				$language 	 = ($this->input->post('language')) ? trim($this->input->post('language')) : '';
				$fname 		 = ($this->input->post('first_name')) ? trim($this->input->post('first_name')) : '';
				$lname 		 = ($this->input->post('last_name')) ? trim($this->input->post('last_name')) : '';
				$email 		 = ($this->input->post('email')) ? trim($this->input->post('email')) : '';
				$gender 	 = ($this->input->post('gender')) ? trim($this->input->post('gender')) : '';
				$proLat 	 = ($this->input->post('driver_latitude')) ? trim($this->input->post('driver_latitude')) : '';
				$proLongi 	 = ($this->input->post('driver_longitude')) ? trim($this->input->post('driver_longitude')) : '';
				$address 	 = ($this->input->post('address')) ? trim($this->input->post('address')) : '';
				
				$userMeta 	= array();
				$error 		= '';
				$role = $this->config->item('customer_role');
				$data = $this->Webservice_customer_model->checkPhoneNumber($phone,$role,$ccd);
			
				if(count($data)>0)
				{
					$chkToken = $this->Webservice_customer_model->checkDeviceTokenAvailable($deviceToken);
					if(is_array($chkToken) && count($chkToken)>0)
					{
						$deltetedToken = $this->Webservice_customer_model->deleteDeviceToken($deviceToken);
					}

					$accessToken 				= md5(date("Y-m-d H:i:s"));
					$userMeta['user_id'] 		= $data[0]->user_id;
					$userMeta['device_type'] 	= $deviceType;
					$userMeta['device_token'] 	= $deviceToken;
					$userMeta['access_token'] 	= $accessToken;
					$userMeta['updated_by'] 	= $data[0]->user_id;
					$userMeta['updated_date'] 	= date("Y-m-d H:i:s");
					$tableName 					= 'tbl_user_meta';

					$metaData = $this->Webservice_customer_model->insertData($tableName,$userMeta);
					
					$data[0]->access_token 		= $accessToken;
					
					$response = array("response"=>"true","message"=>$user_details_found,"webservice_name"=>"loginWithPhoneNumber","action_type"=>"login","data"=>$data);
				}
				else
				{
					$userData = array();
					$userData['role_id'] 			= $this->config->item('customer_role');
					$userData['language_id'] 		= $language;
					$userData['first_name'] 		= $fname;
					$userData['last_name'] 			= $lname;
					$userData['email'] 				= $email;
					$userData['password'] 			= md5($password);
					$userData['gender'] 			= $gender;
					$userData['country_code'] 		= $ccd;
					$userData['contact_no'] 		= $phone;
					$userData['dob'] 				= $dob;
					$userData['driver_latitude'] 	= $proLat;
					$userData['driver_longitude'] 	= $proLongi;
					$userData['address'] 			= $address;
					$userData['last_ip_address'] 	= $_SERVER['SERVER_ADDR'];
					$userData['last_plateform'] 	= $_SERVER['HTTP_USER_AGENT'];
					$userData['last_login_time'] 	= date("Y-m-d H:i:s");
					$userData['created_by'] 		= "1";
					$userData['created_date'] 		= date("Y-m-d H:i:s");
					$tableName 						= 'tbl_users';

					if(isset($_FILES) && count($_FILES)>0)
					{
						$userData['profile_photo']	= trim($_FILES['profile_photo']['name']);
					}
					if( $error!='' && sizeof($error)>0 )
					{
						$errors = isset($error) ? $error : '';
						$response=array("response"=>"false","message"=>$user_details_not_saved,"webservice_name"=>"loginWithPhoneNumber","action_type"=>"register");
					}
					else
					{
						if( isset($_FILES['profile_photo']) && $_FILES['profile_photo']['name']!='' )
						{
							$config['upload_path']   = './assets/uploads/restaurants/'; 
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
					}

					$uid = $this->Webservice_customer_model->insertData($tableName,$userData);

					if($uid)
					{
						$accessToken 				= md5(date("Y-m-d H:i:s"));
						$userMeta['user_id'] 		= $uid;
						$userMeta['device_type'] 	= $deviceType;
						$userMeta['device_token'] 	= $deviceToken;
						$userMeta['access_token'] 	= $accessToken;
						$userMeta['updated_by'] 	= $uid;
						$userMeta['updated_date'] 	= date("Y-m-d H:i:s");
						$tableName1 				= 'tbl_user_meta';

						$metaData = $this->Webservice_customer_model->insertData($tableName1,$userMeta);
						
						$userData['access_token'] 		= $accessToken;
						$userData['user_id'] 		= $uid;
						$response=array("response"=>"true","message"=>$user_details_saved,"webservice_name"=>"action_typeaction_type","action_type"=>"register","data"=>$userData);
					}
					else
					{
						$response=array("response"=>"false","message"=>$user_details_not_saved,"webservice_name"=>"action_typeaction_type","action_type"=>"register");
					}
				}
			}
		}
		$this->evaluateExecutionTime($startTime,'loginWithPhoneNumber',$this->input->post());
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
		$userData = $this->Webservice_customer_model->getUserMetaData($userId,$accessToken);
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
	 * Description 		: check date format
	 * Input 			: date
	 * Output 			: PHP default date format
	 * Created by 		: Vaibhav Mehta
	 * Created Date 	: 24 August 2017 10:30 PM 
	*/ 
	function dateFormat($date){
		if(!empty($date)){
			return date("Y-m-d H:i:s",strtotime($date));
		}else{
			return $date;
		}
	}

	/**
	 * Description 		: If user forgot password and want to change password, send email for it
	 * Input 			: Click on forgot password and enter new password
	 * Output 			: user login credentials has been changed
	 * Created by 		: Vaibhav Mehta
	 * Created Date 	: 31 August 2017 3:00 PM
	*/ 
	function forgotPassword(){
		$startTime = $this->benchmark->mark('code_start');
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
		$sendEmail       	        = $this->lang->line('message_sendEmail');
		$email_required 			= $this->lang->line('email_required');
		$default_language_required 	= $this->lang->line('default_language_required');
		$error_email_send 			= $this->lang->line('error_email_send');
		$error_login_via_facebook 	= $this->lang->line('error_login_via_facebook');
		$error_login_via_gmail 		= $this->lang->line('error_login_via_gmail');
		$error_login_via_login 		= $this->lang->line('error_login_via_login');
		$error_email_format 		= $this->lang->line('error_email_format');

		$this->form_validation->set_rules('email','Email','valid_email', $email_require);
		if(trim($this->input->post('email'))=="")
		{
			$response=array("response"=>"false","message"=>$email_required);
		}else if($this->form_validation->run() == FALSE){
			
			$response=array("response"=>"false","message"=>$this->lang->line('message_EnetrEmail1'));
		}
		else if(trim($this->input->post('default_language'))==""){
			$response = array("response"=>"false","message"=>$default_language_required);
		}
		else{
			$email 	= trim($this->input->post('email'));			
			
			$res 	= $this->Webservice_customer_model->checkEmailExist($email);
			if(is_array($res) && count($res)>0){
				if($res[0]->platform == "0"){

					$mail 							= $this->base64url_encode($email);
					$passwordResetLink 				= site_url("login/resetPassword/".$mail."/5");
					$data['passwordResetLink']		= $passwordResetLink;
					$data['email_template']			= 'password_forgot';
					$data['to_email']				= $email;
					$data['user_name']				= $res[0]->user_name;
					$data['subject']				= 'Reset Password';
					$message 						= "Click On below link to reset your password<br>".$passwordResetLink;

					$mails = $this->sendMail($data);
					
					if($mails['success'] == "1"){
						$userUpdate = array(
						"change_password_date" 		=> date("Y-m-d H:i:s"),
						"security_token" 			=> $mail,
						"security_token_expiry_date"=> date("Y-m-d H:i:s",strtotime('+1 hour'))
						);
						$dataSuccess = $this->Webservice_customer_model->updateUserProfile($res[0]->user_id,$userUpdate);
						$response = array("response"=>"true","message"=>$sendEmail);
					}
					else{
						$response = array("response"=>"false","message"=>$error_email_send);
					}
				}
				else{
					if($res[0]->register_via == "1"){
						$response = array("response"=>"false","message"=>$error_login_via_facebook);
					}
					else if($res[0]->register_via == "2"){
						$response = array("response"=>"false","message"=>$error_login_via_gmail);
					}
					else{
						$response = array("response"=>"false","message"=>$error_login_via_login);
					}
				}
			}
			else{
				$response = array("response"=>"false","message"=>$error_email_format);
			}
		}
		$this->evaluateExecutionTime($startTime,'forgotPassword',$this->input->post());
		echo json_encode($response);
		exit;
	}

	/**
	 * Controller Name: Webservice
	 * Description : User logout functionality
	 * Input : Click on logout
	 * Output : user succesfully logged out
	 * Created by : Vaibhav Mehta
	 * Created Date: 18/05/17 06:20 PM 
	*/ 
	function restaurantLogout()
	{
		$startTime = $this->benchmark->mark('code_start');
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
		$message_restaurantLogout 	= $this->lang->line('message_restaurantLogout');
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

			$res = $this->Webservice_customer_model->deleteUserMetaData($userId,$deviceToken);
			$response = array("response"=>"true","message"=>$message_restaurantLogout);	
		}
		$this->evaluateExecutionTime($startTime,'restaurantLogout',$this->input->post());
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : Validate of date and time
	 * Input : date and it's format if needed
	 * Output : Specific date format
	 * Created by : Vaibhav Mehta
	 * Created Date: 12/06/17 12:00 PM 
	*/ 
	function validateDate($date, $format = 'Y-m-d')
	{
	    $d = DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) == $date;
	}

	/**
	 * Description : profile detail updation
	 * Created by : Vaibhav Mehta
	 * Created Date: 05/10/17 06:30 PM 
	*/
	function updateProfile()
	{
		$startTime = $this->benchmark->mark('code_start');
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
		$platform_required 			= $this->lang->line('platform_required');
		$delivery_contact_no_required 	= $this->lang->line('delivery_contact_no_required');
		$gender_required 			= $this->lang->line('gender_required');
		$account_deleted 			= $this->lang->line('account_deleted');
		$error_dob 					= $this->lang->line('error_dob');
		$no_updates 				= $this->lang->line('no_updates');
		$token_mismatched 			= $this->lang->line('token_mismatched');

		if(trim($this->input->post('user_id'))=="")
		{
			$response = array("response"=>"false","message"=>$userid_required);
		}
		else if(trim($this->input->post('access_token'))=="")
		{
			$response = array("response"=>"false","message"=>$token_required);
		}
		else if(trim($this->input->post('role_id'))=="")
		{
			$response = array("response"=>"false","message"=>$role_required);
		}
		else if(trim($this->input->post('first_name'))=="")
		{
			$response = array("response"=>"false","message"=>$first_name_required);
		}		
		else if(trim($this->input->post('delivery_contact_no'))=="")
		{
			$response = array("response"=>"false","message"=>$delivery_contact_no_required);
		}
		else if(trim($this->input->post('platform'))=="")
		{
			$response = array("response"=>"false","message"=>$platform_required);
		}
		/*else if(trim($this->input->post('gender'))=="")
		{
			$response = array("response"=>"false","message"=>$gender_required);
		}*/
		else if(trim($this->input->post('default_language'))==""){
			$response = array("response"=>"false","message"=>$default_language_required);
		}
		else
		{
			$userId 		= trim($this->input->post('user_id'));
			$accessToken 	= trim($this->input->post("access_token"));
			$firstname 		= trim($this->input->post("first_name"));
			$deliverycontact= trim($this->input->post("delivery_contact_no"));
			$gender 		= trim($this->input->post("gender"));
			$role 			= trim($this->input->post("role_id"));
			
			$token 			= $this->checkAccessToken($userId,$accessToken);
			if($accessToken===$token)
			{
				$account = $this->Webservice_customer_model->chkAccountDelete($userId);
				if(is_array($account) && count($account)>0)
				{
					$response = array("response"=>"false","message"=>$account_deleted,"account_status"=>"inactive","webservice_name"=>"updateProfile");
				}
				else
				{
					if($dob!='')
					{
						if($this->validateDate(trim($this->input->post('dob')))=='')
						{
							$error = $error_dob;
						}
						else
						{
							$dob = ($this->input->post('dob')) ? trim($this->input->post('dob')) : '';
						}
					}

					if($error)
					{
						$response = array("response"=>"false","webservice_name"=>"updateProfile","message"=>$error);
					}
					else
					{
						$userData['first_name']		= trim($this->input->post('first_name'));
						$userData['gender']			= trim($this->input->post('gender'));
						$userData['delivery_contact_no'] = trim($this->input->post('delivery_contact_no'));

						if($this->input->post('last_name')!='')
						{
							$userData['last_name']	= trim($this->input->post('last_name'));
						}
						if($this->input->post('email')!='' && $this->input->post('platform')==1)
						{
							$userData['email']	= trim($this->input->post('email'));
						}					
						if($this->input->post('dob')!='')
						{
							if($this->validateDate(trim($this->input->post('dob')))==false)
							{							
								$error = $error_dob;
							}
							else
							{
								$userData['dob']	    = $this->dateFormat($this->input->post('dob'));
							}
						}

						if($this->input->post('last_name')!='')
						{
							$userData['last_name']	= ($this->input->post('last_name')) ? trim($this->input->post('last_name')) : '' ;
						}
						if($this->input->post('language')!='')
						{
							$userData['language']	= (trim($this->input->post('language'))) ? trim($this->input->post('language')) : '';
						}
						if($this->input->post('city')!='')
						{
							$userData['city']	= (trim($this->input->post('city'))) ? trim($this->input->post('city')) : '';
						}
						if($this->input->post('state')!='')
						{
							$userData['state']	= (trim($this->input->post('state'))) ? trim($this->input->post('state')) : '';
						}
						if($this->input->post('conutry')!='')
						{
							$userData['conutry']	= (trim($this->input->post('conutry'))) ? trim($this->input->post('conutry')) : '';
						}
						if($this->input->post('country_code')!='')
						{
							$userData['country_code']	= (trim($this->input->post('country_code'))) ? trim($this->input->post('country_code')) : '';
						}

						if(isset($_FILES) && count($_FILES)>0){
							$userData['profile_photo']	= trim($_FILES['profile_photo']['name']);
						}
						$userData['updated_by']		= $userId;
						$userData['updated_date']	= date("Y-m-d H:i:s");

						if( isset($error) && sizeof($error)>0 )
						{
							$errors = isset($error) ? $error : '';
							$response = array("response"=>"false","message"=>$errors,"account_status"=>"active","webservice_name"=>"updateProfile");
						}
						else
						{
							if( isset($_FILES['profile_photo']) && $_FILES['profile_photo']['name']!='' )
							{

								
								if($role == $this->config->item('super_admin_role'))
								{
									$config['upload_path']   = './assets/uploads/users/';
								}
								else if($role==$this->config->item('restaurant_owner_role'))
								{
									$config['upload_path']   = './assets/uploads/restaurants/';
								}
								else if($role==$this->config->item('restaurant_manager_role'))
								{
									$config['upload_path']   = './assets/uploads/restaurants/';
								}
								else if($role== $this->config->item('driver_role'))
								{
									$config['upload_path']   = './assets/uploads/users/drivers/';
								}
								else if($role==$this->config->item('customer_role'))
								{
									$config['upload_path']   = './assets/uploads/users/customers/';
								}
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
						}

						if( isset($error) && sizeof($error)>0 )
						{
							$errors = isset($error) ? $error : '';
							$response = array("response"=>"false","message"=>$errors,"account_status"=>"active","webservice_name"=>"updateProfile");
						}
						else
						{
							$resp = $this->Webservice_customer_model->updateUserProfile($userId,$userData);

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
								$respo = $this->Webservice_customer_model->getUserdetails($userId);

								if(is_array($respo) && count($respo)>0)
								{
									foreach ($respo as $key => $value)
									{
										if($value->profile_photo)
										{
											$respo[$key]->profile_photo =$userData['profile_photo'];
										}
									}
								}
							
								$response = array("response"=>"true","message"=>$message_updateProfile,"data"=>$respo,"account_status"=>"active","webservice_name"=>"updateProfile");
							}
							else
							{
								$response = array("response"=>"false","message"=>$no_updates,"account_status"=>"active","webservice_name"=>"updateProfile");
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
		$this->evaluateExecutionTime($startTime,'updateProfile',$this->input->post());
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : Get dashboard content details
	 * Created by : Vaibhav Mehta
	 * Created Date: 06/10/17 04:30 PM 
	*/
	function getDashboardContent()
	{
		$startTime = $this->benchmark->mark('code_start');
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
		$message_getDashboardContent = $this->lang->line('message_getDashboardContent');
		$default_language_required 	 = $this->lang->line('default_language_required');
		$role_required 				 = $this->lang->line('role_required');
		$no_dashboard_content 		 = $this->lang->line('no_dashboard_content');

		if(trim($this->input->post('role_id'))=="")
		{
			$response = array("response"=>"false","message"=>$role_required);
		}
		else if(trim($this->input->post('default_language'))==""){
			$response = array("response"=>"false","message"=>$default_language_required);
		}
		else
		{
			$userId 		= trim($this->input->post('user_id'));
			$roleId 		= trim($this->input->post("role_id"));
			$offset 		= trim($this->input->post('offset')) ? $this->input->post('offset') : '0';
			$total 			= trim($this->input->post('total')) ? $this->input->post('total') : '10';
			
			$cuisines 		= $this->Webservice_customer_model->getAllDishCategories($offset,$total);
			$dishes 		= $this->Webservice_customer_model->getFastMovingDishes($offset,$total);

			if(is_array($cuisines) && count($cuisines)>0)
			{
				foreach ($cuisines as $key => $value)
				{
					if($value->image)
					{
						$cuisines[$key]->image = base_url().'assets/uploads/products/'.$value->image;
					}
				}
			}

			if(is_array($dishes) && count($dishes)>0)
			{
				foreach ($dishes as $key => $value)
				{
					if($value->dish_image)
					{
						$dishes[$key]->dish_image = base_url().'assets/uploads/products/'.$value->dish_image;
					}
				}
			}

			if(count($cuisines)>0 || count($dishes)>0)
			{
				$response = array("response"=>"true","webservice_name"=>"getDashboardContent","message"=>$message_getDashboardContent,"dish_categories"=>$cuisines,"best_selling_dishes"=>$dishes);
			}
			else
			{
				$response = array("response"=>"false","webservice_name"=>"getDashboardContent","message"=>$no_dashboard_content);
			}
		}
		$this->evaluateExecutionTime($startTime,'getDashboardContent',$this->input->post());
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : Get dish categories and dishes details based on cuisine id
	 * Created by : Vaibhav Mehta
	 * Created Date: 06/10/17 05:30 PM 
	*/
	function getCategoryDishesBasedOnCuisine()
	{
		$startTime = $this->benchmark->mark('code_start');
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
		$message_getCategoryDishesBasedOnCuisine = $this->lang->line('message_getCategoryDishesBasedOnCuisine');
		$token_mismatched 			= $this->lang->line('token_mismatched');
		$role_required 				= $this->lang->line('role_required');
		$userid_required 			= $this->lang->line('userid_required');
		$cuisine_required 			= $this->lang->line('cuisine_required');
		$default_language_required 	= $this->lang->line('default_language_required');
		$no_categories 				= $this->lang->line('no_categories');
		
		if(trim($this->input->post('user_id'))=="")
		{
			$response = array("response"=>"false","message"=>"User id is required!");
		}
		else if(trim($this->input->post('cuisine_id'))=="")
		{
			$response = array("response"=>"false","message"=>$cuisine_required);
		}
		else if(trim($this->input->post('role_id'))=="")
		{
			$response = array("response"=>"false","message"=>"Role is required!");
		}
		else if(trim($this->input->post('default_language'))==""){
			$response = array("response"=>"false","message"=>"Language is required!");
		}
		else
		{
			$userId 		= trim($this->input->post('user_id'));
			$cuisineId 		= trim($this->input->post("cuisine_id"));
			$roleId 		= trim($this->input->post("role_id"));
			$offset 		= trim($this->input->post('offset')) ? $this->input->post('offset') : '0';
			$total 			= trim($this->input->post('total')) ? $this->input->post('total') : '10';
			
			$cats = $this->Webservice_customer_model->getAllDishes($cuisineId);
			$products = array();
			if(is_array($cats) && count($cats)>0)
			{
				foreach ($cats as $key => $value)
				{
					if(!array_key_exists($value->category_id,$products))
					{
						$products[$value->category_id] = array(
							"category_id" => $value->category_id,
							"category_name" => $value->category_name,
							"category_name_ar" => $value->category_name_ar,
							"category_image" => base_url().'assets/uploads/category/'.$value->image,
							"dishes" => array()
						);
					}

					$products[$value->category_id]['dishes'][] = array(
						"dish_id" => $value->product_id,
						"dish_name" => $value->name,
						"product_ar_name" => $value->product_ar_name,
						"dish_desc" => $value->description,
						"dish_price" => $value->price,
						"dish_image" => base_url().'assets/uploads/products/'.$value->dish_image,
						"discount_type" => $value->discount_type,
						"discount" => $value->discount
					);
				}
				$response = array("response"=>"true","webservice_name"=>"getCategoryDishesBasedOnCuisine","message"=>$message_getCategoryDishesBasedOnCuisine,"dishes"=>$products);
			}
			else
			{
				$response = array("response"=>"false","webservice_name"=>"getCategoryDishesBasedOnCuisine","message"=>$no_categories);
			}
		}
		$this->evaluateExecutionTime($startTime,'getCategoryDishesBasedOnCuisine',$this->input->post());
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : User login functionality
	 * Input : Valid email or phone no and password
	 * Output : user succesfully logged in 
	 * Created by : Vaibhav Mehta
	 * Created Date: 09/10/17 05:00 PM 
	*/ 
	function normalUserLogin()
	{
		$startTime = $this->benchmark->mark('code_start');
		$lange 		= trim($this->input->post("default_language")) ? trim($this->input->post("default_language")) : "EN" ;
		
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
		
		$this->form_validation->set_rules('email','Email','valid_email', $email_require);
		if(trim($this->input->post('email'))=="")
		{
			$response=array("response"=>"false","message"=>$this->lang->line('message_EmailReq'));
		}
		else if(trim($this->input->post('password'))=="")
		{
			$response=array("success"=>"false","message"=>$this->lang->line('message_PasswordReq'));	
		}
		else
		{	
		//var_dump(filter_var($email, FILTER_VALIDATE_EMAIL));exit;
			$email 		 = $this->input->post('email');
			$password 	 = $this->input->post('password');
			$remember_me = $this->input->post('remember_me');
			
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
				$role 	= $this->config->item('customer_role');
				$data 	= $this->Webservice_customer_model->normalUserLogin($email,$password,$type,$role);
				if(is_array($data) && sizeof($data)>0)
				{
					$userId 						= $data[0]->user_id;
					$accessToken 					= md5(date("Y-m-d H:i:s"));
					$userMetaData['access_token'] 	= $accessToken;
					$userMetaData['device_type'] 	= ($this->input->post('device_type')) ? trim($this->input->post('device_type')) : '';
					$userMetaData['device_token'] 	= ($this->input->post('device_token')) ? trim($this->input->post('device_token')) : '';
					$userMetaData['user_id'] 		= $userId;
					$tableName 						= 'tbl_user_meta';
					$metaId                         = $this->Webservice_customer_model->insertData($tableName,$userMetaData);					

					$userDetails['user_id'] 		= $userId;
					$userDetails['first_name'] 		= $data[0]->first_name;
					$userDetails['last_name'] 		= $data[0]->last_name;
					$userDetails['email'] 			= $data[0]->email;
					$userDetails['phone_no'] 		= $data[0]->contact_no;
					$userDetails['profile_photo'] 	= $data[0]->profile_photo;
					$userDetails['role_id'] 		= $data[0]->role;
					$userDetails['access_token'] 	= $accessToken;

					$response = array("response"=>"true","data"=>$userDetails,"message"=>$this->lang->line('message_normalUserLogin'),"webservice_name"=>"normalUserLogin");
				}
				else
				{
					$response=array("success"=>"false","message"=>$this->lang->line('invalid_login'),"webservice_name"=>"normalUserLogin");
				}
			}			
		}
		$this->evaluateExecutionTime($startTime,'normalUserLogin',$this->input->post());
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : User register functionality
	 * Input : Enter neccessary details
	 * Output : user can be register with proper details
	 * Created by : Vaibhav Mehta
	 * Created Date: 09/10/17 05:30 PM 
	*/
	function normalUserSignup()
	{
		$startTime = $this->benchmark->mark('code_start');
		$lange 		= trim($this->input->post("default_language"));
		if(isset($_COOKIE['lang']) && $_COOKIE['lang']!='')
		{
			$lang 		=$_COOKIE['lang'];

		}
		else
		{
			$lang 		= $this->default_language;
			
		}
			$langFile 	= $lang.'_lang';

		$this->lang->load($langFile,$lang);
		$message_normalUserSignup 	= $this->lang->line('message_normalUserSignup');
		$error_register 			= $this->lang->line('error_register');
		$first_name_required 		= $this->lang->line('first_name_required');
		$last_name_required 		= $this->lang->line('last_name_required');
		$error_email 				= $this->lang->line('error_email');
		$email_required 			= $this->lang->line('email_required');
		$password_required 			= $this->lang->line('password_required');
		$phone_required 			= $this->lang->line('phone_required');
		$country_code_required 		= $this->lang->line('country_code_required');
		$default_language_required 	= $this->lang->line('default_language_required');
		$contact_exists 			= $this->lang->line('message_phone_exists');
		$email_exists 				= $this->lang->line('email_exists');
		$error_email_format 		= $this->lang->line('error_email_format');
		$customer_not_created 		= $this->lang->line('customer_not_created');

		if(trim($this->input->post('first_name'))=="")
		{
			$response=array("response"=>"false","message"=>$first_name_required);
		}
		else if(trim($this->input->post('last_name'))=="")
		{
			$response=array("response"=>"false","message"=>$last_name_required);	
		}
		else if(trim($this->input->post('email'))=="")
		{
			$response=array("response"=>"false","message"=>$email_required);
		}
		else if(trim($this->input->post('password'))=="")
		{
			$response=array("response"=>"false","message"=>$password_required);
		}
		else if(trim($this->input->post('contact'))=="")
		{
			$response=array("response"=>"false","message"=>$phone_required);
		}
		else if(trim($this->input->post('country_code'))=="")
		{
			$response=array("response"=>"false","message"=>$country_code_required);
		}
		else if(trim($this->input->post('default_language'))==""){
			$response = array("response"=>"false","message"=>$default_language_required);
		}
		else
		{
			$email 		= trim($this->input->post('email'));
			$fname 		= trim($this->input->post('first_name'));
			$lname 		= trim($this->input->post('last_name'));
			$country_code	= trim($this->input->post('country_code'));
			$contact	= trim($this->input->post('contact'));
			$password 	= md5(trim($this->input->post('password')));
			$role 		= $this->config->item('customer_role');			

			$emailRes 	= $this->Webservice_customer_model->checkEmailExist($email);
			$contactRes	= $this->Webservice_customer_model->checkPhoneNumber($contact,$role);
			if(is_array($contactRes) && sizeof($contactRes)>0)
			{
				$response = array("response"=>"false","message"=>$contact_exists,"webservice_name"=>"normalUserSignup");
			}
			else
			{
				if (!filter_var($email, FILTER_VALIDATE_EMAIL))
				{
				    $response = array("response"=>"false","message"=>$error_email_format,"webservice_name"=>"normalUserSignup");
				}
				else if(is_array($emailRes) && sizeof($emailRes)>0)
				{
					$response = array("response"=>"false","message"=>$email_exists,"webservice_name"=>"normalUserSignup");
				}
				else
				{
					$userData['role_id']		= $this->config->item('customer_role');
					$userData['first_name']		= $fname;
					$userData['last_name']		= $lname;
					$userData['email']			= $email;
					$userData['password']		= $password;
					$userData['contact_no']		= $contact;
					$userData['country_code']	= $country_code;
					$userData['profile_photo']  = 'no.png'; 
					$userData['created_date']	= date("Y-m-d H:i:s");
					$tableName 					= 'tbl_users';

					$userId = $this->Webservice_customer_model->insertData($tableName,$userData);
					if($userId)
					{
						$accessToken 					= md5(date("Y-m-d H:i:s"));
						$userMetaData['user_id']		= $userId;
						$userMetaData['device_type']	= trim($this->input->post('device_type'));
						$userMetaData['device_token']   = trim($this->input->post('device_token'));
						$userMetaData['access_token']   = $accessToken;
						$tableName1 					= 'tbl_user_meta';

						$res = $this->Webservice_customer_model->insertData($tableName1,$userMetaData);

						$userData['access_token'] 		= $accessToken;
						$userData['user_id']      		= $userId;

						$response=array("response"=>"true","data"=>$userData,"webservice_name"=>"normalUserSignup");
					}
					else
					{
						$response = array("response"=>"false","message"=>"customer not creadted");	
					}
				}
			}
		}
		$this->evaluateExecutionTime($startTime,'normalUserSignup',$this->input->post());
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : User Login via Social Media functionality
	 * Input : User has to login with either facebook, google or twitter
	 * Output : User has been logged in successfully
	 * Created by : Vaibhav Mehta
	 * Created Date: 09/10/17 06:00 PM 
	*/
	function socialLogin()
	{
		$startTime = $this->benchmark->mark('code_start');
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
		$message_socialLogin 		= $this->lang->line('message_socialLogin');
		$platform_required 			= $this->lang->line('platform_required');
		$facebook_id_required 		= $this->lang->line('facebook_id_required');
		$google_id_required 		= $this->lang->line('google_id_required');
		$error_login_via_login 		= $this->lang->line('error_login_via_login');
		$error_login_via_normal_login = $this->lang->line('error_login_via_normal_login');
		$user_details_not_found 	= $this->lang->line('user_details_not_found');
		$user_details_found 		= $this->lang->line('user_details_found');
		$message_normalUserSignup 	= $this->lang->line('message_normalUserSignup');
		$error_register 			= $this->lang->line('error_register');

		if(trim($this->input->post('platform'))=="")
		{
			$response=array("response"=>"false","message"=>$platform_required);
		}
		else if(trim($this->input->post('default_language'))==""){
			$response = array("response"=>"false","message"=>$default_language_required);
		}
		else if(trim($this->input->post('platform'))!="")
		{
			if(trim($this->input->post('platform'))=="1" && trim($this->input->post('facebook_id'))=="")
			{
				$response=array("response"=>"false","message"=>$facebook_id_required);
			}
			else if(trim($this->input->post('platform'))=="2" && trim($this->input->post('google_id'))=="")
			{
				$response=array("response"=>"false","message"=>$google_id_required);
			}
			else if(trim($this->input->post('platform'))=="3")
			{
				$response=array("response"=>"false","message"=>$error_login_via_normal_login);
			}
			else
			{
				$error = '';
				$userData = array();
				$platform = trim($this->input->post('platform'));
				
				if($platform == 1)
				{
					$error = $this->Webservice_customer_model->checkFacebookIdExists(trim($this->input->post('facebook_id')));
					$userData = $this->Webservice_customer_model->getUserDetails(trim($this->input->post('facebook_id')),trim($this->input->post('platform')));

					if(is_array($userData) && count($userData)>0)
					{
						$meta['user_id'] 		= $userData[0]->user_id;
						$meta['device_type'] 	= ($this->input->post('device_type')) ? trim($this->input->post('device_type')) : '';
						$meta['device_token'] 	= ($this->input->post('device_token')) ? trim($this->input->post('device_token')) : '';
						$meta['access_token'] 	= md5(date('Y-m-d H:i:s'));
						$meta['updated_by'] 	= $userData[0]->user_id;
						$meta['updated_date'] 	= date('Y-m-d H:i:s');
						$tableName 				= 'tbl_user_meta';
						$metaId = $this->Webservice_customer_model->insertData($tableName,$meta);
						$userData[0]->device_type 	= $meta['device_type'];
						$userData[0]->device_token 	= $meta['device_token'];
						$userData[0]->access_token 	= $meta['access_token'];
					}
					else
					{
						$response = array("response"=>"false","webservice_name"=>"socialLogin","message"=>$user_details_not_found);
					}
				}
				else if($platform == 2)
				{
					$meta = array();
					$error = $this->Webservice_customer_model->checkGoogleIdExists(trim($this->input->post('google_id')));
					$userData = $this->Webservice_customer_model->getUserDetails(trim($this->input->post('google_id')),trim($this->input->post('platform')));

					if(is_array($userData) && count($userData)>0)
					{
						$meta['user_id'] 		= $userData[0]->user_id;
						$meta['device_type'] 	= ($this->input->post('device_type')) ? trim($this->input->post('device_type')) : '';
						$meta['device_token'] 	= ($this->input->post('device_token')) ? trim($this->input->post('device_token')) : '';
						$meta['access_token'] 	= md5(date('Y-m-d H:i:s'));
						$meta['updated_by'] 	= $userData[0]->user_id;
						$meta['updated_date'] 	= date('Y-m-d H:i:s');
						$tableName 				= 'tbl_user_meta';
						$metaId = $this->Webservice_customer_model->insertData($tableName,$meta);
						$userData[0]->device_type 	= $meta['device_type'];
						$userData[0]->device_token 	= $meta['device_token'];
						$userData[0]->access_token 	= $meta['access_token'];
					}
					else
					{
						$response = array("response"=>"false","webservice_name"=>"socialLogin","message"=>$user_details_not_found);
					}
				}

				if($error)
				{
					if(strpos($userData[0]->profile_photo,'http')!==true)
					{
						$userData[0]->profile_photo = base_url().'assets/uploads/users/customers/'.$userData[0]->profile_photo;	
					}

					$response = array("response"=>"true","webservice_name"=>"socialLogin","message"=>$user_details_found,"data"=>$userData[0]);
				}
				else
				{
					$userData['role_id']		= $this->config->item('customer_role');
					$userData['first_name']		= trim($this->input->post('first_name')) ? trim($this->input->post('first_name')) : '';
					$userData['last_name']		= trim($this->input->post('last_name')) ? trim($this->input->post('last_name')) : '';
					$userData['email']			= trim($this->input->post('email')) ? trim($this->input->post('email')) : '';
					$userData['contact_no']		= trim($this->input->post('phone_no')) ? trim($this->input->post('phone_no')) : '';
					$userData['platform']		= trim($this->input->post('platform'));
					$userData['facebook_id']	= trim($this->input->post('facebook_id')) ? trim($this->input->post('facebook_id')) : '';
					$userData['google_id']		= trim($this->input->post('google_id')) ? trim($this->input->post('google_id')) : '';
					$userData['language_id']		= trim($this->input->post('language_id')) ? trim($this->input->post('language_id')) : '';
					$userData['profile_photo']		= trim($this->input->post('profile_photo')) ? trim($this->input->post('profile_photo')) : '';
					$userData['gender']		= trim($this->input->post('gender')) ? trim($this->input->post('gender')) : '';
					$userData['country_code']		= trim($this->input->post('country_code')) ? trim($this->input->post('country_code')) : '';
					$userData['delivery_contact_no']		= trim($this->input->post('delivery_contact_no')) ? trim($this->input->post('delivery_contact_no')) : '';
					$userData['dob']		= trim($this->input->post('dob')) ? trim($this->input->post('dob')) : '';
					$userData['store_name']		= trim($this->input->post('store_name')) ? trim($this->input->post('store_name')) : '';
					$userData['driver_latitude']		= trim($this->input->post('driver_latitude')) ? trim($this->input->post('driver_latitude')) : '';
					$userData['driver_longitude']		= trim($this->input->post('driver_longitude')) ? trim($this->input->post('driver_longitude')) : '';
					$userData['address']		= trim($this->input->post('address')) ? trim($this->input->post('address')) : '';
					$userData['website']		= trim($this->input->post('website')) ? trim($this->input->post('website')) : '';
					$userData['is_send_push']		= trim($this->input->post('is_send_push')) ? trim($this->input->post('is_send_push')) : '';
					$userData['last_ip_address']		= trim($this->input->post('last_ip_address')) ? trim($this->input->post('last_ip_address')) : '';
					$userData['last_plateform']		= trim($this->input->post('last_plateform')) ? trim($this->input->post('last_plateform')) : '';
					$userData['last_login_time']		= trim($this->input->post('last_login_time')) ? trim($this->input->post('last_login_time')) : '';
					$userData['country']		= trim($this->input->post('Country')) ? trim($this->input->post('Country')) : '';
					$userData['state']		= trim($this->input->post('state')) ? trim($this->input->post('state')) : '';
					$userData['city']		= trim($this->input->post('city')) ? trim($this->input->post('city')) : '';
					$userData['available_for_online_order']		= trim($this->input->post('available_for_online_order')) ? trim($this->input->post('available_for_online_order')) : '';
					$userData['fk_vehicle_id']		= trim($this->input->post('fk_vehicle_id')) ? trim($this->input->post('fk_vehicle_id')) : '';
					$userData['security_token']		= trim($this->input->post('security_token')) ? trim($this->input->post('security_token')) : '';
					$userData['security_token_expiry_date']		= trim($this->input->post('security_token_expiry_date')) ? trim($this->input->post('security_token_expiry_date')) : '';
					$userData['is_active']		= trim($this->input->post('is_active')) ? trim($this->input->post('is_active')) : '1';
					$userData['is_deleted']		= trim($this->input->post('is_deleted')) ? trim($this->input->post('is_deleted')) : '0';
					$userData['change_password_date']		= trim($this->input->post('change_password_date')) ? trim($this->input->post('change_password_date')) : '';
					$userData['created_by']		= trim($this->input->post('created_by')) ? trim($this->input->post('created_by')) : '';
					$userData['updated_by']		= trim($this->input->post('updated_by')) ? trim($this->input->post('updated_by')) : '';
					$userData['updated_date']		= trim($this->input->post('updated_date')) ? trim($this->input->post('updated_date')) : '';

					$userData['created_date']	= date("Y-m-d H:i:s");
					$tableName 					= 'tbl_users';

					$userId = $this->Webservice_customer_model->insertData($tableName,$userData);

					if($userId)
					{
						$accessToken 					= md5(date("Y-m-d H:i:s"));
						$userMetaData['user_id']		= $userId;
						$userMetaData['device_type']	= trim($this->input->post('device_type'));
						$userMetaData['device_token']   = trim($this->input->post('device_token'));
						$userMetaData['access_token']   = $accessToken;
						$tableName1 					= 'tbl_user_meta';

						$res = $this->Webservice_customer_model->insertData($tableName1,$userMetaData);

						$userData['access_token'] 		= $accessToken;
						$userData['user_id']      		= $userId;
						$response=array("response"=>"true","data"=>$userData,"message"=>$message_normalUserSignup,"webservice_name"=>"socialLogin");
					}
					else
					{
						$response=array("response"=>"false","message"=>$error_register,"webservice_name"=>"socialLogin");	
					}
				}
			}
		}
		$this->evaluateExecutionTime($startTime,'socialLogin',$this->input->post());
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : Get dishes details based on product id
	 * Created by :Rashmi Nayani
	 * Created Date: 11/10/17 01:00 PM 
	*/
	function getProductDetails()
	{
		$startTime = $this->benchmark->mark('code_start');
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
		$message_getProductDetails 	= $this->lang->line('message_getProductDetails');
		$no_product 				= $this->lang->line('no_product');
		$product_required 			= $this->lang->line('product_required');
		$default_language_required 	= $this->lang->line('default_language_required');


		if(trim($this->input->post('product_id'))==""){
			$response = array("response"=>"false","message"=>$product_required);
		}
		else if(trim($this->input->post('default_language'))==""){
			$response = array("response"=>"false","message"=>$default_language_required);
		}
		else
		{
			$productId 	= trim($this->input->post("product_id"));
			
			$dishDetail = $this->Webservice_customer_model->getDishDetail($productId);
			
			if(is_array($dishDetail) && count($dishDetail)>0)
			{
				if(is_array($dishDetail) && count($dishDetail)>0)
				{
					foreach ($dishDetail as $key => $value)
					{
						if($value->dish_image)
						{
							$dishDetail[$key]->dish_image = base_url().'assets/uploads/products/'.$dishDetail[0]->dish_image;
						}
					}
				}
				$response = array("response"=>"true","webservice_name"=>"getProductDetails","message"=>$message_getProductDetails,"dishes"=>$dishDetail);
			}
			else
			{
				$response = array("response"=>"false","webservice_name"=>"getProductDetails","message"=>$no_product);
			}
		}
		$this->evaluateExecutionTime($startTime,'getProductDetails',$this->input->post());
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : add customer product delivery address details
	 * Created by : Rashmi Nayani
	 * Created Date: 13/10/17 03:50 PM 
	*/ 
	function addDeliveryAddress()
	{
		//print_r($this->input->post());exit;
		$startTime = $this->benchmark->mark('code_start');
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

		if(trim($this->input->post('user_id'))==""){

			$response = array("response"=>"false","message"=>$this->lang->line('userid_required'));
		}
		else if(trim($this->input->post('access_token'))==""){

			$response = array("response"=>"false","message"=>$this->lang->line('token_required'));
		}
		else if(trim($this->input->post('address_type'))==""){

			$response = array("response"=>"false","message"=> $this->lang->line('address_type_required'));
		}
		else if(trim($this->input->post('customer_name'))==""){

			$response = array("response"=>"false","message"=>$this->lang->line('cust_name_required'));
		}
		else if(trim($this->input->post('contact_no'))==""){

			$response = array("response"=>"false","message"=>$this->lang->line('phone_required'));
		}
		else if(trim($this->input->post('customer_latitude'))==""){
			$response = array("response"=>"false","message"=>$this->lang->line('latitude_required'));
			exit("in");
		}
		else if(trim($this->input->post('customer_longitude'))==""){
			$response = array("response"=>"false","message"=>$this->lang->line('longitude_required'));
		}
		else if(trim($this->input->post('locality_id'))==""){
			$response = array("response"=>"false","message"=>$this->lang->line('locality_id_required'));
		}
		else if(trim($this->input->post('default_language'))==""){
			$response = array("response"=>"false","message"=>$this->lang->line('default_language_required'));
		}
		else if(trim($this->input->post('street'))==""){
			$response = array("response"=>"false","message"=>$this->lang->line('street_req'));
		}
		else if(trim($this->input->post('building'))==""){
			$response = array("response"=>"false","message"=>$this->lang->line('building_req'));
		}
		else if(trim($this->input->post('appartment_no'))==""){
			$response = array("response"=>"false","message"=>$this->lang->line('appartment_req'));
		}
		else if(trim($this->input->post('block'))==""){
			$response = array("response"=>"false","message"=>$this->lang->line('block_req'));
		}
		else if(trim($this->input->post('floor'))==""){
			$response = array("response"=>"false","message"=>$this->lang->line('floor_req'));
		}
		else if(trim($this->input->post('address_type') == "3") && trim($this->input->post('other_address')) == ""){
			$response = array("response"=>"false","message"=>$this->lang->line('other_address_req'));
			
		}
		else
		{
			$userId 		= trim($this->input->post('user_id'));
			$accessToken 	= trim($this->input->post("access_token"));
			$token 			= $this->checkAccessToken($userId,$accessToken);
			$email          = trim($this->input->post('email'));
			if($accessToken===$token){
				$account = $this->Webservice_customer_model->chkAccountDelete($userId);
				if(is_array($account) && count($account)>0){

					$response = array("response"=>"false","message"=>$this->lang->line('account_deleted'),"account_status"=>"inactive","webservice_name"=>"addDeliveryAddress");
				}
				else{
					if($email != ''){

						if (!filter_var($email,FILTER_VALIDATE_EMAIL)){

						    $error = $error_email_format;
						}
					}
					if(is_numeric(trim($this->input->post("contact_no"))) == false){

						 $error = $this->lang->line('error_email_format');
						
					}
					if($error){
						$response = array("response"=>"false","webservice_name"=>"addDeliveryAddress","message"=>$error);
					}
					else{
						$deliveryData['user_id'] 		    = $userId;
						$deliveryData['customer_name']      = trim($this->input->post("customer_name"));
						$deliveryData['address_type'] 	    = trim($this->input->post("address_type"));
						$deliveryData['email'] 			    = $email;
						$deliveryData['contact_no'] 	    = trim($this->input->post("contact_no"));
						$deliveryData['locality_id']	    = trim($this->input->post("locality_id"));
						$deliveryData['customer_latitude'] 	= trim($this->input->post("customer_latitude"));
						$deliveryData['customer_longitude']	= trim($this->input->post("customer_longitude"));
						$deliveryData['street']	            = trim($this->input->post("street"));
						$deliveryData['building']	        = trim($this->input->post("building"));
						$deliveryData['appartment_no']	    = trim($this->input->post("appartment_no"));
						$deliveryData['block']	            = trim($this->input->post("block"));
						$deliveryData['avenue']	            = trim($this->input->post("avenue"));
						$deliveryData['floor']	            = trim($this->input->post("floor"));
						$deliveryData['address1'] 		    = trim($this->input->post("address_line1"));
						$deliveryData['other_address'] 		= trim($this->input->post("other_address"));
						$deliveryData['created_by'] 	    = $userId;
						$deliveryData['created_date'] 	    = date("Y-m-d H:i:s");
						$tableName1 					    = 'tbl_customer_delivery_address';
						
						$res = $this->Webservice_customer_model->insertData($tableName1,$deliveryData);
						if($res){
							$deliveryData['address_id'] 	= $res;
							$response=array("response"=>"true","data"=>$deliveryData,"message"=>$this->lang->line('message_addDeliveryAddress'),"webservice_name"=>"addDeliveryAddress");
						}
						else{
							$response=array("response"=>"false","message"=>$this->lang->line('address_add_fail'),"webservice_name"=>"addDeliveryAddress");	
						}
					}
				}
			}
			else{

				$response = array("response"=>"false","access"=>"false","message"=>$this->lang->line('token_mismatched'),"webservice_name"=>"addDeliveryAddress");
			}
		}
		$this->evaluateExecutionTime($startTime,'getProductDetails',$this->input->post());
		echo json_encode($response);exit;
	}

	/**
	 * Description : edit customer product delivery address details
	 * Created by : Rashmi Nayani
	 * Created Date: 13/05/17 03:50 PM 
	**/

	function editDeliveryAddress(){
		$startTime = $this->benchmark->mark('code_start');
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
		$locality_id_required 		= $this->lang->line('locality_id_required');

		if(trim($this->input->post('user_id'))==""){
			$response = array("response"=>"false","message"=>$this->lang->line('userid_required'));
		}
		else if(trim($this->input->post('access_token'))==""){
			$response = array("response"=>"false","message"=>$this->lang->line('token_required'));
		}
		else if(trim($this->input->post('address_id'))==""){
			$response = array("response"=>"false","message"=>$this->lang->line('addressid_required'));
		}
		else if(trim($this->input->post('address_type'))==""){

			$response = array("response"=>"false","message"=>$this->lang->line('address_type_required'));
		}
		else if(trim($this->input->post('contact_no'))==""){
			$response = array("response"=>"false","message"=>$this->lang->line('phone_required'));
		}
		else if(trim($this->input->post('customer_latitude'))==""){
			$response = array("response"=>"false","message"=>$this->lang->line('latitude_required'));
		}
		else if(trim($this->input->post('customer_longitude'))==""){
			$response = array("response"=>"false","message"=>$this->lang->line('longitude_required'));
		}
		else if(trim($this->input->post('locality_id'))==""){
			$response = array("response"=>"false","message"=>$this->lang->line('locality_id_required'));
		}
		else if(trim($this->input->post('default_language'))==""){
			$response = array("response"=>"false","message"=>$this->lang->line('default_language_required'));
		}else if(trim($this->input->post('street'))==""){
			$response = array("response"=>"false","message"=>$this->lang->line('street_req'));
		}
		else if(trim($this->input->post('building'))==""){
			$response = array("response"=>"false","message"=>$this->lang->line('building_req'));
		}
		else if(trim($this->input->post('appartment_no'))==""){
			$response = array("response"=>"false","message"=>$this->lang->line('appartment_req'));
		}
		else if(trim($this->input->post('block'))==""){
			$response = array("response"=>"false","message"=>$this->lang->line('block_req'));
		}
		else if(trim($this->input->post('floor'))==""){
			$response = array("response"=>"false","message"=>$this->lang->line('floor_req'));
		}
		else if(trim($this->input->post('address_type') == "3") && trim($this->input->post('other_address')) == "")
		{
			$response = array("response"=>"false","message"=>$this->lang->line('other_address_req'));
		}
		else{

			$userId 		= trim($this->input->post('user_id'));
			$addressId 		= trim($this->input->post('address_id'));
			$accessToken 	= trim($this->input->post("access_token"));
			$email 			= ($this->input->post('email')) ? trim($this->input->post('email')) : '';
			
			$token 			= $this->checkAccessToken($userId,$accessToken);
			if($accessToken===$token){
				$account = $this->Webservice_customer_model->chkAccountDelete($userId);
				if(is_array($account) && count($account)>0){

					$response = array("response"=>"false","message"=>$this->lang->line('account_deleted'),"account_status"=>"inactive","webservice_name"=>"editDeliveryAddress");
				}
				else{

					if(trim($this->input->post('email'))!= ''){

						if (!filter_var($email,FILTER_VALIDATE_EMAIL)){

						    $error =$this->lang->line('error_email_format');
						}
					}
					if(is_numeric($this->input->post('contact_no')) == false){

						 $error = array("response"=>"false","message"=>$this->lang->line('contact_numeric'),"webservice_name"=>"editDeliveryAddress");
						
					}
					if($error){
						$response = array("response"=>"false","webservice_name"=>"editDeliveryAddress","message"=>$error);
					}
					else{
						$deliveryData['user_id'] 		    = $userId;
						$deliveryData['customer_name'] 	    = trim($this->input->post("customer_name"));
						$deliveryData['address1'] 		    = trim($this->input->post("address_line1"));
						$deliveryData['address2'] 		    = ($this->input->post('address_line2')) ? trim($this->input->post('address_line2')) : '';
						$deliveryData['address_type'] 	    = trim($this->input->post("address_type"));
						$deliveryData['email'] 			    = $email;
						$deliveryData['contact_no'] 	    = trim($this->input->post("contact_no"));
						$deliveryData['street']	            = trim($this->input->post("street"));
						$deliveryData['building']	        = trim($this->input->post("building"));
						$deliveryData['appartment_no']	    = trim($this->input->post("appartment_no"));
						$deliveryData['block']	            = trim($this->input->post("block"));
						$deliveryData['avenue']	            = trim($this->input->post("avenue"));
						$deliveryData['floor']	            = trim($this->input->post("floor"));
						$deliveryData['customer_latitude'] 	= trim($this->input->post("customer_latitude"));
						$deliveryData['customer_longitude'] = trim($this->input->post("customer_longitude"));
						$deliveryData['locality_id']        = trim($this->input->post("locality_id"));
						$deliveryData['other_address'] 		= trim($this->input->post("other_address"));
						$deliveryData['updated_by'] 	    = $userId;
						$deliveryData['updated_date'] 	    = date("Y-m-d H:i:s");
						
						$res = $this->Webservice_customer_model->updateDeliveryAddressData($deliveryData,$addressId);

						if($res){
							$deliveryData['address_id'] 	= $addressId;
							$response=array("response"=>"true","data"=>$deliveryData,"message"=>$this->lang->line('message_editDeliveryAddress'),"webservice_name"=>"editDeliveryAddress");
						}
						else{

							$response=array("response"=>"false","message"=>$this->lang->line('address_update_fail'),"webservice_name"=>"editDeliveryAddress");	
						}
					}
					
				}
			}
			else{

				$response = array("response"=>"false","access"=>"false","message"=>$this->lang->line('token_mismatched'),"webservice_name"=>"editDeliveryAddress");
			}
		}
		$this->evaluateExecutionTime($startTime,'editDeliveryAddress',$this->input->post());
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : edit customer product delivery address details
	 * Created by : Rashmi Nayani
	 * Created Date: 13/05/17 03:50 PM 
	*/ 
	function deleteDeliveryAddress(){
		$startTime = $this->benchmark->mark('code_start');
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
		$message_deleteDeliveryAddress = $this->lang->line('message_deleteDeliveryAddress');
		$default_language_required 	= $this->lang->line('default_language_required');
		$token_mismatched 			= $this->lang->line('token_mismatched');
		$userid_required 			= $this->lang->line('userid_required');
		$token_required 			= $this->lang->line('token_required');
		$addressid_required 		= $this->lang->line('addressid_required');
		$account_deleted 			= $this->lang->line('account_deleted');
		$address_delete_fail 		= $this->lang->line('address_delete_fail');

		if(trim($this->input->post('user_id'))==""){

			$response = array("response"=>"false","message"=>$userid_required);
		}
		else if(trim($this->input->post('access_token'))==""){

			$response = array("response"=>"false","message"=>$token_required);
		}
		else if(trim($this->input->post('address_id'))==""){

			$response = array("response"=>"false","message"=>$addressid_required);
		}
		else if(trim($this->input->post('default_language'))==""){
			$response = array("response"=>"false","message"=>$default_language_required);
		}
		else{
			$userId 		= trim($this->input->post('user_id'));
			$addressId 		= trim($this->input->post('address_id'));
			$accessToken 	= trim($this->input->post("access_token"));
			$otherAddress 	= trim($this->input->post('other_address'));

			$token 			= $this->checkAccessToken($userId,$accessToken);
			if($accessToken===$token){
				$account = $this->Webservice_customer_model->chkAccountDelete($userId);
				if(is_array($account) && count($account)>0){

					$response = array("response"=>"false","message"=>$account_deleted,"account_status"=>"inactive","webservice_name"=>"deleteDeliveryAddress");
				}
				else{

					$deliveryData['is_active'] 		= '0';
					$deliveryData['other_data'] 	= $otherAddress;
					$deliveryData['updated_by'] 	= $userId;
					$deliveryData['updated_date'] 	= date("Y-m-d H:i:s");
					
					$res = $this->Webservice_customer_model->updateDeliveryAddressData($deliveryData,$addressId);

					if($res){

						$response=array("response"=>"true","data"=>$deliveryData,"message"=>$message_deleteDeliveryAddress,"webservice_name"=>"deleteDeliveryAddress");
					}
					else{

						$response=array("response"=>"false","message"=>$address_delete_fail,"webservice_name"=>"deleteDeliveryAddress");	
					}
				}
			}
			else{

				$response = array("response"=>"false","access"=>"false","message"=>$token_mismatched,"webservice_name"=>"deleteDeliveryAddress");
			}
		}
		$this->evaluateExecutionTime($startTime,'deleteDeliveryAddress',$this->input->post());
		echo json_encode($response);
		exit;
	}
	/**
	 * Description : Get customer delivery address details
	 * Created by :Rashmi Nayani
	 * Created Date: 11/10/17 01:00 PM 
	*/
	function getCustomerDeliveryAddress()
	{
		$startTime = $this->benchmark->mark('code_start');
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
		$message_getCustomerDeliveryAddress = $this->lang->line('message_getCustomerDeliveryAddress');
		$default_language_required 	= $this->lang->line('default_language_required');
		$token_mismatched 			= $this->lang->line('token_mismatched');
		$userid_required 			= $this->lang->line('userid_required');
		$token_required 			= $this->lang->line('token_required');
		$account_deleted 			= $this->lang->line('account_deleted');
		$address_get_fail 			= $this->lang->line('address_get_fail');

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
				$account = $this->Webservice_customer_model->chkAccountDelete($userId);
				if(is_array($account) && count($account)>0)
				{
					$response = array("response"=>"false","message"=>$account_deleted,"account_status"=>"inactive","webservice_name"=>"getDeliveryAddress");
				}
				else
				{

					$addressDetail = $this->Webservice_customer_model->getDeliveryAddress($userId);

					if(is_array($addressDetail) && count($addressDetail)>0)
					{
						$response = array("response"=>"true","webservice_name"=>"getDeliveryAddress","message"=>$message_getCustomerDeliveryAddress,"dishes"=>$addressDetail);
					}
					else
					{
						$response = array("response"=>"false","webservice_name"=>"getDeliveryAddress","message"=>$address_get_fail);
					}
				}
			}
			else
			{
				$response = array("response"=>"false","access"=>"false","message"=>$token_mismatched,"webservice_name"=>"getDeliveryAddress");
			}
		}
		$this->evaluateExecutionTime($startTime,'getCustomerDeliveryAddress',$this->input->post());
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : Get dish categories and dishes details based on cuisine id
	 * Created by : Vaibhav Mehta
	 * Created Date: 06/10/17 05:30 PM 
	*/
	function getCategoryDishesBasedOnCategory()
	{
		$startTime = $this->benchmark->mark('code_start');
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
		$message_getCategoryDishesBasedOnCategory = $this->lang->line('message_getCategoryDishesBasedOnCategory');
		$category_required 			= $this->lang->line('category_required');
		$default_language_required 	= $this->lang->line('default_language_required');
		$role_required 				= $this->lang->line('role_required');
		$dishes_found 				= $this->lang->line('dishes_found');
		$dishes_not_found 			= $this->lang->line('dishes_not_found');

		if(trim($this->input->post('category_id'))=="")
		{
			$response = array("response"=>"false","message"=>$category_required);
		}
		else if(trim($this->input->post('role_id'))=="")
		{
			$response = array("response"=>"false","message"=>$role_required);
		}
		else if(trim($this->input->post('default_language'))==""){
			$response = array("response"=>"false","message"=>$default_language_required);
		}
		else
		{
			$catId 		= trim($this->input->post("category_id"));
			$roleId 	= trim($this->input->post("role_id"));
			$offset 	= trim($this->input->post('offset')) ? $this->input->post('offset') : '0';
			$total 		= trim($this->input->post('total')) ? $this->input->post('total') : '10';
			
			$cat = $this->Webservice_customer_model->getAllDishes($catId);
			if(is_array($cat) && count($cat)>0)
			{
				foreach ($cat as $key => $value)
				{
					if($value->dish_image)
					{
						$cat[$key]->dish_image = base_url().'assets/uploads/products/'.$value->dish_image;
					}

					if($value->image)
					{
						$cat[$key]->image = base_url().'assets/uploads/category/'.$value->image;
					}
				}
				$catName 	= $cat[0]->category_name;
				$catImage 	= base_url().'assets/uploads/category/'.$cat[0]->image;
				$response 	= array("response"=>"true","webservice_name"=>"getCategoryDishesBasedOnCategory","message"=>$dishes_found,"category_id"=>$catId,"category_name"=>$catName,"category_image"=>$catImage,"dishes"=>$cat);
			}
			else
			{
				$response = array("response"=>"false","webservice_name"=>"getCategoryDishesBasedOnCategory","message"=>$dishes_not_found);
			}
		}
		$this->evaluateExecutionTime($startTime,'getCategoryDishesBasedOnCategory',$this->input->post());
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : Get dish categories and dishes details based on cuisine id
	 * Created by : Vaibhav Mehta
	 * Created Date: 06/10/17 05:30 PM 
	*/
	function searchDishes()
	{
		$startTime = $this->benchmark->mark('code_start');
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
		$default_language_required 	= $this->lang->line('default_language_required');
		$message_searchDishes 		= $this->lang->line('message_searchDishes');
		$dish_name_required 		= $this->lang->line('dish_name_required');
		$cat_dish_not_found 		= $this->lang->line('cat_dish_not_found');

		if(trim($this->input->post('dish_name'))=="")
		{
			$response = array("response"=>"false","message"=>$dish_name_required_);
		}
		else if(trim($this->input->post('default_language'))==""){
			$response = array("response"=>"false","message"=>$default_language_required);
		}
		else
		{
			$keyword 		= trim($this->input->post("dish_name"));
			
			$dishes = $this->Webservice_customer_model->searchDishes($keyword);

			$products = array();
			if(is_array($dishes) && count($dishes)>0)
			{
				foreach ($dishes as $key => $value)
				{
					if(!array_key_exists($value->category_id,$products))
					{
						$products[$value->category_id] = array(
							"category_id" => $value->category_id,
							"category_name" => $value->category_name,
							"category_name_ar" => $value->category_name_ar,
							"category_image" => base_url().'assets/uploads/category/'.$value->image,
							"dishes" => array()
						);
					}

					$products[$value->category_id]['dishes'][] = array(
						"dish_id" => $value->product_id,
						"dish_name" => $value->name,
						"product_ar_name" => $value->product_ar_name,
						"dish_desc" => $value->description,
						"dish_price" => $value->price,
						"dish_image" => base_url().'assets/uploads/products/'.$value->product_image,
						"discount_type" => $value->discount_type,
						"discount" => $value->discount
					);
				}

				$response = array("response"=>"true","webservice_name"=>"searchDishes","message"=>$message_searchDishes,"dishes"=>$products);
			}
			else
			{
				$response = array("response"=>"false","webservice_name"=>"searchDishes","message"=>$cat_dish_not_found);
			}
		}
		$this->evaluateExecutionTime($startTime,'searchDishes',$this->input->post());
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : add product to cart
	 * Created by : Rashmi Nayani
	 * Created Date: 30/10/17 05:00 PM 
	*/ 
	function saveProducttoCart()
	{
		if(trim($this->input->post('user_id'))==""){

			$response = array("response"=>"false","message"=>"User id is required!");
		}
		else if(trim($this->input->post('access_token'))==""){

			$response = array("response"=>"false","message"=>"Access Token is required!");
		}
		else if(trim($this->input->post('total_price'))==""){

			$response = array("response"=>"false","message"=>"Total price is required!");
		}
		else if(trim($this->input->post('order_type'))==""){

			$response = array("response"=>"false","message"=>"Order type is required!");
		}
		else if(trim($this->input->post('delivery_address_id'))==""){

			$response = array("response"=>"false","message"=>"Delivery address id is required!");
		}
		else if(trim($this->input->post('customer_lat'))==""){

			$response = array("response"=>"false","message"=>"Customer Latitude is required!");
		}
		else if(trim($this->input->post('customer_long'))==""){

			$response = array("response"=>"false","message"=>"Customer Longitude is required!");
		}
		else if(trim($this->input->post('dishes'))==""){

			$response = array("response"=>"false","message"=>"Dish data is required!");
		}
		else if(trim($this->input->post('restaurant_id'))==""){

			$response = array("response"=>"false","message"=>"Restaurnet id data is required!");
		}
		else
		{
			$userId				= trim($this->input->post('user_id'));
			$accessToken 		= trim($this->input->post("access_token"));
			$totalPrice 		= trim($this->input->post("total_price"));
			$orderType 			= trim($this->input->post("order_type"));
			$addressId 			= trim($this->input->post("delivery_address_id"));
			$delivery_charges 	= trim($this->input->post("delivery_charges"));
			$customer_lat 		= trim($this->input->post("customer_lat"));
			$customer_long 		= trim($this->input->post("customer_long"));
			$orderId     		= trim($this->input->post("order_id"));
			$dishes 			= trim($this->input->post("dishes"));
			$restaurantId 		= trim($this->input->post("restaurant_id"));
			$specialInstruction = trim($this->input->post("special_instruction"));
			$error 				= "";
			$error1 			= "";
			$totalAmount 		= 0;

			$token 	= $this->checkAccessToken($userId,$accessToken);
		
			if($accessToken===$token){

				$account = $this->Webservice_customer_model->chkAccountDelete($userId);
				if(is_array($account) && count($account)>0){

					$response = array("response"=>"false","message"=>"Your account was deleted!!!","account_status"=>"inactive","webservice_name"=>"saveProducttoCart");
				}
				else{

					$dishData = json_decode($dishes);
					
					foreach ($dishData as $key => $value) {

						$productId 		 = $value->product_id;
						$quantity 		 = $value->quantity;
						$amount 		 = $value->amount;
						$choice 		 = ($value->choice)?($value->choice):"";
						$totalDishPrice1 = 0;
						
						if($productId == "" || $productId == 0){
							
							$error = "Product id is required";
							
						}
						elseif($quantity == "" || $quantity == 0){
				
							$error = "Quantity is required";
							
						}
						else if($amount == "" || $amount == 0){
							
							$error = "Dish amount is required!";
							
						}
						else if(!empty($choice)){

							$productDetails = $this->Webservice_customer_model->getDishDetail($productId,$restaurantId);

							if(!empty($productDetails) || count($productDetails)>0){

								$dishChoices   	 	=$productDetails[0]->choice_id;
								$dishPrice 			=$productDetails[0]->dish_price;
								$disheChoicesPrices =$productDetails[0]->choice_price;
								if($dishChoices == ''){
									$error = "choises are not available";
								}else{
									$dishChoices 		=explode(',',$dishChoices);
									$disheChoicesPrices =explode(',',$disheChoicesPrices);
									foreach ($choice as $key => $val) {

										if ($val->choice_id == "" || $val->choice_id ==  0) {

											$error = "Choice id is required!";
										}
										else{

											if(in_array($val->choice_id, $dishChoices)){

												$key             = array_search($val->choice_id,$dishChoices);
												$choicePrice     =$disheChoicesPrices[$key];
												$totalDishPrice1 =$totalDishPrice1 + $choicePrice;
												$arr['choicePrice'][]           =$totalDishPrice1;
											}else{
												$error ="Choice id is considered as invalid!";
											}

										}
									}
								}
							
								$totalAmount = $totalAmount + $quantity * ($totalDishPrice1+$productDetails[0]->dish_price);
								
							}else{
								$response = array("response"=>"false","message"=>"Dish Not Found!!!","webservice_name"=>"saveProducttoCart");
								echo json_encode($response);
								exit;
							}
						
						}else{
							$error = "Choice id is required!";
						}
					}
					
					$getLocalityData  =$this->Webservice_customer_model->getLocalityData($addressId);
					$orderData['user_id'] 					= $userId;
					$orderData['restaurant_id'] 			= $getLocalityData[0]->restaurant_id;
					$orderData['total_price'] 				= $totalPrice + $getLocalityData[0]->delivery_charge;
					$orderData['delivery_charges'] 		    = $getLocalityData[0]->delivery_charge;
					$orderData['order_type'] 				= $orderType;
					$orderData['special_instruction']   	= $specialInstruction;
					$orderData['selected_delivery_address'] = $addressId;
					$orderData['order_placed_time']         = date("Y-m-d H:i:s");
					
					if(isset($getLocalityData) && count($getLocalityData)>0){
						$timestamp = strtotime(date("Y-m-d H:i:s")) + 60*$getLocalityData[0]->delivered_time;
					}else{
						$timestamp = strtotime(date("Y-m-d H:i:s")) + 60*60;
					}
					$orderData['expected_delivery_time']    = date('Y-m-d H:i:s', $timestamp);
					$orderData['order_status'] 				= 0;
					$orderData['created_by'] 				= $userId;
					$tableName1 							= 'tbl_orders';

					if($orderId != "")
					{
						$orderData['updated_date'] 				= date("Y-m-d H:i:s");
						$res             = $this->Webservice_customer_model->updateData($orderData,$orderId);

						//DELETE PREVIOUS ORDER DISH  DETAILS AND DISH CHOICE
						$deleteOrderDetail 			= $this->Webservice_customer_model->deleteOrderDetailData($orderId);
						$deleteOrderChoiceDetail 	= $this->Webservice_customer_model->deleteOrderChoiceData($orderId);
						$res =$orderId;
						
					}
					else
					{
						$orderData['created_date'] 				= date("Y-m-d H:i:s");
						$res = $this->Webservice_customer_model->insertData($tableName1,$orderData);
					}

					//INSERT ORDER DISH  DETAILS AND DISH CHOICE
					foreach ($dishData as $key => $value) {

						$productId 		= $value->product_id;
						$quantity 		= $value->quantity;
						$amount 		= $value->amount;
						$description 	= ($value->description)?($value->description):"";
						$discountType 	= ($value->discount_type)?($value->discount_type):"";
						$discountAmount = ($value->discount_amount)?($value->discount_amount):"";
						$choice 		= ($value->choice)?($value->choice):"";

						$orderDetails['order_id'] 			= $res;
						$orderDetails['product_id'] 		= $productId;
						$orderDetails['quantity'] 			= $quantity;
						$orderDetails['amount'] 			= $amount;
						$orderDetails['description'] 		= $description;
						$orderDetails['discount_type']		= $addressId;
						$orderDetails['created_date'] 		= date("Y-m-d H:i:s");
						$orderDetails['created_by'] 		= $userId;
						$tableName2 						= 'tbl_order_details';
						$res1 = $this->Webservice_customer_model->insertData($tableName2,$orderDetails);
						if (!empty($choice)) {

							foreach ($choice as $key => $val) {

								$choiceId 	= $val->choice_id;

								$choiceDataa['fk_order_detail_id'] 	= $res1;
								$choiceDataa['fk_order_id'] 		= $res;
								$choiceDataa['fk_dish_id'] 			= $productId;
								$choiceDataa['fk_choice_id'] 		= $choiceId;
								$choiceDataa['created_date'] 		= date("Y-m-d H:i:s");
								$choiceDataa['created_by'] 			= $userId;
								$tableName3 						= 'tbl_order_dish_choice';
								
								$res2 = $this->Webservice_customer_model->insertData($tableName3,$choiceDataa);
								if (sizeof($res2) == 0) {
									$error1 = "Failed to save dish choice data";
								}
							}
						}
						if (sizeof($res1) == 0) {

							$error1 = "Failed to save dish data";
						}
					}

					if (sizeof($res) == 0 ||  sizeof($res1) == 0) {

						$error1 = "Failed to save order details";
					}

					if ($error1) {
						$response = array("response"=>"false","webservice_name"=>"saveProducttoCart","message"=>$error1);
					}
					else{

						$orderDetailData  = $this->Webservice_customer_model->getOrderData($res);
						
						$response     = array("response"=>"true","webservice_name"=>"saveProducttoCart","message"=>"Order Placed Successfully","data"=>array('order_id'=>$res),"order_data"=>$orderDetailData);
						
					}
				}
			}
			else{
				$response = array("response"=>"false","access"=>"false","message"=>"Access token mismatched!!!","webservice_name"=>"saveProducttoCart");
			}
		}
		echo json_encode($response);
		exit;
	}


	/**
	 * Description : Get Customer order details
	 * Created by :Rashmi Nayani
	 * Created Date: 30/10/17 12:20 PM 
	*/
	function getMyorders()
	{
		$startTime = $this->benchmark->mark('code_start');
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
		$message_getMyorders 		= $this->lang->line('message_getMyorders');
		$account_deleted 			= $this->lang->line('account_deleted');
		$token_mismatched 			= $this->lang->line('token_mismatched');
		$filed_to_fetch_order 		= $this->lang->line('filed_to_fetch_order');
		$role_required 				= $this->lang->line('role_required');
		$token_required 			= $this->lang->line('token_required');
		$userid_required 			= $this->lang->line('userid_required');
		$default_language_required 	= $this->lang->line('default_language_required');

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
				$account = $this->Webservice_customer_model->chkAccountDelete($userId);
				if(is_array($account) && count($account)>0)
				{
					$response = array("response"=>"false","message"=>$account_deleted,"account_status"=>"inactive","webservice_name"=>"getMyorders");
				}
				else
				{
					$orderDetail = $this->Webservice_customer_model->getMyOrders($userId);
					//echo "<pre>"; print_r($orderDetail);exit;
					if(is_array($orderDetail) && count($orderDetail)>0){
						$k = 0;
						foreach ($orderDetail as $key => $value) {
							
							$orderDetails[$value->order_id]['order_id'] 				= $value->sequence_no;
							$orderDetails[$value->order_id]['order_status'] 			= $value->order_status;
							$orderDetails[$value->order_id]['replace_by'] 			    = $value->order_refer_by;
							$orderDetails[$value->order_id]['restaurant_id'] 			= $value->restaurant_id;
							$orderDetails[$value->order_id]['order_placed_time'] 		= $value->order_placed_time;
							$orderDetails[$value->order_id]['expected_delivery_time'] 	= $value->expected_delivery_time;
							$orderDetails[$value->order_id]['order_confirmed_time'] 	= $value->order_confirmed_time;
							$orderDetails[$value->order_id]['total_price'] 				= $value->total_price;
							$orderDetails[$value->order_id]['description'] 	            = $value->description;
							$orderDetails[$value->order_id]['restaurant_id'] 			= $value->restaurant_id;
							$orderDetails[$value->order_id]['payment_type'] 			= $value->payment_type;
							$orderDetails[$value->order_id]['headline'] 			= $value->restaurant_name;
							$orderDetails[$value->order_id]['restaurant_address'] 		= $value->res_address;
							$orderDetails[$value->order_id]['restaurant_email'] 		= $value->res_email;
							$orderDetails[$value->order_id]['restaurant_contact_no'] 	= $value->res_contact_no;
							$orderDetails[$value->order_id]['customer_latitude'] 		= $value->customer_latitude;
							$orderDetails[$value->order_id]['customer_longitude'] 		= $value->customer_longitude;
							$orderDetails[$value->order_id]['customer_name'] 			= $value->customer_name;
							$orderDetails[$value->order_id]['customer_contact_no'] 		= $value->usr_contact_no;
							$orderDetails[$value->order_id]['customer_email'] 			= $value->usr_email;
							$orderDetails[$value->order_id]['zipcode'] 					= $value->zipcode;
							$fullAddress =($value->appartment_no !="" || $value->appartment_no !=null)?$value->appartment_no.",":"";
							$fullAddress .=($value->floor!="" || $value->floor!=null)?"Floor -".$value->floor.",":"";
							$fullAddress .=($value->block!="" || $value->block!=null)?"Block -".$value->block.",":"";
							$fullAddress .=($value->building !="" ||$value->building !=null)?"Building -".$value->building.",":"";
							$fullAddress .=($value->street !="")?$value->street.',':'';
							$fullAddress .=($value->avenue !="" || $value->avenue !=null)?$value->avenue.',':'';
							$fullAddress .=($value->usr_address!="")?$value->usr_address:"";

							$orderDetails[$value->order_id]['delivery_address'] 		  = $fullAddress;
							$orderDetails[$value->order_id]['other_address'] 		  	  = $value->other_address;
							$orderDetails[$value->order_id]['delivered_by'] 			  = $value->delivered_by;
							$orderDetails[$value->order_id]['driver_first_name'] 		  = $value->driver_first_name;
							$orderDetails[$value->order_id]['driver_last_name'] 		  = $value->driver_last_name;
							$orderDetails[$value->order_id]['driver_contact_no'] 		  = $value->driver_contact_no;

							$orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['product_id']  = $value->product_id;
							$orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['dish_name']  = $value->name;
							$orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['product_ar_name']  = $value->product_ar_name;
							$orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['quantity']  = $value->quantity;
							$orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['base_price']  = $value->amount;
							$orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['description']  = $value->od_desc;
							
							if ($value->choice_id != "") {
					    	  	$orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['choice'][$value->choice_id]['choice_id'] = $value->choice_id;
							    $orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['choice'][$value->choice_id]['choice_price'] = $value->choice_price;
							    $orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['choice'][$value->choice_id]['choice_name'] = $value->choice_name;
							    $orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['choice'][$value->choice_id]['choice_name_ar'] = $value->choice_name_ar;
							    $orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['choice'][$value->choice_id]['choice_description'] = $value->choice_description;
							    $orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['choice'][$value->choice_id]['choice_category_name'] = $value->choice_category_name;
						    }
						    $k++;
						}
						//print_r($orderDetails);exit;
						$i = 0;
						$z = 0;
						foreach ($orderDetails as $key => $value) {
							
							$orderData[$i]['order_id'] 					= $value['order_id'];
							$orderData[$i]['order_status'] 				= $value['order_status'];
							$orderData[$i]['replace_by'] 				= $value['replace_by'];
							$orderData[$i]['restaurant_id'] 	    	= $value['restaurant_id'];
							$orderData[$i]['order_placed_time'] 		= $value['order_placed_time'];
							$orderData[$i]['expected_delivery_time'] 	= $value['expected_delivery_time'];
							$orderData[$i]['order_confirmed_time'] 		= $value['order_confirmed_time'];
							$orderData[$i]['current_time'] 		        = date('Y-m-d H:i:s');
							$orderData[$i]['total_price'] 				= $value['total_price'];
							$orderData[$i]['description'] 				= $value['description'];
							$orderData[$i]['payment_type'] 				= $value['payment_type'];
							$orderData[$i]['restaurant_name'] 			= $value['restaurant_name'];
							$orderData[$i]['restaurant_address'] 		= $value['restaurant_address'];
							$orderData[$i]['restaurant_email'] 			= $value['restaurant_email'];
							$orderData[$i]['restaurant_contact_no'] 	= $value['restaurant_contact_no'];
							$orderData[$i]['delivery_address'] 			= $value['delivery_address'];
							$orderData[$i]['other_address'] 			= $value['other_address'];
							$orderData[$i]['customer_name'] 			= $value['customer_name'];
							$orderData[$i]['customer_contact_no'] 		= $value['customer_contact_no'];
							$orderData[$i]['customer_email'] 			= $value['customer_email'];
							$orderData[$i]['customer_latitude'] 			= $value['customer_latitude'];
							$orderData[$i]['customer_longitude'] 			= $value['customer_longitude'];
							$orderData[$i]['zipcode'] 					= $value['zipcode'];
							$orderData[$i]['country'] 		    		= $value['country'];
							$orderData[$i]['state'] 					= $value['state'];
							$orderData[$i]['city'] 		    			= $value['city'];
							$orderData[$i]['delivered_by'] 		    	= $value['delivered_by'];
							$orderData[$i]['driver_first_name'] 		= $value['driver_first_name'];
							$orderData[$i]['driver_last_name']			= $value['driver_last_name'];
							$orderData[$i]['driver_contact_no']			= $value['driver_contact_no'];
						
							$j =0;
							foreach ($value['dishes'] as $key => $val) {

								//for($kkey = 0; $kkey < sizeof($val); $kkey++){
									$orderData[$i]['dishes'][$j]['product_id'] 		= $val['product_id'];
									$orderData[$i]['dishes'][$j]['dish_name'] 		= $val['dish_name'];
									$orderData[$i]['dishes'][$j]['product_ar_name'] = $val['product_ar_name'];
									$orderData[$i]['dishes'][$j]['quantity'] 		= $val['quantity'];
									$orderData[$i]['dishes'][$j]['base_price'] 		= $val['base_price'];
									$orderData[$i]['dishes'][$j]['description'] 	= $val['description'];

									$k=0;
									foreach ($val['choice'] as $key => $va) {
										$productDetails = $this->Webservice_customer_model->getDishDetail($val['product_id'],$value['restaurant_id']);
										$dishChoices   	 	=$productDetails[0]->choice_id;
										$dishPrice 			=$productDetails[0]->dish_price;
										$disheChoicesPrices =$productDetails[0]->choice_price;
										$dishChoices 		=explode(',',$dishChoices);
										$disheChoicesPrices =explode(',',$disheChoicesPrices);
										$key                = array_search($va['choice_id'],$dishChoices);
										$choicePrice       =$disheChoicesPrices[$key];

										$orderData[$i]['dishes'][$j]['choice'][$k]['choice_id'] 		  = $va['choice_id'];
										$orderData[$i]['dishes'][$j]['choice'][$k]['choice_price'] 		  = $choicePrice;
										$orderData[$i]['dishes'][$j]['choice'][$k]['choice_name'] 		  = $va['choice_name'];
										$orderData[$i]['dishes'][$j]['choice'][$k]['choice_name_ar'] 	  = $va['choice_name_ar'];
										$orderData[$i]['dishes'][$j]['choice'][$k]['choice_description']  = $va['choice_description'];
										$orderData[$i]['dishes'][$j]['choice'][$k]['choice_category_name']= $va['choice_category_name'];
										$k++;
									}
									$j++;
								//}
							}
							$i++;
						}
						$response = array("response"=>"true","webservice_name"=>"getMyorders","message"=>$message_getMyorders,"orders"=>$orderData);
					}
					else{

						$response = array("response"=>"false","webservice_name"=>"getMyorders","message"=>$filed_to_fetch_order);
					}
				
				}
			}
			else
			{
				$response = array("response"=>"false","access"=>"false","message"=>$token_mismatched,"webservice_name"=>"getMyorders");
			}
		}
		$this->evaluateExecutionTime($startTime,'getMyorders',$this->input->post());
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : Get customer order details
	 * Created by  :Rashmi Nayani
	 * Created Date: 2/11/17 3:30 PM 
	*/
	function getOrderDetails()
	{
		$startTime = $this->benchmark->mark('code_start');
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
		$message_getOrderDetails 		= $this->lang->line('message_getOrderDetails');
		$filed_to_fetch_order_detail 	= $this->lang->line('filed_to_fetch_order_detail');
		$account_deleted 				= $this->lang->line('account_deleted');
		$token_mismatched 				= $this->lang->line('token_mismatched');
		$token_required 				= $this->lang->line('token_required');
		$userid_required 				= $this->lang->line('userid_required');
		$order_id_required 				= $this->lang->line('order_id_required');
		$default_language_required 		= $this->lang->line('default_language_required');
		
		if(trim($this->input->post('user_id'))==""){
			$response = array("response"=>"false","message"=>$userid_required);
		}
		else if(trim($this->input->post('access_token'))==""){
			$response = array("response"=>"false","message"=>$token_required);
		}
		else if(trim($this->input->post('order_id'))==""){
			$response = array("response"=>"false","message"=>$order_id_required);
		}
		else if(trim($this->input->post('default_language'))==""){
			$response = array("response"=>"false","message"=>$default_language_required);
		}
		else{

			$userId 		= trim($this->input->post('user_id'));
			$orderId 		= trim($this->input->post('order_id'));
			$accessToken 	= trim($this->input->post("access_token"));

			$token 			= $this->checkAccessToken($userId,$accessToken);

			$orderIdData 	= $this->Home_model->getOrderIdFromSequenceNo($orderId);
			$orderId 		= $orderIdData->order_id;

			if($accessToken===$token){
				$account = $this->Webservice_customer_model->chkAccountDelete($userId);
				if(is_array($account) && count($account)>0)
				{
					$response = array("response"=>"false","message"=>$account_deleted,"account_status"=>"inactive","webservice_name"=>"getOrderDetails");
				}
				else
				{
					$orderDetail = $this->Webservice_customer_model->getMyOrders($userId,$orderId);

					$DriverRating = $this->Webservice_customer_model->getRating($orderId,'tbl_driver_ratings');
					$RestauratRating = $this->Webservice_customer_model->getRating($orderId,'tbl_restaurant_ratings');

					if(is_array($orderDetail) && count($orderDetail)>0){
						$k = 0;
						$i = 0;
						foreach ($orderDetail as $key => $value) {
							$otherAddress = "";
							if($value->address_type == 1)
							{
								$otherAddress = "Home";
							}
							else if($value->address_type == 2)
							{
								$otherAddress = "Office";
							}
							else if($value->address_type == 3)
							{
								$otherAddress = $value->other_address;
							}
							/*echo "test:".$otherAddress;
							exit;*/
							$orderDetails[$value->order_id]['order_id'] 				= $value->order_id;
							$orderDetails[$value->order_id]['order_status'] 			= $value->order_status;
							$orderDetails[$value->order_id]['special_instruction'] 		= $value->special_instruction;
							$orderDetails[$value->order_id]['order_placed_time'] 		= $value->order_placed_time;
							$orderDetails[$value->order_id]['expected_delivery_time'] 	= $value->expected_delivery_time;
							$orderDetails[$value->order_id]['description'] 	            = $value->description;
							$orderDetails[$value->order_id]['current_time'] 			= date('Y-m-d H:i:s');
							$orderDetails[$value->order_id]['total_price'] 				= $value->total_price;
							$orderDetails[$value->order_id]['payment_type'] 			= $value->payment_type;
							$orderDetails[$value->order_id]['restaurant_id'] 			= $value->restaurant_id;
							$orderDetails[$value->order_id]['restaurant_name'] 			= $value->restaurant_name;
							$orderDetails[$value->order_id]['restaurant_address'] 		= $value->res_address;
							$orderDetails[$value->order_id]['restaurant_email'] 		= $value->res_email;
							$orderDetails[$value->order_id]['restaurant_contact_no'] 	= $value->res_contact_no;
							$orderDetails[$value->order_id]['restaurant_latitude'] 		= $value->res_latitude;
							$orderDetails[$value->order_id]['restaurant_longitude'] 	= $value->res_longitude;
							$orderDetails[$value->order_id]['custom_delivery_time'] 	= "";
							$orderDetails[$value->order_id]['delivery_charge']      	= $value->delivery_charges;
							$orderDetails[$value->order_id]['delivery_address'] 		= $value->usr_address;
							$orderDetails[$value->order_id]['other_address'] 		    = $otherAddress;
							$orderDetails[$value->order_id]['customer_name'] 			= $value->customer_name;
							$orderDetails[$value->order_id]['customer_contact_no'] 		= $value->usr_contact_no;
							$orderDetails[$value->order_id]['customer_email'] 			= $value->usr_email;
							$orderDetails[$value->order_id]['customer_latitude'] 		= $value->customer_latitude;
							$orderDetails[$value->order_id]['customer_longitude'] 		= $value->customer_longitude;
							$orderDetails[$value->order_id]['zipcode'] 					= $value->zipcode;
							$orderDetails[$value->order_id]['country'] 					= "";
							$orderDetails[$value->order_id]['state'] 					= "";
							$orderDetails[$value->order_id]['city'] 					= "";
							$orderDetails[$value->order_id]['delivered_by'] 			= $value->delivered_by;
							$orderDetails[$value->order_id]['driver_first_name'] 		= $value->driver_first_name;
							$orderDetails[$value->order_id]['driver_last_name'] 		= $value->driver_last_name;
							$orderDetails[$value->order_id]['driver_contact_no'] 		= $value->driver_contact_no;
							
						    $orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['product_id']  = $value->product_id;
						    $orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['quantity']  = $value->quantity;
						    $orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['amount']  = $value->amount;
						    $orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['description']  = $value->od_desc;
						    $orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['discount_type']  = $value->discount_type;
						    $orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['discount_amount']  = $value->discount_amount;
						    $orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['name']  = $value->name;
						    $orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['product_ar_name']  = $value->product_ar_name;
						    $orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['price']  = $value->choice_price;
						    $orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['dish_image']  = $value->dish_image;
						    $orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['order_detail_id']  = $value->order_detail_id;

						    if ($value->choice_id != "") {

					    	  	$orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['choice'][$value->choice_id]['choice_id'] = $value->choice_id;
							    $orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['choice'][$value->choice_id]['choice_price'] = $value->choice_price;
							    $orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['choice'][$value->choice_id]['choice_name'] = $value->choice_name;
							    $orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['choice'][$value->choice_id]['choice_name_ar'] = $value->choice_name_ar;
							    $orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['choice'][$value->choice_id]['choice_description'] = $value->choice_description;
							    $orderDetails[$value->order_id]['dishes'][$value->order_detail_id]['choice'][$value->choice_id]['choice_category_name'] = $value->choice_category_name;
							     
						    }
						    $k++;
						}

						$z = 0;
						foreach ($orderDetails as $key => $value) {

							$orderData['order_id'] 				= $value['order_id'];
							$orderData['order_status'] 			= $value['order_status'];
							$orderData['special_instruction'] 	= $value['special_instruction'];
							$orderData['order_placed_time'] 	= $value['order_placed_time'];
							$orderData['expected_delivery_time']= $value['expected_delivery_time'];
							$orderData['order_confirmed_time'] 	= $value['order_confirmed_time'];
							$orderData['current_time'] 			= date('Y-m-d H:i:s');
							$orderData['total_price'] 			= $value['total_price']-$value['delivery_charge'];
							$orderData['description'] 			= $value['description'];
							$orderData['payment_type'] 			= $value['payment_type'];
							$orderData['restaurant_id'] 		= $value['restaurant_id'];
							$orderData['restaurant_name'] 		= $value['restaurant_name'];
							$orderData['restaurant_address'] 	= $value['restaurant_address'];
							$orderData['restaurant_email'] 		= $value['restaurant_email'];
							$orderData['restaurant_contact_no'] = $value['restaurant_contact_no'];
							$orderData['restaurant_latitude'] 	= $value['restaurant_latitude'];
							$orderData['restaurant_longitude'] 	= $value['restaurant_longitude'];
							$orderData['custom_delivery_time'] 	= $value['custom_delivery_time'];
							$orderData['delivery_charge'] 	    = $value['delivery_charge'];
							$orderData['delivery_address'] 		= $value['delivery_address'];
							$orderData['other_address'] 		= $value['other_address'];
							$orderData['customer_name'] 		= $value['customer_name'];
							$orderData['customer_contact_no'] 	= $value['customer_contact_no'];
							$orderData['customer_email'] 		= $value['customer_email'];
							$orderData['customer_latitude'] 	= $value['customer_latitude'];
							$orderData['customer_longitude'] 	= $value['customer_longitude'];
							$orderData['zipcode'] 				= $value['zipcode'];
							$orderData['country'] 		    	= $value['country'];
							$orderData['state'] 				= $value['state'];
							$orderData['city'] 		    		= $value['city'];
							$orderData['delivered_by'] 		    = $value['delivered_by'];
							$orderData['driver_first_name'] 	= $value['driver_first_name'];
							$orderData['driver_last_name']		= $value['driver_last_name'];
							$orderData['driver_contact_no']		= $value['driver_contact_no'];


							if(count($DriverRating)>0){
								$orderData['rating']['driver']['rating_id'] = $DriverRating[0]->rating_id;
								$orderData['rating']['driver']['driver_id'] = $DriverRating[0]->driver_id;
								$orderData['rating']['driver']['user_id']   = $DriverRating[0]->user_id;
								$orderData['rating']['driver']['rating']    = $DriverRating[0]->rating;
								$orderData['rating']['driver']['reason']    = $DriverRating[0]->reason;
							}else
							{
								$orderData['rating']['driver'] = null;
							}
							if(count($RestauratRating)>0){
								$orderData['rating']['restaurant']['rating_id'] = $RestauratRating[0]->rating_id;
								$orderData['rating']['restaurant']['restaurant_id'] = $RestauratRating[0]->restaurant_id;
								$orderData['rating']['restaurant']['user_id'] = $RestauratRating[0]->user_id;
								$orderData['rating']['restaurant']['rating'] = $RestauratRating[0]->rating;
								$orderData['rating']['restaurant']['reason'] = $RestauratRating[0]->reason;
							}
							else{
								$orderData['rating']['restaurant'] = null;	
							}

							$j =0;
							foreach ($value['dishes'] as $key => $val) {

								//for($kkey = 0; $kkey < sizeof($val); $kkey++){
								/*if(($orderData['dishes'][$j]['order_detail_id']!=$val['order_detail_id']) && $j!=0) {
									//$j++;
									continue;
								}*/
									$orderData['dishes'][$j]['product_id'] 		= $val['product_id'];
									$orderData['dishes'][$j]['order_detail_id'] = $val['order_detail_id'];
									$orderData['dishes'][$j]['quantity'] 		= $val['quantity'];
									$orderData['dishes'][$j]['amount'] 			= $val['amount'];
									$orderData['dishes'][$j]['description'] 	= $val['description'];
									$orderData['dishes'][$j]['discount_type'] 	= $val['discount_type'];
									$orderData['dishes'][$j]['discount_amount'] = $val['discount_amount'];
									$orderData['dishes'][$j]['name'] 			= $val['name'];
									$orderData['dishes'][$j]['ar_name'] 		= $val['product_ar_name'];
									$orderData['dishes'][$j]['description'] 	= $val['description'];
									$orderData['dishes'][$j]['price'] 			= number_format($val['amount']/$val['quantity'],2,".",".");
									$orderData['dishes'][$j]['dish_image'] 		= base_url().'assets/uploads/products/'.$val['dish_image'];

									$k=0;
									/*if(($orderData['dishes'][$j]['order_detail_id']!=$val['order_detail_id']) && $j!=0) {
										//$j++;
										continue;
									}*/
									foreach ($val['choice'] as $key => $va) {
										$productDetails = $this->Webservice_customer_model->getDishDetail($val['product_id'],$val['restaurant_id']);
										$dishChoices   	 	=$productDetails[0]->choice_id;
										$dishPrice 			=$productDetails[0]->dish_price;
										$disheChoicesPrices =$productDetails[0]->choice_price;
										$dishChoices 		=explode(',',$dishChoices);
										$disheChoicesPrices =explode(',',$disheChoicesPrices);
										$key             = array_search($va['choice_id'],$dishChoices);
										$choicePrice     =$disheChoicesPrices[$key];
										$orderData['dishes'][$j]['choice'][$k]['choice_id'] 		  = $va['choice_id'];
										$orderData['dishes'][$j]['choice'][$k]['choice_price'] 		  = $choicePrice;
										$orderData['dishes'][$j]['choice'][$k]['choice_name'] 		  = $va['choice_name'];
										$orderData['dishes'][$j]['choice'][$k]['choice_name_ar'] 	  = $va['choice_name_ar'];
										$orderData['dishes'][$j]['choice'][$k]['choice_description']  = $va['choice_description'];
										$orderData['dishes'][$j]['choice'][$k]['choice_category_name']= $va['choice_category_name'];

										$k++;
									}	
									//$z++;
									$j++;
								//}
							}
						}

						$response = array("response"=>"true","webservice_name"=>"getOrderDetails","message"=>$message_getOrderDetails,"orderDetail"=>$orderData);
					
					}
					else{
						$response = array("response"=>"false","webservice_name"=>"getOrderDetails","message"=>$filed_to_fetch_order_detail);
					}
				
				}
			}
			else
			{
				$response = array("response"=>"false","access"=>"false","message"=>$token_mismatched,"webservice_name"=>"getOrderDetails");
			}
		}
		$this->evaluateExecutionTime($startTime,'getOrderDetails',$this->input->post());
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : add customer order rate 
	 * Created by : Rashmi Nayani
	 * Created Date: 3/11/17 05:00 PM 
	*/ 
	function addCustomerOrderRate()
	{
		$startTime = $this->benchmark->mark('code_start');
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

		$message_addCustomerOrderRate 	= $this->lang->line('message_addCustomerOrderRate');
		$account_deleted 				= $this->lang->line('account_deleted');
		$default_language_required 		= $this->lang->line('default_language_required');
		$token_mismatched 				= $this->lang->line('token_mismatched');
		$userid_required 				= $this->lang->line('userid_required');
		$token_required 				= $this->lang->line('token_required');
		$order_id_required 				= $this->lang->line('order_id_required');
		$rating_required  				= $this->lang->line('rating_required');
		$comment_required 				= $this->lang->line('comment_required');
		$message_addResDriverRating 	= $this->lang->line('message_addResDriverRating');
		$failed_rating 					= $this->lang->line('failed_rating');

		if(trim($this->input->post('user_id'))==""){
			$response = array("response"=>"false","message"=>$userid_required);
		}
		else if(trim($this->input->post('access_token'))==""){
			$response = array("response"=>"false","message"=>$token_required);
		}
		else if(trim($this->input->post('order_id'))==""){
			$response = array("response"=>"false","message"=>$order_id_required);
		}
		else if(trim($this->input->post('rating'))==""){
			$response = array("response"=>"false","message"=>$rating_required);
		}
		else if(trim($this->input->post('comment'))==""){
			$response = array("response"=>"false","message"=>$comment_required 	);
		}
		else if(trim($this->input->post('is_driver_rating'))==""){
			$response = array("response"=>"false","message"=>$driver_rating_required);
		}
		else if(trim($this->input->post('default_language'))==""){
			$response = array("response"=>"false","message"=>$default_language_required);
		}
		else
		{
			$userId				= trim($this->input->post('user_id'));
			$accessToken 		= trim($this->input->post("access_token"));
			$orderId 			= trim($this->input->post("order_id"));
			$Rating 			= trim($this->input->post("rating"));
			$Comment  			= trim($this->input->post("comment"));
			$isDriverRating 	= trim($this->input->post('is_driver_rating'));
			$error 				= "";
			
			$orderData 			= $this->Home_model->getOrderIdFromSequenceNo($orderId);
			$orderId 			= $orderData->order_id;

			$token = $this->checkAccessToken($userId,$accessToken);
		
			if($accessToken===$token){
				$account = $this->Webservice_customer_model->chkAccountDelete($userId);

				if(is_array($account) && count($account)>0){

					$response = array("response"=>"false","message"=>$account_deleted,"account_status"=>"inactive","webservice_name"=>"addMyOrderRate");
				}
				else{

					$orderDetails = $this->Webservice_customer_model->getMyOrders($userId,$orderId);

					//print_r($orderDetails);exit;
						$ratingData['user_id'] 		= $userId;
						$ratingData['order_id'] 	= $orderId;
						$ratingData['rating'] 		= $Rating;
						$ratingData['reason'] 		= $Comment;
						$ratingData['created_by'] 	= $userId;
						$ratingData['created_date'] = date("Y-m-d H:i:s");
					
					if ($isDriverRating == 1 ) {
						$ratingData['driver_id'] 		= $orderDetails[0]->delivered_by;
						$tableName1 				= 'tbl_driver_ratings';
						$message = $message_addResDriverRating;
					}
					else{
						$ratingData['restaurant_id'] 	= $orderDetails[0]->restaurant_id;
						$tableName1 				= 'tbl_restaurant_ratings';
						$message = $message_addCustomerOrderRate;
					}
						
						$res = $this->Webservice_customer_model->insertData($tableName1,$ratingData);	

					if ($res>0) {

						$ratingData['rating_id'] = $res;
						$response=array("response"=>"true","data"=>$ratingData,"message"=>$message,"webservice_name"=>"addMyOrderRate");
					}
					else{
						$response=array("response"=>"false","data"=>$ratingData,"message"=>$failed_rating,"webservice_name"=>"addMyOrderRate");
					}
				}
			}
			else{

				$response = array("response"=>"false","access"=>"false","message"=>$token_mismatched,"webservice_name"=>"addMyOrderRate");
			}
		}
		$this->evaluateExecutionTime($startTime,'addMyOrderRate',$this->input->post());
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : customer change password request
	 * Created by : Rashmi Nayani
	 * Created Date: 4/11/17 11:15 PM 
	*/
	function changePassword()
	{
		$startTime = $this->benchmark->mark('code_start');
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
				$account = $this->Webservice_customer_model->chkAccountDelete($userId);

				if(is_array($account) && count($account)>0){

					$response = array("response"=>"false","message"=>"Your account was deleted!!!","account_status"=>"inactive","webservice_name"=>"addMyOrderRate");
				}
				else{

					$oldPassword	= md5(trim($this->input->post('old_password')));


					$chkedPassword = $this->Webservice_customer_model->checkPassword($userId,$oldPassword);

					$toEmail 		= $chkedPassword['email'];
					$toName 		= $chkedPassword['first_name'];

					if(is_array($chkedPassword) && count($chkedPassword)>0)
					{
						$userDetails['password'] = md5(trim($this->input->post('new_password')));

						$result 		= $this->Webservice_customer_model->updateUserData($userId,$userDetails);
						if( $result == 1 )
						{
							$data['email_template']		= 'update_password';
							$data['to_email']			= $toEmail;
							$data['name']				= $toName;
							$data['subject']			= 'Update Password';					

							$mails = $this->sendMail($data);
							$response = array("response" => "true" , "message" => $this->lang->line('message_passSuccUpdated'));					
						}
						else
						{
							$response = array("response" => "false" , "message" => "Your password is not updated.");
						}
					}
					else
					{
						$response = array("response" => "false" , "message" =>$this->lang->line('message_incorrectCurPass'));
					}
				}
			}
			else{

				$response = array("response"=>"false","access"=>"false","message"=>"Access token mismatched!!!","webservice_name"=>"addMyOrderRate");
			}
		}
		$this->evaluateExecutionTime($startTime,'getProductDetails',$this->input->post());
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : Get customer delivery address details
	 * Created by :Rashmi Nayani
	 * Created Date: 11/10/17 01:00 PM 
	*/
	function getDriverStatus()
	{
		$startTime 		= $this->benchmark->mark('code_start');
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
		$message_getDriverStatus 	= $this->lang->line('message_getDriverStatus');
		$driver_not_found 			= $this->lang->line('driver_not_found');
		$default_language_required 	= $this->lang->line('default_language_required');
		$token_mismatched 			= $this->lang->line('token_mismatched');
		$userid_required 			= $this->lang->line('userid_required');
		$token_required 			= $this->lang->line('token_required');
		$order_id_required 			= $this->lang->line('order_id_required');
		$account_deleted 			= $this->lang->line('account_deleted');

		if(trim($this->input->post('user_id'))==""){
			$response = array("response"=>"false","message"=>$userid_required);
		}
		else if(trim($this->input->post('access_token'))==""){
			$response = array("response"=>"false","message"=>$token_required);
		}
		else if(trim($this->input->post('order_id'))==""){
			$response = array("response"=>"false","message"=>$order_id_required);
		}
		else if(trim($this->input->post('default_language'))==""){
			$response = array("response"=>"false","message"=>$default_language_required);
		}
		else{

			$userId 		= trim($this->input->post('user_id'));
			$accessToken 	= trim($this->input->post("access_token"));
			$orderId 		= trim($this->input->post("order_id"));
			
			$token 			= $this->checkAccessToken($userId,$accessToken);

			if($accessToken===$token){
				$account = $this->Webservice_customer_model->chkAccountDelete($userId);
				if(is_array($account) && count($account)>0)
				{
					$response = array("response"=>"false","message"=>$account_deleted,"account_status"=>"inactive","webservice_name"=>"getDriverStatus");
				}
				else
				{
					$driverDetail = $this->Webservice_customer_model->getDriverDetails($orderId);

					if(is_array($driverDetail) && count($driverDetail)>0)
					{

						$driverStatus  = array(
						"order_id" 			=> $driverDetail[0]->order_id,
						"driver_first_name" => $driverDetail[0]->d_first_name,
						"driver_last_name" 	=> $driverDetail[0]->d_last_name,
						"contact_no" 		=> $driverDetail[0]->d_contact_no,
						"driver_latitude" 	=> $driverDetail[0]->driver_latitude,
						"driver_longitude"	=> $driverDetail[0]->driver_longitude
						);
					
						$response = array("response"=>"true","webservice_name"=>"getDriverStatus","message"=>$message_getDriverStatus,"data"=>$driverStatus);
					}
					else
					{
						$response = array("response"=>"false","webservice_name"=>"getDriverStatus","message"=>$driver_not_found);
					}
				}
			}
			else
			{
				$response = array("response"=>"false","access"=>"false","message"=>$token_mismatched,"webservice_name"=>"getDriverStatus");
			}
		}
		$this->evaluateExecutionTime($startTime,'getDriverStatus',$this->input->post());
		echo json_encode($response);
		exit;
	}

	function getPaymentLink()
	{
		$startTime 		= $this->benchmark->mark('code_start');
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
		//echo $lang;exit;
		$this->lang->load($langFile,$lang);
		$message_getPaymentLink 	= $this->lang->line('message_getPaymentLink');
		$driver_not_found 			= $this->lang->line('driver_not_found');
		$default_language_required 	= $this->lang->line('default_language_required');
		$token_mismatched 			= $this->lang->line('token_mismatched');
		$userid_required 			= $this->lang->line('userid_required');
		$token_required 			= $this->lang->line('token_required');
		$order_id_required 			= $this->lang->line('order_id_required');
		$amount_required 			= $this->lang->line('amount_required');
		$account_deleted 			= $this->lang->line('account_deleted');

		if($_REQUEST)
		{
			if(trim($_REQUEST['order_id']==''))
			{
				$response = array("response"=>"false","message"=>$order_id_required);
			}
			else if(trim($_REQUEST['amount'])=='')
			{
				$response = array("response"=>"false","message"=>$amount_required);
			}
			else
			{
				$oid 	= trim($_REQUEST['order_id']);
				$amount = trim($_REQUEST['amount']);
				$fields = array(
					"amount" 			=> $amount,
					"currency_code"		=> $this->config->item('currency'),
					"gateway_code" 		=> $this->config->item('gateway_code'),
					"order_no" 			=> $oid,
					"customer_email" 	=> $_REQUEST['customer_email'] ? trim($_REQUEST['customer_email']) : '',
					"disclosure_url" 	=> $this->config->item('disclosure_url'),
					"redirect_url" 		=> $this->config->item('redirect_url')
				);
				foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
				rtrim($fields_string, '&');
				$ch = curl_init();

				curl_setopt($ch,CURLOPT_URL,$this->config->item('disclosure_url'));
				curl_setopt($ch,CURLOPT_POST, count($fields));
				curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

				$result = curl_exec($ch);
				curl_close($ch);

				$response = array("response"=>"true","message"=>$message_getPaymentLink,"url"=>json_decode($result));
			}
		}
		else
		{
			$response = array("response"=>"false","message"=>$order_id_required,"webservice_name"=>"getPaymentLink");
		}
		$this->evaluateExecutionTime($startTime,'getDriverStatus',$this->input->post());
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : Get User details
	 * Created by : Vaibhav Mehta
	 * Created Date: 22/11/17 01:30 PM 
	*/ 
	function getUserDetails()
	{
		$startTime 		= $this->benchmark->mark('code_start');
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
		$message_getUserDetails 		= $this->lang->line('message_getUserDetails');
		$account_deleted 				= $this->lang->line('account_deleted');
		$userid_required 				= $this->lang->line('userid_required');
		$user_details_not_found 		= $this->lang->line('user_details_not_found');
		$user_details_found 			= $this->lang->line('user_details_found');

		if(trim($this->input->post('user_id'))=="")
		{
			$response = array("success"=>"false","message"=>$userid_required);
		}
		else
		{
			$userId 		= trim($this->input->post('user_id'));
			$account 		= $this->Webservice_customer_model->chkAccountDelete($userId);
			if(is_array($account) && count($account)>0)
			{
				$response = array("response"=>"false","message"=>$account_deleted,"account_status"=>"inactive","webservice_name"=>"getUserDetails");
			}
			else
			{
				$userDetails = $this->Webservice_customer_model->getUserDetails($userId);
				if(is_array($userDetails) && count($userDetails)>0)
				{

					if($userDetails[0]->profile_photo)
					{
						$userDetails[0]->profile_photo = base_url().'assets/uploads/users/customers/'.$userDetails[0]->profile_photo;
					}					
					if($userDetails[0]->is_active==1)
					{
						$response = array("response"=>"true","message"=>$user_details_found,"data"=>$userDetails,"webservice_name"=>"getUserdetails");
					}
					else
					{
						$response = array("response"=>"false","message"=>$user_details_not_found,"webservice_name"=>"getUserdetails");
					}
				}
				else
				{
					$response = array("response"=>"false","message"=>$user_details_not_found,"account_status"=>"active","webservice_name"=>"getUserdetails");
				}					
			}
		}
		$this->evaluateExecutionTime($startTime,'getUserDetails',$this->input->post());
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : Get User details
	 * Created by : Vaibhav Mehta
	 * Created Date: 27/11/17 12:30 PM 
	*/
	function completeOrder()
	{
		$startTime 		= $this->benchmark->mark('code_start');

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
		$message_completeOrder 			= $this->lang->line('message_completeOrder');
		$account_deleted 				= $this->lang->line('account_deleted');
		$default_language_required 		= $this->lang->line('default_language_required');
		$token_mismatched 				= $this->lang->line('token_mismatched');
		$userid_required 				= $this->lang->line('userid_required');
		$token_required 				= $this->lang->line('token_required');
		$order_id_required 				= $this->lang->line('order_id_required');
		$payment_type_required 			= $this->lang->line('payment_type_required');
		$failed_update_order 			= $this->lang->line('failed_update_order');

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
		else if(trim($this->input->post('payment_type'))==""){
			$response = array("response"=>"false","message"=>$payment_type_required);
		}
		else
		{
			$userId				= trim($this->input->post('user_id'));
			$accessToken 		= trim($this->input->post("access_token"));
			$orderId 			= trim($this->input->post("order_id"));
			$payment 			= trim($this->input->post("payment_type"));
			
			$token = $this->checkAccessToken($userId,$accessToken);
		
			if($accessToken===$token){
				$account = $this->Webservice_customer_model->chkAccountDelete($userId);

				if(is_array($account) && count($account)>0){

					$response = array("response"=>"false","message"=>$account_deleted,"account_status"=>"inactive","webservice_name"=>"completeOrder");
				}
				else
				{
					$expectedTime = $this->Webservice_customer_model->getRestaurantDefaultTime($orderId);
					$now = date("Y-m-d H:i:s");

					$placedTime = strtotime($now);
					$finalTime 	= $placedTime + $expectedTime[0]->custom_delivery_time;
					$updateOrderData['order_placed_time'] 		= $now;
					$updateOrderData['order_type'] 				= $payment;
					$updateOrderData['order_status'] 			= "1";
					$updateOrderData['updated_by'] 				= $userId;
					$updateOrderData['updated_date'] 			= date("Y-m-d H:i:s");

					$seqNo = $this->Home_model->getLatestSequenceNumber();
					$updateOrderData['sequence_no'] = $seqNo->sequence_no + 1;
						
					$res = $this->Webservice_customer_model->updateData($updateOrderData,$orderId);
					
					if ($res)
					{	
						$response=array("response"=>"true","message"=>$message_completeOrder,"webservice_name"=>"completeOrder");
					}
					else
					{
						$response=array("response"=>"false","message"=>$failed_update_order,"delivery_time"=>$deliveryTime,"webservice_name"=>"completeOrder");
					}
				}
			}
			else{

				$response = array("response"=>"false","access"=>"false","message"=>$token_mismatched,"webservice_name"=>"completeOrder");
			}
		}
		$this->evaluateExecutionTime($startTime,'completeOrder',$this->input->post());
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
		$startTime 		= $this->benchmark->mark('code_start');

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
				$account = $this->Webservice_customer_model->chkAccountDelete($userId);

				if(is_array($account) && count($account)>0){

					$response = array("response"=>"false","message"=>$account_deleted,"account_status"=>"inactive","webservice_name"=>"getOrderStatus");
				}
				else
				{
					$getOrderData = $this->Webservice_customer_model->getOrderData($orderId);
					$deliveryTime = '3600';

					if(is_array($getOrderData) && count($getOrderData)>0)
					{
						$deliveryTime = $getOrderData[0]->custom_delivery_time;
						$status1 = $this->OrderStatus;
						$status = $status1['order_status'];
						$response=array("response"=>"true","message"=>$message_getOrderStatus,"delivery_time"=>$deliveryTime,/*"order_status"=>$status,*/"webservice_name"=>"getOrderStatus");
					}
					else
					{
						$response=array("response"=>"false","message"=>$failed_order_status,"webservice_name"=>"getOrderStatus");
					}
				}
			}
			else{

				$response = array("response"=>"false","access"=>"false","message"=>$token_mismatched,"webservice_name"=>"getOrderStatus");
			}
		}
		$this->evaluateExecutionTime($startTime,'getOrderStatus',$this->input->post());
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : get all choices for syncing the mobile and web database
	 * Created by : Ajay Patel
	 * Created Date: 22/12/17 03:30 PM 
	*/
	function getAllChoiceData()
	{ 
		$startTime = $this->benchmark->mark('code_start');
		$offset 		= trim($this->input->post('offset')) ? $this->input->post('offset') : '0';
		$total 			= trim($this->input->post('total')) ? $this->input->post('total') : '0';
		$allData = $this->Webservice_customer_model->getAllChoiceData($offset,$total);
		
	
		if(count($allData)>0)
		{
			foreach ($allData as $key => $value) {
				$value->choice_name =stripslashes($value->choice_name);
			}
			$response = array("response"=>"true","webservice_name"=>"getAllChoiceData","message"=>'Data is found',"choice"=>$allData);
		}
		else
		{
			$response = array("response"=>"false","webservice_name"=>"getAllChoiceData","message"=>'No data is found');
		}
		
		$this->evaluateExecutionTime($startTime,'getAllChoiceData',$this->input->post());
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : get all choices for syncing the mobile and web database
	 * Created by : Ajay Patel
	 * Created Date: 22/12/17 03:30 PM 
	*/
	function getAllChoiceCategoryData()
	{ 
		$startTime = $this->benchmark->mark('code_start');
		$offset 		= trim($this->input->post('offset')) ? $this->input->post('offset') : '0';
		$total 			= trim($this->input->post('total')) ? $this->input->post('total') : '0';
		$allData = $this->Webservice_customer_model->getAllChoiceCategoryData($offset,$total);
		
		if(count($allData)>0)
			{				
				foreach ($allData as $key => $value) {
					$value->choice_category_name =stripslashes($value->choice_category_name);
				}
				$response = array("response"=>"true","webservice_name"=>"getAllChoiceCategoryData","message"=>'Data is found',"choiceCategory"=>$allData);
			}
			else
			{
				$response = array("response"=>"false","webservice_name"=>"getAllChoiceCategoryData","message"=>'No data is found');
			}
		
		$this->evaluateExecutionTime($startTime,'getAllChoiceCategoryData',$this->input->post());
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : get all dish choices for syncing the mobile and web database
	 * Created by : Ajay Patel
	 * Created Date: 22/12/17 03:30 PM 
	*/
	function getAllDishChoiceData()
	{ 
		$startTime = $this->benchmark->mark('code_start');
		$offset 		= trim($this->input->post('offset')) ? $this->input->post('offset') : '0';
		$total 			= trim($this->input->post('total')) ? $this->input->post('total') : '0';
		$allData = $this->Webservice_customer_model->getAllDishChoiceData($offset,$total);
		
		if(count($allData)>0)
		{
			$response = array("response"=>"true","webservice_name"=>"getAllDishChoiceData","message"=>'Data is found',"choice"=>$allData);
		}
		else
		{
			$response = array("response"=>"false","webservice_name"=>"getAllDishChoiceData","message"=>'No data is found');
		}
		
		$this->evaluateExecutionTime($startTime,'getAllDishChoiceData',$this->input->post());
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : get all dish category for syncing the mobile and web database
	 * Created by : Ajay Patel
	 * Created Date: 22/12/17 03:30 PM 
	*/
	function getAllDishCategoryData($locality='')
	{ 

		$startTime    = $this->benchmark->mark('code_start');
		$search 	  = trim($this->input->post('search')) ? $this->input->post('search') : '';
		$offset 	  = trim($this->input->post('offset')) ? $this->input->post('offset') : '0';
		$total 		  = trim($this->input->post('total')) ? $this->input->post('total') : '0';
		$allData      = $this->Webservice_customer_model->getAllDishCategoryData($offset=null,$total=null,$locality,$search);

		$lang =$_COOKIE['lang'];
		if(count($allData)>0){
			foreach ($allData as $key => $value) {
				$allData[$key]->category_name=($lang=='AR')?$value->category_ar_name:stripslashes($value->category_name);
			}
		}
		
 		$imagePath    =base_url()."assets/uploads/category/";
 		//print_r($allData);exit;
		if(count($allData)>0)
		{
			$bestDishes =0;
			if($locality !='')
			{
				$totalBestDishes =$this->Webservice_customer_model->countBestDishes($allData[0]->restaurant_id);
				$bestDishes =$totalBestDishes[0]->total;
			}
			$response = array("response"=>"true","webservice_name"=>"getAllDishCategoryData","message"=>'Data is found',"choice"=>$allData,"imagePpath"=>$imagePath,'totalBestDishes'=>$bestDishes);
		}
		else
		{
			$response = array("response"=>"false","webservice_name"=>"getAllDishCategoryData","message"=>'No data is found');
		}
		$this->evaluateExecutionTime($startTime,'getAllDishCategoryData',$this->input->post());
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : get all dishes for syncing the mobile and web database
	 * Created by : Ajay Patel
	 * Created Date: 22/12/17 03:30 PM 
	*/
	function getAllDishData()
	{ 
		$startTime  = $this->benchmark->mark('code_start');
		$offset     = trim($this->input->post('offset')) ? $this->input->post('offset') : '0';
		$total 		= trim($this->input->post('total')) ? $this->input->post('total') : '0';
		$type		= trim($this->input->post('type')) ? $this->input->post('type') : '0';
		$imagePath  =base_url()."assets/images/front-endold/dishes";
		$allData    = $this->Webservice_customer_model->getAllDishData($offset,$total,$type);
		
		if(is_array($allData) && count($allData)>0)
		{
			$response = array("response"=>"true","webservice_name"=>"getAllDishData","message"=>'Data is found',"choice"=>$allData,"imagePpath"=>$imagePath);
		}
		else
		{
			$response = array("response"=>"false","webservice_name"=>"getAllDishData","message"=>'No data is found');
		}
		
		$this->evaluateExecutionTime($startTime,'getAllDishData',$this->input->post());
		echo json_encode($response);exit;
	}

	/**
	 * Description : get all dishes based on category for syncing the mobile and web database
	 * Created by : Ajay Patel
	 * Updated by : Manisha Kanazariya
	 * Created Date: 22/12/17 03:30 PM 
	*/
	function getdishescategorywise($locality ='')
	{
		
		$startTime = $this->benchmark->mark('code_start');
		$search    = trim($this->input->post('search')) ? $this->input->post('search') : '';
		$offset    = trim($this->input->post('offset')) ? $this->input->post('offset') : '0';
		$total 	   = trim($this->input->post('total')) ? $this->input->post('total') : '0';

		$categoryData = $this->Webservice_customer_model->getAllDishCategoryData("","",$locality,$search);
		
		$lang =$_COOKIE['lang'];
		
 		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id']!=''){
 			$user_id       =$_COOKIE['user_id'];	
 			$favouriteDish =$this->Webservice_customer_model->listFavouriteDish($user_id);
 			$favDishes     =array();
 			if(count($favouriteDish)>0){
 				foreach ($favouriteDish as $key => $value) {
 					$favDishes[]= $value->fk_dish_id;

 				}
 			}
 		}	


 		if(isset($locality) && $locality != ""){
 			$dishRating = $this->Home_model->dishesRating($locality);
			if(count($dishRating)>0){
				foreach ($dishRating as $key => $value) {
					$dishRate[$value->dish_id] =ceil($value->rating/$value->total);
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
 		

		foreach ($categoryData as $key => $value) {
			
			if($value->category_id !=null || $value->category_id !=""){

				$dishesdata_cat_vise[$value->category_id]['category_id']   	 = $value->category_id;
				$dishesdata_cat_vise[$value->category_id]['category_name']   =($lang =='AR')?$value->category_ar_name:$value->category_name;
				$dishesdata_cat_vise[$value->category_id]['dishes']        	 = $this->Webservice_customer_model->getAllDishes($value->category_id,$locality,"",$search);
			    foreach ($dishesdata_cat_vise[$value->category_id]['dishes'] as $key1 => $value1) {
			    	$dishesdata_cat_vise[$value->category_id]['dishes'][$key1]->product_en_name =($lang =='AR')?$value1->product_ar_name:$value1->product_en_name;
			    	$dishesdata_cat_vise[$value->category_id]['dishes'][$key1]->en_description  =($lang =='AR')?$value1->ar_description:$value1->en_description;
			    	$dishesdata_cat_vise[$value->category_id]['dishes'][$key1]->is_best_dishes  =$value1->is_best_dishes;

			    	if(isset($favDishes) && !empty($favDishes)){
			    		if(in_array($value1->product_id,$favDishes)){
			    			$dishesdata_cat_vise[$value->category_id]['dishes'][$key1]->is_favourite= 1;
			    		}else{
			    			$dishesdata_cat_vise[$value->category_id]['dishes'][$key1]->is_favourite= 0;
			    		}
			    	}else{
			    			$dishesdata_cat_vise[$value->category_id]['dishes'][$key1]->is_favourite= 0;
			    		}
			    	$choice       =array();
			    	if(isset($value1->choice_id) && $value1->choice_id !=''){
						
						$choices      =explode(',',$value1->choice_id);
						$choicePrices =explode(',',$value1->choice_price);

						foreach ($choices as $key2 => $value2) {
							$choice[$key2]['choice_id']     =$value2;
							$choice[$key2]['choice_value']  =$choicePrices[$key2];
						}
					}
					$dishesdata_cat_vise[$value->category_id]['dishes'][$key1]->choices    =$choice;
			    }
			}
		}

		if(is_array($dishesdata_cat_vise) && count($dishesdata_cat_vise)>0)
		{			
			//check is best dishes is exist?
			$bestDishes =0;
			if($locality !='')
			{
				$totalBestDishes =$this->Webservice_customer_model->countBestDishes($categoryData[0]->restaurant_id);
				$bestDishes =$totalBestDishes[0]->total;
			}

			$response = array("response"=>"true","webservice_name"=>"getdishescategorywise","message"=>'Data is found',"category"=>$dishesdata_cat_vise,"locality_id"=>$categoryData[0]->locality_id,"restaurant_id"=>$categoryData[0]->restaurant_id,"totalCategory"=>count($dishesdata_cat_vise),'totalBestDishes'=>$bestDishes);

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
		$this->evaluateExecutionTime($startTime,'getdishescategorywise',$this->input->post());
		echo json_encode($response);
		exit;
	}

	

	/**
	 * Description : customer website - get dishes based on choice
	 * Created by : Umang Kothari
	 * Created Date: 25/12/17 03:30 PM 
	*/
	function getchoicewisedish()
	{
		$startTime   = $this->benchmark->mark('code_start');
		$offset	     = trim($this->input->post('offset')) ? $this->input->post('offset') : '0';
		$total 	     = trim($this->input->post('total')) ? $this->input->post('total') : '0';
		$dish_id 	 = trim($this->input->post('dish_id')) ? $this->input->post('dish_id') : '0';
		// $locality    =($_SESSION['locality']!='')?$_SESSION['locality']:1;
		$locality 	 = trim($this->input->post('dish_id')) ? $this->input->post('locality_id') : 1;
		$lang        =$_COOKIE['lang'];
		$dishDetails = $this->Webservice_customer_model->getAllDishes('',$locality,$dish_id);
		$cat_id = 0;
		$i = 0;

		$dishChoiceData['product_id']      = $dishDetails[0]->product_id;
		$dishChoiceData['product_en_name'] =($lang =='AR')?$dishDetails[0]->product_ar_name:$dishDetails[0]->product_en_name;
		$dishChoiceData['en_description']  =($lang =='AR')?$dishDetails[0]->ar_description:$dishDetails[0]->en_description;
		$dishChoiceData['price']           = $dishDetails[0]->resDishPrice;
		$dishChoiceData['choiceCategory']  = array(); 
		
		if($dishDetails[0]->choice_id !=''){
			$choices =explode(',',$dishDetails[0]->choice_id);
			$chPrice =explode(',',$dishDetails[0]->choice_price);

			foreach ($choices as $key => $value) {
				$ch = $this->Webservice_customer_model->dishchoiceDetails($value);
				if(!empty($ch)){

					if($cat_id != $ch[0]->fk_choice_category_id)
					{
						$i++;
						$dishChoiceData['choiceCategory'][$i]['category_id']   = $ch[0]->fk_choice_category_id;
						$dishChoiceData['choiceCategory'][$i]['category_name'] =($lang =='AR')?$ch[0]->choice_category_name_ar:$ch[0]->choice_category_name;
						$dishChoiceData['choiceCategory'][$i]['is_multiple']   = $ch[0]->is_multiple;

						$cat_id = $ch[0]->fk_choice_category_id;
						$j = 0;
					}
					$dishChoiceData['choiceCategory'][$i]['choice'][$j]['choice_id']   = $ch[0]->choice_id;
					$dishChoiceData['choiceCategory'][$i]['choice'][$j]['choice_name'] =($lang =='AR')?$ch[0]->choice_name_ar:$ch[0]->choice_name;
					$dishChoiceData['choiceCategory'][$i]['choice'][$j]['is_active']   = $ch[0]->is_active;
					$dishChoiceData['choiceCategory'][$i]['choice'][$j]['price']       = $chPrice[$key];
					$j++;

					//Use in future for ascending sorting
					//
					/*usort($dishChoiceData['choiceCategory'][$i]['choice'], function ($item1, $item2) {
						return $item1['choice_name'] <=> $item2['choice_name'];
					});*/
				}
			}
		}
	
		if(is_array($dishChoiceData) && count($dishChoiceData)>0)
		{				
			$response = array("response"=>"true","webservice_name"=>"getchoicewisedish","message"=>'Data is found',"dish_details"=>$dishChoiceData);
		}
		else
		{
			$response = array("response"=>"false","webservice_name"=>"getchoicewisedish","message"=>'No data is found');
		}
		
		$this->evaluateExecutionTime($startTime,'getchoicewisedish',$this->input->post());
		echo json_encode($response);
		exit;

	}

	/**
	 * Description : customer website - get kuwait's state and city
	 * Created by : Umang Kothari
	 * Created Date: 28/12/17 03:30 PM 
	*/
	function getCityState()
	{
		$startTime = $this->benchmark->mark('code_start');
		$offset	= trim($this->input->post('offset')) ? $this->input->post('offset') : '0';
		$total 	= trim($this->input->post('total')) ? $this->input->post('total') : '0';

		$state = $this->Webservice_customer_model->getState(117);
		foreach ($state as $key => $value) {
			$statearr[] = $value->state_id;
		}
		$city = $this->Webservice_customer_model->getCity($statearr);

		if((is_array($state) && count($state)>0) && (is_array($city) && count($city)>0))
		{				
			$response = array("response"=>"true","webservice_name"=>"getCityState","message"=>'State ,City List',"state"=>$state,"city"=>$city);
		}
		else
		{
			$response = array("response"=>"false","webservice_name"=>"getCityState","message"=>'No data is found');
		}


		$this->evaluateExecutionTime($startTime,'getCityState',$this->input->post());
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : based on current lat lon give near by restaurant
	 * Created by : Vaibhav Mehta
	 * Created Date: 29/12/17 12:30 PM 
	*/
	function getSelectedRestaurant()
	{
		$startTime 		= $this->benchmark->mark('code_start');

		if(trim($this->input->post('latitude'))=="")
		{
			$response = array("success"=>"false","message"=>"Latitude is required");
		}
		else if(trim($this->input->post('longitude'))==""){
			$response = array("response"=>"false","message"=>"Longitude is required");
		}
		else
		{
			$lat 	= trim($this->input->post('latitude'));
			$lon 	= trim($this->input->post("longitude"));

			$resId 	= $this->Webservice_customer_model->getSelectedRestaurant($lat,$lon);

			if($resId)
			{
				$response = array("response"=>"true","webservice_name"=>"getSelectedRestaurant","message"=>'Near By restaurant found.',"nearby"=>$resId);
			}
			else
			{
				$resRandom 	= $this->Webservice_customer_model->getSelectedRestaurantRandom();
				$response = array("response"=>"true","webservice_name"=>"getSelectedRestaurant","message"=>'Near By restaurant found.',"nearby"=>$resId);
			}
		}
		$this->evaluateExecutionTime($startTime,'getSelectedRestaurant',$this->input->post());
		echo json_encode($response);
		exit;
	}

	/**
	 * Description : make Dispute Order status
	 * Created by : Umang Kothari
	 * Created Date: 29/01/18 12:30 PM 
	*/

	function changeStatus()
	{
		$startTime 		= $this->benchmark->mark('code_start');
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
		$status_id_required			= $this->lang->line('status_id_required');
		$token_mismatched 			= $this->lang->line('token_mismatched');
		$token_required 			= $this->lang->line('token_required');
		$userid_required 			= $this->lang->line('userid_required');
		$order_id_required 			= $this->lang->line('order_id_required');
		$reason_required 			= $this->lang->line('reason_required');
		$default_language_required 	= $this->lang->line('default_language_required');

		if(trim($this->input->post('user_id'))==""){
			$response = array("response"=>"false","message"=>$userid_required);
		}
		else if(trim($this->input->post('status'))==""){
			$response = array("response"=>"false","message"=>$status_id_required);
		}
		else if(trim($this->input->post('access_token'))==""){
			$response = array("response"=>"false","message"=>$token_required);
		}
		else if(trim($this->input->post('order_id'))==""){
			$response = array("response"=>"false","message"=>$order_id_required);
		}
		else if(trim($this->input->post('reason'))==""){
			$response = array("response"=>"false","message"=>$reason_required);
		}
		else if(trim($this->input->post('default_language'))==""){
			$response = array("response"=>"false","message"=>$default_language_required);
		}
		else
		{
			$userId 		= trim($this->input->post('user_id'));
			$status 		= trim($this->input->post('status'));
			$orderId 		= trim($this->input->post('order_id'));
			$accessToken 	= trim($this->input->post("access_token"));
			
			$order_id 		= $this->Home_model->getOrderIdFromSequenceNo($orderId);
			$orderId 		= $order_id->order_id;

			$token 			= $this->checkAccessToken($userId,$accessToken);

			if($accessToken===$token){
				$data['order_status'] = $status;
				$data['reason'] = $this->input->post('reason');
				$this->Webservice_driver_model->updateOrderStatus($data,$orderId);

				$response = array("response"=>"true","message"=>$message_changeOrderStatus,"webservice_name"=>"changeStatus");
			}
			else
			{
				$response = array("response"=>"false","access"=>"false","message"=>$token_mismatched,"webservice_name"=>"disputeOrder");
			}

			// $this->sendPushNotificationUsingFirebase($status, $orderId);
		}
		$this->evaluateExecutionTime($startTime,'getSelectedRestaurant',$this->input->post());
		echo json_encode($response);
		exit;

	}

	/**
	 * Description : Check Mobile No. is exits or not
	 * Created by : Umang Kothari
	 * Created Date: 31/01/18 12:30 PM 
	*/

	function checkMobileno()
	{
		$startTime 		= $this->benchmark->mark('code_start');
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

		$phone_required 				= $this->lang->line('phone_required');
		$invalid_login 				= $this->lang->line('invalid_login');
		

		if(trim($this->input->post('phone_no'))==""){
			$response = array("response"=>"false","message"=>$phone_required);
		}
		else
		{
			$phone_no = $this->input->post('phone_no');
			$userData = $this->Webservice_customer_model->checkPhoneNumber($phone_no,5);
			if(is_array($userData) && count($userData)>0)
			{
				$mail 	= $this->base64url_encode($phone_no);
				$userUpdate = array(
					"change_password_date" 		=> date("Y-m-d H:i:s"),
					"security_token" 			=> $mail,
					"security_token_expiry_date"=> date("Y-m-d H:i:s",strtotime('+1 hour'))
					);
					$dataSuccess = $this->Webservice_customer_model->updateUserProfile($userData[0]->user_id,$userUpdate);
				//print_r($userData);exit("in");
				$userDetails['user_id'] = $userData[0]->user_id;
				$userDetails['country_code'] = $userData[0]->country_code;
				$userDetails['contact_no'] = $userData[0]->contact_no;
				$userDetails['security_token'] = $mail;
				$response = array("response"=>"true","data"=>$userDetails,"webservice_name"=>"checkMobileno");
			}
			else
			{
				$response = array("response"=>"false","message"=>$invalid_login,"webservice_name"=>"checkMobileno");
			}
		}
		$this->evaluateExecutionTime($startTime,'getSelectedRestaurant',$this->input->post());
		echo json_encode($response);
		exit;

	}

	/**
	 * Description : Check Mobile No. is exits or not
	 * Created by : Umang Kothari
	 * Created Date: 31/01/18 12:30 PM 
	*/

	function resetPassword()
	{
		$startTime 		= $this->benchmark->mark('code_start');
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

		$sectoken_required 		= $this->lang->line('sectoken_required');
		$password_required 		= $this->lang->line('password_required');
		$password_change 		= $this->lang->line('password_change');
		$password_notchange		= $this->lang->line('password_notchange');
		$token_expiry 			= $this->lang->line('token_expiry');


		if(trim($this->input->post('password'))==""){
			$response = array("response"=>"false","message"=>$password_required);
		}
		else if(trim($this->input->post('security_token'))==""){
			$response = array("response"=>"false","message"=>$sectoken_required);
		}
		else
		{
			$securityToken 		= $this->input->post('security_token');
			$password 		 	= $this->input->post('password');
			

			if($securityToken != ""){

				$userData 		= $this->User_model->getUserDetailsBySecurityToken($securityToken);

				if(is_array($userData) && count($userData)>0){
					//echo date('Y-m-d H:i:s',strtotime(" -30 minutes"))."<br>";
					$tokenExpiryData = $userData[0]->security_token_expiry_date;
					if(date('Y-m-d H:i:s',strtotime(" -60 minutes")) < $tokenExpiryData)
					{
						$userDataUpdate = array(
		    				"password" 						=> md5($password),
		    				"security_token" 				=> '',
		    				"security_token_expiry_date" 	=> '',
		    				"change_password_date"			=> date("Y-m-d H:i:s")
		        		);

			        	$res = $this->Login_model->updateUserDetailsBYSecurityToken($securityToken,$userDataUpdate);
			        	if(isset($res) && $res==1)
						{
							$response = array("response"=>"true","message"=>$password_change);
						}
						else
						{
							$response = array("response"=>"false","message"=>$password_notchange);	
						}


					}
					else
					{
						$response = array("response"=>"false","message"=>$token_expiry);	
					}
				}
				else
				{
					$response = array("response"=>"false","message"=>"user not found");
				}
			}
			else
			{
				$response = array("response"=>"false","message"=>$sectoken_required);
			}

		}
	$this->evaluateExecutionTime($startTime,'getSelectedRestaurant',$this->input->post());
	echo json_encode($response);
	exit;

	}

	/**
	 * Description : Get Localities list 
	 * Created by : Umang Kothari
	 * Created Date: 31/01/18 12:30 PM 
	*/

	function getlocalites($lang="")
	{
		$localites = $this->Webservice_customer_model->getLocality();
		if($lang !=""){
			foreach ($localites as $key => $value) {
				
				if($lang =='AR'){
					$localites[$key]->name =$value->name_ar;
				}else{
					$localites[$key]->name =$value->name;
				}
			}
		}
		echo json_encode($localites);exit;
	}

	/**
	 * Description : Get Restaurant dish details
	 * Created by : Umang Kothari
	 * Created Date: 31/01/18 12:30 PM 
	*/

	
	function getRestaurantDishes()
	{
		$startTime 	= $this->benchmark->mark('code_start');
		$result 	= $this->Webservice_customer_model->getRestaurantDishes();
		
		if(sizeof($result)>0){
			$response = array("success"=>"1","data"=>$result);
		}
		else{
			$response = array("success"=>"0","message"=>"No Restaurant Found!");
		}
		$this->evaluateExecutionTime($startTime,'getRestaurantDetails',$this->input->post());
		echo json_encode($response);
		exit;
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
			$allFavouriteDish =$this->Webservice_customer_model->listFavouriteDish($user_id,'',$restaurant_id);
		
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
	 * Description :Get Restaurant full information(restaurant details ,locality,opening time)
	 * Created by : manisha Kanazariya
	 * Created Date: 22/05/18 1:30 PM 
	*/

	function getRestaurantDetails(){

			$getRestaurant =$this->Webservice_customer_model->getRestaurantDetail();
			foreach ($getRestaurant as $key => $value) {
				if(count($getRestaurant)>0){
					$resData[$key]['restaurant_id']       =$value->restaurant_id;
					$resData[$key]['restaurant_name']     =$value->restaurant_name;
					$resData[$key]['restaurant_name_ar']  =$value->restaurant_name_ar;
					$resData[$key]['headline']            =$value->headline;
					$resData[$key]['address']             =$value->address;
					$resData[$key]['email']               =$value->email;
					$resData[$key]['contact_no']          =$value->contact_no;
					$resData[$key]['delivery_contact_no'] =$value->delivery_contact_no;
					$resData[$key]['banner_image']        =$value->banner_image;
					$resData[$key]['description']         =$value->description;
					$resData[$key]['country']             =$value->country;
					$resData[$key]['state']               =$value->state;
					$resData[$key]['city']                =$value->city;
					$resData[$key]['zipcode']             =$value->zipcode;
					$resData[$key]['owner_id']            =$value->o_uid;
					$resData[$key]['manager_id']          =$value->m_uid;
					$resData[$key]['zipcode']             =$value->zipcode;
					$resData[$key]['owner']               =$value->o_fname." ".$value->o_lname;
					$resData[$key]['manager']             =$value->m_fname." ".$value->m_lname;

					$getLocality=$this->Webservice_customer_model->getLocality($value->restaurant_id);
					 if(count($getLocality)>0){

					 	foreach ($getLocality as $key1 => $value1) {
					 		$resData[$key]['locality'][$key1]['locality_id'] =$value1->locality_id;
					 		$resData[$key]['locality'][$key1]['name'] =$value1->name;
					 		$resData[$key]['locality'][$key1]['name_ar'] =$value1->name_ar;
					 		$resData[$key]['locality'][$key1]['delivered_time'] =$value1->delivered_time;
					 		$resData[$key]['locality'][$key1]['delivery_charge'] =$value1->delivery_charge;
					 		$resData[$key]['locality'][$key1]['min_order_amount'] =$value1->min_order_amount;
					 		$resData[$key]['locality'][$key1]['lat'] =$value1->lat;
					 		$resData[$key]['locality'][$key1]['lon'] =$value1->lon;
					 		$resData[$key]['locality'][$key1]['created_date'] =$value1->created_date;
					 	}
					 }else{
					 		$resData[$key]['locality']=array();
					 }
					
					$getTime=$this->Webservice_customer_model->getRestaurantTime($value->restaurant_id);
					
						$i=0;
					 foreach ($getTime as $k1 => $v1) {
					 	
					 	$resData[$key][$v1->day][$k1]['from_time']=$v1->from_time;
					 	$resData[$key][$v1->day][$k1]['to_time']=$v1->to_time;
					 	$day = $v1->day;
					 	$resData[$key][$v1->day] = array_values($resData[$key][$v1->day]);
					 	
					 }

					 $response['success'] ="true";
				     $response['message'] =$resData;
				}else{
					$response['success'] ="false";
				    $response['message'] ="Restaurant not found";
				}

			}
		
		echo json_encode($response);exit;
	}

	/**
	 * [getUpdate Get Update of Latest app]
	 * @author Hardik Ghadshi
	 * @Created Date   2019-03-12T17:09:50+0530
	 * @return  [type] [description]
	 */
	function getUpdate(){

		if(sizeof($this->input->post()) > 0){
			$appVersion    = $this->input->post('app_version');
			$deviceType    = $this->input->post('device_type');
			$app            = $this->input->post('app');
		}

		if(sizeof($this->input->get()) > 0){
			$appVersion    = $this->input->get('app_version');
			$deviceType    = $this->input->get('device_type');
			$app            = $this->input->get('app');
		}

		if(!isset($appVersion) || $appVersion == ""){
			$response = array("success"=>"0","message"=>"appver_required");
		}
		elseif(!isset($deviceType) || $deviceType == ""){
			$response = array("success"=>"0","message"=>"devicetype_required");
		}elseif (!isset($app) || $app == "") {
			$response = array("success"=>"0","message"=>"app_required");
		}else{

			$versionRequestData = $this->Webservice_driver_model->checkForUpdateVersion($deviceType, $app);

			$VerReqData['latest_ver'] = $versionRequestData->latest_ver;
			$VerReqData['status'] = $versionRequestData->status;

			$response = array('update'=>'0','flag'=>'0');
			if(count($VerReqData)>0){

                    //Split the current user ver digit from 1.0.0 
				$appVer = str_split($appVersion,strripos($appVersion,'.'));
				$appVerLast  =(float)ltrim($appVer[1], '.');

                    //Split the latest user ver digit from 1.0.0 
				$lstappVer = str_split($VerReqData['latest_ver'],strripos($VerReqData['latest_ver'],'.'));
				$lstappVerLast  =(float)ltrim($lstappVer[1], '.');

                    //Update is define that new version is available
                    //Flag is define that latest version is force update or not
				if($VerReqData['status']==1)
				{
                        //Match the parent ver is same or not like (1.0).0 
					if((float)$lstappVer[0]==(float)$appVer[0])     
					{                   
                            //if parent is equal then check the child ver is larger or not than or not like 1.0.(0)
						if($lstappVerLast > $appVerLast){
							$response['update'] = '1';
							$response['flag'] = '1';
						}
					}
					elseif((float)$lstappVer[0]>(float)$appVer[0])
					{
						$response['update'] = '1';
						$response['flag'] = '1';
					}
				}
				else{
				//Get The latest force update ver from database
					$forceUpdateResult = $this->Webservice_driver_model->getLstForceupdatever($deviceType, $app);

					$forceUpdate['latest_ver'] = $forceUpdateResult->latest_ver;
					$forceUpdate['status'] = $forceUpdateResult->status;

                        //Split the latest force update ver digit from 1.0.0 
					$fuappVer = str_split($forceUpdate['latest_ver'],strripos($forceUpdate['latest_ver'],'.'));
					$fuappVerLast  =(float)ltrim($fuappVer[1], '.');

                        if((float)$fuappVer[0]==(float)$appVer[0])//forcefully update 
                        {
                            if($fuappVerLast > $appVerLast)//forcefully update
                            {
                            	$response['update'] = '1';
                            	$response['flag'] = '1';
                            }
                            elseif($fuappVerLast == $appVerLast)
                            {
                            	if((float)$lstappVer[0]==(float)$appVer[0])
                            	{
                                    if($lstappVerLast > $appVerLast)//regular update 
                                    {
                                    	$response['update'] = '1';
                                    	$response['flag'] = '0';
                                    }   
                                }
                                elseif((float)$lstappVer[0]>(float)$appVer[0])//regular update 
                                {
                                	$response['update'] = '1';
                                	$response['flag'] = '0';
                                }
                            }
                            elseif($fuappVerLast < $appVerLast)
                            {
                            	if((float)$lstappVer[0]==(float)$appVer[0])
                            	{
                                    if($lstappVerLast > $appVerLast)//regular update 
                                    {
                                    	$response['update'] = '1';
                                    	$response['flag'] = '0';
                                    }   
                                }
                                elseif((float)$lstappVer[0]>(float)$appVer[0])//regular update 
                                {
                                	$response['update'] = '1';
                                	$response['flag'] = '0';
                                }
                            }
                        }
                        elseif((float)$fuappVer[0] > (float)$appVer[0])//forcefully update
                        {
                        	$response['update'] = '1';
                        	$response['flag'] = '1';
                        }
                        elseif((float)$fuappVer[0] < (float)$appVer[0])
                        {
                        	if((float)$lstappVer[0]==(float)$appVer[0])
                        	{
                                if($lstappVerLast > $appVerLast)//regular update 
                                {
                                	$response['update'] = '1';
                                	$response['flag'] = '0';
                                } 
                            }
                            elseif((float)$lstappVer[0]>(float)$appVer[0])//regular update 
                            {
                            	$response['update'] = '1';
                            	$response['flag'] = '0';
                            }
                        }
                    }
                    
                }else{
                	$response = array("success"=>"0","message"=>"nolatest_ver");
                }

                if($response['update']!=0){
                	$response["success"]="1";
                	$response["message"]= "latest_ver available";
                }
                else{
                	$response["success"]="0";
                	$response["message"]= "no latest_ver";
                }
            }

            echo json_encode($response);
        }

    public function clearAppLogCron(){
        $this->Login_model->clearAppLogCron();
    }


    /*
     * [updateOrder to Update Order type]
	 * @author Devesh Khandelwal
	 * @Created Date   2020-02-21
	 * @return  [type] [description]	
    */
    function updateOrder()
    {
    	$orderId   = trim($this->input->post("order_id"));
    	$orderType = trim($this->input->post("order_type"));

    	if(empty($orderId))
    	{
    		$response = array("success"=>"0","message"=>"Order Id Can't be blanked");
    	} 
    	else if(empty($orderType))
    	{
    		$response = array("success"=>"0","message"=>"Order Type Can't be blanked");
    	}
    	else 
    	{
    		$data   = array('order_type' => $orderType);
    		$result = $this->Webservice_customer_model->updateOrder($orderId, $data);

    		if(!empty($result) && count($result) > 0)
    		{
    			$response = array("success"=>"1","message"=>"Order Updated Successfully");	
    		}	
    		else 
    		{
    			$response = array("success"=>"0","message"=>"Order Updation Failed");
    		}
    	}

    	echo json_encode($response);
    	exit;
    }    
}


