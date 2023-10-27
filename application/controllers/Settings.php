<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(E_ALL);
class Settings extends CI_Controller
{
	public function __construct()
	{
		/*
         *  Developed by: Shalini S
         *  Date    : 18 Feb, 2022
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




	/**
	 * @desc currency settings related operations
	 * Add
	 * Edit
	 * List
	 * Delete
	 * Save
	 */
	public function currency()
	{

		$segment = $this->uri->segment(3);
		if ($segment == 'add_currency') {
			$segment4 = $this->uri->segment(4);
			if ($segment4 != "") {
				$edit_id = $segment4;
				$this->data['currency_details'] = $this->General_Model->getAllItemTable('currency_types', 'id', $edit_id, 'id', 'DESC')->row();
			}
			$this->load->view('settings/add_currency', $this->data);
		} else if ($segment == 'list_currency') {
			$row_count = $this->uri->segment(4);
			$this->loadRecord($row_count, 'currency_types', 'settings/currency/list_currency', 'id', 'DESC', 'settings/currency_list', 'currencies');
		} else if ($segment == 'delete_currency') {
			$segment4 = $this->uri->segment(4);
			$delete_id = $segment4;
			$delete = $this->General_Model->delete_data('currency_types', 'id', $delete_id);
			if ($delete == 1) {
				$response = array('status' => 1, 'msg' => 'Currency type deleted Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error while deleting currency type.');
				echo json_encode($response);
				exit;
			}
		} else if ($segment == 'save_currency') {
			$this->form_validation->set_rules('currency_name', 'Currency Name', 'required');
			$this->form_validation->set_rules('currency_code', 'Currency Code', 'required');
			$this->form_validation->set_rules('currency_symbol', 'Currency Symbol', 'required');
			$this->form_validation->set_rules('status', 'Status', 'required');
			$this->form_validation->set_rules('difference', 'Price Difference', 'required');
			if ($this->form_validation->run() !== false) {
				$insert_data = array(
					'name' => $_POST['currency_name'],
					'currency_code' => $_POST['currency_code'],
					'symbol' => $_POST['currency_symbol'],
					'status' => $_POST['status'],
					'price_difference' => $_POST['difference'],
				);
				if ($_POST['currency_id'] == '') {
					$insert_data['add_by'] = $this->session->userdata('admin_id');
					$insert_data['store_id'] = $this->session->userdata('storefront')->admin_id;
					$inserted_id = $this->General_Model->insert_data('currency_types', $insert_data);
					if ($inserted_id) {
						$response = array('msg' => 'Currency type details added successfully.', 'redirect_url' => base_url() . 'settings/currency/list_currency', 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to add new currency type.', 'redirect_url' => base_url() . 'settings/currency/add_currency', 'status' => 0);
					}
					echo json_encode($response);
					exit;
				} else {
					$currency_id = $_POST['currency_id'];
					if ($this->General_Model->update_table('currency_types', 'id', $currency_id, $insert_data)) {
						$response = array('msg' => 'Currency type details updated Successfully.', 'redirect_url' => base_url() . 'settings/currency/list_currency', 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to update game category details.', 'redirect_url' => base_url() . 'settings/currency/add_currency/' . $currency_id, 'status' => 0);
					}
					echo json_encode($response);
					exit;
				}
			} else {
				$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'settings/currency/add_currency', 'status' => 0);
			}
			echo json_encode($response);
			exit;
		}
	}



	public function match_settings()
	{

		$segment = $this->uri->segment(3);
		if ($segment == 'edit_email_access') {
			$segment4 = json_decode(base64_decode($this->uri->segment(4)));
			if ($segment4 != "") {
				$edit_id = $segment4;
				$this->data['email_access'] = $this->General_Model->getAllItemTable('email_access', 'id', $edit_id, 'id', 'DESC')->row();
			}
			$this->load->view('settings/email_settings/edit_email_access', $this->data);
		} else if ($segment == 'set_match_settings') {
			$segment4 = json_decode(base64_decode($this->uri->segment(4)));
			$edit_id = $segment4;
			$this->data['sellers']    = $this->General_Model->get_admin_details_by_role_v1(1, 'status');
			$this->data['partners']   = $this->General_Model->get_admin_details_by_role_v1(2, 'status');
			$this->data['afiliates']  = $this->General_Model->get_admin_details_by_role_v1(3, 'status');
			$this->data['storefronts']  = $this->General_Model->get_admin_details_by_role_v1(4, 'status');
			$this->data['match_settings']  = $this->General_Model->getAllItemTable('match_settings', 'matches', $segment4, 'matches', 'DESC')->row();
			$this->load->view('settings/match_settings/set_match_settings', $this->data);
		} else if ($segment == 'match_settings') {

			$row_count = $this->uri->segment(4);
			$this->loadRecord($row_count, 'matches', 'settings/match_settings/match_settings', 'm_id', 'DESC', 'settings/match_settings/match_settings', 'matches');
		} else if ($segment == 'save_match_settings') {

			// echo "<pre>";print_r($_POST);exit;

			$this->form_validation->set_rules('matchId', 'Match', 'required');
			if ($this->form_validation->run() !== false) {

				$match_count = $this->General_Model->getid('match_settings', array('matches' => $_POST['matchId']))->num_rows();
				if ($match_count == 0) {

					$match_settings_data['matches'] = $_POST['matchId'];
					$this->db->insert('match_settings', $match_settings_data);
				}
				$sellers_data = "";
				if ($_POST['sellers']) {
					$sellers_data = implode(',', $_POST['sellers']);
				}
				$partners_data = "";
				if ($_POST['partners']) {
					$partners_data = implode(',', $_POST['partners']);
				}
				$afiliates_data = "";
				if ($_POST['afiliates']) {
					$afiliates_data = implode(',', $_POST['afiliates']);
				}
				$storefronts_data = "";
				if ($_POST['storefronts']) {
					$storefronts_data = implode(',', $_POST['storefronts']);
				}
				$match_settings_data = array(
					'sellers' => $sellers_data,
					'partners' => $partners_data,
					'afiliates' => $afiliates_data,
					'storefronts' => $storefronts_data,
					'status' => $_POST['status']
				); //echo "<pre>";print_r($match_settings_data);exit;

				$matchId = $_POST['matchId'];
				if ($this->General_Model->update_table('match_settings', 'matches', $matchId, $match_settings_data)) {
					$response = array('msg' => 'Match Settings updated Successfully.', 'redirect_url' => base_url() . 'settings/match_settings/match_settings', 'status' => 1);
				} else {
					$response = array('msg' => 'Failed to update Match Settings.', 'redirect_url' => base_url() . 'settings/match_settings/match_settings', 'status' => 0);
				}
				echo json_encode($response);
				exit;
			} else {
				$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'settings/match_settings/match_settings', 'status' => 0);
			}
			echo json_encode($response);
			exit;
		} else if ($segment == 'save_email_settings') {

			$this->form_validation->set_rules('smtp', 'SMTP', 'required');
			$this->form_validation->set_rules('host', 'Host', 'required');
			$this->form_validation->set_rules('port', 'Port', 'required');
			$this->form_validation->set_rules('username', 'Username', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');
			if ($this->form_validation->run() !== false) {
				$insert_data = array(
					'smtp' => $_POST['smtp'],
					'host' => $_POST['host'],
					'port' => $_POST['port'],
					'username' => $_POST['username'],
					'status' => $_POST['status'],
					'password' => $_POST['password'],
				);
				if ($_POST['id'] == '') {
					$response = array('msg' => 'You Cant able to new email access settings.', 'redirect_url' => base_url() . 'settings/email_settings/email_list', 'status' => 0);
					echo json_encode($response);
					exit;
				} else {
					$access_id = $_POST['id'];
					if ($this->General_Model->update_table('email_access', 'id', $access_id, $insert_data)) {
						$response = array('msg' => 'Email Access Settings updated Successfully.', 'redirect_url' => base_url() . 'settings/email_settings/email_list', 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to update Email Access details.', 'redirect_url' => base_url() . 'settings/email_settings/email_list', 'status' => 0);
					}
					echo json_encode($response);
					exit;
				}
			} else {
				$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'settings/email_settings/email_list', 'status' => 0);
			}
			echo json_encode($response);
			exit;
		}
	}

	public function tournaments()
	{
		$url_segment  = $segment = $this->uri->segment(3);
		$tournament_id       = $this->uri->segment(4);

		if ($url_segment == "add") {
			$this->data['countries'] = $this->General_Model->getAllItemTable('countries')->result();
			$this->data['apiLeague'] =  $this->getApiLeague();
			$this->load->view('event/add_tournament', $this->data);
		}
		if ($url_segment == "add_content_tournment") {
			$this->data['countries'] = $this->General_Model->getAllItemTable('countries')->result();
			$this->data['apiLeague'] =  $this->getApiLeague();
			if ($tournament_id != '') {
				$this->data['tournaments']      = $this->General_Model->get_tournament_data($tournament_id)->row();
				$getBannedCountries = $this->db->query("SELECT * FROM `banned_countries_tournament` WHERE `tournament_id` = " . $tournament_id)->result();
				$ban_arr = [];
				foreach ($getBannedCountries as $bc) {
					$ban_arr[] = $bc->country_id;
				}
				$this->data['ban_arr'] = $ban_arr;
			}
			$this->load->view('event/add_content_tournment', $this->data);
		} 
		 else if ($url_segment == "edit") {
			$this->data['countries'] = $this->General_Model->getAllItemTable('countries')->result();
			$this->data['apiLeague'] =  $this->getApiLeague();
			if ($tournament_id != '') {
				$this->data['tournaments']      = $this->General_Model->get_tournament_data($tournament_id)->row();
				$getBannedCountries = $this->db->query("SELECT * FROM `banned_countries_tournament` WHERE `tournament_id` = " . $tournament_id)->result();
				$ban_arr = [];
				foreach ($getBannedCountries as $bc) {
					$ban_arr[] = $bc->country_id;
				}
				$this->data['ban_arr'] = $ban_arr;
			}

			$this->load->view('event/add_tournament', $this->data);
		} else if ($url_segment == "save") {
			$tournamentId = $this->input->post('tournamentId');
			//Insert into table
			if ($tournamentId == '') {
				if ($this->input->post()) {
					$msg = '';
					$this->form_validation->set_rules('name', 'Tournament Name', 'required');
					if (!empty($_FILES['tournament_image']['name'])) {
						$this->form_validation->set_rules('tournament_image', 'Image file', 'callback_timage_file_check');
					}
					$insertData = array();
					if ($this->form_validation->run() !== false) {

						if (!empty($_FILES['tournament_image']['name'])) {
							$config['upload_path'] = 'uploads/tournaments';
							$config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF|';
							//$config['max_size'] = '10000';
							$config['encrypt_name'] = TRUE;
							$this->load->library('upload', $config);
							if ($this->upload->do_upload('tournament_image')) {
								$outputData['tournament_image'] = $this->upload->data();
								$insertData['tournament_image'] = $outputData['tournament_image']['file_name'];
							} else {
								$msg .= 'Failed to add tournament image';
							}
						}

						$insertData['tournament_name'] = trim($this->input->post('name'));
						$insertData['status'] = $this->input->post('is_active') ? 1 : 0;
						$insertData['create_date'] = strtotime(date('Y-m-d H:i:s'));
						$insertData['popular_tournament'] = $this->input->post('is_popular') ? 1 : 0;
						$insertData['sort_by'] = trim($this->input->post('sortby'));
						$insertData['page_title'] = strip_tags($this->input->post('title'));
						$insertData['meta_description'] = $this->input->post('metadescription');
						$insertData['page_content'] = $this->input->post('tournament_content');
						$insertData['api_tournament_id'] = trim($this->input->post('apileague'));
						$insertData['tournament_url'] = str_replace(" ", "-", trim($this->input->post('name')));
						$insertData['toggle_pc'] = $this->input->post('toggle_pc') ? 1 : 0;
						$insertData['toggle_cl'] = $this->input->post('toggle_cl') ? 1 : 0;
						$insertData['toggle_cr'] = $this->input->post('toggle_cr') ? 1 : 0;
						$insertData['show_in_list'] = $this->input->post('show_tournament') ? 1 : 0;
						$insertData['url_key']=$this->input->post('url_key');
						$t_id = $this->General_Model->insert_data('tournament', $insertData);

						$this->db->delete('banned_countries_tournament', array('tournament_id' => $t_id));
						$bancountry_ids = $this->input->post('bcountry');
						foreach ($bancountry_ids as $val) {
							$this->data = array(
								'tournament_id' => $t_id,
								'country_id' => trim($val)
							);
							$this->db->insert('banned_countries_tournament', $this->data);
						}

						//Add to language table
						$lang = $this->General_Model->getAllItemTable('language','store_id',$this->session->userdata('storefront')->admin_id)->result();
						foreach ($lang as $key => $l_code) {
							$insertData_lang = array();
							$insertData_lang['tournament_id'] = $t_id;
							$insertData_lang['language'] = $l_code->language_code;
							$insertData_lang['tournament_name'] = trim($this->input->post('name'));
							$insertData_lang['tournament_image'] = $insertData['tournament_image'];
							$insertData_lang['page_title'] = strip_tags($this->input->post('title'));
							$insertData_lang['meta_description'] = $this->input->post('metadescription');
							$insertData_lang['page_content'] = $this->input->post('tournament_content');
							$insertData_lang['tournament_content_left'] = $this->input->post('t_content_left');
							$insertData_lang['tournament_content_right'] = $this->input->post('t_content_right');
							$this->General_Model->insert_data('tournament_lang', $insertData_lang);
						}

						$response = array('status' => 1, 'msg' => 'Tournament Created Successfully. ' . $msg, 'redirect_url' => base_url() . 'settings/tournaments');
						echo json_encode($response);
						exit;
					} else {
						$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'settings/tournaments/add', 'status' => 0);
						echo json_encode($response);
						exit;
					}
				}
			} else {
				$tournament_id =    $tournamentId;
				//echo "<pre>";print_r($_POST);exit;
				//if its an update request
				if ($this->input->post()) {

					if($_POST['flag'] != 'content'){
					$this->form_validation->set_rules('name', 'Tournament Name', 'required');
					if (!empty($_FILES['tournament_image']['name'])) {
						$this->form_validation->set_rules('tournament_image', 'Image file', 'callback_timage_file_check');
					}
					}
					else{
					$this->form_validation->set_rules('title', 'Tournament Title', 'required');
					}
					$updateData = array();
					$updateData_lang = array();
					
					$msg = '';
					if ($this->form_validation->run() !== false) {

						if($_POST['flag'] != 'content'){

						if (!empty($_FILES['tournament_image']['name'])) {
							$teamdata = $this->General_Model->getAllItemTable_array('tournament', array('t_id' => $tournament_id))->row();
							if (@getimagesize(base_url() . './uploads/tournaments/' . $teamdata->tournament_image)) {
								unlink('./uploads/tournaments/' . $teamdata->tournament_image);
							}
							$config['upload_path'] = 'uploads/tournaments';
							$config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF|';
							//$config['max_size'] = '1000';
							$config['encrypt_name'] = TRUE;
							$this->load->library('upload', $config);
							if (!$this->upload->do_upload('tournament_image')) {
								$msg .= 'Failed to add tournament image';
							} else {
								$data = $this->upload->data();
								$imagename = $data['file_name'];
								$updateData_lang['tournament_image'] = $imagename;
								$updateData['tournament_image'] = $imagename;
							}
						} else {
							$updateData_lang['tournament_image'] = $this->input->post('exs_file');
						}

						$updateData['tournament_name'] = trim($this->input->post('name'));
						$updateData['sort_by'] = trim($this->input->post('sortby'));
						$updateData['api_tournament_id'] = trim($this->input->post('apileague'));
						$updateData['status'] = $this->input->post('is_active') ? 1 : 0;
						$updateData['popular_tournament'] = $this->input->post('is_popular') ? 1 : 0;
						$updateData['show_in_list'] = $this->input->post('show_tournament') ? 1 : 0;
						$updateData['toggle_pc'] = $this->input->post('toggle_pc') ? 1 : 0;
						$updateData['toggle_cl'] = $this->input->post('toggle_cl') ? 1 : 0;
						$updateData['toggle_cr'] = $this->input->post('toggle_cr') ? 1 : 0;

					}
					else{

						$updateData['page_title'] = strip_tags($this->input->post('title'));
						$updateData['meta_description'] = $this->input->post('metadescription');
						$updateData['page_content'] = $this->input->post('tournament_content');
						$updateData['url_key']=$this->input->post('url_key');
						$updateData_lang['page_title'] = strip_tags($this->input->post('title'));
						$updateData_lang['meta_description'] = $this->input->post('metadescription');
						$updateData_lang['page_content'] = $this->input->post('tournament_content');
						$updateData_lang['tournament_content_left'] = $this->input->post('t_content_left');
						$updateData_lang['tournament_content_right'] = $this->input->post('t_content_right');
						$updateData['seo_keywords'] = $this->input->post('seo_keywords');
						
					}
						
						
						
						
						if ($this->session->userdata('language_code') == 'en') {
							$updateData['tournament_url'] = str_replace(" ", "-", trim($this->input->post('name')));
						}
						$this->General_Model->update('tournament', array('t_id' => $tournament_id), $updateData);

						if($_POST['flag'] != 'content'){

						$this->db->delete('banned_countries_tournament', array('tournament_id' => $tournament_id));
						$bancountry_ids = $this->input->post('bcountry');
						foreach ($bancountry_ids as $val) {
							$this->data = array(
								'tournament_id' => $tournament_id,
								'country_id' => trim($val)
							);
							$this->db->insert('banned_countries_tournament', $this->data);
						}

						$updateData_lang['tournament_name'] = trim($this->input->post('name'));
						
						}
						//Update language table         
						
						
						$this->General_Model->update('tournament_lang', array('tournament_id' => $tournament_id, 'language' => $this->session->userdata('language_code')), $updateData_lang);

						$response = array('status' => 1, 'msg' => 'Tournament data updated Successfully.' . $msg, 'redirect_url' => base_url() . 'settings/tournaments');
						echo json_encode($response);
						exit;
					}
				} else {
					$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'settings/tournaments/add', 'status' => 0);
					echo json_encode($response);
					exit;
				}
			}
		} else if ($url_segment == "delete") {
			$t_id   = $this->uri->segment(4);
			$updateData_data['status'] = 2;
			//$delete = $this->General_Model->delete_data('tournament', 't_id', $t_id);
			$delete = $this->General_Model->update('tournament', array('t_id' => $t_id), $updateData_data);
			if ($delete == 1) {
				//$this->General_Model->delete_data('tournament_lang', 'tournament_id', $t_id);
				$response = array('status' => 1, 'msg' => 'Tournament data deleted Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error While Deleting tournament data.');
				echo json_encode($response);
				exit;
			}
		}
		else if ($url_segment == "delete_trash") {
			$t_id   = $this->uri->segment(4);
			$updateData_data['status'] = 1;
			//$delete = $this->General_Model->delete_data('tournament', 't_id', $t_id);
			$delete = $this->General_Model->update('tournament', array('t_id' => $t_id), $updateData_data);
			if ($delete == 1) {
				//$this->General_Model->delete_data('tournament_lang', 'tournament_id', $t_id);
				$response = array('status' => 1, 'msg' => 'Tournament moved from trash Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error While Undoing tournament data.');
				echo json_encode($response);
				exit;
			}
		} else {
			
			$row_count = $this->uri->segment(3);
			if ($this->input->post('submit') != NULL) {
				$search_text = $this->input->post('search');
				$this->session->set_userdata(array("searcht" => $search_text));
			} else {
				if ($this->session->userdata('searcht') != NULL) {
					$search_text = $this->session->userdata('searcht');
				}
			}

			$this->loadRecord_v1($row_count, 'tournament', 'settings/tournaments', 't_id', 'DESC', 'event/tournaments', 'tournaments', 'tournament',$search_text);
		}
	}

