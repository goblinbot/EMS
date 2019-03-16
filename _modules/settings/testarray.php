<?php
if(!isset($_SESSION)) {
  session_start();
}

// echo "<pre>";var_dump($_SESSION);echo "</pre>";

  include_once($_SERVER["DOCUMENT_ROOT"] . "/EMS/_includes/config.global.php");
  include_once($_CONFIG["root"] . "/_includes/functions.global.php");
  include_once($_CONFIG["root"] . "/_includes/includes.php");
  $userArr = ar_initUsergroups();
  return $userArr;

  ?>