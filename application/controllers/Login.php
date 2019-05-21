<?php
	
	/**
	 * Controller Name 	: Login
	 * Descripation 	: Use to manage user login and logout
	 * @author 			: Vaibhav Mehta
	 * Created date 	: 08 September 2017 2:00PM
	 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller
{
	/**
	 * function to invoke necessary component
	 * @author Vaibhav Mehta
	 */
	function __construct(){
		parent::__construct();
		//loading login model
		$this->load->model(array('Login_model','User_model'));
		$this->load->library('form_validation');
	}


	/**
	 * deafult function call when controller class is load than login view
	 * @author Vaibhav Mehta
	 */
	function index()
	{
		if(!$this->session->userdata('current_user'))
		{
			$data['title']='Login';
			$this->load->view('Elements/login_header',$data);
			$this->load->view('Login/login');  
			$this->load->view('Elements/login_footer');	
		}
		else{
			redirect('Dashboard');
		}
	}



	/**
	 * function to authenticate user 
	 * @author Vaibhav Mehta
	 */
	function userAuthentication()
	{
		$email 		= $this->input->post('email');
		$password 	= $this->input->post('password');

		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == FALSE)
        {		
        	$data['post'] 		= $this->input->post();
        	$data['error']     	= validation_errors();
    	   	$this->load->view('Elements/login_header',$data);
			$this->load->view('Login/login');  
			$this->load->view('Elements/login_footer');	
        }
        else
        {
        	$login_user = $this->Login_model->user_authentication($email,$password);
        	
			if(sizeof($login_user)>0){
				$this->checkVAlidUser($login_user);
			}else{
				//setting flash data
				$this->session->set_flashdata('login_error','Invalid login credentials!');
				$data['post'] 		= $this->input->post();
	    	   	$this->load->view('Elements/login_header',$data);
				$this->load->view('Login/login');  
				$this->load->view('Elements/login_footer');
			}
        } 
	}

	/**
	 * function to logout user
	 * @author Vaibhav Mehta
	 */
	function logoutUser()
	{
		$this->session->unset_userdata('current_user');
		$this->session->unset_userdata('customer_user');
		redirect('Login');
	}

	/**
	 * function for enter Email forgetpassword  of user
	 * @author Vaibhav Mehta
	 */
	function emailForForgetPassword()
	{
		$data[] = "";
		$this->load->view('Elements/login_header',$data);
		$this->load->view('Login/forget_password');  
		$this->load->view('Elements/login_footer');	
	}

	/**
	 * function for forget password  of user
	 * @author Vaibhav Mehta
	 */
	
	function forgotPassword()
	{
		$email 		= $this->input->post('email');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');

		if ($this->form_validation->run() == FALSE)
        {		
        	$data['post'] 		= $this->input->post();
        	$data['error']     	= validation_errors();
    	   	$this->load->view('Elements/login_header',$data);
			$this->load->view('Login/forget_password');  
			$this->load->view('Elements/login_footer');
        }
        else
        {
        	$res 	= $this->Login_model->checkEmailExist($email);
        	if(is_array($res) && count($res)>0)
        	{
				$mail 							= $this->base64url_encode($email);
				$passwordResetLink 				= site_url("login/resetPassword/".$mail.'/');
				$data['passwordResetLink']		= $passwordResetLink;
				$data['email_template']			= 'password_forgot';
				$data['to_email']				= trim($email);
				$data['base_url']				= base_url();
				$data['user_name']				= $res[0]->first_name.' '.$res[0]->last_name;				
				$data['subject']				= 'Reset Password';
				$message 						= "Click On below link to reset your password<br>".$passwordResetLink;

				$mails = $this->sendMail($data);

				if($mails['success'] == "1")
				{
					$userUpdate = array(
						"change_password_date" 		=> date("Y-m-d H:i:s"),
						"security_token" 			=> $mail,
						"security_token_expiry_date"=> date("Y-m-d H:i:s",strtotime('+1 hour'))
						);
					$dataSuccess = $this->Login_model->updateUserForgotPasswordDate($res[0]->user_id,$userUpdate,"");

					$this->session->set_flashdata('success','Please check your inbox for changing your password.');
					redirect('Login/emailForForgetPassword');
				}
				else
				{
					$this->session->set_flashdata('error','Email sending failure, please try again!');
					redirect('Login/emailForForgetPassword');
				}
        	}
        	else
        	{
        		$this->session->set_flashdata('error','Please enter valid email address!');
				redirect('Login/emailForForgetPassword');
        	}
        }
	}
		

	/**
	 * function to reset password of user
	 * @author Vaibhav Mehta
	 */

	function resetPassword($securityToken=NULL){
		if($this->uri->segment(3) != ""){
			$securityToken = $this->uri->segment(3);
		}
		$user_role          = $this->uri->segment(4);
		$success 			= 0;
		$messages 			= "";
		$data['userdata'] 	= "";
		$data['user_role'] 	= $user_role;

		if($securityToken != ""){

			$userData 		= $this->User_model->getUserDetailsBySecurityToken($securityToken,$user_role);
			
			if(is_array($userData) && count($userData)>0){
				//echo date('Y-m-d H:i:s',strtotime(" -30 minutes"))."<br>";
				$tokenExpiryData = $userData[0]->security_token_expiry_date;
				if(date('Y-m-d H:i:s',strtotime(" -60 minutes")) < $tokenExpiryData)
				{
					$success 			= 1;
					$data['userdata'] 	= $userData;

					$this->load->view('Elements/login_header',$data);
					$this->load->view('Login/reset_password');  
					$this->load->view('Elements/login_footer');
				}
				else
				{
					$this->session->set_flashdata('error','Security token is Expired, Please try again!');
					redirect('Login/emailForForgetPassword');
				}
			}
			else
			{
				$this->session->set_flashdata('error','Security token is not Exited, Please try again!');
				redirect('Login/emailForForgetPassword');
			}
		}
		else{
			$this->session->set_flashdata('error','Security token is required, Please try again!');
			redirect('Login/emailForForgetPassword');
		}
	}


	/**
	 * function to update password of user
	 * @author Vaibhav Mehta
	 */
	function updateUserpassword()
	{
		//echo "<pre>"; print_r($this->input->post());exit;
		if($this->input->post('security_token') != "" && $this->input->post('user_id') != "")
		{		
			$userId 			= $this->input->post('user_id');
			$securityToken 		= $this->input->post('security_token');
			$password 			= $this->input->post('password');
			$confirmPassword 	= $this->input->post('confirm_password');
			$user_role       	= $this->input->post('user_role');

			$this->form_validation->set_rules('password', 'Password', 'required');
			$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');

			$userData 		= $this->Login_model->getUserDetailsBySecurityToken($securityToken,$user_role);
			
			$data['failure'] = '';

			if ($this->form_validation->run() == FALSE)
	        {		
        	   	$data['userdata'] = $userData;
        	   	$data['post'] = $this->input->post();
        	   	$this->load->view('Elements/login_header',$data);
				$this->load->view('Login/reset_password');  
				$this->load->view('Elements/login_footer');
	        }
	        else
	        {
	        	$userDataUpdate = array(
    				"password" 						=> md5($password),
    				"security_token" 				=> '',
    				"security_token_expiry_date" 	=> '',
    				"change_password_date"			=> date("Y-m-d H:i:s")
        		);

	        	$res = $this->Login_model->updateUserDetailsBYSecurityToken($securityToken,$userDataUpdate,
	        		$user_role);

				if(isset($res) && $res==1)
				{
					$this->session->set_flashdata('success','Your password has been reset successfully.');
					if($user_role != '5'){
						redirect('Login');
					}else{
						redirect('Home');						
					}
				}
				else
				{
					$this->session->set_flashdata('error','Failed to reset your password, Please try again!');
					$data['userdata'] 	= $userData;
	        	   	$data['post'] 		= $this->input->post();
	        	   	$this->load->view('Elements/login_header',$data);
					$this->load->view('Login/reset_password');  
					$this->load->view('Elements/login_footer');
				}
	        }
		}
		else
		{
			$this->session->set_flashdata('error','please try again!');
			redirect('Login');
		}
	}
	
	public function test() {

	    $this->load->library('email');    
	    $config['protocol']     = 'smtp';
	    $config['smtp_host']    = 'mail.q8captain.com'; 
	    $config['smtp_port']    = '26';
	    $config['smtp_user']    = 'notification@q8captain.com';
	    $config['smtp_pass']    = 'Q8captain@123';
	    $config['smtp_host']    = 'ssl://smtp.googlemail.com'; 
	    $config['smtp_port']    = '465';
	    $config['smtp_user']    = 'oneclickdevelopers@gmail.com';
	    $config['smtp_pass']    = 'szmbsymbevvzwcfi';
	    $config['mailtype']     = 'html';
	    $config['charset']      = 'utf-8';
	    $config['newline']      = '\r\n';
	    $config['crlf']         = '\r\n';
	    $config['wordwrap']     = TRUE;
	    $config['validation']   = TRUE;
	    $config['smtp_timeout'] = 50;
	    $config['smtp_keepalive'] = TRUE;
	    $config['protocol']  = "smtp";
	    $config['smtp_port'] = '465';
	    $config['smtp_host'] = 'ssl://smtp.googlemail.com'; 
	    $config['smtp_user'] = 'onlinegymtrainers@gmail.com';
	    $config['smtp_pass'] = 'onlinegym123';

	    $config['protocol']     = 'smtp';
	    $config['smtp_host']    = 'ssl://smtp.googlemail.com'; 
	    $config['smtp_port']    = '465';
	    $config['smtp_user']    = 'oneclickdevelopers@gmail.com';
	    $config['smtp_pass']    = 'szmbsymbevvzwcfi';
	    $config['mailtype']  = 'html';
	    $config['charset']   = 'utf-8';
	    $config['newline']   = "\r\n";
	    $config['wordwrap']  = TRUE;
	    $this->email->initialize($config); 
	    
	    $this->email->from("kanazariya16mahi@gmail.com","Q8 Captain");
	    $this->email->to("mh_2016_hm@outlook.com");
	    $this->email->subject("test Email");
	    $this->email->message("hiiiiiiiiiiii");
	    $result =$this->email->send();
	    print_r($this->email->print_debugger());die;
	    if($result)
	      return "true";
	    else
	      return "false";
	    echo json_encode($response);
	    exit;
	}

}
