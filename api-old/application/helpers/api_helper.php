<?php
defined('BASEPATH') or exit('No direct script access allowed');
use \Mailjet\Resources;

use  CWG\OneSignal\OneSignal ;
use  CWG\OneSignal\Device ;

function add_to_mailing_list($email,$name,$list){
    require 'vendor/autoload.php';

    $mj = new \Mailjet\Client('59d422991973a13458e114d6980eb0c9', '412c3f6358f03ec46cac3e76f9c42724',true,['version' => 'v3.1']);
    $body = [
        'IsExcludedFromCampaigns' => "false",
        'Name' => $name,
        'Email' => $email
    ];
    $response = $mj->post(Resources::$Contact, ['body' => $body]);
    $response->success() && var_dump($response->getData());
}

function add_onesignal_device($friconn_id)
{
    require 'vendor/autoload.php';

    $appID = '038c8a5c-ccd3-4366-9906-21de9b0a0a09' ;
    $authorizationRestApiKey = 'OWVjYjg4OTItMmM4My00MDI5LTgwYTYtMDJiNmU5N2FlNjEz' ;

    $api = new  OneSignal($appID ,$authorizationRestApiKey );

    //Creating the Device 
    $return = $api ->device -> setLanguage ('en')
    -> setIdentifier($friconn_id)
    -> setDevice(Device :: WEB)
    -> addTag('enrolls' , '11111111')
    -> addTag('course' , '12312312')
    -> addTag('class' , '1111')
    -> create();


    return $return;
}


function update_onesignal_device($value='')
{
    require 'vendor/autoload.php';

    $appID = '038c8a5c-ccd3-4366-9906-21de9b0a0a09' ;
    $authorizationRestApiKey = 'OWVjYjg4OTItMmM4My00MDI5LTgwYTYtMDJiNmU5N2FlNjEz' ;
    $deviceID = '69aeecc1-7b58-44d1-8000-7767de437adf' ;

    $api = new  OneSignal($appID ,$authorizationRestApiKey );

    //New Device Information 
    $return = $api ->device -> setLanguage ('en')
    -> setIdentifier('12312312313')
    -> setDevice(Device :: WEB)
    -> addTag('enrolls' , '11')
    -> update($deviceID);


    return $return;
}



if (!function_exists('send_mailjet_email')) {
    function send_mailjet_email($subject, $message, $to, $name)
    {
        require 'vendor/autoload.php';
        // Use your saved credentials, specify that you are using Send API v3.1

        $mj = new \Mailjet\Client('59d422991973a13458e114d6980eb0c9','412c3f6358f03ec46cac3e76f9c42724',true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "info@friconn.com",
                        'Name' => "Friconn"
                    ],
                    'To' => [
                        [
                            'Email' => $to,
                            'Name' => $name
                        ]
                    ],
                    'Subject' => $subject,
                    'TextPart' => "$message",
                    'HTMLPart' => $message,
                    'CustomID' => "AppGettingStartedTest"
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}

if (!function_exists('generate_id')) {
    function generate_id($length = 6)
    {
        $charset = '123456789';
        $randStringLen = $length;
        $randString = "";
        for ($i = 0; $i < $randStringLen; $i++) {
            $randString .= $charset[mt_rand(0, strlen($charset) - 1)];
        }
        return $randString;
    }
} 
if (!function_exists('encrypt')) {
    function encrypt($string)
    {
        $secret_key = '4n9*^%%$3n^&4v&%7@!90&$$3c3x$^*$m8a456#@tgf%$$c';
        $secret_iv = 'cXpYEjhvzuVXOV7ltEQSAq8dvNQTWLar';
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        return $output;
    }
}

if (!function_exists('decrypt')) {
    function decrypt($string)
    {
        $secret_key = '4n9*^%%$3n^&4v&%7@!90&$$3c3x$^*$m8a456#@tgf%$$c';
        $secret_iv = 'cXpYEjhvzuVXOV7ltEQSAq8dvNQTWLar';
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);

        return $output;
    }
}


if (!function_exists('shaPassword')) {
    function shaPassword($field = "", $salt = "")
    {
        if ($field) {
            return hash('sha256', $field . $salt);
        }
    }
}

if (!function_exists('invite_url')) {
    function invite_url($id)
    {
        $url = "https://friconn.com/invite/" . $id;
        return $url;
    }
}


