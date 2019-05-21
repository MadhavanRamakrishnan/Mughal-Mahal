<?php

	/**
	 * Controller Name 	: Dishes
	 * Descripation 	: Use to manage all the activity related to cuisines Dishes
	 * @author 			: Rashmi Nayani
	 * Created date 	: 06-10-2017 03:15PM
	 */

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Dishes extends MY_Controller
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
		$this->load->model(array('Login_model','Dishes_model','Choice_model','DishChoice_model'));
		$this->menu 	= $this->getMenu();
		$this->submenu 	= $this->getSubMenu();
		$this->load->library('form_validation');
		$this->load->library('upload');
		$this->load->library('pagination');
		$this->form_validation->run($this);
		$this->isLoginUser();
	}


	/**
	 * deafult function call for listing dishes
	 * @author Rashmi Nayani
	 * Created date: 06-10-2017 4:20 PM
	 */
	function dishList(){
		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();

		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']   	= $submenuArray;

		$data['dishlist']		= $this->Dishes_model->getAllDishes();
		$this->load->view('Elements/header',$data);
		$this->load->view('Dishes/index');
		$this->load->view('Elements/footer');
	}

	public function searchDish(){
		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();

		foreach($submenu as $key=>$value){
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']   	= $submenuArray;
		$search = $this->input->post('search');
		if($search){

			$search=$search;
		}
		else{

			$search=null;
		}
		$config 					= array();
		$config["base_url"] 		= site_url('Dishes/dishList');
		$totalRow 					= $this->Dishes_model->dishesCount($search);
		$config["total_rows"] 		= $totalRow;
		$config["per_page"] 		= 4;
		$config['use_page_numbers'] = TRUE;
		$config['num_links'] 		= 5;
		$config['cur_tag_open'] 	= '&nbsp;<a class="active">';
		$config['cur_tag_close'] 	= '</a>';
		$config['next_link'] 		= 'Next';
		$config['prev_link'] 		= 'Previous';
		$this->pagination->initialize($config);

		if($this->uri->segment(3)){
			$page = ($this->uri->segment(3)) ;
		}
		else{
			$page = 1;
		}
		$offset					= ($page - 1) * $config["per_page"];
		$data['dishlist']		= $this->Dishes_model->getAllDishes($config["per_page"],$offset,$search);
		$data['offset'] 		= $offset + 1;		
		$str_links 				= $this->pagination->create_links();	
		$data["links"] 			= explode('&nbsp;',$str_links );

		$this->load->view('Elements/header',$data);
		$this->load->view('Dishes/index');
		$this->load->view('Elements/footer');
		
	}


	/**
	 * function to add dish category
	 * @author Rashmi Nayani
	 * Created date: 06-10-2017 4:20 PM
	 */
	function addDishDetail(){

		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();

		foreach($submenu as $key=>$value){

			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']   	= $submenuArray;
		$data['categoryList']  = $this->Dishes_model->getCategory();

		if ($this->input->post('add')=='Save') {
			$this->form_validation->set_rules('category_id', 'Category Name', 'required');
			$this->form_validation->set_rules('dish_name', 	 'Dish Name', 'required|callback_isDishExist['.json_encode($this->input->post()).']');
			$this->form_validation->set_rules('ar_dish_name', 'Dish name in arabic', 'required');
			if ($this->form_validation->run() == FALSE){
				
			}
			else{
				$config['upload_path']   		= './assets/images/front-end/dishes'; 
				$config['allowed_types']        = 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
				$config['max_size']             = 5120;
				
				$this->upload->initialize($config);
				if (! $this->upload->do_upload('image')){
					$error = array('error' => $this->upload->display_errors());
				}
				else{
					$dataupload 	   				= array('upload_data' => $this->upload->data());
					$dishData['dish_image'] = $dataupload['upload_data']['file_name'];	
				}
				if(isset($error) && sizeof($error)>0){
					
					$data['image_error']=$error['error'];
				}
				else{
					$dishData['category_id']	= trim($this->input->post('category_id'));
					$dishData['product_en_name']= trim($this->input->post('dish_name'));
					$dishData['product_ar_name']= trim($this->input->post('ar_dish_name'));
					$dishData['en_description']	= trim($this->input->post('description'));
					$dishData['ar_description']	= trim($this->input->post('ar_description'));
					$dishData['created_date']	= date("Y-m-d H:i:s");

					$product_id = $this->Dishes_model->addDishDetail($dishData);
					if (sizeof($product_id)>0) {
						$this->session->set_flashdata('success_msg', "Dish Details Added successfully!");
						redirect('Dishes/dishList');
					}
					else{
						$this->session->set_flashdata('error_msg', "Something went wrong while adding Dish details");
						redirect('Dishes/addDishDetail');
					}
				}
			}	
		}
	 	$this->load->view('Elements/header',$data);
		$this->load->view('Dishes/add_dish');
		$this->load->view('Elements/footer');
	}
	
	/**
	 * function to edit category details 
	 * @author Rashmi Nayani
	 * Created date: 07-10-2017 1:50 PM
	 */
	function editDishDetail($id){

		$dishDetails	= $this->Dishes_model->getDishDetails($id);
		if ($id != null && sizeof($dishDetails)>0) {
			$data['userdata']	= $this->session->userdata('current_user');
			$data['menu']   	= $this->menu;
			$submenu   			= $this->submenu;
			$submenuArray 		= array();

			foreach($submenu as $key=>$value){

				$submenuArray[$value->parent_page_id][] = $value;
			}
			$data['submenu']   		    = $submenuArray;
			$data['dish_data']		    = $dishDetails;
			$data['categoryList']	    = $this->Dishes_model->getCategory();
			if ($this->input->post('update')=='Update') {
				
				$this->form_validation->set_rules('category_id', 'Category Name', 'required');
				$this->form_validation->set_rules('dish_name', 	 'Dish Name', 'required|callback_isDishExist['.json_encode($this->input->post()).']');
				$this->form_validation->set_rules('product_ar_name', 'Dish name in arabic', 'required');
				
				if ($this->form_validation->run() == FALSE){
					
				}
				else{
					if($_FILES['image']['name'] != ""){

						$config['upload_path']   		= './assets/images/front-end/dishes'; 
						$config['allowed_types']        = 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
						$config['max_size']             = 5120;
						
						$this->upload->initialize($config);
						if (! $this->upload->do_upload('image')){
							$error = array('error' => $this->upload->display_errors());
						}
						else{
							$dataupload 	   			= array('upload_data' => $this->upload->data());
							$dishData['dish_image'] = $dataupload['upload_data']['file_name'];
							if($data['dish_data'][0]->dish_image){
								$img = "./assets/images/front-end/dishes".$data['dish_data'][0]->dish_image;
								unlink($img);						
							}	
						}
					}
					else{

						$dishData['dish_image'] = $data['dish_data'][0]->dish_image;
					}
					if(isset($error) && sizeof($error)>0){

						$data['image_error']=$error['error'];
					}
					else{
						$dishData['category_id']	= trim($this->input->post('category_id'));
						$dishData['product_en_name']= trim($this->input->post('dish_name'));
						$dishData['product_ar_name']= trim($this->input->post('product_ar_name'));
						$dishData['en_description']	= trim($this->input->post('description'));
						$dishData['ar_description']	= trim($this->input->post('ar_description'));
						$dishData['created_date']	= date("Y-m-d H:i:s");

						$result = $this->Dishes_model->editDishDetail($dishData,$id);

						
						if (sizeof($result)>0 || sizeof($result)>0) {
							$this->session->set_flashdata('success_msg', "Dish Details updated successfully!");
							redirect('Dishes/dishList');
						}
						else{
							$this->session->set_flashdata('error_msg', "Something went wrong while updating Dish details");
							redirect('Dishes/editDishDetail');
						}
					}
				}
			}
			$this->load->view('Elements/header',$data);
			$this->load->view('Dishes/edit_dish');
			$this->load->view('Elements/footer');
		}
		else{
			redirect('Dishes/dishList');
		}
	}
	/**
	 * function to check duplicate Dish  Exist
	 * @author Rashmi Nayani
	 * Created date: 07-10-2017 1:00 PM
	 */

	function isDishExist($dish,$x){

		$catId 		= $this->input->post('category_id');
		$productId 	= $this->input->post('product_id');

		$data 	= $this->Dishes_model->getDuplicateDish($dish,$catId,$productId);

		if(sizeof($data)>0){
			$this->form_validation->set_message('isDishExist', 'This Dish is already exist');
			return false;
		}
		else{
			return true;
		}
	}
	/**
	 * function to delete dish details 
	 * @author Rashmi Nayani
	 * Created date: 06-10-2017 1:00 PM
	 */
	function deleteDishDetail(){
		$data['userdata']	= $this->session->userdata('current_user');
		$productId    		= $this->input->post('product_id');

		$dishDetail['is_active'] 	= "0";
		$dishDetail['updated_date'] = date("Y-m-d H:i:s");	
		
		$result = $this->Dishes_model->editDishDetail($dishDetail,$productId);
		if($result>0){
			$response = array("success"=>"1","message"=>"Dish details delete successfully","product_id" => $productId );
		}	
		else{
			$response = array("success"=>"0","message"=>"Something went wrong while delete DISH details");
		}
		echo json_encode($response);
		exit;
	}


	/**
	 * deafult function call for listing dish choices
	 * @author Manisha Kanazariya
	 * Created date: 17-02-2018 4:20 PM
	 */
	function dishChoiceList(){
		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;	
		$submenuArray 		= array();

		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']   	= $submenuArray;
		$data['categoryList'] 	= $this->Choice_model->getAllChoiceCategory();
		$data['choiceList'] 	= $this->Choice_model->getAllChoices();
		
		//echo "<pre>";print_r($data['choiceList']);exit;
		$config 					= array();
		$config["base_url"] 		= site_url('Dishes/dishChoiceList');
		$totalRow 					= count($this->DishChoice_model->getAllDishChoices());
		$config["total_rows"] 		= $totalRow;
		$config["per_page"] 		= 3;
		$config['use_page_numbers'] = TRUE;
		$config['num_links'] 		= 5;
		$config['cur_tag_open'] 	= '&nbsp;<a class="active">';
		$config['cur_tag_close'] 	= '</a>';
		$config['next_link'] 		= 'Next';
		$config['prev_link'] 		= 'Previous';
		$this->pagination->initialize($config);

		if($this->uri->segment(3)){
			$page = ($this->uri->segment(3)) ;
		}
		else{
			$page = 1;
		}

		$offset					= ($page - 1) * $config["per_page"];
		$data['dishChoiceList']		= $this->DishChoice_model->getAllDishChoices($config["per_page"],$offset);
		$data['offset'] 		= $offset + 1;		
		$str_links 				= $this->pagination->create_links();	
		$data["links"] 			= explode('&nbsp;',$str_links );
		
       // echo "<pre>";print_r($data['dishChoiceList']);exit;
		$this->load->view('Elements/header',$data);
		$this->load->view('DishChoices/dishChoiceList');
		$this->load->view('Elements/footer');
	}

	/**
	 * deafult function call for get choice of given category
	 * @author Manisha Kanazariya
	 * Created date: 17-02-2018 4:20 PM
	 */
      function getChoiceOfCategory($id){
      	$choiceList 	= $this->Choice_model->getAllChoices('',$id);
      	$choice='';
      	if(count($choiceList)>0){
      		foreach ($choiceList as $key => $value) {
	         	$choice .='<li class="active-result" data-option-array-index="'.$value->choice_id.'" style="">'.$value->choice_name.'</li>';
	         	//$choice .="<option value='".$value->choice_id."' style>".$value->choice_name."</option>";
	         }
      	}
	    $response['message'] =$choice;
         echo json_encode($response);exit;
      }
	/**
	 * deafult function call for Add dish choices
	 * @author Manisha Kanazariya
	 * Created date: 17-02-2018 4:20 PM
	 */
	function addDishChoice()
	{
		//echo "<pre>";print_r($_POST);
		$response['success'] ='0';
		$response['message'] ='';
		$response['catName'] ='';
		$response['chioce'] ='';
		$this->form_validation->set_rules('catName' ,'Category Name' ,'required');

		if ($this->form_validation->run() == FALSE){
			
			$response['catName']=validation_errors();
        }
		$this->form_validation->set_rules('chioce[]' ,'choice','required',array('required' => 'You have to select atleast one %s.'));

        if($this->form_validation->run() == FALSE){

        	$input =validation_errors();
        	$index = strripos($input,"<p>");
		    $in =str_replace(substr($input,0,$index),'',$input);
        	$response['chioce']=$in;
        }
		else{

			$data=array();
			foreach ($_POST['chioce'] as $key => $value) {
				$data[$key]['fk_dish_id']   =$_POST['catName'];
				$data[$key]['fk_choice_id'] =$value;
				$data[$key]['is_active']    =1;
				$data[$key]['created_date'] =date('Y-m-d h:i:s');
			}
			$addDishChoice =$this->DishChoice_model->addDishChoices($data);
			
			if($addDishChoice >0){
				$response['success'] =1;

			}
			else{
				$response['message'] ='Try again ,there is something went wrong.';
			}
		}
		echo json_encode($response);exit;
	} 

	function DishChoices($categoryId){
		$choiceList 	= $this->Choice_model->getAllChoices('',$categoryId);

			$response['success'] =0;
			$response['message'] ='';

			if(count($choiceList)>0){

				$choices=array();

				foreach ($choiceList as $key => $value) {
					$choices[$key]['choice_id']   =$value->choice_id;
					$choices[$key]['choice_name'] =$value->choice_name;
				}
				$response['success'] =1;
				$response['message'] =$choices;
			}
		echo json_encode($response);
		exit;
	}

	/**
	 * [dishStatusUpdate Update dish status of active or inactive]
	 * @author Hardik Ghadshi
	 * @Created Date   2019-04-04T13:38:52+0530
	 * @return  [type] [description]
	 */
	function dishStatusUpdate(){
		$id = $this->input->post('product_id');
		$data['is_active'] = $this->input->post('is_active');

		$result = $this->Dishes_model->editDishDetail($data, $id);

		if($result > 0){
			$response = array("success" => "1","message" => "Status Updated successfully");
		}else{
			$response = array("success" => "0","message" => "Something went wrong");
		}
		echo json_encode($response);
	}
}
