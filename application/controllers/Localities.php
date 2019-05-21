<?php

	/**
	 * Controller Name 	: Category
	 * Descripation 	: Use to manage all the activity related to cuisines Dishes Category
	 * @author 			: Rashmi Nayani
	 * Created date 	: 06-10-2017 03:15PM
	 */

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Localities extends MY_Controller
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
		$this->load->model(array('Login_model','Category_model','Restaurant_model'));
		$this->menu 	= $this->getMenu();
		$this->submenu 	= $this->getSubMenu();
		$this->load->library('form_validation');
		$this->load->library('upload');
		$this->form_validation->run($this);

	}

	/**
	 * List all localities
	 * @author Manisha Kanazariya 
	 * Created date: 1-06-2018 3:50 PM
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
		$data['submenu']   		= $submenuArray;
		$data['localitylist'] 	=$this->Restaurant_model->getlocality();
		$this->load->view('Elements/header',$data);
		$this->load->view('Locality/listLocalities');
		$this->load->view('Elements/footer');
		
	}

	/**
	 * Add  localities
	 * @author Manisha Kanazariya 
	 * Created date: 1-06-2018 3:50 PM
	 */
	function addLocality(){

		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();

		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']   		= $submenuArray;
		$j =5;
		$deliveryTimes =array();
		for ($i=0; $j <= 100 ; $i++) { 
			$deliveryTimes[$j] =$j;
			$j=$j+5;
		}
		$data['deliveryTimes'] =$deliveryTimes;

		if($this->input->post("add") == "Save"){
			$this->form_validation->set_rules('name', 'name', 'required');
			$this->form_validation->set_rules('name_ar', 'arabic name', 'required');
			$this->form_validation->set_rules('delivered_time', 'delivery time', 'required');
			$this->form_validation->set_rules('delivery_charge', 'delivery charge', 'required|numeric');
			$this->form_validation->set_rules('min_order_amount', 'minimum amount for order', 'required|numeric');
			$this->form_validation->set_rules('lat', 'Set location of locality', 'required');
			
			
			if ($this->form_validation->run() == FALSE){
				
			}else{
				$localityData[0]['name']              =$this->input->post('name');
				$localityData[0]['name_ar']           =$this->input->post('name_ar');
				$localityData[0]['delivered_time']    =$this->input->post('delivered_time');
				$localityData[0]['delivery_charge']   =$this->input->post('delivery_charge');
				$localityData[0]['min_order_amount']  =$this->input->post('min_order_amount');
				$localityData[0]['lat']               =$this->input->post('lat');
				$localityData[0]['lon']               =$this->input->post('lon');
				$localityData[0]['created_date']      =date('Y-m-d h:i:s');

				$addLocality =$this->Restaurant_model->addLocality($localityData);
				if (sizeof($addLocality)>0) {
					$this->session->set_flashdata('success_message', "Locality details added successfully!");
					redirect('Localities');
				}
				else{
					$this->session->set_flashdata('error_message', "Something went wrong while adding locality details");
				}
				
			}

		}

		$this->load->view('Elements/header',$data);
		$this->load->view('Locality/addLocality');
		$this->load->view('Elements/footer');
	}
	/**
	 * Edit localities
	 * @author Manisha Kanazariya 
	 * Created date: 1-06-2018 3:50 PM
	 */

	function editLocality($id){

		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();

		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']   		= $submenuArray;
		$j =5;
		$deliveryTimes =array();
		for ($i=0; $j <= 100 ; $i++) { 
			$deliveryTimes[$j] =$j;
			$j=$j+5;
		}
		$data['deliveryTimes'] =$deliveryTimes;
		$data['locality'] 	=$this->Restaurant_model->getlocality($id);
		
		if($this->input->post("Edit") == "Save"){
			$this->form_validation->set_rules('name', 'name', 'required');
			$this->form_validation->set_rules('name_ar', 'arabic name', 'required');
			$this->form_validation->set_rules('delivered_time', 'delivery time', 'required');
			$this->form_validation->set_rules('delivery_charge', 'delivery charge', 'required|numeric');
			$this->form_validation->set_rules('min_order_amount', 'minimum amount for order', 'required|numeric');
			$this->form_validation->set_rules('lat', 'select addreess im map', 'required');
			
			
			if ($this->form_validation->run() == FALSE){
				
			}else{
				$localityData['name']              =$this->input->post('name');
				$localityData['name_ar']           =$this->input->post('name_ar');
				$localityData['delivered_time']    =$this->input->post('delivered_time');
				$localityData['delivery_charge']   =$this->input->post('delivery_charge');
				$localityData['min_order_amount']  =$this->input->post('min_order_amount');
				$localityData['lat']               =$this->input->post('lat');
				$localityData['lon']               =$this->input->post('lon');

				$localityData['created_date']      =date('Y-m-d h:i:s');

				$editLocality =$this->Restaurant_model->updateLocality($localityData,$id);

				if (sizeof($editLocality)>0) {
					$this->session->set_flashdata('success_message', "Locality details updated successfully!");
					redirect('Localities');
				}
				else{
					$this->session->set_flashdata('error_message', "Something went wrong while updating locality details");
				}
				
			}

		}

		$this->load->view('Elements/header',$data);
		$this->load->view('Locality/editLocality');
		$this->load->view('Elements/footer');
	}

	/**
	 * Delete localities
	 * @author Manisha Kanazariya 
	 * Created date: 1-06-2018 3:50 PM
	 */
	function deleteLocality(){
		
		$id=$this->input->post('id');
		$deleteLocality =$this->Restaurant_model->deleteLocality($id);
		if($deleteLocality>0){
			$response['success'] ="1";
			$this->session->set_flashdata('success_message', "Locality deleted successfully!");
		}else{
			$response['success'] ="0";
			$this->session->set_flashdata('error_message', "Something went wrong while deleting Locality,Please try again");
		}
		
		exit;
	}
	
}