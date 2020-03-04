<?php

	/**
	 * Controller Name 	: Category
	 * Descripation 	: Use to manage all the activity related to cuisines Dishes Category
	 * @author 			: Rashmi Nayani
	 * Created date 	: 06-10-2017 03:15PM
	 */

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Category extends MY_Controller
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
		$this->load->model(array('Login_model','Category_model'));
		$this->menu 	= $this->getMenu();
		$this->submenu 	= $this->getSubMenu();
		$this->load->library('form_validation');
		$this->load->library('upload');
		$this->form_validation->run($this);
		$rid =$this->getRestaurantForRoleBaseAccess();

	}


	/**
	 * deafult function call for listing dishes
	 * @author Vaibhav Mehta
	 * Created date: 06-10-2017 4:20 PM
	 */
	function dishCategory(){
		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();

		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']   		= $submenuArray;
		$data['categoryList'] 	= $this->Category_model->getAllCategory();
		$this->load->view('Elements/header',$data);
		$this->load->view('Category/index');
		$this->load->view('Elements/footer');
	}

	/**
	 * function to add dish category
	 * @author Rashmi Nayani
	 * Created date: 06-10-2017 4:20 PM
	 */
	function addCategory(){

		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();

		foreach($submenu as $key=>$value){

			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']   	= $submenuArray;
		
		if ($this->input->post('add')=='Save') {
			$this->form_validation->set_rules('category_name', 'Cuisine Name', 'required|callback_isCategoryExist['.json_encode($this->input->post('category_name')).']');
			
			
			if ($this->form_validation->run() == FALSE){
				
			}
			else{
				if($_FILES['image']['name'] != ""){

					$config['upload_path']   		= './assets/uploads/category/'; 
					$config['allowed_types']        = 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
					$config['max_size']             = 5120;
					$config["encrypt_name"] 		= true;
					
					$this->upload->initialize($config);
					if (! $this->upload->do_upload('image')){
						$error = array('error' => $this->upload->display_errors());
					}
					else{
						$dataupload 	   = array('upload_data' => $this->upload->data());
						$categoryData['image'] = $dataupload['upload_data']['file_name'];	
					}
				}
				else{

					$categoryData['image'] = "";
				}
				if(isset($error) && sizeof($error)>0){
					$data['image_error']=$error['error'];
				}
				else{
					$categoryData['category_name']	= addslashes(trim($this->input->post('category_name')));
					$categoryData['created_date']	= date("Y-m-d H:i:s");

					$result = $this->Category_model->addCategory($categoryData);

					if (sizeof($result)>0) {
						$this->session->set_flashdata('success_msg', "Category Details Added successfully!");
						redirect('Category/dishCategory');
					}
					else{
						$this->session->set_flashdata('error_msg', "Something went wrong while adding Category details");
						redirect('Category/addCategory');
					}
				}
			}
		}

		$this->load->view('Elements/header',$data);
		$this->load->view('Category/add_category');
		$this->load->view('Elements/footer');
	}
	/**
	 * function to edit category details 
	 * @author Rashmi Nayani
	 * Created date: 06-10-2017 5:20 PM
	 */
	function editCategory($id){
		$categoryDetails	= $this->Category_model->getAllCategory($id);

		if ($id != null && sizeof($categoryDetails)>0) {
			$data['userdata']	= $this->session->userdata('current_user');
			$data['menu']   	= $this->menu;
			$submenu   			= $this->submenu;
			$submenuArray 		= array();

			foreach($submenu as $key=>$value){

				$submenuArray[$value->parent_page_id][] = $value;
			}
			$data['submenu']   	  = $submenuArray;
			$data['category_data']= $categoryDetails;

			if ($this->input->post('update')=='Update') {

				$this->form_validation->set_rules('category_name', 'Cuisine Name', 'required|callback_isCategoryExist['.json_encode($this->input->post()).']');
				
				if ($this->form_validation->run() == FALSE){
					
				}
				else{
					if($_FILES['image']['name'] != ""){

						$config['upload_path']   		= './assets/uploads/category/'; 
						$config['allowed_types']        = 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
						$config['max_size']             = 5120;
						$config["encrypt_name"] 		= true;
						
						$this->upload->initialize($config);
						if (! $this->upload->do_upload('image')){
							$error = array('error' => $this->upload->display_errors());
							
						}
						else{
							$dataupload 	       = array('upload_data' => $this->upload->data());
							$categoryData['image'] = $dataupload['upload_data']['file_name'];	
							if($data['category_data'][0]->image){

								$img = "./assets/uploads/category/".$data['category_data'][0]->image;
								unlink($img);						
							}	
						}	
					}
					else{

						$categoryData['image'] = $data['category_data'][0]->image;
					}
					if(isset($error) && sizeof($error)>0){
						$data['image_error']=$error['error'];
					}
					else{
						$categoryData['category_name']	= addslashes(trim($this->input->post('category_name')));
						$categoryData['created_date']	= date("Y-m-d H:i:s");
						
						$result = $this->Category_model->editCategory($categoryData,$id);

						if (sizeof($result)>0) {
							$this->session->set_flashdata('success_msg', "Category Details updated successfully!");
							redirect('Category/dishCategory');
						}
						else{
							$this->session->set_flashdata('error_msg', "Something went wrong while updating Category details");
							redirect('Category/editCategory');
						}
					}
				}
			}
			$this->load->view('Elements/header',$data);
			$this->load->view('Category/edit_category');
			$this->load->view('Elements/footer');
			
		}
		else{
			redirect('Category/dishesCategory');
		}
	}
	/**
	 * function to check duplicate Cuisine Catefory Exist
	 * @author Rashmi Nayani
	 * Created date: 06-10-2017 4:45 PM
	 */

	function isCategoryExist($category,$x){

		$catId 	= $this->input->post('category_id');
		
		$data 	= $this->Category_model->getDuplicateCategory($category,$catId);
		if(sizeof($data)>0){
			$this->form_validation->set_message('isCategoryExist', 'This cuisine Category is already exist');
			return false;
		}
		else{
			return true;
		}
	}
	/**
	 * function to delete category details 
	 * @author Rashmi Nayani
	 * Created date: 06-10-2017 1:00 PM
	 */
	public function deleteCategory(){
		$data['userdata']	= $this->session->userdata('current_user');
		$catId    			= $this->input->post('category_id');

		$CatDetail['is_active'] 	= "0";
		$CatDetail['updated_date'] 	= date("Y-m-d H:i:s");	
		
		$result = $this->Category_model->editCategory($CatDetail,$catId);
		if($result>0){
			$response = array("success"=>"1","message"=>"Category details deleted successfully");
		}	
		else{
			$response = array("success"=>"0","message"=>"Something went wrong while delete category details");
		}
		echo json_encode($response);
		exit;
	}
}