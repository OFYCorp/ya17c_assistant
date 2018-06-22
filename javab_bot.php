<?php
$ACCESS_TOKEN = 's6B8WTWNkSI3IhbUSYVoNsHsazHZsh68GURPWHlBwAwcEr9w7Av21XJ43q8B3JGccXFxUfmK+IsDwiYm+tumI+ZLw5rFK8+bJBG9+4h0BylzHoIpV3eHttznIttxn9XrCYxk5tJhnpBcYqY6Gt68dgdB04t89/1O/w1cDnyilFU='; // Access Token ค่าที่เราสร้างขึ้น
$POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $ACCESS_TOKEN);

$API_REPLY_URL = 'https://api.line.me/v2/bot/message/reply';

$request = file_get_contents('php://input');   // Get request content
$request_array = json_decode($request, true);   // Decode JSON to Array

if (sizeof($request_array['events']) > 0) {

    foreach ($request_array['events'] as $event) {
        $API_PROFILE_URL = 'https://api.line.me/v2/bot/profile/' . $event['source']['userId'];
        $request_profile_data = request_profile($API_PROFILE_URL, $POST_HEADER);

        $reply_message = '';
        $reply_token = $event['replyToken'];

        if ($event['type'] == 'message') {
            if ($event['message']['type'] == 'text') {
                $text = $event['message']['text'];
//                $reply_message = 'ระบบได้รับข้อความ ('.$text.') ของคุณแล้ว';

                if ($text == 'รถมีทั้งหมดกี่รุ่น') {
                    $reply_message = 'คุณต้องการถามถึงรถรุ่น Yaris หรือ Yaris ATIV?';

                } else if ($text == 'Yaris') {
                    $reply_message = 'มีทั้งหมด 4 รุ่น ดังนี้ 1';

                } else if ($text == 'Yaris ATIV') {
                    $reply_message = 'มีทั้งหมด 5 รุ่น ดังนี้ xxxxxx';
                } else {
//                    $reply_message = 'User ID: ' . $event['source']['userId'] . ' type: ' . $event['source']['type'];
                    $reply_message = json_encode($event) . ' ';
                }


            } else {
                $reply_message = json_encode($event);

            }

        } else if ($event['type'] == 'join') {
            $reply_message = 'สวัสดีครับ! ผมคือผู้ช่วยของเพื่อนสมาชิก ฝากเนื้อฝากตัวด้วยนะครับ ^^';

        } else if ($event['type'] == 'leave') {
            $reply_message = 'ขอบคุณที่ให้ผมได้พบกับทุกท่าน ลาก่อนครับ';

        } else {
//            $reply_message = 'ระบบได้รับ Event '.ucfirst($event['type']).' ของคุณแล้ว';
            $reply_message = json_encode($event);
        }

        $reply_message = $request_profile_data;

        if (strlen($reply_message) > 0) {
            //$reply_message = iconv("tis-620","utf-8",$reply_message);
            $data = [
                'replyToken' => $reply_token,
//                'messages' => [['type' => 'text', 'text' => $reply_message]]
                'messages' =>
                    [
                        [
                            'type' => 'template',
                            'altText' => 'this is a buttons template',
                            'template' => [
                                'type' => 'buttons',
                                'actions' => [
                                    'type' => 'message',
                                    'label' => 'Action 1',
                                    'text' => 'Action 1',
                                ],
                                [
                                    'type' => 'message',
                                    'label' => 'Action 2',
                                    'text' => 'Action 2',
                                ],
                                'thumbnailImageUrl' => 'http://www.yarisativ2017.club/img/yarisativ-logo.jpg',
                                'title' => 'คุณรู้สึกอย่างไรกับคลับเรา',
                                'text' => 'ตอบแบบสอบถามเพื่อการพัฒนาที่ดียิ่งขึ้น',
                            ]
                        ]
                    ]
            ];
            $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);

            $send_result = send_reply_message($API_REPLY_URL, $POST_HEADER, $post_body);
            echo "Result: " . $send_result . "\r\n";
        }
    }
}

echo "OK";

function send_reply_message($url, $post_header, $post_body)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

function request_profile($url, $post_header)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

?>