<?php

	/**
	 * Controller Name 	: Reports
	 * Descripation 	: Use to manage all the activity related to Reports
	 * @author 			:Manisha Kanazariya
	 * Created date 	: 05-05-2018 03:15PM
	 */

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Reports extends MY_Controller
	{
	/**
	 * deafult function call when controller class is load
	 * @author 			:Manisha Kanazariya
	 * Created date 	: 05-05-2018 03:15PM
	 */
	function __construct(){
		parent::__construct();
		//loading login model
		$this->checkLogin();
		$this->load->model(array('Login_model','Reports_model','Restaurant_model'));
		$this->load->library('pagination');
		$this->menu 	= $this->getMenu();
		$this->submenu 	= $this->getSubMenu();
		$this->userdata	= $this->session->userdata('current_user');

	}


	/**
	 * deafult function call for listing dishes
	 * @author 			:Manisha Kanazariya
	 * Created date 	: 05-05-2018 03:15PM
	 */
	function index(){
		$resId = $this->getRestaurantForRoleBaseAccess();
		if($resId !=""){
			redirect('Reports/sales_report');
		}else{
			redirect('Reports/reports_list');
		}

	}

	function reports_list(){

		$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();

		$resId               = $this->getRestaurantForRoleBaseAccess();
		$data['resId']   	 = $resId;

		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']   	 = $submenuArray;
		$data['restaurants'] = $this->Restaurant_model->getAllRestaurantDetails();
		$data['type']        =$this->config->item('report_type');

		$endDate = date('Y-m-d H:i:s');
		
		//pagination
		$totalRow 					= $this->Reports_model->getOrderDataCount(1,'','','');
   
		$config["base_url"] 		= site_url('Reports/getReportData');
		$config["total_rows"] 		= $totalRow;
		$config["per_page"] 		= 10;
		$config['use_page_numbers'] = TRUE;
		$config['num_links'] 		= 5;
		$config['cur_tag_open']		= '&nbsp;<a class="active">';
		$config['cur_tag_close']	= '</a>';
		$config['next_link'] 		= 'Next';
		$config['prev_link'] 		= 'Previous';
		
		$this->pagination->initialize($config);

		if($this->uri->segment(3)){
			$page = ($this->uri->segment(3)) ;
		}
		else{
			$page = 1;
		}

		$offset=($page - 1) * $config["per_page"];
		$orderData 			= $this->Reports_model->getOrderData(1,$config["per_page"],$offset,'',$endDate);

		$data['orderData']	= $orderData;
		$data['offset'] 	= $offset + 1;	
		$str_links 			= $this->pagination->create_links();
		$data["links"] 		= explode('&nbsp;',$str_links );
		$this->load->view('Elements/header',$data);
		$this->load->view('Reports/report',$data);
		$this->load->view('Elements/footer');
		
	}

	/**
	 * deafult function call for listing Orders data for report 
	 * @author 			:Manisha Kanazariya
	 * Created date 	: 05-05-2018 03:15PM
	 */
	function getReportData($option =""){
		$resId               = $this->getRestaurantForRoleBaseAccess();

	 	if(is_numeric($option) || $option ==""){
	 		$type      =($_GET['type'])?$_GET['type']:"";
	 		$payment   =($_GET['payment_type'])?$_GET['payment_type']:"";
	 		$startDate =($_GET['startDate'])?$_GET['startDate']:"";
	 		$endDate   =($_GET['endDate'])?$_GET['endDate']:"";
	 		$restaurant=($resId != '')?$resId:$_GET['restaurant'];

	 	}else{
	 		$export    =json_decode($_COOKIE['export']);
	 		$type      =($export->type)?$export->type:"";
	 		$payment   =($export->payment_type)?$export->payment_type:"";
		 	$startDate =$export->startDate;
		 	$endDate   =$export->endDate;
		 	$restaurant=($resId != '')?$resId:$export->restaurant;
	 	}
	 	
		$type_val  =$this->config->item('report_type')[$type];
	 	$startDate =($startDate !="")?date('Y-m-d H:i:s',strtotime($startDate.' 00:00:00')):"";
	 	$endDate   =($endDate != "")?date('Y-m-d H:i:s',strtotime($endDate.' 23:59:59')):"";
	 	//pagination
	 // echo $startDate;
	 // echo $endDate;
		$totalRow 					= $this->Reports_model->getOrderDataCount($type,'','',$startDate,$endDate,$restaurant,$payment);

		$config["base_url"] 		= site_url('Reports/getReportData');
		$config["total_rows"] 		= $totalRow;
		$config["per_page"] 		= 10;
		$config['use_page_numbers'] = TRUE;
		$config['num_links'] 		= 5;
		$config['cur_tag_open']		= '&nbsp;<a class="active">';
		$config['cur_tag_close']	= '</a>';
		$config['next_link'] 		= 'Next';
		$config['prev_link'] 		= 'Previous';

		if($type == 9){
			$config["per_page"] 		= 30;
		}
		
		$this->pagination->initialize($config);

		if($this->uri->segment(3)){
			$page = ($this->uri->segment(3)) ;
		}
		else{
			$page = 1;
		}

		$offset=($page - 1) * $config["per_page"];
		if(is_numeric($option) || $option ==""){

			$orderData 		= $this->Reports_model->getOrderData($type,$config["per_page"],$offset,$startDate,$endDate,$restaurant,$payment);
		}else{
			$orderData 		= $this->Reports_model->getOrderData($type,"","",$startDate,$endDate,$restaurant,$payment);
		}
	
		$data['offset'] 	= $offset + 1;	
		$str_links 			= $this->pagination->create_links();
		$response["links"] 		= explode('&nbsp;',$str_links );
	 	if(count($orderData)>0){
	 			$TotalSales  =0;
	 			foreach ($orderData as $key => $value) {

	 				if($type ==1){
		 				$orderData[$key]->TotalSales   =number_format($value->TotalSales,4, '.', '');
		 			}
	 				else if($type == 3){
		 				$orderData[$key]->OrderID   =$this->getOrerId($value->OrderID);
		 				$orderData[$key]->OrderTime =date('d M Y   h:i:s a',strtotime($value->OrderTime));
		 			}
		 			else if($type == 7){
		 				$orderData[$key]->Sr        =++$offset;
		 				$orderData[$key]->OrderID   =$this->getOrerId($value->OrderID);
		 				$orderData[$key]->OrderTime =date('d M Y  h:i:s a',strtotime($value->OrderTime));
		 				$orderData[$key]->Payment   =$this->config->item('payment_type')[$value->Payment];
		 				$orderData[$key]->Mobile    =($value->Mobile !='')?" (+965) ".$value->Mobile:'';
		 				$orderData[$key]->Status    =($value->Status==7)?"Successful":"Disputed";;
		 			}

		 		}
	 		$response['data']   = $orderData;
	 		$response['success'] ='1';
	 		$response['type_val'] =$type_val;
 	 	}else{
 	 		$response['success'] ='0';
 	 		$response['type_val'] =$type_val;
	 	}
	 	
	 	if(is_numeric($option) || $option ==""){
	 		echo json_encode($response);exit;
	 	}else{
	 		$this->exportReport($type_val,$orderData);
	 		delete_cookie('export'); 
	 		unlink($path.$fileName) or die("Unable to open file!");
	 		
	 	}
	 	
	}


	 /**
	 * deafult function call for sales report
	 * @author 			:Manisha Kanazariya
	 * Created date 	: 25-05-2018 03:15PM
	 */
	function sales_report(){
	 	$data['userdata']	= $this->session->userdata('current_user');
		$data['menu']   	= $this->menu;
		$submenu   			= $this->submenu;
		$submenuArray 		= array();
		$resId               = $this->getRestaurantForRoleBaseAccess();
		foreach($submenu as $key=>$value)
		{
			$submenuArray[$value->parent_page_id][] = $value;
		}
		$data['submenu']   	 = $submenuArray;
		$data['resId']   	 = $resId;
		$data['restaurants'] = $this->Restaurant_model->getAllRestaurantDetails($resId);
		

		//pagination
		$totalRow 					= $this->Reports_model->getOrderDataCount(7,'','','','',$resId);
		$config["base_url"] 		= site_url('Reports/getReportData');
		$config["total_rows"] 		= $totalRow;
		$config["per_page"] 		= 10;
		$config['use_page_numbers'] = TRUE;
		$config['num_links'] 		= 5;
		$config['cur_tag_open']		= '&nbsp;<a class="active">';
		$config['cur_tag_close']	= '</a>';
		$config['next_link'] 		= 'Next';
		$config['prev_link'] 		= 'Previous';
		
		$this->pagination->initialize($config);

		if($this->uri->segment(3)){
			$page = ($this->uri->segment(3)) ;
		}
		else{
			$page = 1;
		}

		$offset=($page - 1) * $config["per_page"];
		$orderData 			= $this->Reports_model->getOrderData(7,$config["per_page"],$offset,'','',$resId);
		
		$data['offset'] 	= $offset + 1;	
		$str_links 			= $this->pagination->create_links();
		$data["links"]   	= explode('&nbsp;',$str_links );

		if(count($orderData)>0){
			foreach ($orderData as $key => $value) {
				$orderData[$key]->Sr  =$key+1;
				$orderData[$key]->OrderID   =$this->getOrerId($value->OrderID);
				$orderData[$key]->Amount    =number_format($value->Amount,3, '.', '')." KD";
				$orderData[$key]->OrderTime =date('d M Y  h:i:s a',strtotime($value->OrderTime));
				$orderData[$key]->Payment   =$this->config->item('payment_type')[$value->Payment];
				$orderData[$key]->Status    =($value->Status==7)?"Successful":"Disputed";
			}
		}
		$data['orderData']   =$orderData;
		$data['type']        =$this->config->item('payment_type');
		$this->load->view('Elements/header',$data);
		$this->load->view('Reports/sales_report',$data);
		$this->load->view('Elements/footer');
	 }

	 /**
	 * deafult function call for export data of report 
	 * @author 			:Manisha Kanazariya
	 * Created date 	: 05-05-2018 03:15PM
	 */
	 function exportReport($type_val,$data){
	 		// echo "<pre>";print_r($data);die;
		    $path           =$this->config->item('root_path').'assets/Export/Report/';
		    $fileName       = $type_val.'-data-'.time().'.csv';
			$f = fopen($path.$fileName, "w");
			if(count($data)>0){
				$firstKey = key($data);
				foreach ($data[$firstKey] as $key => $value) {
					$column[]=$key;
				}
				fputcsv($f,$column);
				
			}else{
				fputcsv($f,array(""));
			}
			foreach ($data as $val) {
				$input =array();
				foreach ($val as  $value) {
					$input[] =$value;
				}
			    fputcsv($f,$input);
			}
			// download file
	        header("Content-Type: text/csv");
	        redirect('assets/Export/Report/'.$fileName);
	        exit;
	
	}

		/**
	 * deafult function call for listing Orders data for report 
	 * @author 			:Manisha Kanazariya
	 * Created date 	: 05-05-2018 03:15PM
	 */
	function getReport($option =""){
	 	$resId               = $this->getRestaurantForRoleBaseAccess();
	 	if($option == "export"){
	 		$export    =json_decode($_COOKIE['export']);
	 		$type      =($export->type)?$export->type:"";
	 		$payment   =($export->payment_type)?$export->payment_type:"";
		 	$startDate =$export->startDate;
		 	$endDate   =$export->endDate;
		 	$restaurant=($resId != '')?$resId:$export->restaurant;
	 	}else{
	 		$type      =($this->input->post('type'))?$this->input->post('type'):"";
	 		$payment   =($this->input->post('payment_type'))?$this->input->post('payment_type'):"";
		 	$startDate =$this->input->post('startDate');
		 	$endDate   =$this->input->post('endDate');
		 	$restaurant=($resId != '')?$resId:$this->input->post('restaurant');
	 	}
		$type_val  =$this->config->item('report_type')[$type];
	 	$startDate =($startDate !="")?date('Y-m-d H:i:s',strtotime($startDate.' 00:00:00')):"";
	 	$endDate   =($endDate != "")?date('Y-m-d H:i:s',strtotime($endDate.' 23:59:59')):"";

	 	$orderData =$this->Reports_model->getOrderData($type,'','',$startDate,$endDate,$restaurant,$payment);
	 
	 	if(count($orderData)>0){
	 			$TotalSales  =0;
		 		$TotalOrders =0;
		 		$j           =0;
		 		$TotalOd     =0;
	 			$TotalSl     =0;
	 			foreach ($orderData as $key => $value) {
		 		    if($type >3  && $type != 7 ){
		 				$data[$key]['Hours']       =date('H',strtotime($value->OrderTime));
		 				$data[$key]['days']        =date('d',strtotime($value->OrderTime));
		 				$data[$key]['months']      =date('M',strtotime($value->OrderTime));
		 				$data[$key]['mon']         =date('m',strtotime($value->OrderTime));
		 				$data[$key]['year']        =date('Y',strtotime($value->OrderTime));
		 				$data[$key]['TotalOrders'] =$value->order_id;
		 				$data[$key]['TotalSales']  =$value->total_price;
		 				if($type == 8){
		 					$data[$key]['Payment'] =$value->Payment;
		 				}
		 			}
		 		}

		 		//per hour report
		 		if($type == 4){
		 			$hour        = $data[0]['Hours'];
		 			foreach ($data as $key => $value) {
		 				if($hour == $value['Hours']){
		 					$TotalSales =$TotalSales + $data[$key]['TotalSales'];
		 					$TotalOrders ++;
		 				}else{
		 					$TotalOrders  =1;
		 					$TotalSales   =$data[$key]['TotalSales'];
		 					$hour = $value['Hours'];
		 				}
		 				$datas[$hour]['Hours']       =$hour;
		 				$datas[$hour]['TotalOrders'] =$TotalOrders;
		 				$datas[$hour]['TotalSales']  =$TotalSales;
		 			}
		 			foreach ($datas as $key => $value) {
		 				$dt[$j]['Hours']       =$value['Hours'];
		 				$dt[$j]['TotalOrders'] =$value['TotalOrders'];
		 				$dt[$j]['TotalSales']  =$value['TotalSales'];
		 				$j++;
		 				$TotalOd = $TotalOd+$value['TotalOrders'];
	 					$TotalSl = $TotalSl+$value['TotalSales'];
		 			}
		 			$orderData =$dt;
	 			}
	 			
	 			//per day report
	 			if($type == 5){
	 				$days  =$data[0]['days'];
	 				$month =$data[0]['months'];
	 				$year  =$data[0]['year'];
	 			
	 				foreach ($data as $key => $value) {
		 				if($days == $value['days'] && $year == $data[$key]['year'] && $month == $data[$key]['months']){
		 					$TotalSales =$TotalSales + $data[$key]['TotalSales'];
		 					$TotalOrders ++;
		 				}else{
		 					$TotalOrders  =1;
		 					$TotalSales =$data[$key]['TotalSales'];
		 					$year  = $data[$key]['year'];
		 					$month = $data[$key]['months'];
		 					$days  = $value['days'];
		 					$j++;
		 				}

		 				$datas[$j]['Year']         = $year;
		 				$datas[$j]['Months']       = $month;
		 				$datas[$j]['Days']         = $days;
		 				$datas[$j]['TotalOrders']  = $TotalOrders;
		 				$datas[$j]['TotalSales']   = $TotalSales;
		 				$TotalOd = $TotalOd+1;
	 					$TotalSl = $TotalSl+$data[$key]['TotalSales'];
		 			}
		 			$orderData =$datas;
		 			
	 			}
	 			//per month report
	 			if($type == 6){
	 				$month =$data[$key]['months'];
	 				$year  =$data[$key]['year'];
	 				
	 				foreach ($data as $key => $value) {
		 				if($month == $value['months'] && $year == $data[$key]['year']){
		 					$TotalSales =$TotalSales + $data[$key]['TotalSales'];
		 					$TotalOrders ++;
		 				}else{
		 					$TotalOrders  =1;
		 					$TotalSales =$data[$key]['TotalSales'];
		 					$month = $value['months'];
		 					$year = $data[$key]['year'];
		 					$j++;
		 				}
		 				$datas[$j]['Year']         = $year;
		 				$datas[$j]['Months']       = $month;
		 				$datas[$j]['TotalOrders']  = $TotalOrders;
		 				$datas[$j]['TotalSales']   = $TotalSales;
		 			    $TotalOd = $TotalOd+1;
	 					$TotalSl = $TotalSl+$data[$key]['TotalSales'];
		 			}

		 			$orderData =$datas;
	 			}

	 			//sales report summary
	 			if($type == 8){

	 				$ps      =$data[0]['Payment'];
	 				$Months  =$data[0]['months'];
	 				$year    =$data[0]['year'];
	 				$pattern =(int)($year.$data[0]['mon'].$ps);

	 				$newTl[$pattern]['TotalSales']  =0;
	 				$newTl[$pattern]['TotalOrders'] =0;
	 				$newTl[$pattern]['Payment']     =$ps;

	 				foreach ($data as $key => $value) {
	 					$pt_nxt =(int)($value['year'].$value['mon'].$value['Payment']);
		 				if(isset($newTl[$pt_nxt])){
		 					$newTl[$pt_nxt]['TotalSales']  = $newTl[$pt_nxt]['TotalSales'] + $value['TotalSales'];
							$newTl[$pt_nxt]['TotalOrders'] = $newTl[$pt_nxt]['TotalOrders'] + 1;
							$newTl[$pt_nxt]['Payment']     = $newTl[$pt_nxt]['Payment'];
		 				}else{
		 					$year    = $value['year'];
		 					$Months  = $value['months'];
		 					$newTl[$pt_nxt]['Payment']     =$value['Payment'];
		 					$newTl[$pt_nxt]['TotalOrders'] = 1;
		 					$newTl[$pt_nxt]['TotalSales']  = $value['TotalSales'];
		 				}

		 				$datas[$pt_nxt]['Year']         = $year;
		 				$datas[$pt_nxt]['Months']       = $Months;
		 				$datas[$pt_nxt]['Payment']      = $this->config->item('payment_type')[$newTl[$pt_nxt]['Payment']];
		 				$datas[$pt_nxt]['TotalOrders']  = $newTl[$pt_nxt]['TotalOrders'];
		 				$datas[$pt_nxt]['TotalSales']   = $newTl[$pt_nxt]['TotalSales'];
		 				$TotalOd = $TotalOd+1;
	 					$TotalSl = $TotalSl+$value['TotalSales'];
	 					
		 			}
		 			rsort($datas);
		 			$orderData =$datas;
	 			}
	 		/*print_r($data);exit();*/
	 		$response['success']    ='1';
	 		$response['type_val']   =$type_val;
	 		$response['data']       =$orderData;
	 		$response['TotalOd']    =$TotalOd;
	 		$response['TotalSl']    =$TotalSl;

 	 	}else{
 	 		$response['success'] ='0';
 	 		$response['type_val'] =$type_val;
	 	}
	 	if($option == "export"){
	 		if($type >3  && $type != 7 ){
	 			$key =count($orderData);
	 			$od  =$orderData[$key-1];
	 			$first_key = key($od);
	 			
	 			foreach ($od as $k => $v) {

	 				$orderData[$key][$k] ="";
	 				$orderData[$key][$first_key] ="Total";

	 				if($k == "TotalOrders"){
	 					$orderData[$key][$k] =$TotalOd;
	 				}
	 				if($k == "TotalSales"){
	 					$orderData[$key][$k] =$TotalSl;
	 				}
	 			}
 			}
	 		$this->exportReport($type_val,$orderData);
	 		 unlink($path.$fileName) or die("Unable to open file!");
	 	}else{
	 		echo json_encode($response);exit;
	 	}
	}

	/**
	 * [driverReport description]
	 * Description:
	 * @author: Manisha Kanazariya
	 * @CreatedDate:2019-07-22T13:34:23+0530
	 */
	function driverReport()
	{
		if($this->input->post("ajaxData")==null){
			$data['userdata']	= $this->session->userdata('current_user');
			$data['menu']   	= $this->menu;
			$submenu   			= $this->submenu;
			$submenuArray 		= array();
			$resId              = $this->getRestaurantForRoleBaseAccess();
			foreach($submenu as $key=>$value)
			{
				$submenuArray[$value->parent_page_id][] = $value;
			}
			$data['submenu']   	 = $submenuArray;
			$data['resId']   	 = $resId;
			$data['restaurants'] = $this->Restaurant_model->getAllRestaurantDetails($resId);
			$data['drivers']     = $this->Reports_model->getRestaurantDrivers($resId);
		}
		
		$driverId    =($this->input->post("driverId"))?$this->input->post("driverId"):null;
		$resId       =($this->input->post("resID"))?$this->input->post("resID"):null;
		$startDate   =($this->input->post("startDate"))?$this->input->post("startDate"):null;
		$endDate     =($this->input->post("endDate"))?$this->input->post("endDate"):null;
		$paymentType =($this->input->post("paymentType"))?$this->input->post("paymentType"):null;
   
   		$totalOrders                =$this->Reports_model->countDriverOrders($driverId,$resId,$startDate,$endDate,$paymentType);
   		// print_r($totalOrders);
		$config["base_url"] 		= site_url('Reports/driverReport');
		$config["total_rows"] 		= $totalOrders->total;
		$config["per_page"] 		= 20;
		$config['use_page_numbers'] = TRUE;
		$config['cur_tag_open']		= '&nbsp;<a class="active">';
		$config['cur_tag_close']	= '</a>';
		$config['next_link'] 		= 'Next';
		$config['prev_link'] 		= 'Previous';
		
		$this->pagination->initialize($config);

		if($this->uri->segment(3)){
			$page = ($this->uri->segment(3)) ;
		}
		else{
			$page = 1;
		}

		$offset             =($page - 1) * $config["per_page"];
		$repData            = $this->Reports_model->getDriverOrders($config["per_page"],$offset,$driverId,$resId,$startDate,$endDate,$paymentType);
		$strLinks 			= $this->pagination->create_links();
		

		if($this->input->post("ajaxData")==null){
			$data['repData']    = $repData;
			$data['offset'] 	= $offset + 1;	
			$data["links"] 		= explode('&nbsp;',$strLinks);
			$data["totalOrders"]= $totalOrders->total;
			$data["totalAmount"]= number_format($totalOrders->amount,3,'.','')." KD";
			// print_r($data);
		}else{
			if(count($repData)>0){

				foreach ($repData as $key => $value) {
					$value->delivered_time =date('d M Y',strtotime($value->delivered_time));
					$value->order_type     =$this->config->item('payment_type')[$value->order_type];
					$value->order_status   =$this->config->item('OrderStatus')[$value->order_status];
				}

				$message['repData']    =$repData;
				$message['offset'] 	   = $offset + 1;	
				$message['links']      =explode('&nbsp;',$strLinks);
				$message["totalOrders"]= $totalOrders->total;
				$message["totalAmount"]= number_format($totalOrders->amount,3,'.','')." KD";
				$response =array("success"=>"1","data"=>$message);

			}else{
				$response =array("success"=>"0","data"=>"Orders Not Found!");
			}
			echo json_encode($response);exit;
		}

		$data['type']        =$this->config->item('payment_type');
		$this->load->view('Elements/header',$data);
		$this->load->view('Reports/driverReport',$data);
		$this->load->view('Elements/footer');
	}

	function exportDriverReport()
	{
		$export    =json_decode($_COOKIE['exportDriverReport']);
		$driverId    =($export->driverId)?$export->driverId:null;
		$resId       =($export->resID)?$export->resID:null;
		$startDate   =($export->startDate)?$export->startDate:null;
		$endDate     =($export->endDate)?$export->endDate:null;
		$paymentType =($export->paymentType)?$export->paymentType:null;
		$orderData   =array();
		$repData  =$this->Reports_model->getDriverOrders(null,null,$driverId,$resId,$startDate,$endDate,$paymentType);
	
		foreach ($repData as $key => $value) {
			$orderData[$key]['Sr']          =$key+1;
			$orderData[$key]['OrderId']     =$value->order_id;
			$orderData[$key]['Driver']      =$value->first_name." ".$value->last_name;
			$orderData[$key]['Amount']      =number_format($value->total_price,3, '.', '')." KD";;
			$orderData[$key]['OrderTime']   =$value->delivered_time;
			$orderData[$key]['Restaurant']  =$value->restaurant_name;
			$orderData[$key]['PaymentType'] =$this->config->item('payment_type')[$value->order_type];
			$orderData[$key]['OredreStatus']=($value->order_status==7)?"Successful":"Disputed";
		}
		$this->exportReport("DriverReport",$orderData);
		delete_cookie('exportDriverReport'); 
	 	unlink($path.$fileName) or die("Unable to open file!");

	}
}