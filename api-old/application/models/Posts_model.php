<?php
class posts_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->load->model('fn_model');
        $this->status_code  = get_response_status_code();
    }

    public function get_post($slug){
        $this->db->select('*');
        $this->db->where(['slug' => $slug]);
        $this->db->or_where(['id' => $slug]);
        $post = $this->db->get('posts')->row_array();
        if ($post) {
            $category = $this->fn_model->get_category_via_id($post['category_id']);
            $post['category'] = $category['category'];
            return $post;
        }
        return null;
    }

    public function get_posts(){
        $this->db->select('*');
        $posts = $this->db->order_by('id','desc')->get('posts')->result();

        if ($posts) {
            foreach ($posts as $post) {
                $user = $this->fn_model->get_user_via_friconn_id($post->friconn_id);
                $post->user = $user['last_name']. ' '.$user['other_names'];

                $course = $this->fn_model->get_user_course($post->course_id);
                $post->course = $course;
            }
            return $posts;
        }
        return null;
    }


    public function ask_post($data){
        if ($this->db->insert('posts',$data)) {
            $post = $this->get_user_last_post($data['friconn_id']);
            return $post;
        }
        return false;
    }

    public function get_user_last_post($friconn_id){
        $this->db->select('*');
        $this->db->where(['friconn_id' => $friconn_id]);
        $post = $this->db->order_by('id', "desc")->limit(1)->get('posts')->row_array();

        if ($post) {
            $course = $this->fn_model->get_user_course($post['course_id']);
            $post['course'] = $course;

            return $post;
        }
        return null;
    }

    public function add_post_tags($data){
        $post_id = $data['post_id'];
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

            $post_tag_data = [
                'tag_id' => $tag_id,
                'post_id' => $post_id,
            ];

            $this->db->insert('post_tags',$post_tag_data);
            if ($sn == 4) {
                break;
            }
            $sn++;
        }
        $post_tags = $this->get_post_tags($post_id);
        return $post_tags;
    }

    public function get_post_tags($post_id){
        $this->db->select('id,post_id,tag_id');
        $this->db->where(['post_id' => $post_id]);
        $post_tags = $this->db->order_by('id', "desc")->get('post_tags')->result();

        if ($post_tags) {
            foreach ($post_tags as $post_tag) {
                $post_tag->tag = $this->fn_model->get_tag_via_id($post_tag->tag_id)['tag'];
            }
            return $post_tags;
        }
        return null;
    }

    public function get_post_answers($id){
        $this->db->select('*');
        $this->db->where(['post_id' => $id]);
        $post_answers = $this->db->order_by('id', "desc")->get('post_answers')->result();

        if ($post_answers) {
 
            foreach ($post_answers as $post_answer) {
                $f_id = $post_answer->friconn_id;
                
                $user = $this->fn_model->get_user_via_friconn_id($f_id);
                $post_answer->user = $user['last_name'].' '.$user['other_names'];
                
                $post_answer->upvotes = $this->get_answer_upvotes($post_answer->id);
                $post_answer->downvotes = $this->get_answer_downvotes($post_answer->id);
            }
            // $post = $this->get_post($id);
            // $post_answers['post'] = $post['subject'];
            
                
            

            return $post_answers;
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

    public function add_post_answer($data){
        if ($this->db->insert('post_answers',$data)) {
            $post = $this->get_user_last_answer($data['friconn_id']);
            return $post;
        }
        return false;
    }

    public function get_user_last_answer($friconn_id){
        $this->db->select('*');
        $this->db->where(['friconn_id' => $friconn_id]);
        $post_answer = $this->db->order_by('id', "desc")->limit(1)->get('post_answers')->row_array();

        if ($post_answer) {
            $post = $this->get_post($post_answer['post_id']);
            $post_answer['post'] = $post['subject'];

            $user = $this->fn_model->get_user_via_friconn_id($post_answer['friconn_id']);
            $post_answer['user'] = $user['last_name'].' '.$user['other_names'];

            return $post_answer;
        }
        return null;
    }
}
