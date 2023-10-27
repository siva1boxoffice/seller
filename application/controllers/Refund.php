<?php //echo strtoupper(substr(PHP_OS, 0, 3));exit;
if (!defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);
class Refund extends CI_Controller {

    public function __construct() {

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
    }
     public function app_data() {

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
                //echo "<pre>";print_r($branches);exit;
                $sessionUserInfo = array('storefront' => $branches);
                $this->session->set_userdata($sessionUserInfo);
            /*$sessionUserInfo = array('storefront' => $this->data['branches'][count($this->data['branches']) - 1]);*/
        }
        return $this->data;
    }

    //Default function while load the admin panel
    public function refund($booking_no) {
        
        $orderId = md5($booking_no);
        $orderData =  $this->General_Model->getOrderData($orderId);

        if($orderData->transcation_id != ""){

                    $post['reference'] = $booking_no;
                    /*$post['amount']['value'] = $orderData->total_payment;
                    $post['amount']['currency'] = $orderData->currency_code;*/
                    $post['merchantAccount'] = "1boxofficeECOM";
                   // echo "<pre>";print_r($post);exit;
                    $handle = curl_init();
                    //$url = "https://checkout-test.adyen.com/v69/payments/".$orderData->transcation_id."/reversals";//echo $url;exit;
                    $url = "https://f880fb1f8a16d0b6-1boxoffice-checkout-live.adyenpayments.com/checkout/v69/payments/".$orderData->transcation_id."/reversals";//echo $url;exit;

                    https://f880fb1f8a16d0b6-1boxoffice-checkout-live.adyenpayments.com/checkout/v69/paymentMethods

                    $headers = [
                    'X-API-Key: AQEshmfxKYnJbh1Cw0m/n3Q5qf3VGYlCFZxMXmxVwybUw41HjYvY/WYm8ViY1ZgQwV1bDb7kfNy1WIxIIkxgBw==-erbgzn3xWbhYdPiYS/3g/KkII6gdh7LvXjsuTs4RSts=-ynkH:]4nFu5$w(d9',
                    'Content-Type: application/json'
                    ];

                    curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($handle, CURLOPT_URL, $url);
                    curl_setopt($handle, CURLOPT_POST, 1);
                    curl_setopt($handle, CURLOPT_POSTFIELDS,json_encode($post));
                    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
                    $output = curl_exec($handle);
                    curl_close($handle);
                    echo "<pre>";print_r($output);exit;
                    //echo $url;exit;
                }

        echo "<pre>";print_r($orderData);exit;
    }
   
}
?>
