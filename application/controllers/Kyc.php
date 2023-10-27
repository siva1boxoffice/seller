<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);
class Kyc extends CI_Controller
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

 public function file_check($str){
       /* $allowed_mime_type_arr = array('application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/xlsx','application/xls','application/ods','application/docs','application/pdf','image/gif','image/jpeg','image/pjpeg','image/png','image/x-png');*/
        $allowed_mime_type_arr = array('application/pdf','image/gif','image/jpeg','image/pjpeg','image/png','image/x-png');
        $mime = get_mime_by_extension($_FILES['file']['name']);
        if(isset($_FILES['file']['name']) && $_FILES['file']['name']!=""){
            if(in_array($mime, $allowed_mime_type_arr)){
                return true;
            }else{
                $this->form_validation->set_message('file_check', 'Please select only pdf/gif/jpg/png file.');
                return false;
            }
        }else{
            $this->form_validation->set_message('file_check', 'Please choose a file to upload.');
            return false;
        }
    }

	public function documents()
	{  
		$this->datas['documents'] = $this->General_Model->get_document_info()->row();
		$this->load->view(THEME_NAME.'/kyc/documents/documents', $this->datas);
	}

	public function view_documents()
	{  

$file = 'uploads/seller_documents/seller_documents.docx';

header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: inline; filename="' . basename($file) . '"');
header('Content-Length: ' . filesize($file));

readfile($file);
	}

	public function send_document_notification($seller_id,$document_type){

		if($seller_id != ""){

					$post_data = array("seller_id" => $seller_id,'document_type' => $document_type);
					$handle = curl_init();
					$url = API_CRON_URL.'seller_document_notification';//echo $url;exit;
					curl_setopt($handle, CURLOPT_HTTPHEADER, array(
					'domainkey: https://www.1boxoffice.com/en/'
					));
					curl_setopt($handle, CURLOPT_URL, $url);
					curl_setopt($handle, CURLOPT_POST, 1);
					curl_setopt($handle, CURLOPT_POSTFIELDS,$post_data);
					curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
					$output = curl_exec($handle);
					curl_close($handle);
					return true;
			}

	}

	public function upload_contract()
	{ 
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('company_name', 'Company Name', 'required');
		$this->form_validation->set_rules('designation', 'Position In Company', 'required');
		$this->form_validation->set_rules('file', 'Contract Document', 'callback_file_check');
		if ($this->form_validation->run() !== false) {

			$check_exists = $this->General_Model->getAllItemTable_array('seller_documents', array("seller_id" => $this->session->userdata('admin_id')))->row();
			

			$insertData['name'] 	= trim($_POST['name']);
			$insertData['company_name'] 	= trim($_POST['company_name']);
			$insertData['designation'] 	= trim($_POST['designation']);
			$image = time().'-'.$_FILES["file"]['name'];
			if (!empty($_FILES['file']['name'])) {
					$config['file_name']   = $image;
					$config['upload_path'] = 'uploads/seller_documents';
					$config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF|pdf';
					//$config['max_size'] = '10000';
					$config['encrypt_name'] = TRUE;
					$this->load->library('upload', $config);
					if ($this->upload->do_upload('file')) {
						$outputData['contract_document'] = $this->upload->data();
						$insertData['contract_document'] = $outputData['contract_document']['file_name'];
					} else { //echo $this->upload->display_errors();exit;
						$insertData['contract_document'] 	= '';
					}
				}
				
				if($insertData['contract_document'] != ""){
					$insertData['contract_status'] 		= 2;
				}

			if($check_exists->id == ""){
			$insertData['seller_id'] 		= $this->session->userdata('admin_id');

				$sellInsert = $this->General_Model->insert_data('seller_documents', $insertData);
			}
			else{

				if($insertData['contract_document'] != ""){
					$insertData['contract_status'] 		= 2;
				}
				$this->General_Model->update('seller_documents', array('seller_id' => $this->session->userdata('admin_id')), $insertData);

			}

			$this->send_document_notification($this->session->userdata('admin_id'),'contract');

			$response = array('status' => 1, 'msg' => 'Seller Agreement updated successfully.');
				echo json_encode($response);
				exit;

		}
		else{
		$response = array('status' => 0, 'msg' => validation_errors());	
		}
		echo json_encode($response);
		exit;
	}

	public function upload_document()
	{ //echo "<pre>";print_r($_POST);exit;
		 

		if($_POST['upload_type'] == "photo"){
			$fname = "photo";
			$this->form_validation->set_rules('file', 'Photo ID', 'callback_file_check');
		}
		else if($_POST['upload_type'] == "address"){
			$fname = "address";
			$this->form_validation->set_rules('file', 'Address Proof', 'callback_file_check');
		}
		else if($_POST['upload_type'] == "contract"){
			$fname = "contract";
			$this->form_validation->set_rules('file', 'Seller Contract', 'callback_file_check');
		}
		
		if ($this->form_validation->run() !== false) {

			$check_exists = $this->General_Model->getAllItemTable_array('seller_documents', array("seller_id" => $this->session->userdata('admin_id')))->row();
			$image = time().'-'.$_FILES["file"]['name'];

			if (!empty($_FILES['file']['name'])) {
					$config['file_name']   = $image;
					$config['upload_path'] = 'uploads/seller_documents';
					$config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF|pdf';
					//$config['max_size'] = '10000';
					$config['encrypt_name'] = TRUE;
					$this->load->library('upload', $config);
					if ($this->upload->do_upload('file')) {
						$outputData[$fname.'_document'] = $this->upload->data();
						$insertData[$fname.'_document'] = $outputData[$fname.'_document']['file_name'];
					} else {
						$insertData[$fname.'_document'] 	= '';
					}
				}

				if($insertData[$fname.'_document'] != ""){
					$insertData[$fname.'_status'] 		= 2;
				}

			if($check_exists->id == ""){
			$insertData['seller_id'] 		= $this->session->userdata('admin_id');

				$sellInsert = $this->General_Model->insert_data('seller_documents', $insertData);
			}
			else{

				$updateData[$fname.'_document'] 		= $insertData[$fname.'_document'];
				if($insertData[$fname.'_document'] != ""){
					$updateData[$fname.'_status'] 		= 2;
				}
				$this->General_Model->update('seller_documents', array('seller_id' => $this->session->userdata('admin_id')), $updateData);

			}

			$this->send_document_notification($this->session->userdata('admin_id'),$fname);

			$response = array('status' => 1, 'msg' => 'Seller '.ucfirst($fname).' Document updated successfully.');
				echo json_encode($response);
				exit;

		}
		else{
		$response = array('status' => 0, 'msg' => validation_errors());	
		}
		echo json_encode($response);
		exit;
	
	}


}
