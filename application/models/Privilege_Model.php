<?php
class Privilege_Model extends CI_Model {

	
	public function __construct(){
	    parent::__construct();
    }
	public function get_privileges_list(){
   		return $this->db->get('privileges');
	}
	public function add_privilege($priv_post){
		$this->db->insert('subadmin_privileges',$priv_post);
	}

	public function get_allowed_pages($sub_admin_id,$controller_name,$function_name){ 
		
		$allowed_pages = array('settings_payout_migrate','game_orders','game_change_ticket_type','game_request_event','game_report_issue','kyc_view_documents','kyc_upload_document','kyc_upload_contract','kyc_documents','tickets_get_bulk_events','tickets_bulk_tickets','tickets_bulk_upload_tickets','home_update_block','game_download_orders','game_update_statdium_block','home_save_my_accounts','game_getStadiumByid','game_uploadEticket','game_deleteEticket','game_uploadNominee','game_download_tickets','game_saveNominee','game_saveEticket','home_index','home_master','game_category','tickets_get_tktcat_by_stadium_id','tickets_getCurrency_event','tickets_getMatchDetails','tickets_get_block_by_stadium_id','tickets_create_ticket','chats','payout');
		
		if(in_array($controller_name.'_'.$function_name,$allowed_pages)){
		
			return true;	
		}
		else
		{
			return false;	
		}
	}
	

	public function get_privileges_by_sub_admin_id($sub_admin_id,$controller_name,$function_name){ 
		$this->db->where('admin_role_details.admin_id',$sub_admin_id);
		$this->db->where('privilege_functions.controller_name',$controller_name);
		$this->db->where('privilege_functions.function_name',$function_name);
		$this->db->join('privilege_admin_roles', 'privilege_admin_roles.admin_role_id = admin_role_details.admin_roles_id','right');
		$this->db->join('privilege_actions', 'privilege_actions.privilege_id = privilege_admin_roles.privilege_id','right');
			$this->db->join('privilege_functions', 'privilege_functions.privilege_functions_id = privilege_actions.privilege_functions_id','left');
	 
		
		$query = $this->db->get('admin_role_details');
		
	//echo $this->db->last_query();exit;
		if ( $query->num_rows() > 0 ) 
		{
			return true;	
		}
		else
		{
			return false;	
		}
	}
	public function get_modules_by_sub_admin_id($sub_admin){
		$this->db->select('privileges.controller');
		$this->db->where('subadmin_id',$sub_admin);
		$this->db->join('privileges', 'privileges.id = subadmin_privileges.privilege_id');
		return $this->db->get('subadmin_privileges');
	}
	public function get_modules_by_sub_admin($sub_admin){
		$this->db->select('privileges.id');
		$this->db->where('subadmin_id',$sub_admin);
		$this->db->join('privileges', 'privileges.id = subadmin_privileges.privilege_id');
		return $this->db->get('subadmin_privileges');
	}
	public function delete_privileges($sub_admin){
		$this->db->where('subadmin_id',$sub_admin);
		$this->db->delete('subadmin_privileges');
	}

	public function get_module_privileges_list(){
   		return $this->db->get('product');
	}
	public function get_modules_by_agent($agent){
		$this->db->select('product.product_id');
		$this->db->where('b2b_id',$agent);
		$this->db->join('product', 'product.product_id = b2b_privileges.product_id');
		return $this->db->get('b2b_privileges');
	}
	public function delete($agent){
		$this->db->where('b2b_id',$agent);
		$this->db->delete('b2b_privileges');
	}
	public function add($priv_post){
		$this->db->insert('b2b_privileges',$priv_post);
	}
	public function get_modules_by_b2b_id($agent){
		$this->db->select('product.controller');
		$this->db->where('b2b_id',$agent);
		$this->db->join('product', 'product.product_id = b2b_privileges.product_id');
		return $this->db->get('b2b_privileges');
	}
  				
}
