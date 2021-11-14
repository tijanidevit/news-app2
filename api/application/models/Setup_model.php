<?php
class setup_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->load->model('fn_model');
        $this->status_code  = get_response_status_code();
    }

    public function runQuert($query){
        $post = $this->db->query($query);
        if ($post) {
            return $post;
        }
        return null;
    }
}