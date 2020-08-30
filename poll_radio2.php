<?php
$resp = array();

//Fetch the JSON of NPO Radio 2
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://www.nporadio2.nl/?option=com_ajax&plugin=nowplaying&format=json&channel=nporadio2',
    CURLOPT_HTTPHEADER => array('Accept: application/json')
));
$resp = curl_exec($curl);

// Close request to clear up some resources
curl_close($curl);
$resp2 = json_decode($resp, true);
$artist = ($resp2['data'][0]['artist']);
$title = ($resp2['data'][0]['title']);

//Check the artist and create the message
switch ($artist) {
    case "Paul Simon":
        $message = "Hey!\r\n" . $artist . " is nu op NPO Radio 2 met " . $title;
        notify($message);
        break;
    case "Simon & Garfunkel":
        $message = "Hey!\r\n" . $artist . " is nu op NPO Radio 2 met " . $title . "!\r\n(0.5 puntje)";
        notify($message);
        break;
    case "Kenny Loggins":
        $message = "Cut looose! \r\n" . $artist . " is nu op NPO Radio 2 met " . $title;
        notify($message);
        break;
}

function notify($message)
{
    // The message formatting for email
    $message = wordwrap($message, 70, "\r\n");

    //IFTTT Notification
    $url = '#';
    $ch = curl_init($url);
    $xml = 'value1=' . $message;
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

    //Email notification
    $response = curl_exec($ch);
    curl_close($ch);
    mail("#", "Nu op NPO Radio 2", $message);
}
