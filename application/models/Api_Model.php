<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Api_Model extends CI_Model
{
	function get_api_details($status="", $row_per_page="", $row_no="", $search="")
	{
		$this->db->select('api_partner_events.*, api_partner_events.id as partner_id, match_info.match_name as event_name, match_info.match_date as event_date');
		$this->db->from('api_partner_events');
		$this->db->where('api_status',$status);
		
		$this->db->join('admin_details', 'admin_details.admin_id = api_partner_events.partner_id');
		$this->db->join('tournament', 'tournament.t_id = api_partner_events.tournament_id');
		$this->db->join('match_info', 'match_info.m_id = api_partner_events.event_id');

		$this->db->join('game_category', 'game_category.id = api_partner_events.category_id');
		
		$this->db->order_by('api_partner_events.id', 'DESC');
		if ($row_per_page != '' && $row_no >= 0) {
			$this->db->limit($row_per_page, $row_no);
		}
		if ($search != '') {
			$this->db->like('match_info.match_name', $search);
		}
		$qry = $this->db->get();
		return $qry;
		// if ($qry->num_rows() > 0) {
		// 	return $qry->result();
		// } else {
		// 	return array();
		// }
	}

	function api_key_settings($row_per_page="", $row_no="")
	{
		$this->db->select('api_key_settings.*,admin_details.admin_name, admin_details.admin_email, admin_details.company_name, admin_details.admin_last_name');
		$this->db->from('api_key_settings');
		$this->db->join('admin_details', 'admin_details.admin_id = api_key_settings.partner_id','LEFT');
		$this->db->order_by('api_key_settings.id', 'DESC');
		if ($row_per_page != '' && $row_no >= 0) {
			$this->db->limit($row_per_page, $row_no);
		}
		$qry = $this->db->get();
		return $qry;
	}

	function api_ip_patching($row_per_page="", $row_no="")
	{
		$this->db->select('api_ip_patching.*,admin_details.admin_name, admin_details.admin_email, admin_details.company_name, admin_details.admin_last_name');
		$this->db->from('api_ip_patching');
		$this->db->join('admin_details', 'admin_details.admin_id = api_ip_patching.partner_id','LEFT');
		$this->db->order_by('api_ip_patching.id', 'DESC');
		if ($row_per_page != '' && $row_no >= 0) {
			$this->db->limit($row_per_page, $row_no);
		}
		$qry = $this->db->get();
		return $qry;
	}

	public function get_event_by_id($id="")
	{

		$this->db->select('match_info.*,match_info.status as match_status,match_info_lang.*, DATE_FORMAT(match_info.match_date, "%d %M %Y") as match_date_formated')->from('match_info')->join('match_info_lang', 'match_info_lang.match_id = match_info.m_id', 'left');
		$this->db->where('match_info.event_type', 'match');
		if(!empty($id)){
			$this->db->where('match_info.tournament', $id);
		}
		$this->db->where("match_date >= NOW()");
		$this->db->where('match_info_lang.language', $this->session->userdata('language_code'));
		$this->db->order_by('match_info.match_date', 'ASC');

		$query = $this->db->get();
		return $query;
	}

}
?>

