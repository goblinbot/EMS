<?php

  // database
  $mHOST  =    ''; // host
  $mUSER  =    ''; // username
  $mPASS  =    ''; // password
  $mDB    =    ''; // databasename

  $UPLINK = mysqli_connect($mHOST, $mUSER, $mPASS, $mDB);

    // default config array
    $_CONFIG = array(
      "maintenance" => "off",
      "params",
      "modules",
      "includes" => array(),
      "header" => "/ems"
    );

    // root defines na de hoofd config
    $_CONFIG["root"] = $_SERVER["DOCUMENT_ROOT"] . $_CONFIG["header"];

    // params array vullen
    $_CONFIG["params"] = array(
      "title" => "EMS",
      "version" => "0.03.23"
    );

    // default include array vullen voor in de head tag
    $_CONFIG["includes"]["css"] = array(
      "/_includes/css/reset.css"
      ,"/_includes/css/style.css"
    );

    // default include array vullen voor in de footer
    $_CONFIG["includes"]["js"] = array(
      "/_assets/js/jquery.min.js"
      ,"/_includes/js/functions.js"
    );
