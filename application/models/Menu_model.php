<?php
	/**
	 * Model Name: Menu_model
	 * Descripation: Use to manage the role based menu
	 * @author Vaibhav mehta
	 * Created date: 05 April 2017
	 */
class Menu_model extends CI_Model
{
	/**
	 * function to build query to get All parent menus
	 * @author Vaibhav Mehta
	 * Created date: 05 April 2017
	 */
	function getMenu($rid)
	{
		$this->db->from('qc_role_page');
		$this->db->join('qc_pages','qc_pages.page_id=qc_role_page.page_id');
		$this->db->where('qc_pages.is_active','1');
		$this->db->where('qc_pages.is_menu','1');
		$this->db->where('qc_pages.parent_page_id','0');
		$this->db->where('qc_role_page.role_id',$rid);
		$this->db->where('qc_pages.page_order !=','0');
		$this->db->group_by('qc_pages.page_id');
		$this->db->order_by('qc_pages.page_order','asc');
		$query=$this->db->get();
		return $query->result();
	}

	/**
	 * function to build query to get All sub menus based on page url
	 * @author Vaibhav Mehta
	 * Created date: 05 April 2017
	 */
	function getSubMenu($rid)
	{
		//$subQry = "parent_page_id = (SELECT page_id FROM qc_pages WHERE is_active=1 and is_menu=1 and parent_page_id=0)";
		/*$this->db->select('qc_pages.page_id,parent_page_id,page_title,page_icon,page_url,page_slug,page_order');*/
		$this->db->select('qc_pages.*,qc_role_page.*');
		$this->db->from('qc_pages');
		$this->db->join('qc_role_page','qc_pages.page_id=qc_role_page.page_id AND qc_role_page.role_id="'.$rid.'"','left');
		$this->db->where('qc_pages.is_menu','1');
		$this->db->where('qc_pages.is_active','1');
		$this->db->where('qc_pages.parent_page_id !=','0');
		$this->db->where('qc_pages.page_order !=','0');
		$this->db->where('qc_role_page.role_id',$rid);
		$this->db->order_by('page_order','asc');
		$query=$this->db->get();
		//echo $this->db->last_query();exit;
		return $query->result();
	}

	/**
	 * function to build query to check page url is accessible to user or not
	 * @author Vaibhav Mehta
	 * Created date: 24/07/2017 03:30 PM
	 */
	function checkUrlAccess($segment)
	{
		$this->db->select('page_id');
		$this->db->where('page_url',$segment);
		$query = $this->db->get('qc_pages');
		return $query->result();
	}
}