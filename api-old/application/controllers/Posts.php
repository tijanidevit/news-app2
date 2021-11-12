<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Posts extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('posts_model');
        $this->load->model('courses_model');
        $this->load->model('account_model');
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

    function new_post()
    {
        $this->form_validation->set_rules('friconn_id', 'Friconn ID', 'required');
        $this->form_validation->set_rules('course_id', 'Course ID', 'required');
        $this->form_validation->set_rules('subject', 'Subject', 'required');
        $this->form_validation->set_rules('post', 'post', 'required');

        if ($this->form_validation->run() === FALSE) {
            return $this->response([
                'status' => "error",
                'message' => "All input boxes required.",
                'status_code' => $this->status_code['badRequest'],
            ], $this->status_code['badRequest']);
        }
        $post_data = array(
            'friconn_id' => $this->input->post('friconn_id'),
            'course_id' => $this->input->post('course_id'),
            'subject' => $this->input->post('subject'),
            'post' => $this->input->post('post'),
            'slug' => url_title($this->input->post('subject'), 'dash', TRUE).time(),
        );
        $tags = $this->input->post('tags');
        
        // return $this->response([
        //         'status' => "success",
        //         'message' => "post asked successfully. Await an answer soon",
        //         'status_code' => $this->status_code['created'],
        //         'data' => $post_data
        //     ], $this->status_code['created']);
        
        $post = $this->posts_model->ask_post($post_data);
        if ($post) {

            $tags = explode(';', $this->input->post('tags'));
            
            if (count($tags) > 0) {
                $tags_data = [
                    'post_id' => $post['id'],
                    'tags' => $tags,
                ];
                $post['tags'] = $this->posts_model->add_post_tags($tags_data);
            }

            //GET EDUMINISTERS WIITH THIS COURSE

            $title = "New post Posted";
            $body = "A new ".$post['course']." post has been asked.  Please login to provide an answer ";
            $click_action = "https://friconn.com/post/".$post_data['slug'];

            $course_eduministers = $this->courses_model->get_course_eduministers($post['course_id']);


            foreach ($course_eduministers as $eduminister) {
                $friconn_id = $eduminister->friconn_id;

                $eduminister_tokens = $this->account_model->get_user_push_token($friconn_id);

                foreach ($eduminister_tokens as $token) {
                    $d = send_push_notification($title,$body,$click_action,$token->token);
                }
            }

            return $this->response([
                'status' => "success",
                'message' => "post asked successfully. Await an answer soon",
                'status_code' => $this->status_code['created'],
                'data' => $post,
                'd' => $d
            ], $this->status_code['created']);
        }
        else{
            return $this->response([
                'status' => "error",
                'message' => "Unable to ask post.",
                'status_code' => $this->status_code['internalServerError'],
            ], $this->status_code['internalServerError']);

        }
    }

    function view_get($id = '')
    {
        if (!$id) {
            $posts = $this->posts_model->get_posts();
            if ($posts == null) {
                return $this->response([
                    'status' => "error",
                    'message' => "No posts asked yet.",
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

    function answers_get($id)
    {
        if (!$id) {
            return $this->response([
                'status' => "error",
                'message' => "Please select a post.",
                'status_code' => $this->status_code['badRequest'],
            ], $this->status_code['badRequest']);
        }
        $post_answers = $this->posts_model->get_post_answers($id);
        if ($post_answers == null) {
            return $this->response([
                'status' => "error",
                'message' => "No answer submitted for this post.",
                'status_code' => $this->status_code['ok'],
            ], $this->status_code['ok']);
        }

        // if (deductUserPoints or UpdateUserPoints) {
        //     # code... I think it should be update, the points deduction and calculation should be done via FE
        // then we can return success response
        // }

        return $this->response([
            'status' => "success",
            'message' => "post answers fetched successfully.",
            'status_code' => $this->status_code['ok'],
            'data' => $post_answers
        ], $this->status_code['ok']);
    }

    function answers_post()
    {

        $this->form_validation->set_rules('friconn_id', 'Friconn ID', 'required');
        $this->form_validation->set_rules('post_id', 'post ID', 'required');
        $this->form_validation->set_rules('answer', 'answer', 'required');

        if ($this->form_validation->run() === FALSE) {
            return $this->response([
                'status' => "error",
                'message' => "All input boxes required.",
                'status_code' => $this->status_code['badRequest'],
            ], $this->status_code['badRequest']);
        }
        $answer_data = array(
            'friconn_id' => $this->input->post('friconn_id'),
            'post_id' => $this->input->post('post_id'),
            'answer' => $this->input->post('answer'),
        );

        $post_answer = $this->posts_model->add_post_answer($answer_data);
        if (!$post_answer) {
            return $this->response([
                'status' => "error",
                'message' => "Unable to submit answer for this post.",
                'status_code' => $this->status_code['internalServerError'],
            ], $this->status_code['internalServerError']);
        }

        $this->fn_model->set_post_answered($answer_data['post_id']);
        $post = $this->posts_model->get_post($id);

        $title = "Answer Provided to Your post";
        $body = "Your post ".$post['course']." post has been asked.  Please login to provide an answer ";
        $click_action = "https://friconn.com/post/".$post_data['slug'];

        $friconn_id = $post['friconn_id'];
        $learner_tokens = $this->account_model->get_user_push_token($friconn_id);
        
        foreach ($learner_tokens as $token) {
            send_push_notification($title,$body,$click_action,$token->token);
        }

        return $this->response([
            'status' => "success",
            'message' => "Answer submitted successfully.",
            'status_code' => $this->status_code['ok'],
            'data' => $post_answer
        ], $this->status_code['ok']);
    }
}