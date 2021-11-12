<?php
class gists_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->load->model('fn_model');
        $this->status_code  = get_response_status_code();
    }

    public function add_gist($data)
    {
        try {
            if ($this->db->insert('gists', $data)) {  
                return $this->get_user_last_gist($data['student_id']);
            }
        } catch (\Throwable $th) {
            return array(
                'status' => "error",
                'message' => "Opps! The server has encountered a temporary error. Please try again later",
                'status_code' => $this->status_code['internalServerError']
            );
        };
    }

    public function get_gist($slug){
        $this->db->select('*');
        $this->db->where(['id' => $slug]);
        $gist = $this->db->get('gists')->row_array();
        if ($gist) {
            $category = $this->fn_model->get_category_via_id($gist['category_id']);
            $gist['category'] = $category['category'];

            $user = $this->fn_model->get_student_via_id($gist['student_id']);
            $gist['user'] = $user['fullname'];

            $gist_views_data = [
                'id' => $gist['id'],
                'views' => $gist['views'] + 1
            ];
            $this->update_gist_views($gist_views_data);
            return $gist;
        }
        return null;
    }

    public function get_gists(){
        $this->db->select('*');
        $gists = $this->db->order_by('id','desc')->get('gists')->result();
        if ($gists) {
            foreach ($gists as $gist) {
                $category = $this->fn_model->get_category_via_id($gist->category_id);
                $gist->category = $category['category'];
            }
            return $gists;
        }
        return null;
    }

    public function add_gist_comment($data){
        if ($this->db->insert('gist_comments',$data)) {
            return true;
        }
        return false;
    }

    public function get_gist_comments($gist_id){
        $this->db->select('*');
        $this->db->where(['gist_id' => $gist_id]);
        $gist_comments = $this->db->order_by('id', "desc")->get('gist_comments')->result();

        if ($gist_comments) {
            foreach ($gist_comments as $gist_comment) {
                $gist_comment->user = $this->fn_model->get_student_via_id($gist_comment->student_id)['fullname'];
            }
            return $gist_comments;
        }
        return null;
    }
    

    public function add_gist_like($data){
        $this->db->select('id');
        $check = $this->db->get_where('gist_likes',$data)->num_rows();
        if (!$check) {
            if ($this->db->insert('gist_likes',$data)) {
                return $this->get_gist_likes($data['gist_id']);
            }
            else{
                return false;
            }
        }
        else{
            return $this->get_gist_likes($data['gist_id']);
        }
        
        
    }

    public function delete_gist_like($data){
        $this->db->where($data);
        $check = $this->db->delete('gist_likes');
        return $this->get_gist_likes($data['gist_id']);
    }


    public function get_gist_likes($id){
        $this->db->select('id');
        return $this->db->get_where('gist_likes',['gist_id' => $id])->num_rows();
    }

    public function get_user_last_gist($student_id){
        $this->db->select('*');
        $this->db->where(['student_id' => $student_id]);
        $gist = $this->db->order_by('id', "desc")->limit(1)->get('gists')->row_array();

        if ($gist) {
            $category = $this->fn_model->get_category_via_id($gist['category_id']);
            $gist['category'] = $category['category'];

            $user = $this->fn_model->get_student_via_id($gist['student_id']);
            $gist['user'] = $user['fullname'];

            return $gist;
        }
        return null;
    }

    public function update_gist_views($data)
    {
        $update = $this->db->update('gists',$data,['id' => $data['id']]);
        if ($update) {
            return true;
        }
        return false;
    }
}
