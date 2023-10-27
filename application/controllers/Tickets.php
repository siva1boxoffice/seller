<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(E_ALL);
class Tickets extends CI_Controller
{
	public function __construct()
	{
		/*
         *  Developed by: Sivakumar G
         *  Date    : 22 January, 2022
         *  1BoxOffice Hub
         *  https://www.1boxoffice.com/
        */
		parent::__construct();
		$this->load->model('Tickets_Model');
		$this->check_isvalidated();
		$this->app_name = $this->General_Model->get_type_name_by_id('general_settings', '1', 'settings_value');
		$this->app_login_image = $this->General_Model->get_type_name_by_id('general_settings', '13', 'settings_value');
		$this->app_title = $this->General_Model->get_type_name_by_id('general_settings', '2', 'settings_value');
		$this->general_path = $this->General_Model->get_type_name_by_id('general_settings', '16', 'settings_value');
		$this->app_favicon = $this->General_Model->get_type_name_by_id('general_settings', '15', 'settings_value');
		$this->login_image = $this->General_Model->get_type_name_by_id('general_settings', '13', 'settings_value');
		$this->logo = $this->General_Model->get_type_name_by_id('general_settings', '17', 'settings_value');
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->data = array();
		$this->data['app'] = $this->app_data();
	}	
	private function check_isvalidated()
	{
		if (!$this->session->userdata('admin_logged_in') || $this->session->userdata('admin_id') != ADMIN_ID) {
			if ($this->session->userdata('admin_logged_in')) {
				$controller_name = $this->router->fetch_class();
				$function_name = $this->router->fetch_method();
				$this->load->model('Privilege_Model');
				$sub_admin_id = $this->session->userdata('admin_id');
				//echo $sub_admin_id;exit;
				if (!$this->Privilege_Model->get_allowed_pages($sub_admin_id, $controller_name, $function_name) && !$this->Privilege_Model->get_privileges_by_sub_admin_id($sub_admin_id, $controller_name, $function_name)) {
					redirect(base_url() . 'access/error_denied', 'refresh');
				}
			} else {
				redirect(base_url(), 'refresh');
			}
		}
		else{
			redirect(base_url() . 'access/error_denied', 'refresh');
		}
	}

	public function app_data()
	{

		$this->data['app_name'] = $this->app_name;
		$this->data['app_login_image'] = $this->app_login_image;
		$this->data['app_title'] = $this->app_title;
		$this->data['general_path'] = $this->general_path;
		$this->data['app_favicon'] = $this->app_favicon;
		$this->data['login_image'] = $this->login_image;
		$this->data['logo'] = $this->logo;
		$this->data['languages'] = $this->General_Model->getAllItemTable('language','store_id',$this->session->userdata('storefront')->admin_id)->result();
		$this->data['branches'] = $this->General_Model->get_admin_details_by_role(4);
		if ($this->session->userdata('storefront')->company_name == '') {
				$branches = $this->General_Model->get_admin_details(13);
				$sessionUserInfo = array('storefront' => $branches);
				$this->session->set_userdata($sessionUserInfo);
			/*$sessionUserInfo = array('storefront' => $this->data['branches'][count($this->data['branches']) - 1]);*/
		}
		return $this->data;
	}

	
	public function bulk_tickets(){
		$this->datas['tournaments'] = $this->General_Model->get_tournments()->result();
		$this->load->view(THEME_NAME.'/tickets/bulk_listing', $this->datas);	
	}

