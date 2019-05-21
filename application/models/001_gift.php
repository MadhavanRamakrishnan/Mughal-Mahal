<?php

	defined("BASEPATH") OR exit("NO direct script access allowed");

	/**
	* 
	*/
	class Migration_gift extends CI_Migration
	{
		
		public function up(){
			
			$fields = array(
			        'is_deleted' => array('type' => 'TINYINT','default' => 0)
			      
			);
			$this->dbforge->add_column('sv_gift', $fields);

			
			
		}
		public function down()
		{

		}
	}	
	
?>	