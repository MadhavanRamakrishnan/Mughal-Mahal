<?php
/**
 * Model Name 		: Login_model
 * Descripation 	: Use to manage user login and logout
 * @author  		: Vaibhav Mehta
 * Created date 	: 08 September 2017 4:00PM
 */
class Login_model extends CI_Model
{
	
	/**
	 * Description 		: Use to manage user side web serive query 
	 * Input 			: Mention Input parameters for login email,password 
	 * Output 			: User has logged in if he puts correct credentials
	 * Created by 		: Vaibhav Mehta
	 * Created Date 	: 08 September 2017 4:00PM
	*/ 
	function user_authentication($email,$password)
	{
		$admin 	 = $this->config->item('super_admin_role');
		$owner 	 = $this->config->item('restaurant_owner_role');
		$sales 	 = $this->config->item('sales_role');
		$this->db->where('email',$email);
		$this->db->where('password',md5($password));
		$this->db->where("(role_id ='$admin' OR role_id ='$owner' OR role_id ='$sales') ");
		$this->db->where("is_active","1");
		
		$query=$this->db->get('tbl_users');
		return $query->result();
	}

	/**
	 * Description 		: Use to check the email address is exists in database or not
	 * Created by 		: Vaibhav Mehta
	 * Created Date 	: 08 September 2017 4:00PM
	*/ 
	function checkEmailExist($email)
	{
		$this->db->where('email',$email);
		$this->db->where('role_id !=','5');
		$this->db->where('is_active','1');
		$query = $this->db->get('tbl_users');
		return $query->result();
	}

	/**
	 * Description 		: Update forgot password data
	 * Created by 		: Vaibhav Mehta
	 * Created Date 	: 08 September 2017 4:00PM
	*/ 
	function updateUserForgotPasswordDate($userId,$userData,$user_role ="")
		{if($user_role !=""){
				$this->db->where('role_id',$user_role);
			}else{
				$this->db->where('role_id !=','5');
			}
		$this->db->where("user_id",$userId);
		$this->db->update("tbl_users",$userData);

		
		return $this->db->affected_rows();
	}

	/**
	 * function to build query to get user security token 
	 * @author Vaibhav Mehta
	 */
	function getUserDetailsBySecurityToken($securityToken,$user_role ="")
	{
		if($user_role !=""){
			$this->db->where('role_id',$user_role);
		}else{
			$this->db->where('role_id !=','5');
		}
		$this->db->where("security_token",$securityToken);
		$query=$this->db->get("tbl_users");
		return $query->result();
	}

	/**
	 * function to build query to update security token 
	 * @author Vaibhav Mehta
	 */
	function updateUserDetailsBYSecurityToken($securityToken,$userData,$user_role ="")
	{
		if($user_role !=""){
			$this->db->where('role_id',$user_role);
		}
		$this->db->where("security_token",$securityToken);
		$this->db->update("tbl_users",$userData);
		return $this->db->affected_rows();
	}

	public function clearAppLogCron(){
		$this->db->empty_table('tbl_api_log'); 
    }
}

