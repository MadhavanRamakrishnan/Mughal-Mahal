<?php

	/**
	 * Controller Name: MY_Controller
	 * Descripation: Use to global functinality
	 * @author Suresh Suthar
	 * Created date: 14 Nov 2016
	 */

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	function __construct(){
		parent::__construct();
		$this->load->model(array('Menu_model','User_model','Restaurant_model'));
		$this->admin_Role   =$this->config->item("super_admin_role");
		$this->owener_Role  =$this->config->item("restaurant_owner_role");
		$this->sales_Role   =$this->config->item("sales_role");
	}
	function checkLogin(){
		if($this->session->userdata('current_user')==''){
			redirect('Login');
		}
	}
 
	function sendMail($data)
	{
		$this->load->library('email');
		$config['protocol'] 	= $this->config->item('protocol');
		$config['smtp_host'] 	= $this->config->item('smtp_host');
		$config['smtp_port'] 	= $this->config->item('smtp_port');
		$config['smtp_user'] 	= $this->config->item('smtp_user');
		$config['smtp_pass'] 	= $this->config->item('smtp_pass');
		$config['charset'] 		= $this->config->item('charset');
		$config['newline'] 		= $this->config->item('newline');
		$config['mailtype'] 	= $this->config->item('mailtype');
		$config['validation'] 	= $this->config->item('validation');
		$config['wordwrap'] 	= $this->config->item('wordwrap');
		$this->email->initialize($config);
		$this->email->set_newline("\r\n");
		// $this->email->from('developer@itoneclick.com', $this->config->item('project_name'));
		$this->email->from('customercare@mughalmahal.com', $this->config->item('project_name'));
		$this->email->to($data['to_email']);
		//$message  = $this->load->view('Email/header',$data,TRUE);
		$message = $this->load->view('Email/'.$data["email_template"],$data,TRUE);
		//echo "<pre>"; print_r($message);exit;
		//$message .= $this->load->view('Email/footer',$data,TRUE);


		$this->email->subject($data['subject']);
		$this->email->message($message);
		if($this->email->send()){
			$response=array("success"=>"1","message"=>"Mail Sent Successfully");
		}
		else{
			$response=array("success"=>"0","message"=>$this->email->print_debugger());
		}
		return $response;
	}
	/**
	 * Description : Check role base access for any type of user and depend on this show menu
	 * Created By : Vaibhav Mehta
	 * Time : 07:30 PM 30-12-2017
	*/
	function getAccess()
	{
		$request 	= base_url(uri_string());
		$cname = $this->router->fetch_class();
		$mname = $this->router->fetch_method();

		if(isset($cname) && !is_numeric($mname))
		{
			$str 		= $cname.'/'.$mname;
		}
		else
		{
			$str 		= $cname.'/index';
		}

		$rid 	= $this->session->userdata('current_user')[0]->role;
		$access = $this->checkAccess($rid,$str);
		if( is_array($access) && !empty($access[0]->page_id) && !empty($access[0]->role_id) )
		{
			
		}
		$access[0]->page_id;
		$access[0]->role_id;
	}

	/**
	 * Description : Check role base access for any type of user and depend on this show menu
	 * Created By : Vaibhav Mehta
	 * Time : 07:30 PM 30-12-2017
	*/
	function checkAccess($rid,$str)
	{
		//echo "<pre>"; print_r($str);exit;
		if($rid)
		{
			$this->db->from('qc_role_page');
			$this->db->join('qc_pages','qc_pages.page_id=qc_role_page.page_id');
			$this->db->where('qc_role_page.role_id',$rid);
			$this->db->where('qc_pages.page_url',$str);
		}
		else
		{
			$this->db->from('qc_role_page');
			$this->db->join('qc_pages','qc_pages.page_id=qc_role_page.page_id');
			$this->db->where('qc_role_page.role_id','5');
			$this->db->where('qc_pages.page_url',$str);
		}

		$query=$this->db->get();
		return $query->result();
	}

	function sendPushNotificationUsingFirebase($orderStatus, $orderId)
	{
		//$ch = curl_init("https://fcm.googleapis.com/fcm/send");
	    $url = "https://fcm.googleapis.com/fcm/send";

	    $orderData	 	= $this->Webservice_customer_model->getOrderData($orderId);
	    $userMetaData 	= $this->Webservice_customer_model->getUserMetaData($orderData[0]->user_id);

	    for($i = 0; $i < sizeof($userMetaData); $i++){
	    	if($userMetaData[$i]->device_token){
		    	$token[] = $userMetaData[$i]->device_token;
	    	}
	    }

	    if($orderStatus == 2){
	    	//Title of the Notification.
		    $title = "Order Confirmed";
		    //Body of the Notification.
		    $body = "Your Order Confirmed";
		    $data['order_id'] = $orderId;
		    $notification_type = "confirm_order";
	    }elseif($orderStatus == 3){
		    $title = "Cooking";
		    $body = "Food in Kichen";
		    $data['order_id'] = $orderId;
		    $notification_type = "cooking";
	    }elseif($orderStatus == 4){
	    	$title = "Order Collected";
		    $body = "Driver Collected your order";
		    $data['order_id'] = $orderId;
		    $notification_type = "Collected";
	    }elseif($orderStatus == 5){
	    	$title = "On the way";
		    $body = "Driver on the way";
		    $data['order_id'] = $orderId;	
		    $notification_type = "on_the_way";
	    }elseif($orderStatus == 7){
	    	$title = "Food Delivered";
		    $body = "Your food has been delivered";
		    $data['order_id'] = $orderId;
		    $notification_type = "delivered";
	    }elseif($orderStatus == 13){
	    	$title = "Order Cancelled";
		    $body = "Order cancelled by customer";
		    $data['order_id'] = $orderId;
		    $notification_type = "cancelled";
	    }elseif($orderStatus == 14){
	    	$title = "Order Cancelled";
		    $body = "Order cancelled by restaurant";
		    $data['order_id'] = $orderId;
		    $notification_type = "cancelled";
	    }
	  	
	    $mainData = array(
	        'title' 				=> $title,
	        'body' 					=> $body,
	        'data' 					=> $data,//Order Id Send In data
	        'notification_type' 	=> $notification_type,//Notification Type ex. Order place, order cancel
	        'sound' 				=>'Default'
	    );

	    $notification = array('title' =>$title, 'body' => $body, 'sound' => 'default');

	    $arrayToSend = array('registration_ids' => $token, 'notification' => $notification,'priority'=>'high','data'=>$mainData);

	    //Generating JSON encoded string form the above array.
	    $json = json_encode($arrayToSend);

	    //Setup headers:
	    $headers = array();
	    $headers[] = 'Content-Type: application/json';
	    $headers[] = 'Authorization: key= AAAAZDOJtn0:APA91bEYPFszLDyCsSRmzjswhvwLojqdccy0EZav26hqskeR9FLgWKk4GIBATU_PZ84fHhKBP6GIZ6vd6tzK31x7x-9EIq-V1KZc4aXa3hcRU9RJ0HwbLcEGTeEvJqZ-3d-v0thoM9SM'; //Server key here

	    //Setup curl, add headers and post parameters.
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);       

	    //Send the request
	    $response = curl_exec($ch);

	    //Close request
	    curl_close($ch);
	    //echo $response;
	}

	function sendPushNotificationUsingFirebaseToDriver($userId, $orderId)
	{
		//$ch = curl_init("https://fcm.googleapis.com/fcm/send");
	    $url = "https://fcm.googleapis.com/fcm/send";

	    $userMetaData 	= $this->Webservice_customer_model->getUserMetaData($userId);

	    for($i = 0; $i < sizeof($userMetaData); $i++){
	    	if($userMetaData[$i]->device_token){
		    	$token[] = $userMetaData[$i]->device_token;
	    	}
	    }

    	//Title of the Notification.
	    $title = "New Order";
	    //Body of the Notification.
	    $body = "New Order Assigned";
	    $data['order_id'] = $orderId;
	    $notification_type = "new_order";
	  	
	    $mainData = array(
	        'title' 				=> $title,
	        'body' 					=> $body,
	        'data' 					=> $data,//Order Id Send In data
	        'notification_type' 	=> $notification_type,//Notification Type ex. Order place, order cancel
	        'sound' 				=>'Default'
	    );

	    $notification = array('title' =>$title, 'body' => $body, 'sound' => 'default');

	    $arrayToSend = array('registration_ids' => $token, 'notification' => $notification,'priority'=>'high','data'=>$mainData);

	    //Generating JSON encoded string form the above array.
	    $json = json_encode($arrayToSend);

	    //Setup headers:
	    $headers = array();
	    $headers[] = 'Content-Type: application/json';
	    $headers[] = 'Authorization: key= AAAAZDOJtn0:APA91bEYPFszLDyCsSRmzjswhvwLojqdccy0EZav26hqskeR9FLgWKk4GIBATU_PZ84fHhKBP6GIZ6vd6tzK31x7x-9EIq-V1KZc4aXa3hcRU9RJ0HwbLcEGTeEvJqZ-3d-v0thoM9SM'; //Server key here

	    //Setup curl, add headers and post parameters.
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);       

	    //Send the request
	    $response = curl_exec($ch);

	    //Close request
	    curl_close($ch);
	    // echo $response;
	}

	/**
     * Description : send merchant push to android device
     * Input : device token and push data array
     * Output :  send push to target device
     * Created by : Suresh suthar
     * Created Date: 03/04/17 5:48 PM
      */
    function sendAndroidPush($deviceToken,$pushData,$data){

   
        $server_key = 'AAAAbk-G9Cc:APA91bG--PZ9_5c_afRIetpww5aeYYdKA5AoNbt-o62xmWb_EQ6RBDu7_5aJjoaSHjwILWffsrMUFbvfDi2wVlCj-DuRr3uPGymEJK8QXZQAq0r2crBreR2sF5oeTQhrtWK1V2a1iamk';

          $url = 'https://fcm.googleapis.com/fcm/send';
       
        $msg = array
        (
            'title'  => $pushData['title'],
            'body'=>$pushData['body'],
            'subtitle' => '',
            'tickerText' => '',
            'vibrate' => 1,
            'sound'  => 1,
            'largeIcon' => 'large_icon',
            'smallIcon' => 'small_icon',
            'other_data'=>$data
        );

        $fields = array(
             'registration_ids' => $deviceToken,
              'data'=>$msg
        );


        $headers = array(
            'Authorization:key='.$server_key,
            'Content-Type: application/json'
            );

       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0); 
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
       $result = curl_exec($ch);          
       echo curl_error($ch);
       if ($result === FALSE) {
           die('Curl failed: ' . curl_error($ch));
       }
       curl_close($ch);
       //echo $result;exit;
    }

    function iosPushNotification($pushData,$deviceToken,$data){

        //$deviceToken = '0ae89eb04f6134d9e1b5300cfb1dd968549703fd5eed987a32a8fce3c1aa27fa';

        $passphrase = 'Oneclick1@';
       
       
        $message = $pushData['body'];
       
        $ctx = stream_context_create();
        $certificateFile = APPPATH.'third_party/OcCalender.pem';
        //$certificateFile = APPPATH.'third_party/ck.pem';
        stream_context_set_option($ctx, 'ssl', 'local_cert', $certificateFile);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

       
        //$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
        $fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
        /*if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);
        echo 'Connected to APNS' . PHP_EOL;*/
       
        $body['aps'] = array(
            'alert' => $message,
            'badge' => +1,
            'sound' => 'default'
        );
        $body['message'] = $data;
       
        $payload = json_encode($body);
       
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
       
        $result = fwrite($fp, $msg, strlen($msg));
        /*if (!$result)
            echo 'Message not delivered' . PHP_EOL;
        else
            echo 'Message successfully delivered' . PHP_EOL;*/
       
        fclose($fp);
    }


	/**
     * Description :To encode data in the specifik formate
     * Created by :Manisha Kanazariya
     * Created Date: 1 september 2017 6:20 PM
      */
      function base64url_encode($data) {     	
	    return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 	
	}
	/**
     * Description :To decode data in the specifik formate
     * Created by :Manisha Kanazariya
     * Created Date: 1 september 2017 6:50 PM
      */
	  function base64url_decode($data) {     	
	    return base64_decode($data); 	
	}

	/**
     * Description :To generate Random password
     * Created by :Manisha Kanazariya
     * Created Date: 9 september 2017 12:20 PM
      */
	function randomPassword() {
 	   $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890@#$_*&';
    	$pass = array(); //remember to declare $pass as an array
    	$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    	for ($i = 0; $i < 8; $i++) {
	        $n = rand(0, $alphaLength);
    	    $pass[] = $alphabet[$n];
    	}
    	return implode($pass); //turn the array into a string
	}

	/**
     * Description 	: To Change date format
     * Created by 	: Purvesh Patel
     * Created Date : 11 september 2017 1:20 PM
      */
	function changedDateTimeSFormat($datess) {
 	   $data  = date("d-M-Y H:i",strtotime($datess));
 	   return $data;
	}
	function databaseDateformate($date){
		if($date != ""){
			 $data  = date("Y-m-d",strtotime($date));
 	   		return $data;
		}
		
	}

	/**
	 * Description : get page id from the requested url
	 * input : page_url string
	 * Output : If page string exists than give its page id
	 * Created By : Vaibhav Mehta
	 * Time : 11:30 PM 05-04-2017
	*/
	function getPage($str)
	{
		$this->db->select('page_id');
		$this->db->from('qc_pages');
		$this->db->where('qc_pages.page_url',$str);

		$query=$this->db->get();
		return $query->result();
	}

	/**
	 * Description : get the menu from database
	 * input : page_url string
	 * Output : If page string exists than give its page id
	 * Created By : Vaibhav Mehta
	 * Time : 11:30 PM 05-04-2017
	*/
	function getMenu()
	{
		$rid = $this->session->userdata('current_user')[0]->role_id;
		if($rid)
		{
			$menu = $this->Menu_model->getMenu($rid);
		}
		else
		{
			redirect('Login');
		}
		
		return $menu;
	}

	/**
	 * Description : get submenu from databse
	 * input : page_url string
	 * Output : If page string exists than give its page id
	 * Created By : Vaibhav Mehta
	 * Time : 11:30 PM 05-04-2017
	*/
	function getSubMenu()
	{
		$cname = $this->router->fetch_class();
		$mname = $this->router->fetch_method();
		$url   = $cname.'/'.$mname;
		$rid = $this->session->userdata('current_user')[0]->role_id;
		if($rid)
		{
			$menu = $this->Menu_model->getSubMenu($rid);
			return $menu;
		}
		else
		{
			redirect('Login');
		}
	}

	/**
	 * Description : Function to calculate time of service call
	 * Created By : Vaibhav Mehta
	 * Time : 01:30 PM 07-11-2017
	*/
	function evaluateExecutionTime($startTime,$apiName,$postval=null)
	{
		$endTime = $this->benchmark->mark('code_end');
		$executionTime = ($endTime - $startTime);

		$data['execution_time'] = $executionTime;
		$data['service_name'] 	= $apiName;
		$data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		$data['post_value'] = json_encode($postval);
		$data['date']			= date("Y-m-d H:i:s");
		$this->db->insert("tbl_api_log",$data);		
	}

	function compareGivenDate($givenDate)
	{
		$current = strtotime(date("Y-m-d"));
		$date    = strtotime(date("d-m-Y", strtotime($givenDate)));
				
		// 0 = today , >1 = Future Date , >0 = tomorrow , <-1 = Long Back , else yesterday
		$datediff = $date - $current;
		$difference = floor($datediff/(60*60*24));
		if($difference==0)
		{
			return 0;
		}
		else if($difference > 1)
		{
			return 1;
		}
		else if($difference > 0)
		{
			return 2;
		}
		else if($difference < -1)
		{
			return 3;
		}
		else
		{
			return 4;
		}
	}

	function getRestaurantForRoleBaseAccess()
	{
		$data['userdata'] = $this->session->userdata('current_user');
		if($data['userdata']){
			$rid 		= $data['userdata'][0]->role_id;
			$uid 		= $data['userdata'][0]->user_id;
			$superAdmin = $this->config->item('super_admin_role');
			$restOwner 	= $this->config->item('restaurant_owner_role');
			$restMang 	= $this->config->item('restaurant_manager_role');
			$restId 	= '';
			$resId 	    = '';
			if($rid==$superAdmin)
			{
				$resId = '';	
			}
			else if($rid==$restOwner)
			{
				$restId = $this->User_model->getRestaurantIdOfOwner($uid,$rid);
			}
			else if($rid==$restMang)
			{
				$restId = $this->User_model->getRestaurantIdOfManager($uid,$rid);
			}
			if($restId)
			{
				$resId = $restId[0]->restaurant_id;
			}
			return $resId;
		}
		else{
			redirect('Login');
		}
	}
	/**
	 * function to check is manager or owner assigned by 
	 * @author Manisha Kanazariya
	 * Created date: 09-10-2017 7:41 PM
	 */


	function checkVAlidUser($data){
		$adminRole   = $this->config->item('super_admin_role');
		$restOwner 	 = $this->config->item('restaurant_owner_role');

		if($data[0]->role_id>$adminRole && $data[0]->role_id == $restOwner){

			$getowner =$this->Restaurant_model->checkOwner($data[0]->user_id);
			if(count($getowner)>0){
				//setting user session 
				$data[0]->restaurant_name =$getowner[0]->restaurant_name;

				$this->session->set_userdata('current_user',$data);
				redirect('Dashboard');
			}else{
				$this->session->set_flashdata('login_error','You don\'t have access to login with any restaurant,Please contact with admin.');
	    	   	$this->load->view('Elements/login_header');
				$this->load->view('Login/login');  
				$this->load->view('Elements/login_footer');
			}
		}else{
				$this->session->set_userdata('current_user',$data);
				redirect('Dashboard');
		}
	}

	/**
	 * function to return order id formate
	 * @author Manisha Kanazariya
	 * Created date: 09-10-2017 7:41 PM
	 */
	public function getOrerId($id){
	  return str_pad($id,$this->config->item('order_range'),"0",STR_PAD_LEFT);
	}
    
    public function isLoginUser(){
    	$rsid =$this->getRestaurantForRoleBaseAccess();
    	$data['userdata'] = $this->session->userdata('current_user');
		$rid 		= $data['userdata'][0]->role_id;
		$admin_role = $this->config->item('super_admin_role');
		$sales_role = $this->config->item('sales_role');
		if($rsid =="" && $rid !=$admin_role && $rid!= $sales_role){
			$this->session->set_userdata('current_user', '');
			redirect('Login');
		}
    }

    public  function ftime($time,$f) {
	    if (gettype($time)=='string')	
		  $time = strtotime($time);	 
	  
	    return ($f==24) ? date("G:i", $time) : date("g:i A", $time);	
	  }
}