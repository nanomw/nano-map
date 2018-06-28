<?php
exec("curl -g -d '{ \"action\": \"peers\" }' '[::1]:7076'", $output);

foreach ($output as $k => $v) {
    preg_match( '/^[\s"\[]+([^\]]+).+/', $v,$matches);
    if ($matches[1] and !empty(trim($matches[1]))) {
        if (filter_var($matches[1], FILTER_VALIDATE_IP)) {
            $ips[$matches[1]] = $matches[1];
        }
    }
}

if (is_array($ips)) {
    include_once(__DIR__.DIRECTORY_SEPARATOR."config.php");
    $link = mysqli_connect($host, $dbUser, $password, $db);
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
    foreach ($ips as $key => $val) {
        $query = "INSERT INTO `active_nodes` SET `ip` = INET6_ATON('{$val}'), `last_checked` = DATE_SUB(NOW(),INTERVAL 1 DAY) ON DUPLICATE KEY UPDATE `ip` = INET6_ATON('{$val}'), `active` = `active` + 1";
        $result = mysqli_query($link, $query);
    }
    $query = "DELETE FROM `active_nodes` WHERE `last_online` < DATE_SUB(NOW(), INTERVAL 10 DAY )";
    $result = mysqli_query($link, $query);

    $query = "INSERT INTO `active_nodes_stats_temp` (`amount`, `type`, `date`) VALUES ('".count($ips)."', 'Total peers', NOW())";
    $result = mysqli_query($link, $query);

    $query = "INSERT INTO `active_nodes_stats_temp` (`amount`, `type`, `date`) SELECT COUNT(*), 'Unique IPs in last 10 days', NOW() FROM `active_nodes`";
    $result = mysqli_query($link, $query);

    $query = "INSERT INTO `active_nodes_stats_temp` (`amount`, `type`, `date`) SELECT COUNT(*), '90%', NOW() FROM `active_nodes` WHERE `first_online` < DATE_SUB(now(),INTERVAL 7 DAY) AND ROUND( `active` / FLOOR((TIMESTAMPDIFF(MINUTE, `first_online`, NOW()) + 10) / 10 ) * 100 ,2) > 90";
    $result = mysqli_query($link, $query);

    $query = "INSERT INTO `active_nodes_stats_temp` (`amount`, `type`, `date`) SELECT COUNT(*), '99%', NOW() FROM `active_nodes` WHERE `first_online` < DATE_SUB(now(),INTERVAL 7 DAY) AND ROUND( `active` / FLOOR((TIMESTAMPDIFF(MINUTE, `first_online`, NOW()) + 10) / 10 ) * 100 ,2) > 99";
    $result = mysqli_query($link, $query);

    $count = 0;
    if ($result = mysqli_query($link, "SELECT COUNT(*) FROM `active_nodes_stats_temp` WHERE DATE(`date`) = DATE(DATE_SUB(NOW(),INTERVAL 1 DAY))")) {
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $count = $row['COUNT(*)'];
        }
        mysqli_free_result($result);
        if ($count > 0) {
            $types = array('90%','99%','Total peers','Unique IPs in last 10 days');
            foreach ($types as $key => $val) {
                $query = "INSERT INTO `active_nodes_stats` (`amount`, `type`, `date`) SELECT ROUND(SUM(`amount`) / COUNT(*)), '{$val}', DATE(DATE_SUB(NOW(),INTERVAL 1 DAY)) FROM `active_nodes_stats_temp` WHERE DATE(`date`) = DATE(DATE_SUB(NOW(),INTERVAL 1 DAY)) AND `type` = '{$val}'";
                $result = mysqli_query($link, $query);
            }
            $query = "DELETE FROM `active_nodes_stats_temp` WHERE DATE(`date`) = DATE(DATE_SUB(NOW(),INTERVAL 1 DAY))";
            $result = mysqli_query($link, $query);
        }
    }

}
