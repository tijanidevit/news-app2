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

        

        INSERT INTO `categories` (`id`, `category`, `created_at`, `updated_at`) VALUES
        (1, 'Project', '2021-10-20 11:49:42', '2021-10-20 11:49:42'),
        (2, 'Seminar', '2021-10-20 11:49:42', '2021-10-20 11:49:42');


        INSERT INTO `gists` (`id`, `student_id`, `category_id`, `title`, `content`, `featured_image`, `status`, `views`, `created_at`, `updated_at`) VALUES
        (1, 1, 1, 'When is NACOS Day?', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\r\n            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\r\n            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\r\n            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\r\n            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\r\n            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'img.png', 1, 8, '2021-10-22 09:13:18', '2021-11-02 18:51:36'),
        (2, 1, 1, 'Result Will Be Out Soon', 'Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon ', '', 0, 4, '2021-11-02 18:17:55', '2021-11-02 19:06:55'),
        (3, 1, 1, 'Result Will Be Out Soon', 'Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon ', '', 0, 0, '2021-11-02 18:19:56', '2021-11-02 18:19:56'),
        (4, 1, 1, 'Result Will Be Out Soon', 'Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon ', '', 0, 0, '2021-11-02 18:20:58', '2021-11-02 18:20:58');


        INSERT INTO `gist_comments` (`id`, `gist_id`, `student_id`, `comment`, `created_at`) VALUES
        (NULL, 1, 1, 'Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon ', '2021-11-02 18:45:14');

        INSERT INTO `levels` (`id`, `level`) VALUES
        (1, 'ND1'),
        (2, 'ND2');

        INSERT INTO `posts` (`id`, `category_id`, `title`, `content`, `featured_image`, `views`, `status`, `created_at`, `updated_at`) VALUES
        (1, 1, 'A Simple Test', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\r\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\r\nquis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\r\nconsequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\r\ncillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\r\nproident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2003-Davido.jpg', 2, 1, '2021-10-20 06:53:01', '2021-11-02 17:35:47'),
        (2, 2, 'Davido Test', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\r\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\r\nquis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\r\nconsequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\r\ncillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\r\nproident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '8601-Davido.jpg', 0, 1, '2021-10-20 06:56:53', '2021-10-20 12:08:28'),
        (3, 2, 'Davido Test', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\r\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\r\nquis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\r\nconsequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\r\ncillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\r\nproident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2003-Davido.jpg', 0, 1, '2021-10-20 06:57:08', '2021-10-20 12:08:24'),
        (4, 1, 'Another Test', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\r\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\r\nquis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\r\nconsequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\r\ncillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\r\nproident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '8854-fpi_logo.png', 0, 1, '2021-10-20 06:58:23', '2021-10-20 12:08:30'),
        (5, 1, 'Hello World', 'Hello World Content!', '8431-Davido.jpg', 0, 1, '2021-10-20 12:04:09', '2021-10-20 12:04:09');



        INSERT INTO `students` (`id`, `matric_no`, `level_id`, `fullname`, `email`, `image`, `gender`, `password`, `status`, `created_at`, `updated_at`) VALUES
        (1, 'N/CS/17/0900', 1, 'Abara Chima Odiri', 'odiri@gmail.com', 'od.png', 'M', '3b1f8519c8d10b83f4680968b7f0d103', 1, '2021-10-22 08:19:22', '2021-10-22 08:19:22'),
        (2, '', 1, 'Ayomide Aniyah', 'fine@me.com', '', 'F', 'MWE3bVh2aXBYdGpDVjdNc2lhWmdrZz09', 1, '2021-11-02 16:29:17', '2021-11-02 16:29:17'),
        (3, '', 1, 'Ayomide Aniyah', 'bad@me.com', '', 'F', 'MWE3bVh2aXBYdGpDVjdNc2lhWmdrZz09', 1, '2021-11-02 16:30:12', '2021-11-02 16:30:12'),
        (4, 'H/CS/19/0911', 1, 'Ayomide Aniyah', 'you@me.com', '', 'F', 'MWE3bVh2aXBYdGpDVjdNc2lhWmdrZz09', 1, '2021-11-02 16:32:52', '2021-11-02 16:32:52');

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
            
        CREATE TABLE `gists` (
        `id` int(11) NOT NULL,
        `student_id` int(11) NOT NULL,
        `category_id` int(11) NOT NULL,
        `title` varchar(120) NOT NULL,
        `content` text NOT NULL,
        `featured_image` varchar(150) NOT NULL,
        `status` int(11) DEFAULT 1,
        `views` int(11) NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


        ";
        $this->setup_model->runQuert($query);
    }
}