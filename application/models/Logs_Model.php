<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Logs_Model extends CI_Model
{
	
	function get_logs($row_per_page="", $row_no="",$search="",$request_type="")
	{
		$this->db->select('api_seller_logs.*');
		$this->db->from('api_seller_logs');
		//$this->db->join('admin_details', 'admin_details.admin_id = api_key_settings.seller_id','LEFT');
		$this->db->order_by('api_seller_logs.id', 'DESC');
		$this->db->where('request_type',$request_type);
		$this->db->where('seller_id',$this->session->userdata('admin_id'));
		if($search){
			$this->db->group_start();
			$this->db->like("api_seller_logs.request_type",$search);
			$this->db->or_like("api_seller_logs.request_filename",$search);
			$this->db->or_like("api_seller_logs.response_filename",$search);
			$this->db->or_like("api_seller_logs.created_at",$search);
			$this->db->group_end();
		}
		if ($row_per_page != '' && $row_no >= 0) {
			$this->db->limit($row_no, $row_per_page);
		}
		$qry = $this->db->get();
		return $qry;
	}

	function get_logs_group()
	{
		$this->db->select('api_seller_logs.*');
		$this->db->from('api_seller_logs');
		//$this->db->join('admin_details', 'admin_details.admin_id = api_key_settings.seller_id','LEFT');
		$this->db->where('seller_id',$this->session->userdata('admin_id'));
		$this->db->order_by('api_seller_logs.id', 'DESC');
		$this->db->group_by('request_type');
		$qry = $this->db->get();
		return $qry;
	}


	function get_clicks($row_per_page="", $row_no="",$search="",$request_type="")
	{
		$this->db->select('api_checkout_tracker.*,ml.match_name,m.match_date');
		$this->db->from('api_checkout_tracker');
		//$this->db->join('admin_details', 'admin_details.admin_id = api_key_settings.seller_id','LEFT');
		$this->db->order_by('api_checkout_tracker.id', 'DESC');
		$this->db->where('api_checkout_tracker.seller_id',$this->session->userdata('admin_id'));

		$this->db->join('match_info m', 'm.m_id = api_checkout_tracker.match_id','LEFT');
		$this->db->join('match_info_lang ml', 'ml.match_id = m.m_id','LEFT');
		$this->db->where('ml.language','en');
		if ($row_per_page != '' && $row_no >= 0) {
			$this->db->limit($row_no, $row_per_page);
		}
		$qry = $this->db->get();
		return $qry;
	}

}
?>