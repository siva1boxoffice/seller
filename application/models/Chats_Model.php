<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Chats_Model extends CI_Model
{

	public function get_chat_list($seller_id,$status="",$booking_id="")
    {
    	 $this->db->join('register', 'register.id = chats_data.user_id', 'left')
    						->join('booking_global', 'booking_global.booking_no = chats_data.booking_id', 'left')
    						->join('admin_details', 'admin_details.admin_id = booking_global.seller_id', 'left')
    						->where('booking_global.seller_id',$seller_id)
    						
    						->select("chats_data.*,register.first_name,register.last_name,admin_details.admin_name,admin_details.admin_last_name,MAX(chats_data.updated_at) as updated_at,MAX(chats_data.updated_at) as created_at,sum( case when chats_data.status = 0 then 1 else 0 end ) as total_unread");
    	//$this->db->from("(select chats.* from chats order by id desc ) as chats_data ");
    	$this->db->from("chats as chats_data ");
    	if($booking_id){
    		if($status == 2){
    			$this->db->where('chats_data.status',0);
    			$this->db->where('chats_data.send_by',1);
    		}

    		$this->db->where('chats_data.booking_id',$booking_id);
    		$this->db->group_by('chats_data.id');

    		}
    	else{
    		//$this->db->from("chats as chats_data ");
    		$this->db->order_by('MAX(chats_data.id)','desc');
    		$this->db->group_by('booking_id');
    	}

    	$chats_list =  $this->db->get();
    	//echo $this->db->last_query();


		$this->db->where('send_by',1)
                  ->where('booking_id',$booking_id)
                  ->update('chats',array('status' => 1))
                  ;

              // echo"<pre>"; print_r($chats_list->result());
        return $chats_list->result();               
        
    }


    public function get_chat_list_ajax($seller_id)
    {
    	$this->db->select("chats_data.booking_id,count(chats_data.id) as message");
    	$this->db->join('booking_global', 'booking_global.booking_no = chats_data.booking_id', 'left');
		$this->db->from("chats as chats_data ");
		$this->db->where('chats_data.status',0);
		$this->db->where('chats_data.send_by',1);
		$this->db->where('booking_global.seller_id',$seller_id);
		$this->db->group_by('chats_data.booking_id');
    	$chats_list =  $this->db->get();
    	//echo $this->db->last_query();
        // echo"<pre>"; print_r($chats_list->result());
        return $chats_list->result();               
        
    }
}