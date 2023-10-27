<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);
class Accounts extends CI_Controller {
    public function __construct() {
        /*
         *  Developed by: Sivakumar G
         *  Date    : 22 January, 2022
         *  1BoxOffice Hub
         *  https://www.1boxoffice.com/
        */
        parent::__construct();
        $this->load->model('Accounts_Model');
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

    public function save_payout(){

        //echo "<pre>";print_r($_POST);exit;
        $this->form_validation->set_rules('seller', 'Seller', 'required');
        $this->form_validation->set_rules('order_from', 'Ordered Date From', 'required');
        $this->form_validation->set_rules('order_to', 'Ordered Date To', 'required');
        $this->form_validation->set_rules('orders[]', 'Orders', 'required');
        if (empty($_FILES['payout_receipt']['name']))
        {
        $this->form_validation->set_rules('payout_receipt', 'payout receipt', 'required');
        }

        if ($this->form_validation->run() !== false) { 
                $pay_out_info = array();
                if (!empty($_FILES['payout_receipt']['name'])) { 

                $config['upload_path'] = 'uploads/payout_receipt';
                $config['allowed_types'] = 'pdf';
                $config['max_size'] = '1000000';
                $config['encrypt_name'] = TRUE;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('payout_receipt')) {
               
                $data = $this->upload->data();
                $pay_out_info['receipt'] = $data['file_name'];

                } else {
                $pay_out_info['receipt'] = '';
                }
                }  

            $order_info = array();
            $total_amount = array();
            foreach($_POST['orders'] as $orders){

            $where = array('bg_id' => $orders);
            $payable_orders = $this->Accounts_Model->get_unpaid_orders($where);
            $order_info[] = $payable_orders[0];
            $total_amount[] = $payable_orders[0]->ticket_amount;
            }

            
            if(isset($order_info)){
                $pay_out_info['payout_no'] = rand(10000,10000000);
                $pay_out_info['seller_id'] = $_POST['seller'];
                $pay_out_info['payout_date_from'] = date('Y-m-d',strtotime($_POST['order_from']));
                $pay_out_info['payout_date_to'] = date('Y-m-d',strtotime($_POST['order_to']));
                $pay_out_info['payout_orders'] = json_encode($order_info);
                $pay_out_info['total_payable'] = array_sum($total_amount);
                $pay_out_info['currency'] = $order_info[0]->base_currency;
                $pay_out_info['total_orders'] = count($order_info);
                $pay_out_info['paid_date_time'] = date('Y-m-d h:i:s');
                $Insert = $this->General_Model->insert_data('payouts', $pay_out_info);
            }
            if($Insert != ''){
                 foreach($order_info as $order_in){
                    $update_data = array('payout_status' => '1');
                    $this->General_Model->update('booking_global', array('bg_id' => $order_in->bg_id), $update_data);
                 }

            }
            $response = array('status' => 1, 'msg' => "Payout Created Successfully.", 'redirect_url' => base_url() . 'accounts/payouts');
              echo json_encode($response);
                exit;

            
        }
        else { 
                $response = array('status' => 0, 'msg' => validation_errors());
                echo json_encode($response);
                exit;
            }

      
    }

    
    public function re_arrange_fee()
    {
        if($_POST['orders'][0] != ''){
            $payable_orders = $this->Accounts_Model->get_unpaid_orders_v1($_POST['orders']);
            $payable_amount = array();
            foreach ($payable_orders as $payable_order) {
               $payable_amount[] = $payable_order->ticket_amount;
            }
          //  echo "<pre>";print_r($payable_orders);exit;
            
             $this->mydata['payable_orders'] = $payable_orders;
          //  $list_orders = $this->load->view('accounts/make_payouts_ajax', $this->mydata, TRUE);
            $this->mydata['base_currency'] = $payable_orders[0]->base_currency;
            $this->mydata['list_orders'] = $list_orders;
            $this->mydata['payable_amount'] = number_format(array_sum($payable_amount),2);
            echo json_encode(array('status' => 1, 'response' => $this->mydata));
            exit;
        }
      
    }

    public function get_unpayable_orders()
    {
        if($_POST['seller_id'] != ''){

            $where = array('seller_id' => $_POST['seller_id'],'order_from' => $_POST['order_from'],'order_to' => $_POST['order_to']);
            $payable_orders = $this->Accounts_Model->get_unpaid_orders($where);
            $payable_amount = array();
            foreach ($payable_orders as $payable_order) {
               $payable_amount[] = $payable_order->ticket_amount;
            }
          //  echo "<pre>";print_r($payable_orders);exit;
            
             $this->mydata['payable_orders'] = $payable_orders;
            $list_orders = $this->load->view('accounts/make_payouts_ajax', $this->mydata, TRUE);
            $this->mydata['base_currency'] = $payable_orders[0]->base_currency;
            $this->mydata['list_orders'] = $list_orders;
            $this->mydata['payable_amount'] = number_format(array_sum($payable_amount),2);
            echo json_encode(array('status' => 1, 'response' => $this->mydata));
            exit;
        }
      
    }
    
    public function make_payouts()
    {
        if ($this->session->userdata('role') == 6) {
            $this->data['sellers']    = $this->General_Model->get_admin_details_by_role_v1(1, 'status');
             $this->load->view('accounts/make_payouts',$this->data);
        }
      
    }

    


    public function payouts()
    {
      //  if ($this->session->userdata('role') == 6) {
             $pending_payout    = $this->Accounts_Model->admin_payout_pending();
             $pending_amount = array();
             foreach($pending_payout as $pending_payouts){
                $pending_amount[] = $pending_payouts->ticket_amount;
             }
             $this->data['pending_amount']    = array_sum($pending_amount);
             $this->data['pending_total_orders']    = count($pending_amount);
             $this->data['payout_histories']    = $this->Accounts_Model->admin_payout_histories();
             $this->load->view('accounts/payouts',$this->data);

      /*  }
        else{
             $this->load->view('accounts/payouts');
        }*/
      
    }


  


   
}