	public function getApiLeague()
	{
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://api-football-v1.p.rapidapi.com/v2/leagues",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"x-rapidapi-host: api-football-v1.p.rapidapi.com",
				"x-rapidapi-key: f84f64646cmsh2de81a07982e478p100b35jsn7e6f01dcec8d"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			//echo "cURL Error #:" . $err;
		} else {

			return json_decode($response);
		}
	}


	public function teams()
	{
		$url_segment  = $segment = $this->uri->segment(3);
		$team_id       = $this->uri->segment(4);

		if ($url_segment == "add_team") {
			$this->data['gcategory'] = $this->General_Model->get_game_category()->result();

			if ($team_id != '') {
				$this->data['teams']      = $this->General_Model->get_team_data($team_id)->row();
			}

			$this->load->view('event/add_team', $this->data);
		}
		else if ($url_segment == "add_team_content") {
			$this->data['gcategory'] = $this->General_Model->get_game_category()->result();

			if ($team_id != '') {
				$this->data['teams']      = $this->General_Model->get_team_data($team_id)->row();
			}

			$this->load->view('event/add_team_content', $this->data);
		}
		else if ($url_segment == "save_team") { 
			$teamId = $this->input->post('teamId');
			//Insert into table
			if ($teamId == '') {
				if ($this->input->post()) {
					$msg = '';
					$this->form_validation->set_rules('teamname', 'Team Name', 'required');
					$this->form_validation->set_rules('gamecategory', 'Game Category', 'required');
					if (!empty($_FILES['team_image']['name'])) {
						$this->form_validation->set_rules('team_image', 'Image file', 'callback_image_file_check');
					}
					if (!empty($_FILES['team_bg']['name'])) {
						$this->form_validation->set_rules('team_bg', 'Image file', 'callback_bg_image_file_check');
					}
					$insertData = array();
					$insertData['team_image']='';
					$insertData['team_bg']='';
				
					if ($this->form_validation->run() !== false) {
						if (!empty($_FILES['team_image']['name'])) {
							$config['upload_path'] = 'uploads/teams';
							$config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF|';
							//$config['max_size'] = '10000';
							$config['encrypt_name'] = TRUE;
							$this->load->library('upload', $config);
							$this->upload->initialize($config);
							if ($this->upload->do_upload('team_image')) {
								$outputData['team_image'] = $this->upload->data();
							//	$insertData_lang_team_image = $outputData['team_image']['file_name'];
								$insertData['team_image'] = $outputData['team_image']['file_name'];
							} else {
								$msg .= 'Failed to add team image';
							}
						}

						if (!empty($_FILES['team_bg']['name'])) {
							$config2['upload_path'] = 'uploads/background';
							$config2['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF|';
							$config2['max_size'] = '10000';
							$config2['encrypt_name'] = TRUE;
							$this->load->library('upload', $config2);
							$this->upload->initialize($config2);
							if ($this->upload->do_upload('team_bg')) {
								$outputData['team_bg'] = $this->upload->data();
							//	$insertData_lang_team_bg = $outputData['team_bg']['file_name'];
								$insertData['team_bg'] = $outputData['team_bg']['file_name'];
							} else {
								$msg .= 'Failed to add team background image';
							}
						}

						

						$insertData['team_name'] = trim($this->input->post('teamname'));
						$insertData['category'] = trim($this->input->post('gamecategory'));
						$insertData['team_color'] = trim($this->input->post('teamcolor'));
						$insertData['popular_team'] = trim($this->input->post('topteam'));
						$insertData['create_date'] = strtotime(date('Y-m-d H:i:s'));
						$insertData['status'] = $this->input->post('is_active') ? 1 : 0;
						$insertData['page_title'] = strip_tags($this->input->post('pagetitle'));
						$insertData['meta_description'] = $this->input->post('metadescription');
						$insertData['page_content'] = trim($this->input->post('page_content'));						
						$insertData['url_key'] = trim($this->input->post('url_key'));
						$insertData['team_url'] = str_replace(" ", "-", trim($this->input->post('teamname')));
						$team_id = $this->General_Model->insert_data('teams', $insertData);

						//Add to language table
						$lang = $this->General_Model->getAllItemTable('language','store_id',$this->session->userdata('storefront')->admin_id)->result();

						foreach ($lang as $key => $l_code) {
							$insertData_lang = array();
							$insertData_lang['team_id'] = $team_id;
							$insertData_lang['team_image'] = $insertData['team_image'] ;
							$insertData_lang['team_bg'] = $insertData['team_bg'] ;
							$insertData_lang['language'] = $l_code->language_code;
							$insertData_lang['team_name'] = trim($this->input->post('teamname'));
							$insertData_lang['team_color'] = $this->input->post('teamcolor');
							$insertData_lang['page_title'] = strip_tags($this->input->post('pagetitle'));

							$insertData_lang['meta_description'] = $this->input->post('metadescription');
							$insertData_lang['page_content'] = $insertData['page_content'];						

							$this->General_Model->insert_data('teams_lang', $insertData_lang);
						}

						$response = array('status' => 1, 'msg' => 'Team Created Successfully. ' . $msg, 'redirect_url' => base_url() . 'settings/teams');
						echo json_encode($response);
						exit;
					} else {
						$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'settings/teams/add_team', 'status' => 0);
						echo json_encode($response);
						exit;
					}
				}
			} else {

				//if its an update request
				if ($this->input->post()) {

					$updateData = array();
					$updateData_lang = array();

					if($_POST['flag'] != 'content'){

					$this->form_validation->set_rules('teamname', 'Team Name', 'required');
					$this->form_validation->set_rules('gamecategory', 'Game Category', 'required');

						if (!empty($_FILES['team_image']['name'])) {
							$this->form_validation->set_rules('team_image', 'Image file', 'callback_image_file_check');
						}
						if (!empty($_FILES['team_bg']['name'])) {
							$this->form_validation->set_rules('team_bg', 'Image file', 'callback_bg_image_file_check');
						}

					}

					if($_POST['flag'] == 'content'){
						$this->form_validation->set_rules('pagetitle', 'Team Title', 'required');
					}


					$msg = '';
					if ($this->form_validation->run() !== false) { 

						if($_POST['flag'] != 'content'){

						if (!empty($_FILES['team_image']['name'])) {
							$teamdata = $this->General_Model->getAllItemTable_array('teams', array('id' => $teamId))->row();
							if (@getimagesize(base_url() . './uploads/teams/' . $teamdata->team_image)) {
								unlink('./uploads/teams/' . $teamdata->team_image);
							}
							$config['upload_path'] = 'uploads/teams';
							$config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF|';
							//$config['max_size'] = '1000';
							$config['encrypt_name'] = TRUE;
							$this->load->library('upload', $config);
							$this->upload->initialize($config);
							if (!$this->upload->do_upload('team_image')) {
								$msg .= 'Failed to add team image';
							} else {
								$data = $this->upload->data();
								$imagename = $data['file_name'];
								$updateData_lang['team_image'] = $imagename;
								$updateData['team_image'] = $imagename;
							}
						} else {
							$updateData_lang['team_image'] = $this->input->post('exs_file');
						}



						if (!empty($_FILES['team_bg']['name'])) {
							$teamdata = $this->General_Model->getAllItemTable_array('teams', array('id' => $teamId))->row();
							if (@getimagesize(base_url() . './uploads/background/' . $teamdata->team_bg)) {
								unlink('./uploads/background/' . $teamdata->team_bg);
							}
							$config2['upload_path'] = 'uploads/background';
							$config2['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF|';
							$config2['max_size'] = '1000';
							$config2['encrypt_name'] = TRUE;
							$this->load->library('upload', $config2);
							$this->upload->initialize($config2);
							if (!$this->upload->do_upload('team_bg')) {
								$msg .= 'Failed to add team background image';
							} else {
								$data = $this->upload->data();
								$imagename = $data['file_name'];
								$updateData_lang['team_bg'] = $imagename;
								$updateData['team_bg'] = $imagename;
							}
						} else {
							$updateData_lang['team_bg'] = $this->input->post('exs_filebg');
						}

						} 	

						if($_POST['flag'] != 'content'){

						$updateData['team_name'] = trim($this->input->post('teamname'));
						$updateData['category'] = trim($this->input->post('gamecategory'));
						$updateData['team_color'] = trim($this->input->post('teamcolor'));
						$updateData['popular_team'] = trim($this->input->post('topteam'));
						$updateData['create_date'] = strtotime(date('Y-m-d H:i:s'));
						$updateData['status'] = $this->input->post('is_active') ? 1 : 0;
						

						if ($this->session->userdata('language_code') == 'en') {
							$updateData['team_url'] = str_replace(" ", "-", trim($this->input->post('teamname')));
						}
											
						}
						else{
						$updateData['seo_keywords'] = trim($this->input->post('seo_keywords'));
						$updateData['page_title'] = strip_tags($this->input->post('pagetitle'));
						$updateData['url_key'] = trim($this->input->post('url_key'));
						$updateData['meta_description'] = $this->input->post('metadescription');
						$updateData_lang['page_content'] = trim($this->input->post('page_content'));

						}

						//if($_POST['flag'] != 'content'){
						
						$this->General_Model->update('teams', array('id' => $teamId), $updateData);

						//}
						if($_POST['flag'] != 'content'){
						//Update language table         
						$updateData_lang['team_color'] = $this->input->post('teamcolor');
						$updateData_lang['team_name'] = trim($this->input->post('teamname'));
						}
						else{
						$updateData_lang['page_title'] = strip_tags($this->input->post('pagetitle'));
						$updateData_lang['meta_description'] = $this->input->post('metadescription');
						$updateData_lang['page_content'] = trim($this->input->post('page_content'));
						}
											
						$this->General_Model->update('teams_lang', array('team_id' => $teamId, 'language' => $this->session->userdata('language_code')), $updateData_lang);

						$response = array('status' => 1, 'msg' => 'Team data updated Successfully.' . $msg, 'redirect_url' => base_url() . 'settings/teams');
						echo json_encode($response);
						exit;
					}
				} else {
					$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'settings/teams/add_team', 'status' => 0);
					echo json_encode($response);
					exit;
				}
			}
		} else if ($url_segment == "delete_team") {
			$team_id   = $this->uri->segment(4);
			//$delete = $this->General_Model->delete_data('teams', 'id', $team_id);
			$updateData_data['status'] = 2;
			$delete = $this->General_Model->update('teams', array('id' => $team_id), $updateData_data);
			if ($delete == 1) {
				//$this->General_Model->delete_data('teams_lang', 'team_id', $team_id);
				$response = array('status' => 1, 'msg' => 'Team data deleted Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error While Deleting team data.');
				echo json_encode($response);
				exit;
			}
		} 
		else if ($url_segment == "delete_trash_team") {
			$team_id   = $this->uri->segment(4);
			$updateData_data['status'] = 1;
			$delete = $this->General_Model->update('teams', array('id' => $team_id), $updateData_data);
			if ($delete == 1) {
			//	$this->General_Model->delete_data('teams_lang', 'team_id', $team_id);
				$response = array('status' => 1, 'msg' => 'Team Moved from trash Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error While Undoing team data.');
				echo json_encode($response);
				exit;
			}
		} else {
			//$row_count = $this->uri->segment(3);
			$row_count = $this->uri->segment(4);
			$seg = $this->uri->segment(3);
			if ($this->input->post('submit') != NULL) {
				$search_text = $this->input->post('search');
				$this->session->set_userdata(array("searchteam" => $search_text));
			} else {
				if ($this->session->userdata('searchteam') != NULL) {
					$search_text = $this->session->userdata('searchteam');
				}
			} 
			$this->loadRecord_v1($row_count, 'teams', 'settings/teams/'.$seg, 'id', 'DESC', 'event/teams', 'teams', 'teams',$search_text);
		}
	}
	/**
	 * Fetch data and display based on the pagination request
	 */
	public function loadRecord_v1($rowno = 0, $table, $url, $order_column, $order_by, $view, $variable_name, $type, $search = '')
	{

		// Load Pagination library
		$this->load->library('pagination');

		// Row per page
		//$row_per_page = 10;
		$row_per_page = 10;

		// Row position
		if ($rowno != 0) {
			$rowno = ($rowno - 1) * $row_per_page;
		}
		// All records count
		$allcount = $this->General_Model->get_table_row_count($table, '');

		if ($type == 'teams') {
			$seg = $this->uri->segment(3);
			if($this->uri->segment(3) == ''){
				$seg = 'untrashed';
			} 
			$allcount = $this->General_Model->get_teams_by_limit('', '', '','', '',$search,$seg)->num_rows();
			$record = $this->General_Model->get_teams_by_limit($rowno, $row_per_page, $order_column, $order_by, '',$search,$seg)->result();
		} else if ($type == 'tournament') {
			$seg = $this->uri->segment(3);
			if($this->uri->segment(3) == ''){
				$seg = 'untrashed';
			}
			$allcount = $this->General_Model->get_tournament_by_limit('', '', '', '', '', $search,$seg)->num_rows();
			$record = $this->General_Model->get_tournament_by_limit($rowno, $row_per_page, $order_column, $order_by, '', $search,$seg)->result();

		} else {

			// Get records
			$record = $this->General_Model->get_limit_based_data($table, $rowno, $row_per_page, $order_column, $order_by, '')->result();
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
		$this->data['row'] = $rowno;
		$this->data['search'] = $search;
		// Load view
		$this->load->view($view, $this->data);
	}

	public function email_logs()
	{
			$type = $this->uri->segment(3);
			$row_count = $this->uri->segment(4);
			$this->loadRecord($row_count, 'email_logs', 'settings/email_logs/'.$type, 'id', 'DESC', 'settings/email_logs','email_logs', 'id',$type);
	}

	public function email_settings()
	{




		$segment = $this->uri->segment(3);
		if ($segment == 'edit_email_access') {
			$segment4 = json_decode(base64_decode($this->uri->segment(4)));
			if ($segment4 != "") {
				$edit_id = $segment4;
				$this->data['email_access'] = $this->General_Model->getAllItemTable('email_access', 'id', $edit_id, 'id', 'DESC')->row();
			}
			$this->load->view('settings/email_settings/edit_email_access', $this->data);
		} else if ($segment == 'email_list') {
			$row_count = $this->uri->segment(4);
			$this->loadRecord($row_count, 'email_access', 'settings/email_settings/email_list', 'id', 'DESC', 'settings/email_settings/email_access', 'email_access');
		} else if ($segment == 'save_email_settings') {

			$this->form_validation->set_rules('smtp', 'SMTP', 'required');
			$this->form_validation->set_rules('host', 'Host', 'required');
			$this->form_validation->set_rules('port', 'Port', 'required');
			$this->form_validation->set_rules('username', 'Username', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');
			if ($this->form_validation->run() !== false) {
				$insert_data = array(
					'smtp' => $_POST['smtp'],
					'host' => $_POST['host'],
					'port' => $_POST['port'],
					'username' => $_POST['username'],
					'status' => $_POST['status'],
					'password' => $_POST['password'],
				);
				if ($_POST['id'] == '') {
					$response = array('msg' => 'You Cant able to new email access settings.', 'redirect_url' => base_url() . 'settings/email_settings/email_list', 'status' => 0);
					echo json_encode($response);
					exit;
				} else {
					$access_id = $_POST['id'];
					if ($this->General_Model->update_table('email_access', 'id', $access_id, $insert_data)) {
						$response = array('msg' => 'Email Access Settings updated Successfully.', 'redirect_url' => base_url() . 'settings/email_settings/email_list', 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to update Email Access details.', 'redirect_url' => base_url() . 'settings/email_settings/email_list', 'status' => 0);
					}
					echo json_encode($response);
					exit;
				}
			} else {
				$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'settings/email_settings/email_list', 'status' => 0);
			}
			echo json_encode($response);
			exit;
		}
	}


	public function seller_settings()
	{

		//echo "<pre>";print_r($this->session->userdata('role'));

		$segment = $this->uri->segment(3);

		if ($segment == 'add_seller_settings') {

			$segment4 = $this->uri->segment(4);

			if ($segment4 != "") {

				$edit_id = $edit_id = json_decode(base64_decode($this->uri->segment(4)));
				$this->data['markup'] = $this->General_Model->getAllItemTable('tickets_markup', 'id', $edit_id, 'id', 'DESC')->row();
			}
			$this->data['role']    = 1;
			$this->data['sellers'] = $this->General_Model->get_admin_details_by_role($this->data['role'], 'ACTIVE');
			//echo "<pre>";print_r($this->data['sellers']);exit;
			$this->load->view('settings/seller_settings/add_seller_settings', $this->data);
		} else if ($segment == 'add_partner_settings') {

			$segment4 = $this->uri->segment(4);

			if ($segment4 != "") {

				$edit_id = $edit_id = json_decode(base64_decode($this->uri->segment(4)));
				$this->data['markup'] = $this->General_Model->getAllItemTable('tickets_markup', 'id', $edit_id, 'id', 'DESC')->row();
			}
			$this->data['role']    = 2;
			$this->data['sellers'] = $this->General_Model->get_admin_details_by_role($this->data['role'], 'ACTIVE');
			//echo "<pre>";print_r($this->data['sellers']);exit;
			$this->load->view('settings/seller_settings/add_seller_settings', $this->data);
		} else if ($segment == 'add_afliliate_settings') {

			$segment4 = $this->uri->segment(4);

			if ($segment4 != "") {

				$edit_id = $edit_id = json_decode(base64_decode($this->uri->segment(4)));
				$this->data['markup'] = $this->General_Model->getAllItemTable('tickets_markup', 'id', $edit_id, 'id', 'DESC')->row();
			}
			$this->data['role']    = 3;
			$this->data['sellers'] = $this->General_Model->get_admin_details_by_role($this->data['role'], 'ACTIVE');
			//echo "<pre>";print_r($this->data['sellers']);exit;
			$this->load->view('settings/seller_settings/add_seller_settings', $this->data);
		} else if ($segment == 'seller_settings_list') {
			$this->data['role']    = 1;
			$row_count = $this->uri->segment(4);
			$this->loadRecord($row_count, 'tickets_markup', 'settings/seller_settings/seller_settings_list', 'tickets_markup.id', 'DESC', 'settings/seller_settings/seller_settings_list', 'markups', 1);
		} else if ($segment == 'partner_settings_list') {
			$this->data['role']    = 2;
			$row_count = $this->uri->segment(4);
			$this->loadRecord($row_count, 'tickets_markup', 'settings/seller_settings/seller_settings_list', 'tickets_markup.id', 'DESC', 'settings/seller_settings/seller_settings_list', 'markups', 2);
		} else if ($segment == 'afliliate_settings_list') {
			$this->data['role']    = 3;
			$row_count = $this->uri->segment(4);
			$this->loadRecord($row_count, 'tickets_markup', 'settings/seller_settings/seller_settings_list', 'tickets_markup.id', 'DESC', 'settings/seller_settings/seller_settings_list', 'markups', 3);
		} else if ($segment == 'delete_seller_markup') {

			$segment4 = $this->uri->segment(4);
			$delete_id = $segment4;
			$delete = $this->General_Model->delete_data('tickets_markup', 'id', $delete_id);
			if ($delete == 1) {
				$response = array('status' => 1, 'msg' => 'Markup details deleted Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error while deleting Markup details.');
				echo json_encode($response);
				exit;
			}
		} else if ($segment == 'save_markup') {



			$this->form_validation->set_rules('user_id', 'Seller', 'required');
			$this->form_validation->set_rules('markup', 'Markup Value', 'required');

			if ($_POST['role'] == 1) {
				$user_type = 'seller';
			} else if ($_POST['role'] == 2) {
				$user_type = 'partner';
			} else if ($_POST['role'] == 3) {
				$user_type = 'afliliate';
			}
			$user_role = $this->session->userdata('role');

			if ($user_role == 6) {

				$markup_type = "TO_SELLER";
			} else {

				$markup_type = "TO_CUSTOMER";
			}



			if ($this->form_validation->run() !== false) {
				$insert_data = array(
					'user_id' => $_POST['user_id'],
					'user_role' => $_POST['role'],
					'markup' => $_POST['markup'],
					'markup_type' => $markup_type,
					'status' => $_POST['status'],
					'store_id' => $this->session->userdata('storefront')->admin_id,
					'add_by' => $this->session->userdata('admin_id')
				);

				if ($_POST['id'] == '') {

					$inserted_id = $this->General_Model->insert_data('tickets_markup', $insert_data);
					if ($inserted_id) {
						$response = array('msg' => 'New Markup Created successfully.', 'redirect_url' => base_url() . 'settings/seller_settings/' . $user_type . '_settings_list', 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to Create New Markup.', 'redirect_url' => base_url() . 'settings/seller_settings/' . $user_type . '_settings_list', 'status' => 0);
					}
					echo json_encode($response);
					exit;
				} else {
					$id = $_POST['id'];


					if ($this->General_Model->update_table('tickets_markup', 'id', $id, $insert_data)) {
						$response = array('msg' => 'Markup details updated Successfully.', 'redirect_url' => base_url() . 'settings/seller_settings/' . $user_type . '_settings_list', 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to update Markup details.', 'redirect_url' => base_url() . 'settings/_settings_list/' . $user_type . '_settings', 'status' => 0);
					}
					echo json_encode($response);
					exit;
				}
			} else {
				$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'settings/seller_settings/' . $user_type . '_settings_list', 'status' => 0);
			}
			echo json_encode($response);
			exit;
		}
	}

	


	public function discount_coupons()
	{

		$segment = $this->uri->segment(3);

		if ($segment == 'add_discount_coupon') {

			$segment4 = $this->uri->segment(4);

			if ($segment4 != "") {

				$edit_id = $edit_id = json_decode(base64_decode($this->uri->segment(4)));
				$this->data['coupons'] = $this->General_Model->getAllItemTable('coupon_code', 'c_id', $edit_id, 'c_id', 'DESC')->row();
			}


			$this->load->view('settings/coupons/add_coupon', $this->data);
		} else if ($segment == 'discount_coupon_list') {

			$row_count = $this->uri->segment(4);
			$this->loadRecord($row_count, 'coupon_code', 'settings/discount_coupons/discount_coupon_list', 'c_id', 'DESC', 'settings/coupons/coupon_list', 'coupons');
		} else if ($segment == 'delete_coupon') {

			$segment4 = $this->uri->segment(4);
			$delete_id = $segment4;
			$delete = $this->General_Model->delete_data('coupon_code', 'c_id', $delete_id);
			if ($delete == 1) {
				$response = array('status' => 1, 'msg' => 'Coupon details deleted Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error while deleting Coupon details.');
				echo json_encode($response);
				exit;
			}
		} else if ($segment == 'save_coupon') {

			$this->form_validation->set_rules('coupon_code', 'Coupon Code', 'required');
			$this->form_validation->set_rules('coupon_type', 'Coupon Type', 'required');
			$this->form_validation->set_rules('coupon_value', 'Coupon Value', 'required');
			$this->form_validation->set_rules('expiry_date', 'Expiry Date', 'required');

			if ($this->form_validation->run() !== false) {
				$insert_data = array(
					'coupon_code' => $_POST['coupon_code'],
					'coupon_type' => $_POST['coupon_type'],
					'coupon_value' => $_POST['coupon_value'],
					'status' => $_POST['status'],
					'expiry_date' => date('Y-m-d', strtotime($_POST['expiry_date'])),
					'create_date' => date('Y-m-d'),
				);
				if ($_POST['id'] == '') {

					$inserted_id = $this->General_Model->insert_data('coupon_code', $insert_data);
					if ($inserted_id) {
						$response = array('msg' => 'New Coupon Created successfully.', 'redirect_url' => base_url() . 'settings/discount_coupons/discount_coupon_list', 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to Create New Coupon.', 'redirect_url' => base_url() . 'settings/discount_coupons/discount_coupon_list', 'status' => 0);
					}
					echo json_encode($response);
					exit;
				} else {
					$id = $_POST['id'];


					if ($this->General_Model->update_table('coupon_code', 'c_id', $id, $insert_data)) {
						$response = array('msg' => 'Coupon details updated Successfully.', 'redirect_url' => base_url() . 'settings/discount_coupons/discount_coupon_list', 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to update Coupon details.', 'redirect_url' => base_url() . 'settings/discount_coupons/discount_coupon_list', 'status' => 0);
					}
					echo json_encode($response);
					exit;
				}
			} else {
				$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'settings/discount_coupons/discount_coupon_list', 'status' => 0);
			}
			echo json_encode($response);
			exit;
		}
	}

	public function customers()
	{



		$segment = $this->uri->segment(3);

		if ($segment == 'add_customer') {

			$segment4 = $this->uri->segment(4);

			if ($segment4 != "") {

				$edit_id = $edit_id = json_decode(base64_decode($this->uri->segment(4)));
				$this->data['customers'] = $this->General_Model->getAllItemTable('register', 'id', $edit_id, 'id', 'DESC')->row();
			}

			$this->data['countries'] = $this->General_Model->getAllItemTable('countries', '', '', 'id', 'DESC')->result();


			$this->load->view('settings/customers/add_customer', $this->data);
		} else if ($segment == 'customer_list') {

			$row_count = $this->uri->segment(4);
			$this->loadRecord($row_count, 'register', 'settings/customers/customer_list', 'id', 'DESC', 'settings/customers/customer_list', 'customers');
		} else if ($segment == 'delete_customer') {

			$segment4 = $this->uri->segment(4);
			$delete_id = $segment4;
			$delete = $this->General_Model->delete_data('register', 'id', $delete_id);
			if ($delete == 1) {
				$response = array('status' => 1, 'msg' => 'Customer details deleted Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error while deleting Customer details.');
				echo json_encode($response);
				exit;
			}
		} else if ($segment == 'save_customer') {

			$this->form_validation->set_rules('firstname', 'First Name', 'required');
			$this->form_validation->set_rules('lastname', 'Last Code', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required');
			$this->form_validation->set_rules('phonecode', 'Phone code', 'required');
			$this->form_validation->set_rules('phone', 'Phone', 'required');
			$this->form_validation->set_rules('address', 'Address', 'required');
			$this->form_validation->set_rules('postal_code', 'Postal Code', 'required');
			$this->form_validation->set_rules('country', 'Country', 'required');
			$this->form_validation->set_rules('city', 'City', 'required');
			if ($_POST['id'] == '') {
				$this->form_validation->set_rules('password', 'Password', 'required');
				$this->form_validation->set_rules('confirm_password', 'Confirm password', 'required|matches[password]');
			}
			if ($this->form_validation->run() !== false) {
				$insert_data = array(
					'first_name' => $_POST['firstname'],
					'last_name' => $_POST['lastname'],
					'email' => $_POST['email'],
					'address' => $_POST['address'],
					'city' => $_POST['city'],
					'dialing_code' => $_POST['phonecode'],
					'mobile' => $_POST['phone'],
					'state' => $_POST['state'],
					'code' => $_POST['postal_code'],
					'country' => $_POST['country'],
					'active' => 0,
					'status' =>   $_POST['status'],
					'user_type' => 'buyer',
					'created_date' => date('Y-m-d H:i:s'),
				);
				if ($_POST['id'] == '') {

					$insert_data['add_by'] = $this->session->userdata('admin_id');
					$insert_data['password']         = md5($_POST['password']);
					$insert_data['confirm_password'] = md5($_POST['password']);

					$insert_data['add_by'] = $this->session->userdata('admin_id');
					$inserted_id = $this->General_Model->insert_data('register', $insert_data);
					if ($inserted_id) {
						$response = array('msg' => 'New Customer Created successfully.', 'redirect_url' => base_url() . 'settings/customers/customer_list', 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to Create New Customer.', 'redirect_url' => base_url() . 'settings/customers/customer_list', 'status' => 0);
					}
					echo json_encode($response);
					exit;
				} else {
					$id = $_POST['id'];

					if ($_POST['password'] != '') {
						$this->form_validation->set_rules('password', 'password', 'required');
						$this->form_validation->set_rules('confirm_password', 'Confirm password', 'required|matches[password]');
					}
					if ($this->form_validation->run() !== false) {

						$insert_data['password']         = md5($_POST['password']);
						$insert_data['confirm_password'] = md5($_POST['password']);
					} else {
						$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'settings/customers/customer_list', 'status' => 0);
						echo json_encode($response);
						exit;
					}

					if ($this->General_Model->update_table('register', 'id', $id, $insert_data)) {
						$response = array('msg' => 'Customer details updated Successfully.', 'redirect_url' => base_url() . 'settings/customers/customer_list', 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to update Customer details.', 'redirect_url' => base_url() . 'settings/customers/customer_list', 'status' => 0);
					}
					echo json_encode($response);
					exit;
				}
			} else {
				$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'settings/customers/customer_list', 'status' => 0);
			}
			echo json_encode($response);
			exit;
		}
	}


	function api_settings()
	{

		$segment = $this->uri->segment(3);
		if ($segment == 'add_api_settings') {

			$settings = $this->General_Model->get_general_settings($this->session->userdata('storefront')->admin_id, '', 'SL')->result();
			if (isset($settings)) {
				$mysettings = array();
				foreach ($settings as $skey => $setting) {
					$mysettings[$setting->site_name] = $setting->site_value;
				}
			}
			$this->data['apis'] = $mysettings;

			$this->load->view('settings/api_settings/api_settings', $this->data);
		} else if ($segment == 'save_api_settings') {

			$store_id = $this->session->userdata('storefront')->admin_id;

			$this->form_validation->set_rules('FACEBOOK_ID', 'Facebook App Id', 'required');
			$this->form_validation->set_rules('FACEBOOK_KEY', 'Facebook App Secret', 'required');
			$this->form_validation->set_rules('GOOGLE_ID', 'Google Client ID', 'required');
			$this->form_validation->set_rules('GOOGLE_KEY', 'Google Client Secret', 'required');
			if ($this->form_validation->run() !== false) {
				$insert_data = array(
					'FACEBOOK_ID' => $_POST['FACEBOOK_ID'],
					'FACEBOOK_KEY' => $_POST['FACEBOOK_KEY'],
					'GOOGLE_ID' => $_POST['GOOGLE_ID'],
					'GOOGLE_KEY' => $_POST['GOOGLE_KEY'],
				);

				$datainset = array();
				foreach ($insert_data as $ikey => $idata) {
					$datainset[] = array('site_name' => $ikey, 'site_value' => $idata, 'store_id' => $store_id, 'site_code' => 'SL', 'add_by' => $this->session->userdata('admin_id'));
				}

				if ($this->General_Model->update_site_settings($datainset, 'SL', $store_id)) {
					$response = array('msg' => 'API Settings updated Successfully.', 'redirect_url' => base_url() . 'settings/api_settings/add_api_settings', 'status' => 1);
				} else {
					$response = array('msg' => 'Failed to update API Settings.', 'redirect_url' => base_url() . 'settings/api_settings/add_api_settings', 'status' => 0);
				}
				echo json_encode($response);
				exit;
			} else {
				$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'settings/api_settings/add_api_settings', 'status' => 0);
			}
			echo json_encode($response);
			exit;
		}
	}

	function store_settings()
	{
		$this->load->view('settings/store_settings/store_settings', $this->data);
	}

	function add_store_settings()
	{

		$segment = $this->uri->segment(3);
		if ($segment == 'add_store_settings') {
			$this->data['currency_details'] = $this->General_Model->getAllItemTable('currency_types', '', '', 'id', 'DESC')->result();
			$settings = $this->General_Model->get_general_settings($this->session->userdata('storefront')->admin_id, '', 'BA')->result();
			if (isset($settings)) {
				$mysettings = array();
				foreach ($settings as $skey => $setting) {
					$mysettings[$setting->site_name] = $setting->site_value;
				}
			}
			$this->data['settings'] = $mysettings;
			$this->load->view('settings/store_settings/add_store_settings', $this->data);
		} else if ($segment == 'save_store_settings') {

			//echo "<pre>";print_r($_POST);exit;

			$this->form_validation->set_rules('SITE_TITLE', 'Site Name', 'required');
			$this->form_validation->set_rules('SITE_CURRENCY', 'Site Currency', 'required');
			$this->form_validation->set_rules('SITE_DESCRIPTION', 'Store Description', 'required');
			$this->form_validation->set_rules('CONTACT_NAME', 'Contact Name', 'required');
			$this->form_validation->set_rules('CONTACT_EMAIL', 'Contact Email', 'required');
			$this->form_validation->set_rules('CONTACT_PHONE', 'Contact Phone', 'required');
			$this->form_validation->set_rules('STORE_MARKUP', 'Store Markup', 'required');

			if ($this->form_validation->run() !== false) {



				$admin_id  = $this->session->userdata('storefront')->admin_id;

				if (isset($_FILES["profile_filepond"]["name"]) && $_FILES["profile_filepond"]["name"] != '') {
					$logo_image = explode(".", $_FILES["profile_filepond"]["name"]);
					$newlogoname = date('YmdHis') . rand(1, 9999999) . '.' . end($logo_image);
					$tmpnamert = $_FILES['profile_filepond']['tmp_name'];
					move_uploaded_file($tmpnamert, 'uploads/site/' . $newlogoname);
					$logo = 'uploads/site/' . $newlogoname;
				} else {

					$site_data = $this->General_Model->get_general_settings($admin_id, 'SITE_LOGO')->row();
					$logo = $site_data->site_value;
				}
				//echo "ADMIN ID = ".$admin_id;exit;
				$insert_data = array(
					'SITE_LOGO'  => $logo,
					'SITE_TITLE' => $_POST['SITE_TITLE'],
					'SITE_DOMAIN' => $_POST['SITE_DOMAIN'],
					'SITE_CURRENCY' => $_POST['SITE_CURRENCY'],
					'SITE_DESCRIPTION' => $_POST['SITE_DESCRIPTION'],
					'CONTACT_NAME' => $_POST['CONTACT_NAME'],
					'CONTACT_EMAIL' => $_POST['CONTACT_EMAIL'],
					'CONTACT_PHONE' => $_POST['CONTACT_PHONE'],
					'CONTACT_COUNTRY' => $_POST['CONTACT_COUNTRY'],
					'CONTACT_STATE' => $_POST['CONTACT_STATE'],
					'CONTACT_CITY' => $_POST['CONTACT_CITY'],
					'CONTACT_ADDRESS' => $_POST['CONTACT_ADDRESS'],
					'STORE_MARKUP' => $_POST['STORE_MARKUP']
				);
				$datainset = array();
				foreach ($insert_data as $ikey => $idata) {
					$datainset[] = array('site_name' => $ikey, 'site_value' => $idata, 'store_id' => $admin_id, 'site_code' => 'BA', 'add_by' => $this->session->userdata('admin_id'));
				}
				
				if ($this->General_Model->update_site_settings($datainset, 'BA', $admin_id)) {
					$response = array('msg' => 'Site Settings updated Successfully.', 'redirect_url' => base_url() . 'settings/store_settings/add_store_settings', 'status' => 1);
				} else {
					$response = array('msg' => 'Failed to update Payment Gateway Settings.', 'redirect_url' => base_url() . 'settings/store_settings/add_store_settings', 'status' => 0);
				}
				echo json_encode($response);
				exit;
			} else {
				$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'settings/store_settings/add_store_settings', 'status' => 0);
			}
			echo json_encode($response);
			exit;
		}
	}


	function gateway_settings()
	{

		$segment = $this->uri->segment(3);
		if ($segment == 'add_gateway_settings') {

			$gateways = $this->General_Model->get_general_settings($this->session->userdata('storefront')->admin_id, '', 'GA')->result();
			if (isset($gateways)) {
				$mysettings = array();
				foreach ($gateways as $skey => $setting) {
					$mysettings[$setting->site_name] = $setting->site_value;
				}
			}
			$this->data['gateways'] = $mysettings;

			$this->load->view('settings/gateway_settings/gateway_settings', $this->data);
		} else if ($segment == 'save_gateway_settings') {

			$this->form_validation->set_rules('PAYPAL_ID', 'Paypal Id', 'required');
			$this->form_validation->set_rules('PAYPAL_KEY', 'Paypal Key', 'required');
			$this->form_validation->set_rules('NETWORK_ID', 'Network Id', 'required');
			$this->form_validation->set_rules('NETWORK_KEY', 'Network Key', 'required');
			if ($this->form_validation->run() !== false) {
				$admin_id  = $this->session->userdata('storefront')->admin_id;
				$insert_data = array(
					'PAYPAL_ID' => $_POST['PAYPAL_ID'],
					'PAYPAL_KEY' => $_POST['PAYPAL_KEY'],
					'NETWORK_ID' => $_POST['NETWORK_ID'],
					'NETWORK_KEY' => $_POST['NETWORK_KEY'],
				);
				$datainset = array();
				foreach ($insert_data as $ikey => $idata) {
					$datainset[] = array('site_name' => $ikey, 'site_value' => $idata, 'store_id' => $admin_id, 'site_code' => 'GA', 'add_by' => $this->session->userdata('admin_id'));
				}

				if ($this->General_Model->update_site_settings($datainset, 'BA', $admin_id)) {
					$response = array('msg' => 'Payment Gateway Settings updated Successfully.', 'redirect_url' => base_url() . 'settings/gateway_settings/add_gateway_settings', 'status' => 1);
				} else {
					$response = array('msg' => 'Failed to update Payment Gateway Settings.', 'redirect_url' => base_url() . 'settings/gateway_settings/add_gateway_settings', 'status' => 0);
				}
				echo json_encode($response);
				exit;
			} else {
				$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'settings/gateway_settings/add_gateway_settings', 'status' => 0);
			}
			echo json_encode($response);
			exit;
		}
	}


	function email_templates()
	{


		$segment = $this->uri->segment(3);
		if ($segment == 'add_email_template') {
			$this->data['email_types']    = $this->General_Model->getAllItemTable('email_types')->result();

			$segment4 = $edit_id = json_decode(base64_decode($this->uri->segment(4)));
			if ($segment4 != "") {
				$edit_id = $segment4;
				$this->data['emails'] = $this->General_Model->getAllItemTable('email', 'id', $edit_id, 'id', 'DESC')->row();
			}
			$this->load->view('settings/email_templates/add_email_templates', $this->data);
		} else if ($segment == 'list_email_templates') {
			$row_count = $this->uri->segment(4);
			$this->loadRecord($row_count, 'email', 'settings/email_templates/list_email_templates', 'id', 'DESC', 'settings/email_templates/list_email_templates', 'emails');
		} else if ($segment == 'delete_email_templates') {
			$segment4 = $this->uri->segment(4);
			$delete_id = $segment4;
			$delete = $this->General_Model->delete_data('email', 'id', $delete_id);
			if ($delete == 1) {
				$response = array('status' => 1, 'msg' => 'Email Template deleted Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error while deleting Email Template.');
				echo json_encode($response);
				exit;
			}
		} else if ($segment == 'save_email_templates') {
			$this->form_validation->set_rules('type', 'Template Type', 'required');
			$this->form_validation->set_rules('from_emailid', 'From Email Id', 'required');
			$this->form_validation->set_rules('to_emailid', 'Admin Email Id', 'required');
			$this->form_validation->set_rules('status', 'Status', 'required');
			$this->form_validation->set_rules('topic', 'Subject', 'required');
			$this->form_validation->set_rules('content', 'Template content', 'required');
			if ($this->form_validation->run() !== false) {
				$insert_data = array(
					'key' => $_POST['type'],
					'from_emailid' => $_POST['from_emailid'],
					'to_emailid' => $_POST['to_emailid'],
					'subject' => $_POST['topic'],
					'status' => $_POST['status'],
					'message' => $_POST['content'],
				);
				if ($_POST['id'] == '') {
					$insert_data['add_by'] = $this->session->userdata('admin_id');
					$inserted_id = $this->General_Model->insert_data('email', $insert_data);
					if ($inserted_id) {
						$response = array('msg' => 'Email template added successfully.', 'redirect_url' => base_url() . 'settings/email_templates/list_email_templates', 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to add new Email template.', 'redirect_url' => base_url() . 'settings/email_templates/add_email_template', 'status' => 0);
					}
					echo json_encode($response);
					exit;
				} else {
					$template_id = $_POST['id'];
					if ($this->General_Model->update_table('email', 'id', $template_id, $insert_data)) {
						$response = array('msg' => 'Email template updated Successfully.', 'redirect_url' => base_url() . 'settings/email_templates/list_email_templates', 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to update game category details.', 'redirect_url' => base_url() . 'settings/email_templates/add_email_template' . $template_id, 'status' => 0);
					}
					echo json_encode($response);
					exit;
				}
			} else {
				$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'settings/email_templates/add_email_template', 'status' => 0);
			}
			echo json_encode($response);
			exit;
		}
	}
	public function banner_image_file_check()
	{
		$allowed_mime_types = array('image/jpeg', 'image/svg+xml', 'image/png', 'image/gif');
		if (isset($_FILES['banner_image']['name']) && $_FILES['banner_image']['name'] != "") {
			$mime = get_mime_by_extension($_FILES['banner_image']['name']);
			$fileAr = explode('.', $_FILES['banner_image']['name']);
			$ext = end($fileAr);
			if (in_array($mime, $allowed_mime_types)) {
				return true;
			} else {
				$this->form_validation->set_message('file_check', 'Please select only image file to upload.');
				return false;
			}
		} else {
			$this->form_validation->set_message('file_check', 'Please select a image file to upload.');
			return false;
		}
	}
	public function timage_file_check()
	{
		$allowed_mime_types = array('image/jpeg', 'image/svg+xml', 'image/png', 'image/gif');
		if (isset($_FILES['tournament_image']['name']) && $_FILES['tournament_image']['name'] != "") {
			$mime = get_mime_by_extension($_FILES['tournament_image']['name']);
			$fileAr = explode('.', $_FILES['tournament_image']['name']);
			$ext = end($fileAr);
			if (in_array($mime, $allowed_mime_types)) {
				return true;
			} else {
				$this->form_validation->set_message('file_check', 'Please select only image file to upload.');
				return false;
			}
		} else {
			$this->form_validation->set_message('file_check', 'Please select a image file to upload.');
			return false;
		}
	}
	public function bg_image_file_check()
	{
		$allowed_mime_types = array('image/jpeg', 'image/svg+xml', 'image/png', 'image/gif');
		if (isset($_FILES['team_bg']['name']) && $_FILES['team_bg']['name'] != "") {
			$mime = get_mime_by_extension($_FILES['team_bg']['name']);
			$fileAr = explode('.', $_FILES['team_bg']['name']);
			$ext = end($fileAr);
			if (in_array($mime, $allowed_mime_types)) {
				return true;
			} else {
				$this->form_validation->set_message('file_check', 'Please select only image file to upload.');
				return false;
			}
		} else {
			$this->form_validation->set_message('file_check', 'Please select a image file to upload.');
			return false;
		}
	}
	public function image_file_check()
	{
		$allowed_mime_types = array('image/jpeg', 'image/svg+xml', 'image/png', 'image/gif');
		if (isset($_FILES['team_image']['name']) && $_FILES['team_image']['name'] != "") {
			$mime = get_mime_by_extension($_FILES['team_image']['name']);
			$fileAr = explode('.', $_FILES['team_image']['name']);
			$ext = end($fileAr);
			if (in_array($mime, $allowed_mime_types)) {
				return true;
			} else {
				$this->form_validation->set_message('file_check', 'Please select only image file to upload.');
				return false;
			}
		} else {
			$this->form_validation->set_message('file_check', 'Please select a image file to upload.');
			return false;
		}
	}

	/**
	 * Fetch data and display based on the pagination request
	 */
	public function loadRecord($rowno = 0, $table, $url, $order_column, $order_by, $view, $variable_name, $role = '', $search = '')
	{

		// Load Pagination library
		$this->load->library('pagination');

		// Row per page
		$row_per_page = 10;

		// Row position
		if ($rowno == '') {
			$rowno = 0;
		}
		if ($rowno != 0) {
			$rowno = ($rowno - 1) * $row_per_page;
		}

		if ($table != "tickets_markup" && $table != "matches" && $table != 'states' && $table != 'cities' && $table != 'ticket_types' && $table != 'split_types' && $table != 'ticket_details' && $table != 'email' && $table != 'static_page' && $table != 'top_league_cups' && $table != 'upcoming_event' && $table !='banners' && $table !='email_logs') {

			// All records count
			$allcount = $this->General_Model->get_table_row_count($table, '');

			// Get records
			$record = $this->General_Model->get_limit_based_data($table, $rowno, $row_per_page, $order_column, $order_by)->result();
		} else if ($table != "tickets_markup" && $table == "matches") {

			$url = "settings/match_settings/match_settings";
			// All records count
			$allcount = $this->General_Model->get_matches('', 'upcoming')->num_rows();

			// Get records
			$record = $this->General_Model->get_matches('', 'upcoming', $rowno, $row_per_page, $order_column, $order_by)->result();
		} else if ($table == "states") {
			$allcount = $this->General_Model->get_table_row_count($table, '');
			$record = $this->General_Model->get_states_by_limit($rowno, $row_per_page, $order_column, $order_by, '')->result();
		} else if ($table == "cities") {
			$allcount = $this->General_Model->get_city_by_limit('', '', '', '', '', $search)->num_rows();
			$record = $this->General_Model->get_city_by_limit($rowno, $row_per_page, $order_column, $order_by, '', $search)->result();
		} else if ($table == "ticket_types") {
			$allcount = $this->General_Model->get_ticket_type_by_limit('', '', '', '', '', $search)->num_rows();
			$record = $this->General_Model->get_ticket_type_by_limit($rowno, $row_per_page, $order_column, $order_by, '', $search)->result();
		} else if ($table == "split_types") {
			$allcount = $this->General_Model->get_split_type_by_limit('', '', '', '', '', $search)->num_rows();
			$record = $this->General_Model->get_split_type_by_limit($rowno, $row_per_page, $order_column, $order_by, '', $search)->result();
		} else if ($table == "ticket_details") {
			$allcount = $this->General_Model->get_ticket_details_by_limit('', '', '', '', '', $search)->num_rows();
			$record = $this->General_Model->get_ticket_details_by_limit($rowno, $row_per_page, $order_column, $order_by, '', $search)->result();
		} else if ($table == "email") {
			$allcount = $this->General_Model->get_email_template_by_limit('', '', '', '', '', $search)->num_rows();
			$record = $this->General_Model->get_email_template_by_limit($rowno, $row_per_page, $order_column, $order_by, '', $search)->result();
		} else if ($table == "static_page") {
			$allcount = $this->General_Model->get_static_page_by_limit('', '', '', '', '', $search)->num_rows();
			$record = $this->General_Model->get_static_page_by_limit($rowno, $row_per_page, $order_column, $order_by, '', $search)->result();
		} 
		else if ($table == "banners") {
			$allcount = $this->General_Model->get_banners_by_limit('', '', '', '', '', $search)->num_rows();
			$record = $this->General_Model->get_banners_by_limit($rowno, $row_per_page, $order_column, $order_by, '', $search)->result();
		} 
		else if ($table == "email_logs") { 
			$allcount = $this->General_Model->email_logs('', '', '', '', '', $search)->num_rows();
			$record = $this->General_Model->email_logs($rowno, $row_per_page, $order_column, $order_by, '', $search)->result();
		}
		else if ($table == "top_league_cups") {
			if ($role == "topleagues") {
				// All records count
				$allcount = $this->General_Model->top_leagues_by_limit('', '', '', '', array('match_type=' => 'league'), $search)->num_rows();
				// Get records
				$record = $this->General_Model->top_leagues_by_limit($rowno, $row_per_page, $order_column, $order_by, array('match_type=' => 'league'), $search)->result();
			} else {
				// All records count
				$allcount = $this->General_Model->top_leagues_by_limit('', '', '', '', array('match_type=' => 'cups'), $search)->num_rows();
				// Get records
				$record = $this->General_Model->top_leagues_by_limit($rowno, $row_per_page, $order_column, $order_by, array('match_type=' => 'cups'), $search)->result();
			}
		} else if ($table == "upcoming_event") {
			$allcount = $this->General_Model->get_upcoming_event_by_limit('', '', '', '', '', $search)->num_rows();
			$record = $this->General_Model->get_upcoming_event_by_limit($rowno, $row_per_page, $order_column, $order_by, '', $search)->result();
		} else {

			// All records count
			$allcount = $this->General_Model->get_table_row_count_markup($role)->num_rows();
			// Get records
			$record = $this->General_Model->get_limit_based_data_markup($table, $rowno, $row_per_page, $order_column, $order_by, $role)->result();
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
		$this->data['row'] = $rowno;
		$this->data['search'] = $search;

		// Load view
		$this->load->view($view, $this->data);
	}

	/**
	 * Add,edit,update,delete and list countries position
	 */

	public function countries()
	{

		$segment = $this->uri->segment(3);
		if ($segment == 'add') {
			$this->load->view('settings/add_country', $this->data);
		} else if ($segment == 'edit') {
			$segment4 = $this->uri->segment(4);
			if ($segment4 != "") {
				$edit_id = $segment4;
				$this->data['country_details'] = $this->General_Model->getAllItemTable('countries', 'id', $edit_id, '', '')->row();
			}
			$this->load->view('settings/add_country', $this->data);
		} else if ($segment == 'delete') {
			$segment4 = $this->uri->segment(4);
			$delete_id = $segment4;
			$delete = $this->General_Model->delete_data('countries', 'id', $delete_id);
			if ($delete == 1) {
				$response = array('status' => 1, 'msg' => 'Country deleted Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error while deleting country.');
				echo json_encode($response);
				exit;
			}
		} else if ($segment == 'save') {
			if ($this->input->post()) {
				$this->form_validation->set_rules('cname', 'Country Name', 'required');
				$this->form_validation->set_rules('sname', 'Sort Name', 'required');
				$this->form_validation->set_rules('pcode', 'Phone Code', 'required');

				if ($this->form_validation->run() !== false) {
					$editId = $this->input->post('country_id');
					if ($editId == '') {
						$insert_data = array(
							'name' => $this->input->post('cname'),
							'phonecode' => $this->input->post('pcode'),
							'sortname' => $this->input->post('sname'),
							'add_by' => $this->session->userdata('admin_id'),
							'create_date' => strtotime(date('Y-m-d H:i:s'))
						);
						$inserted_id = $this->General_Model->insert_data('countries', $insert_data);
						if ($inserted_id) {
							$response = array('msg' => 'New country added Successfully.', 'redirect_url' => base_url() . 'settings/countries', 'status' => 1);
						} else {
							$response = array('msg' => 'Failed to add new country.', 'redirect_url' => base_url() . 'settings/countries/add', 'status' => 0);
						}
						echo json_encode($response);
						exit;
					} else {
						$updateData = array();
						$updateData['name'] = trim($this->input->post('cname'));
						$updateData['phonecode'] = trim($this->input->post('pcode'));
						$updateData['sortname'] = $this->input->post('sname');
						$this->General_Model->update('countries', array('id' => $editId), $updateData);

						$response = array('status' => 1, 'msg' => 'Country data updated Successfully.', 'redirect_url' => base_url() . 'settings/countries');
						echo json_encode($response);
						exit;
					}
				} else {
					$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'settings/countries/add', 'status' => 0);
				}
				echo json_encode($response);
				exit;
			}
		} else {
			$row_count = $this->uri->segment(3);
			$this->loadRecord($row_count, 'countries', 'settings/countries', 'id', 'DESC', 'settings/countries', 'countries', 'countries');
		}
	}


	/**
	 * Add,edit,update,delete and list states
	 */

	public function states()
	{

		$segment = $this->uri->segment(3);
		if ($segment == 'add') {
			$this->data['countries'] = $this->General_Model->getAllItemTable('countries')->result();
			$this->load->view('settings/add_state', $this->data);
		} else if ($segment == 'edit') {
			$this->data['countries'] = $this->General_Model->getAllItemTable('countries')->result();
			$segment4 = $this->uri->segment(4);
			if ($segment4 != "") {
				$edit_id = $segment4;
				$this->data['state_details'] = $this->General_Model->get_state_data($edit_id)->row();
			}
			$this->load->view('settings/add_state', $this->data);
		} else if ($segment == 'delete') {
			$segment4 = $this->uri->segment(4);
			$delete_id = $segment4;
			$delete = $this->General_Model->delete_data('states', 'id', $delete_id);
			if ($delete == 1) {
				$response = array('status' => 1, 'msg' => 'State deleted Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error while deleting state.');
				echo json_encode($response);
				exit;
			}
		} else if ($segment == 'save') {
			if ($this->input->post()) {
				$this->form_validation->set_rules('cname', 'Country', 'required');
				$this->form_validation->set_rules('sname', 'State Name', 'required');

				if ($this->form_validation->run() !== false) {
					$editId = $this->input->post('state_id');
					if ($editId == '') {
						$insert_data = array(
							'name' => $this->input->post('sname'),
							'country_id' => $this->input->post('cname'),
							'add_by' => $this->session->userdata('admin_id'),
							'create_date' => strtotime(date('Y-m-d H:i:s'))
						);
						$inserted_id = $this->General_Model->insert_data('states', $insert_data);
						if ($inserted_id) {
							$response = array('msg' => 'New state added Successfully.', 'redirect_url' => base_url() . 'settings/states', 'status' => 1);
						} else {
							$response = array('msg' => 'Failed to add new state.', 'redirect_url' => base_url() . 'settings/states/add', 'status' => 0);
						}
						echo json_encode($response);
						exit;
					} else {
						$updateData = array();
						$updateData['name'] = trim($this->input->post('sname'));
						$updateData['country_id'] = trim($this->input->post('cname'));
						$this->General_Model->update('states', array('id' => $editId), $updateData);

						$response = array('status' => 1, 'msg' => 'State data updated Successfully.', 'redirect_url' => base_url() . 'settings/states');
						echo json_encode($response);
						exit;
					}
				} else {
					$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'settings/states/add', 'status' => 0);
				}
				echo json_encode($response);
				exit;
			}
		} else {
			$row_count = $this->uri->segment(3);
			$this->loadRecord($row_count, 'states', 'settings/states', 'id', 'DESC', 'settings/states', 'states', 'states');
		}
	}


	/**
	 * Add,edit,update,delete and list cities
	 */

	public function cities()
	{

		$segment = $this->uri->segment(3);
		if ($segment == 'add') {
			$this->data['countries'] = $this->General_Model->getAllItemTable('countries')->result();
			$this->load->view('settings/city/add_city', $this->data);
		} else if ($segment == 'edit') {
			$this->data['countries'] = $this->General_Model->getAllItemTable('countries')->result();
			$segment4 = $this->uri->segment(4);
			if ($segment4 != "") {
				$edit_id = $segment4;
				$this->data['city_details'] = $this->General_Model->get_city_data($edit_id)->row();
				$stateId = $this->data['city_details']->state_id;
				$stateData = $this->General_Model->get_state_data($stateId)->row();
				$countryId = $stateData->country_id;
				$this->data['selected_country'] = $countryId;
				$this->data['states'] = $this->General_Model->get_states($countryId)->result();
			}
			$this->load->view('settings/city/add_city', $this->data);
		} else if ($segment == 'delete') {
			$segment4 = $this->uri->segment(4);
			$delete_id = $segment4;
			$delete = $this->General_Model->delete_data('cities', 'id', $delete_id);
			if ($delete == 1) {
				$response = array('status' => 1, 'msg' => 'City deleted Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error while deleting city.');
				echo json_encode($response);
				exit;
			}
		} else if ($segment == 'save') {
			if ($this->input->post()) {
				$this->form_validation->set_rules('cname', 'Country', 'required');
				$this->form_validation->set_rules('sname', 'State Name', 'required');
				$this->form_validation->set_rules('cityname', 'City Name', 'required');

				if ($this->form_validation->run() !== false) {
					$editId = $this->input->post('city_id');
					if ($editId == '') {
						$insert_data = array(
							'name' => $this->input->post('cityname'),
							'state_id' => $this->input->post('sname'),
							'add_by' => $this->session->userdata('admin_id'),
							'create_date' => strtotime(date('Y-m-d H:i:s'))
						);
						$inserted_id = $this->General_Model->insert_data('cities', $insert_data);
						if ($inserted_id) {
							$response = array('msg' => 'New city added Successfully.', 'redirect_url' => base_url() . 'settings/cities', 'status' => 1);
						} else {
							$response = array('msg' => 'Failed to add new city.', 'redirect_url' => base_url() . 'settings/states/cities', 'status' => 0);
						}
						echo json_encode($response);
						exit;
					} else {
						$updateData = array();
						$updateData['name'] = trim($this->input->post('cityname'));
						$updateData['state_id'] = trim($this->input->post('sname'));
						$this->General_Model->update('cities', array('id' => $editId), $updateData);

						$response = array('status' => 1, 'msg' => 'City data updated Successfully.', 'redirect_url' => base_url() . 'settings/cities');
						echo json_encode($response);
						exit;
					}
				} else {
					$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'settings/cities/add', 'status' => 0);
				}
				echo json_encode($response);
				exit;
			}
		} else {
			if ($this->input->post('submit') != NULL) {
				$search_text = $this->input->post('search');
				$this->session->set_userdata(array("searchcity" => $search_text));
			} else {
				if ($this->session->userdata('searchcity') != NULL) {
					$search_text = $this->session->userdata('searchcity');
				}
			}
			$row_count = $this->uri->segment(3);
			$this->loadRecord($row_count, 'cities', 'settings/cities', 'id', 'DESC', 'settings/city/cities', 'cities', 'cities', $search_text);
		}
	}

	public function getStates()
	{
		$country_id = $this->input->post('country_id');
		if (!empty($country_id)) {
			$states = $this->db->select('id,name')->get_where('states', array('country_id' => $country_id))->result();
		} else {
			$states = $this->db->select('id,name')->get_where('states')->result();
		}

		echo json_encode($states);
		exit;
	}


	/**
	 * Add,edit,update,delete and list ticket types
	 */

	public function ticket_types()
	{

		$segment = $this->uri->segment(3);
		if ($segment == 'add') {
			$this->load->view('settings/ticket_types/add_ticket_type', $this->data);
		} else if ($segment == 'edit') {
			$segment4 = $this->uri->segment(4);
			if ($segment4 != "") {
				$edit_id = $segment4;
				$this->data['ticket_details'] = $this->General_Model->get_ticket_type_data($edit_id)->row();
			}
			$this->load->view('settings/ticket_types/add_ticket_type', $this->data);
		} else if ($segment == 'delete') {
			$segment4 = $this->uri->segment(4);
			$delete_id = $segment4;
			$delete = $this->General_Model->delete_data('ticket_types', 'id', $delete_id);
			if ($delete == 1) {
				$this->General_Model->delete_data('ticket_types_lang', 'ticket_type_id', $delete_id);
				$response = array('status' => 1, 'msg' => 'Ticket type deleted Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error while deleting ticket type.');
				echo json_encode($response);
				exit;
			}
		} else if ($segment == 'save') {
			if ($this->input->post()) {
				$this->form_validation->set_rules('name', 'Ticket Type', 'required');
				if ($this->form_validation->run() !== false) {
					$editId = $this->input->post('ticket_type_id');
					if ($editId == '') {
						$status = $this->input->post('status') ? 1 : 0;
						if (!empty($_FILES['ticket_image']['name'])) {
							$config['upload_path'] = 'uploads/ticket_image';
							$config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF|';
							//$config['max_size'] = '10000';
							$config['encrypt_name'] = TRUE;
							$this->load->library('upload', $config);
							if ($this->upload->do_upload('ticket_image')) {
								$outputData['ticket_image'] = $this->upload->data();
								$ticket_image = $outputData['ticket_image']['file_name'];
							}
						}
						$ticket_image = isset($ticket_image)?$ticket_image:"";
						$insert_data = array(
							'name' => $this->input->post('name'),
							'status' =>  $status,
							'add_by' => $this->session->userdata('admin_id'),
							'ticket_description' => $this->input->post('t_description'),
							'ticket_image' => $ticket_image,
							'create_date' => strtotime(date('Y-m-d H:i:s'))
						);
						$inserted_id = $this->General_Model->insert_data('ticket_types', $insert_data);
						if ($inserted_id) {
							$lang = $this->General_Model->getAllItemTable('language','store_id',$this->session->userdata('storefront')->admin_id)->result();
							foreach ($lang as $key => $l_code) {
								$language_data = array(
									'language' =>  $l_code->language_code,
									'ticket_type_id' => $inserted_id,
									'ticket_description' => $this->input->post('t_description'),
									'ticket_image' => $ticket_image,
									'name' => $this->input->post('name')
								);
								$this->General_Model->insert_data('ticket_types_lang', $language_data);
							}
							$response = array('msg' => 'New ticket type added successfully.', 'redirect_url' => base_url() . 'settings/ticket_types', 'status' => 1);
						} else {
							$response = array('msg' => 'Failed to add new ticket type.', 'redirect_url' => base_url() . 'settings/ticket_types/add', 'status' => 0);
						}
						echo json_encode($response);
						exit;
					} else {
						$updateData = array();
						$updateData['name'] = trim($this->input->post('name'));
						$updateData['status'] = $this->input->post('status') ? 1 : 0;
						$updateData['ticket_description'] = $this->input->post('t_description');
						if (!empty($_FILES['ticket_image']['name'])) {
							$ticketdata = $this->General_Model->get_ticket_type_data($edit_id)->row();
							if (@getimagesize(base_url() . 'uploads/ticket_image/' . $ticketdata->ticket_image)) {
								unlink('uploads/ticket_image/' . $ticketdata->ticket_image);
							}
							$config['upload_path'] = 'uploads/ticket_image';
							$config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF|webp|';
							//$config['max_size'] = '10000';
							$config['encrypt_name'] = TRUE;
							$this->load->library('upload', $config);
							if ($this->upload->do_upload('ticket_image')) {
								$outputData['ticket_image'] = $this->upload->data();
								$updateData['ticket_image'] = $outputData['ticket_image']['file_name'];
								$updateData_lang['ticket_image'] = $outputData['ticket_image']['file_name'];
							}
						}
						$this->General_Model->update('ticket_types', array('id' => $editId), $updateData);
						
						//Update language table			
						$updateData_lang['name'] = trim($this->input->post('name'));
						$updateData_lang['ticket_description'] = trim($this->input->post('t_description'));
						$this->General_Model->update('ticket_types_lang', array('ticket_type_id' => $editId, 'language' => $this->session->userdata('language_code')), $updateData_lang);

						$response = array('status' => 1, 'msg' => 'Ticket type data updated Successfully.', 'redirect_url' => base_url() . 'settings/ticket_types');
						echo json_encode($response);
						exit;
					}
				} else {
					$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'settings/ticket_types/add', 'status' => 0);
				}
				echo json_encode($response);
				exit;
			}
		} else {
			if ($this->input->post('submit') != NULL) {
				$search_text = $this->input->post('search');
				$this->session->set_userdata(array("searchttype" => $search_text));
			} else {
				if ($this->session->userdata('searchttype') != NULL) {
					$search_text = $this->session->userdata('searchttype');
				}
			}
			$row_count = $this->uri->segment(4);
			$this->loadRecord($row_count, 'ticket_types', 'settings/ticket_types/ticket_types', 'id', 'DESC', 'settings/ticket_types/ticket_types', 'ticket_types', 'ticket_types', $search_text);
		}
	}


	/**
	 * Add,edit,update,delete and list split types
	 */

	public function split_types()
	{

		$segment = $this->uri->segment(3);
		if ($segment == 'add') {
			$this->load->view('settings/split_types/add_split_type', $this->data);
		} else if ($segment == 'edit') {
			$segment4 = $this->uri->segment(4);
			if ($segment4 != "") {
				$edit_id = $segment4;
				$this->data['split_details'] = $this->General_Model->get_split_type_data($edit_id)->row();
			}
			$this->load->view('settings/split_types/add_split_type', $this->data);
		} else if ($segment == 'delete') {
			$segment4 = $this->uri->segment(4);
			$delete_id = $segment4;
			$delete = $this->General_Model->delete_data('split_types', 'id', $delete_id);
			if ($delete == 1) {
				$this->General_Model->delete_data('split_types_lang', 'split_type_id', $delete_id);
				$response = array('status' => 1, 'msg' => 'Split type deleted Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error while deleting split type.');
				echo json_encode($response);
				exit;
			}
		} else if ($segment == 'save') {
			if ($this->input->post()) {
				$this->form_validation->set_rules('name', 'Split Type', 'required');
				if ($this->form_validation->run() !== false) {
					$editId = $this->input->post('split_type_id');
					if ($editId == '') {
						$status = $this->input->post('status') ? 1 : 0;
						if (!empty($_FILES['spilit_image']['name'])) {
							$config['upload_path'] = 'uploads/spilit_image';
							$config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF|';
							//$config['max_size'] = '10000';
							$config['encrypt_name'] = TRUE;
							$this->load->library('upload', $config);
							if ($this->upload->do_upload('spilit_image')) {
								$outputData['spilit_image'] = $this->upload->data();
								$spilit_image = $outputData['spilit_image']['file_name'];
							}
						}
						$spilit_image = isset($spilit_image)?$spilit_image:"";
						$insert_data = array(
							'name' => $this->input->post('name'),
							'status' =>  $status,
							'add_by' => $this->session->userdata('admin_id'),
							'split_description' => $this->input->post('s_description'),
							'spilit_image' => $spilit_image,
							'create_date' => strtotime(date('Y-m-d H:i:s'))
						);
						$inserted_id = $this->General_Model->insert_data('split_types', $insert_data);
						if ($inserted_id) {
							$lang = $this->General_Model->getAllItemTable('language','store_id',$this->session->userdata('storefront')->admin_id)->result();
							foreach ($lang as $key => $l_code) {
								$language_data = array(
									'language' =>  $l_code->language_code,
									'split_type_id' => $inserted_id,
									'split_description' => $this->input->post('s_description'),
									'spilit_image' => $spilit_image,
									'name' => $this->input->post('name')
								);
								$this->General_Model->insert_data('split_types_lang', $language_data);
							}
							$response = array('msg' => 'New split type added successfully.', 'redirect_url' => base_url() . 'settings/split_types', 'status' => 1);
						} else {
							$response = array('msg' => 'Failed to add new split type.', 'redirect_url' => base_url() . 'settings/split_types/add', 'status' => 0);
						}
						echo json_encode($response);
						exit;
					} else {
						$updateData = array();
						$updateData['name'] = trim($this->input->post('name'));
						$updateData['status'] = $this->input->post('status') ? 1 : 0;
						if (!empty($_FILES['spilit_image']['name'])) {
							$spilitdata = $this->General_Model->get_split_type_data($editId)->row();
							if (@getimagesize(base_url() . 'uploads/spilit_image/' . $spilitdata->spilit_image)) {
								unlink('uploads/spilit_image/' . $spilitdata->spilit_image);
							}
							$config['upload_path'] = 'uploads/spilit_image';
							$config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF|';
							//$config['max_size'] = '10000';
							$config['encrypt_name'] = TRUE;
							$this->load->library('upload', $config);
							if ($this->upload->do_upload('spilit_image')) {
								$outputData['spilit_image'] = $this->upload->data();
								$updateData['spilit_image'] = $outputData['spilit_image']['file_name'];
								$updateData_lang['spilit_image'] = $outputData['spilit_image']['file_name'];
							}
						}
						$this->General_Model->update('split_types', array('id' => $editId), $updateData);

						//Update language table			
						$updateData_lang['name'] = trim($this->input->post('name'));
						$updateData_lang['split_description'] = $this->input->post('s_description');
						$this->General_Model->update('split_types_lang', array('split_type_id' => $editId, 'language' => $this->session->userdata('language_code')), $updateData_lang);

						$response = array('status' => 1, 'msg' => 'Split type data updated Successfully.', 'redirect_url' => base_url() . 'settings/split_types');
						echo json_encode($response);
						exit;
					}
				} else {
					$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'settings/split_types/add', 'status' => 0);
				}
				echo json_encode($response);
				exit;
			}
		} else {
			if ($this->input->post('submit') != NULL) {
				$search_text = $this->input->post('search');
				$this->session->set_userdata(array("searchsptype" => $search_text));
			} else {
				if ($this->session->userdata('searchsptype') != NULL) {
					$search_text = $this->session->userdata('searchsptype');
				}
			}
			$row_count = $this->uri->segment(4);
			$this->loadRecord($row_count, 'split_types', 'settings/split_types/split_types', 'id', 'DESC', 'settings/split_types/split_types', 'split_types', 'split_types', $search_text);
		}
	}


	/**
	 * Add,edit,update,delete and list split types
	 */

	public function ticket_details()
	{

		$segment = $this->uri->segment(3);
		if ($segment == 'add') {
			$this->load->view('settings/ticket_details/add_ticket_details', $this->data);
		} else if ($segment == 'edit') {
			$segment4 = $this->uri->segment(4);
			if ($segment4 != "") {
				$edit_id = $segment4;
				$this->data['ticket_details'] = $this->General_Model->get_ticket_details_data($edit_id)->row();
			}
			$this->load->view('settings/ticket_details/add_ticket_details', $this->data);
		} else if ($segment == 'delete') {
			$segment4 = $this->uri->segment(4);
			$delete_id = $segment4;
			$delete = $this->General_Model->delete_data('ticket_details', 'id', $delete_id);
			if ($delete == 1) {
				$this->General_Model->delete_data('ticket_details_lang', 'ticket_details_id', $delete_id);
				$response = array('status' => 1, 'msg' => 'Ticket details deleted Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error while deleting ticket details.');
				echo json_encode($response);
				exit;
			}
		} else if ($segment == 'save') { 
			if ($this->input->post()) {
				$this->form_validation->set_rules('name', 'Ticket Name', 'required');
				$msg = '';
				if ($this->form_validation->run() !== false) {
					$editId = $this->input->post('ticket_details_id');

					if ($editId == '') {
						$filename = '';
						if (!empty($_FILES['tdetails_image']['name'])) {
							$config['upload_path'] = 'uploads/ticket_details';
							$config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF|SVG';
							//$config['max_size'] = '10000';
							$config['encrypt_name'] = TRUE;
							$this->load->library('upload', $config);
							if ($this->upload->do_upload('tdetails_image')) {
								$outputData['tdetails_image'] = $this->upload->data();
								$insert_data['ticket_image'] = $outputData['tdetails_image']['file_name'];
								$filename = $outputData['tdetails_image']['file_name'];
							} else {
								$msg .= 'Failed to add ticket image';
							}
						}

						$insert_data['ticket_name'] = $this->input->post('name');
						$insert_data['ticket_type'] = $this->input->post('ticket_type');
						$insert_data['display_view'] = $this->input->post('display_view');
						$insert_data['status'] =   $this->input->post('status') ? 1 : 0;
						$insert_data['add_by'] = $this->session->userdata('admin_id');
						$insert_data['create_date'] = strtotime(date('Y-m-d H:i:s'));
						$inserted_id = $this->General_Model->insert_data('ticket_details', $insert_data);
						if ($inserted_id) {
							$lang = $this->General_Model->getAllItemTable('language','store_id',$this->session->userdata('storefront')->admin_id)->result();
							foreach ($lang as $key => $l_code) {
								$language_data = array(
									'language' =>  $l_code->language_code,
									'ticket_details_id' => $inserted_id,
									'ticket_name' => $this->input->post('name'),
									'ticket_image' =>	$filename
								);
								$this->General_Model->insert_data('ticket_details_lang', $language_data);
							}
							$response = array('msg' => 'New ticket details added successfully.', 'redirect_url' => base_url() . 'settings/ticket_details', 'status' => 1);
						} else {
							$response = array('msg' => 'Failed to add new ticket details.', 'redirect_url' => base_url() . 'settings/ticket_details/add', 'status' => 0);
						}
						echo json_encode($response);
						exit;
					} else {
						$updateData = array();


						if (!empty($_FILES['tdetails_image']['name'])) {
							$tdata = $this->General_Model->getAllItemTable_array('ticket_details', array('id' => $editId))->row();
							if (@getimagesize(base_url() . './uploads/ticket_details/' . $tdata->ticket_image)) {
								unlink('./uploads/ticket_details/' . $tdata->ticket_image);
							}
							$config['upload_path'] = 'uploads/ticket_details';
							$config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF|SVG';
							//$config['max_size'] = '1000';
							$config['encrypt_name'] = TRUE;
							$this->load->library('upload', $config);
							if (!$this->upload->do_upload('tdetails_image')) {
								$msg .= 'Failed to add ticket details image';
							} else {
								$data = $this->upload->data();
								$imagename = $data['file_name'];
								$updateData_lang['ticket_image'] = $imagename;
								$updateData['ticket_image'] = $imagename;
							}
						} else {
							$updateData_lang['ticket_image'] = $this->input->post('exs_file');
						}
						

						$updateData['ticket_name'] = trim($this->input->post('name'));
						$updateData['ticket_type'] = $this->input->post('ticket_type');
						$updateData['display_view'] = $this->input->post('display_view');
						$updateData['status'] = $this->input->post('status') ? 1 : 0;

						$this->General_Model->update('ticket_details', array('id' => $editId), $updateData);


						//Update language table			
						$updateData_lang['ticket_name'] = trim($this->input->post('name'));
						$this->General_Model->update('ticket_details_lang', array('ticket_details_id' => $editId, 'language' => $this->session->userdata('language_code')), $updateData_lang);
						if($updateData_lang['ticket_image'] != ''){

							$lang = $this->General_Model->getAllItemTable('language','store_id',$this->session->userdata('storefront')->admin_id)->result();
							foreach ($lang as $key => $l_code) {
								$language_data = array(
									'ticket_image' =>	$updateData_lang['ticket_image']
								);
								$this->General_Model->update('ticket_details_lang', array('ticket_details_id' => $editId), $language_data);
							}
							
						}
						

						$response = array('status' => 1, 'msg' => 'Ticket details data updated Successfully.', 'redirect_url' => base_url() . 'settings/ticket_details');
						echo json_encode($response);
						exit;
					}
				} else {
					$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'settings/ticket_details/add', 'status' => 0);
				}
				echo json_encode($response);
				exit;
			}
		} else {
			if ($this->input->post('submit') != NULL) {
				$search_text = $this->input->post('search');
				$this->session->set_userdata(array("searchtdetail" => $search_text));
			} else {
				if ($this->session->userdata('searchtdetail') != NULL) {
					$search_text = $this->session->userdata('searchtdetail');
				}
			}
			$row_count = $this->uri->segment(4);
			$this->loadRecord($row_count, 'ticket_details', 'settings/ticket_details/ticket_details', 'id', 'DESC', 'settings/ticket_details/ticket_details', 'ticket_details', 'ticket_details', $search_text);
		}
	}

	public function email_management()
	{
		$this->data['roles'] = $this->General_Model->getAllItemTable('admin_role', 'status', 'ACTIVE', 'admin_role_id', 'ASC')->result();
		$active_function_id = $this->General_Model->get_email_permissions();
		$function_ids = array();
		foreach ($active_function_id as $value) {
			$function_ids[$value["privilege_id"]][] = $value["email_type_id"];
		}
		$this->data['active_functions'] = $function_ids;
		$this->load->view('settings\api_settings\email_management\email_permissions', $this->data);
	}
	public function save_email_permission()
	{
		$this->data = array();
		$i = 0;
		foreach ($_POST['privilege'] as $pkey => $pvalue) {
			$j = 0;
			foreach ($pvalue as $key => $value) {
				$this->data[$i]["privilege_id"] = $pkey;
				$this->data[$i]["email_type_id"] = $value;
				$j++;
				$i++;
			}
		}
		$response = $this->General_Model->update_email_permission($this->data);
		if ($response) {
			$messge = array('msg' => 'Email Permissions Updated successfully.', 'redirect_url' => base_url() . 'settings/email_management', 'status' => 1);
		} else {
			$messge = array('msg' => 'Failed to update Email Permissions.', 'redirect_url' => base_url() . 'settings/email_management',);
		}
		echo json_encode($messge);
		exit;
	}



	/**
	 * @desc static page related operations
	 * Add
	 * Edit
	 * List
	 * Delete
	 * Save
	 */
	public function static_pages()
	{

		$segment = $this->uri->segment(3);
		if ($segment == 'add') {
			$this->data['page_types'] = $this->General_Model->getAllItemTable('page_types')->result();
			$this->load->view('settings/static_pages/add_page', $this->data);
		} else if ($segment == 'edit') {
			$this->data['page_types'] = $this->General_Model->getAllItemTable('page_types')->result();
			$segment4 = $this->uri->segment(4);
			if ($segment4 != "") {
				$edit_id = $segment4;
				$this->data['page_details'] = $this->General_Model->get_static_page_data($edit_id)->row();
			}
			$this->load->view('settings/static_pages/add_page', $this->data);
		} else if ($segment == 'delete') {
			$segment4 = $this->uri->segment(4);
			$delete_id = $segment4;
			$delete = $this->General_Model->delete_data('static_page', 'id', $delete_id);
			if ($delete == 1) {
				$this->General_Model->delete_data('static_page_lang', 'static_page_id', $delete_id);
				$response = array('status' => 1, 'msg' => 'Page deleted Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error while deleting page.');
				echo json_encode($response);
				exit;
			}
		} else if ($segment == 'save') {
			$this->form_validation->set_rules('ptype', 'Page Type', 'required');
			$this->form_validation->set_rules('title', 'Page Title', 'required');
			$this->form_validation->set_rules('page_content', 'Page Content', 'required');
			if ($this->form_validation->run() !== false) {
				$insert_data = array(
					'page_type' => $_POST['ptype'],
					'page_title' => $_POST['title'],
					'page_description' => $_POST['page_content'],
				);
				$insert_data['status'] = $this->input->post('status') ? 1 : 0;

				if ($_POST['page_id'] == '') {
					$insertData['create_date'] = strtotime(date('Y-m-d H:i:s'));
					$insert_data['add_by'] = $this->session->userdata('admin_id');
					$inserted_id = $this->General_Model->insert_data('static_page', $insert_data);
					if ($inserted_id) {
						//Add to language table
						$lang = $this->General_Model->getAllItemTable('language','store_id',$this->session->userdata('storefront')->admin_id)->result();
						foreach ($lang as $key => $l_code) {
							$insertData_lang = array();
							$insertData_lang['static_page_id'] = $inserted_id;
							$insertData_lang['language'] = $l_code->language_code;
							$insertData_lang['title'] = trim($this->input->post('title'));
							$insertData_lang['description'] = trim($this->input->post('page_content'));
							$this->General_Model->insert_data('static_page_lang', $insertData_lang);
						}
						$response = array('msg' => 'Page details added successfully.', 'redirect_url' => base_url() . 'settings/static_pages', 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to add new page.', 'redirect_url' => base_url() . 'settings/static_pages/add', 'status' => 0);
					}
					echo json_encode($response);
					exit;
				} else {
					$editId = $_POST['page_id'];
					if ($this->General_Model->update_table('static_page', 'id', $editId, $insert_data)) {
						//Update language table         
						$updateData_lang['title'] = trim($this->input->post('title'));
						$updateData_lang['description'] = strip_tags($this->input->post('page_content'));
						$this->General_Model->update('static_page_lang', array('static_page_id' => $editId, 'language' => $this->session->userdata('language_code')), $updateData_lang);
						$response = array('msg' => 'Page details updated Successfully.', 'redirect_url' => base_url() . 'settings/static_pages', 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to update page details.', 'redirect_url' => base_url() . 'settings/static_pages/edit/' . $editId, 'status' => 0);
					}
					echo json_encode($response);
					exit;
				}
			} else {
				$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'settings/static_pages/add', 'status' => 0);
			}
			echo json_encode($response);
			exit;
		} else {
			if ($this->input->post('submit') != NULL) {
				$search_text = $this->input->post('search');
				$this->session->set_userdata(array("searchstpage" => $search_text));
			} else {
				if ($this->session->userdata('searchstpage') != NULL) {
					$search_text = $this->session->userdata('searchstpage');
				}
			}
			$row_count = $this->uri->segment(4);
			$this->loadRecord($row_count, 'static_page', 'settings/static_pages/static_pages', 'id', 'DESC', 'settings/static_pages/static_pages', 'pages', 'static_pages', $search_text);
		}
	}
	/**
	 * @desc top league related operations
	 * Add
	 * Edit
	 * List
	 * Delete
	 * Save
	 */
	public function league()
	{

		$segment = $this->uri->segment(3);
		if ($segment == 'add') {
			$this->data['tournments']   = $this->General_Model->get_tournments()->result();
			$this->load->view('settings/league/add_league', $this->data);
		} else if ($segment == 'edit') {
			$this->data['tournments']   = $this->General_Model->get_tournments()->result();
			$segment4 = $this->uri->segment(4);
			if ($segment4 != "") {
				$edit_id = $segment4;
				$this->data['top_league'] = $this->General_Model->getAllItemTable_array('top_league_cups', array('id' => $edit_id))->row();
			}
			$this->load->view('settings/league/add_league', $this->data);
		} else if ($segment == 'delete') {
			$segment4 = $this->uri->segment(4);
			$delete_id = $segment4;
			$delete = $this->General_Model->delete_data('top_league_cups', 'id', $delete_id);
			if ($delete == 1) {
				$response = array('status' => 1, 'msg' => 'Top league deleted Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error while deleting top league.');
				echo json_encode($response);
				exit;
			}
		} else if ($segment == 'save') {
			$this->form_validation->set_rules('tournament', 'Tournament', 'required');
			if ($this->form_validation->run() !== false) {
				$insert_data = array(
					'tournament_id' => $_POST['tournament'],
					'match_type' => $_POST['match_type']
				);
				if ($_POST['top_league_id'] == '') {
					$insertData['create_date'] = strtotime(date('Y-m-d H:i:s'));
					$insert_data['add_by'] = $this->session->userdata('storefront')->admin_id;
					$inserted_id = $this->General_Model->insert_data('top_league_cups', $insert_data);
					if ($inserted_id) {
						$response = array('msg' => 'Top league details added successfully.', 'redirect_url' => base_url() . 'settings/league', 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to add new top league.', 'redirect_url' => base_url() . 'settings/league/add', 'status' => 0);
					}
					echo json_encode($response);
					exit;
				} else {
					$editId = $_POST['top_league_id'];
					if ($this->General_Model->update_table('top_league_cups', 'id', $editId, $insert_data)) {
						$response = array('msg' => 'Top league details updated Successfully.', 'redirect_url' => base_url() . 'settings/league', 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to update top league details.', 'redirect_url' => base_url() . 'settings/league/edit/' . $editId, 'status' => 0);
					}
					echo json_encode($response);
					exit;
				}
			} else {
				$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'settings/league/add', 'status' => 0);
			}
			echo json_encode($response);
			exit;
		} else {
			if ($this->input->post('submit') != NULL) {
				$search_text = $this->input->post('search');
				$this->session->set_userdata(array("searchtolcup" => $search_text));
			} else {
				if ($this->session->userdata('searchtolcup') != NULL) {
					$search_text = $this->session->userdata('searchtolcup');
				}
			}
			$row_count = $this->uri->segment(3);
			$this->loadRecord($row_count, 'top_league_cups', 'settings/league', 'id', 'DESC', 'settings/league/leagues', 'topleagues', 'topleagues', $search_text);
		}
	}
	/**
	 * @desc top cups related operations
	 * Add
	 * Edit
	 * List
	 * Delete
	 * Save
	 */
	public function cups()
	{
		$segment = $this->uri->segment(3);
		if ($segment == 'add') {
			$this->data['tournments']   = $this->General_Model->get_tournments()->result();
			$this->load->view('settings/cups/add_cups', $this->data);
		} else if ($segment == 'edit') {
			$this->data['tournments']   = $this->General_Model->get_tournments()->result();
			$segment4 = $this->uri->segment(4);
			if ($segment4 != "") {
				$edit_id = $segment4;
				$this->data['top_cup'] = $this->General_Model->getAllItemTable_array('top_league_cups', array('id' => $edit_id))->row();
			}
			$this->load->view('settings/cups/add_cups', $this->data);
		} else if ($segment == 'delete') {
			$segment4 = $this->uri->segment(4);
			$delete_id = $segment4;
			$delete = $this->General_Model->delete_data('top_league_cups', 'id', $delete_id);
			if ($delete == 1) {
				$response = array('status' => 1, 'msg' => 'Top cup deleted Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error while deleting top cup.');
				echo json_encode($response);
				exit;
			}
		} else if ($segment == 'save') {
			$this->form_validation->set_rules('tournament', 'Tournament', 'required');
			if ($this->form_validation->run() !== false) {
				$insert_data = array(
					'tournament_id' => $_POST['tournament'],
					'match_type' => $_POST['match_type']
				);
				if ($_POST['top_cup_id'] == '') {
					$insertData['create_date'] = strtotime(date('Y-m-d H:i:s'));
					$insert_data['add_by'] = $this->session->userdata('storefront')->admin_id;
					$inserted_id = $this->General_Model->insert_data('top_league_cups', $insert_data);
					if ($inserted_id) {
						$response = array('msg' => 'Top cup details added successfully.', 'redirect_url' => base_url() . 'settings/cups', 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to add new top cup.', 'redirect_url' => base_url() . 'settings/cups/add', 'status' => 0);
					}
					echo json_encode($response);
					exit;
				} else {
					$editId = $_POST['top_cup_id'];
					if ($this->General_Model->update_table('top_league_cups', 'id', $editId, $insert_data)) {
						$response = array('msg' => 'Top cup details updated Successfully.', 'redirect_url' => base_url() . 'settings/cups', 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to update top cup details.', 'redirect_url' => base_url() . 'settings/cups/edit/' . $editId, 'status' => 0);
					}
					echo json_encode($response);
					exit;
				}
			} else {
				$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'settings/cups/add', 'status' => 0);
			}
			echo json_encode($response);
			exit;
		} else {
			if ($this->input->post('submit') != NULL) {
				$search_text = $this->input->post('search');
				$this->session->set_userdata(array("searchcups" => $search_text));
			} else {
				if ($this->session->userdata('searchcups') != NULL) {
					$search_text = $this->session->userdata('searchcups');
				}
			}
			$row_count = $this->uri->segment(3);
			$this->loadRecord($row_count, 'top_league_cups', 'settings/cups', 'id', 'DESC', 'settings/cups/cups', 'topcups', 'topcups', $search_text);
		}
	}
	/**
	 * @desc top cups related operations
	 * Add
	 * Edit
	 * List
	 * Delete
	 * Save
	 */
	public function upcoming_events()
	{
		$segment = $this->uri->segment(3);
		if ($segment == 'add') {
			$this->data['matches']   = $this->General_Model->get_matches('', 'upcoming', '', '', '', '', '')->result();
			$this->load->view('settings/upcoming_events/add_upcoming_event', $this->data);
		} else if ($segment == 'edit') {
			$this->data['matches']   = $this->General_Model->get_matches('', 'upcoming', '', '', '', '', '')->result();
			$segment4 = $this->uri->segment(4);
			if ($segment4 != "") {
				$edit_id = $segment4;
				$this->data['upcoming_events'] = $this->General_Model->getAllItemTable_array('upcoming_event', array('id' => $edit_id))->row();
			}
			$this->load->view('settings/upcoming_events/add_upcoming_event', $this->data);
		} else if ($segment == 'delete') {
			$segment4 = $this->uri->segment(4);
			$delete_id = $segment4;
			$delete = $this->General_Model->delete_data('upcoming_event', 'id', $delete_id);
			if ($delete == 1) {
				$response = array('status' => 1, 'msg' => 'Upcoming event deleted Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error while deleting upcoming event.');
				echo json_encode($response);
				exit;
			}
		} else if ($segment == 'save') {
			$this->form_validation->set_rules('match', 'Match', 'required');
			if ($this->form_validation->run() !== false) {
				$insert_data = array(
					'match_id' => $_POST['match']
				);
				if ($_POST['upcoming_event_id'] == '') {
					$insert_data['add_by'] = $this->session->userdata('storefront')->admin_id;
					$inserted_id = $this->General_Model->insert_data('upcoming_event', $insert_data);
					if ($inserted_id) {
						$response = array('msg' => 'Upcoming event details added successfully.', 'redirect_url' => base_url() . 'settings/upcoming_events', 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to add new upcoming event.', 'redirect_url' => base_url() . 'settings/upcoming_events/add', 'status' => 0);
					}
					echo json_encode($response);
					exit;
				} else {
					$editId = $_POST['upcoming_event_id'];
					if ($this->General_Model->update_table('upcoming_event', 'id', $editId, $insert_data)) {
						$response = array('msg' => 'Upcoming event details updated Successfully.', 'redirect_url' => base_url() . 'settings/upcoming_events', 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to update upcoming event details.', 'redirect_url' => base_url() . 'settings/upcoming_events/edit/' . $editId, 'status' => 0);
					}
					echo json_encode($response);
					exit;
				}
			} else {
				$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'settings/upcoming_events/add', 'status' => 0);
			}
			echo json_encode($response);
			exit;
		} else {
			if ($this->input->post('submit') != NULL) {
				$search_text = $this->input->post('search');
				$this->session->set_userdata(array("searchupeve" => $search_text));
			} else {
				if ($this->session->userdata('searchupeve') != NULL) {
					$search_text = $this->session->userdata('searchupeve');
				}
			}
			$row_count = $this->uri->segment(3);
			$this->loadRecord($row_count, 'upcoming_event', 'settings/upcoming_events', 'id', 'DESC', 'settings/upcoming_events/upcoming_events', 'upcoming_events', 'upcoming_events', $search_text);
		}
	}

		/**
	 * @desc static page related operations
	 * Add
	 * Edit
	 * List
	 * Delete
	 * Save
	 */
	public function banners()
	{

		$segment = $this->uri->segment(3);
		if ($segment == 'add') {
			$this->load->view('settings/banners/add_banners', $this->data);
		} else if ($segment == 'edit') {			
			$segment4 = $this->uri->segment(4);
			if ($segment4 != "") {
				$edit_id = $segment4;
				$this->data['banner_details'] = $this->General_Model->get_banner_data($edit_id)->row();
			}
			$this->load->view('settings/banners/add_banners', $this->data);
		} else if ($segment == 'delete') {
			$segment4 = $this->uri->segment(4);
			$delete_id = $segment4;
			$delete = $this->General_Model->delete_data('banners', 'id', $delete_id);
			if ($delete == 1) {
				$this->General_Model->delete_data('banners_lang', 'banner_id', $delete_id);
				$response = array('status' => 1, 'msg' => 'Banner deleted Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error while deleting banner.');
				echo json_encode($response);
				exit;
			}
		} else if ($segment == 'save') {
			$this->form_validation->set_rules('title', 'Banner Title', 'required');
			$this->form_validation->set_rules('banner_description', 'Banner Description', 'required');
			$this->form_validation->set_rules('banner_image', 'Banner Image', 'required');
			if (!empty($_FILES['banner_image']['name'])) {
				$this->form_validation->set_rules('banner_image', 'Banner file', 'callback_banner_image_file_check');
			}
			if ($this->form_validation->run() !== false) {
				$msg='';
				
				$insert_data ['title'] = $_POST['title'];
				$insert_data ['description'] = $_POST['banner_description'];
				$insert_data['status'] = $this->input->post('status') ? 1 : 0;

				if ($_POST['banner_id'] == '') {

					if (!empty($_FILES['banner_image']['name'])) {
						
						$config['upload_path'] = 'uploads/banners';
						$config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF|';
						//$config['max_size'] = '1000';
						$config['encrypt_name'] = TRUE;
						$this->load->library('upload', $config);
						if (!$this->upload->do_upload('banner_image')) {
							$msg .= 'Failed to add banner image';
						} else {
							$data = $this->upload->data();
							$imagename = $data['file_name'];
							$insert_data['image'] = $imagename;
						}
					} 
				
					$insert_data['add_by'] = $this->session->userdata('storefront')->admin_id;
					$inserted_id = $this->General_Model->insert_data('banners', $insert_data);
					if ($inserted_id) {
						//Add to language table
						$lang = $this->General_Model->getAllItemTable('language','store_id',$this->session->userdata('storefront')->admin_id)->result();
						foreach ($lang as $key => $l_code) {
							$insertData_lang = array();
							$insertData_lang['banner_id'] = $inserted_id;
							$insertData_lang['language'] = $l_code->language_code;
							$insertData_lang['title'] = trim($this->input->post('title'));
							$insertData_lang['description'] = trim($this->input->post('banner_description'));
							$this->General_Model->insert_data('banners_lang', $insertData_lang);
						}
						$response = array('msg' => 'Banner details added successfully.', 'redirect_url' => base_url() . 'settings/banners', 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to add new banner.', 'redirect_url' => base_url() . 'settings/banners/add', 'status' => 0);
					}
					echo json_encode($response);
					exit;
				} else {
					$editId = $_POST['banner_id'];

					if (!empty($_FILES['banner_image']['name'])) {
						$bannerdata = $this->General_Model->getAllItemTable_array('banners', array('id' => $editId))->row();
						if (@getimagesize(base_url() . './uploads/banners/' . $bannerdata->banner_image)) {
							unlink('./uploads/banners/' . $bannerdata->banner_image);
						}
						$config['upload_path'] = 'uploads/banners';
						$config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF|';
						//$config['max_size'] = '1000';
						$config['encrypt_name'] = TRUE;
						$this->load->library('upload', $config);
						if (!$this->upload->do_upload('banner_image')) {
							$msg .= 'Failed to add banner image';
						} else {
							$data = $this->upload->data();
							$imagename = $data['file_name'];
							$insert_data['image'] = $imagename;
						}
					} 


					if ($this->General_Model->update_table('banners', 'id', $editId, $insert_data)) {
						//Update language table         
						$updateData_lang['title'] = trim($this->input->post('title'));
						$updateData_lang['description'] = strip_tags($this->input->post('banner_description'));
						$this->General_Model->update('banners_lang', array('banner_id' => $editId, 'language' => $this->session->userdata('language_code')), $updateData_lang);
						$response = array('msg' => 'Banner details updated Successfully.', 'redirect_url' => base_url() . 'settings/banners', 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to update banner details.', 'redirect_url' => base_url() . 'settings/banners/edit/' . $editId, 'status' => 0);
					}
					echo json_encode($response);
					exit;
				}
			} else {
				$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'settings/banners/add', 'status' => 0);
			}
			echo json_encode($response);
			exit;
		} else {
			if ($this->input->post('submit') != NULL) {
				$search_text = $this->input->post('search');
				$this->session->set_userdata(array("searchbanner" => $search_text));
			} else {
				if ($this->session->userdata('searchbanner') != NULL) {
					$search_text = $this->session->userdata('searchbanner');
				}
			}
			$row_count = $this->uri->segment(4);
			$this->loadRecord($row_count, 'banners', 'settings/banners/banners', 'id', 'DESC', 'settings/banners/banners', 'banners', 'banners', $search_text);
		}
	}
}
