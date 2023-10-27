<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Logs extends CI_Controller {
    public function __construct() {

        /*
         *  Developed by: PANDIYAN G
         *  Date    : 17 Jan, 2022
         *  1BoxOffice Hub
         *  https://www.1boxoffice.com/
        */
        parent::__construct();

        $this->role = $this->session->userdata('role');
        $this->partner_id =  $this->session->userdata('admin_id');
        if($this->partner_id == "" && $this->session->userdata('role') != 2  ){
            redirect("login");
        }
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
        $this->load->model('Logs_Model');
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

    public function index() 
    {
        $this->data['results'] = $this->Logs_Model->get_logs_group($this->partner_id)->result();
      
        //print_r($this->data['results']);
        $this->load->view(THEME_NAME.'/logs/index', $this->data);
    }

     public function ajax() 
    {
        $row_count = 1;
        $request_type = @$_POST['request_type'] ?  $_POST['request_type'] :  "Events";
        $this->loadRecord($row_count, 'api_partner_logs', 'logs/', 'id', 'DESC', THEME_NAME.'/logs/list_ajax', 'results', 'ajax_logs',$_POST['keyword'],$request_type);
    }

    public function loadRecord($rowno = 0, $table, $url, $order_column, $order_by, $view, $variable_name, $type, $search = '',$request_type)
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

        if ($type == 'ajax_logs') {
            $allcount = $this->Logs_Model->get_logs("","",$search,$request_type)->num_rows();
            $record = $this->Logs_Model->get_logs($rowno, $row_per_page, $search,$request_type)->result();
        }
        else if ($type == 'clicks_ajax') {
            $allcount = $this->Logs_Model->get_clicks("","",$search,$request_type)->num_rows();
            $record = $this->Logs_Model->get_clicks($rowno, $row_per_page, $search,$request_type)->result();
        }
        else {

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
         if ($type == 'ajax_logs') {  
            $list_orders = $this->load->view($view, $this->data, TRUE);
            $response = array('search_type' => 'listing', 'orders' => $list_orders);
            echo json_encode($response);die;
         }
         if ($type == 'clicks_ajax') {  
            $list_orders = $this->load->view($view, $this->data, TRUE);
            $response = array('search_type' => 'listing', 'orders' => $list_orders);
            echo json_encode($response);die;
         }
         else{
            $this->load->view($view, $this->data);
         }
        
    }


    public function download($filename){

        //Check the file exists or not
        $file_url = LOGS_DOWNLOAD."public/logs/".$filename;
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary"); 
        header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
        readfile($file_url); 
    }

    public function clicks() 
    {
        $this->data['results'] = $this->Logs_Model->get_clicks()->result();
      
        //print_r($this->data['results']);
        $this->load->view(THEME_NAME.'/logs/clicks', $this->data);
    }

    public function clicks_ajax()
    {
        $row_count = 1;
        $request_type ="";
        $this->loadRecord($row_count, 'api_partner_logs', 'logs/clicks', 'id', 'DESC', THEME_NAME.'/logs/list_clicks_ajax', 'results', 'clicks_ajax',$_POST['keyword'],$request_type);
    }


}
