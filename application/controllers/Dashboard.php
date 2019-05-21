<?php

/**
 * Controller Name: Dashboard
 * Descripation: Use to manage dashboard activity
 * @author Vaibhav Mehta
 * Created date: 25 Jan 2017
 * Modified date: 30 Jan 2017
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
	/**
	 * function to invoke necessary component
	 * @author Vaibhav Mehta
	 * Created Date: 25 Jan 2017
	 * Modified Date: 25 Jan 2017
	 */

	function __construct()
	{
		parent::__construct();
		$this->checkLogin();
		$this->isLoginUser();
		$this->load->model(array('Login_model','User_model'));
		$this->menu 		= $this->getMenu();
		$this->submenu 		= $this->getSubMenu();
		$this->userdata		= $this->session->userdata('current_user');
	}

	/**
	 * deafult function call when controller class is load
	 * @author Vaibhav Mehta
	 * Created Date: 25 Jan 2017
	 * Modified By: Rashmi Nayani
	 * Modified Date: 27 Jan 2017
	 */
	function index(){
		$resId = $this->getRestaurantForRoleBaseAccess();

        $data['userdata'] 		= $this->userdata;
		$data['menu']   		= $this->menu;
		$data['submenu']   		= $this->submenu;

		$data['customerCount'] 	= $this->User_model->getCustomer($resId,null,null,"count");
		$data['countCustomers'] = $data['customerCount'][0]->countCustomer;
		$data['orderCount'] 	= $this->User_model->getOrder($resId);
		$data['countOrders'] 	= count($data['orderCount']);
		$data['countDrivers'] 	= $this->User_model->getDriverCount($resId);
		$data['countVehicles'] 	= $this->User_model->getVehicleCount($resId);
		$data['customerOrder'] 	= $this->User_model->getOrder($resId,$limit='5',$orderBy='DESC');
		//echo "<pre>";print_r($data['customerOrder']);exit;
		$data['customers'] 		= $this->User_model->getCustomer($resId,$limit='5',$orderBy='DESC');
		$this->load->view('Elements/header',$data);
		$this->load->view('Dashboard/dashboard');
		$this->load->view('Elements/footer');
	}

	function check()
	{
		echo "INNNN";exit;
	}
	
	/**
	 * [getLatestOrder Get Latest order Details for dashboard]
	 * @author Hardik Ghadshi
	 * @Created Date   2019-03-07T19:25:49+0530
	 * @return  [type] [description]
	 */
	function getLatestOrder(){
		$data = $this->User_model->getOrder($resId,$limit='5',$orderBy='DESC');

		echo json_encode($data);
	}
}