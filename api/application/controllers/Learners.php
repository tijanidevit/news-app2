<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';




class Learners extends REST_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('learners_model');
        $this->load->model('fn_model');
        $this->status_code  = get_response_status_code();
        $this->load->library('form_validation');
    }

    function index_get()
    {
        $this->response([
            'status' => 'success',
            'message' => 'Learners API Connected successful.',
            'time_connected' => date('d-M-Y h:i:s'),
            'domain' => base_url(),
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
            $result = $this->learners_model->get_login_info($credentials);
            $this->response($result, $result['status_code']);
        }
    }

    function register_post()
    {

        $this->load->library('form_validation');

        try {

            $this->form_validation->set_rules('email', 'Email', 'required');
            $this->form_validation->set_rules('last_name', 'Last name', 'required');
            $this->form_validation->set_rules('other_names', 'Other Names', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');
            if ($this->form_validation->run() === FALSE) {
                $this->response([
                    'status' => "failed",
                    'message' => "One or more required data is missing.",
                    'status_code' => $this->status_code['badRequest'],
                    'data' => []
                ], $this->status_code['badRequest']);
            } else {
                $friconn_id = generate_id() . $this->learners_model->get_last_user_id();
                $details = array(
                    "friconn_id" => $friconn_id,
                    "verification_code" => rand(111111, 999999),
                    "role_id" => $this->fn_model->get_user_role_id('learner'),
                    "last_name" => $this->input->post('last_name'),
                    "other_names" => $this->input->post('other_names'),
                    "email" => $this->input->post('email'),
                    "password" => encrypt($this->input->post('password'))
                );

                if (!$this->fn_model->check_user_email($details['email'])) {
                    $this->response([
                        'status' => 'error',
                        'message' => 'Hey! The email is already associated with another account. We know you have a lot in your head, try use the forget password if you have forgotten your login details',
                        'status_code' => $this->status_code['forbidden']
                    ], $this->status_code['forbidden']);
                } else {

                    $title = 'Registration Successful';
                    $message = '<div style="font-weight: bold; padding:20px; text-align:center; border: 2px solid #ddd;">
                    <p> Dear '.$details['last_name'].',</p>
                    <p>We are glad to have you on board as a learner on FRICONN. Please verify your email address so we know it’s really you and also to gain access into the system.
                    </p>
                    <p> Please use this code - <b>'.$details['verification_code'].'</b> to verify your account!</p>
                    <p> Your Friconn ID is - <b>'.$details['friconn_id'].'</p>
                    </div>
                    ';

                    //add_to_mailing_list($details['email'],$details['last_name']. ' '.$details['other_names'],'Learners');
                    
                    //send_email();
                    $response = $this->learners_model->create_account($details);
                    return $this->response(add_onesignal_device($details['friconn_id']));
                    
                    // send_mailjet_email($title, $message, $details['email'], $details['last_name']);
                    
                    $this->response($response, $response['status_code']);
                }
            }
        } 
        catch (\Throwable $th) {
            $this->response([
                'status' => 'error',
                'message' => "Opps! The server has encountered a temporary error. Please try again later",
                'status_code' => $this->status_code['internalServerError']
            ], $this->status_code['internalServerError']);
        }
    }
    function profile_get($friconn_id = '')
    {
        if (!$friconn_id) {
            $learners = $this->learners_model->get_learners();
            return $this->response([
                'status' => "success",
                'message' => "Learners fetched successfully.",
                'status_code' => $this->status_code['ok'],
                'data' => $learners
            ], $this->status_code['ok']);
        }
        else{
            $learner = $this->learners_model->get_learner($friconn_id);
            if ($learner == null) {
                return $this->response([
                    'status' => "error",
                    'message' => "Learner not found or not a learner.",
                    'status_code' => $this->status_code['ok'],
                    'data' => $learner
                ], $this->status_code['ok']);
            }
            return $this->response([
                'status' => "success",
                'message' => "Learner fetched successfully.",
                'status_code' => $this->status_code['ok'],
                'data' => $learner
            ], $this->status_code['ok']);
        }
    }
    function profile_post()
    {
        $this->form_validation->set_rules('friconn_id', 'Friconn ID', 'required');
        $this->form_validation->set_rules('dob', 'Date of birth', 'required');
        $this->form_validation->set_rules('phone', 'Phone Number', 'required');
        $this->form_validation->set_rules('institution_id', 'Institution', 'required');
        $this->form_validation->set_rules('department_id', 'department_id', 'required');
        $this->form_validation->set_rules('level_id', 'level_id', 'required');
        if ($this->form_validation->run() === FALSE) {
            return $this->response([
                'status' => "failed",
                'message' => "All inputs are required.",
                'status_code' => $this->status_code['badRequest'],
                'data' => []
            ], $this->status_code['badRequest']);
        }

        $profile = [
            'friconn_id' => $this->input->post('friconn_id'),
            'dob' => $this->input->post('dob'),
            'phone' => $this->input->post('phone'),
            "institution_id" => $this->input->post('institution_id'),
            "department_id" => $this->input->post('department_id'),
            "level_id" => $this->input->post('level_id'),
        ];

        $learner = $this->fn_model->get_user_via_friconn_id($profile['friconn_id']);
        $role_id = $this->fn_model->get_user_role_id('learner');

        if (! $learner ) {
            return $this->response([
                'status' => "error",
                'message' => "Learner not found.",
                'status_code' => $this->status_code['ok'],
            ], $this->status_code['ok']);
        }

        if ($learner['role_id'] != $role_id) {
            return $this->response([
                'status' => "error",
                'message' => "User not a learner.",
                'status_code' => $this->status_code['unauthorized'],
            ], $this->status_code['unauthorized']);
        }

        if ($this->learners_model->update_learner_profile($profile)) {
            return $this->response([
                'status' => "success",
                'message' => "Learner profile updated successfully.",
                'status_code' => $this->status_code['ok'],
                'data' => $learner
            ], $this->status_code['ok']);
        }

        return $this->response([
            'status' => "error",
            'message' => "Unable to update learner profile.",
            'status_code' => $this->status_code['badRequest'],
            'data' => $learner
        ], $this->status_code['badRequest']);
    }

    function points_get($friconn_id)
    {
        $learner_points = $this->learners_model->get_learner_points($friconn_id);
        if ($learner_points == null) {
            return $this->response([
                'status' => "error",
                'message' => "Unable to fetch learner points.",
                'status_code' => $this->status_code['ok'],
            ], $this->status_code['ok']);
        }
        return $this->response([
            'status' => "success",
            'message' => "Learner points fetched successfully.",
            'status_code' => $this->status_code['ok'],
            'data' => $learner_points
        ], $this->status_code['ok']);
    }

    function points_post()
    {

        $this->form_validation->set_rules('friconn_id', 'Friconn ID', 'required');
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
            'friconn_id' => $this->input->post('friconn_id'),
            'points' => $this->input->post('points')
        ];
        $learner = $this->learners_model->get_learner($data['friconn_id']);
        if ($learner == null) {
            return $this->response([
                'status' => "error",
                'message' => "Learner not found or not an learner.",
                'status_code' => $this->status_code['ok']
            ], $this->status_code['ok']);
        }

        $learner_points = $this->learners_model->update_learner_points($data);
        if (! $learner_points) {
            return $this->response([
                'status' => "error",
                'message' => "Learner points not updated.",
                'status_code' => $this->status_code['badRequest'],
            ], $this->status_code['badRequest']);
        }
        return $this->response([
            'status' => "success",
            'message' => "Learner points updated successfully.",
            'status_code' => $this->status_code['ok'],
            'data' => $learner_points
        ], $this->status_code['ok']);
    }

    function courses_get($friconn_id = '')
    {
        if ($friconn_id) {
            $learner_courses = $this->learners_model->get_learner_courses($friconn_id);
            if ($learner_courses == null) {
                return $this->response([
                    'status' => "error",
                    'message' => "Learner has no course added.",
                    'status_code' => $this->status_code['ok'],
                ], $this->status_code['ok']);
            }
            return $this->response([
                'status' => "success",
                'message' => "Learner courses fetched successfully.",
                'status_code' => $this->status_code['ok'],
                'data' => $learner_courses
            ], $this->status_code['ok']);
        }
        else{
            $learner_courses = $this->learners_model->get_learners_courses($friconn_id);
            if ($learner_courses == null) {
                return $this->response([
                    'status' => "error",
                    'message' => "Learners have no courses added.",
                    'status_code' => $this->status_code['ok'],
                ], $this->status_code['ok']);
            }
            return $this->response([
                'status' => "success",
                'message' => "Learners courses fetched successfully.",
                'status_code' => $this->status_code['ok'],
                'data' => $learner_courses
            ], $this->status_code['ok']);
        }
    }

    function courses_post()
    {
        $this->form_validation->set_rules('friconn_id', 'Friconn ID', 'required');
        $this->form_validation->set_rules('course_id', 'course_id', 'required');
        if ($this->form_validation->run() === FALSE) {
            return $this->response([
                'status' => "failed",
                'message' => "All inputs are required.",
                'status_code' => $this->status_code['badRequest'],
                'data' => []
            ], $this->status_code['badRequest']);
        }

        $data = [
            'friconn_id' => $this->input->post('friconn_id'),
            'course_id' => $this->input->post('course_id'),
        ];


        $role_id = $this->fn_model->get_user_role_id('learner');
        $user = $this->fn_model->get_user_via_friconn_id($data['friconn_id']);

        if ($user['role_id'] !== $role_id) {
            return $this->response([
                'status' => "error",
                'message' => "User not a learner.",
                'status_code' => $this->status_code['unauthorized'],
            ], $this->status_code['unauthorized']);
        }


        if (! $this->fn_model->get_user_course($data['course_id'])) {
            return $this->response([
                'status' => "error",
                'message' => "Course does not exist.",
                'status_code' => $this->status_code['ok'],
            ], $this->status_code['ok']);
        }

        if ($this->learners_model->check_learner_course($data)) {
            return $this->response([
                'status' => "error",
                'message' => "Learner already added this course.",
                'status_code' => $this->status_code['conflict'],
            ], $this->status_code['conflict']);
        }
        $course = $this->learners_model->add_learner_course($data);
        if (!$course) {
            return $this->response([
                'status' => "error",
                'message' => "Learner course not added.",
                'status_code' => $this->status_code['internalServerError'],
            ], $this->status_code['internalServerError']);
        }

        return $this->response([
            'status' => "success",
            'message' => "Learner course added successfully.",
            'status_code' => $this->status_code['created'],
            'data' => $course
        ], $this->status_code['created']);
    }

    function questions_get($friconn_id)
    {
        $learner_questions = $this->learners_model->get_learner_questions($friconn_id);
        if ($learner_questions == null) {
            return $this->response([
                'status' => "error",
                'message' => "Learner has no question added.",
                'status_code' => $this->status_code['ok'],
            ], $this->status_code['ok']);
        }
        return $this->response([
            'status' => "success",
            'message' => "Learner questions fetched successfully.",
            'status_code' => $this->status_code['ok'],
            'data' => $learner_questions
        ], $this->status_code['ok']);   
    }
    
    function answers_get($friconn_id)
    {
        $learner_answers = $this->learners_model->get_learner_answers($friconn_id);
        if ($learner_answers == null) {
            return $this->response([
                'status' => "error",
                'message' => "Learner has no answer added.",
                'status_code' => $this->status_code['ok'],
            ], $this->status_code['ok']);
        }
        return $this->response([
            'status' => "success",
            'message' => "Learner answers fetched successfully.",
            'status_code' => $this->status_code['ok'],
            'data' => $learner_answers
        ], $this->status_code['ok']);   
    }

    // function plans_get($friconn_id = '')
    // {
    //     if ($friconn_id) {
    //         $learner_plans = $this->learners_model->get_learner_plans($friconn_id);
    //         if ($learner_plans == null) {
    //             return $this->response([
    //                 'status' => "error",
    //                 'message' => "Learner has no plan added.",
    //                 'status_code' => $this->status_code['ok'],
    //             ], $this->status_code['ok']);
    //         }
    //         return $this->response([
    //             'status' => "success",
    //             'message' => "Learner plans fetched successfully.",
    //             'status_code' => $this->status_code['ok'],
    //             'data' => $learner_plans
    //         ], $this->status_code['ok']);
    //     }
    //     else{
    //         $learner_plans = $this->learners_model->get_learners_plans($friconn_id);
    //         if ($learner_plans == null) {
    //             return $this->response([
    //                 'status' => "error",
    //                 'message' => "Learners have no plans added.",
    //                 'status_code' => $this->status_code['ok'],
    //             ], $this->status_code['ok']);
    //         }
    //         return $this->response([
    //             'status' => "success",
    //             'message' => "Learners plans fetched successfully.",
    //             'status_code' => $this->status_code['ok'],
    //             'data' => $learner_plans
    //         ], $this->status_code['ok']);
    //     }
    // }

    // function plans_post()
    // {
    //     $this->form_validation->set_rules('friconn_id', 'Friconn ID', 'required');
    //     $this->form_validation->set_rules('plan_id', 'plan_id', 'required');
    //     $this->form_validation->set_rules('points', 'points', 'required');
    //     if ($this->form_validation->run() === FALSE) {
    //         return $this->response([
    //             'status' => "failed",
    //             'message' => "All inputs are required.",
    //             'status_code' => $this->status_code['badRequest'],
    //             'data' => []
    //         ], $this->status_code['badRequest']);
    //     }

    //     $data = [
    //         'friconn_id' => $this->input->post('friconn_id'),
    //         'plan_id' => $this->input->post('plan_id'),
    //         'points' => $this->input->post('points'),
    //     ];


    //     $role_id = $this->fn_model->get_user_role_id('learner');
    //     $user = $this->fn_model->get_user_via_friconn_id($data['friconn_id']);

    //     if ($user['role_id'] !== $role_id) {
    //         return $this->response([
    //             'status' => "error",
    //             'message' => "User not a learner.",
    //             'status_code' => $this->status_code['unauthorized'],
    //         ], $this->status_code['unauthorized']);
    //     }


    //     if (! $this->fn_model->get_user_plan($data['plan_id'])) {
    //         return $this->response([
    //             'status' => "error",
    //             'message' => "plan does not exist.",
    //             'status_code' => $this->status_code['ok'],
    //         ], $this->status_code['ok']);
    //     }

    //     $plan = $this->learners_model->add_learner_plan($data);
    //     if (!$plan) {
    //         return $this->response([
    //             'status' => "error",
    //             'message' => "Learner plan not added.",
    //             'status_code' => $this->status_code['internalServerError'],
    //         ], $this->status_code['internalServerError']);
    //     }

    //     $old_points = $this->fn_model->get_learner_points($data['friconn_id']);
    //     $new_points = $data['points'];

    //     $points = $old_points + $new_points;
    //     $update_data = ['friconn_id' => $data['friconn_id'], 'points'=> $points];

    //     $plan['new_points'] = $this->fn_model->update_learner_points($update_data);

    //     return $this->response([
    //         'status' => "success",
    //         'message' => "Learner plan added successfully.",
    //         'status_code' => $this->status_code['created'],
    //         'data' => $plan
    //     ], $this->status_code['created']);
    // }

    function payments_get($friconn_id)
    {
        if ($friconn_id) {
            $learner_payments = $this->learners_model->get_learner_payments($friconn_id);
            if ($learner_payments == null) {
                return $this->response([
                    'status' => "error",
                    'message' => "Learner has no payment added.",
                    'status_code' => $this->status_code['ok'],
                ], $this->status_code['ok']);
            }
            return $this->response([
                'status' => "success",
                'message' => "Learner payments fetched successfully.",
                'status_code' => $this->status_code['ok'],
                'data' => $learner_payments
            ], $this->status_code['ok']);
        }
        else{            
            return $this->response([
                'status' => "error",
                'message' => "Friconn ID required.",
                'status_code' => $this->status_code['badRequest'],
            ], $this->status_code['badRequest']);
        }
    }

}
