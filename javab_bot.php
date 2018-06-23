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
                } else if ($text == 'รถมีทั้งหมดกี่รุ่น') {
                    $reply_message = 'คุณต้องการถามถึงรถรุ่น Yaris หรือ Yaris ATIV?';
                } else if ($text == 'Yaris') {
                    $reply_message = 'มีทั้งหมด 4 รุ่น ดังนี้                           - 2 J Eco CVT ราคา 489,000 บาท - 2 J CVT        ราคา 539,000 บาท   - 2 E CVT        ราคา 569,000 บาท   - 2 G CVT       ราคา 619,000 บาท';
                } else if ($text == 'Yaris ATIV') {
                    $reply_message = 'มีทั้งหมด 5 รุ่น ดังนี้                           - 1.2 J Eco CVT ราคา 479,000 บาท - 1.2 J CVT ราคา 529,000 บาท - 1.2 E CVT ราคา 559,000 บาท - 1.2 G CVT ราคา 609,000 บาท- 1.2 S CVT ราคา 635,000 บาท';
                } else if ($text == 'Yaris ATIV มีกี่สี') {
                    $reply_message = 'มีทั้งหมด 7 สี ดังนี้
                    - Dark Blue Mica Metallic
                    - Grey Metallic
                    - Silver Metallic
                    - Quartz Brown Metallic
                    - Super White
                    - Attitude Black Mica
                    - Red Mica Metallic';
                } else if ($text == 'Yaris มีกี่สี') {
                    $reply_message = 'มีทั้งหมด 7 สี ดังนี้
                    - Citrus Mica Metallic
                    - Orange Metallic
                    - Red Mica Metallic
                    - Super White II
                    - Silver Metallic
                    - Grey Metallic
                    - Attitude Black Mica';
                } else if ($text == 'เบอร์โทรฉุกเฉิน') {
                    $reply_message = '(66) 0-2386-2000 ตลอด 24 ชั่วโมง (หรือ 1800-238444 โทรฟรีสำหรับต่างจังหวัด เฉพาะโทรศัพท์พื้นฐาน ตลอด 24 ชั่วโมง)';
                }
//                } else {
//                    $reply_message = 'ขออภัยครับ ไม่พบเนื้อหาที่คุณต้องการ';
//                    $reply_message = 'User ID: ' . $event['source']['userId'] . ' type: ' . $event['source']['type'];
//                    $reply_message = json_encode($event) . ' ';
//            } else {
//                $reply_message = json_encode($event);
            }
        } else if ($event['type'] == 'join') {
            $reply_message = 'สวัสดีครับ! ผมคือผู้ช่วยของเพื่อนสมาชิก ฝากเนื้อฝากตัวด้วยนะครับ ^^';
        } else if ($event['type'] == 'leave') {
            $reply_message = 'ขอบคุณที่ให้ผมได้พบกับทุกท่าน ลาก่อนครับ';
//        } else {
//            $reply_message = 'ระบบได้รับ Event '.ucfirst($event['type']).' ของคุณแล้ว';
//            $reply_message = json_encode($event);
        }

//        $reply_message = $request_profile_data;

        if (strlen($reply_message) > 0) {
            $reply_message = iconv("tis-620", "utf-8", $reply_message);
            $data = [
                'replyToken' => $reply_token,
                // Text
                'messages' => [['type' => 'text', 'text' => $reply_message]],
//                 Multi-Text
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

//              ออกแบบไว้แล้ว แต่แก้ไม่ไหวแล้ว ไล่ไม่ถูก ไม่รู้ติดตรงไหน

//                    'messages' =>
//                        [[
//                            "type" => "template",
//                            "altText" => "this is a carousel template",
//                            "template" =>
//                                [
//                                    "type" => "carousel",
//                                    "actions" => [],
//                                    "columns" =>
//                                        [
//                                            [
//                                                "thumbnailImageUrl" => "https://www.toyota.co.th/media/files_usage/product/large/7aee2366ff550c5eada98722c8d3e65027644abbad370cec4f54ea63b805c1f8.png",
//                                                "title" => "1.2 S CVT",
//                                                "text" => "ราคา 635,000 บาท",
//                                                "actions" => [
//                                                    [
//                                                        "type" => "message",
//                                                        "label" => "รายละเอียด",
//                                                        "text" => "Action 1"
//                                                    ]
//                                                ]
//                                            ],
//                                            [
//                                                "thumbnailImageUrl" => "https://www.toyota.co.th/media/files_usage/product/large/34abf17927ce881b4c5a79a9bcff5d0bbf648345312c21bf0378bc5fcd2d0b63.png",
//                                                "title" => "1.2 G CVT",
//                                                "text" => "ราคา 609,000 บาท",
//                                                "actions" => [
//                                                    [
//                                                        "type" => "message",
//                                                        "label" => "รายละเอียด",
//                                                        "text" => "Action 1"
//                                                    ]
//                                                ]
//                                            ],
//                                            [
//                                                "thumbnailImageUrl" => "https://www.toyota.co.th/media/files_usage/product/large/9bbb65df329426be113b590c4a06c0df4adb8af43f4dd6d5a7fe87dd76bbb839.png",
//                                                "title" => "1.2 E CVT",
//                                                "text" => "ราคา 559,000 บาท",
//                                                "actions" => [
//                                                    [
//                                                        "type" => "message",
//                                                        "label" => "รายละเอียด",
//                                                        "text" => "Action 1"
//                                                    ]
//                                                ]
//                                            ],
//                                            [
//                                                "thumbnailImageUrl" => "https://www.toyota.co.th/media/files_usage/product/large/cac95fd8be30c963ae0f113265fd5d36f09c74ef9d9029481c8951724c7a2502.png",
//                                                "title" => "1.2 J CVT",
//                                                "text" => "ราคา 529,000 บาท",
//                                                "actions" => [
//                                                    [
//                                                        "type" => "message",
//                                                        "label" => "รายละเอียด",
//                                                        "text" => "Action 1"
//                                                    ]
//                                                ]
//                                            ],
//                                            [
//                                                "thumbnailImageUrl" => "https://www.toyota.co.th/media/files_usage/product/large/46f12669788d6f5cd804bde6aba0833608f91bfb1d8b2443275d94962d1d309a.png",
//                                                "title" => "1.2 J Eco CVT",
//                                                "text" => "ราคา 479,000 บาท",
//                                                "actions" => [
//                                                    [
//                                                        "type" => "message",
//                                                        "label" => "รายละเอียด",
//                                                        "text" => "Action 1"
//                                                    ]
//                                                ]
//                                            ]
//                                        ]
//                                ]
//                        ]]
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