	public function bulk_upload_tickets(){
		
	if ($this->input->post('submit')) { 

	$path = 'uploads/bulktickets/';
	require_once APPPATH . "third_party/PHPExcel.php";
	//echo "<pre>";print_r($_FILES);exit;
	$config['upload_path'] = $path;
	$config['allowed_types'] = 'xlsx|xls|csv';
	$config['remove_spaces'] = TRUE;
	$this->load->library('upload', $config);
	$this->upload->initialize($config);            
	if (!$this->upload->do_upload('uploadFile')) {
	$error = array('error' => $this->upload->display_errors());
	} else {
	$data = array('upload_data' => $this->upload->data());
	}
	if(empty($error)){
	if (!empty($data['upload_data']['file_name'])) {
	$import_xls_file = $data['upload_data']['file_name'];
	} else {
	$import_xls_file = 0;
	}
	$inputFileName = $path . $import_xls_file;
	try {
	$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
	$objReader = PHPExcel_IOFactory::createReader($inputFileType);
	$objPHPExcel = $objReader->load($inputFileName);
	$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
	$flag = true;
	$i=0;
	foreach ($allDataInSheet as $value) {
	if($flag){
	$flag =false;
	continue;
	} //echo "<pre>";print_r($value);exit;
	if($value['B'] != "" && $value['D'] != "" && $value['F'] != ""  && $value['H'] != "" && $value['I'] != "" && $value['L'] != ""){



	$match_currency = $this->General_Model->get_match_info($value['B']); 

	if(!empty($match_currency[0])){

	$ticketid = mt_rand(1000, 9999) . '_' . mt_rand(100000, 999999);
	$ticket_group_id = mt_rand(100000, 999999);

	$inserdata[$i]['ticketid'] = $ticketid;
	$inserdata[$i]['ticket_group_id'] = $ticket_group_id;
	$inserdata[$i]['user_id'] = $this->session->userdata('admin_id');
	//$inserdata[$i]['match_name'] = $value['A'];
	$inserdata[$i]['match_id'] = $value['B'];
	//$inserdata[$i]['ticket_type_name'] = $value['C'];
	$inserdata[$i]['ticket_type'] = $value['D'];
	//$inserdata[$i]['ticket_category_name'] = $value['E'];
	$inserdata[$i]['ticket_category'] = $value['F'];
	//$inserdata[$i]['split_name'] = $value['G'];
	$inserdata[$i]['split'] = $value['H'];
	$inserdata[$i]['quantity'] = $value['I'];
	//$inserdata[$i]['currency_name'] = $value['J'];
	$inserdata[$i]['price_type'] = $match_currency[0]['price_type'];
	$inserdata[$i]['price'] = $value['L'];
	//$inserdata[$i]['seller_notes'] = array($value['M'],$value['O']);
	$inserdata[$i]['listing_note'] = implode(',',array($value['N'],$value['P']));
	$inserdata[$i]['sell_type'] = 'buy';
	$inserdata[$i]['track'] = '0';
	$inserdata[$i]['ready_to_ship'] = 0;
	$inserdata[$i]['event_flag'] = 'E';
	$inserdata[$i]['sell_date'] = date('Y-m-d H:i:s');
	$inserdata[$i]['add_by'] = $this->session->userdata('admin_id');
	$inserdata[$i]['store_id'] = $this->session->userdata('storefront')->admin_id;
	$i++;
	}
	else{
		$error = array('status' => 0,'msg' => 'Invalid Match.Please enter the valid data.','redirect_url' => base_url() . 'tickets/index/upload_tickets');
		echo json_encode($error);exit;
	}

	}

	}  // echo "<pre>";print_r($inserdata);exit;
	$result = $this->General_Model->insert_batch_data('sell_tickets',$inserdata);   
	if($result){
	$error = array('status' => 1,'msg' => 'Success.Tickets uploaded Successfully.','redirect_url' => base_url() . 'tickets/index/listing');
		echo json_encode($error);exit;
	}else{
	$error = array('status' => 0,'msg' => 'Oops.Error while uploading tickets.','redirect_url' => base_url() . 'tickets/index/upload_tickets');
		echo json_encode($error);exit;
	}             
	} catch (Exception $e) {

		$error = array('status' => 0,'msg' => 'Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
	. '": ' .$e->getMessage(),'redirect_url' => base_url() . 'tickets/index/upload_tickets');
		echo json_encode($error);exit;
	}
	}else{
	$error = array('status' => 0,'msg' => $error['error'],'redirect_url' => base_url() . 'tickets/index/upload_tickets');
		echo json_encode($error);exit;

	}
	}
}

	
	public function get_bulk_events()
	{
		if($_POST['flag'] == 'save'){
				
			if(!empty($_POST['match_ids'])){
				$match_ids = $_POST['match_ids'];
				$ticket_data = array();

				$stadium_ids = array();
				$match_prices = array();
				$tournaments = array();

				$check_match_info = $this->General_Model->getMatchAdditionalInfo($match_ids[0]);

				$check_exists = $this->General_Model->getAllItemTable_array('seller_whish_list', array("seller_id" => $this->session->userdata('admin_id'),"status" => 0))->row();
				if($check_exists->match_id != ""){
					$check_match_info_v1 = $this->General_Model->getMatchAdditionalInfo($check_exists->match_id);
					if($check_match_info->team_1 != $check_match_info_v1->team_1){
						$matches = explode('vs',strtolower($check_match_info_v1->match_name));
						$response = array('status' => 0,'msg' => "Oops.Unable to add two different team events in same list.You can add only ".$matches[0].' matches');
						echo json_encode($response);exit;
					}
					
				}
				
				/*if(($check_match_info->tournament != $check_match_info_v1->tournament) && $check_match_info->tournament != $check_match_info_v1->tournament){
					$delete = $this->General_Model->delete('seller_whish_list',array("seller_id" => $this->session->userdata('admin_id'),'status' => 0));
				}*/
				

				foreach($match_ids as $match_id){

				$exists = $this->General_Model->getAllItemTable_array('seller_whish_list', array('match_id' => $match_id,"seller_id" => $this->session->userdata('admin_id'),'status' => 0))->row();

				//$tickets_exists = $this->General_Model->getAllItemTable_array('sell_tickets', array('match_id' => $match_id,"user_id" => $this->session->userdata('admin_id'),"status" => 1))->row();

				$match_info = $this->General_Model->getMatchAdditionalInfo($match_id);
				$stadium_ids[]  = $match_info->venue;
				$match_prices[] = $match_info->price_type;
				$tournaments[] = $match_info->tournament;

				if($exists->id == ''){

					$ticket_data[] = array(
					'match_id' => $match_id,
					'seller_id' => $this->session->userdata('admin_id'),
					'status'    => 0
					);
				}
			}

				$stadium_duplicate 		 = array_unique($stadium_ids);
				$price_duplicate   		 = array_unique($match_prices); 
				$tournaments_duplicate   = array_unique($tournaments); 
				//echo "<pre>";print_r($stadium_duplicate);exit;
				if(count($tournaments_duplicate) > 1){
					$response = array('status' => 0,'msg' => "Oops.You can add only same home team events.");
					echo json_encode($response);exit;
				}
				if(count($stadium_duplicate) > 1){
					$response = array('status' => 0,'msg' => "Oops.You can add only same home team events.");
					echo json_encode($response);exit;
				}
				if(count($price_duplicate) > 1){
					$response = array('status' => 0,'msg' => "Oops.You can add only same home team events.");
					echo json_encode($response);exit;
				}
				
				$ticket_create = $this->General_Model->insert_batch_data('seller_whish_list',$ticket_data);
				if($ticket_create == true){

					$redirect_url = base_url() . 'tickets/index/create_ticket/bulk';

					$response = array('status' => 1,'msg' => count($ticket_data)." Matches Successfully Added to bulk tickets upload section.",'redirect_url' => $redirect_url);
					echo json_encode($response);exit;
				}
				else{
					$response = array('status' => 0,'msg' => "Oops.Failed to Add Matches to bulk tickets upload section.");
					echo json_encode($response);exit;
				}

			}
		}
		else{

		//echo "<pre>";print_r($_POST);exit;

		$row_per_page = $_POST['limit'] ? $_POST['limit'] :  50;
		$rowno        =  $_POST['page'] ? $_POST['page'] :  1;
		if($rowno != 1){
			$rowno        = $rowno * $row_per_page;
		}
		
		$tournament  	   = $_POST['tournament'];
		$event_start_date  = $_POST['event_start_date'];
		$event_end_date    = $_POST['event_end_date'];
		$keywords          = $_POST['keywords'];
		$search_array      =  array('tournament' => $tournament,'event_start_date' => $event_start_date,'event_end_date' => $event_end_date,'keywords' => $keywords);
		$this->datas['list_events']       = $this->Tickets_Model->get_bulk_events($search_array,$rowno,$row_per_page);
		$this->datas['page'] = $_POST['page'];
		/*echo $this->db->last_query();
		echo "<pre>";print_r($this->datas['list_events']);exit;*/
		$list_tickets = $this->load->view(THEME_NAME.'/tickets/list_events_ajax', $this->datas, TRUE);
		$response = array('events' => $list_tickets,'count' => count($this->datas['list_events']));
		echo json_encode($response);exit;
		}
	}
	public function index()
	{


		$segment = $this->uri->segment(3);
		if ($segment == "upload_tickets") {
			$this->load->view(THEME_NAME.'/tickets/upload_tickets', $this->data);
		}	
		else if ($segment == "approve_reject") {

		  $segment4 = $this->uri->segment(4);
		  $this->data['status_flag'] = $segment4;
		  if($segment4 == "approve_reject"){
		  	$this->data['approve_request'] = $this->General_Model->ticket_approve_request('approve_reject')->result();
		  }
		  else{
		  	$this->data['approve_request'] = $this->General_Model->ticket_approve_request('pending')->result();
		  }
		  
		  //echo "<pre>";print_r($this->data['approve_request']);exit;
		  $this->load->view(THEME_NAME.'/tickets/approve_orders', $this->data);
		}
		else if ($segment == "get_ticket_search") {

			
			$this->data['ticket_types'] = $this->General_Model->get_ticket_type_data('', 'ACTIVE')->result();
			$this->data['split_types'] = $this->General_Model->get_split_type_data('', 'ACTIVE')->result();
			$this->data['ticket_details'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE')->result();
			//$allcount = $this->Tickets_Model->ticket_keyword_search();
			$allcount = 1000;//echo 'match_type = '.$_POST['match_type'];exit;
			$listings_e = $this->Tickets_Model->ticket_keyword_search($_POST['keyword'],$_POST['match_type']);//echo "<pre>";print_r($listings);exit;

			if($this->session->userdata('other_event') == '1'){

			$listings_oe = $this->Tickets_Model->ticket_keyword_search_oe($_POST['keyword'],$_POST['match_type']);
			}
			else{
			$listings_oe = array();	
			}
			//$listings = array_merge($listings_e,$listings_oe);
			$listings_data = array_merge($listings_e,$listings_oe);
			$columns = array_column($listings_data, 'match_date');
			array_multisort($columns, SORT_ASC, $listings_data);
			$listings = $listings_data;
			$mylisting = array();
			foreach ($listings as $tkey => $listing) {
				$tkt_category = $this->Tickets_Model->tkt_category($listing->venue);
				$category_data = array();
				$block_data = array();

				if ($get_std) {
					foreach ($get_std as $std) {
						$block_id = explode("-", $std->block_id);

						$block_data[$std->block_id] = end($block_id);
					}
				}

				if ($tkt_category) {

					foreach ($tkt_category as $key => $std) {
						$category_data[$std->category] = $std->seat_category;
					} 
					$listings[$tkey]->block_data = $block_data;
					$listings[$tkey]->tkt_category = $category_data;

					
				} //echo $listing->m_id.'='.$_POST['match_type'];exit;
				$listings[$tkey]->tickets = $this->Tickets_Model->getListing_filter($listing->m_id,$_POST['match_type']);
				$listings[$tkey]->pending_fullfillment = $this->General_Model->pending_fullfillment($listing->m_id)->num_rows();
				$soldticket = $this->General_Model->ticket_sold_quantity($listing->m_id)->row();
				$listings[$tkey]->sold_qty = $soldticket->total_ticket_sold;
				

			} 
			$this->data['search_type'] = "listing";
			$this->data['listings'] = $listings;
			$this->data['match_id'] = $match_id;
			$this->load->library('pagination');
			// Pagination Configuration
			$config['base_url'] = base_url() . 'tickets/index/load_tickets/';
			$config['use_page_numbers'] = TRUE;
			$config['total_rows'] = $allcount;
			$config['per_page'] = $rowperpage;
			$config['full_tag_open'] = '<ul class="pagination">';
			$config['full_tag_close'] = '</ul>';
			$config['first_link'] = false;
			$config['last_link'] = false;
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['prev_link'] = '&laquo';
			$config['prev_tag_open'] = '<li class="prev">';
			$config['prev_tag_close'] = '</li>';
			$config['next_link'] = '&raquo';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="active"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			// Initialize
			$this->pagination->initialize($config);
			$this->data['seller_id'] = $_POST['seller_id'];
			$this->data['last_ticket_id'] = $_POST['last_ticket_id'];
			
			// Initialize $data Array
			$this->data['pagination'] = '<div class="pagination datatable-pagination pagination-datatables flex-column" id="pagination">' . $this->pagination->create_links() . '</div>'; 
			
			$list_tickets = $this->load->view(THEME_NAME.'/tickets/list_tickets_ajax', $this->data, TRUE);//echo "<pre>";print_r($list_tickets);exit;
			/*if (empty($listings)) {
				$list_tickets = $this->load->view(THEME_NAME.'/errors/nofound', $this->data, TRUE);
			}*/

			$response = array('search_type' => 'listing', 'tickets' => $list_tickets);
			echo json_encode($response);

			

		}
		else if ($segment == "get_oe_ticket_search") {

			
			$this->data['ticket_types'] = $this->General_Model->get_ticket_type_data('', 'ACTIVE')->result();
			$this->data['split_types'] = $this->General_Model->get_split_type_data('', 'ACTIVE')->result();
			$this->data['ticket_details'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE')->result();
			//$allcount = $this->Tickets_Model->ticket_keyword_search();
			$allcount = 1000;//echo 'match_type = '.$_POST['match_type'];exit;
			$listings = $this->Tickets_Model->ticket_keyword_search_oe($_POST['keyword'],$_POST['match_type']);//echo "<pre>";print_r($listings);exit;
			$mylisting = array();
			foreach ($listings as $tkey => $listing) {
				$tkt_category = $this->Tickets_Model->tkt_category($listing->venue);
				$category_data = array();
				$block_data = array();

				if ($get_std) {
					foreach ($get_std as $std) {
						$block_id = explode("-", $std->block_id);

						$block_data[$std->block_id] = end($block_id);
					}
				}

				if ($tkt_category) {

					foreach ($tkt_category as $key => $std) {
						$category_data[$std->category] = $std->seat_category;
					} 
					$listings[$tkey]->block_data = $block_data;
					$listings[$tkey]->tkt_category = $category_data;

					
				} //echo $listing->m_id.'='.$_POST['match_type'];exit;
				$listings[$tkey]->tickets = $this->Tickets_Model->getListing_filter($listing->m_id,$_POST['match_type']);
				$listings[$tkey]->pending_fullfillment = $this->General_Model->pending_fullfillment($listing->m_id)->num_rows();
				$listings[$tkey]->sold_qty = $this->General_Model->ticket_sold_quantity($listing->m_id)->num_rows();
				

			} 
			$this->data['search_type'] = "listing";
			$this->data['listings'] = $listings;
			$this->data['match_id'] = $match_id;
			$this->load->library('pagination');
			// Pagination Configuration
			$config['base_url'] = base_url() . 'tickets/index/load_tickets/';
			$config['use_page_numbers'] = TRUE;
			$config['total_rows'] = $allcount;
			$config['per_page'] = $rowperpage;
			$config['full_tag_open'] = '<ul class="pagination">';
			$config['full_tag_close'] = '</ul>';
			$config['first_link'] = false;
			$config['last_link'] = false;
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['prev_link'] = '&laquo';
			$config['prev_tag_open'] = '<li class="prev">';
			$config['prev_tag_close'] = '</li>';
			$config['next_link'] = '&raquo';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="active"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			// Initialize
			$this->pagination->initialize($config);
			$this->data['seller_id'] = $_POST['seller_id'];
			$this->data['last_ticket_id'] = $_POST['last_ticket_id'];
			
			// Initialize $data Array
			$this->data['pagination'] = '<div class="pagination datatable-pagination pagination-datatables flex-column" id="pagination">' . $this->pagination->create_links() . '</div>'; 
			
			$list_tickets = $this->load->view(THEME_NAME.'/tickets/oe_list_tickets_ajax', $this->data, TRUE);//echo "<pre>";print_r($list_tickets);exit;
			/*if (empty($listings)) {
				$list_tickets = $this->load->view(THEME_NAME.'/errors/nofound', $this->data, TRUE);
			}*/

			$response = array('search_type' => 'listing', 'tickets' => $list_tickets);
			echo json_encode($response);

			

		}
		else if($segment == "update_ticket_status"){

				
			
			$ticket_data = $this->Tickets_Model->check_match($_POST['tickets'][0]);
				
			$event_flag = 'E';	
			if(@$ticket_data->m_id != ''){
				$event_flag = 'OE';
			}
			if(!empty($_POST)){
			$ticket_current_status = "";
			if($_POST['ticket_action'] == 'unpublish'){
				$status = 0;
				$ticket_current_status = "Unpublished";
			}
			else if($_POST['ticket_action'] == 'publish'){
				$status = 1;
				$ticket_current_status = "Published";
			}
			else if($_POST['ticket_action'] == 'delete'){
				$status = 2;
			}

			if($_POST['selltype'] == 'match'){

				$updateArray = array();

			for($x = 0; $x < count($_POST['tickets']); $x++){

			$update_data[$x] = array(       
			'status'   => $status,      
			'match_id' 	   => $_POST['tickets'][$x]
			); 
				if($_POST['ticket_action'] == 'delete'){
					$update_data[$x]['ticket_deleted_date'] = date('Y-m-d H:i:s');
				}
			}
			if($_POST['ticket_action'] != 'delete'){
			$this->db->where('status != ',2);
			}
			$this->db->where('add_by',$this->session->userdata('admin_id'));
			$this->db->update_batch('sell_tickets', $update_data,'match_id');

			}
			else{

			$ticket_data = $this->Tickets_Model->getListing_v5($_POST['tickets'][0]);
			$match_id = $ticket_data->m_id;

			$event_flag = $ticket_data->event_flag;

				$updateArray = array();

			for($x = 0; $x < count($_POST['tickets']); $x++){

			$update_data[$x] = array(       
			'status'   => $status,      
			's_no' 	   => $_POST['tickets'][$x]
			); 
			if($_POST['ticket_action'] == 'delete'){
					$update_data[$x]['ticket_deleted_date'] = date('Y-m-d H:i:s');
			}
			}
			if($_POST['ticket_action'] != 'delete'){
			$this->db->where('status != ',2);
			}
			$this->db->where('add_by',$this->session->userdata('admin_id'));
			$this->db->update_batch('sell_tickets', $update_data,'s_no');

			}

			

			if ($this->db->affected_rows() > 0){

				$response = array('status' => 1,'event_flag' => $event_flag,'match_id' => $match_id,'selltype' => $_POST['selltype'], 'msg' => 'Ticket Status Updated Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 0, 'msg' => 'Tickets are already in '.$ticket_current_status);
				echo json_encode($response);
				exit;
			}
		}
		else {
				$response = array('status' => 0, 'msg' => 'Oops.Something went Wrong.');
				echo json_encode($response);
				exit;
			}

		}
		else if ($segment == "get_ticket") {
			$ticket_id = $_POST['ticket_id'];
			$ticket_data = $this->Tickets_Model->getListing_v4($ticket_id);

			$team1 = $this->General_Model->getAllItemTable_array('teams_lang', array('team_id' => $ticket_data->team_1,"language" => $this->session->userdata('language_code')))->row();
			$team2 = $this->General_Model->getAllItemTable_array('teams_lang', array('team_id' => $ticket_data->team_2,"language" => $this->session->userdata('language_code')))->row();
			$ticket_data->team1_name = $team1->team_name;
			$ticket_data->team2_name = $team2->team_name;

			$condition['stadium_id'] = $ticket_data->venue;
			$condition['category'] = $ticket_data->ticket_category;
			$condition['source_type'] = '1boxoffice';
			$this->data['blocks_data'] = $this->General_Model->getAllItemTable('stadium_details', $condition)->result();

			$tkt_category = $this->Tickets_Model->tkt_category($ticket_data->venue);
			foreach ($tkt_category as $key => $std) {
						$category_data[$std->category] = $std->seat_category;
					}
			$this->data['tkt_categories'] = $category_data;
			$this->data['list_ticket'] = $ticket_data;
			$this->data['ticket_types'] = $this->General_Model->get_ticket_type_data('', 'ACTIVE')->result();
			$this->data['split_types'] = $this->General_Model->get_split_type_data('', 'ACTIVE')->result();
			/*$this->data['ticket_details'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE')->result();*/
			$this->data['restriction_left'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE',1,1)->result();
			$this->data['restriction_right'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE',1,2)->result();
			$this->data['notes_left'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE',2,1)->result();
			$this->data['notes_right'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE',2,2)->result();
			$this->data['split_details_left'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE',3,1)->result();//echo "<pre>";print_r($this->data['restriction_left']);exit;
			$this->data['split_details_right'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE',3,2)->result();
			if($_POST['type'] == 'clone'){
				$html = $this->load->view(THEME_NAME.'/tickets/ticket_clone_ajax', $this->data, TRUE);
			}
			else if($_POST['type'] == 'edit'){
				$html = $this->load->view(THEME_NAME.'/tickets/ticket_edit_ajax', $this->data, TRUE);
			}
			else{
				$html = "Invaid Request data.";
			}
			
			$response = array('status' => '1', 'html' => $html);
			echo json_encode($response);
			exit;
		 	echo "<pre>";print_r($_POST);exit;
		}
		else if ($segment == "get_ticket_oe") {
			$ticket_id = $_POST['ticket_id'];
			$ticket_data = $this->Tickets_Model->getListing_oe($ticket_id);
			$team1 = $this->General_Model->getAllItemTable_array('teams_lang', array('team_id' => $ticket_data->team_1,"language" => $this->session->userdata('language_code')))->row();
			$team2 = $this->General_Model->getAllItemTable_array('teams_lang', array('team_id' => $ticket_data->team_2,"language" => $this->session->userdata('language_code')))->row();
			$ticket_data->team1_name = $team1->team_name;
			$ticket_data->team2_name = $team2->team_name;

			$condition['stadium_id'] = $ticket_data->venue;
			$condition['category'] = $ticket_data->ticket_category;
			$this->data['blocks_data'] = $this->General_Model->getAllItemTable('stadium_details', $condition)->result();

			$this->data['tkt_categories'] = $this->Tickets_Model->tkt_category($ticket_data->venue);
			$this->data['list_ticket'] = $ticket_data;
			$this->data['ticket_types'] = $this->General_Model->get_ticket_type_data('', 'ACTIVE')->result();
			$this->data['split_types'] = $this->General_Model->get_split_type_data('', 'ACTIVE')->result();
			/*$this->data['ticket_details'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE')->result();*/
			$this->data['restriction_left'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE',1,1)->result();
			$this->data['restriction_right'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE',1,2)->result();
			$this->data['notes_left'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE',2,1)->result();
			$this->data['notes_right'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE',2,2)->result();
			$this->data['split_details_left'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE',3,1)->result();//echo "<pre>";print_r($this->data['restriction_left']);exit;
			$this->data['split_details_right'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE',3,2)->result();
			if($_POST['type'] == 'clone'){
				$html = $this->load->view(THEME_NAME.'/tickets/oe_ticket_clone_ajax', $this->data, TRUE);
			}
			else if($_POST['type'] == 'edit'){
				$html = $this->load->view(THEME_NAME.'/tickets/oe_ticket_edit_ajax', $this->data, TRUE);
			}
			else{
				$html = "Invaid Request data.";
			}
			
			$response = array('status' => '1', 'html' => $html);
			echo json_encode($response);
			exit;
		 	echo "<pre>";print_r($_POST);exit;
		}
		else if ($segment == "create_ticket") {

			$bulk = $this->uri->segment(4);
			if($bulk == "bulk"){
				//$this->data['bulk_ticket_matches'] =  $this->General_Model->getAllItemTable_array('seller_whish_list', array('seller_id' => $this->session->userdata('admin_id'),'status' => 0))->result();
				$this->data['bulk_ticket_matches'] =  $this->Tickets_Model->bulk_ticket_matches();
			}
			
			$ticket_types = $this->General_Model->get_ticket_type_data('', 'ACTIVE')->result();
			$categories = $this->General_Model->get_ticket_type_category()->result();
			$this->data['ticket_deliveries'] = $categories;
			$this->data['ticket_types'] = $ticket_types;
			$this->data['ticket_max'] = 10;
			$this->data['split_types'] = $this->General_Model->get_split_type_data('', 'ACTIVE')->result();
			$this->data['restriction_left'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE',1,1)->result();//echo "<pre>";print_r($this->data['restriction_left']);exit;
			$this->data['restriction_right'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE',1,2)->result();
			$this->data['notes_left'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE',2,1)->result();
			$this->data['notes_right'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE',2,2)->result();
			$this->data['split_details_left'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE',3,1)->result();//echo "<pre>";print_r($this->data['restriction_left']);exit;
			$this->data['split_details_right'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE',3,2)->result();
			$this->data['matches'] = $this->Tickets_Model->getallMatch();
			$this->load->view(THEME_NAME.'/tickets/create_ticket', $this->data);
		} 
		else if ($segment == "create_oe_ticket") {

			$ticket_types = $this->General_Model->get_ticket_type_data('', 'ACTIVE')->result();
			$categories = $this->General_Model->get_ticket_type_category()->result();
			$this->data['ticket_deliveries'] = $categories;
			$this->data['ticket_types'] = $ticket_types;
			$this->data['ticket_max'] = 10;
			$this->data['split_types'] = $this->General_Model->get_split_type_data('', 'ACTIVE')->result();
			$this->data['restriction_left'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE',1,1)->result();//echo "<pre>";print_r($this->data['restriction_left']);exit;
			$this->data['restriction_right'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE',1,2)->result();
			$this->data['notes_left'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE',2,1)->result();
			$this->data['notes_right'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE',2,2)->result();
			$this->data['split_details_left'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE',3,1)->result();//echo "<pre>";print_r($this->data['restriction_left']);exit;
			$this->data['split_details_right'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE',3,2)->result();
			$this->data['matches'] = $this->Tickets_Model->getallMatch('','other');
			$this->load->view(THEME_NAME.'/tickets/create_oe_ticket', $this->data);
		}
		
		else if ($segment == "get_match_names_oe"){
			$search = $this->input->get('search');	
			
			$matches_list = $this->Tickets_Model->getallMatch_oe($search);
			$matches =  array();
			foreach ($matches_list as $key => $value) {
				$matches[] = array(
						'id'		=> $value->m_id,
						'text'		=> $value->match_name." - ".$value->match_date_format." - ".$value->category_name,
				);
			}
			echo json_encode($matches);
		}
		else if ($segment == "get_match_names"){
			$search = $this->input->get('search');	
			if($this->session->userdata('other_event') == '1'){
			$matches_list_oe = $this->Tickets_Model->getallMatch_oe($search);
			}
			else{
				$matches_list_oe = [];
			}
			$matches_list_e = $this->Tickets_Model->getallMatch($search,'other');
			$matches_list = array_merge($matches_list_oe,$matches_list_e);
			$matches =  array();
			foreach ($matches_list as $key => $value) {
				
				if($value->event_type == 'other'){
					if($value->tournament_name != ""){
						$text = $value->match_name." - ".$value->match_date_format." - ".$value->tournament_name;
					}
					else{
						$text = $value->match_name." - ".$value->match_date_format." - ".$value->category_name;
					}
					
				}
				else{
					$text = $value->match_name." - ".$value->match_date_format." - ".$value->tournament_name;
				}
				$matches[] = array(
						'id'		=> $value->m_id,
						'text'		=> $text,
						'event_type'=> $value->event_type,
						'OE' => $matches_list_oe
				);
			}
			echo json_encode($matches);
		}
		else if ($segment == "ticket_request") {

			//$this->data['tickets'] = $this->Tickets_Model->ticket_request()->result();
			$type = $this->uri->segment(4);
			$row_count = $this->uri->segment(5);
			$this->loadRecord($row_count, 'request_tickets', 'tickets/index/ticket_request/'.$type, 'request_tickets.id', 'DESC', 'tickets/ticket_request','tickets', 'categories', $type);

			//$this->load->view(THEME_NAME.'/tickets/ticket_request', $this->data);
		} 
		else if ($segment == "contact_enquiry") {

			//$this->data['tickets'] = $this->Tickets_Model->ticket_request()->result();

			$row_count = $this->uri->segment(4);
			$this->loadRecord($row_count, 'contact_enquiries', 'tickets/index/contact_enquiry', 'contact_details.id', 'DESC', 'tickets/contact_enquiry','contacts','','');

			//$this->load->view(THEME_NAME.'/tickets/ticket_request', $this->data);
		} 
		else if ($segment == "partnership_enquiry") {

			//$this->data['tickets'] = $this->Tickets_Model->ticket_request()->result();

			$row_count = $this->uri->segment(4);
			$this->loadRecord($row_count, 'partner_enquiry', 'tickets/index/partnership_enquiry', 'partner_enquiry.id', 'DESC', 'tickets/partnership_enquiry','partners', '', '');

			//$this->load->view(THEME_NAME.'/tickets/ticket_request', $this->data);
		} 
		else if ($segment == "ticket_details") {
			$segment4 = $this->uri->segment(4);
			$this->data['tickets'] = $this->Tickets_Model->ticket_request($segment4)->row();
			//echo "<pre>";print_r($this->data['tickets']);exit;
			$this->load->view(THEME_NAME.'/tickets/ticket_details', $this->data);
		} 
		else if ($segment == "update_enquiry_status") {

				$update_data = array(
					'status' => $_POST['status']
				);
				if($_POST['flag'] == 'partner'){
					$table = 'partner_enquiry';
					$column='id';
					$columnid=$_POST['id'];
				}
				else if($_POST['flag'] == 'ticket'){
					$table = 'request_tickets';
					$column='id';
					$columnid=$_POST['id'];
				}
				else if($_POST['flag'] == 'contact'){
					$table = 'contact_details';
					$column='id';
					$columnid=$_POST['id'];
				}
				$update = $this->General_Model->update_table($table, $column, $columnid, $update_data);

				if ($update == true) {
					$response = array('status' => 1, 'msg' => 'Enquiry updated Successfully.');
					echo json_encode($response);
					exit;
				} else {
					$response = array('status' => 0, 'msg' => 'Error while updating Enquiry Status.');
					echo json_encode($response);
					exit;
				}
		}
		else if ($segment == "listing_details") {

			$match_id = $this->uri->segment(4);
			
			$this->data['match_id'] = $match_id;

			$this->load->view(THEME_NAME.'/tickets/list_tickets_details', $this->data);
		}
		else if ($segment == "oe_listing_details") {

			$match_id = $this->uri->segment(4);
			
			$this->data['match_id'] = $match_id;

			$this->load->view(THEME_NAME.'/tickets/oe_list_tickets_details', $this->data);
		}
		else if ($segment == "listing") {
			$_SESSION['match_id']	= '';
			$_SESSION['event'] =  '';
			$_SESSION['ticket_category'] = '';
			$_SESSION['stadium'] =  '';
			$_SESSION['event_start_date']	= '';
			$_SESSION['event_end_date'] = '';
			$_SESSION['ignore_end_date'] = '';
			$this->data['sellers'] = $this->General_Model->get_sellers();
			//echo "<pre>";print_r($this->data['sellers']);exit;
			$this->load->view(THEME_NAME.'/tickets/list_tickets', $this->data);
		}
		else if ($segment == "oe_listing") {
			$_SESSION['match_id']	= '';
			$_SESSION['event'] =  '';
			$_SESSION['ticket_category'] = '';
			$_SESSION['stadium'] =  '';
			$_SESSION['event_start_date']	= '';
			$_SESSION['event_end_date'] = '';
			$_SESSION['ignore_end_date'] = '';
			$this->data['sellers'] = $this->General_Model->get_sellers();
			//echo "<pre>";print_r($this->data['sellers']);exit;
			$this->load->view(THEME_NAME.'/tickets/oe_list_tickets', $this->data);
		} else if ($segment == "upload_management") {
			$this->load->view(THEME_NAME.'/tickets/upload_management', $this->data);
		} else if ($segment == "save_ticket_details") {

			//echo "<pre>";print_r($_POST);exit;


			if (!empty($_POST['s_no'])) {
				//echo "<pre>";print_r($_POST);exit;
				$ticketid = $_POST['s_no'];//echo 'ticketid = '.$ticketid;exit;
				$ticket_data = $this->Tickets_Model->get_sell_tickets($ticketid);
				$match_id    = $ticket_data->match_id;
				
				$update_data = array(
					'ticket_updated_date' => date('Y-m-d H:i:s'),
					'listing_note' => implode(',', $_POST['ticket_details'])
				);
				$update = $this->General_Model->update_table('sell_tickets', 's_no', $_POST['s_no'], $update_data);

				if ($update == true) {
					$response = array('status' => 1,'match_id' => $match_id, 'msg' => 'Seller Notes updated Successfully.');
					echo json_encode($response);
					exit;
				} else {
					$response = array('status' => 1, 'msg' => 'Error while updating Seller Notes.');
					echo json_encode($response);
					exit;
				}
			}
		}
		 else if ($segment == "load_tickets") {

			if ($this->uri->segment(4)) {
				$rowno = ($this->uri->segment(4));
			} else {
				$rowno = 0;
			}
			$rowperpage = 1000;

			// Row position
			if ($rowno != 0) {
				$rowno = ($rowno - 1) * $rowperpage;
			}

			$match_id = $_POST['match_id'];
			$seller_id = $_POST['seller_id'];
			$last_ticket_id = $_POST['last_ticket_id'];
			
			//echo "<pre>";print_r($_POST);exit;
			if($seller_id != '' && $seller_id != 'all'){
			$this->session->set_userdata('seller_id', $seller_id);
			}
			else if($seller_id == 'all'){
				$this->session->unset_userdata('seller_id');
			}
			else{
				//$this->session->unset_userdata('seller_id');
			} 
			$this->data['ticket_types'] = $this->General_Model->get_ticket_type_data('', 'ACTIVE')->result();
			$this->data['split_types'] = $this->General_Model->get_split_type_data('', 'ACTIVE')->result();
			$this->data['ticket_details'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE')->result();
			$allcount = $this->Tickets_Model->getListing_count("", "", "", "upcoming");

			$listings = $this->Tickets_Model->getListing("", "", "", "upcoming", "", "", $rowno, $rowperpage);
			$mylisting = array();
			foreach ($listings as $tkey => $listing) {
				$tkt_category = $this->Tickets_Model->tkt_category($listing->venue);
				$category_data = array();
				$block_data = array();

				if ($get_std) {
					foreach ($get_std as $std) {
						$block_id = explode("-", $std->block_id);

						$block_data[$std->block_id] = end($block_id);
					}
				}

				if ($tkt_category) {

					foreach ($tkt_category as $key => $std) {
						$category_data[$std->category] = $std->seat_category;
					}
					$listings[$tkey]->block_data = $block_data;
					$listings[$tkey]->tkt_category = $category_data;
					$listings[$tkey]->tickets = $this->Tickets_Model->getListing_v1($listing->m_id);
					$listings[$tkey]->pending_fullfillment = $this->General_Model->pending_fullfillment($listing->m_id)->num_rows();
					$listings[$tkey]->sold_qty = $this->General_Model->ticket_sold_quantity($listing->m_id)->num_rows();
				}
			}
			$this->data['search_type'] = "listing";
			$this->data['listings'] = $listings;
			$this->data['match_id'] = $match_id;
			$this->load->library('pagination');
			// Pagination Configuration
			$config['base_url'] = base_url() . 'tickets/index/load_tickets/';
			$config['use_page_numbers'] = TRUE;
			$config['total_rows'] = $allcount;
			$config['per_page'] = $rowperpage;
			$config['full_tag_open'] = '<ul class="pagination">';
			$config['full_tag_close'] = '</ul>';
			$config['first_link'] = false;
			$config['last_link'] = false;
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['prev_link'] = '&laquo';
			$config['prev_tag_open'] = '<li class="prev">';
			$config['prev_tag_close'] = '</li>';
			$config['next_link'] = '&raquo';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="active"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			// Initialize
			$this->pagination->initialize($config);
			$this->data['seller_id'] = $_POST['seller_id'];
			$this->data['last_ticket_id'] = $_POST['last_ticket_id'];
			
			// Initialize $data Array
			$this->data['pagination'] = '<div class="pagination datatable-pagination pagination-datatables flex-column" id="pagination">' . $this->pagination->create_links() . '</div>'; 

			$list_tickets = $this->load->view(THEME_NAME.'/tickets/list_tickets_ajax', $this->data, TRUE);
			/*if (empty($listings)) {
				$list_tickets = $this->load->view(THEME_NAME.'/errors/nofound', $this->data, TRUE);
			}*/
			$response = array('search_type' => 'listing', 'tickets' => $list_tickets);
			echo json_encode($response);
			exit;
		}
		 else if ($segment == "load_tickets_details") {

			
			$match_id = $_POST['match_id'];
			$last_ticket_id = $_POST['last_ticket_id'];
			
			$this->data['ticket_types'] = $this->General_Model->get_ticket_type_data('', 'ACTIVE')->result();
			$this->data['split_types'] = $this->General_Model->get_split_type_data('', 'ACTIVE')->result();
			$this->data['ticket_details'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE')->result();

			$listings = $this->Tickets_Model->getListing_details($match_id);

			$mylisting = array();
			foreach ($listings as $tkey => $listing) {

				

		

				$tkt_category = $this->Tickets_Model->tkt_category($listing->venue);
				$category_data = array();
				$block_data = array();

				if ($get_std) {
					foreach ($get_std as $std) {

						$block_id = explode("-", $std->block_id);
						$block_data[end($block_id)] = $std->id;
						/*$block_id = explode("-", $std->block_id);
						$block_data[$std->block_id] = end($block_id);*/
					}
				}

				if ($tkt_category) {

					foreach ($tkt_category as $key => $std) {
						$category_data[$std->category] = $std->seat_category;
					}
					$listings[$tkey]->block_data = $block_data;
					$listings[$tkey]->tkt_category = $category_data;
					//$listings[$tkey]->tickets = $this->Tickets_Model->getListing_v1($listing->m_id);
				/*	$listings[$tkey]->tickets = $this->Tickets_Model->getListing_filter($listing->m_id);
					$listings[$tkey]->pending_fullfillment = $this->General_Model->pending_fullfillment($listing->m_id)->num_rows();
					$listings[$tkey]->sold_qty = $this->General_Model->ticket_sold_quantity($listing->m_id)->num_rows();*/

					$listings[$tkey]->tickets = $this->Tickets_Model->getListing_filter($listing->m_id);
					$listings[$tkey]->pending_fullfillment = $this->General_Model->pending_fullfillment($listing->m_id)->num_rows();
					$soldticket = $this->General_Model->ticket_sold_quantity($listing->m_id)->row();
					$listings[$tkey]->sold_qty = $soldticket->total_ticket_sold;

					$availableticket = $this->Tickets_Model->ticket_available_quantity($listing->m_id,'publish')->row();
					$listings[$tkey]->available_tickets = $availableticket->tickets_available;

					
				}
			} 
			$this->data['listings'] = $listings;
			$this->data['match_id'] = $match_id;
			$this->data['last_ticket_id'] = $last_ticket_id;
			
			$list_tickets = $this->load->view(THEME_NAME.'/tickets/list_ajax', $this->data, TRUE);
			/*if (empty($listings)) {
				$list_tickets = $this->load->view(THEME_NAME.'/errors/nofound', $this->data, TRUE);
			}*/
			$response = array('search_type' => 'listing', 'tickets' => $list_tickets);
			echo json_encode($response);
			exit;
		}
		else if ($segment == "oe_load_tickets_details") {

			
			$match_id = $_POST['match_id'];
			$last_ticket_id = $_POST['last_ticket_id'];
			
			$this->data['ticket_types'] = $this->General_Model->get_ticket_type_data('', 'ACTIVE')->result();
			$this->data['split_types'] = $this->General_Model->get_split_type_data('', 'ACTIVE')->result();
			$this->data['ticket_details'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE')->result();

			$listings = $this->Tickets_Model->getListing_details_oe($match_id);
			//echo "<pre>";print_r($listings);exit;
			$mylisting = array();
			foreach ($listings as $tkey => $listing) {

				

		

				$tkt_category = $this->Tickets_Model->tkt_category($listing->venue);
				$category_data = array();
				$block_data = array();

				if ($get_std) {
					foreach ($get_std as $std) {
						$block_id = explode("-", $std->block_id);

						$block_data[$std->block_id] = end($block_id);
					}
				}

				if ($tkt_category) {

					foreach ($tkt_category as $key => $std) {
						$category_data[$std->category] = $std->seat_category;
					}
					$listings[$tkey]->block_data = $block_data;
					$listings[$tkey]->tkt_category = $category_data;
					//$listings[$tkey]->tickets = $this->Tickets_Model->getListing_v1($listing->m_id);
					/*$listings[$tkey]->tickets = $this->Tickets_Model->getListing_filter($listing->m_id);
					$listings[$tkey]->pending_fullfillment = $this->General_Model->pending_fullfillment($listing->m_id)->num_rows();
					$listings[$tkey]->sold_qty = $this->General_Model->ticket_sold_quantity($listing->m_id)->num_rows();*/

					

					
				}
				$listings[$tkey]->tickets = $this->Tickets_Model->getListing_filter($listing->m_id,$_POST['match_type']);
					$listings[$tkey]->pending_fullfillment = $this->General_Model->pending_fullfillment($listing->m_id)->num_rows();
					$soldticket = $this->General_Model->ticket_sold_quantity($listing->m_id)->row();
					$listings[$tkey]->sold_qty = $soldticket->total_ticket_sold;
					$availableticket = $this->Tickets_Model->ticket_available_quantity($listing->m_id,'publish')->row();
					$listings[$tkey]->available_tickets = $availableticket->tickets_available;
					
			}//echo "<pre>";print_r($listings);exit;
			$this->data['listings'] = $listings;
			$this->data['match_id'] = $match_id;
			$this->data['last_ticket_id'] = $last_ticket_id;
			
			$list_tickets = $this->load->view(THEME_NAME.'/tickets/oe_list_ajax', $this->data, TRUE);
			/*if (empty($listings)) {
				$list_tickets = $this->load->view(THEME_NAME.'/errors/nofound', $this->data, TRUE);
			}*/
			$response = array('search_type' => 'listing', 'tickets' => $list_tickets);
			echo json_encode($response);
			exit;
		} else if ($segment == "filter_tickets") {

			if ($this->uri->segment(4)) {
				$rowno = ($this->uri->segment(4));
			} else {
				$rowno = 0;
			}
			$rowperpage = 1000;

			// Row position
			if ($rowno != 0) {
				$rowno = ($rowno - 1) * $rowperpage;
			}

			$match_id 		= $_POST['match_id'];
			$event 					= $_POST['event'];
			$ticket_category 		 = $_POST['ticket_category'];
			$stadium 				 = $_POST['stadium'];
			$event_start_date = $_POST['event_start_date'];
			$event_end_date 		= $_POST['event_end_date'];
			$ignore_end_date 		 = $_POST['ignore_end_date'];
			
			if ($ignore_end_date == 1) {
				$event_end_date = '';
			}
			$this->data['search_type'] = "filter";
			$this->data['ticket_types'] = $this->General_Model->get_ticket_type_data('', 'ACTIVE')->result();
			$this->data['split_types'] = $this->General_Model->get_split_type_data('', 'ACTIVE')->result();
			$this->data['ticket_details'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE')->result();
			$allcount = $this->Tickets_Model->getListing_count($event, $ticket_category, $stadium, 'upcoming', $event_start_date, $event_end_date);

			$listings = $this->Tickets_Model->getListing($event, $ticket_category, $stadium, 'upcoming', $event_start_date, $event_end_date, $rowno, $rowperpage);
			$mylisting = array();
			foreach ($listings as $tkey => $listing) {
				$tkt_category = $this->Tickets_Model->tkt_category($listing->venue);
				$category_data = array();
				$block_data = array();

				if ($get_std) {
					foreach ($get_std as $std) {
						$block_id = explode("-", $std->block_id);

						$block_data[$std->block_id] = end($block_id);
					}
				}

				if ($tkt_category) {

					foreach ($tkt_category as $key => $std) {
						$category_data[$std->category] = $std->seat_category;
					}
					$listings[$tkey]->block_data = $block_data;
					$listings[$tkey]->tkt_category = $category_data;
					$listings[$tkey]->tickets = $this->Tickets_Model->getListing_v1($listing->m_id);
				}
			}
			$this->data['listings'] = $listings;
			$this->data['match_id'] = $match_id;
			$this->load->library('pagination');
			// Pagination Configuration
			$config['base_url'] = base_url() . 'tickets/index/filter_tickets/';
			$config['use_page_numbers'] = TRUE;
			$config['total_rows'] = $allcount;
			$config['per_page'] = $rowperpage;
			$config['full_tag_open'] = '<ul class="pagination">';
			$config['full_tag_close'] = '</ul>';
			$config['first_link'] = false;
			$config['last_link'] = false;
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['prev_link'] = '&laquo';
			$config['prev_tag_open'] = '<li class="prev">';
			$config['prev_tag_close'] = '</li>';
			$config['next_link'] = '&raquo';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="active"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			// Initialize
			$this->pagination->initialize($config);

			// Initialize $data Array
			$this->data['pagination'] = '<div class="pagination datatable-pagination pagination-datatables flex-column" id="paginationfilter">' . $this->pagination->create_links() . '</div>';
			$list_tickets = $this->load->view(THEME_NAME.'/tickets/list_tickets_ajax', $this->data, TRUE);
			if (empty($listings)) {
				$list_tickets = $this->load->view(THEME_NAME.'/errors/nofound', $this->data, TRUE);
			}
			$response = array('search_type' => 'filter', 'tickets' => $list_tickets);
			echo json_encode($response);
			exit;
		} else if ($segment == "filter_search") {
			if ($this->uri->segment(4)) {
				$rowno = ($this->uri->segment(4));
			} else {
				$rowno = 0;
			}
			$rowperpage = 1000;

			// Row position
			if ($rowno != 0) {
				$rowno = ($rowno - 1) * $rowperpage;
			}
			$match_id 			= $_POST['match_id'];
			$filter 			= $_POST['filter'];

			$this->data['search_type'] = "filter";
			$this->data['ticket_types'] = $this->General_Model->get_ticket_type_data('', 'ACTIVE')->result();
			$this->data['split_types'] = $this->General_Model->get_split_type_data('', 'ACTIVE')->result();
			$this->data['ticket_details'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE')->result();
			$allcount = $this->Tickets_Model->getListing_count('', '', '', $filter, '', '');

			$listings = $this->Tickets_Model->getListing('', '', '', $filter, '', '', $rowno, $rowperpage);
			$mylisting = array();
			foreach ($listings as $tkey => $listing) {
				$tkt_category = $this->Tickets_Model->tkt_category($listing->venue);
				$category_data = array();
				$block_data = array();

				if ($get_std) {
					foreach ($get_std as $std) {
						$block_id = explode("-", $std->block_id);

						$block_data[$std->block_id] = end($block_id);
					}
				}

				if ($tkt_category) {

					foreach ($tkt_category as $key => $std) {
						$category_data[$std->category] = $std->seat_category;
					}
					$listings[$tkey]->block_data = $block_data;
					$listings[$tkey]->tkt_category = $category_data;
					$listings[$tkey]->tickets = $this->Tickets_Model->getListing_v1($listing->m_id);
				}
			}
			$this->data['listings'] = $listings;
			$this->data['match_id'] = $match_id;
			$this->load->library('pagination');
			// Pagination Configuration
			$config['base_url'] = base_url() . 'tickets/index/filter_search/';
			$config['use_page_numbers'] = TRUE;
			$config['total_rows'] = $allcount;
			$config['per_page'] = $rowperpage;
			$config['full_tag_open'] = '<ul class="pagination">';
			$config['full_tag_close'] = '</ul>';
			$config['first_link'] = false;
			$config['last_link'] = false;
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['prev_link'] = '&laquo';
			$config['prev_tag_open'] = '<li class="prev">';
			$config['prev_tag_close'] = '</li>';
			$config['next_link'] = '&raquo';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="active"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			// Initialize
			$this->pagination->initialize($config);

			// Initialize $data Array
			$this->data['pagination'] = '<div class="pagination datatable-pagination pagination-datatables flex-column" id="paginationsearch">' . $this->pagination->create_links() . '</div>';
			$list_tickets = $this->load->view(THEME_NAME.'/tickets/list_tickets_ajax', $this->data, TRUE);
			if (empty($listings)) {
				$list_tickets = $this->load->view(THEME_NAME.'/errors/nofound', $this->data, TRUE);
			}
			$response = array('search_type' => 'filter', 'tickets' => $list_tickets);
			echo json_encode($response);
			exit;
		} else if ($segment == 'ticket_delete') {

			$segment4 = $this->uri->segment(4);
			$delete_id = $_POST['ticketid'];
			$ticketid = $_POST['ticketid'];
			$ticket_data = $this->Tickets_Model->get_sell_tickets($ticketid);
			$match_id = $ticket_data->match_id;
			$update_data = array('status' => 2,'ticket_deleted_date' => date('Y-m-d H:i:s'));
			//$delete = $this->General_Model->delete_data('sell_tickets', 's_no', $delete_id);
			$update = $this->General_Model->update_table('sell_tickets', 's_no', $delete_id, $update_data);
			if ($update == 1) {
				$response = array('status' => 1,'match_id' => $match_id, 'msg' => 'Ticket Deleted Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error while deleting Ticket.');
				echo json_encode($response);
				exit;
			}
		} 
		else if ($segment == 'ticket_update_v1') {

			$ticket_status = 1;
			if ($_POST['ticket_status'] == 'false') {
				$ticket_status = 0;
			}
			//$ticket_status = $_POST['ticket_status'];
			$ticket_track = '0';
			if ($_POST['ticket_track'] == 1) {
				$ticket_track = '1';
			} //echo "<pre>";print_r($_POST);exit;
			$update_data = array(
				'ticket_type' => $_POST['ticket_type'],
				'ticket_category' => $_POST['ticket_category'],
				'ticket_block' => $_POST['ticket_block'],
				//'home_town' => $_POST['home_down'],
				'row' => $_POST['ticket_row'],
				//'quantity' => $_POST['ticket_quantity'],
				//'seat' => $_POST['ticket_seat'],
				'price' => $_POST['ticket_price'],
				//'listing_note' => implode(',', $_POST['ticket_details']),
				'ticket_updated_date' => date('Y-m-d H:i:s'),
				//'split' => $_POST['ticket_split'],
				/*'sell_type' => $_POST['sell_type'],
				'track' => $ticket_track,*/
				'status' => $ticket_status
			);
			//echo "<pre>";print_r($update_data);exit;
			$ticketid = $_POST['ticketid'];
			$ticket_data = $this->Tickets_Model->get_sell_tickets($ticketid);
			$match_id = $ticket_data->match_id;
			$update = $this->General_Model->update_table('sell_tickets', 's_no', $_POST['ticketid'], $update_data);//echo 'update = '.$update;exit;
			//echo "<pre>";print_r($update);exit;
			if ($update == true) {
				$response = array('status' => 1,'match_id' => $match_id, 'msg' => 'Ticket updated Successfully.','redirect_url' => base_url() . 'tickets/index/listing_details/'.$match_id);
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error while updating Ticket.');
				echo json_encode($response);
				exit;
			}
		} 
		else if ($segment == 'auto_save') {

			//if ($_POST['column_name'] != '' && $_POST['column_value'] != '' && $_POST['sno'] != '') {
			if ($_POST['column_name'] != '' && $_POST['sno'] != '') {	

			$update_data = array(
				$_POST['column_name'] => $_POST['column_value']
			);
			if($_POST['column_name'] == "ticket_category"){
				$update_data['ticket_block'] = '';
			}
			$ticketid = $_POST['sno'];
			$ticket_data = $this->Tickets_Model->get_sell_tickets($ticketid);
			$match_id = $ticket_data->match_id;
			$update = $this->General_Model->update_table('sell_tickets', 's_no', $_POST['sno'], $update_data);
			//echo $this->db->last_query();exit;
			if ($update == true) {
				$response = array('status' => 1,'match_id' => $match_id, 'msg' => 'Ticket updated Successfully.','redirect_url' => base_url() . 'tickets/index/listing_details/'.$match_id);
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error while updating Ticket.');
				echo json_encode($response);
				exit;
			}
		}
		else {
				$response = array('status' => 0, 'msg' => 'Failed');
				echo json_encode($response);
				exit;
			}

		}
		else if ($segment == 'ticket_update') {

			$ticket_status = 1;
			if ($_POST['ticket_status'] == '') {
				$ticket_status = 0;
			}
			//$ticket_status = $_POST['ticket_status'];
			$ticket_track = '0';
			if ($_POST['ticket_track'] == 1) {
				$ticket_track = '1';
			} 
			$ticket_details = '';
			if ($_POST['ticket_details'] != '') {
				$ticket_details = implode(',', $_POST['ticket_details']);
			}

			

			$update_data = array(
				'ticket_type' => $_POST['ticket_type'],
				'ticket_category' => $_POST['ticket_category'],
				'ticket_block' => $_POST['ticket_block'],
				'home_town' => $_POST['home_down'],
				'row' => $_POST['ticket_row'],
				'quantity' => $_POST['ticket_quantity'],
				'seat' => @$_POST['ticket_seat'],
				'price' => $_POST['ticket_price'],
				'listing_note' => $ticket_details,
				'ticket_updated_date' => date('Y-m-d H:i:s'),
				'split' => $_POST['ticket_split'],
				/*'sell_type' => $_POST['sell_type'],
				'track' => $ticket_track,*/
				'status' => $ticket_status
			);
			//echo "<pre>";print_r($update_data);exit;
			$ticketid = $_POST['ticketid'];
			$ticket_data = $this->Tickets_Model->get_sell_tickets($ticketid);
			$match_id = $ticket_data->match_id;
			$update = $this->General_Model->update_table('sell_tickets', 's_no', $_POST['ticketid'], $update_data);//echo 'update = '.$update;exit;
			if($ticket_data->event_flag == 'OE'){
			  $redirect_url = base_url() . 'tickets/index/oe_listing_details/'.$match_id;
			}
			else{
			  $redirect_url = base_url() . 'tickets/index/listing_details/'.$match_id;
			}
			if ($update == true) {
				$response = array('status' => 1,'event_flag' => $ticket_data->event_flag,'match_id' => $match_id, 'msg' => 'Ticket updated Successfully.','redirect_url' => $redirect_url);
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1,'event_flag' => $ticket_data->event_flag, 'msg' => 'Error while updating Ticket.');
				echo json_encode($response);
				exit;
			}
		} 
		else if ($segment == 'update_ticket_status_v1') { 
			//echo "<pre>";print_r($_POST);exit;
			$ticket_status = 0;
			if ($_POST['ticket_status'] == 'true') {
				$ticket_status = 1;
			}
			//$flag = $_POST['flag'];
			$update_data = array(
				'status' => $ticket_status
				);
			$update = $this->General_Model->update_table('sell_tickets', 's_no', $_POST['ticket_id'], $update_data);
			//echo $this->db->last_query();exit;
			if ($this->db->affected_rows() > 0){
				$response = array('status' => 1, 'msg' => 'Ticket Status updated Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error while updating Ticket Status.');
				echo json_encode($response);
				exit;
			}
		}
		else if ($segment == 'ticket_update_status') {

			$ticket_status = 0;
			if ($_POST['ticket_status'] == 'true') {
				$ticket_status = 1;
			}
			$flag = $_POST['flag'];
			
			$ticket_data = $this->Tickets_Model->get_sell_tickets_by_match($_POST['match_id']);
			//echo "<pre>";print_r($ticket_data);exit;
			foreach($ticket_data as $ticketdata){
				
				if($ticketdata->status != $ticket_status){
				$update_data = array(
				'status' => $ticket_status
				);
				$update = $this->General_Model->update_table('sell_tickets', 's_no', $ticketdata->s_no, $update_data);
				//echo $this->db->last_query();
				}
			}
			
			//exit;
			
			//echo "<pre>";print_r($update);exit;
			if ($update == true) {
				$response = array('status' => 1,'flag' => $flag,'match_id' => $_POST['match_id'], 'msg' => 'Ticket Status updated Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error while updating Ticket Status.');
				echo json_encode($response);
				exit;
			}
		}
		else if ($segment == 'mass_duplicate') {

			$ticketid = $_POST['ticketid'];
			$match_id = $_POST['match_id'];
			$this->data['ticket_types'] = $this->General_Model->get_ticket_type_data('', 'ACTIVE')->result();
			$this->data['split_types'] = $this->General_Model->get_split_type_data('', 'ACTIVE')->result();
			$this->data['ticket_details'] = $this->General_Model->get_ticket_details_data('', 'ACTIVE')->result();	
			$listings = $this->Tickets_Model->getListing_details_v1($match_id,$ticketid);
			$mylisting = array();
			//echo "<pre>";print_r($listings);exit;
			foreach ($listings as $tkey => $listing) {
			$tkt_category = $this->Tickets_Model->tkt_category($listing->venue);
			$category_data = array();
			$block_data = array();

			if ($get_std) {
			foreach ($get_std as $std) {
			$block_id = explode("-", $std->block_id);

			$block_data[$std->block_id] = end($block_id);
			}
			}

			if ($tkt_category) {

			foreach ($tkt_category as $key => $std) {
			$category_data[$std->category] = $std->seat_category;
			}
			$listings[$tkey]->block_data = $block_data;
			$listings[$tkey]->tkt_category = $category_data;
			$listings[$tkey]->tickets = $this->Tickets_Model->getListing_v3($listing->m_id,$ticketid);
			}
			}
			$this->data['listings'] = $listings;

			$list_tickets = $this->load->view(THEME_NAME.'/tickets/bulk_duplicate', $this->data, TRUE);
			$response = array('tickets' => $list_tickets);
			echo json_encode($response);
			exit;

		}
		else if ($segment == 'save_mass_duplicate') {

			$s_no 	  = $_POST['s_no'];
			$match_id = $_POST['match_id'];

			$old_ticket = $this->Tickets_Model->getListing_v3($match_id,$s_no);
			//echo "<pre>";print_r($_POST);exit;
			//echo 'event_flag = '.$old_ticket[0]->event_flag;exit;
			if($match_id != "" && $s_no != ""){

				$ticket_data = array();
				for($i = 0;$i < count($_POST['ticket_type']);$i++){
					$ticketid = mt_rand(1000, 9999) . '_' . mt_rand(100000, 999999);
					$ticket_group_id = mt_rand(100000, 999999);

					$ticket_data[] = array(
					'ticketid' => $ticketid,
					'ticket_group_id' => $ticket_group_id,
					'user_id' => $old_ticket[0]->user_id,
					'match_id' => $old_ticket[0]->match_id,
					'ticket_type' => $_POST['ticket_type'][$i],
					'ticket_category' => $_POST['ticket_category'][$i],
					'ticket_block' => $_POST['ticket_block'][$i],
					'home_town' => $_POST['home_town'][$i],//$_POST['home_town'][$i],
					'row' => $_POST['row'][$i],
					'event_flag' => $old_ticket[0]->event_flag,
					'quantity' => $_POST['quantity'][$i],
					'seat' => $old_ticket[0]->seat,
					'sold' => 0,
					'price_type' => $old_ticket[0]->price_type,
					'price' => $_POST['price'][$i],
					'listing_note' => $old_ticket[0]->listing_note,
					'split' => $_POST['split'][$i],
					'sell_date' => date('Y-m-d H:i:s'),
					'auto_disable' => $old_ticket[0]->auto_disable,
					'status' => $old_ticket[0]->status,//$_POST['status'][$i],
					'add_by' => $this->session->userdata('admin_id'),
					'store_id' => $this->session->userdata('storefront')->admin_id
					);
				}
				
				$ticket_data = $this->General_Model->insert_batch_data('sell_tickets',$ticket_data);
				$sellInsert  = $this->db->insert_id();
			}

			if ($sellInsert != "") {
				$response = array('status' => 1,'event_flag' => $old_ticket[0]->event_flag, 'msg' => 'Ticket Duplicated Successfully.','seller_id' => $ticket_data->add_by,'match_id' => $match_id,'ticket_last_id' => $sellInsert);
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 0,'event_flag' => $old_ticket[0]->event_flag, 'msg' => 'Error while duplicating Tickets.');
				echo json_encode($response);
				exit;
			}

			
		}
		else if ($segment == 'ticket_duplicate') {


			$ticketid = $_POST['ticketid'];
			$ticket_data = $this->Tickets_Model->get_sell_tickets($ticketid);

			if ($ticket_data->s_no) {
				//echo "<pre>";print_r($ticket_data);exit;
				$ticketid = mt_rand(1000, 9999) . '_' . mt_rand(100000, 999999);
				$insertData['ticketid'] = $ticketid;
				$insertData['user_id'] = $ticket_data->user_id;
				$insertData['match_id'] = $ticket_data->match_id;
				$insertData['ticket_type'] = $ticket_data->ticket_type;
				$insertData['ticket_category'] = $ticket_data->ticket_category;
				$insertData['ticket_block'] = $ticket_data->ticket_block;
				$insertData['home_town'] = $ticket_data->home_town;
				$insertData['row'] = $ticket_data->row;
				$insertData['quantity'] = $ticket_data->quantity;
				$insertData['seat'] = $ticket_data->seat;
				$insertData['sold'] = $ticket_data->sold;
				$insertData['price_type'] = $ticket_data->price_type;
				$insertData['price'] = $ticket_data->price;
				$insertData['listing_note'] = $ticket_data->listing_note;
				$insertData['delivery_courier'] = $ticket_data->delivery_courier;
				$insertData['split'] = $ticket_data->split;
				$insertData['collection'] = $ticket_data->collection;
				$insertData['pickup_address'] = $ticket_data->pickup_address;
				$insertData['ready_to_ship'] = $ticket_data->ready_to_ship;
				$insertData['eticket_file'] = $ticket_data->eticket_file;
				$insertData['status'] = $ticket_data->status;
				$insertData['team_support'] = $ticket_data->team_support;
				$insertData['track'] = $ticket_data->track;
				$insertData['sell_type'] = $ticket_data->sell_type;
				$insertData['sell_date'] = date('Y-m-d H:i:s');
				//$insertData['add_by'] = $this->session->userdata('admin_id');
				$insertData['add_by'] = $ticket_data->add_by;
				$insertData['store_id'] = $this->session->userdata('storefront')->admin_id;

				$sellInsert = $this->General_Model->insert_data('sell_tickets', $insertData);
			}

			if ($sellInsert != "") {
				$response = array('status' => 1, 'msg' => 'Ticket Duplicated Successfully.','seller_id' => $ticket_data->add_by,'ticket_last_id' => $sellInsert);
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 0, 'msg' => 'Error while duplicating Ticket.');
				echo json_encode($response);
				exit;
			}
		}
	}

	
	
	
	function get_tktcat_by_stadium_id()
	{

		$block_data = array();
		$block_data = array();
		$block_data_color = array();
		if ($this->input->post('match_id')) {

			$match_id = $this->input->post('match_id');

			$get_mtch					= $this->Tickets_Model->getallMatch_ById($match_id);
			//echo $get_mtch[0]->venue;exit;
			$this->data['tkt_category'] = $this->Tickets_Model->tkt_category($get_mtch[0]->venue);
			//print_r($this->data['tkt_category']);
			if ($this->data['tkt_category']) { //echo $get_mtch[0]->tournament;exit;

				foreach ($this->data['tkt_category'] as $key => $std) {
					/*if($std->stadium_id > 310 ){

					}
					else if(($get_mtch[0]->tournament != 41 && $get_mtch[0]->tournament != 19 && $get_mtch[0]->tournament != 8) && ($std->category == 13 || $std->category == 14 || $std->category == 15 || $std->category == 16)) {
						
						continue;
					}*/
					$block_data[] = array('category' => $std->category,'seat_category' => $std->seat_category);
					//$block_data[$std->category] = $std->seat_category;

					$block_data_color[ $std->block_color] =  $std->seat_category;
				} 
			}
		}
		$response = array('block_data' => $block_data, 'match_data' => $get_mtch[0],'block_data_color' => $block_data_color);
		echo json_encode($response);
		exit;
	}


	function getMatchDetails()
	{
		if ($this->input->post('match_id')) {
			$get_mtch = $this->Tickets_Model->getMatchAdditionalInfo($this->input->post('match_id'));
			//print_r($get_mtch);
			$team_1 = $this->General_Model->get_team_data($get_mtch->team_1)->row();
			$team_2 = $this->General_Model->get_team_data($get_mtch->team_2)->row();
			if($get_mtch->event_type == 'other'){
			if($team_2->team_image != ""){

			 $get_mtch->team1_image = base_url() .'uploads/teams/'. $team_1->team_image;
			 $get_mtch->team2_image = base_url() .'uploads/teams/'. $team_2->team_image;
			 $get_mtch->team1_name = $team_1->team;
			$get_mtch->team2_name = $team_2->team;
			}
			else{
				$get_mtch->event_image = base_url() .'uploads/event_image/'. $get_mtch->event_image;
			}

			}
			else{

			 $get_mtch->team1_image = base_url() .'uploads/teams/'. $team_1->team_image;
			$get_mtch->team2_image = base_url() .'uploads/teams/'. $team_2->team_image;
			$get_mtch->team1_name = $team_1->team;
			$get_mtch->team2_name = $team_2->team;
			}
			
			



			 $stadium_image =  base_url() .$get_mtch->stadium_image;


             //$mapcode = json_decode($get_mtch->stadium_svg,true);

                $StadiumDetails  =$this->db->select('*')
                			->from('stadium_details')
                            ->where('stadium_id',$get_mtch->venue)
                            ->where('source_type','1boxoffice')
                            ->get()->result();


                $regions = array();
                $new_stadium = array();
                foreach($StadiumDetails as $values){

                        $block_id = $values->block_id;
                        $regions = array();
                        $regions['id'] = $block_id;
                        $regions['id_no_spaces'] = $block_id;
                        $regions['fill'] = $values->block_color;
                        $regions['href'] = $values->category;
                        $regions['tooltip'] = $values->category;
                        $regions['data'] = array();
                        $regions['full_block_name'] = $values->full_block_name;
                        $mapcode['regions'][$block_id] = $regions;
                        $new_stadium[] = $regions;
                }
             $mapcode['source'] = $stadium_image;
             if($get_mtch->stadium_type == 2) $mapcode =  $new_stadium ;
            
             $mapcode['source'] = $stadium_image;
             $get_mtch->stadium_svg =  json_encode($mapcode);


             if($get_mtch->stadium_image != ""){
				$get_mtch->stadium_image = base_url() . $get_mtch->stadium_image;
			}
			else{
				$get_mtch->stadium_image = "";
			}

			$data['get_mtch'] = $get_mtch;
			//print_r($data['get_mtch']);
			 $get_mtch->stadium_html = $this->load->view(THEME_NAME.'/tickets/stadium_ajax', $data,true) ;
		} //echo "<pre>";print_r($get_mtch);exit;
		echo json_encode($get_mtch);
		exit;
	}

	function get_block_by_stadium_id()
	{
		
		$block_data = array();
		if ($this->input->post('match_id')) {


			$match_id = $this->input->post('match_id');
			$category_id = $this->input->post('category_id');
			$ticket = $this->input->post('ticket');
			$get_mtch = $this->General_Model->getAllItemTable('match_info', 'm_id', $match_id)->result();

			$condition = array();
			$condition['stadium_id'] = $get_mtch[0]->venue;
			$condition['source_type'] = '1boxoffice';
			if ($category_id) {

				$get_categ = $this->General_Model->getAllItemTable('stadium_seats', 'id', $category_id)->result();
				$condition['category'] = $get_categ[0]->id;
			}
			//echo "<pre>";print_r($condition);exit;
			$get_std = $this->General_Model->getAllItemTable('stadium_details', $condition)->result();
			//echo $this->db->last_query();exit;

			if ($get_std) {
				foreach ($get_std as $std) {
					$block_id = explode("-", $std->block_id);
					$block_data[strtoupper(end($block_id))] = $std->id;
					//$block_data[$std->block_id] = $std->id;

					//$block_data[$std->block_id] = $std->id;
				}
			}
		} //echo "<pre>";print_r($block_data);exit;
		echo json_encode($block_data);
	}


	function getCurrency_event()
	{

		$match_info = $this->General_Model->getAllItemTable('match_info', 'm_id', $this->input->post('match_id'))->result();
		$get_mtch 	= $this->General_Model->get_currency_types('currency_code', $match_info[0]->price_type)->result();
		echo json_encode($get_mtch);
		exit;
	}


	public function create_ticket()
	{

		if ($this->input->post()) {

			//echo "<pre>";print_r($_POST);exit;

			$data = $this->input->post();

			$this->form_validation->set_rules('add_eventname_addlist[]', 'Event Name', 'required');
			$this->form_validation->set_rules('ticket_types[]', 'Ticket Types', 'required');
			$this->form_validation->set_rules('ticket_category[]', 'Ticket Category', 'required');
			/*$this->form_validation->set_rules('ticket_block', 'Ticket Block', 'required');
			$this->form_validation->set_rules('row', 'Ticket row', 'required');*/
			$this->form_validation->set_rules('add_qty_addlist[]', 'Ticket Quantity', 'required');
			$this->form_validation->set_rules('add_pricetype_addlist[]', 'Ticket Currecny', 'required');
			$this->form_validation->set_rules('add_price_addlist[]', 'Ticket Price', 'required');
			$this->form_validation->set_rules('split_type[]', 'Split Type', 'required');

			if ($this->form_validation->run() !== false) {
				$stadium_ids = array();
				$match_prices = array();
				$tournaments = array();
				foreach ($data['add_eventname_addlist'] as $key => $event_selected) {

					$match_info = $this->General_Model->getMatchAdditionalInfo($data['add_eventname_addlist'][$key]);
				 if($key == 0){

				 	$stadium_ids[]  = $match_info->venue;
					$match_prices[] = $match_info->price_type;
					$tournaments[] = $match_info->tournament;

				 }
					if(in_array($match_info->tournament, $tournaments) && in_array($match_info->price_type, $match_prices) && in_array($match_info->venue, $stadium_ids)) {

					

					$ticketid = mt_rand(1000, 9999) . '_' . mt_rand(100000, 999999);
					$ticket_group_id = mt_rand(100000, 999999);
					$ticket_details = "";
					if(isset($data['ticket_details'])){
						$ticket_details = implode(',', $data['ticket_details']);
					}
					
					$insertData = array();	
					$insertData['user_id'] = $this->session->userdata('admin_id');
					$insertData['match_id'] = $data['add_eventname_addlist'][$key];
					$insertData['ticket_type'] = $data['ticket_types'][0];
					$insertData['ticket_type_category'] = $data['ticket_type_category'][$data['ticket_types'][0]];
					$insertData['ticket_category'] = $data['ticket_category'][0];
					$insertData['ticket_block'] = $data['ticket_block'];
					$insertData['row'] = $data['row'];
					$insertData['quantity'] =  $data['add_qty_addlist'][0];
					$insertData['price_type'] = $data['add_pricetype_addlist'][0];
					$insertData['price'] = $data['add_price_addlist'][0];
					$insertData['web_price'] = @$data['add_web_price_addlist'][0];
					$insertData['listing_note'] = $ticket_details;
					$insertData['split'] = $data['split_type'][0];
					$insertData['sell_date'] = date('Y-m-d H:i:s');
					$insertData['pickup_address'] = $data['add_pickup_address_addlist'][0];
					$insertData['team_support'] = $data['add_team_support'][0];
					$insertData['home_town'] = $data['home_town'];
					$insertData['ticketid'] = $ticketid;
					$insertData['ticket_group_id'] = $ticket_group_id;
					$insertData['sell_type'] = 'buy';
					$insertData['track'] = '0';
					$redirect_url = base_url() . 'tickets/index/listing';
					if($_POST['event'] == 'OE'){
						$insertData['event_flag'] = 'OE';
						$redirect_url = base_url() . 'tickets/index/oe_listing';
					}

					$insertData['ready_to_ship'] = 0;
					$insertData['add_by'] = $this->session->userdata('admin_id');
					$insertData['store_id'] = $this->session->userdata('storefront')->admin_id;
					//echo "<pre>"; print_r($insertData);
					$sellInsert = $this->General_Model->insert_data('sell_tickets', $insertData);
					if($sellInsert == true){
						$updates_datas = array(
						'status' => 1
						);

						$update = $this->General_Model->update_table_v2('seller_whish_list', array('match_id' => $data['add_eventname_addlist'][$key],'seller_id' => $this->session->userdata('admin_id')), $updates_datas);

					}
					}
					else{
						$response = array('status' => 0, 'msg' => "Opps.For Some Matches Unable to add tickets.Please Contact Admin.", 'redirect_url' => $redirect_url);
						echo json_encode($response);
						exit;
					}
					

				} 
				$response = array('status' => 1, 'msg' => "New Ticket(s) Created Successfully.", 'redirect_url' => $redirect_url);
			} else {
				$response = array('status' => 0, 'msg' => validation_errors(), 'redirect_url' => base_url() . 'tickets/index/create_ticket');
			}
			echo json_encode($response);
			exit;
		}
	}

	public function loadRecord($rowno = 0, $table, $url, $order_column, $order_by, $view, $variable_name, $type, $search = '')
	{

		// Load Pagination library
		$this->load->library('pagination');

		// Row per page
		$row_per_page = 10;
		// Row position
		if ($table != 'request_tickets') { 
		if ($rowno != 0) {
			$rowno = ($rowno - 1) * $row_per_page;
		}
		}
		// All records count
		/*	echo $table;exit;*/
		 //echo $table;exit;
		if ($table == 'request_tickets') { 
			$row_per_page = 20;
		// Row position
		if ($rowno != 0) { 
			$rowno = ($rowno - 1) * $row_per_page;
		} 
		//echo "request_tickets";exit;
			$allcount = $this->Tickets_Model->ticket_request_by_limit('', '', '', '', '', $search)->num_rows();
			$record = $this->Tickets_Model->ticket_request_by_limit($rowno, $row_per_page, $order_column, $order_by, '', $search)->result();
		}
		else if ($table == 'contact_enquiries') { 
			$allcount = $this->Tickets_Model->contact_details('', '', '', '', '', $search)->num_rows();
			$record = $this->Tickets_Model->contact_details($rowno, $row_per_page, $order_column, $order_by, '', $search)->result();
		}
		else if ($table == 'partner_enquiry') { 
			$allcount = $this->Tickets_Model->partner_enquiry_details('', '', '', '', '', $search)->num_rows();
			$record = $this->Tickets_Model->partner_enquiry_details($rowno, $row_per_page, $order_column, $order_by, '', $search)->result();
		}

		// Pagination Configuration
		$config['base_url'] = base_url() . $url;
		$config['use_page_numbers'] = TRUE;
		$config['total_rows'] = $allcount;
		$config['per_page'] = $row_per_page;
		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] = '</ul>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = ' ';
		$config['next_tag_open'] = '<li><a data-page="next"><i class=" fas fa-angle-right"></a></i>';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = ' ';
		$config['prev_tag_open'] = '<li><a data-page="prev"><i class="fas fa-angle-left"></a></i>';
		$config['prev_tag_close'] = '</li>';
		$config['last_link'] = '>>';
		$config['first_link'] = '<<';
		// Initialize
		$this->pagination->initialize($config);

		$this->data['pagination'] = $this->pagination->create_links();
		$this->data[$variable_name] = $record;
		$this->data['row'] = $rowno;//echo "<pre>";print_r($this->data['pagination']);exit;
		$this->data['search'] = $search;
		// Load view
		$this->load->view($view, $this->data);
	}


	public function update_ticket_data(){

		$ticket_data = $this->Tickets_Model->get_sell_tickets_pending();
		foreach($ticket_data as $ticket){

				$ticketid = mt_rand(1000, 9999) . '_' . mt_rand(100000, 999999);

				$update_data = array(
					'ticketid' => $ticketid
				);
				$update = $this->General_Model->update_table('sell_tickets', 's_no', $ticket->s_no, $update_data);
		} echo "done";exit;
		//echo "<pre>";print_r($ticket_data);exit;
	}

	public function get_expired_tickets(){

		$ticket_data = $this->Tickets_Model->get_expired_tickets()->result();
		//echo "<pre>";print_r($ticket_data);exit;
		foreach($ticket_data as $ticket){
				$update_data = array(
					'status' => 0
				);
				$update = $this->General_Model->update_table('sell_tickets', 's_no', $ticket->s_no, $update_data);
		} echo "Status Updation done";exit;
		//echo "<pre>";print_r($ticket_data);exit;
	}

	public function get_stadium(){

		$stadium_data = $this->General_Model->get_stadium()->result();
		//echo "<pre>";print_r($ticket_data);exit;
		$catgegory = array();
		$catgegory[0] = array('id' => 13,'color' => 'rgba(127,127,127,1)','block' => 1);
		$catgegory[1] = array('id' => 14,'color' => 'rgba(8,59,245,1)','block' => 1);
		$catgegory[2] = array('id' => 15,'color' => 'rgba(72,140,192,1)','block' => 1);
		$catgegory[3] = array('id' => 16,'color' => 'rgba(57,24,250,1)','block' => 1);

		foreach($stadium_data as $stadium){
			foreach($catgegory as $cat){
			//	echo "<pre>";print_r($cat);exit;
			$is_present = $this->General_Model->get_stadium_details($stadium->s_id,$cat['id'])->num_rows();//echo $is_present;exit;
			if($is_present == 0){

				$update_data = array(
					'stadium_id' => $stadium->s_id,
					'category' => $cat['id'],
					'block_color' => $cat['color'],
					'block_id' => $cat['block']
				);//echo "<pre>";print_r($update_data);exit;
				$update = $this->General_Model->insert_data('stadium_details', $update_data);

			}
				
			}
		} echo "Stadium Updation done";exit;
		//echo "<pre>";print_r($ticket_data);exit;
	}


	public function update_auto_disable(){

		$ticket_data = $this->Tickets_Model->getListing_v1($_POST['match_id']);
		if($ticket_data[0]->match_id == $_POST['match_id']){
		
		foreach($ticket_data as $ticket){
				$update_data = array(
					'auto_disable' => $_POST['auto_disable']
				);
				$update = $this->General_Model->update_table_v1('sell_tickets', array('match_id' => $ticket->match_id), $update_data);
		} 

		$response = array('status' => 1, 'msg' => 'Display Hours Updated Successfully.');
					echo json_encode($response);
					exit;
		}
		else{

			$response = array('status' => 0, 'msg' => 'No Tickets available in this match.');
					echo json_encode($response);
					exit;
			
		}

	}



}
