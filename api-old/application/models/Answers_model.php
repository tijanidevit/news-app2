<?php
class Answers_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->load->model('fn_model');
        $this->status_code  = get_response_status_code();
    }

    public function get_answer($id){
        $this->db->select('*');
        $answer = $this->db->get_where('question_answers',['id' => $id])->row_array();

        if ($answer) {

            $user = $this->fn_model->get_user_via_friconn_id($answer['friconn_id']);
            $answer['user'] = $user['last_name']. ' '.$user['other_names'];

            $answer['upvotes'] = $this->get_answer_upvotes($answer['id']);
            $answer['downvotes'] = $this->get_answer_downvotes($answer['id']);
            return $answer;
        }
        return null;
    }

    public function get_answers(){
        $this->db->select('*');
        $answers = $this->db->order_by('id','desc')->get('question_answers')->result();

        if ($answers) {
            foreach ($answers as $answer) {
                $user = $this->fn_model->get_user_via_friconn_id($answer->friconn_id);
                $answer->user = $user['last_name']. ' '.$user['other_names'];
                
                
                $answer->upvotes = $this->get_answer_upvotes($answer->id);
                $answer->downvotes = $this->get_answer_downvotes($answer->id);

            }
            return $answers;
        }
        return null;
    }
    public function get_answer_upvotes($id){
        $this->db->select('id');
        return $this->db->get_where('answer_votes',['answer_id' => $id,'vote_type' => 1])->num_rows();
    }

    public function get_answer_downvotes($id){
        $this->db->select('id');
        return $this->db->get_where('answer_votes',['answer_id' => $id,'vote_type' => 0])->num_rows();
    }
    

    public function vote_answer($data){
        if ($this->db->insert('answer_votes',$data)) {
            return true;
        }
        return false;
    }

    public function check_user_answer_vote($data){
        $this->db->select('id');
        $answer_vote = $this->db->get_where('answer_votes',['answer_id' => $data['answer_id'],'friconn_id' => $data['friconn_id']])->num_rows();


        if ($answer_vote > 0) {
            return true;
        }
        return null;
    }

    
    public function get_answer_votes($id){
        $this->db->select('*');
        $this->db->where(['answer_id' => $id]);
        $answer_answers = $this->db->order_by('id', "desc")->get('answer_votes')->result();

        if ($answer_answers) {
            $answer = $this->get_answer($id);
            $answer_answers['answer'] = $answer['subject'];

            foreach ($answer_answers as $answer_answer) {
                $user = $this->fn_model->get_user_via_friconn_id($answer_answer->friconn_id);
                $answer_answer->user = $user['last_name'].' '.$user['other_names'];
            }

            return $answer_answers;
        }
        return null;
    }


    public function get_user_last_answer($friconn_id){
        $this->db->select('*');
        $this->db->where(['friconn_id' => $friconn_id]);
        $answer_answer = $this->db->order_by('id', "desc")->limit(1)->get('answer_answers')->row_array();

        if ($answer_answer) {
            $answer = $this->get_answer($answer_answer['answer_id']);
            $answer_answer['answer'] = $answer['subject'];

            $user = $this->fn_model->get_user_via_friconn_id($answer_answer['friconn_id']);
            $answer_answer['user'] = $user['last_name'].' '.$user['other_names'];

            return $answer_answer;
        }
        return null;
    }
}
