<?php
class Account_model extends CI_Model
{
    /* 
        @Description Account Model.
    */
        public function __construct()
        {
            $this->load->database();
            $this->load->model('fn_model');
            $this->status_code  = get_response_status_code();
        }

        public function check_verification_code($data)
        {
            $this->db->where(
                [
                    'friconn_id' => $data['friconn_id'],
                    'verification_code' => $data['verification_code']
                ]
            );

            $result = $this->db->get('users')->num_rows();
            if ($result > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function check_user_approval($friconn_id)
        {
            $this->db->select('approved');
            $this->db->where(
                [
                    'friconn_id' => $friconn_id
                ]
            );

            $user = $this->db->get('users')->row_array();
            if ($user['approved'] == 1) {
                return true;
            } else {
                return false;
            }
        }

        public function verify_user_account($friconn_id)
        {
            $query = $this->db->update('users', ['approved' => 1], array('friconn_id' => $friconn_id));
            $this->set_verification_code(['verification_code' => '', 'friconn_id' => $friconn_id]);
            if ($query) {
                return array(
                    'status' => 'success',
                    'message' => 'Email address verified successfully.',
                    'status_code' => $this->status_code['ok']
                );
            } else {
                return array(
                    'status' => "error",
                    'message' => "Opps! The server has encountered a temporary error. Please try again later",
                    'status_code' => $this->status_code['internalServerError']
                );
            }
        }

        public function set_verification_code($data)
        {
            if ($this->db->update(
                'users',
                [
                    'verification_code' => $data['verification_code']
                ],
                array(
                    'friconn_id' => $data['friconn_id']
                )
            )) {
                return true;
            } else {
                return false;
            }
        }

        public function get_account_email($friconn_id)
        {
            $this->db->where(["friconn_id" => $friconn_id]);
            $user = $this->db->get('users')->row_array();
            if ($user) {
                return $user['email'];
            } else {
                return '';
            }
        }

        public function save_password_request($data)
        {
            $friconn_id = $data['friconn_id'];
            $this->db->select('id');
            $this->db->where(["friconn_id" => $friconn_id]);

            $check_user_request = $this->db->get('password_requests')->num_rows();
            if ($check_user_request == 0) {
                if ($this->db->insert('password_requests',$data)) {
                    return true;
                }
            }
            else{
                if ($this->db->update('password_requests',$data,['friconn_id' => $data['friconn_id']])) {
                    return true;
                }
            }

            return false;
        }

        public function delete_password_request($token)
        {
            $this->db->where(["token" => $token]);
            if ($this->db->delete('password_requests')) {
                return true;
            }
            return false;
        }

        public function delete_user_password_request($friconn_id)
        {
            $this->db->where(["friconn_id" => $friconn_id]);
            if ($this->db->delete('password_requests')) {
                return true;
            }
            return false;
        }

        public function fetch_user_password_token($friconn_id)
        {

            $this->db->where("friconn_id", $friconn_id);
            $tokenCheck = $this->db->get('password_requests');
            if ($tokenCheck->num_rows() > 0) {
                return $tokenCheck->row_array();
            }
            return false;
        }


        public function fetch_password_token($token)
        {

            $this->db->where("token", $token);
            $tokenCheck = $this->db->get('password_requests');
            if ($tokenCheck->num_rows() > 0) {
                return $tokenCheck->row_array();
            }
            return false;
        }

        public function update_user_password($new_password,$friconn_id)
        {
            $update_password = $this->db->update('users',['password' => $new_password],['friconn_id' => $friconn_id]);
            if ($update_password) {
                return true;
            }
            return false; 
        }

        public function add_push_token($data)
        {
            $add_token = $this->db->insert('user_push_tokens',$data);
            if ($add_token) {
                return true;
            }
            return false; 
        }

        public function get_user_push_token($friconn_id)
        {
            $this->db->where("friconn_id", $friconn_id);
            $user_tokens = $this->db->get('user_push_tokens')->result();
            if ($user_tokens) {
                return $user_tokens;
            }
            return false; 
        }
    }
