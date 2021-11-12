<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Students extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('students_model');
        $this->load->model('fn_model');
        $this->status_code  = get_response_status_code();
        $this->load->library('form_validation');
    }

    function index_get()
    {

        $this->response([
            'status' => 'success',
            'message' => 'students API Connected successful.',
            'time_connected' => date('d-M-Y h:i:s'),
            'domain' => base_url()
        ], REST_Controller::HTTP_OK);
    }
    function login_post()
    {

        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');


        if ($this->form_validation->run() === FALSE) {
            $this->response([
                'status' => "failed",
                'message' => "Provide email and password.",
                'status_code' => $this->status_code['badRequest'],
                'data' => []
            ], $this->status_code['badRequest']);
        } else {
            $credentials = array(
                'email' => $this->input->post('email'),
                'password' => encrypt($this->input->post('password'))
            );
            $result = $this->students_model->get_login_info($credentials);
            $this->response($result, $result['status_code']);
        }
    }

    function register_post()
    {
        try {
            $this->form_validation->set_rules('email', 'Email', 'required');
            $this->form_validation->set_rules('fullname', 'Full name', 'required');
            $this->form_validation->set_rules('level_id', 'Level ID', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');
            $this->form_validation->set_rules('gender', 'Gender', 'required');
            $this->form_validation->set_rules('matric_no', 'Matric No', 'required');

            if ($this->form_validation->run() === FALSE) {
                $this->response([
                    'status' => "failed",
                    'message' => "One or more required data is missing.",
                    'status_code' => $this->status_code['badRequest'],
                    'data' => []
                ], $this->status_code['badRequest']);
            } else {
                $details = array(
                    "fullname" => $this->input->post('fullname'),
                    "level_id" => $this->input->post('level_id'),
                    "email" => $this->input->post('email'),
                    "gender" => $this->input->post('gender'),
                    "matric_no" => $this->input->post('matric_no'),
                    "password" => encrypt($this->input->post('password'))
                );

                if ($this->fn_model->check_student_email($details['email'])) {
                    $this->response([
                        'status' => 'error',
                        'message' => 'The email is already associated with another account.',
                        'status_code' => $this->status_code['forbidden']
                    ], $this->status_code['forbidden']);
                } else {
                    
                    $response = $this->students_model->create_account($details);
                    $this->response($response, $response['status_code']);
                }
            }
        } catch (\Throwable $th) {
            $this->response([
                'status' => 'error',
                'message' => "Opps! The server has encountered a temporary error. Please try again later",
                'status_code' => $this->status_code['internalServerError']
            ], $this->status_code['internalServerError']);
        }
    }

    function profile_get($student_id = '')
    {
        if (!$student_id) {
            $students = $this->students_model->get_students();
            return $this->response([
                'status' => "success",
                'message' => "Students fetched successfully.",
                'status_code' => $this->status_code['ok'],
                'data' => $students
            ], $this->status_code['ok']);
        }
        else{
            $student = $this->students_model->get_student($student_id);
            if ($student == null) {
                return $this->response([
                    'status' => "error",
                    'message' => "Student not found or not an student.",
                    'status_code' => $this->status_code['ok'],
                    'data' => $student
                ], $this->status_code['ok']);
            }
            return $this->response([
                'status' => "success",
                'message' => "Student fetched successfully.",
                'status_code' => $this->status_code['ok'],
                'data' => $student
            ], $this->status_code['ok']);
        }
    }
    function profile_post()
    {
        $this->form_validation->set_rules('employment_status_id', 'Employment Status', 'required');
        $this->form_validation->set_rules('student_id', 'Friconn ID', 'required');
        $this->form_validation->set_rules('linked_in_url', 'LinkedIn url', 'required');
        $this->form_validation->set_rules('state_id', 'State', 'required');
        $this->form_validation->set_rules('gender_id', 'Gender', 'required');
        $this->form_validation->set_rules('dob', 'Date of birth', 'required');
        $this->form_validation->set_rules('phone', 'Phone Number', 'required');
        $this->form_validation->set_rules('points', 'Points', 'required');
        $this->form_validation->set_rules('bio', 'Bio', 'required');
        if ($this->form_validation->run() === FALSE) {
            return $this->response([
                'status' => "failed",
                'message' => "All inputs are required.",
                'status_code' => $this->status_code['badRequest'],
                'data' => []
            ], $this->status_code['badRequest']);
        }

        $profile = [
            'student_id' => $this->input->post('student_id'),
            'employment_status_id' => $this->input->post('employment_status_id'),
            'dob' => $this->input->post('dob'),
            'phone' => $this->input->post('phone'),
            'points' => $this->input->post('points'),
            'gender_id' => $this->input->post('gender_id'),
            'bio' => $this->input->post('bio'),
            'linked_in_url' => $this->input->post('linked_in_url'),
            'state_id' => $this->input->post('state_id'),
        ];

        $student = $this->fn_model->get_student_via_student_id($profile['student_id']);
        $role_id = $this->fn_model->get_student_role_id('student');

        if (! $student ) {
            return $this->response([
                'status' => "error",
                'message' => "Student not found.",
                'status_code' => $this->status_code['ok'],
            ], $this->status_code['ok']);
        }

        if ($student['role_id'] != $role_id) {
            return $this->response([
                'status' => "error",
                'message' => "Student not an student.",
                'status_code' => $this->status_code['unauthorized'],
            ], $this->status_code['unauthorized']);
        }

        if ($this->students_model->update_student_profile($profile)) {
            return $this->response([
                'status' => "success",
                'message' => "Student profile updated successfully.",
                'status_code' => $this->status_code['ok'],
                'data' => $student
            ], $this->status_code['ok']);
        }

        return $this->response([
            'status' => "error",
            'message' => "Unable to update student profile.",
            'status_code' => $this->status_code['badRequest'],
            'data' => $student
        ], $this->status_code['badRequest']);    
    }

    function points_get($student_id)
    {
        $student_points = $this->students_model->get_student_points($student_id);
        if ($student_points == null) {
            return $this->response([
                'status' => "error",
                'message' => "Unable to fetch student points.",
                'status_code' => $this->status_code['ok'],
            ], $this->status_code['ok']);
        }
        return $this->response([
            'status' => "success",
            'message' => "Student points fetched successfully.",
            'status_code' => $this->status_code['ok'],
            'data' => $student_points
        ], $this->status_code['ok']);
    }

    function points_post()
    {

        $this->form_validation->set_rules('student_id', 'Friconn ID', 'required');
        $this->form_validation->set_rules('points', 'Points', 'required');
        if ($this->form_validation->run() === FALSE) {
            return $this->response([
                'status' => "failed",
                'message' => "All inputs are required.",
                'status_code' => $this->status_code['badRequest'],
                'data' => []
            ], $this->status_code['badRequest']);
        }

        $data = [
            'student_id' => $this->input->post('student_id'),
            'points' => $this->input->post('points')
        ];
        $student = $this->students_model->get_student($data['student_id']);
        if ($student == null) {
            return $this->response([
                'status' => "error",
                'message' => "Student not found or not an student.",
                'status_code' => $this->status_code['ok']
            ], $this->status_code['ok']);
        }

        $update_point = $this->students_model->update_student_points($data);
        if (! $update_point) {
            return $this->response([
                'status' => "error",
                'message' => "Student points not updated.",
                'status_code' => $this->status_code['badRequest'],
            ], $this->status_code['badRequest']);
        }
        return $this->response([
            'status' => "success",
            'message' => "Student points updated successfully.",
            'status_code' => $this->status_code['ok'],
            'data' => $update_point
        ], $this->status_code['ok']);
    }

    function payouts_get($student_id = '')
    {
        if ($student_id) {
            $student_payouts = $this->students_model->get_student_payouts($student_id);
            if ($student_payouts == null) {
                return $this->response([
                    'status' => "error",
                    'message' => "Student has no payouts history.",
                    'status_code' => $this->status_code['ok'],
                ], $this->status_code['ok']);
            }
            return $this->response([
                'status' => "success",
                'message' => "Student payouts history fetched successfully.",
                'status_code' => $this->status_code['ok'],
                'data' => $student_payouts
            ], $this->status_code['ok']);
        }
        else{
            $student_payouts = $this->students_model->get_students_payouts();
            if ($student_payouts == null) {
                return $this->response([
                    'status' => "error",
                    'message' => "Students have no payouts history.",
                    'status_code' => $this->status_code['ok'],
                ], $this->status_code['ok']);
            }
            return $this->response([
                'status' => "success",
                'message' => "Students payouts history fetched successfully.",
                'status_code' => $this->status_code['ok'],
                'data' => $student_payouts
            ], $this->status_code['ok']);
        }
    }

    function posts_get($student_id)
    {
        $role_id = $this->fn_model->get_student_role_id('student');
        $student = $this->fn_model->get_student_via_student_id($student_id);

        if ($student['role_id'] !== $role_id) {
            return $this->response([
                'status' => "error",
                'message' => "Student not an student.",
                'status_code' => $this->status_code['unauthorized'],
            ], $this->status_code['unauthorized']);
        }
        
        $student_posts = $this->students_model->get_student_posts($student_id);
        if ($student_posts == null) {
            return $this->response([
                'status' => "error",
                'message' => "Student has no post added.",
                'status_code' => $this->status_code['ok'],
            ], $this->status_code['ok']);
        }
        return $this->response([
            'status' => "success",
            'message' => "Student posts fetched successfully.",
            'status_code' => $this->status_code['ok'],
            'data' => $student_posts
        ], $this->status_code['ok']);
        
    }
}