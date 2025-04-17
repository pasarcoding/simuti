<?php
function send_wa($link_send, $api_key, $sender, $number, $message)
{
    $data = [
        "api_key" => $api_key,
        "sender" => $sender,
        "number" => $number,
        "message" => $message
    ];

    $data_string = json_encode($data);

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $link_send,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $data_string,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_VERBOSE => 0,
        CURLOPT_CONNECTTIMEOUT => 0,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
}



//webHook Set
class FormatMessage
{
    public static function text($text, $quoted = false)
    {
        return json_encode(['text' => $text, 'quoted' => $quoted]);
    }

    public static function exampleMedia($quoted = false)
    {
        // image
        // ['image' => ['url' => 'url_image'], 'caption' => 'text', 'quoted' => true or false]
        // video
        // ['video' => ['url' => 'url_video'], 'caption' => 'text', 'quoted' => true or false]
        // audio
        // ['audio' => ['url' => 'url_audio'], 'ptt' => true or false, 'quoted' => true or false, 'fileName' => 'filename.mp3', 'mimetype' => 'audio/mpeg']
        // pdf
        // ['document' => ['url' => 'url_pdf'], 'quoted' => true or false, 'fileName' => 'filename.pdf', 'mimetype' => 'application/pdf']
        return json_encode([
            'image' => [
                'url' =>
                'https://png.pngtree.com/element_our/md/20180626/md_5b321c99945a2.jpg',
            ],
            'caption' => 'caption',
            'quoted' => $quoted,
        ]);
    }

    public static function exampleButton($quoted = false)
    {
        // button
        $buttons = [
            [
                'buttonId' => 'id1',
                'buttonText' => ['displayText' => 'Button 1'],
                'type' => 1,
            ],
            [
                'buttonId' => 'id2',
                'buttonText' => ['displayText' => 'Button 2'],
                'type' => 1,
            ],
            [
                'buttonId' => 'id3',
                'buttonText' => ['displayText' => 'Button 3'],
                'type' => 1,
            ],
        ];
        $message = [
            'text' => 'text',
            'footer' => 'footer',
            'headerType' => 1,
            'buttons' => $buttons,
            'quoted' => $quoted,
        ];

        // if wnat to add image you can add like this to $message
        // $message['image'] = ['url' => 'url_image']; and change text to caption

        return json_encode($message);
    }

    public static function exampleTemplate($quoted = false)
    {
        $templateButtons = [
            [
                'index' => 1,
                'urlButton' => [
                    'displayText' => 'Visit our website',
                    'url' => 'https://www.example.com',
                ],
            ],
            [
                'index' => 2,
                'callButton' => [
                    'displayText' => 'Call us now',
                    'phoneNumber' => '+1234567890',
                ],
            ],
        ];

        $message = [
            'text' => 'text',
            'footer' => 'footer', // optional
            'templateButtons' => $templateButtons,
            'viewOnce' => true,
            'quoted' => $quoted, // optional
        ];

        // if wnat to add image you can add like this to $message
        // $message['image'] = ['url' => 'url_image']; and change text to caption
        return json_encode($message);
    }

    public static function exampleList($quoted = false)
    {
        $section = [
            'title' => 'Menu List',
            'rows' => [
                [
                    'title' => 'List Item 1',
                    'rowId' => 'id2',
                    'description' => '',
                ],
                [
                    'title' => 'List Item 2',
                    'rowId' => 'id3',
                    'description' => '',
                ],
            ],
        ];

        $section2 = [
            'title' => 'Menu List 2',
            'rows' => [
                [
                    'title' => 'List Item 1',
                    'rowId' => 'id2',
                    'description' => '',
                ],
                [
                    'title' => 'List Item 2',
                    'rowId' => 'id3',
                    'description' => '',
                ],
            ],
        ];

        $listMessage = [
            'text' => 'text',
            'footer' => 'footer',
            'title' => 'name of list',
            'buttonText' => 'button of list',
            'sections' => [$section, $section2],
        ];

        return json_encode($listMessage);
    }
}
