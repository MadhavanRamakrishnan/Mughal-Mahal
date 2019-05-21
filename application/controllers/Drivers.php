<?php

	/**
	 * Controller Name 	: Driver
	 * Descripation 	: Use to manage all the activity related to driver
	 * @author 			: Vaibhav Mehta
	 * Created date 	: 28-09-2017 07:00PM
	 */

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Drivers extends MY_Controller
	{
	/**
	 * deafult function call when controller class is load
	 * @author Vaibhav Mehta
	 * Created date: 28-09-2017 3:50 PM
	 */
	function __construct(){
		parent::__construct();
		$this->isLoginUser();
		//loading login model
		$this->checkLogin();
		$this->load->model(array('Login_model','Driver_model'));
		$this->menu 	= $this->getMenu();
		$this->submenu 	= $this->getSubMenu();
		$this->load->library('form_validation');
		$this->load->library('upload');
		$this->load->library('encrypt');

	}

	/**
	 * deafult function call for listing drivers
	 * @author Vaibhav Mehta
	 * Created date: 28-09-2017 3:50 PM
	 */
	function index()
	{
		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();
		$driverRole 		= $this->config->item('driver_role');
		// $data['vehicles'] 	= $this->Driver_model->getallVehicleData();
		// echo "<pre>";print_r($data['vehicles']);exit();

		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']   	= $submenuArray;

		$resId = $this->getRestaurantForRoleBaseAccess();

		$data['drivers'] 	= $this->Driver_model->getAllDriverDetails($driverRole,$resId);
		//echo "<pre>";print_r($data['drivers']);exit();
		$this->load->view('Elements/header',$data);
		$this->load->view('Drivers/index');
		$this->load->view('Elements/footer');
	}

	/**
	 * deafult function call when create new driver
	 * @author Rashmi Nayani
	 * Created date: 10-10-2017 11:45 PM
	 */
	function getallVehicle()
	{
		$oid = $this->input->post('oid');
		$rid = $this->input->post('rid');
		if($oid)
		{
			$vehicle 	= $this->Driver_model->getallVehicleData($oid,$rid);
			$html 		= '';
			$html 		.= '<option value="0"> --- Select Vehicle --- </option>';
			if(is_array($vehicle) && count($vehicle)>0)
			{
				foreach ($vehicle as $key => $value)
				{
					if($value->vehicle_id)
					{
						$html .= '<option value="'.$value->vehicle_id.'" >';
					}
					else
					{
						$html .= '<option value="'.$value->vehicle_id.'">';
					}
					$html .= $value->brand.' '.$value->model.'</option>';
				}
				$response = array("success"=>"1","message"=>"Vehicle available.","Vehicle"=>$html);
			}
			else
			{
				$response = array("success"=>"0","message"=>"No Vehicle available.","Vehicle"=>$html);
			}
		}
		else
		{
			$response = array("success"=>"0","message"=>"Please select Vehicle for drivers.");
		}
		echo json_encode($response);
	}
	function changeVehicle()
	{
		//$uid = $this->userdata[0]->user_id;
		$oid = $this->input->post('oid');
		$did = $this->input->post('did');

		if($oid)
		{
			$changeDriverVehicle = array();
			if($did==0 && $did==""){
				$changeDriverVehicle['fk_vehicle_id']	= 0;	
			}else{
				$changeDriverVehicle['fk_vehicle_id']	= $did;
			}
			//$updateOrder['updated_by'] 		= $uid;
			//$updateOrder['updated_date'] 	= date("Y-m-d H:i:s");
			
			$update 		= $this->Driver_model->updateasignVehicle($oid,$changeDriverVehicle);
			$vehicle 	= $this->Driver_model->getallVehicleById($did);
			if($vehicle){

			$name 			= $vehicle[0]->brand.' '.$vehicle[0]->model;
			}else{
				$name = array();
			}
			

			if($update)
			{
				$response = array("success"=>"1","message"=>"Vehicle has been successfully assign to the driver.","vehicle_name"=>$name);
			}
			else
			{
				$response = array("success"=>"0","message"=>"Vehicle can't be assigned to selected driver, please try after sometime.");
			}
		}
		else
		{
			$response = array("success"=>"0","message"=>"Please select Vehicle.");
		}
		echo json_encode($response);
	}

	/**
	 * deafult function call for listing orders
	 * @author Vaibhav Mehta
	 * Created date: 18-11-2017 5:50 PM
	 */
	function addDrivers(){ 
		$data['userdata']	= $this->session->userdata('current_user');
		$data['resId'] 		= $this->getRestaurantForRoleBaseAccess();
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();
		$driverRole 		= $this->config->item('driver_role');

		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']   		= $submenuArray;
		$data['countryList'] 	= $this->Driver_model->getAllCountries();
		$data['resList'] 		= $this->Driver_model->getAllRestaurant($data['resId']);

		if ($this->input->post('add')=='Save') {
			
			$this->form_validation->set_rules('fname','First Name', 'required|callback_isNameExist');
			$this->form_validation->set_rules('lname', 'Last Name', 'required|max_length[155]');
			$this->form_validation->set_rules('branch', 'Restaurant Branch', 'required');
			$this->form_validation->set_rules('contact_no', 'Contact No', 'required|max_length[8]|regex_match[/^[a-z0-9]+$/]|min_length[8]|numeric');//|is_unique[tbl_users.contact_no]
			$this->form_validation->set_rules('email', 'Email', 'required|callback_isEmailExist|valid_email');
			if($_FILES['image']['name'] == ""){
				
				$this->form_validation->set_rules('image', '', 'callback_file_check');
			}
			if ($this->form_validation->run() == FALSE){
				
			}
			else{
				$config['upload_path']   		= './assets/uploads/users/drivers/'; 
				$config['allowed_types']        = 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
				$config['max_size']             = 5120;
				$config["encrypt_name"] 		= true;

				
				$this->upload->initialize($config);
				if (! $this->upload->do_upload('image')){

					$error = array('error' => $this->upload->display_errors());
				}
				else{
					$dataupload = array('upload_data' => $this->upload->data());
					$driverData['profile_photo'] = $dataupload['upload_data']['file_name'];	
				}
				if(isset($error) && sizeof($error)>0){
					$data['image_error']=$error['error'];
				}
				else{
					function randomPassword() {
						$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
				    $pass = array(); //remember to declare $pass as an array
				    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
				    for ($i = 0; $i < 8; $i++) {
				    	$n = rand(0, $alphaLength);
				    	$pass[] = $alphabet[$n];
				    }
				    return implode($pass); //turn the array into a string
				}
				
				
				$random = randomPassword();
				$encrypt = $this->config->item('encryption_key');
				$encryptpassword = md5($encrypt.$random);
				$decryptPassword = $encrypt.$random;
				
				$driverData['first_name'] 	= trim($this->input->post('fname'));
				$driverData['last_name'] 	= trim($this->input->post('lname'));
				$driverData['fk_restaurant_id']= trim($this->input->post('branch'));
		        
				$driverData['contact_no']	= trim($this->input->post('contact_no'));
				$driverData['email'] 		= trim($this->input->post('email'));
				$driverData['vendor'] 		= trim($this->input->post('vendor'));
				$driverData['password']		= $encryptpassword;
				$driverData['role_id'] 		= $this->config->item('driver_role');
				$driverData['created_by'] 	= $data['userdata'][0]->user_id;	
				$driverData['created_date']	= date("Y-m-d H:i:s");

				$result = $this->Driver_model->addDriverDetail($driverData);
				
				if (sizeof($result)>0) {

					$emailData['login_link']    = site_url('Login');
					$emailData['email_template']= 'credentials';
					$emailData['to_email']		= $driverData['email'];
					$emailData['password']		= $encrypt.$random;
					$emailData['base_url']		= base_url();
					$emailData['user_name']		= $driverData['first_name'].' '.$driverData['last_name'];				
					$emailData['subject']		= 'Registration Successfully';
					$mails = $this->sendMail($emailData);
					
					$this->session->set_flashdata('success_msg', "Driver Details Added successfully!");
					redirect('Drivers/index');
				}
				else{
					$this->session->set_flashdata('error_msg', "Something went wrong while adding Driver details");
					redirect('Drivers/addDrivers');
				}
			}
		}
	}
	
	$this->load->view('Elements/header',$data);
	$this->load->view('Drivers/add_driver');
	$this->load->view('Elements/footer');
}

	/**
	 * function to check image selected or not
	 * @author Rashmi Nayani
	 * Created date: 12-10-2017 6:15 PM
	 */
	public function file_check($str){

		if($_FILES['image']['name'] == ""){

			$this->form_validation->set_message('file_check', 'Please choose a file to upload.');
			return false;
		}
	}
	/**
	 * function to get state by selecting country
	 * @author Rashmi Nayani
	 * Created date: 10-10-2017 1:30 PM
	 */
	public function getState(){

		$data['userdata']	= $this->session->userdata('current_user');
		$countryId    		= $this->input->post('country_id');
		$result = $this->Driver_model->getState($countryId);
		if(sizeof($result)>0){
			$response = array("success"=>"1","data"=>$result);
		}
		else{
			$response = array("success"=>"0","message"=>"No State deatils found!");
		}
		echo json_encode($response);
		exit;
	}
	/**
	 * function to get city for selectimg state
	 * @author Rashmi Nayani
	 * Created date: 10-10-2017 1:30 PM
	 */
	public function getCity(){
		$data['user_data']  =$this->session->userdata('current_user');
		$stateId 	= $this->input->post('state_id');
		$result 	= $this->Driver_model->getCity($stateId);

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
	 * function to check duplicate Restaurant Exist
	 * @author Rashmi Nayani
	 * Created date: 10-10-2017 1:20 PM
	 */

	function isNameExist($name,$id){
		
		$driverRole 		= $this->config->item('driver_role');
		$data = $this->Driver_model->getDuplicateName($driverRole,$name,$id);
		if(sizeof($data)>0){
			$this->form_validation->set_message('isNameExist', 'This Driver Name is already exist');
			return false;
		}
		else{
			return true;
		}
	}
	/**
	 * function to check duplicate email Exist
	 * @author Rashmi Nayani
	 * Created date: 10-10-2017 1:30 PM
	 */

	function isEmailExist($email){
		$driverRole 		= $this->config->item('driver_role');
		$data 	= $this->Driver_model->getDuplicateEmail($driverRole,$email);
		if(sizeof($data)>0){
			$this->form_validation->set_message('isEmailExist', 'This Email is already exist');
			return false;
		}
		else{
			return true;
		}
	}

	/**
	 * function to edit category details 
	 * @author Rashmi Nayani
	 * Created date: 10-10-2017 1:50 PM
	 */
	function editDrivers($id){
		$driverRole 	= $this->config->item('driver_role');
		$data['resId'] 	= $this->getRestaurantForRoleBaseAccess();
		$driverDetails 	= $this->Driver_model->getAllDriverDetails($driverRole,$data['resId'],$id);

		if ($id != null && sizeof($driverDetails)>0) {
			$data['userdata']	= $this->session->userdata('current_user');
			$data['menu']   	= $this->menu;
			$submenu   			= $this->submenu;
			$submenuArray 		= array();

			foreach($submenu as $key=>$value){

				$submenuArray[$value->parent_page_id][] = $value;
			}
			$data['submenu']   	 	= $submenuArray;
			$data['countryList'] 	= $this->Driver_model->getAllCountries();
			$data['resList'] 		= $this->Driver_model->getAllRestaurant($data['resId']);
			$data['driv_data'] 		= $driverDetails;

			if ($this->input->post('update')=='Update') {
				$this->form_validation->set_rules('fname','First Name', 'required');
				$this->form_validation->set_rules('lname', 'Last Name', 'required|max_length[155]');
				$this->form_validation->set_rules('branch', 'Restaurant Branch', 'required');
				$this->form_validation->set_rules('contact_no', 'Contact No', 'required|max_length[8]|regex_match[/^[a-z0-9]+$/]|min_length[8]|numeric');
				
				if ($this->form_validation->run() == FALSE){
					
				}
				else{
					if($_FILES['image']['name'] != ""){

						$config['upload_path']   		= './assets/uploads/users/drivers'; 
						$config['allowed_types']        = 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
						$config['max_size']             = 5120;
						$config["encrypt_name"] 		= true;
						
						$this->upload->initialize($config);
						if (! $this->upload->do_upload('image')){

							$error = array('error' => $this->upload->display_errors());
						}
						else{
							$dataupload = array('upload_data' => $this->upload->data());
							$driverData['profile_photo'] = $dataupload['upload_data']['file_name'];	
							if($data['driv_data'][0]->profile_photo){
								$img = "./assets/uploads/users/drivers/".$data['driv_data'][0]->profile_photo;
								if(file_exists($img))
								{
									unlink($img);
								}
							}
						}
					}
					else{
						$driverData['profile_photo'] =$data['driv_data'][0]->profile_photo;
					}
					if(isset($error) && sizeof($error)>0){
						$data['image_error']=$error['error'];
					}
					else{
						
						if( $this->input->post('password')){
						//	$driverData['password']= md5(trim($this->input->post('password')));
						}
						$driverData['first_name'] 	= trim($this->input->post('fname'));
						$driverData['last_name'] 	= trim($this->input->post('lname'));
						if(trim($this->input->post('branch')) != $driverDetails[0]->fk_restaurant_id){
							$driverData['fk_vehicle_id'] =0;
						}
						$driverData['fk_restaurant_id']= trim($this->input->post('branch'));
						$driverData['contact_no']	= trim($this->input->post('contact_no'));
						$driverData['vendor'] 		= trim($this->input->post('vendor'));
						$driverData['updated_by'] 	= $data['userdata'][0]->user_id;	
						$driverData['updated_date']	= date("Y-m-d H:i:s");

						$result = $this->Driver_model->editDriverDetail($driverData,$id);
						
						if (sizeof($result)>0) {
							$this->session->set_flashdata('success_msg', "Driver Details updated successfully!");
							redirect('Drivers/index');
						}
						else{
							$this->session->set_flashdata('error_msg', "Something went wrong while updating Driver details");
							redirect('Drivers/editDrivers');
						}
					}
				}
			}
			$this->load->view('Elements/header',$data);
			$this->load->view('Drivers/edit_driver');
			$this->load->view('Elements/footer');
			
		}
		else{
			redirect('Drivers/index');
		}
	}
	
	/**
	 * function to delete Restaurant details 
	 * @author Rashmi Nayani
	 * Created date: 10-10-2017 7:50 PM
	 */
	public function deleteDriverDetail(){
		$data['userdata']	= $this->session->userdata('current_user');
		$uId    			= $this->input->post('user_id');

		$driverData['is_active'] 	= "0";
		$driverData['updated_by'] 	= $data['userdata'][0]->user_id;
		$driverData['updated_date'] = date("Y-m-d H:i:s");	
		
		$result = $this->Driver_model->editDriverDetail($driverData,$uId);
		if($result>0){
			$response = array("success"=>"1","message"=>"Driver details delete successfully");
		}	
		else{
			$response = array("success"=>"0","message"=>"Something went wrong while delete Driver details");
		}
		echo json_encode($response);
		exit;
	}
}

