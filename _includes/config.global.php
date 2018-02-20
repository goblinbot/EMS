<?php

  // database
  $mHOST  =    '127.0.0.1';
  $mUSER  =    'root';
  $mPASS  =    '';
  $mDB    =    'thijsboerma_ems';

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
