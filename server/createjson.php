<?php
include_once(__DIR__.DIRECTORY_SEPARATOR."config.php");

$link = mysqli_connect($host, $dbUser, $password, $db);
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

// Detect timezone
if ($result = mysqli_query($link, "SHOW GLOBAL VARIABLES LIKE 'time_zone'")) {
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $timezone = $row['Value'];
    }
    mysqli_free_result($result);
    if ($timezone == 'SYSTEM') {
        exec("cat /etc/timezone", $tzdata);
        $timezone = $tzdata[0];
    }
}

// Query the database
if ($result = mysqli_query($link, "
SELECT 
    IF(SUBSTR(INET6_NTOA(`ip`),1,7) = '::ffff:', SUBSTR(INET6_NTOA(`ip`) FROM 8), INET6_NTOA(`ip`)) as `ip`,
    ROUND( `active` / FLOOR((TIMESTAMPDIFF(MINUTE, `first_online`, NOW()) + 10) / 10 ) * 100 ,2) as `percentage`,
    IF(TIMESTAMPDIFF(MINUTE, `last_online`, NOW()) <= 10, 'now',  DATE_FORMAT(CONVERT_TZ(`last_online`, '{$timezone}', 'UTC'), '%Y-%m-%d %H:%i:%s') ) as `lastOnline`,
    DATE_FORMAT(CONVERT_TZ(`first_online`, '{$timezone}', 'UTC'), '%Y-%m-%d %H:%i:%s') as `firstOnline`,
    `id`,
    IF(`last_checked` < DATE_SUB(now(),INTERVAL 1 DAY), 'yes', 'no') as `to_check`,
    `website_ip` AS `wi`, `website_hostname` AS `wh`, `website_hostname_https` AS `whs`
FROM `active_nodes`  
WHERE `first_online` < DATE_SUB(now(),INTERVAL 7 DAY) 
AND ROUND( `active` / FLOOR((TIMESTAMPDIFF(MINUTE, `first_online`, NOW()) + 10) / 10 ) * 100 ,2) > 90
ORDER BY `active_nodes`.`active`  DESC
")) {
    $i = 0;
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $i++;
        $ips[$row['ip']] = $row;
        $ips[$row['ip']]['rank'] = $i;
    }
    mysqli_free_result($result);
}

// Load GeoIP databases
require_once __DIR__.DIRECTORY_SEPARATOR.'vendor/autoload.php';
use GeoIp2\Database\Reader;
$list_dirs = read_all_files(__DIR__.DIRECTORY_SEPARATOR.'db');
foreach ($list_dirs['dirs'] as $list_dir) {
    if (stristr($list_dir, "GeoLite2-ASN")) {
        $asn_dir = $list_dir;
    } elseif (stristr($list_dir, "GeoLite2-City")) {
        $city_dir = $list_dir;
    }
}

$reader = new Reader($city_dir.'GeoLite2-City.mmdb');
$reader_asn = new Reader($asn_dir.'GeoLite2-ASN.mmdb');

