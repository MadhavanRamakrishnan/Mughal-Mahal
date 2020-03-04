<?php

	/**
	 * Controller Name 	: Customers
	 * Descripation 	: Use to manage all the activity related to customers
	 * @author 			: Vaibhav Mehta
	 * Created date 	: 23-11-2017 05:40PM
	 */

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Customers extends MY_Controller
	{
	/**
	 * deafult function call when controller class is load
	 * @author Vaibhav Mehta
	 * Created date: 11-10-2017 5:40 PM
	 */
	function __construct(){
		parent::__construct();
		$this->isLoginUser();
		//loading login model
		$this->checkLogin();
		$this->load->model(array('Login_model','Order_model','Customer_model','Driver_model','Restaurant_model'));
		$this->menu 	= $this->getMenu();
		$this->submenu 	= $this->getSubMenu();
		$this->load->library('form_validation');
		$this->load->library('pagination');
		$this->load->library('upload');
		$this->load->library('encrypt');
		$this->userdata		= $this->session->userdata('current_user');
		$this->isLoginUser();
	}


	/**
	 * deafult function call for listing of customers
	 * @author Vaibhav Mehta
	 * Created date: 23-11-2017 5:50 PM
	 */
	function index()
	{
		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$searchText = ($this->input->cookie('filter', TRUE))?$this->input->cookie('filter', TRUE):"";
		
		$submenu   			= $this->submenu;
		$submenuArray 		= array();

		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']   			= $submenuArray;

		$resId = $this->getRestaurantForRoleBaseAccess();

		$totalRow 					= $this->Customer_model->getAllCustomersCount($resId,$searchText);
		
		$config["base_url"] 		= site_url('Customers/index');
		$config["total_rows"] 		= $totalRow[0]->customer_count;
		$config["per_page"] 		= 10;
		$config['use_page_numbers'] = TRUE;
		$config['num_links'] 		= 9;
		$config['cur_tag_open']		= '&nbsp;<a class="active">';
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

		$offset 		= ($page - 1) * $config["per_page"];
		$rid 			= $this->config->item('customer_role');
		$customers 		= $this->Customer_model->getAllCustomers($resId,$rid,$config["per_page"], $offset,$searchText,$search=null);
		if(is_array($customers) && count($customers)>0)
		{
			foreach ($customers as $key => $value)
			{
				$a = $value->profile_photo;
				$b = 'assets/uploads/users/customers/'.$a;
				$c = './assets/uploads/users/customers/'.$a;
				if(file_exists($c) && $a )
				{
					$customers[$key]->profile_photo = base_url().$b;
				}
				else
				{
					$customers[$key]->profile_photo = base_url().'assets/uploads/users/no_image.png';
				}
			}
		}

		$data['customers']	    = $customers;		
		$data['offset']         = $offset + 1;	
		$str_links              = $this->pagination->create_links();
		$data["links"]          = explode('&nbsp;',$str_links );
		$data['localitylist'] 	= $this->Restaurant_model->getlocality();
		$data['text'] 			= $searchText;

		$this->load->view('Elements/header',$data);
		$this->load->view('Customers/index');
		$this->load->view('Elements/footer');
		
	}
	/**
	 * [filterDataGet description]
	 * @author Rajnee Patel
	 * @Created Date   2019-11-13T11:02:39+0530
	 * @return  [type] [description]
	 */
	public function filterDataGet()
	{
		$data['userdata']	= $this->session->userdata('current_user');
		$searchText = ($this->input->post('value'))?$this->input->post('value'):"";

		$resId = $this->getRestaurantForRoleBaseAccess();

		$totalRow 					= $this->Customer_model->getAllCustomersCount($resId,$searchText);
		
		$config["base_url"] 		= site_url('Customers/index');
		$config["total_rows"] 		= $totalRow[0]->customer_count;
		$config["per_page"] 		= 10;
		$config['use_page_numbers'] = TRUE;
		$config['num_links'] 		= 9;
		$config['cur_tag_open']		= '&nbsp;<a class="active">';
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

		$offset 		= ($page - 1) * $config["per_page"];
		$rid 			= $this->config->item('customer_role');
		$customers 		= $this->Customer_model->getAllCustomers($resId,$rid,$config["per_page"], $offset,$searchText,$search=null);

		$data['customers']	    = $customers;
		$data['totalRow']		= $totalRow;		
		$data['offset']         = $offset + 1;	
		$str_links              = $this->pagination->create_links();
		$data["links"]          = explode('&nbsp;',$str_links );
		$data['localitylist'] 	= $this->Restaurant_model->getlocality();
		echo json_encode($data);exit;
	}
   	/**
   	 * [indexFilter description]
   	 * @author Rajnee Patel
   	 * @Created Date   2019-11-14T11:00:26+0530
   	 * @return  [type] [description]
   	 */
   	/*function indexFilter()
   	{
   		$data['userdata']	= $this->session->userdata('current_user');
   		$searchText = ($this->input->cookie('filter', TRUE))?$this->input->cookie('filter', TRUE):"";
   		$data['menu']   	= $this->menu;

   		$submenu   			= $this->submenu;
   		$submenuArray 		= array();

   		foreach($submenu as $key=>$value)
   		{
   			$submenuArray[$value->parent_page_id][] = $value;
   		}
   		$data['submenu']   			= $submenuArray;

   		$resId = $this->getRestaurantForRoleBaseAccess();

   		$totalRow 					= $this->Customer_model->getAllCustomersCount($resId,$searchText);
		//echo $totalRow; exit;
   		$config["base_url"] 		= site_url('Customers/indexFilter');
   		$config["total_rows"] 		= $totalRow[0]->customer_count;
   		$config["per_page"] 		= 10;
   		$config['use_page_numbers'] = TRUE;
   		$config['num_links'] 		= 9;
   		$config['cur_tag_open']		= '&nbsp;<a class="active">';
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

   		$offset 		= ($page - 1) * $config["per_page"];
   		$rid 			= $this->config->item('customer_role');
   		$customers 		= $this->Customer_model->getAllCustomers($resId,$rid,$config["per_page"], $offset,$searchText,$search=null);
   		if(is_array($customers) && count($customers)>0)
   		{
   			foreach ($customers as $key => $value)
   			{
   				$a = $value->profile_photo;
   				$b = 'assets/uploads/users/customers/'.$a;
   				$c = './assets/uploads/users/customers/'.$a;
   				if(file_exists($c) && $a )
   				{
   					$customers[$key]->profile_photo = base_url().$b;
   				}
   				else
   				{
   					$customers[$key]->profile_photo = base_url().'assets/uploads/users/no_image.png';
   				}
   			}
   		}

   		$data['customers']	    = $customers;		
   		$data['offset']         = $offset + 1;	
   		$str_links              = $this->pagination->create_links();
   		$data["links"]          = explode('&nbsp;',$str_links );
   		$data['localitylist'] 	= $this->Restaurant_model->getlocality();
   		$data['text'] 			= $searchText;

   		$this->load->view('Elements/header',$data);
   		$this->load->view('Customers/indexFilter');
   		$this->load->view('Elements/footer');

   	}*/

	/**
	 * function to add customer details 
	 * @author Manisha Kanazariya 
	 * Created date: 13-11-2018 012:30 PM
	 */
	public function addCustomers()
	{	
		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();

		foreach($submenu as $key=>$value){
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']   	 	= $submenuArray;

		if ($this->input->post('add')=='Save')
		{
			$this->form_validation->set_rules('fname','First Name', 'required');
			$this->form_validation->set_rules('lname', 'Last Name', 'required|max_length[155]');
			$this->form_validation->set_rules('contact_no', 'Contact No', 'required|max_length[8]|regex_match[/^[a-z0-9]+$/]|min_length[8]|callback_isCustContactExist');
			$this->form_validation->set_rules('email','Email', 'callback_isEmailExist');
			$this->form_validation->set_rules('dob','Date of Birth', 'required');
			if ($this->form_validation->run() == FALSE)
			{

			}
			else
			{
				$customerData['first_name'] 		= trim($this->input->post('fname'));
				$customerData['last_name'] 			= trim($this->input->post('lname'));
				$customerData['contact_no']			= trim($this->input->post('contact_no'));
				$customerData['dob']                = trim(date('Y-m-d', strtotime($this->input->post('dob'))));
				$customerData['email'] 		        = trim($this->input->post('email'));
				$customerData['role_id'] 		    = $this->config->item('customer_role');
				$customerData['created_by'] 		= $data['userdata'][0]->user_id;	
				$customerData['created_date']		= date("Y-m-d H:i:s");

				$result = $this->Customer_model->insertData('tbl_users',$customerData);

				if (sizeof($result)>0) 
				{
					$this->session->set_flashdata('success_msg', "Customer Details updated successfully!");
					redirect('Customers/index');
				}
				else
				{
					$this->session->set_flashdata('error_msg', "Something went wrong while updating customer details.");
					redirect('Customers/editDrivers');
				}
			}
		}

		$this->load->view('Elements/header',$data);
		$this->load->view('Customers/add_customer');
		$this->load->view('Elements/footer');
	}

	/**
	 * function to edit customer details 
	 * @author Vaibhav Mehta
	 * Created date: 01-12-2017 04:30 PM
	 */
	function editCustomers($id)
	{
		$customerRole 		= $this->config->item('customer_role');
		$customerDetails 	= $this->Customer_model->getAllCustomerDetails($customerRole,$id);


		if ($id != null && sizeof($customerDetails)>0) {
			$data['userdata']	= $this->session->userdata('current_user');
			$data['menu']   	= $this->menu;
			$submenu   			= $this->submenu;
			$submenuArray 		= array();

			foreach($submenu as $key=>$value){
				$submenuArray[$value->parent_page_id][] = $value;
			}
			$data['submenu']   	 	= $submenuArray;
			$data['driv_data'] 		= $customerDetails;
			$data['resList'] 		= $this->Driver_model->getAllRestaurant();
			$customerData 			= array();
			if ($this->input->post('update')=='Update')
			{
				$this->form_validation->set_rules('fname','First Name', 'required');
				$this->form_validation->set_rules('lname', 'Last Name', 'required|max_length[155]');
				$this->form_validation->set_rules('contact_no', 'Contact No', 'required|max_length[8]|regex_match[/^[a-z0-9]+$/]|min_length[8]|callback_isCustContactExist');
				$this->form_validation->set_rules('dob','Date of Birth', 'required');
				if ($this->form_validation->run() == FALSE)
				{

				}
				else
				{
					$customerData['first_name'] 		= trim($this->input->post('fname'));
					$customerData['last_name'] 			= trim($this->input->post('lname'));
					$customerData['contact_no']			= trim($this->input->post('contact_no'));
					$customerData['dob']                = date('Y-m-d', strtotime($this->input->post('dob')));
					$customerData['updated_by'] 		= $data['userdata'][0]->user_id;	
					$customerData['updated_date']		= date("Y-m-d H:i:s");
					$result = $this->Customer_model->editCustomerDetail($customerData,$id);

					if (sizeof($result)>0) 
					{
						$this->session->set_flashdata('success_msg', "Customer Details updated successfully!");
						redirect('Customers/index');
					}
					else
					{
						$this->session->set_flashdata('error_msg', "Something went wrong while updating customer details.");
						redirect('Customers/editDrivers');
					}
				}
			}

			$this->load->view('Elements/header',$data);
			$this->load->view('Customers/edit_customer');
			$this->load->view('Elements/footer');
			
		}
		else{
			redirect('Customers/index');
		}
	}
	
	/**
	 * function to delete customer details 
	 * @author Vaibhav Mehta
	 * Created date: 01-12-2017 04:30 PM
	 */
	public function deleteCustomerDetails()
	{
		$data['userdata']	= $this->session->userdata('current_user');
		$uId    			= $this->input->post('user_id');

		$driverData['is_active'] 			= "0";
		$driverData['is_deleted'] 			= "1";
		$driverData['updated_by'] 			= $data['userdata'][0]->user_id;
		$driverData['updated_date'] 		= date("Y-m-d H:i:s");
		$driverData['deleted_date'] 		= date("Y-m-d H:i:s");
		$driverData['deleted_by']			= $data['userdata'][0]->user_id;
		$driverData['deleted_ip_address']	= trim($this->input->ip_address());
		
		$result = $this->Customer_model->editCustomerDetail($driverData,$uId);
		if($result>0)
		{
			$response = array("success"=>"1","message"=>"Customer details delete successfully");
		}	
		else
		{
			$response = array("success"=>"0","message"=>"Something went wrong while delete customer details");
		}
		echo json_encode($response);
		exit;
	}

	/**
	 * function to get detailed information about the customer
	 * @author Vaibhav Mehta
	 * Created date: 01-12-2017 07:30 PM
	 */
	public function getCustomerDetails($id)
	{
		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();
		$resId = $this->getRestaurantForRoleBaseAccess();
		$data['customerDetails']=$this->Customer_model->customerBasicDetails($id);
		$data['customerAddress']=$this->Customer_model->customerAddress($id);
		$orderDetails           =$this->Customer_model->orderDetails($id,$resId);
		unset($data['customerAllAddress'][0]);
		unset($data['customerAllAddress'][1]);
		$order=array();
		$dishes =array();
		$discount=0;
		$order_id ="";
		if(count($orderDetails)>0){
			

			foreach ($orderDetails as $key => $value) {

				if($value->order_id != $order_id){
					$discount =$value->discount_amount;
					$order_id =$value->order_id;
				}else{
					$discount =$value->discount_amount+$discount;
				}

				$order[$value->order_id]['sequence_no'] =$value->sequence_no;
				$order[$value->order_id]['order_id']    =$value->order_id;
				$order[$value->order_id]['discount']    =$discount;
				$order[$value->order_id]['total_price'] =number_format((float)$value->total_price, 3, '.', '');
				$order[$value->order_id]['delivery_charges'] =number_format((float)$value->delivery_charges, 3, '.', '');
				$orderDishDetails                       =$this->Customer_model->orderDishDetails($id,$value->order_id);

				foreach ($orderDishDetails as $key => $value1) {
					$Odrdishes[$value->order_id][$value1->product_id]['dishName'] =$value1->dish_name;
					$Odrdishes[$value->order_id][$value1->product_id]['order_id'] =$value->order_id;
					$Odrdishes[$value->order_id][$value1->product_id]['product_id']=$value1->product_id;
					$Odrdishes[$value->order_id][$value1->product_id]['quantity'] 	                      =$value1->quantity;
					$Odrdishes[$value->order_id][$value1->product_id]['amount'] 	                      =$value1->amount;


					$Odrdishes[$value->order_id][$value1->product_id]['dishCategory']                     =$value1->choice_category_name;
					$Odrdishes[$value->order_id][$value1->product_id]['price']                            =  number_format((float)($value1->dish_price), 2, '.', '');
					$Odrdishes[$value->order_id][$value1->product_id]['choice_name']                       =$value1->choice_name;
					$Odrdishes[$value->order_id][$value1->product_id]['choice_id']                         =$value1->choice_id;
					$Odrdishes[$value->order_id][$value1->product_id]['choices']                           =$value1->ches_id;
					$Odrdishes[$value->order_id][$value1->product_id]['choices_price']                     =$value1->choice_price;

				}
			}
			if(isset($Odrdishes) &&  count($Odrdishes)>0){
				$dishId='';
				foreach ($Odrdishes as $key => $value1) {
					foreach ($value1 as $k1 => $v1) {
						if($v1['choice_id'] !=""){
							$ch            =explode(',', $v1['choices']);
							$chPrice       =explode(',', $v1['choices_price']);
							$chPrice_index =array_search($v1['choice_id'],$ch);
							if($v1['choice_id'] !=""){
								$getChoiceCategory =$this->Customer_model->getChoiceCategory($v1['choice_id']);
								foreach ($getChoiceCategory as $cck => $ccv) {
									$Odrdishes[$key][$k1]['choice_cat'][$ccv->choice_id] =$ccv->choice_category_name;
								}
								
							}

							if($dishId == $v1['product_id'] && $ordId == $v1['order_id']){
								$Odrdishes[$key][$k1]['choice'][$v1['choice_id']]           = $v1['choice_name'];
								$Odrdishes[$key][$k1]['choice_price'][$v1['choice_id']]     = $chPrice[$chPrice_index];
							}else{
								$Odrdishes[$key][$k1]['choice'][$v1['choice_id']]           =$v1['choice_name'];
								$Odrdishes[$key][$k1]['choice_price'][$v1['choice_id']]     = $chPrice[$chPrice_index];
							}
							$ordId =$v1['order_id'];
							$dishId=$v1['product_id'];
						}

					}
				}
				$data['dishes'] =$Odrdishes;
			}
		}
		
		$data['orders'] =$order;

		$this->load->view('Elements/header',$data);
		$this->load->view('Customers/customer_detail',$data);
		$this->load->view('Elements/footer');
	}

	/**
	 * function to add Dilivery Address the customer
	 * @author Manisha kanazariya 
	 * Created date: 06-2-2018 06:30 PM
	 */
	public function addDiliverAddress(){

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

		$addDiliverAddress =$this->Customer_model->addDiliverAddress($delivery_address);

		if($addDiliverAddress > 0){
			$response['success'] =1;
		}else{
			$response['success'] =0;
		}

		echo json_encode($response);exit;

	}

	 /**
	 * function to check duplicate email Exist
	 * @author Vaibhav Mehta
	 * Created date: 10-10-2017 1:30 PM
	 */

	 public function isEmailExist($email)
	 {
	 	$custoemrRole 	= $this->config->item('customer_role');
	 	$data 			= $this->Customer_model->getDuplicateEmail($custoemrRole,$email);
	 	if(sizeof($data)>0)
	 	{
	 		$this->form_validation->set_message('isEmailExist', 'This Email is already exist');
	 		return false;
	 	}
	 	else
	 	{
	 		return true;
	 	}
	 }
	/**
	 * function to check duplicate email Exist
	 * @author Vaibhav Mehta
	 * Created date: 10-10-2017 1:30 PM
	 */

	public function isCustContactExist($contact)
	{
		$user_id            =($this->input->post('cust_id'))?$this->input->post('cust_id'):"";
		$isCustContactExist = $this->Customer_model->isCustContactExist($contact,$user_id);
		if($contact =="")
		{
			$this->form_validation->set_message('isCustContactExist', 'The contact number is required');
			return false;
		}else if(sizeof($isCustContactExist)>0)
		{
			$this->form_validation->set_message('isCustContactExist', 'The contact number is already exist');
			return false;
		}
		else
		{
			return true;
		}
	}

	/**
	 * [getCustomerData Get Customer Data]
	 * @author Hardik Ghadshi
	 * @Created Date   2019-11-05T13:23:47+0530
	 * @return  [type] [description]
	 */
	public function getCustomerData(){

		$phoneNumber = $this->input->post('phone_no');

		$customerData = $this->Customer_model->isCustContactExist($phoneNumber,'');

		if(sizeof($customerData) > 0){
			$data['customer_data'] = $customerData[0];
			$data['addressDetail']  = $this->Webservice_customer_model->getDeliveryAddress($data['customer_data']->user_id);
			$response = array("success" => "1", "data" => $data);
		}else{
			$response = array("success" => "0", "data" => "");
		}
		echo json_encode($response);
	}

	/**
	 * [createCustomer Create customer while creating order and customer is not registered with system]
	 * @author Hardik Ghadshi
	 * @Created Date   2019-11-06T11:16:16+0530
	 * @return  [type] [description]
	 */
	public function createCustomer(){

		$data['customer_type'] = $this->input->post('customer_type');
		$data['first_name'] = $this->input->post('first_name');
		$data['last_name'] 	= $this->input->post('last_name');
		$data['email'] 		= $this->input->post('email');
		$data['role_id'] 	= "5";
		$data['country_code'] = "+965";
		$data['contact_no'] 	= $this->input->post('contact_no');

		$result = $this->Customer_model->insertData('tbl_users',$data);

		if($result){
			$response = array("success" => "1", "message" => "Account created", "data" => $result);
		}else{
			$response = array("success" => "0", "message" => "Something went wrong");
		}

		echo json_encode($response);
	}

	public function updateCustomer($id)
	{
		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$data['localitylist'] 	=$this->Restaurant_model->getlocality();
		$submenuArray 		= array();
		$resId = $this->getRestaurantForRoleBaseAccess();
		$data['customerDetails']=$this->Customer_model->customerBasicDetails($id);
		$data['customerAddress']=$this->Customer_model->customerAddress($id);
		$orderDetails           =$this->Customer_model->orderDetails($id,$resId);
		unset($data['customerAllAddress'][0]);
		unset($data['customerAllAddress'][1]);
		$order=array();
		$dishes =array();
		$discount=0;
		$order_id ="";  
		if(count($orderDetails)>0){
			

			foreach ($orderDetails as $key => $value) {

				if($value->order_id != $order_id){
					$discount =$value->discount_amount;
					$order_id =$value->order_id;
				}else{
					$discount =$value->discount_amount+$discount;
				}

				$order[$value->order_id]['order_id']    =$value->order_id;
				$order[$value->order_id]['discount']    =$discount;
				$order[$value->order_id]['total_price'] =number_format((float)$value->total_price, 3, '.', '');
				$order[$value->order_id]['delivery_charges'] =number_format((float)$value->delivery_charges, 3, '.', '');
				$orderDishDetails                       =$this->Customer_model->orderDishDetails($id,$value->order_id);

				foreach ($orderDishDetails as $key => $value1) {
					$Odrdishes[$value->order_id][$value1->product_id]['dishName'] =$value1->dish_name;
					$Odrdishes[$value->order_id][$value1->product_id]['order_id'] =$value->order_id;
					$Odrdishes[$value->order_id][$value1->product_id]['product_id']=$value1->product_id;
					$Odrdishes[$value->order_id][$value1->product_id]['quantity'] 	                      =$value1->quantity;
					$Odrdishes[$value->order_id][$value1->product_id]['amount'] 	                      =$value1->amount;


					$Odrdishes[$value->order_id][$value1->product_id]['dishCategory']                     =$value1->choice_category_name;
					$Odrdishes[$value->order_id][$value1->product_id]['price']                            =  number_format((float)($value1->dish_price), 2, '.', '');
					$Odrdishes[$value->order_id][$value1->product_id]['choice_name']                       =$value1->choice_name;
					$Odrdishes[$value->order_id][$value1->product_id]['choice_id']                         =$value1->choice_id;
					$Odrdishes[$value->order_id][$value1->product_id]['choices']                           =$value1->ches_id;
					$Odrdishes[$value->order_id][$value1->product_id]['choices_price']                     =$value1->choice_price;

				}
			}
			if(isset($Odrdishes) &&  count($Odrdishes)>0){
				$dishId='';
				foreach ($Odrdishes as $key => $value1) {
					foreach ($value1 as $k1 => $v1) {
						if($v1['choice_id'] !=""){
							$ch            =explode(',', $v1['choices']);
							$chPrice       =explode(',', $v1['choices_price']);
							$chPrice_index =array_search($v1['choice_id'],$ch);
							if($v1['choice_id'] !=""){
								$getChoiceCategory =$this->Customer_model->getChoiceCategory($v1['choice_id']);
								foreach ($getChoiceCategory as $cck => $ccv) {
									$Odrdishes[$key][$k1]['choice_cat'][$ccv->choice_id] =$ccv->choice_category_name;
								}
								
							}

							if($dishId == $v1['product_id'] && $ordId == $v1['order_id']){
								$Odrdishes[$key][$k1]['choice'][$v1['choice_id']]           = $v1['choice_name'];
								$Odrdishes[$key][$k1]['choice_price'][$v1['choice_id']]     = $chPrice[$chPrice_index];
							}else{
								$Odrdishes[$key][$k1]['choice'][$v1['choice_id']]           =$v1['choice_name'];
								$Odrdishes[$key][$k1]['choice_price'][$v1['choice_id']]     = $chPrice[$chPrice_index];
							}
							$ordId =$v1['order_id'];
							$dishId=$v1['product_id'];
						}

					}
				}
				$data['dishes'] =$Odrdishes;
			}
		}
		
		$data['orders'] =$order;

		$this->load->view('Elements/header',$data);
		$this->load->view('Customers/update_customer');
		$this->load->view('Elements/footer');
	}

	function updateCustomerData()
	{
		$data['userdata']	= $this->session->userdata('current_user');
		$pass = trim($this->input->post('pass'));
		$customerData['first_name'] 		= trim($this->input->post('first_name'));
		$customerData['last_name'] 			= trim($this->input->post('last_name'));
		$customerData['contact_no']			= trim($this->input->post('contact_no'));
		$customerData['email']				= trim($this->input->post('email'));
		$customerData['password']			= md5($pass);
		$customerData['dob']                = date('Y-m-d', strtotime($this->input->post('dob')));
		$customerData['updated_by'] 		= $data['userdata'][0]->user_id;	
		$customerData['updated_date']		= date("Y-m-d H:i:s");
		$customerData['updated_ip_address'] = trim($this->input->ip_address());

		$result = $this->Customer_model->editCustomerDetail($customerData,$this->input->post('id'));

		if (sizeof($result)>0) 
		{
			$response = array("success" => "1", "message" => "Customer data update successfully", "data" => $result);
		}else{
			$response = array("success" => "0", "message" => "Something went wrong");
		}
		echo json_encode($response);
	}

}
