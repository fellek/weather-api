<?php
$ort = @$_GET['o'];
$orte = [
    'goe' => ['51.54019','9.91399'],
    'gmp21' => ['52.45614','13.62766'],
    'hoffeld' => ['49.08933','11.62474'],
    'krachmacher' => ['49.0','12.0'],
    'rav' => ['47.7782704','9.6121303'],
    'pass' => ['48.5667364','13.4319466'],
    'wingert' => ['49.29292','8.52277']
];

if (array_key_exists($ort, $orte)) {
    $geo = $orte[$ort];
} else {
    $geo = ['49.29292','8.52277'];
}

echo "<table><tr>";
foreach ($orte as $key => $value) {
    echo "<td><a href='?o=$key'>$key</a></td>";
}
echo "</tr></table>";

$lat = $geo[0];
$lon = $geo[1];

$url = "https://www.agrar.basf.de/api/weather/weatherDetails?latitude=$lat&longitude=$lon&lang=de";
$stream = stream_context_create(array(
    "ssl"=>array(
        "verify_peer"=> false,
        "verify_peer_name"=> false, ),
    'http' => array(
        'timeout' => 30     ) )     );

$array = get_headers($url, false);
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

if (@$_GET['debug'] === "true") {
    print_r($json);
    die();
}
echo "<table border='1' style='font-family: sans-serif;'>
<tr><td colspan='24'>Wetterprognose f&uuml;r $ort</td></tr>
<tr>";
$i=0;
$progIt = 0;
foreach($json['days1h'] as $key => $day) {
    echo "<tr>";
    echo "<td>";
    if ($i==0) {
        echo "Heute";
    } else {
        echo date ('l', time() + $i*3600);
    }
    echo "</td>";
    $first = true;
    foreach ($day['1h'] as $hour => $data) {
        if ($first == true) {
            $time = explode(":",$data['time']);
            for ($j=1; $j<$time[0]; $j++) {
                echo "<td></td>";
            }
            $first = false;
        }
        if ($data['radwm2'] == 0) {
            $color ="#00C1FF";
        } elseif ($data['radwm2'] > 700) {
            $color ="#ff0000";
        } elseif ($data['radwm2'] > 500) {
            $color ="#ff8800";
        } elseif ($data['radwm2'] > 300) {
            $color ="#ecb94d";
        } elseif ($data['radwm2'] > 200) {
            $color ="#ecc808";
        } elseif ($data['radwm2'] > 100) {
            $color ="#BFFF6B";
        } elseif ($data['radwm2'] > 70) {
            $color ="#ffd00f";
        } else {
            $color ="#ffC1FF";
        }
        echo "<td width='150px' style='background-color: $color; opacity: ".(1.3-$data['cloud']/100).";'>";
        echo $data['time'] . "<br>";
        echo $data['tair'] . "°C&nbsp;(" . $data['tdew'] . "°C)" . "<br>";
        echo "Wolken&nbsp;".$data['cloud'] . "%" . "<br>";
        echo $data['precprob'] . "%&nbsp;/&nbsp;" .$data['prec']. "mm<br>";
        echo $data['radwm2'] . "&nbsp;W/m²" . "<br>";
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


//print_r($json);
?>
<a href="https://fellek.net/impressum">Impressum</a>
