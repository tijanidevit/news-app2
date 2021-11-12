<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Account extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('account_model');
        $this->load->model('fn_model');
        $this->status_code  = get_response_status_code();
        $this->load->library('form_validation');
    }

    function index_get()
    {

        $this->response([
            'status' => 'success',
            'message' => 'Account API Connected successful.',
            'time_connected' => date('d-M-Y h:i:s'),
            'domain' => base_url()
        ], REST_Controller::HTTP_OK);
    }

    public function password_post($action)
    {

        if ($action === 'token') { #verify user's password token

            $this->form_validation->set_rules('token', 'Password Reset Token', 'required');

            if ($this->form_validation->run() === FALSE) {
                return $this->response([
                    'status' => "error",
                    'message' => "Password Reset Token required.",
                    'status_code' => $this->status_code['badRequest']
                ], $this->status_code['badRequest']);
            } else {
                $token = $this->input->post('token');
                $token_data = $this->account_model->fetch_password_token($token);
                if (!$token_data) {
                    return $this->response([
                        'status' => 'error',
                        'message' => 'Unrecognized Token',
                        'status_code' => $this->status_code['notFound']
                    ], $this->status_code['notFound']);
                }

                return $this->response([
                    'status' => 'success',
                    'message' => 'Token Valid',
                    'data' => $token_data,
                    'status_code' => $this->status_code['ok']
                ], $this->status_code['ok']);
            }
        } else if ($action === 'request') { #request password token
            $email = $this->post('email');

            $this->form_validation->set_rules('email', 'Email', 'required');

            if ($this->form_validation->run() === FALSE) {
                return $this->response([
                    'status' => "error",
                    'message' => "Email address required.",
                    'status_code' => $this->status_code['badRequest']
                ], $this->status_code['badRequest']);
            }
            $user = $this->fn_model->get_user_via_email($email);

            if (!$user) {
                return $this->response([
                    "message" => "We couldn't find your email addres in our store. Would you try again ?",
                    "status" => "error",
                    "status_code" => "404"
                ], $this->status_code['notFound']);
            }


            $friconn_id = $user['friconn_id'];

            $link = urlify($friconn_id);
            $token_data = array(
                "token" => $link['token'],
                "friconn_id" => $friconn_id,
                "expires_at" => plushrs(date('Y-m-d h:i:s'), 24),
            );

            try {
                $save_password_request = $this->account_model->save_password_request($token_data);
                if (!$save_password_request) {
                    return $this->response([
                        "message" => "internalServerError",
                        "status" => "error",
                    ], $this->status_code['badRequest']);;
                }

                $emailMessage = "
                <p class='text-justify'>You requested for a password reset. Click the link below to reset your password</p>
                <p class='text-center'><a href='" . $link['url'] . "'>Click to reset password</a></p>
                <p>If you do not initial this, you may revoke password change access <a href='https://friconn.com/password/revoke'>here</a> </p>
                ";
                // $send = send_HTML_email($this, 'Friconn Password Reset', $emailMessage, $email);
                // if ($send) {
                $this->response([
                    "message" => "Okay. We are good to go! A reset link has been sent to your email address",
                    "status" => "success",
                    "data" =>
                    [
                        'token_data' => $token_data,
                        'link' => $link,
                    ]
                ], $this->status_code['ok']);
                // }
            } catch (Exception $e) {
                $this->response([
                    "message" => $e,
                    "status" => "error",
                ], $this->status_code['methodNotAllowed']);
            }
        } else if ($action === 'reset') { #reset forgotten password
            $this->form_validation->set_rules('friconn_id', 'Friconn ID', 'required');
            $this->form_validation->set_rules('new_password', 'New Password', 'required');

            if ($this->form_validation->run() === FALSE) {
                $this->response([
                    'status' => "error",
                    'message' => "All inputs are required.",
                    'status_code' => $this->status_code['badRequest']
                ], $this->status_code['badRequest']);
            } else {
                $new_password = $this->input->post('new_password');
                $friconn_id = $this->input->post('friconn_id');

                if (!$this->account_model->fetch_user_password_token($friconn_id)) {
                    return $this->response([
                        'status' => 'error',
                        'message' => 'User has not made any password reset request',
                        'status_code' => $this->status_code['notFound']
                    ], $this->status_code['notFound']);
                }

                $user_data = $this->fn_model->get_user_via_friconn_id($friconn_id);
                if (!$user_data) {
                    return $this->response([
                        'status' => 'error',
                        'message' => 'Unrecognized user',
                        'status_code' => $this->status_code['notFound']
                    ], $this->status_code['notFound']);
                }

                if (strlen('' . $new_password) < 6) {
                    return $this->response([
                        'status' => 'error',
                        'message' => 'New Password must be up to six character lengths',
                        'status_code' => $this->status_code['lengthRequired']
                    ], $this->status_code['lengthRequired']);
                }

                if ($this->account_model->update_user_password(encrypt($new_password), $friconn_id)) {
                    $this->account_model->delete_user_password_request($friconn_id);
                    return $this->response([
                        'status' => 'success',
                        'message' => 'Password updated successfully',
                        'status_code' => $this->status_code['ok']
                    ], $this->status_code['ok']);
                }

                return $this->response([
                    'status' => 'error',
                    'message' => 'Password not updated',
                    'status_code' => $this->status_code['badRequest']
                ], $this->status_code['badRequest']);
            }
        } else if ($action === 'update') { #user update password 
            $this->form_validation->set_rules('old_password', 'Old Password', 'required');
            $this->form_validation->set_rules('friconn_id', 'Friconn ID', 'required');
            $this->form_validation->set_rules('new_password', 'New Password', 'required');

            if ($this->form_validation->run() === FALSE) {
                $this->response([
                    'status' => "error",
                    'message' => "All inputs are required.",
                    'status_code' => $this->status_code['badRequest']
                ], $this->status_code['badRequest']);
            } else {
                $old_password = $this->input->post('old_password');
                $new_password = $this->input->post('new_password');
                $friconn_id = $this->input->post('friconn_id');

                $user_data = $this->fn_model->get_user_via_friconn_id($friconn_id);
                if (!$user_data) {
                    return $this->response([
                        'status' => 'error',
                        'message' => 'Unrecognized user',
                        'status_code' => $this->status_code['notFound']
                    ], $this->status_code['notFound']);
                }

                if ($user_data['password'] != encrypt($old_password)) {
                    return $this->response([
                        'status' => 'error',
                        'message' => 'Old password not correct',
                        'status_code' => $this->status_code['badRequest']
                    ], $this->status_code['badRequest']);
                }

                if (strlen('' . $new_password) < 6) {
                    return $this->response([
                        'status' => 'error',
                        'message' => 'New Password must be up to six character lengths',
                        'status_code' => $this->status_code['lengthRequired']
                    ], $this->status_code['lengthRequired']);
                }

                if ($new_password == $old_password) {
                    return $this->response([
                        'status' => 'error',
                        'message' => 'Old password and new password cannot be the same',
                        'status_code' => $this->status_code['badRequest']
                    ], $this->status_code['badRequest']);
                }

                if ($this->account_model->update_user_password(encrypt($new_password), $friconn_id)) {
                    return $this->response([
                        'status' => 'success',
                        'message' => 'Password updated successfully',
                        'status_code' => $this->status_code['ok']
                    ], $this->status_code['ok']);
                }

                return $this->response([
                    'status' => 'error',
                    'message' => 'Password not updated',
                    'status_code' => $this->status_code['badRequest']
                ], $this->status_code['badRequest']);
            }
        } else if ($action === 'revoke') {
            #revoke password token
            $token = $this->post('token');
            $this->form_validation->set_rules('token', 'Token', 'required');
            if ($this->form_validation->run() === FALSE) {
                return $this->response([
                    'status' => "error",
                    'message' => "Token required.",
                    'status_code' => $this->status_code['badRequest']
                ], $this->status_code['badRequest']);
            }
            if (!$this->account_model->fetch_password_token($token)) {
                return $this->response([
                    'status' => "error",
                    'message' => "Token not found.",
                    'status_code' => $this->status_code['notFound']
                ], $this->status_code['notFound']);
            }
            if ($this->account_model->delete_password_request($token)) {
                return $this->response([
                    'status' => "success",
                    'message' => "Token revoked successfully.",
                    'status_code' => $this->status_code['ok']
                ], $this->status_code['ok']);
            } else {
                return $this->response([
                    'status' => "error",
                    'message' => "Unable to revoke token.",
                    'status_code' => $this->status_code['badRequest']
                ], $this->status_code['badRequest']);
            }
        } else {
            return $this->response([
                'status' => "error",
                'message' => "Action not recognized.",
                'status_code' => $this->status_code['badRequest']
            ], $this->status_code['badRequest']);
        }
    }

    function email_post($action) #Email
    {
        if ($action == 'verify') { #verify email
            $this->form_validation->set_rules('verification_code', 'Verification Code', 'required');
            $this->form_validation->set_rules('friconn_id', 'Friconn ID', 'required');

            if ($this->form_validation->run() === FALSE) {
                $this->response([
                    'status' => "error",
                    'message' => "one or more required data is missing.",
                    'status_code' => $this->status_code['badRequest']
                ], $this->status_code['badRequest']);
            } else {

                $data = array(
                    'friconn_id' => $this->input->post('friconn_id'),
                    'verification_code' => $this->input->post('verification_code')
                );


                // if ($this->account_model->check_user_approval($data['friconn_id'])) {
                //     return $this->response([
                //         'status' => 'success',
                //         'message' => 'Email already verified',
                //         'status_code' => $this->status_code['ok']
                //     ], $this->status_code['ok']);
                // }

                $is_valid = $this->account_model->check_verification_code($data);

                if (true) {
                    $email = $this->account_model->get_account_email($data['friconn_id']);

                    $verification_response = $this->account_model->verify_user_account($data['friconn_id']);

                    $message ='
                    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                    <html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

                    <head>
                        <!--[if gte mso 9]><xml><o:OfficeDocumentSettings><o:AllowPNG/><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml><![endif]-->
                        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                        <meta name="viewport" content="width=device-width">
                        <!--[if !mso]><!-->
                        <meta http-equiv="X-UA-Compatible" content="IE=edge">
                        <!--<![endif]-->
                        <title></title>
                        <!--[if !mso]><!-->
                        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
                        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
                        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
                        <!--<![endif]-->
                        <style type="text/css">
                            body {
                                margin: 0;
                                padding: 0;
                            }

                            table,
                            td,
                            tr {
                                vertical-align: top;
                                border-collapse: collapse;
                            }

                            * {
                                line-height: inherit;
                            }

                            a[x-apple-data-detectors=true] {
                                color: inherit !important;
                                text-decoration: none !important;
                            }
                        </style>
                        <style type="text/css" id="media-query">
                            @media (max-width: 660px) {

                                .block-grid,
                                .col {
                                    min-width: 320px !important;
                                    max-width: 100% !important;
                                    display: block !important;
                                }

                                .block-grid {
                                    width: 100% !important;
                                }

                                .col {
                                    width: 100% !important;
                                }

                                .col_cont {
                                    margin: 0 auto;
                                }

                                img.fullwidth,
                                img.fullwidthOnMobile {
                                    max-width: 100% !important;
                                }

                                .no-stack .col {
                                    min-width: 0 !important;
                                    display: table-cell !important;
                                }

                                .no-stack.two-up .col {
                                    width: 50% !important;
                                }

                                .no-stack .col.num2 {
                                    width: 16.6% !important;
                                }

                                .no-stack .col.num3 {
                                    width: 25% !important;
                                }

                                .no-stack .col.num4 {
                                    width: 33% !important;
                                }

                                .no-stack .col.num5 {
                                    width: 41.6% !important;
                                }

                                .no-stack .col.num6 {
                                    width: 50% !important;
                                }

                                .no-stack .col.num7 {
                                    width: 58.3% !important;
                                }

                                .no-stack .col.num8 {
                                    width: 66.6% !important;
                                }

                                .no-stack .col.num9 {
                                    width: 75% !important;
                                }

                                .no-stack .col.num10 {
                                    width: 83.3% !important;
                                }

                                .video-block {
                                    max-width: none !important;
                                }

                                .mobile_hide {
                                    min-height: 0px;
                                    max-height: 0px;
                                    max-width: 0px;
                                    display: none;
                                    overflow: hidden;
                                    font-size: 0px;
                                }

                                .desktop_hide {
                                    display: block !important;
                                    max-height: none !important;
                                }
                            }
                        </style>
                    </head>

                    <body class="clean-body" style="margin: 0; padding: 0; -webkit-text-size-adjust: 100%; background-color: #f4f2f2;">
                        <!--[if IE]><div class="ie-browser"><![endif]-->
                        <table class="nl-container" style="table-layout: fixed; vertical-align: top; min-width: 320px; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f4f2f2; width: 100%;" cellpadding="0" cellspacing="0" role="presentation" width="100%" bgcolor="#f4f2f2" valign="top">
                            <tbody>
                                <tr style="vertical-align: top;" valign="top">
                                    <td style="word-break: break-word; vertical-align: top;" valign="top">
                                        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color:#f4f2f2"><![endif]-->
                                        <div style="background-color:transparent;">
                                            <div class="block-grid " style="min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: #0f1fb0;">
                                                <div style="border-collapse: collapse;display: table;width: 100%;background-color:#0f1fb0;">
                                                    <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                        <div class="col_cont" style="width:100% !important;">
                                                            <!--[if (!mso)&(!IE)]><!-->
                                                            <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:10px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                <!--<![endif]-->
                                                                <div class="img-container center fixedwidth" align="center" style="padding-right: 30px;padding-left: 30px;">
                                                                    <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 30px;padding-left: 30px;" align="center"><![endif]-->
                                                                    <div style="font-size:1px;line-height:30px">&nbsp;</div><a href="http://www.example.com" target="_blank" style="outline:none" tabindex="-1"><img class="center fixedwidth" align="center" border="0" src="https://d15k2d11r6t6rl.cloudfront.net/public/users/BeeFree/beefree-ffn5ecyn38i/Friconn-home-logo.png" alt="Your Logo" title="Your Logo" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: 0; width: 100%; max-width: 170px; display: block;" width="170"></a>
                                                                    <div style="font-size:1px;line-height:15px">&nbsp;</div>
                                                                    <!--[if mso]></td></tr></table><![endif]-->
                                                                </div>
                                                                <!--[if (!mso)&(!IE)]><!-->
                                                            </div>
                                                            <!--<![endif]-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="background-color:transparent;">
                                            <div class="block-grid " style="min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: #0f1fb0;">
                                                <div style="border-collapse: collapse;display: table;width: 100%;background-color:#0f1fb0;background-image:;background-position:top center;background-repeat:no-repeat">
                                                    <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                        <div class="col_cont" style="width:100% !important;">
                                                            <!--[if (!mso)&(!IE)]><!-->
                                                            <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                <!--<![endif]-->
                                                                <div class="img-container center fixedwidth" align="center">
                                                                    <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="" align="center"><![endif]--><img class="center fixedwidth" align="center" border="0" src="https://media2.giphy.com/media/zSz2KsgySmfjbb8NJS/giphy.gif?cid=20eb4e9d0k8848jg00g1ns2tuwc4buxu8njmbzgmsfyywszf&amp;rid=giphy.gif&amp;ct=s" alt="Welcome" title="Welcome" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: 0; width: 100%; max-width: 320px; display: block;" width="320">
                                                                    <!--[if mso]></td></tr></table><![endif]-->
                                                                </div>
                                                                <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 15px; padding-left: 15px; padding-top: 10px; padding-bottom: 0px; font-family: Arial, sans-serif"><![endif]-->
                                                                <div style="color:#555555;font-family:Nunito, Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:10px;padding-right:15px;padding-bottom:0px;padding-left:15px;">
                                                                    <div class="txtTinyMce-wrapper" style="line-height: 1.2; font-size: 12px; font-family: Nunito, Arial, Helvetica Neue, Helvetica, sans-serif; color: #555555; mso-line-height-alt: 14px;">
                                                                        <p style="margin: 0; font-size: 38px; line-height: 1.2; word-break: break-word; text-align: center; mso-line-height-alt: 46px; margin-top: 0; margin-bottom: 0;"><span style="color: #ffffff; font-size: 38px;"><strong>We\'re excited to have you<br></strong></span></p>
                                                                    </div>
                                                                </div>
                                                                <!--[if mso]></td></tr></table><![endif]-->
                                                                <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 15px; padding-left: 15px; padding-top: 0px; padding-bottom: 20px; font-family: Arial, sans-serif"><![endif]-->
                                                                <div style="color:#555555;font-family:Nunito, Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:0px;padding-right:15px;padding-bottom:20px;padding-left:15px;">
                                                                    <div class="txtTinyMce-wrapper" style="line-height: 1.2; font-size: 12px; font-family: Nunito, Arial, Helvetica Neue, Helvetica, sans-serif; color: #555555; mso-line-height-alt: 14px;">
                                                                        <p style="margin: 0; font-size: 38px; line-height: 1.2; word-break: break-word; text-align: center; mso-line-height-alt: 46px; margin-top: 0; margin-bottom: 0;"><span style="color: #ffffff; font-size: 38px;"><strong>join Friconn!</strong></span></p>
                                                                    </div>
                                                                </div>
                                                                <!--[if mso]></td></tr></table><![endif]-->
                                                                <!--[if (!mso)&(!IE)]><!-->
                                                            </div>
                                                            <!--<![endif]-->
                                                        </div>
                                                    </div>
                                                    <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                                                    <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
                                                </div>
                                            </div>
                                        </div>
                                        <div style="background-color:transparent;">
                                            <div class="block-grid " style="min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: #0f1fb0;">
                                                <div style="border-collapse: collapse;display: table;width: 100%;background-color:#0f1fb0;">
                                                    <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                        <div class="col_cont" style="width:100% !important;">
                                                            <!--[if (!mso)&(!IE)]><!-->
                                                            <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                <!--<![endif]-->
                                                                <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 25px; padding-left: 25px; padding-top: 20px; padding-bottom: 20px; font-family: Arial, sans-serif"><![endif]-->
                                                                <div style="color:#fffefe;font-family:Nunito, Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.5;padding-top:20px;padding-right:25px;padding-bottom:20px;padding-left:25px;">
                                                                    <div class="txtTinyMce-wrapper" style="line-height: 1.5; font-size: 12px; font-family: Nunito, Arial, Helvetica Neue, Helvetica, sans-serif; color: #fffefe; mso-line-height-alt: 18px;">
                                                                        <p style="margin: 0; font-size: 13px; line-height: 1.5; word-break: break-word; mso-line-height-alt: 20px; mso-ansi-font-size: 14px; margin-top: 0; margin-bottom: 0;"><span style="font-size: 13px; mso-ansi-font-size: 14px;">The time to solve your doubts and curiosity is now. We understand that everyone need help to develop and grow. We are here to make sure you get necessary and effective help. Remember, it is not help if it mislead you.</span></p>
                                                                        <p style="margin: 0; font-size: 13px; line-height: 1.5; word-break: break-word; mso-line-height-alt: 20px; mso-ansi-font-size: 14px; margin-top: 0; margin-bottom: 0;"><span style="font-size: 13px; mso-ansi-font-size: 14px;">Our platform offers you a chance to get moderated and verified answers to the questions &nbsp;from your studies. We exist to expand your horizon and give you a wider perspective of concepts.</span></p>
                                                                        <p style="margin: 0; font-size: 13px; line-height: 1.5; word-break: break-word; mso-line-height-alt: 20px; mso-ansi-font-size: 14px; margin-top: 0; margin-bottom: 0;"><span style="font-size: 13px; mso-ansi-font-size: 14px;">You matter to us. Your future matter to us. Your career matter to us. We do not see you as a user, we see you as a companion. And together, we can make the world a better place. It all start with learning the right way.</span></p>
                                                                    </div>
                                                                </div>
                                                                <!--[if mso]></td></tr></table><![endif]-->
                                                                <table cellpadding="0" cellspacing="0" role="presentation" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top">
                                                                    <tr style="vertical-align: top;" valign="top">
                                                                        <td style="word-break: break-word; vertical-align: top; padding-bottom: 0px; padding-left: 0px; padding-right: 0px; padding-top: 0px; text-align: center; width: 100%;" width="100%" align="center" valign="top">
                                                                            <h1 style="color:#ffffff;direction:ltr;font-family:Nunito, Arial, Helvetica Neue, Helvetica, sans-serif;font-size:23px;font-weight:normal;letter-spacing:normal;line-height:120%;text-align:center;margin-top:0;margin-bottom:0;"><strong>What\'s Next?</strong></h1>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 25px; padding-left: 25px; padding-top: 20px; padding-bottom: 20px; font-family: Arial, sans-serif"><![endif]-->
                                                                <div style="color:#fffefe;font-family:Nunito, Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.5;padding-top:20px;padding-right:25px;padding-bottom:20px;padding-left:25px;">
                                                                    <div class="txtTinyMce-wrapper" style="line-height: 1.5; font-size: 12px; font-family: Nunito, Arial, Helvetica Neue, Helvetica, sans-serif; color: #fffefe; mso-line-height-alt: 18px;">
                                                                        <p style="margin: 0; font-size: 13px; line-height: 1.5; word-break: break-word; text-align: center; mso-line-height-alt: 20px; mso-ansi-font-size: 14px; margin-top: 0; margin-bottom: 0;"><span style="font-size: 13px; mso-ansi-font-size: 14px;">It takes few minutes to ask questions, considering updating your profile to started.</span></p>
                                                                        <p style="margin: 0; font-size: 13px; line-height: 1.5; word-break: break-word; mso-line-height-alt: 20px; mso-ansi-font-size: 14px; margin-top: 0; margin-bottom: 0;">&nbsp;</p>
                                                                    </div>
                                                                </div>
                                                                <!--[if mso]></td></tr></table><![endif]-->
                                                                <div class="button-container" align="center" style="padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;"><a href="https://friconn.com/profile" target="_blank" style="-webkit-text-size-adjust: none; text-decoration: none; display: inline-block; color: #0f1fb0; background-color: #ffffff; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; width: auto; width: auto; border-top: 1px solid #8a3b8f; border-right: 1px solid #8a3b8f; border-bottom: 1px solid #8a3b8f; border-left: 1px solid #8a3b8f; padding-top: 5px; padding-bottom: 5px; font-family: Nunito, Arial, Helvetica Neue, Helvetica, sans-serif; text-align: center; mso-border-alt: none; word-break: keep-all;"><span style="padding-left:20px;padding-right:20px;font-size:16px;display:inline-block;letter-spacing:undefined;"><span style="font-size: 16px; line-height: 2; word-break: break-word; mso-line-height-alt: 32px;">Update Profile</span></span></a>
                                                                    <!--[if mso]></center></v:textbox></v:roundrect></td></tr></table><![endif]-->
                                                                </div>
                                                                <!--[if (!mso)&(!IE)]><!-->
                                                            </div>
                                                            <!--<![endif]-->
                                                        </div>
                                                    </div>
                                                    <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                                                    <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
                                                </div>
                                            </div>
                                        </div>
                                        <div style="background-color:transparent;">
                                            <div class="block-grid " style="min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: #0f1fb0;">
                                                <div style="border-collapse: collapse;display: table;width: 100%;background-color:#0f1fb0;">
                                                    <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                        <div class="col_cont" style="width:100% !important;">
                                                            <!--[if (!mso)&(!IE)]><!-->
                                                            <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                <div style="color:#555555;font-family:Nunito, Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.5;padding-top:30px;padding-right:15px;padding-bottom:15px;padding-left:15px;">
                                                                    <div class="txtTinyMce-wrapper" style="line-height: 1.5; font-size: 12px; font-family: Nunito, Arial, Helvetica Neue, Helvetica, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                                                                        <p style="margin: 0; font-size: 24px; line-height: 1.5; word-break: break-word; text-align: center; mso-line-height-alt: 36px; margin-top: 0; margin-bottom: 0;"><span style="font-size: 24px; color: #ffffff;"><strong>Don\'t just take our word for it.<br></strong></span></p>
                                                                    </div>
                                                                </div>
                                                                <!--[if mso]></td></tr></table><![endif]-->
                                                                <!--[if (!mso)&(!IE)]><!-->
                                                            </div>
                                                            <!--<![endif]-->
                                                        </div>
                                                    </div>
                                                    <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                                                    <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
                                                </div>
                                            </div>
                                        </div>
                                        <div style="background-color:transparent;">
                                            <div class="block-grid " style="min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: #0f1fb0;">
                                                <div style="border-collapse: collapse;display: table;width: 100%;background-color:#0f1fb0;">
                                                    <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:640px"><tr class="layout-full-width" style="background-color:#0f1fb0"><![endif]-->
                                                    <!--[if (mso)|(IE)]><td align="center" width="640" style="background-color:#0f1fb0;width:640px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:20px; padding-bottom:5px;"><![endif]-->
                                                    <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                        <div class="col_cont" style="width:100% !important;">
                                                            <!--[if (!mso)&(!IE)]><!-->
                                                            <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:20px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                <!--<![endif]-->
                                                                <div class="img-container center autowidth" align="center" style="padding-right: 0px;padding-left: 0px;">
                                                                    <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 0px;padding-left: 0px;" align="center"><![endif]--><img class="center autowidth" align="center" border="0" src="https://d1oco4z2z1fhwp.cloudfront.net/templates/default/3861/Bold_Blue_Quotation_Divider.png" alt="Alternate text" title="Alternate text" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: 0; width: 100%; max-width: 640px; display: block;" width="640">
                                                                    <!--[if mso]></td></tr></table><![endif]-->
                                                                </div>
                                                                <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 25px; padding-left: 25px; padding-top: 20px; padding-bottom: 45px; font-family: Arial, sans-serif"><![endif]-->
                                                                <div style="color:#ffffff;font-family:Nunito, Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.8;padding-top:20px;padding-right:25px;padding-bottom:45px;padding-left:25px;">
                                                                    <div class="txtTinyMce-wrapper" style="line-height: 1.8; font-size: 12px; font-family: Nunito, Arial, Helvetica Neue, Helvetica, sans-serif; color: #ffffff; mso-line-height-alt: 22px;">
                                                                        <p style="margin: 0; font-size: 22px; line-height: 1.8; text-align: center; word-break: break-word; mso-line-height-alt: 40px; margin-top: 0; margin-bottom: 0;"><em><span style="font-size: 16px;">With the help of Friconn, I was able to scale through my Java course with ease. <br></span></em></p>
                                                                        <p style="margin: 0; font-size: 22px; line-height: 1.8; text-align: center; word-break: break-word; mso-line-height-alt: 40px; margin-top: 0; margin-bottom: 0;"><em><span style="font-size: 16px;">– Akin Ebenezer<br></span></em></p>
                                                                    </div>
                                                                </div>
                                                                <!--[if mso]></td></tr></table><![endif]-->
                                                                <table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
                                                                    <tbody>
                                                                        <tr style="vertical-align: top;" valign="top">
                                                                            <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                <table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="50%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 1px solid #FFFFFF; width: 50%;" align="center" role="presentation" valign="top">
                                                                                    <tbody>
                                                                                        <tr style="vertical-align: top;" valign="top">
                                                                                            <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <!--[if (!mso)&(!IE)]><!-->
                                                            </div>
                                                            <!--<![endif]-->
                                                        </div>
                                                    </div>
                                                    <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                                                    <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
                                                </div>
                                            </div>
                                        </div>
                                        <div style="background-color:transparent;">
                                            <div class="block-grid " style="min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: #0f1fb0;">
                                                <div style="border-collapse: collapse;display: table;width: 100%;background-color:#0f1fb0;">
                                                    <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:640px"><tr class="layout-full-width" style="background-color:#0f1fb0"><![endif]-->
                                                    <!--[if (mso)|(IE)]><td align="center" width="640" style="background-color:#0f1fb0;width:640px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
                                                    <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                        <div class="col_cont" style="width:100% !important;">
                                                            <!--[if (!mso)&(!IE)]><!-->
                                                            <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                <!--<![endif]-->
                                                                <table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
                                                                    <tbody>
                                                                        <tr style="vertical-align: top;" valign="top">
                                                                            <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 15px; padding-right: 15px; padding-bottom: 15px; padding-left: 15px;" valign="top">
                                                                                <table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;" align="center" role="presentation" valign="top">
                                                                                    <tbody>
                                                                                        <tr style="vertical-align: top;" valign="top">
                                                                                            <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <!--[if (!mso)&(!IE)]><!-->
                                                            </div>
                                                            <!--<![endif]-->
                                                        </div>
                                                    </div>
                                                    <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                                                    <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
                                                </div>
                                            </div>
                                        </div>
                                        <div style="background-color:transparent;">
                                            <div class="block-grid " style="min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: #0f1fb0;">
                                                <div style="border-collapse: collapse;display: table;width: 100%;background-color:#0f1fb0;">
                                                    <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:640px"><tr class="layout-full-width" style="background-color:#0f1fb0"><![endif]-->
                                                    <!--[if (mso)|(IE)]><td align="center" width="640" style="background-color:#0f1fb0;width:640px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:25px; padding-bottom:25px;"><![endif]-->
                                                    <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                        <div class="col_cont" style="width:100% !important;">
                                                            <!--[if (!mso)&(!IE)]><!-->
                                                            <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:25px; padding-bottom:25px; padding-right: 0px; padding-left: 0px;">
                                                                <!--<![endif]-->
                                                                <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 25px; padding-left: 25px; padding-top: 30px; padding-bottom: 15px; font-family: Arial, sans-serif"><![endif]-->
                                                                <div style="color:#555555;font-family:Nunito, Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:30px;padding-right:25px;padding-bottom:15px;padding-left:25px;">
                                                                    <div class="txtTinyMce-wrapper" style="line-height: 1.2; font-size: 12px; font-family: Nunito, Arial, Helvetica Neue, Helvetica, sans-serif; color: #555555; mso-line-height-alt: 14px;">
                                                                        <p style="margin: 0; font-size: 16px; line-height: 1.2; word-break: break-word; text-align: center; mso-line-height-alt: 19px; margin-top: 0; margin-bottom: 0;"><span style="color: #ffffff;"><span style="caret-color: #ffffff; font-size: 30px;"><strong>Ask your first question now!<br></strong></span></span></p>
                                                                    </div>
                                                                </div>
                                                                <!--[if mso]></td></tr></table><![endif]-->
                                                                <div class="button-container" align="center" style="padding-top:20px;padding-right:10px;padding-bottom:25px;padding-left:10px;">
                                                                    <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"><tr><td style="padding-top: 20px; padding-right: 10px; padding-bottom: 25px; padding-left: 10px" align="center"><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="https://www.friconn.com/dashboard" style="height:27.75pt;width:115.5pt;v-text-anchor:middle;" arcsize="90%" stroke="false" fillcolor="#ffffff"><w:anchorlock/><v:textbox inset="0,0,0,0"><center style="color:#00bbdc; font-family:Arial, sans-serif; font-size:11px"><![endif]--><a href="https://www.friconn.com/dashboard" target="_blank" style="-webkit-text-size-adjust: none; text-decoration: none; display: inline-block; color: #00bbdc; background-color: #ffffff; border-radius: 33px; -webkit-border-radius: 33px; -moz-border-radius: 33px; width: auto; width: auto; border-top: 1px solid #ffffff; border-right: 1px solid #ffffff; border-bottom: 1px solid #ffffff; border-left: 1px solid #ffffff; padding-top: 0px; padding-bottom: 5px; font-family: Nunito, Arial, Helvetica Neue, Helvetica, sans-serif; text-align: center; mso-border-alt: none; word-break: keep-all;"><span style="padding-left:20px;padding-right:20px;font-size:11px;display:inline-block;letter-spacing:undefined;"><span style="font-size: 16px; margin: 0; line-height: 2; word-break: break-word; mso-line-height-alt: 32px;"><strong><span style="font-size: 11px; line-height: 22px;" data-mce-style="font-size: 11px; line-height: 22px;">GET STARTED<br></span></strong></span></span></a>
                                                                    <!--[if mso]></center></v:textbox></v:roundrect></td></tr></table><![endif]-->
                                                                </div>
                                                                <!--[if (!mso)&(!IE)]><!-->
                                                            </div>
                                                            <!--<![endif]-->
                                                        </div>
                                                    </div>
                                                    <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                                                    <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
                                                </div>
                                            </div>
                                        </div>
                                        <div style="background-color:transparent;">
                                            <div class="block-grid " style="min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: #0f1fb0;">
                                                <div style="border-collapse: collapse;display: table;width: 100%;background-color:#0f1fb0;">
                                                    <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:640px"><tr class="layout-full-width" style="background-color:#0f1fb0"><![endif]-->
                                                    <!--[if (mso)|(IE)]><td align="center" width="640" style="background-color:#0f1fb0;width:640px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:0px; padding-bottom:0px;"><![endif]-->
                                                    <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                        <div class="col_cont" style="width:100% !important;">
                                                            <!--[if (!mso)&(!IE)]><!-->
                                                            <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                <!--<![endif]-->
                                                                <div class="img-container center fixedwidth" align="center" style="padding-right: 30px;padding-left: 30px;">
                                                                    <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 30px;padding-left: 30px;" align="center"><![endif]-->
                                                                    <div style="font-size:1px;line-height:30px">&nbsp;</div><a href="http://www.example.com" target="_blank" style="outline:none" tabindex="-1"><img class="center fixedwidth" align="center" border="0" src="https://d15k2d11r6t6rl.cloudfront.net/public/users/BeeFree/beefree-ffn5ecyn38i/Friconn-home-logo.png" alt="Your Logo" title="Your Logo" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: 0; width: 100%; max-width: 170px; display: block;" width="170"></a>
                                                                    <div style="font-size:1px;line-height:15px">&nbsp;</div>
                                                                    <!--[if mso]></td></tr></table><![endif]-->
                                                                </div>
                                                                <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif"><![endif]-->
                                                                <div style="color:#ffffff;font-family:Nunito, Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                                                    <div class="txtTinyMce-wrapper" style="line-height: 1.2; font-size: 12px; font-family: Nunito, Arial, Helvetica Neue, Helvetica, sans-serif; color: #ffffff; mso-line-height-alt: 14px;">
                                                                        <p style="margin: 0; font-size: 14px; line-height: 1.2; word-break: break-word; text-align: center; mso-line-height-alt: 17px; margin-top: 0; margin-bottom: 0;">+234-807-835-1351<br><a href="mailto:http://www.example.com" target="_blank" title="http://www.example.com" style="text-decoration: underline; color: #ff8f05;" rel="noopener">info@friconn.com</a></p>
                                                                        <p style="margin: 0; font-size: 14px; line-height: 1.2; word-break: break-word; text-align: center; mso-line-height-alt: 17px; margin-top: 0; margin-bottom: 0;"><a href="http://www.friconn.com" target="_blank" style="text-decoration: underline; color: #ff8f05;" rel="noopener">www.friconn.com</a></p>
                                                                    </div>
                                                                </div>
                                                                <!--[if mso]></td></tr></table><![endif]-->
                                                                <table class="social_icons" cellpadding="0" cellspacing="0" width="100%" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top">
                                                                    <tbody>
                                                                        <tr style="vertical-align: top;" valign="top">
                                                                            <td style="word-break: break-word; vertical-align: top; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                <table class="social_table" align="center" cellpadding="0" cellspacing="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-tspace: 0; mso-table-rspace: 0; mso-table-bspace: 0; mso-table-lspace: 0;" valign="top">
                                                                                    <tbody>
                                                                                        <tr style="vertical-align: top; display: inline-block; text-align: center;" align="center" valign="top">
                                                                                            <td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 7.5px; padding-left: 7.5px;" valign="top"><a href="#" target="_blank"><img width="32" height="32" src="https://d2fi4ri5dhpqd1.cloudfront.net/public/resources/social-networks-icon-sets/t-circle-white/instagram@2x.png" alt="Instagram" title="instagram" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: 0; display: block;"></a></td>
                                                                                            <td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 7.5px; padding-left: 7.5px;" valign="top"><a href="#" target="_blank"><img width="32" height="32" src="https://d2fi4ri5dhpqd1.cloudfront.net/public/resources/social-networks-icon-sets/t-circle-white/youtube@2x.png" alt="YouTube" title="YouTube" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: 0; display: block;"></a></td>
                                                                                            <td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 7.5px; padding-left: 7.5px;" valign="top"><a href="http://www.facebook.com/friconnAfrica" target="_blank"><img width="32" height="32" src="https://d2fi4ri5dhpqd1.cloudfront.net/public/resources/social-networks-icon-sets/t-circle-white/facebook@2x.png" alt="Facebook" title="facebook" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: 0; display: block;"></a></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <!--[if (!mso)&(!IE)]><!-->
                                                            </div>
                                                            <!--<![endif]-->
                                                        </div>
                                                    </div>
                                                    <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                                                    <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
                                                </div>
                                            </div>
                                        </div>
                                        <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!--[if (IE)]></div><![endif]-->
                    </body>

                    </html>';
                    send_mailjet_email('Welcome on Board', $message, $email, 'Friconn User');

                    $this->response(
                        $verification_response,
                        $verification_response['status_code']
                    );
                } else {
                    $this->response([
                        'status' => 'error',
                        'message' => 'Invalid or expired verification code',
                        'status_code' => $this->status_code['badRequest']
                    ], $this->status_code['badRequest']);
                }
            }
        } else if ($action === 'request') {  #request email verification code
            $this->form_validation->set_rules('friconn_id', 'Friconn ID', 'required');

            if ($this->form_validation->run() === FALSE) {
                $this->response([
                    'status' => "error",
                    'message' => "Friconn ID is missing.",
                    'status_code' => $this->status_code['badRequest']
                ], $this->status_code['badRequest']);
            } else {
                $data = array(
                    'friconn_id' => $this->input->post('friconn_id'),
                    'verification_code' => rand(111111, 999999)
                );


                if ($this->account_model->check_user_approval($data['friconn_id'])) {
                    return $this->response([
                        'status' => 'success',
                        'message' => 'Email already verified',
                        'status_code' => $this->status_code['ok']
                    ], $this->status_code['ok']);
                }


                $user_email = $this->account_model->get_account_email($data['friconn_id']);
                if ($user_email !== '') {
                    if ($this->account_model->set_verification_code($data)) {
                        send_HTML_email($this, 'Verification Code Resent', $data['verification_code'], $user_email);
                        $this->response(
                            [
                                'status' => 'success',
                                'message' => 'Verification code sent successfully',
                                'status_code' => $this->status_code['ok']
                            ],
                            $this->status_code['ok']
                        );
                    } else {
                        $this->response([
                            'status' => "error",
                            'message' => "Opps! The server has encountered a temporary error. Please try again later",
                            'status_code' => $this->status_code['internalServerError']
                        ]);
                    }
                } else {
                    $this->response([
                        'status' => 'error',
                        'message' => 'Acccount not found',
                        'status_code' => $this->status_code['notFound']
                    ], $this->status_code['notFound']);
                }
            }
        }
    }




    public function push_token_post()
    {

        $this->form_validation->set_rules('token', 'User Token', 'required');
        $this->form_validation->set_rules('friconn_id', 'Friconn ID', 'required');

        if ($this->form_validation->run() === FALSE) {
            return $this->response([
                'status' => "error",
                'message' => "User Token and Friconn ID required.",
                'status_code' => $this->status_code['badRequest']
            ], $this->status_code['badRequest']);
        } 
        else {
            $token = $this->input->post('token');
            $friconn_id = $this->input->post('friconn_id');

            $data = [
                'friconn_id' => $friconn_id,
                'token' => $token,
            ];

            $token_data = $this->account_model->add_push_token($data);
            
            return $this->response([
                'status' => 'success',
                'message' => 'Token Added Successfully',
                'data' => $token_data,
                'status_code' => $this->status_code['ok']
            ], $this->status_code['ok']);
        }
    }
}
