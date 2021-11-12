<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Gists extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('gists_model');
        $this->load->model('fn_model');
        $this->status_code  = get_response_status_code();
        $this->load->library('form_validation');
    }

    function index_get()
    {

        $this->response([
            'status' => 'success',
            'message' => 'gists API Connected successful.',
            'time_connected' => date('d-M-Y h:i:s'),
            'domain' => base_url()
        ], REST_Controller::HTTP_OK);
    }


    function new_post()
    {
        $this->form_validation->set_rules('student_id', 'Student ID', 'required');
        $this->form_validation->set_rules('category_id', 'Category ID', 'required');
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('content', 'Content', 'required');
        if ($this->form_validation->run() === FALSE) {
            return $this->response([
                'status' => "failed",
                'message' => "All inputs are required.",
                'status_code' => $this->status_code['badRequest'],
                'data' => []
            ], $this->status_code['badRequest']);
        }

        $gist = [
            'student_id' => $this->input->post('student_id'),
            'content' => $this->input->post('content'),
            'category_id' => $this->input->post('category_id'),
            'title' => $this->input->post('title'),
        ];

        $student = $this->fn_model->get_student_via_id($gist['student_id']);

        if (! $student ) {
            return $this->response([
                'status' => "error",
                'message' => "Student not found.",
                'status_code' => $this->status_code['ok'],
            ], $this->status_code['ok']);
        }

        $gist = $this->gists_model->add_gist($gist);
        if ($gist) {
            return $this->response([
                'status' => "success",
                'message' => "Student gist added successfully.",
                'status_code' => $this->status_code['ok'],
                'data' => $gist
            ], $this->status_code['ok']);
        }

        return $this->response([
            'status' => "error",
            'message' => "Unable to update student gist.",
            'status_code' => $this->status_code['badRequest'],
            'data' => $student
        ], $this->status_code['badRequest']);    
    }

    function view_get($id = '')
    {
        if (!$id) {
            $gists = $this->gists_model->get_gists();
            if ($gists == null) {
                return $this->response([
                    'status' => "error",
                    'message' => "No gists posted yet.",
                    'status_code' => $this->status_code['ok'],
                ], $this->status_code['ok']);
            }
            return $this->response([
                'status' => "success",
                'message' => "gists fetched successfully.",
                'status_code' => $this->status_code['ok'],
                'data' => $gists
            ], $this->status_code['ok']);
        }
        else{
            $gist = $this->gists_model->get_gist($id);
            if ($gist == null) {
                return $this->response([
                    'status' => "error",
                    'message' => "gist not found.",
                    'status_code' => $this->status_code['ok'],
                ], $this->status_code['ok']);
            }
            $gist['comments'] = $this->gists_model->get_gist_comments($id);
            $gist['likes'] = $this->gists_model->get_gist_likes($id);
            return $this->response([
                'status' => "success",
                'message' => "gist fetched successfully.",
                'status_code' => $this->status_code['ok'],
                'data' => $gist
            ], $this->status_code['ok']);
        }
    }

    function comments_get($id)
    {
        if (!$id) {
            return $this->response([
                'status' => "error",
                'message' => "Please select a gist.",
                'status_code' => $this->status_code['badRequest'],
            ], $this->status_code['badRequest']);
        }
        $gist_comments = $this->gists_model->get_gist_comments($id);
        if ($gist_comments == null) {
            return $this->response([
                'status' => "error",
                'message' => "No comments on this gist yet.",
                'status_code' => $this->status_code['ok'],
            ], $this->status_code['ok']);
        }

        return $this->response([
            'status' => "success",
            'message' => "gist comments fetched successfully.",
            'status_code' => $this->status_code['ok'],
            'data' => $gist_comments
        ], $this->status_code['ok']);
    }

    function comments_post()
    {
        $this->form_validation->set_rules('student_id', 'student ID', 'required');
        $this->form_validation->set_rules('gist_id', 'gist ID', 'required');
        $this->form_validation->set_rules('comment', 'comment', 'required');

        if ($this->form_validation->run() === FALSE) {
            return $this->response([
                'status' => "error",
                'message' => "All input boxes required.",
                'status_code' => $this->status_code['badRequest'],
            ], $this->status_code['badRequest']);
        }
        $comment_data = array(
            'student_id' => $this->input->post('student_id'),
            'gist_id' => $this->input->post('gist_id'),
            'comment' => $this->input->post('comment'),
        );

        $gist_comment = $this->gists_model->add_gist_comment($comment_data);
        if (!$gist_comment) {
            return $this->response([
                'status' => "error",
                'message' => "Unable to submit comment for this gist.",
                'status_code' => $this->status_code['internalServerError'],
            ], $this->status_code['internalServerError']);
        }

        return $this->response([
            'status' => "success",
            'message' => "Comment submitted successfully.",
            'status_code' => $this->status_code['ok'],
            'data' => $gist_comment
        ], $this->status_code['ok']);
    }


    function like_post()
    {
        $this->form_validation->set_rules('student_id', 'student ID', 'required');
        $this->form_validation->set_rules('gist_id', 'gist ID', 'required');

        if ($this->form_validation->run() === FALSE) {
            return $this->response([
                'status' => "error",
                'message' => "All input boxes required.",
                'status_code' => $this->status_code['badRequest'],
            ], $this->status_code['badRequest']);
        }
        $like_data = array(
            'student_id' => $this->input->post('student_id'),
            'gist_id' => $this->input->post('gist_id')
        );

        $gist_like = $this->gists_model->add_gist_like($like_data);
        if (!$gist_like) {
            return $this->response([
                'status' => "error",
                'message' => "Unable to submit like for this gist.",
                'status_code' => $this->status_code['internalServerError'],
            ], $this->status_code['internalServerError']);
        }

        return $this->response([
            'status' => "success",
            'message' => "Gist liked successfully.",
            'status_code' => $this->status_code['ok'],
            'data' => $gist_like
        ], $this->status_code['ok']);
    }


    function unlike_post()
    {
        $this->form_validation->set_rules('student_id', 'student ID', 'required');
        $this->form_validation->set_rules('gist_id', 'gist ID', 'required');

        if ($this->form_validation->run() === FALSE) {
            return $this->response([
                'status' => "error",
                'message' => "All input boxes required.",
                'status_code' => $this->status_code['badRequest'],
            ], $this->status_code['badRequest']);
        }
        $like_data = array(
            'student_id' => $this->input->post('student_id'),
            'gist_id' => $this->input->post('gist_id')
        );

        $gist_like = $this->gists_model->delete_gist_like($like_data);
        if ($gist_like === false) {
            return $this->response([
                'status' => "error",
                'message' => "Unable to unlike this gist.",
                'status_code' => $this->status_code['internalServerError'],
            ], $this->status_code['internalServerError']);
        }

        return $this->response([
            'status' => "success",
            'message' => "Gist unliked successfully.",
            'status_code' => $this->status_code['ok'],
            'data' => $gist_like
        ], $this->status_code['ok']);
    }
}