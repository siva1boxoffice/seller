<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);
class Api extends CI_Controller {
    public function __construct() {
        /*
         *  Developed by: Sivakumar G
         *  Date    : 22 January, 2022
         *  1BoxOffice Hub
         *  https://www.1boxoffice.com/
        */
        parent::__construct();
        
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
        $this->load->model('Api_Model');
    }

    public function app_data() {

        $this->data['app_name'] = $this->app_name;
        $this->data['app_login_image'] = $this->app_login_image;
        $this->data['app_title'] = $this->app_title;
        $this->data['general_path'] = $this->general_path;
        $this->data['app_favicon'] = $this->app_favicon;
        $this->data['login_image'] = $this->login_image;
        $this->data['logo'] = $this->logo;
        $this->data['languages'] = $this->General_Model->getAllItemTable('language')->result();
        $this->data['branches'] = $this->General_Model->get_admin_details_by_role(4);
        if ($this->session->userdata('storefront')->company_name == '') {
                $branches = $this->General_Model->get_admin_details(13);
                $sessionUserInfo = array('storefront' => $branches);
                $this->session->set_userdata($sessionUserInfo);
            /*$sessionUserInfo = array('storefront' => $this->data['branches'][count($this->data['branches']) - 1]);*/
        }
        return $this->data;

    }

