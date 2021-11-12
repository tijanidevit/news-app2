<?php
class Learners_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->load->model('fn_model');
        $this->status_code  = get_response_status_code();
    }

    public function get_login_info($credentials)
    {
        $this->db->where(["email" => $credentials['email']]);
        $user = $this->db->get('users')->row_array();

        if (!$user) {
            return array(
                'status' => "error",
                'message' => "Invalid email address",
                'status_code' => $this->status_code['unauthorized']
            );
        }

        if ($user['password'] !== $credentials['password']) {
            return array(
                'status' => "error",
                'message' => "Login failed! Incorrect account password.",
                'status_code' => $this->status_code['unauthorized']
            );
        }

        if (!$this->fn_model->match_user_with_role($user['role_id'], 'learner')) {
            return array(
                'status' => "error",
                'message' => "Access Denied! Please login through the right user account.",
                'status_code' => $this->status_code['unauthorized']
            );
        }


        if ($user['blocked'] === 1) {
            return array(
                'status' => "error",
                'message' => "Access Denied! This account is temporarily blocked, please contact our support centre.",
                'status_code' => $this->status_code['forbidden']
            );
        }
        $learner_details = $this->_get_learner_details($user['friconn_id']);

        if ($user['approved'] === 0) {
            return array(
                'status' => "success",
                'message' => "One more step to go, please verify your email address.",
                'status_code' => $this->status_code['ok'],
                'data' => array_merge($user, $learner_details)
            );
        }

        return array(
            'status' => "success",
            'message' => "Login successful.",
            'status_code' => $this->status_code['ok'],
            'data' => array_merge($user, $learner_details)
        );
    }

    public function create_account($details)
    {
        try {
            if ($this->db->insert('users', $details)) {
                if ($this->db->insert('learners_profile', array('friconn_id' => $details['friconn_id']))) {
                    $this->db->where(["friconn_id" => $details['friconn_id']]);
                    $user = $this->db->get('users')->row_array();
                    if ($user) {
                        $other_details = $this->_get_learner_details($details['friconn_id']);
                        return array(
                            'status' => "success",
                            'message' => "One more step to go, please verify your email address.",
                            'status_code' => $this->status_code['created'],
                            'data' => array_merge($user, $other_details)
                        );
                    } else {
                        return array(
                            'status' => "error",
                            'message' => "Opps! The server has encountered a temporary error. Please try again later",
                            'status_code' => $this->status_code['internalServerError']
                        );
                    }
                } else {
                    return array(
                        'status' => "error",
                        'message' => "Opps! The server has encountered a temporary error. Please try again later",
                        'status_code' => $this->status_code['internalServerError']
                    );
                }
            } else {
                return array(
                    'status' => "error",
                    'message' => "Opps! The server has encountered a temporary error. Please try again later",
                    'status_code' => $this->status_code['internalServerError']
                );
            }
        } catch (\Throwable $th) {
            return array(
                'status' => "error",
                'message' => "Opps! The server has encountered a temporary error. Please try again later",
                'status_code' => $this->status_code['internalServerError']
            );
        };
    }

    public function get_last_user_id()
    {
        $users = $this->db->select('id')->order_by('id', "desc")->limit(1)->get('users')->row();
        if(empty($users))
            return 1;
        return ($users->id + 1);
    }

    private function _get_learner_details($friconn_id)
    {
        $this->db->select('*');
        $query = $this->db->get_where('learners_profile', array('friconn_id' => $friconn_id));
        $result = $query->row_array();
        $result['institution'] = $this->fn_model->get_user_institution($result['institution_id']);
        $result['department'] = $this->fn_model->get_user_department($result['department_id']);
        $result['level'] = $this->fn_model->get_user_level($result['level_id']);
        return $result;
    }
    public function get_learners(){
        $role_id = $this->fn_model->get_user_role_id('learner');

        $this->db->select('friconn_id,role_id,last_name,other_names,email,profile_image,created_at');
        $profiles = $this->db->get_where('users',['role_id' => $role_id])->result();
        foreach ($profiles as $profile) {
            $profile->profile = $this->_get_learner_details($profile->friconn_id);
        }
        return $profiles;
    }

    public function get_learner($friconn_id){
        $role_id = $this->fn_model->get_user_role_id('learner');
        $this->db->select('friconn_id,role_id,last_name,other_names,email,profile_image,created_at');
        $learner = $this->db->get_where('users',['friconn_id' => $friconn_id,'role_id' => $role_id])->row_array();

        if ($learner['role_id'] == $role_id) {
            $learner_details = $this->_get_learner_details($friconn_id);
            return array_merge($learner, $learner_details);
        }
        return null;
    }

    public function update_learner_profile($data)
    {
        $update_profile = $this->db->update('learners_profile',$data,['friconn_id' => $data['friconn_id']]);
        if ($update_profile) {
            return true;
        }
        return false;
    }

    public function get_learner_points($friconn_id){
        $this->db->select('points');
        $learner_points = $this->db->get_where('learners_profile',['friconn_id' => $friconn_id])->row_array();

        if ($learner_points) {
            return $learner_points['points'];
        }
        return null;
    }

    public function update_learner_points($data)
    {
        $update_points = $this->db->update('learners_profile',$data,['friconn_id' => $data['friconn_id']]);
        if ($update_points) {
            return $this->get_learner_points($data['friconn_id']);
        }
        return false;
    }

    public function get_learners_courses(){
        $this->db->select('*');
        $learners_courses = $this->db->order_by('id', "desc")->get('learner_courses')->result();

        if ($learners_courses) {
            foreach ($learners_courses as $learners_course) {
                $user = $this->fn_model->get_user_via_friconn_id($learners_course->friconn_id);
                $learners_course->learner = $user['last_name']. ' '.$user['other_names'];

                $course = $this->fn_model->get_user_course($learners_course->course_id);
                $learners_course->course = $course;
            }

            return $learners_courses;
        }
        return null;
    }

    public function get_learner_courses($friconn_id){
        $this->db->select('*');
        $this->db->where(['friconn_id' => $friconn_id]);
        $learner_courses = $this->db->order_by('id', "desc")->get('learner_courses')->result();

        if ($learner_courses) {
            foreach ($learner_courses as $learner_course) {
                $user = $this->fn_model->get_user_via_friconn_id($learner_course->friconn_id);
                $learner_course->learner = $user['last_name']. ' '.$user['other_names'];

                $course = $this->fn_model->get_user_course($learner_course->course_id);
                $learner_course->course = $course;
            }

            return $learner_courses;
        }
        return null;
    }

    public function check_learner_course($data){
        $this->db->select('id');
        $this->db->where(['course_id' => $data['course_id'],'friconn_id' =>$data['friconn_id']]);
        $learners_course = $this->db->get('learner_courses')->num_rows();
        if ($learners_course > 0) {
            return true;
        }
        return false;
    }

    public function add_learner_course($data){
        if ($this->db->insert('learner_courses',$data)) {
            $course = $this->get_learner_last_course($data['friconn_id']);
            return $course;
        }
        return false;
    }

    public function get_learner_last_course($friconn_id){
        $this->db->select('*');
        $this->db->where(['friconn_id' => $friconn_id]);
        $learner_course = $this->db->order_by('id', "desc")->limit(1)->get('learner_courses')->row_array();

        if ($learner_course) {
            $course = $this->fn_model->get_user_course($learner_course['course_id']);
            $learner_course['course'] = $course;

            return $learner_course;
        }
        return null;
    }

    public function get_learner_questions($friconn_id){
        $this->db->select('*');
        $this->db->where(['friconn_id' => $friconn_id]);
        $learner_questions = $this->db->order_by('id', "desc")->get('questions')->result();

        if ($learner_questions) {
            foreach ($learner_questions as $learner_question) {
                $user = $this->fn_model->get_user_via_friconn_id($learner_question->friconn_id);
                $learner_question->learner = $user['last_name']. ' '.$user['other_names'];
                $learner_question->question = substr($learner_question->question, 0,119);

                $course = $this->fn_model->get_user_course($learner_question->course_id);
                $learner_question->course = $course;
            }

            return $learner_questions;
        }
        return null;
    }

    public function get_learner_answers($friconn_id){
        $this->db->select('*');
        $this->db->where(['friconn_id' => $friconn_id]);
        $learner_answers = $this->db->order_by('id', "desc")->get('question_answers')->result();

        if ($learner_answers) {
            foreach ($learner_answers as $learner_answer) {
                $user = $this->fn_model->get_user_via_friconn_id($learner_answer->friconn_id);
                $learner_answer->learner = $user['last_name']. ' '.$user['other_names'];

                $question = $this->fn_model->get_user_question($learner_answer->question_id);
                $learner_answer->question = $question;
            }

            return $learner_answers;
        }
        return null;
    }

    public function get_learners_plans(){
        $this->db->select('*');
        $learners_plans = $this->db->order_by('id', "desc")->get('learner_plans')->result();

        if ($learners_plans) {
            foreach ($learners_plans as $learners_plan) {
                $user = $this->fn_model->get_user_via_friconn_id($learners_plan->friconn_id);
                $learners_plan->learner = $user['last_name']. ' '.$user['other_names'];

                $plan = $this->fn_model->get_user_plan($learners_plan->plan_id);
                $learners_plan->plan = $plan;
            }

            return $learners_plans;
        }
        return null;
    }

    public function get_learner_plans($friconn_id){
        $this->db->select('*');
        $this->db->where(['friconn_id' => $friconn_id]);
        $learner_plans = $this->db->order_by('id', "desc")->get('learner_plans')->result();

        if ($learner_plans) {
            foreach ($learner_plans as $learner_plan) {
                $user = $this->fn_model->get_user_via_friconn_id($learner_plan->friconn_id);
                $learner_plan->learner = $user['last_name']. ' '.$user['other_names'];

                $plan = $this->fn_model->get_user_plan($learner_plan->plan_id);
                $learner_plan->plan = $plan;
            }

            return $learner_plans;
        }
        return null;
    }

    public function check_learner_plan($data){
        $this->db->select('id');
        $this->db->where(['plan_id' => $data['plan_id'],'friconn_id' =>$data['friconn_id']]);
        $learners_plan = $this->db->get('learner_plans')->num_rows();
        if ($learners_plan > 0) {
            return true;
        }
        return false;
    }

    public function add_learner_plan($data){
        if ($this->db->insert('learner_plans',$data)) {
            $plan = $this->get_learner_last_plan($data['friconn_id']);
            return $plan;
        }
        return false;
    }

    public function get_learner_last_plan($friconn_id){
        $this->db->select('*');
        $this->db->where(['friconn_id' => $friconn_id]);
        $learner_plan = $this->db->order_by('id', "desc")->limit(1)->get('learner_plans')->row_array();

        if ($learner_plan) {
            $plan = $this->fn_model->get_user_plan($learner_plan['plan_id']);
            $learner_plan['plan'] = $plan;

            return $learner_plan;
        }
        return null;
    }


    public function get_learner_payments($friconn_id){
        $this->db->select('*');
        $this->db->where(['friconn_id' => $friconn_id]);
        $learner_payments = $this->db->order_by('id', "desc")->get('payments')->result();

        if ($learner_payments) {
            foreach ($learner_payments as $learner_payment) {
                $user = $this->fn_model->get_user_via_friconn_id($learner_payment->friconn_id);
                $learner_payment->learner = $user['last_name']. ' '.$user['other_names'];
                
                $plan = $this->fn_model->get_user_plan($learner_payment->plan_id);
                $learner_payment->plan = $plan;
            }

            return $learner_payments;
        }
        return null;
    }
}
