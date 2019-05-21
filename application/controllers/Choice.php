<?php

	/**
	 * Controller Name 	: Category
	 * Descripation 	: Use to manage all the activity related to cuisines Dishes Category
	 * @author 			: Rashmi Nayani
	 * Created date 	: 06-10-2017 03:15PM
	 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Choice extends MY_Controller
{
	/**
	 * deafult function call when controller class is load
	 * @author Rashmi Nayani
	 * Created date: 26-10-2017 12:40 PM
	 */
	function __construct(){
		parent::__construct();
		//loading login model
		$this->checkLogin();
		$this->load->model(array('Login_model','Choice_model'));
		$this->menu 	= $this->getMenu();
		$this->submenu 	= $this->getSubMenu();
		$this->load->library('form_validation');
		$this->load->library('upload');
		$this->form_validation->run($this);
		$this->isLoginUser();
	}
	/**
	 * deafult function call for listing choices
	 * @author Rashmi Nayani
	 * Created date: 26-10-2017 12:40 PM
	 */
	function choiceCategoryList(){
		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();

		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']   		= $submenuArray;
		$data['categoryList'] 	= $this->Choice_model->getAllChoiceCategory();
		$this->load->view('Elements/header',$data);
		$this->load->view('Choice/category_list');
		$this->load->view('Elements/footer');
	}

	/**
	 * function to add dish category
	 * @author Rashmi Nayani
	 * Created date: 26-10-2017 5:50 PM
	 */
	function addChoiceCategory(){
			$this->form_validation->set_rules('cat_name', 'Category Name', 'required|callback_isCategoryExist['.$_POST["id"].']');
			if ($this->form_validation->run() == FALSE){
	        	$response['success'] ='0';
	        	$response['message'] =validation_errors();
	        }
	        else{
	        	
	            $categoryData['choice_category_name	']	= trim($this->input->post('cat_name'));
	            $categoryData['created_date']			= date("Y-m-d H:i:s");

	            $result = $this->Choice_model->addChoiceCategory($categoryData);

	            if (sizeof($result)>0) {
	            	   $response['success'] ='1';
	            	   $this->session->set_flashdata('success_msg', "Choice category details added successfully!");
	            }
	            else{
	            	$response['success'] ='0';
	        	    $response['message'] = 'Something went wrong while adding choice category details';
	            }
	        }
	  		
	  		echo json_encode($response);exit;
		
	}
	/**
	 * function to edit choice category details 
	 * @author Rashmi Nayani
	 * Created date: 26-10-2017 7:30 PM
	 */
	function editChoiceCategory($id){
				$id=$_POST['id'];
				$response['success']=0;
				$response['message']='';
				$this->form_validation->set_rules('cat_name', 'Category Name', 'required|callback_isCategoryExist['.$_POST["id"].']');
				
				if ($this->form_validation->run() == FALSE){
		        	
		        	$response['message'] = validation_errors();

		        }
		        else{
		        	
		            $categoryData['choice_category_name']	= trim($_POST['cat_name']);
		            $categoryData['updated_date']			= date("Y-m-d H:i:s");

		            $result = $this->Choice_model->editChoiceCategory($categoryData,$id);

		            if (sizeof($result)>0) {
		            	   $response['success']=1;
		            }
		            else{
		            	$response['message']='Something went wrong while updating Category details';
		            	
		            }
		        }
		        
		        echo json_encode($response);exit;

	}

	/**
	 * function to check duplicate Cuisine Catefory Exist
	 * @author Rashmi Nayani
	 * Created date: 26-10-2017 4:45 PM
	 */

	function isCategoryExist($category,$x){
		$data 	= $this->Choice_model->getDuplicateCategory($category,$x);
		if(sizeof($data)>0){
			$this->form_validation->set_message('isCategoryExist', 'This Choice of Category is already exist');
			return false;
		}
		else{
			return true;
		}
	}
	/**
	 * function to delete choice category details 
	 * @author Rashmi Nayani
	 * Created date: 26-10-2017 7:15 PM
	 */
	function deleteChoiceCategory(){
 		$data['userdata']	= $this->session->userdata('current_user');
 		$catId    			= $this->input->post('choice_category_id');

		$CatDetail['is_active'] 	= "0";
		$CatDetail['updated_date'] 	= date("Y-m-d H:i:s");	
		$result = $this->Choice_model->editChoiceCategory($CatDetail,$catId);

		if($result>0){
			//$this->session->set_flashdata('success_msg', "Category details delete successfully");
			$response = array("success"=>"1","message"=>"Category details delete successfully");
		}	
		else{
			$response = array("success"=>"0","message"=>"Something went wrong while delete category details");
		}
		echo json_encode($response);
		exit;
	}
	function deleteChoice(){
 		$data['userdata']	= $this->session->userdata('current_user');
 		$Id    			= $this->input->post('choice_id');
 		
		$CatDetail['is_active'] 	= "0";
		$CatDetail['updated_date'] 	= date("Y-m-d H:i:s");	
		
		$result = $this->Choice_model->editChoice($CatDetail,$Id);
		if($result>0){
			//$this->session->set_flashdata('success_msg', "Category details delete successfully");
			$response = array("success"=>"1","message"=>"Choice details delete successfully");
		}	
		else{
			$response = array("success"=>"0","message"=>"Something went wrong while delete category details");
		}
		echo json_encode($response);
		exit;
	}

	/**
	 * deafult function call for listing Choices
	 * @author Rashmi Nayani
	 * Created date: 27-10-2017 11:25 PM
	 */
	function choicesList(){

		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();

		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']   		= $submenuArray;
		$data['choiceList'] 	= $this->Choice_model->getAllChoices();
		$data['categoryList']  	= $this->Choice_model->getAllChoiceCategory();
		$this->load->view('Elements/header',$data);
		$this->load->view('Choice/choices_list');
		$this->load->view('Elements/footer');
	}

	/**
	 * function to add dish category
	 * @author Rashmi Nayani
	 * Created date: 24-10-2017 5:50 PM
	 */
	function addChoice(){
        	$response['success'] ='0';
        	$response['message'] ='';
	        	
    		$choiceData['fk_choice_category_id'] = $_POST['cat_name'];
    		$choiceData['choice_name']           = $_POST['choice_name'];
    		$choiceData['choice_name_ar']           = $_POST['choice__ar_name'];
    		$choiceData['created_date']			 = date("Y-m-d H:i:s");
    		
        	$result = $this->Choice_model->addChoice($choiceData);
        	

    	  	if (sizeof($result)>0) {
            	  $response['success'] ='1';
            }
            else{
            	  $response['success'] ='2';
            	  $response['message'] ='Something went wrong while adding choice  details';
            	 
            }
			
	        
	        echo json_encode($response);exit;
		
	}
	/**
	 * function to edit choice category details 
	 * @author Rashmi Nayani
	 * Created date: 24-10-2017 7:30 PM
	 */
	function editChoices($id){
            
		    $response['success'] ='0';
        	$response['message'] ='';
        	
	        	
    		$choiceData['choice_name']           = $_POST['choice_name'];
    		$choiceData['choice_name_ar']        = $_POST['choice__ar_name'];
    		$choiceData['fk_choice_category_id'] = $_POST['cat_name'];
    		$choiceData['updated_date']			 = date("Y-m-d H:i:s");
    		
        	$result = $this->Choice_model->editChoice($choiceData,$id);
        	

    	  	if (sizeof($result)>0) {
            	  $response['success'] ='1';
            }
            else{
            	  $response['success'] ='2';
            	  $response['message'] ='Something went wrong while adding choice  details';
            	 
            }
		
	        echo json_encode($response);exit;
	}
	
}