<?php
header("Content-Type: application/json; charset=UTF-8");
error_reporting(!E_ALL);
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

$i=0;
$rjson=[];
foreach($json['days1h'] as $key => $day) {
    if ($i==0) {
        $wochentag = "Heute";
    } else {
        $wochentag = date ('l', time() + $i*3600);
    }
    $j=0;
    $strahlung = array();
    foreach ($day['1h'] as $hour => $data) {
        if ($data['radwm2'] > 0 ) {
            $rjson[$wochentag]["stunde"][$hour] += $data['radwm2'];
        }
        $i++;
    }
    $rjson[$wochentag]["average"] = array_sum($rjson[$wochentag]["stunde"])/count($rjson[$wochentag]["stunde"]);
}
echo json_encode($rjson);
//echo "</tr></table>";

/**unset($json['day1h']['tair']);
unset($json['day1h']['tdew']);
unset($json['day1h']['relhum']);
unset($json['day1h']['radjcm2']);
unset($json['day1h']['precprob']);
unset($json['day1h']['prec']);
//unset($json['day1h']);

*/
//echo json_encode($json);
