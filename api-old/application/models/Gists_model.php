<?php
class gists_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->load->model('fn_model');
        $this->status_code  = get_response_status_code();
    }

    public function get_gist($slug){
        $this->db->select('*');
        $this->db->where(['slug' => $slug]);
        $this->db->or_where(['id' => $slug]);
        $gist = $this->db->get('gists')->row_array();
        if ($gist) {
            $category = $this->fn_model->get_category_via_id($gist['category_id']);
            $gist['category'] = $category['category'];
            return $gist;
        }
        return null;
    }

    public function get_gists(){
        $this->db->select('*');
        $gists = $this->db->order_by('id','desc')->get('gists')->result();

        if ($gists) {
            foreach ($gists as $gist) {
                $user = $this->fn_model->get_user_via_friconn_id($gist->friconn_id);
                $gist->user = $user['last_name']. ' '.$user['other_names'];

                $course = $this->fn_model->get_user_course($gist->course_id);
                $gist->course = $course;
            }
            return $gists;
        }
        return null;
    }


    public function ask_gist($data){
        if ($this->db->insert('gists',$data)) {
            $gist = $this->get_user_last_gist($data['friconn_id']);
            return $gist;
        }
        return false;
    }

    public function get_user_last_gist($friconn_id){
        $this->db->select('*');
        $this->db->where(['friconn_id' => $friconn_id]);
        $gist = $this->db->order_by('id', "desc")->limit(1)->get('gists')->row_array();

        if ($gist) {
            $course = $this->fn_model->get_user_course($gist['course_id']);
            $gist['course'] = $course;

            return $gist;
        }
        return null;
    }

    public function add_gist_tags($data){
        $gist_id = $data['gist_id'];
        $tags = $data['tags'];
        

        $sn = 0;
        
        foreach ($tags as $tag) {
            if(!$tag) continue;
            $check_tag_existence = $this->fn_model->get_tag_via_tag($tag);
            if ($check_tag_existence != 0) {
                $tag_id = $check_tag_existence['id'];
            }
            else{
                $tag_data =['tag' => $tag];
                $tag_id = $this->fn_model->add_tag($tag_data)['id'];
            }

            $gist_tag_data = [
                'tag_id' => $tag_id,
                'gist_id' => $gist_id,
            ];

            $this->db->insert('gist_tags',$gist_tag_data);
            if ($sn == 4) {
                break;
            }
            $sn++;
        }
        $gist_tags = $this->get_gist_tags($gist_id);
        return $gist_tags;
    }

    public function get_gist_tags($gist_id){
        $this->db->select('id,gist_id,tag_id');
        $this->db->where(['gist_id' => $gist_id]);
        $gist_tags = $this->db->order_by('id', "desc")->get('gist_tags')->result();

        if ($gist_tags) {
            foreach ($gist_tags as $gist_tag) {
                $gist_tag->tag = $this->fn_model->get_tag_via_id($gist_tag->tag_id)['tag'];
            }
            return $gist_tags;
        }
        return null;
    }

    public function get_gist_answers($id){
        $this->db->select('*');
        $this->db->where(['gist_id' => $id]);
        $gist_answers = $this->db->order_by('id', "desc")->get('gist_answers')->result();

        if ($gist_answers) {
 
            foreach ($gist_answers as $gist_answer) {
                $f_id = $gist_answer->friconn_id;
                
                $user = $this->fn_model->get_user_via_friconn_id($f_id);
                $gist_answer->user = $user['last_name'].' '.$user['other_names'];
                
                $gist_answer->upvotes = $this->get_answer_upvotes($gist_answer->id);
                $gist_answer->downvotes = $this->get_answer_downvotes($gist_answer->id);
            }
            // $gist = $this->get_gist($id);
            // $gist_answers['gist'] = $gist['subject'];
            
                
            

            return $gist_answers;
        }
        return null;
    }
    
    public function get_answer_upvotes($id){
        $this->db->select('id');
        return $this->db->get_where('answer_votes',['answer_id' => $id,'vote_type' => 1])->num_rows();
    }

    public function get_answer_downvotes($id){
        $this->db->select('id');
        return $this->db->get_where('answer_votes',['answer_id' => $id,'vote_type' => 0])->num_rows();
    }

    public function add_gist_answer($data){
        if ($this->db->insert('gist_answers',$data)) {
            $gist = $this->get_user_last_answer($data['friconn_id']);
            return $gist;
        }
        return false;
    }

    public function get_user_last_answer($friconn_id){
        $this->db->select('*');
        $this->db->where(['friconn_id' => $friconn_id]);
        $gist_answer = $this->db->order_by('id', "desc")->limit(1)->get('gist_answers')->row_array();

        if ($gist_answer) {
            $gist = $this->get_gist($gist_answer['gist_id']);
            $gist_answer['gist'] = $gist['subject'];

            $user = $this->fn_model->get_user_via_friconn_id($gist_answer['friconn_id']);
            $gist_answer['user'] = $user['last_name'].' '.$user['other_names'];

            return $gist_answer;
        }
        return null;
    }
}
