<?php
class students_model extends CI_Model
{
    /* 
        @Description students Model.
    */
        public function __construct()
        {
            $this->load->database();
            $this->load->model('fn_model');
            $this->load->model('gists_model');
            $this->status_code  = get_response_status_code();
        }

        public function get_login_info($credentials)
        {

            $this->db->where(["email" => $credentials['email']]);
            $student = $this->db->get('students')->row_array();

            if (!$student) {
                return array(
                    'status' => "error",
                    'message' => "Invalid email address",
                    'status_code' => $this->status_code['unauthorized']
                );
            }

            if ($student['password'] !== $credentials['password']) {
                return array(
                    'status' => "error",
                    'message' => "Login failed! Incorrect account password.",
                    'status_code' => $this->status_code['unauthorized']
                );
            }

            if ($student['status'] == 0) {
                return array(
                    'status' => "error",
                    'message' => "Access Denied! This account is temporarily blocked, please contact our support centre.",
                    'status_code' => $this->status_code['forbidden']
                );
            }

            return array(
                'status' => "success",
                'message' => "Login successful.",
                'status_code' => $this->status_code['ok'],
                'data' => $student
            );
        }


        public function create_account($details)
        {
            try {
                if ($this->db->insert('students', $details)) {
                    if (true) {
                        $this->db->where(["email" => $details['email']]);
                        $student = $this->db->get('students')->row_array();
                        if ($student) {
                            return array(
                                'status' => "success",
                                'message' => "Account Created Successfully.",
                                'status_code' => $this->status_code['created'],
                                'data' => $student
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

        public function get_last_student_id()
        {
            $students = $this->db->select('id')->order_by('id', "desc")->limit(1)->get('students')->row();
            if(empty($students))
                return 1;
            return ($students->id + 1);
        }

        public function get_students(){
            $this->db->select('*');
            $profiles = $this->db->get('students')->result();
            return $profiles;
        }

        public function update_student($data)
        {
            $update = $this->db->update('students',$data,['student_id' => $data['student_id']]);
            if ($update) {
                return true;
            }
            return false;
        }

        public function get_student_gists($student_id){
            $this->db->select('*');
            $this->db->where(['student_id' => $student_id]);
            $student_gists = $this->db->order_by('id', "desc")->get('student_gists')->result();

            if ($student_gists) {
                foreach ($student_gists as $student_gist) {
                    $student = $this->fn_model->get_student_via_student_id($student_gist->student_id);
                    $student_gist->student = $student['last_name']. ' '.$student['other_names'];

                    $gist = $this->fn_model->get_student_gist($student_gist->gist_id);
                    $student_gist->gist = $gist;
                }

                return $student_gists;
            }
            return null;
        }

        public function check_student_gist($data){
            $this->db->select('id');
            $this->db->where(['gist_id' => $data['gist_id'],'student_id' =>$data['student_id']]);
            $students_gist = $this->db->get('student_gists')->num_rows();
            if ($students_gist > 0) {
                return true;
            }
            return false;
        }

        public function add_student_gist($data){
            if ($this->db->insert('student_gists',$data)) {
                $gist = $this->get_student_last_gist($data['student_id']);
                return $gist;
            }
            return false;
        }

        public function get_student_last_gist($student_id){
            $this->db->select('*');
            $this->db->where(['student_id' => $student_id]);
            $student_gist = $this->db->order_by('id', "desc")->limit(1)->get('student_gists')->row_array();

            if ($student_gist) {
                $gist = $this->fn_model->get_student_gist($student_gist->gist_id);
                $student_gist->gist = $gist;

                return $student_gist;
            }
            return null;
        }

        public function get_student_questions($student_id){
            $this->db->select('*');
            $this->db->where(['student_id' => $student_id]);
            $student_questions = $this->db->order_by('id', "desc")->get('questions')->result();

            if ($student_questions) {
                foreach ($student_questions as $student_question) {
                    $student = $this->fn_model->get_student_via_student_id($student_question->student_id);
                    $student_question->student = $student['last_name']. ' '.$student['other_names'];

                    $student_question->question = substr($student_question->question, 0,119);

                    // $gist = $this->fn_model->get_student_gist($student_question->gist_id);
                    // $students_question->gist = $gist;
                }

                return $student_questions;
            }
            return null;
        }

        public function get_student_gigs($student_id){
            $student_gists = $this->get_student_gists($student_id);

            if ($student_gists) {
                $gigs = [];
                foreach ($student_gists as $student_gist) {

                    $this->db->select('*');
                    $this->db->where(['gist_id' => $student_gist->gist_id, 'gisted' =>0, 'active_status' => 1 ]);
                    //Use where_in later for better experience
                    $gist_gigs = $this->db->order_by('id', "desc")->get('questions')->result();

                    // return $gist_gigs[0]->student_id;

                    foreach ($gist_gigs as $gist_gig) {
                        $student = $this->fn_model->get_student_via_student_id($gist_gig->student_id);
                        $gist_gig->asked_by = $student['last_name']. ' '.$student['other_names'];

                        $gist_gig->question = substr($gist_gig->question, 0,119);

                        $gist = $this->fn_model->get_student_gist($gist_gig->gist_id);
                        $gist_gig->gist = $gist;

                    array_push($gigs, $gist_gig);
                    }

                    // array_push($gigs, $gist_gigs);
                }

                return $gigs;
            }
            return null;
        }

        // public function get_student_gists($student_id){
        //     $this->db->select('*');
        //     $this->db->where(['student_id' => $student_id]);
        //     $student_gists = $this->db->order_by('id', "desc")->get('question_gists')->result();

        //     if ($student_gists) {
        //         foreach ($student_gists as $student_gist) {
        //             $student = $this->fn_model->get_student_via_student_id($student_gist->student_id);
        //             $student_gist->student = $student['last_name']. ' '.$student['other_names'];

        //             $question = $this->fn_model->get_student_question($student_gist->question_id);
        //             $student_gist->question = $question;
        //         }

        //         return $student_gists;
        //     }
        //     return null;
        // }

    }
