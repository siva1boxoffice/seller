<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);
class Payout extends CI_Controller
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
	private function check_isvalidated()
	{ 
		if (!$this->session->userdata('admin_logged_in') || $this->session->userdata('admin_id') != ADMIN_ID) {
			// if ($this->session->userdata('admin_logged_in')) {
			// 	$controller_name = $this->router->fetch_class();
			// 	$function_name = $this->router->fetch_method();
			// 	$this->load->model('Privilege_Model');
			// 	$sub_admin_id = $this->session->userdata('admin_id');
			// 	//echo $sub_admin_id;exit;
			// 	if (!$this->Privilege_Model->get_allowed_pages($sub_admin_id, $controller_name, $function_name) && !$this->Privilege_Model->get_privileges_by_sub_admin_id($sub_admin_id, $controller_name, $function_name)) {
			// 		redirect(base_url() . 'access/error_denied', 'refresh');
			// 	}
			// } else {
			// 	redirect(base_url(), 'refresh');
			// }
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

	public function payment()
	{	
		$this->data['countries']    		= $this->General_Model->fetch_country_list();
    	$this->data['bank_accounts_gbp']    = $this->Accounts_Model->bank_accounts('GBP');
    	$this->data['bank_accounts_eur']    = $this->Accounts_Model->bank_accounts('EUR');
    	$this->data['bank_accounts_usd']    = $this->Accounts_Model->bank_accounts('USD');
    	$this->data['bank_accounts_aed']    = $this->Accounts_Model->bank_accounts('AED');
		$this->data['payout_histories']     = $this->Accounts_Model->admin_payout_histories();
		$this->data['payout_orders']        = $this->Accounts_Model->admin_payout_orders();

		$this->data['pending_delivery_gbp']    = $this->Accounts_Model->admin_payout_orders_payable('GBP','pending_delivery');
		$this->data['pending_payout_gbp']      = $this->Accounts_Model->admin_payout_orders_payable('GBP','pending_payout');
		$this->data['pending_delivery_eur']    = $this->Accounts_Model->admin_payout_orders_payable('EUR','pending_delivery');
		//echo "<pre>";print_r($this->data['pending_delivery_eur']);exit;
		$this->data['pending_payout_eur']      = $this->Accounts_Model->admin_payout_orders_payable('EUR','pending_payout');
		$this->data['pending_delivery_usd']    = $this->Accounts_Model->admin_payout_orders_payable('USD','pending_delivery');
		$this->data['pending_payout_usd']      = $this->Accounts_Model->admin_payout_orders_payable('USD','pending_payout');
		$this->data['pending_delivery_aed']    = $this->Accounts_Model->admin_payout_orders_payable('AED','pending_delivery');
		$this->data['pending_payout_aed']      = $this->Accounts_Model->admin_payout_orders_payable('AED','pending_payout');

		$this->data['holding_gbp']    = $this->Accounts_Model->onhold_prices('GBP');
		$this->data['holding_eur']    = $this->Accounts_Model->onhold_prices('EUR');
		$this->data['holding_usd']    = $this->Accounts_Model->onhold_prices('USD');
		$this->data['holding_aed']    = $this->Accounts_Model->onhold_prices('AED');


		//echo count($this->data['payout_orders']);exit;
		$this->load->view(THEME_NAME.'/payout/payment_new', $this->data);
	}

	public function ajax_payout_histories()
	{	
		$payment_reference 					 = @$_POST['payment_reference'];
		$this->datas['payout_histories']     = $this->Accounts_Model->admin_payout_histories('',$payment_reference);
		$response = $this->load->view(THEME_NAME.'/payout/ajax_payout_histories', $this->datas,true);
		echo json_encode(array('status' => 1,'response' => $response));exit;
	}

	public function ajax_payout_orders()
	{	
		$payment_reference 					 = @$_POST['payment_reference'];
		$status 					 		 = @$_POST['status'];
		$this->datas['payout_orders']        = $this->Accounts_Model->admin_payout_orders($payment_reference,$status);
		$response = $this->load->view(THEME_NAME.'/payout/ajax_payout_orders', $this->datas,true);
		echo json_encode(array('status' => 1,'response' => $response));exit;
	}

	public function download_payout_report(){
		
		$payout_histories     = $this->Accounts_Model->admin_payout_histories();
		$fileName 			  = "Seller_PayoutReports_" . date('Y-m-d') . ".xls"; 
		// Column names 
		$fields 			  = array('Sl.No','PaymentReference','ToAccount','Amount','InitiatedDate','ExpectedDate','Status'); 
		// Display column names as first row 
		$excelData = implode("\t", array_values($fields)) . "\n"; 
			$i = 1;
		  foreach($payout_histories as $payout_history){ 

			  if($payout_history->account_number != ""){ $account_number = $payout_history->account_number;}else{ $account_number =  "-";}
			  if($payout_history->payout_currency == "GBP"){
			      $total_payable = "GBP ".number_format($payout_history->total_payable,2);
			  } else if($payout_history->payout_currency == "EUR"){
			  	  $total_payable = "EUR ".number_format($payout_history->total_payable,2);
			  } else if($payout_history->payout_currency == "USD"){
			  	   $total_payable = "USD ".number_format($payout_history->total_payable,2);
			  } else if($payout_history->payout_currency == "AED"){
			  	$total_payable = "AED ".number_format($payout_history->total_payable,2);
			  } 

        	$lineData = array($i,$payout_history->payout_no,$account_number,$total_payable,date('j F Y',strtotime($payout_history->paid_date_time)),date('j F Y',strtotime($payout_history->paid_date_time. ' + 2 days')),'Paid'); 
       		$i++;
        	$excelData .= implode("\t", array_values($lineData)) . "\n"; 
    	} 
		header("Content-Type: application/vnd.ms-excel"); 
		header("Content-Disposition: attachment; filename=\"$fileName\""); 
		// Render excel data 
		echo $excelData;exit;
	}

	public function download_order_report(){
		
		$payout_histories     = $this->Accounts_Model->admin_payout_orders();
		$fileName 			  = "Seller_PayoutOrders_" . date('Y-m-d') . ".xls"; 
		$fields 			  = array('Sl.No','OrderId','PaymentReference','Event','NetAmount','Deductions','PaymentInitiatedDate','Ticket','Status'); 
		
		// Display column names as first row 
		$excelData = implode("\t", array_values($fields)) . "\n"; 
		$i = 1;
	  foreach($payout_histories as $payout_history){ 

			  if($payout_history->currency_type == "GBP"){
			      $total_payable   = "GBP ".number_format($payout_history->ticket_amount,2);
			      $total_deduction = "GBP ".number_format($payout_history->on_hold,2);
			  } else if($payout_history->currency_type == "EUR"){
			  	  $total_payable = "EUR ".number_format($payout_history->ticket_amount,2);
			  	  $total_deduction = "EUR ".number_format($payout_history->on_hold,2);
			  } else if($payout_history->currency_type == "USD"){
			  	   $total_payable = "USD ".number_format($payout_history->ticket_amount,2);
			  	   $total_deduction = "USD ".number_format($payout_history->on_hold,2);
			  } else if($payout_history->currency_type == "AED"){
			  	$total_payable = "AED ".number_format($payout_history->ticket_amount,2);
			  	$total_deduction = "AED ".number_format($payout_history->on_hold,2);
			  } 

			   if($payout_history->payout_status == '1'){
			        $paid_date_time = date('j F Y',strtotime($payout_history->paid_date_time));
			   }else if($payout_history->payout_status == '2'){
			       $paid_date_time = "Dispute";
			   }else if($payout_history->payout_status == '0'){
			       $paid_date_time = "Pending Payment";
			   }

			    if($payout_history->payout_status == '1'){
			     $payout_status = "Paid";
			    }else if($payout_history->payout_status == '2'){ 
			    	$payout_status = "Dispute";
			    }else if($payout_history->payout_status == '0'){
			    	$payout_status = "Pending";
			    }

	        $lineData = array($i,$payout_history->booking_no,$payout_history->payout_no,$payout_history->match_name,$total_payable,$total_deduction,$paid_date_time,$payout_history->quantity.'*'.$payout_history->seat_category,$payout_status); 
	       $i++;
	        $excelData .= implode("\t", array_values($lineData)) . "\n"; 
	    } 
		header("Content-Type: application/vnd.ms-excel"); 
		header("Content-Disposition: attachment; filename=\"$fileName\""); 
		// Render excel data 
		echo $excelData;exit;

	}

	public function payment_old()
	{	
		
		$this->data['payout_histories']    = $this->Accounts_Model->admin_payout_histories();
		$this->load->view(THEME_NAME.'/payout/payment', $this->data);
	}

	
	public function get_bank_accounts()
    {  
    	$account_id = $_POST['account_id'];
    	if($account_id != ""){
    		$bank_accounts    = $this->Accounts_Model->bank_accounts('',$account_id);
        	echo json_encode(array('status' => 1,'result' => $bank_accounts));exit;
    	}
    	else{
        echo json_encode(array('status' => 0,'result' => ''));exit;
    	}
    	
      
    }

	 public function accounts()
    { 
    	$this->data['countries']    		= $this->General_Model->fetch_country_list();
    	$this->data['bank_accounts_gbp']    = $this->Accounts_Model->bank_accounts('GBP');
    	$this->data['bank_accounts_eur']    = $this->Accounts_Model->bank_accounts('EUR');
    	$this->data['bank_accounts_usd']    = $this->Accounts_Model->bank_accounts('USD');
    	
        $this->load->view(THEME_NAME.'/payout/accounts',$this->data);
      
    }
    
    public function save_bank_accounts()
    { 

    		$this->form_validation->set_rules('currency', 'Currency', 'required');
			$this->form_validation->set_rules('account_name', 'Account Name', 'required');
			$this->form_validation->set_rules('bank', 'Bank', 'required');
			$this->form_validation->set_rules('account_number', 'Account Number', 'required');
			//$this->form_validation->set_rules('confirm_account_number', 'Confirm Account Number', 'required|matches[account_number]');
			$this->form_validation->set_rules('branch', 'Branch', 'required');
			$this->form_validation->set_rules('sort_code', 'Sort Code', 'required');
			/*$this->form_validation->set_rules('iban', 'IBAN', 'required');
			$this->form_validation->set_rules('swift', 'Swift Code', 'required');*/
			if ($this->form_validation->run() !== false) {

				$bank_accounts['country'] 			= $_POST['country'];
				$bank_accounts['currency'] 			= $_POST['currency'];
				$bank_accounts['beneficiary_name'] 	= $_POST['account_name'];
				$bank_accounts['bank_name'] 		= $_POST['bank'];
				$bank_accounts['account_number'] 	= $_POST['account_number'];
				$bank_accounts['bank_address'] 		= $_POST['branch'];
				$bank_accounts['sort_code'] 		= $_POST['sort_code'];
				/*$bank_accounts['iban_number'] 		= $_POST['iban'];
				$bank_accounts['swift_code'] 		= $_POST['swift'];*/

				$check_exists = $this->General_Model->getAllItemTable_array('admin_bank_details', array("admin_id" => $this->session->userdata('admin_id'),"currency" => $_POST['currency']))->row();
				if($check_exists->bank_id  != ""){
					$update = $this->General_Model->update_table('admin_bank_details', 'bank_id', $check_exists->bank_id, $bank_accounts);
					$response = array('status' => 1, 'msg' => strtoupper($_POST['currency']).' Bank account updated Successfully.');
					echo json_encode($response);
					exit;
				}
				else{
					$bank_accounts['admin_id'] 			= $this->session->userdata('admin_id');
					$update = $this->General_Model->insert_data('admin_bank_details', $bank_accounts);
					$response = array('status' => 1, 'msg' => strtoupper($_POST['currency']).' Bank account created Successfully.');
					echo json_encode($response);
					exit;
				}

				

			} else {
				$response = array('status' => 0, 'msg' => validation_errors(), 'redirect_url' => base_url() . 'payout/accounts');
			}
			echo json_encode($response);
			exit;

    	
    }

     public function bank_account_delete()
    {  
    		
			$delete_id = $_POST['addressid'];
			if($delete_id != ""){
				$delete = $this->General_Model->delete_data('admin_bank_details', 'bank_id ', $delete_id);
			
			if ($delete == 1) {
				$response = array('status' => 1, 'msg' => 'Bank account deleted Successfully.');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 0, 'msg' => 'Error while deleting Bank account.');
				echo json_encode($response);
				exit;
			}
			}
			else{
				$response = array('status' => 0, 'msg' => 'Error while deleting Bank account.');
				echo json_encode($response);
				exit;
			}
			

    }
	public function download_payout_data(){

            $where_array = array();
            if($_GET['sale_start_date'] != "" && $_GET['sale_end_date'] != ""){
                $where_array['sale_start_date']  = $_GET['sale_start_date'];
                $where_array['sale_end_date']  = $_GET['sale_end_date'];

                $fileName = "Sales_summary_" .$_GET['sale_start_date'] . "_To_".$_GET['sale_end_date'].".xls"; 
            }
            else{
                 $fileName = "Sales_summary_" . date('Y-m-d') . ".xls"; 
            }
            if($_GET['sellers'] != ""){
                $where_array['sellers'] = explode(",", $_GET['sellers']);
            }
            if($_GET['users'] != ""){
                $where_array['users'] =  explode(",",$_GET['users']);
            }
            if($_GET['tournaments'] != ""){
                $where_array['tournaments'] = $_GET['tournaments'];
            }
            
            if($_GET['match_id'] != ""){
                $where_array['match_id'] = $_GET['match_id'];
            }

            if(@$_GET['partner'] != ""){
                $where_array['partner'] = 1;
            }

            //echo "<pre>";print_r($where_array);exit;
            $download_orders = $this->General_Model->get_confirmed_orders('',$where_array)->result();
           
            // Column names 
            $fields = array('OrderNo','OrderDate','Tournament','Match','MatchDate','MatchTime','Stadium','SellerName','Customer Name','Customer Email','SeatCategory','Row','Qty','SoldAt','Currency'); 
            if(@$_GET['partner'] != ""){
                $fields[]= 'PartnerCommision';
                $fields[]= 'CommisionCurrency';
            } //echo "<pre>";print_r($fields);exit;
            // Display column names as first row 
            $excelData = implode("\t", array_values($fields)) . "\n"; 
            $total_amount = array();
            foreach($download_orders as $download_order){  
            $lineData = array($download_order->booking_no,$download_order->updated_at,$download_order->tournament_name,$download_order->match_name,date("d F Y",strtotime($download_order->match_date)),$download_order->match_time,$download_order->stadium_name.','.$download_order->stadium_city_name.','.$download_order->stadium_country_name,$download_order->seller_first_name.' '.$download_order->seller_last_name,

                $download_order->customer_first_name.' '.$download_order->customer_last_name,
                $download_order->customer_email,
                $download_order->seat_category,
                $download_order->row,$download_order->quantity,number_format($download_order->total_base_amount,2),$download_order->base_currency); 
            if(@$_GET['partner'] != ""){
                $lineData[]= $download_order->partner_commission;
                $lineData[]= $download_order->base_currency;
            }
            $total_amount[] = $download_order->total_base_amount;
            $excelData .= implode("\t", array_values($lineData)) . "\n"; 
            } 
            $lineData = array('','','','','','','','','','','',number_format(array_sum($total_amount),2),$download_orders[0]->base_currency); 
            $excelData .= implode("\t", array_values($lineData)) . "\n"; 
            header("Content-Type: application/vnd.ms-excel"); 
            header("Content-Disposition: attachment; filename=\"$fileName\""); 

            // Render excel data 
            echo $excelData; 

            exit;
    }

	public function download_payout()
	{	
		$payout_id = $this->uri->segment(3);
		if($payout_id != ""){ 

			$this->load->model('Accounts_Model');
		$payout_histories    = $this->Accounts_Model->admin_payout_histories('',$payout_id);

		$payout_data = array();
		if(!empty($payout_histories)){
			foreach($payout_histories as $payout_history){
			
				$payout_data[$payout_history->payout_date_from.'_'.$payout_history->payout_date_to][] = $payout_history;
				
			}
		}
	}

		

	
		$fields = array('Sl.No','Payment#','OrderNo','OrderDate','Match','MatchDate','Stadium','Description','SoldAt','Currency'); 
            $excelData = implode("\t", array_values($fields)) . "\n"; 
            $total_amount = array();
            foreach($payout_data as $payout_datas){   
            	  foreach($payout_datas as $payout){ 	//echo "<pre>";print_r($payout);exit;
            	  	$payout_orders = json_decode($payout->payout_orders);
            	  	$i = 1;
            	  	 foreach($payout_orders as $payout_order){  
            	  	 		 //echo "<pre>";print_r($payout_order);
            	  	$booking_data    = $this->Accounts_Model->booking_data($payout_order->bg_id);
            $lineData = array($i,$payout->payout_no,$payout_order->booking_no,date("d/m/Y h:i:s",strtotime($payout_order->created_at)),$booking_data->match_name,date("d F Y",strtotime($booking_data->match_date)),$booking_data->stadium_name,'PROCEEDS',
                $payout_order->ticket_amount,$payout_order->currency_type); 
            $i++;
            $excelData .= implode("\t", array_values($lineData)) . "\n"; 
        		}
            } 
        }

            $lineData = array('','','','','','','','TOTAL:',number_format($payout_datas[0]->total_payable,2),$payout_datas[0]->payout_currency); 
            $excelData .= implode("\t", array_values($lineData)) . "\n"; 
            $fileName = "Payment_" .$payout_datas[0]->payout_no.".xls";

            header("Content-Type: application/vnd.ms-excel"); 
            header("Content-Disposition: attachment; filename=\"$fileName\""); 

            // Render excel data 
            echo $excelData; 

            exit;
	}

	public function payment_details()
	{	
		$payout_id = $this->uri->segment(3);
		if($payout_id != ""){ 

			$this->load->model('Accounts_Model');
		$payout_histories    = $this->Accounts_Model->admin_payout_histories('','',$payout_id);

		$payout_data = array();
		if(!empty($payout_histories)){
			foreach($payout_histories as $payout_history){
			
				$payout_data[$payout_history->payout_date_from.'_'.$payout_history->payout_date_to][] = $payout_history;
				
			}
		}
	}
		$this->data['payout_histories']    =  $payout_data;
		$this->load->view(THEME_NAME.'/payout/payment_details', $this->data);
	}

	public function index()
	{	
		$this->load->model('Accounts_Model');
		$this->data['payout_histories']    = $this->Accounts_Model->admin_payout_histories();
		$this->load->view(THEME_NAME.'/payout/payout_histories', $this->data);
	}

	public function payout_history_ajax(){ 
		$currency =$_POST['currency'];
		if($currency != ""){

		$this->load->model('Accounts_Model');
		$payout_histories    = $this->Accounts_Model->admin_payout_histories($currency);
		$payout_data = array();
		if(!empty($payout_histories)){
			foreach($payout_histories as $payout_history){
				$payout_data[$payout_history->payout_date_from.'_'.$payout_history->payout_date_to][] = $payout_history;
				
			}
		}
		$this->data['payout_histories']    =  $payout_data;
		$mydata['payouts'] = $this->load->view(THEME_NAME.'/payout/payout_history_ajax', $this->data,true);
		ob_start('ob_gzhandler');
		header('Content-Type: application/json');
		echo json_encode($mydata, JSON_PRETTY_PRINT);  exit;
		}

	}

	public function payment_history_ajax(){ 
		$currency =$_POST['currency'];
		$txnid    =$_POST['txnid'];
		$fromdate =$_POST['fromdate'];
		$todate   =$_POST['todate'];
		if($currency != ""){

		$this->load->model('Accounts_Model');
		$payout_histories    = $this->Accounts_Model->admin_payout_histories($currency,'',$txnid,$fromdate,$todate);
		$payout_data = array();
		$payout_txns = array();
		/*if(!empty($payout_histories)){
			foreach($payout_histories as $payout_history){
				$payout_data[$payout_history->payout_date_from.'_'.$payout_history->payout_date_to][] = $payout_history;
				
			}
		}*/
		if(!empty($payout_histories)){
			foreach($payout_histories as $payout_history){ 

				$paid_date_time = date("F Y",strtotime($payout_history->paid_date_time));
				//echo $payout_history->paid_date_time.' '.$paid_date_time;echo "<br>";
				//$payout_data[$payout_history->payout_date_from.'_'.$payout_history->payout_date_to][] = $payout_history;
				$payout_data[$paid_date_time][] = $payout_history;
				if($txnid == ""){
					$payout_txns[] = array('value' => $payout_history->payout_id,'label' => $payout_history->payout_no);
				}
				
				
			}
		}
		$this->data['payout_histories']    =  $payout_data;
		$mydata['payout_txns']    =  $payout_txns;
		$mydata['payouts'] = $this->load->view(THEME_NAME.'/payout/payment_history_ajax', $this->data,true);
		ob_start('ob_gzhandler');
		header('Content-Type: application/json');
		echo json_encode($mydata, JSON_PRETTY_PRINT);  exit;
		}

	}

	public function payout_details(){

		$payout_id = $this->uri->segment(3);
		if($payout_id != ""){

			$this->load->model('Accounts_Model');
		$payout_histories    = $this->Accounts_Model->admin_payout_histories('',$payout_id);
		$payout_data = array();
		if(!empty($payout_histories)){
			foreach($payout_histories as $payout_history){
				$payout_data[$payout_history->payout_date_from.'_'.$payout_history->payout_date_to][] = $payout_history;
				
			}
		}
		$this->data['payout_histories']    =  $payout_data;
		$this->load->view(THEME_NAME.'/payout/payout_details', $this->data);

		}
	}

}