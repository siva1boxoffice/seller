<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Accounts_Model extends CI_Model {
  
  
  function get_unpaid_orders_v1($where = array())
	{ 
		//echo "<pre>";print_r($where);exit;
		$this->db->select('*');
		if($where){
			$this->db->where_in('bg_id',$where);
		}
		$this->db->where('payout_status','0');
		$this->db->where('booking_status',1);
		$query = $this->db->get('booking_global');
		//echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return '';
		}
	}



function bank_accounts($currency='',$account_id='')
	{ 

		/*$this->db->select('*');
		$this->db->where('admin_id',$this->session->userdata('admin_id'));
		if($currency != ""){
			$this->db->where('currency',$currency);
		}
		if($account_id != ""){
			$this->db->where('bank_id ',$account_id);
		}
		$query = $this->db->get('admin_bank_details');
		//echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return '';
		}*/

		$this->db->select('admin_bank_details.*,countries.name as country_name');
		$this->db->from('admin_bank_details');
		$this->db->join('countries', 'countries.id = admin_bank_details.country','LEFT');
		$this->db->where('admin_bank_details.admin_id',$this->session->userdata('admin_id'));
		if($currency != ""){
			$this->db->where('admin_bank_details.currency',$currency);
		}
		if($account_id != ""){
			$this->db->where('admin_bank_details.bank_id',$account_id);
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return '';
		}

	}

 function admin_payout_pending()
	{ 
		
		$this->db->select('*');
		$this->db->join('admin_details', 'admin_details.admin_id = booking_global.seller_id');
		$this->db->where('booking_global.payout_status','0');
		$this->db->where('booking_global.booking_status',1);
		if ($this->session->userdata('role') == 1) {
			$this->db->where('booking_global.seller_id', $this->session->userdata('admin_id'));
		}
		$query = $this->db->get('booking_global');
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return '';
		}
	}

  function admin_payout_histories($currency='',$payout_id='',$txnid='',$fromdate='',$todate='')
	{ 
		
		$this->db->select('*,payouts.currency as payout_currency,admin_bank_details.*');
		$this->db->join('admin_details', 'admin_details.admin_id = payouts.seller_id');
		$this->db->join('admin_bank_details', 'admin_bank_details.bank_id = payouts.paid_account','LEFT');
		if ($this->session->userdata('role') == 1) {
			$this->db->where('payouts.seller_id', $this->session->userdata('admin_id'));
		}
		if($currency != ''){
			$this->db->where('payouts.currency', $currency);
		}
		if($payout_id != ''){
			//$this->db->where('payouts.payout_no', $payout_id);
			$this->db->like('payouts.payout_no', $payout_id);
		}
		if($txnid != ''){
			$this->db->where('payouts.payout_id', $txnid);
		}
		if($fromdate != ''){
			$this->db->where('payouts.paid_date_time >= ', date("Y-m-d H:i",strtotime($fromdate)));
		}
		if($todate != ''){
			$this->db->where('payouts.paid_date_time <= ', date("Y-m-d H:i",strtotime($todate)));
		}
		$this->db->order_by('payouts.payout_date_from','ASC');
		$query = $this->db->get('payouts');//echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return '';
		}
	}

	function admin_payout_orders_payable($currency,$payable_type)
	{ 
		
		$this->db->select('SUM(booking_global.ticket_amount) as total_ticket_amount');
		$this->db->join('payouts', 'payouts.payout_id = booking_global.payout_id','LEFT');	
		$this->db->join('booking_tickets', 'booking_tickets.booking_id = booking_global.bg_id','LEFT');
		$this->db->join('admin_bank_details', 'admin_bank_details.bank_id = payouts.paid_account','LEFT');
		$this->db->where('booking_global.seller_id', $this->session->userdata('admin_id'));
		$this->db->where('booking_global.currency_type',$currency);
		if($payable_type == 'pending_delivery'){
			$this->db->where('booking_global.payout_status','0');
			$this->db->where('booking_global.booking_status',1);
		}
		if($payable_type == 'pending_payout'){
			
			$this->db->where_in('booking_global.booking_status',[4,5,6]);
			$this->db->where('booking_global.payout_status != ','2');
			$this->db->where('booking_global.payout_status != ','1');

		}
		//$this->db->order_by('payouts.payout_date_from','ASC');
		$query = $this->db->get('booking_global');//echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return '';
		}

	}

	function onhold_prices($currency)
	{ 
		
		$this->db->select('SUM(booking_global.on_hold) as total_hold_amount');
		$this->db->where('booking_global.seller_id', $this->session->userdata('admin_id'));
		$this->db->where('booking_global.booking_status != ',2);
		$this->db->where('booking_global.booking_status != ',3);
		$this->db->where('booking_global.booking_status != ',0);
		$this->db->where('booking_global.booking_status != ',7);
		$this->db->where('booking_global.payout_status','2');
		$this->db->where('booking_global.currency_type',$currency);
		$query = $this->db->get('booking_global');
		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return '';
		}

	}

	function admin_payout_orders($payment_reference="",$status = "")
	{ 
		
		$this->db->select('booking_global.*,payouts.*,payouts.currency as payout_currency,admin_bank_details.*,booking_tickets.*,booking_global.booking_no,booking_tickets.match_date as event_date,booking_global.ticket_amount,booking_global.currency_type');
		$this->db->join('payouts', 'payouts.payout_id = booking_global.payout_id','LEFT');	
		$this->db->join('booking_tickets', 'booking_tickets.booking_id = booking_global.bg_id','LEFT');
		$this->db->join('admin_bank_details', 'admin_bank_details.bank_id = payouts.paid_account','LEFT');
		$this->db->where('booking_global.seller_id', $this->session->userdata('admin_id'));
		$this->db->where('booking_global.booking_status != ',2);
		$this->db->where('booking_global.booking_status != ',3);
		$this->db->where('booking_global.booking_status != ',0);
		$this->db->where('booking_global.booking_status != ',7);
		if($payment_reference != ""){
			$this->db->like('payouts.payout_no',$payment_reference);
			$this->db->or_like('booking_global.booking_no',$payment_reference);
		}
		if($status == 1){
			$this->db->where('booking_global.payout_status','1');
		}
		if($status == 2){
			$this->db->where('booking_global.payout_status','2');
		}
		if($status == 0){
			$this->db->where('booking_global.payout_status','0');
		}
		//$this->db->order_by('payouts.payout_date_from','ASC');
		$query = $this->db->get('booking_global');//echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return '';
		}
		/*$this->db->select('booking_global.*,booking_tickets.*,booking_global.booking_no,booking_tickets.match_date as event_date,booking_global.ticket_amount,booking_global.currency_type');
		$this->db->join('booking_tickets', 'booking_tickets.booking_id = booking_global.bg_id','LEFT');
		$this->db->where('booking_global.seller_id', $this->session->userdata('admin_id'));
		$this->db->where('booking_global.booking_status != ',2);
		$this->db->where('booking_global.booking_status != ',3);
		$this->db->where('booking_global.booking_status != ',0);
		$this->db->where('booking_global.booking_status != ',7);
		//$this->db->order_by('payouts.payout_date_from','ASC');
		$query = $this->db->get('booking_global');//echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return '';
		}*/

	}

	function booking_data($bg_id){
		$this->db->select('booking_tickets.match_name,booking_tickets.match_date,booking_tickets.stadium_name,booking_global.booking_no,booking_global.ticket_amount,booking_global.currency_type');
		$this->db->join('booking_global', 'booking_global.bg_id = booking_tickets.booking_id');
		if($bg_id != ''){
			$this->db->where('booking_tickets.booking_id', $bg_id);
		}
		$query = $this->db->get('booking_tickets');
		if ($query->num_rows() >= 0) {
			return $query->row();
		} else {
			return '';
		}
	}

  function get_unpaid_orders($where = array())
	{ //echo "<pre>";print_r($where);exit;
		$this->db->select('*');
		if($where['seller_id'] != ''){
			$this->db->where('seller_id',$where['seller_id']);
		}
		if($where['order_from'] != ''){
			$this->db->where('updated_at >= ', date("Y-m-d H:i",strtotime($where['order_from'])));
		}
		if($where['order_to'] != ''){
			$this->db->where('updated_at <= ', date("Y-m-d H:i",strtotime($where['order_to'])));
		} 
		if($where['bg_id'] != ''){
			$this->db->where('bg_id', $where['bg_id']);
		} 
		$this->db->where('payout_status','0');
		$this->db->where('booking_status',1);
		$query = $this->db->get('booking_global');
		//echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return '';
		}
	}

}
?>
