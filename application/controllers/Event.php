<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);
class Event extends CI_Controller
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


	public function stadiums()
	{

		echo "stadiums";
		exit;
	}

	public function team_categories()
	{

		echo "team_categories";
		exit;
	}

	public function ticket_categories()
	{

		echo "ticket_categories";
		exit;
	}

	public function match_categories()
	{

		echo "match_categories";
		exit;
	}

	public function tournaments()
	{

		echo 'tournaments';
		exit;
	}

	public function tournament_details()
	{

		echo "tournament_details";
		exit;
	}



	public function tournament_categories()
	{

		echo "tournament_categories";
		exit;
	}

	public function add_tournaments()
	{

		echo "add_tournaments";
		exit;
	}


	public function teams()
	{

		echo "teams";
		exit;
	}

	public function matches()
	{

		// $this->data['app'] = $this->app_data();

		$match_segment  = $segment = $this->uri->segment(3);
		$match_id       = json_decode(base64_decode($this->uri->segment(4)));

		if ($match_segment == "add_match") {

			$this->data['stadiums']     = $this->General_Model->get_stadium()->result();
			$this->data['teams']        = $this->General_Model->get_teams()->result();
			$this->data['tournments']   = $this->General_Model->get_tournments()->result();
			$this->data['countries']    = $this->General_Model->getAllItemTable('countries')->result();
			$this->data['currencies']   = $this->General_Model->getAllItemTable('currency_types','store_id',$this->session->userdata('storefront')->admin_id)->result();
			if ($match_id != '') {

				$this->data['matches']      = $this->General_Model->get_matches($match_id)->row();
				$getBannedCountries = $this->db->query("SELECT * FROM `banned_countries_match` WHERE `match_id` = " . $match_id)->result();
				$ban_arr = [];
				foreach ($getBannedCountries as $bc) {
					$ban_arr[] = $bc->country_id;
				}
				$this->data['ban_arr'] = $ban_arr;
			}

			//echo "<pre>";print_r($this->data['currencies']);exit;
			$this->load->view('event/add_match', $this->data);
		}
		else if ($match_segment == "add_content") {

			$this->data['stadiums']     = $this->General_Model->get_stadium()->result();
			$this->data['teams']        = $this->General_Model->get_teams()->result();
			$this->data['tournments']   = $this->General_Model->get_tournments()->result();
			$this->data['countries']    = $this->General_Model->getAllItemTable('countries')->result();
			$this->data['currencies']   = $this->General_Model->getAllItemTable('currency_types')->result();
			if ($match_id != '') {

				$this->data['matches']      = $this->General_Model->get_matches($match_id)->row();
				$getBannedCountries = $this->db->query("SELECT * FROM `banned_countries_match` WHERE `match_id` = " . $match_id)->result();
				$ban_arr = [];
				foreach ($getBannedCountries as $bc) {
					$ban_arr[] = $bc->country_id;
				}
				$this->data['ban_arr'] = $ban_arr;
			}

			//echo "<pre>";print_r($this->data['currencies']);exit;
			$this->load->view('event/add_content', $this->data);
		}
		else if ($match_segment == "get_city") {

			$country_id = $_POST['country_id'];
			$states    = $this->General_Model->get_state_cities($country_id);
			$statesCount = COUNT($states);
			$city = '';
			if ($statesCount > 0) {
				$city .= '<option value="">-Select City-</option>';
				foreach ($states as $state) {
					$city .= '<option value="' . $state->id . '">' . $state->name . '</option>';
				}
			} else {
				$city .= '<option value="">City not available</option>';
			}

			echo json_encode(array('city' => $city, 'state' => $states[0]->state_id));
			exit;
		} 
		else if ($match_segment == "duplicateCheck") {

					$matchId = $this->input->post('matchId');

					$tournamentArray = $this->General_Model->getid('tournament', array('tournament.t_id' => trim($this->input->post('tournament')), 'tournament_lang.language' => 'en'))->result();
					$team1Array = $this->General_Model->getid('teams', array('teams.id' => $this->input->post('team1'), 'teams_lang.language' => 'en'))->result();
					$team2Array = $this->General_Model->getid('teams', array('teams.id' => $this->input->post('team2'), 'teams_lang.language' => 'en'))->result();
					$title = strip_tags($tournamentArray[0]->tournament_name . ' ' . $team1Array[0]->team_name . '-vs-' . $team2Array[0]->team_name . '-tickets');

					if ($this->input->post('event_url')) {
						$title = strip_tags($this->input->post('event_url'));
					}
					$titleURL = strtolower(url_title($title));

					if($matchId != ""){

					$duplicateCheck = $this->General_Model->getid('match_info', array('slug' => $titleURL,'matchid_not' => $matchId))->result();

					}else{

					$duplicateCheck = $this->General_Model->getid('match_info', array('slug' => $titleURL))->result();

					}
					

					if (count($duplicateCheck) > 0) {
						//$titleURL = $titleURL . '-' . time();
						$response = array('status' => 0, 'msg' => 'URL was used. Do you want Apply it to the new Match ?', 'redirect_url' => base_url() . 'event/matches/upcoming');
					
					}
					else{
						$response = array('status' => 1, 'msg' => 'ok', 'redirect_url' => base_url() . 'event/matches/upcoming');
					}
					echo json_encode($response);
					exit;


		}
		else if ($match_segment == "save_matches") {

			

			$update_url = $this->input->post('update_url');
			$matchId = $this->input->post('matchId');

			if ($matchId == '') {

				if ($this->input->post()) {

					
					$insertData = array();
					$insertData['team_1'] = trim($this->input->post('team1'));
					$insertData['team_2'] = trim($this->input->post('team2'));
					$insertData['match_name'] = trim($this->input->post('matchname'));
					$insertData['hometown'] = trim($this->input->post('hometown'));
					$insertData['status'] = $this->input->post('is_active') ? 1 : 0;
					$insertData['availability'] = $this->input->post('availability') ? 1 : 0;
					$insertData['upcoming_events'] = $this->input->post('upcomingevents') ? 1 : 0;
					$insertData['match_date'] = date('Y-m-d H:i:s', strtotime($this->input->post('matchdate') . ' ' . $this->input->post('matchtime')));
					$insertData['match_time'] = $this->input->post('matchtime');
					$insertData['tournament'] = trim($this->input->post('tournament'));
					$insertData['venue'] = $this->input->post('venue');
					$insertData['price_type'] = $this->input->post('price_type');
					$insertData['matchticket'] = $this->input->post('matchticket');
					$insertData['daysremaining'] = 1;//$this->input->post('daysremaining');
					$insertData['state'] = $this->input->post('state');
					$insertData['city'] = $this->input->post('city');
					$insertData['country'] = $this->input->post('country');
					$insertData['create_date'] = strtotime(date('Y-m-d h:i:s'));
					$insertData['ignoreautoswitch'] = $this->input->post('ignoreautoswitch') ? 1 : 0;
					$insertData['top_games'] = $this->input->post('top_games') ? 1 : 0;
					$insertData['high_demand'] = $this->input->post('high_demand') ? '1' : '0';
					$insertData['almost_sold'] = $this->input->post('almost_sold') ? '1' : '0';
					$insertData['add_by'] = $this->session->userdata('admin_id');


					$tournamentArray = $this->General_Model->getid('tournament', array('tournament.t_id' => trim($this->input->post('tournament')), 'tournament_lang.language' => 'en'))->result();
					$team1Array = $this->General_Model->getid('teams', array('teams.id' => $this->input->post('team1'), 'teams_lang.language' => 'en'))->result();
					$team2Array = $this->General_Model->getid('teams', array('teams.id' => $this->input->post('team2'), 'teams_lang.language' => 'en'))->result();
					$title = strip_tags($tournamentArray[0]->tournament_name . ' ' . $team1Array[0]->team_name . '-vs-' . $team2Array[0]->team_name . '-tickets');

					if ($this->input->post('event_url')) {
						$title = strip_tags($this->input->post('event_url'));
					}
					$titleURL = strtolower(url_title($title));
					$duplicateCheck = $this->General_Model->getid('match_info', array('slug' => $titleURL))->result();
					if (count($duplicateCheck) > 0) {
						if($update_url == 1){
							$oldtitleURL = $duplicateCheck[0]->slug.'-'.time();
							$mupdateData = array('slug' => $oldtitleURL);
							$this->General_Model->update('match_info', array('m_id' => $duplicateCheck[0]->m_id), $mupdateData);
						}
						else{
						$response = array('status' => 0, 'msg' => 'URL Exists.Please use different one.', 'redirect_url' => base_url() . 'event/matches/upcoming');
						echo json_encode($response);
						exit;
						}
					}
					$insertData['slug'] = $titleURL;
					$match_id = $this->General_Model->insert_data('match_info', $insertData);

					// 
					$matchId = $this->db->insert_id();
					$this->db->delete('banned_countries_match', array('match_id' => $matchId));

					$bancountry_ids = $this->input->post('bcountry');
					foreach ($bancountry_ids as $val) {
						$this->data = array(
							'match_id' => $matchId,
							'country_id' => trim($val)
						);
						$this->db->insert('banned_countries_match', $this->data);
					}
					// 

					$lang = $this->General_Model->getAllItemTable('language','store_id',$this->session->userdata('storefront')->admin_id)->result();
					foreach ($lang as $key => $l_code) {
						$insertData_lang = array();
						$insertData_lang['match_id'] = $match_id;
						$insertData_lang['language'] = $l_code->language_code;
						$insertData_lang['match_name'] = trim($this->input->post('matchname'));

						/*$insertData_lang['meta_title'] = $this->input->post('metatitle');
						$insertData_lang['meta_description'] = $this->input->post('metadescription');*/
						if ($this->input->post('description')) {
							//$insertData_lang['description'] = $this->input->post('description');
					
						} else {

							$tournamentArray = $this->General_Model->getid('tournament', array('tournament.t_id' => trim($this->input->post('tournament')), 'tournament_lang.language' => 'en'))->result();
							$team1Array = $this->General_Model->getid('teams', array('teams.id' => $this->input->post('team1'), 'teams_lang.language' => 'en'))->result();
							$team2Array = $this->General_Model->getid('teams', array('teams.id' => $this->input->post('team2'), 'teams_lang.language' => 'en'))->result();

							$stadium = $this->General_Model->getid('stadium', array('stadium.s_id' => $this->input->post('venue')))->result();

							$description = 'Buy ' . $team1Array[0]->team_name . ' vs ' . $team2Array[0]->team_name . ' tickets for the ' . $tournamentArray[0]->tournament_name . ' game being played on ' . date('d M Y', strtotime($insertData['match_date'])) . ' at ' . $stadium[0]->stadium_name . '. 1BoxOffice offers a wide range of ' . $team1Array[0]->team_name . ' vs ' . $team2Array[0]->team_name . ' tickets that suits most football fans budget. Contact 1BoxOffice today for more information on how to buy ' . $team1Array[0]->team_name . ' tickets!';
							$insertData_lang['description'] = $description;
						}
						$this->General_Model->insert_data('match_info_lang', $insertData_lang);
					}

					$response = array('status' => 1, 'msg' => 'Match Created Successfully.', 'redirect_url' => base_url() . 'event/matches/upcoming');
					echo json_encode($response);
					exit;
				}
			} else {

				if ($this->input->post()) {

					$matchId = $this->input->post('matchId');

					if($_POST['flag'] == "content"){
					$updateData['seo_keywords'] = $this->input->post('seo_keywords');
/*
					$tournamentArray = $this->General_Model->getid('tournament', array('tournament.t_id' => trim($this->input->post('tournament')), 'tournament_lang.language' => 'en'))->result();
					$team1Array = $this->General_Model->getid('teams', array('teams.id' => $this->input->post('team1'), 'teams_lang.language' => 'en'))->result();
					$team2Array = $this->General_Model->getid('teams', array('teams.id' => $this->input->post('team2'), 'teams_lang.language' => 'en'))->result();
					$title = strip_tags($tournamentArray[0]->tournament_name . ' ' . $team1Array[0]->team_name . '-vs-' . $team2Array[0]->team_name . '-tickets');

					if ($this->input->post('event_url')) {
						$title = strip_tags($this->input->post('event_url'));
					}
					$titleURL = strtolower(url_title($title));
					$duplicateCheck = $this->General_Model->getid('match_info', array('slug' => $titleURL, 'matchid_not' => $matchId))->result();
					if (count($duplicateCheck) > 0) {
						if($update_url == 1){
							$oldtitleURL = $titleU.'-'.time();
							$mupdateData = array('slug' => $oldtitleURL);
							$this->General_Model->update('match_info', array('m_id' => $duplicateCheck[0]->m_id), $mupdateData);
						}
						else{
						$response = array('status' => 0, 'msg' => 'URL Exists.Please use different one.', 'redirect_url' => base_url() . 'event/matches/upcoming');
						echo json_encode($response);
						exit;
						}
					}*/

					$this->General_Model->update('match_info', array('m_id' => $matchId), $updateData);
					}
					if($_POST['flag'] != "content"){

					$updateData = array();
					$updateData['team_1'] = trim($this->input->post('team1'));
					$updateData['team_2'] = trim($this->input->post('team2'));
					$updateData['hometown'] = trim($this->input->post('hometown'));
					$updateData['tournament'] = trim($this->input->post('tournament'));
					$updateData['status'] = $this->input->post('is_active') ? 1 : 0;
					$updateData['availability'] = $this->input->post('availability') ? 1 : 0;
					$updateData['upcoming_events'] = $this->input->post('upcomingevents') ? 1 : 0;
					$updateData['match_date'] = date('Y-m-d H:i:s', strtotime($this->input->post('matchdate') . ' ' . $this->input->post('matchtime')));
					$updateData['match_time'] = $this->input->post('matchtime');
					$updateData['venue'] = $this->input->post('venue');
					$updateData['state'] = $this->input->post('state');
					$updateData['city'] = $this->input->post('city');
					$updateData['country'] = $this->input->post('country');
					$updateData['create_date'] = strtotime(date('Y-m-d h:i:s'));
					$updateData['matchticket'] = $this->input->post('matchticket');
					$updateData['daysremaining'] = 1;//$this->input->post('daysremaining');
					$updateData['ignoreautoswitch'] = $this->input->post('ignoreautoswitch') ? 1 : 0;
					$updateData['top_games'] = $this->input->post('top_games') ? 1 : 0;
					$updateData['high_demand'] = $this->input->post('high_demand') ? '1' : '0';
					$updateData['almost_sold'] = $this->input->post('almost_sold') ? '1' : '0';
					$updateData['event_type'] = 'match';
					$updateData['add_by'] = $this->session->userdata('admin_id');
					$updateData['price_type'] = $this->input->post('price_type');

					//echo "<pre>";print_r($updateData);exit;


					$tournamentArray = $this->General_Model->getid('tournament', array('tournament.t_id' => trim($this->input->post('tournament')), 'tournament_lang.language' => 'en'))->result();
					$team1Array = $this->General_Model->getid('teams', array('teams.id' => $this->input->post('team1'), 'teams_lang.language' => 'en'))->result();
					$team2Array = $this->General_Model->getid('teams', array('teams.id' => $this->input->post('team2'), 'teams_lang.language' => 'en'))->result();
					$title = strip_tags($tournamentArray[0]->tournament_name . ' ' . $team1Array[0]->team_name . '-vs-' . $team2Array[0]->team_name . '-tickets');

					if ($this->input->post('event_url')) {
						$title = strip_tags($this->input->post('event_url'));
					}
					$titleURL = strtolower(url_title($title));
					$duplicateCheck = $this->General_Model->getid('match_info', array('slug' => $titleURL, 'matchid_not' => $matchId))->result();
					//echo 'duplicateCheck = '.count($duplicateCheck).'='.$update_url;exit;
					if (count($duplicateCheck) > 0) {
						if($update_url == 1){
							$oldtitleURL = $duplicateCheck[0]->slug.'-'.time();
							$mupdateData = array('slug' => $oldtitleURL);
							$this->General_Model->update('match_info', array('m_id' => $duplicateCheck[0]->m_id), $mupdateData);
						}
						else{
						$response = array('status' => 0, 'msg' => 'URL Exists.Please use different one.', 'redirect_url' => base_url() . 'event/matches/upcoming');
						echo json_encode($response);
						exit;
						}
					}
					$updateData['slug'] = $titleURL;
					//echo "<pre>";print_r($updateData);exit;
					$this->General_Model->update('match_info', array('m_id' => $matchId), $updateData);
					//echo $this->db->last_query();exit;
					$this->db->delete('banned_countries_match', array('match_id' => $matchId));

					$bancountry_ids = $this->input->post('bcountry');
					foreach ($bancountry_ids as $val) {
						$this->data = array(
							'match_id' => $matchId,
							'country_id' => trim($val)
						);
						$this->db->insert('banned_countries_match', $this->data);
					}
            
				}

					$updateData_lang = array();
					$updateData_lang['match_name'] = trim($this->input->post('matchname'));
					if ($this->input->post('description')) {
						$updateData_lang['description'] = $this->input->post('description');
					} else {
						$tournamentArray = $this->General_Model->getid('tournament', array('tournament.t_id' => trim($this->input->post('tournament')), 'tournament_lang.language' => 'en'))->result();
						$team1Array = $this->General_Model->getid('teams', array('teams.id' => $this->input->post('team1'), 'teams_lang.language' => 'en'))->result();
						$team2Array = $this->General_Model->getid('teams', array('teams.id' => $this->input->post('team2'), 'teams_lang.language' => 'en'))->result();
						$stadium = $this->General_Model->getid('stadium', array('stadium.s_id' => $this->input->post('venue')))->result();

						$description = 'Buy ' . $team1Array[0]->team_name . ' vs ' . $team2Array[0]->team_name . ' tickets for the ' . $tournamentArray[0]->tournament_name . ' game being played on ' . date('d M Y', strtotime($updateData['match_date'])) . ' at ' . $stadium[0]->stadium_name . '. 1BoxOffice offers a wide range of ' . $team1Array[0]->team_name . ' vs ' . $team2Array[0]->team_name . ' tickets that suits most football fans budget. Contact 1BoxOffice today for more information on how to buy ' . $team1Array[0]->team_name . ' tickets!';
						$updateData_lang['description'] = $description;
					}
					$updateData_lang['meta_title'] = $this->input->post('metatitle');
					$updateData_lang['meta_description'] = $this->input->post('metadescription');
					$this->General_Model->update('match_info_lang', array('match_id' => $matchId, 'language' => $this->session->userdata('language_code')), $updateData_lang);


					$response = array('status' => 1, 'msg' => 'Match data updated Successfully.', 'redirect_url' => base_url() . 'event/matches/upcoming');
					echo json_encode($response);
				}
			}
		} else if ($match_segment == "delete_match") {
			$match_id   = $segment = $this->uri->segment(4);
			$updateData_data['status'] = 2;
			/*$match_id   = $segment = $this->uri->segment(4);
			$delete     = $this->General_Model->delete_match_data($match_id);*/
			$delete = $this->General_Model->update('match_info', array('m_id' => $match_id), $updateData_data);


			if ($delete == 1) {

				$response = array('status' => 1, 'msg' => 'Match Moved to trash Successfully.');
				echo json_encode($response);
				exit;
			} else {

				$response = array('status' => 1, 'msg' => 'Error While Moving Match to trash.');
				echo json_encode($response);
				exit;
			}
		}
		else if ($match_segment == "undo_match") {
			$match_id   = $segment = $this->uri->segment(4);
			$updateData_data['status'] = 1;
			/*$match_id   = $segment = $this->uri->segment(4);
			$delete     = $this->General_Model->delete_match_data($match_id);*/
			$delete = $this->General_Model->update('match_info', array('m_id' => $match_id), $updateData_data);


			if ($delete == 1) {

				$response = array('status' => 1, 'msg' => 'Match Moved from trash Successfully.');
				echo json_encode($response);
				exit;
			} else {

				$response = array('status' => 1, 'msg' => 'Error While Undoing Match data.');
				echo json_encode($response);
				exit;
			}
		}
		 else {
			if ($this->input->post('submit') != NULL) {
				$search_text = $this->input->post('search');
				$this->session->set_userdata(array("searchmatch" => $search_text));
			} else {
				if ($this->session->userdata('searchmatch') != NULL) {
					$search_text = $this->session->userdata('searchmatch');
				}
			}
			$row_count   = $this->uri->segment(4);
			$status_flag = $this->uri->segment(3);

			$this->data['status_flag']     = $status_flag;

			$this->loadRecord($status_flag, $row_count, 'm_id', 'DESC', 'event/matches', 'matches',$search_text);
			//$this->load->view('event/matches',$this->data);
		}
	}


	/**
	 * Fetch data and display based on the pagination request
	 */
	public function loadRecord($status_flag, $rowno = 0, $order_column, $order_by, $view, $variable_name,$search='')
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

		$variable_name = "matches";
		$this->data['status_flag'] = $status_flag;
		if ($status_flag == "upcoming") {
			$url = "event/matches/upcoming";
			// All records count
			$allcount = $this->General_Model->get_matches('', 'upcoming','','','','','',$search)->num_rows();
	
			// Get records
			$record = $this->General_Model->get_matches('', 'upcoming', $rowno, $row_per_page, $order_column, $order_by,'',$search)->result();
		} else if ($status_flag == "expired") {
			$url = "event/matches/expired";

		//	$this->data['expired_matches'] = $this->General_Model->get_matches('', 'expired')->result();
			// All records count
			$allcount = $this->General_Model->get_matches('', 'expired','','','','','',$search)->num_rows();


			// Get records
			$record = $this->General_Model->get_matches('', 'expired', $rowno, $row_per_page, $order_column, $order_by,'',$search)->result();
		}
		else if ($status_flag == "trashed") {
			$url = "event/matches/trashed";

		//	$this->data['expired_matches'] = $this->General_Model->get_matches('', 'expired')->result();
			// All records count
			$allcount = $this->General_Model->get_matches('', 'trashed','','','','','',$search)->num_rows();


			// Get records
			$record = $this->General_Model->get_matches('', 'trashed', $rowno, $row_per_page, $order_column, $order_by,'',$search)->result();
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


	public function other_events_category()
	{
		$event_segment  = $segment = $this->uri->segment(3);
		$category_id       = json_decode(base64_decode($this->uri->segment(4)));

		if ($event_segment == "add_category") {
			$this->data['categories'] = $this->General_Model->get_other_events_categories()->result();
			if ($category_id != '') {
				$this->data['category']      = $this->General_Model->get_other_events_categories($row_no = '', $row_per_page = '', $orderColumn = '', $orderby = '', array('A.id' => $category_id))->row();
			}
			$this->load->view('event/add_other_event_category', $this->data);
		} else if ($event_segment == "save_category") {
			$categoryId = $this->input->post('categoryId');

			if ($categoryId == '') {

				if ($this->input->post()) {
					$insertData = array();
					$insertData['parent_id'] = trim($this->input->post('parent'));
					$insertData['category_name'] = trim($this->input->post('categoryname'));
					$insertData['status'] = $this->input->post('is_active') ? 1 : 0;
					$insertData['sort'] = $this->input->post('sortno');
					$insertData['category_desc'] = $this->input->post('category_description');
					$insertData['create_date'] = strtotime(date('Y-m-d h:i:s'));
					$insertData['add_by'] = $this->session->userdata('admin_id');

					$category_id = $this->General_Model->insert_data('otherevent_category', $insertData);

					$lang = $this->General_Model->getAllItemTable('language','store_id',$this->session->userdata('storefront')->admin_id)->result();
					foreach ($lang as $key => $l_code) {
						$insertData_lang = array();
						$insertData_lang['other_event_cat_id'] = $category_id;
						$insertData_lang['language'] = $l_code->language_code;
						$insertData_lang['category_name'] = trim($this->input->post('categoryname'));
						$insertData_lang['category_desc'] = $this->input->post('category_description');

						$this->General_Model->insert_data('otherevent_category_lang', $insertData_lang);
					}

					$response = array('status' => 1, 'msg' => 'Category Created Successfully.', 'redirect_url' => base_url() . 'event/other_events_category');
					echo json_encode($response);
					exit;
				}
			} else {

				if ($this->input->post()) {
					$categoryId = $this->input->post('categoryId');
					$updateData = array();
					$updateData['parent_id'] = trim($this->input->post('parent'));
					$updateData['category_name'] = trim($this->input->post('categoryname'));
					$updateData['status'] = $this->input->post('is_active') ? 1 : 0;
					$updateData['sort'] = $this->input->post('sortno');
					$updateData['category_desc'] = $this->input->post('category_description');
					$updateData['create_date'] = strtotime(date('Y-m-d h:i:s'));
					$updateData['add_by'] = $this->session->userdata('admin_id');

					$this->General_Model->update('otherevent_category', array('id' => $categoryId), $updateData);

					$updateData_lang = array();
					$updateData_lang['category_name'] = trim($this->input->post('categoryname'));
					$updateData_lang['category_desc'] = $this->input->post('category_description');
					$this->General_Model->update('otherevent_category_lang', array('other_event_cat_id' => $category_id, 'language' => $this->session->userdata('language_code')), $updateData_lang);

					$response = array('status' => 1, 'msg' => 'Category updated Successfully.', 'redirect_url' => base_url() . 'event/other_events_category');
					echo json_encode($response);
				}
			}
		} else if ($event_segment == "delete_category") {

			$category_id   = $segment = $this->uri->segment(4);
			$delete     = $this->General_Model->delete_other_events_category($category_id);

			if ($delete == 1) {
				$response = array('status' => 1, 'msg' => 'Category deleted Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error While Deleting Category.');
				echo json_encode($response);
				exit;
			}
		} else {
			$row_count   = $this->uri->segment(3);
			if ($this->input->post('submit') != NULL) {
				$search_text = $this->input->post('search');
				$this->session->set_userdata(array("searchotherevecat" => $search_text));
			} else {
				if ($this->session->userdata('searchotherevecat') != NULL) {
					$search_text = $this->session->userdata('searchotherevecat');
				}
			}
			$this->loadOtherEventsCategoriesRecord($row_count, 'id', 'DESC', 'event/other_events_categories', 'other_events_categories',$search_text);
		}
	}

	public function loadOtherEventsCategoriesRecord($rowno = 0, $order_column, $order_by, $view, $variable_name,$search)
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

		$variable_name = "other_events_categories";

		$url = "event/other_events_category";
		// All records count
		$allcount = $this->General_Model->get_other_events_categories('','','','','',$search)->num_rows();

		// Get records
		$record = $this->General_Model->get_other_events_categories($rowno, $row_per_page, $order_column, $order_by,'',$search)->result();

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

	public function other_events()
	{
		$event_segment  = $segment = $this->uri->segment(3);
		$match_id       = json_decode(base64_decode($this->uri->segment(4)));

		if ($event_segment == "add_event") {
			$this->data['categories'] = $this->General_Model->get_other_events_categories()->result();
			$this->data['stadiums']     = $this->General_Model->get_stadium()->result();
			$this->data['countries']    = $this->General_Model->getAllItemTable('countries')->result();
			if ($match_id != '') {
				// echo "MATCHID:".$match_id;exit;
				$this->data['event']      = $this->General_Model->getOtherEvents('', '', $row_no = '', $row_per_page = '', $orderColumn = '', $orderby = '', array('match_info.m_id' => $match_id))->row();
				// echo "<pre>"; print_r($this->data['event']);exit;
			}
			$this->load->view('event/add_other_event', $this->data);
		}
		else if ($event_segment == "add_event_content") {

			$this->data['categories'] = $this->General_Model->get_other_events_categories()->result();
			$this->data['stadiums']     = $this->General_Model->get_stadium()->result();
			$this->data['countries']    = $this->General_Model->getAllItemTable('countries')->result();
			if ($match_id != '') {
				// echo "MATCHID:".$match_id;exit;
				$this->data['event']      = $this->General_Model->getOtherEvents('', '', $row_no = '', $row_per_page = '', $orderColumn = '', $orderby = '', array('match_info.m_id' => $match_id))->row();
				// echo "<pre>"; print_r($this->data['event']);exit;
			}
			$this->load->view('event/add_event_content', $this->data);
		} else if ($event_segment == "save_events") {
			$matchId = $this->input->post('matchId');

			if ($matchId == '') {
				$msg = '';
				if ($this->input->post()) {

					if (!empty($_FILES['event_image']['name'])) {
						$this->form_validation->set_rules('event_image', 'Image file', 'callback_image_file_check');
					}
					if ($this->form_validation->run() !== false) {
						$insertData = array();						

						if (!empty($_FILES['event_image']['name'])) {
							$config['upload_path'] = 'uploads/event_image';
							$config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF|';
							$config['max_size'] = '10000';
							$config['encrypt_name'] = TRUE;
							$this->load->library('upload', $config);
							$this->upload->initialize($config);
							if ($this->upload->do_upload('event_image')) {
								$outputData['event_image'] = $this->upload->data();
								$insertData['event_image'] = $outputData['event_image']['file_name'];
							} else {
								$msg .= 'Failed to add event image';
							}
						}

						$insertData['other_event_category'] = $this->input->post('category');
						$insertData['match_name'] = trim($this->input->post('eventname'));
						$insertData['status'] = $this->input->post('is_active') ? 1 : 0;
						$insertData['availability'] = $this->input->post('availability') ? 1 : 0;
						$insertData['privatelink'] = $this->input->post('privatelink') ? '1' : '0';
						$insertData['apishare'] = $this->input->post('apishare') ? '1' : '0';
						$insertData['meta_title'] = trim($this->input->post('metatitle'));
						$insertData['meta_description'] = trim($this->input->post('metadescription'));
						$insertData['description'] = trim($this->input->post('description'));

						$insertData['match_date'] = date('Y-m-d H:i:s', strtotime($this->input->post('matchdate') . ' ' . $this->input->post('matchtime')));
						$insertData['match_time'] = $this->input->post('matchtime');
						$insertData['country'] = $this->input->post('country');
						$insertData['city'] = $this->input->post('city');
						$insertData['venue'] = $this->input->post('venue');
						$insertData['slug'] = trim($this->input->post('categoryname'));
						$insertData['event_type'] = 'other';
						$insertData['create_date'] = strtotime(date('Y-m-d h:i:s'));
						$insertData['add_by'] = $this->session->userdata('admin_id');
						if ($this->input->post('event_url')) {
							$title = strip_tags($this->input->post('event_url'));
						}
						$titleURL = strtolower(url_title($title));
						$duplicateCheck = $this->General_Model->getid('match_info', array('slug' => $titleURL))->result();
						if (count($duplicateCheck) > 0) {
							if($update_url == 1){

						}
						else{
						$response = array('status' => 0, 'msg' => 'URL Exists.Please use different one.', 'redirect_url' => base_url() . 'event/matches/upcoming');
						echo json_encode($response);
						exit;
						}
						}
						$insertData['slug'] = $titleURL;

						$match_id = $this->General_Model->insert_data('match_info', $insertData);

						$lang = $this->General_Model->getAllItemTable('language','store_id',$this->session->userdata('storefront')->admin_id)->result();
						foreach ($lang as $key => $l_code) {
							$insertData_lang = array();
							$insertData_lang['match_id'] = $match_id;
							$insertData_lang['language'] = $l_code->language_code;
							$insertData_lang['match_name'] = trim($this->input->post('eventname'));
							$insertData_lang['meta_title'] = $this->input->post('metatitle');
							$insertData_lang['meta_description'] = $this->input->post('metadescription');
							$insertData_lang['event_image'] =	$insertData['event_image'];
							$this->General_Model->insert_data('match_info_lang', $insertData_lang);
						}

						$response = array('status' => 1, 'msg' => 'Other Event Created Successfully.', 'redirect_url' => base_url() . 'event/other_events/upcoming');
						echo json_encode($response);
						exit;
					}
				}
			} else {

				if ($this->input->post()) {
					$this->form_validation->set_rules('eventname', 'Event name', 'required');
					/*if($_POST['flag'] != "content"){
					if (!empty($_FILES['event_image']['name'])) {
						$this->form_validation->set_rules('event_image', 'Image file', 'callback_image_file_check');
					}
					}
					if($_POST['flag'] == "content"){
						$this->form_validation->set_rules('eventname', 'Event name', 'required');
					}*/
					//echo "<pre>";print_r($_POST);exit;
					if ($this->form_validation->run() !== false) {
						$msg='';
						$matchId = $this->input->post('matchId');
						$updateData = array();

						if($_POST['flag'] != "content"){

						if (!empty($_FILES['event_image']['name'])) {
							$mdata = $this->General_Model->getAllItemTable_array('match_info', array('m_id' => $matchId))->row();
						
							if (@getimagesize(base_url() . './uploads/event_image/' . $mdata->event_image)) {
								unlink('./uploads/event_image/' . $mdata->event_image);
							}
							$config['upload_path'] = 'uploads/event_image';
							$config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF|';
							$config['max_size'] = '1000';
							$config['encrypt_name'] = TRUE;
							$this->load->library('upload', $config);
							$this->upload->initialize($config);
							if (!$this->upload->do_upload('event_image')) {
								$msg .= 'Failed to add event image';
							} else {
								$data = $this->upload->data();
								$imagename = $data['file_name'];
								$updateData_lang['event_image'] = $imagename;
								$updateData['event_image'] = $imagename;
							}
						} else {
							$updateData_lang['event_image'] = $this->input->post('exs_file');
						}
					
						$updateData['other_event_category'] = $this->input->post('category');
						$updateData['match_name'] = trim($this->input->post('eventname'));
						$updateData['status'] = $this->input->post('is_active') ? 1 : 0;
						$updateData['availability'] = $this->input->post('availability') ? 1 : 0;
						$updateData['privatelink'] =$this->input->post('privatelink') ? '1' : '0';
						$updateData['apishare'] = $this->input->post('apishare') ? '1' : '0';
						

						$updateData['match_date'] = date('Y-m-d H:i:s', strtotime($this->input->post('matchdate') . ' ' . $this->input->post('matchtime')));
						$updateData['match_time'] = $this->input->post('matchtime');
						$updateData['country'] = $this->input->post('country');
						$updateData['city'] = $this->input->post('city');
						$updateData['venue'] = $this->input->post('venue');
						$updateData['slug'] = trim($this->input->post('categoryname'));
						$updateData['event_type'] = 'other';
						$updateData['create_date'] = strtotime(date('Y-m-d h:i:s'));
						$updateData['add_by'] = $this->session->userdata('admin_id');
						if ($this->input->post('event_url')) {
							$title = strip_tags($this->input->post('event_url'));
						}
						$titleURL = strtolower(url_title($title));
						$duplicateCheck = $this->General_Model->getid('match_info', array('slug' => $titleURL, 'matchid_not' => $matchId))->result();
						if (count($duplicateCheck) > 0) {
							if($update_url == 1){

						}
						else{
						$response = array('status' => 0, 'msg' => 'URL Exists.Please use different one.', 'redirect_url' => base_url() . 'event/matches/upcoming');
						echo json_encode($response);
						exit;
						}
						}
						$updateData['slug'] = $titleURL;

						$this->General_Model->update('match_info', array('m_id' => $matchId), $updateData);
						}
						else{
						/*$updateData['meta_title'] = trim($this->input->post('metatitle'));
						$updateData['meta_description'] = trim($this->input->post('metadescription'));
						$updateData['description'] = trim($this->input->post('description'));*/
						$updateData['seo_keywords'] = trim($this->input->post('seo_keywords'));
						$this->General_Model->update('match_info', array('m_id' => $matchId), $updateData);

						$updateData_lang = array();

						$updateData_lang['match_id'] = $matchId;
						$updateData_lang['match_name'] = trim($this->input->post('eventname'));
						$updateData_lang['meta_title'] = trim($this->input->post('metatitle'));
						$updateData_lang['meta_description'] = trim($this->input->post('metadescription'));
						$updateData_lang['description'] = trim($this->input->post('description'));
						//echo $matchId;
						//echo "<pre>";print_r($updateData_lang);exit;
						$this->General_Model->update('match_info_lang', array('match_id' => $matchId, 'language' => $this->session->userdata('language_code')), $updateData_lang);

					}

						
						$response = array('status' => 1, 'msg' => 'Other Event updated Successfully.'.$msg, 'redirect_url' => base_url() . 'event/other_events/upcoming');
						echo json_encode($response);
						exit;
					}
				}
			}
		} else if ($event_segment == "delete_event") {

			$event_id   = $segment = $this->uri->segment(4);
			$delete     = $this->General_Model->delete_other_events($event_id);

			if ($delete == 1) {
				$response = array('status' => 1, 'msg' => 'Other Event deleted Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error While Deleting Other Event.');
				echo json_encode($response);
				exit;
			}
		} else {
			$row_count   = $this->uri->segment(4);
			if ($this->input->post('submit') != NULL) {
				$search_text = $this->input->post('search');
				$this->session->set_userdata(array("searchotherevents" => $search_text));
			} else {
				if ($this->session->userdata('searchotherevents') != NULL) {
					$search_text = $this->session->userdata('searchotherevents');
				}
			}
			$status_flag = $this->uri->segment(3);
			$this->data['status_flag']     = $status_flag;
			$this->loadOtherEventsRecord($status_flag, $row_count, 'm_id', 'DESC', 'event/other_events', 'other_events',$search_text);
		}
	}

	public function loadOtherEventsRecord($status_flag, $rowno = 0, $order_column, $order_by, $view, $variable_name,$search='')
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

		$variable_name = "other_events";

		if ($status_flag == "upcoming") {
			$url = "event/other_events/upcoming";
			// All records count
			//$allcount = $this->General_Model->get_matches('','upcoming')->num_rows();
			$allcount = $this->General_Model->getOtherEvents('', 'upcoming','','','','','',$search)->num_rows();
			// Get records
			$record = $this->General_Model->getOtherEvents('', 'upcoming', $rowno, $row_per_page, $order_column, $order_by,'',$search)->result();
		} else if ($status_flag == "expired") {
			$url = "event/other_events/expired";

			$this->data['expired_other_events'] = $this->General_Model->getOtherEvents('', 'expired','','','','','',$search)->result();
			// All records count
			$allcount = $this->General_Model->getOtherEvents('', 'expired','','','','','',$search)->num_rows();


			// Get records
			$record = $this->General_Model->getOtherEvents('', 'expired', $rowno, $row_per_page, $order_column, $order_by,'',$search)->result();
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
	public function image_file_check()
	{
		$allowed_mime_types = array('image/jpeg', 'image/svg+xml', 'image/png', 'image/gif');
		if (isset($_FILES['event_image']['name']) && $_FILES['event_image']['name'] != "") {
			$mime = get_mime_by_extension($_FILES['event_image']['name']);
			$fileAr = explode('.', $_FILES['event_image']['name']);
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
}
