<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);
class Game extends CI_Controller
{
	public function __construct()
	{
		/*
         *  Developed by: Shalini S
         *  Date    : 16 Feb, 2022
         *  1BoxOffice Hub
         *  https://www.1boxoffice.com/
        */
		parent::__construct();
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
		$this->data['languages'] = $this->General_Model->getAllItemTable('language', 'store_id', $this->session->userdata('storefront')->admin_id)->result();
		$this->data['branches'] = $this->General_Model->get_admin_details_by_role(4);
		if ($this->session->userdata('storefront')->company_name == '') {
				$branches = $this->General_Model->get_admin_details(13);
				$sessionUserInfo = array('storefront' => $branches);
				$this->session->set_userdata($sessionUserInfo);
			/*$sessionUserInfo = array('storefront' => $this->data['branches'][count($this->data['branches']) - 1]);*/
		}
		return $this->data;
	}


public function change_ticket_type()
{
	$this->form_validation->set_rules('ticket_type', 'Ticket Type', 'required');
		$this->form_validation->set_rules('bg_id', 'Order Id', 'required');
		if ($this->form_validation->run() !== false) {
		$booking_tickets = $this->General_Model->getAllItemTable('booking_tickets', 'booking_id', $_POST['bg_id'])->row();
		if($booking_tickets->booking_id != ""){

			if($booking_tickets->ticket_type == 3){
				if($_POST['ticket_type'] == 1){
					$response = array('msg' => 'Sorry.You can change Paper tickets to E-ticket or Mobile Ticket Only.', 'status' => 0);
					echo json_encode($response);
					exit;
				}

			} 
			else if($booking_tickets->ticket_type == 2){
				if($_POST['ticket_type'] != 4){
					$response = array('msg' => 'Sorry.You can change E-ticket to Mobile Ticket Only.', 'status' => 0);
					echo json_encode($response);
					exit;
				}
			}
			else if($booking_tickets->ticket_type == 4){
				if($_POST['ticket_type'] != 2){
					$response = array('msg' => 'Sorry.You can Change Mobile Ticket To Only E-ticket.', 'status' => 0);
					echo json_encode($response);
					exit;
				}
			}
		
		$update_data = array('ticket_type' => $_POST['ticket_type']);
		$this->General_Model->update('booking_tickets', array('booking_id' => $_POST['bg_id']), $update_data);

		$response = array('msg' => 'Ticket Type Changed Successfully.', 'status' => 1);
		}
		}
		else {
		$response = array('msg' => validation_errors(), 'status' => 0);
		}
		echo json_encode($response);
		exit;
	echo "<pre>";print_r($_POST);exit;
}

public function request_event()
	{
		$this->form_validation->set_rules('event_name', 'Event Name', 'required');
		$this->form_validation->set_rules('event_location', 'Event location', 'required');
		$this->form_validation->set_rules('event_date', 'Event Date', 'required');
		if ($this->form_validation->run() !== false) {
			/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
			$request_data['seller_id'] 		= $this->session->userdata('admin_id');
			$request_data['event_name'] 	= $_POST['event_name'];
			$request_data['event_location'] = $_POST['event_location'];
			$request_data['event_date'] 	= date('Y-m-d',strtotime($_POST['event_date']));
			//echo "<pre>";print_r($request_data);exit;
			$inserted_id = $this->General_Model->insert_data('seller_request_event', $request_data);
			if($inserted_id != ""){

					$post_data = array("id" => $inserted_id);
					$handle = curl_init();
					$url = API_CRON_URL.'request_ticket_notify';//echo $url;exit;
					curl_setopt($handle, CURLOPT_HTTPHEADER, array(
					'domainkey: https://www.1boxoffice.com/en/'
					));
					curl_setopt($handle, CURLOPT_URL, $url);
					curl_setopt($handle, CURLOPT_POST, 1);
					curl_setopt($handle, CURLOPT_POSTFIELDS,$post_data);
					curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
					$output = curl_exec($handle);
					curl_close($handle);
				

				$response = array('msg' => 'Your Event Request submitted Successfully.Our Concerned team will contact you.', 'status' => 1);
			}
		} else {
		$response = array('msg' => validation_errors(), 'status' => 0);
		}
		echo json_encode($response);
		exit;
		
	}

	public function report_issue()
	{ //echo "report_issue";exit;
		$reports = array('1' => 'Rescheduled Event','2' => 'Incorrect Venue Map','3' => 'Missing Ticket Category','4' => 'Other Issue','5' => 'Cancelled or Postponed Event');
		if($_POST['issue'] != ""){

			$issue = $reports[$_POST['issue']];
			$report_data['seller_id'] = $this->session->userdata('admin_id');
			$report_data['report'] = $_POST['issue'];
			$report_data['order_id'] = @$_POST['report_order_id'];
			$report_data['event_id'] = @$_POST['report_match_id'];
			$report_data['report_text'] = $issue;//echo "<pre>";print_r($report_data);exit;
			$inserted_id = $this->General_Model->insert_data('report_issue', $report_data);

			if($inserted_id != ""){

					$post_data = array("id" => $inserted_id);
					$handle = curl_init();
					$url = API_CRON_URL.'report_issue';//echo $url;exit;
					curl_setopt($handle, CURLOPT_HTTPHEADER, array(
					'domainkey: https://www.1boxoffice.com/en/'
					));
					curl_setopt($handle, CURLOPT_URL, $url);
					curl_setopt($handle, CURLOPT_POST, 1);
					curl_setopt($handle, CURLOPT_POSTFIELDS,$post_data);
					curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
					$output = curl_exec($handle);
					//print_r($output);exit;
					curl_close($handle);
				

				$response = array('msg' => 'Your report issue submitted Successfully.Our Concerned team will contact you.', 'status' => 1);
				echo json_encode($response);
				exit;
			}
			
			


		}
		else{
			$response = array('msg' => 'Oops.unable to submit your issue.', 'status' => 0);
			echo json_encode($response);
			exit;
		}
	}
	/**
	 * @desc Gane category related operations
	 * Add
	 * Edit
	 * List
	 * Delete
	 * Save
	 */
	public function category()
	{

		$segment = $this->uri->segment(3);
		if ($segment == 'add_category') {
			$segment4 = $this->uri->segment(4);
			if ($segment4 != "") {
				$edit_cat_id = $segment4;
				$this->data['category_details'] = $this->General_Model->get_game_category_data($edit_cat_id)->row();
			}
			$this->load->view('game/add_category', $this->data);
		} else if ($segment == 'list_category') {
			$row_count = $this->uri->segment(4);
			if ($this->input->post('submit') != NULL) {
				$search_text = $this->input->post('search');
				$this->session->set_userdata(array("searchc" => $search_text));
			} else {
				if ($this->session->userdata('searchc') != NULL) {
					$search_text = $this->session->userdata('searchc');
				}
			}
			$this->loadRecord($row_count, 'game_category', 'game/category/list_category', 'id', 'DESC', 'game/category_list', 'categories', 'gamecategory', $search_text);
		} else if ($segment == 'delete_category') {
			$segment4 = $this->uri->segment(4);
			$delete_id = $segment4;
			$delete = $this->General_Model->delete_data('game_category', 'id', $delete_id);
			if ($delete == 1) {
				$this->General_Model->delete_data('game_category_lang', 'game_cat_id', $delete_id);
				$response = array('status' => 1, 'msg' => 'Game category deleted Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error while deleting game category.');
				echo json_encode($response);
				exit;
			}
		} else if ($segment == 'save_category') {
			$this->form_validation->set_rules('category_name', 'Category Name', 'required');
			$this->form_validation->set_rules('status', 'Category Status', 'required');

			if ($this->form_validation->run() !== false) {
				$insert_data = array(
					'category_name' => $_POST['category_name'],
					'status' => $_POST['status'],
					'add_by' => $this->session->userdata('admin_id'),
					'create_date' => date("Y-m-d H:i:s")
				);
				if ($_POST['category_id'] == '') {
					$inserted_id = $this->General_Model->insert_data('game_category', $insert_data);
					if ($inserted_id) {
						$lang = $this->General_Model->getAllItemTable('language', 'store_id', $this->session->userdata('storefront')->admin_id)->result();
						foreach ($lang as $key => $l_code) {
							$language_data = array(
								'language' =>  $l_code->language_code,
								'game_cat_id' => $inserted_id,
								'category_name' => $_POST['category_name']
							);
							$this->General_Model->insert_data('game_category_lang', $language_data);
						}
						$response = array('msg' => 'New Game Category Created Successfully.', 'redirect_url' => base_url() . 'game/category/list_category', 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to create new game category.', 'redirect_url' => base_url() . 'game/category/add_category', 'status' => 0);
					}
					echo json_encode($response);
					exit;
				} else {
					$category_id = $_POST['category_id'];

					if ($this->General_Model->update_table('game_category', 'id', $category_id, $insert_data)) {
						$language_data = array(
							'category_name' => $_POST['category_name']
						);
						$this->General_Model->update('game_category_lang', array('game_cat_id' => $category_id, 'language' => $this->session->userdata('language_code')), $language_data);

						$response = array('msg' => 'Game category details updated Successfully.', 'redirect_url' => base_url() . 'game/category/list_category', 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to update game category details.', 'redirect_url' => base_url() . 'game/category/add_category/' . $category_id, 'status' => 0);
					}
					echo json_encode($response);
					exit;
				}
			} else {
				$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'game/category/add_category', 'status' => 0);
			}
			echo json_encode($response);
			exit;
		}
	}

	/**
	 * Получение IP пользователя
	 * 
	 * @return string
	 */
	public function getUserIP()
	{
		$client = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote = $_SERVER['REMOTE_ADDR'];

		if (filter_var($client, FILTER_VALIDATE_IP)) {
			$ip = $client;
		} elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
			$ip = $forward;
		} else {
			$ip = $remote;
		}

		return $ip;
	}

	public function saveNominee()
	{
		$msg = '';
		$ticketId = $this->input->post('ticket_id');
		$bookingId = $this->input->post('booking_id');
		$bookingNo = $this->input->post('booking_no');

		if ($_POST["first_name"][0] != '') {
			for ($count = 0; $count < count($_POST["first_name"]); $count++) {


				$this->db->where(array('ticket_id' => $ticketId, 'booking_id' => $bookingId, 'serial' => $count + 1));
				$query = $this->db->get('booking_etickets');
				$resultTest = $query->row();
				if (!empty($resultTest)) {

					$updateData['first_name'] = $_POST["first_name"][$count];
					$updateData['last_name'] = $_POST["last_name"][$count];
					$updateData['nationality'] = $_POST["nationality"][$count];
					$updateData['dob'] 			= $_POST["dob"][$count];

					$this->General_Model->update_table('booking_etickets', 'id', $resultTest->id, $updateData);
				}
			}
		}

		$response = array('status' => 1, 'msg' => 'Nominee added successfully.' . $msg, 'redirect_url' => base_url() . '/game/orders/details/' . md5($bookingNo));
		echo json_encode($response);
		exit;
	}


	public function upload_single_ticket()
	{ //echo "<pre>";print_r($_POST);exit;
		$msg = '';
		$ticketId = $_POST['ticketid'];
		$config["upload_path"] = './uploads/e_tickets/';
		$config["allowed_types"] = 'pdf';
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if ($_FILES["eticket"]["name"] != '') { 
			$file_ext = pathinfo($_FILES["eticket"]["name"], PATHINFO_EXTENSION);
			$_FILES["file"]["name"] =  $ticketId .'.'. $file_ext;
			$_FILES["file"]["type"] = $_FILES["eticket"]["type"];
			$_FILES["file"]["tmp_name"] = $_FILES["eticket"]["tmp_name"];
			$_FILES["file"]["error"] = $_FILES["eticket"]["error"];
			$_FILES["file"]["size"] = $_FILES["eticket"]["size"];

			
				if ($this->upload->do_upload('file')) {
					$data = $this->upload->data();
					//$insertData['ticket_file'] = $data["file_name"];
					$this->db->where(array('ticketid' => $ticketId));
					$query = $this->db->get('booking_etickets');
					$resultTest = $query->row();
					$bookingId  = $resultTest->booking_id;
					//echo "<pre>";print_r($resultTest);exit;
					if (!empty($resultTest)) {
						unlink('./uploads/e_tickets/' . $resultTest->ticket_file);
						$updateData['ticket_file'] = $data["file_name"];
						$updateData['ticket_upload_date'] = date("Y-m-d h:i:s");
						$updateData['ticket_status'] = 1;
						$done = $this->General_Model->update_table('booking_etickets', 'id', $resultTest->id, $updateData);
					}

					if($done == true){

					$status = '1';
					$updateData = array('delivery_status' => $status);
					$cond = array('bg_id' => $bookingId);
					$this->General_Model->update('booking_global', $cond, $updateData);
					$msg = 'E-tickets added successfully.';
					}
				} else {
					$msg = 'Failed to add e-ticket';
				}

		}

		$response = array('status' => 1, 'msg' => $msg);
		echo json_encode($response);
		exit;
	}

	public function saveEticket()
	{
		$msg = '';
		$ticketId = $this->input->post('ticket_id');
		$bookingId = $this->input->post('booking_id');
		$bookingNo = $this->input->post('booking_no');
		$config["upload_path"] = './uploads/e_tickets/';
		$config["allowed_types"] = 'pdf';
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if ($_FILES["eticket"]["name"] != '') {
			for ($count = 0; $count < count($_FILES["eticket"]["name"]); $count++) {
				$insertData = array();
				$_FILES["file"]["name"] =  time() . $_FILES["eticket"]["name"][$count];
				$_FILES["file"]["type"] = $_FILES["eticket"]["type"][$count];
				$_FILES["file"]["tmp_name"] = $_FILES["eticket"]["tmp_name"][$count];
				$_FILES["file"]["error"] = $_FILES["eticket"]["error"][$count];
				$_FILES["file"]["size"] = $_FILES["eticket"]["size"][$count];
				$insertData['booking_id'] = $bookingId;
				$insertData['ticket_id'] = $ticketId;
				$insertData['serial'] = $count + 1;

				if ($this->upload->do_upload('file')) {
					$data = $this->upload->data();
					$insertData['ticket_file'] = $data["file_name"];
					$this->db->where(array('ticket_id' => $ticketId, 'booking_id' => $bookingId, 'serial' => $count + 1));
					$query = $this->db->get('booking_etickets');
					$resultTest = $query->row();
					if (!empty($resultTest)) {
						unlink('./uploads/e_tickets/' . $resultTest->ticket_file);
						$updateData['ticket_file'] = $data["file_name"];
						$updateData['ticket_upload_date'] = date("Y-m-d h:i:s");
						$updateData['ticket_status'] = 1;
						$done = $this->General_Model->update_table('booking_etickets', 'id', $resultTest->id, $updateData);
					} else {

						$done =  $this->General_Model->insert_data('booking_etickets', $insertData);
						if($done != ''){
							$done = true;
						}
					}

					if($done == true){

					$status = '1';
					$updateData = array('delivery_status' => $status);
					$cond = array('bg_id' => $bookingId);
					$this->General_Model->update('booking_global', $cond, $updateData);

					}
				} else {
					$msg .= 'Failed to add e-ticket #' . intval(intval($count) + 1);
				}
			}
		}

		$response = array('status' => 1, 'msg' => 'E-tickets added successfully.' . $msg, 'redirect_url' => base_url() . '/game/orders/details/' . md5($bookingNo));
		echo json_encode($response);
		exit;
	}

