<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Security_Model extends CI_Model {
    public function admin_ip_track($type) {
        $data = array('admin_id' => ADMIN_ID, 'login_track_details_ip' => CLIENT_ADDR, 'login_track_details_system_info' => HTTP_USER_AGENT, 'attempt' => ($type), 'login_track_status_info' => $_SERVER['REMOTE_ADDR'] . '||' . $_SERVER['REMOTE_PORT']);
        $this->db->insert('admin_login_tracking_details', $data);
        return $this->db->insert_id();
    }
    public function check_admin_login($username, $password = '') {
        $username = ($username);
        $password = ($password);
        $this->db->select('admin_login_details.*,admin_details.*,admin_type.admin_type as admin_type_name,admin_role_details.admin_roles_id as role_id')->from('admin_login_details')->where('admin_login_details.admin_user_name', $username);
        if ($password != '') {
            $this->db->where('admin_login_details.admin_password', $password);
        }
        $this->db->join('admin_details', 'admin_login_details.admin_id  = admin_details.admin_id', 'left')->join('admin_type', 'admin_type.admin_type_id  = admin_login_details.admin_type_id', 'left')->join('admin_role_details', 'admin_role_details.admin_id  = admin_login_details.admin_id', 'left');
        $this->db->where('admin_details.admin_status', 'ACTIVE');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data['status'] = 1;
            $data['result'] = $query->row();
            return $data;
        } else {
            $data['status'] = 0;
            $data['result'] = '';
            return $data;
        }
    }
    public function get_admin_details() {
        if ($this->session->userdata('sa_id')) {
            $admin_id = $this->session->userdata('sa_id');
            $this->db->select('*')->from('super_admin_details')->where('super_admin_id', $admin_id)->where('super_admin_status', 'ACTIVE');
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                return $query->row();
            } else {
                return '';
            }
        } else {
            return '';
        }
    }
    public function get_user_details($admin_id) {
        $this->db->select('*')->from('admin_details')->where('admin_details.admin_id', $admin_id)->where('admin_details.admin_status', 'ACTIVE');
        $this->db->join('user_details', 'user_details.user_id  = admin_details.user_id', 'left');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return '';
        }
    }
    public function admin_pattern_check($pattern) {
        if ($this->session->userdata('admin_id')) {
            $admin_id = $this->session->userdata('admin_id');
            $this->db->select('*')->from('admin_login_details')->where('admin_id', $admin_id)->where('admin_pattren', $pattern);
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
?>
