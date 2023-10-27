<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);
class Home extends CI_Controller
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
		//echo "<pre>";print_r($this->session->userdata('storefront'));exit;
		//echo $this->session->userdata('storefront')->company_name;exit;
		if ($this->session->userdata('storefront')->company_name == '') {
				$branches = $this->General_Model->get_admin_details(13);
				//echo "<pre>";print_r($branches);exit;
				$sessionUserInfo = array('storefront' => $branches);
				$this->session->set_userdata($sessionUserInfo);
			/*$sessionUserInfo = array('storefront' => $this->data['branches'][count($this->data['branches']) - 1]);*/
		}
		return $this->data;
	}

	public function index()
	{ 
		$this->data['getMySalesData'] = $this->General_Model->getOrders('','all','',0,10)->result();
		$this->data['orders'] = $this->General_Model->getOrders('','all_confirmed')->num_rows();

		$this->data['confirmed_orders'] = $this->General_Model->getOrders('','all_confirmed')->num_rows();//echo "AAAAAAA";exit;
		$this->data['confirmed_sales_gbp'] = $this->General_Model->confirmed_sales('GBP');
		$this->data['confirmed_sales_eur'] = $this->General_Model->confirmed_sales('EUR');
		$this->data['confirmed_sales_usd'] = $this->General_Model->confirmed_sales('USD');
		$this->data['confirmed_sales_aed'] = $this->General_Model->confirmed_sales('AED');
		$this->data['abondaned'] = $this->General_Model->abondaned()->num_rows();
		$total_quantity = $this->General_Model->sell_ticket_count();
		//echo "<pre>";print_r($total_quantity);exit;
		 $total_qty = array_sum(array_column($total_quantity,'total_quantity'));
		$this->data['listed_tickets'] = $total_qty;
		$this->load->view(THEME_NAME.'/home', $this->data);
	}
	
	public function update_block()
	{
		$blocks = $this->General_Model->getAllItemTable('stadium_details', '','', 'id', 'DESC')->result();
		foreach($blocks as $block){
			$block_id = $block->block_id;
			$block_data = explode('-',$block_id);
			if($block_data[0] != '' && $block_data[1] != ''){
				$uvalue = array('block_id' => $block_data[1]);
				$this->General_Model->update_table('stadium_details', 'id', $block->id, $uvalue);
				//echo "<pre>";print_r($block_data);exit;
			}
			
		}
		
	}

	public function branch()
	{

		$segment = $this->uri->segment(3);
		if ($segment == 'add_branch') {
			$segment4 = $this->uri->segment(4);
			if ($segment4 != "") {
				$edit_id = json_decode(base64_decode($segment4));
				$this->data['branch'] = $this->General_Model->getAllItemTable('branches', 'branch_id', $edit_id, 'branch_id', 'DESC')->row();
			}
			$this->load->view(THEME_NAME.'/users/add_branch', $this->data);
		} else if ($segment == 'list_branch') {
			$this->data['branches'] = $this->General_Model->getAllItemTable('branches', '', '', 'branch_id', 'DESC')->result();
			$this->load->view(THEME_NAME.'/users/branch', $this->data);
		} else if ($segment == 'delete_branch') {
			$segment4 = $this->uri->segment(4);
			$delete_id = $segment4;
			$delete = $this->General_Model->delete_data('branches', 'branch_id', $delete_id);
			if ($delete == 1) {
				$response = array('status' => 1, 'msg' => 'Branch data deleted Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error While Deleting Branch data.');
				echo json_encode($response);
				exit;
			}
		} else if ($segment == 'save_branch') {
			$this->form_validation->set_rules('branch_name', 'Branch Name', 'required');
			if ($this->form_validation->run() !== false) {
				$insert_data = array('branch_name' => $_POST['branch_name'],);
				if ($_POST['status'] != '') {
					$insert_data['status'] = $_POST['status'];
				}
				$branch_id = $_POST['branch_id'];
				if ($branch_id == '') {
					if ($this->General_Model->insert_data('branches', $insert_data)) {
						$response = array('msg' => 'New Branch Created Successfully.', 'redirect_url' => base_url() . 'home/branch/list_branch', 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to create new Branch.', 'redirect_url' => base_url() . 'home/branch/add_branch', 'status' => 0);
					}
					echo json_encode($response);
					exit;
				} else {
					if ($this->General_Model->update_table('branches', 'branch_id', $branch_id, $insert_data)) {
						$response = array('msg' => 'Branch details updated Successfully.', 'redirect_url' => base_url() . 'home/branch/list_branch', 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to update Branch details.', 'redirect_url' => base_url() . 'home/branch/add_branch/' . $branch_id, 'status' => 0);
					}
					echo json_encode($response);
					exit;
				}
			} else {
				$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'home/branch/add_branch', 'status' => 0);
			}
			echo json_encode($response);
			exit;
		}
	}

	public function profile()
	{

		$segment = $this->uri->segment(3);
		if ($segment == "bankaccounts") {
			$this->load->view(THEME_NAME.'/profile/bank_accounts', $this->data);
		}
		if ($segment == 'edit_profile') {
			$this->load->view(THEME_NAME.'/profile/edit_admin_profile', $this->data);
		}
		if ($segment == 'manage_profile') {
			$this->data['country_lists'] = $this->General_Model->fetch_country_list();
			$admin_id = $this->session->userdata('admin_id');
			$this->data['admin_profile_info'] = $this->General_Model->get_admin_details($admin_id);
			$this->data['flag'] = $this->uri->segment(4);
			$this->load->view(THEME_NAME.'/profile/manage_profile', $this->data);
		}
		if ($segment == 'update_profile') {
			$this->load->library('form_validation');
			if ($_POST['flag'] == 1) {
				$this->form_validation->set_rules('first_name', 'First Name', 'required');
				$this->form_validation->set_rules('last_name', 'Last Name', 'required');
				$this->form_validation->set_rules('company_name', 'Company Name', 'required');
				$this->form_validation->set_rules('company_url', 'Company Website Url', 'required');
			}
			if ($_POST['flag'] == 2) {
				$this->form_validation->set_rules('address_details_id', 'Address Id', 'required');
				$this->form_validation->set_rules('country', 'Country', 'required');
				$this->form_validation->set_rules('state', 'State', 'required');
				$this->form_validation->set_rules('city', 'City', 'required');
				$this->form_validation->set_rules('zip_code', 'Postal Code', 'required');
				$this->form_validation->set_rules('address', 'Address', 'required');
			}
			if ($_POST['flag'] == 3) {
				$this->form_validation->set_rules('password', 'Password', 'required');
				$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|matches[password]');
			}
			if ($_POST['flag'] == 4) {
				$this->form_validation->set_rules('beneficiary_name', 'Beneficiary Name', 'required');
				$this->form_validation->set_rules('bank_name', 'Bank Name', 'required');
				$this->form_validation->set_rules('iban_number', 'Iban Number', 'required');
				$this->form_validation->set_rules('beneficiary_address', 'Beneficiary Address', 'required');
				$this->form_validation->set_rules('bank_address', 'Bank Address', 'required');
				$this->form_validation->set_rules('account_number', 'Account Number', 'required');
				$this->form_validation->set_rules('swift_code', 'Swift Code', 'required');
			}
			if ($this->form_validation->run() !== false) {
				if ($_POST['flag'] == 1) {
					if (isset($_FILES["profile_filepond"]["name"]) && $_FILES["profile_filepond"]["name"] != '') {
						$logo_image = explode(".", $_FILES["profile_filepond"]["name"]);
						$newlogoname = date('YmdHis') . rand(1, 9999999) . '.' . end($logo_image);
						$tmpnamert = $_FILES['profile_filepond']['tmp_name'];
						move_uploaded_file($tmpnamert, 'uploads/users/' . $newlogoname);
						$admin_profile_pic = base_url() . 'uploads/users/' . $newlogoname;
					} else {
						$admin_id = $this->session->userdata('admin_id');
						$admin_lists = $this->General_Model->get_admin_details($admin_id);
						$admin_profile_pic = $admin_lists->admin_profile_pic;
					}
					$update_information = array('admin_name' => $_POST['first_name'], 'admin_last_name' => $_POST['last_name'], 'company_name' => $_POST['company_name'], 'company_url' => $_POST['company_url']);
					$update_information['admin_profile_pic'] = $admin_profile_pic;
					if ($this->General_Model->update_admin_details($update_information, $this->session->userdata('admin_id'))) {
						$response = array('msg' => 'Admin details updated successfully.', 'redirect_url' => base_url() . 'home/profile/manage_profile/' . $_POST['flag'], 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to update admin details.', 'redirect_url' => base_url() . 'home/profile/manage_profile/' . $_POST['flag'], 'status' => 0);
					}
					echo json_encode($response);
					exit;
				}
				if ($_POST['flag'] == 2) {
					$address_details_id = $_POST['address_details_id'];
					$address_information = array('country' => $_POST['country'], 'state' => $_POST['state'], 'city' => $_POST['city'], 'zip_code' => $_POST['zip_code'], 'address' => $_POST['address'],); // echo "<pre>";print_r($address_information);exit;
					if ($this->General_Model->update_admin_address($address_information, $address_details_id)) {
						$response = array('msg' => 'Address details updated successfully.', 'redirect_url' => base_url() . 'home/profile/manage_profile/' . $_POST['flag'], 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to update admin address.', 'redirect_url' => base_url() . 'home/profile/manage_profile/' . $_POST['flag'], 'status' => 0);
					}
					echo json_encode($response);
					exit;
				}
				if ($_POST['flag'] == 3) {
					$new_password = $this->input->post('password');
					if ($this->General_Model->update_admin_password($new_password, $this->session->userdata('admin_id'))) {
						$response = array('msg' => 'Password updated successfully.', 'redirect_url' => base_url() . 'home/profile/manage_profile/' . $_POST['flag'], 'status' => 1);
					} else {
						$response = array('msg' => 'Password updation Failed.', 'redirect_url' => base_url() . 'home/profile/manage_profile/' . $_POST['flag'], 'status' => 0);
					}
				} else {
					$response = array('msg' => 'Invalid Old Password.', 'redirect_url' => base_url() . 'home/profile/manage_profile/' . $_POST['flag'], 'status' => 0);
				}
				if ($_POST['flag'] == 4) {
					$bank_information = array('beneficiary_name' => $_POST['beneficiary_name'], 'bank_name' => $_POST['bank_name'], 'iban_number' => $_POST['iban_number'], 'beneficiary_address' => $_POST['beneficiary_address'], 'bank_address' => $_POST['bank_address'], 'account_number' => $_POST['account_number'], 'swift_code' => $_POST['swift_code'],);
					//  echo "<pre>";print_r($_POST);exit;
					if ($this->General_Model->update_table('admin_bank_details', 'admin_id', $this->session->userdata('admin_id'), $bank_information)) {
						$response = array('msg' => 'Bank details updated successfully.', 'redirect_url' => base_url() . 'home/profile/manage_profile/' . $_POST['flag'], 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to update Bank details.', 'redirect_url' => base_url() . 'home/profile/manage_profile/' . $_POST['flag'], 'status' => 0);
					}
				}
			} else {
				$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'home/profile/manage_profile/' . $_POST['flag'], 'status' => 0);
			}
			echo json_encode($response);
			exit;
		}
	}

	public function myaccounts(){

		$segment = $this->uri->segment(3);
		
			$admin_id = $this->session->userdata('admin_id');
			$this->data['user'] = $this->General_Model->get_admin_details($admin_id);
			$this->data['country_lists'] = $this->General_Model->fetch_country_list();
			$this->data['roles'] = $this->General_Model->getAllItemTable('admin_role', 'status', 'ACTIVE', 'admin_role_id', 'DESC')->result();
			$this->load->view(THEME_NAME.'/users/myaccount', $this->data);
		

	}

	public function save_my_accounts(){

				/*$new_password = $this->input->post('password');
							$cpassword = $this->input->post('cpassword');
				echo $new_password.'='.$cpassword;exit;	*/		
				$this->form_validation->set_rules('first_name', 'First Name', 'required');
				$this->form_validation->set_rules('last_name', 'Last Name', 'required');
				$this->form_validation->set_rules('email', 'Email', 'required');
				$this->form_validation->set_rules('mobile_no', 'Mobile No.', 'required');
				$this->form_validation->set_rules('area_code', 'Area Code.', 'required');
				$this->form_validation->set_rules('company_name', 'Company Name', 'required');
				$this->form_validation->set_rules('company_url', 'Company Website Url', 'required');
		 		
		 		$this->form_validation->set_rules('country', 'Country', 'required');
				$this->form_validation->set_rules('state', 'State', 'required');
				$this->form_validation->set_rules('city', 'City', 'required');
				$this->form_validation->set_rules('zip_code', 'Zip Code', 'required');
				$this->form_validation->set_rules('address', 'Address', 'required');
				$this->form_validation->set_rules('currency', 'Currency', 'required');

				if($_POST['ignore_password_update'] != 1){
					$this->form_validation->set_rules('password', 'Password', 'required');
					$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|matches[password]');
				}
				

				$this->form_validation->set_rules('beneficiary_name', 'Beneficiary Name', 'required');
				$this->form_validation->set_rules('bank_name', 'Bank Name', 'required');
				$this->form_validation->set_rules('iban_number', 'Iban Number', 'required');
				$this->form_validation->set_rules('beneficiary_address', 'Beneficiary Address', 'required');
				$this->form_validation->set_rules('bank_address', 'Bank Address', 'required');
				$this->form_validation->set_rules('account_number', 'Account Number', 'required');
				$this->form_validation->set_rules('swift_code', 'Swift Code', 'required');
			
			if ($this->form_validation->run() !== false) {

				$admin_id = $this->session->userdata('admin_id');

			$update_information = array('admin_name' => $_POST['first_name'], 'admin_last_name' => $_POST['last_name'], 'admin_email' => $_POST['email'],'phone_code' => $_POST['area_code'], 'admin_cell_phone' => $_POST['mobile_no'], 'company_name' => $_POST['company_name'], 'company_url' => $_POST['company_url'],'currency' => $_POST['currency']);
					$update_information['admin_profile_pic'] = '';
					
					if ($admin_id != '') {
						
						try{

							$this->General_Model->update_admin_details($update_information, $admin_id);
							$address_details_id = $_POST['address_details_id'];
							$address_information = array('country' => $_POST['country'], 'state' => $_POST['state'], 'city' => $_POST['city'], 'zip_code' => $_POST['zip_code'], 'address' => $_POST['address']);
							$this->General_Model->update_admin_address($address_information, $address_details_id);
							if($_POST['ignore_password_update'] != 1){
							$new_password = $this->input->post('password');
							$cpassword = $this->input->post('cpassword');
							if($new_password == $cpassword){

							$this->General_Model->update_admin_password($new_password, $admin_id);

							}
							}
							
							$bank_information = array('admin_id' => $admin_id, 'beneficiary_name' => $_POST['beneficiary_name'], 'bank_name' => $_POST['bank_name'], 'iban_number' => $_POST['iban_number'], 'beneficiary_address' => $_POST['beneficiary_address'], 'bank_address' => $_POST['bank_address'], 'account_number' => $_POST['account_number'], 'swift_code' => $_POST['swift_code'],);

							$this->General_Model->update_table('admin_bank_details', 'admin_id', $admin_id, $bank_information);

								$response = array('msg' => 'Success.Profile details updated Successfully.', 'redirect_url' => base_url() . 'home/myaccounts', 'status' => 1);


						} catch(Exception $e) {
							$response = array('msg' => 'Failed to update Profile details.', 'redirect_url' => base_url() . 'home/myaccounts', 'status' => 0);
						}
						echo json_encode($response);
						exit;
					}

			}
			else { 
				$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'home/myaccounts', 'status' => 0);
			}
			echo json_encode($response);
			exit;
	}

	public function users()
	{

		$segment = $this->uri->segment(3);
		if ($segment == 'add_user') {
			$this->data['flag'] = $this->uri->segment(4);
			$segment5 = $this->uri->segment(5);
			$admin_id = json_decode(base64_decode($segment5));
			$this->data['user'] = $this->General_Model->get_admin_details($admin_id);
			$this->data['country_lists'] = $this->General_Model->fetch_country_list();
			$this->data['roles'] = $this->General_Model->getAllItemTable('admin_role', 'status', 'ACTIVE', 'admin_role_id', 'DESC')->result();
			$this->load->view(THEME_NAME.'/users/add_user', $this->data);
		} else if ($segment == 'delete_user') {
			$segment4 = $this->uri->segment(4);
			$delete = $this->General_Model->delete_multiple_data($segment4);
			if ($delete == 1) {
				$response = array('status' => 1, 'msg' => 'User data deleted Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 1, 'msg' => 'Error While Deleting User data.');
				echo json_encode($response);
				exit;
			}
		} else if ($segment == 'save_user') {
			
			if ($_POST['flag'] == 1) {
				$this->form_validation->set_rules('first_name', 'First Name', 'required');
				$this->form_validation->set_rules('last_name', 'Last Name', 'required');
				$this->form_validation->set_rules('email', 'Email', 'required');
				$this->form_validation->set_rules('mobile_no', 'Mobile No.', 'required');
				$this->form_validation->set_rules('company_name', 'Company Name', 'required');
				$this->form_validation->set_rules('company_url', 'Company Website Url', 'required');
				$this->form_validation->set_rules('role', 'Role', 'required');
			} else if ($_POST['flag'] == 2) {
				//echo "<pre>";print_r($_POST);exit;
				$this->form_validation->set_rules('country', 'Country', 'required');
				$this->form_validation->set_rules('state', 'State', 'required');
				$this->form_validation->set_rules('city', 'City', 'required');
				$this->form_validation->set_rules('zip_code', 'Zip Code', 'required');
				$this->form_validation->set_rules('address', 'Address', 'required');
			} else if ($_POST['flag'] == 3) {
				$this->form_validation->set_rules('password', 'Password', 'required');
				$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|matches[password]');
			} else if ($_POST['flag'] == 4) {
				$this->form_validation->set_rules('beneficiary_name', 'Beneficiary Name', 'required');
				$this->form_validation->set_rules('bank_name', 'Bank Name', 'required');
				$this->form_validation->set_rules('iban_number', 'Iban Number', 'required');
				$this->form_validation->set_rules('beneficiary_address', 'Beneficiary Address', 'required');
				$this->form_validation->set_rules('bank_address', 'Bank Address', 'required');
				$this->form_validation->set_rules('account_number', 'Account Number', 'required');
				$this->form_validation->set_rules('swift_code', 'Swift Code', 'required');
			}
			if ($this->form_validation->run() !== false) {
				if ($_POST['flag'] == 1) {
					if (isset($_FILES["profile_filepond"]["name"]) && $_FILES["profile_filepond"]["name"] != '') {
						$logo_image = explode(".", $_FILES["profile_filepond"]["name"]);
						$newlogoname = date('YmdHis') . rand(1, 9999999) . '.' . end($logo_image);
						$tmpnamert = $_FILES['profile_filepond']['tmp_name'];
						move_uploaded_file($tmpnamert, 'uploads/users/' . $newlogoname);
						$admin_profile_pic = base_url() . 'uploads/users/' . $newlogoname;
					} else {
						$admin_lists = $this->General_Model->get_admin_details($_POST['admin_id']);
						$admin_profile_pic = $admin_lists->admin_profile_pic;
					}
					$update_information = array('admin_name' => $_POST['first_name'], 'admin_last_name' => $_POST['last_name'], 'admin_email' => $_POST['email'], 'admin_cell_phone' => $_POST['mobile_no'], 'company_name' => $_POST['company_name'], 'company_url' => $_POST['company_url'],);
					$update_information['admin_profile_pic'] = $admin_profile_pic;
					$admin_id = $_POST['admin_id'];
					if ($admin_id != '') {
						//echo 'admin_id = '.$admin_id;echo "<pre>";print_r($update_information);exit;
						if ($_POST['role'] != '') {
							$update_role = array('admin_roles_id' => $_POST['role'],);
							if ($this->General_Model->update_table('admin_role_details', 'admin_id', $admin_id, $update_role)) {
								$role_flag = 1;
							}
						}
						if ($this->General_Model->update_admin_details($update_information, $admin_id) || $role_flag == 1) {
							$response = array('msg' => 'user details updated successfully.', 'redirect_url' => base_url() . 'home/users/add_user/' . $_POST['flag'] . '/' . base64_encode(json_encode($admin_id)), 'status' => 1);
						} else {
							$response = array('msg' => 'Failed to update user details.', 'redirect_url' => base_url() . 'home/users/add_user/' . $_POST['flag'] . '/' . base64_encode(json_encode($admin_id)), 'status' => 0);
						}
					} else {
						$admin_newid = $this->General_Model->insert_data('admin_details', $update_information);
						if ($admin_newid != '') {
							$role_information = array('admin_id' => $admin_newid, 'admin_roles_id' => $_POST['role'],);
							$role_id = $this->General_Model->insert_data('admin_role_details', $role_information);
							$address_information = array('country' => $_POST['country'], 'state' => $_POST['state'], 'city' => $_POST['city'], 'zip_code' => $_POST['zip_code'], 'address' => $_POST['address'],);
							$address_id = $this->General_Model->insert_data('address_details', $address_information);
							if ($address_id != '') {
								$update_information = array('address_details_id' => $address_id);
								if ($this->General_Model->update_admin_details($update_information, $admin_newid)) {
									$login_information = array('admin_id' => $admin_newid, 'admin_type_id' => $_POST['role'], 'admin_user_name' => $_POST['email'],);
									$login_id = $this->General_Model->insert_data('admin_login_details', $login_information);
									if ($login_id != '') {
										$bank_information = array('admin_id' => $admin_newid, 'beneficiary_name' => $_POST['beneficiary_name'], 'bank_name' => $_POST['bank_name'], 'iban_number' => $_POST['iban_number'], 'beneficiary_address' => $_POST['beneficiary_address'], 'bank_address' => $_POST['bank_address'], 'account_number' => $_POST['account_number'], 'swift_code' => $_POST['swift_code'],);
										$bank_id = $this->General_Model->insert_data('admin_bank_details', $bank_information);
										if ($bank_id != '') {
											$response = array('msg' => 'New user details created successfully.', 'redirect_url' => base_url() . 'home/users/add_user/' . $_POST['flag'] . '/' . base64_encode(json_encode($admin_newid)), 'status' => 1);
										}
									} else {
										$response = array('msg' => 'Failed to Create User details.', 'redirect_url' => base_url() . 'home/users/add_user/1', 'status' => 1);
									}
								}
							} else {
								$response = array('msg' => 'Failed to Create User details.', 'redirect_url' => base_url() . 'home/users/add_user/1', 'status' => 1);
							}
						} else {
							$response = array('msg' => 'Failed to Create User details.', 'redirect_url' => base_url() . 'home/users/add_user/1', 'status' => 1);
						}
					}
					echo json_encode($response);
					exit;
				}
				if ($_POST['flag'] == 2) {
					$address_details_id = $_POST['address_details_id'];
					$address_information = array('country' => $_POST['country'], 'state' => $_POST['state'], 'city' => $_POST['city'], 'zip_code' => $_POST['zip_code'], 'address' => $_POST['address'],); // echo "<pre>";print_r($address_information);exit;
					if ($this->General_Model->update_admin_address($address_information, $address_details_id)) {
						$response = array('msg' => 'Address details updated successfully.', 'redirect_url' => base_url() . 'home/users/add_user/' . $_POST['flag'] . '/' . base64_encode(json_encode($_POST['admin_id'])), 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to update admin address.', 'redirect_url' => base_url() . 'home/users/add_user/' . $_POST['flag'] . '/' . base64_encode(json_encode($_POST['admin_id'])), 'status' => 0);
					}
					echo json_encode($response);
					exit;
				}
				if ($_POST['flag'] == 3) {
					$new_password = $this->input->post('password');
					if ($this->General_Model->update_admin_password($new_password, $_POST['admin_id'])) {
						$response = array('msg' => 'Password updated successfully.', 'redirect_url' => base_url() . 'home/users/add_user/' . $_POST['flag'] . '/' . base64_encode(json_encode($_POST['admin_id'])), 'status' => 1);
					} else {
						$response = array('msg' => 'Password updation Failed.', 'redirect_url' => base_url() . 'home/users/add_user/' . $_POST['flag'] . '/' . base64_encode(json_encode($_POST['admin_id'])), 'status' => 0);
					}
				}
				if ($_POST['flag'] == 4) {
					$bank_information = array('beneficiary_name' => $_POST['beneficiary_name'], 'bank_name' => $_POST['bank_name'], 'iban_number' => $_POST['iban_number'], 'beneficiary_address' => $_POST['beneficiary_address'], 'bank_address' => $_POST['bank_address'], 'account_number' => $_POST['account_number'], 'swift_code' => $_POST['swift_code'],);
					//  echo "<pre>";print_r($_POST);exit;
					if ($this->General_Model->update_table('admin_bank_details', 'admin_id', $_POST['admin_id'], $bank_information)) {
						$response = array('msg' => 'Bank details updated successfully.', 'redirect_url' => base_url() . 'home/users/add_user/' . $_POST['flag'] . '/' . base64_encode(json_encode($_POST['admin_id'])), 'status' => 1);
					} else {
						$response = array('msg' => 'Failed to update Bank details.', 'redirect_url' => base_url() . 'home/users/add_user/' . $_POST['flag'] . '/' . base64_encode(json_encode($_POST['admin_id'])), 'status' => 0);
					}
				}
			} else {
				$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'home/users/add_user/' . $_POST['flag'] . '/' . base64_encode(json_encode($_POST['admin_id'])), 'status' => 0);
			}
			echo json_encode($response);
			exit;
		} else if ($segment == 'users') {

			$this->data['users'] = $this->General_Model->get_user_details()->result();
			$row_count = $this->uri->segment(4);
			$this->loadRecord($row_count, ' ', 'home/users/users', 'id', 'DESC', 'users/user_list', 'users', 'users', '');
			$this->load->view(THEME_NAME.'/users/user_list', $this->data);
		} else if ($segment == 'user_permissions') {
			$this->data['roles'] = $this->General_Model->getAllItemTable('admin_role', 'status', 'ACTIVE', 'admin_role_id', 'ASC')->result();
			$this->data['privilege_functions'] = $this->General_Model->get_privilege_functions();
			$active_function_id = $this->General_Model->get_privilege_active_functions();
			$function_ids = array();
			foreach ($active_function_id as $value) {
				$function_ids[$value["privilege_id"]][] = $value["privilege_functions_id"];
			}
			$this->data['active_functions'] = $function_ids;
			$this->load->view(THEME_NAME.'/users/user_permissions', $this->data);
			exit;
		} else if ($segment == 'save_permission') {
			$this->data = array();
			// for($i = 0;$i <= count($_POST['privilege']);$i++){
			$i = 0; //echo "<pre>";print_r($_POST['privilege']);exit;
			foreach ($_POST['privilege'] as $pkey => $pvalue) {
				$j = 0;
				foreach ($pvalue as $key => $value) {
					$this->data[$i]["privilege_id"] = $pkey;
					$this->data[$i]["privilege_functions_id"] = $value;
					$j++;
					$i++;
				}
			}
			$response = $this->General_Model->activate_functions($this->data);
			if ($response) {
				$messge = array('msg' => 'User Permissions Updated successfully.', 'redirect_url' => base_url() . 'home/users/user_permissions', 'status' => 1);
			} else {
				$messge = array('msg' => 'Failed to update User Permissions.', 'redirect_url' => base_url() . 'home/users/user_permissions',);
			}
			echo json_encode($messge);
			exit;
		} else if ($segment == 'user_roles') {

			$this->data['roles'] = $this->General_Model->get_user_roles();
			$this->load->view(THEME_NAME.'/users/user_roles', $this->data);
		}
	}
	public function master()
	{

		$segment = $this->uri->segment(3);
		if ($segment == 'get_state') {
			if ($_POST['country_id'] != '') {
				$this->mydata['states'] = $this->General_Model->getAllItemTable('states', 'country_id', $_POST['country_id'], 'id', 'DESC')->result();
				echo json_encode($this->mydata);
				exit;
			}
		} else if ($segment == 'get_city') {
			if ($_POST['state_id'] != '') {

				$this->mydata['cities'] = $this->General_Model->getAllItemTable('cities', 'state_id', $_POST['state_id'], 'id', 'DESC')->result();
				echo json_encode($this->mydata);
				exit;
			}
		} else if ($segment == 'set_language') {
			if ($_POST['language_code'] != '') {
				$sessionLanguageInfo = array('language_code' => $_POST['language_code']);
				$this->session->unset_userdata('language_code');
				$this->session->set_userdata($sessionLanguageInfo);
				$response = array('status' => 1, 'msg' => 'Language set Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 0, 'msg' => 'Language set failed.');
				echo json_encode($response);
				exit;
			}
		} else if ($segment == 'set_storefront') {

			if ($_POST['admin_id'] != '') {
				$branches = $this->General_Model->get_admin_details($_POST['admin_id']);
				$sessionUserInfo = array('storefront' => $branches);
				$this->session->unset_userdata('storefront');
				$this->session->set_userdata($sessionUserInfo);
				$response = array('status' => 1, 'msg' => 'Storefront Switched Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 0, 'msg' => 'Failed to Switch Storefront.');
				echo json_encode($response);
				exit;
			}
		}
	}

	// $this->loadRecord($row_count, ' ', 'home/users/users', 'id', 'DESC', 'users/user_list', 'users', $type);

	/**
	 * Fetch data and display based on the pagination request
	 */
	public function loadRecord($rowno = 0, $table, $url, $order_column, $order_by, $view, $variable_name, $type, $where_array)
	{

		// Load Pagination library
		$this->load->library('pagination');

		// Row per page
		$row_per_page = 10;

		// Row position
		if ($rowno != 0) {
			$rowno = ($rowno - 1) * $row_per_page;
		}
		if ($type == 'users') {
			// All records count
			$allcount =  $this->General_Model->get_user_details()->num_rows();

			// Get records
			$record = $this->General_Model->get_user_details_by_limit($rowno, $row_per_page)->result();
		} else {
			// All records count
			$allcount = $this->General_Model->get_table_row_count($table, '');

			// Get records
			$record = $this->General_Model->get_limit_based_data($table, $rowno, $row_per_page, $order_column, $order_by, $where_array)->result();
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

		// Load view
		$this->load->view($view, $this->data);
	}

	/**
	 * @desc Function used to display admin user login reports
	 */
	public function login_report()
	{

		$where_array = array('admin_id', $this->session->userdata('admin_id'));
		$this->loadRecord(0, 'admin_login_tracking_details', 'home/login_report', 'admin_login_tracking_details_id', 'DESC', 'profile/login_report', 'login_details', ' ', $where_array);
	}

	function geo_ip_settings()
	{
		$segment = $this->uri->segment(3);
		if ($segment == 'add') {
			$this->data['countries'] = $this->General_Model->getAllItemTable('countries')->result();

			$geoSettings = $this->General_Model->get_general_settings($this->session->userdata('storefront')->admin_id, '', 'GE')->result();
			if (isset($geoSettings)) {
				$mysettings = array();
				foreach ($geoSettings as $skey => $setting) {
					$mysettings[$setting->site_name] = $setting->site_value;
				}
			}
			$this->data['geoSettings'] = $mysettings;
			$this->load->view(THEME_NAME.'/settings/geo_ip_settings/geo_ip_settings', $this->data);
		} else if ($segment == 'save') {

			$this->form_validation->set_rules('ctype', 'Selection', 'required');
			if ($this->form_validation->run() !== false) {
				$admin_id  = $this->session->userdata('storefront')->admin_id;
				$country_ids = $this->input->post('gcountry');
				$county_ids = '';
				foreach ($country_ids as $val) {
					$county_ids .= $val;
					$county_ids .= ',';
				}
				$county_ids = rtrim($county_ids, ',');

				$insert_data = array(
					'COUNTRY_IDS' => $county_ids,
					'COUNTRY_SELECTION_TYPE' => $_POST['ctype'],
				);
				$datainset = array();
				foreach ($insert_data as $ikey => $idata) {
					$datainset[] = array('site_name' => $ikey, 'site_value' => $idata, 'store_id' => $admin_id, 'site_code' => 'GE', 'add_by' => $this->session->userdata('admin_id'));
				}

				if ($this->General_Model->update_site_settings($datainset, 'GE', $admin_id)) {
					$response = array('msg' => 'GEO IP Settings updated Successfully.', 'redirect_url' => base_url() . 'home/geo_ip_settings/add', 'status' => 1);
				} else {
					$response = array('msg' => 'Failed to update GEO IP Settings.', 'redirect_url' => base_url() . 'home/geo_ip_settings/add', 'status' => 0);
				}
				echo json_encode($response);
				exit;
			} else {
				$response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'home/geo_ip_settings/add', 'status' => 0);
			}
			echo json_encode($response);
			exit;
		}
	}
}
