<?php
error_reporting(-1);
ini_set('display_errors', 'On');
// global functions
  include_once($_CONFIG["root"] . "/_includes/functions.armoury.php");
  include_once($_CONFIG["root"] . "/_includes/functions.settings.php");
function loginRequired() {
  global $_CONFIG;

  if(!isset($_SESSION["ems_login"])) {
    header("location: ".$_CONFIG["header"]."/?error=noAuth");
    exit();
  }
}

// include de CSS gebaseerd op global config
function EMSincludeCSS() {

  global $_CONFIG;
  $printresult = "";

  if(isset($_CONFIG["includes"]["css"]) && count($_CONFIG["includes"]["css"]) > 0) {
    foreach($_CONFIG["includes"]["css"] AS $stylesheetUrl){
      $printresult .=  "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$_CONFIG["header"] . $stylesheetUrl."\" />\n";
    }
  } else {
    // ERR:
  }
  return $printresult;
}

// include de JS gebaseerd op global config
function EMSincludeJS() {

  global $_CONFIG;
  $printresult = "";

  if(isset($_CONFIG["includes"]["js"]) && count($_CONFIG["includes"]["js"]) > 0) {
    foreach($_CONFIG["includes"]["js"] AS $javascriptUrl){
      $printresult .= "\t<script type=\"text/javascript\" src=\"". $_CONFIG["header"] . $javascriptUrl ."\"></script>\n";
    }
  } else {
    // ERR:
  }
  return $printresult;
}

// check de currentmodule voor een active/"" return.
function checkForActiveModule($input = null, $type = null) {

  global $_MODULES;

  $return = "";

  if(!isset($type)) {
    $type = 'module';
  }

  if(isset($input) && $input != "") {

    $type = strtolower($type);
    $input = strtolower($input);

    if($type == 'module') {
      if ($input == strtolower($_MODULES["current"]["module"])) {
        $return = "active";
      } else {
        $return = "";
      }
    } else if ($type == 'page') {
      if ($input == strtolower($_MODULES["current"]["page"])) {
        $return = "active";
      } else {
        $return = "";
      }
    }
  }

  return $return;
}

function huizingfilter($input = null) {

  /* stoute mensenfilter */
  $triggers    = array('tps:/','tp:/',"src=","src =",'<','>','><','.js',';','$','[',']','(',')',':');
  $error       = false;

  foreach ($triggers as $trigger) { // looped door de huizingtriggertriggertrigger
    if (strpos($input,$trigger) !== false) {
      $error = true;
    }
  }

  if($error == true) {
    echo "<h1>Invalid input detected. Operations ended.</h1>";
    exit();

  } else {
    return "clear";
  }

}


/* WCMS echo maar dan EMS. Is notabene mijn functie. */
function EMS_echo(&$var, $else = '') {
  return isset($var) && $var ? $var : $else;
}


function init_modules() {

  global $_CONFIG, $_MODULES;

  $RETURN = "";

  $printVertibar = "<div class=\"vertibar leftbar item\">";
  $printSubmenu = "<div class=\"menu leftbar item\">";

  if(!isset($_MODULES["current"]["module"]) || $_MODULES["current"]["module"] == "") {
    $_MODULES["current"]["module"] = "home";
  }
  if(!isset($_MODULES["current"]["page"]) || $_MODULES["current"]["page"] == "") {
    $_MODULES["current"]["page"] = "home";
  }

  // loop door main modules.
  foreach ($_MODULES["modules"] as $main_module => $modules) {

    // loop door de module entries
    foreach ($modules as $module => $mod_data) {

      $user_validated = false;

      if($mod_data->menu_level == '0') {

        if($mod_data->usergroups == null) {
          $user_validated = true;
        } else {
          $user_validated = validateCurrentUser($mod_data->usergroups);
        }

        if($user_validated == true) {
          $xCLASS = checkForActiveModule($mod_data->title, 'module');
          $printVertibar .= "<a href=\"".$mod_data->url."\" class=\"block\" title=\"".$mod_data->title."\">"
            ."<p class=\"".$xCLASS."\"><i class=\"".$mod_data->icon."\"></i></p>"
          ."</a>";

          if($xCLASS == "active") {

            $xCLASS = checkForActiveModule($mod_data->title, 'page');

            $printSubmenu .= "<a href=\"".$mod_data->url."\" class=\"block\" title=\"".$mod_data->title."\">"
              ."<p class=\"".$xCLASS."\">".$mod_data->title."</p>"
            ."</a>";
          }
        }

      } else if ($mod_data->menu_level == '1') {

        // check de parent.
        if(checkForActiveModule($mod_data->parent, 'module') == 'active') {

          if($mod_data->usergroups == null) {
            $user_validated = true;
          } else {
            $user_validated = validateCurrentUser($mod_data->usergroups);
          }

          if($user_validated == true) {

            $xCLASS = checkForActiveModule($mod_data->title, 'page');

            $printSubmenu .= "<a href=\"".$mod_data->url."\" class=\"block\" title=\"".$mod_data->title."\">"
              ."<p class=\"".$xCLASS."\">".$mod_data->title."</p>"
            ."</a>";
          }

        } else {
          // parent is niet de active, skip naar volgende
        }
      } else if ($mod_data->menu_level == '-1') {

        // niet zichtbaar : -1 = hidden
      } else {

        // niet zichtbaar : parent is geen bekende waarde.
      }

      unset($xCLASS);
    }

  }

  $printVertibar .= "</div>";
  $printSubmenu .= "</div>";

  $RETURN .= $printVertibar;
  $RETURN .= $printSubmenu;

  return $RETURN;
}

function activateModule($module = null,$page = null) {
  global $_MODULES;

  $CURRENTMODULE = array();
  $CURRENTMODULE["module"] = "home";
  $CURRENTMODULE["page"] = "home";

  if(isset($module) && $module != "") {
    $CURRENTMODULE["module"] = $module;
  }
  if(isset($page) && $page != "") {
    $CURRENTMODULE["page"] = $page;
  }

  return $CURRENTMODULE;
}

// todo
function validateCurrentUser($usergroups = null) {

  global $_SESSION;

  // default: false. !!!! TIJDELIJK TRUE !!!
  $result = true;




  // en natuurlijk..
  // If admin : result = true.

  return $result;

}
function ar_initECCCharacters() {
  global $_CONFIG, $CharGenUPLINK;

  $characterArr = array();

  $sql = "SELECT characterID, character_name 
          FROM joomla.ecc_characters 
          WHERE sheet_status <> 0 AND character_name IS NOT NULL AND character_name <> ''
          ORDER by character_name ASC;";
  $result = $CharGenUPLINK->query($sql);
  $characterArr = $result;

  return $characterArr;
}