$curlOptions = array(
    CURLOPT_HEADER => false,
    CURLOPT_CONNECTTIMEOUT => 2,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_NOBODY => true,
);
$checked = 0;
foreach ($ips as $k => $v) {
    try{
        $record_asn = $reader_asn->asn($k); // Detect service provider
    } catch(Exception $e){
        echo $e->getMessage()."\n";
    }
    try{
        $record = $reader->city($k); // Detect coordinates, city, country etc.
    } catch(Exception $e){
        echo $e->getMessage()."\n";
    }

    // Detect host
    $host = gethostbyaddr($k);
    // Detect website
    if ($v['to_check'] == 'yes' and $checked < 50) {
        $checked++;
        $websiteIp = 'No';
        $websiteHostname = 'No';
        $websiteHostnameHttps = 'No';
        if (filter_var($k, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $urlIp = "[".$k."]";
        } else {
            $urlIp = $k;
        }
        $ch = curl_init();
        $curlOptions[CURLOPT_URL] = 'http://'.$urlIp;
        curl_setopt_array($ch, $curlOptions);
        curl_exec($ch);
        if (!curl_errno($ch)) {
            $websiteIp = 'Yes';
        }
        if ($k != $host) {
            $curlOptions[CURLOPT_URL] = 'https://'.$host;
            curl_setopt_array($ch, $curlOptions);
            curl_exec($ch);
            if (!curl_errno($ch)) {
                $websiteHostnameHttps = 'Yes';
                $websiteHostname = 'Yes';
            } else {
                $curlOptions[CURLOPT_URL] = 'http://'.$host;
                curl_setopt_array($ch, $curlOptions);
                curl_exec($ch);
                if (!curl_errno($ch)) {
                    $websiteHostname = 'Yes';
                }
            }
        } else {
            $websiteHostname = $websiteIp;
        }
        curl_close($ch);
        $query = "UPDATE `active_nodes` SET `website_ip` = '$websiteIp', `website_hostname` = '{$websiteHostname}', `website_hostname_https` = '{$websiteHostnameHttps}', `last_checked` = NOW()  WHERE `id` = {$v['id']}";
        $result = mysqli_query($link, $query);
    }

    unset($v['id'], $v['to_check']);
    $geodata[$record->continent->name][$record->country->name][$record->mostSpecificSubdivision->name][$record->city->name][] = array_merge($v, array(
        'lat' => $record->location->latitude,
        'long' => $record->location->longitude,
        'isp' => $record_asn->autonomousSystemOrganization,
        'host' => $host
    ) );
}

$types = array('90%','99%','Total peers','Unique IPs in last 10 days');
$statsData = array();
foreach ($types as $key => $val) {
    if ($result = mysqli_query($link, "SELECT ROUND(SUM(`amount`) / COUNT(*)) as `count` FROM `active_nodes_stats_temp` WHERE DATE(`date`) = DATE(NOW()) AND `type` = '{$val}'")) {
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $statsData[$val] = $row['count'];
        }
        mysqli_free_result($result);
    }
}

// Write data json
$response = array();
$response['data'] = $geodata;
$response['stats'] = $statsData;
$fp = fopen($webServerRootFolder.DIRECTORY_SEPARATOR.'data.json', 'w');
fwrite($fp, json_encode($response));
fclose($fp);

// Write stats json
$response = array();
if (!file_exists($webServerRootFolder.DIRECTORY_SEPARATOR.'stats.json') or date("G") == "1") {
    if ($result = mysqli_query($link, "SELECT * FROM `active_nodes_stats` ORDER BY `date`")) {
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $stats[$row['type']][$row['date']] = $row['amount'];
        }
        mysqli_free_result($result);
    }

    $response['data'] = $stats;
    $fp = fopen($webServerRootFolder.DIRECTORY_SEPARATOR.'stats.json', 'w');
    fwrite($fp, json_encode($response));
    fclose($fp);
}


function read_all_files($root = '.'){
    $files  = array('files'=>array(), 'dirs'=>array());
    $directories  = array();
    $last_letter  = $root[strlen($root)-1];
    $root  = ($last_letter == '\\' || $last_letter == '/') ? $root : $root.DIRECTORY_SEPARATOR;

    $directories[]  = $root;

    while (sizeof($directories)) {
        $dir  = array_pop($directories);
        if ($handle = opendir($dir)) {
            while (false !== ($file = readdir($handle))) {
                if ($file == '.' || $file == '..') {
                    continue;
                }
                $file  = $dir.$file;
                if (is_dir($file) and stristr($file, "geo")) {
                    $directory_path = $file.DIRECTORY_SEPARATOR;
                    array_push($directories, $directory_path);
                    $files['dirs'][]  = $directory_path;
                }
            }
            closedir($handle);
        }
    }

    return $files;
}
