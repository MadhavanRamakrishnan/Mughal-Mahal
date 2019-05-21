<?php 

	defined('BASEPATH') OR exit("No direct script access allowed");

	/**
	 * Controller Name 	: Migrate
	 * Descripation 	: Use to perform migration of data
	 * @author 			: Manisha KAnazariya
	 * Created date 	: 15-6-2018 01:15PM
	 */
	class Migrate extends CI_controller
	{
		
		public function index(){
			$this->load->library('migration');

			if($this->migration->current() === FALSE){
				show_error($this->migration->error_string());
			}
		}
	}

?>