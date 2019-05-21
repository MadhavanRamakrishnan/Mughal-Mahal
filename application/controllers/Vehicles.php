<?php

	/**
	 * Controller Name 	: Vehicles
	 * Descripation 	: Use to manage all the activity related to Vehicles
	 * @author 			: Vaibhav Mehta
	 * Created date 	: 30-09-2017 01:00PM
	 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicles extends MY_Controller
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
		$this->load->model(array('Login_model','Vehicle_model','Restaurant_model','Driver_model'));
		$this->menu 	= $this->getMenu();
		$this->submenu 	= $this->getSubMenu();
		$this->load->library('form_validation');
		$this->load->library('upload');
	}


	/**
	 * deafult function call for listing drivers
	 * @author Vaibhav Mehta
	 * Created date: 28-09-2017 3:50 PM
	 * update by :Manisha Kanazariya
	 * update date :15/02/2018 1:04 PM
	 */
	function index()
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
		$resId = $this->getRestaurantForRoleBaseAccess();
		$data['vehicles'] 	= $this->Vehicle_model->getAllVehicleDetails('',$resId);
        
		$this->load->view('Elements/header',$data);
		$this->load->view('Vehicles/index');
		$this->load->view('Elements/footer');
	}

	/**
	 * deafult function call when create new driver
	 * @author Rashmi Nayani
	 * update by :Manisha Kanazariya
	 * update date :15/02/2018 1:04 PM
	 * Created date: 10-10-2017 11:45 PM
	 */
	function addVehicle(){
		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();
		$driverRole 		= $this->config->item('driver_role');
		$data['resId'] 		= $this->getRestaurantForRoleBaseAccess();
		$data['restaurants']= $this->Driver_model->getAllRestaurant($data['resId']);
		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']   	= $submenuArray;

		if ($this->input->post('add')=='Save') {
        	
			$this->form_validation->set_rules('brand','Brand Name', 'required');
			$this->form_validation->set_rules('model', 'Model Name', 'required');
			$this->form_validation->set_rules('restaurant_id', 'Restautant', 'required');
			$this->form_validation->set_rules('no_plate', 'Number Plate', 'required');
			$this->form_validation->set_rules('description', 'Description', 'required');
			$this->form_validation->set_rules('purchase_date', 'Purchase Date', 'required');

			if ($this->form_validation->run() == FALSE){
	        	
	        }
	        else{
				$config['upload_path']   		= './assets/uploads/vehicles/'; 
                $config['allowed_types']        = 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
                $config['max_size']             = 5120;
            
               
                $this->upload->initialize($config);
                if (! $this->upload->do_upload('image')){

                    $error = array('error' => $this->upload->display_errors());
                }
                else{
                    $dataupload = array('upload_data' => $this->upload->data());
                    $vehiData['image'] = $dataupload['upload_data']['file_name'];	
                }
	        	if(isset($error) && sizeof($error)>0){
        			$data['image_error']=$error['error'];
	        	}
	        	else{
	        		
		            $vehiData['brand'] 			= trim($this->input->post('brand'));
		            $vehiData['model'] 			= trim($this->input->post('model'));
		            $vehiData['restaurant_id']  = trim($this->input->post('restaurant_id'));
		            $vehiData['number_plate']	= trim($this->input->post('no_plate'));
		            $vehiData['vendor']	        = trim($this->input->post('vendor'));
		            $vehiData['description']	= trim($this->input->post('description'));
		            $vehiData['purchase_date']	= trim(date('Y/m/d', strtotime($this->input->post('purchase_date'))));	
		            $vehiData['created_by'] 	= $data['userdata'][0]->user_id;	
		            $vehiData['created_date']	= date("Y-m-d H:i:s");

		            $result = $this->Vehicle_model->addVehicleDetail($vehiData);
		         
		            if (sizeof($result)>0) {
		            	   $this->session->set_flashdata('success_msg', "Vehicle Details Added successfully!");
		            	   redirect('Vehicles/index');
		            }
		            else{
		            	 $this->session->set_flashdata('error_msg', "Something went wrong while adding Vehicle details");
		            	   redirect('Vehicles/addVehicle');
		            }
				}
	        }
   		}
	
		$this->load->view('Elements/header',$data);
		$this->load->view('Vehicles/add_vehicle');
		$this->load->view('Elements/footer');
	}
	
	/**
	 * function to edit vehicle details 
	 * @author Rashmi Nayani
	 * update by :Manisha Kanazariya
	 * update date :15/02/2018 1:04 PM
	 * Created date: 10-10-2017 1:50 PM
	 */
	function editVehicle($id){
		$resId = $this->getRestaurantForRoleBaseAccess();
		$vehicleDetails =$this->Vehicle_model->getAllVehicleDetails($id,$resId);

		if ($id != null && sizeof($vehicleDetails)>0) {
			$data['userdata']	 = $this->session->userdata('current_user');
		    $data['restaurants']= $this->Driver_model->getAllRestaurant($resId);
			$data['menu']   	 = $this->menu;
			$submenu   			 = $this->submenu;
			$submenuArray 		 = array();

			foreach($submenu as $key=>$value){

				$submenuArray[$value->parent_page_id][] = $value;
			}
			$data['submenu']   	 	= $submenuArray;
			$data['vehicle_data'] 	= $vehicleDetails;
			if ($this->input->post('update')=='Update') {
        	
			$this->form_validation->set_rules('brand','Brand Name', 'required');
			$this->form_validation->set_rules('model', 'Model Name', 'required');
			$this->form_validation->set_rules('restaurant_id', 'Restautant', 'required');
			$this->form_validation->set_rules('no_plate', 'Number Plate', 'required');
			$this->form_validation->set_rules('description', 'Description', 'required');
			$this->form_validation->set_rules('purchase_date', 'Purchase Date', 'required');

			if ($this->form_validation->run() == FALSE){
	        	
	        }
	        else{
	        	if($_FILES['image']['name'] != ""){

					$config['upload_path']   		= './assets/uploads/vehicles/'; 
	                $config['allowed_types']        = 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
	                $config['max_size']             = 5120;
	               
	               
	                $this->upload->initialize($config);
	                if (! $this->upload->do_upload('image')){

	                    $error = array('error' => $this->upload->display_errors());
	                }
	                else{
	                    $dataupload = array('upload_data' => $this->upload->data());
	                    $vehiData['image'] = $dataupload['upload_data']['file_name'];	
	                    if($data['vehicle_data'][0]->image){
								$img = "./assets/uploads/products/".$data['vehicle_data'][0]->image;
									unlink($img);						
					   	}
	                }
	            }
	            else{
	            	$vehiData['image'] = $data['vehicle_data'][0]->image;	
	            }
	        	if(isset($error) && sizeof($error)>0){
        			$data['image_error']=$error['error'];
	        	}
	        	else{
	        		
		            $vehiData['brand'] 			= trim($this->input->post('brand'));
		            $vehiData['model'] 			= trim($this->input->post('model'));
		            $vehiData['restaurant_id']  = trim($this->input->post('restaurant_id'));
		            $vehiData['number_plate']	= trim($this->input->post('no_plate'));
		            $vehiData['vendor']         = trim($this->input->post('vendor'));
		            $vehiData['description']	= trim($this->input->post('description'));
		            $vehiData['purchase_date']	= trim(date('Y/m/d', strtotime($this->input->post('purchase_date'))));	
		            $vehiData['created_by'] 	= $data['userdata'][0]->user_id;	
		            $vehiData['created_date']	= date("Y-m-d H:i:s");

		            $result = $this->Vehicle_model->editVehicleDetail($vehiData,$id);
		         
		            if (sizeof($result)>0) {
		            	   $this->session->set_flashdata('success_msg', "Vehicle Details updated successfully!");
		            	   redirect('Vehicles/index');
		            }
		            else{
		            	 $this->session->set_flashdata('error_msg', "Something went wrong while updating Vehicle details");
		            	   redirect('Vehicles/editVehicle');
		            }
				}
	        }
   		}
			$this->load->view('Elements/header',$data);
			$this->load->view('Vehicles/edit_vehicle');
			$this->load->view('Elements/footer');
			
		}
		else{
			redirect('Vehicles/index');
		}
	}
	
	/**
	 * function to delete vehicle details 
	 * @author Rashmi Nayani
	 * Created date: 10-10-2017 5:25 PM
	 */
	public function deleteVehicleDetail(){
 		$data['userdata']	= $this->session->userdata('current_user');
 		$vehicleId    		= $this->input->post('vehicle_id');

		$vehiData['is_active'] 		= "0";
		$vehiData['updated_by'] 	= $data['userdata'][0]->user_id;
		$vehiData['updated_date'] 	= date("Y-m-d H:i:s");	
		
		$result = $this->Vehicle_model->editVehicleDetail($vehiData,$vehicleId);
		if($result>0){
			$response = array("success"=>"1","message"=>"Vehicle details delete successfully");
		}	
		else{
			$response = array("success"=>"0","message"=>"Something went wrong while delete Vehicle details");
		}
		echo json_encode($response);
		exit;
	}
}