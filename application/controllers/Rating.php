<?php
/**
* Controller Name: Restaurants
* Descripation: Use to manage Restaurants activity
* @author Vaibhav Mehta
* Created date: 28-09-2017 3:50 PM
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Rating extends MY_Controller
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
        $this->load->model(array('Login_model','Rating_model','Restaurant_model'));
        $this->checkLogin();
        $this->menu     = $this->getMenu();
        $this->submenu  = $this->getSubMenu();
        $this->load->library('form_validation');
        $this->load->library('upload');
        $this->load->library('pagination');
        $this->form_validation->run($this);
    }

    /**
    * deafult function call when controller class is load
    * @author Vaibhav Mehta
    * Created date: 28-09-2017 3:50 PM
    */
    function index()
    {
     
        $data['userdata'] = $this->session->userdata('current_user');
        $data['menu']     = $this->menu;
        $submenu          = $this->submenu;

        if($data['userdata'][0]->role_id == $this->admin_Role || $data['userdata'][0]->role_id == $this->sales_Role){
          $resId            = $this->getRestaurantForRoleBaseAccess();

          foreach($submenu as $key=>$value)
          {
          $submenuArray[$value->parent_page_id][] = $value;
          }
          $data['submenu']            = $submenuArray;

          $totalRow                   = $this->Rating_model->getAllrestaurantRatingCount($resId);
          $config["base_url"]         = site_url('Rating/index');
          $config["total_rows"]       = $totalRow;
          $config["per_page"]         = 10;
          $config['use_page_numbers'] = TRUE;
          $config['num_links']        = 5;
          $config['cur_tag_open']     = '&nbsp;<a class="active">';
          $config['cur_tag_close']    = '</a>';
          $config['next_link']        = 'Next';
          $config['prev_link']        = 'Previous';
          $this->pagination->initialize($config);

          if($this->uri->segment(3)){
          $page = ($this->uri->segment(3)) ;
          }
          else{
          $page = 1;
          }

          $offset     = ($page - 1) * $config["per_page"];
          $rating     = $this->Rating_model->getAllRestaurantRating($resId,$config["per_page"], $offset,$search=null);

          $data['rating']         = $rating;   
          $data['offset']         = $offset + 1;  
          $str_links              = $this->pagination->create_links();
          $data["links"]          = explode('&nbsp;',$str_links );
          $data["restoruntData"]  = $this->Rating_model->getAllRestaurantAvelibility($resId);
          $this->load->view('Elements/header',$data);
          $this->load->view('Rating/restaurants_rating');
          $this->load->view('Elements/footer');
        }else{
          redirect('Rating/getDriverRating');
        }
        
    }

    /**
     * deafult function call when create new customer
     * @author Vaibhav Mehta
     * Created date: 01-12-2017 12:15 PM
     */
    function getDriverRating()
    {
        $data['userdata'] = $this->session->userdata('current_user');
        $data['menu']     = $this->menu;
        $submenu          = $this->submenu;

        foreach($submenu as $key=>$value)
        {
            $submenuArray[$value->parent_page_id][] = $value;
        }
        $data['submenu']        = $submenuArray;
        $resId  = $this->getRestaurantForRoleBaseAccess();
        //echo $resId;exit;
        $totalRow                   = $this->Rating_model->getAllDriverDataCount($resId);
        $config["base_url"]         = site_url('Rating/getDriverRating');
        $config["total_rows"]       = $totalRow;
        $config["per_page"]         = 10;
        $config['use_page_numbers'] = TRUE;
        $config['num_links']        = 5;
        $config['cur_tag_open']     = '&nbsp;<a class="active">';
        $config['cur_tag_close']    = '</a>';
        $config['next_link']        = 'Next';
        $config['prev_link']        = 'Previous';
        $this->pagination->initialize($config);

        if($this->uri->segment(3)){
        $page = ($this->uri->segment(3)) ;
        }
        else{
        $page = 1;
        }

        $offset=($page - 1) * $config["per_page"];
        $rating = $this->Rating_model->getAllDriverData($resId,$config["per_page"], $offset,$search=null);

        $data['rating']  = $rating;   
        $data['offset'] = $offset + 1;  
        $str_links = $this->pagination->create_links();
        $data["links"] = explode('&nbsp;',$str_links );
        $this->load->view('Elements/header',$data);
        $this->load->view('Rating/drivers_rating');
        $this->load->view('Elements/footer');
    }

    function DriverRatingById($id)
    {
        $data['userdata'] = $this->session->userdata('current_user');
        $data['menu']     = $this->menu;
        $submenu        = $this->submenu;
        $submenuArray     = array();

        foreach($submenu as $key=>$value){
          $submenuArray[$value->parent_page_id][] = $value;
        }
        $data['submenu']    = $submenuArray;
      
        $totalRow                   = $this->Rating_model->getDriverRatingByIdCount($id);
        //$config                     = array();
        $config["base_url"]         = site_url('Rating/DriverRatingById/'.$id.'/');
        $config["total_rows"]       = $totalRow;
        $config["per_page"]         = 4;
        $config['use_page_numbers'] = TRUE;
        $config['cur_tag_open']     = '&nbsp;<a class="active">';
        $config['cur_tag_close']    = '</a>';
        $config['next_link']        = 'Next';
        $config['prev_link']        = 'Previous';
        $this->pagination->initialize($config);

        if($this->uri->segment(4)){
          $page = ($this->uri->segment(4)) ;
        }
        else{
          $page = 1;
        }
        $offset         = ($page - 1) * $config["per_page"];
        $data['driverRatingById'] = $this->Rating_model->getDriverRatingById($id,$config["per_page"], $offset);
      $data['driverName'] = $this->Rating_model->getDriverName($id);
      $data['offset'] = $offset +1; 

      $str_links = $this->pagination->create_links();
      $data["links"] = explode('&nbsp;',$str_links ); 

      //echo "<pre>"; print_r($data['driverRatingById']); exit;
      $this->load->view('Elements/header',$data);
      $this->load->view('Rating/driver_rating_order_wise');
      $this->load->view('Elements/footer');
    }

    /**
     * deafult function call when create new customer
     * @author Vaibhav Mehta
     * Created date: 01-12-2017 12:15 PM
     */
    function RestaurantsRatingUserWise($id)
    { 
      $data['userdata'] = $this->session->userdata('current_user');
      $data['menu']     = $this->menu;
      $submenu        = $this->submenu;
      $submenuArray     = array();

      foreach($submenu as $key=>$value)
      {
        $submenuArray[$value->parent_page_id][] = $value;
      }
      $data['submenu']        = $submenuArray;
      $totalRow           = $this->Rating_model->RestaurantsRatingUserWiseCount($id);
      $config           = array();
      $config["base_url"]     = site_url('Rating/RestaurantsRatingUserWise/'.$id);
      $config["total_rows"]     = $totalRow;
      $config["per_page"]     = 10;
      $config['use_page_numbers'] = TRUE;
      $config['num_links']    = 5;
      $config["uri_segment"] = 4;
      $config['cur_tag_open']   = '&nbsp;<a class="active">';
      $config['cur_tag_close']  = '</a>';
      $config['next_link']    = 'Next';
      $config['prev_link']    = 'Previous';
      //echo "<pre>";print_r($config);exit();
      $this->pagination->initialize($config);
      if($this->uri->segment(4)){
        $page = ($this->uri->segment(4)) ;
      }
      else{
        $page = 1;
      }

      $offset=($page - 1) * $config["per_page"];
      $data['Restaurantsrating'] = $this->Rating_model->RestaurantsRatingUserWise($id,$config["per_page"],$offset);
      $data['RestaurantName'] = $this->Rating_model->getRestaurantName($id);
      $data['offset'] = $offset + 1;
      $str_links = $this->pagination->create_links();
      $data["links"] = explode('&nbsp;',$str_links );
      //echo "<pre>"; print_r($data['RestaurantName']); exit;
      $this->load->view('Elements/header',$data);
      $this->load->view('Rating/Restaurants_rating_user_wise');
      $this->load->view('Elements/footer');
    }

    /**
     * deafult function call when create new customer
     * @author Vaibhav Mehta
     * Created date: 01-12-2017 12:15 PM
     */


}