    public function api_partners() 
    {
        $segment = $this->uri->segment(3);
        if ($segment == 'add_partner') {
            $this->data['flag'] = $this->uri->segment(4);
            $segment5 = $this->uri->segment(5);
            $admin_id = json_decode(base64_decode($segment5));
            $this->data['user'] = $this->General_Model->get_admin_details($admin_id);
            $this->data['country_lists'] = $this->General_Model->fetch_country_list();
            $this->load->view('api/partners/add_partner', $this->data);
        }else if ($segment == 'delete_partner') {
            $segment4 = $this->uri->segment(4);
            $delete = $this->General_Model->delete_multiple_data($segment4);
            if ($delete == 1) {
                $response = array('status' => 1, 'msg' => 'Partner data deleted Successfully.');
                echo json_encode($response);
                exit;
            } else {
                $response = array('status' => 1, 'msg' => 'Error While Deleting Partner data.');
                echo json_encode($response);
                exit;
            }
        }else if ($segment == 'save_partner') {
            
            if ($_POST['flag'] == 1) {
                $this->form_validation->set_rules('first_name', 'First Name', 'required');
                $this->form_validation->set_rules('last_name', 'Last Name', 'required');
                $this->form_validation->set_rules('email', 'Email', 'required');
                $this->form_validation->set_rules('mobile_no', 'Mobile No.', 'required');
                $this->form_validation->set_rules('company_name', 'Company Name', 'required');
                $this->form_validation->set_rules('company_url', 'Company Website Url', 'required');
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
                        move_uploaded_file($tmpnamert, 'uploads/partners/' . $newlogoname);
                        $admin_profile_pic = base_url() . 'uploads/partners/' . $newlogoname;
                    } else {
                        $admin_lists = $this->General_Model->get_admin_details($_POST['admin_id']);
                        $admin_profile_pic = $admin_lists->admin_profile_pic;
                    }
                    $update_information = array('admin_name' => $_POST['first_name'], 'admin_last_name' => $_POST['last_name'], 'admin_email' => $_POST['email'], 'admin_cell_phone' => $_POST['mobile_no'], 'company_name' => $_POST['company_name'], 'company_url' => $_POST['company_url'],);
                    $update_information['admin_profile_pic'] = $admin_profile_pic;
                    $admin_id = $_POST['admin_id'];
                    //echo "<pre>"; print_r($update_information);die;
                    if ($admin_id != '') {
                        if ($this->General_Model->update_admin_details($update_information, $admin_id) || $role_flag == 1) {
                            $response = array('msg' => 'Partner details updated successfully.', 'redirect_url' => base_url() . 'api/api_partners/add_partner/' . $_POST['flag'] . '/' . base64_encode(json_encode($admin_id)), 'status' => 1);
                        } else {
                            $response = array('msg' => 'Failed to update Partner details.', 'redirect_url' => base_url() . 'api/api_partners/add_partner/' . $_POST['flag'] . '/' . base64_encode(json_encode($admin_id)), 'status' => 0);
                        }
                    } else {
                        $admin_newid = $this->General_Model->insert_data('admin_details', $update_information);
                        if ($admin_newid != '') {
                            $role_information = array('admin_id' => $admin_newid, 'admin_roles_id' => 2,);
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
                                            $response = array('msg' => 'New Partner details created successfully.', 'redirect_url' => base_url() . 'api/api_partners/add_partner/' . $_POST['flag'] . '/' . base64_encode(json_encode($admin_newid)), 'status' => 1);
                                        }
                                    } else {
                                        $response = array('msg' => 'Failed to Create User details.', 'redirect_url' => base_url() . 'api/api_partners/add_partner/1', 'status' => 1);
                                    }
                                }
                            } else {
                                $response = array('msg' => 'Failed to Create User details.', 'redirect_url' => base_url() . 'api/api_partners/add_partner/1', 'status' => 1);
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
                        $response = array('msg' => 'Address details updated successfully.', 'redirect_url' => base_url() . 'api/api_partners/add_partner/' . $_POST['flag'] . '/' . base64_encode(json_encode($_POST['admin_id'])), 'status' => 1);
                    } else {
                        $response = array('msg' => 'Failed to update partner address.', 'redirect_url' => base_url() . 'api/api_partners/add_partner/' . $_POST['flag'] . '/' . base64_encode(json_encode($_POST['admin_id'])), 'status' => 0);
                    }
                    echo json_encode($response);
                    exit;
                }
                if ($_POST['flag'] == 3) {
                    $new_password = $this->input->post('password');
                    if ($this->General_Model->update_admin_password($new_password, $_POST['admin_id'])) {
                        $response = array('msg' => 'Password updated successfully.', 'redirect_url' => base_url() . 'api/api_partners/add_partner/' . $_POST['flag'] . '/' . base64_encode(json_encode($_POST['admin_id'])), 'status' => 1);
                    } else {
                        $response = array('msg' => 'Password updation Failed.', 'redirect_url' => base_url() . 'api/api_partners/add_partner/' . $_POST['flag'] . '/' . base64_encode(json_encode($_POST['admin_id'])), 'status' => 0);
                    }
                }
                if ($_POST['flag'] == 4) {
                    $bank_information = array('beneficiary_name' => $_POST['beneficiary_name'], 'bank_name' => $_POST['bank_name'], 'iban_number' => $_POST['iban_number'], 'beneficiary_address' => $_POST['beneficiary_address'], 'bank_address' => $_POST['bank_address'], 'account_number' => $_POST['account_number'], 'swift_code' => $_POST['swift_code'],);
                    //  echo "<pre>";print_r($_POST);exit;
                    if ($this->General_Model->update_table('admin_bank_details', 'admin_id', $_POST['admin_id'], $bank_information)) {
                        $response = array('msg' => 'Bank details updated successfully.', 'redirect_url' => base_url() . 'api/api_partners/add_partner/' . $_POST['flag'] . '/' . base64_encode(json_encode($_POST['admin_id'])), 'status' => 1);
                    } else {
                        $response = array('msg' => 'Failed to update Bank details.', 'redirect_url' => base_url() . 'api/api_partners/add_partner/' . $_POST['flag'] . '/' . base64_encode(json_encode($_POST['admin_id'])), 'status' => 0);
                    }
                }
            } else {
                $response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'api/api_partners/add_partner/' . $_POST['flag'] . '/' . base64_encode(json_encode($_POST['admin_id'])), 'status' => 0);
            }
            echo json_encode($response);
            exit;
        }else {
            $this->data['users'] = $this->General_Model->get_admin_details_by_role_v1(2, 'status');
            $this->load->view('api/partners/partner_list', $this->data);
        }
    }   


    public function new_api()
    {
        $segment = $this->uri->segment(3);
        if ($segment == 'save_api') {
            
            $this->form_validation->set_rules('partners', 'Partner', 'required');
            $this->form_validation->set_rules('from_date', 'From Date', 'required');
            $this->form_validation->set_rules('to_date', 'To Date', 'required');
            $this->form_validation->set_rules('tournament', 'Tournament', 'required');
            $this->form_validation->set_rules('events[]', 'Events', 'required');
            $this->form_validation->set_rules('category', 'Category', 'required');
            $this->form_validation->set_rules('tickets_per_events', 'Tickets per events', 'required');
            $this->form_validation->set_rules('fullfillment', 'Full Fillment', 'required');
            $this->form_validation->set_rules('status', 'API Status', 'required');

            if ($this->form_validation->run() !== false) {
                
                $apiId = $_POST['new_apiId'];
                if ($apiId == '') {
                    for($i=0; $i<count($_POST['events']);$i++)
                    {
                        $eveVal = $_POST;
                        $insertData = array(
                            'API_id'  => date('dyhis'),
                            'partner_id' => $eveVal['partners'],
                            'from_date'  => $eveVal['from_date'],
                            'to_date'    => $eveVal['to_date'],
                            'tournament_id' => $eveVal['tournament'],
                            'event_id'      => $eveVal['events'][$i],
                            'category_id'   => $eveVal['category'],
                            'tickets_per_events' => $eveVal['tickets_per_events'],
                            'fullfillment_type' => $eveVal['fullfillment'],
                            'api_status'       => $eveVal['status']
                        );
                        
                        $a_id = $this->General_Model->insert_data('api_partner_events', $insertData);
                    }
                    $response = array('status' => 1, 'msg' => 'API Created Successfully. ' . $msg, 'redirect_url' => base_url() . 'api/api_listing');
                    echo json_encode($response);
                    exit;
                }else {

                    $eveVal = $_POST;
                    $updatetData = array(
                        'partner_id' => $eveVal['partners'],
                        'from_date'  => date('Y-m-d', strtotime($eveVal['from_date'])),
                        'to_date'    => date('Y-m-d', strtotime($eveVal['to_date'])),
                        'tournament_id' => $eveVal['tournament'],
                        'event_id'      => $eveVal['events'],
                        'category_id'   => $eveVal['category'],
                        'tickets_per_events' => $eveVal['tickets_per_events'],
                        'fullfillment_type' => $eveVal['fullfillment'],
                        'api_status'       => $eveVal['status'],
                        'update_date_time' => date('Y-m-d H:i:s'),
                    );
                    $this->General_Model->update_table('api_partner_events', 'id', $apiId, $updatetData);
                    $response = array('status' => 1, 'msg' => 'API Updated Successfully. ' . $msg, 'redirect_url' => base_url() . 'api/api_listing');
                    echo json_encode($response);
                    exit;
                }
            }else {
                $response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'api/new_api/api_list', 'status' => 0);
            }
            echo json_encode($response);
            exit;
        }else{
            $segment3 = $this->uri->segment(3);
            $api_id = json_decode(base64_decode($segment3));
            if(!empty($api_id)){
                $this->data['api_data']  = $this->General_Model->getAllItemTable('api_partner_events', 'id', $api_id, 'id', 'DESC')->row();
            }
            $this->data['partners']   = $this->General_Model->get_admin_details_by_role_v1(2, 'status');
            $this->data['tournaments']   = $this->General_Model->get_tournament_data()->result();
            $this->data['events']   = $this->Api_Model->get_event_by_id()->result();
            $this->data['categories']   = $this->General_Model->get_game_category()->result();
            //echo "<pre>";print_r($this->data['categories']);die;
            $this->load->view('api/new_api',$this->data);
        }

    }

    public function api_listing($status="active",$rowno="")
    {
        if ($this->input->post('submit') != NULL) {
            $search_text = $this->input->post('search');
            $this->session->set_userdata(array("api_listing_search" => $search_text));
        } else {
            if ($this->session->userdata('api_listing_search') != NULL) {
                $search_text = $this->session->userdata('api_listing_search');
            }
        }
        $status = $this->uri->segment(3);
        $rowno = $this->uri->segment(4);
        if($status == "delete_api") {
            $updatetData['api_status'] = 2;
            $delete = $this->General_Model->update_table('api_partner_events', 'id', $rowno, $updatetData); 
            if ($delete == 1) {
                $response = array('status' => 1, 'msg' => 'API data deleted Successfully.','redirect_url' => base_url() . 'api/api_listing/');
                echo json_encode($response);
                exit;
            } else {
                $response = array('status' => 1, 'msg' => 'Error While Deleting API data.','redirect_url' => base_url() . 'api/api_listing/');
                echo json_encode($response);
                exit;
            }

        }
        $this->loadRecord('api_listing',$rowno, 'api/api_listing', 'api_list', $status, "",$search_text);
    }

    public function settings()
    {
        $this->load->view('api/settings/api_settings');
    }

    public function api_key_settings()
    {
        $segment = $this->uri->segment(3);
        if($segment == "save"){
            $this->form_validation->set_rules('partners', 'Partner', 'required');
            $this->form_validation->set_rules('api_key', 'API Key', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required');
            if ($this->form_validation->run() !== false) {
                $settings_id = $_POST['settings_id'];
                if ($settings_id == '') {
                    $insertData = array('partner_id' => $_POST['partners'],'api_key' => $_POST['api_key'],'status' => $_POST['status']);
                    
                    if ($this->General_Model->insert_data('api_key_settings', $insertData)) {
                        $response = array('msg' => 'API settings added successfully.', 'redirect_url' => base_url() . 'api/api_key_settings/', 'status' => 1);
                    } else {
                        $response = array('msg' => 'Failed to Add API settings.', 'redirect_url' => base_url() . 'api/api_key_settings/add_setting/', 'status' => 0);
                    }
                }else{
                    $updateData = array('partner_id' => $_POST['partners'],'api_key' => $_POST['api_key'],'status' => $_POST['status']);
                    
                    if ($this->General_Model->update_table('api_key_settings','id',$settings_id, $updateData)) {
                        $response = array('msg' => 'API settings Updated successfully.', 'redirect_url' => base_url() . 'api/api_key_settings/', 'status' => 1);
                    } else {
                        $response = array('msg' => 'Failed to Update API settings.', 'redirect_url' => base_url() . 'api/api_key_settings/add_setting/', 'status' => 0);
                    }
                }
            }else {
                $response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'api/api_key_settings/add_setting', 'status' => 0);
            }
            echo json_encode($response);
            exit;

        }else if($segment == "add_setting"){
            $segment4 = $this->uri->segment(4);
            $settings_id = json_decode(base64_decode($segment4));
            if(!empty($settings_id)){
                $this->data['settings']  = $this->General_Model->getAllItemTable('api_key_settings', '', $segment4, 'id', 'DESC')->row();
            }
            $this->data['partners']   = $this->General_Model->get_admin_details_by_role_v1(2, 'status');
            //echo "<pre>"; print_r($this->data);die;
            $this->load->view('api/settings/add_api_settings', $this->data);
        }else if ($segment == 'delete_api_settings') {
            $segment4 = $this->uri->segment(4);
            $delete = $this->General_Model->delete_data('api_key_settings','id',$segment4);
            if ($delete == 1) {
                $response = array('status' => 1, 'msg' => 'API settings deleted Successfully.');
                echo json_encode($response);
                exit;
            } else {
                $response = array('status' => 1, 'msg' => 'Error While Deleting API Settings.');
                echo json_encode($response);
                exit;
            }
        }else{
            $this->loadRecord('api_settings',$rowno, 'api/settings/api_settings_list', 'api_settings');
        }
    }

    function ip_patching()
    {
        $segment = $this->uri->segment(3);
        if($segment == "save"){
            $this->form_validation->set_rules('partners', 'Partner', 'required');
            $this->form_validation->set_rules('ip_address', 'IP Address', 'required');
            if ($this->form_validation->run() !== false) {
                $settings_id = $_POST['settings_id'];
                if ($settings_id == '') {
                    $insertData = array('partner_id' => $_POST['partners'],'ip_address' => $_POST['ip_address']);
                    
                    if ($this->General_Model->insert_data('api_ip_patching', $insertData)) {
                        $response = array('msg' => 'IP Patched successfully.', 'redirect_url' => base_url() . 'api/ip_patching/', 'status' => 1);
                    } else {
                        $response = array('msg' => 'Failed to Add IP Patching.', 'redirect_url' => base_url() . 'api/ip_patching/add_ip/', 'status' => 0);
                    }
                }else{
                    $updateData = array('partner_id' => $_POST['partners'],'ip_address' => $_POST['ip_address']);
                    
                    if ($this->General_Model->update_table('api_ip_patching','id',$settings_id, $updateData)) {
                        $response = array('msg' => 'IP Patching Updated successfully.', 'redirect_url' => base_url() . 'api/ip_patching/', 'status' => 1);
                    } else {
                        $response = array('msg' => 'Failed to Update IP Patching.', 'redirect_url' => base_url() . 'api/ip_patching/add_ip/', 'status' => 0);
                    }
                }
            }else {
                $response = array('msg' => validation_errors(), 'redirect_url' => base_url() . 'api/api_key_settings/add_setting', 'status' => 0);
            }
            echo json_encode($response);
            exit;

        }else if($segment == "add_ip"){
            $segment4 = $this->uri->segment(4);
            $settings_id = json_decode(base64_decode($segment4));
            if(!empty($settings_id)){
                $this->data['settings']  = $this->General_Model->getAllItemTable('api_ip_patching', '', $segment4, 'id', 'DESC')->row();
            }
            $this->data['partners']   = $this->General_Model->get_admin_details_by_role_v1(2, 'status');
            //echo "<pre>"; print_r($this->data);die;
            $this->load->view('api/ip_patching/add_ip_patching', $this->data);
        }else if ($segment == 'delete_ip') {
            $segment4 = $this->uri->segment(4);
            $delete = $this->General_Model->delete_data('api_ip_patching','id',$segment4);
            if ($delete == 1) {
                $response = array('status' => 1, 'msg' => 'IP Patching deleted Successfully.');
                echo json_encode($response);
                exit;
            } else {
                $response = array('status' => 1, 'msg' => 'Error While Deleting IP Patching.');
                echo json_encode($response);
                exit;
            }
        }else{
            $this->loadRecord('ip_patching',$rowno, 'api/ip_patching/ip_patching_list', 'ip_list');
        }
    }

    /**
     * Fetch data and display based on the pagination request
     */
    public function loadRecord($status_flag, $rowno = 0, $view, $variable_name, $get_status="", $table="",$search_text="")
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
       
        if ($status_flag == "api_listing") {
            $url = "api/api_listing/".$get_status;
            if($get_status == "trashed"){ $status="2";}else if($get_status == "expired"){ $status="0";}else {$status=1;}
            // All records count
            $allcount = $this->Api_Model->get_api_details($status,"","",$search_text)->num_rows();
            // Get records
            $record = $this->Api_Model->get_api_details($status, $row_per_page, $rowno, $search_text)->result();
        }else if($status_flag == "api_settings"){
            $url = "api/api_key_settings";
            // All records count
            $allcount = $this->Api_Model->api_key_settings()->num_rows();
            // Get records
            $record = $this->Api_Model->api_key_settings($row_per_page, $rowno)->result();
        }else if($status_flag == "ip_patching"){
            $url = "api/ip_patching";
            // All records count
            $allcount = $this->Api_Model->api_ip_patching()->num_rows();
            // Get records
            $record = $this->Api_Model->api_ip_patching($row_per_page, $rowno)->result();
        }else if($status_flag == "api_partner_markup"){
            $url = "api/api_partner_markup/partner_matkup_list";
            // All records count
            $allcount = $this->General_Model->get_table_row_count_markup(2)->num_rows();

            // Get records
            $record = $this->General_Model->get_limit_based_data_markup("",$rowno,$row_per_page,"id","DESC",2)->result();
        }else {
            $url = 'api/'.$status_flag;
            // All records count
            $allcount = $this->General_Model->get_table_row_count($table, '');
            // Get records
            $record = $this->General_Model->get_limit_based_data($table, $rowno, $row_per_page)->result();
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
        $this->data['search'] = $search_text;
        // Load view
        $this->load->view($view, $this->data);
    }

    public function getEventById($id)
    {   $response['status'] = 0;
        $response['data'] = "";
        if(!empty($id)){
           $event =  $this->Api_Model->get_event_by_id($id);
           if($event->num_rows() > 0){
                $response['status'] = 1;
                $response['data'] = $event->result();
           }
           
        }
        echo json_encode($response);
        
    }

    public function api_partner_markup()
    {
        //echo "<pre>";print_r($this->session->userdata('role'));

        $segment = $this->uri->segment(3);

        if ($segment == 'add_partner_markup') {

            $segment4 = $this->uri->segment(4);

            if ($segment4 != "") {

                $edit_id = $edit_id = json_decode(base64_decode($this->uri->segment(4)));
                $this->data['markup'] = $this->General_Model->getAllItemTable('tickets_markup', 'id', $edit_id, 'id', 'DESC')->row();
            }
            $this->data['role']    = 2;
            $this->data['partner'] = $this->General_Model->get_admin_details_by_role($this->data['role'], 'ACTIVE');
            //echo "<pre>";print_r($this->data['partner']);exit;
            $this->load->view('api/markup/add_markup', $this->data);
        } else if ($segment == 'partner_matkup_list') {
            $this->data['role']    = 2;
            $row_count = $this->uri->segment(4);
            $this->loadRecord('api_partner_markup',$rowno="", 'api/markup/markup_list', 'markups','','tickets_markup');
        }else if ($segment == 'delete_seller_markup') {

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
            $user_type = 'partner';
            
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
                    'status' => isset($_POST['status'])?$_POST['status']:0,
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


   
}
