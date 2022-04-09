<?php
$url = 'https://www.agrar.basf.de/api/weather/weatherDetails?latitude=49.29292&longitude=8.52277&lang=de';
$stream = stream_context_create(array(
    "ssl"=>array(
        "verify_peer"=> false,
        "verify_peer_name"=> false, ),
    'http' => array(
        'timeout' => 30     ) )     );

$array = get_headers($url, 0, $stream);
$string = $array[0];
if(strpos($string,"200"))
{
    //echo 'url exists  '.$url."\n<br>";
}
else
{
    echo 'url:  '.$url." does not exist \n<br>";
    return;
}
/* Ende - prüfen ob Seite existiert */
$file = file_get_contents($url);
$json = json_decode($file, TRUE);

SetValueFloat(20502,(time_convert($json['days'][0]['sdur'])));                  // Sonnenstunden heute
SetValueFloat(30733,(time_convert($json['days'][1]['sdur'])));                  // Sonnenstunden morgen
SetValueFloat(52325,(time_convert($json['days'][2]['sdur'])));                  // Sonnenstunden übermorgen
SetValueFloat(43074,(time_convert($json['days'][3]['sdur'])));                  // Sonnenstunden überübermorgen


// --- Funktionen ---

function time_convert($Zeit) {
    $d = explode(':', $Zeit);
    $d2 = 100 / 60 * $d[1];
    $d2 = round(($d[0].'.'.$d2), 1);
    return $d2;
}
