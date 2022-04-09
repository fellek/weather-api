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
/**
echo "<table border='1' style='font-family: sans-serif;'><tr>";
$i=0;
foreach($json['days1h'] as $key => $day) {
    echo "<tr>";
    echo "<td>";
    if ($i==0) {
        echo "Heute";
    } else {
        echo date ('l', time() + $i*3600);
    }
    echo "</td>";
    foreach ($day['1h'] as $hour => $data) {
        echo "<td width='150px'>";
        echo $data['time'] . "<br>";
        echo "Temperatur&nbsp;(min/max):&nbsp;" . $data['tair'] . "&nbsp;gef&uuml;hlt&nbsp;wie" . $data['tdew'] . "&nbsp;°C" . "<br>";
        echo "Bew&ouml;lkung: " . $data['cloud'] . " h" . "<br>";
        echo "Strahlung:    " . $data['radwm2'] . " W/m²" . "<br>";
        echo "</td>";
        $i++;
    }
    echo "</tr>";
}
echo "</tr></table>";

unset($json['day1h']['tair']);
unset($json['day1h']['tdew']);
unset($json['day1h']['relhum']);
unset($json['day1h']['radjcm2']);
unset($json['day1h']['wsms']);
unset($json['day1h']['wskmh']);
unset($json['day1h']['wgustms']);
unset($json['day1h']['wgustkmh']);
unset($json['day1h']['wdir']);
unset($json['day1h']['wdir_labels']);
unset($json['day1h']['precprob']);
unset($json['day1h']['prec']);
//unset($json['day1h']);

*/
echo json_encode($json);