	public function uploadNominee($orderId)
	{
		$this->data['orderData'] =  $this->General_Model->getOrderData($orderId);
		$this->data['eticketData'] = 		$this->General_Model->getAllItemTable_Array('booking_etickets', array('booking_id' => $this->data['orderData']->bg_id, 'ticket_id' => $this->data['orderData']->bt_id))->result();
		//	print_r($this->data['eticketData']);
		$this->load->view('game/upload_nominee', $this->data);
	}

	public function uploadEticket($orderId)
	{
		$this->data['orderData'] =  $this->General_Model->getOrderData($orderId);
		$this->data['orderData']->team1_image = base_url() .'uploads/teams/'. $this->data['orderData']->team_image_a;
		$this->data['orderData']->team2_image = base_url() .'uploads/teams/'. $this->data['orderData']->team_image_b;
		$this->data['eticketData'] = 		$this->General_Model->getAllItemTable_Array('booking_etickets', array('booking_id' => $this->data['orderData']->bg_id, 'ticket_id' => $this->data['orderData']->bt_id))->result();
		if($this->data['orderData']->listing_note != ''){
				$this->data['seller_notes'] = 		$this->General_Model->get_seller_notes($this->data['orderData']->listing_note);

			}
			//echo "<pre>";print_r($this->data['eticketData']);exit;
		$this->load->view('game/upload_eticket', $this->data);
	}

	public function deleteEticket()
	{

		$segment4 = $this->uri->segment(3);


		$order = $this->General_Model->getAllItemTable_Array('booking_etickets', array('id' => $segment4))->row();

		if ($segment4 != '')
			$insertData = array('ticket_file' => '', 'ticket_status' => 0,'ticket_upload_date' => '');
		$delete = $this->General_Model->update('booking_etickets', array('id' => $segment4), $insertData);
		if ($delete == 1) {
			$status = '0';
			$updateData = array('delivery_status' => $status);
			$cond = array('bg_id' => $order->booking_id);
			$this->General_Model->update('booking_global', $cond, $updateData);
			$response = array('status' => 1, 'msg' => 'Ticket Deleted Successfully.');
			echo json_encode($response);
			exit;
		} else {
			$response = array('status' => 1, 'msg' => 'Error while deleting Ticket.');
			echo json_encode($response);
			exit;
		}
	}



	function currencyConverterMap2($convert_amount, $currency_from, $currency_to)
	{

		$CI = &get_instance();
		$exchange_price = $CI->General_Model->getAllItemTable_Array('exchange_rates', array('currencyto' => strtoupper($currency_from) . '_' . strtoupper($currency_to)))->result();

		if ($exchange_price) {
			$exchange_price = $exchange_price[0]->price;
		} else {
			$exchange_price = 1.00;
		}
		$conversion_rate = (float) $exchange_price;


		$currency = $CI->General_Model->getAllItemTable_Array('currency_types', array('currency_code' => strtoupper($currency_to)))->result();
		if ($currency) {
			$currency_symbol = $currency[0]->symbol;
		}


		return str_replace(',', '', number_format($convert_amount * $conversion_rate, 2));
	}

	public function download_tickets($booking_no){
		//echo $booking_no;exit;
		$this->data['orderData'] =  $this->General_Model->getOrderData($booking_no);
		$eticketData             = 	$this->General_Model->get_download_tickets(array('booking_id' => $this->data['orderData']->bg_id, 'ticket_id' => $this->data['orderData']->bt_id))->result();

        $createdzipname = $this->data['orderData']->booking_no.'_Tickets';

        $this->load->library('zip');
        $this->load->helper('download');

        // create new folder 
        //$this->zip->add_dir('uploads/tickets_zip');

        foreach ($eticketData as $file) {

            $paths = base_url().'uploads/e_tickets/'.$file->ticket_file;
            // add data own data into the folder created
            $this->zip->add_data($file->ticket_file,file_get_contents($paths));    
        }
        $this->zip->download($createdzipname.'.zip');
    }

