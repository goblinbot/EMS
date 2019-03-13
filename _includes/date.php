<?php
error_reporting(-1);
ini_set('display_errors', 'On');
ini_set('allow_url_fopen', 1);
$tz = 'Europe/Amsterdam';
$timestamp = time();
$dt = new DateTime("now", new DateTimeZone($tz)); //first argument "must" be a string
$dt->setTimestamp($timestamp); //adjust the object to correct timestamp
$json = file_get_contents('http://localhost:5000/api/time');
$obj = json_decode($json);
$DateTime = $obj->iDay . "-" . $obj->iMonth . "-" . $obj->iYear . $obj->iYearAfter . " " .  $dt->format('H:i:s') . "\n";;
?>
