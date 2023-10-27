<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Email_Model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }
    public function mysqlRealEscapeString($value) {
        $search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
        $replace = array("\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z");
        return str_replace($search, $replace, $value);
    }
    function email_access() {
        $this->db->select('*');
        $this->db->from('email_access');
        return $this->db->get()->row();
    }
    function booking_success_email_template() {
        $this->db->select('*');
        $this->db->from('email_template');
        $this->db->where('email_type', 'booking_success');
        return $this->db->get()->row();
    }
    public function get_booking_hotel_data($global_id) {
        $this->db->select('booking_hotel.id as hotel_id, booking_hotel.*, booking_global.*');
        $this->db->from('booking_hotel');
        $this->db->join('booking_global', 'booking_hotel.booking_global_id = booking_global.booking_global_id');
        $this->db->join('booking_transaction', 'booking_hotel.id = booking_transaction.booking_id');
        $this->db->where('booking_hotel.product_id = booking_transaction.booking_product_id');
        $this->db->where('booking_global.booking_global_id', $global_id);
        $qry = $this->db->get();
        if ($qry->num_rows() > 0) {
            return $qry->result();
        } else {
            return array();
        }
    }
    public function get_hotel($hcode) {
        $this->db->select('*');
        $this->db->from('hotel_list');
        $this->db->where('hotel_code', $hcode);
        return $this->db->get()->row();
    }
    public function get_hotel_conatcts($hotel_id) {
        $this->db->select('reservation_email');
        $this->db->from('hotel_contacts');
        $this->db->where('hotel_code', $hotel_id);
        return $this->db->get()->row();
    }
    public function get_user_email($user_id) {
        $this->db->select('user_email');
        $this->db->from('user_details');
        $this->db->where('user_id', $user_id);
        return $this->db->get()->row();
    }
    public function get_employee_email($user_id) {
        $this->db->select('admin_email');
        $this->db->from('admin_details');
        $this->db->where('admin_id', $user_id);
        return $this->db->get()->row();
    }
    public function send_mail($content) { //echo "<pre>";print_r($content);exit;
        $access = $this->email_access();
        $ci = get_instance();
        $ci->load->library('email');
        $config['protocol'] = $access->smtp;
        $config['smtp_host'] = $access->host;
        $config['smtp_port'] = $access->port;
        $config['smtp_user'] = $access->username;
        $config['smtp_pass'] = $access->password;
        $config['charset'] = "utf-8";
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";
        $config['wordwrap'] = true;
        $ci->email->initialize($config);
        $ci->email->from($content['email_template']->email_from, $content['email_template']->email_from_name);
        $ci->email->to($content['email_template']->to_email);
        if ($content['email_template']->cc != '') {
            $ci->email->cc($content['email_template']->cc);
        }
        $ci->email->reply_to($content['email_template']->email_from, $content['email_template']->email_from_name);
        $ci->email->subject($content['email_template']->subject);
        $message = str_replace("{%%USERNAME%%}", $content['user_data']->admin_name, $content['email_template']->message);
        $ci->email->message($message);
        $ci->email->send();
        if (!$ci->email->send()) {
            return false;
        }
        return true;
    }
    public function call_hotal_mails($book_global_id, $booking_id) {
        $success_temp = $this->booking_success_email_template();
        // $hotel_data = $this->get_booking_hotel_data($book_global_id, $booking_id)[0];
        $booking_data['voucher'] = $vouchers = $this->Booking_Model->get_booking_hotel_data($book_global_id, $booking_id);
        $booking_data['passenger'] = $this->Booking_Model->getBookingpassengerTemp($booking_id, 2)->result();
        $hotel_details = $this->get_hotel($vouchers[0]->hotel_code);
        if ($hotel_details->booking_status == 'INSTANT') {
            $hotel = array();
            $client = array();
            $ccmail = array();
            $booking = array();
            // hotel maill
            $hotel_conatct = $this->get_hotel_conatcts($hotel_details->hotel_code);
            $hotel[] = isset($hotel_conatct->reservation_email) ? $hotel_conatct->reservation_email : '';
            // client or agent mail
            if ($this->AGENTS_USER_TYPE_ID == 1) {
                $user_email = $this->get_user_email($vouchers[0]->master_user_id);
                $client[] = $user_email->user_email;
                if ($this->USER_TYPE_ID == 8) {
                    $user_email = $this->get_user_email($vouchers[0]->user_id);
                    $client[] = $user_email->user_email;
                }
            } elseif ($this->AGENTS_USER_TYPE_ID == 9) {
                $user_email = $this->get_employee_email($vouchers[0]->user_id);
                $client[] = $user_email->user_email;
            }
            $client[] = $vouchers[0]->lead_pax_email;
            // Falcon mail
            $a = explode(',', $success_temp->to_email);
            foreach ($a as $b) {
                $ccmail[] = $b;
            }
            /*if ($this->AGENTS_USER_TYPE_ID == 1) {
                $user_email = $this->get_user_email($vouchers[0]->master_user_id);
                $client[] = $user_email->user_email;
            
                if ($this->USER_TYPE_ID == 8) {
            
                    $user_email = $this->get_user_email($vouchers[0]->user_id);
                    $client[] = $user_email->user_email;
            
                }
            } elseif ($this->AGENTS_USER_TYPE_ID == 9) {
                $user_email = $this->get_employee_email($hotel_data->user_id);
                $client[] = $user_email->user_email;
            
            }
            */
            $booking[] = 'booking@falcon-travel.com';
            $user_content = 'Thank you For choosing Falcon Luxury Travel services';
            $hotel_content = 'Please confirm The following Booking informations ASAP';
            // Voucher
            $voucher = $this->load->view('vouchers/hotel_voucher', $booking_data, true);
            $this->mailconf('Booking Voucher', $voucher, $success_temp->email_from, $success_temp->email_from_name, $ccmail, '');
            $this->mailconf('Booking Voucher', $user_content . '<br>' . $voucher, $success_temp->email_from, $success_temp->email_from_name, $client, $ccmail);
            $this->mailconf('Booking Voucher', $hotel_content . '<br>' . $voucher, $success_temp->email_from, $success_temp->email_from_name, $hotel, $ccmail);
            $this->mailconf('Booking Voucher', $voucher, $success_temp->email_from, $success_temp->email_from_name, $booking, $ccmail);
        }
        return true;
    }
    public function call_flight_mails($book_global_id, $booking_id) {
        $success_temp = $this->booking_success_email_template();
        $vouchers = $this->Booking_Model->get_booking_flight_data($book_global_id, $booking_id);
        //echo $this->db->last_query();die;
        $booking_data['voucher'] = $vouchers[0];
        $booking_data['flight_info'] = $this->Booking_Model->get_booking_flight_pnr($vouchers[0]->crs_flight_id);
        $booking_data['passenger'] = $this->Booking_Model->getBookingpassengerTemp($booking_id, 1)->result();
        //$booking_data['PNR'] = $booking_data['flight_info']->PNR;
        $booking_data['segment'] = json_decode($booking_data['voucher']->segment_data);
        $booking_data['pricing'] = json_decode($booking_data['voucher']->PricingDetails);
        //echo "<pre/>";print_r($booking_data);die;
        $voucher = $this->load->view('vouchers/flight_voucher', $booking_data, true);
        //echo $voucher;die;
        $list = array();
        $hotel = array();
        $client = array();
        $ccmail = array();
        $booking = array();
        // hotel maill
        $hotel_conatct = $this->get_hotel_conatcts($hotel_details->hotel_code);
        $hotel[] = isset($hotel_conatct->reservation_email) ? $hotel_conatct->reservation_email : '';
        // client or agent mail
        if ($this->AGENTS_USER_TYPE_ID == 1) {
            $user_email = $this->get_user_email($packages[0]->master_user_id);
            $client[] = $user_email->user_email;
            if ($this->USER_TYPE_ID == 8) {
                $user_email = $this->get_user_email($packages[0]->user_id);
                $client[] = $user_email->user_email;
            }
        } elseif ($this->AGENTS_USER_TYPE_ID == 9) {
            $user_email = $this->get_employee_email($packages[0]->user_id);
            $client[] = $user_email->user_email;
        }
        $client[] = $booking_data['voucher']->lead_pax_email;
        //echo $success_temp->to_email;die;
        // Falcon mail
        $a = explode(',', $success_temp->to_email);
        foreach ($a as $b) {
            $ccmail[] = $b;
        }
        $booking[] = 'ticket@falcon-travel.com';
        $user_content = 'Thank you For choosing Falcon Luxury Travel services';
        $hotel_content = 'Please confirm The following Booking informations ASAP';
        // Voucher
        //$voucher = $this->load->view('vouchers/hotel_voucher', $booking_data, true);
        $this->mailconf('Booking Voucher', $voucher, $success_temp->email_from, $success_temp->email_from_name, $ccmail, '');
        $this->mailconf('Booking Voucher', $user_content . '<br>' . $voucher, $success_temp->email_from, $success_temp->email_from_name, $client, $ccmail);
        //$this->mailconf('Booking Voucher', $hotel_content . '<br>'.$voucher, $success_temp->email_from, $success_temp->email_from_name, $hotel, $ccmail);
        $this->mailconf('Booking Voucher', $voucher, $success_temp->email_from, $success_temp->email_from_name, $booking, $ccmail);
    }
    public function call_package_mails($global_id, $booking_id) {
        $success_temp = $this->booking_success_email_template();
        $this->load->model('Package_Model');
        $packages = $this->Package_Model->get_booking_package_data($global_id, $booking_id);
        $package_details = $this->Package_Model->get_booking_package_details($global_id, $booking_id);
        //$passenger_details             = $this->Booking_Model->get_booking_pax_details($booking_id);
        $passenger_details = $this->Booking_Model->getBookingpassengerTemp($booking_id, 4)->result();
        $pax_data = array();
        foreach ($passenger_details as $passenger_detail) {
            $pax_data[$passenger_detail->travel_type][] = $passenger_detail;
        }
        $hotels = explode('###', $package_details[0]->hotel_data);
        $hotel_data = array();
        foreach ($hotels as $hotel_value) {
            $hid = json_decode(base64_decode($hotel_value));
            $hotel_data[] = $this->Hotel_Model->hotel_details_crs($hid);
        }
        $room_selected_data = json_decode($package_details[0]->room_selected_data, 1);
        //echo "<pre>";print_r($package_details[0]->tour_day_lists);exit;
        $booking_data['package'] = $packages[0];
        $booking_data['pax'] = $pax_data;
        $booking_data['tours'] = json_decode($package_details[0]->tour_day_lists);
        $booking_data['hotels'] = $room_selected_data;
        $booking_data['rooms'] = explode('###', $package_details[0]->room_data);
        $booking_data['transfers'] = json_decode(base64_decode($package_details[0]->transfer_data));
        $booking_data['flight_data'] = json_decode(base64_decode($package_details[0]->flight_data));
        $booking_data['request'] = $package_details[0]->request;
        // echo "<pre>";print_r($booking_data);exit;
        $voucher = $this->load->view('vouchers/package_voucher', $booking_data, true);
        $client[] = $booking_data['voucher']->lead_pax_email;
        if ($this->AGENTS_USER_TYPE_ID == 1) {
            $user_email = $this->get_user_email($packages[0]->master_user_id);
            $client[] = $user_email->user_email;
            if ($this->USER_TYPE_ID == 8) {
                $user_email = $this->get_user_email($packages[0]->user_id);
                $client[] = $user_email->user_email;
            }
        } elseif ($this->AGENTS_USER_TYPE_ID == 9) {
            $user_email = $this->get_employee_email($packages[0]->user_id);
            $client[] = $user_email->user_email;
        }
        $a = explode(',', $success_temp->to_email);
        foreach ($a as $b) {
            $ccmail[] = $b;
        }
        $booking[] = 'packages@falcon-travel.com';
        $user_content = 'Thank you For choosing Falcon Luxury Travel services';
        $hotel_content = 'Please confirm The following Booking informations ASAP';
        // Voucher
        //$voucher = $this->load->view('vouchers/hotel_voucher', $booking_data, true);
        $this->mailconf('Booking Voucher', $voucher, $success_temp->email_from, $success_temp->email_from_name, $ccmail, '');
        $this->mailconf('Booking Voucher', $user_content . '<br>' . $voucher, $success_temp->email_from, $success_temp->email_from_name, $client, $ccmail);
        //$this->mailconf('Booking Voucher', $hotel_content . '<br>'.$voucher, $success_temp->email_from, $success_temp->email_from_name, $hotel, $ccmail);
        $this->mailconf('Booking Voucher', $voucher, $success_temp->email_from, $success_temp->email_from_name, $booking, $ccmail);
        // Voucher
        
    }
    public function call_tour_mails($global_id, $booking_id) {
        $success_temp = $this->booking_success_email_template();
        $booking_data['tour'] = $tour = $this->Booking_Model->get_booking_tour_data($global_id, $booking_id);
        $booking_data['passenger'] = $this->Booking_Model->getBookingpassengerTemp($booking_id, 6)->result();
        $voucher = $this->load->view('vouchers/tour_voucher', $booking_data, true);
        $client[] = $tour[0]->lead_pax_email;
        if ($this->AGENTS_USER_TYPE_ID == 1) {
            $user_email = $this->get_user_email($tour[0]->master_user_id);
            $client[] = $user_email->user_email;
            if ($this->USER_TYPE_ID == 8) {
                $user_email = $this->get_user_email($tour[0]->user_id);
                $client[] = $user_email->user_email;
            }
        } elseif ($this->AGENTS_USER_TYPE_ID == 9) {
            $user_email = $this->get_employee_email($tour[0]->user_id);
            $client[] = $user_email->user_email;
        }
        $a = explode(',', $success_temp->to_email);
        foreach ($a as $b) {
            $ccmail[] = $b;
        }
        $booking[] = 'transport@falcon-travel.com';
        $user_content = 'Thank you For choosing Falcon Luxury Travel services';
        $this->mailconf('Booking Voucher', $voucher, $success_temp->email_from, $success_temp->email_from_name, $ccmail, '');
        $this->mailconf('Booking Voucher', $user_content . '<br>' . $voucher, $success_temp->email_from, $success_temp->email_from_name, $client, $ccmail);
        $this->mailconf('Booking Voucher', $voucher, $success_temp->email_from, $success_temp->email_from_name, $booking, $ccmail);
    }
    public function call_transfer_mails($global_id, $booking_id) {
        $success_temp = $this->booking_success_email_template();
        $booking_data['transfer'] = $transfer = $this->Booking_Model->get_booking_transfer_data($global_id, $booking_id);
        $booking_data['passenger'] = $this->Booking_Model->getBookingpassengerTemp($booking_id, 3)->result();
        $voucher = $this->load->view('vouchers/transfer_voucher', $booking_data, true);
        $client[] = $transfer[0]->lead_pax_email;
        if ($this->AGENTS_USER_TYPE_ID == 1) {
            $user_email = $this->get_user_email($transfer[0]->master_user_id);
            $client[] = $user_email->user_email;
            if ($this->USER_TYPE_ID == 8) {
                $user_email = $this->get_user_email($transfer[0]->user_id);
                $client[] = $user_email->user_email;
            }
        } elseif ($this->AGENTS_USER_TYPE_ID == 9) {
            $user_email = $this->get_employee_email($transfer[0]->user_id);
            $client[] = $user_email->user_email;
        }
        $a = explode(',', $success_temp->to_email);
        foreach ($a as $b) {
            $ccmail[] = $b;
        }
        $booking[] = 'transport@falcon-travel.com';
        $user_content = 'Thank you For choosing Falcon Luxury Travel services';
        $this->mailconf('Booking Voucher', $voucher, $success_temp->email_from, $success_temp->email_from_name, $ccmail, '');
        $this->mailconf('Booking Voucher', $user_content . '<br>' . $voucher, $success_temp->email_from, $success_temp->email_from_name, $client, $ccmail);
        $this->mailconf('Booking Voucher', $voucher, $success_temp->email_from, $success_temp->email_from_name, $booking, $ccmail);
    }
}
?>