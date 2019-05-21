<?php

	/**
	 * Controller Name: Hotel
	 * Descripation: Use to create menu role dependent
	 * @author Vaibhav Mehta
	 * Created date: 05 April 2017
	 * Modified date: 
	 */
error_reporting(1);
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends MY_Controller
{
	/**
	 * function to invoke necessary component
	 * @author Vaibhav Mehta
	 * Created Date: 05 April 2017
	 * Modified Date: 
	 */

	function __construct()
	{
		parent::__construct();
		$this->load->model('Menu_model');
		$this->getMenu();
		$request 	= base_url(uri_string());
		if(isset($request))
		{
			$get_data = explode("/",$request);
		}
		if(isset($get_data[5]))
		{
			$str 		= $get_data[4].'/'.$get_data[5];
		}
		else
		{
			$str 		= $get_data[4];
		}
		//echo "<pre>"; print_r($str); exit;
		$base 		= site_url();
	}

	
}