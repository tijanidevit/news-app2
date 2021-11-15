<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Setup extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('setup_model');
    }

    function db_get()
    {
        $query = "
        INSERT INTO `admin` (`id`, `email`, `password`, `created_at`) VALUES
        (1, 'admin@news.com', '39d5b40ca6272059833743b327220089', '2021-10-20 05:50:58');
        ";

        // $posts = $this->posts_model->get_posts();

        $this->setup_model->runQuert($query);
        $this->response([
            'status' => 'success',
            'message' => 'posts API Connected successful.',
            'time_connected' => date('d-M-Y h:i:s'),
            'domain' => base_url()
        ], REST_Controller::HTTP_OK);
    }

    function db1_get()
    {
        $query = "
        INSERT INTO `categories` (`id`, `category`, `created_at`, `updated_at`) VALUES
        (1, 'Project', '2021-10-20 11:49:42', '2021-10-20 11:49:42'),
        (2, 'Seminar', '2021-10-20 11:49:42', '2021-10-20 11:49:42');
        (3, 'Sports', '2021-10-20 11:49:42', '2021-10-20 11:49:42');
        (4, 'Programming', '2021-10-20 11:49:42', '2021-10-20 11:49:42');
        (5, 'Assignment', '2021-10-20 11:49:42', '2021-10-20 11:49:42');
        (6, 'Others', '2021-10-20 11:49:42', '2021-10-20 11:49:42');
        ";

        // $posts = $this->posts_model->get_posts();

        $this->setup_model->runQuert($query);
        $this->response([
            'status' => 'success',
            'message' => 'posts API Connected successful.',
            'time_connected' => date('d-M-Y h:i:s'),
            'domain' => base_url()
        ], REST_Controller::HTTP_OK);
    }


    function db2_get()
    {
        $query = "
        INSERT INTO `gists` (`id`, `student_id`, `category_id`, `title`, `content`, `featured_image`, `status`, `views`, `created_at`, `updated_at`) VALUES
        (1, 1, 1, 'When is NACOS Day?', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\r\n            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\r\n            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\r\n            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\r\n            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\r\n            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'img.png', 1, 8, '2021-10-22 09:13:18', '2021-11-02 18:51:36'),
        (2, 1, 1, 'Result Will Be Out Soon', 'Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon ', '', 0, 4, '2021-11-02 18:17:55', '2021-11-02 19:06:55'),
        (3, 1, 1, 'Result Will Be Out Soon', 'Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon ', '', 0, 0, '2021-11-02 18:19:56', '2021-11-02 18:19:56'),
        (4, 1, 1, 'Result Will Be Out Soon', 'Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon ', '', 0, 0, '2021-11-02 18:20:58', '2021-11-02 18:20:58');
        ";

        // $posts = $this->posts_model->get_posts();

        $this->setup_model->runQuert($query);
        $this->response([
            'status' => 'success',
            'message' => 'posts API Connected successful.',
            'time_connected' => date('d-M-Y h:i:s'),
            'domain' => base_url()
        ], REST_Controller::HTTP_OK);
    }


    function db3_get()
    {
        $query = "
        INSERT INTO `gist_comments` (`id`, `gist_id`, `student_id`, `comment`, `created_at`) VALUES
        (NULL, 1, 1, 'Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon ', '2021-11-02 18:45:14');
        ";

        // $posts = $this->posts_model->get_posts();

        $this->setup_model->runQuert($query);
        $this->response([
            'status' => 'success',
            'message' => 'posts API Connected successful.',
            'time_connected' => date('d-M-Y h:i:s'),
            'domain' => base_url()
        ], REST_Controller::HTTP_OK);
    }




    function db3_get()
    {
        $query = "

        INSERT INTO `levels` (`id`, `level`) VALUES
        (1, 'ND1'),
        (2, 'ND2');
        (3, 'HND1');
        (4, 'HND2');

        ";

        // $posts = $this->posts_model->get_posts();

        $this->setup_model->runQuert($query);
        $this->response([
            'status' => 'success',
            'message' => 'posts API Connected successful.',
            'time_connected' => date('d-M-Y h:i:s'),
            'domain' => base_url()
        ], REST_Controller::HTTP_OK);
    }



    function dba_get()
    {
        $query = "

        INSERT INTO `posts` (`id`, `category_id`, `title`, `content`, `featured_image`, `views`, `status`, `created_at`, `updated_at`) VALUES
        (1, 1, 'A Simple Test', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\r\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\r\nquis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\r\nconsequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\r\ncillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\r\nproident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'https://images.unsplash.com/photo-1484318571209-661cf29a69c3?ixid=MnwxMjA3fDB8MHxzZWFyY2h8Nnx8YWZyaWNhfGVufDB8fDB8fA%3D%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60', 2, 1, '2021-10-20 06:53:01', '2021-11-02 17:35:47'),

        (2, 2, 'Davido Test', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\r\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\r\nquis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\r\nconsequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\r\ncillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\r\nproident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'https://images.unsplash.com/photo-1484318571209-661cf29a69c3?ixid=MnwxMjA3fDB8MHxzZWFyY2h8Nnx8YWZyaWNhfGVufDB8fDB8fA%3D%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60', 0, 1, '2021-10-20 06:56:53', '2021-10-20 12:08:28'),

        (3, 2, 'Davido Test', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\r\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\r\nquis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\r\nconsequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\r\ncillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\r\nproident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'https://images.unsplash.com/photo-1484318571209-661cf29a69c3?ixid=MnwxMjA3fDB8MHxzZWFyY2h8Nnx8YWZyaWNhfGVufDB8fDB8fA%3D%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60', 0, 1, '2021-10-20 06:57:08', '2021-10-20 12:08:24'),

        (4, 1, 'Another Test', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\r\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\r\nquis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\r\nconsequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\r\ncillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\r\nproident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'https://images.unsplash.com/photo-1484318571209-661cf29a69c3?ixid=MnwxMjA3fDB8MHxzZWFyY2h8Nnx8YWZyaWNhfGVufDB8fDB8fA%3D%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60', 0, 1, '2021-10-20 06:58:23', '2021-10-20 12:08:30'),
        
        (5, 1, 'Hello World', 'Hello World Content!', 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?ixid=MnwxMjA3fDB8MHxzZWFyY2h8MXx8YWZyaWNhfGVufDB8fDB8fA%3D%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60', 0, 1, '2021-10-20 12:04:09', '2021-10-20 12:04:09');
        ";

        // $posts = $this->posts_model->get_posts();

        $this->setup_model->runQuert($query);
        $this->response([
            'status' => 'success',
            'message' => 'posts API Connected successful.',
            'time_connected' => date('d-M-Y h:i:s'),
            'domain' => base_url()
        ], REST_Controller::HTTP_OK);
    }


    function db4_get()
    {
        $query = "

        INSERT INTO `students` (`id`, `matric_no`, `level_id`, `fullname`, `email`, `image`, `gender`, `password`, `status`, `created_at`, `updated_at`) VALUES
        (1, 'N/CS/17/0900', 1, 'Abara Chima Odiri', 'odiri@gmail.com', 'https://media.istockphoto.com/vectors/happy-handsome-man-showing-thumbs-up-concept-illustration-in-cartoon-vector-id980239992?k=20&m=980239992&s=612x612&w=0&h=xAzgjGwK4PPI3k6ZMnPT-I-_BYjeIGwwIf-OgTYMZoc=', 'M', '3b1f8519c8d10b83f4680968b7f0d103', 1, '2021-10-22 08:19:22', '2021-10-22 08:19:22'),
        (2, '', 1, 'Ayomide Aniyah', 'fine@me.com', 'https://media.istockphoto.com/vectors/happy-handsome-man-showing-thumbs-up-concept-illustration-in-cartoon-vector-id980239992?k=20&m=980239992&s=612x612&w=0&h=xAzgjGwK4PPI3k6ZMnPT-I-_BYjeIGwwIf-OgTYMZoc=', 'F', 'MWE3bVh2aXBYdGpDVjdNc2lhWmdrZz09', 1, '2021-11-02 16:29:17', '2021-11-02 16:29:17'),
        (3, '', 1, 'Ayomide Aniyah', 'bad@me.com', 'https://media.istockphoto.com/vectors/happy-handsome-man-showing-thumbs-up-concept-illustration-in-cartoon-vector-id980239992?k=20&m=980239992&s=612x612&w=0&h=xAzgjGwK4PPI3k6ZMnPT-I-_BYjeIGwwIf-OgTYMZoc=', 'F', 'MWE3bVh2aXBYdGpDVjdNc2lhWmdrZz09', 1, '2021-11-02 16:30:12', '2021-11-02 16:30:12'),
        (4, 'H/CS/19/0911', 1, 'Ayomide Aniyah', 'you@me.com', 'https://media.istockphoto.com/vectors/happy-handsome-man-showing-thumbs-up-concept-illustration-in-cartoon-vector-id980239992?k=20&m=980239992&s=612x612&w=0&h=xAzgjGwK4PPI3k6ZMnPT-I-_BYjeIGwwIf-OgTYMZoc=', 'F', 'MWE3bVh2aXBYdGpDVjdNc2lhWmdrZz09', 1, '2021-11-02 16:32:52', '2021-11-02 16:32:52');

        ";

        // $posts = $this->posts_model->get_posts();

        $this->setup_model->runQuert($query);
        $this->response([
            'status' => 'success',
            'message' => 'posts API Connected successful.',
            'time_connected' => date('d-M-Y h:i:s'),
            'domain' => base_url()
        ], REST_Controller::HTTP_OK);
    }

    function db5_get()
    {
        $query = "

        COMMIT;
        ";

        // $posts = $this->posts_model->get_posts();

        $this->setup_model->runQuert($query);
        $this->response([
            'status' => 'success',
            'message' => 'posts API Connected successful.',
            'time_connected' => date('d-M-Y h:i:s'),
            'domain' => base_url()
        ], REST_Controller::HTTP_OK);
    }

    function ag_get(){
        $query = "
        
        CREATE TABLE `gist_likes` (
        `id` int(11) NOT NULL,
        `gist_id` int(11) NOT NULL,
        `student_id` int(11) NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp()
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        ";
        $this->setup_model->runQuert($query);
    }


    function agb_get(){
        $query = "
        CREATE TABLE `levels` (
        `id` int(11) NOT NULL,
        `level` varchar(120) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        ";
        $this->setup_model->runQuert($query);
    }



    function ag1_get(){
        $query = "
        
        CREATE TABLE `posts` (
        `id` int(11) NOT NULL,
        `category_id` int(11) NOT NULL,
        `title` varchar(120) NOT NULL,
        `content` text NOT NULL,
        `featured_image` varchar(120) NOT NULL,
        `views` int(11) NOT NULL DEFAULT 0,
        `status` int(11) NOT NULL DEFAULT 1,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";
        $this->setup_model->runQuert($query);
    }



    function ag2_get(){
        $query = "
                
        CREATE TABLE `students` (
        `id` int(11) NOT NULL,
        `matric_no` varchar(20) NOT NULL,
        `level_id` int(11) NOT NULL,
        `fullname` varchar(120) NOT NULL,
        `email` varchar(120) NOT NULL,
        `image` varchar(120) NOT NULL,
        `gender` varchar(1) NOT NULL,
        `password` text NOT NULL,
        `status` int(11) NOT NULL DEFAULT 1,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        ";
        $this->setup_model->runQuert($query);
    }



    function ag3_get(){
        $query = "


        ALTER TABLE `admin`
        ADD PRIMARY KEY (`id`);
        ";
        $this->setup_model->runQuert($query);
    }


    function ag4_get(){
        $query = "
        ALTER TABLE `categories`
        ADD PRIMARY KEY (`id`);

        ";
        $this->setup_model->runQuert($query);
    }


    function ag5_get(){
        $query = "
        ALTER TABLE `gists`
        ADD PRIMARY KEY (`id`);
        ";
        $this->setup_model->runQuert($query);
    }


    function ag6_get(){
        $query = "

        ALTER TABLE `gist_likes`
        ADD PRIMARY KEY (`id`);
        ";
        $this->setup_model->runQuert($query);
    }



    function ag7_get(){
        $query = "
        
        ALTER TABLE `levels`
        ADD PRIMARY KEY (`id`);
        ";
        $this->setup_model->runQuert($query);
    }



    function ag8_get(){
        $query = "
    

        ALTER TABLE `posts`
        ADD PRIMARY KEY (`id`);
        ";
        $this->setup_model->runQuert($query);
    }



    function ag9_get(){
        $query = "

        ALTER TABLE `students`
        ADD PRIMARY KEY (`id`);
        ";
        $this->setup_model->runQuert($query);
    }



    function ag10_get(){
        $query = "

        ALTER TABLE `admin`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

        ";
        $this->setup_model->runQuert($query);
    }



    function ag11_get(){
        $query = "

        ALTER TABLE `categories`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
        ";
        $this->setup_model->runQuert($query);
    }

    function ag12_get(){
        $query = "

        ALTER TABLE `gists`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
        ";
        $this->setup_model->runQuert($query);
    }

    function ag13_get(){
        $query = "

        ALTER TABLE `gist_likes`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
        ";
        $this->setup_model->runQuert($query);
    }

    function ag14_get(){
        $query = "

        ALTER TABLE `levels`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

        ";
        $this->setup_model->runQuert($query);
    }

    function ag15_get(){
        $query = "

        ALTER TABLE `posts`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
        ";
        $this->setup_model->runQuert($query);
    }


    function ag16_get(){
        $query = "

        ALTER TABLE `students`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
        ";
        $this->setup_model->runQuert($query);
    }


    function og_get(){
        $query = "

        ALTER TABLE `levels`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
        ";
        $this->setup_model->runQuert($query);
    }


    function og1_get(){
        $query = "

        ALTER TABLE `posts`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

        ALTER TABLE `students`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
        ";
        $this->setup_model->runQuert($query);
    }


    function og2_get(){
        $query = "

        ALTER TABLE `posts`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
        ";
        $this->setup_model->runQuert($query);
    }


    function og3_get(){
        $query = "

        ALTER TABLE `students`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
        ";
        $this->setup_model->runQuert($query);
    }


    function og4_get(){
        $query = "
        COMMIT
        ";
        $this->setup_model->runQuert($query);
    }

}