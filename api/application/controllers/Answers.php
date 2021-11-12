<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Answers extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('answers_model');
        $this->load->model('eduministers_model');
        $this->load->model('fn_model');
        $this->status_code  = get_response_status_code();
        $this->load->library('form_validation');
    }

    function index_get()
    {
      	$this->response([
            'status' => 'success',
            'message' => 'Answers API Connected successful.',
            'time_connected' => date('d-M-Y h:i:s'),
            'domain' => base_url()
        ], REST_Controller::HTTP_OK);
    }

    function view_get($id = '')
    {
        if (!$id) {
            $answers = $this->answers_model->get_answers();
            if ($answers == null) {
                return $this->response([
                    'status' => "error",
                    'message' => "No answers provided yet.",
                    'status_code' => $this->status_code['notFound'],
                ], $this->status_code['notFound']);
            }
            return $this->response([
                'status' => "success",
                'message' => "Answers fetched successfully.",
                'status_code' => $this->status_code['ok'],
                'data' => $answers
            ], $this->status_code['ok']);
        }
        else{
            $answer = $this->answers_model->get_answer($id);
            if ($answer == null) {
                return $this->response([
                    'status' => "error",
                    'message' => "Answer not found.",
                    'status_code' => $this->status_code['notFound'],
                ], $this->status_code['notFound']);
            }
            return $this->response([
                'status' => "success",
                'message' => "Answer fetched successfully.",
                'status_code' => $this->status_code['ok'],
                'data' => $answer
            ], $this->status_code['ok']);
        }
    }

    function upvotes_post()
    {
        $this->form_validation->set_rules('friconn_id', 'Friconn ID', 'required');
        $this->form_validation->set_rules('answer_id', 'answer ID', 'required');
        $points_earned = $this->fn_model->get_current_earning_points();


        //Add points arned to existing point
        //Creae new onts earning table and add points earned

        $answer_id = $this->input->post('answer_id');

        $answer = $this->answers_model->get_answer($answer_id);

        $answered_by = $answer['friconn_id'];

        $eduminister = $this->eduministers_model->get_eduminister($answered_by);
        $eduminister_points = $eduminister['points'];
        $points_balance = $eduminister_points + $points_earned;


        if ($this->form_validation->run() === FALSE) {
            return $this->response([
                'status' => "error",
                'message' => "All inputs are required.",
                'status_code' => $this->status_code['badRequest'],
            ], $this->status_code['badRequest']);
        }
        $vote_data = array(
            'friconn_id' => $this->input->post('friconn_id'),
            'answer_id' => $answer_id,
            'vote_type' => 1,
            'points_earned' => $points_earned,
        );

        if ($this->answers_model->check_user_answer_vote($vote_data)) {
            // return $this->response([
            //     'status' => "error",
            //     'message' => "You already voted this answer.",
            //     'status_code' => $this->status_code['ok'],
            // ], $this->status_code['ok']);
        }

        $vote_answer = $this->answers_model->vote_answer($vote_data);
        if (!$vote_answer) {
            return $this->response([
                'status' => "error",
                'message' => "Unable to vote this answer.",
                'status_code' => $this->status_code['internalServerError'],
            ], $this->status_code['internalServerError']);
        }

        $allocation_data = [
            'friconn_id' => $answered_by,
            'answer_id' => $answer_id,
            'points_earned' => $points_earned
        ];

        $this->eduministers_model->add_eduminister_points_allocaton($allocation_data);
        return $this->response([
            'status' => "success",
            'message' => "Answer voted successfully.",
            'status_code' => $this->status_code['ok'],
            'data' => $vote_answer
        ], $this->status_code['ok']);
    }


    function downvotes_post()
    {
        $this->form_validation->set_rules('friconn_id', 'Friconn ID', 'required');
        $this->form_validation->set_rules('answer_id', 'answer ID', 'required');
        if ($this->form_validation->run() === FALSE) {
            return $this->response([
                'status' => "error",
                'message' => "All inputs are required.",
                'status_code' => $this->status_code['badRequest'],
            ], $this->status_code['badRequest']);
        }
        $vote_data = array(
            'friconn_id' => $this->input->post('friconn_id'),
            'answer_id' => $this->input->post('answer_id'),
            'vote_type' => 0,
            'points_earned' => 0,
        );

        if ($this->answers_model->check_user_answer_vote($vote_data)) {
            return $this->response([
                'status' => "error",
                'message' => "You already voted this answer.",
                'status_code' => $this->status_code['badRequest'],
            ], $this->status_code['badRequest']);
        }

        $vote_answer = $this->answers_model->vote_answer($vote_data);
        if (!$vote_answer) {
            return $this->response([
                'status' => "error",
                'message' => "Unable to vote this answer.",
                'status_code' => $this->status_code['internalServerError'],
            ], $this->status_code['internalServerError']);
        }
        return $this->response([
            'status' => "success",
            'message' => "Answer voted successfully.",
            'status_code' => $this->status_code['ok'],
            'data' => $vote_answer
        ], $this->status_code['ok']);
    }
}