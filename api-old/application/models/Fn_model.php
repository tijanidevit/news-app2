<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Fn_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
    }

    public function check_student_email($email)
    {

        $this->db->where("email", $email);
        $emailCheck = $this->db->get('students');
        if ($emailCheck->num_rows() > 0) {
            return true;
        }
        return false;
    }

    public function get_student_via_email($email)
    {

        $this->db->where("email", $email);
        $emailCheck = $this->db->get('students');
        if ($emailCheck->num_rows() > 0) {
            return $emailCheck->row_array();
        }
        return false;
    }

    public function get_category_via_id($category)
    {

        $this->db->where("category", $category);
        $categoryCheck = $this->db->get('categories');
        if ($categoryCheck->num_rows() > 0) {
            return $categoryCheck->row_array();
        }
        return false;
    }

    public function get_student_state($id)
    {
        $this->db->select('state');
        $this->db->where(["id" => $id]);
        $result = $this->db->get('states')->row_array();
        if ($result) {
            return $result['state'];
        } else {
            return 0;
        }
    }

    public function get_student_gender($id)
    {
        $this->db->select('gender');
        $this->db->where(["id" => $id]);
        $result = $this->db->get('genders')->row_array();
        if ($result) {
            return $result['gender'];
        } else {
            return 0;
        }
    }

    public function get_student_employment_status($id)
    {
        $this->db->select('employment_status');
        $this->db->where(["id" => $id]);
        $result = $this->db->get('employment_statuses')->row_array();
        if ($result) {
            return $result['employment_status'];
        } else {
            return 0;
        }
    }

    public function get_student_department($id)
    {
        $this->db->select('department');
        $this->db->where(["id" => $id]);
        $result = $this->db->get('departments')->row_array();
        if ($result) {
            return $result['department'];
        } else {
            return 0;
        }
    }

    public function get_student_level($id)
    {
        $this->db->select('level');
        $this->db->where(["id" => $id]);
        $result = $this->db->get('levels')->row_array();
        if ($result) {
            return $result['level'];
        } else {
            return 0;
        }
    }

    public function get_student_institution($id)
    {
        $this->db->select('institution');
        $this->db->where(["id" => $id]);
        $result = $this->db->get('institutions')->row_array();
        if ($result) {
            return $result['institution'];
        } else {
            return 0;
        }
    }


    public function get_student_course($id)
    {
        $this->db->select('course');
        $this->db->where(["id" => $id]);
        $result = $this->db->get('courses')->row_array();
        if ($result) {
            return $result['course'];
        } else {
            return 0;
        }
    }

    public function get_student_question($id)
    {
        $this->db->select('subject');
        $this->db->where(["id" => $id]);
        $result = $this->db->get('questions')->row_array();
        if ($result) {
            return $result['subject'];
        } else {
            return 0;
        }
    }

    public function get_student_plan($id)
    {
        $this->db->select('plan');
        $this->db->where(["id" => $id]);
        $result = $this->db->get('plans')->row_array();
        if ($result) {
            return $result['plan'];
        } else {
            return 0;
        }
    }

    public function get_plan_points($id)
    {
        $this->db->select('points');
        $this->db->where(["id" => $id]);
        $result = $this->db->get('plans')->row_array();
        if ($result) {
            return $result['points'];
        } else {
            return 0;
        }
    }

    public function get_student_faculty($id)
    {
        $this->db->select('faculty');
        $this->db->where(["id" => $id]);
        $result = $this->db->get('faculties')->row_array();
        if ($result) {
            return $result['faculty'];
        } else {
            return 0;
        }
    }

    public function get_tag_via_tag($tag)
    {
        $this->db->select('id,tag');
        $this->db->where(["tag" => $tag]);
        $result = $this->db->get('tags')->row_array();
        if ($result) {
            return $result;
        } else {
            return 0;
        }
    }

    public function get_tag_via_id($id)
    {
        $this->db->select('id,tag');
        $this->db->where(["id" => $id]);
        $result = $this->db->get('tags')->row_array();
        if ($result) {
            return $result;
        } else {
            return 0;
        }
    }

    public function get_question_via_id($id)
    {
        $this->db->select('*');
        $this->db->where(["id" => $id]);
        $result = $this->db->get('questions')->row_array();
        if ($result) {
            return $result;
        } else {
            return 0;
        }
    }

    public function add_tag($data){
        if ($this->db->insert('tags',$data)) {
            $tag = $this->get_tag_via_tag($data['tag']);
            return $tag;
        }
        return false;
    }

    public function set_question_answered($id)
    {
        if ($this->db->update('questions',['answered' => 1],['id' => $id])) {
            return true;
        } else {
            return false;
        }
    }

    public function get_current_earning_points(){
        $this->db->select('*');
        $points = $this->db->order_by('id', "desc")->limit(1)->get('earning_points')->row_array();
        if ($points) {
            return $points['earning_point'];
        }
        return null;
    }

    public function get_current_charging_points(){
        $this->db->select('*');
        $points = $this->db->order_by('id', "desc")->limit(1)->get('charging_points')->row_array();
        if ($points) {
            return $points['charging_point'];
        }
        return null;
    }

    public function update_eduminister_points($data)
    {
        $update_points = $this->db->update('eduministers_profile',$data,['friconn_id' => $data['friconn_id']]);
        if ($update_points) {
            return $this->get_eduminister_points($data['friconn_id']);
        }
        return false;
    }


    public function get_eduminister_points($friconn_id){
        $this->db->select('points');
        $eduminister_points = $this->db->get_where('eduministers_profile',['friconn_id' => $friconn_id])->row_array();

        if ($eduminister_points) {
            return $eduminister_points['points'];
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

    public function get_learner_points($friconn_id){
        $this->db->select('points');
        $learner_points = $this->db->get_where('learners_profile',['friconn_id' => $friconn_id])->row_array();
        
        if ($learner_points) {
            return $learner_points['points'];
        }
        return null;
    }

    public function get_conversion_rate($id = '')
    {
        if ($id) {
            $this->db->select('naira_per_point');
            $this->db->where(["id" => $id]);
            $result = $this->db->get('conversion_rates')->row_array();
            if ($result) {
                return $result;
            } else {
                return 0;
            }
        }
        else{
            $this->db->select('naira_per_point');
            $result = $this->db->order_by('id','desc')->limit(1)->get('conversion_rates')->row_array();
            if ($result) {
                return $result;
            } else {
                return 0;
            }
        }
    }

    public function get_conversion_rate_id()
    {
     
        $this->db->select('id');
        $result = $this->db->order_by('id','desc')->limit(1)->get('conversion_rates')->row_array();
        if ($result) {
            return $result;
        } else {
            return 0;
        }
        
    }
}
