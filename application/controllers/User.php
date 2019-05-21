<?php

	/**
	 * Controller Name 	: Login
	 * Descripation 	: Use to manage user login and logout
	 * @author 			: Vaibhav Mehta
	 * Created date 	: 08 September 2017 2:00PM
	 */

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller
{
	/**
	 * function to invoke necessary component
	 * @author Vaibhav Mehta
	 */
	function __construct(){
		parent::__construct();
		//loading login model
		$this->isLoginUser();
		$this->load->model(array('Login_model','User_model','Restaurant_model'));
		$this->load->library('form_validation');
			$this->menu 	= $this->getMenu();
		$this->submenu 	= $this->getSubMenu();
		$this->load->library('upload');
	}

	function editProfile(){

			$user_id=$this->input->post('user_id');
				
			$userdata	 = $this->session->userdata('current_user');
			$userdataAll = $this->User_model->getUserData($userdata[0]->user_id);
			$data['userdata'] = $userdataAll;
			$managerData = array();
			$data['managData'] = $managerData;
			$data['menu']   	= $this->menu;
			$submenu   			= $this->submenu;
			$submenuArray 		= array();
			foreach($submenu as $key=>$value){

				$submenuArray[$value->parent_page_id][] = $value;
			}
			$data['submenu']   	 	= $submenuArray;
			
			$data['countryList'] 	=$this->Restaurant_model->getCountry();
			if (sizeof($userdata)>0) {
				
				if ($this->input->post('update')=='Update') {
		        	
					$this->form_validation->set_rules('first_name','First Name', 'required');
					$this->form_validation->set_rules('last_name', 'Last Name', 'required');
					$this->form_validation->set_rules('email', 'Email', 'trim|required');
					$this->form_validation->set_rules('contact_no', 'Contact No', 'required|max_length[8]|regex_match[/^[a-z0-9]+$/]|min_length[8]|numeric');
					
					if ($this->form_validation->run() == FALSE){
			      
					}else{
			        	if($_FILES['image']['name'] != ""){


							$config['upload_path']   		= './assets/uploads/users/'; 
			                $config['allowed_types']        = 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
			          
			                $this->upload->initialize($config);
			                if (!$this->upload->do_upload('image')){

			                    $error = array('error' => $this->upload->display_errors());

			                }else{
			                    $dataupload = array('upload_data' => $this->upload->data());
			                    $userData['profile_photo'] = $dataupload['upload_data']['file_name'];
			                    if($userdata[0]->profile_photo){
									$img = "./assets/uploads/users/".$userdata[0]->profile_photo;
										unlink($img);						
						   		}	
			                }
		            	}

			        		
			            $userData['first_name'] 	    = trim($this->input->post('first_name'));
			            $userData['last_name'] 			= trim($this->input->post('last_name'));
			            $userData['email'] 			    = trim($this->input->post('email'));
			            $userData['gender'] 			= trim($this->input->post('gender'));
			            $userData['dob'] 			    = date("Y-m-d",strtotime($this->input->post('dob')));
			            $userData['country']			= trim($this->input->post('country'));
			            $userData['state']				= trim($this->input->post('state'));
			            $userData['city']				= trim($this->input->post('city'));
			            $userData['zipcode']			= trim($this->input->post('zip_code'));
			            $userData['address']			= trim($this->input->post('address'));
			            $userData['contact_no']			= trim($this->input->post('contact_no'));
			            $userData['delivery_contact_no']= trim($this->input->post('delivery_no'));
			            $userData['updated_by'] 		= $data['userdata'][0]->user_id;	
			            $userData['updated_date']		= date("Y-m-d H:i:s");
			           
			            $user_id = $this->User_model->updateUser($userData,$user_id);
							

						if (sizeof($user_id)>0) {
			            	   $this->session->set_flashdata('success_msg', "User Details updated successfully!");
			            	   redirect('user/editProfile');
			            }
			            else{
			            	 $this->session->set_flashdata('error_msg', "Something went wrong while updating user details");
			            	   redirect('user/editProfile');
			            }
						
			        }
					
			    }

				
			}else{
				redirect('user/editProfile');
			}
			$this->load->view('Elements/header',$data);
			$this->load->view('User/editProfile');
			$this->load->view('Elements/footer');
				
		}
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
}
