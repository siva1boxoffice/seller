<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Chats extends CI_Controller
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
				// if (!$this->Privilege_Model->get_allowed_pages($sub_admin_id, $controller_name, $function_name) && !$this->Privilege_Model->get_privileges_by_sub_admin_id($sub_admin_id, $controller_name, $function_name)) {
				// 	redirect(base_url() . 'access/error_denied', 'refresh');
				// }
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


	public function users()
	{
		$segment  = $segment = $this->uri->segment(3);
		

		if ($segment == "lists") {
			$row_count = $this->uri->segment(5);
			$flag = $this->uri->segment(4);

			$this->loadRecord($row_count, 'chats', 'chats/list_chats/'.$flag, 'id', 'DESC', THEME_NAME.'/chats/list_chats', 'chats_list', 'lists',$_POST);
		}

		else if ($segment == "ajax_list") {
			$row_count = $this->uri->segment(5);
			$flag = $this->uri->segment(4);
			$this->loadRecord($row_count, 'chats', 'chats/ajax_list_chats/'.$flag, 'id', 'DESC', THEME_NAME.'/chats/ajax_list_chats', 'chats_list', 'ajax_list_chats',$_POST);


		}

		else if ($segment == "save_chats") {
				$message = $this->input->post('message'); 
			        $booking_id = $this->input->post('message_id');  
			        $user_id = $this->input->post('user_id');  
			        $message = $this->input->post('message');  
			        $send_by = 2;
			        $data = array(
                        'booking_id' => $booking_id,
                        'send_by'    => $send_by,
                        'message'    => $message,
                        'user_id'    => $user_id,
                        'status'	 => 0,
                        'created_at' => date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s") 
                    );
               $insert_data =  $this->db->insert('chats',$data);
                if($insert_data){
		            $response['result']=['message' => "Saved Successfully.",'status' => 1];
		            echo json_encode($response);
		        }   
		        else{
		            $response['result']= ["message" => "Insert Failed",'status' => 0];
		            echo json_encode($response);
		        } 
		}



		else{
			echo "no segments";
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

		$this->load->model("Chats_Model");
		// All records count
		$allcount = $this->General_Model->get_table_row_count($table, '');
		$admin_id = $this->session->userdata('admin_id');
		$ajax_list = array();
		if($type == 'ajax_list_chats'){
			$booking_id = $_GET['booking_id'];	
			$status = $_GET['status'];	
			$record = $this->Chats_Model->get_chat_list($admin_id,$status,$booking_id);
			if($status == 2){
				$ajax_list = $this->Chats_Model->get_chat_list_ajax($admin_id);
			}
			//echo "<pre>";print_r($record);die;
		}
		else{
			$record = $this->Chats_Model->get_chat_list($admin_id);
		}
			
		// 	// Get records
		// $record = $this->General_Model->get_limit_based_data($table, $rowno, $row_per_page, $order_column, $order_by, '')->result();

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
		if($type == 'ajax_list_chats'){
			echo json_encode( array('message' => $record,'ajax_list' => $ajax_list));
		}
		else{
			$this->load->view($view, $this->data);
		}

	}

}