	/**
	 * Orders related operations
	 * Add
	 * List
	 * Edit
	 * Delete
	 */
	public function orders()
	{
		$idiom = $this->session->get_userdata('language');
		// print_r($idiom); exit;
		$this->lang->load('message', 'english');
		$segment = $this->uri->segment(3);
		$this->data['customers_list'] = $this->General_Model->getAllItemTable('register')->result();
		$this->data['events_list'] = $this->General_Model->get_event()->result();
		if ($segment == 'list_order') { 

			$row_count = $this->uri->segment(5);
			$flag = $this->uri->segment(4);//echo $flag;exit;
			if($flag == "completed"){ 
			 $this->loadRecord($row_count, 'booking_global', 'game/orders/list_order/'.$flag, 'id', 'DESC', THEME_NAME.'/game/list_completed_order', 'getMySalesData', 'orders',$_POST);
			}
			else{
				$this->loadRecord($row_count, 'booking_global', 'game/orders/list_order/'.$flag, 'id', 'DESC', THEME_NAME.'/game/list_order', 'getMySalesData', 'orders',$_POST);
			}

		}
		else if ($segment == 'load_side_filter') {
			$row_count = 0;
			$this->loadRecord($row_count, 'booking_global', 'game/orders/list_order/all', 'id', 'DESC', THEME_NAME.'/game/list_order', 'getMySalesData', 'load_side_filter',$_POST);
		}
		else if ($segment == 'completed_order') {

			
			$row_count = $this->uri->segment(4);
			$flag = $this->uri->segment(3);
			$this->loadRecord($row_count, 'booking_global', 'game/orders/list_order/'.$flag, 'id', 'DESC', THEME_NAME.'/game/list_completed_order', 'getMySalesData', 'orders',$_POST);

		}
		else if ($segment == 'get_ajax_orders') { 
			$row_count = 1;
			if($_POST['page'] != ''){
				$row_count = $_POST['page'];
			}
			
			$flag = 'all';
			if($_POST['filter'] == 'completed'){
			$_POST['flag']  = 'completed';
			} 
			else{
				$_POST['flag']  = 'all';
			} 
			$this->loadRecord($row_count, 'booking_global', 'game/orders/list_order/'.$flag, 'id', 'DESC', THEME_NAME.'/game/list_order_ajax', 'getMySalesData', 'ajax_orders',$_POST);

		}
		else if ($segment == 'get_order_search') {
			$row_count = 1;
			//$flag = 'all';
			if($_POST['filter'] == 'completed'){
			$flag  = 'completed';
			} 
			else{
				$flag  = 'all';
			}
			$this->loadRecord($row_count, 'booking_global', 'game/orders/list_order/'.$flag, 'id', 'DESC', THEME_NAME.'/game/list_order_ajax', 'getMySalesData', 'get_order_search',$_POST);

		}
		 else if ($segment == 'ticket_delivery') {
			$segment4 = $this->uri->segment(4);
			$this->data['getMySalesData'] = $this->General_Model->ticket_delivery($segment4);
			$this->load->view('game/ticket_delivery', $this->data);
		} else if ($segment == 'payment_history') {
			$this->data['getMySalesData'] = $this->General_Model->payment_history();
			$this->load->view('game/payment_history', $this->data);
		} else if ($segment == 'my_sales') { 
			$segment4 = $this->uri->segment(4);
			if ($segment4 == '') {
				$segment4 = "upcoming";
			}
			$segment5 = $this->uri->segment(5);
			$this->data['sellers'] = $this->General_Model->get_sellers();
			if ($segment5 == "load_sales") {
				if ($this->uri->segment(6)) {
					$rowno = ($this->uri->segment(6));
				} else {
					$rowno = 0;
				}
				$rowperpage = 10;
				$where = array();
				if($_POST['seller_id'] != ''){
					$where['seller_id']=$_POST['seller_id'];
				}
				

				// Row position
				if ($rowno != 0) {
					$rowno = ($rowno - 1) * $rowperpage;
				}
				$allcount =   $this->General_Model->my_sales_V1_filter($segment4, '', '', '', '', '',$where)->num_rows();
				$mysales =  $this->General_Model->my_sales_V1_filter($segment4, '', '', '', '', '',$where)->result();
				$sales_data = array();
				foreach ($mysales as $mysale) {
					$total_quantity = 0;
					$available_tickets = 0;
					$download_tickets = 0;
					$match_id = $mysale->match_id;
					$mysales_tickets  =  $this->General_Model->my_ticket_quantity($match_id)->row();
					$myorders_tickets =  $this->General_Model->my_orders_quantity($match_id)->row();
					$pending_tickets  =  $this->General_Model->my_orders_pending_tickets($match_id, 'notuploaded')->num_rows();
					//$uploaded_tickets  =  $this->General_Model->my_orders_pending_tickets($match_id,'uploaded')->num_rows();
					//$available_tickets  =  $this->General_Model->my_orders_pending_tickets($match_id,'available')->num_rows();
					$available_tickets  =  $this->General_Model->my_orders_pending_tickets($match_id, 'available')->num_rows(); //echo $this->db->last_query();
					$download_tickets  =  $this->General_Model->my_orders_pending_tickets($match_id, 'download')->num_rows();

					//echo "pending_tickets <pre>";print_r($pending_tickets);exit;

					if ($mysales_tickets->total_quantity) {
						$total_quantity = $mysales_tickets->total_quantity;
					}
					$mysale->ticket_quantity = $total_quantity;

					$sold_quantity = 0;
					if ($myorders_tickets->sold_quantity) {
						$sold_quantity = $myorders_tickets->sold_quantity;
					}
					$mysale->sold_quantity = $sold_quantity;

					$mysale->pending_quantity  = $pending_tickets;
					//$mysale->uploaded_quantity = $uploaded_tickets;
					$mysale->available_tickets = $available_tickets;
					$mysale->download_tickets = $download_tickets;


					$sales_data[] = $mysale;
				} 
				$this->data['segment'] = $segment4;
				$this->data['getMySalesData'] = $sales_data;
				$this->load->library('pagination');
				// Pagination Configuration
				$config['base_url'] = base_url() . 'orders/my_sales/expired/load_sales/';
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
				$this->data['pagination'] = '<div class="pagination datatable-pagination pagination-datatables flex-column" id="pagination">' . $this->pagination->create_links() . '</div>';

				$list_tickets = $this->load->view('game/my_sales_ajax', $this->data, TRUE);
				if (empty($sales_data)) {
					$list_tickets = $this->load->view('errors/nofound', $this->data, TRUE);
				}
				$response = array('segment' => $segment4, 'sales' => $list_tickets);
				echo json_encode($response);
				exit;
			} else if ($segment5 == "filter_sales") {
				if ($this->uri->segment(6)) {
					$rowno = ($this->uri->segment(6));
				} else {
					$rowno = 0;
				}
				$rowperpage = 10;

				// Row position
				if ($rowno != 0) {
					$rowno = ($rowno - 1) * $rowperpage;
				}
				$event 					= $_POST['event'];
				$ticket_category 		 = $_POST['ticket_category'];
				$stadium 				 = $_POST['stadium'];
				$event_start_date = $_POST['event_start_date'];
				$event_end_date 		= $_POST['event_end_date'];
				$ignore_end_date 		 = $_POST['ignore_end_date'];

				if ($ignore_end_date == 1) {
					$event_end_date = '';
				}
				$where['event_search']=$event;
				$where['ticket_category_search']=$ticket_category;
				$where['stadium_search']=$stadium;
				$where['start_date']=$event_start_date;
				$where['end_date']=$event_end_date;

				$allcount =   $this->General_Model->my_sales_V1_filter($segment4, '', '', '', '', '',$where)->num_rows();


				$mysales =  $this->General_Model->my_sales_V1_filter($segment4, '', '', '', '', '',$where)->result();
				$sales_data = array();
				foreach ($mysales as $mysale) {
					$total_quantity = 0;
					$available_tickets = 0;
					$download_tickets = 0;
					$match_id = $mysale->match_id;
					$mysales_tickets  =  $this->General_Model->my_ticket_quantity($match_id)->row();
					$myorders_tickets =  $this->General_Model->my_orders_quantity($match_id)->row();
					$pending_tickets  =  $this->General_Model->my_orders_pending_tickets($match_id, 'notuploaded')->num_rows();
					//$uploaded_tickets  =  $this->General_Model->my_orders_pending_tickets($match_id,'uploaded')->num_rows();
					//$available_tickets  =  $this->General_Model->my_orders_pending_tickets($match_id,'available')->num_rows();
					$available_tickets  =  $this->General_Model->my_orders_pending_tickets($match_id, 'available')->num_rows(); //echo $this->db->last_query();
					$download_tickets  =  $this->General_Model->my_orders_pending_tickets($match_id, 'download')->num_rows();

					//echo "pending_tickets <pre>";print_r($pending_tickets);exit;

					if ($mysales_tickets->total_quantity) {
						$total_quantity = $mysales_tickets->total_quantity;
					}
					$mysale->ticket_quantity = $total_quantity;

					$sold_quantity = 0;
					if ($myorders_tickets->sold_quantity) {
						$sold_quantity = $myorders_tickets->sold_quantity;
					}
					$mysale->sold_quantity = $sold_quantity;

					$mysale->pending_quantity  = $pending_tickets;
					//$mysale->uploaded_quantity = $uploaded_tickets;
					$mysale->available_tickets = $available_tickets;
					$mysale->download_tickets = $download_tickets;


					$sales_data[] = $mysale;
				}
				$this->data['segment'] = $segment4;
				$this->data['getMySalesData'] = $sales_data;
				$this->load->library('pagination');
				// Pagination Configuration
				$config['base_url'] = base_url() . 'orders/my_sales/expired/load_sales/';
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
				$this->data['pagination'] = '<div class="pagination datatable-pagination pagination-datatables flex-column" id="pagination">' . $this->pagination->create_links() . '</div>';

				$list_tickets = $this->load->view('game/my_sales_ajax', $this->data, TRUE);
				if (empty($sales_data)) {
					$list_tickets = $this->load->view('errors/nofound', $this->data, TRUE);
				}
				$response = array('segment' => $segment4, 'sales' => $list_tickets);
				echo json_encode($response);
				exit;
			} else {
				$_SESSION['event'] =  '';
				$_SESSION['ticket_category'] = '';
				$_SESSION['stadium'] =  '';
				$_SESSION['event_start_date']	= '';
				$_SESSION['event_end_date'] = '';
				$_SESSION['ignore_end_date'] = '';
				$this->load->view('game/my_sales', $this->data);
			}
		} else if ($segment == 'my_sales_details') {
			$segment4 = $this->uri->segment(4);
			$this->data['event'] = $this->General_Model->my_sales_details($segment4);
			$myorders_tickets =  $this->General_Model->my_orders_quantity($segment4)->row();
			$mysales_tickets  =  $this->General_Model->my_ticket_quantity($segment4)->row();
			$ticket_categories  =  $this->General_Model->my_orders_details($segment4)->result();
			$all_ticket = array();
			foreach ($ticket_categories as $ticket_category) {
				//	echo $ticket_category->ticket_category;
				$available_tickets = 0;
				$download_tickets = 0;
				$pending_tickets = 0;
				$available_tickets  =  $this->General_Model->my_orders_pending_tickets_v1($ticket_category->match_id, 'available', $ticket_category->ticket_category)->num_rows();
				$download_tickets  =  $this->General_Model->my_orders_pending_tickets_v1($ticket_category->match_id, 'download', $ticket_category->ticket_category)->num_rows();
				$pending_tickets  =  $this->General_Model->my_orders_pending_tickets_v1($ticket_category->match_id, 'notuploaded', $ticket_category->ticket_category)->num_rows();
				$ticket_category->available_tickets = $available_tickets;
				$ticket_category->download_tickets = $download_tickets;
				$ticket_category->pending_tickets = $pending_tickets;
				$all_ticket[] = $ticket_category;
				//echo $this->db->last_query();
				//echo $available_tickets;
				//echo "<br>";

			}  //echo "<pre>";print_r($myorders_tickets);exit;
			$this->data['ticket_categories'] = $all_ticket;

			$this->data['event']->ticket_quantity = $mysales_tickets->total_quantity;
			$this->data['event']->sold_quantity = $myorders_tickets->sold_quantity;
			$this->data['getMySalesData'] = $this->General_Model->getOrders($segment4);
			//echo "<pre>";print_r($this->data['event']);exit; 
			$this->load->view('game/my_sales_details', $this->data);
		} else if ($segment == 'update_booking_status') {


			$order = $this->General_Model->getAllItemTable_Array('booking_global', array('md5(bg_id)' => $_POST['bg_id']))->row();
			if ($_POST['bg_id'] != "" && $_POST['status'] != "" && $order->bg_id != "") {

				$status = $_POST['status'];
				if ($status == 1) {
					$booking_confirmation_no = "TK" . str_pad($order->bg_id, 7, '0', STR_PAD_LEFT);
					$updateData = array('booking_status' => $status, 'booking_confirmation_no' => $booking_confirmation_no, 'updated_at' => date("Y-m-d h:i:s"));
				} else {
					$updateData = array('booking_status' => $status, 'booking_confirmation_no' => '', 'updated_at' => date("Y-m-d h:i:s"));
				}
				$cond = array('md5(bg_id)' => $_POST['bg_id']);

				$this->General_Model->update('booking_global', $cond, $updateData);

				if($_POST['mail_enable'] == 1){

					$handle = curl_init();
					$url = API_MAIL_URL.$order->bg_id;
					curl_setopt($handle, CURLOPT_HTTPHEADER, array(
					'domainkey: https://www.1boxoffice.com/en/'
					));
					curl_setopt($handle, CURLOPT_URL, $url);
					curl_setopt($handle, CURLOPT_POST, 1);
					curl_setopt($handle, CURLOPT_POSTFIELDS,
					"email_notify=notify");
					curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
					$output = curl_exec($handle);
					curl_close($handle);
					//echo "<pre>";print_r($output);exit;
					//echo $url;exit;
				}
				//echo $this->db->last_query();exit;
				$response = array('status' => 1, 'msg' => "Success.Booking Status Updated Successfully.");
			} else {
				$response = array('status' => 0, 'msg' => "Failed to update booking status.Invalid order id.");
			}
			echo json_encode($response);
			exit;
		} else if ($segment == 'update_ticket_status') {


			$order = $this->General_Model->getAllItemTable_Array('booking_etickets', array('md5(id)' => $_POST['ticket_id']))->row();
			if ($_POST['ticket_id'] != "" && $_POST['status'] != "" && $order->id != "") {

				$status = $_POST['status'];
				$updateData = array('ticket_status' => $status, 'reject_reason' => $_POST['reason']);
				$cond = array('md5(id)' => $_POST['ticket_id']);

				$done = $this->General_Model->update('booking_etickets', $cond, $updateData);
				if($done == true){
					if($status == 2){
						$ticket_status = '2';
					}
					else if($status == 6){
						$ticket_status = '3';
					}
					
					$updateData = array('delivery_status' => $ticket_status);
					$cond = array('bg_id' => $order->booking_id);
					$this->General_Model->update('booking_global', $cond, $updateData);

				}
				//echo $this->db->last_query();exit;
				$response = array('status' => 1, 'msg' => "Success.E-Ticket Status Updated Successfully.");
			} else {
				$response = array('status' => 0, 'msg' => "Failed to update E-Ticket status.Invalid Ticket id.");
			}
			echo json_encode($response);
			exit;
		} else if ($segment == 'abandoned') {
			$row_count = $this->uri->segment(5);
			$flag = $this->uri->segment(4);
			/*$this->data['getMySalesData'] = $this->General_Model->abondaned()->result();
			$this->load->view('game/abandoned', $this->data);*/

			$this->loadRecord($row_count, 'booking_global', 'game/orders/abandoned/'.$flag, 'bg_id', 'DESC', 'game/abandoned', 'getMySalesData', 'abandoned', $flag);

		} else if ($segment == 'abondaned_details') {
			$segment4 = $this->uri->segment(4);
			$this->data['orderData'] = $this->General_Model->abondaned($segment4)->row();
			if($this->data['orderData']->listing_note != ''){
				$this->data['seller_notes'] = 		$this->General_Model->get_seller_notes($this->data['orderData']->listing_note);

			} //echo "<pre>";print_r($this->data['seller_notes']);
			//exit;
			$this->load->view('game/abondaned_details', $this->data);
		} else if ($segment == 'payment_details') {
			$segment4 = $this->uri->segment(4);
			$this->data['orderData'] =  $this->General_Model->getOrderData($segment4);
			$this->load->view('game/payment_details', $this->data);
		} 
		else if ($segment == 'save_nominee') {

			$msg = '';
			// echo "<pre>";print_r($_POST);
			// echo "<pre>";print_r($_FILES);exit;
			$bookingId = $this->input->post('booking_id');
			$done = false;
		if ($_POST["delivery_provider"] != "") {  	

			$this->db->where(array('booking_id' => $bookingId));
			$query = $this->db->get('booking_ticket_tracking');
			$delivery = $query->row();

			$config["upload_path"] = './uploads/pod/';
			$config["allowed_types"] = 'pdf|gif|jpg|png|jpeg';
			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			if ($_FILES["pod_file"]["name"] != '') { 
				$podId = time();
				$file_ext = pathinfo($_FILES["pod_file"]["name"], PATHINFO_EXTENSION);
				$_FILES["file"]["name"] =  $podId .'.'. $file_ext;
				$_FILES["file"]["type"] = $_FILES["pod_file"]["type"];
				$_FILES["file"]["tmp_name"] = $_FILES["pod_file"]["tmp_name"];
				$_FILES["file"]["error"] = $_FILES["pod_file"]["error"];
				$_FILES["file"]["size"] = $_FILES["pod_file"]["size"];

			
				if ($this->upload->do_upload('file')) {
					$data = $this->upload->data();

					if (!empty($delivery)) {
						unlink('./uploads/pod/' . $delivery->pod);
					}
						$tracking_updateData['pod'] = $data["file_name"];
						$tracking_updateData['tracking_date_time'] = date("Y-m-d h:i:s");
					

			}
			} 

			$tracking_updateData['delivery_provider'] = $_POST["delivery_provider"];
			$tracking_updateData['tracking_number'] = $_POST["tracking_number"];
			$tracking_updateData['tracking_link'] = $_POST["tracking_link"];
			$tracking_updateData['pod_status'] = 1;
			$delivery_status = '1';

			//echo "<pre>";print_r($tracking_updateData);exit;

			if (!empty($delivery)) { 
				$ticket_delivery = 1;
				$update_tickt = $this->General_Model->update_table('booking_ticket_tracking', 'booking_id', $bookingId, $tracking_updateData);

				$this->db->where(array('booking_id' => $bookingId));
				$query = $this->db->get('booking_etickets');
				$results = $query->result();

				if (!empty($results)) {
					foreach($results as $ticket){ 
						//$updateData['ticket_status'] 	= 2;
						$updateData['ticket_status'] 	= 1;
						$done = true;
						//$delivery_status = '2';
						$this->General_Model->update_table('booking_etickets', 'id', $ticket->id, $updateData);
					}
					
				} //echo "sss";exit;

			}
			else{
				$tracking_updateData['booking_id'] = $_POST["booking_id"];
				//echo "<pre>";print_r($tracking_updateData);exit;
					$insert = $this->General_Model->insert_data('booking_ticket_tracking', $tracking_updateData);
					$this->db->where(array('booking_id' => $_POST["booking_id"]));
					$query = $this->db->get('booking_etickets');
					$results = $query->result();

					if (!empty($results)) {

					foreach($results as $ticket){
					$updateData['ticket_status'] 	= 1;
					$done = true;
					//$delivery_status = '2';
					$update_tickt = $this->General_Model->update_table('booking_etickets', 'id', $ticket->id, $updateData);
					}

					}
			}

		}

		if ($_POST["nominee"][0] != '') { 

			$config["upload_path"] = './uploads/e_tickets/';
			$config["allowed_types"] = 'pdf|gif|jpg|png|jpeg';
			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			for ($count = 0; $count < count($_POST["nominee"]); $count++) {

				$name = explode(' ',trim($_POST['nominee'][$count]));
				$this->db->where(array('id' => $_POST['ticket_id'][$count], 'booking_id' => $bookingId, 'serial' => $count + 1));
				$query = $this->db->get('booking_etickets');
				$resultTest = $query->row();

				if (!empty($resultTest)) {
					$ticket_attendees = 1;
					$updateData['first_name'] 	= $name[0];
					$updateData['last_name'] 	= $name[1];
					$updateData['email'] 		= $_POST["email"][$count];
					//$updateData['nationality'] 	= $_POST["nationality"][$count];
					//$updateData['dob'] 			= $_POST["dob"][$count];

					$temp_files =  $this->General_Model->getAllItemTable_Array('ticket_temp_file', array('ticket_id' => $bookingId,'ticket_sort' => $count),'','ticket_sort','ASC')->row();
					//echo "<pre>";print_r($temp_files);die;
					if($temp_files->id != ""){
					// echo $this->db->last_query();
					// print_r($temp_files);

					$ticketId = time()+($count + 1);
					$updateData['ticket_file'] = $temp_files->ticket_filename;
					$updateData['ticketid'] = $ticketId;
					$updateData['ticket_upload_date'] = date("Y-m-d h:i:s");
					$updateData['ticket_status'] = 1;
					$delivery_status = '1';
					$done = true;
					$ticket_etickets = 1;
					}
					else{
						$delivery_status = '0';
						$updateData['ticket_file'] = "";
						$updateData['ticket_status'] = 0;
					}
			
					//  if(!empty($_FILES["eticket"]["name"][$count])){
				
					// 	if ($_FILES["eticket"]["name"][$count] != '') { 
					// 		$ticketId = time();
					// 		$file_ext = pathinfo($_FILES["eticket"]["name"][$count], PATHINFO_EXTENSION);
					// 		$_FILES["file"]["name"] =  $ticketId .'.'. $file_ext;
					// 		$_FILES["file"]["type"] = $_FILES["eticket"]["type"][$count];
					// 		$_FILES["file"]["tmp_name"] = $_FILES["eticket"]["tmp_name"][$count];
					// 		$_FILES["file"]["error"] = $_FILES["eticket"]["error"][$count];
					// 		$_FILES["file"]["size"] = $_FILES["eticket"]["size"][$count];

							
					// 		if ($this->upload->do_upload('file')) {
					// 			$data = $this->upload->data();

					// 			if (!empty($resultTest)) {
					// 				unlink('./uploads/e_tickets/' . $resultTest->ticket_file);
					// 				$updateData['ticket_file'] = $data["file_name"];
					// 				$updateData['ticketid'] = $ticketId;
					// 				$updateData['ticket_upload_date'] = date("Y-m-d h:i:s");
					// 				$updateData['ticket_status'] = 1;
					// 				$delivery_status = '1';
					// 				$done = true;
					// 			}

					// 		}
					// 	}
					// }
					//echo "<pre>";print_r($updateData);exit;
					$update_tickt = $this->General_Model->update_table('booking_etickets', 'id', $resultTest->id, $updateData);
					//echo $this->db->last_query();
				}
			}
		}	
		
		/*if($_POST["qr_link"] != ''){
				$updateData = array('qr_link' => $_POST["qr_link"]);
				$cond = array('bg_id' => $bookingId);
				$this->General_Model->update('booking_global', $cond, $updateData);
		}*/
		  $this->db->where(array('booking_id' => $bookingId));
				$query = $this->db->get('booking_etickets');
				$results = $query->result();

				if (!empty($results) && $_POST['tracking_number'] != "") {

					foreach($results as $tkey => $ticket){
						$updatedData['ticket_status'] 	= 1;
						$done = true;
						$this->General_Model->update_table('booking_etickets', 'id', $ticket->id, $updatedData);
					}

						$delivery_status = '1';
						
				} 
			//	echo "<pre>";print_r($_POST['qr_link']);exit;
				if (!empty($results) && (!empty($_POST['qr_link'][0]) || !empty($_POST['qr_link_ios'][0]))) {

					foreach($results as $tkey => $ticket){
						$updatedData['qr_link'] 	= $_POST['qr_link'][$tkey];
						$updatedData['qr_link_ios'] 	= $_POST['qr_link_ios'][$tkey];
						$updatedData['ticket_status'] 	= 1;
						$done = true;
						$this->General_Model->update_table('booking_etickets', 'id', $ticket->id, $updatedData);
					}

						$delivery_status = '1';
					
				} 

				$temp_files =  $this->General_Model->getAllItemTable_Array('ticket_instruction_temp_file', array('ticket_id' => $bookingId))->row();
				//echo "<pre>";print_r($temp_files);exit;
		if($temp_files->id != ""){
		$updateData_1['instruction_file'] = $temp_files->ticket_filename;
		}
		else{
		$updateData_1['instruction_file'] = "";
		}

		$cond_1 = array('bg_id' => $bookingId);
		$this->General_Model->update('booking_global', $cond_1, $updateData_1);

		if($_POST["ticket_delivered"] == 1){
				$updateData = array('booking_status' => 5,'delivery_status' => '6');
				$cond = array('bg_id' => $bookingId);
				$this->General_Model->update('booking_global', $cond, $updateData);
		} //echo 'booking_id = '.$bookingId.'&update_tickt = '.$update_tickt.'&nominee='.$_POST['nominee'][0];exit;

		
			if($update_tickt == true && $_POST['nominee'][0] != "" && $_POST["ticket_delivered"] != 1){

				$post_data = array("bg_id" => $bookingId);
						$handle = curl_init();
					$url = API_CRON_URL.'update-nominee-notfication';//echo $url;exit;
					curl_setopt($handle, CURLOPT_HTTPHEADER, array(
					'domainkey: https://www.1boxoffice.com/en/'
					));
					curl_setopt($handle, CURLOPT_URL, $url);
					curl_setopt($handle, CURLOPT_POST, 1);
					curl_setopt($handle, CURLOPT_POSTFIELDS,$post_data);
					curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
					$output = curl_exec($handle);
					curl_close($handle);



			} 
			if($done == true && @$_POST["ticket_delivered"] != 1){ 
					
					$post_data = array("bg_id" => $bookingId);
					//echo 'post_data=';print_r($post_data);exit;
						$handle = curl_init();
					$url = API_CRON_URL.'seller-upload-notfication';
					curl_setopt($handle, CURLOPT_HTTPHEADER, array(
					'domainkey: https://www.1boxoffice.com/en/'
					));
					curl_setopt($handle, CURLOPT_URL, $url);
					curl_setopt($handle, CURLOPT_POST, 1);
					curl_setopt($handle, CURLOPT_POSTFIELDS,$post_data);
					curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
					$output = curl_exec($handle);
					curl_close($handle);
					$updateData = array('delivery_status' => $delivery_status);
					$cond = array('bg_id' => $bookingId);
					$this->General_Model->update('booking_global', $cond, $updateData);
			}

			/*if($ticket_delivery == 1){
				$show_msg = "Attendees and Tickets POD updated Successfully.";
			}
			if($ticket_etickets == 1){
				$show_msg = "Attendees and E-Tickets updated Successfully.";
			}*/
			$show_msg = "Attendees and Tickets details updated Successfully.";

		$response = array('status' => 1, 'msg' => $show_msg, 'redirect_url' => base_url() . '/game/orders/list_order');
		echo json_encode($response);
		exit;

		}
		else if ($segment == 'details') {
			$segment4 = $this->uri->segment(4);
			$this->data['orderData'] =  $this->General_Model->getOrderData($segment4);
			$this->data['eticketData'] = 		$this->General_Model->get_download_tickets(array('booking_id' => $this->data['orderData']->bg_id, 'ticket_id' => $this->data['orderData']->bt_id))->result();
			$this->data['qr_links'] = 		$this->General_Model->get_qr_links(array('booking_id' => $this->data['orderData']->bg_id))->result();
			$this->data['nominees'] = 		$this->General_Model->get_nominees(array('booking_id' => $this->data['orderData']->bg_id, 'ticket_id' => $this->data['orderData']->bt_id))->result();//echo $this->db->last_query();exit;
			$this->data['delivery'] = 		$this->General_Model->booking_ticket_tracking($this->data['orderData']->bg_id);
			if($this->data['orderData']->listing_note != ''){
				$this->data['seller_notes'] = 		$this->General_Model->get_seller_notes($this->data['orderData']->listing_note);
			}
			$bg_id = $this->data['orderData']->bg_id;
			if($bg_id){
				if($this->data['nominees']){

					$this->General_Model->delete('ticket_temp_file', array('ticket_id' => $bg_id));
					//echo $this->db->last_query();
					foreach($this->data['nominees']  as $key => $row){
						if($row->ticket_file){

							$insert_data = array();
							$insert_data['ticket_filename']  = $row->ticket_file;
							$insert_data['ticket_id'] = $bg_id;
							$insert_data['ticket_sort'] = $key;
							$insert_id = $this->General_Model->insert_data('ticket_temp_file', $insert_data);
							 $this->db->last_query();
						}
					}
				}
			}
			$this->data['tempFiles'] = $this->General_Model->getAllItemTable_Array('ticket_temp_file', array('ticket_id' => $bg_id),'','ticket_sort','ASC')->result();
			$this->data['ticket_instruction_files'] = $this->General_Model->getAllItemTable_Array('ticket_instruction_temp_file', array('ticket_id' => $bg_id))->row();
			//echo "<pre>";print_r($this->data['tempFiles'] );exit;
			$orders = $this->load->view(THEME_NAME.'/game/order_info', $this->data, TRUE);
				if (empty($orders)) {
					$orders = "No order details available.";
					//$orders = "No order details available.";
				}
				$response = array('status' => 1, 'orders' => $orders);
				echo json_encode($response);
				exit;

			//$this->load->view('game/order_info', $this->data);
		} else if ($segment == 'add_order') {
			if ($this->input->get('match_id')) {
				$this->data['matchInfo'] = $this->data['getMatchInfo'] = $this->General_Model->getAllItemTable_Array('match_info', array('m_id' => $this->input->get('match_id')))->result();
				$this->data['event_popup_message'] = $this->General_Model->getAllItemTable_Array('event_popup_message')->result();

				if ($this->data['matchInfo'] && $this->data['matchInfo'][0]->event_type == 'match') {

					$this->data['matchId'] = $this->data['matchInfo'][0]->m_id;
					$stadiumid = $this->data['matchInfo'][0]->venue;
					$team1 = $this->data['matchInfo'][0]->team_1;
					$team2 = $this->data['matchInfo'][0]->team_2;
					$tournamentId = $this->data['matchInfo'][0]->tournament;

					if (!empty($value) && !empty($this->session->userdata('id'))) {
						$matchlivesearchdata = $this->General_Model->getAllItemTable_Array('live_search', array('match_id' => $this->data['matchId'], 'user_id' => $this->session->userdata('id')))->result();
						$duplicateCheck = sizeof($matchlivesearchdata);
						if ($duplicateCheck == 0) {
							$insertData = array();
							$insertData['match_id'] = $this->data['matchId'];
							$insertData['user_id'] = $this->session->userdata('id');
							$insertData['count'] = 1;
							$insertData['create_date'] = date('Y-m-d H:i:s');
							$insert = $this->General_Model->insert_data('live_search', $insertData);
						}
					}

					$this->data['tournamentName'] = $this->General_Model->getAllItemTable_Array('tournament', array('t_id' => $tournamentId))->result();
					$this->data['team1Image'] = $this->General_Model->getAllItemTable_Array('teams', array('teams.id' => $team1))->result();
					$this->data['team2Image'] = $this->General_Model->getAllItemTable_Array('teams', array('teams.id' => $team2))->result();
					$this->data['stadiumImage'] = $this->General_Model->getAllItemTable_Array('stadium', array('s_id' => $stadiumid))->result();

					// $ticket_extra_fee = $this->General_Model->getAllItemTable_Array('seller_percentage')->result();
					// $extra_fee_percent = (float) $ticket_extra_fee[0]->site_fee;
					$extra_fee_percent = 0;
					$stad_dtl = $this->General_Model->getAllItemTable_Array('stadium_details', array('stadium_id' => $stadiumid))->result();

					$tot_cats = [];
					$stadium_category = $this->General_Model->getAllItemTable_Array('stadium_seats')->result();
					if ($stadium_category) {
						foreach ($stadium_category as $std_cat) {
							// $tot_cats[$std_cat->seat_category]=$std_cat->id;
							$tot_cats[$std_cat->id] = $std_cat->id;
						}
					}
					$set_stadium_blocks = [];
					$set_stadium_blocks_with_cat = [];
					$set_stadium_cat_name = [];
					if ($stad_dtl) {
						foreach ($stad_dtl as $stdm) {
							$set_stadium_blocks[$stdm->block_id] = $stdm->block_color;
							$set_stadium_blocks_with_cat[$tot_cats[$stdm->category]][] = $stdm->block_id;
							$set_stadium_cat_name[$stdm->category][] = $tot_cats[$stdm->category];
						}
					}

					$sell_ticket_data = $this->General_Model->getAllItemTable_Array('sell_tickets', array('match_id' => $this->data['matchInfo'][0]->m_id), NULL, NULL, NULL, array('s_no', 'desc'))->result();
					$this->data['checkSellTicketData'] = $sell_ticket_data;

					$ticket_price_info = [];
					$ticket_price_info_with_cat = [];
					if ($sell_ticket_data && $this->data['matchInfo'][0]->availability == 1) {

						foreach ($sell_ticket_data as $sl_tckt) {

							$lowest_price = (($extra_fee_percent / 100) * (float) $sl_tckt->price) + (float) $sl_tckt->price;
							// $ticket_price_info[$sl_tckt->ticket_category][$sl_tckt->ticket_block]['price'][] = $this->currencyConverterMap2($lowest_price, $sl_tckt->price_type, $this->session->userdata('currency'));
							$ticket_price_info[$sl_tckt->ticket_category][$sl_tckt->ticket_block]['price'][] = $lowest_price;
							$ticket_price_info[$sl_tckt->ticket_category][$sl_tckt->ticket_block]['no_of_ticket'][] = (int) $sl_tckt->quantity;
						}
					}

					if ($ticket_price_info) {
						foreach ($ticket_price_info as $cat_key => $tckt_prc) {
							$tckt_price = [];
							$tckt_ticket = 0;
							foreach ($tckt_prc as $sub_tckt_prc) {
								$tckt_price = array_merge($sub_tckt_prc['price'], $tckt_price);
								$tckt_ticket = (float) $tckt_ticket + array_sum($sub_tckt_prc['no_of_ticket']);
							}
							$ticket_price_info_with_cat[$cat_key]['name'] = array_search($cat_key, $tot_cats);
							$ticket_price_info_with_cat[$cat_key]['price'] = min($tckt_price);
							$ticket_price_info_with_cat[$cat_key]['no_of_ticket'] = $tckt_ticket;
						}
					}

					$this->data['selected_customer'] = $this->General_Model->getAllItemTable_Array('register', array('id' => $this->input->get('customer_id')))->row();
					$this->data['ticket_price_info'] = json_encode($ticket_price_info);
					$this->data['ticket_price_info_with_cat'] = json_encode($ticket_price_info_with_cat);
					$this->data['set_stadium_blocks'] = json_encode($set_stadium_blocks);
					$this->data['set_stadium_blocks_with_cat'] = json_encode($set_stadium_blocks_with_cat);
					$this->data['set_stadium_cat_name'] = json_encode($set_stadium_cat_name);
					$this->data['get_std'] = $this->General_Model->getAllItemTable_Array('stadium', array('s_id' => $this->data['matchInfo'][0]->venue))->result();
					$this->data['get_city'] = $this->General_Model->getAllItemTable_Array('states', array('id' => $this->data['matchInfo'][0]->state))->result();
					$this->data['get_tournament'] = $this->General_Model->getAllItemTable_Array('tournament', array('t_id' => $this->data['matchInfo'][0]->tournament, 'language'))->result();
				}
			}

			$this->data['cid'] = $this->input->get('cid');

			if ($this->data['cid'] != '') {

				$this->data['sessionArray'] = $sessionArray = $this->session->userdata('sessionValue');
				$this->data['matcheventdetails'] = $this->General_Model->getAllItemTable_Array('match_info', array('m_id' => $sessionArray['matcheventid']))->result();

				$this->data['stadiumdetails'] = $this->General_Model->getAllItemTable_Array('sell_tickets', array('s_no' => $sessionArray['sellticketid']))->result();

				$this->data['selected_customer'] = $this->General_Model->getAllItemTable_Array('register', array('id' => $this->input->get('customer_id')))->row();

				$sellerPercentageData = $this->General_Model->getAllItemTable('seller_percentage')->result();

				$defaultPrice = $this->data['stadiumdetails'][0]->price;
				$this->data['totalCartPrice'] = $defaultPrice;
				if (empty($this->data['totalCartPrice'])) {
					$this->data['totalCartPrice'] = '10.00';
				}
				$this->data['priceType'] = $this->data['stadiumdetails'][0]->price_type;
				$this->data['settings'] = $this->General_Model->getSiteSettings();

				$this->data['myaddresses'] = $this->General_Model->getAllItemTable_Array('addresses', array('user_id' => $this->input->get('customerid'), 'set_default' => 1))->result();
				$this->data['addresses'][0] = $this->data['myaddresses'][0];

				if (count($this->data['addresses']) > 0) {
					$this->data['getRegisterDataByid'] = $this->db->query("SELECT s.name as first_name , s.surname as last_name ,s.address,s.country,s.province as state,s.phone as mobile,s.postal_code as code,s.dialing_code as phonecode ,s.phone as phone,s.city as city,c.email as email FROM addresses as s left join register as c on c.id=s.user_id WHERE s.user_id like '" . $this->input->get('customer_id') . "' and s.set_default = '1'")->result();
				}

				$this->data['allCountries'] = $this->db->query("SELECT * FROM countries ORDER BY name ASC ")->result();
			}


			$this->load->view('game/add_order', $this->data);
		} else if ($segment == 'checkout') {
			$data = $this->input->post();

			if (count($this->input->post('request_ticket')) == 1) {
				$insertArray = array(
					'event_id' => $this->input->post('matcheventid'),
					'user_id' => $this->input->post('userid'),
					'country' => $this->input->post('country'),
					'block_category' => $this->input->post('category'),
					'quantity' => $this->input->post('nooftick'),
					'special_request' => $this->input->post('message'),
					'status' => 0,
					'request_date' => get_est_time()
				);
				$this->Settings_model->insert('request_tickets', $insertArray);

				$sessionArray = array(
					'matcheventid' => $this->input->post('matcheventid'),
					'stadiumid' => $this->input->post('stadiumid'),
					'noTickets' => $this->input->post('nooftick'),
					'sellticketid' => $this->input->post('sellticketid'),
					'sellerid' => $this->input->post('sellerId')
				);
				$this->session->set_userdata('sessionValue', $sessionArray);

				//Notification message
				$this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success', $this->lang->line('added_success')));
				redirect('game/checkout');
			}

			if ($this->input->post('sessionset')) {
				$sell_tickets_info = $this->General_Model->getAllItemTable_Array('sell_tickets', array('s_no' => $this->input->post('sellticketid')))->result();
				if ($this->input->post('nooftick')) {
					$no_tickets = $this->input->post('nooftick');
				} elseif ($sell_tickets_info[0]->split == '2') {

					$no_tickets = $sell_tickets_info[0]->quantity;
				} elseif ($sell_tickets_info[0]->split == '4') {

					$no_tickets = 2;
				} else {
					$no_tickets = 1;
				}

				$this->General_Model->delete('cart_session', array('ip' => $_SERVER['REMOTE_ADDR']));

				$added_datetime = date("Y-m-d H:i");
				$expiry = date("Y-m-d H:i", strtotime('+10 minutes', strtotime($added_datetime)));
				$insert_cart_session = array(
					'match_id' => $this->input->post('matcheventid'),
					'sell_id' => $this->input->post('sellticketid'),
					'no_ticket' => $no_tickets,
					'ip' => $_SERVER['REMOTE_ADDR'],
					'added_datetime' => $added_datetime,
					'expriy_datetime' => $expiry,
				);
				$cid = $this->General_Model->insert_data('cart_session', $insert_cart_session);


				$sessionArray = array(
					'matcheventid' => $this->input->post('matcheventid'),
					'stadiumid' => $this->input->post('stadiumid'),
					'noTickets' => $no_tickets,
					'sellticketid' => $this->input->post('sellticketid'),
					'sellerid' => $this->input->post('sellerId'),
					'cid' => $cid

				);

				$this->session->set_userdata('sessionValue', $sessionArray);
			}
			$this->data['sessionArray'] = $sessionArray = $this->session->userdata('sessionValue');
			$this->data['matcheventdetails'] = $this->General_Model->getAllItemTable_Array('match_info', array('m_id' => $sessionArray['matcheventid']))->result();
			if ($this->data['matcheventdetails']) {
				$response = array('status' => 1, 'match_id' => $sessionArray['matcheventid'], 'customer_id' => $this->input->post('customerid'), 'cid' => $cid);
			} else {
				$response = array('status' => 1, 'msg' => "Invalid match event details");
			}
			echo json_encode($response);
			exit;

			/*

			if (!$this->session->userdata('sessionValue')) {
				redirect('home');
				$this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success', "No select any match...!"));
			}

			$this->data['sessionArray'] = $sessionArray = $this->session->userdata('sessionValue');
			$this->data['matcheventdetails'] = $this->General_Model->getAllItemTable_Array('match_info', array('m_id' => $sessionArray['matcheventid']))->result();

			$this->data['stadiumdetails'] = $this->General_Model->getAllItemTable_Array('sell_tickets', array('s_no' => $sessionArray['sellticketid']))->result();

			$this->data['selected_customer'] = $this->General_Model->getAllItemTable_Array('register', array('id' => $this->input->post('customerid')))->row();

			$sellerPercentageData = $this->General_Model->getAllItemTable('seller_percentage')->result();

			$defaultPrice = $this->data['stadiumdetails'][0]->price;
			$percentage = ($sellerPercentageData[0]->site_fee / 100) * $defaultPrice;

			$this->data['totalPrice'] = $defaultPrice + $percentage;
			if (empty($this->data['totalPrice'])) {
				$this->data['totalPrice'] = '10.00';
			}
			$this->data['priceType'] = $this->data['stadiumdetails'][0]->price_type;


			$this->data['settings'] = $this->General_Model->getSiteSettings();

			$this->data['myaddresses'] = $this->General_Model->getAllItemTable_Array('addresses', array('user_id' => $this->input->post('customerid'), 'set_default' => 1))->result();
			$this->data['addresses'][0] = $this->data['myaddresses'][0];

			if (count($this->data['addresses']) > 0) {
				$this->data['getRegisterDataByid'] = $this->db->query("SELECT s.name as first_name , s.surname as last_name ,s.address,s.country,s.province as state,s.phone as mobile,s.postal_code as code,s.dialing_code as phonecode ,s.phone as phone,s.city as city,c.email as email FROM addresses as s left join register as c on c.id=s.user_id WHERE s.user_id like '" . $this->session->userdata('id') . "' and s.set_default = '1'")->result();
			}

			$this->data['allCountries'] = $this->db->query("SELECT * FROM countries ORDER BY name ASC ")->result();
			if ($this->data['matcheventdetails']) {
				return $this->load->view('game/order_checkout', $this->data);
			} else {
				redirect();

			}*/
		} 

		else if ($segment == 'delete_temp_file') {
			$segment4 = $this->uri->segment(4);
			//echo "<pre>";print_r($_POST);exit;
			if($segment4 == 2 && $this->input->post('ticket_id') != "" && $this->input->post('sort') != ""){
				//echo "<pre>";print_r($updateData);exit;

				$id 		= $this->input->post('id');
				$ticket_id  = $this->input->post('ticket_id');
				$sort       = $this->input->post('sort');
				//echo $ticket_id.'='.$sort;exit;
				$updateData = array(
								'ticket_file'	=> '',
								'ticket_status'	=> 0
				);
				  $where = array(
				  	'booking_id' => $ticket_id,
				  	'serial' => $sort
				  );
				 $update_tickt = $this->General_Model->update_table_v2('booking_etickets', $where, $updateData);

				 if($ticket_id != ""){

					$updateDatav = array(
					'booking_status' => 1,
					'delivery_status'	=> 0
					);

					$this->General_Model->update_table('booking_global', 'bg_id', $ticket_id, $updateDatav);

				 }
				


				 
				 $type = "1";
			}

			if($segment4 == 2){
				$id = $this->input->post('id');
				$delete = $this->General_Model->delete_data('ticket_temp_file', 'id', $id);
				$type = "2";
			}
				//echo $this->db->last_query();
			echo json_encode(array("message"  => true,'type' => $type));
			die;
		}
		else if ($segment == 'delete_instruction_temp_file') {
			$id = $this->input->post('id');
			if($id != ""){

				
				$updateData = array(
								'instruction_file'	=> ''
				);
			
				//print_r($updateData);die;
				 $update_tickt = $this->General_Model->update_table('booking_global', 'bg_id', $id, $updateData);

				
				$delete = $this->General_Model->delete_data('ticket_instruction_temp_file', 'ticket_id', $id);
				$type = "2";
			}
				//echo $this->db->last_query();
			echo json_encode(array("message"  => true,'type' => $type));
			die;
		}
		else if ($segment == 'update_tempfile_status') {
			//print_r($this->input->post('data'));
			if($this->input->post('data')){
				$data = json_decode($this->input->post('data'));
				foreach($data as $key =>$value){
				//	echo $key."--".$value;
					$updateData  = array( 'ticket_sort' => $key);
					$this->General_Model->update('ticket_temp_file', array('id' => $value), $updateData);
					//echo $this->db->last_query();
				}	
			}
		}
		else if ($segment == 'save_temp_file') {
			$ticket_id =  $this->uri->segment(4);
			$count = count(@$_FILES["eticket"]['name']);
 			$data_image = array();
			if ($count > 0) { 


				$config["upload_path"] = './uploads/e_tickets/';
				$config["allowed_types"] = 'pdf|gif|jpg|png|jpeg';
				$this->load->library('upload', $config);
				$this->upload->initialize($config);


				$temp_files_coun =  $this->General_Model->getAllItemTable_Array('ticket_temp_file', array('ticket_id' => $ticket_id),'','ticket_sort','ASC')->num_rows();


				for ($i=0; $i < $count ; $i++) { 
				
					$ticketId = time();
					$file_ext = pathinfo($_FILES["eticket"]["name"][$i], PATHINFO_EXTENSION);
					$_FILES["file"]["name"] =  $_FILES["eticket"]["name"][$i]."-".$ticketId .'.'. $file_ext;
					$_FILES["file"]["type"] = $_FILES["eticket"]["type"][$i];
					$_FILES["file"]["tmp_name"] = $_FILES["eticket"]["tmp_name"][$i];
					$_FILES["file"]["error"] = $_FILES["eticket"]["error"][$i];
					$_FILES["file"]["size"] = $_FILES["eticket"]["size"][$i];
					$insert_data = array();
					if ($this->upload->do_upload('file')) {
						$data = $this->upload->data();
						$insert_data['ticket_filename'] = $data["file_name"];
						$insert_data['ticket_id'] = $ticket_id;
						$insert_data['ticket_sort'] = $i + $temp_files_coun;
						$insert_id = $this->General_Model->insert_data('ticket_temp_file', $insert_data);
						$data_image[] = array('id' => $insert_id,'name' => $data["file_name"]);

					}
				}
			}
			echo json_encode($data_image);
			die;	
		}
		else if ($segment == 'save_ticket_instruction_temp_file') {
			$ticket_id =  $this->uri->segment(4);
			if($ticket_id != ""){

				$updateData = array(
								'instruction_file'	=> ''
				);
				
				$update_tickt = $this->General_Model->update_table('booking_global', 'bg_id', $ticket_id, $updateData);

				$delete = $this->General_Model->delete_data('ticket_instruction_temp_file', 'ticket_id', $ticket_id);

			}
			$count = count(@$_FILES["ticket_instruction"]['name']);

 			$data_image = array();
			if ($count > 0) { 


				$config["upload_path"] = './uploads/e_tickets/';
				$config["allowed_types"] = 'pdf|gif|jpg|png|jpeg';
				$this->load->library('upload', $config);
				$this->upload->initialize($config);


				$temp_files_coun =  $this->General_Model->getAllItemTable_Array('ticket_instruction_temp_file', array('ticket_id' => $ticket_id),'','ticket_sort','ASC')->num_rows();


				for ($i=0; $i < $count ; $i++) { 
				
					$ticketId = time();
					$file_ext = pathinfo($_FILES["ticket_instruction"]["name"][$i], PATHINFO_EXTENSION);
					$_FILES["file"]["name"] =  $_FILES["ticket_instruction"]["name"][$i]."-".$ticketId .'.'. $file_ext;
					$_FILES["file"]["type"] = $_FILES["ticket_instruction"]["type"][$i];
					$_FILES["file"]["tmp_name"] = $_FILES["ticket_instruction"]["tmp_name"][$i];
					$_FILES["file"]["error"] = $_FILES["ticket_instruction"]["error"][$i];
					$_FILES["file"]["size"] = $_FILES["ticket_instruction"]["size"][$i];
					$insert_data = array();
					if ($this->upload->do_upload('file')) {
						$data = $this->upload->data();
						$insert_data['ticket_filename'] = $data["file_name"];
						$insert_data['ticket_id'] = $ticket_id;
						$insert_data['ticket_sort'] = $i + $temp_files_coun;
						$insert_id = $this->General_Model->insert_data('ticket_instruction_temp_file', $insert_data);
						$data_image[] = array('id' => $insert_id,'name' => $data["file_name"]);

					}
					else{
						//echo $this->upload->display_errors();exit;

					}
				}
			}
			echo json_encode($data_image);
			die;	
		}
		else if ($segment == 'save_order') {

			$matchId = $this->input->post('matchid');
			$userId = $this->input->post('userId');

			$matchInfoArray = $this->General_Model->getAllItemTable_Array('match_info', array('m_id' => $matchId))->result();
			$noTickets = $this->input->post('notickets');
			$sellId = $this->input->post('sellid');

			$this->data['sellTicketsData'] = $this->General_Model->getAllItemTable_Array('sell_tickets', array('s_no' => $sellId))->result();
			$sessionArray = $this->session->userdata('sessionValue');
			$cart_id = $sessionArray['cid'];
			$cart = $this->General_Model->get_cart_data($cart_id)->row();

			$this->form_validation->set_rules('first_name', 'First Name', 'required');
			$this->form_validation->set_rules('last_name', 'Last Name', 'required');
			$this->form_validation->set_rules('address', 'Address', 'required');
			$this->form_validation->set_rules('postcode', 'Postcode', 'required');
			$this->form_validation->set_rules('country', 'Country', 'required');
			$this->form_validation->set_rules('city', 'City', 'required');
			$this->form_validation->set_rules('phoneCode', 'Phone Code', 'required');
			$this->form_validation->set_rules('billingphonenumber', 'Phone Number', 'required');
			$this->form_validation->set_rules('userId', 'user id', 'required');
			$this->form_validation->set_rules('billingphonenumber', 'Phone Number', 'required');
			$this->form_validation->set_rules('notickets', 'Ticket Quantity', 'required');

			if ($this->form_validation->run() !== false) {

				//echo "<pre>";print_r($this->data['sellTicketsData'][0]);exit;
				$qty = $_POST['notickets'];
				$price_type = $this->data['sellTicketsData'][0]->price_type;
				$price 		= $this->data['sellTicketsData'][0]->price;
				$sub_total  = $qty * $price;
				$tax_fees 	= 0;
				$discount 	= 0;
				$total_amount = $sub_total + $tax_fees + $discount;
				$ip_address = $_SERVER['REMOTE_ADDR'];
				//echo "AAAAA";exit;
				$booking_no = '1BO' . time();
				$booking_confirmation_no = 'TK' . time();;
				$booking_global = array(
					'user_id'        => $_POST['userId'],
					'sub_total'      => $sub_total,
					'booking_status' => 1,
					'booking_no' 	 => $booking_no,
					'booking_confirmation_no' 	 => $booking_confirmation_no,
					'tax_fees'       => $tax_fees,
					'discount_amount' => $discount,
					'total_amount'   => $total_amount,
					'currency_type'  => $price_type,
					'ip_address'     => $ip_address
				);
				//echo "<pre>";print_r($booking_global);exit;
				$booking_id = $this->General_Model->insert_data('booking_global', $booking_global);
				if ($booking_id != '') {

					$data_billing = array(
						'booking_id'     => $booking_id,
						'title'          => 'Mr',
						'first_name'     => $_POST['first_name'],
						'last_name'      => $_POST['last_name'],
						'email'          => $_POST['email'],
						'dialing_code'   => $_POST['phoneCode'],
						'mobile_no'      => $_POST['billingphonenumber'],
						'address'        => $_POST['address'],
						'postal_code'    => $_POST['postcode'],
						'country_id'     => $_POST['country'],
						'state_id'       => $_POST['city'],
					);
					$data_billing_id = $this->General_Model->insert_data('booking_billing_address', $data_billing);

					$data_ticket = array(
						'booking_id'     => $booking_id,
						'match_id'       => $cart->match_id,
						'match_name'     => $cart->match_name,
						'match_date'     => $cart->match_date,
						'match_time'     => $cart->match_time,
						'team_image_a'   => $cart->team_image_a,
						'team_image_b'   => $cart->team_image_b,
						'tournament_name' => $cart->tournament_name,
						'tournament_id'  => $cart->tournament,
						'ticket_type'    => $cart->ticket_type,
						'seat_category'  => $cart->seat_category,
						'ticket_category' => $cart->ticket_category,
						'split'          => $cart->split,
						'ticket_block'   => $cart->ticket_block,
						'row'            => $cart->row,
						'listing_note'   => $cart->listing_note,
						'stadium_name'   => $cart->stadium_name,
						'stadium_map'    => $cart->stadium_image,
						'stadium_id'     => $cart->venue,
						'country_name'   => $cart->country_name,
						'country_id'     => $cart->country,
						'city_name'      => $cart->state_name,
						'city_id'        => $cart->city,
						'ticket_id'      => $cart->s_no,
						'quantity'       => $cart->no_ticket,
						'price'          => $cart->price,
						'currency_type'  => $cart->price_type,
					);
					$booking_tickets_id = $this->General_Model->insert_data('booking_tickets', $data_ticket);


					if ($cart->ticket_type == 2) {

						$tickets_data = array();
						for ($i = 1; $i <= $cart->no_ticket; $i++) {

							$tickets_data[] = array(

								'booking_id' => $booking_id,
								'ticket_id' => $booking_tickets_id,
								'serial' => $i,
								'ticket_file' => '',


							);
						}
						$booking_tickets_id = $this->General_Model->insert_batch_data('booking_etickets', $tickets_data);
					}
					$txn = 'txn_' . time();
					$payment_data = array(
						'booking_id'            => $booking_id,
						'payment_status'        => 1,
						'transcation_id'        => $txn,
						'payment_type'          => 2,
						'payment_response'      => "$txn",
						'total_payment'         => $total_amount,
						'currency_code'         => $price_type,
						'message'               => "success",
						'payment_date'          => date("Y-m-d H:i:s")
					);

					$payment_id = $this->General_Model->insert_data('booking_payments', $payment_data);
				}

				$response = array(
					'status' => 1,
					'msg' => "New Order Created Successfully",
					'redirect_url' => base_url() . 'game/orders/list_order'
				);

				echo json_encode($response);
				exit;
			} else {

				$response = array(
					'status' => 0,
					'msg' => validation_errors(),
					'redirect_url' => base_url() . 'game/orders/list_order'
				);

				echo json_encode($response);
				exit;
			}
			/*$sellerPercentageData = $this->General_Model->getAllItemTable_Array('seller_percentage')->result();
=======
			}
		} else if ($segment == 'save_order') {
			$matchId = $this->input->post('matchid');
			$userId = $this->input->post('userId');
			$matchInfoArray = $this->General_Model->getAllItemTable_Array('match_info', array('m_id' => $matchId))->result();
			$noTickets = $this->input->post('notickets');
			$sellId = $this->input->post('sellid');
			$this->data['sellTicketsData'] = $this->General_Model->getAllItemTable_Array('sell_tickets', array('s_no' => $sellId))->result();
			$sellerPercentageData = $this->General_Model->getAllItemTable_Array('seller_percentage')->result();

			$selling_price = $this->General_Model->currencyConverterMap2($this->data['sellTicketsData'][0]->price + ($this->data['sellTicketsData'][0]->price * $sellerPercentageData[0]->site_fee / 100), $this->data['sellTicketsData'][0]->price_type, $this->session->userdata('currency'));
			$arrangement_fee = ($selling_price * $sellerPercentageData[0]->arrangement_fee / 100) * $noTickets;
			$total_final_price = ($selling_price * $noTickets) + $arrangement_fee + $site_fee;
			$seller_total_price = $this->General_Model->currencyConverterMap2($total_final_price, $this->session->userdata('currency'), $this->data['sellTicketsData'][0]->price_type);
			$currency = strtoupper($this->data['sellTicketsData'][0]->price_type);
			$order_amount = round($seller_total_price * 100);
			$date = date("Y-m-d H:i:s");
			$dataDB = array(
				'user_id' => $userId,
				'match_id' => $matchId,
				'block_id' => $_POST['stadiumblockid'],
				'sell_id' => $sellId,
				'no_tickets' => $noTickets,
				'amount' => $order_amount / 100,
				'discount' => $_POST['discountvalue'],
				'discount_type' => $_POST['coupontype'],
				'currency_type' => $currency,
				'txn_id' => time(),
				'payment_reference_number' => "TEST",
				'status' => 0,
				'type' => 'manual',
				// 'payment_status' => $status,
				'payment_date' => $date,
				'ip_address' => $_SERVER['REMOTE_ADDR']
			);
			$purchase = $this->General_Model->insert_data('purchase', $dataDB);

			redirect('game/orders/list_order');*/
		}
	}

