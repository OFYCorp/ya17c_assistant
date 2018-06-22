<?php
$ACCESS_TOKEN = 's6B8WTWNkSI3IhbUSYVoNsHsazHZsh68GURPWHlBwAwcEr9w7Av21XJ43q8B3JGccXFxUfmK+IsDwiYm+tumI+ZLw5rFK8+bJBG9+4h0BylzHoIpV3eHttznIttxn9XrCYxk5tJhnpBcYqY6Gt68dgdB04t89/1O/w1cDnyilFU='; // Access Token ค่าที่เราสร้างขึ้น
$POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $ACCESS_TOKEN);

// API URL for reply message to user.
$API_REPLY_URL = 'https://api.line.me/v2/bot/message/reply';

$request = file_get_contents('php://input');   // Get request content
$request_array = json_decode($request, true);   // Decode JSON to Array

if (sizeof($request_array['events']) > 0) {

    foreach ($request_array['events'] as $event) {

        // API URL for get user profile.
        $API_PROFILE_URL = 'https://api.line.me/v2/bot/profile/' . $event['source']['userId'];

        // Get user profile data from LINE.
        $request_profile_data = request_profile($API_PROFILE_URL, $POST_HEADER);

        // Reply message conditions
        $reply_message = '';
        $reply_token = $event['replyToken'];

        if ($event['type'] == 'message') {
            if ($event['message']['type'] == 'text') {
                $text = $event['message']['text'];
//                $reply_message = 'ระบบได้รับข้อความ ('.$text.') ของคุณแล้ว';

                if ($text == 'สวัสดี') {
                    $reply_message = 'ต้องการสอบถามข้อมูลด้านไหนครับ..?';
                }
                if ($text == 'รถมีทั้งหมดกี่รุ่น') {
                    $reply_message = 'คุณต้องการถามถึงรถรุ่น Yaris หรือ Yaris ATIV?';

                } else if ($text == 'Yaris') {
                    $reply_message = 'มีทั้งหมด 4 รุ่น ดังนี้                           - 2 J Eco CVT ราคา 489,000 บาท - 2 J CVT        ราคา 539,000 บาท   - 2 E CVT        ราคา 569,000 บาท   - 2 G CVT       ราคา 619,000 บาท';

                }
//                else if ($text == 'Yaris ATIV') {
//                    $reply_message = 'มีทั้งหมด 5 รุ่น ดังนี้                           - 1.2 J Eco CVT ราคา 479,000 บาท - 1.2 J CVT ราคา 529,000 บาท - 1.2 E CVT ราคา 559,000 บาท - 1.2 G CVT ราคา 609,000 บาท- 1.2 S CVT ราคา 635,000 บาท';
//                }
                if ($text == 'ัYaris ATIV') {
                    $reply_message = 'มีทั้งหมด 7 สี ดังนี้- Dark Blue Mica Metallic - Grey Metallic- Silver Metallic- Quartz Brown Metallic- Super White - Attitude Black Mica- Red Mica Metallic';
                }
                if ($text == 'Yaris มีกี่สี') {
                    $reply_message = 'มีทั้งหมด 7 สี ดังนี้
                    - Citrus Mica Metallic
                    - Orange Metallic
                    - Red Mica Metallic
                    - Super White II
                    - Silver Metallic
                    - Grey Metallic
                    - Attitude Black Mica';
                }
//                } else {
//                    $reply_message = 'ขออภัยครับ ไม่พบเนื้อหาที่คุณต้องการ';
//                    $reply_message = 'User ID: ' . $event['source']['userId'] . ' type: ' . $event['source']['type'];
//                    $reply_message = json_encode($event) . ' ';


//            } else {
//                $reply_message = json_encode($event);

            }

        } else if ($event['type'] == 'join') {
            $reply_message = 'สวัสดีครับ! ผมคือผู้ช่วยของเพื่อนสมาชิก ฝากเนื้อฝากตัวด้วยนะครับ ^^ ';

        } else if ($event['type'] == 'leave') {
            $reply_message = 'ขอบคุณที่ให้ผมได้พบกับทุกท่าน ลาก่อนครับ';

//        } else {
//            $reply_message = 'ระบบได้รับ Event '.ucfirst($event['type']).' ของคุณแล้ว';
//            $reply_message = json_encode($event);
        }

//        $reply_message = $request_profile_data;

        if (strlen($reply_message) > 0) {
            //$reply_message = iconv("tis-620","utf-8",$reply_message);
            $data = [
                'replyToken' => $reply_token,
                // Text
                'messages' => [['type' => 'text', 'text' => $reply_message]]

            // Multi-Text
//                'messages' => [
//                    ['type' => 'text', 'text' => $reply_message],
//                    ['type' => 'text', 'text' => 'ทดสอบ'],
//                ],

                // Image
//                'messages' => [[
//                    'type' => 'image',
//                    'originalContentUrl' => 'https://i2.wp.com/beebom.com/wp-content/uploads/2016/01/Reverse-Image-Search-Engines-Apps-And-Its-Uses-2016.jpg?resize=640%2C426',
//                    'previewImageUrl' => 'https://i2.wp.com/beebom.com/wp-content/uploads/2016/01/Reverse-Image-Search-Engines-Apps-And-Its-Uses-2016.jpg?resize=640%2C426',
//                    'animated' => false]]

//                 Sticker
//                'messages' => [[
//                    'type' => 'sticker',
//                    'packageId' => '4',
//                    'stickerId' => '623']]

//                 Location
//                'messages' => [[
//                    'type' => 'location',
//                    'title' => 'ศูนย์บริการโตโยต้าบัสส์',
//                    'address' => '69/40 ถนนบางยี่ขัน แขวง/เขตบางกอกใหญ่ กรุงเทพ',
//                    'latitude' => '13.840058',
//                    'longitude' => '100.580857',
//                ]]

                // Template
//                'messages' =>
//                    [[
//                        'type' => 'template',
//                        'altText' => 'this is a buttons template',
//                        'template' =>
//                            [
//                                'type' => 'buttons',
//                                'actions' =>
//                                    [
//                                        [
//                                            'type' => 'message',
//                                            'label' => 'Action 1',
//                                            'text' => 'Action 1',
//                                        ],
//                                        [
//                                            'type' => 'message',
//                                            'label' => 'Action 2',
//                                            'text' => 'Action 2',
//                                        ],
//                                    ],
//                                'thumbnailImageUrl' => 'https://i2.wp.com/beebom.com/wp-content/uploads/2016/01/Reverse-Image-Search-Engines-Apps-And-Its-Uses-2016.jpg?resize=640%2C426',
//                                'title' => 'คุณรู้สึกอย่างไรกับคลับเรา',
//                                'text' => 'ตอบแบบสอบถามเพื่อการพัฒนาที่ดียิ่งขึ้น',
//                            ]
//                    ]]


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