if (!function_exists('urlify')) {
    function urlify($id)
    {
        $token = $id.generate_id();
        $param = array(
            "url" => base_url("account/password/reset/" . $token),
            "token" => $token
        );

        return $param;
    }
}


if (!function_exists('create_url_slug')) {
    function create_url_slug($title)
    {
        $new_title = substr($title, 0, 70) . "-" . generate_id();
        return $new_title;
    }
}

if (!function_exists('plushrs')) {
    function plushrs($dt, $hrs)
    {
        $pure = strtotime($dt);
        $plus = ($pure + 60 * 60 * $hrs);
        $newPure = date('Y-m-d H:i:s', $plus);
        return $newPure;
    }
}

if (!function_exists('test_and_replace_foul_words')) {
    function test_and_replace_foul_words($text_content)
    {
        $formated_text = $text_content;
        $foul_words    = array('mad' => array('word' => "mad", "replacement" => "m*d"), 'shit' => array('word' => "shit", "replacement" => "sh*t"), 'crazy' => array('word' => "crazy", "replacement" => "cr*zy"), 'insane' => array('word' => "insane", "replacement" => "ins*ne"), 'fuck' => array('word' => "fuck", "replacement" => "f*ck"), 'nigga' => array('word' => "nigga", "replacement" => "n*gga"), 'bitch' => array('word' => "bitch", "replacement" => "b*tch"), 'stupid' => array('word' => "stupid", "replacement" => "st*pid"), 'fool' => array('word' => "fool", "replacement" => "f**l"), 'mad' => array('word' => "mad", "replacement" => "m*d"), 'bastard' => array('word' => "bastard", "replacement" => "b*stard"));

        foreach ($foul_words as $foul_word) {
            $formated_text = str_replace($foul_word['word'], $foul_word['replacement'], $formated_text);
        }
        return $formated_text;
    }
}

if (!function_exists('get_cloudinary_details')) {
    function get_cloudinary_details()
    {
    //return array("cloud_name" => "ismailobadimu", "api_key" => "341793831973628", "api_secret" => "3Ts_6QnJ9KyE1TsaHwf5W5gLjUc");
        return array("cloud_name" => "lewa", "api_key" => "266546296533642", "api_secret" => "M6yRhDSfn6Kh6pkgsRfHutuZHAw");
    }
}


if (!function_exists('get_response_status_code')) {
    function get_response_status_code()
    {
        return [
            'ok' => 200,
            'created' => 201,
            'badRequest' => 400,
            'unauthorized' => 401,
            'forbidden' => 403,
            'notFound' => 404,
            'methodNotAllowed' => 405,
            'conflict' => 409,
            'lengthRequired' => 411,
            'internalServerError' => 500,
            'serviceUnavailable' => 503
        ];
    }
}
if (!function_exists('send_HTML_email')) {
    function send_HTML_email($_this,$subject, $message, $to)
    {
        $mail_to_send_to = $to;
        $from_email = "info@friconn.com";
        $sendflag = 'send';    

        if ( $sendflag == "send" )
        {
            $subject= $subject;
            $email = "thenewxpat@gmail.com" ;

        // $headers = "From: $from_email" . "\r\n" . "Reply-To: $from_email"  ;

        // $headers  = 'MIME-Version: 1.0' . "\r\n";
        // $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

        // To send HTML mail, the Content-type header must be set
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        // Create email headers
            $headers .= 'From: '.$from_email."\r\n".
            'Reply-To: '.$from_email."\r\n" .


            $a = mail( $mail_to_send_to, $subject, $message, $headers );

        }   
    }
}



if (!function_exists('send_push_notification')) {
    function send_push_notification($title,$body,$click_action,$token)
    {
        $url = "https://fcm.googleapis.com/fcm/send";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
           "Accept: application/json",
           "Authorization: key=AAAAGsPc_Ng:APA91bEXPgTQlhI-BJznAS87wD4hUq30xRLu2AF7Z1ESnv0ij8lNiT9wNAKBjUyYMkyFWyhqIIUOU-4XI-_X9EY0bAHtJJySX_lqLT4FrHyj13cvDm1nHWdiwb-5pgSUJr-OSazCy1qD",
           "Content-Type: application/json",
       );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = <<<DATA
        {
            "notification": {
                "title": "$title",
                "body": "$body",
                "click_action": "$click_action",
                "icon": "https://res.cloudinary.com/friconn/image/upload/v1625936505/friconn-icon_nasobh.png"
            }
            "to": "$token"
        } 
        DATA;

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        return (json_decode($resp));

    }
}
