<?php
/**
* Controller Name: Restaurants
* Descripation: Use to manage Restaurants activity
* @author Vaibhav Mehta
* Created date: 28-09-2017 3:50 PM
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends MY_Controller
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
        $this->load->model(array('Login_model','Restaurant_model','Rating_model'));
        $this->checkLogin();
        $this->menu 	= $this->getMenu();
        $this->submenu 	= $this->getSubMenu();
        $this->load->library('form_validation');
        $this->load->library('upload');
        $this->form_validation->run($this);
    }

    /**
    * deafult function call when controller class is load
    * @author Vaibhav Mehta
    * Created date: 28-09-2017 3:50 PM
    */
    function index()
    {
        $resId              = $this->getRestaurantForRoleBaseAccess();
        $restoruntData      = $this->Restaurant_model->getAllRestaurantAvelibility($resId);
        $data['userdata']   = $this->session->userdata('current_user');
        $data['menu']       = $this->menu;
        $submenu            = $this->submenu;
        $submenuArray       = array();

        foreach($submenu as $key=>$value){
            $submenuArray[$value->parent_page_id][] = $value;
        }

        $data['submenu']      = $submenuArray;
        $data['restoruntData']= $restoruntData;
        if ($this->input->post('update')=='Update') {
            $choiceData1 = array();
            $choicedata = $this->input->post();
            
            for($x=0; $x<sizeof($choicedata['restaurant_id']); $x++){
                
                if($choicedata['restaurant_id'][$x]){
                    $choiceData1[$x]['restaurant_id']       = $choicedata['restaurant_id'][$x];
                    $choiceData1[$x]['custom_delivery_time'] = $choicedata['delivery_time'][$x];
                }
                
            }
            $result = $this->Restaurant_model->editRestaurantDetailDelivery($choiceData1);
            if (sizeof($result)>0) {
               $this->session->set_flashdata('success_msg', "Restaurant delivery time updated successfully!");
        	   redirect('Setting/index');
            }
            else{
            	$this->session->set_flashdata('error_msg', "Something went wrong while updating Restaurant delivery time details");
	            redirect('Setting/index/');
            }
        }

    	$this->load->view('Elements/header',$data);
    	$this->load->view('Setting/edit_delivery_time');
    	$this->load->view('Elements/footer');
    }

    /**
    * function to add restaurants details
    * @author Rashmi Nayani
    * Created date: 09-10-2017 4:45 PM
    */
    function RestaurantAvelibility()
    {
        $resId              = $this->getRestaurantForRoleBaseAccess();
        $data['userdata']   = $this->session->userdata('current_user');
        $restoruntData      = $this->Rating_model->getRestaurantAvelibility($resId,$data['userdata'][0]->user_id);
        $data['menu']       = $this->menu;
        $submenu            = $this->submenu;
        $submenuArray       = array();

        foreach($submenu as $key=>$value){

            $submenuArray[$value->parent_page_id][] = $value;
        }

        $data['submenu']       = $submenuArray;
        $data['restoruntData'] = $restoruntData;
        if ($this->input->post('update')=='Update') {

            $choicedata = $this->input->post();
            $x=0;

            foreach ($choicedata['restaurant_id'] as $key => $value) {
                if(isset($choicedata['is_active'][$value]) && $choicedata['is_active'][$value]=='on'){

                    $cData[$x]['is_availability']='1';
                }
                else{

                    $cData[$x]['is_availability']='0';    
                }

                $cData[$x]['restaurant_id']=$value;
                $x++;
            }
            
	  	    $result = $this->Restaurant_model->editRestaurantDetailDelivery($cData);

            if (sizeof($result)>0) {
                $this->session->set_flashdata('success_msg', "Restaurant Avelibility Details updated successfully!");
                redirect('Setting/RestaurantAvelibility');
            }
            else{
            	 $this->session->set_flashdata('error_msg', "Something went wrong while updating Restaurant Avelibility details");
            	   redirect('Setting/RestaurantAvelibility/');
            }
        }
    	$this->load->view('Elements/header',$data);
    	$this->load->view('Setting/edit_restorant_avalibility');
    	$this->load->view('Elements/footer');
    }
}