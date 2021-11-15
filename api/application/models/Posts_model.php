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
        $this->db->where(['id' => $slug]);
        $this->db->or_where(['id' => $slug]);
        $post = $this->db->get('posts')->row_array();
        if ($post) {
            $category = $this->fn_model->get_category_via_id($post['category_id']);
            $post['category'] = $category['category'];

            $post_views_data = [
                'id' => $post['id'],
                'views' => $post['views'] + 1
            ];
            $this->update_post_views($post_views_data);
            return $post;
        }
        return null;
    }

    public function get_posts(){
        $this->db->select('*');
        $posts = $this->db->order_by('id','desc')->get('posts')->result();
        if ($posts) {
            foreach ($posts as $post) {
                $category = $this->fn_model->get_category_via_id($post->category_id);
                $post->category = $category['category'];
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



    public function add_post_comment($data){
        if ($this->db->insert('post_comments',$data)) {
            return true;
        }
        return false;
    }

    public function get_post_comments($post_id){
        $this->db->select('*');
        $this->db->where(['post_id' => $post_id]);
        $post_comments = $this->db->order_by('id', "desc")->get('post_comments')->result();

        if ($post_comments) {
            foreach ($post_comments as $post_comment) {
                if (is_int($post_comment->sender)) {
                    $post_comment->user = 'Admin';
                }
                else{
                    return $this->fn_model->get_student_via_id($post_comment->sender);
                    $post_comment->user = $this->fn_model->get_student_via_id($post_comment->sender)['fullname'];

                    // return $post_comment->user;
                }
            }
            return $post_comments;
        }
        return null;
    }

    public function update_post_views($data)
    {
        $update = $this->db->update('posts',$data,['id' => $data['id']]);
        if ($update) {
            return true;
        }
        return false;
    }
}
