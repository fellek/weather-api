<?php
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

//echo "<table border='1' style='font-family: sans-serif;'><tr>";
$i=0;
$strahlung = array();
foreach($json['days1h'] as $key => $day) {
  //  echo "<tr>";
  //  echo "<td>";
    if ($i==0) {
        $wochentag = "Heute";
    } else {
        $wochentag = date ('l', time() + $i*3600);
    }
 //   echo "</td>";
    $j=0;
    foreach ($day['1h'] as $hour => $data) {
        if ($data['radwm2'] > 0 ) {
            $strahlung[$i] += $data['radwm2'];
        }
   //     echo "<td width='150px'>";
   //     echo $data['time'] . "<br>";
   //     echo "Temperatur&nbsp;(min/max):&nbsp;" . $data['tair'] . "&nbsp;gef&uuml;hlt&nbsp;wie" . $data['tdew'] . "&nbsp;°C" . "<br>";
   //     echo "Bew&ouml;lkung: " . $data['cloud'] . " h" . "<br>";
   //     echo "Strahlung:    " . $data['radwm2'] . " W/m²" . "<br>";
   //     echo "</td>";
        $i++;
    }
    $json[$wochentag]["stunde"] = $strahlung;
    $json[$wochentag]["average"] = array_sum($strahlung)/count($strahlung);
    $j++;
   // echo "</tr>";
}
echo json_encode($json);
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
