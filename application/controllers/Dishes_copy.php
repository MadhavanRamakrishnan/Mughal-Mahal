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
		//loading login model
		$this->checkLogin();
		$this->load->model(array('Login_model','Dishes_model'));
		$this->menu 	= $this->getMenu();
		$this->submenu 	= $this->getSubMenu();
		$this->load->library('form_validation');
		$this->load->library('upload');
		$this->load->library('pagination');
		$this->form_validation->run($this);

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

		$config 					= array();
		$config["base_url"] 		= site_url('Dishes/dishList');
		$totalRow 					= $this->Dishes_model->dishesCount();
		$config["total_rows"] 		= $totalRow;
		$config["per_page"] 		= 5;
		$config['use_page_numbers'] = TRUE;
		$config['num_links'] 		= 5;
		$config['cur_tag_open'] 	= '&nbsp;<a class="current">';
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
		$data['dishlist']		= $this->Dishes_model->getAllDishes($config["per_page"],$offset);
		$data['offset'] 		= $offset + 1;		
		$str_links 				= $this->pagination->create_links();	
		$data["links"] 			= explode('&nbsp;',$str_links );

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
		$config['cur_tag_open'] 	= '&nbsp;<a class="current">';
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
		$data['categoryList']= $this->Dishes_model->getCategory();

        if ($this->input->post('add')=='Save') {
			$this->form_validation->set_rules('category_id', 'Category Name', 'required');
			$this->form_validation->set_rules('dish_name', 	 'Dish Name', 'required|callback_isDishExist['.json_encode($this->input->post()).']');
			$this->form_validation->set_rules('description', 'Descripation', 'required');
			$this->form_validation->set_rules('price', 'Dish Price', 'required|numeric');
	
			
			if ($this->form_validation->run() == FALSE){
	        	
	        }
	        else{

				$config['upload_path']   		= './assets/uploads/products/'; 
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
		            $dishData['product_en_name']	= trim($this->input->post('dish_name'));
		            $dishData['en_description']	= trim($this->input->post('description'));
		            $dishData['price']			= trim($this->input->post('price'));
		            $dishData['discount_type']	= trim($this->input->post('discount_type'));
		            $dishData['discount']		= trim($this->input->post('discount'));
		            //$dishData['created_by'] 	= $data['userdata'][0]->user_id;	
		            $dishData['created_date']	= date("Y-m-d H:i:s");

		            $product_id = $this->Dishes_model->addDishDetail($dishData);
		            //echo "<pre>";print_r($product_id);exit;
		            if ($_FILES['other_image']['name'] != "") {

		        		$filesCount = count($_FILES['other_image']['name']);
		            	for($i = 0; $i < $filesCount; $i++){

			                $_FILES['userFile']['name'] 	= $_FILES['other_image']['name'][$i];
			                $_FILES['userFile']['type'] 	= $_FILES['other_image']['type'][$i];
			                $_FILES['userFile']['tmp_name'] = $_FILES['other_image']['tmp_name'][$i];
			                $_FILES['userFile']['error'] 	= $_FILES['other_image']['error'][$i];
			                $_FILES['userFile']['size'] 	= $_FILES['other_image']['size'][$i];

			                $config['upload_path']   		= './assets/uploads/products/'; 
			                $config['allowed_types']        = 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
			                $config['max_size']             = 5120;
			            
			                $this->upload->initialize($config);
			                if($this->upload->do_upload('userFile')){
			                    $fileData = $this->upload->data();
			                    $uploadData[$i]['file_name'] = $fileData['file_name'];
			                    $images['media_name']=$uploadData[$i]['file_name'];
			              		
			                }
			                else{
	                    		$error1 = array('error1' => $this->upload->display_errors());

			                }
			                if(isset($error1) && sizeof($error1)>0){
		        	
			        			$data['image_error1']=$error['error1'];
				        	}
				        	else{
				        		$images['product_id'] 	= $product_id;
				        		$images['created_by'] 	= $data['userdata'][0]->user_id;	
			            		$images['created_date']	= date("Y-m-d H:i:s");
			            		
	           					$result = $this->Dishes_model->addDishMedia($images);

				        	}
		            	}
	        		}

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
		//echo "<pre>";print_r($dishDetails);exit;
		if ($id != null && sizeof($dishDetails)>0) {
			$data['userdata']	= $this->session->userdata('current_user');
			$data['menu']   	= $this->menu;
			$submenu   			= $this->submenu;
			$submenuArray 		= array();

			foreach($submenu as $key=>$value){

				$submenuArray[$value->parent_page_id][] = $value;
			}
			//echo "<pre>";print_r($submenuArray);exit;
			$data['submenu']   		= $submenuArray;
			$data['dish_data']		= $dishDetails;
			$data['categoryList']	= $this->Dishes_model->getCategory();
			$data['imagesList'] 	= $this->Dishes_model->getImages();

			if ($this->input->post('update')=='Update') {
				$this->form_validation->set_rules('category_id', 'Category Name', 'required');
				$this->form_validation->set_rules('dish_name', 	 'Dish Name', 'required|callback_isDishExist['.json_encode($this->input->post()).']');
				$this->form_validation->set_rules('description', 'Descripation', 'required');
				$this->form_validation->set_rules('price', 'Dish Price', 'required');

				if ($this->form_validation->run() == FALSE){
		        	
		        }
		        else{
		        	if($_FILES['image']['name'] != ""){

						$config['upload_path']   		= './assets/uploads/products/'; 
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
								$img = "./assets/uploads/products/".$data['dish_data'][0]->dish_image;
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
			            $dishData['product_en_name']			= trim($this->input->post('dish_name'));
			            $dishData['en_description']	= trim($this->input->post('description'));
			            $dishData['price']			= trim($this->input->post('price'));
			            $dishData['discount_type']	= trim($this->input->post('discount_type'));
			            $dishData['discount']		= trim($this->input->post('discount'));
			            //$dishData['created_by'] 	= $data['userdata'][0]->user_id;	
			            $dishData['created_date']	= date("Y-m-d H:i:s");

			            $result = $this->Dishes_model->editDishDetail($dishData,$id);

			            if ($_FILES['other_image']['name'] != "") {

			        		$filesCount = count($_FILES['other_image']['name']);
			            	for($i = 0; $i < $filesCount; $i++){

				                $_FILES['userFile']['name'] 	= $_FILES['other_image']['name'][$i];
				                $_FILES['userFile']['type'] 	= $_FILES['other_image']['type'][$i];
				                $_FILES['userFile']['tmp_name'] = $_FILES['other_image']['tmp_name'][$i];
				                $_FILES['userFile']['error'] 	= $_FILES['other_image']['error'][$i];
				                $_FILES['userFile']['size'] 	= $_FILES['other_image']['size'][$i];

				                $config['upload_path']   		= './assets/uploads/products/'; 
				                $config['allowed_types']        = 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
				                $config['max_size']             = 5120;
				            
				                $this->upload->initialize($config);
				                if($this->upload->do_upload('userFile')){
				                    $fileData = $this->upload->data();
				                    $uploadData[$i]['file_name'] = $fileData['file_name'];
				                    $images['media_name']=$uploadData[$i]['file_name'];
				              		
				                }
				                else{
		                    		$error1 = array('error1' => $this->upload->display_errors());

				                }
				                if(isset($error1) && sizeof($error1)>0){
			        	
				        			$data['image_error1']=$error['error1'];
					        	}
					        	else{
					        		$images['product_id'] 	= $id;
					        		$images['updated_by'] 	= $data['userdata'][0]->user_id;	
				            		$images['updated_date']	= date("Y-m-d H:i:s");
				            		
		           					$result1 = $this->Dishes_model->addDishMedia($images);

					        	}
			            	}
	        			}

			            if (sizeof($result)>0 || sizeof($result1)>0) {
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
		//$dishDetail['updated_by'] 	= $data['userdata'][0]->user_id;
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
}
