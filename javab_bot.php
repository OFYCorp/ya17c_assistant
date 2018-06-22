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
            $reply_message = 'สวัสดีครับ! ผมคือผู้ช่วยของเพื่อนสมาชิก ฝากเนื้อฝากตัวด้วยนะครับ ^^ ';

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
                // Text
//                'messages' => [['type' => 'text', 'text' => $reply_message]]

                // Image
                'messages' => [[
                    'type' => 'image',
                    'originalContentUrl' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQAAAAEACAYAAABccqhmAAAAAXNSR0IArs4c6QAAEDRJREFUeAHtneFuIzcMBs9F3v+V0+iHgcLQ0CHL27WkOaBIQ5H8yGGOWGjhy+PPnz/fP/8t8ef7e17q4/FI15/NRf5p4YsCVmNC9RJ38q/gJY1Krs66KvrZmH+yAfpLQAL7EHAB7DNLO5FAmoALII3MAAnsQ8AFsM8s7UQCaQIugDQyAySwD4EvaqXzZpQ0yJ69SY1qpVxk76pp5KG6SJv8R65KzIh7/XOFBtX6Wsv/+T7qg/Jm64r8s/pZf+qhYo/68AmgQtQYCWxCwAWwySBtQwIVAi6ACjVjJLAJARfAJoO0DQlUCLgAKtSMkcAmBPAtAPUX3ShSDNm7bkajmkiDYsifehh2ykX2KNedZ9T7FX10alMust/JvJNtpT+fAO6cvtoSuJmAC+DmASgvgTsJuADupK+2BG4m4AK4eQDKS+BOAi6AO+mrLYGbCaTfAtxc71Q+uv2kW9YoZiZCeYYv5Ypishoz/2HLalCeKFe2P/KPtOmMclX6zsaQNtW6ot0ngBWnZs0SaCLgAmgCaRoJrEjABbDi1KxZAk0EXABNIE0jgRUJuABWnJo1S6CJwBZvAbK3u4MdxVxx80saVNOol2LG2exPlGvmP2ykQbnIn/IPe2euSGd2RvVSTbMcu9l8AthtovYjgQQBF0AClq4S2I2AC2C3idqPBBIEXAAJWLpKYDcCLoDdJmo/EkgQcAEkYOkqgd0IjN+rPf2d2/TK5AoA9Fqms6ZODcp1BSti8ok1dfKo9EesqK5Ig3JRDPmTdqedahoaPgF0kjaXBBYj4AJYbGCWK4FOAi6ATprmksBiBFwAiw3MciXQScAF0EnTXBJYjAB+GCi6Ofy0HqNau25fKxoUQzWR/+Cdjcn6r6YR/Qxmeyf/SCN7Fs02m6vT3yeATprmksBiBFwAiw3MciXQScAF0EnTXBJYjIALYLGBWa4EOgm4ADppmksCixF4/NyATj8LsFIflRtWaptykX8nJ9IeGqQfxcxqozwz36ctq/GMy3zN1hXVlM2VqXM3X58Adpuo/UggQcAFkIClqwR2I+AC2G2i9iOBBAEXQAKWrhLYjYALYLeJ2o8EEgTwswCJHGXX6CZ3lpRud8k+y/G0ZbWfcR1fd9G+gnuFFcVk66U842eAclFM1v8qDZ8AOv5Gm0MCixJwASw6OMuWQAcBF0AHRXNIYFECLoBFB2fZEugg4ALooGgOCSxKwAWw6OAsWwIdBPDDQF2vM+j1R0fxzxxU6zgn/Sjmmfe/XynPf31e/580Krlec1/5PfVBNXT2R9q7aBDDYe/skXR8AiAy2iVwAAEXwAFDtkUJEAEXAJHRLoEDCLgADhiyLUqACLgAiIx2CRxAAD8MRDeQ2VtZ8h9ssxo0D8oz/EmfYsif7FTTinbqMcuq0jtpU66s/8hDfZC9U4P6IG3yj+xUb6ThE0BE1DMJbE7ABbD5gG1PAhEBF0BExzMJbE7ABbD5gG1PAhEBF0BExzMJbE7gr38WIOIX3U7O4uiWc+b7zpbVjvJ11dVZU1TvJ54RwyuYkHYnJ+qjot2ZyyeAzimbSwKLEXABLDYwy5VAJwEXQCdNc0lgMQIugMUGZrkS6CTgAuikaS4JLEYA3wJQH3RrWbmZrMRQXZ9o7+yvM9ffZkW1VnSzP2+RBuWKYrrOOplQTZX+fAIgmtolcAABF8ABQ7ZFCRABFwCR0S6BAwi4AA4Ysi1KgAi4AIiMdgkcQMAFcMCQbVECRODxc/BNh3fZ6ZUJveYg/1E/xVBvUS6KIQ3KlfWv9EG1RnaqN4rJnlV6z2qQf1ab/Ef+LKsoF9Wb1aA8kbZPAERNuwQOIOACOGDItigBIuACIDLaJXAAARfAAUO2RQkQARcAkdEugQMI4IeB6Oaw62YyYkvaUUz2rLOPbL0VbdKgXFn/iB/lohiqafhnc5FGZI/0Z3FUU5SHYmb5r7JF9VINPgEQGe0SOICAC+CAIduiBIiAC4DIaJfAAQRcAAcM2RYlQARcAERGuwQOIICfBcjeKNKtaJSnEpOdyRUa2ZrIn2ol/8gecac40q/kIo0uO9U68mfrpVzZPF29PfNQXc/z336N+vAJ4LcU9ZPAhgRcABsO1ZYk8FsCLoDfktJPAhsScAFsOFRbksBvCbgAfktKPwlsSAA/C0C9Zm8moxvIbC6q6Qp71AfpU39356J6r7BnmZB/pVbiThrkP7SzMVn/qL9sLvIfGj4BRKQ9k8DmBFwAmw/Y9iQQEXABRHQ8k8DmBFwAmw/Y9iQQEXABRHQ8k8DmBFwAmw/Y9iQQEfiiw+jVAcXM7JU80euXmUbFRnWRNvkPbYohO+Ui/6g/yhXF0Bnpkwb5U/6d7NR7lhX5D1akQfYoF7H3CYDIaJfAAQRcAAcM2RYlQARcAERGuwQOIOACOGDItigBIuACIDLaJXAAgS+6OaSbRmJSyUMxZM/WRLUOO+Xq1KZcUV3Zs2wf2fwV/0rflRiqjZiQP9mjmrIaUS7Sz9qppkjbJ4AsZf0lsBEBF8BGw7QVCWQJuACyxPSXwEYEXAAbDdNWJJAl4ALIEtNfAhsR+KKbQ+oxulGkmKw9W1M2//DP9hH5X1EvaUR1zbhQnpnv00YxWe2Rj3I9tV6/VjQqMa+63d9T31GtdJbNRf6jR58AuidtPgksRMAFsNCwLFUC3QRcAN1EzSeBhQi4ABYalqVKoJuAC6CbqPkksBAB/BeBunqgm8yRn24no5hsXaRBebL+lCeyVzSISTYX5anUS9oVDYqpaFAM9Uja5D/sFEPaWf+KNsWQ9vD3CYCoaZfAAQRcAAcM2RYlQARcAERGuwQOIOACOGDItigBIuACIDLaJXAAARfAAUO2RQkQAXwNSK8Osq85SHjYsxpRLjrLapA/5Y/snawoF+lTH1EeislqkH/FTjVFfZAO5SL/TjvVW6mJclXq9QmgQs0YCWxCwAWwySBtQwIVAi6ACjVjJLAJARfAJoO0DQlUCLgAKtSMkcAmBPAtAPVHt5aVm0nKRfaKBvWRtXdqUy7qe9RKZ5Qr29/wp1yfqB31d0W9pEF2Yhv1QWedGj4BEGXtEjiAgAvggCHbogSIgAuAyGiXwAEEXAAHDNkWJUAEXABERrsEDiDw+Onxe9Zn9tay82ZyVs9Vtiv6II2ox+w8olzZs0q9pNHVR1RTlwb1MOyR/izuzpoibZ8AZtPSJoFDCLgADhm0bUpgRsAFMKOiTQKHEHABHDJo25TAjIALYEZFmwQOIfDXfz149ra0wj265czmo1ydfXRqUF2kEfHoykV5rtCuaFBMxJDOqHeyUx6qqWIn7ZHLJ4AKUWMksAkBF8Amg7QNCVQIuAAq1IyRwCYEXACbDNI2JFAh4AKoUDNGApsQcAFsMkjbkECFwOPnNcT0w0D06gDcUZvyYMDPwZ0alXqjXrrOiMmd9X5iTV283+W5s/dObZ8A3k3acwlsTMAFsPFwbU0C7wi4AN4R8lwCGxNwAWw8XFuTwDsCLoB3hDyXwMYEvrpukSt5Om8zaUakkfXv7I+0I43obJYv2/csxzsb1RRpU8w7rY7zqK5Z/jtrHfVk65318C6PTwBETbsEDiDgAjhgyLYoASLgAiAy2iVwAAEXwAFDtkUJEAEXAJHRLoEDCOCvB8/eQJJ/dJNKZ9lc5D/m16UR/SxE+rO4bE1RH7P8kY20o5hsf1Gu7BlpR31UYmZ1UZ6Z79NGMVG9z9jXr5WY1xzvvvcJ4B0hzyWwMQEXwMbDtTUJvCPgAnhHyHMJbEzABbDxcG1NAu8IuADeEfJcAhsTwF8MQjeQ2VtO8h9MSYPsV8yB6q3UVImhHqku8u/UJo077REP6p1iyJ/so+9sLvKPGEb6szjSiPL4BDAjqU0ChxBwARwyaNuUwIyAC2BGRZsEDiHgAjhk0LYpgRkBF8CMijYJHELABXDIoG1TAjMC+E+C0SuFWZLIFr2CoDjSviIXaVOtV9krvf/t2ohVVCvFZGutaFBMV02jB8pF2lHflCuKyZ75BJAlpr8ENiLgAthomLYigSwBF0CWmP4S2IiAC2CjYdqKBLIEXABZYvpLYCMCj59epr8efKMeU61Ubl7phpdyZf2jBq7IFenPzqjvmW/VRn2PfFl9ypXNE/VCGlEM6VdykY5PAERGuwQOIOACOGDItigBIuACIDLaJXAAARfAAUO2RQkQARcAkdEugQMI4FuAK24giS9pk39kz96YknaUpxIT1bzKWWffd+Yi7WgO0c9DFNdxlq03qtUngI6JmEMCixJwASw6OMuWQAcBF0AHRXNIYFECLoBFB2fZEugg4ALooGgOCSxKoO0tAN1MRjeQWWYVDYrJalf8qXeqifyHdjaG/Dv7oFyd2qQR2Yljti7KE2l/4lnUt08Anzgxa5LARQRcABeBVkYCn0jABfCJU7EmCVxEwAVwEWhlJPCJBFwAnzgVa5LARQRcABeBVkYCn0jg8fOKYPl/Eqzyuoba7sxFA69oUK6snfoeeaguiiH/qCbKRTGkkc3T3V9Ff9Yj9Td8uzRmuk+bTwBPEn6VwIEEXAAHDt2WJfAk4AJ4kvCrBA4k4AI4cOi2LIEnARfAk4RfJXAgAfz14J/Igm5FyR71EN2+zuI6NShXtqZZne9skQbV9S7n63mUJ9J/zRN9H+WJ9Gc5o1wz/8hGubI1RRqdZz4BdNI0lwQWI+ACWGxgliuBTgIugE6a5pLAYgRcAIsNzHIl0EnABdBJ01wSWIzAF9V7560l3aRSrVl/yjPs1HekQTFkj3JFtc3OSGPm+85GdWU1KM/Qp1wUk/WPeuzMRTqkQf6RPcuEclGe4e8TAFHTLoEDCLgADhiyLUqACLgAiIx2CRxAwAVwwJBtUQJEwAVAZLRL4AAC+BaAeo9uFCmG7F03pl15qM6qvZNVtYbXuKimLEfyjzSis9dax/dZ/2rMTDuyVeqK8nWcVWryCaCDvDkksCgBF8Cig7NsCXQQcAF0UDSHBBYl4AJYdHCWLYEOAi6ADormkMCiBFwAiw7OsiXQQSD9GrBD9JNz0KsUeuUV9UIxnRqUK6qLzrK5qD/KP+zZmGxNFW3SyNYaaVc0KCbSyZ75BJAlpr8ENiLgAthomLYigSwBF0CWmP4S2IiAC2CjYdqKBLIEXABZYvpLYCMCx74FuOKGlTTodpn8x88bxWR/FqM8kf5MJ+s/clBMVNdMu9NG2lRrp3ak0VUX5Rl9+ATQOU1zSWAxAi6AxQZmuRLoJOAC6KRpLgksRsAFsNjALFcCnQRcAJ00zSWBxQik3wJEN4qf2Dvdsnb2QRpX8Mj2EdVKuSiG/KO+KVcUkz2jurLalCeqhzQoF/lHGnRW0fAJgGhql8ABBFwABwzZFiVABFwAREa7BA4g4AI4YMi2KAEi4AIgMtolcAABfAvQeTt5J0e6Gc3W1MmDcnXVOnojjWzfFf87taPe7+RbYVKJmc0r6tsngBkxbRI4hIAL4JBB26YEZgRcADMq2iRwCAEXwCGDtk0JzAi4AGZUtEngEAIugEMGbZsSmBH4F1oZXJqjQT2WAAAAAElFTkSuQmCC',
                    'previewImageUrl' => 'data:image/png;base64,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',
                    'animated' => false]]

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