	/**
	 * Stadium related operations
	 * Add
	 * List
	 * Edit
	 * Delete
	 */
	public function stadium()
	{
		$segment = $this->uri->segment(3);
		if ($segment == 'get_stadium') {
			$segment4 = $this->uri->segment(4);
			$getStadiumDetails = $this->General_Model->getAllItemTable('stadium', 's_id', $segment4)->row();
			$svg_filename = base_url() . 'uploads/stadium/maps/user-uploads/' . basename($getStadiumDetails->stadium_image);
			header('location: ' . base_url('game/stadium/add_stadium') . '/' . $segment4 . '?map=' . $svg_filename);
		} else if ($segment == 'add_stadium') {
			$segment4 = $this->uri->segment(4);
			$this->data['allCountries'] = $this->General_Model->getAllItemTable('countries', NULL, NULL, 'name', 'asc')->result();
			$this->data['allTeams'] = $this->General_Model->getAllItemTable('teams')->result();
			//$this->data['getSeatCategory'] = $this->General_Model->getAllItemTable('stadium_seats', 'status', 1)->result();
			$this->data['getSeatCategory'] = $this->General_Model->getAllItemTable_Array('stadium_seats_lang', array('language' => 'en'),'','seat_category','ASC')->result();
			if ($segment4 != "") {
				$this->data['edit_stadium_id'] = $segment4;
				$this->data['getStadium'] = $this->General_Model->getAllItemTable('stadium', 's_id', $segment4)->row();
				$this->load->view('game/edit_stadium', $this->data);
				exit;
			}
			$this->load->view('game/add_stadium', $this->data);
		} else if ($segment == 'list_stadium') {
			$search_text = '';
			if ($this->input->post('submit') != NULL) {
				$search_text = $this->input->post('search');
				$this->session->set_userdata(array("searchstadium" => $search_text));
			} else {
				if ($this->session->userdata('searchstadium') != NULL) {
					$search_text = $this->session->userdata('searchstadium');
				}
			}
			$row_count = $this->uri->segment(4);
			$this->loadRecord($row_count, 'stadium', 'game/stadium/list_stadium', 's_id', 'DESC', 'game/stadium_list', 'stadiums', 'stadiums', $search_text);
		} else if ($segment == 'delete_stadium') {
			$segment4 = $this->uri->segment(4);
			$delete_id = $segment4;
			$delete = $this->General_Model->delete_data('stadium', 's_id', $delete_id);
			if ($delete == 1) {
				$response = array('status' => 1, 'msg' => 'Stadium deleted Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error while deleting stadium.');
				echo json_encode($response);
				exit;
			}
		} else if ($segment == 'save_stadium') {
			if ($this->input->post()) {
				$insertData = array();
				$insertData['stadium_name'] = $this->input->post('stadiumname');
				$insertData['stadium_image'] = $this->input->post('stadiumimage');
				$insertData['width'] = $this->input->post('stadiumwidth');
				$insertData['height'] = $this->input->post('stadiumheight');
				$insertData['status'] = 1;
				//echo "<pre>"; var_dump(json_decode($this->input->post('stadiumcode'))); echo "</pre>"; exit;
				$segment4 = $this->uri->segment(4);
				if ($segment4) {
					$insertData['stadium_name'] = $this->input->post('stadiumViewName');
					$insertData['stadium_name_ar'] = $this->input->post('stadiumViewName_ar');
					$this->General_Model->update('stadium', array('s_id' => $segment4), $insertData);
					$insertTable = true;
				} else {
					$insertTable = $this->General_Model->insert_data('stadium', $insertData);
				}
				if ($insertTable) {
					if ($segment4) {
						$stadiumId = $segment4;
					} else {
						$stadiumId = $insertTable;
						$insertStatdiumData = array();
						$assignBlockId = explode(',', $this->input->post('blockid'));
						$fillColor = explode('-', $this->input->post('fillcolor'));
						$categoryName = explode(',', $this->input->post('categoryname'));
						$getStadiumByid = $this->General_Model->getAllItemTable_array('stadium', array('s_id' => $stadiumId))->result();
						foreach ($assignBlockId as $key => $value) {
							$insertStatdiumData['stadium_id'] = $stadiumId;
							$insertStatdiumData['block_id'] = $assignBlockId[$key];
							$insertStatdiumData['block_color'] = $fillColor[$key];
							$insertStatdiumData['category'] = $categoryName[$key];
							$this->General_Model->insert_data('stadium_details', $insertStatdiumData);

							$regions = array();
							$regions['id'] = $assignBlockId[$key];
							$regions['id_no_spaces'] = $assignBlockId[$key];
							$regions['fill'] = $fillColor[$key];
							$regions['href'] = $categoryName[$key];
							$regions['tooltip'] = $categoryName[$key];

							$regions['data'] = array();
							$someArray['regions'][$assignBlockId[$key]] = $regions;
						}

						$updateData = array();
						$updateData['map_code'] = json_encode($someArray);
						$this->General_Model->update_table('stadium', 's_id', $stadiumId, $updateData);
					}
				}

				redirect('/game/stadium/list_stadium');
			}
		} else if ($segment == 'check_stadium') {
			define('MAPSVG_PLUGIN_URL', "http" . (!empty($_SERVER['HTTPS']) ? "s" : "") . "://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['SCRIPT_NAME']) . '/');
			define('MAPSVG_ADMIN_URL', "");
			define('DIRECTORY_SEPARATOR', "/");

			define('MAPSVG_SITE_URL', base_url());
			define('MAPSVG_PLUGIN_DIR', $_SERVER['DOCUMENT_ROOT'] . '/' . DIRECTORY_SEPARATOR);
			define('MAPSVG_BUILDER_DIR', MAPSVG_PLUGIN_DIR);
			define('MAPSVG_MAPS_DIR', MAPSVG_PLUGIN_DIR . 'maps');
			// define('MAPSVG_MAPS_UPLOADS_DIR', MAPSVG_MAPS_DIR . DIRECTORY_SEPARATOR . 'user-uploads');
			define('MAPSVG_MAPS_URL', MAPSVG_PLUGIN_URL . 'maps/');
			define('MAPSVG_PINS_DIR', MAPSVG_PLUGIN_DIR . 'markers' . DIRECTORY_SEPARATOR);
			define('MAPSVG_PINS_URL', MAPSVG_PLUGIN_URL . 'markers/');
			define('MAPSVG_VERSION', '2.2.1');
			define('MAPSVG_JQUERY_VERSION', '6.2.0');

			if (isset($_POST['upload_svg']) && $_FILES['svg_file']['tmp_name']) {
				$target_dir = MAPSVG_MAPS_UPLOADS_DIR;

				$target_file = $target_dir . DIRECTORY_SEPARATOR . basename(urldecode(strtolower(str_replace(' ', '-', $_FILES["svg_file"]["name"]))));

				$file_parts = pathinfo(urldecode(strtolower(str_replace(' ', '-', $_FILES["svg_file"]["name"]))));
				// $getStadiumData = $this->General_Model->getAllItemTable_Array('stadium', array('stadium_name' => $file_parts['filename']))->result();
				// $duplicateCheck = sizeof($getStadiumData);
				$duplicateCheck = 0;

				if ($duplicateCheck == 0) {
					//            $a = str_replace("\n", " \\n", str_replace("e\\", "e \\", $js_mapsvg_options));

					if (strtolower($file_parts['extension']) != 'svg') {
						$mapsvg_error = 'Wrong file format (' . $file_parts['extension'] . '). Only SVG files are compatible with the plugin.';

						// $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('error', $mapsvg_error));

						header('location: ' . base_url() . 'game/stadium/add_stadium');
					} else {
						// print_r('uploads/stadium/maps/' . basename(urldecode(strtolower(str_replace(' ', '-', $_FILES["svg_file"]["name"]))))); exit;
						if (@move_uploaded_file($_FILES["svg_file"]["tmp_name"], 'uploads/stadium/maps/user-uploads/' . basename(urldecode(strtolower(str_replace(' ', '-', $_FILES["svg_file"]["name"])))))) {

							$mapsvg_notice = "The file " . basename(urldecode(strtolower(str_replace(' ', '-', $_FILES["svg_file"]["name"])))) . " has been uploaded.";
							$svg_filename = base_url() . 'uploads/stadium/maps/user-uploads/' . basename(urldecode(strtolower(str_replace(' ', '-', $_FILES["svg_file"]["name"]))));
							header('location: ' . base_url() . 'game/stadium/add_stadium?map=' . $svg_filename);
						} else {

							$mapsvg_error = "An error occured during upload of your file. Please check that " . MAPSVG_MAPS_UPLOADS_DIR . " folder exists and it has full permissions (777).";
							header('location: ' . MAPSVG_ADMIN_URL . '?uploadError=1');
						}
					}
				} else {
					// $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success', 'Stadium Name already Extist...!'));
					header('location: ' . admin_url() . 'game/add_stadium');
				}
			}
			//        }

			if (isset($_GET['action'])) {
				if (is_callable($_GET['action'])) {
					$data = isset($_GET['data']) ? $_GET['data'] : null;
					call_user_func($_GET['action'], $data);
				}
			}

			function check_php()
			{
				echo "1";
			}
		}
	}

	/**
	 * Fetch data and display based on the pagination request
	 */
	public function loadRecord($rowno = 0, $table, $url, $order_column, $order_by, $view, $variable_name, $type, $search = '')
	{ 

		// Load Pagination library
		$this->load->library('pagination');

		// Row per page
		$row_per_page = 10;

		// Row position
		if ($rowno != 0) {
			$rowno = ($rowno - 1) * $row_per_page;
		}
		// All records count
		$allcount = $this->General_Model->get_table_row_count($table, '');
 
		if ($type == 'gamecategory') {
			$allcount = $this->General_Model->get_game_category_by_limit('', '', '', '', '', $search)->num_rows();
			$record = $this->General_Model->get_game_category_by_limit($rowno, $row_per_page, $order_column, $order_by, '', $search)->result();
		} else if ($type == 'seatcategory') {
			$record = $this->General_Model->get_seat_category_by_limit($rowno, $row_per_page, $order_column, $order_by, '')->result();
		} else if ($type == 'stadiums') {
			$allcount = $this->General_Model->get_stadium_by_limit('', '', '', '', '', $search)->num_rows();
			$record = $this->General_Model->get_stadium_by_limit($rowno, $row_per_page, $order_column, $order_by, '', $search)->result();
		}
		else if ($type == 'abandoned') {
			$allcount = $this->General_Model->abondaned('','', '',$search)->num_rows();
			$record = $this->General_Model->abondaned('',$rowno, $row_per_page,$search)->result();
		}
		else if ($type == 'orders' || $type == 'ajax_orders' || $type == 'get_order_search' || $type == 'load_side_filter') { 
				$row_per_page = $_POST['limit'] ? $_POST['limit'] :  10;
				$rowno =  $this->uri->segment(5) ?  $this->uri->segment(5) : $_POST['page'];

				if ($rowno != '' && $rowno != 0) {
				$rowno = ($rowno - 1) * $row_per_page;
				}
				else{
					$rowno = 0;
				} 

			if ($type == "get_order_search") {
				//echo "<pre>";print_r($_POST);exit;
					$segment = $_POST['flag'];
					if($segment == "uploadticket"){
						//$record = $this->General_Model->getOrders('',$segment,'',$rowno, $row_per_page,$search)->result();
						$record = $this->General_Model->getOrders_v2($segment,'',$rowno, $row_per_page,$search)->result();
					}
					else if($segment == "pending_pod"){
						//$record = $this->General_Model->getOrders('',$segment,'',$rowno, $row_per_page,$search)->result();
						$record = $this->General_Model->getOrders_v3($segment,'',$rowno, $row_per_page,$search)->result();
					}
					else{
						//$record = $this->General_Model->getOrders('',$segment,'',$rowno, $row_per_page,$search)->result();
					$record = $this->General_Model->getOrders_v1($segment,'',$rowno, $row_per_page,$search)->result();
					}
				

			} else { 

			//echo "dsds".$this->uri->segment(4);exit;
				$segment = $this->uri->segment(4);
				if($type == 'ajax_orders'){
					$segment = $_POST['filter'];
				}

				if($type == 'load_side_filter'){ 

					if(empty($_POST['filter'])){
				/*$wc_all = $this->General_Model->getOrders_v1('wc')->num_rows();
				$wc_gbp = $this->General_Model->getOrders_v1('wc','GBP')->result();  
				$wc_eur = $this->General_Model->getOrders_v1('wc','EUR')->result();
				$wc_usd = $this->General_Model->getOrders_v1('wc','USD')->result();
				$wc_aed = $this->General_Model->getOrders_v1('wc','AED')->result();
				$this->data['orders']['wc_all'] = $wc_all;
				$this->data['orders']['wc_gbp'] = $wc_gbp;
				$this->data['orders']['wc_eur'] = $wc_eur;
				$this->data['orders']['wc_usd'] = $wc_usd;
				$this->data['orders']['wc_aed'] = $wc_aed;

				$wc_delivered_all = $this->General_Model->getOrders_v1('wc_delivered')->num_rows();
				$wc_delivered_gbp = $this->General_Model->getOrders_v1('wc_delivered','GBP')->result();
				$wc_delivered_eur = $this->General_Model->getOrders_v1('wc_delivered','EUR')->result();
				$wc_delivered_usd = $this->General_Model->getOrders_v1('wc_delivered','USD')->result();
				$wc_delivered_aed = $this->General_Model->getOrders_v1('wc_delivered','AED')->result();
				$this->data['orders']['wc_delivered_all'] = $wc_delivered_all;
				$this->data['orders']['wc_delivered_gbp'] = $wc_delivered_gbp;
				$this->data['orders']['wc_delivered_eur'] = $wc_delivered_eur;
				$this->data['orders']['wc_delivered_usd'] = $wc_delivered_usd;
				$this->data['orders']['wc_delivered_aed'] = $wc_delivered_aed;*/

				$completed_all = $this->General_Model->getOrders_v1_new('completed')->num_rows();
				$completed_gbp = $this->General_Model->getOrders_v1_new('completed','GBP')->result();
				$completed_eur = $this->General_Model->getOrders_v1_new('completed','EUR')->result();
				$completed_usd = $this->General_Model->getOrders_v1_new('completed','USD')->result();
				$completed_aed = $this->General_Model->getOrders_v1_new('completed','AED')->result();

				$this->data['orders']['completed_all'] = $completed_all;
				$this->data['orders']['completed_gbp'] = $completed_gbp;
				$this->data['orders']['completed_eur'] = $completed_eur;
				$this->data['orders']['completed_usd'] = $completed_usd;
				$this->data['orders']['completed_aed'] = $completed_aed;
				
				$confirmed_all = $this->General_Model->getOrders_v1_new('confirmed')->num_rows();
				$confirmed_gbp = $this->General_Model->getOrders_v1_new('confirmed','GBP')->result();
				$confirmed_eur = $this->General_Model->getOrders_v1_new('confirmed','EUR')->result();
				$confirmed_usd = $this->General_Model->getOrders_v1_new('confirmed','USD')->result();
				$confirmed_aed = $this->General_Model->getOrders_v1_new('confirmed','AED')->result();
				$this->data['orders']['confirmed_all'] = $confirmed_all;
				$this->data['orders']['confirmed_gbp'] = $confirmed_gbp;
				$this->data['orders']['confirmed_eur'] = $confirmed_eur;
				$this->data['orders']['confirmed_usd'] = $confirmed_usd;
				$this->data['orders']['confirmed_aed'] = $confirmed_aed;
				

				$getpaid_all = $this->General_Model->getOrders_v1_new('getpaid')->num_rows();
				$getpaid_gbp = $this->General_Model->getOrders_v1_new('getpaid','GBP')->result();
				$getpaid_eur = $this->General_Model->getOrders_v1_new('getpaid','EUR')->result();
				$getpaid_usd = $this->General_Model->getOrders_v1_new('getpaid','USD')->result();
				$getpaid_aed = $this->General_Model->getOrders_v1_new('getpaid','AED')->result();
				$this->data['orders']['getpaid_all'] = $getpaid_all;
				$this->data['orders']['getpaid_gbp'] = $getpaid_gbp;
				$this->data['orders']['getpaid_eur'] = $getpaid_eur;
				$this->data['orders']['getpaid_usd'] = $getpaid_usd;
				$this->data['orders']['getpaid_aed'] = $getpaid_aed;
				
				$cancelled_all = $this->General_Model->getOrders_v1_new('cancelled')->num_rows();
				$cancelled_gbp = $this->General_Model->getOrders_v1_new('cancelled','GBP')->result();
				$cancelled_eur = $this->General_Model->getOrders_v1_new('cancelled','EUR')->result();
				$cancelled_usd = $this->General_Model->getOrders_v1_new('cancelled','USD')->result();
				$cancelled_aed = $this->General_Model->getOrders_v1_new('cancelled','AED')->result();
				$this->data['orders']['cancelled_all'] = $cancelled_all;
				$this->data['orders']['cancelled_gbp'] = $cancelled_gbp;
				$this->data['orders']['cancelled_eur'] = $cancelled_eur;
				$this->data['orders']['cancelled_usd'] = $cancelled_usd;
				$this->data['orders']['cancelled_aed'] = $cancelled_aed;

				/*$pending_eticket_all = $this->General_Model->getOrders_v2('pending_eticket')->num_rows();
				$pending_eticket_gbp = $this->General_Model->getOrders_v2('pending_eticket','GBP')->result();
				$pending_eticket_eur = $this->General_Model->getOrders_v2('pending_eticket','EUR')->result();
				$pending_eticket_usd = $this->General_Model->getOrders_v2('pending_eticket','USD')->result();
				$pending_eticket_aed = $this->General_Model->getOrders_v2('pending_eticket','AED')->result();
				$this->data['orders']['pending_eticket_all'] = $pending_eticket_all;
				$this->data['orders']['pending_eticket_gbp'] = $pending_eticket_gbp;
				$this->data['orders']['pending_eticket_eur'] = $pending_eticket_eur;
				$this->data['orders']['pending_eticket_usd'] = $pending_eticket_usd;
				$this->data['orders']['pending_eticket_aed'] = $pending_eticket_aed;

				$pending_pod_all = $this->General_Model->getOrders_v3('pending_pod')->num_rows();
				$pending_pod_gbp = $this->General_Model->getOrders_v3('pending_pod','GBP')->result();
				$pending_pod_eur = $this->General_Model->getOrders_v3('pending_pod','EUR')->result();
				$ppending_pod_usd = $this->General_Model->getOrders_v3('pending_pod','USD')->result();
				$pending_pod_aed = $this->General_Model->getOrders_v3('pending_pod','AED')->result();
				$this->data['orders']['pending_pod_all'] = $pending_pod_all;
				$this->data['orders']['pending_pod_gbp'] = $pending_pod_gbp;
				$this->data['orders']['pending_pod_eur'] = $pending_pod_eur;
				$this->data['orders']['pending_pod_usd'] = $ppending_pod_usd;
				$this->data['orders']['pending_pod_usd'] = $pending_pod_aed;

				$finalising_all = $this->General_Model->getOrders_v1('FinalisingOrder')->num_rows();
				$finalising_gbp = $this->General_Model->getOrders_v1('FinalisingOrder','GBP')->result();
				$finalising_eur = $this->General_Model->getOrders_v1('FinalisingOrder','EUR')->result();
				$finalising_usd = $this->General_Model->getOrders_v1('FinalisingOrder','USD')->result();
				$finalising_aed = $this->General_Model->getOrders_v1('FinalisingOrder','AED')->result();
				$this->data['orders']['finalising_all'] = $finalising_all;
				$this->data['orders']['finalising_gbp'] = $finalising_gbp;
				$this->data['orders']['finalising_eur'] = $finalising_eur;
				$this->data['orders']['finalising_usd'] = $finalising_usd;
				$this->data['orders']['finalising_aed'] = $finalising_aed;*/

				$pending_all = $this->General_Model->getOrders_v1_new('pending')->num_rows();
				$pending_gbp = $this->General_Model->getOrders_v1_new('pending','GBP')->result();
				$pending_eur = $this->General_Model->getOrders_v1_new('pending','EUR')->result();
				$pending_usd = $this->General_Model->getOrders_v1_new('pending','USD')->result();
				$pending_aed = $this->General_Model->getOrders_v1_new('pending','AED')->result();
				$this->data['orders']['pending_all'] = $pending_all;
				$this->data['orders']['pending_gbp'] = $pending_gbp;
				$this->data['orders']['pending_eur'] = $pending_eur;
				$this->data['orders']['pending_usd'] = $pending_usd;
				$this->data['orders']['pending_aed'] = $pending_aed;

				$issue_all = $this->General_Model->getOrders_v1_new('issue')->num_rows();
				$issue_gbp = $this->General_Model->getOrders_v1_new('issue','GBP')->result();
				$issue_eur = $this->General_Model->getOrders_v1_new('issue','EUR')->result();
				$issue_usd = $this->General_Model->getOrders_v1_new('issue','USD')->result();
				$issue_aed = $this->General_Model->getOrders_v1_new('issue','AED')->result();
				$this->data['orders']['issue_all'] = $issue_all;
				$this->data['orders']['issue_gbp'] = $issue_gbp;
				$this->data['orders']['issue_eur'] = $issue_eur;
				$this->data['orders']['issue_usd'] = $issue_usd;
				$this->data['orders']['issue_aed'] = $issue_aed;
				}
				

					$load_side_filter = $this->load->view(THEME_NAME.'/game/load_side_filter', $this->data, TRUE);
					$response = array('load_side_filter' => $load_side_filter);
					echo json_encode($response);exit;

				}
			
			else{  
				if($_POST['filter'] != ""){ 
					if($_POST['filter'] == 'uploadticket'){

					$allcount = $this->General_Model->getOrders_v2('pending_eticket')->num_rows();
					$record   = $this->General_Model->getOrders_v2('pending_eticket','',$rowno, $row_per_page,$search)->result();

					}
					else if($_POST['filter'] == 'pending_pod'){

					$allcount = $this->General_Model->getOrders_v3('pending_pod')->num_rows();
					$record   = $this->General_Model->getOrders_v3('pending_pod','',$rowno, $row_per_page,$search)->result();
					//echo $this->db->last_query();exit;

					}
					else{

					$allcount = $this->General_Model->getOrders_v1($_POST['filter'])->num_rows();
					$record   = $this->General_Model->getOrders_v1($_POST['filter'],'',$rowno, $row_per_page,$search)->result();

					}
					

				}

				
			}

			$this->data['sellers'] = $this->General_Model->get_sellers();
			}

		} else {

			// Get records
			$record = $this->General_Model->get_limit_based_data($table, $rowno, $row_per_page, $order_column, $order_by, '')->result();
		} 
		// Pagination Configuration
		$config['base_url'] = base_url() . $url;
		$config['use_page_numbers'] = TRUE;
		$config['total_rows'] = $allcount;
		//$config['uri_segment'] = 4;
		$config['per_page'] = $row_per_page;
		$config['full_tag_open'] = "<nav><ul class='pagination'>";
		$config['full_tag_close'] = '</ul></nav>';
		$config['num_tag_open'] = '<li class=" page-item">';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active page-item"><a class="page-link getpage" href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['first_tag_open'] = '<li class=" page-item">';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class=" page-item">';
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = ' > ';
		$config['next_tag_open'] = '<li class=" page-item">';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = '  < ';
		$config['prev_tag_open'] = '<li class=" page-item">';
		$config['prev_tag_close'] = '</li>';
		$config['last_link'] = '>>';
		$config['first_link'] = '<<';

		// Initialize
		$this->pagination->initialize($config);

		$this->data['pagination'] = $this->pagination->create_links();
		$this->data[$variable_name] = $record;
		$this->data['row'] = $rowno;
		$this->data['search'] = $search;
		// Load view
		 if ($type == 'ajax_orders' || $type == 'get_order_search') {  
		 	//echo $view;exit;
		 	$list_orders = $this->load->view($view, $this->data, TRUE);
		 	$response = array('search_type' => 'listing', 'orders' => $list_orders,'pagination' => $this->data['pagination'],'rowno' => $rowno);
			echo json_encode($response);
		 }
		 else{
		 	$this->load->view($view, $this->data);
		 }
		
	}

	/**
	 * Add,edit,update,delete and list seat position
	 */

	public function seat_category()
	{

		$segment = $this->uri->segment(3);
		if ($segment == 'add') {
			$this->load->view('game/add_seat_category', $this->data);
		} else if ($segment == 'edit') {
			$segment4 = $this->uri->segment(4);
			if ($segment4 != "") {
				$edit_cat_id = $segment4;
				$this->data['category_details'] = $this->General_Model->get_seat_category_data($edit_cat_id)->row();
			}
			$this->load->view('game/add_seat_category', $this->data);
		} else if ($segment == 'delete') {
			$segment4 = $this->uri->segment(4);
			$delete_id = $segment4;
			$delete = $this->General_Model->delete_data('stadium_seats', 'id', $delete_id);
			if ($delete == 1) {
				$this->General_Model->delete_data('stadium_seats_lang', 'stadium_seat_id', $delete_id);
				$response = array('status' => 1, 'msg' => 'Seat position deleted Successfully.');
				// echo json_encode($response);
				// exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error while deleting seat position.');
				// echo json_encode($response);
				// exit;
			}
			redirect('/game/seat_category');
		} else if ($segment == 'save') {
			if ($this->input->post()) {
				$this->form_validation->set_rules('seat_position', 'Seat Position', 'required');
				$this->form_validation->set_rules('event', 'For event', 'required');
				$this->form_validation->set_rules('is_active', 'Status', 'required');

				if ($this->form_validation->run() !== false) {
					$editcatId = $this->input->post('category_id');
					if ($editcatId == '') {
						$status = $this->input->post('is_active') ? 1 : 0;
						$insert_data = array(
							'seat_category' => $this->input->post('seat_position'),
							'status' => $status,
							'event_type' => $this->input->post('event'),
							// 'add_by' => $this->session->userdata('admin_id'),
							'create_date' => strtotime(date('Y-m-d H:i:s'))
						);
						$inserted_id = $this->General_Model->insert_data('stadium_seats', $insert_data);
						if ($inserted_id) {
							$lang = $this->General_Model->getAllItemTable('language', 'store_id', $this->session->userdata('storefront')->admin_id)->result();
							foreach ($lang as $key => $l_code) {
								$language_data = array(
									'language' =>  $l_code->language_code,
									'stadium_seat_id' => $inserted_id,
									'seat_category' => $this->input->post('seat_position')
								);
								$this->General_Model->insert_data('stadium_seats_lang', $language_data);
							}
							$response = array('msg' => 'New Seat Position Created Successfully.', 'redirect_url' => base_url() . 'game/category', 'status' => 1);
						} else {
							$response = array('msg' => 'Failed to create new seat position.', 'redirect_url' => base_url() . 'game/seat_category/add', 'status' => 0);
						}
						redirect('/game/seat_category');
						// echo json_encode($response);
						exit;
					} else {
						$updateData = array();
						$updateData_lang = array();
						$updateData['seat_category'] = trim($this->input->post('seat_position'));
						$updateData['event_type'] = trim($this->input->post('event'));
						$updateData['status'] = $this->input->post('is_active') ? 1 : 0;
						$this->General_Model->update('stadium_seats', array('id' => $editcatId), $updateData);

						//Update language table			
						$updateData_lang['seat_category'] = trim($this->input->post('seat_position'));
						$this->General_Model->update('stadium_seats_lang', array('stadium_seat_id' => $editcatId, 'language' => $this->session->userdata('language_code')), $updateData_lang);

						redirect('/game/seat_category');
						// $response = array('status' => 1, 'msg' => 'Seat position data updated Successfully.', 'redirect_url' => base_url() . 'game/seat_category');
						// echo json_encode($response);
						exit;
					}
				} else {
					redirect('/game/seat_category');
					// $response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'game/seat_category/add', 'status' => 0);
					exit;
				}
				echo json_encode($response);
				exit;
			}
		} else {
			$row_count = $this->uri->segment(3);
			$this->loadRecord($row_count, 'stadium_seats', 'game/seat_category', 'id', 'DESC', 'game/seat_category_list', 'categories', 'seatcategory');
		}
	}

	function getStadiumByid()
	{
		$stadiumId = $this->input->post('stadiumid');
		$getStadiumByid = $this->General_Model->getAllItemTable('stadium', 's_id', $stadiumId)->result();
		$inputValidation = "OK";
		$set['Status'] = $inputValidation;
		$set['Json'] = $getStadiumByid;


		echo $val = str_replace('\\/', '/', json_encode($set));
	}

	function getSellTicketsById()
	{
		if ($this->input->post()) {
			$matchId = $this->input->post('matchid');
			$noTickets = $this->input->post('notickets');
			$ticketCategory = $this->input->post('ticketcategory');

			if (!empty($ticketCategory)) {
				$getSellTicketsByid = $this->db->query("SELECT *, `quantity`-`sold` AS `difference` FROM `sell_tickets` WHERE ticket_category=$ticketCategory AND match_id=$matchId HAVING `difference` >= $noTickets")->result();
			} else {
				$getSellTicketsByid = $this->db->query("SELECT *, `quantity`-`sold` AS `difference` FROM `sell_tickets` WHERE match_id=$matchId HAVING `difference` >= $noTickets")->result();
			}


			if ($getSellTicketsByid) {

				foreach ($getSellTicketsByid as $key => $filter_ticket) {

					if (($filter_ticket->split == 3) && ($filter_ticket->difference - $noTickets == 1) && ($noTickets != 0)) {
						unset($getSellTicketsByid[$key]);
					} else if ($filter_ticket->split == 2 && $filter_ticket->difference != $noTickets && ($noTickets != 0)) {

						unset($getSellTicketsByid[$key]);
					} else if (($filter_ticket->split == 4) && ($noTickets % 2 != 0) && ($noTickets != 0)) {

						unset($getSellTicketsByid[$key]);
					} else {
					}
				}
			}


			$inputValidation = "OK";
			$set['Status'] = $inputValidation;
			$set['Json'] = $getSellTicketsByid;

			echo $val = str_replace('\\/', '/', json_encode($set));
		} else {

			redirect(base_url());
		}
	}

	function getStadiumDetailsByid()
	{
		$stadiumId = $this->input->post('stadiumid');
		$getStadiumDetailsByid = $this->General_Model->getAllItemTable_Array('stadium_details', array('stadium_id' => $stadiumId))->result()->result();
		$inputValidation = "OK";
		$set['Status'] = $inputValidation;
		$set['Json'] = $getStadiumDetailsByid;
		echo $val = str_replace('\\/', '/', json_encode($set));
	}

	function getStadiumDetailsByBlockid()
	{
		$blockid = $this->input->post('blockid');
		$stadiumid = $this->input->post('stadiumid');

		$getStadiumDetailsByid = $this->General_Model->getAllItemTable_array('stadium_details', array('block_id' => $blockid, 'stadium_id' => $stadiumid))->result();
		$inputValidation = "OK";
		$set['Status'] = $inputValidation;
		$set['Json'] = $getStadiumDetailsByid;
		echo $val = str_replace('\\/', '/', json_encode($set));
	}

	function update_statdium_block()
	{
		$stadiumId = $this->input->post('stadiumid');
		$color = $this->input->post('color');
		$href = $this->input->post('href');
		$block_id = $this->input->post('block_id');

		$getStadiumByid = $this->General_Model->getAllItemTable_array('stadium', array('s_id' => $stadiumId))->result();

		$someArray = json_decode($getStadiumByid[0]->map_code, true);



		if (array_key_exists($block_id, $someArray['regions'])) {

			foreach ($someArray['regions'] as $key => $sr) {

				if ($block_id == $key) {
					$someArray['regions'][$key]['fill'] = $color;
					$someArray['regions'][$key]['href'] = $href;
					$someArray['regions'][$key]['tooltip'] = $href;
				}
			}
		} else {

			$regions = array();
			$regions['id'] = $block_id;
			$regions['id_no_spaces'] = $block_id;
			$regions['fill'] = $color;
			$regions['href'] = $href;
			$regions['tooltip'] = $href;

			$regions['data'] = array();

			$someArray['regions'][$block_id] = $regions;
		}


		$updateData = array();
		$updateData['map_code'] = json_encode($someArray);
		$this->General_Model->update_table('stadium', 's_id', $stadiumId, $updateData);

		$getStadiumDetailsByid = $this->General_Model->getAllItemTable_array('stadium_details', array('stadium_id' => $stadiumId, 'block_id' => $block_id))->row();

		if ($getStadiumDetailsByid) {

			$updateData_stddetail = array();
			$updateData_stddetail['category'] = $href;
			$updateData_stddetail['block_color'] = $color;
			$this->General_Model->update('stadium_details', array('stadium_id' => $stadiumId, 'block_id' => $block_id), $updateData_stddetail);
		} else {

			$insertStatdiumData = array();
			$insertStatdiumData['stadium_id'] = $stadiumId;
			$insertStatdiumData['block_id'] = $block_id;
			$insertStatdiumData['block_color'] = $color;
			$insertStatdiumData['category'] = $href;
			$this->General_Model->insert_data('stadium_details', $insertStatdiumData);
		}

		echo 'true';
	}

	function getPostalCode()
	{
		$getMatchByid = $this->General_Model->getAllItemTable_array('countries', array('id' => $this->input->post('country_id')), NULL, NULL, NULL, array('name', 'asc'))->result();
		$codeCount = COUNT($getMatchByid);
		//State option list
		if ($codeCount > 0) {
			foreach ($getMatchByid as $getMatch) {
				echo '+' . $getMatch->phonecode . '||' . strtolower($getMatch->sortname);
			}
		} else {
			echo '';
		}
	}

	function getCouponCode()
	{

		if ($this->input->post()) {

			$couponCode = $this->input->post('couponcode');
			$getCouponCode = $this->General_Model->getAllItemTable_array('coupon_code', array('coupon_code' => $couponCode, 'status' => 1))->result();
			if (count($getCouponCode) == 1) {
				$currentDate = date('m-d-Y');
				$expiryDate = $getCouponCode[0]->expiry_date;
				//$expiryDate = $getCouponCode[0]->expiry_date;
				if ($currentDate > $expiryDate) {
					$getCouponCodeData = '';
					$message = '<span class="failed-code">Promocode expired.</span>';
					$inputValidation = 'Not ok';
				} else {
					$getCouponCodeData = $this->General_Model->getAllItemTable_array('coupon_code', array('coupon_code' => $couponCode, 'status' => 1))->result();
					$message = '<span class="success-code">Promocode applied successfully.</span>';
					$inputValidation = 'OK';
				}
				// $message = '<span class="success-code">Promocode applied successfully.</span>';
			} else {
				$message = '<span class="failed-code">Invailed code entered.</span>';
				$inputValidation = 'Not ok';
			}
			$inputValidation = $inputValidation;
			$set['Status'] = $inputValidation;
			$set['Message'] = $message;
			$set['Json'] = $getCouponCodeData;
			echo $val = str_replace('\\/', '/', json_encode($set));
		} else {
			redirect(base_url());
		}
	}

	function base_currency_totalamount($total_price = NULL, $to_currency = NULL)
	{
		echo $this->General_Model->currencyConverterMap2($this->input->post('total_price'), $this->session->userdata('currency'), $this->input->post('to_currency'));
	}

	function getAddressDropdown()
	{
		if (!empty($this->input->post('country_id'))) {
			$getMatchByid = $this->General_Model->getAllItemTable_array('states', array('country_id' => $this->input->post('country_id')), NULL, NULL, NULL, array('name', 'asc'))->result();
			$statesCount = COUNT($getMatchByid);
			if ($statesCount > 0) {
				$state = $this->input->post('state');
				echo '<option value="">Select State</option>';
				foreach ($getMatchByid as $getMatch) {
					$selected = '';
					if ($state == $getMatch->id) {
						$selected = 'selected="selected"';
					}
					echo '<option value="' . $getMatch->id . '" ' . $selected . '>' . $getMatch->name . '</option>';
				}
			} else {
				echo '<option value="">State not available</option>';
			}
		}
	}


	public function stadium_category()
	{
		$segment = $this->uri->segment(3);
		if ($segment == 'add') {
			$this->load->view('game/add_stadium_category', $this->data);
		} else if ($segment == 'edit') {
			$segment4 = $this->uri->segment(4);
			if ($segment4 != "") {
				$edit_cat_id = $segment4;
				$this->data['category_details'] = $this->General_Model->get_seat_category_data($edit_cat_id)->row();
			}
			$this->load->view('game/add_stadium_category', $this->data);
		} else if ($segment == 'delete') {
			$segment4 = $this->uri->segment(4);
			$delete_id = $segment4;
			$delete = $this->General_Model->delete_data('stadium_seats', 'id', $delete_id);
			if ($delete == 1) {
				$this->General_Model->delete_data('stadium_seats_lang', 'stadium_seat_id', $delete_id);
				$response = array('status' => 1, 'msg' => 'Stadium Category deleted Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error while deleting stadium category.');
				echo json_encode($response);
				exit;
			}
			redirect('/game/stadium_category');
		} else if ($segment == 'save') {
			if ($this->input->post()) {
				$this->form_validation->set_rules('seat_position', 'Seat Position', 'required');
				$this->form_validation->set_rules('event', 'For event', 'required');
				//$this->form_validation->set_rules('is_status', 'Status', 'required');

				if ($this->form_validation->run() !== false) {
					$editcatId = $this->input->post('category_id'); 
					if ($editcatId == '') {
						$status = $this->input->post('is_status') ? 1 : 0;
						$insert_data = array(
							'seat_category' => $this->input->post('seat_position'),
							'status' => $status,
							'event_type' => $this->input->post('event'),
							// 'add_by' => $this->session->userdata('admin_id'),
							'create_date' => strtotime(date('Y-m-d H:i:s'))
						);
						$inserted_id = $this->General_Model->insert_data('stadium_seats', $insert_data);
						if ($inserted_id) {
							$lang = $this->General_Model->getAllItemTable('language', 'store_id', $this->session->userdata('storefront')->admin_id)->result();
							foreach ($lang as $key => $l_code) {
								$language_data = array(
									'language' =>  $l_code->language_code,
									'stadium_seat_id' => $inserted_id,
									'seat_category' => $this->input->post('seat_position')
								);
								$this->General_Model->insert_data('stadium_seats_lang', $language_data);
							}
							$response = array('status' => 1, 'msg' => 'New Stadium Categoty Created Successfully.','redirect_url' => base_url() . 'game/stadium_category', 'status' => 1);
							echo json_encode($response);
							exit;
						} else {
							$response = array('msg' => 'Failed to create new seat position.', 'redirect_url' => base_url() . 'game/stadium_category/add', 'status' => 0);
						}
						echo json_encode($response);
						exit;
					} else { 
						$updateData = array();
						$updateData_lang = array();
						$updateData['seat_category'] = trim($this->input->post('seat_position'));
						$updateData['event_type'] = trim($this->input->post('event'));
						$updateData['status'] = $this->input->post('is_status') ? 1 : 0;
						
						$this->General_Model->update('stadium_seats', array('id' => $editcatId), $updateData);
						
						//Update language table			
						$updateData_lang['seat_category'] = trim($this->input->post('seat_position'));
						$this->General_Model->update('stadium_seats_lang', array('stadium_seat_id' => $editcatId, 'language' => $this->session->userdata('language_code')), $updateData_lang);
						$response = array('status' => 1, 'msg' => 'Stadium Category data updated Successfully.','redirect_url' => base_url() . 'game/stadium_category');
						echo json_encode($response);
						exit;
					}
				} else {
					//redirect('/game/stadium_category');
					$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'game/stadium_category', 'status' => 0);
				}
				echo json_encode($response);
				exit;
			}
		} else {
			$row_count = $this->uri->segment(3);
			$this->loadRecord($row_count, 'stadium_seats', 'game/stadium_category', 'id', 'DESC', 'game/stadium_category_list', 'categories', 'seatcategory');
		}
		
	}

function filterData(&$str){ 
    $str = preg_replace("/\t/", "\\t", $str); 
    $str = preg_replace("/\r?\n/", "\\n", $str); 
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
}

