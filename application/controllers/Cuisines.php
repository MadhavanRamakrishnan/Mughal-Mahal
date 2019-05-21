<?php

	/**
	 * Controller Name 	: Cuisines
	 * Descripation 	: Use to manage all the activity related to cuisines
	 * @author 			: Vaibhav Mehta
	 * Created date 	: 30-09-2017 01:00PM
	 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Cuisines extends MY_Controller
{
	/**
	 * deafult function call when controller class is load
	 * @author Vaibhav Mehta
	 * Created date: 28-09-2017 3:50 PM
	 */
	function __construct(){
		parent::__construct();
		//loading login model
		$this->checkLogin();
		$this->load->model(array('Login_model','Cuisine_model'));
		$this->menu 	= $this->getMenu();
		$this->submenu 	= $this->getSubMenu();
		$this->load->library('form_validation');
		$this->load->library('upload');
		$this->form_validation->run($this);

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

		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']   	= $submenuArray;

		$data['cuisines'] 	= $this->Cuisine_model->getAllCuisines();
		//$data['countries'] 	= $this->Driver_model->getAllCountries();
		//echo "<pre>"; print_r($data);exit;
        
		$this->load->view('Elements/header',$data);
		$this->load->view('Cuisines/index');
		$this->load->view('Elements/footer');
	}

	/**
	 * function to add cuisines 
	 * @author Rashmi Nayani
	 * Created date: 05-10-2017 6:00 PM
	 */
	function addCuisine(){

		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();

		foreach($submenu as $key=>$value){

			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']   	= $submenuArray;

        if ($this->input->post('add')=='Save') {
			$this->form_validation->set_rules('cuisine_name', 'Cuisine Name', 'required|callback_isCuisineExist');
			$this->form_validation->set_rules('description', 'Descripation', 'required');
			
			if ($this->form_validation->run() == FALSE){
	        	
	        }
	        else{
	        	if($_FILES['image']['name'] != ""){

					$config['upload_path']   		= './assets/uploads/cuisines/'; 
	                $config['allowed_types']        = 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
	                $config['max_size']             = 5120;
	               
	                $this->upload->initialize($config);
	                if (! $this->upload->do_upload('image')){
                        	$error = array('error' => $this->upload->display_errors());
	                }
	                else{
                        $dataupload 	   = array('upload_data' => $this->upload->data());
	                    $cuisineData['image'] = $dataupload['upload_data']['file_name'];	
	                }
	            }
	            else{

	            	$cuisineData['image'] = "";
	            }
	        	if(isset($error) && sizeof($error)>0){
        				$data['image_error']=$error['error'];
	        	}
	        	else{
		            $cuisineData['cuisine_name']	= trim($this->input->post('cuisine_name'));
		            $cuisineData['description']		= trim($this->input->post('description'));
		            $cuisineData['created_by'] 		= $data['userdata'][0]->user_id;	
		            $cuisineData['created_date']	= date("Y-m-d H:i:s");

		            $result = $this->Cuisine_model->addCuisine($cuisineData);

		            if (sizeof($result)>0) {
		            	   $this->session->set_flashdata('success_msg', "Cuisine Details Added successfully!");
		            	   redirect('cuisines/index');
		            }
		            else{
		            	 $this->session->set_flashdata('error_msg', "Something went wrong while adding cuisine details");
		            	   redirect('cuisines/addCuisine');
		            }
		         
				}
	        }
	    }

		$this->load->view('Elements/header',$data);
		$this->load->view('Cuisines/add_cuisine');
		$this->load->view('Elements/footer');
	}
	/**
	 * function to edit cuisines 
	 * @author Rashmi Nayani
	 * Created date: 05-10-2017 7:30 PM
	 */
	function editCuisine($id){

		$cuisineDetails	= $this->Cuisine_model->getAllCuisines($id);

		if ($id != null && sizeof($cuisineDetails)>0) {
			$data['userdata']	= $this->session->userdata('current_user');
			$data['menu']   	= $this->menu;
			$submenu   			= $this->submenu;
			$submenuArray 		= array();

			foreach($submenu as $key=>$value){

				$submenuArray[$value->parent_page_id][] = $value;
			}
			$data['submenu']   	  = $submenuArray;
			$data['cuisine_data'] = $cuisineDetails;

			if ($this->input->post('update')=='Update') {

				$this->form_validation->set_rules('cuisine_name', 'Cuisine Name', 'required|callback_isCuisineExist['.$this->input->post('cuisine_id').']');
				$this->form_validation->set_rules('description', 'Descripation', 'required');
				
				if ($this->form_validation->run() == FALSE){
		        	
		        }
		        else{

		        	if($_FILES['image']['name'] != ""){

						$config['upload_path']   		= './assets/uploads/cuisines/'; 
		                $config['allowed_types']        = 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
		                $config['max_size']             = 5120;
		               
		                $this->upload->initialize($config);

		                if (! $this->upload->do_upload('image')){
                        	$error = array('error' => $this->upload->display_errors());
		                }
		                else{
	                        $dataupload 	   = array('upload_data' => $this->upload->data());
		                    $cuisineData['image'] = $dataupload['upload_data']['file_name'];
		                     if($data['cuisine_data'][0]->image){
								$img = "./assets/uploads/cuisines/".$data['cuisine_data'][0]->image;
									unlink($img);						
					   		 }	
		                }
		            }
		            else{
		            	$cuisineData['image'] = $data['cuisine_data'][0]->image;
		            }
		        	if(isset($error) && sizeof($error)>0){
        				$data['image_error']=$error['error'];
        				
        				
		        	}
		        	else{
			            $cuisineData['cuisine_name']	= trim($this->input->post('cuisine_name'));
			            $cuisineData['description']		= trim($this->input->post('description'));
			            $cuisineData['updated_by'] 		= $data['userdata'][0]->user_id;	
			            $cuisineData['updated_date']	= date("Y-m-d H:i:s");

			            $result = $this->Cuisine_model->editCuisine($cuisineData,$id);

			            if (sizeof($result)>0) {
			            	   $this->session->set_flashdata('success_msg', "cuisines Details updated successfully!");
			            	   redirect('cuisines/index');
			            }
			            else{
			            	 $this->session->set_flashdata('error_msg', "Something went wrong while updating cuisine details");
			            	   redirect('cuisines/editCuisine');
			            }
			         
					}
		        }
	    	}
	    	$this->load->view('Elements/header',$data);
			$this->load->view('Cuisines/edit_cuisine');
			$this->load->view('Elements/footer');
		}
		else{
			redirect('Cuisines');
		}
	}
	/**
	 * function to check duplicate cuisine exist
	 * @author Rashmi Nayani
	 * Created date: 06-10-2017 1:00 PM
	 */

	function isCuisineExist($cuisine,$cuisineId=null){
		$data 	= $this->Cuisine_model->getDuplicateCuisine($cuisine,$cuisineId);
		if(sizeof($data)>0){
			$this->form_validation->set_message('isCuisineExist', 'This cuisine name is already exist');
			return false;
		}
		else{
			return true;
		}
	}
	/**
	 * function to delete cuisines  details
	 * @author Rashmi Nayani
	 * Created date: 06-10-2017 1:00 PM
	 */
	public function deleteCuisine(){
 		$data['userdata']	= $this->session->userdata('current_user');
 		$cuisineId    		= $this->input->post('cuisine_id');

		$CuisineDetail['is_active'] 	= "0";
		$CuisineDetail['updated_by'] 	= $data['userdata'][0]->user_id;
		$CuisineDetail['updated_date'] 	= date("Y-m-d H:i:s");	
		
		$result = $this->Cuisine_model->editCuisine($CuisineDetail,$cuisineId);
		if($result>0){
			$response = array("success"=>"1","message"=>"Cuisine details delete successfully","cuisine_id" => $cuisineId );
		}	
		else{
			$response = array("success"=>"0","message"=>"Something went wrong while delete cuisine details");
		}
		echo json_encode($response);
		exit;
	}
}