<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Categories extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('categories_model');
        $this->load->model('fn_model');
        $this->status_code  = get_response_status_code();
        $this->load->library('form_validation');
    }

    function index_get()
    {
        $this->response([
            'status' => 'success',
            'message' => 'categories API Connected successful.',
            'time_connected' => date('d-M-Y h:i:s'),
            'domain' => base_url()
        ], REST_Controller::HTTP_OK);
    }

    function view_get($slug = '')
    {
        if (!$slug) {
            $categories = $this->categories_model->get_categories();
            if ($categories == null) {
                return $this->response([
                    'status' => "error",
                    'message' => "No categories added yet.",
                    'status_code' => $this->status_code['ok'],
                ], $this->status_code['ok']);
            }
            return $this->response([
                'status' => "success",
                'message' => "Categories fetched successfully.",
                'status_code' => $this->status_code['ok'],
                'data' => $categories
            ], $this->status_code['ok']);
        }
        else{
            $category = $this->categories_model->get_category($slug);
            if ($category == null) {
                return $this->response([
                    'status' => "error",
                    'message' => "category not found.",
                    'status_code' => $this->status_code['ok'],
                ], $this->status_code['ok']);
            }
            return $this->response([
                'status' => "success",
                'message' => "category fetched successfully.",
                'status_code' => $this->status_code['ok'],
                'data' => $category
            ], $this->status_code['ok']);
        }
    }
}