	public function download_orders(){

		$download_orders = $this->General_Model->download_seller_orders()->result();
		//echo "<pre>";print_r($download_orders);exit;
$fileName = "Seller_OrderReports_" . date('Y-m-d') . ".xls"; 
// Column names 
$fields = array('OrderNo','OrderDate','Tournament','Match','MatchDate','MatchTime','Stadium','SellerName','SeatCategory','Row','Qty','SoldAt','Currency'); 
// Display column names as first row 
$excelData = implode("\t", array_values($fields)) . "\n"; 

  foreach($download_orders as $download_order){  
        $lineData = array($download_order->booking_no,$download_order->updated_at,$download_order->tournament_name,$download_order->match_name,$download_order->match_date,$download_order->match_time,$download_order->stadium_name.','.$download_order->stadium_city_name.','.$download_order->stadium_country_name,$download_order->seller_first_name.' '.$download_order->seller_last_name,$download_order->seat_category,$download_order->row,$download_order->quantity,number_format($download_order->ticket_amount,2),$download_order->currency_type); 
       
        $excelData .= implode("\t", array_values($lineData)) . "\n"; 
    } 
header("Content-Type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=\"$fileName\""); 
 
// Render excel data 
echo $excelData; 
 
exit;

		echo "<pre>";print_r($download_orders);exit;
		echo "download_orders";exit;
	}
}
