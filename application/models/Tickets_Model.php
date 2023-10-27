<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Tickets_Model extends CI_Model
{

    function get_sell_tickets_by_match($match_id){ 
      
        $this->db->select('s_no,status');
        $this->db->where('match_id', $match_id);
        if($this->session->userdata('role') != 6){
            $this->db->where('sell_tickets.add_by',$this->session->userdata('admin_id'));
        }
        $result = $this->db->get('sell_tickets');
        return $result->result();
        
    }

    

    function bulk_ticket_matches() {
        
        $admin_id = $this->session->userdata('admin_id');
        $this->db->select("match_info.*,stadium.stadium_name,tournament_lang.tournament_name,DATE_FORMAT(match_date,'%d %M %Y') as match_date_format,match_info_lang.match_id,match_info_lang.match_name,match_info_lang.language,match_info_lang.extra_title,match_info_lang.description,match_info_lang.meta_title,match_info_lang.meta_description,match_info_lang.event_image");
        $this->db->join('seller_whish_list', 'seller_whish_list.match_id = match_info.m_id', 'left');
        $this->db->join('match_info_lang', 'match_info_lang.match_id = match_info.m_id', 'left');
        $this->db->join('tournament_lang', 'tournament_lang.tournament_id = match_info.tournament', 'left');
        $this->db->join('tournament', 'tournament_lang.tournament_id = tournament.t_id', 'left');
        $this->db->join('stadium', 'stadium.s_id = match_info.venue', 'left');

        $this->db->join('match_settings', 'match_settings.matches = match_info.m_id', 'left');
        $this->db->where("(!FIND_IN_SET('".$admin_id."',match_settings.sellers)  OR  match_settings.sellers IS NULL)", null, false);
        
        $this->db->where('match_info.match_date >= ', date("Y-m-d H:i"));
        $this->db->where('match_info.status', 1);
        $this->db->where('tournament.status', 1);
        $this->db->where('seller_whish_list.status', 0);
        $this->db->where('seller_whish_list.seller_id', $admin_id);
        $this->db->where('match_info_lang.language', 'en');
        $this->db->where('tournament_lang.language', 'en');
        $this->db->order_by('match_info.match_date', 'ASC');
        $this->db->group_by('seller_whish_list.match_id');
        $result = $this->db->get('match_info');
    //echo $this->db->last_query();exit;
        return $result->result();
    }

     function get_bulk_events($search="",$row_no=1, $row_per_page=50) {
        
        $admin_id = $this->session->userdata('admin_id');
        $this->db->select("match_info.*,stadium.stadium_name,tournament_lang.tournament_name,DATE_FORMAT(match_date,'%d %M %Y') as match_date_format,match_info_lang.match_id,match_info_lang.match_name,match_info_lang.language,match_info_lang.extra_title,match_info_lang.description,match_info_lang.meta_title,match_info_lang.meta_description,match_info_lang.event_image,(SUM(s1.quantity 
             ) ) as tickets_available,MAX(s1.price) as max_fare, MIN(s1.price) as min_fare,s1.price_type as ticket_currency");
        $this->db->join('sell_tickets s1', 's1.match_id = match_info.m_id AND s1.status !=2 AND s1.user_id = '.$admin_id, 'left');
        $this->db->join('match_info_lang', 'match_info_lang.match_id = match_info.m_id', 'left');
        $this->db->join('tournament_lang', 'tournament_lang.tournament_id = match_info.tournament', 'left');
        $this->db->join('tournament', 'tournament_lang.tournament_id = tournament.t_id', 'left');
        $this->db->join('stadium', 'stadium.s_id = match_info.venue', 'left');
        $this->db->join('match_settings', 'match_settings.matches = match_info.m_id', 'left');
        $this->db->where("(!FIND_IN_SET('".$admin_id."',match_settings.sellers)  OR  match_settings.sellers IS NULL)", null, false);
        $this->db->where('match_info.match_date >= ', date("Y-m-d H:i"));
        $this->db->where('match_info.status', 1);
        $this->db->where('tournament.status', 1);
        $this->db->where('match_info_lang.language', 'en');
        $this->db->where('tournament_lang.language', 'en');
        if($search['tournament']){
            $this->db->where('tournament_lang.tournament_id', $search['tournament']);
        }
        if($search['event_start_date'] != "" && $search['event_end_date'] != ""){

            $this->db->where('match_info.match_date >= ', date("Y-m-d H:i",strtotime($search['event_start_date'])));
            $this->db->where('match_info.match_date < ', date("Y-m-d H:i",strtotime($search['event_end_date'])));
        }
        if($search['keywords']){
            $this->db->group_start();
            $this->db->like('match_info_lang.match_name',$search['keywords'] );
            $this->db->or_like('tournament_lang.tournament_name',$search['keywords'] );
            $this->db->or_like('match_info.search_keywords',$search['keywords']);
            $this->db->or_like('stadium.stadium_name',$search['keywords']);
            $this->db->group_end();
        }
        $this->db->order_by('match_info.match_date', 'ASC');
        $this->db->group_by('match_info.m_id');
        if ($row_per_page != '' && $search['keywords'] == "") {
            //$this->db->order_by('booking_global.created_at', 'DESC');
            $this->db->limit($row_per_page, $row_no);
        }
        $result = $this->db->get('match_info');
        //echo $this->db->last_query();exit;
        return $result->result();
    }

     function getallMatch($keywords="") {
        
        /*$get_mtch = $this->db->query("SELECT match_info.*,tournament.tournament_name,DATE_FORMAT(match_date,'%d %M %Y') as match_date_format,match_info_lang.match_id,match_info_lang.match_name,match_info_lang.language,match_info_lang.extra_title,match_info_lang.description,match_info_lang.meta_title,match_info_lang.meta_description,match_info_lang.event_image FROM match_info,match_info_lang
            INNER JOIN tournament ON tournament.t_id=match_info.tournament
         where match_info.m_id = match_info_lang.match_id and match_date > '".date("Y-m-d H:i")."' and language = '".$this->session->userdata('language_code')."' order by match_date asc")->result();
        return $get_mtch;*/
        $twhere =  "FIND_IN_SET('".$this->session->userdata('admin_id')."',tournament_settings.sellers)";
        $mwhere =  "FIND_IN_SET('".$this->session->userdata('admin_id')."',match_settings.sellers)";



        $this->db->select("match_info.*,tournament_lang.tournament_name,DATE_FORMAT(match_date,'%d %M %Y') as match_date_format,match_info_lang.match_id,match_info_lang.match_name,match_info_lang.language,match_info_lang.extra_title,match_info_lang.description,match_info_lang.meta_title,match_info_lang.meta_description,match_info_lang.event_image");
        $this->db->join('match_info_lang', 'match_info_lang.match_id = match_info.m_id', 'left');
        $this->db->join('tournament_lang', 'tournament_lang.tournament_id = match_info.tournament', 'left');
       /* $this->db->join('tournament_settings', 'tournament_settings.tournaments = match_info.tournament', 'left');
        $this->db->join('match_settings', 'match_settings.matches = match_info.m_id', 'left');*/
        $this->db->where('match_info.match_date >= ', date("Y-m-d H:i"));
        $this->db->where('match_info.status', 1);
        $this->db->where('match_info_lang.language', $this->session->userdata('language_code'));
        $this->db->where('tournament_lang.language', $this->session->userdata('language_code'));

        $this->db->join('match_settings', 'match_settings.matches = match_info.m_id', 'left');
        $admin_id = $this->session->userdata('admin_id');
        $this->db->where("(!FIND_IN_SET('".$admin_id."',match_settings.sellers)  OR  match_settings.sellers IS NULL)", null, false);

        //$this->db->where_in('tournament_settings.sellers', [11]);
       // $this->db->where_in('match_settings.sellers', [11]);
        //$this->db->where("FIND_IN_SET(11,tournament_settings.sellers)",null,false);
        //$this->db->where("FIND_IN_SET(11,match_settings.sellers)",null,false);
       /*$this->db->where("FIND_IN_SET('".$this->session->userdata('admin_id')."',tournament_settings.sellers) !=", 0);
         $this->db->where("FIND_IN_SET('".$this->session->userdata('admin_id')."',match_settings.sellers) !=", 0);*/
        $this->db->order_by('match_info.match_date', 'ASC');

        if($this->session->userdata('admin_id') != 1 && $this->session->userdata('admin_id') != 25 && $this->session->userdata('admin_id') != 212){
           // if($this->session->userdata('admin_id') != 1 && ($this->session->userdata('admin_id') != 25 && $this->session->userdata('admin_id') != 11)){
        $this->db->where('tournament_lang.tournament_id != ', 19);
        }
        if($keywords){
            $this->db->group_start();
            $this->db->like('match_info_lang.match_name',$keywords );
            $this->db->or_like('tournament_lang.tournament_name',$keywords );
            $this->db->or_like('match_info.match_date',date("Y-m-d", strtotime($keywords) ));
            $this->db->or_like('match_info.search_keywords',$keywords );
            $this->db->group_end();
        }
         $this->db->group_by('match_info.m_id');
        $result = $this->db->get('match_info');
      //  echo $this->db->last_query();exit;
        return $result->result();
    }
    
 function getallMatch_oe($keywords="") {
        if($this->session->userdata('admin_id') == 25){
        $twhere =  "FIND_IN_SET('".$this->session->userdata('admin_id')."',tournament_settings.sellers)";
        $mwhere =  "FIND_IN_SET('".$this->session->userdata('admin_id')."',match_settings.sellers)";
        $this->db->select("match_info.*,otherevent_category_lang.category_name,DATE_FORMAT(match_date,'%d %M %Y') as match_date_format,match_info_lang.match_id,match_info_lang.match_name,match_info_lang.language,match_info_lang.extra_title,match_info_lang.description,match_info_lang.meta_title,match_info_lang.meta_description,match_info_lang.event_image");
        $this->db->join('match_info_lang', 'match_info_lang.match_id = match_info.m_id', 'left');
        $this->db->join('otherevent_category', 'otherevent_category.id = match_info.other_event_category', 'left');
        $this->db->join('otherevent_category_lang', 'otherevent_category_lang.other_event_cat_id = otherevent_category.id', 'left');

        $this->db->join('match_settings', 'match_settings.matches = match_info.m_id', 'left');
        $admin_id = $this->session->userdata('admin_id');
        $this->db->where("(!FIND_IN_SET('".$admin_id."',match_settings.sellers)  OR  match_settings.sellers IS NULL)", null, false);
        
        $this->db->where('match_info.match_date >= ', date("Y-m-d H:i"));
        $this->db->where('match_info.status', 1);
        $this->db->where('match_info_lang.language', $this->session->userdata('language_code'));
        $this->db->where('otherevent_category_lang.language', $this->session->userdata('language_code'));
        $this->db->order_by('match_info.match_date', 'ASC');

        if($keywords){
            $this->db->group_start();
            $this->db->like('match_info_lang.match_name',$keywords );
           // $this->db->or_like('tournament_lang.tournament_name',$keywords );
            $this->db->or_like('match_info.match_date',date("Y-m-d", strtotime($keywords) ));
            $this->db->or_like('match_info.search_keywords',$keywords );
            $this->db->group_end();
        }
          $this->db->where('match_info.event_type','other');
        $this->db->group_by('match_info.m_id');
        $result = $this->db->get('match_info');
        //echo $this->db->last_query();exit;
        return $result->result();
    }
    else{
        return array();
    }
    }
    
	 function getallMatch__old($keywords="") {
        
		/*$get_mtch = $this->db->query("SELECT match_info.*,tournament.tournament_name,DATE_FORMAT(match_date,'%d %M %Y') as match_date_format,match_info_lang.match_id,match_info_lang.match_name,match_info_lang.language,match_info_lang.extra_title,match_info_lang.description,match_info_lang.meta_title,match_info_lang.meta_description,match_info_lang.event_image FROM match_info,match_info_lang
            INNER JOIN tournament ON tournament.t_id=match_info.tournament
         where match_info.m_id = match_info_lang.match_id and match_date > '".date("Y-m-d H:i")."' and language = '".$this->session->userdata('language_code')."' order by match_date asc")->result();
        return $get_mtch;*/

        $this->db->select("match_info.*,tournament_lang.tournament_name,DATE_FORMAT(match_date,'%d %M %Y') as match_date_format,match_info_lang.match_id,match_info_lang.match_name,match_info_lang.language,match_info_lang.extra_title,match_info_lang.description,match_info_lang.meta_title,match_info_lang.meta_description,match_info_lang.event_image");
        $this->db->join('match_info_lang', 'match_info_lang.match_id = match_info.m_id', 'left');
        $this->db->join('tournament_lang', 'tournament_lang.tournament_id = match_info.tournament', 'left');
        $this->db->where('match_info.match_date >= ', date("Y-m-d H:i"));
        $this->db->where('match_info.status', 1);
        $this->db->where('match_info_lang.language', $this->session->userdata('language_code'));
        $this->db->where('tournament_lang.language', $this->session->userdata('language_code'));
        $this->db->order_by('match_info.match_date', 'ASC');
        if($this->session->userdata('admin_id') != 1 && $this->session->userdata('admin_id') != 25){
        $this->db->where('tournament_lang.tournament_id != ', 19);
        }
        if($keywords){
            $this->db->group_start();
            $this->db->like('match_info_lang.match_name',$keywords );
            $this->db->or_like('tournament_lang.tournament_name',$keywords );
            $this->db->or_like('match_info.match_date',date("Y-m-d", strtotime($keywords) ));
            $this->db->or_like('match_info.search_keywords',$keywords );
            $this->db->group_end();
        }

        $result = $this->db->get('match_info');
        return $result->result();
    }

    function getallMatch_ById($match_id="") {  
        
       
		$get_mtch = $this->db->query("SELECT match_info.*,DATE_FORMAT(match_date,'%d %M %Y') as match_date_format,match_info_lang.match_id,match_info_lang.match_name,match_info_lang.language,match_info_lang.extra_title,match_info_lang.description,match_info_lang.meta_title,match_info_lang.meta_description,match_info_lang.event_image FROM match_info,match_info_lang where match_info.m_id = match_info_lang.match_id and language = '".$this->session->userdata('language_code')."' and match_info.m_id = '".$match_id."' order by match_info.m_id asc")->result();
        return $get_mtch;
    }

    function tkt_category($venue="") {  
        
        $this->db->select('stadium_details.category,stadium_seats_lang.seat_category,stadium_details.block_color');
        $this->db->join('stadium_seats_lang', 'stadium_seats_lang.stadium_seat_id = stadium_details.category', 'left');
        $this->db->join('stadium_seats', 'stadium_seats.id = stadium_seats_lang.stadium_seat_id', 'left');
        $this->db->where('stadium_details.stadium_id', $venue);
        $this->db->where('stadium_seats_lang.language', $this->session->userdata('language_code'));
         $this->db->where('stadium_seats.source_type', '1boxoffice');
         $this->db->where('stadium_details.source_type', '1boxoffice');
        $this->db->group_by('stadium_details.category');
        $this->db->order_by('stadium_seats_lang.seat_category','ASC');
        $result = $this->db->get('stadium_details');//echo $this->db->last_query();exit;
        return $result->result();
      /*  $tkt_category = $this->db->query("SELECT stadium_details.category,stadium_seats_lang.seat_category,stadium_details.block_color,stadium_details.stadium_id FROM `stadium_details`,`stadium_seats_lang` WHERE stadium_details.category = stadium_seats_lang.stadium_seat_id and stadium_id = '".$venue."' and language = '".$this->session->userdata('language_code')."' group by stadium_details.category Order by stadium_seats_lang.seat_category ASC")->result();
        return $tkt_category;*/
    }

    function getMatchAdditionalInfo($match_id) {
        $this->db->select('m.*, t.tournament_name, c.name as city_name, s.name as state_name, cn.name as country_name, st.stadium_type, st.stadium_image,st.stadium_name,st.map_code as stadium_svg');
        $this->db->join('tournament t', 't.t_id = m.tournament', 'left');
        $this->db->join('cities c', 'c.id = m.city', 'left');
        $this->db->join('states s', 's.id = m.state', 'left');
        $this->db->join('countries cn', 'cn.id = m.country', 'left');
        $this->db->join('stadium st', 'st.s_id = m.venue', 'left');
        $this->db->where('m.m_id', $match_id);
        $result = $this->db->get('match_info m');
        return $result->row();
    }

     function partner_enquiry_details($row_no='', $row_per_page='', $orderColumn = '', $orderby = '', $where_array = array(), $search = ''){ 
        $this->db->select('*');
        $this->db->order_by('partner_enquiry.id', 'DESC');
        if (!empty($where_array)) {
            foreach ($where_array as $columnkey => $value) {
                $this->db->where($columnkey, $value);
            }
        }
        if ($orderColumn != "" && $orderby != "") {
            $this->db->order_by($orderColumn, $orderby);
        }
        if ($row_per_page != '') {
            $this->db->limit($row_per_page, $row_no);
        }
        $result = $this->db->get('partner_enquiry');
      //  echo $this->db->last_query();exit;
        return $result;
    }


     function contact_details($row_no='', $row_per_page='', $orderColumn = '', $orderby = '', $where_array = array(), $search = ''){ 
        $this->db->select('contact_details.*,contact_details.status as contact_status,contact_details.id as contact_id,countries.name as country_name');
        $this->db->join('countries', 'countries.id = contact_details.country', 'left');
        $this->db->order_by('contact_details.id', 'DESC');
        if (!empty($where_array)) {
            foreach ($where_array as $columnkey => $value) {
                $this->db->where($columnkey, $value);
            }
        }
        if ($orderColumn != "" && $orderby != "") {
            $this->db->order_by($orderColumn, $orderby);
        }
        if ($row_per_page != '') {
            $this->db->limit($row_per_page, $row_no);
        }
        $result = $this->db->get('contact_details');
      //  echo $this->db->last_query();exit;
        return $result;
    }

    function getAllTickets() {
        $this->db->select('*');
        $this->db->join('match_info_lang ml', 'ml.match_id = m.m_id', 'left');
        $this->db->join('sell_tickets st', 'st.match_id = m.m_id', 'left');
        $this->db->join('stadium sd', 'sd.s_id = m.venue', 'left');
        $this->db->group_by('st.match_id');
        $this->db->where('m.match_date >= ', date("Y-m-d H:i"));
        $this->db->order_by('m.match_date', 'asc');
        if($this->session->userdata('role') != 6){
            $this->db->where('st.add_by',$this->session->userdata('admin_id'));
        }
        $result = $this->db->get('match_info m');
        //echo $this->db->last_query();exit;
        return $result->result();
    }

    public function get_expired_tickets()
    {

        $this->db->select('sell_tickets.s_no')->from('sell_tickets')
        ->join('match_info', 'match_info.m_id = sell_tickets.match_id', 'left');
        $this->db->where('match_info.match_date < ', date("Y-m-d H:i"));
        $this->db->where('sell_tickets.status', 1);
        $query = $this->db->get();
        return $query;
    }

    function getListing_details($match_id) {
        
        $this->db->select('m.m_id,ml.match_name,m.match_date,m.match_time,m.event_type,m.venue,c.name as country_name,cit.name as city_name');
        $this->db->join('match_info_lang ml', 'ml.match_id = m.m_id');
        $this->db->join('sell_tickets st', 'st.match_id = m.m_id');
        $this->db->join('stadium sd', 'sd.s_id = m.venue');
        $this->db->join('tournament_lang td', 'td.tournament_id = m.tournament');
        $this->db->join('countries c', 'c.id = m.country');
        $this->db->join('cities cit', 'cit.id = m.city','LEFT');
        //$this->db->where('st.status', 1);
        $this->db->where('st.status !=', 2);
        if($match_id) {
            $this->db->where('ml.match_id', $match_id);
        }
        $this->db->group_by('st.match_id');
        $this->db->order_by('m.match_date', 'asc');
         $this->db->order_by('st.price', 'asc');
         
        if($this->session->userdata('role') != 6){
            $this->db->where('st.add_by',$this->session->userdata('admin_id'));
        } //echo $this->session->userdata('admin_id');exit;
        if($this->session->userdata('role') == 6 && $this->session->userdata('seller_id') != ''){
            $this->db->where('st.add_by',$this->session->userdata('seller_id'));
        }
         //$this->db->where('st.status != ',2);
        if ($orderColumn != "" && $orderby != "") {
            $this->db->order_by($orderColumn, $orderby);
        }
        $this->db->where('td.language',$this->session->userdata('language_code'));
        if ($row_per_page != '') {
            $this->db->limit($row_per_page, $row_no);
        }
        $result = $this->db->get('match_info m');
      // echo $this->db->last_query();exit;
        return $result->result();
    }

    function getListing_details_oe($match_id) {
        
        $this->db->select('m.m_id,ml.match_name,m.match_date,m.match_time,m.venue,m.event_type,c.name as country_name,cit.name as city_name');
        $this->db->join('match_info_lang ml', 'ml.match_id = m.m_id');
        $this->db->join('sell_tickets st', 'st.match_id = m.m_id');
        $this->db->join('stadium sd', 'sd.s_id = m.venue');
        $this->db->join('otherevent_category_lang oc', 'oc.other_event_cat_id = m.other_event_category','LEFT');
        $this->db->join('countries c', 'c.id = m.country');
        $this->db->join('cities cit', 'cit.id = m.city','LEFT');
        //$this->db->where('st.status', 1);
        $this->db->where('st.status !=', 2);
        if($match_id) {
            $this->db->where('ml.match_id', $match_id);
        }
        $this->db->group_by('st.match_id');
        $this->db->order_by('m.match_date', 'asc');
         $this->db->order_by('st.price', 'asc');
         
        if($this->session->userdata('role') != 6){
            $this->db->where('st.add_by',$this->session->userdata('admin_id'));
        } //echo $this->session->userdata('admin_id');exit;
        if($this->session->userdata('role') == 6 && $this->session->userdata('seller_id') != ''){
            $this->db->where('st.add_by',$this->session->userdata('seller_id'));
        }
         //$this->db->where('st.status != ',2);
        if ($orderColumn != "" && $orderby != "") {
            $this->db->order_by($orderColumn, $orderby);
        }
        $this->db->where('oc.language',$this->session->userdata('language_code'));
        if ($row_per_page != '') {
            $this->db->limit($row_per_page, $row_no);
        }
        $result = $this->db->get('match_info m');
       //echo $this->db->last_query();exit;
        return $result->result();
    }

     function ticket_keyword_search($search = '',$match_type='') {
       
        $this->db->select('m.m_id,ml.match_name,m.match_date,m.match_time,m.event_type,c.name as country_name,cit.name as city_name');
        $this->db->join('match_info_lang ml', 'ml.match_id = m.m_id');
        $this->db->join('sell_tickets st', 'st.match_id = m.m_id');
        $this->db->join('stadium sd', 'sd.s_id = m.venue');
        $this->db->join('tournament_lang td', 'td.tournament_id = m.tournament');
        $this->db->join('countries c', 'c.id = m.country');
        $this->db->join('cities cit', 'cit.id = m.city','LEFT');
        $this->db->join('stadium_seats_lang stdseat', 'stdseat.stadium_seat_id = st.ticket_category','LEFT');
        $this->db->group_by('st.match_id');
        $this->db->order_by('m.match_date', 'asc');
         $this->db->order_by('st.price', 'asc');
         $this->db->where('st.quantity >', 0);
        if($this->session->userdata('role') != 6){
            $this->db->where('st.add_by',$this->session->userdata('admin_id'));
        } //echo $this->session->userdata('admin_id');exit;
        if($this->session->userdata('role') == 6 && $this->session->userdata('seller_id') != ''){
            $this->db->where('st.add_by',$this->session->userdata('seller_id'));
        }

          if($match_type == "upcoming") {
            $this->db->where('m.match_date >= ', date("Y-m-d H:i"));
            $this->db->where('st.status != ',2);
        } else if($match_type == "expired") {
            $this->db->where('m.match_date < ', date("Y-m-d H:i"));
            $this->db->where('st.status != ',2);
        }
        else if($match_type == "publish") {
            $this->db->where('st.status', 1);
        }
        else if($match_type == "unpublish") {
            $this->db->where('st.status', 0);
        }
        else if($match_type == "deleted") {
            $this->db->where('st.status', 2);
        }
        else{
            $this->db->where('st.status != ',2);
        }
        $this->db->where('m.match_date >= ', date("Y-m-d H:i"));
       /* if($match_type == "") {
         $this->db->where('st.status',1);
        } */
         
        if($search != '') {
            $this->db->group_start();
            $this->db->like('ml.match_name',$search, 'both'); 
            $this->db->or_like('td.tournament_name', $search, 'both');
            $this->db->or_like('sd.stadium_name', $search, 'both');
            $this->db->or_like('cit.name', $search, 'both');
            $this->db->or_like('stdseat.seat_category', $search, 'both');
            $this->db->or_like('st.ticket_group_id', $search, 'both');
            $this->db->or_like('m.search_keywords',$search, 'both');
            $this->db->group_end();
        }
         
        if ($orderColumn != "" && $orderby != "") {
            $this->db->order_by($orderColumn, $orderby);
        }
        $this->db->where('td.language',$this->session->userdata('language_code'));
        if ($row_per_page != '') {
            $this->db->limit($row_per_page, $row_no);
        }
        $result = $this->db->get('match_info m');
      // echo $this->db->last_query();exit;
        return $result->result();
    }

    function ticket_keyword_search_oe($search = '',$match_type='') {
       
        $this->db->select('*,c.name as country_name,cit.name as city_name');
        $this->db->join('match_info_lang ml', 'ml.match_id = m.m_id');
        $this->db->join('sell_tickets st', 'st.match_id = m.m_id');
        $this->db->join('stadium sd', 'sd.s_id = m.venue');
        $this->db->join('otherevent_category_lang oc', 'oc.other_event_cat_id = m.other_event_category');
        $this->db->join('countries c', 'c.id = m.country');
        $this->db->join('cities cit', 'cit.id = m.city','LEFT');
        $this->db->join('stadium_seats_lang stdseat', 'stdseat.stadium_seat_id = st.ticket_category','LEFT');
        $this->db->group_by('st.match_id');
        $this->db->order_by('m.match_date', 'asc');
         $this->db->order_by('st.price', 'asc');
        if($this->session->userdata('role') != 6){
            $this->db->where('st.add_by',$this->session->userdata('admin_id'));
        } //echo $this->session->userdata('admin_id');exit;
        if($this->session->userdata('role') == 6 && $this->session->userdata('seller_id') != ''){
            $this->db->where('st.add_by',$this->session->userdata('seller_id'));
        }

          if($match_type == "upcoming") {
            $this->db->where('m.match_date >= ', date("Y-m-d H:i"));
            $this->db->where('st.status != ',2);
        } else if($match_type == "expired") {
            $this->db->where('m.match_date < ', date("Y-m-d H:i"));
            $this->db->where('st.status != ',2);
        }
        else if($match_type == "publish") {
            $this->db->where('st.status', 1);
        }
        else if($match_type == "unpublish") {
            $this->db->where('st.status', 0);
        }
        else if($match_type == "deleted") {
            $this->db->where('st.status', 2);
        }
        else{
            $this->db->where('st.status != ',2);
        }
        $this->db->where('m.match_date >= ', date("Y-m-d H:i"));
        //$this->db->where('m.match_date >= ', date("Y-m-d H:i"));
        $this->db->where('oc.language',$this->session->userdata('language_code'));
       /* if($match_type == "") {
         $this->db->where('st.status',1);
        } */
         
        if($search != '') {
            $this->db->group_start();
            $this->db->like('ml.match_name',$search, 'both'); 
            $this->db->or_like('sd.stadium_name', $search, 'both');
            $this->db->or_like('cit.name', $search, 'both');
            $this->db->or_like('stdseat.seat_category', $search, 'both');
            $this->db->or_like('st.ticket_group_id', $search, 'both');
            $this->db->or_like('m.search_keywords',$search, 'both');
            $this->db->group_end();
        }
         
        if ($orderColumn != "" && $orderby != "") {
            $this->db->order_by($orderColumn, $orderby);
        }
        if ($row_per_page != '') {
            $this->db->limit($row_per_page, $row_no);
        }
        $this->db->group_by('m.m_id');
        $result = $this->db->get('match_info m');
     // echo $this->db->last_query();exit;
        return $result->result();
    }


    function getListing_details_v1($match_id,$ticketid) {
        
        $this->db->select('*,c.name as country_name,cit.name as city_name');
        $this->db->join('match_info_lang ml', 'ml.match_id = m.m_id');
        $this->db->join('sell_tickets st', 'st.match_id = m.m_id');
        $this->db->join('stadium sd', 'sd.s_id = m.venue');
        $this->db->join('tournament_lang td', 'td.tournament_id = m.tournament');
        $this->db->join('countries c', 'c.id = m.country');
        $this->db->join('cities cit', 'cit.id = m.city','LEFT');
        if($match_id) {
            $this->db->where('ml.match_id', $match_id);
        }
        if($ticketid) {
            $this->db->where('st.s_no', $ticketid);
        }
        $this->db->group_by('st.match_id');
        $this->db->order_by('m.match_date', 'asc');
         $this->db->order_by('st.price', 'asc');
        if($this->session->userdata('role') != 6){
            $this->db->where('st.add_by',$this->session->userdata('admin_id'));
        } //echo $this->session->userdata('admin_id');exit;
        if($this->session->userdata('role') == 6 && $this->session->userdata('seller_id') != ''){
            $this->db->where('st.add_by',$this->session->userdata('seller_id'));
        }
         $this->db->where('st.status != ',2);
        if ($orderColumn != "" && $orderby != "") {
            $this->db->order_by($orderColumn, $orderby);
        }
        $this->db->where('td.language',$this->session->userdata('language_code'));
        if ($row_per_page != '') {
            $this->db->limit($row_per_page, $row_no);
        }
        $result = $this->db->get('match_info m');
       //echo $this->db->last_query();exit;
        return $result->result();
    }


     function getListing($event_search = "", $ticket_category_search = "", $stadium_search = "", $event_status = "", $start_date = "", $end_date = "",$row_no = '', $row_per_page = '', $orderColumn = '', $orderby = '',$match_id='') {
        
        $this->db->select('*,c.name as country_name,cit.name as city_name');
        $this->db->join('match_info_lang ml', 'ml.match_id = m.m_id');
        $this->db->join('sell_tickets st', 'st.match_id = m.m_id');
        $this->db->join('stadium sd', 'sd.s_id = m.venue');
        $this->db->join('tournament_lang td', 'td.tournament_id = m.tournament');
        $this->db->join('countries c', 'c.id = m.country');
        $this->db->join('cities cit', 'cit.id = m.city','LEFT');
        $this->db->join('ticket_types_lang ty', 'ty.ticket_type_id = st.ticket_type','LEFT');

        if($event_search) {
            $this->db->where('ml.match_name LIKE ', '%'.$event_search.'%');
        }
        if($match_id) {
            $this->db->where('ml.match_id', $match_id);
        }

        if($ticket_category_search) {
            $this->db->where('ty.name LIKE ', '%'.$ticket_category_search.'%');
        }

        if($stadium_search) {
            $this->db->where('sd.stadium_name LIKE ', '%'.$stadium_search.'%');
        }

        if($event_status == "upcoming" && $start_date == '') {
            $this->db->where('m.match_date >= ', date("Y-m-d H:i"));
        } else if($event_status == "expired") {
            $this->db->where('m.match_date < ', date("Y-m-d H:i"));
        }
        else if($event_status != "") {
           // $this->db->where('m.match_date < ', date("Y-m-d H:i"));
        }

        if($start_date) {
            $this->db->where('m.match_date >= ', date("Y-m-d 00:00", strtotime($start_date)));
        }
        
        if($end_date) {
            $this->db->where('m.match_date < ', date("Y-m-d 23:59", strtotime($end_date)));
        }

        $this->db->group_by('st.match_id');
        //$this->db->where('m.match_date >= ', date("Y-m-d H:i"));
        $this->db->order_by('m.match_date', 'asc');
         $this->db->order_by('st.price', 'asc');
        if($this->session->userdata('role') != 6){
            $this->db->where('st.add_by',$this->session->userdata('admin_id'));
        } //echo $this->session->userdata('admin_id');exit;
        if($this->session->userdata('role') == 6 && $this->session->userdata('seller_id') != ''){
            $this->db->where('st.add_by',$this->session->userdata('seller_id'));
        }
         $this->db->where('st.status != ',2);
		if ($orderColumn != "" && $orderby != "") {
			$this->db->order_by($orderColumn, $orderby);
		}
        $this->db->where('td.language',$this->session->userdata('language_code'));
        $this->db->where('ty.language',$this->session->userdata('language_code'));
		if ($row_per_page != '') {
			$this->db->limit($row_per_page, $row_no);
		}
        $result = $this->db->get('match_info m');
       //echo $this->db->last_query();exit;
        return $result->result();
    }

	function getListing_count($event_search = "", $ticket_category_search = "", $stadium_search = "", $event_status = "", $start_date = "", $end_date = "",$row_no = '', $row_per_page = '', $orderColumn = '', $orderby = '',$match_id='') {
        
       $this->db->select('*,c.name as country_name,cit.name as city_name');
        $this->db->join('match_info_lang ml', 'ml.match_id = m.m_id');
        $this->db->join('sell_tickets st', 'st.match_id = m.m_id');
        $this->db->join('stadium sd', 'sd.s_id = m.venue');
        $this->db->join('tournament_lang td', 'td.tournament_id = m.tournament');
        $this->db->join('countries c', 'c.id = m.country');
        $this->db->join('cities cit', 'cit.id = m.city','LEFT');
        $this->db->join('ticket_types_lang ty', 'ty.ticket_type_id = st.ticket_type','LEFT');

        if($event_search) {
            $this->db->where('ml.match_name LIKE ', '%'.$event_search.'%');
        }
        if($match_id) {
            $this->db->where('ml.match_id', $match_id);
        }

        if($ticket_category_search) {
            $this->db->where('ty.name LIKE ', '%'.$ticket_category_search.'%');
        }

        if($stadium_search) {
            $this->db->where('sd.stadium_name LIKE ', '%'.$stadium_search.'%');
        }

        if($event_status == "upcoming" && $start_date == '') {
            $this->db->where('m.match_date >= ', date("Y-m-d H:i"));
        } else if($event_status == "expired") {
            $this->db->where('m.match_date < ', date("Y-m-d H:i"));
        }
        else if($event_status != "") {
           // $this->db->where('m.match_date < ', date("Y-m-d H:i"));
        }

        if($start_date) {
            $this->db->where('m.match_date >= ', date("Y-m-d 00:00", strtotime($start_date)));
        }
        
        if($end_date) {
            $this->db->where('m.match_date < ', date("Y-m-d 23:59", strtotime($end_date)));
        }

        $this->db->group_by('st.match_id');
        //$this->db->where('m.match_date >= ', date("Y-m-d H:i"));
        $this->db->order_by('m.match_date', 'asc');
         $this->db->order_by('st.price', 'asc');
        if($this->session->userdata('role') != 6){
            $this->db->where('st.add_by',$this->session->userdata('admin_id'));
        } //echo $this->session->userdata('admin_id');exit;
        if($this->session->userdata('role') == 6 && $this->session->userdata('seller_id') != ''){
            $this->db->where('st.add_by',$this->session->userdata('seller_id'));
        }
         $this->db->where('st.status != ',2);
        if ($orderColumn != "" && $orderby != "") {
            $this->db->order_by($orderColumn, $orderby);
        }
        $this->db->where('td.language',$this->session->userdata('language_code'));
        $this->db->where('ty.language',$this->session->userdata('language_code'));
        if ($row_per_page != '') {
            $this->db->limit($row_per_page, $row_no);
        }
        $result = $this->db->get('match_info m');

        return $result->num_rows();
    }

     function ticket_available_quantity($m_id="",$match_type='')
    {
        $this->db->select('sum(sell_tickets.quantity) as tickets_available');
        $this->db->order_by('sell_tickets.price', 'asc');
        $this->db->order_by('sell_tickets.s_no', 'DESC');
        $this->db->where('match_id', $m_id);
       //echo $match_type;exit;

        if($match_type == "publish") {
             $this->db->where('sell_tickets.status',1);
             //$this->db->or_where('st.status !=', 2);
        }
        else if($match_type == "unpublish") {
             $this->db->where('sell_tickets.status',0);
             // $this->db->or_where('st.status !=', 2);
        }
        else if($match_type == "deleted") {
             $this->db->where('sell_tickets.status',2);
        }
        else{
            $this->db->where('sell_tickets.status != ', 2);
        }

        if($this->session->userdata('role') != 6){
            $this->db->where('sell_tickets.add_by',$this->session->userdata('admin_id'));
        }
        $result = $this->db->get('sell_tickets');
        //echo $this->db->last_query();exit;
        return $result;
    }

     function getListing_filter($m_id="",$match_type='') {

        $this->db->select('*');
        $this->db->order_by('sell_tickets.price', 'asc');
        $this->db->order_by('sell_tickets.s_no', 'DESC');
        $this->db->where('match_id', $m_id);
       //echo $match_type;exit;

        if($match_type == "publish") {
             $this->db->where('sell_tickets.status',1);
             //$this->db->or_where('st.status !=', 2);
        }
        else if($match_type == "unpublish") {
             $this->db->where('sell_tickets.status',0);
             // $this->db->or_where('st.status !=', 2);
        }
        else if($match_type == "deleted") {
             $this->db->where('sell_tickets.status',2);
        }
        else{
            $this->db->where('sell_tickets.status != ', 2);
        }

        if($this->session->userdata('role') != 6){
            $this->db->where('sell_tickets.add_by',$this->session->userdata('admin_id'));
        }
        $result = $this->db->get('sell_tickets');
        //echo $this->db->last_query();exit;
        return $result->result();
        
    }

    function getListing_v1($m_id="") {

        $this->db->select('*');
        $this->db->order_by('sell_tickets.price', 'asc');
        $this->db->order_by('sell_tickets.s_no', 'DESC');
        $this->db->where('match_id', $m_id);
        $this->db->where('sell_tickets.status != ',2);
        if($this->session->userdata('role') != 6){
            $this->db->where('sell_tickets.add_by',$this->session->userdata('admin_id'));
        }
        $result = $this->db->get('sell_tickets');
        
        return $result->result();
        
    }

    function getListing_v3($m_id="",$s_no) {

        $this->db->select('*');
        $this->db->order_by('sell_tickets.price', 'asc');
        $this->db->order_by('sell_tickets.s_no', 'DESC');
        $this->db->where('match_id', $m_id);
        $this->db->where('sell_tickets.s_no', $s_no);
        $this->db->where('sell_tickets.status != ',2);
        $result = $this->db->get('sell_tickets');
        
        return $result->result();
        
    }

    function getListing_v2($ticketid) {
        
        $this->db->select('*,c.name as country_name,cit.name as city_name');
        $this->db->join('match_info_lang ml', 'ml.match_id = m.m_id');
        $this->db->join('sell_tickets st', 'st.match_id = m.m_id');
        $this->db->join('stadium sd', 'sd.s_id = m.venue');
        $this->db->join('tournament td', 'td.t_id = m.tournament');
        $this->db->join('countries c', 'c.id = m.country');
        $this->db->join('cities cit', 'cit.id = m.city');
        $this->db->where('st.ticketid',$ticketid);
        $this->db->order_by('m.match_date', 'asc');
        $this->db->order_by('st.price', 'asc');
        if($this->session->userdata('role') != 6){
            $this->db->where('st.add_by',$this->session->userdata('admin_id'));
        }
        $result = $this->db->get('match_info m');
       //echo $this->db->last_query();exit;
        return $result->row();
    }

     function getListing_v4($ticketid) {
        
        $this->db->select('*,c.name as country_name,cit.name as city_name,st.status as ticket_status');
        $this->db->join('match_info_lang ml', 'ml.match_id = m.m_id');
        $this->db->join('sell_tickets st', 'st.match_id = m.m_id');
        $this->db->join('stadium sd', 'sd.s_id = m.venue');
        $this->db->join('tournament td', 'td.t_id = m.tournament');
        $this->db->join('countries c', 'c.id = m.country');
        $this->db->join('cities cit', 'cit.id = m.city');
        $this->db->where('st.s_no',$ticketid);
        $this->db->order_by('m.match_date', 'asc');
        $this->db->order_by('st.price', 'asc');
        if($this->session->userdata('role') != 6){
            $this->db->where('st.add_by',$this->session->userdata('admin_id'));
        }
        $result = $this->db->get('match_info m');
       //echo $this->db->last_query();exit;
        return $result->row();
    }

     function getListing_v5($ticketid) {
        
        $this->db->select('*,c.name as country_name,cit.name as city_name,st.status as ticket_status');
        $this->db->join('match_info_lang ml', 'ml.match_id = m.m_id');
        $this->db->join('sell_tickets st', 'st.match_id = m.m_id');
        $this->db->join('stadium sd', 'sd.s_id = m.venue');
        //$this->db->join('tournament td', 'td.t_id = m.tournament');
        $this->db->join('countries c', 'c.id = m.country');
        $this->db->join('cities cit', 'cit.id = m.city');
        $this->db->where('st.s_no',$ticketid);
        $this->db->order_by('m.match_date', 'asc');
        $this->db->order_by('st.price', 'asc');
        if($this->session->userdata('role') != 6){
            $this->db->where('st.add_by',$this->session->userdata('admin_id'));
        }
        $result = $this->db->get('match_info m');
       //echo $this->db->last_query();exit;
        return $result->row();
    }

    function getListing_oe($ticketid) {
        
        $this->db->select('*,c.name as country_name,cit.name as city_name,st.status as ticket_status');
        $this->db->join('match_info_lang ml', 'ml.match_id = m.m_id');
        $this->db->join('sell_tickets st', 'st.match_id = m.m_id');
        $this->db->join('stadium sd', 'sd.s_id = m.venue');
        $this->db->join('otherevent_category_lang oc', 'oc.other_event_cat_id = m.other_event_category');
        $this->db->join('countries c', 'c.id = m.country');
        $this->db->join('cities cit', 'cit.id = m.city');
        $this->db->where('st.s_no',$ticketid);
        $this->db->order_by('m.match_date', 'asc');
        $this->db->order_by('st.price', 'asc');
        $this->db->where('oc.language',$this->session->userdata('language_code'));
        if($this->session->userdata('role') != 6){
            $this->db->where('st.add_by',$this->session->userdata('admin_id'));
        }
        $result = $this->db->get('match_info m');
       //echo $this->db->last_query();exit;
        return $result->row();
    }

    function check_match($match_id) {
        
        $this->db->select('m.*');
        $this->db->join('match_info_lang ml', 'ml.match_id = m.m_id');
        $this->db->join('stadium sd', 'sd.s_id = m.venue');
        $this->db->join('otherevent_category_lang oc', 'oc.other_event_cat_id = m.other_event_category');
        $this->db->where('m.m_id',$match_id);
        $this->db->where('oc.language',$this->session->userdata('language_code'));
        $result = $this->db->get('match_info m');
       //echo $this->db->last_query();exit;
        return $result->row();
    }

    
 function e_ticket_files($ticket_id="",$ticket_type=''){ 

        $this->db->select('*');
        $this->db->where('sell_id', $ticket_id);
        if($ticket_type != ""){
        $this->db->where('ticket_type', $ticket_type);
        }
        $result = $this->db->get('e_ticket_files');
        return $result->result();
        
    }

    function get_sell_tickets($ticket_id=""){ 

        $this->db->select('*');
        $this->db->where('s_no', $ticket_id);
        if($this->session->userdata('role') != 6){
            $this->db->where('sell_tickets.add_by',$this->session->userdata('admin_id'));
        }
        $result = $this->db->get('sell_tickets');
        return $result->row();
        
    }

    function get_sell_tickets_pending(){ 

        $this->db->select('*');
        $this->db->where('ticketid', '');
        $result = $this->db->get('sell_tickets');
        return $result->result();
        
    }

    

    

    function ticket_request($id='') {
       /* $this->db->select('request_tickets.*,match_info.*, match_info_lang.*');
        $this->db->join('match_info', 'match_info.m_id = request_tickets.event_id', 'left');
        $this->db->join('match_info_lang', 'match_info_lang.match_id = match_info.m_id', 'left');
        $this->db->where('match_info_lang.language', $this->session->userdata('language_code'));
        $result = $this->db->get('request_tickets');
*/
         $this->db->select('req.id as request_id,req.status as request_status,req.*,m.*, t.tournament_name, c.name as city_name, s.name as state_name, cn.name as country_name,match_info_lang.*, st.stadium_image,stadium_seats_lang.*');
         $this->db->join('match_info m', 'm.m_id = req.event_id', 'left');
         $this->db->join('match_info_lang', 'match_info_lang.match_id = m.m_id', 'left');
        $this->db->join('tournament t', 't.t_id = m.tournament', 'left');
        $this->db->join('cities c', 'c.id = m.city', 'left');
        $this->db->join('states s', 's.id = m.state', 'left');
        $this->db->join('countries cn', 'cn.id = m.country', 'left');
        $this->db->join('stadium st', 'st.s_id = m.venue', 'left');
         $this->db->join('stadium_seats_lang', 'stadium_seats_lang.stadium_seat_id = req.block_category', 'left');
         $this->db->where('match_info_lang.language', $this->session->userdata('language_code'));
         $this->db->where('stadium_seats_lang.language', $this->session->userdata('language_code'));
          $this->db->order_by('req.id','DESC');
          if($id != ''){
            $this->db->where('req.id', $id);
          }
        $result = $this->db->get('request_tickets req');
        //echo $this->db->last_query();exit;
        return $result;
    }

    public function ticket_request_by_limit($row_no='', $row_per_page='', $orderColumn = '', $orderby = '', $where_array = array(), $search = '')
    { 
         $this->db->select('request_tickets.id as request_id,request_tickets.status as request_status,request_tickets.*,m.*, t.tournament_name, c.name as city_name, s.name as state_name, cn.name as country_name,match_info_lang.*, st.stadium_image,stadium_seats.*');
         $this->db->from('request_tickets');
         $this->db->join('match_info m', 'm.m_id = request_tickets.event_id', 'left');
         $this->db->join('match_info_lang', 'match_info_lang.match_id = m.m_id', 'left');
        $this->db->join('tournament_lang t', 't.tournament_id = m.tournament', 'left');
        $this->db->join('cities c', 'c.id = m.city', 'left');
        $this->db->join('states s', 's.id = m.state', 'left');
        $this->db->join('countries cn', 'cn.id = m.country', 'left');
        $this->db->join('stadium st', 'st.s_id = m.venue', 'left');
        $this->db->join('stadium_seats', 'stadium_seats.id = request_tickets.block_category', 'left');
        $this->db->where('match_info_lang.language', $this->session->userdata('language_code'));
        $this->db->where('t.language', $this->session->userdata('language_code'));
        if (!empty($where_array)) {
            foreach ($where_array as $columnkey => $value) {
                $this->db->where($columnkey, $value);
            }
        }
        if ($orderColumn != "" && $orderby != "") {
           // $this->db->order_by($orderColumn, $orderby);
        }
        // $this->db->order_by('request_tickets.id','DESC');
        if ($search == 'upcoming') {
        $this->db->where('m.match_date >= ', date("Y-m-d H:i"));
        $this->db->order_by('m.match_date','ASC');
        }
        if ($search == 'all') {
        $this->db->order_by('request_tickets.id','DESC');
        }
        else if ($search == 'past') {
        $this->db->where('m.match_date < ', date("Y-m-d H:i"));
        }
        else if ($search == 'open') {
             $this->db->where('request_tickets.status',0);
        }
        else if ($search == 'closed') {
            $this->db->where('request_tickets.status',1);
        }
        else{
            $this->db->order_by('request_tickets.id','DESC');
        }
        if ($row_per_page != '') {
            $this->db->limit($row_per_page, $row_no);
        }
        $query = $this->db->get();
        return $query;
    }
 /*   public function ticket_request()
    {
         $this->db->select('request_tickets.id as request_id,request_tickets.*,m.*, t.tournament_name, c.name as city_name, s.name as state_name, cn.name as country_name,match_info_lang.*, st.stadium_image,stadium_seats.*');
         $this->db->from('request_tickets');
         $this->db->join('match_info m', 'm.m_id = request_tickets.event_id', 'left');
         $this->db->join('match_info_lang', 'match_info_lang.match_id = m.m_id', 'left');
        $this->db->join('tournament t', 't.t_id = m.tournament', 'left');
        $this->db->join('cities c', 'c.id = m.city', 'left');
        $this->db->join('states s', 's.id = m.state', 'left');
        $this->db->join('countries cn', 'cn.id = m.country', 'left');
        $this->db->join('stadium st', 'st.s_id = m.venue', 'left');
        $this->db->join('stadium_seats', 'stadium_seats.id = request_tickets.block_category', 'left');
        $this->db->where('match_info_lang.language', $this->session->userdata('language_code'));
         $this->db->order_by('request_tickets.id','DESC');
       
        $query = $this->db->get();
        return $query;

    }
*/

}
?>

