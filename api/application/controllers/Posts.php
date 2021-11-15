<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Posts extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('posts_model');
        // $this->load->model('courses_model');
        // $this->load->model('account_model');
        $this->load->model('fn_model');
        $this->status_code  = get_response_status_code();
        $this->load->library('form_validation');
    }

    function index_get()
    {

        $this->response([
            'status' => 'success',
            'message' => 'posts API Connected successful.',
            'time_connected' => date('d-M-Y h:i:s'),
            'domain' => base_url()
        ], REST_Controller::HTTP_OK);
    }

    function view_get($id = '')
    {
        if (!$id) {
            $posts = $this->posts_model->get_posts();
            if ($posts == null) {
                return $this->response([
                    'status' => "error",
                    'message' => "No posts posted yet.",
                    'status_code' => $this->status_code['ok'],
                ], $this->status_code['ok']);
            }
            return $this->response([
                'status' => "success",
                'message' => "posts fetched successfully.",
                'status_code' => $this->status_code['ok'],
                'data' => $posts
            ], $this->status_code['ok']);
        }
        else{
            $post = $this->posts_model->get_post($id);
            if ($post == null) {
                return $this->response([
                    'status' => "error",
                    'message' => "post not found.",
                    'status_code' => $this->status_code['ok'],
                ], $this->status_code['ok']);
            }
            return $this->response([
                'status' => "success",
                'message' => "post fetched successfully.",
                'status_code' => $this->status_code['ok'],
                'data' => $post
            ], $this->status_code['ok']);
        }
    }



    function comments_post()
    {
        $this->form_validation->set_rules('student_id', 'student ID', 'required');
        $this->form_validation->set_rules('post_id', 'Post ID', 'required');
        $this->form_validation->set_rules('comment', 'comment', 'required');

        if ($this->form_validation->run() === FALSE) {
            return $this->response([
                'status' => "error",
                'message' => "All input boxes required.",
                'status_code' => $this->status_code['badRequest'],
            ], $this->status_code['badRequest']);
        }
        $comment_data = array(
            'id' => time() - 100000;
            'sender' => $this->input->post('student_id'),
            'post_id' => $this->input->post('post_id'),
            'comment' => $this->input->post('comment'),
        );

        $post_comment = $this->posts_model->add_post_comment($comment_data);
        if (!$post_comment) {
            return $this->response([
                'status' => "error",
                'message' => "Unable to submit comment for this post.",
                'status_code' => $this->status_code['internalServerError'],
            ], $this->status_code['internalServerError']);
        }

        return $this->response([
            'status' => "success",
            'message' => "Comment submitted successfully.",
            'status_code' => $this->status_code['ok'],
            'data' => $post_comment
        ], $this->status_code['ok']);
    }

    function comments_get($id)
    {
        if (!$id) {
            return $this->response([
                'status' => "error",
                'message' => "Please select a post.",
                'status_code' => $this->status_code['badRequest'],
            ], $this->status_code['badRequest']);
        }
        $post_comments = $this->posts_model->get_post_comments($id);
        if ($post_comments == null) {
            return $this->response([
                'status' => "error",
                'message' => "No comments on this post yet.",
                'status_code' => $this->status_code['ok'],
            ], $this->status_code['ok']);
        }

        return $this->response([
            'status' => "success",
            'message' => "post comments fetched successfully.",
            'status_code' => $this->status_code['ok'],
            'data' => $post_comments
        ], $this->status_code['ok']);
    }
}