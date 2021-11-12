<?php
class categories_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->load->model('fn_model');
        $this->status_code  = get_response_status_code();
    }

    public function get_category($slug){
        $this->db->select('*');
        $this->db->where(['category' => $slug]);
        $this->db->or_where(['id' => $slug]);
        $category = $this->db->get('categories')->row_array();
        if ($category) {
            $category['posts'] = $this->get_category_posts($category['id']);
            return $category;
        }
        return null;
    }

    public function get_categories(){
        $this->db->select('id,category');
        $categories = $this->db->order_by('id','desc')->get('categories')->result();

        if ($categories) {
            return $categories;
        }
        return null;
    }

    public function get_category_posts($category_id){
        $this->db->select('id,category_id,post_id');
        $this->db->where(['category_id' => $category_id]);
        $category_posts = $this->db->order_by('id', "desc")->get('post_categories')->result();

        if ($category_posts) {
            foreach ($category_posts as $category_post) {
                $category_post->post = $this->fn_model->get_post_via_id($category_post->post_id)['subject'];
            }
            return $category_posts;
        }
        return null;
    }
}
