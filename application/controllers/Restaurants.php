
<?php
/**
 * Controller Name: Restaurants
 * Descripation: Use to manage Restaurants activity
 * @author Vaibhav Mehta
 * Created date: 28-09-2017 3:50 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Restaurants extends MY_Controller
{
	/**
	 * function to invoke necessary component
	 * @author Vaibhav Mehta
	 * Created date: 28-09-2017 3:50 PM
	 */

	function __construct()
	{
		parent::__construct();
		$this->isLoginUser();
		$this->load->model(array('Login_model','Restaurant_model','User_model','Driver_model','Dishes_model','Choice_model','Rating_model','Webservice_customer_model'));
		$this->checkLogin();
		$this->menu 	= $this->getMenu();
		$this->submenu 	= $this->getSubMenu();
		$this->load->library('form_validation');
		$this->load->library('upload');
		$this->form_validation->run($this);
		$this->load->library('encrypt');
		
		
	}

	/**
	 * deafult function call when controller class is load
	 * @author Vaibhav Mehta
	 * Created date: 28-09-2017 3:50 PM
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
		$data['submenu']   	= $submenuArray;

		$data['restaurants'] = $this->Restaurant_model->getAllRestaurantDetails();
		$data['dishList']	 = $this->Dishes_model->getAllDishes("","");
		$this->load->view('Elements/header',$data);
		$this->load->view('Restaurants/index');
		$this->load->view('Elements/footer');
	}


	/**
	 * function to add restaurants details
	 * @author Rashmi Nayani
	 * Created date: 09-10-2017 4:45 PM
	 */
	function addRestaurants(){
		
		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();
		$oid 				= $this->config->item('restaurant_owner_role');
		$mid 				= $this->config->item('restaurant_manager_role');

		foreach($submenu as $key=>$value){

			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']   		= $submenuArray;
		$data['ownerList'] 		= $this->Restaurant_model->getOwner();
		$data['Localities']  	= $this->Restaurant_model->getRestaurantLocality("");
		$data['managerList'] 	= $this->Restaurant_model->getManager();
		$data['countryList'] 	=$this->Restaurant_model->getCountry();
		$data['localitylist'] 	=$this->Restaurant_model->getlocality();

		$data['ownVal']			="";
		$data['managVal']		="";


		if ($this->input->post('add')=='Save') {
			$this->form_validation->set_rules('restaurant_name','Restaurant Name', 'required|callback_isRestaurantExist');
			$this->form_validation->set_rules('headline', 'Restaurant Headline', 'required|max_length[155]');
			$this->form_validation->set_rules('owner_name', 'Restaurant Owner', 'trim');
		
			$this->form_validation->set_rules('contact_no', 'Contact No', 'required|max_length[8]|regex_match[/^[a-z0-9]+$/]|min_length[8]');
			$this->form_validation->set_rules('delivery_no', 'Delivery Contact No', 'required|regex_match[/^[a-z0-9]+$/]|min_length[8]|max_length[8]');
			$this->form_validation->set_rules('email', 'Email', 'required|callback_isEmailExist|valid_email');

			if($_FILES['image']['name'] == ""){
				$this->form_validation->set_rules('image', '', 'callback_file_check');
			}

			if ($this->form_validation->run() == FALSE){
				
			}
			else{

				$config['upload_path']   		= './assets/uploads/restaurants/'; 
				$config['allowed_types']        = 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
				$config['max_size']             = 5120;
				
				$this->upload->initialize($config);
				if (! $this->upload->do_upload('image')){

					$error = array('error' => $this->upload->display_errors());
					
				}
				else{
					$dataupload = array('upload_data' => $this->upload->data());
					$resData['banner_image'] = $dataupload['upload_data']['file_name'];	

				}
				if(isset($error) && sizeof($error)>0){
					$data['image_error']=$error['error'];

				}
				else{
					
					$resData['restaurant_name'] 	= trim($this->input->post('restaurant_name'));
					$resData['headline'] 			= trim($this->input->post('headline'));
					$resData['address']				= trim($this->input->post('address'));
					$resData['description']			= trim($this->input->post('description'));
					$resData['contact_no']			= trim($this->input->post('contact_no'));
					$resData['delivery_contact_no']	= trim($this->input->post('delivery_no'));
					$resData['email'] 				= trim($this->input->post('email'));
					$resData['created_by'] 			= $data['userdata'][0]->user_id;	
					$resData['created_date']		= date("Y-m-d H:i:s");
					
					$res_id = $this->Restaurant_model->addRestaurantDetail($resData);

					$owners = $this->input->post('owner_name');
					
					if($owners!='' && $owners>0){
						
						$ownerData['fk_user_id'] 	= $owners;
						$ownerData['restaurant_id']	= $res_id;
						$ownerData['created_by'] 	= $data['userdata'][0]->user_id;	
						$ownerData['created_date']	= date("Y-m-d H:i:s");
						$ownerId                    = $this->Restaurant_model->addRestaurantOwner($ownerData);
					}

					$localities =$this->input->post('localities');
	    			if(count($localities)>0){
	    				$localities =implode(",",$localities);
	    				$deleteLoc  =$this->Restaurant_model->updateRestaurantLocality(array('restaurant_id'=>$res_id),"",$localities);
	    			}
					if (sizeof($res_id)>0) {
						$this->session->set_flashdata('success_msg', "Restaurant Details Added successfully!");
						redirect('Restaurants/index');
					}
					else{
						$this->session->set_flashdata('error_msg', "Something went wrong while adding restaurant details");
						redirect('Restaurants/addRestaurants');
					}
				}
			}
		}
		$this->load->view('Elements/header',$data);
		$this->load->view('Restaurants/add_restaurants');
		$this->load->view('Elements/footer');
	}

	/**
	 * function to check image selected or not
	 * @author Rashmi Nayani
	 * Created date: 12-10-2017 6:15 PM
	 */
	function changeAvailability(){
		$restaurantId 	= $this->input->post('id');
		$status 	= $this->input->post('status');
		$data 			= array(
			"is_availability"	=> $status,
		);
		$results 		= $this->Restaurant_model->updateAvilability($restaurantId,$data);
		if($results >= 0){
			$response 	= array("success"=>1,"message"=>"Change Availability.");
		}
		else{
			$response 	= array("success"=>2,"message"=>"Please try again!");
		}
		echo json_encode($response);
	}
	public function file_check($str){

		if($_FILES['image']['name'] == ""){

			$this->form_validation->set_message('file_check', 'Please choose a file to upload.');
			return false;
		}
	}
	/**
	 * function to get state by selecting country
	 * @author Rashmi Nayani
	 * Created date: 09-10-2017 4:45 PM
	 */
	
	public function getState(){

		$data['userdata']	= $this->session->userdata('current_user');
		$countryId    		= $this->input->post('country_id');
		$result = $this->Restaurant_model->getState($countryId);
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
	 * Created date: 09-10-2017 4:50 PM
	 */
	public function getCity(){
		$data['user_data']  =$this->session->userdata('current_user');
		$stateId 	= $this->input->post('state_id');
		$result 	= $this->Restaurant_model->getCity($stateId);

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
	 * function to check duplicate Restaurant Exist
	 * @author Rashmi Nayani
	 * Created date: 09-10-2017 5:30 PM
	 */

	function isRestaurantExist($resName,$id){
		
		$data = $this->Restaurant_model->getDuplicateRestaurant($resName,$id);
		if(sizeof($data)>0){
			$this->form_validation->set_message('isRestaurantExist', 'This Restaurant is already exist');
			return false;
		}
		else{
			return true;
		}
	}
	/**
	 * function to check duplicate email Exist
	 * @author Rashmi Nayani
	 * Created date: 09-10-2017 5:30 PM
	 */

	function isEmailExist($email){
		if($email ==""){
			$this->form_validation->set_message('isEmailExist', 'Email is required');
		}
		else{
			$data 	= $this->Restaurant_model->getDuplicateEmail($email);
			 if(sizeof($data)>0){
			$this->form_validation->set_message('isEmailExist', 'This Email is already exist');
			return false;
			}
			else{
				return true;
			}
		}
	}
	/**
	 * function to check duplicate email Exist
	 * @author Rashmi Nayani
	 * Created date: 09-10-2017 5:30 PM
	 */

	function isUserEmailExist($email){
		if($email ==""){
			$this->form_validation->set_message('isUserEmailExist', 'Email is required');
		}
		else{
			
			$data 	= $this->Restaurant_model->getDuplicateUserEmail($email);
			
			 if(sizeof($data)>0){
			$this->form_validation->set_message('isUserEmailExist', 'This Email is already exist');
			return false;
			}
			else{
				return true;
			}
		}
	}
	/**
	 * function to check duplicate contact Exist
	 * @author Rashmi Nayani
	 * Created date: 09-10-2017 5:30 PM
	 */

	function isUserContactExist($phone){
		$userId =$this->input->post('user_id');
		if($phone ==""){
			$this->form_validation->set_message('isUserContactExist', 'Contact number is required');
		}
		else{
			
			$data 	= $this->Restaurant_model->getDuplicateUserPhone($phone,$userId);
			if(sizeof($data)>0){
				$this->form_validation->set_message('isUserContactExist', 'This Contact number is already exist');
				return false;
			}
			else{
				return true;
			}
		}
	}
	/**
	 * function to edit category details 
	 * @author Rashmi Nayani
	 * Created date: 06-10-2017 5:20 PM
	 */
	function editRestaurants($id){
		
		$ownerDatas =$this->Restaurant_model->getAllRestaurantDetails($id);
		if ($id != null && sizeof($ownerDatas)>0) { 
			$data['userdata']	= $this->session->userdata('current_user');
			$data['menu']   	= $this->menu;
			$submenu   			= $this->submenu;
			$submenuArray 		= array();
			foreach($submenu as $key=>$value){

				$submenuArray[$value->parent_page_id][] = $value;
			}
			$data['submenu']   	 	= $submenuArray;
			$oid 					= $this->config->item('restaurant_owner_role');
			$mid 					= $this->config->item('restaurant_manager_role');
			$data['ownerList'] 		= $this->Restaurant_model->getOwner($id);
			$data['Localities']  	= $this->Restaurant_model->getRestaurantLocality($id);
			
			$data['managerList'] 	= $this->Restaurant_model->getManager($id);
			$data['imagesList'] 	=$this->Restaurant_model->getimages($id);
			$data['res_data'] 		= $ownerDatas;

	    if ($this->input->post('update')=='Update') {
	    	
	    	$this->form_validation->set_rules('restaurant_name','Restaurant Name', 'required');
	    	$this->form_validation->set_rules('headline', 'Restaurant Headline', 'required|max_length[155]');
	    	$this->form_validation->set_rules('owner_name', 'Restaurant Owner', 'trim');
	    	
	    	$this->form_validation->set_rules('contact_no', 'Contact No', 'required|max_length[13]|regex_match[/^[a-z0-9]+$/]|min_length[8]');
	    	$this->form_validation->set_rules('delivery_no', 'Delivery Contact No', 'required|regex_match[/^[a-z0-9]+$/]|min_length[8]|max_length[8]');
	    	
	    	if ($this->form_validation->run() == FALSE){

	    	}
	    	else{
	    		if($_FILES['image']['name'] != ""){

	    			$config['upload_path']   		= './assets/uploads/restaurants/'; 
	    			$config['allowed_types']        = 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
	    			$config['max_size']             = 5120;
	    			
	    			
	    			$this->upload->initialize($config);
	    			if (! $this->upload->do_upload('image')){

	    				$error = array('error' => $this->upload->display_errors());
	    			}
	    			else{
	    				$dataupload = array('upload_data' => $this->upload->data());
	    				$resData['banner_image'] = $dataupload['upload_data']['file_name'];
	    				if($data['res_data'][0]->banner_image){
	    					$img = "./assets/uploads/restaurants/".$data['res_data'][0]->banner_image;
	    					unlink($img);						
	    				}	
	    			}
	    		}
	    		else{
	    			$resData['banner_image'] = $data['res_data'][0]->banner_image;
	    		}
	    		if(isset($error) && sizeof($error)>0){
	    			$data['image_error']=$error['error'];
	    		}
	    		else{
	    			
	    			$resData['restaurant_name'] 	= trim($this->input->post('restaurant_name'));
	    			$resData['headline'] 			= trim($this->input->post('headline'));
	    			$resData['address']				= trim($this->input->post('address'));
	    			$resData['description']			= trim($this->input->post('description'));
	    			$resData['contact_no']			= trim($this->input->post('contact_no'));
	    			$resData['delivery_contact_no']	= trim($this->input->post('delivery_no'));
	    			$resData['created_by'] 			= $data['userdata'][0]->user_id;	
	    			$resData['created_date']		= date("Y-m-d H:i:s");

	    			$res_id         = $this->Restaurant_model->editRestaurantDetail($resData,$id);
	    			$deleteManager  =$this->Restaurant_model->deleteRestaurantManager($id,"");
	    			$deleteOwner    =$this->Restaurant_model->deleteRestaurantOwner($id,"");

	    			$owners = $this->input->post('owner_name');
	    			if($owners!='' && $owners>0){
	    				$oData['fk_user_id'] 	= $owners;
	    				$oData['restaurant_id'] = $id;
	    				$oData['created_by'] 	= $data['userdata'][0]->user_id;	
	    				$oData['created_date']	= date("Y-m-d H:i:s");
	    				$ownerId                = $this->Restaurant_model->addRestaurantOwner($oData);

	    			}
	    			
	    			
	    			$localities =$this->input->post('localities');
	    			$deleteLoc  =$this->Restaurant_model->updateRestaurantLocality(array('restaurant_id'=>'0'),$id);
	    			if(count($localities)>0){
	    				$localities =implode(",",$localities);
	    				$deleteLoc  =$this->Restaurant_model->updateRestaurantLocality(array('restaurant_id'=>$id),"",$localities);
	    			}
	    			
	    			if (sizeof($res_id)>0) {
	    				$this->session->set_flashdata('success_msg', "Restaurant Details updated successfully!");
	    				if($data['userdata'][0]->role_id == $this->admin_Role || $data['userdata'][0]->role_id == $this->sales_Role){
	    					redirect('Restaurants/index');
	    				}else{
	    					redirect('Restaurants/owners');
	    				}
	    			}
	    			else{
	    				$this->session->set_flashdata('error_msg', "Something went wrong while updating restaurant details");
	    				redirect('Restaurants/editRestaurants');
	    			}
	    		}
	    	}
	    	
	    }

	    $this->load->view('Elements/header',$data);
	    $this->load->view('Restaurants/edit_restaurant');
	    $this->load->view('Elements/footer');
	    
	}
	else{
		redirect('Restaurants/index');
	}
}

	/**
	 * function to delete Restaurant details 
	 * @author Rashmi Nayani
	 * Created date: 09-10-2017 7:41 PM
	 */
	public function deleteRestaurantDetail(){
		$data['userdata']	= $this->session->userdata('current_user');
		$resId    			= $this->input->post('restaurant_id');

		$ownerData['is_active'] 	= "0";
		$ownerData['updated_by'] 	= $data['userdata'][0]->user_id;
		$ownerData['updated_date'] 	= date("Y-m-d H:i:s");	
		
		$result = $this->Restaurant_model->editRestaurantDetail($ownerData,$resId);
		if($result>0){
			$response = array("success"=>"1","message"=>"Restaurant details delete successfully");
		}	
		else{
			$response = array("success"=>"0","message"=>"Something went wrong while delete Restaurant details");
		}
		echo json_encode($response);
		exit;
	}

	/**
	 * function to get restaurants owners Listing
	 * @author Rashmi Nayani
	 * Created date: 09-10-2017 7:41 PM
	 */
	function ownersList(){
		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();
		$ownerRole 			= $this->config->item('restaurant_owner_role');
		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']   	= $submenuArray;

		$data['owners'] = $this->Restaurant_model->getAllOwnersDetails($ownerRole);
		//echo "<pre>";print_r($data['owners']);exit();

		
		$this->load->view('Elements/header',$data);
		$this->load->view('Restaurants/owners_list');
		$this->load->view('Elements/footer');
	}

	/**
	 * deafult function call when create new owner
	 * @author Rashmi Nayani
	 * Created date: 24-10-2017 03:15 PM
	 */
	function addOwner(){
		
		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$data['resList'] 	= $this->Restaurant_model->getAllOwnerRestaurant();
		$submenuArray 		= array();
		
		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']   	= $submenuArray;
		$data['countryList'] 	=$this->Restaurant_model->getCountry();

		if ($this->input->post('add')=='Save') {
			
			$this->form_validation->set_rules('fname','First Name', 'required');//|callback_isNameExist
			$this->form_validation->set_rules('lname', 'Last Name', 'required');
			$this->form_validation->set_rules('restaurant_id', 'Restaurant Branch', 'trim');
			$this->form_validation->set_rules('contact_no', 'Contact No', 'required|max_length[8]|regex_match[/^[a-z0-9]+$/]|min_length[8]');//|is_unique[tbl_users.contact_no]
			$this->form_validation->set_rules('email', 'Email', 'required|callback_isUserEmailExist|valid_email');
			
			if ($this->form_validation->run() == FALSE){
				
			}else{
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
				
				
				/*$random = randomPassword();*/
				$random = "mughalmahal2019kw";
				$encrypt = $this->config->item('encryption_key');
				$encryptpassword = md5($encrypt.$random);
				$decryptPassword = $encrypt.$random;

				$ownerData['first_name'] 	= trim($this->input->post('fname'));
				$ownerData['last_name'] 	= trim($this->input->post('lname'));
		        
				$ownerData['contact_no']	= trim($this->input->post('contact_no'));
				$ownerData['email'] 		= trim($this->input->post('email'));
				$ownerData['password']		= $encryptpassword;
		         
				$ownerData['role_id'] 		= $this->config->item('restaurant_owner_role');
				$ownerData['created_by'] 	= $data['userdata'][0]->user_id;	
				$ownerData['created_date']	= date("Y-m-d H:i:s");

				
				$result = $this->Restaurant_model->addOwnerDetail($ownerData);
				if (sizeof($result)>0)
				 {
					$restaurantOwners['restaurant_id'] 	= trim($this->input->post('restaurant_id'));
					$restaurantOwners['fk_user_id'] 	= $result;
					$restaurantOwners['created_by'] 	= $data['userdata'][0]->user_id;
					$restaurantOwners['created_date']	= date("Y-m-d H:i:s");
					if($restaurantOwners['restaurant_id'] !="")
					{
						$this->Restaurant_model->restaurantOwnersData($restaurantOwners);
					}

				}
				if (sizeof($result)>0)
				 {
					//sending an email on the manager email of its credentials
					$emailData['login_link']    = site_url('Login');
					$emailData['email_template']= 'credentials';
					$emailData['to_email']		= $ownerData['email'];
					$emailData['password']		= $encrypt.$random;
					$emailData['base_url']		= base_url();
					$emailData['user_name']		= $ownerData['first_name'].' '.$ownerData['last_name'];				
					$emailData['subject']		= 'Registration Successfully';
					$mails = $this->sendMail($emailData);

					$this->session->set_flashdata('success_msg', "Manager Details Added successfully!");
					redirect('Restaurants/ownersList');
				}
				else{
					$this->session->set_flashdata('error_msg', "Something went wrong while adding manager details");
					redirect('Restaurants/addOwner');
				}
			}
		}
		$this->load->view('Elements/header',$data);
		$this->load->view('Restaurants/add_owner');
		$this->load->view('Elements/footer');
	}
	
	
	

	/**
	 * function to check duplicate owner name Exist
	 * @author Rashmi Nayani
	 * Created date: 24-10-2017 1:20 PM
	 */

	function isNameExist($name,$id){
		
		$ownerRole 	= $this->config->item('restaurant_owner_role');
		$data = $this->Restaurant_model->getDuplicateName($ownerRole,$name,$id);
		if(sizeof($data)>0){
			$this->form_validation->set_message('isNameExist', 'This owner name is already exist');
			return false;
		}
		else{
			return true;
		}
	}
	/**
	 * function to edit owner details 
	 * @author Rashmi Nayani
	 * Created date: 24-10-2017 4:30 PM
	 */
	function editOwner($id){
		
		$ownerRole 	= $this->config->item('restaurant_owner_role');
		$ownerDetails =$this->Restaurant_model->getAllOwnersDetails($ownerRole,$id);
		$data['resList'] 		= $this->Restaurant_model->getAllOwnerRestaurant($id);
		$user_id= $this->input->post('user_id');
		if ($id != null && sizeof($ownerDetails)>0) {
			$data['userdata']	= $this->session->userdata('current_user');
			$data['menu']   	= $this->menu;
			$submenu   			= $this->submenu;
			$submenuArray 		= array();

			foreach($submenu as $key=>$value){

				$submenuArray[$value->parent_page_id][] = $value;
			}
			$data['submenu']   	 	= $submenuArray;
			$data['countryList'] 	=$this->Restaurant_model->getCountry();
			$data['owner_data'] 	= $ownerDetails;

			if ($this->input->post('update')=='Update') {

				$this->form_validation->set_rules('fname','First Name', 'required');
				$this->form_validation->set_rules('lname', 'Last Name', 'required');
				$this->form_validation->set_rules('restaurant_id', 'Restaurant Branch', 'trim');
				$this->form_validation->set_rules('contact_no', 'Contact No', 'required|max_length[13]|regex_match[/^[a-z0-9]+$/]|min_length[8]|callback_isUserContactExist');
				if ($this->form_validation->run() == FALSE){
				}
				else{
					
					$ownerData['first_name'] 	= trim($this->input->post('fname'));
					$ownerData['last_name'] 	= trim($this->input->post('lname'));
					$ownerData['contact_no']	= trim($this->input->post('contact_no'));
					$ownerData['updated_by'] 	= $data['userdata'][0]->user_id;	
					$ownerData['updated_date']	= date("Y-m-d H:i:s");
					$result = $this->Restaurant_model->editOwnerDetail($ownerData,$id);
					if (sizeof($result)>0) {
						$restaurantOwners['restaurant_id'] 	= trim($this->input->post('restaurant_id'));
						$restaurantOwners['fk_user_id'] 	= $user_id;
						$restaurantOwners['updated_by'] 	= $data['userdata'][0]->user_id;	
						$restaurantOwners['updated_date']	= date("Y-m-d H:i:s");
						$deleteOwner    =$this->Restaurant_model->deleteRestaurantOwner("",$user_id);

						if($restaurantOwners['restaurant_id'] !=""){
							$this->Restaurant_model->restaurantOwnersData($restaurantOwners);
						}
					}
					if (sizeof($result)>0) {

						$this->session->set_flashdata('success_msg', "Manager Details updated successfully!");
						redirect('Restaurants/ownersList');
					}
					else{
						$this->session->set_flashdata('error_msg', "Something went wrong while updating manager details");
						redirect('Restaurants/editOwner');
					}
					
				}
			}
			$this->load->view('Elements/header',$data);
			$this->load->view('Restaurants/edit_owner');
			$this->load->view('Elements/footer');
			
		}
		else{
			redirect('Restaurants/ownersList');
		}
	}

	/**
	 * function to delete owner details 
	 * @author Rashmi Nayani
	 * Created date: 24-10-2017 6:30 PM
	 */
	public function deleteOwnerDetail(){
		$data['userdata']	= $this->session->userdata('current_user');
		$userId    			= $this->input->post('user_id');

		$ownerData['is_active'] 	= "0";
		$ownerData['updated_by'] 	= $data['userdata'][0]->user_id;
		$ownerData['updated_date'] 	= date("Y-m-d H:i:s");	
		
		$result         = $this->Restaurant_model->editOwnerDetail($ownerData,$userId);
		$deleteOwner    =$this->Restaurant_model->deleteRestaurantOwner("",$userId);
		if($result>0){
			$response = array("success"=>"1","message"=>"user details delete successfully");
		}	
		else{
			$response = array("success"=>"0","message"=>"Something went wrong while delete user details");
		}
		echo json_encode($response);
		exit;
	}

	/**
	 * deafult function call when restaurant owner is logged in
	 * @author Vaibhav Mehta
	 * Created date: 08-01-2018 02:50 PM
	 */
	function owners(){
		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();

		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu'] = $submenuArray;
		$data['restaurants'] = array();
		
		$restMang 		 = $this->config->item('restaurant_manager_role');
		$resId 			 = $this->getRestaurantForRoleBaseAccess();

		$restaurants 	 = $this->Restaurant_model->getRestaurantDetails($resId);
		$data['owners']  = $this->Restaurant_model->getAllManagersDetails($restMang,$resId);
		$data["rating"]  = $this->Rating_model->getAllRestaurantAvelibility($resId);
		if(count($restaurants)>0)
		{
			$data['restaurants'] = $restaurants[0];
		}


		$data['resId'] = $resId;

		$restaurantDishes	 = $this->Restaurant_model->getRestaurantsDishes($resId);
		$dishes          	 =array();
		if(count($restaurants)>0)
		{
			$data['restaurants'] = $restaurants[0];
		}
		$catId =0;
		$cnt   =0;
		if(count($restaurantDishes)>0){
			
			foreach ($restaurantDishes as $key => $value) {
				if($catId == $value->category_id){
					$cnt ++;
				}else{
					$cnt   = 1;
					$catId = $value->category_id;
					$dishes[$catId]['category_id']   =$catId;
					$dishes[$catId]['category_name'] =$value->category_name;
				}
				$dishes[$catId]['total_dish']    =$cnt;
			}
			
		}	
		$data['dishes'] =$dishes;

		$this->load->view('Elements/header',$data);
		$this->load->view('Restaurants/owners');
		$this->load->view('Elements/footer');
	}

	function RestaurantDetails($id){

		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();

		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu'] 	 = $submenuArray;
		$data['days']       =$this->config->item('days');

		//restaurant opening time
		$restaurantTime = $this->Webservice_customer_model->getRestaurantTime($id);
		$resData =array();
		
		foreach ($restaurantTime as $k1 => $v1) {
			
			if($v1->is_approved == 1 ){
			 	$resData[$v1->day][$k1]['from_time'] =date('h:i A',strtotime($v1->from_time));
			 	$resData[$v1->day][$k1]['to_time']   =date('h:i A',strtotime($v1->to_time));
			}
			else{
				$resData[$v1->day] =array();
			}
			
		 	
		 }
		$data['resData'] =$resData;
		$data['restaurants'] = array();
		$restaurants 	 	 = $this->Restaurant_model->getRestaurantDetails($id);
		$restaurantDishes	 = $this->Restaurant_model->getRestaurantsDishes($id);
		$dishes          	 =array();
	
		if(count($restaurants)>0)
		{
			$data['restaurants'] = $restaurants[0];
		}
		$catId =0;
		$cnt   =0;
		if(count($restaurantDishes)>0){
			
			foreach ($restaurantDishes as $key => $value) {
				if($catId == $value->category_id){
					$cnt ++;
				}else{
					$cnt   = 1;
					$catId = $value->category_id;
					$dishes[$catId]['category_id']   =$catId;
					$dishes[$catId]['category_name'] =$value->category_name;
				}
				$dishes[$catId]['total_dish']    =$cnt;
			}
			
		}
		$data['dishes'] =$dishes;
		$this->load->view('Elements/header',$data);
		$this->load->view('Restaurants/restaurant_details');
		$this->load->view('Elements/footer');
	}

	function getCategoryDish()
	{
		$res_id           =(isset($_POST['res_id']))?$_POST['res_id']:"";
		$cat_id           =(isset($_POST['cat_id']))?$_POST['cat_id']:"";
		$dish_id          =(isset($_POST['dish_id']))?$_POST['dish_id']:"";
		$is_best_dish     =$_POST['is_best'];
		$is_dish          =$_POST['is_dish'];
		
		$data    =array();
		if($is_dish == "1")
		{
			$restaurantDishes	 = $this->Restaurant_model->getRestaurantsDishes($res_id,$cat_id,$dish_id,$is_best_dish);

			if(count($restaurantDishes)>0)
			{
				foreach ($restaurantDishes as $key => $value)
				{
					$data[$value->product_id]['dish_name'] =$value->product_en_name;
					$data[$value->product_id]['dish_price'] =$value->dish_price ."  KD";
				}
			}
		}
		else if($is_dish == "0")
		{
			$restaurantDishes	 = $this->Restaurant_model->getRestaurantsDishes($res_id,$cat_id,$dish_id,$is_best_dish);

			if(count($restaurantDishes)>0){
				if($restaurantDishes[0]->choice_id !=""){
					$ch      =explode(',', $restaurantDishes[0]->choice_id);
					$chPrice =explode(',', $restaurantDishes[0]->choice_price);

					foreach ($ch as $ckey => $cvalue) {
						$choice =$this->Restaurant_model->restaurantDisheChoice($cvalue);
						if(count($choice)>0){
							$data[$ckey]['choice_name']          =$choice[0]->choice_name;
							$data[$ckey]['choice_category_name'] =$choice[0]->choice_category_name;
							$data[$ckey]['choice_price']         =$chPrice[$ckey]."  KD";
							$data[$ckey]['choice_id']            =$cvalue;
						}
					}
				}

			}
		}
		if(count($data)>0){
			$response['success']=1;
			$response['message']=$data;
		}else{
			$response['success']=0;
			$response['message']="Dishes not found";
		}

		echo json_encode($response);exit;
	}

	/**
	 * function for get dish list to add for restaurant
	 * @author Manisha Kanazariya
	 * Created date: 19-02-2018 11:50 PM
	 */
	function dishList(){

		$data['dishList'] = $this->Dishes_model->getAllDishes("","");
		$response         =array();

	    if(count($data['dishList'])>0){
	    	foreach ($data['dishList'] as $key => $value) {
	    		
	    		$response[$key]['lable']=$value->product_id;
	    		$response[$key]['value']=$value->product_en_name;

	    	}
	    }
	    echo json_encode($response);exit;
	}

	function addRestaurantDish(){

		 if($_POST['dishId'] ==''){
		 	$response['success']=0;
		 	$response['message']='Select One dish';

		 }else if($_POST['resId']==''){
		 	$response['success']=0;
		 	$response['message']='Something went wrong please try again';
		 }else{
		 	$data['fk_restaurant_id'] =$_POST['resId'];
		 	$data['fk_dish_id']       =$_POST['dishId'];

		 	$addResDish =$this->Restaurant_model->addRestaurantDish($data);
		 	if($addResDish >0){
		 		$response['success']=1;
		 		$response['message']='';
		 	}
		 	else{
		 		$response['success']=0;
		 	    $response['message']='Something went wrong please try again';
		 	}
		 }
		 echo json_encode($response);exit;
	}

	function addLocality(){
		$csv = array_map('str_getcsv', file("assets/uploads/mughal_mahal_new_localities.csv"));
		unset($csv[0]);
		
		foreach ($csv as $key => $value) {

			$url ='https://maps.google.com/maps/api/geocode/json?address='.$value[0].'&sensor=false&region='.$value[6];
			$url =str_replace(" ","%20",$url);

	    	$geocode  			 = file_get_contents("$url");
		    $output 			 = json_decode($geocode);
		    if($output->results[0]->geometry->location->lat != ""){

			    $geo[$key]['name']    =$value['0'];
			    $geo[$key]['lat'] 	  =$output->results[0]->geometry->location->lat;
			    $geo[$key]['lon'] 	  =$output->results[0]->geometry->location->lng;
			    $geo[$key]['name_ar'] =$value['7'];
		    	
		    }

			
		}
		
		    $addLocality =$this->Restaurant_model->addLocality($geo);
		    echo $addLocality ."Localities are added";

	}

	/**
	 * function for add dish list  for restaurant
	 * @author Manisha Kanazariya
	 * Created date: 24-04-2018 11:50 PM
	 */
	function addDishes($restaurantId){
		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();
		$data['error']      ="";
		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu'] = $submenuArray;
		$data['res_id']	 = $restaurantId;
		$data['dishList']	 = $this->Restaurant_model->getAllDishes($restaurantId);
		$data['choiceList'] = $this->Choice_model->getAllChoices();
		$resDishes           =$this->Restaurant_model->getRestaurantDishes($restaurantId);

		if(isset($_POST['add']) && $_POST['add'] =='Save'){
			$dish =$this->input->post('dish');
			//dish validation (dish is required) 

			if($dish[0] ==""){
				$data['error'] ="Dish is required";
			}else{
				$dishPrice    =$this->input->post('dish_price');
				$choices      =$this->input->post('choice');
				$choice_price =$this->input->post('choice_price');
				$resDishdata  =array();

				foreach ($dish as $key => $value) {

					$resDishdata[$key]['fk_restaurant_id'] =$restaurantId;
					$resDishdata[$key]['fk_dish_id'] 	   =$value;
					$resDishdata[$key]['dish_price'] 	   =$dishPrice[$key];
					$choiceid ='';
					$choiceprice ='';

					//add choices and its price for the dish
					foreach ($choices[$key] as $key1 => $value1) {
						if($value1 !=''){
							$choiceid    .=$value1.",";
							$choiceprice .=($choice_price[$key][$key1] != "")?$choice_price[$key][$key1]:'0';
							$choiceprice .=',';
						}
					}
					$resDishdata[$key]['choice_id']       =rtrim($choiceid,",");
					$resDishdata[$key]['choice_price']    =rtrim($choiceprice,",");
				}
				
				if(!empty($resDishdata)){
					$insertDish =$this->Restaurant_model->addRestaurantDishes($resDishdata);
					if($insertDish>0){
						redirect('Restaurants/restaurantDishes/'.$restaurantId);
					}else{
						$data['error'] ="Something went wrong, Please try again.";
					}
				}
			}

		}
		$this->load->view('Elements/header',$data);
		$this->load->view('Restaurants/addDishes');
		$this->load->view('Elements/footer');
	}
	
	/**
	 * function for delete dish from restaurant
	 * @author Manisha Kanazariya
	 * Created date: 19-05-2018 11:50 PM
	 */
	function deleteRestaurantDish(){
		$res_id  =$this->input->post('res_id');
		$dish_id =$this->input->post('dish_id');
		$deleteDish =$this->Restaurant_model->deleteRestaurantDish($res_id,$dish_id);
		if($deleteDish>0){
			$response['success'] ='1';
			$response['message'] ='';
		}else {
			$response['success'] ='0';
			$response['message'] ='The dish not deleted successfully ,Please try again!';
		}
		echo json_encode($response);exit;
	}

	/**
	 * function for delete dish choice from restaurant
	 * @author Manisha Kanazariya
	 * Created date: 19-06-2018 11:50 PM
	 */
	function deleteRestaurantDishChoice(){
		$res_id     =$this->input->post('res_id');
		$dish_id    =$this->input->post('dish_id');
		$choice_id  =$this->input->post('choice_id');
		$restaurantDishes	 = $this->Restaurant_model->getRestaurantsDishes($res_id,"",$dish_id);

		$choices       =explode(',', $restaurantDishes[0]->choice_id);
		$choices_price =explode(',', $restaurantDishes[0]->choice_price);
		if (($key = array_search($choice_id, $choices)) !== false) {
	   		unset($choices[$key]);
	   		unset($choices_price[$key]);
	   		$choices =implode(",",$choices);
	   		$choices_price =implode(",",$choices_price);

	   		$data['fk_restaurant_id'] =$res_id;
			$data['fk_dish_id']       =$dish_id;
			$data['choice_id']        =$choices;
			$data['choice_price']     =$choices_price;
			$updateResDish =$this->Restaurant_model->UpdateRestaurantDish($res_id,$dish_id,$data);
		 	if($updateResDish>0){
				$response['success'] ='1';
				$response['message'] ='';
			}else {
				$response['success'] ='0';
				$response['message'] ='The dish choice not deleted successfully ,Please try again!';
			}
			echo json_encode($response);exit;
		}
		
	}

	/**
	 * function for list sales person
	 * @author Manisha Kanazariya
	 * Created date: 21-05-2018 11:50 PM
	 */
	function sales_person(){
		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();

		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']   	= $submenuArray;
		
		$salesData =$this->Restaurant_model->getSalesPersonDetails();

		if(count($salesData)>0){
			$data['salesData'] =$salesData;
		}else{
			$data['salesData'] =array();
		}
		$this->load->view('Elements/header',$data);
		$this->load->view('Restaurants/sales_person_list');
		$this->load->view('Elements/footer');
	}

	/**
	 * function for add sales person
	 * @author Manisha Kanazariya
	 * Created date: 21-05-2018 11:50 PM
	 */
	function addSalesPerson()
	{
		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();

		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']   	= $submenuArray;

		if ($this->input->post('add')=='Save') {
			
			$this->form_validation->set_rules('fname','First Name', 'required');
			$this->form_validation->set_rules('lname', 'Last Name', 'required');
			$this->form_validation->set_rules('contact_no', 'Contact No', 'required|max_length[8]|regex_match[/^[a-z0-9]+$/]|min_length[8]');//|is_unique[tbl_users.contact_no]
			$this->form_validation->set_rules('email', 'Email', 'required|callback_isUserEmailExist|valid_email');
			
			if ($this->form_validation->run() == FALSE){
				
			}else{
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

				$salesData['first_name'] 	= trim($this->input->post('fname'));
				$salesData['last_name'] 	= trim($this->input->post('lname'));
		        
				$salesData['contact_no']	= trim($this->input->post('contact_no'));
				$salesData['email'] 		= trim($this->input->post('email'));
				$salesData['password']		= $encryptpassword;
		         
				$salesData['role_id'] 		= $this->config->item('sales_role');
				$salesData['created_by'] 	= $data['userdata'][0]->user_id;	
				$salesData['created_date']	= date("Y-m-d H:i:s");
				
				$result = $this->Restaurant_model->addSalesPersonDetails($salesData);
				
				if (sizeof($result)>0) 
				{
					//sending an email on the sales persion email of its credentials
					$emailData['login_link']    = site_url('Login');
					$emailData['email_template']= 'credentials';
					$emailData['to_email']		= $salesData['email'];
					$emailData['password']		= $encrypt.$random;
					$emailData['base_url']		= base_url();
					$emailData['user_name']		= $salesData['first_name'].' '.$salesData['last_name'];				
					$emailData['subject']		= 'Registration Successfully';
					$mails = $this->sendMail($emailData);

					$this->session->set_flashdata('success_msg', "Sales person details Added successfully!");
					redirect('Restaurants/sales_person');
				}
				else{
					$this->session->set_flashdata('error_msg', "Something went wrong while adding sales person details");
					redirect('Restaurants/addSalesPerson');
				}
			}
		}

		$this->load->view('Elements/header',$data);
		$this->load->view('Restaurants/add_sales_person');
		$this->load->view('Elements/footer',$data);
	}

	/**
	 * function for edit sales person
	 * @author Manisha Kanazariya
	 * Created date: 21-05-2018 11:50 PM
	 */
	function editSalesPerson($user_id)
	{
		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();

		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']   	= $submenuArray;
		$data['salesData']  =$this->Restaurant_model->getSalesPersonDetails($user_id);

 		if ($this->input->post('update')=='Update') {
			
			$this->form_validation->set_rules('fname','First Name', 'required');
			$this->form_validation->set_rules('lname', 'Last Name', 'required');
			$this->form_validation->set_rules('contact_no', 'Contact No', 'required|max_length[8]|regex_match[/^[a-z0-9]+$/]|min_length[8]|callback_isUserContactExist');
			
			if ($this->form_validation->run() == FALSE){
			}else{
				

				$salesData['first_name'] 	= trim($this->input->post('fname'));
				$salesData['last_name'] 	= trim($this->input->post('lname'));
		        
				$salesData['contact_no']	= trim($this->input->post('contact_no'));
				$salesData['email'] 		= trim($this->input->post('email'));
				$salesData['updated_by'] 	= $data['userdata'][0]->user_id;	
				$salesData['updated_date']	= date("Y-m-d H:i:s");
				
				$result = $this->Restaurant_model->updateSalesPersonDetails($salesData,$user_id);
				
				if (sizeof($result)>0) {
					$this->session->set_flashdata('success_msg', "Sales person details updated successfully!");
					redirect('Restaurants/sales_person');
				}
				else{
					$this->session->set_flashdata('error_msg', "Something went wrong while updating sales person details");
					redirect('Restaurants/editSalesPerson/'.$user_id);
				}
			}
		}

		$this->load->view('Elements/header',$data);
		$this->load->view('Restaurants/edit_sales_person');
		$this->load->view('Elements/footer',$data);
	}

	/**
	 * function for delete sales person
	 * @author Manisha Kanazariya
	 * Created date: 22-05-2018 11:50 PM
	 */
	function deleteSalesPerson($user_id){
		$result = $this->Restaurant_model->updateSalesPersonDetails(array('is_active'=>'0'),$user_id);
			
			if ($result >0) {
				$this->session->set_flashdata('success_msg', "Sales person deleted successfully!");
			}
			else{
				$this->session->set_flashdata('error_msg', "Something went wrong while deleting sales person details");
			}
			redirect('Restaurants/sales_person');	
	}

	/**
	 * function for list restaurant time
	 * @author Manisha Kanazariya
	 * Created date: 24-05-2018 11:50 PM
	 */
	function addRestaurantTime($res_id){
		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();

		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']   	= $submenuArray;
		$data['days']       =$this->config->item('days');
		$data['resId']      =$this->getRestaurantForRoleBaseAccess();
		$restaurantTime = $this->Restaurant_model->getRestaurantTimeData($res_id);
		$resData =array();
		
		foreach ($restaurantTime as $k1 => $v1) {
			$resData[$v1->day][$k1]['is_approved']=$v1->is_approved;
			$resData[$v1->day][$k1]['time_id']    =$v1->restaurant_days_time_id;
			if($v1->restaurant_days_time_id !=''){
				$resData[$v1->day][$k1]['from_time']  =date('H:i ',strtotime($v1->update_from_time));
		 		$resData[$v1->day][$k1]['to_time']    =date('H:i ',strtotime($v1->update_to_time));
			}else{
				$resData[$v1->day][$k1]['from_time']  ="";
		 		$resData[$v1->day][$k1]['to_time']    ="";
			}
		}
		$data['resData'] =$resData;
		$data['res_id']  =$res_id;
		$this->load->view('Elements/header',$data);
		$this->load->view('Restaurants/addRestaurantTime');
		$this->load->view('Elements/footer',$data);
	}
	/**
	 * function for update restaurant time
	 * @author Manisha Kanazariya
	 * Created date: 24-05-2018 11:50 PM
	 */
	function updateRestaurantTime()
	{
		$user      =$this->session->userdata('current_user');
		$id        =$this->input->post('id');
		$day       =$this->input->post('day');
		$res_id    =$this->input->post('res_id');
		$from_time =date('H:i:s',strtotime($this->input->post('from_time')));
		$to_time   =date('H:i:s',strtotime($this->input->post('to_time')));

		$resId     = $this->getRestaurantForRoleBaseAccess();
		if($id != "")
		{

			$getTime =$this->Restaurant_model->getRestaurantTime($id);
			
			if($getTime[0]->from_time != trim($from_time) || $getTime[0]->to_time !=trim($to_time))
			{
				if($resId !="")
				{
					$newData['update_from_time'] =$from_time;
					$newData['update_to_time']   =$to_time;
					/*$newData['is_approved']      =0;*/
				}
				else
				{
					$newData['update_from_time'] =$from_time;
					$newData['update_to_time']   =$to_time;
					$newData['from_time']        =$from_time;
					$newData['to_time']          =$to_time;
					$newData['is_approved']      =1;
				}

				
				$newData['updated_by']       =$user[0]->user_id;
				$newData['updated_date']     =date('Y-m-d H:i:s');
				$updateTime =$this->Restaurant_model->updateRestaurantTime($id,$newData);
				
				if($updateTime > 0)
				{
				 	$response['success'] ='1';
				 	$response['message'] ="";
				}else
				{
					$response['success'] ='0';
				 	$response['message'] =array('from_time'=>$getTime[0]->from_time,'to_time'=>$getTime[0]->to_time);
				} 
			}
			else
			{
				$response['success'] ='1';
				$response['message'] =array('from_time'=>$from_time,'to_time'=>$to_time);
			}

		}else
		{
			$resDayData             =$this->Restaurant_model->getRestaurantTimeDetail($res_id,$day);
		    $newData['fk_restaurant_days_id'] =$resDayData[0]->restaurant_days_id;
		   
		    if($resId !="")
			{
				$newData['update_from_time'] =$from_time;
				$newData['update_to_time']   =$to_time;
				$newData['is_approved']      =0;
			}else
			{
				$newData['update_from_time'] =$from_time;
				$newData['update_to_time']   =$to_time;
				$newData['from_time']        =$from_time;
				$newData['to_time']          =$to_time;
				$newData['is_approved']      =1;
			}
		   
			$newData['created_by']            =$user[0]->user_id;

			$addTime                          =$this->Restaurant_model->addRestaurantTime($newData);
			if($addTime>0){
			 	$response['success'] ='1';
			 	$response['message'] ="";
			}else{
				$response['success'] ='0';
			 	$response['message'] ="";
			} 
		}
		
		echo json_encode($response);exit;
	}

	/**
	 * function for delete restaurant time
	 * @author Manisha Kanazariya
	 * Created date: 24-05-2018 11:50 PM
	 */
	function deleteTimeTime(){
		$id             =$this->input->post('id');
		$deleteTimeTime =$this->Restaurant_model->deleteTimeTime($id);
		if($deleteTimeTime>0){
		 	$response['success'] ='1';
		 	$response['message'] ="";
		}else{
			$response['success'] ='0';
		 	$response['message'] ="";
		}
		echo json_encode($response);exit;
	}
	/**
	 * function for appoval time restaurant time by admin
	 * @author Manisha Kanazariya
	 * Created date: 25-05-2018 11:50 PM
	 */
	function checkRestaurantTime($res_id){
		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();

		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']   	= $submenuArray;
		$data['days']       = $this->config->item('days');
		$restaurantTime     = $this->Restaurant_model->checkRestaurantTime($res_id);
		$resData =array();
		foreach ($restaurantTime as $k1 => $v1) 
		{
			if($v1->restaurant_days_time_id)
			{
				if($v1->from_time !=$v1->update_from_time || $v1->to_time !=$v1->update_to_time || $v1->is_approved ==0)	
				{
					$resData[$v1->day][$k1]['approved']    =0;
				}
				else
				{
					$resData[$v1->day][$k1]['approved']    =1;
				}

				$resData[$v1->day][$k1]['time_id']         = $v1->restaurant_days_time_id;
				if($v1->from_time =="00:00:00" && $v1->to_time =="00:00:00")
				{
					$resData[$v1->day][$k1]['from_time']         ="";
			 		$resData[$v1->day][$k1]['to_time']           ="";
				}
				else
				{
					$resData[$v1->day][$k1]['from_time']         =date('h:i A',strtotime($v1->from_time));
			 		$resData[$v1->day][$k1]['to_time']           =date('h:i A',strtotime($v1->to_time));
				}
			 	
			 	$resData[$v1->day][$k1]['update_from_time']  =date('h:i A',strtotime($v1->update_from_time));
			 	$resData[$v1->day][$k1]['update_to_time']    =date('h:i A',strtotime($v1->update_to_time));
			}
			

			
		 }
		$data['resData'] =$resData;
		$data['res_id']  =$res_id;
		$this->load->view('Elements/header',$data);
		$this->load->view('Restaurants/checkRestaurantTime');
		$this->load->view('Elements/footer',$data);
	}
	/**
	 * function for approve restaurant time by admin
	 * @author Manisha Kanazariya
	 * Created date: 25-05-2018 11:50 PM
	 */
	function approveRestaurantTime(){
		$time_id   =$this->input->post('time_id');
		$user      =$this->session->userdata('current_user');
		$getTime   =$this->Restaurant_model->getRestaurantTime($time_id);
		$newData['from_time'] =$getTime[0]->update_from_time;
		$newData['to_time']   =$getTime[0]->update_to_time;

		$newData['is_approved']      =1;
		$newData['updated_by']       =$user[0]->user_id;
		$newData['updated_date']     =date('Y-m-d H:i:s');
		$updateTime =$this->Restaurant_model->updateRestaurantTime($time_id,$newData);
		
		if($updateTime > 0)
		{
		 	$response['success'] ='1';
		 	$response['message'] ="";
		}else
		{
			$response['success'] ='0';
		 	$response['message'] ="";
		} 
		echo json_encode($response);exit;
	}

	function checkFromTime(){
		$res_id =$this->input->post('res_id');
		$day    =$this->input->post('day');
		$id     =$this->input->post('id');
		$from   =date('H:i',strtotime($this->input->post('from')));
		$to     =date('H:i',strtotime($this->input->post('to')));
		$checkFromTime =$this->Restaurant_model->checkFromTime($id,$res_id,$day);
		
		if(count($checkFromTime)>0){

			foreach ($checkFromTime as $key => $value) {

				$last_from =date('H:i',strtotime($value->update_from_time));
				$last_to   =date('H:i',strtotime($value->update_to_time));
				/*if($value->is_approved == 1 ){
					$last_from =date('H:i',strtotime($value->from_time));
				    $last_to   =date('H:i',strtotime($value->to_time));
				 }else if($value->from_time != "" && $value->update_from_time == "00:00:00" ){
				 	$last_from =date('H:i',strtotime($value->from_time));
					$last_to   =date('H:i',strtotime($value->to_time));
				 }else if($value->from_time != "" && $value->from_time != "00:00:00"){
				 }*/

				
				if($from >= $last_from && $from <= $last_to){
					//exist duration where from time between the duration  
					$response['case']    ='exist duration where from time between the duration';
					$response['success'] ='0';
					$get[$value->update_from_time] ='1';
					break;
				}
				if($to >=$last_from && $to <=$last_to){
					//exist duration where to time between the duration 
					$response['case']     ='exist duration where to time between the duration';
					$response['success']  ='0';
					$get[$value->update_from_time] ='2';
					break;
				}
				if($from < $last_from && $to > $last_to ){
					//exist time duration in database between given from and to time
					$response['case']     ='1exist time duration which  between given from and to time';
					$response['success']  ='0';
					$get[$value->update_from_time] ='3';
					break;
				}
				
				if(!isset($response['success'])){
					
					$response['case']    ='4 valid given  from and to time';
				    $response['success'] ='1';
				    $get[$value->update_from_time] ='4';
				}
			}
		
		}else{
			//valid given  from and to time
			$response['case']    ='5 valid given  from and to time';
			$response['success'] ='1';
		}
		
		echo json_encode($response);exit;
	}

	function restaurantDishes($res_id){
		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();
		$resId     = $this->getRestaurantForRoleBaseAccess();
		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu'] 	 = $submenuArray;
		$data['days']       =$this->config->item('days');

		//restaurant opening time
		$resData =array();
		$data['resId']       = $resId;
		$data['restaurants'] = array();
		$restaurants 	 	 = $this->Restaurant_model->getRestaurantDetails($res_id);
		$restaurantDishes	 = $this->Restaurant_model->getRestaurantsDishes($res_id);
		$dishes          	 =array();
		if(count($restaurants)>0)
		{
			$data['restaurants'] = $restaurants[0];
		}
		$catId         =0;
		$cnt           =0;
		$bestDishCount =0;

		if(count($restaurantDishes)>0){
			$dishes[1]['category_id']   =1;
			$dishes[1]['category_name'] ='Best Dishes';
			foreach ($restaurantDishes as $key => $value) {
				if($catId == $value->category_id){
					$cnt ++;
				}else{
					$cnt   = 1;
					$catId = $value->category_id;
					$dishes[$catId]['category_id']   =$catId;
					$dishes[$catId]['category_name'] =$value->category_name;
				}
				$dishes[$catId]['total_dish']    =$cnt;

				if($value->is_best_dishes ==1)
				{
					$bestDishCount ++;
				}
			}
			
		}
	
		$dishes[1]['total_dish']    =$bestDishCount;
		$data['dishes'] =$dishes;
		$this->load->view('Elements/header',$data);
		$this->load->view('Restaurants/restaurant_dishes');
		$this->load->view('Elements/footer');
	}

	function setDishId(){
		$res_id  =$this->input->post('res_id');
		$dish_id =$this->input->post('dish_id');
		$_SESSION['restaurant_id'] =$res_id;
		$_SESSION['dish_id']       =$dish_id;
	    print_r($_SESSION);exit;
	}

	/**
	 * function for add dish list  for restaurant
	 * @author Manisha Kanazariya
	 * Created date: 24-04-2018 11:50 PM
	 */
	function editDish(){
		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();
		$data['error']      ="";
		$dish_id            =$this->uri->segment(3);
		$res_id             =$this->uri->segment(4);

		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']    = $submenuArray;
		$data['res_id']	    = $res_id;
		$dishChoice         =array();
		$resDishes          =$this->Restaurant_model->getRestaurantsDish($res_id,$dish_id);
		$choices            =explode(',', $resDishes[0]->choice_id);
		$choices_price      =explode(',', $resDishes[0]->choice_price);
		/* echo "<pre>";print_r($choices)	;
		 echo "<pre>";print_r($choices_price);*/

		if($resDishes[0]->choice_id != ""){
			$dishChoice        =$this->Restaurant_model->getRestaurantsDishChoice($dish_id,$resDishes[0]->choice_id);
			foreach ($dishChoice as $key => $value) {
				$k = array_search($value->choice_id, $choices);
				$dishChoice[$key]->ch_price =$choices_price[$k];
			}
		}
		$data['resDish']    =$resDishes;
		$data['dishChoice'] =$dishChoice;
		$data['choiceList'] = $this->Choice_model->getAllChoices();
		if((isset($_POST['add']) && $_POST['add'] =='Save') || (isset($_POST['update']) && $_POST['update'] =='Update')){
			    $dish =$this->input->post('dish');
			
			    $updateResDish=$this->Restaurant_model->UpdateRestaurantDish($res_id,$dish,array("choice_id"=>"","choice_price"=>""));
			    //END delete dish choice and choice price

				$dishPrice    =$this->input->post('dish_price');
				$choices      =$this->input->post('choice');
				$choice_price =$this->input->post('choice_price');
				$resDishdata  =array();
				$choiceid ='';
				$choiceprice ='';

				//add choices and its price for the dish
				foreach ($choices as $key1 => $value1) {
					if($value1 !=''){
						$choiceid    .=$value1.",";
						$choiceprice .=($choice_price[$key1] != "")?$choice_price[$key1]:'0';
						$choiceprice .=',';
					}
				}
				$resDishdata['dish_price'] 	     =$dishPrice;
				$resDishdata['choice_id']       =rtrim($choiceid,",");
				$resDishdata['choice_price']    =rtrim($choiceprice,",");
			 
			if(!empty($resDishdata)){
				$insertDish =$this->Restaurant_model->UpdateRestaurantDish($res_id,$dish,$resDishdata);

				if($insertDish <= 0){
					$this->session->set_flashdata('error_msg',"Something went wrong, Please try again.");
				}else if($insertDish >0 && isset($_POST['add']) && $_POST['add'] =='Save'){
					redirect('Restaurants/restaurantDishes/'.$res_id);
				}else{
					$this->session->set_flashdata('success_msg',"Dish updated successfully.");
					redirect('Restaurants/editDish/'.$dish_id."/".$res_id);
				}
			}
			

		}
		$this->load->view('Elements/header',$data);
		$this->load->view('Restaurants/editDish');
		$this->load->view('Elements/footer');
	}



	/**
	 * function for set ramadhan month  for restaurant
	 * @author Manisha Kanazariya
	 * Created date: 24-04-2018 11:50 PM
	 */
	function IsRamadhanMonthdata(){
		$restaurant_id       =$this->input->post('res_id');
		$data['is_ramadhan'] =$this->input->post('is_ramadhan');
		$updateData =$this->Restaurant_model->editRestaurantDetail($data,$restaurant_id);
		if($updateData>0){
			$response['success'] =1;
		}else{
			$response['success'] =0;
		}
		echo json_encode($response);exit;
	}
	

	/**
	 * function for add  best dish for restaurant
	 * @author Manisha Kanazariya
	 * Created date: 28-09-2018 11:50 PM
	 */
	function listBestDishes($res_id)
	{
		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();

		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu'] 	 = $submenuArray;
		$data['days']       =$this->config->item('days');

		$bestDish =array();
		$data['restaurants'] = array();
		$restaurants 	 	 = $this->Restaurant_model->getRestaurantDetails($res_id);
		$restaurantDishes	 = $this->Restaurant_model->getRestaurantsDishes($res_id);
		

		$dishes          	 =array();
		if(count($restaurants)>0)
		{
			$data['restaurants'] = $restaurants[0];
		}
		$catId =0;
		$cnt   =0;
		if(count($restaurantDishes)>0){
			
			foreach ($restaurantDishes as $key => $value)
			{
				
				if($value->is_best_dishes == 1)
				{
					$bestDish[$key]=$value;
					unset($restaurantDishes[$key]);
				}
			}
			
		}
		//echo "<pre>";print_r($bestDish);exit;
		$data['bestDish'] =$bestDish;
		$data['dishes']   =$restaurantDishes;
		$this->load->view('Elements/header',$data);
		$this->load->view('Restaurants/restaurant_best_dishes');
		$this->load->view('Elements/footer');
	}

	/**
	 * function for add best dish form restaurant
	 * @author Manisha Kanazariya
	 * Created date: 28-09-2018 11:50 PM
	 */
	function addBestDish($resId)
	{
		$dishId                    =$this->input->post('dishId');
		$resData['is_best_dishes'] =1;
	    $updateResDish             =$this->Restaurant_model->updateBestDish($resId,$dishId,$resData);
	    
	    if($updateResDish > 0)
	    {
	    	$response['success'] ='1';
	    	$response['message'] ='';
	    }
	    else
	    {
	    	$response['success'] ='0';
	    	$response['message'] ='Something went wrong,Please try again!';
	    }
	    echo json_encode($response);exit();
	}

	/**
	 * function for remove best dish form restaurant
	 * @author Manisha Kanazariya
	 * Created date: 28-09-2018 11:50 PM
	 */
	function removeBestDish($dishId)
	{
		$resId                     =$this->input->post('resId');
		$resData['is_best_dishes'] =0;
	    $updateResDish             =$this->Restaurant_model->updateBestDish($resId,$dishId,$resData);
	    
	    if($updateResDish > 0)
	    {
	    	$response['success'] ='1';
	    	$response['message'] ='';
	    }
	    else
	    {
	    	$response['success'] ='0';
	    	$response['message'] ='Something went wrong,Please try again!';
	    }
	    echo json_encode($response);exit();
	}
}